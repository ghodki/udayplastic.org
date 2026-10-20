<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    date_default_timezone_set('Asia/Kolkata');
    
    class Estimate_purchase extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);

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
            $data['page'] = 'estimate_purchase/index';
            $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function saveestimate()
        {
            $product = $this->input->post('product');
            $array_of_products = [];
            foreach ($product as $row) {
                $qty = $row['qty'];
                $netAmount = $row['netAmount'];
                $rate = $row['rate'];
                $product_id = $row['product'];
                if ($qty == '' || $netAmount == '' || $rate == '' || $product_id == '') {
                } else {
                    $array_of_products[] = $row;
                }
            }
            
            if (empty($array_of_products)) {
                echo json_encode(['status' => false, 'msg' => 'Please enter products required fields.']);
                return;
            }
            
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }
            
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Invoice number required, Please try again.']);
                return;
            }
            
            $this->form_validation->set_rules('invoiceDate', 'Invoice Date', 'trim|required');
            $this->form_validation->set_rules('partyname', 'Party Name', 'trim|required');
            $this->form_validation->set_rules('customerId', 'Customer', 'trim|required');
            $this->form_validation->set_rules('amountInWords', 'Amount In Words', 'trim|required');
            $this->form_validation->set_rules('taxableAmount', 'Taxable Amount', 'trim|required');
            $this->form_validation->set_rules('totalTax', 'Total Tax', 'trim|trim|required');
            $this->form_validation->set_rules('totalValue', 'Total Value', 'trim|required');
            $this->form_validation->set_rules('roundOff', 'Round Off', 'trim|required');
            $this->form_validation->set_rules('total', 'Total', 'trim|required');
            
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors()]);
                return;
            }
            
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $data['EmployeeId'] = $this->session->userdata('EmployeeId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
            }

            $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
            
            $data['invoiceNumber'] = ucwords($this->input->post('invoiceNumber'));
            $data['invoiceDate'] = $this->input->post('invoiceDate');
            $data['voucherType'] = $this->input->post('voucherType');
            $data['customerId'] = ucfirst($this->input->post('customerId'));
            $data['customerName'] = get_title('parties', ['id' => $this->input->post('customerId')], 'name');
            $data['gstNo'] = ucfirst($this->input->post('gstNo'));
            $data['taxCode'] = $this->input->post('gstType');
            $data['customerAddress'] = $this->input->post('customerAddress');
            $data['customerCity'] = $this->input->post('customerCity');
            $data['state_name'] = $this->input->post('statename');
            $data['city_name'] = $this->input->post('cityCustomer');
            $data['customerState'] = $this->input->post('customerState');
            $data['customerMobile'] = $this->input->post('customerMobile');
            $data['customerEmail'] = $this->input->post('customerEmail');
            $data['customerBalance'] = $this->input->post('customerBalance');
            $data['amountInWords'] = ucwords($this->input->post('amountInWords'));
            $data['taxableAmount'] = $this->input->post('taxableAmount');
            $data['totalTax'] = $this->input->post('totalTax');
            $data['totalValue'] = $this->input->post('totalValue');
            $data['roundOff'] = $this->input->post('roundOff');
            $data['freight_charge'] = $this->input->post('freight_charge');
            $data['cash_discount'] = $this->input->post('cash_discount');
            $data['total'] = $this->input->post('total');
            $data['expenses'] = $this->input->post('expenses');
            $data['lrDate'] = $this->input->post('lrDate');
            $data['lrNumber'] = $this->input->post('lrNumber');
            $data['ewayBill'] = $this->input->post('ewayBill');
            $data['transport'] = $this->input->post('transport');
            $data['payTermsDay'] = $this->input->post('payTermsDay');
            $data['dueDate'] = $this->input->post('dueDate');
            $data['lot_no'] = $this->input->post('lot_no');
            $data['dcDate'] = $this->input->post('dcDate');
            $data['dcNumber'] = $this->input->post('dcNumber');
            $data['oldBalance'] = $this->input->post('oldBalance');
            $data['totalsgst'] = $this->input->post('totalsgst');
            $data['totalcgst'] = $this->input->post('totalcgst');
            $data['totaligst'] = $this->input->post('totaligst');
            $data['orderDate'] = $this->input->post('orderDate');
            $data['orderNumber'] = $this->input->post('orderNumber');
            $data['taxCal'] = $this->input->post('taxcal');
            $data['shipping_address'] = $this->input->post('shipping_address');
            $data['comp_id'] = $CompId;
            $data['CompanyBranchId'] = $CompanyBranchId; 
            $data['user_id'] = $UserId;
            $data['financial_year'] = $FinancialYear;
            $data['cess'] = $this->input->post('cess');
            $data['vahical_no'] = $this->input->post('vahical_no');
            $data['lh'] = $this->input->post('lh');
            $data['lot_no'] = $this->input->post('lot_no');
            $data['remark'] = $this->input->post('remark');
            $data['p_code'] = $this->input->post('p_code');
            $data['format_invoice'] = $this->input->post('format_invoice');
            $data['product'] = json_encode($array_of_products);

            $parties = array(
                'name' => get_title('parties', ['id' => $this->input->post('customerId')], 'name'),
                'customer_id' => $this->input->post('customerId'),
                'address' => $this->input->post('customerAddress'),
                'city_id' => $this->input->post('customerCity'),
                'state_id' => $this->input->post('customerState'),
                'mobile_number' => $this->input->post('customerMobile'),
                'statename' => $this->input->post('statename'),
                'cityname' => $this->input->post('cityCustomer'),
                'gst_no' => ucfirst($this->input->post('gstNo')),
                'email' => $this->input->post('customerEmail')
            );
            $data['parties'] = json_encode($parties);

            $productids = [];
            foreach ($array_of_products as $row) {
                $productids[] = $row['product'];
            }

            $data['product_ids'] = implode(',', $productids);
            $data['num_of_items'] = $this->input->post('num_of_items');
            $data['created'] = date('d-m-Y h:i:s A');
            $data['created_by'] = $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name');
            $result = $this->user_model->saveAry('estimate_purchase', $data);
            
            // invoice tabe data store 
            $this->user_model->bulkEstimatePurchaseUp('estimate_product', $array_of_products, $result, $data['invoiceNumber'], $data['invoiceDate']);
            // send_sms_for_sale($this->input->post('customerMobile'), $this->input->post('total'));
            if (isset($result) && !empty($result)) {
                $PaymentDedit = [
                    'date' => $this->input->post('invoiceDate'),
                    'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                    'party_id' => $this->input->post('customerId'),
                    'invoice_id' => $result,
                    'invoice_type' => 'Estimate Purchase',
                    'debit' => $this->input->post('total'),
                    'transaction_type' => 'DR',
                    'user_id' => $UserId,
                    'comp_id' => $CompId,
                    'CompanyBranchId' => $CompanyBranchId,
                    'financial_year' => $FinancialYear,
                    'created' => date('d-m-Y h:i:s A')
                ];
                $this->user_model->saveAry('payment', $PaymentDedit);

                if ($this->input->post('voucherType') == 1) {
                    $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                    $unorder_where = ['comp_id' => $CompId, 'user_id' => $UserId , 'status'=> 1];
                    if ($this->session->userdata('BranchRole')) {
                        $SeriesWhere['series.CompanyBranchId'] = $CompanyBranchId;
                        $unorder_where['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Estimate Voucher')->where($SeriesWhere)->get('seriestitle')->row();
                    $UnorderNumber = $this->db->select('id')->where($unorder_where)->order_by('id', 'DESC')->get('payment')->row('id') + 1;
                    
                    $PaymentCredit = [
                        'voucher_type' => 'Estimate Voucher',
                        'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                        'date' => date('Y-m-d'),
                        'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                        'party_id' => $this->input->post('customerId'),
                        'invoice_id' => $result,
                        'invoice_type' => 'Estimate Purchase',
                        'credit' => $this->input->post('total'),
                        'transaction_type' => 'CR',
                        'payment_type' => 'Payment',
                        'paymentmode' => get_title('payment_mode', ['payment_method' => 'Cash'], 'id'),
                        'user_id' => $UserId,
                        'comp_id' => $CompId,
                        'CompanyBranchId' => $CompanyBranchId,
                        'financial_year' => $FinancialYear,
                        'created' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->saveAry('payment', $PaymentCredit);

                    $GetSeriesWhere = ['series_id' => $Series->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $GetSeriesWhere['CompanyBranchId'] = $CompanyBranchId; 
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
                    
                    $EstimateUpdate = [
                        'paid_amount' => $this->input->post('total')
                    ];
                    $this->user_model->updateAry('estimate_purchase', $EstimateUpdate, ['id' => $result]);
                }
                
                $where1 = ['series_id' => $this->input->post('series_id'), 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $where1['CompanyBranchId'] = $CompanyBranchId; 
                }
                $series = $this->user_model->getRow('series', $where1);
                if ($series) {
                    if ($series->starter_point == 19) {
                        $seriesNumber = 110;
                    } elseif ($series->starter_point == 199) {
                        $seriesNumber = 1100;
                    } elseif ($series->starter_point == 1999) {
                        $seriesNumber = 11000;
                    } elseif ($series->starter_point == 19999) {
                        $seriesNumber = 110000;
                    } elseif ($series->starter_point == 199999) {
                        $seriesNumber = 1100000;
                    } else {
                        $seriesNumber = $series->starter_point + 1;
                    }
                    $seriesNumberData['starter_point'] = $seriesNumber;
                    $this->user_model->updateAry('series', $seriesNumberData, $where1);
                } else {
                    $only_number_invoice['unorder_invoice_number'] = $this->input->post('invoiceNumber');
                    $this->user_model->updateAry('estimate_purchase', $only_number_invoice, ['id' => $result]);
                }
                echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }        
        
        public function InvoiceNumberForEstimate()
        {
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $EmployeeId = $this->session->userdata('EmployeeId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');

                $estimate_where = ['status' => 1, 'InvoiceNumber' => $id, 'comp_id' => $CompId, 'user_id' => $UserId];
                if ($this->session->userdata('BranchRole')) {
                    $estimate_where['CompanyBranchId'] = $CompanyBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $estimate_where['EmployeeId'] = $EmployeeId;
                }
                
                $party = $this->user_model->getRow('estimate_purchase', $estimate_where);
                if (isset($party) && !empty($party)) {
                    $estimate_product_where = ['InvoiceID' => $party->id, 'comp_id' => $CompId, 'user_id' => $UserId, 'type' => "Purchase"];
                    if ($this->session->userdata('BranchRole')) {
                        $estimate_product_where['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $invoice_product = $this->user_model->getAry('estimate_product', '', '', '', $estimate_where);
                    $data['invoice_product'] = $invoice_product;
                    $data['party'] = $party;
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}
          
        public function printEstimatePurchasePreview()
        {
            
        }
        
        public function edit($id = null)
        {
            if (empty($id)) {
                $data['page'] = 'error';
                return $this->load->view('inventory_sep/inventory', $data);
            }
            $data['page'] = 'estimate_purchase/EditEstimatePurchase';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimate_purchase', $where);
            
            if ($result->voucherType != 1) {
                if ($result->paid_amount != 0) {
                    $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not change this.', 'class' => 'danger']);
                    return redirect(base_url() . 'estimate_purchase');
                }
            }
            
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate_purchase');
            }
        }
        
        public function estimateUpdate()
        {
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }
            
            $id = $this->input->post('printSale');
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }
            
            $product = $this->input->post('product');
            $array_of_products = [];
            foreach ($product as $row) {
                $qty = $row['qty'];
                $netAmount = $row['netAmount'];
                $rate = $row['rate'];
                $productId = $row['product'];
                if ($qty == '' || $netAmount == '' || $rate == '' || $productId == '') {
                } else {
                    $array_of_products[] = $row;
                }
            }
            
            if (empty($array_of_products)) {
                echo json_encode(['status' => false, 'msg' => 'Please enter products required fields.']);
                return;
            }
            
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }

            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required|trim');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Invoice number is required, Please try again.']);
                return;
            }
            
            $this->form_validation->set_rules('invoiceDate', 'Invoice Date', 'trim|required');
            $this->form_validation->set_rules('partyname', 'Party Name', 'trim|required');
            $this->form_validation->set_rules('customerId', 'Customer', 'trim|required');
            $this->form_validation->set_rules('amountInWords', 'Amount In Words', 'trim|required');
            $this->form_validation->set_rules('taxableAmount', 'Taxable Amount', 'trim|required');
            $this->form_validation->set_rules('totalTax', 'Total Tax', 'trim|trim|required');
            $this->form_validation->set_rules('totalValue', 'Total Value', 'trim|required');
            $this->form_validation->set_rules('roundOff', 'Round Off', 'trim|required');
            $this->form_validation->set_rules('total', 'Total', 'trim|required');
            
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors()]);
                return;
            }

            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $data['EmployeeId'] = $this->session->userdata('EmployeeId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');

            $data['invoiceNumber'] = ucwords($this->input->post('invoiceNumber'));
            $data['invoiceDate'] = $this->input->post('invoiceDate');
            $data['voucherType'] = $this->input->post('voucherType');
            $data['customerId'] = ucfirst($this->input->post('customerId'));
            $data['customerName'] = get_title('parties', ['id' => $this->input->post('customerId')], 'name');
            $data['gstNo'] = ucfirst($this->input->post('gstNo'));
            $data['taxCode'] = $this->input->post('gstType');
            $data['customerAddress'] = $this->input->post('customerAddress');
            $data['customerCity'] = $this->input->post('customerCity');
            $data['state_name'] = $this->input->post('statename');
            $data['city_name'] = $this->input->post('cityCustomer');
            $data['customerState'] = $this->input->post('customerState');
            $data['customerMobile'] = $this->input->post('customerMobile');
            $data['customerEmail'] = $this->input->post('customerEmail');
            $data['customerBalance'] = $this->input->post('customerBalance');
            $data['amountInWords'] = ucwords($this->input->post('amountInWords'));
            $data['taxableAmount'] = $this->input->post('taxableAmount');
            $data['totalTax'] = $this->input->post('totalTax');
            $data['totalValue'] = $this->input->post('totalValue');
            $data['roundOff'] = $this->input->post('roundOff');
            $data['freight_charge'] = $this->input->post('freight_charge');
            $data['cash_discount'] = $this->input->post('cash_discount');
            $data['total'] = $this->input->post('total');
            $data['expenses'] = $this->input->post('expenses');
            $data['lrDate'] = $this->input->post('lrDate');
            $data['lrNumber'] = $this->input->post('lrNumber');
            $data['ewayBill'] = $this->input->post('ewayBill');
            $data['transport'] = $this->input->post('transport');
            $data['payTermsDay'] = $this->input->post('payTermsDay');
            $data['dueDate'] = $this->input->post('dueDate');
            $data['dcDate'] = $this->input->post('dcDate');
            $data['dcNumber'] = $this->input->post('dcNumber');
            $data['lot_no'] = $this->input->post('lot_no');
            $data['oldBalance'] = $this->input->post('oldBalance');
            $data['totalsgst'] = $this->input->post('totalsgst');
            $data['totalcgst'] = $this->input->post('totalcgst');
            $data['totaligst'] = $this->input->post('totaligst');
            $data['orderDate'] = $this->input->post('orderDate');
            $data['orderNumber'] = $this->input->post('orderNumber');
            $data['shipping_address'] = $this->input->post('shipping_hideen_address');
            $data['taxCal'] = $this->input->post('taxcal');
            $data['comp_id'] = $CompId;
            $data['CompanyBranchId'] = $CompanyBranchId;
            $data['user_id'] = $UserId;
            $data['vahical_no'] = $this->input->post('vahical_no');
            $data['lh'] = $this->input->post('lh');
            $data['cess'] = $this->input->post('cess');
            $data['remark'] = $this->input->post('remark');
            $data['p_code'] = $this->input->post('p_code');
            $data['format_invoice'] = $this->input->post('format_invoice');
            $data['product'] = json_encode($array_of_products);

            $parties = array(
                'name' => get_title('parties', ['id' => $this->input->post('customerId')], 'name'),
                'customer_id' => $this->input->post('customerId'),
                'address' => $this->input->post('customerAddress'),
                'city_id' => $this->input->post('customerCity'),
                'state_id' => $this->input->post('customerState'),
                'mobile_number' => $this->input->post('customerMobile'),
                'statename' => $this->input->post('statename'),
                'cityname' => $this->input->post('cityCustomer'),
                'gst_no' => ucfirst($this->input->post('gstNo')),
                'email' => $this->input->post('customerEmail')
            );
            
            $data['parties'] = json_encode($parties);
            $productids = [];
            foreach ($array_of_products as $row) {
                $productids[] = $row['product'];
            }

            $data['product_ids'] = implode(',', $productids);
            $data['num_of_items'] = $this->input->post('num_of_items');
            $data['updated'] = date('d-m-Y h:i:s A');
            $data['updated_by'] = $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name');
            $where = ['id' => $id];
            $there = ['invoiceID' => $id];
            
            $result = $this->user_model->updateAry('estimate_purchase', $data, $where);
            
            $invoice_pro = $this->user_model->getAry('estimate_product', '', '', '', $there);
            $sale_condition = ['invoiceID' => $id, 'type' => 'Purchase'];
            if ($invoice_pro) {
                foreach ($invoice_pro as $invoice) {
                    $this->user_model->deleteRow('estimate_product', $sale_condition);
                }
            }
            $resultProduct = $this->user_model->bulkEstimatePurchaseUp('estimate_product', $array_of_products, $id, $data['invoiceNumber'], $data['invoiceDate']);
            if (isset($result) && !empty($result) && !empty($resultProduct)) {
                if ($this->input->post('voucherType') == 1) {
                    if ($this->db->where('invoice_id', $id)->where('invoice_type', 'Estimate Purchase')->where('transaction_type', 'CR')->get('payment')->num_rows() > 0) {
                        $PaymentCredit = [
                            'party_id' => $this->input->post('customerId'),
                            'credit' => $this->input->post('total'),
                            'updated' => date('d-m-Y h:i:s A')
                        ];
                        $this->user_model->updateAry('payment', $PaymentCredit, ['invoice_id' => $id, 'invoice_type' => 'Estimate Purchase', 'transaction_type' => 'CR']);

                        $EstimateUpdate = [
                            'paid_amount' => $this->input->post('total')
                        ];
                        $this->user_model->updateAry('estimate_purchase', $EstimateUpdate, ['id' => $id]);
                    } else {
                        $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                        $unorder_where = ['comp_id'=> $CompId, 'user_id' => $UserId, 'status' => 1];
                        if ($this->session->userdata('BranchRole')) {
                            $SeriesWhere['series.CompanyBranchId'] = $CompanyBranchId;
                            $unorder_where['CompanyBranchId'] = $CompanyBranchId;
                        }
                        $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Estimate Voucher')->where($SeriesWhere)->get('seriestitle')->row();
                        $UnorderNumber = $this->db->select('id')->where($unorder_where)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                        $PaymentCredit = [
                            'voucher_type' => 'Estimate Voucher',
                            'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                            'date' => date('Y-m-d'),
                            'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                            'party_id' => $this->input->post('customerId'),
                            'invoice_id' => $id,
                            'invoice_type' => 'Estimate Purchase',
                            'credit' => $this->input->post('total'),
                            'transaction_type' => 'CR',
                            'payment_type' => 'Payment',
                            'paymentmode' => get_title('payment_mode', ['payment_method' => 'Cash'], 'id'),
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'CompanyBranchId' => $CompanyBranchId,
                            'financial_year' => get_title('estimate_purchase', ['id' => $invoiceID], 'financial_year'),
                            'created' => date('d-m-Y h:i:s A')
                        ];
                        $this->user_model->saveAry('payment', $PaymentCredit);
                        
                        $GetSeriesWhere = ['series_id' => $Series->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];                    
                        if ($this->session->userdata('BranchRole')) {
                            $GetSeriesWhere['CompanyBranchId'] = $CompanyBranchId;
                        }
                        $GetSeries = $this->user_model->getRow('series', $GetSeriesWhere);
                        if ($GetSeries) {
                            $SeriesNumber['starter_point'] = $GetSeries->starter_point + 1;
                            $this->user_model->updateAry('series', $SeriesNumber, $GetSeriesWhere);
                        }
                        
                        $EstimateUpdate = [
                            'paid_amount' => $this->input->post('total')
                        ];
                        $this->user_model->updateAry('estimate_purchase', $EstimateUpdate, ['id' => $id]);
                    }
                } else {
                    $PaymentDebit = [
                        'date' => $this->input->post('invoiceDate'),
                        'party_id' => $this->input->post('customerId'),
                        'debit' => $this->input->post('total'),
                        'updated' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->updateAry('payment', $PaymentDebit, ['invoice_id' => $id, 'invoice_type' => 'Estimate Purchase', 'transaction_type' => 'DR']);
                    $EstimateUpdate = [
                        'paid_amount' => NULL
                    ];
                    $this->user_model->updateAry('estimate_purchase', $EstimateUpdate, ['id' => $id]);
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Estimate Purchase', 'transaction_type' => 'CR', 'payment_type' => 'Payment']);
                }
                echo json_encode(['status' => true, 'data' => $id, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }       
        
	public function getInvoiceFromEstimatePurchaseByInvoiceNumber()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $EmployeeId = $this->session->userdata('EmployeeId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['estimate_purchase.invoiceNumber' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $Where['EmployeeId'] = $EmployeeId;
                }
                
                $this->db->select('*');
                $this->db->where($Where);
                $result = $this->db->get('estimate_purchase')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}
        
	public function getInvoiceFromEstimatePurchase()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $EmployeeId = $this->session->userdata('EmployeeId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['estimate_purchase.invoiceNumber' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $Where['EmployeeId'] = $EmployeeId;
                }
                
                $EstimatePurchaseData = $this->user_model->getRow('estimate_purchase', $Where);
                if (isset($EstimatePurchaseData) && !empty($EstimatePurchaseData)) {
                    $invoice_where = ['InvoiceID' => $party->id, 'comp_id' => $CompId, 'user_id' => $UserId, 'type' => "Purchase"];
                    if ($this->session->userdata('BranchRole')) {
                        $invoice_where['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $invoice_product = $this->user_model->getAry('estimate_product', '', '', '', $invoice_where);
                    $data['invoice_product'] = $invoice_product;
                    $data['PartyData'] = $this->user_model->getRow('parties', ['id' => $EstimatePurchaseData->customerId]);
                    $data['EstimatePurchaseData'] = $EstimatePurchaseData;
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}
        
	public function getInvoiceByEstimatePurchaseForPrevious()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $EmployeeId = $this->session->userdata('EmployeeId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['invoiceNumber < ' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $Where['EmployeeId'] = $EmployeeId;
                }
                
                $this->db->select('*');
                $this->db->where($Where);
                $this->db->order_by('id', 'DESC');
                $result = $this->db->get('estimate_purchase')->row();

                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function getInvoiceByEstimatePurchaseForNext()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
                return false;
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $EmployeeId = $this->session->userdata('EmployeeId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['invoiceNumber > ' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $Where['EmployeeId'] = $EmployeeId;
                }
                
                $this->db->select('*');
                $this->db->where($Where);
                $this->db->order_by('id', 'ASC');
                $result = $this->db->get('estimate_purchase')->row();

                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}
        
        public function getLastMaxPriceOfProductByEstimatePurchase ()
        {
            if ($_POST) {
                $product_id = $this->input->post('product_id');
                $party_id = $this->input->post('party_id');
                if ($product_id == null || $product_id == '' && $party_id == null || $party_id == '') {
                    echo json_encode(['status' => false, 'data' => 'Enter required fields']);
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                        $EmployeeId = $this->session->userdata('EmployeeId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompanyBranchId = $this->session->userdata('CompBranchId');

                    $Where = ['customerId' => $party_id, 'type' => 'Estimate Purchase', 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompanyBranchId;
                    }
                    if ($this->session->userdata('RoleType')) {
                        $Where['EmployeeId'] = $EmployeeId;
                    }
                    
                    $this->db->select('invoiceNumber');
                    $this->db->where($Where);
                    $this->db->order_by('id', 'DESC');
                    $this->db->limit(1);
                    $PurchaseResult = $this->db->get('estimate_purchase')->row();
                    
                    $ProductWhere = ['type' => 'Purchase', 'InvoiceNo' => $PurchaseResult->invoiceNumber, 'product_id' => $product_id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $ProductWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $this->db->select('MAX(rate) AS Rate');
                    $this->db->where($ProductWhere);
                    $this->db->order_by('id', 'DESC');
                    $this->db->limit(1);
                    $PartyRate = $this->db->get('estimate_product')->row();                       
                    if ($PartyRate) {
                        echo json_encode(['status' => true, 'data' => $PartyRate]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Not Found']);
                    }
                }
            }
        }
       
        public function report ($start = 0)
        {
            $invoiceNumber = '';
            $invoiceDate = '';
            $fromDate = '';
            $toDate = '';
            $partyName = '';
            $productName = '';
            $first_date = '';
            $last_date = '';
            $limit = '';
            $party = '';

            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
                $limit = '';
            }

            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
                $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
                $first_date = $this->input->get('fromDate');
                $last_date = $this->input->get('toDate');
                $limit = '';
            } else {
                $fromDate = ['invoiceDate >=' => date('Y-m-d')];
                $toDate = ['invoiceDate <=' => date('Y-m-d')];
                $limit = '';
            }

            $partyFilter = function_exists('input_get_array') ? input_get_array('party') : (array) ($this->input->get('party') ?: []);
            if (!empty($partyFilter) && !in_array('All', $partyFilter, true)) {
                $party = $partyFilter;
            }

            $sale_register = $this->input->get('sale_register') ?: '';
            
            $table = 'estimate_purchase';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $financial_year = $this->session->userdata('financial_year');
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $financial_year];
            $pwhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                $pwhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            }
            
            if ($sale_register == 'general') {
                if ($this->input->get('invoiceStatus') != 'All' && $this->input->get('invoiceStatus') != '') {
                    $where['status'] = $this->input->get('invoiceStatus');
                }
            } elseif ($sale_register != '') {
                $where['status'] = 1;
            }
            
            $result = $this->user_model->getTblerDate($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where, '', $party);
            $data['parties'] = $this->db->select('id, name')->where($pwhere)->order_by('id', 'ASC')->get('parties')->result();
            $data['fromDate'] = $first_date;
            $data['toDate'] = $last_date;
            $data['result'] = $result;
            
            if ($sale_register == "general") {
                $data['page'] = 'estimate_purchase/report/general';
            } elseif ($sale_register == "columnar") {
                $data['page'] = 'estimate_purchase/report/columnar';
            } elseif ($sale_register == "hsn") {
                $data['page'] = 'estimate_purchase/report/hsn';
            } elseif ($sale_register == "detail") {
                $data['page'] = 'estimate_purchase/report/details';
            } else {
                $data['page'] = 'estimate_purchase/report/index';
            }
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function view($id)
        {
            if (!$id) {
                return redirect(base_url() . 'Estimate_purchase');
            }
            $data['page'] = 'estimate_purchase/view';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimate_purchase', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'Purchase'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('estimate_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Estimate_purchase');
            }   
        }
        
        public function recycle($id)
        {
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate_purchase/report');
            }
            
            $EstimatePurchaseInfo = $this->user_model->getRow('estimate_purchase', ['id' => $id]);
            if ($EstimatePurchaseInfo->paid_amount != 0) {
                $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not delete this.', 'class' => 'danger']);
                return redirect('estimate_purchase/report');
            }
            
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
            }
            $data['status'] = 0;
            $return = $this->user_model->updateAry('estimate_purchase', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Purchase"];
                $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('estimate_product', $statusChange, $where);
                    }
                }
                $this->session->set_flashdata(['msg' => 'Estimate Deleted', 'class' => 'success']);
                return redirect('estimate_purchase/report');
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate_purchase/report');
            }
        }
        
        public function RecycleEstimatePurchase($id = null)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            
            $EstimatePurchaseInfo = $this->user_model->getRow('estimate_purchase', ['id' => $id]);
            if ($EstimatePurchaseInfo->paid_amount != 0) {
                echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not delete this.']);
                return;
            }
            
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
            }
            $data['status'] = 0;
            $return = $this->user_model->updateAry('estimate_purchase', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Purchase"];
                $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('estimate_product', $statusChange, $where);
                    }
                }
                echo json_encode(['status' => true, 'msg' => 'Estimate deleted successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
        
        public function delete($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            }
            $return = $this->user_model->deleteRow('estimate_purchase', $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Purchase"];
                $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                if ($res) {
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Estimate Purchase']);
                    foreach ($res as $da) {
                        $this->user_model->deleteRow('estimate_product', $where);
                    }
                }

                if (isset($res) && !empty($res)) {
                    echo json_encode(['status' => true, 'msg' => 'estimate deleted successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
        
        public function print($id)
        {
            if (!$id) {
                return redirect('estimate_purchase');
            }
            $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');

            if ($fromet == '4inch') {
                $data['page'] = 'inventory_sep/estimate_purchase/print/4inch';
            } else if ($fromet == 'A5') {
                $data['page'] = 'inventory_sep/estimate_purchase/print/a5';
            } else if ($fromet == 'Lh') {
                $data['page'] = 'inventory_sep/estimate_purchase/print/lh';
            } else if ($fromet == '3inch') {
                $data['page'] = 'inventory_sep/estimate_purchase/print/3inch';
            } else {
                $data['page'] = 'inventory_sep/estimate_purchase/print/a5';
            }

            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimate_purchase', $where);

            $pwhare = ['invoiceID' => $id, 'type' => 'Purchase'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('estimate_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate_purchase');
            }   
        }
        
        public function download($id)
        {
            
        }
        
        public function set_warehouse_for_product()
        {
            $this->load->view('inventory_sep/estimate_purchase/ChangeWarehouse');
        }
    }