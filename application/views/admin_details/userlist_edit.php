<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5 card" style="margin-top:5em !important">
				<div class="container">
				<div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
					<h1 class="h5 text-center">Profile Update</h1>
					<button type="button" name="add" id="add" class="btn btn-primary">Add More</button>
					<br>
					<br>
					<?php
					if ($this->session->userdata('roles') == 2) {
					?>
						<form id="registrationForm" action="<?= base_url('admin/updates') ?>" method="POST">

						<?php
					} else if ($this->session->userdata('roles') == 4) {
						?>
							<form id="registrationForm" action="<?= base_url('admin/emupdate') ?>" method="POST">

							<?php
						} else { ?>

								<form id="registrationForm" action="<?= base_url('admin/update') ?>" method="POST">
								<?php
							} ?>

								<div class="row">
									<input name="id" type="hidden" class="form-control" value="<?= $userinfo->id; ?>">
									<input name="usertype" type="hidden" class="form-control" value="<?= $userinfo->userType; ?>">


									<div class="col-md-3 col-sm-12 col-lg-4">

										<div class="form-group">
											<label for="name" class="h4 text-primary px-1">Name</label>
											<input id="name" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?= $userinfo->name; ?>">
										</div>
										<span class="col-sm-4 control-label">
											<?php echo form_error('name', '<div class="alert alert-danger">', '</div>'); ?>
										</span>
									</div>

									<div class="col-md-3 col-sm-12 col-lg-4">

										<div class="form-group">
											<label for="pass" class="h4 text-primary px-1">Password</label>
											<input id="password" name="password" placeholder="Password" type="text" class="form-control" required="true" value="<?= $userinfo->password; ?>">
											<span class="col-sm-4 control-label">
												<?php echo form_error('password', '<div class="alert alert-danger">', '</div>'); ?>
											</span>
										</div>
									</div>
									<!-- <div class="form-group">
                                    <input id="confPassword" name="confPassword" placeholder="Confirm Password" type="password" class="form-control" required="true" value="">
                                </div> -->
									<div class="col-md-3 col-sm-12 col-lg-4">

										<div class="form-group">
											<label for="number" class="h4 text-primary px-1">Mobile No</label>
											<input id="number" name="number" placeholder="Number" type="text" class="form-control" value="<?= $userinfo->number ?>" required>
											<span class="col-sm-4 control-label">
												<?php echo form_error('number', '<div class="alert alert-danger">', '</div>'); ?>
											</span>
										</div>
									</div>


									<div class="col-md-3 col-sm-12 col-lg-4">

										<div class="form-group">
											<label for="email" class="h4 text-primary px-1">Email</label>
											<input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= $userinfo->email ?>">
											<span class="col-sm-4 control-label">
												<?php echo form_error('email', '<div class="alert alert-danger">', '</div>'); ?>
											</span>
										</div>
									</div>
									<div class="col-md-3 col-sm-12 col-lg-4">
										<?php

										$orgDate = $userinfo->created_at;
										// $newDate = date("d-m-Y", strtotime($orgDate));
										// $d=strtotime("10:30pm April 15 2014");

										?>
										<div class="form-group">
											<label for="date" class="h4 text-primary px-1">Date</label>
											<input name="date" type="date" class="form-control" value="<?= $orgDate; ?>" required="true">



										</div>
									</div>

									<div class="col-md-3 col-sm-12 col-lg-4">
										<div class="form-group">
											<label for="gender" class="h4 text-primary px-1">Gender</label>
											<?php
											if ($userinfo->gender == "Male") {
											?>
												<div class="form-check">
													<label class="form-check-label">
														<input type="radio" checked class="form-check-input" value="Male" name="gender">Male
													</label>
												</div>
												<div class="form-check">
													<label class="form-check-label">
														<input type="radio" class="form-check-input" value="Female" name="gender">Female
													</label>
												</div>
											<?php
											} else {
											?>
												<div class="form-check">
													<label class="form-check-label">
														<input type="radio" class="form-check-input" value="Male" name="gender">Male
													</label>
												</div>
												<div class="form-check">
													<label class="form-check-label">
														<input type="radio" checked class="form-check-input" value="Female" name="gender">Female
													</label>
												</div>
											<?php
											}
											?>
										</div>
									</div>

									<?php
									// echo '<pre>';
									// print_r($userinfo);
									// die;
									if ($userinfo->userType == 3 && $this->session->userdata('roles') != 2 && $this->session->userdata('roles') != 4) {

									?>
										<div class="col-md-3 col-sm-12 col-lg-4">
											<div class="form-group">
												<label for="assign" class="h4 text-primary px-1">Assign</label>
												<select id='referral' name="referral" required class="form-control">

													<option value="">--Select--</option>
													<?php
													$where = ['userType' => 2, 'status' => 1];
													$data = $this->user_model->getAry('users', '', '', '', $where);
													// 	$there=['userid'=>$userinfo->id];
													// $subcat = $this->user_model->getRow('subcat',$there);
													// echo '<pre>';
													// 													print_r($subcat);
													// 													die;
													foreach ($data as $ref) {
													?>

														<option value="<?= $ref->id ?>" <?= $ref->referral == $userinfo->referral ? 'selected' : ''; ?>><?= $ref->name ?></option>

														<!-- <option value="<?= $ref->id; ?>"><?= $ref->name; ?></option> -->
													<?php
													}
													?>

												</select> <!-- <input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= $userinfo->email ?>"> -->
											</div>
										</div>




									<?php
									}

									if ($userinfo->userType == 2) {

									?>
										<div class="col-md-3 col-sm-12 col-lg-4" id="account_informations">

											<div class="form-group">
												<label for="permission_account_add">Account Add Permission</label>
												<select class="form-control" id="permission_account_add" name="permission_account_add">
													<option value="">--Select--</option>

													<option value="YES" <?= $userinfo->permission_account_add == 'YES' ? 'selected' : '' ?>>YES</option>
													<option value="NO" <?= $userinfo->permission_account_add == 'NO' ? 'selected' : '' ?>>NO</option>


												</select>
												</span>
											</div>
										</div>




									<?php
									}
									?>

									<br>


									<?php
									if ($this->session->userdata('roles') == 2 && $userinfo->userType != 4) {
										$code = $this->session->userdata('referral');
										$user_id = $this->session->userdata('user_id');
										$condition = ['userid' => $userinfo->id, 'usertype' => 4];
										$employee_code = $this->user_model->getRow('subcat', $condition);
										if ($employee_code) {
											$term = ['id' => $employee_code->emp_id, 'usertype' => 4];
											$users_details = $this->user_model->getRow('users', $term);
											if ($users_details) {
												$namesub = $users_details->name;
												$subid = $users_details->id;
											} else {
												$condition = ['userid' => $userinfo->id];
												$employee_code = $this->user_model->getRow('subcat', $condition);
												$subid = '';
												$namesub = '';
											}
										} else {
											$condition = ['userid' => $userinfo->id];
											$employee_code = $this->user_model->getRow('subcat', $condition);
											$subid = '';
											$namesub = '';
										}


										// echo '<pre>';
										// print_r($userinfo);
										// die;

									?>

										<div class="col-md-3 col-sm-12 col-lg-4">
											<div class="form-group">
												<select id='referral' name="referral" class="form-control">
													<option value="">--Select--</option>


													<?php
													// $this->db->join('credentials', 'tblanswers.answerid = credentials.cid', 'inner');
													$where = ['userType' => 4, 'status' => 1, 'referral' => $code];
													$data = $this->user_model->getAry('users', '', '', '', $where);
													$there = ['userid' => $userinfo->id];
													$subcat = $this->user_model->getRow('subcat', $there);
													// echo '<pre>';
													// 													print_r($subcat);
													// 													die;
													foreach ($data as $ref) {
													?>

														<option value="<?= $ref->id ?>" <?= $ref->id == $subcat->emp_id ? 'selected' : ''; ?>><?= $ref->name ?></option>

														<!-- <option value="<?= $ref->id; ?>"><?= $ref->name; ?></option> -->
													<?php
													}
													?>

												</select> <!-- <input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= $userinfo->email ?>"> -->
											</div>
										</div>


									<?php
									}
									?>


									<table class="table table-bordered table-hover" id="dynamic_field">

										<?php
										if ($userinfo->dynamic != '') {

											$decode = json_decode($userinfo->dynamic);
											// echo '<pre>';
											// print_r($decode);
											// die;
											// echo $decode[1]->label;
											// die;
											if ($decode != '' && $decode != null) {
												foreach ($decode as $key => $data) {

										?>

													<tr id="row<?= $key ?>">
														<td><input type="text" name="key[<?= $key ?>]" placeholder="Enter Field Name" class="form-control name_list" value="<?= $data->label; ?>"></td>
														<td><input type="text" name="data[<?= $key ?>]" placeholder="Enter Field Value" class="form-control name_email" value="<?= $data->value; ?>"></td>
														<td>
															<?php
															if ($data->status == 1) {
															?>
																<div class="form-check">
																	<label class="form-check-label">
																		<input type="radio" checked class="form-check-input" value="1" name="optradio[<?= $key ?>]">Yes
																	</label>
																</div>
																<div class="form-check">
																	<label class="form-check-label">
																		<input type="radio" class="form-check-input" value="0" name="optradio[<?= $key ?>]">No
																	</label>
																</div>
															<?php
															} else {
															?>
																<div class="form-check">
																	<label class="form-check-label">
																		<input type="radio" class="form-check-input" value="1" name="optradio[<?= $key ?>]">Yes
																	</label>
																</div>
																<div class="form-check">
																	<label class="form-check-label">
																		<input type="radio" checked class="form-check-input" value="0" name="optradio[<?= $key ?>]">No
																	</label>
																</div>
															<?php
															}
															?>
														</td>
														<?php

														?>

														<td><button type="button" name="remove" id="<?= $key ?>" class="btn btn-danger btn_remove">X</button></td>
													</tr>
													<?php
												}
											}
										} else {

											if ($other != '') {
												// echo '<pre>';
												// print_r($other);
												// die;
												$decode = json_decode($other[0]->extra_userdata);

												// echo $decode[1]->label;
												// die;
												if ($decode) {
													foreach ($decode as $key => $data) {
													?>


														<tr id="row<?= $key ?>">
															<td><input type="text" name="key[<?= $key ?>]" placeholder="Enter Field Name" class="form-control name_list" value="<?= $data->label; ?>"></td>
															<td><input type="text" name="data[<?= $key ?>]" placeholder="Enter Field Value" class="form-control name_email"></td>
															<td>

																<div class="form-check">
																	<label class="form-check-label">
																		<input type="radio" class="form-check-input" value="1" name="optradio[<?= $key ?>]">Yes
																	</label>
																</div>
																<div class="form-check">
																	<label class="form-check-label">
																		<input type="radio" checked class="form-check-input" value="0" name="optradio[<?= $key ?>]">No
																	</label>
																</div>

															</td>
															<td><button type="button" name="remove" id="<?= $key ?>" class="btn btn-danger btn_remove">X</button></td>
														</tr>
										<?php
													}
												}
											}
										}

										?>


									</table>

									<div class="form-group ml-2">
										<input type="submit" class="form-control text-primary" id="submit" name="submit" value="Update">
									</div>

								</form>
				</div>
	</div>
			</div>
			
		</div>
	</div>
</div>

</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		if (<?= $key ?>) {
			var i = "<?= $key ?>";
		} else {
			var i = 0;
		}


		$("#add").click(function() {
			console.log('aman', i);

			// console.log(j);
			i++;
			$('#dynamic_field').append('<tr id="row' + i + '"><td><input type="text" name="key[' + i + ']" placeholder="Enter Field Name" class="form-control name_list" required /></td><td><input type="text" name="data[' + i + ']"  placeholder="Enter Field Value" class="form-control name_email"/></td><td> <div class = "form-check" ><label class = "form-check-label" ><input type = "radio" class = "form-check-input" value = "1"   name = "optradio[' + i + ']" > Yes </label> </div> <div class = "form-check"><label class = "form-check-label"><input type = "radio" class = "form-check-input" value = "0" checked  name = "optradio[' + i + ']" > No </label></div> </td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button ></td></tr> ');
		});

		<?php
		?>




		$(document).on('click', '.btn_remove', function() {
			var button_id = $(this).attr("id");
			$('#row' + button_id + '').remove();
		});


	});
</script>