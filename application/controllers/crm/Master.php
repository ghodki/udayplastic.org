<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Master extends CI_Controller
    {   
        public function __construct()
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
    
        public function sale_entry()
        {
            $data['saleData'] = $this->user_model->getMonthWiseForSubServices('crm_sale');
            $data['page'] = 'sale/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function sale_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_sale'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function sale_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_sale_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function sale_save()
        {
            if ($_POST) {
                $rules = $this->sale_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_sale', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Sale Created"
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
    
        public function saleReport($pagename)
        {
            $this->load->view('crm/sale/' . $pagename);
        }
    
        public function delete_sale($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_sale', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Sale Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/sale_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/sale_entry');
                }
            }
        }
    
        public function edit_sale($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_sale', ['id' => $id]);
                $this->load->view('crm/sale/edit', $data);
            }
        }
    
        public function view_sale($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_sale', ['id' => $id]);
                $this->load->view('crm/sale/view', $data);
            }
        }
    
        public function update_sale()
        {
            if ($_POST) {
                $rules = $this->sale_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_sale', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Sale Updated"
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
    
        public function unique_party_for_sale()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_sale', $where);
           
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_sale', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_sale_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_sale', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_sale_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
        
        public function purchaseReport($pagename)
        {
            $this->load->view('crm/purchase/' . $pagename);
        }
    
        protected function purchase_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_purchase'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function purchase_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_purchase_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function purchase_entry()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            
            $this->db->select('*');
            $this->db->from('crm_purchase');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('month', $exp[0]);
            $this->db->where('year', $this->session->userdata('fy'));
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
            $data['purchaseData'] = $this->db->get()->result();
            $data['page'] = 'purchase/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        public function purchase_save()
        {
            if ($_POST) {
                $rules = $this->purchase_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
    
                    $result = $this->user_model->saveAry('crm_purchase', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "purchase Created"
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
    
        public function delete_purchase($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_purchase', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Purchase Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/purchase_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/purchase_entry');
                }
            }
        }
    
        public function edit_purchase($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_purchase', ['id' => $id]);
                $this->load->view('crm/purchase/edit', $data);
            }
        }
    
        public function view_purchase($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_purchase', ['id' => $id]);
                $this->load->view('crm/purchase/view', $data);
            }
        }
    
        public function update_purchase()
        {
            if ($_POST) {
                $rules = $this->purchase_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_purchase', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "purchase Updated"
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
    
        public function unique_party_for_purchase()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
    
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_purchase', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_purchase', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_purchase_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
    
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_purchase', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_purchase_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        // PURCHASE END
        // GSTR1 Topic
        public function gstr1()
        {
            $data['gstr1Data'] = $this->user_model->getMonthWiseForSubServices('crm_gstr1');
            $data['page'] = 'gstr1/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function gstr1_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_gstr1'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function gstr1_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_gstr1_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function gstr1_save()
        {
            if ($_POST) {
                $rules = $this->gstr1_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_gstr1', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "gstr1 Created"
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
    
        public function edit_gstr1($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_gstr1', ['id' => $id]);
                $this->load->view('crm/gstr1/edit', $data);
            }
        }
    
        public function view_gstr1($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_gstr1', ['id' => $id]);
                $this->load->view('crm/gstr1/view', $data);
            }
        }
    
        public function update_gstr1()
        {
            if ($_POST) {
                $rules = $this->gstr1_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_gstr1', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "gstr1 Updated"
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
    
        public function delete_gstr1($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_gstr1', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'GSTR1 Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/gstr1');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/gstr1');
                }
            }
        }
    
        public function unique_party_for_gstr1()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);

            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_gstr1', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_gstr1', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_gstr1_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);

            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'subservice_id' => $this->session->userdata('sub_service_id'), 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_gstr1', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_gstr1_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function gstr1Show($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/gstr1/' . $gstr1Show);
            }
        }
    
        public function gstr3bShow($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/gstr3b/' . $gstr1Show);
            }
        }
    
        public function details_receivedShow($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/income_tax/details_received/' . $gstr1Show);
            }
        }
    
        public function return_filedShow($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/income_tax/return_filed/' . $gstr1Show);
            }
        }
    
        public function firm_it_return_show($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/income_tax/firm_it_return/' . $gstr1Show);
            }
        }
        public function icometax_audit_show($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/income_tax/incometax_audit/' . $gstr1Show);
            }
        }
    
        public function tds_filling_show($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/tds/tds_filling/' . $gstr1Show);
            }
        }
        public function form16_show($gstr1Show)
        {
            if (!empty($gstr1Show)) {
                $this->load->view('crm/tds/form16/' . $gstr1Show);
            }
        }
    
        // GSTR1 END
        protected function gstr3b_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_gstr3b'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function gstr3b_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_gstr3b_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function gstr3b()
        {
            $data['gstr3bData'] = $this->user_model->getMonthWiseForSubServices('crm_gstr3b');
            $data['page'] = 'gstr3b/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        public function gstr3b_save()
        {
            if ($_POST) {
                $rules = $this->gstr3b_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $result = $this->user_model->saveAry('crm_gstr3b', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "gstr3b Created"
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
    
        public function edit_gstr3b($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_gstr3b', ['id' => $id]);
                $this->load->view('crm/gstr3b/edit', $data);
            }
        }
    
        public function delete_gstr3b($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_gstr3b', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'GSTR3B Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/gstr3b');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/gstr3b');
                }
            }
        }
    
        public function view_gstr3b($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_gstr3b', ['id' => $id]);
                $this->load->view('crm/gstr3b/view', $data);
            }
        }
    
        public function update_gstr3b()
        {
            if ($_POST) {
                $rules = $this->gstr3b_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_gstr3b', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "gstr3b Updated"
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
    
        public function unique_party_for_gstr3b()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);

            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_gstr3b', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_gstr3b', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_gstr3b_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_gstr3b', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_gstr3b_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function correction()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
    
            $this->db->select('*');
            $this->db->from('crm_correction');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
    
            $data['correctionData'] = $this->db->get()->result();
    
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');

            $this->db->select('*');
            $this->db->from('crm_correction');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('status', 1);
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
            $data['approvedData'] = $this->db->get()->result();
    
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
    
            $this->db->select('*');
            $this->db->from('crm_correction');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
            $this->db->where('status', 0);
            $data['pendingData'] = $this->db->get()->result();
    
            $data['page'] = 'correction/index';
            $this->load->view('crm/main', $data);
        }
    
        protected function correction_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function correction_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function correction_save()
        {
            if ($_POST) {
                $rules = $this->correction_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    
                    $result = $this->user_model->saveAry('crm_correction', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "correction Created"
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
    
        public function correctionReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/correction/' . $pagename, $data);
        }
    
        public function delete_correction($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_correction', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'correction Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/correction');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/correction');
                }
            }
        }
    
        public function edit_correction($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_correction', ['id' => $id]);
                $this->load->view('crm/correction/edit', $data);
            }
        }
    
        public function view_correction($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_correction', ['id' => $id]);
                $this->load->view('crm/correction/view', $data);
            }
        }
    
        public function update_correction()
        {
            if ($_POST) {
                $rules = $this->correction_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['category_id'] = get_title('crm_party', ['id' => $this->input->post('party')], 'category_id');
    
                    $result = $this->user_model->updateAry('crm_correction', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "correction Updated"
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
    
        public function bank_statementReport($pagename)
        {
            $this->load->view('crm/bank_statement/' . $pagename);
        }
    
        public function bank_statement_entry()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            
            $this->db->select('*');
            $this->db->from('crm_bankstatement');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('month', $exp[0]);
            $this->db->where('year', $this->session->userdata('fy'));
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
    
            $data['bank_statementData'] = $this->db->get()->result();
            $data['page'] = 'bank_statement/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function bank_statement_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_bank_statement'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function bank_statement_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_bank_statement_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function bank_statement_save()
        {
            if ($_POST) {
                $rules = $this->bank_statement_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $months = $this->session->userdata('months'); $exp = explode("-", $months);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_bankstatement', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "bank_statement Created"
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
    
        public function delete_bank_statement($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_bankstatement', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'bank_statement Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/bank_statement_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/bank_statement_entry');
                }
            }
        }
    
        public function edit_bank_statement($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_bankstatement', ['id' => $id]);
                $this->load->view('crm/bank_statement/edit', $data);
            }
        }
    
        public function view_bank_statement($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_bankstatement', ['id' => $id]);
                $this->load->view('crm/bank_statement/view', $data);
            }
        }
    
        public function update_bank_statement()
        {
            if ($_POST) {
                $rules = $this->bank_statement_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_bankstatement', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "bank_statement Updated"
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
    
        public function unique_party_for_bank_statement()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month', $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_bankstatement', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_bank_statement', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_bank_statement_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_bankstatement', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_bank_statement_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function details_received_entry()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "details_received"], 'id');
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $data['details_receivedData'] = $this->user_model->getAry('crm_details_received', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'income_tax/details_received/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function details_received_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_details_received'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function details_received_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_details_received_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function details_received_save()
        {
            if ($_POST) {
                $rules = $this->details_received_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $months = $this->session->userdata('months'); $exp = explode("-", $months);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_details_received', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "details_received Created"
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
    
        public function details_receivedReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/details_received/' . $pagename, $data);
        }
    
        public function delete_details_received($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_details_received', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'details_received Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/details_received_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/details_received_entry');
                }
            }
        }
    
        public function edit_details_received($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_details_received', ['id' => $id]);
                $this->load->view('crm/income_tax/details_received/edit', $data);
            }
        }
    
        public function view_details_received($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_details_received', ['id' => $id]);
                $this->load->view('crm/income_tax/details_received/view', $data);
            }
        }
    
        public function update_details_received()
        {
            if ($_POST) {
                $rules = $this->details_received_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_details_received', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "details_received Updated"
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
    
        public function unique_party_for_details_received()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_details_received', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_details_received', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_details_received_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_details_received', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_details_received_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function return_filed_entry()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "return_filed"], 'id');
            $data['return_filedData'] = $this->user_model->getMonthWiseForSubServices('crm_return_filed');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'income_tax/return_filed/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function return_filed_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_return_filed'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function return_filed_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_return_filed_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function return_filed_save()
        {
            if ($_POST) {
                $rules = $this->return_filed_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['status'] = $this->input->post('status');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $months = $this->session->userdata('months'); $exp = explode("-", $months);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_return_filed', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "return_filed Created"
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
    
        public function return_filedReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/return_filed/' . $pagename, $data);
        }
    
        public function delete_return_filed($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_return_filed', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'return_filed Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/return_filed_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/return_filed_entry');
                }
            }
        }
    
        public function edit_return_filed($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_return_filed', ['id' => $id]);
                $this->load->view('crm/income_tax/return_filed/edit', $data);
            }
        }
    
        public function view_return_filed($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_return_filed', ['id' => $id]);
                $this->load->view('crm/income_tax/return_filed/view', $data);
            }
        }
    
        public function update_return_filed()
        {
            if ($_POST) {
                $rules = $this->return_filed_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_return_filed', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "return_filed Updated"
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
    
        public function unique_party_for_return_filed()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_return_filed', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_return_filed', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_return_filed_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_return_filed', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_return_filed_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
        
        public function gst_return_entry()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);

            $this->db->select('*');
            $this->db->from('crm_gst_return');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('month', $exp[0]);
            $this->db->where('year', $this->session->userdata('fy'));
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
    
            $data['gst_returnData'] = $this->db->get()->result();
            $data['page'] = 'gst_return/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function gst_return_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_gst_return'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function gst_return_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_gst_return_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
    
            );
            return $rules;
        }
    
        public function gst_return_save()
        {
            if ($_POST) {
                $rules = $this->gst_return_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_gst_return', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "gst_return Created"
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
    
        public function gst_returnReport($pagename)
        {
            $this->load->view('crm/gst_return/' . $pagename);
        }
    
        public function delete_gst_return($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_gst_return', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'gst_return Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/gst_return_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/gst_return_entry');
                }
            }
        }
    
        public function edit_gst_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_gst_return', ['id' => $id]);
                $this->load->view('crm/gst_return/edit', $data);
            }
        }
    
        public function view_gst_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_gst_return', ['id' => $id]);
                $this->load->view('crm/gst_return/view', $data);
            }
        }
    
        public function update_gst_return()
        {
            if ($_POST) {
                $rules = $this->gst_return_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_gst_return', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "gst_return Updated"
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
    
        public function unique_party_for_gst_return()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);

            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_gst_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_gst_return', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_gst_return_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_gst_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_gst_return_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function incometax_audit_entry()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "income_tax_audit"], 'id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $data['Incometax_auditData'] = $this->user_model->getAry('crm_incometax_audit', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'income_tax/incometax_audit/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function incometax_audit_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_incometax_audit'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
    
            );
            return $rules;
        }
    
        protected function incometax_audit_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_incometax_audit_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function incometax_audit_save()
        {
            if ($_POST) {
                $rules = $this->Incometax_audit_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $months = $this->session->userdata('months'); $exp = explode("-", $months);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_incometax_audit', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Incometax_audit Created"
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
    
        public function incometax_auditReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/incometax_audit/' . $pagename, $data);
        }
    
        public function delete_incometax_audit($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_incometax_audit', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'incometax_audit Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/incometax_audit_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/incometax_audit_entry');
                }
            }
        }
    
        public function edit_incometax_audit($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_incometax_audit', ['id' => $id]);
                $this->load->view('crm/income_tax/incometax_audit/edit', $data);
            }
        }
    
        public function view_incometax_audit($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_incometax_audit', ['id' => $id]);
                $this->load->view('crm/income_tax/incometax_audit/view', $data);
            }
        }
    
        public function update_incometax_audit()
        {
            if ($_POST) {
                $rules = $this->Incometax_audit_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_incometax_audit', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Incometax_audit Updated"
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
    
        public function unique_party_for_incometax_audit()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_incometax_audit', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_incometax_audit', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_incometax_audit_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_incometax_audit', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_incometax_audit_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function firm_it_return_entry()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "firm_it_return"], 'id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $data['firm_it_returnData'] = $this->user_model->getAry('crm_firm_it_return', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'income_tax/firm_it_return/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function firm_it_return_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_firm_it_return'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function firm_it_return_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_firm_it_return_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function firm_it_return_save()
        {
            if ($_POST) {
                $rules = $this->firm_it_return_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $months = $this->session->userdata('months'); $exp = explode("-", $months);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_firm_it_return', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "firm_it_return Created"
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
    
        public function firm_it_returnReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/firm_it_return/' . $pagename, $data);
        }
    
        public function delete_firm_it_return($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_firm_it_return', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'firm_it_return Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/firm_it_return_entry');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/firm_it_return_entry');
                }
            }
        }
    
        public function edit_firm_it_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_firm_it_return', ['id' => $id]);
                $this->load->view('crm/income_tax/firm_it_return/edit', $data);
            }
        }
    
        public function view_firm_it_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_firm_it_return', ['id' => $id]);
                $this->load->view('crm/income_tax/firm_it_return/view', $data);
            }
        }
    
        public function update_firm_it_return()
        {
            if ($_POST) {
                $rules = $this->firm_it_return_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_firm_it_return', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "firm_it_return Updated"
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
    
        public function unique_party_for_firm_it_return()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_firm_it_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_firm_it_return', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_firm_it_return_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months'); $exp = explode("-", $months);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_firm_it_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_firm_it_return_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function tds_filling()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "tds_filling"], 'id');
            $data['tds_fillingData'] = $this->user_model->getMonthWiseForSubServices('crm_tds_filling');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'tds/tds_filling/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function tds_filling_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_tds_filling'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function tds_filling_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_tds_filling_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function tds_filling_save()
        {
            if ($_POST) {
                $rules = $this->tds_filling_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_tds_filling', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "TDS FILLING Created"
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
    
        public function tds_fillingReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/tds_filling/' . $pagename, $data);
        }
    
        public function delete_tds_filling($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_tds_filling', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'TDS FILLING Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/tds_filling');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/tds_filling');
                }
            }
        }
    
        public function edit_tds_filling($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_tds_filling', ['id' => $id]);
                $this->load->view('crm/tds/tds_filling/edit', $data);
            }
        }
    
        public function view_tds_filling($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_tds_filling', ['id' => $id]);
                $this->load->view('crm/tds/tds_filling/view', $data);
            }
        }
    
        public function update_tds_filling()
        {
            if ($_POST) {
                $rules = $this->tds_filling_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_tds_filling', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "tds_filling Updated"
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
    
        public function unique_party_for_tds_filling()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_tds_filling', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_tds_filling', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_tds_filling_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_tds_filling', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_tds_filling_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function form16()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "form16"], 'id');
            $data['form16Data'] = $this->user_model->getMonthWiseForSubServices('crm_form16');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'tds/form16/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function form16_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_form16'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function form16_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_form16_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function form16_save()
        {
            if ($_POST) {
                $rules = $this->form16_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_form16', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "FORM 16 Created"
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
    
        public function form16Report($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/form16/' . $pagename, $data);
        }
    
        public function delete_form16($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_form16', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'FORM 16 Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/form16');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/form16');
                }
            }
        }
    
        public function edit_form16($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_form16', ['id' => $id]);
                $this->load->view('crm/tds/form16/edit', $data);
            }
        }
    
        public function view_form16($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_form16', ['id' => $id]);
                $this->load->view('crm/tds/form16/view', $data);
            }
        }
    
        public function update_form16()
        {
            if ($_POST) {
                $rules = $this->form16_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_form16', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "form16 Updated"
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
    
        public function unique_party_for_form16()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
    
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);

            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_form16', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_form16', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_form16_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_form16', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_form16_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function tds_details()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "tds_details"], 'id');
            $data['tds_detailsData'] = $this->user_model->getMonthWiseForSubServices('crm_tds_details');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'tds/tds_details/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function tds_details_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_tds_details'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        protected function tds_details_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_tds_details_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function tds_details_save()
        {
            if ($_POST) {
                $rules = $this->tds_details_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_tds_details', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "TDS DETAILS Created"
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
    
        public function tds_detailsReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/tds_details/' . $pagename, $data);
        }
    
        public function delete_tds_details($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_tds_details', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => ' TDS DETAILS Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/tds_details');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/tds_details');
                }
            }
        }
    
        public function edit_tds_details($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_tds_details', ['id' => $id]);
                $this->load->view('crm/tds/tds_details/edit', $data);
            }
        }
    
        public function view_tds_details($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_tds_details', ['id' => $id]);
                $this->load->view('crm/tds/tds_details/view', $data);
            }
        }
    
        public function update_tds_details()
        {
            if ($_POST) {
                $rules = $this->tds_details_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_tds_details', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "tds_details Updated"
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
    
        public function unique_party_for_tds_details()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_tds_details', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_tds_details', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_tds_details_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);

            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_tds_details', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_tds_details_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function status()
        {
            return redirect('crm/status');
        }
    
        public function status_filter($id = null, $firstDate = null, $lastDate = null, $typeId = null)
        {
            $data['id'] = $id;
            $data['typeId'] = $typeId;
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $this->db->select("*");
            $this->db->from('crm_dsc_process');
            if ($firstDate) {
                $this->db->where('date >=', $firstDate);
            }
            if ($lastDate) {
                $this->db->where('date <=', $lastDate);
            }
            if ($typeId) {
                $this->db->where('type', $typeId);
            }
            if ($id) {
                $this->db->where('status', $id);
            }
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $data['result'] = $this->db->get()->result();
            $this->load->view('crm/digital_signature/dsc_process/status_filter', $data);
        }
    
        public function loadAjax()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "dsc_process"], 'id');
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            $data['result'] = $this->user_model->getAry('crm_dsc_process', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);

            $this->load->view('crm/digital_signature/dsc_process/status_filter', $data);
        }
    
        public function dsc_process()
        {
            $data['page'] = 'digital_signature/dsc_process/index';
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "dsc_process"], 'id');
            $this->session->set_userdata('sub_service_id', $service_id);
    
            $this->db->select('*');
            $this->db->from('crm_dsc_process');
            if (!empty($this->input->get('firstDate'))) {
                $firstDate = $this->input->get('firstDate');
                $this->db->where('date>=', $firstDate);
            }
            if (!empty($this->input->get('lastDate'))) {
                $lastDate = $this->input->get('lastDate');
                $this->db->where('date<=', $lastDate);
            }
            if (!empty($this->input->get('status'))) {
                $status = $this->input->get('status');
                $this->db->where('status', $status);
            }
            if (!empty($this->input->get('type'))) {
                $type = $this->input->get('type');
                $this->db->where('type', $type);
            }
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $data['result'] = $this->db->get()->result();
    
            $this->load->view('crm/main', $data);
        }
    
        protected function dsc_process_rules()
        {
            $rules = array(
                array(
                    'field' => 'applicant_name',
                    'label' => 'Applicant Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'mobile_number',
                    'label' => 'Mobile Number',
                    'rules' => 'trim|xss_clean|regex_match[/^[0-9]{10}$/]|min_length[10]'
                ),
                array(
                    'field' => 'expiry_date',
                    'label' => 'Expiry Date',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|xss_clean|valid_email'
                ),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|xss_clean'
                ),
                array(
                    'field' => 'type',
                    'label' => 'Type',
                    'rules' => 'trim|xss_clean|required'
                ),
                array(
                    'field' => 'remark',
                    'label' => 'Remark',
                    'rules' => 'trim|xss_clean'
                ),
                array(
                    'field' => 'ref_by',
                    'label' => 'Ref By',
                    'rules' => 'trim|xss_clean'
                ),
            );
    
            return $rules;
        }
    
        public function dsc_process_save()
        {
            if ($_POST) {
                $rules = $this->dsc_process_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['mobile_number'] = $this->input->post('mobile_number');
                    $data['applicant_name'] = $this->input->post('applicant_name');
                    $data['type'] = $this->input->post('type');
                    $data['ref_by'] = $this->input->post('ref_by');
                    $data['org_name'] = $this->input->post('org_name');
                    $data['email'] = $this->input->post('email');
                    $data['remark'] = $this->input->post('remark');
                    $data['selectedBy'] = $this->input->post('selectedBy');
                    $data['invoiceNumber'] = $this->input->post('invoiceNumber');
                    $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $data['expiry_date'] = date('Y-m-d', strtotime($this->input->post('expiry_date')));
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $result = $this->user_model->saveAry('crm_dsc_process', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "DSC PROCESS Created"
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
    
        public function update_dsc_process()
        {
            if ($_POST) {
                $rules = $this->dsc_process_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['mobile_number'] = $this->input->post('mobile_number');
                    $data['applicant_name'] = $this->input->post('applicant_name');
                    $data['type'] = $this->input->post('type');
                    $data['ref_by'] = $this->input->post('ref_by');
                    $data['org_name'] = $this->input->post('org_name');
                    $data['email'] = $this->input->post('email');
                    $data['remark'] = $this->input->post('remark');
                    $data['invoiceNumber'] = $this->input->post('invoiceNumber');
                    $data['selectedBy'] = $this->input->post('selectedBy');
                    $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $data['expiry_date'] = date('Y-m-d', strtotime($this->input->post('expiry_date')));
                    $data['status'] = $this->input->post('status');
                    $result = $this->user_model->updateAry('crm_dsc_process', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "DSC Process Updated"
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
    
        public function delete_dsc_process($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_dsc_process', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => ' DSC Process Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/dsc_process');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/dsc_process');
                }
            }
        }
    
        public function edit_dsc_process($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_dsc_process', ['id' => $id]);
                $this->load->view('crm/digital_signature/dsc_process/edit', $data);
            }
        }
    
        public function view_dsc_process($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_dsc_process', ['id' => $id]);
                $this->load->view('crm/digital_signature/dsc_process/view', $data);
            }
        }
    
        public function insurance_policy()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "insurance_policy"], 'id');
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            $data['insurance_policyData'] = $this->user_model->getAry('crm_insurance_policy', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'insurance_management/insurance_policy/index';
            $this->load->view('crm/main', $data);
        }
    
        protected function insurance_policy_rules()
        {
            $rules = array(
                array(
                    'field' => 'applicant_name',
                    'label' => 'Applicant Name',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'mobile_number',
                    'label' => 'Mobile Number',
                    'rules' => 'trim|xss_clean|regex_match[/^[0-9]{10}$/]|min_length[10]'
                ),
                array(
                    'field' => 'expiry_date',
                    'label' => 'Expiry Date',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|xss_clean|valid_email'
                ),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|xss_clean'
                ),
                array(
                    'field' => 'type',
                    'label' => 'Type',
                    'rules' => 'trim|xss_clean|required'
                ),
                array(
                    'field' => 'remark',
                    'label' => 'Remark',
                    'rules' => 'trim|xss_clean'
                ),
                array(
                    'field' => 'ref_by',
                    'label' => 'Ref By',
                    'rules' => 'trim|xss_clean'
                ),
            );
            
            return $rules;
        }
    
        public function insurance_policy_save()
        {
            if ($_POST) {
                $rules = $this->insurance_policy_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['mobile_number'] = $this->input->post('mobile_number');
                    $data['applicant_name'] = $this->input->post('applicant_name');
                    $data['type'] = $this->input->post('type');
                    $data['ref_by'] = $this->input->post('ref_by');
                    $data['org_name'] = $this->input->post('org_name');
                    $data['email'] = $this->input->post('email');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $data['expiry_date'] = date('Y-m-d', strtotime($this->input->post('expiry_date')));
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $result = $this->user_model->saveAry('crm_insurance_policy', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Insurance Policy Created"
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
    
        public function update_insurance_policy()
        {
            if ($_POST) {
                $rules = $this->insurance_policy_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['mobile_number'] = $this->input->post('mobile_number');
                    $data['applicant_name'] = $this->input->post('applicant_name');
                    $data['type'] = $this->input->post('type');
                    $data['ref_by'] = $this->input->post('ref_by');
                    $data['org_name'] = $this->input->post('org_name');
                    $data['email'] = $this->input->post('email');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                    $data['expiry_date'] = date('Y-m-d', strtotime($this->input->post('expiry_date')));
                    $data['status'] = $this->input->post('status');
                    $result = $this->user_model->updateAry('crm_insurance_policy', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Insurance Policy Updated"
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
    
        public function delete_insurance_policy($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_insurance_policy', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => ' Insurance Policy Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/insurance_policy');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/insurance_policy');
                }
            }
        }
    
        public function edit_insurance_policy($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_insurance_policy', ['id' => $id]);
                $this->load->view('crm/insurance_management/insurance_policy/edit', $data);
            }
        }
    
        public function view_insurance_policy($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_insurance_policy', ['id' => $id]);
                $this->load->view('crm/insurance_management/insurance_policy/view', $data);
            }
        }
    
        public function refBySearch()
        {
            $refBy = $this->input->post('refName');
            $crm_id = $this->session->userdata('crm_id');
            $user_id = $this->session->userdata('crm_id');
    
            $this->db->select('*');
            $this->db->from('crm_dsc_process');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->like('ref_by', $refBy);
            $this->db->group_by('ref_by');
            $result = $this->db->get()->result();
            if ($result) {
                $response = array(
                    'status' => true,
                    'data' => $result
                );
            } else {
                $response = array(
                    'status' => false,
                    'message' => "Data Not Found",
                    'data' => []
                );
            }
    
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    
        public function pt_correction()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "pt_correction"], 'id');
            $data['pt_correctionData'] = $this->user_model->getMonthWiseForSubServices('crm_pt_correction');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'professionaltax/pt_correction/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function pt_correction_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_correction'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            
            return $rules;
        }
    
        protected function pt_correction_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_correction_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            
            return $rules;
        }
    
        public function pt_correction_save()
        {
            if ($_POST) {
                $rules = $this->pt_correction_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_pt_correction', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT CORRECTION Created"
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
    
        public function pt_correctionReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/pt_correction/' . $pagename, $data);
        }
    
        public function delete_pt_correction($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_pt_correction', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'PT CORRECTION Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/pt_correction');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/pt_correction');
                }
            }
        }
    
        public function edit_pt_correction($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_correction', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_correction/edit', $data);
            }
        }
    
        public function view_pt_correction($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_correction', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_correction/view', $data);
            }
        }
    
        public function update_pt_correction()
        {
            if ($_POST) {
                $rules = $this->pt_correction_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_pt_correction', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT CORRECTION Updated"
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
    
        public function unique_party_for_pt_correction()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_correction', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_correction', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_pt_correction_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_correction', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_correction_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function pt_details_received()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "pt_details_received"], 'id');
            $data['pt_details_receivedData'] = $this->user_model->getMonthWiseForSubServices('crm_pt_details_received');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'professionaltax/pt_details_received/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function pt_details_received_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_details_received'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }
    
        protected function pt_details_received_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_details_received_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }
    
        public function pt_details_received_save()
        {
            if ($_POST) {
                $rules = $this->pt_details_received_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_pt_details_received', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT DETAILS RECEIVED ADDED"
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
    
        public function pt_details_receivedReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/pt_details_received/' . $pagename, $data);
        }
    
        public function delete_pt_details_received($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_pt_details_received', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'PT DETAILS RECEIVED Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/pt_details_received');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/pt_details_received');
                }
            }
        }
    
        public function edit_pt_details_received($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_details_received', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_details_received/edit', $data);
            }
        }
    
        public function view_pt_details_received($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_details_received', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_details_received/view', $data);
            }
        }
    
        public function update_pt_details_received()
        {
            if ($_POST) {
                $rules = $this->pt_details_received_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_pt_details_received', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT DETAILS RECEIVED
                            Updated"
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
    
        public function unique_party_for_pt_details_received()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_details_received', $where);
           
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_details_received', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_pt_details_received_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_details_received', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_details_received_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function pt_annual_return()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "pt_annual_return"], 'id');
            $data['pt_annual_returnData'] = $this->user_model->getMonthWiseForSubServices('crm_pt_annual_return');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'professionaltax/pt_annual_return/index';
            $this->load->view('crm/gst_main', $data);
        }
    
        protected function pt_annual_return_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_annual_return'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }
    
        protected function pt_annual_return_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_annual_return_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }
    
        public function pt_annual_return_save()
        {
            if ($_POST) {
                $rules = $this->pt_annual_return_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_pt_annual_return', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT Annual Return Added"
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
    
        public function pt_annual_returnReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/pt_annual_return/' . $pagename, $data);
        }
    
        public function delete_pt_annual_return($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_pt_annual_return', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'PT Annual Return Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/pt_annual_return');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/pt_annual_return');
                }
            }
        }
    
        public function edit_pt_annual_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_annual_return', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_annual_return/edit', $data);
            }
        }
    
        public function view_pt_annual_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_annual_return', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_annual_return/view', $data);
            }
        }
    
        public function update_pt_annual_return()
        {
            if ($_POST) {
                $rules = $this->pt_annual_return_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_pt_annual_return', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT ANNUAL RETURN Updated"
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
    
        public function unique_party_for_pt_annual_return()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_annual_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_annual_return', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_pt_annual_return_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_annual_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_annual_return_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function pt_return()
        {
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "pt_return"], 'id');
            $data['pt_returnData'] = $this->user_model->getMonthWiseForSubServices('crm_pt_return');
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'professionaltax/pt_return/index';
            $this->load->view('crm/gst_main', $data);
        }
        
        public function pt_returnShow($ptreturnshow)
        {
            if (!empty($ptreturnshow)) {
                $this->load->view('crm/professionaltax/pt_return/' . $ptreturnshow);
            }
        }

        public function pt_details_receivedShow($ptdetailsreceivedshow)
        {
            if (!empty($ptdetailsreceivedshow)) {
                $this->load->view('crm/professionaltax/pt_details_received/' . $ptdetailsreceivedshow);
            }
        }

        public function pt_annual_returnShow($ptannualreturnshow)
        {
            if (!empty($ptannualreturnshow)) {
                $this->load->view('crm/professionaltax/pt_annual_return/' . $ptannualreturnshow);
            }
        }
    
        protected function pt_return_rules()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_return'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }
    
        protected function pt_return_rules_update()
        {
            $rules = array(
                array(
                    'field' => 'party',
                    'label' => 'Party',
                    'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_return_update'
                ),
                array(
                    'field' => 'date',
                    'label' => 'Date',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            return $rules;
        }
    
        public function pt_return_save()
        {
            if ($_POST) {
                $rules = $this->pt_return_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['status'] = $this->input->post('status');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_pt_return', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT Annual Return Added"
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
    
        public function pt_returnReport($pagename, $cateId)
        {
            $data['category_id'] = $cateId;
            $this->load->view('crm/pt_return/' . $pagename, $data);
        }
    
        public function delete_pt_return($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_pt_return', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'PT Annual Return Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/pt_return');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/pt_return');
                }
            }
        }
    
        public function edit_pt_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_return', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_return/edit', $data);
            }
        }
    
        public function view_pt_return($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_pt_return', ['id' => $id]);
                $this->load->view('crm/professionaltax/pt_return/view', $data);
            }
        }
    
        public function filterDsc()
        {
            $firstDate = $this->input->post('firstDate');
            $lastDate = $this->input->post('lastDate');
            $type = $this->input->post('type');
            $status = $this->input->post('status');
            $crm_id = $this->session->userdata('crm_id');
            $user_id = $this->session->userdata('user_id');
    
            $this->db->select('*');
            $this->db->from('crm_dsc_process');
            $this->db->where('date>=', $firstDate);
            $this->db->where('date<=', $lastDate);
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('status', $status);
            $this->db->where('type', $type);
            $data['result'] = $this->db->get()->result();

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    
        public function update_pt_return()
        {
            if ($_POST) {
                $rules = $this->pt_return_rules_update();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['party_id'] = $this->input->post('party');
                    $data['remark'] = $this->input->post('remark');
                    $data['status'] = $this->input->post('status');
                    $data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
                    $data['timestamp'] = time();
                    $result = $this->user_model->updateAry('crm_pt_return', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "PT ANNUAL RETURN Updated"
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
    
        public function unique_party_for_pt_return()
        {
            $party = $this->input->post('party');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_return', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function unique_party_for_pt_return_update()
        {
            $party = $this->input->post('party');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $month = $this->session->userdata('months');
            $exp = explode('-', $month);
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')];
            $return_value = $this->user_model->getRow('crm_pt_return', $where);
            
            if ($return_value) {
                $this->form_validation->set_message('unique_party_for_pt_return_update', 'Party should be Unique');
                return false;
            }
            return true;
        }
    
        public function export_dsc_prcessed()
        {
            $ids = $this->input->post('ids');
    
            if (!$ids) {
                $response = array(
                    'status' => 'error',
                    'message' => "Please provide all The Details"
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            }
    
            $fileName = 'dsc_processed-' . time() . '.xls';
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(21);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
    
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Applicant name');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ORG Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Email');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Mobile Number');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Type');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Ref By');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Registration Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Expiry Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Status');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Remark');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Colleted By');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Invoice Number');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Type');
    
            $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray(
                array(
                    'font' => array(
                        'color' => array('argb' => 'FF0000'),
                    ),
                    'alignment' => array(
                        'text' => 'center'
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );
    
            $rowCount = 2;
            if ($ids) {
                foreach ($ids as $key => $value_of_id) {
                    $val = $this->user_model->getRow('crm_dsc_process', ['id' => $value_of_id]);
                    if ($val) {
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':M' . $rowCount)->applyFromArray(
                            array(
                                'font' => array(
                                    'color' => array('argb' => '000000'),
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                        'color' => array('rgb' => '000000')
                                    )
                                )
                            )
                        );
                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val->applicant_name);
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val->org_name);
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val->email);
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val->mobile_number);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, get_title('crm_type', ['id' => $val->type], 'name'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val->ref_by);
                        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $val->date);
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val->expiry_date);
                        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, get_title('crm_status', ['id' => $val->status], 'name'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $val->remark);
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $val->selectedBy);
                        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $val->invoiceNumber);
                        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, get_title('crm_type', ['id' => $val->type], 'name'));
                        $rowCount++;
                    }
                }
                ob_start();
                $write = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $write->save('php://output');
                $xlsData = ob_get_contents();
                ob_end_clean();
                $response =  array(
                    'status' => 'success',
                    'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
                    'message' => "File Exported"
                );
    
                die(json_encode($response));
            } else {
                $response =  array(
                    'status' => 'false',
                    'message' => "File Could not Be Exported"
    
                );
    
                die(json_encode($response));
            }
        }
    
        public function office_expence()
        {
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $service_id = get_title('crm_servicesubgroup', ['sub_name' => "office_expence"], 'id');
            $where = ['user_id' => $user_id, 'crm_id' => $crm_id];
            $data['result'] = $this->user_model->getAry('crm_office_expence', '', '', '', $where);
            $this->session->set_userdata('sub_service_id', $service_id);
            $data['page'] = 'expence/office_expence/index';
            $this->load->view('crm/main', $data);
        }
    
        protected function office_rules()
        {
            $rules = array(
                array(
                    'field' => 'applicant_name',
                    'label' => 'Applicant',
                    'rules' => 'trim|required|xss_clean'
                ),
                array(
                    'field' => 'amount',
                    'label' => 'Amount',
                    'rules' => 'trim|required|xss_clean'
                )
            );
            
            return $rules;
        }
    
        public function office_expence_save()
        {
            if ($_POST) {
                $rules = $this->office_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $data['applicant_name'] = $this->input->post('applicant_name');
                    $data['remark'] = $this->input->post('remark');
                    $data['amount'] = $this->input->post('amount');
                    $data['paymentmode'] = $this->input->post('paymentmode');
                    $data['timestamp'] = time();
                    $data['subservice_id'] = $this->session->userdata('sub_service_id');
                    $data['user_id'] = $this->session->userdata('user_id');
                    $data['crm_id'] = $this->session->userdata('crm_id');
                    $month = $this->session->userdata('months');
                    $exp = explode('-', $month);
                    $data['month'] = $exp[0];
                    $data['year'] = $this->session->userdata('fy');
                    $result = $this->user_model->saveAry('crm_office_expence', $data);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Office Expence Created"
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
    
        public function edit_office_expence($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_office_expence', ['id' => $id]);
                $this->load->view('crm/expence/office_expence/edit', $data);
            }
        }
    
        public function view_office_expence($id)
        {
            if (!empty($id)) {
                $data['result'] = $this->user_model->getRow('crm_office_expence', ['id' => $id]);
                $this->load->view('crm/expence/crm_office_expence/view', $data);
            }
        }
    
        public function officeExpenceUpdate()
        {
            if ($_POST) {
                $rules = $this->office_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $response = array(
                        'status' => 'error',
                        'message' => validation_errors()
                    );
                } else {
                    $id = $this->input->post('id');
                    $data['remark'] = $this->input->post('remark');
                    $data['amount'] = $this->input->post('amount');
                    $data['paymentmode'] = $this->input->post('paymentmode');
                    $data['applicant_name'] = $this->input->post('applicant_name');
                    $result = $this->user_model->updateAry('crm_office_expence', $data, ['id' => $id]);
                    if ($result) {
                        $response = array(
                            'status' => 'success',
                            'message' => "Office Expence Updated"
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
    
        public function delete_office_expence($id)
        {
            if (!empty($id)) {
                $result = $this->user_model->deleteRow('crm_office_expence', ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Office Expence Deleted Successfully.', 'class' => 'success']);
                    return redirect('crm/master/office_expence');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
                    return redirect('crm/master/office_expence');
                }
            }
        }
    
        public function getPartyFromSubService()
        {
            $html = '';
            $html .= '<select class="form-control select2" required name="party" id="party">';
            $html .= '<option value="">Select Party</option>';
            $getResu = $this->user_model->getPartyForSubservices();
            if ($getResu) {
                foreach ($getResu as $category) {
                    $sub_service_assigns = json_decode($category->sub_services_id);
                    if ($sub_service_assigns) {
                        foreach ($sub_service_assigns as $services_data_id) {
                            if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                $html .= '<option value=' . $category->id . '>' . $category->party_name . '</option>';
                            }
                        }
                    }
                }
            }
            $html . "</select>";
            print_r($html);
        }
    
        public function getPartyFromSubServiceSelected()
        {
            $partyId = $this->input->post('party_id');
            $html = '';
            $html .= '<select class="form-control select2" required name="party" id="party">';
            $html .= '<option value="">Select Party</option>';
            $getResu = $this->user_model->getPartyForSubservices();
            if ($getResu) {
                foreach ($getResu as $category) {
                    $sub_service_assigns = json_decode($category->sub_services_id);
                    if ($sub_service_assigns) {
                        foreach ($sub_service_assigns as $services_data_id) {
                            if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                if ($category->id == $partyId) {
                                    $selected = "selected";
                                } else {
                                    $selected = "";
                                }
                                $html .= '<option value=' . $category->id . ' ' . $selected . '>' . $category->party_name . '</option>';
                            }
                        }
                    }
                }
            }
            $html . "</select>";
            print_r($html);
        }
    
        public function getPartyFromSubServiceMonthWise()
        {
            $html = '';
            $html .= '<select class="form-control select2" required name="party" id="party">';
            $html .= '<option value="">Select Party</option>';
            $getResu = $this->user_model->getPartyForSubservices();
            if ($getResu) {
                foreach ($getResu as $category) {
                    $sub_service_assigns = json_decode($category->sub_services_id);
                    if ($sub_service_assigns) {
                        foreach ($sub_service_assigns as $services_data_id) {
                            if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                $html .= '<option value=' . $category->id . '>' . $category->party_name . '</option>';
                            }
                        }
                    }
                }
            }
            $html . "</select>";
            print_r($html);
        }
    }