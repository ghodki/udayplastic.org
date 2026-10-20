<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');

    require APPPATH . '/libraries/REST_Controller.php';
    
    class Electrician extends REST_Controller
    {
        public function __construct()
        {
            parent::__construct();
            
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, OPTIONS, POST, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
            
            $this->load->model('user_model');
        }
        
        public function index_GET()
        {
            $data['electricians'] = $this->db->where('isDeleted', 1)->order_by('id', 'DESC')->get('electrician')->result();
            $data['page'] = "electrician/index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function create_GET()
        {
            $data['page'] = "electrician/create";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function save_POST()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|max_length[10]|min_length[10]|is_unique[electrician.mobile]');
                $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
                $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['page'] = "electrician/create";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    if ($_FILES['photograph']['name']) {
                        $photographFileName = time() . $_FILES['photograph']['name'];
                        move_uploaded_file($_FILES['photograph']['tmp_name'], 'uploads/electrician/' . $photographFileName);
                        $photograph = $photographFileName;
                    } else {
                        $photograph = "";
                    }
                    
                    $electricianData = [
                        'date' => $this->input->post('date'),
                        'name' => $this->input->post('name'),
                        'mobile' => $this->input->post('mobile'),
                        'email' => $this->input->post('email'),
                        'gender' => $this->input->post('gender'),
                        'upiId' => $this->input->post('upiId'),
                        'password' => $this->input->post('password'),
                        'photograph' => $photograph,
                        'created' => date('d-m-Y h:i:s A'),
                        'createdBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('electrician', $electricianData);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'electrician');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'electrician/create');
                    }                    
                }
            }
        }
        
        public function edit_GET($userId)
        {
            if (!empty($userId)) {
                $electricianQuery = $this->db->where(['id' => $userId, 'isDeleted' => 1])->get('electrician');
                if ($electricianQuery->num_rows()) {
                    $data['electrician'] = $electricianQuery->row();
                    $data['page'] = "electrician/edit";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id is required.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
                
        public function update_POST()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $electricianId = $this->input->post('electricianId');
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|max_length[10]|min_length[10]|callback_isUniqueMobile[' . $electricianId . ']');
                $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
                $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['electrician'] = $this->db->where('id', $electricianId)->get('electrician')->row();
                    $data['page'] = "electrician/edit";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    if ($_FILES['photograph']['name']) {
                        unlink('uploads/electrician/' . $this->input->post('oldPhotograph'));
                        $photographFileName = time() . $_FILES['photograph']['name'];
                        move_uploaded_file($_FILES['photograph']['tmp_name'], 'uploads/electrician/' . $photographFileName);
                        $photograph = $photographFileName;
                    } else {
                        $photograph = $this->input->post('oldPhotograph');
                    }
                    
                    $electricianData = [
                        'date' => $this->input->post('date'),
                        'name' => $this->input->post('name'),
                        'mobile' => $this->input->post('mobile'),
                        'email' => $this->input->post('email'),
                        'gender' => $this->input->post('gender'),
                        'upiId' => $this->input->post('upiId'),
                        'password' => $this->input->post('password'),
                        'photograph' => $photograph,
                        'updated' => date('d-m-Y h:i:s A'),
                        'updatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $Result = $this->user_model->updateAry('electrician', $electricianData, ['id' => $electricianId, 'isDeleted' => 1]);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'electrician');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'electrician/edit/' . $electricianId);
                    }                    
                }
            }
        }

        public function status_GET($userId)
        {
            if ($userId) {
                $where = ['id' => $userId, 'isDeleted' => 1];
                $electricianQuery = $this->db->select('status')->where($where)->get('electrician');
                if ($electricianQuery->num_rows()) {
                    if ($electricianQuery->row()->status == 1) {
                        $status = 0;
                    } else {
                        $status = 1;
                    }
                    $result = $this->user_model->updateAry('electrician', ['status' => $status], $where);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }            
        }
        
        public function info_GET($userId)
        {
            if ($userId) {
                $electricianQuery = $this->db->where(['id' => $userId, 'isDeleted' => 1])->get('electrician');
                if ($electricianQuery->num_rows()) {
                    $data['electrician'] = $electricianQuery->row();
                    return $this->load->view('inventory_sep/electrician/info', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'User id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }            
        }
        
        public function delete_GET($userId)
        {
            if ($userId) {
                $where = ['id' => $userId, 'isDeleted' => 1];
                $electricianQuery = $this->db->select('id')->where($where)->get('electrician');
                if ($electricianQuery->num_rows()) {
                    if ($this->user_model->updateAry('electrician', ['isDeleted' => 0], $where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'User id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function rewardPointCost_GET()
        {
            $data['rewardPointCost'] = $this->db->order_by('id', 'DESC')->get('reward_point_cost')->result();
            $data['page'] = "electrician/rewardPointCost";
            return $this->load->view('inventory_sep/inventory', $data);
        }        
        
        public function rewardPointCostSave_POST()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('rewardPoint', 'Reward Point', 'trim|required|numeric');
                    $this->form_validation->set_rules('equivalentRupee', 'Equivalent Rupee', 'trim|required|numeric');
                    $this->form_validation->set_rules('minimumWithdrawalAmount', 'Minimum Withdrawal Amount', 'trim|required|numeric');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;                        
                    } else {
                        if ($this->db->select('id')->get('reward_point_cost')->num_rows() > 0) {
                            echo json_encode(['Status' => false, 'Message' => 'You create only one record.']);
                            return;                            
                        }

                        if ($_FILES['catalogue']['name']) {
                            $catalogueName = time() . $_FILES['catalogue']['name'];
                            move_uploaded_file($_FILES['catalogue']['tmp_name'], 'uploads/catalogue/' . $catalogueName);
                            $catalogueFileName = $catalogueName;
                        } else {
                            $catalogueName = "";
                        }
                        
                        $rewardPointCostData = [
                            'rewardPoint' => $this->post('rewardPoint'),
                            'equivalentRupee' => $this->post('equivalentRupee'),
                            'minimumWithdrawalAmount' => $this->post('minimumWithdrawalAmount'),
                            'catalogue' => $catalogueFileName,
                            'created' => date('d-m-Y h:i:s A')
                        ];
                        
                        $Result = $this->user_model->saveAry('reward_point_cost', $rewardPointCostData);
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
        
        public function rewardPointCostEdit_GET($id)
        {
            if (isset($id) && !empty($id)) {
                $rewardPointCostQuery = $this->db->where('id', $id)->get('reward_point_cost');
                if ($rewardPointCostQuery->num_rows()) {
                    $data['rewardPointCostData'] = $rewardPointCostQuery->row();
                    return $this->load->view('inventory_sep/electrician/rewardPointCostEdit', $data);                    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'electrician/rewardPointCost');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'electrician/rewardPointCost');
            }
        }   
        
        public function rewardPointCostUpdate_POST()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('rewardPoint', 'Reward Point', 'trim|required|numeric');
                    $this->form_validation->set_rules('equivalentRupee', 'Equivalent Rupee', 'trim|required|numeric');
                    $this->form_validation->set_rules('minimumWithdrawalAmount', 'Minimum Withdrawal Amount', 'trim|required|numeric');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        if ($_FILES['catalogue']['name']) {
                            unlink('uploads/catalogue/' . $this->post('oldCatalogue'));                            
                            $catalogueName = time() . $_FILES['catalogue']['name'];
                            move_uploaded_file($_FILES['catalogue']['tmp_name'], 'uploads/catalogue/' . $catalogueName);
                            $catalogueFileName = $catalogueName;
                        } else {
                            $catalogueFileName = $this->post('oldCatalogue');
                        }
                        
                        $rewardPointCostData = [
                            'rewardPoint' => $this->post('rewardPoint'),
                            'equivalentRupee' => $this->post('equivalentRupee'),
                            'minimumWithdrawalAmount' => $this->post('minimumWithdrawalAmount'),
                            'catalogue' => $catalogueFileName,
                            'updated' => date('d-m-Y h:i:s A')
                        ];
                        $Result = $this->user_model->updateAry('reward_point_cost', $rewardPointCostData, ['id' => $this->post('rewardPointCostId')]);
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
        
        public function rewardPointCostDelete_GET($id)
        {
            if (isset($id) && !empty($id)) {
                $rewardPointCostQuery = $this->db->select('id')->where('id', $id)->get('reward_point_cost');
                if ($rewardPointCostQuery->num_rows()) {
                    if ($this->user_model->deleteRow('reward_point_cost', ['id' => $id])) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function insight_GET($userId)
        {
            if (isset($userId) && !empty($userId)) {
                $data['electricianName'] = get_title('electrician', ['id' => $userId], 'name');
                $walletQuery = $this->db->select('SUM(credit) as Credit, SUM(debit) AS Debit')->where('userId', $userId)->get('electrician_wallet')->row();
                $data['userId'] = $userId;
                $data['totalEarn'] = $this->db->select('SUM(credit) as Credit')->where('userId', $userId)->where('debit', NULL)->where('productId IS NOT NULL', null, false)->get('electrician_wallet')->row('Credit') ?? 0;
                $data['totalBalance'] = ($walletQuery->Credit - $walletQuery->Debit) ?? 0;
                $data['totalWithdrawal'] = $this->db->select('SUM(rewardPoints) as withdrawalPoint')->where('userId', $userId)->where('status', 1)->get('reward_points_withdrawal')->row('withdrawalPoint') ?? 0;
                $data['page'] = "electrician/insight";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'User id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function earningReport_GET($userId)
        {
            if (isset($userId) && !empty($userId)) {
                $data['electricianName'] = get_title('electrician', ['id' => $userId], 'name');
                $data['userId'] = $userId;
                $data['earningData'] = $this->db->select('date, credit, productId')->where(['userId' => $userId, 'debit' => NULL])->where('productId IS NOT NULL', null, false)->order_by('id', 'DESC')->get('electrician_wallet')->result();
                $data['page'] = "electrician/earnReport";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'User id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function balanceReport_GET($userId)
        {
            if (isset($userId) && !empty($userId)) {
                $data['electricianName'] = get_title('electrician', ['id' => $userId], 'name');
                $data['userId'] = $userId;
                $data['balanceData'] = $this->db->select('credit, debit, balance, created')->where('userId', $userId)->order_by('id', 'DESC')->get('electrician_wallet')->result();
                $data['page'] = "electrician/balanceReport";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'User id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function cashoutReport_GET($userId)
        {
            if (isset($userId) && !empty($userId)) {
                $data['electricianName'] = get_title('electrician', ['id' => $userId], 'name');
                $data['userId'] = $userId;
                $data['withdrawalData'] = $this->db->select('date, rewardPoints, status, remark, adminRemark')->where(['userId' => $userId, 'isDeleted' => 1])->order_by('id', 'DESC')->get('reward_points_withdrawal')->result();
                $data['page'] = "electrician/withdrawalReport";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'User id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        //API's        
        public function registration_POST()
        {
            $name = $this->post('name');
            $mobile = $this->post('mobile');
            $email = $this->post('email');
            $gender = $this->post('gender');
            $password = $this->post('password');
            
            if (!empty($name) && !empty($mobile) && !empty($gender) && !empty($password)) {
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('mobile', 'Mobile', 'numeric|max_length[10]|min_length[10]|is_unique[electrician.mobile]', 
                    array(
                        'numeric' => 'The Mobile field must be a numeric value.',
                        'max_length' => 'The Mobile field max length must be 10.',
                        'min_length' => 'The Mobile field min length must be 10.',
                        'is_unique' => 'The Mobile field must contain a unique value.'
                    )
                );
                $this->form_validation->set_rules('email', 'Email', 'valid_email', array('valid_email' => 'The Email field is valid email.'));
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => FALSE,
                        'message' => validation_errors(),
                        'data' => ""
                    ], REST_Controller::HTTP_OK);
                } else {
                    $registrationData = [
                        'date' => date('Y-m-d'),
                        'name' => $name,
                        'mobile' => $mobile,
                        'email' => $email,
                        'gender' => $gender,
                        'password' => $password,
                        'created' => date('d-m-Y h:i:s A')
                    ];

                    $result = $this->db->insert('electrician', $registrationData);
                    if ($result) {
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Registration successfully.',
                            'data' => ""
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Somethigs went wrong, try again.',
                            'data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);                    
                    }                    
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function auth_POST()
        {
            $mobile = $this->post('mobile');
            $password = $this->post('password');
            
            if (!empty($mobile) && !empty($password)) {
                $where = ['mobile' => $mobile, 'password' => $password, 'status' => 1, 'isDeleted' => 1];
                $electricianQuery = $this->db->select('id')->where($where)->get('electrician');
                if ($electricianQuery->num_rows()) {
                    $this->db->where('id', $electricianQuery->row()->id)->update('electrician', ['isLoggedIn' => bin2hex(openssl_random_pseudo_bytes(16))]);
                    
                    $electricianData = $electricianQuery->row_array();
                    $electricianData['isLoggedIn'] = get_title('electrician', ['id' => $electricianQuery->row()->id], 'isLoggedIn');
                    
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Welcome, back',
                        'data' => $electricianData
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No account found.',
                        'data' => 0
                    ], REST_Controller::HTTP_UNAUTHORIZED);                    
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function logOut_POST()
        {
            $userId = $this->post('userId');
            
            if (!empty($userId)) {
                $Where = ['id' => $userId, 'status' => 1, 'isDeleted' => 1];
                $electricianQuery = $this->db->select('id')->where($Where)->get('electrician');
                if ($electricianQuery->num_rows()) {
                    $logOutResult = $this->db->where('id', $electricianQuery->row()->id)->update('electrician', ['isLoggedIn' => NULL]);
                    if ($logOutResult) {
                        $this->response([
                            'Status' => TRUE,
                            'Message' => 'Logged Out Successfully',
                            'Data' => ''
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Enter required fields.',
                            'Data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No account found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_UNAUTHORIZED);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }        
        
        public function profile_GET($userId)
        {
            if (!empty($userId)) {
                $where = ['id' => $userId, 'status' => 1, 'isDeleted' => 1];
                $electricianQuery = $this->db->select('id, name, mobile, email, gender, photograph, upiId, isLoggedIn')->where($where)->get('electrician');
                if ($electricianQuery->num_rows()) {
                    $electricianData = $electricianQuery->row_array();
                    $electricianData['photograph'] = base_url() . "uploads/electrician/" . $electricianQuery->row()->photograph;
                    
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Electrician Data',
                        'data' => $electricianData
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);                    
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Electrician id missing.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }            
        }
        
        public function isUniqueMobile($mobile, $userId) 
        {
            $where = ['isDeleted' => 1, 'mobile' => $mobile, 'id != ' => $userId];
            if ($this->db->select('id')->where($where)->get('electrician')->num_rows() === 0) {
                return true;
            } else {
                $this->form_validation->set_message('isUniqueMobile', 'The Mobile must be unique.');
                return false;
            }
        }
        
        public function updateProfile_POST()
        {
            $userId = $this->post('userId');
            $name = $this->post('name');
            $mobile = $this->post('mobile');
            $email = $this->post('email');
            $gender = $this->post('gender');
            $upiId = $this->post('upiId');
            
            if (!empty($userId) && !empty($name) && !empty($mobile) && !empty($gender)) {
                $where = ['id' => $userId, 'status' => 1, 'isDeleted' => 1];
                $electricianQuery = $this->db->select('id, photograph')->where($where)->get('electrician');
                if ($electricianQuery->num_rows()) {
                    $this->form_validation->set_error_delimiters('', '');
                    $this->form_validation->set_rules('mobile', 'Mobile', 'numeric|max_length[10]|min_length[10]|callback_isUniqueMobile[' . $userId . ']', 
                        array(
                            'numeric' => 'The Mobile field must be a numeric value.',
                            'max_length' => 'The Mobile field max length must be 10.',
                            'min_length' => 'The Mobile field min length must be 10.',
                            'isUniqueMobile' => 'The Mobile field must contain a unique value.'
                        )
                    );
                    $this->form_validation->set_rules('email', 'Email', 'valid_email', array('valid_email' => 'The Email field is valid email.'));
                    if ($this->form_validation->run() == false) {
                        $this->response([
                            'status' => FALSE,
                            'message' => validation_errors(),
                            'data' => ""
                        ], REST_Controller::HTTP_OK);
                    } else {                    
                        if ($_FILES['photograph']['name']) {
                            unlink('uploads/electrician/' . $electricianQuery->row()->photograph);
                            $photographName = time() . $_FILES['photograph']['name'];
                            move_uploaded_file($_FILES['photograph']['tmp_name'], 'uploads/electrician/' . $photographName);
                            $photographFileName = $photographName;
                        } else {
                            $photographFileName = $electricianQuery->row()->photograph;
                        }

                        $electricianData = [
                            'name' => $name,
                            'mobile' => $mobile,
                            'email' => $email,
                            'gender' => $gender,
                            'photograph' => $photographFileName,
                            'upiId' => $upiId,
                            'updated' => date('d-m-Y h:i:s A'),
                            'updatedBy' => get_title('electrician', ['id' => $userId], 'name')
                        ];

                        $result = $this->db->where($where)->update('electrician', $electricianData);
                        if ($result) {
                            $this->response([
                                'status' => TRUE,
                                'message' => 'Profile update successfully.',
                                'data' => ""
                            ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'status' => FALSE,
                                'message' => 'Somethigs went wrong, try again.',
                                'data' => 0
                            ], REST_Controller::HTTP_BAD_REQUEST);                            
                        }
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No record found.',
                        'data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function changePassword_POST()
        {
            $userId = $this->post('userId');
            $currentPassword = $this->post('currentPassword');
            $newPassword = $this->post('newPassword');
            
            if (!empty($userId) && !empty($currentPassword) && !empty($newPassword)) {
                $where = ['id' => $userId, 'status' => 1, 'isDeleted' => 1];
                $electricianQuery = $this->db->select('id, password')->where($where)->get('electrician');
                if ($electricianQuery->num_rows()) {
                    if ($electricianQuery->row()->password == $currentPassword) {
                        $electricianData = [
                            'password' => $newPassword,
                            'updated' => date('d-m-Y h:i:s A'),
                            'updatedBy' => get_title('electrician', ['id' => $userId], 'name')
                        ];
                        
                        $result = $this->db->where($where)->update('electrician', $electricianData);
                        if ($result) {
                            $this->response([
                                'status' => TRUE,
                                'message' => 'Password change successfully.',
                                'data' => ""
                            ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'status' => FALSE,
                                'message' => 'Somethigs went wrong, try again.',
                                'data' => 0
                            ], REST_Controller::HTTP_BAD_REQUEST);                            
                        }
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Current Password does not match, try again.',
                            'data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No record found.',
                        'data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function scanBarcode_POST()
        {
            $userId = $this->post('userId');
            $barcode = $this->post('barcode');
            if (!empty($userId) && !empty($barcode)) {
                $barcodeWhere = ['barcode' => $barcode, 'isDeleted' => 1];
                if ($this->db->select('barcode')->where($barcodeWhere)->get('products_barcode')->num_rows()) {
                    if ($this->db->select('id')->where(['id' => $userId, 'status' => 1, 'isDeleted' => 1])->get('electrician')->num_rows()) {
                        $barcodeWhere['status'] = 0;
                        $barcodeQuery = $this->db->select('id, rewardPoints, productId')->where($barcodeWhere)->get('products_barcode');
                        if ($barcodeQuery->num_rows()) {
                            $walletQuery = $this->db->select('SUM(credit) as Credit, SUM(debit) AS Debit')->where('userId', $userId)->get('electrician_wallet')->row();
                            $walletCreditData = [
                                'date' => date('Y-m-d'),
                                'credit' => $barcodeQuery->row()->rewardPoints,
                                'balance' => (($walletQuery->Credit - $walletQuery->Debit) + $barcodeQuery->row()->rewardPoints),
                                'userId' => $userId,
                                'productId' => $barcodeQuery->row()->productId,
                                'created' => date('d-m-Y h:i:s A'),
                                'createdBy' => get_title('electrician', ['id' => $userId], 'name')
                            ];

                            $result = $this->db->insert('electrician_wallet', $walletCreditData);

                            $ProductsBarcodeData = [
                                'status' => 1,
                                'redeemBy' => get_title('electrician', ['id' => $userId], 'name'),
                                'updated' => date('d-m-Y h:i:s A'),
                                'updatedBy' => get_title('electrician', ['id' => $userId], 'name')
                            ];
                            $this->db->where('id', $barcodeQuery->row()->id)->update('products_barcode', $ProductsBarcodeData);

                            $returnData = [
                                'rewardPoint' => $barcodeQuery->row()->rewardPoints,
                                'productName' => get_title('product', ['id' => $barcodeQuery->row()->productId], 'p_name')
                            ]; 
                            
                            if ($result) {
                                $this->response([
                                    'status' => TRUE,
                                    'message' => 'Rewards redeem successfully.',
                                    'data' => $returnData
                                ], REST_Controller::HTTP_OK);
                            } else {
                                $this->response([
                                    'status' => FALSE,
                                    'message' => 'Somethigs went wrong, try again.',
                                    'data' => 0
                                ], REST_Controller::HTTP_BAD_REQUEST);
                            }
                        } else {
                            $this->response([
                                'status' => FALSE,
                                'message' => 'Already redeemed.',
                                'data' => 0
                            ], REST_Controller::HTTP_BAD_REQUEST);
                        }
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Unauthorized User, login and try again.',
                            'data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);                        
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Invalid barcode.',
                        'data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function dashboard_GET($userId)
        {
            if (!empty($userId)) {
                $walletQuery = $this->db->select('SUM(credit) as Credit, SUM(debit) AS Debit')->where('userId', $userId)->get('electrician_wallet');
                if ($walletQuery->num_rows()) {
                    $walletData = [
                        'totalEarn' => $this->db->select('SUM(credit) as Credit')->where('userId', $userId)->where('debit', null)->where('productId IS NOT NULL', null, false)->get('electrician_wallet')->row('Credit'),
                        'totalBalance' => ($walletQuery->row()->Credit - $walletQuery->row()->Debit),
                        'totalWithdrawal' => $this->db->select('SUM(rewardPoints) as withdrawalPoint')->where('userId', $userId)->where('status', 1)->get('reward_points_withdrawal')->row('withdrawalPoint')
                    ];
                } else {
                    $walletData = [
                        'totalEarn' => 0,
                        'totalBalance' => 0,
                        'totalWithdrawal' => 0
                    ];
                }
                $this->response([
                    'status' => TRUE,
                    'message' => 'Dashboard.',
                    'data' => $walletData
                ], REST_Controller::HTTP_OK);                
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function withdrawalRequest_POST()
        {
            $userId = $this->post('userId');
            $rewardPoints = $this->post('rewardPoints');
            $fcmId = $this->post('fcmId');
            $remark = $this->post('remark');
            
            if (!empty($userId) && !empty($rewardPoints)) {
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('rewardPoints', 'Reward Points', 'numeric', 
                    array(
                        'numeric' => 'The Reward Points field must be a numeric value.'
                    )
                );
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => FALSE,
                        'message' => validation_errors(),
                        'data' => ""
                    ], REST_Controller::HTTP_OK);
                } else {
                    if (!empty(get_title('electrician', ['id' => $userId], 'upiId'))) {
                        $rewardPointData = $this->db->select('rewardPoint, equivalentRupee, minimumWithdrawalAmount')->get('reward_point_cost')->row();
                        $withdrawalRequestAmount = (($rewardPoints / $rewardPointData->rewardPoint) * $rewardPointData->equivalentRupee);
                        if ($withdrawalRequestAmount >= $rewardPointData->minimumWithdrawalAmount) {
                            $walletQuery = $this->db->select('SUM(credit) as Credit, SUM(debit) AS Debit')->where('userId', $userId)->get('electrician_wallet');
                            if ($walletQuery->num_rows()) {
                                $balanceRewardPoints = ($walletQuery->row()->Credit - $walletQuery->row()->Debit);
                                if ($balanceRewardPoints >= $rewardPoints) {
                                    $withdrawalRequest = [
                                        'date' => date('Y-m-d'),
                                        'rewardPoints' => $rewardPoints,
                                        'fcmId' => $fcmId,
                                        'remark' => $remark,
                                        'userId' => $userId,
                                        'created' => date('d-m-Y h:i:s A'),
                                        'createdBy' => get_title('electrician', ['id' => $userId], 'name')
                                    ];

                                    $result = $this->db->insert('reward_points_withdrawal', $withdrawalRequest);

                                    $walletData = [
                                        'date' => date('Y-m-d'),
                                        'debit' => $rewardPoints,
                                        'balance' => ($balanceRewardPoints - $rewardPoints),
                                        'userId' => $userId,
                                        'keyNote' => $remark,
                                        'created' => date('d-m-Y h:i:s A'),
                                        'createdBy' => get_title('electrician', ['id' => $userId], 'name')
                                    ];
                                    $this->db->insert('electrician_wallet', $walletData);

                                    if ($result) {
                                        $this->response([
                                            'status' => TRUE,
                                            'message' => 'Request submitted successfully.',
                                            'data' => ""
                                        ], REST_Controller::HTTP_OK);
                                    } else {
                                        $this->response([
                                            'status' => FALSE,
                                            'message' => 'Somethigs went wrong, try again.',
                                            'data' => 0
                                        ], REST_Controller::HTTP_BAD_REQUEST);
                                    }
                                } else {
                                    $this->response([
                                        'status' => FALSE,
                                        'message' => 'You can withdrawal only available reward points.',
                                        'data' => 0
                                    ], REST_Controller::HTTP_BAD_REQUEST);                            
                                }
                            } else {
                                $this->response([
                                    'status' => FALSE,
                                    'message' => 'No record found.',
                                    'data' => 0
                                ], REST_Controller::HTTP_BAD_REQUEST);
                            }
                        } else {
                            $this->response([
                                'status' => FALSE,
                                'message' => 'You can withdraw 100 rupees or a greater amount.',
                                'data' => 0
                            ], REST_Controller::HTTP_BAD_REQUEST);                        
                        }
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Before proceeding, please update your UPI Id.',
                            'data' => 'redirect'
                        ], REST_Controller::HTTP_BAD_REQUEST);                         
                    }
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function earnReport_GET($userId)
        {
            if (!empty($userId)) {
                $earnPointsQuery = $this->db->select('date, credit, productId')->where(['userId' => $userId, 'debit' => NULL])->where('productId IS NOT NULL', null, false)->order_by('id', 'DESC')->get('electrician_wallet');
                if ($earnPointsQuery->num_rows()) {
                    $earnPointsArray = [];
                    foreach ($earnPointsQuery->result() as $key => $earnPointData) {
                        $earnPointsArray[] = [
                            'date' => $earnPointData->date,
                            'credit' => $earnPointData->credit,
                            'produtName' => get_title('product', ['id' => $earnPointData->productId], 'p_name')
                        ];
                    }
                    
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Success.',
                        'data' => $earnPointsArray,
                        'rewardPointAndCost' => $this->db->select('rewardPoint, equivalentRupee')->get('reward_point_cost')->row()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No Record found.',
                        'data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function walletTransactions_GET($userId)
        {
            if (!empty($userId)) {
                $walletTransactionsQuery = $this->db->select('credit, debit, balance, created')->where('userId', $userId)->order_by('id', 'DESC')->get('electrician_wallet');
                if ($walletTransactionsQuery->num_rows()) {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Success.',
                        'data' => $walletTransactionsQuery->result()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No Record found.',
                        'data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function withdrawalReport_GET($userId)
        {
            if (!empty($userId)) {
                $rewardPointsWithdrawalQuery = $this->db->select('date, rewardPoints, status, adminRemark')->where(['userId' => $userId, 'isDeleted' => 1])->order_by('id', 'DESC')->get('reward_points_withdrawal');
                if ($rewardPointsWithdrawalQuery->num_rows()) {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Success.',
                        'data' => $rewardPointsWithdrawalQuery->result()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'No Record found.',
                        'data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function catalogue_GET($userId)
        {
            if (!empty($userId)) {
                $userQuery = $this->db->select('id')->where(['id' => $userId, 'isDeleted' => 1])->get('electrician');
                if ($userQuery->num_rows()) {
                    $catalogueQuery = $this->db->select('catalogue')->get('reward_point_cost');
                    if ($catalogueQuery->num_rows()) {
                        $catalogueUrl = base_url() . 'uploads/catalogue/' . $catalogueQuery->row()->catalogue;
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Success.',
                            'data' => ['catalogueUrl' => !empty($catalogueQuery->row()->catalogue) ? $catalogueUrl : '']
                        ], REST_Controller::HTTP_OK);                        
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'No record found.',
                            'data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);                        
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'You are not authorised.',
                        'data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Enter required fields.',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }