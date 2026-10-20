<style type="text/css">
    th, td {
        white-space: nowrap;
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
                    <div class="header bg-gradient-green">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h4 style="color: #fff; padding: 5px 0px 5px 0px;" class="text-center">
                                    Add Branch Address
                                </h4>
                                <form method="POST" action="<?= base_url(); ?>Branches/Save">
                                    <div class="row">
                                        <div class="col-md-2 col-sm-2 col-lg-2">
                                            <div class="form-group">
                                                <label for="Title">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="Title" value="<?= set_value('Title'); ?>" id="Title" class="form-control form-control-sm" placeholder="Title" required="" />
                                                <span class="text-danger"><?= form_error('Title'); ?></span>
                                            </div>                                        
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-lg-2">
                                            <div class="form-group">
                                                <label for="Phone">Phone <span class="text-danger">*</span></label>
                                                <input type="text" name="Phone" value="<?= set_value('Phone'); ?>" id="Phone" class="form-control form-control-sm" placeholder="Phone" required="" />
                                                <span class="text-danger"><?= form_error('Phone'); ?></span>
                                            </div>                                        
                                        </div>
                                        <div class="col-md-5 col-sm-5 col-lg-5">
                                            <div class="form-group">
                                                <label for="Address">Address <span class="text-danger">*</span></label>
                                                <textarea name="Address" id="Address" class="form-control form-control-sm" placeholder="Address" required=""><?= set_value('Address'); ?></textarea>
                                                <span class="text-danger"><?= form_error('Address'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-sm-5 col-lg-5">
                                            <div class="form-group">
                                                <label for="Location">Location</label>
                                                <textarea name="Location" id="Location" class="form-control form-control-sm" placeholder="Location"><?= set_value('Location'); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-2">
                                            <button type="submit" class="btn btn-primary">
                                                Save
                                            </button>
                                            <input type="reset" class="btn btn-danger" value="Reset" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-navy mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-navy">
                                    Branches
                                </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Title</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($Branches) && $Branches != '') {
                                                $Sr = 0;
                                                foreach ($Branches as  $key => $data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $data->Date; ?></td>
                                            <td><?= $data->Title; ?></td>
                                            <td><?= $data->Phone; ?></td>
                                            <td style="white-space: unset;"><?= $data->Address; ?></td>
                                            <td>
                                                <a href="<?= $data->Location; ?>" class="btn btn-primary btn-sm" title="Location" target="_blank">
                                                    <i class="fa fa-map-marker"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($data->Status == 1) { ?>
                                                <a href="<?= base_url(); ?>Branches/Status/<?= $data->Id; ?>" class="btn bg-navy btn-sm" title="Click to Inactive" onclick="return confirm('Are you sure want to change status!');">
                                                    <i class="fa fa-check-circle"></i> Active
                                                </a>
                                                <?php } elseif ($data->Status == 0) { ?>
                                                <a href="<?= base_url(); ?>Branches/Status/<?= $data->Id; ?>" class="btn bg-maroon btn-sm" title="Click to Active" onclick="return confirm('Are you sure want to change status!');">
                                                    <i class="fa fa-ban"></i> Inactive
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url(); ?>Branches/Edit/<?= $data->Id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Branches/Delete/<?= $data->Id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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