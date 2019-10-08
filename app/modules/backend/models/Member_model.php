<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH."/modules/backend/core/MY_Model.php";
class Member_model extends MY_Model{

  var $column_order = array(null, 'id_register','nama','email','telepon','is_active');
  var $column_search = array('id_register','nama','email','telepon');
  var $order = array('id_person'=>"DESC");


  private function _get_datatables_query()
    {


      $this->db->from("tb_person");
      $this->db->where("is_delete !=", "1");
      $this->db->where("is_complate", "1");
      $this->db->where("is_verifikasi", "1");


        //add custom filter here
        if($this->input->post('id_register'))
        {
            $this->db->like('id_register', $this->input->post('id_register'));
        }
        if($this->input->post('nama'))
        {
            $this->db->like('nama', $this->input->post('nama'));
        }
        if($this->input->post('email'))
        {
            $this->db->like('email', $this->input->post('email'));
        }
        if($this->input->post('telepon'))
        {
            $this->db->like('telepon', $this->input->post('telepon'));
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


    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from("tb_person");
        $this->db->where("is_complate", "1");
        $this->db->where("is_verifikasi", "1");
        return $this->db->count_all_results();
    }


    function get_detail_member($id,$mem_reg)
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
                                  tb_person.foto,
                                  tb_person.alamat,
                                  tb_person.id_provinsi,
                                  tb_person.id_kabupaten,
                                  tb_person.id_kecamatan,
                                  tb_person.id_kelurahan,
                                  tb_person.is_delete,
                                  tb_person.is_verifikasi,
                                  tb_person.created,
                                  tb_person.modified,
                                  tb_auth.username,
                                  trans_person_rekening.nama_rekening,
                                  trans_person_rekening.no_rekening,
                                  trans_person_rekening.kota_pembukuan,
                                  trans_person_rekening.ref_bank,
                                  ref_bank.inisial_bank")
                        ->from("tb_person")
                        ->join("tb_auth","tb_auth.id_person = tb_person.id_person","left")
                        ->join("trans_person_rekening","trans_person_rekening.id_person = tb_person.id_person","left")
                        ->join("ref_bank","ref_bank.id_bank = trans_person_rekening.ref_bank","left")
                        ->where("tb_person.id_person",dec_uri($id))
                        ->where("tb_person.id_register",$mem_reg)
                        ->where("tb_person.is_delete","0")
                        ->get()
                        ->row();
        return $query;
    }



}
