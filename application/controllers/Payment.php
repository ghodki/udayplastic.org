<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
    
class Payment extends CI_Controller
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
            $data['page'] = 'payment/index';
            $data['InvoiceId'] = $invoiceId;
            $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function paymentByCustomer($customerId)
        {
            $data['page'] = 'payment/index';
            $data['CustomerId'] = $customerId;
            $this->load->view('inventory_sep/inventory', $data);
        }
    
        public function edit($id)
        {
            if (isset($id) && !empty($id)) {
                $CompId = $this->session->userdata('comp_id');
                $UserId = $this->session->userdata('user_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $Where = ['id' => $id, 'comp_id' => $CompId, 'user_id' => $UserId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                $return = $this->user_model->getRow('payment', $Where);

                if ($return) {
                    $data['result'] = $return;
                    $data['page'] = 'payment/edit';
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Could not find the data.', 'class' => 'danger']);
                    return redirect(base_url() . 'Payment');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not access.', 'class' => 'danger']);
                return redirect(base_url() . 'Payment');
            }
        }
    
        public function getPayment()
        {
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            if (isset($id) && !empty($id)) {
                $results = $this->user_model->getAry('payment', '', '', '', ['partyID' => $id]);

                if (isset($results) && !empty($results)) {
                    $html = '';
                    $sum_of_payment = 0;
                    $sum_of_reciept = 0;
                    $aman = 1;
                    foreach ($results as $key => $result) {
                        $key = $key + 1;

                        $html .= "<tr class='text-dark'>";
                        $html .= '<td scope="col" width="2%">' . $key . '</td>';
                        $html .= '<td scope="col" width="10%">' . $result->date . '</td>';
                        $html .= '<td scope="col" width="22%">' . get_title('parties', ['id' => $result->partyID], 'name') . '</td>';
                        $html .= '<td scope="col" width="8%">' . $result->paymentmode . '</td>';
                                if ($result->paymentReceipt == 'Payment') {
                                    $html .= '<td scope="col" width="19%">' . $result->amount . '</td>';
                                    $sum_of_payment = $sum_of_payment + $result->amount;
                                } else {
                                    $html .= '<td scope="col" width="19%">--</td>';
                                }
                                if ($result->paymentReceipt == 'Reciept') {
                                    $html .= '<td scope="col" width="28%">' . $result->amount . '</td>';
                                    $sum_of_reciept = $sum_of_reciept + $result->amount;
                                } else {
                                    $html .= '<td scope="col" width="28%">--</td>';
                                }
                        $html .= '<td scope="col" width="20%">' . $balance = $sum_of_reciept - $sum_of_payment . '</td>';

                        $html .= '<td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a href="' . base_url('index.php/payment/edit/') . $result->id . '" class="dropdown-item cp">Edit</a> <a href="' . base_url('index.php/payment/delete/') . $result->id . '" class="dropdown-item cp">Delete</a>
                                        </div>
                                    </div>
                                </td>';
                        $html .= '</tr>';
                    }

                    $total['payment'] = $sum_of_payment;
                    $total['receipt'] = $sum_of_reciept;
                    echo json_encode(['status' => true, 'data' => $html, 'total' => $total]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            } else {
                $res = $this->user_model->getFilterData('parties', '', $name, '');
                if ($res) {
                    foreach ($res as $result) {
                        $results = $this->user_model->getAry('payment', '', '', '', ['partyID' => $result->id]);
                        if (isset($results) && !empty($results)) {
                            $html = '';
                            $sum_of_payment = 0;
                            $sum_of_reciept = 0;
                            foreach ($results as $key => $result) {
                                $key = $key + 1;
                                $html .= "<tr class='text-dark'>";
                                $html .= '<td scope="col" width="2%">' . $key . '</td>';
                                $html .= '<td scope="col" width="10%">' . $result->date . '</td>';
                                $html .= '<td scope="col" width="22%">' . get_title('parties', ['id' => $result->partyID], 'name') . '</td>';
                                $html .= '<td scope="col" width="8%">' . $result->paymentmode . '</td>';
                                        if ($result->paymentReceipt == 'Payment') {
                                            $html .= '<td scope="col" width="19%">' . $result->amount . '</td>';
                                            $sum_of_payment = $sum_of_payment + $result->amount;
                                        } else {
                                            $html .= '<td scope="col" width="19%">--</td>';
                                        }
                                        if ($result->paymentReceipt == 'Reciept') {
                                            $html .= '<td scope="col" width="28%">' . $result->amount . '</td>';
                                            $sum_of_reciept = $sum_of_reciept + $result->amount;
                                        } else {
                                            $html .= '<td scope="col" width="28%">--</td>';
                                        }
                                $html .= '<td scope="col" width="20%">' . $balance = $sum_of_reciept - $sum_of_payment . '</td>';
                                $html .= '<td class="text-right">
					                        <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a href="' . base_url('index.php/payment/edit/') . $result->id . '" class="dropdown-item cp">Edit</a> <a href="' . base_url('index.php/payment/delete/') . $result->id . '" class="dropdown-item cp">Delete</a>
                                                </div>
                                                </div>
                                        </td>';
                                $html .= '</tr>';
                            }
                            $total['payment'] = $sum_of_payment;
                            $total['receipt'] = $sum_of_reciept;
                        }
                    }
                    echo json_encode(['status' => true, 'data' => $html, 'total' => $total]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
        }
    
        public function paymentPrint()
        {
            $id = $this->input->get('pID');
            $name = $this->input->get('sh');
            if (isset($id) && !empty($id)) {
                $results = $this->user_model->getAry('parties', '', '', '', ['id' => $id]);
                $result['result'] = $results;
            } else {
                $res = $this->user_model->getFilterData('parties', '', $name, '');
                $result['result'] = $res;
            }
            $result['page'] = 'inventory_sep/payment/print_payment';
            $this->load->view('inventory', $result);
        }
    
        public function update()
        {
            $id = $this->input->post('id');
            if ($_POST) {
                $id = $this->input->post('id');
                if (!isset($id) && empty($id)) {
                    $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                    redirect('payment');
                }

                $there = ['id' => $id];
                $re = $this->user_model->getRow('payment', $there);
                if ($re) {
                    $data['remark'] = $this->input->post('remark');
                    $data['ladger'] = $this->input->post('ladger');
                    $data['date'] = $this->input->post('date');
                    $data['partyID'] = $this->input->post('customerId');
                    $data['amount'] = $this->input->post('amount');
                    $data['paymentReceipt'] = $this->input->post('payment_recipt');
                    $data['paymentmode'] = $this->input->post('payment_mode');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['comp_id'] = $this->session->userdata('comp_id');
                    if ($this->session->userdata('BranchRole')){
                        $data['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                    }
                    $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    }
                    $result = $this->user_model->updateAry('payment', $data, $where);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
                        redirect('payment');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        redirect('payment');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Data Not Found.', 'class' => 'danger']);
                    redirect('payment');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'try Again.', 'class' => 'danger']);
                redirect('payment');
            }
        }
        
        public function getPaymentRemark($id) 
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
                            if (json_decode($PaymentInfo->invoice_type)[$key] == "Purchase") {
                                $PurchaseAmount = ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('purchases')->row('paid_amount') - $AmountInPaidInInvoice);
                                $UpdatePurchase = [
                                    'paid_amount' => $PurchaseAmount ? $PurchaseAmount : NULL,
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('purchases', $UpdatePurchase, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                            }

                            if (json_decode($PaymentInfo->invoice_type)[$key] == "Estimate Purchase") {
                                $EstimatePurchaseAmount = ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('estimate_purchase')->row('paid_amount') - $AmountInPaidInInvoice);
                                $UpdateEstimatePurchase = [
                                    'paid_amount' => $EstimatePurchaseAmount ? $EstimatePurchaseAmount : NULL,
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('estimate_purchase', $UpdateEstimatePurchase, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                            }
                        }
                    } else {
                        if ($PaymentInfo->invoice_type == "Purchase") {
                            $PurchaseAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('purchases')->row('paid_amount') - $PaymentInfo->credit);
                            $UpdatePurchase = [
                                'paid_amount' => $PurchaseAmount ? $PurchaseAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('purchases', $UpdatePurchase, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                        }
                        if ($PaymentInfo->invoice_type == "Estimate Purchase") {
                            $EstimatePurchaseAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('estimate_purchase')->row('paid_amount') - $PaymentInfo->credit);
                            $UpdateEstimatePurchase = [
                                'paid_amount' => $EstimatePurchaseAmount ? $EstimatePurchaseAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('estimate_purchase', $UpdateEstimatePurchase, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                        }
                    }
                    
                    $this->user_model->deleteRow('payment', $Where);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
    
        public function paymentDeleteAjax($id)
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
                            if (json_decode($PaymentInfo->invoice_type)[$key] == "Purchase") {
                                $PurchaseAmount = ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('purchases')->row('paid_amount') - $AmountInPaidInInvoice);
                                $UpdatePurchase = [
                                    'paid_amount' => $PurchaseAmount ? $PurchaseAmount : NULL,
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('purchases', $UpdatePurchase, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                            }
                            if (json_decode($PaymentInfo->invoice_type)[$key] == "Estimate Purchase") {
                                $EstimatePurchaseAmount = ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('estimate_purchase')->row('paid_amount') - $AmountInPaidInInvoice);
                                $UpdateEstimatePurchase = [
                                    'paid_amount' => $EstimatePurchaseAmount ? $EstimatePurchaseAmount : NULL,
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('estimate_purchase', $UpdateEstimatePurchase, ['id' => $InvoiceId, 'type' => json_decode($PaymentInfo->invoice_type)[$key]]);
                            }
                        }
                    } else {
                        if ($PaymentInfo->invoice_type == "Purchase") {
                            $PurchaseAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('purchases')->row('paid_amount') - $PaymentInfo->credit);
                            $UpdatePurchase = [
                                'paid_amount' => $PurchaseAmount ? $PurchaseAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('purchases', $UpdatePurchase, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                        }
                        if ($PaymentInfo->invoice_type == "Estimate Purchase") {
                            $EstimatePurchaseAmount = ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('estimate_purchase')->row('paid_amount') - $PaymentInfo->credit);
                            $UpdateEstimatePurchase = [
                                'paid_amount' => $EstimatePurchaseAmount ? $EstimatePurchaseAmount : NULL,
                                'updated' => $updated,
                                'updated_by' => $updatedBy
                            ];
                            $this->user_model->updateAry('estimate_purchase', $UpdateEstimatePurchase, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                        }
                    }
                    
                    $this->user_model->deleteRow('payment', $Where);
                    echo json_encode(['status' => true, 'msg' => 'Payment deleted successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Payment id not found.']);
                return;
            }
        }
    
        public function getVoucherNumber ()
        {
            if ($_POST) {
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
                if (in_array("Purchase", $InvoiceRowType) || in_array("Estimate Purchase", $InvoiceRowType)) {
                    $PaymentData['credit'] = $this->input->post('paid_amount');
                    $PaymentData['transaction_type'] = 'CR';
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

                        //purchase invoices
                        if ($RowType == "Purchase") {
                            $PurchaseInfo = $this->db->select('total, paid_amount')->where($where)->group_start()->where($extra_where)->or_where($or_where)->group_end()->get('purchases')->row();
                            $OldPaidAmount = $PurchaseInfo->paid_amount;

                            $remainingAmount = $PurchaseInfo->total - $OldPaidAmount;

                            if ($PaidAmount >= $remainingAmount) {
                                // If the paid amount is enough to cover the remaining amount for this invoice
                                $PaidAmount -= $remainingAmount; // Deduct the paid amount
                                $PurchaseUpdates = [
                                    'paid_amount' => $PurchaseInfo->total, // Mark as fully paid
                                    'updated' => date('d-m-Y h:i:s A'),
                                    'updated_by' => $UpdatedBy
                                ];
                                $AmountToPaidInInvoice[] = '"' . $remainingAmount . '"';
                            } else {
                                // If the paid amount is not enough to cover the total remaining amount for this invoice
                                $PurchaseUpdates = [
                                    'paid_amount' => $OldPaidAmount + $PaidAmount, // Update paid amount
                                    'updated' => date('d-m-Y h:i:s A'),
                                    'updated_by' => $UpdatedBy
                                ];
                                $AmountToPaidInInvoice[] = '"' . $PaidAmount . '"';
                                $PaidAmount = 0; // Zero out the remaining paid amount
                            }

                            $PurchaseWhere = ['id' => $RowId, 'type' => $RowType];
                            $this->user_model->updateAry('purchases', $PurchaseUpdates, $PurchaseWhere);
                            // Break the loop if there's no remaining paid amount
                            if ($PaidAmount <= 0) {
                                break;
                            }
                        }

                        //estimate purchase invoices
                        if ($RowType == "Estimate Purchase") {
                            $EstimatePurchaseInfo = $this->db->select('total, paid_amount')->where($where)->group_start()->where($extra_where)->or_where($or_where)->group_end()->get('estimate_purchase')->row();
                            $OldPaidAmount = $EstimatePurchaseInfo->paid_amount;

                            $remainingAmount = $EstimatePurchaseInfo->total - $OldPaidAmount;

                            if ($PaidAmount >= $remainingAmount) {
                                // If the paid amount is enough to cover the remaining amount for this invoice
                                $PaidAmount -= $remainingAmount; // Deduct the paid amount
                                $EstimatePurchaseUpdates = [
                                    'paid_amount' => $EstimatePurchaseInfo->total, // Mark as fully paid
                                    'updated' => date('d-m-Y h:i:s A'),
                                    'updated_by' => $UpdatedBy
                                ];
                                $AmountToPaidInInvoice[] = '"' . $remainingAmount . '"';
                            } else {
                                // If the paid amount is not enough to cover the total remaining amount for this invoice
                                $EstimatePurchaseUpdates = [
                                    'paid_amount' => $OldPaidAmount + $PaidAmount, // Update paid amount
                                    'updated' => date('d-m-Y h:i:s A'),
                                    'updated_by' => $UpdatedBy
                                ];
                                $AmountToPaidInInvoice[] = '"' . $PaidAmount . '"';
                                $PaidAmount = 0; // Zero out the remaining paid amount
                            }

                            $EstimatePurchaseWhere = ['id' => $RowId, 'type' => $RowType];
                            $this->user_model->updateAry('estimate_purchase', $EstimatePurchaseUpdates, $EstimatePurchaseWhere);
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
                    echo json_encode(['Status' => true, 'Message' => 'Payment saved successfully.']);
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
                    $partyBalance = getPartyBalancePayment($id);
                } elseif ($this->input->post('estimate') == true) {
                    $partyBalance = getPartyBalanceWithEstimatePayment($id);
                }
                $startDate = get_title('parties', ['id' => $id], 'added_at');
                $fromDate = date('Y-m-d', strtotime($startDate));
                $toDate = date('Y-m-d');
                echo json_encode(['status' => true, 'partyBalance' => $partyBalance, 'From' => $fromDate, 'To' => $toDate]);
            } else {
                echo json_encode(['status' => false, 'partyBalance' => 0, 'From' => "", 'To' => ""]);
            }
        }
    
        public function purchase_report($party_id = null)
        {
            if (isset($party_id) && !empty($party_id)) {
                $data['party_id'] = $party_id;
                $this->load->view('inventory_sep/customer_report/party_purchase', $data);
            }
        }
    
        public function sale_report($id = null)
        {
            if (isset($id) && !empty($id)) {
                $data['party_id'] = $id;
                $this->load->view('inventory_sep/customer_report/party_sale', $data);
            }
        }
        
        public function payment($id = null)
        {
            if (isset($id) && !empty($id)) {
                $data['party_id'] = $id;
                $this->load->view('inventory_sep/customer_report/payment', $data);
            }
        }
	    
        public function print($Id)
        {
            if (isset($Id) && !empty($Id)) {
                $Where = ['id' => $Id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $data['Branch'] = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId')]);
                } else {
                    $data['Company'] = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id')]);
                }
                
                $data['Payment'] = $this->user_model->getRow('payment', $Where);
                return $this->load->view('inventory_sep/payment/print', $data);
            }
        }
        
        public function getPandingInvoices()
        {
            if ($_POST) {
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
                    
                    if (get_title('accounting_group', ['id' => $AccountGroup], 'name') == "Sundry Creditors") {
                        if ($this->input->post('estimate') == "") {
                            $extra_where = ['paid_amount' => NULL];
                            $or_where = 'total != paid_amount';
                            
                            $results = $this->user_model->getSelectedResult('purchases', 'id, invoiceNumber, type', $where, $extra_where, $or_where);
                        } elseif ($this->input->post('estimate') == true) {
                            $or_where = 'total != paid_amount';
                            
                            $PurchaseQuery = $this->db->select('id, invoiceNumber, type')->where($where)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('purchases');
                            $EstimatePurchaseQuery = $this->db->select('id, invoiceNumber, type')->where($where)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('estimate_purchase');
                            $results = $this->db->query($PurchaseQuery . " UNION " . $EstimatePurchaseQuery . " ORDER BY id ASC")->result();
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
        
        public function getPaymentStatement()
        {
            if ($_POST) {                
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
                    
                    $Payments = $this->db->where($Where)->order_by('id', 'ASC')->get('payment')->result();

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
                                    if ((is_array(json_decode($Data->invoice_type))) || ($Data->invoice_type == "Purchase" && $Data->payment_type == "Payment") || ($Data->invoice_type == "Estimate Purchase" && $Data->payment_type == "Payment")) {
                                        $number = $Data->voucher_number;
                                    } else {
                                        if ($Data->invoice_type == "Purchase") {
                                            if ($Data->payment_type == NULL) { 
                                                $number = "<a href='" . base_url() . "purchase/viewpurchase/" . $Data->invoice_id . "' title='View Invoice'>"
                                                        . get_title('purchases', ['id' => $Data->invoice_id], 'invoiceNumber')
                                                        . "</a>";
                                            } else {
                                                $number = "";
                                            }
                                        } elseif ($Data->invoice_type == "Estimate Purchase") {
                                            if ($Data->payment_type == NULL) {
                                                $number = "<a href='" . base_url() . "estimate_purchase/view/" . $Data->invoice_id . "' title='View Invoice'>"
                                                        . get_title('estimate_purchase', ['id' => $Data->invoice_id], 'invoiceNumber')
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
                                    $totalCredit += $Data->credit;
                                    $totalDebit += $Data->debit;
                                    $totalDiscount += $Data->cashDiscountAmount;
                                    $cashDiscountAmount = !empty($Data->cashDiscountAmount) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($Data->cashDiscountAmount) : "";

                                    if ($Data->invoice_type == "Purchase" || $Data->invoice_type == "Estimate Purchase" || in_array('Purchase', json_decode($Data->invoice_type)) || in_array('Estimate Purchase', json_decode($Data->invoice_type))) {
                                        $totalBalance = indianMoneyFormat(($totalDebit - $totalCredit));
                                    }                                    
                                    
                                    if (!empty($Data->debit) && $Data->transaction_type == "DR" && $Data->payment_type == NULL && $Data->invoice_type == "Purchase") {
                                        $sql = "SELECT paymentmode FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'CR'";
                                        $query = $this->db->query($sql);
                                        $PaymentModeId = $query->row('paymentmode');
                                        if (get_title('purchases', ['id' => $Data->invoice_id, 'type' => 'Purchase'], 'paid_amount') == get_title('purchases', ['id' => $Data->invoice_id, 'type' => 'Purchase'], 'total')) { 
                                            if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                                $sql1 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'CR'";
                                                $query1 = $this->db->query($sql1);
                                                if ($query1->row('ChequeStatus') == "0") {
                                                    $PurchasePaymentStatus = "<a href='javascript:void(0);' class='text-maroon' onclick='onChequeInfo($Data->invoice_id);' title='Cheque Info'><i class='fa fa-exclamation'></i> UnPaid/PDC</a>";
                                                } elseif ($query1->row('ChequeStatus') == "1") {
                                                    $PurchasePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                                }                                                 
                                            } else {
                                                $PurchasePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                            }
                                        } elseif (get_title('purchases', ['id' => $Data->invoice_id, 'type' => 'Purchase'], 'paid_amount') == NULL || get_title('purchases', ['id' => $Data->invoice_id, 'type' => 'Purchase'], 'paid_amount') == 0) { 
                                            $PurchasePaymentStatus = "<a href='" . base_url() . "payment/index/" .  get_title('purchases', ['id' => $Data->invoice_id, 'type' => 'Purchase'], 'id') . "' class='text-danger'><i class='fa fa-exclamation-triangle'></i> Unpaid</a>";
                                        } else {
                                            if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                                $sql2 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'CR'";
                                                $query2 = $this->db->query($sql2);
                                                if ($query2->row('ChequeStatus') == "0") {
                                                    $PurchasePaymentStatus = "<a href='javascript:void(0);' class='text-maroon' onclick='onChequeInfo($Data->invoice_id);' title='Cheque Info'><i class='fa fa-exclamation'></i> UnPaid/PDC</a>";
                                                } elseif ($query2->row('ChequeStatus') == "1") { 
                                                    $PurchasePaymentStatus = "<a href='" . base_url() . "payment/index/" .  get_title('purchases', ['id' => $Data->invoice_id, 'type' => 'Purchase'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                                }
                                            } else {
                                                $PurchasePaymentStatus = "<a href='" . base_url() . "payment/index/" .  get_title('purchases', ['id' => $Data->invoice_id, 'type' => 'Purchase'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                            }
                                        }
                                    } else {
                                        $PurchasePaymentStatus = "";
                                    }
                                    
                                    if (!empty($Data->debit) && $Data->transaction_type == "DR" && $Data->payment_type == NULL && $Data->invoice_type == "Estimate Purchase") {
                                        $sql = "SELECT paymentmode FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'CR'";
                                        $query = $this->db->query($sql);
                                        $PaymentModeId = $query->row('paymentmode');
                                        if (get_title('estimate_purchase', ['id' => $Data->invoice_id, 'type' => 'Estimate Purchase'], 'paid_amount') == get_title('estimate_purchase', ['id' => $Data->invoice_id, 'type' => 'Estimate Purchase'], 'total')) { 
                                            if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                                $sql1 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'CR'";
                                                $query1 = $this->db->query($sql1);
                                                if ($query1->row('ChequeStatus') == "0") {
                                                    $EstimatePurchasePaymentStatus = "<span class='text-maroon'><i class='fa fa-exclamation'></i> UnPaid/PDC</span>";
                                                } elseif ($query1->row('ChequeStatus') == "1") {
                                                    $EstimatePurchasePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                                }                                                 
                                            } else {
                                                $EstimatePurchasePaymentStatus = "<span class='text-success'><i class='fa fa-check-circle'></i> Paid</span>";
                                            }
                                        } elseif (get_title('estimate_purchase', ['id' => $Data->invoice_id, 'type' => 'Estimate Purchase'], 'paid_amount') == NULL || get_title('estimate_purchase', ['id' => $Data->invoice_id, 'type' => 'Estimate Purchase'], 'paid_amount') == 0) { 
                                            $EstimatePurchasePaymentStatus = "<a href='" . base_url() . "payment/index/" .  get_title('estimate_purchase', ['id' => $Data->invoice_id, 'type' => 'Estimate Purchase'], 'id') . "' class='text-danger'><i class='fa fa-exclamation-triangle'></i> Unpaid</a>";
                                        } else { 
                                            if (get_title('payment_mode', ['id' => $PaymentModeId], 'payment_method') == "Cheque") {
                                                $sql2 = "SELECT ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Data->invoice_id\"]') AND transaction_type = 'CR'";
                                                $query2 = $this->db->query($sql2);
                                                if ($query2->row('ChequeStatus') == "0") {
                                                    $EstimatePurchasePaymentStatus = "<span class='text-maroon'><i class='fa fa-exclamation'></i> UnPaid/PDC</span>";
                                                } elseif ($query2->row('ChequeStatus') == "1") { 
                                                    $EstimatePurchasePaymentStatus = "<a href='" . base_url() . "payment/index/" .  get_title('estimate_purchase', ['id' => $Data->invoice_id, 'type' => 'Estimate Purchase'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                                }
                                            } else {
                                                $EstimatePurchasePaymentStatus = "<a href='" . base_url() . "payment/index/" .  get_title('estimate_purchase', ['id' => $Data->invoice_id, 'type' => 'Estimate Purchase'], 'id') . "' class='text-warning'><i class='fa fa-exclamation-circle'></i> Partial</a>";
                                            }
                                        }
                                    } else {
                                        $EstimatePurchasePaymentStatus = "";
                                    }
                                    if ($Data->invoice_type == "Purchase") {
                                        $PaymentStatus = $PurchasePaymentStatus;
                                    } elseif ($Data->invoice_type == "Estimate Purchase") {
                                        $PaymentStatus = $EstimatePurchasePaymentStatus;
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
            if ($_POST) {
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
                    
                    if (get_title('accounting_group', ['id' => $AccountGroup], 'name') == "Sundry Creditors") {
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
                            
                            $results = $this->user_model->getSelectedInvoice('purchases', 'SUM(total) AS total, SUM(paid_amount) AS paid_amount', $where, $where_id, $where_type, $extra_where, $or_where);
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
                            
                            $PurchaseQuery = $this->db->select('SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($where)->where_in('id', $where_id)->where_in('type', $where_type)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('purchases');
                            $EstimatePurchaseQuery = $this->db->select('SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($where)->where_in('id', $where_id)->where_in('type', $where_type)->group_start()->where('paid_amount', NULL)->or_where($or_where)->group_end()->get_compiled_select('estimate_purchase');
                            $results = $this->db->query($PurchaseQuery . " UNION " . $EstimatePurchaseQuery)->result();
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
        
        public function statement()
        {
            $user_id = $this->session->userdata('user_id');
            $comp_id = $this->session->userdata('comp_id');
            
            $accountGroupId = $this->input->get('accountGroupId');
            $ledgerId = $this->input->get('ledgerId') ?: null;
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $fromDate = $this->input->get('fromDate');
                $toDate = $this->input->get('toDate');
            } else {
                $fromDate = date('Y-m-01');
                $toDate = date('Y-m-d');
            }

            $openingBalanceValue = 0;
            $data['ledgerStatement'] = [];

            // Only query when a ledger/account group is selected
            if (!empty($accountGroupId) || !empty($ledgerId)) {
                $openingBalanceWhere = ['date <' => $fromDate, 'status' => 1, 'user_id' => $user_id, 'comp_id' => $comp_id];
                $pwhere = ['date >=' => $fromDate, 'date <=' => $toDate, 'status' => 1, 'user_id' => $user_id, 'comp_id' => $comp_id];
                if (!empty($accountGroupId)) {
                    $openingBalanceWhere['account_group'] = $accountGroupId;
                    $pwhere['account_group'] = $accountGroupId;
                }
                if (!empty($ledgerId)) {
                    $openingBalanceWhere['party_id'] = $ledgerId;
                }
                if ($this->session->userdata('BranchRole')) {
                    $openingBalanceWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $pwhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $openingBalanceData = $this->db->select('SUM(credit) AS totalCredit, SUM(debit) AS totalDebit')->where($openingBalanceWhere)->get('payment')->row();
                $totalCredit = (float) ($openingBalanceData->totalCredit ?? 0);
                $totalDebit = (float) ($openingBalanceData->totalDebit ?? 0);

                $groupName = !empty($accountGroupId) ? get_title('accounting_group', ['id' => $accountGroupId], 'name') : '';
                if ($groupName == "Sundry Debitors") {
                    $openingBalanceValue = ($totalCredit - $totalDebit);
                } elseif ($groupName == "Sundry Creditors") {
                    $openingBalanceValue = ($totalDebit - $totalCredit);
                } else {
                    $openingBalanceValue = ($totalCredit - $totalDebit);
                }
                
                $data['ledgerStatement'] = $this->user_model->getResultArray('payment', $pwhere, $ledgerId);
            }

            $data['page'] = 'payment/statement';
            $data['openingBalance'] = $openingBalanceValue;
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function multipleStatement()
        {
            $user_id = $this->session->userdata('user_id');
            $comp_id = $this->session->userdata('comp_id');
            
            $accountGroupId = $this->input->get('accountGroupId');
            $ledgerId = $this->input->get('ledgerId') ?: null;
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $fromDate = $this->input->get('fromDate');
                $toDate = $this->input->get('toDate');
            } else {
                $fromDate = date('Y-m-01');
                $toDate = date('Y-m-d');
            }
            
            $openingBalanceWhere = ['account_group' => $accountGroupId, 'date <' => $fromDate, 'status' => 1, 'user_id' => $user_id, 'comp_id' => $comp_id];
            if (!empty($ledgerId)) {
                $openingBalanceWhere['party_id'] = $ledgerId;
            }
            $pwhere = ['account_group' => $accountGroupId, 'date >=' => $fromDate, 'date <=' => $toDate, 'status' => 1, 'user_id' => $user_id, 'comp_id' => $comp_id];
            if ($this->session->userdata('BranchRole')) {
                $openingBalanceWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $pwhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $openingBalanceData = $this->db->select('SUM(credit) AS totalCredit, SUM(debit) AS totalDebit')->where($openingBalanceWhere)->get('payment')->row();
            $totalCredit = (float) ($openingBalanceData->totalCredit ?? 0);
            $totalDebit = (float) ($openingBalanceData->totalDebit ?? 0);

            if (get_title('accounting_group', ['id' => $accountGroupId], 'name') == "Sundry Debitors") {
                $openingBalanceValue = ($totalCredit - $totalDebit);
            } elseif (get_title('accounting_group', ['id' => $accountGroupId], 'name') == "Sundry Creditors") {
                $openingBalanceValue = ($totalDebit - $totalCredit);
            } else {
                $openingBalanceValue = ($totalCredit - $totalDebit);
            }
            
            $data['ledgerStatement'] = $this->user_model->getResultArray('payment', $pwhere, $ledgerId);
            $data['page'] = 'payment/multiple-statement';
            $data['openingBalance'] = $openingBalanceValue;
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function printStatement()
        {            
            $this->load->view('inventory_sep/payment/print-statement');
        }
        
        
        public function PrintMultiplePaymentStatement()
        {
            $this->load->view('inventory_sep/payment/print-multiple-statement');
        }
        
        
        public function getPaymentInfomationByInvoiceId() 
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

                    if ($this->db->where('type', 'Purchase')->where($CommonWhere)->get('purchases')->num_rows()) {
                        $this->db->select('id, type, customerId');
                        $this->db->where('type', 'Purchase');
                        $this->db->where($CommonWhere);
                        if ($this->session->userdata('RoleType')) {
                            $this->db->where('EmployeeId', $EmployeeId);
                        }
                        $Result = $this->db->get('purchases')->row();
                        $partyInfo = $this->db->select('id, group_head')->where('id', $Result->customerId)->get('parties')->row();
                    }
                    if ($this->db->where('type', 'Estimate Purchase')->where($CommonWhere)->get('estimate_purchase')->num_rows()) {
                        $this->db->select('id, type, customerId');
                        $this->db->where('type', 'Estimate Purchase');
                        $this->db->where($CommonWhere);
                        if ($this->session->userdata('RoleType')) {
                            $this->db->where('EmployeeId', $EmployeeId);
                        }
                        $Result = $this->db->get('estimate_purchase')->row();
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
        
        public function getPaymentInfomationByCustomerId() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $CustomerId = $this->input->post('CustomerId');
                if (isset($CustomerId) && !empty($CustomerId)) {
                    if ($this->session->userdata('RoleType')) {
                        $EmployeeId = $this->session->userdata('EmployeeId');
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');        
                    
                    $CommonWhere = ['customerId' => $CustomerId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $CommonWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    if ($this->db->where('type', 'Purchase')->where($CommonWhere)->get('purchases')->num_rows()) {
                        $this->db->select('id, type, customerId');
                        $this->db->where('type', 'Sale');
                        $this->db->where($CommonWhere);
                        $Result = $this->db->get('purchases')->row();
                        $partyInfo = $this->db->select('id, group_head')->where('id', $Result->customerId)->get('parties')->row();
                    }
                    if ($this->db->where('type', 'Estimate Purchase')->where($CommonWhere)->get('estimate_purchase')->num_rows()) {
                        $this->db->select('id, type, customerId');
                        $this->db->where('type', 'Estimate Purchase');
                        $this->db->where($CommonWhere);
                        $Result = $this->db->get('estimate_purchase')->row();
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
        
        public function partialUnpaidInvoices() 
        {
            $CustomerId = $this->input->get('party');
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $fromDate = $this->input->get('fromDate');
                $toDate = $this->input->get('toDate');
            } else {
                $fromDate = date('Y-m-d');
                $toDate = date('Y-m-d');
            }
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');        

            $CommonWhere = ['invoiceDate >= ' => $fromDate, 'invoiceDate <= ' => $toDate, 'customerId' => $CustomerId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $CommonWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            $data['InvoiceData'] = [];
            if ($this->input->get('account_group') == 5) {
                $data['InvoiceData'] = $this->db->select('id, invoiceDate, invoiceNumber, customerName, type, total, paid_amount')->where($CommonWhere)->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->order_by('id', 'DESC')->get('sales')->result();
            } elseif ($this->input->get('account_group') == 6) {
                $data['InvoiceData'] = $this->db->select('id, invoiceDate, invoiceNumber, customerName, type, total, paid_amount')->where($CommonWhere)->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->order_by('id', 'DESC')->get('purchases')->result();
            }
            $data['account_group'] = $this->db->where('status', 1)->where('name', 'Sundry Creditors')->or_where('name', 'Sundry Debitors')->order_by('name', 'ASC')->get('accounting_group')->result();
            $data['page'] = 'payment/partialAndUnpaidInvoices';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function chequeInfo($invoiceId) 
        {
            if (empty($invoiceId)) {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $sql = "SELECT id FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$invoiceId\"]') AND transaction_type = 'CR'";
                $query = $this->db->query($sql);
                if ($this->db->select('id')->where('id', $invoiceId)->where('type', 'Purchase')->get('purchases')->num_rows()) {
                    $data['purchaseInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, type, total')->where('id', $invoiceId)->where('type', 'Purchase')->get('purchases')->row();
                }
                if ($this->db->select('id')->where('id', $invoiceId)->where('type', 'Estimate Purchase')->get('estimate_purchase')->num_rows()) {
                    $data['purchaseInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, type, total')->where('id', $invoiceId)->where('type', 'Estimate Purchase')->get('estimate_purchase')->row();
                }
                if ($query->num_rows()) {
                    $sql1 = "SELECT id, date, paymentmode, ChequeDate, credit, remark FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$invoiceId\"]') AND transaction_type = 'CR'";
                    $query1 = $this->db->query($sql1);
                    $data['paymentInfo'] = $query1->row();
                } else {
                    $data['paymentInfo'] = $this->db->select('id, date, paymentmode, ChequeDate, credit, remark')->where('invoice_id', $invoiceId)->where('transaction_type', 'CR')->get('payment')->row();
                }
                return $this->load->view('inventory_sep/payment/ChequeInfo', $data);
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
                    
                    if ($this->input->post('invoiceType') == "Purchase") {
                        $InvoiceData['paid_amount'] = (get_title('purchases', ['id' => $this->input->post('invoiceId')], 'paid_amount') - $this->input->post('Amount'));
                        $this->user_model->updateAry('purchases', $InvoiceData, ['id' => $this->input->post('invoiceId'), 'type' => 'Purchase']);
                    }
                    if ($this->input->post('invoiceType') == "Estimate Purchase") {
                        $InvoiceData['paid_amount'] = (get_title('estimate_purchase', ['id' => $this->input->post('invoiceId')], 'paid_amount') - $this->input->post('Amount'));
                        $this->user_model->updateAry('estimate_purchase', $InvoiceData, ['id' => $this->input->post('invoiceId'), 'type' => 'Estimate Purchase']);
                    }
                    $Result = $this->user_model->deleteRow('payment', ['id' => $this->input->post('PaymentId')]);
                }
                
                if ($Result) {
                    echo json_encode(['status' => true, 'message' => 'Record Updated successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'message' => 'Failed.']);
                }
            }
        }
    }