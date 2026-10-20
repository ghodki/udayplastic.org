<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set("Asia/Calcutta");
    
    class Comp_profile extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);
            $this->load->model('user_model');
            
            if ($this->session->userdata('RoleType')) {
                if (empty($this->session->userdata('UserId'))) {
                    return redirect(base_url() . "signin");
                }
            } else {
                if (empty($this->session->userdata('user_id'))) {
                    return redirect(base_url() . "signin");
                }
            }
	}

	public function index()
	{
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['comp_profile'] = $this->user_model->getRow('comp_profile', $where);
            if (is_object($data['comp_profile'])) {
                if (!isset($data['comp_profile']->saleTemplate2)) {
                    $data['comp_profile']->saleTemplate2 = null;
                }
                if (!isset($data['comp_profile']->sms_status)) {
                    $data['comp_profile']->sms_status = null;
                }
            }

            $data['page'] = 'comp_profile/index';  
            return $this->load->view('inventory_sep/inventory', $data);
	}
        
	public function save()
	{
            if ($_POST) {
                $data['name'] = $this->input->post('name');
                $data['content'] = $this->input->post('content');
                $data['bank_details'] = $this->input->post('bank_details');
                $data['subject_to'] = $this->input->post('subject_to');
                $data['user_id'] = $this->session->userdata('user_id');
                $data['comp_id'] = $this->session->userdata('comp_id');
                $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $data['dcno'] = $this->input->post('dcno');
                $data['dcdate'] = $this->input->post('dcdate');
                $data['dpb'] = $this->input->post('dpb');
                $data['ptd'] = $this->input->post('ptd');
                $data['duedate'] = $this->input->post('duedate');
                $data['lrnumber'] = $this->input->post('lrnumber');
                $data['eway'] = $this->input->post('eway');
                $data['lrdate'] = $this->input->post('lrdate');
                $data['barcode_con'] = $this->input->post('barcode_con');
                
                $unknown = [
                    'barcode' => $this->input->post('barcode_con'),
                    'lrdate' => $this->input->post('lrdate'),
                    'eway' => $this->input->post('eway'),
                    'lrnumber' => $this->input->post('lrnumber'),
                    'duedate' => $this->input->post('duedate'),
                    'ptd' => $this->input->post('ptd'),
                    'dpb' => $this->input->post('dpb'),
                    'dcdate' => $this->input->post('dcdate'),
                    'dcno' => $this->input->post('dcno')
                ];
                $this->session->set_userdata($unknown);
                
                $result = $this->user_model->saveAry('comp_profile', $data);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'comp_profile');
            }
	}

	public function delete($id)
	{
            if (isset($id) && $id != '') {
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $return = $this->user_model->deleteRow('comp_profile', $where);
                if ($return) {
                    $this->session->unset_userdata('barcode');
                    $this->session->unset_userdata('lrdate');
                    $this->session->unset_userdata('eway');
                    $this->session->unset_userdata('lrnumber');
                    $this->session->unset_userdata('duedate');
                    $this->session->unset_userdata('ptd');
                    $this->session->unset_userdata('dpb');
                    $this->session->unset_userdata('dcdate');
                    $this->session->unset_userdata('dcno');
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect(base_url() . 'comp_profile/index');
            }
	}

	public function edit($id)
	{
            if (isset($id) && $id != '') {
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->getRow('comp_profile', $where);
                if ($return) {
                    $data['comp_profile'] = $return;
                    $data['page'] = 'inventory_sep/comp_profile/edit';
                    return $this->load->view('inventory', $data);
                } else {
                    $data['page'] = 'admin_details/userlist';
                    $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect(base_url() . 'comp_profile/index');
            }
	}

	public function update()
	{
            $id = $this->input->post('id');
            if ($id) {
                $data['name'] = $this->input->post('name');
                $data['content'] = $this->input->post('content');
                $data['subject_to'] = $this->input->post('subject_to');
                $data['bank_details'] = $this->input->post('bank_details');
                $data['dcno'] = $this->input->post('dcno');
                $data['dcdate'] = $this->input->post('dcdate');
                $data['dpb'] = $this->input->post('dpb');
                $data['ptd'] = $this->input->post('ptd');
                $data['duedate'] = $this->input->post('duedate');
                $data['lrnumber'] = $this->input->post('lrnumber');
                $data['eway'] = $this->input->post('eway');
                $data['lrdate'] = $this->input->post('lrdate');
                $data['barcode_con'] = $this->input->post('barcode_con');
                    
                $unknown = [
                    'barcode' => $this->input->post('barcode_con'),
                    'lrdate' => $this->input->post('lrdate'),
                    'eway' => $this->input->post('eway'),
                    'lrnumber' => $this->input->post('lrnumber'),
                    'duedate' => $this->input->post('duedate'),
                    'ptd' => $this->input->post('ptd'),
                    'dpb' => $this->input->post('dpb'),
                    'dcdate' => $this->input->post('dcdate'),
                    'dcno' => $this->input->post('dcno')
                ];
                $this->session->set_userdata($unknown);
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
	}

	public function update_permiss()
	{
            $id = $this->input->post('id');
            if ($id) {
                $data['dcno'] = $this->input->post('dcno');
                $data['dcdate'] = $this->input->post('dcdate');
                $data['dpb'] = $this->input->post('dpb');
                $data['ptd'] = $this->input->post('ptd');
                $data['discount'] = $this->input->post('discount');
                $data['duedate'] = $this->input->post('duedate');
                $data['due_date'] = $this->input->post('due_date');
                $data['lrnumber'] = $this->input->post('lrnumber');
                $data['eway'] = $this->input->post('eway');
                $data['lrdate'] = $this->input->post('lrdate');
                $data['barcode_con'] = $this->input->post('barcode_con');
                $data['vahical_no'] = $this->input->post('vahical_no');
                $data['SalesmanOnPrintOut'] = $this->input->post('SalesmanOnPrintOut');
                $data['orderDate'] = $this->input->post('orderDate');
                $data['orderNumber'] = $this->input->post('order_number');
                $data['lot_no'] = $this->input->post('lot_no');
                $data['lh'] = $this->input->post('lh');
                $data['estimate_format'] = $this->input->post('estimate_format');
                $data['estimate_stock'] = $this->input->post('estimate_stock');
                $data['salesman_sale'] = $this->input->post('salesman_sale');
                $data['salesman_estimate'] = $this->input->post('salesman_estimate');
                $data['pos'] = $this->input->post('pos');
                $data['party_wise_rate'] = $this->input->post('party_wise_rate');
                $data['ProductStockCheck'] = $this->input->post('ProductStockCheck');
                $data['formE'] = $this->input->post('formE');
                $data['eInvoice'] = $this->input->post('eInvoice');
                $data['eCRN_note'] = $this->input->post('eCRN_note');
                $data['eDBN_note'] = $this->input->post('eDBN_note');
                $data['gstVerifiedParty'] = $this->input->post('gstVerifiedParty');
                $data['gstr1Status'] = $this->input->post('gstr1Status');
                 
                $unknown = [
                    'barcode' => $this->input->post('barcode_con'),
                    'lrdate' => $this->input->post('lrdate'),
                    'eway' => $this->input->post('eway'),
                    'lrnumber' => $this->input->post('lrnumber'),
                    'duedate' => $this->input->post('duedate'),
                    'ptd' => $this->input->post('ptd'),
                    'dpb' => $this->input->post('dpb'),
                    'dcdate' => $this->input->post('dcdate'),
                    'dcno' => $this->input->post('dcno'),
                    'discount' => $this->input->post('discount'),
                    'estimate_format' => $this->input->post('estimate_format'),
                    'estimate_stock' => $this->input->post('estimate_stock'),
                    'salesman_sale' => $this->input->post('salesman_sale'),
                    'salesman_estimate' => $this->input->post('salesman_estimate')
                ];
                $this->session->set_userdata($unknown);

                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
	}

	public function update_subj()
	{
            $id = $this->input->post('id');
            if ($id) {
                $data['subject_to'] = $this->input->post('subject_to');
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $result = $this->user_model->updateAry('comp_profile', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
	}

	public function update_total_invoice()
	{
            $id = $this->input->post('id');
            if ($id) {
                $beta['total_invoice'] = $this->input->post('total_invoice');
                $beta['title_invoice'] = $this->input->post('title');
                $beta['estimate'] = $this->input->post('estimate');
                $beta['comp_details_position'] = $this->input->post('comp_details_position');
                $beta['logo_position'] = $this->input->post('logo_position');
                $beta['logo_status'] = $this->input->post('logo_status');
                $beta['signature_display'] = $this->input->post('signature_display');
                $beta['taxcal'] = $this->input->post('taxcal');
                $beta['voucherType'] = $this->input->post('voucherType');
                $beta['logo_width'] = $this->input->post('logo_size_width');
                $beta['invoicePayment'] = $this->input->post('invoicePayment');
                $beta['payment_due_period'] = $this->input->post('payment_due_period');
                $beta['logo_height'] = $this->input->post('logo_size_height');
                $beta['signature_height'] = $this->input->post('signature_height');
                $beta['signature_width'] = $this->input->post('signature_width');
                $beta['qr_status'] = $this->input->post('qr_status');
                $beta['qr_position'] = $this->input->post('qr_position');
                $beta['qr_width'] = $this->input->post('qr_size_width');
                $beta['qr_height'] = $this->input->post('qr_size_height');
                $beta['item_per_page'] = $this->input->post('item_per_page');

                $file_name = $_FILES['logo']['name'];
                if ($file_name) {
                    move_uploaded_file($_FILES['logo']['tmp_name'], 'uploads/complogo/' . $file_name);
                    $beta['logo'] = $file_name;
                }
                
                $scan_signature = $_FILES['scan_signature']['name'];
                if ($scan_signature) {
                    $scan_signature_name = time() . $scan_signature;
                    move_uploaded_file($_FILES['scan_signature']['tmp_name'], 'uploads/signature/' . $scan_signature_name);
                    $beta['scan_signature'] = $scan_signature_name;
                }
                
                $payment_qr = $_FILES['payment_qr']['name'];
                if ($payment_qr) {
                    $payment_qr_name = time() . $payment_qr; 
                    move_uploaded_file($_FILES['payment_qr']['tmp_name'], 'uploads/payment_qr/' . $payment_qr_name);
                    $beta['payment_qr'] = $payment_qr_name;
                }

                if ($this->session->userdata('BranchRole')) {
                    $betas['BillingFormat'] = $this->input->post('cate_id');
                    $there = ['Id' => $this->session->userdata('CompBranchId')];
                    $this->user_model->updateAry('companybranch', $betas, $there);
                } else {
                    $betas['billing_format_id'] = $this->input->post('cate_id');
                    $there = ['id' => $this->session->userdata('comp_id')];
                    $this->user_model->updateAry('inventory_billing', $betas, $there);
                }

                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $beta, $where);
                if ($result) {
                    $this->session->set_userdata('format_of_bill', $this->input->post('cate_id'));
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
	}

	public function update_comp_info()
	{
            $id = $this->session->userdata('comp_id');

            if ($id && !empty($id)) {
                if ($this->session->userdata('BranchRole')) {
                    $data['BranchName'] = $this->input->post('name');
                    $data['Address'] = $this->input->post('address');
                    $data['GST'] = $this->input->post('gst');
                    $data['Mobile'] = $this->input->post('number');
                    $data['ContactPerson'] = $this->input->post('contact');
                    $data['Email'] = $this->input->post('email');
                    $data['AccountingType'] = $this->input->post('ac');
                    $data['State'] = $this->input->post('state');
                    $data['FinancialYear'] = $this->input->post('year');
                    
                    $data['gstr1Mode']="TEST";
                    $data['gstr1AccessToken']="";
                    $data['gstr1_token_expire']="";
                    if($this->input->post('gstr1Mode')=="LIVE"){ 
                        $data['gstr1Mode'] = $this->input->post('gstr1Mode');
                    }
                    
                    $data['ewayBillApiMode']="TEST";
                    if($this->input->post('ewayBillApiMode')=="LIVE"){
                    $data['ewayBillApiMode'] = $this->input->post('ewayBillApiMode');
                    }

                    $data['eInvoiceApiMode'] ="TEST";
                    if($this->input->post('eInvoiceApiMode')=="LIVE"){
                    $data['eInvoiceApiMode'] = $this->input->post('eInvoiceApiMode');
                    }
                    
                    $data['gsp_access_token']=GST_ACCESS_TOKEN;
                    $data['gsp_access_token_expire']=time();
                    if(($data['eInvoiceApiMode']=="LIVE")||($data['ewayBillApiMode']=="LIVE")){
                    $data['gsp_access_token']=GST_ACCESS_TOKEN_LIVE;
                    $data['gsp_access_token_expire']=time(); 
                    }
                    
                    $data['pincode'] = $this->input->post('pincode');
                    $data['eWayUserDetails']=json_encode($this->input->post('eWayUserDetails'));
                    $data['eInvoiceUserDetails']=json_encode($this->input->post('eInvoiceUserDetails'));
                    
                    if($this->input->post('ledger_gst_details')){
                        $data['gst_details'] = $this->input->post('ledger_gst_details');	
                        }
                    $where = ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    $result = $this->user_model->updateAry('companybranch', $data, $where);

                    if ($result) {
                        $city_id = get_title('districts', ['name' => $this->input->post('city')], 'id');
                        if ($city_id) {
                            $only_city['city'] = $city_id;
                            $this->user_model->updateAry('companybranch', $only_city, $where);
                        } else {
                            $city_data['name'] = $this->input->post('city');
                            $city_data['state_id'] = 28;
                            $res['city'] = $this->user_model->saveAry('districts', $city_data);
                            $this->user_model->updateAry('companybranch', $res, $where);
                        } 
                    }

                } else {
                    $data['company_name'] = $this->input->post('name');
                    $data['address'] = $this->input->post('address');
                    $data['gst_no'] = $this->input->post('gst');
                    $data['number'] = $this->input->post('number');
                    $data['contact_person'] = $this->input->post('contact');
                    $data['email'] = $this->input->post('email');
                    $data['accounting'] = $this->input->post('ac');
                    $data['state'] = $this->input->post('state');
                    $data['financial_year'] = $this->input->post('year');
                    
                    $data['gstr1Mode']="TEST";
                    $data['gstr1AccessToken']="";
                    $data['gstr1_token_expire']="";
                    if($this->input->post('gstr1Mode')=="LIVE"){ 
                    $data['gstr1Mode'] = $this->input->post('gstr1Mode');
                    }
                 
                    $data['ewayBillApiMode']="TEST";
                    if($this->input->post('ewayBillApiMode')=="LIVE"){
                    $data['ewayBillApiMode'] = $this->input->post('ewayBillApiMode');
                    }

                    $data['eInvoiceApiMode'] ="TEST";
                    if($this->input->post('eInvoiceApiMode')=="LIVE"){
                    $data['eInvoiceApiMode'] = $this->input->post('eInvoiceApiMode');
                    }
                    
                    $data['gsp_access_token']=GST_ACCESS_TOKEN;
                    $data['gsp_access_token_expire']=time();
                    if(($data['eInvoiceApiMode']=="LIVE")||($data['ewayBillApiMode']=="LIVE")){
                        $data['gsp_access_token']=GST_ACCESS_TOKEN_LIVE;
                        $data['gsp_access_token_expire']=time(); 
                    }
                    
                    $data['pincode'] = $this->input->post('pincode');
                    
                    $data['eWayUserDetails']=json_encode($this->input->post('eWayUserDetails'));
                    $data['eInvoiceUserDetails']=json_encode($this->input->post('eInvoiceUserDetails'));
                    
                    if($this->input->post('ledger_gst_details')){
                    $data['gst_details'] = $this->input->post('ledger_gst_details');	
                    }
                    $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id')];
                    $result = $this->user_model->updateAry('inventory_billing', $data, $where);

                    if ($result) {
                        $city_id = get_title('districts', ['name' => $this->input->post('city')], 'id');
                        if ($city_id) {
                            $only_city['city'] = $city_id;
                            $this->user_model->updateAry('inventory_billing', $only_city, $where);
                        } else {
                            $city_data['name'] = $this->input->post('city');
                            $city_data['state_id'] = 28;
                            $res['city'] = $this->user_model->saveAry('districts', $city_data);
                            $this->user_model->updateAry('inventory_billing', $res, $where);
                        } 
                    }

                }
                
                $beta['name'] = $this->input->post('name');
                $beta['panNumber'] = $this->input->post('panNumber');
                $beta['phoneNumber'] = $this->input->post('phoneNumber');
                $beta['financial_year'] = $this->input->post('year');
                $beta['category'] = $this->input->post('category');
                $beta['CashOpeningBalance'] = $this->input->post('CashOpeningBalance');
                $beta['BankOpeningBalance'] = $this->input->post('BankOpeningBalance');
                $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $beta, $where);
                $this->user_model->updateAry('year', ['Flag' => 0], ['id !=' => $this->input->post('year')]);
                $this->user_model->updateAry('year', ['Flag' => 1], ['id' => $this->input->post('year')]);
                        
                if ($result) {
                    $this->session->set_userdata('state', $this->input->post('state'));
                    $this->session->set_userdata('address', $this->input->post('address'));
                    $this->session->set_userdata('financial_year', $this->input->post('year'));
                    $this->session->set_flashdata(['msg' => 'Record updated Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    redirect(base_url() . 'comp_profile');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'could not find data.', 'class' => 'danger']);
                redirect(base_url() . 'comp_profile');
            }
	}

	public function update_tc()
	{
            $id = $this->input->post('id');
            if ($id) {
                $data['content'] = $this->input->post('content');
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
	}

	public function update_bank()
	{
            $id = $this->input->post('id');
            if ($id) {
                $data['bank_details'] = $this->input->post('bank_details');
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
	}

        public function update_payment()
        {
            $id = $this->input->post('id');
            if ($id) {
                $data['saleTemplate2'] = $this->input->post('saleTemplate2');
                $data['sms_status'] = $this->input->post('sms_status');
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
        }

	public function update_barcode_config()
	{
            $this->form_validation->set_rules('comp_id', 'Company', 'trim|required');
            $this->form_validation->set_rules('barcode_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('barcode_mrp_prefix', 'MRP PRFIX', 'trim');
            $this->form_validation->set_rules('barcode_sell_prefix', 'SELL PREFIX', 'trim');
            $this->form_validation->set_rules('barcode_purchase_prefix', 'PURCHASE PREFIX', 'trim');
            $this->form_validation->set_rules('barcode_generator', 'Barcode Gnerator', 'trim|required');
            $this->form_validation->set_rules('item1', 'Item1', 'trim|required');
            $this->form_validation->set_rules('item2', 'Item2', 'trim|required');
            $this->form_validation->set_rules('item3', 'Item3', 'trim|required');
            $this->form_validation->set_rules('price_code', 'Price Code', 'trim|required');
            $this->form_validation->set_rules('barcode_type', 'Barcode Type', 'trim');
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $contactData = array(
                    'barcode_title' => $this->input->post('barcode_title', true),
                    'barcode_mrp_prefix' => $this->input->post('barcode_mrp_prefix', true),
                    'barcode_sell_prefix' => $this->input->post('barcode_sell_prefix', true),
                    'barcode_purchase_prefix' => $this->input->post('barcode_purchase_prefix', true),
                    'barcode_generator' => $this->input->post('barcode_generator', true),
                    'item1' => $this->input->post('item1', true),
                    'item2' => $this->input->post('item2', true),
                    'item3' => $this->input->post('item3', true),
                    'price_code' => $this->input->post('price_code', true),
                    'barcode_pre_row' => $this->input->post('barcode_pre_row'),
                    'barcode_type' => $this->input->post('barcode_type')
                );

                $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $res = $this->user_model->getRow('comp_profile', $where);
                if ($res) {
                    $this->user_model->updateAry('comp_profile', $contactData, $where);
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3 class='text-success text-center'>Barcode Config Updated</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3 class='text-danger text-center'>Something Went Wrong</h3>"
                    );
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function mrp_barcode_config()
	{
            $this->form_validation->set_rules('mrp_id', 'MRP ID', 'trim|required');
            $this->form_validation->set_rules('zero', 'Zero', 'trim|required');
            $this->form_validation->set_rules('first', 'First', 'trim');
            $this->form_validation->set_rules('second', 'Second', 'trim');
            $this->form_validation->set_rules('third', 'Third', 'trim');
            $this->form_validation->set_rules('fourth', 'fourth', 'trim|required');
            $this->form_validation->set_rules('fifth', 'Fifth', 'trim');
            $this->form_validation->set_rules('sixth', 'sixth', 'trim');
            $this->form_validation->set_rules('seventh', 'seventh', 'trim');
            $this->form_validation->set_rules('eight', 'eight', 'trim');
            $this->form_validation->set_rules('nine', 'nine', 'trim');

            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $contactData = array(
                    'zero' => $this->input->post('zero', true),
                    'first' => $this->input->post('first', true),
                    'second' => $this->input->post('second', true),
                    'third' => $this->input->post('third', true),
                    'fourth' => $this->input->post('fourth', true),
                    'fifth' => $this->input->post('fifth', true),
                    'sixth' => $this->input->post('sixth', true),
                    'seventh' => $this->input->post('seventh', true),
                    'eight' => $this->input->post('eight', true),
                    'nine' => $this->input->post('nine', true),
                    'comp_id' => $this->session->userdata('comp_id'),
                    'user_id' => $this->session->userdata('user_id'),
                    'CompanyBranchId' => $this->session->userdata('CompBranchId')
                );

                $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'id' => $this->input->post('mrp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $res = $this->user_model->getRow('mrp_code', $where);
                if ($res) {
                    $response = $this->user_model->updateAry('mrp_code', $contactData, $where);
                    $ResultResponse = array(
                        'status' => 'success',
                        'message' => "<h3 class='text-success text-center'>Code Generated Updated</h3>",
                        'data' => $this->input->post('mrp_id')
                    );
                } else {
                    $response = $this->user_model->saveAry('mrp_code', $contactData);
                    $ResultResponse = array(
                        'status' => 'success',
                        'message' => "<h3 class='text-success text-center'>Code Generated Updated</h3>",
                        'data' => $response,
                    );
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($ResultResponse));
	}

	public function purchase_barcode_config()
	{
            $this->form_validation->set_rules('zero', 'Zero', 'trim|required');
            $this->form_validation->set_rules('first', 'First', 'trim');
            $this->form_validation->set_rules('second', 'Second', 'trim');
            $this->form_validation->set_rules('third', 'Third', 'trim');
            $this->form_validation->set_rules('fourth', 'fourth', 'trim|required');
            $this->form_validation->set_rules('fifth', 'Fifth', 'trim');
            $this->form_validation->set_rules('sixth', 'sixth', 'trim');
            $this->form_validation->set_rules('seventh', 'seventh', 'trim');
            $this->form_validation->set_rules('eight', 'eight', 'trim');
            $this->form_validation->set_rules('nine', 'nine', 'trim');
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $contactData = array(
                    'zero' => $this->input->post('zero', true),
                    'first' => $this->input->post('first', true),
                    'second' => $this->input->post('second', true),
                    'third' => $this->input->post('third', true),
                    'fourth' => $this->input->post('fourth', true),
                    'fifth' => $this->input->post('fifth', true),
                    'sixth' => $this->input->post('sixth', true),
                    'seventh' => $this->input->post('seventh', true),
                    'eight' => $this->input->post('eight', true),
                    'nine' => $this->input->post('nine', true),
                    'comp_id' => $this->session->userdata('comp_id'),
                    'user_id' => $this->session->userdata('user_id'),
                    'CompanyBranchId' => $this->session->userdata('CompBranchId')
                );
                
                $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'id' => $this->input->post('purchase_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $res = $this->user_model->getRow('purchase_code', $where);
                if ($res) {
                    $response = $this->user_model->updateAry('purchase_code', $contactData, $where);
                    $ResultResponse = array(
                        'data' => $this->input->post('purchase_id'),
                        'status' => 'success',
                        'message' => "<h3 class='text-success text-center'>Code Generated Updated</h3>"
                    );
                } else {
                    $response = $this->user_model->saveAry('purchase_code', $contactData);
                    $ResultResponse = array(
                        'data' => $response,
                        'status' => 'success',
                        'message' => "<h3 class='text-success text-center'>Code Generated Updated</h3>"
                    );
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($ResultResponse));
	}

	public function sell_barcode_config()
	{
            $this->form_validation->set_rules('zero', 'Zero', 'trim|required');
            $this->form_validation->set_rules('first', 'First', 'trim');
            $this->form_validation->set_rules('second', 'Second', 'trim');
            $this->form_validation->set_rules('third', 'Third', 'trim');
            $this->form_validation->set_rules('fourth', 'fourth', 'trim|required');
            $this->form_validation->set_rules('fifth', 'Fifth', 'trim');
            $this->form_validation->set_rules('sixth', 'sixth', 'trim');
            $this->form_validation->set_rules('seventh', 'seventh', 'trim');
            $this->form_validation->set_rules('eight', 'eight', 'trim');
            $this->form_validation->set_rules('nine', 'nine', 'trim');
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $contactData = array(
                    'zero' => $this->input->post('zero', true),
                    'first' => $this->input->post('first', true),
                    'second' => $this->input->post('second', true),
                    'third' => $this->input->post('third', true),
                    'fourth' => $this->input->post('fourth', true),
                    'fifth' => $this->input->post('fifth', true),
                    'sixth' => $this->input->post('sixth', true),
                    'seventh' => $this->input->post('seventh', true),
                    'eight' => $this->input->post('eight', true),
                    'nine' => $this->input->post('nine', true),
                    'comp_id' => $this->session->userdata('comp_id'),
                    'user_id' => $this->session->userdata('user_id'),
                    'CompanyBranchId' => $this->session->userdata('CompBranchId')
                );
                
                $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'id' => $this->input->post('sell_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $res = $this->user_model->getRow('sell_code', $where);
                if ($res) {
                    $ResultResponse = $this->user_model->updateAry('sell_code', $contactData, $where);
                    $response = array(
                        'data' => $this->input->post('sell_id'),
                        'status' => 'success',
                        'message' => "<h3 class='text-success text-center'>Code Generated Updated</h3>"
                    );
                } else {
                    $ResultResponse = $this->user_model->saveAry('sell_code', $contactData);
                    $response = array(
                        'data' => $response,
                        'status' => 'success',
                        'message' => "<h3 class='text-success text-center'>Code Generated Updated</h3>"
                    );
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($ResultResponse));
	}
        
        // SALE CONFIG
	public function update_a5config()
	{
            $contactData = array(
                'customer_name' => $this->input->post('customer_name', true),
                'PartyContactPerson' => $this->input->post('PartyContactPerson', true),
                'customer_gst' => $this->input->post('customer_gst', true),
                'customer_address' => $this->input->post('customer_address', true),
                'shipping_address' => $this->input->post('shipping_address', true),
                'customer_email' => $this->input->post('customer_email', true),
                'customer_mobile_number' => $this->input->post('customer_mobile_number', true),
                'CustomerLocationQR' => $this->input->post('CustomerLocationQR', true),
                'hsn' => $this->input->post('hsn', true),
                'qty' => $this->input->post('qty', true),
                'sell_price' => $this->input->post('sell_price', true),
                'mrp' => $this->input->post('mrp', true),
                'tax' => $this->input->post('tax', true),
                'purchase_price' => $this->input->post('purchase_price', true),
                'taxable_amount' => $this->input->post('taxable_amount', true),
                'taxamount' => $this->input->post('taxamount', true),
                'net_amount' => $this->input->post('net_amount', true),
                'total_taxable_amount' => $this->input->post('total_taxable_amount', true),
                'total_tax_amount' => $this->input->post('total_tax_amount', true),
                'grand_total' => $this->input->post('grand_total', true),
                'round_off' => $this->input->post('round_off', true),
                'freight_charge' => $this->input->post('freight_charge', true),
                'cash_discount' => $this->input->post('cash_discount', true),
                'bank_info' => $this->input->post('bank_info', true),
                'footer' => $this->input->post('footer', true),
                'comp_name' => $this->input->post('comp_name', true),
                'comp_gst' => $this->input->post('comp_gst', true),
                'comp_email' => $this->input->post('comp_email', true),
                'comp_mobile_number' => $this->input->post('comp_mobile_number', true),
                'comp_address' => $this->input->post('comp_address', true),
                'remark' => $this->input->post('remark', true),
                'title' => $this->input->post('title', true),
                'signature' => $this->input->post('signature', true),
                'unit' => $this->input->post('unit', true),
                'SubUnit' => $this->input->post('SubUnit', true),
                'discount_invoice' => $this->input->post('discount_invoice', true),
                'total_summary' => $this->input->post('total_summary', true),
                'hsnsac_summary' => $this->input->post('hsnsac_summary', true),
                'sqrfoot' => $this->input->post('sqrfoot', true),
                'you_saved' => $this->input->post('you_saved', true)
            );

            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $res = $this->user_model->getRow('a5config', $where);
            if ($res) {
                $this->user_model->updateAry('a5config', $contactData, $where);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            } else {
                $this->user_model->saveAry('a5config', $contactData);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            }	
            
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

        public function purchase_config()
	{
            $contactData = array(
                'customer_name' => $this->input->post('customer_name', true),
                'customer_gst' => $this->input->post('customer_gst', true),
                'customer_address' => $this->input->post('customer_address', true),
                'shipping_address' => $this->input->post('shipping_address', true),
                'customer_email' => $this->input->post('customer_email', true),
                'customer_mobile_number' => $this->input->post('customer_mobile_number', true),
                'hsn' => $this->input->post('hsn', true),
                'qty' => $this->input->post('qty', true),
                'sell_price' => $this->input->post('sell_price', true),
                'mrp' => $this->input->post('mrp', true),
                'tax' => $this->input->post('tax', true),
                'purchase_price' => $this->input->post('purchase_price', true),
                'taxable_amount' => $this->input->post('taxable_amount', true),
                'taxamount' => $this->input->post('taxamount', true),
                'net_amount' => $this->input->post('net_amount', true),
                'total_taxable_amount' => $this->input->post('total_taxable_amount', true),
                'total_tax_amount' => $this->input->post('total_tax_amount', true),
                'grand_total' => $this->input->post('grand_total', true),
                'round_off' => $this->input->post('round_off', true),
                'freight_charge' => $this->input->post('freight_charge', true),
                'cash_discount' => $this->input->post('cash_discount', true),
                'bank_info' => $this->input->post('bank_info', true),
                'footer' => $this->input->post('footer', true),
                'comp_name' => $this->input->post('comp_name', true),
                'comp_gst' => $this->input->post('comp_gst', true),
                'comp_email' => $this->input->post('comp_email', true),
                'comp_mobile_number' => $this->input->post('comp_mobile_number', true),
                'comp_address' => $this->input->post('comp_address', true),
                'remark' => $this->input->post('remark', true),
                'title' => $this->input->post('purchase_title', true),
                'unit' => $this->input->post('unit', true),
                'discount_invoice' => $this->input->post('discount_invoice', true),
                'signature' => $this->input->post('signature', true),
                'total_summary' => $this->input->post('total_summary', true),
                'sqrfoot' => $this->input->post('sqrfoot', true)
            );

            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $res = $this->user_model->getRow('purchase_config', $where);
            if ($res) {
                $this->user_model->updateAry('purchase_config', $contactData, $where);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            } else {
                $this->user_model->saveAry('a5config', $contactData);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function credit_note_config()
	{
            $contactData = array(
                'customer_name' => $this->input->post('customer_name', true),
                'customer_gst' => $this->input->post('customer_gst', true),
                'customer_address' => $this->input->post('customer_address', true),
                'shipping_address' => $this->input->post('shipping_address', true),
                'customer_email' => $this->input->post('customer_email', true),
                'customer_mobile_number' => $this->input->post('customer_mobile_number', true),
                'hsn' => $this->input->post('hsn', true),
                'qty' => $this->input->post('qty', true),
                'sell_price' => $this->input->post('sell_price', true),
                'mrp' => $this->input->post('mrp', true),
                'tax' => $this->input->post('tax', true),
                'purchase_price' => $this->input->post('purchase_price', true),
                'taxable_amount' => $this->input->post('taxable_amount', true),
                'taxamount' => $this->input->post('taxamount', true),
                'net_amount' => $this->input->post('net_amount', true),
                'total_taxable_amount' => $this->input->post('total_taxable_amount', true),
                'total_tax_amount' => $this->input->post('total_tax_amount', true),
                'grand_total' => $this->input->post('grand_total', true),
                'round_off' => $this->input->post('round_off', true),
                'bank_info' => $this->input->post('bank_info', true),
                'footer' => $this->input->post('footer', true),
                'comp_name' => $this->input->post('comp_name', true),
                'comp_gst' => $this->input->post('comp_gst', true),
                'comp_email' => $this->input->post('comp_email', true),
                'comp_mobile_number' => $this->input->post('comp_mobile_number', true),
                'comp_address' => $this->input->post('comp_address', true),
                'remark' => $this->input->post('remark', true),
                'title' => $this->input->post('title', true)
            );

            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $res = $this->user_model->getRow('credit_note_config', $where);
            if ($res) {
                $this->user_model->updateAry('credit_note_config', $contactData, $where);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            } else {
                $this->user_model->saveAry('credit_note_config', $contactData);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            }	
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function debit_note_config()
	{
            $contactData = array(
                'customer_name' => $this->input->post('customer_name', true),
                'customer_gst' => $this->input->post('customer_gst', true),
                'customer_address' => $this->input->post('customer_address', true),
                'shipping_address' => $this->input->post('shipping_address', true),
                'customer_email' => $this->input->post('customer_email', true),
                'customer_mobile_number' => $this->input->post('customer_mobile_number', true),
                'hsn' => $this->input->post('hsn', true),
                'qty' => $this->input->post('qty', true),
                'sell_price' => $this->input->post('sell_price', true),
                'mrp' => $this->input->post('mrp', true),
                'tax' => $this->input->post('tax', true),
                'purchase_price' => $this->input->post('purchase_price', true),
                'taxable_amount' => $this->input->post('taxable_amount', true),
                'taxamount' => $this->input->post('taxamount', true),
                'net_amount' => $this->input->post('net_amount', true),
                'total_taxable_amount' => $this->input->post('total_taxable_amount', true),
                'total_tax_amount' => $this->input->post('total_tax_amount', true),
                'grand_total' => $this->input->post('grand_total', true),
                'round_off' => $this->input->post('round_off', true),
                'bank_info' => $this->input->post('bank_info', true),
                'footer' => $this->input->post('footer', true),
                'comp_name' => $this->input->post('comp_name', true),
                'comp_gst' => $this->input->post('comp_gst', true),
                'comp_email' => $this->input->post('comp_email', true),
                'comp_mobile_number' => $this->input->post('comp_mobile_number', true),
                'comp_address' => $this->input->post('comp_address', true),
                'remark' => $this->input->post('remark', true),
                'title' => $this->input->post('title', true)
            );

            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $res = $this->user_model->getRow('debit_note_config', $where);
            if ($res) {
                $this->user_model->updateAry('debit_note_config', $contactData, $where);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            } else {
                $this->user_model->saveAry('debit_note_config', $contactData);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function estimateconfig()
	{
            $contactData = array(
                'customer_name' => $this->input->post('customer_name', true),
                'customer_gst' => $this->input->post('customer_gst', true),
                'customer_address' => $this->input->post('customer_address', true),
                'shipping_address' => $this->input->post('shipping_address', true),
                'customer_email' => $this->input->post('customer_email', true),
                'customer_mobile_number' => $this->input->post('customer_mobile_number', true),
                'hsn' => $this->input->post('hsn', true),
                'qty' => $this->input->post('qty', true),
                'sell_price' => $this->input->post('sell_price', true),
                'mrp' => $this->input->post('mrp', true),
                'tax' => $this->input->post('tax', true),
                'purchase_price' => $this->input->post('purchase_price', true),
                'taxable_amount' => $this->input->post('taxable_amount', true),
                'taxamount' => $this->input->post('taxamount', true),
                'net_amount' => $this->input->post('net_amount', true),
                'total_taxable_amount' => $this->input->post('total_taxable_amount', true),
                'total_tax_amount' => $this->input->post('total_tax_amount', true),
                'grand_total' => $this->input->post('grand_total', true),
                'round_off' => $this->input->post('round_off', true),
                'freight_charge' => $this->input->post('freight_charge', true),
                'cash_discount' => $this->input->post('cash_discount', true),
                'bank_info' => $this->input->post('bank_info', true),
                'footer' => $this->input->post('footer', true),
                'comp_name' => $this->input->post('comp_name', true),
                'comp_gst' => $this->input->post('comp_gst', true),
                'comp_email' => $this->input->post('comp_email', true),
                'comp_mobile_number' => $this->input->post('comp_mobile_number', true),
                'comp_address' => $this->input->post('comp_address', true),
                'remark' => $this->input->post('remark', true),
                'title' => $this->input->post('title', true)
            );

            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $res = $this->user_model->getRow('estimateconfig', $where);
            if ($res) {
                $this->user_model->updateAry('estimateconfig', $contactData, $where);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            } else {
                $this->user_model->saveAry('a5config', $contactData);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}

	public function estimate_purchase_config()
	{
            $contactData = array(
                'customer_name' => $this->input->post('customer_name', true),
                'customer_gst' => $this->input->post('customer_gst', true),
                'customer_address' => $this->input->post('customer_address', true),
                'shipping_address' => $this->input->post('shipping_address', true),
                'customer_email' => $this->input->post('customer_email', true),
                'customer_mobile_number' => $this->input->post('customer_mobile_number', true),
                'hsn' => $this->input->post('hsn', true),
                'qty' => $this->input->post('qty', true),
                'sell_price' => $this->input->post('sell_price', true),
                'mrp' => $this->input->post('mrp', true),
                'tax' => $this->input->post('tax', true),
                'purchase_price' => $this->input->post('purchase_price', true),
                'taxable_amount' => $this->input->post('taxable_amount', true),
                'taxamount' => $this->input->post('taxamount', true),
                'net_amount' => $this->input->post('net_amount', true),
                'total_taxable_amount' => $this->input->post('total_taxable_amount', true),
                'total_tax_amount' => $this->input->post('total_tax_amount', true),
                'grand_total' => $this->input->post('grand_total', true),
                'round_off' => $this->input->post('round_off', true),
                'freight_charge' => $this->input->post('freight_charge', true),
                'cash_discount' => $this->input->post('cash_discount', true),
                'bank_info' => $this->input->post('bank_info', true),
                'footer' => $this->input->post('footer', true),
                'comp_name' => $this->input->post('comp_name', true),
                'comp_gst' => $this->input->post('comp_gst', true),
                'comp_email' => $this->input->post('comp_email', true),
                'comp_mobile_number' => $this->input->post('comp_mobile_number', true),
                'comp_address' => $this->input->post('comp_address', true),
                'remark' => $this->input->post('remark', true),
                'title' => $this->input->post('title', true)
            );

            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $res = $this->user_model->getRow('estimate_purchase_config', $where);
            if ($res) {
                $this->user_model->updateAry('estimate_purchase_config', $contactData, $where);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            } else {
                $this->user_model->saveAry('estimate_purchase_config', $contactData);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
	
        public function invoice_send_mail()
	{
            $id = $this->input->post('id');
            if ($id) {
                $data['email_body'] = $this->input->post('email_body');
                $data['email_subject'] = $this->input->post('email_subject');
                $data['smtp_port'] = $this->input->post('smtp_port');
                $data['smtp_username'] = $this->input->post('smtp_username');
                $data['smtp_server'] = $this->input->post('smtp_server');
                $data['smtp_password'] = $this->input->post('smtp_password');
                $data['saleTemplate'] = $this->input->post('saleTemplate');

                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'comp_profile/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'comp_profile/index');
                }
            } else {
                return redirect(base_url() . 'comp_profile/index');
            }
	}
        
        public function setWarehouse()
        {
            if ($_POST) {
                $warehouse = $this->input->post('warehouse');
                if (!isset($warehouse) && empty($warehouse)) {
                    echo json_encode(['status' => false, 'msg' => 'Please enter required field', 'class' => 'danger']);
                    return;
                }
                
                $warehouseData = [
                    'warehouse' => $this->input->post('warehouse')
                ];
                
                if ($this->session->userdata('role_type') == "Salesman") {
                    $user_id = $this->session->userdata('admin_id');
                } else {
                    $user_id = $this->session->userdata('user_id');
                }
                $where = ['user_id' => $user_id, 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                
                $result = $this->user_model->updateAry('comp_profile', $warehouseData, $where);
                if ($result) {
                    echo json_encode(['status' => true, 'msg' => "Warehouse set successfully", 'class' => 'success', 'data' => $result]);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => "Something went wrong, please try again", 'class' => 'danger']);
                    return;
                }
            }
        }
        
        public function setWarehouseForEstimate()
        {
            if ($_POST) {
                $warehouse = $this->input->post('warehouse');
                if (!isset($warehouse) && empty($warehouse)) {
                    echo json_encode(['status' => false, 'msg' => 'Please enter required field', 'class' => 'danger']);
                    return;
                }
                
                $warehouseData = [
                    'warehouse_estimate' => $this->input->post('warehouse')
                ];
                
                if ($this->session->userdata('role_type') == 'Salesman') {
                    $UserId = $this->session->userdata('admin_id');
                } else {
                    $UserId = $this->session->userdata('user_id');
                }
                $where = ['user_id' => $UserId, 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $result = $this->user_model->updateAry('comp_profile', $warehouseData, $where);
                if ($result) {
                    echo json_encode(['status' => true, 'msg' => "Warehouse set successfully", 'class' => 'success', 'data' => $result]);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => "Something went wrong, please try again", 'class' => 'danger']);
                    return;
                }
            }
        }
        
        public function setWarehouseForPurchase()
        {
            if ($_POST) {
                $warehouse = $this->input->post('warehouse');
                if (!isset($warehouse) && empty($warehouse)) {
                    echo json_encode(['status' => false, 'msg' => 'Please enter required field', 'class' => 'danger']);
                    return;
                }
                
                $warehouseData = [
                    'warehouse_purchase' => $this->input->post('warehouse')
                ];
                
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $result = $this->user_model->updateAry('comp_profile', $warehouseData, ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                if ($result) {
                    echo json_encode(['status' => true, 'msg' => "Warehouse set successfully", 'class' => 'success', 'data' => $result]);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => "Something went wrong, please try again", 'class' => 'danger']);
                    return;
                }
            }
        }
        
        public function setWarehouseForEstimatePurchase()
        {
            if ($_POST) {
                $warehouse = $this->input->post('warehouse');
                if (!isset($warehouse) && empty($warehouse)) {
                    echo json_encode(['status' => false, 'msg' => 'Please enter required field', 'class' => 'danger']);
                    return;
                }
                
                $warehouseData = [
                    'warehouse_estimate_purchase' => $this->input->post('warehouse')
                ];
                
                $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $result = $this->user_model->updateAry('comp_profile', $warehouseData, $where);
                if ($result) {
                    echo json_encode(['status' => true, 'msg' => "Warehouse set successfully", 'class' => 'success', 'data' => $result]);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => "Something went wrong, please try again", 'class' => 'danger']);
                    return;
                }
            }
        }
        
        public function proforma_config()
        {
            $contactData = array(
                'customer_name' => $this->input->post('customer_name', true),
                'customer_gst' => $this->input->post('customer_gst', true),
                'customer_address' => $this->input->post('customer_address', true),
                'shipping_address' => $this->input->post('shipping_address', true),
                'customer_email' => $this->input->post('customer_email', true),
                'customer_mobile_number' => $this->input->post('customer_mobile_number', true),
                'hsn' => $this->input->post('hsn', true),
                'qty' => $this->input->post('qty', true),
                'sell_price' => $this->input->post('sell_price', true),
                'mrp' => $this->input->post('mrp', true),
                'tax' => $this->input->post('tax', true),
                'purchase_price' => $this->input->post('purchase_price', true),
                'taxable_amount' => $this->input->post('taxable_amount', true),
                'taxamount' => $this->input->post('taxamount', true),
                'net_amount' => $this->input->post('net_amount', true),
                'total_taxable_amount' => $this->input->post('total_taxable_amount', true),
                'total_tax_amount' => $this->input->post('total_tax_amount', true),
                'grand_total' => $this->input->post('grand_total', true),
                'round_off' => $this->input->post('round_off', true),
                'freight_charge' => $this->input->post('freight_charge', true),
                'cash_discount' => $this->input->post('cash_discount', true),
                'bank_info' => $this->input->post('bank_info', true),
                'footer' => $this->input->post('footer', true),
                'comp_name' => $this->input->post('comp_name', true),
                'comp_gst' => $this->input->post('comp_gst', true),
                'comp_email' => $this->input->post('comp_email', true),
                'comp_mobile_number' => $this->input->post('comp_mobile_number', true),
                'comp_address' => $this->input->post('comp_address', true),
                'remark' => $this->input->post('remark', true),
                'title' => $this->input->post('title', true)
            );

            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $res = $this->user_model->getRow('proforma_config', $where);
            if ($res) {
                $this->user_model->updateAry('proforma_config', $contactData, $where);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            } else {
                $this->user_model->saveAry('proforma_config', $contactData);
                $response = array(
                    'status' => 'success',
                    'message' => "<h3 class='text-success text-center'>Setting Updated</h3>",
                );
            }
		
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
    }