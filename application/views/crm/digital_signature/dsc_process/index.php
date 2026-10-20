<main id="main" class="main">

    <div class="pagetitle">
        <h1>DSC PROCESS</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">DSC PROCESS</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add DSC PROCESS
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
                    <div class="card-header">
                        <form id="searchForm" method="get">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <?php
                                        $date = date('Y-m-01');
                                        ?>
                                        <label for="firstDate">First Date</label>
                                        <input type="date" name="firstDate" id="firstDate" value="<?= $this->input->get('firstDate') ? $this->input->get('firstDate') : $date ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    $lastDate = date('Y-m-d');

                                    ?>
                                    <div class="form-group">
                                        <label for="lastDate">Last Date</label>
                                        <input type="date" name="lastDate" id="lastDate" value="<?= $this->input->get('lastDate') ? $this->input->get('lastDate') : $lastDate ?>" class="form-control">
                                    </div>
                                </div>
                                <?php
                                $user_id = $this->session->userdata('user_id');
                                $crm_id = $this->session->userdata('crm_id');
                                $this->db->select('*');
                                $this->db->from('crm_status');
                                $crmStatus = $this->db->get()->result();
                                ?>
                                <div class="col-md-2">

                                    <div class="form-group">
                                        <label for="lastDate">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Select Status</option>
                                            <?php
                                            if ($crmStatus) {
                                                foreach ($crmStatus as $crmData) {
                                            ?>
                                                    <option value="<?= $crmData->id ?>" <?= $this->input->get('status') == $crmData->id ? 'selected' : '' ?>><?= $crmData->name ?></option>

                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <!-- <input type="date" required name="lastDate" id="lastDate" class="form-control"> -->
                                    </div>
                                </div>


                                <?php
                                $user_id = $this->session->userdata('user_id');
                                $crm_id = $this->session->userdata('crm_id');
                                $this->db->select('*');
                                $this->db->from('crm_type');
                                $crm_type = $this->db->get()->result();
                                ?>
                                <div class="col-md-2">

                                    <div class="form-group">
                                        <label for="lastDate">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="">Select Type</option>

                                            <?php
                                            if ($crm_type) {
                                                foreach ($crm_type as $crmData) {
                                            ?>
                                                    <option value="<?= $crmData->id ?>" <?= $this->input->get('type') == $crmData->id ? 'selected' : '' ?>><?= $crmData->name ?></option>

                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <!-- <input type="date" required name="lastDate" id="lastDate" class="form-control"> -->
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group" style="margin-top: 23px;">
                                        <input type="submit" name="search" id="search" class="btn btn-primary">
                                        <a href="<?= base_url() ?>crm/master/dsc_process" class="btn btn-danger">Reset</a>

                                    </div>
                                </div>



                            </div>



                        </form>

                        <div class="col-md-2">
                            <!-- <form> -->
                            <div class="form-group" style="margin-top: 23px;">
                                <button class="btn btn-outline-info" title="Export" id="export-button">Export</button>


                            </div>
                            <!-- </form> -->
                        </div>

                    </div>

                </div>



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
                        <h5 class="card-title">DSC PROCESS List
                        </h5>
                        <table class="table table-responsive datatbale">
                            <thead>
                                <tr>
                                    <th rowspan="1" colspan="1" aria-label=""><input type="checkbox" id="product-selectAll"><label for="product-selectAll"></label></th>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Applicant Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mob.</th>
                                    <th scope="col">Expiry Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Ref By</th>
                                    <th scope="col">Invoice Number</th>
                                    <th scope="col">Collected By</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="bodyData">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                <?php
                                    $this->session->unset_userdata('msg');
                                } ?>
                                <?php

                                if (isset($result) && $result != '') {
                                    foreach ($result as  $key => $value) {

                                        $date = $value->date;

                                ?>

                                        <tr scope="<?= $key ?>">
                                            <td class="cheking">
                                                <input class="checkdata" type="checkbox" name="product_id[]" value="<?php echo $value->id; ?>" ?>

                                                <!-- <span><?= $key + 1; ?></span> -->
                                            </td>
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
                                                <?= $value->mobile_number ?>
                                            </td>
                                            <td>
                                                <?= $value->expiry_date ?>
                                            </td>
                                            <td>
                                                <?= get_title('crm_status', ['id' => $value->status], 'name') ?>
                                            </td>
                                            <td>
                                                <?= get_title('crm_type', ['id' => $value->type], 'name'); ?>
                                            </td>
                                            <td>
                                                <?= $value->ref_by ?>
                                            </td>
                                            <td>
                                                <?= $value->invoiceNumber ?>
                                            </td>
                                            <td>
                                                <?= $value->selectedBy ?>
                                            </td>
                                            <td>
                                                <?= $value->remark ?>
                                            </td>

                                            <td class="text-right">

                                                <a href="<?= getCrmUrl() ?>master/delete_dsc_process/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete dsc_process?")'><i class="bi bi-trash"></i></button></a>

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
                    <h5 class="modal-title">Add DSC PROCESS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" autocomplete="off" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/dsc_process_save" method="POST" enctype="multipart/form-data">
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
                                    <input id="email" name="email" placeholder="Email" type="email" class="form-control">
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
                            <style>
                                .highlight {
                                    background-color: blue;
                                }
                            </style>



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
                            <style>
                                #refByList {
                                    position: absolute;
                                    z-index: 999;
                                    background: #fff;
                                }
                            </style>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group" style="position:relative;">
                                    <label for="ref_by">Ref By<span class="text-danger"></span></label>
                                    <input id="ref_by" name="ref_by" placeholder="Ref By" onkeydown="myFunction(event)" type="text" class="form-control">
                                </div>
                                <!-- <div id="refByList"></div> -->
                                <div class="card">
                                    <div class="card-body">
                                        <ul id="refByList" class="card-list">


                                        </ul>
                                    </div>
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

                            <div class="col-md-4 col-sm-6 col-lg-4">
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

                            <div class="col-md-4 col-sm-12 col-lg-4">
                                <div class="form-group">
                                    <label for="collectedBy">Collected By<span class="text-danger"></span></label>
                                    <input id="selectedBy" name="selectedBy" placeholder="Collected By" type="text" class="form-control" value="<?= set_value('selectedBy') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('selectedBy'); ?>

                                </span>
                            </div>


                            <div class="col-md-4 col-sm-12 col-lg-4">
                                <div class="form-group">
                                    <label for="collectedBy">Invoice Number<span class="text-danger"></span></label>
                                    <input id="invoiceNumber" name="invoiceNumber" placeholder="invoiceNumber" type="text" class="form-control" value="<?= set_value('invoiceNumber') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('invoiceNumber'); ?>

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
                    <h5 class="modal-title">Update DSC PROCESS</h5>
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
                    <h5 class="modal-title">View DSC PROCESS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


    <div class="modal" id="dsc_processModel" tabindex="-1" style="display: none;" aria-hidden="true">
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

        $("#product-selectAll").click(function() {
            $(".checkdata").prop('checked', $(this).prop('checked'));
        });

        $(function() {
            $("#export-button").click(function() {
                var selected = new Array();
                $(".cheking input[type=checkbox]:checked").each(function() {
                    selected.push(this.value);
                });
                if (selected.length > 0) {
                    $.ajax({
                        url: "<?= getCrmUrl() ?>master/export_dsc_prcessed",
                        type: 'post',
                        data: {
                            'ids': selected
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                toastr.success(response.message);
                                var $a = $("<a>");
                                $a.attr("href", response.file);
                                $("body").append($a);
                                const date = new Date()
                                date.toLocaleString();
                                $a.attr("download", date.toLocaleString() + "dsc_process.xls");
                                $a[0].click();
                                $a.remove();
                            } else {
                                toastr.error(response.message);

                            }


                        }
                    });
                    // var id = selected;
                    // console.log(id);
                    // href = "<?= getCrmUrl() ?>master/export_dsc_prcessed";
                    // var $a = $("<a>");
                    // $a.attr("href", href);
                    // $("body").append($a);
                    // $a.attr("download", "dsc_process.xls");
                    // $a[0].click();
                    // $a.remove();

                }

            });

        });


        $("#ref_by").on('keyup', function(e) {
            e.preventDefault();
            let ref = $("#ref_by").val();
            if (ref != '') {
                $.ajax({
                    url: "<?= getCrmUrl() ?>master/refBySearch",
                    type: 'post',
                    data: {
                        refName: ref
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 1) {
                            $("#refByList").css("display", "block");
                            console.log(response.data);
                            var myHtml = "";
                            $.each(response.data, function(i, item) {
                                myHtml += "<a href='#'><li onkeydown='selectRef(" + event +
                                    ")' id='selectRef" + i + "'>" + item.ref_by +
                                    "</li></a>";
                            });
                            $("#refByList").html(myHtml);
                        } else {
                            $("#refByList").css("display", "none");
                        }
                    }
                });
            } else {
                $("#refByList").css("display", "none");

            }
        })


    })

    function myFunction(e) {
        if (e.keyCode == '40') {
            let setVal = $("#selectRef0").val();
            console.log(setVal)
            if (setVal) {
                $("#refByList").focus();
                document.getElementById("selectRef0").selected = true;
            }
        }
    }

    function setRef(e) {
        if (e.keyCode == '40') {
            let setRefName = e.target.value;
            let setVal = $("#refByList").val();
            console.log(setVal, "setVal");
            $("#refByList").val(setVal);
            $("#refByList").css("display", "none");
        }
    }

    function selectRef(e) {
        if (e.keyCode == '13') {
            // let setVal = $("#selectRef0").val();
            let setRefName = e.target.value;
            let setVal = $("#refByList").val();
            console.log(setVal, "setVal");
            $("#refByList").val(setVal);
            $("#refByList").css("display", "none");
        }
    }

    function companyEdit(productId) {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_dsc_process/" +
            productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_dsc_process/" +
            productId);
        $("#viewModel").modal("show");

    }

    function loadData() {
        $("#loadData").load("loadAjax");
    }
    //   $("#exampleModals").modal("show");
    $(document).ready(function() {
        // $('.select2').select2();
        document.body.style.zoom = 0.8;
        $("body").addClass("toggle-sidebar");
        // loadData();

    });

    function SaleReport(pagename) {
        jQuery.noConflict();
        $("#dsc_processModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/dsc_processReport/" +
            pagename);
        $("#dsc_processModel").modal("show");

    }

    function closePopup() {
        $("#dsc_processModel").modal("hide");
    }

    function SeeResult(id) {
        let firstDate = $("#firstDate").val();
        let lastDate = $("#lastDate").val();
        $("#loadData").load("status_filter/" + id + "/" + firstDate + "/" + lastDate);
    }

    function seeType(typeId) {
        let firstDate = $("#firstDate").val();
        let lastDate = $("#lastDate").val();
        $("#loadData").load("status_filter//" + firstDate + "/" + lastDate + "/" + typeId);
    }

    function searchDscProcess() {

        $("#loadData").load("searchDscProcess/" + firstDate + "/" + lastDate);
    }


    // // Get all the <li> elements into a collection
    // var listItems = document.querySelectorAll(".card-list li");

    // // Set up a counter to keep track of which <li> is selected
    // var currentLI = 0;

    // // Initialize first li as the selected (focused) one:
    // listItems[currentLI].classList.add("highlight");

    // // Set up a key event handler for the document
    // document.addEventListener("keydown", function(event) {
    //     // Check for up/down key presses
    //     switch (event.keyCode) {
    //         case 38: // Up arrow    
    //             // Remove the highlighting from the previous element
    //             listItems[currentLI].classList.remove("highlight");

    //             currentLI = currentLI > 0 ? --currentLI : 0; // Decrease the counter      
    //             listItems[currentLI].classList.add("highlight"); // Highlight the new element
    //             break;
    //         case 40: // Down arrow
    //             // Remove the highlighting from the previous element
    //             listItems[currentLI].classList.remove("highlight");

    //             currentLI = currentLI < listItems.length - 1 ? ++currentLI : listItems.length - 1; // Increase counter 
    //             listItems[currentLI].classList.add("highlight"); // Highlight the new element
    //             break;
    //     }
    // });
</script>

<script>
    $(document).ready(function() {
        $('.datatbale').DataTable({
            "order": [1, "asc"],
            "autoWidth": false,
            dom: 'Blfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'colvis',
            ],
            columnDefs: [{
                "orderable": false,
                "targets": [0],
            }, ]


        });

    });
</script>