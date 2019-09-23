<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    // $this->load->config('my_config');
    if ($this->session->userdata('logins')!=true) {
        $this->session->sess_destroy();
        redirect(site_url("adm-panel"));
    }
    $this->load->helper("backend");
    $this->load->library(array('template','backend','form_validation','encrypt'));
  }


  function _error404()
  {
    $this->template->set_title('Page Not Found! ERROR 404');
    $this->template->view('error/error404',[]);
  }


  function config_bank($id_rekening)
  {
    $query = $this->db->select("config_rekening.id_rekening,
                                config_rekening.id_bank,
                                config_rekening.nama_rekening,
                                config_rekening.no_rekening,
                                ref_bank.inisial_bank")
                      ->from("config_rekening")
                      ->join("ref_bank","ref_bank.id_bank = config_rekening.id_bank")
                      ->where("config_rekening.id_rekening",$id_rekening)
                      ->get();
    if ($query->num_rows() > 0) {
      $data['row'] = $query->row();
      $this->template->view('config/detail_rekening',$data,false);
    }else {
      echo "total rows 0";
    }
  }


}
