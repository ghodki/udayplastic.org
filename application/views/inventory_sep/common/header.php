<style type="text/css">
    .header-bg {
        background: rgb(11 51 137);
    }
    header .ham-menu .bar span {
        width: 30px;
        height: 2px;
        background: #c9c9c9;
    }
    .primary-show label {
        font-weight:400 !important;
    }
    .content-wrapper {
        min-height:86vh !important;
    }
    .menu .menu__inner .menu__item:hover > .menu__link {
        color: #f05f5d;
    }
    .darkmode .menu .menu__inner .menu__item:hover > .menu__link {
        color: #f05f5d;
    }
    .menu .menu__inner .menu__item .menu__link:hover { 
        text-decoration: none;
        color: #f05f5d;
    }
    @media (min-width: 767px) {
        .submenu.megamenu__normal2 {
            left: 78%;
            max-width: 250px;
            width: 100%;
            height: auto;
            margin: 0 auto;
        }
    }
</style>

<!-- <div class="loader-bg" ></div> -->
<div class="header-bg">    
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
                <a href="<?= base_url(); ?>inventory/dashboard" class="navlogo">
                    <img src="<?= base_url(); ?>inv-assets/images/logo.svg" />
                </a>
                <div class="primary-show">
                    <?php 
                        $CompanyQuery = $this->db->select('id, company_name')->where('user_id', $this->session->userdata('user_id'))->where('status', 1)->get('inventory_billing');
                        if ($CompanyQuery->num_rows() > 1 && empty($this->session->userdata('BranchRole'))) {
                            $MultiCompany = "list-item";
                            $SingleCompany = "none";
                        } else {
                            $MultiCompany = "none";
                            $SingleCompany = "list-item";
                        }
                    ?>
                    <ul class="d-flex" style="margin-bottom: 0rem; padding-left: 0rem;">
                        <li class="p-1 hidden" style="list-style: none;">
                            <a href="<?= base_url(); ?>dashboard" title="User Dashboard" style="font-size: 15px;">
                                <i class="fa fa-home text-white"></i>
                            </a>
                        </li>
                        <li class="p-1 hidden" style="list-style: none; display: <?= $SingleCompany; ?>">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-building text-white"></i> : 
                                <?= ucfirst(get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>
                            </label>
                        </li>
                        <?php if ($this->session->userdata('BranchRole')) { ?>
                        <li class="p-1 hidden" style="list-style: none;">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-sitemap text-white"></i> : 
                                <?= ucfirst(get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')], 'BranchName')); ?>
                            </label>
                        </li>
                        <?php } ?>
                        <li class="p-1 hidden" style="list-style: none;">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-user text-white"></i> : 
                                <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'name'); ?>
                            </label>
                        </li>
                        <li class="p-1 hidden" style="list-style: none;">
                            <label class="text-white" style="font-size: 14px;">
                                <i class="fa fa-map-marker text-white"></i> : 
                                <?= get_title('state_list', ['id' => $this->session->userdata('state')], 'state'); ?>
                            </label>
                        </li>
                        <li class="p-1" style="list-style: none; display: <?= $MultiCompany; ?>">
                            <select name="company_id" id="company_id" class="form-control form-control-sm" title="Company" onchange="ChangeCompany(this.value);">
                                <?php
                                    $Company = $CompanyQuery->result();
                                    if (!empty($Company)) { foreach ($Company as $key => $cdata) { 
                                ?>
                                <option <?= $this->session->userdata('comp_id') == $cdata->id ? "selected" : ""; ?> value="<?= $cdata->id; ?>, Company"><?= $cdata->company_name; ?></option>
                                <?php } }
                                    $AssignCompany = $this->db->select('comp_id')->where('assign_user', $this->session->userdata('user_id'))->get('company_assign')->result();
                                    if (!empty($AssignCompany)) { foreach ($AssignCompany as $key => $acdata) {
                                        $AssignCompInfo = $this->db->select('id, company_name')->where('id', $acdata->comp_id)->get('inventory_billing')->row();
                                ?>
                                <option <?= $this->session->userdata('comp_id') == $AssignCompInfo->id ? "selected" : ""; ?> value="<?= $AssignCompInfo->id; ?>, AssignCompany"><?= $AssignCompInfo->company_name; ?></option>
                                <?php } } ?>
                            </select>
                        </li>
                        <?php if ($this->session->userdata('roles') != 1) { ?>
                        <li class="p-1 hidden" style="list-style: none;">
                            <?php 
                                $expiry = get_title('users', ['id' => $this->session->userdata('user_id')], 'expiry');
                                if (DifferenceFromDates($expiry) <= 7 && strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($expiry)))) {
                            ?>
                            <a class="text-white blink" title="License Expire in <?= DifferenceFromDates($expiry); ?> Days">
                                Expiry : <?= DifferenceFromDates($expiry); ?> Days
                            </a>
                            <?php } elseif (strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($expiry)))) { ?>
                            <a class="text-white" title="License Expire in <?= DifferenceFromDates($expiry); ?> Days">
                                Expiry : <?= DifferenceFromDates($expiry); ?> Days
                            </a>
                            <?php } else { ?>
                            <a class="text-white blink" title="License Expire -<?= DifferenceFromDates($expiry); ?> Days">
                                Expiry : -<?= DifferenceFromDates($expiry); ?> Days
                            </a>    
                            <?php } ?>
                        </li>
                        <?php } ?>
                        <li style="list-style: none; padding-right: 2px;">
                            <?php 
                                $PaymentRequestWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 0];
                                if ($this->session->userdata('BranchRole')) {
                                    $PaymentRequestWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $PaymentRequestQuery = $this->db->select('date')->where($PaymentRequestWhere)->order_by('date', 'ASC')->get('paymentrequest');
                                $PaymentRequestFrom = ($PaymentRequestQuery->num_rows() && $PaymentRequestQuery->row()) ? $PaymentRequestQuery->row()->date : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Reports/PaymentRequestReport?Salesman=All&status=0&from=<?= $PaymentRequestFrom; ?>&to=<?= date('Y-m-d'); ?>" class="text-white button" title="Pending Payment Request">
                                <i class="fa fa-bell text-white <?= $PaymentRequestQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $PaymentRequestQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <li style="list-style: none; padding-right: 2px;">
                            <?php 
                                $OrderbookWhere = ['IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'InvoiceStatus' => 1, 'FinancialYear' => $this->session->userdata('financial_year')];
                                if ($this->session->userdata('BranchRole')) {
                                    $OrderbookWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $OrderbookQuery = $this->db->select('OrderDate')->where($OrderbookWhere)->order_by('OrderDate', 'ASC')->get('orderbook');
                                $OrderbookFrom = ($OrderbookQuery->num_rows() && $OrderbookQuery->row()) ? $OrderbookQuery->row()->OrderDate : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Orderform?Status=1&From=<?= $OrderbookFrom; ?>&To=<?= date('Y-m-d'); ?>" class="text-white button" title="Pending Orderbook">
                                <i class="fa fa-shopping-bag text-white <?= $OrderbookQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $OrderbookQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <li style="list-style: none; padding-right: 2px;">
                            <?php
                                $DeliveryWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year'), 'status' => 1, 'delivery_status' => 0];
                                if ($this->session->userdata('BranchRole')) {
                                    $DeliveryWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $SaleInvoiceFrom = $this->db->select('id, invoiceDate')->where($DeliveryWhere)->get_compiled_select('sales');
                                $EstimateInvoiceFrom = $this->db->select('id, invoiceDate')->where($DeliveryWhere)->get_compiled_select('estimates');
                                $SalesEstimatesFromQuery = '(' . $SaleInvoiceFrom . ' UNION ' . $EstimateInvoiceFrom . ') AS SalesEstimatesFrom';
                                $this->db->reset_query();
                                $this->db->select('invoiceDate');
                                $this->db->order_by('invoiceDate', 'ASC');
                                $InvoiceFrom = $this->db->get($SalesEstimatesFromQuery)->row();
                                $SaleEstimateDeliveryNumRows = ($this->db->select('id')->where($DeliveryWhere)->get('sales')->num_rows() + $this->db->select('id')->where($DeliveryWhere)->get('estimates')->num_rows());
                            ?>
                            <a href="<?= base_url(); ?>Reports/SaleDeliveryReport?estimate=true&PartyId=All&SalesmanId=All&fromDate=<?= $InvoiceFrom->invoiceDate; ?>&toDate=<?= date('Y-m-d'); ?>&Delivery=0&Payment=All" class="text-white button" title="Pending Delivery">
                                <i class="fa fa-bus text-white <?= $SaleEstimateDeliveryNumRows ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $SaleEstimateDeliveryNumRows; ?>
                                </span>
                            </a>
                        </li>
                        <?php if ($this->session->userdata('BranchRole')) { ?>
                        <li style="list-style: none; padding-right: 2px;">
                            <?php
                                $branchInfo = $this->db->select('UserId, branchDivision, mainBranchId')->where(['Id' => $this->session->userdata('CompBranchId'), 'CompId' => $this->session->userdata('comp_id'), 'IsDeleted' => 1])->get('companybranch')->row();
                                $purchaseRequestWhere = ['status' => 1, 'PurchaseRequest' => 1, 'financial_year' => $this->session->userdata('financial_year')];
                                $partyWhere = ['comp_id' => $this->session->userdata('comp_id'),  'status' => 1];
                                
                                if ($branchInfo->branchDivision == 'Y') {
                                        $mainBranchInfo = $this->db->select('Id, BranchName, BranchUserId')->where(['Id' => $branchInfo->mainBranchId, 'CompId' => $this->session->userdata('comp_id'), 'IsDeleted' => 1])->get('companybranch')->row();                                        
                                        $partyWhere['user_id'] = $mainBranchInfo->BranchUserId;
                                        $partyWhere['CompanyBranchId'] = $mainBranchInfo->Id;
                                        $partyWhere['subBranchId'] = $this->session->userdata('CompBranchId');
                                        $purchaseRequestWhere['user_id'] = $mainBranchInfo->BranchUserId;
                                        $purchaseRequestWhere['CompanyBranchId'] = $mainBranchInfo->Id;
                                } elseif ($branchInfo->branchDivision == 'N') {
                                        $partyWhere['user_id'] = $branchInfo->UserId;
                                        $partyWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                        $purchaseRequestWhere['user_id'] = $branchInfo->UserId;
                                }
                                $customerId = get_title('parties', $partyWhere, 'id');
                                $purchaseRequestWhere['customerId'] = $customerId;
                                
                                $purchaseRequestQuery = $this->db->select('invoiceDate')->where($purchaseRequestWhere)->order_by('invoiceDate', 'ASC')->get('sales');
                                $PurchaseRequestFrom = ($purchaseRequestQuery->num_rows() && $purchaseRequestQuery->row()) ? $purchaseRequestQuery->row()->invoiceDate : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Reports/PurchaseRequestReport?From=<?= $PurchaseRequestFrom; ?>&To=<?= date('Y-m-d'); ?>&RequestStatus=1" class="text-white button" title="Pending Purchase Request">
                                <i class="fa fa-shopping-cart text-white <?= $purchaseRequestQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $purchaseRequestQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <?php } ?>
                        <li style="list-style: none; padding-right: 2px;">
                            <?php
                                $LeaveWhere = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Status' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                    $LeaveWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $LeaveQuery = $this->db->select('Date')->where($LeaveWhere)->order_by('Date', 'ASC')->get('leave_request');
                                $LeaveFrom = ($LeaveQuery->num_rows() && $LeaveQuery->row()) ? $LeaveQuery->row()->Date : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Reports/LeaveRequestReport?From=<?= $LeaveFrom; ?>&To=<?= date('Y-m-d'); ?>&Status=1" class="text-white button" title="Pending Leave Request">
                                <i class="fa fa-user-tie text-white <?= $LeaveQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $LeaveQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <li style="list-style: none; padding-right: 2px;">
                            <?php
                                $JobWhere = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Status' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                    $JobWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $JobQuery = $this->db->select('Date')->where($JobWhere)->order_by('Date', 'ASC')->get('carrer_apply');
                                $JobFrom = ($JobQuery->num_rows() && $JobQuery->row()) ? $JobQuery->row()->Date : date('Y-m-d');
                            ?>
                            <a href="<?= base_url(); ?>Vacancies/JobApplicationReport?from=<?= $JobFrom; ?>&to=<?= date('Y-m-d'); ?>&status=1" class="text-white button" title="Pending Job Application">
                                <i class="fa fa-user-secret text-white <?= $JobQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $JobQuery->num_rows(); ?>
                                </span>
                            </a>
                        </li>
                        <li style="list-style: none; padding-right: 2px;">
                            <?php
                                $saleInoviceEditHistoryWhere = ['userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id')];
                                if ($this->session->userdata('BranchRole')) {
                                    $saleInoviceEditHistoryWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                                $saleInoviceEditHistoryQuery = $this->db->select('date')->where($saleInoviceEditHistoryWhere)->group_by('invoiceId')->get('sales_edit_history');
                            ?>
                            <a href="<?= base_url(); ?>reports/saleInoviceEditHistory" class="text-white button" title="Sale Invoice Edit History">
                                <i class="fa fa-edit text-white <?= $saleInoviceEditHistoryQuery->num_rows() ? 'notification' : ''; ?>" style="font-size: 20px;"></i>
                                <span class="notification_badge">
                                    <?= $saleInoviceEditHistoryQuery->num_rows(); ?>
                                </span>
                            </a>                            
                        </li>                        
                        <li class="p-1" style="list-style: none;">
                            <select name="year" id="year" class="form-control form-control-sm year" onchange="ChangeFinancialYear(this.value);" style="width: 100px;">
                                <?php $YearQuery = $this->db->select('id, year')->order_by('year', 'DESC')->get('year'); if ($YearQuery->num_rows()) { foreach ($YearQuery->result() as $key => $ydata) { $expstr = explode("-", $ydata->year);  ?>
                                <option <?= $this->session->userdata('financial_year') == $ydata->id ? "selected" : ""; ?> value="<?= $ydata->id; ?>"><?= $expstr[0] . '-20' . $expstr[1]; ?></option>
                                <?php } } ?>
                            </select>
                        </li>
                    </ul>
                    <?php
                        $ImageName = get_title('users', ['id' => $this->session->userdata('user_id')], 'image');
                        $ImageFile = 'uploads/profile/' . $ImageName;
                    ?>
                    <div class="btn-group user-right align-items-end dropdown-menu-container">
                        <div class="btn dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="usericon">
                                <?php if (file_exists($ImageFile) && !empty($ImageName)) { ?>
                                <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                <?php } else { ?>
                                <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                                <?php } ?>
                            </span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-hover">
                            <li>
                                <a href="<?= base_url(); ?>profile" class="dropdown-item">
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>signin/logout" class="dropdown-item">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- navbar menu -->
                <div class="collapse navbar-collapse menu" id="menu">
                    <ul class="nav-list navbar-nav me-auto mb-2 mb-lg-0 menu__inner">
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Masters
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Parties</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>party">
                                                <i class="fa fa-angle-right"></i> Party
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>party/category">
                                                <i class="fa fa-angle-right"></i> Party Category
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Products</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Product">
                                                <i class="fa fa-angle-right"></i> Product
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>RawMaterial">
                                                <i class="fa fa-angle-right"></i> Raw Material
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Unit">
                                                <i class="fa fa-angle-right"></i> Unit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Tax">
                                                <i class="fa fa-angle-right"></i> Tax
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Productcode">
                                                <i class="fa fa-angle-right"></i> Product Code
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title"><br /></h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>SubUnit">
                                                <i class="fa fa-angle-right"></i> Sub Unit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>product/category">
                                                <i class="fa fa-angle-right"></i> Product Category
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>product/brand">
                                                <i class="fa fa-angle-right"></i> Brand
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>product/hsnSac">
                                                <i class="fa fa-angle-right"></i> Hsn/Sac
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Billing</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Billing_format">
                                                <i class="fa fa-angle-right"></i> Billing Format
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Seriestitle">
                                                <i class="fa fa-angle-right"></i> Series Title
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Finencial_year">
                                                <i class="fa fa-angle-right"></i> Financial Year
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Series">
                                                <i class="fa fa-angle-right"></i> Billing Series
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Accounting_group">
                                                <i class="fa fa-angle-right"></i> Accounting Group
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Sub_group">
                                                <i class="fa fa-angle-right"></i> A/C Sub Group
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Ladger">
                                                <i class="fa fa-angle-right"></i> Ledger
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Warehouse">
                                                <i class="fa fa-angle-right"></i> Warehouse
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Paymentmode">
                                                <i class="fa fa-angle-right"></i> Payment Mode
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Vouchers
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text" style="max-width: 1300px;">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Sales</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Sale">
                                                <i class="fa fa-angle-right"></i> Sale
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Estimate">
                                                <i class="fa fa-angle-right"></i> Estimate
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Orderform/Create">
                                                <i class="fa fa-angle-right"></i> Orderbook
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>gadget">
                                                <i class="fa fa-angle-right"></i> Gadget
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>proforma">
                                                <i class="fa fa-angle-right"></i> Proforma
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Credit_note">
                                                <i class="fa fa-angle-right"></i> Credit Note
                                            </a>
                                        </li>                                        
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Purchases</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Purchase">
                                                <i class="fa fa-angle-right"></i> Purchase
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Estimate_purchase">
                                                <i class="fa fa-angle-right"></i> Estimate Purchase
                                            </a>
                                        </li>
                                        <?php if ($this->session->userdata('BranchRole')) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>PurchaseOrder/create">
                                                <i class="fa fa-angle-right"></i> Purchase Order
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Debit_note">
                                                <i class="fa fa-angle-right"></i> Debit Note
                                            </a>
                                        </li>                                        
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Accounts</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>receipt">
                                                <i class="fa fa-angle-right"></i> Receipt
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>payment">
                                                <i class="fa fa-angle-right"></i> Payment
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>journal">
                                                <i class="fa fa-angle-right"></i> Journal
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>contra">
                                                <i class="fa fa-angle-right"></i> Contra
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>StockManufacturer">
                                                <i class="fa fa-angle-right"></i> Stock Manufacturer
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>vestige">
                                                <i class="fa fa-angle-right"></i> Vestige
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Warehouse/stock_transfer">
                                                <i class="fa fa-angle-right"></i> Stock Transfer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Excel Invoice</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Sale/ExcelDataToSaleInvoice">
                                                <i class="fa fa-angle-right"></i> Excel to Sale Invoice
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Purchase/ExcelDataToPurchaseInvoice">
                                                <i class="fa fa-angle-right"></i> Excel to Purchase Invoice
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Service
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Master</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>services/training">
                                                <i class="fa fa-angle-right"></i> Training
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Voucher</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>services/installationCreate">
                                                <i class="fa fa-angle-right"></i> Installation
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>services/serviceCallCreate">
                                                <i class="fa fa-angle-right"></i> Service Call
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Report</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>services/installation">
                                                <i class="fa fa-angle-right"></i> Installation Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>services/serviceCall">
                                                <i class="fa fa-angle-right"></i> Service Call Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">

                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Reports
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text" style="max-width: 1500px;">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Sale</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Sale/sale_report">
                                                <i class="fa fa-angle-right"></i> Sale Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Estimate/estimate_report">
                                                <i class="fa fa-angle-right"></i> Estimate Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Orderform">
                                                <i class="fa fa-angle-right"></i> Orderbook Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Sale/SalesInovices">
                                                <i class="fa fa-angle-right"></i> All Sales Invoices Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/SaleDeliveryReport">
                                                <i class="fa fa-angle-right"></i> Sale Delivery Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Sale/party_sale_report">
                                                <i class="fa fa-angle-right"></i> Parties Wise Sales Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/saleInvoicesProductsReport">
                                                <i class="fa fa-angle-right"></i> Sale Invoice Product report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>proforma/report">
                                                <i class="fa fa-angle-right"></i> Proforma Invoices report
                                            </a>
                                        </li>
                                          <li>
                                            <a href="<?= base_url(); ?>Einvoice/ewayBillReport">
                                                <i class="fa fa-angle-right"></i> Eway-Bill Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Einvoice/eInvoiceReport">
                                                <i class="fa fa-angle-right"></i> E-Invoice Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title"><br /></h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>gadget/report">
                                                <i class="fa fa-angle-right"></i> Gadget Voucher Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/SalesmanWiseDueReport">
                                                <i class="fa fa-angle-right"></i> Salesman Wise Due Sale
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/SalesmanWiseDueEstimateReport">
                                                <i class="fa fa-angle-right"></i> Salesman Wise Due Estimate
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/SalesmanWiseSaleReport">
                                                <i class="fa fa-angle-right"></i> Salesman Wise Sales
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/SalesmanWiseEstimateReport">
                                                <i class="fa fa-angle-right"></i> Salesman Wise Estimate
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/OldSaleBySalesmanReport">
                                                <i class="fa fa-angle-right"></i> Old Sale By Salesman Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Credit_note/report">
                                                <i class="fa fa-angle-right"></i> Credit Note Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/partiesWiseProductsSalesReport">
                                                <i class="fa fa-angle-right"></i> Party Wise Product Sale Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/gstSalesReport">
                                                <i class="fa fa-angle-right"></i> GST Sales Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Purchase</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Purchase/purchase_report">
                                                <i class="fa fa-angle-right"></i> Purchase Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Estimate_purchase/report">
                                                <i class="fa fa-angle-right"></i> Estimate Purchase Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>PurchaseOrder">
                                                <i class="fa fa-angle-right"></i> Purchase Order Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/PurchaseRequestReport">
                                                <i class="fa fa-angle-right"></i> Purchase Request Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/DebitNoteRequestReport">
                                                <i class="fa fa-angle-right"></i> Debit Note Request Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/purchaseInvoicesProductsReport">
                                                <i class="fa fa-angle-right"></i> Purchase Invoice Product Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/partyPurchaseReport">
                                                <i class="fa fa-angle-right"></i> Parties Wise Purchase Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Debit_note/report">
                                                <i class="fa fa-angle-right"></i> Debit Note Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Accounts</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/DayBookReport">
                                                <i class="fa fa-angle-right"></i> Day Book Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/PaymentRequestReport">
                                                <i class="fa fa-angle-right"></i> Payment Request Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/AccountSummary">
                                                <i class="fa fa-angle-right"></i> Account Summary
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/PaymentDiscountReport">
                                                <i class="fa fa-angle-right"></i> Payment Discount Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/partiesOutstanding">
                                                <i class="fa fa-angle-right"></i> Party Collection Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>payment/statement">
                                                <i class="fa fa-angle-right"></i> Ledger Account Statement
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Stocks_report">
                                                <i class="fa fa-angle-right"></i> Stock Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Gst_report">
                                                <i class="fa fa-angle-right"></i> GST Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/profitAndLossReport">
                                                <i class="fa fa-angle-right"></i> Profit & Loss Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/accountingBalanceSheet">
                                                <i class="fa fa-angle-right"></i> Accounting Balance Sheet
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>stocks_report/stockSummary">
                                                <i class="fa fa-angle-right"></i> Stock Summary
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>receipt/report">
                                                <i class="fa fa-angle-right"></i> Receipts Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>                                
                                <div class="submenu__inner" style="width: 20%;">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>payment/PartialUnpaidInvoices">
                                                <i class="fa fa-angle-right"></i> Partial & Unpaid Invoices
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/purchaseVsSaleProductsReport">
                                                <i class="fa fa-angle-right"></i> Purchase Vs Sale Products Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>StockManufacturer/Reports">
                                                <i class="fa fa-angle-right"></i> Stock Manufacturer Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Warehouse/report">
                                                <i class="fa fa-angle-right"></i> Stock Transfer Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/LeaveRequestReport">
                                                <i class="fa fa-angle-right"></i> Leave Request Report
                                            </a>
                                        </li>                                        
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/employeeSalesReport">
                                                <i class="fa fa-angle-right"></i> Employee Sales Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/employeeTravelsReport">
                                                <i class="fa fa-angle-right"></i> Employee Travels Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/employeeOutstationTravelsReport" style="font-size: 14.5px;">
                                                <i class="fa fa-angle-right"></i> Employee Outstation Travel Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/customersReport">
                                                <i class="fa fa-angle-right"></i> Customers Report
                                            </a>
                                        </li>
                                        <?php if (empty($this->session->userdata('BranchRole'))) { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>reports/vendorWisePurchaseSaleReport" style="font-size: 14.5px;">
                                                <i class="fa fa-angle-right"></i> Vendor Wise Purchase Sale Report
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Cancelled Invoices
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__normal" style="max-width: 300px;">
                                <i class="fa fa-remove submenu__close"></i>
                                <ul class="submenu__list">
                                    <li>
                                        <a href="<?= base_url(); ?>Sale_recycle">
                                            <i class="fa fa-angle-right"></i> Sale Cancel Invoice
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>Purchase_recycle">
                                            <i class="fa fa-angle-right"></i> Purchase Cancel Invoice
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>Recycle/Estimate">
                                            <i class="fa fa-angle-right"></i> Estimate Cancel Invoice
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>Recycle/EstimatePurchase">
                                            <i class="fa fa-angle-right"></i> Estimate Purchase Cancel Invoice
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Employees
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__text">
                                <i class="fa fa-remove submenu__close"></i>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Master</h4>
                                    <ul class="submenu__list">
                                        <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'full_name') == "Admin") { ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Position">
                                                <i class="fa fa-angle-right"></i> Position
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Configuration">
                                                <i class="fa fa-angle-right"></i> Configuration
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Holiday">
                                                <i class="fa fa-angle-right"></i> Holiday
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Create</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Create">
                                                <i class="fa fa-angle-right"></i> Employee
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Attendance">
                                                <i class="fa fa-angle-right"></i> Attendance
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/Salary">
                                                <i class="fa fa-angle-right"></i> Salary
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Report</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Employee">
                                                <i class="fa fa-angle-right"></i> Employee Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/AttendanceReport">
                                                <i class="fa fa-angle-right"></i> Attendance Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url(); ?>Employee/SalaryReport">
                                                <i class="fa fa-angle-right"></i> Salary Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="submenu__inner">
                                    <h4 class="submenu__title">Other</h4>
                                    <ul class="submenu__list">
                                        <li>
                                            <a href="<?= base_url(); ?>Reports/EmployeeTracking">
                                                <i class="fa fa-angle-right"></i> Employee Tracking
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="menu__item">
                            <a href="<?= base_url(); ?>Comp_profile" class="menu__link">
                                <?= $this->session->userdata('BranchRole') ? "Branch" : "Company"; ?> Profile
                            </a>
                        </li>
                        <li class="menu__item">
                            <a href="<?= base_url(); ?>Vacancies" class="menu__link">
                                Vacancies
                            </a>
                        </li>
                        <?php if ($this->session->userdata('roles') == 1) { ?>
                        <li class="menu__item menu__dropdown">
                            <a href="#" class="menu__link">
                                Electricians
                                <i class="fa fa-chevron-right"></i>
                            </a>
                            <div class="submenu megamenu__normal2">
                                <i class="fa fa-remove submenu__close"></i>
                                <ul class="submenu__list">
                                    <li>
                                        <a href="<?= base_url(); ?>electrician/rewardPointCost">
                                            <i class="fa fa-angle-right"></i> Reward Point Cost
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>electrician">
                                            <i class="fa fa-angle-right"></i> Electricians Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>Reports/withdrawalRequestReport">
                                            <i class="fa fa-angle-right"></i> Withdrawal Request Report
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- header -->
</div>

<?php
    if ($this->session->userdata('BranchRole')) {
        $business_details = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'CompId' => $this->session->userdata('comp_id'), 'BranchUserId' => $this->session->userdata('user_id')]);
        $business_details->state = $business_details->State;
    } else {
        $business_details = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')]);    
    }
    $business_details->state_details = $this->db->where('id', $business_details->state)->get('state_list')->row();
    $city_details=$this->db->where('id', $business_details->city)->get('districts')->row();
    $business_details->city_name=""; 
    if(!empty($city_details)){
    $business_details->city_name=$city_details->name;
    }

?>
<script type="text/javascript">
    let BUSINESS_DETAILS = <?= json_encode($business_details); ?>; 
    let GST_ACCESS_TOKEN = "<?= GST_ACCESS_TOKEN ?>"; 
    let GST_ACCESS_TOKEN_LIVE="<?= GST_ACCESS_TOKEN_LIVE ?>"; 
</script>