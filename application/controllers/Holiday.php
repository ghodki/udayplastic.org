<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Holiday extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function Index() 
        {
            $where_user['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
            $where_user['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
            $where_user['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            
            $Data['result'] = $this->user_model->getAry('holidays', '', '', '', $where_user); 
            $Data['page'] = 'holiday/index';
            return $this->load->view('inventory_sep/inventory', $Data);
        } 
        
        public function save()
        {
            if ($_POST) { 
                $this->form_validation->set_rules('holiday_name', 'Name', 'required|trim|is_unique[holidays.name]');
              
                if ($this->form_validation->run() == FALSE) {
                    $where_user['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                    $where_user['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                    $where_user['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    
                    $data['result'] = $this->user_model->getAry('holidays', '', '', '', $where_user); 
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error bg-danger">', '</div>');
                    $data['page'] = 'holiday/index';
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $data['name'] = $this->input->post('holiday_name'); 
                    $data['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                    $data['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                    $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $result = $this->user_model->saveAry('holidays', $data);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'holiday');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect(base_url() .'holiday');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() .'holiday');
            }
        }
        
        public function edit($id)
        {
            $where = ['id' => $id];
            $return = $this->user_model->getRow('holidays', $where); 
            if ($return) {
                $data['holiday'] = $return;
                $data['page'] = 'holiday/edit'; 
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                return redirect(base_url() .'holiday');
            }
        }

        public function update()
        {
            if ($_POST) { 
                $this->form_validation->set_rules('holiday_name', 'Name', 'required|trim');
              
                if ($this->form_validation->run() == FALSE) {
                    $data['holiday'] = $this->user_model->getRow('holidays', ['id' => $this->input->post('id')]);
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error bg-danger">', '</div>');
                    $data['page'] = 'holiday/edit';
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {  
                    $id = $this->input->post('id');
                    $data['name'] = $this->input->post('holiday_name');   
                    $data['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                    $data['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                    $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $where = ['id' => $id]; 
                    $result = $this->user_model->updateAry('holidays', $data, $where);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
                        return redirect(base_url() .'holiday');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect(base_url() .'holiday');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() .'holiday');
            }
        }
        
        public function changeData()
        {  
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            $staus['status'] = $status;     
            $where = ['id' => $id];
            $result = $this->user_model->updateAry('holidays', $staus, $where);
            echo json_encode(['status' => true, 'data' => $result]);
        }
        
        public function setHoliday()
        {
            $where_user['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
            $where_user['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
            $where_user['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            $finacial_year = array_key_exists('financial_year', $_SESSION) ? $this->session->userdata('financial_year') : $this->session->userdata('Finacialyear'); 
            $Data['result'] = $this->user_model->getAry('holiday_details', '', '', '', array_merge($where_user, ['finacial_year' => $finacial_year]));  
            $Data['holidays'] = $this->user_model->getAry('holidays', '', '', '', array_merge($where_user, ['status' => 1]));  
            $Data['page'] = 'holiday/setHoliday'; 
            return $this->load->view('inventory_sep/inventory', $Data);
        }

        public function saveHoliday(){
            if ($_POST) { 
                $this->form_validation->set_rules('holiday_id', 'Name', 'required|trim');
                $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
                $this->form_validation->set_rules('end_date', 'Enc Date', 'required|trim');
              
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error bg-danger">', '</div>');
                    
                    $where_user['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                    $where_user['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                    $where_user['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $finacial_year = array_key_exists('financial_year', $_SESSION) ? $this->session->userdata('financial_year') : $this->session->userdata('Finacialyear'); 
                    $data['result'] = $this->user_model->getAry('holiday_details', '', '', '', array_merge($where_user, ['finacial_year' => $finacial_year]));  
                    $data['holidays'] = $this->user_model->getAry('holidays', '', '', '', array_merge($where_user, ['status' => 1]));
                    $data['page'] = 'holiday/setHoliday'; 
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {   
                    $data['holiday_id'] = $this->input->post('holiday_id');  
                    $data['start_date'] = $this->input->post('start_date');  
                    $data['end_date'] = $this->input->post('end_date');  
                    $data['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                    $data['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                    $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $data['finacial_year'] = array_key_exists('financial_year', $_SESSION) ? $this->session->userdata('financial_year') : $this->session->userdata('Finacialyear');
                    $data['month'] = 0;   
                    $result = $this->user_model->saveAry('holiday_details', $data);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'holiday/setHoliday');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect(base_url() . 'holiday/setHoliday');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'holiday/setHoliday');
            }
        }

        public function editHoliday($id)
        {
            $where = ['id' => $id];
            $return = $this->user_model->getRow('holiday_details', $where); 
            if ($return) {
                $where_user['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                $where_user['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                $where_user['CompanyBranchId'] = $this->session->userdata('CompBranchId');

                $data['holiday'] = $return; 
                $data['result'] = $this->user_model->getAry('holidays', '', '', '', $where_user);
                $data['page'] = 'holiday/editHoliday'; 
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                return redirect(base_url() . 'holiday');
            }
        }

        public function updateHoliday()
        {
            if ($_POST) { 
                $this->form_validation->set_rules('holiday_id', 'Name', 'required|trim');
                $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
                $this->form_validation->set_rules('end_date', 'Enc Date', 'required|trim');
                $id = $this->input->post('id');
                if ($this->form_validation->run() == FALSE) {
                    $where_user['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                    $where_user['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                    $where_user['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    
                    $data['result'] = $this->user_model->getAry('holidays', '', '', '', $where_user); 
                    $this->data['holiday'] = $this->user_model->getRow('holiday_details', ['id' => $id]); 
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error bg-danger">', '</div>');
                    $data['page'] = 'holiday/editHoliday';
                    return $this->load->view('inventory_sep/inventory', $data);
                } else { 
                    $data['holiday_id'] = $this->input->post('holiday_id');   
                    $data['start_date'] = $this->input->post('start_date');   
                    $data['end_date'] = $this->input->post('end_date');   
                    $data['user_id'] = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '';
                    $data['comp_id'] = $this->session->userdata('comp_id') ? $this->session->userdata('comp_id') : '';
                    $data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $where = ['id' => $id]; 
                    $result = $this->user_model->updateAry('holiday_details', $data, $where);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Holiday/setHoliday');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect(base_url() . 'Holiday/setHoliday');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect(base_url() . 'Holiday/editHoliday/' . $id);
            }
        }

        public function changeHolidayDataStatus()
        {  
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            $staus['status'] = $status;     
            $where = ['id' => $id];
            $result = $this->user_model->updateAry('holiday_details', $staus, $where);
            echo json_encode(['status' => true, 'data' => $result]);
        }
        
        public function delete($id)
        {
            if (!empty($id)) {
                if ($this->user_model->deleteRow('holidays', ['id' => $id])) {
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Holiday');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Holiday');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Holiday');
            }
        }
        
        public function DeleteHoliday($id)
        {
            if (!empty($id)) {
                if ($this->user_model->deleteRow('holiday_details', ['id' => $id])) {
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Holiday/setHoliday');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Holiday/setHoliday');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Holiday/setHoliday');
            }
        }
    }