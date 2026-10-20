<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1" />
    	<meta http-equiv="x-ua-compatible" content="ie=edge" />
    	<title>Uday Plastic</title>
    	<link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>assets/images/favicon.png" />    
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/googlefont.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ionicons.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/font-awesomes.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/toastr.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.dataTables.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/buttons.dataTables.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/scroller.jqueryui.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/css22.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/fontawesome-free/css/all.min.css">
    	<!-- overlayScrollbars -->
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    	<!-- Theme style -->
    	<link rel="stylesheet" href="<?= base_url(); ?>dist/css/adminlte.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/daterangepicker/daterangepicker.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/daterangepicker/daterangepicker.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    	<link rel="stylesheet" href="<?= base_url(); ?>dist/css/select2.min.css">
        <link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui.css" />
    	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/choices.min.css" />
        
    	<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    	<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/choices.min.js"></script>    
    	<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/ckeditor.js"></script>   
        <script type="text/javascript">
            var baseurl = '<?= base_url(); ?>';
            let currentDate = new Date();
            let customerArray = new Array();
            let productArray = new Array();
            let unitArray = new Array();
            let seriesArray = new Array();
            const mystateCode = 8;
            let stateCode = 8;
            let sgstPer = 0;
            let cgstPer = 0;
            let igstPer = 0;
            
            const ChangeFinancialYear = (FinancialYearId) => {
                if (FinancialYearId != "" && FinancialYearId != null) {
                    $.ajax({
                        method  :   "POST",
                        url     :   "<?= base_url(); ?>inventory_billing/ChangeFinancialYear",
                        data    :   {FinancialYearId: FinancialYearId},
                        success :   function (Response) {                                                      
                                        if (Response) {
                                            var FinancialYearData = JSON.parse(Response);
                                            if (FinancialYearData.status == true) {
                                                location.reload();
                                            }
                                        }
                                    }
                    });
                }
            };
            
            $("#financial_year").on('change', function() {
                let year = $("#financial_year").val();
                if (year != '') {
                    $.ajax({
                        type    :   "POST",
                        data    :   {year: year},
                        url     :   "<?= base_url(); ?>finencial_year/change_financial_year",
                        success :   function(invoiceNo) {
                                        invoiceNo = JSON.parse(invoiceNo);
                                        if (invoiceNo.status == true) {
                                            toastr.success(invoiceNo.msg, invoiceNo.msg);
                                            window.location.reload();
                                        } else {
                                            toastr.error(invoiceNo.msg, invoiceNo.msg);
                                        }
                                    }
                    });
                }
            });
    	</script>
        
        <style type="text/css">
            @media print{
               @page {
                   size: auto;
               }
           }
           .card-title {
                font-size: 1.5rem;
           }
            body {
                font-family: "Times New Roman";
            }
            .btn-lg {
                width: 150px;
                margin-left: 13px;
            }
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: red;
                -webkit-transition: .4s;
                transition: .4s;
            }
            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }
            input:checked+.slider {
                background-color: green;
            }
            input:focus+.slider {
                box-shadow: 0 0 1px #2196F3;
            }
            input:checked+.slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }
            /* Rounded sliders */
            .slider.round {
                border-radius: 34px;
            }
            .slider.round:before {
                border-radius: 50%;
            }
            #wrapper {
                padding-left: 0;
                -webkit-transition: all 0.5s ease;
                -moz-transition: all 0.5s ease;
                -o-transition: all 0.5s ease;
                transition: all 0.5s ease;
            }
            #wrapper.toggled {
                padding-right: 250px;
            }
            #sidebar-wrapper {
                z-index: 1000;
                position: fixed;
                right: 250px;
                width: 0;
                height: 100%;
                margin-right: -250px;
                overflow-y: auto;
                background: whitesmoke;
                -webkit-transition: all 0.5s ease;
                -moz-transition: all 0.5s ease;
                -o-transition: all 0.5s ease;
                transition: all 0.5s ease;
            }
            #wrapper.toggled #sidebar-wrapper {
                width: 250px;
            }
            #page-content-wrapper {
                width: 100%;
                position: absolute;
                padding: 15px;
            }
            #wrapper.toggled #page-content-wrapper {
                position: absolute;
                margin-right: -250px;
            }
            /* Sidebar Styles */
            .sidebar-nav {
                position: absolute;
                top: 0;
                width: 250px;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .sidebar-nav li {
                text-indent: 20px;
                line-height: 40px;
            }
            .sidebar-nav li a {
                display: block;
                text-decoration: none;
                color: #999999;
            }
            .sidebar-nav li a:hover {
                text-decoration: none;
                color: #fff;
                background: rgba(255, 255, 255, 0.2);
            }
            .sidebar-nav li a:active,
            .sidebar-nav li a:focus {
                text-decoration: none;
            }
            .sidebar-nav>.sidebar-brand {
                height: 65px;
                font-size: 18px;
                line-height: 60px;
            }
            .sidebar-nav>.sidebar-brand a {
                color: #999999;
            }
            .sidebar-nav>.sidebar-brand a:hover {
                color: #fff;
                background: none;
            }
            .nav-sidebar a p {
                color: white;
                font-size: 20px;
            }
            .nav-icon {
                color: white;
            }    
            @media(min-width:768px) {
                #wrapper {
                    padding-right: 250px;
                }
                #wrapper.toggled {
                    padding-right: 0;
                }
                #sidebar-wrapper {
                    width: 250px;
                }
                #wrapper.toggled #sidebar-wrapper {
                    width: 0;
                }
                #page-content-wrapper {
                    padding: 20px;
                    position: relative;
                }
                #wrapper.toggled #page-content-wrapper {
                    position: relative;
                    margin-right: 0;
                }
            }
            #loading-image {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: white !important;
                border: 1px solid #2980B9 !important;
                background-color: #2980B9 !important;
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2980B9), color-stop(100%, #2980B9)) !important;
                background: -webkit-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: -moz-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: -ms-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: -o-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: linear-gradient(to bottom, #2980B9 0%, #2980B9 100%) !important;
            }
            .dataTables_wrapper .dt-button {
                color: white !important;
                border: 1px solid #2980B9 !important;
                background-color: #2980B9 !important;
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2980B9), color-stop(100%, #2980B9)) !important;
                background: -webkit-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: -moz-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: -ms-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: -o-linear-gradient(top, #2980B9 0%, #2980B9 100%) !important;
                background: linear-gradient(to bottom, #2980B9 0%, #2980B9 100%) !important;
            }
            .dataTables_wrapper .dataTables_length {
                font-size: 20px;
            }
            .fa-circle {
                font-weight: 400;
            }
            .search-ul {
                position: absolute;
                z-index: 9;
                background: #fff;
                height: auto;
                max-height: 250px;
                overflow: auto;
            }
    	</style>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse">    	
    	<div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="javascript:void(0);">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="<?= base_url(); ?>inventory/dashboard" class="nav-link text-primary">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block" style="margin-left: 10px; margin-top: 7px;">
                        <span>
                            <i class="fa fa-building"></i> : 
                        </span>
                        <span style="margin-left: 5px;"></span>
                        <strong>
                            <?= ucfirst(get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>
                        </strong>
                    </li>
                    <?php if ($this->session->userdata('BranchRole')) { ?>
                    <li class="nav-item d-none d-sm-inline-block" style="margin-left: 10px; margin-top: 7px;">
                        <span>
                            <i class="fa fa-sitemap"></i> : 
                        </span>
                        <span style="margin-left: 5px;"></span>
                        <strong>
                            <?= ucfirst(get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')], 'BranchName')); ?>
                        </strong>
                    </li>
                    <?php } ?>
                    <li class="nav-item d-none d-sm-inline-block" style="margin-left: 10px; margin-top: 7px;">
                        <span>
                            <i class="fa fa-user"></i> :
                        </span>
                        <strong>
                            <span style="margin-left: 5px;"></span>
                            <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'name'); ?>
                        </strong>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block" style="margin-left: 10px; margin-top: 7px;">
                        <span>
                            <i class="fa fa-map-marker"></i> :
                        </span>
                        <strong>
                            <span style="margin-left: 5px;"></span>
                            <?= get_title('state_list', ['id' => $this->session->userdata('state')], 'state'); ?>
                        </strong>
                    </li>    
                    <li class="nav-item d-none d-sm-inline-block" style="margin-left: 10px; margin-top: 7px;">
                        <span>
                            <i class="fa fa-sign-in-alt"></i> :
                        </span>
                        <strong>
                            <span style="margin-left: 5px;"></span>
                            <?= $this->session->userdata('date'); ?>
                            <?= $this->session->userdata('login'); ?>
                        </strong>
                    </li>
                </ul>
                <!-- SEARCH FORM -->
                <ul class="navbar-nav ml-auto hidden" style="display: none;">
                    <!-- Navbar Search -->
                    <li class="nav-item">
                        <a class="nav-link" href="#menu-toggle" class="btn btn-default" id="menu-toggle">
                            <i class="fas fa-th-large"></i>
                        </a>
                    </li>
                    <!-- Messages Dropdown Menu -->
                    <div id="wrapper">
                        <!-- Sidebar -->
                        <div id="sidebar-wrapper">
                            <p class="text-center mt-5">ShortCut Key</p>
                            <ul class="sidebar-nav">
                                <br /><br /><br /><br />
                                <li>
                                    <span class="text-success">Save=CTRL+S</span>
                                </li>
                                <li>
                                    <span class="text-danger">Delete=CTRL+B</span>
                                </li>
                                <li>
                                    <span class="text-primary">Print=CTRL+P</span>
                                </li>
                                <li>
                                    <span class="text-muted">Print=CTRL+U</span>
                                </li>
                                <li>
                                    <span class="text-muted">Previous=CTRL+Alt+Y</span>
                                </li>
                            </ul>
                        </div>
                        <!-- /#sidebar-wrapper -->
                        <!-- Page Content -->
                        <!-- /#page-content-wrapper -->
                    </div>
                </ul>
            </nav>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar elevation-4 sidebar-collapse" style="background: #2C4668;">
                <!-- Brand Logo -->
                <?php $lo = $this->user_model->commonLogo(); ?>
                <a href="<?= base_url(); ?>profile" class="brand-link bg-white">
                    <?php if ($lo) { ?>
                    <img src="<?= base_url() ?>uploads/logo/<?= $lo[0]->logo; ?>" style="width: 100%;" />
                    <?php } ?>
                </a>
                <!-- Sidebar -->
                <br /><br />
                <div class="sidebar" style="height: 100%;">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-5 pb-3 d-flex">
                        <?php 
                            $image = get_title('users', ['id' => $this->session->userdata('user_id')], 'image');
                            if ($image) {
                        ?>
                        <div class="image">
                            <img src="<?= base_url(); ?>uploads/profile/<?= $image ?>" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <?php } ?>
                        <div class="info">
                            <a href="<?= base_url(); ?>profile" class="d-block text-light">
                                <?= $this->session->userdata('name'); ?>
                            </a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <nav>
                        <ul class="nav nav-pills nav-sidebar flex-column text-light" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                            <li class="nav-item" style="padding-bottom: 10px;">
                                <select name="FinancialYear" id="FinancialYear" class="form-control form-control-sm" style="color: #ffffff; background-color: #2c4668; border: 1px solid #2c4668;" onchange="ChangeFinancialYear(this.value);">
                                    <?php 
                                        if ($this->db->select('id, year')->get('year')->num_rows()) { 
                                            foreach ($this->db->select('id, year')->get('year')->result() as $key => $ydata) { 
                                    ?>
                                    <option <?= $this->session->userdata('financial_year') == $ydata->id ? "selected" : ""; ?> value="<?= $ydata->id; ?>"><?= $ydata->year; ?></option>
                                    <?php } } ?>
                                </select>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url(); ?>inventory/dashboard" class="nav-link active">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p class="text-center">Dashboard</p>    
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-gear"></i>
                                    <p>
                                        Master
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="margin-left: 20px;">
                                    <?php
                                        $where = ['parents_id' => 47];
                                        $inve = $this->user_model->getAry('permissions', '', '', '', $where);
                                        if ($inve) {
                                            foreach ($inve as $invent) {
                                                $url = $invent->url;
                                                if ($invent->id == 56 && $role != 1) {
                                                } else {
                                    ?>
                                    <li class="nav-item">
                                        <a style="color:ghostwhite;" href="<?php echo base_url('invent/' . $invent->url); ?>" class="nav-link <?= (uri_string() == 'invent/' . $invent->url) ? "active" : ""; ?>">
                                            <i class="<?= $invent->icon ?>" title="<?= $invent->name ?>"></i>
                                            <p><?= $invent->name ?></p>
                                        </a>
                                    </li>
                                    <?php } } } ?>
                                </ul>
                            </li>
                            <li id="voucher" class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-inbox"></i>
                                    <p>
                                        Voucher
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="margin-left: 20px;">
                                    <?php
                                        $where = ['parents_id' => 66];
                                        $inve = $this->user_model->getAry('permissions', '', '', '', $where);
                                        if ($inve) {
                                            foreach ($inve as $invent) {
                                                $url = $invent->url;
                                    ?>
                                    <li class="nav-item">
                                        <a style="color:ghostwhite;" href="<?php echo base_url('invent/' . $invent->url); ?>" class="nav-link <?= (uri_string() == 'invent/' . $invent->url) ? "active" : ""; ?>">
                                            <i class="<?= $invent->icon ?>" title="<?= $invent->name ?>"></i>
                                            <p><?= $invent->name ?></p>
                                        </a>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Reports
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="margin-left: 20px;">
                                    <?php
                                        $where = ['parents_id' => 71];
                                        $inve = $this->user_model->getAry('permissions', '', '', '', $where);
                                        if ($inve) {
                                            foreach ($inve as $invent) {
                                                $url = $invent->url;
                                    ?>
                                    <li class="nav-item">
                                        <a style="color:ghostwhite;" href="<?php echo base_url('invent/' . $invent->url); ?>" class="nav-link <?= (uri_string() == 'invent/' . $invent->url) ? "active" : ""; ?>">
                                            <i class="<?= $invent->icon ?>" title="<?= $invent->name ?>"></i>
                                            <p><?= $invent->name ?></p>
                                        </a>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-recycle"></i> 
                                    <p>
                                        Recycle Bin
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="margin-left: 20px;">
                                    <?php
                                        $where = ['parents_id' => 96];
                                        $inve = $this->user_model->getAry('permissions', '', '', '', $where);
                                        if ($inve) {
                                            foreach ($inve as $invent) {
                                            $url = $invent->url;
                                    ?>
                                    <li class="nav-item">
                                        <a style="color:ghostwhite;" href="<?php echo base_url($invent->url); ?>" class="nav-link <?= (uri_string() == 'invent/' . $invent->url) ? "active" : ""; ?>">
                                            <i class="<?= $invent->icon ?>" title="<?= $invent->name ?>"></i>
                                            <p><?= $invent->name ?></p>
                                        </a>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-user-secret"></i> 
                                    <p>
                                        Salesman Reports
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="margin-left: 20px;">
                                    <?php
                                        $where = ['parents_id' => 122];
                                        $inve = $this->user_model->getAry('permissions', '', '', '', $where);
                                        if ($inve) {
                                            foreach ($inve as $invent) {
                                                $url = $invent->url;
                                    ?>
                                    <li class="nav-item">
                                        <a style="color:ghostwhite;" href="<?php echo base_url($invent->url); ?>" class="nav-link <?= (uri_string() == 'invent/' . $invent->url) ? "active" : ""; ?>">
                                            <i class="<?= $invent->icon ?>" title="<?= $invent->name ?>"></i>
                                            <p><?= $invent->name ?></p>
                                        </a>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url() ?>comp_profile" class="nav-link">
                                    <i class="far fa-user nav-icon"></i>
                                    <p><?= $this->session->userdata('BranchRole') ? "Branch" : "Company"; ?> Profile</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url() ?>signin/logout" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Out</p>
                                </a>
                            </li>
                            <li class="nav-item" style="margin-bottom: 100px;">
                                <a href="#" class="nav-link">
                                    <p>SN : <?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'serial_number'); ?></p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
            <!-- Content Wrapper. Contains page content -->
            <!-- /.content-wrapper -->
            <!-- Control Sidebar -->
            <!-- /.control-sidebar -->
    	</div>
        <!-- ./wrapper -->