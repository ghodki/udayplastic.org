<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-blue pb-8 pt-5 pt-md-8">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Ticket Generate</h2>
                                <!-- Card stats -->
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                <?php

                                }
                                ?>


                                <?php echo $error; ?>

                                <?php echo form_open_multipart('ticket/generate_ticket_for_user'); ?>
                                <div class="row">

                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="topic">Topic</label>
                                            <select class="form-control" required name="topic">
                                                <option value=""></option>
                                                <?php
                                                $tickets = $this->user_model->getAry('ticket','','','',['status'=>1]);
                                                if ($tickets) {
                                                    foreach ($tickets as $tic) {
                                                ?>
                                                        <option value="<?= $tic->ticket_id ?>"><?= $tic->ticket_name ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" accept="image/x-png,image/gif,image/jpeg,image/jpg,application/pdf" class="form-control"  name="image" size="20" />

                                        </div>
                                    </div>



                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="message">Message</label>
                                            <textarea id="message" class="form-control" required name="message" required><?= set_value('description') ?></textarea>
                                        </div>

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
        </div>
        <div class="container-fluid mb-9" style="margin-top:100px;">

            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Ticket Status List</label>
                        <div class="row mt-5">
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Ticket Name</th>
                                        <th>Ticket Number</th>
                                        <th>Status</th>
                                        <th>Reply</th>
                                        <th>Message</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php

                                    if (isset($ticket) && !empty($ticket)) {
                                        foreach ($ticket as  $key => $cat) {

                                    ?>
                                            <tr>
                                                <td>
                                                    <span><?= $key + 1; ?></span>
                                                </td>

                                                <td><?= get_title('ticket', ['ticket_id' => $cat->ticket_id], 'ticket_name') ?></td>
                                                <!-- <td><?= $cat->image ?></td> -->
                                                <td><?= $cat->rendom_ticket_id ?></td>

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
                                                <td><?= $cat->message ?></td>



                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                            <!-- <a href="<?= base_url('ticket/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a> -->
                                                            <?php
                                                            if ($cat->status == 0) {

                                                            ?>
                                                                <a href="<?= base_url('ticket/delete_ticket_generate/') . $cat->id ?>" onclick="return confirm('Are you sure you want to delete the ticket?');" class="dropdown-item cp">Delete</a>

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
<script>
    function downloadFile(uri, name) {
        var link = document.createElement("a");
        // If you don't know the name or want to use
        // the webserver default set name = ''
        link.setAttribute('download', name);
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
</script>