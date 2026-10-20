<div class="content-wrapper" id="all">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">
									<?php
									if (isset($client) && $client != '') {
										$com = $client->company_id;

										$where = ['id' => $com];
										$namc = $this->user_model->getRow('khatabook', $where);
										$name = $namc->company_name;
									}

									?>
									<?php echo $name; ?>Client List</h2>
								<!-- Card stats -->
								<div class="row" id="changeData">
									<div class="col-md-6 col-sm-12">
										<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>client/updateprofile" method="POST" enctype="multipart/form-data">




											<div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<select class="form-control" onchange="gochange()" name="select_user" id="select_user">
														<option selected value="<?= $client->id ?>"><?= $client->name ?></option>
														<!-- <option value="www.google.com">A</option> -->

														<?php
														$com = $client->company_id;

														$where = ['company_id' => $com];
														$users = $this->user_model->getAry('client', '', '', '', $where);

														if ($users) {

															foreach ($users as $user) {


														?>
																<option value="<?= $user->id; ?>"><?= $user->name ?></option>

														<?php
															}
														}

														?>
													</select>
												</div>
											</div>

											<div class="col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<?php
													// $date = date("d/m/Y");
													?>
													<input id="date" name="date" type="date" class="form-control" required="true" value="">
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<input id="amount" name="amount" min="0" placeholder="Add Amount" type="number" class="form-control" required="true" value="<?= $client->amount ?>">
												</div>
											</div>

											<div class="col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<select class="form-control" required>
														<option value=""></option>

														<option value="credited">credited</option>
														<option value="debited">debited</option>

													</select>
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<input id="rmark" name="remark" placeholder="Add remark" type="text" class="form-control" required="true">
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<input id="file" name="file" placeholder="Add File" type="file" class="form-control">
												</div>
											</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<table style="width:100%;">
											<tr>
												<th>Client Name</th>
												<td><?= $client->name; ?></td>
											</tr>

											<tr>
												<th>Number</th>
												<td><?= $client->number ?></td>
											</tr>
											<tr>
												<th>Email</th>
												<td><?= $client->email ?></td>
											</tr>
										</table>
									</div>
								</div>

							</div>


							<div class="row mt-5">
								<div class="col-md-12 text-center">
									<button type="submit" class="btn btn-default">


										Update

									</button>
									<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
								</div>
							</div>
							</form>
						</div>
					</div>
					<div class="container-fluid mb-9" style="margin-top:100px;">

						<div class="col">
							<div class="card shadow">
								<div class="card-header border-0">
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Ledger List</label>
									<div class="row mt-5">
										<div class="col-md-2">
											<input type="date" class="form-control">

										</div>
										<div class="col-md-2">
											<input type="date" class="form-control">

										</div>

										<div class="col-md-2">
											<button class="btn btn-info" type="btn" name="btn" class="form-control">Search</button>

										</div>

										<div class="col-md-2">
											<button class="btn btn-primary" type="btn" name="btn" class="form-control">Download</button>

										</div>
									</div>
									<br>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="20%">Create time</th>

													<th scope="col" width="40%">Remark</th>
													<th scope="col" width="20%">Credit Or Debited</th>
													<th scope="col" width="20%">Files</th>


													<th scope="col">Action</th>
												</tr>
											</thead>
											<tbody>


												<?php

												if (isset($client) && $client != '') {
													foreach ($client as  $key => $cat) {
														// $date = $cat->create_at;
														// $newDate = date("d-m-Y", strtotime($date));
												?>
														<tr>
															<td>
																<!-- <span><?= $key + 1; ?></span> -->
															</td>
															<!-- <td><?= $newDate; ?></td> -->

															<td><?= $cat->name ?></td>
															<!-- <td>
																<button class="btn btn-sm btn-secondary" onclick="downloadFile('<?= base_url() . 'uploads/application/' . $cat->image; ?>','<?= $cat->image; ?>')"><?= $cat->image; ?></button>
															<td>
															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<a href="<?= base_url('application/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>
																		<a href="<?= base_url('application/delete/') . $cat->id ?>" onclick="return confirm('Are you sure you want to delete the user?');" class="dropdown-item cp">Delete</a>


																	</div>
																</div>
															</td> -->
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
<!-- <script type="script"></script> -->
<script>
	function gochange() {
		var id = document.getElementById('select_user').value;
		if (parseInt(id) > 0) {
			console.log(id);
			window.location.href = '<?= base_url('client/changeUser/'); ?>' + id;
		}
		// window.location.reload();


	}
</script>



</script>