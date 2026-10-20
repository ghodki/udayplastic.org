<html>

<head>
	<title>2BusinessBuddy</title>
	<style>
		@page {
			/* size: A4; */
			margin: 0;
			<?php
			// $this->session->userdata('')
			?>size: <?= get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name'); ?>
		}

		/* @page {
			size: 7in 9.25in;
			margin: 27mm 16mm 27mm 16mm;
		} */

		.text-right {
			text-align: right;
		}

		.container {
			margin: 3em;
			/* margin-top: 20%; */
			padding-top: 5em;
			padding-left: 2em;
			margin-bottom: 5em;
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
	</style>
</head>

<!-- <body style="font-size:12px;"> -->

<body style="font-size:40px;" onload="window.print()">
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
	// print_r($comp_profile);
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
				<h1 style="text-align:center;margin:0px;">GST-Invoice</h1>




				<table class="outer">

					<tbody class="tblbody">
						<!-- <thead> -->
						<tr>
							<td width="65%" class="border-right border-bottom">

								<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
								if (isset($bank) && !empty($bank)) {
								?>
									<div class="col-md-6" style="display:inline-block; width:40%; padding-bottom:1px;"><span><?= $bank->bank_details; ?></span></div>

									<div class="col-md-6" style="display:inline-block; width:40%; padding-bottom:1px;"><span><?= $bank->name; ?></span></div>


								<?php
								}
								?>
							</td>
							<td rowspan="3" style="padding:0px; vertical-align:text-top">
								<table style="width:100%;border-collapse: collapse; margin-top:0px">
									<tr class="border-bottom">
										<td class="border-right" style="width:45%"><b>Date</b></td>
										<td><?= date('d/m/Y', strtotime($result->added_at)) ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Invoice No</b></td>
										<td><?= $result->invoiceNumber ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Order Date</b></td>
										<td><?= $result->orderDate ? date('d/m/Y', strtotime($result->orderDate)) : '' ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Order No</b></td>
										<td><?= $result->orderNumber ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Dispatch By</b></td>
										<td><?= ucwords($result->transport) ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Docket No</b></td>
										<td><?= $result->lrNumber ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Reached Date</b></td>
										<td>
											<?= $result->reachedDate ? date('d/m/Y', strtotime($result->reachedDate)) : '' ?>
										</td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Eway Bill</b></td>
										<td><?= $result->ewayBill ?></td>
									</tr>
									<tr class="border-bottom">
										<td class="border-right"><b>Chalan No.</b></td>
										<td><?= $result->challanNumber ?></td>
									</tr>
									<tr>
										<td colspan="2" class="noborder">
											<div class="row">

											</div>
										</td>
									</tr>

								</table>
							</td>
						</tr>
						<tr>
							<td width="70%" class="border-right border-bottom">
								<!-- <b>Buyer Details</b><br> -->
								MR:<b><?= $result->customerId != '' && $result->customerId != 0 ?  $party->name : $result->customerName ?></b><br>

							</td>
						</tr>
						<tr>

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
							<td style="vertical-align:text-top" colspan="2">
								<table class="mytable" style="height: 750px;margin:0px;">
									<thead>
										<tr style="background-color: #999999; -webkit-print-color-adjust: exact; font-size:14px">
											<th class="border-right" style="width:25px">S/N</th>
											<!-- <th class="border-right">HSN</th> -->
											<th class="border-right" style="width:30%">PARTICULAR</th>
											<!-- <th class="border-right" style="width:40px">GST</th> -->
											<th class="border-right" style="width:50px">QTY</th>
											<th class="border-right">RATE</th>
											<th class="border-right">DIS</th>
											<!-- <th class="border-right">CD</th> -->
											<!-- <th class="border-right">Taxable Amt</th> -->

											<th>AMOUNT</th>

											<!-- <th>NetAmount</th> -->

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
													<!-- <td class="border-right text-right" style="text-align:center"><?= $row->hsn ?></td> -->
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
													<!-- <td class="border-right text-right" style="text-align:center"><?= $row->tax ?>%</td> -->
													<td class="border-right" style="text-align:center"><?= $row->qty ?></td>
													<td class="border-right text-right" style="text-align:center"><?= money_formats($rate) ?></td>
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
													<td class="border-right text-right" style="text-align:center">
														<?php
														// if ($row->discounttypecd == '%') {
														echo $row->purchase_price;

														// } else {
														// 	echo $row->discounttypecd . $row->cd;
														// 	$totalCD += $row->cd * $row->qty;

														// 	$rate = $rate - $row->cd;
														// }
														?>
													</td>
													<td class="text-right" style="text-align:center"><?= money_formats($row->amount) ?></td>

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
							<td class="border-right border-top">
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
									$this->db->where('type', 'Sale');
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
								<div><span>Remark : </span><?= $result->remark ?></div>
								<div><span>Rs In Words : </span><?= ucwords($result->amountInWords) ?> </div>
							</td>
							<td class="border-top" style="padding:0px">
								<table style="width: 100%; border-collapse: collapse; margin:0px">
									<tr style="font-size:12px">
										<td class="border-right" style="width:40%"><span>GROSS AMT.</span></td>
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
							<td colspan="2" class="border-top" style="padding:0px">
								<table style="width:100%; margin:0px;  border-collapse: collapse; ">
									<tr>
										<td style="width:18%; vertical-align: bottom;" class="border-right">
											<small> &nbsp; &nbsp; &nbsp; (Receivers Signature)</small>
										</td>
										<td>
											<small>
												<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
												if (isset($bank) && !empty($bank)) {
												?>
													<div class="col-md-12" style="display:inline-block; width:40%; padding-bottom:1px;"><b><?= $bank->content; ?></b></div>
												<?php
												}
												?>
											</small>
											<h6 style="margin:0px">E & O E</h6>
										</td>
										<td style="width:18%; vertical-align: bottom;" class="border-left">
											<small style="position: relative; bottom: 0;"> &nbsp; (Authorised Signatory)</small>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="text-center border-top">
								<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
								if (isset($bank) && !empty($bank)) {
								?>
									<div class="col-md-12" style="display:inline-block; width:40%; padding-bottom:1px;"><b><?= $bank->subject_to; ?></b></div>
								<?php
								}
								?>
							</td>
						</tr>
						<!-- </tfoot> -->
					</tbody>

				</table>

				<div style="font-size:10px">Generated by <?= $info ? $info->name : '' ?> contact: <?= $info ? $info->mobile : '' ?>, <?= $info ? $info->email : '' ?></div>
			</div>

	<?php
		}
	}
	?>

</body>

</html>
