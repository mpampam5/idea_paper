<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."/modules/backend/core/MY_Controller.php";

class Trading extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Trading_model","model");
  }

  function get($title="")
  {
    $link_uri = array('info','investor','profit');
    if (in_array($title,$link_uri)) {
      $this->template->set_title('Trading');
      if ($title=="info") {
        $query['row'] = $this->model->get_info_trading();
      }elseif ($title=="investor") {
        $query['row'] = "";
      }
      $data['content_view'] = $this->load->view("content/trading/content_$title",$query,true);
      $this->template->view('content/trading/index',$data);
    }

  }


  function get_form($title="")
  {
    $link_uri = array('info','investor','profit');
    if (in_array($title,$link_uri)) {
      $this->template->set_title('Trading');
      if ($title=="info") {
        $query['row'] = $this->model->get_info_trading();
      }
      $query['action'] = site_url("backend/trading/get_action/$title");
      $data['content_view'] = $this->load->view("content/trading/form_$title",$query,true);
      $this->template->view('content/trading/index',$data);
    }
  }

  function get_action($title="")
  {
    if ($this->input->is_ajax_request()) {
      $link_uri = array('info');
      if (in_array($title,$link_uri)) {
          $json = array('success'=>false, 'alert'=>array());
          if($title=="info")
          {
            $this->_rules_info();
            $table = "trading";
            $data_update = [  "title" => $this->input->post("title",true),
                              "harga_paper" => $this->input->post("harga_paper",true),
                              "jumlah_paper" => $this->input->post("jumlah_paper",true),
            ];

            $where = ["id_trading"=>1];
          }


          $this->form_validation->set_error_delimiters('<label class="error ml-1 text-danger" style="font-size:12px">','</label>');

        if ($this->form_validation->run()) {
          $this->model->get_update($table,$data_update,$where);
          $json['success'] = true;
          $json['alert'] = "Update successfully";
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


  function detail($title="",$reg="")
  {
    $link_uri = array('info','investor','profit');
    if (in_array($title,$link_uri)) {
      if ($row = $this->model->get_detail_member($reg)) {
        $this->template->set_title('Trading');
        $query['row'] = $row;
        $data['content_view'] = $this->load->view("content/trading/detail_$title",$query,true);
        $this->template->view('content/trading/index',$data);
      }else {
        $this->_error404();
      }
      }else {
        $this->_error404();
      }

  }


  function json_investor()
  {
    $this->load->library('Datatables');
    header('Content-Type: application/json');
    echo $this->model->json_investor();
  }

function _rules_info()
{
  $this->form_validation->set_rules("title","&nbsp;*","trim|xss_clean|required|htmlspecialchars");
  $this->form_validation->set_rules("harga_paper","&nbsp;*","trim|xss_clean|required|numeric");
  $this->form_validation->set_rules("jumlah_paper","&nbsp;*","trim|xss_clean|required|numeric");
}

}
