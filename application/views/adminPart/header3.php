<?php $this->load->helper('url'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<meta http-equiv="x-ua-compatible" content="ie=edge">
    	<title>Uday Plastic</title>
    	<link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
    	<!-- Google Font: Source Sans Pro -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/googlefont.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/ionicons.min.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/font-awesomes.min.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/css.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/toastr.min.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.dataTables.min.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/buttons.dataTables.min.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/scroller.jqueryui.min.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/css.css">
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css">
            
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    	<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
    	<!-- Font Awesome -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/fontawesome-free/css/all.min.css">
    	<!-- Tempusdominus Bootstrap 4 -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    	<!-- iCheck -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    	<!-- JQVMap -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/jqvmap/jqvmap.min.css">
    	<!-- Theme style -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>dist/css/adminlte.min.css">
    	<!-- overlayScrollbars -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    	<!-- Daterange picker -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/daterangepicker/daterangepicker.css">
    	<!-- summernote -->
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/summernote/summernote-bs4.min.css" />
    	<link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/dt/dataTables.bootstrap4.min.css" />
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" />
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" />
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css" />
    	<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>dist/css/select2.min.css">
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/css/selectize.bootstrap3.min.css" />
            
    	<script type="text/javascript" src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    	<script type="text/javascript">
            var baseurl = '<?= base_url(); ?>';
            let currentDate = new Date();
            let customerArray = new Array();
            let productArray = new Array();
            let unitArray = new Array();
            let seriesArray = new Array();
            const mystateCode = 23;
            let stateCode = 23;
            let sgstPer = 0;
            let cgstPer = 0;
            let igstPer = 0;
    
            function userNotification(user_id, notification_id) {
                $.ajax({
                        type    :   "POST",
                        url     :   "<?= base_url('notify/seeNotification'); ?>",
                        data    :   {user_id: user_id, notification_id: notification_id},
                        success :   function(data) {
                                        if (data.status == true) {} else {}
                                    }
    
                });
            }
    	</script>
    	<style type="text/css">
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');
            .ck-editor__editable {
                min-height: 300px;
            }
            .ck .ck-content {
                color: #000;
            }            
            .label-success {
                position: relative;
                top: 20px; 
            }
            body {
                /* font-family: "Times New Roman", Times, serif; */
                font-family: "Poppins", sans-serif;font-weight: 400;
            }
            .text-bold {font-weight:500;}
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
            .nav-sidebar a p {
            color: white;
            font-size: 14px;
            margin-left: 10px !important;
            }
            .nav-icon {
            color: white;
            }
            .header {
            background-color: blue;
            }
            .loader {
            position: fixed;
            left: 0px;
            top: 20px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('') 50% 50% no-repeat rgb(249, 249, 249);
            }
            .modal-backdrop.show {
            display: none !important;
            }
            .modal-backdrop.in {
            display: none !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: white !important;
            border: 1px solid #0b3389 !important;
            background-color: #0b3389 !important;
            }
            .dataTables_wrapper .dt-button {
            color: white !important;
            border: 1px solid #0b3389 !important;
            background-color: #0b3389 !important;
            }
            .dataTables_wrapper .dataTables_length {
            font-size: 20px;
            }
            .navbar-expand .navbar-nav .nav-link {
                padding-right: 0.6rem;
                padding-left: 0.6rem;
            }
            .blink {
                animation: blinker 1s linear infinite;
            }
            @keyframes blinker {
                50% {
                    opacity: 0;
                }
            }

            .nav-sidebar .nav-item>.nav-link {
                display:flex;
                align-items:center;
            }

            .nav-pills .nav-link.active {
                color: #fff !important;
                background-color: #e86967 !important;
                border-radius: 0;
            }

            .layout-navbar-fixed .wrapper .brand-link {
                text-align:center;
            }
            .elevation-4 {
                box-shadow: 0 0 6px rgb(0 0 0 / 15%) !important;
            }
            .content-wrapper {padding-top:10px; min-height:91vh !important;}
            .card-header h1 {font-size:18px !important;color:#1e2e42; text-transform:uppercase;}

            .card {
                box-shadow: 0 0 10px rgb(0 0 0 / 11%);
                margin-bottom: 1rem;
                border: 0;
            }

            .d-boxes-style .card:hover {
                box-shadow:0 0 10px rgb(232 105 103 / 46%);
            }

            .d-boxes-style h3 {display:flex; justify-content:space-between;color:#e86967}
            .d-boxes-style h3 i {color:#7e8fa4}

            .main-header .text-primary {color:#0e2843 !important}
            .main-header .text-success {color:#e86967 !important}

    	</style>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" id="body">
	    <?php
            $sessiondata = $this->session->userdata();
            if (!isset($sessiondata) || empty($sessiondata) || !isset($sessiondata['user_id'])) {
                redirect('index');
            }
            $limit = 1000;
            $start = 0;
            $order = ['id' => 'DESC'];
            $where = ['role_id' => $this->session->userdata('roles')];
            $assig_menu = $this->user_model->getAry('role_has_permissions', $order, $start, $limit, $where);
            $assig_menu_arr = [];
            foreach ($assig_menu as $key => $asig_val) {
                $assig_menu_arr[] = $asig_val->permissions_id;
            }
            
            $without_assigning_user = $this->session->userdata('without_assign_user');
            if (isset($without_assigning_user) && !empty($without_assigning_user)) {
                redirect('signin/logout');
            }
            $user_id = $this->session->userdata('user_id');
	    ?>
	    <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="<?= base_url(); ?>" class="nav-link text-primary">Home</a>
                    </li>
                    <?php if ($this->session->userdata('subuser') && $this->session->userdata('roles') != 1) { ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="<?= base_url() ?>signin/adminlogin" class="nav-link">Admin Portal</a>
                    </li>
                    <?php } if ($this->session->userdata('roles') == 1) { ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link text-primary">
                            Wallet : 
                            <b class="text-success">
                                <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'amount'); ?>
                            </b>
                        </a>
                    </li>
                    <?php } if ($this->session->userdata('roles') != 1) { if ($this->user_model->getPermissionForUser('wallet')) { ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link text-primary">
                            Wallet : 
                            <b class="text-success">
                                <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'amount'); ?>
                            </b>
                        </a>
                    </li>
                    <?php } } ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link text-primary">
                            User ID : 
                            <b class="text-success"> 
                                <span>
                                    <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'user_number'); ?>
                                </span>
                            </b>
                        </a>
                    </li>
                    <?php if ($this->session->userdata('roles') != 1) { ?>
                    <li>
                        <?php 
                            $expiry = get_title('users', ['id' => $this->session->userdata('user_id')], 'expiry');
                            if (DifferenceFromDates($expiry) <= 7 && strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($expiry)))) {
                        ?>
                        <marquee onmouseover="this.stop();" onmouseout="this.start();" class="mt-2 blink">
                            <span class="text-danger">Your license has expired in <?= DifferenceFromDates($expiry); ?> days , Please contact your administrator.</span>
                        </marquee>
                        <?php } elseif (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($expiry)))) { ?>
                        <marquee onmouseover="this.stop();" onmouseout="this.start();" class="mt-2 blink">
                            <span class="text-danger">Your license has expired in -<?= DifferenceFromDates($expiry); ?> days , Please contact your administrator.</span>
                        </marquee>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <?php
                        $notify = $this->user_model->getAryss('notify', '', '', 6, '');
                        if ($notify) {
                            foreach ($notify as $key => $notification) {
                    ?>
                    <div class="modal fade" id="jaisa_mera_mai<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="text-center"><?= $notification->title; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center"><?= $notification->name; ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } } ?>

                    <div class="modal fade" id="all_data" tabindex="0" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header"></div>
                                <div class="modal-body">
                                    <table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th width="100px">Date</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $notify = $this->user_model->getAryss('notify', '', '', '', '');
                                                if (isset($notify) && $notify != '') {
                                                    foreach ($notify as $key => $cat) {
                                                        $date = $cat->created_at;
                                                        $newDate = date("d-m-Y", strtotime($date));
                                            ?>
                                            <tr>
                                                <td><span><?= $key + 1; ?></span></td>
                                                <td><?= $newDate; ?></td>
                                                <td><?= $cat->title; ?></td>
                                                <td><?= $cat->name; ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        $notify = $this->user_model->getAryss('notify', '', '', 6, '');
                        $total_notifiaction = 0;
                        $roles = $this->session->userdata('roles');
                        $user_last_notification_id = 0;
                        $count = 0;
                        $total_count_of_notification = 0;
                        $this->db->select_max('notification_id', 'max_id');
                        $this->db->where('user_id', $user_id);
                        $query = $this->db->get('notificaton_reader');
                        $max_id_value = $query->row();
                        if ($max_id_value->max_id != '') {
                            $user_last_notification_id = $max_id_value->max_id;
                        }

                        $this->db->select('*');
                        $this->db->from('notify');
                        $this->db->where('id >', $user_last_notification_id);
                        $qry = $this->db->get();
                        $notify_data = $qry->result();

                        if ($notify_data) {
                            $count = count($notify_data);
                        }

                        $notify = $this->user_model->getAryss('notify', '', '', '', '');
                        $total_count_of_notification = count($notify);
                    ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge"><?= $count; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header"><?= $total_count_of_notification ?> Notifications</span>
                            <div class="dropdown-divider"></div>
                            <?php
                                $notify = $this->user_model->getAryss('notify', '', '', 6, '');
                                if (isset($notify) && !empty($notify)) {
                                    foreach ($notify as $key => $notification) {
                                        $notify = $this->user_model->getAryss('notify', '', '', 6, '');
                                        $newDate = date("d-m-Y h:i", strtotime($notification->created_at));
                                        $lst_id_for_notification = $notification->id;
                            ?>
                            <a class="dropdown-item" data-toggle="modal" data-target="#jaisa_mera_mai<?= $key; ?>" onclick="userNotification(<?= $user_id; ?>, <?= $lst_id_for_notification; ?>);">
                                <i class="fas fa-envelope mr-2"></i><?= $notification->title; ?>
                                <span class="float-right text-light text-sm"><?= $newDate; ?></span>
                            </a>
                            <?php } } ?>
                            <div class="dropdown-divider"></div>
                            <a data-toggle="modal" data-target="#all_data" class="dropdown-item dropdown-footer">
                                See All Notifications
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $this->session->userdata('name'); ?>
                        </a>
                        <div class="dropdown-menu text-center">
                            <a class="dropdown-item nav-link" href="<?= base_url(); ?>profile">
                                <i class="fa fa-user" title="Profile"></i> Profile
                            </a>
                            <?php
                                $fileSharingPermission = $this->user_model->getPermissionForUser('file_sharing');
                                if ($fileSharingPermission) {
                            ?>
                            <a class="dropdown-item" href="<?= base_url(); ?>file_sharing">
                                <i class="fas fa-file" title="File Sharing"></i> File Sharing
                            </a>
                            <?php } ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?= base_url(); ?>signin/logout">
                                <i class="fas fa-sign-out-alt"></i> Sign Out
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <aside class="main-sidebar elevation-4" style="background: #2C4668;">
                <a href="<?= base_url(); ?>dashboard" class="brand-link bg-white">
                    <?php $logo = $this->user_model->commonLogo(); if ($logo) { ?>
                    <img src="<?= base_url(); ?>uploads/logo/<?= $logo[0]->logo; ?>" style="width: 80%;" />
                    <?php } ?>
                </a>
                <br /><br />
                <!-- Sidebar -->
                <div class="sidebar">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <?php $image = get_title('users', ['id' => $this->session->userdata('user_id')], 'image'); if ($image) { ?>
                        <div class="image">
                            <img src="<?= base_url(); ?>uploads/profile/<?= $image; ?>" class="img-circle elevation-2" alt="User Image" />
                        </div>
                        <?php } ?>
                        <div class="info">
                            <a href="<?= base_url(); ?>profile" class="d-block text-light">
                                <?= $this->session->userdata('name'); ?>
                            </a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <?php
                        $limit = 1000;
                        $start = 0;
                        $order = ['id' => 'DESC'];
                        $where = ['parents_id' => 0];
                        $menus = $this->user_model->getAry('permissions', $order, $start, $limit, $where);

                        $side_menuPermission = $this->user_model->getPermissionForUser('side_menu');
                        if ($side_menuPermission) {
                    ?>
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <?php
                                foreach ($menus as $menu) {
                                    $order = ['id' => 'DESC'];
                                    $where = ['parents_id' => $menu->id];
                                    $sub_menus = $this->user_model->getAry('permissions', $order, $start, $limit, $where);
                                    $currentmenu = $this->user_model->checkSubMenu($menu->id, uri_string());
                                    $fileSharingPermission = 1;
                                    if (in_array($menu->id, $assig_menu_arr)) {
                                        if ($menu->url == 'file_sharing')
                                            $fileSharingPermission = $this->user_model->getPermissionForUser($menu->url);
                                        else if ($menu->url == 'wallet') {
                                            $fileSharingPermission = $this->user_model->getPermissionForUser($menu->url);
                                        } else if ($menu->url == 'service') {
                                            $fileSharingPermission = $this->user_model->getPermissionForUser('services');
                                        } else if ($menu->url == 'inventory_billing') {
                                            $fileSharingPermission = $this->user_model->getPermissionForUser('inventory');
                                        } else if ($menu->url == 'paymentreceipt') {
                                            $fileSharingPermission = $this->user_model->getPermissionForUser('paymentreceipt');
                                        }
                                        if ($fileSharingPermission) {
                            ?>
                            <li class="nav-item has-treeview <?php if ($currentmenu == 1) { echo 'menu-open'; } ?>">
                                <a style="color: ghostwhite;" href="<?php if (empty($sub_menus)) { echo base_url($menu->url); } else { echo '#'; } ?>" class="nav-link <?php if (uri_string() == $menu->url || $currentmenu == 1) { echo 'active'; } ?>">
                                    <i class="<?= $menu->icon; ?>" title="<?= $menu->name; ?>"></i>
                                    <p class="text-light"><?= $menu->name; ?></p>
                                    <?php if (!empty($sub_menus)) { echo '<i class="fas fa-angle-left right bg-dark"></i>'; } ?>
                                </a>
                                <?php if (!empty($sub_menus)) { ?>
                                <ul class="nav nav-treeview">
                                    <?php
                                        foreach ($sub_menus as $key => $sub_menus_val) {
                                            if (in_array($sub_menus_val->id, $assig_menu_arr)) {
                                    ?>
                                    <li class="nav-item">
                                        <a style="color: ghostwhite;" href="<?= base_url($sub_menus_val->url); ?>" class="nav-link <?= (uri_string() == $sub_menus_val->url) ? "active" : ""; ?>">
                                            <i class="<?= $sub_menus_val->icon; ?>" title="<?= $sub_menus_val->name; ?>"></i>
                                            <p class="text-light"><?= $sub_menus_val->name; ?></p>
                                        </a>
                                    </li>
                                    <?php } } ?>
                                </ul>
                                <?php } ?>
                            </li>
                            <?php } } }
                                $permission_account_add = get_title('users', ['id' => $user_id], 'permission_account_add');
                                if ($roles == 2 && $permission_account_add == 'YES') {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="<?= base_url('subadmin'); ?>">
                                    <i class="fa fa-bank"></i>
                                    Add Bank Account
                                </a>
                            </li>
                            <?php } ?>
                            <li class="nav-item" style="margin-bottom: 100px;">
                                <a class="nav-link text-light" href="<?= base_url('signin/logout'); ?>">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <p class="text-light">Log Out</p>
                                </a>
                            </li>
                            <li class="nav-item" style="position: fixed; bottom: 0px;">
                                <a class="nav-link text-light" href="#">
                                    <i class="fas fa-id-card"></i>
                                    <p class="text-light">
                                        <?= get_title('users', ['id' => $this->session->userdata('user_id')], 'user_number'); ?>
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php } ?>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
	    </div>