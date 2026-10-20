<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Create Client</h2>
								<!-- Card stats -->
								<div class="row">

									<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>khatabook/clientAdd" method="POST" enctype="multipart/form-data">


										<div class="row">
											<input type="hidden" id="com" name="company_id" value="<?= $comp; ?>">
											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<input id="name" name="name" placeholder="Client Name" type="text" class="form-control" required="true" value="">
												</div>
											</div>

											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<input id="number" name="number" placeholder="Mobile Number" type="text" class="form-control" required="true" value="">
												</div>
											</div>
											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="">
												</div>
											</div>
											<div class="col-md-4 col-md-4 col-sm-4 col-lg-4">
												<div class="form-group">
													<textarea name="address" type="text" class="form-control" required="true" value=""></textarea>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													Send SMS<input type="checkbox" name="send">
												</div>
											</div>

										</div>


										<div class="row mt-5">
											<div class="col-md-12 text-center">
												<button type="submit" class="btn btn-default">


													Save

												</button>
												<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="container-fluid mb-9" style="margin-top:100px;">

							<div class="col">
								<div class="card shadow">
									<div class="card-header border-0">
										<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">
											<?php
											$where = ['id' => $comp];
											$res = $this->user_model->getRow('khatabook', $where);
											$name = $res->company_name;
											?>
											<p class="text-center text-dark"> <?php echo $name ?>Client List</p>
										</label>
										<div class="row">
											<div class="col">
												<input class="form-control" placeholder="Search Name" onkeyup="search_func(this.value);" name="filter" id="filter" type="text">
											</div>

											<div class="col">
												<!-- <input class="btn btn-info" name="print" id="print" type="button"> -->
												<button class="btn btn-primary" onclick="print();">Reports</button>
											</div>
											<div class="col">
												<!-- <input class="btn btn-info" name="print" id="print" type="button"> -->
												<button class="btn btn-primary" onclick="print();">Ledgers Reports</button>
											</div>
											<div class="col">
												<!-- <input class="btn btn-info" name="print" id="print" type="button"> -->
												<button class="btn btn-info" onclick="print();">Print</button>
											</div>
										</div>
										<div class="row mt-5">
										</div>
										<div class="table-responsive">
											<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
												<thead class="thead-dark">
													<tr>
														<th scope="col" width="2%">#</th>
														<th scope="col" width="18%">Date</th>
														<th scope="col" width="20%">Client Name</th>

														<th scope="col" width="30%">Number</th>
														<th scope="col" width="30%">Address</th>


														<th scope="col">Action</th>
													</tr>
												</thead>
												<tbody class="text-dark" id="clientdata">
													<?php
													if (isset($client) && $client != '') {
														foreach ($client as  $key => $value) {
															$date = $value->created_at;
															$newDate = date("d-m-Y", strtotime($date));
													?>
															<tr>
																<td>
																	<span><?= $key + 1; ?></span>
																</td>
																<td><?= $newDate; ?></td>

																<td>
																	<?= $value->name ?>
																</td>

																<td><?= $value->number ?></td>
																<td><?= $value->address ?></td>



																<td class="text-right">
																	<div class="dropdown">
																		<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			<i class="fas fa-ellipsis-v"></i>
																		</a>
																		<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																			<a href="<?= base_url('client/viewprofile/') . $value->id; ?>" class="dropdown-item cp">View Profile</a>

																			<a href="<?= base_url('client/edit/') . $value->id; ?>" class="dropdown-item cp">Edit</a>
																			<a href="<?= base_url('client/delete/') . $value->id ?>" class="dropdown-item cp">Delete</a>


																		</div>
																	</div>
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

<script>
	function search_func(value) {
		// var year_id = $(this).val();
		var user_id = document.getElementById('filter').value;
		var comp = document.getElementById('com').value;
		console.log(user_id);
		console.log(comp);

		// var e = document.getElementById("year");
		// var text = e.options[e.selectedIndex].text;
		// document.getElementById("yy").innerHTML = 'Year' + text;
		document.getElementById('clientdata').innerHTML = '';

		if (parseInt(comp) > 0) {
			$.ajax({
				type: "POST",
				url: '<?= base_url('client/getData'); ?>',
				data: {
					// year_id: year_id,
					user_id: user_id,
					comp_id: comp,

				},
				success: function(data) {
					console.log(data);
					// data1 = JSON.parse(data);

					$("#clientdata").append(data);


				}
			})
		}
	}
</script>