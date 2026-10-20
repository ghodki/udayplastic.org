<?php
function getSubServiceName()
{
    $CI = &get_instance();
    return get_title('crm_servicesubgroup', ['id' => $CI->session->userdata('sub_service_id')], 'sub_service_name');
}

function getServiceName()
{
    $CI = &get_instance();
    return get_title('crm_service', ['id' => $CI->session->userdata('service_id')], 'service_name');
}

function getPartyBalanceReceipt($PartyId)
{
    if ($PartyId) {
        $obj = &get_instance();

        if ($obj->session->userdata('RoleType')) {
            $UserId = $obj->session->userdata('UserId');
            $CompId = $obj->session->userdata('CompId');
        } else {
            $UserId = $obj->session->userdata('user_id');
            $CompId = $obj->session->userdata('comp_id');
        }
        $CompanyBranchId = $obj->session->userdata('CompBranchId');

        $PaymentWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
        if ($obj->session->userdata('BranchRole')) {
            $PaymentWhere['CompanyBranchId'] = $CompanyBranchId;
        }

        $obj->db->select_sum('total');
        $obj->db->where($PaymentWhere);
        $obj->db->where('customerId', $PartyId);
        $obj->db->where('status', 1);
        $sales_result = $obj->db->get('sales')->row();
        // Sale Amount //

        $obj->db->select_sum('debit');
        $obj->db->where($PaymentWhere);
        $obj->db->where('payment_type', "Receipt");
        $obj->db->where('party_id', $PartyId);
        $obj->db->where('status', 1);
        $creditAmount = $obj->db->get('payment')->row();
        // Paid Amount //

        $opening_balance_cr = 0;
        $opening_balance_dbt = 0;
        $opBalnceType = get_title('parties', ['id' => $PartyId], 'opBalnceType');
        if ($opBalnceType == 'CR') {
            $opening_balance_cr = get_title('parties', ['id' => $PartyId], 'opening_balance');
        } else {
            $opening_balance_dbt = get_title('parties', ['id' => $PartyId], 'opening_balance');
        }
        $bal = (($sales_result->total + $opening_balance_dbt) - ($creditAmount->debit + $opening_balance_cr));
        if ($bal <= 0) {
            return -$bal;
        } else {
            return $bal;
        }
    } else {
        return 0;
    }
}

function getPartyBalanceWithEstimateReceipt($PartyId)
{
    if ($PartyId) {
        $obj = &get_instance();

        if ($obj->session->userdata('RoleType')) {
            $UserId = $obj->session->userdata('UserId');
            $CompId = $obj->session->userdata('CompId');
        } else {
            $UserId = $obj->session->userdata('user_id');
            $CompId = $obj->session->userdata('comp_id');
        }
        $CompanyBranchId = $obj->session->userdata('CompBranchId');

        $PaymentWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
        if ($obj->session->userdata('BranchRole')) {
            $PaymentWhere['CompanyBranchId'] = $CompanyBranchId;
        }

        $obj->db->select_sum('total');
        $obj->db->where($PaymentWhere);
        $obj->db->where('customerId', $PartyId);
        $obj->db->where('status', 1);
        $sales_result = $obj->db->get('sales')->row();
        // Sale Amount //

        $obj->db->select_sum('total');
        $obj->db->where($PaymentWhere);
        $obj->db->where('customerId', $PartyId);
        $obj->db->where('status', 1);
        $estimates_result = $obj->db->get('estimates')->row();
        // Estimate Amount //

        $obj->db->select_sum('debit');
        $obj->db->where($PaymentWhere);
        $obj->db->where('payment_type', "Receipt");
        $obj->db->where('party_id', $PartyId);
        $obj->db->where('status', 1);
        $creditAmount = $obj->db->get('payment')->row();

        $opening_balance_cr = 0;
        $opening_balance_dbt = 0;
        $opBalnceType = get_title('parties', ['id' => $PartyId], 'opBalnceType');
        if ($opBalnceType == 'CR') {
            $opening_balance_cr = get_title('parties', ['id' => $PartyId], 'opening_balance');
        } else {
            $opening_balance_dbt = get_title('parties', ['id' => $PartyId], 'opening_balance');
        }
        $bal = (($sales_result->total + $estimates_result->total + $opening_balance_dbt) - ($creditAmount->debit + $opening_balance_cr));
        if ($bal <= 0) {
            return -$bal;
        } else {
            return $bal;
        }
    } else {
        return 0;
    }
}
// infinite coding end here..

function getPartyBalancePayment($PartyId)
{
    if ($PartyId) {
        $obj = &get_instance();

        if ($obj->session->userdata('RoleType')) {
            $UserId = $obj->session->userdata('UserId');
            $CompId = $obj->session->userdata('CompId');
        } else {
            $UserId = $obj->session->userdata('user_id');
            $CompId = $obj->session->userdata('comp_id');
        }
        $CompanyBranchId = $obj->session->userdata('CompBranchId');

        $PaymentWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
        if ($obj->session->userdata('BranchRole')) {
            $PaymentWhere['CompanyBranchId'] = $CompanyBranchId;
        }

        $obj->db->select_sum('total');
        $obj->db->where($PaymentWhere);
        $obj->db->where('customerId', $PartyId);
        $obj->db->where('status', 1);
        $purchase_result = $obj->db->get('purchases')->row();
        // Purchase Amount //

        $obj->db->select_sum('credit');
        $obj->db->where($PaymentWhere);
        $obj->db->where('payment_type', "Payment");
        $obj->db->where('party_id', $PartyId);
        $obj->db->where('status', 1);
        $debitAmount = $obj->db->get('payment')->row();

        $opening_balance_cr = 0;
        $opening_balance_dbt = 0;
        $opBalnceType = get_title('parties', ['id' => $PartyId], 'opBalnceType');
        if ($opBalnceType == 'CR') {
            $opening_balance_cr = get_title('parties', ['id' => $PartyId], 'opening_balance');
        } else {
            $opening_balance_dbt = get_title('parties', ['id' => $PartyId], 'opening_balance');
        }
        $bal =  (($purchase_result->total + $opening_balance_cr) - ($debitAmount->credit + $opening_balance_dbt));
        if ($bal <= 0) {
            return -$bal;
        } else {
            return $bal;
        }
    } else {
        return 0;
    }
}

function getPartyBalanceWithEstimatePayment($PartyId)
{
    if ($PartyId) {
        $obj = &get_instance();

        if ($obj->session->userdata('RoleType')) {
            $UserId = $obj->session->userdata('UserId');
            $CompId = $obj->session->userdata('CompId');
        } else {
            $UserId = $obj->session->userdata('user_id');
            $CompId = $obj->session->userdata('comp_id');
        }
        $CompanyBranchId = $obj->session->userdata('CompBranchId');

        $PaymentWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
        if ($obj->session->userdata('BranchRole')) {
            $PaymentWhere['CompanyBranchId'] = $CompanyBranchId;
        }

        $obj->db->select_sum('total');
        $obj->db->where($PaymentWhere);
        $obj->db->where('customerId', $PartyId);
        $obj->db->where('status', 1);
        $purchase_result = $obj->db->get('purchases')->row();
        // Purchase Amount //

        $obj->db->select_sum('total');
        $obj->db->where($PaymentWhere);
        $obj->db->where('customerId', $PartyId);
        $obj->db->where('status', 1);
        $estimate_purchase_result = $obj->db->get('estimate_purchase')->row();
        // Estimate Purchase Amount //            

        $obj->db->select_sum('credit');
        $obj->db->where($PaymentWhere);
        $obj->db->where('payment_type', "Payment");
        $obj->db->where('party_id', $PartyId);
        $obj->db->where('status', 1);
        $debitAmount = $obj->db->get('payment')->row();

        $opening_balance_cr = 0;
        $opening_balance_dbt = 0;
        $opBalnceType = get_title('parties', ['id' => $PartyId], 'opBalnceType');
        if ($opBalnceType == 'CR') {
            $opening_balance_cr = get_title('parties', ['id' => $PartyId], 'opening_balance');
        } else {
            $opening_balance_dbt = get_title('parties', ['id' => $PartyId], 'opening_balance');
        }
        $bal = (($purchase_result->total + $estimate_purchase_result->total + $opening_balance_cr) - ($debitAmount->credit + $opening_balance_dbt));
        if ($bal <= 0) {
            return -$bal;
        } else {
            return $bal;
        }
    } else {
        return 0;
    }
}
// infinite coding end here..

if (!function_exists('getLedgerAccountBalance')) {
    function getLedgerAccountBalance($ledgerId)
    {
        if ($ledgerId) {
            $obj = &get_instance();

            if ($obj->session->userdata('RoleType')) {
                $userId = $obj->session->userdata('UserId');
                $compId = $obj->session->userdata('CompId');
            } else {
                $userId = $obj->session->userdata('user_id');
                $compId = $obj->session->userdata('comp_id');
            }
            $compBranchId = $obj->session->userdata('CompBranchId');

            $ledgerAccountBalanceWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            if ($obj->session->userdata('BranchRole')) {
                $ledgerAccountBalanceWhere['CompanyBranchId'] = $compBranchId;
            }

            $ledgerAccountInfo = $obj->db->select('opBalnceType, opening_balance')->where($ledgerAccountBalanceWhere)->where('id', $ledgerId)->get('parties')->row();
            // sale amount
            $totalSaleAmount = $obj->db->select_sum('total')->where($ledgerAccountBalanceWhere)->where('customerId', $ledgerId)->get('sales')->row('total');
            $totalEstimateSaleAmount = $obj->db->select_sum('total')->where($ledgerAccountBalanceWhere)->where('customerId', $ledgerId)->get('estimates')->row('total');
            // purchase amount
            $totalPurchaseAmount = $obj->db->select_sum('total')->where($ledgerAccountBalanceWhere)->where('customerId', $ledgerId)->get('purchases')->row('total');
            $totalEstimatePurchaseAmount = $obj->db->select_sum('total')->where($ledgerAccountBalanceWhere)->where('customerId', $ledgerId)->get('estimate_purchase')->row('total');
            // payment credit
            $totalCreditAmount = $obj->db->select_sum('credit')->where($ledgerAccountBalanceWhere)->where(['party_id' => $ledgerId, 'payment_type' => 'Payment'])->get('payment')->row('credit');
            // Receipt debit
            $totalDebitAmount = $obj->db->select_sum('debit')->where($ledgerAccountBalanceWhere)->where(['party_id' => $ledgerId, 'payment_type' => 'Receipt'])->get('payment')->row('debit');
            // journal credit
            $totalJournalCreditAmount = $obj->db->select_sum('credit')->where($ledgerAccountBalanceWhere)->where(['party_id' => $ledgerId, 'payment_type' => 'Journal'])->get('payment')->row('credit');
            // journal debit
            $totalJournalDebitAmount = $obj->db->select_sum('debit')->where($ledgerAccountBalanceWhere)->where(['party_id' => $ledgerId, 'payment_type' => 'Journal'])->get('payment')->row('debit');
            // contra credit
            $totalContraCreditAmount = $obj->db->select_sum('credit')->where($ledgerAccountBalanceWhere)->where(['party_id' => $ledgerId, 'payment_type' => 'Contra'])->get('payment')->row('credit');
            // contra debit
            $totalContraDebitAmount = $obj->db->select_sum('debit')->where($ledgerAccountBalanceWhere)->where(['party_id' => $ledgerId, 'payment_type' => 'Contra'])->get('payment')->row('debit');

            $openingBalanceCredit = 0;
            $openingBalanceDebit = 0;
            if ($ledgerAccountInfo->opBalnceType == 'CR') {
                $openingBalanceCredit = $ledgerAccountInfo->opening_balance;
            } else {
                $openingBalanceDebit = $ledgerAccountInfo->opening_balance;
            }

            $accountBalance = (($totalSaleAmount + $totalEstimateSaleAmount + $totalCreditAmount + $totalJournalCreditAmount + $totalContraCreditAmount + $openingBalanceCredit) - ($totalPurchaseAmount + $totalEstimatePurchaseAmount + $totalDebitAmount + $totalJournalDebitAmount + $totalContraDebitAmount + $openingBalanceDebit));

            if ($accountBalance <= 0) {
                return -$accountBalance;
            } else {
                return $accountBalance;
            }
        } else {
            return 0;
        }
    }
}

function getCrmUrl()
{
    return base_url('crm/');
}

function permissionChecker($data)
{
    $CI = &get_instance();
    $sessionPermission = $CI->session->userdata('master_permission_set');

    if (isset($sessionPermission[$data]) && $sessionPermission[$data]) {
        return true;
    }
    return false;
}

function get_title($table, $condition, $value)
{
    $CI = &get_instance();
    if (empty($table) || empty($value)) {
        return '';
    }
    $row = $CI->db->get_where($table, $condition ?: [])->row();
    if ($row && isset($row->$value)) {
        return $row->$value;
    }
    return '';
}

/**
 * Normalize CI input GET/POST as an array (PHP 8.3-safe for in_array).
 */
function input_get_array($key, $default = [])
{
    $CI = &get_instance();
    $val = $CI->input->get($key);
    if ($val === null || $val === '') {
        return $default;
    }
    return is_array($val) ? $val : [$val];
}

/**
 * PHP 8.3-safe employee permission check from a loaded permission row.
 */
function employee_perm($permRow, $module, $action)
{
    if (empty($permRow) || !is_object($permRow) || empty($module) || empty($action)) {
        return false;
    }
    $json = isset($permRow->$module) ? $permRow->$module : null;
    if ($json === null || $json === '') {
        return false;
    }
    $decoded = json_decode($json);
    return is_object($decoded) && isset($decoded->$action) && (string) $decoded->$action === (string) $action;
}

/**
 * PHP 8.3-safe employee permission check via employee_permission table.
 * $where may be an EmployeeId scalar or a get_title where array.
 */
function employee_has_perm($module, $action, $where = null)
{
    $CI = &get_instance();
    if ($where === null) {
        $where = ['EmployeeId' => $CI->session->userdata('EmployeeId')];
    } elseif (!is_array($where)) {
        $where = ['EmployeeId' => $where];
    }
    $json = get_title('employee_permission', $where, $module);
    if ($json === null || $json === '') {
        return false;
    }
    $decoded = json_decode($json);
    return is_object($decoded) && isset($decoded->$action) && (string) $decoded->$action === (string) $action;
}

function money_formats($number)
{
    return number_format((float) ($number ?? 0), 2);
}

function sendsms($mobileno, $email)
{
    // $sms_api_id = get_title('general_settings', ['general_settings_id' => 88], 'value');
    // $sms_temp_id = get_title('general_settings', ['general_settings_id' => 89], 'value');
    // $message = "Dear User, Thank you for registration with Umatic User Id is " . $email;
    $message = "Dear User, Thank you for registration with Umatic User Id is $email";
    $message = urlencode($message);
    $sender = 'UMATIC';
    $apikey = "92hPY2jpuog7w";
    // $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&rpt=0&summary=1&output=json";
    $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=$apikey&sender=UMATIC&mobile=$mobileno&text=$message&templateid=1207164153202136816&rpt=1&summary=1&output=json";
    // $url = $baseurl.'&sender='.$sender.'&to='.$mobileno.'&message='.$message;    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

function send_service_responce($mobileno = '', $servicename = '', $totalamount = '', $username = '')
{

    // $message = "$username sent a request for $servicename total charge is $totalamount";
    // $message = "New Request received from $username for $servicename";

    $message = "$servicename Request successfully approved Regards Umatic ";
    // $message = "Dear user Your OTP for Forget User Umatic Portal is $servicename Please do not share this OTP. Regards Umatic";
    // $message="Hii $mobileno";
    $message = urlencode($message);
    $sender = 'UMATIC';
    $apikey = '92hPY2jpuog7w';
    // $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&rpt=0&summary=1&output=json";
    $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&templateid=1207164197297764597&rpt=1&summary=1&output=json";
    // $url = $baseurl.'&sender='.$sender.'&to='.$mobileno.'&message='.$message;    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

function send_service_request($username = '')
{

    $message = "New Request received from $username Regards Umatic";
    $message = urlencode($message);
    $mobileno = "9900072629,8884743456";
    $sender = 'UMATIC';
    $apikey = '92hPY2jpuog7w';
    $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&templateid=1207164197282623499&rpt=1&summary=1&output=json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

function send_otp($mobileno = '', $otp = '')
{

    $message = "Dear user Your OTP for Forget User Umatic Portal is $otp. Please do not share this OTP. Regards Umatic";
    $message = urlencode($message);
    $sender = 'UMATIC';
    $apikey = '92hPY2jpuog7w';
    // $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&rpt=0&summary=1&output=json";
    $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&templateid=1207164153209258996&rpt=1&summary=1&output=json";
    // $url = $baseurl.'&sender='.$sender.'&to='.$mobileno.'&message='.$message;    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

function send_sms_for_sale($mobileno = '', $invoice_number = '', $id = '')
{
    $ci_id = &get_instance();
    $name = get_title('comp_profile', ['comp_id' => $ci_id->session->userdata('comp_id'), 'user_id' => $ci_id->session->userdata('user_id')], 'name');
    $saleTemplate = get_title('comp_profile', ['comp_id' => $ci_id->session->userdata('comp_id'), 'user_id' => $ci_id->session->userdata('user_id')], 'saleTemplate');
    if ($saleTemplate == 1) {
        $message = "Dear User, Thank you for Shopping with $name Invoice Value Is $invoice_number UMATIC";
        $tempId = 1207164153194657714;
    } else {
        $url = "https://umatic.in/printInvoice/$id";
        $url = str_replace(' ', '', $url);
        $url = str_replace('.', '.', $url);
        $message = "Dear User, Thank you for Visit with $name Invoice Value Is $invoice_number Download Inv $url Regards Umatic";
        $tempId = 1207165209746845081;
    }
    $message = urlencode($message);
    $sender = 'UMATIC';
    $apikey = '92hPY2jpuog7w';
    $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=$apikey&sender=$sender&mobile=$mobileno&text=$message&templateid=$tempId&rpt=1&summary=1&output=json";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

//Function To Send Sms Using API
function submitsms($mobileno)
{
    $message = "THANK YOU";
    $message = urlencode($message);
    $smsurl = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&rpt=1&summary=1&output=json";

    // $smsurl="http://".$host."/api/pushsms?usr=".$user."&authkey=".$pwd."&sender=".urlencode($sender)."&mobile=".$phoneno."&text=".urlencode($message)."&rpt=".$rpt;
    $response = file_get_contents($smsurl);
    return $response;
}

function send_invoice_on_mobile($mobileno = '', $invoice_number = '', $id = null)
{
    $url = base_url('sale/printSale/' . $id);
    // 	$aman="man.com";
    $ci_id = &get_instance();
    $name = get_title('comp_profile', ['comp_id' => $ci_id->session->userdata('comp_id')], 'name');
    $message = "Dear User, Thank you for Visit with $name Invoice Value Is $invoice_number Download Inv $url Regards Umatic";

    $message = urlencode($message);
    $sender = 'UMATIC';
    $apikey = '92hPY2jpuog7w';
    $url = "http://text.easy2approach.com/api/pushsms?user=UMATICINDIA&authkey=92hPY2jpuog7w&sender=UMATIC&mobile=$mobileno&text=$message&templateid=1207165209746845081&rpt=1&summary=1&output=json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

function payment_receipt_sms($mobile, $rid)
{
    $cid = get_title('payment_receipt_transaction', ['id' => $rid], 'paymentcategory');
    $cname = get_title('payment_receipt_category', ['id' => $cid], 'category');
    $amount = get_title('payment_receipt_transaction', ['id' => $rid], 'amount');
    $rurl = base_url("receipt/" . $rid);
    $url1 = str_replace(' ', '', $rurl);
    $url2 = str_replace('.', '.', $url1);
    $minfo = "Dear User, Thank you for Donate $cname Rs $amount Download receipts $url2 Powered By Umatic";

    $message = urlencode($minfo);
    $sender = 'JITOUM';
    $user = 'UMATICINDIA';
    $apikey = '92hPY2jpuog7w';
    $entityid = 1201164130472942931;
    $tempid = 1207166805989576413;
    $url = "http://text.easy2approach.com/api/pushsms?user=$user&authkey=$apikey&sender=$sender&mobile=$mobile&text=$message&rpt=1&entityid=$entityid&templateid=$tempid";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    //print_r($response);
    //die;
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

function payment_receipt_sms_on_demand($mobile, $senderid, $temp, $id)
{
    if ($temp == "temp_1") {
        $cid = get_title('payment_receipt_transaction', ['id' => $id], 'paymentcategory');
        $cname = get_title('payment_receipt_category', ['id' => $cid], 'category');
        $amount = get_title('payment_receipt_transaction', ['id' => $id], 'amount');
        $rurl = base_url("receipt/" . $id);
        $url1 = str_replace(' ', '', $rurl);
        $url2 = str_replace('.', '.', $url1);
        $minfo = "Dear User, Thank you for Donate $cname Rs $amount Download receipts $url2 Powered By Umatic";

        $message = urlencode($minfo);
        $sender = $senderid;
        $user = 'UMATICINDIA';
        $apikey = '92hPY2jpuog7w';
        $entityid = 1201164130472942931;
        $tempid = 1207166805989576413;
        $url = "http://text.easy2approach.com/api/pushsms?user=$user&authkey=$apikey&sender=$sender&mobile=$mobile&text=$message&rpt=1&entityid=$entityid&templateid=$tempid";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        //print_r($response);
        //die;
        curl_close($ch);

        // Use file get contents when CURL is not installed on server.
        if (!$response) {
            $response = file_get_contents($url);
            return false;
        } else {
            return true;
        }
    }
}

function sms_for_payment($mobile, $pname, $amount, $balance)
{
    $minfo = "Dear User, Thank you for payment $pname of Rs $amount Your Bal Is $balance Regards Umatic";
    $message = urlencode($minfo);
    $sender = "UMATIC";
    $user = 'UMATICINDIA';
    $apikey = '92hPY2jpuog7w';
    $entityid = 1201164130472942931;
    $tempid = 1207165174325099000;
    $url = "http://text.easy2approach.com/api/pushsms?user=$user&authkey=$apikey&sender=$sender&mobile=$mobile&text=$message&rpt=1&entityid=$entityid&templateid=$tempid";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    //print_r($response);
    //die;
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
        $response = file_get_contents($url);
        return false;
    } else {
        return true;
    }
}

function DifferenceFromDates($Date)
{
    return round(abs(strtotime(date('Y-m-d')) - strtotime($Date)) / 86400);
}

function IsLicensed()
{
    $CI = &get_instance();
    if ($CI->session->userdata('RoleType')) {
        $UserId = $CI->session->userdata('UserId');
    } else {
        $UserId = $CI->session->userdata('user_id');
    }

    $CurrentDate = strtotime(date('Y-m-d'));
    $ExpiryDate = strtotime(get_title('users', ['id' => $UserId, 'userType' => 3], 'expiry'));

    if ($CurrentDate <= $ExpiryDate) {
        return true;
    } else {
        return false;
    }
}

function BranchPermission($RowId, $Table)
{
    $CI = &get_instance();
    if ($CI->db->select('id')->where(['id' => $RowId, 'user_id' => $CI->session->userdata('user_id'), 'CompanyBranchId' => $CI->session->userdata('CompBranchId')])->get($Table)->num_rows()) {
        return true;
    } else {
        return false;
    }
}

function amount_in_words($number)
{
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => 'Zero',
        '1' => 'One',
        '2' => 'Two',
        '3' => 'Three',
        '4' => 'Four',
        '5' => 'Five',
        '6' => 'Six',
        '7' => 'Seven',
        '8' => 'Eight',
        '9' => 'Nine',
        '10' => 'Ten',
        '11' => 'Eleven',
        '12' => 'Twelve',
        '13' => 'Thirteen',
        '14' => 'Fourteen',
        '15' => 'Fifteen',
        '16' => 'Sixteen',
        '17' => 'Seventeen',
        '18' => 'Eighteen',
        '19' => 'Nineteen',
        '20' => 'Twenty',
        '30' => 'Thirty',
        '40' => 'Forty',
        '50' => 'Fifty',
        '60' => 'Sixty',
        '70' => 'Seventy',
        '80' => 'Eighty',
        '90' => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
            $str[] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ? "" . $words[$point / 10] . " " . $words[$point = $point % 10] : "";
    return  $result . (!empty($points) ? $points . " Paise Only" : " Only");
}

if (!function_exists('calculate_tax')) {
    function calculate_tax($rate, $tax, $qty, $type)
    {
        $TaxRate = (int) $tax;
        $base_price = $rate * $qty;

        $data['base_price'] = 0;
        $data['taxable_amount'] = 0;
        $data['tax_amount'] = 0;
        $data['net_amount'] = 0;
        $data['cgst'] = 0;
        $data['sgst'] = 0;
        $data['igst'] = 0;
        $data['total'] = 0;
        $data['totalValue'] = 0;
        $data['round_off'] = 0;

        if ($TaxRate != 0) {
            if ($type == 'Exclusive') {
                $TaxAmount = (($base_price * $TaxRate) / 100);

                $data['base_price'] = round($base_price, 2);
                $data['taxable_amount'] = round($base_price, 2);
                $data['tax_amount'] = round($TaxAmount, 2);
                $data['net_amount'] = round(($base_price + $TaxAmount), 2);
                $data['cgst'] = round(($TaxAmount / 2), 2);
                $data['sgst'] = round(($TaxAmount / 2), 2);
                $data['igst'] = round($TaxAmount, 2);
                $data['totalValue'] = round(($base_price + $TaxAmount), 2);
                $data['total'] = round($base_price + $TaxAmount);
                $data['round_off'] = round((((($base_price * $TaxRate) / 100) + $base_price) - $data['total']), 2);
            }

            if ($type == 'Inclusive') {
                $TaxAmount = (($base_price * $tax) / (100 + $TaxRate));

                $data['base_price'] = round($base_price, 2);
                $data['taxable_amount'] = round((($base_price) - $TaxAmount), 2);
                $data['tax_amount'] = round($TaxAmount, 2);
                $data['net_amount'] = round(($base_price), 2);
                $data['cgst'] = round(($TaxAmount / 2), 2);
                $data['sgst'] = round(($TaxAmount / 2), 2);
                $data['igst'] = round($TaxAmount, 2);
                $data['totalValue'] = round($base_price, 2);
                $data['total'] = round($base_price);
                $data['round_off'] = round((round($base_price) - round($base_price, 2)), 2);
            }
        }
        return $data;
    }
}

if (!function_exists('ip_address')) {
    function ip_address()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $ipAddress;
    }
}

if (!function_exists('differenceInHours')) {
    function differenceInHours($startdate, $enddate)
    {
        $totalHours = "I'm working";
        if (($startdate != null) && $enddate != null) {
            $start = new DateTime($startdate);
            $end = new DateTime($enddate);
            // Calculate the difference
            $interval = $start->diff($end);
            $totalHours = $interval->h + ($interval->days * 24);

            $totalHours = $interval->h + $interval->days * 24;
            $totalMinutes = $interval->i;

            $totalHours = $totalHours . ":" . $totalMinutes;
        }
        if (($startdate == null) && $enddate == null) {
            $totalHours = 0;
        }
        return $totalHours;
    }
}

function ProductBalanceQuanity($ProductId)
{
    if ($ProductId != null) {
        $db = &get_instance();
        if ($db->session->userdata('RoleType')) {
            $UserId = $db->session->userdata('UserId');
            $CompId = $db->session->userdata('CompId');
        } else {
            $UserId = $db->session->userdata('user_id');
            $CompId = $db->session->userdata('comp_id');
        }
        $CompBranchId = $db->session->userdata('CompBranchId');

        $EstimateStockWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
        $ProductWhere = ['id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
        $SaleWhere = ['status' => 1, 'type' => 'Sale', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
        $PurchaseWhere = ['status' => 1, 'type' => 'Purchase', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
        $EstimateSaleWhere = ['status' => 1, 'type' => 'Sale', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
        $EstimatePurchaseWhere = ['status' => 1, 'type' => 'Purchase', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
        $TransferToWhere = ['transaction_type' => 'TO', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
        $TransferFromWhere = ['transaction_type' => 'FROM', 'product_id' => $ProductId, 'user_id' => $UserId, 'comp_id' => $CompId];
        $StockManufacturerWhere = ['ProductId' => $ProductId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
        $StockManufacturerDataWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
        $RawMaterialWhere = ['ProductId' => $ProductId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
        $CreditNoteWhere = ['product_id' => $ProductId, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId, 'type' => 'CreditNote'];
        $DebitNoteWhere = ['product_id' => $ProductId, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId, 'type' => 'DebitNote'];
        if ($db->session->userdata('BranchRole')) {
            $EstimateStockWhere['CompanyBranchId'] = $CompBranchId;
            $ProductWhere['CompanyBranchId'] = $CompBranchId;
            $SaleWhere['CompanyBranchId'] = $CompBranchId;
            $PurchaseWhere['CompanyBranchId'] = $CompBranchId;
            $EstimateSaleWhere['CompanyBranchId'] = $CompBranchId;
            $EstimatePurchaseWhere['CompanyBranchId'] = $CompBranchId;
            $TransferToWhere['CompanyBranchId'] = $CompBranchId;
            $TransferFromWhere['CompanyBranchId'] = $CompBranchId;
            $CreditNoteWhere['CompanyBranchId'] = $CompBranchId;
            $DebitNoteWhere['CompanyBranchId'] = $CompBranchId;
        }

        $db->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

        $EstimateStock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');
        $Product = $db->db->select('p_name, qty')->where($ProductWhere)->get('product')->row();
        $Sale = $db->db->select('SUM(qty) as sqty')->where($SaleWhere)->get('invoice_product')->row();
        $Purchase = $db->db->select('SUM(qty) as pqty')->where($PurchaseWhere)->get('invoice_product')->row();
        $EstimateSale = $EstimateStock == "Yes" ? $db->db->select('SUM(qty) as esqty')->where($EstimateSaleWhere)->get('estimate_product')->row() : null;
        $EstimatePurchase = $EstimateStock == "Yes" ? $db->db->select('SUM(qty) as epqty')->where($EstimatePurchaseWhere)->get('estimate_product')->row() : null;
        $TransferTo = $db->db->select('SUM(quantity) as ttqty')->where($TransferToWhere)->get('transfer_products')->row();
        $TransferFrom = $db->db->select('SUM(quantity) as tfqty')->where($TransferFromWhere)->get('transfer_products')->row();
        $StockManufacturer = $db->db->select('SUM(Quantity) as smqty')->where($StockManufacturerWhere)->get('stock_manufacturer_products')->row();
        $RawMaterial = $db->db->select('MainProductId, SUM(Quantity) as rmqty')->where($RawMaterialWhere)->get('raw_material_products')->row();
        $rawMainProductId = is_object($RawMaterial) ? ($RawMaterial->MainProductId ?? null) : null;
        $StockManufacturerData = null;
        if (!empty($rawMainProductId)) {
            $StockManufacturerData = $db->db->select('SUM(Quantity) as smqty_value')->where('ProductId', $rawMainProductId)->where($StockManufacturerDataWhere)->get('stock_manufacturer_products')->row();
        }
        $CreditNote = $db->db->select('SUM(qty) as cnqty')->where($CreditNoteWhere)->get('credit_product')->row();
        $DebitNote = $db->db->select('SUM(qty) as dnqty')->where($DebitNoteWhere)->get('credit_product')->row();

        $productQty = (float) (is_object($Product) ? ($Product->qty ?? 0) : 0);
        $purchaseQty = (float) (is_object($Purchase) ? ($Purchase->pqty ?? 0) : 0);
        $transferToQty = (float) (is_object($TransferTo) ? ($TransferTo->ttqty ?? 0) : 0);
        $estimatePurchaseQty = (float) (is_object($EstimatePurchase) ? ($EstimatePurchase->epqty ?? 0) : 0);
        $stockManuQty = (float) (is_object($StockManufacturer) ? ($StockManufacturer->smqty ?? 0) : 0);
        $debitNoteQty = (float) (is_object($DebitNote) ? ($DebitNote->dnqty ?? 0) : 0);
        $saleQty = (float) (is_object($Sale) ? ($Sale->sqty ?? 0) : 0);
        $transferFromQty = (float) (is_object($TransferFrom) ? ($TransferFrom->tfqty ?? 0) : 0);
        $estimateSaleQty = (float) (is_object($EstimateSale) ? ($EstimateSale->esqty ?? 0) : 0);
        $stockManuDataQty = (float) (is_object($StockManufacturerData) ? ($StockManufacturerData->smqty_value ?? 0) : 0);
        $rawMaterialQty = (float) (is_object($RawMaterial) ? ($RawMaterial->rmqty ?? 0) : 0);
        $creditNoteQty = (float) (is_object($CreditNote) ? ($CreditNote->cnqty ?? 0) : 0);

        return ($productQty + $purchaseQty + $transferToQty + $estimatePurchaseQty + $stockManuQty + $debitNoteQty)
            - ($saleQty + $transferFromQty + $estimateSaleQty + ($stockManuDataQty * $rawMaterialQty) + $creditNoteQty);
    }

    return 0;
}

function DifferenceDays($StartDate, $EndDate)
{
    $startDateTimestamp = strtotime($StartDate);
    $endDateTimestamp = strtotime($EndDate);
    $endDateTimestamp += 24 * 60 * 60;

    $timeDifference = $endDateTimestamp - $startDateTimestamp;
    if ($timeDifference < 0) {
        return 1;
    } else {
        $daysDifference = floor($timeDifference / (60 * 60 * 24));
        return $daysDifference;
    }
}

function isNewDay($LasLoginDatetime, $CurrentDatetime)
{
    $LastLoginDate = date('Y-m-d', strtotime($LasLoginDatetime));
    $CurrentDate = date('Y-m-d', strtotime($CurrentDatetime));

    return $LastLoginDate < $CurrentDate;
}

function indianMoneyFormat($number)
{
    $number = (float) ($number ?? 0);
    $isNegative = $number < 0;
    $number = abs($number);

    $decimal = $number - floor($number);
    $money = floor($number);
    $moneyStr = (string)$money;
    $length = strlen($moneyStr);
    $formattedMoney = '';

    $moneyStr = strrev($moneyStr);

    for ($i = 0; $i < $length; $i++) {
        if ($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) {
            $formattedMoney .= ',';
        }
        $formattedMoney .= $moneyStr[$i];
    }

    $formattedMoney = strrev($formattedMoney);

    if ($decimal > 0) {
        $decimalStr = number_format($decimal, 2);
        $formattedMoney .= substr($decimalStr, 1);  // Removes leading 0 before decimal point
    } else {
        if ($number > 1) {
            $formattedMoney .= '.00';
        }
    }

    // Add negative sign if the original number was negative
    if ($isNegative) {
        $formattedMoney = '-' . $formattedMoney;
    }

    return $formattedMoney;
}

function getRandomColor()
{
    $letters = '0123456789ABCDEF';
    $color = '#';
    for ($i = 0; $i < 6; $i++) {
        $color .= $letters[rand(0, 15)];
    }
    return $color;
}

if (!function_exists('haversineDistance')) {
    function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371; // Earth's radius in kilometers

        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Differences
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        // Haversine formula
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) * 
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c;
    }
}

use Google\Client; // Do not remove
function getAccessToken($serviceAccountPath)
{
    $client = new Client();
    $client->setAuthConfig($serviceAccountPath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->useApplicationDefaultCredentials();
    $token = $client->fetchAccessTokenWithAssertion();
    return $token['access_token'];
}

function sendMessage($accessToken, $projectId, $message)
{
    $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception('Curl error: ' . curl_error($ch));
    }
    curl_close($ch);
    return json_decode($response, true);
}



function checkGspAccessToken($sales)
{ 
    $obj = &get_instance();
    if ($sales->CompanyBranchId == null) {
        $obj->db->select('*');
        $obj->db->where(['id' => $sales->comp_id]);
        $seller_details = $obj->db->get('inventory_billing')->row();
    } else {
        $obj->db->select('*');
        $obj->db->where(['id' => $sales->CompanyBranchId]);
        $seller_details = $obj->db->get('companybranch')->row();
    } 
    
    $data['ewayBillApiMode'] = $seller_details->ewayBillApiMode;
    $data['eInvoiceApiMode'] = $seller_details->eInvoiceApiMode;
    
    if($seller_details->gsp_access_token!=null){
    if ($seller_details->gsp_access_token_expire < time()) {
        $data['token_expire'] = true;
        $data['gst_access_token'] = $seller_details->gsp_access_token; 
    } else {
        $data['token_expire'] = false;
        $data['gst_access_token'] = $seller_details->gsp_access_token;
    }}else{
        $data['token_expire'] = true;
        $data['gst_access_token'] = $seller_details->gsp_access_token;     
    }
    return $data;
}

function getUserPermission()
{

    $db = &get_instance();

    if ($db->session->userdata('RoleType')) {
        $EmployeeId = $db->session->userdata('EmployeeId');
        $UserId = $db->session->userdata('UserId');
        $CompId = $db->session->userdata('CompId');
    } else {
        $UserId = $db->session->userdata('user_id');
        $CompId = $db->session->userdata('comp_id');
    }
    $CompanyBranchId = $db->session->userdata('CompBranchId');

    $Where = ['comp_id' => $CompId, 'user_id' => $UserId];
    if ($db->session->userdata('BranchRole')) {
        $Where['CompanyBranchId'] = $CompanyBranchId;
    }
  
    $db->db->select('*');
    $db->db->where($Where);
    $CompProfile = $db->db->get('comp_profile')->row();
    return $CompProfile; 
}

function getFinancialYearDates($financialYear) {
    // Split the financial year string (e.g., "2024-25")
    [$startYear, $endYear] = explode('-', $financialYear);

    // Ensure proper formatting for dates
    $fromDate = "$startYear-04-01";
    $toDate = "20$endYear-03-31";

    return [
        'fromDate' => $fromDate,
        'toDate' => $toDate,
    ];
}

if (!function_exists('limit_words')) {
    function limit_words($text, $limit = 10) {
        $words = explode(' ', trim($text));
        return implode(' ', array_slice($words, 0, $limit));
    }
}

if (!function_exists('productBalanceQuanityVendor')) {
    function productBalanceQuanityVendor($productId, $userId, $compId, $branchId)
    {
        if ($productId != null) {
            $db = &get_instance();
    
            $EstimateStockWhere = ['user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $ProductWhere = ['id' => $productId, 'user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $SaleWhere = ['status' => 1, 'type' => 'Sale', 'product_id' => $productId, 'user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $PurchaseWhere = ['status' => 1, 'type' => 'Purchase', 'product_id' => $productId, 'user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $EstimateSaleWhere = ['status' => 1, 'type' => 'Sale', 'product_id' => $productId, 'user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $EstimatePurchaseWhere = ['status' => 1, 'type' => 'Purchase', 'product_id' => $productId, 'user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $TransferToWhere = ['transaction_type' => 'TO', 'product_id' => $productId, 'user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $TransferFromWhere = ['transaction_type' => 'FROM', 'product_id' => $productId, 'user_id' => $userId, 'comp_id' => $compId, 'CompanyBranchId' => $branchId];
            $CreditNoteWhere = ['product_id' => $productId, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId, 'type' => 'CreditNote', 'CompanyBranchId' => $branchId];
            $DebitNoteWhere = ['product_id' => $productId, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId, 'type' => 'DebitNote', 'CompanyBranchId' => $branchId];
    
            $db->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    
            $EstimateStock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');
            $Product = $db->db->select('p_name, qty')->where($ProductWhere)->get('product')->row();
            $Sale = $db->db->select('SUM(qty) as sqty')->where($SaleWhere)->get('invoice_product')->row();
            $Purchase = $db->db->select('SUM(qty) as pqty')->where($PurchaseWhere)->get('invoice_product')->row();
            $EstimateSale = $EstimateStock == "Yes" ? $db->db->select('SUM(qty) as esqty')->where($EstimateSaleWhere)->get('estimate_product')->row() : null;
            $EstimatePurchase = $EstimateStock == "Yes" ? $db->db->select('SUM(qty) as epqty')->where($EstimatePurchaseWhere)->get('estimate_product')->row() : null;
            $TransferTo = $db->db->select('SUM(quantity) as ttqty')->where($TransferToWhere)->get('transfer_products')->row();
            $TransferFrom = $db->db->select('SUM(quantity) as tfqty')->where($TransferFromWhere)->get('transfer_products')->row();
            $CreditNote = $db->db->select('SUM(qty) as cnqty')->where($CreditNoteWhere)->get('credit_product')->row();
            $DebitNote = $db->db->select('SUM(qty) as dnqty')->where($DebitNoteWhere)->get('credit_product')->row();

            $productQty = (float) (is_object($Product) ? ($Product->qty ?? 0) : 0);
            $purchaseQty = (float) (is_object($Purchase) ? ($Purchase->pqty ?? 0) : 0);
            $transferToQty = (float) (is_object($TransferTo) ? ($TransferTo->ttqty ?? 0) : 0);
            $estimatePurchaseQty = (float) (is_object($EstimatePurchase) ? ($EstimatePurchase->epqty ?? 0) : 0);
            $debitNoteQty = (float) (is_object($DebitNote) ? ($DebitNote->dnqty ?? 0) : 0);
            $saleQty = (float) (is_object($Sale) ? ($Sale->sqty ?? 0) : 0);
            $transferFromQty = (float) (is_object($TransferFrom) ? ($TransferFrom->tfqty ?? 0) : 0);
            $estimateSaleQty = (float) (is_object($EstimateSale) ? ($EstimateSale->esqty ?? 0) : 0);
            $creditNoteQty = (float) (is_object($CreditNote) ? ($CreditNote->cnqty ?? 0) : 0);
    
            return ($productQty + $purchaseQty + $transferToQty + $estimatePurchaseQty + $debitNoteQty) - ($saleQty + $transferFromQty + $estimateSaleQty + $creditNoteQty);
        }

        return 0;
    }
}

if (!function_exists('loginUser')) {
    function loginUser()
    {
        $db = &get_instance();
        if ($db->session->userdata('RoleType')) {
            $EmployeeId = $db->session->userdata('EmployeeId');
            $UserId = $db->session->userdata('UserId');
            $CompId = $db->session->userdata('CompId');
        } else {
            $UserId = $db->session->userdata('user_id');
            $CompId = $db->session->userdata('comp_id');
        }
        $CompanyBranchId = $db->session->userdata('CompBranchId');
    
        $Where = ['comp_id' => $CompId, 'user_id' => $UserId];
        if ($db->session->userdata('BranchRole')) {
            $Where['CompanyBranchId'] = $CompanyBranchId;
        }
        return $Where;
    }
}


if (!function_exists('getUserDetails')) {
    function getUserDetails()
    {
        $db = &get_instance();
    
        if ($db->session->userdata('BranchRole')) {
            $where_branch = ['Id' => $db->session->userdata('CompBranchId'), 'CompId' => $db->session->userdata('comp_id'), 'BranchUserId' => $db->session->userdata('user_id')];
            $db->db->select('*');
            $db->db->where($where_branch);
            $business_details = $db->db->get('companybranch')->row();
            $business_details->state = $business_details->State;
            $business_details->gst_no = $business_details->GST;
        } else {
            $where_comp = ['id' => $db->session->userdata('comp_id'), 'user_id' => $db->session->userdata('user_id')];
            $db->db->select('*');
            $db->db->where($where_comp);
            $business_details = $db->db->get('inventory_billing')->row();
        }
    
        $db->db->select('*');
        $db->db->where('id', $business_details->state);
        $business_details->state_details = $db->db->get('state_list')->row();
    
        $db->db->select('*');
        $db->db->where('id', $business_details->city);
        $city_details = $db->db->get('districts')->row();
    
        $business_details->city_name = "";
        if (!empty($city_details)) {
            $business_details->city_name = $city_details->name;
        }
    
        return $business_details;
    }
}


if (!function_exists('gstrAccessToken')) {
    function gstrAccessToken($api_type)
    {   
        getGstrAccessToken();  // check access token 
        $userDetails = getUserDetails();  
        
        $domain_url = "https://qa-arapback.mastersindia.co/api/v1/saas-apis/";
        if ($api_type == "Sales") {
            $apiUrl = $domain_url . "sales/";
        } else if ($api_type == "get-gstr-data") {
            $apiUrl = $domain_url . "get-gstr-data-api/";
        } else if ($api_type == "invoice-summary") {
            $apiUrl = $domain_url . "invoice-summary/?screen_key=compare_data";
        } else if ($api_type == "upload-gstr1") {
            $apiUrl = $domain_url . "upload-gstr1/";
        } else if ($api_type == 'get-gstr1-summary') {
            $apiUrl = $domain_url . "get-gstr1-summary/";
        } else if ($api_type == 'list-upload-history') {
            $apiUrl = $domain_url . "list-upload-history/";
        } else if ($api_type == 'proceed-file') {
            $apiUrl = $domain_url . "proceed-file/";
        } else if ($api_type == 'file-submit') {
            $apiUrl = $domain_url . "file-submit/";
        } else if ($api_type == 'gstr1_evc_otp') {
            $apiUrl = $domain_url . "gstr1-evc-otp/";
        }
        // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo0MTY0LCJ1c2VybmFtZSI6InByYXRlZWtyYWkrc2FuZGJveHR3b0BtYXN0ZXJzaW5kaWEuY28iLCJleHAiOjE3MzgzMjA2MTAsImVtYWlsIjoicHJhdGVla3JhaStzYW5kYm94dHdvQG1hc3RlcnNpbmRpYS5jbyIsIm9yaWdfaWF0IjoxNzM4MjM0MjEwfQ.pAdzNBrv0qUw4FlhNxyGBmxNCH6jBz0rPKleiCr8LUo"; // Replace with your actual token
        $token = $userDetails->gstr1AccessToken;
        $data['apiUrl'] = $apiUrl;
        $data['token'] = $token; 
        return $data;
    }
}


if (!function_exists('monthDates')) {
    function monthDates($value)
    {
        $obj = &get_instance();
        $db = &get_instance();
        $financial_year = $obj->session->userdata('financial_year');
        $selectMonth = DateTime::createFromFormat('m/Y', $value);
        $month_value = $selectMonth->format('Y-m');
        $month = $selectMonth->format('m');
        $startDate = (new DateTime($month_value . '-01'))->format('Y-m-d');
        $endDate = (new DateTime($month_value . '-01'))->modify('last day of this month')->format('Y-m-d');
    
        $db->db->select('*');
        $db->db->where('id', $financial_year);
        $data['financial_year'] = $db->db->get('year')->row();
        $data['fromDate'] = $startDate;
        $data['toDate'] = $endDate;
        $data['month'] = $month;
        return $data;
    }
}


if (!function_exists('getGstrAccessToken')) {
    function getGstrAccessToken()
    {
        $db = &get_instance();
        $res['token_status'] =true;
        $userDetails = getUserDetails(); 
        $res['active_till']=date("Y-m-d H:i:s",$userDetails->gstr1_token_expire);
        $res['current_time']=date("Y-m-d H:i:s",time());  
        if(($userDetails->gstr1_token_expire<=time())||($userDetails->gstr1_token_expire=="")){
        if($userDetails->gstr1Mode=="TEST"){
            $url = 'https://qa-arapback.mastersindia.co/api/v1/token-auth/'; // Replace with your API endpoint
            $data = array(
                'username' => 'prateekrai+sandboxtwo@mastersindia.co',
                'password' => 'Password@123'
            ); 
        }  
    
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
    
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch); 
        if ($error) {
            $res['token_status'] =false;
            $res["status"] = false;
            $res['error'] = $error;
            echo 'Error: ' . $error;
        } else {
            $res["status"] = true;
            $token = json_decode($response);
            $res['token'] = $token->token;
            $response;  
            $db->db->where(['gstr1Mode'=>$userDetails->gstr1Mode]); 
            $update_data['gstr1AccessToken']=$res['token'];
            $update_data['gstr1_token_expire']=strtotime("+5 hours"); 
            if ($db->session->userdata('BranchRole')) { 
            $db->db->update('companybranch',$update_data);
            }else{
            $db->db->update('inventory_billing',$update_data); 
            } 
        }
        }
        return $res;
    }
}
