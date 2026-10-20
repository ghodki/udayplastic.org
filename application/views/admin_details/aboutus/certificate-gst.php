<style type="text/css">
    .dt-buttons {
        left: 20%;
    }
    input[type="file"] {
        line-height: 1;
    }
    th, td {
        white-space: nowrap;
    }
</style>
    
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body pl-3 pr-3">
                            <a href="<?= base_url(); ?>aboutus/index" class="btn btn-danger btn-xs pull-right mt-2" title="Back to About Us">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                            <h4 style="color: white;" class="text-center">Add Certificate</h4>
                            <form method="POST" action="<?= base_url('aboutus/certificate'); ?>" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-lg-5">
                                        <div class="form-group">
                                            <label for="certificate">Certificate</label>
                                            <input type="file" name="certificate" id="certificate" class="form-control form-control-sm" accept="image/*" title="Certificate" required="" />
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
                    <div class="card card-primary card-outline pl-3 pr-3">
                        <div class="card-header">
                            <h3 class="card-title">Certificates</h3>
                        </div>
                        <div class="card-boby">
                            <div class="table-responsive">
                                <table class="table table-bordered certificate">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Certificate</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th></th>
                                        </tr>
                                        <?php if (!empty($certificates)) { $n = 0; foreach ($certificates as $key => $cdata) { $n++; ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td>
                                                <?php 
                                                    $cmagefile = $cdata->certificate;
                                                    $cimage = 'uploads/about/' . $cmagefile;
                                                    if (file_exists($cimage) && !empty($cmagefile)) {
                                                ?>
                                                <a href="<?= base_url() . '/' . $cimage; ?>" title="View in large" target="_blank">
                                                    <img src="<?= base_url() . '/' . $cimage; ?>" alt="image" style="width: 50px; height: 50px; border-radius: 25px;" />
                                                </a>
                                                <?php } else { ?>
                                                <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-available" style="width: 50px; border-radius: 25px;" />
                                                <?php } ?>
                                            </td>
                                            <td><?= $cdata->created; ?></td>
                                            <td><?= $cdata->updated; ?></td>
                                            <td>
                                                <a href="<?= base_url('aboutus/certificate-edit/') . $cdata->id; ?>" class="btn btn-success btn-xs">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('aboutus/certificate-delete/') . $cdata->id; ?>" onclick="return confirm('Are you sure want to delete this record!');" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body pl-3 pr-3">
                            <h4 style="color: white;" class="text-center">Add GST</h4>
                            <form method="POST" action="<?= base_url('aboutus/gst'); ?>" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="cname">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" name="cname" value="<?= set_value('cname'); ?>" id="cname" class="form-control form-control-sm" placeholder="Company Name" required="" />
                                            <span class="text-danger"><?= form_error('cname'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="state">State <span class="text-danger">*</span></label>
                                            <input type="text" name="state" value="<?= set_value('state'); ?>" id="state" class="form-control form-control-sm" placeholder="State" required="" />
                                            <span class="text-danger"><?= form_error('state'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="location">Location <span class="text-danger">*</span></label>
                                            <input type="text" name="location" value="<?= set_value('location'); ?>" id="location" class="form-control form-control-sm" placeholder="Location" required="" />
                                            <span class="text-danger"><?= form_error('location'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="offices">Offices/Plant <span class="text-danger">*</span></label>
                                            <input type="text" name="offices" value="<?= set_value('offices'); ?>" id="offices" class="form-control form-control-sm" placeholder="Offices/Plant" required="" />
                                            <span class="text-danger"><?= form_error('offices'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="provisional_id">Provisional ID <span class="text-danger">*</span></label>
                                            <input type="text" name="provisional_id" value="<?= set_value('provisional_id'); ?>" id="provisional_id" class="form-control form-control-sm" placeholder="Provisional ID" required="" />
                                            <span class="text-danger"><?= form_error('provisional_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="address">Address <span class="text-danger">*</span></label>
                                            <textarea name="address" id="address" class="form-control form-control-sm" rows="1" placeholder="Address" required=""><?= set_value('address'); ?></textarea>
                                            <span class="text-danger"><?= form_error('address'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="arn_copy">ARN Copy</label>
                                            <input type="file" name="arn_copy" id="arn_copy" class="form-control form-control-sm" title="ARN Copy" />
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
                    <div class="card card-primary card-outline pl-3 pr-3">
                        <div class="card-header">
                            <h3 class="card-title">GST Info</h3>
                        </div>
                        <div class="card-boby">
                            <div class="table-responsive">
                                <table class="table table-bordered certificate">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>State</th>
                                            <th>Location</th>
                                            <th>Office</th>
                                            <th>Provisional Id</th>
                                            <th>Address</th>
                                            <th>ARN Copy</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th></th>
                                        </tr>
                                        <?php if (!empty($GSTInfo)) { $n = 0; foreach ($GSTInfo as $key => $gstdata) { $n++; ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $gstdata->cname; ?></td>
                                            <td><?= $gstdata->state; ?></td>
                                            <td><?= $gstdata->location; ?></td>
                                            <td><?= $gstdata->offices; ?></td>
                                            <td><?= $gstdata->provisional_id; ?></td>
                                            <td><?= $gstdata->address; ?></td>
                                            <td>
                                                <?php 
                                                    $arnmagefile = $gstdata->arn_copy;
                                                    $arnimage = 'uploads/about/' . $arnmagefile;
                                                    if (file_exists($arnimage) && !empty($arnmagefile)) {
                                                ?>
                                                <a href="<?= base_url() . $arnimage; ?>" class="btn btn-primary btn-xs" title="View in large" target="_blank">
                                                    <i class="fa fa-external-link-alt"></i>
                                                </a>
                                                <?php } else { ?>
                                                <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-available" style="width: 50px; border-radius: 25px;" />
                                                <?php } ?>
                                            </td>
                                            <td><?= $gstdata->created; ?></td>
                                            <td><?= $gstdata->updated; ?></td>
                                            <td>
                                                <a href="<?= base_url('aboutus/gst-edit/') . $gstdata->id; ?>" class="btn btn-success btn-xs">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('aboutus/gst-delete/') . $gstdata->id; ?>" onclick="return confirm('Are you sure want to delete this record!');" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </thead>
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
        $(".certificate").DataTable({
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });
</script>