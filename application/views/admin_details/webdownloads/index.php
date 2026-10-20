<style type="text/css">
    .dt-buttons {
        left: 20%;
    }
    .alert {
        margin-top: 50px;
    }
    th, td {
        white-space: nowrap;
    }
    #descriptiondata {
        top: 12%;
    }
    .modal-dialog {
        margin: 3.75rem auto;
    }
    .modal-header, .modal-body {
        padding: .5rem;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card mt-5">
                <div class="card-header">
                    <h3 class="card-title">
                        Downloads Report
                    </h3>
                    <a href="<?= base_url(); ?>webDownloads/create" class="btn btn-info btn-xs pull-right" title="Create Download">
                        <i class="fa fa-plus"></i> Create
                    </a>
                </div>
                <div class="card-boby">
                    <h5 class="text-center pt-1">Downloads Report</h5>
                    <div class="table-responsive pt-3 p-1">
                        <table class="table table-bordered table-condensed dataTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (!empty($downloads)) {
                                        $n = 0;
                                        foreach ($downloads as $key => $data) {
                                            $n++;
                                ?>
                                <tr>
                                    <td><?= $n; ?></td>
                                    <td><?= $data->name; ?></td>
                                    <td>
                                        <?php 
                                            $imagefile = $data->image;
                                            $image = 'uploads/webdownloads/' . $imagefile;
                                            if (file_exists($image) && !empty($imagefile)) {
                                        ?>
                                        <a href="<?= base_url() . '/' . $image; ?>" title="View in large" target="_blank">
                                            <img src="<?= base_url() . '/' . $image; ?>" alt="image" style="width: 50px; height: 50px; border-radius: 25px;" />
                                        </a>
                                        <?php } else { ?>
                                        <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-available" style="width: 50px; border-radius: 25px;" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $filefile = $data->file;
                                            $file = 'uploads/webdownloads/' . $filefile;
                                            if (file_exists($file) && !empty($filefile)) {
                                        ?>
                                        <a href="<?= base_url() . $file; ?>" class="btn btn-info btn-xs" title="View file" target="_blank">
                                            <i class="fa fa-external-link-alt"></i>
                                        </a>
                                        <?php } else { ?>
                                        <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-available" style="width: 50px; border-radius: 25px;" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($data->status) { ?>
                                        <a href="<?= base_url(); ?>webDownloads/changestatus/<?= $data->id; ?>" class="btn btn-success btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-check-circle"></i> Active
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?= base_url(); ?>webDownloads/changestatus/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-ban"></i> Inactive
                                        </a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $data->created; ?></td>
                                    <td><?= $data->updated; ?></td>
                                    <td>
                                        <a href="<?= base_url('webDownloads/edit/') . $data->id; ?>" class="btn btn-success btn-xs" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        &nbsp;
                                        <a href="<?= base_url('webDownloads/delete/') . $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<script type="text/javascript">
    $(function() {
        $(".dataTable").DataTable({
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });
</script>