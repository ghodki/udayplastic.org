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
                                <?php } ?>
                                
                                <form action="<?= base_url(); ?>paymentReceipt/editParty/<?= $result->id; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="usertype">User Type <span class="text-danger">*</span></label>
                                            <select name="usertype" id="usertype" class="form-control" required="">
                                                <option value=''>Select User Type</option>
                                                <option value="Customer" <?= $result->usertype == "Customer" ? "selected" : "" ?>>Customer</option>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('usertype'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="name">Party Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="<?= set_value('name', $result->party_name); ?>" id="name" class="form-control" placeholder="Party Name" required="" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('name'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="number">Mobile Number <span class="text-danger">*</span></label>
                                                <input type="tel" name="number" value="<?= set_value('number', $result->number); ?>" id="number" placeholder="Mobile Number" class="form-control" required="true" minlength="10" maxlength="10" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('number'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="pan">PAN Number <span class="text-danger">*</span></label>
                                            <input type="text" name="pan" value="<?= set_value('pan', $result->pan); ?>" id="pan" class="form-control" placeholder="PAN Number" minlength="10" maxlength="10" required="" />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('pan'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="<?= set_value('email', $result->email); ?>" id="email" class="form-control" placeholder="Email" required="true" />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('email'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="state_id">State <span class="text-danger">*</span></label>
                                            <select name="state_id" id="state_id" class="form-control" title="Select State" required="">
                                                <?php $statename = get_title('state_list', ['id' => $result->state_id], 'state'); ?>
                                                <option selected="" value="<?= $result->state_id; ?>"><?= $statename; ?></option>
                                                <?php if (!empty($statelist)) { foreach ($statelist as $key => $state) { ?>
                                                <option value="<?= $state->id; ?>"><?= $state->state; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('state_id'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="city_id">City</label>
                                            <input type="text" name="city" value="<?= set_value('city', $result->city); ?>" id="city" class="form-control" placeholder="City" />
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea name="address" id="address" class="form-control" palceholder="Address"><?= set_value('address', $result->address); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="pin">Pin</label>
                                            <input type="tel" name="pin" value="<?= set_value('pin', $result->pin); ?>" id="pin" class="form-control" placeholder="Pin" minlength="6" maxlength="6" />
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                            <a href="<?= base_url(); ?>paymentReceipt/party" class="btn btn-danger">
                                                Cancel
                                            </a>
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