<div class="modal-header">
        <h5 class="modal-title">Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
        </button>
</div>
<div class="modal-body">
        <div class="row">
                <div class="col-md-12">
                        <h5 class="text-center"><?= $partyName; ?></h5>
                </div>
                <div class="col-md-12">
                        <div class="table-responsive">
                                <table class="table table-bordered table-condensed request-data">
                                        <thead class="bg-success">
                                                <tr>
                                                        <th>Sr.</th>
                                                        <th>Invoice Date</th>
                                                        <th>Invoice No.</th>
                                                        <th>Total</th>
                                                        <th>Paid Amount</th>
                                                        <th>Balance</th>
                                                        <th>Type</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <?php
                                                        if (isset($partyPurchaseAndEstimatePurchaseInvoices) && !empty($partyPurchaseAndEstimatePurchaseInvoices)) {
                                                                $sr = 0;
                                                                $totalAmount = 0;
                                                                $totalPaidAmount = 0;
                                                                $totalBalance = 0;
                                                                foreach ($partyPurchaseAndEstimatePurchaseInvoices as $key => $data) {
                                                                        $sr++;
                                                                        $totalAmount += $data->total;
                                                                        $totalPaidAmount += $data->paid_amount;
                                                                        $totalBalance += ($data->total - $data->paid_amount);
                                                ?>
                                                <tr>
                                                        <td><?= $sr; ?></td>
                                                        <td><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                                        <td><?= $data->invoiceNumber; ?></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data->total); ?></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data->paid_amount); ?></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($data->total - $data->paid_amount)); ?></td>
                                                        <td><?= $data->type; ?></td>
                                                </tr>
                                                <?php } } ?>
                                        </tbody>
                                        <tfoot>
                                                <tr>
                                                        <th colspan="3" style="text-align: right;">Total</th>
                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalPaidAmount); ?></th>
                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalBalance); ?></th>
                                                        <th></th>
                                                </tr>
                                        </tfoot>
                                </table>
                        </div>
                </div>
        </div>
</div>
<div class="modal-footer">
        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>