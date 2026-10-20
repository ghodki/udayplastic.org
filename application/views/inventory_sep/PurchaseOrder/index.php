<style type="text/css">
    .form-group {
        margin-bottom: 0.5rem;
    }
    th, td {
        white-space: nowrap;
    }
    .dt-buttons {
        left: 20%;
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
                                    Purchase Order Report
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>PurchaseOrder/create" class="btn btn-xs bg-maroon pull-right" title="Purchase Order Create">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>PurchaseOrder" method="GET">
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
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <button type="submit" class="btn btn-sm bg-maroon">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="<?= base_url(); ?>PurchaseOrder" class="btn btn-default btn-sm" title="Refresh">
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
                                                    <th>Sr.</th>
                                                    <th>Date</th>
                                                    <th>Order Number</th>
                                                    <?php if (!$this->session->userdata('CompBranchId')) { ?> 
                                                    <th>Branch Name</th>
                                                    <?php } else { ?>
                                                    <th>Parent Company</th>
                                                    <?php } ?>
                                                    <th>Mobile</th>
                                                    <th>Total</th>
                                                    <?php if (!$this->session->userdata('CompBranchId')) { ?> 
                                                    <th>Invoice</th>
                                                    <?php } ?>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $Sr = 0;
                                                    $TotalAmount = 0;
                                                    if (!empty($OrderBook)) {
                                                        foreach ($OrderBook as $key => $Data) { 
                                                            $Sr++;
                                                            $TotalAmount += (float) ($Data->SubTotal ?? 0);
                                                ?>
                                                <tr>
                                                    <td><?= $Sr; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($Data->PurchaseDate)); ?></td>
                                                    <td><?= $Data->PurchaseNumber; ?></td>
                                                    <?php if (!$this->session->userdata('CompBranchId')) { ?>
                                                    <td>
                                                        <?= get_title('companybranch', ['Id' => $Data->CompanyBranchId], 'BranchName'); ?>
                                                        <small>
                                                            (<?= get_title('parties', ['CompanyBranchId' => $Data->CompanyBranchId, 'user_id' => $Data->UserId, 'comp_id' => $Data->CompId], 'area'); ?>)
                                                        </small>
                                                    </td>
                                                    <td><?= get_title('companybranch', ['Id' => $Data->CompanyBranchId], 'Mobile'); ?></td>
                                                    <?php } else { ?>
                                                    <td><?= get_title('inventory_billing', ['id' => $Data->CompanyId], 'company_name'); ?></td>
                                                    <td><?= get_title('inventory_billing', ['id' => $Data->CompanyId], 'number'); ?></td>
                                                    <?php } ?>
                                                    <td><i class="fa fa-inr"></i> <?= $Data->SubTotal; ?></td>
                                                    <?php if (!$this->session->userdata('CompBranchId')) { ?>
                                                    <td>
                                                        <?php if ($Data->InvoiceStatus == 1) { ?>
                                                        <a href="<?= base_url(); ?>PurchaseOrder/CreateSaleInvoice/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="Create Sale Invoice" onclick="return confirm('Are you sure want to create this record!');" target="_blank">
                                                            <i class="fa fa-shopping-bag"></i> Create
                                                        </a>
                                                        <?php } elseif ($Data->InvoiceStatus == 2) { ?>
                                                        <a href="<?= base_url(); ?>Sale/SaleEdit/<?= get_title('sales', ['PurchaseOrderId' => $Data->Id], 'id'); ?>" class="btn btn-success btn-xs" title="Sale Invoice" tabindex="_blank">
                                                            <?= get_title('sales', ['PurchaseOrderId' => $Data->Id], 'invoiceNumber'); ?>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td>
                                                        <a href="<?= base_url(); ?>PurchaseOrder/PurchaseOrderInfo/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="View">
                                                            <i class="fa fa-info-circle"></i>
                                                        </a>
                                                        <?php if ($this->session->userdata('CompBranchId')) { ?>
                                                        <a href="<?= base_url(); ?>PurchaseOrder/PurchaseOrderEdit/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>PurchaseOrder/PurchaseOrderDelete/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" style="text-align: right;">Sub Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= number_format((float) ($TotalAmount ?? 0), 2); ?></th>
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
    </div>
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