<?php include_once('includes/main_side.php'); ?>


<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard/main_dashboard">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <?php
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $this->db->select('*');
            $this->db->from('crm_party');
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            //   $this->db->where('status', 1);
            $res = $this->db->get()->result();
            $totalUsersCount = count($res);

            $this->db->select('*');
            $this->db->from('crm_service');
            $this->db->where('status', 1);
            $allservices = $this->db->get()->result();
            if ($allservices) {
                foreach ($allservices as $seprateService) {
            ?>
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <a href="<?= getCrmUrl() ?>dashboard/<?= $seprateService->url ?>">

                                <div class="card-body">
                                    <h5 class="card-title"><?= $seprateService->service_name ?><span></span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                            $user_id = $this->session->userdata('user_id');
                                            $crm_id = $this->session->userdata('crm_id');
                                            $this->db->select('*');
                                            $this->db->from('crm_party');
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('crm_id', $crm_id);
                                            $this->db->like('services_id', $seprateService->id);
                                            $res = $this->db->get()->result();
                                            $allUser = count($res);
                                            ?>
                                            <span><a title="Assign User" class="btn btn-success" href="<?php echo getCrmUrl() ?>party/assignUsers/<?= $seprateService->id ?>"><?= $allUser ?></a></span>,
                                            <span><a title="Not Assign User" class="btn btn-danger" href="<?php echo getCrmUrl() ?>party/NotassignUsers/<?= $seprateService->id ?>"><?= $totalUsersCount - $allUser ?></a></span>

                                            <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
            <?php
                }
            }
            ?>

            <div class="col-xxl-4 col-xl-6">
                <a href="<?= getCrmUrl() ?>party/partyData">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Total User<span></span></h5>

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

                                    $res = $this->db->get()->result();
                                    $totaluser = count($res);


                                    ?>
                                    <h6><?= $totaluser ?></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div>


            <div class="col-xxl-4 col-xl-6">

                <a href="<?= getCrmUrl() ?>party/active_user_for">
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

            <div class="col-xxl-4 col-xl-6">

                <a href="<?= getCrmUrl() ?>party/deactiveuser_for">
                    <div class="card info-card customers-card">


                        <div class="card-body">
                            <h5 class="card-title">Deactivate User<span></span></h5>

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
                                    $this->db->where('status', 0);

                                    $res = $this->db->get()->result();
                                    $crm_party = count($res);


                                    ?>
                                    <h6><?= $crm_party ?></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div><!-- End Customers Card -->

            <div class="col-xxl-4 col-xl-6">

                <a href="<?= getCrmUrl() ?>party/not_register">
                    <div class="card info-card customers-card">



                        <div class="card-body">
                            <h5 class="card-title">Not Assigned<span></span></h5>

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
                                    $this->db->where('sub_services_id', null);
                                    $res = $this->db->get()->result();
                                    $totalUserOfSubservice = count($res);



                                    ?>
                                    <h6><?= $totalUserOfSubservice ?></h6>
                                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                                </div>
                            </div>

                        </div>
                    </div>
                </a>

            </div>


        </div><!-- End Left side columns -->



        <!-- </div> -->
    </section>

</main>

<?php include_once('includes/footer.php'); ?>