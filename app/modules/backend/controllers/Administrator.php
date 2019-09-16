<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Administrator extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Administrator_model","model");
  }

  function _rules()
  {
    $this->form_validation->set_rules("nama","Nama","trim|xss_clean|required");
    $this->form_validation->set_rules("telepon","Telepon","trim|xss_clean|required|numeric");
    $this->form_validation->set_rules("email","Email","trim|xss_clean|required|valid_email");
    $this->form_validation->set_error_delimiters('<label class="error mt-2 text-danger">','</label>');
  }

  function index()
  {
    $this->template->set_title('Administrator');
    $this->template->view('content/administrator/index',array());
  }


  function json()
  {
    if ($this->input->is_ajax_request()) {
      $list = $this->model->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $admin) {
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = $admin->nama;
          $row[] = $admin->username;
          $row[] = $admin->email;
          $row[] = ($admin->level=="superadmin")?'<span class="badge badge-success badge-pill"> '.$admin->level.'</span>':'<span class="badge badge-info badge-pill text-white"> '.$admin->level.'</span>';

          $row[] = ($admin->is_active=="y") ? '<span class="text-success"> Active</span>':'<span class="text-danger">Not active</span>';

          $row[] = '<a href="" class="text-dark" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Reset Password"><i class="fa fa-key"></i></a>&nbsp;&nbsp;
                    <a href="" class="text-primary" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Detail"><i class="fa fa-file"></i></a>&nbsp;&nbsp;
                    <a href="'.site_url("backend/administrator/update/$admin->id_admin").'" class="text-warning" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Update"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                    <a href="'.site_url("backend/administrator/delete/$admin->id_admin").'" id="delete" class="text-danger" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Remove"><i class="fa fa-trash"></i></a>
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


  function add()
  {
    $this->template->set_title("Administrator");
    $data = [
              "action"      => site_url("backend/administrator/add_action"),
              "button"      => "add",
              "nama"        => set_value("nama"),
              "email"       => set_value("email"),
              "telepon"     => set_value("telepon"),
              "is_active"     => set_value("is_active"),
              "level"     => set_value("level"),
            ];
    $this->template->view("content/administrator/form",$data);
  }

  function add_action()
  {
    if ($this->input->is_ajax_request()) {
        $json = array('success'=>false, 'alert'=>array());
        $this->form_validation->set_rules("level","Level","trim|xss_clean|required");
        $this->form_validation->set_rules("is_active","Is Active","trim|xss_clean|required");
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[5]|is_unique[tb_admin.username]',[
          "is_unique" => "Ganti Username Lain"
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('v_password', 'Konfirmasi Password', 'required|matches[password]');
        $this->_rules();
        if ($this->form_validation->run()) {
          $this->load->helper('pass_has');
          $data = [
                    "nama"        => $this->input->post("nama",true),
                    "telepon"     => $this->input->post("telepon",true),
                    "email"       => $this->input->post("email",true),
                    "token"       => date('dmYhis'),
                    "username"    => $this->input->post("username", true),
                    "password"    => pass_encrypt(date('dmYhis'),$this->input->post("v_password")),
                    "level"       => $this->input->post('level'),
                    "is_active"   => $this->input->post('is_active'),
                    "created"     => date('Y-m-d h:i:s')
                  ];
          $this->model->get_insert("tb_admin",$data);

          $json['alert'] = "add new data successful";
          $json['success'] =  true;
        }else {
          foreach ($_POST as $key => $value)
            {
              $json['alert'][$key] = form_error($key);
            }
        }

        echo json_encode($json);
    }
  }


  function update($id)
  {
    if ($row = $this->model->get_where("tb_admin",["id_admin"=>$id])) {
        $this->template->set_title("Administrator");
        $data = [
                  "action"      => site_url("backend/administrator/update_action/$id"),
                  "button"      => "update",
                  "nama"        => set_value("nama",$row->nama),
                  "email"       => set_value("email",$row->email),
                  "telepon"     => set_value("telepon",$row->telepon),
                  "is_active"     => set_value("is_active",$row->is_active),
                  "level"     => set_value("level",$row->level),
                ];
        $this->template->view("content/administrator/form",$data);
    }else {
      $this->_error404();
    }
  }

  function update_action($id)
  {
    if ($this->input->is_ajax_request()) {
        $json = array('success'=>false, 'alert'=>array());
        $this->form_validation->set_rules("level","Level","trim|xss_clean|required");
        $this->form_validation->set_rules("is_active","Is Active","trim|xss_clean|required");
        $this->_rules();
        if ($this->form_validation->run()) {
          $data = [
                    "nama"    => $this->input->post("nama",true),
                    "telepon" => $this->input->post("telepon",true),
                    "email"   => $this->input->post("email",true),
                    "level"       => $this->input->post('level'),
                    "is_active"   => $this->input->post('is_active'),
                    "modified"     => date('Y-m-d h:i:s')
                  ];
          $this->model->get_update("tb_admin",$data,["id_admin"=>$id]);
          $json['alert'] = "update data successful";
          $json['success'] =  true;
        }else {
          foreach ($_POST as $key => $value)
            {
              $json['alert'][$key] = form_error($key);
            }
        }

        echo json_encode($json);
    }
  }


  function delete($id)
{
  if ($this->input->is_ajax_request()) {
    if ($this->model->get_update('tb_admin',['is_delete'=>'1'],["id_admin"=>$id])) {
        $json['success'] = "success";
        $json['alert']   = 'delete successful';
    }
    echo json_encode($json);
  }
}




}
