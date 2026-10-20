<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Debit_note extends CI_Controller
    {
        public function __construct()
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
            $data['page'] = 'debit_note/index';
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function update()
        {	
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }
            $id = $this->input->post('printdebit_note');
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
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
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
            $data['comp_id'] = $CompId;
            $data['CompanyBranchId'] = $CompanyBranchId;
            $data['user_id'] = $UserId;
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
		
            $where = ['id' => $id];
            $there = ['invoiceID' => $id];
            $PreviewsTotal = get_title('debit_notes', ['id' => $id], 'total');
            $result = $this->user_model->updateAry('debit_notes', $data, $where);
            $invoice_pro = $this->user_model->getAry('credit_product', '', '', '', $there);
            $debit_note_condition = ['invoiceID' => $id, 'type' => 'DebitNote'];
            if ($invoice_pro) {
                foreach ($invoice_pro as $invoice) {
                    $this->user_model->deleteRow('credit_product', $debit_note_condition);
                }
            }
            $resultProduct = $this->user_model->bulkdebiteNote('credit_product', $array_of_products, $id, $data['invoiceNumber'], $data['invoiceDate']);
            if (isset($result) && !empty($result) && !empty($resultProduct)) {
                // payment entry
                $SaleId = get_title('credit_notes', ['id' => $this->input->post('CreditNoteId')], 'SaleInvoiceId');
                $PurchaseId = get_title('purchases', ['SaleId' => $SaleId, 'status' => 1], 'id');
                $PaymentQuery = "SELECT id FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$PurchaseId\"]') AND transaction_type = 'CR' AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Purchase\"]')";
                $Query = $this->db->query($PaymentQuery);
                if ($Query->num_rows() > 0) {
                    $PaymentCredit = [
                        'party_id' => $this->input->post('customerId'),
                        'receivedPaidAmount' => $this->input->post('total'),
                        'credit' => $this->input->post('total'),
                        'updated' => date('d-m-Y h:i:s A')
                    ];
                    $this->user_model->updateAry('payment', $PaymentCredit, ['id' => $Query->row('id')]);

                    $PaidAmount = ((get_title('purchases', ['id' => $PurchaseId], 'paid_amount') - $PreviewsTotal) + $this->input->post('total'));
                    $PurchaseUpdate = [
                        'paid_amount' => $PaidAmount,
                        'updated' => date('d-m-Y h:i:s A'),
                        'updated_by' => get_title('users', ['id' => $UserId], 'name')
                    ];
                    $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $PurchaseId]);
                }
                // payment entry end
                echo json_encode(['status' => true, 'data' => $id, 'msg' => 'Invoiced successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }

        public function save()
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

            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required|trim');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Invoice Number Can not be Empty.']);
                return;
            }
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'trim|required|callback_unique_debit_note');
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

            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $InvoiceNumber = $this->input->post('invoiceNumber');

            $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
            }

            $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
            
            $data['invoiceNumber'] = ucwords($InvoiceNumber);
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
            $data['CompanyBranchId'] = $CompanyBranchId;
            $data['user_id'] = $UserId;
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
            );
            $data['parties'] = json_encode($parties);

            $productids = [];
            foreach ($array_of_products as $row) {
                $productids[] = $row['product'];
            }

            $data['product_ids'] = implode(',', $productids);
            $result = $this->user_model->saveAry('debit_notes', $data);
            $this->user_model->bulkdebiteNote('credit_product', $array_of_products, $result, $data['invoiceNumber'], $data['invoiceDate']);
            if (isset($result) && !empty($result)) {                
                if (!empty($this->input->post('CreditNoteInvoiceNumber')) && !empty($this->input->post('CreditNoteId'))) {
                    // Payment Entry
                    $PaymentSeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                    $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
                    if ($this->session->userdata('BranchRole')) {
                        $PaymentSeriesWhere['CompanyBranchId'] = $CompanyBranchId;
                        $UnorderNumberWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($PaymentSeriesWhere)->get('seriestitle')->row();
                    $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->where('status', 1)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                    $SaleId = get_title('credit_notes', ['id' => $this->input->post('CreditNoteId')], 'SaleInvoiceId');
                    $PurchaseId = get_title('purchases', ['SaleId' => $SaleId, 'status' => 1], 'id');

                    $PaymentCredit = [
                        'voucher_type' => 'Tax',
                        'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                        'date' => date('Y-m-d'),
                        'account_group' => get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id'),
                        'party_id' => $this->input->post('customerId'),
                        'invoice_id' => json_encode(array($PurchaseId)),
                        'invoice_type' => json_encode(array('Purchase')),
                        'receivedPaidAmount' => $this->input->post('total'),
                        'credit' => $this->input->post('total'),
                        'transaction_type' => 'CR',
                        'payment_type' => 'Payment',
                        'paymentmode' => get_title('payment_mode', ['payment_method' => 'Debit Note'], 'id'),
                        'user_id' => $UserId,
                        'comp_id' => $CompId,
                        'AmountToPaidInInvoice' => json_encode(array($this->input->post('total'))),
                        'CompanyBranchId' => $CompanyBranchId,
                        'financial_year' => $FinancialYear,
                        'remark' => "Debit Note Number : " . $InvoiceNumber,
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
                    // payment entry end 

                    $CreditNoteId = $this->input->post('CreditNoteId');
                    $this->user_model->updateAry('credit_notes', ['DebitNoteRequest' => 2], ['id' => $CreditNoteId]);
                    $this->user_model->updateAry('debit_notes', ['CreditNoteId' => $CreditNoteId], ['id' => $result]);
                    $PurchaseUpdate = [
                        'paid_amount' => (get_title('purchases', ['id' => $PurchaseId], 'paid_amount') + $this->input->post('total')),
                        'updated' => date('d-m-Y h:i:s A'),
                        'updated_by' => get_title('users', ['id' => $UserId], 'name')
                    ];
                    $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $PurchaseId]);
                }
                
                $SeriesWhere = ['series_id' => $this->input->post('series_id'), 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SeriesWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                
                $DebitNoteSeries = $this->user_model->getRow('series', $SeriesWhere);
                if ($DebitNoteSeries) {
                    if ($DebitNoteSeries->starter_point == 19) {
                        $seriesNumber = 110;
                    } elseif ($DebitNoteSeries->starter_point == 199) {
                        $seriesNumber = 1100;
                    } elseif ($DebitNoteSeries->starter_point == 1999) {
                        $seriesNumber = 11000;
                    } elseif ($DebitNoteSeries->starter_point == 19999) {
                        $seriesNumber = 110000;
                    } elseif ($DebitNoteSeries->starter_point == 199999) {
                        $seriesNumber = 1100000;
                    } else {
                        $seriesNumber = $DebitNoteSeries->starter_point + 1;
                    }
                    $seriesNumberData['starter_point'] = $seriesNumber;
                    $this->user_model->updateAry('series', $seriesNumberData, $SeriesWhere);
                } else {
                    $only_number_invoice['unorder_invoice_number'] = $InvoiceNumber;
                    $this->user_model->updateAry('debit_notes', $only_number_invoice, ['id' => $result]);
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

            $data['page'] = 'debit_note/edit';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('debit_notes', $where);
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Debit_note');
            }
        }

        public function view($id = null)
        {
            if (!$id) {
                return redirect(base_url() . 'Debit_note');
            }
            $data['page'] = 'debit_note/view';
            $where = ['id' => $id];
            $result = $this->user_model->getRow('debit_notes', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'DebitNote'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Debit_note');
            }
        }
	
        public function printDebiteNote($id = null)
        {
            if (!$id) {
                return redirect('debit_note');
            }
            $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
            $where = ['id' => $id];
            $result = $this->user_model->getRow('debit_notes', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'DebitNote'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
                foreach ($products as $row) {
                    $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                    $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data['result'] = $result;
                
                if ($fromet == '4inch') {
                    return $this->load->view('inventory_sep/debit_note/print4inch', $data);
                } else {
                    return $this->load->view('inventory_sep/debit_note/print', $data);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'debit_note');
            }
        }

        public function printdebit_notePreview($id = null)
        {
                if (!$id) {
                        return redirect('debit_note');
                }
                $fromet =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');
                if ($fromet == '4inch') {
                        $data['page'] = 'inventory_sep/debit_note/4inpreview';
                } else if ($fromet == 'A5') {
                        $data['page'] = 'inventory_sep/debit_note/a5preview';
                } else {
                        // $data['page'] = 'inventory_sep/debit_note/a4preview';
                        $data['page'] = 'inventory_sep/debit_note/a5preview';
                }

                $where = ['id' => $id];
                $result = $this->user_model->getRow('debit_notes', $where);

                // echo '<pre>';
                // print_r($result);
                // die;
                $pwhare = ['invoiceID' => $id, 'type' => 'DebitNote'];
                if (isset($result) && !empty($result)) {
                        $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
                        foreach ($products as $row) {
                                $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                                $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                                $row->serials = $serials;
                        }
                        $result->product = $products;
                        $data['result'] = $result;
                        // $data['pages'] = 2;
                        return $this->load->view('inventory', $data);
                } else {
                        $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                        return redirect('debit_note');
                }
        }

        public function print($id=null)
        {




                if (!$id) {
                        return redirect('debit_note');
                }
                $data['page'] = 'inventory_sep/debit_note/print';
                $where = ['id' => $id];
                $result = $this->user_model->getRow('debit_notes', $where);
                $pwhare = ['invoiceID' => $id, 'type' => 'DebitNote'];
                if (isset($result) && !empty($result)) {
                        $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
                        foreach ($products as $row) {
                                $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                                $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                                $row->serials = $serials;
                        }
                        $result->product = $products;
                        $data['result'] = $result;
                        // $this->load->library('pdf');
                        $dompdf = new Dompdf\Dompdf();
                        $dompdf->loadHtml($this->load->view('forprint', $data, true));
                        // (Optional) Setup the paper size and orientation
                        $dompdf->setPaper('A4');

                        // Render the HTML as PDF
                        $dompdf->render();

                        // Get the generated PDF file contents
                        $pdf = $dompdf->output();
                        // $dompdf->stream("codexworld", array("Attachment" => 0));
                        // Output the generated PDF to Browser
                        $dompdf->stream();
                        // die;
                } else {
                        $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                        return redirect('debit_notes');
                }
        }

        public function exportdebit_note()
        {
                $data['page'] = 'inventory_sep/debit_note_report/index';
                $invoiceNumber = '';
                $invoiceDate = '';
                $toDate = '';
                $fromDate = '';
                $partyName = '';
                $productName = '';
                if ($this->input->get('invoiceNumber')) {
                        $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
                }
                if ($this->input->get('debit_note_register')) {
                        $debit_note_register = $this->input->get('debit_note_register');
                }

                if ($this->input->get('invoiceDate')) {
                        $invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
                }
                if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                        $fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
                        $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
                }

                $limit = 10;
                $table = 'debit_notes';
                $order = ['col' => 'added_at', 'order' => 'DESC'];
                $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')){
                        $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                } 
                $count =  $this->user_model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
                $result =  $this->user_model->getTblerDate($table, '', $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
                $data['result'] = $result;
                $data['count'] = $count;
                $data['debit_note_register'] = $debit_note_register;


                $fileName = 'debit_notes-' . time() . '.xls';
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
                $returndebit_note = 0;
                $debit_noteproduct = 0;
                $returnpurchase = 0;
                $purchasepruduct = 0;

                $objPHPExcel = $this->load->view('exportexcelfordebit_note', $data, true);
                print_r($objPHPExcel);
        }

        public function pdfdebit_note()
        {
                $data['page'] = 'inventory_sep/debit_note_report/print';
                $invoiceNumber = '';
                $invoiceDate = '';
                $toDate = '';
                $fromDate = '';
                $partyName = '';
                $productName = '';
                $debit_note_register = '';
                if ($this->input->get('invoiceNumber')) {
                        $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
                }
                if ($this->input->get('debit_note_register')) {
                        $debit_note_register = $this->input->get('debit_note_register');
                }

                if ($this->input->get('invoiceDate')) {
                        $invoiceDate = ['invoiceDate' => $this->input->get('invoiceDate')];
                }
                if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                        $fromDate = ['invoiceDate >=' => $this->input->get('fromDate')];
                        $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
                }
                // if (!empty($debit_note_register)) {
                $data['debit_note_register'] = $debit_note_register;
                $table = 'debit_notes';
                $order = ['col' => 'added_at', 'order' => 'DESC'];
                $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')){
                        $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                } 
                // $count =  $this->user_model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
                $result =  $this->user_model->getTblerDate($table, '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
                $data['result'] = $result;


                $data['page'] = 'inventory_sep/debit_note_report/print';
                $this->load->view('inventory', $data);
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
            $return = $this->user_model->updateAry('debit_notes', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "DebitNote"];
                $res = $this->user_model->getAry('credit_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('credit_product', $statusChange, $where);
                    }
                }

                //payment delete
                $SaleId = get_title('credit_notes', ['id' => get_title('debit_notes', ['id' => $id], 'CreditNoteId')], 'SaleInvoiceId');
                $PurchaseId = get_title('purchases', ['SaleId' => $SaleId, 'status' => 1], 'id');
                $PaymentQuery = "SELECT id FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$PurchaseId\"]') AND transaction_type = 'CR' AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Purchase\"]')";
                $Query = $this->db->query($PaymentQuery);
                $this->user_model->deleteRow('payment', ['id' => $Query->row('id'), 'payment_type' => 'Payment', 'transaction_type' => 'CR']);
                $PurchaseUpdate = ['paid_amount' => (get_title('purchases', ['id' => $PurchaseId], 'paid_amount') - get_title('debit_notes', ['id' => $id], 'total'))];
                $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $PurchaseId]);
                //payment delete end

                $this->session->set_flashdata(['msg' => 'Debit Note Deleted', 'class' => 'success']);
                return redirect(base_url() . 'Debit_note/report');
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Debit_note/report');
            }
        }

        // Recycle Bin
        public function permanent_delete($id = null)
        {
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('debit_note/report');
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            } 
            $data['status'] = 0;
            $return = $this->user_model->updateAry('debit_notes', $data, $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "DebitNote"];
                $res = $this->user_model->getAry('credit_product', '', '', '', $where);
                if ($res) {
                    $statusChange['status'] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry('credit_product', $statusChange, $where);
                    }
                }
                
                //payment delete
                $SaleId = get_title('credit_notes', ['id' => get_title('debit_notes', ['id' => $id], 'CreditNoteId')], 'SaleInvoiceId');
                $PurchaseId = get_title('purchases', ['SaleId' => $SaleId, 'status' => 1], 'id');
                $PaymentQuery = "SELECT id FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$PurchaseId\"]') AND transaction_type = 'CR' AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Purchase\"]')";
                $Query = $this->db->query($PaymentQuery);
                $this->user_model->deleteRow('payment', ['id' => $Query->row('id'), 'payment_type' => 'Payment', 'transaction_type' => 'CR']);
                $PurchaseUpdate = ['paid_amount' => (get_title('purchases', ['id' => $PurchaseId], 'paid_amount') - get_title('debit_notes', ['id' => $id], 'total'))];
                $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $PurchaseId]);
                //payment delete end
                
                $this->session->set_flashdata(['msg' => 'Debit Note Deleted', 'class' => 'success']);
                return redirect('debit_note/report');
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect('debit_note/report');
            }
        }

        // Actual Delete
        public function debit_note_deletes($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            } 
            $return = $this->user_model->deleteRow('debit_notes', $where);
            if ($return) {
                $where = ['invoiceID' => $id, 'type' => "DebitNote"];
                $res = $this->user_model->getAry('credit_product', '', '', '', $where);
                if ($res) {
                    foreach ($res as $da) {
                        $this->user_model->deleteRow('credit_product', $where);
                    }
                }

                //payment delete
                $SaleId = get_title('credit_notes', ['id' => get_title('debit_notes', ['id' => $id], 'CreditNoteId')], 'SaleInvoiceId');
                $PurchaseId = get_title('purchases', ['SaleId' => $SaleId, 'status' => 1], 'id');
                $PaymentQuery = "SELECT id FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$PurchaseId\"]') AND transaction_type = 'CR' AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Purchase\"]')";
                $Query = $this->db->query($PaymentQuery);
                $this->user_model->deleteRow('payment', ['id' => $Query->row('id'), 'payment_type' => 'Payment', 'transaction_type' => 'CR']);
                $PurchaseUpdate = ['paid_amount' => (get_title('purchases', ['id' => $PurchaseId], 'paid_amount') - get_title('debit_notes', ['id' => $id], 'total'))];
                $this->user_model->updateAry('purchases', $PurchaseUpdate, ['id' => $PurchaseId]);
                //payment delete end
                
                if (isset($res) && !empty($res)) {
                    echo json_encode(['status' => true, 'msg' => 'debit_note deleted successfully.']);
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

        public function pdfdebit_notes()
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
                $join = ['table' => 'credit_product', 'condition' => 'debit_notes.id = credit_product.invoiceID AND credit_product.type = "DebitNote"'];
                $result =  $this->user_model->getResult('debit_notes', '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $join);
                $data['result'] = $result;
                $html = $this->load->view('inventory', $data, true);
                $pdfFilePath = "pdf/debit_notes-" . time() . ".pdf";
                $pdf = $this->m_pdf->load();
                $pdf->AddPage('L');
                $pdf->WriteHTML($html, 2);
                $pdf->Output($pdfFilePath, "D");
        }

        public function report($start = 0)
        {
            $data['page'] = 'debit_note/report';
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

            
            $data['start_date'] = $fromDate;
            $data['end_date'] = $toDate;

            $table = 'debit_notes';
            $order = ['col' => 'added_at', 'order' => 'DESC'];
            $financial_year = $this->session->userdata('financial_year');

            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $financial_year];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
            } 

            $count =  $this->user_model->count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);
            $result =  $this->user_model->getTblerDate($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where);

            $config['base_url'] = base_url('Debit_note/report');
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

            $config['attributes'] = array('class' => 'page-link');
            $this->pagination->initialize($config);
            $data['fromDate'] = $first_date;
            $data['toDate'] = $last_date;

            $data['result'] = $result;
            $data['start'] = $start;
            $data['end'] = (int)$start + count($result);
            $data['rowCount'] = $count;
            $data['totalCount'] = $this->user_model->RowCount($table);
            $this->load->view('inventory_sep/inventory', $data);
        }

        public function unique_debit_note()
        {
                $invoiceNumber = ucwords($this->input->post('invoiceNumber'));
                $user_id = $this->session->userdata('user_id');
                $comp_id = $this->session->userdata('comp_id');
                $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'invoiceNumber' => $invoiceNumber, 'status' => 1];
                if ($this->session->userdata('BranchRole')){
                        $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                } 
                $return_value = $this->user_model->getRow('debit_notes', $where);
                // print_r($return_value);
                if ($return_value) {
                        return false;
                }
                return true;
        }

        public function getInvoiceByPurchase()
        {

                $this->db->select('*');
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')){
                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId')); 
                }
                $this->db->where('financial_year', $this->session->userdata('financial_year'));
                $this->db->where('status', 1);
                $this->db->order_by("invoiceNumber", "desc");
                $result = $this->db->get('debit_notes')->row();
                if ($result) {
                        echo json_encode(['status' => true, 'data' => $result]);
                } else {
                        echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                        return false;
                }
        }

        public function getInvoiceByPurchaseForNext()
        {
                $id = $this->input->post('id');
                if ($id == null || $id == '') {
                        echo json_encode(['status' => false, 'data' => "Not not Data"]);
                } else {
                        $this->db->select('*');
                        // $this->db->where('Purchases.invoiceNumber >= "' . $id . '"');
                        $this->db->where('Purchases.invoiceNumber > "' . $id . '"');
                        $this->db->where('user_id', $this->session->userdata('user_id'));
                        $this->db->where('comp_id', $this->session->userdata('comp_id'));
                        if ($this->session->userdata('BranchRole')){
                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId')); 
                        }
                        $this->db->where('financial_year', $this->session->userdata('financial_year'));
                        // $this->db->where('status', $this->session->userdata(1));
                        $this->db->where('status', 1);
                        $this->db->order_by("invoiceNumber", "asc");
                        $result = $this->db->get('Purchases')->row();
                        // echo '<pre>';
                        // print_r($result);
                        // die;
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
                $Where = ['status' => 1, 'InvoiceNumber' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                } 
                            
                $party = $this->user_model->getRow('debit_notes', $Where);
                if (isset($party) && !empty($party)) {
                    $invoice_id = $party->id;
                    $invoice_where = ['InvoiceID' => $invoice_id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'type' => "DebitNote"];
                    $invoice_product = $this->user_model->getAry('credit_product', '', '', '', $invoice_where);
                    $data['invoice_product'] = $invoice_product;
                    $data['party'] = $party;
                    $data['partyInfo'] = $this->user_model->getRow('parties', ['id' => $party->customerId]);
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
        }
      
        public function CreateInvoice($CreditNoteId) 
        {
            if (!empty($CreditNoteId)) {
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $LoginUserId = $this->session->userdata('user_id');
                $UserId = get_title('companybranch', ['Id' => $CompBranchId, 'CompId' => $CompId, 'IsDeleted' => 1], 'UserId');
                $FinancialYear = $this->session->userdata('financial_year');
               
                $Where = ['id' => $CreditNoteId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $CreditNoteQuery = $this->db->where($Where)->get('credit_notes');
                if ($CreditNoteQuery->num_rows()) {
                    $CompanyName = get_title('inventory_billing', ['id' => $CompId], 'company_name');
                    $AccountGroupId = get_title('accounting_group', ['name' => 'Sundry Creditors'], 'id');
                    $PartyWhere = ['name' => $CompanyName, 'user_id' => $LoginUserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId, 'group_head' => $AccountGroupId];
                    $data['CustomerId'] = $this->db->select('id')->where($PartyWhere)->get('parties')->row('id');
                    $data['CreditNoteInfo'] = $CreditNoteQuery->row();
                    $data['page'] = "debit_note/CreateInvoice";
                    return $this->load->view('inventory_sep/inventory', $data);    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Reports/DebitNoteRequestReport');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Credit Note id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Reports/DebitNoteRequestReport');
            }
        }
        
        public function getCreditNoteProductsForDebitNoteInvoice() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $CreditNoteId = $this->input->post('CreditNoteId');
                if (!empty($CreditNoteId)) {
                    $BranchUserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $FinancialYear = $this->session->userdata('financial_year');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    $UserId = get_title('companybranch', ['Id' => $CompBranchId, 'BranchUserId' => $BranchUserId, 'CompId' => $CompId, 'IsDeleted' => 1], 'UserId');
                    
                    $CreditNoteProductWhere = ['invoiceID' => $CreditNoteId, 'type' => 'CreditNote', 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];
                    $WarehouseWhere = ['user_id' => $BranchUserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $WarehouseWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $CreditNoteProductInfo = $this->db->select('productname, qty, rate')->where($CreditNoteProductWhere)->get('credit_product')->result();
                    $ProductArray = [];
                    foreach ($CreditNoteProductInfo as $key => $Products) {
                        $ProductWhere = ['p_name' => $Products->productname, 'CompanyBranchId' => $CompBranchId, 'user_id' => $BranchUserId, 'comp_id' => $CompId];
                        $ProductInfo = $this->db->where($ProductWhere)->get('product')->row();
                        $ProductArray[] = array(
                            "p_code" => $ProductInfo->qrCode,
                            "productname" => $ProductInfo->p_name,
                            "product_id" => $ProductInfo->id,
                            "product_dec" => $ProductInfo->short_de,
                            "hsn" => $ProductInfo->hsn,
                            "qty" => $Products->qty,
                            "discount" => 0,
                            "rate" => $Products->rate,
                            "tax" => $ProductInfo->tax_qty,
                            "warehouse" => get_title('comp_profile', $WarehouseWhere, 'warehouse')
                        );
                    }

                    if (is_array($ProductArray)) {
                        echo json_encode(['Status' => true, 'ProductDataArray' => $ProductArray]);
                    } else {
                        echo json_encode(['Status' => false, 'ProductDataArray' => 'No record found.']);
                    }
                } else {
                    echo json_encode(['Status' => false, 'ProductDataArray' => 'Sale id missing.']);
                }
            }
        }
        
        public function DebitNoteCancel($CreditNoteId) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!$CreditNoteId) {
                    echo json_encode(['Status' => false, 'Message' => 'Id not found.']);
                    return;
                }
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $FinancialYear = $this->session->userdata('financial_year');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $Data['DebitNoteRequest'] = 0;
                $Where = ['id' => $CreditNoteId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                if ($this->session->userdata('BranchRole')){
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                
                $CreditNoteResult = $this->user_model->updateAry('credit_notes', $Data, $Where);
                if ($CreditNoteResult) {
                    echo json_encode(['Status' => true, 'Message' => 'Debit Note cancelled successfully.']);
                    return;
                } else {
                    echo json_encode(['Status' => false, 'Message' => 'There is some issue, Please try again.']);
                    return;
                }
            }
        }
    }