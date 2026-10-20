<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Dashboard extends CI_Controller
    {
        public function __construct()
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
    
            $data['page'] = 'main_dashboard';
            $this->load->view('crm/new_main', $data);
        }
    
        public function main_dashboard()
        {
            $data['page'] = 'main_dashboard';
            $this->load->view('crm/new_main', $data);
        }
    
        public function incometax()
        {
            $service_id = get_title('crm_service', ['url' => "incometax"], 'id');
            $this->session->set_userdata('service_id', $service_id);
            $data['page'] = 'income_tax/dashboard';
            $this->load->view('crm/new_main', $data);
        }
    
        public function tds()
        {
            $service_id = get_title('crm_service', ['url' => "tds"], 'id');
            $this->session->set_userdata('service_id', $service_id);
            $data['page'] = 'tds/dashboard';
            $this->load->view('crm/main', $data);
        }
    
        public function gst()
        {
            $service_id = get_title('crm_service', ['url' => "gst"], 'id');
            $this->session->set_userdata('service_id', $service_id);
            $data['page'] = 'gst/gst_dashboard';
            $this->load->view('crm/gst_main', $data);
        }
    
        public function professionaltax()
        {
            $service_id = get_title('crm_service', ['url' => "professionaltax"], 'id');
            $this->session->set_userdata('service_id', $service_id);
            $data['page'] = 'professionaltax/dashboard';
            $this->load->view('crm/gst_main', $data);
        }
    
        public function digital_signature()
        {
            $service_id = get_title('crm_service', ['url' => "digital_signature"], 'id');
            $this->session->set_userdata('service_id', $service_id);
            $data['page'] = 'digital_signature/dashboard';
            $this->load->view('crm/main', $data);
        }
    
        public function regular()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "regular"], 'id');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'gst/regular';
            $this->load->view('crm/gst_main', $data);
        }
    
        public function composition()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "composition"], 'id');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'gst/composition';
            $this->load->view('crm/gst_main', $data);
        }
    
        public function quaterly()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "quaterly"], 'id');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'gst/quarter';
            $this->load->view('crm/gst_main', $data);
        }
    
        public function details_received()
        {
            return redirect('crm/master/details_received_entry');
        }
    
        public function return_filed()
        {
            return redirect('crm/master/return_filed_entry');
        }
    
        public function income_tax_audit()
        {
            redirect('crm/master/Incometax_audit_entry');
        }
    
        public function firm_it_return()
        {
            redirect('crm/master/firm_it_return_entry');
        }
    
        public function tds_filling()
        {
            redirect('crm/master/tds_filling');
        }
    
        public function form16()
        {
            redirect('crm/master/form16');
        }
    
        public function tds_details()
        {
            redirect('crm/master/tds_details');
        }
    
        public function dsc_process()
        {
            return redirect('crm/master/dsc_process');
        }
    
        public function dsc_exp()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "dsc_exp"], 'id');
            // $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            // $data['expire_in_thirtyData'] = $this->user_model->getAry('crm_expire_in_thirty', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'digital_signature/dsc_exp/index';
            $this->load->view('crm/main', $data);
        }
    
        public function verification_not_completed()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "verification_not_completed"], 'id');
            // $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            // $data['expire_in_thirtyData'] = $this->user_model->getAry('crm_expire_in_thirty', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'digital_signature/verification_not_completed/index';
            $this->load->view('crm/main', $data);
        }
    
        public function insurance_management()
        {
            $service_id = get_title('crm_service', ['url' => "insurance_management"], 'id');
            $this->session->set_userdata('service_id', $service_id);
            $data['page'] = 'insurance_management/dashboard';
            $this->load->view('crm/main', $data);
        }
    
        public function insurance_policy()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "insurance_policy"], 'id');
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            $data['insurance_policyData'] = $this->user_model->getAry('crm_insurance_policy', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'insurance_management/insurance_policy/index';
            $this->load->view('crm/main', $data);
        }
    
        public function expire_in_thirty()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "expire_in_thirty"], 'id');
            // $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            // $data['expire_in_thirtyData'] = $this->user_model->getAry('crm_expire_in_thirty', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'insurance_management/expire_in_thirty/index';
            $this->load->view('crm/main', $data);
        }
    
        public function pt_correction()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "pt_correction"], 'id');
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            $data['pt_correctionData'] = $this->user_model->getAry('crm_pt_correction', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'professionaltax/pt_correction/index';
            $this->load->view('crm/main', $data);
        }
    
        public function pt_details_received()
        {
            redirect('crm/master/pt_details_received');
        }
    
        public function pt_annual_return()
        {
            redirect('crm/master/pt_annual_return');
        }
    
        public function pt_return()
        {
            redirect('crm/master/pt_return');
        }
    
        public function expence()
        {
            $service_id = get_title('crm_service', ['short_name' => "expence"], 'id');
            $this->session->set_userdata('service_id', $service_id);
            $data['page'] = 'expence/dashboard';
            $this->load->view('crm/main', $data);
        }
    }