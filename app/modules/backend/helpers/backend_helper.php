<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('sess'))
{
  function sess($str)
  {
     $ci=& get_instance();
    return $ci->session->userdata($str);
  }
}

if ( ! function_exists('test_method'))
{
  function profile($field)
  {
     $ci=& get_instance();
     $query = $ci->db->get_where("tb_admin",['id_admin'=>$ci->session->userdata('id_admin')]);
     if ($query->num_rows()> 0) {
       return $query->row()->$field;
     }else {
       return "Error Helper";
     }
  }
}
