<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/


$route['default_controller'] = 'index';

$route['index'] = 'index';
$route['aboutus'] = 'index/aboutus';
$route['products'] = 'index/products';
$route['product-info/(:any)'] = 'index/product_info/$1';
$route['career'] = 'index/career';
$route['downloads'] = 'index/downloads';
$route['contactus'] = 'index/contactus';
$route['forgot-password'] = 'signin/forgot_password';
$route['reset-password'] = 'signin/ResetPassword';
$route['aboutus/certificate-gst'] = 'aboutus/CertificateGST';
$route['aboutus/certificate-edit/(:num)'] = 'aboutus/CertificateEdit/$1';
$route['aboutus/certificate-update/(:num)'] = 'aboutus/CertificateUpdate/$1';
$route['aboutus/certificate-delete/(:num)'] = 'aboutus/CertificateDelete/$1';
$route['aboutus/gst-edit/(:num)'] = 'aboutus/GSTEdit/$1';
$route['aboutus/gst-update/(:num)'] = 'aboutus/GSTUpdate/$1';
$route['aboutus/gst-delete/(:num)'] = 'aboutus/GSTDelete/$1';
$route['privacy-policy'] = 'index/privacypolicy';
$route['terms-of-use'] = 'index/termsofuse';


$route['inventory/dashboard'] = 'inventory_billing/dashboard';
$route['inventory/dashboard/(:num)'] = 'inventory_billing/dashboard/$1';
$route['inventory/dashboard/products'] = 'inventory_billing/dashboard';
$route['inventory/dashboard/products/(:num)'] = 'inventory_billing/dashboard/$1';
$route['inventory/dashboard/outstanding/balance'] = 'inventory_billing/dashboard';
$route['inventory/dashboard/outstanding/balance/(:num)'] = 'inventory_billing/dashboard/$1';

$route['salesman/dashboard'] = 'salesman/dashboard';
$route['salesman/dashboard/(:num)'] = 'salesman/dashboard/$1';
$route['salesman/dashboard/products'] = 'salesman/dashboard';
$route['salesman/dashboard/products/(:num)'] = 'salesman/dashboard/$1';
$route['salesman/dashboard/outstanding/balance'] = 'salesman/dashboard';
$route['salesman/dashboard/outstanding/balance/(:num)'] = 'salesman/dashboard/$1';

$route['userlist'] = 'userlist';
$route['khatabook/viewclient'] = 'khatabook';
$route['client/viewprofile'] = 'khatabook';

$route['slider'] = 'slider';
$route['admin/edit(:any)'] = 'admin/edit/$1';
$route['admin/delete(:any)'] = 'admin/delete/$1';
$route['slider/delete(:any)'] = 'slider/delete/$1';

// $route['uploads'] = 'index';

// $routes['http://localhost/invetory_project/uploads/file_share/'] = 'index';
$route['enquiry'] = 'master/enquiry';
$route['ProductEnquiryReport'] = 'master/ProductEnquiryReport';
$route['carrer-application'] = 'master/application';
$route['printInvoice/(:any)?'] = 'index/printInvoice/$1';
$route['module_assign'] = 'company_assign/module_assign';
$route['digital_certificate'] = 'supermaster/digital_certificate';
$route['edit_digital_certificate/(:any)?'] = 'supermaster/edit_digital_certificate/$1';
$route['editDigitalCertificate/(:any)?'] = 'supermaster/editDigitalCertificate/$1';
$route['edit_career/(:any)?'] = 'career/edit/$1';
$route['delete_career/(:any)?'] = 'career/delete/$1';
$route['edit_blog/(:any)?'] = 'blog/edit/$1';
$route['delete_blog/(:any)?'] = 'blog/delete/$1';
$route['admin'] = 'admin';
$route['terms_and_conditions'] = 'index/terms_and_conditions';
$route['privacy'] = 'index/privacy';
$route['product_and_service'] = 'index/product_and_service';
$route['refunds_policys'] = 'index/refund_policy';
$route['blogs'] = 'index/blogs';
$route['privacy'] = 'index/privacy';
$route['service/(:any)?'] = 'index/service/$1';
$route['check_pay'] = 'index/check_pay';
$route['digital_certificate/scan/(:any)?'] = 'supermaster/scan_digital_certificate/$1';
$route['scan_edit/(:any)?'] = 'supermaster/edit_digital_certificate/$1';
$route['digitalSignatureApply'] = 'supermaster/digitalSignatureApply';
$route['viewDigitalSignature/(:any)?'] = 'supermaster/viewDigitalSignature/$1';
$route['approve_digital_signature'] = 'supermaster/approve_digital_signature';
$route['decline_digital_signature'] = 'supermaster/decline_digital_signature';

$route['service'] = 'service/index';
$route['serviceSave'] = 'service/save';
$route['serviceChangePosition'] = 'service/changePosition';
$route['serviceChangeStatus'] = 'service/changeStatus';
$route['productServiceChangeStatus'] = 'product_service/changeStatus';

$route['store_ecom'] = 'master/store_ecom';
$route['store-users-list'] = 'master/StoreUsersList';

$route['loan_category'] = 'loan/loan_category';
$route['loan_category_edit/(:any)?'] = 'loan/loan_category_edit/$1';
$route['loan_change_status'] = 'loan/loan_change_status';
$route['loan_request'] = 'loan/loan_request';
$route['loan_list'] = 'loan/loan_list';
$route['approve_loan'] = 'loan/approve_loan';
$route['decline_loan'] = 'loan/decline_loan';


$route['loan'] = 'index/loan_list';
$route['applyloan/(:any)?'] = 'index/applyloan/$1';

$route['paymentreceipt'] = 'PaymentReceipt';
//$route['receipt/(:any)'] = 'index/receipt/$1';

// $route['digital_certificate_subservices'] = 'supermaster/digital_certificate_subservices';

// $route['signin'] = 'signin';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['apk'] = 'index/apk';

$route['orderbook'] = 'OrderBook';
$route['orderbook/getOrderNumberByOrderBook'] = 'OrderBook/getOrderNumberByOrderBook';
$route['orderbook/report'] = 'OrderBook/report';

$route['get_invoice']='einvoice/get_invoice'; 
$route['save-e-invoice']='einvoice/save_e_invoice';  
$route['save-e-way-bill']='einvoice/saveEwayBill';  
$route['get_invoice_eway']='einvoice/get_invoice_eway'; 
$route['gsp_token_update']='einvoice/gsp_token_update';
$route['cancel-e-invoice']='einvoice/cancel_e_invoice';
$route['cancel-eway-bill']='einvoice/cancelEwayBill';
$route['update-distance']='einvoice/updateDistance';

// ====================== e-credit note routes================================//
$route['get_credit_note']='einvoice/get_credit_note';
$route['save-e-credit-note']='einvoice/saveECreditNote';
$route['cancel-eCRN-invoice']='einvoice/cancel_eCRN_invoice';  
// ===================== e-debit note routes=================================//
$route['get_debit_note']='einvoice/get_debit_note';
$route['save-e-debit-note']='einvoice/saveEDebitNote';
$route['cancel-eDBN-invoice']='einvoice/cancel_eDBN_invoice'; 


// ==================== GSTR==================================================//
$route['get-invoices-list']='einvoice/getInvoicesList';