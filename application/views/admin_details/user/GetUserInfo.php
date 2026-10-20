            <form action="<?= base_url('userlist/UpdateLicense'); ?>" method="POST" autocomplete="off">
                <input type="hidden" name="id" value="<?= $UserInfo->id; ?>" />
                <div class="modal-header">
                    <h5 class="modal-title" id="title">User Info</h5>
                    <button type="button" class="close" onclick="CloseModal();">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="name" class="label label-default">Name</label>
                            <input type="text" name="name" value="<?= set_value('name', $UserInfo->name); ?>" id="name" class="form-control form-control-sm" placeholder="Name" readonly="" />
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="label label-default">Email</label>
                            <input type="email" name="email" value="<?= set_value('name', $UserInfo->email); ?>" id="email" class="form-control form-control-sm" placeholder="Email" readonly="" />
                        </div>
                        <div class="col-md-4">
                            <label for="mobile" class="label label-default">Mobile</label>
                            <input type="text" name="mobile" value="<?= set_value('mobile', $UserInfo->number); ?>" id="mobile" class="form-control form-control-sm" placeholder="Mobile" readonly="" />
                        </div>
                        <div class="col-md-4">
                            <label for="created" class="label label-default">Registered</label>
                            <input type="text" name="created" value="<?= set_value('created', $UserInfo->created); ?>" id="created" class="form-control form-control-sm" placeholder="Created" readonly="" />
                        </div>
                        <div class="col-md-4">
                            <label for="days" class="label label-default">Licensed Expire Days</label>
                            <?php 
                                if (strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($UserInfo->expiry)))) { 
                                    $Days = DifferenceFromDates($UserInfo->expiry);
                                } else {
                                    $Days = "-" . DifferenceFromDates($UserInfo->expiry);
                                }
                            ?>
                            <input type="text" name="days" value="<?= $Days; ?>" id="days" class="form-control form-control-sm" placeholder="Days" readonly="" />
                        </div>
                        <div class="col-md-4">
                            <label for="expiry" class="label label-default">Expiry</label>
                            <?php $expiry = date('d-m-Y', strtotime(date('d-m-Y', strtotime(date('d-m-Y'))) . ' + 1 year')); ?>
                            <input type="date" name="expiry" value="<?= set_value('expiry', date('Y-m-d', strtotime($expiry))); ?>" id="expiry" class="form-control form-control-sm" title="Expiry" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <button type="button" class="btn btn-danger" onclick="CloseModal();">Cancel</button>
                </div>
            </form>