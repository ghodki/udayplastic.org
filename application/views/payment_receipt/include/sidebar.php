<?php 
    include_once('header.php'); 
    include_once('topbar.php'); 
?>

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>dashboard">
                <i class="fa fa-home"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>paymentReceipt/viewclient/<?= $this->session->userdata('payment_receipt_company_id'); ?>">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>paymentReceipt/party">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Party</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>paymentReceipt/category">
                <i class="fa fa-list" aria-hidden="true"></i>
                <span>Payment Category</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>paymentReceipt/config">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <span>Receipt Config</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>paymentReceipt/transaction">
                <i class="fa fa-rupee-sign"></i>
                <span>Payment Transaction</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>paymentReceipt">
                <i class="fas fa-receipt"></i>
                <span>Payment Receipt</span>
            </a>
        </li>
        <!-- End Blank Page Nav -->
    </ul>
</aside>