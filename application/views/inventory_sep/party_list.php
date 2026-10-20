<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">
				<div class="col-lg-12">
					<div class="card mb-30">
					<div class="card-header">
					<div class="card-title">Parties</div>
					<div class="float-right">
						<button class="btn btn-outline-success" title="Change Sales Tax" id="change-sales-tax"><i class="fa fa-money" aria-hidden="true"></i></button>
						<button class="btn btn-outline-danger" title="Delete" id="delete-product"><i class="fa fa-trash fa-lg"></i></button>
						<button class="btn btn-outline-info" title="Upload" id="upload-product" data-toggle="modal" data-target="#importModal"><i class="fa fa-upload fa-lg"></i></button>
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
															$productcodes = 0;
															$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
															$productCode = $this->user_model->getRow('productCode', $where);
															if ($productCode) {
																$productcodes = $productCode->productcode;
															}

															// $productDetails = $this->user_model->getAry('product', '', '', '', $where);
															$this->db->from('product');
															// $this->db->where('comp_id', $this->session->userdata('comp_id'));
															// $this->db->where('user_id', $this->session->userdata('user_id'));
															$this->db->where($where);
															$this->db->order_by("id", "desc");
															$productDetails = $this->db->get()->result();
															$total = count($productDetails);

															// $productDetails='';
															if (isset($productDetails) && !empty($productDetails)) {
																$productcodes = 0;
																// foreach ($productDetails as $proData) {
																// 	$productcodes = $proData->qrCode;
																// }
																for ($i = 0; $i < $total; $i++) {
																	$productcodes = $productDetails[$i]->qrCode;
																	if (is_numeric($productcodes) && $productcodes) {
																		$productcodes = $productcodes;
																		break;
																	}
																}


															?>
																<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productcodes + 1 ?>">


															<?php
															} else {
															?>
																<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productcodes ?>">

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

													<div class="col-md-6 col-sm-6 col-lg-6">
														<div class="form-group">
															<textarea id="shortdescription" tabindex="13" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
														</div>
													</div>
													<div class="col-md-6 col-sm-6 col-lg-6">
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

						<div class="modal fade" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
							<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabels"></h5>
										<input type="hidden" id="rownumber">
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
						<div class="card-body">
							
							
							<?php
							if ($this->session->flashdata('msg')) { ?>
								<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
									<?php echo $this->session->flashdata('msg'); ?>
								</div>
							<?php } ?>

							<table class="table table-striped table-bordered table-responsive-sm dataTable">
								<thead>
									<tr>
										<th>Sr.</th>
										<th>Name</th>
										<th>A/C Group Head</th>
										<th>Contact</th>
										<th>Email</th>
										<th>Pan</th>
										<th>GST</th>
										<th>Limit(<i class="fa fa-inr"></i>)</th>
										<th>Type</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if ($parties) {
										foreach ($parties as $key => $row) { ?>
											<tr>
												<td><?= $key + 1 ?></td>
												<td><?= $row->name; ?></td>
												<?php
												$groups = $this->user_model->getRow('accounting_group', ['id' => $row->group_head]);
												?>
												<td><?php if ($groups) {
														echo $groups->name;
													}?>
													</td>
												<td><?= $row->contact; ?></td>
												<td><?= $row->email; ?></td>
												<td><?= $row->pan; ?></td>
												<td><?= $row->gstType; ?></td>
												<td></i><?= $row->credit_limit; ?></td>
												<td><span class="badge"><?= $row->opBalnceType; ?></span></td>

												<td>
													<a href="<?= base_url('party/party_view/') . $row->id ?>" class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye icon icon-size">
															<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
															<circle cx="12" cy="12" r="3"></circle>
														</svg></a>
													| <a href="<?= base_url('party/party_edit/') . $row->id ?>" class="text-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit icon icon-size">
															<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
															<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
														</svg></a>
													| <a href="#" onclick="return deleteRow('<?= base_url('party/party_delete/') . $row->id ?>');" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon icon-size">
															<polyline points="3 6 5 6 21 6"></polyline>
															<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
															<line x1="10" y1="11" x2="10" y2="17"></line>
															<line x1="14" y1="11" x2="14" y2="17"></line>
														</svg></a>
												</td>
											</tr>
									<?php }
									} ?>
								</tbody>
							</table>

							<div class="row">
								<div class="col-md-6">
									<?php if ($totalCount) { ?>
										<p>Showing <?= $start ? $start : 1; ?> to <?= $end ?> of <?= $totalCount ?> entries</p>
									<?php } else { ?>
										<p>No result found.</p>
									<?php } ?>
								</div>
								<div class="col-md-6">
									<div class="fr">
										<?= $this->pagination->create_links(); ?>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>



		</div>
	</div>
</div>

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

	$('#export').click(function(e) {
		e.preventDefault();
		// var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>party/exportparty";
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a.attr("download", "party.xls");
		$a[0].click();
		$a.remove();
	});
</script>
