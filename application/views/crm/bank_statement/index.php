<main id="main" class="main">

    <div class="pagetitle">
        <h1>Bank Statement</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>crm/company">Home</a></li>
                <li class="breadcrumb-item active">Bank Statement</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="card">
            <div class="card-body" style="padding-bottom:10px;">
                <!-- Large Modal -->
                <button type="button" style="margin-top:10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Add Bank Statement
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
      <section class="section dashboard">

<div class="card">
    <?php
    $user_id = $this->session->userdata('user_id');
    $crm_id = $this->session->userdata('crm_id');
    $month = $this->session->userdata('months');
    $exp = explode('-', $month);

    $data['month'] = $exp[0];
    $data['year'] = $this->session->userdata('fy');
    $this->db->select('*');
    $this->db->from('crm_bankstatement');
    // $this->db->join('crm_party', 'crm_party.id = crm_gstr1.user_id', 'inner');
    $this->db->where('user_id', $user_id);
    $this->db->where('crm_id', $crm_id);
    $this->db->where('month', $exp[0]);
    $this->db->where('year', $this->session->userdata('fy'));
    $this->db->where('status', 1);
    $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
    $totalGstr1 = $this->db->get()->result();
    $totalWithDone = count($totalGstr1);


    $this->db->select('*');
    $this->db->from('crm_party');
    $this->db->where('user_id', $user_id);
    $this->db->where('crm_id', $crm_id);

    $this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
    $this->db->where('status', 1);

    $withoutDone = $this->db->get()->result();
    $totalWithoutDone = count($withoutDone);

    ?>
    <div class="card-body" style="padding-bottom:10px;">
        <!-- Large Modal -->
        <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn btn-success" onclick="SaleReport('done')">
            <span>DONE:</span>
            <?= $totalWithDone ?>
        </button>
        <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-primary" onclick="SaleReport('notdone')">
            <span>NOT DONE:</span>
            <?= $totalWithoutDone - $totalWithDone ?>

        </button>
        <button type="button" style="margin-top:10px;margin-right:10px;float: left;" class="btn btn-warning" onclick="SaleReport('total')">
            <span>TOTAL:</span>
            <?= $totalWithoutDone ?>
        </button>
    </div>
</div>
</section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bank Statement List</h5>
                        <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->

                        <!-- Table with stripped rows -->
                        <table class="table datatable table-responsive" id="datatable">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Party Name</th>
                                    <th scope="col">GSTR1</th>
                                    <th scope="col">GSTR3B</th>
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

                                if (isset($bank_statementData) && $bank_statementData != '') {
                                    foreach ($bank_statementData as  $key => $value) {

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
                                                $gstr3bId = get_title('crm_gstr1', ['party_id' => $value->party_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                                if ($gstr3bId) {
                                                    echo "<button class='btn btn-success'>Done</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger'>Not Done</button>";
                                                }
                                                ?></td>

                                            <td><?php
                                                $gstr3bId = get_title('crm_gstr3b', ['party_id' => $value->party_id, 'month' => $exp[0], 'year' => $this->session->userdata('fy')], 'id');
                                                if ($gstr3bId) {
                                                    echo "<button class='btn btn-success'>Done</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger'>Not Done</button>";
                                                }
                                                ?></td>

                                            <td>
                                                <?= $value->remark ?>
                                            </td>

                                            <td class="text-right">

                                                <a href="<?= getCrmUrl() ?>master/delete_bank_statement/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete bank_statement?")'><i class="bi bi-trash"></i></button></a>

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
                    <h5 class="modal-title">Add Bank Statement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="message" class="text-center"></div>
                <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/bank_statement_save" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row text-dark">


                            <div class="col-md-6 col-sm-6 col-lg-6">
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
                    <h5 class="modal-title">Update Bank Statement</h5>
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
                    <h5 class="modal-title">View Bank Statement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModel()"></button>
                </div>
                <div class="modal-body">

                </div>


            </div>
        </div>
    </div>


    <div class="modal" id="bank_statementModel" tabindex="-1" style="display: none;" aria-hidden="true">
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
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/edit_bank_statement/" + productId);
        $("#exampleModals").modal("show");

    }

    function viewModel(productId) {
        jQuery.noConflict();
        $("#viewModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/view_bank_statement/" + productId);
        $("#viewModel").modal("show");

    }


    //   $("#exampleModals").modal("show");
    $(document).ready(function() {
        // $('.select2').select2();


    });

    function SaleReport(pagename) {
        jQuery.noConflict();
        $("#bank_statementModel .modal-dialog .modal-body").load("<?= base_url() ?>crm/master/bank_statementReport/" + pagename);
        $("#bank_statementModel").modal("show");

    }

    function closePopup() {
        $("#bank_statementModel").modal("hide");
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