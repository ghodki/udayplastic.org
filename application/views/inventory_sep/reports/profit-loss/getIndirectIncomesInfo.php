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
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-purple">
                                    Indirect Incomes Info
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>reports/profitAndLossReport" class="btn btn-sm bg-purple pull-right" title="Back">
                               <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12 py-1">
                                    <form action="<?= base_url(); ?>reports/getIndirectIncomesInfo" method="GET">
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
                                                        <button type="submit" class="btn btn-sm bg-purple">
                                                                <i class="fa fa-search text-white"></i> Search
                                                        </button>
                                                        <a href="<?= base_url(); ?>reports/getIndirectIncomesInfo" class="btn btn-danger btn-sm" title="Refresh">
                                                                <i class="fa fa-refresh"></i>
                                                        </a>
                                                </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-purple">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">Date</th>
                                                    <th class="text-white">Voucher Number</th>
                                                    <th class="text-white">Ledger Name</th>
                                                    <th class="text-white">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($indirectIncomesData) && !empty($indirectIncomesData)) {
                                                        $sr = 0;
                                                        $totalAmount = 0;
                                                        foreach ($indirectIncomesData as $key => $indirectIncome) {
                                                            $sr++;
                                                            $totalAmount += $indirectIncome->credit;
                                                            $ledgerName = get_title('parties', ['id' => $indirectIncome->party_id], 'name');
                                                ?>
                                                <tr>
                                                    <td><?= $sr; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($indirectIncome->date)); ?></td>
                                                    <td><?= $indirectIncome->voucher_number; ?></td>
                                                    <td><?= $ledgerName; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($indirectIncome->credit); ?></td>                            
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right" colspan="4">Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
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