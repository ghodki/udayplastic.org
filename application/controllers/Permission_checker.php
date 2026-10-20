<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission_checker extends CI_Controller
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
		$limit = 1000;
		$start = 0;
		$order = ['id' => 'DESC'];
		$result = $this->user_model->getAry('roles', $order, $start, $limit);
		$data['roles'] = $result;
		$where = ['parents_id' => 0];
		$permissions_arr = $this->user_model->getAry('permissions', $order, $start, $limit, $where);

		$where = '';
		$permissions_list = $this->user_model->getAry('permissions', $order, $start, $limit, $where);

		$data['permission'] = $permissions_arr;
		$data['permissions_list'] = $permissions_list;
		$data['title'] = 'Permission Management';
		$data['page'] = 'admin_details/permission/index';
		$this->load->view('admin', $data);
	}
	// public function edit($id)
	// {
	// 	if (!$id) {
	// 		redirect('permission_checker');
	// 	}
	// 	$data['page'] = 'vaccine/edit';
	// 	$data['title'] = 'Vaccine';
	// 	$data['heading'] = 'Edit Vaccine';
	// 	$where = ['id' => $id];
	// 	$result = $this->user_model->getRow('vaccine', $where);
	// 	$data['result'] = $result;
	// 	if (isset($result) && !empty($result)) {
	// 		return $this->load->view('index', $data);
	// 	} else {
	// 		$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
	// 					redirect('permission_checker');

	// 	}
	// }
	public function update()
	{

		$id = $this->input->post('vaccine_id');

		$data['name'] = $this->input->post('vaccineName');
		$data['quantity'] = $this->input->post('quantity');
		$data['price'] = $this->input->post('Price');
		$where = ['id' => $id];
		$result = $this->user_model->updateAry('vaccine', $data, $where);
		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
			redirect('/vaccine/index');
		} else {
			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
			redirect('/vaccine/index');
		}
	}

	// public function create()
	// {
	// 	$data['title'] = 'Vaccine';
	// 	$data['page'] = 'vaccine/create';
	// 	$this->load->view('index', $data);
	// }
	// public function store()
	// {
	// 	$data['name'] = $this->input->post('vaccineName');
	// 	$data['quantity'] = $this->input->post('quantity');
	// 	$data['price'] = $this->input->post('Price');
	// 	$result = $this->user_model->saveAry('vaccine', $data);
	// 	$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
	// 	redirect('/vaccine/index', 'refresh');
	// }
	public function Delete($id)
	{
		if (!$id) {
			echo json_encode(['status' => false, 'msg' => 'Id not found.']);
			return;
		}

		$where = ['id' => $id];
		$result = $this->user_model->deleteRow('vaccine', $where);

		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
			return redirect('vaccine/index');
		} else {
			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.', 'class' => 'success']);
			return redirect('vaccine/index');
		}
	}

	public function View($id)
	{
		if (!$id) {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('vaccine');
		}
		$data['title'] = 'Vaccine | View';
		$data['menu'] = 'Vaccine';
		$data['page'] = 'vaccine/view';
		$where = ['id' => $id];
		$result = $this->user_model->getRow('vaccine', $where);

		if (isset($result) && !empty($result)) {
			$data['result'] = $result;
			return $this->load->view('index', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('vaccine');
		}
	}

	public function addmodule()
	{

		$data['parents_id'] = $this->input->post('parent_id');
		$data['name'] = $this->input->post('name');
		$data['url'] = $this->input->post('url');
		$dyn = '';
		// $vpn_network = $this->input->post('operation');
		if ($this->input->post('operation') != '') {
			foreach ($this->input->post('operation') as $attrKey => $val) {
				$dyn[$val] = $val;
			}
		}
		// echo '<pre>';
		// print_r($vpn_network);

		// $vpn1 = json_encode($vpn_network);
		// echo '<pre>';
		// print_r($vpn1);
		// die;
		$action = $dyn;
		$data['permissions_action'] = json_encode($action);

		$result = $this->user_model->saveAry('permissions', $data);
		$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
		redirect('permission_checker');
	}
}
