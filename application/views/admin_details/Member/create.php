<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <form method="POST" action="<?= base_url(); ?>Member/Save" enctype="multipart/form-data">
                        <div class="card card-outline card-maroon">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-maroon">
                                        Create
                                    </h4>
                                </div>
                                <a href="<?= base_url(); ?>Member" class="btn btn-info btn-sm pull-right" title="Members">
                                    <i class="fa fa-table"></i> Members
                                </a>
                            </div>
                            <div class="card-body">                                
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="FirstName">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="FirstName" value="<?= set_value('FirstName'); ?>" id="FirstName" class="form-control form-control-sm" placeholder="First Name" required="" />
                                            <span class="text-danger"><?= form_error('FirstName'); ?></span>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="LastName">First Last <span class="text-danger">*</span></label>
                                            <input type="text" name="LastName" value="<?= set_value('LastName'); ?>" id="LastName" class="form-control form-control-sm" placeholder="Last Name" required="" />
                                            <span class="text-danger"><?= form_error('LastName'); ?></span>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Designation">Designation <span class="text-danger">*</span></label>
                                            <input type="text" name="Designation" value="<?= set_value('Designation'); ?>" id="Designation" class="form-control form-control-sm" placeholder="Designation" required="" />
                                            <span class="text-danger"><?= form_error('Designation'); ?></span>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Mobile">Mobile</label>
                                            <input type="tel" name="Mobile" value="<?= set_value('Mobile'); ?>" id="Mobile" class="form-control form-control-sm" placeholder="Mobile" minlength="10" maxlength="10" />
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Email">Email</label>
                                            <input type="text" name="Email" value="<?= set_value('Email'); ?>" id="Email" class="form-control form-control-sm" placeholder="Email" />
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Photograph">Photograph</label>
                                            <input type="file" name="Photograph" id="Photograph" class="form-control form-control-sm" title="Photograph" accept="image/*" />
                                        </div>                                        
                                    </div>
                                </div>
                                    
                                <hr class="bg-gray" />
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Facebook">Facebook</label>
                                            <input type="text" name="Facebook" value="<?= set_value('Facebook'); ?>" id="Facebook" class="form-control form-control-sm" placeholder="Facebook" />
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="LinkedIn">LinkedIn</label>
                                            <input type="text" name="LinkedIn" value="<?= set_value('LinkedIn'); ?>" id="LinkedIn" class="form-control form-control-sm" placeholder="LinkedIn" />
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Twitter">Twitter</label>
                                            <input type="text" name="Twitter" value="<?= set_value('Twitter'); ?>" id="Twitter" class="form-control form-control-sm" placeholder="Twitter" />
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Instagram">Instagram</label>
                                            <input type="text" name="Instagram" value="<?= set_value('Instagram'); ?>" id="Instagram" class="form-control form-control-sm" placeholder="Instagram" />
                                        </div>                                        
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="Comment">Comment</label>
                                            <textarea name="Comment" id="Comment" class="form-control form-control-sm" placeholder="Comment"><?= set_value('Comment'); ?></textarea>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Position">Position <span class="text-danger">*</span></label>
                                            <select name="Position" id="Position" class="form-control form-control-sm" title="Position" required="">
                                                <option value="" selected="" disabled="">Select Position</option>
                                                <option value="LEFT" <?= set_select('Position', 'LEFT'); ?>>LEFT</option>
                                                <option value="RIGHT" <?= set_select('Position', 'RIGHT'); ?>>RIGHT</option>
                                                <option value="BOTTOM" <?= set_select('Position', 'BOTTOM'); ?>>BOTTOM</option>
                                            </select>
                                            <span class="text-danger"><?= form_error('Position'); ?></span>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Save
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>