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
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-dark">
                                    Vendor Wise Purchase & Sale Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <form action="<?= base_url(); ?>reports/vendorWisePurchaseSaleReport" method="GET">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 form-group">
                                                <label for="month">Select Month</label>
                                                <input type="month" name="month" value="<?= $this->input->get('month'); ?>" id="startDate" class="form-control form-control-sm" title="Select Month" />
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                <button type="submit" class="btn btn-sm bg-dark">
                                                    <i class="fa fa-search text-white"></i> Search
                                                </button>
                                                <a href="<?= base_url(); ?>reports/vendorWisePurchaseSaleReport" class="btn btn-danger btn-sm" title="Refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-dark">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Vendor</th>
                                                    <th>Purchase</th>
                                                    <th>Sale</th>
                                                    <th>Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $srNo = 0;
                                                    $totalPurchase = 0;
                                                    $totalSale = 0;
                                                    if (isset($branchData) && !empty($branchData)) {
                                                        foreach ($branchData as $key => $dataInfo) {
                                                            $srNo++;
                                                            $branchUser = get_title('users', ['id' => $dataInfo->BranchUserId], 'name');
                                                            
                                                            $month = $this->input->get('month');
                                                            $where = ['user_id' => $dataInfo->BranchUserId, 'CompanyBranchId' => $dataInfo->Id, 'status' => 1];
                                                            if ($month) {
                                                                $startDate = $month . '-01';
                                                                $endDate = date("Y-m-t", strtotime($startDate));
                                                                $where['invoiceDate >='] = $startDate;
                                                                $where['invoiceDate <='] = $endDate;
                                                            }
                                                            
                                                            $purchaseAmount = (float) ($this->db->select_sum('total')->where($where)->get('purchases')->row('total') ?? 0);
                                                            $estimatePurchaseAmount = (float) ($this->db->select_sum('total')->where($where)->get('estimate_purchase')->row('total') ?? 0);
                                                            $saleAmount = (float) ($this->db->select_sum('total')->where($where)->get('sales')->row('total') ?? 0);
                                                            $estimateSaleAmount = (float) ($this->db->select_sum('total')->where($where)->get('estimates')->row('total') ?? 0);
                                                            
                                                            $purchaseWithEstimateAmount = ($purchaseAmount + $estimatePurchaseAmount);
                                                            $saleWithEstimateAmount = ($saleAmount + $estimateSaleAmount);
                                                            $totalPurchase += $purchaseWithEstimateAmount;
                                                            $totalSale += $saleWithEstimateAmount;
                                                ?>
                                                <tr>
                                                    <td><?= $srNo; ?></td>
                                                    <td>
                                                        <?= $dataInfo->BranchName; ?>
                                                        <br />
                                                        <small>
                                                            <?= $branchUser; ?>
                                                        </small>
                                                    </td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($purchaseWithEstimateAmount); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($saleWithEstimateAmount); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" onclick="getBranchStockInfo(<?= $dataInfo->Id; ?>);" class="btn btn-dark btn-sm" title="Current Stock Info">
                                                            <i class="fa fa-shopping-bag"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" style="text-align: right;">Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalPurchase); ?></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalSale); ?></th>
                                                    <th></th>
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

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
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
    
    const getBranchStockInfo = (branchId) => {
        $("#infoModal .modal-dialog .modal-content").load("<?= base_url(); ?>reports/getBranchStockInfo/" + branchId);
        $('#infoModal').modal('show');
    };
</script>