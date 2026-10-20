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


						<!-- <table class="table table-striped bordered">
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
								<tr id="tr0"></tr>
							</tbody>
						</table> -->
						<div class="row">
							<div class="col-md-6">GST 1</div>
							<div class="col-md-6">GST 3(b)
							</div>
						</div>
						<div class="row" id="cl">
							<div class="col-md-6">
								<div class="row" id="tr1">
								</div>
							</div>
							<div class="col-md-6">
								<div class="row" id="tr3">
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
</div>
<script>
	$('#year').change(function() {
		var year_id = $(this).val();
		document.getElementById('cl').innerHTML = '';

		if (parseInt(year_id) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('dashboard/getData'); ?>',
				data: {
					year_id: year_id,
				},
				success: function(data) {
					data1 = JSON.parse(data);
					alldate = data1.data;

					userinfo = alldate.userinfo;
					// month = alldate.month;
					months = alldate.months;
					console.log(userinfo);
					for (i = 0; months.length > i; i++) {

						if (userinfo.length && userinfo != null) {

							for (j = 0; j < userinfo.length; j++) {

								if (userinfo[j].current == i + 1) {
									console.log(userinfo[j].gst);

									if (userinfo[j].gst == 3 || userinfo[j].gst == 1) {
										if (userinfo[j].gst == 1) {
											var row = '<div class="col-md-6 p-5 pull-left bg-danger" style="border:2px solid black;">' + months[i] +
												'<span class="text-success">(Filled)</span><label class="switch"><input type="checkbox" checked id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,0,1)"><span class="slider round"></span></label> </div>'
											$("#tr1").append(row);

										}
										// else {
										// 	var row = '<div class="col-md-6 p-5 bg-success pull-right" style="border:2px solid black;">' + months[i] +
										// 		'<span class="text-success" id="dg' + i + '+1">(Filled)</span><label class="switch"><input type="checkbox" checked onchange="changestatus(' + i + '+1,1,3)"><span class="slider round"></span></label></div>'
										// 	$("#tr3").append(row);

										// }


										// } else if (userinfo[j].gst == 1) {
										// 	// same=array_intersect(gst3,gst1);

										// 	var row = '<tr><td>' + months[i] +
										// 		'<span class="text-success">(Filled)</span><label class="switch"><input type="checkbox"  checked id="gst1' + i + '+1" onchange="changeValue(' + i + '+1,1,1)"><span class="slider round"></span></label> </td>'
										// 	if (gst1 != gst3) {
										// 		'<td>' + months[i] + '<span class="text-danger" id="dg' + i + '+1">(Not Filled)</span><label class="switch"><input type="checkbox"  onchange="changestatus(' + i + '+1,1,3)"><span class="slider round"></span></label></td></tr>'
										// 		$("#cl").append(row);
										// 		console.log(gst3, 'array');
										// 	} else {
										// 		'<td>' + months[i] + '<span class="text-danger" id="dg' + i + '+1">(Not Filled)</span><label class="switch"><input type="checkbox"  onchange="changestatus(' + i + '+1,1,3)"><span class="slider round"></span></label></td></tr>'
										// 		$("#cl").append(row);
										// 		console.log(gst3, 'array');
										// 	}
										// }
									} else {
										var row = '<div class="col-md-6 p-5" style="border:2px solid black;">' + months[i] +
											'<span class="text-danger">(Not Filled)</span><label class="switch"><input type="checkbox onchange="changestatus(' + i + ',1,3)" id="3' + i + '"><span class="slider round"></span></label> </div>'
										'<div class="col-md-6 p-5" style="border:2px solid black;">>' + months[i] + '<span class="text-success" id="dg' + i + '">(Filled)</span><label class="switch"><input type="checkbox" onchange="changestatus(' + i + ',0,3)" id="3' + i + '"><span class="slider round"></span></label></div>'
										$("#cl").append(row);
									}
								}

							}
						} else {
							var row = '<div class="col-md-6 p-5" style="border:2px solid black;">' + months[i] +
								'<span class="text-danger">(Not Filled)</span><label class="switch"><input type="checkbox onchange="changestatus(' + i + ',1,3)" id="3' + i + '"><span class="slider round"></span></label> </div>'
							'<div class="col-md-6 p-5" style="border:2px solid black;">>' + months[i] + '<span class="text-success" id="dg' + i + '">(Filled)</span><label class="switch"><input type="checkbox" onchange="changestatus(' + i + ',0,3)" id="3' + i + '"><span class="slider round"></span></label></div>'
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

					document.getElementById('gst' + m_id).innerHTML = "filed";
					// window.location.reload();

				}
			}
		});
	}
</script>
