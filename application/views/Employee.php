<!DOCTYPE html>
<html lang="en-in" dir="ltr">
    <head>
        <?php include('inventory_sep/common/library.php'); ?>
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>dist/css/select2.min.css" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.dataTables.min.css" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/buttons.dataTables.min.css">
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>dist/css/adminlte.min.css" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/choices.min.css" />
        <style type="text/css">
            .password-container {
                position: relative;
                margin-bottom: 20px;
            }
            .password-input {
                padding-right: 30px;
            }
            .password-toggle-btn {
                transform: translateY(-50%);
                cursor: pointer;
                position: relative !important;
                left: 280px !important;
                right: 0px;
                bottom: 20px;
                border-right: 0px !important;
                top: -19px !important;
            }
            .password-toggle-btn:hover {
                color: #333;
            }
            @media (max-width: 400px) {
                .password-toggle-btn {
                    left: 320px !important;
                }
            }
            
            @media (min-width: 992px) {
                .navbar-expand-lg .navbar-nav .nav-link {
                    padding-right: 0.5rem;
                    padding-left: 0.5rem;
                }
            }
            span.text-danger>p{
                color: #dc3545!important;
            }
            .form-group {
                margin-bottom: 0.25rem;
            }
            .navbar .container-fluid {
                display: contents;
            }
            thead.bg-purple>tr>th,
            thead.bg-maroon>tr>th,
            thead.bg-navy>tr>th,
            thead.bg-fuchsia>tr>th,
            thead.bg-pink>tr>th,
            thead.bg-success>tr>th,
            thead.bg-dark>tr>th,
            tr.bg-pink>th {
                color: #ffffff;
            }
            .navbar-dark {
                background-color: unset;
            }
            .navbar-dark .navbar-nav .nav-link {
                color: rgba(255,255,255,1);
            }
            .dt-buttons {
                left: 20%;
            }
            .choices__inner {
                background-color: #ffffff;
                min-height: 15px;
                padding: 1.5px 7.5px 3px
            }
            .choices__input {
                background-color: #ffffff;
                padding: 0px 0 1px 2px;
            }
            .choices__input {
                margin-bottom: 0px;
            }
        </style>
    </head>
    <body>
        <?php 
            include('inventory_sep/common/inner-header.php'); 
            
            include($page . '.php'); 
        ?>
        
        <footer class="border-top bg-dark">
            <div class="container-fluid">
                <p style="font-size: 14px; color: #fff;" class="text-center mb-0 py-3">
                    Copyright © 2023 Uday Plastic. All rights reserved.
                </p>
            </div>
        </footer>

        <script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/choices.min.js"></script>    
        <script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.colVis.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/owl.carousel.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/wow.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/script.js"></script>
        
        <?php include('inventory_sep/Employee/EmployeeScript.php'); ?>
    </body>
</html>