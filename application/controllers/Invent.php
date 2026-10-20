<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Invent extends CI_Controller
    {
    	function __construct()
    	{
            parent::__construct();
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
    	}    
    
    	public function index()
    	{
            $user_id = $this->session->userdata('user_id');
            $where = ['user_id' => $user_id];
            $data['inventory_billing'] = $this->user_model->getAry('inventory_billing', '', '', '', $where);
            $data['page'] = 'admin_details/inventory_billing/index';
            return $this->load->view('admin', $data);
    	}
    
    	public function billing_format()
    	{
    		$data['billing_format'] = $this->user_model->getAry('billing_format', '', '', '', '');
    		$data['page'] = 'inventory_sep/bill_format';
    		$this->load->view('inventory', $data);
    	}
    
    
    	public function tax()
    	{
    		$data['tax'] = $this->user_model->getAry('tax', '', '', '', '');
    		$data['page'] = 'inventory_sep/tax';
    		$this->load->view('inventory', $data);
    	}
    
    	public function ladger()
    	{
    		return redirect('ladger');
    	}
    
    	public function recycle()
    	{
    		// $data['tax'] = $this->user_model->getAry('tax', '', '', '', '');
    		// $data['page'] = 'inventory_sep/tax';
    		// $this->load->view('inventory', $data);
    		// redirect()
    
    	}
    	public function accounting_group()
    	{
    		$data['accounting_group'] = $this->user_model->getAry('accounting_group', '', '', '', '');
    		$data['page'] = 'inventory_sep/accounting_group';
    		$this->load->view('inventory', $data);
    	}
    	// public function product()
    	// {
    	// 	redirect('product');
    	// }
    	public function product()
    	{
    		return redirect('product');
    	}
    
    	public function estimate_report()
    	{
    		return redirect('estimate/estimate_report');
    	}
    
    
    
    	public function stocks_report()
    	{
    		redirect('stocks_report');
    	}
    	public function party()
    	{
    		return redirect('party');
    	}
        
    	public function unit()
    	{
            $data['unit'] = $this->user_model->getAry('unit', '', '', '', '');
            $data['page'] = 'unit';
            return $this->load->view('inventory_sep/inventory', $data);
    	}
    
    	public function comp_profile()
    	{
    		redirect('comp_profile');
    	}
    
    	public function customer_report()
    	{
    		$data['page'] = 'inventory_sep/customer_report/index';
    		$this->load->view('inventory', $data);
    	}
    	
    		public function ledger_report()
    	{
    
    		$ledger = '';
    		if ($this->input->get('ledger')) {
    			$ledger = $this->input->get('ledger');
    		}
    
    		$user_id = $this->session->userdata('user_id');
    		$comp_id = $this->session->userdata('comp_id');
    		if (!empty($ledger)) {
    			$where = ['comp_id' => $comp_id, 'user_id' => $user_id, 'ladger' => $ledger];
    		} else {
    			$where = ['comp_id' => $comp_id, 'user_id' => $user_id];
    		}
    		$order = ['col' => 'date', 'order' => 'DESC'];
    		$data['payment'] = $this->user_model->getAry('payment', $order, '', '', $where);
    		$data['ladger'] = $this->user_model->getAry('ladger', '', '', '', ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')]);
    
    		$data['page'] = 'inventory_sep/ladger/ledger_report';
    		$this->load->view('inventory', $data);
    	}
    
    	public function sms_template()
    	{
    		redirect('sms_template');
    	}
    	public function productCode()
    	{
    		redirect('productcode');
    	}
    	public function payment()
    	{
    		redirect('payment');
    	}
    	public function qrcode()
    	{
    		redirect('qrcode');
    	}
    	public function finencial_year()
    	{
    		$data['year'] = $this->user_model->getAry('finenncial_year', '', '', '', '');
    		$data['page'] = 'inventory_sep/finencial_year';
    		$this->load->view('inventory', $data);
    	}
    	public function raw_material()
    	{
    		$data['raw_material'] = $this->user_model->getAry('raw_material', '', '', '', '');
    		$data['page'] = 'inventory_sep/raw_material';
    		$this->load->view('inventory', $data);
    	}
    	public function sub_group()
    	{
    		$data['sub_group'] = $this->user_model->getAry('sub_group', '', '', '', '');
    		$data['page'] = 'inventory_sep/sub_group';
    		$this->load->view('inventory', $data);
    	}
    
    	public function series()
    	{
    		redirect('series');
    	}
    
    	public function seriestitle()
    	{
    		$data['seriestitle'] = $this->user_model->getAry('seriestitle', '', '', '', '');
    
    		$data['page'] = 'inventory_sep/seriestitle/index';
    		// $data['title'] = 'MIS | Master';
    		// $data['menu'] = 'Series';
    		$this->load->view('inventory', $data);
    	}
        
    	public function sale()
    	{
            $data['page'] = 'sale/index';
            return $this->load->view('inventory_sep/inventory', $data);
    	}
    
    	public function credit_note()
    	{
    		return redirect('credit_note');
    	}
    	
    	public function debit_note()
    	{
    		return redirect('debit_note');
    	}
    	
    	public function estimate()
    	{
            $data['page'] = 'estimate/index';
            $data['NewFinancialYear'] = null;
            $data['NewFinancialYearFlag'] = null;
            $data['ChequeData'] = null;
            return $this->load->view('inventory_sep/inventory', $data);
    	}
    	
    	public function credit_note_report()
    	{
    		return redirect('credit_note/report');
    	}
    	
    	public function debit_note_report()
    	{
    		return redirect('debit_note/report');
    	}
    	
    	public function sale_report()
    	{
    		redirect('sale/sale_report');
    	}
    	
    	public function gst_report()
    	{
    		redirect('gst_report');
    	}
    	
    	public function purchase_report()
    	{
    		redirect('purchase/purchase_report');
    	}
    	
    	public function purchase()
    	{
            $data['page'] = 'purchase/index';
            return $this->load->view('inventory_sep/inventory', $data);
    	}
    	
    	public function view($id = null)
    	{
    		$user_id = $this->session->userdata('user_id');
    
    		$where = ['id' => $id, 'user_id' => $user_id];
    		// $this->session->userdata('role');
    
    		// $return = $this->user_model->getRow('users', $where);
    		if ($id && $id != null && $user_id != null) {
    			// 	$data['userinfo'] = $return;
    			// 	$where = ['status' => 1];
    			// $data['year'] = $this->user_model->getAry('year');
    			$inventory = $this->user_model->getAry('inventory_billing', '', '', '', $where);
    			if ($inventory) {
    				$data['inventory_billing'] = $inventory;
    				$data['page'] = 'admin_details/inventory_billing/view';
    				$this->load->view('inventory', $data);
    			} else {
    				$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
    				redirect('inventory_billing');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
    			redirect('inventory_billing');
    		}
    	}
    	public function save()
    	{
    
    		if ($_POST) {
    			// print_r($_POST);
    			// die;
    			$data['company_name'] = $this->input->post('name');
    			$data['address'] = $this->input->post('address');
    			$data['gst_no'] = $this->input->post('gst');
    			$data['number'] = $this->input->post('number');
    			$data['contact_person'] = $this->input->post('contact');
    			$data['email'] = $this->input->post('email');
    			$data['accounting'] = $this->input->post('ac');
    			$data['billing_format_id'] = $this->input->post('cate_id');
    			$data['user_id'] = $this->session->userdata('user_id');
    			$result = $this->user_model->saveAry('inventory_billing', $data);
    			if ($result) {
    				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
    				redirect('inventory_billing');
    			} else {
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('inventory_billing');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			redirect('inventory_billing');
    		}
    	}
    
    	public function update()
    	{
    		$id = $this->input->post('id');
    
    		if ($id && $id != null) {
    			// print_r($_POST);
    			// die;
    			$data['company_name'] = $this->input->post('name');
    			$data['address'] = $this->input->post('address');
    			$data['gst_no'] = $this->input->post('gst');
    			$data['number'] = $this->input->post('number');
    			$data['contact_person'] = $this->input->post('contact');
    			$data['email'] = $this->input->post('email');
    			$data['accounting'] = $this->input->post('ac');
    			$data['billing_format_id'] = $this->input->post('cate_id');
    			$where = ['id' => $id];
    			$result = $this->user_model->updateAry('inventory_billing', $data, $where);
    			if ($result) {
    				$this->session->set_flashdata(['msg' => 'Record updated Successfully.', 'class' => 'success']);
    				redirect('inventory_billing');
    			} else {
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('inventory_billing');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'could not find data.', 'class' => 'danger']);
    			redirect('inventory_billing');
    		}
    	}
    
    
    
    	public function edit($id)
    	{
    		$where = ['id' => $id];
    		$return = $this->user_model->getRow('inventory_billing', $where);
    
    		if ($return) {
    			$data['inventory_billing'] = $return;
    			$data['page'] = 'admin_details/inventory_billing/edit';
    			$this->load->view('admin', $data);
    		} else {
    			// $data['page'] = 'admin_details/userlist';
    			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
    			redirect('inventory_billing');
    		}
    	}
    
    	public function delete($id)
    	{
    		if (isset($id) && $id != '') {
    			$where = ['id' => $id];
    
    			$return = $this->user_model->deleteRow('inventory_billing', $where);
    			if ($return) {
    
    				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
    				redirect('inventory_billing');
    			} else {
    				$data['page'] = 'admin_details/inventory_billing/inventory_billing_cat';
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('inventory_billing');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
    			redirect('inventory_billing');
    		}
    	}
        
        public function salesman()
        {
            $data['salesmans'] = $this->user_model->getResult('salesman', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
            $data['page'] = "admin_details/salesman/index";
            
            return $this->load->view('inventory', $data);
        }
        
        public function warehouse()
        {
            $data['warehouses'] = $this->user_model->getResult('warehouse', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
            $data['page'] = "warehouse/index";
            
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function stock_transfer()
        {
            redirect(base_url() . "warehouse/stock_transfer");
        }
        
        public function stock_transfer_report()
        {
            redirect(base_url() . "warehouse/report");
        }
        
        public function salesman_request_report()
        {
            redirect(base_url() . "salesman/salesman_request_report");
        }
        
        public function estimate_purchase()
        {
            redirect(base_url() . "estimate_purchase");
        }
        
        public function estimate_purchase_report()
        {
            redirect(base_url() . "estimate_purchase/report");
        }
    }