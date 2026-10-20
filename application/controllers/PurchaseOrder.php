<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class PurchaseOrder extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper', 'date', 'file']);
            $this->load->library('ajax_pagination_dashboard');
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
            if ($this->input->get('From') && $this->input->get('To')) {
                $From = $this->input->get('From');
                $To = $this->input->get('To');
            } else {
                $From = date('Y-m-d');
                $To = date('Y-m-d');
            }
            $Where = ['PurchaseDate >= ' => $From, 'PurchaseDate <= ' => $To, 'IsDeleted' => 1, 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                $Where['UserId'] = $this->session->userdata('user_id');
            }
            $data['OrderBook'] = $this->db->select('Id, PurchaseDate, PurchaseNumber, CompanyId, SubTotal, CompanyBranchId, UserId, CompId, InvoiceStatus')->where($Where)->order_by('Id', 'DESC')->get('purchaseorder')->result();
            $data['page'] = "PurchaseOrder/index";
            return $this->load->view('inventory_sep/inventory', $data); 
        }

        public function getMinimumQuantityProducts()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ProductsWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $ProductsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Products = $this->db->select('id, p_name, min_qty, price')->where($ProductsWhere)->order_by('id', 'DESC')->get('product')->result();
                if ($Products) {
                    echo json_encode(array('Status' => true, "Data" => $Products));
                } else {
                    echo json_encode(array('Status' => false, "Data" => ""));
                }
            }
        }
        
        public function create()
        {
            if ($this->session->userdata('BranchRole')) {
                $BranchWhere = ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->db->select('Id')->where($BranchWhere)->get('companybranch')->num_rows()) {
                    $CompanyWhere = ['id' => $this->session->userdata('comp_id')];
                    $data['ParentCompany'] = $this->db->select('id, company_name')->where($CompanyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
                    $data['page'] = "PurchaseOrder/create";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not have permission.', 'class' => 'danger']);
                    return redirect(base_url() . 'PurchaseOrder');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'You do not have permission.', 'class' => 'danger']);
                return redirect(base_url() . 'PurchaseOrder');
            } 
        }
         
        public function PurchaseOrderSave() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('CompanyId', 'Parent Company', 'trim|required');
                $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                if ($this->form_validation->run() == false) {
                    $BranchWhere = ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    if ($this->db->select('Id')->where($BranchWhere)->get('companybranch')->num_rows()) {
                        $CompanyWhere = ['id' => $this->session->userdata('comp_id')];
                        $data['ParentCompany'] = $this->db->select('id, company_name')->where($CompanyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
                    }
                    $data['page'] = "PurchaseOrder/create";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $OrderNumber = mt_rand(00000, 99999);
                    
                    $CompProfileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $CompProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                    
                    $OrderbookData = [
                        'PurchaseDate' => $this->input->post('Date'),
                        'PurchaseNumber' => $OrderNumber,
                        'CompanyId' => $this->input->post('CompanyId'),
                        'SubTotal' => $this->input->post('SubTotal'), 
                        'UserId' => $this->session->userdata('user_id'),
                        'CompId' => $this->session->userdata('comp_id'),
                        'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                        'FinancialYear' => $FinancialYear,
                        'Remark' => $this->input->post('Remark'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('purchaseorder', $OrderbookData);
                    
                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity']) && !empty($Product['ProductId'])) {
                            $OrderbookDataItems = [
                                "PurchaseOrderId" => $Result,
                                'PurchaseOrderNumber' => $OrderNumber,
                                "PurchaseOrderDate" => $this->input->post('Date'),
                                "ProductName" => $Product['ProductName'],
                                "ProductId" => $Product['ProductId'],
                                "Price" => $Product['ProductPrice'],
                                "Quantity" => $Product['Quantity'],
                                "Total" => $Product['Total'],
                                "UserId" => $this->session->userdata('user_id'),
                                "CompId" => $this->session->userdata('comp_id'),
                                "CompanyBranchId" => $this->session->userdata('CompBranchId'),
                                "FinancialYear" => $FinancialYear,
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('purchaseorder_products', $OrderbookDataItems);
                        }
                    }
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'PurchaseOrder/index');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'PurchaseOrder/create');
                    }
                }
            }   
        }
        
        public function CreateSaleInvoice($Id) 
        {
            if (!empty($Id)) {
                $CompId = $this->session->userdata('comp_id');
                $UserId = $this->session->userdata('user_id');
                $FinancialYear = $this->session->userdata('financial_year');
                $CompanyBranchId = get_title('purchaseorder', ['Id' => $Id], 'CompanyBranchId');
                
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $PartyWhere = ['CompanyBranchId' => $CompanyBranchId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->db->where($Where)->get('purchaseorder')->num_rows()) {
                    $data['PartyId'] = $this->db->select('id')->where($PartyWhere)->get('parties')->row('id');
                    $data['PurchaseOrderInfo'] = $this->db->where($Where)->get('purchaseorder')->row();
                    $data['PurchaseOrderId'] = $Id;
                    $data['page'] = "PurchaseOrder/CreateSaleInvoice";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'PurchaseOrder/index');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Purchase Order id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'PurchaseOrder/index');
            }
        }
        
        public function getPurchaseOrderProductsForSaleInvoice() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $PurchaseOrderId = $this->input->post('PurchaseOrderId');
                if (!empty($PurchaseOrderId)) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $FinancialYear = $this->session->userdata('financial_year');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $PurchaseOrderProductWhere = ['PurchaseOrderId' => $PurchaseOrderId, 'IsDeleted' => 1, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                    $WarehouseWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $PurchaseOrderProductWhere['CompanyBranchId'] = $CompBranchId; 
                        $WarehouseWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $PurchaseOrderProductInfo = $this->db->select('ProductName, Quantity, Price')->where($PurchaseOrderProductWhere)->get('purchaseorder_products')->result();
                    $ProductArray = [];
                    foreach ($PurchaseOrderProductInfo as $key => $Products) {
                        $ProductWhere = ['p_name' => $Products->ProductName, 'user_id' => $UserId, 'comp_id' => $CompId];
                        $ProductInfo = $this->db->where($ProductWhere)->get('product')->row();
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
                    echo json_encode(['Status' => false, 'ProductDataArray' => 'Purchase Order id missing.']);
                }
            }
        }
        
        public function PurchaseOrderInfo($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
               if($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $Where['UserId'] = $this->session->userdata('user_id');
                }
                if ($this->db->where($Where)->get('purchaseorder')->num_rows()) {
                    $data['OrderBook'] = $this->db->select('Id, PurchaseDate, PurchaseNumber, CompanyId, CompanyBranchId, SubTotal, Remark, Created, CreatedBy, Updated, UpdatedBy')->where($Where)->get('purchaseorder')->row();
                    $ItemsWhere = ['PurchaseOrderId' => $Id, 'IsDeleted' => 1, 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $ItemsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        $ItemsWhere['UserId'] = $this->session->userdata('user_id');
                    }
                    $data['OrderBookItems'] = $this->db->select('ProductId, Price, Quantity, Total')->where($ItemsWhere)->order_by('Id', 'DESC')->get('purchaseorder_products')->result(); 
                    $data['page'] = "PurchaseOrder/info";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'PurchaseOrder/PurchaseOrderInfo');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Purchase Order id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'PurchaseOrder/PurchaseOrderInfo');
            }
        }
        
        public function PurchaseOrderEdit($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
               if($this->session->userdata('BranchRole')){
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->db->where($Where)->get('purchaseorder')->num_rows()) {
                    $CompanyWhere = ['id' => $this->session->userdata('comp_id')];
                    $data['ParentCompany'] = $this->db->select('id, company_name')->where($CompanyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
                    $data['PurchaseOrder'] = $this->db->select('Id, PurchaseDate, CompanyId, SubTotal, Remark')->where($Where)->get('purchaseorder')->row(); 
                    $data['PurchaseOrderId'] = $Id;
                    $data['page'] = "PurchaseOrder/edit";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'PurchaseOrder/index');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Purchase Order id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'PurchaseOrder/create');
            }
        }
        
        public function getPurchaseOrderProduct($OrderBookId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($OrderBookId)) {
                    $ItemsWhere = ['PurchaseOrderId' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $ItemsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $OrderBookItems = $this->db->select('Id, ProductName, ProductId, Price, Quantity, Total')->where($ItemsWhere)->order_by('Id', 'DESC')->get('purchaseorder_products')->result();
                    if ($OrderBookItems) {
                        echo json_encode(array('Status' => true, "Data" => $OrderBookItems));
                    } else {
                        echo json_encode(array('Status' => false, "Data" => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, "Data" => ""));
                }
            }
        }
        
        public function getMinimumQuantityProduct()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ProductId = $this->input->post('ProductId');
                if (!empty($ProductId)) {
                    $ProductMinQty = $this->db->select('min_qty')->where('id', $ProductId)->get('product')->row('min_qty');
                    if ($ProductMinQty) {
                        echo json_encode(array('Status' => true, "Data" => $ProductMinQty));
                    } else {
                        echo json_encode(array('Status' => false, "Data" => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, "Data" => ""));
                }
            }
        }
        
        public function PurchaseOrderUpdate($Id) 
        {
            if ($_POST) {
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('CompanyId', 'Parent Company', 'trim|required');
                $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                if ($this->form_validation->run() == false) {
                    $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if($this->session->userdata('BranchRole')){
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $CompanyWhere = ['id' => $this->session->userdata('comp_id')];
                    $data['ParentCompany'] = $this->db->select('id, company_name')->where($CompanyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
                    $data['OrderBook'] = $this->db->select('Id, PurchaseDate, PurchaseNumber, PartyId, SubTotal, Remark')->where($Where)->get('purchaseorder')->row(); 
                    $data['OrderBookId'] = $Id;
                    $data['page'] = "PurchaseOrder/edit";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $CompProfileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $CompProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                    
                    $OrderbookData = [
                        'PurchaseDate' => $this->input->post('Date'),
                        'CompanyId' => $this->input->post('CompanyId'),
                        'SubTotal' => $this->input->post('SubTotal'),
                        'Remark' => $this->input->post('Remark'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    $Where = ['Id' => $this->input->post('PurchaseOrderId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $FinancialYear];
                    $Result = $this->user_model->updateAry('purchaseorder', $OrderbookData, $Where);
                    
                    $ItemsWhere = ['PurchaseOrderId' => $this->input->post('PurchaseOrderId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $FinancialYear];
                    $PreviewsOrderItems = $this->db->where($ItemsWhere)->get('purchaseorder_products')->result();
                    foreach ($PreviewsOrderItems as $key => $Items) {
                        $this->db->where('Id', $Items->Id)->delete('purchaseorder_products');
                    }
                    
                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity']) && !empty($Product['ProductId'])) {
                            $OrderbookDataItems = [
                                "PurchaseOrderId" => $this->input->post('PurchaseOrderId'),
                                'PurchaseOrderNumber' => get_title('purchaseorder', ['Id' => $this->input->post('PurchaseOrderId')], 'PurchaseNumber'),
                                "PurchaseOrderDate" => $this->input->post('Date'),
                                "ProductName" => $Product['ProductName'],
                                "ProductId" => $Product['ProductId'],
                                "Price" => $Product['ProductPrice'],
                                "Quantity" => $Product['Quantity'],
                                "Total" => $Product['Total'],
                                "UserId" => $this->session->userdata('user_id'),
                                "CompId" => $this->session->userdata('comp_id'),
                                "CompanyBranchId" => $this->session->userdata('CompBranchId'),
                                "FinancialYear" => $FinancialYear,
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('purchaseorder_products', $OrderbookDataItems);
                        }
                    } 
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'PurchaseOrder/index');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'PurchaseOrder/edit/' . $Id);
                    }
                }
            }   
        }
        
        public function PurchaseOrderDelete($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                if ($this->db->where($Where)->get('PurchaseOrder')->num_rows()) {
                    if ($this->user_model->updateAry('purchaseorder', ['IsDeleted' => 0], $Where)) {
                        $ItemsWhere = ['PurchaseOrderId' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                       if($this->session->userdata('BranchRole')){
                            $ItemsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        $this->user_model->updateAry('purchaseorder_products', ['IsDeleted' => 0], $ItemsWhere);
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']); 
                        return redirect(base_url() . 'PurchaseOrder/create');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'PurchaseOrder/create');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                    return redirect(base_url() . 'PurchaseOrder/create/');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Purchase Order id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'PurchaseOrder/create');
            }
        }
        
        public function PurchaseOrderCancel($PurchaseOrderId) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!$PurchaseOrderId) {
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
                $Where = ['Id' => $PurchaseOrderId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'FinancialYear' => $FinancialYear];
                $PurchaseOrderProductWhere = ['OrderbookId' => $PurchaseOrderId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'FinancialYear' => $FinancialYear];
                if ($this->session->userdata('BranchRole')){
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $PurchaseOrderProductWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $PurchaseOrderResult = $this->user_model->updateAry('purchaseorder', $Data, $Where);
                if ($PurchaseOrderResult) {
                    $PurchaseOrderProducts = $this->user_model->getAry('purchaseorder_products', '', '', '', $PurchaseOrderProductWhere);
                    if ($PurchaseOrderProducts) {
                        foreach ($PurchaseOrderProducts as $key => $PurchaseOrderProduct) {
                            $this->user_model->updateAry('purchaseorder_products', $Data, $PurchaseOrderProductWhere);
                        }
                    }
                    echo json_encode(['Status' => true, 'Message' => 'Purchase Order cancelled successfully.']);
                    return;
                } else {
                    echo json_encode(['Status' => false, 'Message' => 'There is some issue, Please try again.']);
                    return;
                }
            }
        }
    }