<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-1">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Company</h2>
								<div class="row">
									<form  class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>khatabook/edit/<?= $result->id ?>" method="POST" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-6 col-sm-12 col-lg-6">
												<div class="form-group">
													<label for="company_name">Company Name<span>*</span></label>
													<input id="name" name="name" placeholder="Company Name" type="text" class="form-control" required="true" value="<?= set_value('name', $result->company_name) ?>">
													<span class="col-sm-4 control-label text-danger">
														<?php echo form_error('name'); ?>
													</span>
												</div>
											</div>
											<input type="hidden" name="company_id" value="<?= $result->id ?>">
											<div class="col-md-6  col-sm-12 col-lg-6">
												<div class="form-group">
													<label for="number">Mobile Number<span>*</span></label>
													<input id="number" name="number" placeholder="Mobile Number" type="text" class="form-control" required="true" value="<?= set_value('number', $result->number) ?>">
													<span class="col-sm-4 control-label text-danger">
														<?php echo form_error('number'); ?>
													</span>
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<label for="address">Address<span></span></label>
													<textarea name="address" type="text" class="form-control" required="true" value=""><?= set_value('address', $result->address) ?></textarea>
												</div>
											</div>
										</div>
										<div class="row mt-1">
											<div class="col-md-12 text-center">
												<button type="submit" class="btn btn-default">
													Update
												</button>
												<a href="#" type="reset" class="btn btn-secondary">Reset</a>
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