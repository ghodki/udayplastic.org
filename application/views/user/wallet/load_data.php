<thead class="thead-dark">
    <tr>

        <th>#</th>
        <th>Service Name</th>
        <th>Transaction Date</th>
        <th>Transaction Id</th>
        <th>CR</th>
        <th>DT</th>
        <th>Balance</th>
        <th>File</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody class="text-dark">
    <?php
    $total_amount_dbt = 0;
    $total_amount_cr = 0;

    if (isset($result) && !empty($result)) {

        $counter = count($result);
        foreach ($result as  $key => $value) {
            $value = (object) $value;

            $date = $value->voucherdate;
            $newDate = $date;
            $amount = 0;
            $status = $value->status;
            $find_status = $value->act_status;
    ?>
            <div id="myModal<?= $value->id ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Amount</h4>

                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" onsubmit="OnSubmitHandler(<?= $value->id ?>)">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="amount" id="amount<?= $value->id ?>" min="1" value="<?= $value->amount ?>">
                                    <input type="hidden" class="form-control" name="user_id" id="user_id<?= $value->id ?>" value="<?= $value->user_id ?>">
                                    <input type="hidden" class="form-control" name="id" id="id<?= $value->id ?>" value="<?= $value->id ?>">

                                </div>
                                <button class="btn btn-primary">Update</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
            <div id="rejectmsgs<?= $value->id ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center">Reject Message</h4>

                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="text-danger"><?= $value->msg ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>

            <tr>
                <td><span><?= $key + 1; ?></span></td>
                <?php
                // for file shring
                if ($value->product_service) {
                ?>
                    <td> <?php echo get_title('categoryfilesharing', ['id' => $value->product_service], 'name'); ?>
                    <?php
                } else if ($value->add_amount == 2) {
                    ?>
                    <td class="text-danger" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Debited">Debited</td>
                <?php
                } else if ($value->add_amount == 3) {
                ?>
                    <td> <?php echo get_title('product_service', ['id' => $value->service_product], 'name'); ?>
                    <?php
                } else if ($value->add_amount == 4) {
                    ?>
                    <td class="text-success" data-toggle="modal" data-target="#walletMsg<?= $value->id ?>" title="Know About Debited">Wallet Credited</td>
                <?php

                } else if ($value->add_amount == 1) {
                ?>
                    <td class="text-success" data-toggle="modal" data-target="#seeMsg<?= $value->id ?>" title="Know About Credited">Credited</td>
                <?php
                } else if ($value->add_amount == 5) {
                ?>
                    <td> <?php echo "Ecommerce Product" ?></td>
                <?php
                }
                ?>
                </td>
                <td><?= $value->voucherdate; ?></td>
                <td><a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>"><?= $value->vouchernumber; ?></a>
                </td>

                <td class="text-success">
                    <?php
                    if ($find_status == 'CR') {
                        if ($value->file_sharing_id) {
                            $amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
                            echo $amount;
                        } else {
                            $amount = $value->amount;
                            echo $value->amount;
                        }
                        if ($value->status == 1) {
                            $total_amount_cr = $total_amount_cr + $amount;
                        }
                    } else {
                        echo 0;
                    }
                    ?>
                </td>

                <td class="text-danger">
                    <?php
                    if ($find_status == 'DBT') {
                        if ($value->file_sharing_id) {
                            $amount = get_title('file_sharing', ['id' => $value->file_sharing_id], 'amount');
                            echo $amount;
                        } else {
                            $amount = $value->amount;
                            echo $value->amount;
                        }
                        if ($value->status == 1) {
                            $total_amount_dbt = $total_amount_dbt + $amount;
                        }
                    } else {
                        echo 0;
                    }

                    ?>

                </td>
                <td><?= $total_amount_cr - $total_amount_dbt ?></td>
                <?php
                $where = ['id' => $value->user_id];
                ?>
                <?php
                if ($value->status == 1 || $value->user_id == $this->session->userdata('user_id')) {
                } else {
                ?>
                    <input type="hidden" style="width:90px;" class="form-control" min="0" name="amount" id="amount<?= $value->id ?>" value="<?= $value->amount ?>">
                <?php
                } ?>
                <!-- </td> -->
                <?php
                if ($value->file_sharing_id) {
                ?>
                    <td><a href="<?= base_url('uploads/file_share/') . get_title('file_sharing', ['id' => $value->file_sharing_id], 'file_name'); ?>" download>Download</a></td>

                    <?php
                } else if ($value->add_amount == 4) {

                    if ($value->image) {
                    ?>
                        <td><a href="<?= base_url('uploads/amount/') . $value->image; ?>" download>Download</a></td>
                    <?php
                    } else if ($value->paymenttype == "PayuMoney") {
                    ?>
                        <td class="text-muted">PayU Money</td>
                    <?php

                    } else {
                    ?>
                        <td class="text-muted">Not Uploaded</td>
                    <?php
                    }
                } else if ($value->add_amount == 2) {
                    ?>
                    <td><a href="#" class="text-danger">Debited</a></td>

                <?php
                } else if ($value->add_amount == 1) {
                ?>
                    <td><a href="#">Credited</a></td>

                <?php


                } else if ($value->add_amount == 3) {
                ?>
                    <td><a href="#">Service Request</a></td>

                <?php
                } else if ($value->add_amount == 5) {
                ?>
                    <td> <?php echo "Ecommerce Product" ?></td>
                <?php
                }
                ?>

                <td><?php

                    if ($value->status == 0) {
                        echo '<p class="text-danger">Pending</p>';
                    } else if ($value->status == 1) {
                        echo '<p class="text-success">Approved</p>';
                    } else {
                        echo '<p class="text-danger" data-toggle="modal" data-target="#rejectmsgs' . $value->id . '">Rejected</p>';
                    }
                    ?></td>
                <td class="text-right">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <?php
                            --$counter;
                            ?>
                            <a href="<?= base_url() ?>wallet/viewdetails/<?= $value->id ?>" class="dropdown-item cp text-primary">View</a>
                            <?php
                            if ($value->status == 0) {
                            ?>
                                <a href="<?= base_url('wallet/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a>
                                <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
    <?php }
    }
    ?>

</tbody>
<tfoot>
    <tr>
        <td colspan="4">
            <?php echo $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
</tfoot>