<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
class Blog extends CI_Controller
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

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => 'blog Name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|xss_clean'
            ),
        );
        return $rules;
    }

    public function index()
    {
        $data['page'] = 'admin_details/blog/index';
        $error = '';
        if (isset($_POST) && !empty($_POST)) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {

                $config['upload_path'] = './uploads/blog/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|jpe';
                $config['overwrite'] = TRUE;
                $config['max_size'] = 0;
                $new_name = time() . '-' . $_FILES["image"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image')) {
                    $data['error'] = $this->upload->display_errors();
                } else {
                    $upload_data = $this->upload->data();
                    $saveData['image'] = $upload_data['file_name'];
                    $saveData['blog_name'] = $this->input->post('name');
                    $saveData['description'] = $this->input->post('description');
                    $saveData['created_at'] = date('d-m-Y H:i:s');
                    $result = $this->user_model->saveAry('blog', $saveData);
                    $this->session->set_flashdata(['msg' => 'blog Successfully Added !!!', 'class' => 'success']);
                    return redirect('blog');
                }
            }
        }
        $data['blog'] = $this->user_model->getResult('blog');
        $this->load->view('admin', $data);
    }


    public function edit($id = null)
    {
        $data['page'] = 'admin_details/blog/edit';
        $data['result'] = $this->user_model->getRow('blog', ['id' => $id]);
        $error = '';
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $data['error'] = validation_errors();
            } else {
                $config['upload_path'] = './uploads/blog/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|jpe';
                $config['overwrite'] = TRUE;
                $new_name = time() . '-' . $_FILES["image"]['name'];
                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                if ($_FILES["image"]['name']) {
                    if (!$this->upload->do_upload('image')) {
                        $data['error'] = $this->upload->display_errors();
                    } else {
                        $upload_data = $this->upload->data();
                        $saveData['image'] = $upload_data['file_name'];
                    }
                }
                $blog_id = $this->input->post('blog_id');
                $saveData['blog_name'] = $this->input->post('name');
                $saveData['description'] = $this->input->post('description');
                $saveData['updated_at'] = date('d-m-Y H:i:s');
                $this->user_model->updateAry('blog', $saveData, ['id' => $blog_id]);
                $this->session->set_flashdata(['msg' => 'blog Suuccessfully Updated !!!', 'class' => 'success']);
                return redirect('blog');
            }
        }
        $this->load->view('admin', $data);
    }



    public function delete($id = null)
    {
        $where = ['id' => $id];
        $result = $this->user_model->getRow('blog', ['id' => $id]);
        if ($result) {
            $path = "upload/blog/$result->image";
            unlink($path);
            $this->user_model->deleteRow('blog', $where);
            $this->session->set_flashdata(['msg' => 'blog Delete Successfully.', 'class' => 'success']);
            redirect('blog');
        } else {
            $this->session->set_flashdata(['msg' => 'Failed to delete.', 'class' => 'danger']);
            redirect('blog');
        }
    }
}
