<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="mt-5" style="margin-top:4em!important">
                        <!-- <div class="container"> -->
                        <div class="container-fluid">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                            <?php

                            }
                            ?>
                            <div class="card p-3">
                                <ul class="nav nav nav-tabs nav-pills">
                                    <li class="nav-link"><a data-toggle="pill" href="#all" onclick="refreshpage()">All</a></li>
                                    <?php
                                    $category = $this->user_model->getResult('categoryfilesharing');
                                    if (isset($category) && !empty($category)) {
                                        foreach ($category as $key => $categories) {
                                    ?>
                                            <li class="nav-link"><a data-toggle="pill" href="#aman<?= $key ?>"><?= $categories->name ?></a></li>

                                    <?php
                                        }
                                    }

                                    ?>
                                </ul>


                                <div class="header-footer">
                                    <?php
                                    $roles = $this->session->userdata('roles');
                                    // role 1=Admin
                                    // role 2 = subadmin
                                    // role 3 =user
                                    // role 4 =Employye
                                    if ($roles == 2) {
                                        $where = ['usertype' => 'subadmin'];
                                    } else if ($roles == 3) {
                                        $where = ['usertype' => 'users'];
                                    } else {
                                        $where = ['status' => 1];
                                    }
                                    $notice = $this->user_model->getAryss('notice', '', '', '', $where);
                                    if ($notice) {
                                    ?>
                                        <div class="bg-info-alt row">
                                            <i class="fas fa-envelope pt-1 text-gray-dark"></i>
                                            <span class="ml-1 p-0 text-danger" style="width: 97%">
                                                <marquee width="100%" id="notification">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#jaisa_mera_mai">
                                                        <!-- <a data-toggle="modal" data-target="#jaisa_mera_mai<?= $key ?>" onclick="userNotification($this->)" class="dropdown-item"> -->
                                                        <?= $notice[0]->name ?>
                                                    </a>
                                                </marquee>
                                            </span>
                                        </div>
                                    <?php
                                    }

                                    ?>
                                </div>
                            </div>
                            <div class="card p-3">
                                <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event)" method="get">
                                    <div class="row mt-1">

                                        <?php
                                        $date = date('Y-m-d');
                                        $from = date('Y-m-01');

                                        ?>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label for="fromDate">From Date</label>
                                            <input type="date" tabindex="1" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : $from ?>" class="form-control" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label for="toDate">To Date</label>
                                            <input type="date" tabindex="2" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : $date ?>" class="form-control" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label for="total">Total Records</label>
                                            <select class="form-control select2 searchselect" name="records" id="records">
                                                <option value="999999999999999999999999999" <?= $this->input->get('records') == '999999999999999999999999999' ? 'selected' : '' ?>>All</option>

                                                <option value="20" <?= $this->input->get('records') == '20' ? 'selected' : '' ?>>20</option>
                                                <option value="50" <?= $this->input->get('records') == '50' ? 'selected' : '' ?>>50</option>
                                                <option value="100" <?= $this->input->get('records') == '100' ? 'selected' : '' ?>>100</option>
                                                <option value="500" <?= $this->input->get('records') == '500' ? 'selected' : '' ?>>500</option>
                                                <option value="1000" <?= $this->input->get('records') == '1000' ? 'selected' : '' ?>>1000</option>
                                                <option value="5000" <?= $this->input->get('records') == '5000' ? 'selected' : '' ?>>5000</option>
                                                <option value="10000" <?= $this->input->get('records') == '10000' ? 'selected' : '' ?>>10000</option>

                                                <option value="500000" <?= $this->input->get('records') == '500000' ? 'selected' : '' ?>>500000</option>


                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6" style="margin-top:31px;">


                                            <button type="submit" class="btn btn-primary" tabindex="3" onclick="return search()"> Search</button> &nbsp
                                            <button type="button" class="btn btn-warning" tabindex="4"><a href="<?= base_url() ?>file_sharing" class="text-muted">Reset</a></button> &nbsp

                                        </div>




                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="jaisa_mera_mai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="text-center">Top Notice</h2>

                        </div>
                        <div class="modal-body">
                            <table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
                                <?php
                                $notify = $this->user_model->getAryss('notice', '', '', 10, $where);
                                if ($notify) {
                                    foreach ($notify as $key => $notification) {
                                        $date = $notification->created_at;
                                        $newDate = date("d-m-Y h:i", strtotime($date));
                                ?>
                                        <tr>
                                            <th><?= $newDate ?></th>
                                            <th class="text-danger"><?= $notification->name ?></th>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#jaisa_mera_mai_jaise_mera_tu">See All</button>


                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="jaisa_mera_mai_jaise_mera_tu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#jaisa_mera_mai">See Top</button> -->
                            <h2 class="text-center">All Notice</h2>
                        </div>
                        <div class="modal-body">
                            <!-- <table> -->
                            <table class="table align-items-center table-flush" style="width: 100%" id="dataTable">

                                <?php
                                $notify = $this->user_model->getAryss('notice', '', '', '', $where);

                                if ($notify) {
                                    foreach ($notify as $key => $notification) {
                                        $date = $notification->created_at;
                                        $newDate = date("d-m-Y h:i", strtotime($date));
                                ?>
                                        <tr>
                                            <th><?= $newDate ?></th>

                                            <th class="text-danger"><?= $notification->name ?></th>
                                        </tr>

                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-secondary">See Top</button> -->


                        </div>
                    </div>
                </div>
            </div>

            <!-- NOTICE BAR END -->

            <div class="tab-content">
                <div id="all" class="tab-pane fade show active">
                    <div class="container-fluid mb-9">
                        <div class="col">
                            <div class="card shadow">
                                <div class="card-header border-0" style="margin-top:10px;">
                                    <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">FILE
                                        SHARED LIST</label>

                                    <div class="table-responsive">
                                        <table class="table table-striped align-items-center table-flush dataTable1" style="width: 100%" id="dataTable1">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Category</th>
                                                    <th>File Shared</th>
                                                    <th>Send By</th>
                                                    <!-- <th>Description</th> -->
                                                    <th>Status</th>
                                                    <th>Wallet</th>
                                                    <th>Amt</th>
                                                    <th>Des</th>

                                                    <th>Reply</th>
                                                    <th>Action</th>


                                                </tr>
                                            </thead>
                                            <tbody class="text-dark">
                                                <?php

                                                // if ($this->session->userdata('roles') != 2) {
                                                if (isset($file_sharing) && $file_sharing != '') {
                                                    foreach ($file_sharing as  $key => $value) {
                                                        $date = $value->date;

                                                        // $newDate = date("d-m-Y", strtotime($date));
                                                ?>
                                                        <div class="modal fade" id="exampleModal<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <form class="form" action="<?= base_url() ?>wallet/updateAmount" method="post">

                                                                            <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                                File Sharing</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="user_id" value="<?= $value->user_id ?>">
                                                                        <input type="hidden" name="id" value="<?= $value->id ?>">
                                                                        <input type="hidden" name="category_id" value="<?= $value->category ?>">

                                                                        <div class="form-group">

                                                                            <label for="amount">Category:</label>
                                                                            <input type="text" readonly class="form-control" value="<?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="amount">Amount:</label>
                                                                            <input type="number" name="amount" class="form-control" value="<?= $value->amount ?>">
                                                                        </div>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                                    </div>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <div id="myModal<?= $value->id ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title text-center">Reject Message</h4>

                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post" action="" onsubmit="OnSubmitHandler(<?= $value->id ?>,<?= $value->user_id ?>)">
                                                                            <div class="form-group">
                                                                                <textarea type="text" class="form-control" name="msg" id="msg<?= $value->id ?>"></textarea>

                                                                                <input type="hidden" class="form-control" name="user_id" id="user_id<?= $value->id ?>" value="<?= $value->user_id ?>">

                                                                            </div>
                                                                            <button class="btn btn-danger">Reject</button>
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div id="myModals<?= $value->id ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title text-center text-danger">Rejected Message</h4>

                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <p><?= $value->msg ?></p>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="amanModelss<?= $value->id ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title text-center">Message</h4>

                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h5 class="text-center text-danger">Can't See Because File is Not Approved so for.....</h5>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <tr>
                                                            <td>
                                                                <span><?= $key + 1; ?></span>
                                                            </td>
                                                            <td><?= $date; ?></td>
                                                            <td><?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>
                                                            </td>

                                                            <?php
                                                            $where = ['id' => $value->user_id];
                                                            $username = $this->user_model->getRow('users', $where);
                                                            ?>
                                                            <td>
                                                                <?php
                                                                $where = ['id' => $value->user_id];
                                                                $username = $this->user_model->getRow('users', $where);
                                                                ?>

                                                                <?php
                                                                if ($value->file_name) {
                                                                    $file_name = $value->file_name;
                                                                    // print_r($file_name);
                                                                    // echo '<pre>';
                                                                    // die;


                                                                    $decode = json_decode($value->file_name);

                                                                    if ($decode != '' && $decode != null) {
                                                                        foreach ($decode as $key => $data) {
                                                                            $file_name = $data->file_name;
                                                                ?>
                                                                            <a href="<?= base_url('uploads/file_share/') . $file_name; ?>" download>Download</a>,
                                                                        <?php
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <a href="<?= base_url('uploads/file_share/') . $file_name; ?>" download>Download</a>,

                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                            <!-- <td><a href="<?= base_url('uploads/file_share/') . $value->file_name; ?>" download>Download</a></td> -->
                                                            <?php
                                                            $where = ['id' => $value->user_id];
                                                            $sendby_name = $this->user_model->getRow('users', $where);
                                                            // echo $sendby_name->amount;
                                                            // die;
                                                            if ($value->role == 3) {
                                                                $send = "Send";
                                                            } else {
                                                                $send = "Recieve";
                                                            }
                                                            ?>
                                                            <td><?= $sendby_name->name; ?><span><i></i>
                                                                </span></td>

                                                            <?php
                                                            if ($value->status == 0) {
                                                            ?>
                                                                <td class="text-danger">Pending</td>
                                                            <?php
                                                            } else if ($value->status == 1) {
                                                            ?>
                                                                <td class="text-success">Approved</td>

                                                            <?php
                                                            } else {
                                                            ?>
                                                                <td class="text-danger" data-toggle="modal" data-target="#myModals<?= $value->id ?>" title="Know About Rejection">Rejected</td>

                                                            <?php
                                                            }
                                                            ?>
                                                            <td class="success">
                                                                <?= get_title('users', ['id' => $value->user_id], 'amount'); ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($value->status == 0 && $this->session->userdata('user_id') != $value->user_id) {
                                                                ?>
                                                                    <input style="width: 100px;" type="number" min="0" id="amounts<?= $value->id ?>" value="" class="form-control">
                                                                <?php
                                                                } else if ($value->status == 2 && $this->session->userdata('user_id') != $value->user_id) {
                                                                    // echo $value->amount;
                                                                ?>
                                                                    <input type="number" style="width: 100px;" min="0" id="amounts<?= $value->id ?>" value="0" class="form-control">

                                                                <?php
                                                                } else {
                                                                    echo $value->amount;
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?= $value->des; ?></td>

                                                            <td>
                                                                <?php

                                                                if ($this->session->userdata('roles') == 3) {
                                                                    if ($value->status == 1) {
                                                                ?>
                                                                        <a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <a href="#" class="text-danger" title="Due to Not Approving" data-toggle="modal" data-target="#amanModelss<?= $value->id ?>">View Reply</a>

                                                                    <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

                                                                <?php
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <div class="dropdown">
                                                                    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                        <?php

                                                                        if ($this->session->userdata('roles') == 3) {
                                                                            if ($value->status == 1) {
                                                                        ?>
                                                                                <a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

                                                                            <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

                                                                        <?php
                                                                        }
                                                                        if ($this->session->userdata('user_id') == $value->user_id) {
                                                                        ?>
                                                                            <?php
                                                                            if ($value->status == 0) {
                                                                            ?>
                                                                                <?php


                                                                                ?>
                                                                            <?php
                                                                            } else if ($value->status == 1) {
                                                                            ?>
                                                                                <a class="dropdown-item cp text-success">Approved</a>

                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <a class="dropdown-item cp text-danger">Rejected</a>

                                                                            <?php
                                                                            }
                                                                        } else {
                                                                            ?>

                                                                            <?php
                                                                            if ($value->status == 0) {
                                                                            ?>
                                                                                <a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#myModal<?= $value->id ?>">Reject</a>

                                                                                <a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
                                                                                <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

                                                                                <!-- <a href="<?= base_url('wallet/edit/') . $value->id ?>" class="dropdown-item cp">Edit</a> -->
                                                                            <?php
                                                                            } else if ($value->status == 1) {
                                                                            ?>
                                                                                <a onclick="return Decline('<?= base_url('file_sharing/decline') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>


                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

                                                                                <a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>

                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        <?php
                                                                        }
                                                                        ?>


                                                                    </div>
                                                                </div>
                                                            </td>

                                                        </tr>

                                                <?php
                                                    }
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

                <?php
                // <?php
                $category = $this->user_model->getResult('categoryfilesharing');
                if (isset($category) && !empty($category)) {
                    foreach ($category as $key => $categories) {
                ?>
                        <div id="aman<?= $key ?>" class="tab-pane fade">
                            <h3 class="text-center"><?= $categories->name ?></h3>

                            <?php
                            if ($this->session->userdata('roles') == 3) {
                            ?>
                                <div class="header bg-blue pb-8 pt-5 pt-md-8">
                                    <div class="container-fluid">
                                        <div class="header-body">
                                            <form method="POST" action="<?= base_url('file_sharing/filesharesave'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">

                                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">File Share</h2>
                                                <!-- Card stats -->
                                                <div class="row">
                                                    <?php

                                                    if ($this->session->flashdata('msg')) { ?>
                                                        <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                                            <?php echo $this->session->flashdata('msg'); ?>
                                                        </div>
                                                    <?php

                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <input type="hidden" name="id" value="<?= $this->session->userdata('user_id'); ?>">
                                                        <input type="hidden" name="category" value="<?= $categories->id ?>">
                                                        <?php
                                                        date_default_timezone_set('Asia/Kolkata');

                                                        ?>
                                                        <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                            <div class="form-group">
                                                                <input id="createdAt" name="date" type="text" class="form-control" value="<?= date('d-m-Y h:ia') ?>" readonly required="true">

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                            <div class="form-group">
                                                                <input type="file" class="form-control" required name="image[]" multiple="" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                            <div class="form-group">
                                                                <textarea id="name" type="text" class="form-control" name="name" required="true"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-5">
                                                        <div class="col-md-12 text-center" style="margin-bottom: 32px;">
                                                            <button type="submit" class="btn btn-default">
                                                                Send
                                                            </button>

                                                            <input type="reset" class="btn btn-secondary" placeholder="Reset">
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            <?php
                            }
                            ?>


                            <div class="container-fluid mb-9">
                                <div class="col">
                                    <div class="card shadow">
                                        <div class="card-header border-0" style="margin-top:10px;">
                                            <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">FILE
                                                SHARED LIST</label>

                                            <div class="table-responsive">
                                                <table class="table table-striped align-items-center table-flush dataTable1" style="width: 100%">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date</th>
                                                            <th>Category</th>
                                                            <th>File Shared</th>
                                                            <th>Send By</th>
                                                            <!-- <th>Description</th> -->
                                                            <th>Status</th>
                                                            <th>Wallet</th>
                                                            <th>Amt</th>
                                                            <th>Des</th>
                                                            <th>Reply</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-dark">
                                                        <?php

                                                        if (isset($file_sharing) && $file_sharing != '') {
                                                            foreach ($file_sharing as  $key => $value) {
                                                                if ($value->category == $categories->id) {
                                                                    $date = $value->date;
                                                        ?>
                                                                    <div id="myModalss<?= $value->id ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">

                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title text-center text-danger">Rejected Message</h4>

                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <p><?= $value->msg ?></p>


                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div id="amanModel<?= $value->id ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">

                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title text-center">Reject Message</h4>

                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" action="" onsubmit="OnSubmitHandlers(<?= $value->id ?>,<?= $value->user_id ?>)">
                                                                                        <div class="form-group">
                                                                                            <textarea type="text" class="form-control" name="msg" id="msgs<?= $value->id ?>"></textarea>

                                                                                            <input type="hidden" class="form-control" name="user_id" id="user_id<?= $value->id ?>" value="<?= $value->user_id ?>">

                                                                                        </div>
                                                                                        <button class="btn btn-danger">Reject</button>
                                                                                    </form>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div id="amanModels<?= $value->id ?>" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">

                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title text-center">Message</h4>

                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <h5 class="text-center text-danger">Can't See Because File is Not Approved so for.....</h5>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <tr>
                                                                        <td>
                                                                            <span><?= $key + 1; ?></span>
                                                                        </td>
                                                                        <td><?= $date; ?></td>
                                                                        <td><?= get_title('categoryfilesharing', ['id' => $value->category], 'name'); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            $where = ['id' => $value->user_id];
                                                                            $username = $this->user_model->getRow('users', $where);
                                                                            ?>

                                                                            <?php
                                                                            if ($value->file_name != '') {

                                                                                $decode = json_decode($value->file_name);
                                                                                if ($decode != '' && $decode != null) {
                                                                                    foreach ($decode as $key => $data) {
                                                                                        $file_name = $data->file_name;
                                                                            ?>
                                                                                        <a href="<?= base_url('uploads/file_share/') . $file_name; ?>" download>Download</a>,
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <?php
                                                                        $where = ['id' => $value->user_id];
                                                                        $sendby_name = $this->user_model->getRow('users', $where);

                                                                        ?>
                                                                        <td><?= $sendby_name->name; ?><span>
                                                                            </span></td>

                                                                        <?php
                                                                        if ($value->status == 0) {
                                                                        ?>
                                                                            <td class="text-danger">Pending</td>
                                                                        <?php
                                                                        } else if ($value->status == 1) {
                                                                        ?>
                                                                            <td class="text-success">Approved</td>

                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <td class="text-danger" data-toggle="modal" data-target="#myModalss<?= $value->id ?>" title="Know About Rejection">Rejected</td>


                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <td class="success">
                                                                            <?= get_title('users', ['id' => $value->user_id], 'amount'); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            if ($value->status == 0 && $this->session->userdata('user_id') != $value->user_id) {
                                                                            ?>
                                                                                <input type="number" style="width: 100px;" min="0" id="amount<?= $value->id ?>" value="" class="form-control">
                                                                            <?php
                                                                            } else if ($value->status == 2 && $this->session->userdata('user_id') != $value->user_id) {
                                                                                // echo $value->amount;
                                                                            ?>
                                                                                <input type="number" style="width: 100px;" min="0" id="amount<?= $value->id ?>" value="0" class="form-control">

                                                                            <?php
                                                                            } else {
                                                                                echo $value->amount;
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td><?= $value->des; ?></td>
                                                                        <td>
                                                                            <?php

                                                                            if ($this->session->userdata('roles') == 3) {
                                                                                if ($value->status == 1) {
                                                                            ?>
                                                                                    <a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                    <a href="#" class="text-danger" title="Due to Not Approving" data-toggle="modal" data-target="#amanModels<?= $value->id ?>">View Reply</a>

                                                                                <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>


                                                                        <td class="text-right">
                                                                            <div class="dropdown">
                                                                                <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                                    <?php
                                                                                    if ($this->session->userdata('user_id') == $value->user_id) {
                                                                                    ?>
                                                                                        <?php
                                                                                        if ($value->status == 0) {
                                                                                        ?>

                                                                                            <?php
                                                                                            $where = ['file_sharing_id' => $value->id];

                                                                                            $files_id_mil_gayyi = $this->user_model->getRow('file_share_chat', $where);
                                                                                            if (!$files_id_mil_gayyi) {
                                                                                            ?>
                                                                                                <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>
                                                                                            <?php
                                                                                            } else {
                                                                                            ?>
                                                                                                <a class="dropdown-item cp text-danger">Can't Delete</a>

                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        <?php
                                                                                        } else if ($value->status == 1) {
                                                                                        ?>
                                                                                            <a class="dropdown-item cp text-success">Approved</a>

                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <a class="dropdown-item cp text-danger" data-toggle="modal" data-target="#myModalss<?= $value->id ?>">Rejected</a>

                                                                                        <?php
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <a href="<?= base_url('file_sharing/chat/' . $value->id) ?>" class="dropdown-item cp text-primary">Reply</a>

                                                                                        <!-- <a href="<?= base_url() ?>userlist/see_details/<?= $value->user_id ?>" class="dropdown-item cp text-primary">View</a> -->
                                                                                        <?php
                                                                                        if ($value->status == 0) {

                                                                                        ?>
                                                                                            <a href="#" class="dropdown-item cp text-danger" data-toggle="modal" data-target="#amanModel<?= $value->id ?>">Reject</a>

                                                                                            <a onclick="return Approve('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>
                                                                                            <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

                                                                                        <?php
                                                                                        } else if ($value->status == 1) {
                                                                                        ?>

                                                                                            <a onclick="return Decline('<?= base_url('file_sharing/decline') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Decline</a>


                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <a onclick="return Delete('<?= base_url('file_sharing/deletes') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-danger">Delete</a>

                                                                                            <a onclick="return Approves('<?= base_url('wallet/approves') ?>',<?= $value->id ?>,<?= $value->user_id ?>);" class="dropdown-item cp text-success">Approve</a>

                                                                                        <?php
                                                                                        }

                                                                                        ?>
                                                                                <?php
                                                                                    }
                                                                                }


                                                                                ?>


                                                                                </div>
                                                                            </div>
                                                                        </td>

                                                                    </tr>

                                                            <?php
                                                            }
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
                <?php
                    }
                }
                ?>



            </div>


        </div>
    </div>
</div>

</div>

<style>
    .pata_nahi_bhai {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: black;
        color: white;
        text-align: center;
    }
</style>
<div class="col-md-12 pata_nahi_bhai">
    <h6 class="text-danger text-center">Note:Only 20 Files can be uploaded and same type of files in single time.</h6>
</div>

</div>
</div>
</div>

</div>

<script>
    $(function() {
        $(".dataTable1").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'pageLength',
            ]

        });
    });

    function refreshpage() {
        // alert("hello");
        window.location.href = '<?= base_url() ?>file_sharing';
    }
</script>

<script>
    function Approve(url, id, user_id) {


        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "success",
            cancelButtonColor: "success",
            confirmButtonText: "Yes, Approve it!",
        }).then((result) => {
            if (result.isConfirmed) {
                removeRow(url, id, user_id);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }

    function Approves(url, id, user_id) {


        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "success",
            cancelButtonColor: "success",
            confirmButtonText: "Yes, Approve it!",
        }).then((result) => {
            if (result.isConfirmed) {
                addRow(url, id, user_id);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }



    function Delete(url, id, user_id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "danger",
            showCancelButton: true,
            confirmButtonColor: "success",
            cancelButtonColor: "danger",
            confirmButtonText: "Yes, Delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                removeRows(url, id, user_id);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }

    function Edit(url, id, user_id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "warning",
            cancelButtonColor: "warning",
            confirmButtonText: "Yes, Edit it!",
        }).then((result) => {
            if (result.isConfirmed) {
                removeRows(url, id, user_id);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }
</script>
<script>
    function addRow(url, id, user_id) {
        var amounts = document.getElementById('amounts' + id).value;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                amount: amounts,
                id: id,
                user_id: user_id,

            },
            success: function(success) {
                success = JSON.parse(success);
                console.log(success);
                if (success.status == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            },
        });
    }

    function removeRow(url, id, user_id) {
        var amounts = document.getElementById('amount' + id).value;
        // console.log(id, "id");

        $.ajax({
            type: "POST",
            url: url,
            data: {
                amount: amounts,
                id: id,
                user_id: user_id,

            },
            success: function(success) {
                success = JSON.parse(success);
                console.log(success);
                if (success.status == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            },
        });
    }



    function removeRows(url, id, user_id) {

        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id,
                user_id: user_id,

            },
            success: function(success) {
                success = JSON.parse(success);
                console.log(success);
                if (success.status == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            },
        });
    }


    function Decline(url, id, user_id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "danger",
            cancelButtonColor: "success",
            confirmButtonText: "Yes, Decline it!",
        }).then((result) => {
            if (result.isConfirmed) {
                decline(url, id, user_id);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }

    function decline(url, id, user_id) {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id,
                user_id: user_id,
            },
            success: function(success) {
                success = JSON.parse(success);
                console.log(success);
                if (success.status == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            },
        });
    }
</script>
<script>
    function Reject(url, id, user_id) {
        Swal.fire({

                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "success",
                showCancelButton: true,
                confirmButtonColor: "danger",
                cancelButtonColor: "success",
                confirmButtonText: "Yes, Reject it!",
            }

        ).then((result) => {
            if (result.isConfirmed) {
                reject(url, id, user_id);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }
</script>
<script>
    function OnSubmitHandler(id, user_id) {

        var user_ids = user_id;
        var msg = document.getElementById('msg' + id).value;
        if (id != '' && user_ids != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>file_sharing/reject",
                data: {
                    msg: msg,
                    user_id: user_ids,
                    id: id,

                },
                success: function(invoiceNo) {
                    invoiceNo = JSON.parse(invoiceNo);
                    console.log(invoiceNo, "dtaaaa");
                    if (invoiceNo.status == true) {

                        Swal.fire({
                            position: 'top-end',
                            icon: 'danger',
                            title: 'Rejected',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(function() {
                            window.reload();
                        }, 2000)

                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: invoiceNo.msg,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }

                },
            });
        } else {
            alert("Something is Missing");
        }
    }

    function OnSubmitHandlers(id, user_id) {

        var user_ids = user_id;
        var msg = document.getElementById('msgs' + id).value;


        if (id != '' && user_ids != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>file_sharing/reject",
                // data: $("#dataForm").serialize(),
                data: {
                    msg: msg,
                    user_id: user_ids,
                    id: id,

                },
                success: function(invoiceNo) {
                    invoiceNo = JSON.parse(invoiceNo);
                    console.log(invoiceNo, "dtaaaa");
                    if (invoiceNo.status == true) {

                        // invoiceData = invoiceNo.data;
                        // cosnole.log(invoiceData);
                        Swal.fire({
                            position: 'top-end',
                            icon: 'danger',
                            title: 'Rejected',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(function() {
                            window.reload();
                        }, 2000)

                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: invoiceNo.msg,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }

                },
            });
        } else {
            alert("Something is Missing");
        }
    }


    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();

        href = "<?= base_url() ?>file_sharing/dateFilter?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };
</script>