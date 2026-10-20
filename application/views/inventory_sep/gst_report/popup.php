<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-responsive-sm dataTable" id="example">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Invoice Date</th>
                        <th style="width:150px;">Customer</th>
                        <th>GSTIN</th>
                        <th>POS</th>
                        <th>Taxable</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <?php
                        $total_taxable_amount = 0;
                        $totalTax_amount = 0;
                        $overall_total = 0;
                        if ($result) {
                            foreach ($result as $key => $invoice_details) {
                                $total_taxable_amount += $invoice_details->taxableAmount;
                                $totalTax_amount += $invoice_details->totalTax;
                                $overall_total += $invoice_details->totalValue;
                                $StateId = get_title('parties', ['id' => $invoice_details->customerId], 'state');
                    ?>
                    <tr>
                        <td><?= $invoice_details->invoiceNumber ?></td>
                        <td><?= $invoice_details->invoiceDate ?></td>
                        <td><?= get_title('parties', ['id' => $invoice_details->customerId], 'name'); ?></td>
                        <td><?= get_title('parties', ['id' => $invoice_details->customerId], 'gst'); ?></td>
                        <td><?= get_title('state_list', ['id' => $StateId], 'code'); ?>-<?= get_title('state_list', ['id' => $StateId], 'state'); ?></td>
                        <td><?= indianMoneyFormat($invoice_details->taxableAmount) ?></td>
                        <td><?= indianMoneyFormat($invoice_details->totalTax) ?></td>
                        <td><?= indianMoneyFormat($invoice_details->totalValue) ?></td>
                        <td>
                            <a href="<?= base_url('sale/viewsale/') . $invoice_details->id ?>" class="text-success" title="View">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            &nbsp;
                            <a href="<?= base_url('sale/saleEdit/') . $invoice_details->id ?>" class="text-info" title="Edit">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>
                            &nbsp;
                            <a href="<?= base_url('sale/printSale/') . $invoice_details->id ?>" class="text-success" title="Print">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                            &nbsp;
                            <a href="<?= base_url('sale/printSales/') . $invoice_details->id ?>" class="text-primary" title="Download">
                                <i class="fa fa-download" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Total</th>
                        <th><?= indianMoneyFormat($total_taxable_amount); ?></th>
                        <th><?= indianMoneyFormat($totalTax_amount); ?></th>
                        <th><?= indianMoneyFormat($overall_total); ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
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
            ]
        });
    });
</script>