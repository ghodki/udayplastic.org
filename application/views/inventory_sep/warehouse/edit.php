            <form action="<?= base_url('warehouse/updateWarehouse'); ?>" method="POST" autocomplete="off">
                <input type="hidden" name="id" value="<?= $warehousedata->id; ?>" />
                <div class="modal-header">
                    <h5 class="modal-title" id="title">Update Warehouse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="<?= set_value('name', $warehousedata->name); ?>" id="name" class="form-control" placeholder="Name" required="" />
                            <span class="text-danger"><?= form_error('name'); ?></span>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="location">Location <span class="text-danger">*</span></label>
                            <input text="text" name="location" value="<?= set_value('location', $warehousedata->location); ?>" id="location" class="form-control" placeholder="Location" required="" />
                            <span class="text-danger"><?= form_error('location'); ?></span>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="contact_person">Contact Person</label>
                            <input type="text" name="contact_person" value="<?= set_value('contact_person', $warehousedata->contact_person); ?>" id="contact_person" class="form-control" placeholder="Contact Person" />
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="mobile">Mobile</label>
                            <input type="tel" name="mobile" value="<?= set_value('mobile', $warehousedata->mobile); ?>" id="mobile" class="form-control" placeholder="Mobile" minlength="10" maxlength="10" />
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" value="<?= set_value('email', $warehousedata->email); ?>" id="email" class="form-control" placeholder="Email" />
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="remark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" placeholder="Remark" rows="1"><?= set_value('remark', $warehousedata->remark); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>