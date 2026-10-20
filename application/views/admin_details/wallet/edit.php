<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<?php

			$roles = $this->session->userdata('roles');
			if ($roles != 1) {
			?>

				<div class="header bg-blue pb-8 pt-5 pt-md-8">
					<div class="container-fluid">
						<div class="header-body ">
							<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Amount</h2>
							<!-- Card stats -->
							<div class="row">
								<?php if ($this->session->flashdata('msg')) { ?>
									<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
										<?php echo $this->session->flashdata('msg'); ?>
									</div>
								<?php

								}
								?>


								<?php
								?>
								<form method="POST" action="<?= base_url('wallet/updatewallet'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">

									<?php

									?>
									<div class="row">
										<input type="hidden" name="id" value="<?= $result->id; ?>">


										<div class="col-md-4 col-sm-12 col-lg-4">
											<div class="form-group">
												<label for="vouchernumber" class="text-dark">Transaction No:</label>
												<input type="text" class="form-control" required id="vouchernumber" value="<?= $result->vouchernumber; ?>" readonly name="vouchernumber">

											</div>
										</div>
										<div class="col-md-4 col-sm-12 col-lg-4">
											<div class="form-group">
												<label for="voucherdate" class="text-dark">Transaction Date:</label>
												<input type="text" class="form-control" required id="voucherdate" value="<?= $result->voucherdate; ?>" readonly name="voucherdate">

											</div>
										</div>


										<?php
										$subadmin_id = get_title('subcat', ['userid' => $this->session->userdata('user_id')], 'referral_id');
										// referral_id =subamdin
										if ($subadmin_id) {

										?>
											<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<label for="bank_account" class="text-dark">Account No:</label>

													<select name="bank_account" tabindex="4" id="bank_account" required class="form-control">
														<option value="">--Select--</option>
														<?php
														$bank_details = $this->user_model->getAll('bank_details', ['user_id' => $subadmin_id]);
														if ($bank_details) {
															foreach ($bank_details as $key => $value) {
														?>
																<option value="<?= $value->id ?>" <?= $value->id == $result->bank_account ? 'selected' : ''; ?>><?= $value->name; ?></option>

																<!-- <option value="<?= $value->id ?>"><?= $value->name; ?>(<?= $value->account_number ?>)</option> -->

																<?php	}
														} else {

															// $bank_details = $this->user_model->getResult('bank_details');
															$bank_details = $this->user_model->getAll('bank_details', ['user_id' => 1]);

															if ($bank_details) {
																foreach ($bank_details as $key => $value) {
																?>
																	<option value="<?= $value->id ?>" <?= $value->id == $result->bank_account ? 'selected' : ''; ?>><?= $value->name; ?></option>

																	<!-- <option value="<?= $value->id ?>"><?= $value->name; ?>(<?= $value->account_number ?>)</option> -->

														<?php	}
															}
														}
														?>
													</select>
												</div>
											</div>
										<?php
										} else {
										?>
											<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<label for="bank_account" class="text-dark">Account No:</label>

													<select name="bank_account" tabindex="4" id="bank_account" required class="form-control">
														<option value="">--Select--</option>
														<?php
														// $bank_details = $this->user_model->getResult('bank_details');
														$bank_details = $this->user_model->getAll('bank_details', ['user_id' => 1]);

														if ($bank_details) {
															foreach ($bank_details as $key => $value) {
														?>
																<option value="<?= $value->id ?>" <?= $value->id == $result->bank_account ? 'selected' : ''; ?>><?= $value->name; ?></option>

																<!-- <option value="<?= $value->id ?>"><?= $value->name; ?>(<?= $value->account_number ?>)</option> -->

														<?php	}
														}
														?>
													</select>
												</div>
											</div>
										<?php
										}


										?>





										<div class="col-md-4 col-sm-12 col-lg-4">
											<div class="form-group">
												<label for="paymenttype" class="text-dark">Payment Type:</label>

												<select class="form-control" required id="paymenttype" name="paymenttype">
													<option value="">--Select--</option>
													<option value="Google Pay" <?= $result->paymenttype == 'Google Pay' ? 'selected' : '' ?>>Google Pay</option>
													<option value="Phone Pay" <?= $result->paymenttype == 'Phone Pay' ? 'selected' : '' ?>>Phone Pay</option>
													<option value="Paytm" <?= $result->paymenttype == 'Paytm' ? 'selected' : '' ?>>Paytm</option>
												</select>
											</div>
										</div>

										<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<label for="amount" class="text-dark">Credit:</label>

												<input id="amount" type="number" min="0" class="form-control" name="amount" value="<?= $result->amount ?>" min="1" placeholder="Amount">
											</div>
										</div>
										<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<label for="file" class="text-dark">File</label>

												<input type="file" class="form-control" required name="image" size="20" />
												<span><?= $result->image ?></span>
											</div>
										</div>
										<div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
											<div class="form-group">
												<label for="note" class="text-dark">Note:</label>
												<textarea id="name" placeholder="Note" type="text" class="form-control" name="name"><?= $result->note ?></textarea>
											</div>
										</div>
									</div>
									<div class="row mt-5" style="margin-bottom: 32px;">
										<div class="col-md-12 text-center">
											<button type="submit" class="btn btn-default">
												Update
											</button>
											<input type="reset" class="btn btn-secondary" placeholder="Reset">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>

		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#example_contra').DataTable();
	});
</script>
<script>
	function Approve(url, id, user_id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "success",
			confirmButtonText: "Yes, Approve it!",
		}).then((result) => {
			if (result.isConfirmed) {
				removeRow(url, id, user_id);
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
	function removeRow(url, id, user_id) {
		var amount = document.getElementById('amount' + id).value;
		console.log(id, "id");
		console.log(amount, "amount");


		$.ajax({
			type: "POST",
			url: url,
			data: {
				amount: amount,
				id: id,
				user_id: user_id,

			},
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