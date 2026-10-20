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
                            <h4 class="card-title">Parties Sales Report</h4>
                        </div>
                        <div class="card-body">
                            <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") { ?>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET" style="padding-bottom: 10px;">
                                <div class="row">
                                    <?php 
                                        if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                            $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeInfo->Id, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                                            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                                            
                                            $salesmans = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $SalesmanIds)->get('employees')->result();
                                            $salesman_selected = [];
                                            foreach (input_get_array('salesman') as $key => $sids) {
                                                $salesman_selected[] = $sids;
                                            }
                                    ?>
                                    <div class="form-group col-md-4 col-sm-4">
                                        <label for="salesman">Salesman</label>
                                        <select name="salesman[]" id="salesman" class="form-control form-control-sm" multiple="multiple">
                                            <option <?= in_array('All', $salesman_selected) ? 'selected' : ''; ?> value="All" <?= set_select('salesman', 'All'); ?>>All</option>
                                            <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $sdata) { ?>
                                            <option <?= in_array($sdata->Id, $salesman_selected) ? 'selected' : ''; ?> value="<?= $sdata->Id; ?>" <?= set_select('salesman', $sdata->Id); ?>><?= $sdata->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group col-md-3 col-sm-3" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Search
                                        </button>
                                        <a href="<?= base_url(); ?>Employee/PartiesSalesReport" class="btn btn-info btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $TotalInvoice = 0;
                                            $Total = 0;
                                            $PaidAmount = 0;
                                            $Balance = 0;
                                            if ($partydata) {
                                                $n = 0;
                                                foreach ($partydata as $key => $row) {
                                                    $where = ['customerId' => $row->id, 'status' => 1];
                                                    if ($this->db->select('customerId')->where($where)->get('sales')->num_rows() || $this->db->select('customerId')->where($where)->get('estimates')->num_rows()) {
                                                        $this->db->select('customerId, COUNT(customerId) AS SaleInvoice, SUM(total) AS SaleTotal, SUM(paid_amount) AS SalePaidAmount');
                                                        $this->db->where($where);
                                                        if ($this->input->get('salesman') != "") {
                                                            if (!in_array('All', input_get_array('salesman'))) {
                                                                $this->db->where_in('SalesmanId', input_get_array('salesman'));
                                                            }
                                                        }
                                                        $Sales = $this->db->get('sales')->row();

                                                        $this->db->select('customerId, COUNT(customerId) AS EstimateInvoice, SUM(total) AS EstimateTotal, SUM(paid_amount) AS EstimatePaidAmount');
                                                        $this->db->where($where);
                                                        if ($this->input->get('salesman') != "") {
                                                            if (!in_array('All', input_get_array('salesman'))) {
                                                                $this->db->where_in('SalesmanId', input_get_array('salesman'));
                                                            }
                                                        }
                                                        $Estimates = $this->db->get('estimates')->row();

                                                        $n++;

                                                        $saleInvoice = (float) ($Sales->SaleInvoice ?? 0);
                                                        $estimateInvoice = (float) ($Estimates->EstimateInvoice ?? 0);
                                                        $saleTotal = (float) ($Sales->SaleTotal ?? 0);
                                                        $estimateTotal = (float) ($Estimates->EstimateTotal ?? 0);
                                                        $salePaid = (float) ($Sales->SalePaidAmount ?? 0);
                                                        $estimatePaid = (float) ($Estimates->EstimatePaidAmount ?? 0);

                                                        $TotalInvoice += ($saleInvoice + $estimateInvoice);
                                                        $Total += ($saleTotal + $estimateTotal);
                                                        $PaidAmount += ($salePaid + $estimatePaid);
                                                        $Balance += (($saleTotal + $estimateTotal) - ($salePaid + $estimatePaid));
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
                                                    if ($this->input->get('salesman') != "") {
                                                        if (!in_array('All', input_get_array('salesman'))) {
                                                            $this->db->where_in('SalesmanId', input_get_array('salesman'));
                                                        }
                                                    }
                                                    $this->db->order_by('invoiceDate', 'DESC');
                                                    $SalesTime = $this->db->get('sales')->row();
                                                    echo (!empty($SalesTime) && !empty($SalesTime->invoiceDate)) ? DifferenceFromDates($SalesTime->invoiceDate) : "0.0";
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $this->db->select('invoiceDate');
                                                    $this->db->where($where);
                                                    if ($this->input->get('salesman') != "") {
                                                        if (!in_array('All', input_get_array('salesman'))) {
                                                            $this->db->where_in('SalesmanId', input_get_array('salesman'));
                                                        }
                                                    }
                                                    $this->db->order_by('invoiceDate', 'DESC');
                                                    $EstimatesTime = $this->db->get('estimates')->row();
                                                    echo (!empty($EstimatesTime) && !empty($EstimatesTime->invoiceDate)) ? DifferenceFromDates($EstimatesTime->invoiceDate) : "0.0";
                                                ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($saleTotal + $estimateTotal, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($salePaid + $estimatePaid, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format(($saleTotal + $estimateTotal) - ($salePaid + $estimatePaid), 2); ?></td>
                                            <td><?= get_title('employees', ['Id' => $row->SalesmanId], 'Name'); ?></td>
                                        </tr>
                                        <?php } } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><?= $TotalInvoice; ?></th>
                                            <th colspan="2"></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Total, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($PaidAmount, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Balance, 2); ?></th>
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $('#salesman').multiselect({
        buttonWidth: 'auto'
    });
    
    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>Employee/PartiesSalesReport?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };
    
    $(function() {
        $(".table").DataTable({
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
                        columns: []
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'print',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'pageLength',
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'colvis',
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                }
            ]
        });
    });
</script>