<style type="text/css">
    .dt-buttons {
        left: 20%;
    }
    #contentdata {
        top: 12%;
    }
    .modal-dialog {
        margin: 3.75rem auto;
    }
    .modal-header, .modal-body {
        padding: .5rem;
    }
    th, td {
        white-space: nowrap;
    }
    input[type="file"] {
        line-height: 1;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body pl-3 pr-3">
                            <h4 style="color: white;" class="text-center">Add About Us</h4>
                            <!-- Card stats -->
                            <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                                <?= $this->session->flashdata('msg'); ?>
                            </div>
                            <?php } ?>
                            <form method="POST" action="<?= base_url('aboutus/save'); ?>" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-lg-5">
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <textarea name="content" id="content" class="form-control form-control-sm" rows="4" placeholder="Content" required=""><?= set_value('content'); ?></textarea>
                                            <span class="text-danger"><?= form_error('content'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" name="image" id="image" class="form-control form-control-sm" title="Image" accept="image/*" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-lg-5">
                                        <div class="form-group">
                                            <label for="content2">Content After Image</label>
                                            <textarea name="content2" id="content2" class="form-control form-control-sm" rows="4" placeholder="Content After Image" required=""><?= set_value('content2'); ?></textarea>
                                            <span class="text-danger"><?= form_error('content2'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1 pb-1">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-xs">
                                            Save
                                        </button>
                                        <button type="reset" class="btn btn-danger btn-xs">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">About Us</h3>
                            <a href="<?= base_url(); ?>aboutus/certificate-gst" class="btn btn-primary btn-xs pull-right" title="Add Certificate & GST">
                                Add Certificate & GST
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="aboutus">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Content</th>
                                            <th>Image</th>
                                            <th>Content</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($aboutus) && $aboutus != '') {
                                                $n = 0;
                                                foreach ($aboutus as $key => $data) {
                                                    $n++;
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td>
                                                <?= word_limiter($data->content, 5); ?>
                                                <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="GetContent(<?= $data->id; ?>);" title="More Information">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <?php 
                                                    $imagefile = $data->image;
                                                    $image = 'uploads/about/' . $imagefile;
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
                                                <?= word_limiter($data->content2, 5); ?>
                                                <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="GetContent2(<?= $data->id; ?>);" title="More Information">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                            </td>
                                            <td><?= $data->created; ?></td>
                                            <td><?= $data->updated; ?></td>
                                            <td>
                                                <a href="<?= base_url('aboutus/edit/') . $data->id; ?>" class="btn btn-success btn-xs">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('aboutus/delete/') . $data->id; ?>" onclick="return confirm('Are you sure want to delete this record!');" class="btn btn-danger btn-xs">
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

<!-- Content data -->
<div class="modal fade" id="contentdata" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
<!-- Content end -->

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap3.2.1.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#aboutus").DataTable({
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });
    
    const GetContent = (Id) => {
        jQuery.noConflict();
        $("#contentdata .modal-dialog .modal-content").load("<?= base_url(); ?>" + "aboutus/contentdata/" + Id);
        $("#contentdata").modal("show");
        $("#contentdata").css("display", "block");
        $("#contentdata").css("opacity", "1");
    };
    
    const GetContent2 = (Id) => {
        jQuery.noConflict();
        $("#contentdata .modal-dialog .modal-content").load("<?= base_url(); ?>" + "aboutus/content2data/" + Id);
        $("#contentdata").modal("show");
        $("#contentdata").css("display", "block");
        $("#contentdata").css("opacity", "1");
    };
</script>