<main id="main" class="main">
    <div class="pagetitle">
        <h1>TYPE</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">TYPE</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    ADD TYPE
                </button>
                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software</a>
                </button>
            </div>
        </div>
    </section>
    <style>
        .card {
            margin-bottom: 7px !important;
        }
    </style>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">TYPE LIST</h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>

                                    <th scope="col" width="2%">#</th>
                                    <!-- <th scope="col">Date</th> -->
                                    <th scope="col">TYPE Name</th>
                                    <th scope="col">TYPE</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                <?php
                                    $this->session->unset_userdata('msg');
                                } ?>
                                <?php

                                if (isset($crm_type) && $crm_type != '') {
                                    foreach ($crm_type as  $key => $value) {

                                        $date = strtotime('d-m-Y', $value->created_at);

                                ?>
                                        <tr scope="<?= $key ?>">
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td>
                                                <?= $value->name ?>
                                            </td>
                                            <?php
                                            if ($value->status == 1) {
                                            ?>
                                                <td class="text-blue"><button class="btn btn-success" onclick="changetype(<?= $value->id ?>,0)">Active</button></td>
                                            <?php
                                            } else {
                                            ?>
                                                <td class="text-blue"><button class="btn btn-danger" onclick="changetype(<?= $value->id ?>,1)">Deactivated</button></td>


                                            <?php

                                            }
                                            ?>

                                            <td class="text-right">

                                                <!-- <a href="<?= getCrmUrl() ?>type/delete_gstr1/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete Gstr1?")'><i class="bi bi-trash"></i></button></a> -->

                                                <button class="btn btn-primary" onclick="viewModel(<?= $value->id ?>)"><i class="bi bi-eye"></i></button>

                                                <button class="btn btn-primary" onclick="companyEdit(<?= $value->id ?>)"><i class="ri-edit-box-line"></i></button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>



            </div>
        </div>
    </section>



    <!-- MODELS  -->
    <div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ADD TYPE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/type/save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                <div class="form-group">
                                    <label for="type_name">TYPE NAME:<span class="text-danger">*</span></label>
                                    <textarea id="type_name" name="type_name" placeholder="TYPE Name" required type="text" class="form-control" value="<?= set_value('type_name') ?>"></textarea>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('type_name'); ?>

                                </span>
                            </div>

                            <div class="col-md-4 col-sm-4 col-lg-4">
                                <div class="form-group">
                                    <label for="short_name">SHORT NAME:<span class="text-danger">*</span></label>
                                    <textarea id="short_name" name="short_name" placeholder="Short Name" required type="text" class="form-control" value="<?= set_value('short_name') ?>"></textarea>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('short_name'); ?>

                                </span>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">

                                    <label for="status">Status<span class="text-danger">*</span></label>
                                    <select class="form-control" name="status">
                                        <option value="1" class="text-success">Approved</option>
                                        <option value="0" class="text-danger">Pending</option>
                                    </select>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('type'); ?>
                                </span>
                            </div>

                            <div class="row">

                                <div class="col-md-12 row">
                                    <?php
                                    $getResu = $this->user_model->getResult("crm_service");
                                    if ($getResu) {
                                        foreach ($getResu as $category) {
                                    ?>
                                            <div class="col-md-2 col-sm-2 col-lg-2">
                                                <div class="form-group">
                                                    <label for="incometax_file_no"><?php echo $category->service_name  ?><span class="text-danger"></span></label>
                                                    <input name="service[]" type="checkbox" value="<?= $category->id ?>" id="checkService<?= $category->id ?>" onchange="checkService(<?= $category->id ?>)" class="form-check-input form-control">
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                                $getResu = $this->user_model->getResult("crm_service");
                                if ($getResu) {
                                    foreach ($getResu as $category) {
                                ?>
                                        <div class="col-md-12 row" id="append_data<?= $category->id ?>"> </div>
                                <?php
                                    }
                                }
                                ?>


                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div><!-- End Large Modal-->

    <div class="modal" id="exampleModals" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">UPDATE TYPE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


    <div class="modal" id="viewModel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">VIEW TYPE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>

    <div class="modal" id="gstr1Model" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">TYPE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closePopup()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>

</main>

<script src="<?= base_url() ?>crm/assets/js/jquery-1.11.3.min.js"></script>

<script>
    $(document).ready(function() {
        $("#signUpForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: contactForm.attr('action'),
                type: 'post',
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.type == 'success') {
                        toastr.success(response.message);
                        $("#largeModal").modal("hide");
                        window.location.reload();

                    } else {
                        toastr.error(response.message);
                    }


                }
            });
        })
    })


    function checkService(service_id) {
        if (!isNaN(service_id)) {
            let checked = document.getElementById("checkService" + service_id);
            $("#append_data" + service_id).html("");
            if (checked.checked == true) {
                $.ajax({
                    url: "<?= base_url() ?>crm/service/subservices",
                    type: 'post',
                    data: {
                        service_id: service_id,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            $("#append_data" + service_id).append(response.data);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        } else {
            alert("please provide valid info");
        }

    }

    function changetype(type_id, type) {
        let returnValue = confirm("Are You Sure Want to change type?");
        if (returnValue) {
            if (!isNaN(type_id) && !isNaN(type)) {
                $.ajax({
                    url: "<?= base_url() ?>crm/type/changetype",
                    type: 'post',
                    data: {
                        type_id: type_id,
                        type: type
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.type == 'success') {
                            toastr.success(response.message);
                            window.location.reload();
                        } else {
                            toastr.error(response.message);

                        }
                    }
                });
            } else {
                alert("please provide valid info");
            }
        }

    }



    function companyEdit(productId) {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/type/edit_type/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/type/view_type/" + productId);
        $("#viewModel").modal("show");

    }

    function PageShow(pagename) {
        jQuery.noConflict();
        $("#gstr1Model .modal-dialog .modal-body").load("<?= base_url() ?>crm/type/PageShow/" + pagename);
        $("#gstr1Model").modal("show");

    }

    function closePopup() {
        $("#gstr1Model").modal("hide");
    }
</script>