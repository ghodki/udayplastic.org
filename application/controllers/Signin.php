<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Calcutta");

class Signin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'cookie']);
        $this->load->model('user_model');
    }

    public function adminlogin()
    {
        $login_data = $this->user_model->getRow('users', ['id' => $this->session->userdata('subuser')]);
        if ($login_data) {
            $time = date("h:i:s A");
            $date = date("d-m-Y");
            $m = date("m");
            $d = date("d");
            $y = date("Y");

            $this->session->set_userdata('user_id', $login_data->id);
            $this->session->set_userdata('roles', $login_data->userType);
            $this->session->set_userdata('name', $login_data->name);
            $this->session->set_userdata('email', $login_data->email);
            $this->session->set_userdata('referral', $login_data->referral);
            $this->session->set_userdata('name', $login_data->name);
            $this->session->set_userdata('login', $time);
            $this->session->set_userdata('date', $date);

            $data['loginDate'] = $date;
            $data['loginTime'] = $time;
            $data['userId'] = $login_data->id;
            $data['roles'] = $login_data->userType;
            $data['dd'] = $d;
            $data['mm'] = $m;
            $data['yy'] = $y;
            $data['strtotime'] = strtotime('now');
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
            return redirect(base_url() . 'signin');
        } else {
            return redirect(base_url() . 'signin');
        }
    }

    public function index()
    {
        if (($this->session->userdata('user_id') !== null)) {
            return redirect(base_url() . 'dashboard');
        }
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/login';
        return $this->load->view('layout', $data);
    }

    public function auth()
    {
        if (($this->session->userdata('user_id') !== null)) {
            return redirect(base_url() . 'dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
                $data['error'] = validation_errors();
                $data['page'] = 'layout/login';
                return $this->load->view('layout', $data);
            } else {
                $login_data = $this->user_model->getRow('users', array(
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password')
                ));

                $LoginDataEmployee = $this->user_model->getRow('employees', array(
                    'Email' => $this->input->post('email'),
                    'Password' => $this->input->post('password')
                ));

                if ($login_data->userType == 1) {
                    $this->session->set_userdata('IsAdmin', 'yes');
                } else {
                    $this->session->set_userdata('IsAdmin', 'no');
                }

                if (!empty($login_data->userType) && $login_data->userType == 1 || $login_data->userType == 2 || $login_data->userType == 3 || $login_data->userType == 4) {
                    if ($login_data) {
                        if ($login_data->status) {
                            $time = date("h:i:s A");
                            $date = date("d-m-Y");
                            $m = date("m");
                            $d = date("d");
                            $y = date("Y");

                            $this->session->set_userdata('user_id', $login_data->id);
                            $this->session->set_userdata('roles', $login_data->userType);
                            $this->session->set_userdata('name', $login_data->name);
                            $this->session->set_userdata('email', $login_data->email);
                            $this->session->set_userdata('referral', $login_data->referral);
                            $this->session->set_userdata('offerCount', 1);
                            $this->session->set_userdata('login', $time);
                            $this->session->set_userdata('date', $date);

                            $data['loginDate'] = $date;
                            $data['loginTime'] = $time;
                            $data['userId'] = $login_data->id;
                            $data['roles'] = $login_data->userType;
                            $data['dd'] = $d;
                            $data['mm'] = $m;
                            $data['yy'] = $y;
                            $data['strtotime'] = strtotime('now');
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
                            $this->session->set_userdata('discount', 'yes');
                            if ($login_data->userType == 1) {
                                return redirect(base_url() . 'Dashboard');
                            } elseif ($login_data->userType == 3) {
                                if ($this->db->where('user_id', $login_data->id)->get('inventory_billing')->num_rows() && $this->db->where('BranchUserId', $login_data->id)->get('companybranch')->num_rows()) {
                                    return redirect(base_url() . 'Dashboard');
                                } elseif ($this->db->where('user_id', $login_data->id)->get('inventory_billing')->num_rows() > 1) {
                                    return redirect(base_url() . 'Inventory_billing');
                                } elseif ($this->db->where('user_id', $login_data->id)->get('inventory_billing')->num_rows() == 1) {
                                    $CompanyData = $this->db->where('user_id', $login_data->id)->get('inventory_billing')->row();
                                    $this->session->set_userdata('comp_id', $CompanyData->id);
                                    $this->session->set_userdata('state', $CompanyData->state);
                                    $this->session->set_userdata('gst_nos', $CompanyData->gst_no);
                                    $this->session->set_userdata('numbers', $CompanyData->number);
                                    $this->session->set_userdata('address', $CompanyData->address);
                                    $this->session->set_userdata('format_of_bill', $CompanyData->billing_format_id);

                                    $CompanyProfileData = $this->db->where('user_id', $login_data->id)->where('comp_id', $CompanyData->id)->get('comp_profile')->row();
                                    $this->session->set_userdata('financial_year', $CompanyProfileData->financial_year);
                                    $this->session->set_userdata('barcode', $CompanyProfileData->barcode_con);
                                    $this->session->set_userdata('lrdate', $CompanyProfileData->lrdate);
                                    $this->session->set_userdata('eway', $CompanyProfileData->eway);
                                    $this->session->set_userdata('lrnumber', $CompanyProfileData->lrnumber);
                                    $this->session->set_userdata('duedate', $CompanyProfileData->duedate);
                                    $this->session->set_userdata('ptd', $CompanyProfileData->ptd);
                                    $this->session->set_userdata('dpb', $CompanyProfileData->dpb);
                                    $this->session->set_userdata('dcdate', $CompanyProfileData->dcdate);
                                    $this->session->set_userdata('dcno', $CompanyProfileData->dcno);
                                    $this->session->set_userdata('discount', $CompanyProfileData->discount);
                                    $this->session->set_userdata('estimate_format', $CompanyProfileData->estimate_format);
                                    $this->session->set_userdata('estimate_stock', $CompanyProfileData->estimate_stock);
                                    $this->session->set_userdata('salesman_sale', $CompanyProfileData->salesman_sale);
                                    $this->session->set_userdata('salesman_estimate', $CompanyProfileData->salesman_estimate);
                                    return redirect(base_url() . 'inventory/dashboard');
                                } elseif ($this->db->where('BranchUserId', $login_data->id)->get('companybranch')->num_rows()) {
                                    return redirect(base_url() . 'CompanyBranch');
                                } else {
                                    return redirect(base_url() . 'Dashboard');
                                }
                            }
                        } else {
                            $this->session->set_flashdata(['msg' => 'Your Account Has been Blocked(contact your subadmin or admin) !!!', 'class' => 'danger']);
                            return redirect(base_url() . 'signin');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Invalid Emaild and Password !!!', 'class' => 'danger']);
                        return redirect(base_url() . 'signin');
                    }
                } elseif (!empty($LoginDataEmployee->RoleId) && $LoginDataEmployee->RoleId == 1 || $LoginDataEmployee->RoleId == 2 || $LoginDataEmployee->RoleId == 3 || $LoginDataEmployee->RoleId == 4) {
                    if ($LoginDataEmployee->IsActive) {
                        $this->session->set_userdata('EmployeeId', $LoginDataEmployee->Id);
                        $this->session->set_userdata('RoleType', get_title('position', ['Id' => $LoginDataEmployee->RoleId], 'PostName'));
                        $this->session->set_userdata('UserId', $LoginDataEmployee->UserId);
                        $this->session->set_userdata('CompId', $LoginDataEmployee->CompId);
                        $this->session->set_userdata('WarehouseId', $LoginDataEmployee->WarehouseId);

                        $Where = ['comp_id' => $LoginDataEmployee->CompId, 'user_id' => $LoginDataEmployee->UserId];
                        $EmployeeConfigWhere = ['CompId' => $LoginDataEmployee->CompId, 'UserId' => $LoginDataEmployee->UserId];

                        if ($LoginDataEmployee->CompanyBranchId) {
                            $Where['CompanyBranchId'] = $LoginDataEmployee->CompanyBranchId;
                            $EmployeeConfigWhere['CompanyBranchId'] = $LoginDataEmployee->CompanyBranchId;
                            $CompanyBranchWhere = ['Id' => $LoginDataEmployee->CompanyBranchId, 'BranchUserId' => $LoginDataEmployee->UserId, 'CompId' => $LoginDataEmployee->CompId];

                            $CompanyBranch = $this->user_model->getRow('companybranch', $CompanyBranchWhere);
                            $this->session->set_userdata('BranchRole', 'CompanyBranch');
                            $this->session->set_userdata('CompBranchId', $LoginDataEmployee->CompanyBranchId);
                            $this->session->set_userdata('state', $CompanyBranch->State);
                            $this->session->set_userdata('gst_nos', $CompanyBranch->GST);
                            $this->session->set_userdata('numbers', $CompanyBranch->Mobile);
                            $this->session->set_userdata('address', $CompanyBranch->Address);
                            $this->session->set_userdata('format_of_bill', $CompanyBranch->BillingFormat);
                        } else {
                            $MainCompany = $this->user_model->getRow('inventory_billing', ['id' => $LoginDataEmployee->CompId, 'user_id' => $LoginDataEmployee->UserId]);
                            $this->session->set_userdata('state', $MainCompany->state);
                            $this->session->set_userdata('gst_nos', $MainCompany->gst_no);
                            $this->session->set_userdata('numbers', $MainCompany->number);
                            $this->session->set_userdata('address', $MainCompany->address);
                            $this->session->set_userdata('format_of_bill', $MainCompany->billing_format_id);
                        }

                        if (get_title('employee_config', $EmployeeConfigWhere, 'LoginIPValidation') === "Yes") {
                            if ($LoginDataEmployee->IPAddress !== $this->input->ip_address()) {
                                $this->session->set_flashdata(['msg' => 'You are not logging in using the office network!', 'class' => 'danger']);
                                return redirect(base_url() . 'signin');
                            }
                        }

                        $CompProfile = $this->user_model->getRow('comp_profile', $Where);

                        $this->session->set_userdata('financial_year', $CompProfile->financial_year);
                        $this->session->set_userdata('barcode', $CompProfile->barcode_con);
                        $this->session->set_userdata('lrdate', $CompProfile->lrdate);
                        $this->session->set_userdata('eway', $CompProfile->eway);
                        $this->session->set_userdata('lrnumber', $CompProfile->lrnumber);
                        $this->session->set_userdata('duedate', $CompProfile->duedate);
                        $this->session->set_userdata('ptd', $CompProfile->ptd);
                        $this->session->set_userdata('dpb', $CompProfile->dpb);
                        $this->session->set_userdata('dcdate', $CompProfile->dcdate);
                        $this->session->set_userdata('dcno', $CompProfile->dcno);
                        $this->session->set_userdata('discount', $CompProfile->discount);
                        $this->session->set_userdata('estimate_format', $CompProfile->estimate_format);
                        $this->session->set_userdata('estimate_stock', $CompProfile->estimate_stock);
                        $this->session->set_userdata('salesman_sale', $CompProfile->salesman_sale);
                        $this->session->set_userdata('salesman_estimate', $CompProfile->salesman_estimate);
                        return redirect(base_url() . 'Employee/Dashboard');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Your account is blocked contact your administrator!', 'class' => 'danger']);
                        return redirect(base_url() . 'signin');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Your are not registered with us kindly register and then login!', 'class' => 'danger']);
                    return redirect(base_url() . 'signin');
                }
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Do not allow Directlly', 'class' => 'danger']);
            return redirect(base_url() . 'signin');
        }
    }

    public function logout()
    {
        session_destroy();
        $this->session->set_flashdata(['logout' => 'Logout Successfully !!!', 'class' => 'danger']);
        return redirect(base_url() . 'signin');
    }

    public function create()
    {
        if ($_POST) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean|max_length[60]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|is_unique[users.email]|max_length[60]');
            $this->form_validation->set_rules('number', 'Mobile', 'numeric|trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|xss_clean|is_unique[users.number]');
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]|max_length[15]|xss_clean');
            if ($this->form_validation->run() == false) {
                $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
                $data['error'] = validation_errors();
                $data['page'] = 'layout/signup';
                return $this->load->view('layout', $data);
            } else {
                $UserData = [
                    'user_number' => rand(0, 999999),
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password'),
                    'number' => $this->input->post('number'),
                    'userType' => 3,
                    'gender' => $this->input->post('gender'),
                    'status' => 0,
                    'created' => date('d-m-Y h:i:s A'),
                    'started' => date('d-m-Y'),
                    'expiry' => date('d-m-Y', strtotime(date('d-m-Y', strtotime(date('d-m-Y'))) . ' + 1 year'))
                ];

                $result = $this->user_model->saveAry('users', $UserData);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Yay! Congratulations, you have register successfully! we will be contacted shortly.', 'class' => 'success']);
                    return redirect(base_url() . 'signin/create');
                } else {
                    $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
                    return redirect(base_url() . 'signin/create');
                }
            }
        }

        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/signup';
        return $this->load->view('layout', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|max_length[60]'
            ),
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|max_length[60]|valid_email|is_unique[users.email]'
            ),
            array(
                'field' => 'number',
                'label' => 'number',
                'rules' => 'trim|max_length[25]|min_length[5]|is_unique[users.number]'
            ),
            array(
                'field' => 'password',
                'label' => 'password',
                'rules' => 'trim|required|min_length[4]|max_length[40]'
            )
        );
        return $rules;
    }

    public function save()
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data['page'] = "signin/create";
                $this->load->view('main', $this->data);
            } else {

                $data['name'] = $this->input->post('name');
                $data['email'] = $this->input->post('email');
                $data['password'] = $this->input->post('password');
                $data['number'] = $this->input->post('number');
                $data['user_number'] = rand(0, 999999);
                $data['gender'] = $this->input->post('gender');
                $data['referral'] = $this->input->post('ref');
                $referral_code = $this->input->post('ref');
                $data['usertype'] = 3;

                $result = $this->user_model->saveAry('users', $data);
                if ($result) {

                    if (!empty($referral_code)) {
                        $subadmin_id = get_title('users', ['referral' => $referral_code, 'userType' => 2], 'id');
                        if ($subadmin_id) {
                            $subcat_data['referral_id'] = $subadmin_id;
                            $subcat_data['userid'] = $result;
                            $this->user_model->saveAry('subcat', $subcat_data);
                        }
                    }
                    $send_sms = sendsms($this->input->post('number'), $this->input->post('email'));
                    if ($result) {
                        $metadata = array(
                            'first_name'      => $this->input->post('name'),
                            'umatic_user_id'      => $result,
                            'phone' => $this->input->post('number'),
                            'email'    => $this->input->post('email'),
                            'password' => $this->input->post('password'),
                            'country' => "INDIA",
                            'state' => "KARNATAKA"

                        );
                        $result = $this->store_umatic_registration($metadata);
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        return redirect('signin/index');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect('signin/index');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('signin/index');
                }
            }
        } else {
            $this->data["page"] = "signin/create";
            $this->load->view('main', $this->data);
        }
    }

    public function store_umatic_registration($metadata)
    {
        $data_string = json_encode($metadata);

        // API STORE UMATIC
        // $curl = curl_init('http://localhost/store/index.php/authi/registration');

        $curl = curl_init('https://store.umatic.in/index.php/authi/registration');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Make it so the data coming back is put into a string
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);  // Insert the data

        // Send the request
        $result = curl_exec($curl);

        // Free up the resources $curl is using
        curl_close($curl);
        return $result;
    }

    public function forgot_password()
    {
        if ($_POST) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|max_length[60]');
            if ($this->form_validation->run() == false) {
                $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
                $data['error'] = validation_errors();
                $data['page'] = 'layout/forgot-password';
                return $this->load->view('layout', $data);
            } else {
                $UserData = [
                    'email' => $this->input->post('email'),
                    'status' => 1,
                ];

                if ($this->db->where($UserData)->get('users')->num_rows() > 0) {
                    $uinfo = $this->db->where($UserData)->get('users')->row();
                    $OTP = mt_rand(100000, 999999);
                    $name = ucwords($uinfo->name);
                    $message =  "<b>"
                        . "Hi, $name Ji"
                        . "<br />"
                        . "Greetings from Uday Plastic!"
                        . "</b>"
                        . "<br /><br />"
                        . "We are sharing an OTP to reset your password. The code is valid for 10 minutes and usable only once."
                        . "<br /><br />"
                        . "<table border=1 cellspacing=0 style='border: 1px solid #00854a !important; width: 100%;'>"
                        . "<tr>"
                        . "<td style='width: 150px; text-align: center;'>NAME</td>"
                        . "<th style='width: 150px;'>$name</th>"
                        . "</tr>"
                        . "<tr>"
                        . "<td style='width: 150px; text-align: center;'>OTP</td>"
                        . "<th style='width: 150px;'>$OTP</th>"
                        . "</tr>"
                        . "</table>"
                        . "<br /><br />"
                        . "Once you have verified the OTP, you will be able to reset your password immediately."
                        . "<br />"
                        . "<p style='text-align: justify;'>"
                        . "Thanks & Regards"
                        . "<br />"
                        . "Uday Plastic Team"
                        . "</p>";

                    $config['smtp_host'] = 'smtp.gmail.com';
                    $config['smtp_port'] = '587';
                    $config['smtp_user'] = 'info@udayplastic.co.in';
                    $config['_smtp_auth'] = TRUE;
                    $config['smtp_pass'] = 'kslxokprunrxipsx';
                    $config['smtp_crypto'] = 'tls';
                    $config['protocol'] = 'mail';
                    $config['mailtype'] = 'html';
                    $config['priority'] = 1;
                    $config['charset'] = 'utf-8';
                    $config['wordwrap'] = TRUE;

                    $this->email->initialize($config);
                    $this->email->set_newline("\r\n");
                    $this->email->from('info@udayplastic.co.in', 'Uday Plastic.');
                    $this->email->to($uinfo->email);
                    $this->email->subject('Forgot Password - Uday Plastic (DO NOT REPLY)');
                    $this->email->message($message);
                    if ($this->email->send()) {
                        $this->session->set_userdata('OTP', $OTP);
                        $this->session->set_userdata('UID', $uinfo->id);
                        $this->session->set_userdata('Role', 'User');
                        $this->session->set_flashdata(['msg' => 'OTP sent your registered email address, kindly Enter OTP', 'class' => 'success']);
                        return redirect(base_url() . 'reset-password');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to sent OTP, kindly contact administrator', 'class' => 'danger']);
                        return redirect(base_url() . 'forgot-password');
                    }
                } elseif ($this->db->where($UserData)->get('salesman')->num_rows() > 0) {
                    $uinfo = $this->db->where($UserData)->get('salesman')->row();
                    $OTP = mt_rand(100000, 999999);
                    $name = ucwords($uinfo->name);
                    $message =  "<b>"
                        . "Hi, $name Ji"
                        . "<br />"
                        . "Greetings from Uday Plastic!"
                        . "</b>"
                        . "<br /><br />"
                        . "We are sharing an OTP to reset your password. The code is valid for 10 minutes and usable only once."
                        . "<br /><br />"
                        . "<table border=1 cellspacing=0 style='border: 1px solid #00854a !important; width: 100%;'>"
                        . "<tr>"
                        . "<td style='width: 150px; text-align: center;'>NAME</td>"
                        . "<th style='width: 150px;'>$name</th>"
                        . "</tr>"
                        . "<tr>"
                        . "<td style='width: 150px; text-align: center;'>OTP</td>"
                        . "<th style='width: 150px;'>$OTP</th>"
                        . "</tr>"
                        . "</table>"
                        . "<br /><br />"
                        . "Once you have verified the OTP, you will be able to reset your password immediately."
                        . "<br />"
                        . "<p style='text-align: justify;'>"
                        . "Thanks & Regards"
                        . "<br />"
                        . "Uday Plastic Team"
                        . "</p>";

                    $config['smtp_host'] = 'smtp.gmail.com';
                    $config['smtp_port'] = '587';
                    $config['smtp_user'] = 'info@udayplastic.co.in';
                    $config['_smtp_auth'] = TRUE;
                    $config['smtp_pass'] = 'kslxokprunrxipsx';
                    $config['smtp_crypto'] = 'tls';
                    $config['protocol'] = 'mail';
                    $config['mailtype'] = 'html';
                    $config['priority'] = 1;
                    $config['charset'] = 'utf-8';
                    $config['wordwrap'] = TRUE;

                    $this->email->initialize($config);
                    $this->email->set_newline("\r\n");
                    $this->email->from('info@udayplastic.co.in', 'Uday Plastic.');
                    $this->email->to($uinfo->email);
                    $this->email->subject('Forgot Password - Uday Plastic (DO NOT REPLY)');
                    $this->email->message($message);
                    if ($this->email->send()) {
                        $this->session->set_userdata('OTP', $OTP);
                        $this->session->set_userdata('UID', $uinfo->id);
                        $this->session->set_userdata('Role', 'Salesman');
                        $this->session->set_flashdata(['msg' => 'OTP sent your registered email address, kindly Enter OTP', 'class' => 'success']);
                        return redirect(base_url() . 'reset-password');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to sent OTP, kindly contact administrator', 'class' => 'danger']);
                        return redirect(base_url() . 'forgot-password');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You are not registered with us, kindly contact administrator', 'class' => 'danger']);
                    return redirect(base_url() . 'forgot-password');
                }
            }
        }

        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/forgot-password';
        return $this->load->view('layout', $data);
    }

    public function ResetPassword()
    {
        if ($_POST) {
            if ($this->session->userdata('OTP') == $this->input->post('otp')) {
                $this->form_validation->set_rules('otp', 'OTP', 'trim|numeric|required|min_length[6]|max_length[6]|xss_clean');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[15]|xss_clean');
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');

                if ($this->form_validation->run() == false) {
                    $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
                    $data['error'] = validation_errors();
                    $data['page'] = 'layout/reset-password';
                    return $this->load->view('layout', $data);
                } else {
                    $UserData = [
                        'password' => $this->input->post('password')
                    ];

                    if ($this->session->userdata('Role') == 'User') {
                        $ResetPassword = $this->user_model->updateAry('users', $UserData, ['id' => $this->session->userdata('UID'), 'status' => 1]);
                    } elseif ($this->session->userdata('Role') == 'Salesman') {
                        $ResetPassword = $this->user_model->updateAry('salesman', $UserData, ['id' => $this->session->userdata('UID'), 'status' => 1]);
                    }
                    if ($ResetPassword) {
                        $this->session->unset_userdata('OTP');
                        $this->session->unset_userdata('UID');
                        $this->session->unset_userdata('Role');
                        $this->session->set_flashdata(['msg' => 'Passowrd reset successfully, kindly login with new password', 'class' => 'success']);
                        return redirect(base_url() . 'signin');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Somethigs went wrong, try again', 'class' => 'danger']);
                        return redirect(base_url() . 'reset-password');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Entered OTP does not verify, kindly try again.', 'class' => 'danger']);
                return redirect(base_url() . 'signin');
            }
        }

        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/reset-password';
        return $this->load->view('layout', $data);
    }

    public function send_otp_sms()
    {
        $number = $this->input->post('number');
        $where = ['number' => $number];

        $query_mail = $this->user_model->getRow('users', $where);
        if ($query_mail) {
            $otp = random_int(100000, 999999);
            $user_id = $query_mail->id;
            $data['user_id'] = $user_id;
            $data['verification_code'] = $otp;
            $data['mobile_number'] = $number;
            $data['hash_key'] = md5($number) . md5($otp);
            $res = send_otp($number, $otp);
            if ($res) {
                $id = $this->user_model->saveAry('forget_password', $data);
                echo json_encode(["msg" => "Otp Sent to Your Number", 'id' => $id, 'status' => true]);
            } else {
                $msg = "Sorry meesage not sent!";
                echo json_encode(["msg" => $msg, 'status' => false]);
            }
        } else {
            $msg = "Sorry Mobile Number does't match!";
            echo json_encode(["msg" => $msg, 'status' => false]);
        }
    }

    public function verify_otp()
    {

        $mobile_number = $this->input->post('number');
        $otp = $this->input->post('otp');
        $id = $this->input->post('id');

        $where = ['mobile_number' => $mobile_number, 'verification_code' => $otp, 'id' => $id];
        $forget_password = $this->user_model->getRow('forget_password', $where);
        if ($forget_password) {
            // $data['verified'] = 1;
            // $this->user_model->updateAry('forget_password', $data, $where);
            echo json_encode(["msg" => "Otp is Valid", 'status' => true]);
        } else {
            $msg = "Entered Otp Is Wrong";
            echo json_encode(["msg" => $msg, 'status' => false]);
        }
    }

    public function verify_otp_with_pass()
    {

        $mobile_number = $this->input->post('number');
        $otp = $this->input->post('otp');
        $where = ['mobile_number' => $mobile_number, 'verification_code' => $otp];
        $forget_password = $this->user_model->getRow('forget_password', $where);
        if ($forget_password) {
            $data['verified'] = 1;
            $token = $forget_password->hash_key;
            $this->user_model->updateAry('forget_password', $data, $where);
            echo json_encode(["msg" => "Otp is Valid", 'token' => $token, 'status' => true]);
        } else {
            $msg = "Entered Otp Is Wrong";
            echo json_encode(["msg" => $msg, 'status' => false]);
        }
    }

    public function recover_password($token = null)
    {

        if (!empty($token)) {
            $where = ['hash_key' => $token, 'verified' => 1];
            $forget_password = $this->user_model->getRow('forget_password', $where);
            if ($forget_password) {
                $data['page'] = 'layout/recover_password';
                $this->load->view('layout/recover_password');
            } else {
                $this->session->set_flashdata(['msg' => 'Invalid Token !!!', 'class' => 'danger']);
                redirect('signin/forget');
            }
        }
    }

    public function reset_password()
    {

        $token = $this->input->post('token');
        if (empty($token)) {
            $msg = "Token expired!";
            echo json_encode(["msg" => $msg, 'status' => false]);
            return;
        }

        $password1 = $this->input->post('password1');
        $password2 = $this->input->post('password2');
        if ($password1 != $password2) {
            $msg = "Password does not match";
            echo json_encode(["msg" => $msg, 'status' => false]);
        }
        $where = ['hash_key' => $token, 'verified' => 1];
        $forget_password = $this->user_model->getRow('forget_password', $where);
        if ($forget_password) {
            $user_id = $forget_password->user_id;
            $data['password'] = $password2;
            $this->user_model->updateAry('users', $data, ['id' => $user_id]);
            $this->user_model->deleteRow('forget_password', $where);
            echo json_encode(["msg" => "Password Changed", 'status' => true]);
        } else {
            $msg = "Token expired";
            echo json_encode(["msg" => $msg, 'status' => false]);
        }
    }

    public function getAllUsers()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_number', null);
        $re = $this->db->get()->result();
        if ($re) {
            foreach ($re as $result) {
                $where = ['id' => $result->id];
                $data['user_number'] = rand(0, 999999);
                $this->user_model->updateAry('users', $data, $where);
            }
        }
    }
}
