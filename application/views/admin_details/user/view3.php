<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
	}

	.switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}

	input:checked+.slider {
		background-color: #2196F3;
	}

	input:focus+.slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked+.slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}

	td {
		background-color: lightgrey;
		width: 300px;
		border: 15px solid green;
		padding: 50px;
		margin: 20px;
	}
</style>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">See Profile</h2>
								<!-- Card stats -->
								<div class="row">

									<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12">

										<div class="row">
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">Name:
													<input type="hidden" name="id" id="userid" value="<?= $userinfo->id ?>">

													<input readonly class="form-control" readonly required="true" value="<?= $userinfo->name ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">Email:
													<input class="form-control" readonly required="true" value="<?= $userinfo->email ?>">
												</div>
											</div>
											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">Number:
													<input class="form-control" readonly value="<?= $userinfo->number ?>">
												</div>
											</div>


											<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
												<div class="form-group">Code:
													<input class="form-control" readonly value="<?= $userinfo->referral ?>">
												</div>
											</div>
											<?php
											if ($userinfo->dynamic != '') {

												$decode = json_decode($userinfo->dynamic);
												// echo '<pre>';
												// print_r($decode);
												// die;
												// echo $decode[1]->label;
												// die;
												if ($decode) {
													foreach ($decode as $data) {
											?>
														<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
															<div class="form-group"><?= $data->label; ?>
																<input class="form-control" readonly value="<?= $data->value; ?>">
															</div>
														</div>
											<?php
													}
												}
											}
											?>

										</div>


										<div class="row mt-5">

										</div>
									</form>
								</div>
							</div>
						</div>



						<div class="container-fluid">
							<div class="header-body">
								<h1 class="text-center">Finencial Year</h1>
								<br>
								<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-5">
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
					</div>
					<br>
					<div class="conatiner">


						<table class="table table-striped bordered">
							<thead>
								<th class="bg-primary">
									GST 1
								</th>
								<span></span>
								<th class="bg-primary">
									GST 3b
								</th>
							</thead>

							<tbody id="cl">
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
<script>
	$('#year').change(function() {
		var year_id = $(this).val();
		var user_id = document.getElementById('userid').value;
		document.getElementById('cl').innerHTML = '';

		if (parseInt(year_id) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('year/getData'); ?>',
				data: {
					year_id: year_id,
					user_id: user_id,
				},
				success: function(data) {
					data1 = JSON.parse(data);
					alldate = data1.data;

					userinfo = alldate.userinfo;
					// month = alldate.month;
					months = alldate.months;

					if (months != '') {
						for (i = 0; months.length > i; i++) {
							gst1 = [];
							gst3 = [];
							if (userinfo != '' && userinfo != null) {
								for (j = 0; j < userinfo.length; j++) {

									if (userinfo[j].current == i + 1) {
										if (userinfo[j].gst == 1) {
											var gst1 = userinfo[j].gst;
											console.log('gst1', gst1);
										} else {
											var gst3 = userinfo[j].gst;
											console.log('gst3', gst3);
										}
										break;
									} else {
										continue;
									}


								}
							}


							if (userinfo != '') {
								if (gst1) {
									var row = '<tr><td>' + months[i] +
										'<span class="text-success">(Filled)</span><label class="switch"><input type="checkbox" checked id="gst1' + i + '" onchange="changeValue(' + i + '+1,1,1)"><span class="slider round"></span></label> </td>'

									if (gst3) {
										'<td>' + months[i] + '<span class="text-success" id="dg' + i + '+1">(Filled)</span><label class="switch"><input type="checkbox" checked onchange="changestatus(' + i + '+1,0,3)"><span class="slider round"></span></label></td></tr>'
										$("#cl").append(row);
									} else {
										'<td>' + months[i] + '<span class="text-success" id="dg' + i + '+1">(Not Filled)</span><label class="switch"><input type="checkbox" checked onchange="changestatus(' + i + '+1,0,3)"><span class="slider round"></span></label></td></tr>'
										$("#cl").append(row);
										console.log('gst3');
									}
								} else if (gst3) {
									var row = '<tr><td>' + months[i] +
										'<span class="text-danger">(Not Filled)</span><label class="switch"><input type="checkbox" id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,1,1)"><span class="slider round"></span></label> </td><td>' + months[i] + '<span class="text-success" id="dg' + i + '+1">(Filled)</span><label class="switch"><input type="checkbox" checked onchange="changestatus(' + i + '+1,0,3)"><span class="slider round"></span></label></td></tr>'
									$("#cl").append(row);
									console.log('gst3');
								} else {
									var row = '<tr><td>' + months[i] +
										'<span id="' + i + '+1" class="text-success"></span><span class="text-danger">(Not filled)</span><label class="switch"><input type="checkbox" id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,1,1)" ><span class="slider round"></span></label> </td><td>' + months[i] + '<span class="text-danger" id="dg' + i + '">(Not Filled)</span><label class="switch"><input type="checkbox" onchange="changestatus(' + i + '+1,1,3)"><span class="slider round"></span></label></td></tr>'
									$("#cl").append(row);
									console.log('without');
								}


							} else {
								var row = '<tr><td>' + months[i] +
									'</span><span class="text-danger">(Not filled)</span><label class="switch"><input type="checkbox" id="gst1' + i + '1" onchange="changeValue(' + i + '+1,1,1)" ><span class="slider round"></span></label> </td><td>' + months[i] + '<span class="text-danger" id="dg' + i + '+1">(Not Filled)</span><label class="switch"><input type="checkbox" onchange="changestatus(' + i + '+1,1,3)"><span class="slider round"></span></label></td></tr>'
								$("#cl").append(row);
								console.log('without');
							}








						}
					}
				}
			});

		} else {
			document.getElementById('cl').innerHTML = '';

		}

	});

	function changestatus(m_id, status, gst) {
		var user_id = document.getElementById('userid').value;
		var year_id = document.getElementById('year').value;

		console.log(m_id);
		console.log(status);
		console.log(gst);
		$.ajax({
			type: "POST",
			url: '<?= base_url('year/getStatus'); ?>',
			data: {
				m_id: m_id,
				user_id: user_id,
				status: status,
				gst: gst,
				year_id: year_id,
			},
			success: function(data) {
				data1 = JSON.parse(data);
				console.log(data1);
				if (data1) {

					document.getElementById('dg' + m_id).innerHTML = "filled";
					// window.location.reload();

				}
			}
		});
	}
</script>
<script>
	function changeValue(m_id, status, gst) {
		var user_id = document.getElementById('userid').value;
		var year_id = document.getElementById('year').value;
		console.log(m_id);
		console.log(status);
		console.log(gst);

		$.ajax({
			type: "POST",
			url: '<?= base_url('year/getStatus'); ?>',
			data: {
				m_id: m_id,
				user_id: user_id,
				status: status,
				gst: gst,
				year_id: year_id,
			},
			success: function(data) {
				data1 = JSON.parse(data);
				console.log(data1);
				if (data1) {

					document.getElementById('gst1' + m_id).innerHTML = "filed";
					// window.location.reload();

				}
			}
		});
	}
</script>
