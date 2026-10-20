<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Khatabook extends CI_Controller
{


	function __construct()
	{

		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('user_model');
		$this->load->library('ajax_pagination');
		$this->perPage = 10;
		if ($this->session->userdata('user_id') === null) {
			redirect('signin');
		}
	}

	protected function khatabook_rules()
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => 'Company Name',
				'rules' => 'trim|required|xss_clean|callback_check_same_company_name'
			),
			array(
				'field' => 'number',
				'label' => 'Mobile Number',
				'rules' => 'trim|required|xss_clean|callback_check_same_mobile'
			),
			array(
				'field' => 'address',
				'label' => 'Address',
				'rules' => 'trim|xss_clean'
			),

		);
		return $rules;
	}

	protected function transaction_rules()
	{
		$rules = array(
			array(
				'field' => 'party',
				'label' => 'Party',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'transaction_type',
				'label' => 'Transaction Type',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'amount',
				'label' => 'Amount',
				'rules' => 'trim|required|xss_clean|numeric'
			),
			array(
				'field' => 'remark',
				'label' => 'Remark',
				'rules' => 'trim|xss_clean'
			),

		);
		return $rules;
	}

	protected function party_rules()
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => 'Party Name',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'usertype',
				'label' => 'User Type',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'number',
				'label' => 'Mobile Number',
				'rules' => 'trim|required|xss_clean|numeric|regex_match[/^[0-9]{10}$/]'
			),
			array(
				'field' => 'address',
				'label' => 'Address',
				'rules' => 'trim|xss_clean'
			),

		);
		return $rules;
	}

	public function check_same_company_name()
	{
		if (!empty($this->input->post('company_id'))) {
			$company_id = $this->input->post('company_id');
		} else {
			$company_id = '';
		}
		$user = $this->session->userdata('user_id');
		$company_name = $this->input->post('name');
		$where = ['id!=' => $company_id, 'company_name' => $company_name, 'user_id' => $user];
		$return_value = $this->user_model->getRow('khatabook_company', $where);
		if ($return_value) {
			$this->form_validation->set_message('check_same_company_name', 'Company Name Can not be same! ');
			return false;
		}
		return true;
	}

	public function check_same_mobile()
	{
		if (!empty($this->input->post('company_id'))) {
			$company_id = $this->input->post('company_id');
		} else {
			$company_id = '';
		}
		$number = $this->input->post('number');
		$user = $this->session->userdata('user_id');
		$where = ['id!=' => $company_id, 'number' => $number, 'user_id' => $user];
		$return_value = $this->user_model->getRow('khatabook_company', $where);
		if ($return_value) {
			$this->form_validation->set_message('check_same_mobile', 'Mobile Number can not be same!! ');
			return false;
		}
		return true;
	}

	public function index()
	{
		if ($_POST) {
			$rules = $this->khatabook_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
			} else {
				$data['company_name'] = $this->input->post('name');
				$data['address'] = $this->input->post('address');
				$data['number'] = $this->input->post('number');
				$data['usertype'] = $this->input->post('usertype');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['created_at'] = date('d-m-Y H:i:s');
				$return = $this->user_model->saveAry('khatabook_company', $data);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('khatabook');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
					redirect('khatabook');
				}
			}
		}
		$user = $this->session->userdata('user_id');
		$where = ['user_id' => $user];
		$data['khatabook'] = $this->user_model->getResult('khatabook_company', $where);
		$data['page'] = 'khatabook/company/index';
		$this->load->view('admin', $data);
	}

	public function edit($id = null)
	{
		if ($_POST) {
			$rules = $this->khatabook_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
			} else {
				$data['company_name'] = $this->input->post('name');
				$data['address'] = $this->input->post('address');
				$data['number'] = $this->input->post('number');
				$data['updated_at'] = date('d-m-Y H:i:s');
				$return = $this->user_model->updateAry('khatabook_company', $data, ['id' => $id]);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
					redirect('khatabook');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
					redirect('khatabook');
				}
			}
		}
		$user = $this->session->userdata('user_id');
		$where = ['user_id' => $user, 'id' => $id];
		$data['result'] = $this->user_model->getRow('khatabook_company', $where);
		$data['page'] = 'khatabook/company/edit';
		$this->load->view('admin', $data);
	}

	public function viewclient($id = null)
	{
		if (isset($id) && $id != '') {
			$user_id = $this->session->userdata('user_id');
			$where = ['id' => $id, 'user_id' => $user_id];
			$return = $this->user_model->getRow('khatabook_company', $where);
			if ($return) {
				$this->session->set_userdata('khatabook_company_id', $id);
				$data['result'] = $return;
				$total_party = $this->user_model->getResult('khatabook_party', ['user_id' => $user_id, 'khatabook_company_id' => $id]);
				$total_transaction = $this->user_model->getResult('khatabook_transaction', ['user_id' => $user_id, 'khatabook_company_id' => $id]);
				$data['total_party'] = count($total_party);
				$data['total_transaction'] = count($total_transaction);
				$data['page'] = 'dashboard';
				$this->load->view('khatabook/main', $data);
			} else {
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				redirect('khatabook');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
			redirect('khatabook');
		}
	}

	public function partysave()
	{
		if ($_POST) {
			$rules = $this->party_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
			} else {
				$data['party_name'] = $this->input->post('name');
				$data['address'] = $this->input->post('address');
				$data['number'] = $this->input->post('number');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['khatabook_company_id'] = $this->session->userdata('khatabook_company_id');
				$data['created_at'] = date('d-m-Y H:i:s');
				$return = $this->user_model->saveAry('khatabook_party', $data);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Party Added Successfully.', 'class' => 'success']);
					redirect('khatabook/party');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
					redirect('khatabook/party');
				}
			}
		}
		$data['page'] = 'party/index';
		$this->load->view('khatabook/main', $data);
	}

	public function editParty($id = null)
	{
		if ($_POST) {
			$rules = $this->party_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
			} else {
				$data['party_name'] = $this->input->post('name');
				$data['address'] = $this->input->post('address');
				$data['number'] = $this->input->post('number');
				$data['usertype'] = $this->input->post('usertype');
				$data['updated_at'] = date('d-m-Y H:i:s');
				$return = $this->user_model->updateAry('khatabook_party', $data, ['id' => $id]);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Party Updated Successfully.', 'class' => 'success']);
					redirect('khatabook/party');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
					redirect('khatabook/party');
				}
			}
		}
		$user = $this->session->userdata('user_id');
		$where = ['user_id' => $user, 'id' => $id];
		$data['result'] = $this->user_model->getRow('khatabook_party', $where);
		$data['page'] = 'party/edit';
		$this->load->view('khatabook/main', $data);
	}



	public function party()
	{

		$user_id = $this->session->userdata('user_id');
		$data = array();
		// Get record count 
		$conditions['returnType'] = 'count';
		$user_id = $this->session->userdata('user_id');
		$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id')];
		$totalRec = $this->user_model->getCommonRows($conditions, 'khatabook_party');
		// Pagination configuration 
		$config['target']      = '#dataList';
		$config['base_url']    = base_url('khatabook/load_party');
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $this->perPage;
		$config['link_func']   = 'searchFilter';

		// Initialize pagination library 
		$this->ajax_pagination->initialize($config);
		// Get records 
		$conditions['limit'] = $this->perPage;
		$data['result'] = $this->user_model->getCommonRows($conditions, 'khatabook_party');
		// Load the list page view
		$data['page'] = 'party/index';
		$this->load->view('khatabook/main', $data);
	}


	function load_party()
	{
		// Define offset 
		$page = $this->input->post('page');
		if (!$page) {
			$offset = 0;
		} else {
			$offset = $page;
		}

		$keywords = $this->input->post('keywords');
		$sortBy = $this->input->post('sortBy');
		$pagelength = $this->input->post('pagelength');
		$column_filter = $this->input->post('column_filter');
		$usertype = $this->input->post('usertype');

		$user_id = $this->session->userdata('user_id');

		if (empty($usertype)) {
			$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id')];
		} else {
			$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id'), 'usertype' => $usertype];
		}
		// Get record count 
		$conditions['returnType'] = 'count';
		if (!empty($keywords)) {
			$conditions['search']['keywords'] = $keywords;
		}
		if (!empty($sortBy)) {
			$conditions['search']['sortBy'] = $sortBy;
		}
		$totalRec = $this->user_model->getCommonRows($conditions, 'khatabook_party', $column_filter);

		// Pagination configuration 
		$config['target']      = '#dataList';
		$config['base_url']    = base_url('user/load_party');
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $pagelength;
		$config['link_func']   = 'searchFilter';
		// Initialize pagination library 
		$this->ajax_pagination->initialize($config);
		// Get records 
		$conditions['start'] = $offset;
		$conditions['limit'] = $pagelength;
		unset($conditions['returnType']);
		$data['result'] = $this->user_model->getCommonRows($conditions, 'khatabook_party', $column_filter);
		// Load the data list view 
		$this->load->view('khatabook/party/load_party', $data, false);
	}

	public function delete_party($id = null)
	{
		if (isset($id) && $id != '') {
			$user_id = $this->session->userdata('user_id');
			$where = ['id' => $id, 'user_id' => $user_id];
			$return = $this->user_model->deleteRow('khatabook_party', $where);
			if ($return) {
				$this->session->set_flashdata(['msg' => 'Party Deleted.', 'class' => 'success']);
				redirect('khatabook/party');
			} else {
				$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
				redirect('khatabook/party');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
			redirect('khatabook/party');
		}
	}


	public function transaction_save()
	{
		if ($_POST) {
			$rules = $this->transaction_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
			} else {
				$data['party_id'] = $this->input->post('party');
				$data['remark'] = $this->input->post('remark');
				$data['amount'] = $this->input->post('amount');
				$data['transaction_type'] = $this->input->post('transaction_type');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['khatabook_company_id'] = $this->session->userdata('khatabook_company_id');
				$data['created_at'] = date('d-m-Y H:i:s');
				$return = $this->user_model->saveAry('khatabook_transaction', $data);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('khatabook/transaction');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
					redirect('khatabook/transaction');
				}
			}
		}
		$data['page'] = 'transaction/index';
		$this->load->view('khatabook/main', $data);
	}

	public function edit_transaction($id = null)
	{
		if ($_POST) {
			$rules = $this->transaction_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
			} else {
				$data['party_id'] = $this->input->post('party');
				$data['remark'] = $this->input->post('remark');
				$data['amount'] = $this->input->post('amount');
				$data['transaction_type'] = $this->input->post('transaction_type');
				$data['updated_at'] = date('d-m-Y H:i:s');
				$return = $this->user_model->updateAry('khatabook_transaction', $data, ['id' => $id]);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Transaction Updated Successfully.', 'class' => 'success']);
					redirect('khatabook/transaction');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
					redirect('khatabook/transaction');
				}
			}
		}
		$user = $this->session->userdata('user_id');
		$where = ['user_id' => $user, 'id' => $id];
		$data['result'] = $this->user_model->getRow('khatabook_transaction', $where);
		$data['page'] = 'transaction/edit';
		$this->load->view('khatabook/main', $data);
	}



	public function transaction()
	{

		$user_id = $this->session->userdata('user_id');
		$data = array();
		// Get record count 
		$conditions['returnType'] = 'count';
		$user_id = $this->session->userdata('user_id');
		$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id')];
		$totalRec = $this->user_model->getCommonRows($conditions, 'khatabook_transaction');
		// Pagination configuration 
		$config['target']      = '#dataList';
		$config['base_url']    = base_url('khatabook/load_khatabook_transaction');
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $this->perPage;
		$config['link_func']   = 'searchFilter';

		// Initialize pagination library 
		$this->ajax_pagination->initialize($config);
		// Get records 
		$conditions['limit'] = $this->perPage;
		$data['result'] = $this->user_model->getCommonRows($conditions, 'khatabook_transaction');
		// Load the list page view
		$data['page'] = 'transaction/index';
		$this->load->view('khatabook/main', $data);
	}


	function load_khatabook_transaction()
	{
		// Define offset 
		$page = $this->input->post('page');
		if (!$page) {
			$offset = 0;
		} else {
			$offset = $page;
		}

		$keywords = $this->input->post('keywords');
		$sortBy = $this->input->post('sortBy');
		$pagelength = $this->input->post('pagelength');
		$column_filter = $this->input->post('column_filter');
		if ($column_filter == 'party_id') {
			$keywords = $this->serachPartybynameForgettingid($keywords);
		}
		$user_id = $this->session->userdata('user_id');
		$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id')];
		// Get record count 
		$conditions['returnType'] = 'count';
		if (!empty($keywords)) {
			$conditions['search']['keywords'] = $keywords;
		}
		if (!empty($sortBy)) {
			$conditions['search']['sortBy'] = $sortBy;
		}
		$totalRec = $this->user_model->getCommonRows($conditions, 'khatabook_transaction', $column_filter);
		// Pagination configuration 
		$config['target']      = '#dataList';
		$config['base_url']    = base_url('khatabook/load_khatabook_transaction');
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $pagelength;
		$config['link_func']   = 'searchFilter';
		// Initialize pagination library 
		$this->ajax_pagination->initialize($config);
		// Get records 
		$conditions['start'] = $offset;
		$conditions['limit'] = $pagelength;
		unset($conditions['returnType']);
		$data['result'] = $this->user_model->getCommonRows($conditions, 'khatabook_transaction', $column_filter);
		// Load the data list view 
		$this->load->view('khatabook/transaction/load_transaction', $data, false);
	}

	public function delete_transaction($id = null)
	{
		if (isset($id) && $id != '') {
			$user_id = $this->session->userdata('user_id');
			$where = ['id' => $id, 'user_id' => $user_id];
			$return = $this->user_model->deleteRow('khatabook_transaction', $where);
			if ($return) {
				$this->session->set_flashdata(['msg' => 'Transaction Successfully Deleted.', 'class' => 'success']);
				redirect('khatabook/transaction');
			} else {
				$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
				redirect('khatabook/transaction');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
			redirect('khatabook/transaction');
		}
	}

	public function serachPartybynameForgettingid($serach_value)
	{
		$party_id = '';
		$this->db->select('*');
		$this->db->from('khatabook_party');
		$this->db->where('khatabook_company_id', $this->session->userdata('khatabook_company_id'));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->like('party_name', $serach_value);
		$result = $this->db->get()->row();
		if ($result) {
			$party_id = $result->id;
		}
		return $party_id;
	}

	public function user_transaction($party_id)
	{

		$user_id = $this->session->userdata('user_id');
		$this->session->set_userdata('party_id', $party_id);
		$data = array();
		// Get record count 
		$conditions['returnType'] = 'count';
		$user_id = $this->session->userdata('user_id');
		$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id'), 'party_id' => $party_id];
		$totalRec = $this->user_model->getCommonRows($conditions, 'khatabook_transaction');
		// Pagination configuration 
		$config['target']      = '#dataList';
		$config['base_url']    = base_url('khatabook/user_load_khatabook_transaction');
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $this->perPage;
		$config['link_func']   = 'searchFilter';

		// Initialize pagination library 
		$this->ajax_pagination->initialize($config);
		// Get records 
		$conditions['limit'] = $this->perPage;
		$data['result'] = $this->user_model->getCommonRows($conditions, 'khatabook_transaction');
		// Load the list page view
		$data['page'] = 'transaction/user_transaction';
		$this->load->view('khatabook/main', $data);
	}


	function user_load_khatabook_transaction()
	{
		// Define offset 
		$page = $this->input->post('page');
		if (!$page) {
			$offset = 0;
		} else {
			$offset = $page;
		}

		$keywords = $this->input->post('keywords');
		$sortBy = $this->input->post('sortBy');
		$pagelength = $this->input->post('pagelength');
		$column_filter = $this->input->post('column_filter');
		$transaction_type = $this->input->post('transaction_type');

		if ($column_filter == 'party_id') {
			$keywords = $this->serachPartybynameForgettingid($keywords);
		}
		$user_id = $this->session->userdata('user_id');
		$party_id = $this->session->userdata('party_id');
		if (empty($transaction_type)) {
			$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id'), 'party_id' => $party_id];
		} else {
			$conditions['where'] = ['user_id' => $user_id, 'khatabook_company_id' => $this->session->userdata('khatabook_company_id'), 'party_id' => $party_id, 'transaction_type' => $transaction_type];
		}

		// Get record count 
		$conditions['returnType'] = 'count';
		if (!empty($keywords)) {
			$conditions['search']['keywords'] = $keywords;
		}
		if (!empty($sortBy)) {
			$conditions['search']['sortBy'] = $sortBy;
		}
		$totalRec = $this->user_model->getCommonRows($conditions, 'khatabook_transaction', $column_filter);
		// Pagination configuration 
		$config['target']      = '#dataList';
		$config['base_url']    = base_url('khatabook/user_load_khatabook_transaction');
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $pagelength;
		$config['link_func']   = 'searchFilter';
		// Initialize pagination library 
		$this->ajax_pagination->initialize($config);
		// Get records 
		$conditions['start'] = $offset;
		$conditions['limit'] = $pagelength;
		unset($conditions['returnType']);
		$data['result'] = $this->user_model->getCommonRows($conditions, 'khatabook_transaction', $column_filter);
		// Load the data list view 
		$this->load->view('khatabook/transaction/load_user_transaction', $data, false);
	}
}
