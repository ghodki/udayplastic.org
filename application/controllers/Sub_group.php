<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Sub_group extends CI_Controller
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
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            } else {
                $data['error'] = '';
                $data['sub_group'] = $this->user_model->getAry('sub_group', '', '', '', '');
                $data['page'] = 'sub_group/index';
                return $this->load->view('inventory_sep/inventory', $data);
            }
	}

	public function save()
	{
		if ($_POST) {
			$this->form_validation->set_rules('name', 'Sub Group', 'required|trim|is_unique[sub_group.name]');
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
				$data['page'] = 'sub_group/index';
                                return $this->load->view('inventory_sep/inventory', $data);
			} else {
				$data['name'] = $this->input->post('name');
				$data['sub_id'] = $this->input->post('account');



				$result = $this->user_model->saveAry('sub_group', $data);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('sub_group');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('sub_group');
				}
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('sub_group');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('sub_group', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('sub_group');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('sub_group');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('sub_group');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');;
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('sub_group', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('sub_group');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('sub_group');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect('sub_group');
		}
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
		$bill_format_status = $this->user_model->getRow('sub_group_status', $where);
		if ($bill_format_status) {
			$staus['status'] = $status;
			$result = $this->user_model->updateAry('sub_group_status', $staus, $where);
		} else {
			$data['bill_id'] = $this->input->post('id');
			$data['status'] = $this->input->post('status');
			$data['comp_id'] = $this->session->userdata('comp_id');
			$data['user_id'] = $this->session->userdata('user_id');
			$result = $this->user_model->saveAry('sub_group_status', $data);
		}
		echo json_encode(['status' => true, 'data' => $result]);
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('sub_group', $where);

		if ($return) {
                    $data['sub_group'] = $return;
                    $data['page'] = 'sub_group/edit';
                    return $this->load->view('inventory_sep/inventory', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('sub_group');
		}
	}
}
