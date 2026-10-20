<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_service extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
				$this->load->helper('action_helper');

		// $this->load->library('custom');
		if ($this->session->userdata('user_id') === null) {
			redirect('index');
		}
	}
	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => 'name',
				'rules' => 'trim|required|max_length[60]'
			),
			array(
				'field' => 'des',
				'label' => 'des',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'price',
				'label' => 'price',
				'rules' => 'trim|required'
			),

		);
		return $rules;
	}

	public function index($start = 0)
	{
		$limit = "";
		$order = ['order' => 'DESC'];
		$role_id = $this->session->userdata('roles');
		$user_id = $this->session->userdata('user_id');

		if ($role_id == 1) {
			$where = ['user_type' => 1];
			// $data['userlist'] = $this->user_model->getAry('users', '', '', '', $where);
			$data['product_service'] = $this->user_model->getAry('product_service', $order, $start, $limit, $where);
		} else if ($role_id == 2) {
			$where = ['user_type' => 2, 'user_id' => $user_id];

			// $data['userlist'] = $this->user_model->getAry('users', '', '', '', $where);
			$data['product_service'] = $this->user_model->getAry('product_service', $order, $start, $limit, $where);
		} else if ($role_id == 3) {
			$where = ['user_id' => $this->session->userdata('user_id')];

			// $data['userlist'] = $this->user_model->getAry('users', '', '', '', $where);
			$data['product_service'] = $this->user_model->getAry('product_service', $order, $start, $limit, $where);
		} else {
			$userlist = [];
			$where = ['emp_id' => $this->session->userdata('user_id'), 'usertype' => 4];
			$employye = $this->user_model->getAry('subcat', $order, $start, $limit, $where);

			// $employye = $this->user_model->getAry('subcat', '', '', '', $where);
			foreach ($employye as $emp) {
				$user_id = $emp->userid;
				$where = ['id' => $user_id];
				$userlist[] = $this->user_model->getRow('product_service', $where);
			}
			// echo '<pre>';
			// print_r($userlist);
			// die;
			$data['product_service'] = $userlist;
		}
		$userlist = $data['product_service'];


		$data['page'] = 'admin_details/product_service/index';
		$this->load->view('admin', $data);
	}

	public function getproduct_services()
	{
		$product_service = $this->db->select('id, p_name')->order_by('p_name', 'ASC')->get_where('product_service', ['status' => 1])->result();
		if (isset($product_service) && !empty($product_service)) {
			echo json_encode(['status' => true, 'data' => $product_service]);
		} else {
			echo json_encode(['status' => true, 'data' => 'Data not found.']);
		}
	}
	public function getProduct_service($id)
	{
		if (!isset($id) && empty($id)) {
			echo json_encode(['status' => false, 'data' => 'Id required.']);
		} else {
			$user_id = $this->session->user_id;
			$comp_id = $this->session->comp_id;
			$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'id' => $id];
			// $product_service = $this->db->select('*')->get_where('product_service', $where)->row();
			$product_service =	$this->user_model->getRow('product_service', $where);
			if (isset($product_service) && !empty($product_service)) {
				// $there = ['id' => $product_service->tax];
				// $product_service['tax'] =	$this->user_model->getAry('tax', '', '', '', $there);
				// // $product_service['unit'] =	$this->user_model->getAry('unit');



				// $product_service['product_service'] = $product_service;

				echo json_encode(['status' => true, 'data' => $product_service]);
			} else {
				echo json_encode(['status' => true, 'data' => 'Data not found.']);
			}
		}
	}

	public function proInfo()
	{
		$id = $this->input->post('id');

		if (!isset($id) && empty($id)) {
			echo json_encode(['status' => false, 'data' => 'Id required.']);
		} else {
			$user_id = $this->session->user_id;
			$comp_id = $this->session->comp_id;
			$where = ['product_service_id' => $id];
			// $product_service = $this->db->select('*')->get_where('product_service', $where)->row();
			$product_service =	$this->user_model->getRow('invoice_product_service', $where);
			if (isset($product_service) && !empty($product_service)) {
				// $there = ['id' => $product_service->tax];
				// $product_service['tax'] =	$this->user_model->getAry('tax', '', '', '', $there);
				// // $product_service['unit'] =	$this->user_model->getAry('unit');



				// $product_service['product_service'] = $product_service;

				echo json_encode(['status' => true, 'data' => $product_service]);
			} else {
				echo json_encode(['status' => true, 'data' => 'Data not found.']);
			}
		}
	}

	public function edit($id)
	{
		if (isset($id) && !empty($id)) {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('product_service', $where);
			if ($return) {
				$data['product_Service'] = $return;
				$data['page'] = 'admin_details/product_service/edit';
				$this->load->view('admin', $data);
			} else {
				// $data['page'] = 'admin_details/parties/parties_cat';
				// $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('product_service');
			}
		} else {
			redirect('product_service');
		}
	}

	public function save()
	{

		if ($_POST) {
			// echo '<pre>';
			// print_r($_POST);
			// die;
			// $this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[product_service.name]');
			// $this->form_validation->set_rules('qty', 'Qty', 'required|trim');
			// $this->form_validation->set_rules('price', 'Price', 'required|trim');
			// $this->form_validation->set_rules('unit', 'Unit', 'required|trim');
			// $this->form_validation->set_rules('tax', 'Tax', 'required|trim');

			// $this->form_validation->set_rules('weight', 'Weight', 'required|trim');
			// $this->form_validation->set_rules('min_qty', 'Min Qty', 'required|trim');


			// if ($this->form_validation->run() == FALSE) {
			// 	$this->data['form_validation'] = validation_errors();
			// 	$this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
			// 	$data['page'] = 'admin_details/product_service/index';
			// 	$this->load->view('admin', $data);
			// } else {
			$data['user_id'] = $this->session->user_id;
			$data['user_type'] = $this->session->roles;
			$data['name'] = $this->input->post('name');
			$data['des'] = $this->input->post('long_des');
			$data['unit'] = $this->input->post('unit');
			$data['amount'] = $this->input->post('price');
			$result = $this->user_model->saveAry('product_service', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('product_service');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('product_service');
			}
		} else {
			redirect('product_service');
		}
	}

	public function purchase($id)
	{
		if (!empty($id) && isset($id)) {
			$where = ['id' => $id];
			$data['user_id'] = $this->session->user_id;
			$data['user_type'] = $this->session->roles;

			$data['pro_id'] = $id;
			$data['order_id']="ORDER".rand(0,1000)."BUY";
			$product_service = $this->user_model->getRow('product_service', $where);
			$data['charged']=$product_service->amount;
			$result = $this->user_model->saveAry('product_service_apply', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('service_product');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('service_product');
			}

			// echo '<pre>';
			// print_r($product_service);
			// die;
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('service_product');
		}
	}

	public function update()
	{
		$id = $this->input->post('id');
		if (isset($id) && !empty($id)) {
			$where = ['id' => $id];
			$yes_find_id = $this->user_model->getRow('product_service', $where);
			if ($yes_find_id) {
				$data['name'] = $this->input->post('name');
				$data['des'] = $this->input->post('long_des');
				$data['unit'] = $this->input->post('unit');
				$data['amount'] = $this->input->post('price');
				$updaated_data = $this->user_model->updateAry('product_service', $data, $where);
				if ($updaated_data) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('product_service');
				} else {
					$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
					redirect('product_service');
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
				redirect('product_service');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
			redirect('product_service');
		}
	}

	public function savePro()
	{

		if (!$this->input->post()) {
			echo json_encode(['status' => false, 'msg' => 'Please enter required fields', 'class' => 'danger']);
			return;
		}
		$this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[product_service.p_name]');
		$this->form_validation->set_rules('qty', 'Qty', 'required|trim');
		$this->form_validation->set_rules('price', 'Price', 'required|trim');
		$this->form_validation->set_rules('unit', 'Unit', 'required|trim');
		$this->form_validation->set_rules('tax', 'Tax', 'required|trim');

		$this->form_validation->set_rules('weight', 'Weight', 'required|trim');
		$this->form_validation->set_rules('min_qty', 'Min Qty', 'required|trim');

		if ($this->form_validation->run() == false) {
			echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
			return;
		}


		$data['p_name'] = $this->input->post('name');
		$data['qty'] = $this->input->post('qty');
		$data['price'] = $this->input->post('price');
		$data['discount'] = $this->input->post('discount');
		$data['short_de'] = $this->input->post('short_des');
		$data['long_de'] = $this->input->post('long_des');
		$data['tax'] = $this->input->post('tax');
		$data['unit'] = $this->input->post('unit');
		$data['purchase_rate'] = $this->input->post('purchase_rate');
		$data['weight'] = $this->input->post('weight');
		$data['min_qty'] = $this->input->post('min_qty');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['comp_id'] = $this->session->userdata('comp_id');
		$data['hsn'] = $this->session->userdata('gsn_code');
		$unit_id = $this->input->post('unit');
		$tax = $this->input->post('tax');


		$where = ['id' => $unit_id];

		$unit_name = $this->user_model->getRow('unit', $where);
		$data['unit_name'] = $unit_name->name;

		$where = ['id' => $tax];

		$taxaty = $this->user_model->getRow('tax', $where);
		$data['tax_qty'] = $taxaty->tax_per;


		$data['cat_id'] = $this->input->post('cate');
		$file_name = $_FILES['image']['name'];
		$file_size = $_FILES['image']['size'];
		$file_tmp = $_FILES['image']['tmp_name'];
		$file_type = $_FILES['image']['type'];

		if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
			move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/product_service/' . $file_name);
			$data['image'] = $file_name;
			$result = $this->user_model->saveAry('product_service', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('invent/product_service');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('invent/product_service');
			}
		} else {
			$data['error'] = "Only png,jpg and jpeg allow";
			$data['page'] = 'admin_details/product_service';
			$this->load->view('admin', $data);
		}
	}
	public function pro_name()
	{
		$pro_id = $this->input->post('id');
		$rownumber = $this->input->post('rownumber');

		$where = ['id' => $pro_id];
		$result1 = $this->user_model->getRow('product_service', $where);
		// print_r($tax_id);
		// die;
		$result = $this->user_model->getAry('product_service', '', '', '', '');
		$html = '';
		// $html .= "<select class='form-control' onchange='ChangeTax(this.value,$rownumber)' id='tax" . $rownumber . "' name='product_service[$rownumber][tax]' required>";
		$html .= "<select class='form-control' name='product_service[$rownumber][product_service]' style='width: 195px !important;' id='product_serviceId$rownumber' class='form-control searchable_select' onchange='onProduct_service_serviceselect($rownumber,event)' onmouseover='showModelPop($rownumber,event)' onfocusout='onProduct_serviceFocusChange(event,$rownumber)' required='true'>";

		$html .= "<option value='' >-Select-</option>";
		if ($result1 != null) {
			$tax_per = $result1->p_name;

			$html .= "<option value=" . $pro_id . " selected >" . $tax_per . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->p_name . "</option>";
		}
		$html . "</select>";

		print_r($html);
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('product_service', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('product_service');
			} else {
				$data['page'] = 'admin_details/product_service/product_service_cat';
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('product_service');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);

			redirect('product_service');
		}
	}
	public function changeStatus()
	{

		$data['status'] = $this->input->post('status');
		$service_id = $this->input->post('service_id');
		$result = $this->user_model->updateAry('product_service', $data, ['id' => $service_id]);
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
	public function load_product_service()
	{
		$data['page'] = 'admin_details/product_service/product_service_popup';
		$this->load->view('admin', $data);
	}
}
