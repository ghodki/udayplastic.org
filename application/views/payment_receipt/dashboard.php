<main id="main" class="main">
    <div class="pagetitle">
         <h1>Payment Receipt Dashboard</h1> 
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>signin">Home</a></li>
                <li class="breadcrumb-item active">Payment Receipt Dashboard</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-4 col-md-6">
                <a href="<?= base_url(); ?>paymentReceipt/party">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Party</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $total_party; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div><!-- End Sales Card -->
            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
                <a href="<?= base_url(); ?>paymentReceipt/transaction">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Payment Receipt<span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa fa-rupee-sign"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $total_transaction; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div><!-- End Revenue Card -->
        </div><!-- End Left side columns -->
        <!-- </div> -->
    </section>
</main><!-- End #main -->