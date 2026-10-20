<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .pagination {
        display: block;
    }
    .pagination a {
        color: black;
        float: left;
        padding: .25rem .5rem;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
        font-size: .875rem;
    }
    .pagination a.active {
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
    }
    a.page-link {
        padding: 6px 8px 6px 8px;
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
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">Popular Products</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 form-group">
                                        <label for="party">Parties</label>
                                        <select name="party[]" id="party" class="form-control form-control-sm" title="Party Name" multiple="">
                                            <?php $partyFilter = function_exists('input_get_array') ? input_get_array('party') : (array) ($this->input->get('party') ?: []); ?>
                                            <option <?= in_array('All', $partyFilter) ? "selected" : ""; ?> value="All">All Party</option>
                                            <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                            <option <?= in_array($party->id, $partyFilter) ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="salesman">Salesman</label>
                                        <select name="salesman[]" id="salesman" class="form-control form-control-sm" title="Salesman" multiple="">
                                            <?php $salesmanFilter = function_exists('input_get_array') ? input_get_array('salesman') : (array) ($this->input->get('salesman') ?: []); ?>
                                            <option <?= in_array('All', $salesmanFilter) ? "selected" : ""; ?> value="All" <?= set_select('salesman', 'All'); ?>>All</option>
                                            <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $sdata) { ?>
                                            <option <?= in_array($sdata->Id, $salesmanFilter) ? "selected" : ""; ?> value="<?= $sdata->Id; ?>" <?= set_select('salesman', $sdata->Id); ?>><?= $sdata->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1">
                                        <label>Estimate</label>
                                        <br />
                                        <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                        Estimate
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" name="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" id="fromDate" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="toDate">To Date</label>
                                        <input type="date" name="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>"  id="toDate" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-xs">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/Popularproducts" class="btn btn-info btn-xs" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive" id="PopullerProductsList">
                                <table class="table table-bordered table-sm" id="DataTable">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Product Name</th>
                                            <th>Sale Qty</th>
                                            <th>Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $TotalQty = 0;
                                            if (!empty($SallingProducts)) { 
                                                foreach ($SallingProducts as $key => $Data) { 
                                                    $Sr++;
                                                    $TotalQty += (float) ($Data->SaleQty ?? 0);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $Data->productname; ?></td>
                                            <td><?= round((float) ($Data->SaleQty ?? 0), 2); ?></td>
                                            <td><?= get_title('product', ['id' => $Data->product_id], 'unit_name'); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align: right;">Total Qty.</th>
                                            <th colspan="2"><?= round($TotalQty, 2); ?></th>
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Type</th>
                                            <th>Product Name</th>
                                            <th>Sale Qty</th>
                                            <th>Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $TotalQty = 0;
                                            if (!empty($SallingProducts)) { 
                                                foreach ($SallingProducts as $key => $Data) { 
                                                    $Sr++;
                                                    $TotalQty += (float) ($Data->SaleQty ?? 0);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $Data->type; ?></td>
                                            <td><?= $Data->productname; ?></td>
                                            <td><?= round((float) ($Data->SaleQty ?? 0), 2); ?></td>
                                            <td><?= get_title('product', ['id' => $Data->product_id], 'unit_name'); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total Qty.</th>
                                            <th colspan="2"><?= round($TotalQty, 2); ?></th>
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('#party').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $('#salesman').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $(function() {
        $("#DataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
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