<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Update Payment Category</h5>
                        <div class="align-items-center">
                            <div class="ps-3">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                    <strong><?php echo $this->session->flashdata('msg'); ?></strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php } ?>
                                
                                <form action="<?= base_url() ?>paymentReceipt/editcategory/<?= $result->id; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="category">Category Name <span class="text-danger">*</span></label>
                                                <input type="text" name="category" value="<?= set_value('category', $result->category); ?>" id="category" class="form-control" placeholder="Category Name" required="true" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('category'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="prefix">Prefix</label>
                                            <input type="text" name="prefix" value="<?= set_value('prefix', $result->prefix); ?>" id="prefix" placeholder="Prefix" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="receipt_number">Receipt Number <span class="text-danger">*</span></label>
                                            <input type="text" name="receipt_number" value="<?= set_value('receipt_number', $result->receipt_number); ?>" id="receipt_number" placeholder="Receipt Number 0001, 0002" class="form-control" />
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-lg-8">
                                            <div class="form-group">
                                                <label for="description">Description <span class="text-danger">*</span></label>
                                                <textarea name="description" id="description" class="form-control" placeholder="Description"><?= set_value('description', $result->description); ?></textarea>
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('description'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                            <a href="<?= base_url(); ?>paymentReceipt/category" class="btn btn-danger">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

        </div><!-- End Left side columns -->
        <!-- </div> -->
    </section>
</main><!-- End #main -->