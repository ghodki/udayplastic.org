<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Socialmedia extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		if ($this->session->userdata('roles') === null) {
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

		$data['socialmedia'] = $this->user_model->getResult('socialmedia');
		$data['page'] = 'admin_details/socialmedia/index';
		$this->load->view('admin', $data);
	}

	public function save()
	{
		if ($_POST) {

			$data['insta'] = $this->input->post('insta');
			$data['fb'] = $this->input->post('fb');
			$data['twt'] = $this->input->post('twt');
			$data['linkdn'] = $this->input->post('linkdn');
			// $data['content'] = $this->input->post('socialmedia_name');
			$result = $this->user_model->saveAry('socialmedia', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				return redirect('socialmedia/index');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('socialmedia/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			return redirect('socialmedia/index');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('socialmedia', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				return redirect('socialmedia/index');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('socialmedia/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			return redirect('socialmedia/index');
		}
	}


	public function edit($id)
	{
		if (isset($id) && $id != '') {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('socialmedia', $where);

			if ($return) {
				$data['socialmedia'] = $return;
				$data['page'] = 'admin_details/socialmedia/edit';
				$this->load->view('admin', $data);
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('socialmedia/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			return redirect('socialmedia/index');
		}
	}

	public function update()
	{
		// 	$post = $this->input->post();
		// 	echo '<pre>';
		// print_r($post);
		// die;
		$id = $this->input->post('id');
		if (!empty($id)) {
			$data['insta'] = $this->input->post('insta');
			$data['fb'] = $this->input->post('fb');
			$data['twt'] = $this->input->post('twt');
			$data['linkdn'] = $this->input->post('linkdn');
			// $data['content'] = $this->input->post('socialmedia_name');

			$where = ['id' => $id];


			$result = $this->user_model->updateAry('socialmedia', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				return redirect('socialmedia/index');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('socialmedia/index');
			}
		} else {
			return redirect('socialmedia/index');
		}
	}
}
