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



	<div class="container mt-5">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8" style="margin-top: 77px;margin-left: 102px;">
				<div class="card">
					<h4 class="card-header">Transaction <label for="Success" class="badge badge-success">Success</label></h4>
					<div class="card-body">
						<?php
						echo "<p>Thank You. Your order status is " . $status . ".</br>";
						echo "Your Transaction ID for this transaction is " . $txnid . "</br>";
						echo "We have received a payment of Rs. " . $amount . ". Your order  will dispatch soon.</p>";
						?>
					</div>
				</div>

			</div>
			<div class="col-md-2"></div>
		</div>
		<!-- Footer -->


	</div>

</body>

</html>
