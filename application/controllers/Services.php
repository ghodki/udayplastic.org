<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Services extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            
            if (empty($this->session->userdata('user_id'))) {
                return redirect(base_url() . 'index');
            }            
        }
        
        public function training()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['trainings'] = $this->db->select('id, categoryId, GROUP_CONCAT(trainingName) AS trainingName, created, createdBy, updated, updatedBy')->where($where)->order_by('id', 'DESC')->group_by('categoryId')->get('training')->result();
            $data['categories'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('category')->result();
            $data['page'] = "services/training/index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function trainingSave()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('categoryId', 'Category Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $trainingName = $this->input->post('trainingName');
                        $userId = $this->session->userdata('user_id');
                        for ($i = 0; $i < count($trainingName); $i++) {
                            if (!empty($trainingName[$i])) {
                                $trainingData = [
                                    'categoryId' => $this->input->post('categoryId'),
                                    'trainingName' => $trainingName[$i],
                                    'userId' => $userId,
                                    'compId' => $this->session->userdata('comp_id'),
                                    'compBranchId' => $this->session->userdata('CompBranchId'),
                                    'created' => date('d-m-Y h:i:s A'),
                                    'createdBy' => get_title('users', ['id' => $userId], 'name'),
                                ];
                            }
                            $Result = $this->user_model->saveAry('training', $trainingData);
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
        }
        
        public function trainingEdit($categoryId)
        {
            if (isset($categoryId) && !empty($categoryId)) {
                $where = ['categoryId' => $categoryId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $categoryWhere = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                    $categoryWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $trainingQuery = $this->db->select('id, categoryId, GROUP_CONCAT(trainingName) AS trainingName')->where($where)->get('training');
                if ($trainingQuery->num_rows()) {
                    $data['trainingData'] = $trainingQuery->result();
                    $data['categories'] = $this->db->select('id, name')->where($categoryWhere)->order_by('id', 'DESC')->get('category')->result();
                    return $this->load->view('inventory_sep/services/training/edit', $data);                    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/training');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/training');
            }
        }
        
        public function trainingUpdate()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('categoryId', 'Category Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $categoryId = $this->input->post('categoryId');
                        $trainingName = $this->input->post('trainingName');
                        $this->user_model->deleteRow('training', ['categoryId' => $categoryId]);
                        
                        $userId = $this->session->userdata('user_id');
                        for ($i = 0; $i < count($trainingName); $i++) {
                            if (!empty($trainingName[$i])) {
                                $trainingData = [
                                    'categoryId' => $categoryId,
                                    'trainingName' => $trainingName[$i],
                                    'userId' => $userId,
                                    'compId' => $this->session->userdata('comp_id'),
                                    'compBranchId' => $this->session->userdata('CompBranchId'),
                                    'created' => date('d-m-Y h:i:s A'),
                                    'createdBy' => get_title('users', ['id' => $userId], 'name'),
                                ];
                                $Result = $this->user_model->saveAry('training', $trainingData);
                            }                            
                        }
                        
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
        
        public function trainingDelete($categoryId)
        {
            if (isset($categoryId) && !empty($categoryId)) {
                $where = ['categoryId' => $categoryId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $trainingQuery = $this->db->select('id')->where($where)->get('training');
                if ($trainingQuery->num_rows()) {
                    if ($this->user_model->updateAry('training', ['isDeleted' => 0], $where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'services/training');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'services/training');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/training');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/training');
            }
        }
        
        // installation
        public function installation()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $fromDate = $this->input->get('fromDate');
                $toDate = $this->input->get('toDate');
            } else {
                $fromDate = date('Y-m-01');
                $toDate = date('Y-m-d');
            }

            $data['installations'] = $this->db->select('id, billNumber, machineSerialNo, manualReportNo, customerId, docs, created')->where(['date >= ' => $fromDate, 'date <= ' => $toDate])->where($where)->order_by('id', 'DESC')->get('installation')->result();
            $data['page'] = "services/installation/index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function installationCreate()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            $otherWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
                $otherWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }

            $data['billNumber'] = 'I' . date('y') . date('m') . rand(1000, 9999);
            $data['categories'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('category')->result();
            $data['brands'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('brand')->result();
            $data['parties'] = $this->db->select('id, name')->where($otherWhere)->order_by('id', 'DESC')->get('parties')->result();
            $data['products'] = $this->db->select('id, p_name')->where($otherWhere)->order_by('id', 'DESC')->get('product')->result();
            $data['page'] = "services/installation/create";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getTrainingByCategory()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $categoryId = $this->input->post('categoryId');
                    if (isset($categoryId) && !empty($categoryId)) {
                        $trainingQuery = $this->db->select('trainingName')->where('categoryId', $categoryId)->order_by('id', 'DESC')->get('training');
                        if ($trainingQuery->num_rows()) {
                            foreach ($trainingQuery->result() as $key => $training) {
                                echo $output = '<label style="margin: 0px 20px 0px 3px;">'
                                            . '<input type="checkbox" name="trainingName[]" value="' . $training->trainingName . '" id="trainingName' . $key . '" /> '
                                            . ucfirst($training->trainingName)
                                            . '</label>';
                            }
                        } else {
                            echo "";
                        }
                    } else {
                        echo "";
                    }
                }
            }
        }
        
        public function getTrainingByCategoryAndInstallationId()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $categoryId = $this->input->post('categoryId');
                    $installationId = $this->input->post('installationId');
                    if (isset($categoryId) && !empty($categoryId) && isset($installationId) && !empty($installationId)) {
                        $trainingQuery = $this->db->select('trainingName')->where(['categoryId' => $categoryId, 'id' => $installationId])->get('installation');
                        if ($trainingQuery->num_rows()) {
                            $trainingNameData = explode(',', $trainingQuery->row()->trainingName);
                            foreach ($trainingNameData as $key => $training) {
                                echo $output = '<label style="margin: 0px 20px 0px 3px;">'
                                            . '<input type="checkbox" name="trainingName[]" value="' . $training . '" id="trainingName' . $key . '" checked="" /> '
                                            . ucfirst($training)
                                            . '</label>';
                            }
                        } else {
                            echo "";
                        }
                    } else {
                        echo "";
                    }
                }
            }
        }
        
        public function getCustomerInfoById()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $customerId = $this->input->post('customerId');
                    if (isset($customerId) && !empty($customerId)) {
                        $customerQuery = $this->db->select('name, email, contact, address_1')->where('id', $customerId)->get('parties');
                        if ($customerQuery->num_rows()) {
                            echo json_encode($customerQuery->row());
                        } else {
                            echo json_encode("");
                        }
                    } else {
                        echo json_encode("");
                    }
                }
            }
        }
        
        public function getProductInfoById()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $productId = $this->input->post('productId');
                    if (isset($productId) && !empty($productId)) {
                        $productQuery = $this->db->select('p_name, price, qrCode, long_de')->where('id', $productId)->get('product');
                        if ($productQuery->num_rows()) {
                            echo json_encode($productQuery->row());
                        } else {
                            echo json_encode("");
                        }
                    } else {
                        echo json_encode("");
                    }
                }
            }
        }
        
        public function installationSave()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('date', 'Date', 'trim|required');
                    $this->form_validation->set_rules('billNumber', 'Bill Number', 'trim|required');
                    $this->form_validation->set_rules('customerId', 'Customer Name', 'trim|required');
                    $this->form_validation->set_rules('customerContactNo', 'Customer Contact Number', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        $createdBy = get_title('users', ['id' => $userId], 'name');
                        
                        if ($_FILES['docs']['name']) {
                            $docsName = time() . $_FILES['docs']['name'];
                            move_uploaded_file($_FILES['docs']['tmp_name'], 'uploads/serviceDocs/' . $docsName);
                            $docsFileName = $docsName;
                        } else {
                            $docsFileName = "";
                        }                        
                        
                        $installationData = [
                            'date' => $this->input->post('date'),
                            'billNumber' => $this->input->post('billNumber'),
                            'manualReportNo' => $this->input->post('manualReportNo'),
                            'warrantyDuration' => $this->input->post('warrantyDuration'),
                            'installationDate' => $this->input->post('installationDate'),
                            'categoryId' => $this->input->post('categoryId'),
                            'brandId' => $this->input->post('brandId'),
                            'productModelName' => $this->input->post('productModelName'),
                            'machineSerialNo' => $this->input->post('machineSerialNo'),
                            'engineerCodeName' => $this->input->post('engineerCodeName'),
                            'meterReading' => $this->input->post('meterReading'),
                            'installationLocation' => $this->input->post('installationLocation'),
                            'consignName' => $this->input->post('consignName'),
                            'designation' => $this->input->post('designation'),
                            'installationAddress' => $this->input->post('installationAddress'),
                            'contactPerson' => $this->input->post('contactPerson'),
                            'contactNumber' => $this->input->post('contactNumber'),
                            'keyOperator' => $this->input->post('keyOperator'),
                            'customerId' => $this->input->post('customerId'),
                            'customerName' => $this->input->post('customerName'),
                            'customerContactNo' => $this->input->post('customerContactNo'),
                            'customerAddress' => $this->input->post('customerAddress'),
                            'customerEmail' => $this->input->post('customerEmail'),
                            'customerKeyOperator' => $this->input->post('customerKeyOperator'),
                            'customerContactPerson' => $this->input->post('customerContactPerson'),
                            'type' => $this->input->post('type'),
                            'installCustomerName' => $this->input->post('installCustomerName'),
                            'installContactNo' => $this->input->post('installContactNo'),
                            'placeOfInstallation' => $this->input->post('placeOfInstallation'),
                            'installEmail' => $this->input->post('installEmail'),
                            'telephoneNoOne' => $this->input->post('telephoneNoOne'),
                            'telephoneNoTwo' => $this->input->post('telephoneNoTwo'),
                            'faxMobileNo' => $this->input->post('faxMobileNo'),
                            'businessStartTime' => $this->input->post('businessStartTime'),
                            'businessEndTime' => $this->input->post('businessEndTime'),
                            'weeklyOff' => $this->input->post('weeklyOff'),
                            'trainingName' => implode(',', $this->input->post('trainingName')),
                            'docs' => $docsFileName,
                            'userId' => $userId,
                            'compId' => $compId,
                            'compBranchId' => $compBranchId,
                            'created' => date('d-m-Y h:i:s A'),
                            'createdBy' => $createdBy,
                        ];
                    }
                    $Result = $this->user_model->saveAry('installation', $installationData);

                    for ($i = 0; $i < count($this->input->post('productId')); $i++) {
                        if (!empty($this->input->post('productId')[$i])) {
                            $installationProductsData = [
                                'installationId' => $Result,
                                'billNumber' => $this->input->post('billNumber'),
                                'installationDate' => $this->input->post('date'),
                                'customerId' => $this->input->post('customerId'),
                                'productId' => $this->input->post('productId')[$i],
                                'productName' => $this->input->post('productName')[$i],
                                'productCode' => $this->input->post('productCode')[$i],
                                'productDescription' => $this->input->post('description')[$i],
                                'serialNumber' => $this->input->post('serialNo')[$i],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'created' => date('d-m-Y h:i:s A'),
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('installation_products', $installationProductsData);
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
        
        public function installationEdit($installationId)
        {
            if (isset($installationId) && !empty($installationId)) {
                $installationWhere = ['id' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $installationProductWhere = ['installationId' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $otherWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $installationWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $installationProductWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                    $otherWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }

                $installationQuery = $this->db->where($installationWhere)->get('installation');
                if ($installationQuery->num_rows()) {
                    $data['installationData'] = $installationQuery->row();
                    $data['categories'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('category')->result();
                    $data['brands'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('brand')->result();
                    $data['parties'] = $this->db->select('id, name')->where($otherWhere)->order_by('id', 'DESC')->get('parties')->result();
                    $data['installationProducts'] = $this->db->select('productId, productName, productCode, productDescription, serialNumber')->where($installationProductWhere)->get('installation_products')->result();
                    $data['products'] = $this->db->select('id, p_name')->where($otherWhere)->order_by('id', 'DESC')->get('product')->result();
                    $data['page'] = "services/installation/edit";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/installation');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/installation');
            }
        }
        
        public function installationUpdate()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('date', 'Date', 'trim|required');
                    $this->form_validation->set_rules('billNumber', 'Bill Number', 'trim|required');
                    $this->form_validation->set_rules('customerId', 'Customer Name', 'trim|required');
                    $this->form_validation->set_rules('customerContactNo', 'Customer Contact Number', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        $createdBy = get_title('users', ['id' => $userId], 'name');
                        
                        if ($_FILES['docs']['name']) {
                            unlink('uploads/serviceDocs/' . $this->input->post('oldDocs'));
                            $docsName = time() . $_FILES['docs']['name'];
                            move_uploaded_file($_FILES['docs']['tmp_name'], 'uploads/serviceDocs/' . $docsName);
                            $docsFileName = $docsName;
                        } else {
                            $docsFileName = $this->input->post('oldDocs');
                        }                        
                        
                        $installationData = [
                            'manualReportNo' => $this->input->post('manualReportNo'),
                            'warrantyDuration' => $this->input->post('warrantyDuration'),
                            'installationDate' => $this->input->post('installationDate'),
                            'categoryId' => $this->input->post('categoryId'),
                            'brandId' => $this->input->post('brandId'),
                            'productModelName' => $this->input->post('productModelName'),
                            'machineSerialNo' => $this->input->post('machineSerialNo'),
                            'engineerCodeName' => $this->input->post('engineerCodeName'),
                            'meterReading' => $this->input->post('meterReading'),
                            'installationLocation' => $this->input->post('installationLocation'),
                            'consignName' => $this->input->post('consignName'),
                            'designation' => $this->input->post('designation'),
                            'installationAddress' => $this->input->post('installationAddress'),
                            'contactPerson' => $this->input->post('contactPerson'),
                            'contactNumber' => $this->input->post('contactNumber'),
                            'keyOperator' => $this->input->post('keyOperator'),
                            'customerId' => $this->input->post('customerId'),
                            'customerName' => $this->input->post('customerName'),
                            'customerContactNo' => $this->input->post('customerContactNo'),
                            'customerAddress' => $this->input->post('customerAddress'),
                            'customerEmail' => $this->input->post('customerEmail'),
                            'customerKeyOperator' => $this->input->post('customerKeyOperator'),
                            'customerContactPerson' => $this->input->post('customerContactPerson'),
                            'type' => $this->input->post('type'),
                            'installCustomerName' => $this->input->post('installCustomerName'),
                            'installContactNo' => $this->input->post('installContactNo'),
                            'placeOfInstallation' => $this->input->post('placeOfInstallation'),
                            'installEmail' => $this->input->post('installEmail'),
                            'telephoneNoOne' => $this->input->post('telephoneNoOne'),
                            'telephoneNoTwo' => $this->input->post('telephoneNoTwo'),
                            'faxMobileNo' => $this->input->post('faxMobileNo'),
                            'businessStartTime' => $this->input->post('businessStartTime'),
                            'businessEndTime' => $this->input->post('businessEndTime'),
                            'weeklyOff' => $this->input->post('weeklyOff'),
                            'trainingName' => implode(',', $this->input->post('trainingName')),
                            'docs' => $docsFileName,
                            'updated' => date('d-m-Y h:i:s A'),
                            'updatedBy' => $createdBy,
                        ];
                    }
                    $Result = $this->user_model->updateAry('installation', $installationData, ['id' => $this->input->post('installationId')]);
                    
                    $this->db->where('installationId', $this->input->post('installationId'))->delete('installation_products');
                    for ($i = 0; $i < count($this->input->post('productId')); $i++) {
                        if (!empty($this->input->post('productId')[$i])) {
                            $installationProductsData = [
                                'installationId' => $this->input->post('installationId'),
                                'billNumber' => $this->input->post('billNumber'),
                                'installationDate' => $this->input->post('date'),
                                'customerId' => $this->input->post('customerId'),
                                'productId' => $this->input->post('productId')[$i],
                                'productName' => $this->input->post('productName')[$i],
                                'productCode' => $this->input->post('productCode')[$i],
                                'productDescription' => $this->input->post('description')[$i],
                                'serialNumber' => $this->input->post('serialNo')[$i],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'created' => date('d-m-Y h:i:s A'),
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('installation_products', $installationProductsData);
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
        
        public function installationInfo($installationId)
        {
            if (isset($installationId) && !empty($installationId)) {
                $installationWhere = ['id' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $installationProductWhere = ['installationId' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                
                if ($this->session->userdata('BranchRole')) {
                    $installationWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $installationProductWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                }

                $installationQuery = $this->db->where($installationWhere)->get('installation');
                if ($installationQuery->num_rows()) {
                    $data['installationData'] = $installationQuery->row();
                    $data['installationProducts'] = $this->db->select('productId, productName, productCode, productDescription, serialNumber')->where($installationProductWhere)->get('installation_products')->result();
                    return $this->load->view('inventory_sep/services/installation/info', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/installation');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/installation');
            }
        }
        
        public function installationPrint($installationId)
        {
            if (isset($installationId) && !empty($installationId)) {
                $installationWhere = ['id' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $installationProductWhere = ['installationId' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $companyWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')];
                $profileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $companyBranchWhere = ['IsDeleted' => 1, 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                
                if ($this->session->userdata('BranchRole')) {
                    $installationWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $installationProductWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $companyBranchWhere['Id'] = $this->session->userdata('CompBranchId');
                    $profileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $data['companyBranchData'] = $this->db->select('BranchName, Mobile, Email, GST, State, Address')->where($companyBranchWhere)->get('companybranch')->row();
                } else {
                    $data['companyData'] = $this->db->select('company_name, number, email, gst_no, state, address')->where($companyWhere)->get('inventory_billing')->row();
                }

                $installationQuery = $this->db->where($installationWhere)->get('installation');
                if ($installationQuery->num_rows()) {
                    $data['installationData'] = $installationQuery->row();
                    $data['profileData'] = $this->db->select('logo')->where($profileWhere)->get('comp_profile')->row();
                    $data['installationProducts'] = $this->db->select('productId, productName, productCode, productDescription, serialNumber')->where($installationProductWhere)->get('installation_products')->result();
                    
                    $this->load->view('inventory_sep/services/installation/print', $data);

                    $this->load->library('pdf');
                    $this->pdf->loadHtml($this->output->get_output());
                    $this->pdf->setPaper('A4', 'portrait');
                    $this->pdf->render();
                    $this->pdf->stream("installationReport.pdf", array("Attachment" => 0));
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/installation');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/installation');
            }
        }
        
        public function installationDelete($installationId)
        {
            if (isset($installationId) && !empty($installationId)) {
                $installationWhere = ['id' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $installationProductWhere = ['installationId' => $installationId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                
                if ($this->session->userdata('BranchRole')) {
                    $installationWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $installationProductWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                }

                if ($this->db->where($installationWhere)->update('installation', ['isDeleted' => 0])) {
                    $this->db->where($installationProductWhere)->update('installation_products', ['isDeleted' => 0]);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'services/installation');                    
                } else {
                    $this->session->set_flashdata(['msg' => 'unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/installation');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/installation');
            }            
        }
        
        // serviceCall
        public function serviceCall()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $fromDate = $this->input->get('fromDate');
                $toDate = $this->input->get('toDate');
            } else {
                $fromDate = date('Y-m-01');
                $toDate = date('Y-m-d');
            }

            $data['serviceCalls'] = $this->db->select('id, billNumber, installationReportNo, manualReportNo, customerId, created, grandTotalAmount, reportBill, voucherNumber')->where(['date >= ' => $fromDate, 'date <= ' => $toDate])->where($where)->order_by('id', 'DESC')->get('service_call')->result();
            $data['totalAmount'] = 0;
            $data['page'] = "services/serviceCall/index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function serviceCallCreate()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            $otherWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
                $otherWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }

            $data['billNumber'] = 'S' . date('y') . date('m') . rand(1000, 9999);
            $data['categories'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('category')->result();
            $data['brands'] = $this->db->select('id, name')->where($where)->order_by('id', 'DESC')->get('brand')->result();
            $data['parties'] = $this->db->select('id, name')->where($otherWhere)->order_by('id', 'DESC')->get('parties')->result();
            $data['products'] = $this->db->select('id, p_name')->where($otherWhere)->order_by('id', 'DESC')->get('product')->result();
            $data['page'] = "services/serviceCall/create";
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function serviceCallSave()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('date', 'Date', 'trim|required');
                    $this->form_validation->set_rules('billNumber', 'Bill Number', 'trim|required');
                    $this->form_validation->set_rules('customerId', 'Customer Name', 'trim|required');
                    $this->form_validation->set_rules('customerContactNo', 'Customer Contact Number', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $compBranchId = $this->session->userdata('CompBranchId');
                        $createdBy = get_title('users', ['id' => $userId], 'name');                        
                        
                        $serviceCallData = [
                            'date' => $this->input->post('date'),
                            'billNumber' => $this->input->post('billNumber'),
                            'installationReportNo' => $this->input->post('installationReportNo'),
                            'manualReportNo' => $this->input->post('manualReportNo'),
                            'billStatus' => $this->input->post('billStatus'),
                            'customerId' => $this->input->post('customerId'),
                            'customerName' => $this->input->post('customerName'),
                            'customerContactNo' => $this->input->post('customerContactNo'),
                            'customerEmail' => $this->input->post('customerEmail'),
                            'customerAddress' => $this->input->post('customerAddress'),
                            'categoryId' => $this->input->post('categoryId'),
                            'brandId' => $this->input->post('brandId'),
                            'productModelName' => $this->input->post('productModelName'),
                            'modelNumber' => $this->input->post('modelNumber'),
                            'machineSerialNo' => $this->input->post('machineSerialNo'),
                            'counterReading' => $this->input->post('counterReading'),
                            'contractStatus' => $this->input->post('contractStatus'),
                            'callType' => $this->input->post('callType'),
                            'installationLocation' => $this->input->post('installationLocation'),
                            'consignName' => $this->input->post('consignName'),
                            'designation' => $this->input->post('designation'),
                            'installationAddress' => $this->input->post('installationAddress'),
                            'contactPerson' => $this->input->post('contactPerson'),
                            'contactNumber' => $this->input->post('contactNumber'),
                            'keyOperator' => $this->input->post('keyOperator'),
                            'problem' => $this->input->post('problem'),
                            'cause' => $this->input->post('cause'),
                            'blackA3' => $this->input->post('blackA3'),
                            'blackA4' => $this->input->post('blackA4'),
                            'colourA3' => $this->input->post('colourA3'),
                            'colourA4' => $this->input->post('colourA4'),
                            'totalReading' => $this->input->post('totalReading'),
                            'actionTaken' => $this->input->post('actionTaken'),
                            'machineStatus' => $this->input->post('machineStatus'),
                            'reportBill' => 'Pending',
                            'grandTotalAmount' => $this->input->post('grandTotalAmount'),
                            'userId' => $userId,
                            'compId' => $compId,
                            'compBranchId' => $compBranchId,
                            'created' => date('d-m-Y h:i:s A'),
                            'createdBy' => $createdBy,
                        ];
                    }
                    $Result = $this->user_model->saveAry('service_call', $serviceCallData);

                    for ($i = 0; $i < count($this->input->post('productId')); $i++) {
                        if (!empty($this->input->post('productId')[$i]) && !empty($this->input->post('quantity')[$i])) {
                            $serviceCallProductsData = [
                                'serviceCallId' => $Result,
                                'billNumber' => $this->input->post('billNumber'),
                                'serviceCallDate' => $this->input->post('date'),
                                'customerId' => $this->input->post('customerId'),
                                'productId' => $this->input->post('productId')[$i],
                                'productName' => $this->input->post('productName')[$i],
                                'partNumber' => $this->input->post('partNumber')[$i],
                                'quantity' => $this->input->post('quantity')[$i],
                                'unitPrice' => $this->input->post('unitPrice')[$i],
                                'amount' => $this->input->post('amount')[$i],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'created' => date('d-m-Y h:i:s A'),
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('service_call_products', $serviceCallProductsData);
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
        
        public function serviceCallInfo($serviceCallId)
        {
            if (isset($serviceCallId) && !empty($serviceCallId)) {
                $serviceCallWhere = ['id' => $serviceCallId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $serviceCallProductWhere = ['serviceCallId' => $serviceCallId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                
                if ($this->session->userdata('BranchRole')) {
                    $serviceCallWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $serviceCallProductWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                }

                $serviceCallQuery = $this->db->where($serviceCallWhere)->get('service_call');
                if ($serviceCallQuery->num_rows()) {
                    $data['serviceCallData'] = $serviceCallQuery->row();
                    $data['serviceCallProducts'] = $this->db->select('productId, productName, partNumber, quantity, unitPrice, amount')->where($serviceCallProductWhere)->get('service_call_products')->result();
                    return $this->load->view('inventory_sep/services/serviceCall/info', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/serviceCall');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/serviceCall');
            }
        }
        
        public function serviceCallToCreateSaleInvoice($serviceCallId)
        {
            if (isset($serviceCallId) && !empty($serviceCallId)) {
                $serviceCallWhere = ['id' => $serviceCallId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                
                if ($this->session->userdata('BranchRole')) {
                    $serviceCallWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                }

                $serviceCallQuery = $this->db->where($serviceCallWhere)->get('service_call');
                if ($serviceCallQuery->num_rows()) {
                    $data['serviceCallId'] = $serviceCallId;
                    $data['page'] = "services/serviceCall/serviceCallToCreateSaleInvoice";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/serviceCall');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/serviceCall');
            }
        }
        
        public function serviceCallPrint($serviceCallId)
        {
            if (isset($serviceCallId) && !empty($serviceCallId)) {
                $serviceCallWhere = ['id' => $serviceCallId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $serviceCallProductWhere = ['serviceCallId' => $serviceCallId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $companyWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'id' => $this->session->userdata('comp_id')];
                $profileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $companyBranchWhere = ['IsDeleted' => 1, 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                
                if ($this->session->userdata('BranchRole')) {
                    $serviceCallWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $serviceCallProductWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $companyBranchWhere['Id'] = $this->session->userdata('CompBranchId');
                    $profileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $data['companyBranchData'] = $this->db->select('BranchName, Mobile, Email, GST, State, Address')->where($companyBranchWhere)->get('companybranch')->row();
                } else {
                    $data['companyData'] = $this->db->select('company_name, number, email, gst_no, state, address')->where($companyWhere)->get('inventory_billing')->row();
                }

                $serviceCallQuery = $this->db->where($serviceCallWhere)->get('service_call');
                if ($serviceCallQuery->num_rows()) {
                    $data['serviceCallData'] = $serviceCallQuery->row();
                    $data['profileData'] = $this->db->select('logo')->where($profileWhere)->get('comp_profile')->row();
                    $data['serviceCallProducts'] = $this->db->select('productId, partNumber, quantity, unitPrice, amount')->where($serviceCallProductWhere)->get('service_call_products')->result();
                    
                    $this->load->view('inventory_sep/services/serviceCall/print', $data);

                    $this->load->library('pdf');
                    $this->pdf->loadHtml($this->output->get_output());
                    $this->pdf->setPaper('A4', 'portrait');
                    $this->pdf->render();
                    $this->pdf->stream("serviceCallReport.pdf", array("Attachment" => 0));
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/serviceCall');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/serviceCall');
            }
        }
        
        public function serviceCallDelete($serviceCallId)
        {
            if (isset($serviceCallId) && !empty($serviceCallId)) {
                $serviceCallWhere = ['id' => $serviceCallId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                $serviceCallProductWhere = ['serviceCallId' => $serviceCallId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                
                if ($this->session->userdata('BranchRole')) {
                    $serviceCallWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                    $serviceCallProductWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                }

                if ($this->db->where($serviceCallWhere)->update('service_call', ['isDeleted' => 0])) {
                    $this->db->where($serviceCallProductWhere)->update('service_call_products', ['isDeleted' => 0]);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'services/serviceCall');                    
                } else {
                    $this->session->set_flashdata(['msg' => 'unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'services/serviceCall');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'services/serviceCall');
            }            
        }
    }