<style type="text/css">
    .form-group {
        margin-bottom: 1rem;
    }
</style>

<section class="hero-section">
    <div class="banner-main">
        <div class="banner-inner">
            <div class="owl-carousel bannerslider">
                <?php if (!empty($sliders)) { foreach ($sliders as $key => $slider) { ?>
                <div class="item">
                    <img src="<?= base_url(); ?>uploads/slider/<?= $slider->image; ?>" />
                    <a href="<?= base_url() . $slider->url; ?>" class="btn btn-primary btn-banner">Explore More</a>
                </div>
                <?php } } ?>
            </div>
        </div>
    </div>
</section>

<section class="about-facts wow fadeInUp pt-0">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="row topqu">
                    <div class="col-md-4">
                        <div class="qua-bg">
                            <div class="uday-icon">
                                <img src="<?= base_url(); ?>web-assets/images/uday-icon.png" />
                            </div>
                            <div class="uday-box-img text-center">
                                <img src="<?= base_url(); ?>web-assets/images/qualityt.svg" />
                            </div>
                            <h4 class="text-center">Quality</h4>
                            <h6 class="text-center">We Believe in Quality</h6>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 mt-md-0">
                        <div class="qua-bg">
                            <div class="uday-icon">
                                <img src="<?= base_url(); ?>web-assets/images/uday-icon.png" />
                            </div>
                            <div class="uday-box-img text-center">
                                <img src="<?= base_url(); ?>web-assets/images/trust.svg" />
                            </div>
                            <h4 class="text-center">Trust</h4>
                            <h6 class="text-center">We Believe in Trust</h6>
                      </div>
                    </div>
                    <div class="col-md-4 mt-4 mt-md-0">
                        <div class="qua-bg">
                            <div class="uday-icon">
                                <img src="<?= base_url(); ?>web-assets/images/uday-icon.png" />
                            </div>
                            <div class="uday-box-img text-center">
                                <img src="<?= base_url(); ?>web-assets/images/innovation.svg" />
                            </div>
                            <h4 class="text-center">Innovation</h4>
                            <h6 class="text-center">We Believe in Innovation</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mt-4 mt-md-0">
                <div class="social-t-bg">
                    <div class="uday-icon">
                        <img src="<?= base_url(); ?>web-assets/images/uday-icon.png" />
                    </div>
                    <h3 class="text-center">We Also Available on</h3>
                    <div class="topp-social">
                        <a href="https://www.instagram.com/UdayPlastic" title="Instagram" target="_blank">
                            <img src="<?= base_url(); ?>web-assets/images/instagram.svg"/>
                        </a>
                        <a href="https://www.facebook.com/Udayplastic" title="Facebook" target="_blank">
                            <img src="<?= base_url(); ?>web-assets/images/facebook.svg"/>
                        </a>
                        <a href="https://www.youtube.com/@UdayPlastic" title="You Tube" target="_blank">
                            <img src="<?= base_url(); ?>web-assets/images/youtube.svg"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="out-product-container pt-0">
    <div class="container">
        <div class="headingsty text-center">
            <h2>Our Products</h2>
        </div>

        <div class="ourpro-proslider owl-carousel">
            <?php if ($products) { foreach ($products as $key => $pdata) { ?>
                <div class="item">
                    <div class="productbox">
                        <a href="javascript:void(0);">
                            <div class="productsimg">
                                <img src="<?= base_url(); ?>uploads/webproducts/<?= $pdata->image; ?>" class="" />
                            </div>
                            <h4><?= $pdata->name; ?></h4>
                        </a>
                        <div class="product-overlay">
                            <a href="javascript:void(0);" onclick="onEnquiryFormHandler(<?= $pdata->id; ?>);" class="btn btn-primary px-4">
                                Enquiry
                            </a>
                        </div>
                    </div>
                </div>
            <?php } } ?>
        </div>

        <!-- <div class="row">
            <?php if ($products) { foreach ($products as $key => $pdata) { ?>
            <div class="col-md-6 col-lg-3 wow fadeInUp">
                <div class="productbox">
                    <a href="javascript:void(0);">
                        <div class="productsimg">
                            <img src="<?= base_url(); ?>uploads/webproducts/<?= $pdata->image; ?>" class="" />
                        </div>
                        <h4><?= $pdata->name; ?></h4>
                    </a>
                    <div class="product-overlay">
                        <a href="javascript:void(0);" onclick="onEnquiryFormHandler(<?= $pdata->id; ?>);" class="btn btn-primary px-4">
                            Enquiry
                        </a>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div> -->

        <div class="text-center view-btn"><a href="<?= base_url(); ?>products">View All Products <i class="fa fa-long-arrow-right"></i></a></div>

    </div>
</section>

<section class="about-facts wow fadeInUp pt-0">
    <div class="container">
        <div class="headingsty mt-0 mb-5 text-center">
        <h2>5000+ Buyers Across The World</h2>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="factsbox d-flex align-items-center mb-3">
                    <div class="factsicon"><img src="<?= base_url(); ?>web-assets/images/states.svg"></div>
                    <div class="factstxt text-center ps-3"><h4>20</h4> State of Operations</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="factsbox d-flex align-items-center mb-3">
                    <div class="factsicon"><img src="<?= base_url(); ?>web-assets/images/distributors.svg"></div>
                    <div class="factstxt text-center ps-3"><h4>200</h4> Distributors</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="factsbox d-flex align-items-center">
                    <div class="factsicon"><img src="<?= base_url(); ?>web-assets/images/dealers.svg"></div>
                    <div class="factstxt text-center ps-3"><h4>2000</h4> Dealers Netwrok</div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="service-container bg-light wow fadeInUp">
    <div class="container">
        <div class="headingsty text-center">
            <h2>Our Services Area</h2>
            <p class="mt-3">Leading producer of pipes, Fittings, Profiles and Engineering plastic extruded & molded products</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card-box services-box text-center">
                    <a href="#">
                        <div class="serviceicon text-center">
                            <img src="<?= base_url(); ?>web-assets/images/electrical.svg" />
                        </div>
                        <h3>Electrical</h3>
                        <p>Uday Plastic, profiles and fittings undergo extensive testing and inspection in its manufacturing facilities.</p>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-box services-box text-center">
                    <a href="#">
                        <div class="serviceicon text-center">
                            <img src="<?= base_url(); ?>web-assets/images/hardware.svg" />
                        </div>
                        <h3>Hardware and Sanatory</h3>
                        <p>Our four advanced plants are located in Rajasthan region and use state of the art materials and technology.</p>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-box services-box text-center"> 
                    <a href="#">
                        <div class="serviceicon text-center">
                            <img src="<?= base_url(); ?>web-assets/images/agriculture.svg" />
                        </div>
                        <h3>Agriculture</h3>
                        <p>Our R&D teams research and develops new products at our in-house facility in accordance with customers demand.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-container wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-lg-6">
                <img src="<?= base_url(); ?>web-assets/images/about-img.png" class="img-fluid"/>
            </div>
            <div class="col-md-7 col-lg-6">
                <div class="headingsty mt-4 mt-md-0 mb-4">
                    <h2>Our Company</h2>
                </div>
                <p class="font-medium" style="text-align: justify;">
                    Well Established Firm When you put your trust in <b>UDAYPLASTIC</b>, you are enlisting the expertise of an organization that has been exceeding the expectations of customers since its founding in 2011.
                </p>
                <p style="text-align: justify;">
                    As an electrical contractor, we offer the peace of mind, confidence, and reliability that come with experience and a commitment to customer satisfaction.
                </p>
                <a href="<?= base_url(); ?>aboutus" class="btn btn-primary px-4">Read More</a>
            </div>
        </div>
    </div>
</section>

<section class="out-product-container wow fadeInUp">
    <div class="container">
        <div class="headingsty text-center">
            <h2>Upcoming Products</h2>
        </div>
        <div class="upcoming-proslider owl-carousel">
            <?php if ($UpcomingProducts) { foreach ($UpcomingProducts as $key => $updata) { ?>
            <div class="item">
                <div class="productbox">
                    <a href="javascript:void(0);">
                        <div class="productsimg">
                            <img src="<?= base_url(); ?>uploads/webproducts/<?= $updata->image; ?>" class="" />
                        </div>
                        <h4><?= $updata->name; ?></h4>
                    </a>
                    <div class="product-overlay">
                        <a href="javascript:void(0);" onclick="onEnquiryFormHandler(<?= $updata->id; ?>);" class="btn btn-primary px-4">
                            Enquiry
                        </a>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>  
</section>

<section class="our-certifications">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="headingsty mb-0">
                    <h2>Our Certificates</h2>
                </div>
                <p>We are the first choice for a collaborative partnership to optimize enterprises. Our certifications are a testimony to our consistency in quality.</p>
            </div>
            <div class="col-lg-8">
                <div class="certificates-slider owl-carousel">
                    <div class="item">
                        <div class="certificates-box"><img src="<?= base_url(); ?>web-assets/images/tradmark.webp"/></div>
                    </div>
                    <div class="item">
                        <div class="certificates-box"><img src="<?= base_url(); ?>web-assets/images/rajsthan-polution.png"/></div>
                    </div>
                    <div class="item">
                        <div class="certificates-box"><img src="<?= base_url(); ?>web-assets/images/MSME_logo.png"/></div>
                    </div>
                    <div class="item">
                        <div class="certificates-box"><img src="<?= base_url(); ?>web-assets/images/khadi.jpg"/></div>
                    </div>
                    <div class="item">
                        <div class="certificates-box"><img src="<?= base_url(); ?>web-assets/images/9001.png"/></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="our-plant-section bg-light">
    <div class="container-fluid pe-0">
        <div class="row me-0">
            <div class="col-lg-6 order-lg-last ps-0 pe-0">
                <img src="<?= base_url(); ?>web-assets/images/production-unit.png" class="w-100" />
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-5 pe-lg-5 align-self-center mt-3 mt-lg-0">
                <div class="headingsty mb-4">
                    <h2><span>Our</span> Production Unit</h2>
                </div>
                <h6 class="mb-4" style="color: #742220;">
                    Leading producer of pipes, Fittings, Profiles and Engineering plastic extruded & molded products
                </h6>
                <p>
                    Uday Plastic, profiles and fittings undergo extensive testing and inspection in its manufacturing facilities.
                </p>

                <h5>Unit Location</h5>
                <p>04 Nayapura bas Atbara Sojat City Pali, Rajasthan-306104, India</p>
                <a href="#" class="readmore">Get Direction<i class="fa fa-long-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="our-clients">
    <div class="container" style="max-width: 1024px;">
        <div class="clients-slider owl-carousel">
            <?php if (!empty($MiniSliders)) { foreach ($MiniSliders as $key => $SliderData) { ?>
            <div class="item">
                <div class="logo-box">
                    <img src="<?= base_url(); ?>uploads/MiniSlider/<?= $SliderData->Image; ?>" />
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</section>

<?php include('common/enquiry.php'); ?>

<section class="mapsection">
    <div class="container" style="max-width: 640px;">
    <div class="headingsty text-center">
        <h2>National Exposure</h2>
    </div>
        <div class="text-center">
            <img src="<?= base_url(); ?>web-assets/images/india-map.svg" class="img-fluid" />
        </div>
    </div>
</section>

<div class="modal fade" id="EnquiryForm" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>
