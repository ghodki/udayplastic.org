<?php
    if (empty($EmployeeInfo)) {
        $EmployeeInfo = (object) [
            'Photograph' => '',
            'Name' => '',
            'EmployeeId' => '',
            'Mobile' => '',
            'Email' => '',
            'TemporaryAddress' => '',
        ];
    }
?>
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Profile</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?> pb-2">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="header-title">
                                Information
                            </h3>
                        </div>
                        <div class="card-boby">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Profile Image -->
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <?php if ($EmployeeInfo->Photograph) { ?>
                                                <img class="profile-user-img img-fluid img-circle" src="<?= base_url(); ?>uploads/Employee/Photograph/<?= $EmployeeInfo->Photograph; ?>" alt="user-profile-picture" />
                                                <?php } else { ?>
                                                <img class="profile-user-img img-fluid img-circle" src="<?= base_url(); ?>assets/images/avatar.png" alt="user-profile-picture" />
                                                <?php }?>
                                            </div>
                                            <h3 class="profile-username text-center">
                                                <?= $EmployeeInfo->Name; ?>
                                            </h3>
                                            <p class="text-muted text-center">
                                                <?= $EmployeeInfo->EmployeeId; ?>
                                                <br />
                                                <?= $this->session->userdata('RoleType'); ?>
                                            </p>
                                            <strong><i class="fas fa-mobile-phone mr-1"></i> Mobile</strong>
                                            <p class="text-muted">
                                                +91-<?= $EmployeeInfo->Mobile; ?>
                                            </p>
                                            <hr />
                                            <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                                            <p class="text-muted">
                                                <?= $EmployeeInfo->Email; ?>
                                            </p>
                                            <hr />
                                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                                            <p class="text-muted"><?= $EmployeeInfo->TemporaryAddress; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Change Password</h5>
                                        <hr />
                                        <form action="<?= base_url(); ?>Employee/ChangePassword" method="POST">
                                            <div class="row">
                                                <div class="col-md-10 form-group">
                                                    <label for="CurrentPassword">Current Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="CurrentPassword" value="<?= set_value('opassword'); ?>" id="CurrentPassword" class="form-control form-control-sm" placeholder="Current Passowrd" required="" minlength="6" maxlength="15" />
                                                    <span class="text-danger"><?= form_error('CurrentPassword'); ?></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10 form-group">
                                                    <label for="NewPassword">New Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="NewPassword" value="<?= set_value('NewPassword'); ?>" id="NewPassword" class="form-control form-control-sm" placeholder="New Passowrd" required="" minlength="6" maxlength="15" />
                                                    <span class="text-danger"><?= form_error('NewPassword'); ?></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10 form-group">
                                                    <label for="ConfirmPassword">Confirm Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="ConfirmPassword" value="<?= set_value('ConfirmPassword'); ?>" id="ConfirmPassword" class="form-control form-control-sm" placeholder="Confirm Passowrd" required="" minlength="6" maxlength="15" />
                                                    <span class="text-danger"><?= form_error('ConfirmPassword'); ?></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10 form-group">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-save"></i> Save Changes
                                                    </button>
                                                    <button type="reset" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-refresh"></i> Reset
                                                    </button>
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
    </section>
    <!-- /.content -->
 </div>