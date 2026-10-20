<header id="header" class="header fixed-top d-flex align-items-center">
    <?php $logo = $this->user_model->commonLogo(); ?>
    <div class="d-flex align-items-center justify-content-between">
        <a href="<?= base_url(); ?>" class="logo d-flex align-items-center">
            <?php if (isset($logo) && !empty($logo)) { ?>
            <img style="max-height: 62px;" src="<?= base_url(); ?>uploads/logo/<?= $logo[0]->logo; ?>" alt="" />
            <?php } ?>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->
    <div class="row">
        <div class="col-md-12">
            <p class="pl-3 pt-3 text-black">
                Company : <b><?= get_title('payment_receipt_company', ['id' => $this->session->userdata('payment_receipt_company_id')], 'company_name'); ?></b>
                &nbsp;&nbsp;
                User : <b><?= get_title('users', ['id' => $this->session->userdata('user_id')], 'name'); ?></b>
            </p>
        </div>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="<?= base_url() ?>crm/assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?= $this->session->userdata('name') ?></span>
                </a><!-- End Profile Iamge Icon -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?= $this->session->userdata('name') ?></h6>
                        <span>
                            <?php
                                $role_id = $this->session->userdata('roles');
                                if ($role_id == 1) {
                                    echo "Admin";
                                } elseif ($role_id == 2) {
                                    echo "Subadmin";
                                } elseif ($role_id == 3) {
                                    echo "User";
                                } elseif ($role_id == 4) {
                                    echo "Employee";
                                }
                            ?>
                        </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url(); ?>signin/logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->
        </ul>
    </nav><!-- End Icons Navigation -->
</header><!-- End Header -->