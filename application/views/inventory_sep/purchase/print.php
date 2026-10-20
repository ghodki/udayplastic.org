<html>

<head>
	<title>Uday Plastic</title>
	<style>
		@page {
			size: A4;
			margin: 0;
		}

		/* @page {
			size: 7in 9.25in;
			margin: 27mm 16mm 27mm 16mm;
		} */

		.text-right {
			text-align: center !important;
		}

		.container {
			/* margin-left: 3em;
			margin-right: 3em;
			margin-top: 1em; */


			/* margin-top: 20%; */
			/* padding-top: 5em; */
			padding: 2em;
			/* margin-bottom: 5em; */
		}

		li {
			line-height: 18px;
			margin-left: 0px;
		}

		div {
			font-size: 25px;
		}

		ol {
			margin-block-start: 0PX;
			padding-inline-start: 20px;
		}

		.outer {
			font-size: 100%;
			table-layout: fixed;
			width: 100%;
			border: 1px solid #000;
			border-collapse: collapse;
			font-size: 20px;
		}

		td {
			line-height: 23px;
			padding: 0px;
			/* padding-left: .25em; */
		}

		tr {
			font-size: 16px;
		}

		.mytable th {
			border-bottom: 1px solid #000;
			border-top: 1px solid #000;
			border-collapse: collapse;
		}

		.mytable {
			border-collapse: collapse;
			width: 100%;
			font-size: 12px
		}

		.text-uppercase {
			text-transform: uppercase;
			margin-top: 1px;
			margin-bottom: 0px;
		}

		.border-right {
			border-right: 1px solid #000;
		}

		.border-left {
			border-left: 1px solid #000;
		}

		.border-bottom {
			border-bottom: 1px solid #000;
		}

		.border-top {
			border-top: 1px solid #000;
		}

		.text-center {
			text-align: center;
		}

		small {
			font-size: 10px;
			line-height: 11px;
		}

		.gstTable {
			width: 100%;
			border: 1px solid #000;
			border-collapse: collapse;
		}

		.gstTable td {
			border: 1px solid #000;
		}

		.blankLine {
			line-height: 30px;
		}

		@media print {

			body {
				font-size: 20px;
			}
		}



		/* .conatiner {
			display: table;
		}

		#pageFooter {
			display: table-footer-group;
		}

		#pageFooter:after {
			counter-increment: <?= $pages ?>;
			content: counter(<?= $pages ?>);
		} */
		.outer .tblbody tr td b p {
			/* margin-top:-24px; */
			text-align: center;
		}
	</style>
</head>

<!-- <body style="font-size:12px;"> -->

<body style="font-size:40px;" onload="window.print()">
	<!-- <body style="font-size:40px;">  -->


	<!-- <body style="font-size:40px;"> -->

	<?php if ($result->customerId != '' && $result->customerId != 0) {
		$party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
		$info = $this->db->get('business_details')->row();
	}
	// $total_inv
	// $total_invoice=[];
	$comp_id = $this->session->userdata('comp_id');
	$user_id = $this->session->userdata('user_id');
	$where = ['comp_id' => $comp_id, 'user_id' => $user_id];
	$comp_profile = $this->user_model->getRow('comp_profile', $where);
	// echo '<pre>';
	// print_r($this->session->userdata());
	// die;
	// echo $this->session->userdata('format_of_bill');
	// die;
	if ($comp_profile) {
		$jklmn = $comp_profile->total_invoice;
	?>
		<?php
		for ($jkl = 0; $jkl < $jklmn; $jkl++) {
		?>
			<div class="container" style="margin-bottom:105%">
				<table class="outer">
					<tbody class="tblbody">
						<tr>
							<?php
							$log_postion = $comp_profile->logo_position;
							$logo_status = $comp_profile->logo_status;
							$comp_details_position = $comp_profile->comp_details_position;

						if ($log_postion == 'left' && $logo_status == 'enable') {

								if ($logo_status == 'enable') {
							?>
									<td>
										<?php
										// $logo = $this->user_model->getResult('logo');
										// // $contact = $this->user_model->getResult('contacts');

										// // $register = $this->user_model->getResult('registered');

										// if (isset($logo) && !empty($logo)) {
										?>

										<img src="<?= base_url() ?>uploads/complogo/<?= $comp_profile->logo; ?>" class="img-fluid" style="width:200px;height:300px;" />
										<br>
										<!-- <span class="text-danger">An ISO certifide compny</span> -->
									<?php
									// }
								}
									?>
									</td>
									<?php
									if ($comp_details_position == 'center') {
										$colspan = "1";
									} else if ($comp_details_position == 'right') {
										$colspan = "0";
									} else {
										$colspan = "1";
									}
									?>
									<!-- <td></td> -->
									<?php
									if ($colspan == 1) {
									?>


										<td class="text-center" colspan="<?= $colspan ?>">
										<?php
									} else {
										?>
										<td></td>
										<td class="text-center">
										<?php
									}
										?>
										<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
										if (isset($bank) && !empty($bank)) {
											echo '<h3>' . $bank->name . '</h3>';
											echo "Address:" . '<span style="line-height:21px !important; text-align:center !important;">' . $this->session->userdata('address') . '</span>';
											// echo '<span>'.$bank->bank_details.'</span>';
											echo '<br>';
											echo "Email:" . '<span style="line-height:21px !important;">' . $this->session->userdata('email') . '</span>';
											echo '<br>';

											echo "Number:" . '<span style="line-height:21px !important;">' . $this->session->userdata('numbers') . '</span>';
											echo '<br>';


											echo "<b>GSTIN:</b>" . '<b style="line-height:21px !important;">' . $this->session->userdata('gst_nos') . '</b>';
										}
										?>

										</td>
										<?php
										if ($colspan == 1) {
										?>
											<td></td>
										<?php
										}
										?>
									<?php
								} else if ($log_postion == 'right' && $logo_status == 'enable') {
									if ($comp_details_position == 'center') {
										$colspan = "2";
									} else if ($comp_details_position == 'right') {
										$colspan = "1";
									} else {
										$colspan = "0";
									}
									?>
										<?php
										if ($colspan == 2) {
											// $colspan="";
										?>
											<td>
											<?php
										} else {
											?>
											<td colspan="<?= $colspan ?>" class="text-center">

											<?php
										}
											?>
											<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
											if (isset($bank) && !empty($bank)) {
												echo '<h3>' . $bank->name . '</h3>';
												echo "Address:" . '<span style="line-height:21px !important; text-align:center !important;">' . $this->session->userdata('address') . '</span>';
												// echo '<span>'.$bank->bank_details.'</span>';
												echo '<br>';
												echo "Email:" . '<span style="line-height:21px !important;">' . $this->session->userdata('email') . '</span>';
												echo '<br>';

												echo "Number:" . '<span style="line-height:21px !important;">' . $this->session->userdata('numbers') . '</span>';
												echo '<br>';


												echo "<b>GSTIN:</b>" . '<b style="line-height:21px !important;">' . $this->session->userdata('gst_nos') . '</b>';
											}
											?>

											</td>
											<?php
											if ($colspan == 0) {
											?>
												<td></td>
											<?php
											}
											?>

											<td>
												<?php
												if ($logo_status == 'enable') {
													// $logo = $this->user_model->getResult('logo');
													// $contact = $this->user_model->getResult('contacts');

													// $register = $this->user_model->getResult('registered');

													// if (isset($logo) && !empty($logo)) {
												?>
													<img src="<?= base_url() ?>uploads/complogo/<?= $comp_profile->logo; ?>" class="img-fluid" style="width:200px;height:300px;" />
													<br>
													<span class="text-danger">An ISO certifide compny</span>
												<?php
													// }
												}
												?>
											</td>

											<!-- <td></td> -->

										<?php
									} else {

										?>


											<?php
											if ($comp_details_position == 'left') {
											?>
												<td class="text-center">
												<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
												if (isset($bank) && !empty($bank)) {
													echo '<b>' . $bank->name . '</b>';
													echo "Address:" . '<span style="line-height:21px !important; text-align:center !important;">' . $this->session->userdata('address') . '</span>';
													// echo '<span>'.$bank->bank_details.'</span>';
													echo '<br>';
													echo "Email:" . '<span style="line-height:21px !important;">' . $this->session->userdata('email') . '</span>';
													echo '<br>';

													echo "Number:" . '<span style="line-height:21px !important;">' . $this->session->userdata('numbers') . '</span>';
													echo '<br>';


													echo "<b>GSTIN:</b>" . '<b style="line-height:21px !important;">' . $this->session->userdata('gst_nos') . '</b>';
												}
												echo "</td>";
												echo "<td colspan='2'></td>";
											}
												?>

												<!-- </td> -->
												<?php

												?>

												<?php
												if ($comp_details_position == 'right') {
												?>
												<td></td>
												<td></td>

												<td class="text-center">
												<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
													if (isset($bank) && !empty($bank)) {
														echo '<b>' . $bank->name . '</b>';
														echo "Address:" . '<span style="line-height:21px !important; text-align:center !important;">' . $this->session->userdata('address') . '</span>';
														// echo '<span>'.$bank->bank_details.'</span>';
														echo '<br>';
														echo "Email:" . '<span style="line-height:21px !important;">' . $this->session->userdata('email') . '</span>';
														echo '<br>';

														echo "Number:" . '<span style="line-height:21px !important;">' . $this->session->userdata('numbers') . '</span>';
														echo '<br>';


														echo "<b>GSTIN:</b>" . '<b style="line-height:21px !important;">' . $this->session->userdata('gst_nos') . '</b>';
													}
													echo "</td>";
												}
												?>

												<!-- </td> -->
												<?php

												?>


												<?php
												if ($comp_details_position == 'center') {
												?>
												<td></td>
												<td class="text-center">
												<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
													if (isset($bank) && !empty($bank)) {
														echo '<h4>' . $bank->name . '</h4>';
														echo "Address:" . '<span style="line-height:21px !important; text-align:center !important;">' . $this->session->userdata('address') . '</span>';
														// echo '<span>'.$bank->bank_details.'</span>';
														echo '<br>';
														echo "Email:" . '<span style="line-height:21px !important;text-align:center !important;">' . $this->session->userdata('email') . '</span>';
														echo '<br>';

														echo "Number:" . '<span style="line-height:21px !important;text-align:center !important;">' . $this->session->userdata('numbers') . '</span>';
														echo '<br>';


														echo "<b>GSTIN:</b>" . '<b style="line-height:21px !important;text-align:center !important;">' . $this->session->userdata('gst_nos') . '</b>';
													}
													echo "</td>";
													echo "<td></td>";
												}
												?>

												<!-- </td> -->
												<?php

												?>


											<?php
										}
										// echo '<pre>';
										// print_r($this->session->userdata());
										// die;
											?>
						</tr>
						<br>
						<tr>
							<td class="border-right border-top text-center border-bottom" colspan="3"><b><?= $comp_profile->title_invoice ?></b></td>
						</tr>
						<tr>
							<td width="70%" colspan="2" class="border-right border-bottom">
								<b>Buyer Details</b><br>
								<b><?= $result->customerId != '' && $result->customerId != 0 ?  $party->name : $result->customerName ?></b><br>
								<?= $result->customerId != '' && $result->customerId != 0 ?  $party->address_1 : $result->customerAddress ?><br>
								<?= $result->customerId != '' && $result->customerId != 0 && isset($party) && $party->address_2  ? $party->address_2 . '<br>' : '' ?>
								<?= isset($party) ? $party->city : $result->customerCity ?> Pin : <?= isset($party) ? $party->pin : '' ?> <?= isset($party) ? get_title('state_list', ['id' => $party->state], 'state') : ''; ?><br>
								<b>GSTN. </b> <?= isset($party) ? $party->gst : $result->gstNo ?><br>
								<b>Contact Details </b> <?= isset($party) ? $party->contact : $result->customerMobile ?>, <?= isset($party) ? $party->email : $result->customerEmail ?>
							</td>
							<!-- <td> -->
							<td rowspan="1" style="padding:0px; vertical-align:text-top">
								<table style="width:100%;border-collapse: collapse; margin-top:0px">
									<tr class="border-bottom">
										<td class="border-right" style="width:45%"><b>Date</b></td>
										<td><?= date('d/m/Y', strtotime($result->added_at)) ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Invoice No</b></td>
										<td><?= $result->invoiceNumber ?></td>
									</tr>
									<?php
									// echo '<pre>';
									// print_r($this->session->userdata());
									// die;

									?>
									<!-- <tr class="border-bottom">

										<td class="border-right"><b>Order Date</b></td>
										<td><?= $result->orderDate ? date('d/m/Y', strtotime($result->orderDate)) : '' ?></td>
									</tr> -->
									<!-- <tr class="border-bottom">
										<td class="border-right"><b>Order No</b></td>
										<td><?= $result->orderNumber ?></td>
									</tr> -->
									<?php
									$dispatch_by = $this->session->userdata('dpb');
									if ($dispatch_by == 'yes') {
									?>
										<tr class="border-bottom">
											<td class="border-right"><b>Dispatch By</b></td>
											<td><?= ucwords($result->transport) ?></td>
										</tr>
									<?php
									}
									// echo '<pre>';
									// print_r($result);
									// die;
									?>
									<?php
									$lr = $this->session->userdata('lrnumber');
									if ($lr == 'yes') {
									?>
										<tr class="border-bottom">
											<td class="border-right"><b>LR No</b></td>
											<td><?= $result->lrNumber ?></td>
										</tr>

									<?php
									}
									?>
									<?php
									$lrdate = $this->session->userdata('lrdate');
									if ($lrdate == 'yes') {
									?>
										<tr class="border-bottom">
											<td class="border-right"><b>LR Date</b></td>
											<td><?= $result->lrDate ?></td>
										</tr>

									<?php
									}
									?>

									<!-- <tr class="border-bottom">
										<td class="border-right"><b>Reached Date</b></td>
										<td>
											<?= $result->reachedDate ? date('d/m/Y', strtotime($result->reachedDate)) : '' ?>
										</td>
									</tr> -->
									<?php
									$ewayBill = $this->session->userdata('eway');
									if ($ewayBill == 'yes') {
									?>
										<tr class="border-bottom">
											<td class="border-right"><b>Eway Bill</b></td>
											<td><?= $result->ewayBill ?></td>
										</tr>
									<?php
									}
									?>
									<?php
									$dcdate = $this->session->userdata('dcdate');
									if ($dcdate == 'yes') {
									?>
										<tr class="border-bottom">
											<td class="border-right"><b>Dc Date</b></td>
											<td><?= $result->dcDate ?></td>
										</tr>
									<?php
									}
									?>
									<?php
									$ptd = $this->session->userdata('ptd');
									if ($ptd == 'yes') {
									?>
										<tr class="border-bottom">
											<td class="border-right"><b>Pay Terms Day</b></td>
											<td><?= $result->payTermsDay ?></td>
										</tr>
									<?php
									}
									?>

									<?php
									$duedate = $this->session->userdata('duedate');
									if ($duedate == 'yes') {
									?>
										<tr class="border-bottom">
											<td class="border-right"><b>DueDate</b></td>
											<td><?= $result->dueDate ?></td>
										</tr>
									<?php
									}
									?>
									<!-- <tr class="border-bottom">
										<td class="border-right"><b>Chalan No.</b></td>
										<td><?= $result->challanNumber ?></td>
									</tr> -->
									<tr>
										<td colspan="3" class="noborder">

										</td>
									</tr>

								</table>
								<!-- </td> -->
							</td>

						</tr>
						<!-- </thead> -->
						<style>
							.mytable tr {
								height: 40px
							}

							.mytable tr:last-child {
								height: auto;
							}
						</style>
						<tr>
							<td style="vertical-align:text-top" colspan="3">
								<table class="mytable" style="height: 750px;margin:0px;">
									<thead>
										<tr style="background-color: #999999; -webkit-print-color-adjust: exact; font-size:14px">
											<th class="border-right text-right" style="width:25px">S/N</th>
											<th class="border-right text-right">HSN</th>

											<th class="border-right text-right" style="width:30%">PARTICULAR</th>

											<th class="border-right text-right" style="width:40px">GST</th>
											<th class="border-right text-right" style="width:50px">QTY</th>
											<th class="border-right text-right">RATE</th>
											<?php
											$discount_session = $this->session->userdata('discount');
											if ($discount_session == 'yes') {
											?>
												<th class="border-right text-right">DIS</th>
											<?php
											}
											?>
											<!-- <th class="border-right">CD</th> -->
											<th class="border-right text-right">Taxable Amt</th>

											<th class="border-right text-right">AMOUNT</th>

											<th class="border-right text-right">NetAmount</th>

										</tr>
									</thead>
									<tbody style="font-size:25px;">
										<?php
										$nosItm = 0;
										$lines = 6;
										$counts = 0;
										$totalDiscount = 0;
										$totalRate = 0;
										$totalCD = 0;
										// print_r($result);
										// die;
										if ($result->product) {
											foreach ($result->product as $key => $row) {
												$qtty = $row->qty;
												$nosItm += $qtty;
												$taxIte =  get_title('product', ['id' => $row->tax], 'tax');
												$rate = $row->rate;
												// $trheight=40;
										?>
												<tr style="vertical-align:top;">
													<td class="border-right" style="text-align:center"><?= $key + 1 ?></td>
													<td class="border-right text-right" style="text-align:center"><?= $row->hsn ?></td>

													<td class="border-right" style="text-align:center"><?= get_title('product', ['id' => $row->product_id], 'p_name'); ?> <small>
															<i>(<?= $row->product_dec ?>)</i>
															<?php if ($row->serials) {
																echo '(';
																foreach ($row->serials as $key => $value) {
																	if (sizeof($row->serials) - 1 > $key) {
																		echo $value->serial_no . ',';
																	} else {
																		echo $value->serial_no;
																	}
																}
																echo ')';
															} ?></small></td>

													<td class="border-right text-right" style="text-align:center"><?= $row->tax ?>%</td>
													<td class="border-right" style="text-align:center"><?= $row->qty ?></td>
													<td class="border-right text-right" style="text-align:center"><?= money_formats($rate) ?></td>
													<?php
													if ($discount_session == 'yes') {
													?>
														<td class="border-right text-right" style="text-align:center">
															<?php
															$totalRate += $rate * $row->qty;
															// if ($row->discounttype == '%') {
															$amt = $rate * $row->qty;
															$totalDiscount += $amt * $row->discount / 100;
															$rate = $rate * (100 - (float)$row->discount) / 100;
															echo $row->discount . '%';
															// } else {
															// 	$totalDiscount +=  $row->discount * $row->qty;
															// 	echo 'Rs' . $row->discount;
															// 	$rate = $rate - $row->discount;
															// }
															?>
														</td>
													<?php
													} else {
														$totalRate += $rate * $row->qty;
														// if ($row->discounttype == '%') {
														$amt = $rate * $row->qty;
														$totalDiscount += $amt * $row->discount / 100;
														$rate = $rate * (100 - (float)$row->discount) / 100;
														// echo $row->discount . '%';
													}
													?>
													<td class="border-right text-right" style="text-align:center">
														<?php
														echo $row->purchase_price;


														?>
													</td>
													<td class="text-right border-right" style="text-align:center;"><?= money_formats($row->amount) ?></td>

													<td class="border-right text-right" style="text-align:center"><?= money_formats($row->netAmount) ?></td>
												</tr>
										<?php
												$counts = $key;
											}
										}
										if (isset($counts) && !empty($counts)) {

											// $count=count($counts);

											$lines = $lines - $counts;
										} else {

											$lines = $lines - $counts;
										}

										?>
									</tbody>
									<tfoot>
										<?php if ($lines > 0) {
											for ($i = 0; $i < $lines; $i++) { ?>
												<tr>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="border-right blankLine">&nbsp;</td>
													<td class="blankLine">&nbsp;</td>
												</tr>
										<?php }
										} ?>
									</tfoot>
								</table>
							</td>
						</tr>
						<!-- <tfoot id="pageFooter"> -->
						<tr style="font-size:12px">
							<td class="border-right border-top border-bottom" colspan="2">
								<table class="gstTable">
									<tr Class="text-center" style="background-color: #999999; -webkit-print-color-adjust: exact; font-size:12px">
										<th style="border-right:1px solid #222;">TAX%</th>
										<th style="border-right:1px solid #222;">Taxable Amt.</th>
										<!-- <th style="border-right:1px solid #222;">TAX AMT</th> -->
										<th style="border-right:1px solid #222;">CGST</th>
										<th style="border-right:1px solid #222;">SGST</th>
										<th style="border-right:1px solid #222;">IGST</th>
									</tr>
									<?php
									$this->db->select('tax');
									$this->db->select_sum('price');
									$this->db->select_sum('sgst');
									$this->db->select_sum('cgst');
									$this->db->select_sum('igst');
									$this->db->select_sum('taxAmount');
									$this->db->from('invoice_product');
									$this->db->where('invoiceID', $result->id);
									$this->db->where('InvoiceNo', $result->invoiceNumber);
									$this->db->where('type', 'Purchase');
									$this->db->group_by('tax');
									$tax = $this->db->get()->result();
									$tcount = 4;
									if (isset($tax) && !empty($tax)) {
										foreach ($tax as $row) { ?>
											<tr style="font-size:12px">
												<td style="text-align:center;"><?= $row->tax; ?>%</td>
												<td style="text-align:center;"><?= money_formats($row->price) ?></td>
												<!-- <td style="text-align:center;"><?= money_formats($row->taxAmount, 2) ?></td> -->
												<td style="text-align:center;"><?= money_formats($row->sgst, 2) ?></td>
												<td style="text-align:center;"><?= money_formats($row->cgst, 2) ?></td>
												<td style="text-align:center;"><?= money_formats($row->igst, 2) ?></td>
											</tr>
										<?php $tcount--;
										}
									}
									if ($tcount > 0) {
										for ($i = 0; $i < $tcount; $i++) { ?>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<!-- <td>&nbsp;</td> -->
											</tr>
									<?php   }
									} ?>
								</table>
								<span style="margin-right: 1em;"><span>No of ltems : </span><?= $counts + 1 ?> </span>
								<span><span>Total Qty : </span><?= $nosItm; ?></span>
								<br>

								<b><span>Rs In Words : </span><?= ucwords($result->amountInWords) ?> </b>
							</td>
							<td class="border-top border-bottom" style="padding:0px;text-align:center !important;">
								<table style="width: 100%; border-collapse: collapse; margin:0px">
									<tr style="font-size:12px">
										<td class="border-right" style="width:40%;"><span>GROSS AMT.</span></td>
										<td><b style="float:right"><?= money_formats($totalRate) ?></b></td>
									</tr>
									<tr>
										<td class="border-right border-top"><span>LESS DIS.</span></td>
										<td class="border-top"><b style="float:right"><?= money_formats($totalDiscount, 2) ?></b></td>
									</tr>
									<tr>
										<td class="border-right border-top"><span>LESS CD</span></td>
										<td class="border-top"><b style="float:right"><?= money_formats($totalCD) ?></b></td>
									</tr>
									<tr>
										<td class="border-right border-top"><span>SUB TOTAL</span></td>
										<td class="border-top"><b style="float:right"><?= money_formats($result->taxableAmount) ?></b></td>
									</tr>
									<tr>
										<td class="border-right border-top"><span>GST</span></td>
										<td class="border-top"><b style="float:right"><?= money_formats($result->totalTax) ?></b></td>
									</tr>
									<tr>
										<td class="border-right border-top"><span>ROUND OFF</span></td>
										<td class="border-top"><b style="float:right"><?= money_formats($result->roundOff) ?></b></td>
									</tr>
									<tr>
										<td class="border-right border-top"><span>NET AMT.</span></td>
										<td class="border-top"><b style="float:right"><?= money_formats($result->total) ?></b></td>
									</tr>

								</table>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<span><b>Bank Account Information:</b></span>
								<br>
								<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
								if (isset($bank) && !empty($bank)) {
								?>
									<span><?= $bank->bank_details; ?></span>
								<?php
								}
								?>
							</td>
						</tr>
						<tr>
							<td colspan="3" class="border-top" style="padding:0px;text-align:center !important;">
								<table style="width:100%; margin:0px;  border-collapse: collapse; ">
									<tr>

										<td style="text-align:initial;">
											<span><b>Terms and Conditions:</b></span>
											<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
											if (isset($bank) && !empty($bank)) {
											?>
												<span><?= $bank->content; ?></span>
											<?php
											}
											?>

											<h6 style="text-align:center;">E & O E</h6>
										</td>
										<td style="width:18%; vertical-align:top;" class="border-right border-left">
											<span><b>Remarks</b></span>
											<br>
											<small style="vertical-align:bottom;"><?= $result->remark ?></small>
										</td>
										<td style="width:24%; vertical-align:bottom;" class="border-right">
											<small style="position: relative; bottom: 0;"> &nbsp; &nbsp; &nbsp; (Receivers Signature)</small>
										</td>
										<td style="width:18%; vertical-align:top;" class="border-left border-bottom">
											<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
											if (isset($bank) && !empty($bank)) {


												echo '<span class="text-align:left;padding:2px;">For:</span><b style="vertical-align:top;">' . $bank->name . '</b>';
											}
											?>
											<br>
											<br>
											<br>
											<br>
											<br>
											<br>

											<small style="text-align:bottom !important;"> &nbsp; (Authorised Signatory)</small>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="3" class="text-center border-top">
								<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
								if (isset($bank) && !empty($bank)) {
								?>
									<span class="text-center"><?= $bank->subject_to; ?></span>
								<?php
								}
								?>
							</td>
						</tr>
						<!-- </tfoot> -->
					</tbody>

				</table>

				<div style="font-size:10px;text-align:center;">Generated by <h6><?= $bank ? $bank->name : '' ?> contact: <?= $bank ? $this->session->userdata('numbers') : '' ?>, <?= $bank ? $this->session->userdata('email') : '' ?></h6>
				</div>
				<h2 class="text-center" style="margin-bottom:0px;">©2021 Uday Plastic All Rights Reserved</h2>

			</div>


	<?php
		}
	}
	?>
	<script>
		$(document).ready(function() {
			$('.loader').removeClass('loader');
			$('.main-footer').hide();

		});
	</script>
</body>

</html>