<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Party extends CI_Controller
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
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/index';
        $this->load->view('crm/main', $data);
    }

    public function assignCal()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
        $partyData = $this->db->get()->result();
        if ($partyData) {
            foreach ($partyData as $dat) {
                $this->db->select('*');
                $this->db->from('crm_sale');
                $this->db->where('user_id', $user_id);
                $this->db->where('party_id', $dat->id);
                $this->db->where('crm_id', $this->session->userdata('crm_id'));
                $saleData = $this->db->get()->result();
                if ($saleData) {
                    foreach ($saleData as $dataaa) {
                        $updatedData['subservice_id'] = $this->session->userdata('sub_service_id');
                        $this->user_model->updateAry('crm_sale', $updatedData, ['party_id' => $dat->id, 'user_id', $user_id, 'crm_id', $this->session->userdata('crm_id')]);
                    }
                }
            }
        }
    }

    public function partyData()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/index';
        $this->load->view('crm/new_main', $data);
    }

    public function customer_report()
    {
        $data['page'] = 'party/customer_report';
        $this->load->view('crm/main', $data);
    }

    public function updateCate()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $aman = $this->db->get()->result();
        if ($aman) {
            foreach ($aman as $akc) {
                $data['category_id'] = $akc->category_id;
                $this->user_model->updateAry('crm_sale', $data, ['party_id' => $akc->id]);
            }
        }
        // $data['page'] = 'party/index';
        // $this->load->view('crm/main', $data);
    }

    public function active_user_for()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 1);

        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/active';
        $this->load->view('crm/main', $data);
    }

    public function deactiveuser_for()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 0);


        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/deactivate';
        $this->load->view('crm/main', $data);
    }

    public function active_user()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('status', 1);
        $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));


        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/active';
        $this->load->view('crm/main', $data);
    }

    public function active_userby_service()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->like('services_id', $this->session->userdata('service_id'));
        $this->db->where('status', 1);
        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/service_party';
        $this->load->view('crm/main', $data);
    }

    public function deactiveuser()
    {
        $user_id = $this->session->userdata('user_id');
        $where = ['user_id' => $user_id];
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
        $this->db->where('status', 0);


        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/deactivate';
        $this->load->view('crm/main', $data);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|xss_clean|callback_unique_party'
            ),
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'number',
                'label' => 'number',
                'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
            ),
            array(
                'field' => 'gst',
                'label' => 'gst',
                'rules' => 'trim|xss_clean|regex_match[/\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}/]|callback_unique_gst'
            ),
            array(
                'field' => 'address',
                'label' => 'address',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'contact',
                'label' => 'contact|xss_clean',
                'rules' => 'trim'
            ),




        );
        return $rules;
    }

    protected function update_rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|xss_clean|callback_unique_party_update'
            ),
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'number',
                'label' => 'number',
                'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
            ),
            array(
                'field' => 'gst',
                'label' => 'gst',
                'rules' => 'trim|xss_clean|regex_match[/\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}/]|callback_unique_gst_update'
            ),
            array(
                'field' => 'address',
                'label' => 'address',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'contact',
                'label' => 'contact|xss_clean',
                'rules' => 'trim'
            ),




        );
        return $rules;
    }


    protected function change_status_rules()
    {
        $rules = array(
            array(
                'field' => 'party_id',
                'label' => 'Party',
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
                $data['party_name'] = $this->input->post('name');
                $data['authorised_name'] = $this->input->post('authorised_name');
                $data['address'] = $this->input->post('address');
                $data['gst_no'] = $this->input->post('gst');
                $data['number'] = $this->input->post('number');
                $data['category_id'] = $this->input->post('category');
                $data['email'] = $this->input->post('email');
                $data['services_id'] = json_encode($this->input->post('service'));
                $data['sub_services_id'] = json_encode($this->input->post('sub_service'));
                $data['gst_username'] = $this->input->post('gst_username');
                $data['gst_password'] = $this->input->post('gst_password');
                $data['tds_username'] = $this->input->post('tds_username');
                $data['tds_password'] = $this->input->post('tds_password');
                $data['incomeTax_username'] = $this->input->post('incomeTax_username');
                $data['incomeTax_password'] = $this->input->post('incomeTax_password');
                $data['ewaybill_password'] = $this->input->post('ewaybill_password');
                $data['ewaybill_username'] = $this->input->post('ewaybill_username');
                $data['pt_password'] = $this->input->post('pt_password');
                $data['pt_username'] = $this->input->post('pt_username');
                $data['remark'] = $this->input->post('remark');
                $data['incometax_file_no'] = $this->input->post('incometax_file_no');
                $data['tds_file_no'] = $this->input->post('tds_file_no');
                $data['gst_file_no'] = $this->input->post('gst_file_no');
                $data['pt_file_no'] = $this->input->post('pt_file_no');
                $data['created_at'] = time();
                $data['user_id'] = $this->session->userdata('user_id');
                $data['crm_id'] = $this->session->userdata('crm_id');
                $data['serial_number'] = rand('10000', '99999');

                $result = $this->user_model->saveAry('crm_party', $data);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Party Created</h3>"
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


    public function edit($id)
    {
        $data['result'] = $this->user_model->getRow('crm_party', ['id' => $id]);
        // $data['page'] = 'party/edit';

        $this->load->view('crm/party/edit', $data);
    }

    public function view($id)
    {
        if ($id) {
            $data['result'] = $this->user_model->getRow('crm_party', ['id' => $id]);
            $this->load->view('crm/party/view', $data);
        }
    }

    public function update()
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

                $data['party_name'] = $this->input->post('name');
                $data['services_id'] = json_encode($this->input->post('service'));
                $data['sub_services_id'] = json_encode($this->input->post('sub_service'));
                $data['authorised_name'] = $this->input->post('authorised_name');
                $data['address'] = $this->input->post('address');
                $data['gst_no'] = $this->input->post('gst');
                $data['number'] = $this->input->post('number');
                $data['category_id'] = $this->input->post('category');
                $data['email'] = $this->input->post('email');
                $data['gst_username'] = $this->input->post('gst_username');
                $data['gst_password'] = $this->input->post('gst_password');
                $data['tds_username'] = $this->input->post('tds_username');
                $data['tds_password'] = $this->input->post('tds_password');
                $data['incomeTax_username'] = $this->input->post('incomeTax_username');
                $data['incomeTax_password'] = $this->input->post('incomeTax_password');
                $data['ewaybill_password'] = $this->input->post('ewaybill_password');
                $data['ewaybill_username'] = $this->input->post('ewaybill_username');
                $data['pt_password'] = $this->input->post('pt_password');
                $data['pt_username'] = $this->input->post('pt_username');
                $data['remark'] = $this->input->post('remark');
                $data['incometax_file_no'] = $this->input->post('incometax_file_no');
                $data['tds_file_no'] = $this->input->post('tds_file_no');
                $data['gst_file_no'] = $this->input->post('gst_file_no');
                $data['pt_file_no'] = $this->input->post('pt_file_no');
                $data['created_at'] = time();
                $data['user_id'] = $this->session->userdata('user_id');
                $data['crm_id'] = $this->session->userdata('crm_id');
                $data['serial_number'] = rand('10000', '99999');

                $result = $this->user_model->updateAry('crm_party', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Party Updated</h3>"
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
                $id = $this->input->post('id');
                $data['status'] = $this->input->post('status');
                $id = $this->input->post('party_id');
                $result = $this->user_model->updateAry('crm_party', $data, ['id' => $id]);
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => "<h3>Party Updated</h3>"
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

    public function partyUploadByExcel()
    {

        if (isset($_FILES["file"]["name"])) {
            $file_type = $_FILES["file"]["type"];
            if ($file_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file_type == 'application/vnd.ms-excel') {
                $path = $_FILES["file"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                $data = [];
                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $product_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $hsn = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $qty = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $subcate = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $short_de = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $long_des = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $price = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $discount = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $cost = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $min_qty = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $accounting_group = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $state = $worksheet->getCellByColumnAndRow(11, $row)->getValue();




                        $data[] = array(
                            'name'  => $product_name,
                            'contact'   => $hsn,
                            'email'    => $qty,
                            'pan'  => $subcate,
                            'gst'   => $short_de,
                            'gstType' => $long_des,
                            'credit_limit' => $price,
                            'address_1' => $discount,
                            'opening_balance' => $cost,
                            'pin' => $min_qty,
                            // 'state' => $state,
                            'state_code' => $this->user_model->getApproxTax('state_list', $state, 'state'),
                            'state' => $this->user_model->getApproxTax('state_list', $state, 'state'),
                            'group_head' => $this->user_model->getApproxTax('accounting_group', $accounting_group, 'name'),
                            'user_id' => $this->session->userdata('user_id'),
                            'comp_id' => $this->session->userdata('comp_id'),
                        );
                    }
                }
                // echo '<pre>';
                // 				print_r($data);
                // 				die;
                // if (empty($product_name)) {
                // 	$this->session->set_flashdata(['msg' => 'Party Name Is Required', 'class' => 'danger']);
                // 	redirect('party');
                // }
                // if (empty($this->user_model->getApproxTax('state_list', $state, 'state'))) {
                // 	$this->session->set_flashdata(['msg' => 'State  Is Required', 'class' => 'danger']);
                // 	redirect('party');
                // }
                // if (empty($this->user_model->getApproxTax('accounting_group', $accounting_group, 'name'))) {
                // 	$this->session->set_flashdata(['msg' => 'Accounting Group Is Required', 'class' => 'danger']);
                // 	redirect('party');
                // }




                // $where = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                // $res = $this->user_model->getAry('parties', '', '', '', $where);
                if (isset($data) && !empty($data)) {
                    foreach ($data as $pro_info) {
                        $this->user_model->saveAry('parties', $pro_info);
                    }
                    if ($data) {

                        $this->session->set_flashdata(['msg' => 'Party Import Successfully.', 'class' => 'success']);
                        redirect('party');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                        redirect('party');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                    redirect('party');
                }
            } else {

                $this->session->set_flashdata(['msg' => 'Only xls and xlsx File Valid.', 'class' => 'danger']);
                redirect('party');
            }
            // return redirect('inventory', $data);
        } else {
            $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
            redirect('party');
        }
    }


    public function unique_party()
    {
        $name = $this->input->post('name');
        $user_id = $this->session->userdata('user_id');
        $crm_id = $this->session->userdata('crm_id');

        $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_name' => $name];
        $return_value = $this->user_model->getRow('crm_party', $where);
        // print_r($return_value);
        if ($return_value) {
            $this->form_validation->set_message('unique_party', 'Party should be Unique');
            return false;
        }
        return true;
    }

    public function unique_party_update()
    {
        $name = $this->input->post('name');
        $id = $this->input->post('id');

        $user_id = $this->session->userdata('user_id');
        $crm_id = $this->session->userdata('crm_id');

        $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_name' => $name, 'id!=' => $id];
        $return_value = $this->user_model->getRow('crm_party', $where);
        // print_r($return_value);
        if ($return_value) {
            $this->form_validation->set_message('unique_party_update', 'Party should be Unique');
            return false;
        }
        return true;
    }

    public function unique_gst()
    {
        $gst = $this->input->post('gst');
        $user_id = $this->session->userdata('user_id');
        $crm_id = $this->session->userdata('crm_id');

        $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'gst_no' => $gst];
        $return_value = $this->user_model->getRow('crm_party', $where);
        // print_r($return_value);
        if ($return_value && !empty($gst)) {
            $this->form_validation->set_message('unique_gst', 'GST No should be Unique');
            return false;
        }
        return true;
    }

    public function unique_gst_update()
    {
        $gst = $this->input->post('gst');
        $id = $this->input->post('id');

        $user_id = $this->session->userdata('user_id');
        $crm_id = $this->session->userdata('crm_id');

        $where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'gst_no' => $gst, 'id!=' => $id];
        $return_value = $this->user_model->getRow('crm_party', $where);
        // print_r($return_value);
        if ($return_value && !empty($gst)) {
            $this->form_validation->set_message('unique_gst_update', 'GST No should be Unique');
            return false;
        }
        return true;
    }


    public function not_register()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->where('sub_services_id', null);

        // $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
        // $this->db->where('status', 1);
        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/not_registered';
        $this->load->view('crm/new_main', $data);
    }


    public function assignUsers($service_id = null)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->like('services_id', $service_id);

        // $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
        // $this->db->where('status', 1);
        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/active';
        $this->load->view('crm/new_main', $data);
    }

    public function NotassignUsers($service_id = null)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('crm_party');
        $this->db->where('user_id', $user_id);
        $this->db->where('crm_id', $this->session->userdata('crm_id'));
        $this->db->not_like('services_id', $service_id);
        $data['partyData'] = $this->db->get()->result();
        $data['page'] = 'party/active';
        $this->load->view('crm/new_main', $data);
    }
}
