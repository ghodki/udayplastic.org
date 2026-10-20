

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<?php
					$roles = $this->session->userdata('roles');
					if ($roles != 1) {
					?>
						<div class="header bg-blue pb-8 pt-5 pt-md-8">
							<div class="container-fluid">
								<div class="header-body ">
									<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Service Request</h2>
									<!-- Card stats -->
									<div class="row">
										<?php if ($this->session->flashdata('msg')) { ?>
											<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
												<?php echo $this->session->flashdata('msg'); ?>
											</div>
										<?php
										}
										$roles = $this->session->userdata('roles');

										?>
										<form method="POST" action="<?= base_url('service_product/save_service'); ?>" class="col-sm-12 col-md-12 col-lg-12">
											<div class="row">
												<div class="col-md-4 col-sm-6 col-lg-4">
													<div class="form-group">
														<label for="service" class="text-white">Service Name</label>

														<select name="service" tabindex="1" id="service" onchange="changeService()" required class="form-control">
															<option value="">--Select--</option>
															<?php
															$service_of_product = $this->user_model->getResults('product_service', ['status' => 1]);
															// echo '<pre>';
															// print_r($product_service);
															if ($service_of_product) {
																foreach ($service_of_product as $key => $perticular_user_data) {
															?>
																	<option value="<?= $perticular_user_data->id ?>"><?= $perticular_user_data->name; ?>(<?= $perticular_user_data->amount ?>)</option>

															<?php	}
															}
															?>
														</select>
													</div>
												</div>
												<div class="col-md-4 col-sm-6 col-lg-4">
													<div class="form-group">
														<label for="qty" class="text-white">Qty</label>
														<input type="number" required="true" min="1" onkeypress="return onlyNumberKey(event)" onchange="changeQty()" onkeyup="changeQty()" class="form-control" value="1" name="qty" id="qty">
													</div>
												</div>
												<div class="col-md-4 col-sm-6 col-lg-4">
													<div class="form-group">
														<label for="charge" class="text-white">Charge</label>
														<input type="tel" min="1" readonly onkeypress="return onlyNumberKey(event)" class="form-control" name="charge" id="charge">
													</div>
												</div>

												<div class="col-md-4 col-sm-6 col-lg-4">
													<div class="form-group">
														<label for="total_amount" class="text-white">Total Amount</label>
														<input type="tel" min="1" readonly onkeypress="return onlyNumberKey(event)" class="form-control" name="total_amount" id="total_amount">
													</div>
												</div>
												<div class="col-md-4 col-sm-6 col-lg-4">
													<div class="form-group">
														<label for="remark" class="text-white">Remark</label>
														<textarea class="form-control" name="remark" id="remark"></textarea>
													</div>
												</div>
											</div>
											<div class="row mt-5">
												<div class="col-md-12 text-center">
													<button type="submit" class="btn btn-default">
														Send
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
					$roles = $this->session->userdata('roles');
					if ($roles == 4 || $roles == 2) {
					?>
						<div class="container-fluid mb-9" style="margin-top:30px;">
							<div class="col">
								<div class="card shadow">
									<div class="card-header border-0">

										<?php if ($this->session->flashdata('msg')) { ?>
											<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
												<?php echo $this->session->flashdata('msg'); ?>
											</div>
										<?php
										}
										?>
										<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Service List</label>
                                        <?php if ($this->session->userdata('roles') == 2) { ?>
                                        <div class="pull-right">
                                            <a href="<?= base_url(); ?>service_product/report" class="btn btn-primary btn-sm" title="Servie Request Report">
                                                <i class="fa fa-file-alt"></i> Report
                                            </a>
                                        </div>
                                        <?php } ?>
										<div class="row m-5">
											<div class="col-md-6">
												<ul class="nav nav-pills">
													<li class="active btn btn-secondary"><a data-toggle="pill" class="active" href="#home">Your Request</a></li>
													<li class="btn btn-secondary"><a data-toggle="pill" href="#menu1">User's Request</a></li>


												</ul>
											</div>
											<div class="col-md-6">

											</div>
										</div>
										<div class="tab-content">
											<div class="tab-pane fade active in show" id="home">
												<div class="table-responsive">
													<table class="table align-items-center table-flush dataTable" style="width: 100%">
														<thead class="thead-light">
															<tr>
																<th>#</th>
																<th>Date</th>
																<th>Service Name</th>
																<th>User</th>
																<th>Charge</th>
																<th>Qty</th>
																<th>Wallet</th>
																<th>Total Amount</th>
																<th>Status</th>
																<th>Remark</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
															<?php

															if (isset($product_service) && !empty($product_service)) {
																foreach ($product_service as $key => $perticular_user_data) {
															?>

																	<div id="reject<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																		<div class="modal-dialog">

																			<!-- Modal content-->
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title text-center text-danger">Rejected Message</h4>

																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																				</div>
																				<div class="modal-body">
																					<!-- <form method="post" action="" ?>)"> -->
																					<div class="form-group">
																						<p><?= $perticular_user_data->reject_msg ?></p>

																					</div>

																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
																				</div>
																			</div>

																		</div>
																	</div>
																	<div id="apprvemsg<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																		<div class="modal-dialog">

																			<!-- Modal content-->
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title text-center text-primary">Approved Message</h4>

																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																				</div>
																				<div class="modal-body">
																					<div class="form-group">
																						<p><?= $perticular_user_data->msg ?></p>


																					</div>

																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
																				</div>
																			</div>

																		</div>
																	</div>
																	<tr>
																		<td><?= $key + 1 ?></td>
																		<td><?= $perticular_user_data->created_at ?></td>
																		<td><?= get_title('product_service', ['id' => $perticular_user_data->pro_id], 'name') ?></td>
																		<td><?= get_title('users', ['id' => $perticular_user_data->user_id], 'name') ?></td>
																		<td><?= $perticular_user_data->charged ?></td>
																		<td><?= $perticular_user_data->qty ?></td>
																		<td><?= get_title('users', ['id' => $perticular_user_data->user_id], 'amount') ?></td>

																		<td><?= $perticular_user_data->total_amount ?></td>
																		<?php
																		if ($perticular_user_data->status == 0) {
																		?>
																			<td class="text-danger"><?= "Pending" ?></td>
																		<?php
																		} else if ($perticular_user_data->status == 1) {
																		?>
																			<!-- <td class="text-success"><?= "Approved" ?></td> -->
																			<td class="text-success" data-toggle="modal" data-target="#apprvemsg<?= $perticular_user_data->id ?>"><?= "Approved" ?></td>

																		<?php
																		} else {
																		?>
																			<td class="text-danger" data-toggle="modal" data-target="#reject<?= $perticular_user_data->id ?>"><?= "Rejected" ?></td>

																		<?php
																		}
																		?>

																		<td><?= $perticular_user_data->remark ?></td>

																		<td class="text-right">
																			<div class="dropdown">
																				<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					<i class="fas fa-ellipsis-v"></i>
																				</a>
																				<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																					<?php

																					if ($this->session->userdata('user_id') == $perticular_user_data->user_id) {
																					?>
																						<?php
																						if ($perticular_user_data->status == 0) {
																						?>


																						<?php
																						} else if ($perticular_user_data->status == 1) {
																						?>
																							<a href="<?= base_url() ?>service_product/viewdetails/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-primary">View</a>

																						<?php
																						} else {
																						?>
																							<a class="dropdown-item cp text-danger">REJECTED</a>

																					<?php
																						}
																					}
																					?>

																				</div>
																			</div>
																		</td>
																	</tr>
															<?php
																}
															}



															?>
														</tbody>
													</table>
												</div>
											</div>
											<div class="tab-pane fade" id="menu1">

												<div class="table-responsive">
													<table class="table align-items-center table-flush dataTable" style="width: 100%">
														<thead class="thead-light">
															<tr>
																<th>#</th>
																<th>Date</th>
																<th>Service Name</th>
																<th>User</th>
																<th>Charge</th>
																<th>Qty</th>
																<th>Wallet</th>
																<th>Total Amount</th>
																<th>Status</th>
																<th>Remark</th>

																<th>Action</th>
															</tr>
														</thead>
														<tbody>
															<?php

															if (isset($difficult_getting_data) && !empty($difficult_getting_data)) {
																foreach ($difficult_getting_data as $key => $perticular_user_data) {
															?>
																	<div id="apprvemsg<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																		<div class="modal-dialog">

																			<!-- Modal content-->
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title text-center text-primary">Approved Message</h4>

																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																				</div>
																				<div class="modal-body">
																					<div class="form-group">
																						<p><?= $perticular_user_data->msg ?></p>


																					</div>

																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
																				</div>
																			</div>

																		</div>
																	</div>

																	<div id="approvemodel<?= $key ?>" class="modal fade" role="dialog">
																		<div class="modal-dialog">

																			<!-- Modal content-->
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title text-center">Message</h4>

																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																				</div>
																				<div class="modal-body">
																					<form method="post">
																						<div class="form-group">
																							<textarea type="text" class="form-control" name="amanmsg" id="amanmsg<?= $perticular_user_data->id ?>"></textarea>

																							<input type="hidden" class="form-control" name="amanuser_id" id="amanuser_id<?= $perticular_user_data->id ?>" value="<?= $perticular_user_data->user_id ?>">

																						</div>
																						<button class="btn btn-success" onclick="approval(<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>)">Approve</button>
																					</form>
																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																				</div>
																			</div>

																		</div>
																	</div>
																	<div id="myModals<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																		<div class="modal-dialog">

																			<!-- Modal content-->
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title text-center">Reject Message</h4>

																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																				</div>
																				<div class="modal-body">
																					<form method="post" action="" onsubmit="return OnSubmitHandler(<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>)">
																						<div class="form-group">
																							<textarea type="text" class="form-control" name="msg" id="msg<?= $perticular_user_data->id ?>"></textarea>
																							<input type="hidden" class="form-control" name="user_id" id="user_id<?= $perticular_user_data->id ?>" value="<?= $perticular_user_data->user_id ?>">
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


																	<div id="reject<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																		<div class="modal-dialog">

																			<!-- Modal content-->
																			<div class="modal-content">
																				<div class="modal-header">
																					<h4 class="modal-title text-center text-danger">Rejected Message</h4>

																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																				</div>
																				<div class="modal-body">
																					<!-- <form method="post" action="" ?>)"> -->
																					<div class="form-group">
																						<p><?= $perticular_user_data->reject_msg ?></p>

																						<!-- <input type="hidden" class="form-control" name="user_id" id="user_id<?= $perticular_user_data->id ?>" value="<?= $perticular_user_data->user_id ?>"> -->

																					</div>
																				</div>
																				<div class="modal-footer">
																					<button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
																				</div>
																			</div>

																		</div>
																	</div>
																	<tr>
																		<td><?= $key + 1 ?></td>
																		<td><?= $perticular_user_data->created_at ?></td>


																		<td><?= get_title('product_service', ['id' => $perticular_user_data->pro_id], 'name') ?></td>
																		<td><?= get_title('users', ['id' => $perticular_user_data->user_id], 'name') ?></td>

																		<td><?= $perticular_user_data->charged ?></td>
																		<td><?= $perticular_user_data->qty ?></td>
																		<td><?= get_title('users', ['id' => $perticular_user_data->user_id], 'amount') ?></td>

																		<?php
																		if ($perticular_user_data->status == 0 && $this->session->userdata('user_id') == $perticular_user_data->user_id) {
																		?>
																			<td><?= $perticular_user_data->total_amount ?></td>

																		<?php
																		} else if ($perticular_user_data->status == 0 || $perticular_user_data->status == 2) {
																			// echo $perticular_user_data->amount;
																		?>
																			<td style="width:150px;"><input type="tel" min="0" onkeypress="return onlyNumberKey(event)" id="amounts<?= $perticular_user_data->id ?>" value="<?= $perticular_user_data->total_amount ?>" class="form-control">
																			</td>
																		<?php
																		} else {
																		?>
																			<td><?= $perticular_user_data->total_amount ?></td>

																		<?php
																		}

																		?>
																		</td>
																		<?php
																		if ($perticular_user_data->status == 0) {
																		?>
																			<td class="text-danger"><?= "Pending" ?></td>
																		<?php
																		} else if ($perticular_user_data->status == 1) {
																		?>
																			<!-- <td class="text-success"><?= "Approved" ?></td> -->
																			<td class="text-success" data-toggle="modal" data-target="#apprvemsg<?= $perticular_user_data->id ?>"><?= "Approved" ?></td>

																		<?php
																		} else {
																		?>
																			<td class="text-danger" data-toggle="modal" data-target="#reject<?= $perticular_user_data->id ?>"><?= "Rejected" ?></td>

																		<?php
																		}
																		?>

																		<td><?= $perticular_user_data->remark ?></td>


																		<td class="text-right">
																			<div class="dropdown">
																				<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																					<i class="fas fa-ellipsis-v"></i>
																				</a>
																				<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																					<?php

																					if ($this->session->userdata('user_id') == $perticular_user_data->user_id) {
																					?>
																						<?php
																						if ($perticular_user_data->status == 0) {
																						?>
																							<!-- <a href="<?= base_url() ?>service_product/delete/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-danger">Not</a> -->

																						<?php
																						} else {
																						?>
																							<a href="<?= base_url() ?>service_product/viewdetails/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-primary">View</a>

																							<a class="dropdown-item cp text-success">Approved</a>

																						<?php
																						}
																					} else {
																						if ($perticular_user_data->status == 0) {
																							// Approval process ADMIN 
																						?>
																							<!-- <a onclick="return Approves('<?= base_url('service_product/approves') ?>',<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>);" class="dropdown-item cp text-success">Approve</a> -->
																							<a data-toggle="modal" data-target="#approvemodel<?= $key ?>" class="dropdown-item cp text-success">Approve</a>

																							<a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#myModals<?= $perticular_user_data->id ?>">Reject</a>
																							<a href="<?= base_url() ?>service_product/delete/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-danger">Delete</a>


																						<?php
																						} else if ($perticular_user_data->status == 1) {
																						?>
																							<a onclick="return Decline('<?= base_url('service_product/decline') ?>',<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>

																							<a href="<?= base_url() ?>service_product/viewdetails/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-primary">View</a>

																						<?php
																						} else {
																						?>
																							<a data-toggle="modal" data-target="#approvemodel<?= $key ?>" class="dropdown-item cp text-success">Approve</a>

																							<!-- <a onclick="return Approves('<?= base_url('service_product/approves') ?>',<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>);" class="dropdown-item cp text-success">Approve</a> -->


																					<?php
																						}
																					}
																					?>

																				</div>
																			</div>
																		</td>
																	</tr>
															<?php
																}
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
					<?php
					} else {
					?>
						<div class="container-fluid mb-9" style="margin-top:20px;">
							<div class="col">
								<div class="card shadow">
									<div class="card-header border-0">
										<label class="mb-5" style="vertical-align: -moz-middle-with-baseline">SERVICE REQUEST</label>
                                        <?php if ($this->session->userdata('roles') == 1) { ?>
                                        <div class="pull-right">
                                            <a href="<?= base_url(); ?>service_product/report" class="btn btn-primary btn-sm" title="Servie Request Report">
                                                <i class="fa fa-file-alt"></i> Report
                                            </a>
                                        </div>
                                        <?php } ?>
										<div class="table-responsive">
											<table class="table align-items-center table-flush dataTable" style="width: 100%">
												<thead class="thead-light">
													<tr>
														<th>#</th>
														<th>Date</th>
														<th>Service Name</th>
														<th>User</th>
														<th>Charge</th>
														<th>Qty</th>
														<th>Wallet</th>
														<th>Total Amount</th>
														<th>Status</th>
														<th>Remark</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php

													if (isset($product_service) && !empty($product_service)) {
														foreach ($product_service as $key => $perticular_user_data) {
													?>


															<div id="approvemodel<?= $key ?>" class="modal fade" role="dialog">
																<div class="modal-dialog">

																	<!-- Modal content-->
																	<div class="modal-content">
																		<div class="modal-header">
																			<h4 class="modal-title text-center">Message</h4>

																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<div class="modal-body">
																			<form method="post">
																				<div class="form-group">
																					<textarea type="text" class="form-control" name="amanmsg" id="amanmsg<?= $perticular_user_data->id ?>"></textarea>

																					<input type="hidden" class="form-control" name="amanuser_id" id="amanuser_id<?= $perticular_user_data->id ?>" value="<?= $perticular_user_data->user_id ?>">

																				</div>
																				<button class="btn btn-success" onclick="approval(<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>)">Approve</button>
																			</form>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																		</div>
																	</div>

																</div>
															</div>
															<div id="myModal<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																<div class="modal-dialog">

																	<!-- Modal content-->
																	<div class="modal-content">
																		<div class="modal-header">
																			<h4 class="modal-title text-center">Reject Message</h4>

																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<div class="modal-body">
																			<form method="post" action="" onsubmit="return OnSubmitHandler(<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>)">
																				<div class="form-group">
																					<textarea type="text" class="form-control" name="msg" id="msg<?= $perticular_user_data->id ?>"></textarea>

																					<input type="hidden" class="form-control" name="user_id" id="user_id<?= $perticular_user_data->id ?>" value="<?= $perticular_user_data->user_id ?>">

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
															<div id="reject<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																<div class="modal-dialog">

																	<!-- Modal content-->
																	<div class="modal-content">
																		<div class="modal-header">
																			<h4 class="modal-title text-center text-danger">Rejected Message</h4>

																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<div class="modal-body">
																			<div class="form-group">
																				<p><?= $perticular_user_data->reject_msg ?></p>


																			</div>

																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
																		</div>
																	</div>

																</div>
															</div>

															<div id="apprvemsg<?= $perticular_user_data->id ?>" class="modal fade" role="dialog">
																<div class="modal-dialog">

																	<!-- Modal content-->
																	<div class="modal-content">
																		<div class="modal-header">
																			<h4 class="modal-title text-center text-primary">Approved Message</h4>

																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		</div>
																		<div class="modal-body">
																			<div class="form-group">
																				<p><?= $perticular_user_data->msg ?></p>


																			</div>

																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
																		</div>
																	</div>

																</div>
															</div>
															<tr>
																<td><?= $key + 1 ?></td>
																<td><?= $perticular_user_data->created_at ?></td>

																<td><?= get_title('product_service', ['id' => $perticular_user_data->pro_id], 'name') ?></td>
																<td><?= get_title('users', ['id' => $perticular_user_data->user_id], 'name') ?></td>

																<td><?= $perticular_user_data->charged ?></td>
																<td><?= $perticular_user_data->qty ?></td>
																<td><?= get_title('users', ['id' => $perticular_user_data->user_id], 'amount') ?></td>


																<?php
																if ($perticular_user_data->status == 0 || $perticular_user_data->status == 2 && $this->session->userdata('user_id') == $perticular_user_data->user_id) {
																?>
																	<td><?= $perticular_user_data->total_amount ?></td>

																<?php
																} else if ($perticular_user_data->status == 0 || $perticular_user_data->status == 2) {
																	// echo $perticular_user_data->amount;
																?>
																	<td style="width:150px;"><input type="tel" min="0" onkeypress="return onlyNumberKey(event)" id="amounts<?= $perticular_user_data->id ?>" value="<?= $perticular_user_data->total_amount ?>" class="form-control">
																	</td>
																<?php
																} else {
																?>
																	<td><?= $perticular_user_data->total_amount ?></td>

																<?php
																}

																?>
																</td>
																<?php
																if ($perticular_user_data->status == 0) {
																?>
																	<td class="text-danger"><?= "Pending" ?></td>
																<?php
																} else if ($perticular_user_data->status == 1) {
																?>
																	<!-- <td class="text-success"><?= "Approved" ?></td> -->
																	<td class="text-success" data-toggle="modal" data-target="#apprvemsg<?= $perticular_user_data->id ?>"><?= "Approved" ?></td>

																<?php
																} else {
																?>
																	<td class="text-danger" data-toggle="modal" data-target="#reject<?= $perticular_user_data->id ?>"><?= "Rejected" ?></td>

																<?php
																}
																?>

																<td><?= $perticular_user_data->remark ?></td>

																<td class="text-right">
																	<div class="dropdown">
																		<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			<i class="fas fa-ellipsis-v"></i>
																		</a>
																		<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

																			<?php

																			if ($this->session->userdata('user_id') == $perticular_user_data->user_id) {
																			?>

																				<?php
																				if ($perticular_user_data->status == 0) {
																				?>

																				<?php
																				} else if ($perticular_user_data->status == 1) {
																				?>
																					<a href="<?= base_url() ?>service_product/viewdetails/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-primary">View</a>

																					<a class="dropdown-item cp text-success">Approved</a>

																				<?php
																				} else {
																				?>

																					<a class="dropdown-item cp text-danger">REJECTED</a>

																				<?php
																				}
																				// WHO IS UPLOADED TILL HERE
																			} else {
																				if ($perticular_user_data->status == 0) {
																					// Approval process ADMIN 
																				?>
																					<a data-toggle="modal" data-target="#approvemodel<?= $key ?>" class="dropdown-item cp text-success">Approve</a>
																					<a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#myModal<?= $perticular_user_data->id ?>">Reject</a>
																					<a href="<?= base_url() ?>service_product/delete/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-danger">Delete</a>

																				<?php
																				} else if ($perticular_user_data->status == 1) {
																				?>
																					<a onclick="return Decline('<?= base_url('service_product/decline') ?>',<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>
																					<a href="<?= base_url() ?>service_product/viewdetails/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-primary">View</a>


																				<?php
																				} else {
																				?>
																					<a data-toggle="modal" data-target="#approvemodel<?= $key ?>" class="dropdown-item cp text-success">Approve</a>

																					<!-- <a onclick="return Approves('<?= base_url('service_product/approves') ?>',<?= $perticular_user_data->id ?>,<?= $perticular_user_data->user_id ?>);" class="dropdown-item cp text-success">Approve</a> -->
																					<a href="<?= base_url() ?>service_product/delete/<?= $perticular_user_data->id ?>" class="dropdown-item cp text-danger">Delete</a>


																			<?php
																				}
																			}
																			?>

																		</div>
																	</div>
																</td>
															</tr>
													<?php
														}
													}

													?>
												</tbody>
											</table>
										</div>
									</div>

								</div>

							</div>
						</div>

					<?php
					}

					?>
				</div>
			</div>

		</div>
	</div>

</div>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
<script>
	$(function() {
		$(".dataTable").DataTable({
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

	function onlyNumberKey(evt) {

		// Only ASCII character in that range allowed
		var ASCIICode = (evt.which) ? evt.which : evt.keyCode
		if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
			return false;
		return true;
	}

	function Decline(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "success",
			showCancelButton: true,
			confirmButtonColor: "danger",
			cancelButtonColor: "success",
			confirmButtonText: "Yes, Decline it!",
		}).then((result) => {
			if (result.isConfirmed) {
				decline(url, id, user_id);
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

	function decline(url, id, user_id) {

		$.ajax({
			type: "POST",
			url: url,
			data: {
				id: id,
				user_id: user_id,
			},
			success: function(success) {
				success = JSON.parse(success);
				console.log(success);
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

	function changeService() {
		let service_id = document.getElementById('service').value;
		if (service_id) {

			$.ajax({
				type: "POST",
				url: '<?= base_url() ?>service_product/getPrice',
				data: {
					service_id: service_id,

				},
				success: function(success) {
					success = JSON.parse(success);
					console.log(success);
					if (success.status == true) {
						let alldata = success.data;
						let price = alldata.amount;
						let qty = document.getElementById('qty').value;
						document.getElementById('charge').value = price;

						if (qty == '') {
							let qty = document.getElementById('qty').value = 1;
							document.getElementById('total_amount').value = price * qty;
						} else {
							let qty = document.getElementById('qty').value;
							let total = qty * price;
							document.getElementById('total_amount').value = total;
						}

					} else {
						document.getElementById('qty').value = 1;

						document.getElementById('total_amount').value = 0;

					}
				},
			});
		} else {
			document.getElementById('charge').value = 0;
			document.getElementById('total_amount').value = 0;
		}

	}

	function changeQty() {
		let qty = document.getElementById('qty').value;
		let service_id = document.getElementById('service').value;
		if (service_id) {
			let charge = document.getElementById('charge').value;
			document.getElementById('total_amount').value = charge * qty;
		} else {
			document.getElementById('charge').value = 0;
			document.getElementById('total_amount').value = 0;


		}



	}
</script>
<script>
	function Approves(url, id, user_id) {
		Swal.fire({
			title: "Are you sure to approve?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "success",
			confirmButtonText: "Yes, Approve it!",
		}).then((result) => {
			if (result.isConfirmed) {
				addRow(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				})
			}
		});
	}


	function addRow(url, id, user_id) {
		var amounts = document.getElementById('amounts' + id).value;
		// console.log(id, "id");
		// alert(url)
		// alert(user_id)
		// alert(id)

		$.ajax({
			type: "POST",
			url: url,
			data: {
				amount: amounts,
				id: id,
				user_id: user_id,

			},
			success: function(success) {
				success = JSON.parse(success);
				console.log(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000
					})
					setTimeout(function() {
						window.location.reload();
					}, 2000)
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					})
				}
			},
		});
	};
</script>
<script>
    function approval(id, user_id) {
        var amounts = document.getElementById('amounts' + id).value;
        var msg = document.getElementById('amanmsg' + id).value;
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>service_product/approves",
            data    :   {amount: amounts, id: id, user_id: user_id, msg: msg},
            success :   function(response) {
                            var responseData = JSON.parse(response);
                            if (responseData.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: responseData.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                    //window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: responseData.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
			}
        });
    }

	// function onApprove(id, user_id) {
	// 	var user_ids = user_id;
	// 	var msg = document.getElementById('msg' + id).value;
	// 	if (id != '' && user_ids != '') {
	// 		$.ajax({
	// 			type: "POST",
	// 			url: "<?= base_url() ?>service_product/reject",
	// 			// data: $("#dataForm").serialize(),
	// 			data: {
	// 				msg: msg,
	// 				user_id: user_ids,
	// 				id: id,

	// 			},
	// 			success: function(invoiceNo) {
	// 				invoiceNo = JSON.parse(invoiceNo);
	// 				// console.log(invoiceNo, "dtaaaa");
	// 				// alert(invoiceNo)
	// 				if (invoiceNo.status == true) {

	// 					Swal.fire({
	// 						position: 'top-end',
	// 						icon: 'danger',
	// 						title: 'Rejected',
	// 						showConfirmButton: false,
	// 						timer: 2000
	// 					})
	// 					setTimeout(function() {
	// 						window.reload();
	// 					}, 2000)

	// 				} else {
	// 					Swal.fire({
	// 						position: 'top-end',
	// 						icon: 'error',
	// 						title: invoiceNo.msg,
	// 						showConfirmButton: false,
	// 						timer: 2000
	// 					})
	// 				}

	// 			},
	// 		});

	// 	} else {
	// 		alert("Something is Missing");
	// 	}
	// }
	function OnSubmitHandler(id, user_id) {
		var user_ids = user_id;
		var msg = document.getElementById('msg' + id).value;
		if (id != '' && user_ids != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>service_product/reject",
				// data: $("#dataForm").serialize(),
				data: {
					msg: msg,
					user_id: user_ids,
					id: id,

				},
				success: function(invoiceNo) {
					invoiceNo = JSON.parse(invoiceNo);
					// console.log(invoiceNo, "dtaaaa");
					// alert(invoiceNo)
					if (invoiceNo.status == true) {

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

	function removeRow(url, id, user_id) {
		var amounts = document.getElementById('amount' + id).value;
		// console.log(id, "id");

		$.ajax({
			type: "POST",
			url: url,
			data: {
				amount: amounts,
				id: id,
				user_id: user_id,

			},
			success: function(success) {
				success = JSON.parse(success);
				console.log(success);
				if (success.status == true) {
					Swal.fire({
						position: "top-end",
						icon: "success",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000
					})
					setTimeout(function() {
						window.location.reload();
					}, 2000)
				} else {
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: success.msg,
						showConfirmButton: false,
						timer: 2000,
					})
				}
			},
		});
	}
</script>
<script>
	function Delete(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "danger",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "danger",
			confirmButtonText: "Yes, Delete it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRows(url, id, user_id);
			} else {
				Swal.fire({
					position: "top-end",
					icon: "info",
					title: "Cancelled",
					showConfirmButton: false,
					timer: 2000,
				})
			}
		});
	}

	function changeStatus(service_id, status) {
		let returnValue = confirm("Are You Sure Want to change Status?");
		if (returnValue) {
			$.ajax({
				url: "<?= base_url() ?>productServiceChangeStatus",
				type: 'post',
				data: {
					service_id: service_id,
					status: status
				},
				dataType: 'json',
				success: function(response) {
					console.log(response.status);
					if (response.status == 'success') {
						toastr.success(response.msg);
						setTimeout(() => {
							window.location.reload();
						}, 1000);
					} else {
						toastr.error(response.msg);
					}
				}
			});
		}
	}
</script>