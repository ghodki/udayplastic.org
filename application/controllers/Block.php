<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Block extends CI_Controller
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
		$role_data = $this->session->userdata('roles');
		// $fyear = $this->session->userdata('Finacialyear');
		if (isset($role_data)) {
			$result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
			$newdata = $result1->permissions_id;
			$permission_data = explode(",", $newdata);
			// print_r($permission_data);
			// die;
			if (empty($permission_data)) {
				return redirect('dashboard');
			}
			$data['page'] = 'admin_details/block/add';
			$data['title'] = 'MIS | Blocktails';
			$data['menu'] = 'Block';
			$data['state'] = $this->user_model->getAry('state_list', ['col' => 'state', 'order' => 'ASC']);
			if (!$this->input->post()) {
				return $this->load->view('admin', $data);
			}
			$this->form_validation->set_rules('name', 'Block', 'required|is_unique[blocks.name]');
			$this->form_validation->set_rules('state', 'State', 'required');
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
				return $this->load->view('admin', $data);
			}

			$insData['name'] = ucfirst($this->input->post('name'));
			$insData['state_id'] = ucfirst($this->input->post('state'));
			$result = $this->user_model->saveAry('blocks', $insData);
			if (isset($result) && !empty($result)) {
				$this->session->set_flashdata(['msg' => 'Bank added successfully.', 'class' => 'success']);
				return redirect('block');
			} else {
				$data['error'] = 'There is some issue, Please try again.';
				return $this->load->view('admin', $data);
			}
		} else {
			$data['error'] = 'There is some issue, Please try again.';
			return $this->load->view('admin', $data);
		}
	}

	public function addBlock()
	{
		$role_data = $this->session->userdata('role');
		$fyear = $this->session->userdata('Finacialyear');
		if (isset($role_data) && isset($fyear)) {
			$result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
			$newdata = $result1->permission_action;
			$permission_data = explode(",", $newdata);
			if (!in_array("add", $permission_data)) {
				return redirect('block');
			}
			$data['page'] = 'block/add';
			$data['title'] = 'MIS | Blocktails';
			$data['menu'] = 'Block';
			$data['state'] = $this->model->getAry('state_list', ['col' => 'state', 'order' => 'ASC']);
			if (!$this->input->post()) {
				return $this->load->view('index', $data);
			}
			$this->form_validation->set_rules('name', 'Block', 'required|is_unique[blocks.name]');
			$this->form_validation->set_rules('state', 'State', 'required');
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
				return $this->load->view('index', $data);
			}

			$insData['name'] = ucfirst($this->input->post('name'));
			$insData['state_id'] = ucfirst($this->input->post('state'));
			$result = $this->model->saveAry('blocks', $insData);
			if (isset($result) && !empty($result)) {
				$this->session->set_flashdata(['msg' => 'Bank added successfully.', 'class' => 'success']);
				return redirect('block');
			} else {
				$data['error'] = 'There is some issue, Please try again.';
				return $this->load->view('index', $data);
			}
		} else {
			$data['error'] = 'There is some issue, Please try again.';
			return $this->load->view('index', $data);
		}
	}

	public function edit($id)
	{
		$role_data = $this->session->userdata('roles');
		// $fyear = $this->session->userdata('Finacialyear');
		if (isset($role_data)) {
			// $result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
			// $newdata = $result1->permission_action;
			// $permission_data = explode(",", $newdata);
			// if (!in_array("edit", $permission_data)) {
			// 	return redirect('block');
			// }
			$data['page'] = 'admin_details/block/edit';

			$data['title'] = 'MIS | Blocktails';
			$data['menu'] = 'Block';
			$data['state'] = $this->user_model->getAry('state_list', ['col' => 'state', 'order' => 'ASC']);
			if (!$id) {
				$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
				return redirect('block');
			}
			$where = ['id' => $id];
			$result = $this->user_model->getRow('blocks', $where);
			if (isset($result) && !empty($result)) {
				$data['result'] = $result;
				return $this->load->view('admin', $data);
			} else {
				$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
				return redirect('block');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
			return redirect('block');
		}
	}

	public function view($start = 0)
	{
		$role_data = $this->session->userdata('roles');

		// $fyear = $this->session->userdata('Finacialyear');
		if (isset($role_data)) {
			// $result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
			// $newdata = $result1->permission_action;
			// $permission_data = explode(",", $newdata);
			// $data['permission_data'] = $permission_data;

			$data['page'] = 'admin_details/block/index';
			$data['title'] = 'MIS | Block Details';
			$data['menu'] = 'block';

			$count = $this->user_model->RowCount('blocks');
			$config['base_url'] = base_url('index.php/block/view');
			$config['total_rows'] = $count;
			$config['per_page'] = 10;
			$config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';
			$config['full_tag_close'] = '</ul></nav>';

			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<li class="page-item">';
			$config['first_tag_close'] = '</li>';

			$config['last_link'] = 'Last';
			$config['last_tag_open'] = '<li class="page-item">';
			$config['last_tag_close'] = '</li>';

			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<li class="page-item">';
			$config['next_tag_close'] = '</li>';

			$config['prev_link'] = 'Previous';
			$config['prev_tag_open'] = '<li class="page-item">';
			$config['prev_tag_close'] = '</li>';

			$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li class="page-item">';
			$config['num_tag_close'] = '</li>';

			$config['attributes'] = array('class' => 'page-link');
			$this->pagination->initialize($config);

			$order = ['col' => 'name', 'order' => 'ASC'];
			$limit = 10;
			$result = $this->user_model->getAry('blocks', $order, $start, $limit);
			$data['result'] = $result;
			$data['start'] = $start;
			$data['end'] = (int)$start + count($result);
			$data['totalCount'] = $count;
			$this->load->view('admin', $data);
		}
	}

	public function is_block_unique()
	{
		$where_not = ['id !=' => $this->input->post('id')];
		$where = ['name' => $this->input->post('name')];
		if ($this->user_model->check_unique('blocks', $where_not, $where)) {
			return TRUE;
		}
		$this->form_validation->set_message('is_block_unique', 'Block must contain a unique value');
		return FALSE;
	}

	public function update()
	{
		$id = $this->input->post('id');
		if (!$id) {
			return redirect('block');
		}
		if (!$this->input->post()) {
			return redirect('block');
		}
		$this->form_validation->set_rules('name', 'Block', 'required|callback_is_block_unique');
		$this->form_validation->set_rules('state', 'State', 'required');
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata(['msg' => validation_errors(), 'class' => 'danger']);
			return redirect('block/edit/' . $id);
		}
		$insData['name'] = ucfirst($this->input->post('name'));

		$insData['state_id'] = ucfirst($this->input->post('state'));
		$result = $this->user_model->updateAry('blocks', $insData, ['id' => $id]);
		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'Block updated successfully.', 'class' => 'success']);
			return redirect('block');
		} else {
			$data['error'] = 'There is some issue, Please try again.';
			$data['page'] = 'admin_details/block/edit/' . $id;
			return $this->load->view('admin', $data);
		}
	}

	public function delete($id)
	{
		$role_data = $this->session->userdata('roles');
		// $fyear = $this->session->userdata('Finacialyear');
		if (isset($role_data)) {
			// $result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
			// $newdata = $result1->permission_action;
			// $permission_data = explode(",", $newdata);
			// if (!in_array("delete", $permission_data)) {
			// 	return redirect('block');
			// }
			if (!$id) {
				return redirect('block');
			}
			$this->db->trans_start();
			$this->user_model->deleteRow('tahsils', ['block_id' => $id]);
			$this->user_model->deleteRow('blocks', ['block_id' => $id]);
			$this->user_model->deleteRow('blocks', ['id' => $id]);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
				return;
			}
			$this->db->trans_commit();
			echo json_encode(['status' => true, 'msg' => 'Block deleted successfully.']);
			return;
		} else {
			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
			return;
		}
	}
}
