<style>
	.error {
		color: red;
	}
</style>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body">
								<!-- Card stats -->
								<?php if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
										<?php echo $this->session->flashdata('msg'); ?>
									</div>
								<?php

								}
								?>
								<?php
								$role = $this->session->userdata('roles');
								if ($role != 3) {
									if ($role != 4) { ?>
										<h1 class="h5 text-center">Create Account</h1>


										<div class="row">
											<?php
											$roles = $this->session->userdata('roles');
											if ($roles == 1) {
											?>
												<form id="registrationForm" class="col-sm-12 col-md-12 col-lg-12 jumbotron" action="<?= base_url('userlist/saveuser') ?>" method="POST">
												<?php
											} else {

												?>
													<form id="registrationForm" class="col-sm-12 col-md-12 col-lg-12 jumbotron" action="<?= base_url('userlist/savesubuser') ?>" method="POST">

													<?php
												}
													?>
													<div class="row">
														<div class="col-md-3 col-sm-12 col-lg-3">

															<div class="form-group">
																<label for="usertype">User Type:</label>

																<select class="form-control" name="userType" required id="usertype">
																	<option value="">--Select UserType--</option>

																	<option value="3">User</option>
																	<?php
																	if ($this->session->userdata('roles') != 1) {
																	?>
																		<option value="4">Employee</option>
																	<?php
																	} ?>
																	<?php
																	if ($this->session->userdata('roles') != 2) {
																	?>
																		<option value="2">Sub Admin</option>
																	<?php
																	}
																	?>

																</select>
															</div>
														</div>
														<div class="col-md-3 col-sm-12 col-lg-3">

															<div class="form-group">
																<label for="name">Name:</label>

																<input id="name" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
															</div>

															<span class="col-sm-4 control-label">
																<?php echo form_error('name'); ?>
															</span>
														</div>

														<div class="col-md-3 col-sm-12 col-lg-3">

															<div class="form-group">
																<label for="password">Password:</label>

																<input id="password" name="password" placeholder="Password" type="password" class="form-control" required="true" value="<?= set_value('password') ?>">
																<span class="col-sm-4 control-label">
																	<?php echo form_error('password'); ?>

																</span>
															</div>
														</div>


														<div class="col-md-3 col-sm-12 col-lg-3">

															<div class="form-group">
																<label for="number">Phone:</label>

																<input id="number" name="number" placeholder="Phone Number" type="text" class="form-control" value="<?= set_value('number') ?>" required>
																<span class="col-sm-4 control-label">
																	<?php echo form_error('number'); ?>

																</span>
															</div>
														</div>
														<div class="col-md-3 col-sm-12 col-lg-3">

															<div class="form-group">
																<label for="email">Email:</label>

																<input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= set_value('email') ?>">
																<span class="col-sm-4 control-label">
																	<?php echo form_error('email'); ?>

																</span>
															</div>
														</div>
														<div class="col-md-3 col-sm-12 col-lg-3">

															<div class="form-group">
																<label for="date">JOIN DATE:</label>

																<input id="createdAt" name="date" type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="true">
																<span class="col-sm-4 control-label">
																</span>
															</div>
														</div>
														<div class="col-md-3 col-sm-12 col-lg-3">
															<div class="form-group">
																<label for="gender">Gender:</label>
																<select class="form-control" id="gender" name="gender">
																	<!-- <option value="">--Select--</option> -->
																	<option value="Male">Male</option>
																	<option value="Female">Female</option>
																</select>
																</span>
															</div>
														</div>
														<div class="col-md-3 col-sm-12 col-lg-3" id="account_informations">

															<div class="form-group">
																<label for="permission_account_add">Account Add Permission</label>
																<select class="form-control" id="permission_account_add" name="permission_account_add">
																	<option value="">--Select--</option>
																	<option value="YES">YES</option>
																	<option value="NO">NO</option>
																</select>
																</span>
															</div>
														</div>

														<div class="col-md-3 col-sm-12 col-lg-3" id="userdata">
														</div>

													</div>

													<br>
													<br>
													<div class="form-group">
														<input type="submit" class="form-control btn btn-primary" name="submit" value="Register">
													</div>

													</form>
										</div>
							</div>

					<?php
									}
								} ?>
						</div>

					</div>
				</div>
			</div>
			<!-- Trigger the modal with a button -->

			<!-- Modal -->

			<div class="container-fluid mb-9" style="margin-top:100px;">

				<div class="col">
					<div class="card shadow">
						<div class="card-header border-0">
							<label class="mb-0" style="vertical-align: -moz-middle-with-baseline" class="text-center">User List</label>
							<div class="row mt-5">
							</div>
							<div class="table-responsive">
								<table class="table align-items-center table-flush" style="width: 100%" id="example_contra">
									<thead class="thead-light">
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Email</th>
											<th>Number</th>
											<!-- <th>SMS</th> -->
											<!-- <th>Referral Code</th> -->
											<th>User Type</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="tableData">
										<?php

										if (isset($userlist) && $userlist != '') {
											foreach ($userlist as $key => $user) {

										?>
												<div id="myModal<?= $user->id ?>" class="modal fade" role="dialog">
													<div class="modal-dialog">

														<!-- Modal content-->
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title text-center">Add Amount</h4>

																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<div class="modal-body">
																<form method="post" action="" onsubmit="OnSubmitHandler(<?= $user->id ?>)">
																	<div class="form-group">
																		<label for="amount">Amount:</label>
																		<input type="number" class="form-control" name="amount" id="amount<?= $user->id ?>" min="1" value="0">
																		<input type="hidden" class="form-control" name="user_id" id="user_id<?= $user->id ?>" value="<?= $user_id ?>">

																	</div>
																	<div class="form-group">
																		<label for="msg">Message:</label>
																		<textarea id="msg<?= $user->id ?>" class="form-control" name="msg<?= $user->id ?>"></textarea>
																	</div>
																	<button class="btn btn-primary">Add</button>
																</form>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															</div>
														</div>

													</div>
												</div>

												<div id="myModals<?= $user->id ?>" class="modal fade" role="dialog">
													<div class="modal-dialog">

														<!-- Modal content-->
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title text-center text-danger">Detect Amount</h4>

																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<div class="modal-body">
																<form method="post" action="" onsubmit="OnSubmitHandlers(<?= $user->id ?>)">
																	<div class="form-group">
																		<label for="amount">Amount:</label>
																		<input type="number" class="form-control" name="amount" id="amounts<?= $user->id ?>" min="1" value="0">
																	</div>
																	<input type="hidden" class="form-control" name="user_id" id="user_id<?= $user->id ?>" value="<?= $user_id ?>">
																	<div class="form-group">
																		<label for="msgs">Message</label>
																		<textarea id="msgs<?= $user->id ?>" class="form-control" name="msgs<?= $user->id ?>"></textarea>
																	</div>

																	<button class="btn btn-danger">Detect</button>
																</form>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															</div>
														</div>

													</div>
												</div>
												<tr>
													<td><?= $key + 1 ?></td>
													<!-- <td><a href="<?= base_url('userlist/see_details/') . $user->id ?>"><?= $user->name; ?></a></td> -->
													<td><a href="<?= base_url('userlist/logindirect/') . $user->id ?>"><?= $user->name; ?></a></td>

													<td><?= $user->email; ?></td>
													<td><?= $user->number; ?></td>
													<?php
													// $where = ['user_id' => $user->id];
													// $username = $this->user_model->getRow('sms_details', $where);
													// if ($username) {
													// 	$rem = $username->remains_sms;
													// } else {
													// 	$rem = 0;
													// }
													// 
													?>
													<!-- <td><?= $rem; ?></td> -->
													<!-- <td><?= $user->referral; ?></td> -->
													<td>
														<?php
														if ($user->userType == 1) {
															echo "Admin";
														} else {
															if ($user->userType == 2) {
																echo "Sub Admin";
															} else if ($user->userType == 3) {
																echo "User";
															} else {
																echo "Employee";
															}
														} ?>

													</td>

													<?php
													if ($user->status == 1) {
													?>
														<td class="text-success" onclick="chnageStatus(<?= $user->id ?>,0)">Active*</td>

													<?php
													} else {
													?>
														<td class="text-danger" onclick="chnageStatus(<?= $user->id ?>,1)">Inactive</td>

													<?php
													}
													?>

													<td class="text-right">
														<div class="dropdown">
															<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<i class="fas fa-ellipsis-v"></i>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																<a href="<?= base_url('admin/view/') . $user->id ?>" class="dropdown-item cp text-primary">View</a>
																<?php
																if ($this->session->userdata('roles') != 3) {
																?>
																	<a href="<?= base_url('admin/edit/') . $user->id ?>" class="dropdown-item cp text-warning">Edit</a>
																	<?php
																	if ($user->status) {
																	?>
																		<a href="<?= base_url('admin/block/') . $user->id ?>" class="dropdown-item cp text-danger">Block</a>

																	<?php
																	} else {
																	?>
																		<a href="<?= base_url('admin/unblock/') . $user->id ?>" class="dropdown-item cp text-success">Unblock</a>

																	<?php
																	}
																	?>
																	<?php
																	if ($user->inventory) {
																	?>
																		<a href="<?= base_url('admin/inventory_block/') . $user->id ?>" class="dropdown-item cp">Inventory Block</a>

																	<?php
																	} else {
																	?>
																		<a href="<?= base_url('admin/inventory_unblock/') . $user->id ?>" class="dropdown-item cp">Inventory Unblock</a>

																	<?php
																	}
																	?>
																	<a href="#" class="dropdown-item cp text-success" data-toggle="modal" data-target="#myModal<?= $user->id ?>"> Add Amount</a>
																	<a href="#" class="dropdown-item cp text-warning" data-toggle="modal" data-target="#myModals<?= $user->id ?>">Detect Amount</a>

																	<a href="<?= base_url('admin/delete/') . $user->id ?>" class="dropdown-item cp text-danger">Delete</a>





																	<a href="<?= base_url('admin/sms/') . $user->id ?>" class="dropdown-item cp text-success"> Add SMS</a>
																	<a href="<?= base_url('admin/fileshare/') . $user->id ?>" class="dropdown-item cp text-muted">File Sharing</a>
																	<a href="<?= base_url('userlist/see_details/') . $user->id ?>" class="dropdown-item cp text-primary">Transaction</a>

																<?php
																}
																?>


															</div>
														</div>
													</td>


												</tr>

										<?php }
										} else {
											echo "There Is no users";
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
	$(document).ready(function() {
		$('#example_contra').DataTable();
	});
</script>
<script type="text/javascript" language="javascript">
	function cancel() {
		window.location.href = "<?= base_url('userlist') ?>";
	}



	$(document).ready(function() {

		$('#usertype').change(function() {

			var party_id = $(this).val();
			console.log(party_id)
			// console.log(party_id, 'party_id');
			if (party_id) {
				if (party_id == 2) {

					document.getElementById('account_informations').style.display = "block";

				} else {
					document.getElementById('account_informations').style.display = 'none';

				}
				$.ajax({
					type: "POST",
					<?php
					if ($this->session->userdata('roles') == 2) {
					?>
						url: '<?= base_url('admin/referals'); ?>'
					<?php
					} else {
					?>
						url: '<?= base_url('admin/referal'); ?>'

					<?php
					} ?>,
					data: {
						usertype: party_id,

					},
					success: function(data) {
						if (data) {
							$("#userdata").html(data);

						} else {
							document.getElementById('userdata').innerHTML = '';

						}
					}

				});
			} else {
				document.getElementById('userdata').innerHTML = '';

			}

		})


	});
</script>
<script>
	function OnSubmitHandler(user_id) {
		// var amount = ('#amount' + user_id).val();
		// var user_id = ('#user_id' + user_id).val();
		// alert(user_id);
		var user_ids = user_id;
		var amount = document.getElementById('amount' + user_id).value;
		var msg = document.getElementById('msg' + user_id).value;

		if (amount > 0 && user_ids != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>userlist/amount",
				// data: $("#dataForm").serialize(),
				data: {
					amount: amount,
					user_id: user_ids,
					msg: msg
				},
				success: function(invoiceNo) {
					invoiceNo = JSON.parse(invoiceNo);
					console.log(invoiceNo, "dtaaaa");
					if (invoiceNo.status == true) {

						// invoiceData = invoiceNo.data;
						// cosnole.log(invoiceData);
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: 'Amount Added',
							showConfirmButton: false,
							timer: 2000
						})
						setTimeout(function() {
							window.reload();
						}, 2000)

					} else {
						Swal.fire({
							position: 'top-end',
							icon: 'error',
							title: invoiceNo.msg,
							showConfirmButton: false,
							timer: 2000
						})
					}

				},

			});
		} else {
			alert("Please Enter Amount");
		}
	}
</script>

<script>
	function OnSubmitHandlers(user_id) {
		// var amount = ('#amount' + user_id).val();
		// var user_id = ('#user_id' + user_id).val();
		// alert(user_id);
		var user_ids = user_id;
		var amount = document.getElementById('amounts' + user_id).value;
		var msg = document.getElementById('msgs' + user_id).value;

		if (amount >= 0 && user_ids != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>userlist/detect_amount",
				// data: $("#dataForm").serialize(),
				data: {
					amount: amount,
					user_id: user_ids,
					msg: msg
				},
				success: function(invoiceNo) {
					invoiceNo = JSON.parse(invoiceNo);
					console.log(invoiceNo, "dtaaaa");
					if (invoiceNo.status == true) {

						// invoiceData = invoiceNo.data;
						// cosnole.log(invoiceData);
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: 'Amount Deducted',
							showConfirmButton: false,
							timer: 2000
						})
						setTimeout(function() {
							window.reload();
						}, 2000)

					} else {
						Swal.fire({
							position: 'top-end',
							icon: 'error',
							title: invoiceNo.msg,
							showConfirmButton: false,
							timer: 2000
						})
					}

				},

			});
		} else {
			alert("Please Enter Amount");
		}
	}
</script>
<!-- <script type="text/javascript" language="javascript" >
 $(document).ready(function(){

  $('#example_contra').DataTable({
   "processing" : true,
   "serverSide" : true,
   "ajax" : {
    url:"<?= base_url() ?>/index.php/userlist",
    type:"POST"
   },
   dom: 'lBfrtip',
   buttons: [
    'excel', 'csv', 'pdf', 'copy'
   ],
   "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
  });
  
 });
 
</script> -->