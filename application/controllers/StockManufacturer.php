<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class StockManufacturer extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            
            if (empty($this->session->userdata('user_id'))) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function index() 
        {
            $CompId = $this->session->userdata('comp_id');
            $UserId = $this->session->userdata('user_id');
            
            $VoucherNumberWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $CompanyWhere = ['id' => $CompId, 'user_id' => $UserId];
            
            $VoucherNumber = $this->db->select('VoucherNumber')->where($VoucherNumberWhere)->order_by('Id', 'DESC')->get('stock_manufacturer')->row('VoucherNumber');
            $SeriesNumber = $VoucherNumber += 1;
            if ($SeriesNumber < '10') {
                $NewSeries = "000$VoucherNumber";
            } elseif ($SeriesNumber < '100') {
                $NewSeries = "00$VoucherNumber";
            } elseif ($SeriesNumber < '1000') {
                $NewSeries = "0$VoucherNumber";
            } elseif ($SeriesNumber < '10000') {
                $NewSeries = "$VoucherNumber";
            } else {
                $NewSeries = "$VoucherNumber";
            }
            
            $Data['VoucherNumber'] = $NewSeries;
            $Data['Company'] = $this->db->select('id, company_name')->where($CompanyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
            $Data['page'] = 'StockManufacturer/Create';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Save() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('VoucherNumber', 'Voucher Number', 'trim|required');
                $this->form_validation->set_rules('CompId', 'Company', 'trim|required');
                $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    foreach ($this->input->post('Products') as $key => $inputProduct) {
                        if (!empty($inputProduct['Quantity'])) {                    
                            $productQuery = $this->db->select('p_name, packetQty, rewardPoint')->where('id', $inputProduct['ProductId'])->get('product');
                            if ($productQuery->num_rows() > 0) {
                                if (empty($productQuery->row()->packetQty) && empty($productQuery->row()->rewardPoint)) {
                                    echo json_encode(['Status' => false, 'Message' => 'Packet qty or reward point is not update for product ' . $productQuery->row()->p_name]);
                                    return;
                                }
                            }
                        }
                    }

                    $StockManufacturerData = [
                        'Date' => $this->input->post('Date'),
                        'VoucherNumber' => $this->input->post('VoucherNumber'),
                        'SubTotal' => $this->input->post('SubTotal'), 
                        'Remark' => $this->input->post('Remark'),
                        'CompId' => $this->input->post('CompId'),
                        'UserId' => $this->session->userdata('user_id'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                    ];
                    $Result = $this->user_model->saveAry('stock_manufacturer', $StockManufacturerData);
                    
                    $this->load->library('zend');
                    $this->zend->load('Zend/Barcode');
                    
                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity'])) {
                            $StockManufacturerItems = [
                                "Date" => $this->input->post('Date'),
                                "StockManufacturerId" => $Result,
                                "VoucherNumber" => $this->input->post('VoucherNumber'),
                                "ProductName" => $Product['ProductName'],
                                "ProductId" => $Product['ProductId'],
                                "Price" => $Product['ProductPrice'],
                                "Quantity" => $Product['Quantity'],
                                "Total" => $Product['Total'],
                                "UserId" => $this->session->userdata('user_id'),
                                "CompId" => $this->input->post('CompId'),
                                "WarehouseId" => get_title('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->input->post('CompId')], 'warehouse'),
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('stock_manufacturer_products', $StockManufacturerItems);
                            
                            // Barcode
                            $productQueryForBarcode = $this->db->select('p_name, packetQty, rewardPoint')->where('id', $Product['ProductId'])->get('product');
                            if ($productQueryForBarcode->num_rows()) {
                                $productInfoForBarcode = $productQueryForBarcode->row();
                                if (!empty($productInfoForBarcode->packetQty) && !empty($productInfoForBarcode->rewardPoint)) {
                                    $barcodeCount = (int)($Product['Quantity'] / $productInfoForBarcode->packetQty);
                                    for ($i = 0; $i < $barcodeCount; $i++) {
                                        $productCode = random_int(0, 99999999999999999);
                                        $imageResource = Zend_Barcode::factory(
                                            'code128', 
                                            'image', 
                                            array(
                                                'text' => $productCode,
                                                'barHeight' => 10,
                                                'factor' => 1,
                                                'drawText' => false
                                            ), 
                                            array()
                                        )->draw();

                                        $imageName = time() . $productCode . '.png';
                                        imagepng($imageResource, 'uploads/barcodeImage/' . $imageName);

                                        $productBarcodeData = [
                                            'date' => date('Y-m-d'),
                                            'barcode' => $productCode,
                                            'qrImage' => $imageName,
                                            'rewardPoints' => $productInfoForBarcode->rewardPoint,
                                            'voucherId' => $Result,
                                            'productId' => $Product['ProductId'],
                                            'userId' => $this->session->userdata('user_id'),
                                            'compId' => $this->input->post('CompId'),
                                            'created' => date('d-m-Y h:i:s A'),
                                            'createdBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                                        ];
                                        $this->user_model->saveAry('products_barcode', $productBarcodeData);
                                    }
                                }
                            }
                        }
                    }
                    
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
        
        public function Reports() 
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
            
            $Where = ['Date >= ' => $From, 'Date <= ' => $To, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            
            $Data['StockManufacturers'] = $this->db->select('Id, Date, VoucherNumber, CompId, SubTotal')->where($Where)->order_by('Id', 'DESC')->get('stock_manufacturer')->result();
            $Data['page'] = 'StockManufacturer/index';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Info($Id)
        {
            if (isset($Id) && !empty($Id)) {
                $CompId = $this->session->userdata('comp_id');
                $UserId = $this->session->userdata('user_id');

                $Where = ['Id' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $ItemsWhere = ['StockManufacturerId' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                
                if ($this->db->select('Id')->where($Where)->get('stock_manufacturer')->num_rows()) {
                    $Data['StockManufacturerData'] = $this->db->where($Where)->get('stock_manufacturer')->row();
                    $Data['StockManufacturerProducts'] = $this->db->where($ItemsWhere)->order_by('Id', 'DESC')->get('stock_manufacturer_products')->result();
                    return $this->load->view('inventory_sep/StockManufacturer/Info', $Data);
                } else {
                    return redirect(base_url() . "StockManufacturer/Reports");
                }
            } else {
                return redirect(base_url() . "StockManufacturer/Reports");
            }
        }
        
        public function Print($Id)
        {
            if (isset($Id) && !empty($Id)) {
                $CompId = $this->session->userdata('comp_id');
                $UserId = $this->session->userdata('user_id');

                $CompanyWhere = ['id' => $CompId, 'user_id' => $UserId];
                $Where = ['Id' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $ItemsWhere = ['StockManufacturerId' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                
                if ($this->db->select('Id')->where($Where)->get('stock_manufacturer')->num_rows()) {
                    $Data['CompanyData'] = $this->db->select('company_name, address, state, number, email')->where($CompanyWhere)->get('inventory_billing')->row();
                    $Data['StockManufacturerData'] = $this->db->where($Where)->get('stock_manufacturer')->row();
                    $Data['StockManufacturerProducts'] = $this->db->where($ItemsWhere)->order_by('Id', 'DESC')->get('stock_manufacturer_products')->result();
                    return $this->load->view('inventory_sep/StockManufacturer/Print', $Data);
                } else {
                    return redirect(base_url() . "StockManufacturer/Reports");
                }
            } else {
                return redirect(base_url() . "StockManufacturer/Reports");
            }
        }
        
        public function barcodePrint($stockManufacturerId)
        {
            if (isset($stockManufacturerId) && !empty($stockManufacturerId)) {
                $compId = $this->session->userdata('comp_id');
                $userId = $this->session->userdata('user_id');

                $companyWhere = ['Id' => $compId, 'user_id' => $userId];
                $where = ['StockManufacturerId' => $stockManufacturerId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
                
                $data['companyData'] = $this->db->select('company_name')->where($companyWhere)->get('inventory_billing')->row();
                $data['stockManufacturerId'] = $stockManufacturerId;
                $data['stockManufacturerProducts'] = $this->db->select('ProductId')->where($where)->order_by('Id', 'DESC')->get('stock_manufacturer_products')->result();
                return $this->load->view('inventory_sep/StockManufacturer/barcodePrint', $data);
            } else {
                return redirect(base_url() . "StockManufacturer/Reports");
            }
        }
        
        public function getStockManufacturerProducts($StockManufacturerId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($StockManufacturerId)) {
                    $ItemsWhere = ['StockManufacturerId' => $StockManufacturerId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    $StockManufacturerItems = $this->db->select('Id, ProductName, ProductId, Price, Quantity, Total')->where($ItemsWhere)->order_by('Id', 'DESC')->get('stock_manufacturer_products')->result();
                    if ($StockManufacturerItems) {
                        echo json_encode(array('Status' => true, "Data" => $StockManufacturerItems));
                    } else {
                        echo json_encode(array('Status' => false, "Data" => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, "Data" => ""));
                }
            }
        }
        
        public function getStockManufacturerEdit() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $Id = $this->input->post('Id');
                if ($Id) {
                    $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];

                    if ($this->db->select('Id')->where($Where)->get('stock_manufacturer')->num_rows()) {
                        $StockManufacturerId = $this->db->select('Id')->where($Where)->get('stock_manufacturer')->row('Id');
                        echo json_encode(['Status' => true, 'StockManufacturerId' => $StockManufacturerId]);
                    } else {
                        echo json_encode(['Status' => false, 'msg' => 'No Record found.']);
                    }
                } else {
                    echo json_encode(['Status' => false, 'msg' => 'Id is missing.']);
                }
            } else {
                echo json_encode(['Status' => false, 'msg' => 'Direct entry not allowed.']);
            }    
        }
        
        public function Edit($Id) 
        {
            if (isset($Id) && !empty($Id)) {
                $CompId = $this->session->userdata('comp_id');
                $UserId = $this->session->userdata('user_id');

                $Where = ['Id' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $CompanyWhere = ['id' => $CompId, 'user_id' => $UserId];                
                
                if ($this->db->select('Id')->where($Where)->get('stock_manufacturer')->num_rows()) {
                    $Data['StockManufacturerData'] = $this->db->where($Where)->get('stock_manufacturer')->row();
                    $Data['Company'] = $this->db->select('id, company_name')->where($CompanyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
                    $Data['StockManufacturerId'] = $Id;
                    $Data['page'] = 'StockManufacturer/Edit';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    return redirect(base_url() . "StockManufacturer/Reports");
                }
            } else {
                return redirect(base_url() . "StockManufacturer/Reports");
            }
        }
        
        public function Update() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                $this->form_validation->set_rules('VoucherNumber', 'Voucher Number', 'trim|required');
                $this->form_validation->set_rules('CompId', 'Company', 'trim|required');
                $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    foreach ($this->input->post('Products') as $key => $inputProduct) {
                        if (!empty($inputProduct['Quantity'])) {                    
                            $productQuery = $this->db->select('p_name, packetQty, rewardPoint')->where('id', $inputProduct['ProductId'])->get('product');
                            if ($productQuery->num_rows() > 0) {
                                if (empty($productQuery->row()->packetQty) && empty($productQuery->row()->rewardPoint)) {
                                    echo json_encode(['Status' => false, 'Message' => 'Packet Qty or Reward Point is not update for product ' . $productQuery->row()->p_name]);
                                    return;                                
                                }
                            }
                        }
                    }
                    
                    $StockManufacturerData = [
                        'Date' => $this->input->post('Date'),
                        'CompId' => $this->input->post('CompId'),
                        'SubTotal' => $this->input->post('SubTotal'),
                        'Remark' => $this->input->post('Remark'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $StockManufacturerId = $this->input->post('StockManufacturerId');
                    $Where = ['Id' => $StockManufacturerId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    $Result = $this->user_model->updateAry('stock_manufacturer', $StockManufacturerData, $Where);
                    
                    $ItemsWhere = ['StockManufacturerId' => $StockManufacturerId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    $PreviewsStockManufacturerItems = $this->db->select('Id, ProductId')->where($ItemsWhere)->get('stock_manufacturer_products')->result();
                    foreach ($PreviewsStockManufacturerItems as $key => $Items) {
                        $this->db->where('Id', $Items->Id)->delete('stock_manufacturer_products');
                        $this->db->where('voucherId', $StockManufacturerId)->delete('products_barcode');
                    }

                    $this->load->library('zend');
                    $this->zend->load('Zend/Barcode');
                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity'])) {
                            $StockManufacturerDataItems = [
                                "Date" => $this->input->post('Date'),
                                "StockManufacturerId" => $StockManufacturerId,
                                'VoucherNumber' => $this->input->post('VoucherNumber'),
                                "ProductName" => $Product['ProductName'],
                                "ProductId" => $Product['ProductId'],
                                "Price" => $Product['ProductPrice'],
                                "Quantity" => $Product['Quantity'],
                                "Total" => $Product['Total'],
                                "UserId" => $this->session->userdata('user_id'),
                                "CompId" => $this->session->userdata('comp_id'),
                                'WarehouseId' => get_title('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->input->post('CompId')], 'warehouse'),
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('stock_manufacturer_products', $StockManufacturerDataItems);
                            
                            // Barcode
                            $productQueryForBarcode = $this->db->select('p_name, packetQty, rewardPoint')->where('id', $Product['ProductId'])->get('product');
                            if ($productQueryForBarcode->num_rows()) {
                                $productInfoForBarcode = $productQueryForBarcode->row();
                                if (!empty($productInfoForBarcode->packetQty) && !empty($productInfoForBarcode->rewardPoint)) {
                                    $barcodeCount = (int)($Product['Quantity'] / $productInfoForBarcode->packetQty);
                                    for ($i = 0; $i < $barcodeCount; $i++) {
                                        $productCode = random_int(0, 99999999999999999);
                                        $imageResource = Zend_Barcode::factory(
                                            'code128', 
                                            'image', 
                                            array(
                                                'text' => $productCode,
                                                'barHeight' => 10,
                                                'factor' => 1,
                                                'drawText' => false
                                            ), 
                                            array()
                                        )->draw();

                                        $imageName = time() . $productCode . '.png';
                                        imagepng($imageResource, 'uploads/barcodeImage/' . $imageName);

                                        $productBarcodeData = [
                                            'date' => date('Y-m-d'),
                                            'barcode' => $productCode,
                                            'qrImage' => $imageName,
                                            'rewardPoints' => $productInfoForBarcode->rewardPoint,
                                            'voucherId' => $StockManufacturerId,
                                            'productId' => $Product['ProductId'],
                                            'userId' => $this->session->userdata('user_id'),
                                            'compId' => $this->input->post('CompId'),
                                            'created' => date('d-m-Y h:i:s A'),
                                            'createdBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                                        ];
                                        $this->user_model->saveAry('products_barcode', $productBarcodeData);
                                    }
                                }
                            }
                        }
                    }
                    
                    if (!empty($Result)) {
                        echo json_encode(['Status' => true, 'Message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Message' => 'Unable to save record']);
                        return;
                    }
                }
            }   
        }
        
        public function Delete($Id) 
        {
            if (isset($Id) && !empty($Id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                $WhereItem = ['StockManufacturerId' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                $barcodeWhere = ['voucherId' => $Id, 'isDeleted' => 1, 'userId' => $UserId, 'compId' => $CompId];

                if ($this->db->select('Id')->where($Where)->get('stock_manufacturer')->num_rows()) {
                    if ($this->user_model->updateAry('stock_manufacturer', ['IsDeleted' => 0], $Where)) {
                        $this->user_model->updateAry('stock_manufacturer_products', ['IsDeleted' => 0], $WhereItem);
                        $this->user_model->updateAry('products_barcode', ['isDeleted' => 0], $barcodeWhere);
                        echo json_encode(['status' => true, 'msg' => 'Record deleted successfully.']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false, 'msg' => 'No record found.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Stock manufacturer id is missing.']);
                return;
            }
        }
    }