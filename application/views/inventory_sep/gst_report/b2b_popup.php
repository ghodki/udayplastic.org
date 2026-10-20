<div class="row">
	<div class="col-lg-12">
		<div class="card mb-30">
			<table class="table table-striped table-bordered table-responsive-sm dataTable" id="example">
				<thead>
					<tr>
						<th>GSTIN</th>
						<th>Invoice Number</th>
						<th>Invoice Value</th>
						<th>State</th>
						<th>Reverse Charge</th>
						<th>Invoice Type</th>
						<th>Taxable value</th>
						<th>Cess Amount</th>

					</tr>
				</thead>

				<tbody id="tbody">
					<?php
					$this->db->where(['invoiceDate >=' => $start_date]);
					$this->db->where(['invoiceDate <=' => $end_date]);
					$this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                        if ($this->session->userdata('BranchRole')) {
                                            $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                        }
                                        $this->db->where('status', 1);
					$this->db->where(['gstNo!=' => '']);
					$totals_data = $this->db->get('sales')->result();
					if ($totals_data) {
						foreach ($totals_data as $key => $invoice_details) {

					?>
							<tr>
								<td><?= $invoice_details->gstNo ?></td>
								<td><?= $invoice_details->invoiceNumber ?></td>
								<td><?= $invoice_details->totalValue ?></td>
								<td><?= $invoice_details->customerState ?>-<?= $invoice_details->state_name ?></td>
								<td><?= 0 ?></td>
								<td>Regular</td>
								<td><?= $invoice_details->taxableAmount ?></td>
								<td>0</td>
							</tr>
					<?php }
					} ?>
				</tbody>


			</table>





		</div>
	</div>
</div>

<script>
	$(function() {
		$(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            sorting: false,
            buttons: [{
                    extend: 'excelHtml5',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"
                },
                {
                    extend: 'pdfHtml5',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"

                },
                {
                    extend: 'csvHtml5',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"

                },
                {
                    extend: 'print',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"

                }
            ],


        });
        document.getElementsByClassName('buttons-excel')[0].click();

    })
		document.getElementsByClassName('buttons-excel')[0].click();

	})
	// setTimeout(function() {
	//     document.getElementsByClassName('buttons-excel')[0].click();
	// }, 2000);
</script>