<main id="main" class="main">

    <div class="pagetitle">
        <h1>Office Expence</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Office Expence</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Office Expence
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
                        <h5 class="card-title">Office Expence List
                        </h5>
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Applicant Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Payment Mode</th>
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

                                        $date = date('d-m-Y', strtotime($value->storeDate));

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
                                                <?= $value->amount ?>
                                            </td>

                                            <td>
                                                <?= get_title('crm_paymentmode', ['id' => $value->paymentmode], 'name') ?>
                                            </td>
                                          
                                            <td>


                                                <?= $value->remark ?>
                                            </td>

                                            <td class="text-right">

                                                <a href="<?= getCrmUrl() ?>master/delete_office_expence/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete office_expence?")'><i class="bi bi-trash"></i></button></a>

                                                <!-- <button class="btn btn-primary" onclick="viewModel(<?= $value->id ?>)"><i class="bi bi-eye"></i></button> -->

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
                    <h5 class="modal-title">Add Office Expence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" autocomplete="off" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/office_expence_save" method="POST" enctype="multipart/form-data">
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
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input id="amount" name="amount" min="0" placeholder="Amount" type="number" required class="form-control">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('amount'); ?>

                                </span>
                            </div>


                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label for="paymentmode">PaymentMode<span class="text-danger"></span></label>
                                    <select class="form-control select2" name="paymentmode" id="paymentmode">
                                        <option value="">Select Status</option>

                                        <?php
                                        $this->db->select('*');
                                        $this->db->from('crm_paymentmode');
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
                    <h5 class="modal-title">Update Office Expence</h5>
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
                    <h5 class="modal-title">View Office Expence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


    <div class="modal" id="office_expenceModel" tabindex="-1" style="display: none;" aria-hidden="true">
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

        // $("#searchForm").on('submit', function(e) {
        //     e.preventDefault();
        //     var contactForm = $(this);
        //     $.ajax({
        //         url: "<?= base_url() ?>crm/master/filterDsc",
        //         type: 'post',
        //         data: contactForm.serialize(),
        //         success: function(response) {
        //             console.log(response);
        //             if (response.status == 'success') {
        //                 $("#bodyData").load("status_filter");
        //                 toastr.success(response.message);
        //                 // $("#largeModal").modal("hide");
        //                 // window.location.reload();

        //             } else {
        //                 toastr.error(response.message);

        //             }


        //         }
        //     });
        // })

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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_office_expence/" +
            productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_office_expence/" +
            productId);
        $("#viewModel").modal("show");

    }

    function loadData() {
        $("#loadData").load("loadAjax");
    }
    //   $("#exampleModals").modal("show");
  

    function SaleReport(pagename) {
        jQuery.noConflict();
        $("#office_expenceModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/office_expenceReport/" +
            pagename);
        $("#office_expenceModel").modal("show");

    }

    function closePopup() {
        $("#office_expenceModel").modal("hide");
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