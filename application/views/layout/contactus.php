<section class="wow fadeInUp landing-banner py-5">
    <div class="landing-row">
        <div class="banner-text-col px-md-5 align-self-center">
        <h1 class="mb-4">We're only a phone call away!</h1>
            <p class="mb-4">
                I’ll always be there for you, all you have to do is ask.
            </p>
        </div>
        <div class="banner-img-col align-self-center">
            <img src="<?= base_url(); ?>web-assets/images/con-banner.png" alt="help-organisations-create-solutions" class="w-100"/>
        </div>
    </div>
</section>

<section class="my-5">
    <div class="container">
        <h2 class="text-center">Drop us a line to Start a Project with us</h2>
        <div class="contact-detial">
            <div class="contact-box br-btm-br-rt">
                <div class="icon">
                    <img src="<?= base_url(); ?>web-assets/images/contact-form.svg" alt="contact-form"/>
                </div>
                <div class="contact-row">
                    <p>Ready to share a Project?</p>
                    <a href="#">Submit a Form  <span class="fa fa-long-arrow-right"></span></a>
                </div>
            </div>
            <div class="contact-box br-btm">
                <div class="icon">
                    <img src="<?= base_url(); ?>web-assets/images/contact-mail.svg" alt="contact-mail" />
                </div>
                <div class="contact-row">
                    <p>Share a detailed specification</p>
                    <a href="mailto:info@udayplastic.co.in">info@udayplastic.co.in <span class="fa fa-long-arrow-right"></span></a>
                </div>
            </div>

            <div class="contact-box br-rt">
                <div class="icon">
                    <img src="<?= base_url(); ?>web-assets/images/contact-whatsapp.svg" alt="contact-whatsapp"/>
                </div>
                <div class="contact-row flex-grow-1">
                    <p>Connect on WhatsApp</p>
                    <div class="whatslnks mb-2">
                        <a href="https://api.whatsapp.com/send?phone=919202400767&amp;text=Hello I am interested in knowing more about developing a project." target="_blank">
                            +91-9202 400 767 <span class="fa fa-long-arrow-right"></span>
                        </a>
                        <br />
                        <a href="https://api.whatsapp.com/send?phone=916383880776&amp;text=Hello I am interested in knowing more about developing a project." target="_blank">
                            +91-6383 880 776 <span class="fa fa-long-arrow-right"></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="contact-box show_msg_box">
                <div class="icon">
                    <img src="<?= base_url(); ?>web-assets/images/contact-chat.svg" alt="contact-chat"/>
                </div>
                <div class="contact-row">
                    <p>Begin a quick discussion</p>
                    <a href="#" target="_blank">Live Chat with Us <span class="fa fa-long-arrow-right"></span></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="our-leaders bg-light py-5 mt-5">
    <div class="container">
        <h2 class="text-center">Our offices</h2>

        <div class="office-box mt-5">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <div class="off-con-box">
                        <h6>Registered Office</h6>
                        <p class="addres-p">04 Nayapura bas Atbara Sojat City Pali, Rajasthan-306104, India</p>
                        <p class="mb-0"><strong>Phone:</strong> <a href="tel:+916383880776">+916383 880776</a>
                        <p><strong>Email:</strong> <a href="mainlto:info@udayplastic.co.in">info@udayplastic.co.in</a></p>
                        <a href="#" target="_blank" class="btnlink">Get Direction<span class="fa fa-long-arrow-right"></span></a>
                    </div>
                </div>

                <div class="col-md-3 col-lg-3 mt-4 mt-md-0">
                    <div class="off-con-box">
                        <h6>Corporate Office</h6>
                        <p class="addres-p">Plot No. 375, Mandakini Colony Kolar Road, Bhopal - 462042</p>
                        <p class="mb-0"><strong>Phone:</strong> <a href="tel:+919202400767">+91 92024 00767</a>
                        <p><strong>Email:</strong> <a href="mainlto:info@udayplastic.co.in">info@udayplastic.co.in</a></p>
                        <a href="#" target="_blank" class="btnlink"> Get Direction<span class="fa fa-long-arrow-right"></span></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="my-5">
    <div class="container">
        <div class="contact-detial justify-content-center">
            <div class="contact-box">
                <div class="icon"><img src="<?= base_url(); ?>web-assets/images/contact-job.svg" alt="contact-job-careers"/></div>
                <div class="contact-row">
                    <p>Looking for Jobs?</p>
                    <a href="<?= base_url(); ?>career">Current Job Openings <span class="fa fa-long-arrow-right"></span></a>
                </div>
            </div>

            <div class="contact-box show_msg_box">
                <div class="icon"><img src="<?= base_url(); ?>web-assets/images/contact-job.svg" alt="covid-digitally-equipped"/></div>
                <div class="contact-row">
                    <p>Looking for Franchise?</p>
                    <a href="#">Make an Enquiry <span class="fa fa-long-arrow-right"></span></a>
                </div>
            </div>

        </div>
        </div>
    </div>
</section>

<section class="inner-container">
    <div class="container" style="max-width:991px">
        <div class="headingsty text-center">
            <h2><span>Ready</span> to start</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                <div class="mt-4 mt-lg-0 enquiry-form-con">
                    <form class="enquiryfoms-sty contactus" action="<?= base_url(); ?>index/enquiry" method="POST">
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
                        
                        <div class="form-group d-grid mb-0">
                            <button type="submit" class="btn btn-primary rounded btn-full">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- <section class="google_map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14334.580729736017!2d73.72695198732501!3d26.077849907848876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3969f19060e66b11%3A0x567db604b90c05bb!2sAtbara%2C%20Rajasthan!5e0!3m2!1sen!2sin!4v1682217882983!5m2!1sen!2sin" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section> -->