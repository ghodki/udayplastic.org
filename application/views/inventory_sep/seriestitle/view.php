<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">

				<div class="col-lg-12">
					<div class="card mb-30">
						<div class="card-header row">
							<div class="col-md-6">
								<h4>Billing Seriestitle</h4>
							</div>
							<div class="col-md-6">
							<a href="<?= base_url('seriestitle') ?>" class="btn btn-primary float-right"><i data-feather="plus" class="icon icon-size"></i>Add</a>
							
							</div>
						</div>
						<?php
						if ($this->session->flashdata('msg')) { ?>
							<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
								<?php echo $this->session->flashdata('msg'); ?>
							</div>
						<?php } ?>

						<table class="table table-striped table-bordered dataTable" id="example">
							<thead>
								<tr>
									<th>Sr.</th>
									<th>Voucher Type</th>
									<th>Active Financial Year</th>
									<th>Active Seriestitle</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($seriestitletitle) {
									foreach ($seriestitletitle as $key => $row) {
										$where = ['seriestitle_type' => $row->id, 'status' => 2];
										$result = $this->user_model->getRow('seriestitle', $where);
										if ($result) {

								?>
											<tr>
												<td><?= $key + 1 ?></td>
												<td><?= $row->seriestitle ?></td>
												<?php
												$res = $this->user_model->getRow('finenncial_year', ['id' => $result->financial_year]);
												$name = $res->year;

												?>
												<td><?= $name ?></td>

												<td><?= $result->start_point; ?></td>
												<td>
													<!-- <a href="<?= base_url('edit-seriestitle/') . $row->id ?>" class="text-info"><i data-feather="edit" class="icon icon-size"></i></a> | -->
												
													<a href="<?= base_url('seriestitle/viewseriestitle/') . $row->id ?>" class="text-info"><i data-feather="eye" class="icon icon-size"></i>View</a>
													<!--<a href="#" onclick="return deleteRow('<?= base_url('delete-seriestitle/') . $row->id ?>')" class="text-danger"><i data-feather="trash-2" class="icon icon-size"></i></a>-->
												</td>
											</tr>
										<?php } else {
										?>
											<tr>
												<td><?= $key + 1 ?></td>
												<td><?= $row->seriestitle ?></td>
												<td>NA</td>


												<td>NA</td>
												<td>
													<!-- <a href="<?= base_url('edit-seriestitle/') . $row->id ?>" class="text-info"><i data-feather="edit" class="icon icon-size"></i></a> | -->
												
													<a href="<?= base_url('seriestitle/viewseriestitle/') . $row->id ?>" class="text-info"><i data-feather="eye" class="icon icon-size"></i>View</a>
													<!--<a href="#" onclick="return deleteRow('<?= base_url('delete-seriestitle/') . $row->id ?>')" class="text-danger"><i data-feather="trash-2" class="icon icon-size"></i></a>-->
												</td>
											</tr>
								<?php }
									}
								}
								?>
							</tbody>
						</table>
						</div>
							</div>
					
						<div class="row">
							<div class="col-md-6">
								<?php if ($totalCount) { ?>
									<p>Showing <?= $start ? $start : 1; ?> to <?= $end ?> of <?= $totalCount ?> entries</p>
								<?php } else { ?>
									<p>No result found.</p>
								<?php } ?>
							</div>
							<div class="col-md-6">
								<div class="fr">
									<?= $this->pagination->create_links(); ?>
								</div>
							</div>
						</div>
						</div>
							</div>
						</div>
