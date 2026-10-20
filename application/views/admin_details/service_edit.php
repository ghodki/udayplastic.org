<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Service</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>

									<?php echo $error; ?>

									<?php echo form_open_multipart('service/update'); ?>
									<div class="row">
										<input type="hidden" name="id" value="<?= $service->id; ?>">

										<div class="col-md-6  col-sm-12 col-lg-6">
											<div class="form-group">
												<div class="form-group">
													<textarea id="YourEditor1" required name="name"><?= $service->title; ?></textarea>
													<script>
														ClassicEditor.create(document.querySelector('#name'), {
                                                        }).catch(error => {
                                                            console.error(error);
                                                        });    
													</script>
												</div>

											</div>
										</div>



										<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

											<div class="form-group">

												<textarea id="text" required name="text"><?= $service->text; ?></textarea>
												<script>
													ClassicEditor.create(document.querySelector('#text'), {
                                                    }).catch(error => {
                                                        console.error(error);
                                                    });    
												</script>
											</div>
										</div>
										<div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
										</div>
										<div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
											<div class="form-group">
												<input type="file" class="form-control" value="<?= $service->image; ?>" name="image" size="20" />
											</div>
										</div>
									</div>
									<div class="row mt-5">
										<div class="col-md-12 text-center">
											<button type="submit" class="btn btn-default">
												Save
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
</div>
<script>
</script>
