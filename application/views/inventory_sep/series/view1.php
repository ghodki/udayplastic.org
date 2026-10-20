<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">

				<div class="col-lg-12">
					<div class="card mb-30">
						<div class="card-body">
							<div class="card-header row">
								<div class="col-md-6">
									<h4>Billing Series</h4>
								</div>
								<div class="col-md-6">
									<a href="<?= base_url('series') ?>" class="btn btn-primary float-right"><i data-feather="plus" class="icon icon-size"></i>Add</a>
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
										<th>Active Series</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$series = $this->user_model->getAry('series');

									if ($series) {
										foreach ($series as $key => $row) {
											$where = ['id' => $row->series_type, 'status' => 1];
											$result = $this->user_model->getRow('seriestitle', $where);
											if ($result) {

									?>
												<tr>
													<td><?= $key + 1 ?></td>
													<td><?= $result->series ?></td>
													<?php
													$result = $this->user_model->getRow('year', ['id' => $row->financial_year]);
													$name = $result->year;
													?>
													<td><?= $name ?></td>
													<td><?= $row->start_point; ?></td>
													<td>
														<!-- <a href="<?= base_url('edit-series/') . $row->series_id ?>" class="text-info"><i data-feather="edit" class="icon icon-size"></i></a> | -->

														<!-- <a href="<?= base_url('viewseries/') . $row->series_id ?>" class="text-info"><i data-feather="eye" class="icon icon-size"></i></a> -->
														<!-- <a href="#" onclick="return deleteRow('<?= base_url('delete-series/') . $row->id ?>')" class="text-danger"><i data-feather="trash-2" class="icon icon-size"></i></a> -->
														| <a href="#" onclick="return deleteRow('<?= base_url('series/Deleteseries/') . $row->series_id ?>');" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon icon-size">
																<polyline points="3 6 5 6 21 6"></polyline>
																<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
																<line x1="10" y1="11" x2="10" y2="17"></line>
																<line x1="14" y1="11" x2="14" y2="17"></line>
															</svg></a>
													</td>
												</tr>
											<?php } else {
											?>
												<tr>
													<td><?= $key + 1 ?></td>
													<td><?= $row->series ?></td>
													<td>NA</td>
													<td>NA</td>
													<td>
														<!-- <a href="<?= base_url('edit-series/') . $row->id ?>" class="text-info"><i data-feather="edit" class="icon icon-size"></i></a> | -->
														<a href="<?= base_url('viewseries/') . $row->id ?>" class="text-info"><i data-feather="eye" class="icon icon-size"></i></a>
														<!--<a href="#" onclick="return deleteRow('<?= base_url('delete-series/') . $row->id ?>')" class="text-danger"><i data-feather="trash-2" class="icon icon-size"></i></a>-->
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
</div>
<script>
	function deleteRow(url) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, delete it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRow(url);
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
	function removeRow(url) {
		$.ajax({
			type: "get",
			url: url,
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