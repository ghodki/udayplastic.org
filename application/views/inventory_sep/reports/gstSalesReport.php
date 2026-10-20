<style type="text/css">
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-dark">
                                    GST Sales Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <form action="<?= base_url(); ?>reports/gstSalesReport" method="GET">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 form-group">
                                                    <label for="from">From</label>
                                                    <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : date('Y-m-01')); ?>" id="from" class="form-control form-control-sm" title="From" />
                                            </div>
                                            <div class="col-md-2 col-sm-2 form-group">
                                                    <label for="to">To</label>
                                                    <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : date('Y-m-d')); ?>" id="to" class="form-control form-control-sm" title="To" />
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fa fa-search text-white"></i> Search
                                                </button>
                                                <a href="<?= base_url(); ?>reports/gstSalesReport" class="btn btn-danger btn-sm" title="Refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-success">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>GSTIN</th>
                                                    <th>Name</th>
                                                    <th>Invoice Num</th>
                                                    <th>Invoice Date</th>
                                                    <th>Total Value</th>
                                                    <th>Taxable Value</th>
                                                    <th>Rat Tax</th>
                                                    <th>Integrated</th>
                                                    <th>Central</th>
                                                    <th>State</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $srNo = 0;
                                                    $totalValue = 0;
                                                    $totalTaxableValue = 0;
                                                    $totalIgst = 0;
                                                    $totalCgst = 0;
                                                    $totalSgst = 0;
                                                    if (isset($gstSales) && !empty($gstSales)) {
                                                        foreach ($gstSales as $key => $sale) {
                                                            $srNo++;
                                                            
                                                            $totalValue += (float) ($sale->netAmount ?? 0);
                                                            $totalTaxableValue += (float) ($sale->price ?? 0);
                                                            $totalIgst += (float) ($sale->igst ?? 0);
                                                            $totalCgst += (float) ($sale->cgst ?? 0);
                                                            $totalSgst += (float) ($sale->sgst ?? 0);
                                                            
                                                            $saleData = $this->db->select('customerId')->where('id', $sale->invoiceID)->get('sales')->row();
                                                            $partyData = $saleData
                                                                ? $this->db->select('name, gst')->where('id', $saleData->customerId)->get('parties')->row()
                                                                : null;
                                                ?>
                                                <tr>
                                                    <td><?= $srNo; ?></td>
                                                    <td><?= $partyData->gst ?? ''; ?></td>
                                                    <td><?= $partyData->name ?? ''; ?></td>
                                                    <td><?= $sale->invoiceNo; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($sale->invoiceDate)); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sale->netAmount ?? 0); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sale->price ?? 0); ?></td>
                                                    <td><?= number_format((float) ($sale->tax ?? 0)); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sale->igst ?? 0); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sale->cgst ?? 0); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sale->sgst ?? 0); ?></td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" style="text-align: right;">Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalValue); ?></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTaxableValue); ?></th>
                                                    <th></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalIgst); ?></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalCgst); ?></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalSgst); ?></th>
                                                </tr>
                                            </tfoot>
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

<script type="text/javascript">
    $(document).ready(function () {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    footer: true
                },
                {
                    extend: 'excel',
                    footer: true
                },
                {
                    extend: 'pdf',
                    footer: true
                },
                {
                    extend: 'print',
                    footer: true
                },
                "pageLength"
            ],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });    
</script>