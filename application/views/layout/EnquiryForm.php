<form method="POST" onsubmit="onEnquiryFormSubmitHandler(event);" id="ProductEnquiryForm">
    <input type="hidden" name="ProductId" value="<?= set_value('ProductId', $ProductId); ?>" id="ProductId" />
    <div class="modal-header">
        <h5 class="modal-title">Enquiry</h5>
        <button type="button" class="close" onclick="onEnquiryFormCloseHandler();">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8 col-xs-8 col-xxl-8 col-sm-8 offset-2">
                <div class="form-group">
                    <label class="text-dark" for="Organization">Company/Organization Name <span class="text-danger">*</span></label>
                    <input type="text" name="Organization" value="<?= set_value('Organization'); ?>" id="Organization" class="form-control form-control-sm" placeholder="Company/Organization Name" required="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8 col-xs-8 col-xxl-8 col-sm-8 offset-2">
                <div class="form-group">
                    <label class="text-dark" for="ContactPerson">Contact Person <span class="text-danger">*</span></label>
                    <input type="text" name="ContactPerson" value="<?= set_value('ContactPerson'); ?>" id="ContactPerson" class="form-control form-control-sm" placeholder="Contact Person" required="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8 col-xs-8 col-xxl-8 col-sm-8 offset-2">
                <div class="form-group">
                    <label class="text-dark" for="Mobile">Mobile <span class="text-danger">*</span></label>
                    <input type="tel" name="Mobile" value="<?= set_value('Mobile'); ?>" id="Mobile" class="form-control form-control-sm" placeholder="Mobile" required="" minlength="10" maxlength="10" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8 col-xs-8 col-xxl-8 col-sm-8 offset-2">
                <div class="form-group">
                    <label class="text-dark" for="Email">Email</label>
                    <input type="text" name="Email" value="<?= set_value('Email'); ?>" id="Email" class="form-control form-control-sm" placeholder="Email" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8 col-xs-8 col-xxl-8 col-sm-8 offset-2">
                <div class="form-group">
                    <label class="text-dark" for="ProductName">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="ProductName" value="<?= set_value('ProductName', $ProductName); ?>" id="ProductName" class="form-control form-control-sm" placeholder="ProductName" required="" readonly="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8 col-xs-8 col-xxl-8 col-sm-8 offset-2">
                <div class="form-group">
                    <label class="text-dark" for="Quantity">Quantity <span class="text-danger">*</span></label>
                    <input type="tel" name="Quantity" value="<?= set_value('Quantity'); ?>" id="Quantity" class="form-control form-control-sm" placeholder="Quantity" required="" minlength="1" maxlength="5" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8 col-xs-8 col-xxl-8 col-sm-8 offset-2">
                <div class="form-group">
                    <label class="text-dark" for="Remark">Remark</label>
                    <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark"><?= set_value('Remark'); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success btn-xs" id="submitButton">Submit</button>
        <button type="reset" class="btn btn-danger btn-xs">Reset</button>
        <button type="button" class="btn btn-secondary btn-xs" onclick="onEnquiryFormCloseHandler();">Close</button>
    </div>
</form>