<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-blue pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Social Media</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" action="<?= base_url('socialmedia/update'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">



											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="fb" placeholder="Facebook URL" value="<?= $socialmedia->fb ?>">
												</div>
											</div>
											<input type="hidden" name="id" value="<?= $socialmedia->id ?>">
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="insta" placeholder="Instagram URL" value="<?= $socialmedia->insta ?>">
												</div>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="twt" placeholder="Twitter URL" value="<?= $socialmedia->twt ?>">
												</div>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="linkdn" placeholder="LinkdIn URL" value="<?= $socialmedia->linkdn ?>">
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
