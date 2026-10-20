<style type="text/css">
    .form-group {
        margin-bottom: 0.5rem;
    }
    th, td {
        white-space: nowrap;
    }
    @media (max-width:300px) {
        .dt-buttons {
            left: 5%;
        }
    }
    @media (max-width:380px) {
        .dt-buttons {
            left: 5%;
        }
    }
    @media (max-width:320px) {
        .dt-buttons {
            left: 5%;
        }
    }
    @media (max-width:400px) {
        .dt-buttons {
            left: 5%;
        }
    }
    @media (max-width:420px) {
        .dt-buttons {
            left: 5%;
        }
    }
    @media (max-width:480px) {
        .dt-buttons {
            left: 5%;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <h3 class="m-0">Orderbook</h3>
                </div><!-- /.col -->
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Reports</a></li>
                        <li class="breadcrumb-item active">Orderbook Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
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
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-maroon">
                                    Orderbook Report
                                </h5>
                            </div>
                            <div class="pull-right">
                                <?php if (employee_perm($EmployeePermission, 'Orderbook', 'Create')) { ?>
                                <a href="<?= base_url(); ?>Employee/Orderbook" class="btn btn-xs bg-maroon" title="Orderbook Create">
                                    <i class="fa fa-plus text-white"></i> Create
                                </a>
                                <?php } ?>
                                <a href="<?= base_url(); ?>Employee/OrderbookReportGrid" class="btn btn-xs bg-primary" title="Orderbook Report Grid View">
                                    <i class="fa fa-th-large text-white"></i> Grid View
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>Employee/OrderbookReport" method="GET">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="From" value="<?= set_value('From', $this->input->get('From') ? $this->input->get('From') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="To" value="<?= set_value('To', $this->input->get('To') ? $this->input->get('To') : date('Y-m-d')); ?>" id="To" class="form-control form-control-sm" title="To" />
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-4 form-group">
                                        <select name="Status" id="Status" class="form-control form-control-sm" title="Orderbook Status">
                                            <option value="" <?= set_select('Status', ''); ?> <?= $this->input->get('Status') == '' ? 'selected' : ''; ?>>All</option>
                                            <option value="1" <?= set_select('Status', '1'); ?> <?= $this->input->get('Status') == '1' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="2" <?= set_select('Status', '2'); ?> <?= $this->input->get('Status') == '2' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="0" <?= set_select('Status', '0'); ?> <?= $this->input->get('Status') == '0' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <button type="submit" class="btn btn-sm bg-maroon">
                                            <i class="fa fa-search text-white"></i>
                                        </button>
                                        <a href="<?= base_url(); ?>Employee/OrderbookReport" class="btn btn-default btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="bg-maroon">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">Date</th>
                                                    <th class="text-white">Order Number</th>
                                                    <th class="text-white">Customer Name</th>
                                                    <th class="text-white">Mobile</th>
                                                    <th class="text-white">Total</th>
                                                    <th class="text-white">Invoice</th>
                                                    <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") { ?>
                                                    <th class="text-white">Salesman</th>
                                                    <?php } ?>
                                                    <th class="text-white"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $Sr = 0;
                                                    $TotalAmount = 0;
                                                    if ($OrderBook) {
                                                        foreach ($OrderBook as $key => $Data) { 
                                                            $Sr++;
                                                            $TotalAmount += (float) ($Data->SubTotal ?? 0);
                                                ?>
                                                <tr>
                                                    <td><?= $Sr; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($Data->OrderDate)); ?></td>
                                                    <td><?= $Data->OrderNumber; ?></td>
                                                    <td><?= get_title('parties', ['id' => $Data->PartyId], 'name'); ?></td>
                                                    <td><?= get_title('parties', ['id' => $Data->PartyId], 'contact'); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= $Data->SubTotal; ?></td>
                                                    <td>
                                                        <?php if ($Data->VoucherType == "Sale") { if ($Data->InvoiceStatus == 1) { if (employee_perm($EmployeePermission, 'Sales', 'Create')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/OrderbookToSaleInvoice/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="Create Sale Invoice" target="_blank">
                                                            <i class="fa fa-shopping-bag text-white"></i> Create
                                                        </a>
                                                        <?php } else { ?>
                                                        <label class="btn btn-warning btn-xs">
                                                            <i class="fa fa-spinner"></i> Wait
                                                        </label>
                                                        <?php } } elseif ($Data->InvoiceStatus == 2) { if (employee_perm($EmployeePermission, 'Sales', 'Edit')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/SaleEdit/<?= get_title('sales', ['order_book_id' => $Data->Id], 'id'); ?>" class="btn btn-success btn-xs">
                                                            <?= get_title('sales', ['order_book_id' => $Data->Id] ,'invoiceNumber'); ?>
                                                        </a>
                                                        <?php } else { ?>
                                                        <?= get_title('sales', ['order_book_id' => $Data->Id], 'invoiceNumber'); ?>    
                                                        <?php } } else { ?>
                                                        <label class="btn btn-danger btn-xs">
                                                            <i class="fa fa-ban text-white"></i> Cancelled
                                                        </label>
                                                        <?php } } if ($Data->VoucherType == "Estimate") { if ($Data->InvoiceStatus == 1) { if (employee_perm($EmployeePermission, 'Estimates', 'Create')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/OrderbookToEstimateInvoice/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="Create Estimate Invoice" target="_blank">
                                                            <i class="fa fa-shopping-bag text-white"></i> Create
                                                        </a>
                                                        <?php } else { ?>
                                                        <label class="btn btn-warning btn-xs">
                                                            <i class="fa fa-spinner"></i> Wait
                                                        </label>
                                                        <?php } } elseif ($Data->InvoiceStatus == 2) { if (employee_perm($EmployeePermission, 'Estimates', 'Edit')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/EstimateEdit/<?= get_title('estimates', ['order_book_id' => $Data->Id], 'id'); ?>" class="btn btn-success btn-xs">
                                                            <?= get_title('estimates', ['order_book_id' => $Data->Id] ,'invoiceNumber'); ?>
                                                        </a>
                                                        <?php } else { ?>
                                                        <?= get_title('estimates', ['order_book_id' => $Data->Id], 'invoiceNumber'); ?>
                                                        <?php } } else { ?>
                                                        <label class="btn btn-danger btn-xs">
                                                            <i class="fa fa-ban text-white"></i> Cancelled
                                                        </label>
                                                        <?php } } ?>
                                                    </td>
                                                    <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') { ?>
                                                    <td><?= get_title('employees', ['Id' => $Data->SalesmanId], 'Name'); ?></td>
                                                    <?php } ?>
                                                    <td>
                                                        <?php if (employee_perm($EmployeePermission, 'Orderbook', 'View')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/OrderbookInfo/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="View">
                                                            <i class="fa fa-info-circle text-white"></i>
                                                        </a>
                                                        <?php } if ($Data->InvoiceStatus == 1) { if (employee_perm($EmployeePermission, 'Orderbook', 'Edit')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/OrderbookEdit/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                            <i class="fa fa-edit text-white"></i>
                                                        </a>
                                                        <?php } if (employee_perm($EmployeePermission, 'Orderbook', 'Delete')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/OrderbookDelete/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                            <i class="fa fa-trash text-white"></i>
                                                        </a>
                                                        <?php } } ?>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" style="text-align: right;">Sub Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= $TotalAmount; ?></th>
                                                    <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') { ?>
                                                    <th></th>
                                                    <?php } ?>
                                                    <th colspan="2"></th>
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
    </section>
    <!-- /.content -->
 </div>

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'lBfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });
</script> 