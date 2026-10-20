console.log(BUSINESS_DETAILS, 'BUSINESS_DETAILS eway');
function getAccessTokenEway(invoice_id = '', type, salesdata) {
    let domain_url = salesdata.ewayBillApiMode == "LIVE" ? this.DOMAIN_LIVE + "token-auth" : this.DOMAIN_HREF + "oauth/access_token";

    $.ajax({
        type: "POST",
        url: "" + domain_url + "",
        data: {
            "username": salesdata.ewayBillApiMode == "LIVE" ? "sunil.neel88@gmail.com" : "testeway@mastersindia.co",
            "password": salesdata.ewayBillApiMode == "LIVE" ? "Sunilneel@123" : "Client!@#Demo987",
            "client_id": salesdata.ewayBillApiMode == "LIVE" ? "UxcwyQHSJVBvBPfCfp" : "TMDIIbTwzkCQWFTHpA",
            "client_secret": salesdata.ewayBillApiMode == "LIVE" ? "D1wfaoPDeUfGSBhgpjQxWkQl" : "BZpqtmFRIkfIrgjOfhyzQjLX",
            "grant_type": "password"
        },
        success: function (data) {
            let res = JSON.parse(data);
            update_gst_access_token(invoice_id, res.access_token, type);
        },
    });
}

function generateEwayBillConfirm(invoice_id) {

    Swal.fire({
        title: "Are you sure ? you want to Generate Eway Bill on the ewaybillgst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {

            generateEwayBill(invoice_id);
        }
    });
}
// generateEwayBill(63039, '');  
function generateEwayBill(invoice_id) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_invoice_eway",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);
            // if (data1.result.gsp_access_token_expire == true) {
            //     getAccessTokenEway(invoice_id, "add", data1.result);
            //     return true;
            // } 
            if(data1.result.all_hsn_verified==false){
                invoiceHsnUpdate(data1.result.id,'eway');
                return true;
            }   
            let shipping_pincode = JSON.parse(data1.result.shippingInfo).shippingZip;
            if ((shipping_pincode == '') || (shipping_pincode == 0)) {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Party (Shipping) pincode not found",
                    showConfirmButton: false,
                    timer: 5000
                })
                return true;
            }

            if ((BUSINESS_DETAILS.pincode == '') || (BUSINESS_DETAILS.pincode == null)) {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Seller pincode not found",
                    showConfirmButton: false,
                    timer: 5000
                })
                return true;
            }

            if(data1.result.transportation_distance==0){
            getDistance(data1.result, BUSINESS_DETAILS.pincode, shipping_pincode);
            }else{
            ewaybill(data1);
            }
        },
    });

}


function ewaybill(invoce_data) { 
    let results = invoce_data.result;
    let domain_url = results.ewayBillApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;
    let shippingInfo = JSON.parse(invoce_data.result.shippingInfo);
    let consignee_details = results.ledger_details;
    if (BUSINESS_DETAILS.BranchCode != undefined) {  // compnay branch seller details set
        BUSINESS_DETAILS.company_name = BUSINESS_DETAILS.BranchName;
        BUSINESS_DETAILS.gst_no = BUSINESS_DETAILS.GST;
        BUSINESS_DETAILS.address = BUSINESS_DETAILS.Address;
    }

   // let consignee = JSON.parse(consignee_details.gst_details);
    let consignee_state = results.state_details; 
    let ObjStatus = "1";// 1 live 0 text 
    let ObjConsignor = BUSINESS_DETAILS.ewayBillApiMode == "LIVE" ? "1" : "0";// 1 live 0 text 
    let ObjConsignee = BUSINESS_DETAILS.ewayBillApiMode == "LIVE" ? "1" : "0";// 1 live 0 text  
    let party_consignee = shippingInfo.shippingGST!=""?shippingInfo.shippingGST:'URP';
    
    $.ajax({
        type: 'POST',
        url: "" + domain_url + "ewayBillsGenerate",
        data: {
            "access_token": results.gst_access_token,
            "userGstin": ObjConsignor == "1" ? BUSINESS_DETAILS.gst_no : "05AAABB0639G1Z8",// 05AAABB0639G1Z8 //BUSINESS_DETAILS.gstno, // API user required
            "supply_type": "outward",
            "sub_supply_type": "Supply",
            "sub_supply_description": "",
            "document_type": "Tax Invoice",
            "document_number": ObjStatus == "1" ? results.invoiceNumber : "IMP/23/01111",
            "document_date": ObjStatus == "1" ? results.invoiceDate : "22/06/2023",
            "gstin_of_consignor": ObjConsignor == "1" ? BUSINESS_DETAILS.gst_no : "05AAABB0639G1Z8",
            "legal_name_of_consignor": ObjConsignor == "1" ? BUSINESS_DETAILS.company_name : "Welton",
            "address1_of_consignor": ObjConsignor == "1" ? BUSINESS_DETAILS.address : "The Taj Mahal Palace,Apollo Bandar",
            "address2_of_consignor": ObjConsignor == "1" ? "" : "Colaba,Mumbai",
            "place_of_consignor": ObjConsignor == "1" ? BUSINESS_DETAILS.city_name : "Mumbai",
            "pincode_of_consignor": ObjConsignor == "1" ? BUSINESS_DETAILS.pincode : 400001,
            "state_of_consignor": ObjConsignor == "1" ? BUSINESS_DETAILS.state_details.state : "UTTARAKHAND",
            "actual_from_state_name": ObjConsignor == "1" ? BUSINESS_DETAILS.state_details.state : "MAHARASHTRA",
            "gstin_of_consignee": ObjConsignee == "1" ? party_consignee : "05AAABC0181E1ZE",
            "legal_name_of_consignee": ObjConsignee == "1" ? shippingInfo.shippingName : "Sthuthya",
            "address1_of_consignee": ObjConsignee == "1" ? shippingInfo.shippingAddress : "Shree Nilaya",
            "address2_of_consignee": ObjConsignee == "1" ? "" : "Dasarahosahalli",
            "place_of_consignee": ObjConsignee == "1" ? shippingInfo.shippingCity : "Dehradun",
            "pincode_of_consignee": ObjConsignee == "1" ? shippingInfo.shippingZip : 248001,
            "state_of_supply": ObjConsignee == "1" ? consignee_state.state : "UTTARAKHAND",
            "actual_to_state_name": ObjConsignee == "1" ? consignee_state.state : "UTTARAKHAND",
            "transaction_type": 3,
            "other_value": 0,
            "total_invoice_value": ObjStatus == "1" ? results.total : 560,
            "taxable_amount": ObjStatus == "1" ? results.taxableAmount : 500,
            "cgst_amount": ObjStatus == "1" ? results.totalcgst : 30,
            "sgst_amount": ObjStatus == "1" ? results.totalsgst : 30,
            "igst_amount": ObjStatus == "1" ? results.totaligst : 0,
            "cess_amount": ObjStatus == "1" ? results.cess : 0,
            "cess_nonadvol_value": 0,
            "transporter_id": results.ewayBillApiMode == "LIVE" ? BUSINESS_DETAILS.gst_no : "05AAABB0639G1Z8",
            "transporter_name": ObjConsignor == "1" ? BUSINESS_DETAILS.company_name : "Welton",
            "transporter_document_number": results.ewayBillApiMode == "LIVE" ? results.dcNumber : "",
            "transporter_document_date": results.dcDate,
            "transportation_mode": "Road",
            "transportation_distance": results.ewayBillApiMode == "LIVE" ? results.transportation_distance : 1769,
            "vehicle_number": results.vahical_no,
            "vehicle_type": "Regular",
            "generate_status": 1,
            "data_source": "erp",
            "user_ref": "",
            "location_code": "",
            "eway_bill_status": "ABC",
            "auto_print": "N",
            "email": "",
            "delete_record": "N",
            "itemList": ewayitemDetails(results, ObjStatus),
        },
        success: function (data) {
            let response = data.results;

            if (response.status == "Success") {
                save_e_way_bill(response, results.id);
            }
            if (response.status == "No Content") {
                if(data.results.message.errorMessage) {
                    var title = data.results.message.errorMessage;
                } else {
                    var title = data.results.message;
                }
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: title,
                    showConfirmButton: false,
                    timer: 5000
                })
            }
            $(".loader-bg").css("display", "none");
        },
    });

}


function ewayitemDetails(items, ObjStatus) {
    var item_arr = [];
    items.product.forEach((value) => {
        let product_cgst = 0;
        let product_sgst = 0;
        let product_igst = 0;
        if (parseInt(value.igst) > 0) {
            product_igst = value.tax;
        } else {
            product_cgst = value.tax / 2;
            product_sgst = value.tax / 2;
        }
        let obj = {
            "product_name": value.productname,
            "product_description": value.product_dec,
            "hsn_code": value.hsn,
            "quantity": value.qty,
            "unit_of_product": "BOX",
            "cgst_rate": product_cgst,
            "sgst_rate": product_sgst,
            "igst_rate": product_igst,
            "cess_rate": 0,
            "cessNonAdvol": 0,
            "taxable_amount": value.price
        }
        item_arr.push(obj);
    });

    var test_item = [
        {
            "product_name": "Wheat",
            "product_description": "Wheat",
            "hsn_code": "1001",
            "quantity": 1,
            "unit_of_product": "BOX",
            "cgst_rate": 6,
            "sgst_rate": 6,
            "igst_rate": 0,
            "cess_rate": 0,
            "cessNonAdvol": 0,
            "taxable_amount": 100
        },
        {
            "product_name": "Rise",
            "product_description": "Wheat",
            "hsn_code": "1001",
            "quantity": 1,
            "unit_of_product": "BOX",
            "cgst_rate": 6,
            "sgst_rate": 6,
            "igst_rate": 0,
            "cess_rate": 0,
            "cessNonAdvol": 0,
            "taxable_amount": 100
        }
    ];


    if (ObjStatus == 1) {
        return item_arr;
    } else {
        return test_item;
    }

}



function save_e_way_bill(response, invoice_id) {
    $.ajax({
        type: 'POST',
        url: baseurl + "save-e-way-bill",
        data: {
            "invoice_data": response.message,
            "invoice_id": invoice_id
        },
        success: function (data) {
            let result = JSON.parse(data);
            $(".loader-bg").css("display", "none");
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "E-Way Bill generate successfully.",
                    showConfirmButton: false,
                    timer: 2000
                })
                let action_invoice = localStorage.getItem('saveAction');
                if (parseInt(action_invoice) == 1) {
                    setTimeout(function () {
                        window.location.href = baseurl + 'sale/saleEdit/' + invoice_id + ''
                    }, 5000)
                } else {
                    setTimeout(function () {
                        window.location.href = baseurl + 'sale/saleEdit/' + invoice_id + ''
                    }, 2000)
                }


            }

        },
    });
}

function update_gst_access_token(invoice_id, access_token, type) {
    $.ajax({
        type: 'POST',
        url: baseurl + "gsp_token_update",
        data: {
            "gsp_access_token": access_token,
            "invoice_id": invoice_id
        },
        success: function (data) {
            let result = JSON.parse(data);
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: result.msg,
                    showConfirmButton: false,
                    timer: 4000
                });
                if (type == "add") {
                    generateEwayBill(invoice_id);
                } else if (type == "cancel") {
                    ewayBillCancel(result.sales.ewayBillNumber, invoice_id);
                }
            }
        },
    });
}

function ewayBillCancelConfirm(ewayBill, invoice_id) {
    Swal.fire({
        title: "Are you sure ? you want to cancel this Eway Bill from the ewaybillgst.gov.in server !",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            ewayBillCancel(ewayBill, invoice_id);
        }
    });
}


function ewayBillCancel(ewayBill, invoice_id) {
    $(".loader-bg").css("display", "block");
    $.ajax({
        type: 'POST',
        url: baseurl + "get_invoice_eway",
        data: {
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let data1 = JSON.parse(data);
            // if (data1.result.gsp_access_token_expire == true) {
            //     getAccessTokenEway(invoice_id, "cancel", data1.result);
            //     return true;
            // }
            ewayBillCancelApi(ewayBill, invoice_id, data1);
        },
    });
}

function ewayBillCancelApi(ewayBill, invoice_id, data1) {
    let domain_url = data1.result.ewayBillApiMode == "LIVE" ? this.DOMAIN_LIVE : this.DOMAIN_HREF;
    $.ajax({
        type: "POST",
        url: "" + domain_url + "ewayBillCancel",
        data: {
            "access_token": data1.result.gst_access_token,
            "userGstin": data1.result.ewayBillApiMode == "LIVE" ? BUSINESS_DETAILS.gst_no : "05AAABB0639G1Z8",
            "eway_bill_number": ewayBill,
            "reason_of_cancel": "Others",
            "cancel_remark": "Cancelled the order",
            "data_source": "erp"
        },
        success: function (data) {
            let res = data;
            $(".loader-bg").css("display", "none");
            if (res.results.status == "Success") {
                cancel_eway_bill(ewayBill, res, invoice_id);
            }

            if (res.results.status == "No Content") {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: res.results.message,
                    showConfirmButton: false,
                    timer: 5000
                })
            }
        },
    });
}


function cancel_eway_bill(eway_bill, res, invoice_id) {
    $.ajax({
        type: 'POST',
        url: baseurl + "cancel-eway-bill",
        data: {
            "eway_bill": eway_bill,
            "invoice_id": invoice_id,
        },
        success: function (data) {
            let result = JSON.parse(data);
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "E Way Bill Canceled successfully.",
                    showConfirmButton: false,
                    timer: 5000
                })
                setTimeout(function () {
                    location.reload();  
                //   window.location.href = baseurl + 'sale/saleEdit/' + invoice_id + ''
                }, 2000)

            }
        },
    });
}



// Function to get latitude and longitude by pincode
/*
async function getLatLongByPincode(pincode) {
    const apiKey = "AIzaSyCnr2cIpAbxu32ce__6vwmr8vuLWbz5L4M"; // Replace with your API key
    const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${pincode}&key=${apiKey}`;
    try {
        const response = await fetch(url); 
        const data = await response.json();

        if (data.status === "OK") {
            const location = data.results[0].geometry.location; 
            return { lat: location.lat, lng: location.lng }; 
        } else {
            console.error("Geocoding failed:", data.status);
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}


async function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in kilometers
    const dLat = (lat2 - lat1) * (Math.PI / 180);
    const dLon = (lon2 - lon1) * (Math.PI / 180);

    const a = 
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c; // Distance in kilometers
}

async function getDistanceBitweenPincode(pincode1,pincode2){ 
    const latlong1 = await getLatLongByPincode(pincode1);
    const latlong2 = await getLatLongByPincode(pincode2); 
    const distance = await calculateDistance(latlong1.lat, latlong1.lng, latlong2.lat, latlong2.lng);
     let total_distance=parseInt(distance);
     BUSINESS_DETAILS.shipping_distance=total_distance;
     localStorage.setItem("shipping_distance",total_distance);
    return total_distance;
}*/
// Example usage

function getDistance(data1, fromPincode, toPincode) {
    let domain_url = data1.ewayBillApiMode == "LIVE" ? this.DOMAIN_LIVE + "distance" : "https://sandb-api.mastersindia.co/api/v1/distance";
    $.ajax({
        type: 'GET',
        url: "" + domain_url + "",
        data: {
            "access_token": data1.ewayBillApiMode == "LIVE" ? data1.gst_access_token : "6971df2b43e2aa38764a76dd4ebed6ac1908a61d",
            "fromPincode": fromPincode,
            "toPincode":toPincode,
        },
        success: function (data) {
            console.log(data,'data'); 
            if (data.results.status == "Success") {
              update_distance(data.results.distance,data1.id);
            }

            if (data.results.status == "No Content") {
                $(".loader-bg").css("display", "none");
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Party (Shipping) pincode not found",
                    showConfirmButton: false,
                    timer: 5000
                })
                return true;
            }
        },
    });
}

function update_distance(distance,invoice_id){
    $.ajax({
        type: 'POST',
        url: baseurl + "update-distance",
        data: {
            "distance": parseInt(distance),
            "invoice_id": invoice_id
        },
        success: function (data) {
            let result = JSON.parse(data);
            if (result.status == true) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: result.msg,
                    showConfirmButton: false,
                    timer: 4000
                }); 
            generateEwayBill(invoice_id); 
            }
        },
    });
}

