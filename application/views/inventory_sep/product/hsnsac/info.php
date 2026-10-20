<div class="modal-header">
    <h5 class="modal-title">Hsn/Sac Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="bg-danger">
                        <tr>
                            <th class="text-white">Column</th>
                            <th class="text-white">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Hsn/Sac Code</th>
                            <td><?= $hsnSacData->hsnSacCode; ?></td>
                        </tr>
                        <tr>
                            <th>Hsn/Sac Description</th>
                            <td><?= $hsnSacData->hsnSacDescription; ?></td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><?= $hsnSacData->type; ?></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $hsnSacData->created; ?></td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td><?= $hsnSacData->createdBy; ?></td>
                        </tr>
                        <tr>
                            <th>Updated</th>
                            <td><?= $hsnSacData->updated; ?></td>
                        </tr>
                        <tr>
                            <th>Updated</th>
                            <td><?= $hsnSacData->updatedBy; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
</div>