<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card mb-30">

						<div class="card-header row">
							<div class="col-md-6">
								<h4>View Credit Note</h4>
							</div>
							<div class="col-md-6">
								<a href="<?= base_url() ?>credit_note/report" class="btn btn-info float-right"><i data-feather="arrow-left-circle" class="icon icon-size"></i>Back</a>
							</div>
						</div>

						<div class="card-body">
							<div class="row border py-3">
								<div class="col-md-12 row" id="pdf">
									<?php 
                                                                            $party = '';
                                                                            if ($result->customerId) {
                                                                                $party = $this->db->select('*')->get_where('parties', ['id' => $result->customerId])->row();
                                                                            }
									?>
									<label class="col-md-2"><strong>Credit Note No. : </strong> <?= $result->invoiceNumber ?></label>
									<label class="col-md-3"><strong>Invoice Date : </strong> <?= date('d/m/Y', strtotime($result->invoiceDate)); ?></label>
									<label class="col-md-3"><strong>Voucher Type : </strong> <?= $result->voucherType == '1' ? 'Cash' : 'Credit'; ?></label>
									<label class="col-md-2"><strong>GST No. : </strong> <?= $party->gst; ?></label>
									<label class="col-md-2"><strong>Tax Code : </strong> <?= $result->taxCode == "1" ? "GST" : "IGST"; ?></label>
									<label class="col-md-5"><strong>Name : <?= $result->customerId ? ucwords($party->name) : $result->customerName; ?></strong></label>
									<label class="col-md-3"><strong>Email : </strong> <?= $party->email; ?></label>
									<label class="col-md-2"><strong>Transport : </strong> <?= $result->transport; ?></label>
									<label class="col-md-2"><strong>Mobile : </strong> <?= $party->contact; ?></label>
									<label class="col-md-5"><strong>Address : </strong> <?= $party->address_1; ?> <?= $party->area; ?></label>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered table-striped table-responsive-sm mt-2 mb-3">
										<thead class="bg-dark">
											<tr>
												<th class="text-white text-center">S No.</th>
												<th class="text-white text-center">Product Name</th>
												<th class="text-white text-center">HSN Code</th>
												<th class="text-white text-center">Qty</th>
												<th class="text-white text-center">Rate</th>
												<th class="text-white text-center">Discount</th>
												<th class="text-white text-center">Tax %</th>
												<th class="text-white text-center">Amount</th>
												<th class="text-white text-center">Tax Amount</th>
												<th class="text-white text-center">Net Amount</th>
											</tr>
										</thead>

										<tbody>
											<?php $totalQty = 0;
											// echo '<pre>';
											// print_r($result);
											// die;
											foreach ($result->product as $key => $row) {
												$totalQty += $row->qty;
											?>
												<tr>
													<td style="padding-left: 5px;" class="text-center"><?= $key + 1; ?></td>
													<td><?= get_title('product', ['id' => $row->product_id], 'p_name'); ?></td>
													<td class="text-right"><?= $row->hsn ?> </td>
													<td class="text-center"><?= $row->qty ?></td>
													<td class="text-right"><?= money_formats($row->rate) ?></td>
													<td class="text-right"><?= $row->discount ?>%</td>
													<!-- <td class="text-right"><?= $row->cd ?>%</td> -->
													<td class="text-center"><?= $row->tax ?></td>
													<td class="text-right"><?= money_formats($row->purchase_price) ?></td>
													<td class="text-right"><?= money_formats($row->taxAmount) ?></td>
													<td class="text-right"><?= money_formats($row->netAmount) ?></td>
												</tr>
											<?php
											} ?>
										</tbody>

										<tfoot>
											<tr>
												<th colspan="3">
													<span class="float-right">Total : </span>
												</th>
												<th class="text-center"><?= $totalQty ?></th>
												<th colspan="3"></th>
												<th class="text-right"><?= money_formats($result->taxableAmount) ?></th>
												<th class="text-right"><?= money_formats($result->totalTax) ?></th>
												<th class="text-right"><?= money_formats($result->totalValue) ?></th>
											</tr>
											<tr>
												<td colspan="9"><span class="float-right px-3 font-weight-bold">Taxable Amount : </span></td>
												<td class="text-right"><?= money_formats($result->taxableAmount); ?></td>
											</tr>
											<?php if ($result->totaligst) { ?>
												<tr>
													<td colspan="9"><span class="float-right px-3 font-weight-bold">Total IGST : </span></td>
													<td class="text-right"><?= money_formats($result->totaligst); ?></td>
												</tr>
											<?php } else { ?>
												<tr>
													<td colspan="9"><span class="float-right px-3 font-weight-bold">Total SGST : </span></td>
													<td class="text-right"><?= money_formats($result->totalsgst); ?></td>
												</tr>
												<tr>
													<td colspan="9"><span class="float-right px-3 font-weight-bold">Total CGST : </span></td>
													<td class="text-right"><?= money_formats($result->totalcgst); ?></td>
												</tr>
											<?php } ?>
											<tr>
												<td colspan="9"><span class="float-right px-3 font-weight-bold">Total GST : </span></td>
												<td class="text-right"><?= money_formats($result->totalTax); ?></td>
											</tr>
											</tr>
											<tr>
												<td colspan="9"><span class="float-right px-3 font-weight-bold">Expenses : </span></td>
												<td class="text-right"><?= $result->expenses ? money_formats($result->expenses) : money_formats(0); ?></td>
											</tr>
											<tr>
												<td colspan="9"><span class="float-right px-3 font-weight-bold">Total Value : </span></td>
												<td class="text-right"><?= money_formats($result->totalValue); ?></td>
											</tr>
											</tr>
											<tr>
												<td colspan="9"><span class="float-right px-3 font-weight-bold">Round Off : </span></td>
												<td class="text-right"><?= money_formats($result->roundOff); ?></td>
											</tr>
											<tr>
												<td colspan="9"><span class="float-right px-3 font-weight-bold">Total : </span></td>
												<td class="text-right"><?= money_formats($result->total); ?></td>
											</tr>
											<tr>
												<td colspan="10"><span class="px-3 font-weight-bold">Amount In Words : </span> <?= ucwords($result->amountInWords); ?></td>
											</tr>
										</tfoot>
									</table>
								</div>

								<?php if ($result->payTermsDay == 1) { ?>
									<div class="col-md-12 row">
										<div class="col-md-2">Pay Terms Day</div>
										<div class="col-md-8"><?= $result->payTermsDay ?></div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>

	</div>

</div>
