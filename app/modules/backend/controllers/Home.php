<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Home extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
  }

  function index()
  {
    $this->template->set_title('Home');
    $this->template->view('content/home/index',array());
  }




}
