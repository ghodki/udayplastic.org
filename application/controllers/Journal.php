<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
    
class Journal extends CI_Controller
{
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'download']);
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
            if ($this->session->userdata('RoleType')) {
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
            } else {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
            }
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $data['ledgers'] = $this->db->select('id, group_head, name')->where($where)->order_by('name', 'ASC')->get('parties')->result();
            $data['page'] = 'journal/index';
            $this->load->view('inventory_sep/inventory', $data);                
        }
        
        public function getVoucherNumber ()
        {
            if ($_POST) {
                $voucherType = $this->input->post('voucherType');
                if ($voucherType == null && $voucherType == "") {
                    echo json_encode(['status' => false, 'data' => '']);
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $userId = $this->session->userdata('UserId');
                        $compId = $this->session->userdata('CompId');
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                    }
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $seriesWhere = ['series.comp_id' => $compId, 'series.user_id' => $userId, 'series.status' => 2];
                    $unorderNumberWhere = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $seriesWhere['CompanyBranchId'] = $compBranchId; 
                        $unorderNumberWhere['CompanyBranchId'] = $compBranchId; 
                    }
                    
                    $series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', $voucherType)->where($seriesWhere)->get()->row();
                    $unorderNumber = $this->db->select('id')->where($unorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;
                    $voucherNumber = isset($series) && !empty($series) ? $this->user_model->getSeris($series) : $unorderNumber;
                    if ($voucherNumber)  {
                        echo json_encode(['status' => true, 'data' => $voucherNumber]);
                    } else {
                        echo json_encode(['status' => false, 'data' => '']);
                    }                    
                }
            } else {
                echo json_encode(['status' => false, 'data' => '']);
            }
        }
        
        public function getLedgerAccountBalance()
        {
            if ($this->input->is_ajax_request()) {
                $ledgerId = $this->input->post('ledgerId');
                if (isset($ledgerId) && !empty($ledgerId)) {
                    $ledgerAccountBalance = getLedgerAccountBalance($ledgerId);
                    echo json_encode(['status' => true, 'ledgerAccountBalance' => $ledgerAccountBalance]);
                } else {
                    echo json_encode(['status' => false, 'ledgerAccountBalance' => 0]);
                }
            }
        }
        
        public function checkJournalEntry() 
        {
            if ($this->input->is_ajax_request()) {
                $journalDate = $this->input->post('journalDate');
                $ledgerId = $this->input->post('ledgerId');
                $amount = $this->input->post('amount');
                
                if (!empty($journalDate) && !empty($ledgerId) && !empty($amount)) {
                    if ($this->session->userdata('RoleType')) {
                        $userId = $this->session->userdata('UserId');
                        $compId = $this->session->userdata('CompId');
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                    }
                    $compBranchId = $this->session->userdata('CompBranchId');

                    $where = ['date' => $journalDate, 'party_id' => $ledgerId, 'debit' => $amount, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1, 'payment_type' => 'Journal'];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                    }

                    if ($this->db->where($where)->get('payment')->num_rows()) {
                        echo json_encode(['status' => false, 'data' => 'notUnique', 'title' => 'Duplicate Data Detected', 'text' => 'The data you are about to submit already exists. Do you want to proceed?']);
                    } else {
                        echo json_encode(['status' => true, 'data' => 'unique', 'title' => '', 'text' => '']);
                    }
                } else {
                    echo json_encode(['status' => false, 'data' => 'notUnique', 'title' => 'Amount is Ziro', 'text' => 'The paid amount ziro. Do you want to proceed?']);
                }
            }
        }
        
        public function save()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->form_validation->set_rules('financial_year', 'Financial Year', 'trim|required');
                $this->form_validation->set_rules('voucher_number', 'Voucher Number', 'trim|required');
                $this->form_validation->set_rules('date', 'Voucher Date', 'trim|required');
                $this->form_validation->set_rules('party_id', 'Ledger By', 'trim|required');
                $this->form_validation->set_rules('paid_amount', 'Amount', 'trim|required|numeric');
                $this->form_validation->set_rules('ledgerIdTo', 'Ledger To', 'trim|required');
                $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $employeeId = $this->session->userdata('EmployeeId');
                        $userId = $this->session->userdata('UserId');
                        $compId = $this->session->userdata('CompId');
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                    }
                    $compBranchId = $this->session->userdata('CompBranchId');                        

                    $seriesWhere = ['series.comp_id' => $compId, 'series.user_id' => $userId, 'series.status' => 2];
                    $unorderNumberWhere = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $seriesWhere['CompanyBranchId'] = $compBranchId; 
                        $unorderNumberWhere['CompanyBranchId'] = $compBranchId; 
                    }
                    
                    $series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Journal')->where($seriesWhere)->get()->row();
                    $unorderNumber = $this->db->select('id')->where($unorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;
                    $voucherNumber = isset($series) && !empty($series) ? $this->user_model->getSeris($series) : $unorderNumber;                        
                    
                    $voucherDate = $this->input->post('date');
                    $ledgerIdBy = $this->input->post('party_id');
                    $ledgerIdTo = $this->input->post('ledgerIdTo');
                    $paidAmount = $this->input->post('paid_amount');
                    $paymentType = $this->input->post('payment_type');
                    $paymentMode = $this->input->post('payment_mode');
                    $chequeDate = $this->input->post('ChequeDate');
                    $remark = $this->input->post('remark');
                    $financialYear = $this->input->post('financial_year');
                    $created = date('d-m-Y h:i:s A');
                    
                    $journalDataDebit = [
                        'voucher_type' => 'Journal',
                        'voucher_number' => $voucherNumber,
                        'date' => $voucherDate,
                        'account_group' => get_title('parties', ['id' => $ledgerIdBy], 'group_head'),
                        'party_id' => $ledgerIdBy, // by ledger
                        'receivedPaidAmount' => $paidAmount,
                        'debit' => $paidAmount,
                        'transaction_type' => 'DR',
                        'payment_type' => $paymentType,
                        'paymentmode' => $paymentMode,
                        'ChequeDate' => $chequeDate,
                        'payment_through' => 'Manually',
                        'remark' => $remark,
                        'user_id' => $userId,
                        'comp_id' => $compId,
                        'CompanyBranchId' => $compBranchId,
                        'EmployeeId' => $employeeId,
                        'financial_year' => $financialYear,
                        'created' => $created
                    ];
                    $saveJournalDebit = $this->user_model->saveAry('payment', $journalDataDebit);
                    
                    $journalDataCredit = [
                        'voucher_type' => 'Journal',
                        'voucher_number' => $voucherNumber,
                        'date' => $voucherDate,
                        'account_group' => get_title('parties', ['id' => $ledgerIdTo], 'group_head'),
                        'party_id' => $ledgerIdTo, // to ledger
                        'receivedPaidAmount' => $paidAmount,
                        'credit' => $paidAmount,
                        'transaction_type' => 'CR',
                        'payment_type' => $paymentType,
                        'paymentmode' => $paymentMode,
                        'ChequeDate' => $chequeDate,
                        'payment_through' => 'Manually',
                        'remark' => $remark,
                        'user_id' => $userId,
                        'comp_id' => $compId,
                        'CompanyBranchId' => $compBranchId,
                        'EmployeeId' => $employeeId,
                        'financial_year' => $financialYear,
                        'created' => $created
                    ];
                    $saveJournalCredit = $this->user_model->saveAry('payment', $journalDataCredit);
                    
                    if (!empty($saveJournalDebit) && !empty($saveJournalCredit)) {
                        $getSeriesWhere = ['series_id' => $series->series_id, 'user_id' => $userId, 'comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                            $getSeriesWhere['CompanyBranchId'] = $compBranchId;
                        }
                        $getSeries = $this->user_model->getRow('series', $getSeriesWhere);
                        if ($getSeries) {
                            if ($getSeries->starter_point == 19) {
                                $seriesNumber = 110;
                            } elseif ($getSeries->starter_point == 199) {
                                $seriesNumber = 1100;
                            } elseif ($getSeries->starter_point == 1999) {
                                $seriesNumber = 11000;
                            } elseif ($getSeries->starter_point == 19999) {
                                $seriesNumber = 110000;
                            } elseif ($getSeries->starter_point == 199999) {
                                $seriesNumber = 1100000;
                            } else {
                                $seriesNumber = $getSeries->starter_point + 1;
                            }
                            $seriesNumberData['starter_point'] = $seriesNumber;
                            $this->user_model->updateAry('series', $seriesNumberData, $getSeriesWhere);
                        }

                        echo json_encode(['status' => true, 'message' => 'Journal saved successfully.']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Somethings went wrong, try again.']);
                        return;
                    }
                }
            }
        }
        
        public function getJournalStatement()
        {
            if ($this->input->is_ajax_request()) {
                $voucherType = $this->input->post('voucherType');
                $fromDate = $this->input->post('fromDate');
                $toDate = $this->input->post('toDate');                
                if (!empty($voucherType) && !empty($fromDate) && !empty($toDate)) {
                    if ($this->session->userdata('RoleType')) {
                        $employeeId = $this->session->userdata('EmployeeId');
                        $userId = $this->session->userdata('UserId');
                        $compId = $this->session->userdata('CompId');
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                    }
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId, 'payment_type' => $voucherType];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId; 
                    }
                    if ($fromDate && $toDate) {
                        $where['date >= '] = $fromDate; 
                        $where['date <= '] = $toDate; 
                    }
                    
                    $journalLedgerQuery = $this->db->select('id, party_id, date, voucher_number, payment_type, paymentmode, debit, credit')->where($where)->order_by('id', 'ASC')->get('payment');

                    if ($journalLedgerQuery->num_rows()) {
                        $table =   '<table class="table table-bordered" id="allPayments">'
                                .  '<thead>'
                                .  '<tr>'
                                .  '<th>Sr.</th>'
                                .  '<th>Date</th>'
                                .  '<th>Voucher No.</th>'                            
                                .  '<th>Ledger Name</th>'
                                .  '<th>Mode</th>'
                                .  '<th>Debit</th>'
                                .  '<th>Credit</th>'
                                .  '<th>Days</th>'
                                .  '<th></th>'
                                .  '</tr>'
                                .  '</thead>'
                                .  '</tbody>';
                        
                                $srNo = 0;
                                $totalDebit = 0;
                                $totalCredit = 0;
                                $totalBalance = 0;
                                foreach ($journalLedgerQuery->result() as $key => $data) {
                                    $srNo++;
                                    $totalDebit += $data->debit;
                                    $totalCredit += $data->credit;
                                    
                                    $ledgerName = get_title('parties', ['id' => $data->party_id], 'name');
                                    if (get_title('payment_mode', ['id' => $data->paymentmode], 'payment_method') == "Cheque") {
                                        $mode = get_title('payment_mode', ['id' => $data->paymentmode], 'payment_method');
                                        $mode .= $data->ChequeDate ? " <small>(" . date('d-m-Y', strtotime($data->ChequeDate)) . ")</small>" : "";
                                    } else {
                                        $mode = get_title('payment_mode', ['id' => $data->paymentmode], 'payment_method');
                                    }
                                    $credit = !empty($data->credit) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($data->credit) : "-";
                                    $debit = !empty($data->debit) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($data->debit) : "-";
                                    $days = abs((strtotime(date('d-m-Y')) - strtotime($data->date)) / (60 * 60 * 24));
                                    if ($this->session->userdata('RoleType')) {
                                        if (json_decode(get_title('employee_permission', ['EmployeeId' => $employeeId], 'Payments'))->Delete == "Delete") {
                                            $action = "<a href='javascript:void(0);' class='btn btn-primary btn-xs' title='Remark' onclick='onGetJournalRemarkHandler($data->id);'>"
                                                    . "<i class='fa fa-info-circle'></i>"
                                                    . "</a>"
                                                    . "&nbsp;"
                                                    . "<a href='javascript:void(0);' class='btn btn-danger btn-xs' title='Delete' onclick='onJournalDeleteHandler($data->id);'>"
                                                    . "<i class='fa fa-trash-alt'></i>"
                                                    . "</a>";
                                        } else {
                                            $action = "<a href='javascript:void(0);' class='btn btn-primary btn-xs' title='Remark' onclick='onGetJournalRemarkHandler($data->id);'>"
                                                    . "<i class='fa fa-info-circle'></i>"
                                                    . "</a>";
                                        } 
                                    } else {
                                        $action = "<a href='javascript:void(0);' class='btn btn-primary btn-xs' title='Remark' onclick='onGetJournalRemarkHandler($data->id);'>"
                                                . "<i class='fa fa-info-circle'></i>"
                                                . "</a>"
                                                . "&nbsp;"
                                                . "<a href='javascript:void(0);' class='btn btn-danger btn-xs' title='Delete' onclick='onJournalDeleteHandler($data->id);'>"
                                                . "<i class='fa fa-trash-alt'></i>"
                                                . "</a>";
                                    }                                    
                                    
                                    $table .=  '<tr>'
                                            .  '<td>' . $srNo . '</td>'
                                            .  '<td>' . date('d-m-Y', strtotime($data->date)) . '</td>'                                            
                                            .  '<td>' . $data->voucher_number . '</td>'
                                            .  '<td>' . $ledgerName . '</td>'
                                            .  '<td>' . $mode . '</td>'
                                            .  '<td>' . $debit . '</td>'
                                            .  '<td>' . $credit . '</td>'
                                            .  '<td>' . $days . '</td>'
                                            .  '<td>' . $action . '</td>'
                                            .  '</tr>';                                    
                                }
                                $table  .= '</tbody>'
                                        .  '<tfoot>'
                                        .  '<tr>'
                                        .  '<th colspan="5" style="text-align: right;">Total</th>'
                                        .  '<th><i class="fa fa-inr"></i> ' . indianMoneyFormat($totalDebit) . '</th>'
                                        .  '<th><i class="fa fa-inr"></i> ' . indianMoneyFormat($totalCredit) . '</th>'
                                        .  '<th></th>'
                                        .  '<th></th>'
                                        .  '</tr>'
                                        .  '</tfoot>'
                                        .  '</table>';      
                                echo $table;
                    } else {
                        echo '<span class="text-danger text-center">No Record found.</span>';
                    }
                } else {
                    echo '<span class="text-danger text-center">Enter required fields.</span>';
                }
            }
        }
        
        public function getJournalRemark($journalId) 
        {
            if (!empty($journalId)) {
                $data['remarkData'] = get_title('payment', ['id' => $journalId], 'remark');
                return $this->load->view('inventory_sep/journal/journalRemarkData', $data);
            }
        }
        
        public function ajaxDelete($journalId)
        {
            if (isset($journalId) && !empty($journalId)) {
                if ($this->session->userdata('RoleType')) {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                } else {
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                }
                $compBranchId = $this->session->userdata('CompBranchId');

                $deleteWhere = ['payment_through' => 'Manually', 'user_id' => $userId, 'comp_id' => $compId, 'payment_type' => 'Journal'];
                $voucherNumberWhere = ['id' => $journalId, 'payment_through' => 'Manually', 'user_id' => $userId, 'comp_id' => $compId, 'payment_type' => 'Journal'];
                if ($this->session->userdata('BranchRole')) {
                    $deleteWhere['CompanyBranchId'] = $compBranchId;
                    $voucherNumberWhere['CompanyBranchId'] = $compBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $deleteWhere['EmployeeId'] = $employeeId;
                    $voucherNumberWhere['EmployeeId'] = $employeeId;
                }
                
                $voucherNumber = $this->db->select('voucher_number')->where($voucherNumberWhere)->get('payment')->row('voucher_number');
                $deleteWhere['voucher_number'] = $voucherNumber;
                
                $deleteJournalQuery = $this->db->select('voucher_number')->where($deleteWhere)->get('payment');
                if ($deleteJournalQuery->num_rows()) {
                    $this->db->where($deleteWhere)->update('payment', ['status' => 0]);
                    echo json_encode(['status' => true, 'msg' => 'Journal deleted successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Journal id not found.']);
                return;
            }
        }
        
        public function delete($journalId)
        {
            if (isset($journalId) && !empty($journalId)) {
                if ($this->session->userdata('RoleType')) {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                } else {
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                }
                $compBranchId = $this->session->userdata('CompBranchId');

                $deleteWhere = ['payment_through' => 'Manually', 'user_id' => $userId, 'comp_id' => $compId, 'payment_type' => 'Journal'];
                $voucherNumberWhere = ['id' => $journalId, 'payment_through' => 'Manually', 'user_id' => $userId, 'comp_id' => $compId, 'payment_type' => 'Journal'];
                if ($this->session->userdata('BranchRole')) {
                    $deleteWhere['CompanyBranchId'] = $compBranchId;
                    $voucherNumberWhere['CompanyBranchId'] = $compBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $deleteWhere['EmployeeId'] = $employeeId;
                    $voucherNumberWhere['EmployeeId'] = $employeeId;
                }
                
                $voucherNumber = $this->db->select('voucher_number')->where($voucherNumberWhere)->get('payment')->row('voucher_number');
                $deleteWhere['voucher_number'] = $voucherNumber;
                
                $deleteJournalQuery = $this->db->select('voucher_number')->where($deleteWhere)->get('payment');
                if ($deleteJournalQuery->num_rows()) {
                    $this->db->where($deleteWhere)->update('payment', ['status' => 0]);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Journal id not found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
}