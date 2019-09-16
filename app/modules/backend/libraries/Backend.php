<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

  class Backend
  {

    private $ci;

    public function __construct()
    {
      $this->ci =& get_instance();
    }

    function logs_activity($data)
    {
      return $this->ci->db->insert("log_activity_admin",$data);
    }

  }
