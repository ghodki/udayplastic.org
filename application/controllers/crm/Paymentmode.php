<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paymentmode extends CI_Controller
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
        $this->db->from('crm_paymentmode');
        $data['crm_paymentmode'] = $this->db->get()->result();
        $data['page'] = 'paymentmode/index';
        $this->load->view('crm/new_main', $data);
    }

 

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'paymentmode_name',
                'label' => 'paymentmode_name',
                'rules' => 'trim|required|xss_clean|callback_unique_paymentmode'
            ),
        );
        return $rules;
    }

    protected function update_rules()
    {
        $rules = array(
            array(
                'field' => 'paymentmode_name',
                'label' => 'paymentmode_name',
                'rules' => 'trim|required|xss_clean|callback_unique_paymentmode_update'
            ),
            array(
                'field' => 'id',
                'label' => 'id',
                'rules' => 'trim|required|xss_clean'
            ),

        );
        return $rules;
    }


    protected function change_paymentmode_rules()
    {
        $rules = array(
            array(
                'field' => 'paymentmode_id',
                'label' => 'paymentmode',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'paymentmode',
                'label' => 'paymentmode',
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
                    'paymentmode' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['name'] = $this->input->post('paymentmode_name');
                $data['created_at'] = time();
                $result = $this->user_model->saveAry('crm_paymentmode', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>paymentmode Created</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>paymentmode Could not be Created</h3>"
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


    public function edit_paymentmode($id)
    {
        $data['result'] = $this->user_model->getRow('crm_paymentmode', ['id' => $id]);
        // $data['page'] = 'paymentmode/edit';

        $this->load->view('crm/paymentmode/edit', $data);
    }

    public function view_paymentmode($id)
    {
        if ($id) {
            $data['result'] = $this->user_model->getRow('crm_paymentmode', ['id' => $id]);
            $this->load->view('crm/paymentmode/view', $data);
        }
    }

    public function update_paymentmode()
    {

        if ($_POST) {
            $rules = $this->update_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'paymentmode' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $id = $this->input->post('id');
                $data['name'] = $this->input->post('paymentmode_name');
                $result = $this->user_model->updateAry('crm_paymentmode', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>paymentmode Updated</h3>"
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

   

    public function unique_paymentmode()
    {
        $paymentmode_name = $this->input->post('paymentmode_name');
        $where = ['name' => $paymentmode_name];
        $return_value = $this->user_model->getRow('crm_paymentmode', $where);
        if ($return_value) {
            $this->form_validation->set_message('unique_paymentmode', 'paymentmode should be Unique');
            return false;
        }
        return true;
    }

    public function unique_paymentmode_update()
    {
        $paymentmode_name = $this->input->post('paymentmode_name');
        $id = $this->input->post('id');
        $where = ['name' => $paymentmode_name, 'id!=' => $id];
        $return_value = $this->user_model->getRow('crm_paymentmode', $where);
        // print_r($return_value);
        if ($return_value) {
            $this->form_validation->set_message('unique_paymentmode_update', 'paymentmode should be Unique');
            return false;
        }
        return true;
    }

   

    protected function change_status_rules()
    {
        $rules = array(
            array(
                'field' => 'paymentmode_id',
                'label' => 'Paymentmode Id',
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
                $id = $this->input->post('paymentmode_id');
                $result = $this->user_model->updateAry('crm_paymentmode', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Payment Mode Updated</h3>"
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

}
