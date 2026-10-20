<style type="text/css">
    .topheadingsection {
        height: calc(45vh - 90px);
    }
</style>

<div class="topheadingsection">
    <div class="container text-center">
        <h1>Sign Up</h1>
    </div>
</div>

<section class="login-container signupcon">
    <div class="container">
        <div class="shadow rounded">
            <div class="row">
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                <div class="col-lg-6  pe-lg-0 d-none d-lg-block overflow-hidden">
                    <img src="<?= base_url(); ?>web-assets/images/login-img1.jpg" class="login-img"/>
                </div>
                <div class="col-lg-6 ps-lg-0">
                    <div class="card border-0">
                        <div class="card-body">
                            <h4 class="text-center mb-4">New Sign Up</h4>
                            <form action="<?= base_url(); ?>signin/create" method="POST">
                                <div class="form-group mb-3">
                                    <i class="fa fa-user"></i>
                                    <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control rounded-pill" placeholder="Enter Name" required="" />
                                    <span class="text-danger"><?= form_error('name'); ?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <i class="fa fa-envelope"></i>
                                    <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control rounded-pill" placeholder="Email Address" required="" />
                                    <span class="text-danger"><?= form_error('email'); ?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <i class="fa fa-phone"></i>
                                    <input type="tel" name="number" value="<?= set_value('number'); ?>" id="number" class="form-control rounded-pill" placeholder="Mobile No." required="" maxlength="10" minlength="10" />
                                    <span class="text-danger"><?= form_error('number'); ?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <i class="fa fa-lock"></i>
                                    <input type="password" name="password" value="<?= set_value('password'); ?>" id="password" class="form-control rounded-pill" placeholder="Enter Password" required="" />
                                    <span class="text-danger"><?= form_error('password'); ?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Gender</label>
                                    <div class="d-flex">
                                        <div class="form-check me-4">
                                            <input type="radio" name="gender" value="Male" <?= set_radio('gender', 'Male'); ?> id="male" class="form-check-input" checked="" required="" />
                                            <label class="form-check-label" for="male">
                                                Male
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="gender" value="Female" <?= set_radio('gender', 'Female'); ?> id="female" class="form-check-input" required="" />
                                            <label class="form-check-label" for="female">
                                                Female
                                            </label>
                                        </div>
                                        <span class="text-danger"><?= form_error('gender'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                                </div>
                            </form>
                            <p class="text-center mt-3">
                                Have an account? 
                                <a href="<?= base_url(); ?>signin">
                                    Login Here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>