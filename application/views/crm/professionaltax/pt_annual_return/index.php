<style type="text/javascript">
    .card {
        margin-bottom: 7px !important;
    }
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>PT ANNUAL RETURN</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">PT ANNUAL RETURN</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    ADD PT ANNUAL RETURN
                </button>
                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url(); ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software
                    </a>
                </button>
            </div>
        </div>
    </section>

    <section class="section dashboard">
        <div class="card">
            <?php
                $totalreturn_filed = $this->user_model->getMonthWiseForSubServices('crm_pt_annual_return');
                $totalWithDone = count($totalreturn_filed);
                $totalWithoutDone = $this->user_model->getPartyFromSubServiceCount();
            ?>
            <div class="card-body" style="padding-bottom:10px;">
                <!--Large Modal--> 
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn btn-success" onclick="pt_annual_returnShow('done');">
                    <span>PT ANNUAL RETURN DONE:</span>
                    <?= $totalWithDone; ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-primary" onclick="pt_annual_returnShow('notdone');">
                    <span>PT ANNUAL RETURN NOT DONE:</span>
                    <?= $totalWithoutDone - $totalWithDone; ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-warning" onclick="pt_annual_returnShow('total');">
                    <span>TOTAL PT ANNUAL RETURN:</span>
                    <?= $totalWithoutDone; ?>
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
                    <div class="card-body">
                        <h5 class="card-title">PT ANNUAL RETURN List</h5>
                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Party Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (isset($pt_annual_returnData) && $pt_annual_returnData != '') {
                                        foreach ($pt_annual_returnData as  $key => $value) {
                                ?>
                                <tr scope="<?= $key ?>">
                                    <td>
                                        <span><?= $key + 1; ?></span>
                                    </td>
                                    <td><?= $value->date; ?></td>
                                    <td>
                                        <?= get_title('crm_party', ['id' => $value->party_id], 'party_name') ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($value->status == 1) {
                                            echo '<button class="btn btn-success">Approved</button>';
                                        } else {
                                            echo '<button class="btn btn-danger">Pending</button>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $value->remark ?>
                                    </td>

                                    <td class="text-right">

                                        <a href="<?= getCrmUrl(); ?>master/delete_pt_annual_return/<?= $value->id; ?>">
                                            <button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete pt_annual_return?");'>
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

    <!-- MODELS  -->
    <div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add PT ANNUAL RETURN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/pt_annual_return_save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">
                            <div class="col-sm-6 col-lg-4 col-md-3">
                                <div class="form-group">
                                    <label for="Category">Party<span class="text-danger">*</span></label>
                                    <span class="partyDataUpdate"></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-md-3">
                                <div class="form-group">
                                    <?php $last_month = date('Y-m-d'); ?>
                                    <label for="date">Date<span class="text-danger">*</span></label>
                                    <input id="date" name="date" placeholder="Date" type="date" required class="form-control" value="<?= set_value('date', $last_month); ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('remark'); ?>
                                </span>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="remark">Remark<span class="text-danger"></span></label>
                                    <textarea id="remark" name="remark" placeholder="Remark" class="form-control"><?= set_value('remark') ?></textarea>
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
                    <h5 class="modal-title">Update PT ANNUAL RETURN</h5>
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
                    <h5 class="modal-title">View PT ANNUAL RETURN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel();"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div class="modal" id="pt_annual_returnModel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PT ANNUAL RETURN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closePopup();"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
</main>

<script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/jquery-1.11.3.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        partyData();
                
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url(); ?>crm/master/edit_pt_annual_return/" + productId);
        $("#exampleModals").modal("show");
    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url(); ?>crm/master/view_pt_annual_return/" + productId);
        $("#viewModel").modal("show");
    }

    function pt_annual_returnShow(pagename) {
        jQuery.noConflict();
        $("#pt_annual_returnModel .modal-dialog .modal-body").load("<?= base_url(); ?>crm/master/pt_annual_returnShow/" + pagename);
        $("#pt_annual_returnModel").modal("show");
    }

    function closePopup() {
        $("#pt_annual_returnModel").modal("hide");
    }
    
    function partyData() {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url('crm/master/getPartyFromSubService'); ?>",
            cache   :   false,
            success :   function(value) {
                            console.log(value);
                            $('.partyDataUpdate').html(value);
                        }
        });
    }
</script>