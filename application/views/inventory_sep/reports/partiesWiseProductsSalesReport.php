<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    th, td {
        white-space: nowrap;
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
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <div class="card card-fuchsia card-outline">
                                                <div class="card-header">
                                                        <div class="card-title">
                                                                <h5 class="text-fuchsia">
                                                                        Sale Invoice Product Report
                                                                </h5>
                                                        </div>
                                                </div>
                                                <div class="card-body">
                                                        <div class="row">
                                                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                                                        <form id="reportForm" action="<?= base_url(); ?>reports/partiesWiseProductsSalesReport" method="GET">
                                                                                <?php
                                                                                    $selectedPartyId = function_exists('input_get_array') ? input_get_array('partyId') : (array) ($this->input->get('partyId') ?: []);
                                                                                    $selectedSalesmanId = function_exists('input_get_array') ? input_get_array('salesmanId') : (array) ($this->input->get('salesmanId') ?: []);
                                                                                    $selectedProductId = function_exists('input_get_array') ? input_get_array('productId') : (array) ($this->input->get('productId') ?: []);
                                                                                    $selectedCategoryId = function_exists('input_get_array') ? input_get_array('categoryId') : (array) ($this->input->get('categoryId') ?: []);
                                                                                    $selectedBrandId = function_exists('input_get_array') ? input_get_array('brandId') : (array) ($this->input->get('brandId') ?: []);
                                                                                ?>
                                                                                <div class="row">
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                            <label for="partyId">
                                                                                                    Parties
                                                                                                    <a href="javascript:void(0);" id="partyRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="partyId[]" id="partyId" class="form-control form-control-sm" title="Party Name" multiple="">
                                                                                                <option <?= in_array('All', $selectedPartyId, true) ? "selected" : ""; ?> value="All">All Parties</option>
                                                                                                <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                                                                                <option <?= in_array($party->id, $selectedPartyId) ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                            <label for="salesmanId">
                                                                                                    Salesman's
                                                                                                    <a href="javascript:void(0);" id="salesmanRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="salesmanId[]" id="salesmanId" class="form-control form-control-sm" title="Salesman's" multiple="">
                                                                                                <option <?= in_array('All', $selectedSalesmanId, true) ? "selected" : ""; ?> value="All" <?= set_select('salesmanId', 'All'); ?>>All</option>
                                                                                                <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $salesman) { ?>
                                                                                                <option <?= in_array($salesman->Id, $selectedSalesmanId) ? "selected" : ""; ?> value="<?= $salesman->Id; ?>" <?= set_select('salesmanId', $salesman->Id); ?>><?= $salesman->Name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-4 form-group">
                                                                                            <label for="productId">
                                                                                                    Products
                                                                                                    <a href="javascript:void(0);" id="productRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="productId[]" id="productId" class="form-control form-control-sm" title="Products" multiple="">
                                                                                                <option <?= in_array('All', $selectedProductId, true) ? "selected" : ""; ?> value="All" <?= set_select('productId', 'All'); ?>>All</option>
                                                                                                <?php if (!empty($products)) { foreach ($products as $key => $product) { ?>
                                                                                                <option <?= in_array($product->id, $selectedProductId) ? "selected" : ""; ?> value="<?= $product->id; ?>" <?= set_select('productId', $product->id); ?>><?= $product->p_name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                            <label for="categoryId">
                                                                                                    Categories
                                                                                                    <a href="javascript:void(0);" id="categoryRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="categoryId[]" id="categoryId" class="form-control form-control-sm" title="Categories" multiple="">
                                                                                                <option <?= in_array('All', $selectedCategoryId, true) ? "selected" : ""; ?> value="All" <?= set_select('categoryId', 'All'); ?>>All</option>
                                                                                                <?php if (!empty($categories)) { foreach ($categories as $key => $category) { ?>
                                                                                                <option <?= in_array($category->id, $selectedCategoryId) ? "selected" : ""; ?> value="<?= $category->id; ?>" <?= set_select('categoryId', $category->id); ?>><?= $category->name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                            <label for="brandId">
                                                                                                    Brands
                                                                                                    <a href="javascript:void(0);" id="brandRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="brandId[]" id="brandId" class="form-control form-control-sm" title="Brands" multiple="">
                                                                                                <option <?= in_array('All', $selectedBrandId, true) ? "selected" : ""; ?> value="All" <?= set_select('brandId', 'All'); ?>>All</option>
                                                                                                <?php if (!empty($brands)) { foreach ($brands as $key => $brand) { ?>
                                                                                                <option <?= in_array($brand->id, $selectedBrandId) ? "selected" : ""; ?> value="<?= $brand->id; ?>" <?= set_select('brandId', $brand->id); ?>><?= $brand->name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                                <label for="from">From</label>
                                                                                                <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : date('Y-m-01')); ?>" id="from" class="form-control form-control-sm" title="From" />
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                                <label for="to">To</label>
                                                                                                <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : date('Y-m-d')); ?>" id="to" class="form-control form-control-sm" title="To" />
                                                                                        </div>
                                                                                        <div class="col-md-1 col-sm-1 form-group">
                                                                                            <label for="estimate">Estimate</label>
                                                                                            <br />
                                                                                            <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                                                                            Estimate
                                                                                        </div>
                                                                                        <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                                                                <button type="submit" class="btn btn-sm bg-fuchsia">
                                                                                                        <i class="fa fa-search text-white"></i> Search
                                                                                                </button>
                                                                                                <a href="<?= base_url(); ?>reports/partiesWiseProductsSalesReport" class="btn btn-danger btn-sm" title="Refresh">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                </a>
                                                                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm" title="Previous Month" onclick="return previousMonth();">
                                                                                                    <i class="fa fa-calendar-minus-o"></i>
                                                                                                </a>
                                                                                                <a href="javascript:void(0);" class="btn btn-warning btn-sm" title="Current Month" onclick="return currentMonth();">
                                                                                                    <i class="fa fa-calendar-check-o"></i>
                                                                                                </a>
                                                                                                <a href="javascript:void(0);" class="btn btn-dark btn-sm" title="Next Month" onclick="return nextMonth();">
                                                                                                    <i class="fa fa-calendar-plus-o"></i>
                                                                                                </a>
                                                                                        </div>
                                                                                </div>
                                                                        </form>
                                                                </div>
                                                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                                                        <div class="table-responsive mt-2">
                                                                                <table class="table table-sm table-bordered">
                                                                                        <?php if ($this->input->get('estimate') == "") { ?>
                                                                                        <thead class="bg-fuchsia">
                                                                                                <tr>
                                                                                                        <th class="text-white">Sr.</th>
                                                                                                        <th class="text-white">Customer Name</th>
                                                                                                        <th class="text-white">Product Name</th>
                                                                                                        <th class="text-white">HSN Code</th>
                                                                                                        <th class="text-white">Tax</th>
                                                                                                        <th class="text-white">Qty</th>
                                                                                                        <th class="text-white">Taxable Amt</th>
                                                                                                        <th class="text-white">Tax Amt</th>
                                                                                                        <th class="text-white">Total Amt</th>
                                                                                                        <th class="text-white">Salesman</th>
                                                                                                </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                                <?php
                                                                                                        if (isset($saleInvoicesProducts) && !empty($saleInvoicesProducts)) {
                                                                                                                $srNo = 0;
                                                                                                                $totalQty = 0;
                                                                                                                $totalTaxable = 0;
                                                                                                                $totalTax = 0;
                                                                                                                $totalAmount = 0;
                                                                                                                foreach ($saleInvoicesProducts as $key => $invoiceProduct) {
                                                                                                                        $srNo++;
                                                                                                                        $totalQty += $invoiceProduct->saleQty;
                                                                                                                        $totalTaxable += $invoiceProduct->taxableAmount;
                                                                                                                        $totalTax += $invoiceProduct->taxAmount;
                                                                                                                        $totalAmount += $invoiceProduct->netAmount;
                                                                                                                        
                                                                                                                        $partyInfo = $this->db->select('name, SalesmanId')->where('id', $invoiceProduct->customerId)->get('parties')->row();
                                                                                                                        $salesmanName = get_title('employees', ['Id' => $partyInfo->SalesmanId], 'Name');
                                                                                                ?>
                                                                                                <tr>
                                                                                                        <td><?= $srNo; ?></td>
                                                                                                        <td><?= $partyInfo->name; ?></td>
                                                                                                        <td><?= $invoiceProduct->p_name; ?></td>
                                                                                                        <td><?= $invoiceProduct->hsn; ?></td>
                                                                                                        <td><?= intval($invoiceProduct->tax); ?> <i class="fa fa-percentage"></i></td>
                                                                                                        <td><?= indianMoneyFormat($invoiceProduct->saleQty); ?></td>
                                                                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($invoiceProduct->taxableAmount); ?></td>
                                                                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($invoiceProduct->taxAmount); ?></td>
                                                                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($invoiceProduct->netAmount); ?></td>
                                                                                                        <td><?= $salesmanName; ?></td>
                                                                                                </tr>
                                                                                                <?php } } ?>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                                <tr>
                                                                                                        <th colspan="5" style="text-align: right;">Total</th>
                                                                                                        <th><?= indianMoneyFormat($totalQty); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTaxable); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTax); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
                                                                                                        <th></th>
                                                                                                </tr>
                                                                                        </tfoot>
                                                                                        <?php } elseif ($this->input->get('estimate') == true) { ?>
                                                                                        <thead class="bg-fuchsia">
                                                                                                <tr>
                                                                                                        <th class="text-white">Sr.</th>
                                                                                                        <th class="text-white">Type</th>
                                                                                                        <th class="text-white">Customer Name</th>
                                                                                                        <th class="text-white">Product Name</th>
                                                                                                        <th class="text-white">HSN Code</th>                                                                                                        
                                                                                                        <th class="text-white">Tax</th>
                                                                                                        <th class="text-white">Qty</th>
                                                                                                        <th class="text-white">Taxable Amt</th>
                                                                                                        <th class="text-white">Tax Amt</th>
                                                                                                        <th class="text-white">Total Amt</th>
                                                                                                        <th class="text-white">Salesman</th>
                                                                                                </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                                <?php
                                                                                                        if (isset($saleWithEstimateInvoicesProducts) && !empty($saleWithEstimateInvoicesProducts)) {
                                                                                                                $srNo = 0;
                                                                                                                $totalQty = 0;
                                                                                                                $totalTaxable = 0;
                                                                                                                $totalTax = 0;
                                                                                                                $totalAmount = 0;
                                                                                                                foreach ($saleWithEstimateInvoicesProducts as $key => $invoiceProduct) {
                                                                                                                        $srNo++;
                                                                                                                        $totalQty += $invoiceProduct->saleQty;
                                                                                                                        $totalTaxable += $invoiceProduct->taxableAmount;
                                                                                                                        $totalTax += $invoiceProduct->taxAmount;
                                                                                                                        $totalAmount += $invoiceProduct->netAmount;
                                                                                                                        
                                                                                                                        $partyInfo = $this->db->select('name, SalesmanId')->where('id', $invoiceProduct->customerId)->get('parties')->row();
                                                                                                                        $salesmanName = get_title('employees', ['Id' => $partyInfo->SalesmanId], 'Name');
                                                                                                ?>
                                                                                                <tr>
                                                                                                        <td><?= $srNo; ?></td>
                                                                                                        <td><?= $invoiceProduct->type; ?></td>
                                                                                                        <td><?= $partyInfo->name; ?></td>
                                                                                                        <td><?= $invoiceProduct->p_name; ?></td>
                                                                                                        <td><?= $invoiceProduct->hsn; ?></td>
                                                                                                        <td><?= intval($invoiceProduct->tax); ?> <i class="fa fa-percentage"></i></td>
                                                                                                        <td><?= indianMoneyFormat($invoiceProduct->saleQty); ?></td>
                                                                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($invoiceProduct->taxableAmount); ?></td>
                                                                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($invoiceProduct->taxAmount); ?></td>
                                                                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($invoiceProduct->netAmount); ?></td>
                                                                                                        <td><?= $salesmanName; ?></td>
                                                                                                </tr>
                                                                                                <?php } } ?>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                                <tr>
                                                                                                        <th colspan="6" style="text-align: right;">Total</th>
                                                                                                        <th><?= indianMoneyFormat($totalQty); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTaxable); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTax); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
                                                                                                        <th></th>
                                                                                                </tr>
                                                                                        </tfoot>                                                                                        
                                                                                        <?php } ?>
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
        $('#partyId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $('#salesmanId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $('#productId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $('#categoryId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $('#brandId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $("#partyRefresh").click(function () {
            $("#partyId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#partyId").multiselect("refresh");
        });

        $("#salesmanRefresh").click(function () {
            $("#salesmanId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#salesmanId").multiselect("refresh");
        });

        $("#productRefresh").click(function () {
            $("#productId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#productId").multiselect("refresh");
        });

        $("#categoryRefresh").click(function () {
            $("#categoryId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#categoryId").multiselect("refresh");
        });

        $("#brandRefresh").click(function () {
            $("#brandId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#brandId").multiselect("refresh");
        });
        
        function previousMonth() {
            var fromDate = document.getElementById('from').value;
            fromDate = new Date(fromDate);
            fromDate.setMonth(fromDate.getMonth() - 1);
            var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
            var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
            document.getElementById('to').value = lastDay;
            document.getElementById('from').value = firstDay;
            var form = $("#reportForm").serialize();        
            window.location.href = "<?= base_url(); ?>reports/partiesWiseProductsSalesReport?" + form;
        }

        function currentMonth() {
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
            document.getElementById('from').value = today;
            document.getElementById('to').value = monthlastdate;
            var form = $("#reportForm").serialize();        
            window.location.href = "<?= base_url(); ?>reports/partiesWiseProductsSalesReport?" + form;
        }

        function nextMonth() {
            var fromDate = document.getElementById('from').value;
            fromDate = new Date(fromDate);
            fromDate.setMonth(fromDate.getMonth() + 1);
            var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
            var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
            document.getElementById('to').value = lastDay;
            document.getElementById('from').value = firstDay;
            var form = $("#reportForm").serialize();        
            window.location.href = "<?= base_url(); ?>reports/partiesWiseProductsSalesReport?" + form;
        }        
        
        $(document).ready(function() {
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