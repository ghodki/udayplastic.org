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
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <div class="card card-outline card-purple">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-purple">Purchase Request Report</h5>
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
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="RequestStatus">Request Status</label>
                                        <select name="RequestStatus" id="RequestStatus" class="form-control form-control-sm" title="Purchase Request Status">
                                            <option value="" <?= $this->input->get('RequestStatus') == '' ? 'selected' : ''; ?>>All</option>
                                            <option value="0" <?= $this->input->get('RequestStatus') == '0' ? 'selected' : ''; ?>>Rejected</option>
                                            <option value="1" <?= $this->input->get('RequestStatus') == '1' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="2" <?= $this->input->get('RequestStatus') == '2' ? 'selected' : ''; ?>>Approved</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-xs bg-purple">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/PurchaseRequestReport" class="btn btn-danger btn-xs" title="Refresh">
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
                                <table class="table table-bordered table-striped table-sm" id="DataTable">
                                    <thead class="bg-purple">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>State</th>
                                            <th>Taxable</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Purchase</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $TotalTaxableAmount = 0;
                                            $TotalTaxAmount = 0;
                                            $Total = 0;
                                            if (!empty($SalesData)) { 
                                                foreach ($SalesData as $key => $Sale) {
                                                    $Sr++;
                                                    $TotalTaxableAmount += (float) ($Sale->taxableAmount ?? 0);
                                                    $TotalTaxAmount += (float) ($Sale->totalTax ?? 0);
                                                    $Total += (float) ($Sale->total ?? 0);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Sale->invoiceDate)); ?></td>
                                            <td><?= $Sale->invoiceNumber; ?></td>
                                            <td><?= get_title('parties', ['id' => $Sale->customerId], 'name'); ?></td>
                                            <td><?= get_title('state_list', ['id' => get_title('parties', ['id' => $Sale->customerId], 'state')], 'state'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sale->taxableAmount ?? 0); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sale->totalTax ?? 0); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sale->total ?? 0); ?></td>
                                            <td>
                                                <?php if ($Sale->PurchaseRequest == 1) { ?>
                                                <a href="<?= base_url(); ?>Purchase/CreateInvoice/<?= $Sale->id; ?>" class="btn bg-maroon btn-sm" title="Create Purchase Invoice" target="_blank">
                                                    <i class="fa fa-shopping-cart"></i> Create
                                                </a>
                                                <?php } elseif ($Sale->PurchaseRequest == 2) { ?>
                                                <a href="<?= base_url(); ?>Purchase/purchaseEdit/<?= get_title('purchases', ['SaleId' => $Sale->id], 'id'); ?>" class="btn btn-success btn-sm" target="_blank">
                                                    <?= get_title('purchases', ['SaleId' => $Sale->id], 'invoiceNumber'); ?>
                                                </a>
                                                <?php } else { ?>
                                                <label class="btn btn-danger btn-xs">
                                                    <i class="fa fa-ban text-white"></i> Cancelled
                                                </label>                                                    
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url(); ?>Sale/viewsale/<?= $Sale->id; ?>" class="btn btn-sm btn-primary" title="View Invoice" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTaxableAmount ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTaxAmount ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total ?? 0); ?></th>
                                            <th></th>
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
        window.location.href = "<?= base_url(); ?>Reports/PurchaseRequestReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Reports/PurchaseRequestReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Reports/PurchaseRequestReport?" + form;
    }

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_PurchaseRequestReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_PurchaseRequestReport";
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
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
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
    
    const channel = new BroadcastChannel('reload-channel');
    channel.onmessage = (event) => {
        if (event.data === 'reload') {
            location.reload();
        }
    };
</script>