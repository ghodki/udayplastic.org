<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">

			<div class="container-fluid mb-9">

				<div class="col">
					<div class="card shadow">
						<div class="card-header border-0" style="margin-top:70px;">
							<label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Transaction History of <?= get_title('users', ['id' => $wallet[0]->user_id], 'name'); ?> </label>
							<br>
                                                        <span style="margin-left:auto;" class="d-block">Wallet Amount:<?= get_title('users', ['id' => $wallet[0]->user_id], 'amount'); ?></span>
							<div class="row mt-5">
							</div>
							<div class="table-responsive">
								<table class="table table-striped align-items-center table-flush dataTable" style="width: 100%" id="example_contra">
									<thead class="thead-dark">
										<tr>
											<th>#</th>

											<th>Service Name</th>
											<th>Transaction Date</th>
											<th>Transaction Id</th>
											<th>CR</th>
											<th>DBT</th>
											<th>Balance</th>
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
										// if ($this->session->userdata('roles') != 2) {
										if (isset($wallet) && !empty($wallet)) {


											foreach ($wallet as  $key => $value) {
												$date = date("d-m-Y",strtotime($value->tranDate));
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

														<!-- <td class="text-danger"><?= "Debited" ?></td> -->

													<?php
													} else if ($value->add_amount == 4) {

													?>
														<td class="text-success" data-toggle="modal" data-target="#walletMsg<?= $value->id ?>" title="Know About Debited">Wallet Credited</td>

														<!-- <td class="text-danger"><?= "Debited" ?></td> -->

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
													} else {
													?>
														<td></td>
													<?php
													}
													?>


													<td><?=  date('d-m-Y', strtotime($value->voucherdate)) ?></td>
													<!-- <td></td> -->
													<td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
													</td>

													<td class="text-success">
														<?php
														if ($find_status == 'CR') {
															if ($value->file_sharing_id) {
																// echo $value->amount;
																$amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
																echo $amount;
																// $total_amount_cr = +$amount;
																$total_amount_cr = $total_amount_cr + $amount;
															} else {
																$amount = $value->amount;
																echo $value->amount;
																$total_amount_cr = $total_amount_cr + $amount;
															}
														} else {
															echo 0;
														}

														?>


													</td>

													<td class="text-danger">
														<?php
														if ($find_status == 'DBT') {
															if ($value->file_sharing_id) {
																// echo $value->amount;
																$amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
																echo $amount;
																$total_amount_dbt = $total_amount_dbt + $amount;
															} else {
																$amount = $value->amount;
																echo $value->amount;
																$total_amount_dbt = $total_amount_dbt + $amount;
															}
														} else {
															echo 0;
														}

														?>


													</td>
													<td>
														<?=
														$total_amount_cr - $total_amount_dbt;
														?>
													</td>




													<!-- <td>
													
														// $name=get_title('bank_details', ['id' => $value->bank_account], 'name');
														// get_title('bank_details', ['id' => $value->bank_account], 'account_number');

													

													</td> -->


													<?php
													$where = ['id' => $value->user_id];
													// $name= get_title('users',$where);
													?>
													<!-- <td> -->
													<?php
													if ($value->status == 1 || $value->user_id == $this->session->userdata('user_id')) {

														// echo  $value->amount;
													} else {

													?>
														<input type="hidden" style="width:90px;" class="form-control" min="0" name="amount" id="amount<?= $value->id ?>" value="<?= $value->amount ?>">
													<?php
													} ?>
													<!-- </td> -->
													<?php
													if ($value->file_sharing_id) {
													?>
														<td><a href="<?= base_url('uploads/file_share/') . get_title('file_sharing', ['id' => $value->file_sharing_id], 'file_name'); ?>" download>Download</a></td>

													<?php
													} else {
													?>
														<td><a href="<?= base_url('uploads/amount/') . $value->image; ?>" download>Download</a></td>

													<?php
													}
													?>


													<td><?php

														if ($value->status == 0) {
															echo '<p class="text-danger">Pending</p>';
														} else {
															echo '<p class="text-success">Approved</p>';
														}
														?></td>

													<!-- <td>
													<?php
													// echo get_title('users', ['id' => $value->assign_user], 'name')
													?>
													</td> -->
													<td class="text-right">
														<div class="dropdown">
															<a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<i class="fas fa-ellipsis-v"></i>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																<?php
																if ($this->session->userdata('roles') != 3) {
																?>
																	<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>

																	<!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
																	<?php
																	if ($value->status == 0) {
																	?>
																		<a href="<?= base_url('wallet/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a>
																		<?php
																	} else {


																		if (!$value->file_sharing_id) {
																		?>
																			<!-- <a href="<?= base_url('wallet/file_sharing/decline/') . $value->file_sharing_id ?>" class="dropdown-item cp text-danger">Decline</a> -->


																			<a href="<?= base_url('wallet/declined/') . $value->id ?>" class="dropdown-item cp text-danger">Decline</a>

																		<?php
																		} ?>

																	<?php
																	}
																	?>
																	<?php
																	if ($value->status == 0 && $value->user_id != $this->session->userdata('user_id')) {
																	?>
																		<!-- <a onclick="return Approve('<?= base_url('wallet/approve') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a> -->

																	<?php
																	}
																	?>
																<?php
																} else {
																?>
																	<a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>
																	<?php
																	if ($value->status == 0) {
																	?>

																		<a href="<?= base_url('wallet/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a>

																		<a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a>
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

										<?php }
										}
										// }else
										// {

										// }

										?>

									</tbody>
									<tfoot>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										

										<td>Total:</td>
										<td class="text-success">
											<?php
											echo $total_amount_cr;

											?>
										</td>

										<td class="text-danger" style="font-size:30px;"><?php

																				echo $total_amount_dbt;
																				?></td>
										<td>Balance:</td>
										<!-- <td></td> -->
										<td class="text-success" style="font-size:20px;"><?= $total_amount_cr - $total_amount_dbt ?></td>
										<td></td>
										<td></td>
									</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		$(".dataTable").DataTable({
			dom: 'Bfrtip',
			pageLength: 20,
			lengthMenu: [
				[20, 50, 100, -1],
				[20, 50, 100, "All"]
			],
			buttons: [{
					extend: 'copyHtml5',
					footer: true,
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'excelHtml5',
					footer: true,
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'csvHtml5',
					footer: true,
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'pdfHtml5',
					footer: true,
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'print',
					footer: true,
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'pageLength',
					footer: true,
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'colvis',
					footer: true,
					exportOptions: {
						columns: ':visible'
					}
				}


			],


		});
	});
</script>