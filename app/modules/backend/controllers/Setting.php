<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Setting extends MY_Controller{

    function index()
    {
      $this->template->set_title('Setting');
      $this->template->view('content/setting/index',array());
    }




}
