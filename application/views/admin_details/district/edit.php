<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">

					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add District(City)</h2>

								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" action="<?= base_url() ?>district/update_district" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">
											<div class="col-md-4 col-sm-6 col-lg-4">
												<input type="hidden" value="<?= $result->id ?>" name="id">
											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
												<div class="form-group">
													<label for="name">State</label>
													<select name="state_id" required id="state" class="form-control searchselect select2" style="width:100%">
														<option value="">Select State</option>
														<?php
														$state = $this->user_model->getAryss('state_list');

														if ($state) {
															foreach ($state as $key => $row) { ?>
																<option value="<?= $row->id ?>" <?= set_value('state_id', $result->state_id) == $row->id ? 'selected' : ''; ?>><?= $row->state; ?>
																</option>
														<?php }
														} ?>
													</select>
													<!-- <input type="text" class="form-control" name="name" value="<?= set_value('name') ?>" placeholder="Enter Name"> -->
												</div>
												<span class="control-label text-danger">
													<?php echo form_error('state_id'); ?>

												</span>
											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">

											</div>

											<div class="col-md-4 col-sm-6 col-lg-4">

											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
												<div class="form-group">
													<label for="name">District Name</label>
													<input type="text" class="form-control" value="<?= set_value('name', $result->name) ?>" name="name" placeholder="Enter District Name">
												</div>
												<span class="control-label text-danger">
													<?php echo form_error('name'); ?>

												</span>
											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">

											</div>

										</div>
										<div class="row mt-5">
											<div class="col-md-12 text-center">
												<button type="submit" class="btn btn-default">
													Update
												</button>

												<input type="reset" class="btn btn-secondary" placeholder="Reset">
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