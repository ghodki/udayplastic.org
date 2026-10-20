<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Branches extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->model('user_model');
        if ($this->session->userdata('roles') === null) {
            return redirect(base_url() . 'index');
        }
    }

    public function index() {
        $data['Branches'] = $this->user_model->getResult('branches');
        $data['page'] = 'admin_details/Branches/index';
        $this->load->view('admin', $data);
    }

    public function Save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('Title', 'Title', 'trim|required');
            $this->form_validation->set_rules('Phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('Address', 'Address', 'trim|required');
            if ($this->form_validation->run() == false) {
                $data['Branches'] = $this->user_model->getResult('branches');
                $data['page'] = 'admin_details/Branches/index';
                $data['error'] = validation_errors();
                return $this->load->view('admin', $data);
            } else {
                $BranchData = [
                    'Date' => date('d-m-Y'),
                    'Title' => $this->input->post('Title'),
                    'Phone' => $this->input->post('Phone'),
                    'Address' => $this->input->post('Address'),
                    'Location' => $this->input->post('Location')
                ];

                $Result = $this->user_model->saveAry('branches', $BranchData);
                if (!empty($Result)) {
                    $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Branches');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Branches');
                }
            }
        }
    }

    public function Edit($Id) {
        if (!empty($Id)) {
            if ($this->db->where('Id', $Id)->get('branches')->num_rows()) {
                $Data['BranchId'] = $Id;
                $Data['BranchData'] = $this->db->where('Id', $Id)->get('branches')->row();
                $Data['page'] = 'admin_details/Branches/edit';
                return $this->load->view('admin', $Data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Branches');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Branch id is missing.', 'class' => 'danger']);
            return redirect(base_url() . 'Branches');
        }
    }

    public function Update($Id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('Title', 'Title', 'trim|required');
            $this->form_validation->set_rules('Phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('Address', 'Address', 'trim|required');
            if ($this->form_validation->run() == false) {
                $Data['BranchId'] = $Id;
                $Data['BranchData'] = $this->db->where('Id', $Id)->get('branches')->row();
                $Data['page'] = 'admin_details/Branches/edit';
                $Data['error'] = validation_errors();
                return $this->load->view('admin', $Data);
            } else {
                $BranchData = [
                    'Title' => $this->input->post('Title'),
                    'Phone' => $this->input->post('Phone'),
                    'Address' => $this->input->post('Address'),
                    'Location' => $this->input->post('Location')
                ];

                $UpdateWhere = ['Id' => $this->input->post('BranchId')];
                $Result = $this->user_model->updateAry('branches', $BranchData, $UpdateWhere);

                if (!empty($Result)) {
                    $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Branches');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Branches/Edit/' . $Id);
                }
            }
        }
    }

    public function Status($Id) {
        if ($Id) {
            if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                $Branch = $this->user_model->getRow('branches', ['Id' => $Id]);
                if ($Branch) {
                    if ($Branch->Status == 1) {
                        $Status = 0;
                    } else {
                        $Status = 1;
                    }

                    $Result = $this->user_model->updateAry('branches', ['Status' => $Status], ['Id' => $Id]);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Branches');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Branches');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'Branches');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                return redirect(base_url() . 'Branches');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
            return redirect(base_url() . 'Branches');
        }
    }

    public function Delete($id) {
        if (isset($id) && $id != '') {
            $where = ['Id' => $id];

            $return = $this->user_model->deleteRow('branches', $where);
            if ($return) {
                $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                return redirect(base_url() . 'Branches');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'Branches');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
            return redirect(base_url() . 'Branches');
        }
    }
}
