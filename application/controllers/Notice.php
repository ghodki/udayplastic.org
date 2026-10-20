<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notice extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		$this->load->library('custom');
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
			// $data['notice'] = $this->user_model->getResult('notice');
			$where = ['user_id' => $user_id];
			$data['notice'] = $this->user_model->getAry('notice', '', '', '', $where);
			$data['page'] = 'admin_details/notice/index';
			$this->load->view('admin', $data);
		}
	}

	public function save()
	{
		if ($_POST) {

			$data['name'] = $this->input->post('name');
			$data['usertype'] = $this->input->post('usertype');
			$data['user_id'] = $this->session->userdata('user_id');
			$data['role_id'] = $this->session->userdata('roles');
			$result = $this->user_model->saveAry('notice', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('notice');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('notice');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('notice');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('notice', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('notice');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('notice');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('notice');
		}
	}




	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');
			$data['usertype'] = $this->input->post('usertype');
			$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id')];
			$result = $this->user_model->updateAry('notice', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('notice');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('notice');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Smoething went wrong', 'class' => 'danger']);
			redirect('notice');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id')];
		$return = $this->user_model->getRow('notice', $where);

		if ($return) {
			$data['notice'] = $return;
			$data['page'] = 'admin_details/notice/edit';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Something is Issue', 'class' => 'danger']);
			redirect('notice');
		}
	}

	public function statusChange()
	{
		$notice_id = $this->input->post('notice_id');
		$status = $this->input->post('status');
		if ($status == 1) {
			$status = 0;
		}else
		{
			$status=1;
		}

		if ($notice_id) {

			$data['status'] = $status;

			$where = ['id' => $notice_id, 'user_id' => $this->session->userdata('user_id')];
			$re = $this->user_model->getRow('notice', $where);
			$result = $this->user_model->updateAry('notice', $data, $where);

			if ($result) {

				$response = array(
					'status' => 'success',
					'message' => "Status Updated",
					'class' => 'success'
				);
			} else {
				$response = array(
					'status' => 'danger',
					'message' => "Something Went Wrong",
					'class' => 'danger'
				);
			}
		} else {
			$response = array(
				'status' => 'danger',
				'message' => "Data not found",
				'class' => 'danger'
			);
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}
}
