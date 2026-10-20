<?php
defined('BASEPATH') or exit('No direct script access allowed');

class status extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->load->helper('action_helper');
        if ($this->session->userdata('roles') === null) {
            redirect('welcome');
        }
    }
public function insurance_policy()
    {
        $user_id = $this->session->userdata('user_id');
        $crm_id = $this->session->userdata('crm_id');
        $service_id = get_title('crm_servicesubgroup', ['sub_name' => "insurance_policy"], 'id');
        $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
        $data['insurance_policyData'] = $this->user_model->getAry('crm_insurance_policy', '', '', '', $where);
        $this->session->set_userdata('sub_service_id', $service_id);
        $data['page'] = 'insurance_management/insurance_policy/index';
        $this->load->view('crm/main', $data);
    }

    protected function insurance_policy_rules()
    {
        $rules = array(
            array(
                'field' => 'applicant_name',
                'label' => 'Applicant Name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'mobile_number',
                'label' => 'Mobile Number',
                'rules' => 'trim|xss_clean|regex_match[/^[0-9]{10}$/]|min_length[10]'
            ),
            array(
                'field' => 'expiry_date',
                'label' => 'Expiry Date',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'date',
                'label' => 'Date',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|xss_clean|valid_email'
            ),
            array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'type',
                'label' => 'Type',
                'rules' => 'trim|xss_clean|required'
            ),
            array(
                'field' => 'remark',
                'label' => 'Remark',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'ref_by',
                'label' => 'Ref By',
                'rules' => 'trim|xss_clean'
            ),
        );


        return $rules;
    }

    public function insurance_policy_save()
    {

        if ($_POST) {
            $rules = $this->insurance_policy_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['mobile_number'] = $this->input->post('mobile_number');
                $data['applicant_name'] = $this->input->post('applicant_name');
                $data['type'] = $this->input->post('type');
                $data['ref_by'] = $this->input->post('ref_by');
                $data['org_name'] = $this->input->post('org_name');
                $data['email'] = $this->input->post('email');
                $data['remark'] = $this->input->post('remark');
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['expiry_date'] = date('Y-m-d', strtotime($this->input->post('expiry_date')));
                $data['subservice_id'] = $this->session->userdata('sub_service_id');
                $data['user_id'] = $this->session->userdata('user_id');
                $data['status'] = $this->input->post('status');
                $data['crm_id'] = $this->session->userdata('crm_id');
                $result = $this->user_model->saveAry('crm_insurance_policy', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>DSC PROCESS Created</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>Something Went Wrong</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_insurance_policy()
    {

        if ($_POST) {
            $rules = $this->insurance_policy_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $id = $this->input->post('id');
                $data['mobile_number'] = $this->input->post('mobile_number');
                $data['applicant_name'] = $this->input->post('applicant_name');
                $data['type'] = $this->input->post('type');
                $data['ref_by'] = $this->input->post('ref_by');
                $data['org_name'] = $this->input->post('org_name');
                $data['email'] = $this->input->post('email');
                $data['remark'] = $this->input->post('remark');
                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['expiry_date'] = date('Y-m-d', strtotime($this->input->post('expiry_date')));
                $data['status'] = $this->input->post('status');
                $result = $this->user_model->updateAry('crm_insurance_policy', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>DSC Process Updated</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>Something Went Wrong</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_insurance_policy($id)
    {
        if (!empty($id)) {
            $result = $this->user_model->deleteRow('crm_insurance_policy', ['id' => $id]);
            if ($result) {
                $this->session->set_flashdata(['msg' => ' DSC Process Deleted Successfully.', 'class' => 'success']);
                return redirect('crm/master/insurance_policy');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                return redirect('crm/master/insurance_policy');
            }
        }
    }

    public function edit_insurance_policy($id)
    {
        if (!empty($id)) {
            $data['result'] = $this->user_model->getRow('crm_insurance_policy', ['id' => $id]);
            $this->load->view('crm/insurance_management/insurance_policy/edit', $data);
        }
    }

    public function view_insurance_policy($id)
    {
        if (!empty($id)) {
            $data['result'] = $this->user_model->getRow('crm_insurance_policy', ['id' => $id]);
            $this->load->view('crm/insurance_management/insurance_policy/view', $data);
        }
    }
}