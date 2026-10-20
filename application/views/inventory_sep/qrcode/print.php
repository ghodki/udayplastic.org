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

<body style="font-size:13px; padding:3em">
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

			$barcode_title = '';
			$barcode_sell_prefix = '';
			$barcode_mrp_prefix = '';
			$company_details = $this->user_model->getRow('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
			if ($company_details) {


				$barcode_title = $company_details->barcode_title;
				$barcode_mrp_prefix = $company_details->barcode_mrp_prefix;
				$barcode_sell_prefix = $company_details->barcode_sell_prefix;
				$item1 = $company_details->item1;
				$item2 = $company_details->item2;
				$item3 = $company_details->item3;
				if ($company_details->price_code == 'mrp_price') {
					$purchase_code = $this->user_model->getRow('mrp_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
				} else if ($company_details->price_code == 'sell_price') {
					$purchase_code = $this->user_model->getRow('sell_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
				// } else if ($purchase_code == 'purchase_price') {
				}else
				{
				    $purchase_code = $this->user_model->getRow('purchase_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
				}
				//  else {
				// 	$purchase_code = array(
				// 		'zero' => '',
				// 		'first' => '',
				// 		'second' => '',
				// 		'third' => '',
				// 		'fourth' => '',
				// 		'fifth' => '',
				// 		'sixth' => '',
				// 		'seventh' => '',
				// 		'eight' => '',
				// 		'nine' => ''
				// 	);
				// }
			}



			if (isset($result) && !empty($result)) {
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

								?>
								<?php
								if ($qrcode) {

								?>
									<div class="row">
										<?php
										if ($col_md == 12) {
										?>
											<div class="col-md-12">
												<span><b><?= $barcode_title ?></b></span>
												<br>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:200px;height:50px;">
												<br>
												<span><b><?= $product->p_name; ?></b></span>
												<br>
												<?php

												if ($qrcode) {
													$resp = '';
												?>
													<span><b><?= $barcode_mrp_prefix ?>.<?= $product->mrp ?></b></span>
													<br>
													<span><b><?= $barcode_sell_prefix ?>.<?= $product->price ?></b></span>
												<?php
													$array  = array_map('intval', str_split($product->price));
													$arry_of_code = [];
													if ($array && $purchase_code) {
														foreach ($array as $a) {
															if ($a == 0) {
																$zero = $purchase_code->zero;
																$arry_of_code[] = $zero;
															} elseif ($a == 1) {
																$first = $purchase_code->first;
																$arry_of_code[] = $first;
															} elseif ($a == 2) {
																$second = $purchase_code->second;
																$arry_of_code[] = $second;
															} elseif ($a == 3) {
																$third = $purchase_code->third;
																$arry_of_code[] = $third;
															} elseif ($a == 4) {
																$fourth = $purchase_code->fourth;
																$arry_of_code[] = $fourth;
															} elseif ($a == 5) {
																$fifth = $purchase_code->fifth;
																$arry_of_code[] = $fifth;
															} elseif ($a == 6) {
																$sixth = $purchase_code->sixth;
																$arry_of_code[] = $sixth;
															} elseif ($a == 7) {
																$seventh = $purchase_code->seventh;
																$arry_of_code[] = $seventh;
															} elseif ($a == 8) {
																$eight = $purchase_code->eight;
																$arry_of_code[] = $eight;
															} else {
																$nine = $purchase_code->nine;
																$arry_of_code[] = $nine;
															}
														}
														$resp = implode("", $arry_of_code);
													}
												}
												?>

												<div class="row">
													<div class="col-md-2"></div>
													<div class="col-md-4">
														<span><?= $resp ?></span>
													</div>

													<div class="col-md-4">
														<span>(incl. of All taxes)</span>
													</div>
													<div class="col-md-2"></div>


												</div>
											</div>
										<?php
										} elseif ($col_md == 6) {
										?>
											<!-- <div class="col-md-1">

											</div> -->
											<!-- <div class="col-md-4">
												<p style="transform: rotate(270deg);margin-top:62px;"><b><?= $product->p_name; ?></b></p>
											</div> -->
											<div class="col-md-12">
												<span><b><?= $barcode_title ?></b></span>
												<br>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:200px;height:50px;">
												<br>
												<span><b><?= $product->p_name; ?></b></span>
												<br>
												<?php

												if ($qrcode) {
													$resp = '';
												?>
													<span><b><?= $barcode_mrp_prefix ?>.<?= $product->mrp ?></b></span>
													<br>
													<span><b><?= $barcode_sell_prefix ?>.<?= $product->price ?></b></span>
												<?php
													$array  = array_map('intval', str_split($product->price));
													$arry_of_code = [];
													if ($array && $purchase_code) {
														foreach ($array as $a) {
															if ($a == 0) {
																$zero = $purchase_code->zero;
																$arry_of_code[] = $zero;
															} elseif ($a == 1) {
																$first = $purchase_code->first;
																$arry_of_code[] = $first;
															} elseif ($a == 2) {
																$second = $purchase_code->second;
																$arry_of_code[] = $second;
															} elseif ($a == 3) {
																$third = $purchase_code->third;
																$arry_of_code[] = $third;
															} elseif ($a == 4) {
																$fourth = $purchase_code->fourth;
																$arry_of_code[] = $fourth;
															} elseif ($a == 5) {
																$fifth = $purchase_code->fifth;
																$arry_of_code[] = $fifth;
															} elseif ($a == 6) {
																$sixth = $purchase_code->sixth;
																$arry_of_code[] = $sixth;
															} elseif ($a == 7) {
																$seventh = $purchase_code->seventh;
																$arry_of_code[] = $seventh;
															} elseif ($a == 8) {
																$eight = $purchase_code->eight;
																$arry_of_code[] = $eight;
															} else {
																$nine = $purchase_code->nine;
																$arry_of_code[] = $nine;
															}
														}
														$resp = implode("", $arry_of_code);
													}
												}
												?>

												<div class="row">
													<div class="col-md-2"></div>
													<div class="col-md-4">
														<span><?= $resp ?></span>
													</div>

													<div class="col-md-4">
														<span>(incl. of All taxes)</span>
													</div>
													<div class="col-md-2"></div>


												</div>
											</div>

										<?php
										} elseif ($col_md == 4) {
										?>

											<div class="col-md-12">
												<span><b><?= $barcode_title ?></b></span>
												<br>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:200px;height:50px;">
												<br>
												<span><b><?= $product->p_name; ?></b></span>
												<br>
												<?php

												if ($qrcode) {
													$resp = '';
												?>
													<span><b><?= $barcode_mrp_prefix ?>.<?= $product->mrp ?></b></span>
													<br>
													<span><b><?= $barcode_sell_prefix ?>.<?= $product->price ?></b></span>
												<?php
													$array  = array_map('intval', str_split($product->price));
													$arry_of_code = [];
													if ($array && $purchase_code) {
														foreach ($array as $a) {
															if ($a == 0) {
																$zero = $purchase_code->zero;
																$arry_of_code[] = $zero;
															} elseif ($a == 1) {
																$first = $purchase_code->first;
																$arry_of_code[] = $first;
															} elseif ($a == 2) {
																$second = $purchase_code->second;
																$arry_of_code[] = $second;
															} elseif ($a == 3) {
																$third = $purchase_code->third;
																$arry_of_code[] = $third;
															} elseif ($a == 4) {
																$fourth = $purchase_code->fourth;
																$arry_of_code[] = $fourth;
															} elseif ($a == 5) {
																$fifth = $purchase_code->fifth;
																$arry_of_code[] = $fifth;
															} elseif ($a == 6) {
																$sixth = $purchase_code->sixth;
																$arry_of_code[] = $sixth;
															} elseif ($a == 7) {
																$seventh = $purchase_code->seventh;
																$arry_of_code[] = $seventh;
															} elseif ($a == 8) {
																$eight = $purchase_code->eight;
																$arry_of_code[] = $eight;
															} else {
																$nine = $purchase_code->nine;
																$arry_of_code[] = $nine;
															}
														}
														$resp = implode("", $arry_of_code);
													}
												}
												?>

												<div class="row">
													<div class="col-md-2"></div>
													<div class="col-md-4">
														<span><?= $resp ?></span>
													</div>

													<div class="col-md-4">
														<span>(incl. of All taxes)</span>
													</div>
													<div class="col-md-2"></div>


												</div>
											</div>
										<?php
										} else {
										?>

											<div class="col-md-12">
												<span><b><?= $barcode_title ?></b></span>
												<br>
												<img src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:200px;height:50px;">
												<br>
												<span><b><?= $product->p_name; ?></b></span>
												<br>
												<?php

												if ($qrcode) {
													$resp = '';
												?>
													<span><b><?= $barcode_mrp_prefix ?>.<?= $product->mrp ?></b></span>
													<br>
													<span><b><?= $barcode_sell_prefix ?>.<?= $product->price ?></b></span>
												<?php
													$array  = array_map('intval', str_split($product->price));
													$arry_of_code = [];
													if ($array && $purchase_code) {
														foreach ($array as $a) {
															if ($a == 0) {
																$zero = $purchase_code->zero;
																$arry_of_code[] = $zero;
															} elseif ($a == 1) {
																$first = $purchase_code->first;
																$arry_of_code[] = $first;
															} elseif ($a == 2) {
																$second = $purchase_code->second;
																$arry_of_code[] = $second;
															} elseif ($a == 3) {
																$third = $purchase_code->third;
																$arry_of_code[] = $third;
															} elseif ($a == 4) {
																$fourth = $purchase_code->fourth;
																$arry_of_code[] = $fourth;
															} elseif ($a == 5) {
																$fifth = $purchase_code->fifth;
																$arry_of_code[] = $fifth;
															} elseif ($a == 6) {
																$sixth = $purchase_code->sixth;
																$arry_of_code[] = $sixth;
															} elseif ($a == 7) {
																$seventh = $purchase_code->seventh;
																$arry_of_code[] = $seventh;
															} elseif ($a == 8) {
																$eight = $purchase_code->eight;
																$arry_of_code[] = $eight;
															} else {
																$nine = $purchase_code->nine;
																$arry_of_code[] = $nine;
															}
														}
														$resp = implode("", $arry_of_code);
													}
												}
												?>

												<div class="row">
													<div class="col-md-2"></div>
													<div class="col-md-4">
														<span><?= $resp ?></span>
													</div>

													<div class="col-md-5">
														<span>(incl. of All taxes)</span>
													</div>
													<div class="col-md-1"></div>


												</div>
											</div>
										<?php
										}
										?>

									</div>
								<?php
								}
								?>

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