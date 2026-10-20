<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class AccountLedger extends CI_Controller 
{
        public function __construct() 
        {
                parent::__construct();
                $this->load->model('user_model');

                if (empty($this->session->userdata('user_id'))) {
                        return redirect(base_url() . 'index');
                }
        }
        
        // account receipt voucher
        public function receipt() 
        {
                if ($this->input->get('from') && $this->input->get('to')) {
                        $from = $this->input->get('from');
                        $to = $this->input->get('to');
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }                
                
                $where = ['date >= ' => $from, 'date <= ' => $to, 'type' => 'Receipt', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $receiptQuery = $this->db->select('id, date, voucherNumber, ledgerId, narration, transactionType, debit, credit')->where($where)->order_by('id', 'DESC')->get('account_ledger');
                if ($receiptQuery->num_rows()) {
                        $data['receiptData'] = $receiptQuery->result();
                } else {
                        $data['receiptData'] = [];
                }
                $data['page'] = 'accounts/receipt/index';
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function receiptCreate() 
        {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                $voucherWhere = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                $seriesWhere = ['user_id' => $userId, 'comp_id' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                        $seriesWhere['CompanyBranchId'] = $compBranchId;
                        $voucherWhere['compBranchId'] = $compBranchId;
                }
                
                $voucherSeriesQuery = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where(['seriestitle.series' => 'Receipt Voucher', 'series.status' => 2])->where($seriesWhere)->get()->row();
                if (isset($voucherSeriesQuery) && !empty($voucherSeriesQuery)) {
                        $voucherNumber = $this->user_model->getSeris($voucherSeriesQuery);
                } else {
                        $lastRecord = $this->db->select('voucherNumber')->where($voucherWhere)->where('type', 'Receipt')->order_by('id', 'DESC')->limit(1)->get('account_ledger')->row('voucherNumber');
                        if ($lastRecord) {
                                $voucherNumber = str_pad($lastRecord + 1, 3, '0', STR_PAD_LEFT);
                        } else {
                                $voucherNumber = '001';
                        }
                }
                $data['seriesId'] = $voucherSeriesQuery->series_id;
                $data['newVoucherNumber'] = $voucherNumber;
                $data['ledgers'] = $this->db->select('id, name, group_head')->where($where)->order_by('id', 'DESC')->get('parties')->result();
                $data['page'] = 'accounts/receipt/create';
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getLedgerCurrentBalance()
        {
                if ($this->input->is_ajax_request()) {
                        $ledgerId = $this->input->post('ledgerId');
                        if (isset($ledgerId) && !empty($ledgerId)) {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $where = ['ledgerId' => $ledgerId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                        $where['compBranchId'] = $compBranchId;
                                }
                                
                                $ledgerCurrentBalanceQuery = $this->db->select('SUM(credit) AS creditSum, SUM(debit) As debitSum')->where($where)->get('account_ledger');
                                if ($ledgerCurrentBalanceQuery->num_rows()) {
                                        $ledgerCurrentBalanceData = $ledgerCurrentBalanceQuery->row();
                                        $ledgerCurrentBalance = $ledgerCurrentBalanceData->debitSum - $ledgerCurrentBalanceData->creditSum;
                                        
                                        if ($ledgerCurrentBalance < 0) {
                                                $balanceType = 'Dr';
                                        } else {
                                                $balanceType = 'Cr';
                                        }
                                        $ledgerNetBalanceAmount = abs($ledgerCurrentBalance);
                                        $arrayData['ledgerNetBalance'] = $ledgerNetBalanceAmount;
                                        $arrayData['balanceType'] = $balanceType;
                                        
                                        echo json_encode(array('status' => true, 'data' => $arrayData));
                                        return;
                                } else {
                                        echo json_encode(array('status' => false, 'data' => ""));
                                        return;
                                }
                        } else {
                                echo json_encode(array('status' => false, 'data' => ""));
                                return;
                        }
                }
        }
        
        public function getLedgerAccountCurrentBalance()
        {
                if ($this->input->is_ajax_request()) {
                        $ledgerAccountId = $this->input->post('ledgerAccountId');
                        if (isset($ledgerAccountId) && !empty($ledgerAccountId)) {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $where = ['ledgerId' => $ledgerAccountId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                        $where['compBranchId'] = $compBranchId;
                                }
                                
                                $ledgerAccountCurrentBalanceQuery = $this->db->select('SUM(credit) AS creditSum, SUM(debit) As debitSum')->where($where)->get('account_ledger');
                                if ($ledgerAccountCurrentBalanceQuery->num_rows()) {
                                        $ledgerAccountCurrentBalanceData = $ledgerAccountCurrentBalanceQuery->row();
                                        $ledgerAccountCurrentBalance = $ledgerAccountCurrentBalanceData->debitSum - $ledgerAccountCurrentBalanceData->creditSum;
                                        
                                        if ($ledgerAccountCurrentBalance > 0) {
                                                $balanceType = 'Cr';
                                        } else {
                                                $balanceType = 'Dr';
                                        }
                                        $ledgerAccountNetBalanceAmount = abs($ledgerAccountCurrentBalance);
                                        $arrayData['ledgerAccountNetBalance'] = $ledgerAccountNetBalanceAmount;
                                        $arrayData['balanceType'] = $balanceType;
                                        
                                        echo json_encode(array('status' => true, 'data' => $arrayData));
                                        return;
                                } else {
                                        echo json_encode(array('status' => false, 'data' => ""));
                                        return;
                                }
                        } else {
                                echo json_encode(array('status' => false, 'data' => ""));
                                return;
                        }
                }
        }
        
        public function isUniqeReceiptVoucherNumber()
        {
                $voucherNumber = $this->input->post('voucherNumber');
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['type' => 'Receipt', 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1, 'voucherNumber' => $voucherNumber];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                }
            
                if ($this->db->select('id')->where($where)->get('account_ledger')->num_rows() === 0) {
                        return true;
                } else {
                        $this->form_validation->set_message('isUniqeReceiptVoucherNumber', 'The voucher number must be unique.');
                        return false;
                }
        }
        
        public function receiptSave() 
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'Receipt Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required|callback_isUniqeReceiptVoucherNumber');
                        $this->form_validation->set_rules('ledgerId', 'Ledger Name', 'trim|required');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');                        
                        
                        if ($this->form_validation->run() == false) {
                                echo json_encode(['status' => false, 'message' => validation_errors()]);
                                return;
                        } else {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                                if ($this->session->userdata('BranchRole')) {
                                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                                }
                                
                                $receiptDate = $this->input->post('date');
                                $voucherNumber = $this->input->post('voucherNumber');
                                $ledgerId = $this->input->post('ledgerId');
                                $ledgerAccountIds = $this->input->post('ledgerAccountId');
                                $narration = $this->input->post('narration');
                                $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                $created = date('d-m-Y h:i:s A');
                                $createdBy = get_title('users', ['id' => $userId], 'name');
                                
                                $resultCredit = "";
                                $resultDebit = "";                                
		foreach ($ledgerAccountIds as $key => $ledgerAccount) {                                        
                                        if (!empty($ledgerAccount) && !empty($ledgerId)) {
                                                $amount = $this->input->post('amount')[$key];
                                                if (!empty($amount) && $amount > 0) {
                                                        $receiptCreditData = [
                                                                'date' => $receiptDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerId,
                                                                'debit' => '0.00',
                                                                'credit' => $amount,
                                                                'type' => 'Receipt',
                                                                'transactionType' => 'Cr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];                                
                                                        $resultCredit = $this->user_model->saveAry('account_ledger', $receiptCreditData);                                

                                                        $receiptDebitData = [
                                                                'date' => $receiptDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerAccount,
                                                                'debit' => $amount,
                                                                'credit' => '0.00',
                                                                'type' => 'Receipt',
                                                                'transactionType' => 'Dr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];                                
                                                        $resultDebit = $this->user_model->saveAry('account_ledger', $receiptDebitData);
                                                } else {
                                                        echo json_encode(['status' => false, 'message' => 'The amount must be greater than zero and cannot be blank.']);
                                                        return;
                                                }
                                        } else {
                                                echo json_encode(['status' => false, 'message' => 'Both ledgers are required.']);
                                                return;
                                        }
                                }
                                
                                if (!empty($resultCredit) && !empty($resultDebit)) {
                                        $seriesUpdateWhere = ['series_id' => $this->input->post('seriesId'), 'user_id' => $userId, 'comp_id' => $compId];
                                        if ($this->session->userdata('BranchRole')) {
                                                $seriesUpdateWhere['CompanyBranchId'] = $compBranchId;
                                        }
                                        
                                        $seriesData = $this->user_model->getRow('series', $seriesUpdateWhere);                                        
                                        if ($seriesData) {
                                                $starterPoint = $seriesData->starter_point + 1;
                                                $this->user_model->updateAry('series', ['starter_point' => $starterPoint], $seriesUpdateWhere);
                                        }
                                        echo json_encode(['status' => true, 'message' => 'Record saved successfully.']);
                                        return;
                                } else {
                                        echo json_encode(['status' => false, 'message' => 'Unable to save the record. Please try again.']);
                                        return;
                                }                                
                        }
                }
        }
        
        public function receiptInfo($receiptId) 
        {
                if (isset($receiptId) && !empty($receiptId)) {
                        $where = ['id' => $receiptId, 'type' => 'Receipt', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        
                        $receiptInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($receiptInfoQuery->num_rows()) {
                                $data['receiptData'] = $receiptInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/receipt/info', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/receipt');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Receipt id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/receipt');
                }
        }
        
        public function receiptPrint($receiptId)
        {
                if (isset($receiptId) && !empty($receiptId)) {
                        $where = ['id' => $receiptId, 'type' => 'Receipt', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        $compProfileWhere = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                $compProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                
                                $data['branch'] = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId')]);
                        } else {
                                $data['company'] = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id')]);
                        }
                
                        $receiptInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($receiptInfoQuery->num_rows()) {
                                $data['compProfile'] = $this->db->select('content, panNumber')->where($compProfileWhere)->get('comp_profile')->row();
                                $data['receiptData'] = $receiptInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/receipt/print', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/receipt');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Receipt id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/receipt');
                }
        }
                
        public function receiptEdit($receiptId) 
        {
                if (isset($receiptId) && !empty($receiptId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $receiptId, 'type' => 'Receipt', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $topLedgerWhere = ['type' => 'Receipt', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $ledgerWhere = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $topLedgerWhere['CompanyBranchId'] = $compBranchId;
                                $ledgerWhere['CompanyBranchId'] = $compBranchId;
                        }
                        
                        $receiptInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($receiptInfoQuery->num_rows()) {
                                $data['receiptData'] = $receiptInfoQuery->row();
                                $data['ledgerId'] = $this->db->select('ledgerId')->where($topLedgerWhere)->where('voucherNumber', $receiptInfoQuery->row()->voucherNumber)->order_by('id', 'ASC')->get('account_ledger')->row()->ledgerId;
                                $data['ledgers'] = $this->db->select('id, name, group_head')->where($ledgerWhere)->order_by('id', 'DESC')->get('parties')->result();                                
                                $data['page'] = 'accounts/receipt/edit';
                                return $this->load->view('inventory_sep/inventory', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/receipt');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Receipt id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/receipt');
                }
        }
        
        public function receiptUpdate()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'Receipt Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required');
                        $this->form_validation->set_rules('ledgerId', 'Ledger Name', 'trim|required');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');                        
                        
                        if ($this->form_validation->run() == false) {
                                echo json_encode(['status' => false, 'message' => validation_errors()]);
                                return;
                        } else {
                                $voucherNumber = $this->input->post('voucherNumber');
                                $receiptId = $this->input->post('receiptId');
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $receiptWhere = ['voucherNumber' => $voucherNumber, 'type' => 'Receipt', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                                $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                                $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                                if ($this->session->userdata('BranchRole')) {
                                        $receiptWhere['compBranchId'] = $compBranchId;
                                        $where['CompanyBranchId'] = $compBranchId;
                                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                                }
                                //delete previous
                                $this->db->where($receiptWhere)->delete('account_ledger');
                                
                                //insert now
                                $receiptDate = $this->input->post('date');
                                $voucherNumber = $this->input->post('voucherNumber');
                                $ledgerId = $this->input->post('ledgerId');
                                $ledgerAccountIds = $this->input->post('ledgerAccountId');
                                $narration = $this->input->post('narration');
                                $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                $created = date('d-m-Y h:i:s A');
                                $createdBy = get_title('users', ['id' => $userId], 'name');
                                
                                $resultCredit = "";
                                $resultDebit = "";
		foreach ($ledgerAccountIds as $key => $ledgerAccount) {
                                        if (!empty($ledgerAccount) && !empty($ledgerId)) {
                                                $amount = $this->input->post('amount')[$key];
                                                if (!empty($amount) && $amount > 0) {
                                                        $receiptCreditData = [
                                                                'date' => $receiptDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerId,
                                                                'debit' => '0.00',
                                                                'credit' => $amount,
                                                                'type' => 'Receipt',
                                                                'transactionType' => 'Cr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];                                
                                                        $resultCredit = $this->user_model->saveAry('account_ledger', $receiptCreditData);                                

                                                        $receiptDebitData = [
                                                                'date' => $receiptDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerAccount,
                                                                'debit' => $amount,
                                                                'credit' => '0.00',
                                                                'type' => 'Receipt',
                                                                'transactionType' => 'Dr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];                                
                                                        $resultDebit = $this->user_model->saveAry('account_ledger', $receiptDebitData);
                                                } else {
                                                        echo json_encode(['status' => false, 'message' => 'The amount must be greater than zero and cannot be blank.']);
                                                        return;
                                                }
                                        } else {
                                                echo json_encode(['status' => false, 'message' => 'Both ledgers are required.']);
                                                return;
                                        }
                                }
                                
                                if (!empty($resultCredit) && !empty($resultDebit)) {
                                        echo json_encode(['status' => true, 'message' => 'Record update successfully.']);
                                        return;
                                } else {
                                        echo json_encode(['status' => false, 'message' => 'Unable to update record.']);
                                        return;
                                }                                
                        }
                }
        }
        
        public function receiptDelete($receiptId)
        {
                if (isset($receiptId) && !empty($receiptId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $receiptId, 'type' => 'Receipt', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $receiptWhere = ['type' => 'Receipt', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $receiptWhere['compBranchId'] = $compBranchId;
                        }
                        
                        $voucherNumber = get_title('account_ledger', $where, 'voucherNumber');
                        if ($voucherNumber) {
                                $receiptWhere['voucherNumber'] = $voucherNumber;
                        }
                        $receiptInfoQuery = $this->db->select('id')->where($receiptWhere)->get('account_ledger');
                        if ($receiptInfoQuery->num_rows()) {
                                if ($this->db->where($receiptwhere)->update('account_ledger', ['isDeleted' => 0])) {
                                        $this->session->set_flashdata(['msg' => 'Record has been deleted.', 'class' => 'success']);
                                        return redirect(base_url() . 'accountLedger/receipt');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Record has been not deleted.', 'class' => 'danger']);
                                        return redirect(base_url() . 'accountLedger/receipt');
                                }
                        } else {
                                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/receipt');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Receipt id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/receipt');
                }                
        }
        
        // account payment voucher
        public function payment()
        {
                if ($this->input->get('from') && $this->input->get('to')) {
                        $from = $this->input->get('from');
                        $to = $this->input->get('to');
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }                
                
                $where = ['date >= ' => $from, 'date <= ' => $to, 'type' => 'Payment', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $paymentQuery = $this->db->select('id, date, voucherNumber, ledgerId, narration, transactionType, debit, credit')->where($where)->order_by('id', 'DESC')->get('account_ledger');
                if ($paymentQuery->num_rows()) {
                        $data['paymentData'] = $paymentQuery->result();
                } else {
                        $data['paymentData'] = [];
                }
                $data['page'] = 'accounts/payment/index';
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function paymentCreate() 
        {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                $voucherWhere = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                $seriesWhere = ['user_id' => $userId, 'comp_id' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                        $seriesWhere['CompanyBranchId'] = $compBranchId;
                        $voucherWhere['compBranchId'] = $compBranchId;
                }
                
                $voucherSeriesQuery = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where(['seriestitle.series' => 'Payment Voucher', 'series.status' => 2])->where($seriesWhere)->get()->row();
                if (isset($voucherSeriesQuery) && !empty($voucherSeriesQuery)) {
                        $voucherNumber = $this->user_model->getSeris($voucherSeriesQuery);
                } else {
                        $lastRecord = $this->db->select('voucherNumber')->where($voucherWhere)->where('type', 'Payment')->order_by('id', 'DESC')->limit(1)->get('account_ledger')->row('voucherNumber');
                        if ($lastRecord) {
                                $voucherNumber = str_pad($lastRecord + 1, 3, '0', STR_PAD_LEFT);
                        } else {
                                $voucherNumber = '001';
                        }
                }
                $data['seriesId'] = $voucherSeriesQuery->series_id;
                $data['newVoucherNumber'] = $voucherNumber;
                $data['ledgers'] = $this->db->select('id, name, group_head')->where($where)->order_by('id', 'DESC')->get('parties')->result();
                $data['page'] = 'accounts/payment/create';
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function isUniqePaymentVoucherNumber()
        {
                $voucherNumber = $this->input->post('voucherNumber');
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['type' => 'Payment', 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1, 'voucherNumber' => $voucherNumber];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                }
            
                if ($this->db->select('id')->where($where)->get('account_ledger')->num_rows() === 0) {
                        return true;
                } else {
                        $this->form_validation->set_message('isUniqePaymentVoucherNumber', 'The voucher number must be unique.');
                        return false;
                }
        }
        
        public function paymentSave() 
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'Payment Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required|callback_isUniqePaymentVoucherNumber');
                        $this->form_validation->set_rules('ledgerId', 'Ledger Name', 'trim|required');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');
                        
                        if ($this->form_validation->run() == false) {
                                echo json_encode(['status' => false, 'message' => validation_errors()]);
                                return;
                        } else {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                                if ($this->session->userdata('BranchRole')) {
                                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                                }
                                
                                $paymentDate = $this->input->post('date');
                                $voucherNumber = $this->input->post('voucherNumber');
                                $ledgerId = $this->input->post('ledgerId');
                                $ledgerAccountIds = $this->input->post('ledgerAccountId');
                                $narration = $this->input->post('narration');
                                $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                $created = date('d-m-Y h:i:s A');
                                $createdBy = get_title('users', ['id' => $userId], 'name');
                                
                                $resultCredit = "";
                                $resultDebit = "";
                                foreach ($ledgerAccountIds as $key => $ledgerAccount) {                                        
                                        if (!empty($ledgerAccount) && !empty($ledgerId)) {
                                                $amount = $this->input->post('amount')[$key];
                                                if (!empty($amount) && $amount > 0) {
                                                        $paymentDebitData = [
                                                                'date' => $paymentDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerId,
                                                                'debit' => $amount,
                                                                'credit' => '0.00',
                                                                'type' => 'Payment',
                                                                'transactionType' => 'Dr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];
                                                        $resultDebit = $this->user_model->saveAry('account_ledger', $paymentDebitData);
                                                
                                                        $paymentCreditData = [
                                                                'date' => $paymentDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerAccount,
                                                                'debit' => '0.00',
                                                                'credit' => $amount,
                                                                'type' => 'Payment',
                                                                'transactionType' => 'Cr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];
                                                        $resultCredit = $this->user_model->saveAry('account_ledger', $paymentCreditData);
                                                } else {
                                                        echo json_encode(['status' => false, 'message' => 'The amount must be greater than zero and cannot be blank.']);
                                                        return;
                                                }
                                        } else {
                                                echo json_encode(['status' => false, 'message' => 'Both ledgers are required.']);
                                                return;
                                        }
                                }
                                
                                if (!empty($resultCredit) && !empty($resultDebit)) {
                                        $seriesUpdateWhere = ['series_id' => $this->input->post('seriesId'), 'user_id' => $userId, 'comp_id' => $compId];
                                        if ($this->session->userdata('BranchRole')) {
                                                $seriesUpdateWhere['CompanyBranchId'] = $compBranchId;
                                        }
                                        
                                        $seriesData = $this->user_model->getRow('series', $seriesUpdateWhere);
                                        if ($seriesData) {
                                                $starterPoint = $seriesData->starter_point + 1;
                                                $this->user_model->updateAry('series', ['starter_point' => $starterPoint], $seriesUpdateWhere);
                                        }
                                        
                                        echo json_encode(['status' => true, 'message' => 'Record saved successfully.']);
                                        return;
                                } else {
                                        echo json_encode(['status' => false, 'message' => 'Unable to save the record. Please try again.']);
                                        return;
                                }                                
                        }
                }
        }
        
        public function paymentInfo($paymentId) 
        {
                if (isset($paymentId) && !empty($paymentId)) {
                        $where = ['id' => $paymentId, 'type' => 'Payment', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        
                        $paymentInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($paymentInfoQuery->num_rows()) {
                                $data['paymentData'] = $paymentInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/payment/info', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/payment');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Payment id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/payment');
                }
        }
        
        public function paymentPrint($paymentId)
        {
                if (isset($paymentId) && !empty($paymentId)) {
                        $where = ['id' => $paymentId, 'type' => 'Payment', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        $compProfileWhere = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                $compProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                
                                $data['branch'] = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId')]);
                        } else {
                                $data['company'] = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id')]);
                        }
                
                        $paymentInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($paymentInfoQuery->num_rows()) {
                                $data['compProfile'] = $this->db->select('content, panNumber')->where($compProfileWhere)->get('comp_profile')->row();
                                $data['paymentData'] = $paymentInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/payment/print', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/payment');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Payment id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/payment');
                }
        }
                
        public function paymentEdit($paymentId) 
        {
                if (isset($paymentId) && !empty($paymentId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $paymentId, 'type' => 'Payment', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $topLedgerWhere = ['type' => 'Payment', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $ledgerWhere = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $topLedgerWhere['CompanyBranchId'] = $compBranchId;
                                $ledgerWhere['CompanyBranchId'] = $compBranchId;
                        }
                        
                        $paymentInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($paymentInfoQuery->num_rows()) {
                                $data['paymentData'] = $paymentInfoQuery->row();
                                $data['ledgerId'] = $this->db->select('ledgerId')->where($topLedgerWhere)->where('voucherNumber', $paymentInfoQuery->row()->voucherNumber)->order_by('id', 'ASC')->get('account_ledger')->row()->ledgerId;
                                $data['ledgers'] = $this->db->select('id, name, group_head')->where($ledgerWhere)->order_by('id', 'DESC')->get('parties')->result();                                
                                $data['page'] = 'accounts/payment/edit';
                                return $this->load->view('inventory_sep/inventory', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/payment');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Payment id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/payment');
                }
        }
        
        public function paymentUpdate()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'Payment Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required');
                        $this->form_validation->set_rules('ledgerId', 'Ledger Name', 'trim|required');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');                        
                        
                        if ($this->form_validation->run() == false) {
                                echo json_encode(['status' => false, 'message' => validation_errors()]);
                                return;
                        } else {
                                $voucherNumber = $this->input->post('voucherNumber');
                                $paymentId = $this->input->post('paymentId');
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $paymentWhere = ['voucherNumber' => $voucherNumber, 'type' => 'Payment', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                                $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                                $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                                if ($this->session->userdata('BranchRole')) {
                                        $paymentWhere['compBranchId'] = $compBranchId;
                                        $where['CompanyBranchId'] = $compBranchId;
                                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                                }
                                
                                //delete previous
                                $this->db->where($paymentWhere)->delete('account_ledger');
                                
                                //insert now
                                $paymentDate = $this->input->post('date');
                                $ledgerId = $this->input->post('ledgerId');
                                $ledgerAccountIds = $this->input->post('ledgerAccountId');
                                $narration = $this->input->post('narration');
                                $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                $created = date('d-m-Y h:i:s A');
                                $createdBy = get_title('users', ['id' => $userId], 'name');
                                
                                $resultCredit = "";
                                $resultDebit = "";
		foreach ($ledgerAccountIds as $key => $ledgerAccount) {
                                        if (!empty($ledgerAccount) && !empty($ledgerId)) {
                                                $amount = $this->input->post('amount')[$key];
                                                if (!empty($amount) && $amount > 0) {
                                                        $paymentDebitData = [
                                                                'date' => $paymentDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerId,
                                                                'debit' => $amount,
                                                                'credit' => '0.00',
                                                                'type' => 'Payment',
                                                                'transactionType' => 'Dr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];
                                                        $resultDebit = $this->user_model->saveAry('account_ledger', $paymentDebitData);

                                                        $paymentCreditData = [
                                                                'date' => $paymentDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerAccount,
                                                                'debit' => '0.00',
                                                                'credit' => $amount,
                                                                'type' => 'Payment',
                                                                'transactionType' => 'Cr',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];                                
                                                        $resultCredit = $this->user_model->saveAry('account_ledger', $paymentCreditData);
                                                } else {
                                                        echo json_encode(['status' => false, 'message' => 'The amount must be greater than zero and cannot be blank.']);
                                                        return;
                                                }
                                        } else {
                                                echo json_encode(['status' => false, 'message' => 'Both ledgers are required.']);
                                                return;
                                        }
                                }
                                
                                if (!empty($resultCredit) && !empty($resultDebit)) {
                                        echo json_encode(['status' => true, 'message' => 'Record update successfully.']);
                                        return;
                                } else {
                                        echo json_encode(['status' => false, 'message' => 'Unable to update record.']);
                                        return;
                                }                                
                        }
                }
        }
        
        public function paymentDelete($paymentId)
        {
                if (isset($paymentId) && !empty($paymentId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $paymentId, 'type' => 'Payment', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $paymentWhere = ['type' => 'Payment', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $paymentWhere['compBranchId'] = $compBranchId;
                        }
                        
                        $voucherNumber = get_title('account_ledger', $where, 'voucherNumber');
                        if ($voucherNumber) {
                                $paymentWhere['voucherNumber'] = $voucherNumber;
                        }
                        $paymentInfoQuery = $this->db->select('id')->where($paymentWhere)->get('account_ledger');
                        if ($paymentInfoQuery->num_rows()) {
                                if ($this->db->where($paymentWhere)->update('account_ledger', ['isDeleted' => 0])) {
                                        $this->session->set_flashdata(['msg' => 'Record has been deleted.', 'class' => 'success']);
                                        return redirect(base_url() . 'accountLedger/payment');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Record has been not deleted.', 'class' => 'danger']);
                                        return redirect(base_url() . 'accountLedger/payment');
                                }
                        } else {
                                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/payment');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Receipt id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/receipt');
                }                
        }
        
        // account general voucher
        public function general() 
        {
                if ($this->input->get('from') && $this->input->get('to')) {
                        $from = $this->input->get('from');
                        $to = $this->input->get('to');
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }                
                
                $where = ['date >= ' => $from, 'date <= ' => $to, 'type' => 'General', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $generalQuery = $this->db->select('id, date, voucherNumber, ledgerId, narration, transactionType, debit, credit')->where($where)->order_by('id', 'DESC')->get('account_ledger');
                if ($generalQuery->num_rows()) {
                        $data['generalData'] = $generalQuery->result();
                } else {
                        $data['generalData'] = [];
                }
                $data['page'] = 'accounts/general/index';
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function generalCreate() 
        {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                $voucherWhere = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                $seriesWhere = ['user_id' => $userId, 'comp_id' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                        $seriesWhere['CompanyBranchId'] = $compBranchId;
                        $voucherWhere['compBranchId'] = $compBranchId;
                }
                
                $voucherSeriesQuery = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where(['seriestitle.series' => 'General Voucher', 'series.status' => 2])->where($seriesWhere)->get()->row();
                if (isset($voucherSeriesQuery) && !empty($voucherSeriesQuery)) {
                        $voucherNumber = $this->user_model->getSeris($voucherSeriesQuery);
                } else {
                        $lastRecord = $this->db->select('voucherNumber')->where($voucherWhere)->where('type', 'General')->order_by('id', 'DESC')->limit(1)->get('account_ledger')->row('voucherNumber');
                        if ($lastRecord) {
                                $voucherNumber = str_pad($lastRecord + 1, 3, '0', STR_PAD_LEFT);
                        } else {
                                $voucherNumber = '001';
                        }
                }
                $data['seriesId'] = $voucherSeriesQuery->series_id;
                $data['newVoucherNumber'] = $voucherNumber;
                $data['ledgers'] = $this->db->select('id, name, group_head')->where($where)->order_by('id', 'DESC')->get('parties')->result();
                $data['page'] = 'accounts/general/create';
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function isUniqeGeneralVoucherNumber()
        {
                $voucherNumber = $this->input->post('voucherNumber');
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['type' => 'General', 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1, 'voucherNumber' => $voucherNumber];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                }
            
                if ($this->db->select('id')->where($where)->get('account_ledger')->num_rows() === 0) {
                        return true;
                } else {
                        $this->form_validation->set_message('isUniqeGeneralVoucherNumber', 'The voucher number must be unique.');
                        return false;
                }
        }
        
        public function generalSave() 
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'General Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required|callback_isUniqeGeneralVoucherNumber');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');
                        
                        if ($this->form_validation->run() == false) {
                                echo json_encode(['status' => false, 'message' => validation_errors()]);
                                return;
                        } else {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                                if ($this->session->userdata('BranchRole')) {
                                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                                }                                
                                
                                $generalDate = $this->input->post('date');
                                $voucherNumber = $this->input->post('voucherNumber');
                                $ledgerAccountIds = $this->input->post('ledgerAccountId');
                                $narration = $this->input->post('narration');
                                $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                $created = date('d-m-Y h:i:s A');
                                $createdBy = get_title('users', ['id' => $userId], 'name');

                                $generalResult = "";
		foreach ($ledgerAccountIds as $key => $ledgerAccount) {
                                        if (!empty($ledgerAccount)) {
                                                $amount = $this->input->post('amount')[$key];
                                                if (!empty($amount) && $amount > 0) {
                                                        $type = $this->input->post('type')[$key];

                                                        $generalData = [
                                                                'date' => $generalDate,
                                                                'voucherNumber' => $voucherNumber,
                                                                'ledgerId' => $ledgerAccount,
                                                                'type' => 'General',
                                                                'narration' => $narration,
                                                                'userId' => $userId,
                                                                'compId' => $compId,
                                                                'compBranchId' => $compBranchId,
                                                                'financialYear' => $financialYear,
                                                                'created' => $created,
                                                                'createdBy' => $createdBy,
                                                        ];

                                                        if ($type == "Dr") {
                                                                $generalData['debit'] = $amount;
                                                                $generalData['credit'] = '0.00';
                                                                $generalData['transactionType'] = 'Dr';
                                                        }
                                                        if ($type == "Cr") {
                                                                $generalData['debit'] = '0.00';
                                                                $generalData['credit'] = $amount;
                                                                $generalData['transactionType'] = 'Cr';
                                                        }

                                                        $generalResult = $this->user_model->saveAry('account_ledger', $generalData);
                                                } else {
                                                        echo json_encode(['status' => false, 'message' => 'The amount must be greater than zero and cannot be blank.']);
                                                        return;
                                                }
                                        } else {
                                                echo json_encode(['status' => false, 'message' => 'Ledger are required.']);
                                                return;
                                        }
                                }
                                
                                if (!empty($generalResult)) {
                                        $seriesUpdateWhere = ['series_id' => $this->input->post('seriesId'), 'user_id' => $userId, 'comp_id' => $compId];
                                        if ($this->session->userdata('BranchRole')) {
                                                $seriesUpdateWhere['CompanyBranchId'] = $compBranchId;
                                        }
                                        
                                        $seriesData = $this->user_model->getRow('series', $seriesUpdateWhere);                                        
                                        if ($seriesData) {
                                                $starterPoint = $seriesData->starter_point + 1;
                                                $this->user_model->updateAry('series', ['starter_point' => $starterPoint], $seriesUpdateWhere);
                                        }
                                        echo json_encode(['status' => true, 'message' => 'Record saved successfully.']);
                                        return;
                                } else {
                                        echo json_encode(['status' => false, 'message' => 'Unable to save the record. Please try again.']);
                                        return;
                                }                                
                        }
                }
        }
        
        public function generalInfo($generalId) 
        {
                if (isset($generalId) && !empty($generalId)) {
                        $where = ['id' => $generalId, 'type' => 'General', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        
                        $generalInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($generalInfoQuery->num_rows()) {
                                $data['generalData'] = $generalInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/general/info', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/general');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'General id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/general');
                }
        }
        
        public function generalPrint($generalId)
        {
                if (isset($generalId) && !empty($generalId)) {
                        $where = ['id' => $generalId, 'type' => 'General', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        $compProfileWhere = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                $compProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                
                                $data['branch'] = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId')]);
                        } else {
                                $data['company'] = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id')]);
                        }
                
                        $generalInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($generalInfoQuery->num_rows()) {
                                $data['compProfile'] = $this->db->select('content, panNumber')->where($compProfileWhere)->get('comp_profile')->row();
                                $data['generalData'] = $generalInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/general/print', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/general');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'General id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/general');
                }
        }
                
        public function generalEdit($generalId) 
        {
                if (isset($generalId) && !empty($generalId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $generalId, 'type' => 'General', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $partyWhere = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $partyWhere['CompanyBranchId'] = $compBranchId;
                        }
                        
                        $generalInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($generalInfoQuery->num_rows()) {
                                $data['generalData'] = $generalInfoQuery->row();
                                $data['parties'] = $this->db->select('id, name, area, contact')->where($partyWhere)->order_by('id', 'DESC')->get('parties')->result();                                
                                $data['page'] = 'accounts/general/edit';
                                return $this->load->view('inventory_sep/inventory', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/general');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'General id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/general');
                }
        }
        
        public function generalUpdate()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'General Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');
                        
                        $voucherNumber = $this->input->post('voucherNumber');
                        $generalId = $this->input->post('generalId');
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $generalWhere = ['voucherNumber' => $voucherNumber, 'type' => 'General', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                        if ($this->session->userdata('BranchRole')) {
                                $generalWhere['compBranchId'] = $compBranchId;
                                $where['CompanyBranchId'] = $compBranchId;
                                $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        }                        
                        
                        if ($this->form_validation->run() == false) {
                                $data['generalData'] = $this->db->where($generalWhere)->get('account_ledger')->row();
                                $data['parties'] = $this->db->select('id, name, area, contact')->where($where)->order_by('id', 'DESC')->get('parties')->result();
                                $data['error'] = validation_errors();
                                $data['page'] = 'accounts/general/edit';
                                return $this->load->view('inventory_sep/inventory', $data);
                        } else {
                                $this->db->where($generalWhere)->delete('account_ledger');
                                
                                $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                $createdBy = get_title('users', ['id' => $userId], 'name');
                                
                                $generalResult = "";
		foreach ($this->input->post('partyId') as $key => $party) {
                                        if (!empty($party)) {
                                                $generalData = [
                                                        'date' => $this->input->post('date'),
                                                        'voucherNumber' => $voucherNumber,
                                                        'ledgerId' => '0',
                                                        'partyId' => $party,
                                                        'type' => 'General',
                                                        'narration' => $this->input->post('narration'),
                                                        'userId' => $userId,
                                                        'compId' => $compId,
                                                        'compBranchId' => $compBranchId,
                                                        'financialYear' => $financialYear,
                                                        'created' => date('d-m-Y h:i:s A'),
                                                        'createdBy' => $createdBy,
                                                ];
                                                
                                                if ($this->input->post('type')[$key] == "Dr") {
                                                        $generalData['debit'] = $this->input->post('amount')[$key];
                                                        $generalData['credit'] = '0.00';
                                                        $generalData['transactionType'] = 'Dr';
                                                }
                                                if ($this->input->post('type')[$key] == "Cr") {
                                                        $generalData['debit'] = '0.00';
                                                        $generalData['credit'] = $this->input->post('amount')[$key];
                                                        $generalData['transactionType'] = 'Cr';
                                                }
                                                
                                                $generalResult = $this->user_model->saveAry('account_ledger', $generalData);
                                        }                                        
                                }
                                
                                if (!empty($generalResult)) {
                                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                                        return redirect(base_url() . 'accountLedger/general');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                                        return redirect(base_url() . 'accountLedger/generalEdit/' . $generalId);
                                }                                
                        }
                }
        }
        
        public function generalDelete($generalId)
        {
                if (isset($generalId) && !empty($generalId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $generalId, 'type' => 'General', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $generalWhere = ['type' => 'General', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $generalWhere['compBranchId'] = $compBranchId;
                        }
                        
                        $voucherNumber = get_title('account_ledger', $where, 'voucherNumber');
                        if ($voucherNumber) {
                                $generalWhere['voucherNumber'] = $voucherNumber;
                        }
                        $generalInfoQuery = $this->db->select('id')->where($generalWhere)->get('account_ledger');
                        if ($generalInfoQuery->num_rows()) {
                                if ($this->db->where($generalWhere)->update('account_ledger', ['isDeleted' => 0])) {
                                        $this->session->set_flashdata(['msg' => 'Record has been deleted.', 'class' => 'success']);
                                        return redirect(base_url() . 'accountLedger/general');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Record has been not deleted.', 'class' => 'danger']);
                                        return redirect(base_url() . 'accountLedger/general');
                                }
                        } else {
                                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/general');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'General id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/general');
                }                
        }
        
        // account contra voucher
        public function contra() 
        {
                if ($this->input->get('from') && $this->input->get('to')) {
                        $from = $this->input->get('from');
                        $to = $this->input->get('to');
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }                
                
                $where = ['date >= ' => $from, 'date <= ' => $to, 'type' => 'Contra', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $contraQuery = $this->db->select('id, date, voucherNumber, ledgerId, narration, transactionType, debit, credit')->where($where)->order_by('id', 'DESC')->get('account_ledger');
                if ($contraQuery->num_rows()) {
                        $data['contraData'] = $contraQuery->result();
                } else {
                        $data['contraData'] = [];
                }
                $data['page'] = 'accounts/contra/index';
                return $this->load->view('inventory_sep/inventory', $data);
        }       
        
        public function contraCreate() 
        {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                $voucherWhere = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                $seriesWhere = ['user_id' => $userId, 'comp_id' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                        $seriesWhere['CompanyBranchId'] = $compBranchId;
                        $voucherWhere['compBranchId'] = $compBranchId;
                }
                
                $voucherSeriesQuery = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where(['seriestitle.series' => 'Contra Voucher', 'series.status' => 2])->where($seriesWhere)->get()->row();
                if (isset($voucherSeriesQuery) && !empty($voucherSeriesQuery)) {
                        $voucherNumber = $this->user_model->getSeris($voucherSeriesQuery);
                } else {
                        $lastRecord = $this->db->select('voucherNumber')->where($voucherWhere)->where('type', 'Contra')->order_by('id', 'DESC')->limit(1)->get('account_ledger')->row('voucherNumber');
                        if ($lastRecord) {
                                $voucherNumber = str_pad($lastRecord + 1, 3, '0', STR_PAD_LEFT);
                        } else {
                                $voucherNumber = '001';
                        }
                }
                $data['seriesId'] = $voucherSeriesQuery->series_id;
                $data['newVoucherNumber'] = $voucherNumber;
                $data['ledgers'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('ladger')->result();
                $data['page'] = 'accounts/contra/create';
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getLedgerFromList()
        {
                if ($this->input->is_ajax_request()) {
                        $ledgerId = $this->input->post('ledgerId');
                        if (isset($ledgerId) && !empty($ledgerId)) {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                                $compBranchId = $this->session->userdata('CompBranchId');

                                $where = ['id !=' => $ledgerId, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                        $where['CompanyBranchId'] = $compBranchId;
                                }
                                
                                $ledgerQuery = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('ladger');
                                if ($ledgerQuery->num_rows()) {
                                        $ledgerDataHtml = "";
                                        foreach ($ledgerQuery->result() as $key => $ledgerInfo) {
                                                $ledgerDataHtml .= "<option '" . set_select('ledgerIdFrom', $ledgerInfo->id) . "' value='" . $ledgerInfo->id . "'>" . $ledgerInfo->name . "</option>";
                                        }                                        
                                        echo $ledgerDataHtml;
                                } else {
                                        echo $ledgerDataHtml;
                                }
                        } else {
                                echo $ledgerDataHtml;
                        }
                }
        }
        
        public function isUniqeContraVoucherNumber()
        {
                $voucherNumber = $this->input->post('voucherNumber');
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['type' => 'Contra', 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1, 'voucherNumber' => $voucherNumber];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                }
            
                if ($this->db->select('id')->where($where)->get('account_ledger')->num_rows() === 0) {
                        return true;
                } else {
                        $this->form_validation->set_message('isUniqeContraVoucherNumber', 'The voucher number must be unique.');
                        return false;
                }
        }
        
        public function contraSave() 
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'Contra Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required|callback_isUniqeContraVoucherNumber');
                        $this->form_validation->set_rules('ledgerIdTo', 'Ledger Name To', 'trim|required');
                        $this->form_validation->set_rules('ledgerIdFrom', 'Ledger Name From', 'trim|required');
                        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
                        $this->form_validation->set_rules('transactionMode', 'Transaction Mode', 'trim|required');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');
                        
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        $voucherWhere = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $seriesWhere = ['user_id' => $userId, 'comp_id' => $compId];
                        $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $compBranchId;
                                $seriesWhere['CompanyBranchId'] = $compBranchId;
                                $voucherWhere['compBranchId'] = $compBranchId;
                                $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        }                        
                        
                        if ($this->form_validation->run() == false) {
                                $voucherSeriesQuery = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where(['seriestitle.series' => 'Contra Voucher', 'series.status' => 2])->where($seriesWhere)->get()->row();
                                if (isset($voucherSeriesQuery) && !empty($voucherSeriesQuery)) {
                                        $voucherNumber = $this->user_model->getSeris($voucherSeriesQuery);
                                } else {
                                        $lastRecord = $this->db->select('voucherNumber')->where($voucherWhere)->where('type', 'Contra')->order_by('id', 'DESC')->limit(1)->get('account_ledger')->row('voucherNumber');
                                        if ($lastRecord) {
                                                $voucherNumber = str_pad($lastRecord + 1, 3, '0', STR_PAD_LEFT);
                                        } else {
                                                $voucherNumber = '001';
                                        }
                                }
                                $data['seriesId'] = $voucherSeriesQuery->series_id;
                                $data['newVoucherNumber'] = $voucherNumber;
                                $data['ledgers'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('ladger')->result();
                                $data['parties'] = $this->db->select('id, name, area, contact')->where($where)->order_by('id', 'DESC')->get('parties')->result();                                
                                $data['error'] = validation_errors();
                                $data['page'] = 'accounts/contra/create';
                                return $this->load->view('inventory_sep/inventory', $data);
                        } else {          
                                $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                $createdBy = get_title('users', ['id' => $userId], 'name');
                                $transactionMode = $this->input->post('transactionMode');
                                
                                $contraCreditData = [
                                        'date' => $this->input->post('date'),
                                        'voucherNumber' => $this->input->post('voucherNumber'),
                                        'ledgerId' => $this->input->post('ledgerIdFrom'),
                                        'partyId' => '0',
                                        'debit' => '0.00',
                                        'credit' => $this->input->post('amount'),
                                        'type' => 'Contra',
                                        'transactionType' => 'Dr',
                                        'narration' => $this->input->post('narration'),                                    
                                        'transactionMode' => $transactionMode,
                                        'userId' => $userId,
                                        'compId' => $compId,
                                        'compBranchId' => $compBranchId,
                                        'financialYear' => $financialYear,
                                        'created' => date('d-m-Y h:i:s A'),
                                        'createdBy' => $createdBy,
                                ];
                                
                                $contraDebitData = [
                                        'date' => $this->input->post('date'),
                                        'voucherNumber' => $this->input->post('voucherNumber'),
                                        'ledgerId' => $this->input->post('ledgerIdTo'),
                                        'partyId' => '0',
                                        'debit' => $this->input->post('amount'),
                                        'credit' => '0.00',
                                        'type' => 'Contra',
                                        'transactionType' => 'Cr',
                                        'narration' => $this->input->post('narration'),
                                        'transactionMode' => $transactionMode,
                                        'userId' => $userId,
                                        'compId' => $compId,
                                        'compBranchId' => $compBranchId,
                                        'financialYear' => $financialYear,
                                        'created' => date('d-m-Y h:i:s A'),
                                        'createdBy' => $createdBy,
                                ];
                                
                                if ($transactionMode == "Cheque") {
                                        $contraCreditData['chequeNumber'] = $this->input->post('chequeNumber');
                                        $contraCreditData['chequeDate'] = $this->input->post('chequeDate');
                                        
                                        $contraDebitData['chequeNumber'] = $this->input->post('chequeNumber');
                                        $contraDebitData['chequeDate'] = $this->input->post('chequeDate');                                        
                                }                                
                                if ($transactionMode == "E-Transfer") {
                                        $contraCreditData['transactionNumber'] = $this->input->post('transactionNumber');
                                        $contraCreditData['transactionDate'] = $this->input->post('transactionDate');
                                        
                                        $contraDebitData['transactionNumber'] = $this->input->post('transactionNumber');
                                        $contraDebitData['transactionDate'] = $this->input->post('transactionDate');                                        
                                }
                                if ($transactionMode != "Cash") {
                                        $contraCreditData['bankName'] = $this->input->post('bankName');
                                        
                                        $contraDebitData['bankName'] = $this->input->post('bankName');
                                }
                                
                                $resultCredit = $this->user_model->saveAry('account_ledger', $contraCreditData);
                                $resultDebit = $this->user_model->saveAry('account_ledger', $contraDebitData);

                                if (!empty($resultCredit) && !empty($resultDebit)) {
                                        $seriesUpdateWhere = ['series_id' => $this->input->post('seriesId'), 'user_id' => $userId, 'comp_id' => $compId];
                                        if ($this->session->userdata('BranchRole')) {
                                                $seriesUpdateWhere['CompanyBranchId'] = $compBranchId;
                                        }
                                        
                                        $seriesData = $this->user_model->getRow('series', $seriesUpdateWhere);                                        
                                        if ($seriesData) {
                                                $starterPoint = $seriesData->starter_point + 1;
                                                $this->user_model->updateAry('series', ['starter_point' => $starterPoint], $seriesUpdateWhere);
                                        }
                                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                                        return redirect(base_url() . 'accountLedger/contra');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                                        return redirect(base_url() . 'accountLedger/contraCreate');
                                }                                
                        }
                }
        }
        
        public function contraInfo($contraId) 
        {
                if (isset($contraId) && !empty($contraId)) {
                        $where = ['id' => $contraId, 'type' => 'Contra', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        
                        $contraInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($contraInfoQuery->num_rows()) {
                                $data['contraData'] = $contraInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/contra/info', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/contra');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Contra id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/contra');
                }
        }
        
        public function contraPrint($contraId)
        {
                if (isset($contraId) && !empty($contraId)) {
                        $where = ['id' => $contraId, 'type' => 'Contra', 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                        $compProfileWhere = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                $compProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                
                                $data['branch'] = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId')]);
                        } else {
                                $data['company'] = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id')]);
                        }
                
                        $contraInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($contraInfoQuery->num_rows()) {
                                $data['compProfile'] = $this->db->select('content, panNumber')->where($compProfileWhere)->get('comp_profile')->row();
                                $data['contraData'] = $contraInfoQuery->row();
                                return $this->load->view('inventory_sep/accounts/contra/print', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/contra');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Contra id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/contra');
                }
        }
                
        public function contraEdit($contraId) 
        {
                if (isset($contraId) && !empty($contraId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $contraId, 'type' => 'Contra', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $ladgerAndPartyWhere = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $ladgerAndPartyWhere['CompanyBranchId'] = $compBranchId;
                        }
                        
                        $contraInfoQuery = $this->db->where($where)->get('account_ledger');
                        if ($contraInfoQuery->num_rows()) {
                                $data['contraData'] = $contraInfoQuery->row();
                                $data['ledgers'] = $this->db->select('id, name')->where($ladgerAndPartyWhere)->order_by('id', 'DESC')->get('ladger')->result();
                                $data['contraLedgerIdFrom'] = get_title('account_ledger', ['voucherNumber' => $contraInfoQuery->row()->voucherNumber, 'transactionType' => 'Dr'], 'ledgerId');
                                $data['page'] = 'accounts/contra/edit';
                                return $this->load->view('inventory_sep/inventory', $data);
                        } else {
                                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/contra');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Contra id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/contra');
                }
        }
        
        public function contraUpdate()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->form_validation->set_rules('date', 'Contra Date', 'trim|required');
                        $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required');
                        $this->form_validation->set_rules('ledgerIdTo', 'Ledger Name To', 'trim|required');
                        $this->form_validation->set_rules('ledgerIdFrom', 'Ledger Name From', 'trim|required');
                        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
                        $this->form_validation->set_rules('transactionMode', 'Transaction Mode', 'trim|required');
                        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');
                        
                        $voucherNumber = $this->input->post('voucherNumber');
                        $contraId = $this->input->post('contraId');
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $contraWhere = ['voucherNumber' => $voucherNumber, 'type' => 'Contra', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $where = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        $compProfileWhere = ['comp_id' => $compId, 'user_id' => $userId];
                        if ($this->session->userdata('BranchRole')) {
                                $contraWhere['compBranchId'] = $compBranchId;
                                $where['CompanyBranchId'] = $compBranchId;
                                $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        }                        
                        
                        $contraLedgerIdFromInfo = $this->db->select('id, ledgerId')->where($contraWhere)->where('transactionType', 'Dr')->get('account_ledger')->row();
                        
                        if ($this->form_validation->run() == false) {
                                $data['contraData'] = $this->db->where($contraWhere)->get('account_ledger')->row();
                                $data['ledgers'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('ladger')->result();
                                $data['contraLedgerIdFrom'] = $contraLedgerIdFromInfo->ledgerId;
                                $data['error'] = validation_errors();
                                $data['page'] = 'accounts/contra/edit';
                                return $this->load->view('inventory_sep/inventory', $data);
                        } else {
                                $updatedBy = get_title('users', ['id' => $userId], 'name');
                                
                                $contraCreditData = [
                                        'date' => $this->input->post('date'),
                                        'ledgerId' => $this->input->post('ledgerIdFrom'),
                                        'credit' => $this->input->post('amount'),
                                        'narration' => $this->input->post('narration'),
                                        'transactionMode' => $this->input->post('transactionMode'),
                                        'updated' => date('d-m-Y h:i:s A'),
                                        'updatedBy' => $updatedBy,
                                ];
                                
                                $contraDebitData = [
                                        'date' => $this->input->post('date'),
                                        'ledgerId' => $this->input->post('ledgerIdTo'),
                                        'debit' => $this->input->post('amount'),
                                        'narration' => $this->input->post('narration'),
                                        'transactionMode' => $this->input->post('transactionMode'),
                                        'updated' => date('d-m-Y h:i:s A'),
                                        'updatedBy' => $updatedBy,
                                ];
                                
                                if ($transactionMode == "Cheque") {
                                        $contraCreditData['chequeNumber'] = $this->input->post('chequeNumber');
                                        $contraCreditData['chequeDate'] = $this->input->post('chequeDate');
                                        
                                        $contraDebitData['chequeNumber'] = $this->input->post('chequeNumber');
                                        $contraDebitData['chequeDate'] = $this->input->post('chequeDate');                                        
                                }                                
                                if ($transactionMode == "E-Transfer") {
                                        $contraCreditData['transactionNumber'] = $this->input->post('transactionNumber');
                                        $contraCreditData['transactionDate'] = $this->input->post('transactionDate');
                                        
                                        $contraDebitData['transactionNumber'] = $this->input->post('transactionNumber');
                                        $contraDebitData['transactionDate'] = $this->input->post('transactionDate');                                        
                                }
                                if ($transactionMode != "Cash") {
                                        $contraCreditData['bankName'] = $this->input->post('bankName');
                                        
                                        $contraDebitData['bankName'] = $this->input->post('bankName');
                                }
                                
                                $resultCredit = $this->db->where('id', $contraLedgerIdFromInfo->id)->update('account_ledger', $contraCreditData);
                                $resultDebit = $this->db->where('id', $contraId)->update('account_ledger', $contraDebitData);
                                
                                if (!empty($resultCredit) && !empty($resultDebit)) {
                                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                                        return redirect(base_url() . 'accountLedger/contra');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                                        return redirect(base_url() . 'accountLedger/contraEdit/' . $contraId);
                                }                                
                        }
                }
        }
        
        public function contraDelete($contraId)
        {
                if (isset($contraId) && !empty($contraId)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['id' => $contraId, 'type' => 'Contra', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        $contraWhere = ['type' => 'Contra', 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['compBranchId'] = $compBranchId;
                                $contraWhere['compBranchId'] = $compBranchId;
                        }
                        
                        $voucherNumber = get_title('account_ledger', $where, 'voucherNumber');
                        if ($voucherNumber) {
                                $contraWhere['voucherNumber'] = $voucherNumber;
                        }
                        $contraDeleteQuery = $this->db->select('id')->where($contraWhere)->get('account_ledger');
                        if ($contraDeleteQuery->num_rows()) {
                                if ($this->db->where($contraWhere)->update('account_ledger', ['isDeleted' => 0])) {
                                        $this->session->set_flashdata(['msg' => 'Record has been deleted.', 'class' => 'success']);
                                        return redirect(base_url() . 'accountLedger/contra');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Record has been not deleted.', 'class' => 'danger']);
                                        return redirect(base_url() . 'accountLedger/contra');
                                }
                        } else {
                                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                                return redirect(base_url() . 'accountLedger/contra');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Contra id can not be blank.', 'class' => 'danger']);
                        return redirect(base_url() . 'accountLedger/contra');
                }                
        }
        
        public function ledgerAccountSummary()
        {
                $fromDate = $this->input->get('from');
                $toDate = $this->input->get('to');
                $ledgerId = $this->input->get('ledgerId');
                
                if ($fromDate && $toDate) {
                        $from = $fromDate;
                        $to = $toDate;
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }                
                
                $ledgerWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $where = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($ledgerId != "All" && $ledgerId != "") {
                        $where['partyId'] = $this->input->get('partyId');
                }
                if ($this->session->userdata('BranchRole')) {
                        $ledgerWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $ledgerAccountQuery = $this->db->select('id, date, voucherNumber, ledgerId, narration, transactionType, debit, credit')->where($where)->order_by('id', 'DESC')->get('account_ledger');
                if ($ledgerAccountQuery->num_rows()) {
                        $data['ledgerAccountData'] = $ledgerAccountQuery->result();                        
                } else {
                        $data['ledgerAccountData'] = [];
                }
                $data['ledgers'] = $this->db->select('id, name, group_head')->where($ledgerWhere)->order_by('id', 'DESC')->get('parties')->result();
                $data['page'] = 'accounts/ledgerAccountSummary';
                return $this->load->view('inventory_sep/inventory', $data);                
        }
}