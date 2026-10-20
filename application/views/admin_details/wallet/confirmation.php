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
	<script>
		window.onload = function() {
			document.getElementById('paynoes').click();
		}
	</script>
</head>

<body>

	<!-- Bootstrap 4 Navbar  -->

	<!-- End Bootstrap 4 Navbar -->



	<div class="container mt-5">
		<div class="row hidden" hidden>
			<div class="col-md-2"></div>

			<div class="col-md-8">
				<div class="card">
					<h5 class="card-header bg-success text-white">Checkout Confirmation</h5>
					<div class="card-body">
						<form class="form1" action="<?php echo $action; ?>/_payment" method="post" id="payuForm" name="payuForm">
							<input type="hidden" name="key" value="<?php echo $mkey; ?>" />
							<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
							<input type="hidden" name="txnid" value="<?php echo $tid; ?>" />
							<input type="hidden" name="user_id" value="<?php echo $user_id ?>" />

							<div class="form-group">
								<label class="control-label">Total Payable Amount</label>
								<input class="form-control" name="amount" value="<?php echo $amount; ?>" readonly />
							</div>
							<div class="form-group">
								<label class="control-label">Your Name</label>
								<input class="form-control" name="firstname" id="firstname" value="<?php echo $name; ?>" readonly />
							</div>
							<div class="form-group">
								<label class="control-label">Email</label>
								<input class="form-control" name="email" id="email" value="<?php echo $mailid; ?>" readonly />
							</div>
							<div class="form-group">
								<label class="control-label">Phone</label>
								<input class="form-control" name="phone" value="<?php echo $phoneno; ?>" readonly />
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea class="form-control" name="productinfo" readonly><?php echo $productinfo; ?></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Address</label>
								<input class="form-control" name="address1" value="<?php echo $address; ?>" readonly />
							</div>
							<div class="form-group">
								<label class="control-label">Remark</label>
								<textarea class="form-control" name="remark" readonly><?php echo $remark; ?></textarea>
							</div>
							<div class="form-group">
								<input name="surl" value="<?php echo $sucess; ?>" size="64" type="hidden" />
								<input name="furl" value="<?php echo $failure; ?>" size="64" type="hidden" />
								<!--for test environment comment  service provider   -->
								<input type="hidden" name="service_provider" value="payu_paisa" size="64" />
								<input name="curl" value="<?php echo $cancel; ?> " type="hidden" />
							</div>
							<div class="form-group float-right">
								<input type="submit" target="_blank" value="Pay Now" id="paynoes" class="btn btn-success" />
							</div>
						</form>
					</div>
				</div>

			</div>
			<div class="col-md-2"></div>
		</div>
		<!-- Footer -->


	</div>

</body>

</html>