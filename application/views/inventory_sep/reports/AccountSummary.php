<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .card-body {
        padding: 0.5rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-primary">
                                    Account Summary
                                </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                                    <div class="card card-outline card-maroon">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h6 class="text-maroon">Purchase</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-navy">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) AS total')->where($Where)->get('purchases')->row('total')); ?>
                                                            </h3>
                                                            <p>Total Purchase</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-shopping-cart" style="opacity: 0.50;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/PurchaseReport" class="small-box-footer" title="Purchase Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-primary">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(paid_amount, 0)) AS paid_amount')->where($Where)->get('purchases')->row('paid_amount')); ?>
                                                            </h3>
                                                            <p>Total Payment</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-bank" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/PurchasePaymentReport" class="small-box-footer" title="Purchase Payment Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-maroon">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS Balance')->where($Where)->get('purchases')->row('Balance')); ?>
                                                            </h3>
                                                            <p>Total Balance</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-inr" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/PurchaseBalanceReport" class="small-box-footer" title="Purchase Balance Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                                    <div class="card card-outline card-warning">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h6 class="text-warning">Estimate Purchase</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-teal">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) AS total')->where($Where)->get('estimate_purchase')->row('total')); ?>
                                                            </h3>
                                                            <p>Total Estimate Purchase</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-shopping-cart" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/EstimatePurchaseReport" class="small-box-footer" title="Estimate Purchase Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-purple">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(paid_amount, 0)) AS paid_amount')->where($Where)->get('estimate_purchase')->row('paid_amount')); ?>
                                                            </h3>
                                                            <p>Total Payment</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-bank" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/EstimatePurchasePaymentReport" class="small-box-footer" title="Estimate Purchase Payment Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-olive">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS Balance')->where($Where)->get('estimate_purchase')->row('Balance')); ?>
                                                            </h3>
                                                            <p>Total Balance</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-inr" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/EstimatePurchaseBalanceReport" class="small-box-footer" title="Estimate Purchase Balance Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                                    <div class="card card-outline card-navy">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h6 class="text-navy">Sale</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-success">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) AS total')->where($Where)->get('sales')->row('total')); ?>
                                                            </h3>
                                                            <p>Total Sale</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-shopping-bag" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/SaleReport" class="small-box-footer" title="Sales Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-info">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(paid_amount, 0)) AS paid_amount')->where($Where)->get('sales')->row('paid_amount')); ?>
                                                            </h3>
                                                            <p>Total Received</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-bank" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/SaleReceivedReport" class="small-box-footer" title="Sales Received Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-danger">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS Balance')->where($Where)->get('sales')->row('Balance')); ?>
                                                            </h3>
                                                            <p>Total Balance</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-inr" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/SaleBalanceReport" class="small-box-footer" title="Sales Balance Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h6 class="text-info">Estimate</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-warning">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) AS total')->where($Where)->get('estimates')->row('total')); ?>
                                                            </h3>
                                                            <p>Total Estimate</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-shopping-bag" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/EstimateReport" class="small-box-footer" title="Estimate Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-dark">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(paid_amount, 0)) AS paid_amount')->where($Where)->get('estimates')->row('paid_amount')); ?>
                                                            </h3>
                                                            <p>Total Received</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-bank" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/EstimateReceivedReport" class="small-box-footer" title="Estimate Received Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 col-xxl-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-cyan">
                                                        <div class="inner">
                                                            <h3>
                                                                <?= indianMoneyFormat($this->db->select('SUM(IFNULL(total, 0)) - SUM(IFNULL(paid_amount, 0)) AS Balance')->where($Where)->get('estimates')->row('Balance')); ?>
                                                            </h3>
                                                            <p>Total Balance</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fa fa-inr" style="opacity: 0.20;"></i>
                                                        </div>
                                                        <a href="<?= base_url(); ?>Reports/EstimateBalanceReport" class="small-box-footer" title="Estimate Balance Report">
                                                            More Info <i class="fa fa-angle-double-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>