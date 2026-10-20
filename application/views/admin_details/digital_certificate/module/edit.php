<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style>
        .control-label {
            color: red;
        }

        label {
            color: aliceblue;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header  pb-8 pt-5 pt-md-8" style="background: #2C4668;">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px mb-3" class="text-center">Update Sub-Services for <?= $result->service_name ?></h2>
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
                                    <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>scan_edit/<?= $subservice->id ?>" method="POST" enctype="multipart/form-data">
                                        <!-- Service Heading and Sub Heading Start -->
                                        <!-- 1st Year validity  with Organisation User Type with Certificate Type Signing -->
                                        <div class="row">
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_validity">Certificate Validity</label>
                                                <select class="form-control" name="certificate_validity" id="certificate_validity">
                                                    <option value="1 Year" <?= set_value('certificate_validity', $subservice->validity) == "1 Year" ? 'selected' : ''; ?>>1 Year</option>
                                                    <option value="2 Year" <?= set_value('certificate_validity', $subservice->validity) == "2 Year" ? 'selected' : ''; ?>>2 Years</option>
                                                    <option value="3 Year" <?= set_value('certificate_validity', $subservice->validity) == "3 Year" ? 'selected' : ''; ?>>3 Years</option>

                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_validity'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="userType">User Type</label>
                                                <select class="form-control" name="userType" id="userType">
                                                    <option value="Organisation" <?= set_value('userType', $subservice->user_type) == "Organisation" ? 'selected' : ''; ?>>Organisation</option>
                                                    <option value="Individual" <?= set_value('userType', $subservice->user_type) == "Individual" ? 'selected' : ''; ?>>Individual</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('userType'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_type">Certificate Type</label>
                                                <select class="form-control" name="certificate_type" id="certificate_type">
                                                    <option value="Signing" <?= set_value('certificate_type', $subservice->certificate_type) == "Signing" ? 'selected' : ''; ?>>Signing</option>
                                                    <option value="Combo" <?= set_value('certificate_type', $subservice->certificate_type) == "Combo" ? 'selected' : ''; ?>>Combo</option>
                                                     <option value="DGFT Certificate" <?= set_value('certificate_type',$subservice->certificate_type) == "DGFT Certificate" ? 'selected' : ''; ?>>DGFT Certificate</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_type'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="usb_token">USB Token</label>
                                                <select class="form-control" name="usb_token" id="usb_token">
                                                    <option value="Not Required" <?= set_value('usb_token', $subservice->usb_token) == "Not Required" ? 'selected' : ''; ?>>Not Required</option>
                                                    <option value="ePass" <?= set_value('usb_token', $subservice->usb_token) == "ePass" ? 'selected' : ''; ?>>ePass</option>
                                                    <option value="ProxKey" <?= set_value('usb_token', $subservice->usb_token) == "ProxKey" ? 'selected' : ''; ?>>ProxKey</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('usb_token'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <label for="price">Service Charged</label>
                                                <input id="price" name="price" min="1" placeholder="Service Charge" type="number" class="form-control" required="true" value="<?= set_value('price', $subservice->amount) ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('price'); ?>
                                                </span>
                                            </div>
                                            <input id="digital_certificate_id" type="hidden" name="digital_certificate_id" value="<?= $result->id ?>">
                                            <input id="service_ids" type="hidden" name="service_id" value="<?= $subservice->id ?>">

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