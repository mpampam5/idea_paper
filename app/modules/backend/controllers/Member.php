<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Member extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Member_model","model");
  }

  function index()
  {
    $this->template->set_title('Member');
    $this->template->view('content/member/index',array());
  }

  function json()
  {
    if ($this->input->is_ajax_request()) {
      $list = $this->model->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $member) {
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = "<span class='text-primary' style='font-weight:bold'> $member->id_register</span>";
          $row[] = $member->nama."<br><i class='text-info' style='font-size:11px;'>Mulai bergabung : ".date('d/m/Y H:i', strtotime($member->created))."</i>";
          $row[] = $member->email;
          $row[] = $member->telepon;

          $row[] = '
                    <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="fa fa-cog"></span> Action</button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
                              <a class="dropdown-item text-primary" href="'.site_url("backend/member/update/$member->id_person").'"><i class="fa fa-file"></i> Detail</a>
                              <a class="dropdown-item text-warning" href="'.site_url("backend/member/update/$member->id_person").'"><i class="fa fa-pencil"></i> Edit</a>
                              <a class="dropdown-item text-danger" href="'.site_url("backend/member/update/$member->id_person").'"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                          </div>
                   ';

          $data[] = $row;
      }

      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->model->count_all(),
                      "recordsFiltered" => $this->model->count_filtered(),
                      "data" => $data,
              );
      //output to json format
      echo json_encode($output);
    }
  }

}
