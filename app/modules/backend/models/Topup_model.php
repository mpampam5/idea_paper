<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH."/modules/backend/core/MY_Model.php";
class Topup_model extends MY_Model{

  var $column_order = array(null, 'trans_person_deposit.id_trans_person_deposit','tb_person.id_register','tb_person.nama','trans_person_deposit.created');
  var $column_search = array('trans_person_deposit.kode_transaksi','tb_person.id_register','tb_person.nama','trans_person_deposit.created');
  var $order = array('id_trans_person_deposit'=>"DESC");
  var $select = " trans_person_deposit.id_trans_person_deposit AS id_trans_person_deposit,
                  trans_person_deposit.kode_transaksi AS kode_transaksi,
                  trans_person_deposit.id_person AS id_person,
                  trans_person_deposit.metode_pembayaran AS metode_pembayaran,
                  trans_person_deposit.nominal AS nominal,
                  trans_person_deposit.status AS status,
                  trans_person_deposit.created,
                  tb_person.id_register AS id_register,
                  tb_person.nama AS nama,
                  tb_person.email AS email,
                  ref_bank.inisial_bank AS inisial_bank,
                  config_rekening.id_rekening AS id_rekening,
                  config_rekening.id_bank AS id_bank,
                  config_rekening.nama_rekening AS nama_rekening,
                  config_rekening.no_rekening AS no_rekening";

  private function _get_datatables_query($status)
    {
      $this->db->select($this->select);
      $this->db->from("trans_person_deposit");
      $this->db->join("tb_person","tb_person.id_person = trans_person_deposit.id_person");
      $this->db->join("config_rekening","config_rekening.id_rekening = trans_person_deposit.metode_pembayaran");
      $this->db->join("ref_bank","ref_bank.id_bank = config_rekening.id_bank");
      $this->db->where("trans_person_deposit.status", $status);


        //add custom filter here
        if($this->input->post('kode_transaksi'))
        {
            $this->db->like('trans_person_deposit.kode_transaksi', $this->input->post('kode_transaksi'));
        }
        if($this->input->post('id_register'))
        {
            $this->db->like('tb_person.id_register', $this->input->post('id_register'));
        }
        if($this->input->post('nama'))
        {
            $this->db->like('tb_person.nama', $this->input->post('nama'));
        }
        if($this->input->post('created'))
        {
            $created = $this->input->post('created');
            $date = date('Y-m-d',strtotime($created));
            $this->db->like('trans_person_deposit.created', $date);
        }




        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


    public function get_datatables($status)
    {
        $this->_get_datatables_query($status);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered($status)
    {
        $this->_get_datatables_query($status);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($status)
    {
        $this->db->from("trans_person_deposit");
        $this->db->where("status", $status);
        return $this->db->count_all_results();
    }

    function topup_detail($id,$kd_trans)
    {
      $query = $this->db->select("trans_person_deposit.id_trans_person_deposit AS id_trans_person_deposit,
                                  trans_person_deposit.kode_transaksi AS kode_transaksi,
                                  trans_person_deposit.id_person AS id_person,
                                  trans_person_deposit.metode_pembayaran AS metode_pembayaran,
                                  trans_person_deposit.nominal AS nominal,
                                  trans_person_deposit.status AS status,
                                  trans_person_deposit.created AS created,
                                  trans_person_deposit.admin_approved,
                                  trans_person_deposit.time_approved,
                                  tb_admin.nama as nama_admin,
                                  tb_person.id_register AS id_register,
                                  tb_person.nama AS nama,
                                  tb_person.email AS email,
                                  ref_bank.inisial_bank AS inisial_bank,
                                  config_rekening.id_rekening AS id_rekening,
                                  config_rekening.id_bank AS id_bank,
                                  config_rekening.nama_rekening AS nama_rekening,
                                  config_rekening.no_rekening AS no_rekening")
                        ->from("trans_person_deposit")
                        ->join("tb_person","tb_person.id_person = trans_person_deposit.id_person")
                        ->join("config_rekening","config_rekening.id_rekening = trans_person_deposit.metode_pembayaran")
                        ->join("ref_bank","ref_bank.id_bank = config_rekening.id_bank")
                        ->join("tb_admin","tb_admin.id_admin = trans_person_deposit.admin_approved","left")
                        ->where("trans_person_deposit.id_trans_person_deposit", $id)
                        ->where("trans_person_deposit.kode_transaksi", $kd_trans)
                        ->get();
      return $query->row();

    }

}//end class
