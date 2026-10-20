<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Registered extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		if ($this->session->userdata('user_id') === null) {
			redirect('welcome');
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data['registered'] = $this->user_model->getResult('registered');
		$data['page'] = 'admin_details/registered/index';
		$this->load->view('admin', $data);
	}
	public function save()
	{
		if ($_POST) {

			$data['location'] = $this->input->post('registered_name');
			$data['number'] = $this->input->post('number');
			$data['email'] = $this->input->post('email');


			$result = $this->user_model->saveAry('registered', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				return redirect('registered/index');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('registered/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			return redirect('registered/index');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('registered', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				return redirect('registered/index');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('registered/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			return redirect('registered/index');
		}
	}


	public function edit($id)
	{
		if (isset($id) && $id != '') {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('registered', $where);

			if ($return) {
				$data['registered'] = $return;
				$data['page'] = 'admin_details/registered/edit';
				$this->load->view('admin', $data);
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('registered/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			return redirect('registered/index');
		}
	}

	public function update()
	{
		// 	$post = $this->input->post();
		// 	echo '<pre>';
		// print_r($post);
		// die;
		$id = $this->input->post('id');
		if ($id) {
			$data['location'] = $this->input->post('registered_name');
			$data['number'] = $this->input->post('number');

			$data['email'] = $this->input->post('email');


			$where = ['id' => $id];


			$result = $this->user_model->updateAry('registered', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				return redirect('registered/index');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('registered/index');
			}
		} else {
			return redirect('registered/index');
		}
	}
}
