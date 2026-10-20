<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .card-body {
        padding: 0.25rem 0.25rem;
    }
    .btn-group {
        display: block;
    }
    .custom-select {
        height: calc(2rem + 1px);
        line-height: 1;
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
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-outline card-yellow">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-yellow">OrderForm Report</h5>
                            </div>
                            <div class="pull-right">
                                <a href="javascript:void(0);" class="btn btn-primary btn-xs" title="Previous Month" onclick="return PreviousMonth();">
                                    <i class="fa fa-calendar-minus-o"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-warning btn-xs" title="Current Month" onclick="return CurrentMonth();">
                                    <i class="fa fa-calendar-check-o"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-dark btn-xs" title="Next Month" onclick="return NextMonth();">
                                    <i class="fa fa-calendar-plus-o"></i>
                                </a>
                                <a href="<?= base_url(); ?>Orderform/Create" class="btn btn-info btn-xs" title="Create Orderform">
                                    <i class="fa fa-plus"></i> Create
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" id="ReportForm">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 form-group">
                                        <label for="Party">Parties</label>
                                        <select name="Party[]" id="Party" class="form-control form-control-sm" title="Party Name" multiple="">
                                            <?php $selectedParties = function_exists('input_get_array') ? input_get_array('Party') : (array) ($this->input->get('Party') ?: []); ?>
                                            <option <?= in_array('All', $selectedParties, true) ? "selected" : ""; ?> value="All">All Party</option>
                                            <?php if (!empty($Parties)) { foreach ($Parties as $key => $Party) { ?>
                                            <option <?= in_array($Party->id, $selectedParties) ? "selected" : ""; ?> value="<?= $Party->id; ?>"><?= $Party->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="Salesmans">Salesman</label>
                                        <select name="Salesmans[]" id="Salesmans" class="form-control form-control-sm" title="Salesmans" multiple="">
                                            <?php $selectedSalesmans = function_exists('input_get_array') ? input_get_array('Salesmans') : (array) ($this->input->get('Salesmans') ?: []); ?>
                                            <option <?= in_array('All', $selectedSalesmans, true) ? "selected" : ""; ?> value="All" <?= set_select('Employees', 'All'); ?>>All</option>
                                            <?php if (!empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                            <option <?= in_array($Salesman->Id, $selectedSalesmans) ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>" <?= set_select('Salesmans', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="From">From</label>
                                        <input type="date" name="From" value="<?= $this->input->get('From') ? $this->input->get('From') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="To">To</label>
                                        <input type="date" name="To" value="<?= $this->input->get('To') ? $this->input->get('To') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>"  id="To" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="col-md-1 col-sm-4 form-group">
                                        <label for="Status">Status</label>
                                        <select name="Status" id="Status" class="form-control form-control-sm" title="Orderbook Status">
                                            <option value="" <?= $this->input->get('Status') == '' ? 'selected' : ''; ?>>All</option>
                                            <option value="1" <?= $this->input->get('Status') == '1' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="2" <?= $this->input->get('Status') == '2' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="0" <?= $this->input->get('Status') == '0' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-xs bg-orange">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Orderform" class="btn btn-danger btn-xs" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm" id="DataTable">
                                    <thead class="bg-yellow">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Order Number</th>
                                            <th>Customer Name</th>
                                            <th>Mobile</th>
                                            <th>Total</th>
                                            <th>Salesman</th>
                                            <th>Invoice</th>
                                            <th>Created By</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $Total = 0;
                                            if (!empty($OrderForms)) { 
                                                foreach ($OrderForms as $key => $Order) {
                                                    $Sr++;
                                                    $Total += (float) ($Order->SubTotal ?? 0);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Order->OrderDate)); ?></td>
                                            <td><?= $Order->OrderNumber; ?></td>
                                            <td><?= get_title('parties', ['id' => $Order->PartyId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $Order->PartyId], 'contact'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Order->SubTotal); ?></td>
                                            <td><?= get_title('employees', ['Id' => $Order->SalesmanId], 'Name'); ?></td>
                                            <td>
                                                <?php if ($Order->InvoiceStatus == 1) { if ($Order->VoucherType == "Sale") { ?>
                                                <a href="<?= base_url(); ?>Orderform/OrderbookToSaleInvoice/<?= $Order->Id; ?>" class="btn btn-primary btn-xs" title="Create Sale Invoice" target="_blank">
                                                    <i class="fa fa-shopping-bag"></i> Create
                                                </a>
                                                <?php } elseif ($Order->VoucherType == "Estimate") { ?>
                                                <a href="<?= base_url(); ?>Orderform/OrderbookToEstimateInvoice/<?= $Order->Id; ?>" class="btn btn-primary btn-xs" title="Create Estimate Invoice" target="_blank">
                                                    <i class="fa fa-shopping-bag"></i> Create
                                                </a>
                                                <?php } } elseif ($Order->InvoiceStatus == 2 && get_title('sales', ['order_book_id' => $Order->Id], 'status') == 1 || get_title('estimates', ['order_book_id' => $Order->Id], 'status') == 1) { if ($Order->VoucherType == "Sale") { ?> 
                                                <a href="<?= base_url(); ?>Sale/SaleEdit/<?= get_title('sales', ['order_book_id' => $Order->Id], 'id'); ?>" class="btn btn-success btn-xs" target="_blank">
                                                    <?= get_title('sales', ['order_book_id' => $Order->Id] ,'invoiceNumber'); ?>
                                                </a>
                                                <?php } elseif ($Order->VoucherType == "Estimate") { ?>
                                                <a href="<?= base_url(); ?>Estimate/estimateEdit/<?= get_title('estimates', ['order_book_id' => $Order->Id], 'id'); ?>" class="btn btn-success btn-xs" target="_blank">
                                                    <?= get_title('estimates', ['order_book_id' => $Order->Id] ,'invoiceNumber'); ?>
                                                </a>
                                                <?php } } else { ?>
                                                <label class="btn btn-danger btn-xs">
                                                    <i class="fa fa-ban text-white"></i> Cancelled
                                                </label>
                                                <?php } ?>
                                            </td>
                                            <td><?= $Order->CreatedBy; ?></td>
                                            <td>
                                                <?php if ($Order->InvoiceStatus == 1) { ?>
                                                <a href="<?= base_url(); ?>Orderform/OrderStatus/<?= $Order->Id; ?>" class="btn bg-maroon btn-xs" title="Cancelled" onclick="return confirm('Are you sure want to cancelled this order!');">
                                                    <i class="fa fa-ban"></i>
                                                </a>                                                
                                                <?php } ?>
                                                <a href="<?= base_url(); ?>Orderform/Info/<?= $Order->Id; ?>" class="btn btn-primary btn-xs" title="View">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Orderform/Edit/<?= $Order->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Orderform/Delete/<?= $Order->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th colspan="2"><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total ?? 0); ?></th>
                                            <th colspan="3"></th>
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
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('#Party').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $('#Salesmans').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    function PreviousMonth() {
        var fromDate = document.getElementById('From').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('To').value = lastDay;
        document.getElementById('From').value = firstDay;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Orderform?" + form;
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
        window.location.href = "<?= base_url(); ?>Orderform?" + form;
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
        window.location.href = "<?= base_url(); ?>Orderform?" + form;
    }

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_OrderbookReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_OrderbookReport";
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