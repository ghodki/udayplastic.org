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
                    <!-- updated view start -->
                    <div class="card">
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
                            <div id="updatedview" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <?php if ($this->input->get('estimate') == "") { ?>
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
                                            <th>Taxable Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Created</th>
                                            <th>Created By</th>
                                            <th>Updated</th>
                                            <th>Updated By</th>
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
                                                if ($result) {
                                                    foreach ($result as $key => $row) {
                                                        $totalTaxable_amount += $row->taxableAmount;
                                                        $total_tax += $row->totalTax;
                                                        $total_amount += $row->total;
                                                        $round_off = $round_off + $row->roundOff;

                                            ?>
                                            <tr>
                                                <td class="cheking text-center">
                                                    <input class="checkdata" type="checkbox" name="product_id[]" value="<?php echo $row->id; ?>" />
                                                </td>
                                                <td><?= $key + 1; ?></td>
                                                <td><?= $row->invoiceNumber; ?></td>
                                                <td><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                                <td>
                                                    <?php
                                                        if ($row->voucherType == 1) {
                                                            $payment_mode = "Cash";
                                                        } else if ($row->voucherType == 2) {
                                                            $payment_mode = "Credit";
                                                        } else {
                                                            $payment_mode = $row->voucherType;
                                                        }
                                                        echo $payment_mode;
                                                    ?>
                                                </td> 
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->totalTax); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->total); ?></td>
                                                <td><?= $row->created; ?></td>
                                                <td><?= $row->created_by; ?></td>
                                                <td><?= $row->updated; ?></td>
                                                <td><?= $row->updated_by; ?></td>
                                                <td>
                                                    <a href="<?= base_url('sale/viewsale/') . $row->id; ?>" class="text-success" target="_blank" title="View">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('sale/saleEdit/') . $row->id; ?>" class="text-info" target="_blank" title="Edit">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    <?php if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                    &nbsp;
                                                    <a href="<?= base_url('sale/sale_deleted/') . $row->id; ?>" class="text-danger" onclick="return confirm('Are you sure to want to delete this invoice');" title="Cancelled">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <?php } ?>
                                                    &nbsp;
                                                    <a href="<?= base_url('sale/printSale/') . $row->id; ?>" class="text-success" target="_blank" title="Print">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('sale/printSales/') . $row->id; ?>" class="text-primary" target="_blank" title="Download">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="#" class="text-dark" title="Send Mail" onclick="sendmail(<?= $row->id; ?>);">
                                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="https://api.whatsapp.com/send?phone=+91<?= get_title('parties', ['id' => $row->customerId], 'contact'); ?>&text=<?= base_url(); ?>printInvoice/<?= $row->id; ?>" class="text-info" title="Send on WhatsApp" target="_blank">
                                                        <img src="<?= base_url(); ?>assets/images/whatsapp.png" alt="whatsapp" style="width: 20px;" />
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
                                            <th colspan="5"></th>
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Type</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Payment Mode</th> 
                                            <th>Taxable Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Created</th>
                                            <th>Created By</th>
                                            <th>Updated</th>
                                            <th>Updated By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $TotalTaxableAmount = 0;
                                            $TotalTax = 0;        
                                            $TotalAmount = 0;
                                            
                                            $party = $this->input->get('party');
                                            $Salesman = $this->input->get('Salesman');
                                            $from = $this->input->get('fromDate');
                                            $to = $this->input->get('toDate');
                                            $user_id = $this->session->userdata('user_id');
                                            $comp_id = $this->session->userdata('comp_id');
                                            $status = 1;
                                            $financial_year = $this->session->userdata('financial_year');
                                            
                                            // Sale
                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, user_id, taxableAmount, totalTax, total, type, added_at, created, created_by, updated, updated_by');
                                            $this->db->from('sales');
                                            if ($party != "") {
                                                if (!in_array('All', $party)) {
                                                    $this->db->where_in('customerId', $party);
                                                }
                                            }
                                            if ($Salesman != "") {
                                                if (!in_array('All', $Salesman)) {
                                                    $this->db->where_in('SalesmanId', $Salesman);
                                                }
                                            }
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')){
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId')); 
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            $this->db->where('updated !=', '');
                                            $this->db->where('updated_by !=', '');
                                            $query1 = $this->db->get_compiled_select();
                                            
                                            // Estimate
                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, user_id, taxableAmount, totalTax, total, type, added_at, created, created_by, updated, updated_by');
                                            $this->db->from('estimates');
                                            if ($party != "") {
                                                if (!in_array('All', $party)) {
                                                    $this->db->where_in('customerId', $party);
                                                }
                                            }
                                            if ($Salesman != "") {
                                                if (!in_array('All', $Salesman)) {
                                                    $this->db->where_in('SalesmanId', $Salesman);
                                                }
                                            }
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')){
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId')); 
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            $this->db->where('updated !=', '');
                                            $this->db->where('updated_by !=', '');
                                            $query2 = $this->db->get_compiled_select();
                                            
                                            $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                                            if (!empty($allsales)) {
                                                $n = 0;
                                                foreach ($allsales as $key => $data) {
                                                    $n++;
                                                    $TotalTaxableAmount += $data->taxableAmount;
                                                    $TotalTax += $data->totalTax;
                                                    $TotalAmount += $data->total;
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $data->type; ?></td>
                                            <td><?= $data->invoiceNumber; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'contact'); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'gst'); ?></td>
                                            <td>
                                                <?php
                                                    if ($data->voucherType == 1) {
                                                        $payment_mode = "Cash";
                                                    } elseif ($data->voucherType == 2) {
                                                        $payment_mode = "Credit";
                                                    } else {
                                                        $payment_mode = $data->voucherType;
                                                    }
                                                    echo $payment_mode;
                                                ?>
                                            </td>
                                            <td>
                                                <?= !empty($data->taxableAmount) ? "<i class='fa fa-inr'></i>&nbsp;" . indianMoneyFormat($data->taxableAmount) : ""; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data->totalTax) ? "<i class='fa fa-inr'></i>&nbsp;" . indianMoneyFormat($data->totalTax) : ""; ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data->total); ?></td>
                                            <td>
                                                <?php 
                                                    if (!empty($data->created)) { 
                                                        echo $data->created; 
                                                    } else { 
                                                        echo date('d-m-Y h:i:s A', strtotime($data->added_at)); 
                                                    } 
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if (!empty($data->created_by)) {
                                                        echo $data->created_by;
                                                    } else {
                                                        echo get_title('users', ['id' => $data->user_id], 'name');
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $data->updated; ?></td>
                                            <td><?= $data->updated_by; ?></td>
                                            <td>
                                                <?php if ($data->type == "Sale") { ?>
                                                <a href="<?= base_url('sale/viewsale/') . $data->id; ?>" class="text-primary" target="_blank" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                 &nbsp;
                                                <a href="<?= base_url('sale/saleEdit/') . $data->id; ?>" class="text-info" target="_blank" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('sale/printSale/') . $data->id; ?>" class="text-success" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('sale/printSales/') . $data->id; ?>" class="text-danger" target="_blank" title="Download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <?php } elseif ($data->type == "Estimate") { ?>
                                                <a href="<?= base_url('estimate/viewestimate/') . $data->id; ?>" class="text-primary" target="_blank" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/estimateEdit/') . $data->id; ?>" class="text-info" target="_blank" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimate/') . $data->id; ?>" class="text-success" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimates/') . $data->id; ?>" class="text-danger" target="_blank" title="Download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTaxableAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTax); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount); ?></th>
                                            <th colspan="6"></th>
                                        </tr>
                                    </tfoot>
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
                    <!-- Update View End -->
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
        document.getElementById('updatedview').style.display = "block";
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
        href = "<?= base_url(); ?>sale/sale_report?" + form;
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
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_SaleUpdatedViewReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_SaleUpdatedViewReport";
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
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "index.php/sale/sendmail/" + id);
        $("#common_modal_label").html("Send Mail");
        $("#common_modal").modal("show");
    }
</script>