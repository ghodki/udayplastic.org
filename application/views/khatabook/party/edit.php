<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Update Party</h5>
                        <div class="align-items-center">
                            <div class="ps-3">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                        <strong><?php echo $this->session->flashdata('msg'); ?></strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php
                                }
                                ?>
                                <form action="<?= base_url() ?>khatabook/editParty/<?= $result->id ?>" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="usertype">User Type<span class="text-danger">*</span></label>
                                            <select class="form-control" name="usertype" required="true">
                                                <option value=''>Select UserType</option>
                                                <option value="Customer" <?= $result->usertype == "Customer" ? "selected" : "" ?>>Customer</option>
                                                <option value="Supplier" <?= $result->usertype == "Supplier" ? "selected" : "" ?>>Supplier</option>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('usertype'); ?>
                                            </span>
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="name">Party Name<span class="text-danger">*</span></label>
                                                <input id="name" name="name" placeholder="Party Name" type="text" class="form-control" required="true" value="<?= set_value('name', $result->party_name) ?>">
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?php echo form_error('name'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- <input type="hidden" name="party_id" value=""> -->

                                        <div class="col-md-4  col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="number">Mobile Number<span class="text-danger">*</span></label>
                                                <input id="number" name="number" placeholder="Mobile Number" type="tel" class="form-control" required="true" minlength="10" maxlength="10" value="<?= set_value('number', $result->number) ?>">
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?php echo form_error('number'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="address">Address<span></span></label>
                                                <textarea name="address" type="text" class="form-control" required="true" value=""><?= set_value('address', $result->address) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

        </div><!-- End Left side columns -->
        <!-- </div> -->
    </section>
</main><!-- End #main -->