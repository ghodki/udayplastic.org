<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Wallet extends CI_Controller
    {
	    public function __construct()
	    {
		    parent::__construct();
		    
    		$this->load->helper(['url', 'form']);
    		
    		$this->load->model('user_model');
    		
			$this->load->library('ajax_pagination');
			
    		$this->perPage = 10;
	    }


	    public function wallet_list()
	    {
			// POST data
			$postData = $this->input->post();
		
			// Get data
			$data = $this->user_model->getUsers($postData,['user_id'=>$this->session->userdata('user_id')]);
	
			echo json_encode($data);
	    }

        public function index()
	    {
			$this->wallet_report();

	    }
	
		public function load_wallet()
	    {
		    $fileSharingPermission = $this->user_model->getPermissionForUser('wallet');
		    
		    if (!$fileSharingPermission) {
			    $data['page'] = 'admin_details/error/permission_denied';
		    } else {
    			$data = array();
    			$user_id = $this->session->userdata('user_id');
    			$role_id = $this->session->userdata('roles');
    			
    			if ($role_id == 1) {
				    // admin
			    } else if ($role_id == 3) {
    				// user
    				$data['page'] = 'user/wallet/index';
    				$conditions['where'] = ['user_id' => $user_id];
			    } else if ($role_id == 2) {
				    // subadmin
			    } else {
				    // employee
			    }
			    
    			// Get record count 
    			$conditions['returnType'] = 'count';
    			$totalRec = $this->user_model->getCommonRows($conditions, 'wallet');
    			// Pagination configuration 
    			$config['target']      = '#dataList';
    			$config['base_url']    = base_url('wallet/load_wallet_list');
    			$config['total_rows']  = $totalRec;
    			$config['per_page']    = $this->perPage;
    			$config['link_func']   = 'searchFilter';
    			// Initialize pagination library 
    			$this->ajax_pagination->initialize($config);
    			// Get records 
    			$conditions['limit'] = $this->perPage;
    			$data['result'] = $this->user_model->getCommonRows($conditions, 'wallet');
    			// Load the list page view
		    }
		    
		    $this->load->view('admin', $data);
    	}


	    public function load_wallet_list()
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

			$conditions['where'] = array(
				'user_id' => $user_id
			);

    		$keywords = '';
    		$sortBy = $this->input->post('sortBy');
    		$pagelength = $this->input->post('pagelength');
    
    		$conditions['returnType'] = 'count';
    		if (!empty($keywords)) {
    			$conditions['search']['keywords'] = $keywords;
    		}
    		if (!empty($sortBy)) {
    			$conditions['search']['sortBy'] = $sortBy;
    		}
    		$totalRec = $this->user_model->getCommonRows($conditions, 'wallet');

    		// Pagination configuration 
    		$config['target']      = '#dataList';
    		$config['base_url']    = base_url('wallet/load_wallet_list');
    		$config['total_rows']  = $totalRec;
    		$config['per_page']    = $pagelength;
    		$config['link_func']   = 'searchFilter';
    		// Initialize pagination library 
    		$this->ajax_pagination->initialize($config);
    		// Get records 
    		$conditions['start'] = $offset;
    		$conditions['limit'] = $pagelength;
    		unset($conditions['returnType']);
    		$data['result'] = $this->user_model->getCommonRows($conditions, 'wallet');
    		// Load the data list view 
    
    		$this->load->view('user/wallet/load_data', $data, false);
	    }

    	public function sync()
    	{
    		$role_id = $this->session->userdata('roles');
    		$user_id = $this->session->userdata('user_id');
    		$referral = $this->session->userdata('referral');
    		$limit = 100;
    		if ($role_id == 1) {
    			$where = ['userType' => 2];
    			$userinfor = $this->user_model->getAryss('users', '', '', $limit, $where);
    			$wallet = [];
    			foreach ($userinfor as $userDetails) {
    				$there = ['user_id' => $userDetails->id];
    				$mil_gaya_data = $this->user_model->getAryss('wallet', '', '', '', $there);
    				if ($mil_gaya_data) {
    					foreach ($mil_gaya_data as $mila) {
    						$info['admin_id'] = $user_id;
    						$info['subadmin_id'] = $userDetails->id;
    						$this->user_model->updateAry('wallet', $info, ['id' => $mila->id]);
    					}
    				}
    			}
    		} else if ($role_id == 3) {
    		} else if ($role_id == 2) {
    			// subadmin
    			$where = ['referral' => $referral, 'id!=' => $user_id];
    			$userinfor = $this->user_model->getAryss('users', '', '', '', $where);
    			if ($userinfor) {
    				foreach ($userinfor as $userDetails) {
    					$there = ['user_id' => $userDetails->id, 'subadmin_id' => null];
    					$mil_gaya_data = $this->user_model->getAryss('wallet', '', '', '', $there);
    					if ($mil_gaya_data) {
    						foreach ($mil_gaya_data as $mila) {
    							$info['subadmin_id'] = $user_id;
    							$this->user_model->updateAry('wallet', $info, ['id' => $mila->id]);
    						}
    					}
    				}
    			}
    		} else {
    			$where = ['emp_id' => $user_id, 'userType' => 4, 'status' => 1];
    			$userinfor = $this->user_model->getAry('subcat', '', '', '', $where);
    			foreach ($userinfor as $userDetails) {
    				$there = ['user_id' => $userDetails->userid];
    				$mil_gaya_data = $this->user_model->getAry('wallet', '', '', '', $there);
    				if ($mil_gaya_data) {
    					foreach ($mil_gaya_data as $mila) {
    						// $wallet[] = $mila;
    						$info['emp_id'] = $user_id;
    
    						$this->user_model->updateAry('wallet', $info, ['id' => $mila->id]);
    					}
    				}
    			}
    		}
    		return redirect('wallet');
	    }



    	public function wallet_report()
    	{
    		$role_id = $this->session->userdata('roles');
    		$user_id = $this->session->userdata('user_id');
    		$referral = $this->session->userdata('referral');
    		$fromDate = '';
    		$toDate = '';
    		$bank_account = '';
    		$limit = 20;

    		if ($this->input->get('bank_account')) {
    			$bank_account = ['bank_account' => $this->input->get('bank_account')];
    		}
    		if ($this->input->get('records')) {
    			$limit = $this->input->get('records');
    			if ($limit == 'all') {
    				$limit = '';
    			} elseif ($limit <= 0) {
    				$limit = 20;
    			}
    		}
    		if ($this->input->get('fromDate') &&  $this->input->get('toDate')) {
    			$fromDate = ['created_at >=' => date("Y-m-d", strtotime($this->input->get('fromDate')))];
    			$toDate = ['created_at <=' => date("Y-m-d", strtotime($this->input->get('toDate')))];
    			$limit='';
    		}
    
    		if ($role_id == 1) {
    			$data['page'] = 'admin/index';
    			$where = ['userType' => 2];
    			$where = ['admin_id' => $user_id];
    			$data['subadmin_data'] =  $this->user_model->getWalletData('wallet', '', $fromDate, $toDate, $where, $bank_account, $limit);
    			$condtion = ['admin_id' => 0];
    			$data['wallet'] =  $this->user_model->getWalletData('wallet', '', $fromDate, $toDate, $condtion, $bank_account, $limit);
    		} else if ($role_id == 3) {
    			return redirect('wallet/load_wallet');
    		} else if ($role_id == 2) {
    			// subadmin
    			$data['page'] = 'subadmin/wallet/index';
    			$where = ['subadmin_id' => $user_id, 'admin_id' => 0];
    			$data['wallet'] =  $this->user_model->getWalletData('wallet', '', $fromDate, $toDate, $where, $bank_account, $limit);
    			$where = ['user_id' => $user_id];
    			$data['subadmin_data'] =  $this->user_model->getWalletData('wallet', '', $fromDate, $toDate, $where, $bank_account, $limit);
    		} else {
    			$data['page'] = 'employee/wallet/index';
    			$where = ['emp_id' => $user_id];
    			$data['wallet'] =  $this->user_model->getWalletData('wallet', '', $fromDate, $toDate, $where, $bank_account, $limit);
    			$where = ['user_id' => $user_id];
    			$data['subadmin_data'] =  $this->user_model->getWalletData('wallet', '', $fromDate, $toDate, $where, $bank_account, $limit);
    		}
    
    		$this->load->view('admin', $data);
    	}

    	public function check()
    	{
    		$amount =  $this->input->post('payble_amount');
    		$product_info = $this->input->post('product_info');
    		$customer_name = $this->input->post('customer_name');
    		$customer_emial = $this->input->post('customer_email');
    		$customer_mobile = $this->input->post('mobile_number');
    		$customer_address = $this->input->post('customer_address');
    		$remark = $this->input->post('remark');
    		$user_id = $this->session->userdata('user_id');
    		if (!empty($this->session->userdata('ecommerce_site'))) {
    			$ecommerce_site = $this->session->userdata('ecommerce_site');
    		} else {
    			$ecommerce_site = '';
    		}
    		//payumoney details
    		$MERCHANT_KEY = "vMZTS1"; //change  merchant with yours
    		$SALT = "URW2CKFa";  //change salt with yours 
    		$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    		//optional udf values 
    		$udf1 = '';
    		$udf2 = '';
    		$udf3 = '';
    		$udf4 = '';
    		$udf5 = '';
    
    		$hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_emial . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . $SALT;
    		$hash = strtolower(hash('sha512', $hashstring));
    
    		$success = base_url() . 'index.php/Status';
    		$fail = base_url() . 'index.php/Status';
    		$cancel = base_url() . 'index.php/Status';
    		$unsuccessfull_transaction = [
    			'user_id' => $user_id,
    			'transaction_id_test' => $txnid,
    			'remark' => $remark,
    			'ecommerce_site' => $ecommerce_site
    
    
    		];
    		$this->user_model->saveAry('payment_unsuccessfull', $unsuccessfull_transaction);
    		$data = array(
    			'mkey' => $MERCHANT_KEY,
    			'tid' => $txnid,
    			'hash' => $hash,
    			'amount' => $amount,
    			'remark' => $remark,
    			'name' => $customer_name,
    			'user_id' => $user_id,
    			'productinfo' => $product_info,
    			'mailid' => $customer_emial,
    			'phoneno' => $customer_mobile,
    			'address' => $customer_address,
    			'action' => "https://secure.payu.in", //for live change action  https://secure.payu.in
    			'sucess' => $success,
    			'failure' => $fail,
    			'cancel' => $cancel
    		);
    		$data['page'] = 'admin_details/wallet/confirmation';
    
    
    		$this->load->view('admin', $data);
    	}

    	public function savewallet()
    	{
    		if ($_POST) {
    			$data['amount'] = $this->input->post('amount');
    			$data['vouchernumber'] = $this->input->post('vouchernumber');
    			
    			date_default_timezone_set('Asia/Kolkata');
    			$data['note'] = $this->input->post('name');
    			$data['bank_account'] = $this->input->post('bank_account');
    			$data['transactionID'] = 'Tran' . rand(5, 1000) . 'ID';
    			$data['voucherdate'] = date('d-m-Y h:i');
    			$data['created_at'] = date('Y-m-d');
    			$data['status'] = 0;
    			$data['add_amount'] = 4;
    			$data['act_status'] = "CR";
    			$data['user_id'] = $this->session->userdata('user_id');
    			$file_name = $_FILES['image']['name'];
    			if ($file_name != '') {
    				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/amount/' . $file_name);
    			}
    			$data['image'] = $file_name;
    			$result = $this->user_model->saveAry('wallet', $data);
    			if ($result) {
    				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
    				return redirect('wallet');
    			} else {
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				return redirect('wallet');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			return redirect('wallet');
    		}
    	}

    	public function delete($id)
    	{
    		$where = ['id' => $id];
    
    		$return = $this->user_model->deleteRow('wallet', $where);
    		if ($return) {
    
    			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
    			redirect('wallet');
    		} else {
    			$data['page'] = 'admin_details/userlist';
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			redirect('wallet');
    		}
    	}
    	
    	// WALLET APPROVE
    	public function approve()
    	{
    		// echo '<pre>';
    		// print_r($_POST);
    		// die;
    		$id = $this->input->post('id');
    		$user_id = $this->input->post('user_id');
    
    		$data['assign_user'] = $this->session->userdata('user_id');
    
    		if ($id == null || $user_id == null) {
    			echo json_encode(['status' => false, 'msg' => 'Something Went Wrong.']);
    			return;
    		}
    
    		$amount = $this->input->post('amount');
    		$data['status'] = 1;
    		$where = ['id' => $id];
    		$data['amount'] = $amount;
    		$data['act_status'] = "CR";
    		$return = $this->user_model->updateAry('wallet', $data, $where);
    		// $this->user_model->updateAry('wallet', $data, ['id' => $id + 1, 'user_id' => 1]);
    
    		$there = ['id' => $user_id];
    		$wlt = $this->user_model->getRow('users', $there);
    		$amount = $wlt->amount + $amount;
    		$amounts['amount'] = $amount;
    		$return = $this->user_model->updateAry('users', $amounts, $there);
    		if ($return) {
    			echo json_encode(['status' => true, 'data' => $return, 'msg' => 'Approved.']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
    			return;
    		}
    	}

    	// FILE SHARING APPROVES
    	public function approves()
    	{
    		$id = $this->input->post('id');
    		if ($id == null) {
    			echo json_encode(['status' => false, 'msg' => 'Something Went Wrong.']);
    			return;
    		}
    		$amount = $this->input->post('amount');
    		if ($amount == '' || $amount < 0) {
    			$amount = 0;
    		}
    		$amts = $amount;
    		$user_id = $this->input->post('user_id');
    		$data['status'] = 1;
    		$where = ['id' => $id];
    		$data['amount'] = $amount;
    		$there = ['id' => $user_id];
    		$wlt = $this->user_model->getRow('users', $there);
    		$amount = $wlt->amount - $amount;
    		$userType = $wlt->userType;
    		$referral = $wlt->referral;
    
    		if ($amount < 0) {
    			echo json_encode(['status' => false, 'msg' => 'Do Not Have a Sufficiant Fund']);
    			return;
    		}
    		$return = $this->user_model->getRow('file_sharing', $where);
    		// echo 
    		$walletsdata = $this->user_model->getRow('wallet', ['file_sharing_id' => $id]);
    		if ($return) {
    			$whatsapp['assign_user'] = $this->session->userdata('user_id');
    			$whatsapp['amount'] = $amts;
    
    			if ($walletsdata) {
    
    				$this->user_model->updateAry('wallet', $whatsapp, ['file_sharing_id' => $walletsdata->file_sharing_id]);
    				// $this->user_model->updateAry('wallet', $whatsapp, ['file_sharing_id' => $walletsdata->file_sharing_id, 'user_id' => 1]);
    			} else {
    
    				$whatsapp['act_status'] = "DBT";
    				$whatsapp['voucherdate'] = $return->date;
    				$whatsapp['product_service'] = $return->category;
    				$whatsapp['user_id'] = $user_id;
    				$whatsapp['file_sharing_id'] = $return->id;
    				$whatsapp['vouchernumber'] = "FILES" . rand(100, 1000) . "UMATIC";
    				$this->user_model->saveAry('wallet', $whatsapp);
    			}
    		}
    
    
    
    		$return = $this->user_model->updateAry('file_sharing', $data, $where);
    		$amounts['amount'] = $amount;
    		$file_data['user_id'] = $user_id;
    		$file_data['file_id'] = $id;
    		$file_data['msg'] = "Your Filing Is Approved!";
    
    		$this->user_model->saveAry('notice_file', $file_data);
    
    		$returnssss = $this->user_model->updateAry('users', $amounts, $there);
    		$amount = $amts;
    
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
    
    
    		if ($return) {
    			// $this->session->userdata('user_id');
    			echo json_encode(['status' => true, 'data' => $return, 'msg' => 'Okay.']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
    			return;
    		}
    	}

	public function updateAmount()
	{
		// echo '<pre>';
		// print_r($_POST);
		// die;
		if (isset($_POST)) {
			$id = $this->input->post('id');
			$amount = $this->input->post('amount');
			if ($amount == '' || $amount < 0) {
				$amount = 0;
			}
			$user_id = $this->session->userdata('user_id');
			$category_id = $this->session->userdata('category_id');
			$where = ['id' => $id];
			$file_sharing_data = $this->user_model->getRow('file_sharing', $where);
			// echo '<pre>';
			// 		print_r($file_sharing_data);
			// 		die;
			if ($file_sharing_data) {
				// $previous_amount_for_file = $file_sharing_data->amount;
				$data['amount'] = $amount;
				$return = $this->user_model->updateAry('file_sharing', $data, $where);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Done', 'class' => 'success']);
					redirect('file_sharing');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
					redirect('file_sharing');
				}
			} else {
				// die;
				$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
				redirect('file_sharing');
			}
		} else {
			// $this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
			redirect('file_sharing');
		}
	}

	public function updateAmountss()
	{
		// echo '<pre>';
		// print_r($_POST);
		// die;
		if (isset($_POST)) {
			$id = $this->input->post('id');
			$amount = $this->input->post('amount');
			if ($amount == '' || $amount < 0) {
				$amount = 0;
			}
			$user_id = $this->session->userdata('user_id');
			$category_id = $this->session->userdata('category_id');
			$where = ['id' => $id];
			$file_sharing_data = $this->user_model->getRow('file_sharing', $where);
			$there = ['id' => $user_id];
			$userinfo = $this->user_model->getRow('users', $there);

			// echo '<pre>';
			// print_r($_POST);
			// die;

			if ($file_sharing_data && $userinfo) {
				$user_total['amount'] = $userinfo->amount;
				$total_amount['amount'] = $file_sharing_data->amount;
				$previous_amount_for_file = $file_sharing_data->amount;
				// $amount = $this->input->post('amount');
				if ($previous_amount_for_file > $amount) {
					$total_amounts  = $previous_amount_for_file - $amount;
					$total_amount['amount']  = $total_amounts;
					$user_total['amount'] = $userinfo->amount + $total_amounts;
				} else {
					$total_amounts  = $previous_amount_for_file - $amount;

					$total_amount['amount']  = $total_amounts;
					// $user_total = $total_amount;
					$user_total['amount'] = $userinfo->amount - $total_amounts;
				}
				$return = $this->user_model->updateAry('users', $user_total, $there);
				$return = $this->user_model->updateAry('file_sharing', $total_amount, $where);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Amount Updated', 'class' => 'success']);
					redirect('file_sharing');
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
					redirect('file_sharing');
				}
			} else {
				// die;
				$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
				redirect('file_sharing');
			}
		} else {
			// $this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
			redirect('file_sharing');
		}
	}
	public function filesharesave()
	{
		$id = $this->input->post('id');
		$user = $this->session->userdata('user_id');

		if (isset($id) && $id != '') {
			$roles = $this->session->userdata('roles');

			if ($roles == 3) {
				$where = ['userid' => $id];
				$ret = $this->user_model->getRow('subcat', $where);
				if ($ret) {
					$data['subid'] = $ret->referral_id;
				}
			} else {
				if ($roles == 2) {
					$data['subid'] = $user;
				} else {
					$where = ['userid' => $id];
					$ret = $this->user_model->getRow('subcat', $where);
					if ($ret) {
						$data['subid'] = $ret->referral_id;
					}
				}
			}
			// print_r($user);
			// die;

			if (isset($_POST)) {
				$file_name = $_FILES['image']['name'];
				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/file_share/' . $file_name);
				$data['des'] = $this->input->post('name');
				$data['user_id'] = $this->input->post('id');

				$data['file_name'] = $file_name;
				$data['date'] = $this->input->post('date');
				$data['role'] = $this->session->userdata('roles');
				$ret = $this->user_model->saveAry('wallet', $data);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
					redirect('wallet');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('wallet');
				}
			} else {
				$data['page'] = 'admin_details/application';
				$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
				redirect('wallet');
			}
		} else {
			$data['page'] = 'admin_details/application';
			$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
			redirect('wallet');
		}
	}

	public function fileshareemployye()
	{
		$id = $this->input->post('id');
		$user = $this->session->userdata('user_id');
		$referral = $this->session->userdata('user_id');


		if (isset($id) && $id != '') {

			$where = ['userid' => $id];
			$ret = $this->user_model->getRow('subcat', $where);
			if ($ret) {
				$data['emp_id'] = $ret->referral_id;
				$where = ['usertype' => 2, 'referral' => $referral];
				$subadmin_id = $this->user_model->getRow('users', $where);
				$data['subid'] = $subadmin_id->id;
			}


			// print_r($user);
			// die;

			if (isset($_POST)) {
				$file_name = $_FILES['image']['name'];
				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/file_share/' . $file_name);
				$data['des'] = $this->input->post('name');
				$data['user_id'] = $this->input->post('id');

				$data['file_name'] = $file_name;
				$data['date'] = $this->input->post('date');
				$data['usertype'] = 4;

				$data['role'] = $this->session->userdata('roles');
				$ret = $this->user_model->saveAry('wallet', $data);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
					redirect('wallet');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('wallet');
				}
			} else {
				$data['page'] = 'admin_details/application';
				$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
				redirect('wallet');
			}
		} else {
			$data['page'] = 'admin_details/application';
			$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
			redirect('wallet');
		}
	}

	public function edit($id = null)
	{
		if (isset($id) && !empty($id)) {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('wallet', $where);
			if ($return) {
				$data['result'] = $return;
				$data['page'] = 'admin_details/wallet/edit';
				$this->load->view('admin', $data);
			} else {
				// $data['page'] = 'admin_details/parties/parties_cat';
				// $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('wallet');
			}
		} else {
			redirect('wallet');
		}
	}

	public function updatewallet()
	{
		$id = $this->input->post('id');

		if (isset($_POST) && !empty($id)) {
			$where = ['id' => $id, 'status' => 0];
			$data['amount'] = $this->input->post('amount');
			$data['vouchernumber'] = $this->input->post('vouchernumber');
			$data['cheque'] = $this->input->post('cheque');
			// $data['bank_account'] = $this->input->post('bank_account');
			$data['voucherdate'] = $this->input->post('voucherdate');
			$data['created_at'] = date('Y-m-d');
			// $data['paymenttype'] = $this->input->post('paymenttype');
			$data['product_service'] = $this->input->post('product_service');
			$data['cheqedate'] = $this->input->post('cheqedate');
			// $data['note'] = $this->input->post('name');
			// $data['bank_account'] = $this->input->post('bank_account');
			// $data['amount'] = $this->input->post('amount');
			$data['note'] = $this->input->post('name');
			$data['bank_account'] = $this->input->post('bank_account');
			if ($_FILES['image']['name'] != '') {
				$file_name = $_FILES['image']['name'];
				$file_size = $_FILES['image']['size'];
				$file_tmp = $_FILES['image']['tmp_name'];
				$file_type = $_FILES['image']['type'];
				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/amount/' . $file_name);

				// move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/application/' . $file_name);
				$data['image'] = $file_name;
				// $data['app_name'] = $this->input->post('app_name');
				$ret = $this->user_model->updateAry('wallet', $data, $where);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
					redirect('wallet');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('wallet');
				}
			} else {
				// $data['app_name'] = $this->input->post('app_name');
				// $data['amount'] = $this->input->post('amount');
				// $data['note'] = $this->input->post('name');
				// $data['bank_account'] = $this->input->post('bank_account');
				// $data['transactionID'] = 'Tran' . rand(5, 1000) . 'ID';
				// $data['tranDate'] = date("d/m/Y");
				// $data['status'] = 0;
				// $data['user_id'] = $this->session->userdata('user_id');
				// $file_name = $_FILES['image']['name'];
				$ret = $this->user_model->updateAry('wallet', $data, $where);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
					redirect('wallet');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('wallet');
				}
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('wallet');
		}
	}

	public function viewdetails($id = null)
	{
		if (isset($id) && !empty($id)) {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('wallet', $where);
			if ($return) {
				$data['result'] = $return;
				$data['page'] = 'admin_details/wallet/view_details';
				$this->load->view('admin', $data);
			} else {
				// $data['page'] = 'admin_details/parties/parties_cat';
				// $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('wallet');
			}
		} else {
			redirect('wallet');
		}
	}

	public function declined($id = null)
	{
		if (isset($id) && !empty($id)) {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('wallet', $where);

			if ($return) {

				$unknown['status'] = 0;
				$this->user_model->updateAry('wallet', $unknown, $where);
				$there = ['id' => $return->user_id];
				$amount = $return->amount;

				$wlt = $this->user_model->getRow('users', $there);
				$amount = $wlt->amount - $amount;
				$amounts['amount'] = $amount;
				$this->user_model->updateAry('users', $amounts, $there);
				// $wlts = $this->user_model->getRow('users', ['id' => 1]);
				// $amt_for_admin = $wlts->amount + $return->amount;
				// $amounts['amount'] = $amt_for_admin;
				// $this->user_model->updateAry('users', $amounts, ['id' => $this->session->userdata('user_id')]);
				// $data['page'] = 'admin_details/wallet/index';
				redirect('wallet');

				// $this->load->view('admin', $data);
			} else {
				// $data['page'] = 'admin_details/parties/parties_cat';
				// $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('wallet');
			}
		} else {
			redirect('wallet');
		}
	}

	public function reject()
	{
		// $where = ['id' => $id];
		$id = $this->input->post('id');
		$user_id = $this->input->post('user_id');
		$msg = $this->input->post('msg');

		if ($id == null || $user_id == null) {
			echo json_encode(['status' => false, 'msg' => 'Something Went Wrong.']);
			return;
		}
		// $user_id = $this->input->post('user_id');
		// status=2 for rejection
		$where = ['id' => $id, 'user_id' => $user_id];
		$return = $this->user_model->getRow('wallet', $where);

		if ($return) {
			$data['status'] = 2;
			$data['msg'] = $msg;
			// $walletsdata = $this->user_model->getRow('wallet', ['file_sharing_id' => $id]);
			$this->user_model->updateAry('wallet', $data, $where);
			echo json_encode(['status' => true, 'msg' => 'Wallet Rejected']);
			return;
		} else {
			echo json_encode(['status' => false, 'msg' => 'Data Not Found']);
			return;
		}
		// $data['status'] = 0;

	}

	public function check_location()
	{

		$post['pickup_postcode'] = 560010;
		$post['cod'] = 0;
		$shipState = $this->shiprocket->serviceability($post);
		print_r($shipState);
		die;
	}
	
// 	public function updateWalletDate()
// 	{
// 		$wallet_data = $this->user_model->getResult('wallet');
// 		if ($wallet_data) {
// 			foreach ($wallet_data as $wal) {
// 				$sates = explode(" ", $wal->voucherdate);
// 				$data['created_at'] = date('Y-m-d', strtotime($sates[0]));
// 				$this->user_model->updateAry('wallet', $data, ['id' => $wal->id]);
// 			}
// 		}
// 	}
}
