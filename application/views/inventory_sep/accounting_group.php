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

									<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Accounting Group</h2>
									<!-- Card stats -->
									<div class="row">
										<?php if ($this->session->flashdata('msg')) { ?>
											<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
												<?php echo $this->session->flashdata('msg'); ?>
											</div>
										<?php

										}
										?>
										<form method="POST" action="<?= base_url('accounting_group/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
											<div class="row">
												<div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
												</div>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<textarea id="name" type="text" class="form-control" name="name" required="true"></textarea>
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
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Billing Format List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-dark">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="18%">Date</th>
													<th scope="col" width="80%">Description</th>
													<th scope="col">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if (isset($accounting_group) && $accounting_group != '') {
													foreach ($accounting_group as  $key => $cat) {
														$date = $cat->created_at;
														$newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<td><?= $newDate; ?></td>
															<td>
																<?php
																if ($role != 1) {
																	$where = ['sub_id' => $cat->id];
																	$sub_group_s = $this->user_model->getRow('sub_group', $where);
																	if ($sub_group_s) {
																		$name = $sub_group_s->name;
																		echo $name . '<i>(' . ($cat->name) . ')</i>';
																	} else {
																		$name = $cat->name;
																		echo $name;
																	}
																} else {
																	$name = $cat->name;
																	echo $name;
																}
																?>
															</td>


															<!-- <script>window.location.href="<?php echo base_url('MyControl/get_details'); ?>?user_id="+user_id +"&se_data="+se_data;</script> -->
															<?php
															if ($role == 1) {
															?>
																<td class="text-right">
																	<div class="dropdown">
																		<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			<i class="fas fa-ellipsis-v"></i>
																		</a>
																		<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

																			<a href="<?= base_url('accounting_group/delete/') . $cat->id ?>" class="dropdown-item cp">Delete</a>



																			<!-- <a href="<?= base_url('accounting_group/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a> -->

																		</div>
																	</div>
																</td>
														</tr>
													<?php
															} else {
													?>

														<td>
															<?php
																$user_id = $this->session->userdata('user_id');
																$comp_id = $this->session->userdata('comp_id');
																$bill_id = $cat->id;
																$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $bill_id];
																if ($this->session->userdata('BranchRole')) {
																	$where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
																}
																$resu = $this->user_model->getRow('accountant_status', $where);
																// $this->session->userdata('user_id');

																if ($resu) {
																	if ($resu->status == 1) {
															?>

																	<span class="text-success"></span><label class="switch"><input type="checkbox" checked onchange="changestatus(<?= $cat->id ?>,0)"><span class="slider round"></span></label>
																<?php
																	} else {

																?>
																	<span class="text-danger"></span><label class="switch"><input type="checkbox" onchange="changestatus(<?= $cat->id ?>,1)"><span class="slider round"></span></label>

																<?php
																	}
																} else {
																	if ($cat->status == 1) {
																?>

																	<span class="text-success"></span><label class="switch"><input type="checkbox" checked onchange="changestatus(<?= $cat->id ?>,0)"><span class="slider round"></span></label>
																<?php
																	} else {

																?>
																	<span class="text-danger"></span><label class="switch"><input type="checkbox" onchange="changestatus(<?= $cat->id ?>,1)"><span class="slider round"></span></label>

															<?php
																	}
																}
															?>
														</td>
													<?php
															}
													?>
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
<script>
	function changestatus(year_id, val) {
		var id = year_id;
		var value = val;
		$.ajax({
			type: "POST",
			url: '<?= base_url('accounting_group/changeData'); ?>',
			data: {
				id: id,
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
</script>
