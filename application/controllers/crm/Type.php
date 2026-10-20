<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Type extends CI_Controller
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
        $this->db->from('crm_type');
        $data['crm_type'] = $this->db->get()->result();
        $data['page'] = 'type/index';
        $this->load->view('crm/new_main', $data);
    }

    public function PageShow($pagename)
    {
        $this->load->view('crm/type/' . $pagename);
    }

    public function active_user()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_type');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('type', 1);
        $data['typeData'] = $this->db->get()->result();
        $data['page'] = 'type/active';
        $this->load->view('crm/new_main', $data);
    }

    public function deactiveuser()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_type');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('type', 0);
        $data['typeData'] = $this->db->get()->result();
        // $data['page'] = 'type/deactivate';
        $this->load->view('crm/type/deactivate', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'type_name',
                'label' => 'type_name',
                'rules' => 'trim|required|xss_clean|callback_unique_type'
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
                'field' => 'type_name',
                'label' => 'type_name',
                'rules' => 'trim|required|xss_clean|callback_unique_type_update'
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


    protected function change_type_rules()
    {
        $rules = array(
            array(
                'field' => 'type_id',
                'label' => 'type',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'type',
                'label' => 'type',
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
                    'type' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['name'] = $this->input->post('type_name');
                $data['short_name'] = $this->input->post('short_name');
                $data['status'] = $this->input->post('status');
                $data['services_id'] = json_encode($this->input->post('service'));
                $data['sub_services_id'] = json_encode($this->input->post('sub_service'));

                $data['created_at'] = time();
                $result = $this->user_model->saveAry('crm_type', $data);
                if ($result) {
                    $response = array(
                        'type' => 'success',
                        'message' => "<h3>type Created</h3>"
                    );
                } else {
                    $response = array(
                        'type' => 'error',
                        'message' => "<h3>type Could not be Created</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'type' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function edit_type($id)
    {
        $data['result'] = $this->user_model->getRow('crm_type', ['id' => $id]);
        // $data['page'] = 'type/edit';

        $this->load->view('crm/type/edit', $data);
    }

    public function view_type($id)
    {
        if ($id) {
            $data['result'] = $this->user_model->getRow('crm_type', ['id' => $id]);
            $this->load->view('crm/type/view', $data);
        }
    }

    public function update_type()
    {

        if ($_POST) {
            $rules = $this->update_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'type' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $id = $this->input->post('id');
                $data['name'] = $this->input->post('type_name');
                $data['short_name'] = $this->input->post('short_name');
                $data['status'] = $this->input->post('status');
                $data['services_id'] = json_encode($this->input->post('service'));
                $data['sub_services_id'] = json_encode($this->input->post('sub_service'));
                
                $result = $this->user_model->updateAry('crm_type', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'type' => 'success',
                        'message' => "<h3>type Updated</h3>"
                    );
                } else {
                    $response = array(
                        'type' => 'error',
                        'message' => "<h3>Something Went Wrong</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'type' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function changetype()
    {

        if ($_POST) {
            $rules = $this->change_type_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $response = array(
                    'type' => 'error',
                    'message' => validation_errors()
                );
            } else {
                $data['status'] = $this->input->post('type');
                $id = $this->input->post('type_id');
                $result = $this->user_model->updateAry('crm_type', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'type' => 'success',
                        'message' => "<h3>type Updated</h3>"
                    );
                } else {
                    $response = array(
                        'type' => 'error',
                        'message' => "<h3>Something Went Wrong</h3>"
                    );
                }
            }
        } else {
            $response = array(
                'type' => 'error',
                'message' => "<h3>Required Field can not Be Empty</h3>"
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function unique_type()
    {
        $type_name = $this->input->post('type_name');
        $where = ['name' => $type_name];
        $return_value = $this->user_model->getRow('crm_type', $where);
        if ($return_value) {
            $this->form_validation->set_message('unique_type', 'type should be Unique');
            return false;
        }
        return true;
    }

    public function unique_type_update()
    {
        $type_name = $this->input->post('type_name');
        $id = $this->input->post('id');
        $where = ['name' => $type_name, 'id!=' => $id];
        $return_value = $this->user_model->getRow('crm_type', $where);
        // print_r($return_value);
        if ($return_value) {
            $this->form_validation->set_message('unique_type_update', 'type should be Unique');
            return false;
        }
        return true;
    }

    public function subtypes()
    {
        $id = $this->input->post('type_id');
        $result = $this->user_model->getAry('crm_typesubgroup', '', '', '', ['type_id' => $id]);
        $html = '';
        // select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
        foreach ($result as $key => $dist_val) {
            $html .= '<div class="col-md-2 col-sm-2 col-lg-2">
            <div class="form-group">
                <label for="sub_type[]">' . $dist_val->sub_type_name . '<span class="text-danger"></span></label>
                <input name="sub_type[]" type="checkbox" value="' . $dist_val->id . '" class="form-check-input form-control">
            </div>

        </div>';
        }
        $response = array(
            'type' => 'success',
            'data' => $html
        );
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function chekedSubtype()
    {
        $id = $this->input->post('type_id');
        $party_id = $this->input->post('party_id');
        $result = $this->user_model->getAry('crm_typesubgroup', '', '', '', ['type_id' => $id]);
        $html = '';
        // select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" onfocusout="onProductCodeFocusChange(event,' + rowCount + ')"
        foreach ($result as $key => $dist_val) {
            $sub_types = get_title('crm_party', ['id' => $party_id], 'sub_types_id');
            $subs_se = json_decode($sub_types);
            if (in_array($dist_val->id, $subs_se)) {
                $chekd = "checked";
            } else {
                $chekd = "";
            }
            $html .= '<div class="col-md-2 col-sm-2 col-lg-2">
            <div class="form-group">
                <label for="sub_type">' . $dist_val->sub_type_name . '<span class="text-danger"></span></label>
                <input name="sub_type[]" ' . $chekd . ' type="checkbox" value="' . $dist_val->id . '" class="form-check-input form-control">
            </div>

        </div>';
        }
        $response = array(
            'type' => 'success',
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
            $sub_services = get_title('crm_type', ['id' => $status_id], 'sub_services_id');
            $subs_se = json_decode($sub_services);
            if (in_array($dist_val->id, $subs_se)) {
                $chekd = "checked";
            } else {
                $chekd = "";
            }
            $html .= '<div class="col-md-2 col-sm-2 col-lg-2">
            <div class="form-group">
                <label for="sub_service">' . $dist_val->sub_service_name . '<span class="text-danger"></span></label>
                <input name="sub_service[]" ' . $chekd . ' type="checkbox" value="' . $dist_val->id . '" class="form-check-input form-control">
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
