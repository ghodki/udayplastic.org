<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
class Supermaster extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->load->library('ajax_pagination');
        $this->perPage = 10;

        if ($this->session->userdata('user_id') === null) {
            redirect('index');
        }
    }


    // public function service_apply()
    // {

    // }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => 'Name',
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


    public function digital_certificate()
    {
        $data['page'] = 'admin_details/digital_certificate/index';
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {
                $saveData['service_name'] = $this->input->post('name');
                $saveData['sub_heading'] = $this->input->post('sub_heading');
                $saveData['description'] = $this->input->post('description');
                $saveData['created_at'] = date('d-m-Y H:i:s');
                $result = $this->user_model->saveAry('special_services', $saveData);
                $this->session->set_flashdata(['msg' => 'Service Suuccessfully Added !!!', 'class' => 'success']);
                return redirect('digital_certificate');
            }
        }
        $data['service'] = $this->user_model->getResult('special_services');
        $this->load->view('admin', $data);
    }

    protected function scan_rules()
    {
        $rules = array(
            array(
                'field' => 'usb_token',
                'label' => 'Usb Token',
                'rules' => 'trim|required|xss_clean|callback_check_same_entry'
            ),
            array(
                'field' => 'certificate_type',
                'label' => 'Certificate Type',
                'rules' => 'trim|required|xss_clean|callback_check_same_entry'
            ),
            array(
                'field' => 'digital_certificate_id',
                'label' => 'Digital Cerificate',
                'rules' => 'trim|required|xss_clean|callback_check_same_entry'
            ),
            array(
                'field' => 'price',
                'label' => 'Price',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'certificate_validity',
                'label' => 'Certificate Validity',
                'rules' => 'trim|required|xss_clean|callback_check_same_entry'
            ),
            array(
                'field' => 'userType',
                'label' => 'User Type',
                'rules' => 'trim|required|xss_clean|callback_check_same_entry'
            ),


        );
        return $rules;
    }

    public function scan_digital_certificate($id = null)
    {
        $data['page'] = 'admin_details/digital_certificate/module/index';
        if ($_POST) {

            $rules = $this->scan_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {
                $saveData['user_type'] = $this->input->post('userType');
                $saveData['validity'] = $this->input->post('certificate_validity');
                $saveData['amount'] = $this->input->post('price');
                $saveData['usb_token'] = $this->input->post('usb_token');
                $saveData['digital_certificate_id'] = $this->input->post('digital_certificate_id');
                $saveData['certificate_type'] = $this->input->post('certificate_type');
                $saveData['created_at'] = date('d-m-Y H:i:s');
                $result = $this->user_model->saveAry('digital_certificate_subservices', $saveData);
                $this->session->set_flashdata(['msg' => 'Service Suuccessfully Added !!!', 'class' => 'success']);
                redirect('digital_certificate/scan/' . $id);
            }
        }
        $data['result'] = $this->user_model->getRow('special_services', ['id' => $id]);
        $data['digital_certificate_subservices'] = $this->user_model->getAry('digital_certificate_subservices', '', '', '', ['digital_certificate_id' => $id]);
        $this->load->view('admin', $data);
    }


    public function edit_digital_certificate($id = null)
    {
        $data['page'] = 'admin_details/digital_certificate/module/edit';
        if ($_POST) {
            $rules = $this->scan_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {
                $saveData['user_type'] = $this->input->post('userType');
                $saveData['validity'] = $this->input->post('certificate_validity');
                $saveData['amount'] = $this->input->post('price');
                $saveData['usb_token'] = $this->input->post('usb_token');
                $service_id = $this->input->post('service_id');
                $digital_certificate_id = $this->input->post('digital_certificate_id');
                $saveData['certificate_type'] = $this->input->post('certificate_type');
                $saveData['created_at'] = date('d-m-Y H:i:s');
                $result = $this->user_model->updateAry('digital_certificate_subservices', $saveData, ['id' => $service_id]);
                $this->session->set_flashdata(['msg' => 'Service Suuccessfully Added !!!', 'class' => 'success']);
                return redirect('digital_certificate/scan/' . $digital_certificate_id);
            }
        }
        $res = $this->user_model->getRow('digital_certificate_subservices', ['id' => $id]);
        if ($res) {
            $data['result'] = $this->user_model->getRow('special_services', ['id' => $res->digital_certificate_id]);
            $data['subservice'] = $res;
            $this->load->view('admin', $data);
        } else {
            $this->session->set_flashdata(['msg' => 'Something went Wrong!!!', 'class' => 'danger']);
            return redirect('digital_certificate');
        }
    }

    public function editDigitalCertificate($id = null)
    {
        $data['page'] = 'admin_details/digital_certificate/edit';
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {
                $saveData['service_name'] = $this->input->post('name');
                $saveData['sub_heading'] = $this->input->post('sub_heading');
                $saveData['description'] = $this->input->post('description');
                $saveData['created_at'] = date('d-m-Y H:i:s');
                $result = $this->user_model->updateAry('special_services', $saveData, ['id' => $id]);
                $this->session->set_flashdata(['msg' => 'Service Suuccessfully Updated !!!', 'class' => 'success']);
                return redirect('digital_certificate');
            }
        }
        $res = $this->user_model->getRow('special_services', ['id' => $id]);
        if ($res) {
            $data['result'] = $res;
            $this->load->view('admin', $data);
        } else {
            $this->session->set_flashdata(['msg' => 'Something went Wrong!!!', 'class' => 'danger']);
            return redirect('digital_certificate');
        }
    }



    public function delete($id)
    {
        $where = ['id' => $id];

        $return = $this->user_model->deleteRow('service', $where);
        if ($return) {
            $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
            redirect('service');
        } else {
            $data['page'] = 'admin_details/userlist';
            $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
            redirect('service');
        }
    }

    public function check_same_entry()
    {
        if (!empty($this->input->post('service_id'))) {
            $service_id = $this->input->post('service_id');
        } else {
            $service_id = '';
        }
        $user_type = $this->input->post('userType');
        $validity = $this->input->post('certificate_validity');
        $usb_token = $this->input->post('usb_token');
        $digital_certificate_id = $this->input->post('digital_certificate_id');
        $certificate_type = $this->input->post('certificate_type');
        $where = ['user_type' => $user_type, 'validity' => $validity, 'usb_token' => $usb_token, 'digital_certificate_id' => $digital_certificate_id, 'certificate_type' => $certificate_type, 'id!=' => $service_id];
        $return_value = $this->user_model->getRow('digital_certificate_subservices', $where);
        if ($return_value) {
            $this->form_validation->set_message('check_same_entry', 'Same Entry can not be again!! ');
            return false;
        }
        return true;
    }

    public function digitalSignatureApply()
    {

        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('roles');
        $where = [];
        $where = ['digital_certificate_id' => 1];
        $data = array();
        // Get record count 
        $conditions['returnType'] = 'count';
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('roles');
        if ($role_id != 1) {
            $conditions['where'] = ['user_id' => $user_id];
        }
        $totalRec = $this->user_model->getRows($conditions);
        // Pagination configuration 
        $config['target']      = '#dataList';
        $config['base_url']    = base_url('supermaster/ajaxPaginationData');
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);
        // Get records 
        $conditions['limit'] = $this->perPage;
        if ($role_id != 1)
            $conditions['where'] = ['user_id' => $user_id];
        $data['result'] = $this->user_model->getRows($conditions);
        // Load the list page view
        $data['page'] = 'admin_details/digital_certificate/applied/index';
        $this->load->view('admin', $data);
    }

    function ajaxPaginationData()
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
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('roles');
        if ($role_id != 1) {
            $conditions['where'] = ['user_id' => $user_id];
        }
        // Get record count 
        $conditions['returnType'] = 'count';
        if (!empty($keywords)) {
            $conditions['search']['keywords'] = $keywords;
        }
        if (!empty($sortBy)) {
            $conditions['search']['sortBy'] = $sortBy;
        }
        $totalRec = $this->user_model->getRows($conditions);

        // Pagination configuration 
        $config['target']      = '#dataList';
        $config['base_url']    = base_url('supermaster/ajaxPaginationData');
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $pagelength;
        $config['link_func']   = 'searchFilter';
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);
        // Get records 
        $conditions['start'] = $offset;
        $conditions['limit'] = $pagelength;
        unset($conditions['returnType']);
        $data['result'] = $this->user_model->getRows($conditions);
        // Load the data list view 
        $this->load->view('admin_details/digital_certificate/applied/data', $data, false);
    }

    public function viewDigitalSignature($id = null)
    {
        $data['page'] = 'admin_details/digital_certificate/applied/view';
        $result = $this->user_model->getRow('digital_certificate_apply', ['id' => $id]);
        if ($result) {
            $data['result'] = $result;
            $this->load->view('admin', $data);
        } else {
            $this->session->set_flashdata(['msg' => 'something went wrong', 'class' => 'danger']);
            return redirect('digitalSignatureApply');
        }
    }

    public function approve_digital_signature()
    {
        $service_id = $this->input->post('service_id');
        $result = $this->user_model->getRow('digital_certificate_apply', ['id' => $service_id]);
        if ($result) {
            $data['status'] = 2;
            $data['assignBy'] = $this->session->userdata('user_id');
            $data['approvalDate'] = date('d-m-Y H:i:s');
            $this->user_model->updateAry('digital_certificate_apply', $data, ['id' => $service_id]);
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

    public function decline_digital_signature()
    {
        $service_id = $this->input->post('service_id');
        $result = $this->user_model->getRow('digital_certificate_apply', ['id' => $service_id]);
        if ($result) {
            $data['status'] = 4;
            $data['approvalDate'] = date('d-m-Y H:i:s');
            $data['assignBy'] = $this->session->userdata('user_id');
            $this->user_model->updateAry('digital_certificate_apply', $data, ['id' => $service_id]);
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

    public function UpdateDigitalSignature()
    {
        $service_id = $this->input->post('service_id');
        $result = $this->user_model->getRow('digital_certificate_apply', ['id' => $service_id]);
        if ($result) {
            $data['update_at'] = date('d-m-Y H:i:s');
            if ($_FILES['org_doc']['name'] != '') {
                $path = 'uploads/digital_signature/org_doc/' . $result->org_doc;
                if (file_exists($path)) {
                    unlink($path);
                }
                $file_name = time() . $_FILES['org_doc']['name'];
                move_uploaded_file($_FILES['org_doc']['tmp_name'], 'uploads/digital_signature/org_doc/' . $file_name);
                $data['org_doc'] = $file_name;
            }
            if ($_FILES['aadhar']['name'] != '') {
                $path = 'uploads/digital_signature/aadhar/' . $result->aadhar;
                if (file_exists($path)) {
                    unlink($path);
                }
                $file_name = time() . $_FILES['aadhar']['name'];
                move_uploaded_file($_FILES['aadhar']['tmp_name'], 'uploads/digital_signature/aadhar/' . $file_name);
                $data['aadhar'] = $file_name;
            }
            if ($_FILES['pancard']['name'] != '') {
                $path = 'uploads/digital_signature/pancard/' . $result->pancard;
                if (file_exists($path)) {
                    unlink($path);
                }
                $file_name = time() . $_FILES['pancard']['name'];
                move_uploaded_file($_FILES['pancard']['tmp_name'], 'uploads/digital_signature/pancard/' . $file_name);
                $data['pancard'] = $file_name;
            }
             if ($_FILES['ice_certificate']['name'] != '') {
                $path = 'uploads/digital_signature/ice_certificate/' . $result->ice_certificate;
                if (file_exists($path)) {
                    unlink($path);
                }
                $file_name = time() . $_FILES['ice_certificate']['name'];
                move_uploaded_file($_FILES['ice_certificate']['tmp_name'], 'uploads/digital_signature/ice_certificate/' . $file_name);
                $data['ice_certificate'] = $file_name;
            }
            $this->user_model->updateAry('digital_certificate_apply', $data, ['id' => $service_id]);
            $response = array(
                'status' => true,
                'message' => "Files Updated!!"
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

    public function unlinkimage($path = null)
    {
        if ($path) {
            unlink($path);
        }
        return true;
    }
}
