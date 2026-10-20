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

                                        <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea id="description" name="description" placeholder="Description" type="text" class="form-control"><?= set_value('description') ?></textarea>
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('description'); ?>
                                            </span>
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
                                                        <th>Description</th>
                                                        <th>Add Services</th>
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
                                                                <td><button class="btn btn-primary"><a style="color: aliceblue;" href="<?= base_url() ?>digital_certificate/scan/<?= $value->id ?>"><?= $value->service_name ?></a></button></td>
                                                                <td><?= $value->sub_heading ?></td>
                                                                <td><?= $value->description ?></td>
                                                                <td><button class="btn btn-primary"><a style="color: aliceblue;" href="<?= base_url() ?>digital_certificate/scan/<?= $value->id ?>">Sub services</a></button></td>
                                                                <td class="text-right">
                                                                    <div class="dropdown">
                                                                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                            <a href="<?= base_url('editDigitalCertificate/') . $value->id ?>" class="dropdown-item cp">Edit</a>
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