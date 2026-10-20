<?php include_once('includes/main_side.php'); ?>


<main id="main" class="main">

    <div class="pagetitle">
        <h1>Professional Tax</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>dashboard/main_dashboard">Home</a></li>
                <li class="breadcrumb-item active">Professional Tax Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <?php
            $this->db->select('*');
            $this->db->from('crm_servicesubgroup');
            $this->db->where('status', 1);
            $this->db->where('service_id', $this->session->userdata('service_id'));
            $this->db->order_by('id', 'desc');
            $allservices = $this->db->get()->result();
            if ($allservices) {
                foreach ($allservices as $seprateService) {
            ?>
                    <div class="col-xxl-4 col-md-6">
                        <a href="<?= getCrmUrl() ?>dashboard/<?= $seprateService->sub_name ?>">
                            <div class="card info-card sales-card">

                                <div class="card-body">
                                    <h5 class="card-title"><?= $seprateService->sub_service_name ?><span></span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">

                                            <h6></h6>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div><!-- End Sales Card -->
            <?php
                }
            }
            ?>
        </div>
    </section>

</main>

<?php include_once('includes/footer.php'); ?>