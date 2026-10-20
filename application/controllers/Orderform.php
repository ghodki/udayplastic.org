<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Orderform extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
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
        
        public function Index()
        {
            $Party = '';
            $Salesman = '';
            $partyFilter = function_exists('input_get_array') ? input_get_array('Party') : (array) ($this->input->get('Party') ?: []);
            if ($partyFilter !== [] && !in_array('All', $partyFilter, true)) {
                $Party = $partyFilter;
            }
            $salesmanFilter = function_exists('input_get_array') ? input_get_array('Salesmans') : (array) ($this->input->get('Salesmans') ?: []);
            if ($salesmanFilter !== [] && !in_array('All', $salesmanFilter, true)) {
                $Salesman = $salesmanFilter;
            }
            
            if ($this->input->get('From') && $this->input->get('To')) {
                $FromDate = $this->input->get('From');
                $ToDate = $this->input->get('To');
            } else {
                $FromDate = date('Y-m-d');
                $ToDate = date('Y-m-d');
            }
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
            $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $PartiesWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
            $OrderBookWhere = ['OrderDate >= ' => $FromDate, 'OrderDate <= ' => $ToDate, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
            
            if ($this->input->get('Status') != "") {
                $OrderBookWhere['InvoiceStatus'] = $this->input->get('Status');
            }
            
            if ($this->session->userdata('BranchRole')) {
                $PartiesWhere['CompanyBranchId'] = $CompanyBranchId;
                $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                $OrderBookWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->select('Id, OrderDate, OrderNumber, PartyId, VoucherType, SubTotal, InvoiceStatus, SalesmanId, CreatedBy');
            $this->db->where($OrderBookWhere);
            if (!empty($Party)) {
                $this->db->where_in('PartyId', $Party);
            }
            if (!empty($Salesman)) {
                $this->db->where_in('SalesmanId', $Salesman);
            }
            $this->db->order_by('Id', 'DESC');
            $data['OrderForms'] = $this->db->get('orderbook')->result();
            $data['Parties'] = $this->db->select('id, name')->where($PartiesWhere)->order_by('name', 'ASC')->get('parties')->result(); 
            $data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->get('employees')->result();
            $data['page'] = "OrderForm/Index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalesmanName()
        {
            if ($_POST) {
                if ($this->input->post('PartyId')) {
                    $PartyId = $this->input->post('PartyId');
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompanyBranchId = $this->session->userdata('CompBranchId');
                    
                    $PartyWhere = ['id' => $PartyId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                    $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $PartyWhere['CompanyBranchId'] = $CompanyBranchId; 
                        $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    
                    $SalesmanId = $this->db->select('SalesmanId')->where($PartyWhere)->get('parties')->row('SalesmanId');
                    $Salesmans = $this->db->select('Id, Name')->where($SalesmanWhere)->get('employees')->result();
                    foreach ($Salesmans as $key => $Salesman) {
                        $Selected = $SalesmanId == $Salesman->Id ? 'selected' : '';
                        echo "<option $Selected value='$Salesman->Id'" . set_select('SalesmanId', $Salesman->Id) .">" . $Salesman->Name . "</option>";
                    }
                }
            }
        }
        
        public function Create() 
        {
            $PartiesWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $PartiesWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            } 
            
            $data['Parties'] = $this->db->select('id, name')->where($PartiesWhere)->order_by('id', 'DESC')->get('parties')->result();
            $data['page'] = "OrderForm/Create";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function Save() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('PartyId', 'Party Name', 'trim|required');
                $this->form_validation->set_rules('VoucherType', 'Voucher Type', 'trim|required');
                $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                $this->form_validation->set_rules('SalesmanId', 'Salesman', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $PartiesWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $PartiesWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    }
                    $data['Parties'] = $this->db->select('id, name')->where($PartiesWhere)->order_by('id', 'DESC')->get('parties')->result();
                    $data['page'] = "OrderForm/Create";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    $OrderDate = $this->input->post('Date');
                    $PartyId = $this->input->post('PartyId');
                    $VoucherType = $this->input->post('VoucherType');
                    $SubTotal = $this->input->post('SubTotal');
                    
                    $OrderbookWhere = ['OrderDate' => $OrderDate, 'PartyId' => $PartyId, 'VoucherType' => $VoucherType, 'SubTotal' => $SubTotal, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                    $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $OrderbookWhere['CompanyBranchId'] = $CompBranchId;
                        $CompProfileWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                    $OrderNumber = mt_rand(00000, 99999);
                    
                    if (empty($this->db->where($OrderbookWhere)->get('orderbook')->num_rows())) {
                        $OrderformData = [
                            'OrderDate' => $OrderDate,
                            'OrderNumber' => $OrderNumber,
                            'PartyId' => $PartyId,
                            'VoucherType' => $VoucherType,
                            'SubTotal' => $SubTotal,
                            'SalesmanId' => $this->input->post('SalesmanId'),
                            'UserId' => $UserId,
                            'CompId' => $CompId,
                            'CompanyBranchId' => $CompBranchId,
                            'FinancialYear' => $FinancialYear,
                            'Remark' => $this->input->post('Remark'),
                            'Created' => date('d-m-Y h:i:s A'),
                            'CreatedBy' => get_title('users', ['id' => $UserId], 'name'),
                        ];

                        $Result = $this->user_model->saveAry('orderbook', $OrderformData);

                        foreach ($this->input->post('Products') as $key => $Product) {
                            if (!empty($Product['Quantity'])) {
                                $OrderformDataItems = [
                                    "OrderbookId" => $Result,
                                    'OrderNumber' => $OrderNumber,
                                    "OrderDate" => $OrderDate,
                                    "ProductName" => $Product['ProductName'],
                                    "ProductId" => $Product['ProductId'],
                                    "Price" => $Product['ProductPrice'],
                                    "Quantity" => $Product['Quantity'],
                                    "Total" => $Product['Total'],
                                    "EmployeeId" => $this->input->post('SalesmanId'),
                                    "UserId" => $UserId,
                                    "CompId" => $CompId,
                                    "CompanyBranchId" => $CompBranchId,
                                    "FinancialYear" => $FinancialYear,
                                    "Created" => date('d-m-Y h:i:s A'),
                                    "CreatedBy" => get_title('users', ['id' => $UserId], 'name')
                                ];
                                $this->user_model->saveAry('orderbook_products', $OrderformDataItems);
                            }
                        }

                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Orderform');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Orderform');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Duplicate entry not allowed on same date', 'class' => 'danger']);
                        return redirect(base_url() . 'Orderform');
                    }
                }
            }
        }
        
        public function Edit($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                } 
                if ($this->db->where($Where)->get('orderbook')->num_rows()) {
                    $PartiesWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $PartiesWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    } 

                    $data['Parties'] = $this->db->select('id, name')->where($PartiesWhere)->order_by('id', 'DESC')->get('parties')->result();
                    $data['OrderForm'] = $this->db->select('Id, OrderDate, PartyId, VoucherType, SubTotal, Remark')->where($Where)->get('orderbook')->row();
                    $data['OrderFormId'] = $Id;
                    $data['page'] = "OrderForm/Edit";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Orderform');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderform id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Orderform');
            }
        }
        
        public function getOrderformProduct($OrderFormId) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($OrderFormId)) {
                    $ItemsWhere = ['OrderbookId' => $OrderFormId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $ItemsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    }
                    $OrderFormItems = $this->db->select('Id, ProductName, ProductId, Price, Quantity, Total')->where($ItemsWhere)->order_by('Id', 'DESC')->get('orderbook_products')->result();
                    if ($OrderFormItems) {
                        echo json_encode(array('Status' => true, "Data" => $OrderFormItems));
                    } else {
                        echo json_encode(array('Status' => false, "Data" => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, "Data" => ""));
                }
            }
        }
        
        public function Update($Id) 
        {
            if ($_POST) {
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('PartyId', 'Party Name', 'trim|required');
                $this->form_validation->set_rules('VoucherType', 'Voucher Type', 'trim|required');
                $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                $this->form_validation->set_rules('SalesmanId', 'Salesman', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $PartiesWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $PartiesWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    }
                    $data['Parties'] = $this->db->select('id, name')->where($PartiesWhere)->order_by('id', 'DESC')->get('parties')->result();
                    $data['OrderForm'] = $this->db->select('Id, OrderDate, OrderNumber, PartyId, SubTotal, Remark')->where($Where)->get('orderbook')->row();
                    $data['OrderFormId'] = $Id;
                    $data['page'] = "OrderForm/Edit";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $CompProfileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $CompProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                    
                    $OrderformData = [
                        'OrderDate' => $this->input->post('Date'),
                        'PartyId' => $this->input->post('PartyId'),
                        'VoucherType' => $this->input->post('VoucherType'),
                        'SubTotal' => $this->input->post('SubTotal'),
                        'SalesmanId' => $this->input->post('SalesmanId'),
                        'Remark' => $this->input->post('Remark'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    $Where = ['Id' => $this->input->post('OrderFormId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    } 
                    $Result = $this->user_model->updateAry('orderbook', $OrderformData, $Where);
                    
                    $ItemsWhere = ['OrderbookId' => $this->input->post('OrderFormId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $FinancialYear];
                    if ($this->session->userdata('BranchRole')) {
                        $ItemsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    } 
                    
                    $PreviewsOrderItems = $this->db->where($ItemsWhere)->get('orderbook_products')->result();
                    foreach ($PreviewsOrderItems as $key => $Items) {
                        $this->db->where('Id', $Items->Id)->delete('orderbook_products');
                    }
                    
                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity'])) {
                            $OrderformDataItems = [
                                "OrderbookId" => $this->input->post('OrderFormId'),
                                'OrderNumber' => get_title('orderbook', ['Id' => $this->input->post('OrderFormId')], 'OrderNumber'),
                                "OrderDate" => $this->input->post('Date'),
                                "ProductName" => $Product['ProductName'],
                                "ProductId" => $Product['ProductId'],
                                "Price" => $Product['ProductPrice'],
                                "Quantity" => $Product['Quantity'],
                                "Total" => $Product['Total'],
                                "EmployeeId" => $this->input->post('SalesmanId'),
                                "UserId" => $this->session->userdata('user_id'),
                                "CompId" => $this->session->userdata('comp_id'),
                                "CompanyBranchId" => $this->session->userdata('CompBranchId'),
                                'FinancialYear' => $FinancialYear,
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('orderbook_products', $OrderformDataItems);
                        }
                    }
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Orderform');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Orderform/Edit/' . $Id);
                    }
                }
            }   
        }
        
        public function Info($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                } 
                if ($this->db->where($Where)->get('orderbook')->num_rows()) {
                    $data['OrderForm'] = $this->db->select('*')->where($Where)->get('orderbook')->row();
                    $ItemsWhere = ['OrderbookId' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $ItemsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    } 
                    $data['OrderFormItems'] = $this->db->select('ProductId, Price, Quantity, Total')->where($ItemsWhere)->order_by('Id', 'DESC')->get('orderbook_products')->result();
                    $data['page'] = "OrderForm/Info";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Orderform');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderform id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Orderform');
            }
        }
        
        public function Delete($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')){
                    $Where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                } 
                if ($this->db->where($Where)->get('orderbook')->num_rows()) {
                    if ($this->user_model->updateAry('orderbook', ['IsDeleted' => 0], $Where)) {
                        $ItemsWhere = ['OrderbookId' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                        if ($this->session->userdata('BranchRole')){
                            $ItemsWhere['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                        } 
                        $this->user_model->updateAry('orderbook_products', ['IsDeleted' => 0], $ItemsWhere);
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Orderform');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Orderform');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Orderform');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderform id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Orderform');
            }
        }
        
        public function OrderbookToSaleInvoice($Id) 
        {
            if (!empty($Id)) {
                $OrderbookWhere = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                $OrderbookProductWhere = ['OrderbookId' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $OrderbookWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    $OrderbookProductWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                } 
                if ($this->db->where($OrderbookWhere)->get('orderbook')->num_rows()) {
                    $data['OrderbookInfo'] = $this->db->where($OrderbookWhere)->get('orderbook')->row();
                    $data['OrderBookId'] = $Id;
                    $data['page'] = "OrderForm/OrderbookToSaleInvoice";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Orderform');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderform id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Orderform');
            }
        }
        
        public function OrderbookToEstimateInvoice($Id) 
        {
            if (!empty($Id)) {
                $OrderbookWhere = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                $OrderbookProductWhere = ['OrderbookId' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $OrderbookWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    $OrderbookProductWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                } 
                if ($this->db->where($OrderbookWhere)->get('orderbook')->num_rows()) {
                    $data['OrderbookInfo'] = $this->db->where($OrderbookWhere)->get('orderbook')->row();
                    $data['OrderBookId'] = $Id;
                    $data['page'] = "OrderForm/OrderbookToEstimateInvoice";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Orderform');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderform id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Orderform');
            }
        }
        
        public function getOrderbookProductsForSaleInvoice() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $OrderbookId = $this->input->post('OrderbookId');
                if (!empty($OrderbookId)) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $FinancialYear = $this->session->userdata('financial_year');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $OrderbookProductWhere = ['OrderbookId' => $OrderbookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                    $WarehouseWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $OrderbookProductWhere['CompanyBranchId'] = $CompBranchId; 
                        $WarehouseWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $OrderbookProductInfo = $this->db->where($OrderbookProductWhere)->get('orderbook_products')->result();
                    $ProductArray = [];
                    foreach ($OrderbookProductInfo as $key => $Products) {
                        $ProductInfo = $this->db->where('id', $Products->ProductId)->get('product')->row();
                        $ProductArray[] = array(
                            "p_code" => $ProductInfo->qrCode,
                            "productname" => $ProductInfo->p_name,
                            "product_id" => $ProductInfo->id,
                            "product_dec" => $ProductInfo->short_de,
                            "hsn" => $ProductInfo->hsn,
                            "qty" => $Products->Quantity,
                            "discount" => 0,
                            "rate" => $Products->Price,
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
                    echo json_encode(['Status' => false, 'ProductDataArray' => 'Orderbook id missing.']);
                }
            }
        }
        
        public function OrderbookCancel($OrderbookId) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!$OrderbookId) {
                    echo json_encode(['Status' => false, 'Message' => 'Id not found.']);
                    return;
                }
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $FinancialYear = $this->session->userdata('financial_year');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $Data['InvoiceStatus'] = 0;
                $Where = ['Id' => $OrderbookId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'FinancialYear' => $FinancialYear];
                $OrderBookProductWhere = ['OrderbookId' => $OrderbookId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'FinancialYear' => $FinancialYear];
                if ($this->session->userdata('BranchRole')){
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $OrderBookProductWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $OrderbookResult = $this->user_model->updateAry('orderbook', $Data, $Where);
                if ($OrderbookResult) {
                    $OrderBookProducts = $this->user_model->getAry('orderbook_products', '', '', '', $OrderBookProductWhere);
                    if ($OrderBookProducts) {
                        foreach ($OrderBookProducts as $key => $OrderbookProduct) {
                            $this->user_model->updateAry('orderbook_products', $Data, $OrderBookProductWhere);
                        }
                    }
                    echo json_encode(['Status' => true, 'Message' => 'Orderbook cancelled successfully.']);
                    return;
                } else {
                    echo json_encode(['Status' => false, 'Message' => 'There is some issue, Please try again.']);
                    return;
                }
            }
        }
        
        public function PendingOrderbookSale() 
        {
            if ($this->input->is_ajax_request()) {
                $PartyId = $this->input->post('PartyId');
                if (isset($PartyId) && !empty($PartyId)) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $Where = ['IsDeleted' => 1, 'InvoiceStatus' => 1, 'VoucherType' => 'Sale', 'PartyId' => $PartyId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    if ($this->db->select('Id')->where($Where)->get('orderbook')->num_rows()) {
                        $OrderbookData = $this->db->select('Id, OrderDate, PartyId, SubTotal, SalesmanId')->where($Where)->get('orderbook')->result();
                        echo json_encode(['Status' => true, 'Data' => $OrderbookData, 'Message' => 'Pending orderbook found for this party.']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Data' => '', 'Message' => 'No record found.']);
                        return;
                    }
                } else {
                    echo json_encode(['Status' => false, 'Message' => 'Party id missing.']);
                    return;
                }
            }
        }
        
        public function PendingOrderbookEstimate() 
        {
            if ($this->input->is_ajax_request()) {
                $PartyId = $this->input->post('PartyId');
                if (isset($PartyId) && !empty($PartyId)) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $Where = ['IsDeleted' => 1, 'InvoiceStatus' => 1, 'VoucherType' => 'Estimate', 'PartyId' => $PartyId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    if ($this->db->select('Id')->where($Where)->get('orderbook')->num_rows()) {
                        $OrderbookData = $this->db->select('Id, OrderDate, PartyId, SubTotal, SalesmanId')->where($Where)->get('orderbook')->result();
                        echo json_encode(['Status' => true, 'Data' => $OrderbookData, 'Message' => 'Pending orderbook found for this party.']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Message' => 'No record found.']);
                        return;
                    }
                } else {
                    echo json_encode(['Status' => false, 'Message' => 'Party id missing.']);
                    return;
                }
            }
        }
        
        public function getSalesmanName($SalesmanId) 
        {
            if ($this->input->is_ajax_request()) {
                $SalesmanName = get_title('employees', ['Id' => $SalesmanId], 'Name');
                echo json_encode($SalesmanName);
            }
        }
        
        public function getPartyName($PartyId) 
        {
            if ($this->input->is_ajax_request()) {
                $PartyName = get_title('parties', ['id' => $PartyId], 'name');
                echo json_encode($PartyName);
            }
        }
        
        public function OrderStatus($Id) 
        {
            if (isset($Id) && !empty($Id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                    
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'InvoiceStatus' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->db->where($Where)->get('orderbook')->num_rows()) {
                    $OrderbookData = [
                        "InvoiceStatus" => 0,
                        "Updated" => date('d-m-Y h:i:s A'),
                        "UpdatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                    ];
                    if ($this->db->where($Where)->update('orderbook', $OrderbookData)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);                        
                    } else {
                        $this->session->set_flashdata(['msg' => 'somthigs went wrong, try again.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);                        
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderbook id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }