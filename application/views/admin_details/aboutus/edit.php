<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body pl-2 pr-2">
                            <h4 style="color: white;" class="text-center">Update About Us</h4>
                            <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                                <?= $this->session->flashdata('msg'); ?>
                            </div>
                            <?php } ?>
                            <form method="POST" action="<?= base_url('aboutus/update'); ?>" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $aboutus->id; ?>" />
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <textarea name="content" id="content" class="form-control" rows="4" placeholder="Content" required=""><?= set_value('content', $aboutus->content); ?></textarea>
                                            <span class="text-danger"><?= form_error('content'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" name="image" id="image" class="form-control" title="Image" accept="image/*" />
                                            <input type="hidden" name="oldimage" value="<?= $aboutus->image; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <br />
                                            <img src="<?= base_url(); ?>uploads/about/<?= $aboutus->image; ?>" alt="aboutus" style="width: 100px; height: 50px;" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="content2">Content After Image</label>
                                            <textarea name="content2" id="content2" class="form-control" rows="4" placeholder="Content After Image" required=""><?= set_value('content2', $aboutus->content2); ?></textarea>
                                            <span class="text-danger"><?= form_error('content2'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1 pb-1">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-warning btn-xs">
                                            Save Changes
                                        </button>
                                        <a href="<?= base_url(); ?>aboutus/index" class="btn btn-danger btn-xs">
                                            Cancel
                                        </a>
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