<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Seriestitle extends CI_Controller
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
            $data['page'] = 'seriestitle/index';
            $data['seriestitle'] = $this->user_model->getAry('seriestitle', '', '', '', '');

            if (!$this->input->post()) {
                return $this->load->view('inventory_sep/inventory', $data);
            }
            $this->form_validation->set_rules('seriestitle_type', 'seriestitle Type', 'required');
            $this->form_validation->set_rules('start_point', 'Start Point', 'required|is_unique[seriestitle.start_point]');
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
                return $this->load->view('inventory_sep/inventory', $data);
            }
            $insData['seriestitle_type'] = $seriestitle_id = ucfirst($this->input->post('seriestitle_type'));
            $fyearid = $this->input->post('year');
            $where = ['id' => $fyearid];
            $year = $this->user_model->getRow('finenncial_year', $where);
            if ($year) {
                $insData['financial_year'] = $year->id;
                $prefix = ucfirst($this->input->post('prefix'));
                $start_point = $this->input->post('start_point');
                $insData['start_point'] = $prefix . "-" . $start_point;
                $insData['user_id'] = $this->session->userdata('user_id');
                $insData['comp_id'] = $this->session->userdata('comp_id');
                if ($this->session->userdata('BranchRole')) {
                    $insData['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                }
                $result = $this->user_model->saveAry('seriestitle', $insData);
            }
            if (isset($result) && !empty($result)) {
                $this->session->set_flashdata(['msg' => 'Seriestitle added successfully.', 'class' => 'success']);
                redirect('seriestitle/list');
            } else {
                $this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
                $data['error'] = 'There is some issue, Please try again.';
                return $this->load->view('inventory_sep/inventory', $data);
            }
	}
        
	public function save()
	{
		$role_data = $this->session->userdata('roles');
		$data['page'] = 'seriestitle/index';
		// $data['title'] = 'MIS | Statetails';
		$data['menu'] = 'State';
		// $data['state'] = $this->user_model->getAry('state_list', ['col' => 'state', 'order' => 'ASC']);
		// if (!$this->input->post()) {
		// 	return $this->load->view('admin', $data);
		// }
		// $fyear = $this->session->userdata('Finacialyear');
		if (isset($role_data) == 1 && isset($_POST)) {
			// $result1 = $this->db->get_where('role_has_permissions', ['role_id' => $role_data])->row();
			// $newdata = $result1->permissions_id;
			// $permission_data = explode(",", $newdata);
			// print_r($permission_data);
			// die;
			// if (empty($permission_data)) {
			// 	return redirect('dashboard');
			// }

			$this->form_validation->set_rules('name', 'Name', 'required|is_unique[seriestitle.series]');
			// $this->form_validation->set_rules('state', 'State', 'required');
			if ($this->form_validation->run() == false) {
				$data['error'] = validation_errors();
				return $this->load->view('inventory_sep/inventory', $data);
			}

			$insData['series'] = ucfirst($this->input->post('name'));
			// $insData['state_id'] = ucfirst($this->input->post('state'));
			$result = $this->user_model->saveAry('seriestitle', $insData);
			if (isset($result) && !empty($result)) {
				$this->session->set_flashdata(['msg' => 'Series Tittle added successfully.', 'class' => 'success']);
				// return redirect('seriestitle');
				return redirect('seriestitle');
			} else {
				$data['error'] = 'There is some issue, Please try again.';
				return $this->load->view('inventory_sep/inventory', $data);
			}
		} else {
			$data['error'] = 'There is some issue, Please try again.';
			return $this->load->view('inventory_sep/inventory', $data);
		}
	}

	public function edit($id)
	{
		if (!$id) {
			$this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
			return redirect('seriestitle');
		}
		// $data['page'] = 'seriestitle/edit';
		$data['page'] = 'seriestitle/edit';

		// $data['title'] = 'MIS | Master';
		// $data['menu'] = 'Seriestitle';

		$where = ['id' => $id];
		$result = $this->user_model->getRow('seriestitle', $where);
		// $a = $result->start_point;
		// $string[strlen($a)-1]  ;
		// $ser1 = explode('-', $a);
		// $b = $ser1[count($ser1) - 1];
		// $d = strlen($b) + 1;
		// $data["prefix"]  = substr($a, 0, -$d);
		// $data["startfrom"] = $b;

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
			return redirect('seriestitle');
		}
	}

	public function add()
	{

		$data['page'] = 'seriestitle/add';

		return $this->load->view('inventory_sep/inventory', $data);
	}
	public function update()
	{
		if (!$this->input->post()) {
			return redirect('seriestitle');
		}
		$seriestitle_id = $this->input->post('id');
		$data['series'] = $this->input->post('name');

		if (!$seriestitle_id) {
			return redirect('seriestitle');
		}
		// $this->form_validation->set_rules('seriestitle_type', 'seriestitle Type', 'required');
		// $this->form_validation->set_rules('start_point', 'Start Point', 'required|callback_is_exclude_unique[' . $seriestitle_id . ']');
		// if ($this->form_validation->run() == false) {
		// 	$this->session->set_flashdata(['msg' => validation_errors(), 'class' => 'danger']);
		// 	return redirect('edit-seriestitle/' . $seriestitle_id);
		// }

		// $fyearid = $this->input->post('year');
		// $where = ['id' => $fyearid];
		// $year = $this->model->getRow('financialyear', $where);
		// if ($year) {
		// 	$data['financial_year'] = $year->id;
		// 	$prefix = ucfirst($this->input->post('prefix'));
		// $start_point = $this->input->post('start_point');
		// $data['seriestitle_type'] = ucfirst($this->input->post('seriestitle_type'));
		// $data['start_point'] = $prefix . "-" . $start_point;

		$where = ['id' => $seriestitle_id];
		$result = $this->user_model->updateAry('seriestitle', $data, $where);
		if (isset($result) && !empty($result)) {
			$this->session->set_flashdata(['msg' => 'Seriestitle updated successfully.', 'class' => 'success']);
			return redirect('seriestitle');
		} else {
			$this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
			return redirect('seriestitle/edit/' . $seriestitle_id);
		}
	}

	public function Deleteseriestitle($id)
	{
            if (!$id) {
                $this->session->set_flashdata(['msg' => 'Id not found.', 'class' => 'danger']);
                return redirect(base_url() . 'seriestitle');
            }
            $where = ['id' => $id];
            $result = $this->user_model->deleteRow('seriestitle', $where);
            if (isset($result) && !empty($result)) {
                $this->session->set_flashdata(['msg' => 'Seriestitle deleted successfully.', 'class' => 'danger']);
                return redirect(base_url() . 'seriestitle');
            } else {
                $this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
                return redirect(base_url() . 'seriestitle');
            }
	}

	public function list($start = 0)
	{


		$count = $this->user_model->RowCount('seriestitle');
		$config['base_url'] = base_url('index.php/seriestitle/viewseriestitle');
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
		$data['page'] = 'inventory_sep/seriestitle/view';

		$data['title'] = 'MIS | Master';
		$data['menu'] = 'Seriestitle';
		$limit = 10;
		$order = ['order' => 'DESC'];
		$user_id = $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('comp_id');
		$where = ['user_id' => $user_id, 'comp_id' => $comp_id];
		if ($this->session->userdata('BranchRole')){
			$where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
		}
		$seriestitles = $this->user_model->getAry('seriestitle', $order, $start, $limit, $where);
		$seriestitle = $this->user_model->getAry('seriestitle', $order, $start, $limit);
		$data['seriestitles'] = $seriestitles;
		$data['seriestitle'] = $seriestitle;

		$data['start'] = $start;
		$data['end'] = (int)$start + count($seriestitles);
		$data['totalCount'] = $count;
		$this->load->view('inventory', $data);
	}

	public function viewseriestitle($id)
	{

		$data['page'] = 'inventory_sep/seriestitle/notview';
		$data['title'] = 'MIS | Master';
		$data['menu'] = 'Seriestitle';
		$limit = 10;
		$start = 0;
		$order = ['order' => 'DESC'];
		$where = ['seriestitle_type' => $id];
		$seriestitles = $this->user_model->getAry('seriestitle', $order, $start, $limit, $where);
		// $seriestitlename = get_title('seriestitle', ['id' => $id], 'seriestitle');
		$data['seriestitles'] = $seriestitles;
		$data['seriestitleid'] = $id;
		// $data['seriestitlename'] = $seriestitlename;

		$count = count($seriestitles);
		$config['base_url'] = base_url('index.php/seriestitle/viewseriestitle/');
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
		$data['end'] = (int)$start + count($seriestitles);
		$data['totalCount'] = $count;

		$this->load->view('inventory', $data);
	}
	public function activeseriestitle()
	{
		$id = $_POST['id'];
		$seriestitleid = $_POST['seriestitleid'];
		// update status       
		$start = "";
		$limit = "";
		$order = "";
		$where = ['seriestitle_type' => $seriestitleid, 'status' => 2]; //2 for active
		$seriestitles = $this->user_model->getAry('seriestitle', $order, $start, $limit, $where);
		if ($seriestitles) {

			foreach ($seriestitles as $row) {
				$data1['status'] = 1;
				$where = ['seriestitle_id' => $row->seriestitle_id];
				$result = $this->user_model->updateAry('seriestitle', $data1, $where);
			}
		}
		$data['status'] = 2;
		$where = ['seriestitle_id' => $id];
		$result = $this->user_model->updateAry('seriestitle', $data, $where);

		if (isset($result) && !empty($result)) {
			echo json_encode(['statusnew' => true, 'msg' => 'Seriestitle activated successfully.']);
			return;
		} else {
			echo json_encode(['statusnew' => false, 'msg' => 'There is some issue, Please try again.']);
			return;
		}
	}
}
