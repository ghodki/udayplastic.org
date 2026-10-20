<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ServiceSubGroup extends CI_Controller
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

    public function index()
    {
        $this->db->select('*');
        $this->db->from('crm_servicesubgroup');
        $data['crm_servicesubgroup'] = $this->db->get()->result();
        $data['page'] = 'serviceSubGroup/index';
        $this->load->view('crm/new_main', $data);
    }

    public function PageShow($pagename)
    {
        $this->load->view('crm/serviceSubGroup/' . $pagename);
    }

    public function active_user()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_serviceSubGroup');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 1);
        $data['serviceSubGroupData'] = $this->db->get()->result();
        $data['page'] = 'serviceSubGroup/active';
        $this->load->view('crm/new_main', $data);
    }

    public function deactiveuser()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_serviceSubGroup');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 0);
        $data['serviceSubGroupData'] = $this->db->get()->result();
        // $data['page'] = 'serviceSubGroup/deactivate';
        $this->load->view('crm/serviceSubGroup/deactivate', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'sub_service_name',
                'label' => 'Sub-Service Name',
                'rules' => 'trim|required|xss_clean|callback_unique_serviceSubGroup'
            ),
            array(
                'field' => 'service',
                'label' => 'Service',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'charge',
                'label' => 'Charge Amount',
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'trim|required|numeric'
            ),
        );
        return $rules;
    }

    protected function update_rules()
    {
        $rules = array(
            array(
                'field' => 'sub_service_name',
                'label' => 'Sub-Service Name',
                'rules' => 'trim|required|xss_clean|callback_unique_serviceSubGroup_update'
            ),
            array(
                'field' => 'service',
                'label' => 'Service',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'charge',
                'label' => 'Charge Amount',
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'id',
                'label' => 'id',
                'rules' => 'trim|required|xss_clean|numeric'
            ),

        );
        return $rules;
    }


    protected function change_status_rules()
    {
        $rules = array(
            array(
                'field' => 'serviceSubGroup_id',
                'label' => 'serviceSubGroup',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'trim|required|xss_clean'
            ),




        );
        return $rules;
    }

    public function save()
    {

        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['sub_service_name'] = $this->input->post('sub_service_name');
                $data['sub_name'] = $this->input->post('sub_name');
                $data['status'] = $this->input->post('status');
                $data['service_id'] = $this->input->post('service');
                $data['service_charge'] = $this->input->post('charge');
                $data['created_at'] = time();
                $result = $this->user_model->saveAry('crm_servicesubgroup', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Sub-Services Created</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>Sub-Services Could not be Created</h3>"
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


    public function edit_serviceSubGroup($id)
    {
        $data['result'] = $this->user_model->getRow('crm_servicesubgroup', ['id' => $id]);
        $this->load->view('crm/serviceSubGroup/edit', $data);
    }

    public function view_serviceSubGroup($id)
    {
        if ($id) {
            $data['result'] = $this->user_model->getRow('crm_servicesubgroup', ['id' => $id]);
            $this->load->view('crm/serviceSubGroup/view', $data);
        }
    }

    public function update_serviceSubGroup()
    {

        if ($_POST) {
            $rules = $this->update_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $id = $this->input->post('id');
                $data['sub_name'] = $this->input->post('sub_name');
                $data['sub_service_name'] = $this->input->post('sub_service_name');
                $data['service_id'] = $this->input->post('service');
                $data['service_charge'] = $this->input->post('charge');
                $data['status'] = $this->input->post('status');
                $result = $this->user_model->updateAry('crm_servicesubgroup', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Sub-Service Updated</h3>"
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

    public function changeStatus()
    {

        if ($_POST) {
            $rules = $this->change_status_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['status'] = $this->input->post('status');
                $id = $this->input->post('serviceSubGroup_id');
                $result = $this->user_model->updateAry('crm_servicesubgroup', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Sub-Service Updated</h3>"
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

    public function unique_serviceSubGroup()
    {
        $serviceSubGroup_name = $this->input->post('sub_service_name');
        $where = ['sub_service_name' => $serviceSubGroup_name];
        $return_value = $this->user_model->getRow('crm_servicesubgroup', $where);
        if ($return_value) {
            $this->form_validation->set_message('unique_serviceSubGroup', 'Sub-Services should be Unique');
            return false;
        }
        return true;
    }

    public function unique_serviceSubGroup_update()
    {
        $serviceSubGroup_name = $this->input->post('sub_service_name');
        $id = $this->input->post('id');
        $where = ['sub_service_name' => $serviceSubGroup_name, 'id!=' => $id];
        $return_value = $this->user_model->getRow('crm_servicesubgroup', $where);
        // print_r($return_value);
        if ($return_value) {
            $this->form_validation->set_message('unique_serviceSubGroup_update', 'Sub-Services should be Unique');
            return false;
        }
        return true;
    }
}
