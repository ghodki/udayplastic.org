<div class="modal-header">
    <h5 class="modal-title">Request Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed request-data">
                    <tr>
                        <th>Date</th>
                        <td><?= !empty($rewardRequestInfo->date) ? date('d-m-Y', strtotime($rewardRequestInfo->date)) : ''; ?></td>
                        <th>Name</th>
                        <td><?= is_object($electricianData ?? null) ? ($electricianData->name ?? '') : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?= is_object($electricianData ?? null) ? ($electricianData->mobile ?? '') : ''; ?></td>
                        <th>UPI Id</th>
                        <td><?= is_object($electricianData ?? null) ? ($electricianData->upiId ?? '') : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Reward Points</th>
                        <td><?= $rewardRequestInfo->rewardPoints ?? ''; ?></td>
                        <th>Status</th>
                        <td>
                            <?php if (($rewardRequestInfo->status ?? '') == '0') { ?>
                            <label class="badge badge-warning">
                                <i class="fa fa-exclamation text-white"></i> Pending
                            </label>
                            <?php } elseif (($rewardRequestInfo->status ?? '') == '1') { ?>
                            <label class="badge badge-success">
                                <i class="fa fa-check-circle text-white"></i> Approved
                            </label>
                            <?php } elseif (($rewardRequestInfo->status ?? '') == '2') { ?>
                            <label class="badge badge-danger">
                                <i class="fa fa-ban text-white"></i> Rejected
                            </label>    
                            <?php } ?>                   
                        </td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td><?= $rewardRequestInfo->remark ?? ''; ?></td>                        
                        <th>Admin Remark</th>
                        <td><?= $rewardRequestInfo->adminRemark ?? ''; ?></td>                        
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $rewardRequestInfo->created; ?></td>
                        <th>Created By</th>
                        <td><?= $rewardRequestInfo->createdBy; ?></td>
                    </tr>
                    <tr>
                        <th>Updated By</th>
                        <td><?= $rewardRequestInfo->updatedBy; ?></td>
                        <th>Updated</th>
                        <td><?= $rewardRequestInfo->updated; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <hr class="bg-purple mt-2" />
        <div class="col-md-12">
            <h5 class="text-navy">As formula set electrician request cost blow:</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Reward Points</th>
                            <th>Reward Point & Cost</th>
                            <th>Reward Point Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                                $rowPoints = (float) ($rewardRequestInfo->rewardPoints ?? 0);
                                $pointCost = (float) (is_object($rewardPointCostData ?? null) ? ($rewardPointCostData->rewardPoint ?? 0) : 0);
                                $pointRupee = (float) (is_object($rewardPointCostData ?? null) ? ($rewardPointCostData->equivalentRupee ?? 0) : 0);
                                $rowAmount = ($pointCost > 0) ? (($rowPoints / $pointCost) * $pointRupee) : 0;
                            ?>
                            <td><?= $rowPoints; ?></td>
                            <td><?= $pointCost; ?> Points = <i class="fa fa-inr"></i> <?= $pointRupee; ?></td>
                            <td>
                                <i class="fa fa-inr"></i> 
                                <?= indianMoneyFormat($rowAmount); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>            
            </div>
        </div>
        
        <?php if ($rewardRequestInfo->status == '0') { ?>
        <hr class="bg-purple mt-2" />
        <div class="col-md-12">
            <form action="<?= base_url(); ?>Reports/withdrawalRequestUpdate" method="POST">
                <input type="hidden" name="requestId" value="<?= set_value('requestId', $rewardRequestInfo->id);?>" id="requestId" />
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-lg-3">
                        <div class="form-group">
                            <label for="transactionDate">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" name="transactionDate" value="<?= set_value('transactionDate', date('Y-m-d')); ?>" id="transactionDate" class="form-control form-control-sm" title="Transaction Date" required="" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <br />
                            <input type="radio" name="status" value="1" <?= set_radio('status', 1); ?> id="Approved" checked="" title="Approved" required="" />
                            <label>Approved</label>
                            <br />
                            <input type="radio" name="status" value="2" <?= set_radio('status', 2); ?> id="Rejected" title="Rejected" required="" />
                            <label>Rejected</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <div class="form-group">
                            <label for="adminRemark">Remark</label>
                            <textarea name="adminRemark" id="adminRemark" class="form-control form-control-sm" placeholder="Remark" rows="2"><?= set_value('Remark'); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3 mt-4">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Submit" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php } else { ?>
        <h3 class="text-navy">Request updated.</h3>
        <?php } ?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>