<span id="Makeenquiry"></span>

<section class="footer-enquiry">
    <div class="container">
        <div class="headingsty">
            <h2><span>Get</span> in Touch</h2>
        </div>
        <div class="row">            
            <div class="col-lg-6">
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                <form class="enquiryfoms-sty" action="<?= base_url(); ?>index/enquiry_customer" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" placeholder="Name" required="" />
                                <span class="text-danger"><?= form_error('name'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control" placeholder="Email" required="" />
                                <span class="text-danger"><?= form_error('email'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="tel" name="mobile" value="<?= set_value('mobile'); ?>" id="mobile" class="form-control" placeholder="Mobile No." maxlength="10" minlength="10" required="" />
                                <span class="text-danger"><?= form_error('mobile'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="message" id="message" class="form-control" rows="4" placeholder="Message" required=""></textarea>
                                <span class="text-danger"><?= form_error('message'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded btn-full">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="franchise-in-box">
                    <div class="headingsty">
                        <h2 class="text-light"><span class="text-light">For Franchise</span> Enquiry</h2>
                    </div>
                    <p>
                        We encourage those who are interested in franchising to follow the path of most <b class="text-white">UDAY PLASTIC</b> franchise.
                    </p>
                    <p><strong>Call Us:</strong> +917848886777</p>
                    <a href="#" class="btn btn-primary">Get a Franchise Enquiry</a>
                </div>
            </div>
        </div>
    </div>
</section>