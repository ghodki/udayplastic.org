<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<?php
			// $comp_id= ;
			// print_r($this->session->userdata('comp_id'));
			// die;

			$role = $this->session->userdata('roles');
			if ($this->session->userdata('roles') == 1) {
			?>
				<div class="row mt-5">
					<div class="col-md-12 col-xl-12 col-lg-12">
						<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
							<div class="container-fluid">
								<div class="header-body ">
									<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add YEAR</h2>
									<!-- Card stats -->
									<div class="row">
										<?php if ($this->session->flashdata('msg')) { ?>
											<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
												<?php echo $this->session->flashdata('msg'); ?>
											</div>
										<?php

										}
										?>
										<form method="POST" action="<?= base_url('finencial_year/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
											<div class="row">


												<div class="col-md-4  col-sm-6 col-lg-4">
													<div class="form-group">Starting Year:
														<input id="start_year" type="date" class="form-control" name="syear" placeholder=" Starting Year" value="<?= date("Y-01-04") ?>" required="true">
													</div>
												</div>
												<?php
												?>
												<div class="col-md-4  col-sm-6 col-lg-4">
													<div class="form-group">Ending Year:
														<input id="eyear" type="date" class="form-control" name="eyear" placeholder=" Ending Year" value="<?= date("Y+1-01-04") ?>" required="true">
													</div>
												</div>
												<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
													<div class="form-group">Add Finenicial Year:
														<input id="year" type="text" class="form-control" name="year" placeholder=" Add YEAR " required="true">
													</div>
												</div>
											</div>
											<div class="row mt-5">
												<div class="col-md-12 text-center">
													<button type="submit" class="btn btn-default">
														Save
													</button>

													<input type="reset" class="btn btn-secondary" placeholder="Reset">
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					<?php
				}
					?>
					<div class="container-fluid mb-9" style="margin-top:100px;">

						<div class="col">
							<div class="card shadow">
								<div class="card-header border-0">
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Add YEAR List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-dark">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="15%">Starting Year</th>
													<th scope="col" width="15%">Ending year</th>
													<th scope="col" width="35%">Finencial Year</th>
													<th scope="col" width="25%">Action</th>

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php
												// print_r($year);
												// die;
												if (isset($year) && $year != '') {
													foreach ($year as  $key => $value) {
														$date = $value->created_at;
														$newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>

															<td><?= $value->starting_year ?></td>
															<td><?= $value->ending_year ?></td>

															<td><?= $value->year ?></td>

															<td>
																<?php
																if ($role == 1) {

																	if ($value->status == 1) {
																?>

																		<span class="text-success"></span><label class="switch"><input type="checkbox" checked onchange="changestatus(<?= $value->id ?>,0)"><span class="slider round"></span></label>
																	<?php
																	} else { ?>
																		<span class="text-danger"></span><label class="switch"><input type="checkbox" onchange="changestatus(<?= $value->id ?>,1)"><span class="slider round"></span></label>

																	<?php	}
																} else {
																	if ($value->status == 1) {
																	?>

																		<span class="text-success"></span><label class="switch"><input type="checkbox" checked onclick="return false;" onchange="changevalue(<?= $value->id ?>,0)"><span class="slider round"></span></label>
																	<?php
																	} else { ?>
																		<span class="text-danger"></span><label class="switch"><input type="checkbox" onclick="return false;" onchange="changevalue(<?= $value->id ?>,1)"><span class="slider round"></span></label>

																<?php	}
																}
																?>
															</td>


														</tr>

												<?php }
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
</div>
<!-- <script>
	function changestatus(year_id, val) {
		var id = year_id;
		var value = val;
		$.ajax({
			type: "POST",
			url: '<?= base_url('finencial_year/changeData'); ?>',
			data: {
				year_id: id,
				status: value,

			},
			success: function(data) {
				data1 = JSON.parse(data);
				console.log(data1);
				if (data1) {


					window.location.reload();

				}
			}
		});
	}
</script> -->