<form id="rewardPointCostFormEdit" method="POST" onsubmit="onRewardPointCostFormUpdateSubmitHandler(event);" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="rewardPointCostId" value="<?= set_value('rewardPointCostId', $rewardPointCostData->id); ?>" id="rewardPointCostId" />
    <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="rewardPoint">Reward Point <span class="text-danger">*</span></label>
                <input type="number" name="rewardPoint" value="<?= set_value('rewardPoint', $rewardPointCostData->rewardPoint); ?>" id="rewardPointEdit" class="form-control form-control-sm" placeholder="Reward Point" required="" autocomplete="off" />
            </div>
            <div class="col-md-6 form-group">
                <label for="equivalentRupee">Equivalent Rupee <span class="text-danger">*</span></label>
                <input type="number" name="equivalentRupee" value="<?= set_value('equivalentRupee', $rewardPointCostData->equivalentRupee); ?>" id="equivalentRupeeEdit" class="form-control form-control-sm" placeholder="Equivalent Rupee" required="" autocomplete="off" />
            </div>
            <div class="col-md-6 form-group">
                <label for="minimumWithdrawalAmount">Minimum Withdrawal Amount <span class="text-danger">*</span></label>
                <input type="number" name="minimumWithdrawalAmount" value="<?= set_value('minimumWithdrawalAmount', $rewardPointCostData->minimumWithdrawalAmount); ?>" id="minimumWithdrawalAmountEdit" class="form-control form-control-sm" placeholder="Minimum Withdrawal Amount" required="" autocomplete="off" />
            </div>
            <div class="col-md-6 form-group">
                <label for="catalogue">Catalogue</label>
                <input type="file" name="catalogue" id="catalogue" class="form-control form-control-sm" title="Catalogue" accept=".pdf,application/pdf" />
                <input type="hidden" name="oldCatalogue" value="<?= set_value('oldCatalogue', $rewardPointCostData->catalogue); ?>" id="oldCatalogue" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success btn-xs" id="updateButton">Make Changes</button>
        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
    </div>
</form>