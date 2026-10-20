<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcategory extends CI_Controller
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
        $user_id = $this->session->userdata('user_id');
        // $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party_category');
        $this->db->where('user_id', $user_id);
        $data['subcategroy'] = $this->db->get()->result();
        $data['page'] = 'crm_party_category/index';
        $this->load->view('crm/new_main', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => 'name',
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
                $data['category_name'] = $this->input->post('name');
                $data['created_at'] = time();
                $data['user_id'] = $this->session->userdata('user_id');
                $data['crm_id'] = $this->session->userdata('crm_id');
                $result = $this->user_model->saveAry('crm_party_category', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Category Created</h3>"
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


    public function edit($id)
    {
        if (!empty($id)) {
            $data['result'] = $this->user_model->getRow('crm_party_category', ['id' => $id]);
            $this->load->view('crm/crm_party_category/edit', $data);
        }
    }

    public function view($id)
    {
        if (!empty($id)) {
            $data['result'] = $this->user_model->getRow('crm_party_category', ['id' => $id]);
            $this->load->view('crm/crm_party_category/view', $data);
        }
    }

    public function Update()
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
                $id = $this->input->post('id');
                $data['category_name'] = $this->input->post('name');

                $data['created_at'] = time();
                $result = $this->user_model->updateAry('crm_party_category', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Category Updated</h3>"
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
