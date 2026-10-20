<style type="text/css">
    .card {
        margin-bottom: 7px !important;
    }
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Insurance Policy</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Insurance Policy</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Insurance Policy
                </button>
                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url(); ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software
                    </a>
                </button>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
                <?php $this->session->unset_userdata('msg'); } ?>
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Insurance Policy List</h5>
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Applicant Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Expiry Date</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (isset($insurance_policyData) && $insurance_policyData != '') {
                                        $count = 0;
                                        foreach ($insurance_policyData as  $key => $value) {
                                            $count++;
                                ?>
                                <tr>
                                    <td><span><?= $count; ?></span></td>
                                    <td><?= $value->date; ?></td>
                                    <td><?= $value->applicant_name; ?></td>
                                    <td><?= $value->email; ?></td>
                                    <td><?= $value->expiry_date; ?></td>
                                    <td>
                                        <?php
                                            if ($value->status == 1) {
                                                echo '<button class="btn btn-success">Approved</button>';
                                            } else if ($value->status == 0) {
                                                echo '<button class="btn btn-danger">Pending</button>';
                                            } else {
                                                echo '<button class="btn btn-primary">' . get_title('crm_status', ['id' => $value->status], 'name') . '</button>';
                                            }
                                        ?>
                                    </td>
                                    <td><?= $value->remark; ?></td>
                                    <td class="text-right">
                                        <a href="<?= getCrmUrl(); ?>master/delete_insurance_policy/<?= $value->id; ?>">
                                            <button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete insurance_policy?");'>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </a>
                                        <button class="btn btn-primary" onclick="viewModel(<?= $value->id; ?>);">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-primary" onclick="companyEdit(<?= $value->id; ?>);">
                                            <i class="ri-edit-box-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php } } ?>
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
                    <h5 class="modal-title">Add Insurance Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url(); ?>crm/master/insurance_policy_save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="applicant_name">Applicant name<span class="text-danger">*</span></label>
                                    <input type="text" name="applicant_name" id="applicant_name" class="form-control" placeholder="Applicant Name" required="" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('applicant_name'); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="org_name">ORG Name<span class="text-danger"></span></label>
                                    <input type="text" name="org_name" id="org_name" class="form-control" placeholder="ORG Name" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('org_name'); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email<span class="text-danger"></span></label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('email'); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number<span class="text-danger"></span></label>
                                    <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="Mobile Number" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('mobile_number'); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="type">Type<span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control" required="">
                                        <option value="">Select Type</option>
                                        <?php
                                            $this->db->select('*');
                                            $this->db->from('crm_type');
                                            $this->db->where('status', 1);
                                            $getResu = $this->db->get()->result();
                                            if ($getResu) { foreach ($getResu as $key => $category) {
                                            ?>
                                            <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                            <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="ref_by">Ref By<span class="text-danger"></span></label>
                                    <input type="text" name="ref_by" id="ref_by" class="form-control" placeholder="Ref By" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('ref_by'); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="date">Date<span class="text-danger">*</span></label>
                                    <input type="date" name="date" value="<?= set_value('date', date('Y-m-d')); ?>" id="date" class="form-control" placeholder="Date" required="" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('date'); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <?php $new_date = date('Y-m-d', strtotime('+1 years', strtotime($last_month))); ?>
                                    <label for="expiry_date">Expiry Date<span class="text-danger">*</span></label>
                                    <input type="date" name="expiry_date" value="<?= set_value('date', $new_date); ?>" id="expiry_date" class="form-control" placeholder="Expiry Date" required="" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('expiry_date'); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="status">Status<span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required="">
                                        <option value="">Select Status</option>
                                        <?php
                                            $this->db->select('*');
                                            $this->db->from('crm_status');
                                            $this->db->where('status', 1);
                                            $getResu = $this->db->get()->result();
                                            if ($getResu) { foreach ($getResu as $category) {
                                        ?>
                                        <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="remark">Remark<span class="text-danger"></span></label>
                                    <textarea name="remark" id="remark" class="form-control" placeholder="Remark"><?= set_value('remark'); ?></textarea>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('remark'); ?>
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
                    <h5 class="modal-title">Update Insurance Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel();"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div class="modal" id="viewModel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Insurance Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel();"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div class="modal" id="insurance_policyModel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closePopup();"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
</main>

<script type="text/javascript" src="<?= base_url() ?>crm/assets/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#signUpForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url     :   contactForm.attr('action'),
                type    :   'POST',
                data    :   contactForm.serialize(),
                success :   function(response) {
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
        });
    });

    function companyEdit(productId) {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url(); ?>crm/master/edit_insurance_policy/" + productId);
        $("#exampleModals").modal("show");
    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url(); ?>crm/master/view_insurance_policy/" + productId);
        $("#viewModel").modal("show");
    }

    function SaleReport(pagename) {
        jQuery.noConflict();
        $("#insurance_policyModel .modal-dialog .modal-body").load("<?= base_url(); ?>crm/master/insurance_policyReport/" + pagename);
        $("#insurance_policyModel").modal("show");
    }

    function closePopup() {
        $("#insurance_policyModel").modal("hide");
    }
</script>