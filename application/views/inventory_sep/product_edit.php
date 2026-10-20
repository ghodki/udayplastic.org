<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px">Update Product</h2>
								<!-- Card stats -->
								<div class="row">

									<form id="product_form" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/update" method="POST" enctype="multipart/form-data">

										<?php
										if (isset($error) && $error != '') {
											echo $error;
										} ?>
										<?php
										if ($this->session->flashdata('msg')) { ?>
											<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
												<?php echo $this->session->flashdata('msg'); ?>
											</div>
										<?php } ?>
										<div class="row">

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<!-- <select id="p_code" name="p_code" class="form-control"> -->
												<!-- <option value="">Select Product Code</option> -->
												<?php
												// if (!empty($result->qrCode)) {
												// 	$taxrate = $this->user_model->getRow('productCode', ['id' => $result->qrCode]);
												// 	if ($taxrate) {
												// 
												?>
												<!-- <option value="<?= $taxrate->id ?>" selected><?= $taxrate->productcode ?></option> -->
												<?php
												// 	}
												// }
												// $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
												// $unit = $this->user_model->getAry('productCode', '', '', '', $where);


												// if (isset($unit) && !empty($unit)) {
												// 	foreach ($unit as $units) {
												// 	
												?>
												<!-- <option value="<?= $units->id ?>" <?= set_value('p_code') == $units->id ? 'selected' : ''; ?>><?= $units->productcode; ?></option> -->
												<?php
												// 	}
												// }

												?>
												<!-- </select> -->
												<div class="form-group">
													<input id="p_code" readonly name="p_code" placeholder="Product MRP" type="text" class="form-control" value="<?= $result->qrCode  ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('p_code'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="name" name="name" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= $result->p_name ?>">

												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('name'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="mrp" name="mrp" placeholder="Product MRP" type="text" class="form-control" value="<?= $result->mrp  ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('mrp'); ?>

												</span>
											</div>
											<input type="hidden" name="id" value="<?= $result->id; ?>">
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="price" name="price" min="1" placeholder="Product Selling Price" type="number" class="form-control" value="<?= $result->price ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('price'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="cost" readonly name="purchase_rate" data-toggle="modal" data-target="#myModal" min="0" placeholder="Purchase Price" type="number" class="form-control" value="<?= $result->purchase_rate ?>">
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
																	<?php
																	$array = array();
																	// $varr='';
																	$array[0] = "--Select Group--";
																	$acgroup = $this->user_model->getResult('raw_material');
																	if ($acgroup) {
																		foreach ($acgroup as $teacher) {
																			$array[$teacher->id] = $teacher->name;
																		}
																	}

																	echo form_dropdown("raw", $array, set_value("raw"), "id='raw' class='form-control'");
																	?>


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
													<input id="qty" name="qty" min="1" placeholder="Product Qunatity" type="number" class="form-control" value="<?= $result->qty ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="discount" name="discount" min="0" placeholder="Discount in %" type="number" class="form-control" value="<?= $result->discount ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="gsn_code" name="gsn_code" placeholder="HSN Code" type="text" class="form-control" value="<?= $result->hsn ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="min_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?= $result->min_qty ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="weight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?= $result->weight ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<select name="unit" id="unit" class="form-control searchselect" data-live-search="true" required>
														<?php
														if ($result->unit != null) {
															$taxrate = $this->user_model->getRow('unit', ['id' => $result->unit]);
															if ($taxrate) {
														?>
																<option value="<?= $taxrate->id ?>" selected><?= $taxrate->name ?></option>
																<?php
															}
														}
														$unit = $this->user_model->getResult('unit');

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

												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error("tax"); ?>

												</span>
											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<select name="tax" id="tax" class="form-control searchselect" data-live-search="true" required>
														<?php
														if ($result->tax != null) {
															$taxrate = $this->user_model->getRow('tax', ['id' => $result->tax]);
															if ($taxrate) {
														?>
																<option value="<?= $taxrate->id ?>" selected><?= $taxrate->name ?></option>
																<?php
															}
														}
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
												// $varr='';
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
													<textarea id="description" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= $result->short_de ?></textarea>
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<textarea id="description" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= $result->long_de ?></textarea>
												</div>
											</div>

										</div>


										<div class="row mt-5">
											<div class="col-md-12 text-center">
												<button type="submit" class="btn btn-default">


													Update

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

	$('#export').click(function(e) {
		e.preventDefault();
		// var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>product/exportproduct";
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a.attr("download", "product.xls");
		$a[0].click();
		$a.remove();
	});
</script>
<script>
	// $('#import_form').on('submit', function(event){
	//   event.preventDefault();
	//   $.ajax({
	//    url:"<?php echo base_url(); ?>index.php/product/importfile",
	//    method:"POST",
	//    data:new FormData(this),
	//    contentType:false,
	//    cache:false,
	//    processData:false,
	//    success:function(data){
	//     $('#file').val('');
	//     load_data();
	//     alert(data);
	//    }
	//   })
	//  });
</script>