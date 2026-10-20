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
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    <!-- Details VIEW START -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4 class="card-title">Estimate Purchase Report</h4>
                                </div>
                                <div class="col-md-6 text-center">
                                    <button type="button" class="btn btn-primary btn-xs" title="Previous Month" id="pre" onclick="return PreMonth();">
                                        <i class="fa fa-calendar-minus-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs" title="Current Month" id="currentMont" onclick="return curent();">
                                        <i class="fa fa-calendar-check-o"></i>
                                    </button>
                                    <button type="button" class="btn btn-dark btn-xs" title="Next Month" id="nextMonth" onclick="return nextmonth();">
                                        <i class="fa fa-calendar-plus-o text-white"></i>
                                    </button>                                    
                                    <button type="button" class="btn btn-default btn-xs" title="JSON File" id="json">
                                        <i class="fa fa-file-code"></i>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-xs" title="Excel File" id="excelExport" >
                                        <i class="fa fa-file-excel-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs" title="Print File" id="printmultiple">
                                        <i class="fa fa-print text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-xs" title="PDF File" id="downloadPDF">
                                        <i class="fa fa-file-pdf-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-xs" title="Refresh" id="resetbtn">
                                        <i class="fa fa-refresh text-white"></i>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>estimate_purchase" class="btn btn-info btn-xs" title="Back to Estimate Purchase Create">
                                            <i class="fa fa-arrow-circle-left"></i> Back
                                        </a>
                                        <a href="<?= base_url(); ?>Recycle/EstimatePurchase" class="btn btn-primary btn-xs" title="Estimate Purchase Cancelled invoices">
                                            <i class="fa fa-recycle"></i> Cancelled
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label>Sale Register</label>
                                        <select name="sale_register" id="sale_register" onkeydown="firstRow(event);" required="" class="form-control searchselect form-control-sm">
                                            <option value="general" <?= $this->input->get('sale_register') == 'general' ? 'selected' : '' ?>>General</option>
                                            <option value="columnar" <?= $this->input->get('sale_register') == 'columnar' ? 'selected' : '' ?>>Columnar</option>
                                            <option value="hsn" <?= $this->input->get('sale_register') == 'hsn' ? 'selected' : '' ?>>HSN Wise</option>
                                            <option value="detail" <?= $this->input->get('sale_register') == 'detail' ? 'selected' : '' ?>>Detail</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4">
                                        <label for="party">Parties</label>
                                        <select name="party[]" id="party" class="form-control form-control-sm" title="Select Party Name" multiple="">
                                            <?php $selectedParty = is_array($this->input->get('party')) ? $this->input->get('party') : []; ?>
                                            <option <?= in_array('All', $selectedParty, true) ? "selected" : ""; ?> value="All">All Party</option>
                                            <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                            <option <?= in_array($party->id, $selectedParty) ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="toDate">To Date</label>
                                        <input type="date" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-xs" onClick="return search();" onkeydown="firstRow(event);">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="details_view" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Party Name</th>
                                            <th>City</th>
                                            <th>GST No</th>
                                            <th>Round Off</th>
                                            <th>Taxable Amt</th>
                                            <?php
                                                $total_gsttype = [];
                                                $this->db->select('tax');
                                                $this->db->from('estimate_product');
                                                $this->db->where('type', 'Purchase');
                                                $this->db->where('status', 1);
                                                $this->db->group_by('tax');
                                                $this->db->order_by('tax', 'ASC');
                                                $tax = $this->db->get()->result();
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $row) {
                                                        if (is_numeric($row->tax)) {
                                                            $total_gsttype[$row->tax] = $row->tax;
                                            ?>
                                            <th style="text-align:center;"><span>Taxable Amt @ </span><?= $row->tax; ?> <i class="fa fa-percent"></i></th>
                                            <th style="text-align:center;">SGST</th>
                                            <th style="text-align:center;">CGST</th>
                                            <th style="text-align:center;">IGST</th>
                                            <?php } } } ?>
                                            <th>Invoice Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $totalTaxable_amount = 0;
                                            $total_tax = 0;
                                            $total_amount = 0;
                                            $round_off = 0;
                                            if (!isset($total_gsttype) || !is_array($total_gsttype)) {
                                                $total_gsttype = [];
                                            }
                                            if (!empty($result)) {
                                                foreach ($result as $key => $row) {
                                                    $totalTaxable_amount += (float) ($row->taxableAmount ?? 0);
                                                    $total_tax += (float) ($row->totalTax ?? 0);
                                                    $total_amount += (float) ($row->total ?? 0);
                                                    $round_off += (float) ($row->roundOff ?? 0);
                                        ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $row->invoiceNumber; ?></td>
                                            <td><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                            <td><?= get_title('districts', ['id' => get_title('parties', ['id' => $row->customerId], 'city')], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                            <td><?= indianMoneyFormat($row->roundOff ?? 0); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount ?? 0); ?></td>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->select_sum('price');
                                                $this->db->select_sum('sgst');
                                                $this->db->select_sum('cgst');
                                                $this->db->select_sum('igst');
                                                $this->db->select_sum('taxAmount');
                                                $this->db->from('estimate_product');
                                                $this->db->where('invoiceID', $row->id);
                                                $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                $this->db->where('type', 'Purchase');
                                                $this->db->where('status', 1);
                                                $this->db->group_by('tax');
                                                $tax = $this->db->get()->result();
                                                foreach ($total_gsttype as $total_tax) {
                                                    $this->db->select('tax');
                                                    $this->db->select_sum('price');
                                                    $this->db->select_sum('sgst');
                                                    $this->db->select_sum('cgst');
                                                    $this->db->select_sum('igst');
                                                    $this->db->select_sum('taxAmount');
                                                    $this->db->from('estimate_product');
                                                    $this->db->where('invoiceID', $row->id);
                                                    $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                    $this->db->where('tax', $total_tax);
                                                    $this->db->where('type', 'Purchase');
                                                    $this->db->where('status', 1);
                                                    $this->db->group_by('tax');
                                                    $this->db->order_by('tax', 'ASC');
                                                    $tax = $this->db->get()->result();
                                                    if (!empty($tax)) {
                                            ?>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax[0]->price); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax[0]->sgst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax[0]->cgst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax[0]->igst); ?></td>
                                            <?php } else { ?>
                                            <td style="text-align:center;">--</td>
                                            <td style="text-align:center;">--</td>
                                            <td style="text-align:center;">--</td>
                                            <td style="text-align:center;">--</td>
                                            <?php } } ?>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->totalValue); ?></td>
                                            <td>
                                                <a href="<?= base_url('estimate_purchase/view/') . $row->id; ?>" class="text-success" title="View">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate_purchase/recycle/') . $row->id; ?>" class="text-info" title="Edit">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                <?php if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate_purchase/recycle/') . $row->id; ?>" class="text-danger" onclick="return confirm('Are you sure to want to delete this invoice');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php } ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate_purchase/print/') . $row->id; ?>" class="text-success" title="Print">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate_purchase/download/') . $row->id; ?>" class="text-primary" title="Download">
                                                    <i class="fa fa-download" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;
                                                <a href="https://api.whatsapp.com/send?phone=+91<?= get_title('parties', ['id' => $row->customerId], 'contact'); ?>&text=<?= base_url(); ?>printInvoice/<?= $row->id; ?>" class="text-info" title="Send on WhatsApp" target="_blank">
                                                    <img src="<?= base_url(); ?>assets/images/whatsapp.png" alt="whatsapp" style="width: 20px;" />
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($round_off); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTaxable_amount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total_tax); ?></th>
                                            <th colspan="12" class="text-right"><i class="fa fa-inr"></i> <?= indianMoneyFormat($total_amount); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-center">
                                Note : <span class="text-danger font-italic">** Deleted invoices stored in cancelled invoices **</span>
                            </p>
                        </div>
                    </div>
                    <!-- Details VIEW END -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="common_modal" tabindex="-1" role="dialog" aria-labelledby="common_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="common_modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $('#party').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });

    $("#sale-selectall").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    $(function() {
        $("#printmultiple").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                if (!confirm('Are you sure to print all invoices?')) return false;
                console.log(selected, "ids");
                $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url('estimate_purchase/PrintMultiple'); ?>",
                    data    :   {id: selected},
                    dataType:   "JSON",
                    success :   function(data) {
                                    console.log(data);
                                    if (data.status == true) {
                                        toastr.success("", data.data);
                                        window.location.href = "<?= base_url(); ?>estimate_purchase/" + data.url;
                                    } else {
                                        toastr.error("", data.data);
                                    }
                                }
                });
            }
        });
    });

    $(document).ready(function() {
        document.getElementById('details_view').style.display = "block";
    });

    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>estimate_purchase/export_estimate_purchase?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "estimate_purchase_report.xls");
        $a[0].click();
        $a.remove();
    });

    $('#downloadPDF').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>estimate_purchase/pdf_estimate_purchase?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    });
    
    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        href = "<?= base_url(); ?>estimate_purchase/report?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };

    function getValueOfSale() {
        var value = document.getElementById('example').value;
        if (value == 'columnar') {
            document.getElementById('example').display = "none";
        } else if (value == 'hsn') {
            document.getElementById('example').display = "none";
        } else {
            document.getElementById('example').display = "show";
        }
    }

    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>estimate_purchase/report";
    });

    function PreMonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate');
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>estimate_purchase/report?" + form;
    }

    function curent() {
        var today = new Date();
        var dd = today.getDate();
        console.log(today);
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
        console.log(today);
        document.getElementById('fromDate').value = today;
        document.getElementById('toDate').value = monthlastdate;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>estimate_purchase/report?" + form;
    }

    function nextmonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate');
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>estimate_purchase/report?" + form;
    }

    var inputs = document.querySelectorAll("input,select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                nextInput[0].focus();
            }
        });
    }

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_EstimatePurchaseDetailsReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_EstimatePurchaseDetailsReport";
        <?php } ?>        
        
        $(".dataTable").DataTable({
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

    function deleteRow(url) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                removeRow(url);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }

    function removeRow(url) {
        $.ajax({
            type    :   "GET",
            url     :   url,
            success :   function(success) {
                            var successJson = JSON.parse(success);
                            if (successJson.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: successJson.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: successJson.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    }

    $('#sale_register').focus();

    function firstRow(event) {
        var start = document.getElementById('trp0');
        start.focus();
        start.style.backgroundColor = 'blue';
        start.style.color = 'white';
    }

    function sendmail(id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "estimate_purchase/sendmail/" + id);
        $("#common_modal_label").html("Send Mail");
        $("#common_modal").modal("show");
    }
</script>