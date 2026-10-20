<!-- <link href="<?= base_url(); ?>assets/css/salecss.css" rel="stylesheet" /> -->
<!-- <script src="<?= base_url() ?>assets/js1/custom.js"></script> -->



<div class="content-wrapper" id="donknow">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">
				<div class="col-lg-12 card">
					<div class="card-header row">
						<div class="col-md-6">
							<h4>Update Sale</h4>

						</div>
						<div class="col-md-6">
							<a href="<?= base_url() ?>sale/salelist" class="btn btn-info float-right"><i data-feather="arrow-left-circle" class="icon icon-size"></i>See List</a>
						</div>
					</div>
					<?php
					// echo '<pre>';
					// print_r($result);
					// die;
					?>
					<form action="<?= base_url() ?>sale/saleUpdate" class="form-horizontal" style="margin-left:20px;" id="dataForm" method="post" onsubmit="onFormSubmitHandler(event)">
						<div class="row">
							<div class="col-md-10">
								<div class="row mb-3">
									<div class="col-sm-4">
										<div class="row mb-1">
											<label for="invoiceNumber" class="col-4">Invoice No.</label>


											<?php
											// echo '<pre>';
											// print_r($result);
											// die;
											?>

											<input autocomplete="nope" type="text" class="form-control col-8" tabindex="1" required="true" readonly name="invoiceNumber" id="invoiceNumber1" value="<?= $result->invoiceNumber ?>">


										</div>
										<div class="row mb-1">
											<label for="invoiceDate" class="col-4">Date</label>
											<input type="date" class="form-control col-8" required="true" name="invoiceDate" tabindex="2" id="invoiceDate" value="<?php echo $result->invoiceDate ?>">
										</div>
										<input type="hidden" name="printSale" value="<?= $result->id; ?>">
										<div class="row mb-1">
											<label for="voucherType" class="col-4" title="Voucher Type">Vchr Typ</label>
											<select name="voucherType" tabindex="3" id="voucherType" required="true" class="form-control col-8">
												<option value="1" <?= $result->taxCode == "1" ? "selected" : '' ?>>Cash</option>
												<option value="2" <?= $result->taxCode == "2" ? "selected" : '' ?>>Credit</option>
											</select>
										</div>
										<div class="row mb-1">
											<label for="orderNumber" class="col-4">Order no.</label>
											<input type="text" tabindex="4" class="form-control col-8" name="orderNumber" id="orderNumber" value="<?php echo $result->orderNumber ?>" placeholder="Order Number">
										</div>
										<div class="row form-group">
											<label for="orderDate" class="col-4">Order Date</label>
											<input type="date" tabindex="5" class="form-control col-8" name="orderDate" id="orderDate" value="<?php echo $result->orderDate ?>" required="true">
										</div>
									</div>

									<div class="col-sm-5">
										<div class="row mb-1 ">
											<label for="customerId" class="col-3">Party</label>
											<div class="col-9 p-0">
												<div id="party_idDiv" style="width:85%;float:left">
													<select tabindex="6" name="customerId" onchange="partyID(event)" id="party_id" style="width:100%" class="searchselect form-control">
														<option value="">Select One</option>
														<?php $party = $this->db->select('id, name, city,state_code')->order_by('name', 'ASC')->get_where('parties', ['status' => 1, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')])->result();
														if ($party) {
															foreach ($party as $row) {
														?>
																<option value="<?= $row->id; ?>" <?= $row->id == $result->customerId ? "selected" : '' ?>><?= ucwords($row->name); ?>(<?= get_title('state_list', ['id' => $row->city], 'state') ?>)</option>
														<?php
															}
														}
														?>
													</select>
												</div>
												<!-- <div id="customerNameDiv" style="width:89%;float:left">
													<input type="text" name="customerName" id="customerName" style="width: 100%;" class="form-control textOnly" disabled>
												</div> -->
												<!-- <span style="cursor: pointer;" class="fr ml-1" id="consignee" title="Add Consignee"><i class="fa fa-lg fa-book text-success" aria-hidden="true"></i></span> -->
												<!-- <span class="fr addText" title="Enter new customer name" onclick="showTextBox()" style="margin-right: 2px"><i class="fa fa-lg fa-user text-warning"></i></span>
												<span class="fr addDdl" title="Select new customer name" onclick="showDDLBox()"><i class="fa fa-lg fa-user text-info"></i></span> -->
												<span class="fr mr-1 addPatys" title="Add new customer" data-toggle="modal" data-target="#exampleModal" style="cursor: pointer;"><i style="margin-left:-2px;" class="fa fa-lg fa-plus-circle text-primary"></i></span>

											</div>
										</div>
										<div class="mb-1 row">
											<label for="gstNo" class="col-3">GST No</label>
											<input type="text" tabindex="7" class="form-control col-9" name="gstNo" id="gstNo" value>
										</div>
										<div class="mb-1 row">
											<label for="gstType" class="col-3">GST Type</label>
											<select name="gstType" tabindex="8" id="gstType" class="form-control  col-9">
												<option value="">-Select-</option>
												<option value="Regular" <?= $result->taxCode == "Regular" ? "selected" : '' ?>>Regular</option>
												<option value="Composite" <?= $result->taxCode == "Composite" ? "selected" : '' ?>>Composite</option>
												<option value="N/A" <?= $result->taxCode == "N/A" ? "selected" : '' ?>>N/A</option>
											</select>
										</div>
										<div class="mb-1 row">
											<label for="customerAddress" class="col-3">Address</label>
											<input autocomplete=" off" tabindex="9" cols="4" class="form-control col-9" name="customerAddress" id="customerAddress" value="<?= $result->customerAddress ?>">
										</div>
										<div class="mb-1 row">
											<label for="taxcal" class="col-3">Tax Cal</label>
											<select name="taxcal" tabindex="10" id="taxcal" required class="form-control  col-9">
												<option value="1" <?= $result->taxCal == "1" ? "selected" : '' ?>>Inclusvie</option>
												<option value="2" <?= $result->taxCal == "2" ? "selected" : '' ?>>Exclusive</option>


											</select>
										</div>
									</div>

									<div class="col-sm-3">
										<div class="row">
											<div class="col-sm-12">
												<div class="input-group mb-1">
													<select name="customerCity" tabindex="11" id="customerCity" style="width: 100%;" required="true" class="form-control">
														<?php
														$stateSelcted = $result->customerCity;
														$where = ['id' => $stateSelcted];
														$state = $this->user_model->getRow('districts', $where);
														if ($state) {
														?>
															<option value="<?= $state->id ?>"><?= $state->name; ?></option>

															<?php
															$states = $this->db->get('districts')->result();
															if ($states) {
																foreach ($states as $row) { ?>
																	<option value="<?= $row->id ?>" <?= set_value('name') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
														<?php
																}
															}
														} ?>
													</select>
												</div>
											</div>
											<div class="col-sm-12">
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-phone"></i></span>
													</div>
													<input autocomplete="off" tabindex="12" type="text" class="form-control" id="customerMobile" name="customerMobile" onkeypress="return IsNumeric(event);" value="<?= $result->customerMobile ?>" placeholder="A/C Mobile">

												</div>
											</div>
											<div class="col-sm-12">
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-envelope"></i></span>
													</div>
													<input autocomplete="off" tabindex="13" type="text" class="form-control" id="customerEmail" name="customerEmail" value="<?= $result->customerEmail ?>" placeholder="A/C Email">

												</div>
											</div>
											<div class="col-sm-12">
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-money" aria-hidden="true"></i></span>
													</div>
													<input autocomplete="off" tabindex="14" type="text" class="form-control" id="customerBalance" name="customerBalance" readonly="" value="<?= $result->customerBalance ?>" placeholder="A/C Balance">

												</div>
											</div>
											<div class="col-sm-12">
												<div class="input-group mb-1">
													<select name="customerState" tabindex="15" id="stateSelect" style="width: 100%;" required="true" class="form-control">
														<?php
														$stateSelcted = $result->customerState;
														$where = ['id' => $stateSelcted];
														$state = $this->user_model->getRow('state_list', $where);

														if ($state) {
														?>
															<option value="<?= $state->id ?>"><?= $state->state; ?></option>

															<?php
														}
														$states = $this->db->get('state_list')->result();
														if ($states) {
															foreach ($states as $row) { ?>
																<option value="<?= $row->id ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>
														<?php
															}
														} ?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!--  -->
								<!-- table -->
								<div class="row mb-3" style="width:100%; height:440px; overflow: scroll;">
									<div class="col-sm-12">

										<table class="table table-bordered inner_table table-responsive-sm mb-0" id="dataTable">

											<thead class="bg-dark">
												<tr>
													<th width="30px;">PCode</th>
													<th width="150px">Product Name

														<a class="cp ml-2 py-0" style="color:#fff;" data-toggle="modal" data-target="#productModel" title="Add New Product"><i class="fa fa-plus-circle " style="font-size: 16px"> </i></a>
													</th>
													<th><i class="fa fa-edit"></i></th>
													<th width="60px">HSN</th>
													<!-- <th width="70px">Cost</th> -->
													<th width="60px">Qty</th>
													<th width="80x">Rate</th>
													<!-- <th width="80px">Base Rate</th> -->
													<th width="20px">Disc.</th>
													<th width="50px">Tax %</th>
													<th width="80px">TaxableAmt</th>

													<th width="100px">TaxAmt</th>

													<th width="100px">Net Amt</th>

													<th width="45px"></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$allProducts = $this->db->select('id, p_name')->get_where('product', ['status' => 1, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')])->result();
												$productCode = $this->db->select('id, productcode')->get_where('productCode', ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')])->result();

												foreach ($result->product as $key => $row) {

												?>

													<tr id="tr<?= $key ?>">
														<td style="width:135px;padding:4px;">
															<input type="text" tabindex="<?= $key + 16 ?>" style="width:100%;" name="product[<?= $key ?>][p_code]" id="p_code<?= $key ?>" class="form-control" onchange="onProductCodeSelect('<?= $key ?>',event)" value="<?= $row->p_code ?>">

														</td>
														<td style="width: 150px !important;padding:4px;">
															<div id="pro_names<?= $key ?>">
																<select style="width: 195px !important;" tabindex="<?= $key + 17 ?>" name="product[<?= $key ?>][product]" id="productId<?= $key ?>" class="form-control" onchange="onProductSelect(<?= $key ?>,event)" onfocusout="onProductFocusChange(event,<?= $key ?>)" required="true">
																	<option value="">Select Product</option>
																	<?php
																	if ($allProducts) {
																		foreach ($allProducts as $row1) { ?>
																			<option value="<?= $row1->id ?>" <?= $row->product_id == $row1->id ? 'selected' : '' ?>><?= $row1->p_name ?></option>
																	<?php
																		}
																	}
																	?>
																</select>

															</div>
														</td>
														<!-- <td> -->
														<td><i class="fas fa-edit" data-toggle="modal" data-target="#desc<?= $key ?>" onmouseover="showPop(<?= $key ?>,event)" title="Product Edit"></i></td>

														<input type="text" tabindex="<?= $key + 18 ?>" readonly id="product_dec<?= $key ?>" name="product[<?= $key ?>][product_dec]" data-toggle="modal" data-target="#desc<?= $key ?>" onmouseover="showPop(<?= $key ?>,event)" value="<?= $row->product_dec; ?>" class="form-control" hidden>
														<!-- </td> -->
														<!-- <div class="modal fade" id="desc1" role="dialog" style="display: none;" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">×</button><h4 class="modal-title">Add Description</h4></div><div class="modal-body"><p>Description.</p><input type="text" class="form-control" name="product[1][product_dec]"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div></td> -->
														<td style="width:30px;padding:4px;"><input tabindex="<?= $key + 19 ?>" style="width:45px;" type="text" id="hsnCode<?= $key ?>" name="product[<?= $key ?>][hsn]" value="<?= $row->hsn; ?>" class="form-control"></td>
														<td style="width:125px;padding:4px;"><input tabindex="<?= $key + 20 ?>" type="number" min="<?= $key ?>" required="true" onchange="onQuantityChangeHandler(event,<?= $key ?>);" onkeypress="return IsNumeric(event);" onkeyup="onQuantityChangeHandler(event,<?= $key ?>)" id="qty<?= $key ?>" name="product[<?= $key ?>][qty]" value="<?= $row->qty ?>" class="form-control"></td>
														<td style="width:125px;padding:4px;"><input tabindex="<?= $key + 21 ?>" type="text" min="0" id="rate<?= $key ?>" name="product[<?= $key ?>][rate]" required="true" onchange="onRateChangeHandler(event,<?= $key ?>)" onkeyup="onRateChangeHandler(event,<?= $key ?>)" value="<?= $row->rate ?>" class="form-control floatNumber"></td>
														<td style="width:30px;padding:4px;"><input type="text" tabindex="<?= $key + 22 ?>" min="0" id="discount<?= $key ?>" name="product[<?= $key ?>][discount]" onchange="onDiscountHandler(event,<?= $key ?>)" onkeyup="onDiscountHandler(event,<?= $key ?>)" value="<?= $row->discount ?>" class="form-control floatNumber" style="width:45px;float:left;"></td>
														<td style="display:none;"><input type="text" min="0" id="cd<?= $key ?>" name="product[<?= $key ?>][cd]" onchange="onCDHandler(event,<?= $key ?>)" onkeyup="onCDHandler(event,<?= $key ?>)" class="form-control floatNumber" style="width:70px;float:left;"></td>
														<td style="width:81px;padding:4px;">
															<div id="taxal<?= $key ?>"><input style="width:81px;" tabindex="<?= $key + 23 ?>" class="form-control" onchange="ChangeTax(this.value,<?= $key ?>)" id="tax<?= $key ?>" name="product[<?= $key ?>][tax]" value="<?= $row->tax ?>" required=""></select></div>
														</td>
														<td style="width:125px;padding:4px;"><input type="text" readonly="" tabindex="<?= $key + 24 ?>" name="product[<?= $key ?>][price]" id="price<?= $key ?>" value="<?= $row->price ?>" onkeyup="onPriceChangeHandler(event,<?= $key ?>)" required="true" class="form-control"></td>
														<td style="width:125px;padding:4px;"><input type="text" min="0" tabindex="<?= $key + 25 ?>" readonly="" id="amount<?= $key ?>" value="<?= $row->taxAmount ?>" name="product[<?= $key ?>][amount]" class="form-control"> <input type="hidden" min="0" value="0.00" readonly="" id="taxAmount<?= $key ?>" name="product[<?= $key ?>][taxAmount]" class="form-control">
															<input autocomplete="off" type="hidden" min="0" value="<?= $row->taxAmount ?>" readonly id="taxAmount<?= $key ?>" name="product[<?= $key ?>][taxAmount]" class="form-control" />
															<input autocomplete="off" type="hidden" readonly id="sgst<?= $key ?>" name="product[<?= $key ?>][sgst]" value="<?= $row->sgst ?>" class="form-control" />
															<input autocomplete="off" type="hidden" readonly id="cgst<?= $key ?>" name="product[<?= $key ?>][cgst]" value="<?= $row->cgst ?>" class="form-control" />
															<input autocomplete="off" type="hidden" readonly id="igst<?= $key ?>" name="product[<?= $key ?>][igst]" value="<?= $row->igst ?>" class="form-control" />
														</td>
														<!-- <script>
													   </script> -->
														<td style="width:200px;padding:4px;"><input type="number" min="0" readonly="" id="netAmount<?= $key ?>" value="<?= $row->netAmount ?>" name="product[<?= $key ?>][netAmount]" class="form-control add-new-row" onkeydown="onNewRowAdd(event)"></td>
														<td style="width:83px;padding:4px;"><a href="#" tabindex="<?= $key + 26 ?>" onclick="onAddNewRowHandler()"><i class="fa fa-plus-circle text-success cp" title="Add new row"></i></a>&nbsp;&nbsp;<a href="#" onclick="onRemoveRowHandler(<?= $key ?>)"><i class="fa fa-minus-circle text-danger cp" title="Remove selected row"></i></a><a href="#" onclick="productForEdit(<?= $key ?>,<?= $row->product_id ?>)"><i class="fas fa-edit" title="Product Edit"></i></a></td>

													</tr>

												<?php
												} ?>
											</tbody>

										</table>

									</div>
								</div>

								<!-- words -->
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row pr-3">
											<label class="col-sm-2">Amount in words</label>
											<input type="text" required readonly class="col-sm-10 form-control" value="<?= $result->amountInWords ?>" id="amountInWords" name="amountInWords">
										</div>
									</div>
								</div>

								<!-- <input type="hidden" name="printPurchase" id="printPurchase" value="<?= $purchase_id ?>"> -->
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row pr-3">
											<label class="col-sm-2">Remark</label>
											<input autocomplete="off" type="text" class="col-sm-10 form-control" id="remark" name="remark">
										</div>
									</div>
								</div>

								<!-- Submit button -->
								<div class="row mb-3 btnsbmt">
									<div class="col-sm-6 col-md-6 col-xl-6">
										<!-- <div class="mr-1 mb-sm-1"> -->
										<button type="submit" id="btnSubmit" class="btn btn-outline-primary">Update</button>

									</div>
									<!-- <div class="col-md-2"></div> -->

								</div>
							</div>

							<!-- Right panel -->
							<div class="col-md-2 col-sm-12">
								<div class="row mb-1">
									<label class="col-7 p-0">Description</label>
									<label class="col-5 p-0">Amount</label>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="Taxable Amount">Taxable Amt.</span>
									<div class="col-7 p-0">
										<input type="number" class="form-control" step="any" id="taxableAmount" required="true" name="taxableAmount" value="<?= $result->taxableAmount ?>">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="totalsgst">Total SGST </span>
									<div class="col-7 p-0">
										<input type="number" value="<?= $result->totalsgst ?>" step="any" id="totalsgst" required="true" name="totalsgst" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="totalcgst">Total CGST</span>
									<div class="col-7 p-0">
										<input type="number" step="any" value="<?= $result->totalcgst ?>" id="totalcgst" required="true" name="totalcgst" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="totaligst">Total IGST</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totaligst" value="<?= $result->totaligst ?>" required="true" name="totaligst" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Total Tax</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totalTax" name="totalTax" value="<?= $result->totalTax ?>" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Expenses</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="expenses" value="<?= $result->expenses ?>" name="expenses" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">CESS</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="cess" name="cess" value="<?= $result->cess ?>" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Total Value</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totalValue" value="<?= $result->totalValue ?>" name="totalValue" required="true" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Round Off</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="roundOff" name="roundOff" min="0" value="<?= $result->roundOff ?>" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">L.R. Date</span>
									<div class="col-7 p-0">
										<input type="date" id="lrDate" name="lrDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">L.R. No.</span>
									<div class="col-7 p-0">
										<input type="text" id="lrNumber" name="lrNumber" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">E-Way Bill</span>
									<div class="col-7 p-0">
										<input type="text" id="ewayBill" name="ewayBill" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Disp. By</span>
									<div class="col-7 p-0">
										<input type="text" id="transport" name="transport" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px" title="Pay Terms (Day)">PTD.</span>
									<div class="col-7 p-0">
										<input type="text" id="payTermsDay" name="payTermsDay" class="form-control" placeholder="Pay Terms Day">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Due Date</span>
									<div class="col-7 p-0">
										<input type="date" id="dueDate" name="dueDate" required="true" class="form-control" value="<?php echo date('Y-m-d'); ?>">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">DC No.</span>
									<div class="col-7 p-0">
										<input type="text" id="dcNumber" name="dcNumber" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">DC Date</span>
									<div class="col-7 p-0">
										<input type="date" id="dcDate" name="dcDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px" title="Old Amount">Old Balance</span>
									<div class="col-7 p-0">
										<input type="number" id="oldBalance" name="oldBalance" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Total</span>
									<div class="col-7 p-0">
										<input type="number" id="total" value="<?= $result->totalValue ?>" name="total" class="form-control" required="true">
									</div>
								</div>
							</div>
						</div>


					</form>
				</div>

			</div>
		</div>
	</div>

</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="addUserForm" method="post" action="<?= base_url('party/create') ?>" onsubmit="onUserFormSubmitHandler(event)" autocomplete="on">
					<div class="" id="partyMsg" style="opacity: 500; display: none;"></div>
					<div class="row">
						<!-- <div class="col-md-4 mb-2">
            <label for="type" class="">Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-control searchselect" required>
                <option value="">-Select-</option>
         
            </select>
        </div> -->
						<div class="col-md-4 mb-2">
							<label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
							<select name="group" id="group" class="form-control searchselect" data-live-search="true" required>
								<option value="">-Select-</option>
								<?php

								$tax = $this->user_model->getResult('accounting_group');
								$role = $this->session->userdata('roles');
								if ($role == 1) {
									if (isset($tax) && !empty($tax)) {
										foreach ($tax as $units) {
								?>
											<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
											<?php
										}
									}
								} else {
									if (isset($tax) && !empty($tax)) {

										foreach ($tax as $units) {

											$user_id = $this->session->userdata('user_id');
											$comp_id = $this->session->userdata('comp_id');

											$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
											$resu = $this->user_model->getRow('accountant_status', $where);
											// $this->session->userdata('user_id');
											if (isset($resu) && !empty($resu)) {
												if ($resu->status == 1) {
											?>
													<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

												<?php
												}
											} else {
												?>
												<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

								<?php
											}
										}
									}
								}
								?>
							</select>
						</div>

						<div class="col-md-4 mb-2">
							<label for="name" class="">Name <span class="text-danger">*</span></label>
							<input required name="name" id="name" class="form-control textOnly" type="text" placeholder="Enter Name">
						</div>

						<div class="col-md-4 mb-2">
							<label for="contact" class="">Mobile <span class="text-danger"></span></label>
							<input onkeyup="checkContacts()" name="contact" id="contacts" class="form-control" type="text" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" placeholder="Enter Mobile">
							<span id="contackcheck"></span>
							<!-- <span class="text-success" id="contackcheck1"></span> -->


						</div>

						<div class="col-md-4 mb-2">
							<label for="email" class="">Email <span class="text-danger"></span></label>
							<input onkeyup="checkEmail()" name="email" id="emails" class="form-control " type="email" placeholder="Enter Email">
							<span id="mailcheck"></span>
						</div>
						<div class="col-md-4 mb-2">
							<label for="pan" class="">Pan No</label>
							<input name="pan" id="pan" class="form-control toUpper" type="text" placeholder="Enter Pan No" maxlength="10">
						</div>
						<div class="col-md-4 mb-2">
							<label for="address_1" class="">Address <span class="text-danger"></span></label>
							<input name="address_1" id="address_1" class="form-control" type="text" placeholder="Enter Address">
						</div>

						<div class="col-md-4 mb-2">
							<label class="control-label" for="state">City <span class="text-danger"></span></label>
							<select name="city" id="city" class="form-control searchselect" data-live-search="true">
								<option value="">-Select-</option>
								<?php $states = $this->db->get('districts')->result();
								if ($states) {
									foreach ($states as $row) { ?>
										<option value="<?= $row->id ?>" data-id="<?= $row->name ?>" data-state="<?= $row->name ?>" <?= set_value('city') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
								<?php
									}
								}
								?>
							</select>
						</div>

						<div class="col-md-4 mb-2">
							<label for="pin" class="">Pin <span class="text-danger"></span></label>
							<input type="text" name="pin" id="pin" placeholder="Pin Code" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
						</div>
						<div class="col-md-4 mb-2">
							<label for="state" class="">State <span class="text-danger">*</span></label><br>
							<select name="state" id="state" class="form-control searchselect" style="width:100%" required>
								<option value="">Select State</option>
								<?php $states = $this->db->get('state_list')->result();
								if ($states) {
									foreach ($states as $row) { ?>
										<option value="<?= $row->id ?>" data-id="<?= $row->code ?>"> <?= $row->state; ?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-md-4 mb-2">
							<label for="code" class="">State Code</label><br>
							<input type="text" readonly name="state_code" id="code" placeholder="Code" class="form-control" required>
						</div>
						<div class="col-md-4 mb-2">
							<label for="credit_limit" class="">Credit Limit</label><br>
							<input type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit') ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
						</div>
						<div class="col-md-4 mb-2">
							<label for="gstTypeParty" class="">gst Type <span class="text-danger"></span></label><br>
							<select name="gstType" id="gstTypeParty" class="form-control">
								<option value="">-Select-</option>
								<option value="Regular">Regular</option>
								<option value="Composite">Composite</option>
								<option value="N/A">N/A</option>
							</select>
						</div>

						<div class="col-md-4 mb-2" id="gstTypeVal">
							<!-- <label for="gst" class="">GSTIN No <span class="text-danger">*</span></label> -->
							<label class="control-label" for="gst">
								<a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No &gt;&gt;</a>
							</label>
							<input name="gst" id="gst" class="form-control toUpper" type="text" placeholder="Enter GSTIN No">
						</div>
						<div class="form-group col-md-4 mb-2">
							<label class="control-label" for="opening_balance">Opening Balance</label>
							<input autocomplete="off" type="text" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="<?= set_value('opening_balance') ?>" class="form-control">
						</div>

						<div class="form-group col-md-4 mb-2">
							<label class="control-label" for="opBalnceType">Type</label>
							<select name="opBalnceType" id="opBalnceType" class="form-control">
								<option value="">-Select-</option>
								<option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR</option>
								<option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR</option>
							</select>
						</div>

						<div class="form-group col-md-4 mb-2" style="margin-top:30px;">
							<label class="control-label" for="sms">Send Sms</label>
							<input type="checkbox" name="sms" id="sms">

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="custClose" onclick="closeModel()" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="btn_login">Save</button>
						<button type="reset" class="btn btn-danger" id="reset">Reset</button>

					</div>
				</form>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="mynewmodal" tabindex="-1" role="dialog" aria-labelledby="mynewmodal" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 400px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="mynewmodal"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h2 class="text-center">Description</h2>
				<input type="text" id="description_for" onchange="getDescriptionOnly()" name="description_for" class="form-control">
				<input type="hidden" id="description_for_id">
				<br>
				<br>
				<br>

				<button class="btn btn-primary" class="close" data-dismiss="modal" onclick="closeModel()">Save</button>
				<!-- Card stats -->
				<div class="row">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabels"></h5>
				<input type="hidden" id="rownumber">
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>
<div class="modal fade" id="productModel" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h2 class="text-center">Add Product</h2>
				<!-- Card stats -->
				<div class="row">
					<div class="" id="productMsg" style="opacity: 500; display: none;"></div>

					<form id="onProductFormSubmitHandler" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/savePro" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event)">

						<?php
						if (isset($error) && !empty($error)) {
							echo $error;
						} ?>

						<div class="row">
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="product_name" name="name" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error('name'); ?>

								</span>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<!-- <select id="p_code" name="p_code" class="form-control">
													 <option value="">Select Product Code</option> -->
								<?php
								$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
								$productCode = $this->user_model->getRow('productCode', $where);

								$productDetails = $this->user_model->getAry('product', '', '', '', $where);
								if (isset($productDetails) && !empty($productDetails)) {
									$productcodes = [];
									foreach ($productDetails as $proData) {
										$productcodes[] = $proData->qrCode;
									}
									if (isset($productcodes) && !empty($productcodes)) {
										$maxvalue = max($productcodes);
										if ($productCode) {
											$productCode = $productCode->productcode;
											if ($productCode < $maxvalue) {
								?>
												<input type="text" class="form-control" require name="pcode" id="pcode" value="<?= $maxvalue + 1 ?>">

											<?php
											} else {
											?>
												<input type="text" class="form-control" require name="pcode" id="pcode" value="<?= $productCode + 1 ?>">

											<?php
											}
										} else {
											?>
											<input type="text" class="form-control" require name="pcode" id="pcode" value="<?= 1 ?>">

										<?php
										}
									} else {
										if ($productCode) {
											$productCode = $productCode->productcode;
										} else {
											$productCode = 0;
										}
										?>
										<input type="text" class="form-control" require name="pcode" id="pcode" value="<?= $productCode + 1 ?>">

									<?php
									}
								} else {
									$productCode = $this->user_model->getRow('productCode', $where);
									if ($productCode) {
										$productCode = $productCode->productcode;
									} else {
										$productCode = 0;
									}
									?>
									<input type="text" class="form-control" require name="pcode" id="pcode" value="<?= $productCode + 1 ?>">

								<?php
								}



								// $this->db->select_max('qrCode');
								// $result = $this->db->get('product');
								// $row = $result->row_array();
								// $next_id = isset($row['max']) ? ($row['max']+1) : $productCode;
								// return $next_id;
								// if (isset($productCode) && !empty($productCode)) {
								// $units=max($unit);
								// $data = mysql_result($unit,0) + 1;

								?>
								<!-- <input type="text" class="form-control" name="p_code" id="p_code" value="<?= $productCode ?>"> -->
								<!-- // 	foreach ($unit as $units) {
													// ?> -->
								<!-- // 		<option value="<?= $units->id ?>" <?= set_value('p_code') == $units->id ? 'selected' : ''; ?>><?= $units->productcode; ?></option> -->
								<!-- // <?php
										// 	}
										// }

										?>
												 </select> -->

								<span class="col-sm-4 control-label">
									<?php echo form_error('p_code'); ?>

								</span>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="price" name="price" min="1" placeholder="Selling Price" type="number" class="form-control" value="<?= set_value('price') ?>">
								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error('price'); ?>

								</span>
							</div>
							<!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="cost" readonly name="sale_rate" data-toggle="modal" data-target="#myModal123" min="0" placeholder="Cost" type="number" class="form-control" required="true" value="<?= set_value('sale_rate') ?>">
								</div>
							</div> -->
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="cost" name="sale_rate" min="0" placeholder="Purchase Price" type="number" class="form-control" value="<?= set_value('sale_rate') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="mrp" name="mrp" min="0" placeholder="MRP" type="number" class="form-control" value="<?= set_value('mrp') ?>">
								</div>
							</div>
							<!-- Button to Open the Modal -->


							<!-- The Modal -->
							<!-- <div class="modal" id="myModal123">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">

										 Modal Header -->
							<!-- <div class="modal-header">
											<h4 class="modal-title text-center text-dark">Raw Material</h4>
											<button type="button" class="close" data-dismiss="modal">&times;</button>
										</div> -->

							<!-- Modal body -->
							<!-- <div class="modal-body">
											<div class="row">
												<div class="col-md-6"> -->
							<?php
							// $array = array();
							// // $varr='';
							// $array[0] = "--Select Group--";
							// $acgroup = $this->user_model->getResult('raw_material');
							// if ($acgroup) {
							// 	foreach ($acgroup as $teacher) {
							// 		$array[$teacher->id] = $teacher->name;
							// 	}
							// }

							// echo form_dropdown("raw", $array, set_value("raw"), "id='raw' class='form-control'");
							?>

							<!-- 
													<p></p>
												</div>

												<div class="col-md-6">

												</div>
												<div class="col-md-12 col-lg-12">
													<table id="raw_details" class="table table-striped">
														<tr class="text-dark">
															<th>Name</th>
															<th>Rate</th>
															<th>Weight</th>
															<th>unit</th>

															<th>Qty</th>
															<th>Total</th>



														</tr>
													</table>

												</div>
												<div class="col-md-12 col-lg-12">
													<table class="table table-striped">
														<tr class="text-dark">
															<td></td>
															<td></td>

															<td></td>
															<td></td>

															<td></td>

															<td>Extra:<input class="form-control" min="0" type="number" name="extra" id="extra"></td>
														</tr>

													</table>
												</div>
												<div class="col-md-12 col-lg-12">
													<table class="table table-striped">
														<tr class="text-dark">
															<td></td>
															<td></td>


															<td>Total Weight:<input type="text" class="form-control" readonly id="overall_weight"></td>

															<td>Total:<input class="form-control" type="text" readonly id="overall"></td>
														</tr>

													</table>
												</div>
											</div>
										</div>

										 Modal footer -->
							<!-- <div class="modal-footer">
											<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
										</div>

									</div>
								</div> -->
							<!-- </div> -->

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="qty" name="qty" min="1" placeholder="Product Qunatity" type="number" class="form-control" value="<?= set_value('qty') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="discount" name="discount" min="0" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="hsn_code" name="hsn_code" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="min_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?= set_value('min_qty') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="weight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<select id="unit" name="unit" class="form-control" required>
									<option value="">Select Unit</option>
									<?php
									$unit = $this->user_model->getResult('unit');
									$role = $this->session->userdata('roles');
									if ($role == 1) {
										if (isset($unit) && !empty($unit)) {
											foreach ($unit as $units) {
									?>
												<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

												<?php
											}
										}
									} else {
										if (isset($unit) && !empty($unit)) {
											foreach ($unit as $units) {

												$user_id = $this->session->userdata('user_id');
												$comp_id = $this->session->userdata('comp_id');

												$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
												$resu = $this->user_model->getRow('unit_status', $where);
												// $this->session->userdata('user_id');
												if (isset($resu) && !empty($resu)) {
													if ($resu->status == 1) {
												?>
														<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

													<?php
													}
												} else {
													?>
													<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

									<?php
												}
											}
										}
									}
									?>
								</select>
								<span class="col-sm-4 control-label">
									<?php echo form_error('unit'); ?>

								</span>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">

									<select id="tax" name="tax" class="form-control" required>
										<option value="">Select Tax</option>
										<?php
										$tax = $this->user_model->getResult('tax');

										if ($role == 1) {
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $units) {
										?>
													<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

													<?php
												}
											}
										} else {
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $units) {

													$user_id = $this->session->userdata('user_id');
													$comp_id = $this->session->userdata('comp_id');

													$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
													$resu = $this->user_model->getRow('tax_status', $where);
													// $this->session->userdata('user_id');
													if (isset($resu) && !empty($resu)) {
														if ($resu->status == 1) {
													?>
															<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

														<?php
														}
													} else {
														?>
														<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

										<?php
													}
												}
											}
										}
										?>
									</select>

								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error("tax"); ?>

								</span>
							</div>
							<!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<?php
								// $array = array();
								// // $varr='';
								// $acgroup = $this->user_model->getResult('product_categories');
								// if ($acgroup) {
								// 	foreach ($acgroup as $teacher) {
								// 		$array[$teacher->id] = $teacher->category_name;
								// 	}
								// }

								// echo form_dropdown("cate", $array, set_value("cate"), "id='cate' class='form-control'");
								?>
								<span class="col-sm-4 control-label">
									<?php
									// echo form_error('cate'); 
									?>

								</span>
							</div> -->

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3 form-group">
								<div class="custom-file">
									<input id="customFile" name="image" type="file" class="custom-file-input" value="<?= set_value('image') ?>">
									<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<textarea id="shortdescription" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<textarea id="description" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= set_value('long_des') ?></textarea>
								</div>
							</div>

						</div>


						<div class="row mt-5">
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-default">


									Save

								</button>
								<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>
</div>


<script>
	function onProductFormSubmitHandler(e) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#onProductFormSubmitHandler").attr("action");
		var form = $("#onProductFormSubmitHandler")[0];
		var data = new FormData(form);
		//(data);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					// closeModel();
					// alert("party Created");
					// window.location.href = "<?= base_url() ?>invent/sale";
					location.reload();
					alert("Product Added")
					document.getElementById('productModel').click();

					// window.location.reload();
					//$("#invoiceNumber").trigger("change");
					// window.reload();

					// //(customerList.data.id, "id");

					// prepareCustomerDropdown(customerList.data);


				} else {
					$("#productMsg").show();
					$("#productMsg").addClass("text");
					$("#productMsg").addClass("text-danger");
					$("#productMsg").html(customerList.msg);
					// setTimeout(function() {
					// 	$("#partyMsg").hide();
					// }, 3000);
				}
			},
			error: function(jqXHR, exception) {
				$("#exampleModal").modal("hide");
				$("#loader").addClass("d-none");
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}


	// const prepareCustomerDropdown = (customers) => {
	// 	// //(customers);
	// 	// var code = $(this).find(':selected').data('id');
	// 	// $('#code').val(code); <
	// 	// //(customers.id);
	// 	$("#party_id").value(customers.id);

	// 	$('#gstNo').val(customers.gstno);


	// };
</script>
<script>
	$(document).ready(function() {
		// getProducts();
		$('#invoiceNumber').focus();
		$('#customerNameDiv').hide()
		$('.addDdl').hide();

		$('.searchselect').select2();

		//$("#invoiceDate").val(moment(currentDate).format('YYYY-MM-DD'))
		// //(gat);
	});



	function checkEmail() {
		var email = document.getElementById('emails').value;
		// //(email);
		if (email != '') {
			$.ajax({
				url: baseurl + "index.php/party/checkmail",
				method: "POST",
				data: {
					email: email,
				},
				success: function(products) {
					var contact = JSON.parse(products);
					//(contact);
					if (contact.status == false) {
						// spanTag1.innerHTML = '';
						// document.getElementById('contackcheck').style.display = 'none';

						document.getElementById('mailcheck').innerHTML = '<span class="text-success">Available</span>';

						document.getElementById('btn_login').disabled = false;

						//("true");

					} else {
						document.getElementById('mailcheck').innerHTML = '<span class="text-danger">Not Available</span>';

						// document.getElementById('contackcheck1').style.display = 'none';

						// document.getElementById('contackcheck').innerHTML = 'Not Available';

						document.getElementById('btn_login').disabled = true;

					}


				},



			});
		} else {
			document.getElementById('mailcheck').innerHTML = '<span class="text-danger">Not Valid</span>';

			// document.getElementById('contackcheck1').style.display = 'none';

			// document.getElementById('contackcheck').innerHTML = 'Not Available';

			document.getElementById('btn_login').disabled = true;

		}
	}

	function checkContacts() {
		var contacts = document.getElementById('contacts').value;
		if (contacts.length > 5) {
			//(contacts);
			$.ajax({
				url: baseurl + "index.php/party/checkcontacts",
				method: "POST",
				data: {
					contact: contacts,
				},
				success: function(products) {
					var contact = JSON.parse(products);
					//(contact);
					if (contact.status == false) {
						// spanTag1.innerHTML = '';
						// document.getElementById('contackcheck').style.display = 'none';

						document.getElementById('contackcheck').innerHTML = '<span class="text-success">Available</span>';

						document.getElementById('btn_login').disabled = false;

						//("true");

					} else {
						document.getElementById('contackcheck').innerHTML = '<span class="text-danger">Not Available</span>';

						// document.getElementById('contackcheck1').style.display = 'none';

						// document.getElementById('contackcheck').innerHTML = 'Not Available';

						document.getElementById('btn_login').disabled = true;

					}

				},

			});
		} else {
			document.getElementById('contackcheck').innerHTML = '<span class="text-danger">Not Vallid</span>';

			document.getElementById('btn_login').disabled = false;
		}
	}


	function getProducts() {
		$.ajax({
			url: baseurl + "index.php/product/getproducts",
			method: "GET",
			success: function(products) {
				var products = JSON.parse(products);
				if (products.status == true) {
					prepareProductsDropdown(products.data.product, products.data.productCode);
					if ("" != null && "" !== "") {
						$("#invoiceNumber").val("");
						$("#invoiceNumber").trigger("change");
					}
					$("td .select2-container").css("width", "150px");
					//("=====Prepare======");
				}
				$('.searchselect').select2();

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permissio n denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	function ProductsGet(product_id) {
		//(product_id);
		$.ajax({
			method: "POST",
			data: {
				id: product_id,
			},
			url: baseurl + "index.php/product/proInfo",

			success: function(products) {
				var products = JSON.parse(products);
				if (products.status == true) {
					//(products.data);
					// let rowCount = $('#dataTable tbody tr').length + 1;

					prepareProducts(products.data);
					if ("" != null && "" !== "") {
						$("#invoiceNumber").val("");
						$("#invoiceNumber").trigger("change");
					}
					$("td .select2-container").css("width", "150px");
					//("=====Prepare======");
				}
				$('.searchselect').select2();

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permissio n denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	function print(value) {
		window.print(value);
	}
	$("#consignee").click(function() {
		// if($('#consigneediv').is(':visible'))
		// {
		//    $('#consigneediv').find('input:text').val('');
		//    $('#consigneediv').find("select").prop("selectedIndex",0);
		// }
		$('#consigneediv').toggle("slow");
	});

	function deleteRow() {
		var printValue = document.getElementById('printSale').value;
		if (printValue == '') {
			alert('ENter First Invocie Number That you Want to delete');
			return;
		}
		var url = "<?php echo base_url(); ?>index.php/sale/sale_delete/" + printValue
		//(url, "url");
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

	$('#consigneestate').change(function() {
		var code = $(this).find(':selected').data('id');
		$('#consigneestatecode').val(code);
	});
	// $("#party_id").change(function() {
	// //(e);
	// var elmtTable = document.getElementById('dataTable');
	// var tableRows = elmtTable.getElementsByTagName('tr');
	// var rowCount = tableRows.length;
	// //(rowCount);
	// for (var x = rowCount; x > 0; x--) {
	// 	//    elmtTable.remove(tableRows[x]);
	// 	// onRemoveRowHandler(tableRows[x])
	// 	$("#tr" + x).remove();

	// }
	// getProducts();

	// 	var party_id = document.getElementById('party_id').value;
	// 	// //(party_id);
	// 	getCustomerDetails(party_id);
	// });
	function partyID(e) {
		// $("#party_id").change(function() {
		// //(e);


		var party_id = e.target.value;;
		// //(party_id);
		getCustomerDetails(party_id);
		// })
	};
	// $("#invoiceNumber").change(function() {
	// 	// //(e);
	// 	// onRemoveRowHandler();
	// 	// $("#tr" + 1).remove();
	// 	var elmtTable = document.getElementById('dataTable');
	// 	var tableRows = elmtTable.getElementsByTagName('tr');
	// 	var rowCount = tableRows.length;
	// 	// //(rowCount);
	// 	for (var x = rowCount; x > 0; x--) {
	// 		//    elmtTable.remove(tableRows[x]);
	// 		// onRemoveRowHandler(tableRows[x])
	// 		$("#tr" + x).remove();

	// 	}

	// 	var invoiceNumber = document.getElementById('invoiceNumber').value;

	// 	//(invoiceNumber, "invocienumber");
	// 	getInvoiceInfo(invoiceNumber);
	// });


	const getInvoiceInfo = (InvoiceNumber) => {

		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/InvoiceNumber",

			data: {
				id: InvoiceNumber,
			},
			success: function(customer) {

				var idss = [];
				var customer = JSON.parse(customer);
				//(customer, "details");
				var da = customer.data;

				var product_details = da.invoice_product;
				//(product_details);
				if (product_details != undefined) {
					var customerDetails = da.party;



					// idss = da.product_ids;

					if (customerDetails.customerId > 0) {
						getCustomerDetails(customerDetails.customerId);
						//(customerDetails.customerId, "customerId");

						// let rowCount = $('#dataTable tbody tr').length + 1;
						document.getElementById('printSale').value = customerDetails.id;
					} else {
						var customer = customerDetails;
						// getCustomerInformation(customerDetails.customerId);
						// let rowCount = $('#dataTable tbody tr').length + 1;
						document.getElementById('nameCustomerKa').value = customerDetails.customerName;
						$("#party_id").val(customer != null ? customer.customerName : "");

						stateCode = customer.state_code ? customer.state_code : "";
						// getStateName(customer.state_code);
						// //(state_name);
						// $name=customer.state;
						sgstPer = 0;
						cgstPer = 0;
						igstPer = 0;
						$("#gstNo").val(customer != null ? customer.gstNo : "");

						$("#gstType").val(customer != null ? customer.gstType : "");
						$("#customerAddress").val(
							customer != null ? customer.address_1 : ""
						);
						$("#customerCity").val(customer != null ? customer.customerCity : "");
						$("#customerMobile").val(customer != null ? customer.customerMobile : "");
						$("#customerEmail").val(customer != null ? customer.customerEmail : "");
						$("#customerBalance").val(customer != null ? customer.credit_limit : "");
						$("#stateSelect").val(customer != null ? customer.state_code : "");



					}

					for (i = 0; i < product_details.length; i++) {

						ProductsGet(product_details[i].product_id);
					}
				} else {
					getProducts();
				}

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	const getCustomerDetails = (customerId) => {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/getCustomer",

			data: {
				id: customerId,
			},
			success: function(customer) {
				var customer = JSON.parse(customer);
				setSelectedCustomerDetails(customer.data);
			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};
	// 	function showAddCustomerDialoag() {
	//   $("#exampleModal .modal-dialog .modal-body").load(baseurl + "index.php/party/load_party");
	//   $("#exampleModalLabel").html("Add Party");
	//   $("#exampleModal").modal("show");
	//   $(".searchselect").select2({ dropdownParent: "#exampleModal" });
	// }
	const setSelectedCustomerDetails = (customer) => {
		stateCode = customer.state_code ? customer.state_code : "";
		// getStateName(customer.state_code);
		// //(state_name);
		// $name=customer.state;
		sgstPer = 0;
		cgstPer = 0;
		igstPer = 0;
		$("#gstNo").val(customer != null ? customer.gst : "");

		$("#gstType").val(customer != null ? customer.gstType : "");
		$("#customerAddress").val(
			customer != null ? customer.address_1 : ""
		);
		$("#customerCity").val(customer != null ? customer.city : "");
		$("#customerMobile").val(customer != null ? customer.contact : "");
		$("#customerEmail").val(customer != null ? customer.email : "");
		$("#customerBalance").val(customer != null ? customer.credit_limit : "");
		$("#stateSelect").val(customer != null ? customer.state_code : "");
		$("#party_id").val(customer != null ? customer.id : "");






	};

	function showAddCustomerDialoag() {
		$("#exampleModal .modal-dialog .modal-body").load(baseurl + "index.php/party/load_party");
		$("#exampleModalLabel").html("Add Party");
		// $("#exampleModal").show();
		$('#exampleModal').modal('show');
		$(".searchselect").select2({
			dropdownParent: "#exampleModal"
		});
	}

	function getStateName(state_id) {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/state/stateName",

			data: {
				id: state_id,
			},
			success: function(customer) {
				var name = JSON.parse(customer);

				$("#stateSelect").val(customer != null ? name.data : "");



			},
		})
	}

	function showDDLBox() {
		$("#party_id").attr("disabled", false);
		$("#party_id").attr("required", true);
		$("#customerName").val("");
		$("#party_idDiv").show();
		$("#customerNameDiv").hide();
		$("#customerName").attr("disabled", true);
		$("#customerName").attr("required", false);
		$(".addPatys").show();
		$(".addText").show();
		$(".addDdl").hide();
	}

	function showTextBox() {
		$("#party_id").attr("disabled", true);
		$("#party_id").attr("required", false);
		$("#party_id").val("").trigger("change");
		$("#party_idDiv").hide();
		$("#customerNameDiv").show();
		$("#customerName").attr("disabled", false);
		$("#customerName").attr("required", true);
		$(".addPatys").hide();
		$(".addText").hide();
		$(".addDdl").show();
	}

	const prepareProductsDropdown = (products, productsCode) => {

		if (products != null && products != '' && typeof products !== "undefined") {
			productArray = products;
		}
		if (productsCode != null && productsCode != '' && typeof productsCode !== "undefined") {
			productArrays = productsCode;
		}
		//(products, "product First data");
		//(productsCode, "product Code data");

		let rowCount = $('#dataTable tbody tr').length + 1;
		let productCodeOptions = '<option value="">PCode</option>';

		let productOptions = '<option value="">Select Product</option>';
		products.map(function(product) {
			productOptions += '<option value="' + product.id + '">' + product.p_name + '</option>';
		})

		productsCode.map(function(proCode) {
			productCodeOptions += '<option value="' + proCode.id + '">' + proCode.productcode + '</option>';
		})
		let unitOptions = '<option value="">Select Unit</option>';

		let readOnly = $("#invoiceType").val() === 2 ? 'readonly' : '';

		$("#dataTable tbody").append('<tr id="tr' + rowCount + '">\n' +
			// '                                                        <td style="width:120px;padding:4px;">\n' +
			// '                                                          <div id="productcode' + rowCount + '"><select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" required="true">' + productCodeOptions + '</select></div>\n' +
			// '                                                       </div></td>\n' +
			'<td style="width:120px;padding:4px;">\n' +
			'                                                          <input type="text" style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="form-control" onchange="onProductCodeSelect(' + rowCount + ',event)">\n' +
			'                                                       </td>\n' +
			'                                                        <td style="width: 195px !important;padding:4px;">\n' +
			'                                                           <div id="pro_names' + rowCount + '"><select style="width: 195px !important;" name="product[' + rowCount + '][product]"  id="productId' + rowCount + '" class="searchselect form-control" onchange="onProductSelect(' + rowCount + ',event)"  onfocusout="onProductFocusChange(event,' + rowCount + ')" required="true">' + productOptions + '</select></div>\n' +
			'                                                       </td>\n' +
			'<td style="padding:4px;">' +
			'<input type="text" readonly hidden id="product_dec' + rowCount + '" name="product[' + rowCount + '][product_dec]" data-toggle="modal" data-target="#desc' + rowCount + '" onmouseover="showPop(' + rowCount + ',event)" value="" class="form-control">' +
			'<i class="fas fa-edit" data-toggle="modal" data-target="#desc' + rowCount + '" onmouseover="showPop(' + rowCount + ',event)" title="Product Edit"></i></td>' +
			'                                                        <td style="width:30px;padding:4px;"><input style="width:45px;" type="text" id="hsnCode' + rowCount + '" name="product[' + rowCount + '][hsn]"  class="form-control" /></td>\n' +
			'                                                         <td style="width:125px;padding:4px;"><input  type="number" min="1" required="true" onchange="onQuantityChangeHandler(event,' + rowCount + ');" onblur="checkStock(event,' + rowCount + ');showSerial(event,' + rowCount + ');" onkeypress="return IsNumeric(event);" onkeyup="onQuantityChangeHandler(event,' + rowCount + ')" id="qty' + rowCount + '" name="product[' + rowCount + '][qty]" class="form-control"/></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input  type="text" min="0" value="0" id="rate' + rowCount + '" name="product[' + rowCount + '][rate]" required="true" onchange="onRateChangeHandler(event,' + rowCount + ')" onkeyup="onRateChangeHandler(event,' + rowCount + ')"  class="form-control floatNumber"/></td>\n' +
			'                                                        <td style="width:30px;padding:4px;"><input  type="text" min="0" value="0" id="discount' + rowCount + '" name="product[' + rowCount + '][discount]"  onchange="onDiscountHandler(event,' + rowCount + ')" onkeyup="onDiscountHandler(event,' + rowCount + ')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +
			'                                                        <td style="display:none;"><input  type="text" min="0" value="0" id="cd' + rowCount + '" name="product[' + rowCount + '][cd]"  onchange="onCDHandler(event,' + rowCount + ')" onkeyup="onCDHandler(event,' + rowCount + ')" class="form-control floatNumber" style="width:70px;float:left;"/></td>\n' +
			'                                                        <td style="width:81px;padding:4px;"><div id="taxal' + rowCount + '"><input style="width:65px;"  type="number" value="0" min="0"  id="tax' + rowCount + '" name="product[' + rowCount + '][tax]" class="form-control"/></div></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input  type="text" readonly name="product[' + rowCount + '][price]" id="price' + rowCount + '" onkeyup="onPriceChangeHandler(event,' + rowCount + ')" required="true" class="form-control" ></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input  type="text" min="0" value="0" readonly id="amount' + rowCount + '" name="product[' + rowCount + '][amount]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="0" readonly id="taxAmount' + rowCount + '" name="product[' + rowCount + '][taxAmount]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="0" readonly id="sgst' + rowCount + '" name="product[' + rowCount + '][sgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="0" readonly id="cgst' + rowCount + '" name="product[' + rowCount + '][cgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="0" readonly id="igst' + rowCount + '" name="product[' + rowCount + '][igst]" class="form-control"/></td>\n' +
			'                                                        <td style="width:200px;padding:4px;"><input  type="number" min="0" value="0" readonly id="netAmount' + rowCount + '" name="product[' + rowCount + '][netAmount]" class="form-control add-new-row" onkeydown="onNewRowAdd(event)"/></td>\n' +
			'                                                        <td style="padding:4px;">' +
			'<a href="#" onclick="onAddNewRowHandler()"><i class="fa fa-plus-circle text-success cp" title="Add new row">' +
			'</i></a>&nbsp;&nbsp;<a href="#" onclick="onRemoveRowHandler(' + rowCount + ')"><i class="fa fa-minus-circle text-danger cp" title="Remove selected row"></i></a>' +
			'<span id="editProduct' + rowCount + '"></span>' +
			'</td>\n' +
			'                                                    </tr>');
		// $(".searchselect").select2()
		// $(".searchselect").select2("destroy").select2();
		// reinitialize();
	}


	const prepareProducts = (products) => {
		var type = "Sale";
		if (products != null && products != '' && typeof products !== "undefined") {
			productArray = products;

		}

		//(productArray, "products data 2nd");
		let rowCount = $('#dataTable tbody tr').length + 1;
		// alert(rowCount);
		// let productOptions = '<option value="">Select Product</option>';
		getProductName(products.product_id, rowCount);

		// getProductCodeDetails(products.p_code, rowCount);

		// products.map(function(product) {
		// productOptions += '<option value="' + products.product_id + '">' + products.product_id + '</option>';
		// });

		let unitOptions = '<option value="">Select Unit</option>';

		let readOnly = $("#invoiceType").val() === 2 ? 'readonly' : '';

		$("#dataTable tbody").append(' <tr id="tr' + rowCount + '">\n' +
			'<td style="width:120px;padding:4px;">\n' +
			'                                                          <input type="text" style="width:100%;" value="' + products.qrCde + '" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="form-control" onchange="onProductCodeSelect(' + rowCount + ',event)">\n' +
			'                                                       </td>\n' +
			'                                                        <td style="width: 150px !important;padding:4px;">\n' +
			'                                                           <div id="pro_names' + rowCount + '"></div>' +
			'                                                          <td style="padding:4px;"><input type="text" readonly id="product_dec' + rowCount + '" name="product[' + rowCount + '][product_dec]" data-toggle="modal" data-target="#desc' + rowCount + '" onmouseover="showPop(' + rowCount + ',event)" value="' + products.product_dec + '" class="form-control"></td>' +
			'                                                        <td style="width:30px;padding:4px;"><input  type="text" id="hsnCode' + rowCount + '" name="product[' + rowCount + '][hsn]" value="' + products.hsn + '" class="form-control" / style="width:45px;"></td>\n' +
			'                                                         <td style="width:125px;padding:4px;"><input  type="number" min="1" value="' + products.qty + '" required="true" onchange="onQuantityChangeHandler(event,' + rowCount + ');" onblur="checkStock(event,' + rowCount + ');showSerial(event,' + rowCount + ');" onkeypress="return IsNumeric(event);" onkeyup="onQuantityChangeHandler(event,' + rowCount + ')" id="qty' + rowCount + '" name="product[' + rowCount + '][qty]" class="form-control"/></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input  type="text" min="0" value="' + products.rate + '" id="rate' + rowCount + '" name="product[' + rowCount + '][rate]" required="true" onchange="onRateChangeHandler(event,' + rowCount + ')" onkeyup="onRateChangeHandler(event,' + rowCount + ')"  class="form-control floatNumber"/></td>\n' +
			'                                                        <td style="width:30px;padding:4px;"><input  type="text" min="0" value="' + products.discount + '" id="discount' + rowCount + '" name="product[' + rowCount + '][discount]"  onchange="onDiscountHandler(event,' + rowCount + ')" onkeyup="onDiscountHandler(event,' + rowCount + ')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +
			'                                                        <td style="display:none;"><input  type="text" min="0" value="0" id="cd' + rowCount + '" name="product[' + rowCount + '][cd]"  onchange="onCDHandler(event,' + rowCount + ')" onkeyup="onCDHandler(event,' + rowCount + ')" class="form-control floatNumber" style="width:70px;float:left;"/></td>\n' +
			'                                                        <td style="width:81px;padding:4px;"><div id="taxal' + rowCount + '"><input style="width:66px;" type="number" value="' + products.tax + '" min="0"  id="tax' + rowCount + '"  name="product[' + rowCount + '][tax]" class="form-control"/></div></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input  type="text" value="' + products.purchase_price + '" readonly name="product[' + rowCount + '][price]" id="price' + rowCount + '" onkeyup="onPriceChangeHandler(event,' + rowCount + ')" required="true" class="form-control" ></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input  type="text" min="0" value="' + products.amount + '" readonly id="amount' + rowCount + '" name="product[' + rowCount + '][amount]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="' + products.taxAmount + '" readonly id="taxAmount' + rowCount + '" name="product[' + rowCount + '][taxAmount]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="' + products.sgst + '" readonly id="sgst' + rowCount + '" name="product[' + rowCount + '][sgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="' + products.cgst + '" readonly id="cgst' + rowCount + '" name="product[' + rowCount + '][cgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="' + products.igst + '" readonly id="igst' + rowCount + '" name="product[' + rowCount + '][igst]" class="form-control"/></td>\n' +
			'                                                        <td style="width:200px;padding:4px;"><input  type="number" min="0" value="' + products.netAmount + '" readonly id="netAmount' + rowCount + '" name="product[' + rowCount + '][netAmount]" class="form-control add-new-row" onkeydown="onNewRowAdd(event)"/></td>\n' +
			'                                                        <td style="padding:4px;">' +
			'<a href="#" onclick="onAddNewRowHandler()"><i class="fa fa-plus-circle text-success cp" title="Add new row">' +
			'</i></a>&nbsp;&nbsp;<a href="#" onclick="onRemoveRowHandler(' + rowCount + ')"><i class="fa fa-minus-circle text-danger cp" title="Remove selected row"></i></a>' +
			'</td>\n' +
			'                                                    </tr>');


		// var tax = document.getElementById('tax' + rowCount).value;
		// var amount = document.getElementById('amount' + rowCount).value;
		// var type = 'Sale'
		// //(tax);
		// //(amount);
		// getTaxData(products.tax, rowCount);


		// calculatePrice(rowCount, type);
		// calculateGST(tax, amount, rowCount);

		// manipulateRHSData();
		// onRateChangeHandler(amount, rowCount);


		// $(".searchselect").select2()
		// $(".searchselect").select2("destroy").select2();
		// reinitialize();


	}
	const onProductSelect = (rowNo, e, type = "Sale") => {

		//(rowNo, "rowno");
		//(e, "event")
		// $('#desca').modal('show');

		const productId = e.target.value;
		if (productId == "" || productId == null) {
			// $('#desca').modal('show');

			// $("#desc" + rowNo).trigger("change");
			onRemoveRowHandler(rowNo);
		}
		$('#editProduct' + rowNo).html('<a href="#" onclick="productForEdit(' + rowNo + ',' + productId + ')"><i class="fas fa-edit" title="Product Edit"></i></a>');

		// $(this).show('true');

		// $('#product_dec' + rowNo).html('<textarea class="form-control" name="product[' + rowNo + '][product_dec]" style="resize:none;height:40px;" placeholder="Enter Description"></textarea>');
		$.ajax({
			method: "GET",
			url: baseurl + "index.php/product/getProduct/" + productId,
			success: function(product) {
				var product = JSON.parse(product);
				var product = product.data;
				$("#product" + rowNo).attr("name", product.p_name);
				setSelectedProductDetails(rowNo, product, type);

				// $('#product' + rowNo).on('select2:select', function(e) {
				//     document.getElementById("cost" + rowNo).focus()
				// });

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};


	const onProductSelects = (rowNo, e, type = "Sale") => {
		$("#product" + rowNo).trigger("change");

		const productId = e.target.value;
		if (productId == "" || productId == null) {
			document.getElementById("party_id").focus();

			onRemoveRowHandler(rowNo);
		}
		// $('#product_dec' + rowNo).html('<textarea class="form-control" name="product[' + rowNo + '][product_dec]" style="resize:none;height:40px;" placeholder="Enter Description"></textarea>');
		$.ajax({
			method: "GET",
			url: baseurl + "index.php/product/getProduct/" + productId,
			success: function(product) {
				var product = JSON.parse(product);

				var product = product.data;
				//(product, "pro");



				$("#product" + rowNo).attr("name", product.p_name);
				setSelectedProductDetails(rowNo, product, type);
				// $('#product' + rowNo).on('select2:select', function(e) {
				//     document.getElementById("cost" + rowNo).focus()
				// });
			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};
	const discounttypeHandler = (e, rowNumber) => {
		discount_type = e.target.value;
		$("#discount" + rowNumber).trigger("change");
	};
	const getProductsCode = (procode, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: procode,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>productCode/getProductsCode",
			success: function(productCode) {
				//(productCode, "productcode");
				$("#productcode" + rowNum).html(productCode);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);


			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}
	const setSelectedProductDetails = (rowNo, product, type) => {
		// //(product);
		var tax = product.tax_qty;
		var tax1 = product.tax;
		//(tax1, "tax1");
		getTaxData(tax1, rowNo);
		// let productCode = product.qrCode;
		// if (productCode) {
		// 	getProductsCode(productCode, rowNo);
		// }
		$("#p_code" + rowNo).val(product.qrCode);

		getProductName(product.id, rowNo);

		$("#rate" + rowNo).val(product.price)
		$("#tax" + rowNo).val(tax);
		$("#tax" + rowNo).trigger("change");

		$("#qty" + rowNo).val(1);
		if (type == "Purchase") {
			$("#rate" + rowNo).val(product.price);
		} else {
			// $("#rate" + rowNo).trigger("change");

			// $("#rate" + rowNo).val(product.price);

			var ratting = $("#rate" + rowNo).val();
			// alert(ratting);

			$("#productId" + rowNo).val(product.id);
			$("#itemCode" + rowNo).val(product != null ? 1 : "");
			$("#hsnCode" + rowNo).val(product != null ? product.hsn : "");
			// $("#stock" + rowNo).val(
			//   product != null &&
			//     product.current_stock != null &&
			//     product.current_stock != ""
			//     ? product.current_stock
			//     : "0"
			// );

			// $("#rate" + rowNo).val(
			// 	product != null ? parseInt(product.price) : "0"
			// );
			var actual_price = product.price;
			purchase_rate = product.price;
			rate = purchase_rate - ((purchase_rate * tax) / (100 + parseInt(tax)));
			let amount = rate;
			//(amount, "amount");
			// $("#base_price" + rowNo).val(rate);
			$("#qty" + rowNo).trigger("change");
			$("#discount" + rowNo).val(0);
			var qty = $("#qty" + rowNo).val();

			// $("#cost" + rowNo).val(parseFloat(product.purchase_price));

			// let cd = $("#cd" + rowNo).val() ? $("#cd" + rowNo).val() : "0";
			let discount = 0;
			// if (discount_type == "%") {
			amount = (amount * (100 - discount)) / 100;
			//(amount, "overrite");
			// } else if (discount_type == "Rs") {
			//   amount = amount - discount;
			// }
			// if (discountcd_type == "%") {
			// amount = (amount * (100 - cd)) / 100;
			// } else if (discountcd_type == "Rs") {
			//   amount = amount - cd;
			// }
			// $("#amount" + rowNo).val(
			// 	($("#qty" + rowNo).val() * parseFloat(amount)).toFixed(2)
			// );

			// $("#amount" + rowNo).val(amount.toFixed(2));

			const taxPercent = $("#tax" + rowNo).val();
			const taxcal = $("#taxcal").val();
			//(taxcal);
			var taxAmount;
			// var price = ((100 + parseFloat(taxPercent)) * parseFloat(amount)) / 100;
			// var price = parseInt(qty) * parseInt(purchase_rate);
			//(price);
			if (taxcal == 1) {
				$("#price" + rowNo).val(amount.toFixed(2));

				var taxAmount = calculateTaxAmount(amount, taxPercent);
			} else {
				$("#price" + rowNo).val(actual_price);

				var taxAmount = calculateTaxAmount(actual_price, taxPercent);

				//(taxAmount, "TeaxtAmountinsideeexclusive");
			}

			// //(taxAmount)
			$("#taxAmount" + rowNo).val(taxAmount.toFixed(2));
			$("#amount" + rowNo).val(taxAmount.toFixed(2));

			//(actual_price, "actiral price1234567");

			//(taxAmount, "TeaxtAmount1234");

			// $("#netAmount" + rowNo).val(
			// 	(parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
			// );
			calculatePrice(rowNo, type);

			calculateGST(tax, amount, rowNo);
			manipulateRHSData();
			$('#editProduct' + rowNo).html('<a href="#" onclick="productForEdit(' + rowNo + ',' + product.id + ')"><i class="fas fa-edit" title="Product Edit"></i></a>');

			// jQuery.noConflict();
			// document.getElementById('description_for_id').value = rowNo;
			// $('#mynewmodal').modal('show');
		}
	};

	const onProductCodeSelect = (rowNo, e, type = "Sale") => {

		//(rowNo, "rowno");
		//(e, "event")
		// alert(rowNo)
		// $('#desca').modal('show');
		var productId = null;
		productId = e.target.value;
		// alert(productId)

		if (productId == "" || productId == null) {
			// alert(productId);

			onRemoveRowHandler(rowNo);
		}
		if (productId != null) {
			// $(this).show('true');

			// $('#product_dec' + rowNo).html('<textarea class="form-control" name="product[' + rowNo + '][product_dec]" style="resize:none;height:40px;" placeholder="Enter Description"></textarea>');
			$.ajax({
				method: "GET",
				url: baseurl + "index.php/productCode/getProductCode/" + productId,
				success: function(product) {
					var product = JSON.parse(product);
					//(product, "pro info amana")
					// alert(product.status)

					if (product.status == true) {
						var product = product.data;
						$("#product" + rowNo).attr("name", product.p_name);
						setSelectedProductDetails(rowNo, product, type);
					} else {
						// alert(product);

						onRemoveRowHandler(rowNo);

					}
					// $('#product' + rowNo).on('select2:select', function(e) {
					//     document.getElementById("cost" + rowNo).focus()
					// });
				},
				error: function(jqXHR, exception) {
					var msg = "";
					if (jqXHR.status === 0) {
						msg = "Not connect.\n Verify Network.";
					} else if (jqXHR.status == 401) {
						msg = "Unauthorized User. [" + jqXHR.status + "]";
						window.location.href = "/gst/signout";
					} else if (jqXHR.status == 403) {
						msg = "Permission denied. [403]";
					} else if (jqXHR.status == 404) {
						msg = "Requested page not found. [404]";
					} else if (jqXHR.status == 406) {
						msg = jqXHR.responseText;
					} else if (jqXHR.status == 500) {
						msg = "Internal Server Error [500].";
					} else if (exception === "parsererror") {
						msg = "Requested JSON parse failed.";
					} else if (exception === "timeout") {
						msg = "Time out error.";
					} else if (exception === "abort") {
						msg = "Ajax request aborted.";
					} else {
						msg = "Uncaught Error.\n";
						console.error(jqXHR.responseText);
					}
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: msg,
						showConfirmButton: false,
						timer: 2000,
					});
				},
			});
		}
	};
	const showModelPop = (rowNumber, e) => {
		$('#mynewmodal').modal('show');

		alert(e);
	}
	const calculateTaxAmount = (amount, taxPercent) => {
		let taxAmount = (amount * taxPercent) / 100;
		return taxAmount;
	};
	const calculateTaxAmount1 = (amount, taxPercent) => {
		let taxAmount = amount - (amount * taxPercent) / 100;
		return taxAmount;
	};
	const getDescriptionOnly = () => {
		// var row = document.getElementById("tr" + rowNo);
		// $('#list').on('keyup', 'tr:last td:nth-last-child(2)', function (event));
		var rowNumber = document.getElementById('description_for_id');
		if (rowNumber != null) {
			rowNumber = rowNumber.value;
		} else {
			rowNumber = "";
		}
		var contents = document.getElementById('description_for');
		// alert(contents.value);
		if (contents != null) {
			contents = contents.value;
		} else {
			contents = null;
		}

		document.getElementById('product_dec' + rowNumber).value = contents;


	}

	// const taxCalculate=(rowcolumn)=>
	// {
	// 	var tax = $("#tax" + rowNumber).val();
	// 	var amount = $("#amount" + rowNumber).val();

	// 	let taxam = (amount * tax) / 100;
	// 	calculatePrice(rowNumber, type);

	// calculateGST(tax, amount, rowNo);
	// manipulateRHSData();

	// }

	function calculateGST(tax, amount, rowNo) {
		var customerId = $("#party_id").val();
		var customerName = $("#customerName").val();
		if (customerId == "" || customerId == null) {
			if (customerName == "" || customerName == null) {
				Swal.fire({
					position: "top-end",
					icon: "warning",
					title: "Please select customer.",
					showConfirmButton: false,
					timer: 2000,
				});
				$("#party_id").focus().trigger("change");
				$("#product" + rowNo).val("");
				$("#product" + rowNo).select2({
					selectONclose: true,
				});
				$("#productId" + rowNo).val("");
				$("#itemCode" + rowNo).val("");
				$("#hsnCode" + rowNo).val("");
				$("#tax" + rowNo).val("0.00");
				$("#stock" + rowNo).val("");
				$("#rate" + rowNo).val("0.00");
				$("#qty" + rowNo).val("");
				$("#price" + rowNo).val("0.00");
				$("#discount" + rowNo).val("0");
				$("#cost" + rowNo).val("0.00");
				$("#taxAmount" + rowNo).val("0.00");
				$("#netAmount" + rowNo).val("0.00");
				return false;
			}
		}
		sgstPer = 0;
		cgstPer = 0;
		igstPer = 0;
		if (stateCode == <?= $this->session->userdata('state'); ?>) {
			sgstPer = tax / 2;
			cgstPer = tax / 2;
		} else {
			igstPer = tax;
		}
		sgst = calculateTaxAmount(amount, sgstPer);
		cgst = calculateTaxAmount(amount, cgstPer);
		igst = calculateTaxAmount(amount, igstPer);
		$("#sgst" + rowNo).val(parseFloat(sgst).toFixed(2));
		$("#cgst" + rowNo).val(parseFloat(cgst).toFixed(2));
		$("#igst" + rowNo).val(parseFloat(igst).toFixed(2));
	}

	const onNewRowAdd = (e) => {
		if (e.keyCode === 13) {
			onAddNewRowHandler();
		}
	};


	const onAddNewRowHandler = () => {
		getProducts();

	}


	function IsNumeric(evt) {
		evt = evt ? evt : window.event;
		var charCode = evt.which ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
	const calculateRate = (numRow, type) => {
		var rate = $("#rate" + numRow).val();
		var tax = $("#tax" + numRow).val();
		//(rate, "before calcualting Rate");
		//(tax, "before calculating Tax");
		if (type == "Purchase") {
			return rate;
		} else {
			return rate - ((rate * tax) / (100 + parseInt(tax)));
		}
	}
	const calculateRate1 = (numRow, type) => {
		var rate = $("#price" + numRow).val();
		var tax = $("#tax" + numRow).val();
		if (type == "Purchase") {
			return rate;
		} else {
			return ((rate * tax) / (100 + parseInt(tax)));
		}
	}
	const showPop = (rowNumber, e) => {
		jQuery.noConflict();
		var des = document.getElementById('product_dec' + rowNumber);
		if (des != null) {
			des = des.value;
		} else {
			des = "";
		}
		//(des,"des");
		// alert(des);
		// if (des == "") {

		// }

		document.getElementById('description_for_id').value = rowNumber;
		document.getElementById('description_for').value = des;
		$('#mynewmodal').modal('show');
	}
	const onQuantityChangeHandler = (e, rowNumber, type = "Sale") => {
		const qty = e.target.value;
		//(qty);
		var cd = $("#cd" + rowNumber).val();
		var purchasre = $("#rate" + rowNumber).val();

		discount = $("#discount" + rowNumber).val();
		// discount_type = $("#discounttype" + rowNumber).val();
		// discountcd_type = $("#discounttypecd" + rowNumber).val();
		var taxcal = $("#taxcal").val();
		// if(taxcal=="Exclusive")
		// {
		let rate = calculateRate(rowNumber, type);
		// }else
		// {
		// 	let rate = calculateRate(rowNumber, type);

		// }
		rate = (rate * (100 - discount)) / 100;

		rate = (rate * (100 - cd)) / 100;

		rate = rate * qty;
		//(rate, "Rate");
		let amount = purchasre;
		$("#price" + rowNumber).val(rate);


		// let netAmount = amount * qty;

		//(amount);
		// $("#amount" + rowNumber).val(rate);

		//here #price is taxable amount=>rate*qty
		//#amount is taxamount=>taxableamount*taxrate/100
		//netamont=>taxableamont+tax amount;

		const taxPercent = $("#tax" + rowNumber).val();
		const taxAmount = calculateTaxAmount(rate, taxPercent);
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		// const taxAmount1 = calculateTaxAmount1(rate, taxPercent);

		$("#amount" + rowNumber).val(taxAmount.toFixed(2));
		//(taxAmount, "Tax Amount");

		// $("#netAmount" + rowNumber).val(netAmount);

		// $("#discount" + rowNumber).trigger("change");
		calculatePrice(rowNumber, type);

		calculateGST(taxPercent, amount, rowNumber);
		manipulateRHSData();
		$("#rate" + rowNumber).trigger("change");

		onRateChangeHandler(rate, rowNumber);

	};

	function ChangeTax(e, rowNumber, type = "Sale") {

		const tax = e;
		//(tax, "taxvalue");
		// e.options[e.selectedIndex].tax;
		// $("#tax"+rowNumber :selected).val(tax); 
		// var code = $(this).find(':selected').data(tax);
		$('#tax' + rowNumber).val(tax);
		// $("#tax"+rowNumber :selected).val(tax); 

		var cd = $("#cd" + rowNumber).val();
		var qty = $("#qty" + rowNumber).val();

		var purchasre = $("#rate" + rowNumber).val();

		discount = $("#discount" + rowNumber).val();

		// discount_type = $("#discounttype" + rowNumber).val();
		// discountcd_type = $("#discounttypecd" + rowNumber).val();
		var taxcal = $("#taxcal").val();
		// if(taxcal=="Exclusive")
		// {
		var rate = calculateRate(rowNumber, type);
		//(rate, "Rate19");

		// }else
		// {
		// 	let rate = calculateRate(rowNumber, type);

		// }
		rate = (rate * (100 - discount)) / 100;

		rate = (rate * (100 - cd)) / 100;

		rate = rate * qty;
		//(rate, "Rate1234");
		let amount = purchasre;
		$("#price" + rowNumber).val(rate.toFixed(2));


		// let netAmount = amount * qty;

		//(amount, "amount");
		// $("#amount" + rowNumber).val(rate);

		//here #price is taxable amount=>rate*qty
		//#amount is taxamount=>taxableamount*taxrate/100
		//netamont=>taxableamont+tax amount;

		const taxPercent = $("#tax" + rowNumber).val();
		//(taxPercent, "Teaxtpercentage");
		const taxAmount = calculateTaxAmount(rate, taxPercent);
		//(taxAmount, "TeaxtAmount")
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		// const taxAmount1 = calculateTaxAmount1(rate, taxPercent);

		$("#amount" + rowNumber).val(taxAmount.toFixed(2));
		//(taxAmount, "Tax Amount");

		// $("#netAmount" + rowNumber).val(netAmount);

		// $("#discount" + rowNumber).trigger("change");
		calculatePrice(rowNumber, type);

		calculateGST(taxPercent, amount, rowNumber);
		manipulateRHSData();
		$("#rate" + rowNumber).trigger("change");

		onRateChangeHandler(rate, rowNumber);

	};


	function onPriceChangeHandler() {
		return;
	}
	const onDiscountHandler = (e, rowNumber, type = "Sale") => {
		let discount = e.target.value;
		var cd = $("#cd" + rowNumber).val();
		let qty = $("#qty" + rowNumber).val();
		let rate = calculateRate(rowNumber, type);
		//(rate, "discount_rate");
		const taxPercent = $("#tax" + rowNumber).val();
		// discount_type = $("#discounttype" + rowNumber).val();
		// discountcd_type = $("#discounttypecd" + rowNumber).val();
		let discountAmt = "";
		// if (discount_type == "Rs") {

		if (discount > 100) {
			Swal.fire({
				position: "top-end",
				title: "Invalid Discount",
				text: "Discount can't be greater than 100%",
				icon: "warning",
				showConfirmButton: false,
				timer: 2000,
			});
			discount = 0;
			$("#discount" + rowNumber).val("0");
			$("#discount" + rowNumber).trigger("change");
		}
		discountAmt = (rate * (100 - discount)) / 100;

		discountAmt = (discountAmt * (100 - cd)) / 100;

		let amount = discountAmt * qty;
		const taxAmount = calculateTaxAmount(amount, taxPercent);
		// $("#amount" + rowNumber).val(amount.toFixed(2));
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		$("#amount" + rowNumber).val(taxAmount.toFixed(2));

		$("#netAmount" + rowNumber).val(
			(parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
		);
		calculateGST(taxPercent, amount, rowNumber);
		calculatePrice(rowNumber, type);
		manipulateRHSData();
		$("#expenses").val(0);
	};

	const calculatePrice = (rowNumber, type) => {
		var tax = $("#tax" + rowNumber).val();
		var cd = $("#cd" + rowNumber).val();
		var val = 0;
		//   discount_type = $("#discounttype" + rowNumber).val();
		//   discountcd_type = $("#discounttypecd" + rowNumber).val();
		discount = $("#discount" + rowNumber).val();
		var qty = $("#qty" + rowNumber).val();

		// amount = (amount * (100 - amount)) / 100;

		// $("#amount" + rowNumber).val(amount);
		var rate = $("#rate" + rowNumber).val();

		var taxcal = $("#taxcal").val();
		var actual_rate = $("#rate" + rowNumber).val();


		//   if (discount_type == "%") {
		rate = (rate * (100 - discount)) / 100;
		//   } else if (discount_type == "Rs") {
		//     rate = rate - discount;
		//   }
		//   if (discountcd_type == "%") {
		rate = (rate * (100 - cd)) / 100;
		//   } else if (discountcd_type == "Rs") {
		//     rate = rate - cd;
		//   }
		var price = "";
		if (tax == "" || tax == null || tax == 0) {
			price = rate;
		} else {
			price = ((100 + parseFloat(tax)) * rate) / 100;
		}


		//(rate, "price");



		if (taxcal == 1) {
			rate = rate - ((rate * tax) / (100 + parseInt(tax)));

			// var rate = calculateRate(rowNumber, type);

			rate = rate * qty;
			$("#price" + rowNumber).val(rate.toFixed(2));
			//(rate, "price1234");
			// var tx = $("#totalTax" + rowNumber).val();
		} else {
			actual_rate = rate * qty;
			$("#price" + rowNumber).val(actual_rate);
			price = $("#price" + rowNumber).val();
			//(price, "price4321");

			// var tx = $("#totalTax" + rowNumber).val();
		}

		var price = $("#price" + rowNumber).val();
		var amount12 = $("#amount" + rowNumber).val();
		var natamount = parseFloat(amount12) + parseFloat(price);
		$("#netAmount" + rowNumber).val(natamount.toFixed(2));
		//(natamount, "NetAmount");



	};

	const onCDHandler = (e, rowNumber, type = "Sale") => {
		let cd = e.target.value;
		let discount = $("#discount" + rowNumber).val();
		var taxPercent = $("#tax" + rowNumber).val();
		let qty = $("#qty" + rowNumber).val();
		let rate = calculateRate(rowNumber, type);
		// discount_type = $("#discounttype" + rowNumber).val();
		// discountcd_type = $("#discounttypecd" + rowNumber).val();
		// if (discount_type == "%") {
		rate = (rate * (100 - discount)) / 100;
		// } else if (discount_type == "Rs") {
		// 	rate = rate - discount;
		// }
		// if (discountcd_type == "%") {
		if (cd > 100) {
			Swal.fire({
				position: "top-end",
				title: "Invalid CD",
				text: "CD can't be greater than 100%",
				icon: "warning",
				confirmButtonColor: "#3085d6",
				showConfirmButton: false,
				timer: 2000,
			});
			cd = 0;
			$("#cd" + rowNumber).val("0");
			$("#cd" + rowNumber).trigger("change");
		}



		// if (discountcd_type == "%") {
		rate = (rate * (100 - cd)) / 100;
		// } else if (discountcd_type == "Rs") {
		// 	rate = rate - cd;
		// }
		amount = qty * rate;
		const taxAmount = calculateTaxAmount(amount, taxPercent);
		$("#amount" + rowNumber).val(amount.toFixed(2));
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		$("#netAmount" + rowNumber).val(
			(parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
		);
		calculateGST(taxPercent, amount, rowNumber);
		calculatePrice(rowNumber, type);
		manipulateRHSData();
		$("#expenses").val(0);
	};

	const onRateChangeHandler = (e, rowNumber, type = 'Sale') => {
		// var rate = e.target.value;
		var rate = $("#rate" + rowNumber).val();

		var cd = $("#cd" + rowNumber).val();
		const taxPercent = $("#tax" + rowNumber).val();
		var netamount = rate * $("#qty" + rowNumber).val();
		$("#price" + rowNumber).val(rate);

		//   discount_type = $("#discounttype" + rowNumber).val();
		//   discountcd_type = $("#discounttypecd" + rowNumber).val();

		// rate = rate - ((rate * taxPercent) / (100 + parseInt(taxPercent)));
		// rate=calculateRate();
		rate = calculateRate(rowNumber, type);

		//   $("#base_price" + rowNumber).val(rate);
		discount = $("#discount" + rowNumber).val();
		//   if (discount_type == "%") {
		rate = (rate * (100 - discount)) / 100;
		//   } else if (discount_type == "Rs") {
		// rate = rate - discount;
		//   }
		//   if (discountcd_type == "%") {
		rate = (rate * (100 - cd)) / 100;
		//   } else if (discountcd_type == "Rs") {
		//     rate = rate - cd;
		//   }
		amount = rate * $("#qty" + rowNumber).val();
		// $("#amount" + rowNumber).val(amount);
		const taxAmount = calculateTaxAmount(amount, taxPercent);
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		$("#amount" + rowNumber).val(taxAmount.toFixed(2));
		// $("#netAmount" + rowNumber).val(
		// 	(parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
		// );
		// $("#discount" + rowNumber).trigger("change");
		$("#expenses").val(0);
		calculatePrice(rowNumber, type);
		calculateGST(taxPercent, amount, rowNumber);
		manipulateRHSData();

	};

	const a = [
		"",
		"One ",
		"Two ",
		"Three ",
		"Four ",
		"Five ",
		"Six ",
		"Seven ",
		"Eight ",
		"Nine ",
		"Ten ",
		"Eleven ",
		"Twelve ",
		"Thirteen ",
		"Fourteen ",
		"Fifteen ",
		"Sixteen ",
		"Seventeen ",
		"Eighteen ",
		"Nineteen ",
	];
	const b = [
		"",
		"",
		"Twenty",
		"Thirty",
		"Forty",
		"Fifty",
		"Sixty",
		"Seventy",
		"Eighty",
		"Ninety",
	];
	const numberToWords = (num) => {
		if ((num = num.toString()).length > 9) return "overflow";
		let n = ("000000000" + num)
			.substr(-9)
			.match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
		if (!n) return;
		var str = "";
		str +=
			n[1] != 0 ?
			(a[Number(n[1])] || b[n[1][0]] + " " + a[n[1][1]]) + "Crore " :
			"";
		str +=
			n[2] != 0 ?
			(a[Number(n[2])] || b[n[2][0]] + " " + a[n[2][1]]) + "Lakh " :
			"";
		str +=
			n[3] != 0 ?
			(a[Number(n[3])] || b[n[3][0]] + " " + a[n[3][1]]) + "Thousand " :
			"";
		str +=
			n[4] != 0 ?
			(a[Number(n[4])] || b[n[4][0]] + " " + a[n[4][1]]) + "Hundred " :
			"";
		str +=
			n[5] != 0 ?
			(str != "" ? "And " : "") +
			(a[Number(n[5])] || b[n[5][0]] + " " + a[n[5][1]]) +
			"Only " :
			"";
		return str;
	};

	const onRemoveRowHandler = (rowNo) => {

		// const invoiceProductId = $("#invoiceProductId" + rowNo).val();
		// const invoiceId = $("#id").val();
		// if (invoiceId != null && invoiceProductId != null) {
		// 	deleteInvoiceProduct(invoiceProductId, invoiceId);
		// }
		$("#tr" + rowNo).remove();
		let rowCount = $("#dataTable tbody tr").length + 1;
		//(rowCount, "rowcount");
		if (rowCount == 1) {
			onAddNewRowHandler();
		} else {
			document.getElementById("expenses").focus();
		}
		manipulateRHSData();
	};
	const manipulateRHSData = () => {
		let taxableAmount = 0;
		let totalTax = 0;
		let totalValue = 0;
		let roundOff = 0;
		let total = 0;
		let totalsgst = 0;
		let totalcgst = 0;
		let totaligst = 0;
		const rows = $("#dataTable").children("tbody").children("tr");
		rows.map(function(row) {
			let row1 = $("#dataTable").children("tbody").children("tr")[row];
			const trId = row1.getAttribute("id");
			const rowNumber = trId.slice(2, trId.length);
			const taxAmount = $("#taxAmount" + rowNumber).val();
			const netAmount = $("#netAmount" + rowNumber).val();
			const sgst = $("#sgst" + rowNumber).val();
			const cgst = $("#cgst" + rowNumber).val();
			const igst = $("#igst" + rowNumber).val();
			const amount = $("#amount" + rowNumber).val();
			const price = $("#price" + rowNumber).val();

			//(taxAmount, 'taxAmount')

			if (taxAmount != null && taxAmount != "") {
				taxableAmount += parseFloat(price);
				totalTax += parseFloat(amount);
				totalValue += parseFloat(netAmount);
			}
			totalsgst += parseFloat(sgst);
			totalcgst += parseFloat(cgst);
			totaligst += parseFloat(igst);
		});
		//(totalTax, 'totaltAx')
		//(taxableAmount, 'taxableAmount')
		//(totalValue, 'totalValue')

		roundOff += parseFloat("." + totalValue.toString().split(".")[1]);
		$("#taxableAmount").val(taxableAmount.toFixed(2));
		$("#totalTax").val(totalTax.toFixed(2));
		$("#amount").val(totalTax.toFixed(2));

		$("#totalValue").val(totalValue.toFixed(2));
		$("#roundOff").val(roundOff == "" || isNaN(roundOff) ? 0 : roundOff.toFixed(2));
		$("#total").val(Math.round(totalValue).toFixed(2));
		$("#amountInWords").val(numberToWords(parseInt(totalValue)));
		$("#totalsgst").val(totalsgst.toFixed(2));
		$("#totalcgst").val(totalcgst.toFixed(2));
		$("#totaligst").val(totaligst.toFixed(2));
		//(taxableAmount, "texableAmount");

	};

	const getTaxData = (tax_id, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: tax_id,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>tax/gettax",

			success: function(invoiceNo) {

				$("#taxal" + rowNum).html(invoiceNo);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);


			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}
	const getProductName = (pro_id, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: pro_id,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>product/pro_name",

			success: function(invoiceNo) {

				$("#pro_names" + rowNum).html(invoiceNo);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);




			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}
	const onFormSubmitHandler = (e) => {
		e.preventDefault();
		if ($("#invoiceNumber").val() != null && $("#invoiceNumber").length < 1) {
			Swal.fire({
				type: 'fail',
				title: "Invoice Number is required",
			});
			$("#btnSubmit").prop("disabled", false);
			return false;
		}
		let buttons = '<div class="mb-3"><span></span></div><div class="mb-2"><button id="btnSave" class="btn btn-primary mr-2" onclick="saveInvoice(1)">Save</button>'
		buttons += '<button id="btnSaveCancel" class="btn btn-default mr-2" onclick="swal.close()">Cancel</button></div>'
		Swal.fire({
			title: 'Are you sure to Save Invoice ?',
			text: "This will create an invoice.",
			html: buttons,
			showConfirmButton: false,
			showCancelButton: false,
			confirmButtonColor: '#009a1a',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, save it!'
		})

	}
	const saveInvoice = (saveAction) => {
		//console.info($("#dataForm").serialize())
		$("#btnSubmit").prop("disabled", true);
		$("#saveAction").val(saveAction)
		$.ajax({
			type: "POST",
			url: "<?= base_url() ?>sale/saleUpdate",
			data: $("#dataForm").serialize(),
			success: function(invoiceNo) {
				invoiceNo = JSON.parse(invoiceNo);
				if (invoiceNo.status == true) {

					invoiceData = invoiceNo.data;
					// cosnole.log(invoiceData);
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: "Invoice saved successfully.",
						showConfirmButton: false,
						timer: 2000
					})
					if (saveAction == 1) { //Save Only
						setTimeout(function() {
							//window.location.reload()
							window.location.href = '<?= base_url() ?>invent/sale'
							// window.location.replace('index.php/invent/purchase')
						}, 2000)
					} else if (saveAction == 2) { //Save & Print
						setTimeout(function() {
							// $("#invoiceNumber").val(invoiceData)
							redirectToPrint(invoiceData)
						}, 2000)
					}
				} else {
					Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: invoiceNo.msg,
						showConfirmButton: false,
						timer: 2000
					})
				}
				$("#btnSubmit").prop("disabled", false);

			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}

	// const updateInvoice = () => {
	// 	//console.info($("#dataForm").serialize())
	// 	var printValue = document.getElementById('printSale').value;
	// 	var invoiceNumber = document.getElementById('invoiceNumber').value;

	// 	if (printValue == '') {
	// 		alert('ENter First Invocie Number That you Want to Update');
	// 		return;
	// 	}
	// 	// $("#updateInvoice").prop("disabled", true);
	// 	// $("#saveAction").val(saveAction)
	// 	var url = "<?= base_url() ?>sale/saleUpdate";

	// 	//(url, "url");
	// 	Swal.fire({
	// 		title: "Are you sure to Update?",
	// 		text: "You won't be able to revert this!",
	// 		icon: "success",
	// 		showCancelButton: true,
	// 		confirmButtonColor: "success",
	// 		cancelButtonColor: "#d33",
	// 		confirmButtonText: "Yes, Update it!",
	// 	}).then((result) => {
	// 		if (result.isConfirmed) {
	// 			updateInvoiceMail(url);
	// 		} else {
	// 			Swal.fire({
	// 				position: "top-end",
	// 				icon: "info",
	// 				title: "Cancelled",
	// 				showConfirmButton: false,
	// 				timer: 2000,
	// 			});
	// 		}
	// 	});

	// }


	const onProductFocusChange = (e, rowNo) => {
		if (e.target.value.length < 1) {
			const row = document.getElementById("tr" + rowNo);
			row.parentNode.removeChild(row);
			document.getElementById("expenses").focus();
			//Add A Blank Row if table is empty
			let rowCount = $("#dataTable tbody tr").length;
			if (rowCount < 1) {
				onAddNewRowHandler();
				document.getElementById("party_id").focus();
			}
		}
	};
	const redirectToPrint = (invoiceNo) => {
		// const invoiceNo = $("#invoiceNumber").val()
		// window.location.href = '<?= base_url(); ?>'+invoiceNo+''
		window.location.href = '<?= base_url(); ?>index.php/sale/printSale/' + invoiceNo + ''
	}



	function save_serial(currentrowid) {
		var qty = $('#qty' + currentrowid).val();
		$('#product_serial' + currentrowid).html('');
		for (var s = 1; s <= qty; s++) {
			//  alert($('#serial' + s).val());
			var val = $('#serial' + currentrowid + s).val();
			$('#product_serial' + currentrowid).append('<input id="serial' + currentrowid + '' + s + '" type="text" value="' + val + '" class="form-control" name="product[' + currentrowid + '][item_serialno][]" style="margin-bootom:5px;" />');
		}

		// $('#product_serial' + currentrowid).html($('#serialdiv').html());
		$('#serialModal').modal('hide');
		$('#rate' + currentrowid).focus();
	}
</script>
<script>
	function onUserFormSubmitHandler(e) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#addUserForm").attr("action");
		var form = $("#addUserForm")[0];
		var data = new FormData(form);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					// closeModel();
					location.reload();

					alert("party Created");
					document.getElementById("reset").click();

					document.getElementById("custClose").click();

					// $("#party_id").trigger("change");

					// //(customerList.data.id, "id");

					// prepareCustomerDropdown(customerList.data);


				} else {
					$("#partyMsg").show();
					$("#partyMsg").addClass("text");
					$("#partyMsg").addClass("text-danger");
					$("#partyMsg").html(customerList.msg);
					// setTimeout(function() {
					// 	$("#partyMsg").hide();
					// }, 3000);
				}
			},
			error: function(jqXHR, exception) {
				$("#exampleModal").modal("hide");
				$("#loader").addClass("d-none");
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}


	const prepareCustomerDropdown = (customers) => {
		// //(customers);
		// var code = $(this).find(':selected').data('id');
		// $('#code').val(code); <
		// //(customers.id);
		$("#party_id").value(customers.id);

		$('#gstNo').val(customers.gstno);


	};
</script>

<script>
	// $(document).ready(function() {
	$('#gstTypeVal').hide()
	// $('.searchselect').select2({
	// 	dropdownParent: "#exampleModal"
	// });

	$('#state').change(function() {
		var code = $(this).find(':selected').data('id');
		$('#code').val(code);
	});
	// const rowCount = 0;
	// $('#tax' + rowCount).change(function() {
	// 	$('#tax' + rowCount).val(3);

	// });

	$('#gstTypeParty').change(function() {
		if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
			$('#gstTypeVal').show();
			$('#gst').attr('required', true);
		} else {
			$('#gstTypeVal').hide();
			$('#gst').attr('required', false);
		}
	});
	// });

	$('#gst').blur(function() {
		$("#custClose").focus()
	});





	// $("input").on("keypress", function(e) {
	// 	if (e.keyCode == 13) {
	// 		$("#addUserForm").submit();
	// 	}
	// });
	// $("#custClose").on("focus", function(e) {
	// 	$("#custClose").css({
	// 		"box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
	// 	});
	// });
	// $("#custSave").on("focus", function(e) {
	// 	$("#custSave").css({
	// 		"box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
	// 	});
	// });
	// $("#custClose").on("blur", function(e) {
	// 	$("#custClose").css({
	// 		"box-shadow": "none"
	// 	});
	// });
	// $("#custSave").on("blur", function(e) {
	// 	$("#custSave").css({
	// 		"box-shadow": "none"
	// 	});
	// });
</script>
<!-- Serial Modal -->
<!-- <div id="serialModal" class="modal fade" role="dialog">
	<div class="modal-dialog">






		Modal content-->
<!-- <div class="modal-content">
			<div class="modal-header"> -->
<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<!-- <h4 class="modal-title">Enter Serial Numbers</h4>
			</div>
			<div class="modal-body" id="serialdiv">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<div id="serialfooter"></div>
			</div>
		</div>

	</div>
</div> -->
<script>

</script>
<script>
	function printSale() {
		var saleValue = document.getElementById('printSale').value;
		//(saleValue);
		if (saleValue)

			redirectToPrint(saleValue);
	}
</script>
<script>
	function updateInvoiceMail(url) {
		$.ajax({
			type: "POST",
			url: url,
			data: $("#dataForm").serialize(),
			success: function(invoiceNo) {
				invoiceNo = JSON.parse(invoiceNo);
				// //(invoiceNo,"idreturn");
				if (invoiceNo.status == true) {

					invoiceData = invoiceNo.data;
					// cosnole.log(invoiceData);
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: "Invoice Updated successfully.",
						showConfirmButton: false,
						timer: 2000
					})
					setTimeout(function() {
						window.location.reload();
					}, 2000);

				} else {
					Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: invoiceNo.msg,
						showConfirmButton: false,
						timer: 2000
					})
				}
				// $("#updateInvoice").prop("disabled", false);

			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#updateInvoice").prop("disabled", false);
			},
		});
	}
</script>
<script>
	function preview() {
		var preview = document.getElementById('printSale').value;
		if (preview != 0) {
			var preview = parseInt(preview) - parseInt(1);
			//(preview);
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/party/getInvoiceBySale",

				data: {
					id: preview,
				},
				success: function(customer) {

					var customer = JSON.parse(customer);
					var saleData = customer.data;
					//(saleData, "sale data");
					if (saleData.invoiceNumber != null) {
						var elmtTable = document.getElementById('dataTable');
						var tableRows = elmtTable.getElementsByTagName('tr');
						var rowCount = tableRows.length;
						// //(rowCount);
						for (var x = rowCount; x > 0; x--) {
							//    elmtTable.remove(tableRows[x]);
							// onRemoveRowHandler(tableRows[x])
							$("#tr" + x).remove();

						}
						document.getElementById('invoiceNumber').value = saleData.invoiceNumber;
						$("#invoiceNumber").trigger("change");

						getInvoiceInfo(saleData.invoiceNumber);

					}
					// var da = customer.data;
				}
			});

		}

	}
</script>
<script>
	function next() {
		var preview = document.getElementById('printSale').value;
		if (preview != 0) {
			var preview = parseInt(preview) + parseInt(1);
			//(preview, "next id");
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/party/getInvoiceBySale",

				data: {
					id: preview,
				},
				success: function(customer) {

					var customer = JSON.parse(customer);
					var saleData = customer.data;
					//(saleData, "sale data");
					if (saleData.invoiceNumber != null) {
						var elmtTable = document.getElementById('dataTable');
						var tableRows = elmtTable.getElementsByTagName('tr');
						var rowCount = tableRows.length;
						// //(rowCount);
						for (var x = rowCount; x > 0; x--) {
							//    elmtTable.remove(tableRows[x]);
							// onRemoveRowHandler(tableRows[x])
							$("#tr" + x).remove();

						}
						document.getElementById('invoiceNumber').value = saleData.invoiceNumber;
						$("#invoiceNumber").trigger("change");

						getInvoiceInfo(saleData.invoiceNumber);

					} else {
						alert("Did not Find the Data");
					}
					// var da = customer.data;
				}
			});

		}

	}

	function productForEdit(rowNumber, productId) {
		jQuery.noConflict();
		document.getElementById('rownumber').value = rowNumber;
		$("#exampleModals .modal-dialog .modal-body").load(baseurl + "index.php/product/productpop/" + productId);
		$("#exampleModalLabels").html("Update Product");
		$("#exampleModals").modal("show");
		// $(".searchselect").select2({
		// 	dropdownParent: "#exampleModal"
		// });
	}
</script>
<script>
	jQuery(document).bind("keyup keydown", function(e) {
		var x = event.which || event.keyCode;
		// if (e.ctrlKey && e.keyCode == 100) {
		if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 117 || e.keycode === 17 || e.keycode === 85)) { //ctrl+u Alt+c, Alt+v will also be disabled sadly.

			// printSale();
			// deleteRow();
			// updateInvoice();
			onFormSubmitHandler(e);


			return false;
		}
	});
	var inputs = document.querySelectorAll("input,select");
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].addEventListener("keypress", function(e) {
			if (e.which == 13) {
				e.preventDefault();
				var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
				if (nextInput.length === 0) {
					nextInput = document.querySelectorAll('[tabIndex="1"]');
				}
				//  alert(e.which)
				nextInput[0].focus();
			}
		})
	}
</script>