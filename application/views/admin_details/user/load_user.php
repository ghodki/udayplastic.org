<thead class="thead-light">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Number</th>
        <th>User Type</th>
        <th>Status</th>
        <th>Registered</th>
        <th>Fees</th>
        <th>Tenure</th>
        <th>Days</th>
        <th>Licensed</th>
        <th>Status</th>
        <th>Expiry</th>
        <th>Action</th>
    </tr>
</thead>
<tbody id="tableData">
    <?php
        if (isset($result) && $result != '') {
            foreach ($result as $key => $unknown) {
                $unknown = (object) $unknown;
    ?>
    <tr>
        <td><?= $key + 1 ?></td>
        <td>
            <a href="<?= base_url('userlist/logindirect/') . $unknown->id ?>">
                <?= $unknown->name; ?>
            </a>
        </td>
        <td><?= $unknown->email; ?></td>
        <td><?= $unknown->number; ?></td>
        <td>
            <?php
                if ($unknown->userType == 1) {
                    echo "Admin";
                } else {
                    if ($unknown->userType == 2) {
                        echo "Sub Admin";
                    } elseif ($unknown->userType == 3) {
                        echo "User";
                    } else {
                        echo "Employee";
                    }
                } 
            ?>
        </td>
        <td>
            <?php if ($unknown->status) { ?>
            <a href="<?= base_url(); ?>userlist/changestatus/<?= $unknown->id; ?>" class="btn btn-success btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                <i class="fa fa-check-circle"></i> Active
            </a>
            <?php } else { ?>
            <a href="<?= base_url(); ?>userlist/changestatus/<?= $unknown->id; ?>" class="btn btn-danger btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                <i class="fa fa-ban"></i> Inactive
            </a>
            <?php } ?>
        </td>
        <td><?= date('d-m-Y', strtotime($unknown->created)); ?></td>
        <td><i class="fa fa-inr"></i> <?= $unknown->license_cost; ?></td>
        <td><?= DifferenceFromDates(date('d-m-Y', strtotime($unknown->created))); ?></td>
        <td>
            <?php if (DifferenceFromDates($unknown->expiry) <= 7 && strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($unknown->expiry)))) { ?>
            <label class="text-danger">
                <?= DifferenceFromDates($unknown->expiry); ?>
            </label>
            <?php } elseif (strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($unknown->expiry)))) { ?>
            <label class="text-success">
                <?= DifferenceFromDates($unknown->expiry); ?>
            </label>
            <?php } else { ?>
            <label class="text-danger">
                -<?= DifferenceFromDates($unknown->expiry); ?>
            </label>    
            <?php } ?>
        </td>
        <td><?= $unknown->started; ?></td>
        <td>
            <?php if (strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($unknown->expiry)))) { ?>
            <label class="btn btn-success btn-xs"><i class="fa fa-thumbs-up"></i> Licensed</label>
            <?php } else { ?>
            <a href="javascript:void(0);" onclick="RenewLicensed(<?= $unknown->id; ?>);" class="btn btn-danger btn-xs" title="Renew License">
                <i class="fa fa-thumbs-down"></i> Expired
            </a>
            <?php } ?>
        </td>
        <td><?= $unknown->expiry; ?></td>
        <td>
            <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a href="<?= base_url('admin/view/') . $unknown->id ?>" class="dropdown-item cp text-primary">
                        View
                    </a>
                    <?php if ($this->session->userdata('roles') != 3) { ?>
                    <a href="<?= base_url('admin/edit/') . $unknown->id ?>" class="dropdown-item cp text-warning">
                        Edit
                    </a>
                    <?php if ($unknown->status) { ?>
                    <a href="<?= base_url('admin/block/') . $unknown->id ?>" class="dropdown-item cp text-danger">
                        Block
                    </a>
                    <?php } else { ?>
                    <a href="<?= base_url('admin/unblock/') . $unknown->id ?>" class="dropdown-item cp text-success">
                        Unblock
                    </a>
                    <?php } if ($unknown->inventory) { ?>
                    <a href="<?= base_url('admin/inventory_block/') . $unknown->id ?>" class="dropdown-item cp">
                        Inventory Block
                    </a>
                    <?php } else { ?>
                    <a href="<?= base_url('admin/inventory_unblock/') . $unknown->id ?>" class="dropdown-item cp">
                        Inventory Unblock
                    </a>
                    <?php } ?>
                    <a href="#" class="dropdown-item cp text-success" onclick="showModel('<?= $unknown->id ?>')">
                        Add Amount
                    </a>
                    <a href="#" class="dropdown-item cp text-warning" onclick="showModels('<?= $unknown->id ?>')">
                        Detect Amount
                    </a>
                    <a href="<?= base_url('admin/delete/') . $unknown->id ?>" class="dropdown-item cp text-danger">
                        Delete
                    </a>
                    <a href="<?= base_url('admin/sms/') . $unknown->id ?>" class="dropdown-item cp text-success">
                        Add SMS
                    </a>
                    <a href="<?= base_url('admin/fileshare/') . $unknown->id ?>" class="dropdown-item cp text-muted">
                        File Sharing
                    </a>
                    <a href="<?= base_url('userlist/see_details/') . $unknown->id ?>" class="dropdown-item cp text-primary">
                        Transaction
                    </a>
                    <?php } ?>
                </div>
            </div>

            <div id="myModal<?= $unknown->id ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center">Add Amount</h4>
                            <button type="button" class="close" data-dismiss="modal" onclick="closeModel(<?= $unknown->id ?>)">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" onsubmit="OnSubmitHandler(<?= $unknown->id ?>)">
                                <input type="hidden" class="form-control" name="user_id" id="user_id<?= $unknown->id ?>" value="<?= $user_id ?>" />
                                <div class="form-group">
                                    <label for="amount">Amount:</label>
                                    <input type="number" class="form-control" name="amount" id="amount<?= $unknown->id ?>" min="1" value="0">
                                </div>
                                <div class="form-group">
                                    <label for="msg">Message:</label>
                                    <textarea id="msg<?= $unknown->id ?>" class="form-control" name="msg<?= $unknown->id ?>"></textarea>
                                </div>
                                <button class="btn btn-primary mt-1">Add</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" onclick="closeModel(<?= $unknown->id ?>)" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="myModals<?= $unknown->id ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center text-danger">Detect Amount</h4>
                            <button type="button" class="close" data-dismiss="modal" onclick="closeModels(<?= $unknown->id ?>)">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" onsubmit="OnSubmitHandlers(<?= $unknown->id ?>)">
                                <input type="hidden" class="form-control" name="user_id" id="user_id<?= $unknown->id ?>" value="<?= $user_id ?>" />
                                <div class="form-group">
                                    <label for="amount">Amount:</label>
                                    <input type="number" class="form-control" name="amount" id="amounts<?= $unknown->id ?>" min="1" value="0">
                                </div>
                                <div class="form-group">
                                    <label for="msgs">Message</label>
                                    <textarea id="msgs<?= $unknown->id ?>" class="form-control" name="msgs<?= $unknown->id ?>"></textarea>
                                </div>
                                <button class="btn btn-danger mt-1">Detect</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" onclick="closeModels(<?= $unknown->id ?>)" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <?php } } else { echo "There Is no users"; } ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="14">
            <?= $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
</tfoot>