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
								<!-- Card stats -->
								<div class="row">


								</div>
							</div>
						</div>


						<div class="container-fluid">
							<div class="header-body">
								<h1 class="text-center"> Add Due Date</h1>
								<br>
								<div class="row">
									<div class="col-md-2"></div>
									<div class="col-md-3">

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
									<!-- <div class="col-md-3">
												<span><a type="btn" class=" btn btn-light" href="#" onclick="print_data();" id="print">Print</a></span>


											</div> -->

								</div>

							</div>
						</div>
						<br>
						<br>
					</div>
					<br>



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
							<h1 class="text-center" id="gggg">List</h1>
						</div>
						<div class="col-md-3 column">
						</div>

					</div>
					<div class="conatiner" id="prt">

						<div class="row" id="cl">


						</div>
					</div>

					<div class="container-fluid">
						<div class="header-body">
							<!-- <h1 class="text-center">Due List</h1> -->
							<br>
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-2"></div>

								<div class="col-md-3">

								</div>


							</div>

						</div>
					</div>


				</div>
			</div>
		</div>
	</div>


</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
	$('#year').change(function() {
		// var year_id = $(this).val();

		var year_id = document.getElementById("year").value;

		console.log(year_id);
		if (parseInt(year_id) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('year/getDueDate'); ?>',
				data: {
					year_id: year_id,


				},
				success: function(data) {
					document.getElementById('cl').innerHTML = '';

					data1 = JSON.parse(data);
					alldate = data1.data;
					console.log(alldate);

					userinfo = alldate.userinfo;
					// month = alldate.month;
					months = alldate.months;
					console.log(userinfo);

					for (i = 0; months.length > i; i++) {
						if (userinfo.length != 0 && userinfo != null) {

							for (j = 0; j < userinfo.length; j++) {
								if (userinfo[j].month_id == i + 1) {
									if (userinfo[j].status == 1) {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
											'</p><form method="post" class="form"  action="<?= base_url('duedate/adddue') ?>"><input type="hidden" name="id" value="' + userinfo[j].id + '"><input type="hidden" name="month_id"  value="' + i + '"><input type="hidden" name="year_id" value="' + year_id + '"><input type="date" value="' + userinfo[j].duedate + '" class="form-control" required name="duedate"  /><br><button type="submit" class="btn btn-primary" name="submit">Update Due Date</button></form>'

										'</div>'
										$("#cl").append(row);

									} else {
										var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
											'</p><form method="post" class="form"  action="<?= base_url('duedate/adddue') ?>"><input type="hidden" name="month_id"  value="' + i + '"> <input type="hidden" name="id" value="' + userinfo[i].id + '"><input type="hidden" name="year_id" value="' + year_id + '"><input type="date" value="" class="form-control" required name="duedate"  /><br><button type="submit" class="btn btn-primary" name="submit">Add Due Date</button></form>'

										'</div>'
										$("#cl").append(row);

									}
								}
							}

						} else {
							var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
								'</p><form method="post" class="form"  action="<?= base_url('duedate/adddue') ?>"><input type="hidden" name="month_id"  value="' + i + '"><input type="hidden" name="year_id" value="' + year_id + '"><input type="date" value="" class="form-control" required name="duedate"  /><br><button type="submit" class="btn btn-primary" name="submit">Update Due Date</button></form>'

							'</div>'
							$("#cl").append(row);

						}
					}
				}

			});

		} else {

			document.getElementById('cl').innerHTML = '';


		}

	});
</script>