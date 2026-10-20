<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Months extends CI_Controller
{


	function __construct()
	{

		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('date');

		$this->load->model('user_model');
		if ($this->session->userdata('user_id') === null) {
			redirect('signin');
		}
	}



	public function index()
	{

		$data['month'] = $this->user_model->getResult('month');
		$data['page'] = 'admin_details/months/index';
		$this->load->view('admin', $data);
	}


	public function save()
	{
		if ($_POST) {

			$data['month'] = $this->input->post('month');
			$return = $this->user_model->saveAry('month', $data);
			if ($return) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('months');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('months');
			}
		}
	}

	public function getData()
	{

		$month_id = $this->input->post('month_id');
		$user_id = $this->input->post('user_id');
		// print_r($user_id);
		// die;
		$where = ['id' => $month_id];
		$we = ['id' => $user_id];

		$month_data = $this->user_model->getRow('month', $where);
		$userdata = $this->user_model->getRow('users', $we);
		$created_at = $userdata->created_at;


		$there = ['date_id' => $month_id, 'userid' => $user_id];
		$userdata = $this->user_model->getAry('finencial_month', '', '', '', $there);

		$start_month = $month_data->starting_month;

		$ending_month = $month_data->ending_month;
		$user_create_date = date('Y-m', strtotime($created_at));

		$date1 = date('Y-m', strtotime($start_month));
		$date2 = date('Y-m', strtotime($ending_month));

		if ($date1 < $date2) {
			$past = $date1;
			$future = $date2;
		} else {
			$past = $date2;
			$future = $date1;
		}

		$months = array();
		for ($i = $past; $past <= $future; $i++) {
			$timestamp = strtotime($past . '-1');
			if ($user_create_date > $future) {
				$months;
			} else {
				$months[] = date('F Y', $timestamp);
			}
			$past = date('Y-m', strtotime('+1 month', $timestamp));
		}
		$data['months'] = $months;

		$data['userinfo'] = $userdata;

		echo json_encode(['status' => true, 'data' => $data]);
	}


	public function getStatus()
	{

		$month_id = $this->input->post('month_id');
		$user_id = $this->input->post('user_id');
		$current = $this->input->post('m_id');
		$status = $this->input->post('status');
		$gst = $this->input->post('gst');
		$data['date_id'] = $month_id;
		$data['userid'] = $user_id;
		$data['date_id'] = $month_id;
		$data['current'] = $current;
		$data['gst'] = $gst;
		$data['status'] = $status;

		$where = ['userid' => $user_id, 'date_id' => $month_id, 'current' => $current, 'gst' => $gst];
		$fin = $this->user_model->getRow('finencial_month', $where);
		if ($fin) {
			$result = $this->user_model->updateAry('finencial_month', $data, $where);
		} else {
			$result = $this->user_model->saveAry('finencial_month', $data);
		};


		echo json_encode(['status' => true, 'data' => $result]);
	}


	// public function search()
	// {
	// 	$role_id = $this->session->userdata('roles');
	// 	$name = $this->input->post('name');
	// 	$number = $this->input->post('number');
	// 	$referral = $this->input->post('ref');
	// 	if ($role_id == 1) {
	// 		$sql = "userType!='1' AND status ='1' AND name='$name' AND number='$number' AND referral='$referral'";
	// 		$userlist = $this->user_model->getAry('users', '', '', '', $sql);
	// 	} else {
	// 		if ($role_id == 2) {
	// 			$sql = "userType!='2' AND status ='1' AND name='$name' AND number='$number' AND referral='$referral'";
	// 			$userlist = $this->user_model->getAry('users', '', '', '', $sql);
	// 		}
	// 	}
	// 	echo json_encode(['status' => true, 'data' => $userlist]);
	// }







	// public function do_upload()
	// {
	// 	$config['upload_path']          = 'uploads/';
	// 	$config['allowed_types']        = 'gif|jpg|png';
	// 	$config['max_size']             = 100;
	// 	$config['max_width']            = 1024;
	// 	$config['max_height']           = 768;

	// 	$this->load->library('upload', $config);

	// 	if (!$this->upload->do_upload('userfile')) {
	// 		$error = array('error' => $this->upload->display_errors());

	// 		$this->load->view('admin', $error);
	// 	} else {
	// 		$data = array('upload_data' => $this->upload->data());

	// 		$this->load->view('admin', $data);
	// 	}
	// }

	// public function saveuser()
	// {
	// 	if ($_POST) {
	// 		$data['name'] = $this->input->post('name');
	// 		$data['email'] = $this->input->post('email');
	// 		$data['password'] = $this->input->post('password');
	// 		$data['number'] = $this->input->post('number');
	// 		$data['gender'] = $this->input->post('gender');
	// 		$data['usertype'] = 2;

	// 		$result = $this->user_model->saveAry('users', $data);
	// 		if ($result) {
	// 			$data['page'] = 'admin_details/userlist';
	// 			$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
	// 			redirect('userlist');
	// 		} else {
	// 			$data['page'] = 'admin_details/userlist';

	// 			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
	// 			redirect('userlist');
	// 		}
	// 	} else {
	// 		$data["page"] = "admin_details/userlist";
	// 		redirect('userlist');
	// 	}
	// }



	public function delete($id)
	{
		$where = ['id' => $id];

		$return = $this->user_model->deleteRow('month', $where);
		if ($return) {
			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
			redirect('month');
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('month');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('users', $where);

		if ($return) {
			$data['userinfo'] = $return;
			$data['page'] = 'admin_details/userlist_edit';
			$this->load->view('admin', $data);
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('userlist');
		}
	}
}
