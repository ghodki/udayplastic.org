<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_categories extends CI_Controller
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
			$data['product_categories'] = $this->user_model->getResult('product_categories');

			$data['page'] = 'admin_details/product/product_cat';
			$this->load->view('admin', $data);
		}
	}
	public function save()
	{
		if ($_POST) {

			$data['category_name'] = $this->input->post('product_categories');


			$result = $this->user_model->saveAry('product_categories', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('product_categories');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('product_categories');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('product_categories');
		}
	}
	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('product_categories', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('product_categories');
			} else {
				$data['page'] = 'admin_details/product/product_cat';
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('product_categories');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('product_categories');
		}
	}
}
