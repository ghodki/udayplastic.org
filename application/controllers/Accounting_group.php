<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Accounting_group extends CI_Controller
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
                $data['accounting_group'] = $this->user_model->getAry('accounting_group', '', '', '', '');
                $data['page'] = 'accounting_group/index';
                return $this->load->view('inventory_sep/inventory', $data);
            }
	}

	public function groupfind($id = null)
	{
		$state_id = $id;
		// $rownumber = $this->input->post('rownumber');

		$where = ['id' => $state_id];
		$result1 = $this->user_model->getRow('accounting_group', $where);
		// print_r($tax_id);
		// die;
		$result = $this->user_model->getAry('accounting_group', '', '', '', '');
		$html = '';
		$html .= "<div class='col-md-3'><label for='Accounting'>Accounting</label></div>";
		// $html .= "<select class='form-control' onchange='ChangeTax(this.value,$rownumber)' id='tax" . $rownumber . "' name='product_service[$rownumber][tax]' required>";
		$html .= "<select class='form-control' name='group' id='accountinng' class='form-control searchable_select' required='true'>";

		$html .= "<option value='' >-Select-</option>";
		if ($result1 != null) {
			$state_name = $result1->name;

			$html .= "<option value=" . $result1->id . " selected >" . $state_name . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->name . "</option>";
		}
		$html . "</select>";
		// echo '<pre>';
		print_r($html);
		// die;
		// if($html)
		// {
		// 	echo json_encode(['status' => true, 'msg' => 'State Find','data'=>$html]);
		// 	return;
		// }else
		// {
		// 	echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
		// 	return;
		// }
		// print_r($html);
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
		$bill_format_status = $this->user_model->getRow('accountant_status', $where);
		if ($bill_format_status) {
			$staus['status'] = $status;
			$result = $this->user_model->updateAry('accountant_status', $staus, $where);
		} else {
			$data['bill_id'] = $this->input->post('id');
			$data['status'] = $this->input->post('status');
			$data['comp_id'] = $this->session->userdata('comp_id');
			if ($this->session->userdata('BranchRole')) {
				$data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
			}
			$data['user_id'] = $this->session->userdata('user_id');
			$result = $this->user_model->saveAry('accountant_status', $data);
		}
		echo json_encode(['status' => true, 'data' => $result]);
	}

	public function save()
	{
            if ($_POST) {
                $this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[accounting_group.name]');
                $this->form_validation->set_rules('Type', 'Type', 'required|trim');
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
                    $data['page'] = 'accounting_group/index';
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $data['name'] = $this->input->post('name');
                    $data['Type'] = $this->input->post('Type');
                    $result = $this->user_model->saveAry('accounting_group', $data);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Accounting_group');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect(base_url() . 'Accounting_group');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'Accounting_group');
            }
	}

	public function delete($id)
	{
            if (isset($id) && $id != '') {
                $where = ['id' => $id];
                $return = $this->user_model->deleteRow('accounting_group', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Accounting_group');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'Accounting_group');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect(base_url() . 'Accounting_group');
            }
	}

	public function update()
	{
            $id = $this->input->post('id');
            if ($id) {
                $data['name'] = $this->input->post('name');
                $data['Type'] = $this->input->post('Type');
                $where = ['id' => $id];
                $result = $this->user_model->updateAry('accounting_group', $data, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Accounting_group');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'Accounting_group');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);
                return redirect(base_url() . 'Accounting_group');
            }
	}

	public function edit($id)
	{
            $where = ['id' => $id];
            $return = $this->user_model->getRow('accounting_group', $where);
            if ($return) {
                $data['accounting_group'] = $return;
                $data['page'] = 'accounting_group/edit';
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                return redirect(base_url() . 'Accounting_group');
            }
	}
    }