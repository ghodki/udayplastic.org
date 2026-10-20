<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    date_default_timezone_set('Asia/Kolkata');
    
    class Ladger extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url']);
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
            $ladger_where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $ladger_where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['Ledgers'] = $this->user_model->getAry('ladger', '', '', '', $ladger_where);
            $data['page'] = 'ladger/index';
            $this->load->view('inventory_sep/inventory', $data);
        }

        public function IsUniqueLedger($LedgerName) 
        {
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'name' => $LedgerName];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $QueryResult = $this->db->select('name')->where($Where)->get('ladger')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueLedger', 'The ledger name must be unique.');
                return false;
            }
        }
        
        public function addledger()
        {
            if ($_POST) {
                $LedgerName = $this->input->post('name');
                $this->form_validation->set_rules('accounting_group', 'Accounting Group', 'trim|xss_clean|required');
                $this->form_validation->set_rules('name', 'Ledger Name', 'trim|xss_clean|required|callback_IsUniqueLedger[' . $LedgerName . ']');
                $this->form_validation->set_rules('ContactPerson', 'Contact Person', 'trim|xss_clean|required');            
                $this->form_validation->set_rules('Mobile', 'Mobile', 'trim|xss_clean|required|numeric');            
                $this->form_validation->set_rules('type', 'Ledger Type', 'trim|xss_clean|required');            
                $this->form_validation->set_rules('opening_balance', 'Opening Balance', 'trim|xss_clean|numeric');

                if ($this->form_validation->run() == FALSE) {
                    $data['page'] = 'ladger/index';
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                }
                
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                
                $insData = [
                    'date' => date('Y-m-d'),
                    'accounting_group' => $this->input->post('accounting_group'),
                    'name' => ucfirst($this->input->post('name')),
                    'ContactPerson' => $this->input->post('ContactPerson'),
                    'Mobile' => $this->input->post('Mobile'),
                    'Email' => $this->input->post('Email'),
                    'Address' => $this->input->post('Address'),
                    'Area' => $this->input->post('Area'),
                    'type' => $this->input->post('type'),
                    'opening_balance' => $this->input->post('opening_balance'),
                    'comp_id' => $CompId,
                    'user_id' => $UserId,
                    'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                    'EmployeeId' => $this->session->userdata('EmployeeId'),
                    'created' => date('d-m-Y h:i:s A')
                ];

                $result = $this->user_model->saveAry('ladger', $insData);
                if (isset($result) && !empty($result)) {
                    $this->session->set_flashdata(['msg' => 'Ledger added successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }

        public function edit($id = null)
        {
            $data['page'] = 'ladger/edit';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('ladger', $where);
            if (isset($result) && !empty($result)) {
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function Info($Id) 
        {
            if ($Id) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $Where = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                if ($this->db->where($Where)->get('ladger')->num_rows()) {
                    $Data['LedgerData'] = $this->db->where($Where)->get('ladger')->row();
                    return $this->load->view('inventory_sep/ladger/Info', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No Record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }

        public function IsUniqueLedgerEdit($LedgerName, $Id) 
        {
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'name' => $LedgerName, 'id !=' => $Id];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $QueryResult = $this->db->select('name')->where($Where)->get('ladger')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueLedgerEdit', 'The ledger name must be unique.');
                return false;
            }
        }
        
        public function updateledger()
        {
            if ($_POST) {
                $id = $this->input->post('id');
                if (!$id) {
                    $this->session->set_flashdata(['msg' => 'Data not found', 'class' => 'danger']);
                    return redirect(base_url() . 'ladger');
                }
                $this->form_validation->set_rules('accounting_group', 'Accounting Group', 'trim|xss_clean|required');
                $this->form_validation->set_rules('name', 'ladger Name', 'trim|xss_clean|required|callback_IsUniqueLedgerEdit[' . $id . ']');
                $this->form_validation->set_rules('ContactPerson', 'Contact Person', 'trim|xss_clean|required');            
                $this->form_validation->set_rules('Mobile', 'Mobile', 'trim|xss_clean|required|numeric');            
                $this->form_validation->set_rules('type', 'ladger Type', 'trim|xss_clean|required');
                $this->form_validation->set_rules('opening_balance', 'Opening Balance', 'trim|xss_clean|numeric');

                if ($this->form_validation->run() == false) {
                    $data['page'] = 'ladger/edit';
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                }
                
                $insData = [
                    'accounting_group' => $this->input->post('accounting_group'),
                    'name' => ucfirst($this->input->post('name')),
                    'ContactPerson' => $this->input->post('ContactPerson'),
                    'Mobile' => $this->input->post('Mobile'),
                    'Email' => $this->input->post('Email'),
                    'Address' => $this->input->post('Address'),
                    'Area' => $this->input->post('Area'),
                    'type' => $this->input->post('type'),
                    'opening_balance' => $this->input->post('opening_balance'),
                    'updated' => date('d-m-Y h:i:s A')
                ];
                
                $result = $this->user_model->updateAry('ladger', $insData, ['id' => $id]);
                if (isset($result) && !empty($result)) {
                    $this->session->set_flashdata(['msg' => 'Ledger updated successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Data not found', 'class' => 'danger']);
                    //return redirect('ladger/edit/' . $id);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }

        public function delete($id = null)
        {
            $where = ['id' => $id];
            $return = $this->user_model->getRow('ladger', $where);
            if ($return) {
                $this->user_model->deleteRow('ladger', $where);
                $this->session->set_flashdata(['msg' => 'Ladger Delete Successfully.', 'class' => 'success']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }