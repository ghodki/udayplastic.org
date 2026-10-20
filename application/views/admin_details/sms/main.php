<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<div class="header-body ">
								<div class="row">
									<div class="col-md-4">
										<div class="card card-stats mb-4 mb-xl-0">
											<div class="card-body">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Total SMS
														</h5>
														<?php
														if (isset($sms) && $sms != '') {
														?>
															<span class="text-dark"><?= $sms->total_sms; ?></span>

														<?php
														} else { ?>
															<span class="text-dark">0</span>


														<?php	}
														?>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
															<i class="fas fa-envelope"></i>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card card-stats mb-4 mb-xl-0">
											<div class="card-body">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Send SMS</h5>
														<?php
														if (isset($sms) && $sms != '') {
														?>
															<span class="text-dark"><?= $sms->send_sms; ?></span>

														<?php
														} else { ?>
															<span class="text-dark">0</span>


														<?php	}
														?>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-info text-white rounded-circle shadow">
															<i class="fas fa-envelope-open-text"></i>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card card-stats mb-4 mb-xl-0">
											<div class="card-body">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Remaining SMS</h5>
														<?php
														if (isset($sms) && $sms != '') {
														?>
															<span class="text-dark"><?= $sms->remains_sms; ?></span>

														<?php
														} else { ?>
															<span class="text-dark">0</span>


														<?php	}
														?>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-green text-white rounded-circle shadow">
															<i class="fas fa-envelope-open "></i>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<h2 class="text-white my-2 mt-4">Send Bulk SMS</h2>
								<!-- Card stats -->
								<div class="row">

									<form method="POST" onsubmit="return validation()" id="signUpForm" action="/2bb/user/send_sms" class="col-sm-12 col-md-12 col-lg-12" modelattribute="">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group col-md-6 m-auto">
													<label class="form-control-label text-white">Enter Sender Code</label>
													<input type="text" minlength="6" maxlength="6" class="form-control" name="senderCode" id="senderCode" value="" placeholder="Enter sender code " onkeypress="return (event.charCode > 64 &amp;&amp; event.charCode < 91) || (event.charCode > 96 &amp;&amp; event.charCode < 123)" required="required">
												</div>
												<div class="form-group col-md-6 m-auto pt-4">
													<label class="form-control-label text-white">Write SMS</label>
													<textarea class="form-control" name="sms" id="sms" cols="8" onchange="onSMSChange(event)" rows="5" value="" placeholder="Write sms here " required="required"></textarea>
													<small id="consumedSMSLabel" class="text-white float-right h4"></small>
												</div>
												<div class="form-group col-md-6 m-auto pt-4">
													<label class="form-control-label text-white">Enter Mobile numbers</label>
													<textarea class="form-control" name="mobile" id="mobile" required="required" rows="5" value="" placeholder="Enter mobile numbers by comma separated "></textarea>
												</div>
												<div class="form-group col-md-6 m-auto" id="divTotalMobiles">
													<div class="col-md-12 row pt-2">
														<p class="h3 text-yellow col-md-9"></p>
														<span class="text-primary col-md-3 text-right text-white cp" onclick="onClearNumber()">Clear All</span>
													</div>
												</div>
												<div class="form-group col-md-6 m-auto pt-4 text-center">
													<p class="h1 text-white ">OR</p>
												</div>
												<div class="form-group col-md-6 m-auto pt-4">
													<input type="file" name="file" id="file" class="d-none" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
													<div class="row">
														<button type="button" class="btn btn-primary col-md-5 mr-auto my-1" onclick="downloadFile()"><i class="fa fa-download mr-4"></i>Download demo file
														</button>
														<button type="button" class="btn btn-secondary col-md-5 ml-auto my-1" onclick="onClickFilePick()"><i class="fa fa-paperclip mr-4"></i>Choose
															from excel
														</button>

													</div>
												</div>
											</div>
										</div>
										<div class="row mt-5">
											<div class="col-md-12 text-center">
												<button type="submit" class="btn btn-default">
													Send
												</button>
												<button type="reset" class="btn btn-secondary">Cancel
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
