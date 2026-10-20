<style type="text/css">
    .alert {
        margin-top: 50px;
    }
    input[type="file"] {
        line-height: 1;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card mt-5">
                <div class="card-header">
                    <h3 class="card-title">
                        Download Information
                    </h3>
                    <a href="<?= base_url(); ?>webDownloads" class="btn btn-info btn-xs pull-right" title="Download Report">
                        <i class="fa fa-file-alt"></i> Report
                    </a>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <?php if (empty($id)) { ?>
                        <form action="<?= base_url(); ?>webDownloads/create" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control form-control-sm" placeholder="Name" required="" />
                                    <span class="text-danger"><?= form_error('name'); ?></span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="image">Image <span class="text-danger">*</span></label>
                                    <input type="file" name="image" id="image" class="form-control form-control-sm" title="Image" accept="image/*" required="" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="file">File <span class="text-danger">*</span></label>
                                    <input type="file" name="file" id="file" class="form-control form-control-sm" title="File" required="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-refresh"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php } else { ?>
                        <form action="<?= base_url(); ?>webDownloads/update/<?= $downloaddata->id; ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="id" value="<?= $productdata->id; ?>" />
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="<?= set_value('name', $downloaddata->name); ?>" id="name" class="form-control form-control-sm" placeholder="Name" required="" />
                                    <span class="text-danger"><?= form_error('name'); ?></span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image" class="form-control form-control-sm" title="Image" accept="image/*" />
                                    <input type="hidden" name="oldimage" value="<?= $downloaddata->image; ?>" />
                                </div>
                                <div class="col-md-3 form-group mt-4">
                                    <img src="<?= base_url(); ?>uploads/webdownloads/<?= $downloaddata->image; ?>" alt="image" style="width: 100px; height: 100px;" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="file">File</label>
                                    <input type="file" name="file" id="file" class="form-control form-control-sm" title="File" />
                                    <input type="hidden" name="oldfile" value="<?= $downloaddata->file; ?>" />
                                </div>
                                <div class="col-md-3 form-group mt-4">
                                    <a href="<?= base_url(); ?>uploads/webdownloads/<?= $downloaddata->file; ?>" class="btn btn-info btn-block" title="View in large" target="_blank">
                                        File <i class="fa fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                    <a href="<?= base_url(); ?>webDownloads" class="btn btn-danger btn-sm">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>                            
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>