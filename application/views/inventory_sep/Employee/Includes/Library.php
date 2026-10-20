<title>Uday Plastic</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link type="image/x-icon" rel="icon" href="<?= base_url(); ?>inv-assets/images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>inv-assets/css/bootstrap.min.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/font-awesome.min.css "/>
<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>inv-assets/css/animate.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url(); ?>inv-assets/css/style.css" />
<!-- Font Awesome Icons -->    
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/googlefont.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ionicons.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/font-awesomes.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/toastr.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/scroller.jqueryui.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/css22.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/fontawesome-free/css/all.min.css" />
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= base_url(); ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>dist/css/select2.min.css" />    	
<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui.css" />
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url(); ?>dist/css/adminlte.min.css" />
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
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
</script>

<style type="text/css">
    body {
        font-size: 14px;
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
        font-size: 16px;
    }
    .fa-circle {
        font-weight: 400;
    }
    .layout-navbar-fixed .wrapper .brand-link {
        overflow: visible;
    }
    .layout-navbar-fixed.layout-fixed .wrapper .sidebar {
        margin-top: calc(1.5rem + 0px);
    }
    .nav-sidebar .nav-item>.nav-link {
        margin-bottom: 0rem;
    }
    .nav-link {
        padding: 0.3rem 0.25rem;
    }
    th, td {
        white-space: nowrap;
    }
    .dt-buttons {
        left: 23%;
    }
    @media print {
        @page {
            size: auto;
        }
    }
    .content-header {
        padding: 5px 5px 0px 0px;
    }
    .content-wrapper>.content {
        padding: 0 0rem;
    }
    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 600;
    }
    .card-body {
        padding: 0.50rem;
    }
    .chart {
        height: 330px;
    }
    #company_id {
        color: #ffffff !important;
        background: rgba(38,89,198,1) !important;
    }
    #company_id option {
        color: #ffffff;
    }
    .table {
        margin-bottom: 0rem;
    }
    th, td {
        white-space: nowrap;
    }
    .table-reponsive {
        display: block;
        width: 100%;
        overflow-x: auto;
    }
    .cutname-txt h6 {
        font-size: 0.8rem;
    }
    .bg-info, .bg-info>a {
        color: #fff!important;
    }
    .small-box>.inner {
        padding: 10px;
    }
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        display: block;
        margin-bottom: 20px;
        position: relative;
    }
    .small-box .icon {
        color: rgba(0,0,0,.15);
        z-index: 0;
    }
    .small-box>.small-box-footer {
        background-color: rgba(0,0,0,.1);
        color: rgba(255,255,255,.8);
        display: block;
        padding: 3px 0;
        position: relative;
        text-align: center;
        text-decoration: none;
        z-index: 10;
    }
    .small-box .icon>i.fa, .small-box .icon>i.fab, .small-box .icon>i.fad, .small-box .icon>i.fal, .small-box .icon>i.far, .small-box .icon>i.fas, .small-box .icon>i.ion {
        font-size: 70px;
        top: 20px;
    }
    .small-box .icon>i {
        font-size: 90px;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: -webkit-transform .3s linear;
        transition: transform .3s linear;
        transition: transform .3s linear,-webkit-transform .3s linear;
    }
    .small-box:hover {
        text-decoration: none;
    }
    .small-box:hover .icon>i {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px;
        padding: 0;
        white-space: nowrap;
    }
    .small-box h3, .small-box p {
        z-index: 5;
        color: #ffffff;
    }
    @media (min-width: 992px) {
        .col-lg-3 .small-box h3, .col-md-3 .small-box h3, .col-xl-3 .small-box h3 {
            font-size: 1.6rem;
        }
    }
    @media (min-width: 1200px) {
        .col-lg-3 .small-box h3, .col-md-3 .small-box h3, .col-xl-3 .small-box h3 {
            font-size: 2.2rem;
        }
    }
    .small-box-footer>i {
        color: #ffffff;
    }
    .fa-file-text-o:before {
        content: "\f0f6";
        color: #00000026;
    }
    .fa-file-o:before {
        content: "\f016";
        color: #00000026;
    }
    .fa-users:before {
        content: "\f0c0";
        color: #00000026;
    }
    .fa-list:before {
        content: "\f03a";
        color: #00000026;
    }
    .bg-gray {
        background-color: #adb5bd!important;
    }
    .bg-warning {
        background-color: #ffc107!important;
    }
    .bg-fuchsia {
        background-color: #f012be!important;
    }
    .bg-purple {
        background-color: #605ca8!important;
    }
    .bg-success {
        background-color: #28a745!important;
    }
    .card-primary.card-outline {
        border-top: 3px solid #007bff;
    }
    .card-info.card-outline {
        border-top: 3px solid #17a2b8;
    }
    .card-danger.card-outline {
        border-top: 3px solid #dc3545;
    }
    .card-success.card-outline {
        border-top: 3px solid #28a745;
    }
    .card-warning.card-outline {
        border-top: 3px solid #ffc107;
    }
    .pagination {
        margin-bottom: 0rem;
    }
    .row {
        --bs-gutter-x: 0.5rem;
    }
    .page-link>i {
        color: #0d6efd;
    }
    .input-group-append {
        margin-left: -1px;
        display: -ms-flexbox;
        display: flex;
    }
    .input-group>.input-group-append>.input-group-text {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .input-group-text {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding: 0.375rem 0.75rem;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    @media (min-width: 992px) {
        .sidebar-mini.sidebar-collapse .content-wrapper {
            margin-left: 0rem !important;
        }
    }
    .collapse:not(.show) {
        display: none !important;
    }
    .navbar {
        display: grid;
    }
    .dropdown-menu-container {
        position: absolute;
        top: 10px;
        right: 0px;
    }
    .dropdown-menu-container:hover .dropdown-hover {
        top: 45px;
        left: -10px;
        display: inline-block;
    }
    #view_selected_product span {
        color: #fff;
    }
    .header {
        background-color: #052f54;
    }
    .dt-button span {
        color: #ffffff;
    }
    @media (min-width: 768px) {
        body:not(.sidebar-mini-md) .content-wrapper, body:not(.sidebar-mini-md) .main-footer, body:not(.sidebar-mini-md) .main-header {
            margin-left: 0px;
        }
    }
    .submenu>i {
        position: relative;
        left: 95%;
        right: 0;
        top: -15%;
        bottom: 0px;
        font-size: 20px;
        display: none;
    }
    @media (max-width: 400px) {
        .submenu__close {
            display: block !important;
        }
        .navlogo {
            position: relative;
            right: 20px;
        }
    }
    .text-danger p {
        color: #dc3545;
    }
    .button {
        display: inline-block;
        position: relative;
        padding: 2px 5px;
    }
    .notification_badge {
        background-color: #fa3e3e;
        border-radius: 2px;
        color: #fff;
        padding: 1px 3px;
        font-size: 10px;
        position: absolute;
        top: -6px;
        right: -2px;
    }
    .notification {
      -webkit-animation: ring 4s .7s ease-in-out infinite;
      -webkit-transform-origin: 50% 4px;
      -moz-animation: ring 4s .7s ease-in-out infinite;
      -moz-transform-origin: 50% 4px;
      animation: ring 4s .7s ease-in-out infinite;
      transform-origin: 50% 4px;
    }
    @-webkit-keyframes ring {
      0% { -webkit-transform: rotateZ(0); }
      1% { -webkit-transform: rotateZ(30deg); }
      3% { -webkit-transform: rotateZ(-28deg); }
      5% { -webkit-transform: rotateZ(34deg); }
      7% { -webkit-transform: rotateZ(-32deg); }
      9% { -webkit-transform: rotateZ(30deg); }
      11% { -webkit-transform: rotateZ(-28deg); }
      13% { -webkit-transform: rotateZ(26deg); }
      15% { -webkit-transform: rotateZ(-24deg); }
      17% { -webkit-transform: rotateZ(22deg); }
      19% { -webkit-transform: rotateZ(-20deg); }
      21% { -webkit-transform: rotateZ(18deg); }
      23% { -webkit-transform: rotateZ(-16deg); }
      25% { -webkit-transform: rotateZ(14deg); }
      27% { -webkit-transform: rotateZ(-12deg); }
      29% { -webkit-transform: rotateZ(10deg); }
      31% { -webkit-transform: rotateZ(-8deg); }
      33% { -webkit-transform: rotateZ(6deg); }
      35% { -webkit-transform: rotateZ(-4deg); }
      37% { -webkit-transform: rotateZ(2deg); }
      39% { -webkit-transform: rotateZ(-1deg); }
      41% { -webkit-transform: rotateZ(1deg); }
      43% { -webkit-transform: rotateZ(0); }
      100% { -webkit-transform: rotateZ(0); }
    }

    @-moz-keyframes ring {
      0% { -moz-transform: rotate(0); }
      1% { -moz-transform: rotate(30deg); }
      3% { -moz-transform: rotate(-28deg); }
      5% { -moz-transform: rotate(34deg); }
      7% { -moz-transform: rotate(-32deg); }
      9% { -moz-transform: rotate(30deg); }
      11% { -moz-transform: rotate(-28deg); }
      13% { -moz-transform: rotate(26deg); }
      15% { -moz-transform: rotate(-24deg); }
      17% { -moz-transform: rotate(22deg); }
      19% { -moz-transform: rotate(-20deg); }
      21% { -moz-transform: rotate(18deg); }
      23% { -moz-transform: rotate(-16deg); }
      25% { -moz-transform: rotate(14deg); }
      27% { -moz-transform: rotate(-12deg); }
      29% { -moz-transform: rotate(10deg); }
      31% { -moz-transform: rotate(-8deg); }
      33% { -moz-transform: rotate(6deg); }
      35% { -moz-transform: rotate(-4deg); }
      37% { -moz-transform: rotate(2deg); }
      39% { -moz-transform: rotate(-1deg); }
      41% { -moz-transform: rotate(1deg); }
      43% { -moz-transform: rotate(0); }
      100% { -moz-transform: rotate(0); }
    }
    @keyframes ring {
      0% { transform: rotate(0); }
      1% { transform: rotate(30deg); }
      3% { transform: rotate(-28deg); }
      5% { transform: rotate(34deg); }
      7% { transform: rotate(-32deg); }
      9% { transform: rotate(30deg); }
      11% { transform: rotate(-28deg); }
      13% { transform: rotate(26deg); }
      15% { transform: rotate(-24deg); }
      17% { transform: rotate(22deg); }
      19% { transform: rotate(-20deg); }
      21% { transform: rotate(18deg); }
      23% { transform: rotate(-16deg); }
      25% { transform: rotate(14deg); }
      27% { transform: rotate(-12deg); }
      29% { transform: rotate(10deg); }
      31% { transform: rotate(-8deg); }
      33% { transform: rotate(6deg); }
      35% { transform: rotate(-4deg); }
      37% { transform: rotate(2deg); }
      39% { transform: rotate(-1deg); }
      41% { transform: rotate(1deg); }
      43% { transform: rotate(0); }
      100% { transform: rotate(0); }
    }
    @media (max-width: 766px) {
        .navlogo img {
            margin-right: 110px;
        }
    }
    .modal .select2-container {
        width: 100% !important;
    }
</style>

<?php
    if ($this->session->userdata('BranchRole')) {
        $business_details = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'CompId' => $this->session->userdata('CompId'), 'BranchUserId' => $this->session->userdata('UserId')]);
        $business_details->state = $business_details->State;
    } else {
        $business_details = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('CompId'), 'user_id' => $this->session->userdata('UserId')]);    
    }
    $business_details->state_details = $this->db->where('id', $business_details->state)->get('state_list')->row();

?>
<script type="text/javascript">
    let BUSINESS_DETAILS = <?= json_encode($business_details); ?>; 
    let GST_ACCESS_TOKEN = "<?= GST_ACCESS_TOKEN ?>"; 
</script>