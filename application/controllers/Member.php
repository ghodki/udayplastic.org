<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Member extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);
            $this->load->model('user_model');
            if ($this->session->userdata('roles') === null) {
                return redirect(base_url() . 'index');
            }
	}

	public function index()
	{
            $data['Members'] = $this->user_model->getResult('members');
            $data['page'] = 'admin_details/Member/index';
            $this->load->view('admin', $data);
	}

	public function Create()
	{
            $data['page'] = 'admin_details/Member/create';
            $this->load->view('admin', $data);
	}
        
        public function Save() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('FirstName', 'First Name', 'trim|required');
                $this->form_validation->set_rules('LastName', 'Last Name', 'trim|required');
                $this->form_validation->set_rules('Designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('Position', 'Position', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['page'] = 'admin_details/Member/create';
                    $data['error'] = validation_errors();
                    return $this->load->view('admin', $data);
                } else {
                    if ($_FILES['Photograph']['name']) {
                        $PhotographFileName = time() . $_FILES['Photograph']['name'];
                        move_uploaded_file($_FILES['Photograph']['tmp_name'], 'uploads/Member/' . $PhotographFileName);
                        $Photograph = $PhotographFileName;
                    } else {
                        $Photograph = "";
                    }
                    $MemberData = [
                        'FirstName' => $this->input->post('FirstName'),
                        'LastName' => $this->input->post('LastName'),
                        'Designation' => $this->input->post('Designation'),
                        'Mobile' => $this->input->post('Mobile'),
                        'Email' => $this->input->post('Email'),
                        'Photograph' => $Photograph,
                        'Facebook' => $this->input->post('Facebook'),
                        'LinkedIn' => $this->input->post('LinkedIn'),
                        'Twitter' => $this->input->post('Twitter'),
                        'Instagram' => $this->input->post('Instagram'),
                        'Comment' => $this->input->post('Comment'),
                        'Position' => $this->input->post('Position'),
                        'Created' => date('d-m-Y h:i:s A')
                    ];
                    
                    $Result = $this->user_model->saveAry('members', $MemberData);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Member');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Member');
                    }
                }
            }
        }

        public function Edit($Id) 
        {
            if (!empty($Id)) {
                if ($this->db->where('Id', $Id)->get('members')->num_rows()) {
                    $Data['MemberId'] = $Id;
                    $Data['MemberData'] = $this->db->where('Id', $Id)->get('members')->row();
                    $Data['page'] = 'admin_details/Member/Edit';
                    return $this->load->view('admin', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Member');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Member id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Member');
            }
        }
        
	public function Update($Id)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('FirstName', 'First Name', 'trim|required');
                $this->form_validation->set_rules('LastName', 'Last Name', 'trim|required');
                $this->form_validation->set_rules('Designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('Position', 'Position', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $Data['MemberId'] = $Id;
                    $Data['MemberData'] = $this->db->where('Id', $Id)->get('members')->row();
                    $Data['page'] = 'admin_details/Member/Edit';
                    $Data['error'] = validation_errors();
                    return $this->load->view('admin', $Data);
                } else {
                    if ($_FILES['Photograph']['name']) {
                        unlink('uploads/Member/' . $this->input->post('OldPhotograph'));
                        $PhotographFileName = time() . $_FILES['Photograph']['name'];
                        move_uploaded_file($_FILES['Photograph']['tmp_name'], 'uploads/Member/' . $PhotographFileName);
                        $Photograph = $PhotographFileName;
                    } else {
                        $Photograph = $this->input->post('OldPhotograph');
                    }
                    $MemberData = [
                        'FirstName' => $this->input->post('FirstName'),
                        'LastName' => $this->input->post('LastName'),
                        'Designation' => $this->input->post('Designation'),
                        'Mobile' => $this->input->post('Mobile'),
                        'Email' => $this->input->post('Email'),
                        'Photograph' => $Photograph,
                        'Facebook' => $this->input->post('Facebook'),
                        'LinkedIn' => $this->input->post('LinkedIn'),
                        'Twitter' => $this->input->post('Twitter'),
                        'Instagram' => $this->input->post('Instagram'),
                        'Comment' => $this->input->post('Comment'),
                        'Position' => $this->input->post('Position'),
                        'Updated' => date('d-m-Y h:i:s A')
                    ];
                    
                    $UpdateWhere = ['Id' => $this->input->post('MemberId')];
                    $Result = $this->user_model->updateAry('members', $MemberData, $UpdateWhere);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Member');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Member/Edit/' . $Id);
                    }
                }
            }
        }
        
        public function Status($Id)
        {
            if ($Id) {
                if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") {
                    $Member = $this->user_model->getRow('members', ['Id' => $Id]);
                    if ($Member) {
                        if ($Member->Status == 1) {
                            $Status = 0;
                        } else {
                            $Status = 1;
                        }

                        $Result = $this->user_model->updateAry('members', ['Status' => $Status], ['Id' => $Id]);
                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Member');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Member');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                        return redirect(base_url() . 'Member');                    
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not have permission to create company branch', 'class' => 'danger']);
                    return redirect(base_url() . 'Member');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Member');
            }
        }
        
        public function Info($Id) 
        {
            if (!empty($Id)) {
                if ($this->db->where('Id', $Id)->get('members')->num_rows()) {
                    $Data['MemberData'] = $this->db->where('Id', $Id)->get('members')->row();
                    return $this->load->view('admin_details/Member/Info', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Member');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Branch id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Member');
            }
        }
        
	public function Delete($Id)
	{
            if (isset($Id) && $Id != '') {
                $return = $this->user_model->deleteRow('members', ['Id' => $Id]);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Member');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'Member');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect(base_url() . 'Member');
            }
	}
    }