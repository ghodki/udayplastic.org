<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class MiniSlider extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);
            $this->load->model('user_model');
            if ($this->session->userdata('roles') === null) {
                return redirect(base_url() . 'index');
            }
	}

	public function index()
	{
            $data['MiniSliders'] = $this->user_model->getResult('mini_slider');
            $data['page'] = 'admin_details/MiniSlider/index';
            $this->load->view('admin', $data);
	}

        public function Save() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_FILES['Image']['name'])) {
                    if ($_FILES['Image']['name']) {
                        $ImageFileName = time() . $_FILES['Image']['name'];
                        move_uploaded_file($_FILES['Image']['tmp_name'], 'uploads/MiniSlider/' . $ImageFileName);
                        $Image = $ImageFileName;
                    } else {
                        $Image = "";
                    }
                    $MiniSliderData = [
                        'Image' => $Image,
                        'Created' => date('d-m-Y h:i:s A')
                    ];

                    $Result = $this->user_model->saveAry('mini_slider', $MiniSliderData);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'MiniSlider');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'MiniSlider');
                    }
                }
            }
        }

        public function Edit($Id) 
        {
            if (!empty($Id)) {
                if ($this->db->where('Id', $Id)->get('mini_slider')->num_rows()) {
                    $Data['MemberId'] = $Id;
                    $Data['MiniSliderData'] = $this->db->where('Id', $Id)->get('mini_slider')->row();
                    $Data['page'] = 'admin_details/MiniSlider/Edit';
                    return $this->load->view('admin', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'MiniSlider');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'MiniSlider');
            }
        }
        
	public function Update()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                if (!empty($_FILES['Image']['name'])) {
                    if ($_FILES['Image']['name']) {
                        unlink('uploads/MiniSlider/' . $this->input->post('OldImage'));
                        $ImageFileName = time() . $_FILES['Image']['name'];
                        move_uploaded_file($_FILES['Image']['tmp_name'], 'uploads/MiniSlider/' . $ImageFileName);
                        $Image = $ImageFileName;
                    } else {
                        $Image = $this->input->post('OldImage');
                    }
                    $MiniSliderData = [
                        'Image' => $Image,
                        'Updated' => date('d-m-Y h:i:s A')
                    ];
                    
                    $UpdateWhere = ['Id' => $this->input->post('MiniSliderId')];
                    $Result = $this->user_model->updateAry('mini_slider', $MiniSliderData, $UpdateWhere);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'MiniSlider');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'MiniSlider/Edit/' . $Id);
                    }
                }
            }
        }
        
	public function Delete($Id)
	{
            if (isset($Id) && $Id != '') {
                $return = $this->user_model->deleteRow('mini_slider', ['Id' => $Id]);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'MiniSlider');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect(base_url() . 'MiniSlider');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect(base_url() . 'MiniSlider');
            }
	}
    }