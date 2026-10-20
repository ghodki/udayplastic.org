<?php
defined('BASEPATH') or exit('No direct script access allowed');

class General_setting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        // $this->load->library('custom');
        $this->load->helper('action_helper');
        if ($this->session->userdata('user_id') === null) {
            redirect('index');
        }
    }


    public function index()
    {

        if (!$this->user_model->check_permission('general_setting')) {
            $data['page'] = 'admin_details/error/permission_denied';
            // redirect(base_url() . 'index.php/dashboard');
        } else {
            $data['general_setting'] = $this->user_model->getResult('file_sharing');
            $data['page'] = 'admin_details/general_setting/index';
        }
        $this->load->view('admin', $data);
    }

    public function update()
    {

        // $this->load->library('form_validation');
        $this->form_validation->set_rules('footer', 'Footer', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim');
        $this->form_validation->set_rules('phone', 'Phone Number', 'trim');
        $this->form_validation->set_rules('sname', 'Site Title', 'trim');

        $this->form_validation->set_rules('author_name', 'Author Name', 'trim');
        $this->form_validation->set_rules('keywords', 'keywords', 'trim');
        $this->form_validation->set_rules('description', 'Description', 'trim');



        if ($this->form_validation->run() == false) {
            $response = array(
                'status' => 'error',
                'message' => validation_errors()
            );
        } else {
            $author_name = array(
                'value' => $this->input->post('author_name'),
            );
            $keywords = array(
                'value' => $this->input->post('keywords'),
            );
            $description = array(
                'value' => $this->input->post('description'),
            );

            $address = array(
                'value' => $this->input->post('address'),
            );
            $email = array(
                'value' => $this->input->post('email'),
            );

            $footer = array(
                'value' => $this->input->post('footer'),
            );
            $phone = array(
                'value' => $this->input->post('phone'),
            );
            $title = array(
                'value' => $this->input->post('sname'),
            );

            $this->user_model->updateAry('general_settings', $email, ['general_settings_id' => 2]);
            $this->user_model->updateAry('general_settings', $title, ['general_settings_id' => 3]);
            $this->user_model->updateAry('general_settings', $address, ['general_settings_id' => 4]);
            $this->user_model->updateAry('general_settings', $phone, ['general_settings_id' => 5]);
            $this->user_model->updateAry('general_settings', $footer, ['general_settings_id' => 42]);
            $this->user_model->updateAry('general_settings', $author_name, ['general_settings_id' => 26]);
            $this->user_model->updateAry('general_settings', $keywords, ['general_settings_id' => 25]);
            $this->user_model->updateAry('general_settings', $description, ['general_settings_id' => 24]);

            $response = array(
                'status' => 'success',
                'message' => "General Setting Updated"
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function smsconfig()
    {

        // $this->load->library('form_validation');
        $this->form_validation->set_rules('sms_api_key', 'Api Key', 'trim|required');
        $this->form_validation->set_rules('sms_temp_key', 'Template ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            $response = array(
                'status' => 'error',
                'message' => validation_errors()
            );
        } else {
            $template_id = array(
                'value' => $this->input->post('sms_temp_key'),
            );
            $sms_id = array(
                'value' => $this->input->post('sms_api_key'),
            );
            $temp_api = $this->user_model->getRow('general_settings', ['general_settings_id' => 89]);
            $sms_api = $this->user_model->getRow('general_settings', ['general_settings_id' => 88]);

            if ($sms_api || $temp_api) {
                $this->user_model->updateAry('general_settings', $template_id, ['general_settings_id' => 89]);
                $this->user_model->updateAry('general_settings', $sms_id, ['general_settings_id' => 88]);
                $response = array(
                    'status' => 'success',
                    'message' => "SMS API UPDATED"
                );
            } else {

                $response = array(
                    'status' => 'error',
                    'message' => "Api Key and Template Key Is Required"
                );
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }



    public function socialmedia()
    {

        // $this->load->library('form_validation');
        $this->form_validation->set_rules('facebook', 'facebook', 'trim');
        $this->form_validation->set_rules('twitter', 'twitter ID', 'trim');
        $this->form_validation->set_rules('linkedin', 'linkedin ID', 'trim');
        $this->form_validation->set_rules('youtube', 'youtube ID', 'trim');
        $this->form_validation->set_rules('google', 'google ID', 'trim');


        if ($this->form_validation->run() == false) {
            $response = array(
                'status' => 'error',
                'message' => validation_errors()
            );
        } else {
            $facebook = array(
                'value' => $this->input->post('facebook'),
            );
            $twitter = array(
                'value' => $this->input->post('twitter'),
            );
            $linkedin = array(
                'value' => $this->input->post('linkedin'),
            );
            $youtube = array(
                'value' => $this->input->post('youtube'),
            );
            $google = array(
                'value' => $this->input->post('google'),
            );
            $instagram = array(
                'value' => $this->input->post('instagram'),
            );


            $this->user_model->updateAry('general_settings', $google, ['general_settings_id' => 90]);
            $this->user_model->updateAry('general_settings', $facebook, ['general_settings_id' => 91]);
            $this->user_model->updateAry('general_settings', $instagram, ['general_settings_id' => 92]);
            $this->user_model->updateAry('general_settings', $twitter, ['general_settings_id' => 93]);
            $this->user_model->updateAry('general_settings', $youtube, ['general_settings_id' => 94]);
            $this->user_model->updateAry('general_settings', $linkedin, ['general_settings_id' => 95]);


            $response = array(
                'status' => 'success',
                'message' => "Social Media Updated"
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
