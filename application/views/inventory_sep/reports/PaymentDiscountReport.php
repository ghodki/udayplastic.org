<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .card-body {
        padding: 0.25rem 0.25rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-dark">Payment Discount Report</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" id="ReportForm">
                                <div class="row">
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="From">From</label>
                                        <input type="date" name="From" value="<?= $this->input->get('From') ? $this->input->get('From') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="To">To</label>
                                        <input type="date" name="To" value="<?= $this->input->get('To') ? $this->input->get('To') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>"  id="To" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-xs bg-purple">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/PaymentDiscountReport" class="btn btn-danger btn-xs" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-xs" title="Previous Month" onclick="return PreviousMonth();">
                                            <i class="fa fa-calendar-minus-o"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-warning btn-xs" title="Current Month" onclick="return CurrentMonth();">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-dark btn-xs" title="Next Month" onclick="return NextMonth();">
                                            <i class="fa fa-calendar-plus-o"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="DataTable">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>Mode</th>
                                            <th>Received</th>
                                            <th>Paid</th>
                                            <th>Discount</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $TotalReceivedAmount = 0;
                                            $TotalPaidAmount = 0;
                                            $TotalDiscountAmount = 0;
                                            if (!empty($PaymentData)) {
                                                foreach ($PaymentData as $key => $Data) {
                                                    $Sr++;
                                                    $rowReceived = (float) ($Data->receivedPaidAmount ?? 0);
                                                    $rowPaid = (float) ($Data->debit ?? 0);
                                                    $rowDiscount = (float) ($Data->cashDiscountAmount ?? 0);
                                                    $TotalReceivedAmount += $rowReceived;
                                                    $TotalPaidAmount += $rowPaid;
                                                    $TotalDiscountAmount += $rowDiscount;
                                                    $invoiceTypes = !empty($Data->invoice_type) ? json_decode($Data->invoice_type, true) : [];
                                                    if (!is_array($invoiceTypes)) {
                                                        $invoiceTypes = [];
                                                    }
                                                    $invoiceIds = !empty($Data->invoice_id) ? json_decode($Data->invoice_id, true) : [];
                                                    if (!is_array($invoiceIds)) {
                                                        $invoiceIds = !empty($Data->invoice_id) ? [$Data->invoice_id] : [];
                                                    }
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= !empty($Data->date) ? date('d-m-Y', strtotime($Data->date)) : ''; ?></td>
                                            <td>
                                                <?php
                                                    if (in_array("Sale", $invoiceTypes, true) && !empty($invoiceIds)) {
                                                        echo $this->db->select('invoiceNumber')->where_in('id', $invoiceIds)->get('sales')->row('invoiceNumber'); 
                                                    } elseif (in_array("Estimate", $invoiceTypes, true) && !empty($invoiceIds)) {
                                                        echo $this->db->select('invoiceNumber')->where_in('id', $invoiceIds)->get('estimates')->row('invoiceNumber'); 
                                                    }
                                                ?>
                                            </td>
                                            <td><?= get_title('parties', ['id' => $Data->party_id], 'name'); ?></td>
                                            <td><?= get_title('payment_mode', ['id' => $Data->paymentmode], 'payment_method'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($rowReceived, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($rowPaid, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($rowDiscount, 2); ?></td>
                                            <td><?= $Data->remark; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalReceivedAmount, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalPaidAmount, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalDiscountAmount, 2); ?></th>
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


<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">    
    function PreviousMonth() {
        var fromDate = document.getElementById('From').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('To').value = lastDay;
        document.getElementById('From').value = firstDay;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/PaymentDiscountReport?" + form;
    }

    function CurrentMonth() {
        var today = new Date();
        var dd = today.getDate();
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
        document.getElementById('From').value = today;
        document.getElementById('To').value = monthlastdate;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/PaymentDiscountReport?" + form;
    }

    function NextMonth() {
        var fromDate = document.getElementById('From').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('To').value = lastDay;
        document.getElementById('From').value = firstDay;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/PaymentDiscountReport?" + form;
    }

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_PaymentDiscountReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_PaymentDiscountReport";
        <?php } ?>        
        
        $("#DataTable").DataTable({
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