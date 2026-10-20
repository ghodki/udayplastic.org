<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-blue pb-8 pt-5 pt-md-8">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Ticket</h2>
                                <!-- Card stats -->
                                <div class="row">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                            <?php echo $this->session->flashdata('msg'); ?>
                                        </div>
                                    <?php

                                    }
                                    ?>
                                    <form method="POST" action="<?= base_url('ticket/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                            </div>
                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                <div class="form-group">
                                                    <textarea id="ticket_name" required type="text" class="form-control" name="ticket_name" required="true"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    Save
                                                </button>

                                                <input type="reset" class="btn btn-secondary" placeholder="Reset">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mb-9" style="margin-top:100px;">

                        <div class="col">
                            <div class="card shadow">
                                <div class="card-header border-0">
                                    <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Ticket List</label>
                                    <div class="row mt-5">
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Ticket Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($ticket) && $ticket) {
                                                    foreach ($ticket as  $key => $cat) {
                                                        $date = $cat->timestamp;
                                                        $newDate = date("d-m-Y", $date);

                                                ?>
                                                        <tr>
                                                            <td>
                                                                <span><?= $key + 1; ?></span>
                                                            </td>
                                                            <td><?= $newDate; ?></td>

                                                            <td><?= $cat->ticket_name ?></td>

                                                            <td class="text-right">
                                                                <div class="dropdown">
                                                                    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                        <a href="<?= base_url('ticket/edit/') . $cat->ticket_id ?>" class="dropdown-item cp">Edit</a>
                                                                        <a href="<?= base_url('ticket/delete/') . $cat->ticket_id ?>" class="dropdown-item cp">Delete</a>


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