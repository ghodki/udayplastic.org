<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style>
        .control-label {
            color: red;
        }

        label {
            color: aliceblue;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header  pb-8 pt-5 pt-md-8" style="background: #2C4668;">
                        <div class="container-fluid mb-9" style="margin-top:100px;">
                            <div class="col">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Digital Signature Applied List</label>
                                        <div class="row mt-5">
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Service Name</th>
                                                        <th>User</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark">
                                                    <?php
                                                    if (isset($result) && $result != '') {
                                                        foreach ($result as  $key => $value) {
                                                            $date = $value->created_at;
                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <span><?= $key + 1; ?></span>
                                                                </td>
                                                                <td><?= $date; ?></td>
                                                                <td><?= get_title('special_services', ['id' => $value->digital_certificate_id], 'service_name') ?></td>
                                                                <td><?= get_title('users', ['id' => $value->user_id], 'name') ?></td>
                                                                <td><?= $value->email; ?></td>
                                                                <td><?= $value->phone; ?></td>
                                                                <td><?= $value->amount; ?></td>
                                                                <td><?php
                                                                    if ($value->status == 0)
                                                                        echo '<button class="btn btn-warning">Under Processing</button>';
                                                                else
                                                                echo '<button class="btn btn-success">Approved</button>';

                                                                     ?></td>

                                                                <!-- <td class="text-right">
                                                                    <div class="dropdown">
                                                                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"> -->
                                                                            <!-- <a href="<?= base_url('edit_digital_certificate/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
                                                                            <!-- <a href="<?= base_url('delete_digital_certificate/') . $value->id ?>" class="dropdown-item cp">Delete</a> -->
                                                                        <!-- </div>
                                                                    </div>
                                                                </td> -->
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
</div>