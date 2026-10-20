<main id="main" class="main">

    <div class="pagetitle">
        <h1>SERVICES</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">SERVICES</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    ADD SERVICE
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
    <section class="section dashboard">

        <div class="card">
            <?php

            $this->db->select('*');
            $this->db->from('crm_service');
            $this->db->where('status', 1);
            $totalService = $this->db->get()->result();
            $totalServiceCount = count($totalService);


            $this->db->select('*');
            $this->db->from('crm_service');
            $this->db->where('status', 0);
            $serviceDisable = $this->db->get()->result();
            $serviceDisableCount = count($serviceDisable);
            $bothadd = $totalServiceCount + $serviceDisableCount;

            ?>
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn btn-success" onclick="PageShow('activeservices')">
                    <span>SERVICES ACTIVE:</span>
                    <?= $totalServiceCount ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-primary" onclick="PageShow('dectiveservices')">
                    <span>SERVICE DEACTIVE:</span>
                    <?= $serviceDisableCount ?>

                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-warning" onclick="PageShow('totalservices')">
                    <span>TOTAL SERVICES:</span>
                    <?= $bothadd ?>
                </button>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">SERVICES LIST</h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>

                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Service Name</th>
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

                                if (isset($crm_service) && $crm_service != '') {
                                    foreach ($crm_service as  $key => $value) {

                                        $date = strtotime('d-m-Y', $value->created_at);

                                ?>
                                        <tr scope="<?= $key ?>">
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td><?= $date; ?></td>
                                            <td>
                                                <?= $value->service_name ?>
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



    <!-- MODELS  -->
    <div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ADD SERVICE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/service/save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">

                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="service_name">SERVICE NAME:<span class="text-danger">*</span></label>
                                    <textarea id="service_name" name="service_name" placeholder="Service Name" required type="text" class="form-control" value="<?= set_value('service_name') ?>"></textarea>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('service_name'); ?>

                                </span>
                            </div>

                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="short_name">SHORT NAME:<span class="text-danger">*</span></label>
                                    <textarea id="short_name" name="short_name" placeholder="Short Name" required type="text" class="form-control" value="<?= set_value('short_name') ?>"></textarea>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('short_name'); ?>

                                </span>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">

                                    <label for="status">Status<span class="text-danger"></span></label>
                                    <select class="form-control" name="status">
                                        <option value="1" class="text-success">Approved</option>
                                        <option value="0" class="text-danger">Pending</option>
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
                    <h5 class="modal-title">UPDATE SERVICE</h5>
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
                    <h5 class="modal-title">VIEW SERVICE</h5>
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
                    <h5 class="modal-title">SERVICE</h5>
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
                    url: "<?= base_url() ?>crm/service/changeStatus",
                    type: 'post',
                    data: {
                        service_id: service_id,
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/service/edit_service/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/service/view_service/" + productId);
        $("#viewModel").modal("show");

    }

    function PageShow(pagename) {
        jQuery.noConflict();
        $("#gstr1Model .modal-dialog .modal-body").load("<?= base_url() ?>crm/service/PageShow/" + pagename);
        $("#gstr1Model").modal("show");

    }

    function closePopup() {
        $("#gstr1Model").modal("hide");
    }
</script>