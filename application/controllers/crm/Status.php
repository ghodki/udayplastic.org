<?php
defined('BASEPATH') or exit('No direct script access allowed');

class status extends CI_Controller
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
        $this->db->from('crm_status');
        $data['crm_status'] = $this->db->get()->result();
        $data['page'] = 'status/index';
        $this->load->view('crm/new_main', $data);
    }

    public function PageShow($pagename)
    {
        $this->load->view('crm/status/' . $pagename);
    }

    public function active_user()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_status');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 1);
        $data['statusData'] = $this->db->get()->result();
        $data['page'] = 'status/active';
        $this->load->view('crm/new_main', $data);
    }

    public function deactiveuser()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_status');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 0);
        $data['statusData'] = $this->db->get()->result();
        // $data['page'] = 'status/deactivate';
        $this->load->view('crm/status/deactivate', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'status_name',
                'label' => 'status_name',
                'rules' => 'trim|required|xss_clean|callback_unique_status'
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
                'field' => 'status_name',
                'label' => 'status_name',
                'rules' => 'trim|required|xss_clean|callback_unique_status_update'
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
                'field' => 'status_id',
                'label' => 'status',
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
                $data['name'] = $this->input->post('status_name');
                $data['short_name'] = $this->input->post('short_name');
                $data['status'] = $this->input->post('status');
                $data['services_id'] = json_encode($this->input->post('service'));
                $data['sub_services_id'] = json_encode($this->input->post('sub_service'));
                $data['created_at'] = time();
                $result = $this->user_model->saveAry('crm_status', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>status Created</h3>"
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => "<h3>status Could not be Created</h3>"
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


    public function edit_status($id)
    {
        $data['result'] = $this->user_model->getRow('crm_status', ['id' => $id]);
        // $data['page'] = 'status/edit';

        $this->load->view('crm/status/edit', $data);
    }

    public function view_status($id)
    {
        if ($id) {
            $data['result'] = $this->user_model->getRow('crm_status', ['id' => $id]);
            $this->load->view('crm/status/view', $data);
        }
    }

    public function update_status()
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
                $data['name'] = $this->input->post('status_name');
                $data['short_name'] = $this->input->post('short_name');
                $data['status'] = $this->input->post('status');
                $data['services_id'] = json_encode($this->input->post('service'));
                $data['sub_services_id'] = json_encode($this->input->post('sub_service'));
                $result = $this->user_model->updateAry('crm_status', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>status Updated</h3>"
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
                $id = $this->input->post('status_id');
                $result = $this->user_model->updateAry('crm_status', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>status Updated</h3>"
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

    public function unique_status()
    {
        $status_name = $this->input->post('status_name');
        $where = ['name' => $status_name];
        $return_value = $this->user_model->getRow('crm_status', $where);
        if ($return_value) {
            $this->form_validation->set_message('unique_status', 'status should be Unique');
            return false;
        }
        return true;
    }

    public function unique_status_update()
    {
        $status_name = $this->input->post('status_name');
        $id = $this->input->post('id');
        $where = ['name' => $status_name, 'id!=' => $id];
        $return_value = $this->user_model->getRow('crm_status', $where);
        // print_r($return_value);
        if ($return_value) {
            $this->form_validation->set_message('unique_status_update', 'status should be Unique');
            return false;
        }
        return true;
    }

    public function substatuss()
    {
        $id = $this->input->post('status_id');
        $result = $this->user_model->getAry('crm_statussubgroup', '', '', '', ['status_id' => $id]);
        $html = '';
        // select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
        foreach ($result as $key => $dist_val) {
            $html .= '<div class="col-md-2 col-sm-2 col-lg-2">
            <div class="form-group">
                <label for="sub_status[]">' . $dist_val->sub_status_name . '<span class="text-danger"></span></label>
                <input name="sub_status[]" type="checkbox" value="' . $dist_val->id . '" class="form-check-input form-control">
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

    public function chekedSubstatus()
    {
        $id = $this->input->post('status_id');
        $party_id = $this->input->post('party_id');
        $result = $this->user_model->getAry('crm_statussubgroup', '', '', '', ['status_id' => $id]);
        $html = '';
        // select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
        foreach ($result as $key => $dist_val) {
            $sub_statuss = get_title('crm_party', ['id' => $party_id], 'sub_statuss_id');
            $subs_se=json_decode($sub_statuss);
            if (in_array($dist_val->id, $subs_se)) {
                $chekd = "checked";
            } else {
                $chekd = "";
            }
            $html .= '<div class="col-md-2 col-sm-2 col-lg-2">
            <div class="form-group">
                <label for="sub_status">' . $dist_val->sub_status_name . '<span class="text-danger"></span></label>
                <input name="sub_status[]" '.$chekd.' type="checkbox" value="' . $dist_val->id . '" class="form-check-input form-control">
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
        $status_id = $this->input->post('status_id');
        $result = $this->user_model->getAry('crm_servicesubgroup', '', '', '', ['service_id' => $id]);
        $html = '';
        // select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
        foreach ($result as $key => $dist_val) {
            $sub_services = get_title('crm_status', ['id' => $status_id], 'sub_services_id');
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
