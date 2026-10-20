<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Online_app extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('download');
		// $this->load->library('custom');

		$this->load->model('user_model');
		
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
		// if ($this->session->userdata('user_id') === null) {
		// 	redirect('index');

		// } else {

			$data['service'] = $this->custom->getService();
			$data['logo'] = $this->user_model->getResult('logo');
			$data['contact'] = $this->user_model->getResult('contacts');
			$data['register'] = $this->user_model->getResult('registered');
			$data['online_application'] = $this->user_model->getResult('online_application');
	
	
			$data['page'] = 'layout/online_application';
			$this->load->view('main',$data);
		
		// }
	}

	public function upload()
	{

		if (isset($_POST)) {
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];

			move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/application/' . $file_name);
			$data['image'] = $file_name;
			$data['app_name'] = $this->input->post('app_name');
			$ret = $this->user_model->saveAry('application_form', $data);
			if ($ret) {
				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('application');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('application');
			}
		} else {
			$data['page'] = 'admin_details/application';
			$this->session->set_flashdata(['msg' => 'jpg and png are allow', 'class' => 'danger']);
			redirect('application');
		}
	}
	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('online_application', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('online_application');

			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('online_application');

			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('online_application');

		}
	}

	public function save()
	{
			$data['name'] = $this->input->post('name');
			$data['link'] = $this->input->post('link');

			$ret = $this->user_model->saveAry('online_application', $data);
			if ($ret) {
				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('online_application');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				// redirect('application');
				redirect('online_application');

			}
	}
}
