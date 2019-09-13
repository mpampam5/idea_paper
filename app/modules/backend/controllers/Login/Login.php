<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {
    $data['action'] = site_url("sign-in-action");
    $this->load->view("login/index",$data);
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
      $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger" style="font-weight:bold">','</label>');


      if ($this->form_validation->run()) {
        $json["success"] = true;
        $json['valid'] = true;
        $json['url'] = site_url("backend/home");


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

}
