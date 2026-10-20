<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    date_default_timezone_set('Asia/Kolkata');
    
    class Userlist extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper(['url', 'action']);
            $this->load->model('user_model');
            $this->load->library('ajax_pagination');
            $this->perPage = 10;
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
        }

        protected function rlues()
        {
                $rules = array(
                        array(
                                'field' => 'name',
                                'label' => 'name',
                                'rules' => 'trim|required|xss_clean|max_length[60]'
                        ),
                        array(
                                'field' => 'email',
                                'label' => 'email',
                                'rules' => 'trim|required|max_length[128]|xss_clean|callback_valid_mail'
                        ),
                        array(
                                'field' => 'number',
                                'label' => 'number',
                                'rules' => 'trim|required|max_length[12]|xss_clean|numeric|callback_valid_number'
                        ),

                );
                return $rules;
        }

        public function update_profile()
        {

                if ($_POST) {

                        $rules = $this->rlues();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                                $this->data['form_validation'] = validation_errors();
                                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                                $data['page'] = 'admin_details/profile/index';
                                $this->load->view('admin', $data);
                        } else {
                                $beta['name'] = $this->input->post('name');
                                $beta['email'] = $this->input->post('email');
                                $beta['number'] = $this->input->post('number');
                                $result = $this->user_model->updateAry('users', $beta, ['id' => $this->session->userdata('user_id')]);
                                if ($result) {
                                        $this->session->set_flashdata(['msg' => 'Profile Updated Successfully.', 'class' => 'success']);
                                        redirect('profile');
                                } else {
                                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                                        redirect('profile');
                                }
                        }
                } else {
                        redirect('profile');
                }
        }

        public function index()
    	{
    		$user_id = $this->session->userdata('user_id');
    		$role_id = $this->session->userdata('roles');
    		$data = array();
    		// Get record count 
    		$conditions['returnType'] = 'count';
    		if ($role_id == 1) {
    			$conditions['where'] = ['userType !=' => 1];
    		} elseif ($role_id == 2) {
    			$conditions['where'] = ['referral' => $this->session->referral, 'userType !=' => 2];
    		} elseif ($role_id == 4) {
    			$conditions['where'] = ['referral' => $this->session->referral, 'userType !=' => 4, 'employee_assign_id' => $user_id];
    		} else {
    			$conditions['where'] = ['id' => $user_id, 'userType' => 3];
    		}
    		$totalRec = $this->user_model->getCommonRows($conditions, 'users');
    
    		// Pagination configuration 
    		$config['target']      = '#dataList';
    		$config['base_url']    = base_url('userlist/load_users');
    		$config['total_rows']  = $totalRec;
    		$config['per_page']    = 10;
    		$config['link_func']   = 'searchFilter';
    
    		// Initialize pagination library 
    		$this->ajax_pagination->initialize($config);
    		// Get records 
    		$conditions['limit'] = $this->perPage;
    		$data['result'] = $this->user_model->getCommonRows($conditions, 'users');
    		// Load the list page view
    
    		$data['page'] = 'admin_details/user/userlist';
    		$this->load->view('admin', $data);
    	}

        public function load_users()
        {
    		$user_id = $this->session->userdata('user_id');
    		$role_id = $this->session->userdata('roles');
    		
		    // Define offset 
		    $page = $this->input->post('page');
		    if (!$page) {
			    $offset = 0;
		    } else {
			    $offset = $page;
		    }

    		$keywords = $this->input->post('keywords');
    		$sortBy = $this->input->post('sortBy');
    		$pagelength = $this->input->post('pagelength');
    		$column_filter = $this->input->post('column_filter');

    		if (!empty($keywords)) {
    			$conditions['search']['keywords'] = $keywords;
    		}
    		if (!empty($sortBy)) {
    			$conditions['search']['sortBy'] = $sortBy;
    		}
    		
    		// Get record count 
    		$conditions['returnType'] = 'count';
    		if ($role_id == 1) {
    			$conditions['where'] = ['userType !=' => 1];
    		} elseif ($role_id == 2) {
    			$conditions['where'] = ['referral' => $this->session->referral, 'userType!=' => 2];
    		} elseif ($role_id == 4) {
    			$conditions['where'] = ['referral' => $this->session->referral, 'userType!=' => 4, 'employee_assign_id' => $user_id];
    		} else {
    			$conditions['where'] = ['id' => $user_id, 'userType' => 3];
    		}    		
    		
    		$totalRec = $this->user_model->getCommonRows($conditions, 'users', $column_filter);
    
    		// Pagination configuration 
    		$config['target']      = '#dataList';
    		$config['base_url']    = base_url('user/load_users');
    		$config['total_rows']  = $totalRec;
    		$config['per_page']    = $pagelength;
    		$config['link_func']   = 'searchFilter';
    		// Initialize pagination library 
    		$this->ajax_pagination->initialize($config);
    		// Get records 
    		$conditions['start'] = $offset;
    		$conditions['limit'] = $pagelength;
    		unset($conditions['returnType']);
    		$data['result'] = $this->user_model->getCommonRows($conditions, 'users', $column_filter);
    		// Load the data list view 
    		$this->load->view('admin_details/user/load_user', $data, false);
	    }

        protected function rules()
        {
                $rules = array(
                        array(
                                'field' => 'name',
                                'label' => 'name',
                                'rules' => 'trim|required|max_length[60]'
                        ),
                        array(
                                'field' => 'email',
                                'label' => 'email',
                                'rules' => 'trim|max_length[60]|valid_email|is_unique[users.email]'
                        ),
                        array(
                                'field' => 'number',
                                'label' => 'number',
                                'rules' => 'trim|max_length[25]|min_length[5]|is_unique[users.number]'
                        ),

                        array(
                                'field' => 'password',
                                'label' => 'password',
                                'rules' => 'trim|required|min_length[4]|max_length[40]'
                        )
                );
                return $rules;
        }

        public function detect_amount()
        {

                if (!$this->input->post()) {
                        echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                        return;
                }

                $amount = $this->input->post('amount');
                $msg = $this->input->post('msg');

                $user_id = $this->input->post('user_id');

                $where = ['id' => $user_id];
                $userofo = $this->user_model->getRow('users', $where);
                $amounts = $userofo->amount;
                $userType = $userofo->userType;
                $referral = $userofo->referral;
                $data['amount'] = $amounts - $amount;
                $res = $this->user_model->updateAry('users', $data, $where);

                if ($userType == 2) {
                        // Subadmin add amount by only admin
                        $admin_amount = get_title('users', ['id' => $this->session->userdata('user_id')], 'amount');
                        $admn_amount_deductuion['amount'] = $admin_amount + $amount;
                        $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $this->session->userdata('user_id')]);
                } else if ($userType == 4) {
                        $subadmin_id = get_title('users', ['referral' => $referral, 'userType' => 2], 'id');
                        $subadmin_amount = get_title('users', ['referral' => $referral, 'userType' => 2], 'amount');
                        $admn_amount_deductuion['amount'] = $subadmin_amount + $amount;
                        $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $subadmin_id]);
                } else {
                        $subadmin_id = get_title('users', ['referral' => $referral, 'userType' => 2], 'id');
                        $subadmin_amount = get_title('users', ['referral' => $referral, 'userType' => 2], 'amount');
                        if ($subadmin_id) {
                                $admn_amount_deductuion['amount'] = $subadmin_amount + $amount;
                                $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $subadmin_id]);
                        } else {
                                $admin_amount = get_title('users', ['id' => $this->session->userdata('user_id')], 'amount');
                                $admn_amount_deductuion['amount'] = $admin_amount + $amount;
                                $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $this->session->userdata('user_id')]);
                        }
                }
                if ($res) {
                        $mountData['assign_user'] = $this->session->userdata('user_id');
                        $mountData['user_id'] = $this->input->post('user_id');
                        $mountData['act_status'] = "DBT";
                        $mountData['add_amount'] = 2;
                        //detetect =>add_amount=2
                        $mountData['msg'] = $msg;

                        // $mountData['add_amount']=1;


                        date_default_timezone_set('Asia/Kolkata');
                        $mountData['voucherdate'] = date('d-m-Y h:ia');
                        $mountData['vouchernumber'] = "TRANDEBITE" . rand(0, 1000) . "DIR";
                        $mountData['created_at'] = date('Y-m-d');

                        // $mountData['tranDate'] = date("d/m/Y G.i:s");

                        $mountData['amount'] = $this->input->post('amount');
                        $return_id = $this->user_model->saveAry('wallet', $mountData);
                        if ($return_id) {
                                echo json_encode(['status' => true, 'data' => $return_id, 'msg' => 'Amount Added successfully.']);
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

        public function amount()
        {

                if (!$this->input->post()) {
                        echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                        return;
                }

                $amount = $this->input->post('amount');
                $user_id = $this->input->post('user_id');
                $msg = $this->input->post('msg');

                if (!$this->input->post('user_id')) {
                        echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                        return;
                }
                // $this->form_validation->set_rules('mobile', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]'); //{10} for 10 digits number

                $where = ['id' => $user_id];
                $userofo = $this->user_model->getRow('users', $where);
                $amounts = $userofo->amount;
                $userType = $userofo->userType;
                $referral = $userofo->referral;

                $data['amount'] = $amount + $amounts;
                $res = $this->user_model->updateAry('users', $data, $where);
                if ($userType == 2) {
                        // Subadmin add amount by only admin
                        $admin_amount = get_title('users', ['id' => $this->session->userdata('user_id')], 'amount');
                        $admn_amount_deductuion['amount'] = $admin_amount - $amount;
                        $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $this->session->userdata('user_id')]);
                } else if ($userType == 4) {
                        $subadmin_id = get_title('users', ['referral' => $referral, 'userType' => 2], 'id');
                        $subadmin_amount = get_title('users', ['referral' => $referral, 'userType' => 2], 'amount');
                        $admn_amount_deductuion['amount'] = $subadmin_amount - $amount;
                        $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $subadmin_id]);
                } else {
                        $subadmin_id = get_title('users', ['referral' => $referral, 'userType' => 2], 'id');
                        $subadmin_amount = get_title('users', ['referral' => $referral, 'userType' => 2], 'amount');
                        if ($subadmin_id) {
                                $admn_amount_deductuion['amount'] = $subadmin_amount - $amount;
                                $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $subadmin_id]);
                        } else {
                                $admin_amount = get_title('users', ['id' => $this->session->userdata('user_id')], 'amount');
                                $admn_amount_deductuion['amount'] = $admin_amount - $amount;
                                $this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $this->session->userdata('user_id')]);
                        }
                }


                if ($res) {
                        $mountData['assign_user'] = $this->session->userdata('user_id');
                        $mountData['user_id'] = $this->input->post('user_id');
                        $mountData['act_status'] = "CR";
                        $mountData['add_amount'] = 1;
                        $mountData['msg'] = $msg;

                        // $mountData['add_amount']=1;


                        date_default_timezone_set('Asia/Kolkata');

                        $mountData['voucherdate'] = date('d-m-Y h:ia');
                        $mountData['vouchernumber'] = "TRANSCREDIT" . rand(0, 1000) . "DIR";

                        $mountData['created_at'] = date('Y-m-d');


                        // $mountData['tranDate'] = date("d/m/Y G.i:s");

                        $mountData['amount'] = $this->input->post('amount');
                        $return_id = $this->user_model->saveAry('wallet', $mountData);
                        if ($return_id) {
                                echo json_encode(['status' => true, 'data' => $return_id, 'msg' => 'Amount Added successfully.']);
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

        public function amount_update()
        {

                if (!$this->input->post('id') && !$this->input->post('user_id')) {
                        echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                        return;
                }


                $amount = $this->input->post('amount');
                $user_id = $this->input->post('user_id');
                $id = $this->input->post('id');

                $walletdata = $this->user_model->getRow('wallet', ['id' => $id]);

                if ($walletdata) {
                        $stay_walet_amounts = $walletdata->amount;
                        // added amount 
                        $walet_amount = $walletdata->amount;


                        $where = ['id' => $id];
                        $informaiton['amount'] = $amount;

                        // echo '<pre>';
                        // print_r($informaiton);
                        // die;
                        // die;

                        $return_id = $this->user_model->updateAry('wallet', $informaiton, $where);

                        $where = ['id' => $user_id];
                        $walletdata = $this->user_model->getRow('users', $where);
                        $total = $walletdata->amount;
                        $stay_walet_amount = 0;
                        $data = [];
                        // added amount 

                        $walet_amount = $walletdata->amount;
                        if ($stay_walet_amounts > $amount) {
                                $stay_walet_amount = $stay_walet_amounts - $amount;
                                $data['amount'] = $total - $stay_walet_amount;
                        } else if ($stay_walet_amounts < $amount) {
                                $stay_walet_amount = $amount - $stay_walet_amounts;
                                $data['amount'] = $total + $stay_walet_amount;
                        } else {
                                $stay_walet_amount = $amount;
                                $data['amount'] = $walet_amount;
                        }

                        // $amounts = $walet_amount->amount;
                        $res = $this->user_model->updateAry('users', $data, $where);

                        // if ($res) {
                        if ($res) {
                                echo json_encode(['status' => true, 'data' => $res, 'msg' => 'Amount Added successfully.']);
                                return;
                        } else {
                                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                                return;
                                // }
                        }
                } else {
                        echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                        return;
                }
                // echo '<pre>';
                // print_r($stay_walet_amount);
                // die;


        }

        public function saveuser()
        {
            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                    $data['page'] = 'admin_details/user/userlist';
                    $this->load->view('admin', $data);
                } else {
                    $data['name'] = $this->input->post('name');
                    $data['email'] = $this->input->post('email');
                    $data['password'] = $this->input->post('password');
                    $data['number'] = $this->input->post('number');
                    $data['gender'] = $this->input->post('gender');
                    $data['user_number'] = rand(0,999999);
                    $data['usertype'] = $this->input->post('userType');
                    $data['referral'] = $this->input->post('referral');
                    $data['permission_account_add'] = $this->input->post('permission_account_add');
                    $data['license_cost'] = $this->input->post('license_cost');
                    $data['created'] = date('d-m-Y h:i:s A');
                    $data['started'] = date('d-m-Y');
                    $data['expiry'] = date('d-m-Y', strtotime(date('d-m-Y', strtotime(date('d-m-Y'))) . ' + 1 year'));

                    $result = $this->user_model->saveAry('users', $data);
                    $send_sms = sendsms($this->input->post('number'), $this->input->post('email'));
                    if ($data['usertype'] == 3) {
                        $referral = $this->input->post('referral');
                        $where = ['referral' => $referral, 'userType' => 2];
                        $return = $this->user_model->getRow('users', $where);
                        if ($return) {
                            $array['referral_id'] = $return->id;
                            $array['userid'] = $result;
                            $result = $this->user_model->saveAry('subcat', $array);
                        }
                    }
                    if ($result) {
                        $metadata = array(
                            'first_name'      => $this->input->post('name'),
                            'umatic_user_id'      => $result,
                            'phone' => $this->input->post('number'),
                            'email'    => $this->input->post('email'),
                            'password' => $this->input->post('password'),
                            'country' => "INDIA",
                            'state' => "KARNATAKA"
                        );
                        $result = $this->store_umatic_registration($metadata);
                        $data['page'] = 'admin_details/userlist';
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        redirect('userlist');
                    } else {
                        $data['page'] = 'admin_details/userlist';
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        redirect('userlist');
                    }
                }
            } else {
                $data["page"] = "admin_details/userlist";
                redirect('userlist');
            }
        }

        public function savesubuser()
        {
            if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                            $this->data['form_validation'] = validation_errors();
                            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                            $data['page'] = 'admin_details/user/userlist';
                            $this->load->view('admin', $data);
                    } else {

                            $data['name'] = $this->input->post('name');
                            $data['email'] = $this->input->post('email');
                            $data['password'] = $this->input->post('password');
                            $data['number'] = $this->input->post('number');
                            $data['gender'] = $this->input->post('gender');
                            $data['usertype'] = $this->input->post('userType');
                            $data['referral'] = $this->input->post('referral');
                            $data['user_number'] = rand(0, 999999);
                            $data['license_cost'] = $this->input->post('license_cost');
                            $data['created'] = date('d-m-Y h:i:s A');
                            $data['started'] = date('d-m-Y');
                            $data['expiry'] = date('d-m-Y', strtotime(date('d-m-Y', strtotime(date('d-m-Y'))) . ' + 1 year'));

                            if ($data['usertype'] == 3) {
                                    $refer = $this->input->post('referral');
                                    $where = ['id' => $refer];
                                    $names = $this->user_model->getRow('users', $where);
                                    if ($names) {
                                            $data['referral'] = $names->referral;
                                            $referral = $names->referral;
                                            $result = $this->user_model->saveAry('users', $data);
                                            $where = ['id' => $refer, 'userType' => 4];
                                            $return = $this->user_model->getRow('users', $where);
                                            if ($return) {
                                                    // $array['referral_id'] = $return->id;/
                                                    $only_employee_id['employee_assign_id'] = $return;
                                                    $this->user_model->updateAry('users', $only_employee_id, ['id' => $result]);
                                                    $array['referral_id'] = $this->session->userdata('user_id');
                                                    $array['emp_id'] = $refer;
                                                    $array['userid'] = $result;
                                                    $array['usertype'] = 4;

                                                    $result = $this->user_model->saveAry('subcat', $array);
                                            }
                                    } else {
                                            $data['referral'] = $this->session->userdata('referral');
                                            $result = $this->user_model->saveAry('users', $data);
                                            if ($result) {
                                                    $array['userid'] = $result;
                                                    $array['referral_id'] = $this->session->userdata('user_id');

                                                    // $array['usertype'] = 4;
                                                    $result = $this->user_model->saveAry('subcat', $array);
                                            } else {
                                                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                                                    redirect('userlist');
                                            }
                                    }
                            } else {
                                    $result = $this->user_model->saveAry('users', $data);
                            }
                            if ($result) {
                            $send_sms = sendsms($this->input->post('number'), $this->input->post('email'));

                                    $metadata = array(
                                            'first_name'      => $this->input->post('name'),
                                            'umatic_user_id'      => $result,
                                            'phone' => $this->input->post('number'),
                                            'email'    => $this->input->post('email'),
                                            'password' => $this->input->post('password'),
                                            'country' => "INDIA",
                                            'state' => "KARNATAKA"

                                    );
                                    $result = $this->store_umatic_registration($metadata);
                                    $data['page'] = 'admin_details/userlist';
                                    $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                                    redirect('userlist');
                            } else {
                                    $data['page'] = 'admin_details/userlist';

                                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                                    redirect('userlist');
                            }
                    }
            } else {
                $data["page"] = "admin_details/userlist";
                redirect('userlist');
            }
        }

        public function store_umatic_registration($metadata)
        {
                $data_string = json_encode($metadata);
                // API STORE UMATIC
                // $curl = curl_init('http://localhost/store/index.php/authi/registration');
                $curl = curl_init('https://store.umatic.in/index.php/authi/registration');


                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

                curl_setopt(
                        $curl,
                        CURLOPT_HTTPHEADER,
                        array(
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($data_string)
                        )
                );

                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Make it so the data coming back is put into a string
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);  // Insert the data

                // Send the request
                $result = curl_exec($curl);

                // Free up the resources $curl is using
                curl_close($curl);
                return $result;
        }

        public function see_details($id = null)
        {
            if (empty($id)) {
                $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                redirect('userlist');
            }
            $role_id = $this->session->userdata('roles');
            $user_id = $this->session->userdata('user_id');
            $referral = $this->session->userdata('referral');
            $wallet = '';
            $order = ['id' => 'ASC'];

            if ($role_id == 1 && $id == 1) {
                $wallet = $this->user_model->getAry('wallet',$order, '','',['status'=>1]);
            } else {
                $wallet = $this->user_model->getAry('wallet',$order, '', '', ['user_id' => $id,'status'=>1]);
            }
            $result['wallet'] = $wallet;
            $result['page'] = 'admin_details/wallet/view_wallet';
            $this->load->view('admin', $result);
        }

        public function edit($id)
        {
            $where = ['id' => $id];
            $return = $this->user_model->getRow('users', $where);

            if ($return) {
                $data['userinfo'] = $return;
                $data['page'] = 'admin_details/userlist_edit';
                $this->load->view('admin', $data);
            } else {
                $data['page'] = 'admin_details/userlist';
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                redirect('userlist');
            }
        }

        public function logindirect($id = null)
        {
            if (isset($id) && !empty($id)) {
                $where = ['id' => $id];
                $login_data = $this->user_model->getRow('users', $where);
                if ($login_data) {
                    $subusers = $this->session->userdata('user_id');
                    if ($subusers) {
                        $this->session->set_userdata('subuser', $subusers);
                        $this->session->set_userdata('roless', "Aman");
                    }

                    $time = date("h:i:s A");
                    $date = date("d-m-Y");
                    $m = date("m");
                    $d = date("d");
                    $y = date("Y");

                    $this->session->set_userdata('user_id', $login_data->id);
                    $this->session->set_userdata('roles', $login_data->userType);
                    $this->session->set_userdata('name', $login_data->name);
                    $this->session->set_userdata('email', $login_data->email);
                    $this->session->set_userdata('referral', $login_data->referral);
                    $this->session->set_userdata('name', $login_data->name);
                    $this->session->set_userdata('login', $time);
                    $this->session->set_userdata('date', $date);

                    $data['loginDate'] = $date;
                    $data['loginTime'] = $time;
                    $data['userId'] = $login_data->id;
                    $data['roles'] = $login_data->userType;
                    $data['dd'] = $d;
                    $data['mm'] = $m;
                    $data['yy'] = $y;
                    $data['strtotime'] = strtotime('now');

                    $this->user_model->saveAry('userlogindetails', $data);
                    $this->session->set_userdata('barcode', 'yes');
                    $this->session->set_userdata('lrdate', 'yes');
                    $this->session->set_userdata('eway', 'yes');
                    $this->session->set_userdata('lrnumber', 'yes');
                    $this->session->set_userdata('duedate', 'yes');
                    $this->session->set_userdata('ptd', 'yes');
                    $this->session->set_userdata('dpb', 'yes');
                    $this->session->set_userdata('dcdate', 'yes');
                    $this->session->set_userdata('dcno', 'yes');
                    redirect('signin');
                } else {
                    redirect('signin');
                }
            } else {
                redirect('signin');
            }
        }

        public function valid_mail()
        {
            $where_not = ['id !=' => $this->session->userdata('user_id')];
            $where = ['email' => $this->input->post('email')];
            if ($this->user_model->check_unique('users', $where_not, $where)) {
                return TRUE;
            }
            $this->form_validation->set_message('valid_mail', 'Email Should be Unique');
            return FALSE;
        }

        public function valid_number()
        {
            $where_not = ['id !=' => $this->session->userdata('user_id')];
            $where = ['number' => $this->input->post('number')];
            if ($this->user_model->check_unique('users', $where_not, $where)) {
                return TRUE;
            }
            $this->form_validation->set_message('valid_number', 'Phone Number Should be Unique');
            return FALSE;
        }

        public function loginForInventory($id = null, $comp_id = null)
        {
            if (isset($id) && !empty($id) && $comp_id) {
                $where = ['id' => $id];
                $login_data = $this->user_model->getRow('users', $where);
                if ($login_data) {
                    $subusers = $this->session->userdata('user_id');
                    if ($subusers) {
                        $this->session->set_userdata('subuser', $subusers);
                        $this->session->set_userdata('roless', "Aman");
                    }
                    $time = date("h:i:sa");
                    $date = date("d-m-Y");
                    $m = date("m");
                    $d = date("d");
                    $y = date("Y");

                    $this->session->set_userdata('user_id', $login_data->id);
                    $this->session->set_userdata('roles', $login_data->userType);
                    $this->session->set_userdata('name', $login_data->name);
                    $this->session->set_userdata('email', $login_data->email);
                    $this->session->set_userdata('referral', $login_data->referral);
                    $this->session->set_userdata('name', $login_data->name);
                    $this->session->set_userdata('login', $time);
                    $this->session->set_userdata('date', $date);

                    $data['loginDate'] = $date;
                    $data['loginTime'] = $time;
                    $data['userId'] = $login_data->id;
                    $data['roles'] = $login_data->userType;
                    $data['dd'] = $d;
                    $data['mm'] = $m;
                    $data['yy'] = $y;

                    $this->user_model->saveAry('userlogindetails', $data);
                    $this->session->set_userdata('barcode', 'yes');
                    $this->session->set_userdata('lrdate', 'yes');
                    $this->session->set_userdata('eway', 'yes');
                    $this->session->set_userdata('lrnumber', 'yes');
                    $this->session->set_userdata('duedate', 'yes');
                    $this->session->set_userdata('ptd', 'yes');
                    $this->session->set_userdata('dpb', 'yes');
                    $this->session->set_userdata('dcdate', 'yes');
                    $this->session->set_userdata('dcno', 'yes');
                    redirect('inventory_billing/view/' . $comp_id);
                } else {
                    redirect('signin');
                }
            } else {
                redirect('signin');
            }
        }

        public function changeemployee()
        {
            $start = 0;
            $data['page'] = 'admin_details/userlist/index';
            $order = ['order' => 'DESC'];
            $employeess = $this->user_model->getResult('users', ['userType' => 4]);
            if ($employeess) {
                foreach ($employeess as $remp) {
                    $where = ['emp_id' => $remp->id, 'usertype' => 4];
                    $employye = $this->user_model->getAry('subcat', $order, $start, '', $where);
                    foreach ($employye as $emp) {
                        $user_id = $emp->userid;
                        $where = ['id' => $user_id];
                        $employees_id['employee_assign_id'] = $remp->id;
                        $this->user_model->updateAry('users', $employees_id, $where);
                    }
                }
            }
        }

        public function changestatus ($id)
        {
            if ($id) {
                $UsersRecord = $this->user_model->getRow('users', ['id' => $id]);
                if ($UsersRecord) {
                    if ($UsersRecord->status == 1) {
                        $status = 0;
                    } else {
                        $status = 1;
                    }
                    $result = $this->user_model->updateAry('users', ['status' => $status, 'updated' => date('d-m-Y h:i:s A')], ['id' => $id]);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'userlist');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'userlist');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'userlist');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'userlist');
            }
        }
        
        public function GetUserInfo ($id)
        {
            if ($id) { 
                $data['UserInfo'] = $this->user_model->getRow('users', ['id' => $id]);
                return $this->load->view('admin_details/user/GetUserInfo', $data);
            }
        }
        
        public function UpdateLicense ()
        {
            if ($_POST) {
                $this->form_validation->set_rules('expiry', 'Expiry', 'trim|required');
                if ($this->form_validation->run() == false) {                    
                    $data['page'] = "admin_details/user/userlist";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('admin', $data);
                } else {
                    $userid = $this->input->post('id');
                    $RenewalData = [
                        'started' => date('d-m-Y'),
                        'updated' => date('d-m-Y h:i:s A'),
                        'expiry' => date('d-m-Y', strtotime($this->input->post('expiry'))),
                    ];
                    
                    $result = $this->user_model->updateAry('users', $RenewalData, ['id' => $userid]);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'userlist');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'userlist');
                    }
                }
            }
        }
        
        public function exportusers ()
        {
            $data['allusers'] = $this->user_model->getResult('users', '');
            
            print_r($this->load->view('exportexcelforusers', $data, true));
        }
    }