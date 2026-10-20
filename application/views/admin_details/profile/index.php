<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <!-- PROFILE UPDATE START -->
                    <form method="POST" action="<?= base_url(); ?>Profile/UpdateProfile" enctype="multipart/form-data">
                        <div class="card card-outline card-dark">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-dark">Update Profile</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" value="<?= set_value('name', $userinfo->name); ?>" id="name" class="form-control" placeholder="Name" required="" />
                                            <span class="text-danger"><?= form_error('name'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="<?= set_value('email', $userinfo->email); ?>" id="email" class="form-control" placeholder="Email" required="" />
                                            <span class="text-danger"><?= form_error('email'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="number">Phone <span class="text-danger">*</span></label>
                                            <input type="tel" name="number" value="<?= set_value('number', $userinfo->number); ?>" id="number" class="form-control" placeholder="Phone Number" required="" min-length="10" max-length="10" />
                                            <span class="text-danger"><?= form_error('number'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="gst">GST</label>
                                            <input type="text" name="gst" value="<?= set_value('gst', $userinfo->gst); ?>" id="gst" class="form-control" placeholder="GST Number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" name="image" id="image" class="form-control" accept="image/*" title="Profile Image" />
                                            <span class="col-sm-4">
                                                <?php if ($userinfo->image) { ?>
                                                <img src="<?= base_url(); ?>uploads/profile/<?= $userinfo->image; ?>" style="height: 50px;" />
                                                <?php } else { ?>
                                                <img src="<?= base_url(); ?>assets/images/avatar.png" style="height: 50px;" />
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea name="address" id="address" class="form-control" placeholder="Address"><?= set_value('address', $userinfo->address); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn bg-dark">Update Profile</button>
                            </div>
                        </div>
                    </form>
                    <!-- PROFILE UPDATE END -->
                </div>                        

                <div class="col-md-12 col-xl-12 col-lg-12">
                    <!-- PASSWORD UPDATE START -->
                    <form method="POST" action="<?= base_url(); ?>Profile/ChangePassword">
                        <input type="hidden" name="actual_pass" value="<?= set_value('actual_pass', $userinfo->password); ?>" id="actual_pass" />
                        <div class="card card-outline card-maroon">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-maroon">Change Password</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">            
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="current_pass">Current Password <span class="text-danger">*</span></label>
                                            <input type="password" name="current_pass" value="<?= set_value('current_pass'); ?>" id="current_pass" class="form-control" placeholder="Current Password" required="" />
                                            <span class="text-danger"><?= form_error('current_pass'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="new_pass">New Password <span class="text-danger">*</span></label>
                                            <input type="password" name="new_pass" value="<?= set_value('new_pass'); ?>" id="new_pass" class="form-control" placeholder="New Password" required="" />
                                            <span class="text-danger"><?= form_error('new_pass'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="confirm_pass">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" name="confirm_pass" value="<?= set_value('confirm_pass'); ?>" id="confirm_pass" class="form-control" placeholder="Confirm Password" required="" />
                                            <span class="text-danger"><?= form_error('confirm_pass'); ?></span>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="card-footer">
                                <button type="submit"class="btn bg-maroon">Change Password</button>
                            </div>
                        </div>
                    </form>
                    <!-- PASSWORD UPDATE END -->
                </div>
            </div>
        </div>
    </div>
</div>