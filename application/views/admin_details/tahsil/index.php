<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">
				<div class="col-lg-12">
					<div class="card-header row">
						<div class="col-md-6">
							<h5>Add District</h5>
						</div>
						<div class="col-md-6">
							<a href="<?= base_url() ?>district/view" class="btn btn-info float-right"><i data-feather="arrow-left-circle" class="icon icon-size"></i>See List</a>
						</div>
					</div>
					<?php
					if (validation_errors()) { ?>
						<div class="alert alert-danger">
							<?= validation_errors(); ?>
						</div>
					<?php } ?>

					<form name="formdata" method="post">
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="name">State</label>
										<select name="state" id="state" class="form-control searchselect" style="width:100%">
											<option value="">Select State</option>
											<?php if ($state) {
												foreach ($state as $key => $row) { ?>
													<option value="<?= $row->id ?>"><?= $row->state ?></option>
											<?php }
											} ?>
										</select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="name">Districts Name</label>
										<input type="text" class="form-control" name="name" placeholder="Enter Name">
									</div>
								</div>


							</div>
							<!-- /.card-body -->
							<div class="form-group">
								<input type="submit" class="btn btn-primary fr" value="Save">
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
