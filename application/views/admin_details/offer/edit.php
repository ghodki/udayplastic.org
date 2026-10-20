<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-blue pb-2 pt-2 pt-md-2">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Offer Update</h2>
                                <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                                <?php } ?>
                                <form action="<?= base_url(); ?>offer/update" id="signup" enctype="multipart/form-data" method="POST">
                                    <input type="hidden" value="<?= $result->offerId; ?>" name="id" />
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="heading" id="heading" placeholder="Heading" value="<?= set_value('heading', $result->heading); ?>" required="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?= set_value('title', $result->title) ?>" required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="url" class="form-control" name="url" id="url" placeholder="URL" value="<?= $result->url ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="file" class="form-control" accept="image/*" name="image" id="image" size="20" />
                                                <span><?= $result->image ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12 col-lg-8">
                                            <div class="form-group">
                                                <textarea id="YourEditor" name="description"><?= set_value('description') ?><?= $result->description ?></textarea>
                                            </div>
                                            <script>
                                                CKEDITOR.replace('YourEditor');
                                            </script>
                                        </div>
                                    </div>
                                    <div class="row mt-2 pb-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-default">
                                                Update
                                            </button>
                                            <input type="reset" class="btn btn-secondary" placeholder="Reset">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>