<?php include_once('header.php'); ?>
<?php include_once('topbar.php'); ?>
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>khatabook/viewclient/<?= $this->session->userdata('khatabook_company_id') ?>">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>khatabook/party">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Party</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>company/profile">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Company Profile</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>inventory_billing">
                <i class="bi bi-file-earmark"></i>
                <span>Inventory</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>crm/company">
                <i class="bi bi-file-earmark"></i>
                <span>CRM</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>khatabook">
                <i class="bi bi-file-earmark"></i>
                <span>Khatabook</span>
            </a>
        </li>
        <!-- End Blank Page Nav -->
    </ul>
</aside>