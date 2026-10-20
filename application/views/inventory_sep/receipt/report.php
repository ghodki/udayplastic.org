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
                                    Receipt Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <form action="<?= base_url(); ?>receipt/report" method="GET">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 form-group">
                                                <label for="from">From</label>
                                                <input type="date" name="from" value="<?= $this->input->get('from') ? $this->input->get('from') : date('Y-m-01'); ?>" id="from" class="form-control form-control-sm" title="Select From Date" />
                                            </div>
                                            <div class="col-md-2 col-sm-2 form-group">
                                                <label for="to">to</label>
                                                <input type="date" name="to" value="<?= $this->input->get('to') ? $this->input->get('to') : date('Y-m-d'); ?>" id="to" class="form-control form-control-sm" title="Select From To" />
                                            </div>
                                            <div class="col-md-2 col-sm-2 form-group">
                                                <label for="mode">Payment Mode</label>
                                                <select name="mode" id="mode" class="form-control form-control-sm" title="Payment Mode">
                                                    <option value="All" <?= $this->input->get('mode') == 'All' ? 'selected' : ''; ?>>All</option>
                                                    <?php if (isset($paymentMode) && !empty($paymentMode)) { foreach ($paymentMode as $key => $mode) { ?>
                                                    <option value="<?= $mode->id; ?>" <?= $this->input->get('mode') == $mode->id ? 'selected' : ''; ?>><?= $mode->payment_method; ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-search text-white"></i> Search
                                                </button>
                                                <a href="<?= base_url(); ?>receipt/report" class="btn btn-danger btn-sm" title="Refresh">
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
                                                    <th>Date</th>
                                                    <th>Receipt</th>
                                                    <th>Customer</th>
                                                    <th>Amount</th>
                                                    <th>Mode</th>
                                                    <th>remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $srNo = 0;
                                                    $totalAmount = 0;
                                                    if (isset($receiptsData) && !empty($receiptsData)) {
                                                        foreach ($receiptsData as $key => $dataInfo) {
                                                            $srNo++;
                                                            $totalAmount += (float) ($dataInfo->debit ?? 0);
                                                ?>
                                                <tr>
                                                    <td><?= $srNo; ?></td>
                                                    <td><?= !empty($dataInfo->date) ? date('d-m-Y', strtotime($dataInfo->date)) : ''; ?></td>
                                                    <td><?= $dataInfo->voucher_number; ?></td>
                                                    <td><?= get_title('parties', ['id' => $dataInfo->party_id], 'name'); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat((float) ($dataInfo->debit ?? 0)); ?></td>
                                                    <td><?= get_title('payment_mode', ['id' => $dataInfo->paymentmode], 'payment_method'); ?></td>
                                                    <td><?= $dataInfo->remark; ?></td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" style="text-align: right;">Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
                                                    <th colspan="2"></th>
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