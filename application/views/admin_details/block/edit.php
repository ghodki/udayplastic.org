<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">
				<div class="col-lg-12">
					<div class="card-header row">
						<div class="col-md-6">
							<h5>Edit District</h5>
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

					<?php
					if ($this->session->flashdata('msg')) { ?>
						<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
							<?php echo $this->session->flashdata('msg'); ?>
						</div>
					<?php } ?>

					<form action="<?= base_url() ?>district/update" method="post">
						<input type="hidden" name="id" id="id" placeholder="Bank Name" value="<?= $result->id; ?>" required>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="name">Districts Name</label>
									<input type="text" class="form-control" name="name" placeholder="Enter name" value=<?= $result->name ?>>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="name">State</label>
									<select name="state" id="state" class="form-control searchselect" style="width:100%">
										<option value="">Select State</option>
										<?php if ($state) {
											foreach ($state as $key => $row) { ?>
												<option value="<?= $row->id ?>" <?= $result->state_id == $row->id ? 'selected' : '' ?>><?= $row->state ?></option>
										<?php }
										} ?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-primary fr" value="Save">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
