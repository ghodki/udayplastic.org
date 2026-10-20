<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class CompanyBranch extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function Index() 
        {
            if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                $Where = ['IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')];
            } elseif (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "user") {
                $Where = ['IsDeleted' => 1, 'BranchUserId' => $this->session->userdata('user_id')];
            }
            $Data['CompanyBranch'] = $this->db->select('Id, Date, BranchCode, BranchName, Mobile, Email, GST, State, Status, BranchUserId, CompId')->where($Where)->order_by('Id', 'DESC')->get('companybranch')->result();
            $Data['page'] = 'admin_details/CompanyBranch/Index';
            return $this->load->view('admin', $Data);
        }
        
        public function Manage() 
        {
            if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                $Where = ['IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')];
            } elseif (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "user") {
                $Where = ['IsDeleted' => 1, 'BranchUserId' => $this->session->userdata('user_id')];
            }
            $Data['CompanyBranch'] = $this->db->select('Id, Date, BranchName, Mobile, Email, GST, State, Status, BranchUserId, CompId')->where($Where)->order_by('Id', 'DESC')->get('companybranch')->result();
            $Data['page'] = 'admin_details/CompanyBranch/Manage';
            return $this->load->view('admin', $Data);
        }
        
        public function Create()
        {
            if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                $data['Companies'] = $this->db->where('user_id', $this->session->userdata('user_id'))->get('inventory_billing')->result();
                $data['Users'] = $this->db->where(['userType' => 3, 'status' => 1])->order_by('id', 'DESC')->get('users')->result();
                $data['States'] = $this->db->get('state_list')->result();
                $data['FinancialYears'] = $this->db->order_by('year', 'DESC')->get('year')->result();
                $data['BillingFormat'] = $this->user_model->getResult('billing_format');
                $data['page'] = "admin_details/CompanyBranch/Create";
                return $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }
        }
        
        public function Save()
        {
            if ($_POST) {
                if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                    $this->form_validation->set_rules('CompId', 'Company Name', 'trim|required');
                    $this->form_validation->set_rules('BranchUserId', 'Branch User', 'trim|required');
                    $this->form_validation->set_rules('BranchName', 'Branch Name', 'trim|required');
                    $this->form_validation->set_rules('Mobile', 'Mobile', 'trim|required|numeric|max_length[10]|min_length[10]|is_unique[companybranch.Mobile]');
                    $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email|is_unique[companybranch.Email]');
                    $this->form_validation->set_rules('GST', 'GST', 'trim|max_length[15]|min_length[15]|is_unique[companybranch.GST]');
                    $this->form_validation->set_rules('ContactPerson', 'Contact Person', 'trim');
                    $this->form_validation->set_rules('State', 'State', 'trim|required');
                    $this->form_validation->set_rules('Address', 'Address', 'trim');
                    $this->form_validation->set_rules('FinancialYear', 'Financial Year', 'trim|required');
                    $this->form_validation->set_rules('BillingFormat', 'Billing Format', 'trim|required');
                    $this->form_validation->set_rules('AccountingType', 'Accounting Type', 'trim|required');
                    $this->form_validation->set_rules('branchDivision', 'Branch Division', 'required');
                    if ($this->form_validation->run() == false) {
                        $data['Companies'] = $this->db->where('user_id', $this->session->userdata('user_id'))->get('inventory_billing')->result();
                        $data['Users'] = $this->db->where(['userType' => 3, 'status' => 1])->order_by('id', 'DESC')->get('users')->result();
                        $data['States'] = $this->db->get('state_list')->result();
                        $data['FinancialYears'] = $this->db->order_by('year', 'DESC')->get('year')->result();
                        $data['BillingFormat'] = $this->user_model->getResult('billing_format');
                        $data['page'] = "admin_details/CompanyBranch/Create";
                        $data['error'] = validation_errors();
                        return $this->load->view('admin', $data);
                    } else {
                        $BranchCode = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
                        $BranchCodeWhere = ['BranchCode' => $BranchCode, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                        if ($this->db->select('BranchCode')->where($BranchCodeWhere)->get('companybranch')->num_rows()) {
                            $BranchCode = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
                        }
                        
                        // 1 Create Branch
                        $CompanyBranchData = [
                            'Date' => date('Y-m-d'),
                            'BranchCode' => $BranchCode,
                            'BranchName' => $this->input->post('BranchName'),
                            'Mobile' => $this->input->post('Mobile'),
                            'Email' => $this->input->post('Email'),
                            'GST' => $this->input->post('GST'),
                            'ContactPerson' => $this->input->post('ContactPerson'),
                            'State' => $this->input->post('State'),
                            'Address' => $this->input->post('Address'),
                            'FinancialYear' => $this->input->post('FinancialYear'),
                            'BillingFormat' => $this->input->post('BillingFormat'),
                            'AccountingType' => $this->input->post('AccountingType'),
                            'branchDivision' => $this->input->post('branchDivision'),
                            'CompId' => $this->input->post('CompId'),
                            'BranchUserId' => $this->input->post('BranchUserId'),
                            'UserId' => $this->session->userdata('user_id'),
                            'mainBranchId' => $this->input->post('mainBranchId'),
                            'Created' => date('d-m-Y h:i:s A'),
                            'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];
                        $Result = $this->user_model->saveAry('companybranch', $CompanyBranchData);                        
                        
                        // 2 Create Cash Party (Default party debitors - for sale entries).
                        $CashParty = [
                            "group_head" => 5,
                            "name" => 'Cash',
                            "city" => 243,
                            "gstType" => 'N/A',
                            "state" => $this->input->post('State'),
                            "state_code" => get_title('state_list', ['id' => $this->input->post('State')], 'code'),
                            "opBalnceType" => "DR",
                            "comp_id" => $this->input->post('CompId'),
                            "user_id" => $this->input->post('BranchUserId'),
                            "CompanyBranchId" => $Result
                        ];
                        $this->user_model->saveAry('parties', $CashParty);
                        
                        // 3 Create Party as Branch for Company (Debitors - for sale entries).
                        if ($this->input->post('branchDivision') == "Y" && !empty($this->input->post('mainBranchId'))) {
                                $mainBranchData = $this->db->select('Id, BranchUserId')->where(['Id' => $this->input->post('mainBranchId'), 'CompId' => $this->input->post('CompId'), 'IsDeleted' => 1])->get('companybranch')->row();
                                $debitorsPartyUserId = $mainBranchData->BranchUserId;
                                $debitorsPartyBranchId = $mainBranchData->Id;
                                $subBranchId = $Result;
                        } elseif ($this->input->post('branchDivision') == "N" && empty($this->input->post('mainBranchId'))) {        
                                $debitorsPartyUserId = $this->session->userdata('user_id');
                                $debitorsPartyBranchId = $Result;
                                $subBranchId = NULL;
                        }
                        $PartyBranchCompany = [
                            "group_head" => 5,
                            "name" => $this->input->post('BranchName'),
                            "ContactPerson" => $this->input->post('ContactPerson'),
                            "contact" => $this->input->post('Mobile'),
                            "email" => $this->input->post('Email'),
                            "gst" => $this->input->post('GST'),
                            "gstType" => 'Regular',
                            "address_1" => $this->input->post('Address'),
                            "state" => $this->input->post('State'),
                            "state_code" => get_title('state_list', ['id' => $this->input->post('State')], 'code'),
                            "opBalnceType" => "DR",
                            "comp_id" => $this->input->post('CompId'),
                            "user_id" => $debitorsPartyUserId,
                            "CompanyBranchId" => $debitorsPartyBranchId,
                            "subBranchId" => $subBranchId
                        ];
                        $this->user_model->saveAry('parties', $PartyBranchCompany);
                        
                        // 4 Create Party as Company for Branch (Creditors - for purchase entries).
                        if ($this->input->post('branchDivision') == "Y" && !empty($this->input->post('mainBranchId'))) {
                                $mainBranchInfo = $this->db->select('Id, BranchName, ContactPerson, Mobile, Email, GST, Address, State, CompId')->where(['Id' => $this->input->post('mainBranchId'), 'CompId' => $this->input->post('CompId'), 'IsDeleted' => 1])->get('companybranch')->row();
                                $PartyCompanyBranch = [
                                    "group_head" => 6,
                                    "name" => $mainBranchInfo->BranchName,
                                    "ContactPerson" => $mainBranchInfo->ContactPerson,
                                    "contact" => $mainBranchInfo->Mobile,
                                    "email" => $mainBranchInfo->Email,
                                    "gstType" => 'Regular',
                                    "gst" => $mainBranchInfo->GST,
                                    "address_1" => $mainBranchInfo->Address,
                                    "state" => $mainBranchInfo->State,
                                    "state_code" => get_title('state_list', ['id' => $mainBranchInfo->State], 'code'),
                                    "opBalnceType" => "CR",
                                    "comp_id" => $mainBranchInfo->CompId,
                                    "user_id" => $this->input->post('BranchUserId'),
                                    "CompanyBranchId" => $Result
                                ];
                                $this->user_model->saveAry('parties', $PartyCompanyBranch);
                        } elseif ($this->input->post('branchDivision') == "N") {
                                $CompanyInfo = $this->db->select('id, company_name, contact_person, number, email, gst_no, address, state')->where(['id' => $this->input->post('CompId'), 'user_id' => $this->session->userdata('user_id')])->get('inventory_billing')->row();
                                $PartyCompanyBranch = [
                                    "group_head" => 6,
                                    "name" => $CompanyInfo->company_name,
                                    "ContactPerson" => $CompanyInfo->contact_person,
                                    "contact" => $CompanyInfo->number,
                                    "email" => $CompanyInfo->email,
                                    "gstType" => 'Regular',
                                    "gst" => $CompanyInfo->gst_no,
                                    "address_1" => $CompanyInfo->address,
                                    "state" => $CompanyInfo->state,
                                    "state_code" => get_title('state_list', ['id' => $CompanyInfo->state], 'code'),
                                    "opBalnceType" => "CR",
                                    "comp_id" => $CompanyInfo->id,
                                    "user_id" => $this->input->post('BranchUserId'),
                                    "CompanyBranchId" => $Result
                                ];
                                $this->user_model->saveAry('parties', $PartyCompanyBranch);
                        }

                        // 5 Create Warehouse
                        $Warehouse = [
                            "name" => "Main",
                            "user_id" => $this->input->post('BranchUserId'),
                            "comp_id" => $this->input->post('CompId'),
                            "CompanyBranchId" => $Result,
                            "created" => date('d-m-Y h:i:s A')
                        ];
                        $WarehouseId = $this->user_model->saveAry('warehouse', $Warehouse);
                        
                        // 6 Copy Parent company Products
                        $Products = $this->db->get_where('product', array('user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->input->post('CompId')))->result();
                        if (!empty($Products)) {
                            foreach ($Products as $product) {
                                $ProductArray = (array) $product;
                                $ProductArray['qty'] = 0;
                                $ProductArray['created_at'] = date('Y-m-d H:i:s');
                                $ProductArray['user_id'] = $this->input->post('BranchUserId');
                                $ProductArray['CompanyBranchId'] = $Result;
                                $ProductArray['warehouse'] = $WarehouseId;
                                
                                $ProductsData = array();
                                foreach ($ProductArray as $key => $Value) {
                                    if ($key !== 'id') {
                                        $ProductsData[$key] = $Value;
                                    }
                                }                                
                                $this->db->insert('product', $ProductsData);
                            }
                        }
                        
                        // 7 Create Company Branch Profile
                        $CompanyBranchProfile = [
                            "user_id" => $this->input->post('BranchUserId'),
                            "comp_id" => $this->input->post('CompId'),
                            "CompanyBranchId" => $Result,
                            "name" => $this->input->post('BranchName'),
                            "financial_year" => $this->input->post('FinancialYear'),
                            "default_warehouse" => $WarehouseId,
                            "warehouse" => $WarehouseId,
                            "warehouse_estimate" => $WarehouseId,
                            "warehouse_purchase" => $WarehouseId,
                            "warehouse_estimate_purchase" => $WarehouseId
                        ];
                        $this->user_model->saveAry('comp_profile', $CompanyBranchProfile);

                        // 8 Create Company Branch Config
                        $CompanyBranch = [
                            "user_id" => $this->input->post('BranchUserId'),
                            "comp_id" => $this->input->post('CompId'),
                            "CompanyBranchId" => $Result
                        ];
                        $this->user_model->saveAry('a5config', $CompanyBranch);
                        $this->user_model->saveAry('purchase_config', $CompanyBranch);
                        $this->user_model->saveAry('estimateconfig', $CompanyBranch);
                        $this->user_model->saveAry('estimate_purchase_config', $CompanyBranch);
                        $this->user_model->saveAry('credit_note_config', $CompanyBranch);
                        $this->user_model->saveAry('debit_note_config', $CompanyBranch);
                        $this->user_model->saveAry('proforma_config', $CompanyBranch);
                        
                        // 9 Create Company Branch Employee Config
                        $EmployeeCompanyBranch = [
                            "UserId" => $this->input->post('BranchUserId'),
                            "CompId" => $this->input->post('CompId'),
                            "CompanyBranchId" => $Result,
                            "Created" => date('d-m-Y h:i:s A'),
                            "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                        ];
                        $this->user_model->saveAry('employee_config', $EmployeeCompanyBranch);

                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'CompanyBranch');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                            return redirect(base_url() . 'CompanyBranch');
                        }
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                    return redirect(base_url() . 'CompanyBranch');
                }
            }
        }
                
        public function Info($Id) 
        {
            if (!empty($Id)) {
                if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                    $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')];
                } elseif (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "user") {
                    $Where = ['Id' => $Id, 'IsDeleted' => 1, 'BranchUserId' => $this->session->userdata('user_id')];
                }
                $Data['CompanyBranch'] = $this->user_model->getRow('companybranch', $Where);
                $Data['page'] = 'admin_details/CompanyBranch/Info';
                return $this->load->view('admin', $Data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }
        }
        
        public function Edit($Id)
        {
            if ($Id) {
                if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                    $data['BranchId'] = $Id;
                    $data['CompanyBranch'] = $this->user_model->getRow('companybranch', ['Id' => $Id, 'UserId' => $this->session->userdata('user_id')]);
                    $data['Companies'] = $this->db->where('user_id', $this->session->userdata('user_id'))->get('inventory_billing')->result();
                    $data['Users'] = $this->db->where(['userType' => 3, 'status' => 1])->order_by('id', 'DESC')->get('users')->result();
                    $data['States'] = $this->db->get('state_list')->result();
                    $data['FinancialYears'] = $this->db->order_by('year', 'DESC')->get('year')->result();
                    $data['BillingFormat'] = $this->user_model->getResult('billing_format');
                    $data['page'] = "admin_details/CompanyBranch/Edit";
                    return $this->load->view('admin', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                    return redirect(base_url() . 'CompanyBranch');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }   
        }
        
        public function IsUniqueMobile($Mobile, $Id) 
        {
            $QueryResult = $this->db->select('Id, Mobile')->where('IsDeleted', 1)->where('Mobile', $Mobile)->where('Id != ', $Id)->where('UserId', $this->session->userdata('user_id'))->get('companybranch')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueMobile', 'The mobile must be unique.');
                return false;
            }
        }
        
        public function IsUniqueEmail($Email, $Id) 
        {
            $QueryResult = $this->db->select('Id, Email')->where('IsDeleted', 1)->where('Email', $Email)->where('Id != ', $Id)->where('UserId', $this->session->userdata('user_id'))->get('companybranch')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueEmail', 'The Email must be unique.');
                return false;
            }
        }
        
        public function IsUniqueGST($GST, $Id) 
        {
            $QueryResult = $this->db->select('Id, GST')->where('IsDeleted', 1)->where('GST', $GST)->where('Id != ', $Id)->where('UserId', $this->session->userdata('user_id'))->get('companybranch')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueGST', 'The GST must be unique.');
                return false;
            }
        }
        
        public function Update($Id)
        {
            if ($Id) {
                if ($_POST) {
                    if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                        $this->form_validation->set_rules('CompId', 'Company Name', 'trim|required');
                        $this->form_validation->set_rules('BranchUserId', 'Branch User', 'trim|required');
                        $this->form_validation->set_rules('BranchName', 'Branch Name', 'trim|required');
                        $this->form_validation->set_rules('Mobile', 'Mobile', 'trim|required|numeric|max_length[10]|min_length[10]|callback_IsUniqueMobile[' . $Id . ']');
                        $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email|callback_IsUniqueEmail[' . $Id . ']');
                        $this->form_validation->set_rules('GST', 'GST', 'trim|max_length[15]|min_length[15]|callback_IsUniqueGST[' . $Id . ']');
                        $this->form_validation->set_rules('ContactPerson', 'Contact Person', 'trim');
                        $this->form_validation->set_rules('State', 'State', 'trim|required');
                        $this->form_validation->set_rules('Address', 'Address', 'trim');
                        $this->form_validation->set_rules('FinancialYear', 'Financial Year', 'trim|required');
                        $this->form_validation->set_rules('BillingFormat', 'Billing Format', 'trim|required');
                        $this->form_validation->set_rules('AccountingType', 'Accounting Type', 'trim|required');
                        $this->form_validation->set_rules('branchDivision', 'Branch Division', 'required');
                        if ($this->form_validation->run() == false) {
                            $data['BranchId'] = $Id;
                            $data['CompanyBranch'] = $this->user_model->getRow('companybranch', ['Id' => $Id, 'UserId' => $this->session->userdata('user_id')]);
                            $data['Companies'] = $this->db->where('user_id', $this->session->userdata('user_id'))->get('inventory_billing')->result();
                            $data['Users'] = $this->db->where(['userType' => 3, 'status' => 1])->order_by('id', 'DESC')->get('users')->result();
                            $data['States'] = $this->db->get('state_list')->result();
                            $data['FinancialYears'] = $this->db->order_by('year', 'DESC')->get('year')->result();
                            $data['BillingFormat'] = $this->user_model->getResult('billing_format');
                            $data['page'] = "admin_details/CompanyBranch/Edit";
                            $data['error'] = validation_errors();
                            return $this->load->view('admin', $data);
                        } else {                        
                            $BranchId = $this->input->post('BranchId');
                            $CompanyBranchData = [
                                'BranchName' => $this->input->post('BranchName'),
                                'Mobile' => $this->input->post('Mobile'),
                                'Email' => $this->input->post('Email'),
                                'GST' => $this->input->post('GST'),
                                'ContactPerson' => $this->input->post('ContactPerson'),
                                'State' => $this->input->post('State'),
                                'Address' => $this->input->post('Address'),
                                'FinancialYear' => $this->input->post('FinancialYear'),
                                'BillingFormat' => $this->input->post('BillingFormat'),
                                'AccountingType' => $this->input->post('AccountingType'),
                                'branchDivision' => $this->input->post('branchDivision'),
                                'CompId' => $this->input->post('CompId'),
                                'BranchUserId' => $this->input->post('BranchUserId'),
                                'mainBranchId' => $this->input->post('mainBranchId'),                                
                                'Updated' => date('d-m-Y h:i:s A'),
                                'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                            ];

                            $Result = $this->user_model->updateAry('companybranch', $CompanyBranchData, ['Id' => $BranchId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')]);
                            if (!empty($Result)) {
                                $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                                return redirect(base_url() . 'CompanyBranch');
                            } else {
                                $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                                return redirect(base_url() . 'CompanyBranch/Edit/' . $Id);
                            }
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                        return redirect(base_url() . 'CompanyBranch');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }   
        }
        
        public function Status($Id)
        {
            if ($Id) {
                if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                    $CompanyBranch = $this->user_model->getRow('companybranch', ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')]);
                    if ($CompanyBranch) {
                        if ($CompanyBranch->Status == 1) {
                            $Status = 0;
                        } else {
                            $Status = 1;
                        }

                        $Result = $this->user_model->updateAry('companybranch', ['Status' => $Status], ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')]);
                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'CompanyBranch');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'CompanyBranch');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                        return redirect(base_url() . 'CompanyBranch');                    
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                    return redirect(base_url() . 'CompanyBranch');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }
        }
        
        public function Delete($Id)
        {
            if (!empty($Id)) {
                if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                    $CompanyBranch = $this->user_model->getRow('companybranch', ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')]);
                    if ($CompanyBranch) {
                        if ($this->user_model->updateAry('companybranch', ['IsDeleted' => 0], ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id')])) {
                            $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'CompanyBranch');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                            return redirect(base_url() . 'CompanyBranch');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                        return redirect(base_url() . 'CompanyBranch');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                    return redirect(base_url() . 'CompanyBranch');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Company branch id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }
        }
        
        public function Redirect($Id) 
        {
            if ($Id && $Id != null && $Id != "" && $this->session->userdata('user_id') && $this->session->userdata('user_id') != null && $this->session->userdata('user_id') != "") {
                $CompanyBranch = $this->db->select('Id, Mobile, GST, State, BillingFormat, Address, UserId, CompId')->where('IsDeleted', 1)->where('Id', $Id)->get('companybranch')->row();
                if ($CompanyBranch) {                    
                    $this->session->set_userdata('comp_id', $CompanyBranch->CompId);
                    $this->session->set_userdata('CompBranchId', $CompanyBranch->Id);
                    $this->session->set_userdata('BranchRole', "CompanyBranch");
                    $this->session->set_userdata('numbers', $CompanyBranch->Mobile);
                    $this->session->set_userdata('gst_nos', $CompanyBranch->GST);
                    $this->session->set_userdata('state', $CompanyBranch->State);
                    $this->session->set_userdata('address', $CompanyBranch->Address);
                    $this->session->set_userdata('format_of_bill', $CompanyBranch->BillingFormat);
                    
                    $CompanyBranchWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $CompanyBranch->CompId, 'CompanyBranchId' => $CompanyBranch->Id];
                    $CompanyBranchProfile = $this->user_model->getRow('comp_profile', $CompanyBranchWhere);
                    if ($CompanyBranchProfile) {
                        $this->session->set_userdata('financial_year', $CompanyBranchProfile->financial_year);
                        $this->session->set_userdata('barcode', $CompanyBranchProfile->barcode_con);
                        $this->session->set_userdata('lrdate', $CompanyBranchProfile->lrdate);
                        $this->session->set_userdata('eway', $CompanyBranchProfile->eway);
                        $this->session->set_userdata('lrnumber', $CompanyBranchProfile->lrnumber);
                        $this->session->set_userdata('duedate', $CompanyBranchProfile->duedate);
                        $this->session->set_userdata('ptd', $CompanyBranchProfile->ptd);
                        $this->session->set_userdata('dpb', $CompanyBranchProfile->dpb);
                        $this->session->set_userdata('dcdate', $CompanyBranchProfile->dcdate);
                        $this->session->set_userdata('dcno', $CompanyBranchProfile->dcno);
                        $this->session->set_userdata('discount', $CompanyBranchProfile->discount);
                        $this->session->set_userdata('estimate_format', $CompanyBranchProfile->estimate_format);
                        $this->session->set_userdata('estimate_stock', $CompanyBranchProfile->estimate_stock);
                        $this->session->set_userdata('salesman_sale', $CompanyBranchProfile->salesman_sale);
                        $this->session->set_userdata('salesman_estimate', $CompanyBranchProfile->salesman_estimate);
                    }
                    return redirect(base_url() . 'inventory/dashboard');
                } else {
                    $this->session->set_flashdata(['msg' => 'Did not find record, try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'CompanyBranch');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Somthing is missing, try again.', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }
        }
        
        public function getCompanyBranches()
        {
                if ($this->input->is_ajax_request()) {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $compId = $this->input->post('compId');
                                $userId = $this->session->userdata('user_id');
                                $where = ['CompId' => $compId, 'UserId' => $userId, 'IsDeleted' => 1];
                                
                                $companyBranchesQuery = $this->db->select('Id, BranchName, ContactPerson')->where($where)->order_by('Id', 'DESC')->get('companybranch');
                                if ($companyBranchesQuery->num_rows()) {
                                        $branchHtml = "<option value='' selected='' disabled=''>Select Branch</option>";
                                        foreach ($companyBranchesQuery->result() as $key => $branches) {
                                                $branchesInfo = $branches->BranchName . " (" . $branches->ContactPerson . ")";
                                                $branchHtml .= "<option value='" . $branches->Id . "'>" . $branchesInfo . "</option>";
                                        }
                                        echo $branchHtml;
                                }
                        }
                }
        }
    }