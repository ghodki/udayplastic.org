<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notify extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		// $this->load->library('custom');
		if ($this->session->userdata('user_id') === null) {
			redirect('index');
		}
	}


	public function index()
	{
		if ($this->session->userdata('user_id') === null) {
			$this->load->view('welcome_message');
		} else {

			$data['error'] = '';
			$user_id = $this->session->userdata('user_id');
			// $data['notify'] = $this->user_model->getResult('notify');
			$where = ['user_id' => $user_id];
			$data['notify'] = $this->user_model->getAry('notify', '', '', '', $where);
			$data['page'] = 'admin_details/notify/index';
			$this->load->view('admin', $data);
		}
	}

	public function save()
	{
		if ($_POST) {

			$data['name'] = $this->input->post('name');
			$data['title'] = $this->input->post('title');

			$data['user_id'] = $this->session->userdata('user_id');
			$data['role_id'] = $this->session->userdata('roles');
			$result = $this->user_model->saveAry('notify', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('notify');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('notify');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('notify');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('notify', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('notify');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('notify');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('notify');
		}
	}

	public function seeNotification()
	{
		$user_id = $this->input->post('user_id');
		$notification_id = $this->input->post('notification_id');

		if ($user_id == '') {
			echo json_encode(['status' => false, 'msg' => 'Id not found.']);
			return;
		}
		$where = ['user_id' => $user_id, 'notification_id' => $notification_id];
		$return = $this->user_model->getRow('notificaton_reader', $where);
		if (!$return) {
			$data['user_id'] = $user_id;
			$data['status'] = 1;
			$data['notification_id'] = $notification_id;

			$this->user_model->saveAry('notificaton_reader', $data);
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');;
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('notify', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('notify');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('notify');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect('notify');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('notify', $where);

		if ($return) {
			$data['notify'] = $return;
			$data['page'] = 'admin_details/notify/edit';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('notify');
		}
	}
}
