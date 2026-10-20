<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Productcode extends CI_Controller
    {
    	public function __construct()
    	{
            parent::__construct();
            $this->load->helper(['form', 'url']);
            $this->load->model('user_model');

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
            $user_id = $this->session->user_id;
            $comp_id = $this->session->comp_id;

            $where = ['user_id' => $user_id, 'comp_id' => $comp_id]; 
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['product'] = $this->user_model->getRow('productcode', $where);
            $data['page'] = 'productcode/index';
            $this->load->view('inventory_sep/inventory', $data);
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
        
	public function getproducts()
	{
		$product_where = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
		if ($this->session->userdata('BranchRole')) {
			$product_where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
		}
		$product = $this->db->select('id, p_name')->order_by('p_name', 'ASC')->get_where('product', $product_where)->result();
		if (isset($product) && !empty($product)) {
			echo json_encode(['status' => true, 'data' => $product]);
		} else {
			echo json_encode(['status' => true, 'data' => 'Data not found.']);
		}
	}

	public function getProduct($id)
	{
		if (!isset($id) && empty($id)) {
			echo json_encode(['status' => false, 'data' => 'Id required.']);
		} else {
			$user_id = $this->session->user_id;
			$comp_id = $this->session->comp_id;
			$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'id' => $id];
			if ($this->session->userdata('BranchRole')) {
				$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
			}
			// $product = $this->db->select('*')->get_where('product', $where)->row();
			$product =	$this->user_model->getRow('product', $where);
			if (isset($product) && !empty($product)) {
				// $there = ['id' => $product->tax];
				// $product['tax'] =	$this->user_model->getAry('tax', '', '', '', $there);
				// // $product['unit'] =	$this->user_model->getAry('unit');



				// $product['product'] = $product;

				echo json_encode(['status' => true, 'data' => $product]);
			} else {
				echo json_encode(['status' => true, 'data' => 'Data not found.']);
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




		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Product Name *');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Product Code *');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hsn Code*');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Weight');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Qty');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Product Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Long Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Selling Price');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Discount');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Purchase Price');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Min Qty');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Tax');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Unit');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Product MRP');


		// $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Tax');
		$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray(
			array(
				'font' => array(
					// 'bold' => true,
					// 'size' => 12,
					'color' => array('argb' => 'FF0000'),
				),
				// 'alignment' => array(
				// 	'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				// 	'wrap' => true,
				// ),
				'alignment' => array(
					'text' => 'center'
				),
				// 'borders' => array(
				// 	// 'outline' => array(
				// 	// 	'style' => PHPExcel_Style_Border::BORDER_THICK,
				// 	// 	'color' => array('argb' => 'FF993300'),
				// 	// ), 
				//    'size'=>1,
				// ),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				)
				// 'fill'  => array(
				// 	'type' => PHPExcel_Style_Fill::FILL_SOLID,
				// 	'color'	=> array('argb' => 'FFCCFFCC')
				// ),
			)
		);

		$qty = 0;
		$taxable = 0;
		$taxamount = 0;
		$netvalue = 0;

		$rowCount = 2;
		$returnsale = 0;
		$saleproduct = 0;
		$returnpurchase = 0;
		$purchasepruduct = 0;
		$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
		if ($this->session->userdata('BranchRole')) {
			$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
		}
		$result = $this->user_model->getAry('product', '', '', '', $where);
		// echo '<pre>';
		// print_r($result);
		// die;
		foreach ($result as $key => $val) {



			$objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':N' . $rowCount)->applyFromArray(
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
			// $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $val->city);
			$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, get_title('tax', ['id' => $val->tax], 'tax_per'));
			$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, get_title('unit', ['id' => $val->unit], 'name'));
			$objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $val->mrp);
			$rowCount++;
		}

		// echo '<pre>';
		// 		print_r($result);importfile
		// 		die;

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

		// header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Disposition: attachment; filename= "' . $fileName . '"');
		header("Cache-Control: max-age=0");
		$objWriter->save("php://output");

		// readfile($fileName);

		// exit;
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
			$user_id = $this->session->userdata('user_id');
			$comp_id = $this->session->userdata('comp_id');
			$where = ['product_id' => $id, 'comp_id' => $comp_id, 'user_id' => $user_id, 'invoiceID' => $invoiceID];
			if ($this->session->userdata('BranchRole')) {
				$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
			}
			// $product = $this->db->select('*')->get_where('product', $where)->row();
			$product =	$this->user_model->getRow('invoice_product', $where);
			if (isset($product) && !empty($product)) {
				echo json_encode(['status' => true, 'data' => $product]);
			} else {
				echo json_encode(['status' => true, 'data' => 'Data not found.']);
			}
		}
	}
        
	public function getProductCode($id)
	{
            if (!isset($id) && empty($id)) {
                echo json_encode(['status' => false, 'data' => 'Id required.']);
            } else {
                if ($this->session->userdata('RoleType') == "Salesman") {
                    $user_id = $this->session->userdata('UserId');
                    $comp_id = $this->session->userdata('CompId');
                } else {
                    $user_id = $this->session->userdata('user_id');
                    $comp_id = $this->session->userdata('comp_id');
                }
                
                $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'qrCode' => $id];
				if ($this->session->userdata('BranchRole')) {
					$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
				}
                $product = $this->user_model->getRow('product', $where);
                if (isset($product) && !empty($product)) {
                    echo json_encode(['status' => true, 'data' => $product]);
                } else {
                    echo json_encode(['status' => false, 'data' => 'Data not found.']);
                }
            }
	}

	public function getProductsCode()
	{

		$tax_id = $this->input->post('id');
		$rownumber = $this->input->post('rownumber');
		$where = ['id' => $tax_id];
		$result1 = $this->user_model->getRow('productcode', $where);
		// print_r($tax_id);
		// die;
		$result = $this->user_model->getAry('productcode', '', '', '', '');
		$html = '';
		$html .= "<select style='width:100%;' class='form-control' onchange='onProductCodeSelect($rownumber,event)' id='p_code$rownumber' name='product[$rownumber][p_code]' required>";
		// select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
		$html .= "<option value='' >-Select-</option>";
		if (!empty($result1)) {
			$tax_per = $result1->productcode;

			$html .= "<option value=" . $result1->id . " selected >" . $tax_per . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->productcode . "</option>";
		}
		$html . "</select>";

		print_r($html);
	}

	public function save()
	{

		if ($_POST) {
			// echo '<pre>';
			// print_r($_POST);
			// die;
			$this->form_validation->set_rules('productcode', 'Product Code', 'required|trim|numeric');
			$this->form_validation->set_rules('prefix_product_code', 'Prefix Code', 'required|trim');

			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
				$data['page'] = 'inventory_sep/productcode/index';
				$this->load->view('inventory', $data);
			} else {

				$data['user_id'] = $this->session->userdata('user_id');
				$data['comp_id'] = $this->session->userdata('comp_id');
				$data['productcode'] = $this->input->post('productcode');
				$data['prefix_product_code'] = $this->input->post('prefix_product_code');

				if ($this->session->userdata('BranchRole')) {
					$data['CompanyBranchId'] = $this->session->userdata('CompBranchId');
				}
				$result = $this->user_model->saveAry('productcode', $data);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('productcode');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('productcode');
				}
			}
		} else {
			redirect('productcode');
		}
	}


	public function update()
	{
		if ($_POST) {
			$this->form_validation->set_rules('productcode', 'Product Code', 'required|trim|numeric');
			$this->form_validation->set_rules('prefix_product_code', 'Prefix Code', 'required|trim');
			$this->form_validation->set_rules('id', 'Main Id', 'required|trim');
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
				$data['page'] = 'inventory_sep/productcode/index';
				$this->load->view('inventory', $data);
			} else {
				$id = $this->input->post('id');
				$user_id = $this->session->userdata('user_id');
				$comp_id = $this->session->userdata('comp_id');
				// $data['hsn'] = $this->session->userdata('gsn_code');
				$data['productcode'] = $this->input->post('productcode');
				$data['prefix_product_code'] = $this->input->post('prefix_product_code');
				$where = ['id' => $id, 'comp_id' => $comp_id, 'user_id' => $user_id];
				if ($this->session->userdata('BranchRole')) {
					$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
				}
				$result = $this->user_model->updateAry('productcode', $data, $where);
				if ($result) {
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('productcode');
				} else {
					$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
					redirect('productcode');
				}
			}
		}
	}
	public function productCodeInfo()
	{
		$pro_id = $this->input->post('id');
		$rownumber = $this->input->post('rownumber');
		$user_id = $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('comp_id');
		$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'id' => $pro_id];
		if ($this->session->userdata('BranchRole')) {
			$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
		}
		$result1 = $this->user_model->getRow('productcode', $where);
		// print_r($tax_id);
		// die;
		$where1 = ['user_id' => $user_id, 'comp_id' => $comp_id];
		if ($this->session->userdata('BranchRole')) {
			$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
		}

		$result = $this->user_model->getAry('productcode', '', '', '', $where1);
		$html = '';
		// $html .= "<select class='form-control' onchange='ChangeTax(this.value,$rownumber)' id='tax" . $rownumber . "' name='product[$rownumber][tax]' required>";
		// $html .= "<select class='form-control' name='product[$rownumber][product]' style='width: 195px !important;' id='productId$rownumber' class='form-control searchable_select' onchange='onProductSelect($rownumber,event)' onmouseover='showModelPop($rownumber,event)' onfocusout='onProductFocusChange(event,$rownumber)' required='true'>";
		$html .= "<select style='width:100%;' class='form-control' onchange='onProductCodeSelect($rownumber,event)' id='p_code$rownumber' name='product[$rownumber][p_code]' required>";

		// $html .= "<option value='' >-Select-</option>";
		if (!empty($result1)) {
			$tax_per = $result1->productcode;

			$html .= "<option value=" . $result1->id . " selected>" . $tax_per . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->productcode . "</option>";
		}
		$html . "</select>";

		print_r($html);
	}

	public function delete($id)
	{
		if (isset($id) && !empty($id)) {
			$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id')];

			$return = $this->user_model->deleteRow('productcode', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('invent/productcode');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('invent/productcode');
			}
		} else {
			redirect('invent/productcode');
		}
	}

	public function load_product()
	{
		$data['page'] = 'admin_details/product/product_popup';
		$this->load->view('admin', $data);
	}




	public function edit($id)
	{
		if (isset($id) && !empty($id)) {

			// $where = [];
			$where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
			if ($this->session->userdata('BranchRole')) {
				$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
			}
			$return = $this->user_model->getRow('productcode', $where);

			if ($return) {
				$data['result'] = $return;
				$data['page'] = 'productcode/edit';
				$this->load->view('inventory_sep/inventory', $data);
			} else {
				// $data['page'] = 'inventory_sep/userlist';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('product');
			}
		} else {
			return redirect('product');
		}
	}


	public function deleteProduct()
	{
		$post = $this->input->post();

		if (isset($post) && !empty($post)) {
			$product_id = $this->input->post('product_id');
			if (!isset($product_id) && empty($product_id)) {
				redirect('invent/product');
			}

			$deleteData = '';


			foreach ($product_id as $key => $value) {

				$product_id = $value;

				$deleteData = $this->user_model->deleteRow('product', ['id' => $product_id]);
			}
			if (isset($deleteData) && !empty($deleteData)) {
				$this->session->set_flashdata(['msg' => 'Product Deleted .', 'class' => 'success']);
				redirect('invent/product');
			} else {
				$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
				redirect('invent/product');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Select First', 'class' => 'danger']);
			redirect('invent/product');
		}
	}
}
