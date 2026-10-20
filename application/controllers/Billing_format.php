<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Billing_format extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);
            $this->load->model('user_model');

            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
	}

	public function index()
	{
            $data['billing_format'] = $this->user_model->getAry('billing_format', '', '', '', '');
            $data['page'] = 'billing_format/index';
            return $this->load->view('inventory_sep/inventory', $data);
	}

	public function changeData()
	{
		// print_r($_POST);
		// die;
		$bill_id = $this->input->post('id');
		$status = $this->input->post('status');
		$comp_id = $this->session->userdata('comp_id');
		$user_id = $this->session->userdata('user_id');
		$where = ['bill_id' => $bill_id, 'comp_id' => $comp_id, 'user_id' => $user_id];
		if ($this->session->userdata('BranchRole')) {
			$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
		}
		$bill_format_status = $this->user_model->getRow('bill_format_status', $where);
		if ($bill_format_status) {
			$staus['status'] = $status;
			$result = $this->user_model->updateAry('bill_format_status', $staus, $where);
		} else {
			$data['bill_id'] = $this->input->post('id');
			$data['status'] = $this->input->post('status');
			$data['comp_id'] = $this->session->userdata('comp_id');
			$data['user_id'] = $this->session->userdata('user_id');
			if ($this->session->userdata('BranchRole')) {
                $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
			$result = $this->user_model->saveAry('bill_format_status', $data);
		}
		echo json_encode(['status' => true, 'data' => $result]);
	}

	public function save()
	{
		if ($_POST) {
			$this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[billing_format.name]');
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
				$data['page'] = 'admin_details/billing_format/index';
				$this->load->view('admin', $data);
			} else {
				$data['name'] = $this->input->post('name');
				$result = $this->user_model->saveAry('billing_format', $data);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('billing_format');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('billing_format');
				}
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('billing_format');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('billing_format', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('billing_format');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('billing_format');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('billing_format');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');;
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('billing_format', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('billing_format');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('billing_format');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect('billing_format');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('billing_format', $where);

		if ($return) {
			$data['billing_format'] = $return;
			$data['page'] = 'admin_details/billing_format/edit';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('billing_format');
		}
	}
}
