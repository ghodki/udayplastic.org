<style type="text/css">
    .text-danger>p {
        color: #ff0000;
    }
    .password-container {
        position: relative;
        margin-bottom: 20px;
    }
    .password-input {
        padding-right: 30px;
    }
    .password-toggle-btn {
        transform: translateY(-50%);
        cursor: pointer;
        position: relative !important;
        left: 280px !important;
        right: 0px;
        bottom: 20px;
        border-right: 0px !important;
        top: -23px !important;
    }
    .password-toggle-btn:hover {
        color: #333;
    }
    @media (max-width: 400px) {
        .password-toggle-btn {
            left: 250px !important;
        }
    }
    .topheadingsection {
        height: calc(45vh - 90px);
    }
</style>

<div class="topheadingsection">
    <div class="container text-center">
        <h1>Sign in</h1>
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
                    <?php session_destroy(); } ?>
                </div>
                <div class="col-lg-6 pe-lg-0 d-none d-lg-block overflow-hidden">
                    <img src="<?= base_url(); ?>web-assets/images/login-img1.jpg" class="login-img"/>
                </div>
                <div class="col-lg-6 ps-lg-0">
                    <div class="card border-0">
                        
                        <div class="card-body">
                            <h4 class="text-center mb-4">User Login</h4>
                            <form name="formdata" action="<?= base_url('signin/auth'); ?>" method="POST">
                                <div class="form-group mb-3">
                                    <i class="fa fa-envelope"></i>
                                    <label for="email">Enter Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control rounded-pill" placeholder="Enter Email" required="" />
                                    <span class="text-danger"><?= form_error('email'); ?></span>
                                </div>
                                <div class="form-group mb-3">
                                    <i class="fa fa-lock"></i>
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" value="<?= set_value('password'); ?>" class="form-control rounded-pill password-input" id="password" placeholder="Enter Password" required="" />
                                    <div class="password-container">
                                        <i class="fa fa-eye password-toggle-btn" onclick="togglePasswordVisibility()"></i>
                                    </div>
                                    <span class="text-danger"><?= form_error('password'); ?></span>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">Login</button>
                                </div>
                            </form>
                            <p class="text-center mt-3 mb-2">
                                <a href="<?= base_url(); ?>forgot-password">
                                    Forgot Password
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

<script type="text/javascript">
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var icon = document.querySelector(".password-toggle-btn");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>