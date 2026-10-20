<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Einvoice extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model');
  }

  public function save_e_invoice()
  {
    $form_data = $_POST['invoice_data'];
    $Einvoice_arr = [
      'AckNo' => $form_data['AckNo'],
      'AckDt' => $form_data['AckDt'],
      'Irn' => $form_data['Irn'],
      'SignedInvoice' => $form_data['SignedInvoice'],
      'SignedQRCode' => $form_data['SignedQRCode'],
      'EwbNo' => $form_data['EwbNo'],
      'EwbDt' => $form_data['EwbDt'],
      'EwbValidTill' => $form_data['EwbValidTill'],
      'QRCodeUrl' => $form_data['QRCodeUrl'],
      'EinvoicePdf' => $form_data['EinvoicePdf'],
      'Status' => $form_data['Status'],
      'Remarks' => $form_data['Remarks'],
      'invoiceNumber' => $form_data['invoiceNumber'],
    ];

    if (array_key_exists('EwaybillPdf', $form_data)) {
      $Einvoice_arr['EwaybillPdf'] = $form_data['EwaybillPdf'];
    }

    $result = $this->user_model->saveAry('e_invoice_details', $Einvoice_arr);

    $data['Irn'] = ['Irn' => $form_data['Irn']];
    $this->user_model->updateAry('sales', $data['Irn'], ['id' => $form_data['invoice_id']]);
    echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function cancel_e_invoice()
  {
    $leger_where = ['id' => $_POST['invoice_id']];
    $legerres = $this->user_model->getRow('sales', $leger_where);

    $invoice_update = true;
    if ($invoice_update) {
      $update_data = [
        'eInvoiceCancelStatus' => 1,
        'eInvoiceCancelDate' => $_POST['cancelDate'],
      ];
      $where_update = ['invoiceNumber' => $legerres->invoiceNumber];
      $this->user_model->updateAry('sales', $update_data, $where_update);
    }
    echo json_encode(['status' => true, 'msg' => 'Invoice saved successfully.']);
  }

  public function delete_e_invoice()
  {
    $leger_where = ['id' => $_POST['invoice_id']];
    $legerres = $this->model->getRow('sales', $leger_where);


    $delete_legerwhere = ['voucher_number' => $legerres->invoiceNumber];
    $rrr = $this->model->deleteRow('ledger_report', $delete_legerwhere);

    $delete_sale = ['invoiceNumber' => $legerres->invoiceNumber];
    $rrr = $this->model->deleteRow('sales', $delete_sale);

    $delete_invoicewhere = ['InvoiceNo' => $legerres->invoiceNumber];
    $this->model->deleteRow('invoice_product', $delete_invoicewhere);

    $delete_trans = ['voucher_number' => $legerres->invoiceNumber];
    $this->model->deleteRow('transporter_details', $delete_trans);

    $delete_e_inv_details = ['invoiceNumber' => $legerres->invoiceNumber];
    $this->model->deleteRow('e_invoice_details', $delete_e_inv_details);


    echo json_encode(['status' => true, 'msg' => 'Invoice saved successfully.']);
  }

  public function user_gst_details_update()
  {
    $gst_details = $_POST['gst_details'];
    $gst_no = $gst_details['Gstin'];

    $where1 = ['gstno' => $gst_no];
    // $data1['start_point']=$data['invoiceNumber']; 
    $data['gst_details'] = ['gst_details' => json_encode($gst_details)];
    $result = $this->model->updateAry('business_details', $data['gst_details'], $where1);
    echo json_encode(['status' => true, 'result' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function ledger_gst_details_update()
  {
    $gst_details = $_POST['gst_details'];
    $gst_no = $gst_details['Gstin'];
    $where1 = ['gst' => $gst_no];
    // $data1['start_point']=$data['invoiceNumber']; 
    $data_array = [
      'gst_details' => json_encode($gst_details),
      'gst_details_status' => 1,
    ];
    $result = $this->model->updateAry('parties', $data_array, $where1);
    echo json_encode(['status' => true, 'result' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function get_invoice()
  {
    $id = $_POST['invoice_id'];
    $where = ['id' => $id];
    $result = $this->user_model->getRow('sales', $where);
    $check_toknen = checkGspAccessToken($result);
    $result->gsp_access_token_expire = $check_toknen['token_expire'];
    $result->gst_access_token = $check_toknen['gst_access_token'];
    $result->ewayBillApiMode = $check_toknen['ewayBillApiMode'];
    $result->eInvoiceApiMode = $check_toknen['eInvoiceApiMode'];

    $result->einvoie_details = false;
    if (isset($result->Irn)) {
      $result->einvoie_details = $this->user_model->getRow('e_invoice_details', ['Irn' => $result->Irn]);
    }
    $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
    $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);

    $result->all_hsn_verified = true;
    foreach ($products as $row) {
      $product_details = $this->user_model->getRow('product', ['id' => $row->product_id]);
      if ($product_details->hsn_verified == "N") {
        $result->all_hsn_verified = false;
      }
      $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
      $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
      $row->serials = $serials;
      $row->product_title = $product_details->p_name;
      $row->is_service = $product_details->is_service;
      $row->hsn_verified = $product_details->hsn_verified;
      $row->hsn_verified_date = $product_details->hsn_verified_date;
      $row->hsn = $product_details->hsn;
    }

    $result->product = $products;
    $result->ledger_details = $this->user_model->getRow('parties', ['id' => $result->customerId]);
    $result->state_details = $this->user_model->getRow('state_list', ['code' => $result->ledger_details->state_code]);

    if (isset($result->transport_id) && $result->transport_id > 0) {
      $result->transport_details = $this->user_model->getRow('transporter_details', ['id' => $result->transport_id]);
    }
    echo json_encode(['status' => true, 'result' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function saveEwayBill()
  {
    $invoice_id = $_POST['invoice_id'];
    $url = $_POST['invoice_data']['url'];
    $data['eway_Bill_url'] = $url;
    $data['ewayBillStatus'] = 1;
    $data['ewayBillDetails'] = json_encode($_POST['invoice_data']);
    $data['ewayBillNumber'] = $_POST['invoice_data']['ewayBillNo'];
    $result = $this->user_model->updateAry('sales', $data, ['id' => $invoice_id]);
    echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function get_invoice_eway()
  {
    $id = $_POST['invoice_id'];
    $where = ['id' => $id];
    $result = $this->user_model->getRow('sales', $where);
    $check_toknen = checkGspAccessToken($result);
    $result->gsp_access_token_expire = $check_toknen['token_expire'];
    $result->gst_access_token = $check_toknen['gst_access_token'];
    $result->ewayBillApiMode = $check_toknen['ewayBillApiMode'];
    $result->eInvoiceApiMode = $check_toknen['eInvoiceApiMode'];
    $result->einvoie_details = false;
    if (isset($result->Irn)) {
      $result->einvoie_details = $this->user_model->getRow('e_invoice_details', ['Irn' => $result->Irn]);
    }
    $pwhare = ['invoiceID' => $id, 'type' => 'Sale'];
    $products = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
    $result->all_hsn_verified = true;
    foreach ($products as $row) {
      $product_details = $this->user_model->getRow('product', ['id' => $row->product_id]);
      if ($product_details->hsn_verified == "N") {
        $result->all_hsn_verified = false;
      }
      $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
      $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
      $row->serials = $serials;
      $row->product_title = $product_details->p_name;
      $row->is_service = $product_details->is_service;
      $row->hsn_verified = $product_details->hsn_verified;
      $row->hsn_verified_date = $product_details->hsn_verified_date;
      $row->hsn = $product_details->hsn;
    }
    $result->product = $products;
    $result->ledger_details = $this->user_model->getRow('parties', ['id' => $result->customerId]);
    $result->state_details = $this->user_model->getRow('state_list', ['code' => $result->ledger_details->state_code]);

    if (isset($result->transport_id) && $result->transport_id > 0) {
      $result->transport_details = $this->user_model->getRow('transporter_details', ['id' => $result->transport_id]);
    }
    echo json_encode(['status' => true, 'result' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function gsp_token_update()
  {
    $invoice_id = $_POST['invoice_id'];
    $gsp_access_token = $_POST['gsp_access_token'];

    $current_timestamp = time();
    $new_timestamp = $current_timestamp + (5 * 60 * 60);
    $data['gsp_access_token_expire'] = $new_timestamp;
    $data['gsp_access_token'] = $gsp_access_token;
    $sales = $this->user_model->getRow('sales', ['id' => $invoice_id]);
    $sellername = "";
    if ($sales->CompanyBranchId == null) {
      $sellername = "Company";
      $result = $this->user_model->updateAry('inventory_billing', $data, ['id' => $sales->comp_id]);
    } else {
      $sellername = "Company Branch";
      $result = $this->user_model->updateAry('companybranch', $data, ['id' => $sales->CompanyBranchId]);
    }
    echo json_encode(['status' => true, 'data' => $result, 'sales' => $sales, 'msg' => $sellername . ' GSP Access token update successfully.']);
  }


  public function cancelEwayBill()
  {
    $leger_where = ['id' => $_POST['invoice_id']];
    $legerres = $this->user_model->getRow('sales', $leger_where);

    $invoice_update = true;
    if ($invoice_update) {
      $update_data = [
        'ewayCancelStatus' => 1,
      ];
      $where_update = ['id' => $legerres->id];
      $this->user_model->updateAry('sales', $update_data, $where_update);
    }
    echo json_encode(['status' => true, 'msg' => 'E way Bill Canceled successfully.']);
  }

  public function updateDistance()
  {
    $invoice_id = $_POST['invoice_id'];
    $distance = $_POST['distance'];
    $data['transportation_distance'] = $distance;
    $result = $this->user_model->updateAry('sales', $data, ['id' => $invoice_id]);
    echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Distance update successfully.']);
  }

  public function ewayBillReport($start = 0)
  {
    $start = is_numeric($start) ? (int) $start : 0;
    $data['page'] = 'gst_report/ewaybill_report';
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

    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];

    $pwhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
    $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
    if ($this->session->userdata('BranchRole')) {
      $where['CompanyBranchId'] = $CompanyBranchId;
      $pwhere['CompanyBranchId'] = $CompanyBranchId;
      $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
    }

    $table = 'sales';
    $order = ['col' => 'id', 'order' => 'DESC'];
    $where['ewayBillStatus'] = 1;
    $result = $this->user_model->getEwayBill($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where, '', $party, $SalesmanId);

    $data['parties'] = $this->db->select('id, name, contact, area')->where($pwhere)->order_by('name', 'ASC')->get('parties')->result();

    $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Id', 'DESC')->get('employees')->result();

    $data['result'] = $result;
    $data['fromDate'] = $first_date;
    $data['toDate'] = $last_date;
    return $this->load->view('inventory_sep/inventory', $data);
  }

  public function eInvoiceReport($start = 0)
  {
    $start = is_numeric($start) ? (int) $start : 0;
    $data['page'] = 'gst_report/einvoice_report';
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

    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];

    $pwhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
    $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
    if ($this->session->userdata('BranchRole')) {
      $where['CompanyBranchId'] = $CompanyBranchId;
      $pwhere['CompanyBranchId'] = $CompanyBranchId;
      $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
    }

    $table = 'sales';
    $order = ['col' => 'id', 'order' => 'DESC'];
    $result = $this->user_model->getEinvoice($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where, '', $party, $SalesmanId);

    $data['parties'] = $this->db->select('id, name, contact, area')->where($pwhere)->order_by('name', 'ASC')->get('parties')->result();

    $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->order_by('Id', 'DESC')->get('employees')->result();

    $data['result'] = $result;
    $data['fromDate'] = $first_date;
    $data['toDate'] = $last_date;
    return $this->load->view('inventory_sep/inventory', $data);
  }

  // ========================================= E-Credit Note Code ================================//
  public function get_credit_note()
  {
    $id = $_POST['invoice_id'];
    $where = ['id' => $id];
    $result = $this->user_model->getRow('credit_notes', $where);
    $check_toknen = checkGspAccessToken($result);
    $result->gsp_access_token_expire = $check_toknen['token_expire'];
    $result->gst_access_token = $check_toknen['gst_access_token'];
    $result->ewayBillApiMode = $check_toknen['ewayBillApiMode'];
    $result->eInvoiceApiMode = $check_toknen['eInvoiceApiMode'];

    $result->einvoie_details = false;
    if (isset($result->Irn)) {
      $result->einvoie_details = $this->user_model->getRow('e_invoice_details', ['Irn' => $result->Irn]);
    }
    $pwhare = ['invoiceID' => $id, 'type' => 'CreditNote'];
    $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);
    $result->all_hsn_verified = true;
    foreach ($products as $row) {
      $product_details = $this->user_model->getRow('product', ['id' => $row->product_id]);
      if ($product_details->hsn_verified == "N") {
        $result->all_hsn_verified = false;
      }
      $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
      $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
      $row->serials = $serials;
      $row->product_title = $product_details->p_name;
      $row->is_service = $product_details->is_service;
      $row->hsn_verified = $product_details->hsn_verified;
      $row->hsn_verified_date = $product_details->hsn_verified_date;
      $row->hsn = $product_details->hsn;
    }
    $result->product = $products;
    $result->ledger_details = $this->user_model->getRow('parties', ['id' => $result->customerId]);
    $result->state_details = $this->user_model->getRow('state_list', ['code' => $result->ledger_details->state_code]);

    if (isset($result->transport_id) && $result->transport_id > 0) {
      $result->transport_details = $this->user_model->getRow('transporter_details', ['id' => $result->transport_id]);
    }
    echo json_encode(['status' => true, 'result' => $result, 'msg' => 'Invoice saved successfully.']);
  }


  public function saveECreditNote()
  {
    $form_data = $_POST['invoice_data'];
    $Einvoice_arr = [
      'AckNo' => $form_data['AckNo'],
      'document_type' => 'CRN',
      'AckDt' => $form_data['AckDt'],
      'Irn' => $form_data['Irn'],
      'SignedInvoice' => $form_data['SignedInvoice'],
      'SignedQRCode' => $form_data['SignedQRCode'],
      'EwbNo' => $form_data['EwbNo'],
      'EwbDt' => $form_data['EwbDt'],
      'EwbValidTill' => $form_data['EwbValidTill'],
      'QRCodeUrl' => $form_data['QRCodeUrl'],
      'EinvoicePdf' => $form_data['EinvoicePdf'],
      'Status' => $form_data['Status'],
      'Remarks' => $form_data['Remarks'],
      'invoiceNumber' => $form_data['invoiceNumber'],
    ];

    if (array_key_exists('EwaybillPdf', $form_data)) {
      $Einvoice_arr['EwaybillPdf'] = $form_data['EwaybillPdf'];
    }

    $result = $this->user_model->saveAry('e_invoice_details', $Einvoice_arr);

    $data['Irn'] = ['Irn' => $form_data['Irn'], 'eCRN_status' => 1];
    $this->user_model->updateAry('credit_notes', $data['Irn'], ['id' => $form_data['invoice_id']]);
    echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function cancel_eCRN_invoice()
  {
    $leger_where = ['id' => $_POST['invoice_id']];
    $legerres = $this->user_model->getRow('credit_notes', $leger_where);

    $invoice_update = true;
    if ($invoice_update) {
      $update_data = [
        'eCRN_cancel' => 1,
        'eCRNCancelDate' => $_POST['cancelDate'],
      ];
      $where_update = ['invoiceNumber' => $legerres->invoiceNumber];
      $this->user_model->updateAry('credit_notes', $update_data, $where_update);
    }
    echo json_encode(['status' => true, 'msg' => 'E-Credit Note Canceled successfully.']);
  }


  // ========================================= E-Debit Note Code ================================//
  public function get_debit_note()
  {
    $id = $_POST['invoice_id'];
    $where = ['id' => $id];
    $result = $this->user_model->getRow('debit_notes', $where);
    $check_toknen = checkGspAccessToken($result);
    $result->gsp_access_token_expire = $check_toknen['token_expire'];
    $result->gst_access_token = $check_toknen['gst_access_token'];
    $result->ewayBillApiMode = $check_toknen['ewayBillApiMode'];
    $result->eInvoiceApiMode = $check_toknen['eInvoiceApiMode'];

    $result->einvoie_details = false;
    if (isset($result->Irn)) {
      $result->einvoie_details = $this->user_model->getRow('e_invoice_details', ['Irn' => $result->Irn]);
    }
    $pwhare = ['invoiceID' => $id, 'type' => 'DebitNote'];
    $products = $this->user_model->getAry('credit_product', '', '', '', $pwhare);

    $result->all_hsn_verified = true;
    foreach ($products as $row) {
      $product_details = $this->user_model->getRow('product', ['id' => $row->product_id]);
      if ($product_details->hsn_verified == "N") {
        $result->all_hsn_verified = false;
      }
      $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
      $serials = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
      $row->serials = $serials;
      $row->product_title = $product_details->p_name;
      $row->is_service = $product_details->is_service;
      $row->hsn_verified = $product_details->hsn_verified;
      $row->hsn_verified_date = $product_details->hsn_verified_date;
      $row->hsn = $product_details->hsn;
    }

    $result->product = $products;
    $result->ledger_details = $this->user_model->getRow('parties', ['id' => $result->customerId]);
    $result->state_details = $this->user_model->getRow('state_list', ['code' => $result->ledger_details->state_code]);

    if (isset($result->transport_id) && $result->transport_id > 0) {
      $result->transport_details = $this->user_model->getRow('transporter_details', ['id' => $result->transport_id]);
    }
    echo json_encode(['status' => true, 'result' => $result, 'msg' => 'Invoice saved successfully.']);
  }


  public function saveEDebitNote()
  {
    $form_data = $_POST['invoice_data'];
    $Einvoice_arr = [
      'AckNo' => $form_data['AckNo'],
      'document_type' => 'DBN',
      'AckDt' => $form_data['AckDt'],
      'Irn' => $form_data['Irn'],
      'SignedInvoice' => $form_data['SignedInvoice'],
      'SignedQRCode' => $form_data['SignedQRCode'],
      'EwbNo' => $form_data['EwbNo'],
      'EwbDt' => $form_data['EwbDt'],
      'EwbValidTill' => $form_data['EwbValidTill'],
      'QRCodeUrl' => $form_data['QRCodeUrl'],
      'EinvoicePdf' => $form_data['EinvoicePdf'],
      'Status' => $form_data['Status'],
      'Remarks' => $form_data['Remarks'],
      'invoiceNumber' => $form_data['invoiceNumber'],
    ];

    if (array_key_exists('EwaybillPdf', $form_data)) {
      $Einvoice_arr['EwaybillPdf'] = $form_data['EwaybillPdf'];
    }

    $result = $this->user_model->saveAry('e_invoice_details', $Einvoice_arr);

    $data['Irn'] = ['Irn' => $form_data['Irn'], 'eDBN_status' => 1];
    $this->user_model->updateAry('debit_notes', $data['Irn'], ['id' => $form_data['invoice_id']]);
    echo json_encode(['status' => true, 'data' => $result, 'msg' => 'Invoice saved successfully.']);
  }

  public function cancel_eDBN_invoice()
  {
    $leger_where = ['id' => $_POST['invoice_id']];
    $legerres = $this->user_model->getRow('debit_notes', $leger_where);

    $invoice_update = true;
    if ($invoice_update) {
      $update_data = [
        'eDBN_cancel' => 1,
        'eDBNCancelDate' => $_POST['cancelDate'],
      ];
      $where_update = ['invoiceNumber' => $legerres->invoiceNumber];
      $this->user_model->updateAry('debit_notes', $update_data, $where_update);
    }
    echo json_encode(['status' => true, 'msg' => 'E-Debit Note Canceled successfully.']);
  }
  
  
  
  public function monthlyGstr()
  {
    $data['page'] = 'gst_report/monthly_gstr';
    $data['result'] = []; 
    return $this->load->view('inventory_sep/inventory', $data);
  }

  public function getGstrList()
  {
    if ($this->input->is_ajax_request()) {
      $userId = $this->session->userdata('user_id');
      $compId = $this->session->userdata('comp_id');
      $compBranchId = $this->session->userdata('CompBranchId');
      $selected_month = $this->input->post('selected_month');
      $monthDates = monthDates($selected_month);
      $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
      if ($this->session->userdata('BranchRole')) {
        $where['CompanyBranchId'] = $compBranchId;
      }
      if (!empty($monthDates['fromDate'])) {
        $where['invoiceDate >='] = $monthDates['fromDate'];
      }
      if (!empty($monthDates['toDate'])) {
        $where['invoiceDate <='] = $monthDates['toDate'];
      }

      $where['gstr1_status !='] = 1;

      $productsQuery = $this->db->select('id,invoiceNumber,invoiceDate,customerName,gstNo,taxableAmount,gstr1_status,gstr1_error')->where($where)->order_by('id', 'DESC')->get('sales');

      if ($productsQuery->num_rows()) {
        $table =  '<table id="gstr-table" class="table table-bordered table-hover table-striped" style="background-color:#e2e3e5;" >'
          . '<thead class="bg-primary">'
          . '<tr>'
          . '<th><input type="checkbox" id="product-selectAll" /></th>'
          . '<th>Invoice</th>'
          . '<th>Date</th>'
          . '<th>Customer</th>'
          . '<th>Gst</th>'
          . '<th>Taxable Amount</th>'
          . '</tr>'
          . '</thead>'
          . '<tbody>';
        foreach ($productsQuery->result() as $key => $data) {
          ++$key;
          $hsnBtnCls = $data->hsn_verified == "Y" ? "btn-success" : "btn-danger";
          $table .= '<tr>'
            .  '<td class="cheking">'
            .  '' . $key . ''
            .  '</td>'
            .  '<td> ' . $data->invoiceNumber . ' </td>'
            .  '<td> ' . $data->invoiceDate . ' </td>'
            .  '<td> ' . $data->customerName . ' </td>'
            .  '<td> ' . $data->gstNo . ' </td>'
            .  '<td> ' . $data->taxableAmount . ' </td>'

            .  '</tr>';

          if ($data->gstr1_status == 2) {
            $errrors = json_decode($data->gstr1_error);
            $cgst_error = isset($errrors->GCACAL) ? $errrors->GCACAL : "";
            $sgst_error = isset($errrors->GSACAL) ? $errrors->GSACAL : "";

            $table .= '<tr>'

              .  '<td colspan="5" style="color:red;" > ' . $cgst_error . ' , ' . $sgst_error
              . '</td><td><a href=' . base_url("sale/saleEdit/" . $data->id) . ' class="text-info" target="_blank" title="Edit">
              <i class="fa fa-edit" aria-hidden="true"></i></a>'
              . ' </td>'

              .  '</tr>';
          }
        }
        $table .= '</tbody>'
          .  '</table>';
        echo $table;
      } else {
        echo '<table id="gstr-table" class="table table-bordered table-hover table-striped"  >'
          . '<thead class="bg-primary">'
          . '<tr>'
          . '<th><input type="checkbox" id="product-selectAll" /></th>'
          . '<th>Invoice</th>'
          . '<th>Date</th>'
          . '<th>Customer</th>'
          . '<th>Gst</th>'
          . '<th>Taxable Amount</th>'
          . '</tr>'
          . '</thead><tbody><tr><td colspan="6" style="text-align:center;" >No Record Found</td></tr></tbody></table>';
      }
    }
  }


  public function uploadGstr()
  {
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $where['invoiceDate >='] = $monthDates['fromDate'];
    $where['invoiceDate <='] = $monthDates['toDate'];
    $where['gstr1_status !='] = 1;
    $where = array_merge($where, loginUser());
    $select_value = "id,invoiceNumber,invoiceDate,
    gstNo,taxableAmount,totalTax,totalsgst,totalcgst,
    totaligst,totalValue,customerName";
    $sale_list = $this->user_model->getInvoicesList('sales', $select_value, $where);
    foreach ($sale_list as $sale_value) {
      $update_invocie['gstr1_status'] = 1;
      $update_invocie['gstr1_error'] = null;
      $update_invocie['gstr1_date'] = date('Y-m-d h:i:s');
      $update_status = $this->user_model->updateAry('sales', $update_invocie, ['id' => $sale_value->id]);
      $select = "id,tax,sgst,cgst,igst,hsn,qty,amount,taxAmount,netAmount,price,productname";
      $sale_value->product_details = $this->user_model->getInvoicesList('invoice_product', $select, ['invoiceID' => $sale_value->id]);
    }
    $gstr_access_token = gstrAccessToken("Sales");
    $apiUrl = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    $data = ["saleData" => $this->saleInvoiceHtml($sale_list)];
    // Convert data to JSON
    $jsonData = json_encode($data);
    // Initialize cURL
    $ch = curl_init();
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: JWT $token",
      "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
      echo "cURL error: " . curl_error($ch);
    } else {
      // Print response  
      $this->UpdateSaleGstrError($response);
      // echo "API Response: " . $response;
    }
    // Close cURL session
    curl_close($ch);
  }
  public function UpdateSaleGstrError($response)
  {
    $res = json_decode($response);
    $res = $res->result;
    $success_count = $res->success_count;
    $failure_count = $res->failure_count;
    $sale_error_data = $res->sale_error_data;

    if ($failure_count > 0) {
      foreach ($sale_error_data as $invoice) {
        if (isset($invoice->error->GEAEIN)) {
        } else {
          $update_invocie['gstr1_status'] = 2;
          $update_invocie['gstr1_error'] = json_encode($invoice->error);
          $update_invocie['gstr1_date'] = date('Y-m-d h:i:s');
          $this->user_model->updateAry('sales', $update_invocie, ['invoiceNumber' => $invoice->document_number]);
        }
      }
    }
  }

  public function saleInvoiceHtml($invoices)
  {
    $api_mode = "TEST";
    $invoice_arr = [];
    $getUserDetails = getUserDetails();
    foreach ($invoices as $value) {
      $date = new DateTime($value->invoiceDate);
      $month = $date->format('m');
      $year = $date->format('Y');
      $gstr3b_return_period = "$month-$year";
      $temp_arr = [
        "document_number" => $value->invoiceNumber,
        "document_date" => date('d/m/Y', strtotime($value->invoiceDate)),
        "supply_type" => "NOR",
        "invoice_status" => "Add",
        "invoice_category" => "TXN",
        "invoice_type" => "R",
        "total_invoice_value" => $value->totalValue,
        "total_taxable_value" => $value->taxableAmount,
        "txpd_taxtable_value" => $value->taxableAmount,
        "gstr3b_return_period" => $api_mode == "LIVE" ? $gstr3b_return_period : "05-2022",
        "reverse_charge" => "N",
        "isamended" => "N",
        "place_of_supply" => $api_mode == "LIVE" ? $getUserDetails->state_details->code : "33",
        "supplier_gstin" => $api_mode == "LIVE" ? $getUserDetails->gst_no : "33GSPTN0591G1Z7", // 33GSPTN9511G3Z3
        "buyer_gstin" => $api_mode == "LIVE" ? $value->gstNo : "33AAOCM4473A1ZE",
        "customer_name" => $api_mode == "LIVE" ? $value->customerName : "MARUTI SPINCOT INDIA PRIV",
        "itemList" => $this->saleInvoiceProductHtml($value->product_details),
      ];
      array_push($invoice_arr, $temp_arr);
    }
    return $invoice_arr;
  }

  public function saleInvoiceProductHtml($product_arr)
  {
    $item_arr = [];
    foreach ($product_arr as $value) {
      $tax_value = $value->tax > 3 ? (int)$value->tax : $value->tax;
      $obj = [
        "igst_amount" => $value->igst,
        "cgst_amount" => $value->cgst,
        "sgst_amount" => $value->sgst,
        "taxable_value" => $value->price,
        "hsn_code" => $value->hsn,
        "product_name" => $value->productname,
        "item_description" => $value->productname,
        "quantity" => $value->qty,
        "cess_amount" => "0.00",
        "gst_rate" => "$tax_value",
        "unit_of_product" => "OTH",
      ];
      array_push($item_arr, $obj);
    }
    return $item_arr;
  }

  public function getUploadedGstrList()
  {
    if ($this->input->is_ajax_request()) {
      $userId = $this->session->userdata('user_id');
      $compId = $this->session->userdata('comp_id');
      $compBranchId = $this->session->userdata('CompBranchId');

      $selected_month = $this->input->post('selected_month');
      $monthDates = monthDates($selected_month);

      $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
      if ($this->session->userdata('BranchRole')) {
        $where['CompanyBranchId'] = $compBranchId;
      }
      if (!empty($monthDates['fromDate'])) {
        $where['invoiceDate >='] = $monthDates['fromDate'];
      }
      if (!empty($monthDates['toDate'])) {
        $where['invoiceDate <='] = $monthDates['toDate'];
      }
      $where['gstr1_status ='] = 1;
      $productsQuery = $this->db->select('id,invoiceNumber,invoiceDate,customerName,gstNo,taxableAmount,gstr1_status,gstr1_error')->where($where)->order_by('id', 'DESC')->get('sales');

      if ($productsQuery->num_rows()) {
        $table =  '<table id="gstr-table" class="table table-bordered table-hover table-striped" style="background:#a8f1a8;" >'
          . '<thead class="bg-primary">'
          . '<tr>'
          . '<th><input type="checkbox" id="product-selectAll" /></th>'
          . '<th>Invoice</th>'
          . '<th>Date</th>'
          . '<th>Customer</th>'
          . '<th>Gst</th>'
          . '<th>Taxable Amount</th>'
          . '</tr>'
          . '</thead>'
          . '<tbody>';
        foreach ($productsQuery->result() as $key => $data) {
          ++$key;
          $hsnBtnCls = $data->hsn_verified == "Y" ? "btn-success" : "btn-danger";
          $table .= '<tr>'
            .  '<td class="cheking">'
            .  '' . $key . ''
            .  '</td>'
            .  '<td> ' . $data->invoiceNumber . ' </td>'
            .  '<td> ' . $data->invoiceDate . ' </td>'
            .  '<td> ' . $data->customerName . ' </td>'
            .  '<td> ' . $data->gstNo . ' </td>'
            .  '<td> ' . $data->taxableAmount . ' </td>'

            .  '</tr>';
        }
        $table .= '</tbody>'
          .  '</table>';
        echo $table;
      } else {
        echo '<table id="gstr-table" class="table table-bordered table-hover table-striped"  >'
          . '<thead class="bg-primary">'
          . '<tr>'
          . '<th><input type="checkbox" id="product-selectAll" /></th>'
          . '<th>Invoice</th>'
          . '<th>Date</th>'
          . '<th>Customer</th>'
          . '<th>Gst</th>'
          . '<th>Taxable Amount</th>'
          . '</tr>'
          . '</thead><tbody><tr><td colspan="6" style="text-align:center;" >No Record Found</td></tr></tbody></table>';
      }
    }
  }

  public function getGstrData()
  {
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $where['invoiceDate >='] = $monthDates['fromDate'];
    $where['invoiceDate <='] = $monthDates['toDate'];
    $FinancialYear = $monthDates['financial_year'];
    $api_mode = "TEST";
    $gstr_access_token = gstrAccessToken("get-gstr-data");
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];

    $userDetails = getUserDetails();
    // Query parameters
    $params = [
      'search' => '',
      'data_type' => 'GSTR1',
      'page_size' => 10,
    ];

    // Add query parameters to the URL
    $url .= '?' . http_build_query($params);
    // Headers
    $headers = [
      "Gstin:33GSPTN0591G1Z7",
      "Year:" . $FinancialYear->year,
      "Month:" . $monthDates['month'],
      "Authorization: JWT " . $token,
    ];

    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute the request
    $response = curl_exec($ch);
    // Check for errors
    if (curl_errno($ch)) {
      echo 'cURL error: ' . curl_error($ch);
    } else {
      $this->getGstrDataHtml($response);
      // Print response
      // echo $response;
    }
    // Close cURL
    curl_close($ch);
  }

  public function getGstrDataHtml($response)
  {
    $res = json_decode($response);
    if ($res->success == 1) {
      $invoice_data = $res->data->b2b;
      $table =  '<table id="gstr-table" class="table table-bordered table-hover table-striped" style="background:#ace7e7;" >'
        . '<thead class="bg-primary">'
        . '<tr>'
        . '<th><input type="checkbox" id="product-selectAll" /></th>'
        . '<th>Invoice</th>'
        . '<th>Date</th>'
        . '<th>Customer</th>'
        . '<th>Net Amount</th>'
        . '<th>Taxable Amount</th>'
        . '</tr>'
        . '</thead>'
        . '<tbody>';
      foreach ($invoice_data as $key => $data) {
        ++$key;
        $table .= '<tr>'
          .  '<td class="cheking">'
          .  '' . $key . ''
          .  '</td>'
          .  '<td> ' . $data->inum . ' </td>'
          .  '<td> ' . $data->idt . ' </td>'
          .  '<td> ' . $data->contact_name . ' </td>'
          .  '<td> ' . $data->net_amount . ' </td>'
          .  '<td> ' . $data->val . ' </td>'
          .  '</tr>';
      }
      $table .= '</tbody>'
        .  '</table>';
      echo $table;

      if (count($invoice_data) == 0) {
        echo '<table id="gstr-table" class="table table-bordered table-hover table-striped"  >'
          . '<tbody><tr><td colspan="6" style="text-align:center;" >No Record Found</td></tr></tbody></table>';
      }
    }
  }

  public function invoiceSummary()
  {
    $gstr_access_token = gstrAccessToken("invoice-summary");
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    // Headers
    $headers = [
      'gstin: 33GSPTN0591G1Z7',
      'month:' . $monthDates['month'],
      'year:' . $monthDates['financial_year']->year,
      'mode: Both',
      'Authorization: JWT ' . $token, // Replace {{token}} with your actual JWT token
    ];

    // Initialize cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $headers,
      CURLOPT_CUSTOMREQUEST => "GET",
    ]);
    $response = curl_exec($curl);
    if (curl_errno($curl)) {
      echo "cURL Error: " . curl_error($curl);
    } else {
      $this->invoiceSummaryHtml($response);
    }
    curl_close($curl);
  }

  public function invoiceSummaryHtml($res)
  {
    $result = json_decode($res);
    if ($result->success == 1) {
      $all_data = $result->data;
      $b2bData = $all_data->b2b;
      $b2csData = $all_data->b2cs;
      $hsnData = $all_data->hsn;
      $nil_ratedData = $all_data->nil_rated;
      $docData = $all_data->doc;
      $invoiceSummary = '<div style="background-color: #f1f1f1;padding:10px;" ><h2 style="text-align:center;"  >Invoice Summary</h2><div class="summary-section">
    <h4 >' . $b2bData->invoice_type . ' Transactions</h4>
    <table>
        <thead>
            <tr>
                <th>Total Invoice</th>
                <th>Invoice Value</th>
                <th>Taxable Value</th>
                <th>Total Tax</th> 
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>' . $b2bData->invoice_count . '</td> 
                <td>₹' . money_formats($b2bData->invoice_value) . '</td> 
                <td>₹' . money_formats($b2bData->taxable_value) . '</td> 
                <td>₹' . money_formats($b2bData->total_tax) . '</td> 
            </tr> 
        </tbody>
    </table>
</div>
<div class="summary-section">
        <h4>' . $b2csData->invoice_type . ' Transactions</h4>
        <table>
            <thead>
                <tr>
                <th>Total Invoice</th>
                <th>Invoice Value</th>
                <th>Taxable Value</th>
                <th>Total Tax</th> 
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>' . $b2csData->invoice_count . '</td> 
                <td>₹' . money_formats($b2csData->invoice_value) . '</td> 
                <td>₹' . money_formats($b2csData->taxable_value) . '</td> 
                <td>₹' . money_formats($b2csData->total_tax) . '</td> 
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <!-- HSN Report Section -->
    <div class="summary-section">
    <h4>' . $hsnData->invoice_type . ' Transactions</h4>
        <table>
            <thead>
                <tr> 
                <th>Invoice Value</th>
                <th>Taxable Value</th>
                <th>Total Tax</th> 
                </tr>
            </thead>
            <tbody>
                <tr> 
                <td>₹' . money_formats($hsnData->invoice_value) . '</td> 
                <td>₹' . money_formats($hsnData->taxable_value) . '</td> 
                <td>₹' . money_formats($hsnData->total_tax) . '</td> 
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <!-- nil_rated Report Section -->
    <div class="summary-section">
    <h4>' . $nil_ratedData->invoice_type . ' Transactions</h4>
        <table>
            <thead>
                <tr> 
                <th>NIL supply</th>
                <th>Exempted Supply</th>
                <th>Non GST Supply</th> 
                </tr>
            </thead>
            <tbody>
                <tr> 
                <td>' . $nil_ratedData->nil_supply . '</td> 
                <td>' . $nil_ratedData->exempted_supply . '</td> 
                <td>' . $nil_ratedData->non_gst_supply . '</td> 
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <!-- nil_rated Report Section -->
    <div class="summary-section">
    <h4>' . $docData->invoice_type . ' </h4>
        <table>
            <thead>
                <tr> 
                <th>Total issued</th>
                <th>Cancelled</th>
                <th>Net issued</th> 
                </tr>
            </thead>
            <tbody>
                <tr> 
                <td>' . $docData->total_issued . '</td> 
                <td>' . $docData->cancelled . '</td> 
                <td>' . $docData->net_issued . '</td> 
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    </div>';

      echo $invoiceSummary;
    }
  }

  public function uploadGstr1()
  {

    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $gstr_access_token = gstrAccessToken("upload-gstr1");
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    // Headers
    $headers = [
      'gstin: 33GSPTN0591G1Z7',
      'month:' . $monthDates['month'],
      'year:' . $monthDates['financial_year']->year,
      'invoice: Y',
      'summary: y',
      'Authorization: JWT ' . $token, // Replace {{token}} with your actual JWT token
    ];

    // Initialize cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $headers,
      CURLOPT_CUSTOMREQUEST => "POST",
    ]);
    $response = curl_exec($curl);
    if (curl_errno($curl)) {
      echo "cURL Error: " . curl_error($curl);
    } else {
      // echo $response;
      $this->uploadGstr1Html($response);
    }
    curl_close($curl);
  }

  public function uploadGstr1Html($jsonData)
  {
    $data = json_decode($jsonData, true);

    // Start HTML output
    echo "<html><body>";
    echo "<h1>Upload Gstr1</h1>";

    // Process invoice_result
    echo "<h2>Invoice Results</h2>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Invoice Type</th><th>Message</th><th>Status</th></tr>";
    foreach ($data['data']['invoice_result'] as $invoice) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($invoice['invoice_type'] ?: 'N/A') . "</td>";
      echo "<td>" . htmlspecialchars($invoice['message']) . "</td>";
      echo "<td>" . ($invoice['status'] ? 'Success' : 'Failure') . "</td>";
      echo "</tr>";
    }
    echo "</table>";

    // Process summary_result
    echo "<h2>Summary Results</h2>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Invoice Type</th><th>Message</th><th>Status</th><th>Additional Details</th></tr>";
    foreach ($data['data']['summary_result'] as $summary) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($summary['invoice_type']) . "</td>";

      if (is_array($summary['message'])) {
        $successMessage = $summary['message']['success']['message'] ?? 'N/A';
        echo "<td>" . htmlspecialchars($successMessage) . "</td>";

        $responses = $summary['message']['success']['responses'] ?? [];
        $details = "Form Type: " . htmlspecialchars($responses['form_typ'] ?? 'N/A') . ", ";
        $details .= "Status Code: " . htmlspecialchars($responses['status_cd'] ?? 'N/A') . ", ";
        $details .= "Action: " . htmlspecialchars($responses['action'] ?? 'N/A');
      } else {
        echo "<td>" . htmlspecialchars($summary['message']) . "</td>";
        $details = 'N/A';
      }

      echo "<td>" . ($summary['status'] ? 'Success' : 'Failure') . "</td>";
      echo "<td>" . htmlspecialchars($details) . "</td>";
      echo "</tr>";
    }
    echo "</table>";

    // End HTML output
    echo "</body></html>";
  }

  public function gstr1Summary()
  {
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $gstr_access_token = gstrAccessToken("get-gstr1-summary");
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    // Headers 
    // Headers for the API request
    $headers = [
      'gstin: 33GSPTN0591G1Z7',
      'month:' . $monthDates['month'],
      'year:' . $monthDates['financial_year']->year,
      'Authorization: JWT ' . $token, // Replace {{token}} with your actual JWT token
    ];
    // cURL initialization
    $ch = curl_init();
    // cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    // Execute the request and get the response
    $response = curl_exec($ch);
    // Check for cURL errors
    if (curl_errno($ch)) {
      echo "cURL Error: " . curl_error($ch);
    } else {
      // Print the response
      return $response;
    }

    // Close the cURL session
    curl_close($ch);
  }

  public function uploadHistory()
  {
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $gstr_access_token = gstrAccessToken("list-upload-history");
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    // Headers 
    // Headers for the API request
    $headers = [
      'gstin: 33GSPTN0591G1Z7',
      'month:' . $monthDates['month'],
      'year:' . $monthDates['financial_year']->year,
      'Authorization: JWT ' . $token, // Replace {{token}} with your actual JWT token
    ];
    // cURL initialization
    $ch = curl_init();
    // cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    // Execute the request and get the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
      echo "cURL Error: " . curl_error($ch);
    } else {
      // Print the response 
      // echo $response; 
      $this->uploadHistoryHtml($response);
    }
    // Close the cURL session
    curl_close($ch);
  }

  public function uploadHistoryHtml($response)
  {
    $result = json_decode($response);
    $column = 'db data';
    $db_data = $result->$column;
    echo '<div>';
    foreach ($result->data as $key => $value) {
      ++$key;
      $active_cls = "";
      if ($key == '1') {
        $active_cls = "active";
      }
      if ($value->type != "") {
        echo '<div class="tab ' . $active_cls . '" id="tab_' . $key . '" onclick="openSheet(' . $key . ')">' . strtoupper($value->type) . '</div>';
      }
    }
    echo ' </div>';
    echo "<pre>";
    foreach ($result->data as $key1 => $value) {
      ++$key1;
      $active_cls = "";
      if ($key1 == '1') {
        $active_cls = "active";
      }
      $key_value = $value->type;
      if ($key_value == "b2b") {
        $this->b2bReportHtml($db_data[$key1 - 1]->payload->$key_value, $key_value, $key1, $active_cls);
      } else if ($key_value == "b2cs") {
        $this->b2csReportHtml($db_data[$key1 - 1]->payload->$key_value, $key_value, $key1, $active_cls);
      } else if ($key_value == "hsn") {
        if (isset($db_data[$key1 - 1]->payload->$key_value->data)) {
          $this->hsnReportHtml($db_data[$key1 - 1]->payload->$key_value->data, $key_value, $key1, $active_cls);
        } else {
          $this->hsnReportHtml($db_data[$key1 - 1]->payload->$key_value->hsn_b2b, $key_value, $key1, $active_cls);
        }
      } else if ($key_value == "at") {
        $this->atReportHtml($db_data[$key1 - 1]->payload->$key_value, $key_value, $key1, $active_cls);
      } else if ($key_value == "cdnr") {
        $this->cdnrReportHtml($db_data[$key1 - 1]->payload->$key_value, $key_value, $key1, $active_cls);
      } else if ($key_value == "Document") {
        $this->documentReportHtml($db_data[$key1 - 1]->payload->doc_issue->doc_det[0]->docs, $key_value, $key1, $active_cls);
      } else if ($key_value == "nil") {
        $this->nilReportHtml($db_data[$key1 - 1]->payload->$key_value, $key_value, $key1, $active_cls);
      } else if ($key_value == "exp") {
        $exp_data = $db_data[$key1 - 1]->payload->$key_value;
        $this->expReportHtml($exp_data, $key_value, $key1, $active_cls);
      } else if ($key_value == "cdnra") {
        $cdnra_data = $db_data[$key1 - 1]->payload->$key_value;
        $this->cdnraReportHtml($cdnra_data, $key_value, $key1, $active_cls);
      } else if ($key_value == "b2ba") {
        $b2ba_data = $db_data[$key1 - 1]->payload->$key_value;
        $this->b2baReportHtml($b2ba_data, $key_value, $key1, $active_cls);
      } else if ($key_value == "txpda") {
        $txpda_data = $db_data[$key1 - 1]->payload->$key_value;
        $this->txpdaReportHtml($txpda_data, $key_value, $key1, $active_cls);
      } else if ($key_value == "txpd") {
        $txpd_data = $db_data[$key1 - 1]->payload->$key_value;
        $this->txpdReportHtml($txpd_data, $key_value, $key1, $active_cls);
      } else if ($key_value == "b2csa") {
        $b2csa_data = $db_data[$key1 - 1]->payload->$key_value;
        $this->b2csaReportHtml($b2csa_data, $key_value, $key1, $active_cls);
      } else if ($key_value =="cdna"){
        $cdna_data = $db_data[$key1 - 1]->payload->$key_value;
        $this->cdnaReportHtml($cdna_data, $key_value, $key1, $active_cls);
      }
      // $db_data[$key]->payload->$key_value
    }
  }

  public function cdnaReportHtml($cdna_data, $key_value, $key1, $active_cls){
    // that time dommy data is not found
    return true;
  }
  public function b2csaReportHtml($b2csa_data, $sheet_name, $key1, $active_cls)
  {
    $b2csa_data = isset($b2csa_data) ? $b2csa_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr>
    <th>pos</th>
    <th>omon</th>
    <th>sply_ty</th>
    <th>etin</th>
    <th>typ</th>
    <th>itms</th>
    </tr>';
    foreach ($b2csa_data as $value) {
      echo '<tr>
   <td>' . $value->pos . '</td> 
   <td>' . $value->omon . '</td>
   <td>' . $value->sply_ty . '</td>
   <td>' . $value->etin . '</td>
   <td>' . $value->typ . '</td>
   <td>' . count($value->itms) . '</td> 
   </tr>';
    }
    if (count($b2csa_data) == 0) {
      echo '<tr><td colspan="3" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function txpdReportHtml($txpd_data, $sheet_name, $key1, $active_cls)
  {
    $txpd_data = isset($txpd_data) ? $txpd_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr><th>pos</th><th>sply_ty</th><th>itms</th></tr>';

    foreach ($txpd_data as $value) {
      echo '<tr>
   <td>' . $value->pos . '</td> 
   <td>' . $value->sply_ty . '</td>
   <td>' . count($value->itms) . '</td> 
   </tr>';
    }
    if (count($txpd_data) == 0) {
      echo '<tr><td colspan="3" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function txpdaReportHtml($txpda_data, $sheet_name, $key1, $active_cls)
  {
    $txpda_data = isset($txpda_data) ? $txpda_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr><th>pos</th><th>omon</th><th>sply_ty</th><th>itms</th></tr>';

    foreach ($txpda_data as $value) {
      echo '<tr>
   <td>' . $value->pos . '</td>
   <td>' . $value->omon . '</td>
   <td>' . $value->sply_ty . '</td>
   <td>' . count($value->itms) . '</td> 
   </tr>';
    }
    if (count($txpda_data) == 0) {
      echo '<tr><td colspan="4" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function b2baReportHtml($b2ba_data, $sheet_name, $key1, $active_cls)
  {
    $b2ba_data = isset($b2ba_data) ? $b2ba_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr>
    <th>ctin</th>
    <th>itms</th>
    <th>inum</th> 
    <th>idt</th> 
    <th>pos</th> 
    <th>oinum</th> 
    <th>oidt</th> 
    <th>val</th> 
    <th>rchrg</th> 
    <th>inv_typ</th> 
    </tr>';

    foreach ($b2ba_data as $value) {
      foreach ($value->inv as $value1) {
        echo '<tr> 
   <td>' . $value->ctin . '</td> 
   <td>' . count($value1->itms) . '</td> 
   <td>' . $value1->inum . '</td>
   <td>' . $value1->idt . '</td>
   <td>' . $value1->pos . '</td> 
   <td>' . $value1->oinum . '</td> 
   <td>' . $value1->oidt . '</td> 
   <td>' . $value1->val . '</td> 
   <td>' . $value1->rchrg . '</td> 
   <td>' . $value1->inv_typ . '</td> 
   </tr>';
      }
    }
    if (count($b2ba_data) == 0) {
      echo '<tr><td colspan="10" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function cdnraReportHtml($cdnra_data, $sheet_name, $key1, $active_cls)
  {
    $cdnra_data = isset($cdnra_data) ? $cdnra_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr>
    <th>ctin</th>
    <th>itms</th>
    <th>val</th>
    <th>nt_num</th>
    <th>nt_dt</th>
    <th>ont_num</th>
    <th>ont_dt</th>
    <th>ntty</th>
    <th>pos</th>
    <th>rchrg</th>
    <th>inv_typ</th>
    </tr>';

    foreach ($cdnra_data as $value) {
      foreach ($value->nt as $value1) {
        echo '<tr> 
   <td>' . $value->ctin . '</td> 
   <td>' . count($value1->itms) . '</td> 
   <td>' . $value1->val . '</td>
   <td>' . $value1->nt_num . '</td>
   <td>' . $value1->nt_dt . '</td>
   <td>' . $value1->ont_num . '</td>
   <td>' . $value1->ont_dt . '</td>
   <td>' . $value1->ntty . '</td>
   <td>' . $value1->pos . '</td>
   <td>' . $value1->rchrg . '</td>
   <td>' . $value1->inv_typ . '</td>
   </tr>';
      }
    }
    if (count($cdnra_data) == 0) {
      echo '<tr><td colspan="11" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function expReportHtml($exp_data, $sheet_name, $key1, $active_cls)
  {
    $exp_data = isset($exp_data) ? $exp_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr>
    <th>itms</th>
    <th>inum</th>
    <th>idt</th>
    <th>val</th>
    <th>sbpcode</th>
    <th>sbnum</th>
    <th>sbdt</th>
    </tr>';

    foreach ($exp_data as $value) {
      echo '<tr><td colspan="7" style="background: #b8c5e1;text-align:center;" >' . $value->exp_typ . '</td></tr>';
      foreach ($value->inv as $value1) {
        echo '<tr>
        <td>' . count($value1->itms) . '</td>
        <td>' . $value1->inum . '</td>
        <td>' . $value1->idt . '</td>
        <td>' . $value1->val . '</td> 
        <td>' . $value1->sbpcode . '</td> 
        <td>' . $value1->sbnum . '</td> 
        <td>' . $value1->sbdt . '</td> 
        </tr>';
      }
    }

    if (count($exp_data) == 0) {
      echo '<tr><td colspan="4" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }
  public function nilReportHtml($nil_data, $sheet_name, $key1, $active_cls)
  {
    $nil_data = isset($nil_data->inv) ? $nil_data->inv : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr><th>nil_amt</th><th>expt_amt</th><th>ngsup_amt</th><th>sply_ty</th></tr>';

    foreach ($nil_data as $value) {
      echo '<tr>
   <td>' . $value->nil_amt . '</td>
   <td>' . $value->expt_amt . '</td>
   <td>' . $value->ngsup_amt . '</td>
   <td>' . $value->sply_ty . '</td> 
   </tr>';
    }
    if (count($nil_data) == 0) {
      echo '<tr><td colspan="4" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function documentReportHtml($db_data, $sheet_name, $key1, $active_cls)
  {
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr><th>S No.</th><th>from</th><th>to</th><th>totnum</th><th>cancel</th><th>net_issue</th></tr>';
    $doc_issue_payload = isset($db_data) ? $db_data : [];
    foreach ($doc_issue_payload as $value) {
      echo '<tr>
   <td>' . $value->num . '</td>
   <td>' . $value->from . '</td>
   <td>' . $value->to . '</td>
   <td>' . $value->totnum . '</td>
   <td>' . $value->cancel . '</td>
   <td>' . $value->net_issue . '</td>
   </tr>';
    }
    if (count($doc_issue_payload) == 0) {
      echo '<tr><td colspan="6" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function cdnrReportHtml($txpda, $sheet_name, $key1, $active_cls)
  {
    $txpda = isset($txpda[0]->nt) ? $txpda[0]->nt : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
        <h3>' . $sheet_name . '</h3>
        <table border="1">';
    echo '<tr> 
        <th>nt_num</th>
        <th>val</th>
        <th>nt_dt</th>
        <th>rchrg</th>
        <th>inv_typ</th>
        <th>ntty</th>
        <th>pos</th>
        <th>itms Count</th> 
        </tr>';
    foreach ($txpda as $value) {
      echo '<tr>
          <td>' . $value->nt_num . '</td>
          <td>' . money_formats($value->val) . '</td>
          <td>' . $value->nt_dt . '</td>
          <td>' . $value->rchrg . '</td>
          <td>' . $value->inv_typ . '</td>
          <td>' . $value->ntty . '</td>
          <td>' . $value->pos . '</td>
          <td>' . count($value->itms) . '</td> 
          </tr>';
    }
    if (count($txpda) == 0) {
      echo '<tr><td colspan="4" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function atReportHtml($at_data, $sheet_name, $key1, $active_cls)
  {
    $data_at = isset($at_data) ? $at_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
   <h3>' . $sheet_name . '</h3>
   <table border="1">';
    echo '<tr> 
   <th>pos</th> 
   <th>sply_ty</th>
   <th>itms Count</th> 
   </tr>';
    foreach ($data_at as $value) {
      echo '<tr>
     <td>' . $value->pos . '</td> 
     <td>' . $value->sply_ty . '</td>
     <td>' . count($value->itms) . '</td> 
     </tr>';
    }
    if (count($data_at) == 0) {
      echo '<tr><td colspan="3" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function hsnReportHtml($hsn_data, $sheet_name, $key1, $active_cls)
  {
    $hsn_b2b = isset($hsn_data) ? $hsn_data : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr>
  <th>S No.</th>
  <th>HSN SC</th>
  <th>Tax</th>
  <th>uqc</th>
  <th>qty</th>
  <th>txval</th>
  <th>iamt</th>
  <th>desc</th>
  <th>csamt</th>
  <th>camt</th>
  <th>samt</th>
  </tr>';

    foreach ($hsn_b2b as $value) {
      echo '<tr>
    <td>' . $value->num . '</td>
    <td>' . $value->hsn_sc . '</td>
    <td>' . $value->rt . '</td>
    <td>' . $value->uqc . '</td>
    <td>' . $value->qty . '</td>
    <td>' . $value->txval . '</td>
    <td>' . $value->iamt . '</td>
    <td>' . $value->desc . '</td>
    <td>' . $value->csamt . '</td>
    <td>' . $value->camt . '</td>
    <td>' . $value->samt . '</td>
    </tr>';
    }
    if (count($hsn_b2b) == 0) {
      echo '<tr><td colspan="11" style="text-align:center;" >No Record Found</td></tr>';
    }

    echo '</table></div>';
  }

  public function b2csReportHtml($b2cs, $sheet_name, $key1, $active_cls)
  {
    $b2cs = isset($b2cs) ? $b2cs : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr> 
    <th>pos</th> 
    <th>Tax</th>
    <th>txval</th> 
    <th>iamt</th> 
    <th>csamt</th> 
    <th>sply_ty</th> 
    <th>typ</th> 
    </tr>';
    foreach ($b2cs as $value) {
      echo '<tr>
      <td>' . $value->pos . '</td> 
      <td>' . $value->rt . '</td> 
      <td>' . $value->txval . '</td> 
      <td>' . $value->iamt . '</td> 
      <td>' . $value->csamt . '</td> 
      <td>' . $value->sply_ty . '</td> 
      <td>' . $value->typ . '</td>  
      </tr>';
    }
    if (count($b2cs) == 0) {
      echo '<tr><td colspan="7" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function b2bReportHtml($b2b, $sheet_name, $key1, $active_cls)
  {
    $inv_data = isset($b2b[0]->inv) ? $b2b[0]->inv : [];
    echo '<div id="sheet' . $key1 . '" class="sheet ' . $active_cls . '">
    <h3>' . $sheet_name . '</h3>
    <table border="1">';
    echo '<tr> 
    <th>inum</th>
    <th>idt</th> 
    <th>pos</th> 
    <th>rchrg</th> 
    <th>inv_typ</th> 
    <th>val</th> 
    <th>itms</th> 
    </tr>';
    foreach ($inv_data as $value) {
      echo '<tr>
      <td>' . $value->inum . '</td> 
      <td>' . $value->idt . '</td> 
      <td>' . $value->pos . '</td> 
      <td>' . $value->rchrg . '</td> 
      <td>' . $value->inv_typ . '</td> 
      <td>' . $value->val . '</td>  
      <td>' . count($value->itms) . '</td> 
      </tr>';
    }
    if (count($inv_data) == 0) {
      echo '<tr><td colspan="7" style="text-align:center;" >No Record Found</td></tr>';
    }
    echo '</table></div>';
  }

  public function proceedFile()
  {
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $gstr_access_token = gstrAccessToken("proceed-file");
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    // Headers 
    // Headers for the API request
    $headers = [
      'gstin: 33GSPTN0591G1Z7',
      'month:' . $monthDates['month'],
      'year:' . $monthDates['financial_year']->year,
      'Authorization: JWT ' . $token, // Replace {{token}} with your actual JWT token
      'qa:https://qa-enterprise.mastersindia.co',
    ];
    // cURL initialization
    $ch = curl_init();
    // cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

    // Execute the request and get the response
    $response = curl_exec($ch);
    // Check for cURL errors
    if (curl_errno($ch)) {
      echo  curl_error($ch);
    } else {
      // Print the response
      // $response = json_decode($response); 
      echo $response;
    }
    // Close the cURL session
    curl_close($ch);
  }

  public function fileSubmit()
  {
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $gstr_access_token = gstrAccessToken("file-submit");
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    // Headers 
    // Headers for the API request
    $headers = [
      'gstin: 33GSPTN0591G1Z7',
      'year:' . $monthDates['financial_year']->year,
      'month:' . $monthDates['month'],
      'Authorization: JWT ' . $token, // Replace {{token}} with your actual JWT token 
    ];
    // cURL initialization
    $ch = curl_init();
    // cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

    // Execute the request and get the response
    $response = curl_exec($ch);
    // Check for cURL errors
    if (curl_errno($ch)) {
      echo  curl_error($ch);
    } else {
      // Print the response
      // $response = json_decode($response); 
      echo $response;
    }
    // Close the cURL session
    curl_close($ch);
  }

  public function gstr1EvcOtp()
  {
    $selected_month = $this->input->post('selected_month');
    $monthDates = monthDates($selected_month);
    $gstr_access_token = gstrAccessToken("gstr1_evc_otp");
    $url = $gstr_access_token['apiUrl'];
    $token = $gstr_access_token['token'];
    // Headers 
    // Headers for the API request
    $headers = [
      'gstin: 33GSPTN0591G1Z7',
      'month:' . $monthDates['month'],
      'year:' . $monthDates['financial_year']->year,
      'Authorization: JWT ' . $token, // Replace {{token}} with your actual JWT token
      'qa:https://qa-enterprise.mastersindia.co',
    ];
    // cURL initialization
    $ch = curl_init();
    // cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    // Execute the request and get the response
    $response = curl_exec($ch);
    // Check for cURL errors
    if (curl_errno($ch)) {
      echo  curl_error($ch);
    } else {
      // Print the response
      // $response = json_decode($response); 
      echo $response;
    }
    // Close the cURL session
    curl_close($ch);
  }
 
  
}
