<?php $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));"); ?>

<style type="text/css">
	.loader-bg {
		background: #161616 url("<?= base_url(); ?>assets/images/loader.gif") no-repeat center center;
		opacity: 0.5;
		background-size: 5%;
		z-index: 9999;
		width: 100%;
		height: 130vh;
		position: fixed;
		top: 0;
		left: 0;
	}

	th,
	td {
		white-space: nowrap;
	}

	.card {
		margin-bottom: 0rem;
	}

	.card-footer p {
		margin-bottom: 0rem;
	}

	.card-body {
		padding: 0.25rem;
	}

	.form-group {
		margin-bottom: 0rem;
	}

	.card-body {
		/* max-height: 150px; Adjust the height as needed */
		overflow-y: auto;
		/* Enable vertical scrolling */
		padding: 10px;
		background-color: #fff;
	}
</style>

<div class="loader-bg"></div>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="modal fade" id="invoiceProductHsnVerifie" tabindex="-1" role="dialog" aria-labelledby="invoiceProductHsnVerifieLabels" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" style="color: red;" id="invoiceProductHsnVerifieLabels"></h5>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<h5 class="modal-title"><a href="https://ewaybillgst.gov.in/Others/ProductSearch.aspx" target="_blank">>> click here for check PRODUCT NAME & HSN</a></h5>

							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="card">

						<div class="card-header">
							<div class="row">
								<div class="col-md-6">
									<h4>DEBIT NOTE REPORT</h4>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-2">
									<a href="<?= base_url() ?>debit_note" class="btn btn-info"><i data-feather="arrow-left-circle" class="icon icon-size"></i>Back</a>
									<a href="<?= base_url() ?>recycle" title="Back TO Debit Note RECYCLE" class="btn btn-primary"><i class="fa fa-recycle"></i>Recycle Bin</a>

								</div>

								<form action="" id="reportForm" onsubmit="onFormSubmitHandler(event)" method="get">
									<div class="row">

										<div class="form-group col-md-2 col-sm-6">
											<label>Sale Register</label>
											<select tabindex="1" name="sale_register" id="sale_register" onkeydown="firstRow(event)" required style="width:100%" class="form-control searchselect">
												<option value="">Select</option>
												<option value="general" <?= $this->input->get('sale_register') == 'general' ? 'selected' : '' ?>>General Register</option>
												<option value="columnar" <?= $this->input->get('sale_register') == 'columnar' ? 'selected' : '' ?>>Columnar Register</option>
												<option value="hsn" <?= $this->input->get('sale_register') == 'hsn' ? 'selected' : '' ?>>HSN Wise Report</option>

												<option value="detailview" <?= $this->input->get('sale_register') == 'detailview' ? 'selected' : '' ?>>Detail View</option>


											</select>
										</div>
										<div class="form-group col-md-2 col-sm-6">
											<label for="invoiceNumber">Invoice Number</label>
											<input type="text" tabindex="2" name="invoiceNumber" id="invoiceNumber" value="<?= $this->input->get('invoiceNumber') ? $this->input->get('invoiceNumber') : '' ?>" class="form-control" />

										</div>
										<?php
										$date = date('Y-m-d');

										?>

										<div class="form-group col-md-2 col-sm-6">
											<label for="fromDate">From Date</label>
											<input type="date" tabindex="3" name="fromDate" onkeydown="firstRow(event)" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : $date ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control" />
										</div>
										<div class="form-group col-md-2 col-sm-6">
											<label for="toDate">To Date</label>
											<input type="date" tabindex="4" onclick="enterKeyPressed(event)" onkeydown="firstRow(event)" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : $date ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control" />
										</div>
										<div class="form-group col-md-2 col-sm-6" style="margin-top:31px">

											<button type="submit" class="btn btn-primary" onClick="return search()" onkeydown="firstRow(event)"> Search</button> &nbsp
										</div>
										<div class="form-group col-md-2 col-sm-6" style="font-size:1.5em;margin-top:31px">

											<i class="fa fa-refresh" title="Refresh" id="resetbtn" aria-hidden="true"></i>
											<!-- <i class="fa fa-university" title="Json Form" style="border-color: #900000;color:green" id="json" aria-hidden="true"></i> -->


											<!-- <i class="fa fa-file-excel-o" title="Excel File" id="excelExport" style="border-color: #900000;color:green" aria-hidden="true"></i> -->
											<!-- <i class="fa fa-file-pdf-o" title="PDF" id="downloadPDF" style="background-color: #c70000;border-color: #900000;color:#fff" aria-hidden="true"></i> -->
											<i class="fas fa-backward" title="Previous Month" id="pre" style="border-color: #900000;color:blue" onclick="return PreMonth()"></i>
											<i class="fas fa-play" title="Current Month" id="currentMont" onclick="return curent()"></i>

											<i class="fas fa-forward" title="Next Month" id="nextMonth" style="border-color: #900000;color:green" onclick="return nextmonth()"></i>
										</div>
									</div>
								</form>
								<?php
								// echo '<pre>';
								// print_r($result);
								// die;
								?>
								<!-- GENERAL START -->
								<?php
								if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
										<?php echo $this->session->flashdata('msg'); ?>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive" id="details_view">
								<table class="table table-striped table-bordered dataTable" id="example">
									<thead>
										<tr>
											<th>Sr.</th>
											<th>Invoice No</th>
											<th>Invoice Date</th>
											<th>Party Name</th>
											<th>Mobile</th>
											<th>GST No</th>
											<th>Payment Mode</th>
											<th>Taxable Amount</th>
											<th>Tax</th>
											<th>Total</th>
											<th>Action</th>
										</tr>
									</thead>

									<tbody>
										<?php
										$totalTaxable_amount = 0;
										$total_tax = 0;
										$total_amount = 0;
										if ($result) {
											foreach ($result as $key => $row) {
												$totalTaxable_amount += $row->taxableAmount;
												$total_tax += $row->totalTax;
												$total_amount += $row->total;
										?>
												<tr>
													<td><?= $key + 1 ?></td>
													<td><?= $row->invoiceNumber; ?></td>
													<td><?= $row->invoiceDate; ?></td>
													<td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
													<td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
													<td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
													<?php
													if ($row->voucherType == 1) {
														$payment_mode = "Cash";
													} else if ($row->voucherType == 2) {
														$payment_mode = "Credit";
													} else {
														$payment_mode = $row->voucherType;
													}
													?>
													<td><?= $payment_mode ?></td>
													<td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->taxableAmount; ?></td>
													<td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->totalTax; ?></td>
													<td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->total; ?></td>
													<td>

														<a href="<?= base_url('debit_note/view/') . $row->id ?>" class="text-success" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
														| <a href="<?= base_url('debit_note/edit/') . $row->id ?>" class="text-info" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
														|<a href="<?= base_url('debit_note/delete/') . $row->id ?>" class="text-danger" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
															<i class="fa fa-trash"></i>
														</a>
														| <a href="<?= base_url('debit_note/printDebiteNote/') . $row->id ?>" class="text-success" title="Print" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>


													</td>
												</tr>
										<?php }
										} ?>
									</tbody>

									<tfoot>
										<th colspan="7">Total</th>
										<th><i class="fa fa-inr total" aria-hidden="true">
												<?php
												echo $totalTaxable_amount;
												// $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
												// $this->db->select_sum('taxableAmount');
												// $this->db->from('sales');
												// $this->db->where($where);
												// $res = $this->db->get()->row();
												// if ($res) {
												// 	echo $res->taxableAmount;
												// } else {
												// 	echo 0;
												// }
												?>
										</th>
										<th><i class="fa fa-inr" aria-hidden="true">
												<?php
												echo $total_tax;
												// $this->db->select_sum('totalTax');
												// $this->db->from('sales');
												// $this->db->where($where);

												// $res = $this->db->get()->row();
												// if ($res) {
												// 	echo $res->totalTax;
												// } else {
												// 	echo 0;
												// }
												?>
										</th>
										<th><i class="fa fa-inr" aria-hidden="true">
												<?php
												// $this->db->select_sum('total');
												// $this->db->from('sales');
												// $this->db->where($where);
												// $res = $this->db->get()->row();
												// if ($res) {
												// 	echo $res->total;
												// } else {
												// 	echo 0;
												// }
												echo $total_amount;
												?>
										</th>
										<th></th>
									</tfoot>
								</table>
							</div>
							<!-- GENERAL END -->
							<!-- Details VIEW START -->
							<div id="details_view">
								<table class="table table-striped table-bordered table-responsive-lg table-responsive-sm table-responsive-md dataTable" id="example">
									<thead>
										<tr>
											<th>Sr.</th>
											<th>Invoice No</th>
											<th>Invoice Date</th>
											<th>Party Name</th>
											<th>City</th>
											<th>GST No</th>
											<th>Voucher Type</th>
											<th>Taxable Amt</th>
											<?php
											$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
											$this->db->select('tax');

											$this->db->from('credit_product');

											$this->db->where('type', 'DebitNote');

											$this->db->group_by('tax');
											$this->db->order_by('tax', 'asc');

											$tax = $this->db->get()->result();
											// print_r($tax);
											// die;
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $row) {
													if (is_numeric($row->tax)) {
														$total_gsttype[$row->tax] = $row->tax;


											?>
														<td style="text-align:center;"><span>Taxable Amt @ </span><?= $row->tax; ?>%</td>

														<!-- <td style="text-align:center;">Taxable Amount</td> -->
														<td style="text-align:center;">SGST</td>
														<td style="text-align:center;">CGST</td>
														<td style="text-align:center;">IGST</td>
											<?php
														// $tcount--;
													}
												}
											}
											?>

											<th>Invoice Total</th>
											<th>Action</th>

										</tr>
									</thead>
									<tbody>

										<?php if ($result) {

											foreach ($result as $key => $row) {
												$row->e_crn_url = "";
												if ($row->eDBN_status == 1) {
													$row->e_crn_url = $this->user_model->getRow('e_invoice_details', ['Irn' => $row->Irn])->EinvoicePdf;
												}
										?>
												<tr id="trp<?= $key ?>">
													<td><?= $key + 1 ?></td>
													<td><?= $row->invoiceNumber; ?></td>
													<td><?= $row->invoiceDate; ?></td>
													<td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
													<td><?= get_title('districts', ['id' => get_title('parties', ['id' => $row->customerId], 'city')], 'name'); ?></td>
													<td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
													<td><?= "Debit Note"; ?></td>
													<td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->taxableAmount; ?></td>
													<?php
													$this->db->select('tax');
													$this->db->select_sum('price');
													$this->db->select_sum('sgst');
													$this->db->select_sum('cgst');
													$this->db->select_sum('igst');
													$this->db->select_sum('taxAmount');
													$this->db->from('credit_product');
													$this->db->where('invoiceID', $row->id);
													$this->db->where('InvoiceNo', $row->invoiceNumber);
													$this->db->where('type', 'DebitNote');
													$this->db->group_by('tax');
													$tax = $this->db->get()->result();

													// $tcount = 4;
													foreach ($total_gsttype as $total_tax) {


														$this->db->select('tax');
														$this->db->select_sum('price');
														$this->db->select_sum('sgst');
														$this->db->select_sum('cgst');
														$this->db->select_sum('igst');
														$this->db->select_sum('taxAmount');
														$this->db->from('credit_product');
														$this->db->where('invoiceID', $row->id);
														$this->db->where('InvoiceNo', $row->invoiceNumber);
														$this->db->where('tax', $total_tax);

														$this->db->where('type', 'DebitNote');
														$this->db->group_by('tax');
														$this->db->order_by('tax', 'asc');
														$tax = $this->db->get()->result();


														// print_r($tax[0]);
														// die;
														if (!empty($tax)) {
													?>
															<td style="text-align:center;"><?= money_formats($tax[0]->price) ?></td>
															<!-- <td style="text-align:center;"><?= money_formats($tax[0]->taxAmount, 2) ?></td> -->
															<td style="text-align:center;"><?= money_formats($tax[0]->sgst, 2) ?></td>
															<td style="text-align:center;"><?= money_formats($tax[0]->cgst, 2) ?></td>
															<td style="text-align:center;"><?= money_formats($tax[0]->igst, 2) ?></td>
														<?php
														} else {
														?>
															<td style="text-align:center;">--</td>
															<td style="text-align:center;">--</td>
															<td style="text-align:center;">--</td>
															<td style="text-align:center;">--</td>

													<?php													}
													}
													?>


													<td><?= $row->totalValue ?></td>
													<td>

														<a href="<?= base_url('debit_note/view/') . $row->id ?>" class="text-success" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
														| <a href="<?= base_url('debit_note/edit/') . $row->id ?>" class="text-info" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
														<?php if (($row->eDBN_status == 1) && ($row->eDBN_cancel == 0)) {
															if (getUserPermission()->eDBN_note == "Y") {  ?>
																|<a href="javascript:void(0)" onclick="cancel_e_DBNConfirm('<?= $row->Irn ?>',<?= $row->id ?>)" class="text-danger" title="E-Debit Note Cancel">
																	<i class="fa fa-trash"></i></a>
															<?php }
														} else { ?>
															|<a href="<?= base_url('debit_note/delete/') . $row->id ?>" class="text-danger" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
																<i class="fa fa-trash"></i></a>
														<?php } ?>
														| <a href="<?= base_url('debit_note/printDebiteNote/') . $row->id ?>" class="text-success" title="Print" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>

														|
														<?php if ($row->eDBN_status == 1) {
															$row->e_crn_url ?>
															<a href="<?= $row->e_crn_url ?>" style="font: weight 800px;" onclick="" title="download E-Credit Note" download>
																<i class="fa fa-download" style="color:#2f91fa;"></i>
															</a>
															<?php } else {
															if (getUserPermission()->eDBN_note == "Y") { ?>
																<a href="javascript:void(0)" class="text-info" onclick="createEDebitetNoteConfirm(<?= $row->id ?>)" title="Print"><i class="fa fa-plus" aria-hidden="true"></i></a>
														<?php }
														} ?>

													</td>

												</tr>
										<?php }
										} ?>
									</tbody>
								</table>
							</div>
							<!-- Details VIEW END -->

							<!-- columnar  start -->
							<div id="Diff">
								<table class="table table-striped table-bordered table-responsive-xlg table-responsive-sm table-responsive-md dataTable" width="100%" id="example">
									<thead>
										<tr>
											<th>Sr.</th>
											<th>Invoice No</th>
											<th>Invoice Date</th>
											<th>Customer</th>
											<th>GSTIN</th>
											<th>POS</th>
											<th>Payment Mode</th>

											<th>Taxable Amount</th>
											<?php

											$this->db->select('tax');
											$this->db->from('credit_product');
											$this->db->where('type', 'DebitNote');
											$this->db->where('tax!=', null);
											$this->db->group_by('tax');
											$this->db->order_by('tax', 'asc');
											$tax = $this->db->get()->result();
											// $total_gsttype = [];
											if (isset($tax) && !empty($tax)) {

												foreach ($tax as $row) {
													if (is_numeric($row->tax) && !empty($row->tax)) {
														$total_gsttype[$row->tax] = $row->tax;

											?>
														<td style="text-align:center;"><span>Taxable Amt @ </span><?= $row->tax; ?>%</td>

											<?php
													}
												}
											}
											?>
											<td style="text-align:center;">SGST</td>
											<td style="text-align:center;">CGST</td>
											<td style="text-align:center;">IGST</td>
											<td style="text-align:center;">CESS</td>

											<th>Invoice Total</th>
											<th>Action</th>

										</tr>
									</thead>
									<tbody>

										<?php
										$totalTaxable_amount = 0;
										$total_tax = 0;
										$total_amount = 0;
										if ($result) {

											foreach ($result as $key => $row) {
												// $total_amount += $row->taxableAmount;
												$total_tax += $row->totalTax;
												$total_amount += $row->total;

												$totalTaxable_amount += $row->taxableAmount;


												$overall_igst = 0;
												$overall_cgst = 0;
												$overall_sgst = 0;
												$total_igst = 0;
												$total_cgst = 0;
												$total_sgst = 0;


										?>
												<tr id="trp<?= $key ?>">
													<td><?= $key + 1 ?></td>
													<td><?= $row->invoiceNumber; ?></td>
													<td><?= $row->invoiceDate; ?></td>
													<td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>


													<td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
													<td><?= get_title('state_list', ['id' => get_title('parties', ['id' => $row->customerId], 'state')], 'state'); ?></td>

													<?php
													if ($row->voucherType == 1) {
														$payment_mode = "Cash";
													} else if ($row->voucherType == 2) {
														$payment_mode = "Credit";
													} else {
														$payment_mode = $row->voucherType;
													}
													?>
													<td><?= $payment_mode ?></td>
													<td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->taxableAmount; ?></td>




													<?php
													$this->db->select('tax');
													$this->db->select_sum('price');
													$this->db->select_sum('sgst');
													$this->db->select_sum('cgst');
													$this->db->select_sum('igst');
													$this->db->select_sum('taxAmount');
													$this->db->from('credit_product');
													$this->db->where('invoiceID', $row->id);
													$this->db->where('InvoiceNo', $row->invoiceNumber);
													$this->db->where('type', 'DebitNote');
													$this->db->group_by('tax');
													$tax = $this->db->get()->result();

													foreach ($total_gsttype as $total_tax) {


														$this->db->select('tax');
														$this->db->select_sum('price');
														$this->db->select_sum('sgst');
														$this->db->select_sum('cgst');
														$this->db->select_sum('igst');
														$this->db->select_sum('taxAmount');
														$this->db->from('credit_product');
														$this->db->where('invoiceID', $row->id);
														$this->db->where('InvoiceNo', $row->invoiceNumber);
														$this->db->where('tax', $total_tax);

														$this->db->where('type', 'DebitNote');
														$this->db->group_by('tax');
														$this->db->order_by('tax', 'asc');
														$tax = $this->db->get()->result();
														// print_r($tax[0]);
														// die;
														if (!empty($tax)) {
															$overall_sgst = $overall_sgst + $tax[0]->sgst;
															$overall_cgst = $overall_cgst + $tax[0]->cgst;
															$overall_igst = $overall_igst + $tax[0]->igst;





													?>
															<td style="text-align:center;"><?= money_formats($tax[0]->price) ?></td>
															<?php

															?>
															<!-- <td style="text-align:center;"><?= money_formats($tax[0]->taxAmount, 2) ?></td> -->
															<!-- <td style="text-align:center;"><?= money_formats($tax[0]->sgst, 2) ?></td>
														<td style="text-align:center;"><?= money_formats($tax[0]->cgst, 2) ?></td>
														<td style="text-align:center;"><?= money_formats($tax[0]->igst, 2) ?></td> -->
														<?php
														} else {
														?>
															<td style="text-align:center;">0.00</td>
															<!-- <td style="text-align:center;">--</td>
														<td style="text-align:center;">--</td>
														<td style="text-align:center;">--</td> -->

													<?php													}
													}
													?>
													<td><?= $overall_sgst ?></td>
													<td><?= $overall_cgst ?></td>
													<td><?= $overall_igst ?></td>
													<?php
													$total_igst = $total_igst + $overall_igst;
													$total_cgst = $total_cgst + $overall_cgst;
													$total_sgst = $total_sgst + $overall_sgst;
													?>
													<td><?= 0 ?></td>
													<td><?= $row->totalValue ?></td>

													<td>
														<a href="<?= base_url('debit_note/view/') . $row->id ?>" class="text-success" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
														| <a href="<?= base_url('debit_note/edit/') . $row->id ?>" class="text-info" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
														|<a href="<?= base_url('debit_note/delete/') . $row->id ?>" class="text-danger" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
															<i class="fa fa-trash"></i>
														</a>
														| <a href="<?= base_url('debit_note/printDebiteNote/') . $row->id ?>" class="text-success" title="Print" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>
													</td>
												</tr>
										<?php }
										} ?>
									</tbody>
									<tfoot>
										<th colspan="7">Total</th>
										<th><i class="fa fa-inr total" aria-hidden="true">
												<?php
												echo $totalTaxable_amount;

												?>
										</th>
										<?php

										$this->db->select('tax');
										$this->db->from('credit_product');
										$this->db->where('type', 'DebitNote');
										$this->db->group_by('tax');
										$this->db->order_by('tax', 'asc');
										$tax = $this->db->get()->result();
										// print_r($tax);
										// die;
										if (isset($tax) && !empty($tax)) {
											foreach ($tax as $row) {
												if (is_numeric($row->tax)) {
													$total_gsttype[$row->tax] = $row->tax;

										?>
													<th style="text-align:center;"><span></span></th>
										<?php
												}
											}
										}
										?>

										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<!-- <td><?= $total_sgst ?></td>
									<td><?= $total_cgst ?></td>
									<td><?= $total_igst ?></td> -->
										<th><i class="fa fa-inr" aria-hidden="true">
												<?php

												echo $total_amount;
												?>
										</th>

										<th></th>
									</tfoot>
								</table>
							</div>
							<!-- columnar && HSN END -->


							<div id="hsn_report">
								<table class="table table-striped table-bordered table-responsive-lg table-responsive-sm table-responsive-md dataTable" id="example">
									<thead>
										<tr>
											<th>Sr.</th>
											<th>HSN No</th>
											<th>Description</th>
											<th>Total Qty</th>
											<th>TAX</th>
											<th>Taxable Amount</th>
											<td style="text-align:center;">IGST</td>
											<td style="text-align:center;">CGST</td>
											<td style="text-align:center;">SGST</td>
											<th>Cess Amount</th>
											<th>Total Value</th>
											<!--<th>Action</th>-->
										</tr>
									</thead>
									<tbody>
										<?php
										$total_amount = 0;
										$total_tax = 0;
										$total_taxAbleAmount = 0;

										$this->db->select('*');
										$this->db->select('hsn');
										$this->db->select('invoiceID');
										$this->db->select('productname');
										$this->db->select_sum('price');

										$this->db->select_sum('netAmount');
										$this->db->select_sum('qty');
										$this->db->select_sum('sgst');
										$this->db->select_sum('cgst');
										$this->db->select_sum('igst');
										$this->db->select_sum('taxAmount');
										$this->db->select_sum('purchase_price');
										// taxable amount
										$this->db->from('credit_product');
										$this->db->where('comp_id', $this->session->userdata('comp_id'));
										$this->db->where('user_id', $this->session->userdata('user_id'));

										$this->db->where('invoiceDate >=', $fromDate);
										$this->db->where('invoiceDate<=', $toDate);

										$this->db->where('type', 'DebitNote');
										$this->db->group_by("hsn", "tax");

										$result_of_products = $this->db->get()->result();

										if (!empty($result_of_products)) {
											foreach ($result_of_products as $key => $tax) {
												$total_taxAbleAmount += $tax->purchase_price;
												$total_tax += $tax->taxAmount;
												$total_amount += $tax->netAmount;
												$overall_igst = 0;
												$overall_cgst = 0;
												$overall_sgst = 0;
												$overall_sgst = $overall_sgst + $tax->sgst;
												$overall_cgst = $overall_cgst + $tax->cgst;
												$overall_igst = $overall_igst + $tax->igst;

										?>
												<tr id="trp<?= $key ?>">
													<td><?= $key + 1 ?></td>
													<td style="text-align:center;"><?= $tax->hsn ?></td>
													<td style="text-align:center;"><?= $tax->productname ?></td>
													<td style="text-align:center;"><?= money_formats($tax->qty) ?></td>
													<td style="text-align:center;"><?= $tax->tax ?></td>

													<td style="text-align:center;"><?= money_formats($tax->taxAmount) ?></td>
													<td style="text-align:center;"><?= money_formats($tax->igst) ?></td>
													<td style="text-align:center;"><?= money_formats($tax->cgst) ?></td>
													<td style="text-align:center;"><?= money_formats($tax->sgst) ?></td>
													<td style="text-align:center;"><?= 0.00 ?></td>
													<td style="text-align:center;"><?= $tax->netAmount ?></td>

												<?php													}

												?>

												</tr>
											<?php
										}

											?>
									</tbody>
								</table>
							</div>
						</div>
						<?php

						?>
						<div class="card-footer">
							<p class="text-center">NOTE:<span class="text-danger">**Deleted Files Stored in Recycle Bin**</span></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script>
	$(document).ready(function() {
		$(".loader-bg").css("display", "none");
	});

	$(document).ready(function() {
		var sale_register = document.getElementById('sale_register').value;
		if (sale_register == 'columnar') {
			document.getElementById('divas').style.display = "none";
			document.getElementById('details_view').style.display = "none";
			document.getElementById('hsn_report').style.display = "none";
			document.getElementById('Diff').style.display = "block";

		} else if (sale_register == 'hsn') {
			document.getElementById('divas').style.display = "none";
			document.getElementById('details_view').style.display = "none";
			document.getElementById('Diff').style.display = "none";
			document.getElementById('hsn_report').style.display = "block";

		} else if (sale_register == 'detailview') {
			document.getElementById('details_view').style.display = "block";
			document.getElementById('divas').style.display = "none";
			document.getElementById('Diff').style.display = "none";
			document.getElementById('hsn_report').style.display = "none";

		} else {
			document.getElementById('details_view').style.display = "none";
			document.getElementById('Diff').style.display = "none";
			document.getElementById('hsn_report').style.display = "none";
			document.getElementById('divas').style.display = "block";
		}

	});

	$('#excelExport').click(function(e) {
		e.preventDefault();
		var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>Debit_note/exportsale?" + form;
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a.attr("download", "file.xls");
		$a[0].click();
		$a.remove();
	})

	const onFormSubmitHandler = (e) => {
		e.preventDefault();
		var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>debit_note/report?" + form;
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a[0].click();
		$a.remove();
	}

	function getValueOfSale() {
		var value = document.getElementById('example').value;

		if (value == 'columnar') {
			document.getElementById('example').display = "none";

		} else if (value == 'hsn') {
			document.getElementById('example').display = "none";
		} else {
			document.getElementById('example').display = "show";

		}
	}
	$('#downloadPDF').click(function(e) {
		e.preventDefault();
		var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>debit_note/pdfdebit_note?" + form;
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a[0].click();
		$a.remove();
	})

	$('#resetbtn').click(function(e) {
		window.location.href = "<?= base_url() ?>invent/debit_note_report";
	});
</script>
<script>
	function PreMonth() {

		var fromDate = document.getElementById('fromDate').value;
		console.log(fromDate, 'fromDate')
		fromDate = new Date(fromDate);
		fromDate.setMonth(fromDate.getMonth() - 1);
		var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD')
		var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD')
		document.getElementById('toDate').value = lastDay;
		document.getElementById('fromDate').value = firstDay;

	}
</script>
<script>
	function curent() {
		var today = new Date();
		var dd = today.getDate();
		console.log(today);
		var mm = today.getMonth() + 1;
		var yyyy = today.getFullYear();
		if (dd < 10) {
			dd = '0' + dd;
		}

		if (mm < 10) {
			mm = '0' + mm;
		}
		today = yyyy + '-' + mm + '-' + dd;
		console.log(today);
		document.getElementById('fromDate').value = today;
		document.getElementById('toDate').value = today;
	}
</script>
<script>
	function nextmonth() {
		var fromDate = document.getElementById('fromDate').value;
		console.log(fromDate, 'fromDate')
		fromDate = new Date(fromDate);
		fromDate.setMonth(fromDate.getMonth() + 1);
		var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD')
		var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD')
		document.getElementById('toDate').value = lastDay;
		document.getElementById('fromDate').value = firstDay;


	}
</script>


<script>
	var inputs = document.querySelectorAll("input,select");
	// //(inputs);
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].addEventListener("keypress", function(e) {
			if (e.which == 13) {
				e.preventDefault();
				var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
				// alert(nextInput);

				if (nextInput.length === 0) {
					nextInput = document.querySelectorAll('[tabIndex="1"]');
				}
				nextInput[0].focus();
			}
		})
	}


	function enterKeyPressed(event) {
		if (event.keyCode == 13 || event.which == 13) {
			// alert("okkk")
			var start = document.getElementById('trp0');
			start.focus();
			start.style.backgroundColor = 'blue';
			start.style.color = 'white';
			console.log("Yes colors changr")

		}
	}

	function keyDownForParty(e, row) {
		//down arrow key
		// alert("yes");

		if (e.keyCode == '40') {
			console.log(row, "rowpaykyah");
			var elmtTable = document.getElementById('example');
			var tableRow = document.getElementById('tbody');
			var tableRows = tableRow.getElementsByTagName('tr');
			console.log(tableRows, "TableRows");
			var rowCount = tableRows.length - 1;
			console.log(rowCount, "rowCounter")
			console.log(row, "row")

			if (rowCount > row) {
				if (row < 0) {
					row = 0;
				}
				row = row + 1;
				previrow = row - 1;
				console.log(row, "tr");
				var start = document.getElementById('trp' + row);
				var previous = document.getElementById('trp' + previrow);

				console.log(previous, "previousrow");
				// var searchparty = document.getElementById('searchproduct').value;
				console.log(start, "start");
				if (previous) {
					previous.style.backgroundColor = 'white';
					previous.style.color = 'black';
					// }
				}
				// }
				// console.log(start,"startrowcount")
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
				// alert("keydown")
				// console.log(start);
				// row++;
				// row++;
				// }
			} else {
				// row = row - 1;
				var start = document.getElementById('trp' + row);
				console.log(start, "eleswala");
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
			}
		}
	}

	function keyUpForParty(e, row) {
		//up arrow key
		if (e.keyCode == '38') {
			if (row == 0) {
				var previous = document.getElementById('trp' + row);
				previous.style.backgroundColor = 'white';
				previous.style.color = 'black'
				document.getElementById('searchparty').focus();
				return;
			}
			console.log(row, "rowpaykyahupwalep");
			var elmtTable = document.getElementById('example');
			var tableRow = document.getElementById('tbody');
			var tableRows = elmtTable.getElementsByTagName('tr');
			console.log(tableRows, "TableRows");
			var rowCount = tableRows.length - 1;
			console.log(rowCount, "rowCounter")
			console.log(row, "row")
			previrow = '';
			if (rowCount > row) {
				if (row <= 0) {
					row = 0;
					// previrow = row + 1;

				} else {
					row = row - 1;
					previrow = row + 1;
				}

				console.log(row, "tr");
				var start = document.getElementById('trp' + row);
				if (previrow) {
					var previous = document.getElementById('trp' + previrow);

					console.log(previous, "previousrow");
					// var searchparty = document.getElementById('searchproduct').value;
					console.log(start, "start");
					if (previous) {
						previous.style.backgroundColor = 'white';
						previous.style.color = 'black';
					}
				}
				// }
				// }
				// console.log(start,"startrowcount")
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
				// alert("keydown")
				// console.log(start);
				// row++;
				// row++;
				// }
			}
		}
	}
</script>
<script>
	const getCustomerDetail = (sale_id, key) => {
		window.location.href = '<?= base_url() ?>debit_note/edit/' + sale_id;
	};

	$(function() {
		$(".dataTable").DataTable({
			dom: 'Bfrtip',
			pageLength: 20,
			lengthMenu: [
				[20, 50, 100, -1],
				[20, 50, 100, "All"]
			],
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
				'print',
				'pageLength',
			]

		});
	});
</script>
<script>
	function deleteRow(url) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, delete it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRow(url);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				});
			}
		});
	}
</script>
<script>
	function removeRow(url) {
		$.ajax({
			type: "get",
			url: url,
			success: function(success) {
				success = JSON.parse(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
					setTimeout(function() {
						window.location.reload();
					}, 2000);
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
				}
			},
		});
	}


	$('#sale_register').focus();

	function invoiceHsnUpdate(invoice_id, type) {
		console.log(type,'type');
		let productId = 19876;
		$(".loader-bg").css("display", "none");
		jQuery.noConflict();
		$("#invoiceProductHsnVerifie .modal-dialog .modal-body").load(baseurl + "product/invoiceProductHsnVerifie?invoice_id=" + invoice_id + "&InvoiceType=" + type);
		$("#invoiceProductHsnVerifieLabels").html("Please verify this products HSN");
		$("#invoiceProductHsnVerifie").modal("show");
	}

	function oninvoiceProductHsnVerifiedFormSubmitHandler(e) {
		e.preventDefault();
		var url = $("#oninvoiceProductHsnVerifiedFormSubmitHandler").attr("action");
		var form = $("#oninvoiceProductHsnVerifiedFormSubmitHandler")[0];
		var data = new FormData(form);
		var type = $("#hsninvoiceType").val();
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(res) {
				res = JSON.parse(res);
				if (res.status == true) {
					toastr.success("Product HSN verified successfully!", "");
					setTimeout(function() {
						//    $("#onProductHsnVerifiedFormSubmitHandler")[0].reset();
						$("#oninvoiceProductHsnVerifiedFormSubmitHandler").modal("hide");
						//     $(".show").css("opacity", "0");
						// $(".productHsnVerifie").addClass("HideModal");
						// $(".productHsnVerifie").removeClass("modal-backdrop"); 
						// getProductsReport();  
						generateEDebitNote(res.invoice_id,'1');
						 
						$(".close").click();
					}, 1000);
				} else {
					$("#productMsg").show();
					$("#productMsg").addClass("text");
					$("#productMsg").addClass("text-danger");
					$("#productMsg").html(res.msg);
				}
			}
		});
	}
</script>