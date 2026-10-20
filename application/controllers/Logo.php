<?php

class Logo extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(['url', 'form']);
		$this->load->model('user_model');
		if ($this->session->userdata('user_id') === null) {
			redirect('welcome');
		}
	}

	// public function downloadVideo()
	// {
	// 	// Initialize a file URL to the variable
	// 	$url = 'https://www.youtube.com/watch?v=CcCkXKbBkzY&list=RDMMCcCkXKbBkzY&index=1';
	// 	// Use basename() function to return the base name of file
	// 	$file_name = basename($url);
	// 	 print_r($file_name);
	// 	 die;
	// 	// Use file_get_contents() function to get the file
	// 	// from url and use file_put_contents() function to
	// 	// save the file by using base name
	// 	if (file_put_contents($file_name, file_get_contents($url))) {
	// 		echo "File downloaded successfully";
	// 	} else {
	// 		echo "File downloading failed.";
	// 	}
	// }

	public function index()
	{
		$data['page'] = 'admin_details/logo/index';
		$data['error'] = '';
		$where = ['user_id' => $this->session->userdata('user_id')];
		$data['logos'] = $this->user_model->getResult('logo', $where);
		$this->load->view('admin', $data);
	}

	public function upload()
	{
		if ($_FILES['image']['name']) {
			$file_name = $_FILES['image']['name'];
			$file_type = $_FILES['image']['type'];
			if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/logo/' . $file_name);
				$data['logo'] = $file_name;
				$data['user_id'] = $this->session->userdata('user_id');
				$ret = $this->user_model->saveAry('logo', $data);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'logo Save Successfully.', 'class' => 'success']);
					redirect('logo');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('logo');
				}
			} else {
				$data['page'] = 'admin_details/logo';
				$this->session->set_flashdata(['msg' => 'jpg or jpeg or png are allow', 'class' => 'danger']);
				redirect('logo');
			}
		} else {
			redirect('logo');
		}
	}


	public function delete($id)
	{
		$where = ['id' => $id];

		$return = $this->user_model->deleteRow('logo', $where);
		if ($return) {
			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
			redirect('logo');
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'success']);
			redirect('logo');
		}
	}
}
