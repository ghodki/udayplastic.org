<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class PaymentReceipt extends CI_Controller 
    {
        public function __construct() 
        {
            parent::__construct();
            
            $this->load->helper(['form', 'url', 'action_helper', 'date']);
            
            $this->load->model('user_model');
            
            $this->load->library('ajax_pagination');
            $this->perPage = 10;
        }

        public function index() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->pay_receipt_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['company_name'] = $this->input->post('company_name');
                    $data['number'] = $this->input->post('number');
                    $data['email'] = $this->input->post('email');
                    $data['address'] = $this->input->post('address');
                    $data['pan'] = $this->input->post('pan');
                    $data['created_at'] = date('d-m-Y H:i:s');

                    $return = $this->user_model->saveAry('payment_receipt_company', $data);
                    
                    $cdata['user_id'] = $this->session->userdata('user_id');
                    $cdata['payment_receipt_company_id'] = $return;
                    $cdata['created_at'] = date('d-m-Y H:i:s');
                    
                    $this->user_model->saveAry('payment_receipt_config', $cdata);
                    
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt');
                    }
                }
            }
            $user = $this->session->userdata('user_id');
            $where = ['user_id' => $user];
            $data['payment_receipt'] = $this->user_model->getResult('payment_receipt_company', $where);
            $data['page'] = 'payment_receipt/company/index';
            $this->load->view('admin', $data);
        }
        
        protected function pay_receipt_rules() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $rules = array(
                array(
                    'field' => 'company_name',
                    'label' => 'Company Name',
                    'rules' => 'trim|required|xss_clean|callback_check_same_company_name'
                ),
                array(
                    'field' => 'number',
                    'label' => 'Mobile Number',
                    'rules' => 'trim|required|xss_clean|callback_check_same_mobile'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|xss_clean|required'
                ),
                array(
                    'field' => 'address',
                    'label' => 'Address',
                    'rules' => 'trim|xss_clean'
                )
            );
            return $rules;
        }
        
        protected function pay_receipt_rules_edit() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $rules = array(
                array(
                    'field' => 'company_name',
                    'label' => 'Company Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'number',
                    'label' => 'Mobile Number',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|xss_clean|required'
                ),
                array(
                    'field' => 'address',
                    'label' => 'Address',
                    'rules' => 'trim|xss_clean'
                )
            );
            return $rules;
        }

        protected function transaction_rules() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $rules = array(
                array(
                    'field' => 'paymentcategory',
                    'label' => 'Payment Category',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'receipt_id',
                    'label' => 'Receip Number',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'transaction_type',
                    'label' => 'Transaction Type',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'transaction_id',
                    'label' => 'Transaction Id',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'transaction_date',
                    'label' => 'Transaction Date',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'bank_name',
                    'label' => 'Bank Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'donor_pan',
                    'label' => 'Donor Pan',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'amount',
                    'label' => 'Amount',
                    'rules' => 'trim|required|xss_clean|numeric'
                )
            );
            return $rules;
        }
        
        protected function edit_transaction_rules() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $rules = array(
                array(
                    'field' => 'paymentcategory',
                    'label' => 'Payment Category',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'transaction_type',
                    'label' => 'Transaction Type',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'transaction_id',
                    'label' => 'Transaction Id',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'transaction_date',
                    'label' => 'Transaction Date',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'bank_name',
                    'label' => 'Bank Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'donor_pan',
                    'label' => 'Donor Pan',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'amount',
                    'label' => 'Amount',
                    'rules' => 'trim|required|xss_clean|numeric'
                )
            );
            return $rules;
        }


        protected function party_rules() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'Party Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'usertype',
                    'label' => 'User Type',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'number',
                    'label' => 'Mobile Number',
                    'rules' => 'trim|required|xss_clean|numeric|regex_match[/^[0-9]{10}$/]'
                ),
                array(
                    'field' => 'pan',
                    'label' => 'PAN Number',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'state_id',
                    'label' => 'State',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }

        protected function category_rules() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $rules = array(
                array(
                    'field' => 'category',
                    'label' => 'Category Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }
        
        public function check_same_company_name() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if (!empty($this->input->post('company_id'))) {
                $company_id = $this->input->post('company_id');
            } else {
                $company_id = '';
            }
            $user = $this->session->userdata('user_id');
            $company_name = $this->input->post('name');
            $where = ['id!=' => $company_id, 'company_name' => $company_name, 'user_id' => $user];
            $return_value = $this->user_model->getRow('payment_receipt_company', $where);
            if ($return_value) {
                $this->form_validation->set_message('check_same_company_name', 'Company Name Can not be same! ');
                return false;
            }
            return true;
        }

        public function check_same_mobile() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if (!empty($this->input->post('company_id'))) {
                $company_id = $this->input->post('company_id');
            } else {
                $company_id = '';
            }
            $number = $this->input->post('number');
            $user = $this->session->userdata('user_id');
            $where = ['id!=' => $company_id, 'number' => $number, 'user_id' => $user];
            $return_value = $this->user_model->getRow('payment_receipt_company', $where);
            if ($return_value) {
                $this->form_validation->set_message('check_same_mobile', 'Mobile Number can not be same!! ');
                return false;
            }
            return true;
        }

        public function receipt($id) 
        {
            $data['receipt'] = $this->user_model->getRow('payment_receipt_transaction', ['id' => $id]);
            
            $this->load->view('payment_receipt/receipt', $data);
        }
        
        public function mailcontent($id)
        {
            $data['id'] = $id;
            $data['transactioninfo'] = $this->user_model->getRow('payment_receipt_transaction', ['id' => $id]);
            $data['maildata'] = $this->user_model->getRow('payment_receipt_config', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
            
            $this->load->view('payment_receipt/transaction/content', $data);
        }

        public function sendmail ()
        {
            $this->form_validation->set_rules('from', 'From', 'trim|required');
            $this->form_validation->set_rules('to', 'To', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('body', 'Body', 'trim|required');
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {
                $data['receipt'] = $this->user_model->getRow('payment_receipt_transaction', ['id' => $this->input->post('id')]);
                
                $this->load->library('pdf');
                $html = $this->load->view('payment_receipt/email/print-receipt', $data, true);
                $this->pdf->loadHtml($html);
                $this->pdf->set_option('isRemoteEnabled', TRUE);
                $this->pdf->set_paper('A4', 'landscape');
                $this->pdf->render();
                $output = $this->pdf->output();
                $path = './uploads/receiptfile/' . time() . '.pdf';
                file_put_contents($path, $output);
                
                $rurl = base_url() . "receipt/" . $this->input->post('id');
                $message = $this->input->post('body')
                            . "\r\n\r\n"
                            . "You can download or print receipt copy by clicking below link: "
                            . "\r\n"
                            . "$rurl";
                
                if ($path) {
                    $this->email->from($this->input->post('from'));
                    $this->email->to($this->input->post('to')); 
                    $this->email->subject($this->input->post('subject'));
                    $this->email->message($message);
                    $this->email->attach($path);

                    if ($this->email->send()) {
                        unlink($path); //for delete generated pdf file.
                        $this->session->set_flashdata(['msg' => 'Mail send Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/transaction');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/transaction');
                    }
                } else {
                    $this->email->from($this->input->post('from'));
                    $this->email->to($this->input->post('to')); 
                    $this->email->subject($this->input->post('subject'));
                    $this->email->message($message);
                    
                    if ($this->email->send()) {
                        $this->session->set_flashdata(['msg' => 'Mail Sent without File(Due To File Error).', 'class' => 'success']);
                        redirect('paymentReceipt/transaction');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/transaction');
                    }  
                }
            }
        }
        
        public function sendsms($id)
        {
            $rinfo = $this->user_model->getRow('payment_receipt_transaction', ['id' => $id]);
            if (get_title('payment_receipt_party', ['id' => $rinfo->party_id], 'number')) {
                if (payment_receipt_sms(get_title('payment_receipt_party', ['id' => $rinfo->party_id], 'number'), $rinfo->id) == "true") {
                    $this->session->set_flashdata(['msg' => 'SMS send Successfully.', 'class' => 'success']);
                    redirect('paymentReceipt/transaction');
                } else {
                    $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                    redirect('paymentReceipt/transaction');
                }
            }
        }
        
        public function edit($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->pay_receipt_rules_edit();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['company_name'] = $this->input->post('company_name');
                    $data['number'] = $this->input->post('number');
                    $data['email'] = $this->input->post('email');
                    $data['address'] = $this->input->post('address');
                    $data['pan'] = $this->input->post('pan');
                    $data['updated_at'] = date('d-m-Y H:i:s');
                    
                    $return = $this->user_model->updateAry('payment_receipt_company', $data, ['id' => $id]);
                    
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt');
                    }
                }
            }
            
            $user = $this->session->userdata('user_id');
            $where = ['user_id' => $user, 'id' => $id];
            $data['result'] = $this->user_model->getRow('payment_receipt_company', $where);
            
            $data['page'] = 'payment_receipt/company/edit';
            $this->load->view('admin', $data);
        }

        public function viewclient($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if (isset($id) && $id != '') {
                $user_id = $this->session->userdata('user_id');
                $where = ['id' => $id, 'user_id' => $user_id];
                $return = $this->user_model->getRow('payment_receipt_company', $where);
                if ($return) {
                    $this->session->set_userdata('payment_receipt_company_id', $id);
                    $data['result'] = $return;
                    $total_party = $this->user_model->getResult('payment_receipt_party', ['user_id' => $user_id, 'payment_receipt_company_id' => $id]);
                    $total_transaction = $this->user_model->getResult('payment_receipt_transaction', ['user_id' => $user_id, 'payment_receipt_company_id' => $id]);
                    $data['total_party'] = count($total_party);
                    $data['total_transaction'] = count($total_transaction);
                    $data['page'] = 'dashboard';
                    $this->load->view('payment_receipt/main', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                    redirect('paymentReceipt');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                redirect('paymentReceipt');
            }
        }

        public function partysave() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->party_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['payment_receipt_company_id'] = $this->session->userdata('payment_receipt_company_id');
                    $data['usertype'] = $this->input->post('usertype');
                    $data['party_name'] = $this->input->post('name');
                    $data['number'] = $this->input->post('number');
                    $data['email'] = $this->input->post('email');
                    $data['pan'] = $this->input->post('pan');
                    $data['state_id'] = $this->input->post('state_id');
                    $data['city'] = $this->input->post('city');
                    $data['address'] = $this->input->post('address');
                    $data['pin'] = $this->input->post('pin');
                    $data['created_at'] = date('d-m-Y H:i:s');
                    $return = $this->user_model->saveAry('payment_receipt_party', $data);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Party Added Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/party');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/party');
                    }
                }
            }
            $data['page'] = 'party/index';
            $this->load->view('payment_receipt/main', $data);
        }
        
        public function partysave2() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->party_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                    $data['statelist'] = $this->user_model->getResult('state_list');
                } else {
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['payment_receipt_company_id'] = $this->session->userdata('payment_receipt_company_id');
                    $data['usertype'] = $this->input->post('usertype');
                    $data['party_name'] = $this->input->post('name');
                    $data['number'] = $this->input->post('number');
                    $data['email'] = $this->input->post('email');
                    $data['pan'] = $this->input->post('pan');
                    $data['state_id'] = $this->input->post('state_id');
                    $data['city'] = $this->input->post('city');
                    $data['address'] = $this->input->post('address');
                    $data['pin'] = $this->input->post('pin');
                    $data['created_at'] = date('d-m-Y H:i:s');
                    
                    $return = $this->user_model->saveAry('payment_receipt_party', $data);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Party Added Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/transaction');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/transaction');
                    }
                }
            }
        }

        public function editParty($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->party_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['usertype'] = $this->input->post('usertype');
                    $data['party_name'] = $this->input->post('name');
                    $data['address'] = $this->input->post('address');
                    $data['number'] = $this->input->post('number');
                    $data['email'] = $this->input->post('email');
                    $data['pan'] = $this->input->post('pan');
                    $data['state_id'] = $this->input->post('state_id');
                    $data['city'] = $this->input->post('city');
                    $data['address'] = $this->input->post('address');
                    $data['pin'] = $this->input->post('pin');
                    $data['updated_at'] = date('d-m-Y H:i:s');
                    $return = $this->user_model->updateAry('payment_receipt_party', $data, ['id' => $id]);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Party Updated Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/party');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/party');
                    }
                }
            }
            $user = $this->session->userdata('user_id');
            $where = ['user_id' => $user, 'id' => $id];
            $data['result'] = $this->user_model->getRow('payment_receipt_party', $where);
            $data['statelist'] = $this->user_model->getResult('state_list');
                        
            $data['page'] = 'party/edit';
            $this->load->view('payment_receipt/main', $data);
        }
        
        public function editParty2() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->party_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $id = $this->input->post('id');
                    $data['usertype'] = $this->input->post('usertype');
                    $data['party_name'] = $this->input->post('name');
                    $data['address'] = $this->input->post('address');
                    $data['number'] = $this->input->post('number');
                    $data['email'] = $this->input->post('email');
                    $data['pan'] = $this->input->post('pan');
                    $data['state_id'] = $this->input->post('state_id');
                    $data['city'] = $this->input->post('city');
                    $data['address'] = $this->input->post('address');
                    $data['pin'] = $this->input->post('pin');
                    $data['updated_at'] = date('d-m-Y H:i:s');
                    $return = $this->user_model->updateAry('payment_receipt_party', $data, ['id' => $id]);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Party Updated Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/transaction');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/transaction');
                    }
                }
            }
        }

        public function loadparty($id)
        {
            $data['id'] = $id;
            $data['partydata'] = $this->user_model->getRow('payment_receipt_party', ['id' => $id]);
            
            $this->load->view('payment_receipt/transaction/loadparty', $data);
        }
        
        public function party() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $user_id = $this->session->userdata('user_id');
            $data = array();
            // Get record count 
            $conditions['returnType'] = 'count';
            $user_id = $this->session->userdata('user_id');
            $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')];
            $totalRec = $this->user_model->getCommonRows($conditions, 'payment_receipt_party');
            // Pagination configuration 
            $config['target'] = "#dataList";
            $config['base_url'] = base_url('paymentReceipt/load_party');
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $this->perPage;
            $config['link_func'] = 'searchFilter';

            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['limit'] = $this->perPage;
            $data['result'] = $this->user_model->getCommonRows($conditions, 'payment_receipt_party');
            $data['statelist'] = $this->user_model->getResult('state_list');
            // Load the list page view
            $data['page'] = 'party/index';
            $this->load->view('payment_receipt/main', $data);
        }

        function load_party() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            // Define offset 
            $page = $this->input->post('page');
            if (!$page) {
                $offset = 0;
            } else {
                $offset = $page;
            }

            $keywords = $this->input->post('keywords');
            $sortBy = $this->input->post('sortBy');
            $pagelength = $this->input->post('pagelength');
            $column_filter = $this->input->post('column_filter');
            $usertype = $this->input->post('usertype');

            $user_id = $this->session->userdata('user_id');

            if (empty($usertype)) {
                $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')];
            } else {
                $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'), 'usertype' => $usertype];
            }
            // Get record count 
            $conditions['returnType'] = 'count';
            if (!empty($keywords)) {
                $conditions['search']['keywords'] = $keywords;
            }
            if (!empty($sortBy)) {
                $conditions['search']['sortBy'] = $sortBy;
            }
            $totalRec = $this->user_model->getCommonRows($conditions, 'payment_receipt_party', $column_filter);

            // Pagination configuration 
            $config['target'] = "#dataList";
            $config['base_url'] = base_url('user/load_party');
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $pagelength;
            $config['link_func'] = 'searchFilter';
            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['start'] = $offset;
            $conditions['limit'] = $pagelength;
            unset($conditions['returnType']);
            $data['result'] = $this->user_model->getCommonRows($conditions, 'payment_receipt_party', $column_filter);
            // Load the data list view 
            $this->load->view('payment_receipt/party/load_party', $data, false);
        }

        public function delete_party($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if (isset($id) && $id != '') {
                $user_id = $this->session->userdata('user_id');
                $where = ['id' => $id, 'user_id' => $user_id];
                $return = $this->user_model->deleteRow('payment_receipt_party', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Party Deleted.', 'class' => 'success']);
                    redirect('paymentReceipt/party');
                } else {
                    $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                    redirect('paymentReceipt/party');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                redirect('paymentReceipt/party');
            }
        }
        
        public function category()
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            // Load the list page view
            $data['page'] = 'category/index';
            $this->load->view('payment_receipt/main', $data);            
        }
        
        function load_category() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            // Define offset 
            $page = $this->input->post('page');
            if (!$page) {
                $offset = 0;
            } else {
                $offset = $page;
            }

            $keywords = $this->input->post('keywords');
            $sortBy = $this->input->post('sortBy');
            $pagelength = $this->input->post('pagelength');
            $column_filter = $this->input->post('column_filter');
            $user_id = $this->session->userdata('user_id');
            
            $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')];
            
            // Get record count 
            $conditions['returnType'] = 'count';
            if (!empty($keywords)) {
                $conditions['search']['keywords'] = $keywords;
            }
            if (!empty($sortBy)) {
                $conditions['search']['sortBy'] = $sortBy;
            }
            $totalRec = $this->user_model->getCommonRows($conditions, 'payment_receipt_category', $column_filter);

            // Pagination configuration 
            $config['target'] = '#dataList';
            $config['base_url'] = base_url('user/load_category');
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $pagelength;
            $config['link_func'] = 'searchFilter';
            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['start'] = $offset;
            $conditions['limit'] = $pagelength;
            unset($conditions['returnType']);
            $data['result'] = $this->user_model->getCommonRows($conditions, 'payment_receipt_category', $column_filter);
            // Load the data list view 
            $this->load->view('payment_receipt/category/load_category', $data, false);
        }
        
        public function categorysave() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->category_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['date'] = date('d-m-Y');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['payment_receipt_company_id'] = $this->session->userdata('payment_receipt_company_id');
                    $data['category'] = $this->input->post('category');
                    $data['prefix'] = $this->input->post('prefix');
                    $data['receipt_number'] = $this->input->post('receipt_number');
                    $data['description'] = $this->input->post('description');
                    $data['status'] = "1";
                    $data['created_at'] = date('d-m-Y H:i:s');
                    
                    $return = $this->user_model->saveAry('payment_receipt_category', $data);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Category Added Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/category');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/category');
                    }
                }
            }
            
            $data['page'] = 'category/index';
            $this->load->view('payment_receipt/main', $data);
        }
        
        public function editcategory($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->category_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['receipt_number'] = $this->input->post('receipt_number');
                    $data['category'] = $this->input->post('category');
                    $data['prefix'] = $this->input->post('prefix');
                    $data['description'] = $this->input->post('description');
                    $data['updated_at'] = date('d-m-Y H:i:s');
                    $return = $this->user_model->updateAry('payment_receipt_category', $data, ['id' => $id]);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Category Updated Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/category');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/category');
                    }
                }
            }
            $user = $this->session->userdata('user_id');
            $where = ['user_id' => $user, 'id' => $id];
            $data['result'] = $this->user_model->getRow('payment_receipt_category', $where);
            $data['page'] = 'category/edit';
            $this->load->view('payment_receipt/main', $data);
        }
        
        public function delete_category($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if (isset($id) && $id != '') {
                $user_id = $this->session->userdata('user_id');
                $where = ['id' => $id, 'user_id' => $user_id];
                $return = $this->user_model->deleteRow('payment_receipt_category', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Category Deleted.', 'class' => 'success']);
                    redirect('paymentReceipt/category');
                } else {
                    $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                    redirect('paymentReceipt/category');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                redirect('paymentReceipt/category');
            }
        }
        
        public function config()
        {
            if ($this->user_model->getRow('payment_receipt_config', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')])) {
                if ($_POST) {
                    if ($_FILES['logo']['name']) {
                        unlink('uploads/complogo/' . $this->input->post('oldlogo'));
                        $filename = time() . $_FILES['logo']['name'];
                        move_uploaded_file($_FILES['logo']['tmp_name'], 'uploads/complogo/' . $filename);
                        $data['logo'] = $filename;
                    } else {
                        $data['logo'] = $this->input->post('oldlogo');
                    }

                    if ($_FILES['signature']['name']) {
                        unlink('uploads/signature/' . $this->input->post('oldsignature'));
                        $filename2 = time() . $_FILES['signature']['name'];
                        move_uploaded_file($_FILES['signature']['tmp_name'], 'uploads/signature/' . $filename2);
                        $data['signature'] = $filename2;
                    } else {
                        $data['signature'] = $this->input->post('oldsignature');
                    }
                    
                    $data['address'] = $this->input->post('address');
                    $data['receipt_color'] = $this->input->post('receipt_color');
                    $data['mailfrom'] = $this->input->post('mailfrom');
                    $data['subject'] = $this->input->post('subject');
                    $data['body'] = $this->input->post('body');
                    $data['sms_status'] = $this->input->post('sms_status');
                    $data['sms_sender_id'] = $this->input->post('sms_sender_id');
                    $data['sms_body'] = $this->input->post('sms_body');
                    $data['updated_at'] = date('d-m-Y H:i:s');

                    $return = $this->user_model->updateAry('payment_receipt_config', $data, ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Config updated successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/config');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/config');
                    }
                }
            } else {
                if ($_POST) {
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['payment_receipt_company_id'] = $this->session->userdata('payment_receipt_company_id');
                    
                    if ($_FILES['logo']['name']) {
                        $filename = time() . $_FILES['logo']['name'];
                        move_uploaded_file($_FILES['logo']['tmp_name'], 'uploads/complogo/' . $filename);
                        $data['logo'] = $filename;
                    } else {
                        $data['logo'] = "";
                    }

                    if ($_FILES['signature']['name']) {
                        $filename2 = time() . $_FILES['signature']['name'];
                        move_uploaded_file($_FILES['signature']['tmp_name'], 'uploads/signature/' . $filename2);
                        $data['signature'] = $filename2;
                    } else {
                        $data['signature'] = "";
                    }

                    $data['address'] = $this->input->post('address');
                    $data['receipt_color'] = $this->input->post('receipt_color');
                    $data['mailfrom'] = $this->input->post('mailfrom');
                    $data['subject'] = $this->input->post('subject');
                    $data['body'] = $this->input->post('body');
                    $data['sms_status'] = $this->input->post('sms_status');
                    $data['sms_sender_id'] = $this->input->post('sms_sender_id');
                    $data['sms_body'] = $this->input->post('sms_body');
                    $data['created_at'] = date('d-m-Y H:i:s');

                    $return = $this->user_model->saveAry('payment_receipt_config', $data);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Config insert successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/config');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to insert config record.', 'class' => 'danger']);
                        redirect('paymentReceipt/config');
                    }
                }   
            }
            
            $data['result'] = $this->user_model->getRow('payment_receipt_config', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
            $data['page'] = 'config/index';
            $this->load->view('payment_receipt/main', $data);
        }
        
        public function transaction_save() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->transaction_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['date'] = date('d-m-Y');
                    $data['receipt_date'] = $this->input->post('receipt_date');
                    $data['paymentcategory'] = $this->input->post('paymentcategory');
                    $data['receipt_id'] = $this->input->post('receipt_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['party_id'] = $this->input->post('party');
                    $data['payment_receipt_company_id'] = $this->session->userdata('payment_receipt_company_id');
                    $data['transaction_type'] = $this->input->post('transaction_type'); 
                    $data['transaction_id'] = $this->input->post('transaction_id'); 
                    $data['transaction_date'] = $this->input->post('transaction_date'); 
                    $data['bank_name'] = $this->input->post('bank_name');
                    $data['donor_pan'] = $this->input->post('donor_pan'); 
                    $data['amount'] = $this->input->post('amount');
                    $data['remark'] = $this->input->post('remark');
                    $data['created_at'] = date('d-m-Y H:i:s');
                    
                    $return = $this->user_model->saveAry('payment_receipt_transaction', $data);
                    
                    if ($this->input->post('send_sms') == "Yes") {
                        if (get_title('payment_receipt_config', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')], 'sms_status') == "Enable") {
                            $mobile = get_title('payment_receipt_party', ['id' => $this->input->post('party')], 'number');
                            $smsconfig = $this->user_model->getRow('payment_receipt_config', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
                            $senderid = $smsconfig->sms_sender_id;
                            $smsbody = $smsconfig->sms_body;
                            if (!empty($senderid) && !empty($smsbody)) {
                                payment_receipt_sms_on_demand($mobile, $senderid, $smsbody, $return);
                            }
                        }
                    }
                    
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/transaction');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/transaction');
                    }
                }
            }
            
            $data['page'] = 'transaction/index';
            $this->load->view('payment_receipt/main', $data);
        }

        public function edit_transaction($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if ($_POST) {
                $rules = $this->edit_transaction_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    $data['receipt_date'] = $this->input->post('receipt_date');
                    $data['paymentcategory'] = $this->input->post('paymentcategory');
                    $data['party_id'] = $this->input->post('party');
                    $data['transaction_type'] = $this->input->post('transaction_type');
                    $data['transaction_id'] = $this->input->post('transaction_id'); 
                    $data['transaction_date'] = $this->input->post('transaction_date'); 
                    $data['bank_name'] = $this->input->post('bank_name');
                    $data['donor_pan'] = $this->input->post('donor_pan'); 
                    $data['amount'] = $this->input->post('amount');
                    $data['remark'] = $this->input->post('remark');
                    $data['updated_at'] = date('d-m-Y H:i:s');
                    
                    $return = $this->user_model->updateAry('payment_receipt_transaction', $data, ['id' => $id]);
                    if ($return) {
                        $this->session->set_flashdata(['msg' => 'Transaction Updated Successfully.', 'class' => 'success']);
                        redirect('paymentReceipt/transaction');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('paymentReceipt/transaction');
                    }
                }
            }
            
            $user = $this->session->userdata('user_id');
            $where = ['user_id' => $user, 'id' => $id];
            $data['result'] = $this->user_model->getRow('payment_receipt_transaction', $where);
            $data['category'] = $this->user_model->getResult('payment_receipt_category', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
            
            $data['page'] = 'transaction/edit';
            $this->load->view('payment_receipt/main', $data);
        }

        public function transaction() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $user_id = $this->session->userdata('user_id');
            $data = array();
            // Get record count 
            $conditions['returnType'] = 'count';
            $user_id = $this->session->userdata('user_id');
            $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')];
            $totalRec = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction');
            // Pagination configuration 
            $config['target'] = '#dataList';
            $config['base_url'] = base_url('paymentReceipt/load_payment_receipt_transaction');
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $this->perPage;
            $config['link_func'] = 'searchFilter';

            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['limit'] = $this->perPage;
            $data['result'] = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction');
            $data['category'] = $this->user_model->getResult('payment_receipt_category', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
            $data['receiptid'] = $this->user_model->getRow("payment_receipt_category", ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
            $data['statelist'] = $this->user_model->getResult('state_list');
            // Load the list page view
            $data['page'] = 'transaction/index';
            $this->load->view('payment_receipt/main', $data);
        }

        function load_payment_receipt_transaction() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            // Define offset 
            $page = $this->input->post('page');
            if (!$page) {
                $offset = 0;
            } else {
                $offset = $page;
            }

            $keywords = $this->input->post('keywords');
            $sortBy = $this->input->post('sortBy');
            $pagelength = $this->input->post('pagelength');
            $column_filter = $this->input->post('column_filter');
            if ($column_filter == 'party_id') {
                $keywords = $this->serachPartybynameForgettingid($keywords);
            }
            $user_id = $this->session->userdata('user_id');
            $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')];
            // Get record count 
            $conditions['returnType'] = 'count';
            if (!empty($keywords)) {
                $conditions['search']['keywords'] = $keywords;
            }
            if (!empty($sortBy)) {
                $conditions['search']['sortBy'] = $sortBy;
            }
            $totalRec = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction', $column_filter);
            // Pagination configuration 
            $config['target'] = '#dataList';
            $config['base_url'] = base_url('paymentReceipt/load_payment_receipt_transaction');
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $pagelength;
            $config['link_func'] = 'searchFilter';
            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['start'] = $offset;
            $conditions['limit'] = $pagelength;
            unset($conditions['returnType']);
            $data['result'] = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction', $column_filter);
            // Load the data list view 
            $this->load->view('payment_receipt/transaction/load_transaction', $data, false);
        }

        public function delete_transaction($id = null) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            if (isset($id) && $id != '') {
                $user_id = $this->session->userdata('user_id');
                $where = ['id' => $id, 'user_id' => $user_id];
                $return = $this->user_model->deleteRow('payment_receipt_transaction', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Transaction Successfully Deleted.', 'class' => 'success']);
                    redirect('paymentReceipt/transaction');
                } else {
                    $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                    redirect('paymentReceipt/transaction');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                redirect('paymentReceipt/transaction');
            }
        }

        public function serachPartybynameForgettingid($serach_value) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $party_id = '';
            $this->db->select('*');
            $this->db->from('payment_receipt_party');
            $this->db->where('payment_receipt_company_id', $this->session->userdata('payment_receipt_company_id'));
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $this->db->like('party_name', $serach_value);
            $result = $this->db->get()->row();
            if ($result) {
                $party_id = $result->id;
            }
            return $party_id;
        }

        public function user_transaction($party_id) 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $user_id = $this->session->userdata('user_id');
            $this->session->set_userdata('party_id', $party_id);
            $data = array();
            // Get record count 
            $conditions['returnType'] = 'count';
            $user_id = $this->session->userdata('user_id');
            $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'), 'party_id' => $party_id];
            $totalRec = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction');
            // Pagination configuration 
            $config['target'] = '#dataList';
            $config['base_url'] = base_url('paymentReceipt/user_load_payment_receipt_transaction');
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $this->perPage;
            $config['link_func'] = 'searchFilter';

            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['limit'] = $this->perPage;
            $data['result'] = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction');
            // Load the list page view
            $data['page'] = 'transaction/user_transaction';
            $this->load->view('payment_receipt/main', $data);
        }

        public function getreceiptnumber()
        {
            if ($_POST) {
                $rid = $this->user_model->getRow('payment_receipt_category', ['id' => $this->input->post('categoryid')]);
                
                if (!empty($rid->prefix)) {
                    $rnum = $rid->receipt_number += 1;
                    $lastrow = $this->user_model->getDescOrder('payment_receipt_transaction', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
                    $str = explode("/", $lastrow->receipt_id);
                    if ($str[0] == $rid->prefix) {
                        echo $rid->prefix . "/" . $str[1] += 1;
                    } else {
                        echo $rid->prefix . "/" . $rnum += 1;
                    }
                } else {
                    echo $rid->receipt_number += 1;
                }
            }
        }
        
        public function getpartypan()
        {
            if ($_POST) {
                $partypan = $this->user_model->getRow('payment_receipt_party', ['id' => $this->input->post('partyid')]);
                echo $partypan->pan;
            }  
        }
        
        function user_load_payment_receipt_transaction() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            // Define offset 
            $page = $this->input->post('page');
            if (!$page) {
                $offset = 0;
            } else {
                $offset = $page;
            }

            $keywords = $this->input->post('keywords');
            $sortBy = $this->input->post('sortBy');
            $pagelength = $this->input->post('pagelength');
            $column_filter = $this->input->post('column_filter');
            $transaction_type = $this->input->post('transaction_type');

            if ($column_filter == 'party_id') {
                $keywords = $this->serachPartybynameForgettingid($keywords);
            }
            $user_id = $this->session->userdata('user_id');
            $party_id = $this->session->userdata('party_id');
            if (empty($transaction_type)) {
                $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'), 'party_id' => $party_id];
            } else {
                $conditions['where'] = ['user_id' => $user_id, 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'), 'party_id' => $party_id, 'transaction_type' => $transaction_type];
            }

            // Get record count 
            $conditions['returnType'] = 'count';
            if (!empty($keywords)) {
                $conditions['search']['keywords'] = $keywords;
            }
            if (!empty($sortBy)) {
                $conditions['search']['sortBy'] = $sortBy;
            }
            $totalRec = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction', $column_filter);
            // Pagination configuration 
            $config['target'] = '#dataList';
            $config['base_url'] = base_url('paymentReceipt/user_load_payment_receipt_transaction');
            $config['total_rows'] = $totalRec;
            $config['per_page'] = $pagelength;
            $config['link_func'] = 'searchFilter';
            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['start'] = $offset;
            $conditions['limit'] = $pagelength;
            unset($conditions['returnType']);
            $data['result'] = $this->user_model->getCommonRows($conditions, 'payment_receipt_transaction', $column_filter);
            // Load the data list view 
            $this->load->view('payment_receipt/transaction/load_user_transaction', $data, false);
        }
        
        protected function importtransaction_rules() 
        {
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            $rules = array(
                array(
                    'field' => 'send_sms',
                    'label' => 'Send SMS',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'file',
                    'label' => 'Excel File',
                    'rules' => 'required'
                )
            );
            return $rules;
        }
        
        public function importTransaction()  
        {
            if ($_POST) {
                $rules = $this->importtransaction_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $data['error'] = validation_errors();
                } else {
                    if (isset($_FILES["file"]["name"])) {
                        $file_type = $_FILES["file"]["type"];
                        if ($file_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file_type == 'application/vnd.ms-excel') {
                            $path = $_FILES["file"]["tmp_name"];
                            $object = PHPExcel_IOFactory::load($path);
                            $data = [];
                            foreach ($object->getWorksheetIterator() as $worksheet) {
                                $highestRow = $worksheet->getHighestRow();
                                for ($row = 2; $row <= $highestRow; $row++) {
                                    $prefix = get_title('payment_receipt_category', ['category' => $worksheet->getCellByColumnAndRow(1, $row)->getValue()], 'prefix');
                                    $rnum = get_title('payment_receipt_category', ['category' => $worksheet->getCellByColumnAndRow(1, $row)->getValue()], 'receipt_number');
                                    if (!empty($prefix)) {
                                        $lastrow = $this->user_model->getDescOrder('payment_receipt_transaction', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
                                        $str = explode("/", $lastrow->receipt_id);
                                        if ($str[0] == $prefix) {
                                            $receiptid = $prefix . "/" . $str[1] += 1;
                                        } else {
                                            $receiptid = $prefix . "/" . $rnum += 1;
                                        }
                                    } else {
                                        $receiptid = $rnum += 1;
                                    }
        
                                    $pnamesheet = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                    $pnamedatabase = get_title('payment_receipt_party', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'), 'party_name' => $worksheet->getCellByColumnAndRow(2, $row)->getValue()], 'party_name');
        
                                    if ($pnamesheet ==  $pnamedatabase) {
                                        $partyid = get_title('payment_receipt_party', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'), 'party_name' => $worksheet->getCellByColumnAndRow(2, $row)->getValue()], 'id');
                                        $partyan = get_title('payment_receipt_party', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'), 'party_name' => $worksheet->getCellByColumnAndRow(2, $row)->getValue()], 'pan');
                                    } else {
                                        $partydata = array(
                                            'user_id' => $this->session->userdata('user_id'),
                                            'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'),
                                            'usertype' => "Customer",
                                            'party_name' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                                            'number' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                                            'email' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                                            'pan' => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                                            'state_id' => get_title('state_list', ['state' => $worksheet->getCellByColumnAndRow(6, $row)->getValue()], 'id'),
                                            'city' => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                                            'pin' => $worksheet->getCellByColumnAndRow(8, $row)->getValue(),
                                            'created_at' => date('d-m-Y h:m:s A')
                                        );
                                        
                                        $partysave = $this->user_model->saveAry('payment_receipt_party', $partydata);
                                        $partyid = $partysave;
                                        $partyan = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                                    }
                                    
                                    $data = array(
                                        'date' => date('d-m-Y'),
                                        'receipt_date' => date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(0, $row)->getValue())),
                                        'paymentcategory' => get_title('payment_receipt_category', ['category' => $worksheet->getCellByColumnAndRow(1, $row)->getValue()], 'id'),
                                        'receipt_id' => $receiptid,
                                        'user_id' => $this->session->userdata('user_id'),                            
                                        'party_id' => $partyid,
                                        'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id'),
                                        'donor_pan' => $partyan,
                                        'transaction_type' => $worksheet->getCellByColumnAndRow(9, $row)->getValue(),
                                        'transaction_id' => $worksheet->getCellByColumnAndRow(10, $row)->getValue(),
                                        'transaction_date' => date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(11, $row)->getValue())),
                                        'bank_name' => $worksheet->getCellByColumnAndRow(12, $row)->getValue(),
                                        'amount' => $worksheet->getCellByColumnAndRow(13, $row)->getValue(),
                                        'remark' => $worksheet->getCellByColumnAndRow(14, $row)->getValue(),
                                        'created_at' => date('d-m-Y h:m:s A')
                                    );
                                    
                                    $res = $this->user_model->saveAry('payment_receipt_transaction', $data);
                                    
                                    if ($this->input->post('send_sms') == "Yes") {
                                        if (get_title('payment_receipt_config', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')], 'sms_status') == "Enable") {
                                            $mobile = get_title('payment_receipt_party', ['id' => $partyid], 'number');
                                            $smsconfig = $this->user_model->getRow('payment_receipt_config', ['user_id' => $this->session->userdata('user_id'), 'payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')]);
                                            $senderid = $smsconfig->sms_sender_id;
                                            $smsbody = $smsconfig->sms_body;
                                            if (!empty($senderid) && !empty($smsbody)) {
                                                payment_receipt_sms_on_demand($mobile, $senderid, $smsbody, $res);
                                            }
                                        }
                                    }
                                }
                                
                                if (isset($res) && !empty($res)) {
                                    $this->session->set_flashdata(['msg' => 'Transaction imported', 'class' => 'success']);
                                    redirect('paymentReceipt/transaction');
                                } else {
                                    $this->session->set_flashdata(['msg' => 'failed to import data', 'class' => 'danger']);
                                    return redirect('paymentReceipt/transaction');
                                }
                            }
                        } else {
                            $this->session->set_flashdata(['msg' => 'Please choose excel file for import data', 'class' => 'danger']);
                            redirect('paymentReceipt/transaction');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                        redirect('paymentReceipt/transaction');
                    }
                }    
            } else {
                $this->session->set_flashdata(['msg' => 'Something Went wrong, try again', 'class' => 'danger']);
                redirect('paymentReceipt/transaction');
            }
        }
    }