<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Gadget extends CI_Controller
    {
    	public function __construct()
    	{
            parent::__construct();
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
    	}    
    
        public function index()
    	{
            $data['page'] = 'gadget/index';
            return $this->load->view('inventory_sep/inventory', $data);
    	}
        
        public function uniqueSale()
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
        
        public function saveSale()
        {
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
            
            $productWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $productCodeWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $getPartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $CompProfileWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
            if ($this->session->userdata('BranchRole')) {
                $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
                $getPartyWhere['CompanyBranchId'] = $CompanyBranchId;
                $productWhere['CompanyBranchId'] = $CompanyBranchId;
                $productCodeWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $product = $this->input->post('product');

            $array_of_products = [];
            foreach ($product as $row) {
                $productName = $row['productname'];
                $productWhere['p_name'] = $productName;
                $productQuery = $this->db->select('id')->where($productWhere)->get('product');
                if (!$productQuery->num_rows()) {
                    if (!empty($row['qty']) && !empty($productName) && !empty($row['rate'])) {                    
                        $productCode = $this->db->select('qrCode')->where($productCodeWhere)->order_by('id', 'DESC')->get('product')->row('qrCode');
                        $warehouseId = get_title('comp_profile', $CompProfileWhere, 'default_warehouse');

                        $newProductCreate = [
                            'p_name' => $productName,
                            'long_de' => $row['product_dec'],
                            'price' => $row['rate'],
                            'unit' => get_title('unit', ['name' => 'PCS'], 'id'),
                            'tax' => get_title('tax', ['tax_per' => 0], 'id'),
                            'qrCode' => $productCode ? $productCode + 1 : 1,
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'CompanyBranchId' => $CompanyBranchId,
                            'tax_qty' => 0,
                            'unit_name' => 'PCS',
                            'warehouse' => $warehouseId
                        ];

                        $row['product'] = $this->user_model->saveAry('product', $newProductCreate);
                        $row['p_code'] = get_title('product', ['id' => $row['product']], 'qrCode');
                        $row['warehouse'] = $warehouseId;
                    }
                } else {
                    $row['product'] = $productQuery->row()->id;
                }
                
                if (!empty($row['qty']) && !empty($row['netAmount']) && !empty($row['rate']) && !empty($row['product'])) {
                    $array_of_products[] = $row;
                }
            }
            
            if (empty($array_of_products)) {
                echo json_encode(['status' => false, 'msg' => 'Please enter products required fields.']);
                return;
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
            $resp = $this->uniqueSale();
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
            $this->form_validation->set_rules('customerMobile', 'Mobile', 'trim|required');
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
            
            $getPartyWhere['name'] = $this->input->post('partyname');
            $getPartyWhere['contact'] = $this->input->post('customerMobile');
            $getPartyQuery = $this->db->where($getPartyWhere)->get('parties');
            if ($getPartyQuery->num_rows()) {
                $partyData = $getPartyQuery->row();
                $customerName = $partyData->name;
                $customerId = $partyData->id;
                $customerAddress = $partyData->address_1;
                $customerCityId = $partyData->city_id;
                $customerStateId = $partyData->state_id;
                $customerMobile = $partyData->contact;
                $customerStateName = $partyData->statename;
                $customerCityName = $partyData->cityname;
                $customerGstNo = $partyData->gst_no;
                $customerEmail = $partyData->email;
            } else {
                $customerName = $this->input->post('partyname');
                $customerMobile = $this->input->post('customerMobile');
                $customerEmail = $this->input->post('customerEmail');
                $customerStateName = $this->input->post('statename');
                $customerStateId = $this->input->post('customerState');
                $customerGstNo = strtoupper($this->input->post('gstNo'));
                $customerAddress = $this->input->post('customerAddress');
                $customerCityId = $this->input->post('customerCity');
                $customerCityName = $this->input->post('cityCustomer');
                
                $newPartyCreate = [
                    'group_head' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                    'name' => $customerName,
                    'contact' => $customerMobile,
                    'email' => $customerEmail,
                    'gstType' => 'N/A',
                    'gst' => $customerGstNo,
                    'address_1' => $customerAddress,
                    'city' => $customerCityId,
                    'state' => $customerStateId,
                    'state_code' => get_title('state_list', ['id' => $customerStateId], 'code'),
                    'opBalnceType' => 'DR',
                    'user_id' => $UserId,
                    'comp_id' => $CompId,
                    'CompanyBranchId' => $CompanyBranchId
                ];
                $customerId = $this->user_model->saveAry('parties', $newPartyCreate);
            }
            
            $data['invoiceNumber'] = $invoiceNumber;
            $data['invoiceDate'] = date('Y-m-d', strtotime($this->input->post('invoiceDate')));
            $data['voucherType'] = $this->input->post('voucherType');
            $data['customerId'] = $customerId;
            $data['customerName'] = $customerName;
            $data['gstNo'] = ucfirst($customerGstNo);
            $data['taxCode'] = $this->input->post('gstType');
            $data['customerAddress'] = $customerAddress;
            $data['customerCity'] = $customerCityId;
            $data['state_name'] = $customerStateName;
            $data['city_name'] = $customerCityName;
            $data['customerState'] = $customerStateId;
            $data['customerMobile'] = $customerMobile;
            $data['customerEmail'] = $customerEmail;
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
            $data['shipping_address'] = $this->input->post('shipping_address');
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
                'name' => $customerName,
                'customer_id' => $customerId,
                'address' => $customerAddress,
                'city_id' => $customerCityId,
                'state_id' => $customerStateId,
                'mobile_number' => $customerMobile,
                'statename' => $customerStateName,
                'cityname' => $customerCityName,
                'gst_no' => $customerGstNo,
                'email' => $customerEmail
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
                    'party_id' => $customerId,
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
                        'party_id' => $customerId,
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

        public function print($id = null)
        {
            if (!$id) {
                return redirect(base_url() . 'gadget');
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
                } else {
                    return $this->load->view('inventory_sep/sale/a5print', $data);
                }
            }
        }

        public function report($start = 0)
        {
            $data['page'] = 'gadget/report';
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
            if (!is_array($partyFilter)) {
                $partyFilter = $partyFilter !== null && $partyFilter !== '' ? [$partyFilter] : [];
            }
            if (!empty($partyFilter) && !in_array('All', $partyFilter, true)) {
                $party = $partyFilter;
            }
            $salesmanFilter = $this->input->get('Salesman');
            if (!is_array($salesmanFilter)) {
                $salesmanFilter = $salesmanFilter !== null && $salesmanFilter !== '' ? [$salesmanFilter] : [];
            }
            if (!empty($salesmanFilter) && !in_array('All', $salesmanFilter, true)) {
                $SalesmanId = $salesmanFilter;
            }

            if ($this->input->get('invoiceNumber')) {
                $invoiceNumber = ['invoiceNumber' => $this->input->get('invoiceNumber')];
            }

            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                $first_date = date('Y-m-d', strtotime($this->input->get('fromDate')));
                $last_date = date('Y-m-d', strtotime($this->input->get('toDate')));
            } else {
                $first_date = date('Y-m-01'); // hard-coded '01' for first day
                $last_date  = date('Y-m-d');

                $fromDate = ['invoiceDate >=' => $first_date];
                $toDate = ['invoiceDate <=' => $last_date];                
            }

            $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];
            if ($this->input->get('invoiceStatus') != 'All' && $this->input->get('invoiceStatus') != '') {
                $where['status'] = $this->input->get('invoiceStatus');
            }
            
            if ($this->input->get('payment') != 'All' && $this->input->get('payment') != '') {
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
        
        public function view($id)
        {
            if (!$id) {
                return redirect(base_url() . 'gadget/report');
            }
            $data['page'] = 'sale/viewsale';
            $data['title'] = 'MIS | Sale View';
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
                return redirect(base_url() . 'gadget/report');
            }
        }
        
        public function edit($SaleId)
        {
            if (empty($SaleId)) {
                $this->session->set_flashdata(['msg' => 'Invoice number missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
            
            $result = $this->user_model->getRow('sales', ['id' => $SaleId]);
            
            if ($result->voucherType != 1) {
                if ($result->paid_amount != 0) {
                    $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not change this.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }
            
            if (isset($result) && !empty($result)) {
                $data['invoiceNumber'] = $result->invoiceNumber;
                $data['page'] = 'gadget/edit';
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'gadget/report');
            }
        }        
        
        public function updateSale()
        {
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
            
            $productWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $productCodeWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $getPartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $CompProfileWhere = ['comp_id' => $CompId, 'user_id' => $UserId];
            if ($this->session->userdata('BranchRole')) {
                $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
                $getPartyWhere['CompanyBranchId'] = $CompanyBranchId;
                $productWhere['CompanyBranchId'] = $CompanyBranchId;
                $productCodeWhere['CompanyBranchId'] = $CompanyBranchId;                
            }            
            
            $id = $this->input->post('printSale');
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }

            $product = $this->input->post('product');
            
            $array_of_products = [];
            foreach ($product as $row) {
                $productName = $row['productname'];
                $productWhere['p_name'] = $productName;
                $productQuery = $this->db->select('id')->where($productWhere)->get('product');
                if (!$productQuery->num_rows()) {
                    if (!empty($row['qty']) && !empty($productName) && !empty($row['rate'])) {                    
                        $productCode = $this->db->select('qrCode')->where($productCodeWhere)->order_by('id', 'DESC')->get('product')->row('qrCode');
                        $warehouseId = get_title('comp_profile', $CompProfileWhere, 'default_warehouse');

                        $newProductCreate = [
                            'p_name' => $productName,
                            'long_de' => $row['product_dec'],
                            'price' => $row['rate'],
                            'unit' => get_title('unit', ['name' => 'PCS'], 'id'),
                            'tax' => get_title('tax', ['tax_per' => 0], 'id'),
                            'qrCode' => $productCode ? $productCode + 1 : 1,
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'CompanyBranchId' => $CompanyBranchId,
                            'tax_qty' => 0,
                            'unit_name' => 'PCS',
                            'warehouse' => $warehouseId
                        ];

                        $row['product'] = $this->user_model->saveAry('product', $newProductCreate);
                        $row['p_code'] = get_title('product', ['id' => $row['product']], 'qrCode');
                        $row['warehouse'] = $warehouseId;
                    }
                } else {
                    $row['product'] = $productQuery->row()->id;
                }
                
                if (!empty($row['qty']) && !empty($row['netAmount']) && !empty($row['rate']) && !empty($row['product'])) {
                    $array_of_products[] = $row;
                }
            }

            if (empty($array_of_products)) {
                echo json_encode(['status' => false, 'msg' => 'Please enter products required fields.']);
                return;
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
            
            $this->form_validation->set_rules('invoiceNumber', 'Invoice Number', 'required|trim');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => 'Invoice number is required.']);
                return;
            }

            $this->form_validation->set_rules('invoiceDate', 'Invoice Date', 'trim|required');
            $this->form_validation->set_rules('partyname', 'Party Name', 'trim|required');
            $this->form_validation->set_rules('customerMobile', 'Mobile', 'trim|required');
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
            
            $getPartyWhere['name'] = $this->input->post('partyname');
            $getPartyWhere['contact'] = $this->input->post('customerMobile');
            $getPartyQuery = $this->db->where($getPartyWhere)->get('parties');
            if ($getPartyQuery->num_rows()) {
                $partyData = $getPartyQuery->row();
                $customerName = $partyData->name;
                $customerId = $partyData->id;
                $customerAddress = $partyData->address_1;
                $customerCityId = $partyData->city_id;
                $customerStateId = $partyData->state_id;
                $customerMobile = $partyData->contact;
                $customerStateName = $partyData->statename;
                $customerCityName = $partyData->cityname;
                $customerGstNo = $partyData->gst_no;
                $customerEmail = $partyData->email;
            } else {
                $customerName = $this->input->post('partyname');
                $customerMobile = $this->input->post('customerMobile');
                $customerEmail = $this->input->post('customerEmail');
                $customerStateName = $this->input->post('statename');
                $customerStateId = $this->input->post('customerState');
                $customerGstNo = strtoupper($this->input->post('gstNo'));
                $customerAddress = $this->input->post('customerAddress');
                $customerCityId = $this->input->post('customerCity');
                $customerCityName = $this->input->post('cityCustomer');
                
                $newPartyCreate = [
                    'group_head' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                    'name' => $customerName,
                    'contact' => $customerMobile,
                    'email' => $customerEmail,
                    'gstType' => 'N/A',
                    'gst' => $customerGstNo,
                    'address_1' => $customerAddress,
                    'city' => $customerCityId,
                    'state' => $customerStateId,
                    'state_code' => get_title('state_list', ['id' => $customerStateId], 'code'),
                    'opBalnceType' => 'DR',
                    'user_id' => $UserId,
                    'comp_id' => $CompId,
                    'CompanyBranchId' => $CompanyBranchId
                ];
                $customerId = $this->user_model->saveAry('parties', $newPartyCreate);
            }            
            
            $data['invoiceNumber'] = ucwords($this->input->post('invoiceNumber'));
            $data['invoiceDate'] = date('Y-m-d', strtotime($this->input->post('invoiceDate')));
            $data['voucherType'] = $this->input->post('voucherType');
            $data['customerId'] = $customerId;
            $data['customerName'] = $customerName;
            $data['gstNo'] = ucfirst($customerGstNo);
            $data['taxCode'] = $this->input->post('gstType');
            $data['customerAddress'] = $customerAddress;
            $data['customerCity'] = $customerCityId;
            $data['state_name'] = $customerStateName;
            $data['city_name'] = $customerCityName;
            $data['customerState'] = $customerStateId;
            $data['customerMobile'] = $customerMobile;
            $data['customerEmail'] = $customerEmail;
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
            $data['shipping_address'] = $this->input->post('shipping_hideen_address');
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
                'name' => $customerName,
                'customer_id' => $customerId,
                'address' => $customerAddress,
                'city_id' => $customerCityId,
                'state_id' => $customerStateId,
                'mobile_number' => $customerMobile,
                'statename' => $customerStateName,
                'cityname' => $customerCityName,
                'gst_no' => $customerGstNo,
                'email' => $customerEmail
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
                            'party_id' => $customerId,
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
                            'party_id' => $customerId,
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
                            'financial_year' => get_title('sales', ['id' => $invoiceID], 'financial_year'),
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
                        'party_id' => $customerId,
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
        
        //  Move To Recycle Bin From Voucher Page
        public function saleDelete($id = null)
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
        
        public function cancelReason($invoiceId)
        {
            $data['invoiceId'] = $invoiceId;
            $data['invoiceData'] = $this->db->select('remark, status')->where('id', $invoiceId)->get('sales')->row();
            return $this->load->view('inventory_sep/sale_report/cancelReason', $data);            
        }        
        
        // Move To Recycle Bin
        public function delete($id)
        {
            if (!$id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            
            $this->form_validation->set_rules('remark', 'Reason', 'trim|required');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors()]);
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
            $data['remark'] = $this->input->post('remark');
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
        
        public function download($id)
        {
            ini_set('memory_limit', '-1');
            set_time_limit(5000);
            
            if (!$id) {
                return redirect(base_url() . 'gadget/report');
            }
            $where = ['id' => $id];
            $result = $this->user_model->getRow('sales', $where);
            $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                $result->product = $products;
                $data['result'] = $result;

                $FileName = str_replace(' ', '_', get_title('parties', ['id' => $result->customerId], 'name')) . '_' . date('d-m-Y', strtotime($result->invoiceDate));
                
                $this->load->library('pdf');
                $this->pdf->loadHtml($this->load->view('inventory_sep/sale/a5print', $data, true));
                $this->pdf->set_option('isRemoteEnabled', TRUE);
                $this->pdf->render();
                $this->pdf->output();
                $this->pdf->stream($FileName . ".pdf", array("Attachment" =>true));
            }
        }        
    }