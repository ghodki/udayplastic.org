<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class File_sharing extends CI_Controller
    {
    	function __construct()
    	{
    		parent::__construct();
    		$this->load->helper('form');
    		$this->load->helper('url');
    		$this->load->model('user_model');
    		if ($this->session->userdata('roles') === null) {
    			redirect('welcome');
    		}
    	}
    
    	public function index()
    	{
    		$role_id = $this->session->userdata('roles');
    		$user_id = $this->session->userdata('user_id');
    		$referral = $this->session->userdata('referral');
    		$limit = 20;
    
    		$data['page'] = 'admin_details/file_share/main';
    		if ($role_id == 1) {
    			$data['file_sharing'] = [];
    		} else if ($role_id == 3) {
    			$where = ['user_id' => $user_id];
    			$data['file_sharing'] = [];
    		} else if ($role_id == 2) {
    			$where = ['subid' => $user_id];
    			$data['file_sharing'] = [];
    			$data['subadmin_data'] = [];
    		} else {
    			$where = ['emp_id' => $user_id];
    			$data['file_sharing'] =[];
    		}
    
    		$this->load->view('admin', $data);
    	}
    
    	public function file_sharing_scrolldown_data()
    	{
    		$response = $this->user_model->getResult('file_sharing');
    		$this->output
    			->set_content_type('application/json')
    			->set_output(json_encode($response));
    	}
    
    	public function dateFilter($start = 0)
    	{
    		$role_id = $this->session->userdata('roles');
    		$user_id = $this->session->userdata('user_id');
    		$referral = $this->session->userdata('referral');
    
    		$fromDate = '';
    		$toDate = '';
    		$limit = 20;
    		if (!empty($this->input->get('fromDate')) && !empty($this->input->get('toDate'))) {
    			$fromDate = ['date >=' => date('d-m-Y', strtotime($this->input->get('fromDate')))];
    			$toDate = ['date <=' => date('d-m-Y', strtotime($this->input->get('toDate')))];
    		} 
    		
    		if ($this->input->get('records')) {
    			$limit = $this->input->get('records');
    			if ($limit == 'all') {
    				$limit = '';
    			} elseif ($limit <= 0) {
    				$limit = 20;
    			}
    		}
    		
    		if ($role_id == 1) {
    			$data['file_sharing'] = $this->user_model->getDateWiseData('file_sharing', $fromDate, $toDate, '', $limit);
    		} else if ($role_id == 3) {
    			$where = ['user_id' => $user_id];
    			$data['file_sharing'] = $this->user_model->getDateWiseData('file_sharing', $fromDate, $toDate, $where, $limit);
    		} else if ($role_id == 2) {
    			$where = ['subid' => $user_id];
    			$data['file_sharing'] = $this->user_model->getDateWiseData('file_sharing', $fromDate, $toDate, $where, $limit);
    		} else {
    			$where = ['emp_id' => $user_id];
    			$data['file_sharing'] = $this->user_model->getDateWiseData('file_sharing', $fromDate, $toDate, $where, $limit);
    		}
    		
    		$data['page'] = 'admin_details/file_share/main';
    
    		$this->load->view('admin', $data);
    	}
    	public function delete($id)
    	{
    		$where = ['id' => $id];
    
    		$return = $this->user_model->deleteRow('file_sharing', $where);
    		if ($return) {
    
    			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
    			redirect('file_sharing');
    		} else {
    			$data['page'] = 'admin_details/userlist';
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			redirect('file_sharing');
    		}
    	}
    
    	public function del($id)
    	{
    	
    		// $data['assign_user'] = $this->session->userdata('user_id');
    
    		if ($id == null) {
    			$this->session->set_flashdata(['msg' => 'Record Not Found.', 'class' => 'danger']);
    			redirect('file_sharing');
    		}
    		// $user_id = $this->input->post('user_id');
    		$file_Sharing_id = $this->user_model->getRow('file_share_chat', ['id' => $id]);
    
    		$where = ['id' => $file_Sharing_id->file_sharing_id, 'status' => 1];
    		$return = $this->user_model->getRow('file_sharing', $where);
    
    		if ($return) {
    			$amount = $return->amount;
    			$file_amount['amount'] = 0;
    			$file_amount['status'] = 0;
    
    			$there = ['id' => $return->user_id];
    			$user_indo = $this->user_model->getRow('users', $there);
    
    			$userwallet = $user_indo->amount;
    			$userType = $user_indo->userType;
    			$referral = $user_indo->referral;
    
    			$usaer['amount'] = $userwallet + $amount;
    			$this->user_model->updateAry('users', $usaer, $there);
    			$this->user_model->updateAry('file_sharing', $file_amount, $where);
    
    			$this->user_model->deleteRow('notice_file', ['file_id' => $id]);
    
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
    			// $walletsdata = $this->user_model->getRow('wallet', ['file_sharing_id' => $id]);
    			$this->user_model->deleteRow('wallet', ['file_sharing_id' => $id]);
    
    			$where = ['id' => $id];
    			$return = $this->user_model->deleteRow('file_share_chat', $where);
    			if ($return) {
    				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
    				redirect('file_sharing');
    			} else {
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('file_sharing');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			redirect('file_sharing');
    		}
    	}
    
    	public function readFile()
    	{
    		$user_id = $this->session->userdata('user_id');
    		if ($user_id) {
    			// $data = ['watch' => 'yes'];
    			$data = array(
    				'watch' => 'yes',
    			);
    			$condition = ['watch' => 'no', 'user_id' => $user_id];
    			// $return = $this->user_model->getAryss('notice_file','','','',$condition);
    			$notwatch = $this->user_model->getAry('notice_file', '', '', '', $condition);
    
    			if ($notwatch) {
    				foreach ($notwatch as $watch) {
    					$return = $this->user_model->updateAry('notice_file', $data, $condition);
    				}
    			}
    			if ($user_id) {
    				// if ($return) {
    				echo json_encode(['status' => true, 'data' => $user_id, 'msg' => 'File Deleted']);
    				return;
    			} else {
    				echo json_encode(['status' => false, 'msg' => 'Your file is approved or another reason']);
    				return;
    			}
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'Your file is approved or another reason']);
    			return;
    		}
    	}
    
    	public function deletes()
    	{
    		// $where = ['id' => $id];
    		$id = $this->input->post('id');
    		// $data['assign_user'] = $this->session->userdata('user_id');
    
    		if ($id == null) {
    			echo json_encode(['status' => false, 'msg' => 'Something Went Wrong.']);
    			return;
    		}
    
    		$user_id = $this->input->post('user_id');
    		$data['status'] = 1;
    		$where = ['id' => $id, 'status' => 0, 'user_id' => $user_id];
    		$return = $this->user_model->deleteRow('file_sharing', $where);
    		if ($return) {
    			// if ($return) {
    			echo json_encode(['status' => true, 'data' => $return, 'msg' => 'File Deleted']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'Your file is approved or another reason']);
    			return;
    		}
    	}
    
    	public function chat($file_id = null)
    	{
    		if ($file_id == null) {
    			$this->session->set_flashdata(['msg' => 'Proper Data not Find', 'class' => 'danger']);
    			redirect('file_sharing');
    		}
    		$there = ['id' => $file_id];
    		$data['file_sharing'] = $this->user_model->getRow('file_sharing', $there);
    
    		$where = ['file_sharing_id' => $file_id];
    		$order = ['id' => 'DESC'];
    		$data['chat'] = $this->user_model->getAryss('file_share_chat', $order, '', '', $where);
    		$data['page'] = 'admin_details/file_share/chat';
    		// $this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    		$this->load->view('admin', $data);
    
    		// print_r($_GET);
    		// die;
    	}
    
    	public function deleteChatFile($id)
    	{
    		$where = ['id' => $id];
    
    		$return = $this->user_model->deleteRow('file_share_chat', $where);
    		if ($return) {
    
    			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
    			redirect('file_sharing');
    		} else {
    			$data['page'] = 'admin_details/userlist';
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			redirect('file_sharing');
    		}
    	}
    
    	public function decline()
    	{
    		// $where = ['id' => $id];
    		$id = $this->input->post('id');
    		// $data['assign_user'] = $this->session->userdata('user_id');
    
    		if ($id == null) {
    			echo json_encode(['status' => false, 'msg' => 'Something Went Wrong.']);
    			return;
    		}
    		$user_id = $this->input->post('user_id');
    		$where = ['id' => $id, 'status' => 1];
    		$return = $this->user_model->getRow('file_sharing', $where);
    
    		if ($return) {
    			$amount = $return->amount;
    			$file_amount['amount'] = 0;
    			$file_amount['status'] = 0;
    
    			$there = ['id' => $return->user_id];
    			$user_indo = $this->user_model->getRow('users', $there);
    
    			$userwallet = $user_indo->amount;
    			$userType = $user_indo->userType;
    			$referral = $user_indo->referral;
    
    			$usaer['amount'] = $userwallet + $amount;
    			$this->user_model->updateAry('users', $usaer, $there);
    			$this->user_model->updateAry('file_sharing', $file_amount, $where);
    
    			$this->user_model->deleteRow('notice_file', ['file_id' => $id]);
    
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
    			// $walletsdata = $this->user_model->getRow('wallet', ['file_sharing_id' => $id]);
    			$this->user_model->deleteRow('wallet', ['file_sharing_id' => $id]);
    			echo json_encode(['status' => true, 'msg' => 'Updated Action']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'Data Not Found']);
    			return;
    		}
    		// $data['status'] = 0;
    	}
    	// 	public function filesharesave()
    	// 	{
    	// 		$id = $this->input->post('id');
    	// 		$user = $this->session->userdata('user_id');
    
    	// 		if (isset($id) && $id != '') {
    	// 			$roles = $this->session->userdata('roles');
    
    	// 			// roles 3 means=>"User"
    	// 			// roles 1 means=>"Admin"
    	// 			// roles 2 means=>"Subadmin"
    	// 			// roles 4 means=>"employee"
    
    
    	// 			if ($roles == 3) {
    	// 				$where = ['userid' => $id];
    	// 				$ret = $this->user_model->getRow('subcat', $where);
    	// 				if ($ret) {
    	// 					$data['subid'] = $ret->referral_id;
    	// 					$data['emp_id'] = $ret->emp_id;
    	// 				}
    	// 			} else if ($roles == 2) {
    	// 				$data['subid'] = $user;
    	// 				$where = ['userid' => $id];
    	// 				$ret = $this->user_model->getRow('subcat', $where);
    	// 				if ($ret) {
    	// 					$data['emp_id'] = $ret->emp_id;
    	// 					$data['subid'] = $ret->referral_id;
    	// 				} else {
    	// 					$data['subid'] = $ret->referral_id;
    	// 				}
    	// 			} else if ($roles == 4) {
    	// 				$where = ['userid' => $id];
    	// 				$ret = $this->user_model->getRow('subcat', $where);
    	// 				if ($ret) {
    	// 					$data['subid'] = $ret->referral_id;
    	// 					$data['emp_id'] = $ret->emp_id;
    	// 				}
    	// 			} else {
    	// 				$data['subid'] = '';
    	// 				$data['emp_id'] = '';
    	// 			}
    
    
    	// 			// print_r($user);
    	// 			// die;
    
    	// 			if (isset($_POST)) {
    	// 				$file_name = $_FILES['image']['name'];
    	// 				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/file_share/' . $file_name);
    	// 				$data['des'] = $this->input->post('name');
    	// 				$data['user_id'] = $this->input->post('id');
    	// 				$data['category'] = $this->input->post('category');
    
    
    	// 				$data['file_name'] = $file_name;
    	// 				// $data['date'] = $this->input->post('date');
    	// 				date_default_timezone_set('Asia/Kolkata');
    	// 				$data['date'] = date('d-m-Y H:ia');
    	// 				$data['role'] = $this->session->userdata('roles');
    	// 				$ret = $this->user_model->saveAry('file_sharing', $data);
    	// 				if ($ret) {
    	// 					$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
    	// 					redirect('file_sharing');
    	// 				} else {
    	// 					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    	// 					redirect('file_sharing');
    	// 				}
    	// 			} else {
    	// 				$data['page'] = 'admin_details/application';
    	// 				$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    	// 				redirect('file_sharing');
    	// 			}
    	// 		} else {
    	// 			$data['page'] = 'admin_details/application';
    	// 			$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    	// 			redirect('file_sharing');
    	// 		}
    	// 	}
    
    		public function savefile_in_chatting_for_user()
    		{
    			$id = $this->input->post('file_id');
    			$user_id = $this->session->userdata('user_id');
    
    			// echo '<pre>';
    			// print_r($_POST);
    			// die;
    			// $field_values_array = ['name'];
    			// echo '<pre>';
    			$field_values_array = $_FILES['field_name']['name'];
    			// echo '<pre>';
    			// print_r($field_values_array);
    			// die;
    			$logo = [];
    			$files_data = [];
    			$file_name = '';
    			if (isset($_POST)) {
    				if ($_FILES['field_name'] != '') {
    					$count = count($_FILES['field_name']['name']);
    
    					for ($i = 0; $count > $i; $i++) {
    						$file_name = $_FILES['field_name']['name'][$i];
    						move_uploaded_file($_FILES['field_name']['tmp_name'][$i], 'uploads/filechat/' . $file_name);
    						$logo['file_name'] = $file_name;
    						$files_data[] = $logo;
    					}
    				}
    				if ($files_data) {
    					$data['file_name'] = json_encode($files_data);
    				}
    
    				$data['msg'] = $this->input->post('name');
    				$data['qty'] = $this->input->post('qty');
    				$data['user_id'] = $user_id;
    				$data['file_sharing_id'] = $id;
    				date_default_timezone_set('Asia/Kolkata');
    				$data['date'] = date('d-m-Y H:ia');
    				$ret = $this->user_model->saveAry('file_share_chat', $data);
    				if ($ret) {
    					$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
    					redirect('file_sharing/chat/' . $id);
    				} else {
    					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    					// redirect('file_sharing');
    					redirect('file_sharing/chat/' . $id);
    				}
    			} else {
    				// $data['page'] = 'admin_details/application';
    				$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    				// redirect('file_sharing');
    				redirect('file_sharing/chat/' . $id);
    			}
    		}
    
    	public function filesharesave()
    	{
    		$id = $this->input->post('id');
    		$user_id = $this->session->userdata('user_id');
    		// echo '<pre>';
    		// print_r($_POST);
    		// die;
    		if (isset($id) && $id != '') {
    			$roles = $this->session->userdata('roles');
    
    			// roles 3 means=>"User"
    			// roles 1 means=>"Admin"
    			// roles 2 means=>"Subadmin"
    			// roles 4 means=>"employee"
    
    
    			if ($roles == 3) {
    				$where = ['userid' => $id];
    				$ret = $this->user_model->getRow('subcat', $where);
    				if ($ret) {
    					$data['subid'] = $ret->referral_id;
    					$data['emp_id'] = $ret->emp_id;
    				}
    			} else if ($roles == 2) {
    				$data['subid'] = '';
    				$data['emp_id'] = '';
    			} else if ($roles == 4) {
    				$where = ['userid' => $id];
    				$ret = $this->user_model->getRow('subcat', $where);
    				if ($ret) {
    					$data['subid'] = $ret->referral_id;
    					// $data['emp_id'] = $ret->emp_id;
    				}
    			} else {
    				$data['subid'] = '';
    				$data['emp_id'] = '';
    			}
    
    
    			// print_r($user);
    			// die;
    			$files_data = [];
    
    			if (isset($_POST)) {
    
    				if ($_FILES['image'] != '') {
    					$count = count($_FILES['image']['name']);
    					for ($i = 0; $count > $i; $i++) {
    						$tme = date("Ymdhis");
    						$file_name = $_FILES['image']['name'][$i];
    						move_uploaded_file($_FILES['image']['tmp_name'][$i], 'uploads/file_share/' . $tme . $i . $file_name);
    						$logo['file_name'] = $tme . $i . $file_name;
    						$files_data[] = $logo;
    					}
    				}
    				if ($files_data) {
    					$uamna = json_encode($files_data);
    
    					$data['file_name'] = json_encode($files_data);
    				}
    
    				// $file_name = $_FILES['image']['name'];
    				// move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/file_share/' . $file_name);
    				$data['des'] = $this->input->post('name');
    				$data['user_id'] = $user_id;
    				$data['category'] = $this->input->post('category');
    				// $data['file_name'] = $file_name;
    				// $data['date'] = $this->input->post('date');
    				date_default_timezone_set('Asia/Kolkata');
    				$data['date'] = date('d-m-Y H:ia');
    				$data['role'] = $this->session->userdata('roles');
    				$date['create_date'] = date('Y-m-d');
    				$ret = $this->user_model->saveAry('file_sharing', $data);
    				if ($ret) {
    					$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
    					redirect('file_sharing');
    				} else {
    					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    					redirect('file_sharing');
    				}
    			} else {
    				$data['page'] = 'admin_details/application';
    				$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    				redirect('file_sharing');
    			}
    		} else {
    			$data['page'] = 'admin_details/application';
    			$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    			redirect('file_sharing');
    		}
    	}
    	
		public function savefile_in_chatting()
    	{
    		// $file_id = $this->input->post('file_id');
    		$id = $this->input->post('file_id');
    		if ($id == null) {
    			$this->session->set_flashdata(['msg' => 'Please Provide Files Id', 'class' => 'danger']);
    			// redirect('file_sharing');
    			redirect('file_sharing/chat/'.$id);
    		}
    		$amount = $this->input->post('amount');
    		if ($amount == '' || $amount < 0) {
    			$amount = 0;
    		}
    		$amts = $amount;
    		$user_id = $this->input->post('user_id');
    		$data['status'] = 1;
    		$where = ['id' => $id,'status'=>0];
    		$data['amount'] = $amount;
    		$there = ['id' => $user_id];
    		$wlt = $this->user_model->getRow('users', $there);
    		$amount = $wlt->amount - $amount;
    		$userType = $wlt->userType;
    		$referral = $wlt->referral;
    
    // 		if ($amount < 0) {
    // 			$this->session->set_flashdata(['msg' => 'Do Not Have a Sufficiant Fund.', 'class' => 'danger']);
    // 			// redirect('file_sharing');
    // 			redirect('file_sharing/chat/' . $id);
    // 		}
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
    								$whatsapp['created_at'] = date('Y-m-d');
    
    				$whatsapp['user_id'] = $user_id;
    				$whatsapp['file_sharing_id'] = $return->id;
    				$whatsapp['vouchernumber'] = "FILES" . rand(10000, 99999) . "UMATIC";
    				$this->user_model->saveAry('wallet', $whatsapp);
    			}
    		
    
    
    
    		$this->user_model->updateAry('file_sharing', $data, $where);
    		$amounts['amount'] = $amount;
    		$file_data['user_id'] = get_title('file_sharing', ['id' => $id], 'user_id');
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
    }
    
    		$id = $this->input->post('file_id');
    		$user_id = $this->session->userdata('user_id');
    		$field_values_array = $_FILES['field_name']['name'];
    		$logo = [];
    		$files_data = [];
    		$file_name = '';
    	
    			if ($_FILES['field_name'] != '') {
    				$count = count($_FILES['field_name']['name']);
    				for ($i = 0; $count > $i; $i++) {
    					// $tme = date("Ymdhis");
    					$file_name = $_FILES['field_name']['name'][$i];
    				if ($file_name) {
    					// $tme = date("Ymdhis");
    					move_uploaded_file($_FILES['field_name']['tmp_name'][$i], 'uploads/filechat/' . $file_name);
    					$logo['file_name'] = $file_name;
    					$files_data[] = $logo;
    				}
    				}
    			}
    			if ($files_data) {
    				$chewaladata['file_name'] = json_encode($files_data);
    			}
    
    			$chewaladata['msg'] = $this->input->post('name');
    			$chewaladata['user_id'] = $user_id;
    			$chewaladata['file_sharing_id'] = $id;
    			date_default_timezone_set('Asia/Kolkata');
    			$chewaladata['date'] = date('d-m-Y H:ia');
    			$ret = $this->user_model->saveAry('file_share_chat', $chewaladata);
    			if ($ret) {
    				$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
    				redirect('file_sharing/chat/' . $id);
    			} else {
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				// redirect('file_sharing');
    				redirect('file_sharing/chat/' . $id);
    			}
    		}
    	
    
    // 	public function savefile_in_chatting()
    // 	{
    // 		$id = $this->input->post('file_id');
    // 		$user_id = $this->session->userdata('user_id');
    // 		$field_values_array = $_FILES['field_name']['name'];
    // 		$logo = [];
    // 		$files_data = [];
    // 		$file_name = '';
    // 		if (isset($_POST)) {
    // 			if ($_FILES['field_name'] != '') {
    // 				$count = count($_FILES['field_name']['name']);
    // 				for ($i = 0; $count > $i; $i++) {
    // 					// $tme = date("Ymdhis");
    // 					$file_name = $_FILES['field_name']['name'][$i];
    // 					// $tme = date("Ymdhis");
    // 					move_uploaded_file($_FILES['field_name']['tmp_name'][$i], 'uploads/filechat/' . $file_name);
    // 					$logo['file_name'] = $file_name;
    // 					$files_data[] = $logo;
    // 				}
    // 			}
    // 			if ($files_data) {
    // 				$data['file_name'] = json_encode($files_data);
    // 			}
    
    // 			$data['msg'] = $this->input->post('name');
    // 			$data['user_id'] = $user_id;
    // 			$data['file_sharing_id'] = $id;
    // 			date_default_timezone_set('Asia/Kolkata');
    // 			$data['date'] = date('d-m-Y H:ia');
    // 			$ret = $this->user_model->saveAry('file_share_chat', $data);
    // 			if ($ret) {
    // 				$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
    // 				redirect('file_sharing/chat/' . $id);
    // 			} else {
    // 				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    // 				// redirect('file_sharing');
    // 				redirect('file_sharing/chat/' . $id);
    // 			}
    // 		} else {
    // 			// $data['page'] = 'admin_details/application';
    // 			$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    // 			// redirect('file_sharing');
    // 			redirect('file_sharing/chat/' . $id);
    // 		}
    // 	}
    
    
    	public function updateFileChat()
    	{
    		// $file_id = $this->input->post('file_id');
    		$id = $this->input->post('id');
    		$file_sharing_id = $this->input->post('file_sharing_id');
    
    		if ($id == null) {
    			$this->session->set_flashdata(['msg' => 'Please Provide Files Id', 'class' => 'danger']);
    			// redirect('file_sharing');
    			redirect('file_sharing');
    		}
    
    		// echo '<pre>';
    		// print_r($_POST);
    		// print_r($_FILES);
    		// die;
    
    
    		$field_values_array = $_FILES['field_name']['name'];
    		$logo = [];
    		$files_data = [];
    		$file_name = '';
    		if ($field_values_array) {
    			$count = count($_FILES['field_name']['name']);
    			for ($i = 0; $count > $i; $i++) {
    				// $tme = date("Ymdhis");
    				$file_name = $_FILES['field_name']['name'][$i];
    			if ($file_name) {
    					// $tme = date("Ymdhis");
    					move_uploaded_file($_FILES['field_name']['tmp_name'][$i], 'uploads/filechat/' . $file_name);
    					$logo['file_name'] = $file_name;
    					$files_data[] = $logo;
    				}
    			}
    		}
    		if ($files_data) {
    			$chewaladata['file_name'] = json_encode($files_data);
    		}
    
    		$chewaladata['msg'] = $this->input->post('name');
    		$chewaladata['qty'] = $this->input->post('qty');
    
    		$ret = $this->user_model->updateAry('file_share_chat', $chewaladata, ['id' => $id]);
    		if ($ret) {
    			$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
    			redirect('file_sharing/chat/' . $file_sharing_id);
    		} else {
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			// redirect('file_sharing');
    			redirect('file_sharing/chat/' . $file_sharing_id);
    		}
    	}
    
    
    	public function editFileReply($id = null)
    	{
    		if (isset($id) && !empty($id)) {
    
    			$where = ['id' => $id];
    			$return = $this->user_model->getRow('file_share_chat', $where);
    			if ($return) {
    				$data['result'] = $return;
    				$data['page'] = 'admin_details/file_share/editFileReply';
    				$this->load->view('admin', $data);
    			} else {
    				$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
    				redirect('file_sharing/chat_edit/' . $id);
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
    			redirect('file_sharing/chat_edit/' . $id);
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
    				$data['emp_id'] = $ret->emp_id;
    
    				$data['subid'] = $ret->id;
    			}
    
    
    			// print_r($user);
    			// die;
    
    			if (isset($_POST)) {
    				$file_name = $_FILES['image']['name'];
    				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/file_share/' . $file_name);
    				$data['des'] = $this->input->post('name');
    				$data['user_id'] = $this->input->post('id');
    				$data['category'] = $this->input->post('category');
    
    				$data['file_name'] = $file_name;
    				// $data['date'] = $this->input->post('date');
    				date_default_timezone_set('Asia/Kolkata');
    				$data['date'] = date('d-m-Y H:ia');
    				// $data['usertype'] = 4;
    
    				$data['role'] = $this->session->userdata('roles');
    				$ret = $this->user_model->saveAry('file_sharing', $data);
    				if ($ret) {
    					$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
    					redirect('file_sharing');
    				} else {
    					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    					redirect('file_sharing');
    				}
    			} else {
    				$data['page'] = 'admin_details/application';
    				$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    				redirect('file_sharing');
    			}
    		} else {
    			$data['page'] = 'admin_details/application';
    			$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
    			redirect('file_sharing');
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
    	
    		$where = ['id' => $id, 'user_id' => $user_id];
    		$return = $this->user_model->getRow('file_sharing', $where);
    		if ($return) {
    			$data['status'] = 2;
    			$data['msg'] = $msg;
    			// $walletsdata = $this->user_model->getRow('wallet', ['file_sharing_id' => $id]);
    			$this->user_model->updateAry('file_sharing', $data, $where);
    			echo json_encode(['status' => true, 'msg' => 'Updated Action']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'Data Not Found']);
    			return;
    		}
    		// $data['status'] = 0;
    
    	}
    
    
    	public function filled()
    	{
    		$role_id = $this->session->userdata('roles');
    		$user_id = $this->session->userdata('user_id');
    		$referral = $this->session->userdata('referral');
    		$limit = 50;
    
    		$data['page'] = 'admin_details/file_share/filled';
    		if ($role_id == 1) {
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, ['status' => 1]);
    		} else if ($role_id == 3) {
    			$where = ['user_id' => $user_id, 'status' => 1];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    		} else if ($role_id == 2) {
    			$where = ['subid' => $user_id, 'status' => 1];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    			$data['subadmin_data'] = $this->user_model->getAryss('file_sharing', '', '', $limit, ['user_id' => $user_id]);
    		} else {
    			$where = ['emp_id' => $user_id, 'status' => 1];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    		}
    
    		$this->load->view('admin', $data);
    	}
    
    	public function nonfilled()
    	{
    		$role_id = $this->session->userdata('roles');
    		$user_id = $this->session->userdata('user_id');
    		$referral = $this->session->userdata('referral');
    		$limit = '';
    
    		$data['page'] = 'admin_details/file_share/nonfilled';
    		if ($role_id == 1) {
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, ['status' => 0]);
    		} else if ($role_id == 3) {
    			$where = ['user_id' => $user_id, 'status' => 0];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    		} else if ($role_id == 2) {
    			$where = ['subid' => $user_id, 'status' => 0];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    			$data['subadmin_data'] = $this->user_model->getAryss('file_sharing', '', '', $limit, ['user_id' => $user_id]);
    		} else {
    			$where = ['emp_id' => $user_id, 'status' => 0];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    		}
    
    		$this->load->view('admin', $data);
    	}
    
    	public function rejectfile()
    	{
    		$role_id = $this->session->userdata('roles');
    		$user_id = $this->session->userdata('user_id');
    		$referral = $this->session->userdata('referral');
    		$limit = '';
    
    		$data['page'] = 'admin_details/file_share/reject';
    		if ($role_id == 1) {
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, ['status' => 2]);
    		} else if ($role_id == 3) {
    			$where = ['user_id' => $user_id, 'status' => 2];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    		} else if ($role_id == 2) {
    			$where = ['subid' => $user_id, 'status' => 2];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    			$data['subadmin_data'] = $this->user_model->getAryss('file_sharing', '', '', $limit, ['user_id' => $user_id]);
    		} else {
    			$where = ['emp_id' => $user_id, 'status' => 2];
    			$data['file_sharing'] = $this->user_model->getAryss('file_sharing', '', '', $limit, $where);
    		}
    
    		$this->load->view('admin', $data);
    	}
    	
        public function report ()
        {
            if ($_POST) {
                $from = $this->input->post('from');
                $to = $this->input->post('to');
                $status = $this->input->post('status'); 
                if ($status == "All" || $status == "") {
                    if ($this->session->userdata('roles') == 1) {
                        $where = ['create_date >=' => $from, 'create_date <=' => $to];
                    } elseif ($this->session->userdata('roles') == 2) {
                        $where = ['create_date >=' => $from, 'create_date <=' => $to, 'subid' => $this->session->userdata('user_id')];
                    }
                } else {
                    if ($this->session->userdata('roles') == 1) {
                        $where = ['create_date >=' => $from, 'create_date <=' => $to, 'status' => $status];
                    } elseif ($this->session->userdata('roles') == 2) {
                        $where = ['create_date >=' => $from, 'create_date <=' => $to, 'status' => $status, 'subid' => $this->session->userdata('user_id')];
                    }
                }
                $data['filedata'] = $this->user_model->getResult('file_sharing', $where);
            } else {
                if ($this->session->userdata('roles') == 1) {
                    $where = ['create_date >=' => date('Y-m-01'), 'create_date <=' => date('Y-m-d')];
                } elseif ($this->session->userdata('roles') == 2) {
                    $where = ['create_date >=' => date('Y-m-01'), 'create_date <=' => date('Y-m-d'), 'subid' => $this->session->userdata('user_id')];
                }
                $data['filedata'] = $this->user_model->getResult('file_sharing', $where);
            } 

            if ($this->session->userdata('roles') == 1) {
                $data['total_request'] = $this->user_model->getResult('file_sharing');
                $data['approved_request'] = $this->user_model->getResult('file_sharing', ['status' => 1]);
                $data['panding_request'] = $this->user_model->getResult('file_sharing', ['status' => 0]);
                $data['reject_request'] = $this->user_model->getResult('file_sharing', ['status' => 2]);
                $this->db->select('SUM(amount) AS total');
                $this->db->where('status', 1);
                $total_result = $this->db->get('file_sharing')->row();
                $data['totalamount_approved_request'] = $total_result->total;
            } elseif ($this->session->userdata('roles') == 2) {
                $data['total_request'] = $this->user_model->getResult('file_sharing', ['subid' => $this->session->userdata('user_id')]);
                $data['approved_request'] = $this->user_model->getResult('file_sharing', ['status' => 1, 'subid' => $this->session->userdata('user_id')]);
                $data['panding_request'] = $this->user_model->getResult('file_sharing', ['status' => 0, 'subid' => $this->session->userdata('user_id')]);
                $data['reject_request'] = $this->user_model->getResult('file_sharing', ['status' => 2, 'subid' => $this->session->userdata('user_id')]);
                $this->db->select('SUM(amount) AS total');
                $this->db->where('status', 1);
                $this->db->where('subid', $this->session->userdata('user_id'));
                $total_result = $this->db->get('file_sharing')->row();
                $data['totalamount_approved_request'] = $total_result->total;
            }
            
            $data['page'] = 'admin_details/file_share/report';
            $this->load->view('admin', $data);
        }
    }