<style>
	td.active {
		border: 1px solid blue;
		font-weight: bold;
		color: yellow;
		background-color: red
	}

	td {
		padding: 5px;
		text-align: center
	}

	.form-control {
		width: 100%;
		height: 32px;
	}

	/* .col-sm-3 {
		margin-bottom: -1rem;
	}
	.col-sm-6 {
		margin-bottom: -1rem;
	} */
</style>
<div class="content-wrapper" id="donknow">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:34px;">
				<div class="col-lg-12 card">
					<div class="card-header row">
						<div class="col-md-2">
							<h4>Create Purchases</h4>
						</div>
						<div class="col-md-2">
							<select class="form-control" onchange="changeFormat()" name="format" id="formats">
								<!-- <option value="">----</option> -->

								<option value="Tax" <?= set_value('format') == 'Tax' ? 'selected' : ''; ?>>Tax
								</option>
								<option value="Estimate" <?= set_value('format') == 'Estimate' ? 'selected' : ''; ?>>Estimate
								</option>

							</select>

						</div>
						<div class="col-md-6">

						</div>
						<!-- </div> -->





						<!-- <button class="btn btn-info" id="sidekaro">Side</button> -->


						<div class="col-md-1">
							<a class="nav-link" title="Expand" onclick="toggleData()">
								<i style="margin-left:60px;" class="fas fa-expand"></i>
							</a>
						</div>
						<div class="col-md-1">
							<a class="nev-link" href="<?= base_url() ?>purchase/purchase_report" title="Purchase List">
								<i style="margin-top:12px;" class="fas fa-list"></i>

							</a>
						</div>
					</div>

					<form action="<?= base_url() ?>purchase/savepurchase" class="form-horizontal" style="margin-left:20px;margin-top:8px" id="dataForm" method="post" onsubmit="onFormSubmitHandler(event)">
						<div class="row">
							<div class="col-md-10" id="fulldisplay">
								<div class="row mb-3">
									<div class="col-sm-4">
										<div class="row mb-1">
											<label for="invoiceNumber" class="col-4">Invoice No.</label>

											<?php
											if (isset($invoiceNumber) && !empty($invoiceNumber)) {
											?>
												<input autocomplete="nope" tabindex="1" type="text" class="form-control col-8" required="true" name="invoiceNumber" id="invoiceNumber" value="<?= $invoiceNumber ?>">
												<?php
											} else {

												$user_id = $this->session->userdata('user_id');
												$comp_id = $this->session->userdata('comp_id');
												$where = ['series.comp_id' => $comp_id, 'series.user_id' => $user_id, 'series.status' => 2];
												$series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id=series.series_type')->like('series', 'purchases')->where($where)->get()->row();
												// echo '<pre>';
												// print_r($this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id=series.series_type')->like('series', 'purchases')->where($where)->get()->row());
												// die;
												//         $query = $this->db->query("SELECT Auto_increment FROM information_schema.tables WHERE table_name='purchases' AND table_schema='modernco_mis'");
												//  $row = $query->row()->Auto_increment;
												// echo   $this->db->last_query();die;
												// print_r($series);
												// die;
												if (isset($series) && !empty($series)) {
													// $a = $series->start_point;
													// $ser1 = explode('-', $a);
													// $count = count($ser1);
													// $ser1[$count - 1] = $ser1[$count - 1] + 1;
													// $ser2 = implode("-", $ser1);

													$ser2 = $this->user_model->getSeris($series);
													// echo '<pre>';
													// print_r($ser2);
													// die;
												?>
													<input autocomplete="nope" type="text" class="form-control col-8" required="true" name="invoiceNumber" id="invoiceNumber" tabindex="1" value="<?= $ser2 ?>">
													<input autocomplete="nope" type="hidden" class="form-control col-8" required="true" name="series_id" id="series_id" readonly value="<?= $series->series_id; ?>">

													<?php } else {
													$user_id = $this->session->userdata('user_id');
													$comp_id = $this->session->userdata('comp_id');
													$where = ['comp_id' => $comp_id, 'user_id' => $user_id,];
													// $order=['id'=>'asc'];
													$order = ['id' => 'DESC'];


													$re = $this->user_model->getAryss('purchases', $order, '', '', $where);

													// echo '<pre>';
													// print_r($re);
													// die;
													if ($re) {
														$invo = $re[0]->invoiceNumber;
														$ser1 = explode('-', $invo);
														$count = count($ser1);
														$ser1[$count - 1] = $ser1[$count - 1] + 1;
														$ser2 = implode("-", $ser1);
														// $this->db->select_max('invoiceNumber');
														// $this->db->from('purchases');
														// $query = $this->db->get();
														// if($query->num_rows() > 0){
														//   echo  $query->row('invoiceNumber');        
														// }else{
														//   echo 1;
														// }
														// die;

														// echo '<pre>';
														// print_r($ser2);
														// die;
														// $this->db->select_max('invoiceNumber', 'max');
														// $query = $this->db->get('purchases');
														// if ($query->num_rows() == 0) {
														//    echo 1;
														// }
														// $max = $query->row()->max;
														// echo  $max == 0 ? 1 : $max + 1;
														// die;

														// $invoicNumber = $invo + 1;
													?>
														<input autocomplete="nope" tabindex="1" type="text" class="form-control col-8" required="true" name="invoiceNumber" id="invoiceNumber" value="<?= $ser2 ?>">
														<input type="hidden" value="" name="series_id">

													<?php
													} else {
													?>
														<input autocomplete="nope" tabindex="1" type="text" class="form-control col-8" required="true" name="invoiceNumber" id="invoiceNumber" value="<?= 1 ?>">
														<input type="hidden" value="" name="series_id">
													<?php
													}
													?>
											<?php }
											}
											?>

										</div>
										<div class="row mb-1">
											<label for="invoiceDate" class="col-4">Date</label>
											<input type="date" tabindex="2" class="form-control col-8" required="true" name="invoiceDate" id="invoiceDate" value="<?php echo date('Y-m-d'); ?>">
										</div>
										<div class="row mb-1">
											<label for="voucherType" class="col-4" title="Voucher Type">Vchr Typ</label>
											<select tabindex="3" name="voucherType" id="voucherType" required="true" class="form-control col-8">

												<option value="1" <?= set_value('voucherType') == '1' ? 'selected' : ''; ?>>Cash
												</option>
												<option value="2" <?= set_value('voucherType') == '2' ? 'selected' : ''; ?>>Credit
												</option>
											</select>
										</div>
										<div class="mb-1 row">
											<label for="taxcal" class="col-4">Tax Cal</label>
											<select name="taxcal" tabindex="4" id="taxcal" required class="form-control  col-8">
												<option value="1" <?= set_value('taxcal') == '1' ? 'selected' : ''; ?>>
													Inclusvie</option>
												<option value="2" <?= set_value('taxcal') == '2' ? 'selected' : ''; ?>>
													Exclusive</option>
											</select>
										</div>
									</div>
									<input type="hidden" name="format_invoice" id="format_invoice" value="Tax">
									<div class="col-sm-5">
										<div class="row mb-1 ">
											<label for="customerId" class="col-3">Party</label>
											<div class="col-9 p-0">
												<div id="party_idDiv" style="width:93%;float:left">
													<input type="hidden" name="customerId" id="party_id" style="width: 100%;" value="" required="true">
													<input type="text" tabindex="5" placeholder="Party Name" onkeyup="showPartyPop(event)" class="form-control col-8 modal-lg" name="partyname" id="partyname" style="width: 100%;" value="<?= get_title('parties', ['id' => set_value('party_id')], 'name') ?>" required="true">

												</div>
												<div id="customerNameDiv" style="width:89%;float:left">
													<input type="text" name="customerName" id="customerName" style="width: 100%;" class="form-control textOnly" disabled>
												</div>
												<!-- <span style="cursor: pointer;" class="fr ml-1" id="consignee" title="Add Consignee"><i class="fa fa-lg fa-book text-success" aria-hidden="true"></i></span> -->
												<!-- <span class="fr addText" title="Enter new customer name" onclick="showTextBox()" style="margin-right: 2px"><i class="fa fa-lg fa-user text-warning"></i></span> -->
												<!-- <span class="fr addDdl" title="Select new customer name" onclick="showDDLBox()"><i class="fa fa-lg fa-user text-info"></i></span> -->
												<span class="fr mr-1 addPatys" title="Add new customer" data-toggle="modal" data-target="#exampleModal" style="cursor: pointer;"><i style="margin-left:6px;" class="fa fa-lg fa-plus-circle text-primary"></i></span>

											</div>
										</div>

										<!-- Modal -->
										<!-- Party Select Start Modal -->
										<div class="modal fade modal-xlg" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
											<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLongTitle1">
															<strong>Select Party</strong>
														</h5>
														<button type="button" id="closepartymodel" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														<!-- <div class="form-group"> -->
														<input type="search" name="searchparty" onkeydown="return keyDown(event)" autocomplete="off" id="searchparty" onkeypress="return enterKeyPressed(event)" class="form-control position-fixed" style="width:45%;margin-left:141px;">
														<!-- </div> -->

													</div>
													<div class="modal-body" style="height:500px;">


														<table class="table table-striped table-bordered table-responsive-sm dataTable" style="overflow:scroll" id="example12">

															<thead class="text-dark">
																<tr>
																	<th>Name</th>
																	<th>Contact</th>
																	<th>Email</th>
																	<th>Edit</th>
																</tr>
															</thead>
															<tbody id="tbody">
																<?php
																//   $party = $this->db->select('party_id, name, city')->order_by('name', 'ASC')->get_where('parties', ['status' => 1])->result();
																$this->db->select('*');
																$this->db->from('parties');
																$this->db->where('status', 1);
																$this->db->where('comp_id', $this->session->userdata('comp_id'));
																$this->db->where('user_id', $this->session->userdata('user_id'));
																$this->db->where_in('group_head');
																$this->db->limit(20);
																$this->db->order_by('name', 'ASC');
																$query = $this->db->get();
																$partys = $query->result();

																if ($partys) {

																	foreach ($partys as $key => $party) {
																?>

																		<tr style="margin-top:10px;" id="trp<?= $key ?>" tabindex="-1" onkeyup="keyUpForParty(event,<?= $key ?>)" onkeypress="getCustomerDetail(<?= $party->id ?>,<?= $key ?>)" onkeydown="keyDownForParty(event,<?= $key ?>)" onclick="getCustomerDetail(<?= $party->id ?>,<?= $key ?>)">

																			<td>
																				<?= $party->name ?></td>
																			<td><?= $party->contact ?></td>
																			<td><?= $party->email ?></td>
																			<td><a href="#" onmouseover="partyProfileEdit(<?= $key ?>,<?= $party->id ?>,event)">Edit</a>
																			</td>





																		<tr id="hiddendata<?= $key ?>" style="display:none;width:142%">
																			<td>
																				<div class="" id="partyMsg<?= $key ?>" style="opacity: 500; display: none;"></div>

																				<!-- <div class="row"> -->
																				<!-- <h1 class="text-center">Party Update</h1> -->
																				<form action="<?= base_url() ?>party/update" method="post">
																					<div class="row">

																						<input type="hidden" name="id" value="<?= $party->id ?>" id="partyId<?= $key ?>">

																						<!-- <input type="hidden" name="id" value="<?= $party->id ?>"> -->
																						<div class="form-group col-sm-3">
																							<?php
																							$tax = $this->user_model->getResult('accounting_group');

																							?>
																							<label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
																							<select id="group<?= $key ?>" name="group" class="form-control" required>
																								<option value="">-Select-</option>
																								<option value="<?= $party->group_head ?>" <?= set_value('group', $party->group_head) == $party->group_head ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $party->group_head], 'name'); ?></option>

																								<?php

																								if ($role == 1) {
																									if (isset($tax) && !empty($tax)) {
																										foreach ($tax as $units) {
																								?>
																											<option value="<?= $units->id ?>" <?= set_value('group', $party->group_head) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

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
																													<option value="<?= $units->id ?>" <?= set_value('group', $units->id) == $units->id ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $units->id], 'name'); ?></option>

																												<?php
																												}
																											} else {
																												?>
																												<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $units->id], 'name'); ?></option>


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
																							<input autocomplete="off" type="text" name="name" id="name<?= $key ?>" value="<?= set_value('name', $party->name) ?>" placeholder="Name" class="form-control textOnly" required="">
																							<span class="col-sm-4 control-label">
																								<?php echo form_error('name'); ?>

																							</span>
																						</div>

																						<div class="form-group col-sm-3">
																							<label class="control-label" for="contact">Contact<span class="text-danger"></span></label>
																							<input autocomplete="off" type="number" name="contact" id="contact<?= $key ?>" value="<?= set_value('contact', $party->contact) ?>" placeholder="Contact" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" class="form-control">
																							<span class="col-sm-4 control-label">
																								<?php echo form_error('contact'); ?>

																							</span>
																						</div>

																						<div class="form-group col-sm-3">
																							<label class="control-label" for="email">Email<span class="text-danger"></span></label>
																							<input autocomplete="off" type="email" name="email" id="email<?= $key ?>" value="<?= set_value('email', $party->email) ?>" placeholder="Email" class="form-control">
																							<span class="col-sm-4 control-label">
																								<?php echo form_error('email'); ?>

																							</span>
																						</div>
																						<div class="form-group col-sm-3">
																							<label class="control-label" for="address_1">Address<span class="text-danger"></span></label>
																							<input autocomplete="off" type="text" name="address_1" id="address_1<?= $key ?>" value="<?= set_value('address_1', $party->address_1) ?>" placeholder="Address" class="form-control">
																							<span class="col-sm-4 control-label">
																								<?php echo form_error('address_1'); ?>

																							</span>
																						</div>

																						<div class="form-group col-sm-3">
																							<label class="control-label" for="state">State <span class="text-danger">*</span></label>
																							<select name="state" onchange="stateChanger(event,<?= $key ?>)" id="state<?= $key ?>" class="form-control">
																								<?php
																								$where = ['id' => $party->state];
																								$acgroup = $this->user_model->getRow('state_list', $where);
																								if ($acgroup) {
																									$name = $acgroup->state;
																								}
																								?>
																								<option value="<?= $party->state ?>" <?= set_value('state') == $party->state ? 'selected' : ''; ?>><?= $name; ?></option>

																								<?php
																								$acgroup = $this->user_model->getResult('state_list');
																								if ($acgroup) {
																									foreach ($acgroup as $row) {

																								?>
																										<option value="<?= $row->id ?>" data-id="<?= $row->code ?>" data-state="<?= $row->state ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>

																										<!-- <option value="<?= $ag->id; ?>"><?= $ag->state; ?></option> -->
																								<?php
																									}
																								}

																								?>

																							</select>
																						</div>
																						<div class="form-group col-sm-3">
																							<label class="control-label" for="state_code">Code</label>
																							<input autocomplete="off" type="text" readonly="" name="state_code" id="state_code<?= $key ?>" value="<?= set_value('code', $party->state_code) ?>" placeholder="Code" class="form-control" required="">
																						</div>

																						<div class="form-group col-sm-3">
																							<label class="control-label" for="state">City <span class="text-danger">*</span></label>
																							<select name="city" id="city<?= $key ?>" class="form-control" data-live-search="true" required>
																								<?php
																								$where = ['id' => $party->city];
																								$acgroup = $this->user_model->getRow('districts', $where);
																								if ($acgroup) {
																									$name = $acgroup->name;
																								} else {
																								?>
																									<option value="">Select City</option>
																								<?php
																								}
																								?>

																								<option value="<?= $party->city ?>" <?= set_value('city') == $party->city ? 'selected' : ''; ?>><?= $name; ?></option>

																								<?php $states = $this->db->get('districts')->result();
																								if ($states) {
																									foreach ($states as $row) { ?>
																										<option value="<?= $row->id ?>" <?= set_value('city') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
																								<?php
																									}
																								}
																								?>
																							</select>
																						</div>


																						<div class="form-group col-sm-3">
																							<label class="control-label" for="pin">Pin Code</label>
																							<input autocomplete="off" type="text" name="pin" id="pin<?= $key ?>" placeholder="Pin Code" value="<?= set_value('pin', $party->pin) ?>" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
																							<span class="col-sm-4 control-label">
																								<?php echo form_error('pin'); ?>

																							</span>
																						</div>

																						<div class="form-group col-sm-3">
																							<label class="control-label" for="pan">Pan No</label>
																							<input autocomplete="off" type="text" name="pan" id="pan<?= $key ?>" placeholder="Pan No" value="<?= set_value('pan', $party->pan) ?>" class="form-control toUpper" maxlength="10">
																							<span class="col-sm-4 control-label">
																								<?php echo form_error('pan'); ?>

																							</span>
																						</div>
																						<div class="form-group col-sm-3">
																							<label class="control-label" for="credit_limit">Credit</label>
																							<input autocomplete="off" type="text" name="credit_limit" id="credit_limit<?= $key ?>" placeholder="Credit Limit" value="<?= set_value('credit_limit', $party->credit_limit) ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
																						</div>

																						<div class="form-group col-sm-3">
																							<label class="control-label" for="gstType">GST Type</label>
																							<select name="gstType" id="gstType<?= $key ?>" class="form-control">
																								<!-- <option value=""></option> -->
																								<option value="<?= $party->gstType; ?>" <?= set_value('gstType') ?>><?= $party->gstType; ?></option>

																								<option value="Regular" <?= set_value('gstType') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
																								<option value="Composite" <?= set_value('gstType') == 'Composite' ? 'selected' : ''; ?>>Composite</option>
																								<option value="N/A" <?= set_value('gstType') == 'N/A' ? 'selected' : ''; ?>>N/A</option>
																							</select>
																						</div>
																						<div class="form-group col-sm-3" id="gstTypeVal">
																							<label class="control-label" for="gst">GST No</label>
																							<input autocomplete="off" type="text" name="gst" id="gst<?= $key ?>" placeholder="GST No" value="<?= $party->gst; ?>" class="form-control" maxlength="15">
																						</div>
																						<div class="form-group col-sm-3 ">
																							<label class="control-label" for="opening_balance">Opening Balance</label>
																							<input autocomplete="off" type="text" name="opening_balance" id="opening_balance<?= $key ?>" placeholder="Opening Balance" value="<?= $party->opening_balance ?>" class="form-control">
																						</div>
																						<div class="form-group col-sm-3">
																							<label class="control-label" for="opBalnceType">Type</label>
																							<select name="opBalnceType" id="opBalnceType<?= $key ?>" class="form-control">
																								<!-- <option value="">-Select-</option> -->
																								<option value="<?= $party->opBalnceType ?>" <?= set_value('opBalnceType'); ?>><?= $party->opBalnceType; ?></option>
																								<option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR</option>
																								<option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR</option>
																							</select>
																						</div>
																						<div class="col-sm-3">
																							<div class="form-group">
																								<button type="button" id="clik<?= $key ?>" onclick="partyProfileEdit(<?= $key ?>,<?= $party->id ?>,event)" class="btn btn-secondary">Close</button>
																								<button class="btn btn-primary" onclick="orBanaoForm(<?= $key ?>,event)" id="orbanaoform">Update Party</button>
																							</div>
																						</div>
																					</div>












																			</td>
																		</tr>

																	<?php
																	}
																	?>
																	</tr>
																<?php

																}
																?>
															</tbody>


														</table>


													</div>




													<!-- <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div> -->
												</div>
											</div>
										</div>


										<!--  Party Select Modal Close-->

										<div class="mb-1 row">
											<label for="gstNo" class="col-3">GST No</label>
											<input type="text" placeholder="GST No" tabindex="6" class="form-control col-9" name="gstNo" id="gstNo">
										</div>
										<!-- <div class="mb-1 row">
	<label for="gstType" class="col-3">GST Type</label>
	<select name="gstType" tabindex="8" id="gstType" class="form-control  col-9">
		<option value="">-Select-</option>
		<option value="Regular" <?= set_value('gstType') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
		<option value="Composite" <?= set_value('gstType') == 'Composite' ? 'selected' : ''; ?>>Composite</option>
		<option value="N/A" <?= set_value('gstType') == 'N/A' ? 'selected' : ''; ?>>N/A</option>

	</select>
</div> -->
										<div class="mb-1 row">
											<label for="customerAddress" class="col-3">Address</label>
											<input autocomplete=" nope" tabindex="7" placeholder="Address" cols=" 4" class="form-control col-9" name="customerAddress" id="customerAddress">
										</div>

										<div class="input-group mb-1">
											<label for="Mobile" class="col-3">Mobile </label>

											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fa fa-phone"></i></span>
											</div>
											<input type="text" cols="4" tabindex="8" class="form-control" id="customerMobile" name="customerMobile" placeholder="A/C Mobile" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10">
										</div>

									</div>

									<div class="col-sm-3">
										<div class="row">


											<!-- City Modal Closed -->

											<div class="col-sm-12">
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-envelope"></i></span>
													</div>
													<input type="email" tabindex="9" class="form-control" id="customerEmail" name="customerEmail" placeholder="A/C Email">
												</div>
											</div>
											<!-- <div class="col-sm-12">
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-money"></i></span>
													</div>
													<input type="text" tabindex="14" class="form-control" id="customerBalance" name="customerBalance" readonly="" placeholder="A/C Balance">
												</div>
											</div> -->

											<div class="col-sm-12">
												<div class="input-group mb-1">
													<input type="text" placeholder="State Name" tabindex="10" class="form-control col-8 modal-lg" name="statename" onchange="getStateNames()" id="statename" style="width: 100%;" value="<?= get_title('state_list', ['id' => set_value('customerState')], 'state') ?>" required="true">
													<input type="hidden" name="customerState" id="stateSelect" style="width: 100%;" value="" required="true">
												</div>
											</div>

											<div class="col-sm-12">
												<div class="input-group mb-1">
													<input type="hidden" name="customerCity" id="customerCity" placeholder="City" style="width: 100%;" value="" required="true">
													<input type="text" placeholder="City Name" tabindex="11" onchange="changeCityNames()" class="form-control" name="cityCustomer" id="cityCustomer" style="width: 100%;" value="<?= get_title('districts', ['id' => set_value('customerCity')], 'name') ?>">
													<span id="hiddencity"></span>
												</div>
											</div>

											<div class="col-sm-12">
												<div class="input-group mb-1">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-money"></i></span>
													</div>
													<input type="text" tabindex="12" onkeypress="return amanEnter(event)" class="form-control" id="customerBalance" name="customerBalance" readonly placeholder="A/C Balance">
												</div>
											</div>
										</div>
									</div>
								</div>


								<!-- table -->
								<div class="row mb-3" style="width:100%; height:330px; overflow: scroll;margin-top:-11px;">
									<div class="col-sm-12">

										<table class="table table-bordered inner_table table-responsive-sm mb-0" id="dataTable">

											<thead class="bg-dark">
												<th>Sr</th>
												<?php
												$barcode = $this->session->userdata('barcode');
												if (isset($barcode) && !empty($barcode) && $this->session->userdata('barcode') == 'yes') {
												?>
													<th width="120px;">PCode</th>

												<?php
												}
												?>
												<th width="150px">Product Name

													<a class="cp ml-2 py-0" style="color:#fff;" data-toggle="modal" data-target="#productModel" title="Add New Product"><i class="fa fa-plus-circle " style="font-size: 16px"> </i></a>
												</th>
												<th>Des</th>
												<th width="60px">HSN</th>
												<!-- <th width="70px">Cost</th> -->
												<th width="60px">Qty</th>
												<th width="80x">Rate</th>
												<!-- <th width="80px">Base Rate</th> -->
												<th width="20px">Disc.</th>
												<th width="50px">Tax %</th>
												<th width="80px">TaxableAmt</th>

												<th width="100px">TaxAmt</th>
												<th width="100px">Net Amt</th>
												<th width="45px"></th>
												</tr>
											</thead>
											<tbody>

											</tbody>

										</table>

									</div>
								</div>

								<!-- words -->
								<div class="row" style="margin-top:-13px;">
									<div class="col-sm-12">
										<div class="form-group row pr-3">
											<label class="col-sm-2">Amount in words</label>
											<input type="text" required readonly class="col-sm-10 form-control" id="amountInWords" name="amountInWords">
										</div>
									</div>
								</div>

								<input type="hidden" name="nameCustomerKa" id="nameCustomerKa" value="">
								<?php
								// $purchases = $this->user_model->getResult('purchases');
								// if ($purchases) {
								// 	$purchase_id = $purchases[0]->id;
								// } else {
								// 	$purchase_id = 0;
								// }

								// 
								?>
								<input type="hidden" name="printPurchase" id="printPurchase" value="0">
								<div class="row" style="margin-top:-13px;">
									<div class="col-sm-12">
										<div class="form-group row pr-3">
											<label class="col-sm-2">Remark</label>
											<input autocomplete="off" type="text" class="col-sm-10 form-control" id="remark" name="remark">
										</div>
									</div>
								</div>

								<!-- Submit button -->
								<div class="row mb-3 btnsbmt">
									<div class="col-sm-6 col-md-6 col-xl-6">
										<!-- <div class="mr-1 mb-sm-1"> -->
										<button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg"><u>S</u>ave</button>

									</div>
									<!-- <div class="col-md-2"></div> -->

								</div>
							</div>

							<!-- Right panel -->
							<div class="col-md-2 col-sm-12" id="rightsidebar">
								<div class="row mb-1">
									<label class="col-7 p-0">Description</label>
									<label class="col-5 p-0">Amount</label>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="Taxable Amount">Taxable Amt.</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="taxableAmount" required="true" name="taxableAmount" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="totalsgst">Total SGST </span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totalsgst" required="true" name="totalsgst" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="totalcgst">Total CGST</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totalcgst" required="true" name="totalcgst" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" title="totaligst">Total IGST</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totaligst" required="true" name="totaligst" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Total Tax</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totalTax" name="totalTax" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Expenses</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="expenses" name="expenses" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">CESS</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="cess" name="cess" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Total Value</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="totalValue" name="totalValue" required="true" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Round Off</span>
									<div class="col-7 p-0">
										<input type="number" step="any" id="roundOff" name="roundOff" min="0" value="0" class="form-control">
									</div>
								</div>
								<?php
								if ($this->session->userdata('lrdate') == 'yes') {
								?>
									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px">L.R. Date</span>
										<div class="col-7 p-0">
											<input type="date" id="lrDate" name="lrDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
										</div>
									</div>
								<?php
								}
								if ($this->session->userdata('lrNumber') == 'yes') {
								?>


									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px">L.R. No.</span>
										<div class="col-7 p-0">
											<input type="text" id="lrNumber" name="lrNumber" class="form-control">
										</div>
									</div>
								<?php
								}
								if ($this->session->userdata('eway') == 'yes') {
								?>
									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px">E-Way Bill</span>
										<div class="col-7 p-0">
											<input type="text" id="ewayBill" name="ewayBill" class="form-control">
										</div>
									</div>
								<?php
								}

								if ($this->session->userdata('dpb') == 'yes') {
								?>
									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px">Disp. By</span>
										<div class="col-7 p-0">
											<input type="text" id="transport" name="transport" class="form-control">
										</div>
									</div>
								<?php
								}
								?>
								<!-- <div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Order No</span>
									<div class="col-7 p-0">
										<input type="text" class="form-control" name="orderNumber" id="orderNumber" placeholder="Order Number">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Order Date</span>
									<div class="col-7 p-0">
										<input type="date" class="form-control" name="orderDate" id="orderDate" value="<?php echo date('Y-m-d'); ?>" required="true">
									</div>
								</div> -->
								<?php
								if ($this->session->userdata('ptd') == 'yes') {
								?>
									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px" title="Pay Terms (Day)">PTD.</span>
										<div class="col-7 p-0">
											<input type="text" id="payTermsDay" name="payTermsDay" class="form-control" placeholder="Pay Terms Day">
										</div>
									</div>
								<?php
								}

								if ($this->session->userdata('duedate') == 'yes') {
								?>
									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px">Due Date</span>
										<div class="col-7 p-0">
											<input type="date" id="dueDate" name="dueDate" required="true" class="form-control" value="<?php echo date('Y-m-d'); ?>">
										</div>
									</div>
								<?php
								}

								if ($this->session->userdata('dcno') == 'yes') {
								?>
									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px">DC No.</span>
										<div class="col-7 p-0">
											<input type="text" id="dcNumber" name="dcNumber" class="form-control">
										</div>
									</div>
								<?php
								}

								if ($this->session->userdata('dcdate') == 'yes') {
								?>
									<div class="row form-group mb-1">
										<span class="col-5 p-0" style="font-size: 14px">DC Date</span>
										<div class="col-7 p-0">
											<input type="date" id="dcDate" name="dcDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
										</div>
									</div>
								<?php
								}

								?>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px" title="Old Amount">Old
										Balance</span>
									<div class="col-7 p-0">
										<input type="number" id="oldBalance" name="oldBalance" class="form-control">
									</div>
								</div>
								<div class="row form-group mb-1">
									<span class="col-5 p-0" style="font-size: 14px">Total</span>
									<div class="col-7 p-0">
										<input type="number" id="total" name="total" class="form-control" required="true">
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="row mb-3 btnsbmtm">
								<div class="col-sm-12">
									 <div class="mr-1 mb-sm-1"> -->
						<!-- <button type="submit" id="btnSubmit" class="btn btn-outline-primary">Save F10</button> -->
						<!-- </div> -->
				</div>
				</form>
			</div>
			<div class="row" id="pud" style="margin-left: 165px;margin-top: -187px;">
				<div class="col-sm-6 col-md-6">
					<button class="btn btn-outline-danger btn-lg" style="margin-left:22px;" onclick="return deleteRow();">Delete</button>
					<button id="updateInvoice" onclick="return updateInvoice();" id="updatePurchase" class="btn btn-lg btn-outline-success"><u>U</u>pdate</button>
					<button type="text" onclick="printPurchase()" id="printPurchases" class="btn btn-lg btn-outline-success"><u>P</u>rint</button>
					<button type="text" onclick="printPurchasePreview()" id="printPurchasePreview" class="btn btn-lg btn-outline-success">Print Preview</button>

				</div>
				<div class="col-md-6">
					<button class="btn btn-lg btn-outline-dark" style="margin-left:339px;" onclick="return preview()">Preview</button>
					<button class="btn btn-lg btn-outline-success" style="margin-left:13px;" onclick="return next()">Next</button>

				</div>
			</div>
		</div>
	</div>
</div>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

<script>
	// $(document).ready(function() {
	// 	$('#example12').DataTable();
	// });
</script>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="addUserForm" method="post" action="<?= base_url('party/create') ?>" onsubmit="onUserFormSubmitHandler(event)" autocomplete="on">
					<div class="" id="partyMsg" style="opacity: 500; display: none;"></div>
					<div class="row">
						<!-- <div class="col-md-4 mb-2">
            <label for="type" class="">Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-control searchselect" required>
                <option value="">-Select-</option>
         
            </select>
        </div> -->
						<div class="col-md-4 mb-2">
							<label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
							<select name="group" id="group" class="form-control" data-live-search="true" required>
								<option value="">-Select-</option>
								<?php

								$tax = $this->user_model->getResult('accounting_group');
								$role = $this->session->userdata('roles');
								if ($role == 1) {
									if (isset($tax) && !empty($tax)) {
										foreach ($tax as $units) {
								?>
											<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
											</option>
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
													<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
													</option>

												<?php
												}
											} else {
												?>
												<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
												</option>

								<?php
											}
										}
									}
								}
								?>
							</select>
						</div>

						<div class="col-md-4 mb-2">
							<label for="name" class="">Name <span class="text-danger">*</span></label>
							<input required name="name" id="name" class="form-control textOnly" type="text" placeholder="Enter Name">
						</div>

						<div class="col-md-4 mb-2">
							<label for="contact" class="">Mobile <span class="text-danger"></span></label>
							<input onkeyup="checkContacts()" name="contact" id="contacts" class="form-control" type="text" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" placeholder="Enter Mobile">
							<span id="contackcheck"></span>
							<!-- <span class="text-success" id="contackcheck1"></span> -->


						</div>

						<div class="col-md-4 mb-2">
							<label for="email" class="">Email <span class="text-danger"></span></label>
							<input onkeyup="checkEmail()" name="email" id="emails" class="form-control " type="email" placeholder="Enter Email">
							<span id="mailcheck"></span>
						</div>
						<div class="col-md-4 mb-2">
							<label for="pan" class="">Pan No</label>
							<input name="pan" id="pan" class="form-control toUpper" type="text" placeholder="Enter Pan No" maxlength="10">
						</div>
						<div class="col-md-4 mb-2">
							<label for="address_1" class="">Address <span class="text-danger"></span></label>
							<input name="address_1" id="address_1" class="form-control" type="text" placeholder="Enter Address">
						</div>

						<div class="col-md-4 mb-2">
							<label class="control-label" for="state">City <span class="text-danger"></span></label>
							<select name="city" id="city" class="form-control" data-live-search="true">
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

						<div class="col-md-4 mb-2">
							<label for="pin" class="">Pin <span class="text-danger"></span></label>
							<input type="text" name="pin" id="pin" placeholder="Pin Code" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
						</div>
						<div class="col-md-4 mb-2">
							<label for="state" class="">State <span class="text-danger">*</span></label><br>
							<select name="state" id="state" class="form-control" style="width:100%" required>
								<option value="">Select State</option>
								<?php $states = $this->db->get('state_list')->result();
								if ($states) {
									foreach ($states as $row) { ?>
										<option value="<?= $row->id ?>" data-id="<?= $row->code ?>"> <?= $row->state; ?>
										</option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-md-4 mb-2">
							<label for="code" class="">State Code</label><br>
							<input type="text" readonly name="state_code" id="code" placeholder="Code" class="form-control" required>
						</div>
						<div class="col-md-4 mb-2">
							<label for="credit_limit" class="">Credit Limit</label><br>
							<input type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit') ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
						</div>
						<div class="col-md-4 mb-2">
							<label for="gstTypeParty" class="">gst Type <span class="text-danger"></span></label><br>
							<select name="gstType" id="gstTypeParty" class="form-control">
								<option value="">-Select-</option>
								<option value="Regular">Regular</option>
								<option value="Composite">Composite</option>
								<option value="N/A">N/A</option>
							</select>
						</div>

						<div class="col-md-4 mb-2" id="gstTypeVal">
							<!-- <label for="gst" class="">GSTIN No <span class="text-danger">*</span></label> -->
							<label class="control-label" for="gst">
								<a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No
									&gt;&gt;</a>
							</label>
							<input name="gst" id="gst" class="form-control toUpper" type="text" placeholder="Enter GSTIN No">
						</div>
						<div class="form-group col-md-4 mb-2">
							<label class="control-label" for="opening_balance">Opening Balance</label>
							<input autocomplete="off" type="text" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="<?= set_value('opening_balance') ?>" class="form-control">
						</div>

						<div class="form-group col-md-4 mb-2">
							<label class="control-label" for="opBalnceType">Type</label>
							<select name="opBalnceType" id="opBalnceType" class="form-control">
								<option value="">-Select-</option>
								<option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR
								</option>
								<option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR
								</option>
							</select>
						</div>

						<div class="form-group col-md-4 mb-2" style="margin-top:30px;">
							<label class="control-label" for="sms">Send Sms</label>
							<input type="checkbox" name="sms" id="sms">

						</div>
					</div>
					<!-- <div class="modal-footer"> -->
					<button type="button" class="btn btn-secondary" id="custClose" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="btn_login">Save</button>
					<button type="reset" class="btn btn-danger" id="reset">Reset</button>

					<!-- </div> -->
				</form>
			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="exampleModal07" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="adpartyform" method="post" action="<?= base_url('party/updateparty') ?>" onsubmit="onFormPartySubmitHandler(event)" autocomplete="on">
					<div class="" id="errormsg" style="opacity: 500; display: none;"></div>
					<div class="row">
						<!-- <div class="col-md-4 mb-2">
            <label for="type" class="">Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-control searchselect" required>
                <option value="">-Select-</option>
         
            </select>
        </div> -->
						<div class="col-md-4 mb-2">
							<label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
							<select name="group" id="dontgroup" class="form-control" data-live-search="true" required>
								<option value="">-Select-</option>
								<?php

								$tax = $this->user_model->getResult('accounting_group');
								$role = $this->session->userdata('roles');
								if ($role == 1) {
									if (isset($tax) && !empty($tax)) {
										foreach ($tax as $units) {
								?>
											<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
											</option>
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
													<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
													</option>

												<?php
												}
											} else {
												?>
												<option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
												</option>

								<?php
											}
										}
									}
								}
								?>
							</select>
						</div>

						<div class="col-md-4 mb-2">
							<label for="name" class="">Name <span class="text-danger">*</span></label>
							<input required name="name" id="dontnameememe" class="form-control textOnly" type="text" placeholder="Enter Name">
						</div>

						<div class="col-md-4 mb-2">
							<label for="contact" class="">Mobile <span class="text-danger"></span></label>
							<input onkeyup="checkContacts()" name="number" id="dontnumber" class="form-control" type="text" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" placeholder="Enter Mobile">
							<span id="contackcheck"></span>
							<!-- <span class="text-success" id="contackcheck1"></span> -->


						</div>

						<div class="col-md-4 mb-2">
							<label for="email" class="">Email <span class="text-danger"></span></label>
							<input onkeyup="checkEmail()" name="email" id="dontemail" class="form-control " type="email" placeholder="Enter Email">
							<span id="mailcheck"></span>
						</div>
						<div class="col-md-4 mb-2">
							<label for="pan" class="">Pan No</label>
							<input name="pan" id="dontpan" class="form-control toUpper" type="text" placeholder="Enter Pan No" maxlength="10">
						</div>
						<div class="col-md-4 mb-2">
							<label for="address_1" class="">Address <span class="text-danger"></span></label>
							<input name="address_1" id="dontaddress_1" class="form-control" type="text" placeholder="Enter Address">
						</div>

						<div class="col-md-4 mb-2">
							<label class="control-label" for="state">City <span class="text-danger"></span></label>
							<select name="city" id="dontcity" class="form-control" data-live-search="true">
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

						<div class="col-md-4 mb-2">
							<label for="pin" class="">Pin <span class="text-danger"></span></label>
							<input type="text" name="pin" id="dontpin" placeholder="Pin Code" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
						</div>
						<div class="col-md-4 mb-2">
							<label for="state" class="">State <span class="text-danger">*</span></label><br>
							<select name="state" id="dontstate" class="form-control" style="width:100%" required>
								<option value="">Select State</option>
								<?php $states = $this->db->get('state_list')->result();
								if ($states) {
									foreach ($states as $row) { ?>
										<option value="<?= $row->id ?>" data-id="<?= $row->code ?>"> <?= $row->state; ?>
										</option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-md-4 mb-2">
							<label for="code" class="">State Code</label><br>
							<input type="text" readonly name="state_code" id="dontcode" placeholder="Code" class="form-control" required>
						</div>
						<div class="col-md-4 mb-2">
							<label for="credit_limit" class="">Credit Limit</label><br>
							<input type="text" name="credit_limit" id="dontcredit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit') ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
						</div>
						<div class="col-md-4 mb-2">
							<label for="gstTypeParty" class="">gst Type <span class="text-danger"></span></label><br>
							<select name="gstType" id="dontgstTypeParty" class="form-control">
								<option value="">-Select-</option>
								<option value="Regular">Regular</option>
								<option value="Composite">Composite</option>
								<option value="N/A">N/A</option>
							</select>
						</div>

						<div class="col-md-4 mb-2" id="gstTypeVal">
							<!-- <label for="gst" class="">GSTIN No <span class="text-danger">*</span></label> -->
							<label class="control-label" for="gst">
								<a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No
									&gt;&gt;</a>
							</label>
							<input name="gst" id="dontgst" class="form-control toUpper" type="text" placeholder="Enter GSTIN No">
						</div>
						<div class="form-group col-md-4 mb-2">
							<label class="control-label" for="opening_balance">Opening Balance</label>
							<input autocomplete="off" type="text" name="opening_balance" id="dontopening_balance" placeholder="Opening Balance" value="<?= set_value('opening_balance') ?>" class="form-control">
						</div>

						<div class="form-group col-md-4 mb-2">
							<label class="control-label" for="opBalnceType">Type</label>
							<select name="opBalnceType" id="dontopBalnceType" class="form-control">
								<option value="">-Select-</option>
								<option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR
								</option>
								<option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR
								</option>
							</select>
						</div>
						<input type="hidden" name="partyid" id="dont_party_id">
						<div class="form-group col-md-4 mb-2" style="margin-top:30px;">
							<label class="control-label" for="sms">Send Sms</label>
							<input type="checkbox" name="sms" id="sms">

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" id="custClose" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="btn_login">Update</button>
						<button type="reset" class="btn btn-danger" id="reset">Reset</button>

					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="exampleModal08" tabindex="-1" role="dialog" aria-labelledby="productModalLabel08" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productModalLabel08"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h2 class="text-center">Update Product</h2>
				<!-- Card stats -->
				<div class="row">
					<div id="productMsg" style="opacity: 500; display: none;"></div>

					<form id="onProductHandlers" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/updateproducts" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandlers(event)">

						<?php
						if (isset($error) && !empty($error)) {
							echo $error;
						} ?>

						<div class="row">
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpname" name="name" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error('name'); ?>

								</span>
							</div>
							<input type="hidden" name="id" id="prosId">
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">

								<input type="text" class="form-control" require readonly name="pcode" id="dontppcode" value="">
								<span class="col-sm-4 control-label">
									<?php echo form_error('p_code'); ?>

								</span>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpprice" name="price" min="1" placeholder="Selling Price" type="number" class="form-control" value="<?= set_value('price') ?>">
								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error('price'); ?>

								</span>
							</div>
							<!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="cost" readonly name="Purchase_rate" data-toggle="modal" data-target="#myModal123" min="0" placeholder="Cost" type="number" class="form-control" required="true" value="<?= set_value('Purchase_rate') ?>">
								</div>
							</div> -->
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpcost" value="" name="Purchase_rate" min="0" placeholder="Purchase Price" type="number" class="form-control">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpmrp" name="mrp" min="0" placeholder="MRP" type="number" class="form-control" value="<?= set_value('mrp') ?>">
								</div>
							</div>


							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpqty" name="qty" min="1" placeholder="Product Qunatity" type="number" class="form-control" value="<?= set_value('qty') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpdiscount" name="discount" min="0" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontphsn_code" name="hsn_code" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpmin_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?= set_value('min_qty') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="dontpweight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<select id="dontpunit" name="unit" class="form-control" required>
									<option value="">Select Unit</option>
									<?php
									$unit = $this->user_model->getResult('unit');
									$role = $this->session->userdata('roles');
									if ($role == 1) {
										if (isset($unit) && !empty($unit)) {
											foreach ($unit as $units) {
									?>
												<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
												</option>

												<?php
											}
										}
									} else {
										if (isset($unit) && !empty($unit)) {
											foreach ($unit as $units) {

												$user_id = $this->session->userdata('user_id');
												$comp_id = $this->session->userdata('comp_id');

												$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
												$resu = $this->user_model->getRow('unit_status', $where);
												// $this->session->userdata('user_id');
												if (isset($resu) && !empty($resu)) {
													if ($resu->status == 1) {
												?>
														<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
														</option>

													<?php
													}
												} else {
													?>
													<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
													</option>

									<?php
												}
											}
										}
									}
									?>
								</select>
								<span class="col-sm-4 control-label">
									<?php echo form_error('unit'); ?>

								</span>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">

									<select id="dontptax" name="tax" class="form-control" required>
										<option value="">Select Tax</option>
										<?php
										$tax = $this->user_model->getResult('tax');

										if ($role == 1) {
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $units) {
										?>
													<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
														<?= $units->name; ?></option>

													<?php
												}
											}
										} else {
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $units) {

													$user_id = $this->session->userdata('user_id');
													$comp_id = $this->session->userdata('comp_id');

													$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
													$resu = $this->user_model->getRow('tax_status', $where);
													// $this->session->userdata('user_id');
													if (isset($resu) && !empty($resu)) {
														if ($resu->status == 1) {
													?>
															<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
																<?= $units->name; ?></option>

														<?php
														}
													} else {
														?>
														<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
															<?= $units->name; ?></option>

										<?php
													}
												}
											}
										}
										?>
									</select>

								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error("tax"); ?>

								</span>
							</div>


							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3 form-group">
								<div class="custom-file">
									<input id="dontpcustomFile" name="image" type="file" class="custom-file-input" value="<?= set_value('image') ?>">
									<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<textarea id="dontpshortdescription" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<textarea id="dontpdescription" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= set_value('long_des') ?></textarea>
								</div>
							</div>

						</div>


						<div class="row mt-5">
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-default">


									Update

								</button>
								<a href="#" type="reset" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="productModel" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="productModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h2 class="text-center">Add Product</h2>
				<!-- Card stats -->
				<div class="row">
					<div class="" id="productMsg" style="opacity: 500; display: none;"></div>

					<form id="onProductFormSubmitHandler" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/savePro" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event)">

						<?php
						if (isset($error) && !empty($error)) {
							echo $error;
						} ?>

						<div class="row">
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="product_name" name="name" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error('name'); ?>

								</span>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<!-- <select id="p_code" name="p_code" class="form-control">
													 <option value="">Select Product Code</option> -->
									<?php
									$productcodes = 0;
									$where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
									$productCode = $this->user_model->getRow('productCode', $where);
									if ($productCode) {
										$productcodes = $productCode->productcode;
									}

									// $productDetails = $this->user_model->getAry('product', '', '', '', $where);
									$this->db->from('product');
									// $this->db->where('comp_id', $this->session->userdata('comp_id'));
									// $this->db->where('user_id', $this->session->userdata('user_id'));
									$this->db->where($where);
									$this->db->order_by("id", "desc");
									$productDetails = $this->db->get()->result();
									$total = count($productDetails);

									// $productDetails='';
									if (isset($productDetails) && !empty($productDetails)) {
										$productcodes = 0;
										// foreach ($productDetails as $proData) {
										// 	$productcodes = $proData->qrCode;
										// }
										for ($i = 0; $i < $total; $i++) {
											$productcodes = $productDetails[$i]->qrCode;
											if (is_numeric($productcodes) && $productcodes) {
												$productcodes = $productcodes;
												break;
											}
										}


									?>
										<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productcodes + 1 ?>">


									<?php
									} else {
									?>
										<input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productcodes ?>">

									<?php
									}



									?>

									<span class="col-sm-4 control-label">
										<?php echo form_error('p_code'); ?>

									</span>
								</div>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="price" name="price" min="1" placeholder="Selling Price" type="number" class="form-control" value="<?= set_value('price') ?>">
								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error('price'); ?>

								</span>
							</div>
							<!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="cost" readonly name="Purchase_rate" data-toggle="modal" data-target="#myModal123" min="0" placeholder="Cost" type="number" class="form-control" required="true" value="<?= set_value('Purchase_rate') ?>">
								</div>
							</div> -->
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="cost" name="Purchase_rate" min="0" placeholder="Purchase Price" type="number" class="form-control" value="<?= set_value('Purchase_rate') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="mrp" name="mrp" min="0" placeholder="MRP" type="number" class="form-control" value="<?= set_value('mrp') ?>">
								</div>
							</div>
							<!-- Button to Open the Modal -->


							<!-- The Modal -->
							<!-- <div class="modal" id="myModal123">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">

										 Modal Header -->
							<!-- <div class="modal-header">
											<h4 class="modal-title text-center text-dark">Raw Material</h4>
											<button type="button" class="close" data-dismiss="modal">&times;</button>
										</div> -->

							<!-- Modal body -->
							<!-- <div class="modal-body">
											<div class="row">
												<div class="col-md-6"> -->
							<?php
							// $array = array();
							// // $varr='';
							// $array[0] = "--Select Group--";
							// $acgroup = $this->user_model->getResult('raw_material');
							// if ($acgroup) {
							// 	foreach ($acgroup as $teacher) {
							// 		$array[$teacher->id] = $teacher->name;
							// 	}
							// }

							// echo form_dropdown("raw", $array, set_value("raw"), "id='raw' class='form-control'");
							?>

							<!-- 
													<p></p>
												</div>

												<div class="col-md-6">

												</div>
												<div class="col-md-12 col-lg-12">
													<table id="raw_details" class="table table-striped">
														<tr class="text-dark">
															<th>Name</th>
															<th>Rate</th>
															<th>Weight</th>
															<th>unit</th>

															<th>Qty</th>
															<th>Total</th>



														</tr>
													</table>

												</div>
												<div class="col-md-12 col-lg-12">
													<table class="table table-striped">
														<tr class="text-dark">
															<td></td>
															<td></td>

															<td></td>
															<td></td>

															<td></td>

															<td>Extra:<input class="form-control" min="0" type="number" name="extra" id="extra"></td>
														</tr>

													</table>
												</div>
												<div class="col-md-12 col-lg-12">
													<table class="table table-striped">
														<tr class="text-dark">
															<td></td>
															<td></td>


															<td>Total Weight:<input type="text" class="form-control" readonly id="overall_weight"></td>

															<td>Total:<input class="form-control" type="text" readonly id="overall"></td>
														</tr>

													</table>
												</div>
											</div>
										</div>

										 Modal footer -->
							<!-- <div class="modal-footer">
											<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
										</div>

									</div>
								</div> -->
							<!-- </div> -->

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="qty" name="qty" min="1" placeholder="Product Qunatity" type="number" class="form-control" value="<?= set_value('qty') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="discount" name="discount" min="0" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="hsn_code" name="hsn_code" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="min_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?= set_value('min_qty') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="weight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<select id="unit" name="unit" class="form-control" required>
									<option value="">Select Unit</option>
									<?php
									$unit = $this->user_model->getResult('unit');
									$role = $this->session->userdata('roles');
									if ($role == 1) {
										if (isset($unit) && !empty($unit)) {
											foreach ($unit as $units) {
									?>
												<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
												</option>

												<?php
											}
										}
									} else {
										if (isset($unit) && !empty($unit)) {
											foreach ($unit as $units) {

												$user_id = $this->session->userdata('user_id');
												$comp_id = $this->session->userdata('comp_id');

												$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
												$resu = $this->user_model->getRow('unit_status', $where);
												// $this->session->userdata('user_id');
												if (isset($resu) && !empty($resu)) {
													if ($resu->status == 1) {
												?>
														<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
														</option>

													<?php
													}
												} else {
													?>
													<option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
													</option>

									<?php
												}
											}
										}
									}
									?>
								</select>
								<span class="col-sm-4 control-label">
									<?php echo form_error('unit'); ?>

								</span>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">

									<select id="tax" name="tax" class="form-control" required>
										<option value="">Select Tax</option>
										<?php
										$tax = $this->user_model->getResult('tax');

										if ($role == 1) {
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $units) {
										?>
													<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
														<?= $units->name; ?></option>

													<?php
												}
											}
										} else {
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $units) {

													$user_id = $this->session->userdata('user_id');
													$comp_id = $this->session->userdata('comp_id');

													$where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
													$resu = $this->user_model->getRow('tax_status', $where);
													// $this->session->userdata('user_id');
													if (isset($resu) && !empty($resu)) {
														if ($resu->status == 1) {
													?>
															<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
																<?= $units->name; ?></option>

														<?php
														}
													} else {
														?>
														<option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
															<?= $units->name; ?></option>

										<?php
													}
												}
											}
										}
										?>
									</select>

								</div>
								<span class="col-sm-4 control-label">
									<?php echo form_error("tax"); ?>

								</span>
							</div>
							<!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<?php
								// $array = array();
								// // $varr='';
								// $acgroup = $this->user_model->getResult('product_categories');
								// if ($acgroup) {
								// 	foreach ($acgroup as $teacher) {
								// 		$array[$teacher->id] = $teacher->category_name;
								// 	}
								// }

								// echo form_dropdown("cate", $array, set_value("cate"), "id='cate' class='form-control'");
								?>
								<span class="col-sm-4 control-label">
									<?php
									// echo form_error('cate'); 
									?>

								</span>
							</div> -->

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3 form-group">
								<div class="custom-file">
									<input id="customFile" name="image" type="file" class="custom-file-input" value="<?= set_value('image') ?>">
									<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div>

							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<textarea id="shortdescription" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
								</div>
							</div>
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<textarea id="description" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= set_value('long_des') ?></textarea>
								</div>
							</div>

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
				</div>
			</div>
		</div>

	</div>
</div>

<!-- <div class="modal fade" id="theModal" role="dialog" aria-labelledby="theModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">

		</div>

	</div>
</div> -->
<div class="modal fade modal-xlg" id="exampleModalLong1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"><strong>Select Product</strong></h5>
				<!-- <button type="button" id="closeproductmodel" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button> -->
				<button type="button" id="closeproductmodel" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<input type="hidden" id="prorow">
				<input type="hidden" id="provalue">
				<input type="search" name="searchproduct" autocomplete="off" id="searchproduct" onkeydown="return keyDownProduct(event)" onkeypress="return enterKey(event)" class="form-control position-fixed" style="width:45%;margin-left:141px;">
				<br>
			</div>
			<div class="modal-body" style="height:500px;">
				<!-- <table class="table table-striped table-bordered table-responsive-sm dataTable" id="example13">
					<thead>
						<tr>
							<th>Name</th>
							<th>Price</th>
							<th>MRP</th>
							<th>Edit</th>

						</tr>
					</thead>
					<tbody id="producttbdoy">
					</tbody>
				</table> -->

			</div>




			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button> -->
			</div>

		</div>
	</div>
</div>



<div class="modal fade" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabels"></h5>
				<input type="hidden" id="rownumber">
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>



<div class="modal fade" id="exampleForParty" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabels"></h5>
				<input type="hidden" id="partyRow">
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="mynewmodal" tabindex="-1" role="dialog" aria-labelledby="mynewmodal" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 400px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="mynewmodal"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h2 class="text-center">Description</h2>
				<input type="text" id="description_for" onchange="getDescriptionOnly()" name="description_for" class="form-control">
				<input type="hidden" id="description_for_id" value=''>
				<br>
				<br>
				<br>

				<button class="btn btn-primary" class="close" data-dismiss="modal" onclick="closeModel()">Save</button>
				<!-- Card stats -->
				<div class="row">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="desca" tabindex="-1" role="dialog" aria-labelledby="desca" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="descasss"></h5>
				<button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h2 class="text-center">Add Product</h2>
				<!-- Card stats -->
				<div class="row">
					<div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
						<div class="form-group">
							<input id="description" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
						</div>

					</div>
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
			</div>
		</div>
	</div>

</div>


<script>
	function onProductFormSubmitHandler(e) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#onProductFormSubmitHandler").attr("action");
		var form = $("#onProductFormSubmitHandler")[0];
		var data = new FormData(form);
		//(data);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					alert("Product Added")
					document.getElementById('productModel').click();
				} else {
					$("#productMsg").show();
					$("#productMsg").addClass("text");
					$("#productMsg").addClass("text-danger");
					$("#productMsg").html(customerList.msg);
				}
			},
			
		});
	}

	function onProductFormSubmitHandlers(e) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#onProductHandlers").attr("action");
		var form = $("#onProductHandlers")[0];
		var data = new FormData(form);
		//(data);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {


					alert("Product Updated")
					document.getElementById('exampleModal08').click();

				} else {
					$("#productMsg").show();
					$("#productMsg").addClass("text");
					$("#productMsg").addClass("text-danger");
					$("#productMsg").html(customerList.msg);
				
				}
			},
			
		});
	}


</script>

<script>
	$(document).ready(function() {
		// var rowCount=0;
		document.body.style.zoom = 0.8;

		jQuery.noConflict();

		$('#invoiceNumber').focus();

		
	});



	function checkEmail() {
		var email = document.getElementById('emails').value;
		// //(email);
		if (email != '') {
			$.ajax({
				url: baseurl + "index.php/party/checkmail",
				method: "POST",
				data: {
					email: email,
				},
				success: function(products) {
					var contact = JSON.parse(products);
					//(contact);
					if (contact.status == false) {
						// spanTag1.innerHTML = '';
						// document.getElementById('contackcheck').style.display = 'none';
						document.getElementById('mailcheck').innerHTML =
							'<span class="text-success">Available</span>';
						document.getElementById('btn_login').disabled = false;
						//("true");
					} else {
						document.getElementById('mailcheck').innerHTML =
							'<span class="text-danger">Not Available</span>';
						// document.getElementById('contackcheck1').style.display = 'none';
						// document.getElementById('contackcheck').innerHTML = 'Not Available';
						document.getElementById('btn_login').disabled = true;
					}

				},
			});
		} else {
			document.getElementById('mailcheck').innerHTML = '<span class="text-danger">Not Valid</span>';
			// document.getElementById('contackcheck1').style.display = 'none';
			// document.getElementById('contackcheck').innerHTML = 'Not Available';
			document.getElementById('btn_login').disabled = true;

		}
	}

	function checkContacts() {
		var contacts = document.getElementById('contacts').value;
		if (contacts.length > 5) {
			//(contacts);
			$.ajax({
				url: baseurl + "index.php/party/checkcontacts",
				method: "POST",
				data: {
					contact: contacts,
				},
				success: function(products) {
					var contact = JSON.parse(products);
					//(contact);
					if (contact.status == false) {
						// spanTag1.innerHTML = '';
						// document.getElementById('contackcheck').style.display = 'none';
						document.getElementById('contackcheck').innerHTML =
							'<span class="text-success">Available</span>';
						document.getElementById('btn_login').disabled = false;
						//("true");
					} else {
						document.getElementById('contackcheck').innerHTML =
							'<span class="text-danger">Not Available</span>';
						// document.getElementById('contackcheck1').style.display = 'none';
						// document.getElementById('contackcheck').innerHTML = 'Not Available';
						document.getElementById('btn_login').disabled = true;
					}
				},
			});
		} else {
			document.getElementById('contackcheck').innerHTML = '<span class="text-danger">Not Vallid</span>';
		}
	}


	function getProducts() {
		$.ajax({
			url: baseurl + "index.php/product/getproducts",
			method: "GET",
			success: function(products) {
				var producshowPartyPopts = JSON.parse(products);
				if (products.status == true) {
					prepareProductsDropdown(products.data.product, products.data.productCode);

				}

			},
		
		});
	}

	function getOnlyKey(row) {
		var rowKey = row;
	}

	// function getProductCode(); {
	// 	$.ajax({
	// 		url: baseurl + "index.php/productCode/getproductsCode",
	// 		method: "GET",
	// 		success: function(products) {
	// 			var products = JSON.parse(products);
	// 			if (products.status == true) {
	// 				prepareProductsDropdown(products.data);
	// 				if ("" != null && "" !== "") {
	// 					$("#invoiceNumber").val("");
	// 					$("#invoiceNumber").trigger("change");
	// 				}
	// 				$("td .select2-container").css("width", "150px");
	// 				//("=====Prepare======");
	// 			}
	// 		},
	// 		error: function(jqXHR, exception) {
	// 			var msg = "";
	// 			if (jqXHR.status === 0) {
	// 				msg = "Not connect.\n Verify Network.";
	// 			} else if (jqXHR.status == 401) {
	// 				msg = "Unauthorized User. [" + jqXHR.status + "]";
	// 				window.location.href = "/gst/signout";
	// 			} else if (jqXHR.status == 403) {
	// 				msg = "Permissio n denied. [403]";
	// 			} else if (jqXHR.status == 404) {
	// 				msg = "Requested page not found. [404]";
	// 			} else if (jqXHR.status == 406) {
	// 				msg = jqXHR.responseText;
	// 			} else if (jqXHR.status == 500) {
	// 				msg = "Internal Server Error [500].";
	// 			} else if (exception === "parsererror") {
	// 				msg = "Requested JSON parse failed.";
	// 			} else if (exception === "timeout") {
	// 				msg = "Time out error.";
	// 			} else if (exception === "abort") {
	// 				msg = "Ajax request aborted.";
	// 			} else {
	// 				msg = "Uncaught Error.\n";
	// 				console.error(jqXHR.responseText);
	// 			}
	// 			Swal.fire({
	// 				position: "top-end",
	// 				icon: "error",
	// 				title: msg,
	// 				showConfirmButton: false,
	// 				timer: 2000,
	// 			});
	// 		},
	// 	});
	// }

	function ProductsGet(product_id, invoiceID) {
		//(product_id);
		$.ajax({
			method: "POST",
			data: {
				id: product_id,
				invoiceID: invoiceID,
			},
			url: baseurl + "index.php/product/proInfo",

			success: function(products) {
				var products = JSON.parse(products);
				if (products.status == true) {
					//(products.data);
					// let rowCount = $('#dataTable tbody tr').length + 1;

					prepareProducts(products.data);
				
				}
			},
			
		});
	}

	function print(value) {
		window.print(value);
	}
	

	function deleteRow() {
		var printValue = document.getElementById('printPurchase').value;
		var invoiceNumber = document.getElementById('invoiceNumber').value;

		if (printValue == 0) {
			Swal.fire({
				position: "top-end",
				icon: "error",
				title: "Enter Invoice First",
				showConfirmButton: false,
				timer: 2000,
			});
			// alert('ENter First Invocie Number That you Want to delete');
			return;
		}
		var url = "<?php echo base_url(); ?>index.php/purchase/temprary_delete/" + printValue
		//(url, "url");
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
						// window.location.reload();
						window.location.href = '<?= base_url(); ?>index.php/invent/purchase';
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





	$(document).ready(function() {
		var invoiceNumber = document.getElementById('invoiceNumber').value;
		document.getElementById('printPurchase').value = 0;
		//(invoiceNumber, "invocienumber");
		getInvoiceInfo(invoiceNumber);


	})


	// var x = '';
	var tr = 0;

	function trIncriment(tr, withdata) {
		return tr++;
	}




	var xml = 0;

	// function keyDown(e) {

	// 	if (e.keyCode == '40') {
	// 		var searchparty = document.getElementById('searchparty').value;
	// 		if (searchparty != '') {
	// 			filterWithSearh(e);
	// 		} else {
	// 			filterWithoutSearh(e);
	// 		}
	// 	}
	// }

	function filterWithSearh(e) {
		if (e.keyCode == '40') {

			var searchparty = document.getElementById('searchparty').value;
			console.log(searchparty, "searchparty");
			if (searchparty != '') {
				tr = xml;
				var elmtTable = document.getElementById('example12');
				var tableRows = elmtTable.getElementsByTagName('tr');
				var rowCount = tableRows.length;
				var start = document.getElementById('tr0');
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';

				console.log(tr, "tr");
				console.log(xml, "xmlll");
				console.log(rowCount, "rowCOUNTTT");
				xml++;
				// }
			}
		}
	}

	function filterWithoutSearh(e) {
		if (e.keyCode == '40') {

			var elmtTable = document.getElementById('example12');
			var tableRows = elmtTable.getElementsByTagName('tr');
			console.log(tableRows, "Tablerowssssss");
			var rowCount = tableRows.length;
			if (rowCount >= tr) {
				if (tr < 0) {
					tr = 0;
				}
				console.log(tr, "tr");
				var start = document.getElementById('tr' + tr);
				// var searchparty = document.getElementById('searchparty').value;
				console.log(start, "start");
				if (tr != 0) {
					var previous = start.previousSibling.previousElementSibling;
				}

				console.log(previous, "previous");

				if (previous != null) {
					previous.style.backgroundColor = 'white';
					previous.style.color = 'black';
					// }
				}

				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
				// alert("keydown")
				console.log(tr);
				tr++;
			}
		} else {
			document.getElementById('tr0').focus();

		}
	}



	function chnageDirections(event) {
		if (event.keyCode == 37) {
			// alert("left click for btnwithprint");
			var start = document.getElementById('btnSaveAndPrint');

			start.style.backgroundColor = 'white';
			start.style.color = 'black';
			document.getElementById('btnSave').focus();
			var start = document.getElementById('btnSave');
			start.style.backgroundColor = 'blue';
			return true;

			// console.log("okk")

		}
		if (event.keyCode == 39) {
			// alert("right click for btnwithprint");
			var start = document.getElementById('btnSaveAndPrint');

			start.style.backgroundColor = 'white';
			start.style.color = 'black';
			document.getElementById('btnSaveCancel').focus();
			var start = document.getElementById('btnSaveCancel');
			start.style.backgroundColor = 'blue';
			// start.style.border = '1 px red';
			return true;
		}
	}

	function chnageDirectionss(event) {
		if (event.keyCode == 37) {
			var start = document.getElementById('btnSaveCancel');
			start.style.backgroundColor = 'white';
			start.style.color = 'black';

			document.getElementById('btnSaveAndPrint').focus();
			var start = document.getElementById('btnSaveAndPrint');
			start.style.backgroundColor = 'blue';
			// console.log("okk")
			// alert("okokok")
			// return true;

		}
		if (event.keyCode == 39) {
			var start = document.getElementById('btnSaveAndPrint');

			start.style.backgroundColor = 'white';
			start.style.color = 'black';
			document.getElementById('btnSaveCancel').focus();
			var start = document.getElementById('btnSaveCancel');
			start.style.backgroundColor = 'blue';
			// start.style.border = '1 px red';
			return true;

		}
	}

	function enterKeysearchcity(event) {
		if (event.keyCode == 13) {
			var search = $('#searchcity').val();
			if (search.length != '') {
				$("tr[tabindex=0]").click();
				document.getElementById("customerMobile").focus();

				return;

			} else {
				var elmtTable = document.getElementById('example14');
				var tableRows = elmtTable.getElementsByTagName('tr');
				var rowCount = tableRows.length;
				console.log(tableRows.tr0, "trooo");
				// for (var x = 1; x > 0; x--) {
				tableRows.tr0.click();
				// if (rowCount)f
				document.getElementById("customerMobile").focus();

				// 	document.getElementById(tableRows + 'tr0').click();
				return;
				// $("#tr0").click();
				// }

			}

		}
	}




	// document.onkeydown = checkKey;

	// function checkKey(e) {
	// 	e = e || window.event;
	// 	if (e.keyCode == '38') {
	// 		alert("keydown")
	// 		// up arrow
	// 		var idx = start.cellIndex;
	// 		var nextrow = start.parentElement.previousElementSibling;
	// 		if (nextrow != null) {
	// 			var sibling = nextrow.cells[idx];
	// 			dotheneedful(sibling);
	// 		}
	// 	} else if (e.keyCode == '40') {
	// 		// down arrow
	// 		var idx = start.cellIndex;
	// 		var nextrow = start.parentElement.nextElementSibling;
	// 		if (nextrow != null) {
	// 			var sibling = nextrow.cells[idx];
	// 			dotheneedful(sibling);
	// 		}
	// 	} else if (e.keyCode == '37') {
	// 		// left arrow
	// 		var sibling = start.previousElementSibling;
	// 		dotheneedful(sibling);
	// 	} else if (e.keyCode == '39') {
	// 		// right arrow
	// 		var sibling = start.nextElementSibling;
	// 		dotheneedful(sibling);
	// 	}
	// }

	function enterKeysearchState(event) {
		if (event.keyCode == 13) {
			var search = $('#searchstate').val();
			if (search.length != '') {
				$("tr[tabindex=0]").click();
				// console.log("okk")
				return true;
			} else {
				// $('#searchparty').focus();
				document.getElementById("tr0").click();
				document.getElementById("searchstate").focus();
			}

		}
	}


	$('#statesearch').keyup(function() {
		// $('#consigneestatecode').val(code);
		var search = $('#statesearch').val();
		if (search.length != '') {
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/party/statesearch",
				data: {
					name: search,
				},
				success: function(customer) {
					var customer = JSON.parse(customer);
					if (customer.status == true) {
						//(customer, "details");
						var da = customer.data;
						myHtml = '';
						if (da.party) {
							$.each(da.party, function(i, item) {

								myHtml += "<tr tabindex=" + i + " id=" + i +
									" onclick='getStateSelect(" + item.id + ")'><td>" + item
									.state + "</td>" +
									"</tr>";
							});
							$("#statesearchdata").html(myHtml);
						} else {
							$('#statesearch').focus();

						}
					}
				}
			})

		} else {
			$('#searchcity').focus();
		}

	});

	$('#searchcity').keyup(function() {
		// $('#consigneestatecode').val(code);
		var search = $('#searchcity').val();
		if (search.length != '') {
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/party/searchcity",
				data: {
					name: search,
				},
				success: function(customer) {
					var customer = JSON.parse(customer);
					if (customer.status == true) {
						//(customer, "details");
						var da = customer.data;
						myHtml = '';
						if (da.party) {
							$.each(da.party, function(i, item) {

								myHtml += "<tr tabindex=" + i + " id=tr" + i +
									" onclick='getCitySelect(" + item.id + ")' ><td>" + item
									.name + "</td>" +
									"</tr>";
							});
							$("#searchcitydata").html(myHtml);
						} else {
							$('#searchcity').focus();

						}
					}
				}
			})

		} else {
			$('#searchcity').focus();
		}

	});



	$('#searchproduct').keyup(function() {
		// $('#consigneestatecode').val(code);
		var search = $('#searchproduct').val();
		if (search.length != '') {
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/product/searchproduct",
				data: {
					name: search,
				},
				success: function(customer) {
					// alert("searchparty");
					var customer = JSON.parse(customer);
					if (customer.status == true) {
						document.getElementById('producttbdoy').innerHTML = '';
						//(customer, "details");
						var da = customer.data;
						myHtml = '';
						if (da.party) {
							$.each(da.party, function(i, item) {
								getTaxDatas(item.tax, i);
								getUnitDatas(item.unit, i);

								$("#producttbdoy").append("<tr tabindex=" + -1 +
									" id=protr" + i +
									" onkeyup='keyUpForProduct(event," + i +
									")' onkeydown='keyDownForProduct(event," +
									i + ")'  onkeypress='onProductSelect(" +
									item.id + ")' onclick='onProductSelect(" +
									item.id + ")'><td>" + item.p_name +
									"</td>" +
									"<td>" + item.price + "</td>" +
									// "<td>" + item.mrp + "</td>" +
									"<td><a href='#' onmouseover='productProfileEdit(" +
									i + "," + item.id + ")'>Edit</a></td></tr>" +
									"<tr style='display: none;width:120%;' id=hiddenproduct" + i +
									"><td><div  id=errorpro" + i + " style='opacity: 500; display: none;'></div>" +

									"<div class='row'>" +

									"<form action='<?= base_url() ?>product/updateproduct' id=id_pata_nahi" + i + " method='post' onsubmit='onFormProductSubmitHandlers(event," + i + ")'>" +
									// "<h1>Product Update</h1>" +
									"<div class='row'>" +

									"<input type='hidden' name='id' value=" + item.id + " id=product_id" + i + ">" +

									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Name<span class='text-danger'>*</span></label>" +
									"																								<input autocomplete='off' type='text' name='name' id='name'  placeholder='Name' value='" + item.p_name + "' class='form-control' required>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>BARCODE<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='barcode' id='barcode'  placeholder='BARCODE' value='" + item.qrCode + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div id=protax" + i + " class='form-group col-md-3'></div>" +
									"<div id=unit" + i + " class='form-group col-md-3'></div>" +

									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>MRP<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='mrp' id='mrp'  placeholder='MRP' value='" + item.mrp + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Selling Price<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='price' id='price'  placeholder='Product Selling Price' value='" + item.price + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Purchase Price<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='price' id='price'  placeholder='Purchase Price' value='" + item.purchase_rate + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>QTY<span class='text-danger'>*</span></label>" +
									"																								<input autocomplete='off' type='text' name='qty' id='qty'  placeholder='Product Qunatity' value='" + item.qty + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Discount%<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='number' name='discount' id='discount'  placeholder='Product Discount' value='" + item.discount + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>HSN<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='hsn' id='hsn'  placeholder='HSN Code' value='" + item.hsn + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>MIN Qty<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='min_qty' id='min_qty'  placeholder='MIN Qty' value='" + item.min_qty + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>HSN<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='weight' id='weight'  placeholder='Weight' value='" + item.weight + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									// "<div class='form-group col-sm-3'>" +
									// "																								<label for='image'>Image<span class='text-danger'></span></label>" +
									// "																								<input autocomplete='off' type='file' name='image' id='image'  placeholder='Image' value='" + item.image + "' class='form-control'>" +
									// "																								" +
									// "" +
									// "																								</span>" +
									// "</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Short Description<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='short_desc' id='short_desc'  placeholder='SHort Description' value='" + item.short_de + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Long Description<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='long_desc' id='long_desc'  placeholder='Long Description' value='" + item.long_de + "' class='form-control'>" +
									"																								" +
									"" +
									"																								</span>" +
									"</div>" +
									"</div>" +
									"<button class='btn btn-success' type='submit'>Update</button>" +
									"<a href='#' id=proclick" + i + " onclick='productProfileEdit(" + i + "," + item.id + ")' class='btn btn-danger'>Close</button>" +
									"</form>" +
									"</td>" +
									"</tr>"

								)
							});

						} else {
							$('#searchproduct').focus();

						}
					}
				}
			})

		} else {
			$('#searchproduct').focus();
		}

	});






	function partyID(e) {
		// $("#party_id").change(function() {
		// //(e);
		var elmtTable = document.getElementById('dataTable');
		var tableRows = elmtTable.getElementsByTagName('tr');
		var rowCount = tableRows.length;
		// //(rowCount);
		for (var x = rowCount; x > 0; x--) {

			$("#tr" + x).remove();
		}
		getProducts();
		// getProductCode();


		var party_id = e.target.value;;
		// //(party_id);
		getCustomerDetails(party_id);
		// document.getElementById("exampleModalLong").click();

		// })
	};


	$("#invoiceNumber").change(function() {
		// //(e);
		// onRemoveRowHandler();
		// $("#tr" + 1).remove();
		var elmtTable = document.getElementById('dataTable');
		var tableRows = elmtTable.getElementsByTagName('tr');
		var rowCount = tableRows.length;
		// //(rowCount);
		for (var x = rowCount; x > 0; x--) {
			//    elmtTable.remove(tableRows[x]);
			// onRemoveRowHandler(tableRows[x])
			$("#tr" + x).remove();
			$("#tr" + x).remove();
			$("#tr" + x).remove();
			$("#tr" + x).remove();


		}

		var invoiceNumber = document.getElementById('invoiceNumber').value;
		document.getElementById('printPurchase').value = 0;


		//(invoiceNumber, "invocienumber");
		getInvoiceInfo(invoiceNumber);
	});


	const getInvoiceInfo = (InvoiceNumber) => {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/incoicepurchase",
			data: {
				id: InvoiceNumber,
			},
			success: function(customer) {
				// //(customer.status,"status");
				var idss = [];
				var customer = JSON.parse(customer);
				if (customer.status == true) {
					//(customer, "details");
					var da = customer.data;
					console.log(da);
					var product_details = da.invoice_product;
					//(product_details, "details");
					if (product_details != undefined) {
						var customerDetails = da.party;
						// idss = da.product_ids;
						// console.log(customerDetails);
						if (customerDetails.customerId > 0) {
							// getCustomerDetails(customerDetails.customerId);
							//(customerDetails.customerId, "customerId");
							// let rowCount = $('#dataTable tbody tr').length + 1;
							document.getElementById('printPurchase').value = customerDetails.id;
							$("#totalsgst").val(customerDetails != null ? customerDetails.totalsgst : 0);
							$("#totaligst").val(customerDetails != null ? customerDetails.totaligst : 0);
							$("#totalcgst").val(customerDetails != null ? customerDetails.totalcgst : 0);
							$("#totalTax").val(customerDetails != null ? customerDetails.totalTax : 0);
							$("#totalValue").val(customerDetails != null ? customerDetails.totalValue : 0);
							$("#total").val(customerDetails != null ? customerDetails.total : 0);
							$("#taxableAmount").val(customerDetails != null ? customerDetails
								.taxableAmount : 0);
							$("#amountInWords").val(customerDetails != null ? customerDetails
								.amountInWords : "");
							$("#gstNo").val(customerDetails != null ? customerDetails.gstNo : "");
							$("#taxcal").val(customerDetails != null ? customerDetails.taxCal : "");
							$("#gstType").val(customerDetails != null ? customerDetails.taxCode : "");
							$("#orderDate").val(customerDetails != null ? customerDetails.orderDate : "");
							$("#orderNumber").val(customerDetails != null ? customerDetails.orderNumber :
								"");
							$("#formats").val(customerDetails.format_invoice != null ? customerDetails.format_invoice : "Tax");
							$("#format_invoice").val(customerDetails != null ? customerDetails.format_invoice : "Tax");

							// $("#gstType").val(customerDetails != null ? customerDetails.gst : "");

							$("#voucherType").val(customerDetails != null ? customerDetails.voucherType :
								"");
							$("#customerAddress").val(
								customerDetails != null ? customerDetails.customerAddress : ""
							);

							$("#customerCity").val(customerDetails != null ? customerDetails.customerCity :
								"");
							getCityName(customerDetails.customerCity);
							getStateName(customerDetails.customerState);


							$("#customerMobile").val(customerDetails != null ? customerDetails
								.customerMobile : "");
							$("#customerEmail").val(customerDetails != null ? customerDetails
								.customerEmail : "");
							$("#customerBalance").val(customerDetails != null ? customerDetails
								.customerBalance : "");
							$("#stateSelect").val(customerDetails != null ? customerDetails.customerState :
								"");
							$("#party_id").val(customerDetails != null ? customerDetails.customerId : "");
							$("#partyname").val(customerDetails != null ? customerDetails.customerId : "");
							// alert(customerDetails.customerId);
							getPartyName(customerDetails.customerId);


						} else {
							var customer = customerDetails;
							// getCustomerInformation(customerDetails.customerId);
							// let rowCount = $('#dataTable tbody tr').length + 1;
							document.getElementById('nameCustomerKa').value = customerDetails.customerName;
							$("#party_id").val(customer != null ? customer.customerName : "");

							stateCode = customer.state_code ? customer.state_code : "";
							// getStateName(customer.state_code);
							getStateName(customer.state);
							// //(state_name);
							// $name=customer.state;
							sgstPer = 0;
							cgstPer = 0;
							igstPer = 0;
							$("#gstNo").val(customer != null ? customer.gstNo : "");
							$("#gstType").val(customer != null ? customer.gstType : "");
							$("#customerAddress").val(
								customer != null ? customer.address_1 : ""
							);
							$("#customerCity").val(customer != null ? customer.customerCity : "");
							getCityName(customerDetails.customerCity);
							$("#taxcal").val(customer != null ? customer.taxCal : "");

							$("#customerMobile").val(customer != null ? customer.customerMobile : "");
							$("#customerEmail").val(customer != null ? customer.customerEmail : "");
							$("#customerBalance").val(customer != null ? customer.opening_balance : "");
							$("#stateSelect").val(customer != null ? customer.state_code : "");

						}
						// var rowCount=0;
						for (i = 0; i < product_details.length; i++) {
							// product_details.p_code[i];
							// //(product_details)
							// ProductsGet(product_details[i].product_id,product_details[i].invoiceID);
							prepareProducts(product_details[i]);
						}
					}
				} else {
					// //(customer.status);
					getProducts();
					// getProductCode();

				}

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	const getCustomerDetails = (customerId) => {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/getCustomer",

			data: {
				id: customerId,
			},
			success: function(customer) {
				var customer = JSON.parse(customer);
				setSelectedCustomerDetails(customer.data);
			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};

	const getCustomerEdit = (customerId) => {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/getCustomer",

			data: {
				id: customerId,
			},
			success: function(customer) {
				var customer = JSON.parse(customer);
				setSelectedCustomer(customer.data);
			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};

	const getProducutEditable = (productid) => {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/product/productset",

			data: {
				id: productid,
			},
			success: function(customer) {
				var customer = JSON.parse(customer);
				setSelectedproduct(customer.data);
			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};

	const orBanoProductForm = (row, e) => {

	}
	const orBanaoForm = (row, e) => {

		var name = $("#name" + row).val();
		var partyId = $("#partyId" + row).val();
		var group = $("#group" + row).val();
		var pin = $("#pin" + row).val();
		var pan = $("#pan" + row).val();
		var contact = $("#contact" + row).val();
		var email = $("#email" + row).val();
		var address_1 = $("#address_1" + row).val();
		var state_code = $("#state_code" + row).val();
		var city = $("#city" + row).val();
		var state = $("#state" + row).val();
		var opening_balance = $("#opening_balance" + row).val();
		var gst = $("#gst" + row).val();
		var gstType = $("#gstType" + row).val();
		var credit_limit = $("#credit_limit" + row).val();
		var opBalnceType = $("#opBalnceType" + row).val();
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/updateparty",

			data: {
				id: partyId,
				contact: contact,
				name: name,
				group: group,
				pin: pin,
				pan: pan,
				email: email,
				address_1: address_1,
				state_code: state_code,
				city: city,
				state: state,
				opening_balance: opening_balance,
				gst: gst,
				gstType: gstType,
				credit_limit: credit_limit,
				opBalnceType: opBalnceType,

			},
			success: function(customer) {
				// console.log(customer);
				var customer = JSON.parse(customer);
				if (customer.status == true) {
					document.getElementById('clik' + row).click();
					// partyProfileEdit('hiddendata' + row,partyId,event);
					alert("Party Updated");
					document.getElementById('trp' + row).focus();

					var start = document.getElementById('trp' + row);
					//  = document.getElementById('trp' + rowId);
					console.log(start, "frmm")
					start.style.backgroundColor = 'blue';
					start.style.color = 'white';
					e.preventDefault();


					// getCustomerDetails(customer.data.id);
				} else {
					$("#partyMsg" + row).show();
					$("#partyMsg" + row).addClass("text");
					$("#partyMsg" + row).addClass("text-danger");
					$("#partyMsg" + row).html(customer.msg);
				}

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}


	const setSelectedCustomer = (customer) => {
		console.log(customer, "party details..............");
		// stateCode = customer.state_code ? customer.state_code : "";
		// getStateName(customer.state_code);
		$("#dontnameememe").val(customer != null ? customer.name : "");
		$("#dontgroup").val(customer != null ? customer.group_head : "");
		$("#dontnumber").val(customer != null ? customer.contact : "");
		$("#dontemail").val(customer != null ? customer.email : "");
		$("#dontpin").val(customer != null ? customer.pin : "");
		$("#dontcity").val(customer != null ? customer.city : "");
		$("#dontaddress_1").val(customer != null ? customer.address_1 : "");
		$("#dontstate").val(customer != null ? customer.state_code : "");
		$("#dontcode").val(customer != null ? customer.state_code : "");

		$("#dontopening_balance").val(customer != null ? customer.opening_balance : "");
		$("#dontopBalnceType").val(customer != null ? customer.opBalnceType : "");
		$("#dontcredit_limit").val(customer != null ? customer.credit_limit : "");
		$("#dontopBalnceType").val(customer != null ? customer.opBalnceType : "");
		$("#dontgstTypeParty").val(customer != null ? customer.gstType : "");
		$("#dont_party_id").val(customer != null ? customer.id : "");
		// document.getElementById("exampleModalLong").click();

	};

	const setSelectedproduct = (customer) => {
		console.log(customer, "product details..............");

		$("#dontpname").val(customer != null ? customer.p_name : "");
		$("#dontppcode").val(customer != null ? customer.qrCode : "");

		$("#dontpprice").val(customer != null ? customer.price : "");
		$("#dontpcost").val(customer != null ? customer.purchase_rate : "");

		$("#dontpmrp").val(customer != null ? customer.mrp : "");
		$("#dontpqty").val(customer != null ? customer.qty : "");
		$("#dontpdiscount").val(customer != null ? customer.discount : "");

		$("#dontphsn_code").val(customer != null ? customer.hsn : "");
		$("#dontpmin_qty").val(customer != null ? customer.min_qty : "");
		$("#dontpweight").val(customer != null ? customer.weight : "");
		$("#dontptax").val(customer != null ? customer.tax : "");
		$("#dontpunit").val(customer != null ? customer.unit : "");
		$("#dontpshortdescription").val(customer != null ? customer.short_de : "");
		$("#dontpdescription").val(customer != null ? customer.long_de : "");
		$("#prosId").val(customer != null ? customer.id : "");


		// document.getElementById("exampleModalLong").click();
	};




	const getPartyList = () => {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/getList",
			success: function(customer) {
				// if (customer.status == true) {
				// customer.parse
				var customers = JSON.parse(customer);
				$("#party_id").html(customers.data);
				// }

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};
	// 	function showAddCustomerDialoag() {
	//   $("#exampleModal .modal-dialog .modal-body").load(baseurl + "index.php/party/load_party");
	//   $("#exampleModalLabel").html("Add Party");
	//   $("#exampleModal").modal("show");
	//   $(".searchselect").select2({ dropdownParent: "#exampleModal" });
	// }
	const setSelectedCustomerDetails = (customer) => {
		stateCode = customer.state_code ? customer.state_code : "";
		getStateName(customer.state);
		// getStateName(customer.state_code);
		// //(state_name);
		// $name=customer.state;
		sgstPer = 0;
		cgstPer = 0;
		igstPer = 0;
		$("#gstNo").val(customer != null ? customer.gst : "");

		$("#gstType").val(customer != null ? customer.gstType : "");
		$("#customerAddress").val(
			customer != null ? customer.address_1 : ""
		);
		$("#customerCity").val(customer != null ? customer.city : "");
		getCityName(customer.city);

		$("#customerMobile").val(customer != null ? customer.contact : "");
		$("#customerEmail").val(customer != null ? customer.email : "");
		$("#customerBalance").val(customer != null ? customer.opening_balance : "");
		$("#stateSelect").val(customer != null ? customer.state_code : "");
		$("#party_id").val(customer != null ? customer.id : "");
		$("#partyname").val(customer != null ? customer.name : "");
		document.getElementById("exampleModalLong").click();
		<?php
		if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
		?>
			document.getElementById("p_code1").focus();
		<?php
		} else {
		?>
			document.getElementById("productname1").focus();

		<?php
		}
		?>

		// if (customer.gst) {
		// 	if (customer.gstType) {
		// 		if (customer.address_1) {
		// 			if (customer.city) {
		// 				if (customer.contact) {
		// 					if (customer.email) {
		// 						if (customer.opening_balance) {
		// 							if (customer.state_code) {
		// 								if (customer.opening_balance) {
		// 									document.getElementById("p_code1").focus();
		// 									// console.log(customer.stateCode)
		// 								} else {
		// 									document.getElementById("customerBalance").focus();
		// 									console.log(customer.stateCode)

		// 								}


		// 							} else {
		// 								document.getElementById("statename").focus();

		// 							}

		// 						} else {
		// 							document.getElementById("customerBalance").focus();

		// 						}

		// 					} else {

		// 						document.getElementById("customerEmail").focus();

		// 					}

		// 				} else {
		// 					document.getElementById("customerMobile").focus();

		// 				}

		// 			} else {
		// 				document.getElementById("customerCity").focus();

		// 			}

		// 		} else {
		// 			document.getElementById("customerAddress").focus();

		// 		}
		// 	} else {
		// 		document.getElementById("gstType").focus();

		// 	}
		// } else {
		// 	document.getElementById("gstNo").focus();

		// }




	};

	function showAddCustomerDialoag() {
		$("#exampleModal .modal-dialog .modal-body").load(baseurl + "index.php/party/load_party");
		$("#exampleModalLabel").html("Add Party");
		// $("#exampleModal").show();
		$('#exampleModal').modal('show');
		$(".searchselect").select2({
			dropdownParent: "#exampleModal"
		});
	}

	function getStateName(state_id) {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/state/stateNames/" + state_id,
			success: function(customer) {
				var product = JSON.parse(customer);
				console.log(product);
				if (product.status == true) {
					var product = product.data;
					console.log(product);
					document.getElementById('statename').value = product.party[0].state;
				} else {
					console.log("not find");
					document.getElementById('statename').value = "";

				}



			},
		})
	}

	function getStateNames() {
		var state_value = document.getElementById('statename').value;
		if (state_value != '') {
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/state/stateNamess/",
				data: {
					name: state_value,
				},
				success: function(customer) {
					var product = JSON.parse(customer);
					console.log(product);
					if (product.status == true) {
						var product = product.data;
						console.log(product);
						document.getElementById('stateSelect').value = product.party[0].id;
						document.getElementById('statename').value = product.party[0].state;

						// var state_value = document.getElementById('statename').value;

						// document.getElementById('statename').value = product.party[0].state;

					} else {
						console.log("not find");
						document.getElementById('stateSelect').value = "";

					}



				},
			})
		} else {
			document.getElementById('stateSelect').value = "";

		}
	}


	function changeCityNames() {

		var city_name = document.getElementById('cityCustomer').value;
		console.log(city_name);
		if (city_name != '') {

			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/district/citynames",
				data: {
					name: city_name,
				},
				success: function(customer) {
					var product = JSON.parse(customer);
					console.log(product);
					if (product.status == true) {
						var product = product.data;
						console.log(product);
						document.getElementById('customerCity').value = product.party[0].id;
						// document.getElementById('statename').value = product.party[0].state;

					} else {
						console.log("not find");
						document.getElementById('customerCity').value = "";

					}



				},
			})
		} else {
			document.getElementById('customerCity').value = "";

		}
	}

	function getPartyName(party_id) {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/partyname/" + party_id,
			success: function(customer) {
				var product = JSON.parse(customer);
				console.log(product);
				if (product.status == true) {
					var product = product.data;
					console.log(product);
					document.getElementById('partyname').value = product.party[0].name;
				} else {
					console.log("not find");
					document.getElementById('partyname').value = "";

				}



			},
		})
	}

	function showDDLBox() {
		$("#party_id").attr("disabled", false);
		$("#party_id").attr("required", true);
		$("#customerName").val("");
		$("#party_idDiv").show();
		$("#customerNameDiv").hide();
		$("#customerName").attr("disabled", true);
		$("#customerName").attr("required", false);
		$(".addPatys").show();
		$(".addText").show();
		$(".addDdl").hide();
	}

	function showTextBox() {
		$("#party_id").attr("disabled", true);
		$("#party_id").attr("required", false);
		$("#party_id").val("").trigger("change");
		$("#party_idDiv").hide();
		$("#customerNameDiv").show();
		$("#customerName").attr("disabled", false);
		$("#customerName").attr("required", true);
		$(".addPatys").hide();
		$(".addText").hide();
		$(".addDdl").show();
	}

	// var rowCount = 0;

	const prepareProductsDropdown = (products, productsCode) => {
		// ++rowCount;
		if (products != null && products != '' && typeof products !== "undefined") {
			productArray = products;
		}
		if (productsCode != null && productsCode != '' && typeof productsCode !== "undefined") {
			productArrays = productsCode;
		}
		//(products, "product First data");
		// $('.searchselect').select2();
		//(productsCode, "product Code data");
		let rowCount = $('#dataTable tbody tr').length + 1;
		// document.getElementById('prodid').value=rowCount;
		console.log(rowCount);
		// alert(rowCount);
		let productCodeOptions = '<option value="">PCode</option>';
		let productOptions = '<option value="">Select Product</option>';
		products.map(function(product) {
			productOptions += '<option value="' + product.id + '">' + product.p_name + '</option>';
		})
		productsCode.map(function(proCode) {
			productCodeOptions += '<option value="' + proCode.id + '">' + proCode.productcode + '</option>';
		})
		let unitOptions = '<option value="">Select Unit</option>';
		let readOnly = $("#invoiceType").val() === 2 ? 'readonly' : '';
		$("#dataTable tbody").append('<tr style="margin-bottom:-10px;margin-top:-10px;" id="tr' + rowCount + '" >\n' +
			'                                                        <td style="width:20px;padding:4px;">\n' +
			// '                                                          <div id="productcode' + rowCount + '"><select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" required="true">' + productCodeOptions + '</select></div>\n' +
			'                                                      ' + rowCount + '</td>\n' +
			<?php
			if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
			?> '<td style="width:120px;padding:4px;">\n' +
				'                                                          <input type="text" style="width:100%;border:none;" name="product[' +
				rowCount + '][p_code]"  tabindex="-1" id="p_code' + rowCount +
				'" class="form-control" onkeydown="keyDownForProductInfo(' + rowCount + ',event)" onchange="onProductCodeEnter(' + rowCount + ',event)" onkeypress="onProductCodeEnter(' + rowCount + ',event)">\n' +
				'                                                       </td>\n' +
			<?php
			} else {
			?> '<td hidden style="width:120px;padding:4px;">\n' +
				'                                                          <input type="text" style="width:100%;border:none;" name="product[' +
				rowCount + '][p_code]"  tabindex="-1" id="p_code' + rowCount +
				'" class="form-control" onkeydown="keyDownForProductInfo(' + rowCount + ',event)" onchange="onProductCodeEnter(' + rowCount + ',event)" onkeypress="onProductCodeEnter(' + rowCount + ',event)">\n' +
				'                                                       </td>\n' +
			<?php
			}
			?> '                                                        <td style="width: 195px !important;padding:4px;">\n' +
			'                                                           <div id="pro_names' + rowCount +
			'"><input type="text" style="width: 195px !important;border:none;" tabindex="-1"  onkeyup="showProductPop(' +
			rowCount + ',event)" name="product[' + rowCount + '][productname]" tabindex="17"  id="productname' +
			rowCount + '" class="form-control"  required="true"></div>\n' +
			'                                                       </div></td>\n' +
			'<td style="width:4px;padding:4px;"><input hidden type="text" readonly id="product_dec' + rowCount +
			'"  name="product[' + rowCount + '][product_dec]" data-toggle="modal" data-target="#desc' + rowCount +
			'" onclick="showPop(' + rowCount +
			',event)" value="" class="form-control"><i class="fas fa-edit" data-toggle="modal" data-target="#desc' +
			rowCount + '" onclick="showPop(' + rowCount + ',event)" title="Product Edit"></i></td>' +
			'                                                        <td style="width:30px;padding:4px;"><input style="width:45px;border:none;" type="text" tabindex="-1" id="hsnCode' +
			rowCount + '" name="product[' + rowCount + '][hsn]"  class="form-control" /></td>\n' +
			'                                                         <td style="width:125px;padding:4px;"><input  type="number" style="border:none;" min="1" required="true" tabindex="-1" onchange="onQuantityChangeHandler(event,' +
			rowCount + ');" showSerial(event,' + rowCount + ');" onkeypress="return EnterPress(event,' + rowCount +
			');" onkeyup="onQuantityChangeHandler(event,' + rowCount + ')" id="qty' + rowCount +
			'" name="product[' + rowCount + '][qty]" class="form-control"/></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input  type="text" style="border:none;" min="0" value="0" id="rate' +
			rowCount + '" tabindex="-1" name="product[' + rowCount +
			'][rate]"  required="true" onchange="onRateChangeHandler(event,' + rowCount +
			')" onkeyup="onRateChangeHandler(event,' + rowCount + ')"  class="form-control floatNumber"/></td>\n' +
			'                                                        <td style="width:30px;padding:4px;"><input  type="text" style="border:none;" min="0" value="0" id="discount' +
			rowCount + '" tabindex="-1" name="product[' + rowCount +
			'][discount]"   onchange="onDiscountHandler(event,' + rowCount +
			')" onkeyup="onDiscountHandler(event,' + rowCount +
			')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +
			'                                                        <td style="display:none;"><input  type="text" min="0" style="border:none;" value="0" id="cd' +
			rowCount + '" name="product[' + rowCount + '][cd]"  onchange="onCDHandler(event,' + rowCount +
			')" onkeyup="onCDHandler(event,' + rowCount +
			')" class="form-control floatNumber" style="width:70px;float:left;"/></td>\n' +
			'                                                        <td style="width:81px;padding:4px;"><div id="taxal' +
			rowCount + '"><input style="width:65px;border:none;"  type="number" value="0" min="0" id="tax' +
			rowCount + '" name="product[' + rowCount + '][tax]" class="form-control"/></div></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input style="border:none;"  type="text" readonly name="product[' +
			rowCount + '][price]" id="price' + rowCount + '" value="0" onkeyup="onPriceChangeHandler(event,' +
			rowCount + ')" required="true" class="form-control bg-light" ></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input style="border:none;" type="text" min="0" value="0" readonly id="amount' +
			rowCount + '" name="product[' + rowCount + '][amount]" class="form-control bg-light"/>' +
			'                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="taxAmount' +
			rowCount + '" name="product[' + rowCount + '][taxAmount]" class="form-control bg-light"/>' +
			'                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="sgst' +
			rowCount + '" name="product[' + rowCount + '][sgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="cgst' +
			rowCount + '" name="product[' + rowCount + '][cgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="igst' +
			rowCount + '" name="product[' + rowCount + '][igst]" class="form-control"/></td>\n' +
			'                                                        <td style="width:140px;padding:4px;"><input style="border:none;"  type="number" min="0" value="0" readonly id="netAmount' +
			rowCount + '" tabindex="' + (rowCount + parseInt(26)) + '" name="product[' + rowCount +
			'][netAmount]" class="form-control bg-light add-new-row" onkeydown="onNewRowAdd(event)"/></td>\n' +
			'                                                        <td style="padding:4px;width:84px;">' +
			'<a href="#" style="width:1px;height:1px;" tabindex="' + (rowCount + parseInt(27)) +
			'" onclick="onAddNewRowHandler()"><i class="fa fa-plus-circle text-success cp" title="Add new row">' +
			'</i></a>&nbsp;&nbsp;<a style="width:1px;height:1px;" href="#" onclick="onRemoveRowHandler(' +
			rowCount + ')"><i class="fa fa-minus-circle text-danger cp" title="Remove selected row"></i></a>' +
			'<span id="editProduct' + rowCount + '"></span>' +
			'</td>\n' +
			'                                                    </tr>');
		// $(".searchselect").select2()
		// $(".searchselect").select2("destroy").select2();
		// reinitialize();
	}

	const showPop = (rowNumber, e) => {
		jQuery.noConflict();
		var des = document.getElementById('product_dec' + rowNumber);
		if (des != null) {
			des = des.value;
		} else {
			des = "";
		}
		document.getElementById('description_for_id').value = rowNumber;
		document.getElementById('description_for').value = des;
		$('#mynewmodal').modal('show');
	}

	const showProductPop = (rowNumber, e) => {
		jQuery.noConflict();
		var party_id = document.getElementById("party_id").value;
		// alert(party_id);
		if (party_id > 0) {
			var des = document.getElementById('prorow' + rowNumber);
			if (des != null) {
				des = des.value;
			} else {
				des = "";
			}
			document.getElementById('prorow').value = rowNumber;
			document.getElementById('provalue').value = des;
			// $('#exampleModalLong1').modal('show');
			// document.getElementById('rownumber').value = rowNumber;
			$("#exampleModalLong1 .modal-dialog .modal-body").load(baseurl + "index.php/product/getallproduct");
			$("#exampleModalLongTitle").html("Select Product");
			$("#exampleModalLong1").modal("show");
		} else {
			// onAddNewRowHandler();
			// showPartyPop(e);

			// $('#exampleModalLong').modal('show');

			document.getElementById('partyname').focus();
		}

	}

	// const showPartyPop = (e) => {
	// 	jQuery.noConflict();
	// 	$('#exampleModalLong').modal('show');
	// }


	// const citymodel = (e) => {
	// 	jQuery.noConflict();
	// 	$('#exampleModalLong2').modal('show');
	// }



	// 	$('#partyname').on('mouseover', function() {
	// 		// $('#exampleModalLong').on('shown.bs.modal', function() {
	// 		$('#exampleModalLong').modal('show');
	// 		// $('#searchparty').trigger('focus');
	// 		$(this).find('[autofocus]').focus();
	// 	});
	// 	// })

	// });






	const prepareProducts = (products) => {
		var type = "Purchase";
		if (products != null && products != '' && typeof products !== "undefined") {
			productArray = products;
		}
		// ++rowCount;

		//(productArray, "products data 2nd");
		let rowCount = $('#dataTable tbody tr').length + 1;
		// alert(rowCount);
		// let productOptions = '<option value="">Select Product</option>';
		getProductName(products.product_id, rowCount);

		// getProductCodeDetails(products.p_code, rowCount);

		// products.map(function(product) {
		// productOptions += '<option value="' + products.product_id + '">' + products.product_id + '</option>';
		// });

		let unitOptions = '<option value="">Select Unit</option>';

		let readOnly = $("#invoiceType").val() === 2 ? 'readonly' : '';
		<?php $barcode = $this->session->userdata('barcode'); ?>
		$("#dataTable tbody").append(' <tr id="tr' + rowCount + '">\n' +
			'                                                        <td style="width:20px;padding:4px;">\n' +
			// '                                                          <div id="productcode' + rowCount + '"><select style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' + rowCount + '" class="searchselect form-control" onchange="onProductCodeSelect(' + rowCount + ',event)" required="true">' + productCodeOptions + '</select></div>\n' +
			'                                                      ' + rowCount + '</td>\n' +

			<?php
			if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
			?> '<td style="width:120px;padding:4px;">\n' +
				'                                                          <input style="border:none;" type="text" value="' +
				products.p_code + '" style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' +
				rowCount + '" onkeydown="keyDownForProductInfo(' + rowCount + ',event)" onkeydown="keyDownForProductInfo(' + rowCount + ',event)"  onchange="onProductCodeEnter(' + rowCount + ',event)" onkeypress="onProductCodeEnter(' + rowCount + ',event)" class="form-control">\n' +
				'                                                       </td>\n' +
			<?php
			} else {
			?> '<td hidden style="width:120px;padding:4px;">\n' +
				'                                                          <input style="border:none;" type="text" value="' +
				products.p_code + '" style="width:100%;" name="product[' + rowCount + '][p_code]"  id="p_code' +
				rowCount + '" onkeydown="keyDownForProductInfo(' + rowCount + ',event)" onkeydown="keyDownForProductInfo(' + rowCount + ',event)"  onchange="onProductCodeEnter(' + rowCount + ',event)" onkeypress="onProductCodeEnter(' + rowCount + ',event)" class="form-control">\n' +
				'                                                       </td>\n' +
			<?php
			}
			?> '                                                        <td style="width: 150px !important;padding:4px;">\n' +
			'                                                           <div id="pro_names' + rowCount +
			'"></div>' +
			'<td style="width:4px;padding:4px;">' +
			'<input type="text" readonly hidden id="product_dec' + rowCount + '" name="product[' + rowCount +
			'][product_dec]" data-toggle="modal" data-target="#desc' + rowCount + '" onclick="showPop(' + rowCount +
			',event)" value="' + products.product_dec + '" class="form-control">' +
			'<i class="fas fa-edit" data-toggle="modal" data-target="#desc' + rowCount + '" onclick="showPop(' +
			rowCount + ',event)" title="Product Edit"></i></td>' +
			'                                                        <td style="width:30px;padding:4px;"><input style="border:none;" type="text" id="hsnCode' +
			rowCount + '" name="product[' + rowCount + '][hsn]" value="' + products.hsn +
			'" class="form-control" / style="width:45px;"></td>\n' +
			'                                                         <td style="width:125px;padding:4px;"><input style="border:none;"  type="number" min="1" value="' +
			products.qty + '" required="true" onchange="onQuantityChangeHandler(event,' + rowCount +
			');" showSerial(event,' + rowCount + ');" onkeypress="return EnterPress(event,' + rowCount +
			');" onkeyup="onQuantityChangeHandler(event,' + rowCount + ')" id="qty' + rowCount +
			'" name="product[' + rowCount + '][qty]" class="form-control"/></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input style="border:none;"  type="text" min="0" value="' +
			products.rate + '" id="rate' + rowCount + '" name="product[' + rowCount +
			'][rate]" required="true" onchange="onRateChangeHandler(event,' + rowCount +
			')" onkeyup="onRateChangeHandler(event,' + rowCount + ')"  class="form-control floatNumber"/></td>\n' +
			'                                                        <td style="width:30px;padding:4px;"><input style="border:none;" type="text" min="0" value="' +
			products.discount + '" id="discount' + rowCount + '" name="product[' + rowCount +
			'][discount]"  onchange="onDiscountHandler(event,' + rowCount + ')" onkeyup="onDiscountHandler(event,' +
			rowCount + ')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +
			'                                                        <td style="display:none;"><input  type="text" style="border:none;" min="0" value="0" id="cd' +
			rowCount + '" name="product[' + rowCount + '][cd]"  onchange="onCDHandler(event,' + rowCount +
			')" onkeyup="onCDHandler(event,' + rowCount +
			')" class="form-control floatNumber" style="width:70px;float:left;"/></td>\n' +
			'                                                        <td style="width:81px;padding:4px;"><div id="taxal' +
			rowCount + '"><input style="border:none;" style="width:66px;" type="number" value="' + products.tax +
			'" min="0"  id="tax' + rowCount + '"  name="product[' + rowCount +
			'][tax]" class="form-control"/></div></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input style="border:none;" type="text" value="' +
			products.purchase_price + '" readonly name="product[' + rowCount + '][price]" id="price' + rowCount +
			'" onkeyup="onPriceChangeHandler(event,' + rowCount +
			')" required="true" class="form-control bg-light" ></td>\n' +
			'                                                        <td style="width:125px;padding:4px;"><input style="border:none;"  type="text" min="0" value="' +
			products.amount + '" readonly id="amount' + rowCount + '" name="product[' + rowCount +
			'][amount]" class="form-control bg-light"/>' +
			'                                                        <input  type="hidden" min="0" value="' +
			products.taxAmount + '" readonly id="taxAmount' + rowCount + '" name="product[' + rowCount +
			'][taxAmount]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="' +
			products.sgst + '" readonly id="sgst' + rowCount + '" name="product[' + rowCount +
			'][sgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="' +
			products.cgst + '" readonly id="cgst' + rowCount + '" name="product[' + rowCount +
			'][cgst]" class="form-control"/>' +
			'                                                        <input  type="hidden" min="0" value="' +
			products.igst + '" readonly id="igst' + rowCount + '" name="product[' + rowCount +
			'][igst]" class="form-control"/></td>\n' +
			'                                                        <td style="width:140px;padding:4px;"><input  type="number" style="border:none;" min="0" value="' +
			products.netAmount + '" readonly id="netAmount' + rowCount + '" name="product[' + rowCount +
			'][netAmount]" class="form-control bg-light add-new-row" onkeydown="onNewRowAdd(event)"/></td>\n' +
			'                                                        <td style="padding:4px;width:84px;">' +
			'<a href="#" onclick="onAddNewRowHandler()"><i class="fa fa-plus-circle text-success cp" title="Add new row">' +
			'</i></a>&nbsp;&nbsp;<a href="#" onclick="onRemoveRowHandler(' + rowCount +
			')"><i class="fa fa-minus-circle text-danger cp" title="Remove selected row"></i></a>' +
			'<span id="editProduct' + rowCount + '"><a href="#" onclick="productForEdit(' + rowCount + ',' +
			products.product_id +
			')"><i class="fas fa-edit" style="width:41px;" title="Product Edit"></i></a></span>' +
			'</td>\n' +
			'                                                    </tr>');

	}





	const getStateSelect = (id, type = "Purchase") => {
		const stateid = id;
		// alert(cityid);
		// alert(name);

		if (stateid != null) {
			getStateName(stateid);

			document.getElementById('stateSelect').value = stateid;
			document.getElementById('exampleModalLong3').click();
			document.getElementById("customerBalance").focus()

			// alert(cityid)
		} else {
			// alert("hello");
			document.getElementById('customerCity').value = "";
			document.getElementById('cityCustomer').value = "";
			document.getElementById("customerBalance").focus()


		}
	}






	const opBalnceType = (id, i) => {

		$.ajax({
			method: "GET",
			url: baseurl + "index.php/party/opBalnceType/" + id,
			success: function(product) {
				// var product = JSON.parse(product);
				// console.log(product, "state_info");
				document.getElementById('opBalnceType' + i).innerHTML = product;
				// if (product.status == true) {

				// ("#state_select").html(product);
				// }

			}
		})
	}






	const getCitySelect = (id, type = "Purchase") => {
		const cityid = id;
		// alert(cityid);
		// alert(name);

		if (cityid != null) {
			getCityName(cityid);
			document.getElementById('customerCity').value = cityid;
			document.getElementById('exampleModalLong2').click();
			document.getElementById("customerMobile").focus()

			alert(cityid)
		} else {
			alert("hello");
			document.getElementById('exampleModalLong2').click();
			document.getElementById('customerCity').value = "";
			document.getElementById('cityCustomer').value = "";
			document.getElementById("customerMobile").focus()


		}
	}

	const getCityName = (id) => {

		$.ajax({
			method: "GET",
			url: baseurl + "index.php/product/getName/" + id,
			success: function(product) {
				var product = JSON.parse(product);
				console.log(product.data);
				if (product.status == true) {

					var product = product.data;
					console.log(product);
					document.getElementById('cityCustomer').value = product.name;

				} else {
					console.log("not find");
					document.getElementById('cityCustomer').value = "";

				}

			}
		})
	}


	// const getCitySelect = (id, type = "Purchase") => {
	// 	const cityid = id;
	// 	if (cityid != null) {
	// 		// $('#editProduct' + rowNo).html('<a href="#" onclick="productForEdit()"><i class="fas fa-edit" title="Product Edit"></i></a>');
	// 		$.ajax({
	// 			method: "GET",
	// 			url: baseurl + "index.php/district/getCity/" + cityid,
	// 			success: function(product) {
	// 				if (product.status == 1) {
	// 					var product = JSON.parse(product);
	// 					var product = product.data;
	// 					document.getElementById('exampleModalLong2').click();
	// 				} else {

	// 				}

	// 			},
	// 			error: function(jqXHR, exception) {
	// 				var msg = "";
	// 				if (jqXHR.status === 0) {
	// 					msg = "Not connect.\n Verify Network.";
	// 				} else if (jqXHR.status == 401) {
	// 					msg = "Unauthorized User. [" + jqXHR.status + "]";
	// 					window.location.href = "/gst/signout";
	// 				} else if (jqXHR.status == 403) {
	// 					msg = "Permission denied. [403]";
	// 				} else if (jqXHR.status == 404) {
	// 					msg = "Requested page not found. [404]";
	// 				} else if (jqXHR.status == 406) {
	// 					msg = jqXHR.responseText;
	// 				} else if (jqXHR.status == 500) {
	// 					msg = "Internal Server Error [500].";
	// 				} else if (exception === "parsererror") {
	// 					msg = "Requested JSON parse failed.";
	// 				} else if (exception === "timeout") {
	// 					msg = "Time out error.";
	// 				} else if (exception === "abort") {
	// 					msg = "Ajax request aborted.";
	// 				} else {
	// 					msg = "Uncaught Error.\n";
	// 					console.error(jqXHR.responseText);
	// 				}
	// 				Swal.fire({
	// 					position: "top-end",
	// 					icon: "error",
	// 					title: msg,
	// 					showConfirmButton: false,
	// 					timer: 2000,
	// 				});

	// 			},
	// 		});
	// 	} else {

	// 	}
	// };


	jQuery(document).bind("keyup keydown", function(e) {
		if (e.ctrlKey && e.keyCode == 80) {
			printPurchase();

			return false;
		}
	});

	jQuery(document).bind("keyup keydown", function(e) {
		var x = event.which || event.keyCode;
		if (e.ctrlKey && e.keyCode == 100) {
			// printPurchase();
			deleteRow();


			return false;
		}
	});

	// $(window).keypress(function(event) {
	// 	if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19));
	// 	alert("Ctrl-S pressed");
	// 	// event.preventDefault();
	// 	return false;
	// 	saveInvoice(1);

	// });

	document.addEventListener("keydown", function(e) {
		if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
			e.preventDefault();
			// saveInvoice(1);
			onFormSubmitHandler(event)
			// document.getElementById('btn_login').click();

			// Process the event here (such as click on submit button)
		}
	}, false);

	const onProductCodeEnter = (rowNo, e, type = 'Sale') => {
		if (e.keyCode == 13) {
			// alert("hello");
			e.preventDefault();
			var productId = e.target.value;
			if (productId == "" || productId == null) {
				// productId=0;
				document.getElementById('productname' + rowNo).focus();
				return;
				// onRemoveRowHandler(rowNo);
				// // alert(productId);
				// return;

			}
			// alert(productId);
			// $(this).show('true');
			// $('#product_dec' + rowNo).html('<textarea class="form-control" name="product[' + rowNo + '][product_dec]" style="resize:none;height:40px;" placeholder="Enter Description"></textarea>');
			$.ajax({
				method: "GET",
				url: baseurl + "index.php/productCode/getProductCode/" + productId,
				success: function(product) {
					var product = JSON.parse(product);
					//(product, "pro info amana")
					if (product.status == true) {
						var product = product.data;
						setSelectedProductDetails(rowNo, product, type);
						onAddNewRowHandler();
						document.getElementById("p_code" + rowNo).disabled = true;
						var firstrow = rowNo;
						rowNo = rowNo + 1;
						console.log(rowNo, "roeNoonkljhhhjh")
						setTimeout(function() {
							// document.getElementById('exampleModalLong1').click();
							document.getElementById('p_code' + firstrow).disabled = false;
							document.getElementById('p_code' + rowNo).focus();
							console.log("dfghjkl")
						}, 1000);


					} else {
						// alert("product");

						// onRemoveRowHandler(rowNo);
						document.getElementById('p_code' + rowNo).value = '';
						document.getElementById('qty' + rowNo).value = 1;
						document.getElementById('rate' + rowNo).value = 0;
						document.getElementById('netAmount' + rowNo).value = 0;
						document.getElementById('price' + rowNo).value = 0;
						document.getElementById('amount' + rowNo).value = 0;
						setTimeout(function() {
							document.getElementById('productId' + rowNo).value = '';

						}, 1000);
						document.getElementById('productname' + rowNo).value = '';
						document.getElementById('productname' + rowNo).focus();
						// document.getElementById('editProduct' + rowNo).innerHTML = hidden;
						// manipulateRHSData();
					}
				},
				error: function(jqXHR, exception) {
					var msg = "";
					if (jqXHR.status === 0) {
						msg = "Not connect.\n Verify Network.";
					} else if (jqXHR.status == 401) {
						msg = "Unauthorized User. [" + jqXHR.status + "]";
						window.location.href = "/gst/signout";
					} else if (jqXHR.status == 403) {
						msg = "Permission denied. [403]";
					} else if (jqXHR.status == 404) {
						msg = "Requested page not found. [404]";
					} else if (jqXHR.status == 406) {
						msg = jqXHR.responseText;
					} else if (jqXHR.status == 500) {
						msg = "Internal Server Error [500].";
					} else if (exception === "parsererror") {
						msg = "Requested JSON parse failed.";
					} else if (exception === "timeout") {
						msg = "Time out error.";
					} else if (exception === "abort") {
						msg = "Ajax request aborted.";
					} else {
						msg = "Uncaught Error.\n";
						console.error(jqXHR.responseText);
					}
					Swal.fire({
						position: "top-end",
						icon: "error",
						title: msg,
						showConfirmButton: false,
						timer: 2000,
					});
				},
			});
		}
		// else {
		// 	// const onProductCodeSelect = (rowNo, e, type = "Purchase") => {
		// 		var productId = e.target.value;
		// 		if (productId == "" || productId == null) {
		// 			// productId=0;
		// 			onRemoveRowHandler(rowNo);
		// 			// alert(productId);
		// 			return;

		// 		}
		// 		// alert(productId);
		// 		// $(this).show('true');
		// 		// $('#product_dec' + rowNo).html('<textarea class="form-control" name="product[' + rowNo + '][product_dec]" style="resize:none;height:40px;" placeholder="Enter Description"></textarea>');
		// 		$.ajax({
		// 			method: "GET",
		// 			url: baseurl + "index.php/productCode/getProductCode/" + productId,
		// 			success: function(product) {
		// 				var product = JSON.parse(product);
		// 				//(product, "pro info amana")
		// 				if (product.status == true) {
		// 					var product = product.data;
		// 					setSelectedProductDetails(rowNo, product, type);
		// 					onAddNewRowHandler();
		// 					document.getElementById("p_code" + rowNo).disabled = true;
		// 					var firstrow = rowNo;
		// 					rowNo = rowNo + 1;
		// 					console.log(rowNo, "roeNoonkljhhhjh")
		// 					setTimeout(function() {
		// 						// document.getElementById('exampleModalLong1').click();
		// 						document.getElementById('p_code' + firstrow).disabled = false;
		// 						document.getElementById('p_code' + rowNo).focus();
		// 						console.log("dfghjkl")
		// 					}, 1000);


		// 				} else {
		// 					// alert("product");

		// 					// onRemoveRowHandler(rowNo);
		// 					document.getElementById('p_code' + rowNo).value = '';
		// 					document.getElementById('qty' + rowNo).value = 1;
		// 					document.getElementById('rate' + rowNo).value = 0;
		// 					document.getElementById('netAmount' + rowNo).value = 0;
		// 					document.getElementById('price' + rowNo).value = 0;
		// 					document.getElementById('amount' + rowNo).value = 0;
		// 					document.getElementById('productId' + rowNo).value = '';
		// 					document.getElementById('productname' + rowNo).value = '';
		// 					document.getElementById('productname' + rowNo).focus();
		// 					// document.getElementById('editProduct' + rowNo).innerHTML = hidden;


		// 					manipulateRHSData();




		// 				}

		// 				// $('#product' + rowNo).on('select2:select', function(e) {
		// 				//     document.getElementById("cost" + rowNo).focus()
		// 				// });
		// 			},
		// 			error: function(jqXHR, exception) {
		// 				var msg = "";
		// 				if (jqXHR.status === 0) {
		// 					msg = "Not connect.\n Verify Network.";
		// 				} else if (jqXHR.status == 401) {
		// 					msg = "Unauthorized User. [" + jqXHR.status + "]";
		// 					window.location.href = "/gst/signout";
		// 				} else if (jqXHR.status == 403) {
		// 					msg = "Permission denied. [403]";
		// 				} else if (jqXHR.status == 404) {
		// 					msg = "Requested page not found. [404]";
		// 				} else if (jqXHR.status == 406) {
		// 					msg = jqXHR.responseText;
		// 				} else if (jqXHR.status == 500) {
		// 					msg = "Internal Server Error [500].";
		// 				} else if (exception === "parsererror") {
		// 					msg = "Requested JSON parse failed.";
		// 				} else if (exception === "timeout") {
		// 					msg = "Time out error.";
		// 				} else if (exception === "abort") {
		// 					msg = "Ajax request aborted.";
		// 				} else {
		// 					msg = "Uncaught Error.\n";
		// 					console.error(jqXHR.responseText);
		// 				}
		// 				Swal.fire({
		// 					position: "top-end",
		// 					icon: "error",
		// 					title: msg,
		// 					showConfirmButton: false,
		// 					timer: 2000,
		// 				});
		// 			},
		// 		});
		// 	};
		// // }
	}




	// const onProductCodeSelect = (rowNo, e, type = "Purchase") => {
	// 	var productId = e.target.value;
	// 	if (productId == "" || productId == null) {
	// 		// productId=0;
	// 		onRemoveRowHandler(rowNo);
	// 		// alert(productId);
	// 		return;

	// 	}
	// 	// alert(productId);
	// 	// $(this).show('true');
	// 	// $('#product_dec' + rowNo).html('<textarea class="form-control" name="product[' + rowNo + '][product_dec]" style="resize:none;height:40px;" placeholder="Enter Description"></textarea>');
	// 	$.ajax({
	// 		method: "GET",
	// 		url: baseurl + "index.php/productCode/getProductCode/" + productId,
	// 		success: function(product) {
	// 			var product = JSON.parse(product);
	// 			//(product, "pro info amana")
	// 			if (product.status == true) {
	// 				var product = product.data;
	// 				setSelectedProductDetails(rowNo, product, type);
	// 				onAddNewRowHandler();
	// 				document.getElementById("p_code" + rowNo).disabled = true;
	// 				var firstrow = rowNo;
	// 				rowNo = rowNo + 1;
	// 				console.log(rowNo, "roeNoonkljhhhjh")
	// 				setTimeout(function() {
	// 					// document.getElementById('exampleModalLong1').click();
	// 					document.getElementById('p_code' + firstrow).disabled = false;
	// 					document.getElementById('p_code' + rowNo).focus();
	// 					console.log("dfghjkl")
	// 				}, 1000);


	// 			} else {
	// 				// alert("product");

	// 				// onRemoveRowHandler(rowNo);
	// 				document.getElementById('p_code' + rowNo).value = '';
	// 				document.getElementById('qty' + rowNo).value = 1;
	// 				document.getElementById('rate' + rowNo).value = 0;
	// 				document.getElementById('netAmount' + rowNo).value = 0;
	// 				document.getElementById('price' + rowNo).value = 0;
	// 				document.getElementById('amount' + rowNo).value = 0;
	// 				document.getElementById('productId' + rowNo).value = '';
	// 				document.getElementById('productname' + rowNo).value = '';
	// 				document.getElementById('productname' + rowNo).focus();
	// 				// document.getElementById('editProduct' + rowNo).innerHTML = hidden;


	// 				manipulateRHSData();




	// 			}

	// 			// $('#product' + rowNo).on('select2:select', function(e) {
	// 			//     document.getElementById("cost" + rowNo).focus()
	// 			// });
	// 		},
	// 		error: function(jqXHR, exception) {
	// 			var msg = "";
	// 			if (jqXHR.status === 0) {
	// 				msg = "Not connect.\n Verify Network.";
	// 			} else if (jqXHR.status == 401) {
	// 				msg = "Unauthorized User. [" + jqXHR.status + "]";
	// 				window.location.href = "/gst/signout";
	// 			} else if (jqXHR.status == 403) {
	// 				msg = "Permission denied. [403]";
	// 			} else if (jqXHR.status == 404) {
	// 				msg = "Requested page not found. [404]";
	// 			} else if (jqXHR.status == 406) {
	// 				msg = jqXHR.responseText;
	// 			} else if (jqXHR.status == 500) {
	// 				msg = "Internal Server Error [500].";
	// 			} else if (exception === "parsererror") {
	// 				msg = "Requested JSON parse failed.";
	// 			} else if (exception === "timeout") {
	// 				msg = "Time out error.";
	// 			} else if (exception === "abort") {
	// 				msg = "Ajax request aborted.";
	// 			} else {
	// 				msg = "Uncaught Error.\n";
	// 				console.error(jqXHR.responseText);
	// 			}
	// 			Swal.fire({
	// 				position: "top-end",
	// 				icon: "error",
	// 				title: msg,
	// 				showConfirmButton: false,
	// 				timer: 2000,
	// 			});
	// 		},
	// 	});
	// };

	const getDescriptionOnly = () => {
		// var row = document.getElementById("tr" + rowNo);
		// $('#list').on('keyup', 'tr:last td:nth-last-child(2)', function (event));
		var rowNumber = document.getElementById('description_for_id');
		if (rowNumber != null) {
			rowNumber = rowNumber.value;
		} else {
			rowNumber = "";
		}
		var contents = document.getElementById('description_for');
		// alert(contents.value);
		if (contents != null) {
			contents = contents.value;
		} else {
			contents = "";
		}

		document.getElementById('product_dec' + rowNumber).value = contents;


	}



	const discounttypeHandler = (e, rowNumber) => {
		discount_type = e.target.value;
		$("#discount" + rowNumber).trigger("change");
	};



	const setSelectedProductDetails = (rowNo, product, type) => {
		if (formats == 'Estimate') {
			$("#tax" + rowNo).val(0);
			var tax = 0;
			var tax1 = 0;
			// document.getElementById("tax" + rowNo).disabled = true;
			$("#tax" + rowNo).prop("disabled", true);

		} else {
			var tax = product.tax_qty;
			var tax1 = product.tax;
			// document.getElementById("tax" + rowNo).disabled = false;
			$("#tax" + rowNo).prop("disabled", false);


		}


		//(tax1, "tax1");
		var formats = document.getElementById('formats').value;
		if (formats == 'Estimate') {
			$("#taxal" + rowNo).val(0);

		} else {
			// $("#cgst1").val(0);
			getTaxData(tax1, rowNo);


		}
		getProductName(product.id, rowNo);

		// let productCode = product.qrCode;
		// if (productCode) {
		// 	getProductsCode(productCode, rowNo);
		// }

		$("#p_code" + rowNo).val(product.qrCode);
		// $("#p_code"+rowNo).val(product.qrCode != null ? customer.name : "");

		var formats = document.getElementById('formats').value;

		if (formats == 'Estimate') {
			$("#tax" + rowNo).val(0);

		} else {
			$("#tax" + rowNo).val(tax);
			$("#tax" + rowNo).trigger("change");
		}
		$("#qty" + rowNo).val(1);
		if (type == "Purchase") {
			$("#rate" + rowNo).val(product.price);
		} else {
			$("#productId" + rowNo).val(product.id);
			$("#itemCode" + rowNo).val(product != null ? 1 : "");
			$("#hsnCode" + rowNo).val(product != null ? product.hsn : "");
			$("#rate" + rowNo).val(
				product != null ? parseInt(product.price) : "0"
			);
			var actual_price = product.price;
			purchase_rate = product.price;
			rate = purchase_rate - ((purchase_rate * tax) / (100 + parseInt(tax)));
			let amount = rate;
			//(amount, "amount");
			$("#qty" + rowNo).trigger("change");
			$("#discount" + rowNo).val(0);
			var qty = $("#qty" + rowNo).val();
			let discount = 0;
			amount = (amount * (100 - discount)) / 100;
			//(amount, "overrite");
			const taxPercent = $("#tax" + rowNo).val();
			const taxcal = $("#taxcal").val();
			//(taxcal);
			var taxAmount;
			var formates = document.getElementById('formats').value;

			//(price);
			if (taxcal == 1) {

				if (formates == 'Estimate') {
					$("#price" + rowNo).val(0);
					// $("#price" + rowNo).prop("disabled", true);

				} else {
					$("#price" + rowNo).val(amount.toFixed(2));

				}

				var taxAmount = calculateTaxAmount(amount, taxPercent);
			} else {
				if (formates == 'Estimate') {
					$("#price" + rowNo).val(0);
					// $("#price" + rowNo).prop("disabled", true);

				} else {
					$("#price" + rowNo).val(amount);
				}
				var taxAmount = calculateTaxAmount(actual_price, taxPercent);
				// //(taxAmount, "TeaxtAmount");
			}

			$("#taxAmount" + rowNo).val(taxAmount.toFixed(2));
			$("#amount" + rowNo).val(taxAmount.toFixed(2));
			//(actual_price, "actiral price1234567");
			//(taxAmount, "TeaxtAmount1234");
			calculatePrice(rowNo, type);
			calculateGST(tax, amount, rowNo);
			manipulateRHSData();

			$('#editProduct' + rowNo).html('<a href="#" onclick="productForEdit(' + rowNo + ',' + product.id +
				')"><i class="fas fa-edit" style="width:41px;" title="Product Edit"></i></a>');
			// document.getElementById('qty' + rowNo).focus();





		}
	};


	// const setSelectedProductDetailsEdit = (rowNo, product) => {

	// 	// let productCode = product.qrCode;
	// 	document.getElementById('productEditModelRow').value = rowNo;
	// 	document.getElementById('productEditModelProductId').value = product.id;
	// 	// $('#mynewmodal').modal('show');
	// 	getTaxData(rowNo);


	// };
	const calculateTaxAmount = (amount, taxPercent) => {
		let taxAmount = (amount * taxPercent) / 100;
		return taxAmount;
	};
	const calculateTaxAmount1 = (amount, taxPercent) => {
		let taxAmount = amount - (amount * taxPercent) / 100;
		return taxAmount;
	};


	// const taxCalculate=(rowcolumn)=>
	// {
	// 	var tax = $("#tax" + rowNumber).val();
	// 	var amount = $("#amount" + rowNumber).val();

	// 	let taxam = (amount * tax) / 100;
	// 	calculatePrice(rowNumber, type);

	// calculateGST(tax, amount, rowNo);
	// manipulateRHSData();

	// }

	function calculateGST(tax, amount, rowNo) {
		var customerId = $("#party_id").val();
		var customerName = $("#customerName").val();
		if (customerId == "" || customerId == null) {
			if (customerName == "" || customerName == null) {
				// Swal.fire({
				// 	position: "top-end",
				// 	icon: "warning",
				// 	title: "Please select customer.",
				// 	showConfirmButton: false,
				// 	timer: 2000,
				// });
				$("#party_id").focus().trigger("change");
				$("#product" + rowNo).val("");
				$("#product" + rowNo).select2({
					selectONclose: true,
				});
				$("#productId" + rowNo).val("");
				$("#itemCode" + rowNo).val("");
				$("#hsnCode" + rowNo).val("");
				$("#tax" + rowNo).val("0.00");
				$("#stock" + rowNo).val("");
				$("#rate" + rowNo).val("0.00");
				$("#qty" + rowNo).val("");
				$("#price" + rowNo).val("0.00");
				$("#discount" + rowNo).val("0");
				$("#cost" + rowNo).val("0.00");
				$("#taxAmount" + rowNo).val("0.00");
				$("#netAmount" + rowNo).val("0.00");
				return false;
			}
		}
		sgstPer = 0;
		cgstPer = 0;
		igstPer = 0;
		// var stateCode =('#stateSelect').val();
		let stateCode = document.getElementById('stateSelect').value;

		if (stateCode == <?= $this->session->userdata('state'); ?>) {
			sgstPer = tax / 2;
			cgstPer = tax / 2;
		} else {
			igstPer = tax;
		}
		sgst = calculateTaxAmount(amount, sgstPer);
		cgst = calculateTaxAmount(amount, cgstPer);
		igst = calculateTaxAmount(amount, igstPer);
		// alert(igst,'1st');
		$("#sgst" + rowNo).val(parseFloat(sgst).toFixed(2));
		$("#cgst" + rowNo).val(parseFloat(cgst).toFixed(2));
		$("#igst" + rowNo).val(parseFloat(igst).toFixed(2));
	}

	const onNewRowAdd = (e) => {
		if (e.keyCode === 13) {
			onAddNewRowHandler();
		}
	};


	const onAddNewRowHandler = () => {
		getProducts();

	}


	function IsNumeric(evt) {
		evt = evt ? evt : window.event;
		var charCode = evt.which ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
	const calculateRate = (numRow, type) => {
		var rate = $("#rate" + numRow).val();
		var tax = $("#tax" + numRow).val();
		//(rate, "before calcualting Rate");
		//(tax, "before calculating Tax");
		if (type == "Purchase") {
			return rate;
		} else {
			return rate - ((rate * tax) / (100 + parseInt(tax)));
		}
	}
	const calculateRate1 = (numRow, type) => {
		var rate = $("#price" + numRow).val();
		var tax = $("#tax" + numRow).val();
		if (type == "Purchase") {
			return rate;
		} else {
			return ((rate * tax) / (100 + parseInt(tax)));
		}
	}
	const onQuantityChangeHandler = (e, rowNumber, type = "Purchase") => {
		const qty = e.target.value;
		//(qty);
		var cd = $("#cd" + rowNumber).val();
		var purchasre = $("#rate" + rowNumber).val();
		discount = $("#discount" + rowNumber).val();
		var taxcal = $("#taxcal").val();
		let rate = calculateRate(rowNumber, type);
		rate = (rate * (100 - discount)) / 100;
		rate = (rate * (100 - cd)) / 100;
		rate = rate * qty;
		//(rate, "Rate");
		let amount = purchasre;
		$("#price" + rowNumber).val(rate);
		const taxPercent = $("#tax" + rowNumber).val();
		const taxAmount = calculateTaxAmount(rate, taxPercent);
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		// const taxAmount1 = calculateTaxAmount1(rate, taxPercent);

		$("#amount" + rowNumber).val(taxAmount.toFixed(2));
		//(taxAmount1, "Tax Amount");

		// $("#netAmount" + rowNumber).val(netAmount);

		// $("#discount" + rowNumber).trigger("change");
		calculatePrice(rowNumber, type);

		calculateGST(taxPercent, amount, rowNumber);
		manipulateRHSData();
		$("#rate" + rowNumber).trigger("change");

		onRateChangeHandler(rate, rowNumber);

	};

	function ChangeTax(e, rowNumber, type = "Purchase") {

		const tax = e;
		//(tax, "taxvalue");
		// e.options[e.selectedIndex].tax;
		// $("#tax"+rowNumber :selected).val(tax); 
		// var code = $(this).find(':selected').data(tax);
		$('#tax' + rowNumber).val(tax);
		// $("#tax"+rowNumber :selected).val(tax); 

		var cd = $("#cd" + rowNumber).val();
		var qty = $("#qty" + rowNumber).val();

		var purchasre = $("#rate" + rowNumber).val();

		discount = $("#discount" + rowNumber).val();

		// discount_type = $("#discounttype" + rowNumber).val();
		// discountcd_type = $("#discounttypecd" + rowNumber).val();
		var taxcal = $("#taxcal").val();
		// if(taxcal=="Exclusive")
		// {
		var rate = calculateRate(rowNumber, type);
		//(rate, "Rate19");

		// }else
		// {
		// 	let rate = calculateRate(rowNumber, type);

		// }
		rate = (rate * (100 - discount)) / 100;

		rate = (rate * (100 - cd)) / 100;

		rate = rate * qty;
		//(rate, "Rate1234");
		let amount = purchasre;
		$("#price" + rowNumber).val(rate.toFixed(2));


		// let netAmount = amount * qty;

		//(amount, "amount");
		// $("#amount" + rowNumber).val(rate);

		//here #price is taxable amount=>rate*qty
		//#amount is taxamount=>taxableamount*taxrate/100
		//netamont=>taxableamont+tax amount;

		const taxPercent = $("#tax" + rowNumber).val();
		//(taxPercent, "Teaxtpercentage");
		const taxAmount = calculateTaxAmount(rate, taxPercent);
		//(taxAmount, "TeaxtAmount")
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		// const taxAmount1 = calculateTaxAmount1(rate, taxPercent);

		$("#amount" + rowNumber).val(taxAmount.toFixed(2));
		//(taxAmount1, "Tax Amount");

		// $("#netAmount" + rowNumber).val(netAmount);

		// $("#discount" + rowNumber).trigger("change");
		calculatePrice(rowNumber, type);

		calculateGST(taxPercent, amount, rowNumber);
		manipulateRHSData();
		$("#rate" + rowNumber).trigger("change");

		onRateChangeHandler(rate, rowNumber);

	};


	function onPriceChangeHandler() {
		return;
	}
	const onDiscountHandler = (e, rowNumber, type = "Purchase") => {
		let discount = e.target.value;
		var cd = $("#cd" + rowNumber).val();
		let qty = $("#qty" + rowNumber).val();
		let rate = calculateRate(rowNumber, type);
		//(rate, "discount_rate");
		const taxPercent = $("#tax" + rowNumber).val();
		// discount_type = $("#discounttype" + rowNumber).val();
		// discountcd_type = $("#discounttypecd" + rowNumber).val();
		let discountAmt = "";
		// if (discount_type == "Rs") {

		if (discount > 100) {
			Swal.fire({
				position: "top-end",
				title: "Invalid Discount",
				text: "Discount can't be greater than 100%",
				icon: "warning",
				showConfirmButton: false,
				timer: 2000,
			});
			discount = 0;
			$("#discount" + rowNumber).val("0");
			$("#discount" + rowNumber).trigger("change");
		}
		discountAmt = (rate * (100 - discount)) / 100;

		discountAmt = (discountAmt * (100 - cd)) / 100;

		let amount = discountAmt * qty;
		const taxAmount = calculateTaxAmount(amount, taxPercent);
		// $("#amount" + rowNumber).val(amount.toFixed(2));
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		$("#amount" + rowNumber).val(taxAmount.toFixed(2));

		$("#netAmount" + rowNumber).val(
			(parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
		);
		calculateGST(taxPercent, amount, rowNumber);
		calculatePrice(rowNumber, type);
		manipulateRHSData();
		$("#expenses").val(0);
	};

	function EnterPress(event, rowNo) {
		if (event.keyCode == 13) {

			onAddNewRowHandler();
			// alert(rowNo);
			// console.log(event.target.value);
			document.getElementById("qty" + rowNo).disabled = true;
			var firstrow = rowNo;
			rowNo = rowNo + 1;
			// 	console.log(rowNo,"roeNoonkljhhhjh")
			setTimeout(function() {
				<?php
				$barcode = $this->session->userdata('barcode');
				if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
				?>
					document.getElementById('p_code' + rowNo).focus();
					document.getElementById("qty" + firstrow).disabled = false;

				<?php
				} else {
				?>
					document.getElementById("qty" + firstrow).disabled = false;

					document.getElementById('productname' + rowNo).focus();

				<?php

				} ?>
			}, 1000);

			return false;

			// return true;
			// forProductCode(rowNo);


		}
	}

	const calculatePrice = (rowNumber, type) => {
		var tax = $("#tax" + rowNumber).val();
		var cd = $("#cd" + rowNumber).val();
		var val = 0;
		var formats = document.getElementById('formats').value;

		//   discount_type = $("#discounttype" + rowNumber).val();
		//   discountcd_type = $("#discounttypecd" + rowNumber).val();
		discount = $("#discount" + rowNumber).val();
		var qty = $("#qty" + rowNumber).val();

		// amount = (amount * (100 - amount)) / 100;

		// $("#amount" + rowNumber).val(amount);
		var rate = $("#rate" + rowNumber).val();

		var taxcal = $("#taxcal").val();
		var actual_rate = $("#rate" + rowNumber).val();


		//   if (discount_type == "%") {
		rate = (rate * (100 - discount)) / 100;
		//   } else if (discount_type == "Rs") {
		//     rate = rate - discount;
		//   }
		//   if (discountcd_type == "%") {
		rate = (rate * (100 - cd)) / 100;
		//   } else if (discountcd_type == "Rs") {
		//     rate = rate - cd;
		//   }
		var price = "";
		if (tax == "" || tax == null || tax == 0) {
			price = rate;
		} else {
			price = ((100 + parseFloat(tax)) * rate) / 100;
		}


		//(rate, "price");



		if (taxcal == 1) {
			rate = rate - ((rate * tax) / (100 + parseInt(tax)));

			// var rate = calculateRate(rowNumber, type);

			rate = rate * qty;
			// if (formats == 'Estimate') {
			// 	$("#price" + rowNumber).val(0);

			// } else {
			$("#price" + rowNumber).val(rate.toFixed(2));

			// }
			//(rate, "price1234");
			// var tx = $("#totalTax" + rowNumber).val();
		} else {
			actual_rate = rate * qty;

			$("#price" + rowNumber).val(actual_rate);

			// }
			price = $("#price" + rowNumber).val();
			//(price, "price4321");

			// var tx = $("#totalTax" + rowNumber).val();
		}
		// if (formats == 'Estimate') {

		// var price = $("#price" + rowNumber).val(0);
		// } else {
		var price = $("#price" + rowNumber).val();
		// alert(price)

		// }
		var amount12 = $("#amount" + rowNumber).val();
		//(amount12, "amount127627167167162716");

		var natamount = parseFloat(amount12) + parseFloat(price);
		$("#netAmount" + rowNumber).val(natamount.toFixed(2));
		//(natamount, "NetAmount");
		if (formats == 'Estimate') {
			$("#price" + rowNumber).val(0);

			$("#tax" + rowNumber).val(0);

			$("#tax" + rowNumber).prop("disabled", true);
		}




	};

	const onCDHandler = (e, rowNumber, type = "Purchase") => {
		let cd = e.target.value;
		let discount = $("#discount" + rowNumber).val();
		var taxPercent = $("#tax" + rowNumber).val();
		let qty = $("#qty" + rowNumber).val();
		let rate = calculateRate(rowNumber, type);
		// discount_type = $("#discounttype" + rowNumber).val();
		// discountcd_type = $("#discounttypecd" + rowNumber).val();
		// if (discount_type == "%") {
		rate = (rate * (100 - discount)) / 100;
		// } else if (discount_type == "Rs") {
		// 	rate = rate - discount;
		// }
		// if (discountcd_type == "%") {
		if (cd > 100) {
			Swal.fire({
				position: "top-end",
				title: "Invalid CD",
				text: "CD can't be greater than 100%",
				icon: "warning",
				confirmButtonColor: "#3085d6",
				showConfirmButton: false,
				timer: 2000,
			});
			cd = 0;
			$("#cd" + rowNumber).val("0");
			$("#cd" + rowNumber).trigger("change");
		}



		// if (discountcd_type == "%") {
		rate = (rate * (100 - cd)) / 100;
		// } else if (discountcd_type == "Rs") {
		// 	rate = rate - cd;
		// }
		amount = qty * rate;
		const taxAmount = calculateTaxAmount(amount, taxPercent);
		$("#amount" + rowNumber).val(amount.toFixed(2));
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		$("#netAmount" + rowNumber).val(
			(parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
		);
		calculateGST(taxPercent, amount, rowNumber);
		calculatePrice(rowNumber, type);
		manipulateRHSData();
		$("#expenses").val(0);
	};

	// 	$('body').on('keydown', 'input, select', function(e) {
	//     if (e.key === "Enter") {
	//         var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
	//         focusable = form.find('input,a,select,button,textarea').filter(':visible');
	//         next = focusable.eq(focusable.index(this)+1);
	//         if (next.length) {
	//             next.focus();
	//         } else {
	//             form.submit();
	//         }
	//         return false;
	//     }
	// });

	const onRateChangeHandler = (e, rowNumber, type = 'Purchase') => {
		// var rate = e.target.value;
		var rate = $("#rate" + rowNumber).val();

		var cd = $("#cd" + rowNumber).val();
		const taxPercent = $("#tax" + rowNumber).val();
		var netamount = rate * $("#qty" + rowNumber).val();
		$("#price" + rowNumber).val(rate);

		//   discount_type = $("#discounttype" + rowNumber).val();
		//   discountcd_type = $("#discounttypecd" + rowNumber).val();

		// rate = rate - ((rate * taxPercent) / (100 + parseInt(taxPercent)));
		// rate=calculateRate();
		rate = calculateRate(rowNumber, type);

		//   $("#base_price" + rowNumber).val(rate);
		discount = $("#discount" + rowNumber).val();
		//   if (discount_type == "%") {
		rate = (rate * (100 - discount)) / 100;
		//   } else if (discount_type == "Rs") {
		// rate = rate - discount;
		//   }
		//   if (discountcd_type == "%") {
		rate = (rate * (100 - cd)) / 100;
		//   } else if (discountcd_type == "Rs") {
		//     rate = rate - cd;
		//   }
		amount = rate * $("#qty" + rowNumber).val();
		// $("#amount" + rowNumber).val(amount);
		const taxAmount = calculateTaxAmount(amount, taxPercent);
		$("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
		$("#amount" + rowNumber).val(taxAmount.toFixed(2));
		// $("#netAmount" + rowNumber).val(
		// 	(parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
		// );
		// $("#discount" + rowNumber).trigger("change");
		$("#expenses").val(0);
		calculatePrice(rowNumber, type);
		calculateGST(taxPercent, amount, rowNumber);
		manipulateRHSData();

	};

	const a = [
		"",
		"One ",
		"Two ",
		"Three ",
		"Four ",
		"Five ",
		"Six ",
		"Seven ",
		"Eight ",
		"Nine ",
		"Ten ",
		"Eleven ",
		"Twelve ",
		"Thirteen ",
		"Fourteen ",
		"Fifteen ",
		"Sixteen ",
		"Seventeen ",
		"Eighteen ",
		"Nineteen ",
	];
	const b = [
		"",
		"",
		"Twenty",
		"Thirty",
		"Forty",
		"Fifty",
		"Sixty",
		"Seventy",
		"Eighty",
		"Ninety",
	];
	const numberToWords = (num) => {
		if ((num = num.toString()).length > 9) return "overflow";
		let n = ("000000000" + num)
			.substr(-9)
			.match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
		if (!n) return;
		var str = "";
		str +=
			n[1] != 0 ?
			(a[Number(n[1])] || b[n[1][0]] + " " + a[n[1][1]]) + "Crore " :
			"";
		str +=
			n[2] != 0 ?
			(a[Number(n[2])] || b[n[2][0]] + " " + a[n[2][1]]) + "Lakh " :
			"";
		str +=
			n[3] != 0 ?
			(a[Number(n[3])] || b[n[3][0]] + " " + a[n[3][1]]) + "Thousand " :
			"";
		str +=
			n[4] != 0 ?
			(a[Number(n[4])] || b[n[4][0]] + " " + a[n[4][1]]) + "Hundred " :
			"";
		str +=
			n[5] != 0 ?
			(str != "" ? "And " : "") +
			(a[Number(n[5])] || b[n[5][0]] + " " + a[n[5][1]]) +
			"Only " :
			"";
		return str;
	};


	const onRemoveRowHandlers = (rowNo) => {
		$("#tr" + rowNo).remove();
		$("#tr" + rowNo).remove();
		$("#tr" + rowNo).remove();
		$("#tr" + rowNo).remove();
		manipulateRHSData();
	};

	const onRemoveRowHandler = (rowNo) => {

		// const invoiceProductId = $("#invoiceProductId" + rowNo).val();
		// const invoiceId = $("#id").val();
		// if (invoiceId != null && invoiceProductId != null) {
		// 	deleteInvoiceProduct(invoiceProductId, invoiceId);
		// }
		// var row = $("#tr" + rowNo).remove();
		//it was not working on first click so i have clicked it four time bcz its working on click at four time 
		$("#tr" + rowNo).remove();
		$("#tr" + rowNo).remove();
		$("#tr" + rowNo).remove();
		$("#tr" + rowNo).remove();
		console.log(rowNo);
		// alert(rowNo);
		let rowCount = $("#dataTable tbody tr").length + 1;
		//(rowCount, "rowcount");
		// alert(rowCount)
		if (rowCount == 1) {
			onAddNewRowHandler();
		} else {
			// $("#tr" + rowNo).remove();


			// alert(status);

			document.getElementById("expenses").focus();


		}
		manipulateRHSData();
	};
	const manipulateRHSData = () => {
		let taxableAmount = 0;
		let totalTax = 0;
		let totalValue = 0;
		let roundOff = 0;
		let total = 0;
		let totalsgst = 0;
		let totalcgst = 0;
		let totaligst = 0;
		const rows = $("#dataTable").children("tbody").children("tr");
		rows.map(function(row) {
			let row1 = $("#dataTable").children("tbody").children("tr")[row];
			const trId = row1.getAttribute("id");
			const rowNumber = trId.slice(2, trId.length);
			const taxAmount = $("#taxAmount" + rowNumber).val();
			const netAmount = $("#netAmount" + rowNumber).val();
			const sgst = $("#sgst" + rowNumber).val();
			const cgst = $("#cgst" + rowNumber).val();
			const igst = $("#igst" + rowNumber).val();
			// alert(igst);
			const amount = $("#amount" + rowNumber).val();
			const price = $("#price" + rowNumber).val();

			//(taxAmount, 'taxAmount')

			if (taxAmount != null && taxAmount != "") {
				taxableAmount += parseFloat(price);
				totalTax += parseFloat(amount);
				totalValue += parseFloat(netAmount);
			}
			totalsgst += parseFloat(sgst);
			totalcgst += parseFloat(cgst);
			totaligst += parseFloat(igst);
		});
		//(totalTax, 'totaltAx')
		//(taxableAmount, 'taxableAmount')
		//(totalValue, 'totalValue')

		roundOff += parseFloat("." + totalValue.toString().split(".")[1]);
		$("#taxableAmount").val(taxableAmount.toFixed(2));
		$("#totalTax").val(totalTax.toFixed(2));
		$("#amount").val(totalTax.toFixed(2));

		$("#totalValue").val(totalValue.toFixed(2));
		$("#roundOff").val(roundOff == "" || isNaN(roundOff) ? 0 : roundOff.toFixed(2));
		$("#total").val(Math.round(totalValue).toFixed(2));
		$("#amountInWords").val(numberToWords(parseInt(totalValue)));
		$("#totalsgst").val(totalsgst.toFixed(2));
		$("#totalcgst").val(totalcgst.toFixed(2));
		$("#totaligst").val(totaligst.toFixed(2));
		var formats = document.getElementById('formats').value;
		if (formats == 'Estimate') {
			$("#taxAmount").val(0);
			// $("#cgst1").val(0);
			// $("#amountInWords").val(0);

			// $("#totalTax").prop("disabled", true);

			// $("#taxableAmount").val(0);


		}
		//(taxableAmount, "texableAmount");

	};

	const getTaxData = (tax_id, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: tax_id,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>tax/gettax",

			success: function(invoiceNo) {

				$("#taxal" + rowNum).html(invoiceNo);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);


			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}

	const getTaxDatas = (tax_id, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: tax_id,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>tax/gettaxs",

			success: function(invoiceNo) {

				$("#protax" + rowNum).html(invoiceNo);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);


			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}

	const getUnitDatas = (unit_id, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: unit_id,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>tax/getunit",

			success: function(invoiceNo) {

				$("#unit" + rowNum).html(invoiceNo);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);


			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}
	const getProductsCode = (procode, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: procode,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>productCode/getProductsCode",
			success: function(productCode) {
				//(productCode, "productcode");
				$("#productcode" + rowNum).html(productCode);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);


			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}

	const getProductName = (pro_id, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: pro_id,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>product/pro_name",

			success: function(invoiceNo) {

				$("#pro_names" + rowNum).html(invoiceNo);

			},

		});
	}


	// $(function() {
	// 	var tabindex = 1;
	// 	$('input,select').each(function() {
	// 		if (this.type != "hidden") {
	// 			var $input = $(this);
	// 			$input.attr("tabindex", tabindex);
	// 			//(input)
	// 			tabindex++;
	// 		}
	// 	});
	// });


	const getProductCodeDetails = (procode, rowNum) => {
		$.ajax({
			type: "POST",
			data: {
				id: procode,
				rownumber: rowNum,
			},
			url: "<?= base_url() ?>productCode/productCodeInfo",

			success: function(procodess) {

				$("#productcode" + rowNum).html(procodess);

				// invoiceData = invoiceNo.data;
				// cosnole.log(invoiceData);




			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}
	const onFormSubmitHandler = (e) => {
		e.preventDefault();

		if ($("#invoiceNumber").val() != null && $("#invoiceNumber").length < 1) {
			Swal.fire({
				type: 'fail',
				title: "Invoice Number is required",
			});
			$("#btnSubmit").prop("disabled", false);

			return false;
		}
		// document.getElementById('btnSave').focus();

		let buttons =
			'<div class="mb-3"><span></span></div><div class="mb-2"><button tabindex="-1" id="btnSave" tabindex="1" class="btn btn-primary mr-2" onkeyup="chnageDirection(event)" onkeydown="chnageDirection(event)" onclick="saveInvoice(1)">Save</button>'
		buttons +=
			'<button id="btnSaveAndPrint" class="btn btn-secondary mr-2" tabindex="-1" onkeyup="chnageDirections(event)" onkeydown="chnageDirections(event)" onclick="saveInvoice(2)">Save & Print</button>'
		buttons +=
			'<button id="btnSaveCancel" class="btn btn-default mr-2" tabindex="-1" onkeyup="chnageDirectionss(event)" onkeydown="chnageDirectionss(event)" onclick="swalclose()">Cancel</button></div>'

		window.setTimeout(function() {
			document.getElementById('btnSaveAndPrint').focus();
		}, 0);
		Swal.fire({
			title: 'Are you sure to Save Invoice ?',
			text: "This will create an invoice.",
			html: buttons,
			showConfirmButton: false,
			showCancelButton: false,
			confirmButtonColor: '#009a1a',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, save it!'
			// document.getElementById('btnSave').click();

		})
		// document.getElementById('btnSave').click();
	}

	const swalclose = () => {
		Swal.fire({
			position: 'top-end',
			icon: 'error',
			title: "Cancelled",
			timer: 2000
		});


	}


	const saveInvoice = (saveAction) => {
		//console.info($("#dataForm").serialize())
		// $("#btnSave").prop("disabled", true);
		// $("#btnSaveAndPrint").prop("disabled", true);
		// $("#btnSaveCancel").prop("disabled", true);
		// document.getElementById('btnSubmit').disabled = true;
		$("#saveAction").val(saveAction)
		$.ajax({
			type: "POST",
			url: "<?= base_url() ?>purchase/savePurchase",
			data: $("#dataForm").serialize(),
			success: function(invoiceNo) {

				invoiceNo = JSON.parse(invoiceNo);
				if (invoiceNo.status == true) {
					invoiceData = invoiceNo.data;
					// cosnole.log(invoiceData);
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: "Invoice saved successfully.",
						showConfirmButton: false,
						timer: 2000
					})
					if (saveAction == 1) { //Save Only
						setTimeout(function() {
							//window.location.reload()
							window.location.replace('Purchase')
						}, 2000)
					} else if (saveAction == 2) { //Save & Print
						setTimeout(function() {
							// $("#invoiceNumber").val(invoiceData)
							redirectToPrint(invoiceData)
						}, 2000)
					}
				} else {
					Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: invoiceNo.msg,
						showConfirmButton: false,
						timer: 2000
					})
					document.getElementById('invoiceNumber').focus();
				}
				$("#btnSubmit").prop("disabled", false);

			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#btnSubmit").prop("disabled", false);
			},
		});
	}

	const updateInvoice = () => {
		//console.info($("#dataForm").serialize())
		var printValue = document.getElementById('printPurchase').value;
		// printValue=1+parseInt(printValue);
		// alert(printValue);
		var invoiceNumber = document.getElementById('invoiceNumber').value;
		// if(invoiceNumber)

		if (printValue == 0) {
			// alert('ENter First Invocie Number That you Want to Update');
			Swal.fire({
				position: "top-end",
				icon: "error",
				title: "Enter Invoice First",
				showConfirmButton: false,
				timer: 2000,
			});
			return;
		}
		// $("#updateInvoice").prop("disabled", true);
		// $("#saveAction").val(saveAction)
		var url = "<?= base_url() ?>purchase/updatepurchase";

		//(url, "url");
		Swal.fire({
			title: "Are you sure to Update?",
			text: "You won't be able to revert this!",
			icon: "success",
			showCancelButton: true,
			confirmButtonColor: "success",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, Update it!",
		}).then((result) => {
			if (result.isConfirmed) {
				updateInvoiceMail(url);
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



	const onProductFocusChange = (e, rowNo) => {
		if (e.target.value.length >= 1) {
			const row = document.getElementById("tr" + rowNo);
			row.parentNode.removeChild(row);
			document.getElementById("expenses").focus();
			//Add A Blank Row if table is empty
			let rowCount = $("#dataTable tbody tr").length;
			if (rowCount < 1) {
				onAddNewRowHandler();
				document.getElementById("party_id").focus();
			}
		}
	};

	const redirectToPrint = (invoiceNo) => {
		// const invoiceNo = $("#invoiceNumber").val()
		// window.location.href = '<?= base_url(); ?>'+invoiceNo+''
		window.location.href = '<?= base_url(); ?>index.php/purchase/printPurchase/' + invoiceNo + '';
	}
	const redirectToPrints = (invoiceNo) => {
		// const invoiceNo = $("#invoiceNumber").val()
		// window.location.href = '<?= base_url(); ?>'+invoiceNo+''
		window.location.href = '<?= base_url(); ?>index.php/purchase/printPurchasePreview/' + invoiceNo + '';
	}



	function save_serial(currentrowid) {
		var qty = $('#qty' + currentrowid).val();
		$('#product_serial' + currentrowid).html('');
		for (var s = 1; s <= qty; s++) {
			//  alert($('#serial' + s).val());
			var val = $('#serial' + currentrowid + s).val();
			$('#product_serial' + currentrowid).append('<input id="serial' + currentrowid + '' + s +
				'" type="text" value="' + val + '" class="form-control" name="product[' + currentrowid +
				'][item_serialno][]" style="margin-bootom:5px;" />');
		}

		// $('#product_serial' + currentrowid).html($('#serialdiv').html());
		$('#serialModal').modal('hide');
		$('#rate' + currentrowid).focus();
	}
</script>
<script>
	function onUserFormSubmitHandler(e) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#addUserForm").attr("action");
		var form = $("#addUserForm")[0];
		var data = new FormData(form);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					// closeModel();
					// window.location.href = "<?= base_url() ?>invent/Purchase";
					// location.reload();
					// alert(customerList.data.id);
					alert("party Created");
					document.getElementById("reset").click();
					document.getElementById("custClose").click();
					// $("#party_id").trigger("change");
					// getPartyList();
					$("#party_id").val(customerList.data.id);
					getCustomerDetail(customerList.data.id, 0);

					// $("#party_id").trigger("change");
					// $("#party_id").trigger("change");
					// //(customerList.data.id, "id");
					// prepareCustomerDropdown(customerList.data);


				} else {
					$("#partyMsg").show();
					$("#partyMsg").addClass("text");
					$("#partyMsg").addClass("text-danger");
					$("#partyMsg").html(customerList.msg);
					// setTimeout(function() {
					// 	$("#partyMsg").hide();
					// }, 3000);
				}
			},
			error: function(jqXHR, exception) {
				$("#exampleModal").modal("hide");
				$("#loader").addClass("d-none");
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	function onProductSubmitHandler(e, row) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#productEditData" + row).attr("action");
		var form = $("#productEditData" + row)[0];
		var data = new FormData(form);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				console.log(customerList.status);
				if (customerList.status == true) {
					document.getElementById('proclick' + row).click();
					// partyProfileEdit('hiddendata' + row,partyId,event);
					alert("Product Updated");
					document.getElementById('protr' + row).focus();

					var start = document.getElementById('protr' + row);
					//  = document.getElementById('trp' + rowId);
					console.log(start, "without js")
					start.style.backgroundColor = 'blue';
					start.style.color = 'white';
					// document.getElementById('exampleModalLong1').click();
					// $('#exampleModalLong1').modal('show');
					// alert("hello");

					// onProductSelect(customerList.data);
					// alert("party updated");
				} else {
					$("#errormsg" + row).show();
					$("#errormsg" + row).addClass("text");
					$("#errormsg" + row).addClass("text-danger");
					$("#errormsg" + row).html(customerList.msg);
					// setTimeout(function() {
					// 	$("#partyMsg").hide();
					// }, 3000);
				}
			},
			error: function(jqXHR, exception) {
				$("#exampleModal").modal("hide");
				$("#loader").addClass("d-none");
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	function party_wala_data() {
		var url = $("#adpartyform").attr("action");
		var form = $("#adpartyform")[0];
		var data = new FormData(form);
		console.log(data, "sdfghjkl");
	}


	function onFormPartySubmitHandler(e) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#adpartyform").attr("action");
		var form = $("#adpartyform")[0];
		var data = new FormData(form);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					alert("party updated");
					document.getElementById("exampleModal07").click();
					$("#party_id").trigger("change");
					$("#party_id").val(customerList.data.id);
					$("#partyname").val(customerList.data.name);


				} else {
					$("#errormsg").show();
					$("#errormsg").addClass("text");
					$("#errormsg").addClass("text-danger");
					$("#errormsg").html(customerList.msg);
					// setTimeout(function() {
					// 	$("#partyMsg").hide();
					// }, 3000);
				}
			},
			error: function(jqXHR, exception) {
				$("#exampleModal").modal("hide");
				$("#loader").addClass("d-none");
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	function onFormPartySubmitHandlers(e, i) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#fom" + i).attr("action");
		var form = $("#fom" + i)[0];
		var data = new FormData(form);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					// partyProfileEdit('hiddendata' + row,partyId,event);
					alert("Party Updated");
					document.getElementById('clik' + i).click();

					document.getElementById('trp' + i).focus();

					var start = document.getElementById('trp' + i);
					//  = document.getElementById('trp' + rowId);
					console.log(start, "trpstart")
					start.style.backgroundColor = 'blue';
					start.style.color = 'white';


				} else {
					$("#errormsg" + i).show();
					$("#errormsg" + i).addClass("text");
					$("#errormsg" + i).addClass("text-danger");
					$("#errormsg" + i).html(customerList.msg);
					// setTimeout(function() {
					// 	$("#partyMsg").hide();
					// }, 3000);
				}
			},
			error: function(jqXHR, exception) {
				$("#exampleModal").modal("hide");
				$("#loader").addClass("d-none");
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	function onFormProductSubmitHandlers(e, i) {
		e.preventDefault();
		// $(".searchselect").select2();
		var url = $("#id_pata_nahi" + i).attr("action");
		var form = $("#id_pata_nahi" + i)[0];
		var data = new FormData(form);
		$.ajax({
			type: "POST",
			enctype: "multipart/form-data",
			url: url,
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 600000,
			success: function(customerList) {
				// $(".searchselect").select2();
				customerList = JSON.parse(customerList);
				if (customerList.status == true) {
					// partyProfileEdit('hiddendata' + row,partyId,event);
					// partyProfileEdit('hiddendata' + row,partyId,event);
					// document.getElementById('proclick' + i).click();

					alert("Product Updated");
					// window.location.href='';

					document.getElementById('protr' + i).focus();
					var start = document.getElementById('protr' + i);
					//  = document.getElementById('trp' + rowId);
					console.log(start, "ye chal")
					start.style.backgroundColor = 'blue';
					start.style.color = 'white';

				} else {
					$("#errorpro" + i).show();
					$("#errorpro" + i).addClass("text");
					$("#errorpro" + i).addClass("text-danger");
					$("#errorpro" + i).html(customerList.msg);
					// setTimeout(function() {
					// 	$("#partyMsg").hide();
					// }, 3000);
				}
			},
			error: function(jqXHR, exception) {
				$("#exampleModal").modal("hide");
				$("#loader").addClass("d-none");
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}

	const prepareCustomerDropdown = (customers) => {
		// //(customers);
		// var code = $(this).find(':selected').data('id');
		// $('#code').val(code); <
		// //(customers.id);
		$("#party_id").value(customers.id);

		$('#gstNo').val(customers.gstno);


	};
</script>

<script>
	// $(document).ready(function() {
	$('#gstTypeVal').hide()
	// $('.searchselect').select2({
	// 	dropdownParent: "#exampleModal"
	// });
	function stateChanger(e, row) {
		var aman = e.target.value;
		// $('#state').change(function() {
		// var code = $(this).find(':selected').data('id');
		$('#state_code' + row).val(aman);
		// });
	}
	$('#state').change(function() {
		var code = $(this).find(':selected').data('id');
		$('#code').val(code);
	});
	// const rowCount = 0;
	// $('#tax' + rowCount).change(function() {
	// 	$('#tax' + rowCount).val(3);

	// });

	$('#gstType').change(function() {
		if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
			$('#gstTypeVal').show();
			$('#gst').attr('required', true);
		} else {
			$('#gstTypeVal').hide();
			$('#gst').attr('required', false);
		}
	});
	// });

	$('#gst').blur(function() {
		$("#custClose").focus()
	});

	document.getElementById('party_id').tabIndex = 6;



	// $("input").on("keypress", function(e) {
	// 	if (e.keyCode == 13) {
	// 		$("#addUserForm").submit();
	// 	}
	// });
	// $("#custClose").on("focus", function(e) {
	// 	$("#custClose").css({
	// 		"box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
	// 	});
	// });
	// $("#custSave").on("focus", function(e) {
	// 	$("#custSave").css({
	// 		"box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
	// 	});
	// });
	// $("#custClose").on("blur", function(e) {
	// 	$("#custClose").css({
	// 		"box-shadow": "none"
	// 	});
	// });
	// $("#custSave").on("blur", function(e) {
	// 	$("#custSave").css({
	// 		"box-shadow": "none"
	// 	});
	// });
</script>
<!-- Serial Modal -->
<!-- <div id="serialModal" class="modal fade" role="dialog">
	<div class="modal-dialog">






		Modal content-->
<!-- <div class="modal-content">
			<div class="modal-header"> -->
<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<!-- <h4 class="modal-title">Enter Serial Numbers</h4>
			</div>
			<div class="modal-body" id="serialdiv">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<div id="serialfooter"></div>
			</div>
		</div>

	</div>
</div> -->
<script>
	// $('.li-modal').on('click', function(e) {
	// 	e.preventDefault();
	// 	$('#theModal').modal('show').find('.modal-content').load($(this).attr('href'));
	// });
</script>
<script>
	var inputs = document.querySelectorAll("input,select");
	// //(inputs);
	for (var i = 0; i < inputs.length; i++) {
		inputs[i].addEventListener("keypress", function(e) {
			if (e.which == 13) {
				e.preventDefault();
				var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
				// alert(nextInput);

				if (nextInput.length === 0) {
					nextInput = document.querySelectorAll('[tabIndex="1"]');
				}
				nextInput[0].focus();
			}
		})
	}
</script>
<script>
	function printPurchase() {
		var PurchaseValue = document.getElementById('printPurchase').value;
		// alert(PurchaseValue);
		var invoiceNumber = document.getElementById('invoiceNumber').value;

		if (PurchaseValue == 0) {
			// alert('Enter First Invoice Number That you Want to Print');
			Swal.fire({
				position: "top-end",
				icon: "error",
				title: "Enter Invoice First",
				showConfirmButton: false,
				timer: 2000,
			});
			return;

		}
		//(invoiceNumber);
		if (PurchaseValue)
			redirectToPrint(PurchaseValue);
	}

	function printPurchasePreview() {
		var PurchaseValue = document.getElementById('printPurchase').value;
		// alert(PurchaseValue);
		var invoiceNumber = document.getElementById('invoiceNumber').value;

		if (PurchaseValue == 0) {
			// alert('Enter First Invoice Number That you Want to Print');
			Swal.fire({
				position: "top-end",
				icon: "error",
				title: "Enter Invoice First",
				showConfirmButton: false,
				timer: 2000,
			});
			return;

		}
		//(invoiceNumber);
		if (PurchaseValue) {
			redirectToPrints(PurchaseValue);
		}
	}
</script>
<script>
	document.onkeyup = function(e) {
		if (e.which == 77) {
			// alert("M key was pressed");
		} else if (e.ctrlKey && e.which == 66) {
			// alert("Ctrl + B shortcut combination was pressed");
			deleteRow();
		} else if (e.ctrlKey && e.altKey && e.which == 89) {
			preview();

			// alert("Ctrl + Alt + Y shortcut combination was pressed");
		} else if (e.ctrlKey && e.altKey && e.shiftKey && e.which == 85) {
			next();
			// alert("Ctrl + Alt + Shift + U shortcut combination was pressed");
		}
	};


	jQuery(document).bind("keyup keydown", function(e) {
		var x = event.which || event.keyCode;
		// if (e.ctrlKey && e.keyCode == 100) {
		if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 117 || e
				.keycode === 17 || e.keycode === 85)) { //ctrl+u Alt+c, Alt+v will also be disabled sadly.

			// printPurchase();
			// deleteRow();
			updateInvoice();


			return false;
		}
	});

	function updateInvoiceMail(url) {
		$.ajax({
			type: "POST",
			url: url,
			data: $("#dataForm").serialize(),
			success: function(invoiceNo) {
				invoiceNo = JSON.parse(invoiceNo);
				// //(invoiceNo,"idreturn");
				if (invoiceNo.status == true) {

					invoiceData = invoiceNo.data;
					// cosnole.log(invoiceData);
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: "Invoice Updated successfully.",
						showConfirmButton: false,
						timer: 2000
					})
					setTimeout(function() {
						window.location.reload();
					}, 2000);

				} else {
					Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: invoiceNo.msg,
						showConfirmButton: false,
						timer: 2000
					})
				}
				// $("#updateInvoice").prop("disabled", false);

			},
			error: function(jqXHR, exception) {
				$("#loader").addClass("d-none");
				var msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status == 403) {
					msg = 'Permission denied. [403]';
				} else if (jqXHR.status == 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n';
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					type: 'error',
					title: msg,
				});
				$("#updateInvoice").prop("disabled", false);
			},
		});
	}
</script>
<script>
	// function preview() {
	// 	var preview = document.getElementById('printPurchase').value;
	// 	if (preview == '0') {
	// 		preview = 2;
	// 	}
	// 	if (preview != 0) {
	// 		// var preview = parseInt(preview) - parseInt(1);
	// 		//(preview);
	// 		$.ajax({
	// 			method: "POST",
	// 			// url: baseurl + "get-customer",
	// 			url: baseurl + "index.php/party/getInvoiceByPurchase",

	// 			data: {
	// 				id: preview,
	// 			},
	// 			success: function(customer) {

	// 				var customer = JSON.parse(customer);
	// 				var PurchaseData = customer.data;
	// 				//(PurchaseData, "Purchase data");
	// 				if (PurchaseData.invoiceNumber != null) {
	// 					var elmtTable = document.getElementById('dataTable');
	// 					var tableRows = elmtTable.getElementsByTagName('tr');
	// 					var rowCount = tableRows.length;
	// 					// //(rowCount);
	// 					for (var x = rowCount; x > 0; x--) {
	// 						//    elmtTable.remove(tableRows[x]);
	// 						// onRemoveRowHandler(tableRows[x])
	// 						$("#tr" + x).remove();

	// 					}
	// 					document.getElementById('invoiceNumber').value = PurchaseData.invoiceNumber;
	// 					$("#invoiceNumber").trigger("change");

	// 					getInvoiceInfo(PurchaseData.invoiceNumber);

	// 				}
	// 				// var da = customer.data;
	// 			}
	// 		});

	// 	}

	// }
	function preview() {
		var invoiceNumber = document.getElementById('invoiceNumber').value;
		if (invoiceNumber != '') {
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/party/getInvoiceByPurchase",

				data: {
					id: invoiceNumber,
				},
				success: function(customer) {

					var customer = JSON.parse(customer);
					console.log(customer, "customers");

					var PurchaseData = customer.data;
					//(PurchaseData, "Purchase data");
					if (PurchaseData.invoiceNumber != null) {
						var elmtTable = document.getElementById('dataTable');
						var tableRows = elmtTable.getElementsByTagName('tr');
						var rowCount = tableRows.length - 1;
						// //(rowCount);
						// var rowCount=0;

						for (var x = rowCount; x > 0; x--) {
							//    elmtTable.remove(tableRows[x]);
							// onRemoveRowHandler(tableRows[x])
							// $("#tr" + x).remove();
							onRemoveRowHandlers(x);

						}
						document.getElementById('invoiceNumber').value = PurchaseData.invoiceNumber;
						$("#invoiceNumber").trigger("change");

						getInvoiceInfo(PurchaseData.invoiceNumber);

					}
					// var da = customer.data;
				}
			});

		}

	}
</script>
<script>
	function next() {
		var invoiceNumber = document.getElementById('invoiceNumber').value;
		if (invoiceNumber != '') {
			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/party/getInvoiceByPurchaseForNext",

				data: {
					id: invoiceNumber,
				},
				success: function(customer) {

					var customer = JSON.parse(customer);
					var PurchaseData = customer.data;
					//(PurchaseData, "Purchase data");
					if (PurchaseData.invoiceNumber != null) {
						var elmtTable = document.getElementById('dataTable');
						var tableRows = elmtTable.getElementsByTagName('tr');
						var rowCount = tableRows.length - 1;
						// //(rowCount);
						for (var x = rowCount; x > 0; x--) {
							//    elmtTable.remove(tableRows[x]);
							// onRemoveRowHandler(x)
							onRemoveRowHandlers(x);

							// $("#tr" + x).remove();

						}
						// alert(rowCount);

						document.getElementById('invoiceNumber').value = PurchaseData.invoiceNumber;
						$("#invoiceNumber").trigger("change");

						getInvoiceInfo(PurchaseData.invoiceNumber);

					} else {
						alert("Did not Find the Data");
					}
					// var da = customer.data;
				}
			});

		}

	}

	function productForEdit(rowNumber, productId) {
		jQuery.noConflict();
		document.getElementById('rownumber').value = rowNumber;
		$("#exampleModals .modal-dialog .modal-body").load(baseurl + "index.php/product/productpop/" + productId);
		$("#exampleModalLabels").html("Update Product");
		$("#exampleModals").modal("show");
		// $(".searchselect").select2({
		// 	dropdownParent: "#exampleModal"
		// });
	}

	function partyProfileEdit(rowNumber, party_id, e) {
		$("#exampleModalLong").modal("show");
		// document.getElementById().style.display = "block";
		var x = document.getElementById('hiddendata' + rowNumber);
		// var notidea = document.getElementById('notidea' + rowNumber);

		console.log(x);
		if (x.style.display === "none") {
			x.style.display = "block";
			// notidea.style.display = "block";

		} else {
			x.style.display = "none";
			// console.log("Hide");
			// console.log("Hide");
			start1 = document.getElementById('searchparty');

			// console.log(start1, "asd")
			start1.focus();
			var elmtTable = document.getElementById('example12');
			var tableRows = elmtTable.getElementsByTagName('tr');
			var rowCount = tableRows.length;
			for (var i = 0; i < rowCount; i++) {
				var start = document.getElementById('trp' + i);
				// document.getElementById('hiddendata' + i).style.display = 'none';

				console.log(start, "styuiopj")
				start.style.backgroundColor = 'white';
				start.style.color = 'black';
			}
			// start1 = document.getElementById('trp'+rowNumber);

			// start1.style.backgroundColor = 'blue';
			// start1.style.color = 'white';
			// notidea.style.display = "none";

		}
		// var elmtTable = document.getElementById('example12');
		// var tableRows = elmtTable.getElementsByTagName('tr');
		// var rowCount = tableRows.length;
		// for (var i = 0; i < rowCount; i++) {

		// 	var start = document.getElementById('trp' + i);
		// 	// document.getElementById('hiddendata' + i).style.display = 'none';

		// 	console.log(start, "styuiopj")
		// 	start.style.backgroundColor = 'white';
		// 	start.style.color = 'black';
	}
	// $("model-body").scrollTop(0);
	// alert("hello");
	// e.preventDefault();
	// return false;
	// alert();
	// getPartyDetails(party_id);
	// getCustomerEdit(party_id);

	// $("#exampleModal07").modal("show");


	// }

	function productProfileEdit(rowNumber, productId) {
		// alert("Heide");
		jQuery.noConflict();
		// $("#exampleModalLongTitle").modal("show");
		var x = document.getElementById('hiddenproduct' + rowNumber);
		console.log(x);
		if (x.style.display === "none") {
			x.style.display = "block";
		} else {
			x.style.display = "none";
			start1 = document.getElementById('searchproduct');
			console.log(start1, "asd")
			start1.focus();
			var elmtTable = document.getElementById('example13');
			var tableRows = elmtTable.getElementsByTagName('tr');
			var rowCount = tableRows.length;
			for (var i = 0; i < rowCount; i++) {
				var start = document.getElementById('protr' + i);
				// document.getElementById('hiddendata' + i).style.display = 'none';

				console.log(start, "styuiopj")
				start.style.backgroundColor = 'white';
				start.style.color = 'black';
			}

		}

		// getPartyDetails(party_id);
		// getProducutEditable(productId);
		// $("#exampleModal08").modal("show");


	}
	// if (condition) {
	// document.getElementById("party_id").tabindex = 6;
	// alert(document.getElementById("party_id").tabindex);
	// }
	// document.addEventListener('keydown', function(event) {
	// 	if (event.keyCode === 13 && (event.target.nodeName === 'INPUT' || event.target.nodeName === 'SELECT')) {
	// 		var form = event.target.form;
	// 		//("Hello");
	// 		var index = Array.prototype.indexOf.call(form, event.target);
	// 		// //(index);

	// 		event.preventDefault();
	// 	}
	// });

	// $('.searchselect').focusout();
	// //("hello");
	//  // the hidden field which 'hosts' the select2
	//     $('#gstNo').focus();
	// 	 // the next field on the form
	// document.getElementById("#select2-party_id-container").tabIndex = 6;
	// document.getElementById("#party_id").tabIndex = 6;
	// document.getElementById('#select1-party_id-container').tabIndex = 6;
	// $('#party_id').on('select2:opening select2:closing', function( event ) {
	// var $searchfield = $(this).parent().find('.select2-search__field');
	// alert($searchfield);
	// $searchfield.prop('disabled', true);
	// $('#party_id').trigger('change.select2');

	// 	$("#rightsidebar").click(function(){
	// 		alert("hello");
	//   var columns_container = $("#fulldisplay");
	//   if (!columns_container.hasClass("expanded")) {
	//     // $(".dynamic-columns .col:first").removeClass("col-xs-2");
	//     $("#fulldisplay").addClass("col-md-12");

	//     // $(".dynamic-columns .col:last-child").removeClass("col-xs-8");
	//     // $(".dynamic-columns .col:last-child").addClass("col-xs-9");
	//     columns_container.toggleClass("expanded");
	//   }
	//   else {
	//     $(".dynamic-columns .col:first").removeClass("col-xs-1");
	//     $(".dynamic-columns .col:first").addClass("col-xs-2");

	//     $(".dynamic-columns .col:last-child").removeClass("col-xs-9");
	//     $(".dynamic-columns .col:last-child").addClass("col-xs-8");
	//     columns_container.toggleClass("expanded");
	//   }
	// });

	function toggleData() {
		// alert("hello")
		$("#rightsidebar").toggle();
		$("#fulldisplay").toggleClass("col-md-12");
		$("#pud").css({
			"margin-top": "-78px",
			"margin-left": "165px"
		});

		// });
		// var element = document.getElementById("rightsidebar");
		// element.classList.add("mystyle");
		// var element = document.getElementById("rightsidebar");
		// console.log(element);
		// element.classList.toggle("rightsidebar");

	}
	$(document).ready(function() {
		$("#rightsidebar").toggle();
		$("#fulldisplay").toggleClass("col-md-12");
		// $("#pud").style='';
		$("#pud").css({
			"margin-top": "-78px",
			"margin-left": "165px"
		});


	});

	function requireValue() {
		var elmtTable = document.getElementById('dataTable');
		var tableRows = elmtTable.getElementsByTagName('tr');
		var rowCount = tableRows.length;
		// //(rowCount);
		for (var x = rowCount; x > 0; x--) {

			$("#tr" + x).remove();
		}
	}
	// 	var element = document.getElementById("");
	// 	console.log(element)
	//   element.classList.remove("form-control");
	// var browserZoomLevel = Math.round(window.devicePixelRatio * 100);
	// $("rightsidebar").click(function() {});


	// function zoom() {
	// }
</script>
<script>
	$('#closepartymodel').click(function() {
		$('#exampleModalLong').modal('hide');
		console.log("Hide");
		// document.getElementById('partyname').focus();

		var elmtTable = document.getElementById('example12');
		var tableRows = elmtTable.getElementsByTagName('tr');
		var rowCount = tableRows.length;
		for (var i = 0; i < rowCount; i++) {
			var start = document.getElementById('trp' + i);
			document.getElementById('hiddendata' + i).style.display = 'none';

			console.log(start, "styuiopj")
			start.style.backgroundColor = 'white';
			start.style.color = 'black';
		}

	})

	$('#closeproductmodel').click(function() {
		$('#exampleModalLong1').modal('hide');
		console.log("Hide");
		var elmtTable = document.getElementById('example13');
		var tableRows = elmtTable.getElementsByTagName('tr');
		var rowCount = tableRows.length;
		for (var i = 0; i < rowCount; i++) {
			var start = document.getElementById('protr' + i);
			document.getElementById('hiddenproduct' + i).style.display = 'none';

			console.log(start, "styuiopj")
			start.style.backgroundColor = 'white';
			start.style.color = 'black';
		}
	})
</script>


<script>
	// $(document).ready(function() {
	// 	$('#example13').DataTable();
	// });
</script>
<script>
	const keyDownForProductInfo = (row, e) => {
		//down arrow key
		if (e.keyCode == '40') {
			// var tableRow = document.getElementById('producttbdoy');
			var elmtTable = document.getElementById('dataTable');
			// var tableRow = document.getElementById('producttbdoy');
			var tableRows = elmtTable.getElementsByTagName('tr');
			console.log(tableRows, "TableRows");
			var rowCount = tableRows.length - 1;
			// console.log(rowCount, "rowCounter")
			// console.log(row, "row")

			if (rowCount > row) {
				if (row < 0) {
					row = 0;
				}
				row = row + 1;
				previrow = row - 1;
				console.log(row, "tr");
				var start = document.getElementById('tr' + row);
				var previous = document.getElementById('tr' + previrow);

				// console.log(previous, "previousrow");
				// // var searchparty = document.getElementById('searchproduct').value;
				// console.log(start, "start");
				// if (previous) {
				// 	previous.style.backgroundColor = 'white';
				// 	previous.style.color = 'black';
				// 	// }
				// }
				// }
				// console.log(start,"startrowcount")
				start.focus();
				// start.style.backgroundColor = 'blue';
				// start.style.color = 'white';

			} else {
				// row = row - 1;
				var start = document.getElementById('tr' + row);
				// console.log(start, "eleswala");
				start.focus();
				// start.style.backgroundColor = 'blue';
				// start.style.color = 'white';
			}
		}
	}
	const keyDownForProduct = (e, row) => {
		//down arrow key
		if (e.keyCode == '40') {
			console.log(row, "rowpaykyah");
			var elmtTable = document.getElementById('example13');
			var tableRow = document.getElementById('producttbdoy');
			var tableRows = tableRow.getElementsByTagName('tr');
			console.log(tableRows, "TableRows");
			var rowCount = tableRows.length - 1;
			console.log(rowCount, "rowCounter")
			console.log(row, "row")

			if (rowCount > row) {
				if (row < 0) {
					row = 0;
				}
				row = row + 1;
				previrow = row - 1;
				console.log(row, "tr");
				var start = document.getElementById('protr' + row);
				var previous = document.getElementById('protr' + previrow);

				console.log(previous, "previousrow");
				// var searchparty = document.getElementById('searchproduct').value;
				console.log(start, "start");
				if (previous) {
					previous.style.backgroundColor = 'white';
					previous.style.color = 'black';
					// }
				}
				// }
				// console.log(start,"startrowcount")
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
				// alert("keydown")
				// console.log(start);
				// row++;
				// row++;
				// }
			} else {
				// row = row - 1;
				var start = document.getElementById('protr' + row);
				console.log(start, "eleswala");
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
			}
		}
	}
	const keyUpForProduct = (e, row) => {
		// function keyUpForProduct(e, row) {
		//up arrow key
		if (e.keyCode == '38') {
			if (row == 0) {
				var previous = document.getElementById('protr' + row);
				previous.style.backgroundColor = 'white';
				previous.style.color = 'black'
				document.getElementById("searchproduct").focus();
				return;

			}


			console.log(row, "rowpaykyahupwalep");
			var elmtTable = document.getElementById('example13');
			// var tableRow = document.getElementById('producttbdoy');
			var tableRows = elmtTable.getElementsByTagName('tr');
			console.log(tableRows, "TableRows");
			var rowCount = tableRows.length - 1;
			console.log(rowCount, "rowCounter")
			console.log(row, "row")
			previrow = '';
			if (rowCount > row) {
				if (row <= 0) {
					row = 0;
					// previrow = row + 1;

				} else {
					row = row - 1;
					previrow = row + 1;
				}

				console.log(row, "tr");
				var start = document.getElementById('protr' + row);
				if (previrow) {
					var previous = document.getElementById('protr' + previrow);

					console.log(previous, "previousrow");
					// var searchparty = document.getElementById('searchproduct').value;
					console.log(start, "start");
					if (previous) {
						previous.style.backgroundColor = 'white';
						previous.style.color = 'black';
					}
				}
				// }
				// }
				// console.log(start,"startrowcount")
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
				// alert("keydown")
				// console.log(start);
				// row++;
				// row++;
				// }
			}
		}
	}

	const keyDownForParty = (e, row) => {
		// function keyDownForParty(e, row) {
		//down arrow key
		// alert("yes");

		if (e.keyCode == '40') {
			console.log(row, "rowpaykyah");
			var elmtTable = document.getElementById('example12');
			var tableRow = document.getElementById('tbody');
			var tableRows = tableRow.getElementsByTagName('tr');
			console.log(tableRows, "TableRows");
			var rowCount = tableRows.length - 1;
			console.log(rowCount, "rowCounter")
			console.log(row, "row")

			if (rowCount > row) {
				if (row < 0) {
					row = 0;
				}
				row = row + 1;
				previrow = row - 1;
				console.log(row, "tr");
				var start = document.getElementById('trp' + row);
				var previous = document.getElementById('trp' + previrow);

				console.log(previous, "previousrow");
				// var searchparty = document.getElementById('searchproduct').value;
				console.log(start, "start");
				if (previous) {
					previous.style.backgroundColor = 'white';
					previous.style.color = 'black';
					// }
				}
				// }
				// console.log(start,"startrowcount")
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
				// alert("keydown")
				// console.log(start);
				// row++;
				// row++;
				// }
			} else {
				// row = row - 1;
				var start = document.getElementById('tr' + row);
				console.log(start, "eleswala");
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
			}
		}
	}
	const keyUpForParty = (e, row) => {
		// function keyUpForParty(e, row) {
		//up arrow key
		if (e.keyCode == '38') {
			if (row == 0) {
				var previous = document.getElementById('trp' + row);
				previous.style.backgroundColor = 'white';
				previous.style.color = 'black'
				document.getElementById('searchparty').focus();
				return;
			}
			console.log(row, "rowpaykyahupwalep");
			var elmtTable = document.getElementById('example12');
			// var tableRow = document.getElementById('producttbdoy');
			var tableRows = elmtTable.getElementsByTagName('tr');
			console.log(tableRows, "TableRows");
			var rowCount = tableRows.length - 1;
			console.log(rowCount, "rowCounter")
			console.log(row, "row")
			previrow = '';
			if (rowCount > row) {
				if (row <= 0) {
					row = 0;
					// previrow = row + 1;

				} else {
					row = row - 1;
					previrow = row + 1;
				}

				console.log(row, "tr");
				var start = document.getElementById('trp' + row);
				if (previrow) {
					var previous = document.getElementById('trp' + previrow);

					console.log(previous, "previousrow");
					// var searchparty = document.getElementById('searchproduct').value;
					console.log(start, "start");
					if (previous) {
						previous.style.backgroundColor = 'white';
						previous.style.color = 'black';
					}
				}
				// }
				// }
				// console.log(start,"startrowcount")
				start.focus();
				start.style.backgroundColor = 'blue';
				start.style.color = 'white';
				// alert("keydown")
				// console.log(start);
				// row++;
				// row++;
				// }
			}
		}
	}


	function keyDownProduct(e) {
		if (e.keyCode == '40') {
			var start = document.getElementById('protr0');
			start.focus();
			start.style.backgroundColor = 'blue';
			start.style.color = 'white';

		}
	}

	function keyDown(e) {

		if (e.keyCode == '40') {
			document.getElementById('searchparty').blur();

			var start = document.getElementById('trp0');
			start.focus();
			start.style.backgroundColor = 'blue';
			start.style.color = 'white';
			console.log(start);

		}

	}

	function getProducts() {
		$.ajax({
			url: baseurl + "index.php/product/getproducts",
			method: "GET",
			success: function(products) {
				var products = JSON.parse(products);
				if (products.status == true) {
					prepareProductsDropdown(products.data.product, products.data.productCode);
					if ("" != null && "" !== "") {
						$("#invoiceNumber").val("");
						$("#invoiceNumber").trigger("change");
					}
					//("=====Prepare======");
				}
				// $('.searchselect').select2();

			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permissio n denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	}


	const onProductSelect = (id, type = "Sale") => {
		var rowNo = document.getElementById('prorow').value;
		const productId = id;
		// alert(rowNo);
		console.log(rowNo);
		// alert(productId);

		if (productId == "" || productId == null) {
			onRemoveRowHandler(rowNo);
			return;
		}
		document.getElementById('searchproduct').value = '';
		var start = document.getElementById('protr' + rowNo);
		// start.focus();
		if (start) {
			console.log(start, "styuiopj")
			start.style.backgroundColor = 'white';
			start.style.color = 'black';
		}


		// $(this).show('true');
		$('#editProduct' + rowNo).html('<a href="#" onclick="productForEdit(' + rowNo + ',' + productId +
			')"><i class="fas fa-edit" style="width:41px;" title="Product Edit"></i></a>');
		// document.getElementById('qty' + rowNo).focus();

		// $('#editProduct' + rowNo).html('<a href="#" onclick="productForEdit()"><i class="fas fa-edit" title="Product Edit"></i></a>');
		$.ajax({
			method: "GET",
			url: baseurl + "index.php/product/getProduct/" + productId,
			success: function(product) {
				var product = JSON.parse(product);
				var product = product.data;
				$("#product" + rowNo).attr("name", product.p_name);
				// $('.searchselect').select2();
				//("yes");
				setSelectedProductDetails(rowNo, product, type);
				// setSelectedProductDetailsEdit(rowNo, product);
				// $('.searchselect').select2();
				document.getElementById('exampleModalLong1').click();
				document.getElementById("qty" + rowNo).focus();
				var search = $('#searchproduct').val();

				$.ajax({
					method: "POST",
					// url: baseurl + "get-customer",
					url: baseurl + "index.php/product/searchproduct",
					data: {
						name: search,
					},
					success: function(customer) {
						var customer = JSON.parse(customer);
						if (customer.status == true) {
							document.getElementById('producttbdoy').innerHTML = '';
							//(customer, "details");
							var da = customer.data;
							myHtml = '';
							if (da.party) {
								$.each(da.party, function(i, item) {
									getTaxDatas(item.tax, i);
									getUnitDatas(item.unit, i);


									$("#producttbdoy").append("<tr tabindex=" + -1 +
										" id=protr" + i +
										" onkeyup='keyUpForProduct(event," + i +
										")' onkeydown='keyDownForProduct(event," +
										i + ")'  onkeypress='onProductSelect(" +
										item.id + ")' onclick='onProductSelect(" +
										item.id + ")'><td>" + item.p_name +
										"</td>" +
										"<td>" + item.price + "</td>" +
										// "<td>" + item.mrp + "</td>" +
										"<td><a href='#' onmouseover='productProfileEdit(" +
										i + "," + item.id + ")'>Edit</a></td></tr>" +
										"<tr style='display: none;width:120%;' id=hiddenproduct" + i +
										"><td><div  id=errorpro" + i + " style='opacity: 500; display: none;'></div>" +

										"<div class='row'>" +

										"<form action='<?= base_url() ?>product/updateproduct' id=id_pata_nahi" + i + " method='post' onsubmit='onFormProductSubmitHandlers(event," + i + ")'>" +
										// "<h1>Product Update</h1>" +
										"<div class='row'>" +

										"<input type='hidden' name='id' value=" + item.id + " id=product_id" + i + ">" +

										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Name<span class='text-danger'>*</span></label>" +
										"																								<input autocomplete='off' type='text' name='name' id='name'  placeholder='Name' value='" + item.p_name + "' class='form-control' required>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>BARCODE<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='barcode' id='barcode'  placeholder='BARCODE' value='" + item.qrCode + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div id=protax" + i + " class='form-group col-md-3'></div>" +
										"<div id=unit" + i + " class='form-group col-md-3'></div>" +

										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>MRP<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='mrp' id='mrp'  placeholder='MRP' value='" + item.mrp + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Selling Price<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='Purchase_rate' id='Purchase_rate'  placeholder='Product Selling Price' value='" + item.price + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Purchase Price<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='price' id='price'  placeholder='Purchase Price' value='" + item.purchase_rate + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>QTY<span class='text-danger'>*</span></label>" +
										"																								<input autocomplete='off' type='text' name='qty' id='qty'  placeholder='Product Qunatity' value='" + item.qty + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Discount%<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='number' name='discount' id='discount'  placeholder='Product Discount' value='" + item.discount + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>HSN<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='hsn' id='hsn'  placeholder='HSN Code' value='" + item.hsn + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>MIN Qty<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='min_qty' id='min_qty'  placeholder='MIN Qty' value='" + item.min_qty + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>HSN<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='weight' id='weight'  placeholder='Weight' value='" + item.weight + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										// "<div class='form-group col-sm-3'>" +
										// "																								<label for='image'>Image<span class='text-danger'></span></label>" +
										// "																								<input autocomplete='off' type='file' name='image' id='image'  placeholder='Image' value='" + item.image + "' class='form-control'>" +
										// "																								" +
										// "" +
										// "																								</span>" +
										// "</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Short Description<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='short_desc' id='short_desc'  placeholder='SHort Description' value='" + item.short_de + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Long Description<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='long_desc' id='long_desc'  placeholder='Long Description' value='" + item.long_de + "' class='form-control'>" +
										"																								" +
										"" +
										"																								</span>" +
										"</div>" +
										"</div>" +
										"<button class='btn btn-success' type='submit'>Update</button>" +
										"<a href='#' id=proclick" + i + " onclick='productProfileEdit(" + i + "," + item.id + ")' class='btn btn-danger'>Close</button>" +

										"</form>" +
										"</td>" +
										"</tr>"


									)
								});

							} else {
								$('#searchproduct').focus();

							}
						}
					}
				})




			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					// console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};

	const showPartyPop = (e) => {
		jQuery.noConflict();
		$('#exampleModalLong').modal('show');
		document.getElementById('searchparty').focus();
	}

	$(document).ready(function() {
		$('#partyname').on('focus', function() {
			// $('#exampleModalLong').on('shown.bs.modal', function() {
			$('#exampleModalLong').modal('show');

			// $().find('[autofocus]').focus();

		});
	})

	$(document).ready(function() {
		$('#searchproduct').on('focus', function() {
			// $('#exampleModalLong').on('shown.bs.modal', function() {
			$('#exampleModalLong1').modal('show');

			// $().find('[autofocus]').focus();

		});
	})

	// $(document).ready(function() {
	// 	$('#cityCustomer').on('focus', function() {
	// 		alert("hello");
	// 		// $('#exampleModalLong').on('shown.bs.modal', function() {
	// 		$('#exampleModalLong2').modal('show');

	// 		// $().find('[autofocus]').focus();

	// 	});
	// });

	$(document).ready(function() {
		$('#exampleModalLong').on('shown.bs.modal', function() {
			$('#searchparty').trigger('focus');
		});
	});


	$(document).ready(function() {
		$('#exampleModalLong1').on('shown.bs.modal', function() {
			$('#searchproduct').trigger('focus');
		});
	});

	const getAccountGroup = (id, i) => {

		$.ajax({
			method: "GET",
			url: baseurl + "index.php/accounting_group/groupfind/" + id,
			success: function(product) {
				// var product = JSON.parse(product);
				// console.log(product, "state_info");
				document.getElementById('group' + i).innerHTML = product;
				// if (product.status == true) {

				// ("#state_select").html(product);
				// }

			}
		})
	}
	// $('#cityCustomer').change(function() {
	// 	var cityname = ("#cityCustomer").val();
	// 	if (cityname != '') {
	// 		$.ajax({
	// 			method: "GET",
	// 			url: baseurl + "index.php/district/alldistricts/" + id,
	// 			success: function(product) {
	// 				// var product = JSON.parse(product);
	// 				// console.log(product, "state_info");
	// 				document.getElementById('group' + i).innerHTML = product;
	// 				if (product.status == true) {
	// 					("#hiddencity").html(<li>Hello</li>);
	// 				}else
	// 				{

	// 				}

	// 			}
	// 		})
	// 	} else {
	// 		document.getElementById('hiddencity').value = '';

	// 		document.getElementById('cityCustomer').innerHTML = '';


	// 	}
	// });


	// 	const getAccountGroup = (id, i) => {

	// $.ajax({
	// 	method: "GET",
	// 	url: baseurl + "index.php/accounting_group/groupfind/" + id,
	// 	success: function(product) {
	// 		// var product = JSON.parse(product);
	// 		// console.log(product, "state_info");
	// 		document.getElementById('group' + i).innerHTML = product;
	// 		// if (product.status == true) {

	// 		// ("#state_select").html(product);
	// 		// }

	// 	}
	// })
	// }

	const getCity = (id, i) => {

		$.ajax({
			method: "GET",
			url: baseurl + "index.php/district/cityfind/" + id,
			success: function(product) {
				// var product = JSON.parse(product);
				// console.log(product, "state_info");
				document.getElementById('city_select' + i).innerHTML = product;
				// if (product.status == true) {

				// ("#state_select").html(product);
				// }

			}
		})
	}
	const getStateSelects = (id, i) => {

		$.ajax({
			method: "GET",
			url: baseurl + "index.php/state/statefind/" + id,
			success: function(product) {

				// var product = JSON.parse(product);
				// console.log(product, "state_info");
				document.getElementById('state_select' + i).innerHTML = product;
				// if (product.status == true) {

				// ("#state_select").html(product);
				// }

			}
		})
	}

	const gstType = (id, i) => {

		$.ajax({
			method: "GET",
			url: baseurl + "index.php/party/gstType/" + id,
			success: function(product) {
				// var product = JSON.parse(product);
				// console.log(product, "state_info");
				document.getElementById('gstType' + i).innerHTML = product;
				// if (product.status == true) {

				// ("#state_select").html(product);
				// }

			}
		})
	}


	// $(document).ready(function() {
	// 	$('#exampleModalLong2').on('shown.bs.modal', function() {
	// 		$('#searchcity').trigger('focus');
	// 	});
	// });

	$(document).ready(function() {
		$('#exampleModalLong3').on('shown.bs.modal', function() {
			$('#searchstate').trigger('focus');
		});
	});

	const getCustomerDetail = (customerId, rowId = null) => {
		$.ajax({
			method: "POST",
			// url: baseurl + "get-customer",
			url: baseurl + "index.php/party/getCustomer",

			data: {
				id: customerId,
			},
			success: function(customer) {
				var customer = JSON.parse(customer);
				setSelectedCustomerDetails(customer.data);
				// document.getElementById('trp'+row).blur();
				var start = document.getElementById('trp' + rowId);
				console.log(start, "cus")
				start.style.backgroundColor = 'white';
				start.style.color = 'black';

				document.getElementById('searchparty').value = '';
				var search = $('#searchparty').val();
				document.getElementById('closepartymodel').click();


				$.ajax({
					method: "POST",
					// url: baseurl + "get-customer",
					url: baseurl + "index.php/party/searchparty",
					data: {
						name: search,
					},
					success: function(customer) {
						var customer = JSON.parse(customer);
						if (customer.status == true) {
							// $("#tbody").innerHTML='';
							document.getElementById('tbody').innerHTML = '';
							//(customer, "details");
							var da = customer.data;
							// console.log(da, "Aman ka Data");

							myHtml = '';
							if (da.party) {
								$.each(da.party, function(i, item) {
									getAccountGroup(item.group_head, i);
									getCity(item.city, i);

									getStateSelects(item.state, i);
									opBalnceType(item.opBalnceType, i);
									gstType(item.gstType, i);


									$("#tbody").append("<tr tabindex='-1' id=trp" + i +
										" onkeypress='getCustomerDetail(" + item
										.id + "," + i +
										")' onkeyup='keyUpForParty(event," + i +
										")' onkeydown='keyDownForParty(event," + i +
										")' onclick='getCustomerDetail(" + item.id +
										"," + i + ")'><td>" + item.name + "</td>" +
										"<td>" + item.contact + "</td>" +
										"<td>" + item.email + "</td>" +
										"<td><a href='#' onmouseover='partyProfileEdit(" +
										i + "," + item.id +
										",event)'>Edit</a></td></tr>" +
										"<tr style='display: none;width:142%;' id=hiddendata" + i +
										"><td><div  id=errormsg" + i + " style='opacity: 500; display: none;'></div>" +

										"<div class='row'>" +

										"<form action='<?= base_url() ?>party/updateparty' id=fom" + i + " method='post' onsubmit='onFormPartySubmitHandlers(event," + i + ")'>" +
										"<div class='row'>" +

										"<input type='hidden' name='id' value=" + item.id + " id=partyId" + i + ">" +
										"<div id=group" + i + " class='form-group col-md-3'></div>" +


										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Name<span class='text-danger'>*</span></label>" +
										"																								<input autocomplete='off' type='text' name='name' id='name'  placeholder='Name' value='" + item.name + "' class='form-control' required>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Contact No<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='tel' name='contact' id='contact'  placeholder='Contact' value='" + item.contact + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Email<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='email' name='email' id='email'  placeholder='Name' value='" + item.email + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Address<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='address_1' id='address_1'  placeholder='Name' value='" + item.address_1 + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>PIN<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='pin' id='pin'  placeholder='Name' value='" + item.pin + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>PAN<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='pan' id='pan'  placeholder='PAN' value='" + item.pan + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Credit Limit<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='credit_limit' id='credit_limit'  placeholder='Credit Limit' value='" + item.credit_limit + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>GST No<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='gst' id='gstNo'  placeholder='GST No' value='" + item.gst + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>Opening Balance<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='opening_balance' id='opening_balance'  placeholder='Opening balance' value='" + item.opening_balance + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +
										"<div id=state_select" + i + " class='form-group col-md-3'></div>" +
										"<div id=city_select" + i + " class='form-group col-md-3'></div>" +
										"<div id=opBalnceType" + i + " class='form-group col-md-3'></div>" +
										"<div id=gstType" + i + " class='form-group col-md-3'></div>" +

										"<div class='form-group col-sm-3'>" +
										"																								<label for='name'>State Code<span class='text-danger'></span></label>" +
										"																								<input autocomplete='off' type='text' name='state_code' id='state_code' readonly  placeholder='State Code' value='" + item.state_code + "' class='form-control'>" +
										"																								<span class='col-sm-3 control-label'>" +
										"																								" +
										"" +
										"																								</span>" +
										"																							</div>" +

										"</div>" +
										"<button class='btn btn-danger' onclick='partyProfileEdit(" + i + "," + item.id + ",event)' id=clik" + i + " type='button'>Close</button>" +

										"<button class='btn btn-success' type='submit'>Update</button>" +
										"</form>" +
										"</td>" +
										"</tr>"
									)
								});

							} else {
								$('#searchparty').focus();
							}
						} else {
							$('#searchparty').focus();

						}
					}
				})


				// $("#searchparty").trigger("change");
				// document.getElementById("searchparty").change;
				// console.log("trigger");

				// $("#tbody2").attr("style", "display:block")
				// $("#tbody").attr("style", "display:none")


				// document.getElementById('searchparty').value ='A';
				// document.getElementById('searchparty').value = '';


				// $('#searchparty').trigger();
				// $('#searchparty').trigger('change')
				// $("#searchparty").trigger("click");






			},
			error: function(jqXHR, exception) {
				var msg = "";
				if (jqXHR.status === 0) {
					msg = "Not connect.\n Verify Network.";
				} else if (jqXHR.status == 401) {
					msg = "Unauthorized User. [" + jqXHR.status + "]";
					window.location.href = "/gst/signout";
				} else if (jqXHR.status == 403) {
					msg = "Permission denied. [403]";
				} else if (jqXHR.status == 404) {
					msg = "Requested page not found. [404]";
				} else if (jqXHR.status == 406) {
					msg = jqXHR.responseText;
				} else if (jqXHR.status == 500) {
					msg = "Internal Server Error [500].";
				} else if (exception === "parsererror") {
					msg = "Requested JSON parse failed.";
				} else if (exception === "timeout") {
					msg = "Time out error.";
				} else if (exception === "abort") {
					msg = "Ajax request aborted.";
				} else {
					msg = "Uncaught Error.\n";
					console.error(jqXHR.responseText);
				}
				Swal.fire({
					position: "top-end",
					icon: "error",
					title: msg,
					showConfirmButton: false,
					timer: 2000,
				});
			},
		});
	};
	$('#searchparty').keyup(function() {
		// $('#consigneestatecode').val(code);
		var search = $('#searchparty').val();
		if (search.length != '') {

			$.ajax({
				method: "POST",
				// url: baseurl + "get-customer",
				url: baseurl + "index.php/party/searchparty",
				data: {
					name: search,
				},
				success: function(customer) {
					var customer = JSON.parse(customer);
					if (customer.status == true) {
						// $("#tbody").innerHTML='';
						document.getElementById('tbody').innerHTML = '';
						//(customer, "details");
						var da = customer.data;
						console.log(da);

						myHtml = '';
						if (da.party) {
							$.each(da.party, function(i, item) {
								getAccountGroup(item.group_head, i);
								getCity(item.city, i);

								getStateSelects(item.state, i);
								opBalnceType(item.opBalnceType, i);
								gstType(item.gstType, i);


								$("#tbody").append("<tr tabindex='-1' id=trp" + i +
									" onkeypress='getCustomerDetail(" + item
									.id + "," + i +
									")' onkeyup='keyUpForParty(event," + i +
									")' onkeydown='keyDownForParty(event," + i +
									")' onclick='getCustomerDetail(" + item.id +
									"," + i + ")'><td>" + item.name + "</td>" +
									"<td>" + item.contact + "</td>" +
									"<td>" + item.email + "</td>" +
									"<td><a href='#' onmouseover='partyProfileEdit(" +
									i + "," + item.id +
									",event)'>Edit</a></td></tr>" +
									"<tr style='display: none;width:142%;' id=hiddendata" + i +
									"><td><div  id=errormsg" + i + " style='opacity: 500; display: none;'></div>" +

									"<div class='row'>" +

									"<form action='<?= base_url() ?>party/updateparty' id=fom" + i + " method='post' onsubmit='onFormPartySubmitHandlers(event," + i + ")'>" +
									"<div class='row'>" +

									"<input type='hidden' name='id' value=" + item.id + " id=partyId" + i + ">" +
									"<div id=group" + i + " class='form-group col-md-3'></div>" +


									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Name<span class='text-danger'>*</span></label>" +
									"																								<input autocomplete='off' type='text' name='name' id='name'  placeholder='Name' value='" + item.name + "' class='form-control' required>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Contact No<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='tel' name='contact' id='contact'  placeholder='Contact' value='" + item.contact + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Email<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='email' name='email' id='email'  placeholder='Name' value='" + item.email + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Address<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='address_1' id='address_1'  placeholder='Name' value='" + item.address_1 + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>PIN<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='pin' id='pin'  placeholder='Name' value='" + item.pin + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>PAN<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='pan' id='pan'  placeholder='PAN' value='" + item.pan + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Credit Limit<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='credit_limit' id='credit_limit'  placeholder='Credit Limit' value='" + item.credit_limit + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>GST No<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='gst' id='gstNo'  placeholder='GST No' value='" + item.gst + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>Opening Balance<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='opening_balance' id='opening_balance'  placeholder='Opening balance' value='" + item.opening_balance + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +
									"<div id=state_select" + i + " class='form-group col-md-3'></div>" +
									"<div id=city_select" + i + " class='form-group col-md-3'></div>" +
									"<div id=opBalnceType" + i + " class='form-group col-md-3'></div>" +
									"<div id=gstType" + i + " class='form-group col-md-3'></div>" +

									"<div class='form-group col-sm-3'>" +
									"																								<label for='name'>State Code<span class='text-danger'></span></label>" +
									"																								<input autocomplete='off' type='text' name='state_code' id='state_code' readonly  placeholder='State Code' value='" + item.state_code + "' class='form-control'>" +
									"																								<span class='col-sm-3 control-label'>" +
									"																								" +
									"" +
									"																								</span>" +
									"																							</div>" +

									"</div>" +
									// "<button class='btn btn-danger'  id=clik"+i+"'>Close</button>" +
									"<button class='btn btn-danger' type='button' onclick='partyProfileEdit(" + i + "," + item.id + ",event)' id=clik" + i + ">Close</button>" +

									"<button class='btn btn-success' type='submit'>Update</button>" +
									"</form>" +
									"</td>" +
									"</tr>"
								)
							});

						} else {
							$('#searchparty').focus();
						}
					} else {
						$('#searchparty').focus();

					}
				}
			})

		} else {
			$('#searchparty').focus();

		}


	});
	// var trpro = 0;

	// function keyUpProduct(e) {

	// 	if (e.keyCode == '38') {
	// 		var elmtTable = document.getElementById('example13');
	// 		var tableRows = elmtTable.getElementsByTagName('tr');
	// 		var rowCount = tableRows.length;

	// 		// for (var i = 0; rowCount > tr;)
	// 		if (rowCount >= trpro) {
	// 			trpro = --trpro;
	// 			if (trpro < 0) {
	// 				trpro = 0;
	// 			}
	// 			var start = document.getElementById('tr' + trpro);
	// 			console.log(start, "start");
	// 			// if (tr <= 0) {

	// 			var next = start.previousSibling.previousElementSibling;
	// 			console.log(next, "next");
	// 			if (next != null) {
	// 				console.log(next, "previouskeyup");
	// 				next.focus();
	// 				next.style.backgroundColor = 'blue';
	// 				next.style.color = 'white';
	// 				// }
	// 			}
	// 			start.focus();
	// 			start.style.backgroundColor = 'white';
	// 			start.style.color = '#040404';
	// 			// alert("keydown")
	// 			console.log(start, "focus");
	// 		} else {
	// 			document.getElementById('tr0').focus();
	// 		}
	// 	} else {
	// 		document.getElementById('tr0').focus();

	// 	}


	// }



	// 	function scrollInView() {
	// 		var target = $('#example12');
	// 		if (target.length) {
	// 			var top = target.offset().top;

	// 			$('#example12').stop().animate({
	// 				scrollTop: top - 100
	// 			}, 400);
	// 			return false;
	// 		}
	// 	}


	// }


	// function ProductCodeKeyPress(event, rowNo) {
	// 	if (event.keyCode == 13) {

	// 		// onAddNewRowHandler();
	// 		// alert(rowNo);
	// 		// console.log(event.target.value);

	// 		document.getElementById("p_code" + rowNo).disabled = true;
	// 		var firstrow = rowNo;
	// 		rowNo = rowNo + 1;
	// 		// 	console.log(rowNo,"roeNoonkljhhhjh")
	// 		setTimeout(function() {
	// 		document.getElementById('exampleModalLongTitle').click();

	// 			document.getElementById("p_code" + firstrow).disabled = false;
	// 				document.getElementById('p_code' + rowNo).focus();
	// 				alert("hello");

	// 		}, 1000);

	// return false;

	// return true;
	// forProductCode(rowNo);


	// }
	// }


	// function forProductCode(rowNo)
	// {

	// }

	function enterKeyPressed(event) {
		if (event.keyCode == 13) {
			var search = $('#searchparty').val();
			if (search.length != '') {
				// $("trp0").click();
				document.getElementById("trp0").click();

				// console.log("okk")
			} else {
				document.getElementById("trp0").click();

			}

		}
	}

	// function enterKeyPresseds(event) {
	// 	if (event.keyCode == 13) {
	// 		var search = $('#searchparty').val();
	// 		if (search.length != '') {
	// 			$("trp0").click();
	// 			console.log("okk")
	// 			return true;

	// 		} else {
	// 			// $('#searchparty').focus();
	// 			// alert("ye");
	// 			document.getElementById("trp0").click();

	// 		}

	// 	}
	// }

	function enterKey(event) {
		if (event.keyCode == 13) {
			var search = $('#searchproduct').val();
			if (search.length != '') {
				// $("tr[tabindex=0]").click();
				document.getElementById('protr0').click();
				// console.log("okk")
				return true;
			} else {
				// document.getElementById('protr0').click();
				var roCount = document.getElementById("prorow").value;
				var netAmount = document.getElementById('netAmount' + roCount);
				if (netAmount != '') {
					onRemoveRowHandler(roCount);
				}
				// }
				document.getElementById("exampleModalLong1").click();
				// document.getElementById("btnSubmit").click();

				onFormSubmitHandler(event);

			}

		}
	}

	function amanEnter(event) {
		// alert("hello");
		// if (event.keyCode == 13) {
		// document.getElementById('btnSave').focus();
		// var lastRow = table.rows[ table.rows.length - 1 ];
		var elmtTable = document.getElementById('dataTable');
		var tableRows = elmtTable.getElementsByTagName('tr');
		var rowCount = tableRows.length - 1;

		jumpIntoAnother(rowCount);

		// }
	}

	function jumpIntoAnother(rowCount) {
		<?php
		$barcode = $this->session->userdata('barcode');

		if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
		?>

			document.getElementById("p_code1").click();
		<?php
		} else {
		?>
			// var vall=document.getElementById("productname" + rowCount).value;
			// alert(vall);
			document.getElementById("productname1").click();

		<?php
		}
		?>
	}

	function chnageDirection(event) {
		if (event.keyCode == 37) {
			//left
			// alert("left click");
			document.getElementById('btnSave').focus();

			var start = document.getElementById('btnSave');

			start.style.backgroundColor = 'blue';
			return false;

			// console.log("okk")

		}
		if (event.keyCode == 39) {
			//right
			// alert("right click");

			var start = document.getElementById('btnSave');

			start.style.backgroundColor = 'white';
			start.style.color = 'black';

			document.getElementById('btnSaveAndPrint').focus();
			var start = document.getElementById('btnSaveAndPrint');
			start.style.backgroundColor = 'blue';
			// alert("pAndT")
			return false;
			// 			setTimeout(function() {
			// 				return true;

			// }, 1000);

			// document.getElementById('btnSave').focus();

		}
	}

	function keyUp(e) {

		// 	// var active = 0;
		// 	// // $('#example12 tr').each(function(idx) {
		// 	// // 	$(this).html(idx);
		// 	// // });
		// 	// rePosition();

		// 	// $(document).keydown(function(e) {
		// 	// 	reCalculate(e);
		// 	// 	rePosition();
		// 	// 	return false;
		// 	// });

		// 	// $('td').click(function() {
		// 	// 	active = $(this).closest('table').find('td').index(this);
		// 	// 	rePosition();
		// 	// });


		// 	// function reCalculate(e) {
		// 	// 	var rows = $('#example12 tr').length;
		// 	// 	var columns = $('#example12 tr:eq(0) td').length;
		// 	// 	//alert(columns + 'x' + rows);

		// 	// 	if (e.keyCode == 37) { //move left or wrap
		// 	// 		active = (active > 0) ? active - 1 : active;
		// 	// 	}
		// 	// 	if (e.keyCode == 38) { // move up
		// 	// 		active = (active - columns >= 0) ? active - columns : active;
		// 	// 	}
		// 	// 	if (e.keyCode == 39) { // move right or wrap
		// 	// 		active = (active < (columns * rows) - 1) ? active + 1 : active;
		// 	// 	}
		// 	// 	if (e.keyCode == 40) { // move down
		// 	// 		active = (active + columns <= (rows * columns) - 1) ? active + columns : active;
		// 	// 	}
		// 	// }

		// 	// function rePosition() {
		// 	// 	$('.active').removeClass('active');
		// 	// 	$('#example12 tr0').eq(active).addClass('active');
		// 	// 	scrollInView();
		// 	// }

		// 	// function scrollInView() {
		// 	// 	var target = $('#example12 tr0:eq(' + active + ')');
		// 	// 	if (target.length) {
		// 	// 		var top = target.offset().top;

		// 	// 		$('html,body').stop().animate({
		// 	// 			scrollTop: top - 100
		// 	// 		}, 400);
		// 	// 		return false;
		// 	// 	}
		// 	// }


		// 	// if (evt.keyCode == 40) {
		// 	// 	event.preventDefault();
		// 	// 	var cellindex = $(this).index()
		// 	// 	console.log(cellindex, "cellindex");
		// 	// 	var rowindex = $(this).parents('tr').index() + 1
		// 	// 	console.log(rowindex, "rowindex");

		// 	// 	$(this).parents('table').find('tr:eq(' + rowindex + ') td:eq(' + cellindex + ')').focus()
		// 	// } else if (evt.keyCode == 38) {
		// 	// 	event.preventDefault();
		// 	// 	var cellindex = $(this).index();
		// 	// 	// console.log(cellindex, "cellindex");

		// 	// 	var rowindex = $(this).parents('tr').index() - 1
		// 	// 	// console.log(rowindex, "rowindex");

		// 	// 	$(this).parents('table').find('tr:eq(' + rowindex + ') td:eq(' + cellindex + ')').focus()
		// 	// }


		// 	// $('table#example12 tbody tr').first().addClass('blue');

		// 	// $(document).keyup(function(event) {
		// 	// 	var pressed = event.key;
		// 	// 	if (pressed == 'ArrowUp') {
		// 	// 		console.log(pressed);
		// 	// 		var lastCheck = $('table#example12 tbody tr').last().hasClass('red');
		// 	// 		console.log(pressed);

		// 	// 		if (lastCheck) return;
		// 	// 		else $('table#example12 tbody tr.red').removeClass('red').next().addClass('red');
		// 	// 	}

		// 	// 	if (pressed == 'ArrowDown') {
		// 	// 		console.log(pressed);

		// 	// 		var firstCheck = $('table#hi tbody tr').first().hasClass('red');
		// 	// 		if (firstCheck) return;
		// 	// 		else $('table#example12 tbody tr.red').removeClass('red').prev().addClass('red');
		// 	// 	}
		// 	// });
		// alert(tr);
		if (e.keyCode == '38') {
			var elmtTable = document.getElementById('example12');
			var tableRows = elmtTable.getElementsByTagName('tr');
			var rowCount = tableRows.length;
			// for (var i = 0; rowCount > tr;)
			if (rowCount >= tr) {
				tr = --tr;
				if (tr < 0) {
					tr = 0;
				}
				var start = document.getElementById('tr' + tr);
				console.log(start, "start");
				// if (tr <= 0) {

				var next = start.previousSibling.previousElementSibling;

				console.log(next, "next");
				// var aman=gettype(tr);
				// console.log(previuous);

				// console.log(aman,"type");
				// start.focus();
				if (next != null) {
					console.log(next, "previouskeyup");

					next.focus();
					next.style.backgroundColor = 'blue';
					next.style.color = 'white';
					// }
				}
				start.focus();
				start.style.backgroundColor = 'white';
				start.style.color = '#040404';
				// alert("keydown")
				console.log(start, "focus");


			} else {
				document.getElementById('tr0').focus();
			}
		} else {
			document.getElementById('tr0').focus();

		}
		// scrollInView();


		// } else if (e.keyCode == '40') {
		// 	// down arrow
		// 	// $(this).closest('tr').next().find('td:eq(' + rowIndex + ')').focus();
		// 	var elmtTable = document.getElementById('example12');
		// 	var tableRows = elmtTable.getElementsByTagName('tr');
		// 	var rowCount = tableRows.length;
		// 	if (rowCount >= tr) {
		// 		if (tr < 0) {
		// 			tr = 0;
		// 		}
		// 		console.log(tr, "tr");
		// 		var start = document.getElementById('tr' + tr);
		// 		console.log(start, "start");
		// 		// if (tr >= 0) {
		// 		var previous = start.previousSibling.previousElementSibling;
		// 		console.log(previous, "previous");

		// 		// var aman=gettype(tr);
		// 		// console.log(previuous);

		// 		// console.log(aman,"type");
		// 		// start.focus();

		// 		if (previous != null) {
		// 			previous.style.backgroundColor = 'white';
		// 			previous.style.color = 'black';
		// 			// }
		// 		}

		// 		start.focus();
		// 		start.style.backgroundColor = 'blue';
		// 		start.style.color = 'white';
		// 		// alert("keydown")
		// 		console.log(tr);
		// 		tr++;


		// var previuous = tr - 1;
		// var start2 = document.getElementById('tr' + previuous);
		// start2.style.backgroundColor = 'white';
		// start2.style.color = 'white';
		// rePosition();

		// function rePosition() {
		// 	// $('.active').removeClass('active');
		// 	$('#example12 tr1').eq(active).addClass('active');
		// 	scrollInView();

		// }

		// function scrollInView() {
		// 	var target = $('#example12 tr1:eq(' + active + ')');
		// 	if (target.length) {
		// 		var top = target.offset().top;

		// 		$('html,body').stop().animate({
		// 			scrollTop: top - 100
		// 		}, 400);
		// 		return false;
		// 	}
		// }
		// scrollInView();
		// $(".someInput").on("keyup", function(e) {
		// 	$(".wrapper").show();
		// 	if (e.which == 40) {
		// 		$('.element:not(:last-child).element-hover').removeClass('element-hover').next().addClass('element-hover');
		// 	} else if (e.which == 38) {
		// 		$('.element:not(:first-child).element-hover').removeClass('element-hover').prev().addClass('element-hover');
		// 	}
		// });

	}

	function changeFormat() {
		var formats = document.getElementById('formats').value;
		// alert(formats);
		document.getElementById('format_invoice').value = formats;


	}
</script>