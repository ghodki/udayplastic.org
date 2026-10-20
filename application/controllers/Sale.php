<?php
    defined('BASEPATH') or exit('No direct script access allowed');    
    date_default_timezone_set('Asia/Kolkata');
    
    class Sale extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);
            $this->load->model('user_model');
            $this->load->library('ajax_pagination_dashboard');
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
                return redirect(base_url() . 'Invent/sale');
            } else {
                return redirect(base_url() . 'Invent/sale');
            }
            
        }

        public function saleUpdate()
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
                $data['edit'] = 1;
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
                echo json_encode(['status' => false, 'msg' => 'Invoice number is required.']);
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
            $data['tds'] = $this->input->post('tds');
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
                'shippingZip' => $this->input->post('shippingZipHidden')
            );
            $data['shippingInfo'] = json_encode($shippingInfo);
            
            $data['taxCal'] = $this->input->post('taxcal');
            $data['comp_id'] = $CompId;
            $data['user_id'] = $UserId;
            $data['CompanyBranchId'] = $CompanyBranchId;
            $data['vahical_no'] = $this->input->post('vehicle');
            $data['lot_no'] = $this->input->post('lot_no');
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
            $data['updated_by'] = $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name');

            //sale history
            $this->invoiceHistory($id, $data);
            
            $where = ['id' => $id];            
            $sale_condition = ['invoiceID' => $id, 'type' => 'Sale'];
            $result = $this->user_model->updateAry('sales', $data, $where);
            $invoice_pro = $this->user_model->getAry('invoice_product', '', '', '', $sale_condition);
            
            if ($invoice_pro) {
                foreach ($invoice_pro as $invoice) {
                    $this->user_model->deleteRow('invoice_product', $sale_condition);
                }
            }
            
            $resultProduct = $this->user_model->bulkSaleUp('invoice_product', $array_of_products, $id, $data['invoiceNumber'], $data['invoiceDate']);

            if (isset($result) && !empty($result) && !empty($resultProduct)) {
                if ($this->input->post('voucherType') == 1) {
                    if ($this->db->where('invoice_id', $id)->where('invoice_type', 'Sale')->where('transaction_type', 'DR')->get('payment')->num_rows() > 0) {
                        $PaymentDebit = [
                            'party_id' => $this->input->post('customerId'),
                            'receivedPaidAmount' => $this->input->post('total'),
                            'debit' => $this->input->post('total'),
                            'updated' => date('d-m-Y h:i:s A')
                        ];
                        $this->user_model->updateAry('payment', $PaymentDebit, ['invoice_id' => $id, 'invoice_type' => 'Sale', 'transaction_type' => 'DR']);

                        $SaleUpdate = [
                            'paid_amount' => $this->input->post('total')
                        ];
                        $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $id]);
                    } else {
                        $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                        $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
                        if ($this->session->userdata('BranchRole')) {
                            $SeriesWhere['series.CompanyBranchId'] = $CompanyBranchId;
                            $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId;
                        }
                        $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                        $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->where('status', 1)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                        $PaymentDebit = [
                            'voucher_type' => 'Tax',
                            'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                            'date' => date('Y-m-d'),
                            'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                            'party_id' => $this->input->post('customerId'),
                            'invoice_id' => $id,
                            'invoice_type' => 'Sale',
                            'receivedPaidAmount' => $this->input->post('total'),
                            'debit' => $this->input->post('total'),
                            'transaction_type' => 'DR',
                            'payment_type' => 'Receipt',
                            'paymentmode' => get_title('payment_mode', ['payment_method' => 'Cash'], 'id'),
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'CompanyBranchId' => $CompanyBranchId,
                            'financial_year' => get_title('sales', ['id' => $id], 'financial_year'),
                            'created' => date('d-m-Y h:i:s A')
                        ];
                        $this->user_model->saveAry('payment', $PaymentDebit);

                        $GetSeriesWhere = ['series_id' => $Series->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                        if ($this->session->userdata('BranchRole')){
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
                        $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $id]);
                    }
                } else {
                    $PaymentCredit = [
                        'date' => date('Y-m-d', strtotime($this->input->post('invoiceDate'))),
                        'party_id' => $this->input->post('customerId'),
                        'credit' => $this->input->post('total'),
                        'updated' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->updateAry('payment', $PaymentCredit, ['invoice_id' => $id, 'invoice_type' => 'Sale', 'transaction_type' => 'CR']);
                    $SaleUpdate = [
                        'paid_amount' => NULL
                    ];
                    $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $id]);
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Sale', 'transaction_type' => 'DR', 'payment_type' => 'Receipt']);
                }
                
                echo json_encode(['status' => true, 'data' => $id, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
        
        public function savesale()
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
            
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Invoice number is required field.']);
                return;
            }
                    
            $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
            $resp = $this->unique_sale();
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required');
            if ($resp == false) {
                $series_id = $this->input->post('series_id');
                $up_array = ['user_id' => $UserId, 'comp_id' => $CompId, 'series_id' => $series_id];
                if ($this->session->userdata('BranchRole')) {
                    $up_array['CompanyBranchId'] = $CompanyBranchId;
                }
                
                if (get_title("series", ["series_id" => $series_id], "starter_point") == 19) {
                    $seriesNumber = 110;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 199) {
                    $seriesNumber = 1100;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 1999) {
                    $seriesNumber = 11000;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 19999) {
                    $seriesNumber = 110000;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 199999) {
                    $seriesNumber = 1100000;
                } else {
                    $seriesNumber = get_title("series", ["series_id" => $series_id], "starter_point") + 1;
                }
                
                $this->user_model->updateAry('series', ['starter_point' => $seriesNumber], $up_array);
                $where = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $CompanyBranchId;
                }
                $series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('series', 'Sales')->where($where)->get()->row();
                if (isset($series) && !empty($series)) {
                    $invoiceNumber = $this->user_model->getSeris($series);
                } else {
                    $where = ['comp_id' => $CompId, 'user_id' => $UserId];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $this->db->select_max('unorder_invoice_number');
                    $this->db->where($where);
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
            $data['tds'] = $this->input->post('tds');
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
            $data['created_by'] = $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name');
            
            $result = $this->user_model->saveAry('sales', $data);
            
            // invoice table data store 
            $this->user_model->bulkSaleUp('invoice_product', $array_of_products, $result, $data['invoiceNumber'], $data['invoiceDate']);
            
            $sms_blocked_status = $this->user_model->getRow('inventory_billing', ['sms_blocked_status' => "Enable", 'id' => $CompId]);

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
                    $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                    $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
                    if ($this->session->userdata('BranchRole')) {
                        $SeriesWhere['CompanyBranchId'] = $CompanyBranchId;
                        $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                    $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->where('status', 1)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                    $PaymentDebit = [
                        'voucher_type' => 'Tax',
                        'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
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

                    $SaleUpdate = [
                        'paid_amount' => $this->input->post('total')
                    ];
                    $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $result]);
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
                    $aman['starter_point'] = $seriesNumber;
                    $this->user_model->updateAry('series', $aman, $where1);
                } else {
                    $only_number_invoice['unorder_invoice_number'] = $invoiceNumber;
                    $this->user_model->updateAry('sales', $only_number_invoice, ['id' => $result]);
                }
                
                if (!empty($this->input->post('OrderbookNumber')) && !empty($this->input->post('OrderbookId'))) {
                    $OrderBookId = $this->input->post('OrderbookId');
                    $OrderBookData = [
                        "InvoiceStatus" => 2,
                        "Updated" => date('d-m-Y h:i:s A'),
                        "UpdatedBy" => $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name')
                    ];
                    $this->user_model->updateAry('orderbook', $OrderBookData, ['Id' => $OrderBookId]);
                    $this->user_model->updateAry('sales', ['order_book_Id' => $OrderBookId], ['id' => $result]);
                    
                    $OrderBookProducts = $this->user_model->getResult('orderbook_products', ['OrderbookId' => $OrderBookId]);
                    if ($OrderBookProducts) {
                        foreach ($OrderBookProducts as $key => $OrderbookProduct) {
                            $this->user_model->updateAry('orderbook_products', $OrderBookData, ['Id' => $OrderbookProduct->Id, 'OrderbookId' => $OrderBookId]);
                        }
                    } 
                }
                
                if (!empty($this->input->post('PurchaseOrderNumber')) && !empty($this->input->post('PurchaseOrderId'))) {
                    $PurchaseOrderId = $this->input->post('PurchaseOrderId');
                    $PurchaseOrderData = [
                        "InvoiceStatus" => 2,
                        "Updated" => date('d-m-Y h:i:s A'),
                        "UpdatedBy" => $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name')
                    ];
                    $this->user_model->updateAry('purchaseorder', $PurchaseOrderData, ['Id' => $PurchaseOrderId]);
                    $this->user_model->updateAry('sales', ['PurchaseOrderId' => $PurchaseOrderId], ['id' => $result]);
                    
                    $PurchaseOrderProducts = $this->user_model->getResult('purchaseorder_products', ['PurchaseOrderId' => $PurchaseOrderId]);
                    if ($PurchaseOrderProducts) {
                        foreach ($PurchaseOrderProducts as $key => $PurchaseOrderProduct) {
                            $this->user_model->updateAry('purchaseorder_products', $PurchaseOrderData, ['Id' => $PurchaseOrderProduct->Id, 'PurchaseOrderId' => $PurchaseOrderId]);
                        }
                    } 
                }
                                
                if (!empty($this->input->post('proformaInvoiceNumber'))) {
                        $proformaId = get_title('proforma', ['invoiceNumber' => $this->input->post('proformaInvoiceNumber')], 'id');
                        $proformaData = [
                                "invoiceStatus" => 1,
                                "updated" => date('d-m-Y h:i:s A'),
                                "updated_by" => get_title('users', ['id' => $UserId], 'name')
                        ];
                        $this->user_model->updateAry('proforma', $proformaData, ['id' => $proformaId]);
                        $this->user_model->updateAry('sales', ['proformaId' => $proformaId], ['id' => $result]);
                    
                        $proformaProducts = $this->user_model->getResult('proforma_product', ['id' => $proformaId]);
                        if ($proformaProducts) {
                                foreach ($proformaProducts as $key => $proformaProduct) {
                                        $this->user_model->updateAry('proforma_product', $proformaData, ['id' => $proformaProduct->id, 'invoiceID' => $proformaId]);
                                }
                        } 
                }

                if (!empty($this->input->post('holdinvoiceid'))) {
                    $HoldInvoiceId = $this->input->post('holdinvoiceid');
                    $this->user_model->deleteRow('hold_invoice', ['id' => $HoldInvoiceId]); 
                }
                
                echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function saleEdit($SaleId)
        {
            if (empty($SaleId)) {
                $this->session->set_flashdata(['msg' => 'Invoice number missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
            
            $result = $this->user_model->getRow('sales', ['id' => $SaleId]);
            $result->einvoie_details = false;
            if ($result->Irn != null) {
                $result->einvoie_details = $this->user_model->getRow('e_invoice_details', ['Irn' => $result->Irn]);  
            } 
            $partyData = $this->user_model->getRow('parties',['id' => $result->customerId]);
        
            if ($result->voucherType != 1) {
                if ($result->paid_amount != 0) {
                    $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not change this.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }
            
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                $data['id'] = $result->id;
                $data['partyData']= $partyData;
                $data['page'] = 'sale/sale_edit';
                $data['result'] = $result; 
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'sale');
            }
        }

        public function viewsale($id)
        {
            if (!$id) {
                return redirect('sale');
            }
            $data['page'] = 'sale/viewsale';
            $data['title'] = 'MIS | Sale View';
            $data['menu'] = 'Sale';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('sales', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Sale');
            }
        }

        public function printSale($id = null)
        {
            if (!$id) {
                return redirect(base_url() . 'sale');
            }
            $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');

            $where = ['id' => $id];
            $result = $this->user_model->getRow('sales', $where);

            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
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
                    return $this->load->view('inventory_sep/sale/3inch', $data);
                } elseif ($fromet == 'A5') {
                    return $this->load->view('inventory_sep/sale/a5print', $data);
                } elseif ($fromet == 'A4') {
                    return $this->load->view('inventory_sep/sale/a4print', $data);
                } elseif ($fromet == 'Lh') {
                    $data['page'] = 'inventory_sep/sale/lh';
                    return $this->load->view('inventory', $data);
                } elseif($fromet == '3inch') {
                    return $this->load->view('inventory_sep/sale/3inch', $data);
                } elseif ($fromet == 'pos') {
                    return $this->load->view('inventory_sep/sale/print/pos', $data);
                } elseif ($fromet == 'invoice') {
                    return $this->load->view('inventory_sep/sale/print/invoice', $data);
                } elseif ($fromet == 'invoice_A401') {
                    return $this->load->view('inventory_sep/sale/print/invoice_A401', $data);
                } elseif ($fromet == 'invoice_A402') {
                    return $this->load->view('inventory_sep/sale/print/invoice_A402', $data);
                } elseif ($fromet == 'invoice_A403') {
                    return $this->load->view('inventory_sep/sale/print/invoice_A403', $data);
                } elseif ($fromet == 'invoice_A404') {
                    return $this->load->view('inventory_sep/sale/print/invoice_A404', $data);
                } elseif ($fromet == 'mc_formatA4') {
                    return $this->load->view('inventory_sep/sale/print/mc_formatA4', $data);
                } else {
                    return $this->load->view('inventory_sep/sale/a5print', $data);
                }
            }
        }

        public function printSalePreview($id = null)
        {
            if (!$id) {
                return redirect('sale');
            }
            $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
            if ($fromet == '4inch') {
                $data['page'] = 'sale/4inpreview';
            } elseif ($fromet == 'A5') {
                $data['page'] = 'sale/a5preview';
            } else {
                $data['page'] = 'sale/a5preview';
            }

            $where = ['id' => $id];
            $result = $this->user_model->getRow('sales', $where);
            
            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
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
                return redirect('sale');
            }
        }

        public function printSales($saleId)
        {
            ini_set('memory_limit', '-1');
            set_time_limit(5000);
            
            if (!$saleId) {
                return redirect(base_url() . 'sale');
            }
            $where = ['id' => $saleId];
            $result = $this->user_model->getRow('sales', $where);
            $pwhare = ['invoiceID' => $saleId, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;

                $fileName = str_replace(' ', '_', get_title('parties', ['id' => $result->customerId], 'name')) . '_' . date('d-m-Y', strtotime($result->invoiceDate));
                
                $format =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
                if ($format == '4inch') {
                    $page = $this->load->view('inventory_sep/sale/3inch', $data, true);
                } elseif ($format == 'A5') {
                    $page = $this->load->view('inventory_sep/sale/a5print', $data, true);
                } elseif ($format == 'A4') {
                    $page = $this->load->view('inventory_sep/sale/a4print', $data, true);
                } elseif ($format == 'Lh') {
                    $page = $this->load->view('inventory_sep/sale/lh', $data, true);
                } elseif($format == '3inch') {
                    $page = $this->load->view('inventory_sep/sale/3inch', $data, true);
                } elseif ($format == 'pos') {
                    $page = $this->load->view('inventory_sep/sale/print/pos', $data, true);
                } elseif ($format == 'invoice') {
                    $page = $this->load->view('inventory_sep/sale/print/invoice', $data, true);
                } elseif ($format == 'invoice_A401') {
                    $page = $this->load->view('inventory_sep/sale/print/invoice_A401', $data, true);
                } elseif ($format == 'invoice_A402') {
                    $page = $this->load->view('inventory_sep/sale/print/invoice_A402', $data, true);
                } elseif ($format == 'invoice_A403') {
                    $page = $this->load->view('inventory_sep/sale/print/invoice_A403', $data, true);
                } elseif ($format == 'invoice_A404') {
                    $page = $this->load->view('inventory_sep/sale/print/invoice_A404', $data, true);
                } elseif ($format == 'mc_formatA4') {
                    $page = $this->load->view('inventory_sep/sale/print/mc_formatA4', $data, true);
                } else {
                    $page = $this->load->view('inventory_sep/sale/a5print', $data, true);
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
                return redirect(base_url() . 'sale');
            }
        }

        public function exportsale()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            if ($this->input->get('estimate') == "") {
                $data['page'] = 'inventory_sep/sale_report/index';
                $invoiceNumber = '';
                $invoiceDate = '';
                $toDate = '';
                $fromDate = '';
                $partyName = '';
                $productName = '';

                if ($this->input->get('estimate')) {
                    $estimate = $this->input->get('estimate');
                }
                if ($this->input->get('invoiceNumber')) {
                    $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
                }
                if ($this->input->get('sale_register')) {
                    $sale_register = $this->input->get('sale_register');
                }
                if ($this->input->get('invoiceDate')) {
                    $invoiceDate = ['invoiceDate' => date('Y-m-d', strtotime($this->input->get('invoiceDate')))];
                }
                if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                    $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                    $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                }

                $limit = '';
                $table = 'sales';
                $order = ['col' => 'added_at', 'order' => 'ASC'];

                if ($sale_register == "general") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                } elseif ($sale_register == "columnar") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                } elseif ($sale_register == "hsn") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];                    
                } elseif ($sale_register == "hsnB2B") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                } elseif ($sale_register == "hsnB2C") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                } elseif ($sale_register == "detailview") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                } elseif ($sale_register == "updateview") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'updated !=' => '', 'updated_by !=' => ''];
                } elseif ($sale_register == "description") {
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                }
                if ($this->session->userdata('BranchRole')){
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $count =  $this->user_model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
                $result =  $this->user_model->getTblerDate($table, '', $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
                $data['result'] = $result;
                $data['count'] = $count;
                $data['sale_register'] = $sale_register;
                $data['estimate'] = $estimate;
                $data['FromDate'] = $fromDate;
                $data['ToDate'] = $toDate;
                print_r($this->load->view('exportexcelforsale', $data, true));
            } elseif ($this->input->get('estimate') == true) {
                $data['page'] = 'inventory_sep/sale_report/index';
                $invoiceNumber = '';
                $invoiceDate = '';
                $toDate = '';
                $fromDate = '';
                $partyName = '';
                $productName = '';

                if ($this->input->get('estimate')) {
                    $estimate = $this->input->get('estimate');
                }
                if ($this->input->get('sale_register')) {
                    $sale_register = $this->input->get('sale_register');
                }
                if ($this->input->get('invoiceNumber')) {
                    $data['invoicenumber'] = ['invoiceNumber' => $this->input->get('invoiceNumber')];
                }
                if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                    $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                    $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                }
                
                $data['user_id'] = $this->session->userdata('user_id');
                $data['comp_id'] = $this->session->userdata('comp_id');
                if ($this->session->userdata('BranchRole')){
                    $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $data['status'] = 1;
                $data['financial_year'] = $this->session->userdata('financial_year');
                $data['sale_register'] = $sale_register;
                $data['estimate'] = $estimate;
                $data['FromDate'] = $fromDate;
                $data['ToDate'] = $toDate;
                print_r($this->load->view('exportexcelforsale', $data, true));
            }
        }

        public function pdfsale()
        {
            $data['page'] = 'sale_report/print';
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
                $invoiceDate = ['invoiceDate' => date('Y-m-d', strtotime($this->input->get('invoiceDate')))];
            }
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
            }
            
            $data['sale_register'] = $sale_register;
            $table = 'sales';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $result =  $this->user_model->getTblerDate($table, '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $data['result'] = $result;
            $data['page'] = 'sale_report/print';
            
            $this->load->view('inventory_sep/inventory', $data);
        }
            
        //  Move To Recycle Bin From Voucher Page
        public function sale_delete($id = null)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }

            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                if (json_decode(get_title('employee_permission', ['EmployeeId' => $this->session->userdata('EmployeeId')], 'Sales'))->View == "View") {
                    echo json_encode(['status' => false, 'msg' => 'you do not have permission.']);
                    return;
                }
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            
            $SaleInfo = $this->user_model->getRow('sales', ['id' => $id]);
            if ($SaleInfo->paid_amount != 0) {
                echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not cancel this.']);
                return;
            }
            
            $data['status'] = 0;
            $where = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $return = $this->user_model->updateAry('sales', $data, $where);
            
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Sale"];
                $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('invoice_product', $statusChange, $where);
                    }
                    $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Sale', 'transaction_type' => 'CR']);
                }
                echo json_encode(['status' => true, 'msg' => 'Sale cancelled successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
            
        // Move To Recycle Bin
        public function sale_deleted($id = null)
        {
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
            
            $SaleInfo = $this->user_model->getRow('sales', ['id' => $id]);
            if ($SaleInfo->paid_amount != 0) {
                $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not cancel this.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
            
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['status'] = 0;
            $return = $this->user_model->updateAry('sales', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Sale"];
                $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) { 
                        $this->user_model->updateAry('invoice_product', $statusChange, $where);
                    }
                    $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Sale', 'transaction_type' => 'CR']);
                }
                $this->session->set_flashdata(['msg' => 'Sale Cancelled', 'class' => 'success']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
            
        // Delete From Recycle Bin (Actual)
        public function sale_deletes($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $return = $this->user_model->deleteRow('sales', $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "Sale"];
                $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                if ($res) {
                    foreach ($res as $da) {
                        $this->user_model->deleteRow('invoice_product', $where);
                    }
                    $this->user_model->deleteRow('payment', ['invoice_id' => $id, 'invoice_type' => 'Sale', 'transaction_type' => 'CR']);
                }

                if (isset($res) && !empty($res)) {
                    echo json_encode(['status' => true, 'msg' => 'Sale cancelled successfully.']);
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

        public function pdfSales()
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
                        $invoiceDate = ['invoiceDate' => date('Y-m-d', strtotime($this->input->get('invoiceDate')))];
                }
                if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                        $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                        $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                }
                if ($this->input->get('partyName')) {
                        $partyName = ['customerId' => $this->input->get('name')];
                }
                if ($this->input->get('productName')) {
                        $productName = ['product_ids' => $this->input->get('productName')];
                }
                $order = ['col' => 'added_at', 'order' => 'DESC'];
                $join = ['table' => 'invoice_product', 'condition' => 'sales.id = invoice_product.invoiceID AND invoice_product.type = "Sale"'];
                $result =  $this->user_model->getResult('sales', '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $join);
                $data['result'] = $result;
                $html = $this->load->view('inventory', $data, true);
                $pdfFilePath = "pdf/sales-" . time() . ".pdf";
                $pdf = $this->m_pdf->load();
                $pdf->AddPage('L');
                $pdf->WriteHTML($html, 2);
                $pdf->Output($pdfFilePath, "D");
        }

        public function sale_report($start = 0)
        {
            $data['page'] = 'sale_report/index';
            $invoiceNumber = '';
            $invoiceDate = '';
            $fromDate = '';
            $toDate = '';
            $partyName = '';
            $productName = '';
            $limit = '';

            $UserId = $this->session->userdata('user_id'); 
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            $SalesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $party = '';
            $SalesmanId = '';
            $partyFilter = $this->input->get('party');
            $salesmanFilter = $this->input->get('Salesman');
            $partyFilter = is_array($partyFilter) ? $partyFilter : [];
            $salesmanFilter = is_array($salesmanFilter) ? $salesmanFilter : [];
            if (!empty($partyFilter) && !in_array('All', $partyFilter, true)) {
                $party = $partyFilter;
            }
            if (!empty($salesmanFilter) && !in_array('All', $salesmanFilter, true)) {
                $SalesmanId = $salesmanFilter;
            }
            $first_date = date('Y-m-d'); // hard-coded '01' for first day
            $last_date  = date('Y-m-d');
            
            if ($this->input->get('sale_register') == '') {
                $fromDate = ['invoiceDate >=' => $first_date];
                $toDate = ['invoiceDate <=' => $last_date];
            }

            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
                $limit = '';
            }

            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                $first_date = date('Y-m-d', strtotime($this->input->get('fromDate')));
                $last_date = date('Y-m-d', strtotime($this->input->get('toDate')));
                $limit = '';
            }

            if ($this->input->get('sale_register')) {
                $limit = '';
                if ($this->input->get('sale_register') == 'general') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];
                    if ($this->input->get('invoiceStatus') != 'All') {
                        $where['status'] = $this->input->get('invoiceStatus');
                    }
                    if ($this->input->get('payment') == 'Paid') {
                        $this->db->where('paid_amount', 'total', false);
                    } elseif ($this->input->get('payment') == 'Unpaid') {
                        $this->db->group_start();
                        $this->db->where('paid_amount', NULL);
                        $this->db->or_where('paid_amount', 0);
                        $this->db->group_end();
                    } elseif ($this->input->get('payment') == 'Partial') {
                        $this->db->where('paid_amount != ', 'total', false);
                    }
                    $data['page'] = 'sale_report/general';
                } elseif ($this->input->get('sale_register') == 'hsn') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                    $data['page'] = 'sale_report/hsn';
                } elseif ($this->input->get('sale_register') == 'hsnB2B') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                    $data['page'] = 'sale_report/hsnB2B';                    
                } elseif ($this->input->get('sale_register') == 'hsnB2C') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                    $data['page'] = 'sale_report/hsnB2C';
                } elseif ($this->input->get('sale_register') == 'columnar') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                    $data['page'] = 'sale_report/columnar';
                } elseif ($this->input->get('sale_register') == 'detailview') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                    $data['page'] = 'sale_report/detailview';
                } elseif ($this->input->get('sale_register') == 'updateview') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear, 'updated !=' => '', 'updated_by !=' => ''];
                    $data['page'] = 'sale_report/updated-view';
                } elseif ($this->input->get('sale_register') == 'description') {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                    $data['page'] = 'sale_report/description';
                } else {
                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];
                    $data['page'] = 'sale_report/index';
                }
            } else {
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];
            }
            
            $pwhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $CompanyBranchId;
                $pwhere['CompanyBranchId'] = $CompanyBranchId;
                $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
            }

            $table = 'sales';
            $order = ['col' => 'id', 'order' => 'DESC'];
            $result = $this->user_model->getTblerDate($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where, '', $party, $SalesmanId);
            
            $data['parties'] = $this->db->select('id, name, contact, area')->where($pwhere)->order_by('name', 'ASC')->get('parties')->result();
            $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Id', 'DESC')->get('employees')->result();
            $data['result'] = $result;
            $data['fromDate'] = $first_date;
            $data['toDate'] = $last_date;
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function unique_sale()
        {
            $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
            $FinancialYear = $this->session->userdata('financial_year');
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            
            $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'invoiceNumber' => $invoiceNumber, 'status' => 1, 'financial_year' => $FinancialYear];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $return_value = $this->user_model->getRow('sales', $where);
            
            if ($return_value) {
                return false;
            }
            return true;
        }

        public function setDefaultValueFor()
        {
                $res = $this->user_model->getAry('sales', '', '', '', ['status' => 0]);
                if ($res) {
                        foreach ($res as $responce) {
                                $where = ['invoiceID' => $responce->id, 'type' => "Sale"];
                                $result = $this->user_model->getAry('invoice_product', '', '', '', $where);
                                if ($result) {
                                        foreach ($result as $rep) {
                                                $statusChange['status'] = 0;
                                                $this->user_model->updateAry('invoice_product', $statusChange, $where);
                                        }
                                }
                        }
                }
        }

        public function setDateFormat()
        {
                $res = $this->user_model->getResult('sales');
                if ($res) {
                        foreach ($res as $responce) {
                                $statusChange['invoiceDate'] = date('Y-m-d', strtotime($responce->invoiceDate));
                                $this->user_model->updateAry('invoice_product', $statusChange, ['invoiceID' => $responce->id, 'type' => 'Sale']);
                        }
                }
        }

        public function sendmail($id)
            {
                    if ($id) {
                            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'id' => $id];
                            if ($this->session->userdata('BranchRole')){
                                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                            }
                            $data['result'] = $this->user_model->getRow('sales', $where);

                            $this->load->view('inventory_sep/sale_report/sendmail', $data);
                    }
            }

    public function send_mail_to_user()
    {
        $this->form_validation->set_rules('sale_id', 'Something is Missing', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('email_cc', 'Mail CC', 'trim|required');
        $this->form_validation->set_rules('email_subject', 'Email Subject', 'trim|required');
        $this->form_validation->set_rules('email_body', 'Email Body', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }

        $sale_id = $this->input->post('sale_id');
        $email = $this->input->post('email');
        $email_cc = $this->input->post('email_cc');
        $email_subject = $this->input->post('email_subject');
        $iurl = base_url() . "printInvoice/" . $sale_id;
        $email_body = $this->input->post('email_body')
                . "\r\n\r\n"
                . "You can download or print invoice copy by clicking below link: "
                . "\r\n"
                . "$iurl";

        $name = get_title('users', ['id' => $this->session->userdata('user_id')], 'name');
        $smtp_mail_where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
        if ($this->session->userdata('BranchRole')){
            $smtp_mail_where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
        }
        $smtp_mail = get_title('comp_profile', $smtp_mail_where, 'smtp_username');

        if (empty($email_cc)) {
            echo json_encode(['status' => false, 'message' => "Failed, Please Check Smtp Setting"]);
            return;
        }

        $result = $this->user_model->getRow('sales', ['id' => $sale_id]);

        if (isset($result) && !empty($result)) {
            $products = $this->user_model->getAry('invoice_product', '', '', '', ['invoiceID' => $sale_id, 'type' => 'Sale']);
            foreach ($products as $row) {
                $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                $row->serials = $serials;
            }
            $result->product = $products;
            $data['result'] = $result;
        }
        //Load the library
        $this->load->library('html2pdf');
        //Set folder to save PDF to
        $this->html2pdf->folder('./uploads/salepdf/');
        //Set the filename to save/download as
        $this->html2pdf->filename(time() . '.pdf');
        //Set the paper defaults
        $this->html2pdf->paper('a4', 'portrait');
        //Load html view
        $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/sale/a5print', $data, true)));

        if ($path = $this->html2pdf->create('save')) {
            $result = $this->email->from($email_cc, $email_cc)
                                ->to($email)
                                ->subject($email_subject)
                                ->message($email_body)
                                ->attach($path)
                                ->send();
            if ($result) {
                unlink($path); //for delete generated pdf file.
                echo json_encode(['status' => true, 'message' => "Mail Sent"]);
                return; 
            } else {
                echo json_encode(['status' => false, 'message' => "Mail not Sent"]);
                return;
            }
            echo json_encode(['status' => true, 'message' => $path]);
            return;
        } else {
            $result = $this->email->from($email_cc, $email_cc)
                    ->to($email)
                    ->subject($email_subject)
                    ->message($email_body)
                    ->attach($path)
                    ->send();
            if ($result) {
                echo json_encode(['status' => true, 'message' => "Mail Sent without File(Due To File Error)"]);
                return;
            } else {
                echo json_encode(['status' => false, 'message' => "Mails not Sent"]);
                return;
            }
        }
    }

        public function salePrintMultipleInvoice()
        {
            if ($this->input->is_ajax_request()) {
                $post = $this->input->post();
                $SessionInvoiceData = [];
                if (($this->session->userdata('aman') !== null)) {
                    $this->session->unset_userdata('aman');
                }
                if (isset($post) && !empty($post)) {
                    foreach ($post['id'] as $key => $value) {
                        $SessionInvoiceData[] = array('id' => $value, 'type' => $this->input->post('type')[$key]);
                    }
                    $this->session->set_userdata("multi_invoice", $SessionInvoiceData);
                    $this->session->set_userdata("NumberOfInvoice", $post['NumberOfInvoice']);
                    $this->session->set_userdata("SaleInvoice", $post['SaleInvoice']);
                    $this->session->set_userdata("EstimateInvoice", $post['EstimateInvoice']);
                    echo json_encode(['status' => TRUE, 'data' => 'Invoice print successfully', 'url' => 'sessionInvoicePrint', 'class' => 'success']);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Something went wronng', 'class' => 'error']);
                }
            }
        }

        public function sessionInvoicePrint()
        {
            ini_set('memory_limit', '-1');
            set_time_limit(5000);
            
            $multi_invoice = $this->session->userdata('multi_invoice');
            if (isset($multi_invoice) && !empty($multi_invoice)) {
                $Saleresult = [];
                $Estimateresult = [];
                $InvoiceType = [];
                foreach ($multi_invoice as $key => $value) {
                    $where = ['id' => $value['id'], 'type' => $value['type']];
                    if ($value['type'] == 'Sale') {
                        $Saleresult[] = $this->user_model->getRow('sales', $where);
                        $InvoiceType[] = $value['type'];
                    }
                    if ($value['type'] == 'Estimate') {
                        $Estimateresult[] = $this->user_model->getRow('estimates', $where);
                        $InvoiceType[] = $value['type'];
                    }
                }

                $data['NumberOfInvoice'] = $this->session->userdata('NumberOfInvoice');
                if (($this->session->userdata('aman') !== null)) {
                    $this->session->unset_userdata('aman');
                }
                
                if (in_array('Sale', $InvoiceType) && $this->session->userdata('SaleInvoice') == 'Sale') {
                    $data['result'] = $Saleresult;
                    $this->load->view('inventory_sep/sale_report/multiple_pdf', $data);
                } 
                if (in_array('Estimate', $InvoiceType) && $this->session->userdata('EstimateInvoice') == 'Estimate') {
                    $data['result'] = $Estimateresult;
                    $this->load->view('inventory_sep/estimate/multiple_pdf', $data);
                }
            } else {
                echo json_encode(['status' => false, 'data' => 'Something Went Wronng', 'class' => 'error']);
            }
        }

        public function paymentModeUpdate()
        {
            $this->form_validation->set_rules('ammoouunntt', 'Amount', 'trim|required|numeric');
            $this->form_validation->set_rules('saleIdForUpdate', 'Sale', 'trim|required');
            $this->form_validation->set_rules('paymentModes', 'Payment Mode', 'trim|required');
            if ($this->form_validation->run() == false) {
                    $response = array(
                            'status' => 'error',
                            'message' => validation_errors()
                    );
            } else {
                    $amount = $this->input->post('ammoouunntt');
                    $saleId = $this->input->post('saleIdForUpdate');
                    $mode = $this->input->post('paymentModes');
                    $data['paymentAmount'] = $amount;
                    $data['paymentMode'] = $mode;
                    $res = $this->user_model->updateAry('sales', $data, ['id' => $saleId]);
                    if ($res) {
                            $response = array(
                                    'status' => 'success',
                                    'message' => "<h3>Payment Completed</h3>",
                                    'data'=>$saleId
                            );
                    } else {
                            $response = array(
                                    'status' => 'error',
                                    'message' => "<h3>Something Went Wrong</h3>"
                            );
                    }
            }

            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
        }
            
        public function getLastMaxPriceOfProductBySale ()
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
                    $this->db->join('invoice_product', 'sales.id = invoice_product.invoiceID');
                    $this->db->where('sales.user_id', $UserId);
                    $this->db->where('sales.comp_id', $CompId);
                    $this->db->where('sales.customerId', $party_id);
                    $this->db->where('sales.status', 1);
                    $this->db->where('invoice_product.product_id', $product_id);
                    $this->db->where('invoice_product.status', 1);
                    $this->db->where('invoice_product.type', 'Sale');
                    if ($this->session->userdata('BranchRole')) {
                        $this->db->where('sales.CompanyBranchId', $CompBranchId);
                        $this->db->where('invoice_product.CompanyBranchId', $CompBranchId);
                    }
                    $this->db->order_by('sales.id', 'DESC');
                    $SaleResult = $this->db->get('sales')->row();
                    if ($SaleResult) {
                        echo json_encode(['status' => true, 'data' => $SaleResult]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Not Found']);
                    }
                }
            }
        }
        
        public function set_warehouse_for_product()
        {
            return $this->load->view('inventory_sep/sale/ChangeWarehouse');
        }
        
        public function party_sale_report ()
        {            
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $companyBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['user_id' => $userId, 'comp_id' => $compId];
            $partyWhere = ['user_id' => $userId, 'comp_id' => $compId];
            $salemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $salesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $salemanRoleId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $companyBranchId;
                $partyWhere['CompanyBranchId'] = $companyBranchId;
                $salesmanWhere['CompanyBranchId'] = $companyBranchId;
            }

            if ($this->input->get('partyId') != "All" && $this->input->get('partyId') != "") {
                    $where['id'] = $this->input->get('partyId');
            }
            
            $accountGroupId = get_title('accounting_group', ['name' => 'Sundry Debitors', 'type' => 'CR', 'status' => 1], 'id');
            
            $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->get('employees')->result();
            $salesmanFilter = $this->input->get('salesman');
            $salesmanFilter = is_array($salesmanFilter) ? $salesmanFilter : [];
            if (!empty($salesmanFilter) && in_array('All', $salesmanFilter, true)) {
                $data['partySales'] = $this->db->select('id, name, contact, gst, SalesmanId')->where($where)->where('group_head', $accountGroupId)->get('parties')->result();
            } elseif (!empty($salesmanFilter)) {
                $data['partySales'] = $this->db->select('id, name, contact, gst, SalesmanId')->where($where)->where('group_head', $accountGroupId)->where_in('SalesmanId', $salesmanFilter)->get('parties')->result();
            } else {
                $data['partySales'] = $this->db->select('id, name, contact, gst, SalesmanId')->where($where)->where('group_head', $accountGroupId)->get('parties')->result();
            }
            $data['parties'] = $this->db->select('id, name, area, contact')->where($partyWhere)->where('group_head', $accountGroupId)->get('parties')->result();
            $data['page'] = 'sale_report/party_sale_report';
            return $this->load->view('inventory_sep/inventory', $data);    
        }
        
        public function partyInvoiceInfo()
        {
                $partyId = $this->input->get('partyId');
                $from = $this->input->get('from');                
                $to = $this->input->get('to');
                
                if (isset($partyId) && isset($from) && isset($to)) {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['invoiceDate >= ' => $from, 'invoiceDate <= ' => $to, 'customerId' => $partyId, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $compBranchId;
                        }
                        
                        $partySaleInvoiceQuery = $this->db->select('id, invoiceDate, invoiceNumber, type, total, paid_amount')->from('sales')->where($where)->get_compiled_select();
                        $partyEstimateInvoiceQuery = $this->db->select('id, invoiceDate, invoiceNumber, type, total, paid_amount')->from('estimates')->where($where)->get_compiled_select();

                        $data['partySaleAndEstimateInvoices'] = $this->db->query($partySaleInvoiceQuery . " UNION " . $partyEstimateInvoiceQuery . " ORDER BY id DESC")->result();
                        $data['partyName'] = get_title('parties', ['id' => $partyId], 'name');
                        return $this->load->view('inventory_sep/sale_report/partyInvoiceInfo', $data);                        
                }
        }
        
        public function POS_1($id = null)
        {
            if (!$id) {
                return redirect('sale');
            }

            $where = ['id' => $id];
            $result = $this->user_model->getRow('sales', $where);

            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;

                $this->load->view('inventory_sep/sale/print/POS-1', $data);
            }
        }
        
        public function CheckSaleInvoiceNumber ()
        {
            if ($_POST) {
                $InvoiceNumber = $this->input->post('InvoiceNumber');
                if ($InvoiceNumber != NULL && !empty($InvoiceNumber)) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    
                    $Where = ['invoiceNumber' => $InvoiceNumber, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $Result = $this->db->where($Where)->get('sales')->num_rows();
                    
                    if ($Result) {
                        echo json_encode(['status' => true]);
                    } else {
                        echo json_encode(['status' => false]);
                    }
                } else {
                    echo json_encode(['status' => false]);
                }
            } else {
                echo json_encode(['status' => false]);
            }
        }
        
        public function Copy($Id)
        {
            if (empty($Id)) {
                return redirect(base_url() . 'Sale/sale_report');
            } else {
                $Result = $this->user_model->getRow('sales', ['id' => $Id]);
                if (isset($Result) && !empty($Result)) {
                    $data['CopiedInvoiceNumber'] = $Result->invoiceNumber;
                    $data['page'] = 'sale/CopySale';
                    
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Sale/sale_report');
                }   
            }
        }

        public function saveHoldInvoice()
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

            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
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
            $data['tds'] = $this->input->post('tds');
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
            $data['created_by'] = $this->session->userdata('RoleType') ? get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name') : get_title('users', ['id' => $UserId], 'name');
            
            $holdinvoiceid = $this->input->post('holdinvoiceid');
            if ($holdinvoiceid > 0) { 
                $result = $this->user_model->updateAry('hold_invoice', $data, ['id' => $holdinvoiceid]);
            } else {
                $result = $this->user_model->saveAry('hold_invoice', $data);     
            }

            if (isset($result) && !empty($result)) {
                echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function getHoldInvoice()
        {    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $FinancialYear = $this->session->userdata('financial_year');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                if ($this->session->userdata('BranchRole')){
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                $WhereIn = [];
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true);
                    foreach ($EmployeeIds as $key => $Id) {
                        $WhereIn[] = $Id;
                    }
                }

                $this->db->where($Where);
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $this->db->where_in('SalesmanId', $WhereIn);
                }
                $this->db->order_by('added_at', 'DESC');
                $Result = $this->db->get('hold_invoice')->result();
                if ($Result) {
                    echo json_encode(['status' => 1, 'result' => $Result]); 
                } else {
                    echo json_encode(['status' => 0, 'result' => ""]); 
                }
            }
        }
        
        public function getHoldInDetails()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $this->input->post('id');
                $result = $this->user_model->getRow('hold_invoice', ['id' => $id]); 
                if ($result) {
                    echo json_encode(['status' => true, 'result' => $result]); 
                } else {
                    echo json_encode(['status' => false, 'result' => ""]); 
                }
            }
        }
        
        public function DeleteHoldInvoice ()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $HoldInvoiceId = $this->input->post('HoldInvoiceId');
                $result = $this->user_model->getRow('hold_invoice', ['id' => $HoldInvoiceId]); 
                if ($result) {
                    $this->user_model->deleteRow('hold_invoice', ['id' => $HoldInvoiceId]); 
                    echo json_encode(['status' => true, 'result' => $result]); 
                } else {
                    echo json_encode(['status' => false, 'result' => ""]); 
                }
            }
        }
        
        public function ChequeInfo($SaleId) 
        {
            if (empty($SaleId)) {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $sql = "SELECT id FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$SaleId\"]') AND transaction_type = 'DR'";
                $query = $this->db->query($sql);
                if ($this->db->select('id')->where('id', $SaleId)->where('type', 'Sale')->get('sales')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, type, total, SalesmanId')->where('id', $SaleId)->where('type', 'Sale')->get('sales')->row();
                }
                if ($this->db->select('id')->where('id', $SaleId)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, type, total, SalesmanId')->where('id', $SaleId)->where('type', 'Estimate')->get('estimates')->row();
                }
                if ($query->num_rows()) {
                    $sql1 = "SELECT id, date, paymentmode, ChequeDate, debit, remark FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$SaleId\"]') AND transaction_type = 'DR'";
                    $query1 = $this->db->query($sql1);
                    $data['PaymentInfo'] = $query1->row();
                } else {
                    $data['PaymentInfo'] = $this->db->select('id, date, paymentmode, ChequeDate, debit, remark')->where('invoice_id', $SaleId)->where('transaction_type', 'DR')->get('payment')->row();
                }
                return $this->load->view('inventory_sep/sale_report/ChequeInfo', $data);
            }
        }
        
        public function ChequeStateUpdate() 
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
                    $Result = $this->user_model->updateAry('payment', $PaymentData, ['id' => $this->input->post('PaymentId')]);
                }
                
                if ($this->input->post('ChequeStatus') == 0) {
                    $InvoiceData = [
                        'updated' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        'updated_by' => date('d-m-Y h:i:s A')
                    ];
                    
                    if ($this->input->post('Type') == "Sale") {
                        $InvoiceData['paid_amount'] = (get_title('sales', ['id' => $this->input->post('SaleId')], 'paid_amount') - $this->input->post('Amount'));
                        $this->user_model->updateAry('sales', $InvoiceData, ['id' => $this->input->post('SaleId'), 'type' => 'Sale']);
                    }
                    if ($this->input->post('Type') == "Estimate") {
                        $InvoiceData['paid_amount'] = (get_title('estimates', ['id' => $this->input->post('SaleId')], 'paid_amount') - $this->input->post('Amount'));
                        $this->user_model->updateAry('estimates', $InvoiceData, ['id' => $this->input->post('SaleId'), 'type' => 'Estimate']);
                    }
                    $Result = $this->user_model->deleteRow('payment', ['id' => $this->input->post('PaymentId')]);
                }
                
                if ($Result) {
                    $this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
                
        public function DeliveryChatHandler($InvoiceId) 
        {
            if (!empty($InvoiceId)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $SaleWhere = ['id' => $InvoiceId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $SaleWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->db->select('id')->where($SaleWhere)->where('type', 'Sale')->get('sales')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, total')->where($SaleWhere)->get('sales')->row();
                } elseif ($this->db->select('id')->where($SaleWhere)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, total')->where($SaleWhere)->get('estimates')->row();
                } else {
                    $data['SaleInfo'] = null;
                }
                return $this->load->view('inventory_sep/Employee/DeliveryChatHandler', $data);
            }
        }
        
        public function DeliveryChatSave() 
        {
            if ($this->input->is_ajax_request()) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $this->form_validation->set_rules('Chat', 'Chat', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $DeliveryChatData = [
                        'Date' => date('Y-m-d'),
                        'InvoiceId' => $this->input->post('InvoiceId'),
                        'Chat' => $this->input->post('Chat'),
                        'UserId' => $UserId,
                        'CompId' => $CompId,
                        'CompanyBranchId' => $CompBranchId,
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $UserId], 'name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('sale_delivery_chat', $DeliveryChatData);
                    if (!empty($Result)) {
                        echo json_encode(['Status' => true, 'Message' => 'Message send successfully.']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Message' => 'Unable to send Message.']);
                        return;
                    }
                }
            }
        }
        
        public function getSaleDeliveryChat() 
        {
            if ($this->input->is_ajax_request()) {
                $InvoiceId = $this->input->post('InvoiceId');
                if ($InvoiceId == "" && $InvoiceId == null) {
                    echo json_encode(['Status' => false, 'Chat' => '<span class="text-danger text-center">Enter required fields.</span>', 'From' => '', 'To' => '']);
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                                
                    $Where = ['InvoiceId' => $InvoiceId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId; 
                    }
                    
                    $DeliveryChat = $this->db->select('Chat, Created, CreatedBy')->where($Where)->order_by('Id', 'ASC')->get('sale_delivery_chat')->result();

                    if ($DeliveryChat) {
                        $ImageUrl = base_url() . 'assets/images/avatar.png';
                        $chatHtml = '';

                        foreach ($DeliveryChat as $key => $Chat) {
                            $chatHtml .= '<div class="card-comment">'
                                      . '<img class="img-circle img-sm" src="' . $ImageUrl . '" alt="User-Image" />'
                                      . '<div class="comment-text">'
                                      . '<span class="username">'
                                      . $Chat->CreatedBy
                                      . '<span class="text-muted float-right">'
                                      . $Chat->Created
                                      . '</span>'
                                      . '</span>'
                                      . $Chat->Chat
                                      . '</div>'
                                      . '</div>';
                        }
                        echo json_encode(['Status' => true, 'Chat' => $chatHtml]);
                    } else {
                        echo json_encode(['Status' => false, 'Chat' => '<span class="text-danger text-center">No Record found.</span>', 'From' => '', 'To' => '']);
                    }
                }
            }
        }
        
        public function ExcelDataToSaleInvoice() 
        {            
            $data['page'] = 'sale/ExcelDataToSaleInvoice';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function CreateSaleInvoices() 
        {
            ini_set('memory_limit', '-1');
            set_time_limit(-1);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES["ExcelSaleData"]["name"]) && $_FILES["ExcelSaleData"]["name"] != "") {
                    $FileType = $_FILES["ExcelSaleData"]["type"];
                    if ($FileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $FileType == 'application/vnd.ms-excel' || $FileType == 'application/vnd.ms-office') {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                        $CompBranchId = $this->session->userdata('CompBranchId');
                        
                        $ExcelFile = $_FILES["ExcelSaleData"]["tmp_name"];
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
                            $SaleIds = [];
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
                                            
                                            $PartyWhere = ['name' => $row['B'], 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'group_head' => get_title('accounting_group', ['name' => 'Sundry Debitors', 'status' => 1], 'id')];
                                            if ($this->session->userdata('BranchRole')) {
                                                $PartyWhere['CompanyBranchId'] = $CompBranchId;
                                            }
                                            
                                            if ($this->db->select('name')->where($PartyWhere)->get('parties')->num_rows() == 0) {
                                                $CustomerData = [
                                                    'group_head' => get_title('accounting_group', ['name' => 'Sundry Debitors', 'status' => 1], 'id'),
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
                                                    'opBalnceType' => 'DR',
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
                                            
                                                               $PartyWhere = ['name' => $row['E'], 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'group_head' => get_title('accounting_group', ['name' => 'Sundry Debitors', 'status' => 1], 'id')];
                                                               $SaleInvoiceWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                                               if ($this->session->userdata('BranchRole')) {
                                                                       $SaleInvoiceWhere['CompanyBranchId'] = $CompBranchId;
                                                                       $PartyWhere['CompanyBranchId'] = $CompBranchId;
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
                                                               
                                                               $SaleInvoiceWhere['invoiceNumber'] = $row['C'];
                                                               if ($this->db->select('id')->where($SaleInvoiceWhere)->get('sales')->num_rows() > 0) continue;
                                            
                                                               $SaleData = [
                                                                       'financial_year' => get_title('year', ['year' => $row['Q']], 'id'),
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
                                                                       'shippingInfo' => $row['F'],
                                                                       'gstNo' => $CustomerInfo->gst,
                                                                       'parties' => json_encode($JSONCustomerData),
                                                                       'format_invoice' => 'Tax',
                                                                       'voucherType' => $VoucherType,
                                                                       'taxCal' => 1,
                                                                       'user_id' => $UserId,
                                                                       'comp_id' => $CompId,
                                                                       'CompanyBranchId' => $CompBranchId,
                                                                       'taxableAmount' => $row['G'],
                                                                       'totalTax' => $row['H'],
                                                                       'tds' => $row['I'],
                                                                       'totalsgst' => $row['J'],
                                                                       'totalcgst' => $row['K'],
                                                                       'totaligst' => $row['L'],
                                                                       'amountInWords' => amount_in_words($row['M']),
                                                                       'totalValue' => $row['M'],
                                                                       'roundOff' => $row['N'],
                                                                       'total' => $row['O'],
                                                                       'type' => 'Sale',
                                                                       'remark' => $row['P'],
                                                                       'created' => date('d-m-Y h:i:s A'),
                                                                       'created_by' => get_title('users', ['id' => $UserId], 'name')
                                                               ];
                                                               $SaleIdData = $this->user_model->saveAry('sales', $SaleData);
                                                               $SaleIds[] = $SaleIdData;
                                            
                                            if (isset($SaleIdData) && !empty($SaleIdData)) {
                                                $PaymentCredit = [
                                                    'date' => $InvoiceDate,
                                                    'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                                                    'party_id' => $CustomerInfo->id,
                                                    'invoice_id' => $SaleIdData,
                                                    'invoice_type' => 'Sale',
                                                    'credit' => $row['O'],
                                                    'transaction_type' => 'CR',
                                                    'user_id' => $UserId,
                                                    'comp_id' => $CompId,
                                                    'CompanyBranchId' => $CompBranchId,
                                                    'financial_year' => get_title('year', ['year' => $row['Q']], 'id'),
                                                    'created' => date('d-m-Y h:i:s A')
                                                ];
                                                $this->user_model->saveAry('payment', $PaymentCredit);
                                            }
                                        }
                                    }
                                    unset($InvoiceDate);
                                    unset($VoucherType);
                                    unset($PartyWhere);
                                    unset($CustomerInfo);
                                    unset($CityName);
                                    unset($StateName);
                                    unset($SaleIdData);
                                    unset($SaleInvoiceWhere);
                                    unset($row);
                               } elseif ($SheetName == 'InvoiceProducts') {
                                    foreach ($Sheet as $index => $row) {
                                        if (!empty($row) && array_filter($row)) {
                                            if ($index === 1) continue;                                            
                                            
                                            $WarehouseWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            $ProductWhere = ['p_name' => $row['D'], 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            $ProductCodeWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            $SaleInvoiceProductWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            $categoryWhere = ['name' => $row['E'], 'userId' => $UserId, 'compId' => $CompId, 'isDeleted' => 1];
                                            $brandWhere = ['name' => $row['F'], 'userId' => $UserId, 'compId' => $CompId, 'isDeleted' => 1];
                                            if ($this->session->userdata('BranchRole')) {
                                                $WarehouseWhere['CompanyBranchId'] = $CompBranchId;
                                                $ProductWhere['CompanyBranchId'] = $CompBranchId;
                                                $ProductCodeWhere['CompanyBranchId'] = $CompBranchId;
                                                $SaleInvoiceProductWhere['CompanyBranchId'] = $CompBranchId;
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
                                            
                                            $SaleInvoiceProductWhere['invoiceNumber'] = $row['C'];
                                            if ($this->db->select('id')->where($SaleInvoiceProductWhere)->get('sales')->num_rows() > 0) {
                                                $SaleId = $this->db->select('id')->where($SaleInvoiceProductWhere)->get('sales')->row('id');
                                            
                                                $InvoiceProductData = [
                                                    'invoiceDate' => date('Y-m-d', strtotime(PHPExcel_Style_NumberFormat::toFormattedString($row['B'], 'YYYY-MM-DD'))),
                                                    'invoiceID' => $SaleId,
                                                    'InvoiceNo' => $row['C'],
                                                    'type' => 'Sale',
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

                                                $NumOfItems = $this->db->select('id')->where('invoiceID', $SaleId)->where('type', 'Sale')->get('invoice_product')->num_rows();
                                                $ProductsIdsData = $this->db->select('product_id')->where('invoiceID', $SaleId)->where('type', 'Sale')->get('invoice_product')->result();
                                                $ProductIds = [];
                                                foreach ($ProductsIdsData as $key => $ProductIdInfo) {
                                                    $ProductIds[] = $ProductIdInfo->product_id;
                                                }

                                                $this->user_model->updateAry('sales', ['product' => json_encode($JSONProductData), 'product_ids' => implode(',', $ProductIds), 'num_of_items' => $NumOfItems], ['id' => $SaleId]);
                                            }
                                        }
                                    }
                                    unset($WarehouseWhere);
                                    unset($ProductWhere);
                                    unset($ProductCodeWhere);
                                    unset($SaleInvoiceProductWhere);
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
                                    unset($SaleId);
                                    unset($row);
                                } elseif ($SheetName == 'Payments') {
                                    foreach ($Sheet as $index => $row) {
                                        if (!empty($row) && array_filter($row)) {
                                            if ($index === 1) continue;
                                            
                                            $VoucherNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                                            $SaleWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                                            $PaymentWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1, 'payment_type' => 'Receipt'];
                                            if ($this->session->userdata('BranchRole')) {
                                                $VoucherNumberWhere['CompanyBranchId'] = $CompBranchId;
                                                $SaleWhere['CompanyBranchId'] = $CompBranchId;
                                            }
                                            $VoucherNumber = ($this->db->select('id')->where($VoucherNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1);
                                            
                                            $SaleWhere['invoiceNumber'] = $row['C'];
                                            $InvoiceData = $this->db->select('id, customerId, type, total')->where($SaleWhere)->get('sales')->row();
                                            $ChequeDate = $row['G'] ? date('Y-m-d', strtotime(PHPExcel_Style_NumberFormat::toFormattedString($row['G'], 'YYYY-MM-DD'))) : "";
                                            
                                            if (empty($this->session->userdata('BranchRole'))) {
                                                $Sql = "SELECT SUM(debit) AS debit FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Receipt' AND invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$InvoiceData->id\"]')";
                                            } else {
                                                $Sql = "SELECT SUM(debit) AS debit FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompBranchId . "' AND status = 1 AND payment_type = 'Receipt' AND invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$InvoiceData->id\"]')";
                                            }
                                            $Query = $this->db->query($Sql);
                                            $DebitAmount = $Query->row('debit');
                                            
                                            if ($DebitAmount == $InvoiceData->total) continue;
                                            
                                            $PaymentData = [
                                                'voucher_type' => 'Tax',
                                                'voucher_number' => $VoucherNumber,
                                                'date' => date('Y-m-d', strtotime(PHPExcel_Style_NumberFormat::toFormattedString($row['B'], 'YYYY-MM-DD'))),
                                                'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                                                'party_id' => $InvoiceData->customerId,
                                                'invoice_id' => json_encode(array($InvoiceData->id)),
                                                'invoice_type' => json_encode(array($InvoiceData->type)),
                                                'receivedPaidAmount' => $row['D'],
                                                'cashDiscountAmount' => $row['E'],
                                                'debit' => ($row['D'] + $row['E']),
                                                'transaction_type' => 'DR',
                                                'payment_type' => 'Receipt',
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
                                            $this->user_model->updateAry('sales', ['paid_amount' => ($row['D'] + $row['E'])], ['id' => $InvoiceData->id]);
                                        }
                                    }
                                    unset($VoucherNumberWhere);
                                    unset($SaleWhere);
                                    unset($PaymentWhere);
                                    unset($VoucherNumber);
                                    unset($InvoiceData);
                                    unset($DebitAmount);
                                    unset($ChequeDate);
                                }
                            }
                            
                            $data['NewCustomers'] = count($CustomerIds);
                            $data['NewInvoices'] = count($SaleIds);
                            $data['NewProducts'] = count($NewProductIds);
                            $data['NewInvoiceProducts'] = count($InvoiceProductIds);
                            $data['NewPayments'] = count($PaymentIds);
                            $data['page'] = 'sale/ExcelDataToSaleInvoice';
                            return $this->load->view('inventory_sep/inventory', $data);
                        } catch (Exception $e) {
                            die('Error loading file "' . pathinfo($ExcelFile, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Please choose excel file for import data', 'class' => 'danger']);
                        return redirect(base_url() . 'Sale/ExcelDataToSaleInvoice');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Please choose a file to upload.', 'class' => 'danger']);
                    return redirect(base_url() . 'Sale/ExcelDataToSaleInvoice');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something Went wrong, try again', 'class' => 'danger']);
                return redirect(base_url() . 'Sale/ExcelDataToSaleInvoice');
            }
        }
        
        public function SalesInovices()
        {
            $Where = ['sales.user_id' => $this->session->userdata('user_id'), 'sales.comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['sales.CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            // Sale Invoices Report ** Get record count ** 
            $conditions['returnType'] = 'count'; 
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getSalesInvoicesData($conditions, 'sales'); 

            // Pagination configuration 
            $config['target']      = '.SalesInvoices'; 
            $config['base_url']    = base_url() . 'Sale/AjaxSaleInvoicesData'; 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = 50; 
            $config['link_func']   = 'searchFilter'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions = array('limit' => 50); 
            $conditions['where'] = $Where;
            $data['SalesInvoices'] = $this->user_model->getSalesInvoicesData($conditions, 'sales'); 
            $data['page'] = 'sale_report/SalesInovices';
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function AjaxSaleInvoicesData()
        {
            $Where = ['sales.user_id' => $this->session->userdata('user_id'), 'sales.comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['sales.CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            // Define offset 
            $page = $this->input->post('Page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('Keywords'); 
            $sortBy = $this->input->post('SortBy');
            $pagelength = $this->input->post('PageLength');
            
            if (!empty($keywords)) { 
                $conditions['search']['Keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['SortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getSalesInvoicesData($conditions, 'sales'); 

            // Pagination configuration 
            $config['target']      = '.SalesInvoices'; 
            $config['base_url']    = base_url() . 'Sale/AjaxSaleInvoicesData';
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $pagelength; 
            $config['link_func']   = 'searchFilter'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $pagelength;
            $conditions['where'] = $Where;
            unset($conditions['returnType']); 
            $data['SalesInvoices'] = $this->user_model->getSalesInvoicesData($conditions, 'sales');
            return $this->load->view('inventory_sep/sale_report/Invoices', $data, false);
        }
        
        public function formE($invoiceId)
        {
            $data['invoiceId'] = $invoiceId;
            return $this->load->view('inventory_sep/sale/formE', $data);
        }
        
        public function invoiceHistory($invoiceId, $newSaleInvoiceData)
        {
            if ($this->session->userdata('RoleType')) {
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $editedBy = get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name');
            } else {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $editedBy = get_title('users', ['id' => $userId], 'name');
            }
            $compBranchId = $this->session->userdata('CompBranchId');

            // Define conditions for fetching invoice and product data
            $where = ['id' => $invoiceId, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];

            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }

            // Get old invoice data
            $oldSaleInvoiceData = $this->db->get_where('sales', $where)->row_array();

            // Find changes in sale invoice
            $saleInvoiceChanges = [];
            foreach ($newSaleInvoiceData as $key => $value) {
                if (isset($oldSaleInvoiceData[$key]) && $oldSaleInvoiceData[$key] != $value) {
                    $saleInvoiceChanges[$key] = ['old' => $oldSaleInvoiceData[$key], 'new' => $value];
                }
            }

            // Log changes if any exist
            if (!empty($saleInvoiceChanges) || !empty($saleInvoiceProductChanges)) {
                $historyData = [
                    'date' => date('Y-m-d'),
                    'invoiceId' => $invoiceId,
                    'editedDateTime' => date('d-m-Y h:i:s A'),
                    'editedBy' => $editedBy,
                    'saleInvoiceChanges' => !empty($saleInvoiceChanges) ? json_encode($saleInvoiceChanges, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
                    'compId' => $compId,
                    'userId' => $userId,
                    'compBranchId' => $compBranchId
                ];

                $this->db->insert('sales_edit_history', $historyData);
            }
        }
    }