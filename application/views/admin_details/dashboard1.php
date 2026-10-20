<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">

			<div class="row mt-5">
				<div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">

					<div class="header bg-gradient-green  pt-5 pt-md-8" style="padding-bottom:40px;">
						<div class="container-fluid">
							<div class="header-body">
								<!-- Card stats -->
								<div class="row">

									<div class="col-xl-3 col-lg-6">
										<div class="card card-stats mb-4 mb-xl-0">
											<div class="card-body">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">
															<?php
															if ($this->session->userdata('roles') == 1) {
																$where = ['userType' => 3, 'status' => 1];
																// print_r($where);
																// die;
																$user = $this->user_model->getAry('users', '', '', '', $where);
																// $user = $this->user_model->getArys('users');
																$count = count($user);
															?>
																<span>Active User</span>
																<br>
																<span class="h2 font-weight-bold mb-0"><?= $count ?></span>


																<?php
															} else {
																if ($this->session->userdata('roles') == 2) {
																	$sub = $this->session->userdata('referral');
																	$where = ['referral' => $sub, 'userType!=' => 2, 'status' => 1, 'userType' => 3];

																	$user = $this->user_model->getAry('users', '', '', '', $where);


																	$countdown = count($user);
																?>
																	<span>Active User</span>
																	<br>
																	<span class="h2 font-weight-bold mb-0"><?= $countdown ?></span>

																<?php
																} else if ($this->session->userdata('roles') == 3) {

																?>
																	<span> SMS</span>
																	<br>
																	<span class="h2 font-weight-bold mb-0">45</span>

																<?php
																} else {
																	$sub = $this->session->userdata('user_id');
																	$where = ['status' => 1, 'emp_id' => $sub, 'usertype' => 4];

																	$user = $this->user_model->getAry('subcat', '', '', '', $where);
																	$count = count($user);
																?>
																	<span> Active User</span>
																	<br>
																	<span class="h2 font-weight-bold mb-0"><?= $count; ?></span>

															<?php
																}
															}
															?>
														</h5>

													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle">
															<i class="fas fa-users" style="width:30px;height:30px;"></i>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
									<div class="col-xl-3 col-lg-6">
										<div class="card card-stats mb-4 mb-xl-0">
											<div class="card-body">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">
															<?php
															if ($this->session->userdata('roles') == 1) {
																$where = ['userType' => 3, 'status' => 1];
																// print_r($where);
																// die;
																$user = $this->user_model->getAry('users', '', '', '', $where);
																// $user = $this->user_model->getArys('users');
																$count = count($user);
															?>
																<span>Total Filling</span>
																<br>
																<span class="h2 font-weight-bold mb-0"><?= $count ?></span>


															<?php

															} else if ($this->session->userdata('roles') == 2) {
																// $sub = $this->session->userdata('referral');
																// $where = ['referral' => $sub, 'userType' != 2, 'status' => 1];
																// $user = $this->user_model->getAry('users', '', '', '', $where);

																// $where = ['userType' => 3, 'status' => 1];
																// print_r($where);
																// die;
																// $uu = $this->user_model->getAry('users', '', '', '', $where);
																// $user = $this->user_model->getArys('users');
																// $counnumbert = count($user);
															?>
																<span>Total Filling</span>

																<br>
																<span class="h2 font-weight-bold mb-0"><?= $countdown ?></span>

															<?php
															} else if ($this->session->userdata('roles') == 3) {

															?>
																<span> SMS</span>
																<br>
																<span class="h2 font-weight-bold mb-0">45</span>

															<?php
															} else {
																$sub = $this->session->userdata('user_id');
																$where = ['status' => 1, 'emp_id' => $sub, 'usertype' => 4];

																$user = $this->user_model->getAry('subcat', '', '', '', $where);
																$count = count($user);
															?>
																<span>Total Filling</span>
																<br>
																<span class="h2 font-weight-bold mb-0"><?= $count; ?></span>

															<?php
															}

															?>
														</h5>

													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle">
															<i class="fas fa-users" style="width:30px;height:30px;"></i>

														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class="col-xl-3 col-lg-6">
										<div class="card card-stats mb-4 mb-xl-0">
											<div class="card-body">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">
															<?php
															if ($this->session->userdata('roles') == 1) {
																// $default_year = $this->session->userdata('default');
																$newdate = date("Y-n", strtotime("-4 months"));

																$where = ['month' => $newdate, 'status' => 1, 'gst' => 3];
																// print_r($where);
																// die;
																$year = $this->user_model->getArys('finencial_year', '', '', '', $where);
																$total = count($year);
																$notfileed = intval($count) - intval($total);
																// print_r($total);
																// die;
															?>
																<span>Filled</span>
																<br>
																<span class="h2 font-weight-bold mb-0"><?= $total; ?></span>

															<?php
															} else if ($this->session->userdata('roles') == 2) {
																// $default_year = $this->session->userdata('default');
																$sub = $this->session->userdata('user_id');
																$where = ['referral_id' => $sub];
																$user = $this->user_model->getAry('subcat', '', '', '', $where);
																if ($user) {
																	foreach ($user as $dt) {
																		$user_id = $dt->userid;
																		$newdate = date("Y-n", strtotime("-4 months"));
																		$where = ['month' => $newdate, 'status' => 1, 'gst' => 3, 'userid' => $user_id];
																		$year = $this->user_model->getAry('finencial_year', '', '', '', $where);
																		if ($year) {
																			$total[] = $year;
																		}

																		// $notfileed = intval($count) - intval($total);
																		// $total = count($total);
																	}
																	if (isset($total) && $total != '') {
																		$total = count($total);
																	} else {
																		$total = 0;
																	}
																} else {
																	$total = 0;
																}

																$not = intval($countdown) - intval($total)

															?>
																<span>Filled</span>
																<br>
																<span class="h2 font-weight-bold mb-0"><?= $total ?></span>

															<?php
															} else if ($this->session->userdata('roles') == 3) {

															?>
																<span> SMS</span>
																<br>
																<span class="h2 font-weight-bold mb-0">45</span>

															<?php
															} else {
																$sub = $this->session->userdata('user_id');
																$where = ['emp_id' => $sub];
																$user = $this->user_model->getAry('subcat', '', '', '', $where);
																if ($user) {
																	foreach ($user as $dt) {
																		$user_id = $dt->userid;
																		$newdate = date("Y-n", strtotime("-4 months"));
																		$where = ['month' => $newdate, 'status' => 1, 'gst' => 3, 'userid' => $user_id];
																		$year = $this->user_model->getAry('finencial_year', '', '', '', $where);
																		if ($year) {
																			$total[] = $year;
																		}

																		// $notfileed = intval($count) - intval($total);
																		// $total = count($total);
																	}
																	if (isset($total) && $total != '') {
																		$total = count($total);
																	} else {
																		$total = 0;
																	}
																} else {
																	$total = 0;
																}

																$notfileees = intval($count) - intval($total)

															?>
																<span>Filled</span>
																<br>
																<span class="h2 font-weight-bold mb-0"><?= $total ?></span>

															<?php
															}

															?>
														</h5>

													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle">
															<i class="fas fa-users" style="width:30px;height:30px;"></i>

														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class="col-xl-3 col-lg-6">
										<div class="card card-stats mb-4 mb-xl-0">
											<div class="card-body">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">
															<?php
															if ($this->session->userdata('roles') == 1) {

															?>
																<span>Not Filled</span>
																<br>
																<span class="h2 font-weight-bold mb-0"><?= $notfileed ?></span>


																<?php
															} else {
																if ($this->session->userdata('roles') == 2) {
																?>
																	<span>Not Filled</span>
																	<br>
																	<span class="h2 font-weight-bold mb-0"><?= $not ?></span>

																<?php
																} else if ($this->session->userdata('roles') == 3) {

																?>
																	<span> SMS</span>
																	<br>
																	<span class="h2 font-weight-bold mb-0">45</span>

																<?php
																} else {

																?>
																	<span>Not Filled</span>
																	<br>
																	<span class="h2 font-weight-bold mb-0"><?= $notfileees; ?></span>


															<?php
																}
															}
															?>
														</h5>

													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle">
															<i class="fas fa-users" style="width:30px;height:30px;"></i>

														</div>
													</div>
												</div>

											</div>

										</div>
										<br>
										<?php
										if ($this->session->userdata('roles') != 3) {

										?>
											<span class="btn btn-light" style="margin-left:170px;"><a href="<?= base_url('dashboard/viewall') ?>">View All</a></span>
										<?php
										}
										?>
									</div>

									<?php


									?>



									<?php
									if ($this->session->userdata('roles') == 3) {
									?>
										<div class="col-md-12">

											<div class="container-fluid">
												<div class="header-body">
													<br>
													<br>
													<h1 class="text-center">Finencial Year</h1>
													<br>
													<div class="row">
														<div class="col-md-2"></div>
														<div class="col-md-3">
															<select id='gst' required class="form-control">
																<option value="">-- Select GST --</option>
																<option value="1">GSTR 1</option>
																<option value="3">GSTR 3B</option>

															</select>
														</div>
														<div class="col-md-3">
															<select id='year' class="form-control">
																<option>-- Select Year --</option>
																<?php
																foreach ($year as $years) {
																	echo "<option value='" . $years->id . "'>" . $years->year . "</option>";
																}
																?>
															</select>
														</div>


													</div>

												</div>
											</div>
											<br>
											<br>
										</div>

										<br>
										<div class="conatiner">



											<div class="row">
												<div class="col-md-3"></div>
												<div class="col-md-6">
													<h1 class="text-center" style="text-align:center">GST</h1>
												</div>
												<div class="col-md-3"></div>

											</div>
											<div class="container">
												<div class="row" id="cl">


												</div>
											</div>
										</div>
									<?php
									} ?>
								</div>
							</div>

						</div>
					</div>
					<div class="header-footer">
						<?php
						$roles = $this->session->userdata('roles');
						$user_id = $this->session->userdata('user_id');

						// role 1=Admin
						// role 2 = subadmin
						// role 3 =user
						// role 4 =Employye

						if ($roles == 2) {
							$where = ['usertype' => 'subadmin', 'status' => 1];
						} else if ($roles == 3) {
							// $subamdin_id=get_title('subcat',['userid'=>$user_id],'referral_id');
							$where = ['usertype' => 'users', 'status' => 1];
						} else {
							$where = ['status' => 1];
						}
						$notice = $this->user_model->getAryss('notice', '', '', '', $where);
						if ($notice) {
						?>
							<div class="bg-info-alt row">
								<i class="fas fa-envelope pt-1 text-gray-dark"></i>
								<span class="ml-1 p-0 text-danger" style="width: 97%">
									<marquee width="100%" id="notification">
										<a class="dropdown-item" data-toggle="modal" data-target="#jaisa_mera_mai">
											<!-- <a data-toggle="modal" data-target="#jaisa_mera_mai<?= $key ?>" onclick="userNotification($this->)" class="dropdown-item"> -->
											<?= $notice[0]->name ?>
										</a>
									</marquee>
								</span>
							</div>
						<?php
						}

						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="jaisa_mera_mai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="text-center">Top Notice</h2>

			</div>
			<div class="modal-body">
				<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
					<?php
					$notify = $this->user_model->getAryss('notice', '', '', 10, $where);
					if ($notify) {
						foreach ($notify as $key => $notification) {
							$date = $notification->created_at;
							$newDate = date("d-m-Y h:i", strtotime($date));
					?>
							<tr>
								<th><?= $newDate ?></th>
								<th class="text-danger"><?= $notification->name ?></th>
							</tr>
					<?php
						}
					}
					?>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#jaisa_mera_mai_jaise_mera_tu">See All</button>


			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="jaisa_mera_mai_jaise_mera_tu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#jaisa_mera_mai">See Top</button> -->
				<h2 class="text-center">All Notice</h2>
			</div>
			<div class="modal-body">
				<!-- <table> -->
				<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">

					<?php
					$notify = $this->user_model->getAryss('notice', '', '', '', $where);

					if ($notify) {
						foreach ($notify as $key => $notification) {
							$date = $notification->created_at;
							$newDate = date("d-m-Y h:i", strtotime($date));
					?>
							<tr>
								<th><?= $newDate ?></th>

								<th class="text-danger"><?= $notification->name ?></th>
							</tr>

					<?php
						}
					}
					?>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<!-- <button type="button" class="btn btn-secondary">See Top</button> -->


			</div>
		</div>
	</div>
</div>

<?php
$where = ['user_id' => $this->session->userdata('user_id'), 'watch' => 'no'];
$counter = $this->user_model->getAryss('notice_file', '', '', '', $where);

if (count($counter)) {
?>
	<div class="modal fade" id="auto_load" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="text-center">File Sharing Notice</h2>

				</div>
				<div class="modal-body">
					<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
						<tr>
							<th>DATE</th>
							<th>CATEGORY</th>
							<th>AMOUNT DBT</th>
							<th>MSG</th>

						</tr>
						<?php
						$where = ['user_id' => $this->session->userdata('user_id'), 'watch' => 'no'];
						$notify = $this->user_model->getAryss('notice_file', '', '', 10, $where);
						if ($notify) {
							foreach ($notify as $key => $notification) {
								$date = $notification->date;
								$newDate = date("d-m-Y h:i", strtotime($date));
								$catname = '';
								$category_id = get_title('file_sharing', ['user_id' => $this->session->userdata('user_id'), 'id' => $notification->file_id], 'category');
								if ($category_id) {
									$catname = get_title('categoryfilesharing', ['id' => $category_id], 'name');
								}
						?>
								<tr>
									<th><?= $newDate ?></th>
									<th><?= $catname ?></th>
									<th><?= get_title('file_sharing', ['user_id' => $this->session->userdata('user_id'), 'id' => $notification->file_id], 'amount'); ?></th>
									<th class="text-success"><?= $notification->msg ?></th>
								</tr>
						<?php
							}
						}
						?>
					</table>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="closemodal">Close</button>


				</div>
			</div>
		</div>
	</div>
<?php
}
?>
<script>
	$('#year').change(function() {
		var year_id = $(this).val();
		var gst = document.getElementById('gst').value;

		document.getElementById('cl').innerHTML = '';

		if (parseInt(year_id) > 0 && parseInt(gst) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('dashboard/getData'); ?>',
				data: {
					year_id: year_id,
					gst: gst,

				},
				success: function(data) {
					data1 = JSON.parse(data);
					alldate = data1.data;

					userinfo = alldate.userinfo;
					// month = alldate.month;
					months = alldate.months;
					console.log(userinfo);
					for (i = 0; months.length > i; i++) {
						if (userinfo != '' && userinfo != null) {

							for (j = 0; j < userinfo.length; j++) {
								if (userinfo[j].current == i + 1) {
									if (userinfo[j].status == 1) {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
											'</p><span class="text-light text-center" style="margin-right:70px">' + (userinfo[j].store_date) + '</span><label class="switch"><input onclick="return false;" type="checkbox" checked><span class="slider round"></span></label><a class="btn btn-light" href="<?= base_url() ?>uploads/filling/' + userinfo[i].image + '" download>Download</a>'
										'</div>';
										$("#cl").append(row);

									} else {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
											'<p style="margin-left:40%"><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label><br>'
										'</div>'
										$("#cl").append(row);
									}
								}
							}



						} else {
							var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
								'<span class="text-danger"></span><label class="switch"><input type="checkbox"  id="gst1' + i + '+1" onclick="return false;"><span class="slider round"></span></label> </div>'
							$("#cl").append(row);

						}
					}
				}
			});

		} else {

			document.getElementById('cl').innerHTML = '';


		}

	});


	$('#gst').change(function() {

		var gst = document.getElementById('gst').value;
		var year_id = document.getElementById('year').value;

		document.getElementById('cl').innerHTML = '';

		if (parseInt(year_id) > 0 && parseInt(gst) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('dashboard/getData'); ?>',
				data: {
					year_id: year_id,
					gst: gst,

				},
				success: function(data) {
					data1 = JSON.parse(data);
					alldate = data1.data;

					userinfo = alldate.userinfo;
					// month = alldate.month;
					months = alldate.months;

					for (i = 0; months.length > i; i++) {
						if (userinfo != '' && userinfo != null) {

							for (j = 0; j < userinfo.length; j++) {
								if (userinfo[j].current == i + 1) {
									if (userinfo[j].status == 1) {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
											'</p><span class="text-light text-center" style="margin-right:70px">' + (userinfo[j].store_date) + '</span><label class="switch"><input type="checkbox" onclick="return false; checked><span class="slider round"></span></label><a class="btn btn-light" href="<?= base_url() ?>uploads/filling/' + userinfo[i].image + '" download>Download</a>'
										'</div>';
										$("#cl").append(row);
									} else {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
											'<p style="margin-left:40%"><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label><br>'
										'</div>'
										$("#cl").append(row);
									}
								}
							}

						} else {
							var row = '<div class="col-md-2 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
								'<span class="text-danger">(Not Filled)</span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </div>'
							$("#cl").append(row);

						}
					}
				}
			});

		} else {

			document.getElementById('cl').innerHTML = '';


		}

	});


	$('#default_year').change(function() {
		var year_id = $(this).val();
		if (parseInt(year_id) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('dashboard/changesession'); ?>',
				data: {
					year_id: year_id,

				},
				success: function(data) {
					data1 = JSON.parse(data);
					console.log(data1);
					if (data1) {
						window.location.reload();
					}
				}
			})
		}

	});
</script>

<script>
	$(document).ready(function() {
		$('#dataTable').DataTable();
		<?php
		$where = ['user_id' => $this->session->userdata('user_id'), 'watch' => 'no'];
		$counter = $this->user_model->getRow('notice_file', $where);
		if (count($counter) > 0) {
		?>
			$(window).on('load', function() {
				$('#auto_load').modal('show');
			});
		<?php
		}
		?>


	});
</script>
<script type="text/javascript">
	$('#closemodal').click(function() {
		$('#auto_load').modal('hide');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('file_sharing/readFile'); ?>",
			dataType: "html",
			success: function(data) {
				// $('#feed-container').prepend(data);
				console.log("GREAT JOB");

			},
			error: function() {
				console.log("SOMETHING IS WRONG");
				// alert("Error posting feed.");
			}
		});
	});
</script>