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
                    <div class="card card-olive card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-olive">
                                    Customers Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (empty($this->session->userdata('BranchRole'))) { ?>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <form id="reportForm" action="<?= base_url(); ?>reports/customersReport" method="GET">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 form-group">
                                                <label for="branchId">Branches</label>
                                                <select name="branchId" id="branchId" class="form-control form-control-sm" title="Branch Name">
                                                    <option <?= $this->input->get('branchId') == 'All' ? "selected" : ""; ?> value="All">All Branches</option>
                                                    <?php if (!empty($branches)) { foreach ($branches as $key => $branch) { ?>
                                                    <option <?= $this->input->get('branchId') == $branch->Id ? "selected" : ""; ?> value="<?= $branch->Id; ?>"><?= $branch->BranchName; ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                <button type="submit" class="btn btn-sm bg-olive">
                                                    <i class="fa fa-search text-white"></i> Search
                                                </button>
                                                <a href="<?= base_url(); ?>reports/customersReport" class="btn btn-danger btn-sm" title="Refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php } ?>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-olive">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">Acc. Group</th>
                                                    <th class="text-white">Name</th>
                                                    <th class="text-white">Contact Person</th>
                                                    <th class="text-white">Mobile</th>
                                                    <th class="text-white">Email</th>
                                                    <th class="text-white">PAN</th>
                                                    <th class="text-white">GST</th>
                                                    <th class="text-white">GST Type</th>
                                                    <th class="text-white">State</th>
                                                    <th class="text-white">City</th>
                                                    <th class="text-white">Area</th>
                                                    <th class="text-white">Address</th>
                                                    <th class="text-white">Pin</th>
                                                    <th class="text-white">Credit Day</th>
                                                    <th class="text-white">Type</th>
                                                    <th class="text-white">Salesman</th>
                                                    <th class="text-white">Category</th>
                                                    <th class="text-white">Last Invoice Date</th>
                                                    <th class="text-white">Total Invoices</th>
                                                    <th class="text-white">Total Amount</th>
                                                    <th class="text-white">Branch Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $srNo = 0;
                                                    $totalInvoice = 0;
                                                    $totalAmount = 0;
                                                    if (isset($customers) && !empty($customers)) {
                                                        foreach ($customers as $key => $customer) {
                                                            $srNo++;
                                                            $accountName = get_title('accounting_group', ['id' => $customer->group_head], 'name');
                                                            $stateName = get_title('state_list', ['id' => $customer->state], 'state');
                                                            $cityName = get_title('districts', ['id' => $customer->city], 'name');
                                                            $salesmanName = get_title('employees', ['Id' => $customer->SalesmanId], 'Name');
                                                            $categoryName = get_title('party_category', ['id' => $customer->categoryId], 'name');
                                                            $branchName = get_title('companybranch', ['Id' => $customer->CompanyBranchId], 'BranchName');
                                                            
                                                            $estimateLastInvoiceDate = $this->db->select('invoiceDate')->where('customerId', $customer->id)->where('status', 1)->order_by('id', 'DESC')->limit(1)->get('estimates')->row('invoiceDate');
                                                            $saleLastInvoiceDate = $this->db->select('invoiceDate')->where('customerId', $customer->id)->where('status', 1)->order_by('id', 'DESC')->limit(1)->get('sales')->row('invoiceDate');                                                            
                                                            
                                                            $estimateInfo = $this->db->select('COUNT(invoiceNumber) AS totalEstimateInvoices, SUM(total) AS totalEstimateInoviceAmount')->where('customerId', $customer->id)->where('status', 1)->get('estimates')->row();
                                                            $salesInfo = $this->db->select('COUNT(invoiceNumber) AS totalSaleInvoices, SUM(total) AS totalSaleInoviceAmount')->where('customerId', $customer->id)->where('status', 1)->get('sales')->row();
                                                            $rowInvoiceCount = (float) (is_object($estimateInfo) ? ($estimateInfo->totalEstimateInvoices ?? 0) : 0)
                                                                + (float) (is_object($salesInfo) ? ($salesInfo->totalSaleInvoices ?? 0) : 0);
                                                            $rowInvoiceAmount = (float) (is_object($estimateInfo) ? ($estimateInfo->totalEstimateInoviceAmount ?? 0) : 0)
                                                                + (float) (is_object($salesInfo) ? ($salesInfo->totalSaleInoviceAmount ?? 0) : 0);
                                                            $totalInvoice += $rowInvoiceCount;
                                                            $totalAmount += $rowInvoiceAmount;
                                                ?>
                                                <tr>
                                                    <td><?= $srNo; ?></td>
                                                    <td><?= $accountName; ?></td>
                                                    <td><?= $customer->name; ?></td>
                                                    <td><?= $customer->ContactPerson; ?></td>
                                                    <td><?= $customer->contact; ?></td>
                                                    <td><?= $customer->email; ?></td>
                                                    <td><?= $customer->pan; ?></td>
                                                    <td><?= $customer->gst; ?></td>
                                                    <td><?= $customer->gstType; ?></td>
                                                    <td><?= $stateName; ?></td>
                                                    <td><?= $cityName; ?></td>
                                                    <td><?= $customer->area; ?></td>
                                                    <td><?= $customer->address_1; ?></td>
                                                    <td><?= $customer->pin; ?></td>
                                                    <td><?= $customer->credit_limit_day; ?></td>
                                                    <td><?= $customer->opBalnceType; ?></td>
                                                    <td><?= $salesmanName; ?></td>
                                                    <td><?= $categoryName; ?></td>
                                                    <td>
                                                        <?php
                                                            $saleTs = !empty($saleLastInvoiceDate) ? strtotime($saleLastInvoiceDate) : 0;
                                                            $estimateTs = !empty($estimateLastInvoiceDate) ? strtotime($estimateLastInvoiceDate) : 0;
                                                            if ($saleTs > $estimateTs) {
                                                               echo $saleLastInvoiceDate ? date('d-m-Y', strtotime($saleLastInvoiceDate)) : '';
                                                            } elseif ($estimateTs > $saleTs) {
                                                               echo $estimateLastInvoiceDate ? date('d-m-Y', strtotime($estimateLastInvoiceDate)) : '';
                                                            }                                                        
                                                        ?>
                                                    </td>
                                                    <td><?= $rowInvoiceCount; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rowInvoiceAmount); ?></td>
                                                    <td><?= $branchName; ?></td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="19" style="text-align: right;">Total</th>
                                                    <th><?= $totalInvoice; ?></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
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
        
        $('#branchId').select2();
    });
</script>