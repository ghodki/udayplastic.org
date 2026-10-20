<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subadmin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        if ($this->session->userdata('roles') === null) {
            // redirect('/');
            redirect('dashboard');
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
        if ($this->session->userdata('roles') == 2) {
            $user_id = $this->session->userdata('user_id');
            $data['bank_details'] = $this->user_model->getAryss('bank_details', '', '', '', ['user_id' => $user_id]);
            $data['page'] = 'subadmin/bank/index';
            $this->load->view('admin', $data);
        } else {
            redirect('dashboard');
        }
    }
    public function save()
    {
        if ($_POST) {
            // echo '<pre>';
            // print_r($_POST);
            // die;
            $this->form_validation->set_rules('name', 'Holder Name', 'required|trim');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
            $this->form_validation->set_rules('account_number', 'Account Number', 'required|numeric|trim|regex_match[/^[0-9,]+$/]');
            $this->form_validation->set_rules('ifsc', 'IFSC', 'required|trim');
            $this->form_validation->set_rules('branch', 'Branch', 'required|trim');

            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
                $data['page'] = 'subadmin/bank/index';
                $this->load->view('admin', $data);
            } else {
                $data['name'] = $this->input->post('name');
                // $data['location'] = $this->input->post('bank_details_name');
                $data['branch'] = $this->input->post('branch');
                $data['ifsc'] = $this->input->post('ifsc');
                $data['account_number'] = $this->input->post('account_number');
                $data['bank_name'] = $this->input->post('bank_name');
                $data['user_id'] = $this->session->userdata('user_id');


                $result = $this->user_model->saveAry('bank_details', $data);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Bank Details Added.', 'class' => 'success']);
                    return redirect('subadmin');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('subadmin');
                }
            }
        } else {
            $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
            return redirect('subadmin');
        }
    }

    public function delete($id = null)
    {
        if ($this->session->userdata('roles') == 2) {

            if (isset($id) && $id != '') {
                $where = ['id' => $id];

                $return = $this->user_model->deleteRow('bank_details', $where);
                if ($return) {

                    $this->session->set_flashdata(['msg' => 'Bank Details Deleted', 'class' => 'success']);
                    return redirect('subadmin');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('subadmin');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect('subadmin');
            }
        } else {
            redirect('dashboard');
        }
    }


    public function edit($id)
    {
        if ($this->session->userdata('roles') == 2) {

            if (isset($id) && $id != '') {

                $where = ['id' => $id];
                $return = $this->user_model->getRow('bank_details', $where);

                if ($return) {
                    $data['bank_details'] = $return;
                    $data['page'] = 'subadmin/bank/edit';
                    $this->load->view('admin', $data);
                } else {
                    // $data['page'] = 'admin_details/userlist';
                    $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                    return redirect('subadmin');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect('subadmin');
            }
        } else {
            redirect('dashboard');
        }
    }

    public function update()
    {
        // 	$post = $this->input->post();
        // 	echo '<pre>';
        // print_r($post);
        // die;
        if ($this->session->userdata('roles') == 2) {

            $id = $this->input->post('id');
            if (!empty($id)) {
                $this->form_validation->set_rules('name', 'Holder Name', 'required|trim');
                $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
                $this->form_validation->set_rules('account_number', 'Account Number', 'required|numeric|trim|regex_match[/^[0-9,]+$/]');
                $this->form_validation->set_rules('ifsc', 'IFSC', 'required|trim');
                $this->form_validation->set_rules('branch', 'Branch', 'required|trim');
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error text-danger bg-danger">', '</div>');
                    $data['page'] = 'subadmin/bank/edit/' . $id;
                    $this->load->view('admin', $data);
                }
                $datas['name'] = $this->input->post('name');
                // $data['location'] = $this->input->post('bank_details_name');
                $datas['branch'] = $this->input->post('branch');
                $datas['ifsc'] = $this->input->post('ifsc');
                $datas['account_number'] = $this->input->post('account_number');
                $datas['bank_name'] = $this->input->post('bank_name');


                $where = ['id' => $id];


                $result = $this->user_model->updateAry('bank_details', $datas, $where);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect('subadmin');
                } else {

                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('subadmin');
                }
            } else {
                return redirect('subadmin');
            }
        } else {
            redirect('dashboard');
        }
    }
}
