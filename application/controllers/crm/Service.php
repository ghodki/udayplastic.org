<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->load->helper('action_helper');
        if ($this->session->userdata('roles') === null) {
            redirect('welcome');
        }
    }

    public function index()
    {
        $this->db->select('*');
        $this->db->from('crm_service');
        $data['crm_service'] = $this->db->get()->result();
        $data['page'] = 'service/index';
        $this->load->view('crm/new_main', $data);
    }

    public function PageShow($pagename)
    {
        $this->load->view('crm/service/' . $pagename);
    }

    public function active_user()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_service');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 1);
        $data['serviceData'] = $this->db->get()->result();
        $data['page'] = 'service/active';
        $this->load->view('crm/new_main', $data);
    }

    public function deactiveuser()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_service');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 0);
        $data['serviceData'] = $this->db->get()->result();
        // $data['page'] = 'service/deactivate';
        $this->load->view('crm/service/deactivate', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'service_name',
                'label' => 'service_name',
                'rules' => 'trim|required|xss_clean|callback_unique_service'
            ),
            array(
                'field' => 'short_name',
                'label' => 'short_name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            ),
        );
        return $rules;
    }

    protected function update_rules()
    {
        $rules = array(
            array(
                'field' => 'service_name',
                'label' => 'service_name',
                'rules' => 'trim|required|xss_clean|callback_unique_service_update'
            ),
            array(
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'id',
                'label' => 'id',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'short_name',
                'label' => 'short_name',
                'rules' => 'trim|required|xss_clean'
            ),

        );
        return $rules;
    }


    protected function change_status_rules()
    {
        $rules = array(
            array(
                'field' => 'service_id',
                'label' => 'service',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'status',
                'label' => 'Status',
                'rules' => 'trim|required|xss_clean'
            ),




        );
        return $rules;
    }

    public function save()
    {

        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['service_name'] = $this->input->post('service_name');
                $data['short_name'] = $this->input->post('short_name');
                $data['status'] = $this->input->post('status');
                $data['created_at'] = time();
                $result = $this->user_model->saveAry('crm_service', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Service Created</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>Service Could not be Created</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function edit_service($id)
    {
        $data['result'] = $this->user_model->getRow('crm_service', ['id' => $id]);
        // $data['page'] = 'service/edit';

        $this->load->view('crm/service/edit', $data);
    }

    public function view_service($id)
    {
        if ($id) {
            $data['result'] = $this->user_model->getRow('crm_service', ['id' => $id]);
            $this->load->view('crm/service/view', $data);
        }
    }

    public function update_service()
    {

        if ($_POST) {
            $rules = $this->update_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $id = $this->input->post('id');
                $data['service_name'] = $this->input->post('service_name');
                $data['short_name'] = $this->input->post('short_name');
                $data['status'] = $this->input->post('status');

                $result = $this->user_model->updateAry('crm_service', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>service Updated</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>Something Went Wrong</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function changeStatus()
    {

        if ($_POST) {
            $rules = $this->change_status_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'status' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['status'] = $this->input->post('status');
                $id = $this->input->post('service_id');
                $result = $this->user_model->updateAry('crm_service', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Service Updated</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>Something Went Wrong</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function unique_service()
    {
        $service_name = $this->input->post('service_name');
        $where = ['service_name' => $service_name];
        $return_value = $this->user_model->getRow('crm_service', $where);
        if ($return_value) {
            $this->form_validation->set_message('unique_service', 'Service should be Unique');
            return false;
        }
        return true;
    }

    public function unique_service_update()
    {
        $service_name = $this->input->post('service_name');
        $id = $this->input->post('id');
        $where = ['service_name' => $service_name, 'id!=' => $id];
        $return_value = $this->user_model->getRow('crm_service', $where);
        // print_r($return_value);
        if ($return_value) {
            $this->form_validation->set_message('unique_service_update', 'Service should be Unique');
            return false;
        }
        return true;
    }

    public function subservices()
    {
        $id = $this->input->post('service_id');
        $result = $this->user_model->getAry('crm_servicesubgroup', '', '', '', ['service_id' => $id]);
        $html = '';
        // select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
        foreach ($result as $key => $dist_val) {
            $html .= '<div class="col-md-2 col-sm-2 col-lg-2">
            <div class="form-group">
                <label for="sub_service[]">' . $dist_val->sub_service_name . '<span class="text-danger"></span></label>
                <input name="sub_service[]" type="checkbox" value="' . $dist_val->id . '" class="form-check-input form-control">
            </div>

        </div>';
        }
        $response = array(
            'status' => 'success',
            'data' => $html
        );
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function chekedSubservice()
    {
        $id = $this->input->post('service_id');
        $party_id = $this->input->post('party_id');
        $result = $this->user_model->getAry('crm_servicesubgroup', '', '', '', ['service_id' => $id]);
        $html = '';
        // select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
        foreach ($result as $key => $dist_val) {
            $sub_services = get_title('crm_party', ['id' => $party_id], 'sub_services_id');
            $subs_se=json_decode($sub_services);
            if (in_array($dist_val->id, $subs_se)) {
                $chekd = "checked";
            } else {
                $chekd = "";
            }
            $html .= '<div class="col-md-2 col-sm-2 col-lg-2">
            <div class="form-group">
                <label for="sub_service">' . $dist_val->sub_service_name . '<span class="text-danger"></span></label>
                <input name="sub_service[]" '.$chekd.' type="checkbox" value="' . $dist_val->id . '" class="form-check-input form-control">
            </div>

        </div>';
        }
        $response = array(
            'status' => 'success',
            'data' => $html
        );
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
