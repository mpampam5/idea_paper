<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('pass_has');
  }

  function index()
  {
    if ($this->session->userdata('logins')==true) {
        redirect(site_url("backend/home"),"refresh");
    }else {
      $data['action'] = site_url("sign-in-action");
      $this->load->view("login/index",$data);
    }
  }


  function action()
  {
    if ($this->input->is_ajax_request()) {
      $json = array('success' => false,
                    "valid"=>false,
                    'url'=>"",
                    'alert'=>array()
                  );
      $this->load->library("form_validation");
      $this->form_validation->set_rules("username","Username","trim|xss_clean|required");
      $this->form_validation->set_rules("password","Password","trim|required");
      $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger" style="font-weight:400;font-size:14px">','</label>');


      if ($this->form_validation->run()) {
          $json["success"] = true;

          $username = $this->input->post("username");
          $password =  $this->input->post("password");

          $query =  $this->db->get_where("tb_admin",[
                                                      "username" => $username,
                                                      "is_delete" => '0',
                                                      "is_active" => "y"
                                                    ]);

          if ($query->num_rows() > 0) {
              $row =  $query->row();

              $pwd =  $row->password;
              $token =  $row->token;

              if (pass_decrypt($token,$password,$pwd)===true) {
                $session = array('logins' => true,
                                 'id_admin' => $row->id_admin
                                );
                $this->session->set_userdata($session);

                $json['valid'] = true;
                $json['url'] = site_url("backend/home");
              }else {
                $json['alert'] = "Username Atau Password Salah";
              }
          }else {
            $json['alert'] = "Username Atau Password Salah";
          }

      }else {
        foreach ($_POST as $key => $value) {
          $json['alert'][$key] = form_error($key);
        }
      }

      echo json_encode($json);
    }


    //
    // echo json_encode($json);
  }

  function logout()
  {
    $this->session->sess_destroy();
    redirect(site_url("adm-panel"),'refresh');
  }


}
