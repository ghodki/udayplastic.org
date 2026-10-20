<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->load->library('ajax_pagination');
        $this->perPage = 10;
        if ($this->session->userdata('roles') === null) {
            redirect('welcome');
        }
    }

    protected function category_rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => 'Category Name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|xss_clean'
            ),

        );
        return $rules;
    }

    public function loan_category()
    {

        if ($_POST) {
            $rules = $this->category_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $data['validation'] = validation_errors();
            } else {
                $file_name = $_FILES['image']['name'];
                if ($file_name) {
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/loan_category/' . $file_name);
                    $data['image'] = $file_name;
                }
                $data['loan_name'] = $this->input->post('title');
                $data['description'] = $this->input->post('description');
                $data['created_at'] = date('Y-m-d h:i:s');
                $result = $this->user_model->saveAry('loan_category', $data);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Category added successfully.', 'class' => 'success']);
                    return redirect('loan_category');
                } else {
                    $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
                    return redirect('loan_category');
                }
            }
        }
        $data['loan_category'] = $this->user_model->getResult('loan_category');
        $data['page'] = 'admin_details/loan_category/index';
        $this->load->view('admin', $data);
    }

    public function loan_category_edit($id = null)
    {
        if (isset($id) && !empty($id)) {
            $where = ['id' => $id];
            $return = $this->user_model->getRow('loan_category', $where);
            if ($_POST) {
                $rules = $this->category_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['validation'] = validation_errors();
                } else {
                    $file_name = $_FILES['image']['name'];
                    if ($file_name) {
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/loan_category/' . $file_name);
                        $data['image'] = $file_name;
                    }
                    $data['loan_name'] = $this->input->post('title');
                    $data['description'] = $this->input->post('description');
                    $data['updated_at'] = date('Y-m-d h:i:s');
                    $result = $this->user_model->updateAry('loan_category', $data, ['id' => $id]);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Category Updated successfully.', 'class' => 'success']);
                        return redirect('loan_category');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
                        return redirect('loan_category');
                    }
                }
            }
            if ($return) {
                $data['result'] = $return;
                $data['page'] = 'admin_details/loan_category/edit';
                $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                return redirect('loan_category');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
            return redirect('loan_category');
        }
    }


    public function loan_change_status()
    {
        $id = $this->input->post('table_id');
        $status = $this->input->post('status');
        $data['status'] = $status;
        $result = $this->user_model->updateAry('loan_category', $data, ['id' => $id]);
        if (isset($result) && !empty($result)) {
            echo json_encode(['status' => true, 'message' => 'Category Status Updated successfully.']);
            return;
        } else {
            echo json_encode(['status' => false, 'message' => 'There is some issue, Please try again.']);
            return;
        }
    }


    public function loan_request()
    {


        $data = array();
        // Get record count 
        $conditions['returnType'] = 'count';
        $totalRec = $this->user_model->getRows($conditions);
        // Pagination configuration 
        $config['target']      = '#dataList';
        $config['base_url']    = base_url('loan/loan_list');
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);
        // Get records 
        $conditions['limit'] = $this->perPage;
        $data['result'] = $this->user_model->getRowsTables('loan_apply', $conditions);
        // Load the list page view
        $data['page'] = 'admin_details/loan/index';
        $this->load->view('admin', $data);
    }

    function loan_list()
    {
        // Define offset 
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }

        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        $pagelength = $this->input->post('pagelength');
        // Get record count 
        $conditions['returnType'] = 'count';
        if (!empty($keywords)) {
            $conditions['search']['keywords'] = $keywords;
        }
        if (!empty($sortBy)) {
            $conditions['search']['sortBy'] = $sortBy;
        }
        $totalRec = $this->user_model->getRowsTables('loan_apply', $conditions);

        // Pagination configuration 
        $config['target']      = '#dataList';
        $config['base_url']    = base_url('loan/loan_list');
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $pagelength;
        $config['link_func']   = 'searchFilter';
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);
        // Get records 
        $conditions['start'] = $offset;
        $conditions['limit'] = $pagelength;
        unset($conditions['returnType']);
        $data['result'] = $this->user_model->getRowsTables('loan_apply', $conditions);
        // Load the data list view 
        $this->load->view('admin_details/loan/loan_list', $data, false);
    }

    public function approve_loan()
    {
        $service_id = $this->input->post('service_id');
        $result = $this->user_model->getRow('loan_apply', ['id' => $service_id]);
        if ($result) {
            $data['status'] = 2;
            $data['updated_by'] = $this->session->userdata('user_id');
            $this->user_model->updateAry('loan_apply', $data, ['id' => $service_id]);
            $response = array(
                'status' => true,
                'message' => "Request Approved!"
            );
        } else {
            $response = array(
                'status' => true,
                'message' => "Something Went Wrong"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function decline_loan()
    {
        $service_id = $this->input->post('service_id');
        $result = $this->user_model->getRow('loan_apply', ['id' => $service_id]);
        if ($result) {
            $data['status'] = 4;
            $data['updated_by'] = $this->session->userdata('user_id');
            $this->user_model->updateAry('loan_apply', $data, ['id' => $service_id]);
            $response = array(
                'status' => true,
                'message' => "Request Declined!"
            );
        } else {
            $response = array(
                'status' => true,
                'message' => "Something Went Wrong"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
