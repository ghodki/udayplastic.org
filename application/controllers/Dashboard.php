<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Dashboard extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);
            $this->load->model('user_model');
            if ($this->session->userdata('roles') === null) {
                return redirect(base_url() . 'signin');
            }
	}

	public function index()
	{
	    $roles_id = $this->session->userdata('roles');

            $data['userinfo'] = $this->user_model->getResult('users');
            $data['totalstoreusers'] = "0";
            $data['users'] = "0";
            if ($roles_id == 1) {
                $LastLoginInfo = $this->db->select('loginDate, loginTime')->where('userId', $this->session->userdata('user_id'))->where('roles', $roles_id)->order_by('id', 'DESC')->get('userlogindetails')->row();
                if (isNewDay($LastLoginInfo->loginDate . '&nbsp;' . $LastLoginInfo->loginTime, date('Y-m-d H:i:s'))) {
                    $data['DBAlert'] = "Ok";
                }
                $data['page'] = 'admin/dashboard';
            } elseif ($roles_id == 2) {
                $data['page'] = 'subadmin/dashboard';
            } elseif ($roles_id == 3) {
                $data['page'] = 'user/dashboard';
            } elseif ($roles_id == 4) {
                $data['page'] = 'employee/dashboard';
            }	  
            return $this->load->view('admin', $data);
	}

	public function addslider()
	{
            $data['page'] = 'admin_details/slider';
            return $this->load->view('admin', $data);
	}

	public function changesession()
	{
            if (isset($_POST)) {
                $id = $this->input->post('year_id');
                $this->session->unset_userdata('default');
                $this->session->set_userdata('default', $id);
            } else {
                return redirect(base_url() . 'dashboard');
            }
            echo json_encode(['status' => true, 'data' => $id]);
	}

	public function getData()
	{
		$year_id = $this->input->post('year_id');
		$user_id = $this->session->userdata('user_id');
		$gst = $this->input->post('gst');

		$where = ['id' => $year_id];
		$we = ['id' => $user_id];

		$year_data = $this->user_model->getRow('year', $where);


		$userdata = $this->user_model->getRow('users', $we);
		$created_at = $userdata->created_at;


		$there = ['date_id' => $year_id, 'userid' => $user_id, 'gst' => $gst];
		$userdata = $this->user_model->getAry('finencial_year', '', '', '', $there);


		$there = ['date_id' => $year_id, 'userid' => $user_id, 'gst' => $gst];
		$userdata = $this->user_model->getAry('finencial_year', '', '', '', $there);
		$start_year = $year_data->starting_year;

		$ending_year = $year_data->ending_year;
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



		$data['months'] = $months;

		$data['userinfo'] = $userdata;

		echo json_encode(['status' => true, 'data' => $data]);
	}

	public function viewall()
	{
		$data['page'] = 'admin_details/viewall/index';
		$data['userinfo'] = $this->user_model->getResult('users');
		$this->load->view('admin', $data);
	}

	public function subadmindata()
	{
		$subadmin_id = $this->input->post('id');
		// print_r($subadmin_id);
		// die;
		// $us=[];

		$where = ['referral_id' => $subadmin_id, 'status' => 1];
		$userdata = $this->user_model->getAry('subcat', '', '', '', $where);
		// print_r($userdata);
		// die;
		// $gst1 = [];/
		// $html = '';
		$html = '';
		$a = 1;
		if ($userdata) {
			foreach ($userdata as $user) {
				$month = date("Y-n", strtotime("-4 months"));

				$where = ['userid' => $user->userid, 'gst' => 3, 'status' => 1, 'month' => $month];
				$row = $this->user_model->getRow('finencial_year', $where);
				if ($row) {
					echo "<tr>";
					echo "<td>" . $a . "</td>";
					++$a;

					echo "<td>" . $row->store_date . "</td>";
					$where = ['id' => $row->userid];
					$nameget = $this->user_model->getRow('users', $where);
					$name = $nameget->name;
					echo "<td>" . $name . "</td>";

					echo "<td>" . "GSTR 3B" . "</td>";

					echo '<td><span class="text-success"></span><label class="switch"><input type="checkbox" onclick="return false;" checked><span class="slider round"></span></label> </td>';
					echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

					echo "</tr>";
				} else {
					$month = date("Y-n", strtotime("-4 months"));

					$where = ['userid' => $user->userid, 'gst' => 3, 'status' => 0, 'month' => $month];
					$row = $this->user_model->getRow('finencial_year', $where);
					if ($row) {

						echo "<tr>";
						echo "<td>" . $a . "</td>";
						++$a;
						echo "<td>" . "Not Filled" . "</td>";
						$where = ['id' => $row->userid];
						$nameget = $this->user_model->getRow('users', $where);
						$name = $nameget->name;
						echo "<td>" . $name . "</td>";

						echo "<td>" . "GSTR 3B" . "</td>";

						echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
						echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

						echo "</tr>";
					} else {

						echo "<tr>";
						echo "<td>" . $a . "</td>";
						++$a;
						echo "<td>" . "Not Filled" . "</td>";
						$where = ['id' => $user->userid];
						$nameget = $this->user_model->getRow('users', $where);
						$name = $nameget->name;
						echo "<td>" . $name . "</td>";

						echo "<td>" . "GSTR 3B" . "</td>";

						echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
						echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$user->userid") . '" class="btn btn-light">View profile</a></td>';

						echo "</tr>";
					}
				}
			}


			// $html .= 

			// array_push($gst1, $gs1);/
			// array_push($gst3,$gs3);

			// print_r($us);
			// die;
			// array_push($us, $this->user_model->getAry('finencial_year', '', '', '', $where));
			// }


			// echo json_encode(['status' => true, 'data' =>]);
		}
		// echo '</table>';
		print_r($html);
		// die;
		// echo json_encode($html);
	}

	public function filter()
	{

		if ($this->session->userdata('roles') == 1) {
			$subadmin_id = $this->input->post('id');
		} else {
			$subadmin_id = $this->session->userdata('user_id');
		}
		$name = $this->input->post('name');

		// print_r($subadmin_id);
		// die;
		// $us=[];

		$where = ['referral_id' => $subadmin_id];
		$userdata = $this->user_model->getAry('subcat', '', '', '', $where);
		// print_r($userdata);
		// die;
		// $gst1 = [];/
		// $html = '';
		$html = '';
		$a = 1;
		if ($userdata) {
			$thew = ['id' => $subadmin_id];
			$ref = $this->user_model->getRow('users', $thew);
			$referal = $ref->referral;
			$where = ['userType' => 3, 'referral' => $referal, 'deletes' => 1];
			$nameget = $this->user_model->gett('users', '', $name, '', '', $where, '');
			// echo '<pre>';
			// print_r($nameget);
			// die;
			foreach ($nameget as $user) {
				$month = date("Y-n", strtotime("-4 months"));

				$where = ['userid' => $user->id, 'gst' => 3, 'status' => 1, 'month' => $month];
				$row = $this->user_model->getRow('finencial_year', $where);
				if ($row) {
					echo "<tr>";
					echo "<td>" . $a . "</td>";
					++$a;

					echo "<td>" . $row->store_date . "</td>";
					$where = ['id' => $row->userid];
					$nameget = $this->user_model->getRow('users', $where);
					$name = $nameget->name;
					echo "<td>" . $name . "</td>";

					echo "<td>" . "GSTR 3B" . "</td>";

					echo '<td><span class="text-success"></span><label class="switch"><input type="checkbox" onclick="return false;" checked><span class="slider round"></span></label> </td>';
					echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

					echo "</tr>";
				} else {
					$month = date("Y-n", strtotime("-4 months"));

					$where = ['userid' => $user->id, 'gst' => 3, 'status' => 0, 'month' => $month];
					$row = $this->user_model->getRow('finencial_year', $where);
					if ($row) {

						echo "<tr>";
						echo "<td>" . $a . "</td>";
						++$a;
						echo "<td>" . "Not Filled" . "</td>";
						$where = ['id' => $row->userid];
						$nameget = $this->user_model->getRow('users', $where);
						$name = $nameget->name;
						echo "<td>" . $name . "</td>";

						echo "<td>" . "GSTR 3B" . "</td>";

						echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
						echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

						echo "</tr>";
					} else {

						echo "<tr>";
						echo "<td>" . $a . "</td>";
						++$a;
						echo "<td>" . "Not Filled" . "</td>";
						$where = ['id' => $user->id];
						$nameget = $this->user_model->getRow('users', $where);
						$name = $nameget->name;
						echo "<td>" . $name . "</td>";

						echo "<td>" . "GSTR 3B" . "</td>";

						echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
						echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$user->id") . '" class="btn btn-light">View profile</a></td>';

						echo "</tr>";
					}
				}
			}
			// $html .= 

			// array_push($gst1, $gs1);/
			// array_push($gst3,$gs3);

			// print_r($us);
			// die;
			// array_push($us, $this->user_model->getAry('finencial_year', '', '', '', $where));
			// }


			// echo json_encode(['status' => true, 'data' =>]);
		} else {
			// 			print_r($_POST);
			// die;
			$where = ['userType' => 3];
			$nameget = $this->user_model->gett('users', '', $name, '', '', $where, '');

			// echo '<pre>';
			// print_r($nameget);
			// die;
			if ($nameget) {
				foreach ($nameget as $user) {
					$month = date("Y-n", strtotime("-4 months"));

					$where = ['userid' => $user->id, 'gst' => 3, 'status' => 1, 'month' => $month];
					$row = $this->user_model->getRow('finencial_year', $where);
					if ($row) {
						echo "<tr>";
						echo "<td>" . $a . "</td>";
						++$a;

						echo "<td>" . $row->store_date . "</td>";
						$where = ['id' => $row->userid];
						$nameget = $this->user_model->getRow('users', $where);
						$name = $nameget->name;
						echo "<td>" . $name . "</td>";

						echo "<td>" . "GSTR 3B" . "</td>";

						echo '<td><span class="text-success"></span><label class="switch"><input type="checkbox" onclick="return false;" checked><span class="slider round"></span></label> </td>';
						echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

						echo "</tr>";
					} else {
						$month = date("Y-n", strtotime("-4 months"));

						$where = ['userid' => $user->id, 'gst' => 3, 'status' => 0, 'month' => $month];
						$row = $this->user_model->getRow('finencial_year', $where);
						if ($row) {

							echo "<tr>";
							echo "<td>" . $a . "</td>";
							++$a;
							echo "<td>" . "Not Filled" . "</td>";
							$where = ['id' => $row->userid];
							$nameget = $this->user_model->getRow('users', $where);
							$name = $nameget->name;
							echo "<td>" . $name . "</td>";

							echo "<td>" . "GSTR 3B" . "</td>";

							echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
							echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

							echo "</tr>";
						} else {

							echo "<tr>";
							echo "<td>" . $a . "</td>";
							++$a;
							echo "<td>" . "Not Filled" . "</td>";
							$where = ['id' => $user->id];
							$nameget = $this->user_model->getRow('users', $where);
							$name = $nameget->name;
							echo "<td>" . $name . "</td>";

							echo "<td>" . "GSTR 3B" . "</td>";

							echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
							echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$user->id") . '" class="btn btn-light">View profile</a></td>';

							echo "</tr>";
						}
					}
				}
			}
		}
		// echo '</table>';
		print_r($html);
		// die;
		// echo json_encode($html);
	}
}
