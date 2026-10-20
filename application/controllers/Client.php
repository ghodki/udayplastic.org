<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends CI_Controller
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
	public function index()
	{
		$user_id = $this->session->userdata('user_id');
		$where = ['userid' => $user_id];
		$data['khatabook'] = $this->user_model->getAry('khatabook', '', '', '', $where);
		$data['page'] = 'admin_details/khatabook/index';
		$this->load->view('admin', $data);
	}

	public function viewprofile($id)
	{
		if (isset($id) && $id != '') {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('client', $where);

			// if ($return) {
			$data['client'] = $return;
			// $data['comp'] = $id;
			// $data['error'] = '';

			$data['page'] = 'admin_details/client/viewprofile';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('khatabook');
		}
	}
	public function save()
	{
		if ($_POST) {

			$data['location'] = $this->input->post('client_name');
			$data['number'] = $this->input->post('number');
			$data['email'] = $this->input->post('email');


			$result = $this->user_model->saveAry('client', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				return redirect('client/index');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('client/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			return redirect('client/index');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];
			$return = $this->user_model->getRow('client', $where);
			if ($return) {
				$company_id = $return->company_id;
				$return = $this->user_model->deleteRow('client', $where);
				if ($return) {
					$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
					redirect('khatabook/viewclient/' . $company_id);
				} else {
					$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
					redirect('khatabook/viewclient/' . $company_id);
				}
			} else {
				$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
				return ('khatabook');
			}
		}
	}


	public function edit($id)
	{
		if (isset($id) && $id != '') {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('client', $where);

			if ($return) {
				$data['client'] = $return;

				$com = $return->company_id;
				$d = $this->clientview($com, $id);
				// print_r($d);
				redirect('client/clientview/' . $com . '/' . $id);
			} else {
				redirect('client/clientview/' . $id);
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			return redirect('client/index');
		}
	}
	public function changeUser()
	{


		$html = '<div class="col-md-6 col-sm-12">
		<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>client/updateprofile" method="POST" enctype="multipart/form-data">




			
			<div class="col-md-12 col-sm-12 col-lg-12">
				<div class="form-group">
					<select class="form-control" required>
						<option value=""></option>

						<option value="credited">credited</option>
						<option value="debited">debited</option>

					</select>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-lg-12">
				<div class="form-group">
					<input id="rmark" name="remark" placeholder="Add remark" type="text" class="form-control" required="true">
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-lg-12">
				<div class="form-group">
					<input id="file" name="file" placeholder="Add File" type="file" class="form-control">
				</div>
			</div>
	</div>
	<div class="col-md-6 col-dm-12">
		<table style="width:100%;">
			<tr>
				<th>Client Name</th>
				<td><?= $client->name; ?></td>
			</tr>

			<tr>
				<th>Number</th>
				<td>48388343</td>
			</tr>

		</table>
	</div>
</div>

</div>


<div class="row mt-5">
<div class="col-md-12 text-center">
	<button type="submit" class="btn btn-default">


		Update

	</button>
	<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
</div>
</div>
</form>
</div>
</div>';
		echo json_encode(['data' => $html]);
	}

	public function update()
	{
		// 	$post = $this->input->post();
		// 	echo '<pre>';
		// print_r($post);
		// die;
		if ($_POST) {

			$data['name'] = $this->input->post('name');
			$data['address'] = $this->input->post('address');
			$data['number'] = $this->input->post('number');
			$data['email'] = $this->input->post('email');

			$company_id = $this->input->post('company_id');
			$id = $this->input->post('id');

			$data['company_id'] = $company_id;
			$where = ['id' => $id];
			$send['page'] = 'admin_details/khatabook/viewclient/' . $company_id;
			$return = $this->user_model->updateAry('client', $data, $where);
			if ($return) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				// $this->load->view('admin', $send);
				redirect('khatabook/viewclient/' . $company_id);
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				// redirect('khatabook');
				$this->load->view('admin', $send);

				// $this->load->view('admin', $send, $company_id);
			}
		}
	}
	public function clientview($com = null, $id = null)
	{
		if ($com != null) {
			if ($id != null) {
				$where = ['id' => $id];
				$return = $this->user_model->getRow('client', $where);
				if ($return) {
					$data['page'] = 'admin_details/client/edit';
					$data['com'] = $com;
					$data['client'] = $return;
					$this->load->view('admin', $data);
				}
			} else {
				redirect('khatabook/viewclient/' . $com);
			}
		} else {
			redirect('khatabook');
		}
		// else {
		// 	redirect('')
		// }
	}
	public function getData()
	{
		$comp_id = $this->input->post('comp_id');
		$client_id = $this->input->post('user_id');
		// print_r($_POST);
		// die;
		$html = 'Not Found';
		$a = 1;
		$where = ['company_id' => $comp_id];
		$table_data = $this->user_model->gett('client', '', $client_id, '', '', $where);
		if ($table_data) {
			foreach ($table_data as $table) {
				$date = $table->created_at;
				$newDate = date("d-m-Y", strtotime($date));
				// $table=
				echo "<tr>";
				echo "<td>" . $a . "</td>";
				++$a;
				echo "<td>" . $newDate . "</td>";

				echo "<td>" . $table->name . "</td>";
				echo "<td>" . $table->number . "</td>";
				echo "<td>" . $table->address . "</td>";


				echo '<td class="text-right">
<div class="dropdown">
	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="fas fa-ellipsis-v"></i>
	</a>
	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
		<a href="' . base_url("index.php/client/edit/$table->id") . '" class="dropdown-item cp">Edit</a>
		<a href="' . base_url("index.php/client/delete/$table->id") . '" class="dropdown-item cp">Delete</a>


	</div>
</div>';

				echo "</tr>";
			}
		}
		echo json_encode($html);
	}
}
