<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ForgotPassword extends CI_Controller
{
	function __construct()

	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('user_model');
		// $this->load->library('custom');
		$this->load->helper('email');
		$this->load->helper('action_helper');

		// $this->load->library('My_PHPMailer');
		// require_once(BASE_PATH . 'application/libraries/PHPMailer/PHPMailerAutoload.php');


	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function randomPassword($length = 8)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$length = rand(10, 16);
		$password = substr(str_shuffle(sha1(rand() . time()) . $chars), 0, $length);
		return $password;
	}
	public function forget()
	{
		$email = $this->input->post('email');
		$where = ['email' => $email];

		$query_mail = $this->user_model->getRow('users', $where);
		if ($query_mail) {
			$password = $this->randomPassword();
			$id = $query_mail->id;
			$where = ['id' => $id];

			$data['password'] = $password;
			$query_mail = $this->user_model->updateAry('users', $data, $where);
			$msg = "Password Has Been Sent to Your mail";

			$to  = $email . ', '; // note the comma

			// subject
			$subject = 'Forget Password';

			// message
			$message = '
<html>
<head>
  <title>Forget Password</title>
</head>
<body>
  <p></p>
  <table>
    <tr>
      <th>Email</th><th>Password</th>
    </tr>
 
    <tr>
      <td>' . $email . '</td><td>' . $password . '</td>
    </tr>
  </table>
</body>
</html>
';

			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// Additional headers
			$headers .= 'To:<' . $email . '>' . "\r\n";
			$headers .= 'From: Request Call <' . $email . '>' . "\r\n";
			$headers .= 'Cc: Cc' . "\r\n";
			$headers .= 'Bcc: Bcc' . "\r\n";

			// Mail it
			mail($to, $subject, $message, $headers);
			//Send mail
			$this->email->send();
		} else {
			$msg = "No Account Found For This Email.";
		}
		echo json_encode(["data" => $msg]);
		// $html = "Invalid Email";
		// if ($row) {
		// 	echo '<input type="text" name="code">';
		// } else {
		// 	echo "Invalid Email Address. Go back";
		// }
		// print_r($html);
	}

	public function forget_password()
	{
		$email = $this->input->post('email');
		$query_mail = $this->user_model->getRow('users', $email);
		if (count($query_mail) != 0) {
			$data['user'] = $query_mail;
			echo "Hi, " . $query_mail[0]['c_name'] . " Check your mailbox.";
			$this->load->library('email');
			$config  =  array(
				'mailtype' => 'html',
				'charset'  => 'utf-8',
				'priority' => '1'
			);


			$this->email->initialize($config);
			$this->email->from('forgetpassword@wantpg.com', 'Forgot Password Mail');
			$this->email->to($query_mail[0]['c_mail']);
			$this->email->subject('Password Recovery Mail');
			$emaildescription = $this->load->view('email/email-template-forget-password', $data, TRUE);
			$this->email->message($emaildescription);
			$result = $this->email->send();
		} else {
			echo "No Account Found For This Email.";
		}
	}


	
}
