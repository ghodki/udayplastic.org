<style type="text/css">
    .background-radial-gradient {
        background-color: hsl(218, 41%, 15%);
        background-image: radial-gradient(650px circle at 0% 0%, hsl(218, 41%, 35%) 15%, hsl(218, 41%, 30%) 35%, hsl(218, 41%, 20%) 75%, hsl(218, 41%, 19%) 80%, transparent 100%), radial-gradient(1250px circle at 100% 100%, hsl(218, 41%, 45%) 15%, hsl(218, 41%, 30%) 35%, hsl(218, 41%, 20%) 75%, hsl(218, 41%, 19%) 80%, transparent 100%);
    }
    #radius-shape-1 {
        height: 220px;
        width: 220px;
        top: -60px;
        left: -130px;
        background: radial-gradient(#44006b, #ad1fff);
        overflow: hidden;
    }
    #radius-shape-2 {
        border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
        bottom: -60px;
        right: -110px;
        width: 300px;
        height: 300px;
        background: radial-gradient(#44006b, #ad1fff);
        overflow: hidden;
    }
    .bg-glass {
        background-color: hsla(0, 0%, 100%, 0.9) !important;
        backdrop-filter: saturate(200%) blur(25px);
    }
</style>

<div class="after-header wow fadeInUp">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb rounded-0 bg-light">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Log In</li>
        </ol>
    </nav>
</div>

<!-- Section: Design Block -->
<section class="background-radial-gradient overflow-hidden">
    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center mb-5">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%);">
                    The best offer
                    <br />
                    <span style="color: hsl(218, 81%, 75%);">for your business</span>
                </h1>
                <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%);">
                    Offers are coming soon....
                </p>
            </div>

            <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>
                <div class="card bg-glass">
                    <div class="card-body px-4 py-5 px-md-5">
                        <?php if ($this->session->flashdata('msg')) { ?>
                        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                            <?= $this->session->flashdata('msg'); ?>
                        </div>
		                  <?php session_destroy(); } ?>
                        <form name="formdata" action="<?= base_url('signin/auth'); ?>" method="POST">
                            <div class="form-group">
                                <input type="email" name="email" value="<?= set_value('email'); ?>" id="loginId" class="form-control" placeholder="Email" required="" />
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" value="<?= set_value('password'); ?>" class="form-control" placeholder="Password" required="" minlength="6" maxlength="15" />
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">Sign In</button>
			</form>
			<br /><br />
			<div class="text-center text-small mt-3">
                            Don't have an account? 
                            <a href="<?= base_url('signin/create'); ?>">
                                Sign up here
                            </a>
			</div>
			<div class="text-center text-danger text-small mt-3">
                            Forgot Password? 
                            <a href="<?= base_url('signin/forget'); ?>">
                                Forgot Password
                            </a>
			</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section: Design Block -->