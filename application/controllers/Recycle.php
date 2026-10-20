<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Recycle extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function Estimate()
        {
            $comp_id = $this->session->userdata('comp_id');
            $user_id = $this->session->userdata('user_id');
            $financial_year = $this->session->userdata('financial_year');
            $order = ['col' => 'id', 'order' => 'DESC'];
            $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'financial_year' => $financial_year, 'status' => 0];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['result'] = $this->user_model->getAry('estimates', $order, '', '', $where);
            $data['page'] = 'recycle/Estimate';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function EstimateStore($id = null)
        {
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id];
            $data = $this->user_model->getRow('estimates', $where);
            if ($data) {
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 0];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $res = $this->user_model->getRow('estimates', $where);
                if ($res) {
                    $datas['status'] = 1;
                    $return = $this->user_model->updateAry('estimates', $datas, $where);
                    if ($return) {
                        $where = ['invoiceID' => $id, 'type' => "Sale"];
                        $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                        if ($res) {
                            $statusChange['status'] = 1;
                            $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR']);
                            foreach ($res as $da) {
                                $this->user_model->updateAry('estimate_product', $statusChange, $where);
                            }
                        }
                        echo json_encode(['status' => true, 'msg' => 'Estimate Stored successfully.']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
        
        public function EstimatePurchase()
        {
            $comp_id = $this->session->userdata('comp_id');
            $user_id = $this->session->userdata('user_id');
            $financial_year = $this->session->userdata('financial_year');
            $order = ['col' => 'id', 'order' => 'DESC'];
            $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'financial_year' => $financial_year, 'status' => 0];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['result'] = $this->user_model->getAry('estimate_purchase', $order, '', '', $where);
            $data['page'] = 'recycle/EstimatePurchase';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function EstimatePurchaseStore($id = null)
        {
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id];
            $data = $this->user_model->getRow('estimate_purchase', $where);
            if ($data) {
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 0];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $res = $this->user_model->getRow('estimate_purchase', $where);
                if ($res) {
                    $datas['status'] = 1;
                    $return = $this->user_model->updateAry('estimate_purchase', $datas, $where);
                    if ($return) {
                        $where = ['invoiceID' => $id, 'type' => "Purchase"];
                        $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                        if ($res) {
                            $statusChange['status'] = 1;
                            $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Estimate Purchase', 'transaction_type' => 'DR']);
                            foreach ($res as $da) {
                                $this->user_model->updateAry('estimate_purchase', $statusChange, $where);
                            }
                        }
                        echo json_encode(['status' => true, 'msg' => 'Estimate Stored successfully.']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                return;
            }
        }
    }