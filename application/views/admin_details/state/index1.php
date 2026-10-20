<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">
				<div class="col-lg-12">
					<div class="card-header row">
						<div class="col-md-6">
							<h5>Add State</h5>
						</div>
						<div class="col-md-6">
							<a href="<?= base_url() ?>state/view" class="btn btn-info float-right"><i data-feather="arrow-left-circle" class="icon icon-size"></i>See List</a>
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
								<div class="col-md-3">

								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="name">State Name</label>
										<input type="text" class="form-control" name="name" placeholder="Enter Name">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<br>
										<input type="submit" class="btn btn-primary fr" value="Save">
									</div>
								</div>
							</div>
							<!-- /.card-body -->

						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
