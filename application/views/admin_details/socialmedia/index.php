<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-blue pb-8 pt-5 pt-md-8">
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
									<form method="POST" action="<?= base_url('socialmedia/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">



											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="fb" placeholder="Facebook URL">
												</div>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="insta" placeholder="Instagram URL">
												</div>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="twt" placeholder="Twitter URL">
												</div>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" name="linkdn" placeholder="LinkdIn URL">
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
					<div class="container-fluid mb-9" style="margin-top:100px;">

						<div class="col">
							<div class="card shadow">
								<div class="card-header border-0">
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Events And News</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="20%">Facebook</th>
													<th scope="col" width="20%">Instagram</th>

													<th scope="col" width="20%">Twitter</th>
													<th scope="col" width="20%">LinkdIn</th>

													<th scope="col" width="8%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if (isset($socialmedia) && !empty($socialmedia)) {
													foreach ($socialmedia as  $key => $date) {
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>
												
															<td><a href="<?= $date->fb; ?>"><?= $date->fb; ?></td>
															<td><a href="<?= $date->insta; ?>"><?= $date->insta; ?></td>
															<td><a href="<?= $date->twt; ?>"><?= $date->twt; ?></td>

															<td><a href="<?= $date->linkdn; ?>"><?= $date->linkdn; ?></td>
															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<a href="<?= base_url('socialmedia/edit/') . $date->id ?>" class="dropdown-item cp">Edit</a>
																		<a href="<?= base_url('socialmedia/delete/') . $date->id ?>" class="dropdown-item cp">Delete</a>


																	</div>
																</div>
															</td>
														</tr>

												<?php }
												}

												?>


											</tbody>
										</table>
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
