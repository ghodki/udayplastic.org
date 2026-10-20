<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">

			<div class="row mt-2">

				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="mt-5" style="margin-top:4em!important">

						<!-- <div class="container"> -->
						<div class="container-fluid">
							<?php if ($this->session->flashdata('msg')) { ?>
								<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
									<?php echo $this->session->flashdata('msg'); ?>
								</div>
							<?php

							}
							?>
							<div class="header-body ">
								<ul class="nav nav nav-tabs nav-pills">
									<li class="nav-link"><a data-toggle="pill" href="#all" onclick="refreshpage()">All</a></li>

									<?php
									$category = $this->user_model->getResult('categoryfilesharing');
									if (isset($category) && !empty($category)) {
										foreach ($category as $key => $categories) {
									?>
											<li class="nav-link"><a data-toggle="pill" href="#aman<?= $key ?>"><?= $categories->name ?></a></li>

									<?php
										}
									}

									?>
								</ul>
								<div class="container">

									<form action="" id="reportForm" onsubmit="onFormSubmitHandler(event)" method="get">

										<div class="row mt-5">

											<?php
											$date = date('Y-m-d');
											?>
											<div class="form-group col-md-4 col-sm-6">
												<label for="fromDate">From Date</label>
												<input type="date" tabindex="1" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : $date ?>" class="form-control" />
											</div>
											<div class="form-group col-md-4 col-sm-6">
												<label for="toDate">To Date</label>
												<input type="date" tabindex="2" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : $date ?>" class="form-control" />
											</div>
											<div class="form-group col-md-4 col-sm-6" style="margin-top:31px;">


												<button type="submit" class="btn btn-primary" tabindex="3" onclick="return search()"> Search</button> &nbsp
												<button type="button" class="btn btn-info" tabindex="4"><a href="<?= base_url() ?>file_sharing" class="text-muted">Reset</a></button> &nbsp

											</div>




										</div>
									</form>
								</div>
								<div class="tab-content">
									<div id="all" class="tab-pane fade show active">
										<div class="container-fluid mb-9">
											<div class="col">
												<div class="card shadow">
													<div class="card-header border-0" style="margin-top:70px;">
														<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">File
															Shared List</label>
														<div class="row">
															<div class="col-md-6">
																<ul class="nav nav-pills">
																	<li class="active"><a data-toggle="pill" class="active" href="#home">Your Request</a></li>
																	<li><a data-toggle="pill" href="#menu1">User's Request</a></li>
																</ul>
															</div>

															<div class="col-md-6">

															</div>
														</div>
														<div class="tab-content">
															<div class="tab-pane fade active in show" id="home">
																<div class="table-responsive">
																	<table class="table table-striped align-items-center table-flush dataTable1" style="width: 100%" id="dataTable1">
																		<thead class="thead-dark">
																			<tr>
																				<th>#</th>
																				<th>Date</th>
																				<th>Category</th>
																				<th>File Shared</th>
																				<th>Send By</th>
																				<!-- <th>Description</th> -->
																				<th>Status</th>
																				<th>Wallet</th>
																				<th>Amt</th>
																				<th>Des</th>

																				<th>Reply</th>
																				<th>Action</th>


																			</tr>
																		</thead>
																		<tbody class="text-dark">
																			<?php
																			// echo '<pre>';
																			// print_r($file_sharing);
																			// die;
																			// if ($this->session->userdata('roles') != 2) {
																			if (isset($subadmin_data) && $subadmin_data != '') {
																				foreach ($subadmin_data as  $key => $value) {
																					$date = $value->date;
																					// $newDate = date("d-m-Y", strtotime($date));
																			?>
																					<div class="modal fade" id="exampleModal<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																						<div class="modal-dialog" role="document">
																							<div class="modal-content">
																								<div class="modal-header">
																									<form class="form" action="<?= base_url() ?>wallet/updateAmount" method="post">

																										<h5 class="modal-title" id="exampleModalLabel">Edit
																											File Sharing</h5>
																										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																											<span aria-hidden="true">&times;</span>
																										</button>
																								</div>
																								<div class="modal-body">
																									<input type="hidden" name="user_id" value="<?= $value->user_id ?>">
																									<input type="hidden" name="id" value="<?= $value->id ?>">
																									<input type="hidden" name="category_id" value="<?= $value->category ?>">

																									<div class="form-group">

																										<label for="amount">Category:</label>
																										<input type="text" readonly class="form-control" value="<?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>">
																									</div>
																									<div class="form-group">
																										<label for="amount">Amount:</label>
																										<input type="number" name="amount" class="form-control" value="<?= $value->amount ?>">
																									</div>


																								</div>
																								<div class="modal-footer">
																									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																									<button type="submit" class="btn btn-primary">Update</button>
																								</div>
																							</div>
																							</form>
																						</div>
																					</div>

																					<div id="myModal<?= $value->id ?>" class="modal fade" role="dialog">
																						<div class="modal-dialog">

																							<!-- Modal content-->
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title text-center">Reject Message</h4>

																									<button type="button" class="close" data-dismiss="modal">&times;</button>
																								</div>
																								<div class="modal-body">
																									<form method="post" action="" onsubmit="OnSubmitHandler(<?= $value->id ?>,<?= $value->user_id ?>)">
																										<div class="form-group">
																											<textarea type="text" class="form-control" name="msg" id="msg<?= $value->id ?>"></textarea>

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

																					<div id="myModals<?= $value->id ?>" class="modal fade" role="dialog">
																						<div class="modal-dialog">

																							<!-- Modal content-->
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title text-center text-danger">Rejected Message</h4>

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
																					<div id="amanModelss<?= $value->id ?>" class="modal fade" role="dialog">
																						<div class="modal-dialog">

																							<!-- Modal content-->
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title text-center">Message</h4>

																									<button type="button" class="close" data-dismiss="modal">&times;</button>
																								</div>
																								<div class="modal-body">
																									<h5 class="text-center text-danger">Can't See Because File is Not Approved so for.....</h5>
																								</div>
																								<div class="modal-footer">
																									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																								</div>
																							</div>

																						</div>
																					</div>
																					<tr>
																						<td>
																							<span><?= $key + 1; ?></span>
																						</td>
																						<td><?= $date; ?></td>
																						<td><?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>
																						</td>

																						<?php
																						$where = ['id' => $value->user_id];
																						$username = $this->user_model->getRow('users', $where);
																						?>
																						<td><a href="<?= base_url('uploads/file_share/') . $value->file_name; ?>" download>Download</a></td>
																						<?php
																						$where = ['id' => $value->user_id];
																						$sendby_name = $this->user_model->getRow('users', $where);
																						// echo $sendby_name->amount;
																						// die;
																						if ($value->role == 3) {
																							$send = "Send";
																						} else {
																							$send = "Recieve";
																						}
																						?>
																						<td><?= $sendby_name->name; ?><span><i>(<?php echo $send; ?>)</i>
																							</span></td>

																						<?php
																						if ($value->status == 0) {
																						?>
																							<td class="text-danger">Pending</td>
																						<?php
																						} else if ($value->status == 1) {
																						?>
																							<td class="text-success">Approved</td>

																						<?php
																						} else {
																						?>
																							<td class="text-danger" data-toggle="modal" data-target="#myModals<?= $value->id ?>" title="Know About Rejection">Rejected</td>

																						<?php
																						}
																						?>
																						<td class="success">
																							<?= get_title('users', ['id' => $value->user_id], 'amount'); ?>
																						</td>
																						<td>
																							<?php
																							if ($value->status == 0 && $this->session->userdata('user_id') != $value->user_id) {
																							?>
																								<input type="tel" min="0" id="amounts<?= $value->id ?>" value="" class="form-control">
																							<?php
																							} else if ($value->status == 2 && $this->session->userdata('user_id') != $value->user_id) {
																								// echo $value->amount;
																							?>
																								<input type="tel" min="0" id="amounts<?= $value->id ?>" value="0" class="form-control">

																							<?php
																							} else {
																								echo $value->amount;
																							}
																							?>
																						</td>
																						<td><?= $value->des; ?></td>

																						<td>
																							<?php

																							if ($this->session->userdata('roles') == 3) {
																								if ($value->status == 1) {
																							?>
																									<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																								<?php
																								} else {
																								?>
																									<a href="#" class="text-danger" title="Due to Not Approving" data-toggle="modal" data-target="#amanModelss<?= $value->id ?>">View Reply</a>

																								<?php
																								}
																							} else {
																								?>
																								<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																							<?php
																							}
																							?>
																						</td>
																						<td class="text-right">
																							<div class="dropdown">
																								<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																									<i class="fas fa-ellipsis-v"></i>
																								</a>
																								<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																									<?php

																									if ($this->session->userdata('roles') == 3) {
																										if ($value->status == 1) {
																									?>
																											<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																										<?php
																										}
																									} else {
																										?>
																										<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																									<?php
																									}
																									if ($this->session->userdata('user_id') == $value->user_id) {
																									?>
																										<?php
																										if ($value->status == 0) {
																										?>
																											<?php
																											$where = ['file_sharing_id' => $value->id];

																											$files_id_mil_gayyi = $this->user_model->getRow('file_share_chat', $where);
																											if (!$files_id_mil_gayyi) {
																											?>
																												<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>
																											<?php
																											} else {
																											?>
																												<a class="dropdown-item cp text-danger">Can't Delete</a>

																											<?php
																											}

																											?>
																										<?php
																										} else if ($value->status == 1) {
																										?>
																											<a class="dropdown-item cp text-success">Approved</a>

																										<?php
																										} else {
																										?>
																											<a class="dropdown-item cp text-danger">Rejected</a>

																										<?php
																										}
																									} else {
																										?>

																										<?php
																										if ($value->status == 0) {
																										?>
																											<a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#myModal<?= $value->id ?>">Reject</a>

																											<a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
																											<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																											<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																										<?php
																										} else if ($value->status == 1) {
																										?>
																											<a onclick="return Decline('<?= base_url('file_sharing/decline') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>


																										<?php
																										} else {
																										?>
																											<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																											<a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>

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

																			<?php
																				}
																			}
																			// }else
																			// {

																			// }

																			?>


																		</tbody>
																	</table>
																</div>
															</div>
															<div class="tab-pane fade" id="menu1">
																<div class="table-responsive">
																	<table class="table table-striped align-items-center table-flush dataTable1" style="width: 100%" id="dataTable1">
																		<thead class="thead-dark">
																			<tr>
																				<th>#AM</th>
																				<th>Date</th>
																				<th>Category</th>
																				<th>File Shared</th>
																				<th>Send By</th>
																				<!-- <th>Description</th> -->
																				<th>Status</th>
																				<th>Wallet</th>
																				<th>Amt</th>
																				<th>Des</th>

																				<th>Reply</th>
																				<th>Action</th>


																			</tr>
																		</thead>
																		<tbody class="text-dark">
																			<?php
																			// echo '<pre>';
																			// print_r($file_sharing);
																			// die;
																			// if ($this->session->userdata('roles') != 2) {
																			if (isset($file_sharing) && $file_sharing != '') {
																				foreach ($file_sharing as  $key => $value) {
																					$date = $value->date;
																					// $newDate = date("d-m-Y", strtotime($date));
																			?>
																					<div class="modal fade" id="exampleModal<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																						<div class="modal-dialog" role="document">
																							<div class="modal-content">
																								<div class="modal-header">
																									<form class="form" action="<?= base_url() ?>wallet/updateAmount" method="post">

																										<h5 class="modal-title" id="exampleModalLabel">Edit
																											File Sharing</h5>
																										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																											<span aria-hidden="true">&times;</span>
																										</button>
																								</div>
																								<div class="modal-body">
																									<input type="hidden" name="user_id" value="<?= $value->user_id ?>">
																									<input type="hidden" name="id" value="<?= $value->id ?>">
																									<input type="hidden" name="category_id" value="<?= $value->category ?>">

																									<div class="form-group">

																										<label for="amount">Category:</label>
																										<input type="text" readonly class="form-control" value="<?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>">
																									</div>
																									<div class="form-group">
																										<label for="amount">Amount:</label>
																										<input type="number" name="amount" class="form-control" value="<?= $value->amount ?>">
																									</div>


																								</div>
																								<div class="modal-footer">
																									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																									<button type="submit" class="btn btn-primary">Update</button>
																								</div>
																							</div>
																							</form>
																						</div>
																					</div>

																					<div id="myModal<?= $value->id ?>" class="modal fade" role="dialog">
																						<div class="modal-dialog">

																							<!-- Modal content-->
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title text-center">Reject Message</h4>

																									<button type="button" class="close" data-dismiss="modal">&times;</button>
																								</div>
																								<div class="modal-body">
																									<form method="post" action="" onsubmit="OnSubmitHandler(<?= $value->id ?>,<?= $value->user_id ?>)">
																										<div class="form-group">
																											<textarea type="text" class="form-control" name="msg" id="msg<?= $value->id ?>"></textarea>

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

																					<div id="myModals<?= $value->id ?>" class="modal fade" role="dialog">
																						<div class="modal-dialog">

																							<!-- Modal content-->
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title text-center text-danger">Rejected Message</h4>

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
																					<div id="amanModelss<?= $value->id ?>" class="modal fade" role="dialog">
																						<div class="modal-dialog">

																							<!-- Modal content-->
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title text-center">Message</h4>

																									<button type="button" class="close" data-dismiss="modal">&times;</button>
																								</div>
																								<div class="modal-body">
																									<h5 class="text-center text-danger">Can't See Because File is Not Approved so for.....</h5>
																								</div>
																								<div class="modal-footer">
																									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																								</div>
																							</div>

																						</div>
																					</div>
																					<tr>
																						<td>
																							<span><?= $key + 1; ?></span>
																						</td>
																						<td><?= $date; ?></td>
																						<td><?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>
																						</td>

																						<?php
																						$where = ['id' => $value->user_id];
																						$username = $this->user_model->getRow('users', $where);
																						?>
																						<td><a href="<?= base_url('uploads/file_share/') . $value->file_name; ?>" download>Download</a></td>
																						<?php
																						$where = ['id' => $value->user_id];
																						$sendby_name = $this->user_model->getRow('users', $where);
																						// echo $sendby_name->amount;
																						// die;
																						if ($value->role == 3) {
																							$send = "Send";
																						} else {
																							$send = "Recieve";
																						}
																						?>
																						<td><?= $sendby_name->name; ?><span><i>(<?php echo $send; ?>)</i>
																							</span></td>

																						<?php
																						if ($value->status == 0) {
																						?>
																							<td class="text-danger">Pending</td>
																						<?php
																						} else if ($value->status == 1) {
																						?>
																							<td class="text-success">Approved</td>

																						<?php
																						} else {
																						?>
																							<td class="text-danger" data-toggle="modal" data-target="#myModals<?= $value->id ?>" title="Know About Rejection">Rejected</td>

																						<?php
																						}
																						?>
																						<td class="success">
																							<?= get_title('users', ['id' => $value->user_id], 'amount'); ?>
																						</td>
																						<td>
																							<?php
																							if ($value->status == 0 && $this->session->userdata('user_id') != $value->user_id) {
																							?>
																								<input type="tel" min="0" id="amounts<?= $value->id ?>" value="" class="form-control">
																							<?php
																							} else if ($value->status == 2 && $this->session->userdata('user_id') != $value->user_id) {
																								// echo $value->amount;
																							?>
																								<input type="tel" min="0" id="amounts<?= $value->id ?>" value="0" class="form-control">

																							<?php
																							} else {
																								echo $value->amount;
																							}
																							?>
																						</td>
																						<td><?= $value->des; ?></td>

																						<td>
																							<?php

																							if ($this->session->userdata('roles') == 3) {
																								if ($value->status == 1) {
																							?>
																									<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																								<?php
																								} else {
																								?>
																									<a href="#" class="text-danger" title="Due to Not Approving" data-toggle="modal" data-target="#amanModelss<?= $value->id ?>">View Reply</a>

																								<?php
																								}
																							} else {
																								?>
																								<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																							<?php
																							}
																							?>
																						</td>
																						<td class="text-right">
																							<div class="dropdown">
																								<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																									<i class="fas fa-ellipsis-v"></i>
																								</a>
																								<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																									<?php

																									if ($this->session->userdata('roles') == 3) {
																										if ($value->status == 1) {
																									?>
																											<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																										<?php
																										}
																									} else {
																										?>
																										<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																									<?php
																									}
																									if ($this->session->userdata('user_id') == $value->user_id) {
																									?>
																										<?php
																										if ($value->status == 0) {
																										?>
																											<?php
																											$where = ['file_sharing_id' => $value->id];

																											$files_id_mil_gayyi = $this->user_model->getRow('file_share_chat', $where);
																											if (!$files_id_mil_gayyi) {
																											?>
																												<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>
																											<?php
																											} else {
																											?>
																												<a class="dropdown-item cp text-danger">Can't Delete</a>

																											<?php
																											}

																											?>
																										<?php
																										} else if ($value->status == 1) {
																										?>
																											<a class="dropdown-item cp text-success">Approved</a>

																										<?php
																										} else {
																										?>
																											<a class="dropdown-item cp text-danger">Rejected</a>

																										<?php
																										}
																									} else {
																										?>

																										<?php
																										if ($value->status == 0) {
																										?>
																											<a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#myModal<?= $value->id ?>">Reject</a>

																											<a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
																											<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																											<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																										<?php
																										} else if ($value->status == 1) {
																										?>
																											<a onclick="return Decline('<?= base_url('file_sharing/decline') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>


																										<?php
																										} else {
																										?>
																											<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																											<a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>

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

																			<?php
																				}
																			}
																			// }else
																			// {

																			// }

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

									<?php
									// <?php
									$category = $this->user_model->getResult('categoryfilesharing');
									if (isset($category) && !empty($category)) {
										foreach ($category as $key => $categories) {
									?>
											<div id="aman<?= $key ?>" class="tab-pane fade">
												<h3 class="text-center"><?= $categories->name ?></h3>

												<?php
												// if ($this->session->userdata('roles') == 3) {
												// 
												?>



												<div class="header bg-blue pb-8 pt-5 pt-md-8">
													<div class="container-fluid">
														<div class="header-body">

															<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">File Share</h2>
															<!-- Card stats -->
															<div class="row">
																<?php if ($this->session->flashdata('msg')) { ?>
																	<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
																		<?php echo $this->session->flashdata('msg'); ?>
																	</div>
																<?php

																}
																?>
																<form method="POST" action="<?= base_url('file_sharing/filesharesave'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">
																	<div class="row">
																		<input type="hidden" name="id" value="<?= $this->session->userdata('user_id'); ?>">
																		<input type="hidden" name="category" value="<?= $categories->id ?>">
																		<?php
																		date_default_timezone_set('Asia/Kolkata');

																		?>
																		<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																			<div class="form-group">
																				<input id="createdAt" name="date" type="text" class="form-control" value="<?= date('d-m-Y h:ia') ?>" readonly required="true">

																			</div>
																		</div>
																		<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																			<div class="form-group">
																				<input type="file" class="form-control" required name="image" size="20" />
																			</div>
																		</div>
																		<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																			<div class="form-group">
																				<textarea id="name" type="text" class="form-control" name="name" required="true"></textarea>
																			</div>
																		</div>
																	</div>
																	<div class="row mt-5">
																		<div class="col-md-12 text-center" style="margin-bottom: 32px;">
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
												// }
												// 
												?>


												<div class="container-fluid mb-9">

													<div class="col">
														<div class="card shadow">
															<div class="card-header border-0" style="margin-top:70px;">
																<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">File
																	Shared List</label>
																<div class="row mt-5">

																</div>
																<div class="table-responsive">
																	<table class="table table-striped align-items-center table-flush dataTable1" style="width: 100%">
																		<thead class="thead-dark">
																			<tr>
																				<th>#</th>
																				<th>Date</th>
																				<th>Category</th>
																				<th>File Shared</th>
																				<th>Send By</th>
																				<!-- <th>Description</th> -->
																				<th>Status</th>
																				<th>Wallet</th>
																				<th>Amt</th>
																				<th>Des</th>
																				<th>Reply</th>
																				<th>Action</th>
																			</tr>
																		</thead>
																		<tbody class="text-dark">
																			<?php
																			// print_r($sms_history);
																			// die;
																			// if ($this->session->userdata('roles') != 2) {
																			if (isset($file_sharing) && $file_sharing != '') {
																				foreach ($file_sharing as  $key => $value) {
																					if ($value->category == $categories->id) {
																						// print_r($categories->id);
																						$date = $value->date;
																						// $newDate = date("d-m-Y", strtotime($date));
																			?>
																						<div id="myModalss<?= $value->id ?>" class="modal fade" role="dialog">
																							<div class="modal-dialog">

																								<!-- Modal content-->
																								<div class="modal-content">
																									<div class="modal-header">
																										<h4 class="modal-title text-center text-danger">Rejected Message</h4>

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

																						<div id="amanModels<?= $value->id ?>" class="modal fade" role="dialog">
																							<div class="modal-dialog">

																								<!-- Modal content-->
																								<div class="modal-content">
																									<div class="modal-header">
																										<h4 class="modal-title text-center">Message</h4>

																										<button type="button" class="close" data-dismiss="modal">&times;</button>
																									</div>
																									<div class="modal-body">
																										<h5 class="text-center text-danger">Can't See Because File is Not Approved so for.....</h5>
																									</div>
																									<div class="modal-footer">
																										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																									</div>
																								</div>

																							</div>
																						</div>
																						<tr>
																							<td>
																								<span><?= $key + 1; ?></span>
																							</td>
																							<td><?= $date; ?></td>
																							<td><?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>
																							</td>

																							<?php
																							$where = ['id' => $value->user_id];
																							$username = $this->user_model->getRow('users', $where);
																							?>
																							<td><a href="<?= base_url('uploads/file_share/') . $value->file_name; ?>" download>Download</a></td>
																							<?php
																							$where = ['id' => $value->user_id];
																							$sendby_name = $this->user_model->getRow('users', $where);
																							if ($value->role == 3) {
																								$send = "Send";
																							} else {
																								$send = "Recieve";
																							}
																							?>
																							<td><?= $sendby_name->name; ?><span><i>(<?php echo $send; ?>)</i>
																								</span></td>

																							<?php
																							if ($value->status == 0) {
																							?>
																								<td class="text-danger">Pending</td>
																							<?php
																							} else if ($value->status == 1) {
																							?>
																								<td class="text-success">Approved</td>

																							<?php
																							} else {
																							?>
																								<td class="text-danger" data-toggle="modal" data-target="#myModalss<?= $value->id ?>" title="Know About Rejection">Rejected</td>


																							<?php
																							}
																							?>
																							<td class="success">
																								<?= get_title('users', ['id' => $value->user_id], 'amount'); ?>
																							</td>
																							<td>
																								<?php
																								if ($value->status == 0 && $this->session->userdata('user_id') != $value->user_id) {
																								?>
																									<input type="tel" min="0" id="amount<?= $value->id ?>" value="" class="form-control">
																								<?php
																								} else if ($value->status == 2 && $this->session->userdata('user_id') != $value->user_id) {
																									// echo $value->amount;
																								?>
																									<input type="tel" min="0" id="amount<?= $value->id ?>" value="0" class="form-control">

																								<?php
																								} else {
																									echo $value->amount;
																								}
																								?>
																							</td>
																							<td><?= $value->des; ?></td>

																							<td>
																								<?php

																								if ($this->session->userdata('roles') == 3) {
																									if ($value->status == 1) {
																								?>
																										<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																									<?php
																									} else {
																									?>
																										<a href="#" class="text-danger" title="Due to Not Approving" data-toggle="modal" data-target="#amanModels<?= $value->id ?>">View Reply</a>

																									<?php
																									}
																								} else {
																									?>
																									<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																								<?php
																								}
																								?>
																							</td>


																							<td class="text-right">
																								<div class="dropdown">
																									<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																										<i class="fas fa-ellipsis-v"></i>
																									</a>
																									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																										<?php
																										if ($this->session->userdata('user_id') == $value->user_id) {
																										?>
																											<!-- <a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>" class="dropdown-item cp text-primary">View</a> -->
																											<?php
																											if ($value->status == 0) {
																											?>
																												<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																												<!-- <a href="<?= base_url('file_sharing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->


																												<?php
																												$where = ['file_sharing_id' => $value->id];

																												$files_id_mil_gayyi = $this->user_model->getRow('file_share_chat', $where);
																												if (!$files_id_mil_gayyi) {
																												?>
																													<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>
																												<?php
																												} else {
																												?>
																													<a class="dropdown-item cp text-danger">Can't Delete</a>

																												<?php
																												}
																												?>
																											<?php
																											} else if ($value->status == 1) {
																											?>
																												<a class="dropdown-item cp text-success">Approved</a>

																											<?php
																											} else {
																											?>
																												<a class="dropdown-item cp text-danger" data-toggle="modal" data-target="#myModalss<?= $value->id ?>">Rejected</a>

																											<?php
																											}
																										} else {
																											?>
																											<a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

																											<!-- <a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>" class="dropdown-item cp text-primary">View</a> -->
																											<?php
																											if ($value->status == 0) {

																											?>
																												<a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#amanModel<?= $value->id ?>">Reject</a>

																												<!-- <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a> -->

																												<a onclick="return Approve('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
																												<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																											<?php
																											} else if ($value->status == 1) {
																											?>
																												<!-- <a data-toggle="modal" data-target="#exampleModal<?= $key ?>" class="dropdown-item cp text-warning">Edit</a> -->
																												<!-- <a onclick="return Edit('<?= base_url('wallet/edit') ?>',<?= $value->id ?>,<?= $value->user_id ?>)" class="dropdown-item cp">Edit</a> -->
																												<!-- <a href="<?= base_url('file_sharing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->
																												<!-- <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a> -->
																												<a onclick="return Decline('<?= base_url('file_sharing/decline') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>


																											<?php
																											} else {
																											?>
																												<a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

																												<a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>

																											<?php
																											}

																											?>
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
																			// }else
																			// {

																			// }

																				?>


																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
									<?php
										}
									}
									?>



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
	// $(document).ready(function() {
	// 	$('#dataTable').DataTable();

	// });

	// $(document).ready(function() {
	// 	$('.dataTable1').DataTable();
	// });

	$(function() {
		$(".dataTable1").DataTable({
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

	function refreshpage() {
		// alert("hello");
		window.location.href = '<?= base_url() ?>file_sharing';
	}
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

	function Approves(url, id, user_id) {


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
				addRow(url, id, user_id);
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
				});
			}
		});
	}

	function Edit(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "warning",
			cancelButtonColor: "warning",
			confirmButtonText: "Yes, Edit it!",
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
				});
			}
		});
	}
</script>
<script>
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



	function removeRows(url, id, user_id) {
		// var amount = document.getElementById('amount' + id).value;
		// console.log(id, "id");
		// console.log(amount, "amount");


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
		// var amount = document.getElementById('amount' + id).value;
		// console.log(id, "id");
		// console.log(amount, "amount");


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
</script>
<script>
	function Reject(url, id, user_id) {
		Swal.fire({

				title: "Are you sure?",
				text: "You won't be able to revert this!",
				icon: "success",
				showCancelButton: true,
				confirmButtonColor: "danger",
				cancelButtonColor: "success",
				confirmButtonText: "Yes, Reject it!",
			}

		).then((result) => {
			if (result.isConfirmed) {
				reject(url, id, user_id);
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
	function OnSubmitHandler(id, user_id) {
		// var amount = ('#amount' + user_id).val();
		// var user_id = ('#user_id' + user_id).val();
		// alert(user_id);
		var user_ids = user_id;
		var msg = document.getElementById('msg' + id).value;
		// alert(user_ids);
		// alert(id);
		// alert(msg);

		if (id != '' && user_ids != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>file_sharing/reject",
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
						});
					}

				},
			});
		} else {
			alert("Something is Missing");
		}
	}

	function OnSubmitHandlers(id, user_id) {
		// var amount = ('#amount' + user_id).val();
		// var user_id = ('#user_id' + user_id).val();
		// alert(user_id);
		var user_ids = user_id;
		var msg = document.getElementById('msgs' + id).value;
		// alert(user_ids);
		// alert(id);
		// alert(msg);

		if (id != '' && user_ids != '') {
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>file_sharing/reject",
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

		href = "<?= base_url() ?>file_sharing/dateFilter?" + form;
		var $a = $("<a>");
		$a.attr("href", href);
		$("body").append($a);
		$a[0].click();
		$a.remove();
	};
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
