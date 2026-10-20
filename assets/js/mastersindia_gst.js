
// var DOMAIN_HREF = "https://pro.mastersindia.co/";  // live
var DOMAIN_HREF = "https://clientbasic.mastersindia.co/";  // test 
// var DOMAIN_HREF = "https://sandb-api.mastersindia.co/api/v1/";
// var DOMAIN_LIVE = "https://pro.mastersindia.co/";
var DOMAIN_LIVE = "https://pro.mastersindia.co/";
// 'https://pro.mastersindia.co/generateEinvoice', 
// var ACCESS_TOKEN_LIVE_STATIC = '55d018622112f162f956c94154e1c1c1d62a94e3'; // live
var ACCESS_TOKEN_LIVE_STATIC = '6971df2b43e2aa38764a76dd4ebed6ac1908a61d'; // test
var E_INVOICE_MODE = "TEST";
var SUPPLY_TYPE = "B2B";
var USER_GSTIN = BUSINESS_DETAILS.eInvoiceApiMode == "LIVE" ? BUSINESS_DETAILS.gst_no : "09AAAPG7885R002";//"08AXWPP2755Q1Z8";//"23AIPPG0820P1Z6";;
var SELLER_GSTIN = "09AAAPG7885R002";
var SELLER_LEGAL_NAME = "MastersIndia UP";
var SELLER_TRADE_NAME = "MastersIndia UP";
var SELLER_ADDRESS1 = "Vila";
var SELLER_ADDRESS2 = "Vila";
var SELLER_LOCATION = "Noida";
var SELLER_PINCODE = 201301;
var SELLER_STATE_CODE = "UTTAR PRADESH";
var SELLER_PHONE = 9876543231;
var SELLER_EMAIL = "";
// ledger details 
var BUYER_GSTIN = "09AAAPG6339C1Z1";
var BUYER_LEGAL_NAME = "M/S BLESSING EXPORT";
var BUYER_TRADE_NAME = "M/S BLESSING EXPORT";
var BUYER_ADDRESS1 = "Vila";
var BUYER_ADDRESS2 = "Vila";
var BUYER_LOCATION = "Noida";
var BUYER_PLACE_OF_SUPPLY = "9";
var BUYER_PINCODE = 201301;
var BUYER_STATE_CODE = "UTTAR PRADESH";
var BUYER_PHONE = 9876543231;
var BUYER_EMAIL = "";
// consign details 
var SHIP_GSTIN = "09AAAPG6339C1Z1";
var SHIP_LEGAL_NAME = "M/S BLESSING EXPORT";
var SHIP_TRADE_NAME = "MastersIndia UT";
var SHIP_ADDRESS1 = "Vila";
var SHIP_ADDRESS2 = "Vila";
var SHIP_LOCATION = "Noida";
var SHIP_PINCODE = 201301;
var SHIP_STATE_CODE = "UTTAR PRADESH";
// Buyer details
var DISPATCH_COMPANY_NAME = "MastersIndia UP";
var DISPATCH_ADDRESS1 = "Vila";
var DISPATCH_ADDRESS2 = "Vila";
var DISPATCH_LOCATION = "Noida";
var DISPATCH_PINCODE = 201301;
var DISPATCH_STATE_CODE = "UTTAR PRADESH";



function set_original_credentials(invoce_data) {
    let consignee_details = JSON.parse(invoce_data.consignee);
    let ledger_details = invoce_data.ledger_details;
    let state_details = invoce_data.state_details;

    var GST_DETAILSS = "";
    if (parseInt(ledger_details.gst_details_status) == 1) {
        console.log('called gst buyer');
        GST_DETAILSS = JSON.parse(ledger_details.gst_details);
        this.BUYER_GSTIN = GST_DETAILSS.Gstin;
        this.BUYER_LEGAL_NAME = GST_DETAILSS.LegalName;
        this.BUYER_TRADE_NAME = GST_DETAILSS.TradeName;
        this.BUYER_ADDRESS1 = GST_DETAILSS.AddrSt;
        this.BUYER_ADDRESS2 = "";//GST_DETAILSS.AddrSt;
        this.BUYER_LOCATION = GST_DETAILSS.AddrLoc;
        this.BUYER_PLACE_OF_SUPPLY = GST_DETAILSS.StateCode;
        this.BUYER_PINCODE = GST_DETAILSS.AddrPncd;
        this.BUYER_STATE_CODE = state_details.state;
        this.BUYER_PHONE = ledger_details.contact;
        this.BUYER_EMAIL = ledger_details.email;
        // consign details   
        if (consignee_details != null) {
            this.SHIP_GSTIN = consignee_details.consigneegst;
            this.SHIP_LEGAL_NAME = consignee_details.consigneename;
            this.SHIP_TRADE_NAME = consignee_details.consigneename;
        }
        this.SHIP_ADDRESS1 = GST_DETAILSS.AddrSt;
        this.SHIP_ADDRESS2 = "";
        this.SHIP_LOCATION = GST_DETAILSS.AddrLoc;
        this.SHIP_PINCODE = GST_DETAILSS.AddrPncd;
        this.SHIP_STATE_CODE = state_details.state;

        var BUSINESS_DETAILS_gst = JSON.parse(BUSINESS_DETAILS.gst_details);
        console.log(BUSINESS_DETAILS_gst, 'BUSINESS_DETAILS_gst');
        if (BUSINESS_DETAILS_gst == null) {
            // alert("Please Update company gst details");
            return true;
        }

        this.USER_GSTIN = BUSINESS_DETAILS_gst.Gstin;
        this.SELLER_GSTIN = BUSINESS_DETAILS_gst.Gstin;
        this.SELLER_LEGAL_NAME = BUSINESS_DETAILS_gst.LegalName;
        this.SELLER_TRADE_NAME = BUSINESS_DETAILS_gst.TradeName;

        this.SELLER_ADDRESS1 = BUSINESS_DETAILS_gst.AddrBno + "," + BUSINESS_DETAILS_gst.AddrBnm + "," + BUSINESS_DETAILS_gst.AddrFlno + "," + BUSINESS_DETAILS_gst.AddrSt;
        this.SELLER_ADDRESS2 = BUSINESS_DETAILS_gst.AddrBno + "," + BUSINESS_DETAILS_gst.AddrBnm + "," + BUSINESS_DETAILS_gst.AddrFlno + "," + BUSINESS_DETAILS_gst.AddrSt;

        this.SELLER_LOCATION = BUSINESS_DETAILS_gst.AddrLoc;
        this.SELLER_PINCODE = BUSINESS_DETAILS_gst.AddrPncd;
        //this.SELLER_STATE_CODE = BUSINESS_DETAILS.state;
        this.SELLER_STATE_CODE = BUSINESS_DETAILS.state_details.code;
        console.log('StateCode : ' + BUSINESS_DETAILS.state_details.code);
        this.SELLER_PHONE = BUSINESS_DETAILS.mobile;
        this.SELLER_EMAIL = BUSINESS_DETAILS.email;

    } else {
        // check_gst(ledger_details);
        without_gst_buyer(ledger_details);
    }

    // Buyer details
    console.log(BUSINESS_DETAILS, 'BUSINESS_DETAILS');
    console.log(BUSINESS_DETAILS_gst, 'BUSINESS_DETAILS_gst');
    this.DISPATCH_COMPANY_NAME = BUSINESS_DETAILS_gst.TradeName;
    this.DISPATCH_ADDRESS1 = BUSINESS_DETAILS_gst.AddrBno + "," + BUSINESS_DETAILS_gst.AddrBnm + "," + BUSINESS_DETAILS_gst.AddrFlno + "," + BUSINESS_DETAILS_gst.AddrSt;
    this.DISPATCH_ADDRESS2 = "";
    this.DISPATCH_LOCATION = BUSINESS_DETAILS_gst.AddrLoc;
    this.DISPATCH_PINCODE = BUSINESS_DETAILS_gst.AddrPncd;
    this.DISPATCH_STATE_CODE = BUSINESS_DETAILS.state_details.state;
    console.log("hello mahesh");
    // generateEinvoice(invoce_data); 
}


function without_gst_buyer(ledger_details) {
    console.log('called without gst buyer');
    this.SUPPLY_TYPE = "EXPWOP";
    this.BUYER_GSTIN = "URP";
    this.BUYER_LEGAL_NAME = ledger_details.name.trim();
    this.BUYER_TRADE_NAME = ledger_details.name.trim();
    this.BUYER_ADDRESS1 = ledger_details.address_1;
    this.BUYER_ADDRESS2 = "";
    this.BUYER_LOCATION = "OTHER TERRITORY";
    this.BUYER_PLACE_OF_SUPPLY = 97;
    this.BUYER_PINCODE = 999999;
    this.BUYER_STATE_CODE = "OTHER TERRITORY";
    this.BUYER_PHONE = ledger_details.contact;
    this.BUYER_EMAIL = ledger_details.email;


    this.SHIP_GSTIN = "URP";
    this.SHIP_LEGAL_NAME = ledger_details.name.trim();
    this.SHIP_TRADE_NAME = ledger_details.name.trim();
    this.SHIP_ADDRESS1 = ledger_details.address_1;
    this.SHIP_ADDRESS2 = "";
    this.SHIP_LOCATION = "OTHER TERRITORY";
    this.SHIP_PINCODE = 999999;
    this.SHIP_STATE_CODE = "OTHER TERRITORY";
}

function getAccessTokenEinvoice(invoice_id = '', type, sales) {
    let domain_url = sales.ewayBillApiMode == "LIVE" ? this.DOMAIN_LIVE + "token-auth" : this.DOMAIN_HREF + "oauth/access_token";

    $.ajax({
        type: "POST",
        url: "" + domain_url + "",
        data: {
            "username": sales.eInvoiceApiMode == "LIVE" ? "sunil.neel88@gmail.com" : "testeway@mastersindia.co",
            "password": sales.eInvoiceApiMode == "LIVE" ? "Sunilneel@123" : "Client!@#Demo987",
            "client_id": sales.eInvoiceApiMode == "LIVE" ? "UxcwyQHSJVBvBPfCfp" : "TMDIIbTwzkCQWFTHpA",
            "client_secret": sales.eInvoiceApiMode == "LIVE" ? "D1wfaoPDeUfGSBhgpjQxWkQl" : "BZpqtmFRIkfIrgjOfhyzQjLX",
            "grant_type": "password"
        },
        success: function (data) {
            let res = JSON.parse(data);
            update_gst_access_token_einvoice(invoice_id, res.access_token, type);
        },
    });
}


function update_gst_access_token_einvoice(invoice_id, access_token, type) {
    $.ajax({
        type: 'POST',
        url: baseurl + "gsp_token_update",
        data: {
            "gsp_access_token": access_token,
            "invoice_id": invoice_id
        },
        success: function (data) {
            let result = JSON.parse(data);
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: result.msg,
                    showConfirmButton: false,
                    timer: 4000
                });
                if (type == "add") {
                    generateEGSTinovice(invoice_id, '');
                } else if (type = "cancel") {
                    cancel_e_invoice(result.sales.irn, invoice_id);
                }
            }
        },
    });
}

// generateEGSTinovice(63039, ''); 
function generateEGSTinoviceConfirm(invoice_id, saveAction) {
    Swal.fire({
        title: "Are you sure ? you want to generate E-Invoice on the einvoice1.gst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            generateEGSTinovice(invoice_id, saveAction);
        }
    });
}
function generateEGSTinovice(invoice_id, saveAction) {

    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_invoice",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);
            // if (data1.result.gsp_access_token_expire == true) {
            //     getAccessTokenEinvoice(invoice_id, 'add', data1.result);
            //     return true;
            // } 

            if(data1.result.all_hsn_verified==false){
                invoiceHsnUpdate(data1.result.id,'INV');
                return true;
            } 

            if (data1.result.eInvoiceApiMode == "LIVE") {
                if ((BUSINESS_DETAILS.gst_details == "") || (BUSINESS_DETAILS.gst_details == null)) {
                    Swal.fire({
                        title: "Seller Gst not found do you want to update Seller GST?",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(baseurl + 'Comp_profile', "_blank");
                        }
                    });
                    $(".loader-bg").css("display", "none");
                    return true;
                }

                if (data1.result.ledger_details.gst == "") {
                    Swal.fire({
                        title: "Party Gst not found do you want to update party GST?",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(baseurl + 'party', "_blank");
                        }
                    });
                    $(".loader-bg").css("display", "none");
                    return true;
                }
                if (data1.result.ledger_details.gst_details_status == 0) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: "Party GST not found with GST service provider",
                        showConfirmButton: false,
                        timer: 5000
                    })
                    $(".loader-bg").css("display", "none");
                    return true;
                }
            }
            generate_gst_invoice(data1.result, saveAction);

        },
    });

}

function generate_gst_invoice(invoice_data, saveAction) {
    if (invoice_data.eInvoiceApiMode == "LIVE") {
        set_original_credentials(invoice_data);
    }
    generateEinvoice(invoice_data);
}

function getHeader() {
    let headers;
    headers = headers.append('Cache-Control', 'no-cache');
    headers = headers.append('Pragma', 'no-cache');
    headers = headers.append('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    headers = headers.append('content-type', 'application/json');
    headers = headers.append('Accept', 'application/json');
    headers = headers.append('Access-Control-Allow-Origin', '*');
    let option = { headers: headers };
    return option;
}

function generateEinvoice(invoice_data) {

    let domain_url = invoice_data.eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;
    var new_roundoff = parseInt(invoice_data.total) - (parseFloat(invoice_data.taxableAmount)
        + parseFloat(invoice_data.totalcgst) +
        parseFloat(invoice_data.totalsgst) +
        parseFloat(invoice_data.totaligst));
    new_roundoff = new_roundoff.toFixed(2);
    
    var requestData = {
        "access_token": invoice_data.gst_access_token,
        "user_gstin": this.USER_GSTIN,//BUSINESS_DETAILS.gstno, // API user required
        "data_source": "erp",
        "transaction_details": {
            "supply_type": this.SUPPLY_TYPE,             // B2C  required
            "charge_type": "N",
            "igst_on_intra": "N",
            "ecommerce_gstin": ""
        },
        "document_details": {
            "document_type": "INV",           // required/ DRN/CRN /INV
            "document_number": invoice_data.invoiceNumber, // invoice number required
            "document_date": invoice_data.invoiceDate // 01/01/2023 DATE FORMATE   required
        },
        "seller_details": {                    // moder computer details
            "gstin": this.SELLER_GSTIN,        // BUSINESS_DETAILS.gstno,          // required
            "legal_name": this.SELLER_LEGAL_NAME,   // BUSINESS_DETAILS.name,          // required
            "trade_name": this.SELLER_TRADE_NAME,
            "address1": this.SELLER_ADDRESS1,   // BUSINESS_DETAILS.address,         // required
            "address2": this.SELLER_ADDRESS2,
            "location": this.SELLER_LOCATION,                      // required
            "pincode": this.SELLER_PINCODE,                        // required
            "state_code": this.SELLER_STATE_CODE,            // required
            "phone_number": this.SELLER_PHONE,
            "email": this.SELLER_EMAIL
        },
        "buyer_details": {               // ledger details             
            "gstin": this.BUYER_GSTIN,// invoice_data.gstNo,     URP  // required
            "legal_name": this.BUYER_LEGAL_NAME,//     TEST  invoice_data.customerName,  // required
            "trade_name": this.BUYER_TRADE_NAME,
            "address1": this.BUYER_ADDRESS1,   // BUSINESS_DETAILS.address,  KOI BHI       // required
            "address2": this.BUYER_ADDRESS2,
            "location": this.BUYER_LOCATION,      // required 
            "place_of_supply": this.BUYER_PLACE_OF_SUPPLY,        // required 
            "pincode": this.BUYER_PINCODE,            // required
            "state_code": this.BUYER_STATE_CODE,      // required
            "phone_number": this.BUYER_PHONE,
            "email": this.BUYER_EMAIL
        },
        "dispatch_details": {       // Buyer details
            "company_name": this.DISPATCH_COMPANY_NAME,// BUSINESS_DETAILS.name,        // required
            "address1": this.DISPATCH_ADDRESS1,// BUSINESS_DETAILS.address,                     // required
            "address2": this.DISPATCH_ADDRESS2,
            "location": this.DISPATCH_LOCATION,                       // required
            "pincode": this.DISPATCH_PINCODE,                         // required
            "state_code": this.DISPATCH_STATE_CODE, // required
        },
        "ship_details": {          // consign details 
            "gstin": this.SHIP_GSTIN,// invoice_data.gstNo,   // required
            "legal_name": this.SHIP_LEGAL_NAME,// invoice_data.customerName,  // required
            "trade_name": this.SHIP_TRADE_NAME,
            "address1": this.SHIP_ADDRESS1,// BUSINESS_DETAILS.address,                     // required
            "address2": this.SHIP_ADDRESS2,
            "location": this.SHIP_LOCATION,                       // required
            "pincode": this.SHIP_PINCODE,                         // required
            "state_code": this.SHIP_STATE_CODE,       // required
        },
        "reference_details": {
            "invoice_remarks": "Invoice Remarks",
            "document_period_details": {
                "invoice_period_start_date": invoice_data.invoiceDate,    // required
                "invoice_period_end_date": invoice_data.invoiceDate      // required
            },
            "preceding_document_details": [{
                "reference_of_original_invoice": invoice_data.invoiceNumber,      // required
                "preceding_invoice_date": invoice_data.invoiceDate,            // required
                "other_reference": "0"
            }]
        },
        "ewaybill_details": ewaybill_details(invoice_data),
        "value_details": {
            "total_assessable_value": parseFloat(invoice_data.taxableAmount), // 4,// 84.75               // required
            "total_cgst_value": parseFloat(invoice_data.totalcgst),// 0,              7.63
            "total_sgst_value": parseFloat(invoice_data.totalsgst),// 0,              7.63
            "total_igst_value": parseFloat(invoice_data.totaligst),// 0.2,
            "total_cess_value": 0,
            "total_cess_value_of_state": 0,
            "total_discount": 0,
            "total_other_charge": 0,
            "total_invoice_value": parseInt(invoice_data.total), // 4.2,//      100.01            // required
            "round_off_amount": parseFloat(new_roundoff), // 0,//             0.01   
            "total_invoice_value_additional_currency": 0
        },
        "item_list": itemDetails(invoice_data.product),
    };    
    
    console.log("Request Data:", JSON.stringify(requestData, null, 2));
    
    $.ajax({
        type: 'POST',
        url: "" + domain_url + "/generateEinvoice",
        data: requestData,
        success: function (data) {
            if (data.results.status == "Success") {
                var resuts_data = data.results.message;
                resuts_data.invoiceNumber = invoice_data.invoiceNumber;
                resuts_data.invoice_id = invoice_data.id;
                save_e_invoice_data(resuts_data);
            }

            if (data.results.status == "Failed") {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: data.results.errorMessage,
                    showConfirmButton: false,
                    timer: 5000
                })
            }

            if (data.results.status == "No Content") {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: data.results.message,
                    showConfirmButton: false,
                    timer: 5000
                })
            }
        },
    });
}

function save_e_invoice_data(invoce_data) {
    $.ajax({
        type: 'POST',
        url: baseurl + "save-e-invoice",
        data: {
            "invoice_data": invoce_data,
        },
        success: function (data) {
            let result = JSON.parse(data);
            // return true;
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "E-Invoice generate successfully.",
                    showConfirmButton: false,
                    timer: 2000
                })
                let action_invoice = localStorage.getItem('saveAction');
                if (parseInt(action_invoice) == 1) {
                    setTimeout(function () {
                        window.location.href = baseurl + 'sale/saleEdit/' + invoce_data.invoice_id + ''
                    }, 5000)
                } else {
                    setTimeout(function () {
                         window.location.href = baseurl + 'sale/saleEdit/' + invoce_data.invoice_id + ''
                    }, 2000)
                }


            }
            $(".loader-bg").css("display", "block");
        },
    });
}


function itemDetails(items) {
    var item_arr = [];
    items.forEach((value) => {
        let obj = {
            "item_serial_number": random_number(),// "502",                    // required
            "product_description": value.product_title,
            "is_service": value.is_service,                                // required
            "hsn_code": value.hsn,//   value.hsn,//                           // required
            "bar_code": "1212",
            "quantity": parseInt(value.qty),// 1,
            "free_quantity": 0,
            "unit": "NOS",
            "unit_price": (parseFloat(value.price) / parseFloat(value.qty)).toFixed(2),//    4, //    // required
            "total_amount": parseFloat(value.price), // 4,      // required
            "pre_tax_value": 0,
            "discount": 0,
            "other_charge": 0,
            "assessable_value": parseFloat(value.price),//                          // required
            "gst_rate": parseInt(value.tax),// 5,                                      // required
            "igst_amount": parseFloat(value.igst),//  0.2,//
            "cgst_amount": parseFloat(value.cgst),//  0, //
            "sgst_amount": parseFloat(value.sgst),// 0, //
            "cess_rate": 0,
            "cess_amount": 0,
            "cess_nonadvol_amount": 0,
            "state_cess_rate": 0,
            "state_cess_amount": 0,
            "state_cess_nonadvol_amount": 0,
            "total_item_value": parseFloat(value.netAmount),//4.2,  // parseFloat(invoice_data.totalValue),//                    // required

            "order_line_reference": "test",
            "product_serial_number": random_number(),
            "batch_details": {
                "name": "aaa",                             // required
                "expiry_date": "31/10/2023",
                "warranty_date": "31/10/2023"
            },
            "attribute_details": [{
                "item_attribute_details": "aaa",
                "item_attribute_value": "147852"
            }]
        }
        item_arr.push(obj);
    });
    console.log(item_arr)
    return item_arr;
}


function random_number() {
    var new_number = Math.floor(Math.random() * 899999 + 100000)
    return new_number;
}


function ewaybill_details(invoice_data) {
    var html = {};
    if (parseInt(invoice_data.eway_bill) == 1) {
        var trans_details = invoice_data.transport_details;
        html = {
            "transporter_id": trans_details.transporter_id,
            "transporter_name": trans_details.transporter_name,// "Jay Trans",
            "transportation_mode": trans_details.transporter_mode,// "1",                      // required
            "transportation_distance": trans_details.transporter_distance,// "0",                 // required
            "transporter_document_number": trans_details.transporter_document,// "1230",
            "transporter_document_date": trans_details.transporter_date,// invoice_data.invoiceDate , 
            "vehicle_number": trans_details.transporter_vh_number,// "PQR1234",
            "vehicle_type": trans_details.transporter_vh_type,// "R"
        }

    }

    return html;
}

$("#eway_bill").click((e) => {
    if (document.getElementById("eway_bill").checked) {
        $("#transporter_details").show();
        $("#transporter_name").prop("required", true);
        $("#transporter_mode").prop("required", true);
        $("#transporter_distance").prop("required", true);
        $("#transporter_document").prop("required", true);
        $("#transporter_date").prop("required", true);
        $("#transporter_vh_number").prop("required", true);
        $("#transporter_vh_type").prop("required", true);
        $("#transporter_id").prop("required", true);

    } else {
        $("#transporter_name").prop("required", false);
        $("#transporter_mode").prop("required", false);
        $("#transporter_distance").prop("required", false);
        $("#transporter_document").prop("required", false);
        $("#transporter_date").prop("required", false);
        $("#transporter_vh_number").prop("required", false);
        $("#transporter_vh_type").prop("required", false);
        $("#transporter_id").prop("required", false);
        $("#transporter_details").hide();
    }
})


/*
$("#transporter_id").change((e) => {
    let gstin = e.target.value;
    $("#transporter_name").val("");
    if (gstin != "") {
        $(".loader").show();
        $.ajax({
            type: 'GET',
            url: "" + this.DOMAIN_HREF + "/gstinDetails",
            data: {
                "access_token": this.ACCESS_TOKEN_LIVE_STATIC,
                "user_gstin": this.USER_GSTIN,
                "gstin": gstin,
            },
            success: function (data) {

                if (data.results.status == "Success") {
                    var gst_details = data.results.message;
                    $("#transporter_name").val(gst_details.LegalName);
                    get_distance(gst_details.AddrPncd, SELLER_PINCODE);
                    // user_gst_details(gst_details);  
                }
                if (data.results.status == "Failed") {

                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: data.results.errorMessage,
                        showConfirmButton: false,
                        timer: 8000
                    })
                }
                $(".loader").hide();
            },
        });
    }
})
*/

function cancelRow(irn, is_ewaybill, invoice_id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Cancel it!",
    }).then((result) => {
        if (result.isConfirmed) {

            // } 
        } else {
            Swal.fire({
                position: "top-end",
                icon: "info",
                title: "Cancelled",
                showConfirmButton: false,
                timer: 5000,
            });
        }
    });
}



function cancel_local_invoice(invoice_id, invoice_data) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "cancel-e-invoice",
        data: {
            "access_token": localStorage.getItem('access_token'),
            "user_gstin": this.USER_GSTIN,
            "invoice_id": invoice_id,
            "cancelDate": invoice_data.message.CancelDate,
            "cancel_reason": "1",
            "cancel_remarks": "Wrong entry"
        },
        success: function (data) {
            let result = JSON.parse(data);
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "Invoice Canceled successfully.",
                    showConfirmButton: false,
                    timer: 5000
                })
                setTimeout(function () {
                    location.reload();
                    //  window.location.href = baseurl + 'sale/saleEdit/' + invoice_id + ''
                }, 2000)

            }
        },
    });
}

function cancel_e_invoiceConfirm(irn, invoice_id) {
    Swal.fire({
        title: "Are you sure ? you want to cancel this E-Invoice from the einvoice1.gst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            cancel_e_invoice(irn, invoice_id);
        }
    });

}

function cancel_e_invoice(irn, invoice_id) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_invoice",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);
            cancel_e_invoiceApi(data1.result.Irn, invoice_id, data1);
        },
    });

}

function cancel_e_invoiceApi(irn, invoice_id, data1) {


    let domain_url = data1.result.eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;

    $.ajax({
        type: 'POST',
        url: "" + domain_url + "cancelEinvoice",
        data: {
            "access_token": data1.result.gst_access_token,
            "user_gstin": this.USER_GSTIN,
            "irn": irn,
            "cancel_reason": "1",
            "cancel_remarks": "Wrong entry"
        },
        success: function (data) {
            $(".loader-bg").css("display", "none");
            if (data.results.status == "Success") {
                cancel_local_invoice(invoice_id, data.results);
            }
            if (data.results.status == "Failed") {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.results.errorMessage,
                    showConfirmButton: false,
                    timer: 5000
                })
            }

        },
    });
}



function cancel_local_data_invoice(invoice_id, invoice_msg) {
    $.ajax({
        type: 'POST',
        url: "delete-e-invoice",
        data: {
            "access_token": localStorage.getItem('access_token'),
            "user_gstin": this.USER_GSTIN,
            "invoice_id": invoice_id,
            "cancel_reason": "1",
            "cancel_remarks": "Wrong entry"
        },
        success: function (data) {
            let result = JSON.parse(data);
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: invoice_msg,
                    showConfirmButton: false,
                    timer: 5000
                })
            }
            $(".loader").hide();
        },
    });
}

function user_gst_details(gst_details) {
    $.ajax({
        type: 'POST',
        url: "user-gst-details-update",
        data: {
            "access_token": localStorage.getItem('access_token'),
            "gst_details": gst_details,
        },
        success: function (data) {
            let result = JSON.parse(data);
        },
    });
}



function ledger_gst_details(gst_details) {
    $.ajax({
        type: 'POST',
        url: "gstdetails-lodger-update",
        data: {
            "access_token": localStorage.getItem('access_token'),
            "gst_details": gst_details,
        },
        success: function (data) {
            let result = JSON.parse(data);
        },
    });
    return true;
}

function gst_ledger_update(gst) {
    $.ajax({
        type: 'GET',
        url: "" + this.DOMAIN_HREF + "gstinDetails",
        data: {
            "access_token": this.ACCESS_TOKEN_LIVE_STATIC,
            "user_gstin": this.USER_GSTIN,
            "gstin": gst,
        },
        success: function (data) {
            if (data.results.status == "Success") {
                GST_DETAILSS = data.results.message;
                console.log(GST_DETAILSS, 'GST_DETAILSS 3 function');
                ledger_gst_details(GST_DETAILSS);

            }
        },
    });
}


$("#gst").blur((e) => {
    // let gstin = e.target.value;
    // gst_num_varifie(gstin);
})


function gst_num_varifie(gstin) {
    if (gstin == 'URP') {
        return true
    }
    let eInvoiceApiMode = $("#eInvoiceApiMode").val();
    if (eInvoiceApiMode == undefined) {
        eInvoiceApiMode = BUSINESS_DETAILS.eInvoiceApiMode;
    }
    let domain_url = eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;
    $("#ledger_add_btn_id").addClass('disable-qty');
    $("#ledger_gst_details").val("");
    if (gstin != "") {
        $(".loader").show();
        $.ajax({
            type: 'GET',
            url: "" + domain_url + "gstinDetails",
            data: {
                "access_token": eInvoiceApiMode == "LIVE" ? GST_ACCESS_TOKEN_LIVE : GST_ACCESS_TOKEN,
                "user_gstin": this.USER_GSTIN,
                "gstin": gstin,
            },
            success: function (data) {
                if (data.results.status == "Success") {
                    var gst_details = data.results.message;
                    $("#ledger_gst_details").val("");
                    $("#ledger_gst_details").val(JSON.stringify(gst_details));
                    $("#ledger_gst_details_party").val("");
                    $("#ledger_gst_details_party").val(JSON.stringify(gst_details));
                    $("#ledger_gst_details_branch").val("");
                    $("#ledger_gst_details_branch").val(JSON.stringify(gst_details));
                    $("#ledger_add_btn_id").removeClass('disable-qty');
                    $("#custSave").removeClass('disable-qty');
                    $(".modal-content").removeClass('disable-qty');

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: "GST varified successfully.",
                        showConfirmButton: false,
                        timer: 2000
                    })

                }
                if (data.results.status == "Failed") {
                    $(".modal-content").removeClass('disable-qty');
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: data.results.errorMessage,
                        showConfirmButton: false,
                        timer: 8000
                    })
                }

                if (data.results.status == "No Content") {
                    $(".modal-content").removeClass('disable-qty');
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: data.results.message,
                        showConfirmButton: false,
                        timer: 8000
                    })
                }

                $(".loader").hide();
            },
        });
    }
}


// user gst varifid

$("#gstTaken").blur((e) => {
    let gstin = e.target.value;
    if (gstin == 'URP') {
        return true
    }
    $("#user_add_btn_id").addClass('disable-qty');
    $("#user_gst_details").val("");
    if (gstin != "") {
        $(".loader").show();
        $.ajax({
            type: 'GET',
            url: "" + this.DOMAIN_HREF + "gstinDetails",
            data: {
                "access_token": this.ACCESS_TOKEN_LIVE_STATIC,
                "user_gstin": this.USER_GSTIN,
                "gstin": gstin,
            },
            success: function (data) {
                if (data.results.status == "Success") {
                    var gst_details = data.results.message;
                    $("#user_gst_details").val(JSON.stringify(gst_details));
                    $("#user_add_btn_id").removeClass('disable-qty');

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: "GST varified successfully.",
                        showConfirmButton: false,
                        timer: 2000
                    })

                }
                if (data.results.status == "Failed") {

                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: data.results.errorMessage,
                        showConfirmButton: false,
                        timer: 8000
                    })
                }
                $(".loader").hide();
            },
        });
    }
})




//getEinvoice();

function getEinvoice() {
    $.ajax({
        type: 'GET',
        url: "" + this.DOMAIN_HREF + "getEinvoiceData",
        data: {
            "access_token": this.ACCESS_TOKEN_LIVE_STATIC,
            "gstin": this.USER_GSTIN,
            "irn": '307f2d29b08435bc7671565e2de10eac58ae0d124d9e704ad9b41b515e04550b',
        },
        success: function (data) {
            console.log(data, 'getEinvoice successfully ');
        },
    });
}

function sale_invoice_number(customer) {
    var new_invoice = "";
    var new_invoice1 = "";
    var invoice_number = $("#invoiceNumber").val();
    var num = invoice_number.split('/');
    var num1 = parseInt(num[3]);
    num1 = Math.abs(num1);
    new_invoice = num[0] + `/` + num[1] + `/BC/` + num1;
    new_invoice1 = num[0] + `/` + num[1] + `/BC/`;
    if (customer.gst_details_status == 1) {
        new_invoice = num[0] + `/` + num[1] + `/BB/` + num1;
        new_invoice1 = num[0] + `/` + num[1] + `/BB/`;
    }
    $("#invoiceNumber").val(new_invoice);
    $("#new_invoiceNumber").val(new_invoice1);
}
var sale_add_page = $("#sale_add_page").val();
if (sale_add_page == "true") {
    onready();
}
function onready() {
    var new_invoice = "";
    var new_invoice1 = "";
    var invoice_number = $("#invoiceNumber").val();
    if (invoice_number != undefined) {
        var num = invoice_number.split('/');
        var num1 = parseInt(num[3]);
        num1 = Math.abs(num1);
        new_invoice = num[0] + `/` + num[1] + `/` + num[2] + `/` + num1;
        $("#invoiceNumber").val(new_invoice);
        new_invoice1 = num[0] + `/` + num[1] + `/` + num[2] + `/`;

        $("#new_invoiceNumber").val(new_invoice1);
    }
}


function ask_to_einvoice(invoice_data, saveAction) {
    Swal.fire({
        title: 'Invoiced Saved Successfully </br> Do you want to generate E - Invoice ?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Yes',
        denyButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            generate_gst_invoice(invoice_data, saveAction);
        } else if (result.isDenied) {
            window.location.replace('sale');
        }
    })
}

// getAccessToken();  
function getAccessToken() {
    // update tocken => 6971df2b43e2aa38764a76dd4ebed6ac1908a61d
    $.ajax({
        type: "POST",
        url: "https://clientbasic.mastersindia.co/oauth/access_token",
        data: {
            "username": "testeway@mastersindia.co",
            "password": "Client!@#Demo987",
            "client_id": "TMDIIbTwzkCQWFTHpA",
            "client_secret": "BZpqtmFRIkfIrgjOfhyzQjLX",
            "grant_type": "password"
        },
        success: function (data) {
            console.log(data, 'getAccessToken successfully ');
        },
    });
}


function check_gst(obj) {
    getGstDetails(obj.gst);
}

function getGstDetails(gstin) {
    $.ajax({
        type: 'GET',
        url: "" + this.DOMAIN_HREF + "gstinDetails",
        data: {
            "access_token": this.ACCESS_TOKEN_LIVE_STATIC,
            "user_gstin": this.USER_GSTIN,
            "gstin": gstin,
        },
        success: function (data) {
            if (data.results.status == "Success") {
                var gst_details = data.results.message;
                console.log(gst_details, 'gst_details');
                $("#user_gst_details").val(JSON.stringify(gst_details));
                $("#user_add_btn_id").removeClass('disable-qty');

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: "GST varified successfully.",
                    showConfirmButton: false,
                    timer: 2000
                })

            }
            if (data.results.status == "Failed") {

                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.results.errorMessage,
                    showConfirmButton: false,
                    timer: 8000
                })
            }
            $(".loader").hide();
        },
    });
}



function checkGstDetails() {
    let gst = $("#gst").val();
    var gstTaken = $("#gstTaken").val();
    if (gst == '') {
        gst = gstTaken;
    }
    console.log(gst, 'gst');
    if (gst == "") {
        alert("Please Enter Your Gst");
        return true;
    }
    gst_num_varifie(gst);
}

/*

function getGSTDetailsAccessToken() {
    let eInvoiceApiMode = $("#eInvoiceApiMode").val();
    let gst = $("#gst").val();
    let domain_url = eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;
    $.ajax({
        type: "POST",
        url: "" + domain_url + "oauth/access_token",
        data: {
            "username": eInvoiceApiMode == "LIVE" ? "sunil.neel88@gmail.com" : "testeway@mastersindia.co",
            "password": eInvoiceApiMode == "LIVE" ? "Sunilneel@123" : "Client!@#Demo987",
            "client_id": eInvoiceApiMode == "LIVE" ? "UxcwyQHSJVBvBPfCfp" : "TMDIIbTwzkCQWFTHpA",
            "client_secret": eInvoiceApiMode == "LIVE" ? "D1wfaoPDeUfGSBhgpjQxWkQl" : "BZpqtmFRIkfIrgjOfhyzQjLX",
            "grant_type": "password"
        },
        success: function (data) {
            let res = JSON.parse(data);
            console.log(res, 'res');
            $("#eInvoiceApiModeAccessTokne").val(res.access_token);
            gst_num_varifie(gst);
        },
    });
}

*/

/*  E-credit note code start  */


function createECreditNoteConfirm(invoice_id) {
    Swal.fire({
        title: "Are you sure ? you want to generate E-Credit Note on the einvoice1.gst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            generateECreditNote(invoice_id);
        }
    });
}


function generateECreditNote(invoice_id, saveAction) {

    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_credit_note",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);

            if(data1.result.all_hsn_verified==false){
                invoiceHsnUpdate(data1.result.id,'CRN');
                return true;
            }   
            if (data1.result.eInvoiceApiMode == "LIVE") {
                if ((BUSINESS_DETAILS.gst_details == "") || (BUSINESS_DETAILS.gst_details == null)) {
                    Swal.fire({
                        title: "Seller Gst not found do you want to update Seller GST?",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(baseurl + 'Comp_profile', "_blank");
                        }
                    });
                    $(".loader-bg").css("display", "none");
                    return true;
                }

                if (data1.result.ledger_details.gst == "") {
                    Swal.fire({
                        title: "Party Gst not found do you want to update party GST?",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(baseurl + 'party', "_blank");
                        }
                    });
                    $(".loader-bg").css("display", "none");
                    return true;
                }
                if (data1.result.ledger_details.gst_details_status == 0) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: "Party GST not found with GST service provider",
                        showConfirmButton: false,
                        timer: 5000
                    })
                    $(".loader-bg").css("display", "none");
                    return true;
                }
            }
            generate_gst_creditNote(data1.result, saveAction);

        },
    });

}


function generate_gst_creditNote(invoice_data, saveAction) {
    if (invoice_data.eInvoiceApiMode == "LIVE") {
        set_original_credentials(invoice_data);
    }
    generateEGSTCreditNote(invoice_data);
}


function generateEGSTCreditNote(invoice_data) {

    let domain_url = invoice_data.eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;
    var new_roundoff = parseInt(invoice_data.total) - (parseFloat(invoice_data.taxableAmount)
        + parseFloat(invoice_data.totalcgst) +
        parseFloat(invoice_data.totalsgst) +
        parseFloat(invoice_data.totaligst));
    new_roundoff = new_roundoff.toFixed(2);
    $.ajax({
        type: 'POST',
        url: "" + domain_url + "/generateEinvoice",
        data: {
            "access_token": invoice_data.gst_access_token,
            "user_gstin": this.USER_GSTIN,//BUSINESS_DETAILS.gstno, // API user required
            "data_source": "erp",
            "transaction_details": {
                "supply_type": this.SUPPLY_TYPE,             // B2C  required
                "charge_type": "N",
                "igst_on_intra": "N",
                "ecommerce_gstin": ""
            },
            "document_details": {
                "document_type": "CRN",           // required/ DRN/CRN /INV
                "document_number": invoice_data.invoiceNumber, // invoice number required
                "document_date": invoice_data.invoiceDate // 01/01/2023 DATE FORMATE   required
            },
            "seller_details": {                    // moder computer details
                "gstin": this.SELLER_GSTIN,        // BUSINESS_DETAILS.gstno,          // required
                "legal_name": this.SELLER_LEGAL_NAME,   // BUSINESS_DETAILS.name,          // required
                "trade_name": this.SELLER_TRADE_NAME,
                "address1": this.SELLER_ADDRESS1,   // BUSINESS_DETAILS.address,         // required
                "address2": this.SELLER_ADDRESS2,
                "location": this.SELLER_LOCATION,                      // required
                "pincode": this.SELLER_PINCODE,                        // required
                "state_code": this.SELLER_STATE_CODE,            // required
                "phone_number": this.SELLER_PHONE,
                "email": this.SELLER_EMAIL
            },
            "buyer_details": {               // ledger details             
                "gstin": this.BUYER_GSTIN,// invoice_data.gstNo,     URP  // required
                "legal_name": this.BUYER_LEGAL_NAME,//     TEST  invoice_data.customerName,  // required
                "trade_name": this.BUYER_TRADE_NAME,
                "address1": this.BUYER_ADDRESS1,   // BUSINESS_DETAILS.address,  KOI BHI       // required
                "address2": this.BUYER_ADDRESS2,
                "location": this.BUYER_LOCATION,      // required 
                "place_of_supply": this.BUYER_PLACE_OF_SUPPLY,        // required 
                "pincode": this.BUYER_PINCODE,            // required
                "state_code": this.BUYER_STATE_CODE,      // required
                "phone_number": this.BUYER_PHONE,
                "email": this.BUYER_EMAIL
            },
            "dispatch_details": {       // Buyer details
                "company_name": this.DISPATCH_COMPANY_NAME,// BUSINESS_DETAILS.name,        // required
                "address1": this.DISPATCH_ADDRESS1,// BUSINESS_DETAILS.address,                     // required
                "address2": this.DISPATCH_ADDRESS2,
                "location": this.DISPATCH_LOCATION,                       // required
                "pincode": this.DISPATCH_PINCODE,                         // required
                "state_code": this.DISPATCH_STATE_CODE, // required
            },
            "ship_details": {          // consign details 
                "gstin": this.SHIP_GSTIN,// invoice_data.gstNo,   // required
                "legal_name": this.SHIP_LEGAL_NAME,// invoice_data.customerName,  // required
                "trade_name": this.SHIP_TRADE_NAME,
                "address1": this.SHIP_ADDRESS1,// BUSINESS_DETAILS.address,                     // required
                "address2": this.SHIP_ADDRESS2,
                "location": this.SHIP_LOCATION,                       // required
                "pincode": this.SHIP_PINCODE,                         // required
                "state_code": this.SHIP_STATE_CODE,       // required
            },
            "reference_details": {
                "invoice_remarks": "Invoice Remarks",
                "document_period_details": {
                    "invoice_period_start_date": invoice_data.invoiceDate,    // required
                    "invoice_period_end_date": invoice_data.invoiceDate      // required
                },
                "preceding_document_details": [{
                    "reference_of_original_invoice": invoice_data.invoiceNumber,      // required
                    "preceding_invoice_date": invoice_data.invoiceDate,            // required
                    "other_reference": "0"
                }]
            },
            "ewaybill_details": ewaybill_details(invoice_data),
            "value_details": {
                "total_assessable_value": parseFloat(invoice_data.taxableAmount), // 4,// 84.75               // required
                "total_cgst_value": parseFloat(invoice_data.totalcgst),// 0,              7.63
                "total_sgst_value": parseFloat(invoice_data.totalsgst),// 0,              7.63
                "total_igst_value": parseFloat(invoice_data.totaligst),// 0.2,
                "total_cess_value": 0,
                "total_cess_value_of_state": 0,
                "total_discount": 0,
                "total_other_charge": 0,
                "total_invoice_value": parseInt(invoice_data.total), // 4.2,//      100.01            // required
                "round_off_amount": parseFloat(new_roundoff), // 0,//             0.01   
                "total_invoice_value_additional_currency": 0
            },
            "item_list": itemDetails(invoice_data.product),
        },
        success: function (data) {
            if (data.results.status == "Success") {
                var resuts_data = data.results.message;
                resuts_data.invoiceNumber = invoice_data.invoiceNumber;
                resuts_data.invoice_id = invoice_data.id;
                saveECreditNote(resuts_data);
            }

            if (data.results.status == "Failed") {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: data.results.errorMessage,
                    showConfirmButton: false,
                    timer: 5000
                })
            }

            if (data.results.status == "No Content") {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: data.results.message,
                    showConfirmButton: false,
                    timer: 5000
                })
            }
        },
    });
}

function saveECreditNote(invoce_data) {
    $.ajax({
        type: 'POST',
        url: baseurl + "save-e-credit-note",
        data: {
            "invoice_data": invoce_data,
        },
        success: function (data) {
            let result = JSON.parse(data);
            // return true;
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "E-Credit Note generate successfully.",
                    showConfirmButton: false,
                    timer: 2000
                })
                let action_invoice = localStorage.getItem('saveAction');
                if (parseInt(action_invoice) == 1) {
                    setTimeout(function () {
                        location.reload();  
                        // window.location.href = baseurl + 'sale/saleEdit/' + invoce_data.invoice_id + ''
                    }, 5000)
                } else {
                    setTimeout(function () {
                        location.reload();  
                        // window.location.href = baseurl + 'sale/saleEdit/' + invoce_data.invoice_id + ''
                    }, 2000)
                }


            }
            $(".loader-bg").css("display", "block");
        },
    });
}


function cancel_e_CRNConfirm(irn, invoice_id) {
    Swal.fire({
        title: "Are you sure ? you want to cancel this E-Credit Note from the einvoice1.gst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            cancel_eCRN_invoice(irn, invoice_id);
        }
    });

}


function cancel_eCRN_invoice(irn, invoice_id) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_credit_note",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);
            cancel_eCRN_invoiceApi(data1.result.Irn, invoice_id, data1);
        },
    });

}

function cancel_eCRN_invoiceApi(irn, invoice_id, data1) {


    let domain_url = data1.result.eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;

    $.ajax({
        type: 'POST',
        url: "" + domain_url + "cancelEinvoice",
        data: {
            "access_token": data1.result.gst_access_token,
            "user_gstin": this.USER_GSTIN,
            "irn": irn,
            "cancel_reason": "1",
            "cancel_remarks": "Wrong entry"
        },
        success: function (data) {
            $(".loader-bg").css("display", "none");
            if (data.results.status == "Success") {
                cancel_local_eCRNinvoice(invoice_id, data.results);
            }
            if (data.results.status == "Failed") {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.results.errorMessage,
                    showConfirmButton: false,
                    timer: 5000
                })
            }

        },
    });
}


function cancel_local_eCRNinvoice(invoice_id, invoice_data) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "cancel-eCRN-invoice",
        data: {
            "access_token": localStorage.getItem('access_token'),
            "user_gstin": this.USER_GSTIN,
            "invoice_id": invoice_id,
            "cancelDate": invoice_data.message.CancelDate,
            "cancel_reason": "1",
            "cancel_remarks": "Wrong entry"
        },
        success: function (data) {
            let result = JSON.parse(data);
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "E-Credit Note Canceled successfully.",
                    showConfirmButton: false,
                    timer: 5000
                })
                setTimeout(function () {
                    location.reload();
                    //  window.location.href = baseurl + 'sale/saleEdit/' + invoice_id + ''
                }, 2000)

            }
        },
    });
}




// E Debit note create code start ----------------

function createEDebitetNoteConfirm(invoice_id) {
    Swal.fire({
        title: "Are you sure ? you want to generate E-Debit Note on the einvoice1.gst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            generateEDebitNote(invoice_id);
        }
    });
}


function generateEDebitNote(invoice_id, saveAction) {

    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_debit_note",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);

            if(data1.result.all_hsn_verified==false){
                invoiceHsnUpdate(data1.result.id,'DBN');
                return true;
            } 

            if (data1.result.eInvoiceApiMode == "LIVE") {
                if ((BUSINESS_DETAILS.gst_details == "") || (BUSINESS_DETAILS.gst_details == null)) {
                    Swal.fire({
                        title: "Seller Gst not found do you want to update Seller GST?",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(baseurl + 'Comp_profile', "_blank");
                        }
                    });
                    $(".loader-bg").css("display", "none");
                    return true;
                }

                if (data1.result.ledger_details.gst == "") {
                    Swal.fire({
                        title: "Party Gst not found do you want to update party GST?",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(baseurl + 'party', "_blank");
                        }
                    });
                    $(".loader-bg").css("display", "none");
                    return true;
                }
                if (data1.result.ledger_details.gst_details_status == 0) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: "Party GST not found with GST service provider",
                        showConfirmButton: false,
                        timer: 5000
                    })
                    $(".loader-bg").css("display", "none");
                    return true;
                }
            }
            generate_gst_debiteNote(data1.result, saveAction);

        },
    });

}


function generate_gst_debiteNote(invoice_data, saveAction) {
    if (invoice_data.eInvoiceApiMode == "LIVE") {
        set_original_credentials(invoice_data);
    }
    generateEGSTDebiteNote(invoice_data);
}


function generateEGSTDebiteNote(invoice_data) {

    let domain_url = invoice_data.eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;
    var new_roundoff = parseInt(invoice_data.total) - (parseFloat(invoice_data.taxableAmount)
        + parseFloat(invoice_data.totalcgst) +
        parseFloat(invoice_data.totalsgst) +
        parseFloat(invoice_data.totaligst));
    new_roundoff = new_roundoff.toFixed(2);
    $.ajax({
        type: 'POST',
        url: "" + domain_url + "/generateEinvoice",
        data: {
            "access_token": invoice_data.gst_access_token,
            "user_gstin": this.USER_GSTIN,//BUSINESS_DETAILS.gstno, // API user required
            "data_source": "erp",
            "transaction_details": {
                "supply_type": this.SUPPLY_TYPE,             // B2C  required
                "charge_type": "N",
                "igst_on_intra": "N",
                "ecommerce_gstin": ""
            },
            "document_details": {
                "document_type": "DBN",           // required/ DBN/CRN /INV
                "document_number": invoice_data.invoiceNumber, // invoice number required
                "document_date": invoice_data.invoiceDate // 01/01/2023 DATE FORMATE   required
            },
            "seller_details": {                    // moder computer details
                "gstin": this.SELLER_GSTIN,        // BUSINESS_DETAILS.gstno,          // required
                "legal_name": this.SELLER_LEGAL_NAME,   // BUSINESS_DETAILS.name,          // required
                "trade_name": this.SELLER_TRADE_NAME,
                "address1": this.SELLER_ADDRESS1,   // BUSINESS_DETAILS.address,         // required
                "address2": this.SELLER_ADDRESS2,
                "location": this.SELLER_LOCATION,                      // required
                "pincode": this.SELLER_PINCODE,                        // required
                "state_code": this.SELLER_STATE_CODE,            // required
                "phone_number": this.SELLER_PHONE,
                "email": this.SELLER_EMAIL
            },
            "buyer_details": {               // ledger details             
                "gstin": this.BUYER_GSTIN,// invoice_data.gstNo,     URP  // required
                "legal_name": this.BUYER_LEGAL_NAME,//     TEST  invoice_data.customerName,  // required
                "trade_name": this.BUYER_TRADE_NAME,
                "address1": this.BUYER_ADDRESS1,   // BUSINESS_DETAILS.address,  KOI BHI       // required
                "address2": this.BUYER_ADDRESS2,
                "location": this.BUYER_LOCATION,      // required 
                "place_of_supply": this.BUYER_PLACE_OF_SUPPLY,        // required 
                "pincode": this.BUYER_PINCODE,            // required
                "state_code": this.BUYER_STATE_CODE,      // required
                "phone_number": this.BUYER_PHONE,
                "email": this.BUYER_EMAIL
            },
            "dispatch_details": {       // Buyer details
                "company_name": this.DISPATCH_COMPANY_NAME,// BUSINESS_DETAILS.name,        // required
                "address1": this.DISPATCH_ADDRESS1,// BUSINESS_DETAILS.address,                     // required
                "address2": this.DISPATCH_ADDRESS2,
                "location": this.DISPATCH_LOCATION,                       // required
                "pincode": this.DISPATCH_PINCODE,                         // required
                "state_code": this.DISPATCH_STATE_CODE, // required
            },
            "ship_details": {          // consign details 
                "gstin": this.SHIP_GSTIN,// invoice_data.gstNo,   // required
                "legal_name": this.SHIP_LEGAL_NAME,// invoice_data.customerName,  // required
                "trade_name": this.SHIP_TRADE_NAME,
                "address1": this.SHIP_ADDRESS1,// BUSINESS_DETAILS.address,                     // required
                "address2": this.SHIP_ADDRESS2,
                "location": this.SHIP_LOCATION,                       // required
                "pincode": this.SHIP_PINCODE,                         // required
                "state_code": this.SHIP_STATE_CODE,       // required
            },
            "reference_details": {
                "invoice_remarks": "Invoice Remarks",
                "document_period_details": {
                    "invoice_period_start_date": invoice_data.invoiceDate,    // required
                    "invoice_period_end_date": invoice_data.invoiceDate      // required
                },
                "preceding_document_details": [{
                    "reference_of_original_invoice": invoice_data.invoiceNumber,      // required
                    "preceding_invoice_date": invoice_data.invoiceDate,            // required
                    "other_reference": "0"
                }]
            },
            "ewaybill_details": ewaybill_details(invoice_data),
            "value_details": {
                "total_assessable_value": parseFloat(invoice_data.taxableAmount), // 4,// 84.75               // required
                "total_cgst_value": parseFloat(invoice_data.totalcgst),// 0,              7.63
                "total_sgst_value": parseFloat(invoice_data.totalsgst),// 0,              7.63
                "total_igst_value": parseFloat(invoice_data.totaligst),// 0.2,
                "total_cess_value": 0,
                "total_cess_value_of_state": 0,
                "total_discount": 0,
                "total_other_charge": 0,
                "total_invoice_value": parseInt(invoice_data.total), // 4.2,//      100.01            // required
                "round_off_amount": parseFloat(new_roundoff), // 0,//             0.01   
                "total_invoice_value_additional_currency": 0
            },
            "item_list": itemDetails(invoice_data.product),
        },
        success: function (data) {
            if (data.results.status == "Success") {
                var resuts_data = data.results.message;
                resuts_data.invoiceNumber = invoice_data.invoiceNumber;
                resuts_data.invoice_id = invoice_data.id;
                saveEDebiteNote(resuts_data);
            }

            if (data.results.status == "Failed") {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: data.results.errorMessage,
                    showConfirmButton: false,
                    timer: 5000
                })
            }

            if (data.results.status == "No Content") {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: data.results.message,
                    showConfirmButton: false,
                    timer: 5000
                })
            }
        },
    });
}

function saveEDebiteNote(invoce_data) {
    $.ajax({
        type: 'POST',
        url: baseurl + "save-e-debit-note",
        data: {
            "invoice_data": invoce_data,
        },
        success: function (data) {
            let result = JSON.parse(data);
            // return true;
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "E-Debit Note generate successfully.",
                    showConfirmButton: false,
                    timer: 2000
                })
                let action_invoice = localStorage.getItem('saveAction');
                if (parseInt(action_invoice) == 1) {
                    setTimeout(function () {
                        location.reload();  
                       // window.location.href = baseurl + 'sale/saleEdit/' + invoce_data.invoice_id + ''
                    }, 5000)
                } else {
                    setTimeout(function () {
                        location.reload();  
                        //window.location.href = baseurl + 'sale/saleEdit/' + invoce_data.invoice_id + ''
                    }, 2000)
                }


            }
            $(".loader-bg").css("display", "block");
        },
    });
}


function cancel_e_DBNConfirm(irn, invoice_id) {
    Swal.fire({
        title: "Are you sure ? you want to cancel this E-Debit Note from the einvoice1.gst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            cancel_eDBN_invoice(irn, invoice_id);
        }
    });

}


function cancel_eDBN_invoice(irn, invoice_id) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_debit_note",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);
            cancel_eDBN_invoiceApi(data1.result.Irn, invoice_id, data1);
        },
    });

}

function cancel_eDBN_invoiceApi(irn, invoice_id, data1) {


    let domain_url = data1.result.eInvoiceApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;

    $.ajax({
        type: 'POST',
        url: "" + domain_url + "cancelEinvoice",
        data: {
            "access_token": data1.result.gst_access_token,
            "user_gstin": this.USER_GSTIN,
            "irn": irn,
            "cancel_reason": "1",
            "cancel_remarks": "Wrong entry"
        },
        success: function (data) {
            $(".loader-bg").css("display", "none");
            if (data.results.status == "Success") {
                cancel_local_eDBNinvoice(invoice_id, data.results);
            }
            if (data.results.status == "Failed") {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.results.errorMessage,
                    showConfirmButton: false,
                    timer: 5000
                })
            }

        },
    });
}


function cancel_local_eDBNinvoice(invoice_id, invoice_data) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "cancel-eDBN-invoice",
        data: {
            "access_token": localStorage.getItem('access_token'),
            "user_gstin": this.USER_GSTIN,
            "invoice_id": invoice_id,
            "cancelDate": invoice_data.message.CancelDate,
            "cancel_reason": "1",
            "cancel_remarks": "Wrong entry"
        },
        success: function (data) {
            let result = JSON.parse(data);
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "E-Debit Note Canceled successfully.",
                    showConfirmButton: false,
                    timer: 5000
                })
                setTimeout(function () {
                    location.reload();
                    //  window.location.href = baseurl + 'sale/saleEdit/' + invoice_id + ''
                }, 2000)

            }
        },
    });
}
// E debit note create code end  ----------------------