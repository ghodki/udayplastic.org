<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class RawMaterial extends CI_Controller
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
            
            $Where = ['CompId' => $CompId, 'UserId' => $UserId, 'IsDeleted' => 1];
            $Data['RawMaterials'] = $this->db->where($Where)->order_by('Id', 'DESC')->get('raw_material')->result();
            $Data['page'] = 'RawMaterial/index';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Create() 
        {
            $compId = $this->session->userdata('comp_id');
            $userId = $this->session->userdata('user_id');
            
            $where = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1, 'type' => 'Finish'];
            $data['products'] = $this->db->select('id, p_name')->where($where)->order_by('id', 'DESC')->get('product')->result();
            $data['page'] = 'RawMaterial/Create';
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function Save() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('ProductId', 'Product Name', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $RawMaterialData = [
                        'ProductId' => $this->input->post('ProductId'),
                        'Remark' => $this->input->post('Remark'),
                        'CompId' => $this->session->userdata('comp_id'),
                        'UserId' => $this->session->userdata('user_id'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                    ];
                    $Result = $this->user_model->saveAry('raw_material', $RawMaterialData);
                    
                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity'])) {
                            $RawMaterialItems = [
                                "RawMaterialId" => $Result,
                                "MainProductId" => $this->input->post('ProductId'),
                                "ProductId" => $Product['ProductId'],
                                "ProductName" => $Product['ProductName'],
                                "Quantity" => $Product['Quantity'],
                                "Price" => $Product['Price'],
                                "Weight" => $Product['Weight'],
                                "Unit" => $Product['Unit'],
                                "CompId" => $this->session->userdata('comp_id'),
                                "UserId" => $this->session->userdata('user_id'),
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('raw_material_products', $RawMaterialItems);
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
        
        public function Info($Id)
        {
            if (isset($Id) && !empty($Id)) {
                $CompId = $this->session->userdata('comp_id');
                $UserId = $this->session->userdata('user_id');

                $Where = ['Id' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $ItemsWhere = ['RawMaterialId' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                
                if ($this->db->select('Id')->where($Where)->get('raw_material')->num_rows()) {
                    $Data['RawMaterialData'] = $this->db->where($Where)->get('raw_material')->row();
                    $Data['RawMaterialProducts'] = $this->db->where($ItemsWhere)->order_by('Id', 'DESC')->get('raw_material_products')->result();
                    return $this->load->view('inventory_sep/RawMaterial/Info', $Data);
                } else {
                    return redirect(base_url() . "RawMaterial");
                }
            } else {
                return redirect(base_url() . "RawMaterial");
            }
        }
        
        public function Edit($rawMaterialId) 
        {
            if (isset($rawMaterialId) && !empty($rawMaterialId)) {
                $compId = $this->session->userdata('comp_id');
                $userId = $this->session->userdata('user_id');

                $where = ['Id' => $rawMaterialId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
                $productWhere = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1, 'type' => 'Finish'];
                
                if ($this->db->select('Id')->where($where)->get('raw_material')->num_rows()) {
                    $data['rawMaterialData'] = $this->db->where($where)->get('raw_material')->row();
                    $data['products'] = $this->db->select('id, p_name')->where($productWhere)->order_by('id', 'DESC')->get('product')->result();
                    $data['rawMaterialId'] = $rawMaterialId;
                    $data['page'] = 'RawMaterial/Edit';
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    return redirect(base_url() . "RawMaterial");
                }
            } else {
                return redirect(base_url() . "RawMaterial");
            }
        }
        
        public function getRawMaterialProducts($RawMaterialId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($RawMaterialId)) {
                    $ItemsWhere = ['RawMaterialId' => $RawMaterialId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    $RawMaterialItems = $this->db->select('Id, ProductId, ProductName, Quantity, Price, Weight, Unit')->where($ItemsWhere)->order_by('Id', 'DESC')->get('raw_material_products')->result();
                    if ($RawMaterialItems) {
                        echo json_encode(array('Status' => true, "Data" => $RawMaterialItems));
                    } else {
                        echo json_encode(array('Status' => false, "Data" => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, "Data" => ""));
                }
            }
        }
        
        
        public function Update() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('ProductId', 'ProductId', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $RawMaterialData = [
                        'ProductId' => $this->input->post('ProductId'),
                        'Remark' => $this->input->post('Remark'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $Where = ['Id' => $this->input->post('RawMaterialId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    $Result = $this->user_model->updateAry('raw_material', $RawMaterialData, $Where);
                    
                    // Update
                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity']) && $Product['RawMaterialProductId']) {
                            $RawMaterialDataItems = [
                                "MainProductId" => $this->input->post('ProductId'),
                                "ProductId" => $Product['ProductId'],
                                "ProductName" => $Product['ProductName'],
                                "Quantity" => $Product['Quantity'],
                                "Price" => $Product['Price'],
                                "Weight" => $Product['Weight'],
                                "Unit" => $Product['Unit'],
                                "Updated" => date('d-m-Y h:i:s A'),
                                "UpdatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            
                            $ItemsWhere = ['Id' => $Product['RawMaterialProductId'], 'RawMaterialId' => $this->input->post('RawMaterialId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                            $this->user_model->updateAry('raw_material_products', $RawMaterialDataItems, $ItemsWhere);
                        } else {
                            $NewItemsWhere = ['RawMaterialId' => $this->input->post('RawMaterialId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                            $PreviewsRawMaterialItems = $this->db->select('Id')->where($NewItemsWhere)->get('raw_material_products')->row();
                            if ($PreviewsRawMaterialItems->Id != $Product['RawMaterialProductId']) {
                                $this->user_model->updateAry('raw_material_products', ['IsDeleted' => 0], ['Id' => $PreviewsRawMaterialItems->Id]);
                            }
                        }
                    } 
                    
                    // Insert
                    foreach ($this->input->post('AddProducts') as $key => $AddProduct) {
                        if (!empty($AddProduct['Quantity'])) {
                            $RawMaterialItems = [
                                "RawMaterialId" => $this->input->post('RawMaterialId'),
                                "MainProductId" => $this->input->post('ProductId'),
                                "ProductId" => $AddProduct['ProductId'],
                                "ProductName" => $AddProduct['ProductName'],
                                "Quantity" => $AddProduct['Quantity'],
                                "Price" => $AddProduct['Price'],
                                "Weight" => $AddProduct['Weight'],
                                "Unit" => $AddProduct['Unit'],
                                "CompId" => $this->session->userdata('comp_id'),
                                "UserId" => $this->session->userdata('user_id'),
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('raw_material_products', $RawMaterialItems);
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
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->db->where($Where)->get('raw_material')->num_rows()) {
                    if ($this->user_model->updateAry('raw_material', ['IsDeleted' => 0], $Where)) {
                        $ItemsWhere = ['RawMaterialId' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                        $this->user_model->updateAry('raw_material_products', ['IsDeleted' => 0], $ItemsWhere);
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'RawMaterial');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'RawMaterial');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                    return redirect(base_url() . 'RawMaterial');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Raw material id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'RawMaterial');
            }
        }
    }