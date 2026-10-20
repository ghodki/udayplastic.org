<main id="main" class="main">

    <div class="pagetitle">
        <h1>Sale</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Sale</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Sale
                </button>
                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software
                    </a>
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
            $res = $this->user_model->getMonthWiseForSubServices('crm_sale');
            $totalWithDone = count($res);
            $totalWithoutDone = $this->user_model->getPartyFromSubServiceCount();
            ?>
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn btn-success" onclick="SaleReport('saledone')">
                    <span>SALE DONE:</span>
                    <?= $totalWithDone ?>
                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-primary" onclick="SaleReport('salenotdone')">
                    <span>SALE NOT DONE:</span>
                    <?= $totalWithoutDone - $totalWithDone ?>

                </button>
                <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-warning" onclick="SaleReport('totalsale')">
                    <span>TOTAL SALE:</span>
                    <?= $totalWithoutDone ?>
                </button>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sale List</h5>
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Party Name</th>
                                    <th scope="col">STATUS</th>
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

                                if (isset($saleData) && $saleData != '') {
                                    foreach ($saleData as  $key => $value) {
                                        $date = $value->date;
                                ?>
                                        <tr scope="<?= $key ?>">
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td><?= $date; ?></td>
                                            <td>
                                                <?= get_title('crm_party', ['id' => $value->party_id], 'party_name') ?>
                                            </td>
                                            <td><?php

                                                echo "<button class='btn btn-success'>Done</button>";
                                                ?>
                                            </td>
                                            <td>
                                                <?= $value->remark ?>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?= getCrmUrl() ?>master/delete_sale/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete Sale?")'><i class="bi bi-trash"></i></button></a>

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
                    <h5 class="modal-title">Add Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/sale_save" method="POST" enctype="multipart/form-data">
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
                                    <?php
                                    $last_month = date('Y-m-d');
                                    ?>
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
                    <h5 class="modal-title">Update Sale</h5>
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
                    <h5 class="modal-title">View Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


    <div class="modal" id="saleModel" tabindex="-1" style="display: none;" aria-hidden="true">
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

<script>
    $(document).ready(function() {
        partyData();

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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_sale/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_sale/" + productId);
        $("#viewModel").modal("show");

    }

    function SaleReport(pagename) {
        jQuery.noConflict();
        $("#saleModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/saleReport/" + pagename);
        $("#saleModel").modal("show");

    }

    function closePopup() {
        $("#saleModel").modal("hide");
    }

    function partyData() {
        $.ajax({
            method: "POST",
            url: "<?= base_url('crm/master/getPartyFromSubService'); ?>",
            cache: false,
            success: function(value) {
                console.log(value);
                $('.partyDataUpdate').html(value);
                $('select').selectize({
                    sortField: 'text',
                });
            }
        });
    }
</script>