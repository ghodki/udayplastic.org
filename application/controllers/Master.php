<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Master extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');

            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
    
        public function loginByStoreId($id = null)
        {
    
            if (isset($id) && !empty($id)) {
                $where = ['id' => $id];
                $login_data = $this->user_model->getRow('users', $where);
                if ($login_data) {
                    date_default_timezone_set("Asia/Calcutta");
                    $time = date("h:i:sa");
                    $date = date("d-m-Y");
                    $m = date("m");
                    $d = date("d");
                    $y = date("Y");
                    $this->session->set_userdata('ecommerce_site', $login_data->id);
                    $this->session->set_userdata('user_id', $login_data->id);
                    $this->session->set_userdata('roles', $login_data->userType);
                    $this->session->set_userdata('name', $login_data->name);
                    $this->session->set_userdata('email', $login_data->email);
                    $this->session->set_userdata('referral', $login_data->referral);
                    $this->session->set_userdata('login', $time);
                    $this->session->set_userdata('date', $date);
                    $data['loginDate'] = $date;
                    $data['loginTime'] = $time;
                    $data['userId'] = $login_data->id;
                    $data['roles'] = $login_data->userType;
                    $data['dd'] = $d;
                    $data['mm'] = $m;
                    $data['yy'] = $y;
                    $this->user_model->saveAry('userlogindetails', $data);
                    $this->session->set_userdata('barcode', 'yes');
                    $this->session->set_userdata('lrdate', 'yes');
                    $this->session->set_userdata('eway', 'yes');
                    $this->session->set_userdata('lrnumber', 'yes');
                    $this->session->set_userdata('duedate', 'yes');
                    $this->session->set_userdata('ptd', 'yes');
                    $this->session->set_userdata('dpb', 'yes');
                    $this->session->set_userdata('dcdate', 'yes');
                    $this->session->set_userdata('dcno', 'yes');
                    redirect('wallet');
                } else {
    
                    redirect('signin');
                }
            } else {
    
                redirect('signin');
            }
        }
        
        public function enquiry()
        {
            $result = $this->user_model->getResult('enquiry');
            $data['result'] = $result;
            $data['page'] = 'admin_details/enquiry';
            $this->load->view('admin', $data);
        }
        
        public function application()
        {
            $data['application'] = $this->user_model->getResult('carrer_apply');
            $data['page'] = 'admin_details/carrer-application';
            return $this->load->view('admin', $data);
        }
        
        public function store_ecom()
        {
            $data['page'] = 'ecommerce_site/login';
            $this->load->view('admin', $data);
        }
        
        public function storeuserslist()
        {
            $data['page'] = 'ecommerce_site/users';
            $url = "https://store.umatic.in/index.php/authi/userlist";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response) {
                $data['users'] = json_decode($response);
            } else {
                $data['users'] = [];
                return false;
            }
            
            $this->load->view('admin', $data);
        }
        
        public function ProductEnquiryReport()
        {
            if ($this->input->get('From') && $this->input->get('To')) {
                $Where = ['Date >=' => $this->input->get('From'), 'Date <= ' => $this->input->get('To')];
            } else {
                $Where = ['Date >=' => date('Y-m-d'), 'Date <= ' => date('Y-m-d')];
            }
            
            $data['ProductEnquiries'] = $this->db->where($Where)->get('product_enquiry')->result();
            $data['page'] = 'admin_details/ProductEnquiryReport';
            $this->load->view('admin', $data);
        }
        
        public function ProductEnquiryDelete($Id)
        {
	{
            if (isset($Id) && $Id != '') {
                $where = ['Id' => $Id];
                $return = $this->user_model->deleteRow('product_enquiry', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'ProductEnquiryReport');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'ProductEnquiryReport');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect(base_url() . 'ProductEnquiryReport');
            }
	}            
        }
    }