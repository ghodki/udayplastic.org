<form onsubmit="onHsnSacUpdateFormHendler(event);" id="updateForm" autocomplete="off">
    <input type="hidden" name="hsnSacId" value="<?= $hsnSacData->id; ?>" id="hsnSacId" />
    <div class="modal-header">
        <h5 class="modal-title">Hsn/Sac Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="hsnSacCode">Hsn/Sac Code <span class="text-danger">*</span></label>
                    <input type="text" name="hsnSacCode" value="<?= $hsnSacData->hsnSacCode; ?>" id="hsnSacCodeEdit" class="form-control form-control-sm" placeholder="Hsn/Sac Code" required="" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="hsnSacDescription">Hsn/Sac Description</label>
                    <textarea name="hsnSacDescription" id="hsnSacDescription" class="form-control form-control-sm" placeholder="Hsn/Sac Description"><?= $hsnSacData->hsnSacDescription; ?></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control form-control-sm" required="">
                        <option value="" disabled="" <?= $hsnSacData->type == '' ? 'selected' : ''; ?>>Select Type</option>
                        <option value="Sale" <?= $hsnSacData->type == 'Sale' ? 'selected' : ''; ?>>Sale</option>
                        <option value="Service" <?= $hsnSacData->type == 'Service' ? 'selected' : ''; ?>>Service</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" id="updateButton" class="btn btn-success btn-sm">Make Changes</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
    </div>
</form>