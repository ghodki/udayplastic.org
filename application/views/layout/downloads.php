<div class="topheadingsection">
    <div class="container text-center">
        <h1>Downloads</h1>
        <p class="px-md-5 font-22">
            UDAY PLASTIC CATELOGUE
        </p>
    </div>
</div>

<section class="inner-container">
    <div class="container">
        <div class="headingsty">
            <h1>Downloads</h1>
        </div>

        <div class="row">
            <?php if (!empty($downloads)) { foreach ($downloads as $key => $data) { ?>
            <div class="col-lg-6">
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="downloadimg">
                                <a href="<?= base_url(); ?>uploads/webdownloads/<?= $data->file; ?>" target="_blank" class="d-block">
                                    <img src="<?= base_url(); ?>uploads/webdownloads/<?= $data->image; ?>" class="rounded" />
                                </a>
                            </div>
                            <div class="donwloadtx">
                                <h5 class="mb-3"><?= $data->name; ?></h5>
                                <a href="<?= base_url(); ?>uploads/webdownloads/<?= $data->file; ?>" target="_blank" class="btn btn-outline-primary px-3" download>
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</section>

<?php include('common/enquiry.php'); ?>