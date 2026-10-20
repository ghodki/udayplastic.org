<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .bg-success p, .bg-success h2 {
        color: #ffffff;
    }
    .head {
        letter-spacing: 5px;
        font-size: 18px;
        font-weight: 600;
        color: #fff;
    }
    .total {
        letter-spacing: 2px;
        font-size: 20px;
        font-weight: 600;
        color: #fff;
    }
    .collapse2:not(.show) {
        display: none !important;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-primary">
                                    Accounting Balance Sheet
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12 py-2">
                                    <form action="<?= base_url(); ?>reports/accountingBalanceSheet" method="GET">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 form-group">
                                                <label for="from">From</label>
                                                <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : $fromDate); ?>" id="from" class="form-control form-control-sm" title="From" />
                                            </div>
                                            <div class="col-md-2 col-sm-2 form-group">
                                                <label for="to">To</label>
                                                <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : $toDate); ?>" id="to" class="form-control form-control-sm" title="To" />
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                <button type="submit" class="btn btn-sm bg-primary">
                                                    <i class="fa fa-search text-white"></i> Search
                                                </button>
                                                <a href="<?= base_url(); ?>reports/accountingBalanceSheet" class="btn btn-danger btn-sm" title="Refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="bg-success p-2">
                                        <?php
                                                if ($this->session->userdata('BranchRole')) {
                                                    $compBranchInfo = $this->db->select('BranchName, Address')->where('Id', $this->session->userdata('CompBranchId'))->get('companybranch')->row();
                                        ?>
                                        <h2 class="text-center"><?= $compBranchInfo->BranchName; ?></h2>
                                        <p class="text-center text-bold"><?= $compInfo->Address; ?></p>
                                        <?php                                    
                                                } else {
                                                    $compInfo = $this->db->select('company_name, address')->where('id', $this->session->userdata('comp_id'))->get('inventory_billing')->row();
                                        ?>
                                        <h2 class="text-center"><?= $compInfo->company_name; ?></h2>
                                        <p class="text-center text-bold"><?= $compInfo->address; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="bg-warning p-1">
                                        <p class="text-center text-bold">
                                            Balance Sheet as on 
                                            <?= date('d F Y', strtotime($toDate)); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php
                                    $sr = 0;
                                    $sundryCreditors = !empty($sundryCreditors) ? $sundryCreditors : [];
                                    $closingStock = !empty($closingStock) ? $closingStock : [];
                                    $sundryDebtors = !empty($sundryDebtors) ? $sundryDebtors : [];
                                    $dutiesAndTaxes = is_object($dutiesAndTaxes ?? null) ? $dutiesAndTaxes : (object) ['totalsgst' => 0, 'totalcgst' => 0, 'totaligst' => 0];
                                    $cashInHand = is_object($cashInHand ?? null) ? $cashInHand : (object) ['cashInHandAmount' => 0];
                                    $bankAccounts = is_object($bankAccounts ?? null) ? $bankAccounts : (object) ['backAccountAmount' => 0];

                                    // Liabilities
                                    $totalTax = (float) ($dutiesAndTaxes->totalsgst ?? 0) + (float) ($dutiesAndTaxes->totalcgst ?? 0) + (float) ($dutiesAndTaxes->totaligst ?? 0);
                                    $totalSundryCreditors = 0;
                                    foreach ($sundryCreditors as $key => $sundryCreditor) {
                                        $totalSundryCreditors += (float) ($sundryCreditor->totalBalanceAmount ?? 0);
                                    }
                                    $currentLiabilities = ($totalTax + $totalSundryCreditors);
                                    $profitAndLossAc = (float) ($currentPeriod ?? 0);
                                    $liabilitiesTotalAmount = ($currentLiabilities + $profitAndLossAc);
                                    
                                    // Assets
                                    $closingStockTotal = 0;
                                    foreach ($closingStock as $key => $cStock) {
                                        $balanceQty = (float) ProductBalanceQuanity($cStock->id);
                                        if ($balanceQty > 0) {
                                            $sr++;
                                            $total = ($balanceQty * (float) ($cStock->rate ?? 0));
                                            $closingStockTotal += $total;
                                        }
                                    }
                                    
                                    $totalSundryDebtors = 0;
                                    foreach ($sundryDebtors as $key => $sundryDebtor) {
                                        $totalSundryDebtors += (float) ($sundryDebtor->totalBalanceAmount ?? 0);
                                    }
                                    $currentAssets = ($closingStockTotal + $totalSundryDebtors + (float) ($cashInHand->cashInHandAmount ?? 0) + (float) ($bankAccounts->backAccountAmount ?? 0));
                                    $assetsTotalAmount = ($currentAssets);
                                ?>
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-bordered">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th class="head">Liabilities</th>
                                                    <th class="head text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-bold">Capital Accounts</td>
                                                    <td class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat(0); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold">Loans</td>
                                                    <td class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat(0); ?></td>
                                                </tr>
                                                <tr data-toggle="collapse" href="#currentLiabilities" role="button" aria-expanded="false" aria-controls="currentLiabilities" title="View More">
                                                    <td class="text-bold">Current Liabilities</td>
                                                    <td class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat($currentLiabilities); ?></td>
                                                </tr>
                                                <tr class="collapse2" id="currentLiabilities">
                                                    <td colspan="2" style="padding: 0px 30px 0px 30px;">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr data-toggle="collapse" href="#dutiesAndTaxes" role="button" aria-expanded="false" aria-controls="dutiesAndTaxes" title="View More">
                                                                    <th>Duties & Taxes</th>
                                                                    <td class="text-right">
                                                                        <i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTax); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr class="collapse2" id="dutiesAndTaxes">
                                                                    <td colspan="2" style="padding: 0px 30px 0px 30px;">
                                                                        <table class="table table-bordered table-sm">
                                                                            <tbody class="bg-gray-light">
                                                                                <?php if (!empty($dutiesAndTaxes->totalsgst)) { ?>
                                                                                <tr>
                                                                                    <td>SGST</td>
                                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($dutiesAndTaxes->totalsgst); ?></td>
                                                                                </tr>
                                                                                <?php } if (!empty($dutiesAndTaxes->totalcgst)) { ?>
                                                                                <tr>
                                                                                    <td>CGST</td>
                                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($dutiesAndTaxes->totalcgst); ?></td>
                                                                                </tr>
                                                                                <?php } if (!empty($dutiesAndTaxes->totaligst)) { ?>
                                                                                <tr>
                                                                                    <td>IGST</td>
                                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($dutiesAndTaxes->totaligst); ?></td>
                                                                                </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr data-toggle="collapse" href="#sundryCreditors" role="button" aria-expanded="false" aria-controls="sundryCreditors" title="View More">
                                                                    <th>Sundry Creditors</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalSundryCreditors); ?></td>
                                                                </tr>
                                                                <tr class="collapse2" id="sundryCreditors">
                                                                    <td colspan="2" style="padding: 0px 30px 0px 30px;">
                                                                        <table class="table table-bordered table-sm">
                                                                            <tbody class="bg-gray-light">
                                                                                <?php foreach ($sundryCreditors as $key => $sundryCreditor) { ?>
                                                                                <tr>
                                                                                    <td><?= get_title('parties', ['id' => $sundryCreditor->customerId], 'name'); ?></td>
                                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($sundryCreditor->totalBalanceAmount); ?></td>
                                                                                </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Supplies</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat(0); ?></td>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr data-toggle="collapse" href="#profitAndLossAc" role="button" aria-expanded="false" aria-controls="profitAndLossAc" title="View More">
                                                    <td class="text-bold">Profit & Loss A/c</td>
                                                    <td class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat($profitAndLossAc); ?></td>
                                                </tr>
                                                <tr class="collapse2" id="profitAndLossAc">
                                                    <td colspan="2" style="padding: 0px 30px 0px 30px;">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Opening Balance</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat(0); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Current Period</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($currentPeriod); ?></td>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-bordered">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th class="head">Assets</th>
                                                    <th class="head text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-bold">Fixed Assets</td>
                                                    <td class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat(0); ?></td>
                                                </tr>
                                                <tr data-toggle="collapse" href="#currentAssets" role="button" aria-expanded="false" aria-controls="currentAssets" title="View More">
                                                    <td class="text-bold">Current Assets</td>
                                                    <td class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat($currentAssets); ?></td>
                                                </tr>
                                                <tr class="collapse2" id="currentAssets">
                                                    <td colspan="2" style="padding: 0px 30px 0px 30px;">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr data-toggle="collapse" href="#closingStock" role="button" aria-expanded="false" aria-controls="closingStock" title="View More">
                                                                    <th>Closing Stock</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($closingStockTotal); ?></td>
                                                                </tr>
                                                                <tr class="collapse2" id="closingStock">
                                                                    <td colspan="2" style="padding: 0px 30px 0px 30px;">
                                                                        <table class="table table-bordered table-sm">
                                                                            <tbody class="bg-gray-light">
                                                                                <?php
                                                                                    foreach ($closingStock as $key => $cStock) {
                                                                                        $balanceQty = (float) ProductBalanceQuanity($cStock->id);
                                                                                        if ($balanceQty > 0) {
                                                                                            $sr++;
                                                                                            $total = ($balanceQty * (float) ($cStock->rate ?? 0));
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?= $cStock->p_name; ?></td>
                                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></td>
                                                                                </tr>
                                                                                <?php } } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr data-toggle="collapse" href="#sundryDebtors" role="button" aria-expanded="false" aria-controls="sundryDebtors" title="View More">
                                                                    <th>Sundry Debtors</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalSundryDebtors); ?></td>
                                                                </tr>
                                                                <tr class="collapse2" id="sundryDebtors">
                                                                    <td colspan="2" style="padding: 0px 30px 0px 30px;">
                                                                        <table class="table table-bordered table-sm">
                                                                            <tbody class="bg-gray-light">
                                                                                <?php foreach ($sundryDebtors as $key => $sundryDebtor) { ?>
                                                                                <tr>
                                                                                    <td><?= get_title('parties', ['id' => $sundryDebtor->customerId], 'name'); ?></td>
                                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($sundryDebtor->totalBalanceAmount); ?></td>
                                                                                </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Cash-in-Hand</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($cashInHand->cashInHandAmount); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Bank Accounts</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($bankAccounts->backAccountAmount); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Current Assets</th>
                                                                    <td class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat(0); ?></td>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <table class="table table-bordered">
                                        <tfoot>
                                            <tr>
                                                <th class="total text-dark">Total</th>
                                                <th class="total text-dark text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($liabilitiesTotalAmount); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <table class="table table-bordered">
                                        <tfoot>
                                                <tr>
                                                    <th class="total text-dark">Total</th>
                                                    <th class="total text-dark text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($assetsTotalAmount); ?></th>
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