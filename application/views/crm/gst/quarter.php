<main id="main" class="main">
    <div class="pagetitle">
        <h1>GST Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>signin">Home</a></li>
                <li class="breadcrumb-item active">Regular Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-4 col-md-6">
                <a href="<?= getCrmUrl() ?>master/gstr1">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">GSTR1+IFS <span>| This Month</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $res = $this->user_model->getMonthWiseForSubServices('crm_gstr1');
                                    $total_gstr1 = count($res);
                                    ?>
                                    <h6><?= $total_gstr1 ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div><!-- End Sales Card -->

            <div class="col-xxl-4 col-md-6">
                <a href="<?= getCrmUrl() ?>master/gstr3b">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">GSTR 3B<span>| This Month</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $res = $this->user_model->getMonthWiseForSubServices('crm_gstr3b');
                                    $total_gstr3b = count($res);
                                    ?>
                                    <h6><?= $total_gstr3b ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div><!-- End Revenue Card -->

            <div class="col-xxl-4 col-xl-6">

                <a href="<?= getCrmUrl() ?>party/active_user">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Active User<span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $activeUser = $this->user_model->getPartyFromSubServiceCount();
                                    ?>
                                    <h6><?= $activeUser ?></h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div>

            <div class="col-xxl-4 col-xl-6">
                <a href="<?= getCrmUrl() ?>master/sale_entry">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Sale Booking Entry<span>| This Month</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $res = $this->user_model->getMonthWiseForSubServices('crm_sale');
                                    $total_sale = count($res);
                                    ?>
                                    <h6><?= $total_sale ?></h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div><!-- End Customers Card -->

            <div class="col-xxl-4 col-xl-6">

                <a href="<?= getCrmUrl() ?>master/purchase_entry">
                    <div class="card info-card customers-card">

                        <div class="card-body">
                            <h5 class="card-title">Purchase Booking Entry<span>| This Month</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $res = $this->user_model->getMonthWiseForSubServices('crm_purchase');
                                    $total_purchase = count($res);
                                    ?>
                                    <h6><?= $total_purchase ?></h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div><!-- End Customers Card -->

            <div class="col-xxl-4 col-xl-6">
                <a href="<?= getCrmUrl() ?>master/correction">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">CORRECTION</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $this->db->select('*');
                                    $this->db->from('crm_correction');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('crm_id', $crm_id);
                                    $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
                                    $this->db->where('status', 0);
                                    $res = $this->db->get()->result();
                                    $totalCorrection = count($res);
                                    ?>
                                    <h6><?= $totalCorrection ?></h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>
            </div>


            <div class="col-xxl-4 col-xl-6">
                <a href="<?= getCrmUrl() ?>master/bank_statement_entry">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">BANK STATEMENT ENTRY</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $res = $this->user_model->getSubServiceData('crm_bankstatement');
                                    $totalCorrection = count($res);
                                    ?>
                                    <h6><?= $totalCorrection ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div><!-- End Customers Card -->

        </div><!-- End Left side columns -->
    </section>

</main><!-- End #main -->