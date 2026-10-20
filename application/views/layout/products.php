<div class="topheadingsection">
    <div class="container text-center">
        <h1>Provide Reliable and High-Quality Products</h1>
        <p class="px-md-5 font-22">
            All Electric Fitting Solution
        </p>
    </div>
</div>

<section class="inner-container">
    <div class="container">
        <div class="headingsty text-center">
            <h2><span>Products</span> Lists</h2>
        </div>
        <div class="row productpage-con">
            <?php if ($products) { foreach ($products as $key => $pdata) { ?>
            <div class="col-md-6 col-lg-3 wow fadeInUp">
                <div class="productbox">
                    <a href="<?= base_url(); ?>product-info/<?= strtolower(str_replace(" ", "-", $pdata->url_name)); ?>">
                        <div class="productsimg">
                            <img src="<?= base_url(); ?>uploads/webproducts/<?= $pdata->image; ?>" class="" />
                        </div>
                        <h4><?= $pdata->name; ?></h4>
                    </a>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</section>

<?php include('common/enquiry.php'); ?>