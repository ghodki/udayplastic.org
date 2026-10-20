<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .card-body {
        padding: 0.25rem 0.25rem;
    }
    .btn-group {
        display: block;
    }
    .custom-select {
        height: calc(2rem + 1px);
        line-height: 1;
    }
    .multiselect-container {
        transform: translate3d(0px, 32px, 0px) !important;
    }
    .multiselect-container .multiselect-option {
        padding: 0px 5px 0px 5px;
    }
    .dropdown-menu.show {
        height: auto;
        max-height: 200px;
        overflow-y: scroll;
    }
    .dropdown-menu {
        min-width: 24.6rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-orange">Products Sales Report</h5>
                            </div>
                            <div class="pull-right">
                                <a href="javascript:void(0);" class="btn btn-primary btn-xs" title="Previous Month" onclick="return PreviousMonth();">
                                    <i class="fa fa-calendar-minus-o"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-warning btn-xs" title="Current Month" onclick="return CurrentMonth();">
                                    <i class="fa fa-calendar-check-o"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-dark btn-xs" title="Next Month" onclick="return NextMonth();">
                                    <i class="fa fa-calendar-plus-o"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" id="ReportForm">
                                <div class="row">
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="Parties">Parties</label>
                                        <select name="Parties[]" id="Parties" class="form-control form-control-sm" title="Party Name" multiple="">
                                            <option <?= in_array('All', $this->input->get('Parties')) ? "selected" : ""; ?> value="All">All Parties</option>
                                            <?php if (!empty($Parties)) { foreach ($Parties as $key => $Party) { ?>
                                            <option <?= in_array($Party->id, $this->input->get('Parties')) ? "selected" : ""; ?> value="<?= $Party->id; ?>"><?= $Party->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="Salesman">Salesman</label>
                                        <select name="Salesman[]" id="Salesman" class="form-control form-control-sm" title="Salesman" multiple="">
                                            <option <?= in_array('All', $this->input->get('Salesman')) ? "selected" : ""; ?> value="All" <?= set_select('Salesman', 'All'); ?>>All</option>
                                            <?php if (!empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                            <option <?= in_array($Salesman->Id, $this->input->get('Salesman')) ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>" <?= set_select('Salesman', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="Products">Products</label>
                                        <select name="Products[]" id="Products" class="form-control form-control-sm" title="Products" multiple="">
                                            <option <?= in_array('All', $this->input->get('Products')) ? "selected" : ""; ?> value="All" <?= set_select('Products', 'All'); ?>>All</option>
                                            <?php if (!empty($Products)) { foreach ($Products as $key => $Product) { ?>
                                            <option <?= in_array($Product->id, $this->input->get('Products')) ? "selected" : ""; ?> value="<?= $Product->id; ?>" <?= set_select('Products', $Product->Id); ?>><?= $Product->p_name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="From">From</label>
                                        <input type="date" name="From" value="<?= $this->input->get('From') ? $this->input->get('From') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="To">To</label>
                                        <input type="date" name="To" value="<?= $this->input->get('To') ? $this->input->get('To') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>"  id="To" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-xs bg-orange">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/ProductsSalesReport" class="btn btn-danger btn-xs" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm" id="DataTable">
                                    <thead class="bg-orange">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Customer Name</th>
                                            <th>Product Name</th>
                                            <th>Sale Qty</th>
                                            <th>Taxable</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Profit</th>
                                            <th>Salesman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!empty($SallingProducts)) { 
                                                $Sr = 0;
                                                $TotalSaleQty = 0;
                                                $TotalTaxableAmount = 0;
                                                $TotalTaxAmount = 0;
                                                $Total = 0;
                                                $TotalProfit = 0;
                                                foreach ($SallingProducts as $key => $Sales) {
                                                    $Sr++;
                                                    $TotalSaleQty += $Sales->SaleQty;
                                                    $TotalTaxableAmount += $Sales->TaxableAmount;
                                                    $TotalTaxAmount += $Sales->TaxAmount;
                                                    $Total += $Sales->NetAmount;
                                                    
                                                    $averageSalePrice = ($Sales->SalePrice / $Sales->SaleQty);
                                                    $averagePurchasePrice = ($Sales->PurchasePrice / $Sales->SaleQty);
                                                    $Profit = (($averageSalePrice * $Sales->SaleQty) - ($averagePurchasePrice * $Sales->SaleQty));
                                                    $TotalProfit += $Profit;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= get_title('parties', ['id' => $Sales->CustomerId], 'name'); ?></td>
                                            <td><?= $Sales->productname; ?></td>
                                            <td><?= round($Sales->SaleQty, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($Sales->TaxableAmount, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= $Sales->TaxAmount; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= $Sales->NetAmount; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($Profit, 2); ?></td>
                                            <td><?= get_title('employees', ['Id' => $Sales->SalesmanId], 'Name'); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total</th>
                                            <th><?= $TotalSaleQty; ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalTaxableAmount, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= $TotalTaxAmount; ?></th>
                                            <th><i class="fa fa-inr"></i> <?= $Total; ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalProfit, 2); ?></th>
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('#Parties').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $('#Salesman').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $('#Products').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    function PreviousMonth() {
        var fromDate = document.getElementById('From').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('To').value = lastDay;
        document.getElementById('From').value = firstDay;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/ProductsSalesReport?" + form;
    }

    function CurrentMonth() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }
        today = yyyy + '-' + mm + '-' + '01';
        var monthlastdate = yyyy + '-' + mm + '-' + dd;
        document.getElementById('From').value = today;
        document.getElementById('To').value = monthlastdate;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/ProductsSalesReport?" + form;
    }

    function NextMonth() {
        var fromDate = document.getElementById('From').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('To').value = lastDay;
        document.getElementById('From').value = firstDay;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/ProductsSalesReport?" + form;
    }

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_ProductSalesReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_ProductSalesReport";
        <?php } ?>        
        
        $("#DataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pageLength',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });
    });
</script>