<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        if ($this->session->userdata('roles') === null) {
            redirect('welcome');
        }
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        if (!$this->user_model->check_permission('config')) {
            $data['page'] = 'admin_details/error/permission_denied';
            // redirect(base_url() . 'index.php/dashboard');
        } else {
            $data['parties'] = $this->user_model->getResult('parties');
            $data['page'] = 'admin_details/config/index';
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


    public function smtp_setting()
    {

        $smtp_port = $this->input->post('smtp_port');
        $smtp_server = $this->input->post('smtp_server');
        $smtp_password = $this->input->post('smtp_password');
        $smtp_username = $this->input->post('smtp_username');
        $this->user_model->updateAry('general_settings', ['value' => $smtp_port], ['general_settings_id' => 73]);
        $this->user_model->updateAry('general_settings', ['value' => $smtp_server], ['general_settings_id' => 72]);
        $this->user_model->updateAry('general_settings',  ['value' => $smtp_password], ['general_settings_id' => 75]);
        $this->user_model->updateAry('general_settings',  ['value' => $smtp_username], ['general_settings_id' => 74]);
        $this->session->set_flashdata(['msg' => 'SMTP CHANGED', 'class' => 'success']);
        redirect('config');
    }

    public function send_pdf()
    {
        ini_set('memory_limit', '20M');
        // load library
        $this->load->library('pdf');
        $pdf = $this->pdf->load();

        // boost the memory limit if it's low ;)
        $html = $this->load->view('reports/someReport', array('var' => 123), true);
        // render the view into HTML

        $pdfFilePath = FCPATH . "attach/pdf_name.pdf";
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "F");

        $result = $this->email
            ->from('example@test.com', 'From name')
            ->to('test@test.com')
            // ->subject($subject)
            // ->message($body)
            ->attach($pdfFilePath)
            ->send();
        $this->email->clear($pdfFilePath);
        if ($result) {
            echo "Send";
            unlink($pdfFilePath); //for delete generated pdf file. 
        }
    }
}
