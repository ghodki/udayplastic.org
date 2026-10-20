<style type="text/css">
    .topheadingsection {
        height: calc(45vh - 90px);
    }
</style>

<div class="topheadingsection">
    <div class="container text-center">
        <h1>Forgot Password</h1>
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
                            <h4 class="text-center mb-4">Forgot Password</h4>
                            <form name="formdata" action="<?= base_url('forgot-password'); ?>" method="POST">
                                <div class="form-group mb-3">
                                    <i class="fa fa-envelope"></i>
                                    <label>Registered Email</label>
                                    <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control rounded-pill" placeholder="Enter Registered Email" required="" />
                                    <span class="text-danger"><?= form_error('email'); ?></span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">Request OTP</button>
                                </div>
                            </form>
                            <p class="text-center mt-3 mb-2">
                                <a href="<?= base_url(); ?>signin">
                                    Back to Login
                                </a>
                            </p>
                            <p class="text-center">
                                Don't have an account? 
                                <a href="<?= base_url(); ?>signin/create">
                                    Sign Up Here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>