<?php

defined("BASEPATH") or exit("No direct script access allowed");
date_default_timezone_set("Asia/Kolkata");

class Proforma extends CI_Controller 
{
        public function __construct() {
                parent::__construct();

                $this->load->helper(["form", "url", "action_helper"]);

                $this->load->model("user_model");

                if ($this->session->userdata('user_id') === null) {
                    return redirect(base_url() . "index");
                }
        }

        public function index() 
        {
                $data["page"] = "proforma/index";
                return $this->load->view("inventory_sep/inventory", $data);
        }

        public function save() 
        {
                $product = $this->input->post("product");
                $array_of_products = [];

                foreach ($product as $row) {
                        $qty = $row["qty"];
                        $netAmount = $row["netAmount"];
                        $rate = $row["rate"];
                        $product_id = $row["product"];

                        if ($qty == "" || $netAmount == "" || $rate == "" || $product_id == "") {

                        } else {
                            $array_of_products[] = $row;
                        }
                }

                if (empty($array_of_products)) {
                        echo json_encode(["status" => false, "msg" => "Please enter products required fields."]);
                        return;
                }

                if (!$this->input->post()) {
                        echo json_encode(["status" => false, "msg" => "Please enter required fields.",
                ]);
                return;
            }

            $this->form_validation->set_rules(
                    "invoiceNumber",
                    "Order Number",
                    "required"
            );
            if ($this->form_validation->run() == false) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Order number is requred.",
                ]);
                return;
            }

            $invoiceNumber = ucwords($this->input->post("invoiceNumber"));
            $resp = $this->unique_order();
            $this->form_validation->set_rules(
                    "invoiceNumber",
                    "Order Number",
                    "required"
            );
            if ($resp == false) {
                $user_id = $this->session->userdata("user_id");
                $comp_id = $this->session->userdata("comp_id");
                $series_id = $this->input->post("series_id");

                if (get_title("series", ["series_id" => $series_id], "starter_point") == 19) {
                    $seriesNumber = 110;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 199) {
                    $seriesNumber = 1100;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 1999) {
                    $seriesNumber = 11000;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 19999) {
                    $seriesNumber = 110000;
                } elseif (get_title("series", ["series_id" => $series_id], "starter_point") == 199999) {
                    $seriesNumber = 1100000;
                } else {
                    $seriesNumber = get_title("series", ["series_id" => $series_id], "starter_point") + 1;
                }
                
                $where = [
                    "user_id" => $user_id,
                    "comp_id" => $comp_id,
                    "series_id" => $series_id,
                ];
                if ($this->session->userdata("BranchRole")) {
                    $where["CompanyBranchId"] = $this->session->userdata(
                            "CompBranchId"
                    );
                }
                $this->user_model->updateAry(
                        "series",
                        ["starter_point" => $seriesNumber],
                        $where
                );

                $where = [
                    "series.comp_id" => $comp_id,
                    "series.user_id" => $user_id,
                    "series.status" => 2,
                ];
                if ($this->session->userdata("BranchRole")) {
                    $where["series.CompanyBranchId"] = $this->session->userdata(
                            "CompBranchId"
                    );
                }
                $series = $this->db
                        ->select("*")
                        ->from("seriestitle")
                        ->join("series", "seriestitle.id = series.series_type")
                        ->where("series", "Proforma")
                        ->where($where)
                        ->get()
                        ->row();
                if (isset($series) && !empty($series)) {
                    $invoiceNumber = $this->user_model->getSeris($series);
                } else {
                    $user_id = $this->session->userdata("user_id");
                    $comp_id = $this->session->userdata("comp_id");
                    $where = ["comp_id" => $comp_id, "user_id" => $user_id];
                    if ($this->session->userdata("BranchRole")) {
                        $where["CompanyBranchId"] = $this->session->userdata(
                                "CompBranchId"
                        );
                    }
                    $this->db->select_max("unorder_invoice_number");
                    $this->db->where($where);
                    $query = $this->db->get("proforma")->row();
                    if (!empty($query->unorder_invoice_number)) {
                        $invoiceNumber = $query->unorder_invoice_number + 1;
                    } else {
                        $invoiceNumber = 1;
                    }
                }
            } else {
                $invoiceNumber = ucwords($this->input->post("invoiceNumber"));
            }

            $this->form_validation->set_rules(
                    "invoiceDate",
                    "Order Date",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "partyname",
                    "Party Name",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "customerId",
                    "Customer",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "amountInWords",
                    "Amount In Words",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "taxableAmount",
                    "Taxable Amount",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "totalTax",
                    "Total Tax",
                    "trim|trim|required"
            );
            $this->form_validation->set_rules(
                    "totalValue",
                    "Total Value",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "roundOff",
                    "Round Off",
                    "trim|required"
            );
            $this->form_validation->set_rules("total", "Total", "trim|required");

            if ($this->form_validation->run() == false) {
                echo json_encode(["status" => false, "msg" => validation_errors()]);
                return;
            }

            $product = $this->input->post("product");

            $data["invoiceNumber"] = $invoiceNumber;
            $data["invoiceDate"] = date(
                    "Y-m-d",
                    strtotime($this->input->post("invoiceDate"))
            );
            $data["voucherType"] = $this->input->post("voucherType");
            $data["customerId"] = ucfirst($this->input->post("customerId"));
            $data["customerName"] = get_title(
                    "parties",
                    ["id" => $this->input->post("customerId")],
                    "name"
            );
            $data["gstNo"] = ucfirst($this->input->post("gstNo"));
            $data["taxCode"] = $this->input->post("gstType");
            $data["customerAddress"] = $this->input->post("customerAddress");
            $data["customerCity"] = $this->input->post("customerCity");
            $data["state_name"] = $this->input->post("statename");
            $data["city_name"] = $this->input->post("cityCustomer");
            $data["customerState"] = $this->input->post("customerState");
            $data["customerMobile"] = $this->input->post("customerMobile");
            $data["customerEmail"] = $this->input->post("customerEmail");
            $data["customerBalance"] = $this->input->post("customerBalance");
            $data["amountInWords"] = ucwords($this->input->post("amountInWords"));
            $data["taxableAmount"] = $this->input->post("taxableAmount");
            $data["totalTax"] = $this->input->post("totalTax");
            $data["totalValue"] = $this->input->post("totalValue");
            $data["roundOff"] = $this->input->post("roundOff");
            $data["freight_charge"] = $this->input->post("freight_charge");
            $data["cash_discount"] = $this->input->post("cash_discount");
            $data["total"] = $this->input->post("total");
            $data["expenses"] = $this->input->post("expenses");
            $data["lrDate"] = $this->input->post("lrDate");
            $data["lrNumber"] = $this->input->post("lrNumber");
            $data["ewayBill"] = $this->input->post("ewayBill");
            $data["transport"] = $this->input->post("transport");
            $data["payTermsDay"] = $this->input->post("payTermsDay");
            $data["dueDate"] = $this->input->post("dueDate");
            $data["dcDate"] = $this->input->post("dcDate");
            $data["dcNumber"] = $this->input->post("dcNumber");
            $data["oldBalance"] = $this->input->post("oldBalance");
            $data["totalsgst"] = $this->input->post("totalsgst");
            $data["totalcgst"] = $this->input->post("totalcgst");
            $data["totaligst"] = $this->input->post("totaligst");
            $data["orderDate"] = $this->input->post("orderDate");
            $data["orderNumber"] = $this->input->post("orderNumber");
            $data["taxCal"] = $this->input->post("taxcal");
                
            $shippingInfo = array(
                     'shippingName' => $this->input->post('shippingNameHidden'),
                     'shippingEmail' => $this->input->post('shippingEmailHidden'),
                     'shippingMobile' => $this->input->post('shippingMobileHidden'),
                     'shippingGSTType' => $this->input->post('shippingGSTTypeHidden'),
                     'shippingGST' => $this->input->post('shippingGSTHidden'),
                     'shippingState' => $this->input->post('shippingStateHidden'),
                     'shippingStateCode' => get_title('state_list', ['id' => $this->input->post('shippingStateHidden')], 'code'),
                     'shippingAddress' => $this->input->post('shippingAddressHidden'),
                     'shippingCity' => $this->input->post('shippingCityHidden'),
                     'shippingZip' => $this->input->post('shippingZipHidden'),
             );
             $data['shippingInfo'] = json_encode($shippingInfo);
                
            $data["comp_id"] = $this->session->userdata("comp_id");
            $data["CompanyBranchId"] = $this->session->userdata("CompBranchId");
            $data["user_id"] = $this->session->userdata("user_id");
            
            $compProfileWhere = ['user_id' => $this->session->userdata("user_id"), 'comp_id' => $this->session->userdata("comp_id")];
            if ($this->session->userdata("BranchRole")) {
                $compProfileWhere["CompanyBranchId"] = $this->session->userdata("CompBranchId");
            }
            
            $data["financial_year"] = get_title('comp_profile', $compProfileWhere, 'financial_year');
            $data["cess"] = $this->input->post("cess");
            $data["remark"] = $this->input->post("remark");
            $data["p_code"] = $this->input->post("p_code");
            $data["format_invoice"] = $this->input->post("format_invoice");
            $data["product"] = json_encode($array_of_products);
            $data["vahical_no"] = $this->input->post("vehicle");
            $data["lot_no"] = $this->input->post("lot_no");

            $parties = [
                "name" => get_title(
                        "parties",
                        ["id" => $this->input->post("customerId")],
                        "name"
                ),
                "customer_id" => $this->input->post("customerId"),
                "address" => $this->input->post("customerAddress"),
                "city_id" => $this->input->post("customerCity"),
                "state_id" => $this->input->post("customerState"),
                "mobile_number" => $this->input->post("customerMobile"),
                "statename" => $this->input->post("statename"),
                "cityname" => $this->input->post("cityCustomer"),
                "gst_no" => ucfirst($this->input->post("gstNo")),
                "email" => $this->input->post("customerEmail"),
            ];
            $data["parties"] = json_encode($parties);

            $productids = [];
            foreach ($array_of_products as $row) {
                $productids[] = $row["product"];
            }

            $data["product_ids"] = implode(",", $productids);
            $data["num_of_items"] = $this->input->post("num_of_items");
            $data["salesman"] = $this->input->post("SalesmanId");
            $data["created"] = date("d-m-Y h:i:s A");
            $data["created_by"] = get_title(
                    "users",
                    ["id" => $this->session->userdata("user_id")],
                    "name"
            );

            $result = $this->user_model->saveAry("proforma", $data);
            // proforma table data store
            $this->user_model->bulkProforma(
                    "proforma_product",
                    $array_of_products,
                    $result,
                    $data["invoiceNumber"],
                    $data["invoiceDate"]
            );
            $sms_where = [
                "sms_blocked_status" => "Enable",
                "id" => $this->session->userdata("comp_id"),
            ];
            if ($this->session->userdata("BranchRole")) {
                $sms_where["CompanyBranchId"] = $this->session->userdata(
                        "CompBranchId"
                );
            }
            $sms_blocked_status = $this->user_model->getRow(
                    "inventory_billing",
                    $sms_where
            );

            if (
                    $this->input->post("customerMobile") &&
                    !empty($sms_blocked_status)
            ) {
                send_sms_for_sale(
                        $this->input->post("customerMobile"),
                        $this->input->post("total"),
                        $result
                );
            }

            if (isset($result) && !empty($result)) {
                $comp_id = $this->session->userdata("comp_id");
                $user_id = $this->session->userdata("user_id");
                $where1 = [
                    "series_id" => $this->input->post("series_id"),
                    "user_id" => $user_id,
                    "comp_id" => $comp_id,
                ];
                if ($this->session->userdata("BranchRole")) {
                    $where1["CompanyBranchId"] = $this->session->userdata(
                            "CompBranchId"
                    );
                }
                $series = $this->user_model->getRow("series", $where1);
                if ($series) {
                    if ($series->starter_point == 19) {
                        $seriesNumber = 110;
                    } elseif ($series->starter_point == 199) {
                        $seriesNumber = 1100;
                    } elseif ($series->starter_point == 1999) {
                        $seriesNumber = 11000;
                    } elseif ($series->starter_point == 19999) {
                        $seriesNumber = 110000;
                    } elseif ($series->starter_point == 199999) {
                        $seriesNumber = 1100000;
                    } else {
                        $seriesNumber = $series->starter_point + 1;
                    }
                    $aman["starter_point"] = $seriesNumber;
                    $this->user_model->updateAry("series", $aman, $where1);
                } else {
                    $only_number_invoice["unorder_invoice_number"] = $invoiceNumber;
                    $this->user_model->updateAry(
                            "proforma",
                            $only_number_invoice,
                            ["id" => $result]
                    );
                }
                echo json_encode([
                    "status" => true,
                    "data" => $result,
                    "msg" => "Proforma save successfully.",
                ]);
                return;
            } else {
                echo json_encode([
                    "status" => false,
                    "msg" => "There is some issue, Please try again.",
                ]);
                return;
            }
        }

        public function print($id) 
        {
            if (!$id) {
                return redirect(base_url() . "proforma");
            }

            $where = ["id" => $id];
            $result = $this->user_model->getRow("proforma", $where);

            $pwhare = ["invoiceID" => $id, "type" => "Proforma"];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry(
                        "proforma_product",
                        "",
                        "",
                        "",
                        $pwhare
                );
                foreach ($products as $row) {
                    $serialsAry = [
                        "invoice_id" => $row->invoiceID,
                        "item_id" => $row->id,
                    ];
                    $serials = $this->user_model->getAry(
                            "item_serials",
                            "",
                            "",
                            "",
                            $serialsAry,
                            "serial_no"
                    );
                    $row->serials = $serials;
                }
                $result->product = $products;
                $data["result"] = $result;

                $fromet = get_title(
                        "billing_format",
                        ["id" => $this->session->userdata("format_of_bill")],
                        "name"
                );
                if ($fromet == "4inch") {
                    return $this->load->view(
                                    "inventory_sep/proforma/4inch",
                                    $data
                    );
                } elseif ($fromet == "A5") {
                    return $this->load->view(
                                    "inventory_sep/proforma/print",
                                    $data
                    );
                } elseif ($fromet == "Lh") {
                    return $this->load->view(
                                    "inventory_sep/proforma/lh",
                                    $data
                    );
                } elseif ($fromet == "3inch") {
                    return $this->load->view(
                                    "inventory_sep/proforma/print/3inch",
                                    $data
                    );
                } elseif ($fromet == 'mc_formatA4') {
                    return $this->load->view('inventory_sep/proforma/mc_formatA4', $data);                    
                } else {
                    return $this->load->view(
                                    "inventory_sep/proforma/print",
                                    $data
                    );
                }
            } else {
                $this->session->set_flashdata([
                    "msg" => "Record not found.",
                    "class" => "danger",
                ]);
                return redirect(base_url() . "proforma");
            }
        }

        public function edit($id) 
        {
            if (empty($id)) {
                $data["page"] = "inventory_sep/error";
                return $this->load->view("inventory", $data);
            }
            $data["page"] = "proforma/edit";
            $where = ["id" => $id];
            $result = $this->user_model->getRow("proforma", $where);
            if (isset($result) && !empty($result)) {
                $data["invoiceNumber"] = $result->invoiceNumber;
                return $this->load->view("inventory_sep/inventory", $data);
            } else {
                $this->session->set_flashdata([
                    "msg" => "Record not found.",
                    "class" => "danger",
                ]);
                return redirect(base_url() . "proforma");
            }
        }

        public function update() 
        {
            if (!$this->input->post()) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Please enter required fields.",
                ]);
                return;
            }
            $id = $this->input->post("printSale");
            if (!$id) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Please enter required fields.",
                ]);
                return;
            }

            $product = $this->input->post("product");
            $array_of_products = [];

            foreach ($product as $row) {
                $qty = $row["qty"];
                $netAmount = $row["netAmount"];
                $rate = $row["rate"];
                $productId = $row["product"];
                if (
                        $qty == "" ||
                        $netAmount == "" ||
                        $rate == "" ||
                        $productId == ""
                ) {

                } else {
                    $array_of_products[] = $row;
                }
            }

            if (empty($array_of_products)) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Please enter products required fields.",
                ]);
                return;
            }

            if (!$this->input->post()) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Please enter required fields.",
                ]);
                return;
            }

            $this->form_validation->set_rules(
                    "invoiceNumber",
                    "Order Number",
                    "required|trim"
            );
            if ($this->form_validation->run() == false) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Invoice number is required.",
                ]);
                return;
            }

            $this->form_validation->set_rules(
                    "invoiceDate",
                    "Order Date",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "partyname",
                    "Party Name",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "customerId",
                    "Customer",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "amountInWords",
                    "Amount In Words",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "taxableAmount",
                    "Taxable Amount",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "totalTax",
                    "Total Tax",
                    "trim|trim|required"
            );
            $this->form_validation->set_rules(
                    "totalValue",
                    "Total Value",
                    "trim|required"
            );
            $this->form_validation->set_rules(
                    "roundOff",
                    "Round Off",
                    "trim|required"
            );
            $this->form_validation->set_rules("total", "Total", "trim|required");

            if ($this->form_validation->run() == false) {
                echo json_encode(["status" => false, "msg" => validation_errors()]);
                return;
            }

            $data["invoiceNumber"] = ucwords($this->input->post("invoiceNumber"));
            $data["invoiceDate"] = date(
                    "Y-m-d",
                    strtotime($this->input->post("invoiceDate"))
            );
            $data["voucherType"] = $this->input->post("voucherType");
            $data["customerId"] = ucfirst($this->input->post("customerId"));
            $data["customerName"] = get_title(
                    "parties",
                    ["id" => $this->input->post("customerId")],
                    "name"
            );
            $data["gstNo"] = ucfirst($this->input->post("gstNo"));
            $data["taxCode"] = $this->input->post("gstType");
            $data["customerAddress"] = $this->input->post("customerAddress");
            $data["customerCity"] = $this->input->post("customerCity");
            $data["state_name"] = $this->input->post("statename");
            $data["city_name"] = $this->input->post("cityCustomer");
            $data["customerState"] = $this->input->post("customerState");
            $data["customerMobile"] = $this->input->post("customerMobile");
            $data["customerEmail"] = $this->input->post("customerEmail");
            $data["customerBalance"] = $this->input->post("customerBalance");
            $data["amountInWords"] = ucwords($this->input->post("amountInWords"));
            $data["taxableAmount"] = $this->input->post("taxableAmount");
            $data["totalTax"] = $this->input->post("totalTax");
            $data["totalValue"] = $this->input->post("totalValue");
            $data["roundOff"] = $this->input->post("roundOff");
            $data["freight_charge"] = $this->input->post("freight_charge");
            $data["cash_discount"] = $this->input->post("cash_discount");
            $data["total"] = $this->input->post("total");
            $data["expenses"] = $this->input->post("expenses");
            $data["lrDate"] = $this->input->post("lrDate");
            $data["lrNumber"] = $this->input->post("lrNumber");
            $data["ewayBill"] = $this->input->post("ewayBill");
            $data["transport"] = $this->input->post("transport");
            $data["payTermsDay"] = $this->input->post("payTermsDay");
            $data["dueDate"] = $this->input->post("dueDate");
            $data["dcDate"] = $this->input->post("dcDate");
            $data["dcNumber"] = $this->input->post("dcNumber");
            $data["oldBalance"] = $this->input->post("oldBalance");
            $data["totalsgst"] = $this->input->post("totalsgst");
            $data["totalcgst"] = $this->input->post("totalcgst");
            $data["totaligst"] = $this->input->post("totaligst");
            $data["orderDate"] = $this->input->post("orderDate");
            $data["orderNumber"] = $this->input->post("orderNumber");

                $shippingInfo = array(
                        'shippingName' => $this->input->post('shippingNameHidden'),
                        'shippingEmail' => $this->input->post('shippingEmailHidden'),
                        'shippingMobile' => $this->input->post('shippingMobileHidden'),
                        'shippingGSTType' => $this->input->post('shippingGSTTypeHidden'),
                        'shippingGST' => $this->input->post('shippingGSTHidden'),
                        'shippingState' => $this->input->post('shippingStateHidden'),
                        'shippingStateCode' => get_title('state_list', ['id' => $this->input->post('shippingStateHidden')], 'code'),
                        'shippingAddress' => $this->input->post('shippingAddressHidden'),
                        'shippingCity' => $this->input->post('shippingCityHidden'),
                        'shippingZip' => $this->input->post('shippingZipHidden'),
                );
                $data['shippingInfo'] = json_encode($shippingInfo);
                
            $data["taxCal"] = $this->input->post("taxcal");
            $data["vahical_no"] = $this->input->post("vehicle");
            $data["lot_no"] = $this->input->post("lot_no");
            $data["cess"] = $this->input->post("cess");
            $data["remark"] = $this->input->post("remark");
            $data["p_code"] = $this->input->post("p_code");
            $data["format_invoice"] = $this->input->post("format_invoice");
            $data["product"] = json_encode($array_of_products);

            $parties = [
                "name" => get_title(
                        "parties",
                        ["id" => $this->input->post("customerId")],
                        "name"
                ),
                "customer_id" => $this->input->post("customerId"),
                "address" => $this->input->post("customerAddress"),
                "city_id" => $this->input->post("customerCity"),
                "state_id" => $this->input->post("customerState"),
                "mobile_number" => $this->input->post("customerMobile"),
                "statename" => $this->input->post("statename"),
                "cityname" => $this->input->post("cityCustomer"),
                "gst_no" => ucfirst($this->input->post("gstNo")),
                "email" => $this->input->post("customerEmail"),
            ];
            $data["parties"] = json_encode($parties);
            $productids = [];

            foreach ($array_of_products as $row) {
                $productids[] = $row["product"];
            }

            $data["product_ids"] = implode(",", $productids);
            $data["num_of_items"] = $this->input->post("num_of_items");
            $data["salesman"] = $this->input->post("SalesmanId");
            $data["updated"] = date("d-m-Y h:i:s A");
            $data["updated_by"] = get_title(
                    "users",
                    ["id" => $this->session->userdata("user_id")],
                    "name"
            );

            $where = ["id" => $id];
            $order_condition = ["invoiceID" => $id, "type" => "Proforma"];
            $result = $this->user_model->updateAry("proforma", $data, $where);
            $invoice_pro = $this->user_model->getAry(
                    "proforma_product",
                    "",
                    "",
                    "",
                    $order_condition
            );

            if ($invoice_pro) {
                $this->user_model->deleteRow("proforma_product", $order_condition);
            }

            $resultProduct = $this->user_model->bulkProforma(
                    "proforma_product",
                    $array_of_products,
                    $id,
                    $data["invoiceNumber"],
                    $data["invoiceDate"]
            );

            if (isset($result) && !empty($result) && !empty($resultProduct)) {
                echo json_encode([
                    "status" => true,
                    "data" => $id,
                    "msg" => "Proforma update successfully.",
                ]);
                return;
            } else {
                echo json_encode([
                    "status" => false,
                    "msg" => "There is some issue, Please try again.",
                ]);
                return;
            }
        }

        public function view($id)
        {
            if (!$id) {
                return redirect(base_url() . "proforma");
            }
            $data["page"] = "inventory_sep/proforma/view";
            $where = ["id" => $id];
            $result = $this->user_model->getRow("proforma", $where);
            $pwhare = ["invoiceID" => $id, "type" => "Proforma"];
            if (isset($result) && !empty($result)) {
                $products = $this->user_model->getAry(
                        "proforma_product",
                        "",
                        "",
                        "",
                        $pwhare
                );
                $result->product = $products;
                $data["result"] = $result;
                return $this->load->view("inventory", $data);
            } else {
                $this->session->set_flashdata([
                    "msg" => "Record not found.",
                    "class" => "danger",
                ]);
                return redirect(base_url() . "proforma");
            }
        }

        public function Recycle($id) 
        {
            if (!$id) {
                $this->session->set_flashdata([
                    "msg" => "Record not found.",
                    "class" => "danger",
                ]);
                return redirect(base_url() . "proforma/report");
            }

            $where = [
                "id" => $id,
                "user_id" => $this->session->userdata("user_id"),
                "comp_id" => $this->session->userdata("comp_id"),
                "status" => 1,
                "financial_year" => $this->session->userdata("financial_year"),
            ];
            if ($this->session->userdata("BranchRole")) {
                $where["CompanyBranchId"] = $this->session->userdata(
                        "CompBranchId"
                );
            }
            $data["status"] = 0;
            $return = $this->user_model->updateAry("proforma", $data, $where);
            if ($return) {
                $where = ["invoiceID" => $id, "type" => "Proforma"];
                $res = $this->user_model->getAry(
                        "proforma_product",
                        "",
                        "",
                        "",
                        $where
                );
                if ($res) {
                    $statusChange["status"] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry(
                                "proforma_product",
                                $statusChange,
                                $where
                        );
                    }
                }
                $this->session->set_flashdata([
                    "msg" => "Proforma deleted",
                    "class" => "success",
                ]);
                return redirect(base_url() . "proforma/report");
            } else {
                $this->session->set_flashdata([
                    "msg" => "Record not found.",
                    "class" => "danger",
                ]);
                return redirect(base_url() . "proforma/report");
            }
        }

        public function Trash($id) 
        {
            if (!$id) {
                echo json_encode(["status" => false, "msg" => "Id not found."]);
                return;
            }

            $where = [
                "id" => $id,
                "user_id" => $this->session->userdata("user_id"),
                "comp_id" => $this->session->userdata("comp_id"),
                "status" => 1,
                "financial_year" => $this->session->userdata("financial_year"),
            ];
            if ($this->session->userdata("BranchRole")) {
                $where["CompanyBranchId"] = $this->session->userdata(
                        "CompBranchId"
                );
            }
            $data["status"] = 0;
            $return = $this->user_model->updateAry("proforma", $data, $where);
            if ($return) {
                $where = ["invoiceID" => $id, "type" => "Proforma"];
                $res = $this->user_model->getAry(
                        "proforma_product",
                        "",
                        "",
                        "",
                        $where
                );
                if ($res) {
                    $statusChange["status"] = 0;
                    foreach ($res as $da) {
                        $this->user_model->updateAry(
                                "proforma_product",
                                $statusChange,
                                $where
                        );
                    }
                }
                echo json_encode([
                    "status" => true,
                    "msg" => "Proforma deleted successfully.",
                ]);
                return;
            } else {
                echo json_encode([
                    "status" => false,
                    "msg" => "There is some issue, Please try again.",
                ]);
                return;
            }
        }

        public function delete($id) 
        {
            if (!$id) {
                echo json_encode(["status" => false, "msg" => "Id not found."]);
                return;
            }
            $where = [
                "id" => $id,
                "user_id" => $this->session->userdata("user_id"),
                "comp_id" => $this->session->userdata("comp_id"),
                "financial_year" => $this->session->userdata("financial_year"),
            ];
            $return = $this->user_model->deleteRow("proforma", $where);
            if ($this->session->userdata("BranchRole")) {
                $where["CompanyBranchId"] = $this->session->userdata(
                        "CompBranchId"
                );
            }
            if ($return) {
                $where = ["invoiceID" => $id, "type" => "Proforma"];
                $res = $this->user_model->getAry(
                        "proforma_product",
                        "",
                        "",
                        "",
                        $where
                );
                if ($res) {
                    foreach ($res as $da) {
                        $this->user_model->deleteRow("proforma_product", $where);
                    }
                }

                if (isset($res) && !empty($res)) {
                    echo json_encode([
                        "status" => true,
                        "msg" => "Proforma deleted successfully.",
                    ]);
                    return;
                } else {
                    echo json_encode([
                        "status" => false,
                        "msg" => "There is some issue, Please try again.",
                    ]);
                    return;
                }
            } else {
                echo json_encode([
                    "status" => false,
                    "msg" => "There is some issue, Please try again.",
                ]);
                return;
            }
        }

        public function download($id) 
        {

        }

        public function preveiw() 
        {

        }

        public function proforma_recycle()
        {

        }

        public function report($start = 0) 
        {
            $invoiceNumber = "";
            $invoiceDate = "";
            $fromDate = "";
            $toDate = "";
            $partyName = "";
            $productName = "";
            $first_date = "";
            $last_date = "";
            $limit = "";
            $party = "";

            if ($this->input->get("invoiceNumber")) {
                $invoiceNumber = [
                    "invoiceNumber" => $this->input->get("invoiceNumber"),
                ];
                $limit = "";
            }

            if ($this->input->get("fromDate") && $this->input->get("toDate")) {
                $fromDate = ["invoiceDate >=" => $this->input->get("fromDate")];
                $toDate = ["invoiceDate <=" => $this->input->get("toDate")];
                $first_date = $this->input->get("fromDate");
                $last_date = $this->input->get("toDate");
                $limit = "";
            } else {
                $fromDate = ["invoiceDate >=" => date("Y-m-01")];
                $toDate = ["invoiceDate <=" => date("Y-m-d")];
                $limit = "";
            }

            $partyFilter = function_exists("input_get_array") ? input_get_array("party") : (array) ($this->input->get("party") ?: []);
            if ($partyFilter !== [] && !in_array("All", $partyFilter, true)) {
                $party = $partyFilter;
            }

            $table = "proforma";
            $order = ["col" => "added_at", "order" => "DESC"];
            $financial_year = $this->session->userdata("financial_year");
            $where = [
                "user_id" => $this->session->userdata("user_id"),
                "comp_id" => $this->session->userdata("comp_id"),
                "status" => 1,
                "financial_year" => $financial_year,
            ];
            if ($this->session->userdata("BranchRole")) {
                $where["CompanyBranchId"] = $this->session->userdata("CompBranchId");
            }
            $result = $this->user_model->getTblerDate(
                    $table,
                    $start,
                    $limit,
                    $order,
                    $invoiceNumber,
                    $invoiceDate,
                    $fromDate,
                    $toDate,
                    $partyName,
                    $productName,
                    $where,
                    "",
                    $party
            );

            $pwhere = [
                "user_id" => $this->session->userdata("user_id"),
                "comp_id" => $this->session->userdata("comp_id"),
                "status" => 1,
            ];
            if ($this->session->userdata("BranchRole")) {
                $pwhere["CompanyBranchId"] = $this->session->userdata("CompBranchId");
            }
            $data["parties"] = $this->db
                    ->select("id, name")
                    ->where($pwhere)
                    ->order_by("id", "ASC")
                    ->get("parties")
                    ->result();
            $data["fromDate"] = $first_date;
            $data["toDate"] = $last_date;
            $data["result"] = $result;
            $data["page"] = "proforma/report";
            return $this->load->view("inventory_sep/inventory", $data);
        }

        public function unique_order() 
        {
            $invoiceNumber = ucwords($this->input->post("invoiceNumber"));
            $user_id = $this->session->userdata("user_id");
            $comp_id = $this->session->userdata("comp_id");

            $where = [
                "user_id" => $user_id,
                "comp_id" => $comp_id,
                "invoiceNumber" => $invoiceNumber,
                "status" => 1,
            ];
            if ($this->session->userdata("BranchRole")) {
                $where["CompanyBranchId"] = $this->session->userdata(
                        "CompBranchId"
                );
            }
            $return_value = $this->user_model->getRow("proforma", $where);
            if ($return_value) {
                return false;
            }
            return true;
        }
        
        public function checkProformaInvoiceNumber ()
        {
                if ($_POST) {
                        $InvoiceNumber = $this->input->post('InvoiceNumber');
                        if ($InvoiceNumber != NULL && !empty($InvoiceNumber)) {
                                $UserId = $this->session->userdata('user_id');
                                $CompId = $this->session->userdata('comp_id');
                    
                                $Where = ['invoiceNumber' => $InvoiceNumber, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $Result = $this->db->where($Where)->get('proforma')->num_rows();
                    
                                if ($Result) {
                                        echo json_encode(['status' => true]);
                                } else {
                                        echo json_encode(['status' => false]);
                                }
                        } else {
                                echo json_encode(['status' => false]);
                        }
                } else {
                        echo json_encode(['status' => false]);
                }
        }        

        public function getProformaByIdPrevious() 
        {
            $id = $this->input->post("id");
            if ($id == null || $id == "") {
                echo json_encode(["status" => false, "data" => "Not not Data"]);
            } else {
                $this->db->select("*");
                $this->db->where('proforma.invoiceNumber < "' . $id . '"');
                $this->db->where("user_id", $this->session->userdata("user_id"));
                $this->db->where("comp_id", $this->session->userdata("comp_id"));
                if ($this->session->userdata("BranchRole")) {
                    $this->db->where(
                            "CompanyBranchId",
                            $this->session->userdata("CompBranchId")
                    );
                }
                $this->db->where(
                        "financial_year",
                        $this->session->userdata("financial_year")
                );
                $this->db->where("status", 1);
                $this->db->order_by("invoiceNumber", "DESC");
                $result = $this->db->get("proforma")->row();
                if ($result) {
                    echo json_encode(["status" => true, "data" => $result]);
                } else {
                    echo json_encode([
                        "status" => false,
                        "data" => "Not Found Data",
                    ]);
                    return false;
                }
            }
        }

        public function getProformaByIdNext()
        {
            $id = $this->input->post("id");
            if ($id == null || $id == "") {
                echo json_encode(["status" => false, "data" => "Not not Data"]);
            } else {
                $this->db->select("*");
                $this->db->where('proforma.invoiceNumber > "' . $id . '"');
                $this->db->where("user_id", $this->session->userdata("user_id"));
                $this->db->where("comp_id", $this->session->userdata("comp_id"));
                if ($this->session->userdata("BranchRole")) {
                    $this->db->where(
                            "CompanyBranchId",
                            $this->session->userdata("CompBranchId")
                    );
                }
                $this->db->where(
                        "financial_year",
                        $this->session->userdata("financial_year")
                );
                $this->db->where("status", 1);
                $this->db->order_by("invoiceNumber", "asc");
                $result = $this->db->get("proforma")->row();
                if ($result) {
                    echo json_encode(["status" => true, "data" => $result]);
                } else {
                    echo json_encode([
                        "status" => false,
                        "data" => "Not Found Data",
                    ]);
                    return false;
                }
            }
        }

        public function getProformaById() 
        {
            $id = $this->input->post("id");
            if (!isset($id) && empty($id)) {
                echo json_encode(["status" => false, "data" => "Id required."]);
            } else {
                $orderbook_where = [
                    "status" => 1,
                    "InvoiceNumber" => $id,
                    "comp_id" => $this->session->userdata("comp_id"),
                    "user_id" => $this->session->userdata("user_id"),
                ];
                if ($this->session->userdata("BranchRole")) {
                    $orderbook_where["CompanyBranchId"] = $this->session->userdata(
                            "CompBranchId"
                    );
                }
                $orderbook = $this->user_model->getRow(
                        "proforma",
                        $orderbook_where
                );
                if (isset($orderbook) && !empty($orderbook)) {
                    $order_id = $orderbook->id;
                    $order_where = [
                        "InvoiceID" => $order_id,
                        "comp_id" => $this->session->userdata("comp_id"),
                        "user_id" => $this->session->userdata("user_id"),
                        "type" => "Proforma",
                    ];
                    $order_product = $this->user_model->getAry(
                            "proforma_product",
                            "",
                            "",
                            "",
                            $order_where
                    );
                    $data["order_product"] = $order_product;
                    $data["orderbook"] = $orderbook;
                    echo json_encode(["status" => true, "data" => $data]);
                } else {
                    echo json_encode([
                        "status" => false,
                        "data" => "Data not found.",
                    ]);
                }
            }
        }

        public function getProformaByInvoiceNumber() 
        {
            $id = $this->input->post("id");
            if ($id == null || $id == "") {
                echo json_encode(["status" => false, "data" => "Not not Data"]);
            } else {
                $this->db->select("*");
                $this->db->where("proforma.invoiceNumber", $id);
                $this->db->where("user_id", $this->session->userdata("user_id"));
                $this->db->where("comp_id", $this->session->userdata("comp_id"));
                if ($this->session->userdata("BranchRole")) {
                    $this->db->where(
                            "CompanyBranchId",
                            $this->session->userdata("CompBranchId")
                    );
                }
                $this->db->where(
                        "financial_year",
                        $this->session->userdata("financial_year")
                );
                $this->db->where("status", 1);
                $this->db->order_by("invoiceNumber", "DESC");
                $result = $this->db->get("proforma")->row();
                if ($result) {
                    echo json_encode(["status" => true, "data" => $result]);
                } else {
                    echo json_encode([
                        "status" => false,
                        "data" => "Not Found Data",
                    ]);
                    return false;
                }
            }
        }

        public function getLastMaxPriceOfProductByProforma() 
        {
            if ($_POST) {
                $product_id = $this->input->post("product_id");
                $party_id = $this->input->post("party_id");
                if (
                        $product_id == null ||
                        ($product_id == "" && $party_id == null) ||
                        $party_id == ""
                ) {
                    echo json_encode([
                        "status" => false,
                        "data" => "Enter required fields",
                    ]);
                } else {
                    $this->db->select("proforma_product.rate as Rate");
                    $this->db->join(
                            "proforma_product",
                            "proforma.id = proforma_product.invoiceID"
                    );
                    $this->db->where(
                            "proforma.user_id",
                            $this->session->userdata("user_id")
                    );
                    $this->db->where(
                            "proforma.comp_id",
                            $this->session->userdata("comp_id")
                    );
                    if ($this->session->userdata("BranchRole")) {
                        $this->db->where(
                                "CompanyBranchId",
                                $this->session->userdata("CompBranchId")
                        );
                    }
                    $this->db->where("proforma.customerId", $party_id);
                    $this->db->where("proforma.status", 1);
                    $this->db->where("proforma_product.product_id", $product_id);
                    $this->db->where("proforma_product.status", 1);
                    $this->db->order_by("proforma.id", "DESC");
                    $OrderBookResult = $this->db->get("proforma")->row();
                    if ($OrderBookResult) {
                        echo json_encode([
                            "status" => true,
                            "data" => $OrderBookResult,
                        ]);
                    } else {
                        echo json_encode([
                            "status" => false,
                            "data" => "Not Found",
                        ]);
                    }
                }
            }
        }

        public function set_warehouse_for_product() 
        {
                return $this->load->view("inventory_sep/proforma/ChangeWarehouse");
        }
        
        public function proformaToSale($proformaId=null)
        {
                if (empty($proformaId)) {
                        $data['page'] = 'error';
                        return $this->load->view('inventory_sep/inventory', $data);
                }
                $where = ['id' => $proformaId, 'user_id' => $this->session->userdata("user_id"), 'comp_id' => $this->session->userdata("comp_id"), 'status' => 1];
                if ($this->session->userdata("BranchRole")) {
                       $where['CompanyBranchId'] = $this->session->userdata("CompBranchId");
                }
                
                $result = $this->user_model->getRow('proforma', $where);
                if (isset($result) && !empty($result)) {
                        $data['proformaInfo'] = $result;
                       $data['proformaId'] = $proformaId;
                       $data['page'] = 'proforma/proformaToSale';
                       return $this->load->view('inventory_sep/inventory', $data);
                } else {
                        $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                        return redirect(base_url() . 'proforma');
                }
        }
        
        public function getProformaProductsForSaleInvoice()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $proformaId = $this->input->post('proformaId');
                        if (!empty($proformaId)) {
                               $userId = $this->session->userdata('user_id');
                               $compId = $this->session->userdata('comp_id');
                               $financialYear = $this->session->userdata('financial_year');
                               $compBranchId = $this->session->userdata('CompBranchId');
                    
                               $proformaProductWhere = ['invoiceID' => $proformaId, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId, 'financial_year' => $financialYear];
                               $warehouseWhere = ['user_id' => $userId, 'comp_id' => $compId];
                               if ($this->session->userdata('BranchRole')) {
                                       $proformaProductWhere['CompanyBranchId'] = $compBranchId; 
                                       $warehouseWhere['CompanyBranchId'] = $compBranchId;
                               }
                    
                               $proformaProductInfo = $this->db->where($proformaProductWhere)->get('proforma_product')->result();
                               $productArray = [];
                               foreach ($proformaProductInfo as $key => $products) {
                                       $productInfo = $this->db->where('id', $products->product_id)->get('product')->row();
                                       $productArray[] = array(
                                               "p_code" => $products->p_code,
                                               "productname" => $productInfo->p_name,
                                               "product_id" => $productInfo->id,
                                               "product_dec" => $productInfo->short_de,
                                               "hsn" => $productInfo->hsn,
                                               "qty" => $products->qty,
                                               "discount" => 0,
                                               "rate" => $products->rate,
                                               "tax" => $productInfo->tax_qty,
                                               "warehouse" => get_title('comp_profile', $warehouseWhere, 'warehouse')
                                       );
                               }
                               
                               if (is_array($productArray)) {
                                       echo json_encode(['status' => true, 'productDataArray' => $productArray]);
                               } else {
                                       echo json_encode(['status' => false, 'productDataArray' => 'No record found.']);
                               }
                       } else {
                               echo json_encode(['status' => false, 'productDataArray' => 'Proforma id missing.']);
                       }
                }
        }
        
        public function proformaCancel($proformaId) 
        {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                        if (!$proformaId) {
                                echo json_encode(['Status' => false, 'Message' => 'Id not found.']);
                                return;
                        }
                        $userId = $this->session->userdata('user_id');
                        $compId = $this->session->userdata('comp_id');
                        $financialYear = $this->session->userdata('financial_year');
                        $compBranchId = $this->session->userdata('CompBranchId');
                
                        $data['invoiceStatus'] = 0;
                        $where = ['id' => $proformaId, 'user_id' => $userId, 'comp_id' => $compId, 'financial_year' => $financialYear];
                        $proformaProductWhere = ['invoiceID' => $proformaId, 'user_id' => $userId, 'comp_id' => $compId, 'IsDeleted' => 1, 'financial_year' => $financialYear];
                        if ($this->session->userdata('BranchRole')){
                                $where['CompanyBranchId'] = $compBranchId;
                                $proformaProductWhere['CompanyBranchId'] = $compBranchId;
                        }
                
                        $proformaResult = $this->user_model->updateAry('proforma', $data, $where);
                        if ($proformaResult) {
                                $proformaProducts = $this->user_model->getAry('proforma_product', '', '', '', $proformaProductWhere);
                                if ($proformaProducts) {
                                       foreach ($proformaProducts as $key => $proformaProduct) {
                                               $this->user_model->updateAry('orderbook_product', $data, $proformaProductWhere);
                                       }
                               }
                               echo json_encode(['Status' => true, 'Message' => 'Proforma cancelled successfully.']);
                               return;
                       } else {
                               echo json_encode(['Status' => false, 'Message' => 'There is some issue, Please try again.']);
                               return;
                       }
                }
        }        
}