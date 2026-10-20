<?php include_once('header.php'); ?>

<header id="header" class="header fixed-top d-flex align-items-center">
    <?php $logo = $this->user_model->commonLogo(); ?>
    <div class="d-flex align-items-center justify-content-between">
        <a href="<?= base_url(); ?>" class="logo d-flex align-items-center">
            <?php if (isset($logo) && !empty($logo)) { ?>
            <img style="max-height: 62px;" src="<?= base_url(); ?>uploads/logo/<?= $logo[0]->logo; ?>" alt="logo" />
            <?php } ?>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <span class="nav-link nav-profile d-flex align-items-center pe-0">
                    <?php
                        $month = $this->session->userdata('months');
                        $selected_month = date('m-Y'); //current month
                        $years = date('Y');
                        echo '<select id="month" class="form-control" name="month" style="width: 120px;" onchange="window.location.href=this.value">' . "\n";
                        for ($i_month = 1; $i_month <= 12; $i_month++) {
                            $selected = ($month == $i_month ? ' selected' : '');
                            echo '<option value="' . base_url('crm/company/setMonth/') . '' . $i_month . "-" . $years . '"' . $selected . '>' . date('F', mktime(0, 0, 0, $i_month, 1, $years)) . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                    ?>
                </span><!-- End Profile Iamge Icon -->
            </li>
            <li class="nav-item dropdown pe-3">
                <span class="nav-link nav-profile d-flex align-items-center pe-0">
                    <?php
                        $year = $this->session->userdata('fy');
                        $years2 = $this->user_model->getResult('year');
                        echo '<select id="year" class="form-control" name="year" onchange="window.location.href=this.value">' . "\n";
                        foreach ($years2 as $key => $newyear) {
                            $selected = ($year == $newyear->id ? ' selected' : '');
                            echo '<option value="' . base_url('crm/company/setYear/') . '' . $newyear->id . '"' . $selected . '>' . $newyear->year . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                    ?>
                </span><!-- End Profile Iamge Icon -->
            </li>
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="<?= base_url(); ?>crm/assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?= $this->session->userdata('name'); ?></span>
                </a><!-- End Profile Iamge Icon -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?= $this->session->userdata('name'); ?></h6>
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
                        <a class="dropdown-item d-flex text-danger align-items-center" href="<?= base_url(); ?>signin/logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>