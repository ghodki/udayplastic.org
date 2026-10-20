<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Sale_recycle extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);
            $this->load->model('user_model');

            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function index()
        {
            $comp_id = $this->session->userdata('comp_id');
            $user_id = $this->session->userdata('user_id');
            $financial_year = $this->session->userdata('financial_year');
            $data['page'] = 'recycle/sale_recycle';
            $order = ['col' => 'id', 'order' => 'DESC'];
            $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'financial_year' => $financial_year, 'status' => 0];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['result'] = $this->user_model->getAry('sales', $order, '', '', $where);
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function store($id = null)
        {
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id];
            $data = $this->user_model->getRow('sales', $where);
            if ($data) {
                $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 0];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $res = $this->user_model->getRow('sales', $where);
                if ($res) {
                    $datas['status'] = 1;
                    $return = $this->user_model->updateAry('sales', $datas, $where);
                    if ($return) {
                        $where = ['invoiceID' => $id, 'type' => "Sale"];
                        $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                        if ($res) {
                            $statusChange['status'] = 1;
                            $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Sale', 'transaction_type' => 'CR']);
                            foreach ($res as $da) {
                                $this->user_model->updateAry('invoice_product', $statusChange, $where);
                            }
                        }
                        echo json_encode(['status' => true, 'msg' => 'Sale Stored successfully.']);
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
