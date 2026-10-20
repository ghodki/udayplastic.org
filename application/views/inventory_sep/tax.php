<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<?php
			$role = $this->session->userdata('roles');
			if ($role == 1) {
			?>
				<div class="row mt-5">
					<div class="col-md-12 col-xl-12 col-lg-12">
						<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
							<div class="container-fluid">
								<div class="header-body ">
									<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Tax</h2>
									<!-- Card stats -->
									<div class="row">
										<?php if ($this->session->flashdata('msg')) { ?>
											<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
												<?php echo $this->session->flashdata('msg'); ?>
											</div>
										<?php

										}
										?>
										<form method="POST" action="<?= base_url('tax/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
											<div class="row">
												<div class="col-md-4 col-sm-6 col-lg-4">
													<div class="form-group">
														<label for="tax_name">Tax Name</label>

														<textarea id="name" type="text" class="form-control" name="name" required="true"></textarea>
													</div>
												</div>
												<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
													<div class="form-group">
														<label for="tax_per">Tax Percentage</label>

														<input type="number" class="form-control" onchange="checkper(this.value)" required min="0" name="tax_per" id="tax_per" placeholder="Tax Percentage">
													</div>
												</div>
												<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
													<div class="form-group">
														<label for="igst">IGST</label>

														<input type="number" class="form-control" readonly name="igst" id="igst" placeholder="IGST">
													</div>
												</div>
												<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
													<div class="form-group">
														<label for="cgst">CGST</label>

														<input type="number" class="form-control" readonly name="cgst" id="cgst" placeholder="CGST">
													</div>
												</div>
												<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
													<div class="form-group">
														<label for="sgst">SGST</label>

														<input type="number" class="form-control" readonly name="sgst" id="sgst" placeholder="SGST">
													</div>
												</div>
												<div class="col-md-4 col-md-4 col-sm-3 col-lg-4">
													<div class="form-group">
														<label for="cess">CESS</label>
														<input type="number" class="form-control" value="0" required name="cess" id="cess" placeholder="CESS">
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
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Tax List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="10%">Date</th>
													<th scope="col" width="16%">Tax Name</th>
													<th scope="col" width="14%">Tax %</th>
													<th scope="col" width="14%">IGST</th>

													<th scope="col" width="14%">CGST</th>
													<th scope="col" width="14%">SGST</th>
													<th scope="col" width="13%">CESS</th>


													<th scope="col">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if (isset($tax) && $tax != '') {
													foreach ($tax as  $key => $cat) {
														$date = $cat->created_at;
														$newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<td><?= $newDate; ?></td>

															<td><?= $cat->name ?></td>
															<td><?= $cat->tax_per ?>%</td>
															<td><?= $cat->igst ?></td>

															<td><?= $cat->cgst ?></td>
															<td><?= $cat->sgst ?></td>
															<td><?= $cat->cess ?></td>

															<?php
															if ($role == 1) {
															?>
																<td class="text-right">
																	<div class="dropdown">
																		<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			<i class="fas fa-ellipsis-v"></i>
																		</a>
																		<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																			<a href="<?= base_url('tax/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>
																			<a href="<?= base_url('tax/delete/') . $cat->id ?>" class="dropdown-item cp">Delete</a>


																		</div>
																	</div>
																</td>
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
																	$resu = $this->user_model->getRow('tax_status', $where);
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
<script>
	function checkper(value) {
		if (value > 0) {
			var cgst = value / 2;
			var cess = value / 2;
			document.getElementById('igst').value = value;
			document.getElementById('cgst').value = cgst;
			document.getElementById('sgst').value = cess;

		} else {
			document.getElementById('tax_per').value = 0;
			document.getElementById('igst').value = 0;
			document.getElementById('cgst').value = 0;
			document.getElementById('sgst').value = 0;
		}
	}
</script>
<script>
	function changestatus(year_id, val) {
		var id = year_id;
		var value = val;
		$.ajax({
			type: "POST",
			url: '<?= base_url('tax/changeData'); ?>',
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