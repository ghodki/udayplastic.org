<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Raw Material</h2>
								<!-- Card stats -->
								<div class="row">

									<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>raw_material/update" method="POST" enctype="multipart/form-data">



										<div class="row">
										<input type="hidden" name="id" value="<?=$raw_material->id; ?>">
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="name" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?= $raw_material->name ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="rate" name="price" min="1" placeholder="Rate" type="number" class="form-control" required="true" value="<?= $raw_material->rate ?>">
												</div>
											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="qty" name="qty" min="1" placeholder="Qunatity" type="number" class="form-control" required="true" value="<?= $raw_material->qty ?>">
												</div>
											</div>

											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="min_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?= $raw_material->min_qty ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">
													<input id="weight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?= $raw_material->weight ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<?php
												$where = ['id' => $raw_material->unit];
												$unitdata = $this->user_model->getRow('unit', $where);
												if ($unitdata) {
													$var = $unitdata->name;
												} else {
													$var = "Unit Deleted";
												}
												?>

												<select id="unit" name="unit" class="form-control" required>

													<option value='<?= $raw_material->unit ?>'><?= $var?></option>
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
