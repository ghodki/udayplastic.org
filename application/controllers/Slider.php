<?php
    date_default_timezone_set('Asia/Kolkata');
    
    class Slider extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['url', 'form']);
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
	}

	public function index()
	{
            $data['error'] = '';
            $data['slider'] = $this->db->order_by('position', 'ASC')->get('slider')->result();
            $data['page'] = 'admin_details/slider';
            
            $this->load->view('admin', $data);
	}

	public function edit($id)
	{
            if ($id) {
                $data['error'] = '';
                $data['result'] = $this->user_model->getRow('slider', ['id' => $id]);
                $data['page'] = 'admin_details/slider_edit';
                
                $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Something went wrong.', 'class' => 'danger']);
                redirect(base_url() . 'slider');
            }
	}

	public function upload()
	{
            if ($_FILES['image']['name']) {
                $data['Title'] = $this->input->post('Title');
                $data['url'] = $this->input->post('url');
                $file_name = time() . $_FILES['image']['name'];
                $file_type = $_FILES['image']['type'];
                if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg' || $file_type == 'image/webp') {
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/slider/' . $file_name);
                    $data['image'] = $file_name;
                    $data['created'] = date('d-m-Y h:i:s A');
                    
                    $ret = $this->user_model->saveAry('slider', $data);
                    if ($ret) {
                        $this->session->set_flashdata(['msg' => 'Record Save Successfully.', 'class' => 'success']);
                        redirect(base_url() . 'slider');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        redirect(base_url() . 'slider');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'jpg and png are allow', 'class' => 'danger']);
                    redirect(base_url() . 'slider');
                }
            } else {
                redirect(base_url() . 'slider');
            }
	}

	public function updateSlider()
	{
            $id = $this->input->post('id');
            $data['Title'] = $this->input->post('Title');
            $data['url'] = $this->input->post('url');
            if ($_FILES['image']['name']) {
                $file_name = time() . $_FILES['image']['name'];
                $file_type = $_FILES['image']['type'];
                if ($file_type == 'image/jpg' || $file_type == 'image/png' || $file_type == 'image/jpeg' || $file_type == 'image/webp') {
                    if (file_exists('uploads/slider/' . $this->input->post('oldimage'))) {
                        unlink('uploads/slider/' . $this->input->post('oldimage'));                            
                    }
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/slider/' . $file_name);
                    $data['image'] = $file_name;
                } else {
                    $this->session->set_flashdata(['msg' => 'jpg and png are allow', 'class' => 'danger']);
                    redirect(base_url() . 'slider/edit/' . $id);
                }
            } else {
                $data['image'] = $this->input->post('oldimage');
            }
            $data['updated'] = date('d-m-Y h:i:s A');
            
            $ret = $this->user_model->updateAry('slider', $data, ['id' => $id]);
            if ($ret) {
                $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                redirect(base_url() . 'slider');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect(base_url() . 'slider/edit/' . $id);
            }
	}

	public function delete($id)
	{
            if ($id) {
                $where = ['id' => $id];
                $slider = $this->user_model->getRow('slider', $where);
                if (file_exists('uploads/slider/' . $slider->image) && !empty($slider->image)) {
                    unlink('uploads/slider/' . $slider->image);                            
                }
                $return = $this->user_model->deleteRow('slider', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    redirect(base_url() . 'slider');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    redirect(base_url() . 'slider');
                }
            }
	}

	public function changeSliderPosition()
	{
            $position = $this->input->post('position');
            if ($position) {
                foreach ($position as $key => $setposition) {
                    $key = $key + 1;
                    $res = $this->user_model->getRow('slider', ['id' => $setposition]);
                    if ($res) {
                        $data['position'] = $key;
                        $data['updated'] = date('d-m-Y h:i:s A');
                        $where = ['id' => $res->id];
                        $this->user_model->updateAry('slider', $data, $where);
                    }
                }
            }
	}
    }