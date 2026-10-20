<main id="main" class="main">

    <div class="pagetitle">
        <h1>Correction</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Correction</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Correction
                </button>

                <button type="button" style="margin-top:10px;float: right;" class="btn btn-primary">
                    <a href="<?= base_url() ?>inventory_billing" target="_blank" class="text-white">
                        Inventory Software</a>
                </button>





            </div>
        </div>
    </section>

    <!-- <style>
        .card {
            margin-bottom: 7px !important;
        }
    </style> -->
    <!-- <section class="section dashboard">

        <div class="card"> -->
    <?php
    // $user_id = $this->session->userdata('user_id');
    // $crm_id = $this->session->userdata('crm_id');
    // $this->db->select('*');
    // $this->db->from('crm_gstr3b');
    // $this->db->where('user_id', $user_id);
    // $this->db->where('crm_id', $crm_id);
    // $this->db->where('status', 1);
    // $totalGstr1 = $this->db->get()->result();
    // $totalWithDone = count($totalGstr1);


    // $this->db->select('*');
    // $this->db->from('crm_party');
    // $this->db->where('user_id', $user_id);
    // $this->db->where('crm_id', $crm_id);
    // $this->db->where('status', 1);

    // $withoutDone = $this->db->get()->result();
    // $totalWithoutDone = count($withoutDone);

    // $this->db->select('*');
    // $this->db->from('crm_party_category');
    // // $this->db->where('user_id', $user_id);
    // // $this->db->where('crm_id', $crm_id);
    // $category = $this->db->get()->result();
    // if ($category) {
    //     foreach ($category as $catData) {
    ?>
    <!-- <div class="card-body" style="padding-bottom:10px;"> -->
    <!-- Large Modal -->
    <?php

    // $this->db->select('*');
    // $this->db->from('crm_party');
    // $this->db->where('user_id', $user_id);
    // $this->db->where('crm_id', $crm_id);
    // $this->db->where('status', 1);
    // $this->db->where('category_id', $catData->id);
    // $totalUserForCate = $this->db->get()->result();
    // $tuforCate = count($totalUserForCate);


    // $this->db->select('*');
    // $this->db->from('crm_correction');
    // $this->db->where('user_id', $user_id);
    // $this->db->where('crm_id', $crm_id);
    // $this->db->where('category_id', $catData->id);
    // $counssss = $this->db->get()->result();
    // $counter = count($counssss);



    ?>
    <!-- <button type="button" style="margin-top:10px;margin-right:50px;float: left;width: 200px;" class="btn-lg btn btn-success" onclick="correctionReport(<?= $catData->id ?>,'totalcorrection')">
                            <span><?= $catData->category_name ?></span>
                        </button>

                        <button type="button" style="margin-top:10px;margin-right:50px;float: left;width: 200px;" class="btn-lg btn btn-primary" onclick="correctionReport(<?= $catData->id ?>,'totalcorrection')">
                            <span>Total:<?= $tuforCate ?></span>
                        </button>
                        <button type="button" style="margin-top:10px;margin-right:50px;float: left;width: 200px;" class="btn-lg btn btn-warning" onclick="correctionReport(<?= $catData->id ?>,'correctiondone')">
                            <span>correction Done:<?= $counter ?></span>
                        </button>

                        <button type="button" style="margin-top:10px;margin-right:50px;float: left;width: 200px;" class="btn-lg btn btn-danger" onclick="correctionReport(<?= $catData->id ?>,'correctionnotdone')">
                            <span>Remaining:<?= $tuforCate - $counter ?></span>
                        </button> -->


    <!-- </div> -->
    <?php
    //     }
    // }
    ?>
    <!-- </div>
    </section> -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn btn-danger text-danger" id="profile-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link btn btn-success text-success" id="contact-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">Approved</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                                <div class="card-body">
                                    <h5 class="card-title">All Correction List</h5>
                                    <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

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
                                            <?php if ($this->session->flashdata('msg')) { ?>
                                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                                    <?php echo $this->session->flashdata('msg'); ?>
                                                </div>
                                            <?php
                                                $this->session->unset_userdata('msg');
                                            } ?>
                                            <?php

                                            if (isset($correctionData) && $correctionData != '') {
                                                foreach ($correctionData as  $key => $value) {

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

                                                            <a href="<?= getCrmUrl() ?>master/delete_correction/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete correction?")'><i class="bi bi-trash"></i></button></a>

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
                            <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="profile-tab">

                                <div class="card-body">
                                    <h5 class="card-title">All Correction List</h5>
                                    <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

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
                                            <?php if ($this->session->flashdata('msg')) { ?>
                                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                                    <?php echo $this->session->flashdata('msg'); ?>
                                                </div>
                                            <?php
                                                $this->session->unset_userdata('msg');
                                            } ?>
                                            <?php

                                            if (isset($pendingData) && $pendingData != '') {
                                                foreach ($pendingData as  $key => $value) {

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

                                                            <a href="<?= getCrmUrl() ?>master/delete_correction/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete correction?")'><i class="bi bi-trash"></i></button></a>

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
                            <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="card-body">
                                    <h5 class="card-title">All Correction List</h5>
                                    <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

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
                                            <?php if ($this->session->flashdata('msg')) { ?>
                                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                                    <?php echo $this->session->flashdata('msg'); ?>
                                                </div>
                                            <?php
                                                $this->session->unset_userdata('msg');
                                            } ?>
                                            <?php

                                            if (isset($approvedData) && $approvedData != '') {
                                                foreach ($approvedData as  $key => $value) {

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

                                                            <a href="<?= getCrmUrl() ?>master/delete_correction/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete correction?")'><i class="bi bi-trash"></i></button></a>

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

                </div>



            </div>
        </div>
    </section>


    <div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Correction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/correction_save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Category">Party<span class="text-danger">*</span></label>
                                    <select class="form-control select2" required name="party" id="party">
                                        <option value="">Select Party</option>

                                        <?php
                                        $user_id = $this->session->userdata('user_id');
                                        $crm_id = $this->session->userdata('crm_id');
                                        $this->db->select('*');
                                        $this->db->from('crm_party');
                                        $this->db->where('user_id', $user_id);
                                        $this->db->where('crm_id', $crm_id);
                                        $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
                                        $this->db->where('status', 1);
                                        $getResu = $this->db->get()->result();
                                        if ($getResu) {
                                            foreach ($getResu as $category) {
                                        ?>
                                                <option value="<?= $category->id ?>"><?= $category->party_name ?></option>

                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php
                                    $last_month = date('Y-m-d', strtotime('-1 month', time()));
                                    ?>
                                    <label for="date">Date<span class="text-danger">*</span></label>
                                    <input id="date" name="date" placeholder="Date" type="date" required class="form-control" value="<?= set_value('date', $last_month) ?>">
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
                    <h5 class="modal-title">Update correction</h5>
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
                    <h5 class="modal-title">View correction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


    <div class="modal" id="correctionModel" tabindex="-1" style="display: none;" aria-hidden="true">
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_correction/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_correction/" + productId);
        $("#viewModel").modal("show");

    }


    //   $("#exampleModals").modal("show");
    $(document).ready(function() {
        // $('.select2').select2();


    });

    function correctionReport(category_id, pagename) {
        jQuery.noConflict();
        $("#correctionModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/correctionReport/" + pagename + "/" + category_id);
        $("#correctionModel").modal("show");

    }

    function closePopup() {
        $("#correctionModel").modal("hide");
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