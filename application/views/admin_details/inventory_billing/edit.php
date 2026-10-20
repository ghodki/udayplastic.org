<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
						<div class="container-fluid">
							<!-- <div class="header-body "> -->
							<!-- <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Inventory Billing</h2> -->
							<!-- Card stats -->
							<!-- <div class="row"> -->

							<!-- <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>inventory_billing/save" method="POST" enctype="multipart/form-data"> -->



							<button style="margin-left:10px" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
								Update Company
							</button>

							<!-- The Modal -->
							<div class="modal fade" id="myModal">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">

										<!-- Modal Header -->
										<div class="modal-header">
											<h4 class="modal-title">Company</h4>
											<button type="button" class="close" data-dismiss="modal">&times;</button>
										</div>

										<!-- Modal body -->
										<div class="modal-body">
											<div class="header-body ">
												<h2 style="color:black;padding:10px 0px 10px 0px" class="text-center">Update Company</h2>
												<!-- Card stats -->
												<div class="row">

													<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>inventory_billing/update" method="POST" enctype="multipart/form-data">

                                                        

														<div class="row">
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<div class="form-group">
																	<input id="name" name="name" placeholder="Company Name" type="text" class="form-control" required="true" value="<?= $inventory_billing->company_name; ?>">
																</div>
															</div>
															<input type="hidden" name="id" value="<?= $inventory_billing->id; ?>">
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<div class="form-group">
																	<input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= $inventory_billing->number; ?>">
																</div>
															</div>
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<div class="form-group">
																	<input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= $inventory_billing->email; ?>">
																</div>
															</div>
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<div class="form-group">
																<input type="hidden" name="ledger_gst_details" id="ledger_gst_details" value="<?= $inventory_billing->gst_details ?>" />
																	<input name="gst" placeholder="GST Number" type="text" id="gstTaken" onblur="checkGstDetails()" class="form-control" value="<?= $inventory_billing->gst_no; ?>" maxlength="15">
																</div>
															</div>
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<div class="form-group">
																	<input name="contact" placeholder="Contact person" type="text" class="form-control" value="<?= $inventory_billing->contact_person; ?>">
																</div>
															</div>
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<select name="state" id="state" class="form-control">
																	<?php
																	$where = ['id' => $inventory_billing->state];
																	$acgroup = $this->user_model->getRow('state_list', $where);
																	if ($acgroup) {
																		$name = $acgroup->state;
																	} else {
																		$name = "<span class='text-danger'>Deleted</span>";
																	}
																	?>
																	<option value="<?= $inventory_billing->state ?>" <?= set_value('state') == $inventory_billing->state ? 'selected' : ''; ?>><?= $name; ?></option>

																	<?php
																	$acgroup = $this->user_model->getResult('state_list');
																	if ($acgroup) {
																		foreach ($acgroup as $row) {

																	?>
																			<option value="<?= $row->id ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>

																	<?php
																		}
																	}

																	?>

																</select>
															</div>
															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<div class="form-group">
																	<input name="address" placeholder="Address" type="text" class="form-control" value="<?= $inventory_billing->address; ?>">
																</div>
															</div>

															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<select id="cate" name="cate_id" class="form-control" required>
																	<?php
																	$where = ['id' => $inventory_billing->billing_format_id];
																	$row = $this->user_model->getRow('billing_format', $where);
																	if ($row) {
																	?>
																		<option selected value="<?= $row->id; ?>"><?= $row->name; ?></option>
																	<?php

																	}
																	?>
																	<?php $cate = $this->user_model->getResult('billing_format');
																	if ($cate) {
																		foreach ($cate as $key => $value) {
																	?>
																			<option value="<?= $value->id; ?>"><?= $value->name; ?></option>

																	<?php	}
																	}
																	?>


																</select>

															</div>

															<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
																<select id="ac" name="ac" class="form-control" required>
																	<?php
																	if ($inventory_billing->accounting == 1) { ?>
																		<option selected value="1">Basic</option>

																	<?php
																	} else {
																	?>
																		<option selected value="2">Advanced</option>

																	<?php
																	}
																	?>

																	<option value="1">Basic</option>
																	<option value="2">Advanced</option>





																</select>

															</div>


															<!-- <div class="col-md-6 col-md-6 col-sm-6 col-lg-6 form-group">
												<div class="custom-file">
													<input id="customFile" name="image" type="file" class="custom-file-input" value="">
													<label class="custom-file-label" for="customFile">Choose file</label>
												</div>
											</div> -->



														</div>


														<div class="row mt-5">
															<div class="col-md-12 text-center">
															<input type="button" id="refresh_gst" onclick="checkGstDetails()" class="btn btn-outline-primary" style="cursor: pointer;" class="btn btn-info" value="Refresh Gst" /> 
																<button type="submit" class="btn btn-default">


																	Save

																</button>
																<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
															</div>
														</div>
													</form>
												</div>
											</div>

											<!-- Modal footer -->
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>

										</div>
									</div>
								</div>

								<!-- <div class="row">
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<input id="name" name="name" placeholder="Company Name" type="text" class="form-control" required="true" value="">
													</div>
												</div>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<input name="number" placeholder="Mobile Number" type="text" class="form-control" required="true" value="">
													</div>
												</div>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<input id="email" name="email" placeholder=" Email" type="email" class="form-control" required="true" value="">
													</div>
												</div>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<input name="gst" placeholder="GST Number" type="text" class="form-control" required="true" value="">
													</div>
												</div>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<input name="contact" placeholder="Contact person" type="text" class="form-control" required="true" value="">
													</div>
												</div>
												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<div class="form-group">
														<input name="Address" placeholder="Address" type="text" class="form-control" required="true" value="">
													</div>
												</div>

												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<select id="cate" name="cate_id" class="form-control" required>
														<option value=''>Billing Format</option>
														<?php
														$cate = $this->user_model->getResult('billing_format');
														if ($cate) {
															foreach ($cate as $key => $value) {
														?>
																<option value="<?= $value->id; ?>"><?= $value->name; ?></option>

														<?php	}
														}
														?>


													</select>

												</div>

												<div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
													<select id="ac" name="ac" class="form-control" required>
														<option value=''>Accounting Type</option>
														<option value="1">Basic</option>
														<option value="2">Advanced</option>





													</select>

												</div> -->


								<!-- <div class="col-md-6 col-md-6 col-sm-6 col-lg-6 form-group">
												<div class="custom-file">
													<input id="customFile" name="image" type="file" class="custom-file-input" value="">
													<label class="custom-file-label" for="customFile">Choose file</label>
												</div>
											</div> -->


								<!-- 
											</div>


											<div class="row mt-5">
												<div class="col-md-12 text-center">
													<button type="submit" class="btn btn-default">


														Save

													</button>
													<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
												</div>
											</div>
									</form>
								</div> -->
							</div>
						</div>
						<div class="container-fluid mb-9" style="margin-top:10px;">

							<div class="col">
								<div class="card shadow">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/mastersindia_gst.js"></script>
<script>
	  function checkGstDetails() {
        var gst = $("#gstTaken").val(); 
        console.log(gst,'gst func'); 
        if (gst != '') { 
             $(".loader").show();
            gst_num_varifie(gst);
            //alert(gst);  
        } else {

            alert("Please Enter Gst Number");

        }
    } 
</script>