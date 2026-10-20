<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-blue pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Application Form</h2>
								<!-- Card stats -->
								<!-- <div class="row"> -->
								<?php if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
										<?php echo $this->session->flashdata('msg'); ?>
									</div>
								<?php

								}
								?>

								<?php echo $error; ?>

								<?php echo form_open_multipart('driver/update'); ?>
								<div class="row">
									<div class="col-md-3 col-sm-12">
										<input type="hidden" name="id" value="<?= $result->id ?>">
									</div>
									<div class="col-md-6 col-sm-12 col-lg-6">
										<div class="form-group">
											<textarea type="text" class="form-control" placeholder="Application Name" required name="app_name"><?= $result->app_name ?></textarea>
										</div>
									</div>
									<div class="col-md-3 col-sm-12"></div>
									<div class="col-md-3 col-sm-12"></div>

									<div class="col-md-6 col-sm-12 col-lg-6">

										<div class="form-group">
											<input type="file" class="form-control" name="image" size="20" />
										</div>
									</div>
									<div class="col-md-3 col-sm-12"></div>



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

