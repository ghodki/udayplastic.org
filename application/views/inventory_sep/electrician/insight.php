<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-danger">
                                    <?= $electricianName; ?>
                                </h3>
                            </div>
                            <a href="<?= base_url(); ?>electrician" class="btn btn-danger btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <!-- small box -->
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>
                                                <?= $totalEarn; ?>
                                            </h3>
                                            <p>Total Earn</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-inr" style="opacity: 0.50;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>electrician/earningReport/<?= $userId; ?>" class="small-box-footer" title="Earn Report">
                                            More Info <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <!-- small box -->
                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3>
                                                <?= $totalBalance; ?>
                                            </h3>
                                            <p>Total Balance</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-bank" style="opacity: 0.20;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>electrician/balanceReport/<?= $userId; ?>" class="small-box-footer" title="Balance Report">
                                            More Info <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <!-- small box -->
                                    <div class="small-box bg-maroon">
                                        <div class="inner">
                                            <h3>
                                                <?= $totalWithdrawal; ?>
                                            </h3>
                                            <p>Total Withdrawal</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-money "style="opacity: 0.20;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>electrician/cashoutReport/<?= $userId; ?>" class="small-box-footer" title="withdrawal Report">
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