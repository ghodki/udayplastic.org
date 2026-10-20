<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Ledger extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
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
            $LedgerWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $LedgerWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $Data['Ledgers'] = $this->db->select('id, accounting_group, name, ContactPerson, Mobile')->where($LedgerWhere)->order_by('id', 'DESC')->get('ladger')->result();
            $Data['PaymentModes'] = $this->db->select('id, payment_method')->order_by('id', 'DESC')->get('payment_mode')->result();
            $Data['page'] = 'Ledger/index';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Report() 
        {
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $FromDate = $this->input->get('fromDate');
                $ToDate = $this->input->get('toDate');
            } else {
                $FromDate = date('Y-m-d');
                $ToDate = date('Y-m-d');
            }
            
            $FinancialYear = $this->session->userdata('financial_year');
            $UserId = $this->session->userdata('user_id'); 
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $LedgerWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $Where = ['Date >= ' => $FromDate, 'Date <= ' => $ToDate, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
            if ($this->session->userdata('BranchRole')) {
                $LedgerWhere['CompanyBranchId'] = $CompBranchId;
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->input->get('LedgerId')) {
                $Where['LedgerId'] = $this->input->get('LedgerId');
            }
            
            $Data['Ledgers'] = $this->db->select('id, accounting_group, name, ContactPerson, Mobile')->where($LedgerWhere)->order_by('id', 'DESC')->get('ladger')->result();
            $Data['LedgersEntries'] = $this->db->select('Id, Date, AccountGroupId, LedgerId, Amount, Type, PaymentMode')->where($Where)->order_by('Id', 'DESC')->get('ledger_entries')->result();
            $Data['page'] = 'Ledger/Report';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Save() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CreatedBy = get_title('employees', ['Id' => $EmployeeId], 'Name');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CreatedBy = get_title('users', ['id' => $UserId], 'name');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('LedgerId', 'Ledger', 'trim|required');
                $this->form_validation->set_rules('Amount', 'Amount', 'trim|numeric|required');
                $this->form_validation->set_rules('Type', 'Type', 'trim|required');
                $this->form_validation->set_rules('PaymentMode', 'PaymentMode', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $LedgerData = [
                        'Date' => $this->input->post('Date'),
                        'AccountGroupId' => get_title('ladger', ['id' => $this->input->post('LedgerId')], 'accounting_group'),
                        'LedgerId' => $this->input->post('LedgerId'),
                        'Amount' => $this->input->post('Amount'),
                        'Type' => $this->input->post('Type'),
                        'PaymentMode' => $this->input->post('PaymentMode'),
                        'Remark' => $this->input->post('Remark'),
                        'UserId' => $UserId,
                        'CompId' => $CompId,
                        'CompanyBranchId' => $CompBranchId,
                        'EmployeeId' => $EmployeeId,
                        'FinancialYear' => $this->session->userdata('financial_year'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => $CreatedBy,
                    ];
                    
                    $Result = $this->user_model->saveAry('ledger_entries', $LedgerData);
                    if (!empty($Result)) {
                        echo json_encode(['Status' => true, 'Message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Message' => 'Unable to save record']);
                        return;
                    }
                }
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
                
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                if ($this->db->where($Where)->get('ledger_entries')->num_rows()) {
                    $Data['LedgerData'] = $this->db->where($Where)->get('ledger_entries')->row();
                    return $this->load->view('inventory_sep/Ledger/Info', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No Record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getLedgerEdit() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $Id = $this->input->post('Id');
                if ($Id) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    
                    $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }

                    if ($this->db->where($Where)->get('ledger_entries')->num_rows()) {
                        $LedgerId = $this->db->select('Id')->where($Where)->get('ledger_entries')->row('Id');
                        echo json_encode(['Status' => true, 'LedgerId' => $LedgerId]);
                    } else {
                        echo json_encode(['Status' => false, 'msg' => 'No Record found.']);
                    }
                } else {
                    echo json_encode(['Status' => false, 'msg' => 'Id is missing.']);
                }
            } else {
                echo json_encode(['Status' => false, 'msg' => 'Direct entry not allowed.']);
            }
        }
        
        public function Edit($Id) 
        {
            if ($Id) {
                $LedgerWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $LedgerWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                if ($this->db->where($Where)->get('ledger_entries')->num_rows()) {
                    $Data['Ledgers'] = $this->db->select('id, accounting_group, name, ContactPerson, Mobile')->where($LedgerWhere)->order_by('id', 'DESC')->get('ladger')->result();
                    $Data['PaymentModes'] = $this->db->select('id, payment_method')->order_by('id', 'DESC')->get('payment_mode')->result();
                    $Data['LedgerData'] = $this->db->where($Where)->get('ledger_entries')->row();
                    $Data['page'] = 'Ledger/Edit';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No Record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Ledger');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Ledger');
            }
        }
        
        public function Update() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $UpdatedBy = get_title('employees', ['Id' => $EmployeeId], 'Name');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $UpdatedBy = get_title('users', ['id' => $UserId], 'name');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('LedgerId', 'Ledger', 'trim|required');
                $this->form_validation->set_rules('Amount', 'Amount', 'trim|numeric|required');
                $this->form_validation->set_rules('Type', 'Type', 'trim|required');
                $this->form_validation->set_rules('PaymentMode', 'PaymentMode', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $LedgerEntryId = $this->input->post('LedgerEntryId');
                    $LedgerData = [
                        'Date' => $this->input->post('Date'),
                        'AccountGroupId' => get_title('ladger', ['id' => $this->input->post('LedgerId')], 'accounting_group'),
                        'LedgerId' => $this->input->post('LedgerId'),
                        'Amount' => $this->input->post('Amount'),
                        'Type' => $this->input->post('Type'),
                        'PaymentMode' => $this->input->post('PaymentMode'),
                        'Remark' => $this->input->post('Remark'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => $UpdatedBy,
                    ];
                    
                    $UpdateWhere = ['Id' => $LedgerEntryId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $UpdateWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    $Result = $this->user_model->updateAry('ledger_entries', $LedgerData, $UpdateWhere);
                    if (!empty($Result)) {
                        echo json_encode(['Status' => true, 'Message' => 'Record update successfully.']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Message' => 'Unable to update record.']);
                        return;
                    }
                }
            }
        }
        
        public function AjaxLedgerDelete($Id)
        {
            if ($Id) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->db->select('Id')->where($Where)->get('ledger_entries')->num_rows()) {
                    if ($this->user_model->updateAry('ledger_entries', ['IsDeleted' => 0], $Where)) {
                        echo json_encode(['status' => true, 'msg' => 'Record deleted successfully.']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false, 'msg' => 'No record found.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Ledger id is missing.']);
                return;
            }
        }
        
        public function Delete($Id)
        {
            if ($Id) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->db->select('Id')->where($Where)->get('ledger_entries')->num_rows()) {
                    if ($this->user_model->updateAry('ledger_entries', ['IsDeleted' => 0], $Where)) {
                        $this->session->set_flashdata(['msg' => 'Record deleted successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Ledger id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getLedgerStatement()
        {
            if ($_POST) {
                $LedgerId = $this->input->post('LedgerId');
                $FromDate = $this->input->post('fromDate');
                $ToDate = $this->input->post('toDate');
                if ($LedgerId == "" && $LedgerId == null) {
                    echo '<span class="text-danger text-center">Enter required fields.</span>';
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    $FinancialYear = $this->session->userdata('financial_year');
                                
                    $Where = ['LedgerId' => $LedgerId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId; 
                    }
                    if ($FromDate && $ToDate) {
                        $Where['Date >= '] = $FromDate; 
                        $Where['Date <= '] = $ToDate; 
                    }
                    
                    $LedgerEntries = $this->db->where($Where)->order_by('Id', 'DESC')->get('ledger_entries')->result();

                    if ($LedgerEntries) {
                        $table =   '<table class="table table-bordered">'
                                .  '<thead>'
                                .  '<tr>'
                                .  '<th>Sr.</th>'
                                .  '<th>Date</th>'
                                .  '<th>Account</th>'
                                .  '<th>Ledger</th>'
                                .  '<th>Amount</th>'
                                .  '<th>Type</th>'
                                .  '<th>Mode</th>'
                                .  '<th>Days</th>'
                                .  '<th></th>'
                                .  '</tr>'
                                .  '</thead>'
                                .  '</tbody>';
                                $Sr = 0;
                                $totalAmount = 0;
                                $From = "";
                                foreach ($LedgerEntries as $key => $Data) { 
                                    $Sr++;
                                    
                                    $From = $Data->Date;
                                    $totalAmount += $Data->Amount;
                                    $days = abs((strtotime(date('d-m-Y')) - strtotime($Data->Date)) / (60 * 60 * 24));

                                    $action = "<a href='javascript:void(0);' class='btn btn-primary btn-xs' title='Info' onclick='onGetLedgerInfoHandler($Data->Id);'>"
                                            . "<i class='fa fa-info-circle text-white'></i>"
                                            . "</a>"
                                            . "&nbsp;";
                                    
                                    $action .= "<a href='javascript:void(0);' class='btn btn-success btn-xs' title='Edit' onclick='onLedgerEditHandler($Data->Id);'>"
                                            . "<i class='fa fa-edit text-white'></i>"
                                            . "</a>";
                                    
                                    $action .= "&nbsp;"
                                            . "<a href='javascript:void(0);' class='btn btn-danger btn-xs' title='Delete' onclick='onLedgerDeleteHandler($Data->Id);'>"
                                            . "<i class='fa fa-trash-alt text-white'></i>"
                                            . "</a>";
                                    
                        $table .=  '<tr>'
                                .  '<td>' . $Sr . '</td>'
                                .  '<td>' . date('d-m-Y', strtotime($Data->Date)) . '</td>'
                                .  '<td>' . get_title('accounting_group', ['id' => $Data->AccountGroupId], 'name') . '</td>'
                                .  '<td>' . get_title('ladger', ['id' => $Data->LedgerId], 'name') . '</td>'
                                .  '<td><i class="fa fa-inr"></i> ' . $Data->Amount . '</td>'
                                .  '<td>' . $Data->Type . '</td>'
                                .  '<td>' . get_title('payment_mode', ['id' => $Data->PaymentMode], 'payment_method') . '</td>'
                                .  '<td>' . $days . '</td>'
                                .  '<td>' . $action . '</td>'
                                .  '</tr>';
                                }
                        $table  .= '</tbody>'
                                .  '<tfoot>'
                                .  '<tr>'
                                .  '<th colspan="4" style="text-align: right;">Total</th>'
                                .  '<th><i class="fa fa-inr"></i> ' . $totalAmount . '</th>'
                                .  '<th colspan="4"></th>'
                                .  '</tr>'
                                .  '</tfoot>'
                                .  '</table>';
                        echo json_encode(['Status' => true, 'Table' => $table, 'From' => $From, 'To' => date('Y-m-d')]);
                    } else {
                        echo json_encode(['Status' => false, 'Table' => '<span class="text-danger text-center">No Record found.</span>', 'From' => '', 'To' => '']);
                    }
                }
            }
        }
        
        public function PrintStatement()
        {            
            $this->load->view('inventory_sep/Ledger/Print');
        }
    }