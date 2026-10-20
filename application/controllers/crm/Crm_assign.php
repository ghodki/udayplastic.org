<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crm_assign extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('user_model');
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
        if (!$this->user_model->check_permission('crm/crm_assign')) {
            $data['page'] = 'admin_details/error/permission_denied';
            // redirect(base_url() . 'index.php/dashboard');
        } else {
            $data['crm_company'] = $this->user_model->getResult('crm_company');
            $data['page'] = 'admin_details/crm_assign/index';
        }
        $this->load->view('admin', $data);
    }
    public function stock($id = null)
    {
        if ($id) {
            $data['comp_details'] = $this->user_model->getRow('inventory_billing', ['id' => $id]);
            $this->load->view('admin_details/crm_assign/comp_stock', $data);
        }
    }

    public function mobile_number_report($id = null)
    {
        $where = ['user_id' => $id, 'contact!=' => ''];
        $data['parties'] = $this->user_model->getAry('parties', '', '', '', $where);
        $data['page'] = 'admin_details/mobile_number_report';
        $data['user_unique_id'] = $id;
        $this->load->view('admin', $data);
    }

    public function customer_report()
    {
        $where = ['contact!=' => ''];
        $data['parties'] = $this->user_model->getAry('parties', '', '', '', $where);
        $data['page'] = 'admin_details/customer_report';
        $this->load->view('admin', $data);
    }

    public function assign_to_user($id)
    {
        if ($id) {
            $data['id'] = $id;
            $this->load->view('admin_details/crm_assign/assign', $data);
        }
    }

    public function assign_comp_to_user()
    {
        $post = $this->input->post();
      
        $comp_id = $this->input->post('comp_id');
        $assing_user = [];
        $data['comp_id'] = $comp_id;
        $data['user_id'] = get_title('crm_company', ['id' => $comp_id], 'user_id');
        $user_id = get_title('crm_company', ['id' => $comp_id], 'user_id');
        $result = $this->user_model->getRow('crm_assign', ['comp_id' => $comp_id, 'user_id' => $user_id]);
        if (isset($post) && !empty($post) && !empty($comp_id)) {
            foreach ($post['assign_user'] as $key => $value) {
                $assing_user[] = $value;
            }
            $data['assign_user'] = json_encode($assing_user);
            if ($result) {
                $res = $this->user_model->updateAry('crm_assign', $data, ['id' => $result->id]);
            } else {
                $res = $this->user_model->saveAry('crm_assign', $data);
            }
            if ($res) {
                echo json_encode(['status' => true, 'data' => 'Company Assign Successfully', 'class' => 'success']);
            } else {
                echo json_encode(['status' => false, 'data' => 'Something Went Wronng', 'class' => 'error']);
            }
        } else {
            echo json_encode(['status' => false, 'data' => 'Please Filled Required Fields.', 'class' => 'error']);
        }
        // $result = $this->user_model->getRow('compassign', ['comp_id' => $comp_id, 'user_id' => $value]);
        // if ($result) {
        //     $this->user_model->updateAry('compassign', ['comp_id' => $comp_id, 'user_id' => $value], ['id' => $result->id]);
        // } else {
        //     $this->user_model->saveAry('compassign', $data);
        // }
        // if ($id) {
        //     $data['id'] = $id;
        //     $this->load->view('admin_details/crm_assign/assign', $data);
        // }
    }
}
