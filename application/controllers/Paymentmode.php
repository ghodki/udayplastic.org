<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Paymentmode extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function index()
        {
            $data['PaymentModes'] = $this->user_model->getResult('payment_mode');
            $data['page'] = "Paymentmode/index";
            
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function Create()
        {                    
            if ($_POST) {
                $this->form_validation->set_rules('PaymentMethod', 'Payment Method', 'trim|required|is_unique[payment_mode.payment_method]');
                if ($this->form_validation->run() == false) {
                    $data['page'] = "Paymentmode/PaymentModeForm";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {                    
                    $PaymentModeData = [
                        'payment_method' => $this->input->post('PaymentMethod'),
                        'created' => date('d-m-Y h:i:s A')
                    ];
                    $Result = $this->user_model->saveAry('payment_mode', $PaymentModeData);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Paymentmode');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Paymentmode/Create');
                    }
                }
            }
            
            $data['page'] = "Paymentmode/PaymentModeForm";

            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function Edit($Id)
        {
            if ($Id) {
                $data['Id'] = $Id;
                $data['PaymentModeData'] = $this->user_model->getRow('payment_mode', ['id' => $Id]);
                $data['page'] = "Paymentmode/PaymentModeForm";

                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Paymentmode');
            }
        }
        
        public function Update($Id)
        {            
            if ($Id) {
                if ($_POST) {
                    $this->form_validation->set_rules('PaymentMethod', 'Payment Method', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $data['Id'] = $Id;
                        $data['PaymentModeData'] = $this->user_model->getRow('payment_mode', ['id' => $Id]);
                        $data['page'] = "Paymentmode/PaymentModeForm";
                        $data['error'] = validation_errors();

                        return $this->load->view('inventory_sep/inventory', $data);
                    } else {
                        $PaymentModeId = $this->input->post('PaymentModeId');

                        $PaymentModeData = [
                            'payment_method' => $this->input->post('PaymentMethod'),
                            'updated' => date('d-m-Y h:i:s A')
                        ];
                        $Result = $this->user_model->updateAry('payment_mode', $PaymentModeData, ['id' => $PaymentModeId]);
                        
                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Paymentmode');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Paymentmode/Edit/' . $Id);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Paymentmode');
            }
        }
        
        public function ChangeStatus($Id)
        {
            if ($Id) {
                $PaymentModeRecord = $this->user_model->getRow('payment_mode', ['id' => $Id]);
                if ($PaymentModeRecord) {
                    if ($PaymentModeRecord->status == 1) {
                        $Status = 0;
                    } else {
                        $Status = 1;
                    }
                    $Result = $this->user_model->updateAry('payment_mode', ['status' => $Status], ['id' => $Id]);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Paymentmode');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Paymentmode');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'Paymentmode');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Paymentmode');
            }
        }
        
        public function Delete($Id)
        {
            if ($this->user_model->deleteRow('payment_mode', ['id' => $Id])) {
                $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                return redirect(base_url() . 'Paymentmode');
            } else {
                $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                return redirect(base_url() . 'Paymentmode');
            }
        }
    }