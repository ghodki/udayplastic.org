<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categoryfilesharing extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		// $this->load->library('admin');	
		if ($this->session->userdata('roles') === null  && $this->session->userdata('roles') != 1) {
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
		// if (permissionChecker('categoryfilesharing')) {
		$data['categoryfilesharing'] = $this->user_model->getResult('categoryfilesharing');

		// $data['error'] = '';

		$data['page'] = 'admin_details/categoryfilesharing/index';
		$this->load->view('admin', $data);
		// }
	}

	public function edit($id = null)
	{
		if (isset($id) && !empty($id)) {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('categoryfilesharing', $where);

			if ($return) {
				$data['result'] = $return;
				// $data['error'] = '';

				$data['page'] = 'admin_details/categoryfilesharing/edit';
				$this->load->view('admin', $data);
			} else {
				// $data['page'] = 'admin_details/application';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('categoryfilesharing/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
			return redirect('categoryfilesharing/index');
		}
	}

	public function update()
	{
		$id = $this->input->post('id');

		if (isset($_POST) && !empty($id)) {
			$where = ['id' => $id];

			$data['name'] = $this->input->post('name');
			$ret = $this->user_model->updateAry('categoryfilesharing', $data, $where);
			if ($ret) {
				$this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
				redirect('categoryfilesharing');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('categoryfilesharing');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('categoryfilesharing');
		}
	}

	public function save()
	{
		// print_r($_FILES);
		// die;

		if (isset($_POST)) {



			$data['name'] = $this->input->post('name');
			$ret = $this->user_model->saveAry('categoryfilesharing', $data);
			if ($ret) {
				$this->session->set_flashdata(['msg' => 'Record Save Successfully.', 'class' => 'success']);
				redirect('categoryfilesharing');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('categoryfilesharing');
			}
		} else {
			// $this->session->set_flashdata(['msg' => '', 'class' => 'danger']);
			redirect('categoryfilesharing');
		}
	}
	public function changeDriverPosition()
	{
		$position = $this->input->post('position');
		// print_r($_POST);
		// die;
		if ($position) {
			foreach ($position as $key => $setposition) {
				$key = $key + 1;
				$res = $this->user_model->getRow('categoryfilesharing', ['id' => $setposition]);
				if ($res) {
					$data['position'] = $key;
					$where = ['id' => $res->id];
					$this->user_model->updateAry('categoryfilesharing', $data, $where);
				}
			}
			// echo json_encode('msg'=>'success','status'=>true);
		}
	}

	public function delete($id=null)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('categoryfilesharing', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('categoryfilesharing');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('categoryfilesharing');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('categoryfilesharing');
		}
	}
}
