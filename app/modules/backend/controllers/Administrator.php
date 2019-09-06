<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Administrator extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
  }

  function index()
  {
    $this->template->set_title('Administrator');
    $this->template->view('content/administrator/index',array());
  }




}
