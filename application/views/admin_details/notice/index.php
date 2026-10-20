<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-blue pb-8 pt-5 pt-md-8">
						<?php if ($this->session->flashdata('msg')) { ?>
							<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
								<?php echo $this->session->flashdata('msg'); ?>
							</div>
						<?php

						}
						?>
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">ADD NOTICE</h2>
								<!-- Card stats -->
								<div class="row">

									<form method="POST" action="<?= base_url('notice/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">
											<div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
											</div>
											<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
												<label for="usertype" class="text-dark">Assign To</label>
												<select class="form-control" name="usertype" id="usertype" required>
													<option value="users">Users</option>
													<?php
													$role = $this->session->userdata('roles');
													if ($role == 1) {
													?>
														<option value="subadmin">Subadmin</option>
													<?php
													}
													?>
												</select>
											</div>
											<div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
											</div>
											<div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
											</div>
											<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
												<div class="form-group">
													<label for="name" class="text-dark">Notice</label>
													<textarea id="name" type="text" class="form-control" placeholder="TEXT" name="name" required="true"></textarea>
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
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
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Notice List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="18%">Date</th>
													<th scope="col" width="20%">Assign For</th>

													<th scope="col" width="50%">Description</th>
													<th scope="col" width="10%">Status</th>

													<th scope="col">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if (isset($notice) && $notice != '') {
													foreach ($notice as  $key => $cat) {
														$date = $cat->created_at;
														$newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<input type="hidden" id="status<?= $cat->id ?>" value="<?= $cat->status ?>">
															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<td><?= $newDate; ?></td>

															<td><?= $cat->usertype ?></td>

															<td><?= $cat->name ?></td>

															<td>
																<?php
																if ($cat->status == 1) {
																?>
																	<div class="form-check text-success">
																		<input class="form-check-input" type="checkbox" value="<?= $cat->id ?>" name="flexCheckDefault" id="flexCheckChecked<?= $cat->id ?>" checked>
																		<label class="form-check-label" for="flexCheckChecked">
																			Active
																		</label>
																	</div>
																<?php
																} else {
																?>
																	<div class="form-check text-danger">
																		<input class="form-check-input" type="checkbox" value="<?= $cat->id ?>" name="flexCheckDefault" id="flexCheckDefault<?= $cat->id ?>">
																		<label class="form-check-label" for="flexCheckDefault">
																			Inactive
																		</label>
																	</div>
																<?php
																}
																?>
															</td>
															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<a href="<?= base_url('notice/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>
																		<a href="<?= base_url('notice/delete/') . $cat->id ?>" class="dropdown-item cp">Delete</a>


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
	$('.form-check-input').change(function() {
		let notice_id = $(this).val();

		let status = $("#status" + notice_id).val();
		$.ajax({
			url: '<?php echo base_url('index.php/notice/statusChange'); ?>',
			type: 'POST',
			data: {
				notice_id: notice_id,
				status: status,
			},
			dataType: 'json',
			success: function(response) {
				console.log(response);
				if (response.status == 'success') {
					// let purchase_id = response.data;
					toastr.success(response.message);
					location.reload();
				} else {
					toastr.error(response.message);

				}


			}
		});
	});
</script>




<?php if ($this->session->flashdata('success')) { ?>
	toastr.success("<?php echo $this->session->flashdata('success'); ?>");
<?php } else if ($this->session->flashdata('error')) {  ?>
	toastr.error("<?php echo $this->session->flashdata('error'); ?>");
<?php } else if ($this->session->flashdata('warning')) {  ?>
	toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");
<?php } else if ($this->session->flashdata('info')) {  ?>
	toastr.info("<?php echo $this->session->flashdata('info'); ?>");
<?php } ?>


</script>