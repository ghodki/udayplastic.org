<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .modal-dialog {
        margin: 3.75rem auto;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-fuchsia">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-fuchsia">
                                    Members
                                </h4>
                            </div>
                            <a href="<?= base_url(); ?>Member/Create" class="btn btn-primary btn-sm pull-right" title="Create">
                                <i class="fa fa-plus-circle"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-fuchsia">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($Members) && $Members != '') {
                                                $Sr = 0;
                                                foreach ($Members as  $key => $data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-2 col-xs-2 col-lg-2 col-xx-2 col-sm-2 col-xxl-2">
                                                        <?php 
                                                            $photographfile = $data->Photograph;
                                                            $photograph = 'uploads/Member/' . $photographfile;
                                                            if (file_exists($photograph) && !empty($photographfile)) {
                                                        ?>
                                                        <img src="<?= base_url() . '/' . $photograph; ?>" alt="Photograph" style="width: 30px; border-radius: 25px;" />
                                                        <?php } else { ?>
                                                        <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 30px; border-radius: 25px;" />
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 col-lg-8 col-xx-8 col-sm-8 col-xxl-8">
                                                        <?= $data->FirstName . "&nbsp;" . $data->LastName; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $data->Designation; ?></td>
                                            <td><?= $data->Mobile; ?></td>
                                            <td><?= $data->Email; ?></td>
                                            <td>
                                                <?php if ($data->Status == 1) { ?>
                                                <a href="<?= base_url(); ?>Member/Status/<?= $data->Id; ?>" class="btn bg-navy btn-sm" title="Click to Inactive" onclick="return confirm('Are you sure want to change status!');">
                                                    <i class="fa fa-check-circle"></i> Active
                                                </a>
                                                <?php } elseif ($data->Status == 0) { ?>
                                                <a href="<?= base_url(); ?>Member/Status/<?= $data->Id; ?>" class="btn bg-maroon btn-sm" title="Click to Active" onclick="return confirm('Are you sure want to change status!');">
                                                    <i class="fa fa-ban"></i> Inactive
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="onGetMemberInfoHandler(<?= $data->Id; ?>);" class="btn btn-secondary btn-sm" title="Info">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Member/Edit/<?= $data->Id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Member/Delete/<?= $data->Id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap3.2.1.min.js"></script>
<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "print", "pageLength"]
        });
    });
    
    const onGetMemberInfoHandler = (Id) => {
        jQuery.noConflict();
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Member/Info/" + Id);
        $('#Info').modal('show');
        $("#Info").css("display", "block");
        $("#Info").css("opacity", "1");
    };
</script>