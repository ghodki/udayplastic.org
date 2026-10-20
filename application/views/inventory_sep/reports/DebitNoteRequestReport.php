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
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-dark">Debit Note Request Report</h5>
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
                                        <button type="submit" class="btn btn-xs bg-dark">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/DebitNoteRequestReport" class="btn btn-danger btn-xs" title="Refresh">
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
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>State</th>
                                            <th>Taxable</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Debit Note</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $TotalTaxableAmount = 0;
                                            $TotalTaxAmount = 0;
                                            $Total = 0;
                                            if (!empty($CreditNotesData)) { 
                                                foreach ($CreditNotesData as $key => $CreditNote) {
                                                    $Sr++;
                                                    $TotalTaxableAmount += (float) ($CreditNote->taxableAmount ?? 0);
                                                    $TotalTaxAmount += (float) ($CreditNote->totalTax ?? 0);
                                                    $Total += (float) ($CreditNote->total ?? 0);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($CreditNote->invoiceDate)); ?></td>
                                            <td><?= $CreditNote->invoiceNumber; ?></td>
                                            <td><?= get_title('parties', ['id' => $CreditNote->customerId], 'name'); ?></td>
                                            <td><?= get_title('state_list', ['id' => get_title('parties', ['id' => $CreditNote->customerId], 'state')], 'state'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($CreditNote->taxableAmount ?? 0), 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($CreditNote->totalTax ?? 0), 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($CreditNote->total ?? 0), 2); ?></td>
                                            <td>
                                                <?php if ($CreditNote->DebitNoteRequest == 1) { ?>
                                                <a href="<?= base_url(); ?>Debit_note/CreateInvoice/<?= $CreditNote->id; ?>" class="btn bg-maroon btn-sm" title="Create Debit Note Invoice" target="_blank">
                                                    <i class="fa fa-shopping-cart"></i> Create
                                                </a>
                                                <?php } elseif ($CreditNote->DebitNoteRequest == 2) { ?>
                                                <a href="<?= base_url(); ?>Debit_note/edit/<?= get_title('debit_notes', ['CreditNoteId' => $CreditNote->id], 'id'); ?>" class="btn btn-success btn-sm" target="_blank">
                                                    <?= get_title('debit_notes', ['CreditNoteId' => $CreditNote->id], 'invoiceNumber'); ?>
                                                </a>
                                                <?php } else { ?>
                                                <label class="btn btn-danger btn-xs">
                                                    <i class="fa fa-ban"></i> Cancelled
                                                </label>                                                    
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url(); ?>Credit_note/view/<?= $CreditNote->id; ?>" class="btn btn-sm btn-primary" title="View Invoice" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= number_format((float) ($TotalTaxableAmount ?? 0), 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format((float) ($TotalTaxAmount ?? 0), 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format((float) ($Total ?? 0), 2); ?></th>
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
        window.location.href = "<?= base_url(); ?>Reports/DebitNoteRequestReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Reports/DebitNoteRequestReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Reports/DebitNoteRequestReport?" + form;
    }

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_DebitNoteRequestReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_DebitNoteRequestReport";
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
    
    const channel = new BroadcastChannel('reload-channel');
    channel.onmessage = (event) => {
        if (event.data === 'reload') {
            location.reload();
        }
    };
</script>