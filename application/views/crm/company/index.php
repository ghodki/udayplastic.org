<main id="main" class="main">

	<div class="pagetitle">
		<h1>Companies</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
				<li class="breadcrumb-item active">Add Company</li>
			</ol>
		</nav>
	</div><!-- End Page Title -->

	<section class="section dashboard">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">CRM</h5>
				<!-- Large Modal -->
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
					Add Company
				</button>

				<button type="button" class="btn btn-primary" style="margin-left:auto;float:right;">
					<a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
						Inventory Software</a>
				</button>
				<div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
					<div class="modal-dialog modal-xl">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Company</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div id="message" class="text-center"></div>
							<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/company/save" method="POST" enctype="multipart/form-data">
								<div class="modal-body">
									<div class="row text-dark">
										<div class="col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<lable for="name">Company Name<span class="text-danger">*</span></lable>
												<input id="name" name="name" placeholder="Company Name*" type="text" class="form-control" required value="<?= set_value('name') ?>">
											</div>
											<span class="col-sm-4 control-label">
												<?php echo form_error('name'); ?>

											</span>
										</div>
										<!-- <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"> -->
										<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
											<lable for="state">Financial Year<span class="text-danger">*</span></lable>

											<select name="financial_year" id="financial_year" class="form-control" required>
												<option value="">-Select-</option>
												<?php $financial_year = $this->db->get('year')->result();
												if ($financial_year) {
													foreach ($financial_year as $row) { ?>
														<option value="<?= $row->id ?>" <?= set_value('financial_year') == $row->id ? 'selected' : ''; ?>><?= $row->year; ?></option>
												<?php
													}
												}
												?>
											</select>
										</div>
										<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<lable for="number">Mobile No<span class="text-danger"></span></lable>

												<input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= set_value('number') ?>">
											</div>
											<span class="col-sm-4 control-label">
												<?php echo form_error('number'); ?>

											</span>
										</div>
										<div class="col-md-4  col-sm-6 col-lg-4">
											<div class="form-group">
												<lable for="email">Email<span class="text-danger"></span></lable>

												<input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= set_value('email') ?>">
											</div>
											<span class="col-sm-4 control-label">
												<?php echo form_error('email'); ?>

											</span>
										</div>
										<div class="col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<lable for="gst">GST No<span class="text-danger"></span></lable>

												<input name="gst" placeholder="GST Number" type="text" class="form-control" value="<?= set_value('gst') ?>">
											</div>
											<span class="col-sm-4 control-label">
												<?php echo form_error('gst'); ?>

											</span>
										</div>
										<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
											<div class="form-group">
												<lable for="contact">Contact<span class="text-danger"></span></lable>

												<input name="contact" placeholder="Contact person" type="text" class="form-control" value="<?= set_value('contact') ?>">
											</div>
											<span class="col-sm-4 control-label">
												<?php echo form_error('contact'); ?>

											</span>
										</div>
										<div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
											<lable for="state">State Name<span class="text-danger">*</span></lable>

											<select name="state" id="state" class="form-control" required>
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
										<div class="col-md-6 col-sm-6 col-lg-4">
											<div class="form-group">
												<lable for="adddress">Address<span class="text-danger"></span></lable>

												<input name="address" placeholder="Address" type="text" class="form-control" value="<?= set_value('address') ?>">
											</div>
											<span class="col-sm-4 control-label">
												<?php echo form_error('address'); ?>

											</span>
										</div>

										<div class="col-md-4 col-sm-6 col-lg-4">
											<lable for="frmt">Billing Format <span class="text-danger"></span></lable>

											<select id="cate" name="cate_id" class="form-control">

												<option value="">Billing Format</option>
												<?php
												$cate = $this->user_model->getResult('billing_format');
												if ($cate) {
													foreach ($cate as $key => $value) {
												?>
														<option value="<?= $value->id ?>" <?= set_value('cate_id') == $value->id ? 'selected' : ''; ?>><?= $value->name; ?></option>

												<?php	}
												}
												?>
											</select>
											<span class="col-sm-4 control-label">
												<?php echo form_error('cate_id'); ?>

											</span>
										</div>

										<div class="col-md-4 col-sm-6 col-lg-4">
											<lable for="at">Accounting Type<span class="text-danger">*</span></lable>

											<select id="ac" name="ac" class="form-control">
												<option value=''>Accounting Type</option>
												<option value="1">Basic</option>
												<option value="2">Advanced</option>
											</select>
											<span class="col-sm-4 control-label">
												<?php echo form_error('ac'); ?>

											</span>
										</div>
									</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
										<button class="btn btn-primary" type="submit">Save changes</button>
									</div>
								</div>
							</form>


						</div>
					</div>
				</div><!-- End Large Modal-->




			</div>
		</div>
	</section>

	<section class="section">
		<div class="row">
			<div class="col-lg-12">

				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Company List</h5>
						<!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->
						<!-- Table with stripped rows -->
						<table class="table datatable" id="datatable">
							<thead>
								<tr>

									<th scope="col" width="2%">#</th>
									<th scope="col">Date</th>
									<th scope="col">Company Name</th>
									<th scope="col">Email</th>
									<th scope="col">State</th>
									<th scope="col">GST Number</th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (isset($companyData) && $companyData != '') {
									foreach ($companyData as  $key => $value) {
										$date = $value->created_at;
										$newDate = date("d-m-Y", strtotime($date));
								?>
										<tr scope="<?= $key ?>">
											<td>
												<span><?= $key + 1; ?></span>
											</td>
											<td><?= $newDate; ?></td>
											<td>
												<a href="<?= getCrmUrl() . "company/view/" . $value->id ?>" class="nav-link"><?= $value->company_name ?></a>
											</td>
											<td><?= $value->email ?></td>
											<td><?= get_title('state_list', ['id' => $value->state], 'state') ?></td>
											<!-- <td><?= $value->address ?></td> -->
											<td><?= $value->gst_no ?></td>
											<td class="text-right">


												<button class="btn btn-primary" onclick="view(<?= $value->id ?>)"><i class="bi bi-eye"></i></button>

												<button class="btn btn-primary" onclick="companyEdit(<?= $value->id ?>)"><i class="ri-edit-box-line"></i></button>
												<!-- <a href="<?= base_url('inventory_billing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->
											</td>
										</tr>
								<?php
									}
								}
								?>
							</tbody>
						</table>
						<!-- End Table with stripped rows -->

					</div>
				</div>

				<div class="modal fade" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
					<div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabels"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">

							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

</main>
<script>
	$(document).ready(function() {
		$("#signUpForm").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						$("#message").addClass('text-success');
						$('message').removeClass('text-danger');
						$("#message").html(response.message);
						$('#datatable').DataTable().ajax.reload();
					} else {
						$("#message").addClass('text-danger');
						$('message').removeClass('text-success');
						$("#message").html(response.message);

					}


				}
			});
		})
	})


	function companyEdit(productId) {
		jQuery.noConflict();
		$("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/company/edit/" + productId);
		$("#exampleModalLabels").html("Update Company");
		$("#exampleModals").modal("show");

	}
</script>