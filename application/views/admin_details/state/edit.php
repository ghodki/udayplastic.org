<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">

					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update State</h2>

								<!-- Card stats -->
								<div class="row">

									<form method="POST" action="<?= base_url() ?>state/update_state" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">
											<input type="hidden" name="state_id" value="<?= $result->id ?>">
											<div class="col-md-4 col-sm-6 col-lg-4">

											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
												<div class="form-group">
													<label for="name">State Name</label>
													<input type="text" class="form-control" name="name" value="<?= set_value('name', $result->state) ?>" placeholder="Enter Name">
												</div>
												<span class="control-label text-danger">
													<?php echo form_error('name'); ?>
													<?php if ($this->session->flashdata('msg')) { ?>
														<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
															<?php echo $this->session->flashdata('msg'); ?>
														</div>
													<?php

													}
													?>
												</span>
											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">

											</div>

											<div class="col-md-4 col-sm-6 col-lg-4">

											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
												<div class="form-group">
													<label for="name">State Code</label>
													<input type="text" class="form-control" value="<?= set_value('code', $result->code) ?>" name="code" placeholder="Enter State Code">
												</div>
												<span class="control-label text-danger">
													<?php echo form_error('code'); ?>

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
												<button type="button" class="btn btn-warning">
													<a href="<?= base_url() ?>state">Go Back</a>


												</button>

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