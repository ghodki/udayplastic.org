<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    date_default_timezone_set('Asia/Kolkata');
    
    class WebProducts extends CI_Controller
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
            $data['products'] = $this->user_model->getResult('web_products');
            $data['page'] = "admin_details/webproducts/index";
            
            return $this->load->view('admin', $data);
        }
        
        public function create()
        {
            if ($_POST) {
                $this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[web_products.name]');
                $this->form_validation->set_rules('url_name', 'URL Name', 'trim|required');
                $this->form_validation->set_rules('description', 'Description', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $data['page'] = "admin_details/webproducts/product-form";
                    $data['error'] = validation_errors();
                    
                    return $this->load->view('admin', $data);
                } else {
                    if ($_FILES['image']['name']) {
                        $filename = time() . $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/webproducts/' . $filename);
                        $image = $filename;
                    } else {
                        $image = "";
                    }
                    
                    if ($_FILES['largeImage']['name']) {
                        $filename = time() . $_FILES['largeImage']['name'];
                        move_uploaded_file($_FILES['largeImage']['tmp_name'], 'uploads/webproductsLarge/' . $filename);
                        $largeImage = $filename;
                    } else {
                        $largeImage = "";
                    }
                    
                    $web_products_data = [
                        'name' => $this->input->post('name'),
                        'url_name' => $this->input->post('url_name'),
                        'image' => $image,
                        'largeImage' => $largeImage,
                        'IsUpcoming' => $this->input->post('IsUpcoming'),
                        'description' => $this->input->post('description'),
                        'created' => date('d-m-Y h:i:s A'),
                    ];
                    $result = $this->user_model->saveAry('web_products', $web_products_data);
                    
                    if (isset($_FILES['productImage'])) {
                        foreach ($_FILES['productImage']['name'] as $key => $File) {
                            $FileName = time() . $_FILES['productImage']['name'][$key];
                            move_uploaded_file($_FILES['productImage']['tmp_name'][$key], 'uploads/webProductsGallery/' . $FileName);
                            
                            $productsImages = [
                                "productId" => $result,
                                "productImage" => $FileName,
                            ];
                            $this->user_model->saveAry('web_product_images', $productsImages);
                        }
                    }
                    
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'webProducts');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'webProducts/create');
                    }
                }
            }
            
            $data['page'] = "admin_details/webproducts/product-form";
            
            return $this->load->view('admin', $data);
        }
        
        public function descriptiondata ($id)
        {
            if ($id != "") {
                $data['proudctInfo'] = $this->db->select('description, largeImage')->where('id', $id)->get('web_products')->row();
                $data['proudctImages'] = $this->db->select('productImage')->where('productId', $id)->get('web_product_images')->result();
                return $this->load->view('admin_details/webproducts/info', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webProducts');
            }
        }
        
        public function changestatus($id)
        {
            if ($id) {
                $ProductRecord = $this->user_model->getRow('web_products', ['id' => $id]);
                if ($ProductRecord) {
                    if ($ProductRecord->status == 1) {
                        $status = 0;
                    } else {
                        $status = 1;
                    }
                    $result = $this->user_model->updateAry('web_products', ['status' => $status, 'updated' => date('d-m-Y h:i:s A')], ['id' => $id]);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'webProducts');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'webProducts');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'webProducts');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webProducts');
            }
        }
        
        public function edit($id)
        {
            if ($id) {
                $data['id'] = $id;
                $data['productdata'] = $this->user_model->getRow('web_products', ['id' => $id]);
                $data['productImages'] = $this->db->where('productId', $id)->get('web_product_images')->result();
                $data['page'] = "admin_details/webproducts/product-form";
                return $this->load->view('admin', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webProducts');
            }
        }
        
        public function update($id)
        {            
            if ($id) {
                if ($_POST) {
                    $this->form_validation->set_rules('name', 'Name', 'trim|required');
                    $this->form_validation->set_rules('url_name', 'URL Name', 'trim|required');
                    $this->form_validation->set_rules('description', 'Description', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $data['id'] = $id;
                        $data['productdata'] = $this->user_model->getRow('web_products', ['id' => $id]);
                        $data['page'] = "admin_details/webproducts/product-form";
                        $data['error'] = validation_errors();

                        return $this->load->view('admin', $data);
                    } else {
                        $pid = $this->input->post('id');

                        if ($_FILES['image']['name']) {
                            unlink('uploads/webproducts/' . $this->input->post('oldimage'));
                            $filename = time() . $_FILES['image']['name'];
                            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/webproducts/' . $filename);
                            $image = $filename;
                        } else {
                            $image = $this->input->post('oldimage');
                        }
                        
                        if ($_FILES['largeImage']['name']) {
                            unlink('uploads/webproductsLarge/' . $this->input->post('oldImageLarge'));
                            $filename = time() . $_FILES['largeImage']['name'];
                            move_uploaded_file($_FILES['largeImage']['tmp_name'], 'uploads/webproductsLarge/' . $filename);
                            $largeImage = $filename;
                        } else {
                            $largeImage = $this->input->post('oldImageLarge');
                        }

                        $web_products_data = [
                            'name' => $this->input->post('name'),
                            'url_name' => $this->input->post('url_name'),
                            'image' => $image,
                            'largeImage' => $largeImage,
                            'IsUpcoming' => $this->input->post('IsUpcoming'),
                            'description' => $this->input->post('description'),
                            'updated' => date('d-m-Y h:i:s A'),
                        ];
                        $result = $this->user_model->updateAry('web_products', $web_products_data, ['id' => $pid]);
                        
                        if (!empty(array_filter($_FILES['productImage']['name']))) {
                            foreach ($_FILES['productImage']['name'] as $key => $File) {
                                move_uploaded_file($_FILES['productImage']['tmp_name'][$key], 'uploads/webProductsGallery/' . time() . $_FILES['productImage']['name'][$key]);
                                
                                $productImageId = $this->input->post('productImageId')[$key];
                                
                                $productsImages = [
                                    "productImage" => $_FILES['productImage']['name'][$key] ? time() . $_FILES['productImage']['name'][$key] : $this->input->post('oldFileName')[$key]
                                ];
                                $this->user_model->updateAry('web_product_images', $productsImages, ['id' => $productImageId, 'productId' => $pid]);
                            }
                        }
                        
                        if (isset($_FILES['newFileName'])) {
                            foreach ($_FILES['newFileName']['name'] as $key => $File) {
                                $FileName = time() . $_FILES['newFileName']['name'][$key];
                                move_uploaded_file($_FILES['newFileName']['tmp_name'][$key], 'uploads/webProductsGallery/' . $FileName);

                                $newProductImages = [
                                    "productId" => $pid,
                                    "productImage" => $FileName
                                ];
                                $this->user_model->saveAry('web_product_images', $newProductImages);
                            }
                        } 
                        
                        if (!empty($result)) {
                            $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'webProducts');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'webProducts/edit/' . $id);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webProducts');
            }
        }
        
        public function delete($id)
        {
            $webproducts = $this->user_model->getRow('web_products', ['id' => $id]);
            if ($webproducts) {
                $iname = $webproducts->image;
                $largeImage = $webproducts->largeImage;
                $ifile = 'uploads/webproducts/' . $iname;
                $largeFile = 'uploads/webproductsLarge/' . $largeImage;
                if (file_exists($ifile) && !empty($iname)) {
                    unlink($ifile);                            
                }
                if (file_exists($largeFile) && !empty($largeImage)) {
                    unlink($largeFile);                            
                }
                
                if ($this->user_model->deleteRow('web_products', ['id' => $id])) {                    
                    $productImages = $this->db->select('productImage')->where('productId', $id)->get('web_product_images')->result();
                    foreach ($productImages as $key => $ImageData) {
                        $piname = $ImageData->productImage;
                        $pfile = 'uploads/webProductsGallery/' . $piname;
                        if (file_exists($pfile) && !empty($piname)) {
                            unlink($pfile);                            
                        }
                    }
                    $this->user_model->deleteRow('web_product_images', ['productId' => $id]);
                    
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'webProducts');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'webProducts');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webProducts');
            }
        }
        
        public function imageDelete($id)
        {
            if ($id) {
                $productImage = $this->db->select('productId, productImage')->where('id', $id)->get('web_product_images')->row();
                $piname = $productImage->productImage;
                $pfile = 'uploads/webProductsGallery/' . $piname;
                if (file_exists($pfile) && !empty($piname)) {
                    unlink($pfile);                            
                }

                if ($this->user_model->deleteRow('web_product_images', ['id' => $id])) {
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'webProducts/edit/' . $productImage->productId);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect(base_url() . 'webProducts/edit/' . $productImage->productId);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'webProducts/edit/' . $productImage->productId);
            }
        }
    }