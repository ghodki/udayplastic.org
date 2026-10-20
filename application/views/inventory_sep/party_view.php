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
									<h5>View Party</h5>
								</div>
								<div class="col-md-6">
									<a href="<?= base_url() ?>invent/party" class="btn btn-primary float-right">Add Party
									</a>
									<circle cx="12" cy="12" r="10"></circle>
									<polyline points="12 8 8 12 12 16"></polyline>
									<line x1="16" y1="12" x2="8" y2="12"></line>
									</svg></a>
								</div>
							</div>
							<table class="table table-striped table-bordered ">
								<tbody>
									<tr>
										<th>Name :</th>
										<td><?= $party->name; ?></td>
										<th>Contact :</th>
										<td><?= $party->contact; ?></td>

									</tr>
									<tr>
										<th>Email :</th>
										<td><?= $party->email; ?></td>

										<th>Pan No. :</th>
										<td><?= $party->pan; ?></td>

									</tr>
									<tr>
										<th>Type :</th>
										<td><?= $party->opBalnceType; ?></td>

										<th>Address 1 :</th>
										<td><?= $party->address_1; ?></td>


									</tr>

									<tr>
										<th>State :</th>
										<?php
										$where = ['id' => $party->state];
										$state = $this->user_model->getRow('state_list', $where);
										if ($state) {
											$name = $state->state;
										} else {
											$name = "Deleted";
										}
										?>
										<td><?= $name; ?></td>

										<th>State Code :</th>
										<!-- <td>23</td> -->
										<td><?= $party->state_code; ?></td>

									</tr>
									<tr>
										<th>GST Type. :</th>
										<td><?= $party->gstType; ?></td>

										<th>GST No. :</th>

										<td><?= $party->gst; ?></td>

									</tr>
									<tr>
										<th>Credit Limit :</th>
										<td><i class="fa fa-inr"></i>
											<?= $party->credit_limit; ?></td>
										<th> </th>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
