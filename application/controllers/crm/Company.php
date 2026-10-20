<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends CI_Controller
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
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_company');
        $this->db->where($where);
        $data['companyData'] = $this->db->get()->result();
        $data['page'] = 'admin_details/crm/company';
        $this->load->view('admin', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'number',
                'label' => 'number',
                'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
            ),
            array(
                'field' => 'state',
                'label' => 'state',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'gst',
                'label' => 'gst',
                'rules' => 'trim|xss_clean|regex_match[/\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}/]'
            ),
            array(
                'field' => 'address',
                'label' => 'address',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'cate_id',
                'label' => 'cate_id',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'ac',
                'label' => 'ac',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'contact',
                'label' => 'contact|xss_clean',
                'rules' => 'trim'
            ),
            array(
                'field' => 'financial_year',
                'label' => 'financial_year',
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
                $data['company_name'] = $this->input->post('name');
                $data['address'] = $this->input->post('address');
                $data['gst_no'] = $this->input->post('gst');
                $data['number'] = $this->input->post('number');
                $data['contact_person'] = $this->input->post('contact');
                $data['email'] = $this->input->post('email');
                $data['state'] = $this->input->post('state');
                $data['accounting'] = $this->input->post('ac');
                $data['financial_year'] = $this->input->post('financial_year');
                $data['invoice_format'] = $this->input->post('cate_id');
                $data['user_id'] = $this->session->userdata('user_id');
                $data['serial_number'] = rand('10000', '99999');
                $result = $this->user_model->saveAry('crm_company', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "Company Created"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "Something Went Wrong"
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "Required Field can not Be Empty"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update()
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
                $data['company_name'] = $this->input->post('name');
                $data['address'] = $this->input->post('address');
                $data['gst_no'] = $this->input->post('gst');
                $data['number'] = $this->input->post('number');
                $data['contact_person'] = $this->input->post('contact');
                $data['email'] = $this->input->post('email');
                $data['state'] = $this->input->post('state');
                $data['accounting'] = $this->input->post('ac');
                $data['financial_year'] = $this->input->post('financial_year');
                $data['invoice_format'] = $this->input->post('cate_id');
                $result = $this->user_model->updateAry('crm_company', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "Company Updated"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "Something Went Wrong"
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "Required Field can not Be Empty"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function edit($id)
    {
        $data['result'] = $this->user_model->getRow('crm_company', ['id' => $id]);
        $this->load->view('admin_details/crm/edit_company', $data);
    }

    public function view($id)
    {
        if ($id) {
            $comp_data = $this->user_model->getRow('crm_company', ['id' => $id]);
            if ($comp_data) {
                $this->session->set_userdata('crm_id', $id);
                $currentMonthYear = date('m-Y', strtotime('-1 month', strtotime(date('Y'))));
                $this->session->set_userdata('months', $currentMonthYear);
                $this->session->set_userdata('fy', $comp_data->financial_year);
                $data['page'] = 'main_dashboard';
                $this->load->view('crm/new_main', $data);
            }
        }
    }


    public function profile()
    {
        $data['page'] = 'company/profile';
        $data['result']=$this->user_model->getRow('crm_company',['id'=>$this->session->userdata('crm_id')]);
        $this->load->view('crm/main', $data);
    }


    public function viewothercomp($id = null)
    {

        // $user_id = $this->session->userdata('user_id');
        $where = ['id' => $id];
        if ($id && $id != null) {
            $inventory = $this->user_model->getAry('crm_company', '', '', '', $where);

            if ($inventory) {
                if (empty($this->session->userdata('without_assign_user_for_crm'))) {
                    $this->session->set_userdata('without_assign_user_for_crm', $this->session->userdata('user_id'));
                }
                $this->session->set_userdata('crm_id', $id);
                $currentMonthYear = date('m-Y', strtotime('-1 month', strtotime(date('Y'))));
                $this->session->set_userdata('months', $currentMonthYear);
                $this->session->set_userdata('fy', $inventory[0]->financial_year);
                $user_id = $inventory[0]->user_id;
                $usersData = $this->user_model->getRow('users', ['id' => $user_id]);
                $this->session->set_userdata('user_id', $usersData->id);
                $this->session->set_userdata('roles', $usersData->userType);
                $this->session->set_userdata('name', $usersData->name);
                $this->session->set_userdata('email', $usersData->email);
                $this->session->set_userdata('referral', $usersData->referral);
                $data['page'] = 'main_dashboard';
                $this->load->view('crm/new_main', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
                redirect('inventory_billing');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Did Not Find Data.', 'class' => 'danger']);
            redirect('inventory_billing');
        }
    }

    public function main_dashboard()
    {
        $data['page'] = 'main_dashboard';
        $this->load->view('crm/new_main', $data);
    }

    public function setMonth($monthYear)
    {
        $this->session->set_userdata('months', $monthYear);
        return redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function setYear($year)
    {
        $this->session->set_userdata('fy', $year);
        return redirect($_SERVER['HTTP_REFERER']);
    }

    public function dateUpdate()
    {
        $gstr3b = $this->user_model->getResult('crm_gstr3b');
        if ($gstr3b) {
            foreach ($gstr3b as $gstData) {
                $data['month'] = $gstData->month - 1;
                $this->user_model->updateAry('crm_gstr3b', $data, ['id' => $gstData->id]);
            }
        }


        $gstr3b = $this->user_model->getResult('crm_gstr1');
        if ($gstr3b) {
            foreach ($gstr3b as $gstData) {
                $data['month'] = $gstData->month - 1;
                $this->user_model->updateAry('crm_gstr1', $data, ['id' => $gstData->id]);
            }
        }

        $gstr3b = $this->user_model->getResult('crm_sale');
        if ($gstr3b) {
            foreach ($gstr3b as $gstData) {
                $data['month'] = $gstData->month - 1;
                $this->user_model->updateAry('crm_sale', $data, ['id' => $gstData->id]);
            }
        }

        $gstr3b = $this->user_model->getResult('crm_purchase');
        if ($gstr3b) {
            foreach ($gstr3b as $gstData) {
                $data['month'] = $gstData->month - 1;
                $this->user_model->updateAry('crm_purchase', $data, ['id' => $gstData->id]);
            }
        }
    }


    public function loginCrm($id = null, $comp_id = null)
    {
        if (isset($id) && !empty($id) && $comp_id) {
            $where = ['id' => $id];
            $login_data = $this->user_model->getRow('users', $where);
            if ($login_data) {
                $subusers = $this->session->userdata('user_id');

                if ($subusers) {
                    $this->session->set_userdata('subuser', $subusers);
                    $this->session->set_userdata('roless', "Aman");
                    $this->session->set_userdata('crmLogin', $subusers);
                }


                date_default_timezone_set("Asia/Calcutta");
                $time = date("h:i:sa");
                $this->session->set_userdata('user_id', $login_data->id);
                $this->session->set_userdata('roles', $login_data->userType);
                $this->session->set_userdata('name', $login_data->name);
                $this->session->set_userdata('email', $login_data->email);
                $this->session->set_userdata('referral', $login_data->referral);
                $this->session->set_userdata('name', $login_data->name);
                $this->session->set_userdata('login', $time);
                $data['userId'] = $login_data->id;
                $data['roles'] = $login_data->userType;
                $this->session->set_userdata('barcode', 'yes');
                $this->session->set_userdata('lrdate', 'yes');
                $this->session->set_userdata('eway', 'yes');
                $this->session->set_userdata('lrnumber', 'yes');
                $this->session->set_userdata('duedate', 'yes');
                $this->session->set_userdata('ptd', 'yes');
                $this->session->set_userdata('dpb', 'yes');
                $this->session->set_userdata('dcdate', 'yes');
                $this->session->set_userdata('dcno', 'yes');
                redirect('crm/company/view/' . $comp_id);
            } else {

                redirect('signin');
            }
        } else {

            redirect('signin');
        }
    }
}
