<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Stocks_report extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);

            $this->load->model('user_model');
            
            $this->load->library('ajax_pagination_dashboard');

            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
        
        public function index()
        {
            $data['page'] = 'stocks/report';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getWarehouseStockReport()
        {   
            $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            // Stock Report ** Get record count ** 
            $conditions['returnType'] = 'count'; 
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getRowsDataStock($conditions, 'product'); 

            // Pagination configuration 
            $config['target']      = '.StockList'; 
            $config['base_url']    = base_url('stocks_report/AjaxStockData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = 15; 
            $config['link_func']   = 'searchFilter'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions = array('limit' => 15); 
            $conditions['where'] = $Where;
            $data['products'] = $this->user_model->getRowsDataStock($conditions, 'product'); 
            
            $data['page'] = 'stocks/warehouse';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function AjaxStockData()
        {
            $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            // Define offset 
            $page = $this->input->post('page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('keywords'); 
            $sortBy = $this->input->post('sortBy');
            $pagelength = $this->input->post('pagelength');
            
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getRowsDataStock($conditions, 'product'); 

            // Pagination configuration 
            $config['target']      = '.StockList'; 
            $config['base_url']    = base_url('stocks_report/AjaxStockData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $pagelength; 
            $config['link_func']   = 'searchFilter'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $pagelength;
            $conditions['where'] = $Where;
            unset($conditions['returnType']); 
            $data['products'] = $this->user_model->getRowsDataStock($conditions, 'product') ?: [];
            $warehouseData = $this->input->post('WarehouseData');
            $data['warehouse'] = !empty($warehouseData)
                ? array_values(array_filter(preg_split("/\s*,\s*/", $warehouseData), 'strlen'))
                : [];
            $data['details'] = $this->input->post('details');
            $data['transfer_type'] = $this->input->post('transfer_type');
            
            return $this->load->view('inventory_sep/stocks/StockList', $data, false);    
        }
        
        public function ExportExcelStock()
        {
            $warehouse = function_exists('input_get_array')
                ? input_get_array('warehouse')
                : (array) ($this->input->get('warehouse') ?: []);

            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $transfer_type = $this->input->get('transfer_type');
            
            if ($this->input->get('details') == "") {
                $details = "";
            } else {
                $details = $this->input->get('details');
            }
            
            $data['warehouse'] = $warehouse;
            $data['transfer_type'] = $transfer_type;
            $data['details'] = $details;
            
            $this->db->select("*");
            $this->db->from('product');
            $this->db->where($where);
            $this->db->where('status', 1);
            $this->db->order_by("id", "DESC");
            $query = $this->db->get();
            $data['result'] = $query->result();
            print_r($this->load->view('inventory_sep/stocks/exportexcelstock', $data, true));
        }
        
        public function stockSummary()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $compId = $this->session->userdata('comp_id');
            $userId = $this->session->userdata('user_id');
            
            $where = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1];
            
            $rawProductQuery = $this->db->select('id, IFNULL(qty, 0) AS quantity, IFNULL(price, 0) AS price')
                ->where($where)
                ->where('type', 'Raw')
                ->order_by('id', 'DESC')
                ->get('product')
                ->result();
            
            $finishProductQuery = $this->db->select('id, IFNULL(qty, 0) AS quantity, IFNULL(price, 0) AS price')
                ->where($where)
                ->where('type', 'Finish')
                ->order_by('id', 'DESC')
                ->get('product')
                ->result();
            
            $data['compId'] = $compId;
            $data['userId'] = $userId;
            $data['rawProducts'] = $rawProductQuery;
            $data['finishProducts'] = $finishProductQuery;
            $data['page'] = 'stocks/stock-summary';
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function rawGoodsStockSummary()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $compId = $this->session->userdata('comp_id');
            $userId = $this->session->userdata('user_id');
            
            $where = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1, 'type' => 'Raw'];
            
            $productId = $this->input->get('productId');
            if ($productId != 'All' && $productId != '') {
                $where['id'] = $productId;
            }
            
            $productQuery = $this->db->select('id, p_name, IFNULL(qty, 0) AS quantity, IFNULL(price, 0) AS price')
                ->where($where)
                ->order_by('id', 'DESC')
                ->get('product')
                ->result();
            
            $data['compId'] = $compId;
            $data['userId'] = $userId;
            $data['products'] = $productQuery;
            $data['page'] = 'stocks/raw-goods-stock-summary';
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function finishGoodsStockSummary()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $compId = $this->session->userdata('comp_id');
            $userId = $this->session->userdata('user_id');
            
            $where = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1, 'type' => 'Finish'];
            
            $productId = $this->input->get('productId');
            if ($productId != 'All' && $productId != '') {
                $where['id'] = $productId;
            }
            
            $productQuery = $this->db->select('id, p_name, IFNULL(qty, 0) AS quantity, IFNULL(price, 0) AS price')
                ->where($where)
                ->order_by('id', 'DESC')
                ->get('product')
                ->result();
            
            $data['compId'] = $compId;
            $data['userId'] = $userId;
            $data['products'] = $productQuery;
            $data['page'] = 'stocks/finish-goods-stock-summary';
            return $this->load->view('inventory_sep/inventory', $data);            
        }
    }