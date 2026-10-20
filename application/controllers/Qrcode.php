<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qrcode extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// $this->load->library('phpqrcode');

		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		// $this->load->library('custom');
		// $this->load->library('upload');
		// $this->load->library('excel');
		if ($this->session->userdata('user_id') === null) {
			redirect('index');
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

	public function index($start = 0)
	{
		$data['page'] = 'inventory_sep/qrcode/index';
$img_url='';

		$toDate = '';
		$fromDate = '';


		if ($this->input->get('from')) {
			$fromDate = ['qrCode >=' => $this->input->get('from')];
			// $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
		}
		if ($this->input->get('to')) {
			// $partyName = ['customerId'=>$this->input->get('partyName')];
			$toDate = ['qrCode <=' => $this->input->get('to')];
		}
		// if($this->input->get('productName')){
		//     $productName = ['product_ids'=>$this->input->get('productName')];
		// }
		$limit = 10;
		$table = 'product';
		$order = ['col' => 'created_at', 'order' => 'DESC'];
		$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
		$count =  $this->user_model->count_all($table, '', '', $fromDate, $toDate, '', '', $where);
		$result =  $this->user_model->getTblerDate($table, $start, $limit, $order, '', '', $fromDate, $toDate, '', '', $where);

		// echo '<pre>';
		// 	print_r($result);
		// 	die;
		$config['base_url'] = base_url('index.php/qrcode/index');
		$config['reuse_query_string'] = true;
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
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

		//$config['anchor_class'] = 'class="page-link"';
		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);

		$data['result'] = $result;
		$data['start'] = $start;
		$data['end'] = (int)$start + count($result);
		$data['rowCount'] = $count;
		$data['totalCount'] = $this->user_model->RowCount($table);
		$this->load->view('inventory', $data);
	}
	public function getproducts()
	{
		$product['product'] = $this->db->select('id, p_name')->order_by('p_name', 'ASC')->get_where('product', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->result();
		if (isset($product) && !empty($product)) {
			$product['productCode'] = $this->db->select('id, productcode')->order_by('id', 'ASC')->get_where('productCode', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->result();
			echo json_encode(['status' => true, 'data' => $product]);
		} else {
			echo json_encode(['status' => false, 'data' => 'Data not found.']);
		}
	}
	public function pdfgenerate()
	{
		$start=0;
		$data['page'] = 'inventory_sep/qrcode/print';


		$toDate = '';
		$fromDate = '';


		if ($this->input->get('from')) {
			$fromDate = ['qrCode >=' => $this->input->get('from')];
			// $toDate = ['invoiceDate <=' => $this->input->get('toDate')];
		}
		if ($this->input->get('to')) {
			// $partyName = ['customerId'=>$this->input->get('partyName')];
			$toDate = ['qrCode <=' => $this->input->get('to')];
		}
		$limit = 10;
		$table = 'product';
		$order = ['col' => 'created_at', 'order' => 'DESC'];
		$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
		$result =  $this->user_model->getTblerDate($table, $start, $limit, $order, '', '', $fromDate, $toDate, '', '', $where);
			$data['result'] = $result;
			// $data['count'] = $count;
			// $data['sale_register'] = $sale_register;	`

			// if ($this->input->get('partyName')) {
			// 	$partyName = ['customerId' => $this->input->get('partyName')];
			// }
			// if ($this->input->get('productName')) {
			// 	$productName = ['product_ids' => $this->input->get('productName')];
			// }
			// $table = 'purchases';
			// $order = ['col' => 'added_at', 'order' => 'DESC'];

			// $result =  $this->user_model->getTblerDate($table, '', '', $order, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName);

			// $data['result'] = $result;
			// $data['sale_register'] =  $this->input->get('sale_register');

			// $data['page'] = 'inventory_sep/purchase_report/print';
			$this->load->view('inventory', $data);
	}

	public function getProduct($id)
	{
		if (!isset($id) && empty($id)) {
			echo json_encode(['status' => false, 'data' => 'Id required.']);
		} else {
			$user_id = $this->session->user_id;
			$comp_id = $this->session->comp_id;
			$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'id' => $id];
			// $product = $this->db->select('*')->get_where('product', $where)->row();
			$product =	$this->user_model->getRow('product', $where);
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
			// $product = $this->db->select('*')->get_where('product', $where)->row();
			$product =	$this->user_model->getRow('invoice_product', $where);
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

			$this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[product.p_name]');
			$this->form_validation->set_rules('p_code', 'product Code', 'trim|callback_unique_productcode');


			$this->form_validation->set_rules('unit', 'Unit', 'required|trim');
			$this->form_validation->set_rules('tax', 'Tax', 'required|trim');

			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
				$data['page'] = 'inventory_sep/product';
				$this->load->view('inventory', $data);
			} else {
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
				$data['user_id'] = $this->session->userdata('user_id');
				$data['comp_id'] = $this->session->userdata('comp_id');
				// $data['hsn'] = $this->session->userdata('gsn_code');
				$data['hsn'] = $this->input->post('gsn_code');
				$data['qrCode'] = $this->input->post('p_code');
				$productCode = $this->input->post('p_code');


				$unit_id = $this->input->post('unit');
				$tax = $this->input->post('tax');


				$where = ['id' => $unit_id];
				$unit_name = $this->user_model->getRow('unit', $where);
				$data['unit_name'] = $unit_name->name;

				$where = ['id' => $tax];
				$taxaty = $this->user_model->getRow('tax', $where);
				$data['tax_qty'] = $taxaty->tax_per;


				$data['cat_id'] = $this->input->post('cate');
				$file_name = $_FILES['image']['name'];
				$file_size = $_FILES['image']['size'];
				$file_tmp = $_FILES['image']['tmp_name'];
				$file_type = $_FILES['image']['type'];



				if ($file_name != null) {
					if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
						move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/product/' . $file_name);
						$data['image'] = $file_name;
						$result = $this->user_model->saveAry('product', $data);
						$user_id = $this->session->userdata('user_id');
						$comp_id = $this->session->userdata('comp_id');
						$there = ['comp_id' => $comp_id, 'user_id' => $user_id];
						$result = $this->user_model->updateAry('productCode', ['productcode' => $productCode], $there);
						if ($result) {
							$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
							redirect('invent/product');
						} else {
							$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
							redirect('invent/product');
						}
					}
				} else {
					// $data['error'] = "Only png,jpg and jpeg allow";
					$result = $this->user_model->saveAry('product', $data);
					$user_id = $this->session->userdata('user_id');
					$comp_id = $this->session->userdata('comp_id');
					$there = ['comp_id' => $comp_id, 'user_id' => $user_id];
					$result = $this->user_model->updateAry('productCode', ['productcode' => $productCode], $there);
					$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
					redirect('invent/product');
				}
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('invent/product');
		}
	}
	public function savePro()
	{
		$data['img_url'] = '';
		$post = $this->input->post();

		$qrtext = $this->input->post('qr_text');

		$data['page'] = 'inventory_sep/qrcode/index';

		if (isset($post) && !empty($qrtext)) {
			// $data = [];
			$code = rand(10000, 99999);
			//load library
			$this->load->library('zend');
			//load in folder Zend
			$this->zend->load('Zend/Barcode');
			//generate barcode
			$imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $code), array())->draw();
			imagepng($imageResource, 'uploads/qr_image/' . $code . '.png');
			$impdata['user_id'] = $this->session->userdata('user_id');
			$impdata['comp_id'] = $this->session->userdata('comp_id');
			$impdata['qrCode'] = $qrtext;
			$impdata['img_url'] = '' . $code . '.png';
			// $data['img_url'] = '' . $code . '.png';
			$data['img_url'] = '' . $code . '.png';

			$res = $this->user_model->saveAry('qrcode', $impdata);
			if ($res) {
				$this->session->set_flashdata(['msg' => 'Barcode Added.', 'class' => 'success']);
				// redirect('product');
				$this->load->view('inventory', $data);
			} else {
				$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
				// redirect('product');
				$this->load->view('inventory', $data);
			}
		} else {

			$this->load->view('inventory', $data);
		}
	}

	public function update()
	{
		$id = $this->input->post('id');
		// print_r($id);
		// die;
		if ($_POST) {
			// echo '<pre>';
			// print_r($_POST);
			// die;
			// $this->form_validation->set_rules('name', 'Name', 'required|trim|is_unique[product.p_name]');
			// $this->form_validation->set_rules('qty', 'Qty', 'required|trim');
			// $this->form_validation->set_rules('price', 'Price', 'required|trim');
			// $this->form_validation->set_rules('unit', 'Unit', 'required|trim');
			// $this->form_validation->set_rules('tax', 'Tax', 'required|trim');

			// $this->form_validation->set_rules('weight', 'Weight', 'required|trim');
			// $this->form_validation->set_rules('min_qty', 'Min Qty', 'required|trim');

			$id = $this->input->post('id');
			if (!isset($id) && empty($id)) {
				// $this->form_validation->set_rules('id', 'id', 'required|trim');
				$this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
				redirect('product');
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
			$data['user_id'] = $this->session->userdata('user_id');
			$data['comp_id'] = $this->session->userdata('comp_id');
			// $data['hsn'] = $this->session->userdata('gsn_code');
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


			// $data['cat_id'] = $this->input->post('cate');
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			// echo '<pre>';
			// print_r($_POST);
			// print_r($_FILES);

			// die;
			$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
			if ($file_name != null) {
				if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg') {
					move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/product/' . $file_name);
					$data['image'] = $file_name;
					$result = $this->user_model->updateAry('product', $data, $where);
					if ($result) {
						$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
						redirect('invent/product');
					} else {
						$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
						redirect('invent/product');
					}
				}
			} else {
				// $data['error'] = "Only png,jpg and jpeg allow";
				$result = $this->user_model->updateAry('product', $data, $where);

				$this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
				redirect('invent/product');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			redirect('invent/product');
		}
	}

	public function updateSaleProduct()
	{

		if ($_POST) {

			$id = $this->input->post('id');
			if (!isset($id) && empty($id)) {
				// $this->form_validation->set_rules('id', 'id', 'required|trim');
				echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
				return;
			}

			// $id = $this->input->post('id');

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
			$data['user_id'] = $this->session->userdata('user_id');
			$data['comp_id'] = $this->session->userdata('comp_id');
			// $data['hsn'] = $this->session->userdata('gsn_code');
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


			// $data['cat_id'] = $this->input->post('cate');
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			// echo '<pre>';
			// print_r($_POST);
			// print_r($_FILES);

			// die;
			$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
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
				// $data['error'] = "Only png,jpg and jpeg allow";
				$result = $this->user_model->updateAry('product', $data, $where);

				echo json_encode(['status' => true, 'msg' => 'Product Added', 'class' => 'success']);
				return;
			}
		} else {
			echo json_encode(['status' => false, 'msg' => 'Invalid Id', 'class' => 'danger']);
			return;
		}
	}
	public function pro_name()
	{
		$pro_id = $this->input->post('id');
		$rownumber = $this->input->post('rownumber');
		$user_id = $this->session->user_id;
		$comp_id = $this->session->comp_id;
		$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'id' => $pro_id];
		$result1 = $this->user_model->getRow('product', $where);
		// print_r($tax_id);
		// die;
		$where1 = ['user_id' => $user_id, 'comp_id' => $comp_id];

		$result = $this->user_model->getAry('product', '', '', '', $where1);
		$html = '';
		// $html .= "<select class='form-control' onchange='ChangeTax(this.value,$rownumber)' id='tax" . $rownumber . "' name='product[$rownumber][tax]' required>";
		$html .= "<select class='searchselect form-control' name='product[$rownumber][product]' style='width: 195px !important;' id='productId$rownumber'  onchange='onProductSelect($rownumber,event)'  onfocusout='onProductFocusChange(event,$rownumber)' required='true'>";

		$html .= "<option value='' >-Select-</option>";
		if ($result1 != null) {
			$tax_per = $result1->p_name;

			$html .= "<option value=" . $pro_id . " selected >" . $tax_per . "</option>";
		}
		foreach ($result as $key => $dist_val) {
			$html .= "<option value=" . $dist_val->id . " >" . $dist_val->p_name . "</option>";
		}
		$html . "</select>";

		print_r($html);
	}

	public function delete($id)
	{
		if (isset($id) && !empty($id)) {
			$where = ['id' => $id, 'user_id' => $this->session->userdata('user_id')];

			$return = $this->user_model->deleteRow('product', $where);
			if ($return) {

				$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
				redirect('invent/product');
			} else {
				$data['page'] = 'admin_details/product/product_cat';
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				redirect('invent/product');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			redirect('invent/product');
		}
	}

	public function load_product()
	{
		$data['page'] = 'admin_details/product/product_popup';
		$this->load->view('admin', $data);
	}

	public function importpros()
	{
		if (isset($_FILES['upload_data'])) {
			$data['page'] = 'inventory_sep/product';
			// if ($this->input->post('submit')) {

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
					die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
						. '": ' . $e->getMessage());
				}
			} else {
				echo $data['error'];
			}
			// }
		} else {
			$this->load->view('inventory', $data);
		}
	}


	public function edit($id)
	{
		if (isset($id) && !empty($id)) {

			// $where = [];
			$where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];

			$return = $this->user_model->getRow('product', $where);

			if ($return) {
				$data['result'] = $return;
				$data['page'] = 'inventory_sep/product_edit';
				$this->load->view('inventory', $data);
			} else {
				// $data['page'] = 'inventory_sep/userlist';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				return redirect('product');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
			return redirect('product');
		}
	}
	public function productpop($id)
	{
		// $where = [];
		$where = ['id' => $id, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];

		$return = $this->user_model->getRow('product', $where);

		if ($return) {
			$data['result'] = $return;
			$data['page'] = 'main/productpopup';


			// $data['page'] = 'productpopup';
			$this->load->view('productpopup', $data);
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
				foreach ($object->getWorksheetIterator() as $worksheet) {
					$highestRow = $worksheet->getHighestRow();
					$highestColumn = $worksheet->getHighestColumn();
					for ($row = 2; $row <= $highestRow; $row++) {
						$product_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						$qr = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						$hsn = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
						$weight = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$qty = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
						$short_de = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
						$long_des = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
						$price = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
						$discount = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
						$cost = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						$min_qty = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
						$tax = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
						$unit = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
						$mrp = $worksheet->getCellByColumnAndRow(13, $row)->getValue();

						$forecheking[] = array(

							'tax' => $this->user_model->getApproxTax('tax', $tax, 'tax_per'),
							'unit' => $this->user_model->getApproxTax('unit', $unit, 'name'),

						);

						// 'tax' => $this->user_model->getApproxTax('tax', $tax, 'tax_per'),
						// 'unit' => $this->user_model->getApproxTax('unit', $unit, 'name'),

						$data[] = array(
							'p_name'  => $product_name,
							'hsn'   => $hsn,
							'qty'    => $qty,
							'short_de'  => $short_de,
							'long_de'   => $long_des,
							'price' => $price,
							'discount' => $discount,
							'purchase_rate' => $cost,
							'min_qty' => $min_qty,
							'weight' => $weight,
							'qrcode' => $qr,
							'mrp' => $mrp,
							// 'tax'=>get_title('tax', ['id' => $tax], 'tax_per'),
							'tax' => $this->user_model->getApproxTax('tax', $tax, 'tax_per'),
							'unit' => $this->user_model->getApproxTax('unit', $unit, 'name'),
							'tax_qty' => $this->user_model->getApproxTaxQty('tax', $tax, 'tax_per'),
							'user_id' => $this->session->userdata('user_id'),
							'comp_id' => $this->session->userdata('comp_id'),
						);

						if (empty($product_name)) {
							$this->session->set_flashdata(['msg' => 'Product Name Is Required', 'class' => 'danger']);
							redirect('invent/product');
						}
						if (empty($this->user_model->getApproxTax('tax', $tax, 'tax_per'))) {
							$this->session->set_flashdata(['msg' => 'Tax Is Required', 'class' => 'danger']);
							redirect('invent/product');
						}
						if (empty($this->user_model->getApproxTax('unit', $unit, 'name'))) {
							$this->session->set_flashdata(['msg' => 'Unit Is Required', 'class' => 'danger']);
							redirect('invent/product');
						}
						// if (empty($price)) {
						// 	$this->session->set_flashdata(['msg' => 'Price Is Required', 'class' => 'danger']);
						// 	redirect('invent/product');
						// }
					}
				}
				// echo '<pre>';
				// print_r($forecheking);
				// die;
				if (isset($forecheking) && !empty($forecheking)) {
					foreach ($forecheking as $checker) {
						$taxvalue = $checker['tax'];
						$unitval = $checker['unit'];
						// print_r($unitval);
						// die;
						if (empty($taxvalue) || empty($unitval)) {
							$this->session->set_flashdata(['msg' => 'Tax And Unit Are Mendatory', 'class' => 'danger']);
							redirect('invent/product');
						}
					}
				}
				// $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
				// $res = $this->user_model->getAry('product', '', '', '', $where);
				if (isset($data) && !empty($data)) {
					foreach ($data as $pro_info) {
						$where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'p_name' => $pro_info['p_name']];
						$res = $this->user_model->getRow('product', $where);
						if ($res) {
							$this->user_model->updateAry('product', $pro_info, $where);
						} else {
							$this->user_model->saveAry('product', $pro_info);
						}
					}
					if ($data) {

						$this->session->set_flashdata(['msg' => 'File Import Successfully.', 'class' => 'success']);
						redirect('invent/product');
					} else {
						$this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
						redirect('invent/product');
					}
				} else {
					$this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
					redirect('invent/product');
				}
			} else {

				$this->session->set_flashdata(['msg' => 'Only xls and xlsx File Valid.', 'class' => 'danger']);
				redirect('invent/product');
			}
			// return redirect('inventory', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
			redirect('invent/product');
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

	// public function importpro()
	// {
	// 	$file=$_FILES['upload_data'];
	// 	$this->load->library('excel');
	//     $data['page']='inventory_sep/product';
	//     if ($file) {
	//         $path = 'uploads/excel_file/';

	//         $config['upload_path'] = $path;
	//         $config['allowed_types'] = 'xlsx|xls|jpg|png';
	//         $config['remove_spaces'] = TRUE;
	//         $this->upload->initialize($config);
	//         $this->load->library('upload', $config);
	//         if (!$this->upload->do_upload('userfile')) {
	//             $data['error'] = array('error' => $this->upload->display_errors());
	//         } else {
	//             // $data['upload'] = array('upload_data' => $this->upload->data());
	// 			$this->load->view('inventory', $data);

	//         }

	//         if (!empty($data['upload_data']['file_name'])) {
	//             $import_xls_file = $data['upload_data']['file_name'];
	//         } else {
	//             $import_xls_file = 0;
	//         }
	//         $inputFileName = $path . $import_xls_file;

	//         try {
	//             $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	//             $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	//             $objPHPExcel = $objReader->load($inputFileName);
	//         } catch (Exception $e) {
	//             die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
	//                     . '": ' . $e->getMessage());
	//         }
	//         $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

	//         $arrayCount = count($allDataInSheet);
	//         $flag = 0;
	//         $createArray = array('First_Name', 'Last_Name', 'Email', 'DOB', 'Contact_NO');
	//         $makeArray = array('First_Name' => 'First_Name', 'Last_Name' => 'Last_Name', 'Email' => 'Email', 'DOB' => 'DOB', 'Contact_NO' => 'Contact_NO');
	//         $SheetDataKey = array();
	//         foreach ($allDataInSheet as $dataInSheet) {
	//             foreach ($dataInSheet as $key => $value) {
	//                 if (in_array(trim($value), $createArray)) {
	//                     $value = preg_replace('/\s+/', '', $value);
	//                     $SheetDataKey[trim($value)] = $key;
	//                 } else {

	//                 }
	//             }
	//         }
	//         $data = array_diff_key($makeArray, $SheetDataKey);

	//         if (empty($data)) {
	//             $flag = 1;
	//         }
	//         if ($flag == 1) {
	//             for ($i = 2; $i <= $arrayCount; $i++) {
	//                 $addresses = array();
	//                 $firstName = $SheetDataKey['First_Name'];
	//                 $lastName = $SheetDataKey['Last_Name'];
	//                 $email = $SheetDataKey['Email'];
	//                 $dob = $SheetDataKey['DOB'];
	//                 $contactNo = $SheetDataKey['Contact_NO'];
	//                 $firstName = filter_var(trim($allDataInSheet[$i][$firstName]), FILTER_SANITIZE_STRING);
	//                 $lastName = filter_var(trim($allDataInSheet[$i][$lastName]), FILTER_SANITIZE_STRING);
	//                 $email = filter_var(trim($allDataInSheet[$i][$email]), FILTER_SANITIZE_EMAIL);
	//                 $dob = filter_var(trim($allDataInSheet[$i][$dob]), FILTER_SANITIZE_STRING);
	//                 $contactNo = filter_var(trim($allDataInSheet[$i][$contactNo]), FILTER_SANITIZE_STRING);
	//                 $fetchData[] = array('first_name' => $firstName, 'last_name' => $lastName, 'email' => $email, 'dob' => $dob, 'contact_no' => $contactNo);
	//             }              
	//             $data['employeeInfo'] = $fetchData;
	//             $this->import->setBatchImport($fetchData);
	//             $this->import->importData();
	//         } else {
	//             echo "Please import correct file";
	//         }
	//     }
	//     $this->load->view('inventory', $data);

	// }


	function check_user_product_code()
	{
		if ($this->input->post('id')) {
			$id = $this->input->post('id');
			$p_code = $this->input->post('p_code');

			$user_id = $this->session->userdata('user_id');
			$comp_id = $this->session->userdata('comp_id');
			$res = $this->user_model->getRow('product', ['id!=' => $id, 'qrCode' => $p_code, 'user_id' => $user_id, 'comp_id' => $comp_id]);
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
			$user_id = $this->session->userdata('user_id');
			$comp_id = $this->session->userdata('comp_id');

			$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'qrCode' => $p_code];
			$return_value = $this->user_model->getRow('product', $where);
			// print_r($return_value);
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
			$user_id = $this->session->userdata('user_id');
			$comp_id = $this->session->userdata('comp_id');

			$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'qrCode' => $p_code];
			$return_value = $this->user_model->getRow('product', $where);
			// print_r($return_value);
			if ($return_value) {
				$this->form_validation->set_message('unique_productcodes', 'product Code  must be unique for every company');

				return false;
			} else {
				return true;
			}
		}
		return true;
	}
}
