<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service extends CI_Controller
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

		$data['error'] = '';
		$data['service'] = $this->custom->getService();
		$data['page'] = 'admin_details/service';
		$this->load->view('admin', $data);
	}
        
	public function save()
	{
		if ($_POST) {

			$data['title'] = $this->input->post('name');
			$data['text'] = $this->input->post('text');

			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/images' . $file_name);
				$data['image'] = $file_name;
				$result = $this->user_model->saveAry('service', $data);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					return redirect('service');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					return redirect('service');
				}
			} else {
				$data['page'] = 'admin_details/service';
				$this->session->set_flashdata(['msg' => 'jpg and png are allow', 'class' => 'danger']);
				redirect('service');
			}
		} else {
			redirect('service');
		}
	}

	public function changePosition()
	{
		$position=$this->input->post('position');
		if($position)
		{
			foreach($position as $key=>$setposition)
			{
				$key=$key+1;
				$res=$this->user_model->getRow('service',['id'=>$setposition]);
				if($res)
				{
					$data['position']=$key;
					$where=['id'=>$res->id];
					$this->user_model->updateAry('service',$data,$where);
				}
			}
			// echo json_encode('msg'=>'success','status'=>true);
		}
		
	}
	public function delete($id)
	{
		$where = ['id' => $id];

		$return = $this->user_model->deleteRow('service', $where);
		if ($return) {

			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
			redirect('service');
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('service');
		}
	}
	public function edit($id)
	{
		if (isset($id) && $id != '') {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('service', $where);

			if ($return) {
				$data['service'] = $return;
				$data['error'] = '';

				$data['page'] = 'admin_details/service_edit';
				$this->load->view('admin', $data);
			} else {
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('service/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			return redirect('service/index');
		}
	}

	public function update()
	{
		// 	$post = $this->input->post();
		// 	echo '<pre>';
		// print_r($post);
		// die;
		if ($_POST) {
			$id = $this->input->post('id');
			$where = ['id' => $id];
			$data['title'] = $this->input->post('name');
			$data['text'] = $this->input->post('text');
			if ($_FILES['image']['name'] != '') {

				$file_name = $_FILES['image']['name'];
				$file_size = $_FILES['image']['size'];
				$file_tmp = $_FILES['image']['tmp_name'];
				$file_type = $_FILES['image']['type'];
				if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
					move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/images' . $file_name);
					$data['image'] = $file_name;
					$result = $this->user_model->updateAry('service', $data, $where);

					if ($result) {
						$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
						return redirect('service/index');
					} else {
						$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
						return redirect('service/index');
					}
				} else {
					$data['page'] = 'admin_details/service';
					$this->session->set_flashdata(['msg' => 'jpg and png are allow', 'class' => 'danger']);
					redirect('service');
				}
			} else {
				$result = $this->user_model->updateAry('service', $data, $where);

				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('service/index');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('service/index');
				}
			}
		} else {
			redirect('service');
		}
	}
	
	public function changeStatus()
	{

		$data['status'] = $this->input->post('status');
		$service_id = $this->input->post('service_id');
		$result = $this->user_model->updateAry('service', $data, ['id' => $service_id]);
		if ($result) {
			echo json_encode(['status' => 'success', 'msg' => 'Service Updated.']);
			return;
		} else {
			echo json_encode(['status' => 'error', 'msg' => 'Something Went Wrong.']);
			return;
		}
		echo json_encode(['status' => 'error', 'msg' => 'Something Went Wrong.']);
		return;
	}
}
