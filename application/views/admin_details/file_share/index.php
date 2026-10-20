<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">

			<div class="row mt-2">

				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="mt-5" style="margin-top:4em!important">

						<!-- <div class="container"> -->
						<div class="container-fluid">

							<div class="header-body ">
								<ul class="nav nav nav-tabs nav-pills">
									<li class="nav-link"><a data-toggle="pill" href="#all" onclick="refreshpage()">All</a></li>

									<?php
									$category = $this->user_model->getResult('categoryfilesharing');
									if (isset($category) && !empty($category)) {
										foreach ($category as $key => $value) {
									?>
											<li class="nav-link"><a data-toggle="pill" href="#aman<?= $key ?>"><?= $value->name ?></a></li>

										<?php
										}
										?>
								</ul>
								<br>
								<br>
								<div class="tab-content">
									<div id="all" class="tab-pane fade active show">

										<div class="container-fluid mb-9" style="margin-top:20px;">

											<div class="col">
												<div class="card shadow">
													<div class="card-header border-0">
														<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">File Shared List</label>
														<div class="row mt-5">
														</div>
														<div class="table-responsive">
															<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
																<thead class="thead-dark">
																	<tr>
																		<th>#</th>
																		<th>Date</th>
																		<th>Category</th>
																		<th>File Shared</th>
																		<th>Send By</th>
																		<!-- <th>Description</th> -->
																		<th>Status</th>
																		<th>Wallet</th>
																		<th>Amt</th>
																		<th>Reply</th>
																		<th>Action</th>


																	</tr>
																</thead>
																<tbody class="text-dark">
																	<?php
																	// print_r($sms_history);
																	// die;
																	if (isset($file_sharing) && $file_sharing != '') {
																		foreach ($file_sharing as  $key => $value) {
																			$date = $value->date;
																			$newDate = date("d-m-Y", strtotime($date));
																	?>
																			<tr>
																				<td>
																					<span><?= $key + 1; ?></span>
																				</td>
																				<td><?= $newDate; ?></td>
																				<td><?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>
																				</td>

																				<?php
																				$where = ['id' => $value->user_id];
																				$username = $this->user_model->getRow('users', $where);
																				?>
																				<td><a href="<?= base_url('uploads/file_share/') . $value->file_name; ?>" download>Download</a></td>
																				<?php
																				$where = ['id' => $value->user_id];
																				$sendby_name = $this->user_model->getRow('users', $where);
																				// echo $sendby_name->amount;
																				// die;
																				if ($value->role == 3) {
																					$send = "Send";
																				} else {
																					$send = "Recieve";
																				}
																				?>
																				<td><?= $sendby_name->name; ?><span><i>(<?php echo $send; ?>)</i>
																					</span></td>

																				<!-- <td><?= $value->des; ?></td> -->
																				<?php
																				if ($value->status == 0) {

																				?>
																					<td class="text-danger">Pending</td>
																				<?php
																				} else if ($value->status == 1) {
																				?>
																					<td class="text-success">Approved</td>

																				<?php
																				} else {
																				?>
																					<td class="text-danger" title="Know About Rejection">Rejected</td>

																				<?php
																				}
																				?>
																				<td class="success">
																					<?= get_title('users', ['id' => $value->user_id], 'amount'); ?>
																				</td>
																				<td>
																					<?php
																					if ($value->status == 0 && $this->session->userdata('user_id') != $value->user_id) {
																					?>
																						<input type="tel" min="0" id="amounts<?= $value->id ?>" value="" class="form-control">
																					<?php
																					} else {
																						echo $value->amount;
																					}
																					?>
																				</td>
<td>
<?php

if ($this->session->userdata('roles') == 3) {
	if ($value->status == 1) {
?>
		<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

	<?php
	}else
	{
		?>
		<a href="#" class="text-primary" title="Due to Not Approving" disabled>View Reply</a>

	<?php
	}
} else {
	?>
	<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

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
																							<?php

																							if ($this->session->userdata('roles') == 3) {
																								if ($value->status == 1) {
																							?>
																									<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																								<?php
																								}
																							} else {
																								?>
																								<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																							<?php
																							}
																							if ($this->session->userdata('user_id') == $value->user_id) {
																							?>
																								<!-- <a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>" class="dropdown-item cp text-primary">View</a> -->
																								<?php
																								if ($value->status == 0) {
																								?>
																									<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																									<!-- <a href="<?= base_url('file_sharing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->
																									<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																								<?php
																								} else {
																								?>
																									<a class="dropdown-item cp text-success">Approved</a>

																								<?php
																								}
																							} else {
																								?>

																								<!-- <a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>" class="dropdown-item cp text-primary">View</a> -->
																								<?php
																								if ($value->status == 0) {
																								?>
																									<a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
																									<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																									<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																								<?php
																								} else {
																								?>
																									<!-- <a data-toggle="modal" data-target="#exampleModal<?= $key ?>" class="dropdown-item cp text-warning">Edit</a> -->
																									<!-- <a onclick="return Edit('<?= base_url('wallet/edit') ?>',<?= $value->id ?>,<?= $value->user_id ?>)" class="dropdown-item cp">Edit</a> -->
																									<!-- <a href="<?= base_url('file_sharing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->
																									<!-- <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a> -->
																									<a onclick="return Decline('<?= base_url('file_sharing/decline') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>


																								<?php
																								}
																								?>
																							<?php
																							}
																							?>


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
									<?php
										$category = $this->user_model->getResult('categoryfilesharing');
										if (isset($category) && !empty($category)) {
											foreach ($category as $key => $categories) {
									?>
											<div id="aman<?= $key ?>" class="tab-pane fade">
												<h3><?= $categories->name ?></h3>
												<div class="header bg-blue pb-8 pt-5 pt-md-8">
													<div class="container-fluid">
														<div class="header-body ">
															<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">File Share</h2>
															<!-- Card stats -->
															<div class="row">
																<?php if ($this->session->flashdata('msg')) { ?>
																	<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
																		<?php echo $this->session->flashdata('msg'); ?>
																	</div>
																<?php

																}
																?>
																<?php
																if ($this->session->userdata('roles') == 4) {

																?>
																	<form method="POST" action="<?= base_url('file_sharing/fileshareemployye'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">

																	<?php
																} else {
																	?>
																		<form method="POST" action="<?= base_url('file_sharing/filesharesave'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">

																		<?php
																	}
																		?>
																		<div class="row">
																			<input type="hidden" name="id" value="<?= $user->id; ?>">
																			<?php
																			// $date=date("d/m/Y");
																			?>
																			<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																				<div class="form-group">
																					<input id="createdAt" name="date" type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" required="true">

																				</div>
																			</div>
																			<!-- <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">

																				<select id="category" name="category" class="form-control" required>
																					<option value=''>Select Category</option>
																					<?php
																					$cate = $this->user_model->getResult('Categoryfilesharing');
																					if ($cate) {
																						foreach ($cate as $key => $value) {
																					?>
																							<option value="<?= $value->id; ?>"><?= $value->name; ?></option>

																					<?php	}
																					}
																					?>


																				</select>

																			</div> -->
																			<input type="hidden" name="category" value="<?= $value->id ?>">
																			<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																				<div class="form-group">
																					<input type="file" class="form-control" required name="image" size="20" />
																				</div>
																			</div>
																			<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																				<div class="form-group">
																					<textarea id="name" type="text" class="form-control" name="name" required="true"></textarea>
																				</div>
																			</div>


																		</div>
																		<div class="row mt-5" style="margin-bottom: 32px;">
																			<div class="col-md-12 text-center">
																				<button type="submit" class="btn btn-default">
																					Send
																				</button>

																				<input type="reset" class="btn btn-secondary" placeholder="Reset">
																			</div>
																		</div>
																		</form>
															</div>
														</div>
													</div>
												</div>



												<div class="container-fluid mb-9" style="margin-top:20px;">

													<div class="col">
														<div class="card shadow">
															<div class="card-header border-0">
																<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">File Shared List</label>
																<div class="row mt-5">
																</div>
																<div class="table-responsive">
																	<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
																		<thead class="thead-dark">
																			<tr>
																				<th>#</th>
																				<th>Date</th>
																				<th>Category</th>
																				<th>File Shared</th>
																				<th>Send By</th>
																				<!-- <th>Description</th> -->
																				<th>Status</th>
																				<th>Wallet</th>
																				<th>Amt</th>
																				<th>Reply</th>
																				<th>Action</th>


																			</tr>
																		</thead>
																		<tbody class="text-dark">
																			<?php
																			// print_r($sms_history);
																			// die;

																			if (isset($file_sharing) && $file_sharing != '') {
																				foreach ($file_sharing as  $key => $value) {
																					if ($value->category == $categories->id) {
																						$date = $value->date;
																						$newDate = date("d-m-Y", strtotime($date));
																						// $this->user_model->getRor('')
																			?>

																						<tr>
																							<td>
																								<span><?= $key + 1; ?></span>
																							</td>
																							<td><?= $newDate; ?></td>
																							<td><?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>
																							</td>

																							<?php
																							$where = ['id' => $value->user_id];
																							$username = $this->user_model->getRow('users', $where);
																							?>
																							<td><a href="<?= base_url('uploads/file_share/') . $value->file_name; ?>" download>Download</a></td>
																							<?php
																							$where = ['id' => $value->user_id];
																							$sendby_name = $this->user_model->getRow('users', $where);
																							// echo $sendby_name->amount;
																							// die;
																							if ($value->role == 3) {
																								$send = "Send";
																							} else {
																								$send = "Recieve";
																							}
																							?>
																							<td><?= $sendby_name->name; ?><span><i>(<?php echo $send; ?>)</i>
																								</span></td>

																							<!-- <td><?= $value->des; ?></td> -->
																							<?php
																							if ($value->status == 0) {
																							?>
																								<td class="text-danger">Pending</td>
																							<?php
																							} else {
																							?>
																								<td class="text-success">Approved</td>

																							<?php
																							}
																							?>
																							<td class="success">
																								<?= get_title('users', ['id' => $value->user_id], 'amount'); ?>
																							</td>
																							<td>
																								<?php
																								if ($value->status == 0 && $this->session->userdata('user_id') != $value->user_id) {
																								?>
																									<input type="tel" min="0" id="amounts<?= $value->id ?>" value="" class="form-control">
																								<?php
																								} else {
																									echo $value->amount;
																								}
																								?>
																							</td>
																							<td>
																								<?php

																								if ($this->session->userdata('roles') == 3) {
																									if ($value->status == 1) {
																								?>
																										<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">View Reply</a>

																									<?php
																									}
																								} else {
																									?>
																									<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">View Reply</a>

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
																										<?php

																										if ($this->session->userdata('roles') == 3) {
																											if ($value->status == 1) {
																										?>
																												<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																											<?php
																											}
																										} else {
																											?>
																											<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																										<?php
																										}
																										if ($this->session->userdata('user_id') == $value->user_id) {
																										?>
																											<!-- <a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>" class="dropdown-item cp text-primary">View</a> -->
																											<?php
																											if ($value->status == 0) {
																											?>
																												<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																												<!-- <a href="<?= base_url('file_sharing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->
																												<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																											<?php
																											} else {
																											?>
																												<a class="dropdown-item cp text-success">Approved</a>

																											<?php
																											}
																										} else {
																											?>

																											<!-- <a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>" class="dropdown-item cp text-primary">View</a> -->
																											<?php
																											if ($value->status == 0) {
																											?>
																												<a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
																												<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																												<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																											<?php
																											} else {
																											?>
																												<!-- <a data-toggle="modal" data-target="#exampleModal<?= $key ?>" class="dropdown-item cp text-warning">Edit</a> -->
																												<!-- <a onclick="return Edit('<?= base_url('wallet/edit') ?>',<?= $value->id ?>,<?= $value->user_id ?>)" class="dropdown-item cp">Edit</a> -->
																												<!-- <a href="<?= base_url('file_sharing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->
																												<!-- <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a> -->
																												<a onclick="return Decline('<?= base_url('file_sharing/decline') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>


																											<?php
																											}
																											?>
																										<?php
																										}
																										?>


																									</div>
																								</div>
																							</td>

																						</tr>
																			<?php }
																				}
																			}

																			?>


																		</tbody>
																	</table>

																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p> -->


											</div>
								<?php
											}
										}
									}
								?>

								</div>
								<!-- <div class=""  -->


							</div>

						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>
<script>
	// $(document).ready(function() {
	// 	$('#dataTable').DataTable();
	// });

	// $('#all').on('click', function() {
	// 	$('li.active').removeClass('active'); // to remove the current active tab
	// 	$(this).addClass('active'); // add active class to the clicked tab
	// });
	function refreshpage() {
		window.location.reload();
	}
</script>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable();
	});

	// function refreshpage() {
	// 	// alert("hello");
	// 	window.location.href = '<?= base_url() ?>file_sharing';
	// }
</script>

<script>
	function Approve(url, id, user_id) {


		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "success",
			confirmButtonText: "Yes, Approve it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRow(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				});
			}
		});
	}

	function Approves(url, id, user_id) {


		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "success",
			confirmButtonText: "Yes, Approve it!",
		}).then((result) => {
			if (result.isConfirmed) {
				addRow(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				});
			}
		});
	}



	function Delete(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "danger",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "danger",
			confirmButtonText: "Yes, Delete it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRows(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				});
			}
		});
	}

	function Edit(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "warning",
			cancelButtonColor: "warning",
			confirmButtonText: "Yes, Edit it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRows(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				});
			}
		});
	}
</script>
<script>
	function addRow(url, id, user_id) {
		var amounts = document.getElementById('amounts' + id).value;
		// console.log(id, "id");
		$.ajax({
			type: "POST",
			url: url,
			data: {
				amount: amounts,
				id: id,
				user_id: user_id,

			},
			success: function(success) {
				success = JSON.parse(success);
				console.log(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
					setTimeout(function() {
						window.location.reload();
					}, 2000);
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
				}
			},
		});
	}

	function removeRow(url, id, user_id) {
		var amounts = document.getElementById('amount' + id).value;
		// console.log(id, "id");

		$.ajax({
			type: "POST",
			url: url,
			data: {
				amount: amounts,
				id: id,
				user_id: user_id,

			},
			success: function(success) {
				success = JSON.parse(success);
				console.log(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
					setTimeout(function() {
						window.location.reload();
					}, 2000);
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
				}
			},
		});
	}



	function removeRows(url, id, user_id) {
		// var amount = document.getElementById('amount' + id).value;
		// console.log(id, "id");
		// console.log(amount, "amount");


		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id,
				user_id: user_id,

			},
			success: function(success) {
				success = JSON.parse(success);
				console.log(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
					setTimeout(function() {
						window.location.reload();
					}, 2000);
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
				}
			},
		});
	}


	function Decline(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "success",
			showCancelButton: true,
			confirmButtonColor: "danger",
			cancelButtonColor: "success",
			confirmButtonText: "Yes, Decline it!",
		}).then((result) => {
			if (result.isConfirmed) {
				decline(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				});
			}
		});
	}

	function decline(url, id, user_id) {
		// var amount = document.getElementById('amount' + id).value;
		// console.log(id, "id");
		// console.log(amount, "amount");


		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id,
				user_id: user_id,
			},
			success: function(success) {
				success = JSON.parse(success);
				console.log(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
					setTimeout(function() {
						window.location.reload();
					}, 2000);
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
				}
			},
		});
	}
</script>
