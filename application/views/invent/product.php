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

<div class="app-main__outer">
    <div class="app-main__inner">
        
        <div class="row">
            <div class="col-md-12 card">
            <div class="card-header">
					<div class="card-title">Products</div>
					<div class="float-right">
						<button class="btn btn-outline-success" title="Change Sales Tax" id="change-sales-tax"><i class="fa fa-money fa-lg"></i></button>
						<button class="btn btn-outline-danger" title="Delete" id="delete-product"><i class="fa fa-trash fa-lg"></i></button>
						<button class="btn btn-outline-info" title="Upload" id="upload-product" data-toggle="modal" data-target="#importModal"><i class="fa fa-upload fa-lg"></i></button>
						<button class="btn btn-outline-primary" title="Copy" id="copy-product"><i class="fa fa-clone fa-lg"></i></button>
						<button class="btn btn-outline-success" title="Add" data-toggle="modal" data-target="#add-product"><i class="fa fa-plus fa-lg"></i></button>
						<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">

							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Upload Excel Data</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<form id="import-frm" method="POST" action="import_product" enctype="multipart/form-data" novalidate="novalidate">
										<div class="modal-body">
											<a href="https://umaticexpert.com/assets/xls/product_template.xls" class="float-right"><i class="fa fa-download"></i> Download Template</a>
											<div class="input-field">
												<div class="file-field">
													<div class="btn" style="margin-top: -15px;">
														<span>Upload File</span>
														<input data-error=".logo" type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
													</div>

													<div class="logo"></div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-sm  btn-secondary" data-dismiss="modal"><span aria-hidden="true">×</span> Close</button>
											<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-upload"></i> Import</button>
										</div>

									</form>
								</div>
							</div>
						</div>
						<div class="modal fade" id="add-product" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="productModalLabel">Add Product</h5>
										<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
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
															<!-- <lablel for="product_name">Product Name</lablel> -->
															<input id="product_name" name="name" tabindex="1" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
														</div>
														<span class="col-sm-4 control-label">
															<?php echo form_error('name'); ?>

														</span>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
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
																			<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $maxvalue + 1 ?>">

																		<?php
																		} else {
																		?>
																			<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productCode + 1 ?>">

																		<?php
																		}
																	} else {
																		?>
																		<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= 1 ?>">

																	<?php
																	}
																} else {
																	if ($productCode) {
																		$productCode = $productCode->productcode;
																	} else {
																		$productCode = 0;
																	}
																	?>
																	<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productCode + 1 ?>">

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
																<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productCode + 1 ?>">

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
															<input id="price" name="price" tabindex="3" min="1" placeholder="Selling Price" type="number" class="form-control" value="<?= set_value('price') ?>">
														</div>
														<span class="col-sm-4 control-label">
															<?php echo form_error('price'); ?>

														</span>
													</div>

													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<input id="cost" name="sale_rate" tabindex="4" min="0" placeholder="Purchase Price" type="number" class="form-control" value="<?= set_value('sale_rate') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<input id="mrp" name="mrp" min="0" tabindex="5" placeholder="MRP" type="number" class="form-control" value="<?= set_value('mrp') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<input id="qty" name="qty" min="1" tabindex="6" placeholder="Product Qunatity" type="number" class="form-control" value="<?= set_value('qty') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<input id="discount" name="discount" min="0" tabindex="7" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<input id="hsn_code" name="hsn_code" tabindex="8" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<input id="min_qty" name="min_qty" min="0" tabindex="9" placeholder="Min Qty" type="number" class="form-control" value="<?= set_value('min_qty') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<input id="weight" name="weight" min="0" tabindex="10" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
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

													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
															<textarea id="shortdescription" tabindex="13" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
														<div class="form-group">
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

					</div>
				</div>

				<div class="card-body">
					<form id="product-search-frm" class="row" style="margin: 10px 0px;">

					</form>
					<div class="row">
						<div class="col-sm-12">
							<table id="product-table" class="table table-bordered dataTable dtr-column" cellspacing="0" width="100%" aria-describedby="product-table_info" role="grid">
								<thead>
									<tr role="row">
										<th class="text-center sorting_disabled" rowspan="1" colspan="1" aria-label=""><input type="checkbox" id="product-selectAll" class="selectAll"><label for="product-selectAll"></label></th>
										<th class="sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Product No: activate to sort column ascending">Sr No</th>
										<th class="sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Product: activate to sort column ascending">Product</th>
										<th class="sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="HSC / SAC: activate to sort column ascending">HSN</th>

										<th class="sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending">Product Code</th>
										<!-- <th class="sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Category: activate to sort column ascending">Category</th> -->
										<th class="text-right sum sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Selling Price: activate to sort column ascending">Selling Price</th>
										<th class="text-right sum sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Purchase Price: activate to sort column ascending">Purchase Price</th>
										<th class="text-right sum sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="MRP Price: activate to sort column ascending">MRP Price</th>
										<!-- <th class="sum text-right sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Instock: activate to sort column ascending">Instock</th>
											<th class="sum text-right sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Outstock: activate to sort column ascending">Outstock</th> -->
										<!-- <th class="sum text-right sorting" tabindex="0" aria-controls="product-table" rowspan="1" colspan="1" aria-label="Available: activate to sort column ascending">Available</th> -->
										<th class="text-center nowrap text-nowrap sorting_disabled" rowspan="1" colspan="1" aria-label="...">...</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th class="text-center" rowspan="1" colspan="1"></th>
										<th rowspan="1" colspan="1">Total</th>
										<th rowspan="1" colspan="1"></th>
										<th rowspan="1" colspan="1"></th>
										<?php

										?>
										<th class="text-right sum" rowspan="1" colspan="1">5498.00</th>
										<th class="text-right sum" rowspan="1" colspan="1">5425.00</th>
										<th class="text-right sum" rowspan="1" colspan="1">9257.00</th>
										<th class="sum text-right" rowspan="1" colspan="1">23.00</th>

										<th class="text-center text-nowrap" rowspan="1" colspan="1"></th>
									</tr>
								</tfoot>
								<tbody>
									<?php
									if (isset($product) && !empty($product)) {
										foreach ($product as  $key => $value) {
											$date = $value->created_at;
											$newDate = date("d-m-Y", strtotime($date));
									?>
											<tr>
												<td>
													<input class="checkdata" type="checkbox" name="product_id[]" value="<?php echo $value->id; ?>" ?>

													<!-- <span><?= $key + 1; ?></span> -->
												</td>
												<td><?= $key + 1; ?></td>
												<td><?= $value->p_name ?></td>
												<td><?= $value->hsn ?></td>

												<td><?= $value->qrCode ?></td>
												<td><?= $value->price ?></td>
												<td><?= $value->purchase_rate ?></td>
												<td><?= $value->mrp ?></td>



												<td class="text-right">
													<div class="dropdown">
														<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<i class="fas fa-ellipsis-v"></i>
														</a>
														<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
															<a href="<?= base_url('product/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a>
															<a href="<?= base_url('product/delete/') . $value->id ?>" class="dropdown-item cp">Delete</a>


														</div>
													</div>
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
    