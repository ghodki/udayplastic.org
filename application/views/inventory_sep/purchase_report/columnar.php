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
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Purchase Report</h4>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" title="Previous Month" id="pre" onclick="return PreMonth();" class="btn btn-primary btn-sm">
                                        Previous Month
                                    </button>
                                    <button type="button" title="Current Month" id="currentMont" onclick="return curent();" class="btn btn-warning btn-sm">
                                        Current Month
                                    </button>
                                    <button type="button" title="Next Month" id="nextMonth" onclick="return nextmonth();" class="btn btn-danger btn-sm">
                                        Next Month
                                    </button>
                                </div>    
                                <div class="col-md-4">
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>purchase" class="btn btn-info btn-sm" title="Back to Purchase Create">
                                            <i class="fa fa-arrow-left"></i> Back
                                        </a>
                                        <a href="<?= base_url(); ?>purchase_recycle" class="btn btn-primary btn-sm" title="Purchase Cancelled Invoices">
                                            <i class="fa fa-recycle"></i> Cancelled
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                    <div class="row">
                                        <div class="form-group col-md-3 col-sm-6">
                                            <label>Purchase Register</label>
                                            <select tabindex="1" name="sale_register" id="sale_register" onkeydown="firstRow(event);" required="" class="form-control searchselect form-control-sm">
                                                <option value="general" <?= $this->input->get('sale_register') == 'general' ? 'selected' : '' ?>>General Register</option>
                                                <option value="columnar" <?= $this->input->get('sale_register') == 'columnar' ? 'selected' : '' ?>>Columnar Register</option>
                                                <option value="hsn" <?= $this->input->get('sale_register') == 'hsn' ? 'selected' : '' ?>>HSN Wise Report</option>
                                                <option value="detailview" <?= $this->input->get('sale_register') == 'detailview' ? 'selected' : '' ?>>Detail View</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label for="invoiceNumber">Invoice Number</label>
                                            <input type="text" tabindex="2" name="invoiceNumber" id="invoiceNumber" value="<?= $this->input->get('invoiceNumber') ? $this->input->get('invoiceNumber') : ''; ?>" class="form-control form-control-sm" placeholder="Invoice Number" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label for="fromDate">From Date</label>
                                            <input type="date" tabindex="3" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label for="toDate">To Date</label>
                                            <input type="date" tabindex="4" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6" style="margin-top: 31px;">
                                            <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                                Search
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" title="Refresh" id="resetbtn">
                                                <i class="fa fa-refresh text-white"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" title="JSON File" id="json">
                                                <i class="fa fa-file-code text-white"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm" title="Excel File" id="excelExport" >
                                                <i class="fa fa-file-excel-o text-white"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" title="PDF File" id="downloadPDF">
                                                <i class="fa fa-file-pdf-o text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- columnar  start -->
                            <div id="Diff" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" width="100%" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Customer</th>
                                            <th>GSTIN</th>
                                            <th>POS</th>
                                            <th>Payment Mode</th>
                                            <th>Round Off</th>
                                            <th>Taxable Amount</th>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->from('invoice_product');
                                                $this->db->where('type', 'Purchase');
                                                $this->db->where('tax!=', null);
                                                $this->db->group_by('tax');
                                                $this->db->order_by('tax', 'asc');
                                                $tax = $this->db->get()->result();
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $row) {
                                                        if (is_numeric($row->tax) && !empty($row->tax)) {
                                                            $total_gsttype[$row->tax] = $row->tax;
                                            ?>
                                            <th style="text-align:center;"><span>Taxable Amt @ </span><?= $row->tax; ?> <i class="fa fa-percent"></i></th>
                                            <?php } } } ?>
                                            <th style="text-align:center;">SGST</th>
                                            <th style="text-align:center;">CGST</th>
                                            <th style="text-align:center;">IGST</th>
                                            <th style="text-align:center;">CESS</th>
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
                                            if ($result) {
                                                foreach ($result as $key => $row) {
                                                    $total_tax += $row->totalTax;
                                                    $total_amount += $row->total;
                                                    $totalTaxable_amount += $row->taxableAmount;
                                                    $round_off += $row->roundOff;

                                                    $overall_igst = 0;
                                                    $overall_cgst = 0;
                                                    $overall_sgst = 0;
                                                    
                                                    $total_igst = 0;
                                                    $total_cgst = 0;
                                                    $total_sgst = 0;
                                        ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $row->invoiceNumber; ?></td>
                                            <td><?= $row->invoiceDate; ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                            <td><?= get_title('state_list', ['id' => get_title('parties', ['id' => $row->customerId], 'state')], 'state'); ?></td>
                                            <?php
                                            if ($row->voucherType == 1) {
                                                $payment_mode = "Cash";
                                            } elseif ($row->voucherType == 2) {
                                                $payment_mode = "Credit";
                                            } else {
                                                $payment_mode = $row->voucherType;
                                            }
                                            ?>
                                            <td><?= $payment_mode; ?></td>
                                            <td><?= indianMoneyFormat($round_off); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount); ?></td>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->select_sum('price');
                                                $this->db->select_sum('sgst');
                                                $this->db->select_sum('cgst');
                                                $this->db->select_sum('igst');
                                                $this->db->select_sum('taxAmount');
                                                $this->db->from('invoice_product');
                                                $this->db->where('invoiceID', $row->id);
                                                $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                $this->db->where('type', 'Purchase');
                                                $this->db->group_by('tax');
                                                $tax = $this->db->get()->result();
                                                foreach ($total_gsttype as $total_tax) {
                                                    $this->db->select('tax');
                                                    $this->db->select_sum('price');
                                                    $this->db->select_sum('sgst');
                                                    $this->db->select_sum('cgst');
                                                    $this->db->select_sum('igst');
                                                    $this->db->select_sum('taxAmount');
                                                    $this->db->from('invoice_product');
                                                    $this->db->where('invoiceID', $row->id);
                                                    $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                    $this->db->where('tax', $total_tax);
                                                    $this->db->where('type', 'Purchase');
                                                    $this->db->group_by('tax');
                                                    $this->db->order_by('tax', 'ASC');
                                                    $tax = $this->db->get()->result();
                                                    if (!empty($tax)) {
                                                        $overall_sgst = $overall_sgst + $tax[0]->sgst;
                                                        $overall_cgst = $overall_cgst + $tax[0]->cgst;
                                                        $overall_igst = $overall_igst + $tax[0]->igst;
                                            ?>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax[0]->price); ?></td>
                                            <?php } else { ?>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> 0.00</td>
                                            <?php } } ?>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($overall_sgst); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($overall_cgst); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($overall_igst); ?></td>
                                            <?php
                                                $total_igst = $total_igst + $overall_igst;
                                                $total_cgst = $total_cgst + $overall_cgst;
                                                $total_sgst = $total_sgst + $overall_sgst;
                                            ?>
                                            <td><i class="fa fa-inr"></i> <?= 0; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->totalValue); ?></td>
                                            <td>
                                                <a href="<?= base_url('purchase/viewpurchase/') . $row->id; ?>" class="text-success" title="View">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                &nbsp; 
                                                <a href="<?= base_url('purchase/purchaseEdit/') . $row->id; ?>" class="text-info" title="Edit">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;
                                                <?php if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                <a href="<?= base_url('purchase/purchase_delete/') . $row->id; ?>" class="text-danger" onclick="return confirm('Are you sure to want to delete this invoice');" title="Cancelled">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php } ?>
                                                &nbsp; 
                                                <a href="<?= base_url('purchase/printpurchase/') . $row->id; ?>" class="text-success" title="Print" target="_blank">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('purchase/printpurchases/') . $row->id; ?>" class="text-primary" title="Download">
                                                    <i class="fa fa-download" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7">Total</th>
                                            <th><i class="fa fa-inr total"></i> <?= indianMoneyFormat($round_off); ?></th>
                                            <th><i class="fa fa-inr total"></i> <?= indianMoneyFormat($totalTaxable_amount); ?></th>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->from('invoice_product');
                                                $this->db->where('type', 'Purchase');
                                                $this->db->group_by('tax');
                                                $this->db->order_by('tax', 'ASC');
                                                $tax = $this->db->get()->result();
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $row) {
                                                        if (is_numeric($row->tax)) {
                                                            $total_gsttype[$row->tax] = $row->tax;
                                            ?>
                                            <th style="text-align:center;"><span></span></th>
                                            <?php } } } ?>
                                            <th colspan="4"></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total_amount); ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- columnar End -->
                        </div>
                        <div class="card-footer">
                            <p class="text-center">
                                Note : <span class="text-danger font-italic">** Deleted invoices stored in cancelled invoices **</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>purchase/exportPurchase?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "purchase_report.xls");
        $a[0].click();
        $a.remove();
    });
    
    $('#downloadPDF').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>purchase/pdfPurchase?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    });

    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        href = "<?= base_url(); ?>purchase/purchase_report?" + form;
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
    $('#downloadPDF').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>sale/pdfsale?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    });

    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>purchase/purchase_report";
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
        var page = document.getElementById('sale_register').value;
        var invoice = document.getElementById('invoiceNumber').value;
        
        window.location.href = "<?= base_url(); ?>purchase/purchase_report?sale_register=" + page + "&invoiceNumber=" + invoice + "&fromDate=" + firstDay + "&toDate=" + lastDay;
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
        var page = document.getElementById('sale_register').value;
        var invoice = document.getElementById('invoiceNumber').value;
        
        window.location.href = "<?= base_url(); ?>purchase/purchase_report?sale_register=" + page + "&invoiceNumber=" + invoice + "&fromDate=" + today + "&toDate=" + monthlastdate;
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
        var page = document.getElementById('sale_register').value;
        var invoice = document.getElementById('invoiceNumber').value;
        
        window.location.href = "<?= base_url(); ?>purchase/purchase_report?sale_register=" + page + "&invoiceNumber=" + invoice + "&fromDate=" + firstDay + "&toDate=" + lastDay;
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
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_PurchaseColumnarReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_PurchaseColumnarReport";
        <?php } ?>
        
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
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
                            var successjson = JSON.parse(success);
                            if (successjson.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: successjson.msg,
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
                                    title: successjson.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    }
</script>