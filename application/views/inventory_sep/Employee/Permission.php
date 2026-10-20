<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <form action="<?= base_url(); ?>Employee/PermissionUpdate" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="PermissionId" value="<?= set_value('PermissionId', $Permission->Id); ?>" id="PermissionId" />
                    <input type="hidden" name="EmployeePrimaryId" value="<?= set_value('EmployeePrimaryId', $Permission->EmployeeId); ?>" id="EmployeePrimaryId" />
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="card-title text-primary">
                                    Permissions
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee" class="btn btn-primary btn-sm pull-right" title="Employee Report">
                                <i class="fa fa-angle-double-left text-white"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <?php
                                $__permDecode = function ($json) {
                                    $decoded = !empty($json) ? json_decode($json) : null;
                                    return is_object($decoded) ? $decoded : (object) [];
                                };
                                $SalesPerm = $__permDecode($Permission->Sales ?? null);
                                $PurchasesPerm = $__permDecode($Permission->Purchases ?? null);
                                $EstimatesPerm = $__permDecode($Permission->Estimates ?? null);
                                $ReceiptsPerm = $__permDecode($Permission->receipts ?? null);
                                $PaymentsPerm = $__permDecode($Permission->Payments ?? null);
                                $PaymentsRequestPerm = $__permDecode($Permission->PaymentsRequest ?? null);
                                $StocksPerm = $__permDecode($Permission->Stocks ?? null);
                                $PartiesPerm = $__permDecode($Permission->Parties ?? null);
                                $ProductsPerm = $__permDecode($Permission->Products ?? null);
                                $OrderbookPerm = $__permDecode($Permission->Orderbook ?? null);
                                $AttendancePerm = $__permDecode($Permission->Attendance ?? null);
                                $EstimatePurchasePerm = $__permDecode($Permission->EstimatePurchase ?? null);
                                $PurchaseOrderPerm = $__permDecode($Permission->PurchaseOrder ?? null);
                                $SalesDeliveriesPerm = $__permDecode($Permission->SalesDeliveries ?? null);
                                $LedgerPerm = $__permDecode($Permission->Ledger ?? null);
                            ?>
                            <div class="col-md-12">
                                <legend class="text-primary" style="font-size: 15px;">Employee</legend>
                                <hr class="bg-primary" />
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="EmployeeId">Employee Id</label>
                                        <input type="text" name="EmployeeId" value="<?= set_value('EmployeeId', $Employee->EmployeeId); ?>" id="EmployeeId" class="form-control form-control-sm" placeholder="Employee Id" readonly="" />
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="Name">Name</label>
                                        <input type="text" name="Name" value="<?= set_value('Name', $Employee->Name); ?>" id="Name" class="form-control form-control-sm" placeholder="Name" readonly="" />
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="Post">Post</label>
                                        <input type="text" name="Post" value="<?= set_value('Post', get_title('position', ['Id' => $Employee->RoleId], 'PostName')); ?>" id="Post" class="form-control form-control-sm" placeholder="Post" readonly="" />
                                    </div>
                                </div>
                                
                                <legend class="text-primary" style="font-size: 15px;">Employee Permissions</legend>
                                <hr class="bg-primary" />
                                <div class="row">    
                                    <div class="col-md-2 form-group">
                                        <label for="Sales">Sales</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="SalesCreate" value="Create" <?= set_checkbox('SalesCreate', 'Create'); ?> id="SalesCreate" <?= ($SalesPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="SalesView" value="View" <?= set_checkbox('SalesView', 'View'); ?> id="SalesView" <?= ($SalesPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="SalesEdit" value="Edit" <?= set_checkbox('SalesEdit', 'Edit'); ?> id="SalesEdit" <?= ($SalesPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="SalesDelete" value="Delete" <?= set_checkbox('SalesDelete', 'Delete'); ?> id="SalesDelete" <?= ($SalesPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                        <i class="fa fa-file text-yellow" title="E-Invoice" ></i>
                                        <input type="checkbox" name="EInvoiceCreate" value="E_Invoice" <?= set_checkbox('EInvoiceCreate', 'E_Invoice'); ?> id="EInvoiceCreate" <?= ($SalesPerm->EInvoiceCreate ?? null) == "E_Invoice" ? "checked" : ""; ?> title="E-Invoice Create" />
                                        <i class="fa fa-file text-gray" title="E-Way Bill" ></i>
                                        <input type="checkbox" name="EWayCreate" value="E_Way" <?= set_checkbox('EWayCreate', 'E_Way'); ?> id="EWayCreate" <?= ($SalesPerm->EWayCreate ?? null) == "E_Way" ? "checked" : ""; ?> title="E-Way Bill Create" />

                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="Purchases">Purchases</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="PurchasesCreate" value="Create" <?= set_checkbox('PurchasesCreate', 'Create'); ?> id="PurchasesCreate" <?= ($PurchasesPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="PurchasesView" value="View" <?= set_checkbox('PurchasesView', 'View'); ?> id="PurchasesView" <?= ($PurchasesPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="PurchasesEdit" value="Edit" <?= set_checkbox('PurchasesEdit', 'Edit'); ?> id="PurchasesEdit" <?= ($PurchasesPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="PurchasesDelete" value="Delete" <?= set_checkbox('PurchasesDelete', 'Delete'); ?> id="PurchasesDelete" <?= ($PurchasesPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="Estimates">Estimates</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="EstimatesCreate" value="Create" <?= set_checkbox('EstimatesCreate', 'Create'); ?> id="EstimatesCreate" <?= ($EstimatesPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="EstimatesView" value="View" <?= set_checkbox('EstimatesView', 'View'); ?> id="EstimatesView" <?= ($EstimatesPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="EstimatesEdit" value="Edit" <?= set_checkbox('EstimatesEdit', 'Edit'); ?> id="EstimatesEdit" <?= ($EstimatesPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="EstimatesDelete" value="Delete" <?= set_checkbox('EstimatesDelete', 'Delete'); ?> id="EstimatesDelete" <?= ($EstimatesPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                   <div class="col-md-2 form-group">
                                        <label for="receipts">Receipts</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="ReceiptsCreate" value="Create" <?= set_checkbox('ReceiptsCreate', 'Create'); ?> id="ReceiptsCreate" <?= ($ReceiptsPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="ReceiptsView" value="View" <?= set_checkbox('ReceiptsView', 'View'); ?> id="ReceiptsView" <?= ($ReceiptsPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="ReceiptsEdit" value="Edit" <?= set_checkbox('ReceiptsEdit', 'Edit'); ?> id="ReceiptsEdit" <?= ($ReceiptsPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="ReceiptsDelete" value="Delete" <?= set_checkbox('ReceiptsDelete', 'Delete'); ?> id="ReceiptsDelete" <?= ($ReceiptsPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>                                        
                                    <div class="col-md-2 form-group">
                                        <label for="Payments">Payments</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="PaymentsCreate" value="Create" <?= set_checkbox('PaymentsCreate', 'Create'); ?> id="PaymentsCreate" <?= ($PaymentsPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="PaymentsView" value="View" <?= set_checkbox('PaymentsView', 'View'); ?> id="PaymentsView" <?= ($PaymentsPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="PaymentsEdit" value="Edit" <?= set_checkbox('PaymentsEdit', 'Edit'); ?> id="PaymentsEdit" <?= ($PaymentsPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="PaymentsDelete" value="Delete" <?= set_checkbox('PaymentsDelete', 'Delete'); ?> id="PaymentsDelete" <?= ($PaymentsPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="PaymentsRequest">Payments Request</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="PaymentsRequestCreate" value="Create" <?= set_checkbox('PaymentsRequestCreate', 'Create'); ?> id="PaymentsRequestCreate" <?= ($PaymentsRequestPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="PaymentsRequestView" value="View" <?= set_checkbox('PaymentsRequestView', 'View'); ?> id="PaymentsRequestView" <?= ($PaymentsRequestPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="PaymentsRequestEdit" value="Edit" <?= set_checkbox('PaymentsRequestEdit', 'Edit'); ?> id="PaymentsRequestEdit" <?= ($PaymentsRequestPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="PaymentsRequestDelete" value="Delete" <?= set_checkbox('PaymentsRequestDelete', 'Delete'); ?> id="PaymentsRequestDelete" <?= ($PaymentsRequestPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="Stocks">Stocks</label>
                                        <br />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="StocksView" value="View" <?= set_checkbox('StocksView', 'View'); ?> id="StocksView" <?= ($StocksPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                    </div>
                                </div>
                                
                                <hr class="bg-primary" />
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="Parties">Parties</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="PartiesCreate" value="Create" <?= set_checkbox('PartiesCreate', 'Create'); ?> id="PartiesCreate" <?= ($PartiesPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="PartiesView" value="View" <?= set_checkbox('PartiesView', 'View'); ?> id="PartiesView" <?= ($PartiesPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="PartiesEdit" value="Edit" <?= set_checkbox('PartiesEdit', 'Edit'); ?> id="PartiesEdit" <?= ($PartiesPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="PartiesDelete" value="Delete" <?= set_checkbox('PartiesDelete', 'Delete'); ?> id="PartiesDelete" <?= ($PartiesPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="Products">Products</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="ProductsCreate" value="Create" <?= set_checkbox('ProductsCreate', 'Create'); ?> id="ProductsCreate" <?= ($ProductsPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="ProductsView" value="View" <?= set_checkbox('ProductsView', 'View'); ?> id="ProductsView" <?= ($ProductsPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="ProductsEdit" value="Edit" <?= set_checkbox('ProductsEdit', 'Edit'); ?> id="ProductsEdit" <?= ($ProductsPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="ProductsDelete" value="Delete" <?= set_checkbox('ProductsDelete', 'Delete'); ?> id="ProductsDelete" <?= ($ProductsPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="Orderbook">Orderbook</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="OrderbookCreate" value="Create" <?= set_checkbox('OrderbookCreate', 'Create'); ?> id="OrderbookCreate" <?= ($OrderbookPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="OrderbookView" value="View" <?= set_checkbox('OrderbookView', 'View'); ?> id="OrderbookView" <?= ($OrderbookPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="OrderbookEdit" value="Edit" <?= set_checkbox('OrderbookEdit', 'Edit'); ?> id="OrderbookEdit" <?= ($OrderbookPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="OrderbookDelete" value="Delete" <?= set_checkbox('OrderbookDelete', 'Delete'); ?> id="OrderbookDelete" <?= ($OrderbookPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="Attendance">Attendance</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="AttendanceCreate" value="Create" <?= set_checkbox('AttendanceCreate', 'Create'); ?> id="AttendanceCreate" <?= ($AttendancePerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="AttendanceView" value="View" <?= set_checkbox('AttendanceView', 'View'); ?> id="AttendanceView" <?= ($AttendancePerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="AttendanceEdit" value="Edit" <?= set_checkbox('AttendanceEdit', 'Edit'); ?> id="AttendanceEdit" <?= ($AttendancePerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="AttendanceDelete" value="Delete" <?= set_checkbox('AttendanceDelete', 'Delete'); ?> id="AttendanceDelete" <?= ($AttendancePerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="EstimatePurchase">Estimate Purchase</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="EstimatePurchaseCreate" value="Create" <?= set_checkbox('EstimatePurchaseCreate', 'Create'); ?> id="EstimatePurchaseCreate" <?= ($EstimatePurchasePerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="EstimatePurchaseView" value="View" <?= set_checkbox('EstimatePurchaseView', 'View'); ?> id="EstimatePurchaseView" <?= ($EstimatePurchasePerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="EstimatePurchaseEdit" value="Edit" <?= set_checkbox('EstimatePurchaseEdit', 'Edit'); ?> id="EstimatePurchaseEdit" <?= ($EstimatePurchasePerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="EstimatePurchaseDelete" value="Delete" <?= set_checkbox('EstimatePurchaseDelete', 'Delete'); ?> id="EstimatePurchaseDelete" <?= ($EstimatePurchasePerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="PurchaseOrder">Purchase Order</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="PurchaseOrderCreate" value="Create" <?= set_checkbox('PurchaseOrderCreate', 'Create'); ?> id="PurchaseOrderCreate" <?= ($PurchaseOrderPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="PurchaseOrderView" value="View" <?= set_checkbox('PurchaseOrderView', 'View'); ?> id="PurchaseOrderView" <?= ($PurchaseOrderPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="PurchaseOrderEdit" value="Edit" <?= set_checkbox('PurchaseOrderEdit', 'Edit'); ?> id="PurchaseOrderEdit" <?= ($PurchaseOrderPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="PurchaseOrderDelete" value="Delete" <?= set_checkbox('PurchaseOrderDelete', 'Delete'); ?> id="PurchaseOrderDelete" <?= ($PurchaseOrderPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                </div>
                                
                                <hr class="bg-primary" />
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="SalesDeliveries">Sales Deliveries</label>
                                        <br />
                                        <i class="fa fa-edit text-primary" title="Edit"></i>
                                        <input type="checkbox" name="SalesDeliveriesEdit" value="Edit" <?= set_checkbox('SalesDeliveriesEdit', 'Edit'); ?> id="SalesDeliveriesEdit" <?= ($SalesDeliveriesPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                    </div>
                                    <div class="col-md-2 form-group d-none">
                                        <label for="Ledger">Ledger</label>
                                        <br />
                                        <i class="fa fa-plus text-info" title="Create"></i>
                                        <input type="checkbox" name="LedgerCreate" value="Create" <?= set_checkbox('LedgerCreate', 'Create'); ?> id="LedgerCreate" <?= ($LedgerPerm->Create ?? null) == "Create" ? "checked" : ""; ?> title="Create" />
                                        <i class="fa fa-eye text-primary" title="View"></i>
                                        <input type="checkbox" name="LedgerView" value="View" <?= set_checkbox('LedgerView', 'View'); ?> id="LedgerView" <?= ($LedgerPerm->View ?? null) == "View" ? "checked" : ""; ?> title="View" />
                                        <i class="fa fa-edit text-success" title="Edit"></i>
                                        <input type="checkbox" name="LedgerEdit" value="Edit" <?= set_checkbox('LedgerEdit', 'Edit'); ?> id="LedgerEdit" <?= ($LedgerPerm->Edit ?? null) == "Edit" ? "checked" : ""; ?> title="Edit" />
                                        <i class="fa fa-trash text-danger" title="Delete"></i>
                                        <input type="checkbox" name="LedgerDelete" value="Delete" <?= set_checkbox('LedgerDelete', 'Delete'); ?> id="LedgerDelete" <?= ($LedgerPerm->Delete ?? null) == "Delete" ? "checked" : ""; ?> title="Delete" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-sm bg-primary">
                                        <i class="fa fa-save text-white"></i> Save Changes
                                    </button>
                                    <a href="<?= base_url(); ?>Employee" class="btn btn-danger btn-sm">
                                        <i class="fa fa-times text-white"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>                            
            </div>
        </div>
    </div>
</section>