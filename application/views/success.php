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


	<!-- End Bootstrap 4 Navbar -->



	<div class="container mt-5" style="margin-top:5rem!important;">
		<div class="row" id="forhidden">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<button class="btn btn-success" onclick="haveToPrint()">Print</button>
			</div>
			<div class="col-md-4">
				<!-- <a href="<?= base_url() ?>index/logindirect/">Back to Dashboard</a> -->
				<?php
				echo "<a href='" . base_url('index.php/userlist/logindirect/') . $this->session->userdata('user_id') . "' class='btn btn-sm float-left btn-info'>Back to Dashboard</a>";
				?>
			</div>


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
					echo "<a href='http://localhost/store/index.php/home/cart_checkout' class='btn btn-sm float-left btn-info'>Continue Shopping</a>";
					// echo "<a href='http://store.umatic.in/index.php/home/cart_checkout' class='btn btn-sm float-left btn-info'>Continue Shopping</a>";

					?>
				</div>


			</div>
		<?php
		}
		?>
		<div class="row">
			<div class="col-md-2">

			</div>
			<div class="col-md-8" style="margin-top: 77px;margin-left: 95px;">
				<div class="card">
					<h4 class="card-header">Transaction <label for="Success" class="badge badge-success">Success</label></h4>
					<div class="card-body">
						<?php

						echo "<p>Thank You. Your order status is " . $status . ".</br>";
						echo "Your Transaction ID for this transaction is<b>" . $txnid . "</b></br>";
						echo "We have received a payment of Rs. " . $amount . ". Your order  will dispatch soon.</p>";
						?>
						<?php
						// echo '<pre>';
						// print_r($this->session->userdata());

						?>
					</div>
				</div>

			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p class="text-center">©2021 Umatic India Pvt Ltd All Rights Reserved</p>

			</div>
		</div>
		<!-- Footer -->


	</div>
	<script>
		function haveToPrint() {
			var val = document.getElementById('forhidden').style.display = "none";
			window.print();
			// closePrintView();
			var val = document.getElementById('forhidden').style.display = "block";


		}

		function closePrintView() {
			var val = document.getElementById('forhidden').style.display = "block";
			// let user_id = document.getElementById('user_id').value;
			// document.location.href = '<?= base_url("index.php/userlist/") ?>' + user_id;
		}
	</script>
</body>

</html>