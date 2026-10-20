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
    .user-prof {
        height: 100%;
        background: #ffffff;
        padding: 10px 20px;
        border-radius: 4px;
        box-shadow: 0 0 10px rgb(0 0 0 / 23%);
    }
    .headuser-txt p {
        font-size:30px;
        margin-bottom: 2px;
        color: #000;
        font-weight: 400;
    }
    span.headuser-icon {
        width: 70px;
        height: 80px;
        background: #d7d9d9;
        text-align: center;
        border-radius: 4px;
        margin-right: 15px;
        line-height: 70px;
        overflow:hidden;
    }
    .headuser-icon img {
        width:auto;
        height:100%;
        margin-right:0; 
        border-radius:0;
    }
    .headuser-txt h6, .headuser-txt span {
        color: #000
    }
    .dashborad-boxes h5, .dashborad-boxes .card-title {
        font-size: 18px;
        text-transform: uppercase;
        font-weight: 500;
    }
    .dashborad-boxes .card {
        height: 100%;
    }
    .vendorsChart {
        height: 400px;
        overflow: hidden;
        position: relative;
    }
</style>

<div class="profile-boxes">    
    <div class="topsections py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>                    
                </div>
                <?php if ($this->session->userdata('roles') != 1) { if (DifferenceFromDates($expiry) <= 7 && strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($expiry)))) { ?>
                <div class="col-md-12">
                    <marquee onmouseover="this.stop();" onmouseout="this.start();" class="pb-4 blink">
                        <span class="text-danger">Your license has expired in <?= DifferenceFromDates($expiry); ?> days , Please contact your administrator.</span>
                    </marquee>
                </div>
                <?php } elseif (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($expiry)))) { ?>
                <div class="col-md-12">
                    <marquee onmouseover="this.stop();" onmouseout="this.start();" class="pb-4 blink">
                        <span class="text-danger">Your license has expired in -<?= DifferenceFromDates($expiry); ?> days , Please contact your administrator.</span>
                    </marquee>
                </div>
                <?php } } ?>
                <div class="col-lg-4">
                    <div class="d-flex user-prof align-items-center">
                        <span class="headuser-icon">
                            <?php if (file_exists($ImageFile) && !empty($ImageName)) { ?>
                            <img src="<?= base_url() . $ImageFile; ?>" alt="user-profile" />
                            <?php } else { ?>
                            <img src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile" />
                            <?php } ?>
                        </span>
                        <div class="headuser-txt">
                            <p>Welcome Back</p>
                            <h6 class="mb-0" style="font-size: 18px">
                                <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'name'); ?>
                                <?php if ($this->session->userdata('BranchRole')) { ?>
                                <small title="Branch Code">
                                    (<?= get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')], 'BranchCode'); ?>)
                                </small>
                                <?php } ?>
                            </h6>
                            <span style="font-size: 14px;color: #666;">
                                <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'email'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <!-- small box -->
                            <div class="small-box bg-gray mb-0 mt-2 mt-md-0">
                                <div class="inner">
                                    <h3>
                                        <?php
                                            if ($this->session->userdata('BranchRole')) {
                                                echo $this->db->select('id')->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'CompanyBranchId' => $this->session->userdata('CompBranchId'), 'status' => 1])->get('sales')->num_rows();
                                            } else {
                                                echo $this->db->select('id')->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1])->get('sales')->num_rows(); 
                                            }
                                        ?>
                                    </h3>
                                    <p>Invoice</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file-text" style="opacity: 0.20;"></i>
                                </div>
                                <a href="<?= base_url(); ?>invent/sale" class="small-box-footer" title="Add, Edit Invoice">
                                    Create Sale <i class="fa fa-angle-double-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-6 col-lg-3">
                            <!-- small box -->
                            <div class="small-box bg-warning mb-0 mt-2 mt-md-0">
                                <div class="inner">
                                    <h3>
                                        <?php
                                            if ($this->session->userdata('BranchRole')) {
                                                echo $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'CompanyBranchId' => $this->session->userdata('CompBranchId'), 'status' => 1, 'payment_type' => 'Receipt'])->get('payment')->num_rows();
                                            } else {
                                                echo $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1, 'payment_type' => 'Receipt'])->get('payment')->num_rows();
                                            }
                                        ?>
                                    </h3>
                                    <p>Receipt</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-file" style="opacity: 0.20;"></i>
                                </div>
                                <a href="<?= base_url(); ?>receipt" class="small-box-footer" title="Create Receipt">
                                    Create Receipt <i class="fa fa-angle-double-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-6 col-lg-3">
                            <!-- small box -->
                            <div class="small-box bg-fuchsia mb-0 mt-2 mt-md-0">
                                <div class="inner">
                                    <h3>
                                        <?php
                                            if ($this->session->userdata('BranchRole')) {
                                                echo $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'CompanyBranchId' => $this->session->userdata('CompBranchId'), 'status' => 1])->get('parties')->num_rows();
                                            } else {
                                                echo $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1])->get('parties')->num_rows();
                                            }
                                        ?>
                                    </h3>
                                    <p>Customers</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users" style="opacity: 0.20;"></i>
                                </div>
                                <a href="<?= base_url(); ?>party" class="small-box-footer" title="Add, Edit Customers">
                                    Create Customer <i class="fa fa-angle-double-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-6 col-lg-3">
                            <!-- small box -->
                            <div class="small-box bg-success mb-0 mt-2 mt-md-0">
                                <div class="inner">
                                    <h3>
                                        <?php
                                            if ($this->session->userdata('BranchRole')) {
                                                echo $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'CompanyBranchId' => $this->session->userdata('CompBranchId'), 'status' => 1])->get('product')->num_rows();
                                            } else {
                                                echo $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1])->get('product')->num_rows();
                                            }
                                        ?>
                                    </h3>
                                    <p>Items</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-list" style="opacity: 0.20;"></i>
                                </div>
                                <a href="<?= base_url(); ?>product" class="small-box-footer" title="Add, Edit Items">
                                    Create Item <i class="fa fa-angle-double-right"></i>
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

<section class="py-3 dashborad-boxes">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow card-danger card-outline mt-3 mt-md-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="type" id="type" class="form-control form-control-sm" onchange="getTotalSaleAndPurchase();" style="width: 80px;">
                                    <option>All</option>
                                    <option>Sale</option>
                                    <option>Purchase</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <p class="pull-left font-weight-bold">
                                    Total Sale
                                    <br />
                                    <i class="fa fa-inr"></i> <span class="font-weight-bold" id="totalSales"></span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="pull-left font-weight-bold">
                                    Total Purchase
                                    <br />
                                    <i class="fa fa-inr"></i> <span class="font-weight-bold" id="totalPurchases"></span>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <div class="pull-right d-flex">
                                    <select name="year" id="fyear" class="form-control form-control-sm" onchange="getTotalSaleAndPurchase();" style="width: 100px;">
                                        <?php if ($this->db->select('id, year')->get('year')->result()) { foreach ($this->db->select('id, year')->order_by('year', 'DESC')->get('year')->result() as $key => $ydata) { $expstr = explode("-", $ydata->year);  ?>
                                        <option <?= $this->session->userdata('financial_year') == $ydata->id ? "selected" : ""; ?> value="<?= $ydata->id; ?>"><?= $expstr[0] . '-20' . $expstr[1]; ?></option>
                                        <?php } } ?>
                                    </select>
                                    &nbsp;
                                    <select name="month" id="month" class="form-control form-control-sm" onchange="getTotalSaleAndPurchase();" style="width: 50px;">
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
                    <div class="card-footer text-center">
                        <a href="<?= base_url(); ?>Reports/AccountSummary" class="btn btn-info btn-sm" title="View More">
                            View More
                        </a>
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
                                    <input type="text" id="keywords1" class="form-control form-control-sm" placeholder="Search" onkeyup="searchFilter1();" />
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
                                        if (!empty($PaymentInfo)) {
                                            $Balance = 0;
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
                                                <i class="fa fa-rupee"></i> <?= indianMoneyFormat(($pinfo['Total'] - $pinfo['PaidAmount'])); ?>
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
                                                <i class="fa fa-rupee"></i> 
                                                <?= indianMoneyFormat($Balance); ?>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?= $PaymentPaging; ?>
                                            &nbsp;&nbsp;
                                            <a href="<?= base_url(); ?>Reports/partiesOutstanding" class="btn btn-info btn-sm" title="View More">
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

<section class="pb-3 dashborad-boxes">
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
                                    <input type="text" id="keywords2" class="form-control form-control-sm" placeholder="Search" onkeyup="searchFilter2();" />
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
                                    <td><i class="fa fa-rupee"></i> <?= indianMoneyFormat($pdata['total']); ?></td>
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
                                        &nbsp;&nbsp;
                                        <a href="<?= base_url(); ?>sale/sale_report" class="btn btn-info btn-sm" title="View More">
                                            View More
                                        </a>
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
                                    <input type="text" id="keywords3" class="form-control form-control-sm" placeholder="Search" onkeyup="searchFilter3();" />
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
                                                    $ImageName = get_title('comp_profile', ['user_id' => $sdata['user_id']], 'logo');
                                                    $ImageFile = 'uploads/complogo/' . $ImageName;
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
                                        &nbsp;&nbsp;
                                        <a href="<?= base_url(); ?>reports/popularproducts" class="btn btn-info btn-sm" title="View More">
                                            View More
                                        </a>
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

<section class="pb-3 dashborad-boxes">
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
                                        if (isset($SalesTotalPaidBalance) && !empty($SalesTotalPaidBalance)) {
                                            $Total = 0;
                                            $Paid = 0;
                                            $Balance = 0;
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
                                            <a href="<?= base_url(); ?>Reports/SaleBalanceReport" title="Balance Report">
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

<section class="pb-3 dashborad-boxes">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow card-cyan card-outline mt-3 mt-md-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title">Top 10 Sales Customers</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reponsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Invoices</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $SalesAmount = 0;
                                        if (isset($Top10SalesCustomers) && !empty($Top10SalesCustomers)) {
                                            foreach ($Top10SalesCustomers as $key => $TopSales) {
                                                $SalesAmount += $TopSales->Total ?? 0;
                                                $PartyData = $this->db->select('name, contact')->where('id', $TopSales->customerId)->get('parties')->row();
                                                $TotalSaleInvoice = $this->db->select('id')->where(['customerId' => $TopSales->customerId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')])->get('sales')->num_rows();
                                                $TotalEstimateInvoice = $this->db->select('id')->where(['customerId' => $TopSales->customerId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')])->get('estimates')->num_rows();
                                    ?>
                                    <tr>
                                        <td>
                                            <?= isset($PartyData->name) ? $PartyData->name : ''; ?>
                                            <?= (!empty($PartyData) && !empty($PartyData->contact)) ? '<br /><small>' . $PartyData->contact . '</small>' : ''; ?>
                                        </td>
                                        <td><?= ($TotalSaleInvoice + $TotalEstimateInvoice); ?></td>
                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($TopSales->Total); ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align: right;">Total</th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($SalesAmount); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="<?= base_url(); ?>reports/topSalesCustomersReport" class="btn btn-sm bg-purple pull-right" title="Top Sales Customers Report">
                            View More
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow card-orange card-outline mt-3 mt-md-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title">Top 10 Purchases Customers</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reponsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Invoices</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $PurchasesAmount = 0;
                                        if (isset($Top10PurchasesCustomers) && !empty($Top10PurchasesCustomers)) {
                                            foreach ($Top10PurchasesCustomers as $key => $TopPurchases) {
                                                $PurchasesAmount += $TopPurchases->Total ?? 0;
                                                $PartyData = $this->db->select('name, contact')->where('id', $TopPurchases->customerId)->get('parties')->row();
                                                $TotalPurchaseInvoice = $this->db->select('id')->where(['customerId' => $TopPurchases->customerId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')])->get('purchases')->num_rows();
                                                $TotalEstimatePurchaseInvoice = $this->db->select('id')->where(['customerId' => $TopPurchases->customerId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')])->get('estimate_purchase')->num_rows();
                                    ?>
                                    <tr>
                                        <td>
                                            <?= isset($PartyData->name) ? $PartyData->name : ''; ?>
                                            <?= (!empty($PartyData) && !empty($PartyData->contact)) ? '<br /><small>' . $PartyData->contact . '</small>' : ''; ?>
                                        </td>
                                        <td><?= ($TotalPurchaseInvoice + $TotalEstimatePurchaseInvoice); ?></td>
                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($TopPurchases->Total); ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align: right;">Total</th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($PurchasesAmount); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (empty($this->session->userdata('BranchRole'))) { ?>
<section class="pb-3 dashborad-boxes">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow card-purple card-outline mt-3 mt-md-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="card-title">Vendors Purchase & Sale</h5>
                            </div>
                            <div class="col-md-1">
                                <select name="vendorType" id="vendorType" class="form-control form-control-sm" onchange="getVendorTotalPurchaseAndSale();" style="width: 80px;">
                                    <option>All</option>
                                    <option>Purchase</option>
                                    <option>Sale</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex">
                                    <select name="vendorYear" id="vendorYear" class="form-control form-control-sm" onchange="getVendorTotalPurchaseAndSale();" style="width: 100px;">
                                        <?php if ($this->db->select('id, year')->get('year')->result()) { foreach ($this->db->select('id, year')->order_by('year', 'DESC')->get('year')->result() as $key => $ydata) { $expstr = explode("-", $ydata->year);  ?>
                                        <option <?= $this->session->userdata('financial_year') == $ydata->id ? "selected" : ""; ?> value="<?= $ydata->id; ?>"><?= $expstr[0] . '-20' . $expstr[1]; ?></option>
                                        <?php } } ?>
                                    </select>
                                    &nbsp;
                                    <select name="vendorMonth" id="vendorMonth" class="form-control form-control-sm" onchange="getVendorTotalPurchaseAndSale();" style="width: 50px;">
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
                            <div class="col-md-3">
                                <p class="pull-left font-weight-bold">
                                    Total Purchase : <i class="fa fa-inr"></i> <span class="font-weight-bold" id="vendorTotalPurchases"></span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="pull-left font-weight-bold">
                                    Total Sale : <i class="fa fa-inr"></i> <span class="font-weight-bold" id="vendorTotalSales"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reponsive">
                            <div class="vendorsChart">
                                <canvas id="vendorsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="<?= base_url(); ?>reports/vendorWisePurchaseSaleReport" class="btn btn-sm bg-purple pull-right" title="Vendor Wise Purchase & Sale Report">
                            View More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<div class="modal fade" id="FinancialYearAlert" tabindex="-1" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Financial Year Alert</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p style="font-size: 20px;" class="text-danger">
                            Welcome to new financial year, 
                            <br /><br />
                            Kindly update financial year under <?= $this->session->userdata('BranchRole') ? "Branch" : "company"; ?> profile. also update billing series for new financial year.
                            <br /><br />
                            Thanks !!!
                            <br /><br />
                            <a href="<?= base_url(); ?>comp_profile" title="Click here to update financial year">
                                Click here to update financial year
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    
<div class="modal fade" id="ChequeAlert" tabindex="-1" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Cheque Alert</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="bg-success">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Customer Name</th>
                                        <th>Payment Date</th>
                                        <th>Amount</th>
                                        <th>Cheque Date</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($ChequeData) && !empty($ChequeData)) {
                                            $Sr = 0;
                                            $Amount = 0;
                                            foreach ($ChequeData as $key => $ChequeInfo) {
                                                $Sr++;
                                                $Amount += $ChequeInfo->debit;
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td><?= get_title('parties', ['id' => $ChequeInfo->party_id], 'name'); ?></td>
                                        <td><?= date('d-m-Y', strtotime($ChequeInfo->date)); ?></td>
                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($ChequeInfo->debit); ?></td>
                                        <td><?= $ChequeInfo->ChequeDate ? date('d-m-Y', strtotime($ChequeInfo->ChequeDate)) : "Not Available"; ?></td>
                                        <td><?= $ChequeInfo->remark; ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align: right;">Total</th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Amount); ?></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?= base_url(); ?>Payment" class="btn btn-primary btn-xs">More Info</a>
                <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        getTotalSaleAndPurchase();
        getAccountSummary();
        getVendorTotalPurchaseAndSale();        
    });    
    
    const saleAmountArray = [];
    const purchaseAmountArray = [];
    const daysArray = [];
    var chart = null;
    
    const getTotalSaleAndPurchase = () => {
        saleAmountArray.length = 0;
        purchaseAmountArray.length = 0;
        daysArray.length = 0;

        var type = $("#type").val();
        var year = $("#fyear").val();
        var month = $("#month").val();
        if (type != "" && year != "" && month != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>inventory_billing/saleAndPurchaseData",
                data    :   {type: type, year: year, month: month},
                success :   function(response) {
                                var responseJson = JSON.parse(response);
                                if (responseJson.status == true) {
                                    var inoviceJson = responseJson.invoiceData;                                       
                                    inoviceJson.forEach((element) => {
                                        if (element.type == "Sale") {
                                            saleAmountArray.push(element.totalAmount);
                                            purchaseAmountArray.push(0);
                                            daysArray.push(element.invoiceDate);
                                        } 
                                        if (element.type == "Purchase") {
                                            purchaseAmountArray.push(element.totalAmount);
                                            saleAmountArray.push(0);
                                            daysArray.push(element.invoiceDate);
                                        }
                                    });

                                    $('#totalSales').text(responseJson.totalSales);
                                    $('#totalPurchases').text(responseJson.totalPurchases);

                                    const ctx = document.getElementById('Chart').getContext("2d");

                                    if (chart) {
                                        chart.clear();
                                        chart.destroy();
                                    }

                                    chart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: daysArray,
                                            datasets: [
                                                {
                                                    label: 'Sale',
                                                    data: saleAmountArray,
                                                    borderWidth: 1
                                                },
                                                {
                                                    label: 'Purchase',
                                                    data: purchaseAmountArray,
                                                    borderWidth: 1
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,                                                
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    console.log("Empty");
                                }
                            }
            });
        } else {
            alert("Field can not be blank.");
        }
    };
    
    const getAccountSummary = () => {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>inventory_billing/getAccountSummary",
            data    :   {accountdata: true},
            success :   function(Response) {
                            var ResponseJson = JSON.parse(Response);
                            if (ResponseJson.status == true) {
                                var Credit = ResponseJson.CreditorsBalance.Credit;
                                var Debit = ResponseJson.DebtorsBalance.Debit;
                                var Cash = ResponseJson.CashBalance.Cash;

                                var xValues = [Cash + " Cash in Hand(Bank)", Credit + " Creditors", Debit + " Debtors"];
                                var yValues = [Cash, Credit, Debit];
                                var barColors = ["#01319d", "#f11313", "#6392fa"];

                                new Chart("weeklyStatistic", {
                                    type: "doughnut",
                                    data: {
                                        labels: xValues,
                                        datasets: [{
                                            backgroundColor: barColors,
                                            data: yValues
                                        }]
                                    },
                                    options: {
                                        title: {
                                            display: true,
                                            text: "Account's Summary"
                                        }
                                    }
                                });
                            }
                        }
        });
    };
    
    // vendor chart
    const vendorPurchaseAmountArray = [];
    const vendorSaleAmountArray = [];
    const vendorNameArray = [];
    let vendorChart = null;
    
    const getVendorTotalPurchaseAndSale = () => {
        vendorPurchaseAmountArray.length = 0;
        vendorSaleAmountArray.length = 0;
        vendorNameArray.length = 0;
        
        var voucherType = $("#vendorType").val();
        var fYear = $("#vendorYear").val();
        var month = $("#vendorMonth").val();
        if (voucherType != "" && fYear != "" && month != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>inventory_billing/getVendorTotalPurchaseAndSale",
                data    :   {type: voucherType, year: fYear, month: month},
                success :   function(response) {
                                var responseData = JSON.parse(response);
                                if (responseData.status == true) {
                                    var responseInoviceData = responseData.invoiceData;
                                    
                                    responseInoviceData.forEach((element) => {
                                        if (element.type === "Purchase") {
                                            vendorPurchaseAmountArray.push(element.totalAmount);
                                            vendorSaleAmountArray.push(0);
                                            vendorNameArray.push(element.branchName);
                                        }
                                        if (element.type === "Sale") {
                                            vendorPurchaseAmountArray.push(0);
                                            vendorSaleAmountArray.push(element.totalAmount);
                                            vendorNameArray.push(element.branchName);
                                        }
                                    });

                                    $('#vendorTotalPurchases').text(responseData.totalPurchases);
                                    $('#vendorTotalSales').text(responseData.totalSales);

                                    const vendorChartCtx = document.getElementById('vendorsChart').getContext("2d");

                                    if (vendorChart) {
                                        vendorChart.clear();
                                        vendorChart.destroy();
                                    }

                                    vendorChart = new Chart(vendorChartCtx, {
                                        type: 'bar',
                                        data: {
                                            labels: vendorNameArray,
                                            datasets: [
                                                {
                                                    label: 'Purchase',
                                                    data: vendorPurchaseAmountArray,
                                                    borderWidth: 1
                                                },
                                                {
                                                    label: 'Sale',
                                                    data: vendorSaleAmountArray,
                                                    borderWidth: 1
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,                                                    
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    console.log("Empty");
                                }
                    }
            });
        } else {
            alert("Field can not be blank.");
        }
    };
</script>