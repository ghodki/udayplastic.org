<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Credit_note extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }

        public function index()
        {
            $data['page'] = 'credit_note/index'; 
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function update()
        {
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }
            $id = $this->input->post('printcredit_note');
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Something Is Missing.']);
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
                    continue;
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
            $data['shipping_address'] = $this->input->post('shipping_hideen_address');
            $data['taxCal'] = $this->input->post('taxcal');
            $data['comp_id'] = $this->session->userdata('comp_id');
            $data['user_id'] = $this->session->userdata('user_id');
            $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
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
            $data['SaleInvoiceId'] = $this->input->post('SaleInvoiceId');
            $data['SaleInvoiceNumber'] = $this->input->post('SaleInvoiceNumber');
            
            $where = ['id' => $id];
            $there = ['invoiceID' => $id];
            $PreviewsTotal = get_title('credit_notes', ['id' => $id], 'total');
            $result = $this->user_model->updateAry('credit_notes', $data, $where);
            $invoice_pro = $this->user_model->getAry('credit_product', '', '', '', $there);
            $credit_note_condition = ['invoiceID' => $id, 'type' => 'CreditNote'];
            if ($invoice_pro) {
                foreach ($invoice_pro as $invoice) {
                    $this->user_model->deleteRow('credit_product', $credit_note_condition);
                }
            }
            $resultProduct = $this->user_model->bulkCreditNote('credit_product', $array_of_products, $id, $data['invoiceNumber'], $data['invoiceDate']);
            if (isset($result) && !empty($result) && !empty($resultProduct)) {
                // payment entry
                if ($this->db->where('invoice_id', $this->input->post('SaleInvoiceId'))->where('invoice_type', 'Sale')->where('transaction_type', 'DR')->get('payment')->num_rows() > 0) {
                    $PaymentDebit = [
                        'party_id' => $this->input->post('customerId'),
                        'receivedPaidAmount' => $this->input->post('total'),
                        'debit' => $this->input->post('total'),
                        'updated' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->updateAry('payment', $PaymentDebit, ['invoice_id' => $this->input->post('SaleInvoiceId'), 'invoice_type' => 'Sale', 'transaction_type' => 'DR']);

                    $SaleUpdate = [
                        'paid_amount' => ((get_title('sales', ['id' => $this->input->post('SaleInvoiceId')], 'paid_amount') - $PreviewsTotal) + $this->input->post('total'))
                    ];
                    $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $this->input->post('SaleInvoiceId')]);
                }
                // payment entry end
                echo json_encode(['status' => true, 'data' => $id, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function saveCreditNote()
        {
            $product = $this->input->post('product');
            $array_of_products = [];
            foreach ($product as $row) {
                $qty = $row['qty'];
                $netAmount = $row['netAmount'];
                $rate = $row['rate'];
                $product_id = $row['product'];
                if ($qty == '' || $netAmount == '' || $rate == '' || $product_id == '') {
                    continue;
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

            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required|callback_unique_credit_note');
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
            
            $CompId = $this->session->userdata('comp_id');
            $UserId = $this->session->userdata('user_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $invoiceNumber = $this->input->post('invoiceNumber');
            
            $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $CompProfileWhere['CompanyBranchId'] = $CompBranchId;
            }
            $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
            
            $data['invoiceNumber'] = ucwords($invoiceNumber);
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
            $data['shipping_address'] = $this->input->post('shipping_address');
            $data['comp_id'] = $CompId;
            $data['user_id'] = $UserId;
            $data['CompanyBranchId'] = $CompBranchId;
            $data['financial_year'] = $FinancialYear;
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
            $data['SaleInvoiceId'] = $this->input->post('SaleInvoiceId');
            $data['SaleInvoiceNumber'] = $this->input->post('SaleInvoiceNumber');
            $data['SaleInvoiceDate'] = $this->input->post('SaleInvoiceDate');
            
            $result = $this->user_model->saveAry('credit_notes', $data);
            $this->user_model->bulkCreditNote('credit_product', $array_of_products, $result, $data['invoiceNumber'], $data['invoiceDate']);
            
            if (isset($result) && !empty($result)) {                
                // payment entry
                $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
                if ($this->session->userdata('BranchRole')) {
                    $SeriesWhere['CompanyBranchId'] = $CompBranchId;
                    $UnorderNumberWhere['CompanyBranchId'] = $CompBranchId;
                }
                $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->where('status', 1)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                $PaymentDebit = [
                    'voucher_type' => 'Tax',
                    'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                    'date' => date('Y-m-d'),
                    'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                    'party_id' => $this->input->post('customerId'),
                    'invoice_id' => $this->input->post('SaleInvoiceId'),
                    'invoice_type' => 'Sale',
                    'receivedPaidAmount' => $this->input->post('total'),
                    'debit' => $this->input->post('total'),
                    'transaction_type' => 'DR',
                    'payment_type' => 'Receipt',
                    'paymentmode' => get_title('payment_mode', ['payment_method' => 'Credit Note'], 'id'),
                    'user_id' => $UserId,
                    'comp_id' => $CompId,
                    'CompanyBranchId' => $CompBranchId,
                    'financial_year' => $FinancialYear,
                    'remark' => "Credit Note Number : " . $invoiceNumber,
                    'created' => date('d-m-Y h:i:s A')
                ];
                $this->user_model->saveAry('payment', $PaymentDebit);

                $GetSeriesWhere = ['series_id' => $Series->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
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

                $SaleUpdate = [
                    'paid_amount' => (get_title('sales', ['id' => $this->input->post('SaleInvoiceId')], 'paid_amount') + $this->input->post('total'))
                ];
                $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $this->input->post('SaleInvoiceId')]);
                // payment entry end 
                
                $where1 = ['series_id' => $this->input->post('series_id'), 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $where1['CompanyBranchId'] = $CompBranchId;
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
                    $only_number_invoice['unorder_invoice_number'] = $invoiceNumber;
                    $this->user_model->updateAry('credit_notes', $only_number_invoice, ['id' => $result]);
                }
                
                echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function edit($id = null)
        {
            if (empty($id)) {
                $data['page'] = 'error';
                return $this->load->view('inventory_sep/inventory', $data);
            }

            $data['page'] = 'credit_note/edit';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('credit_notes', $where);
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note');
            }
        }

        public function view($id = null)
        {
            if (!$id) {
                return redirect(base_url() . 'credit_note');
            }

            $data['page'] = 'credit_note/view';

            $where = ['id' => $id];
            $result = $this->user_model->getRow('credit_notes', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'CreditNote'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note');
            }
        }

        public function printCreditNote($id = null)
        {
            if (!$id) {
                return redirect(base_url() . 'credit_note');
            }

            $where = ['id' => $id];
            $result = $this->user_model->getRow('credit_notes', $where);
            $pwhare = ['invoiceID' => $id, 'InvoiceNo' => $result->invoiceNumber, 'type' => 'CreditNote'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
                if ($fromet == '4inch') {
                    return $this->load->view('inventory_sep/credit_note/print4inch', $data);
                } else {
                    return $this->load->view('inventory_sep/credit_note/print', $data);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note');
            }
        }

        public function printcredit_notePreview($id = null)
        {
            if (!$id) {
                return redirect(base_url() . 'credit_note');
            }
            $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
            if ($fromet == '4inch') {
                $data['page'] = 'credit_note/4inpreview';
            } elseif ($fromet == 'A5') {
                $data['page'] = 'credit_note/a5preview';
            } else {
                $data['page'] = 'credit_note/a5preview';
            }

            $where = ['id' => $id];
            $result = $this->user_model->getRow('credit_notes', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'credit_note'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
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
                return redirect(base_url() . 'credit_note');
            }
        }

        public function print($id=null)
        {
            if (!$id) {
               return redirect(base_url() . 'credit_note');
            }
            $data['page'] = 'inventory_sep/credit_note/print';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('credit_notes', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'CreditNote'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                $dompdf = new Dompdf\Dompdf();
                $dompdf->loadHtml($this->load->view('forprint', $data, true));
                $dompdf->setPaper('A4');
                $dompdf->render();
                $dompdf->output();
                $dompdf->stream();
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note');
            }
        }

        public function getInvoiceBySaleByInvoiceNumber()
        {
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                $this->db->select('*');
                $this->db->where('credit_notes.invoiceNumber', $id);
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('financial_year', $this->session->userdata('financial_year'));
                $this->db->where('status', 1);
                $this->db->order_by('invoiceNumber', 'DESC');
                $result = $this->db->get('credit_notes')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
        }

        public function exportcredit_note()
        {
            $data['page'] = 'inventory_sep/credit_note_report/index';
            $invoiceNumber = '';
            $invoiceDate = '';
            $toDate = '';
            $fromDate = '';
            $partyName = '';
            $productName = '';
            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
            }
            if ($this->input->get('credit_note_register')) {
                $credit_note_register = $this->input->get('credit_note_register');
            }
            if ($this->input->get('invoiceDate')) {
                $invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
            }
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
                $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
            }

            $limit = 10;
            $table = 'credit_notes';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $count =  $this->user_model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $result =  $this->user_model->getTblerDate($table, '', $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $data['result'] = $result;
            $data['count'] = $count;
            $data['credit_note_register'] = $credit_note_register;

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

            print_r($this->load->view('exportexcelforcredit_note', $data, true));
        }

        public function pdfcredit_note()
        {
            $data['page'] = 'inventory_sep/credit_note_report/print';
            $invoiceNumber = '';
            $invoiceDate = '';
            $toDate = '';
            $fromDate = '';
            $partyName = '';
            $productName = '';
            $credit_note_register = '';
            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
            }
            if ($this->input->get('credit_note_register')) {
                $credit_note_register = $this->input->get('credit_note_register');
            }
            if ($this->input->get('invoiceDate')) {
                $invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
            }
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
                $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
            }
            
            $data['credit_note_register'] = $credit_note_register;
            $table = 'credit_notes';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $result =  $this->user_model->getTblerDate($table, '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $data['result'] = $result;
            $data['page'] = 'inventory_sep/credit_note_report/print';
            return $this->load->view('inventory', $data);
        }

        // Recycle Bin
        public function delete($id = null)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['status'] = 0;
            $return = $this->user_model->updateAry('credit_notes', $data, $where);
            
            //payment delete
            $SaleInvoiceId = get_title('credit_notes', ['id' => $id], 'SaleInvoiceId');
            $this->user_model->deleteRow('payment', ['invoice_id' => $SaleInvoiceId, 'invoice_type' => 'Sale', 'transaction_type' => 'DR']);
            $SaleUpdate = ['paid_amount' => (get_title('sales', ['id' => $SaleInvoiceId], 'paid_amount') - get_title('credit_notes', ['id' => $id], 'total'))];
            $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $SaleInvoiceId]);
            //payment delete end
            
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "CreditNote"];
                $res = $this->user_model->getAry('credit_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('credit_product', $statusChange, $where);
                    }
                }
                $this->session->set_flashdata(['msg' => 'Credit Note Deleted', 'class' => 'success']);
                return redirect(base_url() . 'credit_note/report');
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note/report');
            }
        }
        
        // Recycle Bin form edit screen
        public function CreditNoteDelete($id = null)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['status'] = 0;
            $return = $this->user_model->updateAry('credit_notes', $data, $where);
            
            //payment delete
            $SaleInvoiceId = get_title('credit_notes', ['id' => $id], 'SaleInvoiceId');
            $paymentModeId = get_title('payment_mode', ['payment_method' => 'Credit Note'], 'id');
            $remark = "Credit Note Number : " .  get_title('credit_notes', ['id' => $id], 'invoiceNumber');
            $paymentWhere = ['invoice_id' => $SaleInvoiceId, 'invoice_type' => 'Sale', 'paymentmode' => $paymentModeId, 'transaction_type' => 'DR', 'remark' => $remark, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $paymentWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $this->user_model->updateAry('payment', $data, $paymentWhere);
            $SaleUpdate = ['paid_amount' => (get_title('sales', ['id' => $SaleInvoiceId], 'paid_amount') - get_title('credit_notes', ['id' => $id], 'total'))];
            $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $SaleInvoiceId]);
            //payment delete end
            
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "CreditNote"];
                $res = $this->user_model->getAry('credit_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('credit_product', $statusChange, $where);
                    }
                }
                echo json_encode(['status' => true, 'msg' => 'Credit Note deleted successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        // Recycle Bin
        public function permanent_delete($id = null)
        {
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note/report');
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['status'] = 0;
            $return = $this->user_model->updateAry('credit_notes', $data, $where);
            
            //payment delete
            $SaleInvoiceId = get_title('credit_notes', ['id' => $id], 'SaleInvoiceId');
            $this->user_model->deleteRow('payment', ['invoice_id' => $SaleInvoiceId, 'invoice_type' => 'Sale', 'transaction_type' => 'DR']);
            $SaleUpdate = ['paid_amount' => (get_title('sales', ['id' => $SaleInvoiceId], 'paid_amount') - get_title('credit_notes', ['id' => $id], 'total'))];
            $this->user_model->updateAry('sales', $SaleUpdate, ['id' => $SaleInvoiceId]);
            //payment delete end
            
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "CreditNote"];
                $res = $this->user_model->getAry('credit_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('credit_product', $statusChange, $where);
                    }
                }
                $this->session->set_flashdata(['msg' => 'Credit Note Deleted', 'class' => 'success']);
                return redirect(base_url() . 'credit_note/report');
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note/report');
            }
        }

        // Actual Delete
        public function credit_note_deletes($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $return = $this->user_model->deleteRow('credit_notes', $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "CreditNote"];
                $res = $this->user_model->getAry('credit_product', '', '', '', $where);
                if ($res) {
                    foreach ($res as $da) {
                        $this->user_model->deleteRow('credit_product', $where);
                    }
                }
                if (isset($res) && !empty($res)) {
                    echo json_encode(['status' => true, 'msg' => 'credit_note deleted successfully.']);
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

        public function pdfcredit_notes()
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
            if ($this->input->get('partyName')) {
                $partyName = ['customerId' => $this->input->get('name')];
            }
            if ($this->input->get('productName')) {
                $productName = ['product_ids' => $this->input->get('productName')];
            }
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $join = ['table' => 'credit_product', 'condition' => 'credit_notes.id = credit_product.invoiceID AND credit_product.type = "CreditNote"'];
            $result =  $this->user_model->getResult('credit_notes', '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $join);
            $data['result'] = $result;
            $html = $this->load->view('inventory', $data, true);
            $pdfFilePath = "pdf/credit_notes-" . time() . ".pdf";
            $pdf = $this->m_pdf->load();
            $pdf->AddPage('L');
            $pdf->WriteHTML($html, 2);
            $pdf->Output($pdfFilePath, "D");
        }

        public function report($start = 0)
        {
            $data['page'] = 'credit_note/report';
            $invoiceNumber = '';
            $invoiceDate = '';
            $fromDate = date('Y-m-d');
            $toDate = date('Y-m-d');
            $partyName = '';
            $productName = '';
            $first_date = '';
            $last_date = '';
            $limit = 20;
            $fromDate = ['invoiceDate >=' => $fromDate];
            $toDate = ['invoiceDate <=' => $toDate];
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
            }
            $table = 'credit_notes';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $financial_year = $this->session->userdata('financial_year');

            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $financial_year];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $result =  $this->user_model->getTblerDate($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $data['start_date'] = $fromDate;
            $data['end_date'] = $toDate;
            $data['fromDate'] = $first_date;
            $data['toDate'] = $last_date;
            $data['result'] = $result;
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function unique_credit_note()
        {
            $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
            $user_id = $this->session->userdata('user_id');
            $comp_id = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');

            $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'invoiceNumber' => $invoiceNumber, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $CompanyBranchId;
            }
            $return_value = $this->user_model->getRow('credit_notes', $where);
            if ($return_value) {
                return false;
            }
            return true;
        }

        public function getInvoiceBySale()
        {
            $this->db->select('*');
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $this->db->where('comp_id', $this->session->userdata('comp_id'));
            if ($this->session->userdata('BranchRole')) {
                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
            }
            $this->db->where('financial_year', $this->session->userdata('financial_year'));
            $this->db->where('status', 1);
            $this->db->order_by("invoiceNumber", "desc");
            $result = $this->db->get('credit_notes')->row();
            if ($result) {
                echo json_encode(['status' => true, 'data' => $result]);
            } else {
                echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                return false;
            }
        }

        public function getInvoiceBySaleForNext()
        {
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                $this->db->select('*');
                $this->db->where('credit_notes.invoiceNumber > "' . $id . '"');
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('financial_year', $this->session->userdata('financial_year'));
                $this->db->where('status', 1);
                $this->db->order_by("invoiceNumber", "asc");
                $result = $this->db->get('credit_notes')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
        }

        public function getDataByInvoiceNumber()
        {
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $where = ['status' => 1, 'InvoiceNumber' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $party = $this->user_model->getRow('credit_notes', $where);
                if (isset($party) && !empty($party)) {
                    $invoice_id = $party->id;
                    $invoice_product = $this->user_model->getAry('credit_product', '', '', '', ['InvoiceID' => $invoice_id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'type' => "CreditNote"]);
                    $data['invoice_product'] = $invoice_product;
                    $data['party'] = $party;
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
        }
        
        public function Create($Id)
        {
            if (empty($Id)) {
                $this->session->set_flashdata(['msg' => 'Sale invoice id is required.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note');
            }
            $where = ['id' => $Id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $result = $this->user_model->getRow('sales', $where);
            if (isset($result) && !empty($result)) {
                $data['SaleInvoiceId'] = $result->id;
                $data['SaleInvoiceNumber'] = $result->invoiceNumber;
                $data['SaleInvoiceDate'] = $result->invoiceDate;
                $data['page'] = 'credit_note/Create';
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No sale record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'credit_note');
            }       
        }
        
        public function GetSaleInvoiceInfo()
        {
            $InvoiceNumber = $this->input->post('InvoiceNumber');
            if (!isset($InvoiceNumber) && empty($InvoiceNumber)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $this->db->select('*');
                $this->db->where('invoiceNumber', $InvoiceNumber);
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                $this->db->where('status', 1);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $Sale = $this->db->get('sales')->row();
                
                if (isset($Sale) && !empty($Sale)) {
                    $this->db->select('*');
                    $this->db->where('invoiceID', $Sale->id);
                    $this->db->where('comp_id', $this->session->userdata('comp_id'));
                    $this->db->where('status', 1);
                    $this->db->where('type', 'Sale');
                    $this->db->where('user_id', $this->session->userdata('user_id'));
                    if ($this->session->userdata('BranchRole')) {
                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                    }
                    $invoice_product = $this->db->get('invoice_product')->result();
                    $data['invoice_product'] = $invoice_product;
                    $data['InvoiceInfo'] = $Sale;
                    $data['PartyInfo'] = $this->db->where('id', $Sale->customerId)->get('parties')->row();
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
        }
        
        public function GetInvoiceInfo()
        {
            $InvoiceNumber = $this->input->post('InvoiceNumber');
            if (!isset($InvoiceNumber) && empty($InvoiceNumber)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $this->db->select('*');
                $this->db->where('invoiceNumber', $InvoiceNumber);
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                $this->db->where('status', 1);
                $this->db->where('user_id', $this->session->userdata('user_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $CreditNote = $this->db->get('credit_notes')->row();
                
                if (isset($CreditNote) && !empty($CreditNote)) {
                    $this->db->select('*');
                    $this->db->where('invoiceID', $CreditNote->id);
                    $this->db->where('comp_id', $this->session->userdata('comp_id'));
                    $this->db->where('status', 1);
                    $this->db->where('type', 'CreditNote');
                    $this->db->where('user_id', $this->session->userdata('user_id'));
                    if ($this->session->userdata('BranchRole')) {
                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                    }
                    $invoice_product = $this->db->get('credit_product')->result();                    
                    $data['invoice_product'] = $invoice_product;
                    $data['InvoiceInfo'] = $CreditNote;
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
        }
        
        public function GetSaleInvoiceId()
        {
            if ($_POST) {
                $SaleInvoiceNumber = $this->input->post('SaleInvoiceNumber');
                if (!empty($SaleInvoiceNumber) && $SaleInvoiceNumber != "") {
                    $Where = ['invoiceNumber' => $SaleInvoiceNumber, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $SaleInvoiceId = $this->db->select('id')->where($Where)->get('sales')->row('id');
                    if (!empty($SaleInvoiceId)) {
                        echo json_encode(['Status' => true, 'SaleInvoiceId' => $SaleInvoiceId]);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'SaleInvoiceId' => ""]);
                        return;
                    }
                } else {
                    echo json_encode(['Status' => false, 'SaleInvoiceId' => ""]);
                    return;
                }
            }
        }
    }