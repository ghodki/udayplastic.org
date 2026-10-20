<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-2">
				<div class="col-md-12 col-xl-12 col-lg-12">

					<div class="header bg-blue pb-8 pt-5 pt-md-8">



						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Replying</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									if ($file_sharing->user_id == $this->session->userdata('user_id')) {
									?>
										<form method="POST" action="<?= base_url('file_sharing/savefile_in_chatting_for_user'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">

										<?php
									} else {
										?>

											<form method="POST" action="<?= base_url('file_sharing/savefile_in_chatting'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">
											<?php
										}
											?>
											<div class="row">
												<?php
												date_default_timezone_set('Asia/Kolkata');

												?>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<label for="date">Date</label>
														<input id="createdAt" name="date" type="text" class="form-control" value="<?= date('d-m-Y h:ia') ?>" readonly required="true">

													</div>
												</div>
												<input type="hidden" name="file_id" value="<?= $file_sharing->id ?>">
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6 field_wrapper">
													<div class="form-group">
														<label for="wallet">Wallet Balance:</label>
														<input type="number" min="0" autocomplete="off" readonly class="form-control" value="<?= get_title('users', ['id' => $file_sharing->user_id], 'amount') ?>" name="amount">

													</div>
												</div>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6 field_wrapper">
													<div class="form-group">

														<label for="amount">Charge Amount</label>
														<input type="hidden" name="user_id" value="<?= $file_sharing->user_id ?>">
														<input type="number" min="0" autocomplete="off" class="form-control" value="<?= $file_sharing->amount ?>" name="amount">

													</div>
												</div>

												<div class="col-md-5 col-sm-6 col-lg-5 field_wrapper">

													<div class="form-group">
														<label for="file">File</label>
														<input type="file" class="form-control" name="field_name[]" multiple="" />

													</div>
												</div>



												<div class="col-md-1 col-sm-1 col-lg-1">
													<div class="form-group">
														<a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field"><span style="font-size:20px;">+</span></a>


													</div>
												</div>
													<div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
													<div class="form-qty">
														<label for="remark">Qty</label>
														<input type="number" class="form-control" name="qty" id="qty" />

													</div>
												</div>
												<div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
													<div class="form-group">
														<label for="remark">Remark</label>
														<textarea id="name" type="text" class="form-control" name="name" required="true"></textarea>
														<script>
														CKEDITOR.replace('name');
													</script>
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
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Chatting</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-dark">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="10%">Date</th>

													<!-- <th scope="col" width="20%">User Name</th> -->
													<!-- <th scope="col" width="20%">Category Name</th> -->

													<th scope="col" width="40%">File</th>
													<th scope="col" width="13%">Status</th>
													<th scope="col" width="35%">Message</th>
													<th>Action</th>

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php

												if (isset($chat) && $chat != '') {
													foreach ($chat as  $key => $value) {

														$date = $value->date;
														// $newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<td><?= $date; ?></td>
															<td>
																<?php
																$user_id = $this->session->userdata('user_id');
																// $where = ['id' => $value->user_id];
																// $username = $this->user_model->getRow('users', $where);
																?>

																<?php
																if ($value->file_name != '') {

																	$decode = json_decode($value->file_name);


																	if ($decode != '' && $decode != null) {
																		foreach ($decode as $key => $data) {
																			$file_name = $data->file_name;
																?>
																			<a href="<?= base_url('uploads/filechat/') . $file_name; ?>" download>Download</a>,
																<?php
																		}
																	}
																}
																?>
															</td>
															<?php
															if ($user_id == $value->user_id) {
															?>
																<td class="text-primary">Sent</td>
															<?php
															} else {
															?>
																<td class="text-success">Received</td>

															<?php
															}
															?>
															<td><?= $value->msg; ?></td>

															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<?php
																		if ($this->session->userdata('user_id') == $value->user_id) {
																		?>
																			<a href="<?= base_url('file_sharing/editFileReply/' . $value->id) ?>" class="dropdown-item cp text-primary">Edit</a>
																			<?php
																			if ($this->session->userdata('roles') == '3') {
																			?>
																				<a href="<?= base_url('file_sharing/deleteChatFile/' . $value->id) ?>" class="dropdown-item cp text-danger">Delete</a>

																			<?php
																			} else {
																			?>
																				<a href="<?= base_url('file_sharing/del/' . $value->id) ?>" class="dropdown-item cp text-danger">Delete</a>

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
				<style>
					.pata_nahi_bhai {
						position: fixed;
						left: 0;
						bottom: 0;
						width: 100%;
						background-color: black;
						color: white;
						text-align: center;
					}
				</style>
				<div class="col-md-12 pata_nahi_bhai">
					<h4 class="text-danger text-center">Note:Only 20 Files can be uploaded and same type of files in single time.</h4>
				</div>


			</div>

		</div>
	</div>

</div>

<script>
	function refreshpage() {
		window.location.href = '<?= base_url() ?>file_sharing';
	}
</script>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable();
	});

	function refreshpage() {
		// alert("hello");
		window.location.href = '<?= base_url() ?>file_sharing';
	}
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



	function Delete(url) {
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
				removeRows(url);
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
<script type="text/javascript">
	$(document).ready(function() {
		var maxField = 50; //Input fields increment limitation
		var addButton = $('.add_button'); //Add button selector
		var wrapper = $('.field_wrapper'); //Input field wrapper
		var fieldHTML = '<div class="form-group"><input type="file" name="field_name[]" multiple class="form-control" value=""><a href="javascript:void(0);" class="remove_button"><span style="font-size:20px;" class="btn btn-danger form-inline">-</span></a></div>'; //New input field html 
		var x = 1; //Initial field counter is 1

		//Once add button is clicked
		$(addButton).click(function() {
			//Check maximum number of input fields
			if (x < maxField) {
				x++; //Increment field counter
				$(wrapper).append(fieldHTML); //Add field html
			}
		});

		$(wrapper).on('click', '.remove_button', function(e) {
			e.preventDefault();
			$(this).parent('div').remove();
			x--;
		});
	});
</script>