<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Umatic</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/js/bootstrap.min.js" />

</head>

<body>

	<!-- Bootstrap 4 Navbar  -->

	<!-- End Bootstrap 4 Navbar -->



	<div class="container mt-5">

		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8 text-center" style="margin-top: 77px;margin-left: 102px;">
				<div class="card">
					<h4 class="card-header">Transaction <label for="failure" class="badge badge-danger">Failed</label></h4>
					<div class="card-body">
						<?php
						echo "<p>Your order status is " . $status . "..</br>";
						echo "Your transaction id for this transaction is <b>" . $txnid . ".</b> <br>Contact our customer support.</p>";
						echo "<a href='" . base_url('index.php/userlist/logindirect/') . $this->session->userdata('user_id') . "' class='btn btn-sm float-left btn-info'> <-Go Back</a>";
						?>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
		<?php
		$ecommerce_site = $this->session->userdata('ecommerce_site');
		if (isset($ecommerce_site) && !empty($ecommerce_site) && $ecommerce_site != '') {

		?>
			<div class="row">
				<div class="col-md-6"></div>

				<div class="col-md-6">
					<!-- <a href="<?= base_url() ?>index/logindirect/">Back to Dashboard</a> -->
					<?php
					echo "<a href='http://localhost/store/index.php/home/cart_checkout' class='btn btn-sm float-left btn-primary'>Continue Shopping</a>";
					// echo "<a href='http://store.umatic.in/index.php/home/cart_checkout' class='btn btn-sm float-left btn-info'>Continue Shopping</a>";

					?>
				</div>


			</div>
		<?php
		}
		?>
		<!-- Footer -->


	</div>

</body>

</html>