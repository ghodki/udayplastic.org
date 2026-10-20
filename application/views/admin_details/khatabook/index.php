<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-1">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Create Company</h2>
								<?php if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
										<strong><?php echo $this->session->flashdata('msg'); ?></strong>
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
								<?php
								}
								?>
								<div class="row">
									<form  class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>khatabook" method="POST" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-6 col-sm-12 col-lg-6">
												<div class="form-group">
													<label for="company_name">Company Name<span>*</span></label>
													<input id="name" name="name" placeholder="Company Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
													<span class="col-sm-4 control-label text-danger">
														<?php echo form_error('name'); ?>
													</span>
												</div>
											</div>
											<input type="hidden" name="company_id" value="">

											<div class="col-md-6  col-sm-12 col-lg-6">
												<div class="form-group">
													<label for="number">Mobile Number<span>*</span></label>
													<input id="number" name="number" placeholder="Mobile Number" type="text" class="form-control" required="true" value="<?= set_value('number') ?>">
													<span class="col-sm-4 control-label text-danger">
														<?php echo form_error('number'); ?>
													</span>
												</div>
											</div>
											<div class="col-md-12 col-sm-12 col-lg-12">
												<div class="form-group">
													<label for="address">Address<span></span></label>
													<textarea name="address" type="text" class="form-control" required="true" value=""><?= set_value('address') ?></textarea>
												</div>
											</div>
										</div>
										<div class="row mt-1">
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
						<div class="container-fluid mb-9" style="margin-top:20px;">
							<div class="col">
								<div class="card shadow">
									<div class="card-header border-0">
										<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Khatabook List</label>
										<div class="row mt-5">
										</div>
										<div class="table-responsive">
											<table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
												<thead class="thead-dark">
													<tr>
														<th>#</th>
														<th>Date</th>
														<th>Company Name</th>
														<!-- <th>TotalClient</th> -->
														<th>Address</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody class="text-dark">
													<?php

													if (isset($khatabook) && $khatabook != '') {
														foreach ($khatabook as  $key => $value) {
															$date = $value->created_at;
													?>
															<tr>
																<td><span><?= $key + 1 ?></span></td>
																<td><?= $date; ?></td>
																<td>
																	<a href="<?= base_url('khatabook/viewclient/') . $value->id ?>" class="active"><?= $value->company_name ?>(<?= $value->number ?>)</a>
																</td>
																<?php
																// $where = ['company_id' => $value->id];
																// $res = $this->user_model->getAry('client', '', '', '', $where);
																// $total = count($res);
																?>
																<!-- <td><?= $total ?></td> -->
																<td><?= $value->address ?></td>
																<td>
																	<a href="<?= base_url('khatabook/edit/') . $value->id ?>"><i class="fa fa-edit" style="font-size:24px"></i></a>
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