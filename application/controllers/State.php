<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    class State extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('user_model');

            if ($this->session->userdata('RoleType')) {
                if (empty($this->session->userdata('UserId'))) {
                    return redirect(base_url() . 'index');
                }
            } else {
                if (empty($this->session->userdata('user_id'))) {
                    return redirect(base_url() . 'index');
                }                
            }
	}
        
	public function stateNames($id = null)
	{
            if (isset($id) && !empty($id)) {
                $this->db->select('*');
                $this->db->from('state_list');
                $this->db->where('id', $id);
                $this->db->order_by('state', 'ASC');
                $query = $this->db->get();
                $party = $query->result();
                if ($party) {
                    $data['party'] = $party;
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
	}

	public function stateNamess()
	{
		$state_name = $this->input->post('name');
		if ($state_name) {
			$this->db->select('*');
			$this->db->from('state_list');
			$query = $this->db->like('state', $state_name);
			$this->db->order_by('state', 'ASC');
			$query = $this->db->get();
			$party = $query->result();

			if ($party) {
				$data['party'] = $party;
				echo json_encode(['status' => true, 'data' => $data]);
			} else {
				echo json_encode(['status' => false, 'data' => 'Data not found.']);
			}
		} else {
			echo json_encode(['status' => false, 'msg' => 'Required  Field']);
			return;
		}
	}
	
	public function getStateByGst($id = null)
	{
		if (isset($id) && !empty($id)) {
			$this->db->select('*');
			$this->db->from('state_list');
			$this->db->where('code', $id);
			$query = $this->db->get();
			$party = $query->row();
			if ($party) {
				echo json_encode(['status' => true, 'data' => $party]);
			} else {
				echo json_encode(['status' => false, 'data' => 'Data not found.']);
			}
		} else {
			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
			return;
		}
	}
	
	public function index()
	{
		if (!$this->user_model->check_permission('state')) {
			$data['page'] = 'admin_details/error/permission_denied';
			// redirect(base_url() . 'index.php/dashboard');
		} else {
			$role_data = $this->session->userdata('roles');
			$data['page'] = 'admin_details/state/index';
			$data['title'] = 'State';
			$data['menu'] = 'State';
			$data['state'] = $this->user_model->getAryss('state_list');
			if (!$this->input->post()) {
				return $this->load->view('admin', $data);
			}
			// $fyear = $this->session->userdata('Finacialyear');
			if (isset($role_data) && isset($_POST)) {
				$this->form_validation->set_rules('name', 'State Name', 'trim|required|is_unique[state_list.state]');
				$this->form_validation->set_rules('code', 'State Code', 'trim|required|is_unique[state_list.code]');

				// $this->form_validation->set_rules('state', 'State', 'required');
				if ($this->form_validation->run() == false) {
					$data['error'] = validation_errors('<p class="text-danger">', '</p>');
					return $this->load->view('admin', $data);
				}

				$insData['state'] = ucfirst($this->input->post('name'));
				$insData['code'] = ucfirst($this->input->post('code'));

				// $insData['state_id'] = ucfirst($this->input->post('state'));
				$result = $this->user_model->saveAry('state_list', $insData);
				if (isset($result) && !empty($result)) {
					$this->session->set_flashdata(['msg' => 'State added successfully.', 'class' => 'success']);
					return redirect('state');
				} else {
					$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
					return redirect('state');
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
				return redirect('state');
			}
		}
	}

	public function stateName()
	{
		$id = $this->input->post('id');
		$state = get_title('state_list', ['id' => $id], 'state');
		if ($state) {
			echo json_encode(['status' => true, 'data' => $state]);
		} else {
			echo json_encode(['status' => true, 'data' => 'Data not found.']);
		}
	}



	public function edit($id = null)
	{
		$data['page'] = 'admin_details/state/edit';
		if (!$id) {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('state');
		}
		$where = ['id' => $id];
		$result = $this->user_model->getRow('state_list', $where);
		if (isset($result) && !empty($result)) {
			$data['result'] = $result;
			return $this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('state');
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

			$data['page'] = 'admin_details/state/view';
			$data['title'] = 'MIS | State Details';
			$data['menu'] = 'state';

			$count = $this->user_model->RowCount('state_list');
			$config['base_url'] = base_url('index.php/state/view');
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

			$order = ['order' => 'ASC'];
			$limit = 10;
			$result = $this->user_model->getAry('state_list', $order, $start, $limit);
			$data['result'] = $result;
			$data['start'] = $start;
			$data['end'] = (int)$start + count($result);
			$data['totalCount'] = $count;
			$this->load->view('admin', $data);
		}
	}

	// public function is_state_unique()
	// {
	// 	$where_not = ['id !=' => $this->input->post('state_id')];
	// 	$where = ['state' => $this->input->post('name')];
	// 	if ($this->user_model->check_unique('state_list', $where_not, $where)) {
	// 		return TRUE;
	// 	}
	// 	$this->form_validation->set_message('is_state_unique', 'State must contain a unique value');
	// 	return FALSE;
	// }
	public function is_state_code_unique()
	{
		// $where_not = ['code' => $this->input->post('code')];
		$where = ['id!=' => $this->input->post('state_id'), 'code' => $this->input->post('code')];
		if ($this->user_model->getRow('state_list', $where)) {
			$this->form_validation->set_message('is_state_code_unique', 'State Code must contain a unique value');
			return FALSE;
		} else {
			return TRUE;
		}
		return true;
	}
	public function is_state_name_unique()
	{
		// $where_not = ['code' => $this->input->post('code')];
		$where = ['id!=' => $this->input->post('state_id'), 'state' => $this->input->post('name')];
		if ($this->user_model->getRow('state_list', $where)) {
			$this->form_validation->set_message('is_state_name_unique', 'State Name must contain a unique value');
			return FALSE;
		} else {
			return TRUE;
		}
		return true;
	}
	public function unique_contact_update()
	{
		$contact = $this->input->post('contact');
		$id = $this->input->post('id');

		if ($contact) {
			$user_id = $this->session->userdata('user_id');
			$comp_id = $this->session->userdata('comp_id');

			$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'contact' => $contact, 'id!=' => $id,];
			$return_value = $this->user_model->getRow('parties', $where);
			// print_r($return_value);
			if ($return_value) {
				$this->form_validation->set_message('unique_contact_update', 'Party Mobile Number Should be unique');
				return false;
			} else {
				return true;
			}
		}
		return true;
	}

	public function update_state()
	{
		$id = $this->input->post('state_id');
		if (!$id) {
			$this->session->set_flashdata(['msg' => 'Data not found', 'class' => 'danger']);
			return redirect('state');
		}
		$this->form_validation->set_rules('name', 'State Name', 'trim|required|callback_is_state_name_unique');
		$this->form_validation->set_rules('code', 'State Code', 'trim|required|callback_is_state_code_unique');
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata(['msg' => validation_errors(), 'class' => 'danger']);
			// $data['error'] = validation_errors('<p class="text-danger">', '</p>');
			// return $this->load->view('admin', $data);
			return redirect('state/edit/' . $id);
		}
		$insData['state'] = ucfirst($this->input->post('name'));
		$insData['code'] = ucfirst($this->input->post('code'));
		$result = $this->user_model->updateAry('state_list', $insData, ['id' => $id]);
		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'State updated successfully.', 'class' => 'success']);
			return redirect('state');
		} else {
			$this->session->set_flashdata(['msg' => 'Data not found', 'class' => 'danger']);
			return redirect('state');
		}
	}

	public function statefind($id = null)
	{
		$state_id = $id;
		// $rownumber = $this->input->post('rownumber');

		$where = ['id' => $state_id];
		$result1 = $this->user_model->getRow('state_list', $where);
		// print_r($tax_id);
		// die;
		$result = $this->user_model->getAry('state_list', '', '', '', '');
		$html = '';
		$html .= "<div class='col-md-3'><label for='State Name'>State</label></div>";
		// $html .= "<select class='form-control' onchange='ChangeTax(this.value,$rownumber)' id='tax" . $rownumber . "' name='product_service[$rownumber][tax]' required>";
		$html .= "<select class='form-control' name='state' id='state' class='form-control searchable_select' required='true'>";

		$html .= "<option value='' >-Select-</option>";
		if ($result1 != null) {
			$state_name = $result1->state;

			$html .= "<option value=" . $result1->id . " selected >" . $state_name . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->state . "</option>";
		}
		$html . "</select>";
		// echo '<pre>';
		print_r($html);
		// die;
		// if($html)
		// {
		// 	echo json_encode(['status' => true, 'msg' => 'State Find','data'=>$html]);
		// 	return;
		// }else
		// {
		// 	echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
		// 	return;
		// }
		// print_r($html);
	}

	public function delete($id = null)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('state_list', $where);
		if ($return) {
			$this->user_model->deleteRow('state_list', $where);

			$this->session->set_flashdata(['msg' => 'State Delete Successfully.', 'class' => 'success']);
			redirect('state');
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('state');
		}
	}
}
