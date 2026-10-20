<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class GenerateQR extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
        }
        
        public function index() 
        {
            $Data['QRData'] = $this->db->get('generateqr')->result();
            return $this->load->view('GenerateQR/index', $Data);
        }
        
        public function Create() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->form_validation->set_rules('URL', 'URL', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $Data['QRData'] = $this->db->get('generateqr')->result();
                    $Data['error'] = validation_errors();
                    return $this->load->view('GenerateQR/index', $Data);
                } else {
                    include_once(APPPATH . "third_party/phpqrcode/qrlib.php");
                    
                    $PNG_TEMP_DIR = 'uploads/GenerateQR/';
                    
                    if (!file_exists($PNG_TEMP_DIR)) {
                        mkdir($PNG_TEMP_DIR);
                    }
                    $time = time();
                    $filename = $PNG_TEMP_DIR . $time . '.png';
                    $errorCorrectionLevel = 'L';
                    
                    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H'))) {
                        $errorCorrectionLevel = $_REQUEST['level'];    
                    }

                    $matrixPointSize = 4;

                    if ($this->input->post('URL')) { 
                        //it's very important!
                        if (trim($this->input->post('URL')) == '') {
                            die('data cannot be empty! <a href="?">back</a>');
                        }
                        // user data
                        $filename = $PNG_TEMP_DIR . $time . md5($this->input->post('URL') . '|' . $errorCorrectionLevel.'|'.$matrixPointSize).'.png';
                        QRcode::png($this->input->post('URL'), $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
                    } else {    
                        //default data
                        echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
                        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
                    }
                    
                    $QRData = [
                        "URL" => $this->input->post('URL'),
                        "QRImage" => $filename,
                        "Created" => date('d-m-Y h:i:s A')
                    ];
                    $Result = $this->user_model->saveAry('generateqr', $QRData);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'GenerateQR');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'GenerateQR');
                    }
                }
            }
        }
        
        public function Delete($Id)
        {
            if (!empty($Id)) {
                $CompanyBranch = $this->user_model->getRow('generateqr', ['Id' => $Id]);
                if ($CompanyBranch) {
                    if ($this->user_model->deleteRow('generateqr', ['Id' => $Id])) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'GenerateQR');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'GenerateQR');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'GenerateQR');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Company branch id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'CompanyBranch');
            }
        }
    }