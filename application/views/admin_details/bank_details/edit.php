<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Bank</h2>
								<!-- Card stats -->
								<div class="row">
									<?php if ($this->session->flashdata('msg')) { ?>
										<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
											<?php echo $this->session->flashdata('msg'); ?>
										</div>
									<?php

									}
									?>
									<form method="POST" action="<?= base_url('bank_details/update'); ?>" class="col-sm-12 col-md-12 col-lg-12">
										<div class="row">

											<input type="hidden" name="id" id="id" value="<?= $bank_details->id; ?>">
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input type="text" class="form-control" value="<?= set_value('name', $bank_details->name) ?>" name="name" placeholder="Account Holder Name" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('name'); ?>

												</span>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="bank_name" type="text" class="form-control" value="<?= set_value('bank_name', $bank_details->bank_name) ?>" name="bank_name" placeholder="Bank Name" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('bank_name'); ?>

												</span>
											</div>

											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="account_number" type="number" min="1" class="form-control" name="account_number" value="<?= set_value('account_number', $bank_details->account_number) ?>" placeholder="Account Number" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('account_number'); ?>

												</span>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="ifsc" type="text" class="form-control" value="<?= set_value('ifsc', $bank_details->ifsc) ?>" name="ifsc" placeholder="IFSC CODE" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('ifsc'); ?>

												</span>
											</div>
											<div class="col-md-4 col-sm-6 col-lg-4">
												<div class="form-group">
													<input id="branch" type="text" class="form-control" value="<?= set_value('branch'), $bank_details->branch ?>" name="branch" placeholder="Branch" required="true">
												</div>
												<span class="col-sm-4 control-label">
													<?php echo form_error('branch'); ?>

												</span>
											</div>


										</div>
										<div class="row mt-5">
											<div class="col-md-12 text-center">
												<button type="submit" class="btn btn-default">
													Update
												</button>

											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
