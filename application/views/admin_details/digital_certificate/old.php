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
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Service</h2>
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
                                    <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>digital_certificate" method="POST" enctype="multipart/form-data">
                                        <!-- Service Heading and Sub Heading Start -->
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <label for="name">Service Name</label>
                                                <input id="name" name="name" placeholder="Service Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('name'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <label for="sub_heading">Sub Heading</label>
                                                <input id="sub_heading" name="sub_heading" placeholder="Sub heading Name" type="text" class="form-control" required="true" value="<?= set_value('sub_heading') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('sub_heading'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Service Heading and Sub Heading END -->


                                        <!-- 1st Year validity  with Organisation User Type with Certificate Type Signing -->
                                        <div class="row">
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_validity1">Certificate Validity</label>
                                                <select class="form-control" name="certificate_validity1" id="certificate_validity1">
                                                    <option value="1 Year">1 Year</option>
                                                    <!-- <option value="2 Year">2 Years</option>
                                                    <option value="3 Year">3 Years</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_validity1'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="userType1">User Type</label>
                                                <select class="form-control" name="userType1" id="userType1">
                                                    <option value="Organisation">Organisation</option>
                                                    <!-- <option value="Individual">Individual</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('userType1'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_type1">Certificate Type</label>
                                                <select class="form-control" name="certificate_type1" id="certificate_type1">
                                                    <option value="Signing">Signing</option>
                                                    <!-- <option value="Combo">Combo</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_type1'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="usb_token1">USB Token</label>
                                                <select class="form-control" name="usb_token1" id="usb_token1">
                                                    <option value="Not Required">Not Required</option>
                                                    <option value="ePass">ePass</option>
                                                    <option value="ProxKey">ProxKey</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('usb_token1'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <label for="price1">Service Charged</label>
                                                <input id="price1" name="price" min="1" placeholder="Service Charge" type="number" class="form-control" required="true" value="<?= set_value('price1') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('price1'); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2nd Year validity  with Organisation User Type with Certificate Type Signing -->

                                        <div class="row">
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_validity2">Certificate Validity</label>
                                                <select class="form-control" name="certificate_validity2" id="certificate_validity2">
                                                    <option value="2 Year">2 Years</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_validity2'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="userType2">User Type</label>
                                                <select class="form-control" name="userType2" id="userType2">
                                                    <option value="Organisation">Organisation</option>
                                                    <!-- <option value="Individual">Individual</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('userType2'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_type2">Certificate Type</label>
                                                <select class="form-control" name="certificate_type2" id="certificate_type2">
                                                    <option value="Signing">Signing</option>
                                                    <!-- <option value="Combo">Combo</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_type2'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="usb_token2">USB Token</label>
                                                <select class="form-control" name="usb_token2" id="usb_token2">
                                                    <option value="Not Required">Not Required</option>
                                                    <option value="ePass">ePass</option>
                                                    <option value="ProxKey">ProxKey</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('usb_token2'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <label for="price2">Service Charged</label>
                                                <input id="price2" name="price2" min="1" placeholder="Service Charge" type="number" class="form-control" required="true" value="<?= set_value('price2') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('price2'); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 3rd Year validity  with Organisation User Type with Certificate Type Signing -->

                                        <div class="row">

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_validity3">Certificate Validity</label>
                                                <select class="form-control" name="certificate_validity3" id="certificate_validity3">
                                                    <!-- <option value="1 Year">1 Year</option>
                                                    <option value="2 Year">2 Years</option> -->
                                                    <option value="3 Year">3 Years</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_validity3'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="userType3">User Type</label>
                                                <select class="form-control" name="userType3" id="userType3">
                                                    <option value="Organisation">Organisation</option>
                                                    <!-- <option value="Individual">Individual</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('userType3'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_type3">Certificate Type</label>
                                                <select class="form-control" name="certificate_type3" id="certificate_type3">
                                                    <option value="Signing">Signing</option>
                                                    <!-- <option value="Combo">Combo</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_type3'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="usb_token3">USB Token</label>
                                                <select class="form-control" name="usb_token3" id="usb_token3">
                                                    <option value="Not Required">Not Required</option>
                                                    <option value="ePass">ePass</option>
                                                    <option value="ProxKey">ProxKey</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('usb_token3'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <label for="price3">Service Charged</label>
                                                <input id="price3" name="price3" min="1" placeholder="Service Charge" type="number" class="form-control" required="true" value="<?= set_value('price3') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('price3'); ?>
                                                </span>
                                            </div>



                                        </div>


                                        <!-- 1st Year validity  with Individual User Type with Certificate Type Signing -->
                                        <div class="row">
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_validity4">Certificate Validity</label>
                                                <select class="form-control" name="certificate_validity4" id="certificate_validity4">
                                                    <option value="1 Year">1 Year</option>
                                                    <!-- <option value="2 Year">2 Years</option>
                                                    <option value="3 Year">3 Years</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_validity4'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="userType4">User Type</label>
                                                <select class="form-control" name="userType4" id="userType4">
                                                    <!-- <option value="Organisation">Organisation</option> -->
                                                    <option value="Individual">Individual</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('userType4'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_type4">Certificate Type</label>
                                                <select class="form-control" name="certificate_type4" id="certificate_type4">
                                                    <option value="Signing">Signing</option>
                                                    <!-- <option value="Combo">Combo</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_type4'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="usb_token1">USB Token</label>
                                                <select class="form-control" name="usb_token1" id="usb_token1">
                                                    <option value="Not Required">Not Required</option>
                                                    <option value="ePass">ePass</option>
                                                    <option value="ProxKey">ProxKey</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('usb_token1'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <label for="price1">Service Charged</label>
                                                <input id="price1" name="price" min="1" placeholder="Service Charge" type="number" class="form-control" required="true" value="<?= set_value('price1') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('price1'); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2nd Year validity  with Individual User Type with Certificate Type Signing -->

                                        <div class="row">
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_validity2">Certificate Validity</label>
                                                <select class="form-control" name="certificate_validity2" id="certificate_validity2">
                                                    <option value="2 Year">2 Years</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_validity2'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="userType2">User Type</label>
                                                <select class="form-control" name="userType2" id="userType2">
                                                    <!-- <option value="Organisation">Organisation</option> -->
                                                    <option value="Individual">Individual</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('userType2'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_type2">Certificate Type</label>
                                                <select class="form-control" name="certificate_type2" id="certificate_type2">
                                                    <option value="Signing">Signing</option>
                                                    <!-- <option value="Combo">Combo</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_type2'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="usb_token2">USB Token</label>
                                                <select class="form-control" name="usb_token2" id="usb_token2">
                                                    <option value="Not Required">Not Required</option>
                                                    <option value="ePass">ePass</option>
                                                    <option value="ProxKey">ProxKey</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('usb_token2'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <label for="price2">Service Charged</label>
                                                <input id="price2" name="price2" min="1" placeholder="Service Charge" type="number" class="form-control" required="true" value="<?= set_value('price2') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('price2'); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!--Start 3rd Year validity  with Individual User Type with Certificate Type Signing -->

                                        <div class="row">

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_validity3">Certificate Validity</label>
                                                <select class="form-control" name="certificate_validity3" id="certificate_validity3">
                                                    <!-- <option value="1 Year">1 Year</option>
                                                    <option value="2 Year">2 Years</option> -->
                                                    <option value="3 Year">3 Years</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_validity3'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="userType3">User Type</label>
                                                <select class="form-control" name="userType3" id="userType3">
                                                    <!-- <option value="Organisation">Organisation</option> -->
                                                    <option value="Individual">Individual</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('userType3'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="certificate_type3">Certificate Type</label>
                                                <select class="form-control" name="certificate_type3" id="certificate_type3">
                                                    <option value="Signing">Signing</option>
                                                    <!-- <option value="Combo">Combo</option> -->
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('certificate_type3'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-lg-2">
                                                <label for="usb_token3">USB Token</label>
                                                <select class="form-control" name="usb_token3" id="usb_token3">
                                                    <option value="Not Required">Not Required</option>
                                                    <option value="ePass">ePass</option>
                                                    <option value="ProxKey">ProxKey</option>
                                                </select>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('usb_token3'); ?>
                                                </span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <label for="price3">Service Charged</label>
                                                <input id="price3" name="price3" min="1" placeholder="Service Charge" type="number" class="form-control" required="true" value="<?= set_value('price3') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('price3'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <!--End 3rd Year validity  with Individual User Type with Certificate Type Signing -->

                                        <!-- Description Start -->
                                        <div class="row">
                                            <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                                <textarea id="description" name="description" placeholder="Description" type="text" class="form-control"><?= set_value('description') ?></textarea>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('description'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Description End -->

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
                        <div class="container-fluid mb-9" style="margin-top:100px;">

                            <div class="col">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Service List</label>
                                        <div class="row mt-5">
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Service Name</th>
                                                        <th>Sub Heading</th>
                                                        <th>Charge</th>
                                                        <th>Description</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark">
                                                    <?php
                                                    if (isset($service) && $service != '') {
                                                        foreach ($service as  $key => $value) {
                                                            $date = $value->created_at;
                                                            $newDate = date("d-m-Y", strtotime($date));
                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <span><?= $key + 1; ?></span>
                                                                </td>
                                                                <td><?= $newDate; ?></td>
                                                                <td><?= $value->service_name ?></td>
                                                                <td><?= $value->sub_heading ?></td>
                                                                <td><?= $value->service_charge ?></td>
                                                                <td><?= $value->description ?></td>
                                                                <td class="text-right">
                                                                    <div class="dropdown">
                                                                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                            <a href="<?= base_url('edit_digital_certificate/') . $value->id ?>" class="dropdown-item cp">Edit</a>
                                                                            <!-- <a href="<?= base_url('delete_digital_certificate/') . $value->id ?>" class="dropdown-item cp">Delete</a> -->
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                    <?php }
                                                    }

                                                    ?>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>