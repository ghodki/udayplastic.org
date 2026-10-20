<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row" style="margin-top:65px;">
                <div class="col-lg-12">
                    <div class="card mb-30">



                        <table class="table table-striped table-bordered table-responsive-sm dataTable" id="example">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice Value</th>
                                    <th>State</th>
                                    <th>Taxable value</th>
                                    <th>Integrated Tax Amount</th>
                                    <th>Central Tax Amount</th>
                                    <th>State/UT Tax Amount</th>
                                    <th>GSTIN</th>
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
                                $this->db->where('totalValue' > 250000);
                                $this->db->where(['gstNo!=' => '']);
                                $totals_data = $this->db->get('sales')->result();
                                if ($totals_data) {
                                    foreach ($totals_data as $key => $invoice_details) {

                                ?>
                                        <tr>
                                            <td><?= $invoice_details->invoiceNumber ?></td>
                                            <td><?= $invoice_details->invoiceDate ?></td>
                                            <td><?= $invoice_details->totalValue ?></td>
                                            <td><?= $invoice_details->customerState ?>-<?= $invoice_details->state_name ?></td>
                                            <td><?= $invoice_details->taxableAmount ?></td>
                                            <td><?= $invoice_details->totaligst ?></td>
                                            <td><?= $invoice_details->totalcgst ?></td>
                                            <td><?= $invoice_details->totalsgst ?></td>
                                            <td><?= $invoice_details->gstNo ?></td>
                                            <td><?= 0 ?></td>


                                        </tr>
                                <?php }
                                } ?>
                            </tbody>


                        </table>





                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
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
</script>