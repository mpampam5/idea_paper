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
                              status_kontrak,
                              Sum(jumlah_paper) AS jumlah_paper,
                              FORMAT(Sum(total_harga_paper),0) AS total_harga_paper,
                              id_register,
                              nama,
                              email");
    $this->datatables->from('trans_person_trading');
    $this->datatables->join("tb_person","tb_person.id_person = trans_person_trading.id_person");
    $this->datatables->where("status_kontrak","belum");
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
                            trading_profit.status_bagi,
                            trading_profit.created");
    $this->datatables->from('trading_profit');
    $this->datatables->add_column('action','<a href="'.site_url("backend/trading/detail_profit/$1").'" id="detail_profit" class="btn btn-outline-primary"><i class="ti-file"></i> Detail</a>','id_trading_profit');
    $this->datatables->add_column('action2',
                                  '
                                    <a href="'.site_url("backend/trading/bagikan_dividen/$1").'" id="bagikan_dividen" class="btn btn-outline-primary"><i class="ti-file"></i> Bagikan Dividen</a>
                                    <a href="'.site_url("backend/trading/delete_profit/$1").'" id="hapus_profit" class="btn btn-outline-danger"><i class="ti-trash"></i> Hapus</a>
                                  ','id_trading_profit');
    return $this->datatables->generate();
}

function get_detail_profit($id)
{
  $query = $this->db->select("trading_profit.id_trading_profit,
                              trading_profit.time_add,
                              trading_profit.persentasi,
                              trading_profit.nominal,
                              trading_profit.status_bagi")
                    ->from("trading_profit")
                    ->where("id_trading_profit",$id)
                    ->where("status_bagi","sudah")
                    ->get()
                    ->row();
  return $query;

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


function cek_trans_person_trading()
{
  $dates = date("Y-m-d");
  $query = $this->db->select("trans_person_trading.id_trans_person_trading,
                              trans_person_trading.id_trading,
                              trans_person_trading.id_person,
                              Sum(trans_person_trading.jumlah_paper) AS jumlah_paper,
                              trans_person_trading.total_harga_paper,
                              trans_person_trading.status_kontrak,
                              trans_person_trading.waktu_mulai,
                              trans_person_trading.masa_aktif,
                              trans_person_trading.created")
                    ->from("trans_person_trading")
                    ->where("waktu_mulai <=","$dates")
                    ->where("status_kontrak","belum")
                    ->group_by("trans_person_trading.id_person")
                    ->get();
  return $query;
}


}
