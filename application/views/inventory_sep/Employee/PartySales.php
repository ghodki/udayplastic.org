<style type="text/css">
    .table td, .table th {
        padding: 0.25rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-info">
                                    Parties Sales
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/PartiesBalanceReport" class="btn btn-info btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left text-white"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <h4 class="text-info text-center">
                                <?= $PartyName; ?>
                            </h4>
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead class="bg-info">
                                        <tr>
                                            <th class="text-white">Invoice Number</th>
                                            <th class="text-white">Total</th>
                                            <th class="text-white">Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Sr = 0;
                                            $Total = 0;
                                            if (isset($PartiesSales) && !empty($PartiesSales)) {
                                                foreach ($PartiesSales as $key => $Sales) {
                                                    $Sr++;
                                                    $Total += (float) ($Sales->total ?? 0);
                                                    
                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Sales->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, cashDiscountAmount, ChequeStatus')->where('invoice_id', $Sales->id)->where('transaction_type', 'DR')->get('payment')->row();
                                                    $paymentMethod = !empty($PaymentInfo) ? get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') : '';
                                                    $chequeStatus = !empty($PaymentInfo) ? ($PaymentInfo->ChequeStatus ?? null) : null;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if ($Sales->type == 'Sale') { ?>
                                                <a href="<?= base_url(); ?>Employee/PrintSale/<?= $Sales->id; ?>" target="_blank" title="Print">
                                                    <?= $Sales->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($Sales->type == 'Estimate') { ?>
                                                <a href="<?= base_url(); ?>Employee/PrintEstimate/<?= $Sales->id; ?>" target="_blank" title="Print">
                                                    <?= $Sales->invoiceNumber; ?>
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($Sales->total ?? 0), 2); ?></td>
                                            <td>
                                                <?php if ($Sales->total == $Sales->paid_amount) { if ($paymentMethod == "Cheque") { if ($chequeStatus == 0) { ?>
                                                <span class="badge badge-danger">Unpaid/PDC</span>
                                                <?php } elseif ($chequeStatus == 1) { ?>
                                                <span class="badge badge-success">Paid</span>
                                                <?php } } else { ?>
                                                <span class="badge badge-success">Paid</span>
                                                <?php } } elseif ($Sales->paid_amount == NULL || $Sales->paid_amount == "") { ?>
                                                <span class="badge badge-danger">Unpaid</span>
                                                <?php } else {  if ($paymentMethod == "Cheque") { if ($chequeStatus == 0) { ?>
                                                <span class="badge badge-danger">Unpaid/PDC</span>
                                                <?php } elseif ($chequeStatus == 1) { ?>
                                                <span class="badge badge-warning">Partial</span>
                                                <?php } } else { ?>
                                                <span class="badge badge-warning">Partial</span>
                                                <?php } } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: right;">Total</th>
                                            <th colspan="2"><i class="fa fa-inr"> <?= number_format((float) $Total, 2); ?></th>
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