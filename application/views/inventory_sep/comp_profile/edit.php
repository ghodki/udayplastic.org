<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Company Profile</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>


									<form class="form" id="form" action="<?= base_url() ?>comp_profile/update" method="post">
										<div class="row">
											<input type="hidden" name="id" value="<?= $comp_profile->id ?>">
											<div class="col-md-6  col-sm-12 col-lg-6">
												<div class="form-group">


													<textarea id="YourEditor1" required name="name"><?= $comp_profile->name; ?></textarea>
													<script>
														CKEDITOR.replace('YourEditor1');
													</script>
												</div>
											</div>



											<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

												<div class="form-group">

													<textarea id="YourEditor" required name="content"><?= $comp_profile->content; ?></textarea>
													<script>
														CKEDITOR.replace('YourEditor');
													</script>
												</div>
											</div>
											<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

												<div class="form-group">

													<textarea id="YourEditor3" required name="bank_details"><?= $comp_profile->bank_details; ?></textarea>
													<script>
														CKEDITOR.replace('YourEditor3');
													</script>
												</div>
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
