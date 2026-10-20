<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Profile extends CI_Controller
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
        
	protected function rlues()
	{
            $rules = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|required|callback_valid_mail'
                ),
                array(
                    'field' => 'number',
                    'label' => 'number',
                    'rules' => 'trim|required|min_length[10]|max_length[10]|numeric|callback_valid_number'
                )
            );
            return $rules;
	}


	public function index()
	{
            $data['userinfo'] = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
            $data['page'] = 'admin_details/profile/index';
            return $this->load->view('admin', $data);
	}
        
        public function UpdateProfile()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $rules = $this->rlues();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $data['userinfo'] = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
                    $data['error'] = validation_errors();
                    $data['page'] = 'admin_details/profile/index';
                    return $this->load->view('admin', $data);
                } else {
                    $beta['name'] = $this->input->post('name');
                    $beta['email'] = $this->input->post('email');
                    $beta['number'] = $this->input->post('number');
                    $beta['gst'] = $this->input->post('gst');
                    $beta['address'] = $this->input->post('address');
                    if ($_FILES['image']['name']) {
                        $file_name = md5(time().$_FILES['image']['name']).".png";
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/profile/' . $file_name);
                        $beta['image'] = $file_name;
                    }
                    $result = $this->user_model->updateAry('users', $beta, ['id' => $this->session->userdata('user_id')]);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Profile Updated Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Profile');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        return redirect(base_url() . 'Profile');
                    }
                }
            }
        }

	public function ChangePassword()
	{
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->form_validation->set_rules('actual_pass', 'Password', 'required');
                $this->form_validation->set_rules('current_pass', 'Current Password', 'required|matches[actual_pass]');
                $this->form_validation->set_rules('new_pass', 'New Password', 'required');
                $this->form_validation->set_rules('confirm_pass', 'Confirm Password', 'required|matches[new_pass]');
                if ($this->form_validation->run() == FALSE) {
                    $data['userinfo'] = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
                    $data['error'] = validation_errors();
                    $data['page'] = 'admin_details/profile/index';
                    return $this->load->view('admin', $data);
                } else {
                    $beta['password'] = $this->input->post('confirm_pass');
                    $result = $this->user_model->updateAry('users', $beta, ['id' => $this->session->userdata('user_id')]);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Password Updated Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Profile');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        return redirect(base_url() . 'Profile');
                    }
                }
            }
	}

	public function valid_mail()
	{
            $where_not = ['id !=' => $this->session->userdata('user_id')];
            $where = ['email' => $this->input->post('email')];
            if ($this->user_model->check_unique('users', $where_not, $where)) {
                return TRUE;
            }
            $this->form_validation->set_message('valid_mail', 'Email Should be Unique');
            return FALSE;
	}

	public function valid_number()
	{
            $where_not = ['id !=' => $this->session->userdata('user_id')];
            $where = ['number' => $this->input->post('number')];
            if ($this->user_model->check_unique('users', $where_not, $where)) {
                return TRUE;
            }
            $this->form_validation->set_message('valid_number', 'Phone Number Should be Unique');
            return FALSE;
	}
    }