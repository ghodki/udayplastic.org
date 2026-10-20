<?php
    defined('BASEPATH') or exit('No direct script access allowed');    
    date_default_timezone_set('Asia/Kolkata');
    
    class Estimate extends CI_Controller
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
                return redirect(base_url() . 'invent/estimate');
            }
        }

        public function estimatelist()
        {
            $comp_id = $this->session->userdata('comp_id');
            $user_id = $this->session->userdata('user_id');            
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $row['comp_id'] = $this->session->userdata('comp_id');
            $row['user_id'] = $this->session->userdata('user_id');
            $estimate_where = ['user_id' => $user_id, 'comp_id' => $comp_id];
            if ($this->session->userdata('BranchRole')) {
                $estimate_where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            }
            $data['result'] = $this->user_model->getAry('estimates', $order, '', '', $estimate_where);
            $data['page'] = 'inventory_sep/estimate/view';
            
            return $this->load->view('inventory', $data);
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

            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $CompProfileWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
            if ($this->session->userdata('BranchRole')) {
                $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
            }

            if (get_title('comp_profile', $CompProfileWhere, 'ProductStockCheck') == "Y") {
                foreach ($product as $rowData) {
                    if (ProductBalanceQuanity($rowData['product']) < $rowData['qty']) {
                        $Massage = $rowData['productname'] . ' product entered quantity not in stock.';
                        echo json_encode(['status' => false, 'msg' => $Massage]);
                        return;
                    }
                }
            }
            
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required|trim');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Duplicate Invoice number for this company , Please try again.']);
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
            
                $shippingInfo = array(
                        'shippingName' => $this->input->post('shippingNameHidden'),
                        'shippingEmail' => $this->input->post('shippingEmailHidden'),
                        'shippingMobile' => $this->input->post('shippingMobileHidden'),
                        'shippingGSTType' => $this->input->post('shippingGSTTypeHidden'),
                        'shippingGST' => $this->input->post('shippingGSTHidden'),
                        'shippingState' => $this->input->post('shippingStateHidden'),
                        'shippingStateCode' => get_title('state_list', ['id' => $this->input->post('shippingStateHidden')], 'code'),
                        'shippingAddress' => $this->input->post('shippingAddressHidden'),
                        'shippingCity' => $this->input->post('shippingCityHidden'),
                        'shippingZip' => $this->input->post('shippingZipHidden'),
                );
                $data['shippingInfo'] = json_encode($shippingInfo);
                
            $data['taxCal'] = $this->input->post('taxcal');
            $data['comp_id'] = $CompId;
            $data['user_id'] = $UserId;
            $data['CompanyBranchId'] = $CompanyBranchId;
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
            $data['SalesmanId'] = $this->input->post('SalesmanId');
            $data['updated'] = date('d-m-Y h:i:s A');
            $data['updated_by'] = get_title('users', ['id' => $UserId], 'name');
            
            $where = ['id' => $id];
            $there = ['invoiceID' => $id];
            
            $result = $this->user_model->updateAry('estimates', $data, $where);
            $invoice_pro = $this->user_model->getAry('estimate_product', '', '', '', $there);
            $sale_condition = ['invoiceID' => $id, 'type' => 'Sale'];
            if ($invoice_pro) {
                foreach ($invoice_pro as $invoice) {
                    $this->user_model->deleteRow('estimate_product', $sale_condition);
                }
            }

            $resultProduct = $this->user_model->bulkEstimateUp('estimate_product', $array_of_products, $id, $data['invoiceNumber'], $data['invoiceDate']);
            
            if (isset($result) && !empty($result) && !empty($resultProduct)) {
                if ($this->input->post('voucherType') == 1) {
                    if ($this->db->where('invoice_id', $id)->where('invoice_type', 'Estimate')->where('transaction_type', 'DR')->get('payment')->num_rows() > 0) {
                        $PaymentDebit = [
                            'party_id' => $this->input->post('customerId'),
                            'receivedPaidAmount' => $this->input->post('total'),
                            'debit' => $this->input->post('total'),
                            'updated' => date('d-m-Y h:i:s A')
                        ];
                        $this->user_model->updateAry('payment', $PaymentDebit, ['invoice_id' => $id, 'invoice_type' => 'Estimate', 'transaction_type' => 'DR']);

                        $EstimateUpdate = [
                            'paid_amount' => $this->input->post('total')
                        ];
                        $this->user_model->updateAry('estimates', $EstimateUpdate, ['id' => $id]);
                    } else {
                        $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                        $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                        if ($this->session->userdata('RoleType')) {
                            $SeriesWhere['series.CompanyBranchId'] = $CompanyBranchId;
                            $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId;
                        }
                        $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Estimate Voucher')->where($SeriesWhere)->get('seriestitle')->row();
                        $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                        $PaymentDebit = [
                            'voucher_type' => 'Estimate Voucher',
                            'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                            'date' => date('Y-m-d'),
                            'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                            'party_id' => $this->input->post('customerId'),
                            'invoice_id' => $id,
                            'invoice_type' => 'Estimate',
                            'receivedPaidAmount' => $this->input->post('total'),
                            'debit' => $this->input->post('total'),
                            'transaction_type' => 'DR',
                            'payment_type' => 'Receipt',
                            'paymentmode' => get_title('payment_mode', ['payment_method' => 'Cash'], 'id'),
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'companyBranchId' => $CompanyBranchId,
                            'financial_year' => get_title('estimates', ['id' => $id], 'financial_year'),
                            'created' => date('d-m-Y h:i:s A')
                        ];
                        $this->user_model->saveAry('payment', $PaymentDebit);

                        $GetSeriesWhere = ['series_id' => $Series->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                        if ($this->session->userdata('RoleType')) {
                            $GetSeriesWhere['companyBranchId'] = $CompanyBranchId;
                        }
                        $GetSeries = $this->user_model->getRow('series', $GetSeriesWhere);
                        if ($GetSeries) {
                            $SeriesNumber['starter_point'] = $GetSeries->starter_point + 1;
                            $this->user_model->updateAry('series', $SeriesNumber, $GetSeriesWhere);
                        }

                        $EstimateUpdate = [
                            'paid_amount' => $this->input->post('total')
                        ];
                        $this->user_model->updateAry('estimates', $EstimateUpdate, ['id' => $id]);
                    }
                } else {
                    $PaymentCredit = [
                        'date' => $this->input->post('invoiceDate'),
                        'party_id' => $this->input->post('customerId'),
                        'credit' => $this->input->post('total'),
                        'updated' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->updateAry('payment', $PaymentCredit, ['invoice_id' => $id, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR']);
                    $EstimateUpdate = [
                        'paid_amount' => NULL
                    ];
                    $this->user_model->updateAry('estimates', $EstimateUpdate, ['id' => $id]);
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Estimate', 'transaction_type' => 'DR', 'payment_type' => 'Receipt']);
                }
                
                echo json_encode(['status' => true, 'data' => $id, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
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
            
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
           
            $CompProfileWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
            if ($this->session->userdata('BranchRole')) {
                $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
            
            if (get_title('comp_profile', $CompProfileWhere, 'ProductStockCheck') == "Y") {
                foreach ($product as $rowData) {
                    if (ProductBalanceQuanity($rowData['product']) < $rowData['qty']) {
                        $Massage = $rowData['productname'] . ' product entered quantity not in stock.';
                        echo json_encode(['status' => false, 'msg' => $Massage]);
                        return;
                    }
                }
            }

            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required|callback_unique_estimate');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Duplicate Invoice number for this company , Please try again.']);
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

                $shippingInfo = array(
                        'shippingName' => $this->input->post('shippingNameHidden'),
                        'shippingEmail' => $this->input->post('shippingEmailHidden'),
                        'shippingMobile' => $this->input->post('shippingMobileHidden'),
                        'shippingGSTType' => $this->input->post('shippingGSTTypeHidden'),
                        'shippingGST' => $this->input->post('shippingGSTHidden'),
                        'shippingState' => $this->input->post('shippingStateHidden'),
                        'shippingStateCode' => get_title('state_list', ['id' => $this->input->post('shippingStateHidden')], 'code'),
                        'shippingAddress' => $this->input->post('shippingAddressHidden'),
                        'shippingCity' => $this->input->post('shippingCityHidden'),
                        'shippingZip' => $this->input->post('shippingZipHidden'),
                );
                $data['shippingInfo'] = json_encode($shippingInfo);            
            
            $data['comp_id'] = $CompId;
            $data['user_id'] = $UserId;
            $data['CompanyBranchId'] = $CompanyBranchId;
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
            $data['SalesmanId'] = $this->input->post('SalesmanId');
            $data['created'] = date('d-m-Y h:i:s A');
            $data['created_by'] =  get_title('users', ['id' => $UserId], 'name');
            
            $result = $this->user_model->saveAry('estimates', $data);
            $this->user_model->bulkEstimateUp('estimate_product', $array_of_products, $result, $data['invoiceNumber'], $data['invoiceDate']);
            
            // send_sms_for_sale($this->input->post('customerMobile'), $this->input->post('total'));
            if (isset($result) && !empty($result)) {
                $PaymentCredit = [
                    'date' => $this->input->post('invoiceDate'),
                    'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                    'party_id' => $this->input->post('customerId'),
                    'invoice_id' => $result,
                    'invoice_type' => 'Estimate',
                    'credit' => $this->input->post('total'),
                    'transaction_type' => 'CR',
                    'user_id' => $UserId,
                    'comp_id' => $CompId,
                    'CompanyBranchId' => $CompanyBranchId,
                    'financial_year' => $FinancialYear,
                    'created' => date('d-m-Y h:i:s A')
                ];
                $this->user_model->saveAry('payment', $PaymentCredit);

                if ($this->input->post('voucherType') == 1) {
                    $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                    $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $SeriesWhere['series.CompanyBranchId'] = $CompanyBranchId;
                        $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Estimate Voucher')->where($SeriesWhere)->get('seriestitle')->row();
                    $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                    $PaymentDebit = [
                        'voucher_type' => 'Estimate Voucher',
                        'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                        'date' => date('Y-m-d'),
                        'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                        'party_id' => $this->input->post('customerId'),
                        'invoice_id' => $result,
                        'invoice_type' => 'Estimate',
                        'receivedPaidAmount' => $this->input->post('total'),
                        'debit' => $this->input->post('total'),
                        'transaction_type' => 'DR',
                        'payment_type' => 'Receipt',
                        'paymentmode' => get_title('payment_mode', ['payment_method' => 'Cash'], 'id'),
                        'user_id' => $UserId,
                        'comp_id' => $CompId,
                        'CompanyBranchId' => $CompanyBranchId,
                        'financial_year' => $FinancialYear,
                        'created' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->saveAry('payment', $PaymentDebit);

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
                    $this->user_model->updateAry('estimates', $EstimateUpdate, ['id' => $result]);
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
                    $this->user_model->updateAry('estimates', $only_number_invoice, ['id' => $result]);
                }
                
                if (!empty($this->input->post('OrderbookNumber')) && !empty($this->input->post('OrderbookId'))) {
                    $OrderBookId = $this->input->post('OrderbookId');
                    $OrderBookData = [
                        "InvoiceStatus" => 2,
                        "Updated" => date('d-m-Y h:i:s A'),
                        "UpdatedBy" => $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name')
                    ];
                    $this->user_model->updateAry('orderbook', $OrderBookData, ['Id' => $OrderBookId]);
                    $this->user_model->updateAry('estimates', ['order_book_Id' => $OrderBookId], ['id' => $result]);
                    
                    $OrderBookProducts = $this->user_model->getResult('orderbook_products', ['OrderbookId' => $OrderBookId]);
                    if ($OrderBookProducts) {
                        foreach ($OrderBookProducts as $key => $OrderbookProduct) {
                            $this->user_model->updateAry('orderbook_products', $OrderBookData, ['Id' => $OrderbookProduct->Id, 'OrderbookId' => $OrderBookId]);
                        }
                    } 
                }
                    
                echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function estimateEdit($id = null)
        {
            if (empty($id)) {
                $data['page'] = 'error';
                return $this->load->view('inventory_sep/inventory', $data);
            }
            $data['page'] = 'estimate/edit';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimates', $where);
            
            if ($result->voucherType != 1) {
                if ($result->paid_amount != 0) {
                    $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not delete this.', 'class' => 'danger']);
                    return redirect(base_url() . 'estimate');
                }
            }
            
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'estimate');
            }
        }

        public function viewestimate($id)
        {
            if (!$id) {
                return redirect('estimate');
            }
            $data['page'] = 'estimate/view';
            $data['title'] = 'MIS | estimate View';
            $data['menu'] = 'estimate';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimates', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('estimate_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate');
            }
        }

        public function printEstimate($id = null)
        {
            if (!$id) {
                return redirect(base_url() . 'estimate');
            }
            
            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimates', $where);

            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('estimate_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
           
                if ($fromet == '4inch') {
                    return $this->load->view('inventory_sep/estimate/print/4inch', $data);
                } else if ($fromet == 'A5') {
                    return $this->load->view('inventory_sep/estimate/a5print', $data);
                } else if ($fromet == 'Lh') {
                    return $this->load->view('inventory_sep/estimate/print/lh', $data);
                } else if ($fromet == '3inch') {
                    return $this->load->view('inventory_sep/estimate/print/3inch', $data);
                } elseif ($fromet == 'mc_formatA4') {
                    return $this->load->view('inventory_sep/estimate/print/mc_formatA4', $data);
                } else {
                    return $this->load->view('inventory_sep/estimate/a5print', $data);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'estimate');
            }
        }

        public function printEstimatePreview($id = null)
        {
            if (!$id) {
                return redirect('estimate');
            }
            $fromet = get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');

            if ($fromet == '4inch') {
                $data['page'] = 'estimate/preview/4inch';
            } else if ($fromet == 'A5') {
                $data['page'] = 'estimate/preview/a5';
            } else if ($fromet == 'Lh') {
                $data['page'] = 'estimate/preview/lh';
            } else if ($fromet == '3inch') {
                $data['page'] = 'estimate/preview/3inch';
            } else {
                $data['page'] = 'estimate/preview/a5';
            }

            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimates', $where);

            // echo '<pre>';
            // print_r($result);
            // die;
            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('estimate_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                // $data['pages'] = 2;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate');
            }
        }

        public function printEstimates($estimateId)
        {
            if (!$estimateId) {
                return redirect(base_url() . 'estimate');
            }
            $where = ['id' => $estimateId];
            $result = $this->user_model->getRow('estimates', $where);
            $pwhare = ['invoiceID' => $estimateId, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('estimate_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                
                $fileName = str_replace(' ', '_', get_title('parties', ['id' => $result->customerId], 'name')) . '_' . date('d-m-Y', strtotime($result->invoiceDate));
                
                $format =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
           
                if ($format == '4inch') {
                    $page = $this->load->view('inventory_sep/estimate/print/4inch', $data, true);
                } else if ($format == 'A5') {
                    $page = $this->load->view('inventory_sep/estimate/a5print', $data, true);
                } else if ($format == 'Lh') {
                    $page = $this->load->view('inventory_sep/estimate/print/lh', $data, true);
                } else if ($format == '3inch') {
                    $page = $this->load->view('inventory_sep/estimate/print/3inch', $data, true);
                } elseif ($format == 'mc_formatA4') {
                    $page = $this->load->view('inventory_sep/estimate/print/mc_formatA4', $data, true);
                } else {
                    $page = $this->load->view('inventory_sep/estimate/a5print', $data, true);
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
                return redirect(base_url() . 'estimate');
            }
        }
        
        public function exportestimate()
        {
            $data['page'] = 'estimate_report/index';
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

            $limit = 10;
            $table = 'estimates';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
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
                        // 'bold' => true,
                        // 'size' => 12,
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

            $objPHPExcel = $this->load->view('exportexcelforestimate', $data, true);
            print_r($objPHPExcel);
        }

        public function pdfestimate()
        {
            $data['page'] = 'estimate_report/print';
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
            // if (!empty($sale_register)) {
            $data['sale_register'] = $sale_register;
            $table = 'estimates';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
            }
            // $count =  $this->user_model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $result =  $this->user_model->getTblerDate($table, '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $data['result'] = $result;


            $data['page'] = 'estimate_report/print';
            $this->load->view('inventory_sep/inventory', $data);
        }
        
        // Move to Recycle Bin From Voucher Page
        public function estimate_delete($id = null)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            
            $EstimateInfo = $this->user_model->getRow('estimates', ['id' => $id]);
            if ($EstimateInfo->paid_amount != 0) {
                echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not delete this.']);
                return;
            }
            
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            }
            
            $data['status'] = 0;
            $return = $this->user_model->updateAry('estimates', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Sale"];
                $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('estimate_product', $statusChange, $where);
                    }
                    $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR']);
                }
                echo json_encode(['status' => true, 'msg' => 'Estimate deleted successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
        
        // Move To Recycle Bin
        public function estimate_deleted($id = null)
        {
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate/estimate_report');
            }
            
            $EstimateInfo = $this->user_model->getRow('estimates', ['id' => $id]);
            if ($EstimateInfo->paid_amount != 0) {
                $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not delete this.', 'class' => 'danger']);
                return redirect('estimate/estimate_report');
            }
            
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
            }
            $data['status'] = 0;
            $return = $this->user_model->updateAry('estimates', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Sale"];
                $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('estimate_product', $statusChange, $where);
                    }
                    $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR']);
                }
                $this->session->set_flashdata(['msg' => 'Estimate Deleted', 'class' => 'success']);
                return redirect('estimate/estimate_report');
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('estimate/estimate_report');
            }
        }
        
        // Delete From Recycle Bin (Actual)
        public function estimate_deletes($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            }
            $return = $this->user_model->deleteRow('estimates', $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Sale"];
                $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                if ($res) {
                    foreach ($res as $da) {
                        $this->user_model->deleteRow('estimate_product', $where);
                    }
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR']);
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

        public function pdfestimates()
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
            // if($this->input->get('fromDate') &&  $this->input->get('toDate')){
            // 	$fromDate = ['invoiceDate >='=>$this->input->get('fromDate')];
            // 	$toDate = ['invoiceDate <='=>$this->input->get('toDate')];
            // }
            if ($this->input->get('partyName')) {
                $partyName = ['customerId' => $this->input->get('name')];
            }
            if ($this->input->get('productName')) {
                $productName = ['product_ids' => $this->input->get('productName')];
            }
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $join = ['table' => 'estimate_product', 'condition' => 'estimates.id = estimate_product.invoiceID AND estimate_product.type = "Sale"'];
            $result =  $this->user_model->getResult('estimates', '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $join);
            $data['result'] = $result;
            $html = $this->load->view('inventory_sep/inventory', $data, true);
            $pdfFilePath = "pdf/estimates-" . time() . ".pdf";
            $pdf = $this->m_pdf->load();
            $pdf->AddPage('L');
            $pdf->WriteHTML($html, 2);
            $pdf->Output($pdfFilePath, "D");
        }

        public function estimate_report($start = 0)
        {
            $data['page'] = 'estimate/report';
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
            if ($partyFilter !== [] && !in_array('All', $partyFilter, true)) {
                $party = $partyFilter;
            }

            $table = 'estimates';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $financial_year = $this->session->userdata('financial_year');
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $financial_year];
            $pwhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $pwhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            if ($this->input->get('sale_register') == 'general') {
                if ($this->input->get('invoiceStatus') != 'All') {
                    $where['status'] = $this->input->get('invoiceStatus');
                }
            } else {
                if ($this->input->get('sale_register') != "") {
                    $where['status'] = 1;
                }
            }
            
            $result = $this->user_model->getTblerDate($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where, '', $party);
            $data['parties'] = $this->db->select('id, name, contact')->where($pwhere)->order_by('id', 'ASC')->get('parties')->result();
            $data['fromDate'] = $first_date;
            $data['toDate'] = $last_date;
            $data['result'] = $result;
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function invoicepdf($id)
        {
            $id = 314;
            $role_data = $this->session->userdata('role');
            $fyear = $this->session->userdata('Finacialyear');
            $result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
            $newdata = $result1->permission_action;
            $permission_data = explode(",", $newdata);
            $data['permission_data'] = $permission_data;
            if (!in_array("view", $permission_data)) {
                return redirect('estimate');
            }
            $where = ['id' => $fyear];
            $res = $this->model->getRow('financialyear', $where);
            $mindate = $res->startdate;
            $maxdate = $res->enddate;
            $year = $res->year;
            $data['mindate'] = $mindate;
            $data['maxdate'] = $maxdate;
            $data['year'] = $year;

            if (!$id) {
                return redirect('estimate');
            }
            $data['page'] = 'estimate/view';
            $data['title'] = 'MIS | estimate View';
            $data['menu'] = 'estimate';
            $where = ['id' => $id];
            $result = $this->model->getRow('estimates', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
            $products = $this->model->getAry('estimate_product', '', '', '', $pwhare);
            $result->product = $products;
            $data['result'] = $result;

            $html = $this->load->view('estimate/view', $data, true);
            $pdfFilePath = "pdf/estimates-" . time() . ".pdf";
            $pdf = $this->m_pdf->load();
            $pdf->AddPage('L');
            $pdf->WriteHTML($html, 2);
            $pdf->Output($pdfFilePath, "D");
        }

        public function reportPurchase($start = 0)
        {
            $role_data = $this->session->userdata('role');
            $fyear = $this->session->userdata('Finacialyear');
            $fromDate = '';
            $toDate = '';
            if (isset($role_data) && isset($fyear)) {
                $where = ['id' => $fyear];
                $res = $this->model->getRow('financialyear', $where);
                $mindate = $res->startdate;
                $maxdate = $res->enddate;
                $fromDate = ['purchaseDate >=' => $mindate];
                $toDate = ['purchaseDate >=' => $mindate];
            }
            $data['page'] = 'reports/purchase';
            $data['title'] = 'MIS | Purchase Reports';
            $data['menu'] = 'Reports';

            $invoiceNumber = '';
            $invoiceDate = '';

            $partyName = '';
            $productName = '';
            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['purchaseNumber' => $this->input->get('invoiceNumber')];
            }
            if ($this->input->get('invoiceDate')) {
                $invoiceDate = ['purchaseDate' => $this->input->get('invoiceDate')];
            }
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['purchaseDate >=' => $this->input->get('fromDate')];
                $toDate = ['purchaseDate <=' => $this->input->get('toDate')];
            }
            if ($this->input->get('partyName')) {
                $partyName = ['customerId' => $this->input->get('partyName')];
            }
            if ($this->input->get('productName')) {
                $productName = ['product_ids' => $this->input->get('productName')];
            }
            $limit = 10;
            $table = 'purchases';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $count =  $this->model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName);
            $result =  $this->model->getResult($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName);

            $config['base_url'] = base_url('reportpurchase');
            $config['reuse_query_string'] = true;
            $config['total_rows'] = $count;
            $config['per_page'] = $limit;
            $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';
            $config['full_tag_close'] = '</ul></nav>';

            $config['first_link'] = 'First';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';

            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';

            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';

            $config['prev_link'] = 'Previous';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close'] = '</a></li>';

            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';

            //$config['anchor_class'] = 'class="page-link"';
            $config['attributes'] = array('class' => 'page-link');
            $this->pagination->initialize($config);

            $data['result'] = $result;
            $data['start'] = $start;
            $data['end'] = (int)$start + count($result);
            $data['rowCount'] = $count;
            $data['totalCount'] = $this->model->RowCount($table);
            $this->load->view('index', $data);
        }

        public function unique_estimate()
        {
            $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('Comp_id');
            }
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'invoiceNumber' => $invoiceNumber, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $return_value = $this->user_model->getRow('estimates', $Where);
            if ($return_value) {
                return false;
            }
            return true;
        }

        public function estimatePrintMultipleInvoice()
        {

            $data['page'] = 'inventory_sep/estimate_report/multiple_pdf';

            $post = $this->input->post();
            $results = [];
            $products = [];
            if (isset($post) && !empty($post)) {
                foreach ($post['id'] as $key => $value) {
                    $sale_id = $value;
                    $result = $this->user_model->getRow('estimates', ['id' => $sale_id]);
                    $resuls[] = $this->user_model->getRow('estimates', ['id' => $sale_id]);

                    $pwhare = ['invoiceID' => $sale_id, 'type' => 'Sale'];
                    if (isset($result) && !empty($result)) {
                        $products[] = $this->user_model->getAry('estimate_product', '', '', '', $pwhare);
                        foreach ($products as $row) {
                            $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                            $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                            $row->serials = $serials;
                        }
                    }
                }
                $result->product = $products;
                $data['result'] = $results;
                echo json_encode(['status' => TRUE, 'data' => 'Aman', 'class' => 'success']);
                return $this->load->view('inventory', $data);
            } else {
                echo json_encode(['status' => false, 'data' => 'Something Went Wronng', 'class' => 'error']);
            }
        }
        
        public function getLastMaxPriceOfProductByEstimateSale ()
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
                    
                    $this->db->select('invoiceNumber');
                    $this->db->where('user_id', $UserId);
                    $this->db->where('comp_id', $CompId);
                    if ($this->session->userdata('BranchRole')) {
                        $this->db->where('CompanyBranchId', $CompBranchId); 
                    }
                    $this->db->where('customerId', $party_id);
                    $this->db->where('type', 'Estimate');
                    $this->db->order_by('id', 'DESC');
                    $this->db->limit(1);
                    $SaleResult = $this->db->get('estimates')->row();
                    if ($SaleResult) {
                        $this->db->select('MAX(rate) AS Rate');
                        $this->db->where('user_id', $UserId);
                        $this->db->where('comp_id', $CompId);
                        if ($this->session->userdata('BranchRole')) {
                            $this->db->where('CompanyBranchId', $CompBranchId); 
                        }
                        $this->db->where('InvoiceNo', $SaleResult->invoiceNumber);
                        $this->db->where('product_id', $product_id);
                        $this->db->where('type', 'Sale');
                        $this->db->order_by('id', 'DESC');
                        $this->db->limit(1);
                        $PartyRate = $this->db->get('estimate_product')->row();
                        if ($PartyRate) {
                            echo json_encode(['status' => true, 'data' => $PartyRate]);
                        } else {
                            echo json_encode(['status' => false, 'data' => 'Not Found']);
                        }
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Not Found']);
                    }
                }
            }
        }
        
        public function set_warehouse_for_product()
        {
            $this->load->view('inventory_sep/estimate/ChangeWarehouse');
        }

        public function estimateToSale($id=null)
        {
            if (empty($id)) {
                $data['page'] = 'error';
                return $this->load->view('inventory_sep/inventory', $data);
            }
            $data['page'] = 'estimate/converSale';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('estimates', $where);
            
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'estimate');
            }
        } 

        public function estimatetosalesave()
        {
            $product = $this->input->post('product');

            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }            
            
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
       
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Invoice number is required field.']);
                return;
            }
            
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            $SeriesId = $this->input->post('series_id');
            
            $CommonWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
            $SeriesUpdateWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'series_id' => $SeriesId];
            $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
            if ($this->session->userdata('BranchRole')) {
                $CommonWhere['CompanyBranchId'] = $CompanyBranchId;
                $SeriesUpdateWhere['CompanyBranchId'] = $CompanyBranchId;
                $SeriesWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
            $UniqueSaleInvoiceNumber = $this->UniqueSaleInvoiceNumber();
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required');
          
            if ($UniqueSaleInvoiceNumber == false) {
                $this->user_model->updateAry('series', ['starter_point' => get_title('series', ['series_id' => $SeriesId], 'starter_point') + 1], $SeriesUpdateWhere);
                $Series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('series', 'Sales')->where($SeriesWhere)->get()->row();
                if (isset($Series) && !empty($Series)) {
                    $invoiceNumber = $this->user_model->getSeris($Series);
                } else {
                    $this->db->select_max('unorder_invoice_number');
                    $this->db->where($CommonWhere);
                    $query = $this->db->get('sales')->row();
                    
                    if (!empty($query->unorder_invoice_number)) {
                        $invoiceNumber = $query->unorder_invoice_number + 1;
                    } else {
                        $invoiceNumber = 1;
                    }
                }
            } else {
                $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
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
            
            $data['invoiceNumber'] = $invoiceNumber;
            $data['invoiceDate'] = date('Y-m-d', strtotime($this->input->post('invoiceDate')));
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
            $data['oldBalance'] = $this->input->post('oldBalance');
            $data['totalsgst'] = $this->input->post('totalsgst');
            $data['totalcgst'] = $this->input->post('totalcgst');
            $data['totaligst'] = $this->input->post('totaligst');
            $data['orderDate'] = $this->input->post('orderDate');
            $data['orderNumber'] = $this->input->post('orderNumber');
            $data['taxCal'] = $this->input->post('taxcal');

                $shippingInfo = array(
                        'shippingName' => $this->input->post('shippingNameHidden'),
                        'shippingEmail' => $this->input->post('shippingEmailHidden'),
                        'shippingMobile' => $this->input->post('shippingMobileHidden'),
                        'shippingGSTType' => $this->input->post('shippingGSTTypeHidden'),
                        'shippingGST' => $this->input->post('shippingGSTHidden'),
                        'shippingState' => $this->input->post('shippingStateHidden'),
                        'shippingStateCode' => get_title('state_list', ['id' => $this->input->post('shippingStateHidden')], 'code'),
                        'shippingAddress' => $this->input->post('shippingAddressHidden'),
                        'shippingCity' => $this->input->post('shippingCityHidden'),
                        'shippingZip' => $this->input->post('shippingZipHidden'),
                );
                $data['shippingInfo'] = json_encode($shippingInfo);
            
            $data['comp_id'] = $CompId;
            $data['user_id'] = $UserId;
            $data['CompanyBranchId'] = $CompanyBranchId;
            $data['financial_year'] = $FinancialYear;
            $data['cess'] = $this->input->post('cess');
            $data['remark'] = $this->input->post('remark');
            $data['p_code'] = $this->input->post('p_code');
            $data['format_invoice'] = $this->input->post('format_invoice');
            $data['product'] = json_encode($array_of_products);
            $data['vahical_no'] = $this->input->post('vehicle');
            $data['lot_no'] = $this->input->post('lot_no'); 
            $data['estimate_no'] = $this->input->post('estimateNumber');
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
            $data['SalesmanId'] = $this->input->post('SalesmanId');
            $data['created'] = date('d-m-Y h:i:s A');
            $data['created_by'] = get_title('users', ['id' => $UserId], 'name');
            
            $result = $this->user_model->saveAry('sales', $data);
            
            // invoice table data store 
            $this->user_model->bulkSaleUp('invoice_product', $array_of_products, $result, $data['invoiceNumber'], $data['invoiceDate']);
            
            $sms_blocked_status = $this->user_model->getRow('inventory_billing', ['sms_blocked_status' => "Enable", 'id' => $this->session->userdata('comp_id')]);

            if ($this->input->post('customerMobile') && !empty($sms_blocked_status)) {
                // send_sms_for_sale($this->input->post('customerMobile'), $this->input->post('total'), $result);
            }
           
            if (isset($result) && !empty($result)) {                
                $PaymentCredit = [
                    'date' => date('Y-m-d', strtotime($this->input->post('invoiceDate'))),
                    'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                    'party_id' => $this->input->post('customerId'),
                    'invoice_id' => $result,
                    'invoice_type' => 'Sale',
                    'credit' => $this->input->post('total'),
                    'transaction_type' => 'CR',
                    'user_id' => $UserId,
                    'comp_id' => $CompId,
                    'CompanyBranchId' => $CompanyBranchId,
                    'financial_year' => $FinancialYear,
                    'created' => date('d-m-Y h:i:s A')
                ];
                $this->user_model->saveAry('payment', $PaymentCredit);

                if ($this->input->post('voucherType') == 1) {
                    $PaymentSeries = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                    $UnorderNumber = $this->db->select('id')->where($CommonWhere)->where('status', 1)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                    $PaymentDebit = [
                        'voucher_type' => 'Tax',
                        'voucher_number' => !empty($PaymentSeries) ? $this->user_model->getSeris($PaymentSeries) : $UnorderNumber,
                        'date' => date('Y-m-d'),
                        'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                        'party_id' => $this->input->post('customerId'),
                        'invoice_id' => $result,
                        'invoice_type' => 'Sale',
                        'receivedPaidAmount' => $this->input->post('total'),
                        'debit' => $this->input->post('total'),
                        'transaction_type' => 'DR',
                        'payment_type' => 'Receipt',
                        'paymentmode' => get_title('payment_mode', ['payment_method' => 'Cash'], 'id'),
                        'user_id' => $UserId,
                        'comp_id' => $CompId,
                        'CompanyBranchId' => $CompanyBranchId,
                        'financial_year' => $FinancialYear,
                        'created' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->saveAry('payment', $PaymentDebit);

                    $GetSeriesWhere = ['series_id' => $PaymentSeries->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $GetSeriesWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $GetSeries = $this->user_model->getRow('series', $GetSeriesWhere);
                    if ($GetSeries) {
                        $SeriesNumber['starter_point'] = $GetSeries->starter_point + 1;
                        $this->user_model->updateAry('series', $SeriesNumber, $GetSeriesWhere);
                    }

                    $SaleUpdate = [
                        'paid_amount' => $this->input->post('total')
                    ];
                    $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $result]);
                }
                
                if ($this->db->select('series_id')->where($SeriesUpdateWhere)->get('series')->num_rows()) {
                    $StarterPoint['starter_point'] = get_title('series', $SeriesUpdateWhere, 'starter_point') + 1;
                    $this->user_model->updateAry('series', $StarterPoint, $SeriesUpdateWhere);
                } else {
                    $only_number_invoice['unorder_invoice_number'] = $invoiceNumber;
                    $this->user_model->updateAry('sales', $only_number_invoice, ['id' => $result]);
                }

                // estimate bill, their product and estimate payment delete;
                $EstimateId = $this->db->select('id')->where($CommonWhere)->where('invoiceNumber', $this->input->post('estimateNumber'))->where('status', 1)->get('estimates')->row('id');
                $this->db->where($CommonWhere)->where(['invoice_id' => $EstimateId, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR'])->delete('payment');
                $this->db->where($CommonWhere)->where('id', $EstimateId)->update('estimates', ['status' => 0]);
                $this->db->where($CommonWhere)->where('invoiceID', $EstimateId)->update('estimate_product', ['status' => 0]);
                
                if ($this->db->where($CommonWhere)->where('JSON_VALID(invoice_id)', null, false)->where("JSON_CONTAINS(invoice_id, '\"$EstimateId\"')", null, false)->where('transaction_type', 'DR')->get('payment')->num_rows()) {
                    $PaymentData = $this->db->select('id, receivedPaidAmount')->where($CommonWhere)->where('JSON_VALID(invoice_id)', null, false)->where("JSON_CONTAINS(invoice_id, '\"$EstimateId\"')", null, false)->where('transaction_type', 'DR')->get('payment')->row();
                    $SaleId = [$result];
                    $SaleInvoiceId = array_map('strval', $SaleId);
                    $PaymentUpdate = [
                        'voucher_type' => 'Tax',
                        'date' => date('Y-m-d'),
                        'invoice_id' => json_encode($SaleInvoiceId),
                        'invoice_type' => json_encode(array('Sale')),
                        'updated' => date('d-m-Y h:i:s A')
                    ];
                    $this->db->where($CommonWhere)->where('id', $PaymentData->id)->update('payment', $PaymentUpdate);
                    $this->db->where($CommonWhere)->where('id', $result)->update('sales', ['paid_amount' => $PaymentData->receivedPaidAmount]);
                }
                
                echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
        
        public function UniqueSaleInvoiceNumber()
        {
            $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            
            $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'invoiceNumber' => $invoiceNumber, 'status' => 1];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $return_value = $this->user_model->getRow('sales', $where);
            
            if ($return_value) {
                return false;
            }
            return true;
        }
    }