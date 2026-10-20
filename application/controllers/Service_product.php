<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Service_product extends CI_Controller
    {
    	function __construct()
    	{
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);
    
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
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
                    'field' => 'des',
                    'label' => 'des',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'price',
                    'label' => 'price',
                    'rules' => 'trim|required'
                ),
            );
            return $rules;
    	}
    
    	public function index($start = 0)
    	{
            $order = ['id' => 'DESC'];
            $role_id = $this->session->userdata('roles');
            $user_id = $this->session->userdata('user_id');

            if ($role_id == 1) {
                $data['product_service'] = $this->user_model->getResult('product_service_apply');
            } elseif ($role_id == 2) {
                $where = ['user_type' => 2, 'user_id' => $this->session->userdata('user_id')];
                $userlist = $this->user_model->getAryss('product_service_apply', '', '', '', $where);
                $data['product_service'] = $userlist;
    		
                // SUBADMIN  SERVICES REQUESTS
                $userlists = [];
                $where = ['referral_id' => $this->session->userdata('user_id')];
                $subadmin = $this->user_model->getAryss('subcat', '', '', '', $where);
                $data['difficult_getting_data'] = '';
                foreach ($subadmin as $emp) {
                    $user_id = $emp->userid;
                    $where = ['user_id' => $user_id];
                    $userlists = $this->user_model->getAry('product_service_apply', '', '', '', $where);
                    if ($userlists) {
                        foreach ($userlists as $key => $value) {
                            $subadmin_data['subadmin_id'] = $this->session->userdata('user_id');
                            $this->user_model->updateAry('product_service_apply', $subadmin_data, ['id' => $value->id]);
                        }
                        $data['difficult_getting_data'] = $userlists = $this->user_model->getAryss('product_service_apply', '', '', '', ['subadmin_id' => $this->session->userdata('user_id')]);
                    }
                }
            } elseif ($role_id == 3) {
                $product_service = [];
                $where = ['user_id' => $this->session->userdata('user_id')];
                $product_service = $this->user_model->getAryss('product_service_apply', '', '', '', $where);
                $data['product_service'] = $product_service;
            } else {
                $userlist = [];
                $data['difficult_getting_data'] = '';
                $where = ['user_id' => $this->session->userdata('user_id')];
                $product_service = $this->user_model->getAryss('product_service_apply', '', '', '', $where);
                $data['product_service'] = $product_service;
                $where = ['emp_id' => $this->session->userdata('user_id'), 'usertype' => 4];
                $employye = $this->user_model->getAryss('subcat', $order, '', '', $where);
                foreach ($employye as $emp) {
                    $user_id = $emp->userid;
                    $where = ['user_id' => $user_id];
                    $userlists = $this->user_model->getAry('product_service_apply', '', '', '', $where);
                    if ($userlists) {
                        foreach ($userlists as $key => $value) {
                            $subadmin_data['emp_id'] = $this->session->userdata('user_id');
                            $this->user_model->updateAry('product_service_apply', $subadmin_data, ['id' => $value->id]);
                        }
                    }
                }
                $data['difficult_getting_data'] = $userlists = $this->user_model->getAryss('product_service_apply', '', '', '', ['emp_id' => $this->session->userdata('user_id')]);
            }
       
            $data['page'] = 'admin_details/service_product/index';
            return $this->load->view('admin', $data);
    	}
    
    	public function getPrice()
    	{
    		$id = $this->input->post('service_id');
    		if ($id != null) {
    			$data = $this->user_model->getRow('product_service', ['id' => $id]);
    			if (isset($data) && !empty($data)) {
    				echo json_encode(['status' => true, 'data' => $data]);
    			} else {
    				echo json_encode(['status' => false, 'data' => 'Data not found.']);
    			}
    		} else {
    			echo json_encode(['status' => false, 'data' => 'Data not found.']);
    		}
    	}
    	public function getproduct_service_services()
    	{
    		$product_service = $this->db->select('id, p_name')->order_by('p_name', 'ASC')->get_where('product_service', ['status' => 1])->result();
    		if (isset($product_service) && !empty($product_service)) {
    			echo json_encode(['status' => true, 'data' => $product_service]);
    		} else {
    			echo json_encode(['status' => true, 'data' => 'Data not found.']);
    		}
    	}
    	public function getProduct_service($id)
    	{
    		if (!isset($id) && empty($id)) {
    			echo json_encode(['status' => false, 'data' => 'Id required.']);
    		} else {
    			$user_id = $this->session->user_id;
    			$comp_id = $this->session->comp_id;
    			$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'id' => $id];
    			// $product_service = $this->db->select('*')->get_where('product_service', $where)->row();
    			$product_service =	$this->user_model->getRow('product_service', $where);
    			if (isset($product_service) && !empty($product_service)) {
    				// $there = ['id' => $product_service->tax];
    				// $product_service['tax'] =	$this->user_model->getAry('tax', '', '', '', $there);
    				// // $product_service['unit'] =	$this->user_model->getAry('unit');
    
    
    
    				// $product_service['product_service'] = $product_service;
    
    				echo json_encode(['status' => true, 'data' => $product_service]);
    			} else {
    				echo json_encode(['status' => true, 'data' => 'Data not found.']);
    			}
    		}
    	}
    
    
    	public function approve($id)
    	{
    		if (isset($id) && !empty($id)) {
    			$where = ['id' => $id];
    			$res = $this->user_model->getRow('product_service_apply', $where);
    			if ($res) {
    				$there = ['id' => $res->user_id];
    				$charged_by = $res->charged;
    				$user = $this->user_model->getRow('users', $there);
    				if ($user) {
    					$amount = $user->amount;
    					$where = ['id' => $user->id];
    
    					if ($amount < $charged_by) {
    						$this->session->set_flashdata(['msg' => 'User Do Not Have A Sufficiant Fund.', 'class' => 'danger']);
    						redirect('service_product');
    					} else {
    						$amount = $amount - $charged_by;
    
    						$dta['amount'] = $amount;
    						$upd = $this->user_model->updateAry('users', $dta, $where);
    
    						if ($upd) {
    							$data['status'] = 1;
    							$where = ['id' => $id];
    							$sta = $this->user_model->updateAry('product_service_apply', $data, $where);
    							$servicename = get_title('product_service', ['id' => $id], 'name');
    							$mobile = get_title('users', ['id' => $res->user_id], 'number');
    							$username = get_title('users', ['id' => $res->user_id], 'name');
    							send_service_responce($mobile, $servicename, $amount, $username);
    							if ($sta) {
    								$this->session->set_flashdata(['msg' => 'Request Approved', 'class' => 'success']);
    								redirect('service_product');
    							} else {
    								$amount = $amount + $charged_by;
    								$amount['amount'] = $amount;
    								$upd = $this->user_model->updateAry('users', $amount, $where);
    								$this->session->set_flashdata(['msg' => 'Could Not be Approved.', 'class' => 'danger']);
    								redirect('service_product');
    							}
    						} else {
    							$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
    							redirect('service_product');
    						}
    					}
    				} else {
    					$this->session->set_flashdata(['msg' => 'User Did Not Find.', 'class' => 'danger']);
    					redirect('service_product');
    				}
    			} else {
    				$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
    				redirect('service_product');
    			}
    		} else {
    			redirect('service_product');
    		}
    	}
    
    	public function proInfo()
    	{
    		$id = $this->input->post('id');
    
    		if (!isset($id) && empty($id)) {
    			echo json_encode(['status' => false, 'data' => 'Id required.']);
    		} else {
    			$user_id = $this->session->user_id;
    			$comp_id = $this->session->comp_id;
    			$where = ['product_service_id' => $id];
    			// $product_service = $this->db->select('*')->get_where('product_service', $where)->row();
    			$product_service =	$this->user_model->getRow('invoice_product_service', $where);
    			if (isset($product_service) && !empty($product_service)) {
    				echo json_encode(['status' => true, 'data' => $product_service]);
    			} else {
    				echo json_encode(['status' => true, 'data' => 'Data not found.']);
    			}
    		}
    	}
    
    	public function edit($id)
    	{
    		if (isset($id) && !empty($id)) {
    
    			$where = ['id' => $id];
    			$return = $this->user_model->getRow('product_service', $where);
    			if ($return) {
    				$data['product_Service'] = $return;
    				$data['page'] = 'admin_details/product_service/edit';
    				$this->load->view('admin', $data);
    			} else {
    				// $data['page'] = 'admin_details/parties/parties_cat';
    				// $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('product_service');
    			}
    		} else {
    			redirect('product_service');
    		}
    	}
    
    	public function save()
    	{
    
    		if ($_POST) {
    			// echo '<pre>';
    			// print_r($_POST);
    			// die;
    			$this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[product_service.name]');
    			// $this->form_validation->set_rules('qty', 'Qty', 'required|trim');
    			// $this->form_validation->set_rules('price', 'Price', 'required|trim');
    			// $this->form_validation->set_rules('unit', 'Unit', 'required|trim');
    			// $this->form_validation->set_rules('tax', 'Tax', 'required|trim');
    
    			// $this->form_validation->set_rules('weight', 'Weight', 'required|trim');
    			// $this->form_validation->set_rules('min_qty', 'Min Qty', 'required|trim');
    
    
    			if ($this->form_validation->run() == FALSE) {
    				$this->data['form_validation'] = validation_errors();
    				$this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
    				$data['page'] = 'admin_details/product_service/index';
    				$this->load->view('admin', $data);
    			} else {
    				$data['name'] = $this->input->post('name');
    				$data['des'] = $this->input->post('long_des');
    				$data['unit'] = $this->input->post('unit');
    				$data['amount'] = $this->input->post('price');
    				$result = $this->user_model->saveAry('product_service', $data);
    				if ($result) {
    					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
    					redirect('product_service');
    				} else {
    					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    					redirect('product_service');
    				}
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    			redirect('product_service');
    		}
    	}
    
    
    	public function save_service()
    	{
    
    
    		if ($_POST) {
    			$data['qty'] = $this->input->post('qty');
    			$data['pro_id'] = $this->input->post('service');
    			$data['charged'] = $this->input->post('charge');
    			$data['total_amount'] = $this->input->post('total_amount');
    			$data['remark'] = $this->input->post('remark');
    			$data['order_id'] = 'SERVICE' . rand(5, 1000) . 'UMATICID';
    			date_default_timezone_set('Asia/Kolkata');
    			$data['created_at'] = date('d-m-Y h:ia');
    			$role_id = $this->session->userdata('roles');
    			$user_id = $this->session->userdata('user_id');
    			$referral_code = $this->session->userdata('referral');
    			$data['user_id'] = $user_id;
    			$data['user_type'] = $role_id;
    			$data['create_date'] = date('Y-m-d');
    
    			$result = $this->user_model->saveAry('product_service_apply', $data);
    			// $servicename = get_title('product_service', ['id' => $this->input->post('service')], 'name');
    			// $mobile = get_title('users', ['userType' =>4, 'referral' => $referral_code], 'number');
    			// $mobile="7999620058";
    			// if(!$mobile)
    			// {
    			// 	$mobile = get_title('users', ['userType' =>2, 'referral' => $referral_code], 'number');
    
    			// }
    			$username = get_title('users', ['id' => $user_id], 'name');
    			send_service_request($username);
    			if ($result) {
    				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
    				redirect('service_product');
    			} else {
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('service_product');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
    			redirect('service_product');
    		}
    	}
    	public function update()
    	{
    		$id = $this->input->post('id');
    		if (isset($id) && !empty($id)) {
    			$where = ['id' => $id];
    			$yes_find_id = $this->user_model->getRow('product_service', $where);
    			if ($yes_find_id) {
    				$data['name'] = $this->input->post('name');
    				$data['des'] = $this->input->post('long_des');
    				$data['unit'] = $this->input->post('unit');
    				$data['amount'] = $this->input->post('price');
    				$updaated_data = $this->user_model->updateAry('product_service', $data, $where);
    				if ($updaated_data) {
    					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
    					redirect('product_service');
    				} else {
    					$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
    					redirect('product_service');
    				}
    			} else {
    				$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
    				redirect('product_service');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
    			redirect('product_service');
    		}
    	}
    
    	public function deletes($id = null)
    	{
    		if (isset($id) && $id != '') {
    
    			$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id')];
    			$return = $this->user_model->deleteRow('product_service_apply', $where);
    			if ($return) {
    				$this->session->set_flashdata(['msg' => 'Record Deleted Successfully.', 'class' => 'success']);
    				redirect('service_product');
    			} else {
    				$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
    				redirect('service_product');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Something IS Missing.', 'class' => 'danger']);
    
    			redirect('service_product');
    		}
    	}
    
    
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
    
    		$usertupe = $this->session->userdata('roles');
    
    		if ($usertupe == 4) {
    			$wlts = get_title('users', ['referral' => $this->session->userdata('referral'), 'userType' => 2], 'amount');
    			$amt_for_admin = $wlts + $amount;
    		} else {
    			$wlts = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
    			// print_r($wlts);
    			// die;
    			$amt_for_admin = $wlts->amount + $amount;
    		}
    
    
    
    		$user_id = $this->input->post('user_id');
    		$msg = $this->input->post('msg');
    
    		$data['status'] = 1;
    		$where = ['id' => $id];
    		$data['amount'] = $amount;
    		$product_service_apply['total_amount'] = $amount;
    		$product_service_apply['status'] = 1;
    		$product_service_apply['msg'] = $msg;
    
    
    
    
    		$there = ['id' => $user_id];
    		$wlt = $this->user_model->getRow('users', $there);
    		$amount = $wlt->amount - $amount;
    		if ($amount < 0) {
    			echo json_encode(['status' => false, 'msg' => 'Do Not Have a Sufficiant Fund']);
    			return;
    		}
    
    		$return = $this->user_model->getRow('product_service_apply', $where);
    		// echo 
    		$walletsdata = $this->user_model->getRow('wallet', ['product_service_id' => $id]);
    		if ($return) {
    			$whatsapp['assign_user'] = $this->session->userdata('user_id');
    			$whatsapp['amount'] = $amts;
    
    			if ($walletsdata) {
    
    				$this->user_model->updateAry('wallet', $whatsapp, ['product_service_id' => $walletsdata->product_service_id]);
    				// $this->user_model->updateAry('wallet', $whatsapp, ['file_sharing_id' => $walletsdata->file_sharing_id, 'user_id' => 1]);
    			} else {
    				// add_Amount=3 means service apply data
    				//add_amount=0 means file sharing data
    				//add_amount=1 credit directly through add amount
    				//add_amount=2 debited directly through add amount
    				// wallet table data
    				$whatsapp['msg'] = $msg;
    
    				$whatsapp['add_amount'] = 3;
    				$whatsapp['act_status'] = "DBT";
    				$whatsapp['voucherdate'] = $return->created_at;
    								$whatsapp['created_at'] = date('Y-m-d');
    
    				$whatsapp['service_product'] = $return->pro_id;
    				$whatsapp['user_id'] = $user_id;
    				$whatsapp['product_service_id'] = $return->id;
    				// service_apply_id=product_service_id;
    				$whatsapp['vouchernumber'] = $return->order_id;
    				$this->user_model->saveAry('wallet', $whatsapp);
    				// $whatsapp['user_id'] = 1;
    				// $whatsapp['act_status'] = "CR";
    				// $this->user_model->saveAry('wallet', $whatsapp);
    
    			}
    		}
    
    		// echo '<pre>';
    		// print_r($return);
    		// // print_r($returns);
    		// die;
    		// $data
    
    		$return = $this->user_model->updateAry('product_service_apply', $product_service_apply, $where);
    		$servicename = get_title('product_service', ['id' => $id], 'name');
    		$mobile = get_title('users', ['id' => $user_id], 'number');
    		$username = get_title('users', ['id' => $user_id], 'name');
    		send_service_responce($mobile, $servicename);
    		// Send sms
    		$amounts['amount'] = $amount;
    		$returnssss = $this->user_model->updateAry('users', $amounts, $there);
    		$amountss['amount'] = $amt_for_admin;
    		$usertupe = $this->session->userdata('roles');
    		if ($usertupe == 4) {
    			$roles_id = get_title('users', ['referral' => $this->session->userdata('referral'), 'userType' => 2], 'id');
    			$returnss = $this->user_model->updateAry('users', $amountss, ['id' => $roles_id]);
    		} else {
    			$returnss = $this->user_model->updateAry('users', $amountss, ['id' => $this->session->userdata('user_id')]);
    		}
    		if ($return) {
    			// $this->session->userdata('user_id');
    			echo json_encode(['status' => true, 'data' => $return, 'msg' => 'Service Approved.']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
    			return;
    		}
    	}
    
    	public function viewdetails($id = null)
    	{
    		if (isset($id) && !empty($id)) {
    
    			$where = ['product_service_id' => $id];
    			$return = $this->user_model->getRow('wallet', $where);
    			$product_service_apply = $this->user_model->getRow('product_service_apply', ['id' => $id]);
    
    
    			if ($return) {
    				$data['result'] = $return;
    				$data['product_service_apply'] = $product_service_apply;
    
    				$data['page'] = 'admin_details/service_product/view_details';
    				$this->load->view('admin', $data);
    			} else {
    				// $data['page'] = 'admin_details/parties/parties_cat';
    				// $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('service_product');
    			}
    		} else {
    			redirect('service_product');
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
    		$return = $this->user_model->getRow('product_service_apply', $where);
    
    		if ($return) {
    			$this->user_model->deleteRow('wallet', ['product_service_id' => $id]);
    			$amount = $return->total_amount;
    			// $file_amount['amount'] = 0;
    			$file_amount['status'] = 0;
    
    			$there = ['id' => $return->user_id];
    			$user_indo = $this->user_model->getRow('users', $there);
    
    			$userwallet = $user_indo->amount;
    			$usaer['amount'] = $userwallet + $amount;
    			$this->user_model->updateAry('users', $usaer, $there);
    
    			$this->user_model->updateAry('product_service_apply', $file_amount, $where);
    
    			$wlts = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
    			$amt_for_admin = $wlts->amount - $return->total_amount;
    			$amounts['amount'] = $amt_for_admin;
    			$this->user_model->updateAry('users', $amounts, ['id' => $this->session->userdata('user_id')]);
    			// $walletsdata = $this->user_model->getRow('wallet', ['file_sharing_id' => $id]);
    			echo json_encode(['status' => true, 'msg' => 'Service Declined!']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'Data Not Found']);
    			return;
    		}
    		// $data['status'] = 0;
    	}
    
    
    	public function delete($id)
    	{
    		if (isset($id) && $id != '') {
    			$where = ['id' => $id];
    
    			$return = $this->user_model->deleteRow('product_service_apply', $where);
    			if ($return) {
    
    				$this->session->set_flashdata(['msg' => 'Record Deleted Successfully.', 'class' => 'success']);
    				redirect('service_product');
    			} else {
    				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
    				redirect('service_product');
    			}
    		} else {
    			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
    
    			redirect('service_product');
    		}
    	}
    	public function load_product_service()
    	{
    		$data['page'] = 'admin_details/product_service/product_service_popup';
    		$this->load->view('admin', $data);
    	}
    
    
    	public function reject()
    	{
    		$id = $this->input->post('id');
    		$user_id = $this->input->post('user_id');
    		$msg = $this->input->post('msg');
    		if ($id == null || $user_id == null) {
    			echo json_encode(['status' => false, 'msg' => 'Something Went Wrong.']);
    			return;
    		}
    		$where = ['id' => $id];
    		$return = $this->user_model->getRow('product_service_apply', $where);
    		if ($return) {
    			$data['status'] = 2;
    			$data['reject_msg'] = $msg;
    			$this->user_model->updateAry('product_service_apply', $data, $where);
    			echo json_encode(['status' => true, 'msg' => 'Updated Action']);
    			return;
    		} else {
    			echo json_encode(['status' => false, 'msg' => 'Data Not Found']);
    			return;
    		}
    		// $data['status'] = 0;
    
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
                        $where = ['create_date >=' => $from, 'create_date <=' => $to, 'subadmin_id' => $this->session->userdata('user_id')];
                    }
                } else {
                    if ($this->session->userdata('roles') == 1) {
                        $where = ['create_date >=' => $from, 'create_date <=' => $to, 'status' => $status];
                    } elseif ($this->session->userdata('roles') == 2) {
                        $where = ['create_date >=' => $from, 'create_date <=' => $to, 'status' => $status, 'subadmin_id' => $this->session->userdata('user_id')];
                    }
                }
                $data['servicedata'] = $this->user_model->getResult('product_service_apply', $where);
            } else {
                if ($this->session->userdata('roles') == 1) {
                    $where = ['create_date >=' => date('Y-m-01'), 'create_date <=' => date('Y-m-d')];
                } elseif ($this->session->userdata('roles') == 2) {
                    $where = ['create_date >=' => date('Y-m-01'), 'create_date <=' => date('Y-m-d'), 'subadmin_id' => $this->session->userdata('user_id')];
                }
                $data['servicedata'] = $this->user_model->getResult('product_service_apply', $where);
            } 

            if ($this->session->userdata('roles') == 1) {
                $data['total_request'] = $this->user_model->getResult('product_service_apply');
                $data['approved_request'] = $this->user_model->getResult('product_service_apply', ['status' => 1]);
                $data['panding_request'] = $this->user_model->getResult('product_service_apply', ['status' => 0]);
                $data['reject_request'] = $this->user_model->getResult('product_service_apply', ['status' => 2]);
                $this->db->select('SUM(total_amount) AS total');
                $this->db->where('status', 1);
                $total_result = $this->db->get('product_service_apply')->row();
                $data['totalamount_approved_request'] = $total_result->total;
            } elseif ($this->session->userdata('roles') == 2) {
                $data['total_request'] = $this->user_model->getResult('product_service_apply', ['subadmin_id' => $this->session->userdata('user_id')]);
                $data['approved_request'] = $this->user_model->getResult('product_service_apply', ['status' => 1, 'subadmin_id' => $this->session->userdata('user_id')]);
                $data['panding_request'] = $this->user_model->getResult('product_service_apply', ['status' => 0, 'subadmin_id' => $this->session->userdata('user_id')]);
                $data['reject_request'] = $this->user_model->getResult('product_service_apply', ['status' => 2, 'subadmin_id' => $this->session->userdata('user_id')]);
                $this->db->select('SUM(total_amount) AS total');
                $this->db->where('status', 1);
                $this->db->where('subadmin_id', $this->session->userdata('user_id'));
                $total_result = $this->db->get('product_service_apply')->row();
                $data['totalamount_approved_request'] = $total_result->total;
            }
            
            $data['page'] = 'admin_details/service_product/report';
            $this->load->view('admin', $data);
        }
    }