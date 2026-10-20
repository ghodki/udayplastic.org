<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Bank</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" action="<?= base_url('bank_details/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">


											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" value="<?= set_value('name') ?>" name="name" placeholder="Account Holder Name" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('name'); ?>

												</span>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="bank_name" type="text" class="form-control" value="<?= set_value('bank_name') ?>" name="bank_name" placeholder="Bank Name" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('bank_name'); ?>

												</span>
											</div>

											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="account_number" type="number" min="1" class="form-control" name="account_number" value="<?= set_value('account_number') ?>" placeholder="Account Number" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('account_number'); ?>

												</span>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="ifsc" type="text" class="form-control" value="<?= set_value('ifsc') ?>" name="ifsc" placeholder="IFSC CODE" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('ifsc'); ?>

												</span>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="branch" type="text" class="form-control" value="<?= set_value('branch') ?>" name="branch" placeholder="Branch" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('branch'); ?>

												</span>
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
									<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Bank Details</label>
									<div class="row mt-5">
									</div>
									<div class="table-responsive">
										<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
											<thead class="thead-light">
												<tr>
													<th scope="col" width="2%">#</th>
													<th scope="col" width="20%">Holder Name</th>
													<th scope="col" width="20%">Bank Name</th>
													<th scope="col" width="20%">Account Number</th>
													<th scope="col" width="20%">IFSC Code</th>
													<th scope="col" width="20%">Branch</th>


													<th scope="col">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if (isset($bank_details) && $bank_details != '') {
													foreach ($bank_details as  $key => $date) {
												?>
														<tr>
															<td>
																<span><?= $key + 1; ?></span>
															</td>
															<td><?= $date->name ?></td>
															<td><?= $date->bank_name ?></td>
															<td><?= $date->account_number ?></td>

															<td><?= $date->ifsc ?></td>
															<td><?= $date->branch ?></td>



															<td class="text-right">
																<div class="dropdown">
																	<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		<i class="fas fa-ellipsis-v"></i>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																		<a href="<?= base_url('bank_details/edit/') . $date->id ?>" class="dropdown-item cp">Edit</a>
																		<a href="<?= base_url('bank_details/delete/') . $date->id ?>" class="dropdown-item cp">Delete</a>


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
