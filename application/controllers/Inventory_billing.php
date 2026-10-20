<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    // Inventory table and comp profile are connected!
    class Inventory_billing extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);

            $this->load->model('user_model');
            $this->load->library('ajax_pagination_dashboard');
            
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
            $user_id = $this->session->userdata('user_id');
            $where = ['user_id' => $user_id, 'status' => 1];
            $data['inventory_billing'] = $this->user_model->getAry('inventory_billing', '', '', '', $where);
            $data['page'] = 'admin_details/inventory_billing/index';
            $this->load->view('admin', $data);
	}
        
	public function view($id = null)
	{
            $user_id = $this->session->userdata('user_id');
            $where = ['id' => $id, 'user_id' => $user_id];
            if ($id && $id != null && $user_id != null) {
                $inventory = $this->user_model->getAry('inventory_billing', '', '', '', $where);
                if ($inventory) {
                    $this->session->unset_userdata('BranchRole');
                    $this->session->unset_userdata('CompBranchId');
                    $this->session->unset_userdata('comp_id');
                    
                    $this->session->set_userdata('comp_id', $id);
                    $this->session->set_userdata('state', $inventory[0]->state);
                    $this->session->set_userdata('gst_nos', $inventory[0]->gst_no);
                    $this->session->set_userdata('numbers', $inventory[0]->number);
                    $this->session->set_userdata('address', $inventory[0]->address);
                    $this->session->set_userdata('format_of_bill', $inventory[0]->billing_format_id);
                    
                    $comp_id = $this->session->userdata('comp_id');
                    $there = ['comp_id' => $comp_id, 'user_id' => $user_id];
                    $comp_prfiles = $this->user_model->getRow('comp_profile', $there);
                    if ($comp_prfiles) {
                        $this->session->set_userdata('financial_year', $comp_prfiles->financial_year);
                        $this->session->set_userdata('barcode', $comp_prfiles->barcode_con);
                        $this->session->set_userdata('lrdate', $comp_prfiles->lrdate);
                        $this->session->set_userdata('eway', $comp_prfiles->eway);
                        $this->session->set_userdata('lrnumber', $comp_prfiles->lrnumber);
                        $this->session->set_userdata('duedate', $comp_prfiles->duedate);
                        $this->session->set_userdata('ptd', $comp_prfiles->ptd);
                        $this->session->set_userdata('dpb', $comp_prfiles->dpb);
                        $this->session->set_userdata('dcdate', $comp_prfiles->dcdate);
                        $this->session->set_userdata('dcno', $comp_prfiles->dcno);
                        $this->session->set_userdata('discount', $comp_prfiles->discount);
                        $this->session->set_userdata('estimate_format', $comp_prfiles->estimate_format);
                        $this->session->set_userdata('estimate_stock', $comp_prfiles->estimate_stock);
                        $this->session->set_userdata('salesman_sale', $comp_prfiles->salesman_sale);
                        $this->session->set_userdata('salesman_estimate', $comp_prfiles->salesman_estimate);
                    }
                    $data['inventory_billing'] = $inventory;
                    $data['page'] = 'admin_details/inventory_billing/view';

                    $this->load->view('inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Company did not find data.', 'class' => 'danger']);
                    redirect('inventory_billing');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Company and user did not find data.', 'class' => 'danger']);
                redirect('inventory_billing');
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
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|max_length[60]|valid_email'
                ),
                array(
                    'field' => 'number',
                    'label' => 'number',
                    'rules' => 'trim|max_length[25]|min_length[5]'
                ),
                array(
                    'field' => 'state',
                    'label' => 'state',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'gst',
                    'label' => 'gst',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'address',
                    'label' => 'address',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'cate_id',
                    'label' => 'cate_id',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'ac',
                    'label' => 'ac',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'contact',
                    'label' => 'contact',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'financial_year',
                    'label' => 'financial_year',
                    'rules' => 'trim|required'
                ),
            );
            return $rules;
	}

	public function save()
	{
            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['company_name'] = $this->input->post('name');
                    $data['address'] = $this->input->post('address');
                    $data['gst_no'] = $this->input->post('gst');
                    $data['number'] = $this->input->post('number');
                    $data['contact_person'] = $this->input->post('contact');
                    $data['email'] = $this->input->post('email');
                    $data['state'] = $this->input->post('state');
                    $data['accounting'] = $this->input->post('ac');
                    $data['financial_year'] = $this->input->post('financial_year');
                    $data['billing_format_id'] = $this->input->post('cate_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['serial_number'] = rand('10000', '99999');

                    $result = $this->user_model->saveAry('inventory_billing', $data);
                    
                    $cash_party = array(
                        'name' => 'Cash',
                        'state' => $this->input->post('state'),
                        'state_code' => get_title('state_list', ['id' => $this->input->post('state')], 'code'),
                        'city' => 74,
                        'comp_id' => $result,
                        'user_id' => $this->session->userdata('user_id'),
                    );
                    $this->user_model->saveAry('parties', $cash_party);
			
                    $warehouse = [
                        'name' => "Main",
                        'user_id' => $this->session->userdata('user_id'),
                        'comp_id' => $result,
                        'created' => date('d-m-Y h:i:s A')
                    ];
                    $warehouse_id = $this->user_model->saveAry('warehouse', $warehouse);
                    
                    $beta['comp_id'] = $result;
                    $beta['name'] = $this->input->post('name');
                    $beta['user_id'] = $this->session->userdata('user_id');
                    $beta['financial_year'] = $this->input->post('financial_year');
                    $beta['default_warehouse'] = $warehouse_id;
                    $beta['warehouse'] = $warehouse_id;
                    $beta['warehouse_estimate'] = $warehouse_id;
                    $beta['warehouse_purchase'] = $warehouse_id;
                    $beta['warehouse_estimate_purchase'] = $warehouse_id;
                    $this->user_model->saveAry('comp_profile', $beta);
				
                    $CompInfo['user_id'] = $this->session->userdata('user_id');
                    $CompInfo['comp_id'] = $result;

                    $this->user_model->saveAry('a5config', $CompInfo);
                    $this->user_model->saveAry('purchase_config', $CompInfo);
                    $this->user_model->saveAry('estimateconfig', $CompInfo);
                    $this->user_model->saveAry('estimate_purchase_config', $CompInfo);
                    $this->user_model->saveAry('credit_note_config', $CompInfo);
                    $this->user_model->saveAry('debit_note_config', $CompInfo);
                    $this->user_model->saveAry('proforma_config', $CompInfo);
                    
                    $EmployeeCompany = [
                        "UserId" => $this->session->userdata('user_id'),
                        "CompId" => $result,
                        "Created" => date('d-m-Y h:i:s A'),
                        "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                    ];
                    $this->user_model->saveAry('employee_config', $EmployeeCompany);
                    
                    // Copy product
                    if ($this->input->post('CopyProduct') == 'Yes') {
                        $RoleId = get_title('roles', ['name' => 'admin'], 'id'); 
                        $UserId = get_title('users', ['userType' => $RoleId], 'id'); 
                        $CompanyId = get_title('inventory_billing', ['user_id' => $UserId], 'id');
                        
                        $Products = $this->db->get_where('product', array('user_id' => $UserId, 'comp_id' => $CompanyId, 'status' => 1))->result();
                        if (!empty($Products)) {
                            foreach ($Products as $product) {
                                $ProductArray = (array) $product;
                                $ProductArray['qty'] = 0;
                                $ProductArray['min_qty'] = 0;
                                $ProductArray['created_at'] = date('Y-m-d H:i:s');
                                $ProductArray['user_id'] = $this->session->userdata('user_id');
                                $ProductArray['comp_id'] = $result;
                                $ProductArray['warehouse'] = $warehouse_id;

                                $ProductsData = array();
                                foreach ($ProductArray as $key => $Value) {
                                    if ($key !== 'id') {
                                        $ProductsData[$key] = $Value;
                                    }
                                }                                
                                $this->db->insert('product', $ProductsData);
                            }
                        }
                    }
                    
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "<h3 class='text-success text-center'>Company Created</h3>"
                        );
                    } else {
                        $response = array(
                            'status' => 'error',
                            'message' => "<h3 class='text-danger text-center'>Something Went Wrong</h3>"
                        );
                    }
                }
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => "<h3 class='text-danger text-center'>Required Field can not Empty</h3>"
                );
            }
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
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
            $data['gst_details'] = $this->input->post('ledger_gst_details');
			$data['number'] = $this->input->post('number');
			$data['contact_person'] = $this->input->post('contact');
			$data['email'] = $this->input->post('email');
			$data['accounting'] = $this->input->post('ac');
			$data['state'] = $this->input->post('state');
 
			$data['billing_format_id'] = $this->input->post('cate_id');
         
			$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id')];
			$result = $this->user_model->updateAry('inventory_billing', $data, $where);
			$beta['name'] = $this->input->post('name');
			$result = $this->user_model->saveAry('comp_profile', $beta);
			$where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
			$result = $this->user_model->updateAry('comp_profile', $beta, $where);
         
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
                    unset($_SESSION['comp_id']);
                    unset($_SESSION['state']);
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    redirect(base_url() . 'inventory_billing');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    redirect(base_url() . 'inventory_billing');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                redirect(base_url() . 'inventory_billing');
            }
	}

	public function viewable($id = null)
	{
            $user_id = $this->session->userdata('user_id');
            $where = ['id' => $id, 'user_id' => $user_id];
            if ($id && $id != null && $user_id != null) {
                $inventory = $this->user_model->getRow('inventory_billing', $where);
                if ($inventory) {
                    $data['result'] = $inventory;
                    $data['page'] = 'admin_details/inventory_billing/comp_info';
                    $this->load->view('inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Something went wrong!', 'class' => 'danger']);
                    redirect('inventory_billing');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Data issue!', 'class' => 'danger']);
                redirect('inventory_billing');
            }
	}
	
        public function viewothercomp($id = null)
	{
            $where = ['id' => $id];
            if ($id && $id != null) {
                $inventory = $this->user_model->getAry('inventory_billing', '', '', '', $where);
                if ($inventory) {
                    $this->session->unset_userdata('BranchRole');
                    $this->session->unset_userdata('CompBranchId');
                    $this->session->unset_userdata('comp_id');
                    
                    if (empty($this->session->userdata('without_assign_user'))) {
                        $this->session->set_userdata('without_assign_user', $this->session->userdata('user_id'));
                    }
                    $user_id = $inventory[0]->user_id;
                    $this->session->set_userdata('comp_id', $id);
                    $this->session->set_userdata('user_id', $inventory[0]->user_id);
                    $this->session->set_userdata('state', $inventory[0]->state);
                    $this->session->set_userdata('gst_nos', $inventory[0]->gst_no);
                    $this->session->set_userdata('numbers', $inventory[0]->number);
                    $this->session->set_userdata('address', $inventory[0]->address);
                    $this->session->set_userdata('format_of_bill', $inventory[0]->billing_format_id);
                    $comp_id = $this->session->userdata('comp_id');
                    $there = ['comp_id' => $comp_id, 'user_id' => $user_id];
                    $comp_prfiles = $this->user_model->getRow('comp_profile', $there);
                    if ($comp_prfiles) {
                        $this->session->set_userdata('financial_year', $comp_prfiles->financial_year);
                        $this->session->set_userdata('barcode', $comp_prfiles->barcode_con);
                        $this->session->set_userdata('lrdate', $comp_prfiles->lrdate);
                        $this->session->set_userdata('eway', $comp_prfiles->eway);
                        $this->session->set_userdata('lrnumber', $comp_prfiles->lrnumber);
                        $this->session->set_userdata('duedate', $comp_prfiles->duedate);
                        $this->session->set_userdata('ptd', $comp_prfiles->ptd);
                        $this->session->set_userdata('dpb', $comp_prfiles->dpb);
                        $this->session->set_userdata('dcdate', $comp_prfiles->dcdate);
                        $this->session->set_userdata('dcno', $comp_prfiles->dcno);
                        $this->session->set_userdata('discount', $comp_prfiles->discount);
                    }
                    $usersData = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
                    $this->session->set_userdata('user_id', $usersData->id);
                    $this->session->set_userdata('roles', $usersData->userType);
                    $this->session->set_userdata('name', $usersData->name);
                    $this->session->set_userdata('email', $usersData->email);
                    $this->session->set_userdata('referral', $usersData->referral);

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
        
        public function redirect($id)
        {
            $user_id = $this->session->userdata('user_id');
            $where = ['id' => $id, 'user_id' => $user_id];
            if ($id && $id != null && $user_id != null) {
                $inventory = $this->user_model->getAry('inventory_billing', '', '', '', $where);
                if ($inventory) {
                    $this->session->unset_userdata('BranchRole');
                    $this->session->unset_userdata('CompBranchId');
                    $this->session->unset_userdata('comp_id');
                    $this->session->set_userdata('comp_id', $id);
                    $this->session->set_userdata('state', $inventory[0]->state);
                    $this->session->set_userdata('gst_nos', $inventory[0]->gst_no);
                    $this->session->set_userdata('numbers', $inventory[0]->number);
                    $this->session->set_userdata('address', $inventory[0]->address);
                    $this->session->set_userdata('format_of_bill', $inventory[0]->billing_format_id);
                    
                    $comp_id = $this->session->userdata('comp_id');
                    $there = ['comp_id' => $comp_id, 'user_id' => $user_id];
                    $comp_prfiles = $this->user_model->getRow('comp_profile', $there);
                    if ($comp_prfiles) {
                        $this->session->set_userdata('financial_year', $comp_prfiles->financial_year);
                        $this->session->set_userdata('barcode', $comp_prfiles->barcode_con);
                        $this->session->set_userdata('lrdate', $comp_prfiles->lrdate);
                        $this->session->set_userdata('eway', $comp_prfiles->eway);
                        $this->session->set_userdata('lrnumber', $comp_prfiles->lrnumber);
                        $this->session->set_userdata('duedate', $comp_prfiles->duedate);
                        $this->session->set_userdata('ptd', $comp_prfiles->ptd);
                        $this->session->set_userdata('dpb', $comp_prfiles->dpb);
                        $this->session->set_userdata('dcdate', $comp_prfiles->dcdate);
                        $this->session->set_userdata('dcno', $comp_prfiles->dcno);
                        $this->session->set_userdata('discount', $comp_prfiles->discount);
                        $this->session->set_userdata('estimate_format', $comp_prfiles->estimate_format);
                        $this->session->set_userdata('estimate_stock', $comp_prfiles->estimate_stock);
                        $this->session->set_userdata('salesman_sale', $comp_prfiles->salesman_sale);
                        $this->session->set_userdata('salesman_estimate', $comp_prfiles->salesman_estimate);
                    }
                    return redirect(base_url() . 'inventory/dashboard');
                } else {
                    $this->session->set_flashdata(['msg' => 'Company did not find data.', 'class' => 'danger']);
                    redirect('inventory_billing');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Company and user did not find data.', 'class' => 'danger']);
                redirect('inventory_billing');
            }
        }

        public function AssignCompanyRedirect($id = null)
        {
            $where = ['id' => $id];
            if ($id && $id != null) {
                $inventory = $this->user_model->getAry('inventory_billing', '', '', '', $where);
                if ($inventory) {
                    $this->session->unset_userdata('BranchRole');
                    $this->session->unset_userdata('CompBranchId');
                    $this->session->unset_userdata('comp_id');
                    
                    if (empty($this->session->userdata('without_assign_user'))) {
                        $this->session->set_userdata('without_assign_user', $this->session->userdata('user_id'));
                    }
                    $user_id = $inventory[0]->user_id;
                    $this->session->set_userdata('comp_id', $id);
                    $this->session->set_userdata('user_id', $inventory[0]->user_id);
                    $this->session->set_userdata('state', $inventory[0]->state);
                    $this->session->set_userdata('gst_nos', $inventory[0]->gst_no);
                    $this->session->set_userdata('numbers', $inventory[0]->number);
                    $this->session->set_userdata('address', $inventory[0]->address);
                    $this->session->set_userdata('format_of_bill', $inventory[0]->billing_format_id);
                    $comp_id = $this->session->userdata('comp_id');
                    $there = ['comp_id' => $comp_id, 'user_id' => $user_id];
                    $comp_prfiles = $this->user_model->getRow('comp_profile', $there);
                    
                    if ($comp_prfiles) {
                        $this->session->set_userdata('financial_year', $comp_prfiles->financial_year);
                        $this->session->set_userdata('barcode', $comp_prfiles->barcode_con);
                        $this->session->set_userdata('lrdate', $comp_prfiles->lrdate);
                        $this->session->set_userdata('eway', $comp_prfiles->eway);
                        $this->session->set_userdata('lrnumber', $comp_prfiles->lrnumber);
                        $this->session->set_userdata('duedate', $comp_prfiles->duedate);
                        $this->session->set_userdata('ptd', $comp_prfiles->ptd);
                        $this->session->set_userdata('dpb', $comp_prfiles->dpb);
                        $this->session->set_userdata('dcdate', $comp_prfiles->dcdate);
                        $this->session->set_userdata('dcno', $comp_prfiles->dcno);
                        $this->session->set_userdata('discount', $comp_prfiles->discount);
                    }
                    
                    $usersData = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
                    $this->session->set_userdata('user_id', $usersData->id);
                    $this->session->set_userdata('roles', $usersData->userType);
                    $this->session->set_userdata('name', $usersData->name);
                    $this->session->set_userdata('email', $usersData->email);
                    $this->session->set_userdata('referral', $usersData->referral);
                    
                    return redirect(base_url() . 'inventory/dashboard');
                } else {
                    $this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
                    redirect('inventory_billing');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
                redirect('inventory_billing');
            }
        }

        public function dashboard ()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $SaleWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            $InvoiceProductWhere = ['invoice_product.user_id' => $this->session->userdata('user_id'), 'invoice_product.comp_id' => $this->session->userdata('comp_id'), 'invoice_product.status' => 1, 'invoice_product.type' => 'Sale'];
            $CompProfileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $SaleWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $InvoiceProductWhere['invoice_product.CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $CompProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            // Outstatding Payment ** Get record count ** 
            $conditions1['returnType'] = 'count'; 
            $conditions1['where'] = $SaleWhere;
            $totalRecOutstatding = $this->user_model->getRowsDataOutstatding($conditions1, 'sales'); 

            // Pagination configuration 
            $config1['target']      = '#OutstatdingList'; 
            $config1['base_url']    = base_url('inventory_billing/AjaxOutstatdingData'); 
            $config1['total_rows']  = $totalRecOutstatding; 
            $config1['per_page']    = 5; 
            $config1['link_func']   = 'searchFilter1'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config1); 

            // Get records 
            $conditions1 = array('limit' => 5);
            $conditions1['where'] = $SaleWhere;
            
            $data['PaymentInfo'] = $this->user_model->getRowsDataOutstatding($conditions1, 'sales'); 
            $data['PaymentPaging'] = $this->ajax_pagination_dashboard->create_links();
            
            // Previous Sale ** Get record count **
            $conditions2['returnType'] = 'count';
            $conditions2['where'] = $SaleWhere;
            $totalRecPreviousSale = $this->user_model->getRowsDataPreviousSale($conditions2, 'sales'); 

            // Pagination configuration 
            $config2['target']      = '#PreviousSaleList'; 
            $config2['base_url']    = base_url('inventory_billing/AjaxPreviousSaleData'); 
            $config2['total_rows']  = $totalRecPreviousSale; 
            $config2['per_page']    = 5; 
            $config2['link_func']   = 'searchFilter2'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config2);

            // Get records 
            $conditions2 = array('limit' => 5);
            $conditions2['where'] = $SaleWhere;

            $data['PreviousSale'] = $this->user_model->getRowsDataPreviousSale($conditions2, 'sales');
            $data['SalePaging'] = $this->ajax_pagination_dashboard->create_links();
            
            // Populler Products ** Get record count **
            $conditions3['returnType'] = 'count';
            $conditions3['where'] = $InvoiceProductWhere;
            $totalRecPopullerProducts = $this->user_model->getRowsDataPopullerProducts($conditions3, 'invoice_product'); 

            // Pagination configuration 
            $config3['target']      = '#PopullerProductsList'; 
            $config3['base_url']    = base_url('inventory_billing/AjaxPopullerProductsData'); 
            $config3['total_rows']  = $totalRecPopullerProducts; 
            $config3['per_page']    = 5; 
            $config3['link_func']   = 'searchFilter3'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config3);

            // Get records 
            $conditions3 = array('limit' => 5);
            $conditions3['where'] = $InvoiceProductWhere;
            $data['SallingProducts'] = $this->user_model->getRowsDataPopullerProducts($conditions3, 'invoice_product');
            $data['ProductPaging'] = $this->ajax_pagination_dashboard->create_links();
            
            $FinancialYearId = get_title('comp_profile', $CompProfileWhere, 'financial_year');
            $data['NewFinancialYear'] = date('Y-m-d', strtotime("+1 day", strtotime(get_title('year', ['id' => $FinancialYearId], 'ending_year'))));
            $data['NewFinancialYearFlag'] = get_title('year', ['id' => $FinancialYearId], 'Flag');
            
            $Sales = $this->db->select('id, MONTH(invoiceDate) AS Month, SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->group_by('Month')->get_compiled_select('sales');
            $Estimates = $this->db->select('id, MONTH(invoiceDate) AS Month, SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->group_by('Month')->get_compiled_select('estimates');
            $SalesEstimatesQuery = '(' . $Sales . ' UNION ' . $Estimates . ') AS SalesEstimatesData';
            $this->db->reset_query();
            
            $this->db->select('MONTHNAME(CONCAT("2000-", Month, "-01")) AS month_name', FALSE);
            $this->db->select_sum('total', 'total');
            $this->db->select_sum('paid_amount', 'paid_amount');
            $this->db->group_by('Month');
            $this->db->order_by('(Month + 8) % 12 + 1');
            $data['SalesTotalPaidBalance'] = $this->db->get($SalesEstimatesQuery)->result();

            $SalesDelivery = $this->db->select('id, delivery_status, delivery_date')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->get_compiled_select('sales');
            $EstimatesDelivery = $this->db->select('id, delivery_status, delivery_date')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->get_compiled_select('estimates');
            $DeliveryQuery = '(' . $SalesDelivery . ' UNION ' . $EstimatesDelivery . ') AS SaleEstimateDelivery';
            $this->db->reset_query();
            
            $this->db->select('id, delivery_status, delivery_date');
            $this->db->order_by('id', 'DESC');
            $data['Assignments'] = $this->db->get($DeliveryQuery)->result();
            
            $TopSales = $this->db->select('customerId, SUM(total) AS Total')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->group_by('customerId')->get_compiled_select('sales');
            $TopEstimates = $this->db->select('customerId, SUM(total) AS Total')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->group_by('customerId')->get_compiled_select('estimates');
            $TopSalesEstimatesQuery = '(' . $TopSales . ' UNION ' . $TopEstimates . ') AS TopSalesEstimatesData';
            $this->db->reset_query();
            
            $this->db->select('customerId');
            $this->db->select_sum('total', 'Total');
            $this->db->group_by('customerId');
            $this->db->order_by('Total', 'DESC');
            $this->db->limit(10);
            $data['Top10SalesCustomers'] = $this->db->get($TopSalesEstimatesQuery)->result();
            
            $TopPurchases = $this->db->select('customerId, SUM(total) AS Total')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->group_by('customerId')->get_compiled_select('purchases');
            $TopEstimatesPurchases = $this->db->select('customerId, SUM(total) AS Total')->where($SaleWhere)->where('financial_year', $this->session->userdata('financial_year'))->group_by('customerId')->get_compiled_select('estimate_purchase');
            $TopPurchasesEstimatesPurchasesQuery = '(' . $TopPurchases . ' UNION ' . $TopEstimatesPurchases . ') AS TopPurchasesEstimatesPurchasesData';
            $this->db->reset_query();
            
            $this->db->select('customerId');
            $this->db->select_sum('total', 'Total');
            $this->db->group_by('customerId');
            $this->db->order_by('Total', 'DESC');
            $this->db->limit(10);
            $data['Top10PurchasesCustomers'] = $this->db->get($TopPurchasesEstimatesPurchasesQuery)->result();
            
            $PaymentModeChequeId = get_title('payment_mode', ['payment_method' => 'Cheque'], 'id');
            $data['ChequeData'] = $this->db->select('party_id, date, debit, ChequeDate, remark')->where($SaleWhere)->where(['paymentmode' => $PaymentModeChequeId, 'ChequeStatus' => 0, 'payment_through' => 'Manually', 'ChequeDate >= ' => date('Y-m-d'), 'ChequeDate <= ' => date('Y-m-d', strtotime('+1 day'))])->order_by('id', 'DESC')->get('payment')->result();
            $data['page'] = 'dashboard';  
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function AjaxOutstatdingData()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $SaleWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $SaleWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $this->perPage = 5;
            // Define offset 
            $page = $this->input->post('page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('keywords'); 
            $sortBy = 'DESC'; 
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $SaleWhere;
            $totalRec = $this->user_model->getRowsDataOutstatding($conditions, 'sales'); 

            // Pagination configuration 
            $config['target']      = '#OutstatdingList'; 
            $config['base_url']    = base_url('inventory_billing/AjaxOutstatdingData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $this->perPage; 
            $config['link_func']   = 'searchFilter1'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $this->perPage;
            $conditions['where'] = $SaleWhere;
            unset($conditions['returnType']); 
            $data['PaymentInfo'] = $this->user_model->getRowsDataOutstatding($conditions, 'sales'); 
            $data['PaymentPaging'] = $this->ajax_pagination_dashboard->create_links();
            
            return $this->load->view('inventory_sep/search/outstatding-list', $data, false);
        } 
        
        public function AjaxPreviousSaleData()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $SaleWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $SaleWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $this->perPage = 5;
            // Define offset 
            $page = $this->input->post('page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('keywords'); 
            $sortBy = 'DESC'; 
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $SaleWhere;
            $totalRec = $this->user_model->getRowsDataPreviousSale($conditions, 'sales'); 

            // Pagination configuration 
            $config['target']      = '#PreviousSaleList'; 
            $config['base_url']    = base_url('inventory_billing/AjaxPreviousSaleData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $this->perPage; 
            $config['link_func']   = 'searchFilter2'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 
            
            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $this->perPage;
            $conditions['where'] = $SaleWhere;
            unset($conditions['returnType']); 
            $data['PreviousSale'] = $this->user_model->getRowsDataPreviousSale($conditions, 'sales'); 
            $data['SalePaging'] = $this->ajax_pagination_dashboard->create_links();
            
            return $this->load->view('inventory_sep/search/previous-sale-list', $data, false);
        } 
        
        public function AjaxPopullerProductsData()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $InvoiceProductWhere = ['invoice_product.user_id' => $this->session->userdata('user_id'), 'invoice_product.comp_id' => $this->session->userdata('comp_id'), 'invoice_product.status' => 1, 'invoice_product.type' => 'Sale'];
            if ($this->session->userdata('BranchRole')) {
                $InvoiceProductWhere['invoice_product.CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $this->perPage = 5;
            // Define offset 
            $page = $this->input->post('page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('keywords'); 
            $sortBy = 'DESC'; 
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $InvoiceProductWhere;
            $totalRec = $this->user_model->getRowsDataPopullerProducts($conditions, 'invoice_product'); 

            // Pagination configuration 
            $config['target']      = '#PopullerProductsList'; 
            $config['base_url']    = base_url('inventory_billing/AjaxPopullerProductsData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $this->perPage; 
            $config['link_func']   = 'searchFilter3'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 
            
            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $this->perPage;
            $conditions['where'] = $InvoiceProductWhere;
            unset($conditions['returnType']); 
            $data['SallingProducts'] = $this->user_model->getRowsDataPopullerProducts($conditions, 'invoice_product'); 
            $data['ProductPaging'] = $this->ajax_pagination_dashboard->create_links();
            return $this->load->view('inventory_sep/search/populler-products-list', $data, false);
        } 
        
        public function saleAndPurchaseData ()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            if ($this->input->is_ajax_request()) {
                $type = $this->input->post('type');
                $fYear = $this->input->post('year');
                $month = $this->input->post('month');

                if ($type != "" && $fYear != "" && $month != "") {
                    $getFyear = get_title('year', ['id' => $fYear], 'year');
                    $yearStr = explode("-", $getFyear);
                    if ($month >= 4) {
                        $year = $yearStr[0];
                    } else {
                        $year = '20' . $yearStr[1]; 
                    }
                    
                    $firstDayOfMonth = date($year . '-' . $month . '-01');
                    $lastDayOfMonth = date("Y-m-t", strtotime($firstDayOfMonth));
                    
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $fYear, 'invoiceDate >=' => $firstDayOfMonth, 'invoiceDate <=' => $lastDayOfMonth];
                    $whereMonth = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $fYear, 'MONTH(invoiceDate)' => $month];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        $whereMonth['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    
                    if ($type == "All") {
                        $saleData = $this->db->select('COALESCE(SUM(total), 0) AS totalAmount, invoiceDate, type')
                            ->where($where)
                            ->group_by('invoiceDate')
                            ->get_compiled_select('sales');
                        
                        $purchaseData = $this->db->select('COALESCE(SUM(total), 0) AS totalAmount, invoiceDate, type')
                            ->where($where)
                            ->group_by('invoiceDate')
                            ->get_compiled_select('purchases');
                        
                        $invoiceInfo = $this->db->query($saleData . " UNION ALL " . $purchaseData . " ORDER BY invoiceDate ASC")->result();
                        
                        $estimateSaleData = $this->db->select('COALESCE(SUM(total), 0) AS totalAmount, invoiceDate, type')
                            ->where($where)
                            ->group_by('invoiceDate')
                            ->get_compiled_select('estimates');
                        
                        $estimatePurchaseData = $this->db->select('COALESCE(SUM(total), 0) AS totalAmount, invoiceDate, type')
                            ->where($where)
                            ->group_by('invoiceDate')
                            ->get_compiled_select('estimate_purchase');
                        
                        $estimateInvoiceInfo = $this->db->query($estimateSaleData . " UNION ALL " . $estimatePurchaseData . " ORDER BY invoiceDate ASC")->result();
                        
                        $salesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('sales')->row('total');
                        $estimateSalesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('estimates')->row('total');
                        $totalSales = ($salesAmount + $estimateSalesAmount);
                        
                        $purchasesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('purchases')->row('total');
                        $estimatePurchasesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('estimate_purchase')->row('total');
                        $totalPurchases = ($purchasesAmount + $estimatePurchasesAmount);
                        
                        $invoiceInfoWithEstimate = [];
                        
                        foreach ($invoiceInfo as $key => $salePurchaseData) {
                            $invoiceInfoWithEstimate[] = [
                                'totalAmount' => $salePurchaseData->totalAmount,
                                'invoiceDate' => $salePurchaseData->invoiceDate,
                                'type' => $salePurchaseData->type,
                            ];
                        }
                        
                        foreach ($estimateInvoiceInfo as $key => $estimateSaleEstimatePurchaseData) {
                            $type = '';
                            if ($estimateSaleEstimatePurchaseData->type == 'Estimate') {
                                $type = 'Sale';
                            } elseif ($estimateSaleEstimatePurchaseData->type == 'Estimate Purchase') {
                                $type = 'Purchase';
                            }

                            if ($type) {
                                $found = false;
                                foreach ($invoiceInfoWithEstimate as &$existingData) {
                                    // Check both compBranchId and type
                                    if ($existingData['type'] == $type) {
                                        $existingData['totalAmount'] += $estimateSaleEstimatePurchaseData->totalAmount;
                                        $found = true;
                                        break;
                                    }
                                }

                                // If no matching entry is found, add a new entry
                                if (!$found) {
                                    $invoiceInfoWithEstimate[] = [
                                        'totalAmount' => $estimateSaleEstimatePurchaseData->totalAmount,
                                        'invoiceDate' => $estimateSaleEstimatePurchaseData->invoiceDate,
                                        'type' => $type,
                                    ];
                                }
                            }
                        }
                        
                        echo json_encode(['status' => true, 'invoiceData' => $invoiceInfoWithEstimate, 'totalSales' => indianMoneyFormat($totalSales), 'totalPurchases' => indianMoneyFormat($totalPurchases)]);
                    } elseif ($type == "Sale") {
                        $saleDataQuery = $this->db->select('COALESCE(SUM(total), 0) AS totalAmount, invoiceDate, type')
                            ->where($where)
                            ->group_by('invoiceDate')
                            ->order_by('invoiceDate', 'ASC')
                            ->get('sales')
                            ->result();
                        
                        $estimateSaleDataQuery = $this->db->select('COALESCE(SUM(total), 0) AS totalAmount, invoiceDate, type')
                            ->where($where)
                            ->group_by('invoiceDate')
                            ->order_by('invoiceDate', 'ASC')
                            ->get('estimates')
                            ->result();
                        
                        $salesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('sales')->row('total');
                        $estimateSalesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('estimates')->row('total');
                        $totalSales = ($salesAmount + $estimateSalesAmount);
                        
                        $invoiceInfoWithEstimate = [];
                        
                        foreach ($saleDataQuery as $key => $saleData) {
                            $invoiceInfoWithEstimate[] = [
                                'totalAmount' => $saleData->totalAmount,
                                'invoiceDate' => $saleData->invoiceDate,
                                'type' => $saleData->type,
                            ];
                        }

                        foreach ($estimateSaleDataQuery as $key => $estimateSaleData) {
                            $type = '';
                            if ($estimateSaleData->type == 'Estimate') {
                                $type = 'Sale';
                            }

                            if ($type) {
                                $found = false;
                                foreach ($invoiceInfoWithEstimate as &$existingData) {
                                    // Check both compBranchId and type
                                    if ($existingData['type'] == $type) {
                                        $existingData['totalAmount'] += $estimateSaleData->totalAmount;
                                        $found = true;
                                        break;
                                    }
                                }

                                // If no matching entry is found, add a new entry
                                if (!$found) {
                                    $invoiceInfoWithEstimate[] = [
                                        'totalAmount' => $estimateSaleData->totalAmount,
                                        'invoiceDate' => $estimateSaleData->invoiceDate,
                                        'type' => $type,
                                    ];
                                }
                            }
                        }

                        echo json_encode(['status' => true, 'invoiceData' => $invoiceInfoWithEstimate, 'totalPurchases' => 0, 'totalSales' => indianMoneyFormat($totalSales)]);                        
                    } elseif ($type == "Purchase") {
                        $purchaseDataQuery = $this->db->select('COALESCE(SUM(purchases.total), 0) AS totalAmount, invoiceDate, type')->where($where)->group_by('invoiceDate')->order_by('invoiceDate', 'ASC')->get('purchases')->result();
                        
                        $purchasesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('purchases')->row('total');
                        $estimatePurchasesAmount = $this->db->select_sum('total')->where($whereMonth)->group_by('MONTH(invoiceDate)')->get('estimate_purchase')->row('total');
                        $totalPurchases = ($purchasesAmount + $estimatePurchasesAmount);
                        
                        $invoiceInfoWithEstimate = [];

                        foreach ($purchaseDataQuery as $key => $purchaseData) {
                            $invoiceInfoWithEstimate[] = [
                                'totalAmount' => $purchaseData->totalAmount,
                                'invoiceDate' => $purchaseData->invoiceDate,                                
                                'type' => $purchaseData->type,
                            ];
                        }

                        foreach ($estimatePurchaseDataQuery as $key => $estimatePurchaseData) {
                            $type = '';
                            if ($estimatePurchaseData->type == 'Estimate Purchase') {
                                $type = 'Purchase';
                            }

                            if ($type) {
                                $found = false;
                                foreach ($invoiceInfoWithEstimate as &$existingData) {
                                    // Check both compBranchId and type
                                    if ($existingData['type'] == $type) {
                                        $existingData['totalAmount'] += $estimatePurchaseData->totalAmount;
                                        $found = true;
                                        break;
                                    }
                                }

                                // If no matching entry is found, add a new entry
                                if (!$found) {
                                    $invoiceInfoWithEstimate[] = [                                        
                                        'totalAmount' => $estimatePurchaseData->totalAmount,
                                        'invoiceDate' => $estimatePurchaseData->invoiceDate,
                                        'type' => $type,
                                    ];
                                }
                            }
                        }

                        echo json_encode(['status' => true, 'invoiceData' => $invoiceInfoWithEstimate, 'totalPurchases' => indianMoneyFormat($totalPurchases), 'totalSales' => 0]);
                    } else {
                        echo json_encode(['status' => false, 'invoiceData' => '', 'totalSales' => 0, 'totalPurchases' => 0]);
                    }
                }
            }
        }
        
        public function getAccountSummary() 
        {
            if ($_POST) {
                if ($this->input->post('accountdata')) {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompanyBranchId = $this->session->userdata('CompBranchId');
                    
                    $CreditorsWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'account_group' => '5'];
                    $DebtorsWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'account_group' => '5'];
                    $CashWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'paymentmode' => '1'];
                    if ($this->session->userdata('BranchRole')) {
                        $CreditorsWhere['CompanyBranchId'] = $CompanyBranchId;
                        $DebtorsWhere['CompanyBranchId'] = $CompanyBranchId;
                        $CashWhere['CompanyBranchId'] = $CompanyBranchId;
                    }

                    $CreditorsAmount = $this->db->select('SUM(credit) AS Credit')->where($CreditorsWhere)->get('payment')->row();
                    $DebtorsAmount = $this->db->select('SUM(debit) AS Debit')->where($DebtorsWhere)->get('payment')->row();
                    $CashInHand = $this->db->select('(COALESCE(SUM(credit), 0) + COALESCE(SUM(debit), 0)) AS Cash')->where($CashWhere)->get('payment')->row();
                    if ($CreditorsAmount || $DebtorsAmount || $CashInHand) {
                        echo json_encode(['status' => true, 'CreditorsBalance' => $CreditorsAmount, 'DebtorsBalance' => $DebtorsAmount, 'CashBalance' => $CashInHand]);
                    } else {
                        echo json_encode(['status' => false, 'data' => ""]);
                    }
                }
            }
        }
        
        public function ChangeFinancialYear()
        {
            if ($_POST) {
                if ($this->input->post('FinancialYearId')) {
                    $FinancialYearData = $this->db->select('id')->where('id', $this->input->post('FinancialYearId'))->get('year')->row();
                    if ($FinancialYearData) {
                        $this->session->set_userdata('financial_year', $FinancialYearData->id);
                        echo json_encode(['status' => true]);
                    } else {
                        echo json_encode(['status' => false]);
                    }
                }
            }
        }
        
        public function getVendorTotalPurchaseAndSale ()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            if ($this->input->is_ajax_request()) {
                $type = $this->input->post('type');
                $fYear = $this->input->post('year');
                $month = $this->input->post('month');

                if ($type != "" && $fYear != "" && $month != "") {
                    $getFyear = get_title('year', ['id' => $fYear], 'year');
                    $yearStr = explode("-", $getFyear);
                    if ($month >= 4) {
                        $year = $yearStr[0];
                    } else {
                        $year = '20' . $yearStr[1]; 
                    }
                    
                    $firstDayOfMonth = date($year . '-' . $month . '-01');
                    $lastDayOfMonth = date("Y-m-t", strtotime($firstDayOfMonth));
                    
                    $purchasesWhere = ['purchases.comp_id' => $this->session->userdata('comp_id'), 'purchases.status' => 1, 'purchases.financial_year' => $fYear, 'purchases.invoiceDate >=' => $firstDayOfMonth, 'purchases.invoiceDate <=' => $lastDayOfMonth];
                    $estimatePurchasesWhere = ['estimate_purchase.comp_id' => $this->session->userdata('comp_id'), 'estimate_purchase.status' => 1, 'estimate_purchase.financial_year' => $fYear, 'estimate_purchase.invoiceDate >=' => $firstDayOfMonth, 'estimate_purchase.invoiceDate <=' => $lastDayOfMonth];
                    $salesWhere = ['sales.comp_id' => $this->session->userdata('comp_id'), 'sales.status' => 1, 'sales.financial_year' => $fYear, 'sales.invoiceDate >=' => $firstDayOfMonth, 'sales.invoiceDate <=' => $lastDayOfMonth];
                    $estimateSalesWhere = ['estimates.comp_id' => $this->session->userdata('comp_id'), 'estimates.status' => 1, 'estimates.financial_year' => $fYear, 'estimates.invoiceDate >=' => $firstDayOfMonth, 'estimates.invoiceDate <=' => $lastDayOfMonth];
                    $whereMonth = ['comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $fYear, 'MONTH(invoiceDate)' => $month];
                    
                    if ($type == "All") {
                        $purchaseData = $this->db->select('COALESCE(SUM(purchases.total), 0) AS totalAmount, companybranch.BranchName AS branchName, purchases.CompanyBranchId, purchases.type')
                            ->join('companybranch', 'companybranch.Id = purchases.CompanyBranchId', 'LEFT')
                            ->where($purchasesWhere)
                            ->where('purchases.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('purchases.CompanyBranchId')
                            ->get_compiled_select('purchases');

                        $saleData = $this->db->select('COALESCE(SUM(sales.total), 0) AS totalAmount, companybranch.BranchName AS branchName, sales.CompanyBranchId, sales.type')
                            ->join('companybranch', 'companybranch.Id = sales.CompanyBranchId', 'LEFT')
                            ->where($salesWhere)
                            ->where('sales.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('sales.CompanyBranchId')
                            ->get_compiled_select('sales');

                        $invoiceInfo = $this->db->query($purchaseData . " UNION ALL " . $saleData . " ORDER BY CompanyBranchId ASC")->result();
                        
                        $estimatePurchaseData = $this->db->select('COALESCE(SUM(estimate_purchase.total), 0) AS totalAmount, companybranch.BranchName AS branchName, estimate_purchase.CompanyBranchId, estimate_purchase.type')
                            ->join('companybranch', 'companybranch.Id = estimate_purchase.CompanyBranchId', 'LEFT')
                            ->where($estimatePurchasesWhere)
                            ->where('estimate_purchase.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('estimate_purchase.CompanyBranchId')
                            ->get_compiled_select('estimate_purchase');

                        $estimateSaleData = $this->db->select('COALESCE(SUM(estimates.total), 0) AS totalAmount, companybranch.BranchName AS branchName, estimates.CompanyBranchId, estimates.type')
                            ->join('companybranch', 'companybranch.Id = estimates.CompanyBranchId', 'LEFT')
                            ->where($estimateSalesWhere)
                            ->where('estimates.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('estimates.CompanyBranchId')
                            ->get_compiled_select('estimates');

                        $estimateInvoiceInfo = $this->db->query($estimatePurchaseData . " UNION ALL " . $estimateSaleData . " ORDER BY CompanyBranchId ASC")->result();
                        
                        $purchasesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('CompanyBranchId')->get('purchases')->row('total');
                        $estimatePurchasesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('CompanyBranchId')->get('estimate_purchase')->row('total');
                        $totalPurchases = ($purchasesAmount + $estimatePurchasesAmount);
                        
                        $salesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('MONTH(invoiceDate)')->get('sales')->row('total');
                        $estimateSalesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('MONTH(invoiceDate)')->get('estimates')->row('total');
                        $totalSales = ($salesAmount + $estimateSalesAmount);
                        
                        $invoiceInfoWithEstimate = [];

                        foreach ($invoiceInfo as $key => $purchaseSaleData) {
                            $invoiceInfoWithEstimate[] = [
                                'compBranchId' => $purchaseSaleData->CompanyBranchId,
                                'branchName' => $purchaseSaleData->branchName,
                                'totalAmount' => $purchaseSaleData->totalAmount,
                                'type' => $purchaseSaleData->type,
                            ];
                        }

                        foreach ($estimateInvoiceInfo as $key => $estimatePurchaseSaleData) {
                            $type = '';
                            if ($estimatePurchaseSaleData->type == 'Estimate Purchase') {
                                $type = 'Purchase';
                            } elseif ($estimatePurchaseSaleData->type == 'Estimate') {
                                $type = 'Sale';
                            }

                            if ($type) {
                                $found = false;
                                foreach ($invoiceInfoWithEstimate as &$existingData) {
                                    // Check both compBranchId and type
                                    if ($existingData['compBranchId'] == $estimatePurchaseSaleData->CompanyBranchId && $existingData['type'] == $type) {
                                        $existingData['totalAmount'] += $estimatePurchaseSaleData->totalAmount;
                                        $found = true;
                                        break;
                                    }
                                }

                                // If no matching entry is found, add a new entry
                                if (!$found) {
                                    $invoiceInfoWithEstimate[] = [
                                        'compBranchId' => $estimatePurchaseSaleData->CompanyBranchId,
                                        'branchName' => $estimatePurchaseSaleData->branchName,
                                        'totalAmount' => $estimatePurchaseSaleData->totalAmount,
                                        'type' => $type,
                                    ];
                                }
                            }
                        }
                        
                        echo json_encode(['status' => true, 'invoiceData' => $invoiceInfoWithEstimate, 'totalPurchases' => indianMoneyFormat($totalPurchases), 'totalSales' => indianMoneyFormat($totalSales)]);
                    } elseif ($type == "Purchase") {
                        $purchaseDataQuery = $this->db->select('COALESCE(SUM(purchases.total), 0) AS totalAmount, companybranch.BranchName AS branchName, purchases.CompanyBranchId, purchases.type')
                            ->join('companybranch', 'companybranch.Id = purchases.CompanyBranchId', 'LEFT')
                            ->where($purchasesWhere)
                            ->where('purchases.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('purchases.CompanyBranchId')
                            ->get('purchases')->result();
                        
                        $estimatePurchaseDataQuery = $this->db->select('COALESCE(SUM(estimate_purchase.total), 0) AS totalAmount, companybranch.BranchName AS branchName, estimate_purchase.CompanyBranchId, estimate_purchase.type')
                            ->join('companybranch', 'companybranch.Id = estimate_purchase.CompanyBranchId', 'LEFT')
                            ->where($estimatePurchasesWhere)
                            ->where('estimate_purchase.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('estimate_purchase.CompanyBranchId')
                            ->get('estimate_purchase')->result();
                        
                        $purchasesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('MONTH(invoiceDate)')->get('purchases')->row('total');
                        $estimatePurchasesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('MONTH(invoiceDate)')->get('estimate_purchase')->row('total');
                        $totalPurchases = ($purchasesAmount + $estimatePurchasesAmount);

                        $invoiceInfoWithEstimate = [];

                        foreach ($purchaseDataQuery as $key => $purchaseData) {
                            $invoiceInfoWithEstimate[] = [
                                'compBranchId' => $purchaseData->CompanyBranchId,
                                'branchName' => $purchaseData->branchName,
                                'totalAmount' => $purchaseData->totalAmount,
                                'type' => $purchaseData->type,
                            ];
                        }

                        foreach ($estimatePurchaseDataQuery as $key => $estimatePurchaseData) {
                            $type = '';
                            if ($estimatePurchaseData->type == 'Estimate Purchase') {
                                $type = 'Purchase';
                            }

                            if ($type) {
                                $found = false;
                                foreach ($invoiceInfoWithEstimate as &$existingData) {
                                    // Check both compBranchId and type
                                    if ($existingData['compBranchId'] == $estimatePurchaseData->CompanyBranchId && $existingData['type'] == $type) {
                                        $existingData['totalAmount'] += $estimatePurchaseData->totalAmount;
                                        $found = true;
                                        break;
                                    }
                                }

                                // If no matching entry is found, add a new entry
                                if (!$found) {
                                    $invoiceInfoWithEstimate[] = [
                                        'compBranchId' => $estimatePurchaseData->CompanyBranchId,
                                        'branchName' => $estimatePurchaseData->branchName,
                                        'totalAmount' => $estimatePurchaseData->totalAmount,
                                        'type' => $type,
                                    ];
                                }
                            }
                        }
                        
                        echo json_encode(['status' => true, 'invoiceData' => $invoiceInfoWithEstimate, 'totalPurchases' => indianMoneyFormat($totalPurchases), 'totalSales' => 0]);                        
                    } elseif ($type == "Sale") {
                        $saleDataQuery = $this->db->select('COALESCE(SUM(sales.total), 0) AS totalAmount, companybranch.BranchName AS branchName, sales.CompanyBranchId, sales.type')
                            ->join('companybranch', 'companybranch.Id = sales.CompanyBranchId', 'LEFT')
                            ->where($salesWhere)
                            ->where('sales.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('sales.CompanyBranchId')
                            ->get('sales')->result();
                        
                        $estimateSaleDataQuery = $this->db->select('COALESCE(SUM(estimates.total), 0) AS totalAmount, companybranch.BranchName AS branchName, estimates.CompanyBranchId, estimates.type')
                            ->join('companybranch', 'companybranch.Id = estimates.CompanyBranchId', 'LEFT')
                            ->where($estimateSalesWhere)
                            ->where('estimates.CompanyBranchId IS NOT NULL', null, false)
                            ->group_by('estimates.CompanyBranchId')
                            ->get('estimates')->result();
                        
                        $salesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('MONTH(invoiceDate)')->get('sales')->row('total');                        
                        $estimateSalesAmount = $this->db->select_sum('total')->where($whereMonth)->where('CompanyBranchId IS NOT NULL', null, false)->group_by('MONTH(invoiceDate)')->get('estimates')->row('total');                        
                        $totalSales = ($salesAmount + $estimateSalesAmount);

                        $invoiceInfoWithEstimate = [];

                        foreach ($saleDataQuery as $key => $saleData) {
                            $invoiceInfoWithEstimate[] = [
                                'compBranchId' => $saleData->CompanyBranchId,
                                'branchName' => $saleData->branchName,
                                'totalAmount' => $saleData->totalAmount,
                                'type' => $saleData->type,
                            ];
                        }

                        foreach ($estimateSaleDataQuery as $key => $estimateSaleData) {
                            $type = '';
                            if ($estimateSaleData->type == 'Estimate') {
                                $type = 'Sale';
                            }

                            if ($type) {
                                $found = false;
                                foreach ($invoiceInfoWithEstimate as &$existingData) {
                                    // Check both compBranchId and type
                                    if ($existingData['compBranchId'] == $estimateSaleData->CompanyBranchId && $existingData['type'] == $type) {
                                        $existingData['totalAmount'] += $estimateSaleData->totalAmount;
                                        $found = true;
                                        break;
                                    }
                                }

                                // If no matching entry is found, add a new entry
                                if (!$found) {
                                    $invoiceInfoWithEstimate[] = [
                                        'compBranchId' => $estimateSaleData->CompanyBranchId,
                                        'branchName' => $estimateSaleData->branchName,
                                        'totalAmount' => $estimateSaleData->totalAmount,
                                        'type' => $type,
                                    ];
                                }
                            }
                        }
                        
                        echo json_encode(['status' => true, 'invoiceData' => $invoiceInfoWithEstimate, 'totalPurchases' => 0, 'totalSales' => indianMoneyFormat($totalSales)]);
                    } else {
                        echo json_encode(['status' => false, 'invoiceData' => '', 'totalPurchases' => 0, 'totalSales' => 0]);
                    }
                }
            }
        }
    }