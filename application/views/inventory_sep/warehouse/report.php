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
                    <!-- Index Start -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Stock Transfer Report</h4>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" title="Previous Month" id="pre" onclick="return PreMonth();" class="btn btn-outline-primary btn-xs">
                                        Previous Month
                                    </button>
                                    <button type="button" title="Current Month" id="currentMont" onclick="return curent();" class="btn btn-outline-warning btn-xs">
                                        Current Month
                                    </button>
                                    <button type="button" title="Next Month" id="nextMonth" onclick="return nextmonth();" class="btn btn-outline-danger btn-xs">
                                        Next Month
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>warehouse/stock_transfer" class="btn btn-outline-info btn-xs" title="Back to stock transfer">
                                            <i class="fa fa-angle-double-left"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-2 col-sm-6">
                                        <label for="transfer_id">Transfer Id</label>
                                        <input type="text" name="transfer_id" id="transfer_id" value="<?= $this->input->get('transfer_id') ? $this->input->get('transfer_id') : '' ?>" class="form-control form-control-sm" placeholder="Transfer Id" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-6">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" tabindex="3" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-6">
                                        <label for="toDate">To Date</label>
                                        <input type="date" tabindex="4" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-1 col-sm-6" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                            Search
                                        </button>
                                    </div>
                                    <div class="form-group col-md-5 col-sm-6" style="margin-top: 31px;">
                                        <button type="button" class="btn btn-info btn-sm" title="Refresh" id="resetbtn">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" title="Excel File" id="excelExport" >
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Print File" id="printmultiple">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" title="PDF File" id="downloadPDF">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="divas" class="table-responsive">
                                <table class="table table-striped table-bordered DataTable">
                                    <thead>
                                        <tr role="row">
                                            <th><input type="checkbox" id="sale-selectall" /></th>
                                            <th>Sr.</th>
                                            <th>Transfer Id</th>
                                            <th>Date</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Total</th>
                                            <th>Round Off</th>
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
                                                $TotalAmount = 0;
                                                $RoundOff = 0;
                                                $n = 0;
                                                if ($result) {
                                                    foreach ($result as $key => $row) {
                                                        $TotalAmount += (float) ($row->total_amount ?? 0);
                                                        $RoundOff += (float) ($row->roundoff ?? 0);
                                                        $n++;
                                            ?>
                                            <tr>
                                                <td class="cheking text-center">
                                                    <input class="checkdata" type="checkbox" name="row_id[]" value="<?= $row->id; ?>" />
                                                </td>
                                                <td><?= $n; ?></td>
                                                <td><?= $row->transfer_id; ?></td>
                                                <td><?= date("d-m-Y", strtotime($row->transfer_date)); ?></td>
                                                <td><?= get_title('warehouse', ['id' => $row->warehouse_form], 'name'); ?> - <?= get_title('warehouse', ['id' => $row->warehouse_form], 'location'); ?></td>
                                                <td><?= get_title('warehouse', ['id' => $row->warehouse_to], 'name'); ?> - <?= get_title('warehouse', ['id' => $row->warehouse_to], 'location'); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($row->total_amount ?? 0)); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($row->roundoff ?? 0)); ?></td>
                                                <td><?= $row->created; ?></td>
                                                <td><?= get_title('users', ['id' => $row->created_by], 'name'); ?></td>
                                                <td><?= $row->updated; ?></td>
                                                <td><?= get_title('users', ['id' => $row->updated_by], 'name'); ?></td>
                                                <td>
                                                    <a href="<?= base_url('warehouse/info/') . $row->id; ?>" class="text-success" target="_blank" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('warehouse/edit_stock_transfer/') . $row->id; ?>" class="text-info" target="_blank" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('warehouse/delete_stock_transfer/') . $row->id; ?>" class="text-danger" onclick="return confirm('Are you sure want to delete this record.');" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('warehouse/print/') . $row->id; ?>" class="text-primary" target="_blank" title="Print">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('warehouse/download/') . $row->id; ?>" class="text-primary" title="Download">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </form>
                                    </tbody>
                                    <tfoot>
                                        <th colspan="6">Total</th>
                                        <th>
                                            <i class="fa fa-inr"></i> <?= number_format($TotalAmount ?? 0); ?>
                                        </th>
                                        <th>
                                            <i class="fa fa-inr"></i> <?= number_format($RoundOff ?? 0); ?>
                                        </th>
                                        <th colspan="5"></th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Index End -->
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript">
    $("#resetbtn").click(function() {
        window.location.href = "<?= base_url(); ?>warehouse/report";
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
        var transfer_id = document.getElementById('transfer_id').value;
        
        window.location.href = "<?= base_url(); ?>warehouse/report?transfer_id=" + transfer_id + "&fromDate=" + firstDay + "&toDate=" + lastDay;
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
        var transfer_id = document.getElementById('transfer_id').value;
        
        window.location.href = "<?= base_url(); ?>warehouse/report?transfer_id=" + transfer_id + "&fromDate=" + today + "&toDate=" + monthlastdate;
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
        var transfer_id = document.getElementById('transfer_id').value;
        
        window.location.href = "<?= base_url(); ?>warehouse/report?transfer_id=" + transfer_id + "&fromDate=" + firstDay + "&toDate=" + lastDay;
    }
    
    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>warehouse/exportexcel?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "stock_transfer_report.xls");
        $a[0].click();
        $a.remove();
    });
</script>