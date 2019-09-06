<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    // $this->load->config('my_config');
    $this->load->library(array('backend/template','form_validation','encrypt'));
  }


  function _error404()
  {
    $this->template->set_title('Page Not Found! ERROR 404');
    $this->template->view('error/error404',[]);
  }


}
