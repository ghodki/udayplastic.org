<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');                    

    require APPPATH . 'third_party/googleapiclient/vendor/autoload.php';

    class Reports extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url']);
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

        public function activeUsers()
        {
            $data['result'] = $this->user_model->getActiveUsers();
            $data['page'] = 'reports/active-users';
            
            $this->load->view('admin', $data);
        }
        
        public function content ($id)
        {
            if (!empty($id)) {
                
                $this->load->view('reports/login-content');
            }
        }

        public function inactiveUsers()
        {
            $data['result'] = $this->user_model->getInActiveUsers();
            $data['page'] = 'reports/inactive-users';
            
            $this->load->view('admin', $data);
        }
        
        public function partiesOutstanding()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $salesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            if ($this->input->get('fromDate') != "" && $this->input->get('toDate') != "") {
                $from = $this->input->get('fromDate');
                $to = $this->input->get('toDate');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $partyWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $salesmanWhere = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1, 'RoleId' => $salesmanRoleId];
            $salesWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $partyWhere['CompanyBranchId'] = $compBranchId;
                $salesmanWhere['CompanyBranchId'] = $compBranchId;
                $salesWhere['CompanyBranchId'] = $compBranchId;
            }
            
            if (!empty($this->input->get('salesmanId')) && $this->input->get('salesmanId') != "All") {
                $salesWhere['SalesmanId'] = $this->input->get('salesmanId');
            }
            if (!empty($this->input->get('partyId')) && $this->input->get('partyId') != "All") {
                $salesWhere['customerId'] = $this->input->get('partyId');
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['parties'] = $this->db->select('id, name, contact')->where($partyWhere)->order_by('name', 'ASC')->get('parties')->result();
            $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->order_by('Name', 'ASC')->get('employees')->result();
            if ($this->input->get('estimate') == "") {
                $data['partiesSales'] = $this->db->select('customerId, invoiceDate, COUNT(customerId) AS saleInvoice, SUM(total) AS saleTotal, SUM(paid_amount) AS salePaidAmount, SalesmanId')->where($salesWhere)->group_by('customerId')->order_by('saleTotal', 'DESC')->get('sales')->result();
            } elseif ($this->input->get('estimate') == true) {
                $saleQuery = $this->db->select('type, customerId, invoiceDate, COUNT(customerId) AS saleInvoice, SUM(total) AS saleTotal, SUM(paid_amount) AS salePaidAmount, SalesmanId')->where($salesWhere)->group_by('customerId')->get_compiled_select('sales');
                $estimateSaleQuery = $this->db->select('type, customerId, invoiceDate, COUNT(customerId) AS saleInvoice, SUM(total) AS saleTotal, SUM(paid_amount) AS salePaidAmount, SalesmanId')->where($salesWhere)->group_by('customerId')->get_compiled_select('estimates');
                $data['partiesSales'] = $this->db->query("($saleQuery) UNION ALL ($estimateSaleQuery) ORDER BY saleTotal DESC")->result();
            }
            $data['page'] = 'reports/partiesOutstanding';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function Popularproducts()
        {
            $party = '';
            $salesman = '';
            $partyFilter = $this->input->get('party');
            if (!is_array($partyFilter)) {
                $partyFilter = $partyFilter !== null && $partyFilter !== '' ? [$partyFilter] : [];
            }
            if (!empty($partyFilter) && !in_array('All', $partyFilter, true)) {
                $party = $partyFilter;
            }
            $salesmanFilter = $this->input->get('salesman');
            if (!is_array($salesmanFilter)) {
                $salesmanFilter = $salesmanFilter !== null && $salesmanFilter !== '' ? [$salesmanFilter] : [];
            }
            if (!empty($salesmanFilter) && !in_array('All', $salesmanFilter, true)) {
                $salesman = $salesmanFilter;
            }
            
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $FromDate = $this->input->get('fromDate');
                $ToDate = $this->input->get('toDate');
            } else {
                $FromDate = date('Y-m-d');
                $ToDate = date('Y-m-d');
            }
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $InvoiceProductWhere = ['invoice_product.invoiceDate >=' => $FromDate, 'invoice_product.invoiceDate <=' => $ToDate, 'invoice_product.user_id' => $UserId, 'invoice_product.comp_id' => $CompId, 'invoice_product.status' => 1, 'invoice_product.type' => 'Sale'];
            $EstimateProductWhere = ['estimate_product.invoiceDate >=' => $FromDate, 'estimate_product.invoiceDate <=' => $ToDate, 'estimate_product.user_id' => $UserId, 'estimate_product.comp_id' => $CompId, 'estimate_product.status' => 1, 'estimate_product.type' => 'Sale'];
            $PartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $InvoiceProductWhere['invoice_product.CompanyBranchId'] = $CompanyBranchId;
                $EstimateProductWhere['estimate_product.CompanyBranchId'] = $CompanyBranchId;
                $PartyWhere['CompanyBranchId'] = $CompanyBranchId;
                $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            if ($this->input->get('estimate') == "") {
                $this->db->select('invoice_product.product_id, invoice_product.productname, SUM(invoice_product.qty) AS SaleQty');
                $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                $this->db->join('sales', 'sales.id = invoice_product.invoiceID', 'LEFT');
                $this->db->where($InvoiceProductWhere);
                if (!empty($party)) {
                    $this->db->where_in('sales.customerId', $party);
                }
                if (!empty($salesman)) {
                    $this->db->where_in('sales.SalesmanId', $salesman);
                }
                $this->db->group_by('invoice_product.product_id');
                $data['SallingProducts'] = $this->db->get('invoice_product')->result();
            } elseif ($this->input->get('estimate') == true) {
                $this->db->select('sales.type, invoice_product.product_id, invoice_product.productname, SUM(invoice_product.qty) AS SaleQty');
                $this->db->from('invoice_product');
                $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                $this->db->join('sales', 'sales.id = invoice_product.invoiceID', 'LEFT');
                $this->db->where($InvoiceProductWhere);
                if (!empty($party)) {
                    $this->db->where_in('sales.customerId', $party);
                }
                if (!empty($salesman)) {
                    $this->db->where_in('sales.SalesmanId', $salesman);
                }
                $this->db->group_by('invoice_product.product_id');
                $Sales = $this->db->get_compiled_select();
                
                // Estimates
                $this->db->select('estimates.type, estimate_product.product_id, estimate_product.productname, SUM(estimate_product.qty) AS SaleQty');
                $this->db->from('estimate_product');
                $this->db->join('product', 'product.id = estimate_product.product_id', 'LEFT');
                $this->db->join('estimates', 'estimates.id = estimate_product.invoiceID', 'LEFT');
                $this->db->where($EstimateProductWhere);
                if (!empty($party)) {
                    $this->db->where_in('estimates.customerId', $party);
                }
                if (!empty($salesman)) {
                    $this->db->where_in('estimates.SalesmanId', $salesman);
                }
                $this->db->group_by('estimate_product.product_id');
                $Estimates = $this->db->get_compiled_select();
                
                $data['SallingProducts'] = $this->db->query($Sales . " UNION " . $Estimates . " ORDER BY SaleQty DESC")->result();
            }
            
            $data['parties'] = $this->db->select('id, name')->where($PartyWhere)->order_by('name', 'ASC')->get('parties')->result();
            $data['salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->get('employees')->result();
            $data['page'] = 'reports/PopullerProducts';
            return $this->load->view('inventory_sep/inventory', $data);
        }
                
        public function SaleDeliveryReport()
        {
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $FromDate = $this->input->get('fromDate');
                $ToDate = $this->input->get('toDate');
            } else {
                $FromDate = date('Y-m-d');
                $ToDate = date('Y-m-d');
            }
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            $SalesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            
            $PartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
            $Where = ['invoiceDate >= ' => $FromDate, 'invoiceDate <= ' => $ToDate, 'financial_year' => $FinancialYear, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $PartyWhere['CompanyBranchId'] = $CompanyBranchId;
                $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $PartyId = $this->input->get('PartyId');
            $SalesmanId = $this->input->get('SalesmanId');
            $Delivery = $this->input->get('Delivery');
            $Payment = $this->input->get('Payment');
            
            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, total, paid_amount, type, delivery_status, delivery_date, SalesmanId');
            $this->db->where($Where);
            
            if ($PartyId) {
                if ($PartyId != 'All') {
                    $this->db->where('customerId', $PartyId);
                }
            }
            if ($SalesmanId) {
                if ($SalesmanId != 'All') {
                    $this->db->where_in('SalesmanId', $SalesmanId);
                }
            }
            if ($Delivery != "") {
                if ($Delivery != 'All') {
                    $this->db->where('delivery_status', $Delivery);
                }
            }
            if ($Payment == 'Paid') {
                $this->db->where('paid_amount', 'total', false);
            } elseif ($Payment == 'Unpaid') {
                $this->db->group_start();
                $this->db->where('paid_amount', NULL);
                $this->db->or_where('paid_amount', 0);
                $this->db->group_end();
            } elseif ($Payment == 'Partial') {
                $this->db->where('paid_amount != ', 'total', false);
            }
            $this->db->order_by('invoiceDate', 'DESC');
            $data['SaleResult'] = $this->db->get('sales')->result();
            $data['Parties'] = $this->db->select('id, name')->where($PartyWhere)->order_by('name', 'ASC')->get('parties')->result();
            $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Id', 'DESC')->get('employees')->result();
            $data['page'] = 'reports/SaleDeliveryReport';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function DeliveryInfo($Id) 
        {
            if (!empty($Id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                $SalesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                
                $Where = ['id' => $Id, 'financial_year' => $FinancialYear, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                    $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                
                if ($this->db->select('id')->where($Where)->where('type', 'Sale')->get('sales')->num_rows()) {
                    $Data['DeliveryInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerName, total, type, SalesmanId, delivery_date, delivery_status, DeliveredBy')->where($Where)->where('type', 'Sale')->get('sales')->row();
                } elseif ($this->db->select('id')->where($Where)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                    $Data['DeliveryInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerName, total, type, SalesmanId, delivery_date, delivery_status, DeliveredBy')->where($Where)->where('type', 'Estimate')->get('estimates')->row();
                }
                $Data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Id', 'DESC')->get('employees')->result();
                return $this->load->view('inventory_sep/reports/DeliveryInfoData', $Data);
            }
        }
        
        public function UpdateDelivery()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $InvoiceData = [
                    'delivery_date' => $this->input->post('DeliveryDate'),
                    'DeliveredBy' => $this->input->post('DeliveredBy'),
                    'delivery_status' => $this->input->post('DeliveryStatus')
                ];

                $Where = ['id' => $this->input->post('InvoiceId'), 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->input->post('InvoiceType') == "Sale") {
                    $Where['type'] = "Sale";
                    $InvoiceUpdate = $this->user_model->updateAry('sales', $InvoiceData, $Where);
                } elseif ($this->input->post('InvoiceType') == "Estimate") {
                    $Where['type'] = "Estimate";
                    $InvoiceUpdate = $this->user_model->updateAry('estimates', $InvoiceData, $Where);
                }
                
                if ($InvoiceUpdate) {
                    $this->session->set_flashdata(['msg' => 'Invoice data updated successfully', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to update data please try again', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
        
        public function DayBookReport()
        {
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $FromDate = $this->input->get('fromDate');
                $ToDate = $this->input->get('toDate');
            } else {
                $FromDate = date('Y-m-d');
                $ToDate = date('Y-m-d');
            }

            $UserId = $this->session->userdata('user_id'); 
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $SaleWhere = ['sales.invoiceDate >= ' => $FromDate, 'sales.invoiceDate <= ' => $ToDate, 'sales.user_id' => $UserId, 'sales.comp_id' => $CompId, 'sales.status' => 1];
            $EstimateWhere = ['estimates.invoiceDate >= ' => $FromDate, 'estimates.invoiceDate <= ' => $ToDate, 'estimates.user_id' => $UserId, 'estimates.comp_id' => $CompId, 'estimates.status' => 1];
            $PurchaseWhere = ['purchases.invoiceDate >= ' => $FromDate, 'purchases.invoiceDate <= ' => $ToDate, 'purchases.user_id' => $UserId, 'purchases.comp_id' => $CompId, 'purchases.status' => 1];
            $EstimatePurchaseWhere = ['estimate_purchase.invoiceDate >= ' => $FromDate, 'estimate_purchase.invoiceDate <= ' => $ToDate, 'estimate_purchase.user_id' => $UserId, 'estimate_purchase.comp_id' => $CompId, 'estimate_purchase.status' => 1];

            $InvoiceOpeningBalanceWhere = ['invoiceDate < ' => $FromDate, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $PaymentOpeningBalanceWhere = ['date < ' => $FromDate, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $LedgerOpeningBalanceWhere = ['Date < ' => $FromDate, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $PaymentWhere = ['date >= ' => $FromDate, 'date <= ' => $ToDate, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $OpeningBalanceWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $SaleWhere['sales.CompanyBranchId'] = $CompBranchId;
                $EstimateWhere['estimates.CompanyBranchId'] = $CompBranchId;
                $PurchaseWhere['purchases.CompanyBranchId'] = $CompBranchId;
                $EstimatePurchaseWhere['estimate_purchase.CompanyBranchId'] = $CompBranchId;
                $InvoiceOpeningBalanceWhere['CompanyBranchId'] = $CompBranchId;
                $PaymentOpeningBalanceWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerOpeningBalanceWhere['CompanyBranchId'] = $CompBranchId;
                $PaymentWhere['CompanyBranchId'] = $CompBranchId;
                $OpeningBalanceWhere['CompanyBranchId'] = $CompBranchId;
            }
            $SaleCashWhere = ['sales.voucherType' => 1];
            $EstimateCashWhere = ['estimates.voucherType' => 1];
            $PurchaseCashWhere = ['purchases.voucherType' => 1];
            $EstimatePurchaseCashWhere = ['estimate_purchase.voucherType' => 1];
            
            $SaleCreditWhere = ['sales.voucherType != ' => 1];
            $EstimateCreditWhere = ['estimates.voucherType != ' => 1];
            $PurchaseCreditWhere = ['purchases.voucherType != ' => 1];
            $EstimatePurchaseCreditWhere = ['estimate_purchase.voucherType != ' => 1];
            
            $CashReceiptPaymentWhere = ['paymentmode' => 1];
            $OtherReceiptPaymentWhere = ['paymentmode != ' => 1];
            $CashLedgerWhere = ['PaymentMode' => 1];
            $OtherLedgerWhere = ['PaymentMode !=' => 1];
            
            ////////////// CREDIT ///////////////////
            $CashOpeningBalance = (float) (get_title('comp_profile', $OpeningBalanceWhere, 'CashOpeningBalance') ?? 0);
            $CashOpeningBalanceTotalReceipt = (float) ($this->db->select_sum('receivedPaidAmount')->where($CashReceiptPaymentWhere)->where('payment_type', 'Receipt')->where($PaymentOpeningBalanceWhere)->get('payment')->row('receivedPaidAmount') ?? 0);
            $CashOpeningBalanceTotalPayment = (float) ($this->db->select_sum('credit')->where('payment_type', 'Payment')->where($CashReceiptPaymentWhere)->where($PaymentOpeningBalanceWhere)->get('payment')->row('credit') ?? 0);
            $CashOpeningBalanceTotalDisount = (float) ($this->db->select_sum('cashDiscountAmount')->where('payment_type', 'Receipt')->where($CashReceiptPaymentWhere)->where($PaymentOpeningBalanceWhere)->where('cashDiscountAmount != ', "")->get('payment')->row('cashDiscountAmount') ?? 0);
            $CashOpeningBalanceTotalLedgerCr = (float) ($this->db->select_sum('Amount')->where('Type', 'CR')->where($CashLedgerWhere)->where($LedgerOpeningBalanceWhere)->get('ledger_entries')->row('Amount') ?? 0);
            $CashOpeningBalanceTotalLedgerDr = (float) ($this->db->select_sum('Amount')->where('Type', 'DR')->where($CashLedgerWhere)->where($LedgerOpeningBalanceWhere)->get('ledger_entries')->row('Amount') ?? 0);
            
            $BankOpeningBalance = (float) (get_title('comp_profile', $OpeningBalanceWhere, 'BankOpeningBalance') ?? 0);
            $BankOpeningBalanceTotalReceipt = (float) ($this->db->select_sum('receivedPaidAmount')->where($OtherReceiptPaymentWhere)->where('payment_type', 'Receipt')->where($PaymentOpeningBalanceWhere)->get('payment')->row('receivedPaidAmount') ?? 0);
            $BankOpeningBalanceTotalPayment = (float) ($this->db->select_sum('credit')->where('payment_type', 'Payment')->where($CashReceiptPaymentWhere)->where($OtherReceiptPaymentWhere)->get('payment')->row('credit') ?? 0);
            $BankOpeningBalanceTotalDisount = (float) ($this->db->select_sum('cashDiscountAmount')->where('payment_type', 'Receipt')->where($OtherReceiptPaymentWhere)->where($PaymentOpeningBalanceWhere)->where('cashDiscountAmount != ', "")->get('payment')->row('cashDiscountAmount') ?? 0);
            $BankOpeningBalanceTotalLedgerCr = (float) ($this->db->select_sum('Amount')->where('Type', 'CR')->where($OtherLedgerWhere)->where($LedgerOpeningBalanceWhere)->get('ledger_entries')->row('Amount') ?? 0);
            $BankOpeningBalanceTotalLedgerDr = (float) ($this->db->select_sum('Amount')->where('Type', 'DR')->where($OtherLedgerWhere)->where($LedgerOpeningBalanceWhere)->get('ledger_entries')->row('Amount') ?? 0);
            
            $data['CashOpeningBalance'] = (($CashOpeningBalance + $CashOpeningBalanceTotalReceipt + $CashOpeningBalanceTotalLedgerCr) - ($CashOpeningBalanceTotalPayment + $CashOpeningBalanceTotalLedgerDr + $CashOpeningBalanceTotalDisount));
            $data['BankOpeningBalance'] = (($BankOpeningBalance + $BankOpeningBalanceTotalReceipt + $BankOpeningBalanceTotalLedgerCr) - ($BankOpeningBalanceTotalPayment + $BankOpeningBalanceTotalLedgerDr + $BankOpeningBalanceTotalDisount));
            
            $CreditCashSale = $this->db->select('sales.id AS Id, sales.customerId, sales.total, sales.type')->where($SaleCashWhere)->where($SaleWhere)->get_compiled_select('sales');
            $CreditCashEstimate = $this->db->select('estimates.id AS Id, estimates.customerId, estimates.total, estimates.type')->where($EstimateCashWhere)->where($EstimateWhere)->get_compiled_select('estimates');
            $data['CreditCashResult'] = $this->db->query($CreditCashSale . " UNION " . $CreditCashEstimate . " ORDER BY Id DESC")->result();
            
            $CreditCreditSale = $this->db->select('sales.id AS Id, sales.customerId, sales.total, sales.type')->where($SaleCreditWhere)->where($SaleWhere)->get_compiled_select('sales');
            $CreditCreditEstimate = $this->db->select('estimates.id AS Id, estimates.customerId, estimates.total, estimates.type')->where($EstimateCreditWhere)->where($EstimateWhere)->get_compiled_select('estimates');
            $data['CreditCreditResult'] = $this->db->query($CreditCreditSale . " UNION " . $CreditCreditEstimate . " ORDER BY Id DESC")->result();
            
            $data['CashReceiptResult'] = $this->db->select('id, party_id, receivedPaidAmount')->where('payment_type', 'Receipt')->where($CashReceiptPaymentWhere)->where($PaymentWhere)->where('payment_through', 'Manually')->order_by('id', 'DESC')->get('payment')->result();
            $data['OtherReceiptResult'] = $this->db->select('id, party_id, receivedPaidAmount')->where('payment_type', 'Receipt')->where($OtherReceiptPaymentWhere)->where($PaymentWhere)->order_by('id', 'DESC')->get('payment')->result();
            
            ////////////// DEBIT ///////////////////            
            $DebitCashPurchase = $this->db->select('purchases.id AS Id, purchases.customerId, purchases.total, purchases.type')->where($PurchaseWhere)->where($PurchaseCashWhere)->get_compiled_select('purchases');
            $DebitCashEstimatePurchase = $this->db->select('estimate_purchase.id AS Id, estimate_purchase.customerId, estimate_purchase.total, estimate_purchase.type')->where($EstimatePurchaseWhere)->where($EstimatePurchaseCashWhere)->get_compiled_select('estimate_purchase');
            $data['DebitCashResult'] = $this->db->query($DebitCashPurchase . " UNION " . $DebitCashEstimatePurchase . " ORDER BY Id DESC")->result();
            
            $DebitCreditPurchase = $this->db->select('purchases.id AS Id, purchases.customerId, purchases.total, purchases.type')->where($PurchaseWhere)->where($PurchaseCreditWhere)->get_compiled_select('purchases');
            $DebitCreditEstimatePurchase = $this->db->select('estimate_purchase.id AS Id, estimate_purchase.customerId, estimate_purchase.total, estimate_purchase.type')->where($EstimatePurchaseWhere)->where($EstimatePurchaseCreditWhere)->get_compiled_select('estimate_purchase');
            $data['DebitCreditResult'] = $this->db->query($DebitCreditPurchase . " UNION " . $DebitCreditEstimatePurchase . " ORDER BY Id DESC")->result();
            
            $data['PaymentResult'] = $this->db->select('id, party_id, credit, paymentmode')->where('payment_type', 'Payment')->where($PaymentWhere)->order_by('id', 'DESC')->get('payment')->result();
            
            $data['CashDiscountReceiptResult'] = $this->db->select('id, party_id, cashDiscountAmount')->where('payment_type', 'Receipt')->where($PaymentWhere)->where('cashDiscountAmount != ', "")->order_by('id', 'DESC')->get('payment')->result();
            
            $data['CreditAccountsGroup'] = $this->db->select('id, name')->where('Type', 'CR')->order_by('id', 'DESC')->get('accounting_group')->result();
            $data['DebitAccountsGroup'] = $this->db->select('id, name')->where('Type', 'DR')->order_by('id', 'DESC')->get('accounting_group')->result();
            
            $data['page'] = 'reports/DayBookReport';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function PaymentRequestReport()
        {
            if ($this->input->get('from') != "" && $this->input->get('to') != "") {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            if ($this->input->get('status') == "All" || $this->input->get('status') == "") {
                $where = ['date >=' => $from, 'date <=' => $to, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            } else {
                $status = $this->input->get('status');
                $where = ['date >=' => $from, 'date <=' => $to, 'status' => $status, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            }
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $SalesmanWhere = ['RoleId' => $RoleId, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'IsDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $SalesmanWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            if ($this->input->get('Salesman') != "All" && !empty($this->input->get('Salesman'))) {
                $where['SalesmanId'] = $this->input->get('Salesman');
            }
            
            $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('id', 'DESC')->get('employees')->result();
            $data['PaymentRequest'] = $this->db->select('*')->where($where)->order_by('id', 'DESC')->get('paymentrequest')->result();
            $data['page'] = 'reports/PaymentRequestReport';
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function PaymentRequestData($Id)
        {
            $where = ['id' => $Id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            $LedgerWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $LedgerWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['PaymentRequest'] = $this->db->select('*')->where($where)->get('paymentrequest')->row();
            $data['LedgerData'] = $this->db->select('id, name')->where($LedgerWhere)->order_by('id', 'DESC')->get('ladger')->result();
            return $this->load->view('inventory_sep/reports/PaymentRequestData', $data);
        }
        
        public function checkPaymentEntry() 
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

                    $Where = ['date' => $PaymentDate, 'party_id' => $PartyId, 'receivedPaidAmount' => $receivedPaidAmount, 'credit' => $Amount, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
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
        
        public function CheckPaymentEntryFromPaymentRequest() 
        {
            if ($this->input->is_ajax_request()) {
                $PaymentDate = $this->input->post('PaymentDate');
                $PartyId = $this->input->post('PartyId');
                $receivedPaidAmount = $this->input->post('receivedPaidAmount');
                $Amount = $this->input->post('Amount');
                $Status = $this->input->post('Status');
                
                if (!empty($PaymentDate) && !empty($PartyId) && !empty($receivedPaidAmount) && !empty($Amount) && !empty($Status)) {
                    if ($Status == 1) {
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
                        echo json_encode(['Status' => true, 'Data' => 'Unique', 'Title' => '', 'Text' => '']);
                    }
                } else {
                    echo json_encode(['Status' => false, 'Data' => 'NotUnique', 'Title' => 'Received Amount is Ziro', 'Text' => 'The received paid amount ziro. Do you want to proceed?']);
                }
            }
        }
        
        public function PaymentUpdate ()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $InvoiceId = $this->input->post('InvoiceId');
                $InvoiceType = $this->input->post('Type');
                $receivedPaidAmount = $this->input->post('receivedPaidAmount');
                $cashDiscountAmount = $this->input->post('cashDiscountAmount');
                $Amount = $this->input->post('Amount');
                $PaymentDate = $this->input->post('PaymentDate');
                $PartyId = $this->input->post('PartyId');

                $updated = date('d-m-Y h:i:s A');
                $updatedBy = get_title('users', ['id' => $UserId], 'name');
                
                $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                $UnorderNumberWhere = ['comp_id' => $CompId, 'user_id' => $UserId, 'status' => 1];
                $CommonWhere = ['id' => $InvoiceId, 'user_id' => $UserId, 'comp_id' => $CompId];
                $RequestWhere = ['id' => $this->input->post('RequestId'), 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SeriesWhere['series.CompanyBranchId'] = $CompBranchId;
                    $UnorderNumberWhere['CompanyBranchId'] = $CompBranchId;
                    $CommonWhere['CompanyBranchId'] = $CompBranchId;
                    $RequestWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $RequestData = [
                    'status' => $this->input->post('Status'),
                    'remark' => $this->input->post('Remark'),
                    'updated' => $updated,
                    'updated_by' => $updatedBy
                ];
                $Result = $this->user_model->updateAry('paymentrequest', $RequestData, $RequestWhere);
                
                if ($this->input->post('Status') == 1) {
                    if (is_array(json_decode($InvoiceType))) {
                        if (in_array('Sale', json_decode($InvoiceType))) {
                            $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                            $voucherType = "Tax";                            
                        } elseif (in_array('Estimate', json_decode($InvoiceType))) {
                            $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Estimate Voucher')->where($SeriesWhere)->get('seriestitle')->row();
                            $voucherType = "Estimate Voucher";                            
                        }
                    } else {
                        if ($InvoiceType == 'Sale') {
                            $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Tax')->where($SeriesWhere)->get('seriestitle')->row();
                            $voucherType = "Tax";
                        } elseif ($InvoiceType == 'Estimate') {
                            $Series = $this->db->select('*')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'Estimate Voucher')->where($SeriesWhere)->get('seriestitle')->row();
                            $voucherType = "Estimate Voucher";
                        }
                    }
                    $UnorderNumber = $this->db->select('id')->where($UnorderNumberWhere)->order_by('id', 'DESC')->get('payment')->row('id') + 1;

                    $PaymentData = [
                        'voucher_type' => $voucherType,
                        'voucher_number' => !empty($Series) ? $this->user_model->getSeris($Series) : $UnorderNumber,
                        'date' => $PaymentDate,
                        'account_group' => get_title('accounting_group', ['name' => 'Sundry Debitors'], 'id'),
                        'party_id' => $PartyId,
                        'invoice_id' => $InvoiceId,
                        'invoice_type' => $InvoiceType,
                        'debit' => $Amount,
                        'receivedPaidAmount' => $receivedPaidAmount,
                        'cashDiscountAmount' => $cashDiscountAmount,
                        'transaction_type' => "DR",
                        'payment_type' => "Receipt",
                        'paymentmode' => $this->input->post('PaymentModeId'),
                        'ChequeDate' => $this->input->post('ChequeDate'),
                        'payment_through' => "Manually",
                        'user_id' => $UserId,
                        'comp_id' => $CompId,
                        'CompanyBranchId' => $CompBranchId,
                        'financial_year' => $this->session->userdata('financial_year'),
                        'Remark' => $this->input->post('Remark'),
                        'created' => $updated
                    ];
                    $SavePayment = $this->user_model->saveAry('payment', $PaymentData);

                    $GetSeriesWhere = ['series_id' => $Series->series_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $GetSeriesWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    $GetSeries = $this->user_model->getRow('series', $GetSeriesWhere);
                    if ($GetSeries) {
                        $SeriesNumber['starter_point'] = $GetSeries->starter_point + 1;
                        $this->user_model->updateAry('series', $SeriesNumber, $GetSeriesWhere);
                    }
                    
                    if ($SavePayment) {
                        if (is_array(json_decode($InvoiceId)) && is_array(json_decode($InvoiceType))) {
                            $UpdatedBy = get_title('users', ['id' => $UserId], 'name');
                            $PaidAmount = $Amount;
                            $AmountToPaidInInvoice = [];
                            foreach (json_decode($InvoiceId) as $key => $SelectedRecord) {
                                $InvoiceTypeData = json_decode($InvoiceType)[$key];
                                $InvoiceIdData = $SelectedRecord;

                                $InvoiceWhere = ['id' => $InvoiceIdData, 'type' => $InvoiceTypeData, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];

                                if ($this->session->userdata('BranchRole')) {
                                    $InvoiceWhere['CompanyBranchId'] = $CompBranchId; 
                                }
                                $extra_where = ['paid_amount' => NULL];
                                $or_where = ('total != paid_amount');

                                //sale invoices
                                if ($InvoiceTypeData == "Sale") {
                                    $SaleInfo = $this->db->select('total, paid_amount')->where($InvoiceWhere)->group_start()->where($extra_where)->or_where($or_where)->group_end()->get('sales')->row();
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

                                    $SaleWhere = ['id' => $InvoiceIdData, 'type' => $InvoiceTypeData];
                                    $this->user_model->updateAry('sales', $SaleUpdates, $SaleWhere);
                                    // Break the loop if there's no remaining paid amount
                                    if ($PaidAmount <= 0) {
                                        break;
                                    }
                                }

                                //estimate invoices
                                if ($InvoiceTypeData == "Estimate") {
                                    $EstimateInfo = $this->db->select('total, paid_amount')->where($InvoiceWhere)->group_start()->where($extra_where)->or_where($or_where)->group_end()->get('estimates')->row();
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

                                    $EstimateWhere = ['id' => $InvoiceIdData, 'type' => $InvoiceTypeData];
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
                        } else {
                            // Sale
                            if ($InvoiceType == 'Sale') {
                                $SaleData = [
                                    'paid_amount' => get_title('sales', ['id' => $InvoiceId], 'paid_amount') + $Amount,
                                    'payment_date' => $PaymentDate,
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('sales', $SaleData, $CommonWhere);
                            }

                            // Estimate
                            if ($InvoiceType == 'Estimate') {
                                $EstimateData = [
                                    'paid_amount' => get_title('estimates', ['id' => $InvoiceId], 'paid_amount') + $Amount,
                                    'payment_date' => $PaymentDate,
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('estimates', $EstimateData, $CommonWhere);
                            }
                        }
                    }
                }
                
                if (!empty($Result)) {
                    echo json_encode(['Status' => true, 'Message' => 'Request status update successfully.']);
                    return;
                } else {
                    echo json_encode(['Status' => false, 'Message' => 'Unable to update record.']);
                    return;
                }
            }
        }
        
        public function SalesmanWiseSaleReport($start = 0)
        {            
            $invoiceNumber = '';
            $invoiceDate = '';
            $fromDate = '';
            $toDate = '';
            $partyName = '';
            $productName = '';
            $limit = '';
            $salesman = '';
            $start = is_numeric($start) ? (int) $start : 0;
            
            $first_date = date('Y-m-d'); // hard-coded '01' for first day
            $last_date  = date('Y-m-d');

            if ($this->input->get('fromDate') == '' && $this->input->get('toDate') == '') {
                $fromDate = ['invoiceDate >=' => $first_date];
                $toDate = ['invoiceDate <=' => $last_date];
            }
            
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                $first_date = date('Y-m-d', strtotime($this->input->get('fromDate')));
                $last_date = date('Y-m-d', strtotime($this->input->get('toDate')));
                $limit = '';
            }

            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');

            $salesmanFilter = function_exists('input_get_array') ? input_get_array('salesman') : (array) ($this->input->get('salesman') ?: []);
            if (in_array('All', $salesmanFilter, true) || empty($salesmanFilter)) {
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            } else {
                $salesman = $salesmanFilter;
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            }
            $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $CompanyBranchId;
                $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                $PartyWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            if (!empty($this->input->get('Party'))) {
                if ($this->input->get('Party') != 'All') {
                    $where['customerId'] = $this->input->get('Party');
                }
            }

            $data['start_date'] = $fromDate;
            $data['end_date'] = $toDate;

            $table = 'sales';
            $order = ['col' => 'id', 'order' => 'DESC'];

            $data['result'] = $this->user_model->getTblerDate2($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where, '', $salesman);
            $data['salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Id', 'DESC')->get('employees')->result();
            $data['Parties'] = $this->db->select('id, name')->where($PartyWhere)->order_by('id', 'DESC')->get('parties')->result();
            $data['page'] = "reports/SalesmanWiseSaleReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalesmanWiseEstimateReport($start = 0)
        {            
            $invoiceNumber = '';
            $invoiceDate = '';
            $fromDate = '';
            $toDate = '';
            $partyName = '';
            $productName = '';
            $limit = '';
            $salesman = '';
            $start = is_numeric($start) ? (int) $start : 0;
            
            $first_date = date('Y-m-d'); // hard-coded '01' for first day
            $last_date  = date('Y-m-d');

            if ($this->input->get('fromDate') == '' && $this->input->get('toDate') == '') {
                $fromDate = ['invoiceDate >=' => $first_date];
                $toDate = ['invoiceDate <=' => $last_date];
            }
            
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['invoiceDate >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                $toDate = ['invoiceDate <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                $first_date = date('Y-m-d', strtotime($this->input->get('fromDate')));
                $last_date = date('Y-m-d', strtotime($this->input->get('toDate')));
                $limit = '';
            }
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');

            $salesmanFilter = function_exists('input_get_array') ? input_get_array('salesman') : (array) ($this->input->get('salesman') ?: []);
            if (in_array('All', $salesmanFilter, true) || empty($salesmanFilter)) {
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            } else {
                $salesman = $salesmanFilter;
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            }
            $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $CompanyBranchId;
                $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                $PartyWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            if (!empty($this->input->get('Party'))) {
                if ($this->input->get('Party') != 'All') {
                    $where['customerId'] = $this->input->get('Party');
                }
            }
            
            $data['start_date'] = $fromDate;
            $data['end_date'] = $toDate;

            $table = 'estimates';
            $order = ['col' => 'id', 'order' => 'DESC'];

            $data['result'] = $this->user_model->getTblerDate2($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where, '', $salesman);
            $data['salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('id', 'DESC')->get('employees')->result();
            $data['Parties'] = $this->db->select('id, name')->where($PartyWhere)->order_by('id', 'DESC')->get('parties')->result();
            $data['page'] = "reports/SalesmanWiseEstimateReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function AccountSummary() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            $data['Where'] = $Where;
            $data['page'] = "reports/AccountSummary";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function PurchaseReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['PurchasesData'] = $this->db->get('purchases')->result();
            $data['page'] = "reports/PurchaseReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function PurchaseByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, voucherType, total, paid_amount');
                $this->db->where('customerId', $CustomerId);
                $this->db->order_by('id', 'DESC');
                $data['PurchasesInvoices'] = $this->db->get('purchases')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/PurchasesByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/PurchaseReport');
            }
        }
        
        public function PurchasePaymentReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            if (empty($this->session->userdata('BranchRole'))) {
                $Sql = "SELECT party_id, invoice_id, SUM(credit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Purchase\"]') OR (invoice_type = 'Purchase' AND payment_type = 'Payment' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1) GROUP BY party_id ORDER BY id DESC";
            } else {
                $Sql = "SELECT party_id, invoice_id, SUM(credit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Purchase\"]') OR (invoice_type = 'Purchase' AND payment_type = 'Payment' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1) GROUP BY party_id ORDER BY id DESC";
            }
            $Query = $this->db->query($Sql);
            $data['PurchasesPayments'] = $Query->result();
            $data['page'] = "reports/PurchasePaymentReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function PurchasePaymentByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $Sql = "SELECT date, invoice_id, paymentmode, credit AS PaidAmount FROM payment WHERE party_id = '" . $CustomerId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Purchase\"]') OR (invoice_type = 'Purchase' AND payment_type = 'Payment' AND party_id = '" . $CustomerId . "' AND status = 1) ORDER BY id DESC";
                $Query = $this->db->query($Sql);
                $data['PurchasesPayments'] = $Query->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/PurchasePaymentByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/PurchaseReport');
            }
        }
        
        public function PurchaseBalanceReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->group_start();
            $this->db->where('paid_amount', NULL);
            $this->db->or_where('total != paid_amount');
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['PurchasesBalances'] = $this->db->get('purchases')->result();
            $data['page'] = "reports/PurchaseBalanceReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function PurchaseBalanceByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, total AS Total, paid_amount AS PaidAmount');
                $this->db->where('customerId', $CustomerId);
                $this->db->group_start();
                $this->db->where('paid_amount', NULL);
                $this->db->or_where('total != paid_amount');
                $this->db->group_end();
                $this->db->order_by('id', 'DESC');
                $data['PurchasesBalances'] = $this->db->get('purchases')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/PurchaseBalanceByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/PurchaseReport');
            }
        }
        
        public function EstimatePurchaseReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['EstimatesPurchasesData'] = $this->db->get('estimate_purchase')->result();
            $data['page'] = "reports/EstimatePurchaseReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function EstimatePurchaseByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, voucherType, total, paid_amount');
                $this->db->where('customerId', $CustomerId);
                $this->db->order_by('id', 'DESC');
                $data['EstimatesPurchasesInvoices'] = $this->db->get('estimate_purchase')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/EstimatePurchaseByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/PurchaseReport');
            }
        }
        
        public function EstimatePurchasePaymentReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            if (empty($this->session->userdata('BranchRole'))) {
                $Sql = "SELECT party_id, invoice_id, SUM(credit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Estimate Purchase\"]') OR (invoice_type = 'Estimate Purchase' AND payment_type = 'Payment' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1) GROUP BY party_id ORDER BY id DESC";
            } else {
                $Sql = "SELECT party_id, invoice_id, SUM(credit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Estimate Purchase\"]') OR (invoice_type = 'Estimate Purchase' AND payment_type = 'Payment' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1) GROUP BY party_id ORDER BY id DESC";
            }
            $Query = $this->db->query($Sql);
            $data['EstimatesPurchasesPayments'] = $Query->result();
            $data['page'] = "reports/EstimatePurchasePaymentReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function EstimatePurchasePaymentByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $Sql = "SELECT date, invoice_id, paymentmode, credit AS PaidAmount FROM payment WHERE party_id = '" . $CustomerId . "' AND status = 1 AND payment_type = 'Payment' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Estimate Purchase\"]') OR (invoice_type = 'Estimate Purchase' AND payment_type = 'Payment' AND party_id = '" . $CustomerId . "' AND status = 1) ORDER BY id DESC";
                $Query = $this->db->query($Sql);
                $data['EstimatesPurchasesPayments'] = $Query->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/EstimatePurchasePaymentByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/PurchaseReport');
            }
        }
        
        public function EstimatePurchaseBalanceReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->group_start();
            $this->db->where('paid_amount', NULL);
            $this->db->or_where('total != paid_amount');
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['EstimatesPurchasesBalances'] = $this->db->get('estimate_purchase')->result();
            $data['page'] = "reports/EstimatePurchaseBalanceReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function EstimatePurchaseBalanceByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, total AS Total, paid_amount AS PaidAmount');
                $this->db->where('customerId', $CustomerId);
                $this->db->group_start();
                $this->db->where('paid_amount', NULL);
                $this->db->or_where('total != paid_amount');
                $this->db->group_end();
                $this->db->order_by('id', 'DESC');
                $data['EstimatesPurchasesBalances'] = $this->db->get('estimate_purchase')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/EstimatePurchaseBalanceByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/PurchaseReport');
            }
        }
        
        public function SaleReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['SalesData'] = $this->db->get('sales')->result();
            $data['page'] = "reports/SaleReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SaleByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, voucherType, total, paid_amount');
                $this->db->where('customerId', $CustomerId);
                $this->db->order_by('id', 'DESC');
                $data['SalesInvoices'] = $this->db->get('sales')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/SaleByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/SaleReport');
            }
        }
        
        public function SaleReceivedReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            if (empty($this->session->userdata('BranchRole'))) {
                $Sql = "SELECT party_id, invoice_id, SUM(debit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Receipt' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Sale\"]') OR (invoice_type = 'Sale' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Receipt') GROUP BY party_id ORDER BY id DESC";
            } else {
                $Sql = "SELECT party_id, invoice_id, SUM(debit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1 AND payment_type = 'Receipt' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Sale\"]') OR (invoice_type = 'Sale' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1 AND payment_type = 'Receipt') GROUP BY party_id ORDER BY id DESC";
            }
            $Query = $this->db->query($Sql);
            $data['SalesReceiveds'] = $Query->result();
            $data['page'] = "reports/SaleReceivedReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function SaleReceivedByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $Sql = "SELECT date, invoice_id, paymentmode, debit AS PaidAmount FROM payment WHERE party_id = '" . $CustomerId . "' AND status = 1 AND payment_type = 'Receipt' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Sale\"]') OR (invoice_type = 'Sale' AND party_id = '" . $CustomerId . "' AND status = 1 AND payment_type = 'Receipt') ORDER BY id DESC";
                $Query = $this->db->query($Sql);
                $data['SalesReceiveds'] = $Query->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/SaleReceivedByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/SaleReport');
            }
        }
        
        public function SaleBalanceReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->group_start();
            $this->db->where('paid_amount', NULL);
            $this->db->or_where('total != paid_amount');
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['SalesBalances'] = $this->db->get('sales')->result();
            $data['page'] = "reports/SaleBalanceReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function SaleBalanceByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, total AS Total, paid_amount AS PaidAmount');
                $this->db->where('customerId', $CustomerId);
                $this->db->group_start();
                $this->db->where('paid_amount', NULL);
                $this->db->or_where('total != paid_amount');
                $this->db->group_end();
                $this->db->order_by('id', 'DESC');
                $data['SalesBalances'] = $this->db->get('sales')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/SaleBalanceByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/SaleReport');
            }
        }
        
        public function EstimateReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['EstimatesData'] = $this->db->get('estimates')->result();
            $data['page'] = "reports/EstimateReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function EstimateByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, voucherType, total, paid_amount');
                $this->db->where('customerId', $CustomerId);
                $this->db->order_by('id', 'DESC');
                $data['EstimatesInvoices'] = $this->db->get('estimates')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/EstimateByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/SaleReport');
            }
        }
        
        public function EstimateReceivedReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            if (empty($this->session->userdata('BranchRole'))) {
                $Sql = "SELECT party_id, invoice_id, SUM(debit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Receipt' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Estimate\"]') OR (invoice_type = 'Estimate' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND status = 1 AND payment_type = 'Receipt') GROUP BY party_id ORDER BY id DESC";
            } else {
                $Sql = "SELECT party_id, invoice_id, SUM(debit) AS PaidAmount FROM payment WHERE user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1 AND payment_type = 'Receipt' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Estimate\"]') OR (invoice_type = 'Estimate' AND user_id = '" . $UserId . "' AND comp_id = '" . $CompId . "' AND CompanyBranchId = '" . $CompanyBranchId . "' AND status = 1) GROUP BY party_id ORDER BY id DESC";
            }
            $Query = $this->db->query($Sql);
            $data['EstimatesReceiveds'] = $Query->result();
            $data['page'] = "reports/EstimateReceivedReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function EstimateReceivedByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $Sql = "SELECT date, invoice_id, paymentmode, ChequeDate, debit AS PaidAmount FROM payment WHERE party_id = '" . $CustomerId . "' AND payment_type = 'Receipt' AND invoice_type IS NOT NULL AND JSON_VALID(invoice_type) AND JSON_CONTAINS(invoice_type, '[\"Estimate\"]') OR (invoice_type = 'Estimate' AND payment_type = 'Receipt' AND party_id = '" . $CustomerId . "') ORDER BY id DESC";
                $Query = $this->db->query($Sql);
                $data['EstimatesReceiveds'] = $Query->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/EstimateReceivedByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/SaleReport');
            }
        }
        
        public function EstimateBalanceReport() 
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount');
            $this->db->where($Where);
            $this->db->group_start();
            $this->db->where('paid_amount', NULL);
            $this->db->or_where('total != paid_amount');
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
            $this->db->group_by('customerId');
            $data['EstimatesBalances'] = $this->db->get('estimates')->result();
            $data['page'] = "reports/EstimateBalanceReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function EstimateBalanceByCustomer($CustomerId) 
        {
            if (isset($CustomerId) && !empty($CustomerId)) {
                $this->db->select('invoiceDate, invoiceNumber, total AS Total, paid_amount AS PaidAmount');
                $this->db->where('customerId', $CustomerId);
                $this->db->group_start();
                $this->db->where('paid_amount', NULL);
                $this->db->or_where('total != paid_amount');
                $this->db->group_end();
                $this->db->order_by('id', 'DESC');
                $data['EstimatesBalances'] = $this->db->get('estimates')->result();
                $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
                $data['page'] = "reports/EstimateBalanceByCustomer";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect(base_url() . 'Reports/SaleReport');
            }
        }
        
        public function PurchaseRequestReport() 
        {
                if ($this->input->get('From') && $this->input->get('To')) {
                        $from = $this->input->get('From');
                        $to = $this->input->get('To');
                } else {
                        $from = date('Y-m-d');
                        $to = date('Y-m-d');
                }
            
                $compId = $this->session->userdata('comp_id');
                $userId = $this->session->userdata('user_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                $branchInfo = null;
                if (!empty($compBranchId)) {
                        $branchInfo = $this->db->select('branchDivision, UserId, mainBranchId')->where(['Id' => $compBranchId, 'CompId' => $compId, 'IsDeleted' => 1])->get('companybranch')->row();
                }
            
                $partyWhere = ['comp_id' => $compId, 'status' => 1];
                $where = ['invoiceDate >= ' => $from, 'invoiceDate <= ' => $to, 'comp_id' => $compId, 'status' => 1, 'financial_year' => $financialYear];
                if (!empty($branchInfo) && ($branchInfo->branchDivision ?? '') == 'Y') {
                        $mainBranchInfo = $this->db->select('Id, BranchUserId')->where(['Id' => $branchInfo->mainBranchId, 'CompId' => $compId, 'IsDeleted' => 1])->get('companybranch')->row();
                        if (!empty($mainBranchInfo)) {
                                $partyWhere['CompanyBranchId'] = $mainBranchInfo->Id;
                                $partyWhere['user_id'] = $mainBranchInfo->BranchUserId;
                                $partyWhere['subBranchId'] = $compBranchId;
                                $where['CompanyBranchId'] = $branchInfo->mainBranchId;
                                $where['user_id'] = $mainBranchInfo->BranchUserId;
                        }
                } elseif (!empty($branchInfo) && ($branchInfo->branchDivision ?? '') == 'N') {
                        $partyWhere['user_id'] = $branchInfo->UserId;
                        $partyWhere['CompanyBranchId'] = $compBranchId;
                        $where['user_id'] = $branchInfo->UserId;
                } else {
                        $partyWhere['user_id'] = $userId;
                        $where['user_id'] = $userId;
                        if (!empty($compBranchId) && $this->session->userdata('BranchRole')) {
                                $partyWhere['CompanyBranchId'] = $compBranchId;
                                $where['CompanyBranchId'] = $compBranchId;
                        }
                }
                $customerId = $this->db->select('id')->where($partyWhere)->get('parties')->row('id');
                if (!empty($customerId)) {
                        $where['customerId'] = $customerId;
                }
            
                if ($this->input->get('RequestStatus') != "") {
                        $where['PurchaseRequest'] = $this->input->get('RequestStatus');
                }
            
                $this->db->select('id, invoiceDate, invoiceNumber, customerId, taxableAmount, totalTax, total, PurchaseRequest');
                $this->db->where($where);
                $this->db->order_by('id', 'DESC');
                $data['SalesData'] = $this->db->get('sales')->result();
                $data['page'] = "reports/PurchaseRequestReport";
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function PaymentDiscountReport() 
        {
            if ($this->input->get('From') && $this->input->get('To')) {
                $From = $this->input->get('From');
                $To = $this->input->get('To');
            } else {
                $From = date('Y-m-d');
                $To = date('Y-m-d');
            }
            
            $CompId = $this->session->userdata('comp_id');
            $UserId = $this->session->userdata('user_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['date >= ' => $From, 'date <= ' => $To, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'transaction_type' => 'DR', 'payment_type' => 'Receipt', 'cashDiscountAmount != ' => 0];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            $this->db->select('date, invoice_type, invoice_id, party_id, paymentmode, receivedPaidAmount, debit, cashDiscountAmount, remark');
            $this->db->where($Where);
            $this->db->order_by('id', 'DESC');
            $data['PaymentData'] = $this->db->get('payment')->result();
            $data['page'] = "reports/PaymentDiscountReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function LeaveRequestReport() 
        {
            if ($this->input->get('From') && $this->input->get('To')) {
                $From = $this->input->get('From');
                $To = $this->input->get('To');
            } else {
                $From = date('Y-m-d');
                $To = date('Y-m-d');
            }
                        
            $CompId = $this->session->userdata('comp_id');
            $UserId = $this->session->userdata('user_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['Date >= ' => $From, 'Date <= ' => $To, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->input->get('Status') != "") {
                $Where['Status'] = $this->input->get('Status');
            }
            
            $this->db->where($Where);
            $this->db->order_by('Id', 'DESC');
            $data['LeaveRequestData'] = $this->db->get('leave_request')->result();
            $data['page'] = "reports/LeaveRequestReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function LeaveRequestData($Id) 
        {
            if (isset($Id) && !empty($Id)) {
                $Where = ['Id' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                if ($this->db->where($Where)->get('leave_request')->num_rows()) {
                    $data['LeaveRequestData'] = $this->db->where($Where)->get('leave_request')->row();
                    return $this->load->view('inventory_sep/reports/LeaveRequestData', $data);    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Leave id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function UpdateLeaveRequest()
        {
            $EmployeeLeaveData = [
                "Status" => $this->input->post('Status'),
                "AdminRemark" => $this->input->post('AdminRemark'),
                "Updated" => date('d-m-Y h:i:s A'),
                "UpdatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
            ];

            $Result = $this->user_model->updateAry('leave_request', $EmployeeLeaveData, ['Id' => $this->input->post('LeaveId')]);

            if ($Result) {
                $this->session->set_flashdata(['msg' => 'Status updated Successfully.', 'class' => 'success']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata(['msg' => 'Failed. try again', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function UpdateLeaveRequestData()
        {
            $EmployeeLeaveData = [
                "StartDate" => $this->input->post('StartDate'),
                "EndDate" => $this->input->post('EndDate'),
                "Remark" => $this->input->post('Remark'),
                "Updated" => date('d-m-Y h:i:s A'),
                "UpdatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
            ];

            $Result = $this->user_model->updateAry('leave_request', $EmployeeLeaveData, ['Id' => $this->input->post('LeaveId')]);

            if ($Result) {
                $this->session->set_flashdata(['msg' => 'Leave updated Successfully.', 'class' => 'success']);
                return redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata(['msg' => 'Failed. try again', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function LeaveRequestDataEdit($Id) 
        {
            if (isset($Id) && !empty($Id)) {
                $Where = ['Id' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                if ($this->db->where($Where)->get('leave_request')->num_rows()) {
                    $data['LeaveRequestData'] = $this->db->where($Where)->get('leave_request')->row();
                    return $this->load->view('inventory_sep/reports/LeaveRequestDataEdit', $data);    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Leave id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }       
        
        public function PaymentFollowUpChatHandler($CustomerId)
        {
            if (!empty($CustomerId)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $SaleWhere = ['customerId' => $CustomerId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $SaleWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                if ($this->db->select('id')->where($SaleWhere)->where('type', 'Sale')->get('sales')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('customerId, customerName, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount')->where($SaleWhere)->get('sales')->row();
                } elseif ($this->db->select('id')->where($SaleWhere)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('customerId, customerName, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount')->where($SaleWhere)->get('estimates')->row();
                }
                return $this->load->view('inventory_sep/Employee/PaymentFollowUpChatHandler', $data);
            }
        }
        
        public function PaymentFollowUpChatSave() 
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
                    $PaymentFollowUpChatData = [
                        'Date' => date('Y-m-d'),
                        'CustomerId' => $this->input->post('CustomerId'),
                        'Chat' => $this->input->post('Chat'),
                        'UserId' => $UserId,
                        'CompId' => $CompId,
                        'CompanyBranchId' => $CompBranchId,
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $UserId], 'name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('payment_followup_chat', $PaymentFollowUpChatData);
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
        
        public function getPaymentFollowUpChat() 
        {
            if ($this->input->is_ajax_request()) {
                $CustomerId = $this->input->post('CustomerId');
                if ($CustomerId == "" && $CustomerId == null) {
                    echo json_encode(['Status' => false, 'Chat' => '<span class="text-danger text-center">Enter required fields.</span>', 'From' => '', 'To' => '']);
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                                
                    $Where = ['CustomerId' => $CustomerId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId; 
                    }
                    
                    $PaymentFollowUpChat = $this->db->select('Chat, Created, CreatedBy')->where($Where)->order_by('Id', 'ASC')->get('payment_followup_chat')->result();

                    if ($PaymentFollowUpChat) {
                        $ImageUrl = base_url() . 'assets/images/avatar.png';
                        $chatHtml = '';

                        foreach ($PaymentFollowUpChat as $key => $Chat) {
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
        
        public function PartyPaymentStatement() 
        {
            if ($this->input->is_ajax_request()) {
                $PartyId = $this->input->post('PartyId');
                if (isset($PartyId) && !empty($PartyId)) {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $PartyWhere = ['id' => $PartyId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                    $Where = ['party_id' => $PartyId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $PartyWhere['CompanyBranchId'] = $CompBranchId; 
                        $Where['CompanyBranchId'] = $CompBranchId; 
                    }
                    $PartyData = $this->db->select('id, group_head, added_at')->where($PartyWhere)->get('parties')->row();
                    if ($PartyData) {
                        $Where['date >= '] = date('Y-m-d', strtotime($PartyData->added_at));
                        $Where['date <= '] = date('Y-m-d');
                        $Where['account_group'] = $PartyData->group_head;
                        
                        if ($this->db->where($Where)->get('payment')->num_rows()) {
                            $PaymentData = $this->db->where($Where)->order_by('id', 'ASC')->get('payment')->result();
                            
                            $Table = '<h4 class="text-center">Payment Statement</h4>'
                                    . '<table class="table">'
                                    . '<thead>'
                                    . '<tr>'
                                    . '<td>Sr</td>'
                                    . '<td>Date</td>'
                                    . '<td>Type</td>'
                                    . '<td>Voucher Number</td>'
                                    . '<td>Particulars</td>'
                                    . '<td>Narration</td>'
                                    . '<td>Debit</td>'
                                    . '<td>Credit</td>'
                                    . '<td>Balance</td>'
                                    . '</tr>'
                                    . '</thead>'
                                    . '</tbody>';
                                $Sr = 0;
                                $TotalCredit = 0;
                                $TotalDebit = 0;
                                $TotalBalance = 0;
                            foreach ($PaymentData as $key => $Data) {
                                $rowCredit = (float) ($Data->credit ?? 0);
                                $rowDebit = (float) ($Data->debit ?? 0);
                                $TotalCredit += $rowCredit;
                                $TotalDebit += $rowDebit;
                                $TotalBalance += ($rowCredit - $rowDebit);
                                $Sr++;
                                
                                $Type = $Data->payment_type == "" ? $Data->invoice_type : $Data->payment_type;
                                $invoiceTypes = !empty($Data->invoice_type) ? json_decode($Data->invoice_type, true) : [];
                                if (!is_array($invoiceTypes)) {
                                    $invoiceTypes = [];
                                }
                                $VoucherNumber = '';
                                $Particulars = '';
                                
                                if (!empty($invoiceTypes) || ($Data->invoice_type == "Sale" && $Data->payment_type == "Receipt") || ($Data->invoice_type == "Estimate" && $Data->payment_type == "Receipt")) {
                                        $VoucherNumber = $Data->voucher_number;
                                } else {
                                    if ($Data->invoice_type == "Sale") {
                                        $VoucherNumber = ($Data->payment_type == NULL) ? get_title('sales', ['id' => $Data->invoice_id], 'invoiceNumber') : "";
                                    } elseif ($Data->invoice_type == "Estimate") {
                                        $VoucherNumber = ($Data->payment_type == NULL) ? get_title('estimates', ['id' => $Data->invoice_id], 'invoiceNumber') : "";
                                    }
                                }
                                
                                if (in_array("Sale", $invoiceTypes, true) || $Data->invoice_type == "Sale" || in_array("Estimate", $invoiceTypes, true) || $Data->invoice_type == "Estimate") {
                                    if ($Data->transaction_type == "CR") {
                                        $Particulars = "Cr" . "&nbsp;" . "Sales";
                                    } elseif ($Data->transaction_type == "DR")  {
                                        $Particulars = "Dr" . "&nbsp;" . get_title("payment_mode", ["id" => $Data->paymentmode], "payment_method");
                                    }
                                }
                                
                                $Debit = $rowDebit ? "<i class='fa fa-inr'></i> " . number_format($rowDebit, 2) : "";
                                $Credit = $rowCredit ? "<i class='fa fa-inr'></i> " . number_format($rowCredit, 2) : "";
                                $Balance = "<i class='fa fa-inr'></i> " . number_format($TotalBalance, 2);
                                
                                $Table .= '<tr>'
                                        . '<td>' . $Sr . '</td>'
                                        . '<td>' . date('d-m-Y', strtotime($Data->date)) . '</td>'
                                        . '<td>' . $Type . '</td>'
                                        . '<td>' . $VoucherNumber . '</td>'
                                        . '<td>' . $Particulars . '</td>'
                                        . '<td></td>'
                                        . '<td>' . $Debit . '</td>'
                                        . '<td>' . $Credit . '</td>'
                                        . '<td>' . $Balance . '</td>'
                                        . '</tr>';
                            }
                            $Table .= '</tbody>'
                                    . '<tfoot>'
                                    . '<tr>'
                                    . '<th colspan="6" style="text-align: right;">Total</th>'
                                    . '<th><i class="fa fa-inr"></i> ' . number_format($TotalCredit, 2) . '</th>'
                                    . '<th><i class="fa fa-inr"></i> ' . number_format($TotalDebit, 2) . '</th>'
                                    . '<th><i class="fa fa-inr"></i> ' . number_format($TotalBalance, 2) . '</th>'
                                    . '</tr>'
                                    . '</tfoot>'
                                    . '</table>';
                            
                            echo json_encode(array('Status' => true, 'Data' => $Table, 'Msg' => ''));
                            return;
                        } else {
                            echo json_encode(array('Status' => false, 'Data' => '', 'Msg' => 'Payment data not found.'));
                            return;
                        }
                    } else {
                        echo json_encode(array('Status' => false, 'Data' => '', 'Msg' => 'Party data not found.'));
                        return;
                    }
                } else {
                    echo json_encode(array('Status' => false, 'Data' => '', 'Msg' => 'Party id missing.'));
                    return;
                }
            }
        }
        
        public function DebitNoteRequestReport()
        {
            if ($this->input->get('From') && $this->input->get('To')) {
                $From = $this->input->get('From');
                $To = $this->input->get('To');
            } else {
                $From = date('Y-m-d');
                $To = date('Y-m-d');
            }
            
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $UserId = get_title('companybranch', ['Id' => $CompBranchId, 'CompId' => $CompId, 'IsDeleted' => 1], 'UserId');
            if (empty($UserId)) {
                $UserId = $this->session->userdata('user_id');
            }
            $FinancialYear = $this->session->userdata('financial_year');
            
            $PartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if (!empty($CompBranchId)) {
                $PartyWhere['CompanyBranchId'] = $CompBranchId;
            }
            $CustomerId = $this->db->select('id')->where($PartyWhere)->get('parties')->row('id');

            $Where = ['invoiceDate >= ' => $From, 'invoiceDate <= ' => $To, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            if (!empty($CustomerId)) {
                $Where['customerId'] = $CustomerId;
            }
            
            $this->db->select('id, invoiceDate, invoiceNumber, customerId, taxableAmount, totalTax, total, DebitNoteRequest');
            $this->db->where($Where);
            $this->db->order_by('id', 'DESC');
            $data['CreditNotesData'] = $this->db->get('credit_notes')->result();
            $data['page'] = "reports/DebitNoteRequestReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalesmanWiseDueReport()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $salesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            
            $salesmanWhere = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1, 'RoleId' => $salesmanRoleId];
            if ($this->session->userdata('BranchRole')) {
                $salesmanWhere['CompanyBranchId'] = $compBranchId;
            }
            
            $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->order_by('Name', 'ASC')->get('employees')->result();
            $data['page'] = "reports/SalesmanWiseDueReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalesmanWiseDueCustomers($salesmanId)
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $salesQuery = $this->db->select('customerId, COUNT(id) AS totalInvoice, SUM(total) AS total, SUM(paid_amount) AS paidAmount, MAX(invoiceDate) AS invoiceDate, type')
                ->where(['SalesmanId' => $salesmanId, 'status' => 1])
                ->group_start()
                ->where('paid_amount', NULL)
                ->or_where('total != paid_amount')
                ->group_end()
                ->group_by('customerId')
                ->get_compiled_select('sales');

            $estimateSalesQuery = $this->db->select('customerId, COUNT(id) AS totalInvoice, SUM(total) AS total, SUM(paid_amount) AS paidAmount, MAX(invoiceDate) AS invoiceDate, type')
                ->where(['SalesmanId' => $salesmanId, 'status' => 1])
                ->group_start()
                ->where('paid_amount', NULL)
                ->or_where('total != paid_amount')
                ->group_end()
                ->group_by('customerId')
                ->get_compiled_select('estimates');

            $data['salesData'] = $this->db->query("($salesQuery) UNION ALL ($estimateSalesQuery) ORDER BY invoiceDate DESC")->result();
            $data['salesmanName'] = get_title('employees', ['Id' => $salesmanId], 'Name');
            $data['page'] = "reports/SalesmanWiseDueCustomers";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getSalesmanWiseDueInvoiceDates()
        {
            $customerId = $this->input->get('customerId');
            $invoiceType = $this->input->get('invoiceType');
            
            if ($invoiceType == 'Sale') {
                $salesQuery = $this->db->select('customerId, invoiceDate, type')
                    ->where(['customerId' => $customerId, 'status' => 1])
                    ->group_start()
                    ->where('paid_amount', NULL)
                    ->or_where('total != paid_amount')
                    ->group_end()
                    ->order_by('invoiceDate', 'DESC')
                    ->get('sales');
            } elseif ($invoiceType == 'Estimate') {
                $salesQuery = $this->db->select('customerId, invoiceDate, type')
                    ->where(['customerId' => $customerId, 'status' => 1])
                    ->group_start()
                    ->where('paid_amount', NULL)
                    ->or_where('total != paid_amount')
                    ->group_end()
                    ->order_by('invoiceDate', 'DESC')
                    ->get('estimates');                
            }
            
            $data['invoiceInfo'] = $salesQuery->result();
            return $this->load->view('inventory_sep/reports/getSalesmanWiseDueInvoiceDates', $data);
        }
        
        public function SalesmanWiseDueInvoices($customerId)
        {
            $salesQuery = $this->db->select('id, invoiceDate, invoiceNumber, total AS total, paid_amount AS paidAmount, type')
                ->where(['customerId' => $customerId, 'status' => 1])
                ->group_start()
                ->where('paid_amount', NULL)
                ->or_where('total != paid_amount')
                ->group_end()
                ->get_compiled_select('sales');
            
            $estimateSalesQuery = $this->db->select('id, invoiceDate, invoiceNumber, total AS total, paid_amount AS paidAmount, type')
                ->where(['customerId' => $customerId, 'status' => 1])
                ->group_start()
                ->where('paid_amount', NULL)
                ->or_where('total != paid_amount')
                ->group_end()
                ->get_compiled_select('estimates');
            
            $data['salesData'] = $this->db->query("($salesQuery) UNION ALL ($estimateSalesQuery) ORDER BY invoiceDate DESC")->result();
            $data['salesmanId'] = get_title('parties', ['id' => $customerId], 'SalesmanId');
            $data['customerName'] = get_title('parties', ['id' => $customerId], 'name');
            $data['page'] = "reports/SalesmanWiseDueInvoices";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalesmanWiseDueEstimateReport()
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $SalesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            
            $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
            if ($this->session->userdata('BranchRole')) {
                $SalesmanWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Name', 'ASC')->get('employees')->result();
            $data['page'] = "reports/SalesmanWiseDueEstimateReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalesmanWiseDueEstimateCustomers($SalesmanId)
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $SalesQuery = $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount')->where(['SalesmanId' => $SalesmanId, 'status' => 1])->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->group_by('customerId')->get('estimates');
            if ($SalesQuery->num_rows()) {
                $data['SalesData'] = $SalesQuery->result();
            }
            $data['SalesmanName'] = get_title('employees', ['Id' => $SalesmanId], 'Name');
            $data['page'] = "reports/SalesmanWiseDueEstimateCustomers";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalesmanWiseDueEstimateInvoices($CustomerId)
        {
            $SalesQuery = $this->db->select('id, invoiceDate, invoiceNumber, total AS Total, paid_amount AS PaidAmount')->where(['customerId' => $CustomerId, 'status' => 1])->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get('estimates');
            if ($SalesQuery->num_rows()) {
                $data['SalesData'] = $SalesQuery->result();
            }
            $data['SalesmanId'] = get_title('parties', ['id' => $CustomerId], 'SalesmanId');
            $data['CustomerName'] = get_title('parties', ['id' => $CustomerId], 'name');
            $data['page'] = "reports/SalesmanWiseDueEstimateInvoices";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function OldSaleBySalesmanReport()
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $SalesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $FinancialYear = $this->session->userdata('financial_year');
            
            $SalesWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
            if ($this->session->userdata('BranchRole')) {
                $SalesWhere['CompanyBranchId'] = $CompBranchId;
                $SalesmanWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->input->get('From') && $this->input->get('To')) {
                $SalesWhere['invoiceDate >= '] = $this->input->get('From');
                $SalesWhere['invoiceDate <= '] = $this->input->get('To');
            } else {
                $SalesWhere['invoiceDate >= '] = date('Y-m-d');
                $SalesWhere['invoiceDate <= '] = date('Y-m-d');
            }

            $Salesman = $this->input->get('Salesman');
            if ($Salesman == "All" || $Salesman == "") {
                $SaleQuery = $this->db->select('OldSalesmanId')->where($SalesWhere)->where('OldSalesmanId IS NOT NULL')->get('sales');
                if ($SaleQuery->num_rows()) {
                    $OldSalesmanId = [];
                    foreach ($SaleQuery->result() as $key => $Data) {
                        $OldSalesmanId[] = $Data->OldSalesmanId;
                    }
                    $data['SalesData'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, total, paid_amount, OldSalesmanId')->where($SalesWhere)->where_in('OldSalesmanId', $OldSalesmanId)->order_by('id', 'DESC')->get('sales')->result();
                }
            } else {
                $data['SalesData'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, total, paid_amount, OldSalesmanId')->where($SalesWhere)->where('OldSalesmanId', $Salesman)->order_by('id', 'DESC')->get('sales')->result();
            }
            
            $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Name', 'ASC')->get('employees')->result();
            $data['page'] = "reports/OldSaleBySalesmanReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function EmployeeTracking()
        {
            ini_set('memory_limit', '-1');
            set_time_limit(5000);
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['CompId' => $CompId];
            $employeeWhere = ['Status' => 1, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $employeeWhere['CompanyBranchId'] = $CompBranchId;
                $Where['UserId'] = $UserId;
                $Address = urlencode(get_title('companybranch', ['Id' => $CompBranchId], 'Address'));
            } else {
                if ($this->input->get('branchId')) {
                    $Address = urlencode(get_title('companybranch', ['Id' => $this->input->get('branchId')], 'Address'));
                } else {
                    $Address = urlencode(get_title('inventory_billing', ['id' => $CompId], 'address'));
                }
            }
            
            $APIKey = 'AIzaSyCnr2cIpAbxu32ce__6vwmr8vuLWbz5L4M';
            $URL = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $Address . "&key=" . $APIKey;
            $Response = file_get_contents($URL);
            $ResponseData = json_decode($Response);
            if ($ResponseData->status == 'OK') {
                $LatitudeLongitude = $ResponseData->results[0]->geometry->location;
                $data['Latitude'] = $LatitudeLongitude->lat;
                $data['Longitude'] = $LatitudeLongitude->lng;
            } else {
                $data['Latitude'] = '20.5937';
                $data['Longitude'] = '78.9629';
            }
            
            if ($this->input->get('Date')) {
                $Where['Date >= '] = $this->input->get('Date');
                $Where['Date <= '] = $this->input->get('Date');
            } else {
                $Where['Date >= '] = date('Y-m-d');
                $Where['Date <= '] = date('Y-m-d');
            }
            
            if ($this->session->userdata('roles') == 1) {
                if ($this->input->get('branchId')) {
                    $Where['CompanyBranchId'] = $this->input->get('branchId');
                }
            }
            
            if ($this->input->get('employeeId') != "All") {
                $Where['EmployeeId'] = $this->input->get('employeeId');
            }
            
            $EmployeeTrackingQuery = $this->db->select('EmployeeId, StartLatitude, StartLongitude, EndLatitude, EndLongitude')->where($Where)->order_by('Id', 'ASC')->get('employee_tracking');
            if ($EmployeeTrackingQuery->num_rows()) {
                $LocationData = [];
                foreach ($EmployeeTrackingQuery->result() as $key => $Data) {
                    $PointsData = $this->db->select('EndLatitude, EndLongitude')->where($Where)->where('EmployeeId', $Data->EmployeeId)->get('employee_tracking')->result();
                    $Points = [];
                    foreach ($PointsData as $key => $PointVal) {
                        $Points[] = ['lat' => $PointVal->EndLatitude, 'lng' => $PointVal->EndLongitude];
                    }
                    
                    $Photograph = get_title('employees', ['Id' => $Data->EmployeeId], 'Photograph');
                    if (!empty($Photograph)) {
                        $photoUrl = base_url() . 'uploads/Employee/Photograph/' . $Photograph;
                    } else {
                        $photoUrl = base_url() . 'assets/images/avatar.png';
                    }
                    
                    $LocationData[] = [
                        'EmployeeId' => $Data->EmployeeId,
                        'EmployeeName' => get_title('employees', ['Id' => $Data->EmployeeId], 'Name'),
                        'startPoint' => [
                            'lat' => $Data->StartLatitude,
                            'lng' => $Data->StartLongitude
                        ],
                        'endPoint' => [
                            'lat' => $Data->EndLatitude,
                            'lng' => $Data->EndLongitude
                        ],
                        'color' => getRandomColor(),
                        'photoUrl' => $photoUrl,
                        'Points' => $Points
                    ];
                }
            } else {
                $LocationData[] = array();
            }
            
            $data['LocationData'] = json_encode($LocationData, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            $data['page'] = "reports/EmployeeTracking";
            $data['AttendanceDate'] = $this->input->get('Date') ? $this->input->get('Date') : date('Y-m-d');
            $data['companyBranches'] = $this->db->select('Id, BranchName')->where(['IsDeleted' => 1, 'UserId' => $UserId])->order_by('Id', 'DESC')->get('companybranch');
            $data['employeesData'] = $this->db->select('Id, Name')->where($employeeWhere)->order_by('Id', 'DESC')->get('employees');
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function EmployeeTrackingData($EmployeeId, $AttendanceDate)
        {
            if (isset($EmployeeId) && !empty($EmployeeId)) {
                $Date = $AttendanceDate ? $AttendanceDate : date('Y-m-d');
                $EmployeeQuery = $this->db->select('EmpId, Date, InTime, OutTime, WorkingHours, Attendance, SelfieImage')->where(['Date >= ' => $Date, 'Date <= ' => $Date, 'EmpId' => $EmployeeId])->get('employee_attendance');
                if ($EmployeeQuery->num_rows()) {    
                    $data['Date'] = $Date;
                    $data['EmployeeId'] = $EmployeeId;
                    $data['EmployeeData'] = $EmployeeQuery->row();
                    return $this->load->view('inventory_sep/reports/EmployeeTrackingData', $data);
                } else {
                    $data['EmployeeId'] = $EmployeeId;
                    $data['EmployeeData'] = "";
                    return $this->load->view('inventory_sep/reports/EmployeeTrackingData', $data);
                }
            } else {
                $data['EmployeeId'] = "";
                $data['EmployeeData'] = "";
                return $this->load->view('inventory_sep/reports/EmployeeTrackingData', $data);                    
            }
        }        
        
        public function withdrawalRequestReport()
        {
            if ($this->input->get('from') != "" && $this->input->get('to') != "") {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $where = ['date >=' => $from, 'date <=' => $to, 'isDeleted' => 1];
            if ($this->input->get('status') != "All" && $this->input->get('status') != "") {
                $where['status'] = $this->input->get('status');
            }
            
            $data['rewardPoints'] = $this->db->where($where)->order_by('id', 'DESC')->get('reward_points_withdrawal')->result();
            $data['rewardPointCostData'] = $this->db->select('rewardPoint, equivalentRupee')->get('reward_point_cost')->row();
            $data['page'] = "reports/withdrawalRequestReport";
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function withdrawalRequestInfo($requestId)
        {
            if ($requestId) {
                $rewardRequestQuery = $this->db->where(['id' => $requestId, 'isDeleted' => 1])->get('reward_points_withdrawal');
                if ($rewardRequestQuery->num_rows()) {
                    $data['rewardRequestInfo'] = $rewardRequestQuery->row();
                    $data['electricianData'] = $this->db->select('name, mobile, upiId')->where('id', $rewardRequestQuery->row()->userId)->get('electrician')->row();
                    $data['rewardPointCostData'] = $this->db->select('rewardPoint, equivalentRupee')->get('reward_point_cost')->row();
                    return $this->load->view('inventory_sep/reports/withdrawalRequestInfo', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Request id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }            
        }
        
        public function withdrawalRequestUpdate()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $requestId = $this->input->post('requestId');
                $transactionDate = $this->input->post('transactionDate');
                $status = $this->input->post('status');
                $adminRemark = $this->input->post('adminRemark');
                $RequestData = [
                    'transactionDate' => $transactionDate,
                    'status' => $status,
                    'adminRemark' => $adminRemark,
                    'updated' => date('d-m-Y h:i:s A'),
                    'updatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                ];

                $InvoiceUpdate = $this->user_model->updateAry('reward_points_withdrawal', $RequestData, ['id' => $requestId]);
                if ($InvoiceUpdate) {
                    if ($status == "2") {
                        $requestInfo = $this->db->select('rewardPoints, userId')->where('id', $requestId)->get('reward_points_withdrawal')->row();
                        $walletQuery = $this->db->select('SUM(credit) as Credit, SUM(debit) AS Debit')->where('userId', $requestInfo->userId)->get('electrician_wallet')->row();
                        $walletData = [
                            'date' => date('Y-m-d'),
                            'credit' => $requestInfo->rewardPoints,
                            'balance' => (($walletQuery->Credit - $walletQuery->Debit) + $requestInfo->rewardPoints),
                            'userId' => $requestInfo->userId,
                            'keyNote' => $adminRemark,
                            'created' => date('d-m-Y h:i:s A'),
                            'createdBy' => get_title('electrician', ['id' => $requestInfo->userId], 'name')
                        ];
                        $this->db->insert('electrician_wallet', $walletData);
                    }
                    
                    if ($status == "1") {
                        $title = "Approved";
                        $body = "Your request approved successfully. " . $adminRemark;
                    } elseif ($status == "2") {
                        $title = "Rejected";
                        $body = "Your request rejected. " . $adminRemark; 
                    }                    
                    
                    $serviceAccountPath = FCPATH . 'udayplastic-aee23-fba6607f9253.json';
                    $projectId = 'udayplastic-aee23';
                    $message = [
                        'token' => get_title('reward_points_withdrawal', ['id' => $requestId], 'fcmId'),
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                    ];

                    try {
                        $accessToken = getAccessToken($serviceAccountPath);
                        $response = sendMessage($accessToken, $projectId, $message);
                        //echo 'Message sent successfully: ' . print_r($response, true);
                    } catch (Exception $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    
                    $this->session->set_flashdata(['msg' => 'Status updated successfully', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to update data please try again', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }            
        }       
        
        public function employeeSalesReport()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $roleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $salesmanWhere = ['RoleId' => $roleId, 'UserId' => $userId, 'CompId' => $compId, 'isDeleted' => 1];
            $employeeSalesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $salesmanWhere['CompanyBranchId'] = $compBranchId;
                $employeeSalesWhere['compBranchId'] = $compBranchId;
            }
            
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $employeeSalesWhere['employeeId'] = $this->input->get('employeeId');
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->get('employees')->result();
            $data['employeeSales'] = $this->db->select('id, date, month, SUM(grandTotal) AS grandTotal')->where($employeeSalesWhere)->group_by('date')->order_by('id', 'DESC')->get('employee_sales')->result();
            $data['page'] = "reports/employeeSalesReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function employeeSalesInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $employeeSalesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            $employeeSalesPartiesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $employeeSalesWhere['employeeId'] = $this->input->get('employeeId');
                $employeeSalesPartiesWhere['employeeId'] = $this->input->get('employeeId');
            }
            if ($this->session->userdata('BranchRole')) {
                $employeeSalesWhere['compBranchId'] = $compBranchId;
                $employeeSalesPartiesWhere['compBranchId'] = $compBranchId;
            }

            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['employeeSalesInfo'] = $this->db->where($employeeSalesWhere)->group_by('date')->order_by('id', 'DESC')->get('employee_sales')->row();
            $data['employeeSalesParties'] = $this->db->where($employeeSalesPartiesWhere)->get('employee_sales_parties')->result();
            return $this->load->view('inventory_sep/reports/employeeSalesInfo', $data);
        }
        
        public function employeeSalesEdit()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            }
            
            $employeesWhere = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeeSalesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $employeeSalesWhere['compBranchId'] = $compBranchId;
                $employeesWhere['CompanyBranchId'] = $compBranchId;
            }

            if ($this->input->get('department') != '') {
                $employeeSalesWhere['department'] = $this->input->get('department');
            }
            if ($this->input->get('band') != '') {
                $employeeSalesWhere['band'] = $this->input->get('band');
            }
            if ($this->input->get('stateId') != '') {
                $employeeSalesWhere['stateId'] = $this->input->get('stateId');
            }
            if ($this->input->get('employeeId') != 'All') {
                $employeeSalesWhere['employeeId'] = $this->input->get('employeeId');
            }

            $data['employees'] = $this->db->select('Id, Name')->where($employeesWhere)->get('employees')->result();
            $employeeSalesQuery = $this->db->where($employeeSalesWhere)->get('employee_sales');
            if ($employeeSalesQuery->num_rows()) {
                $data['employeeSalesInfo'] = $employeeSalesQuery->row();
                $data['from'] = $from;
                $data['to'] = $to;
                $data['employeeId'] = $this->input->get('employeeId');
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['seniorEmployees'] = $this->db->select('Id, Name')->where($employeesWhere)->where('RoleId != ', 1)->where('WarehouseSalesman IS NOT NULL')->get('employees')->result();
                $data['page'] = "reports/employeeSalesEdit";
                return $this->load->view('inventory_sep/inventory', $data);                
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }            
        }
        
        public function getEmployeeSalesParties()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                $from = $this->input->get('from');
                $to = $this->input->get('to');

                $salesPartiesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $salesPartiesWhere['compBranchId'] = $compBranchId;
                }
                if ($this->input->get('employeeId') != 'All') {
                    $salesPartiesWhere['employeeId'] = $this->input->get('employeeId');
                }

                $employeeSalesPartiesQuery = $this->db->select('id, billingDate, distributorName, retailerName, location, contactPerson, mobileNumber, netSale, seniorEmployeeId, employeeId, remark, created, createdBy, updated, updatedBy')->where($salesPartiesWhere)->get('employee_sales_parties');
                if ($employeeSalesPartiesQuery->num_rows()) {
                    echo json_encode(array('status' => true, "data" => $employeeSalesPartiesQuery->result()));
                } else {
                    echo json_encode(array('status' => false, "data" => "No Product found."));
                }
            }
        }
        
        public function deleteEmployeeSalesData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $salesDataWhere = ['id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $salesDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeSalesData = $this->db->select('employeeSalesId, netSale')->where($salesDataWhere)->get('employee_sales_parties')->row();
                    $where['id'] = $employeeSalesData->employeeSalesId;
                    $currentGrandTotal = get_title('employee_sales', $where, 'grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeSalesData->netSale;
                    $this->db->where($where)->update('employee_sales', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($salesDataWhere)->update('employee_sales_parties', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }
        
        public function updateEmployeeSales()
        {
            if ($this->input->is_ajax_request()) {
                $arraySalesData = [];
                foreach ($this->input->post('parties') as $key => $row) {
                    if ($row['distributorName'] == '' || $row['retailerName'] == '' || $row['location'] == '') {
                        $arraySalesData[] = $row;
                    }
                }

                if (!empty($arraySalesData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter sales required fields.']);
                    return;
                }
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $from = $this->input->post('from');
                    $to = $this->input->post('to');
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('users', ['id' => $userId], 'name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    $updateWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $employeeSalesPartiesUpdateWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    if ($this->input->post('employeeId') != 'All') {
                        $updateWhere['employeeId'] = $this->input->post('employeeId');
                        $employeeSalesPartiesUpdateWhere['employeeId'] = $this->input->post('employeeId');
                    }
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $employeeSalesPartiesUpdateWhere['compBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeSalesData = [
                        'date' => $this->input->post('date'),
                        'baseLocation' => $this->input->post('baseLocation'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'remark' => $this->input->post('remark'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->user_model->updateAry('employee_sales', $employeeSalesData, $updateWhere);                   
                    
                    foreach ($this->input->post('parties') as $key => $party) {
                        if (!empty($party['rowId']) && !empty($party['distributorName']) && !empty($party['retailerName']) && !empty($party['location'])) {
                            $employeeSalesPartiesData = [
                                'date' => $this->input->post('date'),
                                'billingDate' => $party['billingDate'],
                                'distributorName' => $party['distributorName'],
                                'retailerName' => $party['retailerName'],
                                'location' => $party['location'],
                                'contactPerson' => $party['contactPerson'],
                                'mobileNumber' => $party['mobileNumber'],
                                'netSale' => $party['netSale'],
                                'remark' => $party['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            $employeeSalesPartiesUpdateWhere['id'] = $party['rowId'];
                            $this->user_model->updateAry('employee_sales_parties', $employeeSalesPartiesData, $employeeSalesPartiesUpdateWhere);
                        }
                    }                   
                    
                    foreach ($this->input->post('newParties') as $key => $partyData) {
                        if (!empty($partyData['distributorName']) && !empty($partyData['retailerName']) && !empty($partyData['location'])) {
                            $employeeSalesNewPartiesData = [
                                'date' => $this->input->post('date'),
                                'employeeSalesId' => $this->db->select('id')->where(['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $partyData['employeeId']])->order_by('id', 'DESC')->get('employee_sales')->row('id'),
                                'billingDate' => $partyData['billingDate'],
                                'distributorName' => $partyData['distributorName'],
                                'retailerName' => $partyData['retailerName'],
                                'location' => $partyData['location'],
                                'contactPerson' => $partyData['contactPerson'],
                                'mobileNumber' => $partyData['mobileNumber'],
                                'netSale' => $partyData['netSale'],
                                'remark' => $partyData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $partyData['seniorEmployeeId'],
                                'employeeId' => $partyData['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_sales_parties', $employeeSalesNewPartiesData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeSalesDelete()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $this->input->get('from'), 'date <= ' => $this->input->get('to'), 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $where['employeeId'] = $this->input->get('employeeId');
            }
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $compBranchId;
            }
            
            if ($this->db->select('id')->where($where)->get('employee_sales')->num_rows()) {
                if ($this->user_model->updateAry('employee_sales', ['isDeleted' => 0], $where)) {
                    $this->user_model->updateAry('employee_sales_parties', ['isDeleted' => 0], $where);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeSalesReportDownload() 
        {
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            $employeeId = $this->input->get('employeeId');
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $partiesWhere = ['date >=' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $where['employeeId'] = $employeeId;
                $partiesWhere['employeeId'] = $employeeId;
            }            
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $partiesWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");            
            $SaleReportQuery = $this->db->where($where)->group_by('date')->get('employee_sales');
            
            if ($SaleReportQuery->num_rows()) {
                $saleData = $SaleReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);

                // Add the logo image
                $PHPExcelDrawing1 = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing1->setName('Logo');
                $PHPExcelDrawing1->setDescription('Logo');
                $PHPExcelDrawing1->setPath($logoPath);
                $PHPExcelDrawing1->setHeight(65);
                $PHPExcelDrawing1->setCoordinates('A1');
                $PHPExcelDrawing1->setWorksheet($PHPExcel->getActiveSheet());
                
                $PHPExcelDrawing2 = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing2->setName('Logo');
                $PHPExcelDrawing2->setDescription('Logo');
                $PHPExcelDrawing2->setPath($logoPath);
                $PHPExcelDrawing2->setHeight(65);
                $PHPExcelDrawing2->setCoordinates('J1');
                $PHPExcelDrawing2->setWorksheet($PHPExcel->getActiveSheet());
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B1', $Name . ' SALES REPORT')
                    ->mergeCells('B1:J1')
                    ->setCellValue('A2', 'Base Location')
                    ->setCellValue('C2', $saleData->baseLocation)
                    ->mergeCells('A2:B2')
                    ->mergeCells('C2:D2')
                    ->setCellValue('E2', 'State')
                    ->setCellValue('G2', get_title('state_list', ['id' => $saleData->stateId], 'code') . '-' . get_title('state_list', ['id' => $saleData->stateId], 'state'))
                    ->mergeCells('E2:F2')
                    ->mergeCells('G2:H2')
                    ->mergeCells('I2:J2')
                    ->setCellValue('A3', 'Band')
                    ->setCellValue('C3', $saleData->band)
                    ->mergeCells('A3:B3')
                    ->mergeCells('C3:D3')
                    ->setCellValue('E3', 'Department')
                    ->setCellValue('G3', $saleData->department)
                    ->mergeCells('E3:F3')
                    ->mergeCells('G3:H3')
                    ->mergeCells('I3:J3')
                    ->setCellValue('A4', 'Month')
                    ->setCellValue('C4', $saleData->month)
                    ->mergeCells('A4:B4')
                    ->mergeCells('C4:D4')                    
                    ->mergeCells('E4:F4')                    
                    ->mergeCells('G4:H4')
                    ->mergeCells('I4:J4');

                // Add table headers
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', 'Billing Date')
                    ->setCellValue('B5', 'Team Leader')
                    ->setCellValue('C5', 'Salesman')
                    ->setCellValue('D5', 'Distributor Name from whom material supplied')
                    ->setCellValue('E5', 'Retailer / Sub dealer Name')
                    ->setCellValue('F5', 'Location')
                    ->setCellValue('G5', 'Contact Person Name')
                    ->setCellValue('H5', 'Contact Person Mobile No')
                    ->setCellValue('I5', 'Bill value (Net Sale) excluding GST')
                    ->setCellValue('J5', 'Remark');
                
                $PHPExcel->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'font' => array(
                        'bold' => true,
                        'size' => 12
                    )
                );

                $PHPExcel->getActiveSheet()->getStyle('A5:J5')->applyFromArray($styleArray);

                $PHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 25
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                $SaleReportPartiesQuery = $this->db->where($partiesWhere)->get('employee_sales_parties');
                if ($SaleReportPartiesQuery->num_rows()) {
                    $rowCount = 6;
                    $totalNetSale = 0;
                    foreach ($SaleReportPartiesQuery->result() as $key => $salesPerties) {
                        $totalNetSale += $salesPerties->netSale;
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d-m-Y', strtotime($salesPerties->billingDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, get_title('employees', ['Id' => $salesPerties->seniorEmployeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, get_title('employees', ['Id' => $salesPerties->employeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $salesPerties->distributorName);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $salesPerties->retailerName);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $salesPerties->location);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $salesPerties->contactPerson);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $salesPerties->mobileNumber);
                        $PHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $salesPerties->netSale);
                        $PHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $salesPerties->remark);
                        $rowCount++;
                    }
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $totalNetSale);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->getFont()->setBold(true);
                }
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

                $PHPExcel->getActiveSheet()->getStyle('A1:J' . $rowCount)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000435')
                            )
                        )
                    )
                );
                
                $PHPExcel->getActiveSheet()->setTitle('SALES_REPORT');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_SALES_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }      
            
        public function employeeTravelsReport()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }

            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');            
            $where = ['RoleId' => $RoleId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
            }
            
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $employeeTravelsWhere['employeeId'] = $this->input->get('salesmanId');
            }

            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['salesmans'] = $this->db->select('Id, Name')->where($where)->get('employees')->result();
            $data['employeeTravels'] = $this->db->select('id, date, month, SUM(grandTotal) AS grandTotal')->where($employeeTravelsWhere)->group_by('date')->order_by('id', 'DESC')->get('employee_travels')->result();
            $data['page'] = "reports/employeeTravelsReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function employeeTravelsInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            $employeeTravelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $employeeTravelsWhere['employeeId'] = $this->input->get('employeeId');
                $employeeTravelsDataWhere['employeeId'] = $this->input->get('employeeId');
            }            
            if ($this->session->userdata('BranchRole')) {
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
                $employeeTravelsDataWhere['compBranchId'] = $compBranchId;
            }

            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->group_by('date')->order_by('id', 'DESC')->get('employee_travels')->row();
            $data['employeeTravelsData'] = $this->db->where($employeeTravelsDataWhere)->get('employee_travels_data')->result();
            return $this->load->view('inventory_sep/reports/employeeTravelsInfo', $data);
        }
        
        public function employeeTravelsEdit()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            }
            
            $employeesWhere = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
                $employeesWhere['CompanyBranchId'] = $compBranchId;
            }
            
            if ($this->input->get('department') != '') {
                $employeeTravelsWhere['department'] = $this->input->get('department');
            }
            if ($this->input->get('band') != '') {
                $employeeTravelsWhere['band'] = $this->input->get('band');
            }
            if ($this->input->get('stateId') != '') {
                $employeeTravelsWhere['stateId'] = $this->input->get('stateId');
            }
            if ($this->input->get('employeeId') != 'All') {
                $employeeTravelsWhere['employeeId'] = $this->input->get('employeeId');
            }

            $data['employees'] = $this->db->select('Id, Name')->where($employeesWhere)->get('employees')->result();
            $employeeTravelsQuery = $this->db->where($employeeTravelsWhere)->get('employee_travels');
            if ($employeeTravelsQuery->num_rows()) {
                $data['employeeTravelsInfo'] = $employeeTravelsQuery->row();
                $data['from'] = $from;
                $data['to'] = $to;
                $data['employeeId'] = $this->input->get('employeeId');
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['seniorEmployees'] = $this->db->select('Id, Name')->where($employeesWhere)->where('RoleId != ', 1)->where('WarehouseSalesman IS NOT NULL')->get('employees')->result();
                $data['page'] = "reports/employeeTravelsEdit";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getEmployeeTravelsData()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                $from = $this->input->get('from');
                $to = $this->input->get('to');
                
                $TravelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $TravelsDataWhere['compBranchId'] = $compBranchId;
                }
                if ($this->input->get('employeeId') != 'All') {
                    $TravelsDataWhere['employeeId'] = $this->input->get('employeeId');
                }                

                $employeeTravelsDataQuery = $this->db->where($TravelsDataWhere)->get('employee_travels_data');
                if ($employeeTravelsDataQuery->num_rows()) {
                    echo json_encode(array('status' => true, "data" => $employeeTravelsDataQuery->result()));
                } else {
                    echo json_encode(array('status' => false, "data" => "No Product found."));
                }
            }
        }
        
        public function deleteEmployeeTravelsData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $travelsDataWhere = ['id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $travelsDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeTravelsData = $this->db->select('employeeTravelId, total')->where($travelsDataWhere)->get('employee_travels_data')->row();
                    $where['id'] = $employeeTravelsData->employeeTravelId;
                    $currentGrandTotal = get_title('employee_travels', $where, 'grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeTravelsData->total;
                    $this->db->where($where)->update('employee_travels', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($travelsDataWhere)->update('employee_travels_data', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }
        
        public function updateEmployeeTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravelData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['placesVisited'] == '') {
                        $arrayTravelData[] = $row;
                    }
                }

                if (!empty($arrayTravelData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $from = $this->input->post('from');
                    $to = $this->input->post('to');
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('users', ['id' => $userId], 'name');
                    
                    $updateWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $employeeTravelDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $employeeTravelDataWhere['compBranchId'] = $compBranchId;
                    }
                    if ($this->input->post('employeeId') != 'All') {
                        $updateWhere['employeeId'] = $this->input->post('employeeId');
                        $employeeTravelDataWhere['employeeId'] = $this->input->post('employeeId');
                    }                    
                    
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->user_model->updateAry('employee_travels', $employeeTravels, $updateWhere);

                    foreach ($this->input->post('travels') as $key => $party) {
                        if (!empty($party['rowId']) && !empty($party['travelDate']) && !empty($party['placesVisited'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'travelDate' => $party['travelDate'],
                                'placesVisited' => $party['placesVisited'],
                                'dailyAllowanceClaimed' => $party['dailyAllowanceClaimed'],
                                'internetFax' => $party['internetFax'],
                                'xeroxPrint' => $party['xeroxPrint'],
                                'postCourier' => $party['postCourier'],
                                'total' => $party['total'],
                                'remark' => $party['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            $employeeTravelDataWhere['id'] = $party['rowId'];
                            $this->user_model->updateAry('employee_travels_data', $employeeTravelsData, $employeeTravelDataWhere);
                        }
                    }                    

                    foreach ($this->input->post('newTravels') as $key => $newTravelData) {
                        if (!empty($newTravelData['travelDate']) && !empty($newTravelData['placesVisited'])) {
                            $employeeNewTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $this->db->select('id')->where(['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $newTravelData['employeeId']])->order_by('id', 'DESC')->get('employee_travels')->row('id'),
                                'travelDate' => $newTravelData['travelDate'],
                                'placesVisited' => $newTravelData['placesVisited'],
                                'dailyAllowanceClaimed' => $newTravelData['dailyAllowanceClaimed'],
                                'internetFax' => $newTravelData['internetFax'],
                                'xeroxPrint' => $newTravelData['xeroxPrint'],
                                'postCourier' => $newTravelData['postCourier'],
                                'total' => $newTravelData['total'],
                                'remark' => $newTravelData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $newTravelData['seniorEmployeeId'],
                                'employeeId' => $newTravelData['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_travels_data', $employeeNewTravelsData);
                        }
                    }                    
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeTravelsDelete()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $this->input->get('from'), 'date <= ' => $this->input->get('to'), 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $where['employeeId'] = $this->input->get('employeeId');
            }
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $compBranchId;
            }
            
            if ($this->db->select('id')->where($where)->get('employee_travels')->num_rows()) {
                if ($this->user_model->updateAry('employee_travels', ['isDeleted' => 0], $where)) {
                    $this->user_model->updateAry('employee_travels_data', ['isDeleted' => 0], $where);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeTravelsReportDownload()
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);

            $from = $this->input->get('from');
            $to = $this->input->get('to');     
            $employeeId = $this->input->get('employeeId');
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $travelsDataWhere = ['date >=' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $where['employeeId'] = $employeeId;
                $travelsDataWhere['employeeId'] = $employeeId;
            }
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $travelsDataWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $TravelsReportQuery = $this->db->where($where)->group_by('date')->get('employee_travels');
            if ($TravelsReportQuery->num_rows()) {
                $travelData = $TravelsReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                                
                // Define styles
                $topHeaderStyleArray = [
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $headerStyleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $dataStyleArray = [
                    'font' => [
                        'size' => 10,
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];                
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
                                
                // Add the logo image
                $PHPExcelDrawing = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing->setName('Logo');
                $PHPExcelDrawing->setDescription('Logo');
                $PHPExcelDrawing->setPath($logoPath);
                $PHPExcelDrawing->setHeight(60);
                $PHPExcelDrawing->setCoordinates('J1');
                $PHPExcelDrawing->setWorksheet($PHPExcel->getActiveSheet());                
                $PHPExcel->getActiveSheet()->getStyle('J1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Sale:')
                    ->mergeCells('A1:C1')
                    ->mergeCells('D1:E1')
                    ->setCellValue('A2', 'Total Expenses:')
                    ->mergeCells('A2:C2')
                    ->mergeCells('D2:E2')
                    ->setCellValue('F1', 'Format effective date: ' . date('jS F, Y'))
                    ->mergeCells('F1:I2')
                    ->setCellValue('A3', 'LOCAL TRAVEL')                    
                    ->mergeCells('A3:J3')
                    ->setCellValue('A4', 'Band')
                    ->setCellValue('D4', $travelData->band)
                    ->mergeCells('A4:C4')
                    ->mergeCells('D4:E4')
                    ->setCellValue('F4', 'Department')
                    ->setCellValue('H4', $travelData->department)
                    ->mergeCells('F4:G4')                        
                    ->mergeCells('H4:J4')                        
                    ->setCellValue('A5', 'Location / Place of posting')
                    ->setCellValue('D5', $travelData->location . " - " . get_title('state_list', ['id' => $travelData->stateId], 'state'))
                    ->mergeCells('A5:C5')
                    ->mergeCells('D5:E5')
                    ->setCellValue('F5', 'Head Quarter')
                    ->setCellValue('H5', $travelData->headQuarter)
                    ->mergeCells('F5:G5')
                    ->mergeCells('H5:J5')
                    ->setCellValue('A6', 'Month')
                    ->setCellValue('D6', $travelData->month)
                    ->mergeCells('A6:C6')
                    ->mergeCells('D6:E6')
                    ->setCellValue('F6', '')
                    ->setCellValue('H6', '')
                    ->mergeCells('F6:G6')
                    ->mergeCells('H6:J6');

                // Set column titles
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', 'DATE (DD/MM/YYYY)')
                    ->mergeCells('A7:A8')
                    ->setCellValue('B7', 'SENIOR EMPLOYEE')
                    ->mergeCells('B7:B8')                        
                    ->setCellValue('C7', 'SALESMAN')
                    ->mergeCells('C7:C8')
                    ->setCellValue('D7', 'PLACES VISITED')
                    ->mergeCells('D7:D8')
                    ->setCellValue('E7', 'DAILY ALLOWANCE CLAIMED')
                    ->mergeCells('E7:E8')
                    ->setCellValue('F7', 'OTHER MISC. EXPENSES')
                    ->mergeCells('F7:H7')
                    ->setCellValue('F8', 'INTERNET/FAX')
                    ->setCellValue('G8', 'XEROX/PRINT')
                    ->setCellValue('H8', 'POST/COURIER')
                    ->setCellValue('I7', 'TOTAL')
                    ->mergeCells('I7:I8')
                    ->setCellValue('J7', 'REMARK')
                    ->mergeCells('J7:J8');
                                
                $TravelDataReportQuery = $this->db->where($travelsDataWhere)->get('employee_travels_data');
                if ($TravelDataReportQuery->num_rows()) {
                    $rowCount = 9;
                    $totalDailyAllowanceClaimed = 0;
                    $totalInternetFax = 0;
                    $totalXeroxPrint = 0;
                    $totalPostCourier = 0;
                    $totalAmount = 0;
                    foreach ($TravelDataReportQuery->result() as $key => $travelsData) {
                        $totalDailyAllowanceClaimed += $travelsData->dailyAllowanceClaimed;
                        $totalInternetFax += $travelsData->internetFax;
                        $totalXeroxPrint += $travelsData->xeroxPrint;
                        $totalPostCourier += $travelsData->postCourier;
                        $totalAmount += $travelsData->total;
                        
                        $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':J' . $rowCount)->applyFromArray($dataStyleArray);
                        
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d/m/Y', strtotime($travelsData->travelDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, get_title('employees', ['Id' => $travelsData->seniorEmployeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, get_title('employees', ['Id' => $travelsData->employeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $travelsData->placesVisited);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $travelsData->dailyAllowanceClaimed);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $travelsData->internetFax);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $travelsData->xeroxPrint);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $travelsData->postCourier);
                        $PHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $travelsData->total);
                        $PHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $travelsData->remark);
                        $rowCount++;
                    }
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':J' . $rowCount)->applyFromArray($dataStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':D' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':D' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    $PHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $totalDailyAllowanceClaimed);
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $totalInternetFax);
                    $PHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $totalXeroxPrint);
                    $PHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $totalPostCourier);
                    $PHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $totalAmount);
                    
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':J' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':J' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':J' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':J' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    
                    $rowCount++;
                    $previousRowNumber = ($rowCount - 1);
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Employee');
                    $PHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, 'Reporting Senior');
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, 'Director');
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':J' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $previousRowNumber . ':C' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $previousRowNumber . ':F' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('H' . $previousRowNumber . ':J' . $rowCount);
                }
                
                $PHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(35);
                $PHPExcel->getActiveSheet()->getStyle('A7:J8')->getAlignment()->setWrapText(true);
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                
                // Apply styles
                $PHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('E1:J2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('E1:J2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);                
                $PHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('E1:J2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A7:J8')->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A4:J7')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A7:J8')->applyFromArray($headerStyleArray);
                
                // Set background colors
                $PHPExcel->getActiveSheet()->getStyle('A1:C8')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('C3:J3')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('E3:G6')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A7:J8')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);

                $PHPExcel->getActiveSheet()->setTitle('LOCAL_TRAVEL');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_LOCAL_TRAVEL_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }               
        
        public function employeeOutstationTravelsReport()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $where = ['RoleId' => $RoleId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeeTravelsWhere['compBranchId'] = $compBranchId;                
            }
            
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $employeeTravelsWhere['employeeId'] = $this->input->get('salesmanId');
            }

            $data['salesmans'] = $this->db->select('Id, Name')->where($where)->get('employees')->result();
            $data['employeeTravels'] = $this->db->where($employeeTravelsWhere)->order_by('id', 'DESC')->get('employee_outstation_travels')->result();
            $data['page'] = "reports/employeeOutstationTravelsReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function employeeOutstationTravelsInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            $employeeTravelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $employeeTravelsWhere['employeeId'] = $this->input->get('employeeId');
                $employeeTravelsDataWhere['employeeId'] = $this->input->get('employeeId');
            }
            if ($this->session->userdata('BranchRole')) {
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
                $employeeTravelsDataWhere['compBranchId'] = $compBranchId;
            }

            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->group_by('date')->order_by('id', 'DESC')->get('employee_outstation_travels')->row();
            $data['employeeTravelsData'] = $this->db->where($employeeTravelsDataWhere)->get('employee_outstation_travels_data')->result();
            return $this->load->view('inventory_sep/reports/employeeOutstationTravelsInfo', $data);
        }
        
        public function employeeOutstationTravelsEdit()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            }

            $employeesWhere = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
                $employeesWhere['CompanyBranchId'] = $compBranchId;
            }
            if ($this->input->get('department') != '') {
                $employeeTravelsWhere['department'] = $this->input->get('department');
            }
            if ($this->input->get('band') != '') {
                $employeeTravelsWhere['band'] = $this->input->get('band');
            }
            if ($this->input->get('stateId') != '') {
                $employeeTravelsWhere['stateId'] = $this->input->get('stateId');
            }
            if ($this->input->get('employeeId') != 'All') {
                $employeeTravelsWhere['employeeId'] = $this->input->get('employeeId');
            }

            $data['employees'] = $this->db->select('Id, Name')->where($employeesWhere)->get('employees')->result();
            $employeeTravelsQuery = $this->db->where($employeeTravelsWhere)->get('employee_outstation_travels');
            if ($employeeTravelsQuery->num_rows()) {
                $data['employeeTravelsInfo'] = $employeeTravelsQuery->row();
                $data['from'] = $from;
                $data['to'] = $to;
                $data['employeeId'] = $this->input->get('employeeId');
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['seniorEmployees'] = $this->db->select('Id, Name')->where($employeesWhere)->where('RoleId != ', 1)->where('WarehouseSalesman IS NOT NULL')->get('employees')->result();
                $data['page'] = "reports/employeeOutstationTravelsEdit";
                return $this->load->view('inventory_sep/inventory', $data);                    
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getEmployeeOutstationTravelsData()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                $from = $this->input->get('from');
                $to = $this->input->get('to');
                
                $TravelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $TravelsDataWhere['compBranchId'] = $compBranchId;
                }
                if ($this->input->get('employeeId') != 'All') {
                    $TravelsDataWhere['employeeId'] = $this->input->get('employeeId');
                }

                $employeeTravelsDataQuery = $this->db->where($TravelsDataWhere)->get('employee_outstation_travels_data');
                if ($employeeTravelsDataQuery->num_rows()) {
                    echo json_encode(array('status' => true, "data" => $employeeTravelsDataQuery->result()));
                } else {
                    echo json_encode(array('status' => false, "data" => "No Product found."));
                }
            }
        }
        
        public function deleteEmployeeOutstationTravelsData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $travelDataWhere = ['id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $travelDataWhere['compBranchId'] = $compBranchId;
                    }
                                       
                    $employeeTravelsData = $this->db->select('employeeTravelId, totalAmount')->where($travelDataWhere)->get('employee_outstation_travels_data')->row();
                    $where['id'] = $employeeTravelsData->employeeTravelId;
                    $currentGrandTotal = $this->db->select('grandTotal')->where($where)->get('employee_outstation_travels')->row('grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeTravelsData->totalAmount;
                    $this->db->where($where)->update('employee_outstation_travels', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($travelDataWhere)->update('employee_outstation_travels_data', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }        
        
        public function updateEmployeeOutstationTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravalData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['from'] == '' || $row['to'] == '') {
                        $arrayTravalData[] = $row;
                    }
                }

                if (!empty($arrayTravalData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $from = $this->input->post('from');
                    $to = $this->input->post('to');
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('users', ['id' => $userId], 'name');
                    
                    $updateWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $employeeTravelDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $employeeTravelDataWhere['compBranchId'] = $compBranchId;
                    }
                    if ($this->input->post('employeeId') != 'All') {
                        $updateWhere['employeeId'] = $this->input->post('employeeId');
                        $employeeTravelDataWhere['employeeId'] = $this->input->post('employeeId');
                    }
                    
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->user_model->updateAry('employee_outstation_travels', $employeeTravels, $updateWhere);                    
                    
                    foreach ($this->input->post('travels') as $key => $party) {
                        if (!empty($party['travelDate']) && !empty($party['totalAmount'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'travelDate' => $party['travelDate'],
                                'from' => $party['from'],
                                'to' => $party['to'],
                                'bus' => $party['bus'],
                                'startKM' => $party['startKM'],
                                'endKM' => $party['endKM'],
                                'totalKM' => $party['totalKM'],
                                'amount' => $party['amount'],
                                'modelType' => $party['modelType'],
                                'railway' => $party['railway'],
                                'auto' => $party['auto'],
                                'hotel' => $party['hotel'],
                                'food' => $party['food'],
                                'internetFax' => $party['internetFax'],
                                'xeroxPrint' => $party['xeroxPrint'],
                                'postCourier' => $party['postCourier'],
                                'totalAmount' => $party['totalAmount'],
                                'remark' => $party['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            $employeeTravelDataWhere['id'] = $party['rowId'];
                            $this->user_model->updateAry('employee_outstation_travels_data', $employeeTravelsData, $employeeTravelDataWhere);                            
                        }
                    }
                    
                    foreach ($this->input->post('newTravels') as $key => $newTravelData) {
                        if (!empty($newTravelData['travelDate']) && !empty($newTravelData['from']) && !empty($newTravelData['to'])) {
                            $employeeNewTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $this->db->select('id')->where(['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $newTravelData['employeeId']])->order_by('id', 'DESC')->get('employee_outstation_travels')->row('id'),
                                'travelDate' => $newTravelData['travelDate'],
                                'from' => $newTravelData['from'],
                                'to' => $newTravelData['to'],
                                'bus' => $newTravelData['bus'],
                                'startKM' => $newTravelData['startKM'],
                                'endKM' => $newTravelData['endKM'],
                                'totalKM' => $newTravelData['totalKM'],
                                'amount' => $newTravelData['amount'],
                                'modelType' => $newTravelData['modelType'],
                                'railway' => $newTravelData['railway'],
                                'auto' => $newTravelData['auto'],
                                'hotel' => $newTravelData['hotel'],
                                'food' => $newTravelData['food'],
                                'internetFax' => $newTravelData['internetFax'],
                                'xeroxPrint' => $newTravelData['xeroxPrint'],
                                'postCourier' => $newTravelData['postCourier'],
                                'totalAmount' => $newTravelData['totalAmount'],
                                'remark' => $newTravelData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $newTravelData['seniorEmployeeId'],
                                'employeeId' => $newTravelData['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_outstation_travels_data', $employeeNewTravelsData);                            
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeOutstationTravelsDelete()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $this->input->get('from'), 'date <= ' => $this->input->get('to'), 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $where['employeeId'] = $this->input->get('employeeId');
            }            
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $compBranchId;
            }
            
            if ($this->db->select('id')->where($where)->get('employee_outstation_travels')->num_rows()) {
                if ($this->user_model->updateAry('employee_outstation_travels', ['isDeleted' => 0], $where)) {
                    $this->user_model->updateAry('employee_outstation_travels_data', ['isDeleted' => 0], $where);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeOutstationTravelsReportDownload()
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);
            
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            $employeeId = $this->input->get('employeeId');            
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $travelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $where['employeeId'] = $employeeId;
                $travelsDataWhere['employeeId'] = $employeeId;
            }            
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $travelsDataWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $TravelsReportQuery = $this->db->where($where)->group_by('date')->get('employee_outstation_travels');
            if ($TravelsReportQuery->num_rows()) {
                $travelData = $TravelsReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                                
                // Define styles
                $topHeaderStyleArray = [
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $headerStyleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $dataStyleArray = [
                    'font' => [
                        'size' => 10,
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];                
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
                                
                // Add the logo image
                $PHPExcelDrawing = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing->setName('Logo');
                $PHPExcelDrawing->setDescription('Logo');
                $PHPExcelDrawing->setPath($logoPath);
                $PHPExcelDrawing->setHeight(60);
                $PHPExcelDrawing->setCoordinates('T1');
                $PHPExcelDrawing->setWorksheet($PHPExcel->getActiveSheet());                
                $PHPExcel->getActiveSheet()->getStyle('T1:T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Format effective date: ' . date('jS F, Y'))
                    ->mergeCells('A1:T2')
                    ->setCellValue('A3', 'OUTSTATION TRAVEL')                    
                    ->mergeCells('A3:T3')                   
                    ->setCellValue('A4', 'Band')
                    ->mergeCells('A4:D4')
                    ->setCellValue('E4', $travelData->band)
                    ->mergeCells('E4:I4')
                    ->setCellValue('A5', 'Location / Place of posting')
                    ->mergeCells('A5:D5')
                    ->setCellValue('E5', $travelData->location . " - " . get_title('state_list', ['id' => $travelData->stateId], 'state'))
                    ->mergeCells('E5:I5')
                    ->setCellValue('A6', 'Head Quarter')
                    ->mergeCells('A6:D6')
                    ->setCellValue('E6', $travelData->headQuarter)
                    ->mergeCells('E6:I6')                        
                    ->setCellValue('J4', 'Month')
                    ->mergeCells('J4:L4')
                    ->setCellValue('M4', $travelData->month)
                    ->mergeCells('M4:T4')
                    ->setCellValue('J5', 'Department')
                    ->mergeCells('J5:L5')
                    ->setCellValue('M5', $travelData->department)
                    ->mergeCells('M5:T5')
                    ->setCellValue('J6', '')
                    ->mergeCells('J6:L6')
                    ->setCellValue('M6', '')
                    ->mergeCells('M6:T6');

                $PHPExcel->getActiveSheet()->getStyle('E4:I6')->applyFromArray($dataStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('M4:T6')->applyFromArray($dataStyleArray);                
                
                // Set column titles
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', 'DATE')
                    ->mergeCells('A7:A9')
                    ->setCellValue('B7', 'SENIOR EMPLOYEE')
                    ->mergeCells('B7:B9')
                    ->setCellValue('C7', 'SALESMAN')
                    ->mergeCells('C7:C9')
                    ->setCellValue('D7', 'PLACES VISITED')
                    ->mergeCells('D7:E7')
                    ->setCellValue('D8', 'From')
                    ->mergeCells('D8:D9')
                    ->setCellValue('E8', 'To')
                    ->mergeCells('E8:E9')
                    ->setCellValue('F7', 'MODE OF TRAVEL')
                    ->mergeCells('F7:M7')
                    ->setCellValue('F8', 'Bus')
                    ->mergeCells('F8:F9')
                    ->setCellValue('G8', 'Private Own Vehicle')
                    ->mergeCells('G8:K8')
                    ->setCellValue('G9', 'Start KM (A)')
                    ->setCellValue('H9', 'End KM (B)')
                    ->setCellValue('I9', 'Total KM (B - A)')
                    ->setCellValue('J9', 'Amount')
                    ->setCellValue('K9', 'Car/Bike Model Type')                   
                    ->setCellValue('L8', 'Railway')
                    ->mergeCells('L8:L9')
                    ->setCellValue('M8', 'Auto')
                    ->mergeCells('M8:M9')
                    ->setCellValue('N7', 'OUTSTATION')
                    ->mergeCells('N7:O7')
                    ->setCellValue('N8', 'Hotel Accommodation')
                    ->mergeCells('N8:N9')
                    ->setCellValue('O8', 'Food')
                    ->mergeCells('O8:O9')
                    ->setCellValue('P7', 'OTHER MISC. EXPENSES')
                    ->mergeCells('P7:R7')
                    ->setCellValue('P8', 'Internet/Fax')
                    ->mergeCells('P8:P9')
                    ->setCellValue('Q8', 'Xerox/Print')
                    ->mergeCells('Q8:Q9')
                    ->setCellValue('R8', 'Post/Courier')
                    ->mergeCells('R8:R9')
                    ->setCellValue('S7', 'TOTAL AMOUNT')
                    ->mergeCells('S7:S9')
                    ->setCellValue('T7', 'REMARK')
                    ->mergeCells('T7:T9');
                                              
                $PHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(30);
                $PHPExcel->getActiveSheet()->getStyle('A7:T9')->getAlignment()->setWrapText(true);
                
                $TravelDataReportQuery = $this->db->where($travelsDataWhere)->get('employee_outstation_travels_data');
                if ($TravelDataReportQuery->num_rows()) {
                    $rowCount = 10;
                    $totalBus = 0;
                    $totalStartKM = 0;
                    $totalEndKM = 0;
                    $totalTotalKM = 0;
                    $totalAmount = 0;
                    $totalRailway = 0;
                    $totalAuto = 0;
                    $totalHotel = 0;
                    $totalFood = 0;
                    $totalInternetFax = 0;
                    $totalXeroxPrint = 0;
                    $totalPostCourier = 0;
                    $grandTotalAmount = 0;
                    foreach ($TravelDataReportQuery->result() as $key => $travelsData) {
                        $totalBus += $travelsData->bus;
                        $totalStartKM += $travelsData->startKM;
                        $totalEndKM += $travelsData->endKM;
                        $totalTotalKM += $travelsData->totalKM;
                        $totalAmount += $travelsData->amount;
                        $totalRailway += $travelsData->railway;
                        $totalAuto += $travelsData->auto;
                        $totalHotel += $travelsData->hotel;
                        $totalFood += $travelsData->food;
                        $totalInternetFax += $travelsData->internetFax;
                        $totalXeroxPrint += $travelsData->xeroxPrint;
                        $totalPostCourier += $travelsData->postCourier;
                        $grandTotalAmount += $travelsData->totalAmount;
                        
                        $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':T' . $rowCount)->applyFromArray($dataStyleArray);
                        
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d/m/Y', strtotime($travelsData->travelDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, get_title('employees', ['Id' => $travelsData->seniorEmployeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, get_title('employees', ['Id' => $travelsData->employeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $travelsData->from);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $travelsData->to);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $travelsData->bus);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $travelsData->startKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $travelsData->endKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $travelsData->totalKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $travelsData->amount);
                        $PHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $travelsData->modelType);
                        $PHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $travelsData->railway);
                        $PHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $travelsData->auto);
                        $PHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $travelsData->hotel);
                        $PHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $travelsData->food);
                        $PHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $travelsData->internetFax);
                        $PHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $travelsData->xeroxPrint);
                        $PHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $travelsData->postCourier);
                        $PHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $travelsData->totalAmount);
                        $PHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $travelsData->remark);
                        $rowCount++;
                    }
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':T' . $rowCount)->applyFromArray($dataStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':E' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':E' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $totalBus);
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $totalStartKM);
                    $PHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $totalEndKM);
                    $PHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $totalTotalKM);
                    $PHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $totalAmount);
                    $PHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, '');
                    $PHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $totalRailway);
                    $PHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $totalAuto);
                    $PHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $totalHotel);
                    $PHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $totalFood);
                    $PHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $totalInternetFax);
                    $PHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $totalXeroxPrint);
                    $PHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $totalPostCourier);
                    $PHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $grandTotalAmount);
                    
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':T' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':T' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':T' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':C' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('G' . $rowCount . ':H' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('L' . $rowCount . ':M' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('Q' . $rowCount . ':R' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('Q' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':T' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    
                    $rowCount++;
                    $previousRowNumber = ($rowCount - 1);
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Employee');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':C' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, 'Reporting Senior');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('G' . $rowCount . ':H' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, 'Accounts Head');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('L' . $rowCount . ':M' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, 'Director');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('Q' . $rowCount . ':R' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('Q' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':T' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $previousRowNumber . ':F' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('I' . $previousRowNumber . ':K' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('N' . $previousRowNumber . ':P' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('S' . $previousRowNumber . ':T' . $rowCount);
                }                
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
                
                // Apply styles
                $PHPExcel->getActiveSheet()->getStyle('A1:T2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A4:D6')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('J4:L6')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A7:T9')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A3:T3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A3:T3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A1:T1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('G8:K8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A7:T9')->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                // Set background colors
                $PHPExcel->getActiveSheet()->getStyle('A4:D6')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);                
                $PHPExcel->getActiveSheet()->getStyle('J4:L6')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A7:T9')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);

                $PHPExcel->getActiveSheet()->setTitle('OUTSTATION_TRAVEL');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_OUTSTATION_TRAVEL_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function saleInvoicesProductsReport()
        {
                ini_set('memory_limit', '-1');
                
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                if ($this->input->get('from') && $this->input->get('to')) {
                        $from = $this->input->get('from');
                        $to = $this->input->get('to');
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }
                $partyId = function_exists('input_get_array') ? input_get_array('partyId') : (array) ($this->input->get('partyId') ?: []);
                $salesmanId = function_exists('input_get_array') ? input_get_array('salesmanId') : (array) ($this->input->get('salesmanId') ?: []);
                $productId = function_exists('input_get_array') ? input_get_array('productId') : (array) ($this->input->get('productId') ?: []);
                $categoryId = function_exists('input_get_array') ? input_get_array('categoryId') : (array) ($this->input->get('categoryId') ?: []);
                $brandId = function_exists('input_get_array') ? input_get_array('brandId') : (array) ($this->input->get('brandId') ?: []);
                if (in_array('All', $partyId, true) || empty($partyId)) {
                    $partyId = [];
                }
                if (in_array('All', $salesmanId, true) || empty($salesmanId)) {
                    $salesmanId = [];
                }
                if (in_array('All', $productId, true) || empty($productId)) {
                    $productId = [];
                }
                if (in_array('All', $categoryId, true) || empty($categoryId)) {
                    $categoryId = [];
                }
                if (in_array('All', $brandId, true) || empty($brandId)) {
                    $brandId = [];
                }
                
                $salemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                $salesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $salemanRoleId, 'UserId' => $userId, 'CompId' => $compId];
                $commonWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
                $invoiceProductWhere = ['invoice_product.invoiceDate >= ' => $from, 'invoice_product.invoiceDate <= ' => $to, 'invoice_product.status' => 1, 'invoice_product.type' => 'Sale', 'invoice_product.user_id' => $userId, 'invoice_product.comp_id' => $compId, 'invoice_product.financial_year' => $financialYear];
                $commonWhere2 = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $commonWhere['CompanyBranchId'] = $compBranchId;
                        $commonWhere2['compBranchId'] = $compBranchId;
                        $salesmanWhere['CompanyBranchId'] = $compBranchId;
                        $invoiceProductWhere['invoice_product.CompanyBranchId'] = $compBranchId;
                }

                if ($this->input->get('estimate') == "") {
                        $this->db->select('invoice_product.InvoiceNo, invoice_product.invoiceID, invoice_product.invoiceDate, invoice_product.product_id, invoice_product.qty, invoice_product.rate, invoice_product.price, invoice_product.taxAmount, invoice_product.netAmount, invoice_product.hsn, invoice_product.tax');
                        $this->db->join('sales', 'sales.id = invoice_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($invoiceProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('sales.customerId', $partyId);
                        }
                        if (!empty($salesmanId)) {
                            $this->db->where_in('sales.SalesmanId', $salesmanId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $this->db->order_by('invoice_product.id', 'DESC');
                        $saleInvoiceQuery = $this->db->get('invoice_product');
                        if ($saleInvoiceQuery->num_rows()) {
                                $data['saleInvoicesProducts'] = $saleInvoiceQuery->result();
                        }
                } elseif ($this->input->get('estimate') == true) {
                        $this->db->select('invoice_product.InvoiceNo, invoice_product.invoiceID, invoice_product.invoiceDate, invoice_product.product_id, invoice_product.qty, invoice_product.rate, invoice_product.price, invoice_product.taxAmount, invoice_product.netAmount, invoice_product.hsn, invoice_product.tax, sales.customerId, sales.type');
                        $this->db->join('sales', 'sales.id = invoice_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($invoiceProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('sales.customerId', $partyId);
                        }
                        if (!empty($salesmanId)) {
                            $this->db->where_in('sales.SalesmanId', $salesmanId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $invoiceProductSQL = $this->db->get_compiled_select('invoice_product');
                        
                        $this->db->reset_query();
                        
                        // add estimate
                        $estimateProductWhere = ['estimate_product.invoiceDate >= ' => $from, 'estimate_product.invoiceDate <= ' => $to, 'estimate_product.status' => 1, 'estimate_product.type' => 'Sale', 'estimate_product.user_id' => $userId, 'estimate_product.comp_id' => $compId, 'estimate_product.financial_year' => $financialYear];
                        if ($this->session->userdata('BranchRole')) {
                                $estimateProductWhere['estimate_product.CompanyBranchId'] = $compBranchId;
                        }                        
                        $this->db->select('estimate_product.InvoiceNo, estimate_product.invoiceID, estimate_product.invoiceDate, estimate_product.product_id, estimate_product.qty, estimate_product.rate, estimate_product.price, estimate_product.taxAmount, estimate_product.netAmount, estimate_product.hsn, estimate_product.tax, estimates.customerId, estimates.type');
                        $this->db->join('estimates', 'estimates.id = estimate_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = estimate_product.product_id', 'LEFT');
                        $this->db->where($estimateProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('estimates.customerId', $partyId);
                        }
                        if (!empty($salesmanId)) {
                            $this->db->where_in('estimates.SalesmanId', $salesmanId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('estimate_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $estimateProductSQL = $this->db->get_compiled_select('estimate_product');
                        
                        $finalQuery = $this->db->query("($invoiceProductSQL) UNION ALL ($estimateProductSQL) ORDER BY invoiceDate DESC");
                        if ($finalQuery->num_rows()) {
                                $data['saleWithEstimateInvoicesProducts'] = $finalQuery->result();
                        }
                }
                
                $data['parties'] = $this->db->select('id, name')->where($commonWhere)->order_by('name', 'ASC')->get('parties')->result();
                $data['products'] = $this->db->select('id, p_name')->where($commonWhere)->order_by('p_name', 'ASC')->get('product')->result();
                $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->order_by('Name', 'ASC')->get('employees')->result();
                $data['categories'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('category')->result();
                $data['brands'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('brand')->result();
                $data['page'] = "reports/saleInvoicesProductsReport";
                return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function getEmployeeName()
        {
            if ($this->input->is_ajax_request()) {
                if ($this->input->post('employeeId')) {
                    echo get_title('employees', ['Id' => $this->input->post('employeeId')], 'Name');
                } else {
                    echo "";
                }
            }
        }
        
        public function getSalesmanEmployees()
        {
            if ($this->input->is_ajax_request()) {
                if ($this->input->post('seniorEmployeeId')) {
                    $employeeId = $this->input->post('seniorEmployeeId');
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1, 'Id' => $employeeId];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                    }
                    
                    $salesmanIds = json_decode(get_title('employees', $where, 'WarehouseSalesman'), true);
                    foreach ($salesmanIds as $key => $salesman) {
                        echo "<option value='" . $salesman . "'>" . get_title('employees', ['Id' => $salesman], 'Name') . "</option>";
                    }
                }
            }
        }
        
        public function purchaseInvoicesProductsReport()
        {
                ini_set('memory_limit', '-1');
                
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                if ($this->input->get('from') && $this->input->get('to')) {
                        $from = $this->input->get('from');
                        $to = $this->input->get('to');
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }
                $partyId = function_exists('input_get_array') ? input_get_array('partyId') : (array) ($this->input->get('partyId') ?: []);
                $productId = function_exists('input_get_array') ? input_get_array('productId') : (array) ($this->input->get('productId') ?: []);
                $categoryId = function_exists('input_get_array') ? input_get_array('categoryId') : (array) ($this->input->get('categoryId') ?: []);
                $brandId = function_exists('input_get_array') ? input_get_array('brandId') : (array) ($this->input->get('brandId') ?: []);
                if (in_array('All', $partyId, true) || empty($partyId)) {
                    $partyId = [];
                }
                if (in_array('All', $productId, true) || empty($productId)) {
                    $productId = [];
                }
                if (in_array('All', $categoryId, true) || empty($categoryId)) {
                    $categoryId = [];
                }
                if (in_array('All', $brandId, true) || empty($brandId)) {
                    $brandId = [];
                }
            
                $commonWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
                $invoiceProductWhere = ['invoice_product.invoiceDate >= ' => $from, 'invoice_product.invoiceDate <= ' => $to, 'invoice_product.status' => 1, 'invoice_product.type' => 'Purchase', 'invoice_product.user_id' => $userId, 'invoice_product.comp_id' => $compId, 'invoice_product.financial_year' => $financialYear];
                $commonWhere2 = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $commonWhere['CompanyBranchId'] = $compBranchId;
                        $commonWhere2['compBranchId'] = $compBranchId;
                        $invoiceProductWhere['invoice_product.CompanyBranchId'] = $compBranchId;
                }
                
                if ($this->input->get('estimate') == "") {
                        $this->db->select('invoice_product.InvoiceNo, invoice_product.invoiceID, invoice_product.invoiceDate, invoice_product.product_id, invoice_product.qty, invoice_product.rate, invoice_product.price, invoice_product.taxAmount, invoice_product.netAmount, invoice_product.hsn, invoice_product.tax');
                        $this->db->join('purchases', 'purchases.id = invoice_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($invoiceProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('purchases.customerId', $partyId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $this->db->order_by('invoice_product.id', 'DESC');
                        $purchaseInvoiceQuery = $this->db->get('invoice_product');
                        if ($purchaseInvoiceQuery->num_rows()) {
                                $data['purchaseInvoicesProducts'] = $purchaseInvoiceQuery->result();
                        }
                } elseif ($this->input->get('estimate') == true) {
                        $this->db->select('invoice_product.InvoiceNo, invoice_product.invoiceID, invoice_product.invoiceDate, invoice_product.product_id, invoice_product.qty, invoice_product.rate, invoice_product.price, invoice_product.taxAmount, invoice_product.netAmount, invoice_product.hsn, invoice_product.tax, purchases.customerId, purchases.type');
                        $this->db->join('purchases', 'purchases.id = invoice_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($invoiceProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('purchases.customerId', $partyId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $invoiceProductSQL = $this->db->get_compiled_select('invoice_product');

                        $this->db->reset_query();

                        // add estimate
                        $estimateProductWhere = ['estimate_product.invoiceDate >= ' => $from, 'estimate_product.invoiceDate <= ' => $to, 'estimate_product.status' => 1, 'estimate_product.type' => 'Purchase', 'estimate_product.user_id' => $userId, 'estimate_product.comp_id' => $compId, 'estimate_product.financial_year' => $financialYear];
                        if ($this->session->userdata('BranchRole')) {
                                $estimateProductWhere['estimate_product.CompanyBranchId'] = $compBranchId;
                        }
                        $this->db->select('estimate_product.InvoiceNo, estimate_product.invoiceID, estimate_product.invoiceDate, estimate_product.product_id, estimate_product.qty, estimate_product.rate, estimate_product.price, estimate_product.taxAmount, estimate_product.netAmount, estimate_product.hsn, estimate_product.tax, estimate_purchase.customerId, estimate_purchase.type');
                        $this->db->join('estimate_purchase', 'estimate_purchase.id = estimate_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = estimate_product.product_id', 'LEFT');
                        $this->db->where($estimateProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('estimate_purchase.customerId', $partyId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('estimate_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $estimateProductSQL = $this->db->get_compiled_select('estimate_product');

                        $finalQuery = $this->db->query("($invoiceProductSQL) UNION ALL ($estimateProductSQL) ORDER BY invoiceDate DESC");
                        if ($finalQuery->num_rows()) {
                                $data['purchaseWithEstimatePurchaseInvoicesProducts'] = $finalQuery->result();
                        }
                }
                $data['parties'] = $this->db->select('id, name')->where($commonWhere)->order_by('name', 'ASC')->get('parties')->result();
                $data['products'] = $this->db->select('id, p_name')->where($commonWhere)->order_by('p_name', 'ASC')->get('product')->result();
                $data['categories'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('category')->result();
                $data['brands'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('brand')->result();
                $data['page'] = "reports/purchaseInvoicesProductsReport";
                return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function purchaseVsSaleProductsReport()
        {
                ini_set('memory_limit', '-1');
                
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $productId = function_exists('input_get_array') ? input_get_array('productId') : (array) ($this->input->get('productId') ?: []);
                $categoryId = function_exists('input_get_array') ? input_get_array('categoryId') : (array) ($this->input->get('categoryId') ?: []);
                $brandId = function_exists('input_get_array') ? input_get_array('brandId') : (array) ($this->input->get('brandId') ?: []);
                if (in_array('All', $productId, true) || empty($productId)) {
                    $productId = [];
                }
                if (in_array('All', $categoryId, true) || empty($categoryId)) {
                    $categoryId = [];
                }
                if (in_array('All', $brandId, true) || empty($brandId)) {
                    $brandId = [];
                }
            
                $commonWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
                $commonWhere2 = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                $saleVsPurchasewhere = ['invoice_product.status' => 1, 'invoice_product.user_id' => $userId, 'invoice_product.comp_id' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $commonWhere['CompanyBranchId'] = $compBranchId;
                        $commonWhere2['compBranchId'] = $compBranchId;
                        $saleVsPurchasewhere['invoice_product.CompanyBranchId'] = $compBranchId;
                }
                
                if ($this->input->get('estimate') == "") {
                        $this->db->select('invoice_product.product_id, invoice_product.type, invoice_product.qty, invoice_product.rate, product.p_name');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($saleVsPurchasewhere);
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $this->db->order_by('invoice_product.id', 'DESC');
                        $purchaseVsSaleInvoiceQuery = $this->db->get('invoice_product');
                        if ($purchaseVsSaleInvoiceQuery->num_rows()) {
                                $data['purchaseVsSaleInvoicesProducts'] = $purchaseVsSaleInvoiceQuery->result();
                        }
                } elseif ($this->input->get('estimate') == true) {
                        $this->db->select('invoice_product.id AS rowId, invoice_product.product_id, invoice_product.type, invoice_product.qty, invoice_product.rate, product.p_name');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($saleVsPurchasewhere);
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $saleAndPurchaseInvoiceProductSQL = $this->db->get_compiled_select('invoice_product');
                        
                        $this->db->reset_query();
                        
                        // add estimate
                        $estimateProductWhere = ['estimate_product.status' => 1, 'estimate_product.user_id' => $userId, 'estimate_product.comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $estimateProductWhere['estimate_product.CompanyBranchId'] = $compBranchId;
                        }                        
                        
                        $this->db->select('estimate_product.id AS rowId, estimate_product.product_id, estimate_product.type, estimate_product.qty, estimate_product.rate, product.p_name');
                        $this->db->join('product', 'product.id = estimate_product.product_id', 'LEFT');
                        $this->db->where($estimateProductWhere);
                        if (!empty($productId)) {
                            $this->db->where_in('estimate_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $saleAndPurchaseEstimateProductSQL = $this->db->get_compiled_select('estimate_product');

                        $finalQuery = $this->db->query("($saleAndPurchaseInvoiceProductSQL) UNION ALL ($saleAndPurchaseEstimateProductSQL) ORDER BY rowId DESC");
                        if ($finalQuery->num_rows()) {
                                $data['purchaseVsSaleWithEstimateInvoicesProducts'] = $finalQuery->result();
                        }                        
                }
        
                $data['products'] = $this->db->select('id, p_name')->where($commonWhere)->order_by('p_name', 'ASC')->get('product')->result();
                $data['categories'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('category')->result();
                $data['brands'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('brand')->result();
                $data['page'] = "reports/purchaseVsSaleProductsReport";
                return $this->load->view('inventory_sep/inventory', $data);
        }        
        
        public function getProductSaleAndPurchaseInfo() 
        {
                ini_set('memory_limit', '-1');
                
                $estimate = $this->input->get('estimate');
                $productId = $this->input->get('productId');                
                if (isset($productId)) {                        
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        
                        $where = ['product_id' => $productId, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $compBranchId;
                        }
                        
                        if ($estimate == "") {
                                $productSaleAndPurchaseQuery = $this->db->select('product_id, invoiceNo, invoiceDate, type, qty, rate, netAmount')->where($where)->order_by('id', 'DESC')->get('invoice_product');
                                if ($productSaleAndPurchaseQuery->num_rows()) {
                                        $data['productSaleAndPurchaseData'] = $productSaleAndPurchaseQuery->result();
                                }
                        } elseif ($estimate == true) {
                                $this->db->select('id, product_id, invoiceNo, invoiceDate, type, qty, rate, netAmount');
                                $this->db->where($where);
                                $saleAndPurchaseInvoiceProductSQL = $this->db->get_compiled_select('invoice_product');
                                
                                $this->db->reset_query();
                                
                                $this->db->select('id, product_id, invoiceNo, invoiceDate, type, qty, rate, netAmount');
                                $this->db->where($where);
                                $saleAndPurchaseEstimateInvoiceProductSQL = $this->db->get_compiled_select('estimate_product');
                                
                                $finalQuery = $this->db->query("($saleAndPurchaseInvoiceProductSQL) UNION ALL ($saleAndPurchaseEstimateInvoiceProductSQL) ORDER BY id DESC");
                                if ($finalQuery->num_rows()) {
                                        $data['productSaleAndPurchaseData'] = $finalQuery->result();
                                }
                        }
                        
                        $data['productName'] = get_title('product', ['id' => $productId], 'p_name');
                        return $this->load->view('inventory_sep/reports/getProductSaleAndPurchaseInfo', $data);
                }
        }
        
        public function partyPurchaseReport()
        {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $companyBranchId = $this->session->userdata('CompBranchId');

                $where = ['user_id' => $userId, 'comp_id' => $compId];
                $partyWhere = ['user_id' => $userId, 'comp_id' => $compId];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $companyBranchId;
                    $partyWhere['CompanyBranchId'] = $companyBranchId;
                }

                if ($this->input->get('partyId') != "All" && $this->input->get('partyId') != "") {
                        $where['id'] = $this->input->get('partyId');
                }

                $accountGroupId = get_title('accounting_group', ['name' => 'Sundry Creditors', 'type' => 'DR', 'status' => 1], 'id');

                $data['partiesPurchases'] = $this->db->select('id, name, contact, gst')->where($where)->where('group_head', $accountGroupId)->get('parties')->result();
                $data['parties'] = $this->db->select('id, name, area, contact')->where($partyWhere)->where('group_head', $accountGroupId)->get('parties')->result();
                $data['page'] = 'reports/partyPurchaseReport';
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
                        
                        $partyPurchaseInvoiceQuery = $this->db->select('id, invoiceDate, invoiceNumber, type, total, paid_amount')->from('purchases')->where($where)->get_compiled_select();
                        $partyEstimatePurchaseInvoiceQuery = $this->db->select('id, invoiceDate, invoiceNumber, type, total, paid_amount')->from('estimate_purchase')->where($where)->get_compiled_select();

                        $data['partyPurchaseAndEstimatePurchaseInvoices'] = $this->db->query($partyPurchaseInvoiceQuery . " UNION " . $partyEstimatePurchaseInvoiceQuery . " ORDER BY id DESC")->result();
                        $data['partyName'] = get_title('parties', ['id' => $partyId], 'name');
                        return $this->load->view('inventory_sep/reports/partyInvoiceInfo', $data);                        
                }
        }
        
        public function partiesWiseProductsSalesReport()
        {
                ini_set('memory_limit', '-1');
                
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                if ($this->input->get('from') && $this->input->get('to')) {
                        $from = $this->input->get('from');
                        $to = $this->input->get('to');
                } else {
                        $from = date('Y-m-01');
                        $to = date('Y-m-d');
                }
                $partyId = function_exists('input_get_array') ? input_get_array('partyId') : (array) ($this->input->get('partyId') ?: []);
                $salesmanId = function_exists('input_get_array') ? input_get_array('salesmanId') : (array) ($this->input->get('salesmanId') ?: []);
                $productId = function_exists('input_get_array') ? input_get_array('productId') : (array) ($this->input->get('productId') ?: []);
                $categoryId = function_exists('input_get_array') ? input_get_array('categoryId') : (array) ($this->input->get('categoryId') ?: []);
                $brandId = function_exists('input_get_array') ? input_get_array('brandId') : (array) ($this->input->get('brandId') ?: []);
                if (in_array('All', $partyId, true) || empty($partyId)) {
                    $partyId = [];
                }
                if (in_array('All', $salesmanId, true) || empty($salesmanId)) {
                    $salesmanId = [];
                }
                if (in_array('All', $productId, true) || empty($productId)) {
                    $productId = [];
                }
                if (in_array('All', $categoryId, true) || empty($categoryId)) {
                    $categoryId = [];
                }
                if (in_array('All', $brandId, true) || empty($brandId)) {
                    $brandId = [];
                }
                
                $salemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                $salesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $salemanRoleId, 'UserId' => $userId, 'CompId' => $compId];
                $commonWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
                $invoiceProductWhere = ['invoice_product.invoiceDate >= ' => $from, 'invoice_product.invoiceDate <= ' => $to, 'invoice_product.status' => 1, 'invoice_product.type' => 'Sale', 'invoice_product.user_id' => $userId, 'invoice_product.comp_id' => $compId, 'invoice_product.financial_year' => $financialYear];
                $commonWhere2 = ['isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                if ($this->session->userdata('BranchRole')) {
                        $commonWhere['CompanyBranchId'] = $compBranchId;
                        $commonWhere2['compBranchId'] = $compBranchId;
                        $salesmanWhere['CompanyBranchId'] = $compBranchId;
                        $invoiceProductWhere['invoice_product.CompanyBranchId'] = $compBranchId;
                }

                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                if ($this->input->get('estimate') == "") {
                        $this->db->select('sales.customerId, SUM(invoice_product.price) AS taxableAmount, SUM(invoice_product.taxAmount) AS taxAmount, SUM(invoice_product.netAmount) AS netAmount, SUM(invoice_product.qty) AS saleQty, invoice_product.hsn, invoice_product.tax, product.p_name');
                        $this->db->join('sales', 'sales.id = invoice_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($invoiceProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('sales.customerId', $partyId);
                        }
                        if (!empty($salesmanId)) {
                            $this->db->where_in('sales.SalesmanId', $salesmanId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $this->db->group_by('invoice_product.product_id');
                        $this->db->order_by('SUM(invoice_product.qty)', 'DESC');
                        $saleInvoiceQuery = $this->db->get('invoice_product');
                        if ($saleInvoiceQuery->num_rows()) {
                                $data['saleInvoicesProducts'] = $saleInvoiceQuery->result();
                        }
                } elseif ($this->input->get('estimate') == true) {
                        $this->db->select('sales.customerId, SUM(invoice_product.price) AS taxableAmount, SUM(invoice_product.taxAmount) AS taxAmount, SUM(invoice_product.netAmount) AS netAmount, SUM(invoice_product.qty) AS saleQty, invoice_product.hsn, invoice_product.tax, product.p_name, sales.type');
                        $this->db->join('sales', 'sales.id = invoice_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
                        $this->db->where($invoiceProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('sales.customerId', $partyId);
                        }
                        if (!empty($salesmanId)) {
                            $this->db->where_in('sales.SalesmanId', $salesmanId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('invoice_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $this->db->group_by('invoice_product.product_id');
                        $invoiceProductSQL = $this->db->get_compiled_select('invoice_product');
                        
                        $this->db->reset_query();
                        
                        // add estimate
                        $estimateProductWhere = ['estimate_product.invoiceDate >= ' => $from, 'estimate_product.invoiceDate <= ' => $to, 'estimate_product.status' => 1, 'estimate_product.type' => 'Sale', 'estimate_product.user_id' => $userId, 'estimate_product.comp_id' => $compId, 'estimate_product.financial_year' => $financialYear];
                        if ($this->session->userdata('BranchRole')) {
                                $estimateProductWhere['estimate_product.CompanyBranchId'] = $compBranchId;
                        }
                        $this->db->select('estimates.customerId, SUM(estimate_product.price) AS taxableAmount, SUM(estimate_product.taxAmount) AS taxAmount, SUM(estimate_product.netAmount) AS netAmount, SUM(estimate_product.qty) AS saleQty, estimate_product.hsn, estimate_product.tax, product.p_name, estimates.type');
                        $this->db->join('estimates', 'estimates.id = estimate_product.invoiceID', 'LEFT');
                        $this->db->join('product', 'product.id = estimate_product.product_id', 'LEFT');
                        $this->db->where($estimateProductWhere);
                        if (!empty($partyId)) {
                            $this->db->where_in('estimates.customerId', $partyId);
                        }
                        if (!empty($salesmanId)) {
                            $this->db->where_in('estimates.SalesmanId', $salesmanId);
                        }
                        if (!empty($productId)) {
                            $this->db->where_in('estimate_product.product_id', $productId);
                        }
                        if (!empty($categoryId)) {
                            $this->db->where_in('product.categoryId', $categoryId);
                        }
                        if (!empty($brandId)) {
                            $this->db->where_in('product.brandId', $brandId);
                        }
                        $this->db->group_by('estimate_product.product_id');
                        $estimateProductSQL = $this->db->get_compiled_select('estimate_product');
                        
                        $finalQuery = $this->db->query("($invoiceProductSQL) UNION ALL ($estimateProductSQL) ORDER BY saleQty DESC");
                        if ($finalQuery->num_rows()) {
                                $data['saleWithEstimateInvoicesProducts'] = $finalQuery->result();
                        }
                }
                
                $data['parties'] = $this->db->select('id, name')->where($commonWhere)->order_by('name', 'ASC')->get('parties')->result();
                $data['products'] = $this->db->select('id, p_name')->where($commonWhere)->order_by('p_name', 'ASC')->get('product')->result();
                $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->order_by('Name', 'ASC')->get('employees')->result();
                $data['categories'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('category')->result();
                $data['brands'] = $this->db->select('id, name')->where($commonWhere2)->order_by('name', 'ASC')->get('brand')->result();
                $data['page'] = "reports/partiesWiseProductsSalesReport";
                return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function customersReport()
        {
            $compId = $this->session->userdata('comp_id');
            $branchId = $this->input->get('branchId');
            
            $where = ['comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['user_id'] = $this->session->userdata('user_id');
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            } else {
                if ($branchId != '' && $branchId != 'All') {
                    $where['CompanyBranchId'] = $branchId;
                }
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            // First query for GST not empty or NULL
            $this->db->select('id, group_head, name, ContactPerson, contact, email, pan, gst, gstType, state, city, area, address_1, pin, credit_limit_day, opBalnceType, SalesmanId, categoryId, CompanyBranchId');
            $this->db->where($where);
            $this->db->group_start();
            $this->db->where('gst !=', '');
            $this->db->or_where('gst IS NOT NULL');
            $this->db->group_end();
            $this->db->group_by('gst');
            $customersQuery1 = $this->db->get_compiled_select('parties');
            
            // Second query for GST empty or NULL
            $this->db->select('id, group_head, name, ContactPerson, contact, email, pan, gst, gstType, state, city, area, address_1, pin, credit_limit_day, opBalnceType, SalesmanId, categoryId, CompanyBranchId');
            $this->db->where($where);
            $this->db->group_start();
            $this->db->where('gst', '');
            $this->db->or_where('gst IS NULL');
            $this->db->group_end();
            $this->db->group_by(['contact', 'name']);
            $customersQuery2 = $this->db->get_compiled_select('parties');
            
            // Combine the two queries using UNION
            $combinedQuery = "($customersQuery1) UNION ($customersQuery2) ORDER BY name ASC";
            // Execute the combined query
            $query = $this->db->query($combinedQuery);
            // Fetch the results
            $results = $query->result();

            if ($results) {
                $data['customers'] = $results;
            } else {
                $data['customers'] = [];
            }
            $data['branches'] = $this->db->select('Id, BranchName')->where('CompId', $compId)->order_by('BranchName', 'ASC')->get('companybranch')->result();
            $data['page'] = "reports/customersReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function profitAndLossReport()
        {
            $financialYearDates = getFinancialYearDates(get_title('year', ['id' => $this->session->userdata('financial_year')], 'year'));
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = $financialYearDates['fromDate'];
                $to = $financialYearDates['toDate'];
            }
            
            $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $invoiceDateWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to];
            $dateWhere = ['date >=' => $from, 'date <=' => $to];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $directExpensesAccountGroupId = get_title('accounting_group', ['name' => 'Direct Expense'], 'id');
            $indirectExpensesAccountGroupId = get_title('accounting_group', ['name' => 'Indirect Expense'], 'id');
            $indirectIncomesAccountGroupId = get_title('accounting_group', ['name' => 'Indirect Income'], 'id');
            
            $openingStockProductDataQuery = $this->db->select('id, qty, price')->where($where)->where('qty >', 0)->order_by('id', 'DESC')->get('product')->result();
            $openingStockPurchaseRatesQuery = $this->db->select('product_id, rate')->where($where)->where('type', 'Purchase')->order_by('id', 'DESC')->get('invoice_product')->result();
            $purchasesAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS totalPurchaseTaxableAmount')->where($where)->where($invoiceDateWhere)->get('purchases')->row();
            $estimatePurchasesAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS totalEstimatePurchaseTaxableAmount')->where($where)->where($invoiceDateWhere)->get('estimate_purchase')->row();
            $directExpensesAmountQuery = $this->db->select('SUM(IFNULL(debit, 0)) AS totalDirectExpensesAmount')->where($where)->where('account_group', $directExpensesAccountGroupId)->where($dateWhere)->get('payment')->row();
            $indirectExpensesAmountQuery = $this->db->select('SUM(IFNULL(debit, 0)) AS totalIndirectExpensesAmount')->where($where)->where('account_group', $indirectExpensesAccountGroupId)->where($dateWhere)->get('payment')->row();
            $salesAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS totalSaleTaxableAmount')->where($where)->where($invoiceDateWhere)->get('sales')->row();
            $estimateSalesAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS totalEstimateSaleTaxableAmount')->where($where)->where($invoiceDateWhere)->get('estimates')->row();
            $closingStockProductDataQuery = $this->db->select('id, price')->where($where)->order_by('id', 'DESC')->get('product')->result();
            $closingStockPurchaseRatesQuery = $this->db->select('product_id, rate')->where($where)->where('type', 'Purchase')->where($invoiceDateWhere)->order_by('id', 'DESC')->get('invoice_product')->result();            
            $indirectIncomesAmountQuery = $this->db->select('SUM(IFNULL(credit, 0)) AS totalIndirectIncomesAmount')->where($where)->where('account_group', $indirectIncomesAccountGroupId)->where($dateWhere)->get('payment')->row();

            // Create a mapping of product rates for easy access
            $openingStockProductRates = [];
            foreach ($openingStockPurchaseRatesQuery as $rate) {
                $openingStockProductRates[$rate->product_id] = $rate->rate;
            }

            // Calculate the opening stock value
            $openingStockValue = 0;
            foreach ($openingStockProductDataQuery as $openingStock) {
                $productId = $openingStock->id;
                $qty = $openingStock->qty;
                $rate = isset($openingStockProductRates[$productId]) ? $openingStockProductRates[$productId] : $openingStock->price;
                $openingStockValue += ($qty * $rate);
            }
            
            // Create a mapping of product rates for easy access
            $closingStockProductRates = [];
            foreach ($closingStockPurchaseRatesQuery as $rate) {
                $closingStockProductRates[$rate->product_id] = $rate->rate;
            }
            // Calculate the closing stock value
            $closingStockValue = 0;
            foreach ($closingStockProductDataQuery as $closingStock) {
                $productId = $closingStock->id;
                $balanceQty = ProductBalanceQuanity($productId);
                if ($balanceQty > 0) {
                    $rate = isset($closingStockProductRates[$productId]) ? $closingStockProductRates[$productId] : $closingStock->price;
                    $closingStockValue += ($balanceQty * $rate);
                }
            }
            
            $data['fromDate'] = $financialYearDates['fromDate'];
            $data['toDate'] = $financialYearDates['toDate'];
            $data['openingStockAmount'] = $openingStockValue;
            $data['purchasesAmount'] = ($purchasesAmountQuery->totalPurchaseTaxableAmount + $estimatePurchasesAmountQuery->totalEstimatePurchaseTaxableAmount);
            $data['directExpensesAmount'] = $directExpensesAmountQuery->totalDirectExpensesAmount;
            $data['indirectExpensesAmount'] = $indirectExpensesAmountQuery->totalIndirectExpensesAmount;
            $data['salesAmount'] = ($salesAmountQuery->totalSaleTaxableAmount + $estimateSalesAmountQuery->totalEstimateSaleTaxableAmount);
            $data['closingStockAmount'] = $closingStockValue;
            $data['indirectIncomesAmount'] = $indirectIncomesAmountQuery->totalIndirectIncomesAmount;
            $data['page'] = "reports/profit-loss/profitAndLossReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getOpeningStockInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $productDataQuery = $this->db->select('id, p_name, qty, price')->where($where)->where('qty >', 0)->order_by('id', 'DESC')->get('product')->result();
            $openingStockPurchaseRatesQuery = $this->db->select('product_id, rate')->where($where)->where('type', 'Purchase')->order_by('id', 'DESC')->get('invoice_product')->result();
            
            // Convert $openingStockPurchaseRatesQuery to an associative array for easy lookup
            $purchaseRates = [];
            foreach ($openingStockPurchaseRatesQuery as $rateData) {
                $purchaseRates[$rateData->product_id] = $rateData->rate;
            }

            // Merge and display data
            foreach ($productDataQuery as $product) {
                $product->rate = isset($purchaseRates[$product->id]) ? $purchaseRates[$product->id] : $product->price;
            }
            
            $data['openingStockData'] = $productDataQuery;
            $data['page'] = "reports/profit-loss/getOpeningStockInfo";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getPurchaseAccountsInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
                
            $where = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $purchasesAccountsQuery = $this->db->select('type, invoiceDate, invoiceNumber, customerName, taxableAmount')->where($where)->get_compiled_select('purchases');
            $estimatePurchaseAmountQuery = $this->db->select('type, invoiceDate, invoiceNumber, customerName, taxableAmount')->where($where)->get_compiled_select('estimate_purchase');            
            // Combine the two queries using UNION
            $combinedQuery = "($purchasesAccountsQuery) UNION ($estimatePurchaseAmountQuery) ORDER BY invoiceDate DESC";
            // Execute the combined query
            $query = $this->db->query($combinedQuery);
            // Fetch the results
            $results = $query->result();
            
            $data['purchaseAccountsData'] = $results;
            $data['page'] = "reports/profit-loss/getPurchaseAccountsInfo";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getDirectExpensesInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
                
            $where = ['date >=' => $from, 'date <=' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $directExpensesAccountGroupId = get_title('accounting_group', ['name' => 'Direct Expense'], 'id');
            
            $directExpensesAmountQuery = $this->db->select('date, voucher_number, party_id, debit')->where($where)->where('account_group', $directExpensesAccountGroupId)->order_by('id', 'DESC')->get('payment')->result();
            $data['directExpensesData'] = $directExpensesAmountQuery;
            $data['page'] = "reports/profit-loss/getDirectExpensesInfo";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getIndirectExpensesInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
                
            $where = ['date >=' => $from, 'date <=' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $indirectExpensesAccountGroupId = get_title('accounting_group', ['name' => 'Indirect Expense'], 'id');
            
            $indirectExpensesAmountQuery = $this->db->select('date, voucher_number, party_id, debit')->where($where)->where('account_group', $indirectExpensesAccountGroupId)->order_by('id', 'DESC')->get('payment')->result();
            $data['indirectExpensesData'] = $indirectExpensesAmountQuery;
            $data['page'] = "reports/profit-loss/getIndirectExpensesInfo";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getSalesAccountsInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
                
            $where = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            $salesAmountQuery = $this->db->select('type, invoiceDate, invoiceNumber, customerName, taxableAmount')->where($where)->get_compiled_select('sales');
            $estimateSalesAmountQuery = $this->db->select('type, invoiceDate, invoiceNumber, customerName, taxableAmount')->where($where)->get_compiled_select('estimates');            
            // Combine the two queries using UNION
            $combinedQuery = "($salesAmountQuery) UNION ($estimateSalesAmountQuery) ORDER BY invoiceDate DESC";
            // Execute the combined query
            $query = $this->db->query($combinedQuery);
            // Fetch the results
            $results = $query->result();
            $data['salesAccountsData'] = $results;
            $data['page'] = "reports/profit-loss/getSalesAccountsInfo";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getClosingStockInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            
            $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $invoiceDateWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $productDataQuery = $this->db->select('id, p_name, price')->where($where)->order_by('id', 'DESC')->get('product')->result();
            $closingStockPurchaseRatesQuery = $this->db->select('product_id, rate')->where($where)->where('type', 'Purchase')->where($invoiceDateWhere)->order_by('id', 'DESC')->get('invoice_product')->result();
            
            // Convert $closingStockPurchaseRatesQuery to an associative array for easy lookup
            $purchaseRates = [];
            foreach ($closingStockPurchaseRatesQuery as $rateData) {
                $purchaseRates[$rateData->product_id] = $rateData->rate;
            }

            // Merge and display data
            foreach ($productDataQuery as $product) {
                $product->rate = isset($purchaseRates[$product->id]) ? $purchaseRates[$product->id] : $product->price;
            }
            
            $data['closingStockData'] = $productDataQuery;
            $data['page'] = "reports/profit-loss/getClosingStockInfo";
            return $this->load->view('inventory_sep/inventory', $data);
        }
                
        public function getIndirectIncomesInfo()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
                
            $where = ['date >=' => $from, 'date <=' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $indirectIncomesAccountGroupId = get_title('accounting_group', ['name' => 'Indirect Income'], 'id');
            
            $indirectIncomesAmountQuery = $this->db->select('date, voucher_number, party_id, credit')->where($where)->where('account_group', $indirectIncomesAccountGroupId)->order_by('id', 'DESC')->get('payment')->result();
            $data['indirectIncomesData'] = $indirectIncomesAmountQuery;
            $data['page'] = "reports/profit-loss/getIndirectIncomesInfo";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function vendorWisePurchaseSaleReport()
        {
            $compId = $this->session->userdata('comp_id');
            $userId = $this->session->userdata('user_id');
            
            $branchWhere = ['CompId' => $compId, 'UserId' => $userId];
            $branchQuery = $this->db->select('Id, BranchName, BranchUserId')->where($branchWhere)->order_by('Id', 'ASC')->get('companybranch');
            if ($branchQuery->num_rows()) {
                $branchDataResult = $branchQuery->result();
            } else {
                $branchDataResult = [];
            }
            
            $data['branchData'] = $branchDataResult;
            $data['page'] = "reports/vendorWisePurchaseSaleReport";
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function getBranchStockInfo($branchId)
        {
            if (isset($branchId) && !empty($branchId)) {
                $compId = $this->session->userdata('comp_id');
                
                $branchWhere = ['Id' => $branchId, 'CompId' => $compId];
                $branchQuery = $this->db->select('BranchUserId')->where($branchWhere)->get('companybranch');
                if ($branchQuery->num_rows()) {
                    $branchDataResult = $branchQuery->row();
                    
                    $productWhere = ['user_id' => $branchDataResult->BranchUserId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId, 'status' => 1];
                    $productDataQuery = $this->db->select('id, p_name, user_id, comp_id, CompanyBranchId')->where($productWhere)->order_by('id', 'DESC')->get('product')->result();

                    $data['branchStockData'] = $productDataQuery;
                    return $this->load->view('inventory_sep/reports/getBranchStockInfo', $data);
                }
            }
        }
        
        public function topSalesCustomersReport()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $salesQuery = $this->db->select('customerId, COUNT(id) AS totalSaleInvoice, SUM(IFNULL(total, 0)) AS totalAmount')
                ->where($where)
                ->group_by('customerId')
                ->get_compiled_select('sales');

            $estimatesSaleQuery = $this->db->select('customerId, COUNT(id) AS totalSaleInvoice, SUM(IFNULL(total, 0)) AS totalAmount')
                ->where($where)
                ->group_by('customerId')
                ->get_compiled_select('estimates');

            $combinedQuery = "SELECT customerId, SUM(IFNULL(totalSaleInvoice, 0)) AS totalSaleInvoice, SUM(IFNULL(totalAmount, 0)) AS totalAmount 
                FROM (($salesQuery) UNION ALL ($estimatesSaleQuery)) AS combined 
                GROUP BY customerId 
                ORDER BY totalAmount DESC";

            $topSalesResults = $this->db->query($combinedQuery)->result();
            
            $data['topSalesCustomers'] = $topSalesResults;
            $data['branches'] = $this->db->select('Id, BranchName')->where('CompId', $compId)->order_by('BranchName', 'ASC')->get('companybranch')->result();
            $data['page'] = "reports/topSalesCustomersReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function accountingBalanceSheet()
        {
            $financialYearDates = getFinancialYearDates(get_title('year', ['id' => $this->session->userdata('financial_year')], 'year'));
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = $financialYearDates['fromDate'];
                $to = $financialYearDates['toDate'];
            }
            
            $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $invoiceDateWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to];
            $dateWhere = ['date >=' => $from, 'date <=' => $to];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }
            
            /////////////////////////////////////////////////// Liabilities ///////////////////////////////////////////////////
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            // Duties & Taxes
            $dutiesAndTaxesQuery = $this->db->select('SUM(IFNULL(totalsgst, 0)) AS totalsgst, SUM(IFNULL(totalcgst, 0)) AS totalcgst, SUM(IFNULL(totaligst, 0)) AS totaligst')
                ->where($where)
                ->where($invoiceDateWhere)
                ->get('sales')
                ->row();

            // Sundry Creditors
            $purchasesAmountQuery = $this->db->select('invoiceDate, customerId, SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS totalBalanceAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->group_start()
                ->where('paid_amount IS NULL', null, false)
                ->or_where('total != paid_amount')
                ->group_end()
                ->group_by('customerId')
                ->get_compiled_select('purchases');

            $estimatesPurchaseAmountQuery = $this->db->select('invoiceDate, customerId, SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS totalBalanceAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->group_start()
                ->where('paid_amount IS NULL', null, false)
                ->or_where('total != paid_amount')
                ->group_end()
                ->group_by('customerId')
                ->get_compiled_select('estimate_purchase');

            $combinedQuery = "SELECT invoiceDate, customerId, SUM(IFNULL(totalBalanceAmount, 0)) AS totalBalanceAmount FROM
                 (($purchasesAmountQuery) UNION ALL ($estimatesPurchaseAmountQuery)) AS combined
                GROUP BY customerId
                ORDER BY invoiceDate DESC";
            
            $purchasesWithEstimatePurchaseResults = $this->db->query($combinedQuery)->result();
            
            
            // Current Period
            $currentPeriodPurchasesAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS currentPeriodPurchaseTaxableAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->get('purchases')
                ->row();

            $currentPeriodEstimatesPurchaseAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS currentPeriodEstimatePurchaseTaxableAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->get('estimate_purchase')
                ->row();
            
            $currentPeriodSalesAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS currentPeriodSaleTaxableAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->get('sales')
                ->row();

            $currentPeriodEstimatesSaleAmountQuery = $this->db->select('SUM(IFNULL(taxableAmount, 0)) AS currentPeriodEstimateSaleTaxableAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->get('estimates')
                ->row();
            
            ////////////////////////////////////////////////// Assets ///////////////////////////////////////////
            // Closing Stock
            $productDataQuery = $this->db->select('id, p_name, price')->where($where)->order_by('id', 'DESC')->get('product')->result();
            $closingStockPurchaseRatesQuery = $this->db->select('product_id, rate')->where($where)->where('type', 'Purchase')->where($invoiceDateWhere)->order_by('id', 'DESC')->get('invoice_product')->result();
            
            // Convert $closingStockPurchaseRatesQuery to an associative array for easy lookup
            $purchaseRates = [];
            foreach ($closingStockPurchaseRatesQuery as $rateData) {
                $purchaseRates[$rateData->product_id] = $rateData->rate;
            }

            // Merge and display data
            foreach ($productDataQuery as $product) {
                $product->rate = isset($purchaseRates[$product->id]) ? $purchaseRates[$product->id] : $product->price;
            }
            
            // Sundry Debtors
            $salesAmountQuery = $this->db->select('invoiceDate, customerId, SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS totalBalanceAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->group_start()
                ->where('paid_amount IS NULL', null, false)
                ->or_where('total != paid_amount')
                ->group_end()
                ->group_by('customerId')
                ->get_compiled_select('sales');
            
            $estimateSalesAmountQuery = $this->db->select('invoiceDate, customerId, SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS totalBalanceAmount')
                ->where($where)
                ->where($invoiceDateWhere)
                ->group_start()
                ->where('paid_amount IS NULL', null, false)
                ->or_where('total != paid_amount')
                ->group_end()
                ->group_by('customerId')
                ->get_compiled_select('estimates');
            
            $salesWithEstimateSalesQuery = "SELECT invoiceDate, customerId, SUM(IFNULL(totalBalanceAmount, 0)) AS totalBalanceAmount FROM
                (($salesAmountQuery) UNION ALL ($estimateSalesAmountQuery)) AS combined
                GROUP BY customerId
                ORDER BY invoiceDate DESC";
            
            $salesWithEstimateSalesResults = $this->db->query($salesWithEstimateSalesQuery)->result();
            
            // Cash-in-Hand
            $cashPaymentMode = get_title('payment_mode', ['payment_method' => 'Cash'], 'id');
            $cashInHandQuery = $this->db->select('SUM(IFNULL(debit, 0)) - SUM(IFNULL(credit, 0)) AS cashInHandAmount', false)
                ->where($where)
                ->where($dateWhere)
                ->where('paymentmode', $cashPaymentMode)
                ->where('payment_type IS NOT NULL', null, false)
                ->get('payment')
                ->row();
            
            // Bank Accounts
            $bankAccountsQuery = $this->db->select('SUM(IFNULL(debit, 0)) - SUM(IFNULL(credit, 0)) AS backAccountAmount', false)
                ->where($where)
                ->where($dateWhere)
                ->where('paymentmode !=', $cashPaymentMode)
                ->where('payment_type IS NOT NULL', null, false)
                ->get('payment')
                ->row();
            
            $data['fromDate'] = $financialYearDates['fromDate'];
            $data['toDate'] = $financialYearDates['toDate'];
            $data['dutiesAndTaxes'] = $dutiesAndTaxesQuery;
            $data['sundryCreditors'] = $purchasesWithEstimatePurchaseResults;
            $data['currentPeriod'] = (($currentPeriodSalesAmountQuery->currentPeriodSaleTaxableAmount + $currentPeriodEstimatesSaleAmountQuery->currentPeriodEstimateSaleTaxableAmount) - ($currentPeriodPurchasesAmountQuery->currentPeriodPurchaseTaxableAmount + $currentPeriodEstimatesPurchaseAmountQuery->currentPeriodEstimatePurchaseTaxableAmount));
            $data['closingStock'] = $productDataQuery;
            $data['sundryDebtors'] = $salesWithEstimateSalesResults;
            $data['cashInHand'] = $cashInHandQuery;            
            $data['bankAccounts'] = $bankAccountsQuery;            
            $data['page'] = "reports/accountingBalanceSheet";
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function saleInoviceEditHistory()
        {
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');

            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-01');
                $to = date('Y-m-d');
            }
            $dateWhere = ['date >=' => $from, 'date <=' => $to];
            
            $where = ['userId' => $userId, 'compId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $compBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['saleInvoiceHistory'] = $this->db->select('date, invoiceId, editedBy, editedDateTime')
                    ->where($dateWhere)                    
                    ->where($where)                    
                    ->group_by('invoiceId')
                    ->order_by('editedDateTime', 'DESC')
                    ->get('sales_edit_history')
                    ->result();
            
            $data['page'] = "reports/saleInoviceEditHistory";
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function saleInvoiceEditHistoryInfo($invoiceId)
        {
            if (isset($invoiceId) && !empty($invoiceId)) {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['invoiceId' => $invoiceId, 'userId' => $userId, 'compId' => $compId];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $compBranchId;
                }
                
                $data['invoiceHistory'] = $this->db->where($where)
                    ->order_by('editedDateTime', 'DESC')
                    ->get('sales_edit_history')
                    ->result_array();
            
                return $this->load->view('inventory_sep/reports/saleInvoiceEditHistoryInfo', $data);
            }
        }
        
        public function gstSalesReport()
        {
            ini_set('memory_limit', '-1');
            
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            if ($this->input->get('from') && $this->input->get('to')) {
                    $from = $this->input->get('from');
                    $to = $this->input->get('to');
            } else {
                    $from = date('Y-m-01');
                    $to = date('Y-m-d');
            }

            $where = ['invoiceDate >= ' => $from, 'invoiceDate <= ' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];                
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
            }                
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('invoiceID, invoiceNo, invoiceDate, tax')
                    ->select_sum('netAmount')
                    ->select_sum('price')
                    ->select_sum('igst')
                    ->select_sum('cgst')
                    ->select_sum('sgst')
                ->from('invoice_product')
                ->where($where)
                ->group_by(['invoiceNo', 'tax'])
                ->order_by('invoiceNo', 'DESC');
            $gstSales = $this->db->get()->result();
            
            $data['gstSales'] = $gstSales;                
            $data['page'] = "reports/gstSalesReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
    }