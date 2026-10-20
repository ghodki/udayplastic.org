<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-blue pb-8 pt-5 pt-md-8">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Ticket</h2>
                                <!-- Card stats -->
                                <div class="row">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                            <?php echo $this->session->flashdata('msg'); ?>
                                        </div>
                                    <?php

                                    }
                                    ?>
                                    <form method="POST" action="<?= base_url('ticket/update'); ?>" class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                                <input type="hidden" name="ticket_id" value="<?= $result->ticket_id ?>">
                                            </div>
                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                <div class="form-group">
                                                    <textarea id="ticket_name" required type="text" class="form-control" name="ticket_name" required="true"><?= $result->ticket_name ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    Update
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
            </div>
        </div>
    </div>
</div>