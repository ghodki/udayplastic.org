<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    date_default_timezone_set('Asia/Kolkata');
    
    class WebDownloads extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url']);

            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                redirect('index');
            }
        }
        
        public function index()
        {
            $data['downloads'] = $this->user_model->getResult('downloads');
            $data['page'] = "admin_details/webdownloads/index";
            
            return $this->load->view('admin', $data);
        }
        
        public function create()
        {
            if ($_POST) {
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['page'] = "admin_details/webdownloads/download-form";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('admin', $data);
                } else {
                    if ($_FILES['image']['name']) {
                        $filename = time() . $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/webdownloads/' . $filename);
                        $image = $filename;
                    } else {
                        $image = "";
                    }
                    if ($_FILES['file']['name']) {
                        $filename2 = time() . $_FILES['file']['name'];
                        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/webdownloads/' . $filename2);
                        $file = $filename2;
                    } else {
                        $file = "";
                    }
                    
                    $downloadsdata = [
                        'name' => $this->input->post('name'),
                        'image' => $image,
                        'file' => $file,
                        'created' => date('d-m-Y h:i:s A'),
                    ];
                    $result = $this->user_model->saveAry('downloads', $downloadsdata);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'webDownloads');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'webDownloads/create');
                    }
                }
            }
            
            $data['page'] = "admin_details/webdownloads/download-form";
            
            return $this->load->view('admin', $data);
        }
        
        public function changestatus($id)
        {
            if ($id) {
                $DownloadRecord = $this->user_model->getRow('downloads', ['id' => $id]);
                if ($DownloadRecord) {
                    if ($DownloadRecord->status == 1) {
                        $status = 0;
                    } else {
                        $status = 1;
                    }
                    $result = $this->user_model->updateAry('downloads', ['status' => $status, 'updated' => date('d-m-Y h:i:s A')], ['id' => $id]);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'webDownloads');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'webDownloads');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'webDownloads');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webDownloads');
            }
        }
        
        public function edit($id)
        {
            if ($id) {
                $data['id'] = $id;
                $data['downloaddata'] = $this->user_model->getRow('downloads', ['id' => $id]);
                $data['page'] = "admin_details/webdownloads/download-form";

                return $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webDownloads');
            }
        }
        
        public function update($id)
        {            
            if ($id) {
                if ($_POST) {
                    $this->form_validation->set_rules('name', 'Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $data['id'] = $id;
                        $data['productdata'] = $this->user_model->getRow('downloads', ['id' => $id]);
                        $data['page'] = "admin_details/webdownloads/download-form";
                        $data['error'] = validation_errors();

                        return $this->load->view('admin', $data);
                    } else {
                        $did = $this->input->post('id');

                        if ($_FILES['image']['name']) {
                            unlink('uploads/webdownloads/' . $this->input->post('oldimage'));
                            $filename = time() . $_FILES['image']['name'];
                            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/webdownloads/' . $filename);
                            $image = $filename;
                        } else {
                            $image = $this->input->post('oldimage');
                        }
                        
                        if ($_FILES['file']['name']) {
                            unlink('uploads/webdownloads/' . $this->input->post('oldfile'));
                            $filename2 = time() . $_FILES['file']['name'];
                            move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/webdownloads/' . $filename2);
                            $file = $filename2;
                        } else {
                            $file = $this->input->post('oldfile');
                        }

                        $downloaddata = [
                            'name' => $this->input->post('name'),
                            'image' => $image,
                            'file' => $file,
                            'updated' => date('d-m-Y h:i:s A'),
                        ];
                        $result = $this->user_model->updateAry('downloads', $downloaddata, ['id' => $did]);
                        if (!empty($result)) {
                            $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'webDownloads');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'webDownloads/edit/' . $id);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webDownloads');
            }
        }
        
        public function delete($id)
        {
            $webdownloads = $this->user_model->getRow('downloads', ['id' => $id]);
            if ($webdownloads) {
                $iname = $webdownloads->image;
                $ifile = 'uploads/webdownloads/' . $iname;
                if (file_exists($ifile) && !empty($iname)) {
                    unlink($ifile);                            
                }
                
                $fname = $webdownloads->file;
                $ffile = 'uploads/webdownloads/' . $fname;
                if (file_exists($ffile) && !empty($fname)) {
                    unlink($ffile);                            
                }
                if ($this->user_model->deleteRow('downloads', ['id' => $id])) {
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'webDownloads');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'webDownloads');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webDownloads');
            }
        }
    }