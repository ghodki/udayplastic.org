<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Purchase_recycle extends CI_Controller
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
            $data['page'] = 'recycle/purchase_recycle';
            $order = ['col' => 'id', 'order' => 'DESC'];
            $row['comp_id'] = $this->session->userdata('comp_id');
            $row['user_id'] = $this->session->userdata('user_id');
            $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'financial_year' => $financial_year, 'status' => 0];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $result = $this->user_model->getAry('purchases', $order, '', '', $where);
            $data['result'] = $result;
            return $this->load->view('inventory_sep/inventory', $data);
        }

        public function store($id = null)
        {
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
            $where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            $res = $this->user_model->getRow('purchases', $where);
            if ($res) {
                $data['status'] = 1;
                $return = $this->user_model->updateAry('purchases', $data, $where);
                if ($return) {
                    $where = ['invoiceID' => $id, 'type' => "Purchase"];
                    $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                    if ($res) {
                        $statusChange['status'] = 1;
                        $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $id, 'invoice_type' => 'Purchase', 'transaction_type' => 'DR']);
                        foreach ($res as $da) {
                            $this->user_model->updateAry('invoice_product', $statusChange, $where);
                        }
                    }
                    echo json_encode(['status' => true, 'msg' => 'Purchase Stored successfully.']);
                    return;
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