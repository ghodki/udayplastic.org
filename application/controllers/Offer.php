<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offer extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        // $this->load->library('admin');	
        if ($this->session->userdata('roles') === null) {
            redirect('welcome');
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
        $data['offer'] = $this->user_model->getAry('offer', '', '', '');
        $data['page'] = 'admin_details/offer/index';
        $this->load->view('admin', $data);
    }

    public function edit($id = null)
    {
        if (isset($id) && !empty($id)) {
            $where = ['offerId' => $id];
            $return = $this->user_model->getRow('offer', $where);
            if ($return) {
                $data['result'] = $return;
                $data['page'] = 'admin_details/offer/edit';
                $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                return redirect('offer');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Something Went Wrong', 'class' => 'danger']);
            return redirect('offer');
        }
    }

    public function update()
    {
        $id = $this->input->post('id');

        if (isset($_POST) && !empty($id)) {
            $where = ['offerId' => $id];
            $file_name = $_FILES['image']['name'];
            if ($file_name) {
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/offer/' . $file_name);
                $data['image'] = $file_name;
            }
            $data['heading'] = $this->input->post('heading');
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['url'] = $this->input->post('url');
            $ret = $this->user_model->updateAry('offer', $data, $where);
            if ($ret) {
                $this->session->set_flashdata(['msg' => 'Record Updated Successfully.', 'class' => 'success']);
                redirect('offer');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('offer');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
            redirect('offer');
        }
    }

    public function setNotification()
    {
        $id = $this->input->post('table_id');
        $status = $this->input->post('notification_status');
        $data['setNotification'] = $status;
        $result = $this->user_model->updateAry('offer', $data, ['offerId' => $id]);
        if (isset($result) && !empty($result)) {
            echo json_encode(['status' => true, 'message' => 'Notification Updated successfully.']);
            return;
        } else {
            echo json_encode(['status' => false, 'message' => 'There is some issue, Please try again.']);
            return;
        }
    }

    public function upload()
    {
        if (isset($_POST)) {
            $file_name = $_FILES['image']['name'];
            if ($file_name) {
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/offer/' . $file_name);
                $data['image'] = $file_name;
            }
            $data['heading'] = $this->input->post('heading');
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['url'] = $this->input->post('url');
            $ret = $this->user_model->saveAry('offer', $data);
            if ($ret) {
                $this->session->set_flashdata(['msg' => 'Record Save Successfully.', 'class' => 'success']);
                redirect('offer');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('offer');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'warning']);
            redirect('offer');
        }
    }
    public function changeofferPosition()
    {
        $position = $this->input->post('position');
        // print_r($_POST);
        // die;
        if ($position) {
            foreach ($position as $key => $setposition) {
                $key = $key + 1;
                $res = $this->user_model->getRow('offer_form', ['id' => $setposition]);
                if ($res) {
                    $data['position'] = $key;
                    $where = ['id' => $res->id];
                    $this->user_model->updateAry('offer_form', $data, $where);
                }
            }
            // echo json_encode('msg'=>'success','status'=>true);
        }
    }

    public function delete($id)
    {
        if (isset($id) && $id != '') {
            $where = ['offerId' => $id];
            $return = $this->user_model->deleteRow('offer', $where);
            if ($return) {
                $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                redirect('offer');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('offer');
            }
        } else {
            redirect('offer');
        }
    }
}
