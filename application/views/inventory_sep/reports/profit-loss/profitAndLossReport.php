<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .particular {
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
    .table-bordered td, .table-bordered th {
        border: 0px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-purple">
                                    Profit & Loss Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <h2 class="text-center pb-1">
                                        <?php
                                            if ($this->session->userdata('BranchRole')) {
                                                $compBranchInfo = $this->db->select('BranchName, city')->where('Id', $this->session->userdata('CompBranchId'))->get('companybranch')->row();
                                                echo $compBranchInfo->BranchName . ', ' . get_title('districts', ['id' => $compBranchInfo->city], 'name');
                                            } else {
                                                $compInfo = $this->db->select('company_name, city')->where('id', $this->session->userdata('comp_id'))->get('inventory_billing')->row();
                                                echo $compInfo->company_name . ', ' . get_title('districts', ['id' => $compInfo->city], 'name');
                                            }
                                        ?>
                                    </h2>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12 py-1">
                                    <form action="<?= base_url(); ?>reports/profitAndLossReport" method="GET">
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
                                                        <button type="submit" class="btn btn-sm bg-navy">
                                                                <i class="fa fa-search text-white"></i> Search
                                                        </button>
                                                        <a href="<?= base_url(); ?>reports/profitAndLossReport" class="btn btn-danger btn-sm" title="Refresh">
                                                                <i class="fa fa-refresh"></i>
                                                        </a>
                                                </div>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                    $grossProfitCo = (($salesAmount + $closingStockAmount) - ( $openingStockAmount + $purchasesAmount + $directExpensesAmount));
                                    $grossProfitBf = $grossProfitCo;
                                    $netProfit = (($grossProfitCo - $indirectExpensesAmount) + $indirectIncomesAmount);
                                ?>
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <table class="table table-bordered">
                                        <thead class="bg-navy">
                                            <tr>
                                                <th class="particular" colspan="2">Particulars</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url(); ?>reports/getOpeningStockInfo" title="View More">
                                                        Opening Stock
                                                    </a>
                                                </td>
                                                <td class="text-right"> 
                                                    <a href="<?= base_url(); ?>reports/getOpeningStockInfo" title="View More">
                                                       <i class="fa fa-inr"></i> <?= indianMoneyFormat($openingStockAmount); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url(); ?>reports/getPurchaseAccountsInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        Purchase Accounts
                                                    </a>
                                                </td>
                                                <td class="text-right">
                                                    <a href="<?= base_url(); ?>reports/getPurchaseAccountsInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        <i class="fa fa-inr"></i> <?= indianMoneyFormat($purchasesAmount); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url(); ?>reports/getDirectExpensesInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        Direct Expenses
                                                    </a>
                                                </td>
                                                <td class="text-right">
                                                    <a href="<?= base_url(); ?>reports/getDirectExpensesInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        <i class="fa fa-inr"></i> <?= indianMoneyFormat($directExpensesAmount); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Gross Profit c/o</th>
                                                <th class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($grossProfitCo); ?></th>
                                            </tr>
                                            <tr style="border-top: 2px solid #000; border-bottom: 2px solid #000;">
                                                <th></th>
                                                <th class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat(($openingStockAmount + $purchasesAmount + $directExpensesAmount + $grossProfitCo)); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url(); ?>reports/getIndirectExpensesInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="Vew More">
                                                        Indirect Expenses
                                                    </a>
                                                </td>
                                                <th class="text-right">
                                                    <a href="<?= base_url(); ?>reports/getIndirectExpensesInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="Vew More">
                                                        <i class="fa fa-inr"></i> <?= indianMoneyFormat($indirectExpensesAmount); ?>
                                                    </a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>Nett Profit</td>
                                                <th class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($netProfit); ?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2"></th>
                                                <th></th>
                                            </tr>
                                            <tr class="bg-dark">
                                                <th class="total" colspan="2">Total</th>
                                                <th class="text-right total"><i class="fa fa-inr total"></i> <?= indianMoneyFormat(($netProfit + $indirectExpensesAmount)); ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <table class="table table-bordered">
                                        <thead class="bg-navy">
                                            <tr>
                                                <th class="particular" colspan="2">Particulars</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url(); ?>reports/getSalesAccountsInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        Sales Accounts
                                                    </a>
                                                </td>
                                                <td class="text-right">
                                                    <a href="<?= base_url(); ?>reports/getSalesAccountsInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        <i class="fa fa-inr"></i> <?= indianMoneyFormat($salesAmount); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url(); ?>reports/getClosingStockInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        Closing Stock
                                                    </a>
                                                </td>
                                                <td class="text-right">
                                                    <a href="<?= base_url(); ?>reports/getClosingStockInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        <i class="fa fa-inr"></i> <?= indianMoneyFormat($closingStockAmount); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr style="border-top: 2px solid #000; border-bottom: 2px solid #000;">
                                                <th></th>
                                                <th class="text-right text-bold"><i class="fa fa-inr"></i> <?= indianMoneyFormat(($salesAmount + $closingStockAmount)); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>Gross Profit b/f</td>
                                                <th class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($grossProfitBf); ?></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url(); ?>reports/getIndirectIncomesInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        Indirect Incomes
                                                    </a>
                                                </td>
                                                <th class="text-right">
                                                    <a href="<?= base_url(); ?>reports/getIndirectIncomesInfo?from=<?= $fromDate; ?>&to=<?= $toDate; ?>" title="View More">
                                                        <i class="fa fa-inr"></i> <?= indianMoneyFormat($indirectIncomesAmount); ?>
                                                    </a>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2"></th>
                                                <th></th>
                                            </tr>
                                            <tr class="bg-dark">
                                                <th class="total" colspan="2">Total</th>
                                                <th class="text-right total"><i class="fa fa-inr total"></i> <?= indianMoneyFormat(($grossProfitBf + $indirectIncomesAmount)); ?></th>
                                            </tr>
                                        </thead>
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