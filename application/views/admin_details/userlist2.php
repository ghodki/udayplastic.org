<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
					<?php
					if ($this->session->userdata('roles') != 3) { ?>
						<h1 class="h5 text-center">Create Account</h1>
						<?php if ($this->session->flashdata('msg')) { ?>
							<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
								<?php echo $this->session->flashdata('msg'); ?>
							</div>
						<?php
						} ?>

						<div class="row">
							<form id="registrationForm" class="col-sm-12 col-md-12 col-lg-12 jumbotron" action="<?= base_url('userlist/saveuser') ?>" method="POST">
								<div class="row">
									<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

										<div class="form-group">
											<select class="form-control" name="userType" id="usertype">
												<option value="2">Sub Admin</option>
												<option value="3">User</option>

											</select>
										</div>
									</div>
									<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">

										<div class="form-group">
											<input id="name" name="name" placeholder="Name" type="text" class="form-control" required="true" value="">
										</div>
										<span class="col-sm-4 control-label">
											<?php echo form_error('name', '<div class="alert alert-danger">', '</div>'); ?>
										</span>
									</div>

									<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

										<div class="form-group">
											<input id="password" name="password" placeholder="Password" type="password" class="form-control" required="true" value="">
											<span class="col-sm-4 control-label">
												<?php echo form_error('password', '<div class="alert alert-danger">', '</div>'); ?>
											</span>
										</div>
									</div>
									<!-- <div class="form-group">
                                    <input id="confPassword" name="confPassword" placeholder="Confirm Password" type="password" class="form-control" required="true" value="">
                                </div> -->
									<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

										<div class="form-group">
											<input id="number" name="number" placeholder="Number" type="text" class="form-control" required>
											<span class="col-sm-4 control-label">
												<?php echo form_error('number', '<div class="alert alert-danger">', '</div>'); ?>
											</span>
										</div>
									</div>
									<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

										<div class="form-group">
											<input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="">
											<span class="col-sm-4 control-label">
												<?php echo form_error('email', '<div class="alert alert-danger">', '</div>'); ?>
											</span>
										</div>
									</div>
									<!-- 
                                <div class="form-group d-none" id="otpDiv">
                                    <input id="otp" name="otp" placeholder="Enter OTP" type="text" class="form-control" required="true" value="">
                                </div> -->
									<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">

										<div class="form-group">
											<input id="ref" name="ref" required placeholder="Referral" type="text" class="form-control" onblur="validateReferral()" value="">
											<small id="refByTag"></small>
										</div>
									</div>
									<div class="col-md-6 col-md-6 col-sm-12 col-lg-6">
										<div class="form-group">
											<input id="gender1" name="gender" type="radio" value="Male" checked="checked"><label for="gender1">Male</label>


											<input id="gender2" name="gender" type="radio" value="Female"><label for="gender2">Female</label>
										</div>
									</div>
								</div>


								<div class="form-group">
									<input type="submit" class="form-control btn btn-primary" name="submit" value="Register">
								</div>
								<!-- <button id="btnRegister" class="btn btn-primary btn-block d-none" type="submit">Create
                                    Account
                                </button> -->
								<!-- <button id="btnOTP" class="btn btn-outline-dark btn-block" onclick="sendOTP();" type="button">Send OTP
                                </button> -->
								<!-- <button id="btnRegister" class="btn btn-primary btn-block d-none" type="button" onclick="validateSignup()">Create
                                    Account
                                </button> -->
							</form>
						</div>
					<?php
					} ?>
				</div>
			</div>
			<h1 class="text-center">User List</h1>
			<form id="userSearchCommand" method="post">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<input id="userName" name="name" placeholder="Full Name *" type="text" class="form-control" value="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input id="userNumber" name="number" placeholder="Mobile *" type="text" class="form-control" value="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input id="refBy" name="ref" placeholder="Ref By *" type="text" class="form-control" value="">
						</div>
					</div>
					<div class="col-md-3">
						<button type="submit" id="search" class="btn btn-default">Search</button>
						<button type="reset" onclick="cancel()" class="btn btn-secondary">Cancel
						</button>
					</div>
				</div>
			</form>
			<table class="table table-borderless table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Number</th>
						<th>SMS</th>
						<th>Referral Code</th>
						<th>User Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="tableData">
					<?php

					if (isset($userlist) && $userlist != '') {
						foreach ($userlist as $user) {
					?>
							<tr>
								<td><?= $user->name; ?></td>
								<td><?= $user->email; ?></td>
								<td><?= $user->number; ?></td>
								<?php
								$where = ['user_id' => $user->id];
								$username = $this->user_model->getRow('sms_details', $where);
								if ($username) {
									$rem = $username->remains_sms;
								} else {
									$rem = 0;
								}
								?>
								<td><?= $rem; ?></td>
								<td><?= $user->referral; ?></td>
								<td>
									<?php
									if ($user->userType == 1) {
										echo "Admin";
									} else {
										if ($user->userType == 2) {
											echo "Sub Admin";
										} else {
											echo "User";
										}
									} ?>

								</td>

								<td class="text-right">
									<div class="dropdown">
										<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fas fa-ellipsis-v"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
											<a href="<?= base_url('admin/view/') . $user->id ?>" class="dropdown-item cp">View</a>
											<?php
											if ($this->session->userdata('roles') != 3) {
											?>
												<a href="<?= base_url('admin/edit/') . $user->id ?>" class="dropdown-item cp">Edit</a>
												<a href="<?= base_url('admin/delete/') . $user->id ?>" class="dropdown-item cp">Delete</a>
												<?php
												if ($user->status) {
												?>
													<a href="<?= base_url('admin/block/') . $user->id ?>" class="dropdown-item cp">Block</a>

												<?php
												} else {
												?>
													<a href="<?= base_url('admin/unblock/') . $user->id ?>" class="dropdown-item cp">Unblock</a>

												<?php
												}
												?>
												<a href="<?= base_url('admin/sms/') . $user->id ?>" class="dropdown-item cp"> Add SMS</a>
												<a href="<?= base_url('admin/fileshare/') . $user->id ?>" class="dropdown-item cp">File Sharing</a>

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

<script type="text/javascript" language="javascript">



</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#userSearchCommand').submit(function(e) {
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: '<?php echo base_url('index.php/userlist/search') ?>',
				data: $(this).serialize(),
				success: function(data) {
					var json = JSON.parse(data);
					var da = json.data;
					if (da.length != 0) {
						document.getElementById('tableData').innerHTML = '';

						for (i = 0; da.length > i; i++) {
							console.log(da[i].name);
							var row = '<tr><td> ' + da[i].name + ' </td> <td> ' + da[i].email + ' </td> <td>' + da[i].number + '</td> <td>' + da[i].referral + '</td> </tr>'
							$("#tableData").append(row);

						}
					} else {
						window.location.reload();

					}

				},
				error: function(XMLHttpRequest) {
					alert(XMLHttpRequest.responseText);
				}
			});
		});
	});
</script>
<script>
	function cancel() {
		window.location.reload();
	}
</script>
