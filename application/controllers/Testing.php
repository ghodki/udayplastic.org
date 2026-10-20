<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testing extends CI_Controller
{
    function  __construct()
    {
        parent::__construct();

        // Load member model
        $this->load->model('member');
        $this->load->model('user_model');
    }

    function index()
    {

        // Load the member list views
        $this->load->view('a5print.html');
    }
    // public function sale_send()
    // {
    //     $res=send_sms_for_sale("7999620058","01/20/2001");
    //     print_r($res);
    //     die;
    // }
    

    public function send_service_request_aman()
    {
        $res=send_service_request("7999620058","Shopping","","Aman");
        print_r($res);
        die;
    }

    public function send_service_responces()
    {
        $res=send_service_responce("7999620058","Shopping","","Aman");
        print_r($res);
        die;
    }
    public function send_service_respo()
    {
        $res=send_service_responce("7999620058","Shopping");
        print_r($res);
        die;
    }
    public function idonknow()
    {
        $res=send_service_request("7999620058","Aman");
        print_r($res);
        die;
    }
    function a4()
    {

        // Load the member list views
        $this->load->view('a4print.html');
    }

    function thermal_print()
    {

        // Load the member list views
        $this->load->view('thermal_print.html');
    }


    function getLists()
    {
        $data = $row = array();

        // Fetch member's records
        $memData = $this->member->getRows($_POST);
        $i = $_POST['start'];
        foreach ($memData as $member) {
            $i++;
            $created = date('jS M Y', strtotime($member->created));
            $status = ($member->status == 1) ? 'Active' : 'Inactive';
            $data[] = array($i, $member->first_name, $member->last_name, $member->email, $member->gender, $member->country, $created, $status);
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->member->countAll(),
            "recordsFiltered" => $this->member->countFiltered($_POST),
            "data" => $data,
        );

        // Output to JSON format
        echo json_encode($output);
    }
    
      public function sendMail($email=null, $eventId=null,$booking_id=null)
    {
        $data['event_id']=$eventId;
        $data['booking_id']=$booking_id;
        $data['user_id']=$this->session->userdata('user_id');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.antahsukham.com';
        $config['smtp_port'] = '587';
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = 'info@antahsukham.com';
        $config['smtp_pass'] = '8ix^)A4WZ?kR';
        $config['charset'] = 'utf-8';
        $config['newline']  = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not      
        $config = array(
            'charset' => 'utf-8',
            'wordwrap' => TRUE,
            'mailtype' => 'html'
        );
        $this->email->initialize($config);
        $this->email->from('info@antahsukham.com', 'Antah Sukham');
        $this->email->to("chaurasiyaaman09@gmail.com");
        $this->email->subject('For Event Booking');
        $mesg = $this->load->view('email/event',$data, true);
        $mail = $this->email->send($mesg);
        if($mail)
        {
            echo "yes";

        }else
        {
            echo "no";
        }
    }
}
