<?php

class Sms_template extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->model('user_model');
        if ($this->session->userdata('user_id') === null) {
            redirect('index');
        }
    }


    public function index()
    {
        $role_id = $this->session->userdata('roles');

        if ($role_id == 3) {
            $user_id = $this->session->userdata('user_id');

            $where = ['user_id' => $user_id];
            $data['sms'] = $this->user_model->getRow('sms_details', $where);
            $data['page'] = 'admin_details/sms/main';
            $this->load->view('admin', $data);
        } else {
            redirect('userlist');
        }
    }

    public function save()
    {

        $id = $this->input->post('id');
        if (isset($id) && $id != '') {
            $data['sms'] = $this->input->post('sms');
            $data['user_id'] = $id;
            $data['role_assign_id'] = $this->session->userdata('roles');
            $ret = $this->user_model->saveAry('sms_history', $data);
            if ($ret) {
                $where = ['user_id' => $id];
                $sms_details = $this->user_model->getRow('sms_details', $where);
                if ($sms_details) {
                    $sms_qty = $this->input->post('sms');
                    $total_sms = $sms_details->total_sms;
                    $remains = $sms_details->remains_sms;
                    $send_sms = $sms_details->send_sms;
                    $user_id = $id;
                    $sms_de['remains_sms'] = intval($sms_qty) + intval($remains);
                    $sms_de['total_sms'] = intval($total_sms) + intval($sms_qty);
                    $sms_de['send_sms'] = $send_sms;
                    $sms_de['user_id'] = $user_id;
                    $where = ['user_id' => $user_id];

                    $result = $this->user_model->updateAry('sms_details', $sms_de, $where);

                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Successfully Add Sms to the user', 'class' => 'success']);
                        redirect('userlist');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed To Store Data', 'class' => 'danger']);
                        redirect('userlist');
                    }
                } else {
                    $sms_add = $this->input->post('sms');
                    $id = $this->input->post('id');
                    $where = ['id' => $id];
                    $sms_de['remains_sms'] = $sms_add;
                    $sms_de['total_sms'] = $sms_add;
                    $sms_de['send_sms'] = 0;
                    $sms_de['user_id'] = $id;

                    $ret = $this->user_model->saveAry('sms_details', $sms_de);
                    if ($ret) {
                        $this->session->set_flashdata(['msg' => 'Successfully Add Sms to the user', 'class' => 'success']);
                        redirect('userlist');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed To Store Data', 'class' => 'danger']);
                        redirect('userlist');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Did Not Find User', 'class' => 'danger']);

                redirect('userlist');
            }
        } else {
            redirect('userlist');
        }
    }

    public function sendsms()
    {

        // Your PHP installation needs cUrl support, which not all PHP installations
        // include by default.
        // To run under docker:
        // docker run -v $PWD:/code php:7.3.2-alpine php /code/code_sample.php

        $username = 'your_username';
        $password = 'your_password';
        $messages = array(
            array('to' => '+1233454567', 'body' => 'Hello World!'),
            array('to' => '+1233454568', 'body' => 'Hello World!')
        );

        $result = send_message(json_encode($messages), 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30', $username, $password);

        if ($result['http_status'] != 201) {
            print "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status " . $result['http_status'] . "; Response was " . $result['server_response']);
        } else {
            print "Response " . $result['server_response'];
            // Use json_decode($result['server_response']) to work with the response further
        }

        function send_message($post_body, $url, $username, $password)
        {
            $ch = curl_init();
            $headers = array(
                'Content-Type:application/json',
                'Authorization:Basic ' . base64_encode("$username:$password")
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
            // Allow cUrl functions 20 seconds to execute
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            // Wait 10 seconds while trying to connect
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $output = array();
            $output['server_response'] = curl_exec($ch);
            $curl_info = curl_getinfo($ch);
            $output['http_status'] = $curl_info['http_code'];
            $output['error'] = curl_error($ch);
            curl_close($ch);
            return $output;
        }
    }

    public function delete($id)
    {
        $where = ['id' => $id];

        $return = $this->user_model->deleteRow('slider', $where);
        if ($return) {
            $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
            redirect('slider');
        } else {
            $data['page'] = 'admin_details/userlist';
            $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'success']);
            redirect('slider');
        }
    }
}
