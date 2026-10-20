<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Terms_and_condition extends CI_Controller
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
			// $data['terms_and_condition'] = $this->user_model->getResult('terms_and_condition');
			// $where = ['user_id' => $user_id];
			$data['terms_and_condition'] = $this->user_model->getAry('terms_and_condition', '', '', '', '');

			$data['page'] = 'admin_details/terms_and_condition/index';
			$this->load->view('admin', $data);
		}
	}

	public function save()
	{
		if ($_POST) {

			$data['name'] = $this->input->post('name');


			$result = $this->user_model->saveAry('terms_and_condition', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('terms_and_condition');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('terms_and_condition');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('terms_and_condition');
		}
	}

	
	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('terms_and_condition', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('terms_and_condition');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('terms_and_condition');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('terms_and_condition');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');;
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('terms_and_condition', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('terms_and_condition');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('terms_and_condition');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect('terms_and_condition');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('terms_and_condition', $where);
		if ($return) {
			$data['result'] = $return;
			$data['page'] = 'admin_details/terms_and_condition/edit';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('terms_and_condition');
		}
	}
}
