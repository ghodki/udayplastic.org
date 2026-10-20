<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid" style="margin-top:85px;">

			<div class="container-fluid mb-9">

				<div class="col">
					<div class="card shadow">
						<div class="card-header border-0" style="margin-top:70px;">
							<!-- <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Transaction Details</label> -->

							<div class="row" id="datathide">
								<div class="col-md-4">
									<h1>Transaction Details</h1>
								</div>
								<div class="col-md-6">
								</div>
								<div class="col-md-2">
									<!-- <h1>Back</h1> -->
									<a class="nev-link" href="<?= base_url() ?>service_product" title="Sale List">
										<!-- <i style="margin-top:12px;" class="fas fa-list"></i> -->
										Back
									</a>
								</div>
							</div>
							<div class="table-responsive mt-5">
								<table class="table  align-items-center table-flush" style="width: 100%" id="example_contra">
									
									<tbody class="text-dark">
										<?php
										// print_r($sms_history);
										// die;
										// if ($this->session->userdata('roles') != 2) {
										if (isset($result) && $result != '') {
											// foreach ($wallet as  $key => $value) {
											$date = $result->tranDate;
											$newDate = $date;
										?>
											<tr>
												<!-- <th>#</th>
													<td>
														<span><?= $key + 1; ?></span>
													</td> -->
												<td>Name:</td>
												<th><?=
													get_title('users', ['id' => $result->user_id], 'name')
													?>
												</th>
												<?php
												$where = ['id' => $result->user_id];
												// $name= get_title('users',$where);
												?>
												<td>Total Amount</td>
												<th><?= $result->amount; ?></th>
											</tr>
											<tr>
												<td>Qty:</td>
												<th><?= $product_service_apply->qty; ?></th>
												<td>Charge:</td>
												<th><?= $product_service_apply->charged; ?></th>
											</tr>
											<tr>
												<td>Transaction Date</td>
												<th><?= $result->voucherdate; ?></th>
												<td>Transaction Number</td>
												<th><?= $result->vouchernumber; ?></th>
											</tr>

											<tr>
												<td>
													Service Name:
												</td>
												<th>
												<?php
													echo get_title('product_service', ['id' => $product_service_apply->pro_id], 'name')

												?>

												<td>Status</td>
												<?php
												if ($result->status == 0) {
													$status = "Pending";
												?>
													<th class="text-danger"><?= $status; ?></th>

												<?php
												} else {
													$status = "Approved";

												?>
													<th class="text-success"><?= $status; ?></th>

												<?php
												} ?>
												
											</tr>
											




											<tr>
												<td>Assign By</td>

												<td><?=
													get_title('users', ['id' => $result->assign_user], 'name')
													?>
												</td>
												<td>Note</td>
												<th><?= $product_service_apply->remark; ?></th>



											</tr>

											<!-- <td><?= $newDate; ?></td> -->


											</tr>

										<?php }
										// }
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

<script>
	// $(document).ready(function() {
	// 	$('#example_contra').DataTable();
	// });
</script>
