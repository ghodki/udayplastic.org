<style type="text/css">
    .arncopy i.fa-file-pdf-o {
        width: 40px;
        height: 40px;
        background: var(--base-color);
        border-radius: 50%;
        text-align: center;
        color: #fff;
        line-height: 39px;
        font-size: 18px;
        border: 1px solid var(--base-color);
    }
</style>

<div class="topheadingsection">
    <div class="container text-center">
        <p class="px-md-5 font-22">Electrical Product Firm Focused on Value</p>
        <h1 class="mb-5">Our employees, mission, and values are what distinguishes us.</h1>
    </div>
</div>

<div class="inner-content-container">
    <section class="whoweare">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Profile</h2>
                    <p style="text-align: justify;">
                        Well Established Firm When you put your trust in <b>UDAYPLASTIC</b>, you are enlisting the expertise of an organization that has been exceeding the expectations of customers since its founding in 2011. As an electrical contractor, we offer the peace of mind, confidence, and reliability that come with experience and a commitment to customer satisfaction.
                    </p>
                    <p style="text-align: justify;">
                        Comprehensive Solutions Provider The service specialists at <b>UDAYPLASTIC</b> know what a challenging task it can be to identify and solve even the simplest of electrical concerns. That’s why we go out of our way not only to offer comprehensive solutions for all your electrical needs, but to also provide you with the information and resources you need to make educated decisions about which solutions are best for you, and your project.
                    </p>
                    <p style="text-align: justify;">
                        Performance/Customer Service You deserve the best! <b>UDAYPLASTIC</b> believes that quality performance is a result of our ability to understand your specific needs and provide systematic and sustainable solutions to resolve the demands of your project.
                    </p>
                </div>
                <div class="col-lg-6">
                    <h2>Mission</h2>
                    <p style="text-align: justify;">
                        Our mission is to empower electrical systems with products that embody excellence, reliability, and innovation. We aspire to be the foremost choice for contractors, distributors, and end-users by consistently delivering conduit solutions that not only meet but exceed expectations. Embracing technological advancements, our mission extends to being a catalyst for progress in the electrical infrastructure sector. We aim to continually invest in research and development, ensuring our conduit solutions remain at the forefront of efficiency and sustainability.                        
                    </p>
                </div>
                <div class="col-lg-6">
                    <h2>Vision</h2>
                    <p style="text-align: justify;">
                        Our company's vision revolves around pioneering excellence, sustainability, and global impact in the electrical infrastructure sector
                    </p>
                    <ul>
                        <li>Industry Leadership</li>
                        <li>Cutting-Edge Technology</li>
                        <li>Global Presence</li>
                        <li>Sustainability</li>
                        <li>Customer-Centric Approach</li>
                        <li>Innovation Hub</li>
                        <li>Employee Empowerment</li>
                        <li>Safety Excellence</li>
                        <li>Community Engagement</li>
                        <li>Continuous Improvement</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="how-indu">
        <div class="container">
            <h2 class="text-center">Every Uday Plastic belief</h2>
            <div class="row mt-5">
                <div class="col-md-6 col-lg-4">
                    <img src="<?= base_url(); ?>web-assets/images/belief-people.svg" alt="client centric approach" />
                    <h4 class="my-3">Client-centric Approach</h4>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                </div>
                <div class="col-md-6 col-lg-4">
                    <img src="<?= base_url(); ?>web-assets/images/belief-research.svg" alt="effective collaboration" />
                    <h4 class="my-3">Effective Collaboration</h4>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                </div>
                <div class="col-md-6 col-lg-4">
                    <img src="<?= base_url(); ?>web-assets/images/belief-product-driven.svg" alt="quality delivered in time" />
                    <h4 class="my-3">Quality Delivered in Time</h4>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sectionhead bg-light py-5 mt-5">
        <div class="container text-center">
            <h2 style="font-weight:300;" class="px-lg-5">We've helped companies increase their income by 99% on average in their first year with us!</h2>
            <div class="row mt-5">
                <div class="col-12 col-md-4 col-lg-4 counters">
                    <span>550</span><span>+</span>
                    <p>Satisfied Customers</p>
                </div>
                <div class="col-12 col-md-4 col-lg-4 counters">
                    <span>1050</span><span>+</span>
                    <p>Products Delivered Successfully</p>
                </div>
                <div class="col-12 col-md-4 col-lg-4 counters">
                    <span>100</span><span>+</span>
                    <p>Experts Under the Same Roof</p>
                </div>
            </div>
        </div>
    </section>

    <section class="ceoworld my-5">
        <div class="container">
            <div class="">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="">
                            <div class="ceoimg">
                                <?php 
                                    $photographfile = $MemberLeft->Photograph;
                                    $photograph = 'uploads/Member/' . $photographfile;
                                    if (file_exists($photograph) && !empty($photographfile)) {
                                ?>
                                <img src="<?= base_url() . '/' . $photograph; ?>" alt="<?= $MemberLeft->Designation; ?>" />
                                <?php } else { ?>
                                <img src="<?= base_url(); ?>web-assets/images/photo.png" alt="<?= $MemberLeft->Designation; ?>" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="quotebox">
                            <div class="quote__msg">
                                <h2><?= $MemberLeft->FirstName; ?> <span><?= $MemberLeft->LastName; ?></span></h2>
                                <p><?= $MemberLeft->Designation; ?></p>
                                <div class="ceo-social">
                                    <a href="<?= $MemberLeft->Facebook; ?>" target="_blank" class="fa fa-facebook"></a>
                                    <a href="<?= $MemberLeft->LinkedIn; ?>" target="_blank" class="fa fa-linkedin"></a>
                                    <a href="<?= $MemberLeft->Twitter; ?>" target="_blank" class="fa fa-twitter"></a>
                                    <a href="<?= $MemberLeft->Instagram; ?>" target="_blank" class="fa fa-instagram"></a>
                                </div>
                                <h4>"<?= $MemberLeft->Comment; ?>"</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-lg-4 order-lg-last">
                        <div class="ceoimg rightquote">
                            <?php 
                                $photographfile1 = $MemberRight->Photograph;
                                $photograph1 = 'uploads/Member/' . $photographfile1;
                                if (file_exists($photograph1) && !empty($photographfile1)) {
                            ?>
                            <img src="<?= base_url() . '/' . $photograph1; ?>" alt="<?= $MemberRight->Designation; ?>" />
                            <?php } else { ?>
                            <img src="<?= base_url(); ?>web-assets/images/photo.png" alt="<?= $MemberRight->Designation; ?>" />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="quotebox">
                            <div class="quote__msg">
                                <h2><?= $MemberRight->FirstName; ?> <span><?= $MemberRight->LastName; ?></span></h2>
                                <p><?= $MemberRight->Designation; ?></p>
                                <div class="ceo-social">
                                    <a href="<?= $MemberRight->Facebook; ?>" target="_blank" class="fa fa-facebook"></a>
                                    <a href="<?= $MemberRight->LinkedIn; ?>" target="_blank" class="fa fa-linkedin"></a>
                                    <a href="<?= $MemberRight->Twitter; ?>" target="_blank" class="fa fa-twitter"></a>
                                    <a href="<?= $MemberRight->Instagram; ?>" target="_blank" class="fa fa-instagram"></a>
                                </div>
                                <h4>"<?= $MemberRight->Comment; ?>"</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="our-leaders bg-light py-5 mt-5">
        <div class="container">
            <h2 class="text-center">Our Leaders</h2>
            <div class="row mt-5">
                <?php if (!empty($Members)) { foreach ($Members as $key => $Member) { ?>
                <div class="col-6 col-lg-3 mb-4">
                    <div class="img-box">
                        <?php 
                            $photographfile2 = $Member->Photograph;
                            $photograph2 = 'uploads/Member/' . $photographfile2;
                            if (file_exists($photograph2) && !empty($photographfile2)) {
                        ?>
                        <img src="<?= base_url() . '/' . $photograph2; ?>" class="rounded" alt="<?= $Member->Designation; ?>" />
                        <?php } else { ?>
                        <img src="<?= base_url(); ?>web-assets/images/photo.png" class="rounded" alt="<?= $Member->Designation; ?>" />
                        <?php } ?>
                    </div>
                    <div class="member-detail">
                        <h6><?= $Member->FirstName; ?> <?= $Member->LastName; ?></h6>
                        <p><?= $Member->Designation; ?></p>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <p>Our Core Values</p>
                    <h2>Provide Reliable and High-Quality Products</h2>
                </div>
                <div class="col-lg-7">
                    <h3>Maximum Customer Satisfaction</h3>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

                    <h3 class="mt-5">Methodology Based on Results</h3>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

                    <h3 class="mt-5">Everything must be honest.</h3>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

                    <h3 class="mt-5">Excellent Working Environment</h3>
                    <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

                </div>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        <div id="gallery">
            <div id="image-gallery">
                <div class="row">
                    <?php if (!empty($certificates)) { foreach ($certificates as $key => $cdata) { ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 image">
                        <div class="img-wrapper">
                            <a href="<?= base_url(); ?>uploads/about/<?= $cdata->certificate; ?>">
                                <img src="<?= base_url(); ?>uploads/about/<?= $cdata->certificate; ?>" class="img-responsive" />
                            </a>
                            <div class="img-overlay">
                                View Details
                            </div>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>

        <div class="headingsty text-center mt-5">
            <h2><span>GST</span> Info</h2>
        </div>

        <div class="row justify-content-center pb-5">
            <?php if (!empty($gstinfo)) { foreach ($gstinfo as $key => $gdata) { ?>
            <div class="col-lg-6">
                <div class="card shadow h-100">
                    <div class="card-header light-bg-2">
                        <h4><?= $gdata->cname; ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="gstinfo">
                            <b>State:</b>
                            <span><?= $gdata->state; ?></span>
                        </div>
                        <div class="gstinfo">
                            <b>Location:</b>
                            <span><?= $gdata->location; ?></span>
                        </div>
                        <div class="gstinfo">
                            <b>Offices/Plant:</b>
                            <span><?= $gdata->offices; ?></span>
                        </div>
                        <div class="gstinfo">
                            <b>Provisional ID:</b>
                            <span><?= $gdata->provisional_id; ?></span>
                        </div>
                        <div class="gstaddress">
                            <b>Address:</b>
                            <p>
                                <?= $gdata->address; ?>
                            </p>
                        </div>
                        <div class="arncopy d-flex align-items-center">
                            <span>Download ARN Copy</span> 
                            <a href="<?= base_url(); ?>uploads/about/<?= $gdata->arn_copy; ?>" target="_blank" class="ms-3">
                                <i class="fa fa-file-pdf-o"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</div>

<?php include('common/enquiry.php'); ?>