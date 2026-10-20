<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Eattendance extends CI_Controller
{


	function __construct()
	{

		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		if ($this->session->userdata('user_id') === null) {
			redirect('signin');
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	// public function index()
	// {
	// 	$data['page'] = 'admin_details/dashboard';
	// 	$this->load->view('admin', $data);

	// }

	// public function userlist()
	// {
	// 	$data['page'] = 'admin_details/userlist';
	// 	$data['userlist']=$this->user_model->getResult('users');
	// 	$this->load->view('admin', $data);
	// }
	// public function addslider()
	// {
	// 	$data['page'] = 'admin_details/slider';
	// 	// $data['userlist']=$this->user_model->getResult('slider');
	// 	$this->load->view('admin', $data);
	// }
	// protected function rules() {
	// 	$rules = array(
	// 		array(
	// 			'field' => 'name',
	// 			'label' => 'name',
	// 			'rules' => 'trim|required|xss_clean|max_length[60]|'
	// 		),
	// 		array(
	// 			'field' => 'email',
	// 			'label' => 'email',
	// 			'rules' => 'trim|required|max_length[128]|xss_clean'
	// 		),
	// 		array(
	// 			'field' => 'number',
	// 			'label' => $this->lang->line("section_capacity"),
	// 			'rules' => 'trim|required|max_length[12]|xss_clean|numeric|callback_valid_number'
	// 		),
	// 		array(
	// 			'field' => 'classesID',
	// 			'label' => $this->lang->line("section_classes"),
	// 			'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classes'
	// 		),
	// 		array(
	// 			'field' => 'teacherID',
	// 			'label' => $this->lang->line("section_teacher_name"),
	// 			'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_teacher'
	// 		),
	// 		array(
	// 			'field' => 'note',
	// 			'label' => $this->lang->line("section_note"),
	// 			'rules' => 'trim|max_length[200]|xss_clean'
	// 		)
	// 	);
	// 	return $rules;
	// }



	public function index($start = 0)
	{
		$data['userlist']='';
		// $data['page'] = 'admin_details/userlist/index';
		$limit = '';
		$order = ['order' => 'DESC'];
		$role_id = $this->session->userdata('roles');
		if ($role_id == 1) {
			$where = ['status' => 1];
			// $data['userlist'] = $this->user_model->getAry('users', '', '', '', $where);
			$data['userlist'] = $this->user_model->getAryss('userlogindetails', $order, $start, $limit, $where);
		} else if ($role_id == 2) {
			$where = ['referral' => $this->session->referral, 'userType!=' => 2, 'deletes' => 1];
			// $data['userlist'] = $this->user_model->getAry('users', '', '', '', $where);

			$employye = $this->user_model->getAry('users', $order, $start, $limit, $where);
			// $employye = $this->user_model->getAry('subcat', $order, $start, $limit, $where);

			// $employye = $this->user_model->getAry('subcat', '', '', '', $where);
			foreach ($employye as $emp) {
				$user_id = $emp->id;
				$where = ['userId' => $user_id];
				$userlist[] = $this->user_model->getRow('userlogindetails', $where);
			}
		} else if ($role_id == 3) {
			$where = ['id' => $this->session->userdata('user_id')];
			// $data['userlist'] = $this->user_model->getAry('users', '', '', '', $where);
			$data['userlist'] = $this->user_model->getAry('users', $order, $start, $limit, $where);
		} else {
			$userlist = [];
			$where = ['emp_id' => $this->session->userdata('user_id'), 'usertype' => 4];
			$employye = $this->user_model->getAry('subcat', $order, $start, $limit, $where);

			// $employye = $this->user_model->getAry('subcat', '', '', '', $where);
			foreach ($employye as $emp) {
				$user_id = $emp->userid;
				$where = ['userId' => $user_id];
				$userlist[] = $this->user_model->getRow('userlogindetails', $where);
			}
			// echo '<pre>';
			// print_r($userlist);
			// die;
			$data['userlist'] = $userlist;
		}
		$userlist = $data['userlist'];
		// $data['page'] = 'inventory_sep/series/view';
		$data['page'] = 'admin_details/eattendance/index';
		$this->load->view('admin', $data);
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



	public function amount()
	{

		if (!$this->input->post()) {
			echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
			return;
		}

		$amount = $this->input->post('amount');
		$user_id = $this->input->post('user_id');

		$where = ['id' => $user_id];
		$userofo = $this->user_model->getRow('users', $where);
		$amounts = $userofo->amount;
		$data['amount'] = $amount + $amounts;
		$res = $this->user_model->updateAry('users', $data, $where);
		if ($res) {
			$mountData['assign_user'] = $this->session->userdata('user_id');
			$mountData['user_id'] = $this->input->post('user_id');
			// date_default_timezone_set('Asia/Kolkata');
			$mountData['tranDate'] = date("d/m/Y");
			$mountData['transactionID'] = "Trans" . rand(0, 1000) . "DIR";

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

	public function saveuser()
	{
		if ($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
				$data['page'] = 'admin_details/userlist';
				$this->load->view('admin', $data);
			} else {
				$data['name'] = $this->input->post('name');
				$data['email'] = $this->input->post('email');
				$data['password'] = $this->input->post('password');
				$data['number'] = $this->input->post('number');
				$data['gender'] = $this->input->post('gender');
				$data['usertype'] = $this->input->post('userType');
				$data['referral'] = $this->input->post('referral');
				$data['created_at'] = $this->input->post('date');



				$result = $this->user_model->saveAry('users', $data);
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
				$data['page'] = 'admin_details/userlist';
				$this->load->view('admin', $data);
			} else {
				$data['name'] = $this->input->post('name');
				$data['email'] = $this->input->post('email');
				$data['password'] = $this->input->post('password');
				$data['number'] = $this->input->post('number');
				$data['gender'] = $this->input->post('gender');
				$data['usertype'] = $this->input->post('userType');
				$data['referral'] = $this->input->post('referral');
				$data['created_at'] = $this->input->post('date');
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

	public function seedetails($id = null)
	{
		if (isset($id) && !empty($id)) {
			$res = $this->user_model->getAryss('userlogindetails', '', '', '', ['userId' => $id]);
			if ($res) {
				$result['logindetails'] = $res;
				$result['id']=$id;
				$result['page'] = 'admin_details/eattendance/view';

				$this->load->view('admin', $result);
			} else {
				$this->session->set_flashdata(['msg' => 'Not Found Data.', 'class' => 'danger']);
				redirect('eattendance');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Found Data.', 'class' => 'danger']);
			redirect('eattendance');
		}
	}

	// public function search()
	// {
	// 	$role_id = $this->session->userdata('roles');
	// 	$name = $this->input->post('name');
	// 	$number = $this->input->post('number');
	// 	$referral = $this->input->post('ref');
	// 	if ($role_id == 1) {
	// 		$sql = "userType!='1' AND status ='1' AND name='$name' AND number='$number' AND referral='$referral'";
	// 		$userlist = $this->user_model->getAry('users', '', '', '', $sql);
	// 	} else {
	// 		if ($role_id == 2) {
	// 			$sql = "userType!='2' AND status ='1' AND name='$name' AND number='$number' AND referral='$referral'";
	// 			$userlist = $this->user_model->getAry('users', '', '', '', $sql);
	// 		}
	// 	}
	// 	echo json_encode(['status' => true, 'data' => $userlist]);
	// }







	// public function do_upload()
	// {
	// 	$config['upload_path']          = 'uploads/';
	// 	$config['allowed_types']        = 'gif|jpg|png';
	// 	$config['max_size']             = 100;
	// 	$config['max_width']            = 1024;
	// 	$config['max_height']           = 768;

	// 	$this->load->library('upload', $config);

	// 	if (!$this->upload->do_upload('userfile')) {
	// 		$error = array('error' => $this->upload->display_errors());

	// 		$this->load->view('admin', $error);
	// 	} else {
	// 		$data = array('upload_data' => $this->upload->data());

	// 		$this->load->view('admin', $data);
	// 	}
	// }

	// public function saveuser()
	// {
	// 	if ($_POST) {
	// 		$data['name'] = $this->input->post('name');
	// 		$data['email'] = $this->input->post('email');
	// 		$data['password'] = $this->input->post('password');
	// 		$data['number'] = $this->input->post('number');
	// 		$data['gender'] = $this->input->post('gender');
	// 		$data['usertype'] = 2;

	// 		$result = $this->user_model->saveAry('users', $data);
	// 		if ($result) {
	// 			$data['page'] = 'admin_details/userlist';
	// 			$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
	// 			redirect('userlist');
	// 		} else {
	// 			$data['page'] = 'admin_details/userlist';

	// 			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
	// 			redirect('userlist');
	// 		}
	// 	} else {
	// 		$data["page"] = "admin_details/userlist";
	// 		redirect('userlist');
	// 	}
	// }



	// public function delete($id)
	// {
	// 	$where = ['id' => $id];

	// 	$return = $this->user_model->getRow('users', $where);
	// 	if($return)
	// 	{

	// 	}
	// 	// $where = ['userid' => $id];
	// 	// $return = $this->user_model->deleteRow('subcat', $where);
	// 	if ($return) {
	// 		$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
	// 		redirect('userlist');
	// 	} else {
	// 		$data['page'] = 'admin_details/userlist';
	// 		$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
	// 		redirect('userlist');
	// 	}
	// }

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
}
