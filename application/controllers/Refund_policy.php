<?php
defined('BASEPATH') or exit('No direct script access allowed');

class refund_policy extends CI_Controller
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

			// $data['error'] = '';
			// $data['refund_policy'] = $this->user_model->getResult('refund_policy');
			// $where = ['user_id' => $user_id];
			$data['refund_policy'] = $this->user_model->getAry('refund_policy', '', '', '', '');

			$data['page'] = 'admin_details/refund_policy/index';
			$this->load->view('admin', $data);
		}
	}

	public function save()
	{
		if ($_POST) {

			$data['name'] = $this->input->post('name');


			$result = $this->user_model->saveAry('refund_policy', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('refund_policy');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('refund_policy');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('refund_policy');
		}
	}

	
	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('refund_policy', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('refund_policy');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('refund_policy');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('refund_policy');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');;
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('refund_policy', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('refund_policy');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('refund_policy');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect('refund_policy');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('refund_policy', $where);
		if ($return) {
			$data['result'] = $return;
			$data['page'] = 'admin_details/refund_policy/edit';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('refund_policy');
		}
	}
}
