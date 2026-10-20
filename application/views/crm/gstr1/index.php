<style type="text/css">
    .card {
        margin-bottom: 7px !important;
    }
    .dataTables_length {
        margin-top: -30px;
    }
</style>
    
<main id="main" class="main">
    <div class="pagetitle">
        <h1>GSTR1</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">GSTR1</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add GSTR1
                </button>
                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software
                    </a>
                </button>
            </div>
        </div>
    </section>

    <section class="section dashboard">
        <div class="card">
            <?php
                $res = $this->user_model->getMonthWiseForSubServices('crm_gstr1');
                $totalWithDone = count($res);
                $totalWithoutDone = $this->user_model->getPartyFromSubServiceCount();
            ?>
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn btn-success" onclick="gstr1Show('gstr1done');">
                    <span>GSTR1 DONE:</span>
                    <?= $totalWithDone ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-primary" onclick="gstr1Show('gstr1notdone');">
                    <span>GSTR1 NOT DONE:</span>
                    <?= $totalWithoutDone - $totalWithDone ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-warning" onclick="gstr1Show('totalgstr1');">
                    <span>TOTAL GSTR1:</span>
                    <?= $totalWithoutDone ?>
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
                        <h5 class="card-title">GSTR1 List</h5>
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Party Name</th>
                                    <th scope="col">GSTR3B</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (isset($gstr1Data) && $gstr1Data != '') {
                                        $crm_id = $this->session->userdata('crm_id');
                                        $month = $this->session->userdata('months');
                                        $exp = explode('-', $month);
                                        $count = 0;
                                        foreach ($gstr1Data as $key => $value) {
                                            $count++;
                                ?>
                                <tr>
                                    <td> <span><?= $count; ?></span></td>
                                    <td><?= $value->date; ?></td>
                                    <td><?= get_title('crm_party', ['id' => $value->party_id], 'party_name'); ?></td>
                                    <td>
                                        <?php
                                            $gstr3bId = get_title('crm_gstr3b', ['party_id' => $value->party_id, 'subservice_id' => $this->session->userdata('sub_service_id'), 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                            if ($gstr3bId) {
                                                echo "<button class='btn btn-success'>Done</button>";
                                            } else {
                                                echo "<button class='btn btn-danger'>Not Done</button>";
                                            }
                                        ?>
                                    </td>
                                    <td> <?= $value->remark; ?></td>
                                    <td class="text-right">
                                        <a href="<?= getCrmUrl(); ?>master/delete_gstr1/<?= $value->id; ?>">
                                            <button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete Gstr1?")'>
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
                    <h5 class="modal-title">Add GSTR1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url(); ?>crm/master/gstr1_save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="Category">Party<span class="text-danger">*</span></label>
                                    <span id="partyData" class="partyDataUpdate"></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <?php $last_month = date('Y-m-d'); ?>
                                    <label for="date">Date<span class="text-danger">*</span></label>
                                    <input id="date" name="date" placeholder="Date" type="date" required class="form-control" value="<?= set_value('date', $last_month) ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('remark'); ?>
                                </span>
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
                    <h5 class="modal-title">Update GSTR1</h5>
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
                    <h5 class="modal-title">View GSTR1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel();"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div class="modal" id="gstr1Model" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">GSTR1</h5>
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_gstr1/" + productId);
        $("#exampleModals").modal("show");
    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_gstr1/" + productId);
        $("#viewModel").modal("show");
    }

    function gstr1Show(pagename) {
        jQuery.noConflict();
        $("#gstr1Model .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/gstr1Show/" + pagename);
        $("#gstr1Model").modal("show");
    }

    function closePopup() {
        $("#gstr1Model").modal("hide");
    }

    function partyData() {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url('crm/master/getPartyFromSubServiceMonthWise'); ?>",
            cache   :   false,
            success :   function(value) {
                            $('.partyDataUpdate').html(value);
                            $('select').selectize({
                                sortField: 'text',
                            });
                        }
        });
    }
</script>