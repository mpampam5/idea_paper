<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('sess'))
{
  function sess($str)
  {
     $ci=& get_instance();
    return $ci->session->userdata($str);
  }
}

if ( ! function_exists('profile'))
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

if ( ! function_exists('format_rupiah'))
{
  function format_rupiah($int)
  {
    return number_format($int, 0, ',', '.');
  }
}

function wilayah_indonesia($table,$where){

  $ci = get_instance();
  $query =  $ci->db->get_where($table,$where);
  if ($query->num_rows() > 0) {
      return $query->row()->name;
  }else {
    return "data wilayah tidak di temukan";
  }

}
