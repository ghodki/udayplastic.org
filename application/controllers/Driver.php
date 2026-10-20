<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Driver extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
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
		// if (permissionChecker('driver')) {
		$data['driver'] = $this->user_model->getResults('driver_form');

		$data['error'] = '';

		$data['page'] = 'admin_details/driver';
		$this->load->view('admin', $data);
		// }
	}

	public function edit($id = null)
	{
		if (isset($id) && !empty($id)) {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('driver_form', $where);

			if ($return) {
				$data['result'] = $return;
				$data['error'] = '';

				$data['page'] = 'admin_details/driver_edit';
				$this->load->view('admin', $data);
			} else {
				// $data['page'] = 'admin_details/application';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('driver/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
			return redirect('driver/index');
		}
	}

	public function update()
	{
		$id = $this->input->post('id');

		if (isset($_POST) && !empty($id)) {
			$where = ['id' => $id];
			if ($_FILES['image']['name'] != '') {
				$file_name = $_FILES['image']['name'];
				$file_size = $_FILES['image']['size'];
				$file_tmp = $_FILES['image']['tmp_name'];
				$file_type = $_FILES['image']['type'];

				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/driver/' . $file_name);
				$data['image'] = $file_name;
				$data['app_name'] = $this->input->post('app_name');
				$ret = $this->user_model->updateAry('driver_form', $data, $where);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
					redirect('driver');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('driver');
				}
			} else {
				$data['app_name'] = $this->input->post('app_name');
				$ret = $this->user_model->updateAry('driver_form', $data, $where);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
					redirect('driver');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('driver');
				}
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('driver');
		}
	}

	public function upload()
	{
		// print_r($_FILES);
		// die;

		if (isset($_POST)) {
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];

			move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/driver/' . $file_name);
			$data['image'] = $file_name;
			$data['app_name'] = $this->input->post('app_name');
			$ret = $this->user_model->saveAry('driver_form', $data);
			if ($ret) {
				$this->session->set_flashdata(['msg' => 'Record Save Successfully.', 'class' => 'success']);
				redirect('driver');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('driver');
			}
		} else {
			$data['page'] = 'admin_details/driver';
			$this->session->set_flashdata(['msg' => 'jpg and png are allow', 'class' => 'danger']);
			redirect('driver');
		}
	}
	public function changeDriverPosition()
	{
		$position=$this->input->post('position');
		// print_r($_POST);
		// die;
		if($position)
		{
			foreach($position as $key=>$setposition)
			{
				$key=$key+1;
				$res=$this->user_model->getRow('driver_form',['id'=>$setposition]);
				if($res)
				{
					$data['position']=$key;
					$where=['id'=>$res->id];
					$this->user_model->updateAry('driver_form',$data,$where);
				}
			}
			// echo json_encode('msg'=>'success','status'=>true);
		}
		
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('driver_form', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('driver');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('driver');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('driver');
		}
	}
}
