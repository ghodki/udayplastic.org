<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		//  $session_userdata = $this->session->userdata();
		// if(!isset($session_userdata['login']) || $session_userdata['login']=!"yes" || $session_userdata['admin_login']!="yes" || $session_userdata['admin_id']!=1)
		// {
		//     redirect(base_url('login'));
		// }
	}
	public function index()
	{

		$limit = 10;
		$start = 0;
		$order = ['id' => 'DESC'];
		$result = $this->user_model->getAry('roles', $order, $start, $limit);
		$data['roles'] = $result;
		$where = ['parents_id' => 0];
		$permissions_arr = $this->user_model->getAry('permissions', $order, $start, $limit, $where);
		$menus = [];
		foreach ($permissions_arr as $key => $permissions_val) {
			$where = ['parents_id' => $permissions_val->id];
			$child_arr = $this->user_model->getAry('permissions', $order, $start, $limit, $where);
			$menus[$key]['parent_menu'] = $permissions_val;
			$menus[$key]['child'] = $child_arr;
		}

		$data['menus_list'] = $menus;
		$role_list = [];
		foreach ($data['roles'] as $key => $role_val) {
			$role_list[$key]['role_name'] = $role_val->name;
			$role_list[$key]['role_id'] = $role_val->id;
			$this->db->select('role_has_permissions.permissions_id,permissions.*')
				->from('role_has_permissions')
				->where('permissions.parents_id', 0)
				->where('role_has_permissions.role_id', $role_val->id)
				->join('permissions', 'role_has_permissions.permissions_id =permissions.id')
				->order_by("role_has_permissions.permissions_id", "asc");
			$user_assign_parent = $this->db->get()->result_array();
			$role_list[$key]['parent'] = $user_assign_parent;
		}
		// print_r($menus_list);die;
		$data['permissions'] = $result;
		$data['role_list'] = $role_list;
		$data['title'] = 'Role Management';
		$data['page'] = 'admin_details/roles/index';
		$this->load->view('admin', $data);
	}
	public function edit($id)
	{

		$limit = 1000;
		$start = 0;
		$order = ['id' => 'DESC'];
		$result = $this->user_model->getAry('roles', $order, $start, $limit);
		$data['roles'] = $result;
		$where = ['parents_id' => 0];
		$permissions_arr = $this->user_model->getAry('permissions', $order, $start, $limit, $where);
		$menus = [];

		foreach ($permissions_arr as $key => $permissions_val) {
			$where = ['parents_id' => $permissions_val->id];
			$child_arr = $this->user_model->getAry('permissions', $order, $start, $limit, $where);
			$menus[$key]['parent'] = $permissions_val;
			$menus[$key]['child'] = $child_arr;
		}


		$data['menus_list'] = $menus;
		$role_list = [];
		foreach ($data['roles'] as $key => $role_val) {
			$role_list[$key]['role_name'] = $role_val->name;
			$role_list[$key]['role_id'] = $role_val->id;
			$this->db->select('role_has_permissions.permissions_id,permissions.*')
				->from('role_has_permissions')
				->where('permissions.parents_id', 0)
				->where('role_has_permissions.role_id', $role_val->id)
				->join('permissions', 'role_has_permissions.permissions_id =permissions.id')
				->order_by("role_has_permissions.permissions_id", "asc");
			$user_assign_parent = $this->db->get()->result_array();
			$role_list[$key]['parent'] = $user_assign_parent;
		}

		$where = ['id' => $id];
		$select_role = $this->user_model->getAry('roles', $order, $start, $limit, $where);

		$where = ['role_id' => $id];
		$select_module = $this->user_model->getAry('role_has_permissions', $order, $start, $limit, $where);


		$selected_module_id = [];
		foreach ($select_module as $key => $module_val) {
			$selected_module_id[] = $module_val->permissions_id;
		}

		$old_data['selected_id'] = $selected_module_id;
		$old_data['selected_role'] = $select_role[0]->name;
		$old_data['selected_role_id'] = $select_role[0]->id;

		$data['old_data'] = $old_data;
		$data['permissions'] = $result;
		$data['role_list'] = $role_list;
		$data['title'] = 'Role Management';
		$data['page'] = 'admin_details/roles/edit';
		$this->load->view('admin', $data);
	}
	public function editrole()
	{

		// echo '<pre>';
		// print_r($_POST);
		// die;
		$data['name'] = $this->input->post('name');
		$role_id = $this->input->post('role_id');
		$where = ['id' => $role_id];
		$result = $this->user_model->updateAry('roles', $data, $where);

		$where = ['role_id' => $role_id];
		$result = $this->user_model->deleteall('role_has_permissions', $where);

		$permissions = $this->input->post('permissions');
		foreach ($permissions as $per_id) {
			$data = [];
			$data['role_id'] = $role_id;
			$data['permissions_id'] = $per_id;
			$flag =  $result = $this->user_model->saveAry('role_has_permissions', $data);
		}


		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
			redirect('roles/index');
		} else {
			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
			redirect('roles/index');
		}
	}

	public function create()
	{
		$data['title'] = 'Vaccine';
		$data['page'] = 'vaccine/create';
		$this->load->view('index', $data);
	}
	public function addrole()
	{
		// print_r($this->input->post());die;
		$data['name'] = $this->input->post('name');
		$result = $this->user_model->saveAry('roles', $data);

		$query = "select * from roles order by id DESC limit 1";
		$res = $this->db->query($query);
		$last_id = $res->result();
		$flag = 0;
		$permissions = $this->input->post('permissions');
		foreach ($permissions as $per_id) {
			$data = [];
			$data['role_id'] = $last_id[0]->id;
			$data['permissions_id'] = $per_id;
			$flag =  $result = $this->user_model->saveAry('role_has_permissions', $data);
		}


		$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
		redirect('roles/index', 'refresh');
	}
	public function Delete($id)
	{
		if (!$id) {
			echo json_encode(['status' => false, 'msg' => 'Id not found.']);
			return;
		}

		$where = ['id' => $id];
		$result = $this->user_user_model->deleteRow('vaccine', $where);

		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
			return redirect('vaccine/index');
		} else {
			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.', 'class' => 'success']);
			return redirect('vaccine/index');
		}
	}
}
