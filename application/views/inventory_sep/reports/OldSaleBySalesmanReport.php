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
                <div class="col-lg-12">
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-dark">Old Sale By Salesman Report</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-1 col-sm-1">
                                        <label>Estimate</label>
                                        <br />
                                        <input type="checkbox" name="Estimate" value="True" id="Estimate" <?= $this->input->get('Estimate') == True ? "checked" : "unchecked"; ?> />
                                        Estimate
                                    </div>
                                    <div class="col-md-3 col-sm-3 form-group">
                                        <label for="Salesman">Salesman</label>
                                        <select name="Salesman" id="Salesman" class="form-control form-control-sm" title="Salesman">
                                            <option <?= $this->input->get('Salesman') == "All" ? "selected" : ""; ?> value="All" <?= set_select('Salesman', 'All'); ?>>All</option>
                                            <?php if (!empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                            <option <?= $this->input->get('Salesman') == $Salesman->Id ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>" <?= set_select('Salesman', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2 form-group">
                                        <label for="From">From</label>
                                        <input type="date" name="From" value="<?= $this->input->get('From') ? $this->input->get('From') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-2 form-group">
                                        <label for="To">To</label>
                                        <input type="date" name="To" value="<?= $this->input->get('To') ? $this->input->get('To') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>"  id="To" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-2 form-group" style="margin-top: 28px;">
                                        <button type="submit" class="btn btn-sm bg-dark">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/OldSaleBySalesmanReport" class="btn btn-sm btn-danger" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php if ($this->input->get('Estimate') == "") { ?>
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Invoice Date</th>
                                            <th>Invoice Number</th>
                                            <th>Customer Name</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Salesman</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Sr = 0;
                                            $Total = 0;
                                            $Paid = 0;
                                            $Balance = 0;
                                            if (isset($SalesData) && !empty($SalesData)) {
                                                foreach ($SalesData as $key => $Sales) {
                                                    $Sr++;
                                                    $Total += (float) ($Sales->total ?? 0);
                                                    $Paid += (float) ($Sales->paid_amount ?? 0);
                                                    $Balance += ((float) ($Sales->total ?? 0) - (float) ($Sales->paid_amount ?? 0));
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Sales->invoiceDate)); ?></td>
                                            <td><?= $Sales->invoiceNumber; ?></td>
                                            <td><?= get_title('parties', ['id' => $Sales->customerId], 'name'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sales->total ?? 0); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sales->paid_amount ?? 0); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(((float) ($Sales->total ?? 0) - (float) ($Sales->paid_amount ?? 0))); ?></td>
                                            <td>
                                                <?php if ($Sales->paid_amount == $Sales->total) { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } elseif ($Sales->paid_amount == NULL || $Sales->paid_amount == 0) { ?>
                                                <label class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle text-white"></i> UnPaid
                                                </label>
                                                <?php } else { ?>
                                                <label class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle text-white"></i> Partial
                                                </label>
                                                <?php } ?>
                                            </td>
                                            <td><?= get_title('employees', ['Id' => $Sales->OldSalesmanId], 'Name'); ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Sale/printSale/<?= $Sales->id; ?>" class="btn btn-primary btn-xs" title="Print" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Paid ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Balance ?? 0); ?></th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('Estimate') == True) { ?>
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Type</th>
                                            <th>Invoice Date</th>
                                            <th>Invoice Number</th>
                                            <th>Customer Name</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Salesman</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $UserId = $this->session->userdata('user_id');
                                            $CompId = $this->session->userdata('comp_id');
                                            $CompBranchId = $this->session->userdata('CompBranchId');
                                            $FinancialYear = $this->session->userdata('financial_year');

                                            $SalesWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                                            if ($this->session->userdata('BranchRole')) {
                                                $SalesWhere['CompanyBranchId'] = $CompBranchId;
                                            }

                                            if ($this->input->get('From') && $this->input->get('To')) {
                                                $SalesWhere['invoiceDate >= '] = $this->input->get('From');
                                                $SalesWhere['invoiceDate <= '] = $this->input->get('To');
                                            } else {
                                                $SalesWhere['invoiceDate >= '] = date('Y-m-d');
                                                $SalesWhere['invoiceDate <= '] = date('Y-m-d');
                                            }

                                            $Salesman = $this->input->get('Salesman');
                                            $SalesQuery = '';
                                            $EstimatesQuery = '';
                                            if ($Salesman == "All") {
                                                $SalesmanSaleQuery = $this->db->select('OldSalesmanId')->where($SalesWhere)->where('OldSalesmanId IS NOT NULL')->get('sales');
                                                if ($SalesmanSaleQuery->num_rows()) {
                                                    $SaleOldSalesmanId = [];
                                                    foreach ($SalesmanSaleQuery->result() as $key => $SaleData) {
                                                        $SaleOldSalesmanId[] = $SaleData->OldSalesmanId;
                                                    }
                                                    $SalesQuery = $this->db->select('id, type, invoiceDate, invoiceNumber, customerId, total, paid_amount, OldSalesmanId')->where($SalesWhere)->where_in('OldSalesmanId', $SaleOldSalesmanId)->get_compiled_select('sales');
                                                    
                                                }
                                                
                                                $SalesmanEstimateQuery = $this->db->select('OldSalesmanId')->where($SalesWhere)->where('OldSalesmanId IS NOT NULL')->get('estimates');
                                                if ($SalesmanEstimateQuery->num_rows()) {
                                                    $EstimateOldSalesmanId = [];
                                                    foreach ($SalesmanEstimateQuery->result() as $key => $EstimateData) {
                                                        $EstimateOldSalesmanId[] = $EstimateData->OldSalesmanId;
                                                    }
                                                    $EstimatesQuery = $this->db->select('id, type, invoiceDate, invoiceNumber, customerId, total, paid_amount, OldSalesmanId')->where($SalesWhere)->where_in('OldSalesmanId', $EstimateOldSalesmanId)->get_compiled_select('estimates');
                                                }
                                            } else {
                                                $SalesQuery = $this->db->select('id, type, invoiceDate, invoiceNumber, customerId, total, paid_amount, OldSalesmanId')->where($SalesWhere)->where('OldSalesmanId', $Salesman)->get_compiled_select('sales');
                                                $EstimatesQuery = $this->db->select('id, type, invoiceDate, invoiceNumber, customerId, total, paid_amount, OldSalesmanId')->where($SalesWhere)->where('OldSalesmanId', $Salesman)->get_compiled_select('estimates');
                                            }
                                            
                                            if ($SalesQuery && $EstimatesQuery) {
                                                $AllSales = $this->db->query($SalesQuery . " UNION " . $EstimatesQuery . " ORDER BY id DESC")->result();
                                            } else if ($SalesQuery) {
                                                $AllSales = $this->db->query($SalesQuery . " ORDER BY id DESC")->result();
                                            } else if ($EstimatesQuery) {
                                                $AllSales = $this->db->query($EstimatesQuery . " ORDER BY id DESC")->result();
                                            }
                                            
                                            $Sr = 0;
                                            $Total = 0;
                                            $Paid = 0;
                                            $Balance = 0;
                                            if (isset($AllSales) && !empty($AllSales)) {
                                                foreach ($AllSales as $key => $Data) {
                                                    $Sr++;
                                                    $Total += (float) ($Data->total ?? 0);
                                                    $Paid += (float) ($Data->paid_amount ?? 0);
                                                    $Balance += ((float) ($Data->total ?? 0) - (float) ($Data->paid_amount ?? 0));
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $Data->type; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->invoiceDate)); ?></td>
                                            <td><?= $Data->invoiceNumber; ?></td>
                                            <td><?= get_title('parties', ['id' => $Data->customerId], 'name'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Data->total ?? 0); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Data->paid_amount ?? 0); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(((float) ($Data->total ?? 0) - (float) ($Data->paid_amount ?? 0))); ?></td>
                                            <td>
                                                <?php if ($Data->paid_amount == $Data->total) { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } elseif ($Data->paid_amount == NULL || $Data->paid_amount == 0) { ?>
                                                <label class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle text-white"></i> UnPaid
                                                </label>
                                                <?php } else { ?>
                                                <label class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle text-white"></i> Partial
                                                </label>
                                                <?php } ?>
                                            </td>
                                            <td><?= get_title('employees', ['Id' => $Data->OldSalesmanId], 'Name'); ?></td>
                                            <td>
                                                <?php if ($Data->type == 'Sale') { ?>
                                                <a href="<?= base_url(); ?>Sale/printSale/<?= $Data->id; ?>" class="btn btn-primary btn-xs" title="Print" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <?php } if ($Data->type == 'Estimate') { ?>
                                                <a href="<?= base_url(); ?>Estimate/printEstimate/<?= $Data->id; ?>" class="btn btn-primary btn-xs" title="Print" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Paid ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Balance ?? 0); ?></th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>                                    
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-center font-italic text-danger">
                                Note :- If you change the party salesman and previous salesman create sales that's record appear here.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#Salesman').select2();
        
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
</script>