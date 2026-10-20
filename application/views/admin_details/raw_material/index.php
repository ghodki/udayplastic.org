<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Raw Material</h2>
								<!-- Card stats -->
								<div class="row">

									<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>raw_material/save" method="POST" enctype="multipart/form-data">



										<div class="row">
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="name" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?=set_value('name') ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('name'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="rate" name="price" min="1" placeholder="Rate" type="number" class="form-control" required="true" value="<?=set_value('price') ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('price'); ?>

												</span>
											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="qty" name="qty" min="1" placeholder="Qunatity" type="number" class="form-control" required="true"  value="<?=set_value('qty') ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('qty'); ?>

												</span>
											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="min_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?=set_value('min_qty') ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('min_qty'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="weight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?=set_value('weight') ?>">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('weight'); ?>

												</span>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<select id="unit" name="unit" class="form-control" required>
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
										<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Raw_material List</label>
										<div class="row mt-5">
										</div>
										<div class="table-responsive">
											<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
												<thead class="thead-dark">
													<tr>
														<th scope="col" width="2%">#</th>
														<th scope="col" width="10%">Date</th>
														<th scope="col" width="20%">Name</th>

														<th scope="col" width="14%">Price</th>
														<th scope="col" width="18%">Min Qty</th>
														<th scope="col" width="18%">Qty</th>
														<th scope="col" width="18%">Weight</th>




														<th scope="col">Action</th>
													</tr>
												</thead>
												<tbody class="text-dark">
													<?php
													if (isset($raw_material) && $raw_material != '') {
														foreach ($raw_material as  $key => $value) {
															$date = $value->created_at;
															$newDate = date("d-m-Y", strtotime($date));
													?>
															<tr>
																<td>
																	<span><?= $key + 1; ?></span>
																</td>
																<td><?= $newDate; ?></td>

																<td><?= $value->name ?></td>
																<td><?= $value->rate ?></td>
																<td><?= $value->min_qty ?></td>
																<td><?= $value->qty ?></td>
																<td><?= $value->weight ?></td>





																<td class="text-right">
																	<div class="dropdown">
																		<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			<i class="fas fa-ellipsis-v"></i>
																		</a>
																		<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																			<a href="<?= base_url('raw_material/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a>
																			<a href="<?= base_url('raw_material/delete/') . $value->id ?>" class="dropdown-item cp">Delete</a>


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
				</div>
			</div>
		</div>
	</div>
</div>
