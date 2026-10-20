<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid" style="margin-top:85px;">

			<div class="container-fluid mb-9">

				<div class="col">
					<div class="card shadow">
						<div class="card-header border-0" style="margin-top:70px;">
							<!-- <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Transaction Details</label> -->

							<div class="row">
								<div class="col-md-4">
									<h1>Transaction Details</h1>
								</div>
								<div class="col-md-6">
								</div>
								<div class="col-md-2">
									<!-- <h1>Back</h1> -->
									<a class="nev-link" href="<?= base_url() ?>wallet" title="Sale List">
										<!-- <i style="margin-top:12px;" class="fas fa-list"></i> -->
										Back
									</a>
								</div>
							</div>
							<div class="table-responsive mt-5">
								<table class="table  align-items-center table-flush" style="width: 100%" id="example_contra">
									<!-- <thead class="thead-dark">
										<tr>
											<th scope="col" width="2%">#</th>
											<th scope="col" width="20%">Name</th>
											<th scope="col" width="30%">Amount</th>
											<th scope="col" width="28%">Transaction Date</th>
											<th scope="col" width="18%">By Added</th>
										</tr>
									</thead> -->
									<tbody class="text-dark">
										<div id="rejectmsgs" class="modal fade" role="dialog">
											<div class="modal-dialog">

												<!-- Modal content-->
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title text-center">Reject Message</h4>

														<button type="button" class="close" data-dismiss="modal">&times;</button>
													</div>
													<div class="modal-body">
														<p class="text-danger"><?= $result->msg ?></p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>

											</div>
										</div>
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
												<?php
												if ($result->user_id) {
												?>
													<th><?=
														get_title('users', ['id' => $result->user_id], 'name')
														?>
													</th>
												<?php
												} else {
												?>
													<th><?=
														$result->fullname;
														?>
													</th>
												<?php

												}


												$where = ['id' => $result->user_id];
												// $name= get_title('users',$where);
												?>
												<td>Amount</td>
												<th><?= $result->amount; ?></th>
											</tr>

											<tr>
												<td>Transaction Date</td>
												<th><?= $result->voucherdate; ?></th>
												<td>Transaction Number</td>
												<th><?= $result->vouchernumber; ?></th>
											</tr>

											<tr>
												<td>Status</td>
												<?php
												if ($result->status == 0) {
													$status = "Pending";
												?>
													<th class="text-danger"><?= $status; ?></th>

												<?php
												} else if ($result->status == 1) {
													$status = "Approved";
												?>
													<th class="text-success"><?= $status; ?></th>

												<?php
												} else {
													$status = "Rejected";
												?>
													<th class="text-danger" data-toggle="modal" data-target="#rejectmsgs"><?= $status; ?></th>

												<?php
												}
												?>


												<td>File:</td>

												<?php
												if ($result->file_sharing_id) {
												?>
													<th><a href="<?= base_url('uploads/file_share/') . get_title('file_sharing', ['id' => $result->file_sharing_id], 'file_name'); ?>" download>Download</a></th>

												<?php
												} else if ($result->image) {
												?>
													<th><a href="<?= base_url('uploads/amount/') . $result->image; ?>" download>Download</a></th>

												<?php
												} else {
												?>
													<th>No File</th>

												<?php
												}
												?>
											</tr>
											<tr>
												<td>Admin Bank Account</td>
												<!-- <th><?= $result->tranDate; ?></th> -->
												<th><?=
													get_title('bank_details', ['id' => $result->bank_account], 'account_number')
													?>
												</th>
												<td>Payment Type</td>
												<th><?= $result->paymenttype; ?></th>


											</tr>




											<tr>
												<td>Assign By</td>

												<?php
												if ($result->status == 1) {
												?>
													<td> <?php echo get_title('users', ['id' => $result->assign_user], 'name'); ?></td>

												<?php

												} else {
												?>
													<td></td>

												<?php
												}
												?>

												<td>Note</td>
												<th><?= $result->note; ?></th>



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
	$(document).ready(function() {
		$('#example_contra').DataTable();
	});
</script>
