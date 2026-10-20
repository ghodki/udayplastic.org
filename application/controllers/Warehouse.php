<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Warehouse extends CI_Controller
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
            $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $data['warehouses'] = $this->user_model->getResult('warehouse', $Where);
            $data['page'] = "warehouse/index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function create()
        {
            if ($_POST) {
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $this->form_validation->set_rules('location', 'Location', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['warehouses'] = $this->user_model->getResult('warehouse', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                    $data['page'] = "warehouse/index";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {                    
                    $warehouse_data = [
                        'name' => $this->input->post('name'),
                        'location' => $this->input->post('location'),
                        'contact_person' => $this->input->post('contact_person'),
                        'mobile' => $this->input->post('mobile'),
                        'email' => $this->input->post('email'),
                        'remark' => empty($this->input->post('remark')) ? '' : $this->input->post('remark'),
                        'user_id' => $this->session->userdata('user_id'),
                        'comp_id' => $this->session->userdata('comp_id'),
                        'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                        'created' => date('d-m-Y h:i:s A'),
                    ];
                    $result = $this->user_model->saveAry('warehouse', $warehouse_data);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'invent/warehouse');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'invent/warehouse');
                    }
                }
            }
        }
        
        public function edit($id)
        {
            if ($id) {
                $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $EditWhere = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $EditWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $data['id'] = $id;
                $data['warehouses'] = $this->user_model->getResult('warehouse', $Where);
                $data['warehousedata'] = $this->user_model->getRow('warehouse', $EditWhere);
                $data['page'] = "warehouse/index";

                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'invent/warehouse');
            }
        }
        
        public function update($id)
        {            
            if ($id) {
                if ($_POST) {
                    $this->form_validation->set_rules('name', 'Name', 'trim|required');
                    $this->form_validation->set_rules('location', 'Location', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                        $EditWhere = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                            $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                            $EditWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                
                        $data['id'] = $id;
                        $data['warehousedata'] = $this->user_model->getRow('warehouse', $EditWhere);
                        $data['warehouses'] = $this->user_model->getResult('warehouse', $Where);
                        $data['page'] = "warehouse/index";
                        $data['error'] = validation_errors();

                        return $this->load->view('inventory_sep/inventory', $data);
                    } else {
                        $wid = $this->input->post('id');
                        $warehouse_data = [
                            'name' => $this->input->post('name'),
                            'location' => $this->input->post('location'),
                            'contact_person' => $this->input->post('contact_person'),
                            'mobile' => $this->input->post('mobile'),
                            'email' => $this->input->post('email'),
                            'remark' => empty($this->input->post('remark')) ? '' : $this->input->post('remark'),
                            'updated' => date('d-m-Y h:i:s A'),
                        ];
                        $result = $this->user_model->updateAry('warehouse', $warehouse_data, ['id' => $wid]);
                        if (!empty($result)) {
                            $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'invent/warehouse');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'invent/warehouse/edit/' . $id);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'invent/warehouse');
            }
        }
        
        public function changestatus($id)
        {
            if ($id) {
                $Where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $warehouse_record = $this->user_model->getRow('warehouse', $Where);
                if ($warehouse_record) {
                    if ($warehouse_record->status == 1) {
                        $status = 0;
                    } else {
                        $status = 1;
                    }
                    $result = $this->user_model->updateAry('warehouse', ['status' => $status], ['id' => $id]);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'invent/warehouse');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'invent/warehouse');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'invent/warehouse');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'invent/warehouse');
            }
        }
        
        public function delete($id)
        {
            if ($id) {
                $Where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                if ($this->user_model->deleteRow('warehouse', $Where)) {
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'invent/warehouse');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'invent/warehouse');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'invent/warehouse');
            }
        }
        
        public function stock_transfer()
        {
            $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            $WarehousesWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $WarehousesWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            if ($_POST) {
                $transferid = $this->user_model->getDescOrder('stock_transfer', $Where);
                $transfer_id = $transferid->transfer_id + 1;
                
                $stocktransfer = [
                    'transfer_id' => $transfer_id,
                    'transfer_date' => $this->input->post('transfer_date'),
                    'warehouse_form' => $this->input->post('warehouse_form'),
                    'warehouse_to' => $this->input->post('warehouse_to'),
                    'amount_word' => $this->input->post('amount_word'),
                    'total_amount' => $this->input->post('total_amount'),
                    'roundoff' => $this->input->post('roundoff'),
                    'status' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'comp_id' => $this->session->userdata('comp_id'),
                    'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                    'transfer_type' => $this->input->post('transfer_type'),
                    'financial_year' => $this->session->userdata('financial_year'),
                    'created' => date('d-m-Y h:i:s A'),
                    'created_by' => $this->session->userdata('user_id')
                ];
                
                $result = $this->user_model->saveAry('stock_transfer', $stocktransfer);
                if (!empty($result)) {
                    foreach ($this->input->post('product') as $key => $data) {
                        if (!empty($data['quantity'])) {
                            $ProductDataFrom = [
                                'transfer_id' => $transfer_id,
                                'transfer_date' => $this->input->post('transfer_date'),
                                'product_id' => $data['product_id'],
                                'product_code' => $data['product_code'],
                                'product_name' => $data['product_name'],
                                'description' => $data['description'],
                                'unit' => $data['unit'],
                                'hsn' => $data['hsn'],
                                'quantity' => $data['quantity'],
                                'rate' => $data['rate'],
                                'total' => $data['total'],
                                'transfer_type' => $this->input->post('transfer_type'),
                                'warehouse_id' => $this->input->post('warehouse_form'),
                                'transaction_type' => "FROM",
                                'user_id' => $this->session->userdata('user_id'),
                                'comp_id' => $this->session->userdata('comp_id'),
                                'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                                'created' => date('d-m-Y h:i:s A')
                            ];
                            $this->user_model->saveAry('transfer_products', $ProductDataFrom);
                        
                            $ProductDataTo = [
                                'transfer_id' => $transfer_id,
                                'transfer_date' => $this->input->post('transfer_date'),
                                'product_id' => $data['product_id'],
                                'product_code' => $data['product_code'],
                                'product_name' => $data['product_name'],
                                'description' => $data['description'],
                                'unit' => $data['unit'],
                                'hsn' => $data['hsn'],
                                'quantity' => $data['quantity'],
                                'rate' => $data['rate'],
                                'total' => $data['total'],
                                'transfer_type' => $this->input->post('transfer_type'),
                                'warehouse_id' => $this->input->post('warehouse_to'),
                                'transaction_type' => "TO",
                                'user_id' => $this->session->userdata('user_id'),
                                'comp_id' => $this->session->userdata('comp_id'),
                                'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                                'created' => date('d-m-Y h:i:s A')
                            ];
                            $this->user_model->saveAry('transfer_products', $ProductDataTo);
                        }
                    }
                    
                    $this->session->set_flashdata(['msg' => 'Stock transfer successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'warehouse/report');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to transfer stock.', 'class' => 'danger']);
                    return redirect(base_url() . 'warehouse/stock_transfer');
                }
            }
            
            $data['warehouses'] = $this->user_model->getResult('warehouse', $WarehousesWhere);
            $data['transferid'] = $this->user_model->getDescOrder('stock_transfer', $Where);
            $data['page'] = "warehouse/stock-transfer";
            
            return $this->load->view("inventory_sep/inventory", $data);
        }
        
        public function createWarehouse()
        {
            if ($_POST) {
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $this->form_validation->set_rules('location', 'Location', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $data['warehouses'] = $this->user_model->getResult('warehouse', $Where);
                    $data['page'] = "warehouse/stock_transfer";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {                    
                    $warehouse_data = [
                        'name' => $this->input->post('name'),
                        'location' => $this->input->post('location'),
                        'contact_person' => $this->input->post('contact_person'),
                        'mobile' => $this->input->post('mobile'),
                        'email' => $this->input->post('email'),
                        'remark' => empty($this->input->post('remark')) ? '' : $this->input->post('remark'),
                        'user_id' => $this->session->userdata('user_id'),
                        'comp_id' => $this->session->userdata('comp_id'),
                        'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                        'created' => date('d-m-Y h:i:s A'),
                    ];
                    $result = $this->user_model->saveAry('warehouse', $warehouse_data);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'warehouse/stock_transfer');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'warehouse/stock_transfer');
                    }
                }
            }
            
            return $this->load->view("inventory_sep/warehouse/create");
        }
                
        public function editWarehouse($id)
        {
            if (!empty($id)) {
                $Where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $data['warehousedata'] = $this->user_model->getRow('warehouse', $Where);
                return $this->load->view("inventory_sep/warehouse/edit", $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Unable to get record, please try again.', 'class' => 'danger']);
                return redirect(base_url() . 'warehouse/stock_transfer');
            }
        }
        
        public function updateWarehouse()
        {
            if ($this->input->post('id')) {
                if ($_POST) {
                    $this->form_validation->set_rules('name', 'Name', 'trim|required');
                    $this->form_validation->set_rules('location', 'Location', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $id = $this->input->post('id');
                        
                        $Where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                            $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        
                        $data['warehousedata'] = $this->user_model->getRow('warehouse', $Where);
                        $data['page'] = "warehouse/stock_transfer";
                        $data['error'] = validation_errors();

                        return $this->load->view('inventory_sep/inventory', $data);
                    } else {
                        $wid = $this->input->post('id');
                        $warehouse_data = [
                            'name' => $this->input->post('name'),
                            'location' => $this->input->post('location'),
                            'contact_person' => $this->input->post('contact_person'),
                            'mobile' => $this->input->post('mobile'),
                            'email' => $this->input->post('email'),
                            'remark' => empty($this->input->post('remark')) ? '' : $this->input->post('remark'),
                            'updated' => date('d-m-Y h:i:s A'),
                        ];
                        $result = $this->user_model->updateAry('warehouse', $warehouse_data, ['id' => $wid]);
                        if (!empty($result)) {
                            $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'warehouse/stock_transfer');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'warehouse/stock_transfer/' . $id);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'warehouse/stock_transfer');
            }
        }
        
        public function getWarehouse()
        {
            if ($_POST) {
                $id = $this->input->post('warehouse_id');
                $where = ['id !=' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $warehouseto = $this->user_model->getResult('warehouse', $where);
                echo "<option selected='' disabled='' value=''>Select Warehouse To</option>";
                foreach ($warehouseto as $key => $wdatat) {
                    echo "<option value='$wdatat->id'" . set_select('warehouse_to', $wdatat->id) .">" . $wdatat->name . " - " . $wdatat->location . "</option>";
                }
            }
        }
        
        public function getWarehouseFrom()
        {
            if ($_POST) {
                $id = $this->input->post('warehouse_id');
                $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $warehouse = $this->user_model->getResult('warehouse', $where);
                foreach ($warehouse as $key => $wdataf) {
                $selected = $wdataf->id == $id ? "selected" : "";
                    echo "<option $selected value='$wdataf->id'" . set_select('warehouse_from', $wdataf->id) .">" . $wdataf->name . " - " . $wdataf->location . "</option>";
                }
            }
        }
        
        public function getWarehouseTo()
        {
            if ($_POST) {
                $id = $this->input->post('warehouse_id');
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $warehouse = $this->user_model->getRow('warehouse', $where);
                $selected = $warehouse->id == $id ? "selected" : "";
                echo "<option $selected value='$warehouse->id'" . set_select('warehouse_to', $warehouse->id) .">" . $warehouse->name . " - " . $warehouse->location . "</option>";
            }
        }
        
        public function report($start = 0)
        {
            $transferid = '';
            $fromDate = '';
            $toDate = '';
            $limit = 10000;
            
            $first_date = date('Y-m-d'); // hard-coded '01' for first day
            $last_date  = date('Y-m-d');
            
            if ($this->input->get('transfer_id')) {
                $transferid = ['transfer_id' => $this->input->get('transfer_id')];
                $limit = 10000;
            }
            
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['transfer_date >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                $toDate = ['transfer_date <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
                $first_date = date('Y-m-d', strtotime($this->input->get('fromDate')));
                $last_date = date('Y-m-d', strtotime($this->input->get('toDate')));
                $limit = 10000;
            }
                        
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $table = 'stock_transfer';
            $order = ['col' => 'id', 'order' => 'DESC'];

            $count =  $this->user_model->count_all($table, $transferid, '', $fromDate, $toDate, '', '', $where);
            $result =  $this->user_model->getTblerDate($table, $start, $limit, $order, $transferid, '', $fromDate, $toDate, '', '', $where);

            $config['base_url'] = base_url('warehouse/report');
            $config['reuse_query_string'] = true;

            $config['total_rows'] = $count;
            $config['per_page'] = $limit;
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
            $this->pagination->initialize($config);
            $data['fromDate'] = $first_date;
            $data['toDate'] = $last_date;
            $data['result'] = $result;
            $data['start'] = $start;
            $data['end'] = (int)$start + count($result);
            $data['rowCount'] = $count;
            $data['totalCount'] = $this->user_model->RowCount($table);
            
            $data['page'] = "warehouse/report";
            
            return $this->load->view("inventory_sep/inventory", $data);
        }
        
        public function exportexcel()
        {
            $transferid = '';
            $toDate = '';
            $fromDate = '';

            if ($this->input->get('transfer_id')) {
                $transferid = ['transfer_id' => $this->input->get('transfer_id')];
            }
            if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
                $fromDate = ['transfer_date >=' => date('Y-m-d', strtotime($this->input->get('fromDate')))];
                $toDate = ['transfer_date <=' => date('Y-m-d', strtotime($this->input->get('toDate')))];
            }

            $limit = '';
            $table = 'stock_transfer';
            $order = ['col' => 'created', 'order' => 'ASC'];

            $where = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
                
            $count = $this->user_model->count_all($table, $transferid, '', $fromDate, $toDate, '', '', $where);
            $result = $this->user_model->getTblerDate($table, '', $limit, $order, $transferid, '', $fromDate, $toDate, '', '', $where);
            $data['result'] = $result;
            $data['count'] = $count;
            $data['FromDate'] = $fromDate;
            $data['ToDate'] = $toDate;
            print_r($this->load->view('inventory_sep/warehouse/exportexcel', $data, true));
        }
        
        public function edit_stock_transfer($id)
        {
            $data['transfer_id'] = $id;
            $data['page'] = "warehouse/edit-stock-transfer";    
            return $this->load->view("inventory_sep/inventory", $data);
        }

        public function update_stock_transfer ()
        {
            if ($_POST) {
                $stid = $this->input->post('transfer_id');
                $stocktransfer = [
                    'transfer_date' => $this->input->post('transfer_date'),
                    'warehouse_form' => $this->input->post('warehouse_form'),
                    'warehouse_to' => $this->input->post('warehouse_to'),
                    'amount_word' => $this->input->post('amount_word'),
                    'total_amount' => $this->input->post('total_amount'),
                    'roundoff' => $this->input->post('roundoff'),
                    'transfer_type' => $this->input->post('transfer_type'),
                    'updated' => date('d-m-Y h:i:s A'),
                    'updated_by' => $this->session->userdata('user_id')
                ];
                
                $result = $this->user_model->updateAry('stock_transfer', $stocktransfer, ['transfer_id' => $stid]);
                if (!empty($result)) {
                    $this->user_model->deleteRow('transfer_products', ['transfer_id' => $stid]);
                    foreach ($this->input->post('product_update') as $key => $data) {
                        if (!empty($data['quantity'])) {
                            $ProductDataFrom = [
                                'transfer_id' => $stid,
                                'transfer_date' => $this->input->post('transfer_date'),
                                'product_id' => $data['product_id'],
                                'product_code' => $data['product_code'],
                                'product_name' => $data['product_name'],
                                'description' => $data['description'],
                                'unit' => $data['unit'],
                                'hsn' => $data['hsn'],
                                'quantity' => $data['quantity'],
                                'rate' => $data['rate'],
                                'total' => $data['total'],
                                'transfer_type' => $this->input->post('transfer_type'),
                                'warehouse_id' => $this->input->post('warehouse_form'),
                                'transaction_type' => "FROM",
                                'user_id' => $this->session->userdata('user_id'),
                                'comp_id' => $this->session->userdata('comp_id'),
                                'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                                'created' => date('d-m-Y h:i:s A')
                            ];
                            $this->user_model->saveAry('transfer_products', $ProductDataFrom);

                            $ProductDataTo = [
                                'transfer_id' => $stid,
                                'transfer_date' => $this->input->post('transfer_date'),
                                'product_id' => $data['product_id'],
                                'product_code' => $data['product_code'],
                                'product_name' => $data['product_name'],
                                'description' => $data['description'],
                                'unit' => $data['unit'],
                                'hsn' => $data['hsn'],
                                'quantity' => $data['quantity'],
                                'rate' => $data['rate'],
                                'total' => $data['total'],
                                'transfer_type' => $this->input->post('transfer_type'),
                                'warehouse_id' => $this->input->post('warehouse_to'),
                                'transaction_type' => "TO",
                                'user_id' => $this->session->userdata('user_id'),
                                'comp_id' => $this->session->userdata('comp_id'),
                                'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                                'created' => date('d-m-Y h:i:s A')
                            ];
                            $this->user_model->saveAry('transfer_products', $ProductDataTo);
                        }
                    }
                    
                    $this->session->set_flashdata(['msg' => 'Stock transfer updated successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'warehouse/report');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to transfer stock.', 'class' => 'danger']);
                    return redirect(base_url() . 'warehouse/stock_transfer');
                }
            }
        }
        
        public function print($id)
        {
            if (!empty($id)) {
                $where = ['status' => 1, 'id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $data['BranchInfo'] = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id')]);
                } else {
                    $data['compinfo'] = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')]);
                }
                $data['result'] = $this->user_model->getRow('stock_transfer', $where);
                
                
                return $this->load->view('inventory_sep/warehouse/print', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Unable to get print, please try again.', 'class' => 'danger']);
                return redirect(base_url() . 'warehouse/report/' . $id);
            }
        }
        
        public function info($id) 
        {
            $where = ['status' => 1, 'id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $data['BranchInfo'] = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id')]);
            } else {
                $data['compinfo'] = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')]);
            }
            $data['result'] = $this->user_model->getRow('stock_transfer', $where);
            $data['page'] = 'warehouse/info';
            return $this->load->view("inventory_sep/inventory", $data);
        }
        
	public function getStockTransferByTransferId()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                $this->db->select('*');
                $this->db->where('transfer_id', $id);
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('financial_year', $this->session->userdata('financial_year'));
                $this->db->where('status', 1);
                $this->db->order_by("transfer_id", "DESC");
                $result = $this->db->get('stock_transfer')->row();
                
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not not Data"]);
                }
            }
	}
        
	public function TransferId()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                $Where = ['status' => 1, 'transfer_id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                
                $transferdata = $this->user_model->getRow('stock_transfer', $Where);
                if (isset($transferdata) && !empty($transferdata)) {
                    $TransferWhere = ['transfer_id' => $transferdata->transfer_id, 'warehouse_id' => $transferdata->warehouse_form, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                
                    $transfer_product = $this->user_model->getresult('transfer_products', $TransferWhere);
                    $data1['productdata'] = $transfer_product;
                    $data1['transferdata'] = $transferdata;
                    echo json_encode(['status' => true, 'stdata' => $data1]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not not Data"]);
                }
            }
	}
        
	public function getTransferIdByStockTransferPrevious()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {               
                $this->db->select('*');
                $this->db->where('transfer_id <', $id);
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('financial_year', $this->session->userdata('financial_year'));
                $this->db->where('status', 1);
                $this->db->order_by('transfer_id', 'DESC');
                $result = $this->db->get('stock_transfer')->row();

                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not not Data"]);
                }
            }
	}
        
	public function getTransferIdByStockTransferNext()
	{
            $id = $this->input->post('id');
            if ($id == null || $id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {               
                $this->db->select('*');
                $this->db->where('transfer_id >', $id);
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('financial_year', $this->session->userdata('financial_year'));
                $this->db->where('status', 1);
                $this->db->order_by('transfer_id', 'DESC');
                $result = $this->db->get('stock_transfer')->row();

                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not not Data"]);
                }
            }
	}
        
        public function CheckTransferId ()
        {
            $transfer_id = $this->input->post('transfer_id');
            if ($transfer_id == null || $transfer_id == '') {
                echo json_encode(['status' => false, 'data' => "Not not Data"]);
            } else {
                $this->db->select('transfer_id');
                $this->db->where('transfer_id', $transfer_id);
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('financial_year', $this->session->userdata('financial_year'));
                $this->db->where('status', 1);
                $this->db->order_by('transfer_id', 'DESC');
                $result = $this->db->get('stock_transfer')->row();
                if ($result) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not not Data"]);
                }
            }
        }
        
        public function deleteStockTransfer() 
        {
            if ($_POST) {
                $transfer_id = $this->input->post('transferid');
                if ($transfer_id == null || $transfer_id == '') {
                    echo json_encode(['status' => false, 'data' => "Not not Data"]);
                } else {
                    $Where = ['transfer_id' => $transfer_id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    $ProductWhere = ['transfer_id' => $transfer_id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        $ProductWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    if ($this->user_model->deleteRow('stock_transfer', $Where)) {
                        $this->user_model->deleteRow('transfer_products', $ProductWhere);
                        echo json_encode(['status' => true, 'data' => "Record delete successfully."]);
                    } else {
                        echo json_encode(['status' => false, 'data' => "Unable to delete record."]);
                    }   
                }
            }
        }
        
        public function delete_stock_transfer($id)
        {
            if ($id) {
                $Where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];                
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $transfer_id = $this->user_model->getRow('stock_transfer', $Where);
                if ($this->user_model->deleteRow('stock_transfer', $Where)) {
                    $ProductWhere = ['transfer_id' => $transfer_id->transfer_id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $ProductWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $this->user_model->deleteRow('transfer_products', $ProductWhere);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    echo json_encode(['status' => true]);
                    return redirect(base_url() . 'warehouse/report');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'warehouse/report');
                } 
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'warehouse/report');
            }
        }
        
        public function trasnferProductDeleteRow()
        {
            if ($_POST) {
                $id = $this->input->post('id');
                if ($id == null || $id == '') {
                    echo json_encode(['status' => false, 'data' => "Not not Data"]);
                } else {
                    $Where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    if ($this->user_model->deleteRow('transfer_products', $Where)) {
                        echo json_encode(['status' => true, 'data' => "Record delete successfully."]);
                    } else {
                        echo json_encode(['status' => false, 'data' => "Unable to delete record."]);
                    }   
                }
            }
        }
        
        public function edit_product_for_warehouse ($id)
        {
            if (isset($id) && !empty($id)) {
                $where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->getRow('product', $where);
                if ($return) {
                    $data['result'] = $return;
                    $this->load->view('inventory_sep/warehouse/edit-product', $data);
                }
            }
        }
        
        public function getAvailableQuantityFromProduct ()
        {
            if ($_POST) {
                $id = $this->input->post('product_id');
                if ($id == null || $id == '') {
                    echo json_encode(['status' => false, 'data' => 'Not not Data']);
                } else {
                    $whare = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $data = get_title('product', $whare, 'qty');
                    echo json_encode(['status' => true, 'data' => $data]);
                }
            }
        }
        
        public function checkQuantityFromProduct ()
        {
            if ($_POST) {
                $product_id = $this->input->post('product_id');
                $warehouse_id = $this->input->post('warehouse_id');
                $quantity = $this->input->post('quantity');
                if ($product_id == null || $product_id == '' && $warehouse_id == null || $warehouse_id == '' && $quantity == null || $quantity == '') {
                    echo json_encode(['status' => false, 'data' => 'Enter required fields']);
                } else {
                    $whare = ['id' => $product_id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'warehouse' => $warehouse_id];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    
                    $product = get_title('product', $whare, 'qty');
                    if ($product) {
                        if ($quantity <= $product) {
                            echo json_encode(['status' => true, 'data' => '']);
                        } else {
                            echo json_encode(['status' => false, 'data' => 'Please enter less quantity for transfer']);
                        }
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Product not found']);
                    }
                }
            }
        }
        
        public function getWarehouseName()
        {
            if ($_POST) {
                $WarehouseId = $this->input->post('WarehouseId');
                if ($WarehouseId == '' || $WarehouseId == null) {
                    echo json_encode(['status' => false, 'data' => 'Enter required fields']);
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'warehouse' => $WarehouseId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $warehouse = get_title('comp_profile', $Where, 'warehouse');
                    $name = get_title('warehouse', ['id' => $warehouse], 'name');
                    $location = get_title('warehouse', ['id' => $warehouse], 'location');
                    $data = $name . " - " . $location;
                    if ($data) {
                        echo json_encode(['status' => true, 'data' => $data]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Warehouse not found']);
                    }
                }
            }
        }
        
        public function getWarehouseNameForEstimate()
        {
            if ($_POST) {
                $WarehouseId = $this->input->post('WarehouseId');
                if ($WarehouseId == '' || $WarehouseId == null) {
                    echo json_encode(['status' => false, 'data' => 'Enter required fields']);
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'warehouse_estimate' => $WarehouseId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    
                    $warehouse = get_title('comp_profile', $Where, 'warehouse_estimate');
                    $name = get_title('warehouse', ['id' => $warehouse], 'name');
                    $location = get_title('warehouse', ['id' => $warehouse], 'location');
                    $data = $name . " - " . $location;
                    if ($data) {
                        echo json_encode(['status' => true, 'data' => $data]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Warehouse not found']);
                    }
                }
            }
        }
        
        public function getWarehouseNameForPurchase()
        {
            if ($_POST) {
                $WarehouseId = $this->input->post('WarehouseId');
                if ($WarehouseId == '' || $WarehouseId == null) {
                    echo json_encode(['status' => false, 'data' => 'Enter required fields']);
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'warehouse_purchase' => $WarehouseId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $warehouse = get_title('comp_profile', $Where, 'warehouse_purchase');
                    $name = get_title('warehouse', ['id' => $warehouse], 'name');
                    $location = get_title('warehouse', ['id' => $warehouse], 'location');
                    $data = $name . " - " . $location;
                    if ($data) {
                        echo json_encode(['status' => true, 'data' => $data]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Warehouse not found']);
                    }
                }
            }
        }
        
        public function getWarehouseNameForEstimatePurchase()
        {
            if ($_POST) {
                $WarehouseId = $this->input->post('WarehouseId');
                if ($WarehouseId == '' || $WarehouseId == null) {
                    echo json_encode(['status' => false, 'data' => 'Enter required fields']);
                } else {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'warehouse_estimate_purchase' => $WarehouseId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $warehouse = get_title('comp_profile', $Where, 'warehouse_estimate_purchase');
                    $name = get_title('warehouse', ['id' => $warehouse], 'name');
                    $location = get_title('warehouse', ['id' => $warehouse], 'location');
                    $data = $name . " - " . $location;
                    if ($data) {
                        echo json_encode(['status' => true, 'data' => $data]);
                    } else {
                        echo json_encode(['status' => false, 'data' => 'Warehouse not found']);
                    }
                }
            }
        }
    }