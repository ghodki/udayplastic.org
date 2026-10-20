<style type="text/css">
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
        top: -19px !important;
    }
    .password-toggle-btn:hover {
        color: #333;
    }
    @media (max-width: 400px) {
        .password-toggle-btn {
            left: 320px !important;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <form action="<?= base_url(); ?>electrician/save" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="card card-navy card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="title text-navy">
                                        Create
                                    </h3>
                                </div>
                                <a href="<?= base_url(); ?>electrician" class="btn bg-navy btn-sm pull-right" title="Electrician Report">
                                    <i class="fa fa-list-ol"></i> Electrician Report
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="date" value="<?= set_value('date', date('Y-m-d')); ?>" id="date" class="form-control form-control-sm" title="Date" required="" />
                                        <span class="text-danger"><?= form_error('date'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control form-control-sm" placeholder="Name" required="" autocomplete="off" />
                                        <span class="text-danger"><?= form_error('name'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="mobile">Mobile <span class="text-danger">*</span></label>
                                        <input type="tel" name="mobile" value="<?= set_value('mobile'); ?>" id="mobile" class="form-control form-control-sm" placeholder="Mobile" required="" autocomplete="off" minlength="10" maxlength="10" />
                                        <span class="text-danger"><?= form_error('mobile'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control form-control-sm" placeholder="Email" autocomplete="off" />
                                        <span class="text-danger"><?= form_error('email'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="gender">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" class="form-control form-control-sm" title="Gender" required="">
                                            <option value="" selected="" disabled="">Select Gender</option>
                                            <option value="Male" <?= set_select('gender', 'Male'); ?>>Male</option>
                                            <option value="Female" <?= set_select('gender', 'Female'); ?>>Female</option>
                                        </select>
                                        <span class="text-danger"><?= form_error('gender'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" value="<?= set_value('password'); ?>" id="password" class="form-control form-control-sm" placeholder="Password" required="" autocomplete="off" />
                                        <div class="password-container">
                                            <i class="fa fa-eye password-toggle-btn" onclick="passwordVisibilityToggle();"></i>
                                        </div>
                                        <span class="text-danger"><?= form_error('password'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="upiId">UPI Id</label>
                                        <input type="text" name="upiId" value="<?= set_value('upiId'); ?>" id="upiId" class="form-control form-control-sm" placeholder="UPI Id" autocomplete="off" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="photograph">Photograph</label>
                                        <input type="file" name="photograph" id="photograph" class="form-control form-control-sm" title="Photograph" accept="image/*" />
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <button type="submit" class="btn btn-sm bg-navy">
                                            <i class="fa fa-save text-white"></i> Save
                                        </button>
                                        <button type="reset" class="btn btn-danger btn-sm">
                                            <i class="fa fa-refresh text-white"></i> Reset
                                        </button>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 </div>

<script type="text/javascript">
    const passwordVisibilityToggle = () => {
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
    };
</script>