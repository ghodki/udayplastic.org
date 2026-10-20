<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">

                    <div class="container-fluid mb-9">

                        <div class="col">
                            <div class="card shadow">
                                <div class="card-header border-0">
                                    <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Ticket Status List</label>
                                    <div class="row mt-5">
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush table-striped" style="width: 100%" id="dataTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>User Name</th>
                                                    <th>Ticket Name</th>
                                                    <th>Ticket Number</th>
                                                    <th>Date</th>
                                                    <th>Message</th>
                                                    <th>Status</th>
                                                    <th>Reply</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                <?php

                                                if (isset($ticket) && !empty($ticket)) {
                                                    foreach ($ticket as  $key => $cat) {
                                                            $date = $cat->created_at;
                                                        $newDate = date("d-m-Y", $date);

                                                ?>
                                                        <tr>
                                                            <td>
                                                                <span><?= $key + 1; ?></span>
                                                            </td>
                                                            <td>
                                                                <span><?= get_title('users', ['id' => $cat->user_id], 'name') ?></span>
                                                            </td>
                                                            <td>
                                                                <?= $newDate ?>
                                                            </td>
                                                            <td><?= get_title('ticket', ['ticket_id' => $cat->ticket_id], 'ticket_name') ?></td>
                                                            <!-- <td><?= $cat->image ?></td> -->
                                                            <td><?= $cat->rendom_ticket_id ?></td>
                                                            <td><?= $cat->message ?></td>

                                                            <?php
                                                            if ($cat->status == 1) {
                                                                echo "<td class='text-success'>Open</td>";
                                                            } elseif ($cat->status == 2) {
                                                                echo "<td class='text-warning'>Closed</td>";
                                                            } else {
                                                                echo "<td class='text-danger'>Pending</td>";
                                                            }

                                                            ?>

                                                            <td>
                                                                <a class="text-primary" href="<?= base_url('ticket/reply/') . $cat->id ?>">Reply</a>


                                                            </td>


                                                            <td class="text-right">
                                                                <div class="dropdown">
                                                                    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                        <a href="<?= base_url('ticket/reply/') . $cat->id ?>" class="dropdown-item cp text-primary">Reply</a>
                                                                        <a href="<?= base_url('ticket/close_ticket/') . $cat->id ?>" class="dropdown-item cp text-warning">Close Ticket</a>

                                                                        <!-- <a href="<?= base_url('ticket/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a> -->
                                                                        <!-- <a href="<?= base_url('ticket/delete_ticket_generate/') . $cat->id ?>" onclick="return confirm('Are you sure you want to delete the ticket?');" class="dropdown-item cp">Delete</a> -->


                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                <?php }
                                                }

                                                ?>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>