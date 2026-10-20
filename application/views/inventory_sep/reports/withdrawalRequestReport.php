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
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="header-title">
                                    Withdrawal Request Report
                                </h4>
                            </div>
                            <?php $rewardPointsQuery = $this->db->select('date')->where('status', 0)->order_by('date', 'ASC')->get('reward_points_withdrawal'); ?>
                            <a href="<?= base_url(); ?>Reports/withdrawalRequestReport?from=<?= $rewardPointsQuery->row()->date; ?>&to=<?= date('Y-m-d'); ?>&status=0" class="btn btn-danger btn-xs pull-right" title="Pending Request">
                                <i class="fa fa-bell text-white"></i> Pending Request : 
                                <?= $rewardPointsQuery->num_rows(); ?>
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="from">From</label>
                                        <input type="date" name="from" value="<?= $this->input->get('from') ? $this->input->get('from') : date('Y-m-d'); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="to">To</label>
                                        <input type="date" name="to" value="<?= $this->input->get('to') ? $this->input->get('to') : date('Y-m-d'); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1">
                                        <label for="status">Status</label>
                                        <select name="status" id="Payment" class="form-control form-control-sm" title="Status">
                                            <option value="All" <?= $this->input->get('status') == "All" ? "selected" : ""; ?>>All</option>
                                            <option value="0" <?= $this->input->get('status') == "0" ? "selected" : ""; ?>>Pending</option>
                                            <option value="1" <?= $this->input->get('status') == "1" ? "selected" : ""; ?>>Approved</option>
                                            <option value="2" <?= $this->input->get('status') == "2" ? "selected" : ""; ?>>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-xs bg-purple">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/withdrawalRequestReport" class="btn btn-danger btn-xs" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Reward Points</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $totalrewardPoints = 0;
                                            $totalAmount = 0;
                                            $pointCost = (float) (is_object($rewardPointCostData ?? null) ? ($rewardPointCostData->rewardPoint ?? 0) : 0);
                                            $pointRupee = (float) (is_object($rewardPointCostData ?? null) ? ($rewardPointCostData->equivalentRupee ?? 0) : 0);
                                            if (isset($rewardPoints) && !empty($rewardPoints)) {
                                                foreach ($rewardPoints as $key => $data) {
                                                    $rowPoints = (float) ($data->rewardPoints ?? 0);
                                                    $rowAmount = ($pointCost > 0) ? (($rowPoints / $pointCost) * $pointRupee) : 0;
                                                    $totalrewardPoints += $rowPoints;
                                                    $totalAmount += $rowAmount;
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= !empty($data->date) ? date('d-m-Y', strtotime($data->date)) : ''; ?></td>
                                            <td><?= get_title('electrician', ['id' => $data->userId], 'name'); ?></td>
                                            <td><?= get_title('electrician', ['id' => $data->userId], 'mobile'); ?></td>
                                            <td><?= $rowPoints; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rowAmount); ?></td>
                                            <td>
                                                <?php if ($data->status == '0') { ?>
                                                <label class="badge badge-warning">
                                                    <i class="fa fa-exclamation text-white"></i> Pending
                                                </label>
                                                <?php } elseif ($data->status == '1') { ?>
                                                <label class="badge badge-success">
                                                    <i class="fa fa-check-circle text-white"></i> Approved
                                                </label>
                                                <?php } elseif ($data->status == '2') { ?>
                                                <label class="badge badge-danger">
                                                    <i class="fa fa-ban text-white"></i> Rejected
                                                </label>    
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="withdrawalRequestInfo(<?= $data->id; ?>);" class="btn btn-primary btn-xs" title="More Info">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><?= $totalrewardPoints; ?></th>
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


<div class="modal fade" id="requestModal" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
    
    const withdrawalRequestInfo = (requestId) => {
        $("#requestModal .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/withdrawalRequestInfo/" + requestId);
        $('#requestModal').modal('show');
    };
</script>