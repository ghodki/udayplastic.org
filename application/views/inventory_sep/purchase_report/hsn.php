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
                    <div class="card mb-30">
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
                                        <a href="<?= base_url(); ?>purchase_recycle" class="btn btn-primary btn-sm" title="Purchase Cancelled invoices">
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
                            <!-- HSN_Report Start -->
                            <div id="hsn_report" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>HSN No</th>
                                            <th>Description</th>
                                            <th>Total Qty</th>
                                            <th>TAX</th>
                                            <th>Taxable Amount</th>
                                            <th style="text-align:center;">IGST</th>
                                            <th style="text-align:center;">CGST</th>
                                            <th style="text-align:center;">SGST</th>
                                            <th>Cess Amount</th>
                                            <th>Total Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($this->input->get('fromDate') != '') {
                                                $from_date = $this->input->get('fromDate');
                                            } else {
                                                $from_date = '';
                                            }
                                            if ($this->input->get('toDate') != '') {
                                                $toDate = $this->input->get('toDate');
                                            } else {
                                                $toDate = '';
                                            }
                                            $total_amount = 0;
                                            $total_tax = 0;
                                            $total_taxAbleAmount = 0;
                                            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                                            $this->db->select('*');
                                            $this->db->select('hsn');
                                            $this->db->select('invoiceID');
                                            $this->db->select('productname');
                                            $this->db->select_sum('price');
                                            $this->db->select_sum('netAmount');
                                            $this->db->select_sum('qty');
                                            $this->db->select_sum('sgst');
                                            $this->db->select_sum('cgst');
                                            $this->db->select_sum('igst');
                                            $this->db->select_sum('taxAmount');
                                            $this->db->select_sum('purchase_price');
                                            $this->db->from('invoice_product');
                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId')); 
                                            }
                                            $this->db->where('status', 1);
                                            $this->db->where('invoiceDate >=', $from_date);
                                            $this->db->where('invoiceDate <=', $toDate);
                                            $this->db->where('type', 'Purchase');
                                            $this->db->group_by("hsn", "tax");
                                            $result_of_products = $this->db->get()->result();
                                            if (!empty($result_of_products)) {
                                                foreach ($result_of_products as $key => $tax) {
                                                    $total_taxAbleAmount += $tax->purchase_price;
                                                    $total_tax += $tax->taxAmount;
                                                    $total_amount += $tax->netAmount;

                                                    $overall_igst = 0;
                                                    $overall_cgst = 0;
                                                    $overall_sgst = 0;

                                                    $overall_sgst = $overall_sgst + $tax->sgst;
                                                    $overall_cgst = $overall_cgst + $tax->cgst;
                                                    $overall_igst = $overall_igst + $tax->igst;
                                        ?>
                                        <tr id="trp<?= $key; ?>">
                                            <td><?= $key + 1; ?></td>
                                            <td style="text-align:center;"><?= $tax->hsn; ?></td>
                                            <td style="text-align:center;"><?= $tax->productname; ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->qty); ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->tax); ?> <i class="fa fa-percent"></i></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->taxAmount); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->igst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->cgst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->sgst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= 0.00; ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->netAmount); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
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
    // //(inputs);
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
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_PurchaseHSNWiseReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_PurchaseHSNWiseReport";
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
                            var successjosn = JSON.parse(success);
                            if (successjosn.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: successjosn.msg,
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
                                    title: successjosn.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
			}
        });
    }
</script>