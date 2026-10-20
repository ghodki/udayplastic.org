<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>signin">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <!-- <div class="col-lg-12"> -->

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
                <a href="#">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">INDIVIDUAL IT RETURN<span>| This Month</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <?php
                                    $user_id = $this->session->userdata('user_id');
                                    $crm_id = $this->session->userdata('crm_id');
                                    $this->db->select('*');
                                    $this->db->from('crm_gstr1');
                                    $this->db->where('user_id', $user_id);
                                    $this->db->where('crm_id', $crm_id);
                                    $res = $this->db->get()->result();
                                    $total_gstr1 = count($res);


                                    ?>
                                    <h6></h6>
                                    <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
                <a href="#">
                    <div class="card info-card revenue-card">


                        <div class="card-body">
                            <h5 class="card-title">FIRM IT RETURN<span>| This Month</span></h5>

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
                                    $res = $this->db->get()->result();
                                    $total_gstr3b = count($res);


                                    ?>
                                    <h6></h6>
                                    <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                                </div>
                            </div>
                        </div>

                    </div>
                </a>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-6">

                <a href="#">
                    <div class="card info-card customers-card">

                        <div class="card-body">
                            <h5 class="card-title">INCOME TAX AUDIT<span>| This Month</span></h5>

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
                                    $res = $this->db->get()->result();
                                    $total_sale = count($res);


                                    ?>
                                    <h6></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div><!-- End Customers Card -->


            <div class="col-xxl-4 col-xl-6">

                <a href="#">
                    <div class="card info-card customers-card">

                        <div class="card-body">
                            <h5 class="card-title">DETAILS RECEIVED<span>|This Month</span></h5>

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
                                    $res = $this->db->get()->result();
                                    $total_sale = count($res);


                                    ?>
                                    <h6></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div><!-- End Customers Card -->


            <div class="col-xxl-4 col-xl-6">

<a href="#">
    <div class="card info-card customers-card">

        <div class="card-body">
            <h5 class="card-title">TOTAL INCOME TAX USER<span>|This Month</span></h5>

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
                    $res = $this->db->get()->result();
                    $total_sale = count($res);


                    ?>
                    <h6></h6>
                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

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