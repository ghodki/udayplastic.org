<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add SMS</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" action="<?= base_url('sms/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">
											<input type="hidden" name="id" value="<?= $sms->id; ?>">
											<input type="hidden" name="total_sms" value="<?= $sms->sms; ?>">

											<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
												<div class="form-group"> To:
													<input id="name" type="text" class="form-control" name="name" readonly value="<?= $sms->name; ?>" required="true">
												</div>
											</div>
											<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
												<div class="form-group">Qty:
													<input id="sms" type="number" class="form-control" min="0" name="sms" placeholder=" Add SMS Quantity" required="true">
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

					<div class="container-fluid mb-9" style="margin-top:100px;">

						<div class="col">
							<div class="card shadow">
								<div class="card-header border-0">
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Add SMS List</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-dark">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="20%">Date</th>
													<th scope="col" width="30%">User Name</th>
													<th scope="col" width="48%">Sms Allocated</th>

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php
												// print_r($sms_history);
												// die;
												if (isset($sms_history) && $sms_history != '') {
													foreach ($sms_history as  $key => $value) {
														$date = $value->created_at;
														$newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<td><?= $newDate; ?></td>
															<?php
															$where = ['id' => $value->user_id];
															$username = $this->user_model->getRow('users', $where);
															?>
															<td><?= $username->name; ?></td>
															<td><?= $value->sms ?></td>




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
