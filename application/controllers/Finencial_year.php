<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Finencial_year extends CI_Controller
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
                $data['year'] = $this->user_model->getResult('year');
                $data['page'] = 'finencial_year/index';
                return $this->load->view('inventory_sep/inventory', $data);
            }
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

	public function change_financial_year()
	{
		$year_id = $this->input->post('year');
		if ($year_id) {
			$this->session->set_userdata('financial_year', $year_id);

			$msg = "Financial Year Changed";
			echo json_encode(['status' => true, 'msg' => $msg]);
		} else {
			$msg = "Financial Year Can Not Be Empty";
			echo json_encode(['status' => false, 'msg' => $msg]);
		}
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
				redirect(base_url() . 'Finencial_year');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect(base_url() . 'Finencial_year');
			}
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('year', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect(base_url() . 'Finencial_year');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect(base_url() . 'Finencial_year');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect(base_url() . 'Finencial_year');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');;
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('year', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect(base_url() . 'Finencial_year');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect(base_url() . 'Finencial_year');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect(base_url() . 'Finencial_year');
		}
	}

	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('year', $where);

		if ($return) {
			$data['finencial_year'] = $return;
			$data['page'] = 'admin_details/finencial_year/edit';
			$this->load->view('admin', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect(base_url() . 'Finencial_year');
		}
	}
}
