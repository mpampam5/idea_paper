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
          $row[] = $member->is_active=="0"? "<span class='badge badge-danger'> Nonaktif</span>" : "<span class='badge badge-success'> Aktif</span>";


          $row[] = '
                      <a class="btn btn-outline-primary" href="'.site_url("backend/member/detail/personal/".enc_uri($member->id_person)."/$member->id_register").'"><i class="fa fa-file"></i> Detail & Setting</a>
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
        if ($link=="personal") {
          $query['provinsi'] = $this->db->get("wil_provinsi");
          $query['pekerjaan'] = $this->db->get("ref_pekerjaan");
        }
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
          if ($link=="personal") {
            $this->_rules_personal();
            $table = "tb_person";
            $data_update = ["nik"           =>  $this->input->post("nik",true),
                            "nama"          =>  $this->input->post("nama",true),
                            "email"         =>  $this->input->post("email",true),
                            "telepon"       =>  $this->input->post("telepon",true),
                            "jenis_kelamin" =>  $this->input->post("jenis_kelamin",true),
                            "tempat_lahir"  =>  $this->input->post("tempat_lahir",true),
                            "tanggal_lahir" =>  date("Y-m-d",strtotime($this->input->post("tanggal_lahir",true))),
                            "pekerjaan"     =>  $this->input->post("pekerjaan",true),
                            "id_provinsi"   =>  $this->input->post("provinsi",true),
                            "id_kabupaten"  =>  $this->input->post("kabupaten",true),
                            "id_kecamatan"  =>  $this->input->post("kecamatan",true),
                            "id_kelurahan"  =>  $this->input->post("kelurahan",true),
                            "alamat"  =>  $this->input->post("alamat",true)
                            ];
          }elseif ($link=="rekening") {
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


  function _rules_delete()
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
