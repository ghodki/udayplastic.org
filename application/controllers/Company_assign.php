<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Company_assign extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('user_model');
                    $this->load->model('customers_model', 'customers');
    
            if ($this->session->userdata('roles') === null) {
                redirect('welcome');
            }
        }
    
        public function index()
        {
            if (!$this->user_model->check_permission('company_assign')) {
                $data['page'] = 'admin_details/error/permission_denied';
            } else {
                $data['company_assign'] = $this->user_model->getResult('inventory_billing');
                $data['page'] = 'admin_details/company_assign/index';
            }
            
            $this->load->view('admin', $data);
        }
        
        public function stock($id = null)
        {
            if ($id) {
                $data['comp_details'] = $this->user_model->getRow('inventory_billing', ['id' => $id]);
                $this->load->view('admin_details/company_assign/comp_stock', $data);
            }
        }
    
        public function mobile_number_report($id = null)
        {
            $where = ['user_id' => $id, 'contact!=' => ''];
            $data['parties'] = $this->user_model->getAry('parties', '', '', '', $where);
            $data['page'] = 'admin_details/mobile_number_report';
            $data['user_unique_id'] = $id;
            $this->load->view('admin', $data);
        }
        
        public function customer_report()
        {
            $data['page'] = 'admin_details/customer_report';
            $this->load->view('admin', $data);
        }
    
        public function ajax_list()
        {
            $fetch_data = $this->customers->make_datatables();
            $data = array();
            $start = $_POST['start'];
            foreach ($fetch_data as $key => $row) {
                $sub_array = array();
                $sub_array[] = $start + 1;
                $sub_array[] = $row->name;
                $sub_array[] = $row->contact;
                $sub_array[] = $this->getTotalSms($row->id);
                $data[] = $sub_array;
                $start++;
            }
            $output = array(
                "draw"                    =>     intval($_POST["draw"]),
                "recordsTotal"          =>      $this->customers->get_all_data(),
                "recordsFiltered"     =>     $this->customers->get_filtered_data(),
                "data"                    =>     $data
            );
            echo json_encode($output);
        }
    
        public function assign_to_user($id)
        {
            if ($id) {
                $data['id'] = $id;
                $this->load->view('admin_details/company_assign/assign', $data);
            }
        }
        
        
    
    
        public function blocked_by_company($id)
        {
            if ($id) {
                $data['id'] = $id;
                $data['result'] = $this->user_model->getRow('inventory_billing', ['id' => $id]);
                $this->load->view('admin_details/company_assign/blocked_sms', $data);
            }
        }
    
        public function assign_comp_to_user()
        {
            $post = $this->input->post();
            $comp_id = $this->input->post('comp_id');
            $user_assign_by_unique = $this->input->post('user_assign_by_unique');
            $assign_user = $this->input->post('user_id');
            $today = $this->input->post('today');
            $date_range = $this->input->post('date_range');
            $only_user_display = $this->input->post('only_user_display');
            $permission = $this->input->post('permission');
            $view_report_and_gst_return = $this->input->post('view_report_and_gst_return');
            $view_invoice_current_month = $this->input->post('view_invoice_current_month');
            $data['comp_id'] = $comp_id;
            $data['user_id'] = get_title('inventory_billing', ['id' => $comp_id], 'user_id');
            $user_id = get_title('inventory_billing', ['id' => $comp_id], 'user_id');
            $result = $this->user_model->getRow('company_assign', ['comp_id' => $comp_id, 'user_id' => $user_id, 'user_assign_by_unique' => $assign_user]);
            if (isset($post) && !empty($post) && !empty($comp_id)) {
                $data['user_id'] = $user_id;
                $data['user_assign_by_unique'] = $assign_user;
                $data['comp_id'] = $comp_id;
                $data['assign_user'] = $user_assign_by_unique;
                $data['today'] = $today;
                $data['date_range'] = $date_range;
                $data['only_user_display'] = $only_user_display;
                $data['permission'] = $permission;
                $data['view_report_and_gst_return'] = $view_report_and_gst_return;
                $data['view_invoice_current_month'] = $view_invoice_current_month;
                if ($result) {
                    $res = $this->user_model->updateAry('company_assign', $data, ['id' => $result->id]);
                } else {
                    $res = $this->user_model->saveAry('company_assign', $data);
                }
    
                if ($res) {
                    echo json_encode(['status' => true, 'data' => 'Company Assign Successfully', 'class' => 'success']);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Something Went Wronng', 'class' => 'error']);
                }
            } else {
                echo json_encode(['status' => false, 'data' => 'Please Filled Required Fields.', 'class' => 'error']);
            }
        }
    
    
        public function send_mail_to_user()
        {
            $this->form_validation->set_rules('company_id', 'Something is Missing', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');
            $this->form_validation->set_rules('email_subject', 'Email Subject', 'trim|required');
            $this->form_validation->set_rules('email_body', 'Email Body', 'trim|required');
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'message' => validation_errors()]);
                return;
            }
            $company_id = $this->input->post('company_id');
            $status = $this->input->post('status');
            $email_body = strip_tags(html_entity_decode($this->input->post('email_body')));
            $email_subject = $this->input->post('email_subject');
            $smsUpdate['sms_blocked_status'] = $status;
            $this->user_model->updateAry('inventory_billing', $smsUpdate, ['id' => $company_id]);
            $compDetiails = $this->user_model->getRow('inventory_billing', ['id' => $company_id]);
            if ($compDetiails) {
                if ($compDetiails) {
                    $userData['email_body'] = $email_body;
                    $userData['subject'] = $email_subject;
                    $userData['user_id'] = $compDetiails->user_id;
                    $userData['comp_id'] = $compDetiails->id;
                    $this->user_model->saveAry('sms_reminder', $userData);
                }
                echo json_encode(['status' => true, 'message' => "Message Sent"]);
                return;
                //for delete generated pdf file. 
            } else {
                echo json_encode(['status' => false, 'message' => "Something went wrong"]);
                return;
            }
        }
    
        public function setemail()
        {
            $email = "chaurasiyaaman09@gmail.com";
            $subject = "Tessting";
            $message = "Nothing Special";
            $this->sendEmail($email, $subject, $message);
        }
        public function sendEmail($email, $subject, $message)
        {
    
            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.mailtrap.io',
                'smtp_port' => 2525,
                'smtp_user' => '3fac3d45bf8121',
                'smtp_pass' => '38468efac30978',
                'crlf' => "\r\n",
                'newline' => "\r\n",
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );
    
    
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->from('chaurasiyaaman553@gmail.com');
            $this->email->to($email);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->attach('uploads/applicationGSTR3B_33AKMPN4085A2ZA_032021 (2).pdf', 'applicationGSTR3B_33AKMPN4085A2ZA_032021 (2).pdf');
            // 			$this->email->attach('https://www.google.com/url?sa=i&url=https%3A%2F%2Fpixabay.com%2Fimages%2Fsearch%2Feye%2F&psig=AOvVaw2KhCgxZCLfFgEkZk5leTqd&ust=1653133779440000&source=images&cd=vfe&ved=0CAwQjRxqFwoTCNC886-B7vcCFQAAAAAdAAAAABAD');
            if ($this->email->send()) {
                echo 'Email send.';
            } else {
                show_error($this->email->print_debugger());
            }
        }
        
            protected function getTotalSms($partyId)
        {
            $this->db->select('*');
            $this->db->where('customerId', $partyId);
            $this->db->where(['customerMobile!=' => '']);
            $q = $this->db->get('sales');
            $r = $q->result();
            return count($r);
        }
        
        public function module_assign()
        {
            if (isset($_POST) && !empty($_POST)) {
                $allData = $this->input->post('allData');
                if ($allData) {
                    foreach ($allData as $res) {
                        $data['permission'] = $res['permission'];
                        $data['side_menu'] = $res['side_menu'];
                        $data['file_sharing'] = $res['file_sharing'];
                        $data['inventory'] = $res['inventory'];
                        $data['crm'] = $res['crm'];
                        $data['services'] = $res['services'];
                        $data['wallet'] = $res['wallet'];
                        $data['paymentreceipt'] = $res['paymentreceipt'];
                        $data['user_id'] = $res['user_id'];
                        
                        $responce = $this->user_model->getRow('module_assign', ['user_id' => $res['user_id']]);
                        if ($responce) {
                            $this->user_model->updateAry('module_assign', $data, ['user_id' => $res['user_id']]);
                        } else {
                            $this->user_model->saveAry('module_assign', $data);
                        }
                    }
                }
                echo json_encode(['status' => true, 'data' => 'Module Assign Successfully', 'class' => 'success']);
            } else {
                $where = ['userType!=' => 1];
                $data['module_assign'] = $this->user_model->getAryss('users', '', '', '', $where);
                
                $data['page'] = 'admin_details/module_assign/index';
                $this->load->view('admin', $data);
            }
        }
        
        public function assignedUsers()
        {
            $this->db->from('inventory_billing');
            $this->db->join('company_assign', 'company_assign.comp_id = inventory_billing.id', 'LEFT');
            if ($this->session->userdata('roles') != 1) {
                $this->db->where('company_assign.user_id', $this->session->userdata('user_id'));
            }
            $this->db->group_by('company_assign.comp_id');
            $query = $this->db->get();
            
            $data['companys'] = $query->result();
            $data['page'] = 'admin_details/company_assign/assignedusers';
           
            $this->load->view('admin', $data);       
        }
        
        public function viewCompanyUsers ($compid)
        {
            $data['company_users'] = $this->user_model->getResult('company_assign', ['comp_id' => $compid]);
            
            $this->load->view('admin_details/company_assign/companyusers', $data);
        }
    }