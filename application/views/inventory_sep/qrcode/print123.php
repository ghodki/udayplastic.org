<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BARCODE Uday Plastic</title>
	<style>
		/* p span b {
			-ms-transform: rotate(90deg);
			/* IE 9 */
		;
		/* } */
	</style>
</head>

<body style="font-size:13px; padding:3em" onload="window.print()">
	<br>

	<div style="margin:3em">
		<h1 style="text-align: center; margin-top:1em">Barcode Report</h1>
		<br>
		<br>
		<div class="row">
			<?php
			// echo '<pre>';
			// print_r($col_md);
			// die;
			if ($result) {
				foreach ($result as $key => $row) {
					$where = ['productID' => $row];
					$qrcode = $this->user_model->getRow('qrcode', $where);
					if ($qrcode) {
						for ($i = 0; $i < $qrcode->qty; $i++) {
			?>

							<div class="text-center col-md-<?= $col_md ?>">
								<!-- <p><b> -->
								<?php
								$where = ['id' => $row];
								$product = $this->user_model->getRow('product', $where);
								$where = ['id' => $this->session->userdata('comp_id')];
								$productCode = $this->user_model->getRow('inventory_billing', $where);
								// if ($productCode) {
								// 	echo $productCode->company_name;
								// }
								?>
								<!-- </b></p> -->
								<?php
								if ($qrcode) {

								?>
									<div class="row">
										<?php
										if ($col_md == 12) {
										?>
											<div class="col-md-1">

											</div>
											<div class="col-md-3">
												<p style="transform: rotate(270deg);margin-right:-200px;margin-top: 62px;"><b><?= $product->p_name; ?></b></p>
											</div>
											<div class="col-md-4">
												<p><b>
														<?php
														if ($productCode) {
															echo $productCode->company_name;
														}
														?>
													</b></p>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:200px;height:100px;">
												<?php
												if ($qrcode) {
												?>
													<p><b>MRP RS.<?= $product->mrp ?></b></p>
													<p><b>PRICE RS.<?= $product->price ?></b></p>
												<?php
												}
												?>

											</div>
										<?php
										} elseif ($col_md == 6) {
										?>
											<!-- <div class="col-md-1">

											</div> -->
											<div class="col-md-4">
												<p style="transform: rotate(270deg);margin-top:62px;"><b><?= $product->p_name; ?></b></p>
											</div>
											<div class="col-md-4">
												<p><b>
														<?php
														if ($productCode) {
															echo $productCode->company_name;
														}
														?>
													</b></p>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:200px;height:100px;margin-left:-30px;">
												<?php
												if ($qrcode) {
												?>
													<p><b>MRP RS.<?= $product->mrp ?></b></p>
													<p><b>PRICE RS.<?= $product->price ?></b></p>
												<?php
												}
												?>

											</div>
										<?php
										} elseif ($col_md == 4) {
										?>

											<div class="col-md-5">
												<p style="transform: rotate(270deg);margin-top:75px;"><b><?= $product->p_name; ?></b></p>
											</div>
											<div class="col-md-6">
												<p><b>
														<?php
														if ($productCode) {
															echo $productCode->company_name;
														}
														?>
													</b></p>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:200px;height:100px;margin-left:-34px;">
												<?php
												if ($qrcode) {
												?>
													<p><b>MRP RS.<?= $product->mrp ?></b></p>
													<p><b>PRICE RS.<?= $product->price ?></b></p>
												<?php
												}
												?>

											</div>
										<?php
										} else {
										?>

											<div class="col-md-6">
												<p style="transform: rotate(270deg);margin-top: 56px;"><b><?= $product->p_name; ?></b></p>
											</div>
											<div class="col-md-6">
												<p><b>
														<?php
														if ($productCode) {
															echo $productCode->company_name;
														}
														?>
													</b></p>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:100px;height:100px;">
												<?php
												if ($qrcode) {
												?>
													<p><b>MRP RS.<?= $product->mrp ?></b></p>
													<p><b>PRICE RS.<?= $product->price ?></b></p>
												<?php
												}
												?>

											</div>
										<?php
										}
										?>

									</div>
								<?php
								}
								?>
								<p><span style="margin-right:1px;">USSs</span> <span style="margin-left:100px;">(incl of All taxes)</span></p>

							</div>






			<?php }
					}
				}
			} ?>
		</div>
	</div>



</body>

<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>

<script>
	// $(document).ready(function() {
	// 	window.print();
	// 	setTimeout("closePrintView()", 5000);
	// });

	// function closePrintView() {
	// 	document.location.href = '<?= base_url("index.php/invent/product") ?>';
	// }
</script>

</html>