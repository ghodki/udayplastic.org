<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 col-xl-12 col-lg-12">
					
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color:black;padding:10px 0px 10px 0px" class="text-center">ADD STATE</h2>

								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">
											<div class="col-md-4 col-sm-6 col-lg-4">

											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
												<div class="form-group">
													<label for="name">State Name</label>
													<input type="text" class="form-control" name="name" value="<?= set_value('name') ?>" placeholder="Enter Name">
												</div>
												<span class="control-label text-danger">
													<?php echo form_error('name'); ?>

												</span>
											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">

											</div>

											<div class="col-md-4 col-sm-6 col-lg-4">

											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
												<div class="form-group">
													<label for="name">State Code</label>
													<input type="text" class="form-control" value="<?= set_value('code') ?>" name="code" placeholder="Enter State Code">
												</div>
												<span class="control-label text-danger">
													<?php echo form_error('code'); ?>

												</span>
											</div>
											<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">

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
					<div class="container-fluid mb-9" style="margin-top:10px;">

						<div class="col">
							<div class="card shadow">
								<div class="card-header border-0">
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">State List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th>#</th>
													<!-- <th>Date</th> -->
													<th>State Name</th>
													<th>State Code</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if (isset($state) && $state != '') {
													foreach ($state as  $key => $cat) {
														// $date = $cat->created_at;
														// $newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<!-- <td><?= $newDate; ?></td> -->

															<td><?= $cat->state ?></td>
															<td><?= $cat->code ?></td>




															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<a href="<?= base_url('state/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>
																		<a href="<?= base_url('state/delete/') . $cat->id ?>" class="dropdown-item cp">Delete</a>


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
<script>
	$(function() {
		$("#dataTable").DataTable({
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
				'print',
				'pageLength',
			]

		});
	});
</script>