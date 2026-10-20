<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .card {
        margin-bottom: 0rem;
    }
    .card-footer p {
        margin-bottom: 0rem;
    }
    .card-header, .card-body, .card-footer {
        padding: 5px;
    }
    .btn-group{
        display: block;
    }
    .custom-select {
        height: calc(2rem + 1px);
        line-height: 1;
    }
    .form-check-label {
        color: #000 !important;
        font-size: 14px;
    }
    table.dataTable tfoot th {
        padding: 10px 5px 6px 5px;
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
    th {
        padding: 5px !important;
    }
    td {
        padding: 5px !important;
        font-size: 14px;
        font-weight: 500;
        vertical-align: middle !important;
    }
    table.dataTable {
        border-collapse: collapse;
    }
    td label {
        margin-bottom: 0px;
    }
    .form-group {
        margin-bottom: 0rem;
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
                            <h4 class="card-title">Party Sale Report</h4>
                        </div>
                        <div class="card-body">
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET" style="padding-bottom: 10px;">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="from">From</label>
                                            <input type="date" name="from" value="<?= !empty($this->input->get('from')) ? $this->input->get('from') : date('Y-m-01'); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="to">To</label>
                                            <input type="date" name="to" value="<?= !empty($this->input->get('to')) ? $this->input->get('to') : date('Y-m-d'); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                        </div>
                                    </div>
                                        <div class="form-group col-md-3 col-sm-3">
                                                <label for="partyId">Party</label>
                                                <select name="partyId" id="partyId" class="form-control form-control-sm">
                                                        <option <?= $this->input->get('partyId') == "All" ? "selected" : ""; ?> value="All">Select All Parties</option>
                                                        <?php if (isset($parties) && !empty($parties)) { foreach ($parties as $key => $party) { ?>
                                                        <option <?= $this->input->get('partyId') == $party->id ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?>, <?= $party->area; ?>, <?= $party->contact; ?></option>
                                                        <?php } } ?>
                                                </select>
                                        </div>                                        
                                    <div class="form-group col-md-3 col-sm-3">
                                        <?php
                                            $salesman = function_exists('input_get_array') ? input_get_array('salesman') : (array) ($this->input->get('salesman') ?: []);
                                        ?>
                                        <label for="salesman">Salesman</label>
                                        <select name="salesman[]" id="salesman" class="form-control form-control-sm" multiple="multiple">
                                            <option <?= in_array('All', $salesman, true) ? "selected" : ""; ?> value="All" <?= set_select('salesman', 'All'); ?>>All</option>
                                            <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $sdata) { ?>
                                            <option <?= in_array($sdata->Id, $salesman) ? "selected" : ""; ?> value="<?= $sdata->Id; ?>" <?= set_select('salesman', $sdata->Id); ?>><?= $sdata->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Search
                                        </button>
                                        <a href="<?= base_url(); ?>sale/party_sale_report" class="btn btn-info btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Total Invoice</th>
                                            <th>Sale day ago</th>
                                            <th>Estimate day ago</th>
                                            <th>Total</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Salesman</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $totalInvoice = 0;
                                            $total = 0;
                                            $paidAmount = 0;
                                            $balance = 0;
                                            if (isset($partySales) && !empty($partySales)) {
                                                $n = 0;
                                                foreach ($partySales as $key => $row) {
                                                    if ($this->input->get('from') != "" && $this->input->get('to') != "") {
                                                        $from = $this->input->get('from');
                                                        $to = $this->input->get('to');
                                                    } else {
                                                        $from = date('Y-m-01');
                                                        $to = date('Y-m-d');
                                                    }
                                                    $where = ['invoiceDate >= ' => $from, 'invoiceDate <= ' => $to, 'customerId' => $row->id, 'status' => 1];
                                                    $salesmanFilter = function_exists('input_get_array') ? input_get_array('salesman') : (array) ($this->input->get('salesman') ?: []);
                                                    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                                                    if ($this->db->select('customerId')->where($where)->get('sales')->num_rows() || $this->db->select('customerId')->where($where)->get('estimates')->num_rows()) {
                                                        $this->db->select('invoiceDate, customerId, COUNT(customerId) AS SaleInvoice, SUM(total) AS SaleTotal, SUM(paid_amount) AS SalePaidAmount');
                                                        $this->db->where($where);
                                                        if (!empty($salesmanFilter) && !in_array('All', $salesmanFilter, true)) {
                                                            $this->db->where_in('SalesmanId', $salesmanFilter);
                                                        }
                                                        $Sales = $this->db->get('sales')->row();

                                                        $this->db->select('customerId, COUNT(customerId) AS EstimateInvoice, SUM(total) AS EstimateTotal, SUM(paid_amount) AS EstimatePaidAmount');
                                                        $this->db->where($where);
                                                        if (!empty($salesmanFilter) && !in_array('All', $salesmanFilter, true)) {
                                                            $this->db->where_in('SalesmanId', $salesmanFilter);
                                                        }
                                                        $Estimates = $this->db->get('estimates')->row();

                                                        $saleInvoice = (int) ($Sales->SaleInvoice ?? 0);
                                                        $estimateInvoice = (int) ($Estimates->EstimateInvoice ?? 0);
                                                        $saleTotal = (float) ($Sales->SaleTotal ?? 0);
                                                        $estimateTotal = (float) ($Estimates->EstimateTotal ?? 0);
                                                        $salePaid = (float) ($Sales->SalePaidAmount ?? 0);
                                                        $estimatePaid = (float) ($Estimates->EstimatePaidAmount ?? 0);

                                                        $n++;

                                                        $totalInvoice += ($saleInvoice + $estimateInvoice);
                                                        $total += ($saleTotal + $estimateTotal);
                                                        $paidAmount += ($salePaid + $estimatePaid);
                                                        $balance += (($saleTotal + $estimateTotal) - ($salePaid + $estimatePaid));
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $row->name; ?></td>
                                            <td><?= $row->contact; ?></td>
                                            <td><?= $row->gst; ?></td>
                                            <td><?= $saleInvoice + $estimateInvoice; ?></td>
                                            <td>
                                                <?php
                                                    $this->db->select('invoiceDate');
                                                    $this->db->where($where);
                                                    if (!empty($salesmanFilter) && !in_array('All', $salesmanFilter, true)) {
                                                        $this->db->where_in('SalesmanId', $salesmanFilter);
                                                    }
                                                    $this->db->order_by('invoiceDate', 'DESC');
                                                    $SalesTime = $this->db->get('sales')->row();
                                                    echo !empty($SalesTime?->invoiceDate) ? DifferenceFromDates($SalesTime->invoiceDate) : "0.0";
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $this->db->select('invoiceDate');
                                                    $this->db->where($where);
                                                    if (!empty($salesmanFilter) && !in_array('All', $salesmanFilter, true)) {
                                                        $this->db->where_in('SalesmanId', $salesmanFilter);
                                                    }
                                                    $this->db->order_by('invoiceDate', 'DESC');
                                                    $EstimatesTime = $this->db->get('estimates')->row();
                                                    echo !empty($EstimatesTime?->invoiceDate) ? DifferenceFromDates($EstimatesTime->invoiceDate) : "0.0";
                                                ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($saleTotal + $estimateTotal); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($salePaid + $estimatePaid); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($saleTotal + $estimateTotal) - ($salePaid + $estimatePaid)); ?></td>
                                            <td><?= get_title('employees', ['Id' => $row->SalesmanId], 'Name'); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="getInvoiceInfo(<?= $row->id; ?>);" class="btn btn-primary btn-sm" title="Info">
                                                        <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><?= $totalInvoice; ?></th>
                                            <th colspan="2"></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($paidAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($balance); ?></th>
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

<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content"></div>
        </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
        const getInvoiceInfo = (partyId) => {
                var from = $("#from").val();
                var to = $("#to").val();                
                $("#info .modal-dialog .modal-content").load("<?= base_url(); ?>sale/partyInvoiceInfo?from=" + from + "&to=" + to + "&partyId=" +  partyId);
                $('#info').modal('show');
        };
        
    $(document).ready(function () {
        $('#partyId').select2();
    });
    
    $('#salesman').multiselect({
        buttonWidth: 'auto'
    });
    
    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>sale/party_sale_report?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };
    
    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_PartySaleReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_PartySaleReport";
        <?php } ?>
            
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            order: [[7, 'desc']],
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