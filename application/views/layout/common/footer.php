<!-- <section class="our-branches">
    <div class="container">
        <div class="headingsty text-center">
            <h2><span>Our</span> Branches</h2>
        </div>
        <div class="row">
            <?php if (!empty($Branches)) { foreach ($Branches as $key => $Branch) { ?>
            <div class="col-lg-4">
                <div class="branch-box">
                    <h4><?= htmlspecialchars(isset($Branch->Title) ? $Branch->Title : '', ENT_QUOTES, 'UTF-8'); ?></h4>
                    <h5>+91-<?= htmlspecialchars(isset($Branch->Phone) ? $Branch->Phone : (isset($Branch->phone) ? $Branch->phone : ''), ENT_QUOTES, 'UTF-8'); ?></h5>
                    <p><?= htmlspecialchars(isset($Branch->Address) ? $Branch->Address : '', ENT_QUOTES, 'UTF-8'); ?></p>
                    <a href="<?= htmlspecialchars(isset($Branch->Location) ? $Branch->Location : '#', ENT_QUOTES, 'UTF-8'); ?>" class="readmore" target="_blank" title="Get Direction">
                        Get Direction <i class="fa fa-long-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-lg-5 align-self-center">
                <img src="<?= base_url(); ?>web-assets/images/logo.svg"/>
                <p class="mt-4" style="text-align: justify;">
                    Well Established Firm When you put your trust in UDAYPLASTIC, you are enlisting the expertise of an organization that has been exceeding the expectations of customers since its founding in 2011.
                </p>
            </div>

            <div class="col-md-4">
                <h4 class="mb-3">Quick Links</h4>
                <div class="footerlinks">
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="<?= base_url(); ?>">Home</a>
                            <a href="<?= base_url(); ?>aboutus">About Us</a>
                            <a href="<?= base_url(); ?>products">Products</a>
                            <a href="<?= base_url(); ?>career">Career</a>
                        </div>
                        <div class="col-sm-6">
                            <a href="<?= base_url(); ?>contactus">Contact Us</a>
                            <a href="<?= base_url(); ?>downloads">Downloads</a>
                            <a href="<?= base_url(); ?>privacy-policy">Privacy Policy</a>
                            <a href="<?= base_url(); ?>terms-of-use">Terms of Use</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mt-3 mt-lg-0">
                <h4 class="mb-3">Social Connect</h4>
                <div class="d-flex socialicons">
                    <a href="https://www.facebook.com/Udayplastic" class="fa fa-facebook" target="_blank"></a>
                    <a href="https://www.instagram.com/Udayplastic" class="fa fa-instagram"></a>
                    <a href="https://www.youtube.com/@UdayPlastic" class="fa fa-youtube" target="_blank"></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <hr style="border-bottom: 0.5px solid #ffffff;" />
                <img src="<?= base_url(); ?>web-assets/images/logo.svg" alt="udayplastic" />
                <h3 class="text-white" style="text-transform: inherit; margin-top: 20px;">
                    Download the Uday Plastic Electrician App
                </h3>
            </div>
            <div class="col-md-12 text-center mt-2">
                <a href="<?= base_url(); ?>sample/udayPlasticElectrician.apk" title="Download Uday Plastic Electrician App" download="">
                    <img src="<?= base_url('assets/images/androidDownload.png'); ?>" alt="udayPlasticElectrician" style="width: 200px; border-radius: 25px;" />
                </a>
            </div>
        </div>        
    </div>

    <div class="footer-bottom border-top">
        <div class="container">
            <div class="d-flex flex-warp">
                <p class="mb-0">Copyright © 2021 Uday Plastic. All rights reserved.</p><span class="px-2">|</span> 
                <p class="mb-0">Design and developed by <a href="https://www.alinasoftwares.com" target="_blank">Alina Softwares</a></p>
                <p class="mb-0 btn btn-outline-primary" style="margin-left: 50px;">
                    <?php
                        $ip_all = file("ip.txt");
                        $ip_cur = $_SERVER['REMOTE_ADDR']."\n";
                        if (!in_array($ip_cur, $ip_all)) {
                            $ip_all[] = $ip_cur;
                            file_put_contents("ip.txt", implode($ip_all));
                        }

                        echo "Visitors : " . count($ip_all);
                    ?>
                </p>
            </div>
        </div>
    </div>
</footer> -->

<script type="text/javascript" src="<?= base_url(); ?>web-assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>web-assets/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>web-assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>web-assets/js/wow.min.js"></script> 
<script type="text/javascript" src="<?= base_url(); ?>web-assets/js/lightbox.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>web-assets/js/script.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>dist/js/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>web-assets/js/cloud-zoom.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.owl-carousel_1').owlCarousel({
            loop: true,
            margin: 5,
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            autoplay: true,
            autoplayTimeout: 5000,
            items: 3,
            dots: false,
            lazyLoad: true
        });
    });
    
    const onEnquiryFormHandler = (Id) => {
        jQuery.noConflict();
        jQuery("#EnquiryForm .modal-dialog .modal-content").load("<?= base_url(); ?>Index/EnquiryForm/" + Id);
        jQuery('#EnquiryForm').modal('show');
    };
    
    const onEnquiryFormSubmitHandler = (event) => {
        jQuery.noConflict();
        jQuery("#submitButton").prop("disabled", true);
        event.preventDefault();
        var Organization = jQuery("#Organization").val();
        var ContactPerson = jQuery("#ContactPerson").val();
        var Mobile = jQuery("#Mobile").val();
        var ProductName = jQuery("#ProductName").val();
        var Quantity = jQuery("#Quantity").val();
        
        if (Organization && ContactPerson && Mobile && ProductName && Quantity) {
            jQuery.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Index/Save",
                data    :   jQuery("#ProductEnquiryForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                    window.setTimeout(function() {
                                        jQuery('#EnquiryForm').modal('hide');
                                        jQuery("#ProductId").val("");
                                        jQuery("#Organization").val("");
                                        jQuery("#ContactPerson").val("");
                                        jQuery("#Mobile").val("");
                                        jQuery("#Email").val("");
                                        jQuery("#ProductName").val("");
                                        jQuery("#Quantity").val("");
                                        jQuery("#Remark").val("");
                                        jQuery("#submitButton").prop("disabled", false);
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                    jQuery("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 3000
            });
            jQuery("#submitButton").prop("disabled", false);
        }
    };
    
    const onEnquiryFormCloseHandler = () => {
        jQuery('#EnquiryForm').modal('hide');
    };
    setTimeout(function () {
        jQuery('.alert').hide(5000, function () {
            jQuery(this).remove();
        });
    }, 5000);
</script>