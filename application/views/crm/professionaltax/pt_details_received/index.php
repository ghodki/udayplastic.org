<style type="text/css">
    .card {
        margin-bottom: 7px !important;
    }
</style>
    
<main id="main" class="main">
    <?php $ServiceName = getSubServiceName(); ?>
    <div class="pagetitle">
        <h1><?= $ServiceName; ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active"><?= $ServiceName; ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    ADD <?= $ServiceName; ?>
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
                $totalreturn_filed = $this->user_model->getMonthWiseForSubServices('crm_pt_details_received');
                $totalWithDone = count($totalreturn_filed);
                $totalWithoutDone = $this->user_model->getPartyFromSubServiceCount();
            ?>
            <div class="card-body" style="padding-bottom:10px;">
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn btn-success" onclick="pt_details_receivedShow('done');">
                    <span><?= $ServiceName; ?> DONE:</span>
                    <?= $totalWithDone; ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-primary" onclick="pt_details_receivedShow('notdone');">
                    <span><?= $ServiceName; ?> NOT DONE:</span>
                    <?= $totalWithoutDone - $totalWithDone; ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-warning" onclick="pt_details_receivedShow('total');">
                    <span>TOTAL <?= $ServiceName; ?>:</span>
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
                        <h5 class="card-title"><?= $ServiceName; ?> List</h5>
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
                                    if (isset($pt_details_receivedData) && $pt_details_receivedData != '') {
                                        foreach ($pt_details_receivedData as  $key => $value) {
                                ?>
                                <tr scope="<?= $key; ?>">
                                    <td><span><?= $key + 1; ?></span></td>
                                    <td><?= $value->date; ?></td>
                                    <td><?= get_title('crm_party', ['id' => $value->party_id], 'party_name'); ?></td>
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
                                        <a href="<?= getCrmUrl(); ?>master/delete_pt_details_received/<?= $value->id; ?>">
                                            <button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete pt_details_received?");'>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </a>
                                        <button class="btn btn-primary" onclick="viewModel(<?= $value->id ?>);">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-primary" onclick="companyEdit(<?= $value->id ?>);">
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
                    <h5 class="modal-title">Add <?= $ServiceName; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url(); ?>crm/master/pt_details_received_save" method="POST" enctype="multipart/form-data">
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
                                    <input type="date" name="date" value="<?= set_value('date', $last_month); ?>" class="form-control" id="date" placeholder="Date" required="" />
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('remark'); ?>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status<span class="text-danger"></span></label>
                                    <select  name="status" id="status" class="form-control" title="Status">
                                        <option value="1" class="text-success">Approved</option>
                                        <option value="0" class="text-danger">Pending</option>
                                        <?php
                                            $res = $this->user_model->getStatusForService();
                                            if ($res) {
                                                foreach ($res as $row) {
                                        ?>
                                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('status'); ?>
                                </span>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="remark">Remark<span class="text-danger"></span></label>
                                    <textarea name="remark" id="remark" class="form-control" placeholder="Remark"><?= set_value('remark'); ?></textarea>
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
                    <h5 class="modal-title">Update <?= $ServiceName; ?></h5>
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
                    <h5 class="modal-title">View <?= $ServiceName ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel();"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div class="modal" id="pt_details_receivedModel" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $ServiceName; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closePopup()"></button>
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_pt_details_received/" + productId);
        $("#exampleModals").modal("show");
    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_pt_details_received/" + productId);
        $("#viewModel").modal("show");
    }

    function pt_details_receivedShow(pagename) {
        jQuery.noConflict();
        $("#pt_details_receivedModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/pt_details_receivedShow/" + pagename);
        $("#pt_details_receivedModel").modal("show");
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

    function closePopup() {
        $("#pt_details_receivedModel").modal("hide");
    }
</script>