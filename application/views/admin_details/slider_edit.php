<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h4 style="color: white;" class="text-center">
                                    Update Slider
                                </h4>
                                <!-- Card stats -->
                                <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                                    <?= $this->session->flashdata('msg'); ?>
                                </div>
                                <?php } ?>

                                <?= $error; ?>

                                <?= form_open_multipart('slider/updateSlider'); ?>
                                    <input type="hidden" name="id" value="<?= $result->id; ?>" />
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <lable for="Title">Title</lable>
                                                <input type="text" name="Title" value="<?= set_value('Title', $result->Title); ?>" id="Title" class="form-control form-control-sm" placeholder="Title" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <lable for="url">URL</lable>
                                                <input type="text" name="url" value="<?= set_value('url', $result->url); ?>" id="url" class="form-control form-control-sm" placeholder="URL" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-lg-3">
                                            <div class="form-group">
                                                <lable for="image">Image</lable>
                                                <input type="file" name="image" id="image" class="form-control form-control-sm" accept="image/*" title="Image" />
                                                <input type="hidden" name="oldimage" value="<?= $result->image; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-lg-3">
                                            <img src='<?= base_url() . "uploads/slider/" . $result->image; ?>' style="border: 0.1px solid #fff;" height="100px" width="200px" class="photo" />
                                        </div>
                                    </div>
                                    <div class="row mt-1 pb-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-warning btn-xs">
                                                Save Changes
                                            </button>
                                            <a href="<?= base_url(); ?>slider" class="btn btn-danger btn-xs">
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
</div>