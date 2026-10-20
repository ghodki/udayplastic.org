<style>
	#progress {
		position: relative;
		width: 400px;
		border: 1px solid #ddd;
		padding: 1px;
		border-radius: 3px;
	}

	#bar {
		background-color: #B4F5B4;
		width: 0%;
		height: 10px;
		border-radius: 3px;
		padding-bottom: 10px;
	}

	#percent {
		position: absolute;
		top: 3px;
		left: 48%;
	}

	.form-control {
		border: none;
		border-bottom: 1px solid black;
	}
</style>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">

		<div class="container-fluid" style="margin-top:3.5rem!important;">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Products</div>
					<?php
					if ($this->session->flashdata('msg')) { ?>
						<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
							<?php echo $this->session->flashdata('msg'); ?>
						</div>
					<?php } ?>
					<div class="float-right">
						<button class="btn btn-outline-success" title="Change Sales Tax" id="change-sales-tax"><i class="fa fa-money" aria-hidden="true"></i></button>
						<button class="btn btn-outline-danger" title="Delete" id="delete-product"><i class="fa fa-trash fa-lg"></i></button>
						<button class="btn btn-outline-info" title="Upload" id="upload-product" data-toggle="modal" data-target="#importModal"><i class="fa fa-upload fa-lg"></i></button>
						<button class="btn btn-outline-info" title="Export" id="export-button"><i class="fa fa-export fa-lg"></i></button>
						<button class="btn btn-outline-primary" title="Copy" id="copy-product"><i class="fa fa-clone fa-lg"></i></button>
						<button class="btn btn-outline-dark" title="Barcode" id="print-barcode"><i class="fa fa-barcode fa-lg"></i></button>
						<button class="btn btn-outline-success" title="Add" data-toggle="modal" data-target="#add-product"><i class="fa fa-plus fa-lg"></i></button>
						<!-- IMport FIle -->

						<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">

							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Upload Excel Data</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<form id="import-frm" method="POST" action="<?= base_url() ?>product/importfile" enctype="multipart/form-data" novalidate="novalidate">
										<div class="modal-body">
											<a href="<?= base_url() ?>sample/demo.xlsx" class="float-right" download><i class="fa fa-download"></i> Download Template</a>
											<div class="input-field">
												<div class="file-field">
													<div class="btn" style="margin-top: -15px;">
														<span>Upload File</span>
														<input data-error=".logo" required type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
													</div>

													<div class="logo"></div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-sm  btn-secondary" data-dismiss="modal"><span aria-hidden="true">×</span> Close</button>
											<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-upload"></i> Import</button>
											<p>Note:<span class="text-danger">1.If the name of the product is same that will not be save and taxs and units are mendetory that should be match from given taxes and units.</span>
												<br>
												<span class="text-danger" style="text-align:left;">2.maximum product can be uploaded in single time only 4000.</span>
											</p>
										</div>

									</form>
								</div>
							</div>
						</div>

						<!-- IMport FIle  END-->

						<!-- Add Product -->
						<div class="modal fade" id="add-product" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="productModalLabel">Add Product</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<!-- Card stats -->
										<div class="row">
											<div class="" id="productMsg" style="opacity: 500; display: none;"></div>

											<form id="onProductFormSubmitHandler" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/savePro" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event)">
												<div class="row">
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="product_name">Product Name<span class="text-danger">*</span></label>
															<input id="product_name" name="name" tabindex="1" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
														</div>
														<span class="col-sm-4 control-label">
															<?php echo form_error('name'); ?>

														</span>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="product_code">Product Code<span class="text-danger">*</span></label>

															<!-- <select id="p_code" name="p_code" class="form-control">
													 <option value="">Select Product Code</option> -->
															<?php
															$productcodes = 0;
															$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
															$productCode = $this->user_model->getRow('productcode', $where);
															if ($productCode) {
																$prefix_product_code = $productCode->prefix_product_code;
																$productcodes = $productCode->productcode;
																$productcodes = $prefix_product_code . "" . $productcodes;


															?>
																<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productcodes ?>">


															<?php
															} else {
																$user_id = $this->session->userdata('user_id');
																$comp_id = $this->session->userdata('comp_id');
																$where = ['comp_id' => $comp_id, 'user_id' => $user_id,];
																// $this->db->select_max('order_product_code');
																// $this->db->where($where);
																// $query = $this->db->get('product_code_for_product_only')->row();
																$query = $this->user_model->getRow('product_code_for_product_only', $where);
																if ($query) {
																	$inital = $query->order_product_code;
																	if (!is_numeric($inital)) {
																		$inital = 0;
																	}
																} else {
																	$inital = 0;
																}
															?>
																<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $inital + 1 ?>">

															<?php
															}



															?>

															<span class="col-sm-4 control-label">
																<?php echo form_error('p_code'); ?>

															</span>
														</div>
													</div>

													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="price">Selling Price<span class="text-danger">*</span></label>

															<input id="price" name="price" tabindex="3" min="1" placeholder="Selling Price" type="number" class="form-control" value="<?= set_value('price') ?>">
														</div>
														<span class="col-sm-4 control-label">
															<?php echo form_error('price'); ?>

														</span>
													</div>

													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="cost">Purchase Price<span class="text-danger"></span></label>

															<input id="cost" name="sale_rate" tabindex="4" min="0" placeholder="Purchase Price" type="number" class="form-control" value="<?= set_value('sale_rate') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="mrp">MRP<span class="text-danger"></span></label>

															<input id="mrp" name="mrp" min="0" tabindex="5" placeholder="MRP" type="number" class="form-control" value="<?= set_value('mrp') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="qty">Product Qunatity<span class="text-danger">*</span></label>

															<input id="qty" name="qty" min="0" tabindex="6" placeholder="Product Qunatity" type="text" class="form-control" value="<?= set_value('qty') ?>">
														</div>
													</div>
													
																						<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="segement">Segement<span class="text-danger"></span></label>

															<input id="segement" name="segement"  placeholder="Segement" type="text" class="form-control" value="<?= set_value('segement') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="discount">Discount%<span class="text-danger"></span></label>

															<input id="discount" name="discount" min="0" tabindex="7" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="hsn">HSN CODE<span class="text-danger"></span></label>

															<input id="hsn_code" name="hsn_code" tabindex="8" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="min_qty">MIN QTY<span class="text-danger"></span></label>

															<input id="min_qty" name="min_qty" min="0" tabindex="9" placeholder="Min Qty" type="number" class="form-control" value="<?= set_value('min_qty') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<label for="weight">Weight<span class="text-danger"></span></label>

															<input id="weight" name="weight" min="0" tabindex="10" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<label for="unit">Unit<span class="text-danger">*</span></label>

														<select id="unit" name="unit" class="form-control" tabindex="11" required>
															<option value="">Select Unit</option>
															<?php
															$unit = $this->user_model->getResult('unit');
															$role = $this->session->userdata('roles');
															if ($role == 1) {
																if (isset($unit) && !empty($unit)) {
																	foreach ($unit as $units) {
															?>
																		<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
																		</option>

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
																				<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
																				</option>

																			<?php
																			}
																		} else {
																			?>
																			<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
																			</option>

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
															<label for="tax">TAX<span class="text-danger">*</span></label>

															<select id="tax" name="tax" class="form-control" tabindex="12" required>
																<option value="">Select Tax</option>
																<?php
																$tax = $this->user_model->getResult('tax');

																if ($role == 1) {
																	if (isset($tax) && !empty($tax)) {
																		foreach ($tax as $units) {
																?>
																			<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
																				<?= $units->name; ?></option>

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
																					<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
																						<?= $units->name; ?></option>

																				<?php
																				}
																			} else {
																				?>
																				<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
																					<?= $units->name; ?></option>

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


													<!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3 form-group">
								<div class="custom-file"> -->
													<input id="customFile" name="image" type="file" class="custom-file-input" hidden value="<?= set_value('image') ?>">
													<!-- <label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div> -->

													<div class="col-md-6 col-sm-6 col-lg-6">
														<div class="form-group">
															<label for="shortdescription">SHORT DESC<span class="text-danger"></span></label>

															<textarea id="shortdescription" tabindex="13" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
														</div>
													</div>
													<div class="col-md-6 col-sm-6 col-lg-6">
														<div class="form-group">
															<label for="description">LONG DESC<span class="text-danger">*</span></label>

															<textarea id="description" tabindex="14" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= set_value('long_des') ?></textarea>
														</div>
													</div>

												</div>


												<div class="row mt-5">
													<div class="col-md-12 text-center">
														<button type="submit" class="btn btn-primary">
															SAVE
														</button>
														<button type="reset" id="productResetBtn" class="btn btn-danger">RESET</button>

													</div>
												</div>
											</form>
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
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">

									</div>
								</div>
							</div>
						</div>

						<div class="modal fade" id="productStock" tabindex="-1" role="dialog" aria-labelledby="productStockLabels" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="productStockLabels"></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">

									</div>
								</div>
							</div>
						</div>

						<!-- Add Product END-->
						<!-- UPDATE PRODUCT -->

						<div class="modal fade" id="barcode_list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
							<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabels"></h5>
										<input type="hidden" id="rownumber">
										<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<form method="post" action="<?= base_url() ?>product/barcodegenerate">
											<table class="table table-striped table-bordered table-responsive-sm bg-dark">
												<thead>
													<?php
													$condition = ['comp_id' => $this->session->comp_id, 'user_id' => $this->session->user_id];

													// $barcode_pre_row = get_title('comp_profile', ['comp_id' => $this->session->userdata('comp_id')], ['barcode_pre_row']);
													$barcode_pre_row = get_title('comp_profile', ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')], 'barcode_pre_row');

													?>
													<input type="number" min="1" max="4" width="100px;" required placeholder="Number Of Column" name="input_number" id="input_number" value="<?= $barcode_pre_row ?>"><span class="text-danger">(Till 4 Column)</span>
													<!-- <br> -->
													<button class="btn btn-primary" type="submit">Print</button>
													<br>
													<br>

													<tr>
														<th>Name</th>
														<th>No Fo Copies</th>
													</tr>
												</thead>
												<tbody id="dynamic_data">
												</tbody>
											</table>
										</form>

									</div>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="card-body card">
					<form action="<?= base_url() ?>product/product_report" id="reportForm" method="get">
						<div class="row">
							<div class="form-group col-md-2 col-sm-6">
								<label for="product_name">Product Name</label>
								<input type="text" tabindex="2" name="product_name" tabindex="999" id="product_name" value="<?= $this->input->get('product_name') ? $this->input->get('product_name') : '' ?>" class="form-control" />

							</div>
							<div class="form-group col-md-2 col-sm-6">
								<label for="product_code">Product Code</label>
								<input type="text" tabindex="2" name="product_code" tabindex="1000" id="product_code" value="<?= $this->input->get('product_code') ? $this->input->get('product_code') : '' ?>" class="form-control" />

							</div>
							<div class="form-group col-md-2 col-sm-6">
								<label for="hsn_code">HSN Code</label>
								<input type="text" tabindex="2" name="hsn_code" tabindex="1001" id="hsn_code" value="<?= $this->input->get('hsn_code') ? $this->input->get('hsn_code') : '' ?>" class="form-control" />

							</div>
							<div class="form-group col-md-2 col-sm-6">
								<label>TAX</label>
								<select tabindex="1002" name="taxas" id="taxas" style="width:100%" class="form-control">
									<option value="">Select</option>
									<?php
									$tax = $this->user_model->getResult('tax');
									if ($tax) {
										foreach ($tax as $taxdata) {
									?>
											<option value="<?= $taxdata->id ?>" <?= $this->input->get('taxas') == "<?= $taxdata->id ?>" ? 'selected' : '' ?>><?= $taxdata->name ?></option>

									<?php
										}
									}
									?>


								</select>
							</div>
							<div class="form-group col-md-2 col-sm-6">
								<label>UNIT</label>
								<select tabindex="1003" name="units" id="units" style="width:100%" class="form-control">
									<option value="">Select</option>
									<?php
									$unit = $this->user_model->getResult('unit');
									if ($unit) {
										foreach ($unit as $unitdata) {
									?>
											<option value="<?= $unitdata->id ?>" <?= $this->input->get('units') == "<?= $unitdata->id ?>" ? 'selected' : '' ?>><?= $unitdata->name ?></option>


									<?php
										}
									}
									?>

								</select>
							</div>
							<div class="form-group col-md-2 col-sm-6" style="margin-top:31px">

								<button type="submit" class="btn btn-primary"> Search</button> &nbsp
								<i class="fa fa-refresh" title="Refresh" id="resetbtn" aria-hidden="true"></i>

							</div>



						</div>
					</form>

					<div class="row">
						<div class="col-md-6">
							<?php if ($totalCount) { ?>
								<p>Showing <?= $start ? $start : 1; ?> to <?= $end ?> of <?= $totalCount ?> entries</p>
							<?php } else { ?>
								<p>No result found.</p>
							<?php } ?>
						</div>
						<div class="col-md-1">
						</div>
						<div class="col-md-5">
							<?= $this->pagination->create_links(); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">

							<table id="product-table" class="table table-bordered table-hover table-striped dataTable dtr-column text-center" cellspacing="0" width="100%" aria-describedby="product-table_info" role="grid">
								<thead>
									<tr role="row">
										<th rowspan="1" colspan="1" aria-label=""><input type="checkbox" id="product-selectAll"><label for="product-selectAll"></label></th>
										<!--<th>Sr No</th>-->
										<th>Product Code</th>
										<th>Product</th>
										<th>HSN</th>
										<th>TAX</th>
										<th>UNIT</th>
										<th>Selling Price</th>
										<th>Available Stock</th>
										<th>...</th>
									</tr>
								</thead>

								<tbody>
									<?php
									if (isset($product) && !empty($product)) {
										foreach ($product as  $key => $value) {
											$date = $value->created_at;
											$newDate = date("d-m-Y", strtotime($date));
									?>

											<tr>

												<td class="cheking">
													<input class="checkdata" type="checkbox" name="product_id[]" value="<?php echo $value->id; ?>" ?>

													<!-- <span><?= $key + 1; ?></span> -->
												</td>
												<!--<td><?= $key + 1; ?></td>-->
												<td><?= $value->qrCode ?></td>
												<td><?= $value->p_name ?></td>
												<td><?= $value->hsn ?></td>
												<td><?= $value->tax_qty ?>%</td>
												<td><?= $value->unit_name ?></td>

												<td><?= $value->price ?></td>
												<td><?= $value->qty ?></td>
												<!-- <td><?= $value->mrp ?></td> -->
												<td>
													<!-- <a href="<?= base_url('inventory_billing/viewable/') . $value->id ?>"> <i class="fa fa-eye" aria-hidden="true"></i> </a>| -->
													<a href="#" onclick="productEdit(<?= $value->id ?>)" class="btn text-warning"> <i class="fa fa-edit" aria-hidden="true" style="font-size: x-large;"></i></a>
													<a href="#" onclick="productStock(<?= $value->id ?>)" class="btn text-warning"> <i class="fa fa-shopping-basket blue-text" aria-hidden="true" style="font-size: x-large;"></i></a>

													<!-- <a href="#" data-target="#update_product<?= $value->id ?>" data-toggle="modal" class="btn text-warning"> <i class="fa fa-edit" aria-hidden="true" style="font-size: x-large;"></i></a> -->

													<!-- <a href="<?= base_url('product/store/') . $value->id ?>" class="text-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a> -->


												</td>
											</tr>

									<?php }
									}

									?>




								</tbody>
							</table>

						</div>
					</div>
				</div>


			</div>

		</div>

	</div>
</div>
</div>


<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>


<script>
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
	// Select All
	$("#product-selectAll").click(function() {
		$(".checkdata").prop('checked', $(this).prop('checked'));
	});

	// Delete Selected Value
	$(function() {
		$("#delete-product").click(function() {
			var selected = new Array();
			$(".cheking input[type=checkbox]:checked").each(function() {
				selected.push(this.value);
			});
			if (selected.length > 0) {
				if (!confirm('Are you sure to Deletes the projects?')) return false;
				console.log(selected)
				$.ajax({
					type: "POST",
					url: '<?= base_url("index.php/product/deleteProduct") ?>',
					data: {
						id: selected,
					},
					dataType: 'json',
					success: function(data) {
						// data = JSON.parse(data);
						console.log(data);
						if (data.status == true) {
							toastr.success("", data.data);

						} else {
							toastr.error("", data.data);

						}
						setInterval(function() {
							location.reload();
						}, 1000);

					},
					error: function() {
						alert("Something is Wrong");
					}

				});
			} else {
				alert("Select First");
			}

		});

	})


	// COPY DATA
	$(function() {
		$("#copy-product").click(function() {
			var selected = new Array();
			$(".cheking input[type=checkbox]:checked").each(function() {
				selected.push(this.value);
			});
			if (selected.length > 0) {
				console.log(selected[0])
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>" + "index.php/product/getProduct/" + selected[0],
					dataType: 'json',
					success: function(data) {
						// data = JSON.parse(data);
						console.log(data);
						if (data.status == true) {
							console.log(data);
							data = data.data;
							$('#add-product').modal('show');
							$("#product_name").val(data.p_name);
							$("#price").val(data.price);
							$("#cost").val(data.purchase_rate);
							$("#mrp").val(data.mrp);
							$("#discount").val(data.discount);
							$("#qty").val(data.qty);
							$("#hsn_code").val(data.hsn);
							$("#min_qty").val(data.min_qty);
							$("#weight").val(data.weight);
							$("#unit").val(data.unit);
							$("#tax").val(data.tax);
							$("#shortdescription").val(data.short_de);
							$("#description").val(data.long_de);
						} else {
							toastr.error(data.data);

						}
					},
					error: function() {
						alert("Something is Wrong");
					}

				});
			} else {
				alert("Select First");
			}

		});

	});



	$(function() {
		$("#print-barcode").click(function() {
			var selected = new Array();
			$(".cheking input[type=checkbox]:checked").each(function() {
				selected.push(this.value);
			});
			if (selected.length > 0) {
				console.log(selected)
				$.ajax({
					type: "POST",
					url: '<?= base_url("index.php/product/selectProduct") ?>',
					data: {
						id: selected,
					},
					dataType: 'json',
					success: function(data) {
						// var product = JSON.parse(data);
						console.log(data.data);
						if (data.status == true) {
							rowCount = 0;
							var product = data.data;
							console.log(product);
							tabindex = 0;
							document.getElementById('dynamic_data').innerHTML = "";
							$("#barcode_list").modal("show");
							product.forEach(function(products) {

								$("#dynamic_data").append(
									'<tr id="tr' + rowCount + '">\n' +
									'                                                        <td style="padding:4px;"><input type="text" readonly id="product' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][p_name]" value="' + products.p_name + '" class="form-control"></td>' +
									'                                                        <td style="padding:4px;"><input type="text"  id="qty' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][qty]" value="' + products.qty + '" min="1" class="form-control"></td>' +
									'                                                        <input type="hidden" readonly id="id' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][id]" value="' + products.id + '" class="form-control">' +
									'                                                        <input type="hidden" readonly id="productcode' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][productcode]" value="' + products.qrCode + '" class="form-control">' +
									'</tr>' +
									++rowCount
								)

							})
						};
					}


				});
			} else {
				alert("Select First");
			}

		});

	});



	$(document).ready(function() {
		$('#submitButton').click(function() {
			$('#import_form').ajaxForm({
				target: '#outputImage',
				url: '<?= base_url() ?>',
				beforeSubmit: function() {
					$("#outputImage").hide();
					if ($("#uploadImage").val() == "") {
						$("#outputImage").show();
						$("#outputImage").html("<div class='error'>Choose a file to upload.</div>");
						return false;
					}
					$("#progressDivId").css("display", "block");
					var percentValue = '0%';

					$('#progressBar').width(percentValue);
					$('#percent').html(percentValue);
				},
				uploadProgress: function(event, position, total, percentComplete) {

					var percentValue = percentComplete + '%';
					$("#progressBar").animate({
						width: '' + percentValue + ''
					}, {
						duration: 5000,
						easing: "linear",
						step: function(x) {
							percentText = Math.round(x * 100 / percentComplete);
							$("#percent").text(percentText + "%");
							if (percentText == "100") {
								$("#outputImage").show();
							}
						}
					});
				},
				error: function(response, status, e) {
					alert('Oops something went.');
				},

				complete: function(xhr) {
					if (xhr.responseText && xhr.responseText != "error") {
						$("#outputImage").html(xhr.responseText);
					} else {
						$("#outputImage").show();
						$("#outputImage").html("<div class='error'>Problem in uploading file.</div>");
						$("#progressBar").stop();
					}
				}
			});
		});
	});

	function submitHandler() {
		var checkboxes = $('input:checkbox:checked').length;
		if (length > 0) { // alert("select first");
			alert("select first");
			return false;
		}
		return true;
	}


	$(document).ready(function() {
		$('.dataTable').DataTable({
			dom: 'Bfrtip',
			"paging": false,
			"info": false,
			buttons: [
				'csv', 'pdf', 'print', 'colvis',
				{
					extend: 'excelHtml5',
					exportOptions: {
						columns: [1, 2, 3, 4, 5, 6, 7]
					}
				},
			]



		});
	});


	// $(document).on('expanded.pushMenu', handleExpandedEvent);


	jQuery.noConflict();

	function onProductFormSubmitHandler(e) {
		e.preventDefault();
		var url = $("#onProductFormSubmitHandler").attr("action");
		var form = $("#onProductFormSubmitHandler")[0];
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
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					toastr.success("Product Added Successfully!", "");
					setTimeout(function() {
						location.reload();
					}, 2000)

				} else {
					$("#productMsg").show();
					$("#productMsg").addClass("text");
					$("#productMsg").addClass("text-danger");
					$("#productMsg").html(customerList.msg);

				}
			},

		});
	}

	function productEdit(productId) {
		jQuery.noConflict();
		$("#exampleModals .modal-dialog .modal-body").load(baseurl + "index.php/product/edit_product/" + productId);
		$("#exampleModalLabels").html("Update Product");
		$("#exampleModals").modal("show");

	}

	function productStock(productId) {
		jQuery.noConflict();
		$("#productStock .modal-dialog .modal-body").load(baseurl + "index.php/product/stock/" + productId);
		$("#productStockLabels").html("REPORT");
		$("#productStock").modal("show");

	}

	// const onFormSubmitHandler = (e) => {
	// 	e.preventDefault();
	// 	var form = $('#reportForm').serialize();
	// 	href = "<?= base_url() ?>product/product_report?" + form;
	// 	var $a = $("<a>");
	// 	$a.attr("href", href);
	// 	$("body").append($a);
	// 	$a[0].click();
	// 	$a.remove();
	// }
	$("#resetbtn").click(function() {
		window.location.href = "<?= base_url() ?>product";
	})

	$(function() {
		$("#export-button").click(function() {
			var selected = new Array();
			$(".cheking input[type=checkbox]:checked").each(function() {
				selected.push(this.value);
			});
			console.log(selected);
			if (selected.length > 0) {
				var id = selected;
				console.log(id);
				href = "<?= base_url() ?>product/exportproduct";
				var $a = $("<a>");
				$a.attr("href", href);
				$("body").append($a);
				$a.attr("download", "product.xls");
				$a[0].click();
				$a.remove();

			} else {
				alert("Select First");
			}

		});

	});
</script>