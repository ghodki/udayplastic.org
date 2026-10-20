            <form action="<?= base_url(); ?>paymentReceipt/editParty2" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $id; ?>" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="usertype">User Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="usertype" id="usertype" required="">
                                <option value="">Select User Type</option>
                                <option value="Customer" <?= $partydata->usertype == "Customer" ? "selected" : "" ?>>Customer</option>
                            </select>
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('usertype'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="name">Party Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="<?= set_value('name', $partydata->party_name); ?>" id="name" class="form-control" placeholder="Party Name" required="true" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('name'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="number">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" name="number" value="<?= set_value('number', $partydata->number); ?>" id="number" class="form-control" placeholder="Mobile Number" required="true" minlength="10" maxlength="10" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('number'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="pan">PAN Number <span class="text-danger">*</span></label>
                            <input type="text" name="pan" value="<?= set_value('pan', $partydata->pan); ?>" id="pan3" class="form-control" placeholder="PAN Number" required="" minlength="10" maxlength="10" required="" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('pan'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="<?= set_value('email', $partydata->email); ?>" id="email" class="form-control" placeholder="Email" required="true" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('email'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="state_id">State <span class="text-danger">*</span></label>
                            <select name="state_id" id="state_id" class="form-control" title="Select State" required="">
                                <?php $statename = get_title('state_list', ['id' => $partydata->state_id], 'state'); ?>
                                <option selected="" value="<?= $partydata->state_id; ?>"><?= $statename; ?></option>
                                <?php if (!empty($statelist)) { foreach ($statelist as $key => $state) { ?>
                                <option value="<?= $state->id; ?>"><?= $state->state; ?></option>
                                <?php } } ?>
                            </select>
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('state_id'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="city">City</label>
                            <input type="text" name="city" value="<?= set_value('city', $partydata->city); ?>" id="city" class="form-control" placeholder="City" />
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control" placeholder="Address"><?= set_value('address', $partydata->address); ?></textarea>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="pin">Pin</label>
                            <input type="tel" name="pin" value="<?= set_value('pin', $partydata->pin); ?>" id="pin" class="form-control" placeholder="Pin" minlength="6" maxlength="6" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="Close2();">Close</button>
                </div>
            </form>