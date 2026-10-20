<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-blue pb-8 pt-5 pt-md-8">
                        <div class="container-fluid">
                            <button style="margin-left:10px" type="button" class="btn btn-dark" data-toggle="modal" data-target="#myModal">
                                Add New Company
                            </button>

                            <!-- The Modal -->
                            <div class="modal fade" id="myModal">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">

                                        <div class="modal-body">
                                            <div class="header-body ">
                                                <h2 style="color:black;padding:10px 0px 10px 0px" class="text-center">Add Company</h2>
                                                <!-- Card stats -->
                                                <div class="row">
                                                    <div id="message" class="text-center"></div>
                                                    <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= getCrmUrl() ?>company/save" method="POST" enctype="multipart/form-data">
                                                        <div class="row text-dark">
                                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                                <div class="form-group">
                                                                    <lable for="name">Company Name<span class="text-danger">*</span></lable>
                                                                    <input id="name" name="name" placeholder="Company Name*" type="text" class="form-control" required value="<?= set_value('name') ?>">
                                                                </div>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('name'); ?>

                                                                </span>
                                                            </div>
                                                            <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                                                <lable for="state">Financial Year<span class="text-danger">*</span></lable>

                                                                <select name="financial_year" id="financial_year" class="form-control" required>
                                                                    <option value="">-Select-</option>
                                                                    <?php $financial_year = $this->db->get('year')->result();
                                                                    if ($financial_year) {
                                                                        foreach ($financial_year as $row) { ?>
                                                                            <option value="<?= $row->id ?>" <?= set_value('financial_year') == $row->id ? 'selected' : ''; ?>><?= $row->year; ?></option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                                                <div class="form-group">
                                                                    <lable for="number">Mobile No<span class="text-danger"></span></lable>

                                                                    <input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= set_value('number') ?>">
                                                                </div>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('number'); ?>

                                                                </span>
                                                            </div>
                                                            <div class="col-md-4  col-sm-6 col-lg-4">
                                                                <div class="form-group">
                                                                    <lable for="email">Email<span class="text-danger"></span></lable>

                                                                    <input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= set_value('email') ?>">
                                                                </div>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('email'); ?>

                                                                </span>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                                <div class="form-group">
                                                                    <lable for="gst">GST No<span class="text-danger"></span></lable>

                                                                    <input name="gst" placeholder="GST Number" type="text" class="form-control" value="<?= set_value('gst') ?>">
                                                                </div>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('gst'); ?>

                                                                </span>
                                                            </div>
                                                            <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                                                <div class="form-group">
                                                                    <lable for="contact">Contact<span class="text-danger"></span></lable>

                                                                    <input name="contact" placeholder="Contact person" type="text" class="form-control" value="<?= set_value('contact') ?>">
                                                                </div>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('contact'); ?>

                                                                </span>
                                                            </div>
                                                            <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                                                <lable for="state">State Name<span class="text-danger">*</span></lable>

                                                                <select name="state" id="state" class="form-control" required>
                                                                    <option value="">-Select-</option>
                                                                    <?php $states = $this->db->get('state_list')->result();
                                                                    if ($states) {
                                                                        foreach ($states as $row) { ?>
                                                                            <option value="<?= $row->id ?>" data-id="<?= $row->code ?>" data-state="<?= $row->state ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                                <div class="form-group">
                                                                    <lable for="adddress">Address<span class="text-danger"></span></lable>

                                                                    <input name="address" placeholder="Address" type="text" class="form-control" value="<?= set_value('address') ?>">
                                                                </div>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('address'); ?>

                                                                </span>
                                                            </div>

                                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                                <lable for="frmt">Billing Format <span class="text-danger"></span></lable>

                                                                <select id="cate" name="cate_id" class="form-control">

                                                                    <option value="">Billing Format</option>
                                                                    <?php
                                                                    $cate = $this->user_model->getResult('billing_format');
                                                                    if ($cate) {
                                                                        foreach ($cate as $key => $value) {
                                                                    ?>
                                                                            <option value="<?= $value->id ?>" <?= set_value('cate_id') == $value->id ? 'selected' : ''; ?>><?= $value->name; ?></option>


                                                                    <?php    }
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('cate_id'); ?>

                                                                </span>
                                                            </div>

                                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                                <lable for="at">Accounting Type<span class="text-danger">*</span></lable>
                                                                <select id="ac" name="ac" class="form-control">
                                                                    <option value=''>Accounting Type</option>
                                                                    <option value="1">Basic</option>
                                                                    <option value="2">Advanced</option>
                                                                </select>
                                                                <span class="col-sm-4 control-label">
                                                                    <?php echo form_error('ac'); ?>

                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-5">
                                                            <div class="col-md-12 text-center">
                                                                <button type="submit" class="btn btn-dark">
                                                                    Save
                                                                </button>
                                                                <a href="#" type="reset" class="btn btn-secondary">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>





                            </div>
                        </div>
                        <div class="container-fluid mb-9" style="margin-top:10px;">

                            <div class="col">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Inventory_billing List</label>
                                        <div class="row mt-5">
                                            <?php if ($this->session->flashdata('msg')) { ?>
                                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                                    <?php echo $this->session->flashdata('msg'); ?>
                                                </div>
                                            <?php

                                            }
                                            ?>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th scope="col" width="2%">#</th>
                                                        <th>Date</th>
                                                        <th>Company Name</th>
                                                        <!-- <th scope="col" width="10%">Image</th> -->
                                                        <th>Email</th>
                                                        <th>State</th>
                                                        <!-- <th scope="col" width="10%">Address</th> -->
                                                        <th>GST Number</th>

                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark">
                                                    <?php
                                                    if (isset($companyData) && $companyData != '') {
                                                        foreach ($companyData as  $key => $value) {
                                                            $date = $value->created_at;
                                                            $newDate = date("d-m-Y", strtotime($date));
                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <span><?= $key + 1; ?></span>
                                                                </td>
                                                                <td><?= $newDate; ?></td>
                                                                <td>
                                                                    <a href="<?= getCrmUrl() . "company/view/" . $value->id ?>" class="nav-link"><?= $value->company_name ?></a>

                                                                </td>
                                                                <td><?= $value->email ?></td>
                                                                <td><?= get_title('state_list', ['id' => $value->state], 'state') ?></td>
                                                                <!-- <td><?= $value->address ?></td> -->
                                                                <td><?= $value->gst_no ?></td>


                                                                <td class="text-right">
                                                                    <div class="dropdown">
                                                                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                            <a href="#" class="dropdown-item cp text-primary">View</a>
                                                                            <a onclick="companyEdit(<?= $value->id ?>)" class="dropdown-item cp text-warning">Edit</a>
                                                                            <!-- <a href="<?= base_url('inventory_billing/delete/') . $value->id ?>" class="dropdown-item cp text-danger">Delete</a> -->


                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                    <?php }
                                                    }

                                                    ?>


                                                    <?php
                                                    $assigned = [];
                                                    $company_result = $this->user_model->getResult('crm_assign');
                                                    if ($company_result) {
                                                        foreach ($company_result as $compassign_result) {
                                                            // company assign list

                                                            $assign_users = $compassign_result->assign_user;
                                                            if ($assign_users) {

                                                                $all_users_assign_comp = json_decode($assign_users);

                                                                foreach ($all_users_assign_comp as $key => $users_of_assigning) {
                                                                    if ($users_of_assigning == $this->session->userdata('user_id')) {
                                                                        $assigned[] = $compassign_result->comp_id;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }

                                                    if (isset($assigned) && $assigned != '') {
                                                        foreach ($assigned as  $key => $value) {
                                                            $another_comp_details = $this->user_model->getRow('crm_company', ['id' => $value]);
                                                            if ($another_comp_details) {
                                                                $date = $another_comp_details->created_at;
                                                                $newDate = date("d-m-Y", strtotime($date));
                                                    ?>
                                                                <tr>
                                                                    <td>
                                                                        <span><?= $key + 1; ?></span>
                                                                    </td>
                                                                    <td><?= $newDate; ?></td>
                                                                    <td>
                                                                        <a href="<?= base_url('crm/company/viewothercomp/') . $another_comp_details->id ?>" class="nav-link"><?= $another_comp_details->company_name ?></a>

                                                                    </td>
                                                                    <td><?= $another_comp_details->email ?></td>
                                                                    <td><?= get_title('state_list', ['id' => $another_comp_details->state], 'state') ?></td>
                                                                    <!-- <td><?= $value->address ?></td> -->
                                                                    <td><?= $another_comp_details->gst_no ?></td>


                                                                    <td class="text-right">
                                                                        <div class="dropdown">
                                                                            <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                            <a href="#" class="dropdown-item cp text-primary">View</a>
                                                                            <a onclick="companyEdit(<?= $another_comp_details->id ?>)" class="dropdown-item cp text-warning">Edit</a>
                                                                                <!-- <a href="<?= base_url('inventory_billing/viewable/') . $another_comp_details->id ?>" class="dropdown-item cp text-primary">View</a>
																				<a href="<?= base_url('inventory_billing/edit/') . $another_comp_details->id ?>" class="dropdown-item cp text-warning">Edit</a>
																				<a href="<?= base_url('inventory_billing/delete/') . $another_comp_details->id ?>" class="dropdown-item cp text-danger">Delete</a> -->


                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                    <?php }
                                                        }
                                                    }

                                                    ?>



                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 1000px;
        }
    }
</style>
<div class="modal" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog" style="width:1000px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabels">Update Company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" onclick="close()">×</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>crm/assets/js/jquery-1.11.3.min.js"></script>

<script>
    $(document).ready(function() {

        function close() {
            window.location.reload();
        }

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
                        $("#message").addClass('text-success');
                        $('message').removeClass('text-danger');
                        $("#message").html(response.message);
                        setTimeout(function() {
                            window.location.href = "<?= getCrmUrl() ?>company";
                        }, 1000);
                    } else {
                        $("#message").addClass('text-danger');
                        $('message').removeClass('text-success');
                        $("#message").html(response.message);

                    }


                }
            });
        })
    })


    function companyEdit(productId) {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load("<?= base_url() ?>crm/company/edit/" + productId);
        document.getElementById('exampleModals').style.display = 'block';


    }
</script>