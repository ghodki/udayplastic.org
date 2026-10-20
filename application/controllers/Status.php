<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Status extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('user_model');
		// if ($this->session->userdata('user_id') === null) {
		// 	redirect('index');
		// }
	}

	public function index()
	{
		$status = $this->input->post('status');
		if (empty($status)) {
			redirect('wallet');
		}

		$firstname = $this->input->post('firstname');
		$amount = $this->input->post('amount');
		$txnid = $this->input->post('txnid');
		$posted_hash = $this->input->post('hash');
		$key = $this->input->post('key');
		$productinfo = $this->input->post('productinfo');
		$email = $this->input->post('email');
		$user_id = 0;
		$remark = '';
		$ecommerce_site = '';
		$res = $this->user_model->getRow('payment_unsuccessfull', ['transaction_id_test' => $txnid]);
		if ($res) {

			$user_id = $res->user_id;
			$remark = $res->remark;
			$ecommerce_site = $res->ecommerce_site;

			$this->user_model->deleteRow('payment_unsuccessfull', ['id' => $res->id]);
		}

		$salt = "URW2CKFa"; //  Your salt
		$add = $this->input->post('additionalCharges');
		if (isset($add)) {
			$additionalCharges = $this->input->post('additionalCharges');
			$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		} else {

			$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $amount . '|' . $txnid . '|' . $key;
		}
		$data['hash'] = hash("sha512", $retHashSeq);
		$data['amount'] = $amount;
		$data['txnid'] = $txnid;
		$data['posted_hash'] = $posted_hash;
		$data['status'] = $status;
		$data['user_id'] = $user_id;
		$this->session->set_userdata('user_id', $user_id);
		$this->session->set_userdata('ecommerce_site', $ecommerce_site);


		if ($status == 'success') {
			$store['amount'] = $amount;
			date_default_timezone_set('Asia/Kolkata');
			$store['voucherdate'] = date('d-m-Y h:ia');
			$store['vouchernumber'] = $txnid;
			$store['paymenttype'] = "PayuMoney";
			$store['transactionID'] = $txnid;
			$store['tranDate'] = date('d-m-Y h:ia');
						$store['created_at'] = date('Y-m-d');

			$store['status'] = 1;
			$store['note'] = $remark;
			$store['add_amount'] = 4;
			$store['act_status'] = "CR";
			$store['assign_user'] = $user_id;
			$store['user_id'] = $user_id;
			$this->user_model->saveAry('wallet', $store);
			$there = ['id' => $user_id];
			$wlt = $this->user_model->getRow('users', $there);
			if ($wlt) {
				$amountss = $wlt->amount + $amount;
				$amounts['amount'] = $amountss;
				$userType = $wlt->userType;
				$referral = $wlt->referral;
				$return = $this->user_model->updateAry('users', $amounts, $there);

				// if ($userType == 2) {
				// 	// Subadmin add amount by only admin
				// 	$admin_amount = get_title('users', ['id' => 1], 'amount');
				// 	$admn_amount_deductuion['amount'] = $admin_amount + $amount;
				// 	$this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $this->session->userdata('user_id')]);
				// } else if ($userType == 4) {
				// 	$subadmin_id = get_title('users', ['referral' => $referral, 'userType' => 2], 'id');
				// 	$subadmin_amount = get_title('users', ['referral' => $referral, 'userType' => 2], 'amount');
				// 	$admn_amount_deductuion['amount'] = $subadmin_amount + $amount;
				// 	$this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $subadmin_id]);
				// } else {
				// 	$subadmin_id = get_title('users', ['referral' => $referral, 'userType' => 2], 'id');
				// 	$subadmin_amount = get_title('users', ['referral' => $referral, 'userType' => 2], 'amount');
				// 	if ($subadmin_id) {
				// 		$admn_amount_deductuion['amount'] = $subadmin_amount + $amount;
				// 		$this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => $subadmin_id]);
				// 	} else {
				// 		$admin_amount = get_title('users', ['id' => 1], 'amount');
				// 		$admn_amount_deductuion['amount'] = $admin_amount + $amount;
				// 		$this->user_model->updateAry('users', $admn_amount_deductuion, ['id' => 1]);
				// 	}
				// }

				$this->load->view('success', $data);
			} else {
				$this->load->view('success', $data);
			}
		} else {
			$this->load->view('failure', $data);
		}
	}

	public function outsidecheck()
	{
		$status = $this->input->post('status');
		if (empty($status)) {
			redirect('wallet');
		}

		$firstname = $this->input->post('firstname');
		$amount = $this->input->post('amount');
		$txnid = $this->input->post('txnid');
		$posted_hash = $this->input->post('hash');
		$key = $this->input->post('key');
		$productinfo = $this->input->post('productinfo');
		$email = $this->input->post('email');
		$remark = $this->input->post('remark');
		// $user_id = $this->session->userdata('user_id');


		$salt = "URW2CKFa"; //  Your salt
		$add = $this->input->post('additionalCharges');
		if (isset($add)) {
			$additionalCharges = $this->input->post('additionalCharges');
			$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		} else {

			$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $amount . '|' . $txnid . '|' . $key;
		}
		$data['hash'] = hash("sha512", $retHashSeq);
		$data['amount'] = $amount;
		$data['txnid'] = $txnid;
		$data['posted_hash'] = $posted_hash;
		$data['status'] = $status;
		if ($status == 'success') {
			$store['amount'] = $amount;
			date_default_timezone_set('Asia/Kolkata');
			$store['voucherdate'] = date('d-m-Y h:ia');
						$store['created_at'] = date('Y-m-d');

			$store['vouchernumber'] = $txnid;
			$store['paymenttype'] = "PayuMoney";
			$store['transactionID'] = $txnid;
			$store['tranDate'] = date('d-m-Y h:ia');
			$store['status'] = 1;
			$store['admin_id'] = 1;
			$store['add_amount'] = 4;
			$store['act_status'] = "CR";
			$store['note'] = $remark;
			$store['fullname'] = $firstname;
			$store['assign_user'] = 1;
			$this->user_model->saveAry('wallet', $store);
			$wlt = $this->user_model->getRow('users', ['id' => 1]);
			if ($wlt) {
				$amountss = $wlt->amount + $amount;
				$amounts['amount'] = $amountss;
				$return = $this->user_model->updateAry('users', $amounts, ['id' => 1]);
			}
			// $allsession=$this->session->userdata();

			// $data['page'] = 'admin_details/wallet/success';
			$this->load->view('success', $data);
		} else {
			// $data['page'] = 'success';
			// $this->load->view('success', $data);
			$this->load->view('failure', $data);
		}
	}
}
