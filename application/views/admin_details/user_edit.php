<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
					<h1 class="h5 text-center">Profile Edit</h1>
					<button type="button" name="add" id="add" class="btn btn-primary">Add More</button>
					<br>
					<br>

					<form id="registrationForm" action="<?= base_url('admin/update') ?>" method="POST">
						<div class="row">
							<input name="id" type="hidden" class="form-control" value="<?= $userinfo->id; ?>">
							<input name="usertype" type="hidden" class="form-control" value="<?= $userinfo->userType; ?>">


							<div class="col-md-3 col-sm-12 col-lg-4">

								<div class="form-group">
									<input id="name" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?= $userinfo->name; ?>">
								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error('name', '<div class="alert alert-danger">', '</div>'); ?>
								</span>
							</div>

							<div class="col-md-3 col-sm-12 col-lg-4">

								<div class="form-group">
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
									<input id="number" name="number" placeholder="Number" type="text" class="form-control" value="<?= $userinfo->number ?>" required>
									<span class="col-sm-4 control-label">
										<?php echo form_error('number', '<div class="alert alert-danger">', '</div>'); ?>
									</span>
								</div>
							</div>


							<div class="col-md-3 col-sm-12 col-lg-4">

								<div class="form-group">
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
									<input name="date" type="date" class="form-control" value="<?= $orgDate; ?>" required="true">



								</div>
							</div>

							<div class="col-md-3 col-sm-12 col-lg-4">
								<div class="form-group">
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
							if ($userinfo->userType == 3) {
							?>
								<div class="col-md-3 col-sm-12 col-lg-4">
									<div class="form-group">
										<select id='referral' name="referral" required class="form-control">
											<?php
											if ($userinfo->referral != '') {
											?>
												<option value="<?= $userinfo->referral ?>">
												<?php
											} ?>
												<?php
												$where = ['userType' => 2, 'referral' => $userinfo->referral];

												$data = $this->user_model->getAry('users', '', '', '', $where);
												?>
												<?= $data[0]->name ?></option>
												<?php
												$where = ['userType' => 2];
												$data = $this->user_model->getAry('users', '', '', '', $where);
												foreach ($data as $ref) {
												?>
													<option value="<?= $ref->referral; ?>"><?= $ref->name; ?></option>
												<?php
												}
												?>

										</select> <!-- <input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= $userinfo->email ?>"> -->
									</div>
								</div>
							<?php
							}
							?>

							<br>

							<table class="table table-bordered table-hover" id="dynamic_field">
								<?php
								if ($userinfo->dynamic != '') {

									$decode = json_decode($userinfo->dynamic);
									// echo '<pre>';
									// print_r(count($decode));
									// die;
									// echo $decode[1]->label;
									// die;
									if (count($decode)) {
										foreach ($decode as $key => $data) {

								?>

											<tr id="row[<?= $key ?>]">
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


												<tr id="row[<?= $key ?>]">
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		<?php
		// if ($userinfo->dynamic != '') {
		// 
		?>
		// 	var i = <?= $key ?>;
		// $("#add").click(function() {
		// 	i++;
		// 	$('#dynamic_field').append('<tr id="row' + {
		// 		$key
		// 	} + '"><td><input type="text" name="key[]" placeholder="Enter Field Name" class="form-control name_list" required /></td><td><input type="text" name="data[]" required placeholder="Enter Field Value" class="form-control name_email"/></td><td> <div class = "form-check" ><label class = "form-check-label" ><input type = "radio" class = "form-check-input" value = "1" id="yes' + {
		// 		$key
		// 	} + '" name = "optradio[]" > Yes </label> </div> <div class = "form-check"><label class = "form-check-label"><input type = "radio" class = "form-check-input" id="no' + {
		// 		$key
		// 	} + '"	value = "0"	name = "optradio[]" > No </label></div> </td><td><button type="button" name="remove" id="' + {
		// 		$key
		// 	} + '" class="btn btn-danger btn_remove">X</button ></td></tr> ');
		// });
		<?php
		// } else {
		?>

		// var i = <?= $key ?>;

		var i = 0;
		$("#add").click(function() {
			console.log('aman', i);
			i++;
			$('#dynamic_field').append('<tr id="row' + i + '"><td><input type="text" name="key[]" placeholder="Enter Field Name" class="form-control name_list" required /></td><td><input type="text" name="data[]"  placeholder="Enter Field Value" class="form-control name_email"/></td><td> <div class = "form-check" ><label class = "form-check-label" ><input type = "radio" class = "form-check-input" value = "1" id="' + i + '"  name = "optradio[' + i + ']" > Yes </label> </div> <div class = "form-check"><label class = "form-check-label"><input type = "radio" class = "form-check-input" value = "0" checked	id= "' + i + '" name = "optradio[' + i + ']" > No </label></div> </td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button ></td></tr> ');
		});

		<?php
		?>




		$(document).on('click', '.btn_remove', function() {
			var button_id = $(this).attr("id");
			$('#row' + button_id + '').remove();
		});


	});
</script>
