<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Member_verif extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Member_verif_model","model");
  }

  function index()
  {
    $this->template->set_title('Member');
    $this->template->view('content/member_verif/index',array());
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
          $row[] = "<span class='text-primary' style='font-weight:600'> $member->id_register</span>"."<br><span class='text-danger' style='font-size:11px;'>WAKTU REGISTER : ".date('d/m/Y H:i', strtotime($member->created))."</span>";
          $row[] = "<span class='text-danger' style='font-size:11px;'>NIK : ".$member->nik."</span><br><span style='font-weight:600'>".strtoupper($member->nama)."</span>";
          $row[] = $member->email;
          $row[] = $member->telepon;


          $row[] = '
                      <a class="btn btn-outline-primary" href="'.site_url("backend/member_verif/detail/personal/".enc_uri($member->id_person)."/$member->id_register").'"><i class="fa fa-file"></i> Detail & Approved</a>
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
    $link_uri = array('personal');
    if (in_array($link,$link_uri)) {
      if ($row = $this->model->get_detail_member($id,$mem_reg)) {
        $this->template->set_title("Member");
        $query["row"] = $row;
        $data["id_person"] = $row->id_person;
        $data["id_register"] = $row->id_register;
        $data['content_view'] = $this->load->view("content/member_verif/detail_$link",$query,true);
        $this->template->view("content/member_verif/detail",$data);
      }else {
        $this->_error404();
      }
    }else {
      $this->_error404();
    }
  }


  function form($link,$id="",$mem_reg="")
  {
    $link_uri = array('verifikasi');
    if (in_array($link,$link_uri)) {
      if ($row = $this->model->get_detail_member($id,$mem_reg)) {
        $this->template->set_title("Member");
        $query["row"] = $row;
        $query['action'] = site_url("backend/member_verif/form_act/$link/$id/$mem_reg");
        if ($link=="personal") {
          $query['provinsi'] = $this->db->get("wil_provinsi");
          $query['pekerjaan'] = $this->db->get("ref_pekerjaan");
        }
        $data["id_person"] = $row->id_person;
        $data["id_register"] = $row->id_register;
        $data['content_view'] = $this->load->view("content/member_verif/form_$link",$query,true);
        $this->template->view("content/member_verif/detail",$data);
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
      $link_uri = array('verifikasi');
      if (in_array($link,$link_uri)) {
          $json = array('success'=>false, 'alert'=>array());
          if ($link=="verifikasi") {
            $this->_rules_verifikasi();
            $table = "tb_person";
            $keterangan['add']= array('admin_approved' => sess("id_admin"),
                                'approved_time'  => date("Y-m-d H:i:s"),
                                'desc'           => $this->input->post("keterangan",true)
                                );
            $data_update = ["is_verifikasi"     =>  "1",
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

  function _rules_personal()
  {
      $this->form_validation->set_rules("nik","&nbsp;*","trim|xss_clean|required|min_length[16]|max_length[16]|numeric|callback__cek_nik[".$this->input->post("nik_lama",true)."]");
      $this->form_validation->set_rules("nama","&nbsp;*","trim|xss_clean|htmlspecialchars|required");
      $this->form_validation->set_rules("email","&nbsp;*","trim|xss_clean|required|htmlspecialchars|valid_email|callback__cek_email[".$this->input->post("email_lama",true)."]");
      $this->form_validation->set_rules("telepon","&nbsp;*","trim|xss_clean|required|numeric");
      $this->form_validation->set_rules("tempat_lahir","&nbsp;*","trim|xss_clean|htmlspecialchars|required");
      $this->form_validation->set_rules("tanggal_lahir","&nbsp;*","trim|xss_clean|required");
      $this->form_validation->set_rules("jenis_kelamin","&nbsp;*","trim|xss_clean|htmlspecialchars|required");
      $this->form_validation->set_rules("pekerjaan","&nbsp;*","trim|xss_clean|required");
      $this->form_validation->set_rules("provinsi","&nbsp;*","trim|xss_clean|required");
      $this->form_validation->set_rules("kabupaten","&nbsp;*","trim|xss_clean|required");
      $this->form_validation->set_rules("kecamatan","&nbsp;*","trim|xss_clean|required");
      $this->form_validation->set_rules("kelurahan","&nbsp;*","trim|xss_clean|required");
      $this->form_validation->set_rules("alamat","&nbsp;*","trim|xss_clean|htmlspecialchars|required");
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


  function _rules_verifikasi()
  {
    $this->form_validation->set_rules("password","&nbsp;*","trim|xss_clean|required|callback__cek_password");
    $this->form_validation->set_rules("keterangan","&nbsp;*","trim|xss_clean|required|htmlspecialchars");;
  }


  function _cek_nik($str,$nik_lama)
    {
      $row =  $this->db->get_where("tb_person",["nik !="=>$nik_lama,"nik"=>$str,"is_delete"=>"0"]);
      if ($row->num_rows() > 0) {
        $this->form_validation->set_message('_cek_nik', '* Sudah terpakai member lain');
        return false;
      }else {
        return true;
      }
    }

    function _cek_email($str,$email_lama)
      {
        $row =  $this->db->get_where("tb_person",["email !="=>$email_lama,"email"=>$str,"is_delete"=>"0"]);
        if ($row->num_rows() > 0) {
          $this->form_validation->set_message('_cek_email', '* Sudah terpakai member lain');
          return false;
        }else {
          return true;
        }
      }



      function kabupaten(){
            $propinsiID = $_GET['id'];
            $kabupaten   = $this->db->get_where('wil_kabupaten',array('province_id'=>$propinsiID));
            echo '<option value="">-- Pilih Kabupaten/Kota --</option>';
            foreach ($kabupaten->result() as $k)
            {
                echo "<option value='$k->id'>$k->name</option>";
            }
        }


        function kecamatan(){
           $kabupatenID = $_GET['id'];
           $kecamatan   = $this->db->get_where('wil_kecamatan',array('regency_id'=>$kabupatenID));
           echo '<option value="">-- Pilih Kecamatan --</option>';
           foreach ($kecamatan->result() as $k)
           {
               echo "<option value='$k->id'>$k->name</option>";
           }
       }

       function kelurahan(){
            $kecamatanID  = $_GET['id'];
            $desa         = $this->db->get_where('wil_kelurahan',array('district_id'=>$kecamatanID));
            echo '<option value="">-- Pilih Kelurahan/Desa --</option>';
            foreach ($desa->result() as $d)
            {
                echo "<option value='$d->id'>$d->name</option>";
            }
        }

}
