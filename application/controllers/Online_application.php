<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Online_application extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('download');

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
		$data['online_application'] = $this->user_model->getResult('online_application');
		$data['page'] = 'admin_details/online_application/index';
		$this->load->view('admin', $data);
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

	public function edit($id = null)
	{
		if (isset($id) && !empty($id)) {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('online_application', $where);
			if ($_POST) {
				$data['name'] = $this->input->post('name');
				$data['link'] = $this->input->post('link');
				$result = $this->user_model->updateAry('online_application', $data, ['id' => $id]);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'online Application Updated successfully.', 'class' => 'success']);
					return redirect('online_application');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
					return redirect('online_application');
				}
			}
			if ($return) {
				$data['result'] = $return;
				$data['page'] = 'admin_details/online_application/edit';
				$this->load->view('admin', $data);
			} else {
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('online_application');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
			return redirect('online_application');
		}
	}
}
