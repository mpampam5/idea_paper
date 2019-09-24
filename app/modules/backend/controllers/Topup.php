<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Topup extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Topup_model","model");
  }

  function approved()
  {
    $this->template->set_title('Top up');
    $this->template->view('content/topup/approved',array());
  }


  function json_approved()
  {

      $list = $this->model->get_datatables("success");
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $topup) {
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = "<span style='font-weight:bold'> $topup->kode_transaksi</span>";
          $row[] = "<b>Rp.".format_rupiah($topup->nominal)."</b>
                    <br><span class='text-info' style='font-size:11px;line-height:18px'><i class='fa fa-calendar'></i> WAKTU ".date('d/m/Y H:i', strtotime($topup->created))."</span>
                    <br><a href='".site_url("backend/topup/config_bank/$topup->id_rekening")."' id='config_rekening'><span class='text-info' style='font-size:11px;'><i class='fa fa-link'></i> TRANSFER KE BANK ".strtoupper($topup->inisial_bank)."</span></a>";
          $row[] = "<b>".strtoupper($topup->nama)."</b><br><span class='text-info' style='font-size:11px;'>ID.REG : ".$topup->id_register."</span>";


          $row[] = '
                    <div class="btn-group">
                      <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-cog"></span> Action</button>
                      <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
                        <a class="dropdown-item text-primary" href="'.site_url("backend/topup/detail/$topup->id_trans_person_deposit/$topup->kode_transaksi/success").'"><i class="fa fa-file"></i> Detail</a>
                        <a class="dropdown-item text-danger" id="delete" href="'.site_url("backend/topup/delete/$topup->id_trans_person_deposit/$topup->kode_transaksi").'"><i class="fa fa-trash"></i> Delete</a>
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
    $this->template->set_title('Top up');
    $this->template->view('content/topup/pending',array());
  }


  function json_pending()
  {

      $list = $this->model->get_datatables("proses");
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $topup) {
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = "<span style='font-weight:bold'> $topup->kode_transaksi</span>";
          $row[] = "<b>Rp.".format_rupiah($topup->nominal)."</b>
                    <br><span class='text-info' style='font-size:11px;line-height:18px'><i class='fa fa-calendar'></i> WAKTU ".date('d/m/Y H:i', strtotime($topup->created))."</span>
                    <br><a href='".site_url("backend/topup/config_bank/$topup->id_rekening")."' id='config_rekening'><span class='text-info' style='font-size:11px;'><i class='fa fa-link'></i> TRANSFER KE BANK ".strtoupper($topup->inisial_bank)."</span></a>";
          $row[] = "<b>".strtoupper($topup->nama)."</b><br><span class='text-info' style='font-size:11px;'>ID.REG : ".$topup->id_register."</span>";


          $row[] = '
                    <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-cog"></span> Action</button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
                              <a class="dropdown-item text-primary" href="'.site_url("backend/topup/detail/$topup->id_trans_person_deposit/$topup->kode_transaksi/proses").'"><i class="fa fa-file"></i> Detail</a>
                              <a class="dropdown-item text-success" id="approved" href="'.site_url("backend/topup/approved_alert/$topup->id_trans_person_deposit/$topup->kode_transaksi").'"><i class="fa fa-check"></i> Approved</a>
                            </div>
                          </div>
                   ';

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
      if ($row = $this->model->topup_detail($id,$kd_trans)) {
        $this->template->set_title('Top up');
        $data['title_slug'] = ($status == "success") ? "Approved" : "Menunggu verifikasi";
        $data['rows'] = $row;
        $this->template->view('content/topup/detail',$data);
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
      if ($row = $this->model->topup_detail($id,$kd_trans)) {
        $data['action'] = site_url("backend/topup/act_approved/$row->id_trans_person_deposit/$row->kode_transaksi");
        $data['rows'] = $row;
        $this->template->view('content/topup/form_approved',$data,false);
      }else {
        $this->_error404();
      }
    }
  }

  function act_approved($id,$kd_trans)
  {
    if ($this->input->is_ajax_request()) {
      $json = array('success'=>false, 'alert'=>array());
      $this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric');
      $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|xss_clean|htmlspecialchars');
      $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger" style="font-size:11px">','</label>');
      if ($this->form_validation->run()) {
        $data = [
                  "status"    => "success",
                  "nominal"    => $this->input->post("nominal",true),
                  "admin_approved" => sess('id_admin'),
                  "keterangan"   => $this->input->post("keterangan",true),
                  "time_approved"     => date('Y-m-d h:i:s')
                ];
        $this->model->get_update("trans_person_deposit",$data,["id_trans_person_deposit"=>$id,"kode_transaksi"=>$kd_trans]);
        $json['success'] = true;
        $json['alert']   = 'Approved successful';
      }else {
        foreach ($_POST as $key => $value)
          {
            $json['alert'][$key] = form_error($key);
          }
      }

      echo json_encode($json);
    }
  }


  function delete($id,$kd_trans)
  {
    if ($this->input->is_ajax_request()) {
      if ($row = $this->model->topup_detail($id,$kd_trans)) {
        $data['action'] = site_url("backend/topup/act_delete/$row->id_trans_person_deposit/$row->kode_transaksi");
        $data['rows'] = $row;
        $this->template->view('content/topup/form_delete',$data,false);
      }else {
        $this->_error404();
      }
    }
  }

  function act_delete($id,$kd_trans)
  {
    if ($this->input->is_ajax_request()) {
      $json = array('success'=>false, 'alert'=>array());
      $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|xss_clean|htmlspecialchars');
      $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger" style="font-size:11px">','</label>');
      if ($this->form_validation->run()) {
        $data = [
                  "status"    => "delete",
                  "keterangan"   => "keterangan = ".$this->input->post("keterangan",true)." | admin_approved =".sess('id_admin').",".profile("nama")." | waktu = ".date('Y-m-d h:i:s'),
                ];
        $this->model->get_update("trans_person_deposit",$data,["id_trans_person_deposit"=>$id,"kode_transaksi"=>$kd_trans]);
        $json['success'] = true;
        $json['alert']   = 'Delete successful';
      }else {
        foreach ($_POST as $key => $value)
          {
            $json['alert'][$key] = form_error($key);
          }
      }

      echo json_encode($json);
    }
  }


} //end class
