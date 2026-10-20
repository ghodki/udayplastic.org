<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>crm/company/main_dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                <li>
                    <a href="<?= getCrmUrl() ?>party">
                        <i class="bi bi-circle"></i><span>Party</span>
                    </a>
                </li>


            </ul>
        </li><!-- End Components Nav -->

        <?php
        if ($this->session->userdata('roles') == 1) {

        ?>
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-navs" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Super Master</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-navs" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="<?= getCrmUrl() ?>subcategory">
                            <i class="bi bi-circle"></i><span>Category</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            <!-- End Components Nav -->

        <?php
        }
        ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= getCrmUrl() ?>company/profile">
            <i class="fa-solid fa-user"></i>
                <span>Company Profile</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= base_url() ?>inventory_billing">
                <i class="bi bi-file-earmark"></i>
                <span>Inventory Software</span>
            </a>
        </li>
        <!-- End Blank Page Nav -->

    </ul>

</aside>