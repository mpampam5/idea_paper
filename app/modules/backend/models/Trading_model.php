<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH."/modules/backend/core/MY_Model.php";
class Trading_model extends MY_Model{

function get_info_trading()
{
  $query = $this->db->get_where("trading",['id_trading'=>1]);
  return $query->row();
}


function json_investor()
{
  $this->datatables->select("id_trans_person_trading,
                              trans_person_trading.id_person,
                              Sum(jumlah_paper) AS jumlah_paper,
                              FORMAT(Sum(total_harga_paper),0) AS total_harga_paper,
                              id_register,
                              nama,
                              email");
    $this->datatables->from('trans_person_trading');
    $this->datatables->join("tb_person","tb_person.id_person = trans_person_trading.id_person");
    $this->datatables->group_by('trans_person_trading.id_person');
    $this->datatables->add_column('action','<a href="'.site_url("backend/trading/detail/investor/$1").'" class="btn btn-outline-warning"><i class="ti-file"></i> Detail</a>','id_register');
    return $this->datatables->generate();
}



function json_profit()
{
  $this->datatables->select("trading_profit.id_trading_profit,
                            DATE_FORMAT(trading_profit.time_add,'%d/%m/%Y') AS time_add,
                            trading_profit.persentasi,
                            FORMAT(trading_profit.nominal,0) AS nominal,
                            trading_profit.keterangan,
                            trading_profit.created");
    $this->datatables->from('trading_profit');
    $this->datatables->add_column('action','<a href="'.site_url("backend/trading/detail/investor/$1").'" class="btn btn-outline-warning"><i class="ti-file"></i> Detail</a>','id_trading_profit');
    return $this->datatables->generate();
}



function get_detail_member($mem_reg)
{
  $query = $this->db->select("tb_person.id_person,
                              tb_person.id_register,
                              tb_person.nik,
                              tb_person.nama,
                              tb_person.tempat_lahir,
                              tb_person.tanggal_lahir,
                              tb_person.jenis_kelamin,
                              tb_person.pekerjaan,
                              tb_person.telepon,
                              tb_person.email,
                              tb_person.alamat,
                              tb_person.id_provinsi,
                              tb_person.id_kabupaten,
                              tb_person.id_kecamatan,
                              tb_person.id_kelurahan,
                              tb_person.is_delete,
                              tb_person.is_verifikasi,
                              tb_person.is_active,
                              tb_person.created,
                              tb_person.modified,
                              tb_person.keterangan,
                              tb_person.keterangan_active,
                              tb_auth.username,")
                    ->from("tb_person")
                    ->join("tb_auth","tb_auth.id_person = tb_person.id_person","left")
                    ->where("tb_person.id_register",$mem_reg)
                    ->where("tb_person.is_delete","0")
                    ->where("tb_person.is_verifikasi","1")
                    ->get()
                    ->row();
    return $query;
}


}
