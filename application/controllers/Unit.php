<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Unit extends CI_Controller
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
            if ($this->session->userdata('RoleType')) {
                if (empty($this->session->userdata('UserId'))) {
                    return redirect(base_url() . 'index');
                } else {
                    return redirect(base_url() . 'Invent/unit');
                }
            } else {
                if (empty($this->session->userdata('user_id'))) {
                    return redirect(base_url() . 'index');
                } else {
                    return redirect(base_url() . 'Invent/unit');
                }
            }
	}

	public function changeData()
	{ 
		$bill_id = $this->input->post('id');
		$status = $this->input->post('status');
		$comp_id = $this->session->userdata('comp_id');
		$user_id = $this->session->userdata('user_id');
		$where = ['bill_id' => $bill_id, 'comp_id' => $comp_id, 'user_id' => $user_id];
		if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
		}
		$bill_format_status = $this->user_model->getRow('unit_status', $where);
		if ($bill_format_status) {
			$staus['status'] = $status;
			$result = $this->user_model->updateAry('unit_status', $staus, $where);
		} else {
			$data['bill_id'] = $this->input->post('id');
			$data['status'] = $this->input->post('status');
			$data['comp_id'] = $this->session->userdata('comp_id');
			$data['user_id'] = $this->session->userdata('user_id');
			if ($this->session->userdata('BranchRole')) {
                $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
			$result = $this->user_model->saveAry('unit_status', $data);
		}
		echo json_encode(['status' => true, 'data' => $result]);
	}

	public function save()
	{
		if ($_POST) {
			// $rules = $this->rules();

			// $this->form_validation->set_rules($rules);
			$this->form_validation->set_rules('unit_name', 'Name', 'required|trim|is_unique[unit.name]');
			$this->form_validation->set_rules('uqc_code', 'UQC CODE', 'required|trim|is_unique[unit.uqc_code]');


			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error bg-danger">', '</div>');
				$data['page'] = 'admin_details/unit/index';
				$this->load->view('admin', $data);
			} else {

				$data['name'] = $this->input->post('unit_name');
				$data['uqc_code'] = $this->input->post('uqc_code');

				$result = $this->user_model->saveAry('unit', $data);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('unit');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('unit');
				}
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('unit');
		}
	}

	public function delete($id)
	{
		if (isset($id) && $id != '') {
			$where = ['id' => $id];

			$return = $this->user_model->deleteRow('unit', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('unit');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('unit');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('unit');
		}
	}


	public function update()
	{
		$id = $this->input->post('id');

		if ($id) {
			$data['name'] = $this->input->post('unit_name');
			$data['uqc_code'] = $this->input->post('uqc_code');
			$where = ['id' => $id];
			$result = $this->user_model->updateAry('unit', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				redirect('unit');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('unit');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);
			redirect('unit');
		}
	}

	public function edit($id)
	{
            $where = ['id' => $id];
            $return = $this->user_model->getRow('unit', $where);
            if ($return) {
                $data['unit'] = $return;
                $data['page'] = 'unit_edit';
                $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                redirect('unit');
            }
	}
        
        public function getUnitName ()
        {
            if ($_POST) {
                if (!empty($this->input->post('unit_id'))) {
                    $id = $this->input->post('unit_id');
                    echo get_title('unit', ['id' => $id], 'name');
                }
            }
        }
    }