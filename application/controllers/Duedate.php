<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Duedate extends CI_Controller
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
		// $data['year'] = $this->user_model->getAry('year');
		$where = ['status' =>1];
		$data['year'] = $this->user_model->getAry('year', '', '', '', $where);
		$data['page'] = 'admin_details/duedate/index';
		$this->load->view('admin', $data);
	}

	public function adddue()
	{
		if ($_POST) {
			// print_r($_POST);
			// die;
			$id = $this->input->post('id');

			$due_date = $this->input->post('month_id');
			$year_id = $this->input->post('year_id');
			$data['duedate'] = $this->input->post('duedate');
			$data['year_id'] = $this->input->post('year_id');
			$data['status'] = 1;
			$data['month_id'] = $due_date + 1;
			$where = ['id' => $id];
			$result = $this->user_model->updateAry('duedate', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				$page['page'] = 'admin_details/duedate/index';
				// $page['']
				redirect('duedate');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('duedate');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('duedate');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('duedate', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('duedate');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('duedate');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('duedate');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');;
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('duedate', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('duedate');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('duedate');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect('duedate');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('duedate', $where);

		if ($return) {
			$data['duedate'] = $return;
			$data['page'] = 'admin_details/duedate/edit';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('duedate');
		}
	}
}
