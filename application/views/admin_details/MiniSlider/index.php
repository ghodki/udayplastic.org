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
                    <form method="POST" action="<?= base_url(); ?>MiniSlider/Save" enctype="multipart/form-data">
                        <div class="card card-outline card-purple">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-purple">
                                        Create
                                    </h4>
                                </div>
                            </div>
                            <div class="card-body">                                
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Image">Image <span class="text-danger">*</span></label>
                                            <input type="file" name="Image" id="Image" class="form-control form-control-sm" title="Image" accept="image/*" required="" />
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Save
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-maroon">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-fuchsia">
                                    Mini Slider
                                </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-maroon">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Image</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($MiniSliders) && $MiniSliders != '') {
                                                $Sr = 0;
                                                foreach ($MiniSliders as  $key => $data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <?php 
                                                    $Imagefile = $data->Image;
                                                    $Image = 'uploads/MiniSlider/' . $Imagefile;
                                                    if (file_exists($Image) && !empty($Imagefile)) {
                                                ?>
                                                <img src="<?= base_url() . '/' . $Image; ?>" alt="Photograph" style="width: 50px; border-radius: 25px;" />
                                                <?php } else { ?>
                                                <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 50px; border-radius: 25px;" />
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url(); ?>MiniSlider/Edit/<?= $data->Id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>MiniSlider/Delete/<?= $data->Id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "print", "pageLength"]
        });
    });
</script>