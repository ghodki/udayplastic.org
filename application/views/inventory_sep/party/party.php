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
									<h5>Add Ladger</h5>
								</div>
								<div class="col-md-6">
									<a href="<?= base_url() ?>party" class="btn btn-info float-right">See Party </a>
								</div>
							</div>

							<form action="<?= base_url() ?>party" method="post">
								<div class="row">
									<!-- <div class="form-group col-sm-3">
										<label class="control-label" for="group">Accounting Group<span class="text-danger">*</span></label>
										<select name="group" id="group" class="form-control" required="">
											<option value="">-Select-</option>
											<?php
											// $acgroup = $this->user_model->getResult('accounting_group');
											// if ($acgroup) {
											// 	foreach ($acgroup as $ag) {
											// 
											?>
											// 		<option value="<?= $ag->id; ?>"><?= $ag->name; ?></option>
											// <?php
												// 	}
												// }

												?>
										</select>
									</div> -->

									<div class="form-group col-sm-3">
										<label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
										<select name="group" id="group" class="form-control searchselect" data-live-search="true" required>
											<option value="">-Select-</option>
											<?php

											$tax = $this->user_model->getResult('accounting_group');
											$role = $this->session->userdata('roles');
											if ($role == 1) {
												if (isset($tax) && !empty($tax)) {
													foreach ($tax as $units) {
											?>
														<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
														<?php
													}
												}
											} else {
												if (isset($tax) && !empty($tax)) {
													foreach ($tax as $units) {
														$user_id = $this->session->userdata('user_id');
														$comp_id = $this->session->userdata('comp_id');
														$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
														$resu = $this->user_model->getRow('accountant_status', $where);
														// $this->session->userdata('user_id');
														if (isset($resu) && !empty($resu)) {
															if ($resu->status == 1) {
														?>
																<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

															<?php
															}
														} else {
															?>
															<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

											<?php
														}
													}
												}
											}
											?>
										</select>
									</div>
									<div class="form-group col-sm-3">
										<label class="control-label" for="name">Name<span class="text-danger">*</span></label>
										<input autocomplete="off" type="text" name="name" id="name" value="<?= set_value('name') ?>" placeholder="Name" class="form-control textOnly" required="">
										<span class="col-sm-4 control-label">
											<?php echo form_error('name'); ?>

										</span>
									</div>

									<div class="form-group col-sm-3">
										<label class="control-label" for="contact">Contact<span class="text-danger"></span></label>
										<input autocomplete="off" type="number" name="contact" id="contact" value="<?= set_value('contact') ?>" placeholder="Contact" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" class="form-control">
										<span class="col-sm-4 control-label">
											<?php echo form_error('contact'); ?>

										</span>
									</div>

									<div class="form-group col-sm-3">
										<label class="control-label" for="email">Email<span class="text-danger"></span></label>
										<input autocomplete="off" type="email" name="email" id="email" value="<?= set_value('email') ?>" placeholder="Email" class="form-control">
										<span class="col-sm-4 control-label">
											<?php echo form_error('email'); ?>

										</span>
									</div>
									<div class="form-group col-sm-3">
										<label class="control-label" for="address_1">Address<span class="text-danger"></span></label>
										<input autocomplete="off" type="text" name="address_1" id="address_1" value="<?= set_value('address_1') ?>" placeholder="Address" class="form-control">
										<span class="col-sm-4 control-label">
											<?php echo form_error('address_1'); ?>

										</span>
									</div>

									<div class="form-group col-sm-3">
										<label class="control-label" for="state">state <span class="text-danger">*</span></label>
										<select name="state" id="state" class="form-control searchselect" data-live-search="true" required>
											<option value="">-Select-</option>
											<?php $states = $this->db->get('state_list')->result();
											if ($states) {
												foreach ($states as $row) { ?>
													<option value="<?= $row->id ?>" data-id="<?= $row->code ?>" data-state="<?= $row->state ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>
											<?php
												}
											}
											?>
										</select>
									</div>
									<div class="form-group col-sm-3">
										<label class="control-label" for="state_code">Code</label>
										<input autocomplete="off" type="text" readonly="" name="state_code" id="code" value="" placeholder="Code" class="form-control" required="">
									</div>

									<div class="form-group col-sm-3">
										<label class="control-label" for="state">City <span class="text-danger">*</span></label>
										<select name="city" id="city" class="form-control searchselect" data-live-search="true">
											<option value="">-Select-</option>
											<?php $states = $this->db->get('districts')->result();
											if ($states) {
												foreach ($states as $row) { ?>
													<option value="<?= $row->id ?>" data-id="<?= $row->name ?>" data-state="<?= $row->name ?>" <?= set_value('city') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
											<?php
												}
											}
											?>
										</select>
									</div>

									<!--<div class="form-group col-sm-3">-->
									<!--<label class="control-label" for="tehsils">Tehsils</label>   -->
									<!--   <select name="tehsils" id="tahsil_id" class="form-control searchselect"  data-live-search="true">-->
									<!--      <option value="">-Select-</option>           -->
									<!--   </select>-->
									<!--</div>-->
									<!--<div class="form-group col-sm-3">-->
									<!--<label class="control-label" for="blocks">Blocks</label>-->
									<!--   <select name="blocks" id="block" class="form-control searchselect"  data-live-search="true">-->
									<!--      <option value="">-Select-</option>           -->
									<!--   </select>-->
									<!--</div>-->

									<div class="form-group col-sm-3">
										<label class="control-label" for="pin">Pin Code</label>
										<input autocomplete="off" type="text" name="pin" id="pin" placeholder="Pin Code" value="<?= set_value('pin') ?>" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
										<span class="col-sm-4 control-label">
											<?php echo form_error('pin'); ?>

										</span>
									</div>

									<div class="form-group col-sm-3">
										<label class="control-label" for="pan">Pan No</label>
										<input autocomplete="off" type="text" name="pan" id="pan" placeholder="Pan No" value="<?= set_value('pan') ?>" class="form-control toUpper" maxlength="10">
										<span class="col-sm-4 control-label">
											<?php echo form_error('pan'); ?>

										</span>
									</div>
									<div class="form-group col-sm-3">
										<label class="control-label" for="credit_limit">Credit</label>
										<input autocomplete="off" type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit') ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
									</div>
									<div class="form-group col-sm-3">
										<label class="control-label" for="gstType">GST Type</label>
										<select name="gstType" id="gstType" class="form-control">
											<option value="">-Select-</option>
											<option value="Regular" <?= set_value('gstType') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
											<option value="Composite" <?= set_value('gstType') == 'Composite' ? 'selected' : ''; ?>>Composite</option>
											<option value="N/A" <?= set_value('gstType') == 'N/A' ? 'selected' : ''; ?>>N/A</option>
										</select>
									</div>
									<div class="form-group col-sm-3" id="gstTypeVal" style="display:none;">
										<label class="control-label" for="gst">
											<a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No &gt;&gt;</a>
										</label> 
										<input autocomplete="off" type="text" name="gst" id="gstTaken" tabindex="15" placeholder="GST No" onkeyup="checkStateCode()" onblur="checkGstDetails()" value="<?= $party->gst; ?>" class="form-control form-control-sm" maxlength="15">
                                        <input autocomplete="off" type="hidden" name="ledger_gst_details" id="ledger_gst_details" value="" placeholder="GST No"> 
									</div>
									<div class="form-group col-sm-3">
										<label class="control-label" for="opening_balance">Opening Balance</label>
										<input autocomplete="off" type="text" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="<?= set_value('opening_balance') ?>" class="form-control">
									</div>

									<div class="form-group col-sm-3">
										<label class="control-label" for="opBalnceType">Type</label>
										<select name="opBalnceType" id="opBalnceType" class="form-control">
											<option value="">-Select-</option>
											<option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR</option>
											<option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR</option>
										</select>
									</div>

									<div class="form-group col-md-3" style="margin-top:30px;">
										<label class="control-label" for="sms">Send Sms</label>
										<input type="checkbox" name="sms" id="sms">

									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-12 col-md-12">
										<label class="control-label" for="remark">Remark</label>
										<input autocomplete="off" height="100px;" type="text" name="remark" id="remark" tabindex="16" placeholder="Remark" value="<?= set_value('remark') ?>" class="form-control">
									</div>
								</div>
								<!-- <div class="row">
         <div class="col-12 bank_field">
          <h5>Bank Details</h5>
          </div>
      </div>
<div class="row">
   <div class="form-group col-sm-3 bank_field">
   <label class="control-label" for="bank_account_name">Bank Account Name</label>
      <input autocomplete="off" type="text" name="bank_account_name" id="bank_account_name" placeholder="Bank Account Name" value="" class="form-control" >
   </div>
   <div class="form-group col-sm-3 bank_field">
   <label class="control-label" for="bank_account_no">Bank Account Nunber</label>
      <input autocomplete="off" type="text" name="bank_account_no" id="bank_account_no" placeholder="Enter Account Nunber" value="" class="form-control" >
   </div>
   <div class="form-group col-sm-3 bank_field">
   <label class="control-label" for="bank_name">Bank Name</label>
      <input autocomplete="off" type="text" name="bank_name" id="bank_name" placeholder="Bank Name" value="" class="form-control" >
   </div>
   <div class="form-group col-sm-3 bank_field">
   <label class="control-label" for="ifsc">IFSC</label>
      <input autocomplete="off" type="text" name="ifsc" id="ifsc" placeholder="Enter IFSC" value="" class="form-control" >
   </div>
   <div class="form-group col-sm-3 bank_field">
   <label class="control-label" for="bank_address">Bank Address</label>
      <input autocomplete="off" type="text" name="bank_address" id="bank_address" placeholder="Enter Bank Address" value="" class="form-control" >
   </div>
   <div class="form-group col-sm-3 bank_field">
   <label class="control-label" for="branch_name">Branch Name</label>
      <input autocomplete="off" type="text" name="branch_name" id="branch_name" placeholder="Branch Name" value="" class="form-control" >
   </div>
   <div class="form-group col-sm-3 bank_field">
   <label class="control-label" for="current_balance">Current Balance</label>
      <input autocomplete="off" type="text" name="current_balance" id="current_balance" placeholder="Current Balance" value="" class="form-control" >
   </div>
</div> -->

								<div class="row">
									<div class="form-group ml-auto pr-5">
										<input autocomplete="off" type="submit" class="btn btn-primary fr" value="Save">
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

<script>
	$(document).ready(function() {
		$('#gstTypeVal').hide()
		//   $('.searchselect').select2();

		//   $('#state').change(function() {
		//      var code = $(this).find(':selected').data('id');
		//      state = $(this).find(':selected').data('state');
		//      $('#state_code').val(code);

		//   });

		$('#gstType').change(function() {
			if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
				$('#gstTypeVal').show();
				$('#gst').attr('required', true);
			} else {
				$('#gstTypeVal').hide();
				$('#gst').attr('required', false);
			}
		});
		<?php if (isset($error) && !empty($error)) { ?>
			$('#gstTypeVal').show();
		<?php } ?>
	});


	// ----------------- 
	//    $(document).ready(function() {
	//       var state = $('#state').val();
	//       if (parseInt(state) > 0) {
	//          $.ajax({
	//             url: '<?= base_url('welcome/getdistict/'); ?>' + state,
	//             success: function(data) {
	//                data1 = JSON.parse(data);
	//                console.log(data1.data, 'city');
	//                $("#district_id").html(data1.data);
	//             }
	//          });
	//       } else {
	//          $("#district").html('');
	//       }
	//    });



	//    $('#state').change(function() {
	//       var state = $(this).val();
	//       if (parseInt(state) > 0) {
	//          $.ajax({
	//             url: '<?= base_url('welcome/getdistict/'); ?>' + state,
	//             success: function(data) {
	//                data1 = JSON.parse(data);
	//                console.log(data1.data, 'city');
	//                $("#district_id").html(data1.data);
	//             }
	//          });
	//       } else {
	//          $("#district").html('');
	//       }
	//    });

	$('#state').change(function() {
		var code = $(this).find(':selected').data('id');
		$('#code').val(code);
	});


	//    $('#district_id').change(function() {
	//       var dist_id = $(this).val();
	//       if (parseInt(dist_id) > 0) {
	//          $.ajax({
	//             url: '<?= base_url('welcome/gettahsil/'); ?>' + dist_id,
	//             success: function(data) {
	//                data1 = JSON.parse(data);
	//                console.log(data1.data);
	//                $("#tahsil_id").html(data1.data);
	//             }
	//          });
	//       } else {
	//          $('#avalable_stock').val(0);
	//          $('#check_stock').val(0);
	//       }
	//    });


	//    $('#tahsil_id').change(function() {
	//       var tahsil_id = $(this).val();
	//       if (parseInt(tahsil_id) > 0) {
	//          $.ajax({
	//             url: '<?= base_url('welcome/getblocks/'); ?>' + tahsil_id,
	//             success: function(data) {
	//                data1 = JSON.parse(data);
	//                console.log(data1.data);
	//                $("#block").html(data1.data);
	//             }
	//          });
	//       } else {
	//          $('#avalable_stock').val(0);
	//          $('#check_stock').val(0);
	//       }
	//    });


	//    $('#group_head').change(function() {
	//       var group_head_val = $(this).val();
	//       if (group_head_val == 35) {
	//          $('.bank_field').hide();

	//          // $('.bank_field input["text"]').attr('required', false);    
	//       } else if (group_head_val == 106) {
	//          $('.bank_field').hide();

	//          // $('.bank_field input["text"]').attr('required', false);    
	//       } else {
	//          $('.bank_field').show();

	//          // $('.bank_field input["text"]').attr('required', true);  
	//       }
	//    });

	//    $(document).ready(function() {
	//       var group_head_val = $('#group_head').val();


	//       if (group_head_val == 35) {
	//          $('.bank_field').hide();

	//       } else if (group_head_val == 106) {
	//          $('.bank_field').hide();

	//          // $('.bank_field input["text"]').attr('required', false);    
	//       } else {
	//          $('.bank_field').show();

	//       }
	//    })
</script>
