<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set("Asia/Calcutta");
    
    class Product extends CI_Controller
    {
    	public function __construct()
    	{
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);
            $this->load->model('user_model');
            $this->load->library('ajax_pagination_dashboard');
    
            if ($this->session->userdata('RoleType')) {
                if (empty($this->session->userdata('UserId'))) {
                    return redirect(base_url() . 'index');
                }
            } else {
                if (empty($this->session->userdata('user_id'))) {
                    return redirect(base_url() . 'index');
                }                
            }
    	}
        
        public function index()
        {
            $productWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
            $where = ['userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id'), 'isDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $productWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }            
            $data['products'] = $this->db->select('id, p_name')->where($productWhere)->get('product')->result();
            $data['units'] = $this->db->select('id, name')->get('unit')->result();
            $data['taxes'] = $this->db->select('id, name')->get('tax')->result();
            $data['categories'] = $this->db->select('id, name')->where($where)->get('category')->result();
            $data['brands'] = $this->db->select('id, name')->where($where)->get('brand')->result();
            $data['page'] = 'product/index';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
         public function getProductsReport() 
        {
            if ($this->input->is_ajax_request()) {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
                $productId = $this->input->post('productId');
                $unitId = $this->input->post('unitId');
                $taxId = $this->input->post('taxId');
                $hsnCode = $this->input->post('hsnCode');
                $categoryId = $this->input->post('categoryId');
                $brandId = $this->input->post('brandId');
                $hsnStatus = $this->input->post('hsnStatus');
                
                $limit = 50;
                $page = $this->input->post('page') ?? 1;
                $offset = ($page - 1) * $limit;                
                
                $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $compBranchId;
                }
                if (!empty($productId)) {
                    $where['id'] = $productId;
                }
                if (!empty($unitId)) {
                    $where['unit'] = $unitId;
                }
                if (!empty($taxId)) {
                    $where['tax'] = $taxId;
                }
                if (!empty($hsnCode)) {
                    $where['hsn'] = $hsnCode;
                }
                if (!empty($categoryId)) {
                    $where['categoryId'] = $categoryId;
                }
                if (!empty($brandId)) {
                    $where['brandId'] = $brandId;
                }
                if (!empty($hsnStatus)){
                    $where['hsn_verified'] =$hsnStatus;
                }
                
                $totalRecords = $this->db->where($where)->count_all_results('product');
                
                $productsQuery = $this->db->select('id, qrCode, min_qty, p_name, hsn, tax_qty, unit_name, price, hsn_verified')
                    ->where($where)
                    ->order_by('id', 'DESC')
                    ->limit($limit, $offset)
                    ->get('product');
                if ($productsQuery->num_rows()) {
                    $table =  '<table class="table table-bordered table-hover table-striped">'
                        . '<thead class="bg-primary">'
                        . '<tr>'
                        . '<th><input type="checkbox" id="product-selectAll" /></th>'
                        . '<th>Product Code</th>'
                        . '<th>Product</th>'
                        . '<th>HSN</th>'
                        . '<th>HSN Status</th>'
                        . '<th>Tax</th>'
                        . '<th>Unit</th>'
                        . '<th>Selling Price</th>'
                        . '<th>Available Stock</th>'
                        . '<th>...</th>'
                        . '</tr>'
                        . '</thead>'
                        . '<tbody>';
                    foreach ($productsQuery->result() as $key => $data) {
                        $productBalanceQuanity = ProductBalanceQuanity($data->id);
                        $hsnBtnCls = $data->hsn_verified == "Y" ? "btn-success" : "btn-danger"; 
                        $hsn_status = 'unverified';
                        if ($data->hsn_verified == "Y") { $hsn_status='verified'; }
                        $table .= '<tr style="background-color: #' . ($productBalanceQuanity < $data->min_qty ? 'ff000061' : 'ffffff') . ';">'
                           .  '<td class="cheking">'
                           .  '<input class="checkdata" type="checkbox" name="product_id[]" value="' . $data->id . '" />'
                           .  '</td>'
                           .  '<td id="qrCode' . $data->id . '">' . $data->qrCode . '</td>'
                           .  '<td id="p_name' . $data->id . '">' . $data->p_name . '</td>'
                           .  '<td id="hsn' . $data->id . '">' . $data->hsn . '</td>'
                           .  '<td id="hsn_verified' . $data->id . '" >' . $hsn_status . '</td>'
                           .  '<td id="tax_qty' . $data->id . '">' . $data->tax_qty . '%</td>'
                           .  '<td id="unit_name' . $data->id . '">' . $data->unit_name . '</td>'
                           .  '<td id="price' . $data->id . '">' . $data->price . '</td>'
                           .  '<td id="ProductBalanceQuanity' . $data->id . '">' . $productBalanceQuanity . '</td>'
                           .  '<td>'
                           .  '<a href="javascript:void(0);" onclick="productEdit(' . $data->id . ');" class="btn btn-xs btn-success" title="Edit">'
                           .  '<i class="fa fa-edit"></i>'
                           .  '</a>'
                           .  '&nbsp;'
                           .  '<a href="javascript:void(0);" onclick="productStock(' . $data->id . ');" class="btn btn-xs btn-primary" title="Stock">'
                           .  '<i class="fa fa-shopping-basket"></i>'
                           .  '</a>'
                           .  '&nbsp;' 
                           .  '<a href="javascript:void(0);" onclick="productHsnVerifie(' . $data->id . ');" class="btn btn-xs '.$hsnBtnCls.'" id="row_'.$data->id.'" title="Product hsn verifie with Goods and Services Tax E - Waybill System">'
                           .  '<i class="fa fa-check" ></i>'
                           .  '</a>'
                           .  '</td>'
                           .  '</tr>';
                    }
                    $table .= '</tbody>'
                           .  '</table>';
                           
                    $totalPages = ceil($totalRecords / $limit);
                    
                    echo json_encode(['table' => $table, 'pagination' => ['totalRecords' => $totalRecords, 'totalPages' => $totalPages, 'currentPage' => $page, 'limit' => $limit]]);
                } else {
                    echo json_encode(['table' => '<span class="text-danger text-center">No Record found.</span>', 'pagination' => null]);
                }
            }
        }
        
        protected function rules()
        {
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'trim|required|max_length[60]'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|max_length[60]|valid_email|is_unique[parties.email]'
                ),
                array(
                    'field' => 'contact',
                    'label' => 'contact',
                    'rules' => 'trim|max_length[25]|min_length[5]|is_unique[parties.contact]'
                ),
                array(
                    'field' => 'address_1',
                    'label' => 'address_1',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'state',
                    'label' => 'state',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'city',
                    'label' => 'city',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'pan',
                    'label' => 'pan',
                    'rules' => 'trim|is_unique[parties.pan]'
                ),
            );
            return $rules;
	}
        
	public function getallproductsequence($start = 0)
	{
            $limit = 10;
            $table = 'product';
            $order = ['col' => 'p_name', 'order' => 'ASC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $CompBranchId;
            }
            $count =  $this->user_model->count_all($table, '', '', '', '', '', '', $where);
            $result =  $this->user_model->getTblerDate($table, $start, $limit, $order, '', '', '', '', '', '', $where);
	
            $config['base_url'] = base_url() . 'product/getallproductsequence';
            $config['reuse_query_string'] = true;
            $config['total_rows'] = $count;
            $config['per_page'] = 10;
            $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';
            $config['full_tag_close'] = '</ul></nav>';
            $config['first_link'] = 'First';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = 'Previous';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['attributes'] = array('class' => 'page-link');
            
            $this->pagination->initialize($config);
            $data['result'] = $result;
            $data['start'] = $start;
            $data['end'] = (int)$start + count($result);
            $data['rowCount'] = $count;
            $data['totalCount'] = $this->user_model->RowCount('product');
            echo json_encode(['data' => $data, 'status' => true]);
	}
        
        public function getproducts()
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $where = ['status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
            $productCodewhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $CompBranchId;
                $productCodewhere['CompanyBranchId'] = $CompBranchId;
            }
            $product['product'] = $this->db->select('id, p_name')->order_by('p_name', 'ASC')->get_where('product', $where)->result();
            if (isset($product) && !empty($product)) {
                $product['productCode'] = $this->db->select('id, productcode')->order_by('id', 'ASC')->get_where('productcode', $productCodewhere)->result();
                echo json_encode(['status' => true, 'data' => $product]);
            } else {
                echo json_encode(['status' => false, 'data' => 'Data not found.']);
            }
        }

	public function getallproduct($start = 0)
	{
            $limit = 10;
            $table = 'product';
            $order = ['col' => 'p_name', 'order' => 'ASC'];
            $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $count =  $this->user_model->count_all($table, '', '', '', '', '', '', $where);
            $result =  $this->user_model->getTblerDate($table, $start, $limit, $order, '', '', '', '', '', '', $where);

            $config['base_url'] = base_url() . 'product/getallproduct';
            $config['reuse_query_string'] = true;
            $config['total_rows'] = $count;
            $config['per_page'] = 10;
            $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';
            $config['full_tag_close'] = '</ul></nav>';
            $config['first_link'] = 'First';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = 'Previous';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['attributes'] = array('class' => 'page-link');
            
            $this->pagination->initialize($config);
            $data['result'] = $result;
            $data['start'] = $start;
            $data['end'] = (int)$start + count($result);
            $data['rowCount'] = $count;
            $data['totalCount'] = $this->user_model->RowCount('product');
            $data['page'] = 'main/ppopup';
            return $this->load->view('ppopup', $data);
	}

	public function getProduct($Id)
	{
            if (!isset($Id) && empty($Id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $CompId = $this->session->userdata('CompId');
                    $UserId = $this->session->userdata('UserId');
                } else {
                    $CompId = $this->session->userdata('comp_id');
                    $UserId = $this->session->userdata('user_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'id' => $Id];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                }
                $this->db->where($Where);
                $product = $this->db->get('product')->row();
                
                if (isset($product) && !empty($product)) {
                    echo json_encode(['status' => true, 'data' => $product]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

	public function exportproduct()
	{
            $fileName = 'product-' . time() . '.xls';
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);

            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Product Name *');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Product Code *');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hsn Code');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Weight');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Qty');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Product Description');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Long Description');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Selling Price');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Discount');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Purchase Price');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Min Qty');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Tax*');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Unit*');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Product MRP');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Warehouse');

            $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
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
	
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $CompBranchId;
            }
            $result = $this->user_model->getAry('product', '', '', '', $where);

            if ($result) {
                foreach ($result as $key => $val) {
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':O' . $rowCount)->applyFromArray(
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

                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val->p_name);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val->qrCode);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val->hsn);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val->weight);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val->qty);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val->short_de);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $val->long_de);
                    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val->price);
                    $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $val->discount);
                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $val->purchase_rate);
                    $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $val->min_qty);
                    $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, get_title('tax', ['id' => $val->tax], 'tax_per'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, get_title('unit', ['id' => $val->unit], 'name'));
                    $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $val->mrp);
                    $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, empty($val->warehouse) ? '' : get_title('warehouse', ['id' => $val->warehouse], 'name') . ' - ' . get_title('warehouse', ['id' => $val->warehouse], 'location'));
                    $rowCount++;
                }

                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header('Content-Disposition: attachment; filename= "' . $fileName . '"');
                header("Cache-Control: max-age=0");
                $objWriter->save("php://output");
            } else {
                $this->session->set_flashdata(['msg' => 'Not Found List.', 'class' => 'danger']);
                redirect('product');
            }
	}
        
	public function exportproducts()
	{
            $fileName = 'product-' . time() . '.xls';
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);

            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Product Name *');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Product Code *');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hsn Code');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Weight');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Qty');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Product Description');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Long Description');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Selling Price');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Discount');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Purchase Price');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Min Qty');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Tax*');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Unit*');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Product MRP');

            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray(
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

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header('Content-Disposition: attachment; filename= "' . $fileName . '"');
            header("Cache-Control: max-age=0");
            $objWriter->save("php://output");
	}

	public function getValueByApprox($field, $search, $table)
	{
            $query = $this->db->like($field, $search)->orderBy('id')->get($table);
            return $query->result();
	}
        
	public function proInfo()
	{
            $id = $this->input->post('id');
            $invoiceID = $this->input->post('invoiceID');

            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('CompId');
                }                
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['product_id' => $id, 'comp_id' => $CompId, 'user_id' => $UserId, 'invoiceID' => $invoiceID];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $CompBranchId;
                }
                
                $product = $this->user_model->getRow('invoice_product', $where);
                if (isset($product) && !empty($product)) {                            
                    echo json_encode(['status' => true, 'data' => $product]);
                } else {
                    echo json_encode(['status' => true, 'data' => 'Data not found.']);
                }
            }
	}
        
	public function save()
	{
            if ($_POST) {
                $this->form_validation->set_rules('name', 'Name', 'required|trim');
                $this->form_validation->set_rules('p_code', 'product Code', 'trim|callback_unique_productcode');
                $this->form_validation->set_rules('unit', 'Unit', 'required|trim');
                $this->form_validation->set_rules('tax', 'Tax', 'required|trim');
                
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
                    $data['page'] = 'inventory_sep/product';
                    $this->load->view('inventory', $data);
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $data['p_name'] = $this->input->post('name');
                    $data['qty'] = $this->input->post('qty');
                    $data['mrp'] = $this->input->post('mrp');
                    $data['price'] = $this->input->post('price');
                    $data['discount'] = $this->input->post('discount');
                    $data['short_de'] = $this->input->post('short_des');
                    $data['long_de'] = $this->input->post('long_des');
                    $data['tax'] = $this->input->post('tax');
                    $data['unit'] = $this->input->post('unit');
                    $data['purchase_rate'] = $this->input->post('purchase_rate');
                    $data['weight'] = $this->input->post('weight');
                    $data['min_qty'] = $this->input->post('min_qty');
                    $data['user_id'] = $UserId;
                    $data['comp_id'] = $CompId;
                    $data['CompanyBranchId'] = $CompBranchId;
                    $data['hsn'] = $this->input->post('gsn_code');
                    $data['qrCode'] = $this->input->post('p_code');
                    $productCode = $this->input->post('p_code');
                    
                    if (!empty($productCode)) {
                        $code = $productCode;
                        //load library
                        $this->load->library('zend');
                        //load in folder Zend
                        $this->zend->load('Zend/Barcode');
                        //generate barcode
                        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $code), array())->draw();
                        imagepng($imageResource, 'uploads/qr_image/' . $code . '.png');
                        $data['img_url'] = '' . $code . '.png';
                    }
                    
                    $return_product = $this->numeric_wcomma($productCode);
                    $unit_id = $this->input->post('unit');
                    $tax = $this->input->post('tax');
                    $where = ['id' => $unit_id];
                    $unit_name = $this->user_model->getRow('unit', $where);
                    $data['unit_name'] = $unit_name->name;

                    $where = ['id' => $tax];
                    $taxaty = $this->user_model->getRow('tax', $where);
                    $data['tax_qty'] = $taxaty->tax_per;
                    $data['cat_id'] = $this->input->post('cate');
                    $data['warehouse'] = get_title('comp_profile', $CompanyBranchWhere, 'warehouse');
                    
                    $file_name = $_FILES['image']['name'];
                    $file_size = $_FILES['image']['size'];
                    $file_tmp = $_FILES['image']['tmp_name'];
                    $file_type = $_FILES['image']['type'];

                    if ($file_name != null) {
                        if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
                            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/product/' . $file_name);
                            $data['image'] = $file_name;
                            $result = $this->user_model->saveAry('product', $data);
                            $pcodess = $this->user_model->getRow('productcode', $CompanyBranchWhere);
                            if ($return_product) {
                                if ($pcodess) {
                                    if ($pcodess->productcode) {
                                        $result = $this->user_model->updateAry('productcode', ['productcode' => $pcodess->productcode + 1], $CompanyBranchWhere);
                                    }
                                }
                            }

                            if ($result) {
                                $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                                return redirect(base_url() . 'invent/product');
                            } else {
                                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                                return redirect(base_url() . 'invent/product');
                            }
                        }
                    } else {
                        $result = $this->user_model->saveAry('product', $data);
                        $pcodess = $this->user_model->getRow('productcode', $CompanyBranchWhere);
                        if ($return_product) {
                            if ($pcodess) {
                                if ($pcodess->productcode) {
                                    $result = $this->user_model->updateAry('productcode', ['productcode' => $pcodess->productcode + 1], $CompanyBranchWhere);
                                }
                            }
                        }

                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'invent/product');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect('invent/product');
            }
	}

	public function numeric_wcomma($str)
	{
            return preg_match('/^[0-9,]+$/', $str);
	}

    	public function savePro()
    	{
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields', 'class' => 'danger']);
                return;
            }

            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('pcode', 'product Code', 'trim|callback_unique_productcodes');
            $this->form_validation->set_rules('qty', 'Qty', 'trim|numeric');
            $this->form_validation->set_rules('price', 'Selling Price', 'trim|numeric|xss_clean|required');
            $this->form_validation->set_rules('min_qty', 'Min Qty', 'trim|numeric');
            $this->form_validation->set_rules('discount', 'Discount', 'trim|numeric');
            $this->form_validation->set_rules('mrp', 'MRP', 'trim|numeric');
            $this->form_validation->set_rules('sale_rate', 'Purchase Price', 'trim|numeric');
            $this->form_validation->set_rules('unit', 'Unit', 'required|trim');
            $this->form_validation->set_rules('tax', 'Tax', 'required|trim');

            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                return;
            }

            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {            
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            // hsn sac
            $hsnCode = $this->input->post('hsn_code');
            $isService = $this->input->post('is_service');            
            $hsnSacType = $isService == 'Y' ? 'Service' : 'Goods';            
            $hsnCodeQuery = $this->db->select('hsnSacCode')->where(['hsnSacCode' => $hsnCode, 'type' => $hsnSacType, 'isDeleted' => 1])->get('hsnsac_master');
            if ($hsnCodeQuery->num_rows()) {
                $data['hsn'] = $hsnCodeQuery->row()->hsnSacCode;
            } else {
                $hsnSacData = [
                    'hsnSacCode' => $hsnCode,
                    'hsnSacDescription' => $this->input->post('name'),
                    'type' => $hsnSacType,
                    'created' => date('d-m-Y h:i:s A'),
                    'createdBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                ];
                $this->user_model->saveAry('hsnsac_master', $hsnSacData);
                $data['hsn'] = $hsnCode;
            }
            //
            
            $data['p_name'] = $this->input->post('name');
            $data['qrCode'] = $this->input->post('pcode');
            $data['mrp'] = $this->input->post('mrp');
            $data['qty'] = $this->input->post('qty');
            $data['price'] = $this->input->post('price');
            $data['discount'] = $this->input->post('discount');
            $data['short_de'] = $this->input->post('short_des');
            $data['long_de'] = $this->input->post('long_des');
            $data['tax'] = $this->input->post('tax');
            $data['unit'] = $this->input->post('unit');
            $data['SubUnit'] = $this->input->post('SubUnit');
            $data['purchase_rate'] = $this->input->post('sale_rate');
            $data['weight'] = $this->input->post('weight');
            $data['min_qty'] = $this->input->post('min_qty');
            $data['segement'] = $this->input->post('segement');
            $data['user_id'] = $UserId;
            $data['comp_id'] = $CompId;
            $data['CompanyBranchId'] = $CompBranchId;
            $unit_id = $this->input->post('unit');
            $tax = $this->input->post('tax');
            $where = ['id' => $unit_id];
            $unit_name = $this->user_model->getRow('unit', $where);
            $data['unit_name'] = $unit_name->name;
            $where = ['id' => $tax];
            $taxaty = $this->user_model->getRow('tax', $where);
            $data['tax_qty'] = $taxaty->tax_per;
            $data['cat_id'] = $this->input->post('cate');
            $data['warehouse'] = get_title('comp_profile', $CompanyBranchWhere, 'default_warehouse');
            $data['OrderBook'] = $this->input->post('OrderBook');
            $data['packetQty'] = $this->input->post('packetQty');
            $data['rewardPoint'] = $this->input->post('rewardPoint');
            $data['batchCode'] = $this->input->post('batchCode');
            $data['dateOfManufacturing'] = $this->input->post('dateOfManufacturing');
            $data['dateOfExpiry'] = $this->input->post('dateOfExpiry');
            $data['categoryId'] = $this->input->post('categoryId');
            $data['brandId'] = $this->input->post('brandId');
            $data['is_service'] = $isService;
            $data['type'] = $this->input->post('type');
            
            $result_id = $this->user_model->saveAry('product', $data);
            
            if ($this->session->userdata('roles') == 1) {
                $BranchQuery = $this->db->select('Id, BranchUserId, CompId')->where(['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1])->order_by('Id', 'DESC')->get('companybranch');
                if ($BranchQuery->num_rows()) {
                    foreach ($BranchQuery->result() as $key => $BranchData) {
                        $data['user_id'] = $BranchData->BranchUserId;
                        $data['comp_id'] = $BranchData->CompId;
                        $data['CompanyBranchId'] = $BranchData->Id;
                        $data['qty'] = 0;
                        $data['min_qty'] = 0;
                        $data['warehouse'] = get_title('comp_profile', ['user_id' => $BranchData->BranchUserId, 'comp_id' => $BranchData->CompId, 'CompanyBranchId' => $BranchData->Id], 'default_warehouse');
                        if ($this->db->select('id')->where(['p_name' => $this->input->post('name'), 'user_id' => $BranchData->BranchUserId, 'comp_id' => $BranchData->CompId, 'CompanyBranchId' => $BranchData->Id])->get('product')->num_rows() == 0) {
                            $this->user_model->saveAry('product', $data);
                        }
                    }
                }
            }
                
            if ($result_id) {
                $productCode = $this->user_model->getRow('productcode', $CompanyBranchWhere);
                if ($productCode) {
                    $original_code = $productCode->productcode;
                    $only_product_code['productcode'] = $original_code + 1;
                    $result = $this->user_model->updateAry('productcode', $only_product_code, $CompanyBranchWhere);
                } else {
                    $query = $this->user_model->getRow('product_code_for_product_only', $CompanyBranchWhere);
                    if ($query) {
                        $original_code = $query->order_product_code;
                        $after_incriement = $original_code + 1;
                        $only_unorder_list['order_product_code'] = $after_incriement;
                        $result = $this->user_model->updateAry('product_code_for_product_only', $only_unorder_list, $CompanyBranchWhere);
                    } else {
                        $only_unorder_list['order_product_code'] = 1;
                        $only_unorder_list['comp_id'] = $CompId;
                        $only_unorder_list['user_id'] = $UserId;
                        if ($this->session->userdata('BranchRole')) {
                            $only_unorder_list['CompanyBranchId'] = $CompBranchId;
                        }
                        $result = $this->user_model->saveAry('product_code_for_product_only', $only_unorder_list);
                    }
                }
                $product_info = $this->user_model->getRow('product', ['id' => $result_id]);
                $NewProductCode = $productCode->prefix_product_code . $productCode->productcode + 1;
                echo json_encode(['status' => true, 'msg' => 'Product Added', 'class' => 'success', 'data' => $product_info, 'ProductCode' => $NewProductCode]);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'Something went Wrong', 'class' => 'danger']);
                return;
            }
	    }
	
        public function add_qty($id = null)
	    {
            if (isset($id) && !empty($id)) {
                $where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                $return = $this->user_model->getRow('product', $where);
                if ($return) {
                    $data['result'] = $return;
                    return $this->load->view('inventory_sep/product/add_qty', $data);
                }
            }
	    }

	    public function update()
	    {
            $id = $this->input->post('id');
            if ($_POST) {
                $id = $this->input->post('id');
                if (!isset($id) && empty($id)) {
                    echo json_encode(['status' => false, 'msg' => 'Please enter required fields', 'class' => 'danger']);
                    return;
                }

                $this->form_validation->set_rules('name', 'Name', 'required|trim');
                $this->form_validation->set_rules('pcode', 'product Code', 'trim');
                $this->form_validation->set_rules('qty', 'Qty', 'trim|numeric');
                $this->form_validation->set_rules('price', 'Selling Price', 'trim|numeric|xss_clean|required');
                $this->form_validation->set_rules('min_qty', 'Min Qty', 'trim|numeric');
                $this->form_validation->set_rules('discount', 'Discount', 'trim|numeric');
                $this->form_validation->set_rules('mrp', 'MRP', 'trim|numeric');
                $this->form_validation->set_rules('sale_rate', 'Purchase Price', 'trim|numeric');
                $this->form_validation->set_rules('unit', 'Unit', 'required|trim');
                $this->form_validation->set_rules('tax', 'Tax', 'required|trim');
                
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                    return;
                }
                
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                // hsn sac
                $hsnCode = $this->input->post('hsn_code');
                $isService = $this->input->post('is_service');            
                $hsnSacType = $isService == 'Y' ? 'Service' : 'Goods';            
                $hsnCodeQuery = $this->db->select('hsnSacCode')->where(['hsnSacCode' => $hsnCode, 'type' => $hsnSacType, 'isDeleted' => 1])->get('hsnsac_master');
                if ($hsnCodeQuery->num_rows()) {
                    $data['hsn'] = $hsnCodeQuery->row()->hsnSacCode;
                } else {
                    $hsnSacData = [
                        'hsnSacCode' => $hsnCode,
                        'hsnSacDescription' => $this->input->post('name'),
                        'type' => $hsnSacType,
                        'created' => date('d-m-Y h:i:s A'),
                        'createdBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                    ];
                    $this->user_model->saveAry('hsnsac_master', $hsnSacData);
                    $data['hsn'] = $hsnCode;
                }
                //
                
                $data['p_name'] = $this->input->post('name');
                $data['qty'] = $this->input->post('qty');
                $data['price'] = $this->input->post('price');
                $data['discount'] = $this->input->post('discount');
                $data['short_de'] = $this->input->post('short_des');
                $data['long_de'] = $this->input->post('long_des');
                $data['tax'] = $this->input->post('tax');
                $data['mrp'] = $this->input->post('mrp');
                $data['unit'] = $this->input->post('unit');
                $data['SubUnit'] = $this->input->post('SubUnit');
                $data['purchase_rate'] = $this->input->post('sale_rate');
                $data['weight'] = $this->input->post('weight');
                $data['min_qty'] = $this->input->post('min_qty');
                $data['segement'] = $this->input->post('segement');
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompBranchId;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['qrCode'] = $this->input->post('pcode');
                $unit_id = $this->input->post('unit');
                $tax = $this->input->post('tax');
                $unit_name = $this->user_model->getRow('unit', ['id' => $unit_id]);
                $data['unit_name'] = $unit_name->name;
                $taxaty = $this->user_model->getRow('tax', ['id' => $tax]);
                $data['tax_qty'] = $taxaty->tax_per;
                $data['OrderBook'] = $this->input->post('OrderBook');
                $data['packetQty'] = $this->input->post('packetQty');
                $data['rewardPoint'] = $this->input->post('rewardPoint');
                $data['batchCode'] = $this->input->post('batchCode');
                $data['dateOfManufacturing'] = $this->input->post('dateOfManufacturing');
                $data['dateOfExpiry'] = $this->input->post('dateOfExpiry');
                $data['categoryId'] = $this->input->post('categoryId');
                $data['brandId'] = $this->input->post('brandId');
                $data['is_service'] = $isService;
                $data['type'] = $this->input->post('type');
                
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_type = $_FILES['image']['type'];
                
                $result = $this->user_model->updateAry('product', $data, $CompanyBranchWhere);
                $product_data = $this->user_model->getRow('product', $CompanyBranchWhere);
                $product_data->total_qty = ProductBalanceQuanity($product_data->id);
                echo json_encode(['status' => true, 'msg' => "Update Record", 'class' => 'success', 'data' => $product_data]);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => "Something Went Wrong", 'class' => 'danger']);
                return;
            }
        }

	    public function updateproducts()
	    {
            if ($_POST) {
                $id = $this->input->post('id');
                if (!isset($id) && empty($id)) {
                    echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                    return;
                }
                
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $data['p_name'] = $this->input->post('name');
                $data['qty'] = $this->input->post('qty');
                $data['price'] = $this->input->post('price');
                $data['discount'] = $this->input->post('discount');
                $data['short_de'] = $this->input->post('short_des');
                $data['long_de'] = $this->input->post('long_des');
                $data['tax'] = $this->input->post('tax');
                $data['mrp'] = $this->input->post('mrp');
                $data['unit'] = $this->input->post('unit');
                $data['purchase_rate'] = $this->input->post('sale_rate');
                $data['weight'] = $this->input->post('weight');
                $data['min_qty'] = $this->input->post('min_qty');
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompBranchId;
                $data['hsn'] = $this->input->post('hsn_code');
                $data['qrCode'] = $this->input->post('pcode');
                
                $unit_id = $this->input->post('unit');
                $tax = $this->input->post('tax');
                $where = ['id' => $unit_id];

                $unit_name = $this->user_model->getRow('unit', $where);
                $data['unit_name'] = $unit_name->name;
                $where = ['id' => $tax];

                $taxaty = $this->user_model->getRow('tax', $where);
                $data['tax_qty'] = $taxaty->tax_per;

                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_type = $_FILES['image']['type'];

                if ($file_name != null) {
                    if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/product/' . $file_name);
                        $data['image'] = $file_name;
                        $result = $this->user_model->updateAry('product', $data, $CompanyBranchWhere);
                        if ($result) {
                            echo json_encode(['status' => true, 'msg' => 'Product Updated', 'class' => 'success']);
                            return;
                        } else {
                            echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                            return;
                        }
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'JPG,JPEG,PNG,JPEG Files Allow', 'class' => 'danger']);
                        return;
                    }
                } else {
                    $result = $this->user_model->updateAry('product', $data, $where);
                    echo json_encode(['status' => true, 'msg' => 'Product Added', 'class' => 'success']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                return;
            }
	}

	public function updateproduct()
	{
            if ($_POST) {
                $id = $this->input->post('id');
                if (!isset($id) && empty($id)) {
                    echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                    return;
                }
                
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $data['p_name'] = $this->input->post('name');
                $data['qty'] = $this->input->post('qty');
                $data['price'] = $this->input->post('price');
                $data['discount'] = $this->input->post('discount');
                $data['short_de'] = $this->input->post('short_desc');
                $data['long_de'] = $this->input->post('long_desc');
                $data['tax'] = $this->input->post('tax');
                $data['mrp'] = $this->input->post('mrp');
                $data['unit'] = $this->input->post('unit');
                $data['purchase_rate'] = $this->input->post('sale_rate');
                $data['weight'] = $this->input->post('weight');
                $data['min_qty'] = $this->input->post('min_qty');
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompBranchId;
                $data['hsn'] = $this->input->post('hsn');
                $data['created_at'] = date('Y-m-d H:i:s');
                
                $unit_id = $this->input->post('unit');
                $tax = $this->input->post('tax');
                $where = ['id' => $unit_id];

                $unit_name = $this->user_model->getRow('unit', $where);
                $data['unit_name'] = $unit_name->name;
                $where = ['id' => $tax];

                $taxaty = $this->user_model->getRow('tax', $where);
                $data['tax_qty'] = $taxaty->tax_per;

                $result = $this->user_model->updateAry('product', $data, $CompanyBranchWhere);
                if ($result) {
                    echo json_encode(['status' => true, 'msg' => 'Product Added', 'class' => 'success']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'Something Went Wrong', 'class' => 'danger']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Something Went Wrong', 'class' => 'danger']);
                return;
            }
	}

	public function updateSaleProduct()
	{
            if ($_POST) {
                $id = $this->input->post('id');
                if (!isset($id) && empty($id)) {
                    echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                    return;
                }
                
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }

                $data['p_name'] = $this->input->post('name');
                $data['qty'] = $this->input->post('qty');
                $data['price'] = $this->input->post('price');
                $data['discount'] = $this->input->post('discount');
                $data['short_de'] = $this->input->post('short_des');
                $data['long_de'] = $this->input->post('long_des');
                $data['tax'] = $this->input->post('tax');
                $data['mrp'] = $this->input->post('mrp');
                $data['unit'] = $this->input->post('unit');
                $data['purchase_rate'] = $this->input->post('purchase_rate');
                $data['weight'] = $this->input->post('weight');
                $data['min_qty'] = $this->input->post('min_qty');
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompBranchId;
                $data['hsn'] = $this->input->post('gsn_code');
                $data['qrCode'] = $this->input->post('p_code');
                $unit_id = $this->input->post('unit');
                $tax = $this->input->post('tax');
                $where = ['id' => $unit_id];

                $unit_name = $this->user_model->getRow('unit', $where);
                $data['unit_name'] = $unit_name->name;
                $where = ['id' => $tax];

                $taxaty = $this->user_model->getRow('tax', $where);
                $data['tax_qty'] = $taxaty->tax_per;

                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_type = $_FILES['image']['type'];
	
                if ($file_name != null) {
                    if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/product/' . $file_name);
                        $data['image'] = $file_name;
                        $result = $this->user_model->updateAry('product', $data, $where);
                        if ($result) {
                            echo json_encode(['status' => true, 'msg' => 'Product Updated', 'class' => 'success']);
                            return;
                        } else {
                            echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                            return;
                        }
                    }
                } else {
                    $result = $this->user_model->updateAry('product', $data, $CompanyBranchWhere);
                    echo json_encode(['status' => true, 'msg' => 'Product Added', 'class' => 'success']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                return;
            }
	}

	public function updateSaleProducts()
	{
            if ($_POST) {
                $id = $this->input->post('id');
                if (!isset($id) || empty($id)) {
                    echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                    return;
                }
                
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $data['p_name'] = $this->input->post('name');
                $data['qty'] = $this->input->post('qty');
                $data['price'] = $this->input->post('price');
                $data['discount'] = $this->input->post('discount');
                $data['short_de'] = $this->input->post('short_des');
                $data['long_de'] = $this->input->post('long_des');
                $data['tax'] = $this->input->post('tax');
                $data['mrp'] = $this->input->post('mrp');
                $data['unit'] = $this->input->post('unit');
                $data['purchase_rate'] = $this->input->post('purchase_rate');
                $data['weight'] = $this->input->post('weight');
                $data['min_qty'] = $this->input->post('min_qty');
                $data['user_id'] = $UserId;
                $data['comp_id'] = $CompId;
                $data['CompanyBranchId'] = $CompBranchId;
                $data['hsn'] = $this->input->post('gsn_code');
                $data['qrCode'] = $this->input->post('p_code');
                $unit_id = $this->input->post('unit');
                $tax = $this->input->post('tax');
                $where = ['id' => $unit_id];
                $unit_name = $this->user_model->getRow('unit', $where);
                $data['unit_name'] = $unit_name->name;
                $where = ['id' => $tax];
                $taxaty = $this->user_model->getRow('tax', $where);
                $data['tax_qty'] = $taxaty->tax_per;
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_type = $_FILES['image']['type'];

                if ($file_name != null) {
                    if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/product/' . $file_name);
                        $data['image'] = $file_name;
                        $result = $this->user_model->updateAry('product', $data, $CompanyBranchWhere);
                        if ($result) {
                            echo json_encode(['status' => true, 'msg' => 'Product Updated', 'data' => $id, 'class' => 'success']);
                            return;
                        } else {
                            echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                            return;
                        }
                    }
                } else {
                    $result = $this->user_model->updateAry('product', $data, $CompanyBranchWhere);
                    echo json_encode(['status' => true, 'msg' => 'Product Added', 'data' => $id, 'class' => 'success']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
                return;
            }
	}

	public function getName($getName = null)
	{
            $result = $this->user_model->getRow('districts', ['id' => $getName]);

            if (!empty($result)) {
                echo json_encode(['status' => true, 'data' => $result]);
            } else {
                echo json_encode(['status' => false, 'data' => "Not Find"]);
            }
	}

	public function pro_name()
	{
            $pro_id = $this->input->post('id');
            $rownumber = $this->input->post('rownumber');
            
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $CompanyBranchWhere = ['id' => $pro_id, 'user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            $this->db->where($CompanyBranchWhere);
            $result1 = $this->db->get('product')->row();
            $html = '';
            $html .= '<input type="text" style="border: none;" onkeydown="return keyDownProduct(event);" onkeyup="productSearch(event,'.$rownumber.')" onkeypress="return enterKey(event);" name="product[' . $rownumber . '][productname]" tabindex="17" id="productname' . $rownumber . '" class="form-control" value="' . $result1->p_name . '" required="true">';
            $html .= '<input type="hidden" id="productId' . $rownumber . '" name="product[' . $rownumber . '][product]" value="' . $result1->id . '">';
            print_r($html);
	}

	public function barcodegenerate()
	{
            if (isset($_POST['product'])) {
                $pro_id = [];
                $resultdata['page'] = "barcode";
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                foreach ($_POST['product'] as $product) {
                    $pro_id[] = $product['id'];
                    if (!empty($product['productcode'])) {
                        $code = $product['productcode'];
                        $data['productcode'] = $product['productcode'];
                        $data['productID'] = $product['id'];
                        $data['qty'] = $product['qty'];
                        $data['comp_id'] = $CompId;
                        $data['user_id'] = $UserId;
                        $data['CompanyBranchId'] = $CompBranchId;
                        $comp_info = $this->user_model->getRow('comp_profile', $CompanyBranchWhere);
                        $qr_type = "horzontal";
                        if ($comp_info) {
                            $qr_type = $comp_info->barcode_type;
                        }
                        //load library
                        $this->load->library('zend');
                        //load in folder Zend
                        $this->zend->load('Zend/Barcode');
                        //generate barcode
                        
                        $CompanyBranchWhere['productID'] = $product['id'];
                        $res = $this->user_model->getRow('qrcode', $CompanyBranchWhere);
                        if ($res) {
                            $qtyy['qty'] = $product['qty'];
                            $res = $this->user_model->updateAry('qrcode', $qtyy, $CompanyBranchWhere);
                        } else {
                            $timestamp = time();
                            $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $code, 'barHeight' => 10, 'factor' => 1, 'drawText' => false), array())->draw();
                            imagepng($imageResource, 'uploads/qr_image/' . $timestamp . $code . '.png');
                            $data['img_url'] = '' . $timestamp . $code . '.png';
                
                            $this->user_model->saveAry('qrcode', $data);
                        }
                    }
                    $CompanyBranchWhere = null;
                }
                $resultdata['result'] = $pro_id;
                if ($qr_type == 'horzontal') {
                    $this->load->view('barcode', $resultdata);
                } elseif ($qr_type == 'virat') {
                    $this->load->view('virat', $resultdata);
                } elseif ($qr_type == 'sticker') {
                    $this->load->view('sticker', $resultdata);
                } else {
                    $this->load->view('vertical', $resultdata);		
                }
            } else {
                return redirect(base_url() . 'invent/product');
            }
	}
        
	public function selectProduct()
	{
                $result = [];
                $post = $this->input->post();
                if (isset($post) && !empty($post)) {
                    $userId = $this->session->userdata('user_id');
                    $compId = $this->session->userdata('comp_id');
                    $compBranchId = $this->session->userdata('CompBranchId');

                    $where = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $where['CompanyBranchId'] = $compBranchId;
                    }
                    foreach ($post['id'] as $key => $value) {
                        $where['id'] = $value;
                        $fetchedProduct = $this->user_model->getRow('product', $where);
                        $fetchedProduct->balanceQty = ProductBalanceQuanity($fetchedProduct->id);
                        
                        $result[] = $fetchedProduct;
                    }
                }

                if (!empty($result)) {
                    echo json_encode(['status' => true, 'data' => $result]);
                } else {
                    echo json_encode(['status' => false, 'data' => "Not Find"]);
                }
	}
        
	public function delete($id)
	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $return = $this->user_model->deleteRow('product', $CompanyBranchWhere);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'invent/product');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'invent/product');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect(base_url() . 'invent/product');
            }
	}

	public function load_product()
	{
            $data['page'] = 'admin_details/product/product_popup';
            return $this->load->view('admin', $data);
	}

	public function importpros()
	{
            if (isset($_FILES['upload_data'])) {
                $data['page'] = 'inventory_sep/product';
                $path = 'uploads/excel_file';
                require_once APPPATH . "/third_party/PHPExcel.php";
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'xlsx|xls';
                $config['remove_spaces'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('uploadFile')) {
                    $data['error'] = array('error' => $this->upload->display_errors());
                } else {
                    $data = array('upload_data' => $this->upload->data());
                }
                if (empty($error)) {
                    if (!empty($data['upload_data']['file_name'])) {
                        $import_xls_file = $data['upload_data']['file_name'];
                    } else {
                        $import_xls_file = 0;
                    }
                    $inputFileName = $path . $import_xls_file;

                    try {
                        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFileName);
                        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                        $flag = true;
                        $i = 0;
                        foreach ($allDataInSheet as $value) {
                            if ($flag) {
                                $flag = false;
                                continue;
                            }
                            $inserdata[$i]['org_name'] = $value['A'];
                            $inserdata[$i]['org_code'] = $value['B'];
                            $inserdata[$i]['gst_no'] = $value['C'];
                            $inserdata[$i]['org_type'] = $value['D'];
                            $inserdata[$i]['Address'] = $value['E'];
                            $i++;
                        }
                        $result = $this->import->importdata($inserdata);
                        if ($result) {
                            echo "Imported successfully";
                        } else {
                            echo "ERROR !";
                        }
                    } catch (Exception $e) {
                        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                    }
                } else {
                    echo $data['error'];
                }
            } else {
                return $this->load->view('inventory', $data);
            }
	}

	public function edit_product($id = null)
	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['userId' => $UserId, 'compId' => $CompId, 'isDeleted' => 1];
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                    $where['compBranchId'] = $CompBranchId;
                }

                $return = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($return) {
                    $data['categories'] = $this->db->select('id, name')->where($where)->get('category')->result();
                    $data['brands'] = $this->db->select('id, name')->where($where)->get('brand')->result();
                    $data['result'] = $return;
                    return $this->load->view('inventory_sep/product/edit_product', $data);
                }
            }
	}

	public function edit_product_for_sale($id = null)
	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $where = ['userId' => $UserId, 'compId' => $CompId, 'isDeleted' => 1];
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];               
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                    $where['compBranchId'] = $CompBranchId;
                }
                $return = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($return) {
                    $data['categories'] = $this->db->select('id, name')->where($where)->get('category')->result();
                    $data['brands'] = $this->db->select('id, name')->where($where)->get('brand')->result();                    
                    $data['result'] = $return;
                    return $this->load->view('inventory_sep/sale/edit_product', $data);
                }
            }
	}
        
	public function stock($id = null)
	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $return = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($return) {
                    $data['result'] = $return;
                    return $this->load->view('inventory_sep/product/stock', $data);
                }
            }
	}

	public function searchproduct()
	{
            $name = $this->input->post('name');
            $this->db->select('*');
            $this->db->from('product');
            $this->db->like('p_name', $name);
            $this->db->limit(12);
            $this->db->where('comp_id', $this->session->userdata('comp_id'));
            $this->db->where('user_id', $this->session->userdata('user_id'));
            if ($this->session->userdata('BranchRole')) {
                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
            }
            $this->db->order_by('p_name', 'ASC');
            $query = $this->db->get();
            $party = $query->result();
            if ($party) {
                $data['party'] = $party;
                echo json_encode(['status' => true, 'data' => $data]);
            } else {
                echo json_encode(['status' => false, 'data' => 'Data not found.']);
            }
	}
        
	public function productpop($id = null)
	{
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            $return = $this->user_model->getRow('product', $CompanyBranchWhere);
            if ($return) {
                $data['result'] = $return;
                $data['page'] = 'main/productpopup';
                return $this->load->view('productpopup', $data);
            }
	}

        public function importfile()
	{
            if (isset($_FILES["file"]["name"])) {
                $file_type = $_FILES["file"]["type"];
                if ($file_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file_type == 'application/vnd.ms-excel') {
                    $path = $_FILES["file"]["tmp_name"];
                    $object = PHPExcel_IOFactory::load($path);
                    $data = [];
	            $UserId = $this->session->userdata('user_id');
	            $CompId = $this->session->userdata('comp_id');
	            $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
	            if ($this->session->userdata('BranchRole')) {
	                $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
	            }
                    
                    foreach ($object->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $product_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $qr = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            $hsn = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                            $weight = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                            $qty = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                            $short_des = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                            $long_desc = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $price = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $discount = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $cost = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $min_qty = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $tax = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                            $unit = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                            $mrp = $worksheet->getCellByColumnAndRow(13, $row)->getValue();

                            $data = array(
                                'p_name'  => $product_name,
                                'hsn'   => $hsn,
                                'qty'    => $qty,
                                'short_de'  => $short_des,
                                'long_de'   => $long_desc,
                                'price' => $price,
                                'discount' => $discount,
                                'purchase_rate' => $cost,
                                'min_qty' => $min_qty,
                                'weight' => $weight,
                                'qrcode' => $qr,
                                'mrp' => $mrp,
                                'tax_qty' => $tax,
                                'unit_name' => $unit,
                                'unit' => get_title('unit', ['name' => $unit], 'id'),
                                'tax' => get_title('tax', ['tax_per' => $tax], 'id'),
                                'user_id' => $UserId,
                                'comp_id' => $CompId,
                                'CompanyBranchId' => $CompBranchId,
                                'warehouse' => get_title('comp_profile', $CompanyBranchWhere, 'default_warehouse')
                            );

                            $res = $this->user_model->saveAry('product', $data);
                        }
                        if (isset($res) && !empty($res)) {
                            $this->session->set_flashdata(['msg' => 'Products Imported', 'class' => 'success']);
                            return redirect(base_url() . 'invent/product');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                            return redirect(base_url() . 'product');
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                return redirect(base_url() . 'invent/product');
            }
	}

	public function productset()
	{
            $id = $this->input->post('id');
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $res = $this->user_model->getRow('product', $CompanyBranchWhere);
                if (isset($res) && !empty($res)) {
                    echo json_encode(['status' => true, 'data' => $res]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

	public function deleteProduct()
	{
            $post = $this->input->post();
            $deleteData = '';
            if (isset($post) && !empty($post)) {
                foreach ($post['id'] as $key => $value) {
                    $Where = ['id' => $value, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'CompanyBranchId'];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    
                    $deleteData = $this->user_model->deleteRow('product', $Where);
                }
                if (isset($deleteData) && !empty($deleteData)) {
                    echo json_encode(['status' => true, 'data' => 'Product Deleted', 'class' => 'success']);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Something Went Wronng', 'class' => 'error']);
                }
            } else {
                echo json_encode(['status' => false, 'data' => 'Data not found.', 'class' => 'error']);
            }
	}

	function check_user_product_name()
	{
            if ($this->input->post('id')) {
                $id = $this->input->post('id');
                $name = $this->input->post('name');
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id != ' => $id, 'p_name' => $name, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $res = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($res) {
                    $this->form_validation->set_message('check_user_product_name', 'Product Name  must be unique for every company');
                    $response = false;
                } else {
                    $response = true;
                }
            }
            return $response;
	}

	function check_user_product_name_update()
	{
            if ($this->input->post('id')) {
                $id = $this->input->post('id');
                $name = $this->input->post('name');
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id != ' => $id, 'p_name' => $name, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $res = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($res) {
                    $this->form_validation->set_message('check_user_product_name_update', 'Product Name  must be unique for every company');
                    $response = false;
                } else {
                    $response = true;
                }
            }
            return $response;
	}

	function check_user_product_code()
	{
            if ($this->input->post('id')) {
                $id = $this->input->post('id');
                $p_code = $this->input->post('p_code');
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id != ' => $id, 'qrCode' => $p_code, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $res = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($res) {
                    $this->form_validation->set_message('check_user_product_code', 'product Code  must be unique for every company');
                    $response = false;
                } else {
                    $response = true;
                }
            }
            return $response;
	}
        
	public function unique_productcode()
	{
            $p_code = $this->input->post('p_code');
            if ($p_code) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['qrCode' => $p_code, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }

                $return_value = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($return_value) {
                    $this->form_validation->set_message('unique_productcode', 'product Code  must be unique for every company');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
	}
        
	public function unique_productcodes()
	{
            $p_code = $this->input->post('pcode');
            if ($p_code) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['qrCode' => $p_code, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }

                $return_value = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($return_value) {
                    $this->form_validation->set_message('unique_productcodes', 'Product Code  must be unique for every company');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
	}

	public function unique_productcodes_update()
	{
            $p_code = $this->input->post('pcode');
            $product_id = $this->input->post('id');
            if ($p_code) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['qrCode' => $p_code, 'id != ' => $product_id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }

                $return_value = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($return_value) {
                    $this->form_validation->set_message('unique_productcodes_update', 'Product Code  must be unique for every company');
                    return false;
                } else {
                    return true;
                }
            }
            return true;
	}
	
        public function all_product_for_sales()
        {
            $query = $this->input->post('query') ?: '';
            $rowId = $this->input->post('rowId');

            $data = $this->user_model->getProductData($query);

            if ($data->num_rows() > 0) {
                $products = $data->result();

                // Build output using array (much faster than string concatenation)
                $output = [];
                $output[] = '<div><b><div id="view_selected_product" style="width:102%;background-color:#1f324a;color:#fff;text-align:left"></div></b>';
                $output[] = '<div class="row" id="example13" style="position:relative;top:0;left:7px;z-index:99;height:auto;max-height:250px;width:800px;min-width:600px;overflow:auto;text-align:left;background-color:#fff;">';

                foreach ($products as $key => $row) {
                    $tabindex = $key + 1;

                    // Pre-calculate display values
                    $productCode = !empty($row->qrCode) ? ', ' : '';
                    $priceCheck = !empty($row->price) ? ', ' : '';

                    $output[] = '<div class="col-md-12 col-sm-12" style="line-height:0px;" tabindex="' . $tabindex . '" id="protr' . $key . '" onkeyup="return keyUpForProduct(event,' . $key . ',' . $rowId . ')" onkeypress="return onProductSelect(' . $row->id . ',' . $key . ',' . $rowId . ')" onclick="return onProductSelect(' . $row->id . ',' . $key . ',' . $rowId . ')" onkeydown="return keyDownForProduct(event,' . $key . ',' . $rowId . ')">
                        <span style="text-transform:capitalize;line-height:20px;">' . $row->p_name . '</span>' . $productCode . '<span>' . $row->qrCode . '</span>' . $priceCheck . '<span>' . $row->price . '</span>' . $productCode . '<span>' . $row->mrp . '</span>' . $productCode . '<span>' . ProductBalanceQuanity($row->id) . '</span><hr style="margin-top:5px;margin-bottom:5px;" />
                    </div>';
                }

                $output[] = '</div></div>';
                echo implode('', $output);
            } else {
                echo '';
            }
        }

	public function all_product_for_sales_by_code()
	{
            $output = '';
            $query = '';
            $rowId = '';
            if ($this->input->post('query')) {
                $query = $this->input->post('query');
            }
            $rowId = $this->input->post('rowId');
            $data = $this->user_model->getProductDataByCode($query);
            $output .= '
                <div class="table-responsive">
                <table class="table table-striped table-bordered table-responsive-sm dataTable" id="example13">
                <tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Price</th>
                </tr>';

            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key => $row) {
                    $tabindex = $key + 1;
                    $output .= '
                        <tr tabindex="' . $tabindex . '"  id="protr' . $key . '" onkeyup="return keyUpForProductCode(event,' . $key . ')" onkeypress="return onProductCodeEnter(' . $row->id . ',event,' . $rowId . ')" onclick="return onProductCodeEnter(' . $row->id . ',event,' . $rowId . ')" onkeydown="return keyDownForProductCode(event,' . $key . ')">
                        <td>' . $row->qrCode . '</td>
                        <td>' . $row->p_name . '</td>
                        <td>' . $row->price . '</td>
                        </tr>';
                }
            } else {
                $output .= '<tr>
                    <td colspan="5">No Data Found</td>
                    </tr>';
            }
            $output .= '</table>';
            echo $output;
	}
        
        public function qtyAdd()
	{
            if (!$this->input->post('product_id')) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields.']);
                return;
            }
            $product_id = $this->input->post('product_id');
            $qty = $this->input->post('qty');

            $product_data = $this->user_model->getRow('product', ['id' => $product_id]);
            if ($product_data) {
                $data['qty'] = $qty + $product_data->qty;
                $return_id = $this->user_model->updateAry('product', $data, ['id' => $product_id]);
                echo json_encode(['status' => true, 'data' => $return_id, 'msg' => 'Qty Added successfully.']);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'Data Not Found']);
                return;
            }
	}
	
        public function create_product()
	{
            return $this->load->view('inventory_sep/estimate/add_product');
	}
        
        public function getProductsData()
        {
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            // Product list ** Get record count ** 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getRowsDataProduct($conditions, 'product'); 

            // Pagination configuration 
            $config['target']      = '#ProductList'; 
            $config['base_url']    = base_url('product/AjaxProductData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = 20; 
            $config['link_func']   = 'searchFilter'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions = array('limit' => 20); 
            $conditions['where'] = $Where;
            
            $data['products'] = $this->user_model->getRowsDataProduct($conditions, 'product');
            $data['page'] = 'product/products';
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function AjaxProductData ()
        {           
            $this->perPage = 20;
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
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            }
            
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where = ['CompanyBranchId' => $CompBranchId];
            }
            
            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getRowsDataProduct($conditions, 'product'); 

            // Pagination configuration 
            $config['target']      = '#ProductList'; 
            $config['base_url']    = base_url('product/AjaxProductData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $this->perPage; 
            $config['link_func']   = 'searchFilter'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $this->perPage;
            $conditions['where'] = $Where;
            unset($conditions['returnType']); 
            $data['products'] = $this->user_model->getRowsDataProduct($conditions, 'product', $OrWhere); 
            
            return $this->load->view('inventory_sep/product/product-list', $data, false);
        }
        
        public function synchronizeHSN ()
        {
            if ($_POST) {
                $product_id = $this->input->post('product_id');
                $sale_product_id = $this->input->post('sale_product_id');
                $purchase_product_id = $this->input->post('purchase_product_id');
                $estimate_product_id = $this->input->post('estimate_product_id');
                $estimate_purchase_product_id = $this->input->post('estimate_purchase_product_id');
                $product_hsn = $this->input->post('product_hsn');
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                foreach ($product_hsn as $key => $value) {                    
                    if (!empty($value) && $value != 0) { 
                        $hsndata = ['hsn' => $value];
                        if ($sale_product_id[$key] == $product_id[$key]) {
                            $sale_where = ['product_id' => $sale_product_id[$key], 'type' => 'Sale','user_id' => $UserId, 'comp_id' => $CompId];
                            if ($this->session->userdata('BranchRole')) {
                                $sale_where['CompanyBranchId'] = $CompBranchId;
                            }
                            $result = $this->user_model->updateAry('invoice_product', $hsndata, $sale_where);
                        }
                        
                        if ($purchase_product_id[$key] == $product_id[$key]) {
                            $purchase_where = ['product_id' => $purchase_product_id[$key], 'type' => 'Purchase','user_id' => $UserId, 'comp_id' => $CompId];
                            if ($this->session->userdata('BranchRole')) {
                                $purchase_where['CompanyBranchId'] = $CompBranchId;
                            }
                            $result = $this->user_model->updateAry('invoice_product', $hsndata, $purchase_where);
                        }
                        
                        if ($estimate_product_id[$key] == $product_id[$key]) {
                            $estimate_where = ['product_id' => $estimate_product_id[$key], 'type' => 'Sale','user_id' => $UserId, 'comp_id' => $CompId];
                            if ($this->session->userdata('BranchRole')) {
                                $estimate_where['CompanyBranchId'] = $CompBranchId;
                            }
                            $result = $this->user_model->updateAry('estimate_product', $hsndata, $estimate_where);
                        }
                        
                        if ($estimate_purchase_product_id[$key] == $product_id[$key]) {
                            $estimate_purchase_where = ['product_id' => $estimate_purchase_product_id[$key], 'type' => 'Purchase','user_id' => $UserId, 'comp_id' => $CompId];
                            if ($this->session->userdata('BranchRole')) {
                                $estimate_purchase_where['CompanyBranchId'] = $CompBranchId;
                            }
                            $result = $this->user_model->updateAry('estimate_product', $hsndata, $estimate_purchase_where);
                        }
                    }
                }

		if ($result) {
                    $this->session->set_flashdata(['msg' => 'HSN code updated successfully', 'class' => 'success']);
                    return redirect(base_url() . 'product/getProductsData');
		} else {
                    $this->session->set_flashdata(['msg' => 'Unable to update data please try again', 'class' => 'danger']);
                    return redirect(base_url() . 'product/getProductsData');
		}
            }
        }
        
        public function ProductForm ()
        {
            if ($this->session->userdata('RoleType')) {
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
            } else {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');                
            }
            
            $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }            
            $data['categories'] = $this->db->select('id, name')->where($where)->get('category')->result();
            $data['brands'] = $this->db->select('id, name')->where($where)->get('brand')->result();
            return $this->load->view('inventory_sep/product/ProductForm', $data);
        }
        
        public function ProductFormPurchase ()
        {
            if ($this->session->userdata('RoleType')) {
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
            } else {
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');                
            }
            
            $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }            
            $data['categories'] = $this->db->select('id, name')->where($where)->get('category')->result();
            $data['brands'] = $this->db->select('id, name')->where($where)->get('brand')->result();            
            return $this->load->view('inventory_sep/purchase/ProductForm', $data);
        }
        
	    public function ProductsForOrderbook()
	    {
            $Output = '';
            $Query = '';
            $RowId = '';
            if ($this->input->post('Query')) {
                $Query = $this->input->post('Query');
            }
            $RowId = $this->input->post('RowId');
            $ProductData = $this->user_model->getProductData($Query);
            if ($ProductData->num_rows() > 0) {
                foreach ($ProductData->result() as $Key => $ProductRow) {
                    if ($Key == 0) {
                        $Output .= '<div class="row" id="ProductDropDown" style="position: relative; top: 0px; left: 7px; bottom:0px; z-index: 99; height: auto; max-height: 250px; width: 560px; min-width: 560px; overflow: scroll; text-align: left; background-color: #ffffff;">';
                    }
                    if (!empty($ProductRow->price)) {
                        $PriceCheck = ", ";
                    } else {
                        $PriceCheck = "";
                    }
                    
                    $Tabindex = $Key + 1;
                    
                    $Output .= '<div class="col-md-12 col-sm-12" style="text-transform: capitalize; line-height: 20px;" tabindex="' . $Tabindex . '" id="ProductRow' . $Key . '" onkeyup="return KeyUpForProducts(event, ' . $Key . ');" onkeypress="return OnProductSelect(' . $ProductRow->id . ', ' . $RowId . ');" onclick="return OnProductSelect(' . $ProductRow->id . ', ' . $RowId . ');" onkeydown="return KeyDownForProducts(event, ' . $Key . ');">';
                    $Output .= $ProductRow->p_name . $PriceCheck . $ProductRow->price . $PriceCheck . $ProductRow->mrp;
                    $Output .= '<hr class="bg-navy mt-1 mb-1" />';
                    $Output .= '</div>';
                }
            }
            $Output .= '</div>';
            echo $Output;
	    }
	    
        public function fetchRawMaterialProducts()
        {
            $output = '';
            $query = '';
            $rowId = '';
            if ($this->input->post('query')) {
                $query = $this->input->post('query');
            }
            $rowId = $this->input->post('rowId');
            $productData = $this->user_model->fetchRawMaterialProducts($query);
            if ($productData->num_rows() > 0) {
                foreach ($productData->result() as $key => $productRow) {
                    if ($key == 0) {
                        $output .= '<div class="row" id="ProductDropDown" style="position: relative; top: 0px; left: 7px; bottom:0px; z-index: 99; height: auto; max-height: 250px; width: 560px; min-width: 560px; overflow: scroll; text-align: left; background-color: #ffffff;">';
                    }
                    if (!empty($productRow->price)) {
                        $priceCheck = ", ";
                    } else {
                        $priceCheck = "";
                    }
                    
                    $tabindex = $key + 1;
                    
                    $output .= '<div class="col-md-12 col-sm-12" style="text-transform: capitalize; line-height: 20px;" tabindex="' . $tabindex . '" id="ProductRow' . $key . '" onkeyup="return KeyUpForProducts(event, ' . $key . ');" onkeypress="return OnProductSelect(' . $productRow->id . ', ' . $rowId . ');" onclick="return OnProductSelect(' . $productRow->id . ', ' . $rowId . ');" onkeydown="return KeyDownForProducts(event, ' . $key . ');">';
                    $output .= $productRow->p_name . $priceCheck . $productRow->price . $priceCheck . $productRow->mrp;
                    $output .= '<hr class="bg-navy mt-1 mb-1" />';
                    $output .= '</div>';
                }
            }
            $output .= '</div>';
            echo $output;
        }
        
        public function fetchFinishMaterialProducts()
        {
            $output = '';
            $query = '';
            $rowId = '';
            if ($this->input->post('query')) {
                $query = $this->input->post('query');
            }
            $rowId = $this->input->post('rowId');
            $productData = $this->user_model->fetchFinishMaterialProducts($query);
            if ($productData->num_rows() > 0) {
                foreach ($productData->result() as $key => $productRow) {
                    if ($key == 0) {
                        $output .= '<div class="row" id="ProductDropDown" style="position: relative; top: 0px; left: 7px; bottom:0px; z-index: 99; height: auto; max-height: 250px; width: 560px; min-width: 560px; overflow: scroll; text-align: left; background-color: #ffffff;">';
                    }
                    if (!empty($productRow->price)) {
                        $priceCheck = ", ";
                    } else {
                        $priceCheck = "";
                    }
                    
                    $tabindex = $key + 1;
                    
                    $output .= '<div class="col-md-12 col-sm-12" style="text-transform: capitalize; line-height: 20px;" tabindex="' . $tabindex . '" id="ProductRow' . $key . '" onkeyup="return KeyUpForProducts(event, ' . $key . ');" onkeypress="return OnProductSelect(' . $productRow->id . ', ' . $rowId . ');" onclick="return OnProductSelect(' . $productRow->id . ', ' . $rowId . ');" onkeydown="return KeyDownForProducts(event, ' . $key . ');">';
                    $output .= $productRow->p_name . $priceCheck . $productRow->price . $priceCheck . $productRow->mrp;
                    $output .= '<hr class="bg-navy mt-1 mb-1" />';
                    $output .= '</div>';
                }
            }
            $output .= '</div>';
            echo $output;
        }
        
        public function getProductTax() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ProductId = $this->input->post('ProductId');
                if (!isset($ProductId) && empty($ProductId)) {
                    echo json_encode(['Status' => false, 'Data' => 'Product id required.']);
                } else {
                    $TaxData = $this->db->select('id')->where('id', $ProductId)->get('product')->num_rows();
                    if (isset($TaxData) && !empty($TaxData)) {
                        $TaxRate = $this->db->select('tax_qty AS Tax')->where('id', $ProductId)->get('product')->row('Tax');
                        echo json_encode(['Status' => true, 'Data' => $TaxRate]);
                    } else {
                        echo json_encode(['Status' => false, 'Data' => 'Data not found.']);
                    }
                }
            }
        }
        
        public function getProductBalanceQuantity ()
        {
            if ($_POST) {
                $ProductId = $this->input->post('ProductId');
                if ($ProductId != null) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $EstimateStockWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                    $ProductWhere = ['id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $SaleWhere = ['status' => 1, 'type' => 'Sale', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $PurchaseWhere = ['status' => 1, 'type' => 'Purchase', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $EstimateSaleWhere = ['status' => 1, 'type' => 'Sale', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $EstimatePurchaseWhere = ['status' => 1, 'type' => 'Purchase', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $TransferToWhere = ['transaction_type' => 'TO', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $TransferFromWhere = ['transaction_type' => 'FROM', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    $StockManufacturerWhere = ['ProductId' => $ProductId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                    $StockManufacturerDataWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                    $RawMaterialWhere = ['ProductId' => $ProductId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                    $CreditNoteWhere = ['product_id' => $ProductId, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId, 'type' => 'CreditNote'];
                    $DebitNoteWhere = ['product_id' => $ProductId, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId, 'type' => 'DebitNote'];                    
                    if ($this->session->userdata('BranchRole')) {
                        $EstimateStockWhere['CompanyBranchId'] = $CompBranchId;
                        $ProductWhere['CompanyBranchId'] = $CompBranchId;
                        $SaleWhere['CompanyBranchId'] = $CompBranchId;
                        $PurchaseWhere['CompanyBranchId'] = $CompBranchId;
                        $EstimateSaleWhere['CompanyBranchId'] = $CompBranchId;
                        $EstimatePurchaseWhere['CompanyBranchId'] = $CompBranchId;
                        $TransferToWhere['CompanyBranchId'] = $CompBranchId;
                        $TransferFromWhere['CompanyBranchId'] = $CompBranchId;
                        $CreditNoteWhere['CompanyBranchId'] = $CompBranchId;
                        $DebitNoteWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                    
                    $EstimateStock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');
                    $Product = $this->db->select('qty')->where($ProductWhere)->get('product')->row();
                    $Sale = $this->db->select('SUM(qty) as sqty')->where($SaleWhere)->get('invoice_product')->row();
                    $Purchase = $this->db->select('SUM(qty) as pqty')->where($PurchaseWhere)->get('invoice_product')->row();
                    $EstimateSale = $EstimateStock == "Yes" ? $this->db->select('SUM(qty) as esqty')->where($EstimateSaleWhere)->get('estimate_product')->row() : 0;
                    $EstimatePurchase = $EstimateStock == "Yes" ? $this->db->select('SUM(qty) as epqty')->where($EstimatePurchaseWhere)->get('estimate_product')->row() : 0;
                    $TransferTo = $this->db->select('SUM(quantity) as ttqty')->where($TransferToWhere)->get('transfer_products')->row();
                    $TransferFrom = $this->db->select('SUM(quantity) as tfqty')->where($TransferFromWhere)->get('transfer_products')->row();
                    $StockManufacturer = $this->db->select('SUM(Quantity) as smqty')->where($StockManufacturerWhere)->get('stock_manufacturer_products')->row();
                    $RawMaterial = $this->db->select('MainProductId, SUM(Quantity) as rmqty')->where($RawMaterialWhere)->get('raw_material_products')->row();
                    $StockManufacturerData = $this->db->select('SUM(Quantity) as smqty_value')->where('ProductId', $RawMaterial->MainProductId)->where($StockManufacturerDataWhere)->get('stock_manufacturer_products')->row();
                    $CreditNote = $this->db->select('SUM(qty) as cnqty')->where($CreditNoteWhere)->get('credit_product')->row();
                    $DebitNote = $this->db->select('SUM(qty) as dnqty')->where($DebitNoteWhere)->get('credit_product')->row();
                    
                    $BalanceQuantity = ($Product->qty + $Purchase->pqty + $TransferTo->ttqty + $EstimatePurchase->epqty + $StockManufacturer->smqty + $DebitNote->dnqty) - ($Sale->sqty + $TransferFrom->tfqty + $EstimateSale->esqty + ($StockManufacturerData->smqty_value * $RawMaterial->rmqty) + $CreditNote->cnqty);
                    echo json_encode(['status' => true, 'data' => $BalanceQuantity]);
                }
            }
        }
        
        public function getProductCode() 
        {
            if ($this->input->is_ajax_request()) {
                $productcodes = 0;

                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {                
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompBranchId = $this->session->userdata('CompBranchId');

                $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }

                $productCode = $this->user_model->getRow('productcode', $CompanyBranchWhere);
                if ($productCode) {
                    $prefix_product_code = $productCode->prefix_product_code;
                    $productcodes = $productCode->productcode;
                    $NewProductCode = $prefix_product_code . $productcodes;
                } else {
                    $query = $this->user_model->getRow('product_code_for_product_only', $CompanyBranchWhere);
                    if ($query) {
                        $inital = $query->order_product_code;
                        if (!is_numeric($inital)) {
                            $inital = 0;
                        }
                    } else {
                        $inital = 0;
                    }
                    $NewProductCode = $inital + 1;
                }
                echo $NewProductCode;
            }
        }
        
        public function category()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['categories'] = $this->db->select('id, name, created, createdBy, updated, updatedBy')->where($where)->order_by('id', 'DESC')->get('category')->result();
            $data['page'] = "product/category/index";
            return $this->load->view('inventory_sep/inventory', $data);            
        }
        
        public function categorySave()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('categoryName', 'Category Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $categoryData = [
                            'name' => $this->input->post('categoryName'),
                            'userId' => $userId,
                            'compId' => $this->session->userdata('comp_id'),
                            'compBranchId' => $this->session->userdata('CompBranchId'),
                            'created' => date('d-m-Y h:i:s A'),
                            'createdBy' => get_title('users', ['id' => $userId], 'name'),
                        ];

                        $Result = $this->user_model->saveAry('category', $categoryData);
                        if (!empty($Result)) {
                            echo json_encode(['Status' => true, 'Message' => 'Record save successfully']);
                            return;
                        } else {
                            echo json_encode(['Status' => false, 'Message' => 'Unable to save record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function categoryEdit($categoryId)
        {
            if (isset($categoryId) && !empty($categoryId)) {
                $where = ['id' => $categoryId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $categoryQuery = $this->db->select('id, name')->where($where)->get('category');
                if ($categoryQuery->num_rows()) {
                    $data['categoryData'] = $categoryQuery->row();
                    return $this->load->view('inventory_sep/product/category/edit', $data);                    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'product/category');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'product/category');
            }
        }
        
        public function categoryUpdate()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('categoryName', 'Category Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $categoryData = [
                            'name' => $this->input->post('categoryName'),
                            'updated' => date('d-m-Y h:i:s A'),
                            'updatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];

                        $Result = $this->user_model->updateAry('category', $categoryData, ['id' => $this->input->post('categoryId')]);
                        if (!empty($Result)) {
                            echo json_encode(['Status' => true, 'Message' => 'Record update successfully']);
                            return;
                        } else {
                            echo json_encode(['Status' => false, 'Message' => 'Unable to update record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function categoryDelete($categoryId)
        {
            if (isset($categoryId) && !empty($categoryId)) {
                $where = ['id' => $categoryId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $categoryQuery = $this->db->select('id')->where($where)->get('category');
                if ($categoryQuery->num_rows()) {
                    if ($this->user_model->updateAry('category', ['isDeleted' => 0], $where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'product/category');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'product/category');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'product/category');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'product/category');
            }            
        }
        
        public function brand()
        {
            $where = ['isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['brands'] = $this->db->select('id, name, created, createdBy, updated, updatedBy')->where($where)->order_by('id', 'DESC')->get('brand')->result();
            $data['page'] = "product/brand/index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function brandSave()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('brandName', 'Brand Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $userId = $this->session->userdata('user_id');
                        $brandData = [
                            'name' => $this->input->post('brandName'),
                            'userId' => $userId,
                            'compId' => $this->session->userdata('comp_id'),
                            'compBranchId' => $this->session->userdata('CompBranchId'),
                            'created' => date('d-m-Y h:i:s A'),
                            'createdBy' => get_title('users', ['id' => $userId], 'name'),
                        ];

                        $Result = $this->user_model->saveAry('brand', $brandData);
                        if (!empty($Result)) {
                            echo json_encode(['Status' => true, 'Message' => 'Record save successfully']);
                            return;
                        } else {
                            echo json_encode(['Status' => false, 'Message' => 'Unable to save record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function brandEdit($brandId)
        {
            if (isset($brandId) && !empty($brandId)) {
                $where = ['id' => $brandId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $brandQuery = $this->db->select('id, name')->where($where)->get('brand');
                if ($brandQuery->num_rows()) {
                    $data['brandData'] = $brandQuery->row();
                    return $this->load->view('inventory_sep/product/brand/edit', $data);                    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'product/brand');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'product/brand');
            }
        }
        
        public function brandUpdate()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('brandName', 'Brand Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                        return;
                    } else {
                        $brandData = [
                            'name' => $this->input->post('brandName'),
                            'updated' => date('d-m-Y h:i:s A'),
                            'updatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];

                        $Result = $this->user_model->updateAry('brand', $brandData, ['id' => $this->input->post('brandId')]);
                        if (!empty($Result)) {
                            echo json_encode(['Status' => true, 'Message' => 'Record update successfully']);
                            return;
                        } else {
                            echo json_encode(['Status' => false, 'Message' => 'Unable to update record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function brandDelete($brandId)
        {
            if (isset($brandId) && !empty($brandId)) {
                $where = ['id' => $brandId, 'isDeleted' => 1, 'userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $where['compBranchId'] = $this->session->userdata('CompBranchId');
                }
                $brandQuery = $this->db->select('id')->where($where)->get('brand');
                if ($brandQuery->num_rows()) {
                    if ($this->user_model->updateAry('brand', ['isDeleted' => 0], $where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'product/brand');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'product/brand');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'product/brand');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'product/brand');
            }
        }  
        
        public function productHsnVerifie($id = null)
    	{
            if (isset($id) && !empty($id)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompanyBranchWhere = ['id' => $id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $return = $this->user_model->getRow('product', $CompanyBranchWhere);
                if ($return) {
                    $data['result'] = $return;
                    return $this->load->view('inventory_sep/product/productHsnVerifie', $data);
                }
            }
	    }

        public function hsnVerified()
        {
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields', 'class' => 'danger']);
                return;
            }
    
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('hsn_code', 'Hsn Code', 'required|trim');
        
            if ($this->form_validation->run() == false) {
                echo json_encode(['status' => false, 'msg' => validation_errors(), 'class' => 'danger']);
                return;
            }
    
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {            
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $CompanyBranchWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            $CompanyBranchWhere['id'] = $this->input->post('product_id'); 
            
            $update_data['tax_qty']=$this->input->post('product_tax');
            $taxaty = $this->user_model->getRow('tax',['tax_per'=>$update_data['tax_qty']]);
            $update_data['tax'] = $taxaty->id; 
            
            $update_data['p_name'] = $this->input->post('name'); 
            $update_data['hsn'] = $this->input->post('hsn_code');  
            $update_data['hsn_verified'] ='Y';  
            $update_data['hsn_verified_date'] =date('Y-m-d H:i:s');   
            $result = $this->user_model->updateAry('product', $update_data, $CompanyBranchWhere);
            
            if ($result) { 
                echo json_encode(['status' => true, 'msg' => 'Product HSN verified successfully', 'class' => 'success', 'data' => $product_info, 'ProductCode' => $NewProductCode]);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'Something went Wrong', 'class' => 'danger']);
                return;
            }
        }
         
        public function invoiceProductHsnVerifie()
        {
            $id=$_GET['invoice_id'];
            $InvoiceType=$_GET['InvoiceType'];
            $table="sales";
            if($InvoiceType=="CRN"){
            $table="credit_notes";
            }else if($InvoiceType=="DBN"){
            $table="debit_notes";
            }
            if (isset($id) && !empty($id)) { 
                $invoiceDetails = $this->user_model->getRow($table, ['id' => $id]);
                if ($invoiceDetails) { 
                    $data['invoiceDetails'] = $invoiceDetails;
                    $data['InvoiceType']=$InvoiceType;
                    return $this->load->view('inventory_sep/product/invoiceProductHsnVerifie', $data);
                }
            }
        }

        public function invoiceProducthsnVerified()
        {
            if (!$this->input->post()) {
                echo json_encode(['status' => false, 'msg' => 'Please enter required fields', 'class' => 'danger']);
                return;
            }
       
            $form_data = $this->input->post(); 
            $invoice_id=$form_data['invoice_id'];
            foreach($form_data['product'] as $key=>$product){    ++$key;  
            $CompanyBranchWhere['id'] = $product['product_id'];
            $update_data['p_name'] = $product['p_name'];
            $update_data['hsn'] = $product['p_hsn'];
            $update_data['hsn_verified'] = 'Y';
            $update_data['hsn_verified_date'] = date('Y-m-d H:i:s'); 
            $result = $this->user_model->updateAry('product', $update_data, $CompanyBranchWhere);
            }  
            if ($result) {
                echo json_encode(['status' => true, 'msg' => 'Product HSN verified successfully', 'class' => 'success', 'invoice_id' => $invoice_id]);
                return;
            } else {
                echo json_encode(['status' => false, 'msg' => 'Something went Wrong', 'class' => 'danger']);
                return;
            }
        }
                
        public function hsnSac()
        {
            $config = array();
            $config["base_url"] = base_url("product/hsnSac");
            $config["total_rows"] = $this->user_model->getCount('hsnsac_master', ['isDeleted' => 1]);
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
            $config['num_tag_close'] = '</span></li>';
            $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
            $config['cur_tag_close'] = '</span></li>';
            $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
            $config['prev_tag_close'] = '</span></li>';
            $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
            $config['next_tag_close'] = '</span></li>';
            $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
            $config['first_tag_close'] = '</span></li>';
            $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
            $config['last_tag_close'] = '</span></li>';

            $this->pagination->initialize($config);

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["hsnSacData"] = $this->user_model->getPaginationData($config["per_page"], $page, 'hsnsac_master', ['isDeleted' => 1]);
            $data["pagination_links"] = $this->pagination->create_links();
            
            $data['page'] = "product/hsnsac/index";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function hsnSacSave()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('hsnSacCode', 'Hsn/Sac Code', 'trim|required|numeric');
                    $this->form_validation->set_rules('type', 'Type', 'required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['status' => false, 'message' => validation_errors()]);
                        return;
                    } else {
                        $hsnSacData = [
                            'hsnSacCode' => $this->input->post('hsnSacCode'),
                            'hsnSacDescription' => $this->input->post('hsnSacDescription'),
                            'type' => $this->input->post('type'),
                            'created' => date('d-m-Y h:i:s A'),
                            'createdBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];

                        $result = $this->user_model->saveAry('hsnsac_master', $hsnSacData);
                        if (!empty($result)) {
                            echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                            return;
                        } else {
                            echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function hsnSacEdit($hsnSacId)
        {
            if (isset($hsnSacId) && !empty($hsnSacId)) {
                $where = ['id' => $hsnSacId, 'isDeleted' => 1];
                $hsnSacQuery = $this->db->select('id, hsnSacCode, hsnSacDescription, type')->where($where)->get('hsnsac_master');
                if ($hsnSacQuery->num_rows()) {
                    $data['hsnSacData'] = $hsnSacQuery->row();
                    return $this->load->view('inventory_sep/product/hsnsac/edit', $data);                    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'product/hsnSac');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'product/hsnSac');
            }
        }
        
        public function hsnSacInfo($hsnSacId)
        {
            if (isset($hsnSacId) && !empty($hsnSacId)) {
                $where = ['id' => $hsnSacId, 'isDeleted' => 1];
                $hsnSacQuery = $this->db->where($where)->get('hsnsac_master');
                if ($hsnSacQuery->num_rows()) {
                    $data['hsnSacData'] = $hsnSacQuery->row();
                    return $this->load->view('inventory_sep/product/hsnsac/info', $data);                    
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'product/hsnSac');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'product/hsnSac');
            }
        }
        
        public function hsnSacUpdate()
        {
            if ($this->input->is_ajax_request()) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('hsnSacCode', 'Hsn/Sac Code', 'trim|required|numeric');
                    $this->form_validation->set_rules('type', 'Type', 'required');
                    if ($this->form_validation->run() == false) {
                        echo json_encode(['status' => false, 'message' => validation_errors()]);
                        return;
                    } else {
                        $hsnSacData = [
                            'hsnSacCode' => $this->input->post('hsnSacCode'),
                            'hsnSacDescription' => $this->input->post('hsnSacDescription'),
                            'type' => $this->input->post('type'),
                            'updated' => date('d-m-Y h:i:s A'),
                            'updatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];

                        $result = $this->user_model->updateAry('hsnsac_master', $hsnSacData, ['id' => $this->input->post('hsnSacId')]);
                        if (!empty($result)) {
                            echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                            return;
                        } else {
                            echo json_encode(['status' => false, 'message' => 'Unable to update record']);
                            return;
                        }
                    }
                }
            }
        }
        
        public function hsnSacDelete($hsnSacId)
        {
            if (isset($hsnSacId) && !empty($hsnSacId)) {
                $where = ['id' => $hsnSacId, 'isDeleted' => 1];
                $hsnSacQuery = $this->db->select('id')->where($where)->get('hsnsac_master');
                if ($hsnSacQuery->num_rows()) {
                    if ($this->user_model->updateAry('hsnsac_master', ['isDeleted' => 0], $where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'product/hsnSac');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'product/hsnSac');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'product/hsnSac');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'product/hsnSac');
            }
        }  
        
        public function getHsnSacCode()
        {
            if ($_POST) {
                $this->db->select('hsnSacCode');
                $this->db->where('isDeleted', 1);
                $this->db->like('hsnSacCode', $this->input->post('hsnSacCode'));
                $hsnSacCodeList = $this->db->get('hsnsac_master')->result();
                if (!empty($hsnSacCodeList)) {
                    echo json_encode($hsnSacCodeList);
                } else {
                    return false;
                }
            }
        }
    }