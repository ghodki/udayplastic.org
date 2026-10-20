<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Purchase extends CI_Controller
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
            $role_data = $this->session->userdata('roles');
            if (isset($role_data)) {
                return redirect(base_url() . 'invent/purchase');
            }
        }

        public function purchase_report($start = 0)
        {
            $invoiceNumber = '';
            $invoiceDate = '';
            $toDate = '';
            $fromDate = '';
            $partyName = '';
            $productName = '';
            $limit = '';

            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
            }
            $financial_year = $this->session->userdata('financial_year');

            if ($this->input->get('sale_register')) {
                if ($this->input->get('sale_register') == 'general') {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $financial_year];
                    if ($this->input->get('invoiceStatus') && $this->input->get('invoiceStatus') != 'All') {
                        $where['status'] = $this->input->get('invoiceStatus');
                    }
                    $data['page'] = 'purchase_report/general';
                } elseif ($this->input->get('sale_register') == 'hsn') {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $financial_year, 'status' => 1];
                    $data['page'] = 'purchase_report/hsn';
                } elseif ($this->input->get('sale_register') == 'columnar') {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $financial_year, 'status' => 1];
                    $data['page'] = 'purchase_report/columnar';
                } elseif ($this->input->get('sale_register') == 'detailview') {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $financial_year, 'status' => 1];
                    $data['page'] = 'purchase_report/detailview';
                } else {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $financial_year];
                    $data['page'] = 'purchase_report/index';
                }
                $limit = '';
            } else {
                $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $financial_year];
                $data['page'] = 'purchase_report/index';
            }

            if ($this->input->get('invoiceDate')) {
                $invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
            }
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
                $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
            }

            $table = 'purchases';
            $order = ['col' => 'added_at', 'order' => 'DESC'];

            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }

            $data['result'] = $this->user_model->getTblerDate($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);            
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function pdfPurchase()
        {
            $data['page'] = 'purchase_report/index';
            $invoiceNumber = '';
            $invoiceDate = '';
            $toDate = '';
            $fromDate = '';
            $partyName = '';
            $productName = '';
            $sale_register = '';
            
            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
            }
            if ($this->input->get('sale_register')) {
                $sale_register = $this->input->get('sale_register');
            }

            if ($this->input->get('invoiceDate')) {
                $invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
            }
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
                $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
            }
            
            $table = 'purchases';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $result =  $this->user_model->getTblerDate($table, '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $data['result'] = $result;
            $data['sale_register'] = $sale_register;
            $data['page'] = 'purchase_report/print';
            $this->load->view('inventory_sep/inventory', $data);
        }

        // working
        public function save_purchase()
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
            $data['tcs'] = $this->input->post('tcs');
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
            $data['oldBalance'] = $this->input->post('oldBalance');
            $data['totalsgst'] = $this->input->post('totalsgst');
            $data['totalcgst'] = $this->input->post('totalcgst');
            $data['totaligst'] = $this->input->post('totaligst');
            $data['orderDate'] = $this->input->post('orderDate');
            $data['orderNumber'] = $this->input->post('orderNumber');
            $data['taxCal'] = $this->input->post('taxcal');
            $data['comp_id'] = $CompId;
            $data['user_id'] = $UserId;
            $data['CompanyBranchId'] = $CompanyBranchId;
            $data['financial_year'] = $FinancialYear;
            $data['cess'] = $this->input->post('cess');
            $data['remark'] = $this->input->post('remark');
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
            $data['createdBy'] = $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name');
            
            $result = $this->user_model->saveAry('purchases', $data);
                
            // invoice tabe data store 
            $this->user_model->bulkPurchaseUpdate('invoice_product', $array_of_products, $result, $data['invoiceNumber'], $data['invoiceDate']);
            // send_sms_for_sale($this->input->post('customerMobile'), $this->input->post('total'));
            if (isset($result) && !empty($result)) {
                $PaymentDedit = [
                    'date' => $this->input->post('invoiceDate'),
                    'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                    'party_id' => $this->input->post('customerId'),
                    'invoice_id' => $result,
                    'invoice_type' => 'Purchase',
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
                    $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $SeriesWhere['CompanyBranchId'] = $CompanyBranchId;
                        $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                    $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;
                    
                    $PaymentCredit = [
                        'voucher_type' => 'Tax',
                        'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                        'date' => date('Y-m-d'),
                        'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                        'party_id' => $this->input->post('customerId'),
                        'invoice_id' => $result,
                        'invoice_type' => 'Purchase',
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
                    
                    $PurchasesUpdate = [
                        'paid_amount' => $this->input->post('total')
                    ];
                    $this->user_model->updateAry('purchases', $PurchasesUpdate, ['id' => $result]);
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
                    $this->user_model->updateAry('purchases', $only_number_invoice, ['id' => $result]);
                }
                
                if (!empty($this->input->post('SaleInvoiceNumber')) && !empty($this->input->post('SaleId'))) {
                    $SaleId = $this->input->post('SaleId');
                    $SaleData = [
                        "PurchaseRequest" => 2,
                        "updated" => date('d-m-Y h:i:s A'),
                        "updated_by" => $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name')
                    ];
                    $this->user_model->updateAry('sales', $SaleData, ['id' => $SaleId]);
                    $this->user_model->updateAry('purchases', ['SaleId' => $SaleId], ['id' => $result]);
                }
                
                echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function purchaseEdit($id = null)
        {
            if (empty($id)) {
                $data['page'] = 'error';
                return $this->load->view('inventory_sep/inventory', $data);
            }

            $data['page'] = 'purchase/index';
            $result = $this->user_model->getRow('purchases', ['id' => $id]);
                
            if ($result->voucherType != 1) {
                if ($result->paid_amount != 0) {
                    $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not change this.', 'class' => 'danger']);
                    return redirect(base_url() . 'purchase');
                }
            }
            
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('purchase');
            }
        }
        
        // working
        public function update_purchase()
        {
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }
            $id = $this->input->post('printPurchase');
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
            $data['tcs'] = $this->input->post('tcs');
            $data['totalValue'] = $this->input->post('totalValue');
            $data['roundOff'] = $this->input->post('roundOff');
            $data['total'] = $this->input->post('total');
            $data['expenses'] = $this->input->post('expenses');
            // $data['vehicleNumber'] = $this->input->post('vehicleNumber');
            $data['lrDate'] = $this->input->post('lrDate');
            $data['lrNumber'] = $this->input->post('lrNumber');
            $data['ewayBill'] = $this->input->post('ewayBill');
            $data['transport'] = $this->input->post('transport');
            $data['payTermsDay'] = $this->input->post('payTermsDay');
            $data['dueDate'] = $this->input->post('dueDate');
            $data['dcDate'] = $this->input->post('dcDate');
            $data['dcNumber'] = $this->input->post('dcNumber');
            $data['oldBalance'] = $this->input->post('oldBalance');
            $data['totalsgst'] = $this->input->post('totalsgst');
            $data['totalcgst'] = $this->input->post('totalcgst');
            $data['totaligst'] = $this->input->post('totaligst');
            $data['orderDate'] = $this->input->post('orderDate');
            $data['taxCal'] = $this->input->post('taxcal');
            $data['comp_id'] = $CompId;
            $data['user_id'] = $UserId;
            $data['CompanyBranchId'] = $CompanyBranchId;
            $data['cess'] = $this->input->post('cess');
            $data['remark'] = $this->input->post('remark');
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
            $where = ['id' => $id];
            $condition_purchae = ['invoiceID' => $id, 'type' => 'Purchase'];
            
            $result = $this->user_model->updateAry('purchases', $data, $where);
            $invoice_pro = $this->user_model->getAry('invoice_product', '', '', '', $condition_purchae);

            if ($invoice_pro) {
                foreach ($invoice_pro as $invoice) {
                    $this->user_model->deleteRow('invoice_product', $condition_purchae);
                }
            }

            $resultProduct = $this->user_model->bulkPurchaseUpdate('invoice_product', $array_of_products, $id, $data['invoiceNumber'], $data['invoiceDate']);
            if (isset($result) && !empty($result) && !empty($resultProduct)) {
                if ($this->input->post('voucherType') == 1) {
                    if ($this->db->where('invoice_id', $id)->where('invoice_type', 'Purchase')->where('transaction_type', 'CR')->get('payment')->num_rows() > 0) {
                        $PaymentCredit = [
                            'party_id' => $this->input->post('customerId'),
                            'credit' => $this->input->post('total'),
                            'updated' => date('d-m-Y h:i:s A')
                        ];
                        $this->user_model->updateAry('payment', $PaymentCredit, ['invoice_id' => $id, 'invoice_type' => 'Purchase', 'transaction_type' => 'CR']);

                        $PurchaseUpdate = [
                            'paid_amount' => $this->input->post('total')
                        ];
                        $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $id]);
                    } else {
                        $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                        $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                        if ($this->session->userdata('BranchRole')) {
                            $SeriesWhere['CompanyBranchId'] = $CompanyBranchId;
                            $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId;
                        }
                        $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                        $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                        $PaymentCredit = [
                            'voucher_type' => 'Tax',
                            'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                            'date' => date('Y-m-d'),
                            'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                            'party_id' => $this->input->post('customerId'),
                            'invoice_id' => $id,
                            'invoice_type' => 'Purchase',
                            'credit' => $this->input->post('total'),
                            'transaction_type' => 'CR',
                            'payment_type' => 'Payment',
                            'paymentmode' => get_title('payment_mode', ['payment_method' => 'Cash'], 'id'),
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'CompanyBranchId' => $CompanyBranchId,
                            'financial_year' => get_title('purchases', ['id' => $id], 'financial_year'),
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

                        $PurchaseUpdate = [
                            'paid_amount' => $this->input->post('total')
                        ];
                        $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $id]);
                    }
                } else {
                    $PaymentDebit = [
                        'date' => date('Y-m-d', strtotime($this->input->post('invoiceDate'))),
                        'party_id' => $this->input->post('customerId'),
                        'debit' => $this->input->post('total'),
                        'updated' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->updateAry('payment', $PaymentDebit, ['invoice_id' => $id, 'invoice_type' => 'Purchase', 'transaction_type' => 'DR']);
                    $PurchaseUpdate = [
                        'paid_amount' => NULL
                    ];
                    $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $id]);
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Purchase', 'transaction_type' => 'CR', 'payment_type' => 'Payment']);
                }
                
                echo json_encode(['status' => true, 'data' => $id, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function unique_invoiceNumber_for_same_user()
        {
            $invoiceNumber = $this->input->post('invoiceNumber');
            $party_id = $this->input->post('customerId');

            if ((int)$party_id) {
                $where = ['invoiceNumber' => $invoiceNumber, 'customerId' => $party_id, 'status' => 1];
                $res = $this->user_model->getRow('purchases', $where);
                if ($res) {
                    $this->form_validation->set_message("Invoice number for same user already exists");
                    return FALSE;
                }
                return TRUE;
            }
        }

        public function viewpurchase($id)
        {
            if (!$id) {
                return redirect(base_url() . 'purchases');
            }
            $data['page'] = 'purchase/viewpurchase';
            $data['title'] = 'MIS | Purchase View';
            $data['menu'] = 'Purchase';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('purchases', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'Purchase'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('purchases');
            }
        }

        public function printPurchase($id)
        {
            if (!$id) {
                return redirect(base_url() . 'purchase');
            }
            $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');

            $where = ['id' => $id];
            $result = $this->user_model->getRow('purchases', $where);

            $pwhare = ['invoiceID' => $id, 'type' => 'Purchase'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                
                if ($fromet == '4inch') {
                    return $this->load->view('inventory_sep/sale/print4inch', $data);
                } else if ($fromet == 'A5') {
                    return $this->load->view('inventory_sep/purchase/a5print', $data);
                } elseif ($fromet == 'mc_formatA4') {
                    return $this->load->view('inventory_sep/purchase/mc_formatA4', $data);                    
                } else {
                    return $this->load->view('inventory_sep/purchase/a5print', $data);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'purchase');
            }
        }

        public function printPurchases($purchaseId)
        {
            if (!$purchaseId) {
                return redirect(base_url() . 'purchase');
            }

            $where = ['id' => $purchaseId];
            $result = $this->user_model->getRow('purchases', $where);

            $pwhare = ['invoiceID' => $purchaseId, 'type' => 'Purchase'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                
                $fileName = str_replace(' ', '_', get_title('parties', ['id' => $result->customerId], 'name')) . '_' . date('d-m-Y', strtotime($result->invoiceDate));
                
                $format =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
                if ($format == '4inch') {
                    $page = $this->load->view('inventory_sep/sale/print4inch', $data, true);
                } else if ($format == 'A5') {
                    $page = $this->load->view('inventory_sep/purchase/a5print', $data, true);
                } elseif ($format == 'mc_formatA4') {
                    $page = $this->load->view('inventory_sep/purchase/mc_formatA4', $data, true);                    
                } else {
                    $page = $this->load->view('inventory_sep/purchase/a5print', $data, true);
                }
                
                $this->load->library('pdf');
                $this->pdf->loadHtml($page);
                $this->pdf->set_option('isRemoteEnabled', TRUE);
                $this->pdf->setPaper('A4', 'portrait');
                $this->pdf->render();
                $this->pdf->output();
                $this->pdf->stream($fileName . ".pdf", array("Attachment" => true));
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'purchase');
            }
        }

        public function printPurchasePreview($id)
        {
            if (!$id) {
                return redirect('purchase');
            }
            $data['page'] = 'purchase/printPreview';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('purchases', $where);

            $pwhare = ['invoiceID' => $id, 'type' => 'Purchase'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'purchase');
            }
        }

		public function exportPurchase2()
		{
			// echo "hyy";
			// die;
			//excel export start
			$fileName = 'purchase12-' . time() . '.xlsx';
			// $fileName = 'purchase.xlsx';
			// $this->load->library("excel");

			// $this->load->library('excel');

			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load($fileName);
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);

			//column heading
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Sr No.');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'TYPE');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'BILLNO');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'BILLDATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'SUPPINVNO');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'SUPPINVDT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'C_CODE');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'NAME');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Address');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Place');
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Mobile');
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'CODE');
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'PRODUCT');
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'QTY');
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Basic Rate');
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'GST+Rate');
			$object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Employee Data.xls"');
			// $object_writer->save('php://output');
			$object_writer->save('aman.xl');
		}

		public function exportPurchase5()
		{

			$invoiceNumber = '';
			$invoiceDate = '';
			$fromDate = '';
			$toDate = '';
			$partyName = '';
			$productName = '';
			if ($this->input->get('invoiceNumber')) {
				$invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
			}
			if ($this->input->get('invoiceDate')) {
				$invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
			}
			if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
				$fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
				$toDate = ['invoiceDate <=' => $this->input->get('toDate')];
			}
			// if ($this->input->get('partyName')) {
			// 	$partyName = ['customerId' => $this->input->get('partyName')];
			// }
			// if ($this->input->get('productName')) {
			// 	$productName = ['product_ids' => $this->input->get('productName')];
			// }

			$table = 'purchases';
			$order = ['col' => 'added_at', 'order' => 'DESC'];

			$result =  $this->user_model->getTblerDate($table, '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName);

			//excel export start
			$fileName = 'purchase-' . time() . '.xlsx';
			// $fileName = 'purchase.xlsx';
			// $this->load->library("excel");
			$objPHPExcel = new PHPExcel();
			// echo '<pre>';
			// print_r($fileName);
			// die;
			$objPHPExcel->setActiveSheetIndex(0);

			//column heading
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Sr No.');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'TYPE');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'BILLNO');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'BILLDATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'SUPPINVNO');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'SUPPINVDT');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'C_CODE');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'NAME');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Address');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Place');
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Mobile');
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'CODE');
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'PRODUCT');
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'QTY');
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Basic Rate');
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'GST+Rate');
			$rowCount = 2;
			foreach ($result as $key => $val) {
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $key + 1);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "PURCHASE");
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val->invoiceNumber);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, date('d-m-Y', strtotime($val->invoiceDate)));
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val->invoiceNumber);
				// $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, date('d-m-Y', strtotime($val->added_at)));
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, get_title('parties', ['id' => $val->customerId], 'name'));
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $val->customerAddress);
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $val->customerCity);
				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $val->customerMobile);

				$rowCount++; //$rowCount, date('d-m-Y',strtotime($val->purchaseDate))
			}
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
			header('Content-Disposition: attachment; filename= "' . $fileName . '"');
			header("Cache-Control: max-age=0");
			$objWriter->save("php://output");
		}

		public function exportPurchase()
		{



			$data['page'] = 'purchase_report/index';
			$invoiceNumber = '';
			$invoiceDate = '';
			$toDate = '';
			$fromDate = '';
			$partyName = '';
			$productName = '';
			if ($this->input->get('invoiceNumber')) {
				$invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
			}
			if ($this->input->get('sale_register')) {
				$sale_register = $this->input->get('sale_register');
			}

			if ($this->input->get('invoiceDate')) {
				$invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
			}
			if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
				$fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
				$toDate = ['invoiceDate <=' => $this->input->get('toDate')];
			}
			// if($this->input->get('partyName')){
			//     $partyName = ['customerId'=>$this->input->get('partyName')];
			// }
			// if($this->input->get('productName')){
			//     $productName = ['product_ids'=>$this->input->get('productName')];
			// }
			$limit = 10;
			$table = 'purchases';
			$order = ['col' => 'added_at', 'order' => 'DESC'];
			$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                            $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
			$count =  $this->user_model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
			$result =  $this->user_model->getTblerDate($table, '', $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
			$data['result'] = $result;
			$data['count'] = $count;
			$data['sale_register'] = $sale_register;


			$fileName = 'sales-' . time() . '.xls';
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(40);

			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Invoice Date/Voucher Date *');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Invoice No/Voucher No *');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Product Description');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Part No(SKU)*');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Serial No*');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Quantity *');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Unit Price');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Discount%');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'HSN Code');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'GST%');
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Customer Name *');
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Customer City');
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Address');
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Tel./Mobile No');
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Customer GSTIN');
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Gross Amount');
			$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Item Description *');


			$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray(
				array(
					'font' => array(

						'color' => array('argb' => 'FF0000'),
					),
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '000000')
						)
					)

				)
			);

			$qty = 0;
			$taxable = 0;
			$taxamount = 0;
			$netvalue = 0;

			$rowCount = 2;
			$returnsale = 0;
			$saleproduct = 0;
			$returnpurchase = 0;
			$purchasepruduct = 0;

			$objPHPExcel = $this->load->view('exportexcel', $data, true);
			print_r($objPHPExcel);
		}
                
        // RECYCLE BIN 
        public function purchase_delete($id = null)
        {
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }

            $PurchaseInfo = $this->user_model->getRow('purchases', ['id' => $id]);
            if ($PurchaseInfo->paid_amount != 0) {
                $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not cancel this.', 'class' => 'danger']);
                return redirect('purchase/purchase_report');
            }

            $where = ['id' => $id];
            $data['status'] = 0;
            $return = $this->user_model->updateAry('purchases', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Purchase"];
                $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('invoice_product', $statusChange, $where);
                    }
                    $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Purchase', 'transaction_type' => 'DR']);
                }
                $this->session->set_flashdata(['msg' => 'Purchase Cancelled', 'class' => 'success']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }

        public function temprary_delete($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            
            $PurchaseInfo = $this->user_model->getRow('purchases', ['id' => $id]);
            if ($PurchaseInfo->paid_amount != 0) {
                echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not cancel this.']);
                return;
            }
            
            $where = ['id' => $id];
            $data['status'] = 0;
            $return = $this->user_model->updateAry('purchases', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Purchase"];
                $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('invoice_product', $statusChange, $where);
                    }
                    $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Purchase', 'transaction_type' => 'DR']);
                }
                echo json_encode(['status' => true, 'msg' => 'Purchase cancelled successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        // Delete From Recycle Bin
        public function purchase_deletes($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id];
            $return = $this->user_model->deleteRow('purchases', $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Purchase"];
                $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                if ($res) {
                    foreach ($res as $invoice) {
                        $this->user_model->deleteRow('invoice_product', $where);
                    }
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Purchase', 'transaction_type' => 'DR']);
                }
            }
            if ($return) {
                echo json_encode(['status' => true, 'msg' => 'Purchase cancelled successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
        
        public function getLastMaxPriceOfProductByPurchase ()
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
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $this->db->select('invoice_product.rate as Rate');
                    $this->db->join('invoice_product', 'purchases.id = invoice_product.invoiceID');
                    $this->db->where('purchases.user_id', $UserId);
                    $this->db->where('purchases.comp_id', $CompId);
                    if ($this->session->userdata('BranchRole')) {
                        $this->db->where('purchases.CompanyBranchId', $CompBranchId);
                    }
                    $this->db->where('purchases.customerId', $party_id);
                    $this->db->where('purchases.status', 1);
                    $this->db->where('invoice_product.product_id', $product_id);
                    $this->db->where('invoice_product.status', 1);
                    $this->db->where('invoice_product.type', 'Purchase');
                    $this->db->order_by('purchases.id', 'DESC');
                    $PartyRate = $this->db->get('purchases')->row();                       
                    if ($PartyRate) {
                        echo json_encode(['status' => true, 'data' => $PartyRate]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Not Found']);
                    }
                }
            }
        }
        
        public function set_warehouse_for_product()
        {
            $this->load->view('inventory_sep/purchase/ChangeWarehouse');
        }
        
        public function CreateInvoice($invoiceId) 
        {
                if (!empty($invoiceId)) {
                        $compId = $this->session->userdata('comp_id');
                        $loginUserId = $this->session->userdata('user_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        $branchInfo = $this->db->select('UserId, branchDivision, mainBranchId')->where(['Id' => $compBranchId, 'CompId' => $compId, 'IsDeleted' => 1])->get('companybranch')->row();
                        $financialYear = $this->session->userdata('financial_year');
               
                        $where = ['id' => $invoiceId, 'comp_id' => $compId, 'status' => 1, 'PurchaseRequest' => 1, 'financial_year' => $financialYear];
                        if ($branchInfo->branchDivision == 'Y') {
                                $mainBranchInfo = $this->db->select('Id, BranchName, BranchUserId')->where(['Id' => $branchInfo->mainBranchId, 'CompId' => $compId, 'IsDeleted' => 1])->get('companybranch')->row();
                                
                                $where['user_id'] = $mainBranchInfo->BranchUserId;
                                $where['CompanyBranchId'] = $mainBranchInfo->Id;
                                $creditorsPartyName = $mainBranchInfo->BranchName;
                        } elseif ($branchInfo->branchDivision == 'N') {
                                $where['user_id'] = $branchInfo->UserId;
                                $creditorsPartyName = get_title('inventory_billing', ['id' => $compId], 'company_name');
                        }
                        
                        if ($this->db->where($where)->get('sales')->num_rows()) {
                                $accountGroupId = get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id');
                                $partyWhere = ['name' => $creditorsPartyName, 'user_id' => $loginUserId, 'comp_id' => $compId, 'CompanyBranchId' => $compBranchId, 'group_head' => $accountGroupId];
                                $data['purchaseCustomerId'] = $this->db->select('id')->where($partyWhere)->get('parties')->row('id');
                                $data['SaleInfo'] = $this->db->where($where)->get('sales')->row();
                                $data['page'] = "purchase/CreateInvoice";
                                return $this->load->view('inventory_sep/inventory', $data);    
                        } else {
                                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                                return redirect(base_url() . 'Reports/PurchaseRequestReport');
                        }
                } else {
                        $this->session->set_flashdata(['msg' => 'Sale id is missing.', 'class' => 'danger']);
                        return redirect(base_url() . 'Reports/PurchaseRequestReport');
                }
        }
        
        public function getSaleProductsForPurchaseInvoice() 
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $saleId = $this->input->post('SaleId');
                        if (!empty($saleId)) {
                                if ($this->session->userdata('RoleType')) {
                                        $branchUserId = $this->session->userdata('UserId');
                                        $compId = $this->session->userdata('CompId');
                                } else {
                                        $branchUserId = $this->session->userdata('user_id');
                                        $compId = $this->session->userdata('comp_id');
                                }
                                $financialYear = $this->session->userdata('financial_year');
                                $compBranchId = $this->session->userdata('CompBranchId');
                                
                                $branchInfo = $this->db->select('UserId, branchDivision, mainBranchId')->where(['Id' => $compBranchId, 'BranchUserId' => $branchUserId, 'CompId' => $compId, 'IsDeleted' => 1])->get('companybranch')->row();
                    
                                $saleProductWhere = ['invoiceID' => $saleId, 'type' => 'Sale', 'status' => 1, 'comp_id' => $compId, 'financial_year' => $financialYear];                                
                                if ($branchInfo->branchDivision == 'Y') {
                                        $mainBranchIno = $this->db->select('Id, BranchUserId')->where(['Id' => $branchInfo->mainBranchId, 'CompId' => $compId, 'IsDeleted' => 1])->get('companybranch')->row();
                                        $saleProductWhere['user_id'] = $mainBranchIno->BranchUserId;
                                        $saleProductWhere['CompanyBranchId'] = $mainBranchIno->Id;
                                } elseif ($branchInfo->branchDivision == 'N') {
                                        $saleProductWhere['user_id'] = $branchInfo->UserId;
                                }
                                $warehouseWhere = ['user_id' => $branchUserId, 'comp_id' => $compId];
                                if ($this->session->userdata('BranchRole')) {
                                        $warehouseWhere['CompanyBranchId'] = $compBranchId;
                                }
                    
                                $saleProductInfo = $this->db->select('productname, qty, rate')->where($saleProductWhere)->get('invoice_product')->result();
                                $productArray = [];
                                foreach ($saleProductInfo as $key => $products) {
                                        $productWhere = ['p_name' => $products->productname, 'CompanyBranchId' => $compBranchId, 'user_id' => $branchUserId, 'comp_id' => $compId];
                                        $productInfo = $this->db->where($productWhere)->get('product')->row();
                                        $productArray[] = array(
                                                "p_code" => $productInfo->qrCode,
                                                "productname" => $productInfo->p_name,
                                                "product_id" => $productInfo->id,
                                                "product_dec" => $productInfo->short_de,
                                                "hsn" => $productInfo->hsn,
                                                "qty" => $products->qty,
                                                "discount" => 0,
                                                "rate" => $products->rate,
                                                "tax" => $productInfo->tax_qty,
                                                "warehouse" => get_title('comp_profile', $warehouseWhere, 'warehouse')
                                        );
                                }

                                if (is_array($productArray)) {
                                        echo json_encode(['Status' => true, 'ProductDataArray' => $productArray]);
                                } else {
                                        echo json_encode(['Status' => false, 'ProductDataArray' => 'No record found.']);
                                }
                        } else {
                                echo json_encode(['Status' => false, 'ProductDataArray' => 'Sale id missing.']);
                        }
                }
        }
        
        public function PurchaseCancel($saleId) 
        {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        if (!$saleId) {
                                echo json_encode(['Status' => false, 'Message' => 'Id not found.']);
                                return;
                        }
                        if ($this->session->userdata('RoleType')) {
                                $userId = $this->session->userdata('UserId');
                                $compId = $this->session->userdata('CompId');
                        } else {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                        }
                        $financialYear = $this->session->userdata('financial_year');
                
                        $where = ['id' => $saleId, 'comp_id' => $compId, 'status' => 1, 'financial_year' => $financialYear];
                
                        $saleResult = $this->user_model->updateAry('sales', ['PurchaseRequest' => 0], $where);
                        if ($saleResult) {
                                echo json_encode(['Status' => true, 'Message' => 'Purchase cancelled successfully.']);
                                return;
                        } else {
                                echo json_encode(['Status' => false, 'Message' => 'There is some issue, Please try again.']);
                                return;
                        }
                }
        }
        
        public function ExcelDataToPurchaseInvoice() 
        {            
            $data['page'] = 'purchase/ExcelDataToPurchaseInvoice';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function CreatePurchaseInvoices() 
        {
            ini_set('memory_limit', '-1');
            set_time_limit(-1);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES["ExcelPurchaseData"]["name"]) && $_FILES["ExcelPurchaseData"]["name"] != "") {
                    $FileType = $_FILES["ExcelPurchaseData"]["type"];
                    if ($FileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $FileType == 'application/vnd.ms-excel' || $FileType == 'application/vnd.ms-office') {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                        $CompBranchId = $this->session->userdata('CompBranchId');
                        
                        $ExcelFile = $_FILES["ExcelPurchaseData"]["tmp_name"];

                        try {
                            $InputFileType = PHPExcel_IOFactory::identify($ExcelFile);
                            $ObjectReader = PHPExcel_IOFactory::createReader($InputFileType);
                            $ObjectPHPExcel = $ObjectReader->load($ExcelFile);

                            $SheetCount = $ObjectPHPExcel->getSheetCount();
                            $SheetData = [];
                            
                            for ($i = 0; $i < $SheetCount; $i++) {
                                $Sheet = $ObjectPHPExcel->setActiveSheetIndex($i);
                                $SheetName = $ObjectPHPExcel->getSheetNames()[$i];
                                $SheetData[$SheetName] = $Sheet->toArray(null, true, true, true);
                            }

                            $CustomerIds = [];
                            $PurchaseIds = [];
                            $NewProductIds = [];
                            $InvoiceProductIds = [];
                            $PaymentIds = [];
                            foreach ($SheetData as $SheetName => $Sheet) {
                                if ($SheetName == 'Customers') {
                                    foreach ($Sheet as $index => $row) {
                                        if (!empty($row) && array_filter($row)) {
                                            if ($index === 1) continue;
                                            
                                            $StateId = get_title('state_list', ['state' => $row['J']], 'id');
                                            $StateCode = get_title('state_list', ['state' => $row['J']], 'code');
                                            if ($this->db->select('name')->where('name', $row['K'])->get('districts')->num_rows()) {
                                                $CityId = $this->db->select('id')->where('name', $row['K'])->get('districts')->row('id');
                                            } else {
                                                $CityId = $this->user_model->saveAry('districts', ['state_id' => $StateId, 'name' => $row['K']]);
                                            }
                                            
                                            $PartyWhere = ['name' => $row['B'], 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'group_head' => get_title('accounting_group', ['name' => 'Sundry Creditors', 'status' => 1], 'id')];
                                            if ($this->session->userdata('BranchRole')) {
                                                $PartyWhere['CompanyBranchId'] = $CompBranchId;
                                            }
                                            
                                            if ($this->db->select('name')->where($PartyWhere)->get('parties')->num_rows() == 0) {
                                                $CustomerData = [
                                                    'group_head' => get_title('accounting_group', ['name' => 'Sundry Creditors', 'status' => 1], 'id'),
                                                    'name' => $row['B'],
                                                    'gst' => $row['C'],
                                                    'gstType' => $row['C'] ? 'Regular' : 'N/A',
                                                    'contact' => $row['D'],
                                                    'email' => $row['E'],
                                                    'pan' => $row['F'],
                                                    'address_1' => $row['G'],
                                                    'address_2' => $row['H'],
                                                    'area' => $row['I'],
                                                    'state' => $StateId,
                                                    'state_code' => $StateCode,
                                                    'city' => $CityId,
                                                    'pin' => $row['L'],
                                                    'opBalnceType' => 'CR',
                                                    'user_id' => $UserId,
                                                    'comp_id' => $CompId,
                                                    'CompanyBranchId' => $CompBranchId,
                                                    'opening_balance' => $row['M'],
                                                    'added_at' => $row['N']
                                                ];
                                                $CustomerIds[] = $this->user_model->saveAry('parties', $CustomerData);
                                            }
                                        }
                                    }
                                    unset($StateId);
                                    unset($StateCode);
                                    unset($CityId);
                                    unset($row);
                                } elseif ($SheetName == 'Invoices') {
                                    foreach ($Sheet as $index => $row) {
                                        if (!empty($row) && array_filter($row)) {
                                            if ($index === 1) continue;
                                            
                                            $InvoiceDate = date('Y-m-d', strtotime(PHPExcel_Style_NumberFormat::toFormattedString($row['B'], 'YYYY-MM-DD')));
                                            
                                            if ($row['D'] == 'Cash') {
                                                $VoucherType = 1;
                                            } elseif ($row['D'] == 'Credit') {
                                                $VoucherType = 2;
                                            } else {
                                                $VoucherType = $row['D'];
                                            }
                                            
                                            $PartyWhere = ['name' => $row['E'], 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'group_head' => get_title('accounting_group', ['name' => 'Sundry Creditors', 'status' => 1], 'id')];
                                            $PurchaseInvoiceWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            if ($this->session->userdata('BranchRole')) {
                                                $PartyWhere['CompanyBranchId'] = $CompBranchId;
                                                $PurchaseInvoiceWhere['CompanyBranchId'] = $CompBranchId;
                                            }
                                            
                                            $CustomerInfo = $this->db->select('id, name, contact, email, gst, address_1, city, state')->where($PartyWhere)->get('parties')->row();
                                            
                                            $CityName = get_title('districts', ['id' => $CustomerInfo->city], 'name');
                                            $StateName = get_title('state_list', ['id' => $CustomerInfo->state], 'state');
                                            
                                            $JSONCustomerData = array(
                                                'customer_id' => $CustomerInfo->id,
                                                'name' => $CustomerInfo->name,
                                                'mobile_number' => $CustomerInfo->contact,
                                                'email' => $CustomerInfo->email,
                                                'gst_no' => $CustomerInfo->gst,
                                                'address' => $CustomerInfo->address_1,
                                                'city_id' => $CustomerInfo->city,
                                                'cityname' => $CityName,
                                                'state_id' => $CustomerInfo->state,
                                                'statename' => $StateName
                                            );
                                            
                                            $PurchaseInvoiceWhere['invoiceNumber'] = $row['C'];
                                            if ($this->db->select('id')->where($PurchaseInvoiceWhere)->get('purchases')->num_rows() > 0) continue;
                                            
                                            $PurchaseData = [
                                                'financial_year' => get_title('year', ['year' => $row['P']], 'id'),
                                                'invoiceDate' => $InvoiceDate,
                                                'invoiceNumber' => $row['C'],
                                                'customerId' => $CustomerInfo->id,
                                                'customerName' => $CustomerInfo->name,
                                                'customerCity' => $CustomerInfo->city,
                                                'city_name' => $CityName,
                                                'customerMobile' => $CustomerInfo->contact,
                                                'customerState' => $CustomerInfo->state,
                                                'state_name' => $StateName,
                                                'customerEmail' => $CustomerInfo->email,
                                                'customerAddress' => $CustomerInfo->address_1,
                                                'gstNo' => $CustomerInfo->gst,
                                                'parties' => json_encode($JSONCustomerData),
                                                'format_invoice' => 'Tax',
                                                'voucherType' => $VoucherType,
                                                'taxCal' => 1,
                                                'user_id' => $UserId,
                                                'comp_id' => $CompId,
                                                'CompanyBranchId' => $CompBranchId,
                                                'taxableAmount' => $row['F'],
                                                'totalTax' => $row['G'],
                                                'tcs' => $row['H'],
                                                'totalsgst' => $row['I'],
                                                'totalcgst' => $row['J'],
                                                'totaligst' => $row['K'],
                                                'amountInWords' => amount_in_words($row['L']),
                                                'totalValue' => $row['L'],
                                                'roundOff' => $row['M'],
                                                'total' => $row['N'],
                                                'type' => 'Purchase',
                                                'remark' => $row['O'],
                                                'created' => date('d-m-Y h:i:s A'),
                                                'createdBy' => get_title('users', ['id' => $UserId], 'name')
                                            ];
                                            $PurchaseIdData = $this->user_model->saveAry('purchases', $PurchaseData);
                                            $PurchaseIds[] = $PurchaseIdData;
                                            
                                            if (isset($PurchaseIdData) && !empty($PurchaseIdData)) {
                                                $PaymentDebit = [
                                                    'date' => $InvoiceDate,
                                                    'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                                                    'party_id' => $CustomerInfo->id,
                                                    'invoice_id' => $PurchaseIdData,
                                                    'invoice_type' => 'Purchase',
                                                    'debit' => $row['N'],
                                                    'transaction_type' => 'DR',
                                                    'user_id' => $UserId,
                                                    'comp_id' => $CompId,
                                                    'CompanyBranchId' => $CompBranchId,
                                                    'financial_year' => get_title('year', ['year' => $row['P']], 'id'),
                                                    'created' => date('d-m-Y h:i:s A')
                                                ];
                                                $this->user_model->saveAry('payment', $PaymentDebit);
                                            }
                                        }
                                    }
                                    unset($InvoiceDate);
                                    unset($VoucherType);
                                    unset($PartyWhere);
                                    unset($CustomerInfo);
                                    unset($CityName);
                                    unset($StateName);
                                    unset($PurchaseIdData);
                                    unset($PurchaseInvoiceWhere);
                                    unset($row);
                                } elseif ($SheetName == 'InvoiceProducts') {
                                    foreach ($Sheet as $index => $row) {
                                        if (!empty($row) && array_filter($row)) {
                                            if ($index === 1) continue;
                                            
                                            $WarehouseWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            $ProductWhere = ['p_name' => $row['D'], 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            $ProductCodeWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            $PurchaseInvoiceProductWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                               $categoryWhere = ['name' => $row['E'], 'userId' => $UserId, 'compId' => $CompId, 'isDeleted' => 1];
                                               $brandWhere = ['name' => $row['F'], 'userId' => $UserId, 'compId' => $CompId, 'isDeleted' => 1];                                            
                                            if ($this->session->userdata('BranchRole')) {
                                                $WarehouseWhere['CompanyBranchId'] = $CompBranchId;
                                                $ProductWhere['CompanyBranchId'] = $CompBranchId;
                                                $ProductCodeWhere['CompanyBranchId'] = $CompBranchId;
                                                $PurchaseInvoiceProductWhere['CompanyBranchId'] = $CompBranchId;
                                                $categoryWhere['compBranchId'] = $CompBranchId;
                                                $brandWhere['compBranchId'] = $CompBranchId;                                                
                                            }
                                            $ProductCode = ($this->db->select('id')->where($ProductCodeWhere)->order_by('id', 'DESC')->get('product')->row('id') + 1);
                                            
                                            if ($this->db->select('id')->where('tax_per', $row['M'])->get('tax')->num_rows()) {
                                                $TaxId = $this->db->select('id')->where('tax_per', $row['M'])->get('tax')->row('id');
                                            } else {
                                                $TaxName = 'GST @ ' . $row['M'] . '%';
                                                $TaxId = $this->user_model->saveAry('tax', ['name' => $TaxName, 'tax_per' => $row['M'], 'igst' => $row['M'], 'cgst' => ($row['M'] / 2), 'sgst' => ($row['M'] / 2), 'cess' => 0]);
                                                
                                            }
                                            $TaxData = $this->db->select('id, tax_per')->where('id', $TaxId)->get('tax')->row();
                                            
                                            if ($this->db->select('id')->where('name', $row['L'])->get('unit')->num_rows()) {
                                                $UnitId = $this->db->select('id')->where('name', $row['L'])->get('unit')->row('id');
                                            } else {
                                                $UnitId = $this->user_model->saveAry('unit', ['name' => $row['L'], 'uqc_code' => $row['L']]);
                                            }
                                            $UnitData = $this->db->select('id, name')->where('id', $UnitId)->get('unit')->row();
                                            
                                            if ($this->db->select('id')->where($WarehouseWhere)->get('warehouse')->num_rows()) {
                                                $WarehouseId = $this->db->select('id')->where($WarehouseWhere)->get('warehouse')->num_rows('id');
                                            } else {
                                                $WarehouseId = $this->user_model->saveAry('warehouse', ['name' => 'Main', 'user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId]);
                                            }
                                            
                                               if ($this->db->select('id')->where($categoryWhere)->get('category')->num_rows()) {
                                                     $categoryId = $this->db->select('id')->where($categoryWhere)->get('category')->row('id');
                                               } else {
                                                       $categoryData = [
                                                               'name' => $row['E'],
                                                               'userId' => $UserId,
                                                               'compId' => $CompId,
                                                               'compBranchId' => $CompBranchId,
                                                               'created' => date('d-m-Y h:i:s A'),
                                                               'createdBy' => get_title('users', ['id' => $UserId], 'name')
                                                       ];
                                                       $categoryId = $this->user_model->saveAry('category', $categoryData);
                                               }
                                            
                                               if ($this->db->select('id')->where($brandWhere)->get('brand')->num_rows()) {
                                                     $brandId = $this->db->select('id')->where($brandWhere)->get('brand')->row('id');
                                               } else {
                                                       $brandData = [
                                                               'name' => $row['F'],
                                                               'userId' => $UserId,
                                                               'compId' => $CompId,
                                                               'compBranchId' => $CompBranchId,
                                                               'created' => date('d-m-Y h:i:s A'),
                                                               'createdBy' => get_title('users', ['id' => $UserId], 'name')
                                                       ];
                                                       $brandId = $this->user_model->saveAry('brand', $brandData);
                                               }                                            
                                            
                                            if ($this->db->select('p_name')->where($ProductWhere)->get('product')->num_rows()) {
                                                $ProductId = $this->db->select('id')->where($ProductWhere)->get('product')->row('id');
                                            } else {
                                                $ProductData = [
                                                    'p_name' => $row['D'],
                                                    'price' => $row['G'],
                                                    'purchase_rate' => $row['I'],
                                                    'mrp' => $row['J'],
                                                    'hsn' => $row['K'],
                                                    'unit' => $UnitData->id,
                                                    'unit_name' => $UnitData->name,
                                                    'tax' => $TaxData->id,
                                                    'tax_qty' => $TaxData->tax_per,
                                                    'qrCode' => $ProductCode,
                                                    'user_id' => $UserId,
                                                    'comp_id' => $CompId,
                                                    'CompanyBranchId' => $CompBranchId,
                                                    'warehouse' => $WarehouseId,
                                                    'categoryId' => $categoryId,
                                                    'brandId' => $brandId                                                    
                                                ];
                                                $ProductId = $this->user_model->saveAry('product', $ProductData);
                                                $NewProductIds[] = $ProductId;
                                            }
                                            $ProductInfo = $this->db->select('id, p_name, qrCode, short_de, tax_qty, hsn, warehouse')->where('id', $ProductId)->get('product')->row();

                                            $JSONProductData = array(
                                                'product' => $ProductInfo->id,
                                                'productname' => $ProductInfo->p_name,
                                                'p_code' => $ProductInfo->qrCode,
                                                'product_dec' => $ProductInfo->short_de,
                                                'hsn' => $ProductInfo->hsn,
                                                'sqft' => "",
                                                'qty' => $row['N'],
                                                'rate' => $row['H'],
                                                'discount' => 0,
                                                'cd' => 0,
                                                'tax' => $ProductInfo->tax_qty,
                                                'price' => $row['R'],
                                                'amount' => $row['S'],
                                                'taxAmount' => $row['S'],
                                                'sgst' => $row['O'],
                                                'cgst' => $row['P'],
                                                'igst' => $row['Q'],
                                                'netAmount' => $row['T'],
                                                'warehouse' => $WarehouseId
                                            );
                                            
                                            $PurchaseInvoiceProductWhere['invoiceNumber'] = $row['C'];
                                            if ($this->db->select('id')->where($PurchaseInvoiceProductWhere)->get('purchases')->num_rows() > 0) {
                                                $PurchaseId = $this->db->select('id')->where($PurchaseInvoiceProductWhere)->get('purchases')->row('id');
                                            
                                                $InvoiceProductData = [
                                                    'invoiceDate' => date('Y-m-d', strtotime(PHPExcel_Style_NumberFormat::toFormattedString($row['B'], 'YYYY-MM-DD'))),
                                                    'invoiceID' => $PurchaseId,
                                                    'InvoiceNo' => $row['C'],
                                                    'type' => 'Purchase',
                                                    'productname' => $ProductInfo->p_name,
                                                    'rate' => $row['H'],
                                                    'qty' => $row['N'],
                                                    'hsn' => $ProductInfo->hsn,
                                                    'tax' => $ProductInfo->tax_qty,
                                                    'sgst' => $row['O'],
                                                    'cgst' => $row['P'],
                                                    'igst' => $row['Q'],
                                                    'purchase_price' => $row['R'],
                                                    'price' => $row['R'],
                                                    'amount' => $row['S'],
                                                    'taxAmount' => $row['S'],
                                                    'netAmount' => $row['T'],
                                                    'product' => $ProductInfo->id,
                                                    'product_id' => $ProductInfo->id,
                                                    'p_code' => $ProductInfo->qrCode,
                                                    'product_details' => json_encode($JSONProductData),
                                                    'warehouse' => $WarehouseId,
                                                    'user_id' => $UserId,
                                                    'comp_id' => $CompId,
                                                    'CompanyBranchId' => $CompBranchId,
                                                    'financial_year' => get_title('year', ['year' => $row['U']], 'id')
                                                ];
                                                $InvoiceProductIds[] = $this->user_model->saveAry('invoice_product', $InvoiceProductData);

                                                $NumOfItems = $this->db->select('id')->where('invoiceID', $PurchaseId)->where('type', 'Purchase')->get('invoice_product')->num_rows();
                                                $ProductsIdsData = $this->db->select('product_id')->where('invoiceID', $PurchaseId)->where('type', 'Purchase')->get('invoice_product')->result();
                                                $ProductIds = [];
                                                foreach ($ProductsIdsData as $key => $ProductIdInfo) {
                                                    $ProductIds[] = $ProductIdInfo->product_id;
                                                }

                                                $this->user_model->updateAry('purchases', ['product' => json_encode($JSONProductData), 'product_ids' => implode(',', $ProductIds), 'num_of_items' => $NumOfItems], ['id' => $PurchaseId]);
                                            }
                                        }
                                    }
                                    unset($WarehouseWhere);
                                    unset($ProductWhere);
                                    unset($ProductCodeWhere);
                                    unset($PurchaseInvoiceProductWhere);
                                    unset($ProductCode);
                                    unset($TaxData);
                                    unset($TaxName);
                                    unset($TaxId);
                                    unset($UnitData);
                                    unset($UnitId);
                                    unset($WarehouseId);
                                    unset($ProductInfo);
                                    unset($ProductId);
                                    unset($NumOfItems);
                                    unset($ProductsIdsData);
                                    unset($PurchaseId);
                                    unset($row);
                                } elseif ($SheetName == 'Payments') {
                                    foreach ($Sheet as $index => $row) {
                                        if (!empty($row) && array_filter($row)) {
                                            if ($index === 1) continue;
                                            
                                            $VoucherNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                                            $PurchaseWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                                            $PaymentWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1, 'payment_type' => 'Payment'];
                                            if ($this->session->userdata('BranchRole')) {
                                                $VoucherNumberWhere['CompanyBranchId'] = $CompBranchId;
                                                $PurchaseWhere['CompanyBranchId'] = $CompBranchId;
                                                $PaymentWhere['CompanyBranchId'] = $CompBranchId;
                                            }
                                            $VoucherNumber = ($this->db->select('id')->where($VoucherNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1);
                                            
                                            $PurchaseWhere['invoiceNumber'] = $row['C'];
                                            $InvoiceData = $this->db->select('id, customerId, type, total')->where($PurchaseWhere)->get('purchases')->row();
                                            $ChequeDate = $row['G'] ? date('Y-m-d', strtotime(PHPExcel_Style_NumberFormat::toFormattedString($row['G'], 'YYYY-MM-DD'))) : "";

                                            if (empty($this->session->userdata('BranchRole'))) {
                                                $Sql = "SELECT SUM(credit) AS credit FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$InvoiceData->id\"]')";
                                            } else {
                                                $Sql = "SELECT SUM(credit) AS credit FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompBranchId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$InvoiceData->id\"]')";
                                            }
                                            $Query = $this->db->query($Sql);
                                            $CreditAmount = $Query->row('credit');
                                            
                                            if ($CreditAmount == $InvoiceData->total) continue;
                                            
                                            $PaymentData = [
                                                'voucher_type' => 'Tax',
                                                'voucher_number' => $VoucherNumber,
                                                'date' => date('Y-m-d', strtotime(PHPExcel_Style_NumberFormat::toFormattedString($row['B'], 'YYYY-MM-DD'))),
                                                'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                                                'party_id' => $InvoiceData->customerId,
                                                'invoice_id' => json_encode(array($InvoiceData->id)),
                                                'invoice_type' => json_encode(array($InvoiceData->type)),
                                                'receivedPaidAmount' => $row['D'],
                                                'cashDiscountAmount' => $row['E'],
                                                'credit' => ($row['D'] + $row['E']),
                                                'transaction_type' => 'CR',
                                                'payment_type' => 'Payment',
                                                'paymentmode' => get_title('payment_mode', ['payment_method' => $row['F']], 'id'),
                                                'ChequeDate' => $ChequeDate,
                                                'payment_through' => "Manually",
                                                'remark' => $row['H'],
                                                'user_id' => $UserId,
                                                'comp_id' => $CompId,
                                                'CompanyBranchId' => $CompBranchId,
                                                'financial_year' => get_title('year', ['year' => $row['I']], 'id'),
                                                'AmountToPaidInInvoice' => json_encode(array(($row['D'] + $row['E']))),
                                                'created' => date('d-m-Y h:i:s A')
                                            ];
                                            $PaymentIds[] = $this->user_model->saveAry('payment', $PaymentData);
                                            $this->user_model->updateAry('purchases', ['paid_amount' => ($row['D'] + $row['E'])], ['id' => $InvoiceData->id]);
                                        }
                                    }
                                    unset($VoucherNumberWhere);
                                    unset($PurchaseWhere);
                                    unset($PaymentWhere);
                                    unset($VoucherNumber);
                                    unset($InvoiceData);
                                    unset($CreditAmount);
                                    unset($ChequeDate);
                                }
                            }
                            
                            $data['NewCustomers'] = count($CustomerIds);
                            $data['NewInvoices'] = count($PurchaseIds);
                            $data['NewProducts'] = count($NewProductIds);
                            $data['NewInvoiceProducts'] = count($InvoiceProductIds);
                            $data['NewPayments'] = count($PaymentIds);
                            $data['page'] = 'Purchase/ExcelDataToPurchaseInvoice';
                            return $this->load->view('inventory_sep/inventory', $data);
                        } catch (Exception $e) {
                            die('Error loading file "' . pathinfo($ExcelFile, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Please choose excel file for import data', 'class' => 'danger']);
                        return redirect(base_url() . 'Purchase/ExcelDataToPurchaseInvoice');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Please choose a file to upload.', 'class' => 'danger']);
                    return redirect(base_url() . 'Purchase/ExcelDataToPurchaseInvoice');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something Went wrong, try again', 'class' => 'danger']);
                return redirect(base_url() . 'Purchase/ExcelDataToPurchaseInvoice');
            }
        }
    }