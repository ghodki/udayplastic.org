<main id="main" class="main">

    <div class="pagetitle">
        <h1>Active Party</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Active Party</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Party
                </button>
                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software</a>
                </button>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Party List</h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>

                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">GST Number</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($partyData) && $partyData != '') {
                                    $count = 0;
                                    foreach ($partyData as  $key => $value) {
                                        $sub_service_assigns = json_decode($value->sub_services_id);
                                        if ($sub_service_assigns) {
                                            foreach ($sub_service_assigns as $services_data_id) {
                                                if ($services_data_id == $this->session->userdata('sub_service_id')) {
                                                    $date = $value->created_at;
                                                    $newDate = date("d-m-Y", $date);
                                ?>
                                                    <tr scope="<?= $key ?>">
                                                        <td>
                                                            <span><?= ++$count; ?></span>
                                                        </td>
                                                        <td><?= $newDate; ?></td>
                                                        <td>
                                                            <?= $value->party_name ?>
                                                        </td>
                                                        <td><?= $value->email ?></td>
                                                        <td><?= $value->gst_no ?></td>
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
                                                            <button class="btn btn-primary" onclick="viewModel(<?= $value->id ?>)"><i class="bi bi-eye"></i></button>
                                                            <button class="btn btn-primary" onclick="companyEdit(<?= $value->id ?>)"><i class="ri-edit-box-line"></i></button>
                                                        </td>
                                                    </tr>
                                <?php
                                                }
                                            }
                                        }
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
                    <h5 class="modal-title">Add Party</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/party/save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">
                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="name">Party Name<span class="text-danger">*</span></label>
                                    <input id="name" name="name" placeholder="Party Name" required type="text" class="form-control" value="<?= set_value('name') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('name'); ?>

                                </span>
                            </div>

                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="authorised_name">Authorised Name<span class="text-danger"></span></label>
                                    <input id="authorised_name" name="authorised_name" placeholder="Authorised  Name" type="text" class="form-control" value="<?= set_value('authorised_name') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('authorised_name'); ?>

                                </span>
                            </div>

                            <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="number">Mobile No<span class="text-danger"></span></label>

                                    <input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= set_value('number') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('number'); ?>

                                </span>
                            </div>
                            <div class="col-md-4  col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="email">Email<span class="text-danger"></span></label>

                                    <input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= set_value('email') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('email'); ?>

                                </span>
                            </div>
                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="gst">GST No<span class="text-danger"></span></label>

                                    <input name="gst" placeholder="GST Number" type="text" class="form-control" value="<?= set_value('gst') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('gst'); ?>

                                </span>
                            </div>
                            <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="address">Address<span class="text-danger"></span></label>

                                    <input name="address" placeholder="Address" type="text" class="form-control" value="<?= set_value('address') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('address'); ?>

                                </span>
                            </div>
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


                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="incometax_file_no">Income Tax File No<span class="text-danger"></span></label>

                                    <input name="incometax_file_no" placeholder="Income Tax File No" type="text" class="form-control" value="<?= set_value('incometax_file_no') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('incometax_file_no'); ?>

                                </span>
                            </div>

                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="tds_file_no">TDS File No<span class="text-danger"></span></label>

                                    <input name="tds_file_no" placeholder="TDS File No" type="text" class="form-control" value="<?= set_value('tds_file_no') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('tds_file_no'); ?>

                                </span>
                            </div>

                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="gst_file_no">GST File No<span class="text-danger"></span></label>

                                    <input name="gst_file_no" placeholder="GST File No" type="text" class="form-control" value="<?= set_value('gst_file_no') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('gst_file_no'); ?>

                                </span>
                            </div>

                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="pt_file_no">PT File No<span class="text-danger"></span></label>

                                    <input name="pt_file_no" placeholder="PT File No" type="text" class="form-control" value="<?= set_value('pt_file_no') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('pt_file_no'); ?>

                                </span>
                            </div>

                        </div>

                        <div class="mt-3 row text-dark">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="gst_username">GST Username<span class="text-danger"></span></label>
                                    <input id="gst_username" name="gst_username" placeholder="GST Username" type="text" class="form-control" value="<?= set_value('gst_username') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('gst_username'); ?>

                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="gst_password">GST Password<span class="text-danger"></span></label>
                                    <input id="gst_password" name="gst_password" placeholder="GST Password" type="text" class="form-control" value="<?= set_value('gst_password') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('gst_password'); ?>

                                </span>
                            </div>
                        </div>

                        <div class="row mt-2 text-dark">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="tds_username">TDS Username<span class="text-danger"></span></label>
                                    <input id="tds_username" name="tds_username" placeholder="TDS Username" type="text" class="form-control" value="<?= set_value('tds_username') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('tds_username'); ?>

                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="tds_password">TDS Password<span class="text-danger"></span></label>
                                    <input id="tds_password" name="tds_password" placeholder="TDS Password" type="text" class="form-control" value="<?= set_value('tds_password') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('tds_password'); ?>

                                </span>
                            </div>
                        </div>


                        <div class="row mt-2 text-dark">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="incomeTax_username">IncomeTax Username<span class="text-danger"></span></label>
                                    <input id="incomeTax_username" name="incomeTax_username" placeholder="Income Tax Username" type="text" class="form-control" value="<?= set_value('incomeTax_username') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('incomeTax_username'); ?>

                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="incomeTax_password">Income Tax Password<span class="text-danger"></span></label>
                                    <input id="incomeTax_password" name="incomeTax_password" placeholder="Income Tax Password" type="text" class="form-control" value="<?= set_value('incomeTax_password') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('incomeTax_password'); ?>

                                </span>
                            </div>
                        </div>


                        <div class="row mt-2 text-dark">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="pt_username">PT Username<span class="text-danger"></span></label>
                                    <input id="pt_username" name="pt_username" placeholder="PT Username" type="text" class="form-control" value="<?= set_value('pt_username') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('pt_username'); ?>

                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="pt_password">PT Password<span class="text-danger"></span></label>
                                    <input id="pt_password" name="pt_password" placeholder="PT Password" type="text" class="form-control" value="<?= set_value('pt_password') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('pt_password'); ?>

                                </span>
                            </div>

                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="ewaybill_username">Eway-Bill Username<span class="text-danger"></span></label>
                                    <input id="ewaybill_username" name="ewaybill_username" placeholder="Eway-Bill Username" type="text" class="form-control" value="<?= set_value('ewaybill_username') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('ewaybill_username'); ?>

                                </span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="ewaybill_password">Eway-Bill Password<span class="text-danger"></span></label>
                                    <input id="ewaybill_password" name="ewaybill_password" placeholder="Eway-Bill Password" type="text" class="form-control" value="<?= set_value('ewaybill_password') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('tds_password'); ?>

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
                    <h5 class="modal-title">Update Party</h5>
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
                    <h5 class="modal-title">View Party</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
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


    function changeStatus(party_id, status) {
        let amama = confirm("Are You Sure Want to change Status?");
        if (amama) {
            var party_id = party_id;
            var status = status;
            if (!isNaN(party_id) && !isNaN(status)) {
                $.ajax({
                    url: "<?= base_url() ?>crm/party/changeStatus",
                    type: 'post',
                    data: {
                        party_id: party_id,
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/party/edit/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/party/view/" + productId);
        $("#viewModel").modal("show");

    }

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