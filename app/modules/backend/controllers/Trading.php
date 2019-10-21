<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Trading extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Trading_model","model");
  }

  function get($title="")
  {
    $link_uri = array('info','investor','profit');
    if (in_array($title,$link_uri)) {
      $this->template->set_title('Trading');
      if ($title=="info") {
        $query['row'] = $this->model->get_info_trading();
      }elseif ($title=="investor") {
        $query['row'] = "";
      }elseif ($title=="profit") {
        $query['action'] = site_url("backend/trading/get_action_profit");
      }
      $data['content_view'] = $this->load->view("content/trading/content_$title",$query,true);
      $this->template->view('content/trading/index',$data);
    }

  }


  function get_form($title="")
  {
    $link_uri = array('info','investor','profit');
    if (in_array($title,$link_uri)) {
      $this->template->set_title('Trading');
      if ($title=="info") {
        $query['row'] = $this->model->get_info_trading();
      }
      $query['action'] = site_url("backend/trading/get_action/$title");
      $data['content_view'] = $this->load->view("content/trading/form_$title",$query,true);
      $this->template->view('content/trading/index',$data);
    }
  }

  function get_action($title="")
  {
    if ($this->input->is_ajax_request()) {
      $link_uri = array('info');
      if (in_array($title,$link_uri)) {
          $json = array('success'=>false, 'alert'=>array());
          if($title=="info")
          {
            $this->_rules_info();
            $table = "trading";
            $data_update = [  "title" => $this->input->post("title",true),
                              "harga_paper" => $this->input->post("harga_paper",true),
                              "jumlah_paper" => $this->input->post("jumlah_paper",true),
                              "masa_kontrak" => $this->input->post("masa_kontrak",true),
                              "keterangan" => $this->input->post("keterangan",true),
            ];

            $where = ["id_trading"=>1];
          }


          $this->form_validation->set_error_delimiters('<label class="error ml-1 text-danger" style="font-size:12px">','</label>');

        if ($this->form_validation->run()) {
          $this->model->get_update($table,$data_update,$where);
          $json['success'] = true;
          $json['alert'] = "Update successfully";
        }else {
          foreach ($_POST as $key => $value)
          {
            $json['alert'][$key] = form_error($key);
          }
        }


        echo json_encode($json);
      }
    }
  }



  function get_action_profit(){
    if ($this->input->is_ajax_request()) {
      $json = array('success'=>false, 'alert'=>array());
      $this->_rules_profit();
      $this->form_validation->set_error_delimiters('<label class="error text-danger" style="font-size:12px">','</label>');
      if ($this->form_validation->run()) {

        $insert = array('time_add' => $this->input->post("waktu",true),
                        'persentasi' => $this->input->post("persen",true),
                        'nominal' => str_replace(".","",$this->input->post("nominal",true)),
                        'status_bagi' => "belum",
                        'created' => date("Y-m-d H:i:s")
                        );

        $this->model->get_insert("trading_profit",$insert);

        $json['success'] = true;
        $json['alert'] = "successfully";
      }else {
        foreach ($_POST as $key => $value)
        {
          $json['alert'][$key] = form_error($key);
        }
      }

      echo json_encode($json);
    }
  }


  function detail($title="",$reg="")
  {
    $link_uri = array('info','investor');
    if (in_array($title,$link_uri)) {
        if ($row = $this->model->get_detail_member($reg)) {
          $this->template->set_title('Trading');
          $query['row'] = $row;
          $data['content_view'] = $this->load->view("content/trading/detail_$title",$query,true);
          $this->template->view('content/trading/index',$data);
        }else {
          $this->_error404();
        }
      }else {
        $this->_error404();
      }

  }


  function json_investor()
  {
    $this->load->library('Datatables');
    header('Content-Type: application/json');
    echo $this->model->json_investor();
  }


  function json_profit()
  {
    $this->load->library('Datatables');
    header('Content-Type: application/json');
    echo $this->model->json_profit();
  }

function _rules_info()
{
  $this->form_validation->set_rules("title","&nbsp;*","trim|xss_clean|required|htmlspecialchars");
  $this->form_validation->set_rules("harga_paper","&nbsp;*","trim|xss_clean|required|numeric");
  $this->form_validation->set_rules("jumlah_paper","&nbsp;*","trim|xss_clean|required|numeric");
  $this->form_validation->set_rules("masa_kontrak","&nbsp;*","trim|xss_clean|required|numeric");
  $this->form_validation->set_rules("keterangan","&nbsp;*","trim|xss_clean|required|htmlspecialchars");
}


function _rules_profit()
{
  $this->form_validation->set_rules("waktu","&nbsp;*","trim|xss_clean|required|htmlspecialchars");
  $this->form_validation->set_rules("persen","&nbsp;*","trim|xss_clean|required|numeric");
  $this->form_validation->set_rules("nominal","&nbsp;*","trim|xss_clean|required|callback__cek_nominal");
}

function _cek_nominal($str)
{
  if (!preg_match("/^[0-9.]*$/",$str)) {
    $this->form_validation->set_message('_cek_nominal', '* Nominal Tidak valid');
    return false;
  }else {
    return true;
  }
}


function delete_profit($id)
{
  if ($this->input->is_ajax_request()) {
    if ($row = $this->model->get_where('trading_profit',["id_trading_profit"=>$id])) {
      if ($row->status_bagi!="belum") {
        $json['success'] = "error";
        $json['alert']   = 'delete error';
      }else {
        if ($this->model->get_delete('trading_profit',["id_trading_profit"=>$id])) {
            $json['success'] = "success";
            $json['alert']   = 'delete successful';
        }
      }
    }
    echo json_encode($json);
  }
}



function bagikan_dividen($id)
{
  if ($this->input->is_ajax_request()) {
    if ($row = $this->model->get_where('trading_profit',["id_trading_profit"=>$id])) {
        $data['tgl_bagi'] = date('d/m/Y',strtotime($row->time_add));
        $data['persentasi'] = $row->persentasi;
        $data['nominal'] = $row->nominal;
        $data['action'] = site_url("backend/trading/act_dividen/".enc_uri($row->id_trading_profit));
        $this->template->view('content/trading/form_bagikan',$data,false);
    }else {
      echo "error 404";
    }

  }
}



function act_dividen($id)
{
  if ($this->input->is_ajax_request()) {
    $json = array('success'=>false, 'alert'=>array());
    $this->form_validation->set_rules("password","&nbsp;*","trim|required|callback__cek_password",[
      "required" => "* Masukkan password untuk memastikan bahwa anda telah setuju."
    ]);
    $this->form_validation->set_error_delimiters('<label class="error text-danger" style="font-size:12px">','</label>');

    if ($this->form_validation->run()) {

      if ($row_trading_profit = $this->model->get_where('trading_profit',["id_trading_profit"=>dec_uri($id)])) {
        $query = $this->model->cek_trans_person_trading();
        if ($query->num_rows()>0) {
            //START DB TRANS
            $this->db->trans_start();

            foreach ($query->result() as $row) {
              $seluruh_jumlah_paper = get_info_trading("jumlah_paper");
              $persen_paper_member = $row->jumlah_paper/$seluruh_jumlah_paper;
              $dividen = $persen_paper_member*$row_trading_profit->nominal;
              $insert_dividen = array("id_trading_profit" => dec_uri($id),
                                        "id_person" => $row->id_person,
                                        "jumlah_paper"=> $row->jumlah_paper,
                                        "dividen"=> $dividen,
                                        "persentase"=> $persen_paper_member,
                                        "created"=> date("Y-m-d H:i:s")
                                        );

            $this->model->get_insert("trading_dividen",$insert_dividen);
            //inser history
            $history = array('modul' => "trading_dividen",
                              "id_person" => $row->id_person,
                              "keterangan" => json_encode($insert_dividen),
                              'created' => date('Y-m-d H:i:s')
                            );

            $this->model->get_insert("history_all",$history);

            }
        }

        $keterangan= array('admin_approved' => sess('id_admin') ,
                            'time_approved' =>date('Y-m-d H:i:s'),
                            'description'   => "success"
                            );

        $this->model->get_update("trading_profit",["status_bagi"=>"sudah","keterangan"=>json_encode($keterangan)],["id_trading_profit"=>dec_uri($id)]);
        // Validasi DB trans
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
              {
                $this->db->trans_rollback();
                $json['alert_header'] = "error";
                $json['alert'] = "Error ! Gagal membagikan, Terjadi kesalahan";
              }else{
                $this->db->trans_commit();
                $json['alert'] = "Dividen Berhasil dibagikan ke investor";
                $json['alert_header'] = "success";
              }

      }else {
        $json['alert_header'] = "error";
        $json['alert'] = "Error ! Terjadi kesalahan";
      }


      $json['success'] = true;

    }else {
      foreach ($_POST as $key => $value)
      {
        $json['alert'][$key] = form_error($key);
      }
    }

    echo json_encode($json);
  }
}



function detail_profit($id="")
{
  if ($row = $this->model->get_detail_profit($id)) {
    $this->template->set_title('Trading');
    $data['row'] = $row;
    $this->template->view("content/trading/detail_profit",$data,false);
  }else {
    $this->_error404();
  }
}



function cek_cek()
{
  $query = $this->model->cek_trans_person_trading();
  if ($query->num_rows() > 0) {
    foreach ($query->result() as $row) {
      echo $row->jumlah_paper."<br>";
    }
  }else {
    echo "0";
  }
}





}
