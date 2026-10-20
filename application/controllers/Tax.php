<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Tax extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('user_model');

            if ($this->session->userdata('RoleType')) {
                if (empty($this->session->userdata('UserId'))) {
                    return redirect(base_url() . 'index');
                }
            } else {
                if (empty($this->session->userdata('user_id'))) {
                    return redirect(base_url() . 'index');
                }                
            }
	}

	public function index()
	{
            if ($this->session->userdata('user_id') === null) {
                return $this->load->view('welcome_message');
            } else {
                $data['error'] = '';
                $data['tax'] = $this->user_model->getAry('tax', '', '', '', '');
                $data['page'] = 'tax/index';
                return $this->load->view('inventory_sep/inventory', $data);
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
		// print_r($_POST);
		// die;
		if ($_POST) {
			// $rules = $this->rules();
			// $this->form_validation->set_rules($rules);
			$this->form_validation->set_rules('name', 'Tax Name', 'required|trim|is_unique[tax.name]');
			$this->form_validation->set_rules('igst', 'IGST', 'required|trim');
			$this->form_validation->set_rules('cgst', 'CGST', 'required|trim');
			$this->form_validation->set_rules('sgst', 'SGST', 'required|trim');
			$this->form_validation->set_rules('cess', 'CESS', 'required|trim');
			$this->form_validation->set_rules('tax_per', 'Tax Per', 'required|trim');



			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error bg-danger">', '</div>');
				$data['page'] = 'admin_details/tax/index';
				$this->load->view('admin', $data);
			} else {
				$data['name'] = $this->input->post('name');
				$data['tax_per'] = $this->input->post('tax_per');
				$data['igst'] = $this->input->post('igst');
				$data['cgst'] = $this->input->post('cgst');
				$data['sgst'] = $this->input->post('sgst');
				$data['cess'] = $this->input->post('cess');


				$result = $this->user_model->saveAry('tax', $data);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('tax');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('tax');
				}
			}
		} else {
			// $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('tax');
		}
	}

	public function gettax()
	{
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $TaxId = $this->input->post('id');
                $RowNumber = $this->input->post('rownumber');
                $Result = $this->db->get('tax')->result();
                $HTML = "";
                $HTML .= "<select style='width: 70px;' class='form-control' onchange='ChangeTax(this.value, $RowNumber);' id='tax$RowNumber' name='product[$RowNumber][tax]' required=''>";
                foreach ($Result as $key => $TaxVal) {
                    $Selected = $TaxId == $TaxVal->id ? 'selected' : '';
                    $HTML .= "<option " . $Selected .  " value=" . $TaxVal->tax_per . ">" . $TaxVal->tax_per . "</option>";
                }
                $HTML .= "</select>";
                print_r($HTML);
            }
	}

        public function getTaxesForGettingInPreview()
        {
            $tax_number = $this->input->post('id');
            $rownumber = $this->input->post('rownumber');
            $result = $this->user_model->getAry('tax', '', '', '', '', '');
            $html = '';
            $html .= "<select style='width: 70px;' class='form-control' onchange='ChangeTax(this.value, $rownumber);' id='tax$rownumber' name='product[$rownumber][tax]' required=''>";
            if ($tax_number != null) {
                $tax_per = round($tax_number, 0);
                $html .= "<option value=" . $tax_per . " selected>" . $tax_per . "</option>";
            } else {
                $tax_per = 0;
            }
            foreach ($result as $key => $dist_val) {
                if ($tax_per != $dist_val->tax_per) {
                    $html .= "<option value=" . $dist_val->tax_per . ">" . $dist_val->tax_per . "</option>";
                }
            }
            $html .= "</select>";

            print_r($html);
        }

	public function gettaxs()
	{
		$tax_id = $this->input->post('id');
		$rownumber = $this->input->post('rownumber');

		$where = ['id' => $tax_id];
		$result1 = $this->user_model->getRow('tax', $where);
		// print_r($tax_id);
		// die;
		$result = $this->user_model->getAry('tax', '', '', '', '');
		$html = '';
		$html .= "<div class='col-md-3'><label for='tax'>TAX</label></div>";

		$html .= "<select class='form-control'  id='tax$rownumber' name='tax' required>";

		// $html .= "<option value='' >-Select-</option>";
		if ($result1 != null) {
			$tax_per = $result1->tax_per;

			$html .= "<option value=" . $result1->id . " selected >" . $tax_per . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->tax_per . "</option>";
		}
		$html . "</select>";

		print_r($html);
	}

	public function getunit()
	{
		$tax_id = $this->input->post('id');
		$rownumber = $this->input->post('rownumber');

		$where = ['id' => $tax_id];
		$result1 = $this->user_model->getRow('unit', $where);
		// print_r($tax_id);
		// die;
		$result = $this->user_model->getAry('unit', '', '', '', '');
		$html = '';
		$html .= "<div class='col-md-3'><label for='unit'>UNIT</label></div>";

		$html .= "<select class='form-control' onchange='changeunit(this.value,$rownumber)' id='unit$rownumber' name='unit' required>";

		// $html .= "<option value='' >-Select-</option>";
		if ($result1 != null) {
			$tax_per = $result1->name;

			$html .= "<option value=" . $result1->id . " selected >" . $tax_per . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->name . "</option>";
		}
		$html . "</select>";

		print_r($html);
	}
	public function gettaxqty()
	{
		$tax_id = $this->input->post('id');
		$rownumber = $this->input->post('rownumber');

		$where = ['id' => $tax_id];
		$result1 = $this->user_model->getRow('tax', $where);
		if (isset($result1) && !empty($result)) {
			echo json_encode(['status' => true, 'data' => $result]);
		} else {
			echo json_encode(['status' => false, 'data' => 0]);
		}
		// print_r($tax_id);
		// die;
		// $result = $this->user_model->getAry('tax', '', '', '', '');
		// $html = '';
		// $html .= "<select style='width:81px;' class='form-control' onchange='ChangeTax(this.value,$rownumber)' id='tax$rownumber' name='product[$rownumber][tax]' required>";

		// // $html .= "<option value='' >-Select-</option>";
		// if ($result1 != null) {
		// 	$tax_per = $result1->tax_per;

		// 	$html .= "<option value=" . $tax_per . " selected >" . $tax_per . "</option>";
		// }
		// foreach ($result as $key => $dist_val) {
		// 	$html .= "<option value=" . $dist_val->tax_per . " >" . $dist_val->tax_per . "</option>";
		// }
		// $html . "</select>";

		// print_r($html);
	}
	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('tax', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('tax');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('tax');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('tax');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('name');
			$data['tax_per'] = $this->input->post('tax_per');
			$data['igst'] = $this->input->post('igst');
			$data['cgst'] = $this->input->post('cgst');
			$data['sgst'] = $this->input->post('sgst');
			$data['cess'] = $this->input->post('cess');
			$where = ['id' => $id];

			$result = $this->user_model->updateAry('tax', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('tax');
			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('tax');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

			redirect('tax');
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
		if ($this->session->userdata('BranchRole')) {
			$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
		}
		$bill_format_status = $this->user_model->getRow('tax_status', $where);
		if ($bill_format_status) {
			$staus['status'] = $status;
			$result = $this->user_model->updateAry('tax_status', $staus, $where);
		} else {
			$data['bill_id'] = $this->input->post('id');
			$data['status'] = $this->input->post('status');
			$data['comp_id'] = $this->session->userdata('comp_id');
			$data['user_id'] = $this->session->userdata('user_id');
			if ($this->session->userdata('BranchRole')) {
				$data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
			}
			$result = $this->user_model->saveAry('tax_status', $data);
		}
		echo json_encode(['status' => true, 'data' => $result]);
	}
	public function edit($id)
	{
		$where = ['id' => $id];
		$return = $this->user_model->getRow('tax', $where);

		if ($return) {
			$data['tax'] = $return;
			$data['page'] = 'tax/edit';
			$this->load->view('inventory_sep/inventory', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
			redirect('tax');
		}
	}
}
