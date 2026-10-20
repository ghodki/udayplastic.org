<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Vacancies extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            
            if (empty($this->session->userdata('user_id'))) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function Index() 
        {
            $Where = ['IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            if ($this->input->get('from') && $this->input->get('to')) {
                $Where['Date >= '] = $this->input->get('from');
                $Where['Date <= '] = $this->input->get('to');
            } else {
                $Where['Date >= '] = date('Y-m-d');
                $Where['Date <= '] = date('Y-m-d');
            }
            if ($this->input->get('status') != '' && $this->input->get('status') != 'All') {
                $Where['Status'] = $this->input->get('status');
            }
            
            $Data['Vacancies'] = $this->db->select('Id, Date, Designation, Experience, Location, Openings, Status')->where($Where)->order_by('Id', 'DESC')->get('vacancies')->result();
            $Data['page'] = 'Vacancies/index';
            return $this->load->view('inventory_sep/inventory', $Data);            
        }
        
        public function Create()
        {
            $Data['page'] = 'Vacancies/create';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Save() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('Designation', 'Designation', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $Data['error'] = validation_errors();
                    $Data['page'] = 'Vacancies/Create';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $VacanciesData = [
                        'Date' => date('Y-m-d'),
                        'Designation' => $this->input->post('Designation'),
                        'Experience' => $this->input->post('Experience'),
                        'Location' => $this->input->post('Location'),
                        'Openings' => $this->input->post('Openings'),
                        'JobDescription' => $this->input->post('JobDescription'),
                        'Requirements' => $this->input->post('Requirements'),
                        'Other' => $this->input->post('Other'),
                        'UserId' => $this->session->userdata('user_id'),
                        'CompId' => $this->session->userdata('comp_id'),
                        'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('vacancies', $VacanciesData);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Vacancies');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Vacancies');
                    }
                }
            }
        }
        
        public function Info($Id)
        {
            if (!empty($Id)) {
                if ($this->db->select('Id')->where(['Id' => $Id, 'IsDeleted' => 1])->get('vacancies')->num_rows()) {
                    $Data['VacanciesData'] = $this->db->where(['Id' => $Id, 'IsDeleted' => 1])->get('vacancies')->row();
                    return $this->load->view('inventory_sep/Vacancies/info', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Vacancies id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function Status($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1];
                $Vacancies = $this->user_model->getRow('vacancies', $Where);
                if ($Vacancies) {
                    if ($Vacancies->Status == 1) {
                        $Status = 2;
                    } else {
                        $Status = 1;
                    }
                    $Result = $this->user_model->updateAry('vacancies', ['Status' => $Status], $Where);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function Edit($Id)
        {
            if (!empty($Id)) {
                if ($this->db->select('Id')->where(['Id' => $Id, 'IsDeleted' => 1])->get('vacancies')->num_rows()) {
                    $Data['VacanciesData'] = $this->db->where(['Id' => $Id, 'IsDeleted' => 1])->get('vacancies')->row();
                    $Data['page'] = 'Vacancies/edit';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Vacancies id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }            
        }
        public function Update($Id) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('Designation', 'Designation', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $Data['error'] = validation_errors();
                    $Data['page'] = 'Vacancies/edit';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $VacanciesData = [
                        'Designation' => $this->input->post('Designation'),
                        'Experience' => $this->input->post('Experience'),
                        'Location' => $this->input->post('Location'),
                        'Openings' => $this->input->post('Openings'),
                        'JobDescription' => $this->input->post('JobDescription'),
                        'Requirements' => $this->input->post('Requirements'),
                        'Other' => $this->input->post('Other'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $UpdateWhere = ['Id' => $this->input->post('VacanciesId'), 'IsDeleted' => 1];
                    $Result = $this->user_model->updateAry('vacancies', $VacanciesData, $UpdateWhere);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Vacancies');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Vacancies');
                    }
                }
            }
        }
        
        public function Delete($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1];
                if ($this->db->select('Id')->where($Where)->get('vacancies')->num_rows()) {
                    if ($this->user_model->updateAry('vacancies', ['IsDeleted' => 0], $Where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Position id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function JobApplicationReport()
        {
            $Where = ['CompId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            if ($this->input->get('from') && $this->input->get('to')) {
                $Where['Date >= '] = $this->input->get('from');
                $Where['Date <= '] = $this->input->get('to');
            } else {
                $Where['Date >= '] = date('Y-m-d');
                $Where['Date <= '] = date('Y-m-d');
            }
            if ($this->input->get('status') != '' && $this->input->get('status') != 'All') {
                $Where['Status'] = $this->input->get('status');
            }
            
            $Data['Applicants'] = $this->db->select('Id, Date, VacancyId, Name, Email, Mobile, Status, Ressume')->where($Where)->order_by('Id', 'DESC')->get('carrer_apply')->result();
            $Data['page'] = 'Vacancies/JobApplicationReport';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function ApplicantInfo($Id)
        {
            if (!empty($Id)) {
                if ($this->db->select('Id')->where('Id', $Id)->get('carrer_apply')->num_rows()) {
                    $Data['ApplicantData'] = $this->db->where('Id', $Id)->get('carrer_apply')->row();
                    return $this->load->view('inventory_sep/Vacancies/ApplicantInfo', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Applicant id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }            
        }
        
        public function ApplicantStatus($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id];
                $Applicant = $this->user_model->getRow('carrer_apply', $Where);
                if ($Applicant) {
                    if ($Applicant->Status == 1) {
                        $Status = 2;
                    } else {
                        $Status = 1;
                    }
                    $Result = $this->user_model->updateAry('carrer_apply', ['Status' => $Status], $Where);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }