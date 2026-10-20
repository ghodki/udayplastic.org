<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Vestige extends CI_Controller
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
            $compId = $this->session->userdata('comp_id');
            $userId = $this->session->userdata('user_id');

            $voucherNumberWhere = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];            
            $companyWhere = ['id' => $compId, 'user_id' => $userId];
            
            $voucherNumber = $this->db->select('voucherNumber')->where($voucherNumberWhere)->order_by('id', 'DESC')->get('vestiges')->row('voucherNumber');
            $seriesNumber = $voucherNumber += 1;
            if ($seriesNumber < '10') {
                $newSeries = "000$voucherNumber";
            } elseif ($seriesNumber < '100') {
                $newSeries = "00$voucherNumber";
            } elseif ($seriesNumber < '1000') {
                $newSeries = "0$voucherNumber";
            } elseif ($seriesNumber < '10000') {
                $newSeries = "$voucherNumber";
            } else {
                $newSeries = "$voucherNumber";
            }
            
            $data['voucherNumber'] = $newSeries;
            $data['companies'] = $this->db->select('id, company_name')->where($companyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
            $data['page'] = 'vestige/create';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function save() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required');
                $this->form_validation->set_rules('compId', 'Company', 'trim|required');
                $this->form_validation->set_rules('subTotal', 'Sub Total', 'trim|required|numeric');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $vestigeData = [
                        'date' => $this->input->post('date'),
                        'voucherNumber' => $this->input->post('voucherNumber'),
                        'subTotal' => $this->input->post('subTotal'), 
                        'remark' => $this->input->post('remark'),
                        'compId' => $this->input->post('compId'),
                        'userId' => $this->session->userdata('user_id'),
                        'created' => date('d-m-Y h:i:s A'),
                        'createdBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                    ];
                    $result = $this->user_model->saveAry('vestiges', $vestigeData);
                    
                    foreach ($this->input->post('products') as $key => $product) {
                        if (!empty($product['quantity'])) {
                            $vestigeItems = [
                                "date" => $this->input->post('date'),
                                "vestigeId" => $result,
                                "voucherNumber" => $this->input->post('voucherNumber'),
                                "productName" => $product['productName'],
                                "productId" => $product['productId'],
                                "price" => $product['productPrice'],
                                "quantity" => $product['quantity'],
                                "total" => $product['total'],
                                "userId" => $this->session->userdata('user_id'),
                                "compId" => $this->input->post('compId'),
                                "warehouseId" => get_title('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->input->post('compId')], 'warehouse'),
                                "created" => date('d-m-Y h:i:s A'),
                                "createdBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('vestige_products', $vestigeItems);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }
                }
            }   
        }
        
        public function reports() 
        {
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            $compId = $this->session->userdata('comp_id');
            $userId = $this->session->userdata('user_id');
            
            $where = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
            
            $data['vestiges'] = $this->db->select('id, date, voucherNumber, compId, subTotal')->where($where)->order_by('id', 'DESC')->get('vestiges')->result();
            $data['page'] = 'vestige/index';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function info($vestigeId)
        {
            if (isset($vestigeId) && !empty($vestigeId)) {
                $compId = $this->session->userdata('comp_id');
                $userId = $this->session->userdata('user_id');

                $where = ['id' => $vestigeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                $itemsWhere = ['vestigeId' => $vestigeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                
                $vestigeQuery = $this->db->where($where)->get('vestiges');
                if ($vestigeQuery->num_rows()) {
                    $data['vestigeData'] = $vestigeQuery->row();
                    $data['vestigeProducts'] = $this->db->where($itemsWhere)->order_by('id', 'ASC')->get('vestige_products')->result();
                    return $this->load->view('inventory_sep/vestige/info', $data);
                } else {
                    return redirect(base_url() . "vestige/reports");
                }
            } else {
                return redirect(base_url() . "vestige/reports");
            }
        }
        
        public function print($vestigeId)
        {
            if (isset($vestigeId) && !empty($vestigeId)) {
                $compId = $this->session->userdata('comp_id');
                $userId = $this->session->userdata('user_id');

                $companyWhere = ['id' => $compId, 'user_id' => $userId];
                $where = ['id' => $vestigeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                $itemsWhere = ['vestigeId' => $vestigeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                
                $vestigeQuery = $this->db->where($where)->get('vestiges');
                if ($vestigeQuery->num_rows()) {
                    $data['companyData'] = $this->db->select('company_name, address, state, number, email')->where($companyWhere)->get('inventory_billing')->row();
                    $data['vestigeData'] = $vestigeQuery->row();
                    $data['vestigeProducts'] = $this->db->where($itemsWhere)->order_by('id', 'ASC')->get('vestige_products')->result();
                    return $this->load->view('inventory_sep/vestige/print', $data);
                } else {
                    return redirect(base_url() . "vestige/reports");
                }
            } else {
                return redirect(base_url() . "vestige/reports");
            }
        }
        
        public function getVestigeProducts($vestigeId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($vestigeId)) {
                    $itemsWhere = ['vestigeId' => $vestigeId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                    $vestigeItems = $this->db->select('id, productName, productId, price, quantity, total')->where($itemsWhere)->order_by('id', 'ASC')->get('vestige_products')->result();
                    if ($vestigeItems) {
                        echo json_encode(array('status' => true, "data" => $vestigeItems));
                    } else {
                        echo json_encode(array('status' => false, "data" => ""));
                    }
                } else {
                    echo json_encode(array('status' => false, "data" => ""));
                }
            }
        }
        
        public function getVestigeEdit() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $vestigeId = $this->input->post('vestigeId');
                if ($vestigeId) {
                    $where = ['id' => $vestigeId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];

                    $vestigeQuery = $this->db->select('id')->where($where)->get('vestiges');
                    if ($vestigeQuery->num_rows()) {
                        $vestigeId = $vestigeQuery->row('id');
                        echo json_encode(['status' => true, 'vestigeId' => $vestigeId]);
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'No Record found.']);
                    }
                } else {
                    echo json_encode(['status' => false, 'msg' => 'Id is missing.']);
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Direct entry not allowed.']);
            }    
        }
        
        public function edit($vestigeId) 
        {
            if (isset($vestigeId) && !empty($vestigeId)) {
                $compId = $this->session->userdata('comp_id');
                $userId = $this->session->userdata('user_id');

                $where = ['id' => $vestigeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                $companyWhere = ['id' => $compId, 'user_id' => $userId];                
                
                $vestigeQuery = $this->db->where($where)->get('vestiges');
                if ($vestigeQuery->num_rows()) {
                    $data['vestigeData'] = $vestigeQuery->row();
                    $data['companies'] = $this->db->select('id, company_name')->where($companyWhere)->order_by('id', 'DESC')->get('inventory_billing')->result();
                    $data['vestigeId'] = $vestigeId;
                    $data['page'] = 'vestige/edit';
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    return redirect(base_url() . "vestige/reports");
                }
            } else {
                return redirect(base_url() . "vestige/reports");
            }
        }
                
        public function update() 
        {
            if ($_POST) {
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('voucherNumber', 'Voucher Number', 'trim|required');
                $this->form_validation->set_rules('compId', 'Company', 'trim|required');
                $this->form_validation->set_rules('subTotal', 'Sub Total', 'trim|required|numeric');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $vestigeData = [
                        'date' => $this->input->post('date'),
                        'compId' => $this->input->post('compId'),
                        'subTotal' => $this->input->post('subTotal'),
                        'remark' => $this->input->post('remark'),
                        'updated' => date('d-m-Y h:i:s A'),
                        'updatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $vestigeId = $this->input->post('vestigeId');
                    $where = ['id' => $vestigeId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                    $result = $this->user_model->updateAry('vestiges', $vestigeData, $where);
                    
                    $itemsWhere = ['vestigeId' => $vestigeId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                    $previewsVestigeItems = $this->db->select('id, productId')->where($itemsWhere)->get('vestige_products')->result();
                    foreach ($previewsVestigeItems as $key => $items) {
                        $this->db->where('id', $items->id)->delete('vestige_products');
                    }

                    foreach ($this->input->post('products') as $key => $product) {
                        if (!empty($product['quantity'])) {
                            $vestigeDataItems = [
                                "date" => $this->input->post('date'),
                                "vestigeId" => $vestigeId,
                                'voucherNumber' => $this->input->post('voucherNumber'),
                                "productName" => $product['productName'],
                                "productId" => $product['productId'],
                                "Price" => $product['productPrice'],
                                "quantity" => $product['quantity'],
                                "total" => $product['total'],
                                "userId" => $this->session->userdata('user_id'),
                                "compId" => $this->session->userdata('comp_id'),
                                'warehouseId' => get_title('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->input->post('compId')], 'warehouse'),
                                "created" => date('d-m-Y h:i:s A'),
                                "createdBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                            ];
                            $this->user_model->saveAry('vestige_products', $vestigeDataItems);
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
        
        public function delete($vestigeId) 
        {
            if (isset($vestigeId) && !empty($vestigeId)) {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                
                $where = ['id' => $vestigeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                $whereItem = ['vestigeId' => $vestigeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];

                if ($this->db->select('Id')->where($where)->get('vestiges')->num_rows()) {
                    if ($this->user_model->updateAry('vestiges', ['isDeleted' => 0], $where)) {
                        $this->user_model->updateAry('vestige_products', ['isDeleted' => 0], $whereItem);
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
                echo json_encode(['status' => false, 'msg' => 'Vestige id is missing.']);
                return;
            }
        }
    }