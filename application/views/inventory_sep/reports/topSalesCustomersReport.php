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
                                    Top Sales Customers Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-success">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Customer</th>
                                                    <th>Mobile</th>
                                                    <th>City</th>
                                                    <th>Invoices</th>
                                                    <th>Total Sale</th>
                                                    <th>Salesman</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $srNo = 0;
                                                    $totalSaleInvoice = 0;
                                                    $totalSale = 0;
                                                    if (isset($topSalesCustomers) && !empty($topSalesCustomers)) {
                                                        foreach ($topSalesCustomers as $key => $dataInfo) {
                                                            $srNo++;
                                                            
                                                            $totalSaleInvoice += (float) ($dataInfo->totalSaleInvoice ?? 0);
                                                            $totalSale += (float) ($dataInfo->totalAmount ?? 0);
                                                            
                                                            $partyInfo = $this->db->select('name, contact, city, SalesmanId')->where('id', $dataInfo->customerId)->get('parties')->row();
                                                            $salesmanName = !empty($partyInfo) ? get_title('employees', ['Id' => $partyInfo->SalesmanId], 'Name') : '';
                                                            $cityName = !empty($partyInfo) ? get_title('districts', ['id' => $partyInfo->city], 'name') : '';

                                                ?>
                                                <tr>
                                                    <td><?= $srNo; ?></td>
                                                    <td><?= !empty($partyInfo) ? $partyInfo->name : ''; ?></td>
                                                    <td><?= !empty($partyInfo) ? $partyInfo->contact : ''; ?></td>
                                                    <td><?= $cityName; ?></td>
                                                    <td><?= $dataInfo->totalSaleInvoice; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($dataInfo->totalAmount ?? 0); ?></td>
                                                    <td><?= $salesmanName; ?></td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" style="text-align: right;">Total</th>
                                                    <th><?= $totalSaleInvoice; ?></th>
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