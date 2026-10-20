<style type="text/css">
    .owl-nav {
        display: block !important;
    }
    .owl-carousel .owl-nav button.owl-prev i, .owl-carousel .owl-nav button.owl-next i {
        font-size: 65px;
    }
    .owl-carousel .owl-nav button.owl-prev, .owl-carousel .owl-nav button.owl-next {
        height: 70px;
    }    
</style>

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
        <div class="row">
            <div class="col-md-6">
                <?php
                    if ($productinfo->largeImage) {
                       $ImageName =  'webproductsLarge/' . $productinfo->largeImage;
                    } else {
                        $ImageName = 'webproducts/' . $productinfo->image;
                    }
                ?>
                <img src="<?= base_url(); ?>uploads/<?= $ImageName; ?>" id="product-zoom" class="img-fluid rounded mb-3" />
                <?php
                    $galleryImageQuery = $this->db->select('productImage')->where('productId', $productinfo->id)->get('web_product_images');
                    if ($galleryImageQuery->num_rows()) {
                ?>                
                <div id="gallery_01" class="owl-carousel_1 owl-carousel owl-theme slick-padding_2 slider-arrow-style slider-arrow-style__style-2">
                    <?php foreach ($galleryImageQuery->result() as $key => $image) { ?>
                    <div class="item">
                        <a href="javascript:void(0);" data-image="<?= base_url(); ?>uploads/webProductsGallery/<?= $image->productImage; ?>" data-zoom-image="<?= base_url(); ?>uploads/webProductsGallery/<?= $image->productImage; ?>">
                            <img id="product-zoom0" src="<?= base_url(); ?>uploads/webProductsGallery/<?= $image->productImage; ?>" /> 
                        </a>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <div class="col-md-6">
                <div class="headingsty mt-4 mt-md-0 mb-3">
                    <h1 class="mb-3"><?= $productinfo->name; ?></h1>
                </div>
                <h5>Descriptions</h5>
                <p>
                    <?= $productinfo->description; ?>
                </p>
            </div>
        </div>
    </div>
</section>

<?php include('common/enquiry.php'); ?>