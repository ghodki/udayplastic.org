<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Aboutus extends CI_Controller
    {
	public function __construct()
	{
            parent::__construct();
            $this->load->helper(['form', 'url']);

            $this->load->model('user_model');

            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
	}

	public function index()
	{
            if (!$this->user_model->check_permission('aboutus/index')) {
                $data['page'] = 'admin_details/error/permission_denied';
            } else {
                $data['aboutus'] = $this->user_model->getResult('aboutus');
                $data['page'] = 'admin_details/aboutus/index';
            }
            $this->load->view('admin', $data);
	}

        public function save()
	{
            if ($_POST) {
                $this->form_validation->set_rules('content', 'Content', 'trim|required');
                $this->form_validation->set_rules('content2', 'Content', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['page'] = "admin_details/aboutus/index";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('admin', $data);
                } else {
                    if ($_FILES['image']['name']) {
                        $filename = time() . $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/about/' . $filename);
                        $image = $filename;
                    } else {
                        $image = "";
                    }
                    
                    $AboutUsData = [
                        'content' => $this->input->post('content'),
                        'image' => $image,
                        'content2' => $this->input->post('content2'),
                        'created' => date('d-m-Y h:i:s A')
                    ];
                }

                $result = $this->user_model->saveAry('aboutus', $AboutUsData);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                    return redirect('aboutus/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('aboutus/index');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                return redirect('aboutus/index');
            }
	}
        
        public function contentdata ($id)
        {
            if ($id != "") {
                $data['content'] = get_title('aboutus', ['id' => $id], 'content');
                return $this->load->view('admin_details/aboutus/info', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'aboutus/index');
            }
        }        
        
        public function content2data ($id)
        {
            if ($id != "") {
                $data['content2'] = get_title('aboutus', ['id' => $id], 'content2');
                return $this->load->view('admin_details/aboutus/info2', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'aboutus/index');
            }
        }
        
	public function edit($id)
	{
            if (isset($id) && $id != '') {
                $where = ['id' => $id];
                $return = $this->user_model->getRow('aboutus', $where);
                if ($return) {
                    $data['aboutus'] = $return;
                    $data['page'] = 'admin_details/aboutus/edit';
                    $this->load->view('admin', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Could Not Find The Data.', 'class' => 'danger']);
                    return redirect('aboutus/index');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect('aboutus/index');
            }
	}

	public function update()
	{
            if ($_POST) {
                $id = $this->input->post('id');
                $this->form_validation->set_rules('content', 'Content', 'trim|required');
                $this->form_validation->set_rules('content2', 'Content', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['aboutus'] = $this->user_model->getRow('aboutus', ['id' => $id]);
                    $data['page'] = "admin_details/aboutus/edit";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('admin', $data);
                } else {
                    if ($id) {
                        if ($_FILES['image']['name']) {
                            unlink('uploads/about/' . $this->input->post('oldimage'));
                            $filename = time() . $_FILES['image']['name'];
                            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/about/' . $filename);
                            $image = $filename;
                        } else {
                            $image = $this->input->post('oldimage');
                        }
                        
                        $AboutUsData = [
                            'content' => $this->input->post('content'),
                            'image' => $image,
                            'content2' => $this->input->post('content2'),
                            'updated' => date('d-m-Y h:i:s A')
                        ];
                        
                        $result = $this->user_model->updateAry('aboutus', $AboutUsData, ['id' => $id]);
                        if ($result) {
                            $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                            return redirect('aboutus/index');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                            return redirect('aboutus/index');
                        }
                    } else {
                        return redirect('aboutus/index');
                    }
                }
            }
	}
        
	public function delete($id)
	{
            if (isset($id) && $id != '') {
                $where = ['id' => $id];
                $aboutus = $this->user_model->getRow('aboutus', $where);
                $iname = $aboutus->image;
                $ifile = 'uploads/about/' . $iname;
                if (file_exists($ifile) && !empty($iname)) {
                    unlink($ifile);                            
                }

                $return = $this->user_model->deleteRow('aboutus', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect('aboutus/index');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('aboutus/index');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect('aboutus/index');
            }
	}
        
        public function CertificateGST ()
        {
            $data['certificates'] = $this->db->order_by('id', 'DESC')->get('aboutus_certificate')->result();
            $data['GSTInfo'] = $this->db->order_by('id', 'DESC')->get('gstinfo')->result();
            $data['page'] = "admin_details/aboutus/certificate-gst";
         
            return $this->load->view('admin', $data);
        }
        
        public function certificate ()
        {
            if ($_FILES) {
                if ($_FILES['certificate']['name']) {
                    $filename = time() . $_FILES['certificate']['name'];
                    move_uploaded_file($_FILES['certificate']['tmp_name'], 'uploads/about/' . $filename);
                    $certificate = $filename;
                } else {
                    $certificate = "";
                }
                    
                $CertificateData = [
                    'certificate' => $certificate,
                    'created' => date('d-m-Y h:i:s A')
                ];

                $result = $this->user_model->saveAry('aboutus_certificate', $CertificateData);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                    return redirect('aboutus/certificate-gst');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('aboutus/certificate-gst');
                }
            }
        }
        
        public function CertificateEdit ($id)
        {
            if ($id) {
                $data['certificate'] = $this->db->where('id', $id)->get('aboutus_certificate')->row();
                $data['page'] = "admin_details/aboutus/certificate-edit";
                return $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect('aboutus/certificate-gst');
            }
        }
        
        public function CertificateUpdate($id)
        {
            if ($_FILES) {
                if ($_FILES['certificate']['name']) {
                    unlink('uploads/about/' . $this->input->post('oldimage'));
                    $filename = time() . $_FILES['certificate']['name'];
                    move_uploaded_file($_FILES['certificate']['tmp_name'], 'uploads/about/' . $filename);
                    $certificate = $filename;
                } else {
                    $certificate = $this->input->post('oldimage');
                }

                $id = $this->input->post('id');
                $CertificateData = [
                    'certificate' => $certificate,
                    'updated' => date('d-m-Y h:i:s A')
                ];

                $result = $this->user_model->updateAry('aboutus_certificate', $CertificateData, ['id' => $id]);
                if ($result) {
                    $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                    return redirect('aboutus/certificate-gst');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('aboutus/certificate-edit/' . $id);
                }   
            }
        }
        
	public function CertificateDelete($id)
	{
            if (isset($id) && $id != '') {
                $where = ['id' => $id];
                $certificate = $this->user_model->getRow('aboutus_certificate', $where);
                $cname = $certificate->certificate;
                $cfile = 'uploads/about/' . $cname;
                if (file_exists($cfile) && !empty($cname)) {
                    unlink($cfile);                            
                }

                $return = $this->user_model->deleteRow('aboutus_certificate', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect('aboutus/certificate-gst');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('aboutus/certificate-gst');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect('aboutus/certificate-gst');
            }
	}
        
        public function gst ()
        {
            if ($_POST) {
                $this->form_validation->set_rules('cname', 'Company Name', 'trim|required');
                $this->form_validation->set_rules('state', 'State', 'trim|required');
                $this->form_validation->set_rules('location', 'Location', 'trim|required');
                $this->form_validation->set_rules('offices', 'Offices', 'trim|required');
                $this->form_validation->set_rules('provisional_id', 'Provisional Id', 'trim|required|min_length[15]|max_length[15]');
                $this->form_validation->set_rules('address', 'Address', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['page'] = "admin_details/aboutus/certificate-gst";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('admin', $data);
                } else {
                    if ($_FILES['arn_copy']['name']) {
                        $filename = time() . $_FILES['arn_copy']['name'];
                        move_uploaded_file($_FILES['arn_copy']['tmp_name'], 'uploads/about/' . $filename);
                        $arn_copy = $filename;
                    } else {
                        $arn_copy = "";
                    }

                    $GSTData = [
                        'cname' => $this->input->post('cname'),
                        'state' => $this->input->post('state'),
                        'location' => $this->input->post('location'),
                        'offices' => $this->input->post('offices'),
                        'provisional_id' => $this->input->post('provisional_id'),
                        'address' => $this->input->post('address'),
                        'arn_copy' => $arn_copy,
                        'created' => date('d-m-Y h:i:s A')
                    ];

                    $result = $this->user_model->saveAry('gstinfo', $GSTData);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Added Successfully.', 'class' => 'success']);
                        return redirect('aboutus/certificate-gst');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect('aboutus/certificate-gst');
                    }
                }
            }
        }
        
        public function GSTEdit ($id)
        {
            if ($id) {
                $data['gstdata'] = $this->db->where('id', $id)->get('gstinfo')->row();
                $data['page'] = "admin_details/aboutus/gst-edit";
                return $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect('aboutus/certificate-gst');
            }
        }
        
        public function GSTUpdate($id)
        {
            if ($_POST) {
                $this->form_validation->set_rules('cname', 'Company Name', 'trim|required');
                $this->form_validation->set_rules('state', 'State', 'trim|required');
                $this->form_validation->set_rules('location', 'Location', 'trim|required');
                $this->form_validation->set_rules('offices', 'Offices', 'trim|required');
                $this->form_validation->set_rules('provisional_id', 'Provisional Id', 'trim|required|min_length[15]|max_length[15]');
                $this->form_validation->set_rules('address', 'Address', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['gstdata'] = $this->db->where('id', $id)->get('gstinfo')->row();
                    $data['page'] = "admin_details/aboutus/gst-edit";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('admin', $data);
                } else {
                    
                    if ($_FILES['arn_copy']['name']) {
                        unlink('uploads/about/' . $this->input->post('oldcopy'));
                        $filename = time() . $_FILES['arn_copy']['name'];
                        move_uploaded_file($_FILES['arn_copy']['tmp_name'], 'uploads/about/' . $filename);
                        $arn_copy = $filename;
                    } else {
                        $arn_copy = $this->input->post('oldcopy');
                    }

                    $id = $this->input->post('id');
                    $GSTData = [
                        'cname' => $this->input->post('cname'),
                        'state' => $this->input->post('state'),
                        'location' => $this->input->post('location'),
                        'offices' => $this->input->post('offices'),
                        'provisional_id' => $this->input->post('provisional_id'),
                        'address' => $this->input->post('address'),
                        'arn_copy' => $arn_copy,
                        'updated' => date('d-m-Y h:i:s A')
                    ];

                    $result = $this->user_model->updateAry('gstinfo', $GSTData, ['id' => $id]);
                    if ($result) {
                        $this->session->set_flashdata(['msg' => 'Record Update Successfully.', 'class' => 'success']);
                        return redirect('aboutus/certificate-gst');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                        return redirect('aboutus/gst-edit/' . $id);
                    }
                }
            }
        }
        
	public function GSTDelete($id)
	{
            if (isset($id) && $id != '') {
                $where = ['id' => $id];
                $gst = $this->user_model->getRow('gstinfo', $where);
                $gname = $gst->arn_copy;
                $gfile = 'uploads/about/' . $gname;
                if (file_exists($gfile) && !empty($gname)) {
                    unlink($gfile);                            
                }

                $return = $this->user_model->deleteRow('gstinfo', $where);
                if ($return) {
                    $this->session->set_flashdata(['msg' => 'Record Delete Successfully.', 'class' => 'success']);
                    return redirect('aboutus/certificate-gst');
                } else {
                    $this->session->set_flashdata(['msg' => 'Failed.', 'class' => 'danger']);
                    return redirect('aboutus/certificate-gst');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Directly can not Acces.', 'class' => 'danger']);
                return redirect('aboutus/certificate-gst');
            }
	}
    }