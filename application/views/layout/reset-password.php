<style type="text/css">
    section.login-container .form-group i {
        top: 0px;
    }
    .topheadingsection {
        height: calc(45vh - 90px);
    }
</style>

<div class="topheadingsection">
    <div class="container text-center">
        <h1>Reset Password</h1>
    </div>
</div>

<section class="login-container">
    <div class="container">
        <div class="shadow rounded">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-6 pe-lg-0 d-none d-lg-block overflow-hidden">
                    <img src="<?= base_url(); ?>web-assets/images/login-img1.jpg" class="login-img"/>
                </div>
                <div class="col-lg-6 ps-lg-0">
                    <div class="card border-0">
                        
                        <div class="card-body">
                            <h4 class="text-center mb-4">Reset Password</h4>
                            <form action="<?= base_url('reset-password'); ?>" method="POST">
                                <div class="form-group mb-3">
                                    <i class="fa fa-envelope"></i>
                                    <input type="tel" name="otp" value="<?= set_value('otp'); ?>" id="otp" class="form-control rounded-pill" placeholder="Enter OTP" required="" minlength="6" maxlength="6" />
                                    <span class="text-danger"><?= form_error('otp'); ?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <i class="fa fa-lock"></i>
                                    <input type="password" name="password" value="<?= set_value('password'); ?>" id="password" class="form-control rounded-pill" placeholder="Enter Password" required="" />
                                    <span class="text-danger"><?= form_error('password'); ?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <i class="fa fa-repeat"></i>
                                    <input type="password" name="cpassword" value="<?= set_value('cpassword'); ?>" id="cpassword" class="form-control rounded-pill" placeholder="Enter Confirm Password" required="" />
                                    <span class="text-danger"><?= form_error('cpassword'); ?></span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                                </div>
                            </form>
                            <p class="text-center mt-3 mb-2">
                                <a href="<?= base_url(); ?>signin" class="pull-left">
                                    Back to Login
                                </a>
                                <a href="<?= base_url(); ?>forgot-password" class="pull-right">
                                    Forgot Password
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>