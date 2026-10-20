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
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-danger">
                                    Parties Balance
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/PartiesBalanceReport" class="btn btn-danger btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left text-white"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <h4 class="text-danger text-center">
                                <?= $PartyName; ?>
                            </h4>
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead class="bg-danger">
                                        <tr>
                                            <th class="text-white">Invoice Number</th>
                                            <th class="text-white">Balance</th>
                                            <th class="text-white">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Sr = 0;
                                            $Total = 0;
                                            if (isset($PartiesBalance) && !empty($PartiesBalance)) {
                                                foreach ($PartiesBalance as $key => $Balance) {
                                                    $Sr++;
                                                    $Total += ((float) ($Balance->total ?? 0) - (float) ($Balance->paid_amount ?? 0));
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if ($Balance->type == 'Sale') { ?>
                                                <a href="<?= base_url(); ?>Employee/PrintSale/<?= $Balance->id; ?>" target="_blank" title="Print">
                                                    <?= $Balance->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($Balance->type == 'Estimate') { ?>
                                                <a href="<?= base_url(); ?>Employee/PrintEstimate/<?= $Balance->id; ?>" target="_blank" title="Print">
                                                    <?= $Balance->invoiceNumber; ?>
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format(((float) ($Balance->total ?? 0) - (float) ($Balance->paid_amount ?? 0)), 2); ?></td>
                                            <td><?= $Balance->type; ?></td>
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