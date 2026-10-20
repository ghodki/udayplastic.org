<main id="main" class="main">

    <div class="pagetitle">
        <h1>SUB-SERVICES</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">SUB-SERVICES</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    ADD SUB-SERVICE
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
                        <h5 class="card-title">SUB-SERVICES LIST</h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>

                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Service</th>
                                    <th scope="col">SUB-Service Name</th>
                                    <th scope="col">CHARGE</th>
                                    <th scope="col">STATUS</th>
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

                                if (isset($crm_servicesubgroup) && $crm_servicesubgroup != '') {
                                    foreach ($crm_servicesubgroup as  $key => $value) {

                                        $date = strtotime('d-m-Y', $value->created_at);

                                ?>
                                        <tr scope="<?= $key ?>">
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td><?= get_title('crm_service', ['id' => $value->service_id], 'service_name') ?></td>
                                            <td>
                                            <?= $value->sub_service_name ?>
                                            
                                            </td>
                                            <td>
                                                <?= $value->service_charge ?>
                                            </td>
                                            <?php
                                            if ($value->status == 1) {
                                            ?>
                                                <td class="text-blue"><button class="btn btn-success" onclick="changeStatus(<?= $value->id ?>,0)">Active</button></td>
                                            <?php
                                            } else {
                                            ?>
                                                <td class="text-blue"><button class="btn btn-danger" onclick="changeStatus(<?= $value->id ?>,1)">Deactivated</button></td>


                                            <?php

                                            }
                                            ?>

                                            <td class="text-right">

                                                <!-- <a href="<?= getCrmUrl() ?>service/delete_gstr1/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete Gstr1?")'><i class="bi bi-trash"></i></button></a> -->

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


    <style>
        .mySelect2 {
        z-index: 9999 !important;
        }
    </style>
    <!-- MODELS  -->
    <div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ADD SUB-SERVICE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/ServiceSubGroup/save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="service">Service<span class="text-danger">*</span></label>
                                    <select class="form-control mySelect2" required name="service" id="service">
                                        <option value="">Select Service</option>
                                        <?php
                                        $this->db->select('*');
                                        $this->db->from('crm_service');
                                        $this->db->where('status', 1);
                                        $crm_service = $this->db->get()->result();

                                        if ($crm_service) {
                                            foreach ($crm_service as $category) {
                                        ?>
                                                <option value="<?= $category->id ?>"><?= $category->service_name ?></option>

                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>

                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="remark">SUB-SERVICE NAME:<span class="text-danger">*</span></label>
                                    <input type="text" id="sub_service_name" name="sub_service_name" placeholder="Sub-Service Name" required class="form-control" value="<?= set_value('sub_service_name') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('sub_service_name'); ?>

                                </span>
                            </div>

                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="sub_name">SUB-NAME:<span class="text-danger">*</span></label>
                                    <input type="text" id="sub_name" name="sub_name" placeholder="Sub Name" required class="form-control" value="<?= set_value('sub_name') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('sub_name'); ?>

                                </span>
                            </div>

                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="charge">CHARGE AMOUNT:<span class="text-danger">*</span></label>
                                    <input type="number" min="0" id="charge" name="charge" placeholder="Charge Amount" required class="form-control" value="<?= set_value('charge') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('charge'); ?>

                                </span>
                            </div>



                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status<span class="text-danger"></span></label>
                                    <select class="form-control" name="status">
                                        <option value="1" class="text-success">Active</option>
                                        <option value="0" class="text-danger">Deactivate</option>
                                    </select>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('status'); ?>
                                </span>
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
                    <h5 class="modal-title">UPDATE SUB-SERVICE</h5>
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
                    <h5 class="modal-title">VIEW SUB-SERVICE</h5>
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
                    <h5 class="modal-title">SUB-SERVICE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closePopup()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


</main>
<script src="<?= base_url() ?>crm/assets/js/jquery-1.11.3.min.js"></script>

<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->


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
                    if (response.status == 'success') {
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

    function changeStatus(service_id, status) {
        let returnValue = confirm("Are You Sure Want to change Status?");
        if (returnValue) {
            if (!isNaN(service_id) && !isNaN(status)) {
                $.ajax({
                    url: "<?= base_url() ?>crm/serviceSubGroup/changeStatus",
                    type: 'post',
                    data: {
                        serviceSubGroup_id: service_id,
                        status: status
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/serviceSubGroup/edit_serviceSubGroup/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/serviceSubGroup/view_serviceSubGroup/" + productId);
        $("#viewModel").modal("show");

    }

    function PageShow(pagename) {
        jQuery.noConflict();
        $("#gstr1Model .modal-dialog .modal-body").load("<?= base_url() ?>crm/serviceSubGroup/PageShow/" + pagename);
        $("#gstr1Model").modal("show");

    }

    function closePopup() {
        $("#gstr1Model").modal("hide");
    }
</script>