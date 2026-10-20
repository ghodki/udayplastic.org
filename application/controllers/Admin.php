<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    date_default_timezone_set("Asia/Calcutta");
    
    class Admin extends CI_Controller
    {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
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

	public function saveuser()
	{
		if ($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();

				$this->data['page'] = "signin/create";
				$this->load->view('main', $this->data);
			} else {
				$data['name'] = $this->input->post('name');
				$data['email'] = $this->input->post('email');
				$data['password'] = $this->input->post('password');
				$data['number'] = $this->input->post('number');
				$data['gender'] = $this->input->post('gender');
				$data['usertype'] = $this->input->post('userType');
				$data['referral'] = $this->input->post('referral');


				$result = $this->user_model->saveAry('users', $data);
				if ($result) {
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


	public function update()
	{
		// echo '<pre>';
		// print_r($_POST);
		// die;
		$post = $this->input->post();

		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['number'] = $this->input->post('number');
			$data['gender'] = $this->input->post('gender');
			$usertype = $this->input->post('usertype');
			$data['permission_account_add'] = $this->input->post('permission_account_add');
                        $data['license_cost'] = $this->input->post('license_cost');
                        if (!empty($this->input->post('NewExpiry'))) {
                            $data['started'] = date('d-m-Y');
                            $data['expiry'] = $this->input->post('NewExpiry');
                        }
                        
			if ($usertype == 3) {
				$where = ['userType' => 2, 'id' => $this->input->post('referral')];
				$subadmin_details = $this->user_model->getRow('users', $where);
				// echo '<pre>';
				// print_r($where);
				// die;
				if ($subadmin_details) {
					$data['referral'] = $subadmin_details->referral;
									$data['employee_assign_id'] = $employee_id;

					// $data['referral'] = $this->input->post('referral');

				}
			}

			$referral = $this->input->post('referral');

			$data['created_at'] = $this->input->post('date');
			$where = ['id' => $id];
			// $array = [];
			if ($this->input->post('key') != '') {
				foreach ($this->input->post('key') as $attrKey => $val) {
					$dyn['label'] = $val;
					$dyn['value'] = $this->input->post('data')[$attrKey];
					$dyn['status'] = $this->input->post('optradio')[$attrKey];
					// $gold[] = $dyn['label'];
					$array[] = $dyn;
				}
			}
			if (isset($array) && $array != '') {
				$data['dynamic'] = json_encode($array);
				$fold['extra_userdata'] = json_encode($array);
				// $fold=json_encode($array);
				// print_r($data);
				// die;
				$d = $this->user_model->getResult('user_extended');
				if ($d) {
					$there = ['id' => 1];
					$result = $this->user_model->updateAry('user_extended', $fold, $there);
				} else {
					$ret = $this->user_model->saveAry('user_extended', $fold);
				}
			}
			// $data['dynamic']=0;
			$result = $this->user_model->updateAry('users', $data, $where);
			if ($result && $usertype == 3) {
				$there = ['id' => $referral, 'userType' => 2];
				$return = $this->user_model->getRow('users', $where);
				// $main_id = $return->id;
				if ($return) {
					$con = ['userid' => $id];
					$subid['referral_id'] = $return->id;
					$subid['emp_id'] = '';
					$subid['userid'] = $id;

					// $subid['userid'] = $id;
					$getaray = $this->user_model->getRow('subcat', $con);
					if ($getaray) {
						$result = $this->user_model->updateAry('subcat', $subid, $con);
						// foreach ($getaray as $ary) {
						// 	// $result = $this->user_model->deleteAry('subcat',$con);
						// 	$return = $this->user_model->deleteRow('subcat', $con);
						// }
					} else {
						$result = $this->user_model->saveAry('subcat', $subid);
					}

					if ($result) {
						if ($referral) {


							$sit = ['user_id' => $id];
							$subudd['subid'] = $referral;
							$subudd['role'] = 1;
							$subudd['emp_id'] = '';
							$userinform = $this->user_model->getAry('file_sharing', '', '', '', $sit);
							if ($userinform) {
								foreach ($userinform as $us) {
									$where = ['id' => $us->id];
									$hyyy = $this->user_model->updateAry('file_sharing', $subudd, $where);
								}
							} else {
								$data['page'] = 'admin_details/userlist';
								$this->session->set_flashdata(['msg' => 'User Update Successfully.', 'class' => 'success']);
								redirect('userlist');
							}
						} else {
							$data['page'] = 'admin_details/userlist';
							$this->session->set_flashdata(['msg' => 'User Update Successfully.', 'class' => 'success']);
							redirect('userlist');
						}
						if ($result) {
							$data['page'] = 'admin_details/userlist';
							$this->session->set_flashdata(['msg' => 'User Update Successfully.', 'class' => 'success']);
							redirect('userlist');
						} else {
							$data['page'] = 'admin_details/userlist';
							$this->session->set_flashdata(['msg' => 'Something Went Wrong, try again.', 'class' => 'danger']);

							redirect('userlist');
						}
					} else {
						$data['page'] = 'admin_details/userlist';
						$this->session->set_flashdata(['msg' => 'Something Went Wrong. retry again', 'class' => 'danger']);
						redirect('userlist');
					}
				} else {
					$data['page'] = 'admin_details/userlist';
					$this->session->set_flashdata(['msg' => 'Something Went Wrong. try after sometime', 'class' => 'danger']);

					redirect('userlist');
				}
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('userlist');
			}
		} else {
			redirect('userlist');
		}
	}


	public function updates()
	{
		$post = $this->input->post();

		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['number'] = $this->input->post('number');
			$data['gender'] = $this->input->post('gender');
			$usertype = $this->input->post('usertype');

			$employee_id = $this->input->post('referral');
			if ($employee_id) {
				$tre = ['id' => $employee_id];
				$reff = $this->user_model->getRow('users', $tre);
				$data['referral'] = $reff->referral;
			}
                        
                        $data['created_at'] = $this->input->post('date');
                        $data['license_cost'] = $this->input->post('license_cost');
                        if (!empty($this->input->post('NewExpiry'))) {
                            $data['started'] = date('d-m-Y');
                            $data['expiry'] = $this->input->post('NewExpiry');
                        }
			$where = ['id' => $id];
			// $array = [];
			if ($this->input->post('key') != '') {
				foreach ($this->input->post('key') as $attrKey => $val) {
					$dyn['label'] = $val;
					$dyn['value'] = $this->input->post('data')[$attrKey];
					$dyn['status'] = $this->input->post('optradio')[$attrKey];
					// $gold[] = $dyn['label'];
					$array[] = $dyn;
				}
			}

			if (isset($array) && $array != '') {
				$data['dynamic'] = json_encode($array);
				$fold['extra_userdata'] = json_encode($array);
				// $fold=json_encode($array);
				// print_r($data);
				// die;
				$d = $this->user_model->getResult('user_extended');
				if ($d) {
					$there = ['id' => 1];
					$result = $this->user_model->updateAry('user_extended', $fold, $there);
				} else {
					$ret = $this->user_model->saveAry('user_extended', $fold);
				}
			}
			// $data['dynamic']=0;

			$result = $this->user_model->updateAry('users', $data, $where);

			if ($result) {
				$there = ['id' => $employee_id, 'userType' => 4];
				$employee_data = $this->user_model->getRow('users', $there);

				if ($employee_data) {
					$where = ['userid' => $id];
					$user_datas = $this->user_model->getRow('subcat', $where);
					if ($user_datas) {
						$con = ['id' => $user_datas->id];
						$subid['emp_id'] = $employee_id;
						$subid['usertype'] = 4;
						$subid['referral_id'] = $this->session->userdata('user_id');
						$result = $this->user_model->updateAry('subcat', $subid, $con);
						$sit = ['user_id' => $id];
						$subudd['subid'] = $this->session->userdata('user_id');
						$subudd['role'] = 1;
						$subudd['emp_id'] = $employee_id;
						$userinform = $this->user_model->getAry('file_sharing', '', '', '', $sit);
						if ($userinform) {
							foreach ($userinform as $us) {
								$where = ['id' => $us->id];
								$hyyy = $this->user_model->updateAry('file_sharing', $subudd, $where);
							}
						}

						if ($result) {
							$data['page'] = 'admin_details/userlist';
							$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
							redirect('userlist');
						} else {
							$data['page'] = 'admin_details/userlist';
							$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
							redirect('userlist');
						}
					} else {
						$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
						redirect('userlist');
					}
				} else {
					$where = ['userid' => $id];
					$user_datas = $this->user_model->getRow('subcat', $where);

					if ($user_datas) {
						$con = ['id' => $user_datas->id];
						$subid['emp_id'] = $employee_id;
						// $subid['usertype']=4;
						// $subid['referral_id'] = $this->session->userdata('user_id');

						$result = $this->user_model->updateAry('subcat', $subid, $con);
						$sit = ['user_id' => $id];
						$subudd['subid'] = $this->session->userdata('user_id');
						$subudd['role'] = 1;
						$subudd['emp_id'] = '';
						$userinform = $this->user_model->getAry('file_sharing', '', '', '', $sit);
						if ($userinform) {
							foreach ($userinform as $us) {
								$where = ['id' => $us->id];
								$hyyy = $this->user_model->updateAry('file_sharing', $subudd, $where);
							}
						}
						if ($result) {
							$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
							redirect('userlist');
						} else {
							redirect('userlist');
						}
					} else {
						$data['page'] = 'admin_details/userlist';
						$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
						redirect('userlist');
					}
				}
			} else {
				redirect('userlist');
			}
		}
	}

	public function emupdate()
	{
		$post = $this->input->post();
		// 		echo '<pre>';
		// print_r($post);
		// die;
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['number'] = $this->input->post('number');
			$data['gender'] = $this->input->post('gender');
                        $data['license_cost'] = $this->input->post('license_cost');
                        if (!empty($this->input->post('NewExpiry'))) {
                            $data['started'] = date('d-m-Y');
                            $data['expiry'] = $this->input->post('NewExpiry');
                        }
			$usertype = $this->input->post('usertype');


			$data['created_at'] = $this->input->post('date');
			$where = ['id' => $id];
			if ($this->input->post('key') != '') {
				foreach ($this->input->post('key') as $attrKey => $val) {
					$dyn['label'] = $val;
					$dyn['value'] = $this->input->post('data')[$attrKey];
					$dyn['status'] = $this->input->post('optradio')[$attrKey];
					$array[] = $dyn;
				}
			}

			if (isset($array) && $array != '') {
				$data['dynamic'] = json_encode($array);
				$fold['extra_userdata'] = json_encode($array);

				$d = $this->user_model->getResult('user_extended');
				if ($d) {
					$there = ['id' => 1];
					$result = $this->user_model->updateAry('user_extended', $fold, $there);
				} else {
					$ret = $this->user_model->saveAry('user_extended', $fold);
				}
			}

			$result = $this->user_model->updateAry('users', $data, $where);

			if ($result) {

				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('userlist');
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);

			redirect('userlist');
		}
	}


	public function delete($id = null)
	{
		$where = ['id' => $id];

		$return = $this->user_model->getRow('users', $where);
		if ($return) {
			// $data['deletes'] = 0;
			// $data['status'] = 0;
			// // $data['email'] = '';
			// // $data['number'] = '';

			// $status['status'] = 0;

			$there = ['userid' => $id];
			$result = $this->user_model->deleteRow('users', $where);
			$result = $this->user_model->deleteRow('subcat', $there);

			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('userlist');
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'success']);
				redirect('userlist');
			}
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'success']);
			redirect('userlist');
		}
	}

	public function store($id = null)
	{
		$where = ['id' => $id];

		$return = $this->user_model->getRow('users', $where);
		if ($return) {
			$data['deletes'] = 1;
			$data['status'] = 1;
			// $data['email'] = '';
			// $data['number'] = '';

			$status['status'] = 1;

			$there = ['userid' => $id];
			$result = $this->user_model->updateAry('users', $data, $where);
			$result = $this->user_model->updateAry('subcat', $status, $there);

			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('userlist');
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'success']);
				redirect('userlist');
			}
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'success']);
			redirect('userlist');
		}
	}

	public function edit($id = null)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('users', $where);
		$data['other'] = $this->user_model->getResult('user_extended');


		if ($return) {
			$data['userinfo'] = $return;
			$data['page'] = 'admin_details/user/edit_user';
			$this->load->view('admin', $data);
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('userlist');
		}
	}
	public function view($id = null)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('users', $where);
		if ($return) {
			$data['userinfo'] = $return;
			$where = ['status' => 1];
			// $data['year'] = $this->user_model->getAry('year');
			$data['year'] = $this->user_model->getAry('year', '', '', '', $where);


			$data['page'] = 'admin_details/user/view';
			$this->load->view('admin', $data);
		} else {
			redirect('userlist');
		}
	}



	public function sms($id = null)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('users', $where);
			if ($return) {
				$data['sms'] = $return;
				$there = ['user_id' => $id];
				$data['sms_history'] = $this->user_model->getAry('sms_history', '', '', '', $there);
				$data['page'] = 'admin_details/sms/index';
				$this->load->view('admin', $data);
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			redirect('userlist');
		}
	}

	public function block($id = null)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('users', $where);
			if ($return) {
				$data['status'] = 0;
				$result = $this->user_model->updateAry('users', $data, $where);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'User has Been blocked.', 'class' => 'danger']);
					redirect('userlist');
				} else {
					$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
					redirect('userlist');
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			redirect('userlist');
		}
	}

	public function inventory_block($id = null)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('users', $where);
			if ($return) {
				$data['inventory'] = 0;
				$result = $this->user_model->updateAry('users', $data, $where);
				if ($result) {
					$this->session->set_flashdata(['msg' => ' inventry for User has Been blocked.', 'class' => 'success']);
					redirect('userlist');
				} else {
					$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
					redirect('userlist');
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			redirect('userlist');
		}
	}

	public function inventory_unblock($id = null)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('users', $where);
			if ($return) {
				$data['inventory'] = 1;
				$result = $this->user_model->updateAry('users', $data, $where);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Inventory for User is unblocked Now.', 'class' => 'success']);
					redirect('userlist');
				} else {
					$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
					redirect('userlist');
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			redirect('userlist');
		}
	}


	public function unblock($id = null)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('users', $where);
			if ($return) {
				$data['status'] = 1;
				$result = $this->user_model->updateAry('users', $data, $where);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'User is unblocked Now.', 'class' => 'success']);
					redirect('userlist');
				} else {
					$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
					redirect('userlist');
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			redirect('userlist');
		}
	}




	public function fileshare($id = null)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('users', $where);
			if ($return) {
				$data['user'] = $return;
				$there = ['user_id' => $id];
				$data['file_sharing'] = $this->user_model->getAry('file_sharing', '', '', '', $there);
				$data['page'] = 'admin_details/file_share/index';
				$this->load->view('admin', $data);
			} else {
				$data['page'] = 'admin_details/userlist';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			redirect('userlist');
		}
	}

	public function filesharesave()
	{
		$id = $this->input->post('id');
		if (isset($id) && $id != '') {

			if (isset($_POST)) {
				$file_name = $_FILES['image']['name'];
				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/file_share/' . $file_name);
				$data['des'] = $this->input->post('name');
				$data['user_id'] = $this->input->post('id');

				$data['file_name'] = $file_name;
				$data['date'] = $this->input->post('date');
				$data['role'] = $this->session->userdata('roles');
				$ret = $this->user_model->saveAry('file_sharing', $data);
				if ($ret) {
					$this->session->set_flashdata(['msg' => 'Record Uploaded Successfully.', 'class' => 'success']);
					redirect('userlist');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('userlist');
				}
			} else {
				$data['page'] = 'admin_details/application';
				$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
				redirect('userlist');
			}
		} else {
			$data['page'] = 'admin_details/application';
			$this->session->set_flashdata(['msg' => 'Not Allow', 'class' => 'danger']);
			redirect('userlist');
		}
	}
	public function referal()
	{

		$party_id = $this->input->post('usertype');
		if ($party_id != 2) {
			$where = ['userType' => 2, 'deletes' => 1,'status'=>1];
			$result = $this->user_model->getAry('users', '', '', '', $where);
					$html = '<label for="assign_to">Assign To</label>';
			$html .= "<select class='form-control' id='referral' name='referral'>";

			// $html .= "<option value='' >-Select-</option>";
			// $html .= "<option value='' >-Select-</option>";
			foreach ($result as $key => $dist_val) {
				$html .= "<option value=" . $dist_val->referral . " >" . $dist_val->name . "</option>";
			}
			$html . "</select>";
		} else {
			$where = ['userType' => 2];
			$referral = $this->user_model->getAryss('users', '', '', '', $where);
			// print_r($referral[0]->referral);
			// die;
			if ($referral) {
				$ref = $referral[0]->referral + 1;
				$html = "<input type='text' readonly class='form-control' value='$ref' name='referral'>";
			} else {
				$html = "<input type='text' readonly class='form-control' value='1000' name='referral'>";
			}
		}
		print_r($html);
		// die;
		// echo json_encode($html);
	}


	public function referals()
	{
		$referral_code = $this->session->userdata('referral');
		$party_id = $this->input->post('usertype');
		if ($party_id == 3) {
			$where = ['userType' => 4, 'deletes' => 1, 'status' => 1, 'referral' => $referral_code];
			$result = $this->user_model->getAry('users', '', '', '', $where);
			$html = '<label for="assign_to">Assign To</label>';
			$html .= "<select class='form-control' id='referral' name='referral'>";

			// $html .= "<option value='' >-Select-</option>";
			// $html .= "<option value='' >-Select-</option>";
			foreach ($result as $key => $dist_val) {
				$html .= "<option value=" . $dist_val->id . " >" . $dist_val->name . "</option>";
			}
			$html . "</select>";
		} else if ($party_id == 2) {
			$where = ['userType' => 2];
			$referral = $this->user_model->getAryss('users', '', '', '', $where);
			// print_r($referral[0]->referral);
			// die;
			if ($referral) {
				$ref = $referral[0]->referral + 1;
				$html = "<input type='text' readonly class='form-control' value='$ref' name='referral'>";
			} else {
				$html = "<input type='text' readonly class='form-control' value='1000' name='referral'>";
			}
		} else {
			$html = "<input type='hidden' readonly class='form-control' value='$referral_code' name='referral'>";
		}
		print_r($html);
		// die;
		// echo json_encode($html);
	}
	
		protected function sale_rules()
	{
		$rules = array(
			array(
				'field' => 'phone',
				'label' => 'Phone Number',
				'rules' => 'trim|required|xss_clean|numeric'
			),
			array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|xss_clean|is_unique[users.email]'
			),
			array(
				'field' => 'state',
				'label' => 'State',
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'validity',
				'label' => 'Certificate Validity',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'c_type',
				'label' => 'Certificate Type',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'user_type',
				'label' => 'User Type',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'token',
				'label' => 'USB Token',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'digital_certificate_id',
				'label' => 'Digital Certificate',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'sub_service_id',
				'label' => 'Sub Service',
				'rules' => 'trim|required|xss_clean'
			),


		);
		return $rules;
	}

	protected function sale_rules_for_digital_signature()
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => 'Phone Number',
				'rules' => 'trim|required|xss_clean|numeric|regex_match[/^[0-9]{10}$/]'
			),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => 'State',
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'validity',
				'label' => 'Certificate Validity',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'c_type',
				'label' => 'Certificate Type',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'user_type',
				'label' => 'User Type',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'token',
				'label' => 'USB Token',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'digital_certificate_id',
				'label' => 'Digital Certificate',
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'sub_service_id',
				'label' => 'Sub Service',
				'rules' => 'trim|required|xss_clean'
			),

		);
		return $rules;
	}
public function applyDigitalSignature()
	{
		// echo '<pre>';
		// print_r($_POST);
		// die;
		$user_id = $this->session->userdata('user_id');
		if (empty($user_id) && !isset($user_id)) {
			if ($_POST) {

				$rules = $this->sale_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == false) {
					$response = array(
						'status' => 'error',
						'message' => validation_errors()
					);
				} else {
					$data['name'] = $this->input->post('name');
					$data['email'] = $this->input->post('email');
					$data['state'] = $this->input->post('state');
					$data['number'] = $this->input->post('phone');
					$data['gst'] = $this->input->post('gstno');
					$data['address'] = $this->input->post('address');
					$data['created_at'] = date('Y-m-d h:i:s');
					$data['usertype'] = 3;
					$user_id = $this->user_model->saveAry('users', $data);
					if ($user_id) {
						$response = array(
							'status' => 'success',
							'message' => "User Created"
						);
					} else {
						$response = array(
							'status' => 'error',
							'message' => "<h3>Something Went Wrong</h3>"
						);
					}
				}
			} else {
				$response = array(
					'status' => 'error',
					'message' => "Required Field can not Be Empty"
				);
			}
		}
		if ($_POST) {
			$rules = $this->sale_rules_for_digital_signature();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$response = array(
					'status' => 'error',
					'message' => validation_errors()
				);
			} else {
				if ($_FILES['org_doc']['name'] != '') {
					$file_name = time() . $_FILES['org_doc']['name'];
					move_uploaded_file($_FILES['org_doc']['tmp_name'], 'uploads/digital_signature/org_doc/' . $file_name);
					$data['org_doc'] = $file_name;
				}
				if ($_FILES['aadhar']['name'] != '') {
					$file_name = time() . $_FILES['aadhar']['name'];
					move_uploaded_file($_FILES['aadhar']['tmp_name'], 'uploads/digital_signature/aadhar/' . $file_name);
					$data['aadhar'] = $file_name;
				}
				if ($_FILES['pancard']['name'] != '') {
					$file_name = time() . $_FILES['pancard']['name'];
					move_uploaded_file($_FILES['pancard']['tmp_name'], 'uploads/digital_signature/pancard/' . $file_name);
					$data['pancard'] = $file_name;
				}
				if ($_FILES['ice_certificate']['name'] != '') {
					$file_name = time() . $_FILES['ice_certificate']['name'];
					move_uploaded_file($_FILES['ice_certificate']['tmp_name'], 'uploads/digital_signature/ice_certificate/' . $file_name);
					$data['ice_certificate'] = $file_name;
				}
				$data['user_id'] = $user_id;
				$data['name'] = $this->input->post('name');
				$data['email'] = $this->input->post('email');
				$data['validity'] = $this->input->post('validity');
				$data['user_type'] = $this->input->post('user_type');
				$data['certificate_type'] = $this->input->post('c_type');
				$data['usb_token'] = $this->input->post('token');
				$data['state'] = $this->input->post('state');
				$data['phone'] = $this->input->post('phone');
				$data['gst_number'] = $this->input->post('gstno');
				$data['address'] = $this->input->post('address');
				$data['created_at'] = date('Y-m-d h:i:s');
				$data['digital_certificate_id'] = $this->input->post('digital_certificate_id');
				$data['service_id'] = $this->input->post('sub_service_id');
				$data['amount'] = get_title('digital_certificate_subservices', ['id' => $this->input->post('sub_service_id')], 'amount');
				$result = $this->user_model->saveAry('digital_certificate_apply', $data);
				if ($result) {
					$response = array(
						'status' => 'success',
						'message' => "Thanks For Applying",
						'data' => $result
					);
				} else {
					$response = array(
						'status' => 'error',
						'message' => "Something Went Wrong"
					);
				}
			}
		} else {
			$response = array(
				'status' => 'error',
				'message' => "Required Field can not Be Empty"
			);
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	public function payment_being_proceed($digital_certificate_apply_id)
	{
		$result = $this->user_model->getRow('digital_certificate_apply', ['id' => $digital_certificate_apply_id, 'status' => 0]);
		if ($result) {
			$customer_name = $result->name;
			$customer_emial = $result->email;
			$customer_mobile = $result->phone;
			$customer_address = $result->address;
			$amount = $result->amount;
			$productinfo = get_title('special_services', ['id' => $result->digital_certificate_id], 'service_name');
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
			$hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . $customer_name . '|' . $customer_emial . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . $SALT;
			$hash = strtolower(hash('sha512', $hashstring));

			$success = base_url() . 'admin/statusCheckOfPayment';
			$fail = base_url() . 'admin/statusCheckOfPayment';
			$cancel = base_url() . 'admin/statusCheckOfPayment';
			$beat['payment_id'] = $txnid;
			$this->user_model->updateAry('digital_certificate_apply', $beat, ['id' => $digital_certificate_apply_id]);
			$data = array(
				'mkey' => $MERCHANT_KEY,
				'tid' => $txnid,
				'hash' => $hash,
				'amount' => $amount,
				'name' => $customer_name,
				'user_id' => $user_id,
				'mailid' => $customer_emial,
				'phoneno' => $customer_mobile,
				'address' => $customer_address,
				'action' => "https://secure.payu.in", //for live change action  https://secure.payu.in
				'sucess' => $success,
				'failure' => $fail,
				'cancel' => $cancel,
				'productinfo' => $productinfo
			);
			$data['page'] = 'layout/service_payment';
		} else {
			$data['page'] = 'layout/service';
		}
		$this->load->view('main', $data);
	}

	public function statusCheckOfPayment()
	{

		$status = $this->input->post('status');
		if (empty($status)) {
			$this->session->set_flashdata(['msg' => 'could not find,please try again', 'class' => '']);
			redirect('/');
		}
		$txnid = $this->input->post('txnid');
		$data['txnid'] = $txnid;
		if ($status == 'success') {
			$res = $this->user_model->getRow('digital_certificate_apply', ['payment_id' => $txnid, 'status' => 0]);
			if ($res) {
				$store['paymentSuccessDate'] = date('d-m-Y h:ia');
				$store['status'] = 1;
				$this->user_model->updateAry('digital_certificate_apply', $store, ['id' => $res->id]);
				$this->session->set_flashdata(['msg' => 'Payment Successfully completed', 'class' => 'success']);
				return redirect('digitalSignatureApply');
			} else {
				$this->session->set_flashdata(['msg' => 'Payment Failed Please try again', 'class' => 'danger']);
				$data['page'] = 'layout/failedPaymentDigitalCerificate';
			}
		} else {
			$error_Message = $this->input->post('error_Message');
			$this->session->set_flashdata(['msg' => $error_Message, 'class' => 'danger']);
			$data['page'] = 'layout/failedPaymentDigitalCerificate';
		}
		$this->load->view('main', $data);
	}

	
        public function changePrice()
	{
		// echo '<pre>';
		// print_r($_POST);
		// die;
		if ($_POST) {
			$validity = $this->input->post('validity');
			$user_type = $this->input->post('user_type');
			$certificate_type = $this->input->post('c_type');
			$usb_token = $this->input->post('token');
			$digital_certificate_id = $this->input->post('digital_certificate_id');
			$where = ['digital_certificate_id' => $digital_certificate_id, 'validity' => $validity, 'user_type' => $user_type, 'certificate_type' => $certificate_type, 'usb_token' => $usb_token];
			$result = $this->user_model->getRow('digital_certificate_subservices', $where);
			if ($result) {
				$response = array(
					'status' => 'success',
					'data' => $result
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => "Do not have a service for this,please choose another one !!"
				);
			}
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}


}
