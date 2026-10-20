<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add MONTH</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" action="<?= base_url('months/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">


											<div class="col-md-4  col-sm-6 col-lg-4">

											</div>
											<div class="col-md-4  col-sm-6 col-lg-4">
												<div class="form-group">Month Name:
													<input id="emonth" type="text" class="form-control" name="month" placeholder=" Month name " required="true">
												</div>
											</div>
											<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">

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
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Add MONTH List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-dark">
												<tr>
													<th scope="col">#</th>
													<th scope="col"> Month</th>

													<!-- <th>Action</th> -->

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php
												// print_r($month);
												// die;
												if (isset($month) && $month != '') {
													foreach ($month as  $key => $value) {
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>


															<td><?= $value->month ?></td>

															<td>
																<!-- <a href="<?= base_url('month/edit/') . $value->id ?>" class="text-info" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a> -->
																<!-- | <a href="<?= base_url('month/delete/') . $value->id ?>" onclick="return confirm('Are you sure you want to delete the user?');" class="text-danger" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a> -->
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
</div>
