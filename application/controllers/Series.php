<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Series extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);
            $this->load->model('user_model');

            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
	}

	public function index()
	{
            $data['page'] = 'series/index';
            return $this->load->view('inventory_sep/inventory', $data);
	}

	public function edit($id)
	{
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'series');
            }
		$data['page'] = 'series/edit';
		// $data['title'] = 'MIS | Master';
		// $data['menu'] = 'Series';

		$where = ['series_id' => $id];
		$result = $this->user_model->getRow('series', $where);
		$a = $result->start_point;
		// $string[strlen($a)-1]  ;
		$ser1 = explode('-', $a);
		$b = $ser1[count($ser1) - 1];
		$d = strlen($b) + 1;
		$data["prefix"]  = substr($a, 0, -$d);
		$data["startfrom"] = $b;

		//     $prefix="";
		//    $c = count($ser1);
		//    for($i=0;$i<=$c-2;$i++)
		//    {
		//        $prefix.=$ser1[$i]."-";
		//    }
		//    $c=2;       
		//print_r(   substr( $a , 0, -$d ));die;
		if (isset($result) && !empty($result)) {
			$data['result'] = $result;

			return $this->load->view('inventory_sep/inventory', $data);
		} else {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('series');
		}
	}

	public function save_series()
	{
		$data['page'] = 'series/index';
		$this->form_validation->set_rules('series_type', 'series Type', 'required');
		$this->form_validation->set_rules('start_point', 'Start Point', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('prefix', 'Prefix', 'xss_clean');
		$this->form_validation->set_rules('series_type', 'Series Type', 'required|xss_clean');
		$this->form_validation->set_rules('year', 'Financial year', 'xss_clean');

		$this->form_validation->set_rules('fincial_position', 'Financial year Position', 'required|xss_clean');
		$this->form_validation->set_rules('prefixposition', 'Prefix Position', 'required|xss_clean');
		$this->form_validation->set_rules('start_point_position', 'Start Point Position', 'required|xss_clean');




		// $this->form_validation->set_rules('start_point', 'Start Point', 'required|is_unique[series.start_point]');

		if ($this->form_validation->run() == false) {

			$this->data['form_validation'] = validation_errors();
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			return $this->load->view('inventory_sep/inventory', $data);
		}

		$insData['series_type'] = ucfirst($this->input->post('series_type'));
		$finencialposition = ucfirst($this->input->post('fincial_position'));
		$prefix_position = $this->input->post('prefixposition');
		$start_point_positon = $this->input->post('start_point_position');
		if ($prefix_position == $start_point_positon || $prefix_position == $finencialposition || $start_point_positon == $finencialposition) {
			$this->session->set_flashdata(['msg' => 'Position Can Not Be Same, Please Change Position.', 'class' => 'danger']);
			// $data['error'] = 'There is some issue, Please try again.';
			return $this->load->view('inventory_sep/inventory', $data);
		}

		$fyearid = $this->input->post('year');
		$where = ['id' => $fyearid];
		$year = $this->user_model->getRow('year', $where);
		if (!empty($year)) {
			$insData['financial_year'] = $year->id;
			$years = $year->year;
		}

		$prefix = ucfirst($this->input->post('prefix'));
		$start_point = $this->input->post('start_point');

		$insData['symbl'] = $this->input->post('symbl');
		$insData['start_point'] = $start_point;
			$insData['starter_point'] = "1".$start_point - 1;

		$insData['prefix'] = $prefix;
		$insData['financial_year_position'] = $finencialposition;
		$insData['prefix_position'] = $prefix_position;
		$insData['start_point_position'] = $start_point_positon;

		// $insData['start_point'] = $prefix . $symbl . $start_point . $symbl . $years;

		//print_r( $insData);die;
		$insData['user_id'] = $this->session->userdata('user_id');
		$insData['comp_id'] = $this->session->userdata('comp_id');
		if ($this->session->userdata('BranchRole')){
			$insData['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
		}

		$result = $this->user_model->saveAry('series', $insData);

		if ($result) {
			$this->session->set_flashdata(['msg' => 'Series added successfully.', 'class' => 'success']);
			// return redirect('series');
			redirect('series/series_list');
		} else {
			$this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
			$data['error'] = 'There is some issue, Please try again.';
			return $this->load->view('inventory_sep/inventory', $data);
		}
	}

	public function update()
	{
		$post = $this->input->post();
		// echo '<pre>';
		// print_r($post);
		// die;
		if (!$this->input->post()) {
			return redirect('series');
		}
		$series_id = $this->input->post('series_id');
		if (!$series_id) {
			return redirect('series');
		}
		$this->form_validation->set_rules('series_type', 'series Type', 'required');
		$this->form_validation->set_rules('start_point', 'Start Point', 'required|numeric|xss_clean');
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata(['msg' => validation_errors(), 'class' => 'danger']);
			return redirect('series/edit/' . $series_id);
		}

		$finencialposition = ucfirst($this->input->post('fincial_position'));
		$prefix_position = $this->input->post('prefixposition');
		$start_point_positon = $this->input->post('start_point_position');
		if ($prefix_position == $start_point_positon || $prefix_position == $finencialposition || $start_point_positon == $finencialposition) {
			$this->session->set_flashdata(['msg' => 'Position Can Not Be Same, Please Change Position.', 'class' => 'danger']);
			// $data['error'] = 'There is some issue, Please try again.';
			// return $this->load->view('inventory', $data);
			return redirect('series/edit/' . $series_id);
		}

		$fyearid = $this->input->post('year');
		$where = ['id' => $fyearid];
		$year = $this->user_model->getRow('year', $where);

		if (!empty($year)) {
			$insData['financial_year'] = $year->id;
			$years = $year->year;
		} else {
			$insData['financial_year'] = '';
		}

		$prefix = ucfirst($this->input->post('prefix'));
		$start_point = $this->input->post('start_point');
		$insData['symbl'] = $this->input->post('symbl');
			$insData['starter_point'] = "1".$start_point - 1;


		$insData['start_point'] = $start_point;
		$insData['prefix'] = $prefix;
		$insData['financial_year_position'] = $finencialposition;
		$insData['prefix_position'] = $prefix_position;
		$insData['start_point_position'] = $start_point_positon;

		$where = ['series_id' => $series_id];
		$result = $this->user_model->updateAry('series', $insData, $where);

		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'Series updated successfully.', 'class' => 'success']);
			return redirect('series/series_list');
		} else {
			$this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
			return redirect('series/edit/' . $series_id);
		}
	}

	public function delete($id)
	{
		if (!$id) {
			echo json_encode(['status' => false, 'msg' => 'Data not found.']);
			return;
		}
		$user_id = $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('comp_id');



		$where = ['series_id' => $id, 'comp_id' => $comp_id, 'user_id' => $user_id];
		if ($this->session->userdata('BranchRole')){
			$where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
		}
		$result = $this->user_model->deleteRow('series', $where);
		if (isset($result) && !empty($result)) {
			echo json_encode(['status' => true, 'msg' => 'Series deleted successfully.']);
			return;
		} else {
			echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
			return;
		}
	}

	public function series_list($start = 0)
	{

		$user_id = $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('comp_id');
		$where = ['comp_id' => $comp_id, 'user_id' => $user_id];
		if ($this->session->userdata('BranchRole')){
                                        $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
		}
		$counts = $this->user_model->getAry('series', '', '', '', $where);
		$count = count($counts);

		$config['base_url'] = base_url('series/viewseries');
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
		$data['page'] = 'series/view';


		$limit = 10000;
		$order = ['col' => 'series_id', 'order' => 'DESC'];
		$titleOrder = ['col' => 'id', 'order' => 'DESC'];
		$user_id = $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('comp_id');
		$where = ['user_id' => $user_id, 'comp_id' => $comp_id];
		if ($this->session->userdata('BranchRole')){
			$where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
		}
		$seriess = $this->user_model->getAry('series', $order, $start, $limit, $where);

		$seriestitle = $this->user_model->getAry('seriestitle', $titleOrder, $start, $limit);
		$data['seriess'] = $seriess;
		$data['seriestitle'] = $seriestitle;

		$data['start'] = $start;
		$data['end'] = (int)$start + count($seriess);
		$data['totalCount'] = $count;
		$this->load->view('inventory_sep/inventory', $data);
	}

	public function viewseries($id)
	{

		$data['page'] = 'series/notview';
		$data['title'] = 'MIS | Master';
		$data['menu'] = 'Series';
		$limit = 10;
		$start = 0;
		$order = ['col' => 'series_id', 'order' => 'DESC'];
		$user_id = $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('comp_id');
		$where = ['series_type' => $id, 'comp_id' => $comp_id, 'user_id' => $user_id];
		if ($this->session->userdata('BranchRole')){
			$where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
		}
		// $where = [];
		$seriess = $this->user_model->getAry('series', $order, $start, $limit, $where);
		// $seriesname = get_title('seriestitle', ['id' => $id], 'series');
		$data['seriess'] = $seriess;
		$data['seriesid'] = $id;
		// $data['seriesname'] = $seriesname;

		$count = count($seriess);
		$config['base_url'] = base_url('series/viewseries/');
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
		$data['start'] = $start;
		$data['end'] = (int)$start + count($seriess);
		$data['totalCount'] = $count;

		$this->load->view('inventory_sep/inventory', $data);
	}
	public function activeseries()
	{
		$id = $_POST['id'];
		$seriesid = $_POST['seriesid'];
		// update status       
		$start = "";
		$limit = "";
		$order = "";
		$user_id = $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('comp_id');


		$where = ['series_type' => $seriesid, 'status' => 2, 'comp_id' => $comp_id, 'user_id' => $user_id];
		if ($this->session->userdata('BranchRole')){
			$where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
		}
		// $where = []; //2 for active
		$seriess = $this->user_model->getAry('series', $order, $start, $limit, $where);
		if ($seriess) {

			foreach ($seriess as $row) {
				$data1['status'] = 1;
				$where = ['series_id' => $row->series_id];
				$result = $this->user_model->updateAry('series', $data1, $where);
			}
		}
		$data['status'] = 2;
		$where = ['series_id' => $id];
		$result = $this->user_model->updateAry('series', $data, $where);

		if (isset($result) && !empty($result)) {
			echo json_encode(['statusnew' => true, 'msg' => 'Series activated successfully.']);
			return;
		} else {
			echo json_encode(['statusnew' => false, 'msg' => 'There is some issue, Please try again.']);
			return;
		}
	}
}
