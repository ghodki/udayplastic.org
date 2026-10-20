<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-blue pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px">Add Product</h2>
								<!-- Card stats -->
								<div class="row">

									<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/save" method="POST" enctype="multipart/form-data">

										<?php
										if (isset($error) && $error != '') {
											echo $error;
										} ?>

										<div class="row">
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="name" name="name" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('name'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="price" name="price" min="1" placeholder="Product Price" type="number" class="form-control" required="true" value="<?= set_value('price') ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('price'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="cost" readonly name="purchase_rate" data-toggle="modal" data-target="#myModal" min="0" placeholder="Cost" type="number" class="form-control" required="true" value="<?= set_value('purchase_rate') ?>">
												</div>
											</div>
											<!-- Button to Open the Modal -->


											<!-- The Modal -->
											<div class="modal" id="myModal">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">

														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title text-center text-dark">Raw Material</h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>

														<!-- Modal body -->
														<div class="modal-body">
															<div class="row">
																<div class="col-md-6">
																	<select id="raw" name="raw" class="form-control" required>
																		<option value=''>Raw Material</option>
																		<?php
																		$raw_material = $this->user_model->getResult('raw_material');
																		if ($raw_material) {
																			foreach ($raw_material as $key => $value) {
																		?>
																				<option value="<?= $value->id; ?>"><?= $value->name; ?></option>

																		<?php	}
																		}
																		?>


																	</select>

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

														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
														</div>

													</div>
												</div>
											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="qty" name="qty" min="1" placeholder="Product Qunatity" type="number" class="form-control" required="true" value="<?= set_value('qty') ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="discount" name="discount" min="0" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout') ?>">
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
												<div class="form-group">
													<input id="gsn_code" name="gsn_code"  placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('gsn_code') ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<select id="unit" name="unit" <?= set_value('unit') ?> class="form-control" required>
													<option value=''>Select unit</option>
													<?php
													$cate = $this->user_model->getResult('unit');
													if ($cate) {
														foreach ($cate as $key => $value) {
													?>
															<option value="<?= $value->id; ?>"><?= $value->name; ?></option>

													<?php	}
													}
													?>


												</select>

											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<select id="tax" name="tax" <?= set_value('tax') ?> class="form-control" required>
													<option value=''>Select Tax</option>
													<?php
													$cate = $this->user_model->getResult('tax');
													if ($cate) {
														foreach ($cate as $key => $value) {
													?>
															<option value="<?= set_value($value->id); ?>"><?= set_value($value->name); ?></option>

													<?php	}
													}
													?>


												</select>

											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<select id="cate" name="cate" <?= set_value('cate') ?> class="form-control" required>
													<option value=''>Select Category</option>
													<?php
													$cate = $this->user_model->getResult('product_categories');
													if ($cate) {
														foreach ($cate as $key => $value) {
													?>
															<option value="<?= set_value($value->id); ?>"><?= set_value($value->category_name); ?></option>

													<?php	}
													}
													?>


												</select>

											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3 form-group">
												<div class="custom-file">
													<input id="customFile" name="image" type="file" class="custom-file-input" value="<?= set_value('image') ?>">
													<label class="custom-file-label" for="customFile">Choose file</label>
												</div>
											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<textarea id="description" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
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
						<div class="container-fluid mb-9" style="margin-top:100px;">

							<div class="col">
								<div class="card shadow">
									<div class="card-header border-0">
										<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Product List</label>
										<div class="row mt-5">
										</div>
										<div class="table-responsive">
											<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
												<thead class="thead-dark">
													<tr>
														<th scope="col" width="2%">#</th>
														<th scope="col" width="10%">Date</th>
														<th scope="col" width="10%">Product Name</th>
														<th scope="col" width="10%">Image</th>

														<th scope="col" width="10%">Price</th>
														<th scope="col" width="10%">Discount</th>
														<th scope="col" width="10%">GSN Code</th>
														<th scope="col" width="10%">Cost</th>

														<th scope="col" width="10%">Description</th>
														<th scope="col" width="10%">Details</th>

														<th scope="col">Action</th>
													</tr>
												</thead>
												<tbody class="text-dark">
													<?php
													if (isset($product) && $product != '') {
														foreach ($product as  $key => $value) {
															$date = $value->created_at;
															$newDate = date("d-m-Y", strtotime($date));
													?>
															<tr>
																<th>
																	<span><?= $key + 1; ?></span>
																</th>
																<th><?= $newDate; ?></th>

																<th><?= $value->p_name ?></th>
																<th><?= $value->p_name ?></th>
																<th><?= $value->price ?></th>
																<th><?= $value->discount ?></th>
																<th><?= $value->hsn ?></th>
																<th><?= $value->purchase_rate ?></th>

																<th><?= $value->short_de ?></th>
																<th><?= $value->long_de ?></th>


																<th class="text-right">
																	<div class="dropdown">
																		<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			<i class="fas fa-ellipsis-v"></i>
																		</a>
																		<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																			<!-- <a href="<?= base_url('notification/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																			<a href="<?= base_url('product/delete/') . $value->id ?>" class="dropdown-item cp">Delete</a>


																		</div>
																	</div>
																</th>
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
		</div>
	</div>
</div>

<script>
	var array = [];

	$('#raw').change(function() {
		// var year_id = $(this).val();

		var raw = document.getElementById('raw').value;



		var n = array.includes(raw);

		if (n == false) {

			if (parseInt(raw) > 0) {
				$.ajax({
					type: "POST",
					url: '<?= base_url('raw_material/getRaw'); ?>',
					data: {
						id: raw,

					},
					success: function(data) {
						console.log(data);
						$("#raw_details").append(data);
					}

				});

			}
			array.push(raw);
		}







	});
</script>
<script>
	// var overall = 0;

	function changeValue(qty, id) {

		console.log(id, "id");
		console.log(qty, "qty");
		if (qty < 0) {
			document.getElementById('qty' + id).value = 0;
			document.getElementById('mini_total' + id).value = 0;
			document.getElementById('overall').value = overall;
			document.getElementById('cost').value = overall;

		} else {
			var rate = document.getElementById('rate' + id).value;
			var total_for_single = rate * qty;
			// overall = overall + total_for_single;
			document.getElementById('mini_total' + id).value = total_for_single;
			getOverall();
			getWeight();


			// document.getElementById('overall').value = overall;
			// document.getElementById('cost').value = overall;


		}



	}
</script>
<script>
	function getOverall() {
		var x = document.getElementsByClassName("new");
		var i;
		var total = 0;
		if (x) {
			for (i = 0; i < x.length; i++) {
				var v = x[i].value;
				total = parseFloat(total) + parseFloat(v);
			}
		}
		// console.log(total);
		// var x = document.getElementsByClassName("new");
		document.getElementById('cost').value = total;
		document.getElementById('overall').value = total;

	}
</script>
<script>
	function getWeight() {
		var x = document.getElementsByClassName("weight");
		// console.log(x,'weight');

		var i;
		var total = 0;
		for (i = 0; i < x.length; i++) {
			var v = x[i].value;
			total = parseInt(total) + parseInt(v);
		}
		console.log(total, "weight");
		// var x = document.getElementsByClassName("new");
		// document.getElementById('cost').value = total;
		document.getElementById('overall_weight').value = total;
	}
</script>
<script>
	$('#extra').change(function() {
		// var year_id = $(this).val();

		var x = document.getElementsByClassName("new");
		var i;
		var total = 0;
		if (x) {
			for (i = 0; i < x.length; i++) {
				var v = x[i].value;
				total = parseFloat(total) + parseFloat(v);
			}
		}

		var extra = document.getElementById('extra').value;
		if (extra > 0) {
			total = parseFloat(total) + parseFloat(extra);
		}


		// console.log(total);
		// var x = document.getElementsByClassName("new");
		document.getElementById('cost').value = total;
		document.getElementById('overall').value = total;
	});
</script>
