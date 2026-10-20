<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ticket extends CI_Controller
{
    function __construct()
    {

        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        // $this->load->model('stock_model');

        if ($this->session->userdata('user_id') === null) {
            redirect('signin');
        }
    }

    public function index()
    {
        $data['ticket'] = $this->user_model->getAry('ticket', '', '', '', ['status' => 1]);
        $data['page'] = 'admin_details/ticket/index';
        $this->load->view('admin', $data);
    }

    public function save()
    {
        if ($_POST) {

            $data['ticket_name'] = $this->input->post('ticket_name');
            $data['timestamp'] = time();

            $result = $this->user_model->saveAry('ticket', $data);
            if ($result) {
                $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                redirect('ticket');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('ticket');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
            redirect('ticket');
        }
    }

    public function delete($id)
    {
        if (isset($id) && $id != '') {
            $data['status'] = 0;
            $where = ['ticket_id' => $id];
            $result = $this->user_model->updateAry('ticket', $data, $where);
            if ($result) {

                $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                redirect('ticket');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('ticket');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
            redirect('ticket');
        }
    }


    public function update()
    {
        $id = $this->input->post('ticket_id');


        if ($id) {
            $data['ticket_name'] = $this->input->post('ticket_name');
            $data['timestamp'] = time();

            $where = ['ticket_id' => $id];

            $result = $this->user_model->updateAry('ticket', $data, $where);
            if ($result) {
                $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                redirect('ticket');
            } else {

                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('ticket');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);

            redirect('ticket');
        }
    }



    public function edit($id)
    {
        $where = ['ticket_id' => $id];
        $return = $this->user_model->getRow('ticket', $where);

        if ($return) {
            $data['result'] = $return;
            $data['page'] = 'admin_details/ticket/edit';
            $this->load->view('admin', $data);
        } else {
            $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
            redirect('ticket');
        }
    }

    public function ticket_generate()
    {
        $data['ticket'] = $this->user_model->getAry('ticket_generate', '', '', '', ['user_id' => $this->session->userdata('user_id')]);
        $data['page'] = 'user/ticket';
        $this->load->view('admin', $data);
    }

    public function ticket_generate_all()
    {
        $role_id = $this->session->userdata('roles');
        if ($role_id == 1) {
            $data['ticket'] = $this->user_model->getAry('ticket_generate');
        }
        if ($role_id == 2) {
            $data['ticket'] = $this->user_model->getAry('ticket_generate', '', '', '', ['subadmin_id' => $this->session->userdata('user_id')]);
        }
        if ($role_id == 4) {
            $data['ticket'] = $this->user_model->getAry('ticket_generate', '', '', '', ['emp_id' => $this->session->userdata('user_id')]);
        }
        $data['page'] = 'admin_details/ticket/ticket_all';
        $this->load->view('admin', $data);
    }

    // public function ticket_generate_for_subadmin()
    // {
    //     $role_id = $this->session->userdata('roles');
    //     if ($role_id == 2) {
    //         $data['ticket'] = $this->user_model->getAry('ticket_generate', '', '', '', ['subadmin' => $this->session->userdata('user_id')]);
    //         $data['page'] = 'subadmin/ticket';
    //         $this->load->view('admin', $data);
    //     }
    // }

    // public function ticket_generate_for_emp()
    // {
    //     $role_id = $this->session->userdata('roles');
    //     if ($role_id == 4) {
    //         $data['ticket'] = $this->user_model->getAry('ticket_generate', '', '', '', ['emp_id' => $this->session->userdata('user_id')]);
    //         $data['page'] = 'employee/ticket';
    //         $this->load->view('admin', $data);
    //     }
    // }


    public function generate_ticket_for_user()
    {
        if ($_POST) {

            $data['ticket_id'] = $this->input->post('topic');
            $data['message'] = $this->input->post('message');
            $data['rendom_ticket_id'] = rand(100000, 999999);
            $user_id = $this->session->userdata('user_id');
            $file_name = md5($_FILES['image']['name']) . time() . ".png";
            if ($file_name != null) {
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/ticket/' . $file_name);
                $data['image'] = $file_name;
            }
            $data['created_at'] = time();
            $data['user_id'] = $user_id;
            $data['subadmin_id'] = get_title('subcat', ['userid' => $user_id], 'referral_id');
            $data['emp_id'] = get_title('subcat', ['userid' => $user_id, 'usertype!=' => ''], 'emp_id');


            $result = $this->user_model->saveAry('ticket_generate', $data);
            if ($result) {
                $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                redirect('ticket/ticket_generate');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('ticket/ticket_generate');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
            redirect('ticket/ticket_generate');
        }
    }


    public function delete_ticket_generate($id)
    {
        if (isset($id) && $id != '') {
            $where = ['id' => $id];
            $result = $this->user_model->deleteRow('ticket_generate', $where);
            if ($result) {
                $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                redirect('ticket/ticket_generate');
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                redirect('ticket/ticket_generate');
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
            redirect('ticket/ticket_generate');
        }
    }


    public function reply($id = null)
    {
        $data['ticket'] = $this->user_model->getAry('ticketreply', '', '', '', ['ticket_id' => $id]);
        $data['page'] = 'admin_details/ticket/reply';
        $data['ticket_id'] = $id;
        $this->load->view('admin', $data);
    }

    public function replySave()
    {
        $ticket_id = $this->input->post('ticket_id');
        if ($ticket_id) {
            $data['remark'] = $this->input->post('remark');
            $data['ticket_id'] = $this->input->post('ticket_id');
            $data['user_id'] = $this->session->userdata('user_id');
            $data['timestamp'] = time();
            $result = $this->user_model->saveAry('ticketreply', $data);
            $statusChanged['status'] = 1;
            $result = $this->user_model->updateAry('ticket_generate', $statusChanged, ['id' => $ticket_id]);
            if ($result) {
                $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                redirect('ticket/reply/' . $ticket_id);
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                $this->redirectToRoles();
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Ticket Not Found.', 'class' => 'danger']);
            $this->redirectToRoles();
        }
    }


    public  function redirectToRoles()
    {
        $roles = $this->session->userdata('roles');
        if ($roles == 3) {
            redirect('ticket/ticket_generate');
        } else {
            redirect('ticket/ticket_generate_all');
        }
    }

    public function close_ticket($id)
    {

        if ($id) {
            $data['status'] = 2;
            $where = ['id' => $id];
            $result = $this->user_model->updateAry('ticket_generate', $data, $where);
            if ($result) {
                $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                $this->redirectToRoles();
            } else {

                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                $this->redirectToRoles();
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Not Allow.', 'class' => 'danger']);
            $this->redirectToRoles();
        }
    }

    public function delete_ticket_reply($id)
    {
        if (isset($id) && $id != '') {
            $where = ['id' => $id];
            $result = $this->user_model->deleteRow('ticketreply', $where);
            if ($result) {
                $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                $this->redirectToRoles();
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                $this->redirectToRoles();
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
            $this->redirectToRoles();
        }
    }
}
