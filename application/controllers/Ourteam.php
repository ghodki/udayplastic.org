<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ourteam extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		$this->load->library('upload');
		// $this->load->library('admin');	
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
		// if (permissionChecker('ourteam')) {
		$data['ourteam'] = $this->user_model->getResult('ourteam');

		$data['error'] = '';

		$data['page'] = 'admin_details/ourteam/index';
		$this->load->view('admin', $data);
		// }
	}
	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => 'name',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'description',
				'label' => 'description',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'image',
				'label' => 'image',
				'rules' => 'trim|required'
			),


		);
		return $rules;
	}
	public function upload()
	{

		if ($_FILES['image']['name']) {
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];

			if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/ourteam/' . $file_name);
				$data['image'] = $file_name;
				$data['title'] = $this->input->post('title');
				$data['description'] = $this->input->post('description');
				$ret = $this->user_model->saveAry('ourteam', $data);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record save Successfully.', 'class' => 'success']);
					redirect('ourteam');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('ourteam');
				}
			} else {
				$data['page'] = 'admin_details/ourteam';
				$this->session->set_flashdata(['msg' => 'jpg and png are allow', 'class' => 'danger']);
				redirect('ourteam');
			}
		} else {
			redirect('ourteam');
		}
	}
	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('ourteam', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('ourteam');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('ourteam');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('ourteam');
		}
	}
}
