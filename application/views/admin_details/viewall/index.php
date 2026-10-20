<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Check Status</h2>
								<!-- Card stats -->
								<div class="row">


									<div class="col-md-4  col-sm-6 col-lg-4">

									</div>
									<?php
									if ($this->session->userdata('roles') == 1) {


									?>
										<div class="col-md-2  col-sm-6 col-lg-2">

											<select id='subadmin' name="sub_admin" required class="form-control">
												<option value="">Select Sub Admin</option>
												<?php
												$where = ['userType' => 2, 'status' => 1];
												$data = $this->user_model->getAry('users', '', '', '', $where);
												foreach ($data as $ref) {
												?>
													<option value="<?= $ref->id; ?>"><?= $ref->name; ?></option>
												<?php
												}
												?>

											</select>
										<?php
									}

									if ($this->session->userdata('roles') == 2) {
										?>
											<div class="col-md-1  col-sm-6 col-lg-1">
												<input type="hidden" id="subadmin" value="<?= $this->session->userdata('user_id'); ?>">
											<?php
										}
											?>
											</div>
											<br>
											<br>
											<div class="col-md-2  col-sm-6 col-lg-2">
												<span><input class="form-control" placeholder="Search" onkeyup="search_func(this.value);" name="filter" id="filter" type="text"></span>

											</div>
											<div class="col-md-2  col-sm-6 col-lg-2">
												<span><a type="btn" class=" btn btn-light" href="#" onclick="print_data();" id="print">Print</a></span>

											</div>
											<div id="datta" hidden>
												<div class="row">
													<div class="column">
														<img src="<?= base_url() ?>assets/images/logo.png" />
													</div>
													<div class="column">
														<?php
														if ($this->session->userdata('roles') == 2) {
														?>
															<h1 id="chengename"><?= $this->session->userdata('name') ?></h1>

														<?php
														} else {
														?>
															<h1 id="chengename">User Filled List</h1>

														<?php
														}
														?>
													</div>
													<div class="column"></div>
												</div>

											</div>

											<div id="king" hidden>
												<div class="row">

													<div class="column">
														<p></p>
													</div>
													<div class="column">
														<img src="<?= base_url() ?>assets/images/logo.png" />
													</div>
													<div class="column"></div>
												</div>

											</div>
											<div class="container" id="print_Data">


												<table class="table table-striped table-bordered">
													<thead>
														<tr>
															<th>#</th>
															<th>Filling Date</th>
															<th>Name</th>
															<th>GST Type</th>
															<th>Status</th>
															<th class="hidden_field">View Profile</th>




														</tr>
													</thead>
													<tbody id="userdata">
														<?php
														if ($this->session->userdata('roles') == 1) {


														?>
															<?php
															$where = ['deletes' => 1, 'status' => 1, 'userType' => 3];
															$users = $this->user_model->getAry('users', '', '', '', $where);
															$a = 1;
															if ($users) {
																foreach ($users as $user) {
																	$month = date("Y-n", strtotime("-4 months"));

																	$where = ['userid' => $user->id, 'gst' => 3, 'status' => 1, 'month' => $month];
																	$row = $this->user_model->getRow('finencial_year', $where);
																	if ($row) {
																		echo "<tr>";
																		echo "<td>" . $a . "</td>";
																		++$a;

																		echo "<td>" . $row->store_date . "</td>";
																		$where = ['id' => $row->userid];
																		$nameget = $this->user_model->getRow('users', $where);
																		$name = $nameget->name;
																		echo "<td>" . $name . "</td>";

																		echo "<td>" . "GSTR 3B" . "</td>";

																		echo '<td><span class="text-success"></span><label class="switch"><input type="checkbox" onclick="return false;" checked><span class="slider round"></span></label> </td>';
																		echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

																		echo "</tr>";
																	} else {
																		$month = date("Y-n", strtotime("-4 months"));

																		$where = ['userid' => $user->id, 'gst' => 3, 'status' => 0, 'month' => $month];
																		$row = $this->user_model->getRow('finencial_year', $where);
																		if ($row) {

																			echo "<tr>";
																			echo "<td>" . $a . "</td>";
																			++$a;
																			echo "<td>" . "Not Filled" . "</td>";
																			$where = ['id' => $row->userid];
																			$nameget = $this->user_model->getRow('users', $where);
																			$name = $nameget->name;
																			echo "<td>" . $name . "</td>";

																			echo "<td>" . "GSTR 3B" . "</td>";

																			echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
																			echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

																			echo "</tr>";
																		} else {

																			echo "<tr>";
																			echo "<td>" . $a . "</td>";
																			++$a;
																			echo "<td>" . "Not Filled" . "</td>";
																			// $where = ['id' => $row->userid];
																			// $nameget = $this->user_model->getRow('users', $where);
																			// $name = $nameget->name;
																			echo "<td>" . $user->name . "</td>";

																			echo "<td>" . "GSTR 3B" . "</td>";

																			echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
																			echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$user->id") . '" class="btn btn-light">View profile</a></td>';

																			echo "</tr>";
																		}
																	}
																}
															}
														}
														if ($this->session->userdata('roles') == 2) {
															?>
															<?php
															$ref = $this->session->userdata('referral');
															$where = ['deletes' => 1, 'status' => 1, 'userType' => 3, 'referral' => $ref];
															$users = $this->user_model->getAry('users', '', '', '', $where);
															$a = 1;
															if ($users) {
																foreach ($users as $user) {
																	$month = date("Y-n", strtotime("-4 months"));

																	$where = ['userid' => $user->id, 'gst' => 3, 'status' => 1, 'month' => $month];
																	$row = $this->user_model->getRow('finencial_year', $where);
																	if ($row) {
																		echo "<tr>";
																		echo "<td>" . $a . "</td>";
																		++$a;

																		echo "<td>" . $row->store_date . "</td>";
																		$where = ['id' => $row->userid];
																		$nameget = $this->user_model->getRow('users', $where);
																		$name = $nameget->name;
																		echo "<td>" . $name . "</td>";

																		echo "<td>" . "GSTR 3B" . "</td>";

																		echo '<td><span class="text-success"></span><label class="switch"><input type="checkbox" onclick="return false;" checked><span class="slider round"></span></label> </td>';
																		echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

																		echo "</tr>";
																	} else {
																		$month = date("Y-n", strtotime("-4 months"));

																		$where = ['userid' => $user->id, 'gst' => 3, 'status' => 0, 'month' => $month];
																		$row = $this->user_model->getRow('finencial_year', $where);
																		if ($row) {

																			echo "<tr>";
																			echo "<td>" . $a . "</td>";
																			++$a;
																			echo "<td>" . "Not Filled" . "</td>";
																			$where = ['id' => $row->userid];
																			$nameget = $this->user_model->getRow('users', $where);
																			$name = $nameget->name;
																			echo "<td>" . $name . "</td>";

																			echo "<td>" . "GSTR 3B" . "</td>";

																			echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
																			echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

																			echo "</tr>";
																		} else {

																			echo "<tr>";
																			echo "<td>" . $a . "</td>";
																			++$a;
																			echo "<td>" . "Not Filled" . "</td>";
																			// $where = ['id' => $row->userid];
																			// $nameget = $this->user_model->getRow('users', $where);
																			// $name = $nameget->name;
																			echo "<td>" . $user->name . "</td>";

																			echo "<td>" . "GSTR 3B" . "</td>";

																			echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
																			echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$user->id") . '" class="btn btn-light">View profile</a></td>';

																			echo "</tr>";
																		}
																	}
																}
															}
														}

														if ($this->session->userdata('roles') == 4) {
															?>
														<?php
															$ref = $this->session->userdata('referral');
															$user_id = $this->session->userdata('user_id');

															$where = ['emp_id' => $user_id];
															$subcat = $this->user_model->getAry('subcat', '', '', '', $where);
															if ($subcat) {
																foreach ($subcat as $su) {

																	$where = ['id' => $su->userid];
																	$users[] = $this->user_model->getRow('users', $where);
																}
															}
															$a = 1;
															if ($users) {
																foreach ($users as $user) {
																	$month = date("Y-n", strtotime("-4 months"));

																	$where = ['userid' => $user->id, 'gst' => 3, 'status' => 1, 'month' => $month];
																	$row = $this->user_model->getRow('finencial_year', $where);
																	if ($row) {
																		echo "<tr>";
																		echo "<td>" . $a . "</td>";
																		++$a;

																		echo "<td>" . $row->store_date . "</td>";
																		$where = ['id' => $row->userid];
																		$nameget = $this->user_model->getRow('users', $where);
																		$name = $nameget->name;
																		echo "<td>" . $name . "</td>";

																		echo "<td>" . "GSTR 3B" . "</td>";

																		echo '<td><span class="text-success"></span><label class="switch"><input type="checkbox" onclick="return false;" checked><span class="slider round"></span></label> </td>';
																		echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

																		echo "</tr>";
																	} else {
																		$month = date("Y-n", strtotime("-4 months"));

																		$where = ['userid' => $user->id, 'gst' => 3, 'status' => 0, 'month' => $month];
																		$row = $this->user_model->getRow('finencial_year', $where);
																		if ($row) {

																			echo "<tr>";
																			echo "<td>" . $a . "</td>";
																			++$a;
																			echo "<td>" . "Not Filled" . "</td>";
																			$where = ['id' => $row->userid];
																			$nameget = $this->user_model->getRow('users', $where);
																			$name = $nameget->name;
																			echo "<td>" . $name . "</td>";

																			echo "<td>" . "GSTR 3B" . "</td>";

																			echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
																			echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$row->userid") . '" class="btn btn-light">View profile</a></td>';

																			echo "</tr>";
																		} else {

																			echo "<tr>";
																			echo "<td>" . $a . "</td>";
																			++$a;
																			echo "<td>" . "Not Filled" . "</td>";
																			// $where = ['id' => $row->userid];
																			// $nameget = $this->user_model->getRow('users', $where);
																			// $name = $nameget->name;
																			echo "<td>" . $user->name . "</td>";

																			echo "<td>" . "GSTR 3B" . "</td>";

																			echo '<td><span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label> </td>';
																			echo '<td class="hidden_field"><a href="' . base_url("index.php/admin/view/$user->id") . '" class="btn btn-light">View profile</a></td>';

																			echo "</tr>";
																		}
																	}
																}
															}
														}
														?>
													</tbody>
													<br>
													<br>
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
				$('#subadmin').change(function() {
					var subadmin = $(this).val();
					// console.log(subadmin);
					var e = document.getElementById("subadmin");
					var text = e.options[e.selectedIndex].text;
					document.getElementById("chengename").innerHTML = '' + text;
					document.getElementById('userdata').innerHTML = '';

					if (parseInt(subadmin) > 0) {
						$.ajax({
							type: "POST",
							url: '<?= base_url('dashboard/subadmindata'); ?>',
							data: {
								id: subadmin,

							},
							success: function(data) {
								if (data) {
									$("#userdata").html(data);
								} else {
									document.getElementById('userdata').innerHTML = '';

								}

								// userinfo = alldate.gst1;
								// gst3 = alldate.gst3;

								// console.log(alldate[0][0]);
								// console.log(gst3);

								// month = alldate.month;
								// for (i = 0; i < alldate.length; i++) {
								// 	// if(i==1)
								// 	// {
								// 	// 	i--;
								// 	// }
								// 	for (j = 0; j <= 1; ++j) {

								// 		var row = '<tr><td>' + alldate[i][j].id +
								// 			'<span class="text-success"></span><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> </td></tr>'
								// 		$("#userdata").append(row);
								// 		console.log(alldate[i][j])
								// 	}
								// }

							}
						})


					} else {
						document.getElementById('userdata').innerHTML = '';
						window.location.reload();

					}

				});
			</script>
			<script>
				function search_func(value) {
					// var filter = $(this).val();
					var filter = value;

					var subadmin = document.getElementById('subadmin').value;

					console.log(subadmin);
					console.log(filter);


					document.getElementById('userdata').innerHTML = '';

					if (filter != '') {
						$.ajax({
							type: "POST",
							url: '<?= base_url('dashboard/filter'); ?>',
							data: {
								id: subadmin,
								name: filter

							},
							success: function(data) {
								if (data) {
									$("#userdata").html(data);
								} else {
									document.getElementById('userdata').innerHTML = '';

								}

								// userinfo = alldate.gst1;
								// gst3 = alldate.gst3;

								// console.log(alldate[0][0]);
								// console.log(gst3);

								// month = alldate.month;
								// for (i = 0; i < alldate.length; i++) {
								// 	// if(i==1)
								// 	// {
								// 	// 	i--;
								// 	// }
								// 	for (j = 0; j <= 1; ++j) {

								// 		var row = '<tr><td>' + alldate[i][j].id +
								// 			'<span class="text-success"></span><label class="switch"><input type="checkbox" checked><span class="slider round"></span></label> </td></tr>'
								// 		$("#userdata").append(row);
								// 		console.log(alldate[i][j])
								// 	}
								// }

							}
						})


					} else if (subadmin != '') {
						$.ajax({
							type: "POST",
							url: '<?= base_url('dashboard/subadmindata'); ?>',
							data: {
								id: subadmin,
								// name: filter

							},
							success: function(data) {
								if (data) {
									$("#userdata").html(data);
								}
							}


						});


					} else {
						window.location.reload();
					}
				}

				function print_data() {

					// var html = document.getElementById('print_Data').innerHTML;
					// window.print(html);
					// var logo=document.getElementById("dataa").innerHTML;
					// var elements = rootElement.getElementsByClassName('hidden');
					// $(elements).show();
					var uper = document.getElementById('datta').innerHTML;
					var king = document.getElementById('king').innerHTML;
					var els = document.getElementsByClassName('hidden_field');
					console.log(els);
					// for (var i = 0; i < els.length; i++) {
					// 	// console.log();
					// 	var hii = document.getElementById('hidden_field' + els[i]);
					// 	hii.innerHTML = 'none';

					// }
					var x = document.getElementsByClassName('hidden_field');

					// var  = document.getElementsByClassName("classname");
					var i;
					for (i = 0; i < x.length; i++) {
						if (x[i]) {
							x[i].style.display = "none";
						} else {
							x[i].style.display = "none";

						}
					}
					var sTable = document.getElementById('print_Data').innerHTML;

					var style = "<style>";
					style = style + "table {width: 100%;font: 17px Calibri;}";
					style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
					style = style + "padding: 2px 3px;text-align: center;}";
					style = style + ".column {float: left;width: 35.33%;}";
					style = style + ".row:after{content:"
					";display: table;clear: both;}";
					style = style + "</style>";



					// CREATE A WINDOW OBJECT.
					var win = window.open('', '', 'height=1000,width=1000');

					win.document.write('<html><head>');
					win.document.write('<title>BusinessBuddy</title>'); // <title> FOR PDF HEADER.
					win.document.write(style);
					// win.document.write(style1); // ADD STYLE INSIDE THE HEAD TAG.
					// ADD STYLE INSIDE THE HEAD TAG.
					win.document.write('</head>');
					win.document.write('<body>');
					win.document.write(uper); // THE TABLE CONTENTS INSIDE THE BODY TAG.
					win.document.write("<br>");
					win.document.write("<br>");
					win.document.write("<br>");
					win.document.write(sTable); // THE TABLE CONTENTS INSIDE THE BODY TAG.
					win.document.write("<br>");
					win.document.write("<br>");
					win.document.write("<br>");
					win.document.write(king); // THE TABLE CONTENTS INSIDE THE BODY TAG.

					win.document.write('</body></html>');

					win.document.close(); // CLOSE THE CURRENT WINDOW.

					win.print();
				}
			</script>