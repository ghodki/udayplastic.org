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
                    <!-- HSN START -->
                    <div class="card mb-30">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <h4 class="card-title">Sale Report</h4>
                                </div>
                                <div class="col-md-6 text-center">
                                    <button type="button" class="btn btn-primary btn-xs" title="Previous Month" id="pre" onclick="return PreMonth();">
                                        <i class="fa fa-calendar-minus-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs" title="Current Month" id="currentMont" onclick="return curent();">
                                        <i class="fa fa-calendar-check-o text-white"></i>
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
                                <div class="col-md-4">
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>sale" class="btn btn-info btn-xs" title="Back to Sale Create">
                                            <i class="fa fa-arrow-circle-left"></i> Back
                                        </a>
                                        <a href="<?= base_url(); ?>sale_recycle" class="btn btn-primary btn-xs" title="Sale Cancelled Invoices">
                                            <i class="fa fa-recycle"></i> Cancelled
                                        </a>
                                        <a href="<?= base_url(); ?>Sale/SalesInovices" class="btn btn-danger btn-xs" title="All Invoices" target="_blank">
                                            <i class="fa fa-th-list"></i> All Invoices
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-1 col-sm-1">
                                        <label>Estimate</label>
                                        <br />
                                        <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                        Estimate
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label>Sale Register</label>
                                        <select name="sale_register" id="sale_register" onkeydown="firstRow(event);" required="" class="form-control searchselect form-control-sm">
                                            <option value="general" <?= $this->input->get('sale_register') == 'general' ? 'selected' : '' ?>>General Register</option>
                                            <option value="columnar" <?= $this->input->get('sale_register') == 'columnar' ? 'selected' : '' ?>>Columnar Register</option>
                                            <option value="hsn" <?= $this->input->get('sale_register') == 'hsn' ? 'selected' : '' ?>>HSN Wise Report</option>
                                            <option value="hsnB2B" <?= $this->input->get('sale_register') == 'hsnB2B' ? 'selected' : '' ?>>HSN Wise B2B Report</option>
                                            <option value="hsnB2C" <?= $this->input->get('sale_register') == 'hsnB2C' ? 'selected' : '' ?>>HSN Wise B2C Report</option>
                                            <option value="detailview" <?= $this->input->get('sale_register') == 'detailview' ? 'selected' : '' ?>>Detail View</option>
                                            <option value="updateview" <?= $this->input->get('sale_register') == 'updateview' ? 'selected' : '' ?>>Updated Report</option>
                                            <option value="description" <?= $this->input->get('sale_register') == 'description' ? 'selected' : '' ?>>Description Report</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="party">
                                            Parties
                                            <a href="javascript:void(0);" id="partyRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </label>
                                        <select name="party[]" id="party" class="form-control form-control-sm" title="Select Party Name" multiple="">
                                            <option <?= in_array('All', $this->input->get('party')) ? "selected" : ""; ?> value="All">All Party</option>
                                            <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                            <option <?= in_array($party->id, $this->input->get('party')) ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="Salesman">
                                            Salesman 
                                            <a href="javascript:void(0);" id="SalesmanRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </label>
                                        <select name="Salesman[]" id="Salesman" class="form-control form-control-sm" title="Select Salesman Name" multiple="">
                                            <option <?= in_array('All', $this->input->get('Salesman')) ? "selected" : ""; ?> value="All">All Salesman</option>
                                            <?php if (!empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                            <option <?= in_array($Salesman->Id, $this->input->get('Salesman')) ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>"><?= $Salesman->Name; ?></option>
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
                                    <div class="col-md-1 col-sm-1">
                                        <div class="form-group" style="margin-top: 31px;">
                                            <button type="submit" class="btn btn-primary btn-xs" onClick="return search();" onkeydown="firstRow(event);">
                                                <i class="fa fa-search text-white"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="hsn_report" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>HSN No</th>
                                            <th>Description</th>
                                            <th>Total Qty</th>
                                            <th>UQC</th>
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
                                            $total_amount = 0;
                                            $total_tax = 0;
                                            $total_taxAbleAmount = 0;
                                            $round_off = 0;
                                            
                                            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                                            $this->db->select('*');
                                            $this->db->select('invoice_product.hsn');
                                            $this->db->select('invoice_product.invoiceID');
                                            $this->db->select('invoice_product.productname');
                                            $this->db->select_sum('invoice_product.price');
                                            $this->db->select_sum('invoice_product.netAmount');
                                            $this->db->select_sum('invoice_product.qty');
                                            $this->db->select_sum('invoice_product.sgst');
                                            $this->db->select_sum('invoice_product.cgst');
                                            $this->db->select_sum('invoice_product.igst');
                                            $this->db->select_sum('invoice_product.taxAmount');
                                            $this->db->select_sum('invoice_product.purchase_price');
                                            // taxable amount
                                            $this->db->from('invoice_product');
                                            $this->db->join('sales', 'sales.id = invoice_product.invoiceID');
                                            $this->db->where('invoice_product.comp_id', $this->session->userdata('comp_id'));
                                            $this->db->where('invoice_product.user_id', $this->session->userdata('user_id'));
                                            $this->db->where('invoice_product.financial_year', $this->session->userdata('financial_year'));
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('invoice_product.CompanyBranchId', $this->session->userdata('CompBranchId')); 
                                            }
                                            $this->db->where('invoice_product.status', 1);
                                            $this->db->where('invoice_product.invoiceDate >=', $fromDate);
                                            $this->db->where('invoice_product.invoiceDate <=', $toDate);
                                            $this->db->where('invoice_product.type', 'Sale');
                                            $this->db->where('(sales.gstNo IS NULL OR sales.gstNo = "")');
                                            $this->db->group_by("invoice_product.hsn, invoice_product.tax");
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
                                        <tr>
                                            <td style="text-align:center;"><?= $key + 1 ?></td>
                                            <td style="text-align:center;"><?= $tax->hsn; ?></td>
                                            <td style="text-align:center;"><?= $tax->productname; ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->qty); ?></td>
                                            <td style="text-align:center;">
                                                <?php
                                                    $unit = $this->db->select('unit')->where('id', $tax->product_id)->get('product')->row(); 
                                                    $unitCode = $this->db->select('uqc_code')->where('id', $unit->unit)->get('unit')->row();
                                                    echo $unitCode->uqc_code
                                                ?>
                                            </td>                                            
                                            <td style="text-align:center;"><?= $tax->tax; ?> <i class="fa fa-percent"></i></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->price); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->igst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->cgst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->sgst); ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= 0.00; ?></td>
                                            <td style="text-align:center;"><i class="fa fa-inr"></i> <?= indianMoneyFormat($tax->netAmount); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>HSN No</th>
                                            <th>Description</th>
                                            <th>Total Qty</th>
                                            <th>UQC</th>
                                            <th>Tax</th>
                                            <th>Taxable Total</th>
                                            <th>CGST</th>
                                            <th>SGST</th>
                                            <th>IGST</th>
                                            <th>Cess Amount</th>
                                            <th>Total Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $from = $this->input->get('fromDate');
                                            $to = $this->input->get('toDate');
                                            $user_id = $this->session->userdata('user_id');
                                            $comp_id = $this->session->userdata('comp_id');
                                            $status = 1;
                                            $type = "Sale";
                                            
                                            // Sale
                                            $this->db->select('invoice_product.hsn AS hsn, invoice_product.productname, invoice_product.product_id, invoice_product.tax AS tax');
                                            $this->db->select_sum('invoice_product.price');
                                            $this->db->select_sum('invoice_product.qty');
                                            $this->db->select_sum('invoice_product.cgst');
                                            $this->db->select_sum('invoice_product.sgst');
                                            $this->db->select_sum('invoice_product.igst');
                                            $this->db->select_sum('invoice_product.netAmount');
                                            $this->db->from('invoice_product');
                                            $this->db->join('sales', 'sales.id = invoice_product.invoiceID');
                                            $this->db->where('invoice_product.user_id', $user_id);
                                            $this->db->where('invoice_product.comp_id', $comp_id);
                                            $this->db->where('invoice_product.financial_year', $this->session->userdata('financial_year'));
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('invoice_product.CompanyBranchId', $this->session->userdata('CompBranchId')); 
                                            }
                                            $this->db->where('invoice_product.status', $status);
                                            $this->db->where('invoice_product.invoiceDate >=', $from);
                                            $this->db->where('invoice_product.invoiceDate <=', $to);
                                            $this->db->where('invoice_product.type', $type);
                                            $this->db->where('(sales.gstNo IS NULL OR sales.gstNo = "")');
                                            $query1 = $this->db->get_compiled_select();
                                            
                                            // Estimate
                                            $this->db->select('estimate_product.hsn AS hsn, estimate_product.productname, estimate_product.product_id, estimate_product.tax AS tax');
                                            $this->db->select_sum('estimate_product.price');
                                            $this->db->select_sum('estimate_product.qty');
                                            $this->db->select_sum('estimate_product.cgst');
                                            $this->db->select_sum('estimate_product.sgst');
                                            $this->db->select_sum('estimate_product.igst');
                                            $this->db->select_sum('estimate_product.netAmount');
                                            $this->db->from('estimate_product');
                                            $this->db->join('estimates', 'estimates.id = estimate_product.invoiceID');
                                            $this->db->where('estimate_product.user_id', $user_id);
                                            $this->db->where('estimate_product.comp_id', $comp_id);
                                            $this->db->where('estimate_product.financial_year', $this->session->userdata('financial_year'));
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('estimate_product.CompanyBranchId', $this->session->userdata('CompBranchId')); 
                                            }
                                            $this->db->where('estimate_product.status', $status);
                                            $this->db->where('estimate_product.invoiceDate >=', $from);
                                            $this->db->where('estimate_product.invoiceDate <=', $to);
                                            $this->db->where('estimate_product.type', $type);
                                            $this->db->where('(estimates.gstNo IS NULL OR estimates.gstNo = "")');
                                            $query2 = $this->db->get_compiled_select();
                                            
                                            $allsaleproducts = $this->db->query($query1 . " UNION " . $query2 . " GROUP BY hsn, tax")->result();
                                            if (!empty($allsaleproducts)) {
                                                $n = 0;
                                                foreach ($allsaleproducts as $key => $data) {
                                                    $n++;
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $data->hsn; ?></td>
                                            <td><?= $data->productname; ?></td>
                                            <td><?= indianMoneyFormat($data->qty); ?></td>
                                            <td>
                                                <?php
                                                    $unit = $this->db->select('unit')->where('id', $tax->product_id)->get('product')->row(); 
                                                    $unitCode = $this->db->select('uqc_code')->where('id', $unit->unit)->get('unit')->row();
                                                    echo $unitCode->uqc_code
                                                ?>                                            
                                            <td><?= $data->tax; ?> <i class="fa fa-percent"></i></td>
                                            <td><?= !empty($data->price) ? "<i class='fa fa-inr'></i>&nbsp;" . indianMoneyFormat($data->price) : 0; ?></td>
                                            <td><?= $data->cgst != 0 ? "<i class='fa fa-inr'></i>&nbsp;" . indianMoneyFormat($data->cgst) : 0; ?></td>
                                            <td><?= $data->sgst != 0 ? "<i class='fa fa-inr'></i>&nbsp;" . indianMoneyFormat($data->sgst) : 0; ?></td>
                                            <td><?= $data->igst != 0 ? "<i class='fa fa-inr'></i>&nbsp;" . indianMoneyFormat($data->igst) : 0; ?></td>
                                            <td><?= 0.00; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data->netAmount); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-center">
                                Note : <span class="text-danger font-italic">** Deleted invoices stored in cancelled invoices **</span>
                            </p>
                        </div>
                    </div>
                    <!-- HSN END -->
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
    
    $('#Salesman').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $("#partyRefresh").click(function () {
        $("#party option:selected").each(function() {
            $(this).prop("selected", false);
        });
        $("#party").multiselect("refresh");
    });
    
    $("#SalesmanRefresh").click(function () {
        $("#Salesman option:selected").each(function() {
            $(this).prop("selected", false);
        });
        $("#Salesman").multiselect("refresh");
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
                    url     :   "<?= base_url('index.php/sale/salePrintMultipleInvoice'); ?>",
                    data    :   {id: selected},
                    dataType:   "JSON",
                    success :   function(data) {
                                    console.log(data);
                                    if (data.status == true) {
                                        toastr.success("", data.data);
                                        window.location.href = "<?= base_url(); ?>sale/" + data.url;
                                    } else {
                                        toastr.error("", data.data);
                                    }
                                }
                });
            }
        });
    });

    $(document).ready(function() {
        document.getElementById('hsn_report').style.display = "block";
    });

    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>sale/exportsale?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "sale_report.xls");
        $a[0].click();
        $a.remove();
    });

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
    
    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>sale/sale_report?" + form;
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
        window.location.href = "<?= base_url(); ?>sale/sale_report";
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
        window.location.href = "<?= base_url(); ?>sale/sale_report?" + form;
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
        window.location.href = "<?= base_url(); ?>sale/sale_report?" + form;
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
        window.location.href = "<?= base_url(); ?>sale/sale_report?" + form;
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
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_Sale_HSN_B2C_Wise_Report";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_Sale_HSN_B2C_Wise_Report";
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

    function sendmail(id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "Sale/sendmail/" + id);
        $("#common_modal_label").html("Send Mail");
        $("#common_modal").modal("show");
    }
</script>