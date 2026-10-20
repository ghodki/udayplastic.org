<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style>
        .control-label {
            color: red;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header  pb-8 pt-5 pt-md-8" style="background: #2C4668;">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update blog</h2>
                                <!-- Card stats -->
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php
                                    unset($_SESSION['msg']);
                                }

                                // die;
                                ?>
                                <div class="row">

                                    <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>edit_blog/<?= $result->id ?>" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <input id="name" name="name" placeholder="blog Name" type="text" class="form-control" required="true" value="<?= set_value('name', $result->blog_name) ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('name'); ?>
                                                </span>
                                            </div>
                                            <input type="hidden" name="blog_id" value="<?= $result->id ?>">
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <input id="image" name="image" type="file" class="form-control">
                                                <img src="<?= base_url() ?>uploads/blog/<?= $result->image ?>" height="100px;">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo $error ?>
                                                </span>
                                            </div>

                                            <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                                <textarea id="description" name="description" placeholder="Description" type="text" class="form-control"><?= set_value('description', $result->description) ?></textarea>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('description'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    Update
                                                </button>
                                                <a href="#" type="reset" class="btn btn-secondary">Cancel</a>
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
</div>