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
    .modal-dialog {
        margin: 3.75rem auto;
    }
    .modal-header, .modal-body {
        padding: .5rem;
    }
    .modal {
        overflow: auto;
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
                        Products Report
                    </h3>
                    <a href="<?= base_url(); ?>webProducts/create" class="btn btn-info btn-xs pull-right" title="Create Product">
                        <i class="fa fa-plus"></i> Create
                    </a>
                </div>
                <div class="card-boby">
                    <div class="table-responsive pt-3 p-1">
                        <table class="table table-bordered table-condensed dataTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>URL Name</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (!empty($products)) {
                                        $n = 0;
                                        foreach ($products as $key => $data) {
                                            $n++;
                                ?>
                                <tr>
                                    <td><?= $n; ?></td>
                                    <td><?= $data->name; ?></td>
                                    <td><?= $data->url_name; ?></td>
                                    <td>
                                        <?php 
                                            $imagefile = $data->image;
                                            $image = 'uploads/webproducts/' . $imagefile;
                                            if (file_exists($image) && !empty($imagefile)) {
                                        ?>
                                        <a href="<?= base_url() . '/' . $image; ?>" title="View in large" target="_blank">
                                            <img src="<?= base_url() . '/' . $image; ?>" alt="product" style="width: 50px; height: 50px; border-radius: 25px;" />
                                        </a>
                                        <?php } else { ?>
                                        <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-availabl" style="width: 50px; border-radius: 25px;" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?= word_limiter($data->description, 5); ?>
                                        <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="GetInformation(<?= $data->id; ?>);" title="More Information">
                                            <i class="fa fa-info-circle"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($data->status) { ?>
                                        <a href="<?= base_url(); ?>webproducts/changestatus/<?= $data->id; ?>" class="btn btn-success btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-check-circle"></i> Active
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?= base_url(); ?>webproducts/changestatus/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-ban"></i> Inactive
                                        </a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $data->created; ?></td>
                                    <td><?= $data->updated; ?></td>
                                    <td>
                                        <a href="<?= base_url('webProducts/edit/') . $data->id; ?>" class="btn btn-success btn-xs" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        &nbsp;
                                        <a href="<?= base_url('webProducts/delete/') . $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<!-- Description data -->
<div class="modal fade" id="descriptiondata" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
<!-- Description end -->

<script type="text/javascript">
    $(function() {
        $(".dataTable").DataTable({
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });
    
    const GetInformation = (Id) => {
        $("#descriptiondata .modal-dialog .modal-content").load("<?= base_url(); ?>" + "webProducts/descriptiondata/" + Id);
        $("#descriptiondata").addClass("show");
        $("#descriptiondata").css("display", "block");
    };
    
    const CloseModal = () => {
        $("#descriptiondata").removeClass("show");
        $("#descriptiondata").css("display", "none");
    };
</script>