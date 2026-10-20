<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Client</h2>
								<!-- Card stats -->
								<div class="row">

									<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>client/update" method="POST" enctype="multipart/form-data">


										<div class="row">
											<input type="hidden" name="company_id" value="<?= $com; ?>">
											<input type="hidden" name="id" value="<?= $client->id; ?>">

											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<input id="name" name="name" placeholder="Client Name" type="text" class="form-control" required="true" value="<?= $client->name ?>">
												</div>
											</div>

											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<input id="number" name="number" placeholder="Mobile Number" type="text" class="form-control" required="true" value="<?= $client->number ?>">
												</div>
											</div>
											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= $client->email ?>">
												</div>
											</div>
											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<textarea name="address" type="text" class="form-control" required="true" value=""><?= $client->address ?></textarea>
												</div>
											</div>


										</div>


										<div class="row mt-5">
											<div class="col-md-12 text-center">
												<button type="submit" class="btn btn-default">


													Update

												</button>
												<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
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