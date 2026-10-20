<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

require APPPATH . 'third_party/googleapiclient/vendor/autoload.php';

class Index extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['form', 'url', 'action_helper']);
        $this->load->model('user_model');
    }

    public function index()
    {
        // $data['sliders'] = $this->db->order_by('position', 'ASC')->limit(10)->get('slider')->result();
        // $data['MiniSliders'] = $this->db->order_by('id', 'DESC')->get('mini_slider')->result();
        // $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        // $data['products'] = $this->db->where('status', 1)->where('IsUpcoming IS NULL')->order_by('id', 'ASC')->get('web_products')->result();
        // $data['UpcomingProducts'] = $this->db->where('status', 1)->where('IsUpcoming', 'IsUpcoming')->order_by('id', 'ASC')->get('web_products')->result();
        // $data['page'] = 'layout/index';
        return $this->load->view('layout/index.html');
    }

    public function aboutus()
    {
        $data['MemberLeft'] = $this->db->where('Status', 1)->where('Position', 'LEFT')->get('members')->row();
        $data['MemberRight'] = $this->db->where('Status', 1)->where('Position', 'RIGHT')->get('members')->row();
        $data['Members'] = $this->db->where('Status', 1)->where('Position', 'BOTTOM')->order_by('id', 'DESC')->get('members')->result();
        $data['certificates'] = $this->db->get('aboutus_certificate')->result();
        $data['gstinfo'] = $this->db->get('gstinfo')->result();
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/aboutus';
        return $this->load->view('layout', $data);
    }

    public function products()
    {
        $data['products'] = $this->db->where('status', 1)->order_by('id', 'ASC')->get('web_products')->result();
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/products';
        return $this->load->view('layout', $data);
    }

    public function product_info($param = null)
    {
        if ($param) {
            $data['productinfo'] = $this->db->where(['status' => 1, 'url_name' => str_replace("-", " ", $param)])->get('web_products')->row();
        }
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/product-info';
        return $this->load->view('layout', $data);
    }

    public function career()
    {
        $data['Vacancies'] = $this->db->where(['IsDeleted' => 1, 'Status' => 1])->order_by('Id', 'DESC')->get('vacancies')->result();
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/career';
        return $this->load->view('layout', $data);
    }

    public function Vacancy($Id)
    {
        $data['Vacancy'] = $this->db->where(['Id' => $Id, 'IsDeleted' => 1, 'Status' => 1])->get('vacancies')->row();
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['VacancyId'] = $Id;
        $data['page'] = 'layout/Vacancy';
        return $this->load->view('layout', $data);
    }

    public function downloads()
    {
        $data['downloads'] = $this->db->where('status', 1)->order_by('id', 'DESC')->get('downloads')->result();
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/downloads';
        return $this->load->view('layout', $data);
    }

    public function contactus()
    {
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/contactus';
        return $this->load->view('layout', $data);
    }

    public function privacypolicy()
    {
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/privacy-policy';
        return $this->load->view('layout', $data);
    }

    public function termsofuse()
    {
        $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
        $data['page'] = 'layout/terms-of-use';
        return $this->load->view('layout', $data);
    }

    public function logindirect($id = null)
    {
        if (isset($id) && !empty($id)) {
            $where = ['id' => $id];
            $login_data = $this->user_model->getRow('users', $where);
            if ($login_data) {
                $time = date("h:i:sa");
                $date = date("d-m-Y");
                $m = date("m");
                $d = date("d");
                $y = date("Y");

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
                redirect('signin');
            } else {
                redirect('signin');
            }
        } else {
            redirect('signin');
        }
    }

    public function enquiry()
    {
        if ($_POST) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'numeric|trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|xss_clean');
            $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
                $data['page'] = 'layout/contactus';
                $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
                $data['error'] = validation_errors();
                return $this->load->view('layout', $data);
            } else {
                $name = $this->input->post('name');
                $cemail = $this->input->post('email');
                $mobile = $this->input->post('mobile');
                $cmessage = $this->input->post('message');

                $enquiry = [
                    'name' => $name,
                    'email' => $cemail,
                    'mobile' => $mobile,
                    'message' => $cmessage,
                    'created' => date('d-m-Y h:i:s A')
                ];

                $result = $this->user_model->saveAry('enquiry', $enquiry);

                if ($result) {
                    $message = ""
                        . "Hi,"
                        . "<br />"
                        . "Greetings from Uday Plastic!"
                        . "<br /><br />"
                        . "<b>We have received an customer enquiry on www.udayplastic.co.in</b>"
                        . "<br /><br />"
                        . "<table border=1 cellspacing=0 style='border: 1px solid #000000 !important; width: 100%;'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Name</th>"
                        . "<th style='text-align: left;'>Email</th>"
                        . "<th style='text-align: left;'>Mobile</th>"
                        . "<th style='text-align: left;'>Message</th>"
                        . "</tr>"
                        . "</thead>"
                        . "<tbody>"
                        . "<tr>"
                        . "<td>$name</td>"
                        . "<td>$cemail</td>"
                        . "<td>$mobile</td>"
                        . "<td>$cmessage</td>"
                        . "</tr>"
                        . "</tbody>"
                        . "</table>"
                        . "<br /><br />"
                        . "Thanks & Regards"
                        . "<br />"
                        . "Uday Plastic";
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
                    $this->email->to('pradeep.kumar632@gmail.com');
                    $this->email->subject('Enquiry send by customer via www.udayplastic.co.in');
                    $this->email->message($message);
                    $this->email->send();

                    $this->session->set_flashdata(['msg' => 'Yay! Congratulations, we have received your inquiry! you will be contacted shortly.', 'class' => 'success']);
                    return redirect(base_url() . 'contactus');
                } else {
                    $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
                    return redirect(base_url() . 'contactus');
                }
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Fields can not Be Empty', 'class' => 'danger']);
            return redirect(base_url() . 'contactus');
        }
    }

    public function enquiry_customer()
    {
        if ($_POST) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'numeric|trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|xss_clean');
            $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
                $data['sliders'] = $this->db->order_by('position', 'ASC')->limit(10)->get('slider')->result();
                $data['MiniSliders'] = $this->db->order_by('id', 'DESC')->get('mini_slider')->result();
                $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
                $data['products'] = $this->db->where('status', 1)->order_by('id', 'ASC')->get('web_products')->result();
                $data['page'] = 'layout/index';
                $data['error'] = validation_errors();
                return $this->load->view('layout', $data);
            } else {
                $name = $this->input->post('name');
                $cemail = $this->input->post('email');
                $mobile = $this->input->post('mobile');
                $cmessage = $this->input->post('message');

                $enquiry = [
                    'name' => $name,
                    'email' => $cemail,
                    'mobile' => $mobile,
                    'message' => $cmessage,
                    'created' => date('d-m-Y h:i:s A')
                ];

                $result = $this->user_model->saveAry('enquiry', $enquiry);

                if ($result) {
                    $message = ""
                        . "Hi,"
                        . "<br />"
                        . "Greetings from Uday Plastic!"
                        . "<br /><br />"
                        . "<b>We have received an customer enquiry on www.udayplastic.co.in</b>"
                        . "<br /><br />"
                        . "<table border=1 cellspacing=0 style='border: 1px solid #000000 !important; width: 100%;'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Name</th>"
                        . "<th style='text-align: left;'>Email</th>"
                        . "<th style='text-align: left;'>Mobile</th>"
                        . "<th style='text-align: left;'>Message</th>"
                        . "</tr>"
                        . "</thead>"
                        . "<tbody>"
                        . "<tr>"
                        . "<td>$name</td>"
                        . "<td>$cemail</td>"
                        . "<td>$mobile</td>"
                        . "<td>$cmessage</td>"
                        . "</tr>"
                        . "</tbody>"
                        . "</table>"
                        . "<br /><br />"
                        . "Thanks & Regards"
                        . "<br />"
                        . "Uday Plastic";

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
                    $this->email->to('pradeep.kumar632@gmail.com');
                    $this->email->subject('Enquiry send by customer via www.udayplastic.co.in');
                    $this->email->message($message);
                    $this->email->send();

                    $this->session->set_flashdata(['msg' => 'Yay! Congratulations, we have received your inquiry! you will be contacted shortly.', 'class' => 'success']);
                    return redirect(base_url() . "#Makeenquiry");
                } else {
                    $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
                    return redirect(base_url() . "#Makeenquiry");
                }
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Fields can not Be Empty', 'class' => 'danger']);
            return redirect(base_url . "#Makeenquiry");
        }
    }

    public function carrer_apply($Id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'numeric|trim|required|regex_match[/^[0-9]{10}$/]|min_length[10]|max_length[10]|xss_clean');
            $this->form_validation->set_rules('qualification', 'Qualification', 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
                $data['Branches'] = $this->db->where('Status', 1)->order_by('id', 'DESC')->get('branches')->result();
                $data['Vacancy'] = $this->db->where(['Id' => $Id, 'IsDeleted' => 1, 'Status' => 1])->get('vacancies')->row();
                $data['VacancyId'] = $Id;
                $data['page'] = 'layout/Vacancy';
                $data['error'] = validation_errors();
                return $this->load->view('layout', $data);
            } else {
                $VacancyId = $this->input->post('VacancyId');
                $Name = $this->input->post('name');
                $UserEmail = $this->input->post('email');
                $Mobile = $this->input->post('mobile');
                $Qualification = $this->input->post('qualification');
                $Address = $this->input->post('address');

                if ($_FILES['ressume']['name']) {
                    $filename = time() . $_FILES['ressume']['name'];
                    move_uploaded_file($_FILES['ressume']['tmp_name'], 'uploads/ressume/' . $filename);
                    $Ressume = $filename;
                } else {
                    $Ressume = "";
                }

                $VacancyInfo = $this->db->select('Id, UserId, CompId, CompanyBranchId')->where(['Id' => $VacancyId, 'IsDeleted' => 1])->get('vacancies')->row();
                $Date = date('Y-m-d');

                $UserApplication = [
                    'Date' => $Date,
                    'Name' => $Name,
                    'Email' => $UserEmail,
                    'Mobile' => $Mobile,
                    'Qualification' => $Qualification,
                    'Address' => $Address,
                    'Ressume' => $Ressume,
                    'VacancyId' => $VacancyInfo->Id,
                    'UserId' => $VacancyInfo->UserId,
                    'CompId' => $VacancyInfo->CompId,
                    'CompanyBranchId' => $VacancyInfo->CompanyBranchId,
                    'Created' => date('d-m-Y h:i:s A')
                ];

                $Result = $this->user_model->saveAry('carrer_apply', $UserApplication);

                if ($Result) {
                    $message = ""
                        . "Hi,"
                        . "<br />"
                        . "Greetings from Uday Plastic!"
                        . "<br /><br />"
                        . "<b>We have received an career application on www.udayplastic.co.in</b>"
                        . "<br /><br />"
                        . "<table border=1 cellspacing=0 style='border: 1px solid #000000 !important; width: 100%;'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Date</th>"
                        . "<td>$Date</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Name</th>"
                        . "<td>$Name</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Email</th>"
                        . "<td>$UserEmail</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Mobile</th>"
                        . "<td>$Mobile</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Highest Qualification</th>"
                        . "<td>$Qualification</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Address</th>"
                        . "<td>$Address</td>"
                        . "</tr>"
                        . "</thead>"
                        . "</table>"
                        . "<br /><br />"
                        . "Thanks & Regards"
                        . "<br />"
                        . "Uday Plastic";

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
                    $this->email->to('pradeep.kumar632@gmail.com');
                    $this->email->subject('Career application send by user via www.udayplastic.co.in');
                    $this->email->message($message);
                    $this->email->attach('./uploads/ressume/' . $Ressume);
                    $this->email->send();

                    $this->session->set_flashdata(['msg' => 'Yay! Congratulations, we have received your application! you will be contacted shortly.', 'class' => 'success']);
                    return redirect(base_url() . "career");
                } else {
                    $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
                    return redirect(base_url() . "career");
                }
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Fields can not Be Empty', 'class' => 'danger']);
            return redirect(base_url . "career");
        }
    }

    public function printInvoice($Id)
    {
        if (!$Id) {
            return redirect(base_url() . 'Sale');
        }

        $where = ['id' => $Id];
        $result = $this->user_model->getRow('sales', $where);
        $invoiceProductWhere = ['invoiceID' => $Id, 'type' => 'Sale'];
        if (isset($result) && !empty($result)) {
            $this->session->set_userdata('user_id', $result->user_id);
            $this->session->set_userdata('comp_id', $result->comp_id);
            if ($result->CompanyBranchId) {
                $this->session->set_userdata('CompBranchId', $result->CompanyBranchId);
            }
            $products = $this->user_model->getAry('invoice_product', '', '', '', $invoiceProductWhere);
            $result->product = $products;
            $data['result'] = $result;
            return $this->load->view('inventory_sep/sale/print/pdf/a5', $data);
        } else {
            $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
            return redirect(base_url() . 'Sale');
        }
    }

    public function printEInvoice($Id)
    {
        if (!$Id) {
            return redirect(base_url() . 'Estimate');
        }

        $where = ['id' => $Id];
        $result = $this->user_model->getRow('estimates', $where);
        $invoiceProductWhere = ['invoiceID' => $Id, 'type' => 'Estimate'];
        if (isset($result) && !empty($result)) {
            $this->session->set_userdata('user_id', $result->user_id);
            $this->session->set_userdata('comp_id', $result->comp_id);
            if ($result->CompanyBranchId) {
                $this->session->set_userdata('CompBranchId', $result->CompanyBranchId);
            }
            $products = $this->user_model->getAry('estimate_product', '', '', '', $invoiceProductWhere);
            $result->product = $products;
            $data['result'] = $result;
            return $this->load->view('inventory_sep/estimate/print/pdf/a5', $data);
        } else {
            $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
            return redirect(base_url() . 'Estimate');
        }
    }

    public function receipt($id)
    {
        $data['receipt'] = $this->user_model->getRow('payment_receipt_transaction', ['id' => $id]);

        $this->load->library('pdf');
        $html = $this->load->view('payment_receipt/email/print-receipt', $data, true);
        $this->pdf->loadHtml($html);
        $this->pdf->set_option('isRemoteEnabled', TRUE);
        $this->pdf->set_paper('A4', 'landscape');
        $this->pdf->render();
        $this->pdf->output();
        $this->pdf->stream(time() . ".pdf", array("Attachment" => true));
    }

    public function EnquiryForm($ProductId)
    {
        $data['ProductName'] = get_title('web_products', ['id' => $ProductId], 'name');
        $data['ProductId'] = $ProductId;
        return $this->load->view('layout/EnquiryForm', $data);
    }


    public function Save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('Organization', 'Company/Organization', 'trim|required');
            $this->form_validation->set_rules('ContactPerson', 'ContactPerson', 'trim|required');
            $this->form_validation->set_rules('Mobile', 'Mobile', 'trim|numeric|required');
            $this->form_validation->set_rules('ProductName', 'ProductName', 'trim|required');
            $this->form_validation->set_rules('Quantity', 'Quantity', 'trim|numeric|required');
            if ($this->form_validation->run() == false) {
                echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                return;
            } else {
                $Organization = $this->input->post('Organization');
                $ContactPerson = $this->input->post('ContactPerson');
                $Mobile = $this->input->post('Mobile');
                $Email = $this->input->post('Email');
                $ProductName = $this->input->post('ProductName');
                $Quantity = $this->input->post('Quantity');
                $Remark = $this->input->post('Remark');
                $Created = date('d-m-Y h:i:s A');

                $EnquiryData = [
                    'Date' => date('Y-m-d'),
                    'Organization' => $Organization,
                    'ContactPerson' => $ContactPerson,
                    'Mobile' => $Mobile,
                    'Email' => $Email,
                    'ProductId' => $this->input->post('ProductId'),
                    'ProductName' => $ProductName,
                    'Quantity' => $Quantity,
                    'Remark' => $Remark,
                    'Created' => $Created
                ];

                $Result = $this->user_model->saveAry('product_enquiry', $EnquiryData);
                if (!empty($Result)) {
                    $message = ""
                        . "Hi,"
                        . "<br />"
                        . "Greetings from Uday Plastic!"
                        . "<br /><br />"
                        . "<b>We have received an product enquiry on www.udayplastic.co.in</b>"
                        . "<br /><br />"
                        . "<table border=1 cellspacing=0 style='border: 1px solid #000000 !important; width: 100%;'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Company/Organization Name</th>"
                        . "<td>$Organization</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Contact Person</th>"
                        . "<td>$ContactPerson</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Mobile</th>"
                        . "<td>$Mobile</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Email</th>"
                        . "<td>$Email</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Product Name</th>"
                        . "<td>$ProductName</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Quantity</th>"
                        . "<td>$Quantity</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Remark</th>"
                        . "<td>$Remark</td>"
                        . "</tr>"
                        . "<tr>"
                        . "<th style='text-align: left;'>Date & Time</th>"
                        . "<td>$Created</td>"
                        . "</tr>"
                        . "</thead>"
                        . "</table>"
                        . "<br /><br />"
                        . "Thanks & Regards"
                        . "<br />"
                        . "Uday Plastic";

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
                    $this->email->to('pradeep.kumar632@gmail.com');
                    $this->email->subject('Product enquiry send by customer via www.udayplastic.co.in');
                    $this->email->message($message);
                    $this->email->send();

                    echo json_encode(['Status' => true, 'Message' => 'Record save successfully, we will contact you soon.!!!']);
                    return;
                } else {
                    echo json_encode(['Status' => false, 'Message' => 'Unable to save record']);
                    return;
                }
            }
        }
    }

    public function DBBackUpDownload()
    {
        ini_set('memory_limit', '-1');

        $this->load->dbutil();
        $this->load->helper('download');

        $Database = 'udayplasticco_udayplastic';
        //            $Database = 'udayplastic';
        $Query = $this->db->query("SHOW TABLES FROM $Database");
        $Tables = [];
        foreach ($Query->result_array() as $Row) {
            $Tables[] = $Row["Tables_in_$Database"];
        }

        $Prefs = array(
            'tables' => $Tables,
            'ignore' => array(),
            'format' => 'gzip',
            'filename' => "$Database-backup.sql.gz",
            'add_drop' => TRUE,
            'add_insert' => TRUE,
            'newline' => "\n"
        );

        $BackUp = $this->dbutil->backup($Prefs);
        $Filename = $Database . '-backup-' . date('Y-m-d_H-i-s') . '.gz';
        force_download($Filename, $BackUp);
    }
}