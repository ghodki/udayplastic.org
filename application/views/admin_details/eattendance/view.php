<style>
	.error {
		color: red;
	}
</style>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-md-12 col-xl-12 col-lg-12">
					<div class="header pt-md-8">

					</div>
				</div>
				<!-- Trigger the modal with a button -->

				<!-- Modal -->

				<div class="container-fluid mb-9" style="margin-top:100px;">

					<div class="col">
						<div class="card shadow">
							<div class="card-header border-0">
								<label class="mb-0" style="vertical-align: -moz-middle-with-baseline" class="text-center">User Log In Details</label>
								<?php
								$countdata = 0;
								$total_current_active = 0;
								$total_current_inactive = 0;
								$today_active = 0;
								$currentdatelogin = '';
								if (isset($logindetails) && !empty($logindetails)) {
									$countdata = count($logindetails);
								}

								// 	foreach ($userlist as $key => $user) {
								$currentdate = date('d-m-Y');
								$m = date("m");
								$d = date("d");
								$y = date("Y");
								$this->db->select('*');
								$this->db->from('userlogindetails');
								$this->db->where('status', 1);
								$this->db->where('userId',$id);

								// $this->db->where('dd', $d);
								$this->db->where('mm', $m);
								$this->db->where('yy', $y);

								// $this->db->where('loginDate', $currentdate);
								// $this->db->where_in('userId');
								$this->db->group_by('loginDate', true);

								$this->db->order_by('id', 'ASC');

								$query = $this->db->get();

								$currentdatelogin = $query->result();
								if ($currentdatelogin) {
									$total_current_active = count($currentdatelogin);
								}

								?>
								<?php
								$currentdate = date('d-m-Y');
								$m = date("m");
								$d = date("d");
								$y = date("Y");
								$this->db->select('*');
								$this->db->from('userlogindetails');
								$this->db->where('status', 1);
								// $this->db->where('dd', $d);
								$this->db->where('dd', $d);
								$this->db->where('mm', $m);
								$this->db->where('userId',$id);

								$this->db->where('yy', $y);

								$this->db->where('loginDate', $currentdate);
								// $this->db->where_in('userId');
								// $this->db->group_by('loginDate', true);

								$this->db->order_by('id', 'ASC');

								$query = $this->db->get();

								$currentdatelogins = $query->result();
								if ($currentdatelogin) {
									$today_active = count($currentdatelogins);
								}

								?>
								<br>
								<br>
								<h5 class="text-primary bg-primary">Total:<?= $countdata ?></h5>
								<p class="text-success bg-success"> Monthly Active:<?= $total_current_active ?><span>Days</span></p>

								<p class="text-success bg-dark">Today Active:<?= $today_active ?><span>Times</span></p>

								<div class="row mt-5">
								</div>
								<div class="table-responsive">
									<table class="table align-items-center table-flush" style="width: 100%" id="example_contra">
										<thead class="thead-light">
											<tr>
												<th>#</th>
												<th>Name</th>
												<th>Last Login Date</th>
												<th>Last Login Time</th>


												<!-- <th>SMS</th> -->
												<!-- <th>Referral Code</th> -->
												<!-- <th>User Type</th> -->
												<!-- <th>Action</th> -->
											</tr>
										</thead>
										<tbody id="tableData">
											<?php

											if (isset($logindetails) && !empty($logindetails)) {
												foreach ($logindetails as $key => $user) {

											?>

													<tr>
														<td><?= $key + 1 ?></td>
														<td><?= get_title('users', ['id' => $user->userId], 'name'); ?></td>
														<!-- <td><a href="<?= base_url('eattendance/seedetails/') . $user->id ?>" title="Log In Details"><?= $user->name; ?></a></td> -->
														<td><?= $user->loginDate; ?></td>
														<td><?= $user->loginTime; ?></td>
														<?php
														// $where = ['user_id' => $user->id];
														// $username = $this->user_model->getRow('sms_details', $where);
														// if ($username) {
														// 	$rem = $username->remains_sms;
														// } else {
														// 	$rem = 0;
														// }
														// 
														?>
														<!-- <td><?= $rem; ?></td> -->
														<!-- <td><?= $user->referral; ?></td> -->
														<!-- <td></td> -->




													</tr>

											<?php }
											} else {
												echo "There Is no users";
											}
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
</div>


</div>
<script>
	$(document).ready(function() {
		$('#example_contra').DataTable();
	});
</script>
<script type="text/javascript" language="javascript">
	// $(document).ready(function() {

	// 	$('#usertype').change(function() {

	// 		var party_id = $(this).val();
	// 		console.log(party_id)
	// 		// console.log(party_id, 'party_id');
	// 		if (party_id) {
	// 			$.ajax({
	// 				type: "POST",
	// 				<?php
						// 				if ($this->session->userdata('roles') == 2) {
						// 				
						?>
	// 					url: '<?= base_url('admin/referals'); ?>'
	// 				<?php
						// 				} else {
						// 				
						?>
	// 					url: '<?= base_url('admin/referal'); ?>'

	// 				<?php
						// 				} 
						?>,
	// 				data: {
	// 					usertype: party_id,

	// 				},
	// 				success: function(data) {
	// 					if (data) {
	// 						$("#userdata").html(data);

	// 					} else {
	// 						document.getElementById('userdata').innerHTML = '';

	// 					}
	// 				}

	// 			});
	// 		} else {
	// 			document.getElementById('userdata').innerHTML = '';

	// 		}

	// 	})
	// });
</script>
<script>

</script>
