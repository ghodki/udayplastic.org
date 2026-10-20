<style type="text/css">
    .form-group {
        margin-bottom: 0.5rem;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-pink card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-pink">
                                    Raw Material Report
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>RawMaterial/Create" class="btn btn-xs bg-pink pull-right" title="Raw Material Create">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="bg-pink">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Product</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($RawMaterials) && !empty($RawMaterials)) {
                                                        $Sr = 0;
                                                        foreach ($RawMaterials as $key => $Data) { 
                                                            $Sr++;
                                                ?>
                                                <tr>
                                                    <td><?= $Sr; ?></td>
                                                    <td><?= get_title('product', ['id' => $Data->ProductId], 'p_name'); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-primary btn-xs" onclick="onRawMaterialInfoHandler(<?= $Data->Id; ?>);" title="View">
                                                            <i class="fa fa-info-circle"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>RawMaterial/Edit/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>RawMaterial/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to delete this record!');" title="Delete">
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
    </div>
    <!-- /.content -->
 </div>

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<?php include('RawMaterialJs.php'); ?>

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script> 