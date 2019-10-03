<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Withdraw extends MY_Controller{
  public function __construct()
  {
    parent::__construct();
    $this->load->model("Withdraw_model","model");
  }

  function approved()
  {
    $this->template->set_title('Withdraw');
    $this->template->view('content/withdraw/approved',array());
  }


  function json_approved()
  {

      $list = $this->model->get_datatables("success");
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $withdraw) {
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = "<span style='font-weight:bold'> $withdraw->kode_transaksi</span>
                    <br><span class='text-info' style='font-size:11px;line-height:20px'><i class='fa fa-calendar'></i> WAKTU ".date('d/m/Y H:i', strtotime($withdraw->created))."</span>";
          $row[] = "<b>Rp.".format_rupiah($withdraw->nominal)."</b>";
          $row[] = "<b>".strtoupper($withdraw->nama)."</b><br><span class='text-info' style='font-size:11px;'>ID.REG : ".$withdraw->id_register."</span>";


          $row[] = '
                    <div class="btn-group">
                      <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-cog"></span> Action</button>
                      <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
                        <a class="dropdown-item text-primary" href="'.site_url("backend/withdraw/detail/".enc_uri($withdraw->id_trans_withdraw)."/$withdraw->kode_transaksi/success").'"><i class="fa fa-file"></i> Detail</a>
                        
                      </div>
                    </div>
                   ';

          $data[] = $row;
      }

      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->model->count_all("success"),
                      "recordsFiltered" => $this->model->count_filtered("success"),
                      "data" => $data,
              );
      //output to json format
      echo json_encode($output);

  }

  function pending()
  {
    $this->template->set_title('Withdraw');
    $this->template->view('content/withdraw/pending',array());
  }


  function json_pending()
  {

      $list = $this->model->get_datatables("proses");
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $withdraw) {
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = "<span style='font-weight:bold'> $withdraw->kode_transaksi</span>
                    <br><span class='text-info' style='font-size:11px;line-height:20px'><i class='fa fa-calendar'></i> WAKTU ".date('d/m/Y H:i', strtotime($withdraw->created))."</span>";
          $row[] = "<b>Rp.".format_rupiah($withdraw->nominal)."</b>";
          $row[] = "<b>".strtoupper($withdraw->nama)."</b><br><span class='text-info' style='font-size:11px;'>ID.REG : ".$withdraw->id_register."</span>";


          $row[] = '    <a class="btn btn-outline-primary btn-sm" href="'.site_url("backend/withdraw/detail/".enc_uri($withdraw->id_trans_withdraw)."/$withdraw->kode_transaksi/proses").'"><i class="ti-file"></i> Detail & Approved</a>';

          $data[] = $row;
      }

      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->model->count_all("proses"),
                      "recordsFiltered" => $this->model->count_filtered("proses"),
                      "data" => $data,
              );
      //output to json format
      echo json_encode($output);

  }

  function detail($id="",$kd_trans="",$status="")
  {
    $in_status = array('success','proses');

    if (in_array($status,$in_status)) {
      if ($row = $this->model->withdraw_detail($id,$kd_trans)) {
        $this->template->set_title('Withdraw');
        $data['title_slug'] = ($status == "success") ? "Approved" : "Menunggu verifikasi";
        $data['rows'] = $row;
        $this->template->view('content/withdraw/detail',$data);
      }else {
        $this->_error404();
      }

    }else {
      $this->_error404();
    }
  }

  function approved_alert($id,$kd_trans)
  {
    if ($this->input->is_ajax_request()) {
      if ($row = $this->model->withdraw_detail($id,$kd_trans)) {
        $data['action'] = site_url("backend/withdraw/act_approved/".enc_uri($row->id_trans_withdraw)."/$row->kode_transaksi");
        $data['rows'] = $row;
        $this->template->view('content/withdraw/form_approved',$data,false);
      }else {
        echo "error404";
      }
    }
  }

  function act_approved($id,$kd_trans)
  {
    if ($this->input->is_ajax_request()) {
      $json = array('success'=>false, 'alert'=>array());
      $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|xss_clean|htmlspecialchars');
      $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger" style="font-size:11px">','</label>');
      if ($this->form_validation->run()) {
        $data = [
                  "status"    => "success",
                  "admin_approved" => sess('id_admin'),
                  "keterangan"   => $this->input->post("keterangan",true),
                  "time_approved"     => date('Y-m-d h:i:s')
                ];
        if ($this->model->get_update("trans_person_withdraw",$data,["id_trans_withdraw"=>dec_uri($id),"kode_transaksi"=>$kd_trans])) {
          $json['success'] = true;
          $json['alert']   = 'Approved successful';
        }else {
          $json['success'] = true;
          $json['alert']   = 'Approved error';
        }

      }else {
        foreach ($_POST as $key => $value)
          {
            $json['alert'][$key] = form_error($key);
          }
      }

      echo json_encode($json);
    }
  }







}
