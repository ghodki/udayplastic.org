<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header pb-8 pt-5 pt-md-8" style="background: #2C4668;">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Logo</h2>
								<!-- Card stats -->
								<?php if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
										<?php echo $this->session->flashdata('msg'); ?>
									</div>
								<?php

								}
								?>
								<div class="row">
									<div class="col-md-3 col-lg-3 col-xl-3"></div>


									<div class="col-md-6 col-sm-6 col-lg-6">
										<?php echo form_open_multipart('logo/upload'); ?>
										<?php echo $error; ?>
										<div class="form-group">
											<input type="file" accept="image/*" class="form-control" required name="image" />
										</div>
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
			<div class="container-fluid mb-9" style="margin-top:20px;">
				<div class="col">
					<div class="card shadow">
						<div class="card-header border-0">
							<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Logo List</label>
							<div class="row mt-5">
							</div>
							<div class="table-responsive">
								<table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
									<thead class="thead-light">
										<tr>
											<th scope="col" width="10%">#</th>
											<th scope="col" width="80%">Logo</th>
											<th scope="col">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$where = ['user_id' => $this->session->userdata('user_id')];
										$logos = $this->user_model->getResult('logo', $where);
										if (isset($logos) && $logos != '') {
											foreach ($logos as  $key => $date) {
										?>
												<tr>
													<td><span><?= $key + 1; ?></span></td>
													<td><img src="<?= base_url() ?>uploads/logo/<?= $date->logo; ?>"></td>
													<td class="text-right">
														<div class="dropdown">
															<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<i class="fas fa-ellipsis-v"></i>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																<!-- <a href="<?= base_url('logo/edit/') . $date->id ?>" class="dropdown-item cp">Edit</a> -->
																<a href="<?= base_url('logo/delete/') . $date->id ?>" class="dropdown-item cp">Delete</a>
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