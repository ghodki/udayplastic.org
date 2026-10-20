<!--<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap3.2.1.min.css">-->

<!--<script scr="<?= base_url() ?>assets/js/bootstrap3.2.1.min.js"></script>-->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">

			<div style="margin-top:72px;">
				<?php
				$roles = $this->session->userdata('roles');
				?>
				<div class="col">

					<div class="card shadow">
						<div class="card-header border-0">
							<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Transaction History</label>

							<!-- <div class="row m-3 p-3 card"> -->
							<div class="row m-5 p-3 card">
								<div class="col-md-6">
									<ul class="nav nav-pills">
										<li class="active"><a data-toggle="pill" class="active" href="#home">User's
												Request</a></li>
										<li><a data-toggle="pill" href="#menu1">Your Request</a></li>
									</ul>
								</div>
								<div class="col-md-6">

								</div>
							</div>
							<div class="card p-3">
								<form action="" id="reportForm" onsubmit="onFormSubmitHandler(event)" method="get">
									<div class="row">
										<!-- <div class="form-group col-md-2 col-sm-6">
							<label>Payment Type</label>
							<select tabindex="1" name="paymenttype" id="paymenttype" style="width:100%" class="form-control searchselect">
								<option value="">Select</option>
								<option value="Google" <?= $this->input->get('paymenttype') == 'Google' ? 'selected' : '' ?>>GooglePay</option>
								<option value="Paytm" <?= $this->input->get('paymenttype') == 'Paytm' ? 'selected' : '' ?>>Paytm</option>
								<option value="Phone" <?= $this->input->get('paymenttype') == 'Phone' ? 'selected' : '' ?>>Phonepe</option>
								<option value="PayuMoney" <?= $this->input->get('paymenttype') == 'PayuMoney' ? 'selected' : '' ?>>PayuMoney</option>

							</select>
						</div> -->
										<div class="form-group col-md-2 col-sm-6">
											<label for="bank_account" class="text-dark">Account No:</label>
											<select name="bank_account" tabindex="4" id="bank_account" class="form-control">
												<option value="">--Select--</option>
												<?php
												$bank_details = $this->user_model->getAll('bank_details');
												if ($bank_details) {
													foreach ($bank_details as $key => $value) {
												?>
														<!-- <option value="<?= $value->id ?>">
											</option> -->
														<option value="<?= $value->id; ?>" <?= $this->input->get('bank_account') == $value->id ? 'selected' : '' ?>><?= $value->name; ?>(<?= $value->account_number ?>)</option>


												<?php	}
												}
												?>
											</select>
										</div>

										<?php
										$date = date('Y-m-d');
										?>
										<div class="form-group col-md-2 col-sm-6">
											<label for="fromDate">From Date</label>
											<input type="date" tabindex="3" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : $date ?>" class="form-control" />
										</div>
										<div class="form-group col-md-2 col-sm-6">
											<label for="toDate">To Date</label>
											<input type="date" tabindex="4" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : $date ?>" class="form-control" />
										</div>
										<div class="form-group col-md-1 col-sm-6" style="margin-top:20px">

											<button type="submit" class="btn btn-primary" onClick="return search()" onkeydown="firstRow(event)"> Search</button> &nbsp

										</div>
										<div class="form-group col-md-1 col-sm-6" style="margin-top:20px">

											<button type="button" class="btn btn-warning" tabindex="4"><a href="<?= base_url() ?>wallet" class="text-muted">Reset</a></button> &nbsp

										</div>

  <div class="form-group col-md-1 col-sm-4" style="margin-top:20px">

											<button type="button" class="btn btn-warning" tabindex="5"><a href="<?= base_url() ?>wallet/sync" class="text-primary">Sync</a></button> &nbsp

										</div>


									</div>
								</form>
							</div>
							<div class="tab-content">
								<!-- SUBADMIN's USERS START -->

								<div class="tab-pane fade active in show" id="home">
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush example_contra" style="width: 100%">
											<thead class="thead-dark">
												<tr>


													<th>#</th>
													<th>Name</th>
													<th>Service Name</th>
													<th>Transaction Date</th>
													<th>Transaction Id</th>
													<th>CR</th>
													<th>DT</th>
													<th>File</th>
													<th>Status</th>
													<th>Action</th>

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php

												$total_amount_dbt = 0;
												$total_amount_cr = 0;
												// echo '<pre>';
												// print_r($wallet);
												// die;
												if (isset($wallet) && !empty($wallet)) {

													foreach ($wallet as  $key => $value) {
														$date = $value->voucherdate;
														$newDate = $date;
														$amount = 0;
														$status = $value->status;
														$find_status = $value->act_status;
												?>
														<!-- MODAL START -->
														<div id="myModal<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">Update Amount</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<form method="post" action="" onsubmit="OnSubmitHandler(<?= $value->id ?>)">
																			<div class="form-group">
																				<input type="number" class="form-control" name="amount" id="amount<?= $value->id ?>" min="1" value="<?= $value->amount ?>">
																				<input type="hidden" class="form-control" name="user_id" id="user_id<?= $value->id ?>" value="<?= $value->user_id ?>">
																				<input type="hidden" class="form-control" name="id" id="id<?= $value->id ?>" value="<?= $value->id ?>">

																			</div>
																			<button class="btn btn-primary">Update</button>
																		</form>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>

														<div id="seeMsg<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message
																		</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">

																		<p><?= $value->msg ?></p>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<div id="walletMsg<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message
																		</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">

																		<p><?= $value->note ?></p>


																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<div id="seeMsgs<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message
																		</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">

																		<p class="text-center">Payu Amount Refund</p>





																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<div id="refundwallet<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message
																		</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">

																		<p class="text-center">Wallet Request Amount Refund</p>



																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>

														<!-- MODAL END -->
														<?php
														if ($value->paymenttype == "PayuMoney" || $value->add_amount == 4 && $value->status == 1) {
														?>
															<tr>
																<td>
																	<span><?= $key + 1; ?></span>
																</td>
																<?php
																if ($value->user_id) {
																?>
																	<td><a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>"><?= get_title('users', ['id' => $value->user_id], 'name'); ?></a>
																	</td>
																<?php
																} else {
																?>
																	<td><?= $value->fullname ?></td>
																<?php
																}
																?>
																<?php
																if ($value->paymenttype == 'PayuMoney') {
																?>
																	<td class="text-danger" data-toggle="modal" data-target="#seeMsgs<?= $value->id ?>" title="Know About Debited">Debited</td>

																<?php
																} else {
																?>
																	<td class="text-danger" data-toggle="modal" data-target="#refundwallet<?= $value->id ?>" title="Know About Debited">Debited</td>

																<?php
																}
																?>

																<td><?= $value->voucherdate; ?></td>
																<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
																</td>



																<td class="text-success">
																	<?php

																	if ($find_status == 'DBT') {

																		$amount = $value->amount;
																		echo $value->amount;
																		// if ($value->status == 1) {
																		$total_amount_dbt = $total_amount_dbt + $amount;
																		// }
																	} else {
																		echo 0;
																	}


																	?>


																</td>
																<td class="text-danger">
																	<?php

																	if ($find_status == 'CR') {
																		$amount = $value->amount;
																		echo $value->amount;
																		// if ($value->status == 1) {
																		$total_amount_dbt = $total_amount_dbt + $amount;
																		// }
																	} else {
																		echo 0;
																	}


																	?>


																</td>
																<?php

																?>
																<?php
																if ($value->paymenttype == "PayuMoney") {
																?>
																	<td title="payu refund">PayU Money</td>
																<?php
																} else {
																?>
																	<td class="text-danger">Refund</td>
																<?php
																}
																?>
																<td><?php echo '<p class="text-success">Approved</p>';
																	?></td>

																<td class="text-right">
																	<div class="dropdown">
																		<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			<i class="fas fa-ellipsis-v"></i>
																		</a>
																		<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																			<?php
																			if ($this->session->userdata('roles') != 3 && $this->session->userdata('user_id') != $value->user_id) {
																			?>
																				<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>


																			<?php
																			} ?>


																		</div>
																	</div>
																</td>


															</tr>
														<?php
														}
														?>
														<tr>

															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<?php
															if ($value->user_id) {
															?>
																<td><a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>"><?= get_title('users', ['id' => $value->user_id], 'name'); ?></a>

																</td>
															<?php
															} else {
															?>
																<td><?= $value->fullname ?></td>
															<?php
															}
															?>


															<?php
															// for file shring
															if ($value->product_service) {
															?>
																<td> <?php echo get_title('categoryfilesharing', ['id' => $value->product_service], 'name'); ?>
																</td>
															<?php
															} else if ($value->add_amount == 2) {

															?>
																<!-- <td class="text-danger"><?= "Debited" ?></td> -->
																<td class="text-danger" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Debited">Debited</td>

															<?php
															} else if ($value->add_amount == 3) {

															?>
																<td> <?php echo get_title('product_service', ['id' => $value->service_product], 'name'); ?>
																</td>

															<?php
															} else if ($value->add_amount == 4) {
															?>
																<td class="text-success" data-toggle="modal" data-target="#walletMsg<?= $value->id ?>" title="Know About Debited">Wallet Credited</td>

															<?php

															} else if ($value->add_amount == 1) {
															?>
																<!-- <td class="text-success"><?= "success" ?></td> -->
																<td class="text-success" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Credited">Credited</td>


															<?php
															} else if ($value->add_amount == 5) {
															?>
																<td> <?php echo "Ecommerce Product" ?></td>
															<?php
															}
															?>



															<td><?= $value->voucherdate; ?></td>
															<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
															</td>

															<td class="text-success">
																<?php


																if ($find_status == 'CR') {
																	if ($value->file_sharing_id) {
																		// echo $value->amount;
																		$amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
																		echo $amount;
																		// $total_amount_cr = +$amount;
																	} else {
																		$amount = $value->amount;
																		echo $value->amount;
																	}
																	if ($value->status == 1) {
																		$total_amount_cr = $total_amount_cr + $amount;
																	}
																} else {
																	echo 0;
																}


																?>


															</td>

															<td class="text-danger">
																<?php
																if ($find_status == 'DBT') {
																	if ($value->file_sharing_id) {
																		// echo $value->amount;
																		$amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
																		echo $amount;
																		// $total_amount_dbt = $total_amount_dbt + $amount;
																	} else {
																		$amount = $value->amount;
																		echo $value->amount;
																		// $total_amount_dbt = $total_amount_dbt + $amount;
																	}
																	if ($value->status == 1) {
																		$total_amount_dbt = $total_amount_dbt + $amount;
																	}
																} else {
																	echo 0;
																}

																?>


															</td>
															<?php
															$where = ['id' => $value->user_id];

															if ($value->status == 1 || $value->user_id == $this->session->userdata('user_id')) {
															} else {

															?>
																<input type="hidden" style="width:90px;" class="form-control" min="0" name="amount" id="amount<?= $value->id ?>" value="<?= $value->amount ?>">
															<?php
															} ?>
															<?php
															if ($value->file_sharing_id) {
															?>
																<td><a href="<?= base_url('uploads/file_share/') . get_title('file_sharing', ['id' => $value->file_sharing_id], 'file_name'); ?>" download>Download</a></td>

																<?php
															} else if ($value->add_amount == 4) {

																if ($value->image) {
																?>
																	<td><a href="<?= base_url('uploads/amount/') . $value->image; ?>" download>Download</a></td>
																<?php
																} else if ($value->paymenttype == "PayuMoney") {
																?>
																	<td class="text-muted">PayU Money</td>
																<?php

																} else {
																?>
																	<td class="text-muted">Not Uploaded</td>
																<?php
																}
															} else if ($value->add_amount == 2) {
																?>
																<td><a href="#" class="text-danger">Debited</a></td>

															<?php
															} else if ($value->add_amount == 1) {
															?>
																<td><a href="#">Credited</a></td>

															<?php


															} else if ($value->add_amount == 3) {
															?>
																<td><a href="#">Service Request</a></td>

															<?php
															} else if ($value->add_amount == 5) {
															?>
																<td> <?php echo "Ecommerce Product" ?></td>
															<?php
															}
															?>


															<td><?php

																if ($value->status == 0) {
																	echo '<p class="text-danger">Pending</p>';
																} else if ($value->status == 1) {
																	echo '<p class="text-success">Approved</p>';
																} else {
																	echo '<p class="text-danger">REJECTED</p>';
																}
																?></td>

															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<?php
																		if ($this->session->userdata('roles') != 3) {
																		?>
																			<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>

																			<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																			<?php
																			if ($value->status == 0) {
																			?>
																				<a href="<?= base_url('wallet/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a>
																				<?php
																			} else if ($value->add_amount == 4) {


																				if (!$value->file_sharing_id && $value->paymenttype != "PayuMoney") {
																				?>
																					<a href="<?= base_url('wallet/declined/') . $value->id ?>" class="dropdown-item cp text-danger">Decline</a>

																				<?php
																				} ?>

																			<?php
																			}
																			?>
																			<?php
																			if ($value->status == 0 && $value->user_id != $this->session->userdata('user_id')) {
																			?>
																				<a onclick="return Approve('<?= base_url('wallet/approve') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>

																			<?php
																			}
																			?>
																		<?php
																		}
																		?>


																	</div>
																</div>
															</td>


														</tr>

														<!-- End Part For Admin Subadmin And Users -->


												<?php
													}
												}


												?>

											</tbody>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>

												<td>Total:</td>
												<td class="text-success">
													<?php
													echo $total_amount_cr;

													?>
												</td>

												<td class="text-danger" style="font-size:30px;"><?php

																						echo $total_amount_dbt;
																						?></td>
												<td>Balance:</td>
												<!-- <td></td> -->
												<td class="text-success" style="font-size:20px;">
													<?= $total_amount_cr - $total_amount_dbt ?></td>

												<td></td>
											</tr>


										</table>
									</div>
								</div>



								<!-- SUBADMIN's USERS END -->





































								<!-- SUBADMIN REQUESTS START -->
								<div class="tab-pane fade" id="menu1">
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush example_contra" style="width: 100%">
											<thead class="thead-dark">
												<tr>


													<th>#</th>
													<th>Name</th>
													<th>Service Name</th>

													<th>Transaction Date</th>
													<th>Transaction Id</th>
													<th>CR</th>
													<th>DT</th>

													<th>File</th>
													<th>Status</th>


													<th>Action</th>

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php
												$total_amount_dbt = 0;
												$total_amount_cr = 0;

												if (isset($subadmin_data) && !empty($subadmin_data)) {


													foreach ($subadmin_data as  $key => $value) {
														$date = $value->voucherdate;
														$newDate = $date;
														$amount = 0;
														$status = $value->status;
														$find_status = $value->act_status;
												?>
														<div id="myModal<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">Update Amount</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<form method="post" action="" onsubmit="OnSubmitHandler(<?= $value->id ?>)">
																			<div class="form-group">
																				<input type="number" class="form-control" name="amount" id="amount<?= $value->id ?>" min="1" value="<?= $value->amount ?>">
																				<input type="hidden" class="form-control" name="user_id" id="user_id<?= $value->id ?>" value="<?= $value->user_id ?>">
																				<input type="hidden" class="form-control" name="id" id="id<?= $value->id ?>" value="<?= $value->id ?>">

																			</div>
																			<button class="btn btn-primary">Update</button>
																		</form>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>

														<div id="seeMsg<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message
																		</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">

																		<p><?= $value->msg ?></p>





																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<div id="walletMsg<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message
																		</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">

																		<p><?= $value->note ?></p>





																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<div id="seeMsgss<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message
																		</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">

																		<p class="text-center">Payu Amount Refund</p>

																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<?php
														?>
														<tr>

															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<?php
															if ($value->user_id) {
															?>
																<td><a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>"><?= get_title('users', ['id' => $value->user_id], 'name'); ?></a>

																</td>
															<?php
															} else {
															?>
																<td><?= $value->fullname ?></td>
															<?php
															}

															if ($value->product_service) {
															?>
																<td> <?php echo get_title('categoryfilesharing', ['id' => $value->product_service], 'name'); ?>
																</td>
															<?php
															} else if ($value->add_amount == 2) {

															?>
																<!-- <td class="text-danger"><?= "Debited" ?></td> -->
																<td class="text-danger" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Debited">Debited</td>

															<?php
															} else if ($value->add_amount == 3) {

															?>
																<td> <?php echo get_title('product_service', ['id' => $value->service_product], 'name'); ?>
																</td>

																<!-- <td class="text-danger"><?= "Debited" ?></td> -->

															<?php
															} else if ($value->add_amount == 4) {

															?>
																<td class="text-success" data-toggle="modal" data-target="#walletMsg<?= $value->id ?>" title="Know About Debited">Wallet Credited</td>

																<!-- <td class="text-danger"><?= "Debited" ?></td> -->

															<?php

															} else if ($value->add_amount == 1) {
															?>
																<!-- <td class="text-success"><?= "success" ?></td> -->
																<td class="text-success" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Credited">Credited</td>


															<?php
															} else if ($value->add_amount == 5) {
															?>
																<td> <?php echo "Ecommerce Product" ?></td>
															<?php
															}
															?>



															<td><?= $value->voucherdate; ?></td>
															<!-- <td></td> -->
															<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
															</td>

															<td class="text-success">
																<?php

																if ($find_status == 'CR') {
																	if ($value->file_sharing_id) {
																		// echo $value->amount;
																		$amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
																		echo $amount;
																		// $total_amount_cr = +$amount;
																		// $total_amount_cr = $total_amount_cr + $amount;
																	} else {
																		$amount = $value->amount;
																		echo $value->amount;
																	}
																	if ($value->status == 1) {
																		$total_amount_cr = $total_amount_cr + $amount;
																	}
																} else {
																	echo 0;
																}


																?>


															</td>

															<td class="text-danger">
																<?php
																// if ($value->status == 1) {
																if ($find_status == 'DBT') {
																	if ($value->file_sharing_id) {
																		// echo $value->amount;
																		$amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
																		echo $amount;
																		// $total_amount_dbt = $total_amount_dbt + $amount;
																	} else {
																		$amount = $value->amount;
																		echo $value->amount;
																	}
																	if ($value->status == 1) {

																		$total_amount_dbt = $total_amount_dbt + $amount;
																	}
																} else {
																	echo 0;
																}
																// }

																?>

															</td>

															<?php
															$where = ['id' => $value->user_id];
															?>
															<?php
															if ($value->status == 1 || $value->user_id == $this->session->userdata('user_id')) {
															} else {

															?>
																<input type="hidden" style="width:90px;" class="form-control" min="0" name="amount" id="amount<?= $value->id ?>" value="<?= $value->amount ?>">
															<?php
															} ?>
															<?php
															if ($value->file_sharing_id) {
															?>
																<td><a href="<?= base_url('uploads/file_share/') . get_title('file_sharing', ['id' => $value->file_sharing_id], 'file_name'); ?>" download>Download</a></td>

																<?php
															} else if ($value->add_amount == 4) {

																if ($value->image) {
																?>
																	<td><a href="<?= base_url('uploads/amount/') . $value->image; ?>" download>Download</a></td>
																<?php
																} else if ($value->paymenttype == "PayuMoney") {
																?>
																	<td class="text-muted">PayU Money</td>
																<?php

																} else {
																?>
																	<td class="text-muted">Not Uploaded</td>
																<?php
																}
															} else if ($value->add_amount == 2) {
																?>
																<td><a href="#" class="text-danger">Debited</a></td>

															<?php
															} else if ($value->add_amount == 1) {
															?>
																<td><a href="#">Credited</a></td>

															<?php


															} else if ($value->add_amount == 3) {
															?>
																<td><a href="#">Service Request</a></td>

															<?php
															} else if ($value->add_amount == 5) {
															?>
																<td> <?php echo "Ecommerce Product" ?></td>
															<?php
															}
															?>


															<td><?php

																if ($value->status == 0) {
																	echo '<p class="text-danger">Pending</p>';
																} else {
																	echo '<p class="text-success">Approved</p>';
																}
																?></td>

															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<?php
																		if ($this->session->userdata('roles') == $value->user_id) {
																		?>
																			<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>

																			<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																			<?php
																			if ($value->status == 0) {
																			?>
																				<a href="<?= base_url('wallet/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a>
																			<?php
																			}



																			?>

																		<?php
																		}
																		?>


																	</div>
																</div>
															</td>


														</tr>

														<!-- End Part For Admin Subadmin And Users -->

												<?php }
												}


												?>

											</tbody>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>

												<td>Total:</td>
												<td class="text-success">
													<?php
													echo $total_amount_cr;

													?>
												</td>

												<td class="text-danger" style="font-size:30px;"><?php

																						echo $total_amount_dbt;
																						?></td>
												<td>Balance:</td>
												<!-- <td></td> -->
												<td class="text-success" style="font-size:20px;">
													<?= $total_amount_cr - $total_amount_dbt ?></td>

												<td></td>
											</tr>


										</table>
									</div>
								</div>

								<!-- SUBADMIN REQUESTS END -->

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<script>
	$(function() {
		$(".example_contra").DataTable({
			dom: 'Bfrtip',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
				'pageLength',
			]

		});
	});
</script>
<script>
	function Approve(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "success",
			confirmButtonText: "Yes, Approve it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRow(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				});
			}
		});
	}
</script>
<script>
	function removeRow(url, id, user_id) {
		var amount = document.getElementById('amount' + id).value;
		console.log(id, "id");
		console.log(amount, "amount");


		$.ajax({
			type: "POST",
			url: url,
			data: {
				amount: amount,
				id: id,
				user_id: user_id,

			},
			success: function(success) {
				success = JSON.parse(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
					setTimeout(function() {
						window.location.reload();
					}, 2000);
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					});
				}
			},
		});
	}
</script>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
<script>
	function OnSubmitHandler(id) {
		var amount = document.getElementById('amount' + id).value;
		var id = document.getElementById('id' + id).value;
		var user_ids = document.getElementById('user_id' + id).value;


		if (amount > 0 && user_ids != '' && id != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>userlist/amount_update",
				// data: $("#dataForm").serialize(),
				data: {
					amount: amount,
					user_id: user_ids,
					id: id,

				},
				success: function(invoiceNo) {
					invoiceNo = JSON.parse(invoiceNo);
					console.log(invoiceNo, "dtaaaa");
					if (invoiceNo.status == true) {

						// invoiceData = invoiceNo.data;
						// cosnole.log(invoiceData);
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: 'Amount Added',
							showConfirmButton: false,
							timer: 2000
						})
						setTimeout(function() {
							window.reload();
						}, 2000)

					} else {
						Swal.fire({
							position: 'top-end',
							icon: 'error',
							title: invoiceNo.msg,
							showConfirmButton: false,
							timer: 2000
						})
					}

				},

			});
		} else {
			alert("Please Enter Amount");
		}
	}
</script>
<script>
	function onlyNumberKey(evt) {
		var ASCIICode = (evt.which) ? evt.which : evt.keyCode
		if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
			return false;
		return true;
	}
	const onFormSubmitHandler = (e) => {
		e.preventDefault();
		var form = $('#reportForm').serialize();
		href = "<?= base_url() ?>wallet/wallet_report?" + form;
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a[0].click();
		$a.remove();
	};
</script>