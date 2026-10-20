<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Events & News</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" action="<?= base_url('event/update'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">
											<input type="hidden" name="id" value="<?= $event->id; ?>">
											<div class="col-md-2 col-sm-6 col-lg-2">
											</div>

											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="createdAt" name="date" type="date" class="form-control" required="true" value="<?= $event->date; ?>">

												</div>
											</div>

											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="link" value="<?= $event->link; ?>" required="true">
												</div>
											</div>
											<div class="col-md-2 col-sm-6 col-lg-2">
											</div>
											<div class="col-md-2 col-sm-6 col-lg-2">
											</div>
											<div class="col-md-8 col-sm-6 col-lg-8">
												<div class="form-group">
													<textarea id="YourEditor" required name="event_name"><?= $event->content; ?></textarea>
												</div>

												<script>
													CKEDITOR.replace('YourEditor');
												</script>
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
