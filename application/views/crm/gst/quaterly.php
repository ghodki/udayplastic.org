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

            <!-- Left side columns -->
            <!-- <div class="col-lg-12"> -->

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
                <a href="<?= getCrmUrl() ?>master/gstr1">
                    <div class="card info-card sales-card">

                        <!-- <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div> -->

                        <div class="card-body">
                            <h5 class="card-title">GSTR1+IFS <span>| This Month</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $month=$this->session->userdata('months');
                                    $exp=explode('-',$month);
                                    $mont=$exp[0];
                                    $yer=$exp[1];
                                    $this->db->select('*');
                                    $this->db->from('crm_gstr1');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('crm_id', $crm_id);
                                    $this->db->where('month',$mont);
                                    $this->db->where('year',$yer);
                                    $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
                                    $res = $this->db->get()->result();
                                    $total_gstr1 = count($res);
                                    ?>
                                    <h6><?= $total_gstr1 ?></h6>
                                    <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
                <a href="<?= getCrmUrl() ?>master/gstr3b">
                    <div class="card info-card revenue-card">

                        <!-- <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div> -->

                        <div class="card-body">
                            <h5 class="card-title">GSTR 3B<span>| This Month</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="ps-3">

                                    <?php
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $this->db->select('*');
                                    $this->db->from('crm_gstr3b');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('crm_id', $crm_id);
                                    $this->db->where('month',$mont);
                                    $this->db->where('year',$yer);
                                    $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
                                    $res = $this->db->get()->result();
                                    $total_gstr3b = count($res);


                                    ?>
                                    <h6><?= $total_gstr3b ?></h6>
                                    <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

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
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $this->db->select('*');
                                    $this->db->from('crm_party');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('crm_id', $crm_id);
                                    $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
                                    $this->db->where('status', 1);
                                    $res = $this->db->get()->result();
                                    $activeUser = count($res);


                                    ?>
                                    <h6><?= $activeUser ?></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div>


           
            <!-- Customers Card -->
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
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $this->db->select('*');
                                    $this->db->from('crm_sale');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('crm_id', $crm_id);
                                    $this->db->where('month',$mont);
                                    $this->db->where('year',$yer);
                                    $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));

                                    $res = $this->db->get()->result();
                                    $total_sale = count($res);


                                    ?>
                                    <h6><?= $total_sale ?></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div><!-- End Customers Card -->


            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-6">

                <a href="<?= getCrmUrl() ?>master/purchase_entry">
                    <div class="card info-card customers-card">

                        <!-- <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <li class="dropdown-header text-start">
                <h6>Filter</h6>
            </li>

            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
            <li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
    </div> -->

                        <div class="card-body">
                            <h5 class="card-title">Purchase Booking Entry<span>| This Month</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $this->db->select('*');
                                    $this->db->from('crm_purchase');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('crm_id', $crm_id);
                                    $this->db->where('month',$mont);
                                    $this->db->where('year',$yer);
                                    $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
                                    $res = $this->db->get()->result();
                                    $total_purchase = count($res);


                                    ?>
                                    <h6><?= $total_purchase ?></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

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
                                    $this->db->where('status',0);
                                    $res = $this->db->get()->result();
                                    $totalCorrection = count($res);
                                    ?>
                                    <h6><?= $totalCorrection ?></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div><!-- End Customers Card -->





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
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $this->db->select('*');
                                    $this->db->from('crm_bankstatement');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
                                    $this->db->where('crm_id', $crm_id);
                                    $res = $this->db->get()->result();
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



        <!-- </div> -->
    </section>

</main><!-- End #main -->