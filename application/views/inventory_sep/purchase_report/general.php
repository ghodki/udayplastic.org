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
                                            <input type="text" tabindex="2" name="invoiceNumber" id="invoiceNumber" value="<?= $this->input->get('invoiceNumber') ? $this->input->get('invoiceNumber') : '' ?>" class="form-control form-control-sm" placeholder="Invoice Number" />
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
                            <!-- General Start -->
                            <div id="divas" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <thead>
                                        <tr role="row">
                                            <th rowspan="1" colspan="1" aria-label="">
                                                <input type="checkbox" id="sale-selectall">
                                                <label for="sale-selectall"></label>
                                            </th>
                                            <th>Sr.</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Payment Mode</th>
                                            <th style="width:73.3438px;">Taxable Amount</th>
                                            <th style="width:73.3438px;">Tax</th>
                                            <th style="width:73.3438px;">Total</th>
                                            <th>Round Off</th>
                                            <th>Payment</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Freight Charge</th>
                                            <th>Cash Discount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <form class="form_select" method="POST" id="form_select">
                                            <?php
                                                $totalTaxable_amount = 0;
                                                $total_tax = 0;
                                                $total_amount = 0;
                                                $round_off = 0;
                                                $PaidAmount = 0;
                                                $BalanceAmount = 0;
                                                if ($result) {
                                                    foreach ($result as $key => $row) {
                                                        if ($row->status == '1') {
                                                            $TextDecoration = 'unset';
                                                            $totalTaxable_amount += $row->taxableAmount;
                                                            $total_tax += $row->totalTax;
                                                            $total_amount += $row->total;
                                                            $round_off += $row->roundOff;
                                                            $PaidAmount += $row->paid_amount; 
                                                            $BalanceAmount += ($row->total - $row->paid_amount);
                                                        } else {
                                                            $TextDecoration = 'line-through';
                                                        }
                                            ?>
                                            <tr>
                                                <td class="cheking text-center">
                                                    <input class="checkdata" type="checkbox" name="product_id[]" value="<?= $row->id; ?>" />
                                                </td>
                                                <td><?= $key + 1; ?></td>
                                                <td><?= $row->invoiceNumber; ?></td>
                                                <td><?= date("d/m/Y", strtotime($row->invoiceDate)); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                                <td>
                                                    <?php
                                                        if ($row->voucherType == 1) {
                                                            $payment_mode = "Cash";
                                                        } elseif ($row->voucherType == 2) {
                                                            $payment_mode = "Credit";
                                                        } else {
                                                            $payment_mode = $row->voucherType;
                                                        }
                                                        echo $payment_mode; 
                                                    ?>
                                                </td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->totalTax); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->total); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->roundOff); ?></td>
                                                <td>
                                                    <?php if ($row->status == '1') { if ($row->paid_amount == $row->total) { ?>
                                                    <label class="btn btn-success btn-xs"><i class="fa fa-check-circle text-white"></i> Paid</label>
                                                    <?php } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) { ?>
                                                    <a href="<?= base_url(); ?>Payment/index/<?= $row->id; ?>" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-exclamation-triangle text-white"></i> Unpaid
                                                    </a>
                                                    <?php } else { ?>
                                                    <a href="<?= base_url(); ?>Payment/index/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </a>
                                                    <?php } } else { ?>
                                                    <label class="btn btn-danger btn-xs">
                                                        <i class="fa fa-ban text-white"></i> Cancelled
                                                    </label>
                                                    <?php } ?>
                                                </td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->paid_amount); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat(($row->total - $row->paid_amount)); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->freight_charge); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->cash_discount); ?></td>
                                                <td>
                                                    <a href="<?= base_url('purchase/viewpurchase/') . $row->id ?>" class="text-success" title="View" target="_blank">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('purchase/purchaseEdit/') . $row->id; ?>" class="text-info" title="Edit" target="_blank">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    <?php if ($row->status == '1') { if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                    &nbsp;
                                                    <a href="<?= base_url('purchase/purchase_delete/') . $row->id; ?>" class="text-danger" onclick="return confirm('Are you sure to want to delete this invoice');" title="Cancelled">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <?php } } ?>
                                                    &nbsp;
                                                    <a href="<?= base_url('purchase/printpurchase/') . $row->id; ?>" class="text-success" title="Print" target="_blank">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('purchase/printpurchases/') . $row->id; ?>" class="text-primary" title="Download" target="_blank">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </form>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalTaxable_amount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total_tax); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total_amount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($round_off); ?></th>
                                            <th></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($PaidAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($BalanceAmount); ?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- General End -->
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
        var href = "<?= base_url(); ?>purchase/purchase_report?" + form;
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

    var inputs = document.querySelectorAll("input, select");
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
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_PurchaseGeneralReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_PurchaseGeneralReport";
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
</script>