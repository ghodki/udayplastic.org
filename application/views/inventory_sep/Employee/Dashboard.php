<!DOCTYPE html>
<html lang="en-in" dir="ltr">
    <head>
        <?php $this->load->view('inventory_sep/Employee/Includes/Library'); ?>
        <style type="text/css">
            .pagination {
                display: block;
            }
            .pagination a {
                color: black;
                float: left;
                padding: .25rem .5rem;
                text-decoration: none;
                transition: background-color .3s;
                border: 1px solid #ddd;
                font-size: .875rem;
            }
            .pagination a.active {
                background-color: #4CAF50;
                color: white;
                border: 1px solid #4CAF50;
            }    
        </style>
    </head>
    <body>
        <?php
            if (empty($EmployeeInfo)) {
                $EmployeeInfo = (object) ['Name' => '', 'Email' => '', 'Photograph' => ''];
            }
        ?>
        <?php $this->load->view('inventory_sep/Employee/Includes/Header'); ?>
        
        <div class="gradiant-bg">
            <div class="topsections py-2">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 align-self-center">
                            <div class="d-flex align-items-center">
                                <span class="headuser-icon">
                                    <?php $ImageFile = 'uploads/Employee/Photograph/' . $EmployeeInfo->Photograph; if (file_exists($ImageFile) && !empty($EmployeeInfo->Photograph)) { ?>
                                    <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                    <?php } else { ?>
                                    <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                                    <?php } ?>
                                </span>
                                <div class="headuser-txt">
                                    <h6 class="mb-0" style="font-size: 16px">
                                        Welcome Back, <?= $EmployeeInfo->Name; ?>
                                    </h6>
                                    <span style="font-size: 15px;">
                                        <?= $EmployeeInfo->Email; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6 col-lg-3">
                                    <!-- small box -->
                                    <div class="small-box bg-purple">
                                        <div class="inner">
                                            <h3>
                                                <?php
                                                    $EmployeeId = $this->session->userdata('EmployeeId');
                                                    $UserId = $this->session->userdata('UserId');
                                                    $CompId = $this->session->userdata('CompId');
                                                    $CompBranchId = $this->session->userdata('CompBranchId');
                                                    
                                                    $EmployeeWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                                                    $OrderbookWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                                                    $EmployeeOrderbookWhere = [$EmployeeId];
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $OrderbookWhere['CompanyBranchId'] = $CompBranchId;
                                                        $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
                                                    }
                                                    if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                                        $EmployeeWhere['Id'] = $EmployeeId;
                                                        $EmployeeIds = json_decode(get_title('employees', $EmployeeWhere, 'WarehouseSalesman'), true) ?: [];
                                                        foreach ($EmployeeIds as $key => $Id) {
                                                            $EmployeeOrderbookWhere[] = $Id;
                                                        }
                                                    }
                                                    
                                                    echo $this->db->where($OrderbookWhere)->where_in('EmployeeId', $EmployeeOrderbookWhere)->get('orderbook')->num_rows(); 
                                                ?>
                                            </h3>
                                            <p>Orderbook</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-file-text" style="opacity: .20;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>Employee/Orderbook" class="small-box-footer" title="Create Orderbook">
                                            Orderbook Create <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-md-6 col-lg-3">
                                    <!-- small box -->
                                    <div class="small-box bg-gray">
                                        <div class="inner">
                                            <h3>
                                                <?php
                                                    $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                                    $EmployeeSaleWhere = [$EmployeeId];
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $Where['CompanyBranchId'] = $CompBranchId;
                                                        $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
                                                    }

                                                    if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                                        $EmployeeWhere['Id'] = $EmployeeId;
                                                        $EmployeeIds = json_decode(get_title('employees', $EmployeeWhere, 'WarehouseSalesman'), true) ?: [];
                                                        foreach ($EmployeeIds as $key => $Id) {
                                                            $EmployeeSaleWhere[] = $Id;
                                                        }
                                                    }
                                                    $TotalSalePaid = $this->db->select_sum('paid_amount')->where($Where)->where_in('SalesmanId', $EmployeeSaleWhere)->get('sales')->row('paid_amount');
                                                    $TotalEstimatePaid = $this->db->select_sum('paid_amount')->where($Where)->where_in('SalesmanId', $EmployeeSaleWhere)->get('estimates')->row('paid_amount');
                                                    echo number_format((float) ($TotalSalePaid ?? 0) + (float) ($TotalEstimatePaid ?? 0), 2);
                                                ?>
                                            </h3>
                                            <p>Parties Paid</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-file-text" style="opacity: .20;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>Employee/PartiesBalanceReport" class="small-box-footer" title="Parties Paid">
                                            More Info <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-md-6 col-lg-3">
                                    <!-- small box -->
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>
                                                <?php
                                                    $SaleBalance = $this->db->select('SUM(total) - SUM(COALESCE(paid_amount, 0)) AS Balance')->where($Where)->where_in('SalesmanId', $EmployeeSaleWhere)->get('sales')->row('Balance'); 
                                                    $EstimateBalance = $this->db->select('SUM(total) - SUM(COALESCE(paid_amount, 0)) AS Balance')->where($Where)->where_in('SalesmanId', $EmployeeSaleWhere)->get('estimates')->row('Balance'); 
                                                    echo number_format((float) ($SaleBalance ?? 0) + (float) ($EstimateBalance ?? 0), 2);
                                                ?>
                                            </h3>
                                            <p>Parties Balance</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-file" style="opacity: .20;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>Employee/PartiesBalanceReport" class="small-box-footer" title="Create Estimate">
                                            More info <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-md-6 col-lg-3">
                                    <!-- small box -->
                                    <div class="small-box bg-fuchsia">
                                        <div class="inner">
                                            <h3>
                                                <?= $this->db->where($Where)->where_in('SalesmanId', $EmployeeSaleWhere)->get('parties')->num_rows(); ?>
                                            </h3>
                                            <p>Customers</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>Employee/PartiesReport" class="small-box-footer" title="View Customers">
                                            More info <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <section class="py-3">
            <div class="container-fluid">
                <div class="row">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    <div class="col-lg-6">
                        <div class="card shadow card-danger card-outline mt-3 mt-md-0">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="type" id="type" class="form-control form-control-sm" onchange="GetTotalSaleAndEstimate();" style="width: 80px;">
                                            <option>All</option>
                                            <option>Sale</option>
                                            <option>Purchase</option>
                                            <option>Estimate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right d-flex">
                                            <select name="year" id="year" class="form-control form-control-sm" onchange="GetTotalSaleAndEstimate();" style="width: 100px;">
                                                <?php if ($this->db->select('id, year')->get('year')->result()) { foreach ($this->db->select('id, year')->get('year')->result() as $key => $ydata) { $expstr = explode("-", $ydata->year); ?>
                                                <option <?= $this->session->userdata('financial_year') == $ydata->id ? "selected" : ""; ?> value="<?= $ydata->id; ?>"><?= $expstr[0] . '-20' . $expstr[1]; ?></option>
                                                <?php } } ?>
                                            </select>
                                            &nbsp;
                                            <select name="month" id="month" class="form-control form-control-sm" onchange="GetTotalSaleAndEstimate();" style="width: 50px;">
                                                <option <?= date('m') == '01' ? "selected" : ""; ?> value="01">Jan</option>
                                                <option <?= date('m') == '02' ? "selected" : ""; ?> value="02">Feb</option>
                                                <option <?= date('m') == '03' ? "selected" : ""; ?> value="03">Mar</option>                            
                                                <option <?= date('m') == '04' ? "selected" : ""; ?> value="04">Apr</option>                            
                                                <option <?= date('m') == '05' ? "selected" : ""; ?> value="05">May</option>                            
                                                <option <?= date('m') == '06' ? "selected" : ""; ?> value="06">Jun</option>                            
                                                <option <?= date('m') == '07' ? "selected" : ""; ?> value="07">Jul</option>                            
                                                <option <?= date('m') == '08' ? "selected" : ""; ?> value="08">Aug</option>                            
                                                <option <?= date('m') == '09' ? "selected" : ""; ?> value="09">Sep</option>                            
                                                <option <?= date('m') == '10' ? "selected" : ""; ?> value="10">Oct</option>                            
                                                <option <?= date('m') == '11' ? "selected" : ""; ?> value="11">Nov</option>                            
                                                <option <?= date('m') == '12' ? "selected" : ""; ?> value="12">Dec</option>                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="Chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card shadow card-warning card-outline h-100 mt-3 mt-md-0">
                            <div class="card-header">
                                <h5>Account's Summary</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="weeklyStatistic" style="width: 100%; max-width: 600px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card shadow card-success card-outline h-100 mt-3 mt-md-0">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="card-title">Outstanding</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" id="keywords1" class="form-control form-control-sm" placeholder="Search" oninput="searchFilter1();" />
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" id="OutstatdingList">
                                    <table class="table">
                                        <tbody>
                                            <?php
                                                $Balance = 0;
                                                if (!empty($PaymentInfo)) {
                                                    foreach ($PaymentInfo as $key => $pinfo) {
                                                        $Balance += $pinfo['Total'] - $pinfo['PaidAmount'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="cust-name d-flex align-items-center">
                                                        <span class="custimg">
                                                            <?php
                                                                $ImageName = get_title('comp_profile', ['user_id' => $pinfo['user_id']], 'logo');
                                                                $ImageFile = 'uploads/complogo/' . $ImageName;
                                                                if (file_exists($ImageFile) && !empty($ImageName)) {
                                                            ?>
                                                            <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                                            <?php } else { ?>
                                                            <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                                                            <?php } ?>
                                                        </span>
                                                        <div class="cutname-txt">
                                                            <h6><?= get_title('parties', ['id' => $pinfo['customerId']], 'name'); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="ms-auto">
                                                        <i class="fa fa-rupee"></i> <?= $pinfo['Total'] - $pinfo['PaidAmount']; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } } else { ?>
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <span class="text-danger">No record found.</span>
                                                </td>
                                            </tr>    
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total</th>
                                                <th>
                                                    <div class="ms-auto">
                                                        <i class="fa fa-rupee"></i> <?= $Balance; ?>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?= $PaymentPaging; ?>
                                                    &nbsp;&nbsp;
                                                    <a href="<?= base_url(); ?>Employee/PartiesOutstanding" class="btn btn-info btn-sm" title="View More">
                                                        View More
                                                    </a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="pb-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow card-primary card-outline">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="card-title">Previous Invoice</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" id="keywords2" class="form-control form-control-sm" placeholder="Search" oninput="searchFilter2();" />
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-reponsive" id="PreviousSaleList">
                                    <table class="table">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Invoice Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        <?php if (!empty($PreviousSale)) { foreach ($PreviousSale as $key => $pdata) { ?>
                                        <tr>
                                            <td>
                                                <div class="cust-name d-flex align-items-center">
                                                    <span class="custimg">
                                                        <?php
                                                            $ImageName = get_title('comp_profile', ['user_id' => $pdata['user_id']], 'logo');
                                                            $ImageFile = 'uploads/complogo/' . $ImageName;
                                                            if (file_exists($ImageFile) && !empty($ImageName)) {
                                                        ?>
                                                        <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                                        <?php } else { ?>
                                                        <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                                                        <?php } ?>
                                                    </span>
                                                    <div class="cutname-txt">
                                                        <h6><?= get_title('parties', ['id' => $pdata['customerId']], 'name'); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?= date('d-m-Y', strtotime($pdata['invoiceDate'])); ?>
                                            </td>
                                            <td><i class="fa fa-rupee"></i> <?= $pdata['total']; ?></td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <span class="text-danger">No record found.</span>
                                            </td>
                                        </tr>    
                                        <?php } ?>
                                        <tr>
                                            <td colspan="3">
                                                <?= $SalePaging; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow card-info card-outline mt-3 mt-md-0">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="card-title">Popular Products</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" id="keywords3" class="form-control form-control-sm" placeholder="Search" oninput="searchFilter3();" />
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-reponsive" id="PopullerProductsList">
                                    <table class="table">
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                        </tr>
                                        <?php if (!empty($SallingProducts)) { foreach ($SallingProducts as $key => $sdata) { ?>
                                        <tr>
                                            <td>
                                                <div class="cust-name d-flex align-items-center">
                                                    <span class="custimg">
                                                        <?php
                                                            $ImageName = get_title('product', ['id' => $sdata['product_id']], 'image');
                                                            $ImageFile = 'uploads/product/' . $ImageName;
                                                            if (file_exists($ImageFile) && !empty($ImageName)) {
                                                        ?>
                                                        <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                                                        <?php } else { ?>
                                                        <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                                                        <?php } ?>
                                                    </span>
                                                    <div class="cutname-txt">
                                                        <h6><?= $sdata['productname']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $sdata['SaleQty']; ?></td>
                                            <td><?= get_title('product', ['id' => $sdata['product_id']], 'unit_name'); ?></td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <span class="text-danger">No record found.</span>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="3">
                                                <?= $ProductPaging; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="pb-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow card-danger card-outline mt-3 mt-md-0">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="card-title">Total Sales, Paid, Balance</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-reponsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Sales</th>
                                                <th>Paid</th>
                                                <th>Balance</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $Total = 0;
                                                $Paid = 0;
                                                $Balance = 0;
                                                if (isset($SalesTotalPaidBalance) && !empty($SalesTotalPaidBalance)) {
                                                    foreach ($SalesTotalPaidBalance as $key => $InfoData) {
                                                        $Total += $InfoData->total;
                                                        $Paid += $InfoData->paid_amount;
                                                        $Balance += ($InfoData->total - $InfoData->paid_amount);
                                            ?>
                                            <tr>
                                                <td><?= $InfoData->month_name; ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($InfoData->total); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($InfoData->paid_amount); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($InfoData->total - $InfoData->paid_amount)); ?></td>
                                                <td>
                                                    <a href="<?= base_url(); ?>Employee/PartiesBalanceReport" title="Balance Report">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total</th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Paid); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Balance); ?></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow card-navy card-outline mt-3 mt-md-0">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="card-title">Sales Delivery Assignment</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-reponsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Upcoming</th>
                                                <th>Ongoing</th>
                                                <th>Completed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($Assignments) && !empty($Assignments)) {
                                                    $Upcoming = [];
                                                    $Ongoing = [];
                                                    $Completed = [];
                                                    foreach ($Assignments as $key => $AssignData) {                                                
                                                        if ($AssignData->delivery_status == 1) {
                                                            $Completed[] = 'Yes';
                                                        }
                                                        if ($AssignData->delivery_date == date('Y-m-d')) {
                                                            $Ongoing[] = 'On';
                                                        }
                                                        if ($AssignData->delivery_status == 0) {
                                                            $Upcoming[] = 'No';
                                                        }
                                                    }
                                            ?>
                                            <tr>
                                                <td><?= count($Upcoming); ?></td>
                                                <td><?= count($Ongoing); ?></td>
                                                <td><?= count($Completed); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
            
        <?php $this->load->view('inventory_sep/Employee/Includes/Footer'); ?>
    </body>
</html>