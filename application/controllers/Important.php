<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Important extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		if ($this->session->userdata('user_id') === null) {
			redirect('index');
		}
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if ($this->session->userdata('user_id') === null) {
			$this->load->view('welcome_message');
		}
		$data['dates'] = $this->user_model->getResult('important_date');


		$data['page'] = 'admin_details/imp_date/index';
		$this->load->view('admin', $data);
	}
	public function save()
	{
		if ($_POST) {

			$data['date'] = $this->input->post('date');
			$data['imp_date'] = $this->input->post('text');


			$result = $this->user_model->saveAry('important_date', $data);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
				return redirect('important/index');
			} else {
				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				return redirect('important/index');
			}
		} else {
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			return redirect('important/index');
		}
	}
	public function update()
	{
		// 	$post = $this->input->post();
		// 	echo '<pre>';
		// print_r($post);
		// die;
		$id = $this->input->post('id');
		if ($id) {
			$data['date'] = $this->input->post('date');
			$data['imp_date'] = $this->input->post('text');

			$where = ['id' => $id];

		
			$result = $this->user_model->updateAry('important_date', $data, $where);
			if ($result) {
				$this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
				// return redirect('event/index');
			return redirect('important/index');

			} else {

				$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
				// return redirect('event/index');
			return redirect('important/index');

			}
		} else {
			// return redirect('event/index');
			return redirect('important/index');

		}
	}
	public function delete($id)
	{
		$where = ['id' => $id];

		$return = $this->user_model->deleteRow('important_date', $where);
		if ($return) {
			
			$this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'primary']);
			return redirect('important/index');

		} else {
			$data['page'] = 'admin_details/userlist';
			$this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
			return redirect('important/index');

		}
	}

	public function edit($id = null)
	{
		if (isset($id) && !empty($id)) {

			$where = ['id' => $id];
			$return = $this->user_model->getRow('important_date', $where);

			if ($return) {
				$data['result'] = $return;
				// $data['error'] = '';

				$data['page'] = 'admin_details/imp_date/edit';
				$this->load->view('admin', $data);
			} else {
				// $data['page'] = 'admin_details/application';
				$this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
				// return redirect('driver/index');
				return redirect('important/index');

			}
		} else {
			$this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
			// return redirect('driver/index');
			return redirect('important/index');

		}
	}

}
