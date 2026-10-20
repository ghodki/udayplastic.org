<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class District extends CI_Controller
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
        
	public function index()
	{
		if (!$this->user_model->check_permission('district')) {
			$data['page'] = 'admin_details/error/permission_denied';
			// redirect(base_url() . 'index.php/dashboard');
		} else {
			$role_data = $this->session->userdata('roles');
			$data['page'] = 'admin_details/district/index';
			$data['title'] = 'District';
			$data['districts'] = $this->user_model->getAryss('districts');
			if (!$this->input->post()) {
				return $this->load->view('admin', $data);
			}
			// $fyear = $this->session->userdata('Finacialyear');
			if (isset($role_data) && isset($_POST)) {
				$this->form_validation->set_rules('name', 'District Name', 'trim|required|is_unique[districts.name]');
				$this->form_validation->set_rules('state_id', 'State Name', 'trim|required');

				// $this->form_validation->set_rules('state', 'State', 'required');
				if ($this->form_validation->run() == false) {
					$data['error'] = validation_errors('<p class="text-danger">', '</p>');
					return $this->load->view('admin', $data);
				}

				$insData['name'] = ucfirst($this->input->post('name'));
				$insData['state_id'] = ucfirst($this->input->post('state_id'));

				// $insData['state_id'] = ucfirst($this->input->post('state'));
				$result = $this->user_model->saveAry('districts', $insData);
				if (isset($result) && !empty($result)) {
					$this->session->set_flashdata(['msg' => 'District added successfully.', 'class' => 'success']);
					return redirect('district');
				} else {
					$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
					return redirect('district');
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
				return redirect('district');
			}
		}
	}


	public function getCity($id = null)
	{
		if (isset($id) && !empty($id)) {

			$id = $this->input->post('id');
			$this->db->select('*');
			$this->db->from('districts');
			$this->db->where('id', $id);

			// $this->db->where('comp_id', $this->session->userdata('comp_id'));
			// $this->db->where('user_id', $this->session->userdata('user_id'));
			// $this->db->where_in('group_head');
			$this->db->order_by('name', 'ASC');
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
	public function addDistrict()
	{
		$role_data = $this->session->userdata('role');
		$fyear = $this->session->userdata('Finacialyear');
		if (isset($role_data) && isset($fyear)) {
			$result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
			$newdata = $result1->permission_action;
			$permission_data = explode(",", $newdata);
			if (!in_array("add", $permission_data)) {
				return redirect('district');
			}
			$data['page'] = 'district/add';
			$data['title'] = 'MIS | Districttails';
			$data['menu'] = 'District';
			$data['state'] = $this->model->getAry('state_list', ['col' => 'state', 'order' => 'ASC']);
			if (!$this->input->post()) {
				return $this->load->view('index', $data);
			}
			$this->form_validation->set_rules('name', 'District', 'required|is_unique[districts.name]');
			$this->form_validation->set_rules('state', 'State', 'required');
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
				return $this->load->view('index', $data);
			}

			$insData['name'] = ucfirst($this->input->post('name'));
			$insData['state_id'] = ucfirst($this->input->post('state'));
			$result = $this->model->saveAry('districts', $insData);
			if (isset($result) && !empty($result)) {
				$this->session->set_flashdata(['msg' => 'Bank added successfully.', 'class' => 'success']);
				return redirect('district');
			} else {
				$data['error'] = 'There is some issue, Please try again.';
				return $this->load->view('index', $data);
			}
		} else {
			$data['error'] = 'There is some issue, Please try again.';
			return $this->load->view('index', $data);
		}
	}

	public function cityfind($id = null)
	{
		$state_id = $id;
		// $rownumber = $this->input->post('rownumber');

		$where = ['id' => $state_id];
		$result1 = $this->user_model->getRow('districts', $where);
		// print_r($tax_id);
		// die;
		$result = $this->user_model->getAry('districts', '', '', '', '');
		$html = '';
		$html .= "<div class='col-md-3'><label for='City Name'>City</label></div>";
		// $html .= "<select class='form-control' onchange='ChangeTax(this.value,$rownumber)' id='tax" . $rownumber . "' name='product_service[$rownumber][tax]' required>";
		$html .= "<select class='form-control' name='city' id='city' class='form-control searchable_select'>";

		$html .= "<option value='' >-Select-</option>";
		if ($result1 != null) {
			$state_name = $result1->name;

			$html .= "<option value=" . $result1->id . " selected >" . $state_name . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->name . "</option>";
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
	public function edit($id = null)
	{
		$data['page'] = 'admin_details/district/edit';
		if (!$id) {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('district');
		}
		$where = ['id' => $id];
		$result = $this->user_model->getRow('districts', $where);
		if (isset($result) && !empty($result)) {
			$data['result'] = $result;
			return $this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('district');
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

			$data['page'] = 'admin_details/district/index';
			$data['title'] = 'MIS | District Details';
			$data['menu'] = 'district';

			$count = $this->user_model->RowCount('districts');
			$config['base_url'] = base_url('index.php/district/view');
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
			$result = $this->user_model->getAry('districts', $order, $start, $limit);
			$data['result'] = $result;
			$data['start'] = $start;
			$data['end'] = (int)$start + count($result);
			$data['totalCount'] = $count;
			$this->load->view('admin', $data);
		}
	}

	public function is_district_unique()
	{
		$where_not = ['id !=' => $this->input->post('id')];
		$where = ['name' => $this->input->post('name')];
		if ($this->user_model->check_unique('districts', $where_not, $where)) {
			return TRUE;
		}
		$this->form_validation->set_message('is_district_unique', 'District must contain a unique value');
		return FALSE;
	}

	public function update_district()
	{
		$id = $this->input->post('id');
		if (!$id) {
			return redirect('district');
		}
		if (!$this->input->post()) {
			return redirect('district');
		}
		$this->form_validation->set_rules('name', 'District Name', 'trim|required|callback_is_district_name_unique');
		$this->form_validation->set_rules('state_id', 'State', 'required');
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata(['msg' => validation_errors(), 'class' => 'danger']);
			return redirect('district/edit/' . $id);
		}
		$insData['name'] = ucfirst($this->input->post('name'));

		$insData['state_id'] = ucfirst($this->input->post('state_id'));
		$result = $this->user_model->updateAry('districts', $insData, ['id' => $id]);
		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'District updated successfully.', 'class' => 'success']);
			return redirect('district');
		} else {
			$data['error'] = 'There is some issue, Please try again.';
			$data['page'] = 'admin_details/district/edit/' . $id;
			return $this->load->view('admin', $data);
		}
	}

	// public function delete($id)
	// {
	// 	$role_data = $this->session->userdata('roles');
	// 	// $fyear = $this->session->userdata('Finacialyear');
	// 	if (isset($role_data)) {
	// 		// $result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
	// 		// $newdata = $result1->permission_action;
	// 		// $permission_data = explode(",", $newdata);
	// 		// if (!in_array("delete", $permission_data)) {
	// 		// 	return redirect('district');
	// 		// }
	// 		if (!$id) {
	// 			return redirect('district');
	// 		}
	// 		$this->db->trans_start();
	// 		$this->user_model->deleteRow('tahsils', ['district_id' => $id]);
	// 		$this->user_model->deleteRow('blocks', ['district_id' => $id]);
	// 		$this->user_model->deleteRow('districts', ['id' => $id]);
	// 		if ($this->db->trans_status() === FALSE) {
	// 			$this->db->trans_rollback();
	// 			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
	// 			return;
	// 		}
	// 		$this->db->trans_commit();
	// 		echo json_encode(['status' => true, 'msg' => 'District deleted successfully.']);
	// 		return;
	// 	} else {
	// 		echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
	// 		return;
	// 	}
	// }



	public function citynames()
	{
		$city = $this->input->post('name');
		if ($city) {
			$this->db->select('*');
			$this->db->from('districts');
			$query = $this->db->like('name', $city);
			$this->db->order_by('name', 'ASC');
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

	public function delete($id = null)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('districts', $where);
		if ($return) {
			$this->user_model->deleteRow('districts', $where);

			$this->session->set_flashdata(['msg' => 'District Delete Successfully.', 'class' => 'success']);
			redirect('district');
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('district');
		}
	}
	public function is_district_name_unique()
	{
		// $where_not = ['code' => $this->input->post('code')];
		$where = ['id!=' => $this->input->post('id'), 'name' => $this->input->post('name')];
		if ($this->user_model->getRow('districts', $where)) {
			$this->form_validation->set_message('is_district_name_unique', 'District Name must contain a unique value');
			return FALSE;
		} else {
			return TRUE;
		}
		return true;
	}
        
        public function getCityName ()
        {
            if ($_POST) {
                $CityName = $this->db->select('name')->where('id', $this->input->post('city_id'))->get('districts')->row('name');
                if ($CityName) {
                    echo $CityName;
                } else {
                    return false;
                }
            } 
        }
}