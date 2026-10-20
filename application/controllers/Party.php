<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Party extends CI_Controller
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
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $salemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            
            if ($this->session->userdata('BranchRole')) {                
                $salesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $salemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId, 'CompanyBranchId' => $CompBranchId];
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
                $partyCategoryWhere = ['isDeleted' => 1, 'userId' => $UserId, 'compId' => $CompId, 'compBranchId' => $CompBranchId];
            } else {
                $salesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $salemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $partyCategoryWhere = ['isDeleted' => 1, 'userId' => $UserId, 'compId' => $CompId];
            }
            
            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<span class="error text-danger">', '</span>');
                    $data['page'] = 'inventory_sep/party/party';
                    return $this->load->view('inventory', $data);
                } else {
                    $data['name'] = $this->input->post('name');
                    $data['group_head'] = $this->input->post('group');
                    $data['contact'] = $this->input->post('contact');
                    $data['email'] = $this->input->post('email');
                    $data['address_1'] = $this->input->post('address_1');
                    $data['state'] = $this->input->post('state');
                    $data['state_code'] = $this->input->post('state_code');
                    $data['pin'] = $this->input->post('pin');
                    $data['pan'] = $this->input->post('pan');
                    $data['gstType'] = $this->input->post('gstType');
                    $data['gst'] = $this->input->post('gst');
                    $data['credit_limit'] = $this->input->post('credit_limit');
                    $data['opening_balance'] = $this->input->post('opening_balance');
                    $data['opBalnceType'] = $this->input->post('opBalnceType');
                    $data['user_id'] = $UserId;
                    $data['comp_id'] = $CompId;
                    $data['CompanyBranchId'] = $CompBranchId;
                    
                    $result = $this->user_model->saveAry('parties', $data);
                    if ($result) {
                        $city_id = get_title('districts', ['name' => $this->input->post('city')], 'id');
                        if ($city_id) {
                            $only_city['city'] = $city_id;
                            $this->user_model->updateAry('parties', $only_city, ['id' => $result]);
                        } else {
                            $city_data['name'] = $this->input->post('city');
                            $city_data['state_id'] = 28;
                            $res['city'] = $this->user_model->saveAry('districts', $city_data);
                            $this->user_model->updateAry('parties', $res, ['id' => $result]);
                        }
                        return redirect(base_url() . 'party');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect(base_url() . 'party');
                    }
                }
            } else {
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                
                $role_data = $this->session->userdata('roles');
                $start = 0;
                if (isset($role_data) && isset($CompId) && isset($UserId) || $CompBranchId) {
                    $result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
                    $newdata = $result1->action;
                    $permission_data = explode(",", $newdata);
                    $data['permission_data'] = $permission_data;
                    
                    $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->order_by('Name', 'ASC')->get('employees')->result();
                    $data['categories'] = $this->db->select('id, name')->where($partyCategoryWhere)->order_by('id', 'DESC')->get('party_category')->result();
                    $data['areaLists'] = $this->db->select('area')->where($CompanyBranchWhere)->where('area IS NOT NULL', null, false)->where('area != ', '')->group_by('area')->order_by('id', 'DESC')->get('parties')->result();
                    
                    if ($this->input->get('salesmanId')  && $this->input->get('salesmanId') != "All") {
                        $salesmanId = $this->input->get('salesmanId');
                    }
                    if ($this->input->get('categoryId')  && $this->input->get('categoryId') != "All") {
                        $categoryId = $this->input->get('categoryId');
                    }                    
                    if ($this->input->get('areaName')  && $this->input->get('areaName') != "All") {
                        $areaName = $this->input->get('areaName');
                    }
                    
                    $this->db->select('id, group_head, name, contact, gst, address_1, area, city, SalesmanId, categoryId');
                    $this->db->where($CompanyBranchWhere);
                     if (!empty($salesmanId)) {
                            $this->db->where('SalesmanId', $salesmanId);
                     }
                     if (!empty($categoryId)) {
                            $this->db->where('categoryId', $categoryId);
                     }
                     if (!empty($areaName)) {
                            $this->db->where('area', $areaName);
                     } 
                    $this->db->order_by('added_at', 'DESC');
                    $data['parties'] = $this->db->get('parties')->result();
                    $data['page'] = 'party/party_list';
                    
                    return $this->load->view('inventory_sep/inventory', $data);
                }
            }
        }

        public function add_party()
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
            } else {
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            }
            $data['parties'] = $this->user_model->getAry('parties', '', '', '', $CompanyBranchWhere);
            $data['page'] = 'inventory_sep/party/party';
            return $this->load->view('inventory', $data);
	}

	public function getParty()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $results = $this->user_model->getAry('payment', '', '', '', ['partyID' => $id]);
                if (isset($results) && !empty($results)) {
                    $html = '';
                    $sum_of_payment = 0;
                    $sum_of_reciept = 0;
                    $aman = 1;
                    foreach ($results as $key => $result) {
                        $key = $key + 1;

                        $html .= "<tr class='text-dark'>";
                        $html .= '<td scope="col" width="2%">' . $key . '</td>';
                        $html .= '<td scope="col" width="10%">' . $result->date . '</td>';
                        $html .= '<td scope="col" width="22%">' . get_title('parties', ['id' => $result->partyID], 'name') . '</td>';
                        $html .= '<td scope="col" width="8%">' . $result->paymentmode . '</td>';
                        if ($result->paymentReceipt == 'Payment') {
                            $html .= '<td scope="col" width="19%">' . $result->amount . '</td>';
                            $sum_of_payment = $sum_of_payment + $result->amount;
                        } else {
                            $html .= '<td scope="col" width="19%">--</td>';
                        }
                        if ($result->paymentReceipt == 'Reciept') {
                            $html .= '<td scope="col" width="28%">' . $result->amount . '</td>';
                            $sum_of_reciept = $sum_of_reciept + $result->amount;
                        } else {
                            $html .= '<td scope="col" width="28%">--</td>';
                        }
                        $html .= '<td scope="col" width="20%">' . $balance = $sum_of_reciept - $sum_of_payment . '</td>';
                        $html .= '<td class="text-right">
                                    <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a href="' . base_url('index.php/payment/edit/') . $result->id . '" class="dropdown-item cp">Edit</a> <a href="' . base_url('index.php/payment/delete/') . $result->id . '" class="dropdown-item cp">Delete</a>
                                    </div>
                                </div>
                            </td>';
                        $html .= '</tr>';
                    }

                    $total['payment'] = $sum_of_payment;
                    $total['receipt'] = $sum_of_reciept;

                    echo json_encode(['status' => true, 'data' => $html, 'total' => $total]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

	public function getCustomer()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $party = $this->user_model->getRow('parties', ['id' => $id]);
                if (isset($party) && !empty($party)) {
                    echo json_encode(['status' => true, 'data' => $party]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

	public  function searchparty()
	{
            $name = $this->input->post('name');
            $this->db->select('*');
            $this->db->from('parties');
            $this->db->where('status', 1);
            $this->db->like('name', $name);
            $this->db->where('comp_id', $this->session->userdata('comp_id'));
            $this->db->where('user_id', $this->session->userdata('user_id'));
            if ($this->session->userdata('BranchRole')) {
                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
            }
            $this->db->where_in('group_head');
            $this->db->order_by('name', 'ASC');
            $this->db->limit(20);
            $query = $this->db->get();
            $party = $query->result();
            if ($party) {
                $data['party'] = $party;
                echo json_encode(['status' => true, 'data' => $data]);
            } else {
                echo json_encode(['status' => false, 'data' => 'Data not found.']);
            }
	}

	public function searchcity()
	{
            $name = $this->input->post('name');
            $this->db->select('*');
            $this->db->from('districts');
            $this->db->like('name', $name);
            $this->db->order_by('name', 'ASC');
            $query = $this->db->get();
            $party = $query->result();
            if ($party) {
                $data['party'] = $party;
                echo json_encode(['status' => true, 'data' => $data]);
            } else {
                echo json_encode(['status' => false, 'data' => 'Data not found.']);
            }
	}

	public function statesearch()
	{
            $name = $this->input->post('name');
            $this->db->select('*');
            $this->db->from('state_list');
            $this->db->like('state', $name);
            $this->db->order_by('state', 'ASC');
            $query = $this->db->get();
            $party = $query->result();
            if ($party) {
                $data['party'] = $party;
                echo json_encode(['status' => true, 'data' => $data]);
            } else {
                echo json_encode(['status' => false, 'data' => 'Data not found.']);
            }
	}

	public function partyname($id = null)
	{
            if (isset($id) && !empty($id)) {
                $this->db->select('*');
                $this->db->from('parties');
                $this->db->where('id', $id);
                $this->db->order_by('name', 'ASC');
                $query = $this->db->get();
                $party = $query->result();
                if ($party) {
                    $data['party'] = $party;
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
	}

	public function InvoiceNumber()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $financialYear = $this->session->userdata('financial_year');
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['status' => 1, 'InvoiceNumber' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $financialYear];
                $CompanyBranchWhere2 = ['type' => "Sale", 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                    $CompanyBranchWhere2['CompanyBranchId'] = $CompBranchId;
                }
                
                $this->db->where($CompanyBranchWhere);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $SaleData = $this->db->get('sales')->row();
                if (isset($SaleData) && !empty($SaleData)) {
                    $CompanyBranchWhere2['InvoiceID'] = $SaleData->id;
                    
                    $invoice_product = $this->user_model->getAry('invoice_product', '', '', '', $CompanyBranchWhere2);
                    $PartyData = $this->user_model->getRow('parties', ['id' => $SaleData->customerId]);
                    $data['invoice_product'] = $invoice_product;
                    $data['SaleData'] = $SaleData;
                    $data['PartyData'] = $PartyData;
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

	public function InvoiceNumberForEstimate()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $financialYear = $this->session->userdata('financial_year');
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['status' => 1, 'InvoiceNumber' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $financialYear];
                $CompanyBranchWhere2 = ['type' => "Sale", 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                    $CompanyBranchWhere2['CompanyBranchId'] = $CompBranchId;
                }
                
                $this->db->where($CompanyBranchWhere);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $EstimateData = $this->db->get('estimates')->row();

                if (isset($EstimateData) && !empty($EstimateData)) {
                    $CompanyBranchWhere2['InvoiceID'] = $EstimateData->id;
                    
                    $invoice_product = $this->user_model->getAry('estimate_product', '', '', '', $CompanyBranchWhere2);
                    $data['invoice_product'] = $invoice_product;
                    $data['EstimateData'] = $EstimateData;
                    $data['PartyData'] = $this->user_model->getRow('parties', ['id' => $EstimateData->customerId]);
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

	public function invoice()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['status' => 1, 'InvoiceNumber' => $id, 'voucherType' => 2, 'user_id' => $UserId, 'comp_id' => $CompId];
                $CompanyBranchWhere2 = ['type' => "Sale", 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                    $CompanyBranchWhere2['CompanyBranchId'] = $CompBranchId;
                }
                
                $this->db->where($CompanyBranchWhere);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $party = $this->db->get('sales')->row();
                if (isset($party) && !empty($party)) {
                    $CompanyBranchWhere2['InvoiceID'] = $party->id;
                    
                    $invoice_product = $this->user_model->getAry('invoice_product', '', '', '', $CompanyBranchWhere2);
                    $data['invoice_product'] = $invoice_product;
                    $data['party'] = $party;
                    if (!empty($invoice_product)) {
                        echo json_encode(['status' => true, 'data' => $data]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Data not found.']);
                    }
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

        public function incoicepurchase()
        {
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $FinancialYear = $this->session->userdata('financial_year');
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['invoiceNumber' => $id, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear];
                $CompanyBranchWhere2 = ['type' => 'Purchase', 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                    $CompanyBranchWhere2['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $CompanyBranchWhere['EmployeeId'] = $EmployeeId;
                }
                
                $this->db->where($CompanyBranchWhere);
                $PurchaseData = $this->db->get('purchases')->row();
                if (isset($PurchaseData) && !empty($PurchaseData)) {
                    $CompanyBranchWhere2['InvoiceNo'] = $id;
                    
                    $invoice_product = $this->user_model->getAry('invoice_product', '', '', '', $CompanyBranchWhere2);
                    $data['invoice_product'] = $invoice_product;
                    $data['PurchaseData'] = $PurchaseData;
                    $data['PartyData'] = $this->user_model->getRow('parties', ['id' => $PurchaseData->customerId]);
                    echo json_encode(['status' => true, 'data' => $data]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
        }

	public function getInvoiceBySaleByInvoiceNumber()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['invoiceNumber' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $this->db->order_by("invoiceNumber", "DESC");
                $result = $this->db->get('sales')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}
        
	public function getInvoiceByPurchaseByInvoiceNumber()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['invoiceNumber' => $id, 'user_id' => $UserId, 'CompId' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType')) {
                    $Where['EmployeeId'] = $EmployeeId;
                }
                
                $this->db->where($Where);
                $this->db->order_by("invoiceNumber", "DESC");
                $result = $this->db->get('purchases')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function getInvoiceByEstimateByInvoiceNumber()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['invoiceNumber' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'financial_year' => $FinancialYear, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $this->db->order_by("invoiceNumber", "DESC");
                $result = $this->db->get('estimates')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}
	
	public function getInvoiceByEstimate()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
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
                
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $this->db->order_by('id', 'DESC');
                $result = $this->db->get('estimates')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function getInvoiceByEstimateForNext()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
                return false;
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
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
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $this->db->order_by('id', 'ASC');
                $result = $this->db->get('estimates')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function getInvoiceBySale()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
                return false;
            } else {               
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
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
                
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $this->db->order_by('id', 'DESC');
                $result = $this->db->get('sales')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function getInvoiceBySaleForNext()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
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
                
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $SalesmanIds);
                }
                $this->db->order_by("id", "ASC");
                $result = $this->db->get('sales')->row(); 
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function getInvoiceByPurchaseForNext()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
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

                $this->db->where($Where);
                $this->db->order_by("id", "ASC");
                $result = $this->db->get('purchases')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function getInvoiceByPurchase()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
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

                $this->db->where($Where);
                $this->db->order_by("id", "DESC");
                $result = $this->db->get('purchases')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Found Data"]);
                    return false;
                }
            }
	}

	public function update()
	{
            if ($_POST) {
                $id = $this->input->post('id');
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }

                $data['name'] = $this->input->post('name');
                $data['group_head'] = $this->input->post('group');
                $data['contact'] = $this->input->post('contact');
                $data['email'] = $this->input->post('email');
                $data['address_1'] = $this->input->post('address_1');
                $data['state'] = $this->input->post('state');
                $data['state_code'] = $this->input->post('state_code');
                $data['city'] = $this->input->post('city');
                $data['pin'] = $this->input->post('pin');
                $data['pan'] = $this->input->post('pan');
                $data['gstType'] = $this->input->post('gstType');
                $data['gst'] = $this->input->post('gst');
                $data['credit_limit'] = $this->input->post('credit_limit');
                $data['opening_balance'] = $this->input->post('opening_balance');
                $data['opBalnceType'] = $this->input->post('opBalnceType');
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompBranchId;
                $data['remark'] = $this->input->post('remark');

                $result = $this->user_model->updateAry('parties', $data, $CompanyBranchWhere);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Party Updated.', 'class' => 'success']);
                    return redirect(base_url() . 'party');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'party');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'party');
            }
	}

	public function updateparty()
	{
            $post = $this->input->post();
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('contact', 'Contact', 'min_length[10]|max_length[11]');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            $this->form_validation->set_rules('address_1', 'Address 1');
            $this->form_validation->set_rules('city', 'City');
            $this->form_validation->set_rules('state', 'State', 'required');
            $this->form_validation->set_rules('pin', 'Pin', 'min_length[6]|max_length[6]');
            $this->form_validation->set_rules('pan', 'Pan', 'regex_match[/[A-Z]{5}[0-9]{4}[A-Z]{1}/]');
            $this->form_validation->set_rules('gstType', 'GST Type');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                return;
            }
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields', 'class' => 'danger']);
                return;
            }

            $id = $this->input->post('id');
            if (!empty($id)) {
                $id = $this->input->post('id');
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $data['name'] = $this->input->post('name');
                $data['group_head'] = $this->input->post('group');
                $data['contact'] = $this->input->post('contact');
                $data['email'] = $this->input->post('email');
                $data['address_1'] = $this->input->post('address_1');
                $data['state'] = $this->input->post('state');
                $data['state_code'] = $this->input->post('state_code');
                $data['city'] = $this->input->post('city');
                $data['pin'] = $this->input->post('pin');
                $data['pan'] = $this->input->post('pan');
                $data['gstType'] = $this->input->post('gstType');
                $data['gst'] = $this->input->post('gst');
                $data['credit_limit'] = $this->input->post('credit_limit');
                $data['opening_balance'] = $this->input->post('opening_balance');
                $data['opBalnceType'] = $this->input->post('opBalnceType');
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompBranchId;
                $data['remark'] = $this->input->post('remark');

                $result = $this->user_model->updateAry('parties', $data, $CompanyBranchWhere);
                
                if (isset($result) && !empty($result)) {
                    $customers = $this->user_model->getRow('parties', ['id' => $id]);
                    echo json_encode(['status' => true, 'data' => $customers, 'msg' => 'Party Updated successfully.', 'id' => $result]);
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

	public function updatepartys()
	{
            $post = $this->input->post();
            $id = $this->input->post('id');
            if ($id) {
                $data['name'] = $this->input->post('name');
                $data['group_head'] = $this->input->post('group');
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
                } else {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                }
                $data['remark'] = $this->input->post('remark');

                $result = $this->user_model->updateAry('parties', $data, $CompanyBranchWhere);
                if (isset($result) && !empty($result)) {
                    $customers = $this->user_model->getRow('parties', ['id' => $id]);
                    echo json_encode(['status' => true, 'data' => $customers, 'msg' => 'Party Updated successfully.', 'id' => $result]);
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

	protected function rules()
	{
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'trim|required|max_length[400]'
                ),
                array(
                    'field' => 'contact',
                    'label' => 'Contact Number',
                    'rules' => 'trim|required|max_length[10]|min_length[10]|numeric'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|max_length[80]|valid_email'
                ),
                array(
                    'field' => 'address_1',
                    'label' => 'address_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'state',
                    'label' => 'state',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'city',
                    'label' => 'city',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'pan',
                    'label' => 'pan',
                    'rules' => 'trim|callback_unique_pancard'
                ),
            );
            return $rules;
	}

	protected function contact()
	{
            $rules = array(
                array(
                    'field' => 'contact',
                    'label' => 'contact',
                    'rules' => 'trim|required|max_length[25]|min_length[5]|is_unique[parties.contact]'
                ),
            );
            return $rules;
	}

	protected function mails()
	{
            $rules = array(
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|max_length[60]|valid_email|is_unique[parties.email]'
                ),
            );
            return $rules;
	}

	public function checkContacts()
	{
            $rules = $this->contact();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                echo json_encode(['status' => true]);
            } else {
                echo json_encode(['status' => false]);
            }
	}
        
	public function checkmail()
	{
            $rules = $this->mails();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                echo json_encode(['status' => true]);
            } else {
                echo json_encode(['status' => false]);
            }
	}

	public function party_delete($id)
	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
                } else {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                }

                $return = $this->user_model->deleteRow('parties', $CompanyBranchWhere);
                if ($return) {
                    echo json_encode(['status' => true, 'msg' => 'Party deleted successfully.']);
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

	public function party_view($id)
	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
                } else {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                }

                $return = $this->user_model->getRow('parties', $CompanyBranchWhere);
                if ($return) {
                    $data['party'] = $return;
                    $data['page'] = 'inventory_sep/party/party_view';
                    return $this->load->view('inventory', $data);
                } else {
                    return redirect(base_url() . 'party');
                }
            } else {
                return redirect('party');
            }
	}

	public function party_edit($id)
	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
                } else {
                    $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                }
                
                $return = $this->user_model->getRow('parties', $CompanyBranchWhere);
                if ($return) {
                    $data['party'] = $return;
                    $data['page'] = 'inventory_sep/party/party_edit';
                    return $this->load->view('inventory', $data);
                } else {
                    return redirect(base_url() . 'party');
                }
            } else {
                return redirect(base_url() . 'party');
            }
	}
        
	public function getdistict($id)
	{
            $where = ['state_id' => $id];
            $result = $this->user_model->getAry('districts', $order = '', $start = 0, $limit = 0, $where);
            $html = '';
            $html .= "<option value='' >-Select-</option>";
            foreach ($result as $key => $dist_val) {
                $html .= "<option value=" . $dist_val->id . " >" . $dist_val->name . "</option>";
            }
            echo json_encode(['status' => true, 'data' => $html]);
	}
        
	public function getCustomerDetails($id)
	{
            $where = ['id' => $id];
            $result = $this->user_model->getAry('parties', $order = '', $start = 0, $limit = 0, $where = '');
            $html = '';
            $html .= "<option value='' >-Select-</option>";
            foreach ($result as $key => $row) {
                $html .= "<option value=" . $row->id . " selected>" . $row->name . "</option>";
            }
            echo json_encode(['status' => true, 'data' => $html]);
	}
        
	public function getList()
	{
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
            } else {
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            }
            
            $result = $this->user_model->getAry('parties', '', '', '', $CompanyBranchWhere);
            $html = '';

            foreach ($result as $key => $row) {
                $html .= "<option value=" . $row->id . ">" . $row->name . "</option>";
            }
            echo json_encode(['status' => true, 'data' => $html]);
	}

	public function load_party()
	{
            $data['page'] = 'inventory_sep/loadparty';
            return $this->load->view('inventory', $data);
	}

	public function party_edits($id)
	{
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
            } else {
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
            }
            
            $return = $this->user_model->getRow('parties', $CompanyBranchWhere);
            if ($return) {
                $data['result'] = $return;
                $data['page'] = 'inventory_sep/party/party_edits';
                return $this->load->view('inventory', $data);
            }
	}
        
	public function exportparty()
	{
            $fileName = 'party-' . time() . '.xls';
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
            
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Party Name *');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Mobile Number*');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Email');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Pan Number');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'GST No');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'GST Type');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Credit Limit');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Address');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'City');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Pin');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'State');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'State Code');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Opening Balance');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Opening Balance Type');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Accounting group');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Salesman');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'User');
            $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Company');
            $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Created');
            
            $objPHPExcel->getActiveSheet()->getStyle('A1:S1')->applyFromArray(
                array(
                    'font' => array(
                        'color' => array('argb' => 'FF0000'),
                    ),
                    'alignment' => array(
                        'text' => 'center'
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );

            $rowCount = 2;

            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'CompanyBranchId' => $CompBranchId];
            } else {
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            }
            $result = $this->user_model->getAry('parties', '', '', '', $CompanyBranchWhere);

            if ($result) {
                foreach ($result as $key => $val) {
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':S' . $rowCount)->applyFromArray(
                        array(
                            'font' => array(
                                'color' => array('argb' => '000000'),
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000')
                                )
                            )
                        )
                    );

                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val->name);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val->contact);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val->email);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val->pan);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val->gst);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val->gstType);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $val->credit_limit);
                    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val->address_1);
                    $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, get_title('districts', ['id' => $val->city], 'name'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $val->pin);
                    $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, get_title('state_list', ['id' => $val->state], 'state'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, get_title('state_list', ['id' => $val->state], 'code'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $val->opening_balance);
                    $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $val->opBalnceType);
                    $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, get_title('accounting_group', ['id' => $val->group_head], 'name'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, get_title('employees', ['Id' => $val->SalesmanId], 'Name'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, get_title('users', ['id' => $val->user_id], 'name'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, get_title('inventory_billing', ['id' => $val->comp_id], 'company_name'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, date('d-m-Y h:m:s A', strtotime($val->added_at)));
                    $rowCount++;
                }

                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header('Content-Disposition: attachment; filename= "' . $fileName . '"');
                header("Cache-Control: max-age=0");
                $objWriter->save("php://output");
            } else {
                $this->session->set_flashdata(['msg' => 'Not Found List.', 'class' => 'danger']);
                return redirect(base_url() . 'party/party_list');
            }
	}
        
	public function importparty()
	{
            if (isset($_FILES["file"]["name"])) {
                $file_type = $_FILES["file"]["type"];
                if ($file_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file_type == 'application/vnd.ms-excel') {
                    $path = $_FILES["file"]["tmp_name"];
                    $object = PHPExcel_IOFactory::load($path);
                    $data = [];
                    foreach ($object->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $product_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $hsn = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            $qty = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                            $subcate = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                            $short_de = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                            $long_des = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                            $price = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $discount = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $cost = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $min_qty = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $accounting_group = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $state = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                            $data[] = array(
                                'name'  => $product_name,
                                'contact' => $hsn,
                                'email' => $qty,
                                'pan' => $subcate,
                                'gst' => $short_de,
                                'gstType' => $long_des,
                                'credit_limit' => $price,
                                'address_1' => $discount,
                                'opening_balance' => $cost,
                                'pin' => $min_qty,
                                'state_code' => $this->user_model->getApproxTax('state_list', $state, 'state'),
                                'state' => $this->user_model->getApproxTax('state_list', $state, 'state'),
                                'group_head' => $this->user_model->getApproxTax('accounting_group', $accounting_group, 'name'),
                                'user_id' => $this->session->userdata('user_id'),
                                'comp_id' => $this->session->userdata('comp_id'),
                                'CompanyBranchId' => $this->session->userdata('CompBranchId')
                            );
                        }
                    }

                    if (isset($data) && !empty($data)) {
                        foreach ($data as $pro_info) {
                            $this->user_model->saveAry('parties', $pro_info);
                        }
                        if ($data) {
                            $this->session->set_flashdata(['msg' => 'Party Import Successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'party');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                            return redirect(base_url() . 'party');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                        return redirect(base_url() . 'party');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Only xls and xlsx File Valid.', 'class' => 'danger']);
                    return redirect(base_url() . 'party');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                return redirect(base_url() . 'party');
            }
	}

	public function opBalnceType($id = null)
	{
            $state_id = $id;
            $html = '';
            $html .= "<div class='col-md-3'><label for='btype'>Type</label></div>";
            $html .= "<select class='form-control' name='opBalanceType' id='opBalance' class='form-control searchable_select' required='true'>";
            $html .= "<option value='' >-Select-</option>";
            if ($state_id != '') {
                $html .= "<option value='CR' selected >CR</option>";
                $html .= "<option value='DR' selected >DR</option>";
            } else {
                $html .= "<option value='CR' selected >CR</option>";
                $html .= "<option value='DR' selected >DR</option>";
            }
            $html . "</select>";
            print_r($html);
	}

	public function gstType($id = null)
	{
            $state_id = $id;
            $html = '';
            $html .= "<div class='col-md-3'><label for='gst'>GST type</label></div>";
            $html .= "<select class='form-control' name='gsttype' id='gsttype' class='form-control'>";
            $html .= "<option value='' >-Select-</option>";
            if ($state_id != '') {
                $html .= "<option value='Regular' selected >Regular</option>";
                $html .= "<option value='Composite' selected >Composite</option>";
                $html .= "<option value='N/A'></option></option>";
            } else {
                $html .= "<option value='Regular' selected >Regular</option>";
                $html .= "<option value='Composite' selected >Composite</option>";
                $html .= "<option value='' selected ></option>";
            }
            $html . "</select>";
            print_r($html);
	}
        
        public function IsUniqeGSTAndMobile($GST, $Params) 
        {
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            list($Mobile, $accGroupId) = explode(', ', $Params);
            
            $Where = ['group_head' => $accGroupId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->db->select('id')->where($Where)->group_start()->or_where('contact', $Mobile)->group_end()->group_start()->or_where('gst', $GST)->group_end()->get('parties')->num_rows() === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqeGSTAndMobile', 'The GST or mobile must be unique.');
                return false;
            }
        }

        public function partyAdd()
        {
            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->session->userdata('salesman_sale') == 'Yes' || $this->session->userdata('salesman_estimate') == 'Yes') {
                    $this->form_validation->set_rules('SalesmanId', 'Salesman', 'trim|required');
                }
                $this->form_validation->set_rules('gst', 'GST', 'trim|callback_IsUniqeGSTAndMobile[' . $this->input->post('contact') . ', ' . $this->input->post('group') . ']');
                
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                    return;
                } else {        
                    $data['name'] = $this->input->post('name');
                    $data['group_head'] = $this->input->post('group');
                    $data['ContactPerson'] = $this->input->post('ContactPerson');
                    $data['contact'] = $this->input->post('contact');
                    $data['email'] = $this->input->post('email');
                    $data['address_1'] = $this->input->post('address_1');
                    $data['area'] = $this->input->post('area');
                    $data['state'] = $this->input->post('state');
                    $data['state_code'] = get_title('state_list', ['id' => $this->input->post('state')], 'code');
                    $data['city'] = $this->input->post('city');
                    $data['pin'] = $this->input->post('pin');
                    $data['pan'] = $this->input->post('pan');
                    $data['gstType'] = $this->input->post('gstType');
                    $data['gst'] = $this->input->post('gst');
                    $data['remark'] = $this->input->post('remark');
                    $data['segement'] = $this->input->post('segement');
                    $data['credit_limit'] = $this->input->post('credit_limit');
                    $data['credit_limit_day'] = $this->input->post('credit_limit_day');
                    $data['opening_balance'] = $this->input->post('opening_balance');
                    $data['opBalnceType'] = $this->input->post('opBalnceType');
                    $data['SalesmanId'] = $this->input->post('SalesmanId');
                    $data['categoryId'] = $this->input->post('partyCategoryId');

                    if($this->input->post('ledger_gst_details') !=""){
                        $data['gst_details'] = $this->input->post('ledger_gst_details');
                        $data['gst_details_status'] =1;   
                    }

                    if ($_FILES['LocationQR']['name']) {
                        $filename = time() . $_FILES['LocationQR']['name'];
                        move_uploaded_file($_FILES['LocationQR']['tmp_name'], 'uploads/Parties/' . $filename);
                        $data['LocationQR'] = $filename;
                    } else {
                        $data['LocationQR'] = "";
                    }
                    
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['comp_id'] = $this->session->userdata('comp_id');
                    $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    
                    $result = $this->user_model->saveAry('parties', $data);
                    if ($result) {
                        $city_id = get_title('districts', ['name' => $this->input->post('city')], 'id');
                        if ($city_id) {
                            $only_city['city'] = $city_id;
                            $this->user_model->updateAry('parties', $only_city, ['id' => $result]);
                        } else {
                            $city_data['name'] = $this->input->post('city');
                            $city_data['state_id'] = 28;
                            $res['city'] = $this->user_model->saveAry('districts', $city_data);
                            $this->user_model->updateAry('parties', $res, ['id' => $result]);
                        }
                        echo json_encode(['msg' => 'Party Added', 'status' => true]);
                        return;
                    } else {
                        echo json_encode(['msg' => 'Database issue!', 'status' => false]);
                        return;
                    }
                }
            }
        }

        public function edit_party($id = null)
        {
              if (isset($id) && !empty($id)) {
                  $where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                  if ($this->session->userdata('BranchRole')) {
                      $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                  }
                  $return = $this->user_model->getRow('parties', $where);
                  if ($return) {
                      $data['party'] = $return;
                      return $this->load->view('inventory_sep/party/edit_party', $data);
                  }
              }
        }

        public function edit_party_for_sale($id = null)
        {
              if (isset($id) && !empty($id)) {
                  if ($this->session->userdata('RoleType')) {
                      $EmployeeId = $this->session->userdata('EmployeeId');
                      $UserId = $this->session->userdata('UserId');
                      $CompId = $this->session->userdata('CompId');
                      $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
                  } else {
                      $UserId = $this->session->userdata('user_id');
                      $CompId = $this->session->userdata('comp_id');
                  }
                  $where = ['id' => $id, 'comp_id' => $CompId, 'user_id' => $UserId];
                  if ($this->session->userdata('BranchRole')) {
                      $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                  }

                  $this->db->where($where);
                  if ($this->session->userdata('RoleType')) {
                      $this->db->where_in('SalesmanId', $SalesmanIds);
                  }
                  $return = $this->db->get('parties')->row();
                  if ($return) {
                      $data['party'] = $return;
                      return $this->load->view('inventory_sep/sale/party_edit', $data);
                  }
              }
        }

        protected function update_rules()
        {
              $rules = array(
                  array(
                      'field' => 'name',
                      'label' => 'name',
                      'rules' => 'trim|required|max_length[400]'
                  ),
                  array(
                      'field' => 'contact',
                      'label' => 'Contact Number',
                      'rules' => 'trim|required|max_length[10]|min_length[10]|numeric'
                  ),
                  array(
                      'field' => 'email',
                      'label' => 'email',
                      'rules' => 'trim|max_length[80]|valid_email'
                  ),
                  array(
                      'field' => 'address_1',
                      'label' => 'address_1',
                      'rules' => 'trim'
                  ),
                  array(
                      'field' => 'state',
                      'label' => 'state',
                      'rules' => 'trim|required'
                  ),
                  array(
                      'field' => 'pan',
                      'label' => 'pan',
                      'rules' => 'trim|callback_unique_pancard_update'
                  ),
              );
              return $rules;
          }

        public function IsUniqeGSTAndMobileEdit($GST, $Params) 
        {
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            list($Mobile, $accGroupId, $Id) = explode(', ', $Params);
            
            $Where = ['id != ' => $Id, 'group_head' => $accGroupId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->db->select('id')->where($Where)->group_start()->or_where('contact', $Mobile)->group_end()->group_start()->or_where('gst', $GST)->group_end()->get('parties')->num_rows() === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqeGSTAndMobileEdit', 'The GST or mobile must be unique.');
                return false;
            }
        }
        
        public function update_party()
        {
            $id = $this->input->post('id');
            $rules = $this->update_rules();
            $this->form_validation->set_rules($rules);
            if ($this->session->userdata('salesman_sale') == 'Yes' || $this->session->userdata('salesman_estimate') == 'Yes') {
                $this->form_validation->set_rules('SalesmanId', 'Salesman', 'trim|required');
            }
            $this->form_validation->set_rules('gst', 'GST', 'trim|callback_IsUniqeGSTAndMobileEdit[' . $this->input->post('contact') . ', ' . $this->input->post('group')  . ', ' . $id . ']');
            
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                return;
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $SalesmanId = $this->session->userdata('RoleType') == "Salesman" ? $this->session->userdata('EmployeeId') : $this->input->post('SalesmanId');
                
                $where = ['id' => $id, 'comp_id' => $CompId, 'user_id' => $UserId];
                $InvoicesWhere = ['customerId' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $CompanyBranchId;
                    $InvoicesWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                $data['name'] = $this->input->post('name');
                $data['group_head'] = $this->input->post('group');
                $data['ContactPerson'] = $this->input->post('ContactPerson');
                $data['contact'] = $this->input->post('contact');
                $data['email'] = $this->input->post('email');
                $data['address_1'] = $this->input->post('address_1');
                $data['area'] = $this->input->post('area');
                $data['state'] = $this->input->post('state');
                $data['state_code'] = get_title('state_list', ['id' => $this->input->post('state')], 'code');
                $data['pin'] = $this->input->post('pin');
                $data['pan'] = $this->input->post('pan');
                $data['gstType'] = $this->input->post('gstType');
                $data['gst'] = $this->input->post('gst');
                $data['credit_limit'] = $this->input->post('credit_limit');
                $data['credit_limit_day'] = $this->input->post('credit_limit_day');
                $data['opening_balance'] = $this->input->post('opening_balance');
                $data['opBalnceType'] = $this->input->post('opBalnceType');
                $data['SalesmanId'] = $SalesmanId;
                $data['categoryId'] = $this->input->post('partyCategoryId');
                if($this->input->post('ledger_gst_details') !=""){
                    $data['gst_details'] = $this->input->post('ledger_gst_details');
                    $data['gst_details_status'] =1;   
                }
                
                if ($_FILES['LocationQR']['name']) {
                    unlink('uploads/Parties/' . $this->input->post('OldLocationQR'));
                    $filename = time() . $_FILES['LocationQR']['name'];
                    move_uploaded_file($_FILES['LocationQR']['tmp_name'], 'uploads/Parties/' . $filename);
                    $data['LocationQR'] = $filename;
                } else {
                    $data['LocationQR'] = $this->input->post('OldLocationQR');
                }
                
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompanyBranchId;
                $data['remark'] = $this->input->post('remark');
                $data['segement'] = $this->input->post('segement');
                $data['shipName'] = $this->input->post('shipName');
                $data['shipGstNo'] = $this->input->post('shipGstNo');                
                $data['shipAddress'] = $this->input->post('shipAddress');
                $data['shipStateId'] = $this->input->post('shipStateId');                
                $data['shipPincode'] = $this->input->post('shipPincode');
                
                if ($SalesmanId != get_title('parties', ['id' => $id], 'SalesmanId')) {
                    if ($this->db->select('id, customerId, SalesmanId')->where($InvoicesWhere)->where('type', 'Sale')->get('sales')->num_rows()) {
                        $SalesData = $this->db->select('id, customerId, SalesmanId')->where($InvoicesWhere)->where('type', 'Sale')->order_by('id', 'DESC')->get('sales')->result();
                        foreach ($SalesData as $key => $Sale) {
                            $OldSalesmanId = $Sale->SalesmanId ? $Sale->SalesmanId : $SalesmanId;
                            $InvoicesWhere['type'] = 'Sale';
                            $this->user_model->updateAry('sales', ['SalesmanId' => $SalesmanId, 'OldSalesmanId' => $OldSalesmanId], $InvoicesWhere);
                        }
                    }

                    if ($this->db->select('id, customerId, SalesmanId')->where($InvoicesWhere)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                        $EstimatesData = $this->db->select('id, customerId, SalesmanId')->where($InvoicesWhere)->where('type', 'Estimate')->order_by('id', 'DESC')->get('estimates')->result();
                        foreach ($EstimatesData as $key => $Estimate) {
                            $OldSalesmanId = $Estimate->SalesmanId ? $Estimate->SalesmanId : $SalesmanId;
                            $InvoicesWhere['type'] = 'Estimate';
                            $this->user_model->updateAry('estimates', ['SalesmanId' => $SalesmanId, 'OldSalesmanId' => $OldSalesmanId], $InvoicesWhere);
                        }
                    }
                }
                
                $result = $this->user_model->updateAry('parties', $data, $where);

                if ($result) {
                    $city_id = get_title('districts', ['name' => $this->input->post('city')], 'id');
                    $shipCityId = get_title('districts', ['name' => $this->input->post('shipCity')], 'id');
                    if ($city_id) {
                        $only_city['city'] = $city_id;
                        $only_city['shipCityId'] = $shipCityId;
                        $this->user_model->updateAry('parties', $only_city, ['id' => $id]);
                    } else {
                        $city_data['name'] = $this->input->post('city');
                        $city_data['state_id'] = $this->input->post('state');
                        $res['city'] = $this->user_model->saveAry('districts', $city_data);
                        
                        $shipCityData['name'] = $this->input->post('shipCity');
                        $shipCityData['state_id'] = $this->input->post('shipStateId');
                        $res['shipCityId'] = $this->user_model->saveAry('districts', $shipCityData);
                        
                        $this->user_model->updateAry('parties', $res, ['id' => $id]);
                    }
                    
                    echo json_encode(['msg' => 'Party Updated', 'status' => true, 'id' => $id]);
                    return;
                } else {
                    echo json_encode(['msg' => 'Something issue!', 'status' => false]);
                    return;
                }
            }
        }

	protected function save_rules()
	{
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'trim|required|max_length[400]'
                ),
                array(
                    'field' => 'contact',
                    'label' => 'Contact Number',
                    'rules' => 'trim|required|max_length[10]|min_length[10]|numeric'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|max_length[80]|valid_email'
                ),
                array(
                    'field' => 'address_1',
                    'label' => 'address_1',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'state',
                    'label' => 'state',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'city',
                    'label' => 'city',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'pan',
                    'label' => 'pan',
                    'rules' => 'trim|is_unique[parties.pan]|regex_match[/[A-Z]{5}[0-9]{4}[A-Z]{1}/]'
                ),
                array(
                    'field' => 'gst',
                    'label' => 'GST Number',
                    'rules' => 'regex_match[/\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}/]'
                ),
            );
            return $rules;
	}
        
	public function create()
	{
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('contact', 'Contact', 'min_length[10]|max_length[11]|is_unique[parties.contact]');
            $this->form_validation->set_rules('email', 'Email', 'valid_email|is_unique[parties.email]');
            $this->form_validation->set_rules('address_1', 'Address 1');
            $this->form_validation->set_rules('city', 'City');
            $this->form_validation->set_rules('state', 'State', 'required');
            $this->form_validation->set_rules('pin', 'Pin', 'min_length[6]|max_length[6]');
            $this->form_validation->set_rules('pan', 'Pan', 'regex_match[/[A-Z]{5}[0-9]{4}[A-Z]{1}/]');
            $this->form_validation->set_rules('gstType', 'GST Type');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                return;
            }
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields', 'class' => 'danger']);
                return;
            }

            $data['name'] = $this->input->post('name');
            $data['group_head'] = $this->input->post('group');
            $data['contact'] = $this->input->post('contact');
            $data['email'] = $this->input->post('email');
            $data['address_1'] = $this->input->post('address_1');
            $data['state'] = $this->input->post('state');
            $data['state_code'] = $this->input->post('state_code');
            $data['city'] = $this->input->post('city');
            $data['pin'] = $this->input->post('pin');
            $data['pan'] = $this->input->post('pan');
            $data['gstType'] = $this->input->post('gstType');
            $data['gst'] = $this->input->post('gst');
            $data['credit_limit'] = $this->input->post('credit_limit');
            $data['opening_balance'] = $this->input->post('opening_balance');
            $data['opBalnceType'] = $this->input->post('opBalnceType');
            $data['user_id'] = $this->session->userdata('user_id');
            $data['comp_id'] = $this->session->userdata('comp_id');
            $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            
            $result = $this->user_model->saveAry('parties', $data);
            if (isset($result) && !empty($result)) {
                $customers = $this->user_model->getRow('parties', ['id' => $result]);
                echo json_encode(['status' => true, 'data' => $customers, 'msg' => 'Party added successfully.', 'id' => $result]);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
	}

	public function save_party()
	{
            $rules = $this->save_rules();
            $this->form_validation->set_rules($rules);
            if ($this->session->userdata('salesman_sale') == 'Yes' || $this->session->userdata('salesman_estimate') == 'Yes') {
                $this->form_validation->set_rules('SalesmanId', 'Salesman', 'trim|required');
            }
            $this->form_validation->set_rules('gst', 'GST', 'trim|callback_IsUniqeGSTAndMobile[' . $this->input->post('contact') . ', ' . $this->input->post('group') . ']');
            
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                return;
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $data['name'] = $this->input->post('name');
                $data['group_head'] = $this->input->post('group');
                $data['contact'] = $this->input->post('contact');
                $data['email'] = $this->input->post('email');
                $data['address_1'] = $this->input->post('address_1');
                $data['area'] = $this->input->post('area');
                $data['state'] = $this->input->post('state');
                $data['state_code'] = get_title('state_list', ['id' => $this->input->post('state')], 'code');
                $data['pin'] = $this->input->post('pin');
                $data['pan'] = $this->input->post('pan');
                $data['gstType'] = $this->input->post('gstType');
                $data['gst'] = $this->input->post('gst');
                $data['remark'] = $this->input->post('remark');
                $data['credit_limit'] = $this->input->post('credit_limit');
                $data['credit_limit_day'] = $this->input->post('credit_limit_day');
                $data['opening_balance'] = $this->input->post('opening_balance');
                $data['opBalnceType'] = $this->input->post('opBalnceType');
                $data['SalesmanId'] = $this->session->userdata('RoleType') == "Salesman" ? $this->session->userdata('EmployeeId') : $this->input->post('SalesmanId');
                $data['categoryId'] = $this->input->post('partyCategoryId');

                if ($_FILES['LocationQR']['name']) {
                    $filename = time() . $_FILES['LocationQR']['name'];
                    move_uploaded_file($_FILES['LocationQR']['tmp_name'], 'uploads/Parties/' . $filename);
                    $data['LocationQR'] = $filename;
                } else {
                    $data['LocationQR'] = "";
                }
                
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                
                $data['segement'] = $this->input->post('segement');
                
                $result = $this->user_model->saveAry('parties', $data);
                if ($result) {
                    $city_id = get_title('districts', ['name' => $this->input->post('city')], 'id');
                    if ($city_id) {
                        $only_city['city'] = $city_id;
                        $this->user_model->updateAry('parties', $only_city, ['id' => $result]);
                    } else {
                        $city_data['name'] = $this->input->post('city');
                        $city_data['state_id'] = 28;
                        $res['city'] = $this->user_model->saveAry('districts', $city_data);
                        $this->user_model->updateAry('parties', $res, ['id' => $result]);
                    }
                    echo json_encode(['msg' => 'Party Created', 'status' => true, 'id' => $result]);
                    return;
                } else {
                    echo json_encode(['msg' => 'Something issue!', 'status' => false]);
                    return;
                }
            }
	}

	public function deleteParties()
	{
            $post = $this->input->post();
            $deleteData = '';
            if (isset($post) && !empty($post)) {
                foreach ($post['id'] as $key => $value) {
                    $party_id = $value;
                    $deleteData = $this->user_model->deleteRow('parties', ['id' => $party_id]);
                }
                if (isset($deleteData) && !empty($deleteData)) {
                    echo json_encode(['status' => true, 'data' => 'Party Deleted', 'class' => 'success']);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Something Went Wronng', 'class' => 'error']);
                }
            } else {
                echo json_encode(['status' => false, 'data' => 'Data not found.', 'class' => 'error']);
            }
	}

	public function getParties($id = null)
	{
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $user_id = $this->session->user_id;
                $comp_id = $this->session->comp_id;
                $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'id' => $id];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $product = $this->user_model->getRow('parties', $where);
                if (isset($product) && !empty($product)) {
                    echo json_encode(['status' => true, 'data' => $product]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}
        
	public function unique_name()
	{
            $name = $this->input->post('name');
            if ($name) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                } else {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                }

                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'name' => $name];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);
                if ($return_value) {
                    $this->form_validation->set_message('unique_name', 'Party Name must be unique for every company');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
	}
        
	public function unique_email()
	{
            $email = $this->input->post('email');
            if ($email) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'email' => $email];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);
                if ($return_value) {
                    $this->form_validation->set_message('unique_email', 'Party mail must be unique for every company');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
	}
        
	public function unique_contact()
	{
            $contact = $this->input->post('contact');
            if ($contact) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'contact' => $contact];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);
                if ($return_value) {
                    $this->form_validation->set_message('unique_contact', 'Party Mobile Number Should be unique');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }

        public function unique_pancard()
        {
            $pan = $this->input->post('pan');
            $accGroupId = $this->input->post('group');
            if ($pan) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }

                $where = ['group_head' => $accGroupId, 'user_id' => $UserId, 'comp_id' => $CompId, 'pan' => $pan];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);
                if ($return_value) {
                    $this->form_validation->set_message('unique_pancard', 'Pan Number Should be unique');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }

        public function unique_name_update()
        {
            $name = $this->input->post('name');
            $id = $this->input->post('id');
            if ($name) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }

                $where = ['id != ' => $id, 'user_id' => $UserId, 'comp_id' => $CompId, 'name' => $name];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);

                if ($return_value) {
                    $this->form_validation->set_message('unique_name_update', 'Party Name must be unique for every company');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
	}
        
	public function unique_email_update()
	{
            $email = $this->input->post('email');
            $id = $this->input->post('id');

            if ($email) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                    
                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'email' => $email, 'id != ' => $id];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);
		
                if ($return_value) {
                    $this->form_validation->set_message('unique_email_update', 'Party mail must be unique for every company');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
	}
        
	public function unique_contact_update()
	{
            $contact = $this->input->post('contact');
            $id = $this->input->post('id');

            if ($contact) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }

                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'contact' => $contact, 'id != ' => $id];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);

                if ($return_value) {
                    $this->form_validation->set_message('unique_contact_update', 'Party Mobile Number Should be unique');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }

        public function unique_pancard_update()
        {
            $accGroupId = $this->input->post('group');
            $pan = $this->input->post('pan');
            $id = $this->input->post('id');

            if ($pan) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }

                $where = ['group_head' => $accGroupId, 'user_id' => $UserId, 'comp_id' => $CompId, 'pan' => $pan, 'id != ' => $id];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return_value = $this->user_model->getRow('parties', $where);

                if ($return_value) {
                    $this->form_validation->set_message('unique_pancard_update', 'Pan Number Should be unique');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }

        public function partylist()
        {
            return $this->load->view('inventory_sep/sale/party_list');
        }
        
        public function showParty()
        {
            $output = '';
            $query = '';
            if ($this->input->post('query')) {
                $query = $this->input->post('query');
            }

            $EmployeeId = null;
            if ($this->session->userdata('RoleType')) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId];
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId];
            $WhereIn = $EmployeeId !== null && $EmployeeId !== '' ? [$EmployeeId] : [];
            
            if ($this->session->userdata('BranchRole')) {
                $EMPWhere['CompanyBranchId'] = $CompanyBranchId;
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $EMPWhere['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true);
                foreach ($EmployeeIds as $key => $Id) {
                    $WhereIn[] = $Id;
                }
            }
            
            if ($query != '') {
                $this->db->like('name', $query);
            }
            $this->db->where($Where);
            if ($this->session->userdata('RoleType')) {
                $this->db->where_in('SalesmanId', $WhereIn);
            }
            $this->db->order_by('name', 'DESC');
            $data = $this->db->get('parties')->result();
            
            if ($data) {
                $output .= '
                        <div>
                        <b><div id="view_selected_party" style="background-color: #1f324a; color: #ffffff;"></div></b>
                        <div class="row" id="example12" style="height: auto; max-height: 300px; overflow: scroll; min-width: 500px; z-index: 999999;">';                        
                $rowNumber = 0;
                foreach ($data as $key => $row) {
                    if ($row->user_id == $UserId && $row->comp_id == $CompId) {
                        $tabindex = $rowNumber + 1;                        
                        
                        $accountGroupName = get_title('accounting_group', ['id' => $row->group_head], 'name');
                        
                        if (!empty($row->area)) {
                            $AreaComma = ", ";
                        } else {
                            $AreaComma = "";
                        }

                        if (!empty($row->contact)) {
                            $contactComma = ", ";
                        } else {
                            $contactComma = "";
                        }

                        if (!empty($row->group_head)) {
                            $accGroupComma = ", ";
                        } else {
                            $accGroupComma = "";
                        }
                        $output .= '
                                <div class="col-md-12 col-sm-12" id="trp' . $rowNumber . '" onkeyup="return keyUpForParty(event, ' . $rowNumber . ');" onkeypress="return entPressByParty(event, ' . $row->id . ', ' . $rowNumber . ');" onkeydown="return keyDownForParty(event, ' . $rowNumber . ');" onclick="return getCustomerDetail(' . $row->id . ', ' . $rowNumber . ');" tabindex="' . $tabindex . '">' .
                                '<span onclick="return getPendingOrderbook(' . $row->id . ');">' . $row->name . '</span>' . $AreaComma .
                                '<span onclick="return getPendingOrderbook(' . $row->id . ');">' . $row->area . '</span>' . $contactComma .
                                '<span onclick="return getPendingOrderbook(' . $row->id . ');">' . $row->contact . '</span>' . $accGroupComma .
                                '<span onclick="return getPendingOrderbook(' . $row->id . ');">' . $accountGroupName . '</span>' .
                                '</div>';
                                $rowNumber++;
                    }
                }
                $output .= '</div>';
            } else {
                $output .= 'No Data Found';
            }            
            echo $output;
        }

        public function create_party_for_sale_without_copying()
        {
            return $this->load->view('inventory_sep/sale/add_party');
	}

	public function shippingInfo()
	{
                return $this->load->view('inventory_sep/sale/shippingInfo');
	}

	public function create_party_for_sale($id = null)
	{
            if (isset($id) && !empty($id)) {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId];
                $Where = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                $WhereIn = [$EmployeeId];
                if ($this->session->userdata('BranchRole')) {
                    $EMPWhere['CompanyBranchId'] = $CompanyBranchId;
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true);
                    foreach ($EmployeeIds as $key => $Id) {
                        $WhereIn[] = $Id;
                    }
                }
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $WhereIn);
                }
                $return = $this->db->get('parties')->row();
                if ($return) {
                    $data['party'] = $return;
                    return $this->load->view('inventory_sep/sale/copy_party', $data);
                }
            }
	}

	public function downloadd()
	{
            $this->load->dbutil();

            $prefs = array(
                'format' => 'zip',
                'filename' => 'my_db_backup.sql'
            );
            $backup = &$this->dbutil->backup($prefs);

            $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';
            $save = 'pathtobkfolder/' . $db_name;

            $this->load->helper('file');
            write_file($save, $backup);
            $this->load->helper('download');
            force_download($db_name, $backup);
	}

	public function purchase_record($id = null)
	{
            if (isset($id) && !empty($id)) {
                $data['party_id'] = $id;
                $data['page'] = 'inventory_sep/customer_report/party_purchase';
                return $this->load->view('inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'invent/customer_report');
            }
	}

	public function sale_record($id = null)
	{
            if (isset($id) && !empty($id)) {
                $data['party_id'] = $id;
                $data['page'] = 'inventory_sep/customer_report/party_sale';
                return $this->load->view('inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'invent/customer_report');
            }
	}

	public function party_records($id = null)
	{
            if (isset($id) && !empty($id)) {
                $where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->getRow('parties', $where);
                if ($return) {
                    $data['party'] = $return;
                    $data['party_id'] = $id;
                    $data['page'] = 'inventory_sep/customer_report/party_details';
                    return $this->load->view('inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'invent/customer_report');
                }
            }
	}

	public function customer_report_by_filter($id = null)
	{
            if (isset($id) && !empty($id)) {
                $from_date = $this->input->post('start_date');
                $to_date = $this->input->post('end_date');
                $type = $this->input->post('type');
                $printing_format = $this->input->post('printing_format');
                if ($printing_format == 'pdf') {
                    $this->pdf_download($id, $type);
                    return;
                }

                $where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->getRow('parties', $where);
                if ($return) {
                    $data['party'] = $return;
                    $data['party_id'] = $id;
                    $data['start_date'] = $from_date;
                    $data['end_date'] = $to_date;
                    $this->session->set_userdata('from_date', $from_date);
                    $this->session->set_userdata('endDate', $to_date);
                    if ($type == 'debit') {
                        return $this->load->view('inventory_sep/customer_report/debit', $data);
                    } elseif ($type == 'credit') {
                        return $this->load->view('inventory_sep/customer_report/credit', $data);
                    } else {
                        return $this->load->view('inventory_sep/customer_report/party_records', $data);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'invent/customer_report');
                }
            }
	}

	public function pdf_download($id, $type)
	{
            if (isset($id) && !empty($id)) {
                $from_date = $this->input->post('start_date');
                $to_date = $this->input->post('end_date');
                $where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->getRow('parties', $where);

                $data['party'] = $return;
                $data['party_id'] = $id;
                $data['start_date'] = $from_date;
                $data['end_date'] = $to_date;

                $this->load->library('pdf');
                $dompdf = new Dompdf\Dompdf();
                $this->pdf->setPaper('A4', 'portrait');
                if ($type == 'debit') {
                    $dompdf->loadHtml($this->load->view('inventory_sep/customer_report/debitPdf', $data, true));
                } elseif ($type == 'credit') {
                    $dompdf->loadHtml($this->load->view('inventory_sep/customer_report/creditPdf', $data, true));
                } else {
                    $dompdf->loadHtml($this->load->view('inventory_sep/customer_report/party_records_with', $data, true));
                }
                $dompdf->render();
                $dompdf->output();
                $dompdf->stream("UdayPlastic" . time() . ".pdf", array("Attachment" => 1));
            }
	}

        public function mobile_number_report()
	{
            $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'contact != ' => ''];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['parties'] = $this->user_model->getAry('parties', '', '', '', $where);
            $data['page'] = 'inventory_sep/party/mobile_number_report';
            return $this->load->view('inventory', $data);
	}
        
        public function parties()
        {
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $config['base_url'] = base_url() . 'party/parties';
            $config['total_rows'] = $this->db->where($where)->get('parties')->num_rows();
            $config['per_page'] = 20;
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
            $config['uri_segment'] = 3;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            
            $data['links'] = $this->pagination->create_links();
            $data['parties'] = $this->db->where($where)->limit($config['per_page'], $page)->get('parties')->result();  
            $data['page'] = 'party/parties';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function BulkUpdate()
        {
            if ($_POST) {
                $partyids = $this->input->post('party_id');
                foreach ($partyids as $key => $data) {
                    $party_data = [
                        'group_head' => empty($this->input->post('group_head')[$key]) ? "" : $this->input->post('group_head')[$key],
                        'name' => empty($this->input->post('name')[$key]) ? "" : $this->input->post('name')[$key],
                        'ContactPerson' => empty($this->input->post('ContactPerson')[$key]) ? "" : $this->input->post('ContactPerson')[$key],
                        'contact' => empty($this->input->post('contact')[$key]) ? "" : $this->input->post('contact')[$key],
                        'email' => empty($this->input->post('email')[$key]) ? "" : $this->input->post('email')[$key],
                        'gst' => empty($this->input->post('gst')[$key]) ? "" : $this->input->post('gst')[$key],
                        'gstType' => empty($this->input->post('gstType')[$key]) ? "" : $this->input->post('gstType')[$key],
                        'address_1' => empty($this->input->post('address_1')[$key]) ? "" : $this->input->post('address_1')[$key],
                        'area' => empty($this->input->post('area')[$key]) ? "" : $this->input->post('area')[$key],
                        'city' => empty($this->input->post('city')[$key]) ? "" : $this->input->post('city')[$key],
                        'state' => empty($this->input->post('state')[$key]) ? "" : $this->input->post('state')[$key],
                        'state_code' => empty(get_title('state_list', ['id' => $this->input->post('state')[$key]], 'code')) ? "" : get_title('state_list', ['id' => $this->input->post('state')[$key]], 'code'),
                        'SalesmanId' => empty($this->input->post('SalesmanId')[$key]) ? "" : $this->input->post('SalesmanId')[$key],
                        'categoryId' => empty($this->input->post('categoryId')[$key]) ? "" : $this->input->post('categoryId')[$key]
                    ];
                    
                    $party_where = ['id' => $this->input->post('party_id')[$key], 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $party_where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $result = $this->user_model->updateAry('parties', $party_data, $party_where);
                }
                
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Parties updated successfully', 'class' => 'success']);
                    return redirect(base_url() . 'party');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to update data please try again', 'class' => 'danger']);
                    return redirect(base_url() . 'party');
                }
            }
        }
        
        public function getPartiesByAccountGroup()
        {
            if ($_POST) {
                $account_id = $this->input->post('account_id');
                if ($account_id == "" && $account_id == null) {
                    echo "<option value=''>Enter required fields</option>";
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $EmployeeId = $this->session->userdata('EmployeeId');
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                        $SalesmanId = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'));
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['group_head' => $account_id, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $CompBranchId;
                    }
                    $this->db->select('id, name, area, contact');
                    $this->db->where($where);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('SalesmanId', $SalesmanId);
                    }
                    $this->db->order_by('name', 'ASC');
                    $parties = $this->db->get('parties')->result();
                    if ($parties) {
                        $customerId = $this->input->post('customerId');
                        echo "<option selected='' disabled='' value=''>Select Party Name</option>";
                        foreach ($parties as $key => $data) {
                            $OtherInfo = ", " . $data->area . ", " . $data->contact . "</span>";
                            $selected = $data->id === $customerId ? "selected" : "";
                            echo "<option value='" . $data->id  . set_select('party_id', $data->id) . "' $selected>" . $data->name . $OtherInfo . "</option>";
                        }
                    } else {
                        echo "<option value=''>No record found</option>";
                    }
                }
            }
        }
        
        public function getPartiesByAccountGroupForStatement()
        {
            if ($_POST) {
                $account_id = $this->input->post('account_id');
                if ($account_id == "" && $account_id == null) {
                    echo json_encode(['status' => false, 'data' => "Enter required fields"]);
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $EmployeeId = $this->session->userdata('EmployeeId');
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                        $SalesmanId = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'), true);
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $Where = ['group_head' => $account_id, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $this->db->select('id, name, area, contact');
                    $this->db->where($Where);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('SalesmanId', $SalesmanId);
                    }
                    $this->db->order_by('name', 'ASC');
                    $parties = $this->db->get('parties')->result();
                    if ($parties) {
                        echo json_encode(['status' => true, 'data' => $parties]);
                    } else {
                        echo json_encode(['status' => false, 'data' => "No record found"]);
                    }
                }
            }
        }
        
        public function getPartyName ()
        {
            if ($_POST) {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId];
                $Where = ['user_id' => $UserId, 'comp_id' => $CompId];
                $WhereIn = [$EmployeeId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true);
                    foreach ($EmployeeIds as $key => $Id) {
                        $WhereIn[] = $Id;
                    }
                }

                $this->db->select('name');
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $WhereIn);
                }
                $this->db->like('name', $this->input->post('PartyName'));
                $PartyNameList = $this->db->get('parties')->result();
                if (!empty($PartyNameList)) {
                    echo json_encode($PartyNameList);
                } else {
                    return false;
                }
            }
        }
        
        public function getPartyArea ()
        {
            if ($_POST) {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId];
                $Where = ['user_id' => $UserId, 'comp_id' => $CompId];
                $WhereIn = [$EmployeeId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true);
                    foreach ($EmployeeIds as $key => $Id) {
                        $WhereIn[] = $Id;
                    }
                }
                
                $this->db->distinct()->select('area');
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $WhereIn);
                }
                $this->db->like('area', $this->input->post('PartyArea'));
                $PartyAreaList = $this->db->get('parties')->result();
                
                if (!empty($PartyAreaList)) {
                    echo json_encode($PartyAreaList);
                } else {
                    return false;
                }
            }
        }
        
        public function getPartyCity ()
        {
            if ($_POST) {
                if ($this->session->userdata('RoleType')) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId];
                $Where = ['parties.user_id' => $UserId, 'parties.comp_id' => $CompId];
                $WhereIn = [$EmployeeId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true);
                    foreach ($EmployeeIds as $key => $Id) {
                        $WhereIn[] = $Id;
                    }
                }
                
                $this->db->distinct()->select('districts.name AS cname');
                $this->db->join('districts', 'parties.city = districts.id');
                $this->db->where($Where);
                if ($this->session->userdata('RoleType')) {
                    $this->db->where_in('SalesmanId', $WhereIn);
                }
                $this->db->like('districts.name', $this->input->post('PartyCity'));
                $PartyCityList = $this->db->get('parties')->result();
                if (!empty($PartyCityList)) {
                    echo json_encode($PartyCityList);
                } else {
                    return false;
                }
            }
        }       
        
        public function category()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['categories'] = $this->db->select('id, name, created, createdBy, updated, updatedBy')->where($where)->order_by('id', 'DESC')->get('party_category')->result();
            $data['page'] = "party/category/index";
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function isUniqePartyCategoryName()
        {
                $categoryName = $this->input->post('categoryName');
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1, 'name' => $categoryName];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                }

                if ($this->db->select('id')->where($where)->get('party_category')->num_rows() === 0) {
                        return true;
                } else {
                        $this->form_validation->set_message('isUniqePartyCategoryName', 'The category name must be unique.');
                        return false;
                }
        }
        
        public function categorySave()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('categoryName', 'Category Name', 'trim|required|callback_isUniqePartyCategoryName');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $categoryData = [
                            'name' => $this->input->post('categoryName'),
                            'userId' => $userId,
                            'compId' => $this->session->userdata('comp_id'),
                            'compBranchId' => $this->session->userdata('CompBranchId'),
                            'created' => date('d-m-Y h:i:s A'),
                            'createdBy' => get_title('users', ['id' => $userId], 'name'),
                        ];

                        $Result = $this->user_model->saveAry('party_category', $categoryData);
                        if (!empty($Result)) {
                            echo json_encode(['Status' => true, 'Message' => 'Record save successfully']);
                            return;
                        } else {
                            echo json_encode(['Status' => false, 'Message' => 'Unable to save record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function categoryEdit($categoryId)
        {
            if (isset($categoryId) && !empty($categoryId)) {
                $where = ['id' => $categoryId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $categoryQuery = $this->db->select('id, name')->where($where)->get('party_category');
                if ($categoryQuery->num_rows()) {
                    $data['categoryData'] = $categoryQuery->row();
                    return $this->load->view('inventory_sep/party/category/edit', $data);                    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'party/category');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'party/category');
            }
        }
        
        public function isUniqePartyCategoryNameUpdate($categoryName, $categoryId) 
        {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1, 'name' => $categoryName, 'id != ' => $categoryId];
                if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                }            

                $QueryResult = $this->db->select('id, name')->where($where)->get('party_category')->num_rows();
                if ($QueryResult === 0) {
                    return true;
                } else {
                    $this->form_validation->set_message('isUniqePartyCategoryNameUpdate', 'The category name must be unique.');
                    return false;
                }
        }        
        
        public function categoryUpdate()
        {
            if ($this->input->is_ajax_request()) {
                $categoryId = $this->input->post('categoryId');
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('categoryName', 'Category Name', 'trim|required|callback_isUniqePartyCategoryNameUpdate[' . $categoryId . ']');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $categoryData = [
                            'name' => $this->input->post('categoryName'),
                            'updated' => date('d-m-Y h:i:s A'),
                            'updatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];

                        $Result = $this->user_model->updateAry('party_category', $categoryData, ['id' => $categoryId]);
                        if (!empty($Result)) {
                            echo json_encode(['Status' => true, 'Message' => 'Record update successfully']);
                            return;
                        } else {
                            echo json_encode(['Status' => false, 'Message' => 'Unable to update record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function categoryDelete($categoryId)
        {
            if (isset($categoryId) && !empty($categoryId)) {
                $where = ['id' => $categoryId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $categoryQuery = $this->db->select('id')->where($where)->get('party_category');
                if ($categoryQuery->num_rows()) {
                    if ($this->user_model->updateAry('party_category', ['isDeleted' => 0], $where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'party/category');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'party/category');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'party/category');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'party/category');
            }            
        }
        
        public function getAccountGroupIdByLedgerId()
        {
            if ($this->input->is_ajax_request()) {
                $ledgerId = $this->input->post('ledgerId');
                if ($ledgerId == "" && $ledgerId == null) {
                    echo json_encode(array('status' => false, 'data' => "0"));
                    return;
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $employeeId = $this->session->userdata('EmployeeId');
                        $userId = $this->session->userdata('UserId');
                        $compId = $this->session->userdata('CompId');
                        $salesmanId = json_decode(get_title('employees', ['Id' => $employeeId], 'WarehouseSalesman'));
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                    }
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['id' => $ledgerId, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                    }
                    $this->db->select('group_head');
                    $this->db->where($where);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('SalesmanId', $salesmanId);
                    }
                    $this->db->order_by('name', 'ASC');
                    $accountGroupId = $this->db->get('parties')->row('group_head');
                    if ($accountGroupId) {
                        echo json_encode(array('status' => true, 'data' => $accountGroupId));
                        return;
                    } else {
                        echo json_encode(array('status' => false, 'data' => '0'));
                        return;
                    }
                }
            }
        }  
        
        public function updatePartyGst()
        {
            $gst_details=$this->input->post('gst_details');
            $party_id=$this->input->post('party_id'); 
            $update['gst_details'] = $gst_details;
            $update['gst_details_status']=1;
            $result= $this->user_model->updateAry('parties', $update, ['id' => $party_id]);
            $status=false;
            if($result){
            $status=true;
            }
            echo json_encode(['status'=>$status,'result'=>$result]);
        }
    }