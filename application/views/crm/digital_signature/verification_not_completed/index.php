<main id="main" class="main">

    <div class="pagetitle">
        <h1>Verification Not Complete In 90 Days</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Verification Not Complete In 90 Days</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <!-- <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Verification Not Complete In 90 Days
                </button> -->

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
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Verification Not Complete In 90 Days List</h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Applicant Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Expiry Date</th>
                                    <th scope="col">Remark</th>
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

                                if (isset($insurance_policyData) && $insurance_policyData != '') {
                                    foreach ($insurance_policyData as  $key => $value) {

                                        $date = $value->date;

                                ?>
                                        <tr scope="<?= $key ?>">
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td><?= $date; ?></td>
                                            <td>
                                                <?= $value->applicant_name ?>
                                            </td>
                                            <td>
                                                <?= $value->email ?>
                                            </td>
                                            <td>
                                                <?= $value->expiry_date ?>
                                            </td>
                                            <td>


                                                <?= $value->remark ?>
                                            </td>

                                            <td class="text-right">

                                                <a href="<?= getCrmUrl() ?>master/delete_insurance_policy/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete insurance_policy?")'><i class="bi bi-trash"></i></button></a>

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


    <div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Verification Not Complete In 90 Days</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/insurance_policy_save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="applicant_name">Applicant name<span class="text-danger">*</span></label>
                                    <input id="applicant_name" name="applicant_name" placeholder="Applicant Name" type="text" required class="form-control">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('applicant_name'); ?>

                                </span>
                            </div>


                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="org_name">ORG Name<span class="text-danger"></span></label>
                                    <input id="org_name" name="org_name" placeholder="ORG Name" type="text" class="form-control">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('org_name'); ?>

                                </span>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email<span class="text-danger"></span></label>
                                    <input id="email" name="email" required placeholder="Email" type="email" class="form-control">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('email'); ?>

                                </span>
                            </div>



                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number<span class="text-danger"></span></label>
                                    <input id="mobile_number" name="mobile_number" placeholder="Mobile Number" type="text" class="form-control">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('mobile_number'); ?>

                                </span>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="type">Type<span class="text-danger">*</span></label>
                                    <select class="form-control select2" required name="type" id="type">
                                        <option value="">Select Type</option>

                                        <?php
                                        $this->db->select('*');
                                        $this->db->from('crm_type');
                                        $this->db->where('status', 1);
                                        $getResu = $this->db->get()->result();
                                        if ($getResu) {
                                            foreach ($getResu as $category) {
                                        ?>
                                                <option value="<?= $category->id ?>"><?= $category->name ?></option>

                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>

                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="ref_by">Ref By<span class="text-danger"></span></label>
                                    <input id="ref_by" name="ref_by" placeholder="Ref By" type="text" class="form-control">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('ref_by'); ?>
                                </span>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <?php
                                    $last_month = date('Y-m-d');
                                    ?>
                                    <label for="date">Date<span class="text-danger">*</span></label>
                                    <input id="date" name="date" placeholder="Date" type="date" required class="form-control" value="<?= set_value('date', $last_month) ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('date'); ?>

                                </span>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <?php
                                    // $last_month = date('Y-m-d');
                                    $new_date = date('Y-m-d', strtotime('+2 years', strtotime($last_month)));
                                    ?>
                                    <label for="expiry_date">Expiry Date<span class="text-danger">*</span></label>
                                    <input id="expiry_date" name="expiry_date" placeholder="Expiry Date" type="date" required class="form-control" value="<?= set_value('date', $new_date) ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('expiry_date'); ?>

                                </span>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="status">Status<span class="text-danger">*</span></label>
                                    <select class="form-control select2" required name="status" id="status">
                                        <option value="">Select Status</option>

                                        <?php
                                        $this->db->select('*');
                                        $this->db->from('crm_status');
                                        $this->db->where('status', 1);
                                        $getResu = $this->db->get()->result();
                                        if ($getResu) {
                                            foreach ($getResu as $category) {
                                        ?>
                                                <option value="<?= $category->id ?>"><?= $category->name ?></option>

                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>

                            </div>

                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="remark">Remark<span class="text-danger"></span></label>
                                    <textarea id="remark" name="remark" placeholder="Remark" type="text" class="form-control" value="<?= set_value('remark') ?>"></textarea>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('remark'); ?>

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
                    <h5 class="modal-title">Update Verification Not Complete In 90 Days</h5>
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
                    <h5 class="modal-title">View Verification Not Complete In 90 Days</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


    <div class="modal" id="insurance_policyModel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title">GSTR1</h5> -->
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


    function companyEdit(productId) {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_insurance_policy/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_insurance_policy/" + productId);
        $("#viewModel").modal("show");

    }


    //   $("#exampleModals").modal("show");
    $(document).ready(function() {
        // $('.select2').select2();


    });

    function SaleReport(pagename) {
        jQuery.noConflict();
        $("#insurance_policyModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/insurance_policyReport/" + pagename);
        $("#insurance_policyModel").modal("show");

    }

    function closePopup() {
        $("#insurance_policyModel").modal("hide");
    }

    // $(function() {
    //     $(".datatable").DataTable({
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'copyHtml5',
    //             'excelHtml5',
    //             'csvHtml5',
    //             'pdfHtml5',
    //         ]

    //     });
    // });
</script>