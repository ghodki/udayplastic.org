<!DOCTYPE html>
<html lang=en-in dir=ltr>
    <head>
        <title>Generate QR</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="Generate QR" />
        <meta name="copyright" content="Generate QR" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="keywords" content="Generate QR" />
        <meta name="description" content="Generate QR" />
        
        <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>web-assets/favicon.ico" />
        <link href="<?= base_url(); ?>web-assets/css/bootstrap.min.css" rel=stylesheet />
        <link href="<?= base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet"/>
        <link href="<?= base_url(); ?>web-assets/css/animate.css" rel=stylesheet />
        <link href="<?= base_url(); ?>web-assets/css/style.css" rel=stylesheet />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.dataTables.min.css">
        
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $(".DataTable").DataTable({
                    dom: 'lBfrtip',
                    lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]]
                });
            });
            
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert').hide(1000, function() {
                        $(this).remove();
                    });
                }, 1000);
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12 col-xxl-12 col-xl-12 mt-2">
                    <div class="card">
                        <form action="<?= base_url(); ?>GenerateQR/Create" method="POST">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-success">
                                        Generate QR
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xxl-6 col-xl-6">
                                        <div class="form-group">
                                            <label for="URL">URL <span class="text-danger">*</span></label>
                                            <input type="url" name="URL" value="<?= set_value('URL'); ?>" id="URL" class="form-control" placeholder="URL" required="" />
                                            <span class="text-danger"><?= form_error('URL'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success btn-sm" value="Generate" />
                                <input type="reset" class="btn btn-danger btn-sm" value="Reset" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-lg-12 col-xxl-12 col-xl-12 mt-2">
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                </div>
                <div class="col-md-12 col-sm-12 col-lg-12 col-xxl-12 col-xl-12 mt-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-primary">
                                    QR Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xxl-12 col-xl-12">
                                    <div class="table-reponsive">
                                        <table class="table table-bordered DataTable">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">URL</th>
                                                    <th class="text-white">QR</th>
                                                    <th class="text-white">Created</th>
                                                    <th class="text-white"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($QRData) && !empty($QRData)) {
                                                        $Sr = 0;
                                                        foreach ($QRData as $key => $Data) {
                                                            $Sr++;
                                                ?>
                                                <tr>
                                                    <td><?= $Sr; ?></td>
                                                    <td>
                                                        <a href="<?= $Data->URL; ?>" title="URL" target="_blank">
                                                            <?= $Data->URL; ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if (file_exists($Data->QRImage) && !empty($Data->QRImage)) {
                                                        ?>
                                                        <img src="<?= base_url() . $Data->QRImage; ?>" alt="QRImage" />
                                                        <?php } else { ?>
                                                        <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 30px; border-radius: 25px;" />
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= $Data->Created; ?></td>
                                                    <td>
                                                        <a href="<?= base_url(); ?>GenerateQR/Edit/<?= $Data->Id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>GenerateQR/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>