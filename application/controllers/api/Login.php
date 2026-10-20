<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Load the user model
        // $this->load->model('user');
        $this->load->model('user_model');
        $this->load->helper('action_helper');
    }

    public function amount_post()
    {
        // Get the post data
        $user_id = $this->post('user_id');

        // echo '<pre>';

        // Validate the post data
        if (!empty($user_id)) {
            $user_amount = get_title('users', ['id' => $user_id], 'amount');

            if ($user_amount != '') {
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'Amount found',
                    'data' => $user_amount
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'User Not found',
                    'data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
            }
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'User Not found',
                'data' => 0
            ], REST_Controller::HTTP_BAD_REQUEST);
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code

        }
    }

    public function updatetransaction_post()
    {

        // Get the post data

        // $users_id = strip_tags($this->post['users_id']);
        $product_details = strip_tags($this->post('product_details'));
        $shipping_address = $this->post('shipping_address');
        $vat = strip_tags($this->post('vat'));
        $shipping = strip_tags($this->post('shipping'));
        $payment_type = strip_tags($this->post('payment_type'));
        $payment_status = strip_tags($this->post('payment_status'));
        $grand_total = strip_tags($this->post('grand_total'));
        $user_id = strip_tags($this->post('users_id'));
        $sale_code = strip_tags($this->post('sale_code'));

			$created_at = date('Y-m-d');

        // Validate the post data

        if (!empty($user_id) && !empty($grand_total)) {

            $userData = array(
                'user_id' => $user_id,
                'product_details' => $product_details,
                'shipping_address' => $shipping_address,
                'payment_type' => $payment_type,
                'shipping' => $shipping,
                'vat' => $vat,
                'grand_total' => $grand_total,
                'payment_status' => "paid",
                'sale_code' => $sale_code,
                'created_at'=>$created_at

            );


            date_default_timezone_set('Asia/Kolkata');
            $walletdta = array(
                'user_id' => $user_id,
                'amount' => $grand_total,
                'vouchernumber' => $sale_code,
                'voucherdate' => date('d-m-Y h:ia'),
                'paymenttype' => "Wallet",
                'status' => 1,
                'add_amount' => 5,
                'act_status' => "DBT",
                'assign_user' => 1

            );

            $user_amount = get_title('users', ['id' => $user_id], 'amount');
            $admin_amount = get_title('users', ['id' => 1], 'amount');
            $useramounts = ['amount' => $user_amount - $grand_total];
            $useramounts_for_admin = ['amount' => $admin_amount + $grand_total];
            // $insert = $this->user->insert($userData);
            $res = $this->user_model->saveAry('store_ecom', $userData);

            if ($res) {
                $walletdta['store_ecom_id'] = $res;
                $res = $this->user_model->saveAry('wallet', $walletdta);
                if ($res) {
                    $res = $this->user_model->updateAry('users', $useramounts, ['id' => $user_id]);
                    $res = $this->user_model->updateAry('users', $useramounts_for_admin, ['id' => 1]);

                    // Check if the user data is inserted
                    if ($res) {
                        // Set the response and exit
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Your Product has ordered',
                            'data' => $res
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'amount is not updated of user',
                            'data' => "amount is not updated of user"
                        ], REST_Controller::HTTP_BAD_REQUEST);
                        // Set the response and exit
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Product Information is Not Stored.',
                        'data' => "Product Information is Not Stored"
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Transaction Not Completed',
                    'data' => "Transaction Not Completed"
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Transaction Not Completed',
                'data' => "Transaction Not Completed"
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
        // } else {
        //     // Set the response and exit

        //     $this->response("Provide complete user info to add.", REST_Controller::HTTP_BAD_REQUEST);
        // }
    }

 public function update_added_amount_by_payu()
    {
        $grand_total = strip_tags($this->post('amount'));
        $user_id = strip_tags($this->post('user_id'));
        if (!empty($user_id) && !empty($grand_total)) {
            $user_amount = get_title('users', ['id' => $user_id], 'amount');
            $useramounts['amount'] = $grand_total - $user_amount;
            if ($user_amount) {
                $this->user_model->updateAry('users', $useramounts, ['id' => $user_id]);
                $this->response([
                    'status' => TRUE,
                    'message' => 'Amount Added',
                    'data' => $user_amount
                ], REST_Controller::HTTP_OK);
            }
        }
    }
    public function user_get($id = 0)
    {
        // Returns all the users data if the id not specified,
        // Otherwise, a single user will be returned.
        $con = $id ? array('id' => $id) : '';
        $users = $this->user->getRows($con);

        // Check if the user data exists
        if (!empty($users)) {
            // Set the response and exit
            //OK (200) being the HTTP response code
            $this->response($users, REST_Controller::HTTP_OK);
        } else {
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->response([
                'status' => FALSE,
                'message' => 'No user was found.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function user_put()
    {
        $id = $this->put('id');

        // Get the post data
        $first_name = strip_tags($this->put('first_name'));
        $last_name = strip_tags($this->put('last_name'));
        $email = strip_tags($this->put('email'));
        $password = $this->put('password');
        $phone = strip_tags($this->put('phone'));

        // Validate the post data
        if (!empty($id) && (!empty($first_name) || !empty($last_name) || !empty($email) || !empty($password) || !empty($phone))) {
            // Update user's account data
            $userData = array();
            if (!empty($first_name)) {
                $userData['first_name'] = $first_name;
            }
            if (!empty($last_name)) {
                $userData['last_name'] = $last_name;
            }
            if (!empty($email)) {
                $userData['email'] = $email;
            }
            if (!empty($password)) {
                $userData['password'] = md5($password);
            }
            if (!empty($phone)) {
                $userData['phone'] = $phone;
            }
            $update = $this->user->update($userData, $id);

            // Check if the user data is updated
            if ($update) {
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'The user info has been updated successfully.'
                ], REST_Controller::HTTP_OK);
            } else {
                // Set the response and exit
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            // Set the response and exit
            $this->response("Provide at least one user info to update.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
      public function umatic_registration_post()
    {

        // Get the post data

        // $users_id = strip_tags($this->post['users_id']);
        $username = strip_tags($this->post('name'));
        $email = $this->post('email');
        $password = strip_tags($this->post('password'));
        $usertype = 3;

        // Validate the post data

        if (!empty($email) && !empty($username)) {

            $userData = array(
                'name' => $username,
                'email' => $email,
                'password' => $password,
                'userType' => 3,

            );

            $res = $this->user_model->saveAry('users', $userData);
            // Set the response and exit
            $this->response([
                'status' => TRUE,
                'message' => 'User Registered',
                'data' => $res
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'something is missing',
                'data' => "something went wrong"
            ], REST_Controller::HTTP_BAD_REQUEST);
            // Set the response and exit
        }
    }
}
