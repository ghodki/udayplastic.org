<form onsubmit="onBrandUpdateFormHendler(event);" id="updateForm" autocomplete="off">
    <input type="hidden" name="brandId" value="<?= $brandData->id; ?>" id="brandId" />
    <div class="modal-header">
        <h5 class="modal-title">Brand Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="brandName">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="brandName" value="<?= $brandData->name; ?>" id="brandNameEdit" class="form-control form-control-sm" placeholder="Brand Name" required="" autocomplete="off" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" id="updateButton" class="btn btn-success btn-sm">Make Changes</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
    </div>
</form>