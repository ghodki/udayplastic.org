<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
    
class Receipt extends CI_Controller
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
    
    public function index($invoiceId = null)
    {
        $data['page'] = 'receipt/index';
        $data['InvoiceId'] = $invoiceId;
        $this->load->view('inventory_sep/inventory', $data);
    }
    
    public function receiptByCustomer($customerId)
    {
        $data['page'] = 'receipt/index';
        $data['CustomerId'] = $customerId;
        $this->load->view('inventory_sep/inventory', $data);
    }
    
    public function getReceiptRemark($id) 
    {
        if (!empty($id)) {
            $data['Remark'] = get_title('payment', ['id' => $id], 'remark');
            return $this->load->view('inventory_sep/payment/paymentRemarkData', $data);
        }
    }
    
    public function delete($id)
    {
        if (isset($id) && !empty($id)) {
            if ($this->session->userdata('RoleType')) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $updatedBy = get_title('employees', ['Id' => $EmployeeId], 'Name');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $updatedBy = get_title('users', ['id' => $UserId], 'name');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');

            $Where = ['id' => $id, 'payment_through' => 'Manually', 'user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            $PaymentInfo = $this->user_model->getRow('payment', $Where);
            if ($PaymentInfo) {
                $updated = date('d-m-Y h:i:s A');
                if (is_array(json_decode($PaymentInfo->invoice_id)) && is_array(json_decode($PaymentInfo->invoice_type))) {
                    foreach (json_decode($PaymentInfo->invoice_id) as $key => $InvoiceId) {
                        $AmountInPaidInInvoice = json_decode($PaymentInfo->AmountToPaidInInvoice)[$key];
                        if (json_decode($PaymentInfo->invoice_type)[$key] == "Sale") {
                            $SaleAmount = ($this->db->select('paid_amount')->where(['id' => $InvoiceId, 'type' => 'Sale'])->get('sales')->row('paid_amount') - $AmountInPaidInInvoice);
                            $UpdateSale = [
                                'paid_amount' => $SaleAmount ? $SaleAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('sales', $UpdateSale, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                        }

                        if (json_decode($PaymentInfo->invoice_type)[$key] == "Estimate") {
                            $EstimateAmount = ($this->db->select('paid_amount')->where(['id' => $InvoiceId, 'type' => 'Estimate'])->get('estimates')->row('paid_amount') - $AmountInPaidInInvoice);
                            $UpdateEstimate = [
                                'paid_amount' => $EstimateAmount ? $EstimateAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('estimates', $UpdateEstimate, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                        }
                    }
                } else {
                    if ($PaymentInfo->invoice_type == "Sale") {
                        $SaleAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('sales')->row('paid_amount') - $PaymentInfo->debit);
                        $UpdateSale = [
                            'paid_amount' => $SaleAmount ? $SaleAmount : NULL,
                            'updated' => $updated,
                            'updated_by' => $updatedBy
                        ];
                        $this->user_model->updateAry('sales', $UpdateSale, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                    }

                    if ($PaymentInfo->invoice_type == "Estimate") {
                        $EstimateAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('estimates')->row('paid_amount') - $PaymentInfo->debit);
                        $UpdateEstimate = [
                            'paid_amount' => $EstimateAmount ? $EstimateAmount : NULL,
                            'updated' => $updated,
                            'updated_by' => $updatedBy
                        ];
                        $this->user_model->updateAry('estimates', $UpdateEstimate, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                    }
                }
                
                $this->user_model->deleteRow('payment', $Where);
                $this->session->set_flashdata(['msg' => 'record delete successfully.', 'class' => 'success']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Directly can not access.', 'class' => 'danger']);
            return redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    public function receiptDeleteAjax($Id)
    {
        if (isset($Id) && !empty($Id)) {
            if ($this->session->userdata('RoleType')) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $updatedBy = get_title('employees', ['Id' => $EmployeeId], 'Name');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $updatedBy = get_title('users', ['id' => $UserId], 'name');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');

            $Where = ['id' => $Id, 'payment_through' => 'Manually', 'user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            $PaymentInfo = $this->user_model->getRow('payment', $Where);
            if ($PaymentInfo) {
                $updated = date('d-m-Y h:i:s A');
                if (is_array(json_decode($PaymentInfo->invoice_id)) && is_array(json_decode($PaymentInfo->invoice_type))) {
                    foreach (json_decode($PaymentInfo->invoice_id) as $key => $InvoiceId) {
                        $AmountInPaidInInvoice = json_decode($PaymentInfo->AmountToPaidInInvoice)[$key];
                        if (json_decode($PaymentInfo->invoice_type)[$key] == "Sale") {
                            $SaleAmount = ($this->db->select('paid_amount')->where(['id' => $InvoiceId, 'type' => 'Sale'])->get('sales')->row('paid_amount') - $AmountInPaidInInvoice);
                            $UpdateSale = [
                                'paid_amount' => $SaleAmount ? $SaleAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('sales', $UpdateSale, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                        }

                        if (json_decode($PaymentInfo->invoice_type)[$key] == "Estimate") {
                            $EstimateAmount = ($this->db->select('paid_amount')->where(['id' => $InvoiceId, 'type' => 'Estimate'])->get('estimates')->row('paid_amount') - $AmountInPaidInInvoice);
                            $UpdateEstimate = [
                                'paid_amount' => $EstimateAmount ? $EstimateAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('estimates', $UpdateEstimate, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                        }
                    }
                } else {
                    if ($PaymentInfo->invoice_type == "Sale") {
                        $SaleAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('sales')->row('paid_amount') - $PaymentInfo->debit);
                        $UpdateSale = [
                            'paid_amount' => $SaleAmount ? $SaleAmount : NULL,
                            'updated' => $updated,
                            'updated_by' => $updatedBy
                        ];
                        $this->user_model->updateAry('sales', $UpdateSale, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                    }

                    if ($PaymentInfo->invoice_type == "Estimate") {
                        $EstimateAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('estimates')->row('paid_amount') - $PaymentInfo->debit);
                        $UpdateEstimate = [
                            'paid_amount' => $EstimateAmount ? $EstimateAmount : NULL,
                            'updated' => $updated,
                            'updated_by' => $updatedBy
                        ];
                        $this->user_model->updateAry('estimates', $UpdateEstimate, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                    }
                }
                
                $this->user_model->deleteRow('payment', $Where);
                echo json_encode(['status' => true, 'msg' => 'Receipt deleted successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        } else {
            echo json_encode(['status' => false, 'msg' => 'Receipt id not found.']);
            return;
        }
    }
    
    public function getVoucherNumber ()
    {
        if ($this->input->is_ajax_request()) {
            $VoucherType = $this->input->post('VoucherType');
            if ($VoucherType == null && $VoucherType == "") {
                echo json_encode(['status' => false, 'data' => '']);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                
                $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $SeriesWhere['CompanyBranchId'] = $CompanyBranchId; 
                    $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId; 
                }
                
                $Series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', $VoucherType)->where($SeriesWhere)->get()->row();
                $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;
                $VoucherNumber = isset($Series) && !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber;
                if ($VoucherNumber)  {
                    echo json_encode(['status' => true, 'data' => $VoucherNumber]);
                } else {
                    echo json_encode(['status' => false, 'data' => '']);
                }                    
            }
        } else {
            echo json_encode(['status' => false, 'data' => '']);
        }
    }
    
    public function checkReceiptEntry()
    {
        if ($this->input->is_ajax_request()) {
            $PaymentDate = $this->input->post('PaymentDate');
            $PartyId = $this->input->post('PartyId');
            $receivedPaidAmount = $this->input->post('receivedPaidAmount');
            $Amount = $this->input->post('Amount');
            
            if (!empty($PaymentDate) && !empty($PartyId) && !empty($receivedPaidAmount) && !empty($Amount)) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');

                $Where = ['date' => $PaymentDate, 'party_id' => $PartyId, 'receivedPaidAmount' => $receivedPaidAmount, 'debit' => $Amount, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                }

                if ($this->db->where($Where)->get('payment')->num_rows()) {
                    echo json_encode(['Status' => false, 'Data' => 'NotUnique', 'Title' => 'Duplicate Data Detected', 'Text' => 'The data you are about to submit already exists. Do you want to proceed?']);
                } else {
                    echo json_encode(['Status' => true, 'Data' => 'Unique', 'Title' => '', 'Text' => '']);
                }
            } else {
                echo json_encode(['Status' => false, 'Data' => 'NotUnique', 'Title' => 'Received Amount is Ziro', 'Text' => 'The received paid amount ziro. Do you want to proceed?']);
            }
        }
    }
    
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $InvoiceAmount = $this->input->post('invoice_amount');
            $PaidAmount = $this->input->post('paid_amount');
            
            if ($InvoiceAmount < $PaidAmount) {
                echo json_encode(['Status' => false, 'Message' => 'Paid amount should be equal or less from invoice amount.']);
                return;
            }
            
            $InvoiceRowId = [];
            $InvoiceRowType = [];
            foreach ($this->input->post('invoices') as $key => $SelectedRecordData) {
                $SelectedDataInfo = explode(", ", $SelectedRecordData);
                $InvoiceRowId[] = $SelectedDataInfo[0];
                $InvoiceRowType[] = $SelectedDataInfo[1];
            }
            
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
            
            $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
            $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $SeriesWhere['CompanyBranchId'] = $CompBranchId;
                $UnorderNumberWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->input->post('voucher_type') == "Tax") {
                $SeriesTax = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get()->row();
                $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;
                
                $VoucherNumber = isset($SeriesTax) && !empty($SeriesTax) ? $this->user_model->getSeris($SeriesTax) : $UnorderNumber;
            } elseif ($this->input->post('voucher_type') == "Estimate Voucher") {
                $SeriesEstimate = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Estimate Voucher')->where($SeriesWhere)->get()->row();
                $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;
                
                $VoucherNumber = isset($SeriesEstimate) && !empty($SeriesEstimate) ? $this->user_model->getSeris($SeriesEstimate) : $UnorderNumber;
            }
                
            $PaymentData = [
                'voucher_type' => $this->input->post('voucher_type'),
                'voucher_number' => $VoucherNumber,
                'date' => $this->input->post('date'),
                'account_group' => $this->input->post('account_group'),
                'party_id' => $this->input->post('party_id'),
                'invoice_id' => json_encode($InvoiceRowId),
                'invoice_type' => json_encode($InvoiceRowType),
                'receivedPaidAmount' => $this->input->post('receivedPaidAmount'),
                'cashDiscountAmount' => $this->input->post('cashDiscountAmount'),
                'payment_type' => $this->input->post('payment_type'),
                'paymentmode' => $this->input->post('payment_mode'),
                'ChequeDate' => $this->input->post('ChequeDate'),
                'payment_through' => "Manually",
                'remark' => $this->input->post('remark'),
                'user_id' => $UserId,
                'comp_id' => $CompId,
                'CompanyBranchId' => $CompBranchId,
                'EmployeeId' => $EmployeeId,
                'financial_year' => $this->input->post('financial_year'),
                'created' => date('d-m-Y h:i:s A')
            ];
            if (in_array("Sale", $InvoiceRowType) || in_array("Estimate", $InvoiceRowType)) {
                $PaymentData['debit'] = $this->input->post('paid_amount');
                $PaymentData['transaction_type'] = 'DR';
            }
                            
            $SavePayment = $this->user_model->saveAry('payment', $PaymentData);
            if ($SavePayment) {
                if ($this->input->post('voucher_type') == "Tax") {
                    $GetSeriesWhere = ['series_id' => $SeriesTax->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $GetSeriesWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    $GetSeries = $this->user_model->getRow('series', $GetSeriesWhere);
                    if ($GetSeries) {
                        if ($GetSeries->starter_point == 19) {
                            $seriesNumber = 110;
                        } elseif ($GetSeries->starter_point == 199) {
                            $seriesNumber = 1100;
                        } elseif ($GetSeries->starter_point == 1999) {
                            $seriesNumber = 11000;
                        } elseif ($GetSeries->starter_point == 19999) {
                            $seriesNumber = 110000;
                        } elseif ($GetSeries->starter_point == 199999) {
                            $seriesNumber = 1100000;
                        } else {
                            $seriesNumber = $GetSeries->starter_point + 1;
                        }
                        $seriesNumberData['starter_point'] = $seriesNumber;
                        $this->user_model->updateAry('series', $seriesNumberData, $GetSeriesWhere);
                    }
                } elseif ($this->input->post('voucher_type') == "Estimate Voucher") {
                    $GetSeriesWhere = ['series_id' => $SeriesEstimate->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $GetSeriesWhere['CompanyBranchId'] = $CompBranchId; 
                    }          
                    $GetSeries = $this->user_model->getRow('series', $GetSeriesWhere);
                    if ($GetSeries) {
                        if ($GetSeries->starter_point == 19) {
                            $seriesNumber = 110;
                        } elseif ($GetSeries->starter_point == 199) {
                            $seriesNumber = 1100;
                        } elseif ($GetSeries->starter_point == 1999) {
                            $seriesNumber = 11000;
                        } elseif ($GetSeries->starter_point == 19999) {
                            $seriesNumber = 110000;
                        } elseif ($GetSeries->starter_point == 199999) {
                            $seriesNumber = 1100000;
                        } else {
                            $seriesNumber = $GetSeries->starter_point + 1;
                        }
                        $seriesNumberData['starter_point'] = $seriesNumber;
                        $this->user_model->updateAry('series', $seriesNumberData, $GetSeriesWhere);
                    }
                }                    
                
                $AmountToPaidInInvoice = [];
                foreach ($this->input->post('invoices') as $key => $SelectedRecord) {
                    $SelectedData = explode(", ", $SelectedRecord);
                    $RowId = $SelectedData[0];
                    $RowType = $SelectedData[1];

                    $where = ['id' => $RowId, 'type' => $RowType, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];

                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $CompBranchId; 
                    }

                    $extra_where = ['paid_amount' => NULL];
                    $or_where = ('total != paid_amount');

                    //sale invoices
                    if ($RowType == "Sale") {
                        $SaleInfo = $this->db->select('total, paid_amount')->where($where)->group_start()->where($extra_where)->or_where($or_where)->group_end()->get('sales')->row();
                        $OldPaidAmount = $SaleInfo->paid_amount;

                        $remainingAmount = $SaleInfo->total - $OldPaidAmount;

                        if ($PaidAmount >= $remainingAmount) {
                            // If the paid amount is enough to cover the remaining amount for this invoice
                            $PaidAmount -= $remainingAmount; // Deduct the paid amount
                            $SaleUpdates = [
                                'paid_amount' => $SaleInfo->total, // Mark as fully paid
                                'updated' => date('d-m-Y h:i:s A'),
                                'updated_by' => $UpdatedBy
                            ];
                            $AmountToPaidInInvoice[] = '"' . $remainingAmount . '"';
                        } else {
                            // If the paid amount is not enough to cover the total remaining amount for this invoice
                            $SaleUpdates = [
                                'paid_amount' => $OldPaidAmount + $PaidAmount, // Update paid amount
                                'updated' => date('d-m-Y h:i:s A'),
                                'updated_by' => $UpdatedBy
                            ];
                            $AmountToPaidInInvoice[] = '"' . $PaidAmount . '"';
                            $PaidAmount = 0; // Zero out the remaining paid amount
                        }

                        $SaleWhere = ['id' => $RowId, 'type' => $RowType];
                        $this->user_model->updateAry('sales', $SaleUpdates, $SaleWhere);
                        // Break the loop if there's no remaining paid amount
                        if ($PaidAmount <= 0) {
                            break;
                        }
                    }

                    //estimate invoices
                    if ($RowType == "Estimate") {
                        $EstimateInfo = $this->db->select('total, paid_amount')->where($where)->group_start()->where($extra_where)->or_where($or_where)->group_end()->get('estimates')->row();
                        $OldPaidAmount = $EstimateInfo->paid_amount;

                        $remainingAmount = $EstimateInfo->total - $OldPaidAmount;

                        if ($PaidAmount >= $remainingAmount) {
                            // If the paid amount is enough to cover the remaining amount for this invoice
                            $PaidAmount -= $remainingAmount; // Deduct the paid amount
                            $EstimateUpdates = [
                                'paid_amount' => $EstimateInfo->total, // Mark as fully paid
                                'updated' => date('d-m-Y h:i:s A'),
                                'updated_by' => $UpdatedBy
                            ];
                            $AmountToPaidInInvoice[] = '"' . $remainingAmount . '"';
                        } else {
                            // If the paid amount is not enough to cover the total remaining amount for this invoice
                            $EstimateUpdates = [
                                'paid_amount' => $OldPaidAmount + $PaidAmount, // Update paid amount
                                'updated' => date('d-m-Y h:i:s A'),
                                'updated_by' => $UpdatedBy
                            ];
                            $AmountToPaidInInvoice[] = '"' . $PaidAmount . '"';
                            $PaidAmount = 0; // Zero out the remaining paid amount
                        }

                        $EstimateWhere = ['id' => $RowId, 'type' => $RowType];
                        $this->user_model->updateAry('estimates', $EstimateUpdates, $EstimateWhere);
                        // Break the loop if there's no remaining paid amount
                        if ($PaidAmount <= 0) {
                            break;
                        }
                    }
                }                    

                $AmountToPaidInInvoiceData = json_encode($AmountToPaidInInvoice);
                $PaidAmountDataInInvoice = str_replace('\\"', '', $AmountToPaidInInvoiceData);
                $this->user_model->updateAry('payment', ['AmountToPaidInInvoice' => $PaidAmountDataInInvoice], ['id' => $SavePayment]);
                
                $profile_where = ['user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $profile_where['CompanyBranchId'] = $CompBranchId; 
                }
                if (get_title('comp_profile', $profile_where, 'sms_status') == "Enable") {
                    $pmobile = get_title('parties', ['id' => $this->input->post('party_id')], 'contact');
                    $pname = get_title('parties', ['id' => $this->input->post('party_id')], 'name');
                    // sms_for_payment($pmobile, $pname, $this->input->post('amount'), $balance);
                }
                echo json_encode(['Status' => true, 'Message' => 'Receipt saved successfully.']);
                return;
            } else {
                echo json_encode(['Status' => false, 'Message' => 'Somethings went wrong, try again.']);
                return;
            }
        } else {
            echo json_encode(['Status' => false, 'Message' => 'Directly entry not allowed.']);
            return;
        }
    }
    
    public function getPartyBalance($id = null)
    {
        if (isset($id) && !empty($id)) {
            if ($this->input->post('estimate') == "") {
                $partyBalance = getPartyBalanceReceipt($id);
            } elseif ($this->input->post('estimate') == true) {
                $partyBalance = getPartyBalanceWithEstimateReceipt($id);
            }
            $startDate = get_title('parties', ['id' => $id], 'added_at');
            $fromDate = date('Y-m-d', strtotime($startDate));
            $toDate = date('Y-m-d');
            echo json_encode(['status' => true, 'partyBalance' => $partyBalance, 'From' => $fromDate, 'To' => $toDate]);
        } else {
            echo json_encode(['status' => false, 'partyBalance' => 0, 'From' => "", 'To' => ""]);
        }
    }
    
    public function getPandingInvoices()
    {
        if ($this->input->is_ajax_request()) {
            $AccountGroup = $this->input->post('account_group');
            $party_id = $this->input->post('party_id');
            if ($AccountGroup == "" && $AccountGroup == null && $party_id == "" && $party_id == null) {
                echo "<option value=''>Enter required fields</option>";
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['customerId' => $party_id, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $CompBranchId; 
                }
                
                if (get_title('accounting_group', ['id' => $AccountGroup], 'name') == "Sundry Debitors") {
                    if ($this->input->post('estimate') == "") {
                        $extra_where = ['paid_amount' => NULL];
                        $or_where = 'total != paid_amount';
                        
                        $results = $this->user_model->getSelectedResult('sales', 'id, invoiceNumber, type', $where, $extra_where, $or_where);
                    } elseif ($this->input->post('estimate') == true) {
                        $or_where = 'total != paid_amount';
                        
                        $SaleQuery = $this->db->select('id, invoiceNumber, type')->where($where)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('sales');
                        $EstimateSaleQuery = $this->db->select('id, invoiceNumber, type')->where($where)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('estimates');
                        $results = $this->db->query($SaleQuery . " UNION " . $EstimateSaleQuery . " ORDER BY id ASC")->result();
                    }
                }
                
                if ($results) {
                    foreach ($results as $key => $invoicedata) {
                        echo "<option value='" . $invoicedata->id . ", " . $invoicedata->type . set_select('invoices', $invoicedata->id) . "'>" . $invoicedata->invoiceNumber . ", " . $invoicedata->type . "</option>";
                    }
                } else {
                    echo "<option value=''>No record found</option>";
                }
            }
        }
    }        
    
    public function getReceiptStatement()
    {
        if ($this->input->is_ajax_request()) {
            $AccountGroup = $this->input->post('account_group');
            $PartyId = $this->input->post('party_id');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');
            $estimate = $this->input->post('estimate');
            if ($AccountGroup == "" && $AccountGroup == null && $PartyId == "" && $PartyId == null) {
                echo '<span class="text-danger text-center">Enter required fields.</span>';
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $Where = ['party_id' => $PartyId, 'account_group' => $AccountGroup, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId; 
                }
                if ($fromDate && $toDate) {
                    $Where['date >= '] = $fromDate; 
                    $Where['date <= '] = $toDate; 
                }
                
                $Payments = $this->db->select('id, voucher_number, date, invoice_id, invoice_type, credit, debit, cashDiscountAmount, transaction_type, payment_type, paymentmode, ChequeDate, payment_through')
                    ->where($Where)
                    ->order_by('id', 'ASC')
                    ->get('payment')
                    ->result();

                if ($Payments) {
                    $table =   '<table class="table table-bordered" id="allPayments">'
                            .  '<thead>'
                            .  '<tr>'
                            .  '<th>Sr.</th>'
                            .  '<th>Date</th>'
                            .  '<th>Type</th>'
                            .  '<th>Invoice/Voucher</th>'
                            .  '<th>Mode</th>'
                            .  '<th>Credit</th>'
                            .  '<th>Debit</th>'
                            .  '<th>Discount</th>'
                            .  '<th>Balance</th>'
                            .  '<th>Payment</th>'
                            .  '<th>Days</th>'
                            .  '<th></th>'
                            .  '</tr>'
                            .  '</thead>'
                            .  '</tbody>';
                            $Sr = 0;
                            $totalCredit = 0;
                            $totalDebit = 0;
                            $totalDiscount = 0;
                            $totalBalance = 0;
                            foreach ($Payments as $key => $Data) { 
                                $Sr++;
                                $type = $Data->payment_type == "" ? $Data->invoice_type : $Data->payment_type;
                                if ((is_array(json_decode($Data->invoice_type))) || ($Data->invoice_type == "Sale" && $Data->payment_type == "Receipt") || ($Data->invoice_type == "Estimate" && $Data->payment_type == "Receipt")) {
                                    $number = $Data->voucher_number;
                                } else {
                                    if ($Data->invoice_type == "Sale") {
                                        if ($Data->payment_type == NULL) { 
                                            $number = "<a href='" . base_url() . "sale/viewsale/" . $Data->invoice_id . "' title='View Invoice'>"
                                                    . get_title('sales', ['id' => $Data->invoice_id], 'invoiceNumber')
                                                    . "</a>";
                                        } else { 
                                            $number = "";
                                        }
                                    } elseif ($Data->invoice_type == "Estimate") {
                                        if ($Data->payment_type == NULL) {
                                            $number = "<a href='" . base_url() . "estimate/viewestimate/" . $Data->invoice_id . "' title='View Invoice'>"
                                                    . get_title('estimates', ['id' => $Data->invoice_id], 'invoiceNumber')
                                                    . "</a>";
                                        } else { 
                                            $number = "";
                                        }
                                    }
                                }
                                if (get_title('payment_mode', ['id' => $Data->paymentmode], 'payment_method') == "Cheque") {
                                    $Mode = get_title('payment_mode', ['id' => $Data->paymentmode], 'payment_method');
                                    $Mode .= $Data->ChequeDate ? " <small>(" . date('d-m-Y', strtotime($Data->ChequeDate)) . ")</small>" : "";
                                } else {
                                    $Mode = get_title('payment_mode', ['id' => $Data->paymentmode], 'payment_method');
                                }
                                $credit = !empty($Data->credit) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($Data->credit) : "-";
                                $debit = !empty($Data->debit) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($Data->debit) : "-";
                                $totalCredit += (float) $Data->credit;
                                $totalDebit += (float) $Data->debit;
                                $totalDiscount += (float) ($Data->cashDiscountAmount ?? 0);
                                $cashDiscountAmount = !empty($Data->cashDiscountAmount) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($Data->cashDiscountAmount) : "";

                                $invoiceTypes = json_decode($Data->invoice_type ?? '');
                                $invoiceTypes = is_array($invoiceTypes) ? $invoiceTypes : [];
                                if ($Data->invoice_type == "Sale" || $Data->invoice_type == "Estimate" || in_array('Sale', $invoiceTypes) || in_array('Estimate', $invoiceTypes)) {
                                    $totalBalance = indianMoneyFormat(($totalCredit - $totalDebit));
                                }
                                
                                if (!empty($Data->credit) && $Data->transaction_type == "CR" && $Data->payment_type == NULL && $Data->invoice_type == "Sale") {
                                    $sql = "SELECT paymentmode FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'DR'";
                                    $query = $this->db->query($sql);
                                    $PaymentModeId = $query->num_rows() ? $query->row()->paymentmode : $this->db->select('paymentmode')->where(['invoice_id' => $Data->invoice_id, 'invoice_type' => 'Sale', 'transaction_type' => 'DR'])->get('payment')->row('paymentmode');
                                    if (get_title('sales', ['id' => $Data->invoice_id, 'type' => 'Sale'], 'paid_amount') == get_title('sales', ['id' => $Data->invoice_id, 'type' => 'Sale'], 'total')) {
                                        if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                            $sql1 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'DR'";
                                            $query1 = $this->db->query($sql1);
                                            $ChequeStatus = $query1->num_rows() ? $query1->row()->ChequeStatus : $this->db->select('ChequeStatus')->where(['invoice_id' => $Data->invoice_id, 'invoice_type' => 'Sale', 'transaction_type' => 'DR'])->get('payment')->row('ChequeStatus');
                                            if ($ChequeStatus == "0") {
                                                $SalePaymentStatus = "<a href='javascript:void(0);' class='text-maroon' onclick='onChequeInfo($Data->invoice_id);' title='Cheque Info'><i class='fa fa-exclamation'></i> UnPaid/PDC</a>";
                                            } elseif ($ChequeStatus == "1") {
                                                $SalePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                            }
                                        } else {
                                            $SalePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                        }
                                    } elseif (get_title('sales', ['id' => $Data->invoice_id, 'type' => 'Sale'], 'paid_amount') == NULL || get_title('sales', ['id' => $Data->invoice_id, 'type' => 'Sale'], 'paid_amount') == 0) { 
                                        $SalePaymentStatus = "<a href='" . base_url() . "receipt/index/" .  get_title('sales', ['id' => $Data->invoice_id, 'type' => 'Sale'], 'id') . "' class='text-danger'><i class='fa fa-exclamation-triangle'></i> Unpaid</a>";
                                    } else { 
                                        if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                            $sql2 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'DR'";
                                            $query2 = $this->db->query($sql2);
                                            $ChequeStatus2 = $query2->num_rows() ? $query2->row()->ChequeStatus : $this->db->select('ChequeStatus')->where(['invoice_id' => $Data->invoice_id, 'invoice_type' => 'Sale', 'transaction_type' => 'DR'])->get('payment')->row('ChequeStatus');
                                            if ($ChequeStatus2 == "0") {
                                                $SalePaymentStatus = "<a href='javascript:void(0);' class='text-maroon' onclick='onChequeInfo($Data->invoice_id);' title='Cheque Info'><i class='fa fa-exclamation'></i> UnPaid/PDC</a>";
                                            } elseif ($ChequeStatus2 == "1") {
                                                $SalePaymentStatus = "<a href='" . base_url() . "receipt/index/" .  get_title('sales', ['id' => $Data->invoice_id, 'type' => 'Sale'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                            }
                                        } else {
                                            $SalePaymentStatus = "<a href='" . base_url() . "receipt/index/" .  get_title('sales', ['id' => $Data->invoice_id, 'type' => 'Sale'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                        }
                                    }
                                } else {
                                    $SalePaymentStatus = "";
                                }
                                
                                if (!empty($Data->credit) && $Data->transaction_type == "CR" && $Data->payment_type == NULL && $Data->invoice_type == "Estimate") {
                                    $sql = "SELECT paymentmode FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'DR'";
                                    $query = $this->db->query($sql);                                        
                                    $PaymentModeId = $query->num_rows() ? $query->row()->paymentmode : $this->db->select('paymentmode')->where(['invoice_id' => $Data->invoice_id, 'invoice_type' => 'Estimate', 'transaction_type' => 'DR'])->get('payment')->row('paymentmode');
                                    if (get_title('estimates', ['id' => $Data->invoice_id, 'type' => 'Estimate'], 'paid_amount') == get_title('estimates', ['id' => $Data->invoice_id, 'type' => 'Estimate'], 'total')) { 
                                        if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                            $sql1 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'DR'";
                                            $query1 = $this->db->query($sql1);
                                            $ChequeStatus = $query1->num_rows() ? $query1->row()->ChequeStatus : $this->db->select('ChequeStatus')->where(['invoice_id' => $Data->invoice_id, 'invoice_type' => 'Estimate', 'transaction_type' => 'DR'])->get('payment')->row('ChequeStatus');
                                            if ($ChequeStatus == "0") {
                                                $EstimatePaymentStatus = "<a href='javascript:void(0);' class='text-maroon' onclick='onChequeInfo($Data->invoice_id);' title='Cheque Info'><i class='fa fa-exclamation'></i> UnPaid/PDC</a>";
                                            } elseif ($ChequeStatus == "1") {
                                                $EstimatePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                            }                                                 
                                        } else {
                                            $EstimatePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                        }
                                    } elseif (get_title('estimates', ['id' => $Data->invoice_id, 'type' => 'Estimate'], 'paid_amount') == NULL || get_title('estimates', ['id' => $Data->invoice_id, 'type' => 'Estimate'], 'paid_amount') == 0) { 
                                        $EstimatePaymentStatus = "<a href='" . base_url() . "receipt/index/" .  get_title('estimates', ['id' => $Data->invoice_id, 'type' => 'Estimate'], 'id') . "' class='text-danger'><i class='fa fa-exclamation-triangle'></i> Unpaid</a>";
                                    } else { 
                                        if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                            $sql2 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'DR'";
                                            $query2 = $this->db->query($sql2);
                                            $ChequeStatus2 = $query2->num_rows() ? $query2->row()->ChequeStatus : $this->db->select('ChequeStatus')->where(['invoice_id' => $Data->invoice_id, 'invoice_type' => 'Estimate', 'transaction_type' => 'DR'])->get('payment')->row('ChequeStatus');
                                            if ($ChequeStatus2 == "0") {
                                                $EstimatePaymentStatus = "<a href='javascript:void(0);' class='text-maroon' onclick='onChequeInfo($Data->invoice_id);' title='Cheque Info'><i class='fa fa-exclamation'></i> UnPaid/PDC</a>";
                                            } elseif ($ChequeStatus2 == "1") { 
                                                $EstimatePaymentStatus = "<a href='" . base_url() . "receipt/index/" .  get_title('estimates', ['id' => $Data->invoice_id, 'type' => 'Estimate'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                            }
                                        } else {
                                            $EstimatePaymentStatus = "<a href='" . base_url() . "receipt/index/" .  get_title('estimates', ['id' => $Data->invoice_id, 'type' => 'Estimate'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                        }
                                    }
                                } else {
                                    $EstimatePaymentStatus = "";
                                }
                                
                                if ($Data->invoice_type == "Sale") {
                                    $PaymentStatus = $SalePaymentStatus;
                                } elseif ($Data->invoice_type == "Estimate") {
                                    $PaymentStatus = $EstimatePaymentStatus;
                                } else {
                                    $PaymentStatus = "";
                                }
                                $days = abs((strtotime(date('d-m-Y')) - strtotime($Data->date)) / (60 * 60 * 24));
                                if ($Data->payment_through == "Manually") {
                                    if ($this->session->userdata('RoleType')) {
                                        if (json_decode(get_title('employee_permission', ['EmployeeId' => $EmployeeId], 'Payments'))->Delete == "Delete") {
                                            $action = "<a href='javascript:void(0);' class='btn btn-primary btn-xs' title='Remark' onclick='onGetPaymentRemarkHandler($Data->id);'>"
                                                    . "<i class='fa fa-info-circle'></i>"
                                                    . "</a>"
                                                    . "&nbsp;"
                                                    . "<a href='javascript:void(0);' class='btn btn-danger btn-xs' title='Delete' onclick='onPaymentDeleteHandler($Data->id);'>"
                                                    . "<i class='fa fa-trash-alt'></i>"
                                                    . "</a>";
                                        } else {
                                            $action = "<a href='javascript:void(0);' class='btn btn-primary btn-xs' title='Remark' onclick='onGetPaymentRemarkHandler($Data->id);'>"
                                                    . "<i class='fa fa-info-circle'></i>"
                                                    . "</a>";
                                        } 
                                    } else {
                                        $action = "<a href='javascript:void(0);' class='btn btn-primary btn-xs' title='Remark' onclick='onGetPaymentRemarkHandler($Data->id);'>"
                                                . "<i class='fa fa-info-circle'></i>"
                                                . "</a>"
                                                . "&nbsp;"
                                                . "<a href='javascript:void(0);' class='btn btn-danger btn-xs' title='Delete' onclick='onPaymentDeleteHandler($Data->id);'>"
                                                . "<i class='fa fa-trash-alt'></i>"
                                                . "</a>";
                                    } 
                                } else {
                                    $action = "";
                                }
                                
                    $table .=  '<tr>'
                            .  '<td>' . $Sr . '</td>'
                            .  '<td>' . date('d-m-Y', strtotime($Data->date)) . '</td>'
                            .  '<td>' . $type . '</td>'
                            .  '<td>' . $number . '</td>'
                            .  '<td>' . $Mode . '</td>'
                            .  '<td>' . $credit . '</td>'
                            .  '<td>' . $debit . '</td>'
                            .  '<td>' . $cashDiscountAmount . '</td>'
                            .  '<td><i class="fa fa-inr"></i> ' . $totalBalance . '</td>'
                            .  '<td>' . $PaymentStatus . '</td>'
                            .  '<td>' . $days . '</td>'
                            .  '<td>' . $action . '</td>'
                            .  '</tr>';
                            }
                    $table  .= '</tbody>'
                            .  '<tfoot>'
                            .  '<tr>'
                            .  '<th colspan="5" style="text-align: right;">Total</th>'
                            .  '<th><i class="fa fa-inr"></i> ' . indianMoneyFormat($totalCredit) . '</th>'
                            .  '<th><i class="fa fa-inr"></i> ' . indianMoneyFormat($totalDebit) . '</th>'
                            .  '<th><i class="fa fa-inr"></i> ' . indianMoneyFormat($totalDiscount) . '</th>'
                            .  '<th><i class="fa fa-inr"></i> ' . $totalBalance . '</th>'
                            .  '<th></th>'
                            .  '<th></th>'
                            .  '<th></th>'
                            .  '</tr>'
                            .  '</tfoot>'
                            .  '</table>';
                    echo $table;
                } else {
                    echo '<span class="text-danger text-center">No Record found.</span>';
                }
            }
        }
    }        
    
    public function getInvoiceTotal()
    {
        if ($this->input->is_ajax_request()) {
            $AccountGroup = $this->input->post('account_group');
            $invoice_data = $this->input->post('invoice_data');
            $party_id = $this->input->post('party_id');
            if ($AccountGroup == "" && empty($invoice_data) && $party_id == "" && $party_id == null) {
                echo 0;
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['customerId' => $party_id, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $CompBranchId; 
                }
                
                if (get_title('accounting_group', ['id' => $AccountGroup], 'name') == "Sundry Debitors") {
                    if ($this->input->post('estimate') == "") {
                        $InvoiceId = [];
                        $InvoiceType = [];
                        foreach ($this->input->post('invoice_data') as $key => $InvoiceData) {
                            $InvoiceRecordData = explode(", ", $InvoiceData);
                            $InvoiceId[] = $InvoiceRecordData[0];
                            $InvoiceType[] = $InvoiceRecordData[1];
                        }
                        $where_id = $InvoiceId;
                        $where_type = $InvoiceType;
                        $extra_where = ['paid_amount' => NULL];
                        $or_where = ('total != paid_amount');
                        
                        $results = $this->user_model->getSelectedInvoice('sales', 'SUM(total) AS total, SUM(paid_amount) AS paid_amount', $where, $where_id, $where_type, $extra_where, $or_where);
                    } elseif ($this->input->post('estimate') == true) {
                        $InvoiceId = [];
                        $InvoiceType = [];
                        foreach ($this->input->post('invoice_data') as $key => $InvoiceData) {
                            $InvoiceRecordData = explode(", ", $InvoiceData);
                            $InvoiceId[] = $InvoiceRecordData[0];
                            $InvoiceType[] = $InvoiceRecordData[1];
                        }
                        $where_id = $InvoiceId;
                        $where_type = $InvoiceType;
                        $or_where = ('total != paid_amount');
                        
                        $SaleQuery = $this->db->select('SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($where)->where_in('id', $where_id)->where_in('type', $where_type)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('sales');
                        $EstimateSaleQuery = $this->db->select('SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($where)->where_in('id', $where_id)->where_in('type', $where_type)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('estimates');
                        $results = $this->db->query($SaleQuery . " UNION " . $EstimateSaleQuery)->result();
                    }
                }
                
                if ($results) {
                    $TotalInvoice = 0;
                    foreach ($results as $key => $InvoiceVal) {
                        $TotalInvoice += ($InvoiceVal->total - $InvoiceVal->paid_amount);
                    }
                    echo $TotalInvoice;
                } else {
                    echo 0;
                }
            }
        }
    }        
    
    public function printStatement()
    {            
        $this->load->view('inventory_sep/payment/print-statement');
    }
    
    public function getReceiptInfomationByInvoiceId() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $InvoiceId = $this->input->post('InvoiceId');
            if (isset($InvoiceId) && !empty($InvoiceId)) {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanId = json_decode(get_title('employees', ['Id' => $EmployeeId], ['WarehouseSalesman']));
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');        
                
                $CommonWhere = ['id' => $InvoiceId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $CommonWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->db->where('type', 'Sale')->where($CommonWhere)->get('sales')->num_rows()) {
                    $this->db->select('id, type, customerId');
                    $this->db->where('type', 'Sale');
                    $this->db->where($CommonWhere);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('SalesmanId', $SalesmanId);
                    }
                    $Result = $this->db->get('sales')->row();
                    $partyInfo = $this->db->select('id, group_head')->where('id', $Result->customerId)->get('parties')->row();
                }
                if ($this->db->where('type', 'Estimate')->where($CommonWhere)->get('estimates')->num_rows()) {
                    $this->db->select('id, type, customerId');
                    $this->db->where('type', 'Estimate');
                    $this->db->where($CommonWhere);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('SalesmanId', $SalesmanId);
                    }
                    $Result = $this->db->get('estimates')->row();
                    $partyInfo = $this->db->select('id, group_head')->where('id', $Result->customerId)->get('parties')->row();
                }
                   
                if (isset($Result) && !empty($Result)) {
                    echo json_encode(['status' => true, 'msg' => 'Success.', 'InvoiceData' => $Result, 'partyInfo' => $partyInfo]);
                } else {
                    echo json_encode(['status' => false, 'msg' => 'No record found.', 'InvoiceData' => '', 'partyInfo' => '']);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Invoice id missing.', 'InvoiceData' => '', 'partyInfo' => '']);
            }
        }
    }
    
    public function getReceiptInfomationByCustomerId() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $CustomerId = $this->input->post('CustomerId');
            if (isset($CustomerId) && !empty($CustomerId)) {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanId = json_decode(get_title('employees', ['Id' => $EmployeeId], ['WarehouseSalesman']));
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');        
                
                $CommonWhere = ['customerId' => $CustomerId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $CommonWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->db->where('type', 'Sale')->where($CommonWhere)->get('sales')->num_rows()) {
                    $this->db->select('id, type, customerId');
                    $this->db->where('type', 'Sale');
                    $this->db->where($CommonWhere);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('SalesmanId', $SalesmanId);
                    }
                    $Result = $this->db->get('sales')->row();
                    $partyInfo = $this->db->select('id, group_head')->where('id', $Result->customerId)->get('parties')->row();
                }
                if ($this->db->where('type', 'Estimate')->where($CommonWhere)->get('estimates')->num_rows()) {
                    $this->db->select('id, type, customerId');
                    $this->db->where('type', 'Estimate');
                    $this->db->where($CommonWhere);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('SalesmanId', $SalesmanId);
                    }
                    $Result = $this->db->get('estimates')->row();
                    $partyInfo = $this->db->select('id, group_head')->where('id', $Result->customerId)->get('parties')->row();
                }
                   
                if (isset($Result) && !empty($Result)) {
                    echo json_encode(['status' => true, 'msg' => 'Success.', 'InvoiceData' => $Result, 'partyInfo' => $partyInfo]);
                } else {
                    echo json_encode(['status' => false, 'msg' => 'No record found.', 'InvoiceData' => '', 'partyInfo' => '']);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Invoice id missing.', 'InvoiceData' => '', 'partyInfo' => '']);
            }
        }
    }        
    
    public function chequeInfo($invoiceId) 
    {
        if (empty($invoiceId)) {
            $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
            return redirect($_SERVER['HTTP_REFERER']);
        } else {
            $sql = "SELECT id FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$invoiceId\"]') AND transaction_type = 'DR'";
            $query = $this->db->query($sql);
            if ($this->db->select('id')->where('id', $invoiceId)->where('type', 'Sale')->get('sales')->num_rows()) {
                $data['saleInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, type, total, SalesmanId')->where('id', $invoiceId)->where('type', 'Sale')->get('sales')->row();
            }
            if ($this->db->select('id')->where('id', $invoiceId)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                $data['saleInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, type, total, SalesmanId')->where('id', $invoiceId)->where('type', 'Estimate')->get('estimates')->row();
            }
            if ($query->num_rows()) {
                $sql1 = "SELECT id, date, paymentmode, ChequeDate, debit, remark FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$invoiceId\"]') AND transaction_type = 'DR'";
                $query1 = $this->db->query($sql1);
                $data['paymentInfo'] = $query1->row();
            } else {
                $data['paymentInfo'] = $this->db->select('id, date, paymentmode, ChequeDate, debit, remark')->where('invoice_id', $invoiceId)->where('transaction_type', 'DR')->get('payment')->row();
            }
            return $this->load->view('inventory_sep/receipt/ChequeInfo', $data);
        }
    }
    
    public function chequeStatusUpdate() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->input->post('ChequeStatus') == 1) {
                $PaymentData = [
                    'date' => $this->input->post('PaymentDate'),
                    'ChequeDate' => $this->input->post('ChequeDate'),
                    'ChequeStatus' => 1,
                    'remark' => $this->input->post('Remark'),
                    'updated' => date('d-m-Y h:i:s A')
                ];
                $Result = $this->user_model->updateAry('payment', $PaymentData, ['id' => $this->input->post('paymentId')]);
            }
            
            if ($this->input->post('ChequeStatus') == 0) {
                $InvoiceData = [
                    'updated' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    'updated_by' => date('d-m-Y h:i:s A')
                ];
                
                if ($this->input->post('invoiceType') == "Sale") {
                    $InvoiceData['paid_amount'] = (get_title('sales', ['id' => $this->input->post('invoiceId')], 'paid_amount') - $this->input->post('Amount'));
                    $this->user_model->updateAry('sales', $InvoiceData, ['id' => $this->input->post('invoiceId'), 'type' => 'Sale']);
                }
                if ($this->input->post('invoiceType') == "Estimate") {
                    $InvoiceData['paid_amount'] = (get_title('estimates', ['id' => $this->input->post('invoiceId')], 'paid_amount') - $this->input->post('Amount'));
                    $this->user_model->updateAry('estimates', $InvoiceData, ['id' => $this->input->post('invoiceId'), 'type' => 'Estimate']);
                }
                $Result = $this->user_model->deleteRow('payment', ['id' => $this->input->post('paymentId')]);
            }
            
            if ($Result) {
                echo json_encode(['status' => true, 'message' => 'Record Updated successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'message' => 'Failed.']);
            }
        }
    }
    
    public function report()
    {
        $compId = $this->session->userdata('comp_id');
        $userId = $this->session->userdata('user_id');
        $compBranchId = $this->session->userdata('CompBranchId');
        $chequeId = get_title('payment_mode', ['payment_method' => 'Cheque'], 'id');
        
        if ($this->input->get('from') && $this->input->get('to')) {
            $from = $this->input->get('from');
            $to = $this->input->get('to');
        } else {
            $from = date('Y-m-01');
            $to = date('Y-m-d');
        }
        
        $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1, 'payment_type' => 'Receipt'];
        if ($this->session->userdata('BranchRole')) {
            $where['CompanyBranchId'] = $compBranchId;
        }
        if (!empty($this->input->get('mode')) && $this->input->get('mode') != "All") {
            $where['paymentmode'] = $this->input->get('mode');
        }
        
        $receiptQuery = $this->db->select('id, date, voucher_number, party_id, debit, paymentmode, remark')
            ->where($where)
            ->group_start()
                ->group_start()
                    ->where('paymentmode', $chequeId)
                    ->where('ChequeDate >=', $from)
                    ->where('ChequeDate <=', $to)
                ->group_end()
                ->or_group_start()
                    ->where('paymentmode !=', $chequeId)
                    ->where('date >=', $from)
                    ->where('date <=', $to)
                ->group_end()
            ->group_end()                    
            ->order_by('id', 'DESC')
            ->get('payment');
        
        $receiptResults = $receiptQuery->num_rows() ? $receiptQuery->result() : [];
        
        $data['paymentMode'] = $this->db->select('id, payment_method')->get('payment_mode')->result();
        $data['receiptsData'] = $receiptResults;    
        $data['page'] = 'receipt/report';
        return $this->load->view('inventory_sep/inventory', $data);            
    }
}