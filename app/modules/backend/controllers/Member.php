<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Member extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Member_model","model");
  }

  function index()
  {
    $this->template->set_title('Member');
    $this->template->view('content/member/index',array());
  }

  function json()
  {
    if ($this->input->is_ajax_request()) {
      $list = $this->model->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $member) {
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = "<span class='text-primary' style='font-weight:bold'> $member->id_register</span>";
          $row[] = strtoupper($member->nama)."<br><i class='text-info' style='font-size:11px;'>Mulai bergabung : ".date('d/m/Y H:i', strtotime($member->created))."</i>";
          $row[] = $member->email;
          $row[] = $member->telepon;

          $row[] = '
                    <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-cog"></span> Action</button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
                              <a class="dropdown-item text-primary" href="'.site_url("backend/member/detail/personal/".enc_uri($member->id_person)."/$member->id_register").'"><i class="fa fa-file"></i> Detail</a>
                              <a class="dropdown-item text-warning" href="'.site_url("backend/member/update/$member->id_person").'"><i class="fa fa-pencil"></i> Edit</a>
                              <a class="dropdown-item text-danger" href="'.site_url("backend/member/update/$member->id_person").'"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                          </div>
                   ';

          $data[] = $row;
      }

      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->model->count_all(),
                      "recordsFiltered" => $this->model->count_filtered(),
                      "data" => $data,
              );
      //output to json format
      echo json_encode($output);
    }
  }

  function detail($link,$id="",$mem_reg="")
  {
    $link_uri = array('personal','rekening','account','delete');
    if (in_array($link,$link_uri)) {
      if ($row = $this->model->get_detail_member($id,$mem_reg)) {
        $this->template->set_title("Member");
        $query["row"] = $row;
        $data["id_person"] = $row->id_person;
        $data["id_register"] = $row->id_register;
        $data['content_view'] = $this->load->view("content/member/detail_$link",$query,true);
        $this->template->view("content/member/detail",$data);
      }else {
        $this->_error404();
      }
    }else {
      $this->_error404();
    }
  }


  function form($link,$id="",$mem_reg="")
  {
    $link_uri = array('personal','rekening','account','delete');
    if (in_array($link,$link_uri)) {
      if ($row = $this->model->get_detail_member($id,$mem_reg)) {
        $this->template->set_title("Member");
        $query["row"] = $row;
        $query['action'] = site_url("backend/member/form_act/$link/$id/$mem_reg");
        $data["id_person"] = $row->id_person;
        $data["id_register"] = $row->id_register;
        $data['content_view'] = $this->load->view("content/member/form_$link",$query,true);
        $this->template->view("content/member/detail",$data);
      }else {
        $this->_error404();
      }
    }else {
      $this->_error404();
    }
  }


  function form_act($link,$id="",$mem_reg="")
  {
    if ($this->input->is_ajax_request()) {
      $link_uri = array('personal','rekening','account','delete');
      if (in_array($link,$link_uri)) {
          $json = array('success'=>false, 'alert'=>array());

          if ($link=="rekening") {
            $this->_rules_rekening();
            $table = "trans_person_rekening";
            $data_update = ["ref_bank"        =>  $this->input->post("bank",true),
                            "no_rekening"     =>  $this->input->post("no_rekening",true),
                            "nama_rekening"   =>  $this->input->post("nama_rekening",true),
                            "kota_pembukuan"  =>  $this->input->post("kota_pembukuan",true)
                            ];
          }elseif ($link=="account") {
            $this->_rules_account();
            $table = "tb_auth";
            $this->load->helper(array("pass_has","enc_gue"));
            $token = enc_uri(date("dmYhis"));
            $password = $this->input->post("konfirmasi_password");
            $data_update = ["token"     =>  $token,
                            "password"  =>  pass_encrypt($token,$password)
                            ];
          }elseif ($link=="delete") {
            $this->_rules_delete();
            $table = "tb_person";
            $keterangan = array('admin_approved' => sess("id_admin"),
                                'approved_time' => date("Y-m-d H:i:s"),
                                'desc'      => $this->input->post("keterangan",true)
                                );
            $data_update = ["is_delete"     =>  "1",
                            "keterangan"  =>  json_encode($keterangan)
                            ];
          }

          $this->form_validation->set_error_delimiters('<label class="error ml-1 text-danger" style="font-size:12px">','</label>');
          $where = array('id_person' => dec_uri($id));


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


  function _rules_rekening()
  {
    $this->form_validation->set_rules("bank","&nbsp;*","trim|xss_clean|required");
    $this->form_validation->set_rules("no_rekening","&nbsp;*","trim|xss_clean|required|numeric");
    $this->form_validation->set_rules("nama_rekening","&nbsp;*","trim|xss_clean|htmlspecialchars|required");
    $this->form_validation->set_rules("kota_pembukuan","&nbsp;*","trim|xss_clean|htmlspecialchars|required");
  }

  function _rules_account()
  {
    $this->form_validation->set_rules("password","&nbsp;*","trim|xss_clean|required|min_length[5]");
    $this->form_validation->set_rules("konfirmasi_password","&nbsp;*","trim|xss_clean|required|matches[password]",[
      "matches"=> "* Pastikan value sama dengan password baru"
    ]);
  }


  function _rules_delete()
  {
    $this->form_validation->set_rules("password","&nbsp;*","trim|xss_clean|required|callback__cek_password");
    $this->form_validation->set_rules("keterangan","&nbsp;*","trim|xss_clean|required|htmlspecialchars");;
  }



}
