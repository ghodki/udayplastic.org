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
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Service</h2>
                                <!-- Card stats -->
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php

                                }
                                ?>
                                <div class="row">

                                    <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>editDigitalCertificate/<?= $result->id ?>" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <input id="name" name="name" placeholder="Service Name" type="text" class="form-control" required="true" value="<?= set_value('name', $result->service_name) ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('name'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <input id="name" name="sub_heading" placeholder="Sub heading Name" type="text" class="form-control" required="true" value="<?= set_value('sub_heading', $result->sub_heading) ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('sub_heading'); ?>
                                                </span>
                                            </div>
                                            <input id="id" name="id"  type="hidden" class="form-control" required="true" value="<?= set_value('id', $result->id) ?>">

                                            <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                                <div class="form-group">
                                                    <textarea id="description" name="description" placeholder="Description" type="text" class="form-control"><?= $result->description ?></textarea>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('description'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    Save
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