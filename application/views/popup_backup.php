<tr id="hiddenproduct<?= $key ?>" style="display:none">
					<td>
						<form onsubmit="onProductSubmitHandler(event,<?= $key ?>)" id="productEditData<?= $key ?>" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/updateSaleProducts" method="POST" enctype="multipart/form-data">

							<?php
							if (isset($error)) {
								echo $error;
							} ?>
							<div class="" id="errormsg<?= $key ?>" style="opacity: 500; display: none;"></div>

							<!-- <h1 class="text-center">Update Product</h1> -->

							<div class="row">
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="name">Product Name<span class="text-danger">*</span></label>

										<input id="name" name="name" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= $result->p_name ?>">

									</div>
									<span class="col-sm-4 control-label">
										<?php echo form_error('name'); ?>

									</span>
								</div>
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
										<label class="control-label" for="p_code">Product Code<span class="text-danger">*</span></label>

										<input id="p_code" readonly name="p_code" placeholder="Product Barcode" type="text" class="form-control" value="<?= $result->qrCode  ?>">
									</div>
									<span class="col-sm-4 control-label">
										<?php echo form_error('p_code'); ?>

									</span>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="mrp">Product MRP<span class="text-danger"></span></label>

										<input id="mrp" name="mrp" placeholder="Product MRP" type="text" class="form-control" value="<?= $result->mrp  ?>">
									</div>
									<span class="col-sm-4 control-label">
										<?php echo form_error('mrp'); ?>

									</span>
								</div>
								<input type="hidden" name="id" value="<?= $result->id; ?>">

								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="name">Selling Price<span class="text-danger"></span></label>

										<input id="price" name="price" min="1" placeholder="Product Selling Price" type="number" class="form-control" value="<?= $result->price ?>">
									</div>
									<span class="col-sm-4 control-label">
										<?php echo form_error('price'); ?>

									</span>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="name">Purchade Price<span class="text-danger"></span></label>

										<input id="cost" name="purchase_rate" min="0" placeholder="Purchase Price" type="number" class="form-control" value="<?= $result->purchase_rate ?>">
									</div>
								</div>


								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="qty">Qty<span class="text-danger">*</span></label>

										<input id="qty" name="qty" min="1" placeholder="Product Qunatity" type="number" class="form-control" value="<?= $result->qty ?>">
									</div>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="discount">Discount<span class="text-danger"></span></label>

										<input id="discount" name="discount" min="0" placeholder="Discount in %" type="number" class="form-control" value="<?= $result->discount ?>">
									</div>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="hsn">HSN<span class="text-danger"></span></label>

										<input id="gsn_code" name="gsn_code" placeholder="HSN Code" type="text" class="form-control" value="<?= $result->hsn ?>">
									</div>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="min_qty">Min Qty<span class="text-danger"></span></label>

										<input id="min_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?= $result->min_qty ?>">
									</div>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="weight">Weight<span class="text-danger"></span></label>

										<input id="weight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?= $result->weight ?>">
									</div>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="unit">UNIT<span class="text-danger">*</span></label>

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
										<label class="control-label" for="tax">TAX<span class="text-danger">*</span></label>

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
									<label class="control-label" for="image">Image<span class="text-danger"></span></label>

									<div class="custom-file">

										<input id="customFile" name="image" type="file" class="custom-file-input" value="<?= set_value('image') ?>">
										<label class="custom-file-label" for="customFile">Choose file</label>
									</div>
								</div>

								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="des">Short Description<span class="text-danger">*</span></label>

										<textarea id="description" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= $result->short_de ?></textarea>
									</div>
								</div>
								<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
									<div class="form-group">
										<label class="control-label" for="long">Long Description<span class="text-danger">*</span></label>

										<textarea id="description" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= $result->long_de ?></textarea>
									</div>
								</div>

							</div>


							<div class="row mt-2">
								<div class="col-md-12 text-center">
									<button type="submit" class="btn btn-success">


										Update

									</button>
									<a href="#" id='proclick<?= $key ?>' onclick="productProfileEdit(<?= $key ?>,<?= $result->id ?>)" class="btn btn-danger">Close</a>
								</div>
							</div>
						</form>
					</td>


				</tr>