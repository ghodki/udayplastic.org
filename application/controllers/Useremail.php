<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Useremail extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);
            $this->load->model('user_model');
            
            $this->load->library('ajax_pagination');
            $this->perPage = 10;
            
            if ($this->session->userdata('user_id') === null) {
                redirect('signin');
            }
            
            date_default_timezone_set('Asia/Kolkata');
	}
        
        public function index()
        {
            $data = array();
            // Get record count 
            $conditions['returnType'] = 'count';
            $totalRec = $this->user_model->getCommonRows($conditions, 'usersemail');

            // Pagination configuration 
            $config['target']      = '#dataList';
            $config['base_url']    = base_url('useremail/load');
            $config['total_rows']  = $totalRec;
            $config['per_page']    = $this->perPage;
            $config['link_func']   = 'searchFilter';

            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['limit'] = $this->perPage;
            $data['result'] = $this->user_model->getCommonRows($conditions, 'usersemail');
            $data['groupname'] = $this->user_model->getResult('emailgroup');
            
            // Load the list page view
            $data['page'] = 'admin_details/useremail/index';
            $this->load->view('admin', $data);
        }
        
        public function addgroup()
        {
            if ($_POST) {
                $data['name'] = $this->input->post('name');
                $data['created_at'] = date('d-m-Y h:m:s A');

                $result = $this->user_model->saveAry('emailgroup', $data);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record added successfully.', 'class' => 'success']);
                    redirect('useremail');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed save record.', 'class' => 'danger']);
                    redirect('useremail');
                }
            }
        }

        public function editgroup($id)
        {
            $return = $this->user_model->getRow('emailgroup', ['id' => $id]);
            if ($return) {
                $data['id'] = $id;
                $data['groupinfo'] = $return;
                $data['groupname'] = $this->user_model->getResult('emailgroup');
                $data['page'] = 'admin_details/useremail/index';
                $this->load->view('admin', $data);    
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                redirect('useremail');
            }   
        }
        
        public function updategroup()
        {
            if ($_POST) {
                $data['name'] = $this->input->post('name');
                $data['updated'] = date('d-m-Y h:m:s A');
                
                $result = $this->user_model->updateAry('emailgroup', $data, ['id' => $this->input->post('groupid')]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record updated successfully.', 'class' => 'success']);
                    redirect('useremail');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed to update record.', 'class' => 'danger']);
                    redirect('useremail');
                }
            }
        }
        
        public function deletegroup($id)
        {
            $return = $this->user_model->getRow('emailgroup', ['id' => $id]);
            if ($return) {
                $result = $this->user_model->deleteRow('emailgroup', ['id' => $return->id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    redirect('useremail');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed to delete record.', 'class' => 'danger']);
                    redirect('useremail');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'User not found.', 'class' => 'danger']);
                redirect('useremail');
            }
        }
        
        public function load()
        {
            // Define offset 
            $page = $this->input->post('page');
            if (!$page) {
                $offset = 0;
            } else {
                $offset = $page;
            }

            $keywords = $this->input->post('keywords');
            $sortBy = $this->input->post('sortBy');
            $pagelength = $this->input->post('pagelength');
            $column_filter = $this->input->post('column_filter');

            // Get record count 
            $conditions['returnType'] = 'count';
            if (!empty($keywords)) {
                $conditions['search']['keywords'] = $keywords;
            }
            if (!empty($sortBy)) {
                $conditions['search']['sortBy'] = $sortBy;
            }
            $totalRec = $this->user_model->getCommonRows($conditions, 'usersemail', $column_filter);

            // Pagination configuration 
            $config['target']      = '#dataList';
            $config['base_url']    = base_url('useremail/load');
            $config['total_rows']  = $totalRec;
            $config['per_page']    = $pagelength;
            $config['link_func']   = 'searchFilter';
            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['start'] = $offset;
            $conditions['limit'] = $pagelength;
            unset($conditions['returnType']);
            $data['result'] = $this->user_model->getCommonRows($conditions, 'usersemail', $column_filter);
            
            // Load the data list view 
            $this->load->view('admin_details/useremail/load', $data, false);
        }
        
	protected function rules()
	{
            $rules = array(
                array(
                    'field' => 'group_id',
                    'label' => 'Group Name',
                    'rules' => 'trim|required|max_length[50]'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required'
                )
            );
            return $rules;
	}
        
	protected function rules_edit()
	{
            $rules = array(
                array(
                    'field' => 'group_id',
                    'label' => 'Group Name',
                    'rules' => 'trim|required|max_length[50]'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required'
                )
            );
            return $rules;
	}
        
        public function saveuseremail()
	{
            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                    $data['page'] = 'admin_details/useremail/index';
                    $this->load->view('admin', $data);
                } else {
                    $data['date'] = date('d-m-Y');
                    $data['group_id'] = $this->input->post('group_id');
                    $data['email'] = $this->input->post('email');
                    $data['created_at'] = date('d-m-Y h:m:s A');
                    
                    $result = $this->user_model->saveAry('usersemail', $data);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record added successfully.', 'class' => 'success']);
                        redirect('useremail');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed save record.', 'class' => 'danger']);
                        redirect('useremail');
                    }
                }
            }
        }
        
        public function importemails()  
        {
            if (isset($_FILES["file"]["name"])) {
                $file_type = $_FILES["file"]["type"];
                if ($file_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file_type == 'application/vnd.ms-excel') {
                    $path = $_FILES["file"]["tmp_name"];
                    $object = PHPExcel_IOFactory::load($path);
                    $data = [];
                    foreach ($object->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $data = array(
                                'date' => date('d-m-Y'),
                                'group_id' => get_title('emailgroup', ['name' => $worksheet->getCellByColumnAndRow(0, $row)->getValue()], 'id'),
                                'email' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                'created_at' => date('d-m-Y h:m:s A')
                            );

                            $res = $this->user_model->saveAry('usersemail', $data);
                        }
                        if (isset($res) && !empty($res)) {
                            $this->session->set_flashdata(['msg' => 'Emals imported', 'class' => 'success']);
                            redirect('useremail');
                        } else {
                            $this->session->set_flashdata(['msg' => 'failed to import data', 'class' => 'danger']);
                            return redirect('useremail');
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Something Went wrong', 'class' => 'danger']);
                redirect('useremail');
            }
        }
        
        public function edit($id)
        {
            $return = $this->user_model->getRow('usersemail', ['id' => $id]);
            if ($return) {
                $data['userinfo'] = $return;
                $data['groupname'] = $this->user_model->getResult('emailgroup');
                $data['page'] = 'admin_details/useremail/edit';
                $this->load->view('admin', $data);    
            } else {
                $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                redirect('useremail');
            }
        }
        
        public function updateuseremail()
        {
            if ($_POST) {
                $rules = $this->rules_edit();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data['form_validation'] = validation_errors();
                    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                    $data['groupname'] = $this->user_model->getResult('emailgroup');
                    $data['page'] = 'admin_details/useremail/index';
                    $this->load->view('admin', $data);
                } else {
                    $data['group_id'] = $this->input->post('group_id');
                    $data['email'] = $this->input->post('email');
                    $data['updated_at'] = date('d-m-Y h:m:s A');
                    
                    $result = $this->user_model->updateAry('usersemail', $data, ['id' => $this->input->post('id')]);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record updated successfully.', 'class' => 'success']);
                        redirect('useremail');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed to update record.', 'class' => 'danger']);
                        redirect('useremail');
                    }
                }
            }
        }
        
        public function delete($id)
        {
            $return = $this->user_model->getRow('usersemail', ['id' => $id]);
            if ($return) {
                $result = $this->user_model->deleteRow('usersemail', ['id' => $return->id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    redirect('useremail');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed to delete record.', 'class' => 'danger']);
                    redirect('useremail');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'User not found.', 'class' => 'danger']);
                redirect('useremail');
            }
        }
        
        public function sendmail()
        {
            if ($_POST) {
                $from = $this->input->post('from');
                $groupid = $this->input->post('group_id');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');

                $return = $this->user_model->getResult('usersemail', ['group_id' => $groupid]);
                if ($return) {                   
                    foreach ($return as $key => $emailid) {
                        $this->email->from($from);
                        $this->email->to($emailid->email);
                        $this->email->subject($subject);
                        $this->email->message($message);
                        $sendmail = $this->email->send();
                        sleep(5); // in second
                    }

                    if ($sendmail) {
                        $data['send_from'] = $from;
                        $data['group_id'] = $groupid;
                        $data['subject'] = $subject;
                        $data['message'] = $message;
                        $data['created_at'] = date('d-m-Y h:m:s A');
                        $this->user_model->saveAry('sendemail', $data);
                        $this->session->set_flashdata(['msg' => 'Mail send Successfully.', 'class' => 'success']);
                        redirect('useremail');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Something Went Wrong.', 'class' => 'danger']);
                        redirect('useremail');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Could not find the data.', 'class' => 'danger']);
                    redirect('useremail');   
                }
            }
        }
        
        public function mailreport()
        {
            $data = array();
            // Get record count 
            $conditions['returnType'] = 'count';
            $totalRec = $this->user_model->getCommonRows($conditions, 'sendemail');

            // Pagination configuration 
            $config['target']      = '#dataList';
            $config['base_url']    = base_url('useremail/load_mailreport');
            $config['total_rows']  = $totalRec;
            $config['per_page']    = $this->perPage;
            $config['link_func']   = 'searchFilter';

            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['limit'] = $this->perPage;
            $data['result'] = $this->user_model->getCommonRows($conditions, 'sendemail');
            
            // Load the list page view
            $data['page'] = 'admin_details/useremail/mailreport';
            $this->load->view('admin', $data);
        }
        
        public function load_mailreport()
        {
            // Define offset 
            $page = $this->input->post('page');
            if (!$page) {
                $offset = 0;
            } else {
                $offset = $page;
            }

            $keywords = $this->input->post('keywords');
            $sortBy = $this->input->post('sortBy');
            $pagelength = $this->input->post('pagelength');
            $column_filter = $this->input->post('column_filter');

            // Get record count 
            $conditions['returnType'] = 'count';
            if (!empty($keywords)) {
                $conditions['search']['keywords'] = $keywords;
            }
            if (!empty($sortBy)) {
                $conditions['search']['sortBy'] = $sortBy;
            }
            $totalRec = $this->user_model->getCommonRows($conditions, 'sendemail', $column_filter);

            // Pagination configuration 
            $config['target']      = '#dataList';
            $config['base_url']    = base_url('useremail/load_mailreport');
            $config['total_rows']  = $totalRec;
            $config['per_page']    = $pagelength;
            $config['link_func']   = 'searchFilter';
            // Initialize pagination library 
            $this->ajax_pagination->initialize($config);
            // Get records 
            $conditions['start'] = $offset;
            $conditions['limit'] = $pagelength;
            unset($conditions['returnType']);
            $data['result'] = $this->user_model->getCommonRows($conditions, 'sendemail', $column_filter);
            
            // Load the data list view 
            $this->load->view('admin_details/useremail/load_mailreport', $data, false);   
        }
        
        public function messagebody($id)
        {
            $data['id'] = $id;
            $this->load->view('admin_details/useremail/messagebody', $data);
        }
    }