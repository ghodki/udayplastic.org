<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Year extends CI_Controller
{

	function __construct()
	{

		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('date');

		$this->load->model('user_model');
		if ($this->session->userdata('user_id') === null) {
			redirect('signin');
		}
	}



	public function index()
	{

		$data['year'] = $this->user_model->getResult('year');
		$data['page'] = 'admin_details/year/index';
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

	public function save()
	{
		if ($_POST) {



			$data['year'] = $this->input->post('year');
			$data['starting_year'] = $this->input->post('syear');
			$data['ending_year'] = $this->input->post('eyear');

			$return = $this->user_model->saveAry('year', $data);
			if ($return) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				redirect('year');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('year');
			}
		}
	}

	public function getData()
	{

		$year_id = $this->input->post('year_id');
		$user_id = $this->input->post('user_id');
		$gst = $this->input->post('gst');


		// print_r($user_id);
		// die;
		$where = ['id' => $year_id];
		$we = ['id' => $user_id];

		$year_data = $this->user_model->getRow('year', $where);

		$start_year = $year_data->starting_year;

		$ending_year = $year_data->ending_year;
		// $syear = $start_year->format('Y');
		// $enyear = $ending_year->format('Y');

		$date1 = date('Y-m', strtotime($start_year));
		$date2 = date('Y-m', strtotime($ending_year));

		if ($date1 < $date2) {
			$past = $date1;
			$future = $date2;
		} else {
			$past = $date2;
			$future = $date1;
		}

		$mont = array();
		for ($i = $past; $past <= $future; $i++) {
			$timestamp = strtotime($past . '-1');

			$mont[] = date('Y', $timestamp);

			$past = date('Y-m', strtotime('+1 month', $timestamp));
		}



		$userdata = $this->user_model->getRow('users', $we);
		$created_at = $userdata->created_at;


		$there = ['date_id' => $year_id, 'userid' => $user_id, 'gst' => $gst];
		$userdata = $this->user_model->getAry('finencial_year', '', '', '', $there);

		if ($userdata) {
			$userdata = $userdata;
		} else {
			$j = 0;
			for ($i = 1; $i < 13; $i++) {
				$data['date_id'] = $year_id;
				$data['userid'] = $user_id;
				$data['starting_date'] = $start_year;
				$data['ending_date'] = $ending_year;
				// $data['syear'] = $syear;
				// $data['eyear'] = $enyear;

				$data['gst'] = $gst;
				$data['store_date'] = date("Y-m-d");
				$month = $i;
				$y = $mont[$j];
				$data['month'] = date("$y-$month");



				$data['current'] = $i;
				$where = ['userid' => $user_id, 'date_id' => $year_id, 'current' => $i, 'gst' => $gst];
				$fin = $this->user_model->getRow('finencial_year', $where);
				if ($fin) {
					$result = $this->user_model->updateAry('finencial_year', $data, $where);
				} else {
					$data['status'] = 0;
					$result = $this->user_model->saveAry('finencial_year', $data);
				}
				++$j;
			}
		}




		$there = ['date_id' => $year_id, 'userid' => $user_id, 'gst' => $gst];
		$userdata = $this->user_model->getAry('finencial_year', '', '', '', $there);

		$user_create_date = date('Y-m', strtotime($created_at));

		$date1 = date('Y-m', strtotime($start_year));
		$date2 = date('Y-m', strtotime($ending_year));

		if ($date1 < $date2) {
			$past = $date1;
			$future = $date2;
		} else {
			$past = $date2;
			$future = $date1;
		}

		$months = array();
		for ($i = $past; $past <= $future; $i++) {
			$timestamp = strtotime($past . '-1');
			if ($user_create_date > $future) {
				$months;
			} else {
				$months[] = date('F Y', $timestamp);
			}
			$past = date('Y-m', strtotime('+1 month', $timestamp));
		}


		$where = ['id' => $user_id];
		$newdate = date("Y-m");
		$days = 0;
		$user = $this->user_model->getRow('users', $where);
		$user_create_date = $user->created_at;
		$user_create_time = date('Y-m', strtotime($user_create_date));
		// if ($user_create_time <= $newdate) {
		for ($i = 1; $i < 13; $i++) {
			$where = ['date_id' => $year_id, 'status' => 1, 'gst' => 3, 'userid' => $user_id, 'current' => $i];
			$hhhh = $this->user_model->getRow('finencial_year', $where);
			if ($hhhh) {
				// continue;
				$due[] = "empty";
			} else {
				$month_id = $i;
				$where = ['month_id' => $month_id, 'year_id' => $year_id];
				$duedates = $this->user_model->getRow('duedate', $where);
				if ($duedates) {
					if ($duedates->duedate != null) {
						$date_for_due = $duedates->duedate;
						// $old_date=date_format($date_for_due, 'Y-m-d');
						$current_date = date('d-m-Y');
						// $d = date("d-m-Y", strtotime($date_for_due));
						$date1 = date('d-m-Y', strtotime($current_date));
						$date2 = date('d-m-Y', strtotime($date_for_due));
						if ($date1 > $date2) {
							$date1_ts = strtotime($date1);
							$date2_ts = strtotime($date2);
							$diff = $date1_ts - $date2_ts;
							$days = round($diff / 86400);
							if ($days > 0) {
								$msg = "<span class='text-danger' style='margin-right:40px;'>Days Over Due</span>";

								$due[] = $days . $msg;
							} else {
								$msg = "<span style='margin-right:40px;' class='text-success'>Days Remaining</span>";


								$due[] = - ($days) . $msg;
							}
							// $due[] = -($days)+"days over Due";

						} else {
							$date1_ts = strtotime($date1);
							$date2_ts = strtotime($date2);
							$diff = $date2_ts - $date1_ts;
							$days = round($diff / 86400);
							$msg = "<span class='text-success' style='margin-right:40px;'>Days Remaining</span>";

							$due[] = $days . $msg;
						}
					} else {
						$due[] = "<span class='text-muted' style='margin-right:40px;'>Add Due First?</span>";
					}
				} else {
					$due[] = "<span class='text-muted' style='margin-right:40px;'>Add Due First?</span>";
				}
			}
		}
		// } 



		$data['months'] = $months;

		$data['userinfo'] = $userdata;
		$data['due'] = $due;

		echo json_encode(['status' => true, 'data' => $data]);
	}


	public function getStatus()
	{

		$year_id = $this->input->post('year_id');
		$user_id = $this->input->post('user_id');
		$current = $this->input->post('m_id');
		$status = $this->input->post('status');
		$data['store_date'] = date("Y-m-d");
		$month = $current;
		$data['month'] = date("Y-$month");

		$gst = $this->input->post('gst');
		$data['date_id'] = $year_id;
		$data['userid'] = $user_id;
		$data['date_id'] = $year_id;
		$data['current'] = $current;
		$data['gst'] = $gst;
		$data['status'] = $status;
		$there = ['id' => $year_id];
		$year_data = $this->user_model->getRow('year', $there);

		$data['starting_date'] = $year_data->starting_year;

		$data['ending_date'] = $year_data->ending_year;
		$where = ['userid' => $user_id, 'date_id' => $year_id, 'current' => $current, 'gst' => $gst];
		$fin = $this->user_model->getRow('finencial_year', $where);
		if ($fin) {
			$result = $this->user_model->updateAry('finencial_year', $data, $where);
		} else {
			$result = $this->user_model->saveAry('finencial_year', $data);
		};


		echo json_encode(['status' => true, 'data' => $result]);
	}
	public function changeData()
	{
		$year_id = $this->input->post('year_id');
		$status = $this->input->post('status');
		$where = ['id' => $year_id];
		$data['status'] = $status;
		$result = $this->user_model->updateAry('year', $data, $where);
		echo json_encode(['status' => true, 'data' => $result]);
	}
	public function getDueDate()
	{
		$year_id = $this->input->post('year_id');
		// $user_id = $this->input->post('user_id');
		// $gst = $this->input->post('gst');


		// print_r($user_id);
		// die;
		$where = ['id' => $year_id];
		// $we = ['id' => $user_id];

		$year_data = $this->user_model->getRow('year', $where);

		$start_year = $year_data->starting_year;

		$ending_year = $year_data->ending_year;

		$date1 = date('Y-m', strtotime($start_year));
		$date2 = date('Y-m', strtotime($ending_year));

		if ($date1 < $date2) {
			$past = $date1;
			$future = $date2;
		} else {
			$past = $date2;
			$future = $date1;
		}

		$mont = array();
		for ($i = $past; $past <= $future; $i++) {
			$timestamp = strtotime($past . '-1');

			$mont[] = date('Y', $timestamp);

			$past = date('Y-m', strtotime('+1 month', $timestamp));
		}

		// $syear = $start_year->format('Y');
		// $enyear = $ending_year->format('Y');


		// $userdata = $this->user_model->getRow('users', $we);
		// $created_at = $userdata->created_at;

		// echo '<pre>';
		// print_r($mont);
		// die;
		$there = ['year_id' => $year_id];
		$userdata = $this->user_model->getAry('duedate', '', '', '', $there);

		if ($userdata) {
			$userdata = $userdata;
		} else {
			$j = 0;

			for ($i = 1; $i < 13; $i++) {
				$month = $i;
				$data['month_id'] = $i;
				$data['year_id'] = $year_id;
				$data['years'] = $mont[$j];

				$where = ['year_id' => $year_id, 'month_id' => $i];
				$fin = $this->user_model->getRow('duedate', $where);
				if ($fin) {
					$where = ['id' => $fin->id];
					$result = $this->user_model->updateAry('duedate', $data, $where);
				} else {
					$ye['month_id'] = $i;
					$ye['year_id'] = $year_id;
					$ye['status'] = 0;
					$ye['years'] = $mont[$j];

					$result = $this->user_model->saveAry('duedate', $ye);
				}

				++$j;
			}
		}
		$there = ['year_id' => $year_id];
		$userdata = $this->user_model->getAry('duedate', '', '', '', $there);

		// $user_create_date = date('Y-m', strtotime($created_at));

		$date1 = date('Y-m', strtotime($start_year));
		$date2 = date('Y-m', strtotime($ending_year));

		if ($date1 < $date2) {
			$past = $date1;
			$future = $date2;
		} else {
			$past = $date2;
			$future = $date1;
		}

		$months = array();
		for ($i = $past; $past <= $future; $i++) {
			$timestamp = strtotime($past . '-1');

			$months[] = date('F Y', $timestamp);

			$past = date('Y-m', strtotime('+1 month', $timestamp));
		}



		$data['months'] = $months;

		$data['userinfo'] = $userdata;

		echo json_encode(['status' => true, 'data' => $data]);
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







	public function do_upload()
	{
		$config['upload_path']          = 'uploads/';
		$config['allowed_types']        = 'gif|jpg|png|pdf|doc';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('userfile')) {
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('admin', $error);
		} else {
			$data = array('upload_data' => $this->upload->data());

			$this->load->view('admin', $data);
		}
	}

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



	public function delete($id)
	{
		$where = ['id' => $id];

		$return = $this->user_model->deleteRow('year', $where);
		if ($return) {
			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
			redirect('year');
		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('year');
		}
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


	public function doupload()
	{
		// 	$post = $this->input->post();
		// 	echo '<pre>';
		// print_r($post);
		// die;
		if ($_POST) {
			$id = $this->input->post('id');
			$user_id = $this->input->post('userid');

			$where = ['id' => $id];
			// $data['text'] = $this->input->post('text');
			// if ($_FILES['image']['name'] != '') {

			$file_name = $_FILES['image']['name'];
			// $file_size = $_FILES['image']['size'];
			// $file_tmp = $_FILES['image']['tmp_name'];
			// $file_type = $_FILES['image']['type'];
			// if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
			move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/filling/' . $file_name);
			$data['image'] = $file_name;
			$result = $this->user_model->updateAry('finencial_year', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				// $this->load->view('admin',$aman);
				return redirect('admin/view/' . $user_id);
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('admin/view/' . $user_id);
			}
		}
	}
}
