<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row" style="margin-top:65px;">
				<div class="col-lg-12">


					<form name="formdata" action="<?= base_url('manages/purchasesearch/')   ?>" method="post">
						<div class="card-header row">
							<div class="col-md-6">
								<h4>Purchases</h4>
							</div>

							<div class="col-md-3">
								<!--<label for="year" class="col-3"  >Financial Year</label>-->
								<!-- <select name="year" id="year" style="width: 100%;" required="true" data-select2-id="15" class="form-control">
                                    <option value="">Select Financial Year</option>
                                   
                                            
                                            
                                    <?php

									// $fyid = 0;
									// $this->db->select('id, year');
									// $this->db->from('financialyear');
									// $this->db->order_by("id asc");
									// $query = $this->db->get();
									// $year = $query->result();
									// //	print_r($fyearid);die;
									// foreach ($year as $row) {
									// 	if ($row->id == $fyearid) {
									// 		$fyid = $fyearid;
									// 	}

									// 
									?>
                                    <option value="<?= $row->id ?>"<?= $row->id == $fyearid ? "selected" : '' ?>><?= $row->year ?></option>
                                     <?php


										// }



										?>
                                </select> -->
							</div>


							<div class="col-md-3" style="margin: 5px 0px 0px 0px;">
								<!-- <input id="fyid" type="hidden" value="<?= $fyid ?>" />
        
                    <button type="submit"  class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Show</button>
                    <a href="<?= base_url('manages/purchasesearch/') ?>" class="text-info"><i data-feather="edit" class="icon icon-size"></i></a>  -->
								<!--<a href="<?= base_url('/weeklyReport') ?>" class="btn btn-primary printInvoice">Reset</a>-->

								<a href="<?= base_url() ?>purchase/purchaseAdd" class="btn btn-primary float-right"><i data-feather="plus" class="icon icon-size"></i>Add Purchase</a>


							</div>



						</div>
					</form>

					<?php
					if ($this->session->flashdata('msg')) { ?>
						<div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
							<?php echo $this->session->flashdata('msg'); ?>
						</div>
					<?php } ?>

					<table class="table table-striped table-bordered table-responsive-sm dataTable" id="example">
						<thead>
							<tr>
								<th>Sr.</th>
								<th>Invoice No</th>
								<th>Party Name</th>
								<th>Mobile</th>
								<th>Email</th>
								<th>Total</th>
								<th>Invoice Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($result) {
								foreach ($result as $key => $row) { ?>
									<tr>
										<td><?= $key + 1 ?></td>
										<td><?= $row->invoiceNumber; ?></td>
										<td style="width:300px"><?php if ($row->customerId) {
																	echo get_title('parties', ['id' => $row->customerId], 'name');
																} else {
																	echo $row->customerName;
																} ?></td>
										<td><?= $row->customerMobile; ?></td>
										<td><?= $row->customerEmail; ?></td>
										<td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->total; ?></td>
										<td><?= $row->invoiceDate; ?></td>
										<td>

											<a href="<?= base_url('purchase/viewpurchase/') . $row->id ?>" class="text-success" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
											| <a href="<?= base_url('purchase/purchaseEdit/') . $row->id ?>" class="text-info" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
											|<a href="#" onclick="return deleteRow('<?= base_url('purchase/purchase_delete/') . $row->id ?>');" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon icon-size">
													<polyline points="3 6 5 6 21 6"></polyline>
													<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
													<line x1="10" y1="11" x2="10" y2="17"></line>
													<line x1="14" y1="11" x2="14" y2="17"></line>
												</svg></a>
											| <a href="<?= base_url('purchase/printPurchase/') . $row->id ?>" class="text-success" title="Print"><i class="fa fa-print" aria-hidden="true"></i></a> |

											<a href="<?= base_url('invoicepdf/') . $row->id ?>" class="text-primary" title="Print"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a> |


										</td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>



			<!-- <div class="row">
				<div class="col-md-6">
					<?php if ($totalCount) { ?>
						<p>Showing <?= $start ? $start : 1; ?> to <?= $end ?> of <?= $totalCount ?> entries</p>
					<?php } else { ?>
						<p>No result found.</p>
					<?php } ?>
				</div>
				<div class="col-md-6">
					<div class="fr">
						<?= $this->pagination->create_links(); ?>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div>
<script>
	function deleteRow(url) {
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
</script>
<script>
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
