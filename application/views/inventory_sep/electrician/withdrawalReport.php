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
                <div class="col-md-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-dark">
                                    Withdrawal Report
                                </h3>
                            </div>
                            <a href="<?= base_url(); ?>electrician/insight/<?= $userId; ?>" class="btn bg-dark btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="pb-2"><?= $electricianName; ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Reward Points</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Remark</th>
                                            <th>Admin Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($withdrawalData) && !empty($withdrawalData)) {
                                                $rewardPointData = $this->db->select('rewardPoint, equivalentRupee')->get('reward_point_cost')->row();                                                
                                                $Sr = 0;
                                                $totalPoint = 0;
                                                $totalAmount = 0;
                                                foreach ($withdrawalData as $key => $data) {
                                                    $Sr++;
                                                    $totalPoint += $data->rewardPoints;
                                                    $totalAmount += (($data->rewardPoints / $rewardPointData->rewardPoint) * $rewardPointData->equivalentRupee);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                                            <td><?= $data->rewardPoints; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= (($data->rewardPoints / $rewardPointData->rewardPoint) * $rewardPointData->equivalentRupee); ?></td>
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
                                            <td><?= $data->remark; ?></td>
                                            <td><?= $data->adminRemark; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align: right;">Total</th>
                                            <th><?= $totalPoint; ?></th>
                                            <th><i class="fa fa-inr"></i> <?= $totalAmount; ?></th>
                                            <th colspan="3"></th>
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

<script type="text/javascript">
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
</script>