<?php
    if (empty($EmployeeInfo)) {
        $EmployeeInfo = (object) ['Name' => '', 'Photograph' => ''];
    }
    if (empty($EmployeePermission)) {
        $EmployeePermission = (object) [];
    }
?>
<div class="gradiant-bg">
    <header>
        <nav class="navbar navbar-expand-md">
            <div class="container-fluid">
                <div class="navbar-menu d-md-block" style="padding-right: 15px;">
                    <div class="ham-menu">
                        <label class="bar" for="check">
                            <input type="checkbox" id="check">
                            <span class="top"></span>
                            <span class="middle"></span>
                            <span class="bottom"></span>
                        </label>
                    </div>
                </div>
                <a href="<?= base_url(); ?>Employee/Dashboard" class="navlogo">
                    <img src="<?= base_url(); ?>inv-assets/images/logo.svg" />
                </a>
                <div class="primary-show">
                    <ul class="d-flex" style="margin-bottom: 0rem; padding-left: 0rem;">
                        <li class="p-2 hidden" style="list-style: none;">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-building text-white"></i> : <?= ucfirst(get_title('inventory_billing', ['id' => $this->session->userdata('CompId')], 'company_name')); ?>
                            </label>
                        </li>
                        <?php if ($this->session->userdata('BranchRole')) { ?>
                        <li class="p-2 hidden" style="list-style: none;">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-sitemap text-white"></i> : <?= ucfirst(get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>
                            </label>
                        </li>
                        <?php } ?>
                        <li class="p-2 hidden" style="list-style: none;">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-user text-white"></i> : <?= $EmployeeInfo->Name; ?>
                            </label>
                        </li>
                        <li class="p-2 hidden" style="list-style: none;">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-map-marker text-white"></i> : <?= get_title('state_list', ['id' => $this->session->userdata('state')], 'state'); ?>
                            </label>
                        </li>
                        <li class="p-2" style="list-style: none;">
                            <?php 
                                $PaymentRequestWhere = ['user_id' => $this->session->userdata('UserId'), 'comp_id' => $this->session->userdata('CompId'), 'status' => 0];
                                $EmployeeIds = json_decode(get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'WarehouseSalesman'), true) ?: [];
                                $EmployeeIds[] = $this->session->userdata('EmployeeId');
                                if ($this->session->userdata('BranchRole')) {
                                    $PaymentRequestWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $PaymentRequestQuery = $this->db->select('date')->where($PaymentRequestWhere)->where_in('SalesmanId', $EmployeeIds)->order_by('date', 'ASC')->get('paymentrequest');
                                $PaymentRequestFrom = ($PaymentRequestQuery->num_rows() && ($__prRow = $PaymentRequestQuery->row())) ? $__prRow->date : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Employee/PaymentRequestReport?status=0&from=<?= $PaymentRequestFrom; ?>&to=<?= date('Y-m-d'); ?>" class="text-white button" title="Pending Payment Request">
                                <i class="fa fa-bell text-white <?= $PaymentRequestQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $PaymentRequestQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <li class="p-2" style="list-style: none;">
                            <?php 
                                $OrderbookWhere = ['IsDeleted' => 1, 'UserId' => $this->session->userdata('UserId'), 'CompId' => $this->session->userdata('CompId'), 'InvoiceStatus' => 1, 'FinancialYear' => $this->session->userdata('financial_year')];
                                if ($this->session->userdata('BranchRole')) {
                                    $OrderbookWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $OrderbookQuery = $this->db->select('OrderDate')->where($OrderbookWhere)->where_in('EmployeeId', $EmployeeIds)->order_by('OrderDate', 'ASC')->get('orderbook');
                                $OrderbookFrom = ($OrderbookQuery->num_rows() && ($__obRow = $OrderbookQuery->row())) ? $__obRow->OrderDate : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Employee/OrderbookReport?Status=1&From=<?= $OrderbookFrom; ?>&To=<?= date('Y-m-d'); ?>" class="text-white button" title="Pending Orderbook">
                                <i class="fa fa-shopping-bag text-white <?= $OrderbookQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $OrderbookQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <li class="p-2" style="list-style: none;">
                            <?php
                                $DeliveryWhere = ['user_id' => $this->session->userdata('UserId'), 'comp_id' => $this->session->userdata('CompId'), 'financial_year' => $this->session->userdata('financial_year'), 'status' => 1, 'delivery_status' => 0];
                                if ($this->session->userdata('BranchRole')) {
                                    $DeliveryWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $SaleInvoiceFrom = $this->db->select('id, invoiceDate')->where($DeliveryWhere)->where_in('SalesmanId', $EmployeeIds)->get_compiled_select('sales');
                                $EstimateInvoiceFrom = $this->db->select('id, invoiceDate')->where($DeliveryWhere)->where_in('SalesmanId', $EmployeeIds)->get_compiled_select('estimates');
                                $SalesEstimatesFromQuery = '(' . $SaleInvoiceFrom . ' UNION ' . $EstimateInvoiceFrom . ') AS SalesEstimatesFrom';
                                $this->db->reset_query();
                                $this->db->select('invoiceDate');
                                $this->db->order_by('invoiceDate', 'ASC');
                                $InvoiceFromRow = $this->db->get($SalesEstimatesFromQuery)->row();
                                $InvoiceFromDate = ($InvoiceFromRow && !empty($InvoiceFromRow->invoiceDate)) ? $InvoiceFromRow->invoiceDate : date('Y-m-d');
                                $SaleEstimateDeliveryNumRows = ($this->db->select('id')->where($DeliveryWhere)->where_in('SalesmanId', $EmployeeIds)->get('sales')->num_rows() + $this->db->select('id')->where($DeliveryWhere)->where_in('SalesmanId', $EmployeeIds)->get('estimates')->num_rows());
                            ?>
                            <a href="<?= base_url(); ?>Employee/SalesReport?estimate=true&Party=All&salesman%5B%5D=All&fromDate=<?= $InvoiceFromDate; ?>&toDate=<?= date('Y-m-d'); ?>&Delivery=0" class="text-white button" title="Pending Delivery">
                                <i class="fa fa-bus text-white <?= $SaleEstimateDeliveryNumRows ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $SaleEstimateDeliveryNumRows; ?>
                                </span>
                            </a>
                        </li>
                        <li class="p-2" style="list-style: none;">
                            <?php
                                $LeaveWhere = ['UserId' => $this->session->userdata('UserId'), 'CompId' => $this->session->userdata('CompId'), 'Status' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                    $LeaveWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $LeaveQuery = $this->db->select('Date')->where($LeaveWhere)->where_in('EmployeeId', $EmployeeIds)->order_by('Date', 'ASC')->get('leave_request');
                                $LeaveFrom = ($LeaveQuery->num_rows() && ($__lvRow = $LeaveQuery->row())) ? $__lvRow->Date : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Employee/Leave?From=<?= $LeaveFrom; ?>&To=<?= date('Y-m-d'); ?>&Status=1" class="text-white button" title="Pending Leave Request">
                                <i class="fa fa-user-tie text-white <?= $LeaveQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $LeaveQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <li class="p-2 FinancialYear" style="list-style: none;">
                            <select name="year" id="year" class="form-control form-control-sm year" onchange="ChangeFinancialYear(this.value);" style="width: 100px;">
                                <?php $YearQuery = $this->db->select('id, year')->order_by('year', 'DESC')->get('year'); if ($YearQuery->num_rows()) { foreach ($YearQuery->result() as $key => $ydata) { $expstr = explode("-", $ydata->year);  ?>
                                <option <?= $this->session->userdata('financial_year') == $ydata->id ? "selected" : ""; ?> value="<?= $ydata->id; ?>"><?= $expstr[0] . '-20' . $expstr[1]; ?></option>
                                <?php } } ?>
                            </select>
                        </li>
                    </ul>
                    <?php $ImageFile = 'uploads/Employee/Photograph/' . $EmployeeInfo->Photograph; ?>
                    <div class="btn-group user-right align-items-end dropdown-menu-container">
                        <div class="btn dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="usericon">
                                <?php if (file_exists($ImageFile) && !empty($EmployeeInfo->Photograph)) { ?>
                                <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                <?php } else { ?>
                                <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                                <?php } ?>
                            </span>
                            <span class="userheadname">
                                <?= $EmployeeInfo->Name; ?>
                                <br />
                                <small class="text-white">
                                    (<?= $this->session->userdata('RoleType'); ?>)
                                </small>
                            </span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-hover">
                            <li>
                                <a href="<?= base_url(); ?>Employee/Profile" class="dropdown-item">
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>Signin/logout" class="dropdown-item">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- navbar menu -->
                <div class="collapse navbar-collapse menu" id="menu">
                    <ul class="nav-list navbar-nav me-auto mb-2 mb-lg-0 menu__inner">
                        <li class="menu__item">
                            <a href="<?= base_url(); ?>Employee/Dashboard" class="menu__link">
                                Dashboard
                            </a>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Masters
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Parties</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Parties', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/PartiesReport">
                                                <i class="fa fa-angle-right"></i> Parties Report
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Products</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Products', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/productsReport">
                                                <i class="fa fa-angle-right"></i> Products Report
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Ledger', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Ledger">
                                                <i class="fa fa-angle-right"></i> Ledger
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Vouchers
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text" style="max-width: 1192px;">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Sales</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Orderbook', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Orderbook">
                                                <i class="fa fa-angle-right"></i> Orderbook
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'Sales', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Sales">
                                                <i class="fa fa-angle-right"></i> Sale
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'Estimates', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Estimates">
                                                <i class="fa fa-angle-right"></i> Estimate
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Purchases</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Purchases', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Purchases">
                                                <i class="fa fa-angle-right"></i> Purchase
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'EstimatePurchase', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/EstimatesPurchases">
                                                <i class="fa fa-angle-right"></i> Estimate Purchase
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Payment</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'receipts', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>employee/receipts">
                                                <i class="fa fa-angle-right"></i> Receipt
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'Payments', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Payments">
                                                <i class="fa fa-angle-right"></i> Payment
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Ledger', 'Create')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/LedgerEntries">
                                                <i class="fa fa-angle-right"></i> Ledger
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/<?= ($this->session->userdata('RoleType') == "Salesman") ? 'employeeSales' : 'seniorEmployeeSales'; ?>">
                                                <i class="fa fa-angle-right"></i> Employee Sales
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/<?= ($this->session->userdata('RoleType') == "Salesman") ? 'employeeTravels' : 'seniorEmployeeTravels'; ?>">
                                                <i class="fa fa-angle-right"></i> Employee Travels
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/<?= ($this->session->userdata('RoleType') == "Salesman") ? 'employeeOutstationTravels' : 'seniorEmployeeOutstationTravels' ?>">
                                                <i class="fa fa-angle-right"></i> Employee Outstation Travels
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Reports
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text" style="max-width: 1192px;">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Sale</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Orderbook', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/OrderbookReport">
                                                <i class="fa fa-angle-right"></i> Orderbook Report
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'Sales', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/SalesReport">
                                                <i class="fa fa-angle-right"></i> Sales Report
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'Estimates', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/EstimatesReport">
                                                <i class="fa fa-angle-right"></i> Estimates Report
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 25%;">
                                    <h4 class="submenu__title">Purchase</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'Purchases', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/PurchasesReport">
                                                <i class="fa fa-angle-right"></i> Purchases Report
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'EstimatePurchase', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/EstimatesPurchasesReport">
                                                <i class="fa fa-angle-right"></i> Estimates Purchases Report
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'PurchaseOrder', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/PurchaseOrderReport">
                                                <i class="fa fa-angle-right"></i> Purchase Order Report
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">
                                        <?php if (employee_perm($EmployeePermission, 'PaymentsRequest', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/PaymentRequestReport">
                                                <i class="fa fa-angle-right"></i> Payment Request Report
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'Sales', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/PartiesSalesReport">
                                                <i class="fa fa-angle-right"></i> Party Wise Report
                                            </a>
                                        </li>
                                        <?php } if (employee_perm($EmployeePermission, 'Stocks', 'View')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/StockReport">
                                                <i class="fa fa-angle-right"></i> Stock Report
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/PartiesOutstanding">
                                                <i class="fa fa-angle-right"></i> Party Collection Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/PartiesBalanceReport">
                                                <i class="fa fa-angle-right"></i> Parties Balance Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 27%;">
                                    <h4 class="submenu__title">&nbsp;</h4>
                                    <ul class="submenu__list">
                                        <?php if ($this->session->userdata('RoleType') == "Salesman") { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/employeeSalesReport">
                                                <i class="fa fa-angle-right"></i> Employee Sales Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/employeeTravelsReport">
                                                <i class="fa fa-angle-right"></i> Employee Travels Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/employeeOutstationTravelsReport">
                                                <i class="fa fa-angle-right"></i> Employee Outstation Travels Report
                                            </a>
                                        </li>
                                        <?php } else { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/seniorEmployeeSalesReport">
                                                <i class="fa fa-angle-right"></i> Employee Sales Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/seniorEmployeeTravelsReport">
                                                <i class="fa fa-angle-right"></i> Employee Travels Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/seniorEmployeeOutstationTravelsReport">
                                                <i class="fa fa-angle-right"></i> Employee Outstation Travels Report
                                            </a>
                                        </li>                                        
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <?php if (employee_perm($EmployeePermission, 'Attendance', 'View')) { ?>
                        <li class="menu__item">
                            <a href="<?= base_url(); ?>Employee/EMPAttendance" class="menu__link">
                                Attendance
                            </a>
                        </li>
                        <?php } ?>
                        <li class="menu__item">
                            <a href="<?= base_url(); ?>Employee/Calendar" class="menu__link">
                                Calendar
                            </a>
                        </li>
                        <li class="menu__item">
                            <a href="<?= base_url(); ?>Employee/Leave" class="menu__link">
                                Leave
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</div>