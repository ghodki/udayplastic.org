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
												$role_id = $this->session->userdata('roles');

												$decode = json_decode($userinfo->dynamic);
												// echo '<pre>';
												// print_r($decode);
												// die;
												// echo $decode[1]->label;
												// die;
												if ($decode) {
													foreach ($decode as $data) {
														if ($data->status && $role_id == 3) {

											?>
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<div class="form-group"><?= $data->label; ?>
																	<input class="form-control" readonly value="<?= $data->value; ?>">
																</div>
															</div>
														<?php
														} else {
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
											}
											?>

										</div>


										<div class="row mt-5">

										</div>
									</form>
								</div>
							</div>
						</div>
						<?php
						$role_id = $this->session->userdata('roles');
						if ($role_id != 3) {
							if ($userinfo->userType != 2) {
						?>
								<div id="datta" hidden>
									<div class="row">
										<div class="column col-md-3">
											<img src="<?= base_url() ?>assets/images/logo.png" />
										</div>
										<div class="column">
											<h1 id="chengename"><?= $userinfo->name ?></h1>
										</div>
										<div class="column col-md-3">
											<!-- <p><?= $userinfo->email ?></p>
											<p><?= $userinfo->number ?></p> -->

										</div>
									</div>

								</div>

								<div id="king" hidden>
									<div class="row">
										<div class="column">
											<p></p>
										</div>

										<div class="column col-md-3">
											<img src="<?= base_url() ?>assets/images/logo.png" />
										</div>
										<div class="column">
											<p></p>
										</div>
									</div>

								</div>
								<div class="container-fluid">
									<div class="header-body">
										<h1 class="text-center">Finencial Year</h1>
										<br>
										<div class="row">
											<div class="col-md-2"></div>
											<div class="col-md-3">
												<select id='gst' required class="form-control">
													<option value="">-- Select GST --</option>
													<option value="1">GSTR-1</option>
													<option value="3">GSTR-3B</option>

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
											<div class="col-md-3">
												<span><a type="btn" class=" btn btn-light" href="#" onclick="print_data();" id="print">Print</a></span>


											</div>

										</div>

									</div>
								</div>
								<br>
								<br>
					</div>
					<br>
					<div class="row" id="fin" hidden>
						<div class="col-md-6 column">
							<!-- <h1 class="text-center">GST Filling</h1> -->
						</div>
						<div class="col-md-3 column" id="gg"></div>
						<div class="col-md-3 column" id="yy"></div>


					</div>



					<!-- <table class="table table-striped bordered">
							<thead>
								<th class="bg-primary">
									GST
								</th>

							</thead>

							<tbody id="cl">
							</tbody>
						</table> -->
					<div class="row" id="notprinted">

						<div class="col-md-3 column">
							<p></p>
						</div>
						<div class="col-md-6 column">
							<h1 class="text-center" id="gggg">GST Filling</h1>
						</div>
						<div class="col-md-3 column">
						</div>

					</div>
					<div class="conatiner" id="prt">

						<div class="row" id="cl">


						</div>
					</div>
				<?php
							}
						}
						if ($userinfo->userType == 2) {
				?>
				<div class="container-fluid">
					<div class="header-body">
						<h1 class="text-center">View User List</h1>
						<br>
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-2"></div>

							<div class="col-md-3">
								<select id='gopage' onchange="gochange()" required class="form-control">
									<option value=""></option>

									<?php
									$where = ['referral_id' => $userinfo->id];
									$subuser = $this->user_model->getAry('subcat', '', '', '', $where);
									foreach ($subuser as $userlist) {
										$id = $userlist->userid;
										$there = ['id' => $id, 'deletes' => 1];
										$subuser = $this->user_model->getRow('users', $there);
										if ($subuser) {

									?>
											<option title="See Profile" value="<?= $subuser->id; ?>"><?= $subuser->name ?></option>
									<?php
										}
									}
									?>

								</select>
							</div>


						</div>

					</div>
				</div>

			<?php	}
			?>
				</div>
			</div>
		</div>
	</div>


</div>
<script>
	$('#year').change(function() {
		var year_id = $(this).val();
		var user_id = document.getElementById('userid').value;
		var gst = document.getElementById('gst').value;
		var e = document.getElementById("year");
		var text = e.options[e.selectedIndex].text;
		document.getElementById("yy").innerHTML = 'Year' + text;
		document.getElementById('cl').innerHTML = '';

		if (parseInt(year_id) > 0 && parseInt(gst) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('year/getData'); ?>',
				data: {
					year_id: year_id,
					user_id: user_id,
					gst: gst,

				},
				success: function(data) {
					data1 = JSON.parse(data);
					alldate = data1.data;
					console.log(alldate);

					userinfo = alldate.userinfo;
					// month = alldate.month;
					months = alldate.months;
					console.log(months);
					for (i = 0; months.length > i; i++) {
						if (userinfo != '' && userinfo != null) {

							for (j = 0; j < userinfo.length; j++) {
								if (userinfo[j].current == i + 1) {
									if (userinfo[j].status == 1) {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
											'<span class="text-success">(Filled)</span><span class="text-success">' + (userinfo[j].store_date) + '</span><br><label class="switch"><input type="checkbox" checked id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,0,' + gst + ')"><span class="slider round"></span></label>'
										'</div>'
										$("#cl").append(row);
									} else {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
											'<span id="gst1' + i + '+1" class="text-danger">(Not Filled)</span><br><label class="switch"><input type="checkbox"   onchange="changeValue(' + i + '+1,1,' + gst + ')"><span class="slider round"></span></label> </div>'
										$("#cl").append(row);
									}
								}
							}



						} else {
							var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
								'<span id="gst1' + i + '+1" class="text-danger">(Not Filled)</span><br><label class="switch"><input type="checkbox"  id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,1,' + gst + ')"><span class="slider round"></span></label> </div>'
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

		var user_id = document.getElementById('userid').value;
		var gst = document.getElementById('gst').value;
		var year_id = document.getElementById('year').value;
		var e = document.getElementById("gst");
		var text = e.options[e.selectedIndex].text;
		document.getElementById("gg").innerHTML = '' + text;
		document.getElementById('cl').innerHTML = '';

		if (parseInt(year_id) > 0 && parseInt(gst) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('year/getData'); ?>',
				data: {
					year_id: year_id,
					user_id: user_id,
					gst: gst,

				},
				success: function(data) {
					data1 = JSON.parse(data);
					alldate = data1.data;
					console.log(alldate);

					userinfo = alldate.userinfo;
					// month = alldate.month;
					months = alldate.months;

					for (i = 0; months.length > i; i++) {
						if (userinfo != '' && userinfo != null) {

							for (j = 0; j < userinfo.length; j++) {
								if (userinfo[j].current == i + 1) {
									if (userinfo[j].status == 1) {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
											'<span class="text-success">(Filled)</span><span>' + (userinfo[j].store_date) + '</span><br><label class="switch"><input type="checkbox" checked id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,0,' + gst + ')"><span class="slider round"></span></label>'
										'</div>'
										$("#cl").append(row);
									} else {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
											'<span id="gst1' + i + '+1" class="text-danger">(Not Filled)</span><br><label class="switch"><input type="checkbox"  id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,1,' + gst + ')"><span class="slider round"></span></label> </div>'
										$("#cl").append(row);
									}
								}
							}



						} else {
							var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
								'<span id="gst1' + i + '+1" class="text-danger">(Not Filled)</span><br><label class="switch"><input type="checkbox"  id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,1,' + gst + ')"><span class="slider round"></span></label> </div>'
							$("#cl").append(row);
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
					document.getElementById('gst1' + m_id).innerHTML = "Filled";
					// window.location.reload();

				}
			}
		});
	}

	function gochange() {
		var id = document.getElementById('gopage').value;
		if (parseInt(id) > 0) {
			console.log(id);
			window.location.href = '<?= base_url('admin/view/'); ?>' + id;
		}
		// window.location.reload();


	}
</script>
<script>
	function print_data() {

		// var html = document.getElementById('print_Data').innerHTML;
		// window.print(html);
		// var logo=document.getElementById("dataa").innerHTML;
		// var elements = rootElement.getElementsByClassName('hidden');
		// $(elements).show();
		var uper = document.getElementById('datta').innerHTML;
		var king = document.getElementById('king').innerHTML;
		var fin = document.getElementById('fin').innerHTML;
		document.getElementById('notprinted').innerHTML = '';

		// var fin = document.getElementById('fin').innerHTML;

		// var yy = document.getElementById('yy').innerHTML;
		// var gg = document.getElementById('gg').innerHTML;


		// var els = document.getElementsByClassName('hidden_field');
		// console.log(els);
		// for (var i = 0; i < els.length; i++) {
		// 	// console.log();
		// 	var hii = document.getElementById('hidden_field' + els[i]);
		// 	hii.innerHTML = 'none';

		// }
		// var x = document.getElementsByClassName('hidden_field');

		// // var  = document.getElementsByClassName("classname");
		// var i;
		// for (i = 0; i < x.length; i++) {
		// 	if (x[i]) {
		// 		x[i].style.display = "none";
		// 	} else {
		// 		x[i].style.display = "none";

		// 	}
		// }
		var sTable = document.getElementById('prt').innerHTML;
		// style = style + "table {width: 100%;margin-left:20px;font: 30px Calibri;}";
		// style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
		// style = style + "padding: 2px 3px;text-align: center;}";


		var style = "<style>";
		style = style + ".text-success {color:green;}";
		style = style + ".text-danger {color:red;}";
		style = style + ".col-md-3 {float: left;width: 45%;text-align: center;}";
		style = style + ".row:after{content:"
		";display: table;clear: both;}";

		// style = style + "#gggg {color:green;text-align: center;}";




		// style = style + ".col-md-3{float: left;width: 50%;text-align: center;}";
		// style = style + ".cl:after{content:"
		// ";display: table;clear: both;}";
		// style = style + ".column {float: left;width: 33.33%;text-align: center;}";
		// style = style + ".row:after{content:"
		// ";display: table;clear: both;}";



		// style = style + ".col-md-3 {float: left;width: 33.33%;}";
		// style = style + "#cl:after{content:"
		// ";display: table;clear: both;}";



		style = style + "</style>";



		// CREATE A WINDOW OBJECT.
		var win = window.open('', '', 'height=1000,width=1000');

		win.document.write('<html><head>');
		// win.document.write('<link rel="stylesheet" href="http://www.dynamicdrive.com/ddincludes/mainstyle.css" type="text/css" />');
		// win.document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js">');
		// win.document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">');
		// win.document.write('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">');
		win.document.write('<title>BusinessBuddy</title>'); // <title> FOR PDF HEADER.
		win.document.write(style);

		// win.document.write(style1); // ADD STYLE INSIDE THE HEAD TAG.
		// ADD STYLE INSIDE THE HEAD TAG.
		win.document.write('</head>');
		win.document.write('<body class="container">');
		win.document.write(uper);
		win.document.write("<br>");
		win.document.write("<br>");
		win.document.write("<br>");
		win.document.write(fin); // THE TABLE CONTENTS INSIDE THE BODY TAG.
		// THE TABLE CONTENTS INSIDE THE BODY TAG.
		win.document.write("<br>");
		win.document.write("<br>");
		win.document.write("<br>");
		win.document.write(sTable); // THE TABLE CONTENTS INSIDE THE BODY TAG.
		win.document.write("<br>");
		win.document.write("<br>");
		win.document.write("<br>");
		// win.document.write(king); // THE TABLE CONTENTS INSIDE THE BODY TAG.

		win.document.write('</body></html>');

		win.document.close(); // CLOSE THE CURRENT WINDOW.

		win.print();
	}
</script>
