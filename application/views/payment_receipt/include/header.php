<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>Uday Plastic - Payment Receipt</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
        <!-- Favicons -->
        <link rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url(); ?>plugins/fontawesome-free/css/all.min.css" />
        <!-- Vendor CSS Files -->
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/vendor/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/css/bootstrap.min2.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/vendor/bootstrap-icons/bootstrap-icons.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/vendor/boxicons/css/boxicons.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/toastr.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/vendor/quill/quill.snow.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/vendor/quill/quill.bubble.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/vendor/remixicon/remixicon.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/css/datatables.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>crm/assets/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/select2.min.css" />
        
        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/bootstrap.min.js"></script>
        <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
        
        <style type="text/css">
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
            .dataTables_length {
                margin-top: -30px;
            }
            .select2-container .select2-selection--single {
                height: 38px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 38px;
            }
        </style>
    </head>
    <body>