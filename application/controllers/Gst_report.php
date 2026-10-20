<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Gst_report extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper(['form', 'url', 'action_helper']);
            $this->load->model('user_model');
            if ($this->session->userdata('user_id') === null) {
                return redirect(base_url() . 'index');
            }
        }
    
        public function index()
        {
            $data['page'] = 'gst_report/index';
            $this->load->view('inventory_sep/inventory', $data);
        }
    
        public function gstr1($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $result = $this->db->get('sales')->result();
                $data['result'] = $result;
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['page'] = 'gst_report/gstr1';
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
            }
            $this->load->view('inventory_sep/inventory', $data);
        }
    
        public function gstr3b($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $result = $this->db->get('sales')->result();
                $data['result'] = $result;
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['page'] = 'gst_report/gstr3b';
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
            }
            $this->load->view('inventory_sep/inventory', $data);
        }
        // GSTR1 GSTR3 
    
        public function b2b_report($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['page'] = 'gst_report/b2b';
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
            }
            $this->load->view('inventory_sep/inventory', $data);
        }
    
        public function b2c_large_report($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['page'] = 'gst_report/b2c_large';
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
            }
            $this->load->view('inventory_sep/inventory', $data);
        }
    
        public function hsn($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['page'] = 'gst_report/hsn';
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
            }
            $this->load->view('inventory_sep/inventory', $data);
        }
    
        public function b2c_small($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['page'] = 'gst_report/b2c';
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
            }
            $this->load->view('inventory_sep/inventory', $data);
        }
    
        // Details of invoice number
    
        public function b2b_gst_invoice($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['gstNo!=' => '']);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $result = $this->db->get('sales')->result();
                $data['result'] = $result;
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $this->load->view('inventory_sep/gst_report/popup', $data);
            } else {
                $this->load->view('admin_details/error/page_not_found');
            }
        }
    
        public function b2c_large_gst_invoice($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['gstNo!=' => '']);
                $this->db->where('totalValue' > 250000);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $result = $this->db->get('sales')->result();
                $data['result'] = $result;
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $this->load->view('inventory_sep/gst_report/popup', $data);
            } else {
                $this->load->view('admin_details/error/page_not_found');
            }
        }
    
        public function b2c_small_gst_invoice($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['gstNo' => '']);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $result = $this->db->get('sales')->result();
                $data['result'] = $result;
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $this->load->view('inventory_sep/gst_report/popup', $data);
            } else {
                $this->load->view('admin_details/error/page_not_found');
            }
        }
        
        public function hsn_gst_invoice($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $result = $this->db->get('sales')->result();
                $data['result'] = $result;
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $this->load->view('inventory_sep/gst_report/popup', $data);
            } else {
                $this->load->view('admin_details/error/page_not_found');
            }
        }
    
        // JSON REPORT START
        public function b2b_json_report($start_date = null, $end_date = null)
        {
            ini_set('serialize_precision', '-1');
            
            if ($start_date && $end_date) {
                $this->db->select('*');
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where(['gstNo!=' => '']);
                $totals_data = $this->db->get('sales')->result();
                
                if ($this->session->userdata('BranchRole')) {
                    $gstNo = get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'GST');
                } else {
                    $gst_where = ['id' => $this->session->userdata('comp_id')];
                    $gstNo = get_title('inventory_billing', $gst_where, 'gst_no');
                }
                
                $json_m_karna_h['gstin'] = $gstNo;
                $json_m_karna_h['fp'] = date('mY', strtotime($start_date));
                $json_m_karna_h['gt'] = sprintf('%.2f', 0.00);
                $json_m_karna_h['cur_gt'] = sprintf('%.2f', 0.00);
                $b2b_array = [];
                $overall_data = [];
                if ($totals_data) {
                    foreach ($totals_data as $b2b_data) {
                        $tax = get_title('invoice_product', ['InvoiceNo' => $b2b_data->invoiceNumber], 'tax');
                        $tstr = explode(".", $tax);
                        
                        $b2b_array['ctin'] = get_title('parties', ['id' => $b2b_data->customerId], 'gst');
                        if ($b2b_data->totalcgst > 0 && $b2b_data->totalsgst > 0) {
                            $b2b_array['inv'] = array(
                                'inum' => $b2b_data->invoiceNumber,
                                'idt' => date('d-m-Y', strtotime($b2b_data->invoiceDate)),
                                'val' => sprintf('%.2f', $b2b_data->totalValue),
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'rchrg' => 'N',
                                'itms' => array(
                                    'num' => 1,
                                    'itm_det' => array(
                                        'txval' => sprintf('%.2f', $b2b_data->taxableAmount),
                                        'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                        'camt' => $b2b_data->totalcgst > 0 ? sprintf('%.2f', $b2b_data->totalcgst) : sprintf('%.2f', 0.00),
                                        'samt' => $b2b_data->totalsgst > 0 ? sprintf('%.2f', $b2b_data->totalsgst) : sprintf('%.2f', 0.00),
                                        'csamt' => sprintf("##%.2f##", 0.00)
                                    ),
                                ),
                            );
                        } else {
                            $b2b_array['inv'] = array(
                                'inum' => $b2b_data->invoiceNumber,
                                'idt' => date('d-m-Y', strtotime($b2b_data->invoiceDate)),
                                'val' => sprintf('%.2f', $b2b_data->totalValue),
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'rchrg' => 'N',
                                'itms' => array(
                                    'num' => 1,
                                    'itm_det' => array(
                                        'txval' => sprintf('%.2f', $b2b_data->taxableAmount),
                                        'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                        'iamt' => $b2b_data->totaligst > 0 ? sprintf('%.2f', $b2b_data->totaligst) : sprintf('%.2f', 0.00),
                                        'csamt' => sprintf("##%.2f##", 0.00)
                                    ),
                                ),
                            );
                        }
                        $overall_data[] = $b2b_array;
                    }
                }
                $json_m_karna_h['b2b'] = $overall_data;
                // format
                // {"gstin":"29AABCU7671H1ZW","fp":"","gt":0,"cur_gt":0,"version":"GST2.0","hash":"hash","b2cs":[{"sply_ty":"INTRA","rt":18,"typ":"OE","pos":"29","txval":500,"camt":45,"samt":45,"iamt":0,"csamt":0}]}
                $responce = preg_replace('/:"(\d+\.\d+)",/', ':$1,', json_encode($json_m_karna_h, JSON_UNESCAPED_SLASHES));
                $responce = str_replace('"##', '', $responce);
                $responce = str_replace('##"', '', $responce);
                
                $this->load->helper('download');
                force_download($gstNo . "_b2b.json", $responce);
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
                $this->load->view('inventory', $data);
            }
        }
    
        public function b2c_large_json_report($start_date = null, $end_date = null)
        {
            ini_set('serialize_precision', '-1');
            
            if ($start_date && $end_date) {
                $this->db->select('*');
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')){
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where(['gstNo!=' => '']);
                $this->db->where('totalValue' > 250000);
                $totals_data = $this->db->get('sales')->result();
                
                if ($this->session->userdata('BranchRole')){
                    $gstNo = get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'GST');
                } else {
                    $gst_where = ['id' => $this->session->userdata('comp_id')];
                    $gstNo = get_title('inventory_billing', $gst_where, 'gst_no');
                }
                
                $json_m_karna_h['gst'] = $gstNo;
                $json_m_karna_h['fp'] = date('mY', strtotime($start_date));
                $json_m_karna_h['gt'] = sprintf('%.2f', 0.00);
                $json_m_karna_h['cur_gt'] = sprintf('%.2f', 0.00);
                $b2b_array = [];
                $overall_data = [];
                if ($totals_data) {
                    foreach ($totals_data as $b2b_data) {
                        $tax = get_title('invoice_product', ['InvoiceNo' => $b2b_data->invoiceNumber], 'tax');
                        $tstr = explode(".", $tax);
                        
                        $b2b_array['ctin'] = get_title('parties', ['id' => $b2b_data->customerId], 'gst');
                        if ($b2b_data->totalcgst > 0 && $b2b_data->totalsgst > 0) {
                            $b2b_array['inv'] = array(
                                'inum' => $b2b_data->invoiceNumber,
                                'idt' => date('d-m-Y', strtotime($b2b_data->invoiceDate)),
                                'val' => sprintf('%.2f', $b2b_data->totalValue),
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'rchrg' => 'N',
                                'itms' => array(
                                    'num' => 1,
                                    'itm_det' => array(
                                        'txval' => sprintf('%.2f', $b2b_data->taxableAmount),
                                        'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                        'camt' => $b2b_data->totalcgst > 0 ? sprintf('%.2f', $b2b_data->totalcgst) : sprintf('%.2f', 0.00),
                                        'samt' => $b2b_data->totalsgst > 0 ? sprintf('%.2f', $b2b_data->totalsgst) : sprintf('%.2f', 0.00),
                                        'csamt' => sprintf("##%.2f##", 0.00)
                                    ),
                                ),
                            );
                        } else {
                            $b2b_array['inv'] = array(
                                'inum' => $b2b_data->invoiceNumber,
                                'idt' => date('d-m-Y', strtotime($b2b_data->invoiceDate)),
                                'val' => sprintf('%.2f', $b2b_data->totalValue),
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'rchrg' => 'N',
                                'itms' => array(
                                    'num' => 1,
                                    'itm_det' => array(
                                        'txval' => sprintf('%.2f', $b2b_data->taxableAmount),
                                        'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                        'iamt' => $b2b_data->totaligst > 0 ? sprintf('%.2f', $b2b_data->totaligst) : sprintf('%.2f', 0.00),
                                        'csamt' => sprintf("##%.2f##", 0.00)
                                    ),
                                ),
                            );
                        }
                        $overall_data[] = $b2b_array;
                    }
                }
                $json_m_karna_h['b2cl'] = $overall_data;
                // format
                // {"gstin":"29AABCU7671H1ZW","fp":"","gt":0,"cur_gt":0,"version":"GST2.0","hash":"hash","b2cs":[{"sply_ty":"INTRA","rt":18,"typ":"OE","pos":"29","txval":500,"camt":45,"samt":45,"iamt":0,"csamt":0}]}
                $responce = preg_replace('/:"(\d+\.\d+)",/', ':$1,', json_encode($json_m_karna_h, JSON_UNESCAPED_SLASHES));
                $responce = str_replace('"##', '', $responce);
                $responce = str_replace('##"', '', $responce);
                $this->load->helper('download');
                force_download($gstNo . "_b2cl.json", $responce);
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
                $this->load->view('inventory', $data);
            }
        }
    
        public function hsn_json_report($start_date = null, $end_date = null)
        {
            ini_set('serialize_precision', '-1');
            
            if ($start_date && $end_date) {
                $this->db->select('*');
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')){
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $totals_data = $this->db->get('sales')->result();
                
                if ($this->session->userdata('BranchRole')) {
                    $gstNo = get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'GST');
                } else {
                    $gst_where = ['id' => $this->session->userdata('comp_id')];
                    $gstNo = get_title('inventory_billing', $gst_where, 'gst_no');
                }   

                $json_m_karna_h['gst'] = $gstNo;
                $json_m_karna_h['fp'] = date('mY', strtotime($start_date));
                $json_m_karna_h['gt'] = sprintf('%.2f', 0.00);
                $json_m_karna_h['cur_gt'] = sprintf('%.2f', 0.00);
                $b2b_array = [];
                $overall_data = [];
                if ($totals_data) {
                    foreach ($totals_data as $b2b_data) {
                        $tax = get_title('invoice_product', ['InvoiceNo' => $b2b_data->invoiceNumber], 'tax');
                        $tstr = explode(".", $tax);
                        
                        $b2b_array['ctin'] = get_title('parties', ['id' => $b2b_data->customerId], 'gst');
                        if ($b2b_data->totalcgst > 0 && $b2b_data->totalsgst > 0) {
                            $b2b_array['inv'] = array(
                                'inum' => $b2b_data->invoiceNumber,
                                'idt' => date('d-m-Y', strtotime($b2b_data->invoiceDate)),
                                'val' => sprintf('%.2f', $b2b_data->totalValue),
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'rchrg' => 'N',
                                'itms' => array(
                                    'num' => 1,
                                    'itm_det' => array(
                                        'txval' => sprintf('%.2f', $b2b_data->taxableAmount),
                                        'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                        'camt' => $b2b_data->totalcgst > 0 ? sprintf('%.2f', $b2b_data->totalcgst) : sprintf('%.2f', 0.00),
                                        'samt' => $b2b_data->totalsgst > 0 ? sprintf('%.2f', $b2b_data->totalsgst) : sprintf('%.2f', 0.00),
                                        'csamt' => sprintf("##%.2f##", 0.00)
                                    ),
                                ),
                            );
                        } else {
                            $b2b_array['inv'] = array(
                                'inum' => $b2b_data->invoiceNumber,
                                'idt' => date('d-m-Y', strtotime($b2b_data->invoiceDate)),
                                'val' => sprintf('%.2f', $b2b_data->totalValue),
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'rchrg' => 'N',
                                'itms' => array(
                                    'num' => 1,
                                    'itm_det' => array(
                                        'txval' => sprintf('%.2f', $b2b_data->taxableAmount),
                                        'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                        'iamt' => $b2b_data->totaligst > 0 ? sprintf('%.2f', $b2b_data->totaligst) : sprintf('%.2f', 0.00),
                                        'csamt' => sprintf("##%.2f##", 0.00)
                                    ),
                                ),
                            );
                        }
                        $overall_data[] = $b2b_array;
                    }
                }
                $json_m_karna_h['hsn'] = $overall_data;
                // format
                // {"gstin":"29AABCU7671H1ZW","fp":"","gt":0,"cur_gt":0,"version":"GST2.0","hash":"hash","b2cs":[{"sply_ty":"INTRA","rt":18,"typ":"OE","pos":"29","txval":500,"camt":45,"samt":45,"iamt":0,"csamt":0}]}
                $responce = preg_replace('/:"(\d+\.\d+)",/', ':$1,', json_encode($json_m_karna_h, JSON_UNESCAPED_SLASHES));
                $responce = str_replace('"##', '', $responce);
                $responce = str_replace('##"', '', $responce);
                
                $this->load->helper('download');
                force_download($gstNo . "_hsn.json", $responce);
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
                $this->load->view('inventory', $data);
            }
        }
    
        public function b2c_small_json_report($start_date = null, $end_date = null)
        {
            ini_set('serialize_precision', '-1');
            
            if ($start_date && $end_date) {
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                $this->db->select('*');
                $this->db->select(['SUM(totalValue) as stotalValue', 'SUM(taxableAmount) as staxableAmount', 'SUM(totalTax) as stotalTax', 'SUM(totaligst) as stotaligst', 'SUM(totalcgst) as stotalcgst', 'SUM(totalsgst) as stotalsgst']);
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')){
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where(['gstNo' => '']);
                $totals_data = $this->db->get('sales')->result();
                
                if ($this->session->userdata('BranchRole')) {
                    $gstNo = get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'GST');
                } else {
                    $gst_where = ['id' => $this->session->userdata('comp_id')];
                    $gstNo = get_title('inventory_billing', $gst_where, 'gst_no');
                }

                $json_m_karna_h['gstin'] = $gstNo;
                $json_m_karna_h['fp'] = date('mY', strtotime($start_date));
                $json_m_karna_h['gt'] = sprintf('%.2f', 0.00);
                $json_m_karna_h['cur_gt'] = sprintf('%.2f', 0.00);
                $b2b_array = [];
                if ($totals_data) {
                    foreach ($totals_data as $b2b_data) {
                        $tax = get_title('invoice_product', ['InvoiceNo' => $b2b_data->invoiceNumber], 'tax');
                        $tstr = explode(".", $tax);
                        if ($b2b_data->stotalcgst > 0 && $b2b_data->stotalcgst > 0) {
                            $b2b_array = array(
                                'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                'sply_ty' => "INTRA",
                                'typ' => "OE",
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'txval' => sprintf('%.2f', $b2b_data->staxableAmount),
                                'camt' => $b2b_data->stotalcgst > 0 ? sprintf('%.2f', $b2b_data->stotalcgst) : sprintf('%.2f', 0.00),
                                'samt' => $b2b_data->stotalsgst > 0 ? sprintf('%.2f', $b2b_data->stotalsgst) : sprintf('%.2f', 0.00),
                                'csamt' => sprintf("##%.2f##", 0.00)
                            );
                        } else {
                            $b2b_array = array(
                                'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                'sply_ty' => "INTRA",
                                'typ' => "OE",
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'txval' => sprintf('%.2f', $b2b_data->staxableAmount),
                                'iamt' => $b2b_data->stotaligs > 0 ? sprintf('%.2f', $b2b_data->stotaligs) : sprintf('%.2f', 0.00),
                                'csamt' => sprintf("##%.2f##", 0.00)
                            );  
                        }
                    }
                }
                $json_m_karna_h['b2cs'] = $b2b_array;
                // format
                // {"gstin":"29AABCU7671H1ZW","fp":"","gt":0,"cur_gt":0,"version":"GST2.0","hash":"hash","b2cs":[{"sply_ty":"INTRA","rt":18,"typ":"OE","pos":"29","txval":500,"camt":45,"samt":45,"iamt":0,"csamt":0}]}
                $responce = preg_replace('/:"(\d+\.\d+)",/', ':$1,', json_encode($json_m_karna_h, JSON_UNESCAPED_SLASHES));
                $responce = str_replace('"##', '', $responce);
                $responce = str_replace('##"', '', $responce);
                
                $this->load->helper('download');
                force_download($gstNo . "_b2cs.json", $responce);
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
                $this->load->view('inventory', $data);
            }
        }
        // JSON REPORT END
    
        public function all_json($start_date = null, $end_date = null)
        {
            ini_set('serialize_precision','-1');
            
            if ($start_date && $end_date) {
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                
                // b2b start
                $this->db->select('*');
                $this->db->where('invoiceDate >= ', $start_date);
                $this->db->where('invoiceDate <= ', $end_date);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('gstNo != ', '');
                $this->db->group_by('gstNo');
                $totals_data = $this->db->get('sales')->result();
                
                if ($this->session->userdata('BranchRole')) {
                    $gstNo = get_title('companybranch', ['id' => $this->session->userdata('CompBranchId')], 'GST');
                } else {
                    $gst_where = ['id' => $this->session->userdata('comp_id')];
                    $gstNo = get_title('inventory_billing', $gst_where, 'gst_no');
                }

                $json_m_karna_h['gstin'] = $gstNo;
                $json_m_karna_h['fp'] = date('mY', strtotime($start_date));
                $json_m_karna_h['gt'] = sprintf('%.2f', 0.00);
                $json_m_karna_h['cur_gt'] = sprintf('%.2f', 0.00);
                $b2b_array = [];
                $overall_data = [];
                if ($totals_data) {
                    foreach ($totals_data as $b2b_data) {
                        $CustomerGSTNumber = get_title('parties', ['id' => $b2b_data->customerId], 'gst');
                        $b2b_array['ctin'] = $CustomerGSTNumber;
                        $amanJii = [];
                        $this->db->select('*');
                        $this->db->where('invoiceDate >= ', $start_date);
                        $this->db->where('invoiceDate <= ', $end_date);
                        $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                        if ($this->session->userdata('BranchRole')) {
                            $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                        }
                        $this->db->where('gstNo', $CustomerGSTNumber);
                        $gst_wise_date = $this->db->get('sales')->result();
                        
                        foreach ($gst_wise_date as $gstInvoice) {
                            $tax = get_title('invoice_product', ['InvoiceNo' => $gstInvoice->invoiceNumber], 'tax');
                            $tstr = explode(".", $tax);
                        
                            if ($gstInvoice->totalcgst > 0 && $gstInvoice->totalsgst > 0) {
                                $amanJii[] =   array(
                                    'inum' => $gstInvoice->invoiceNumber,
                                    'idt' => date('d-m-Y', strtotime($gstInvoice->invoiceDate)),
                                    'val' => sprintf('%.2f', $gstInvoice->totalValue),
                                    'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $gstInvoice->customerId], 'state')], 'code'),
                                    'rchrg' => 'N',
                                    'itms' => array(
                                        array(
                                            'num' => 1,
                                            'itm_det' => array(
                                                'txval' => sprintf('%.2f', $gstInvoice->taxableAmount),
                                                'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                                'camt' => $gstInvoice->totalcgst > 0 ? sprintf('%.2f', $gstInvoice->totalcgst) : sprintf('%.2f', 0.00),
                                                'samt' => $gstInvoice->totalsgst > 0 ? sprintf('%.2f', $gstInvoice->totalsgst) : sprintf('%.2f', 0.00),
                                                'csamt' => sprintf("##%.2f##", 0.00)
                                            )
                                        )
                                    ),
                                    'inv_typ' => 'R',
                                );
                            } else {
                                $amanJii[] =   array(
                                    'inum' => $gstInvoice->invoiceNumber,
                                    'idt' => date('d-m-Y', strtotime($gstInvoice->invoiceDate)),
                                    'val' => sprintf('%.2f', $gstInvoice->totalValue),
                                    'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $gstInvoice->customerId], 'state')], 'code'),
                                    'rchrg' => 'N',
                                    'itms' => array(
                                        array(
                                            'num' => 1,
                                            'itm_det' => array(
                                                'txval' => sprintf('%.2f', $gstInvoice->taxableAmount),
                                                'rt' => floatval($tstr[1] > 0.00 ? $tax : $tstr[0]),
                                                'iamt' => $gstInvoice->totaligst > 0 ? sprintf('%.2f', $gstInvoice->totaligst) : sprintf('%.2f', 0.00),
                                                'csamt' => sprintf("##%.2f##", 0.00)
                                            )
                                        )
                                    ),
                                    'inv_typ' => 'R',
                                );
                            }
                        }
                        $b2b_array['inv'] = $amanJii;
                        $overall_data[] = $b2b_array;
                    }
                }
                $json_m_karna_h['b2b'] = $overall_data;
                // b2b end
                
                // b2c start
                $this->db->select('*');
                $this->db->select_sum('invoice_product.amount');
                $this->db->select_sum('invoice_product.purchase_price');
                $this->db->select_sum('invoice_product.cgst');
                $this->db->select_sum('invoice_product.sgst');
                $this->db->select_sum('invoice_product.igst');
                $this->db->select_sum('sales.cess');
                $this->db->from('invoice_product');
                $this->db->join('sales', 'invoice_product.invoiceID = sales.id', 'LEFT');
                $this->db->group_by('invoice_product.tax');
                $this->db->where('invoice_product.type', 'Sale');
                $this->db->where('invoice_product.status', 1);
                $this->db->where('sales.gstNo', '');
                $this->db->where(['sales.user_id' => $this->session->userdata('user_id'), 'sales.comp_id' => $this->session->userdata('comp_id'), 'sales.status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('sales.CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('sales.invoiceDate >= ', $start_date);
                $this->db->where('sales.invoiceDate <= ', $end_date);
                $totals_data = $this->db->get()->result();
    
                $b2b_array = [];
                $overall_data = [];
                if ($totals_data) {
                    foreach ($totals_data as $b2b_data) {
                        $tstr = explode(".", $b2b_data->tax);
                        if ($b2b_data->cgst > 0 && $b2b_data->sgst > 0) {
                            $b2b_array[] = array(
                                'rt' => floatval($tstr[1] > 0.00 ? $b2b_data->tax : $tstr[0]),
                                'sply_ty' => 'INTRA',
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'typ' => 'OE',
                                'txval' => sprintf('%.2f', $b2b_data->purchase_price),
                                'camt' => $b2b_data->cgst > 0 ? sprintf('%.2f', $b2b_data->cgst) : sprintf('%.2f', 0.00),
                                'samt' => $b2b_data->sgst > 0 ? sprintf('%.2f', $b2b_data->sgst) : sprintf('%.2f', 0.00),
                                'csamt' => sprintf("##%.2f##", 0.00)
                            );
                        } else {
                            $b2b_array[] = array(
                                'rt' => floatval($tstr[1] > 0.00 ? $b2b_data->tax : $tstr[0]),
                                'sply_ty' => 'INTRA',
                                'pos' => get_title('state_list', ['id' => get_title('parties', ['id' => $b2b_data->customerId], 'state')], 'code'),
                                'typ' => 'OE',
                                'txval' => sprintf('%.2f', $b2b_data->purchase_price),
                                'iamt' => $b2b_data->igst > 0 ? sprintf('%.2f', $b2b_data->igst) : sprintf('%.2f', 0.00),
                                'csamt' => sprintf("##%.2f##", 0.00)
                            );
                        }
                        $overall_data[] = $b2b_array;
                    }
                }
                $json_m_karna_h['b2cs'] = $b2b_array;
                // b2c end
    
                $total_amount = 0;
                $total_tax = 0;
                $total_taxAbleAmount = 0;
                $this->db->select('*');
                $this->db->select('hsn');
                $this->db->select('invoiceID');
                $this->db->select('productname');
                $this->db->select_sum('price');
                $this->db->select_sum('netAmount');
                $this->db->select_sum('qty');
                $this->db->select_sum('sgst');
                $this->db->select_sum('cgst');
                $this->db->select_sum('igst');
                $this->db->select_sum('taxAmount');
                $this->db->select_sum('purchase_price');
                // taxable amount
                $this->db->from('invoice_product');
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('status', 1);
                $this->db->where('invoiceDate >=', $start_date);
                $this->db->where('invoiceDate <=', $end_date);
                $this->db->where('type', 'Sale');
                $this->db->group_by("hsn", "tax");
                $result_of_products = $this->db->get()->result();
                $overall_data = [];
                $overall_data['data'] = '';
                if (!empty($result_of_products)) {
                    $aman = [];
                    $n = 0;
                    foreach ($result_of_products as $key => $tax) {
                        $tstr = explode(".", $tax->tax);
                        $n++;
                        $aman[] = array(
                            'num' => $n,
                            'hsn_sc' => $tax->hsn,
                            'uqc' => get_title('unit', ['id' => get_title('product', ['id' => $tax->product_id], 'unit')], 'uqc_code'),
                            'qty' => round($tax->qty, 2),
                            'rt' => floatval($tstr[1] > 0.00 ? $tax->tax : $tstr[0]),
                            'txval' => sprintf('%.2f', $tax->purchase_price),
                            'iamt' => $tax->igst > 0 ? sprintf('%.2f', $tax->igst) : sprintf('%.2f', 0.00),
                            'camt' => $tax->cgst > 0 ? sprintf('%.2f', $tax->cgst) : sprintf('%.2f', 0.00),
                            'samt' => $tax->sgst > 0 ? sprintf('%.2f', $tax->sgst) : sprintf('%.2f', 0.00),
                            'csamt' => sprintf("##%.2f##", 0.00)
                        );
                        $overall_data['data'] = $aman;
                    }
                }
    
                $overall_data['data'] = $overall_data;
                $json_m_karna_h['hsn'] = $overall_data['data'];
                
                $responce = preg_replace('/:"(\d+\.\d+)",/', ':$1,', json_encode($json_m_karna_h, JSON_UNESCAPED_SLASHES));
                $responce = str_replace('"##', '', $responce);
                $responce = str_replace('##"', '', $responce);
                
                $this->load->helper('download');
                force_download($gstNo . "all.json", $responce);
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
                $this->load->view('inventory', $data);
            }
        }
    
        public function all_excel_file_data($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $data['page'] = 'inventory_sep/gst_report/all_data_report';
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
            }
            $this->load->view('inventory', $data);
        }
    
        public function all_excel($start_date = null, $end_date = null)
        {
            if ($start_date && $end_date) {
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
                $currrent_month = date("F", strtotime($start_date));
                
                if ($this->session->userdata('BranchRole')) {
                    $gst_no = get_title('companybranch', ['id' => $this->session->userdata('CompBranchId')], 'GST');
                } else {
                    $gst_where = ['id' => $this->session->userdata('comp_id')];
                    $gst_no = get_title('inventory_billing', $gst_where, 'gst_no');
                }
                $current_year = get_title('year', ['id' => $this->session->userdata('financial_year')], 'year');
    
                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();
                // Create a first sheet, representing sales data
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'GSTIN/UIN of Recipient');
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Receiver Name');
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Invoice Number');
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Invoice date');
                $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Invoice Value');
                $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Place Of Supply');
                $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Reverse Charge');
                $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Applicable % of Tax Rate');
                $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Invoice Type');
                $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'E-Commerce GSTIN');
                $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Rate');
                $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Taxable Value');
                $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Cess Amount');
                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('B2b');
    
                $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray(
                    array(
                        'font' => array(
                            'color' => array('rgb' => 'red'),
                        ),
                        'alignment' => array(
                            'text' => 'center'
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => 'red')
                            )
                        )
                    )
                );
                $rowCount = 2;
    
                $this->db->where(['invoiceDate >=' => $start_date]);
                $this->db->where(['invoiceDate <=' => $end_date]);
                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where(['gstNo!=' => '']);
                $totals_data = $this->db->get('sales')->result();
                if ($totals_data) {
                    foreach ($totals_data as $key => $val) {
                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, get_title('parties', ['id' => $val->customerId], 'gst'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, get_title('parties', ['id' => $val->customerId], 'name'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val->invoiceNumber);
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val->invoiceDate);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val->totalValue);
                        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, get_title('parties', ['id' => $val->customerId], 'state_code') . "-" . get_title('state_list', ['id' => get_title('parties', ['id' => $val->customerId], 'state')], 'state'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "N");
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, "");
                        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, "Regular");
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, "");
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, get_title('invoice_product', ['invoiceID' => $val->id], 'tax'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $val->taxableAmount);
                        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $val->cess);
                        $rowCount++;
                    }
                }
    
                // Create a new worksheet, after the default sheet
                $objPHPExcel->createSheet();
                // Add some data to the second sheet, resembling some different data types
                $objPHPExcel->setActiveSheetIndex(1);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Type');
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Place Of Supply');
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Applicable % of Tax Rate');
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Rate');
                $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Taxable Value');
                $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Cess Amount');
                $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'E-Commerce GSTIN');
    
                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('B2B');
                $rowCount = 2;
                // B2b end
    
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                $this->db->select('*');
                $this->db->select_sum('invoice_product.amount');
                $this->db->select_sum('invoice_product.purchase_price');
                $this->db->select_sum('sales.cess');
                $this->db->from('invoice_product');
                $this->db->join('sales', 'invoice_product.invoiceID = sales.id', 'LEFT');
                $this->db->group_by('invoice_product.tax');
                $this->db->where(['invoice_product.type' => 'Sale']);
                $this->db->where(['sales.gstNo' => '']);
                $this->db->where(['sales.user_id' => $this->session->userdata('user_id'), 'sales.comp_id' => $this->session->userdata('comp_id'), 'sales.status' => 1]);
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('sales.CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where(['sales.invoiceDate >=' => $start_date]);
                $this->db->where(['sales.invoiceDate <=' => $end_date]);
                $totals_data = $this->db->get()->result();
                if ($totals_data) {
                    foreach ($totals_data as $key => $val) {
                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "OE");
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount,  get_title('parties', ['id' => $val->customerId], 'state_code') . "-" . get_title('state_list', ['id' => get_title('parties', ['id' => $val->customerId], 'state')], 'state'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "");
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val->tax);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val->purchase_price);
                        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val->cess);
                        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "");
                        $rowCount++;
                    }
                }
    
                // Rename 2nd sheet
                $objPHPExcel->getActiveSheet()->setTitle('B2C');
                // b2c end
    
                // HSN START
                $objPHPExcel->createSheet();
                $objPHPExcel->setActiveSheetIndex(2);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'HSN');
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Description');
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'UQC');
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Total Quantity');
                $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Total Value');
                $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Rate');
                $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Taxable Value');
                $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Integrated Tax Amount');
                $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Central Tax Amount');
                $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'State/UT Tax Amount');
                $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Cess Amount');
    
                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('HSN');
                $rowCount = 2;
                $total_amount = 0;
                $total_tax = 0;
                $total_taxAbleAmount = 0;
                $this->db->select('*');
                $this->db->select('hsn');
                $this->db->select('invoiceID');
                $this->db->select('productname');
                $this->db->select_sum('price');
                $this->db->select_sum('netAmount');
                $this->db->select_sum('qty');
                $this->db->select_sum('sgst');
                $this->db->select_sum('cgst');
                $this->db->select_sum('igst');
                $this->db->select_sum('taxAmount');
                $this->db->select_sum('purchase_price');
                // taxable amount
                $this->db->from('invoice_product');
                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                if ($this->session->userdata('BranchRole')) {
                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                }
                $this->db->where('user_id', $this->session->userdata('user_id'));
                $this->db->where('status', 1);
                $this->db->where('invoiceDate >=', $start_date);
                $this->db->where('invoiceDate<=', $end_date);
    
                $this->db->where('type', 'Sale');
                $this->db->group_by("hsn", "tax");
                $result_of_products = $this->db->get()->result();
    
                if (!empty($result_of_products)) {
                    foreach ($result_of_products as $key => $val) {
                        $total_amount += $val->purchase_price;
                        $total_tax += $val->taxAmount;
                        $total_amount += $val->netAmount;
                        $overall_igst = 0;
                        $overall_cgst = 0;
                        $overall_sgst = 0;
                        $overall_sgst = $overall_sgst + $val->sgst;
                        $overall_cgst = $overall_cgst + $val->cgst;
                        $overall_igst = $overall_igst + $val->igst;
    
                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val->hsn);
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val->productname);
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, get_title('product', ['id' => $val->product_id], 'unit_name'));
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val->qty);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val->netAmount);
                        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val->tax);
                        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $val->price);
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val->igst);
                        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $val->cgst);
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $val->sgst);
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $val->cess);
                        $rowCount++;
                    }
                }
    
                $objPHPExcel->getActiveSheet()->setTitle('HSN');
    
                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="GSTR-1_' . $gst_no . "_" . $currrent_month . "_" . $current_year . '.xls');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
            } else {
                $data['page'] = 'admin_details/error/page_not_found';
                $this->load->view('inventory', $data);
            }
        }
    }