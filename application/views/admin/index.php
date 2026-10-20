<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">

			<div class="container-fluid mb-9" style="margin-top:72px;">

				<div class="col">

					<div class="card shadow">
						<div class="card-header border-0">
							<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Transaction History</label>

							<div class="row m-3 p-3 card">
								<div class="col-md-8">
								
									<ul class="nav nav-tabs" id="myTab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="menu1-tab" data-toggle="tab" href="#menu1" role="tab" aria-controls="menu1" aria-selected="true">Subadmin Request</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">User's Request</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab" aria-controls="other" aria-selected="false">Other's Payment</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="store_ecoomerce-tab" data-toggle="tab" href="#store_ecoomerce" role="tab" aria-controls="store_ecoomerce" aria-selected="false">Store Ecommerce</a>
										</li>
									</ul>

								</div>
								<div class="col-md-4">

								</div>
							</div>
							<div class="card p-3 mt-2">
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
												$bank_details = $this->user_model->getResult('bank_details');
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
											<input type="date" tabindex="3" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : '' ?>" class="form-control" />
										</div>
										<div class="form-group col-md-2 col-sm-6">
											<label for="toDate">To Date</label>
											<input type="date" tabindex="4" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : '' ?>" class="form-control" />
										</div>
										<div class="form-group col-md-2 col-sm-6">
											<label for="total">Total Records</label>
											<select class="form-control select2 searchselect" name="records" id="records">
												<option value="20" <?= $this->input->get('records') == '20' ? 'selected' : '' ?>>20</option>

												<option value="all" <?= $this->input->get('records') == 'all' ? 'selected' : '' ?>>All</option>
												<option value="50" <?= $this->input->get('records') == '50' ? 'selected' : '' ?>>50</option>
												<option value="100" <?= $this->input->get('records') == '100' ? 'selected' : '' ?>>100</option>
												<option value="500" <?= $this->input->get('records') == '500' ? 'selected' : '' ?>>500</option>
												<option value="1000" <?= $this->input->get('records') == '1000' ? 'selected' : '' ?>>1000</option>
												<option value="5000" <?= $this->input->get('records') == '5000' ? 'selected' : '' ?>>5000</option>
												<option value="10000" <?= $this->input->get('records') == '10000' ? 'selected' : '' ?>>10000</option>

												<!-- <option value="500000" <?= $this->input->get('records') == '500000' ? 'selected' : '' ?>>500000</option> -->


											</select>
										</div>
										<div class="form-group col-md-1 col-sm-4" style="margin-top:20px">

											<button type="submit" class="btn btn-primary" onClick="return search()" onkeydown="firstRow(event)"> Search</button> &nbsp

										</div>
										<div class="form-group col-md-1 col-sm-4" style="margin-top:20px">

											<button type="button" class="btn btn-warning" tabindex="4"><a href="<?= base_url() ?>wallet" class="text-muted">Reset</a></button> &nbsp

										</div>
	<div class="form-group col-md-1 col-sm-4" style="margin-top:20px">

											<button type="button" class="btn btn-warning" tabindex="5"><a href="<?= base_url() ?>wallet/sync" class="text-primary">Sync</a></button> &nbsp

										</div>

									</div>
								</form>
							</div>
							<div class="tab-content">
								<!-- SUBADMIN'S PAYMENT START -->

								<div class="tab-pane fade active in show" id="menu1">
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush example_contra" style="width: 100%">
											<thead class="thead-dark">
												<tr>


													<th>#</th>
													<th>Name</th>
													<th>Service Name</th>
													<th>Transaction Date</th>
													<th>Transaction Id</th>
													<th>DBT</th>
													<th>CR</th>

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
																		<h4 class="modal-title text-center text-danger">Message</h4>

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
																		<h4 class="modal-title text-center text-danger">Message</h4>

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
																		<h4 class="modal-title text-center text-danger">Message</h4>

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

														<div id="walletrefund<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message</h4>

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
														<div id="amanModel<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center">Reject Message</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<form method="post" action="" onsubmit="OnSubmitHandlers(<?= $value->id ?>,<?= $value->user_id ?>)">
																			<div class="form-group">
																				<textarea type="text" class="form-control" name="msg" id="msgs<?= $value->id ?>"></textarea>

																				<input type="hidden" class="form-control" name="user_id" id="user_id<?= $value->id ?>" value="<?= $value->user_id ?>">

																			</div>
																			<button class="btn btn-danger">Reject</button>
																		</form>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>

														<div id="rejectmsg<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center">Reject Message</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<p class="text-danger"><?= $value->msg ?></p>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<!-- MODAL END -->
														<?php
														if ($value->paymenttype != "PayuMoney" && $value->status == 1 && $value->add_amount == 4) {
															// if ($value->paymenttype == "PayuMoney" || $value->add_amount == 4 || $value->add_amount == 5 || $value->paymenttype == "Wallet" && $value->status == 1) {

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
																if ($value->paymenttype == "PayuMoney") {
																?>
																	<td class="text-success" data-toggle="modal" data-target="#seeMsgs<?= $value->id ?>" title="Know About Debited">Wallet Refund</td>
																<?php
																} else {
																?>
																	<td class="text-success" data-toggle="modal" data-target="#walletrefund<?= $value->id ?>" title="Know About Debited">Wallet Refund</td>

																<?php
																}
																?>


																</td>

																<td><?= $value->voucherdate; ?></td>
																<!-- <td></td> -->
																<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
																</td>



																<td class="text-danger">
																	<?php
																	if ($find_status == 'DBT') {

																		$amount = $value->amount;
																		echo $value->amount;
																		$total_amount_cr = $total_amount_cr + $amount;
																	} else {
																		echo 0;
																	}

																	?>


																</td>
																<td class="text-success">
																	<?php
																	if ($find_status == 'CR') {
																		$amount = $value->amount;
																		echo $value->amount;
																		$total_amount_dbt = $total_amount_dbt + $amount;
																	} else {
																		echo 0;
																	}

																	?>


																</td>





																<?php
																if ($value->paymenttype == "PayuMoney") {
																?>
																	<td title="payu refund">PayU Refund</td>
																<?php
																} else {
																?>
																	<td title="wallet refund" class="text-danger">Refund</td>

																<?php
																}
																?>


																<td><?php


																	echo '<p class="text-success">Approved</p>';

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
																<td><?= $value->fullname; ?></td>
															<?php
															}
															?>
															<?php
															// for file shring
															if ($value->product_service) {
															?>
																<td> <?php echo get_title('categoryfilesharing', ['id' => $value->product_service], 'name'); ?></td>
															<?php
															} else if ($value->add_amount == 2) {

															?>
																<!-- <td class="text-danger"><?= "Debited" ?></td> -->
																<td class="text-danger" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Debited">Debited</td>

															<?php
															} else if ($value->add_amount == 3) {

															?>
																<td> <?php echo get_title('product_service', ['id' => $value->service_product], 'name'); ?></td>
															<?php
															} else if ($value->add_amount == 4) {

															?>
																<td class="text-danger" data-toggle="modal" data-target="#walletMsg<?= $value->id ?>" title="Know About Debited">Wallet Debited</td>


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




															<td class="text-danger">
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
																		// $total_amount_cr = $total_amount_cr + $amount;
																	}
																	if ($value->status == 1) {
																		$total_amount_cr = $total_amount_cr + $amount;
																	}
																} else {
																	echo 0;
																}

																?>


															</td>
															<td class="text-success">
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

																// echo  $value->amount;
															} else {
															?>
																<input type="hidden" style="width:90px;" class="form-control" min="0" name="amount" id="amount<?= $value->id ?>" value="<?= $value->amount ?>">
															<?php
															}
															?>
															<!-- </td> -->
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
																	echo '<p class="text-danger" data-toggle="modal" data-target="#rejectmsg' . $value->id . '">Rejected</p>';
																}
																?></td>


															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<?php
																		?>
																		<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>

																		<?php
																		if ($value->status == 0) {
																		?>
																			<a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#amanModel<?= $value->id ?>">Reject</a>

																			<?php
																		} else {
																			if ($value->add_amount == 4 && $value->status == 1) {

																				if (!$value->file_sharing_id && $value->paymenttype != "PayuMoney") {
																			?>
																					<a href="<?= base_url('wallet/declined/') . $value->id ?>" class="dropdown-item cp text-danger">Decline</a>
																				<?php
																				} ?>

																			<?php
																			}
																		}

																		if ($value->status == 0 || $value->status == 2) {
																			?>
																			<a onclick="return Approve('<?= base_url('wallet/approve') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
																			<!-- status= 2 Rejection -->

																		<?php
																		}

																		?>
																		<?php
																		// }
																		?>

																	</div>
																</div>
															</td>


														</tr>

														<!-- End Part For Admin Subadmin And Users -->






												<?php }
												}
												// }else
												// {

												// }

												?>

											</tbody>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>

												<td>Total:</td>
												<td class="text-danger">
													<?php
													echo $total_amount_cr;

													?>
												</td>

												<td class="text-success" style="font-size:30px;"><?php

																						echo $total_amount_dbt;
																						?></td>
												<td>Balance:</td>
												<!-- <td></td> -->
												<td class="text-success" style="font-size:20px;"><?= $total_amount_dbt - $total_amount_cr ?></td>

												<td></td>
											</tr>


										</table>
									</div>
								</div>

								<!-- SUBADMIN'S PAYMENT END -->


								<!-- USER'S PAYMENT START -->

								<div class="tab-pane fade" id="home">
									<div class="table-responsive">
										<table class="table table-striped align-items-center table-flush example_contra" style="width: 100%">
											<thead class="thead-dark">
												<tr>


													<th>#</th>
													<th>Name</th>
													<th>Service Name</th>
													<th>Transaction Date</th>
													<th>Transaction Id</th>
													<th>DBT</th>

													<th>CR</th>
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
														<div id="walletrefund<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message</h4>

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
														<div id="amanModel<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center">Reject Message</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<form method="post" action="" onsubmit="OnSubmitHandlers(<?= $value->id ?>,<?= $value->user_id ?>)">
																			<div class="form-group">
																				<textarea type="text" class="form-control" name="msg" id="msgs<?= $value->id ?>"></textarea>

																				<input type="hidden" class="form-control" name="user_id" id="user_id<?= $value->id ?>" value="<?= $value->user_id ?>">

																			</div>
																			<button class="btn btn-danger">Reject</button>
																		</form>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>

														<div id="rejectmsg<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center">Reject Message</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<p class="text-danger"><?= $value->msg ?></p>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>
														<!-- MODAL END -->



														<?php
														if ($value->paymenttype != "PayuMoney" && $value->status == 1 && $value->add_amount == 4) {

															// if ($value->paymenttype == "PayuMoney" || $value->add_amount == 4 || $value->add_amount == 5 || $value->paymenttype == "Wallet" && $value->status == 1) {
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
																if ($value->paymenttype == "PayuMoney") {
																?>
																	<td class="text-success" data-toggle="modal" data-target="#seeMsgs<?= $value->id ?>" title="Know About Debited">Payu Refund</td>
																<?php
																} else {
																?>
																	<td class="text-success" data-toggle="modal" data-target="#walletrefund<?= $value->id ?>" title="Know About Debited">Wallet Refund</td>

																<?php
																}
																?>

																<td><?= $value->voucherdate; ?></td>
																<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
																</td>


																<td class="text-danger">
																	<?php
																	if ($find_status == 'DBT') {

																		$amount = $value->amount;
																		echo $value->amount;
																		$total_amount_cr = $total_amount_cr + $amount;
																	} else {
																		echo 0;
																	}

																	?>


																</td>
																<td class="text-success">
																	<?php
																	if ($find_status == 'CR') {
																		$amount = $value->amount;
																		echo $value->amount;
																		$total_amount_dbt = $total_amount_dbt + $amount;
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
																<td> <?php echo get_title('categoryfilesharing', ['id' => $value->product_service], 'name'); ?></td>
															<?php
															} else if ($value->add_amount == 2) {

															?>
																<!-- <td class="text-danger"><?= "Debited" ?></td> -->
																<td class="text-success" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Debited">Credited</td>

															<?php
															} else if ($value->add_amount == 3) {

															?>
																<td> <?php echo get_title('product_service', ['id' => $value->service_product], 'name'); ?></td>
															<?php
															} else if ($value->add_amount == 4) {

															?>
																<td class="text-danger" data-toggle="modal" data-target="#walletMsg<?= $value->id ?>" title="Know About Debited">Wallet Debited</td>


															<?php

															} else if ($value->add_amount == 1) {
															?>
																<!-- <td class="text-success"><?= "success" ?></td> -->
																<td class="text-danger" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Credited">Debited</td>


															<?php
															} else {
															?>
																<td> <?php echo get_title('categoryfilesharing', ['id' => $value->product_service], 'name'); ?></td>
															<?php
															}
															?>



															<td><?= $value->voucherdate; ?></td>
															<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
															</td>



															<td class="text-danger">
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
																		// $total_amount_cr = $total_amount_cr + $amount;
																	}
																	if ($value->status == 1) {
																		$total_amount_cr = $total_amount_cr + $amount;
																	}
																} else {
																	echo 0;
																}

																?>


															</td>
															<td class="text-success">
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
																<td><a href="#">Credited</a></td>

																<!-- <td><a href="#" class="text-danger">Debited</a></td> -->

															<?php
															} else if ($value->add_amount == 1) {
															?>
																<!-- <td><a href="#">Credited</a></td> -->
																<td><a href="#" class="text-danger">Debited</a></td>


															<?php


															} else if ($value->add_amount == 3) {
															?>
																<td><a href="#">Service Request</a></td>

															<?php
															} else {
															?>
																<td><a href="#">FILESHARING</a></td>

															<?php
															}
															?>


															<td><?php

																if ($value->status == 0) {
																	echo '<p class="text-danger">Pending</p>';
																} else if ($value->status == 1) {
																	echo '<p class="text-success">Approved</p>';
																} else {
																	echo '<p class="text-danger" data-toggle="modal" data-target="#rejectmsg' . $value->id . '">Rejected</p>';
																}
																?></td>

															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<?php
																		?>
																		<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>

																		<?php
																		if ($value->status == 0) {
																		?>
																			<a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#amanModel<?= $value->id ?>">Reject</a>

																			<?php
																		} else {
																			if ($value->add_amount == 4 && $value->status == 1) {

																				if (!$value->file_sharing_id && $value->paymenttype != "PayuMoney") {
																			?>
																					<a href="<?= base_url('wallet/declined/') . $value->id ?>" class="dropdown-item cp text-danger">Decline</a>

																				<?php
																				} ?>

																		<?php
																			}
																		}
																		?>
																		<?php
																		if ($value->status == 0 || $value->status == 2) {
																		?>
																			<a onclick="return Approve('<?= base_url('wallet/approve') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>

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
												<td class="text-danger">
													<?php
													echo $total_amount_cr;

													?>
												</td>

												<td class="text-success" style="font-size:30px;"><?php

																						echo $total_amount_dbt;
																						?></td>
												<td>Balance:</td>
												<!-- <td></td> -->
												<td class="text-success" style="font-size:20px;"><?= $total_amount_dbt - $total_amount_cr ?></td>

												<td></td>
											</tr>


										</table>
									</div>
								</div>

								<!-- USER'S PAYMENT END -->

								<!-- OTHER's PAYMENT START -->


								<div class="tab-pane fade" id="other">
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
													<th>File</th>
													<th>Status</th>
													<th>Action</th>

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php


												// $total_amount_dbt = 0;
												$credit_amount = 0;

												if (isset($other) && !empty($other)) {

													foreach ($other as  $key => $value) {
														$date = $value->voucherdate;
														$newDate = $date;
														$amount = 0;
														$status = $value->status;
														$find_status = $value->act_status;
												?>


														<div id="seeMsg<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center text-danger">Message</h4>

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
																		<h4 class="modal-title text-center text-danger">Message</h4>

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
																		<h4 class="modal-title text-center text-danger">Message</h4>

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
														<div id="amansModel<?= $value->id ?>" class="modal fade" role="dialog">
															<div class="modal-dialog">

																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title text-center">Reject Message</h4>

																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>
																	<div class="modal-body">
																		<form method="post" action="" onsubmit="onRejection(<?= $value->id ?>,<?= $value->user_id ?>)">
																			<div class="form-group">
																				<textarea type="text" class="form-control" name="msg" id="msg<?= $value->id ?>"></textarea>

																				<input type="hidden" class="form-control" name="user_id" id="user_ids<?= $value->id ?>" value="<?= $value->user_id ?>">

																			</div>
																			<button class="btn btn-danger">Reject</button>
																		</form>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div>
																</div>

															</div>
														</div>


														<?php
														if ($value->paymenttype == "PayuMoney" || $value->status == 1 && $value->add_amount == 4) {
															// if ($value->paymenttype == "PayuMoney" || $value->add_amount == 4 || $value->add_amount == 5 || $value->paymenttype == "Wallet" && $value->status == 1) {

														?>
															<tr>

																<td>
																	<span><?= $key + 1; ?></span>
																</td>
																<?php

																?>
																<td><?= $value->fullname ?></td>
																<?php

																?>

																<td class="text-success" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Credited">Wallet Credited</td>





																<td><?= $value->voucherdate; ?></td>
																<!-- <td></td> -->
																<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
																</td>




																<td class="text-success">
																	<?php
																	if ($find_status == 'CR') {
																		$amount = $value->amount;
																		echo $value->amount;
																		if ($value->status == 1) {
																			$credit_amount = $credit_amount + $amount;
																		}
																		// $total_amount_dbt = $total_amount_dbt + $amount;
																	} else {
																		echo 0;
																	}

																	?>


																</td>
																<?php

																?>
																<td title="payu refund">PayU Money</td>



																<td><?php


																	echo '<p class="text-success">Approved</p>';

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
													echo $credit_amount;

													?>
												</td>


												<td>Balance:</td>
												<!-- <td></td> -->
												<td class="text-success" style="font-size:20px;"><?= $credit_amount ?></td>

												<td></td>
											</tr>


										</table>
									</div>
								</div>

								<!-- OTHER PAYMENT END-->

								<!-- ECOMMERCE TRANSACTION START -->

								<div class="tab-pane fade" id="store_ecoomerce">
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
													<th>Product</th>
													<th>Status</th>
													<th>Action</th>

												</tr>
											</thead>
											<tbody class="text-dark">
												<?php


												// $total_amount_dbt = 0;
												$credit_amount = 0;

												if (isset($store_ecoomerce) && !empty($store_ecoomerce)) {

													foreach ($store_ecoomerce as  $key => $value) {
														$date = $value->voucherdate;
														$newDate = $date;
														$amount = 0;
														$status = $value->status;
														$find_status = $value->act_status;
												?>
														<tr>

															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<?php

															?>
															<td><a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>"><?= get_title('users', ['id' => $value->user_id], 'name'); ?></a>
															</td>
															<td class="text-success" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Credited">Wallet Credited</td>
															<td><?= $value->voucherdate; ?></td>
															<!-- <td></td> -->
															<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
															</td>
															<td class="text-success">
																<?php
																if ($find_status == 'DBT') {
																	$amount = $value->amount;
																	echo $value->amount;
																	if ($value->status == 1) {
																		$credit_amount = $credit_amount + $amount;
																	}
																	// $total_amount_dbt = $total_amount_dbt + $amount;
																} else {
																	echo 0;
																}

																?>


															</td>
															<?php

															?>
															<td title="Ecommerce">Ecommerce Product</td>
															<td><?php
																echo '<p class="text-success">Approved</p>';

																?></td>
															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>
																	</div>
																</div>
															</td>


														</tr>
													<?php
													}
													?>

												<?php }


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
													echo $credit_amount;

													?>
												</td>


												<td>Balance:</td>
												<!-- <td></td> -->
												<td class="text-success" style="font-size:20px;"><?= $credit_amount ?></td>

												<td></td>
											</tr>


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

<input type="hidden" id="total_Balance" value="<?= $total_amount_cr - $total_amount_dbt ?>">

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

			],
			"lengthMenu": [
				[20, 50, 100, -1],
				[20, 50, 100, "All"]
			],


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

		// Only ASCII character in that range allowed
		var ASCIICode = (evt.which) ? evt.which : evt.keyCode
		if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
			return false;
		return true;
	}

	function OnSubmitHandlers(id, user_id) {

		var user_ids = user_id;
		var msg = document.getElementById('msgs' + id).value;


		if (id != '' && user_ids != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>wallet/reject",
				// data: $("#dataForm").serialize(),
				data: {
					msg: msg,
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
							icon: 'danger',
							title: 'Rejected',
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
			alert("Something is Missing");
		}
	}

	function onRejection(id, user_id) {

		var user_ids = user_id;
		var msg = document.getElementById('msg' + id).value;


		if (id != '' && user_ids != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>wallet/reject",
				// data: $("#dataForm").serialize(),
				data: {
					msg: msg,
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
							icon: 'danger',
							title: 'Rejected',
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
			alert("Something is Missing");
		}
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