<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-purple">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-purple">B2B Report</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable" id="example">
                                    <thead class="bg-purple">
                                        <tr>
                                            <th>GSTIN/UIN of Recipient</th>
                                            <th>Invoice Number</th>
                                            <th>Invoice date</th>
                                            <th>Invoice Value</th>
                                            <th>Place Of Supply</th>
                                            <th>Reverse Charge</th>
                                            <th>Invoice Type</th>
                                            <th>E-Commerce GSTIN</th>
                                            <th>Taxable value</th>
                                            <th>Integrated Tax Amount</th>
                                            <th>Central Tax Amount</th>
                                            <th>State/UT Tax Amount</th>
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
                                                    $StateId = get_title('parties', ['id' => $invoice_details->customerId], 'state');
                                        ?>
                                        <tr>
                                            <td><?= get_title('parties', ['id' => $invoice_details->customerId], 'gst'); ?></td>
                                            <td><?= $invoice_details->invoiceNumber ?></td>
                                            <td><?= $invoice_details->invoiceDate ?></td>
                                            <td><?= $invoice_details->totalValue ?></td>
                                            <td>
                                                <?= get_title('state_list', ['id' => $StateId], 'code'); ?>
                                                -
                                                <?= get_title('state_list', ['id' => $StateId], 'state'); ?>
                                            </td>
                                            <td><?= 0; ?></td>
                                            <td>Regular</td>
                                            <td></td>
                                            <td><?= $invoice_details->taxableAmount ?></td>
                                            <td><?= $invoice_details->totaligst ?></td>
                                            <td><?= $invoice_details->totalcgst ?></td>
                                            <td><?= $invoice_details->totalsgst ?></td>
                                            <td>0</td>
                                        </tr>
                                        <?php } } ?>
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

<script type="text/javascript">
    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            sorting: false,
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'B2B Report'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'B2B Report'
                },
                {
                    extend: 'copy',
                    title: 'B2B Report'
                },
                {
                    extend: 'print',
                    title: 'B2B Report'
                }
            ]
        });
        
        document.getElementsByClassName('buttons-excel')[0].click();
    });
</script>