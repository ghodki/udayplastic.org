<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class SubUnit extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            
            if (empty($this->session->userdata('user_id'))) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function index() 
        {
            $Data['SubUnits'] = $this->db->where('Status', 1)->order_by('Id', 'DESC')->get('sub_unit')->result();
            $Data['page'] = 'SubUnit/index';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Save() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('SubUnitName', 'Sub Unit Name', 'trim|required|is_unique[sub_unit.SubUnitName]');
                if ($this->form_validation->run() == false) {
                    $Data['SubUnits'] = $this->db->where('Status', 1)->order_by('Id', 'DESC')->get('sub_unit')->result();
                    $Data['page'] = 'SubUnit/index';
                    $Data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $SubUnitData = [
                        'SubUnitName' => $this->input->post('SubUnitName'),
                        'Value' => $this->input->post('Value'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('sub_unit', $SubUnitData);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'SubUnit');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'SubUnit');
                    }
                }
            }
        }
        
        public function Edit($Id) 
        {
            if (!empty($Id)) {
                if ($this->db->select('Id, SubUnitName')->where('Id', $Id)->where('Status', 1)->get('sub_unit')->num_rows()) {
                    $Data['SubUnitId'] = $Id;
                    $Data['SubUnitData'] = $this->db->select('Id, SubUnitName, Value')->where('Status', 1)->where('Id', $Id)->get('sub_unit')->row();
                    $Data['page'] = 'SubUnit/Edit';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'SubUnit');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Sub unit id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'SubUnit');
            }
        }
        
        public function IsUniqueName($Name, $Id) 
        {
            $Where = ['SubUnitName' => $Name, 'Id != ' => $Id, 'Status' => 1];
            $QueryResult = $this->db->select('Id, SubUnitName')->where($Where)->get('sub_unit')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueName', 'The sub unit name must be unique.');
                return false;
            }
        }
        
        public function Update($Id) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('SubUnitName', 'Sub Unit Name', 'trim|required|callback_IsUniqueName[' . $Id . ']');
                if ($this->form_validation->run() == false) {
                    $Data['SubUnitId'] = $Id;
                    $Data['SubUnitData'] = $this->db->select('Id, SubUnitName, Value')->where('Status', 1)->where('Id', $Id)->get('sub_unit')->row();
                    $Data['page'] = 'SubUnit/Edit';
                    $Data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $SubUnitData = [
                        'SubUnitName' => $this->input->post('SubUnitName'),
                        'Value' => $this->input->post('Value'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $UpdateWhere = ['Id' => $this->input->post('SubUnitId')];
                    $Result = $this->user_model->updateAry('sub_unit', $SubUnitData, $UpdateWhere);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'SubUnit');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'SubUnit');
                    }
                }
            }
        }
        
        public function Delete($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'Status' => 1];
                if ($this->db->select('Id')->where($Where)->get('sub_unit')->num_rows()) {
                    if ($this->user_model->updateAry('sub_unit', ['Status' => 0], $Where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'SubUnit');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'SubUnit');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'SubUnit');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Position id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'SubUnit');
            }
        }
    }