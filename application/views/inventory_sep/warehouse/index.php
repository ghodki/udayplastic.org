<style type="text/css">
    .dt-buttons {
        left: 15.5%;
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
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="card-title">
                                Warehouse Information
                            </h3>
                        </div>
                        <div class="col-md-12">
                            <?php if (empty($id)) { ?>
                            <form action="<?= base_url(); ?>warehouse/create" method="POST" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" placeholder="Name" required="" />
                                        <span class="text-danger"><?= form_error('name'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="location">Location <span class="text-danger">*</span></label>
                                        <input text="text" name="location" value="<?= set_value('location'); ?>" id="location" class="form-control" placeholder="Location" required="" />
                                        <span class="text-danger"><?= form_error('location'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="contact_person">Contact Person</label>
                                        <input type="text" name="contact_person" value="<?= set_value('contact_person'); ?>" id="contact_person" class="form-control" placeholder="Contact Person" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="tel" name="mobile" value="<?= set_value('mobile'); ?>" id="mobile" class="form-control" placeholder="Mobile" minlength="10" maxlength="10" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control" placeholder="Email" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="remark">Remark</label>
                                        <textarea name="remark" id="remark" class="form-control" placeholder="Remark" rows="1"><?= set_value('remark'); ?></textarea>
                                    </div>
                                    <div class="col-md-3 text-center form-group" style="margin-top: 2rem;">
                                        <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                        <?php } else { ?>
                                        <span class="text-danger">Your license has been expired,<br />Please contact your administrator.</span>
                                        <?php } } else { ?>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                        <?php } ?>
                                        <button type="reset" class="btn btn-danger btn-sm">
                                            <i class="fa fa-refresh"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <?php } else { ?>
                            <form action="<?= base_url(); ?>warehouse/update/<?= $warehousedata->id; ?>" method="POST" autocomplete="off">
                                <input type="hidden" name="id" value="<?= $warehousedata->id; ?>" />
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="<?= set_value('name', $warehousedata->name); ?>" id="name" class="form-control" placeholder="Name" required="" />
                                        <span class="text-danger"><?= form_error('name'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="location">Location <span class="text-danger">*</span></label>
                                        <input text="text" name="location" value="<?= set_value('location', $warehousedata->location); ?>" id="location" class="form-control" placeholder="Location" required="" />
                                        <span class="text-danger"><?= form_error('location'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="contact_person">Contact Person</label>
                                        <input type="text" name="contact_person" value="<?= set_value('contact_person', $warehousedata->contact_person); ?>" id="contact_person" class="form-control" placeholder="Contact Person" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="tel" name="mobile" value="<?= set_value('mobile', $warehousedata->mobile); ?>" id="mobile" class="form-control" placeholder="Mobile" minlength="10" maxlength="10" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" value="<?= set_value('email', $warehousedata->email); ?>" id="email" class="form-control" placeholder="Email" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="remark">Remark</label>
                                        <textarea name="remark" id="remark" class="form-control" placeholder="Remark" rows="1"><?= set_value('remark', $warehousedata->remark); ?></textarea>
                                    </div>
                                    <div class="col-md-3 text-center form-group" style="margin-top: 2rem;">
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fa fa-save"></i> Update
                                        </button>
                                        <a href="<?= base_url(); ?>invent/warehouse" class="btn btn-danger btn-sm">
                                            <i class="fa fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>                            
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card-boby">
                    <h5 class="text-center pt-1">Warehouse Report</h5>
                    <div class="table-responsive pt-3 p-1">
                        <table class="table table-bordered table-condensed dataTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Contact Person</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (!empty($warehouses)) {
                                        $n = 0;
                                        foreach ($warehouses as $key => $data) {
                                            $n++;
                                ?>
                                <tr>
                                    <td><?= $n; ?></td>
                                    <td><?= $data->name; ?></td>
                                    <td><?= $data->location; ?></td>
                                    <td><?= $data->contact_person; ?></td>
                                    <td><?= $data->mobile; ?></td>
                                    <td><?= $data->email; ?></td>
                                    <td><?= $data->remark; ?></td>
                                    <td>
                                        <?php if ($data->status) { ?>
                                        <a href="<?= base_url(); ?>warehouse/changestatus/<?= $data->id; ?>" class="btn btn-success btn-sm" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-check-circle"></i> Active
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?= base_url(); ?>warehouse/changestatus/<?= $data->id; ?>" class="btn btn-danger btn-sm" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-ban"></i> Inactive
                                        </a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $data->created; ?></td>
                                    <td><?= $data->updated; ?></td>
                                    <td>
                                        <a href="<?= base_url('warehouse/edit/') . $data->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        &nbsp;
                                        <a href="<?= base_url('warehouse/delete/') . $data->id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script>