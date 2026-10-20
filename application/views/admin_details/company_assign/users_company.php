<style>
    #progress {
        position: relative;
        width: 400px;
        border: 1px solid #ddd;
        padding: 1px;
        border-radius: 3px;
    }

    #bar {
        background-color: #B4F5B4;
        width: 0%;
        height: 10px;
        border-radius: 3px;
        padding-bottom: 10px;
    }

    #percent {
        position: absolute;
        top: 3px;
        left: 48%;
    }

    .form-control {
        border: none;
        border-bottom: 1px solid black;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">

        <div class="container-fluid" style="margin-top:3.5rem!important;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Company Assign</div>
                    <?php
                    if ($this->session->flashdata('msg')) { ?>
                        <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                            <?php echo $this->session->flashdata('msg'); ?>
                        </div>
                    <?php } ?>
                    <div class="float-right">
                        <button class="btn btn-outline-success" title="Change Sales Tax" id="change-sales-tax"><i class="fa fa-money" aria-hidden="true"></i></button>
                        <button class="btn btn-outline-danger" title="Delete" id="delete-product"><i class="fa fa-trash fa-lg"></i></button>
                        <button class="btn btn-outline-info" title="Upload" id="upload-product" data-toggle="modal" data-target="#importModal"><i class="fa fa-upload fa-lg"></i></button>
                        <button class="btn btn-outline-info" title="Export" id="export-button"><i class="fa fa-export fa-lg"></i></button>
                        <button class="btn btn-outline-primary" title="Copy" id="copy-product"><i class="fa fa-clone fa-lg"></i></button>
                        <button class="btn btn-outline-dark" title="Barcode" id="print-barcode"><i class="fa fa-barcode fa-lg"></i></button>
                        <button class="btn btn-outline-success" title="Add" data-toggle="modal" data-target="#add-product"><i class="fa fa-plus fa-lg"></i></button>
                        <!-- IMport FIle -->



                        <!-- IMport FIle  END-->

                        <!-- Add Product -->
                        <div class="modal fade" id="add-product" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Card stats -->
                                        <div class="row">
                                            <div class="" id="productMsg" style="opacity: 500; display: none;"></div>


                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal fade" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabels"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="productStock" tabindex="-1" role="dialog" aria-labelledby="productStockLabels" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="productStockLabels"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Product END-->
                        <!-- UPDATE PRODUCT -->



                    </div>
                </div>

                <div class="card-body card">
                    <form action="<?= base_url() ?>comp_assign/comp_assign" id="reportForm" method="get">
                        <div class="row">
                            <div class="form-group col-md-2 col-sm-6">
                                <label for="comp_name">Comapny Name</label>
                                <input type="text" tabindex="2" name="comp_name" tabindex="999" id="comp_name" value="<?= $this->input->get('comp_name') ? $this->input->get('comp_name') : '' ?>" class="form-control" />

                            </div>
                            <div class="form-group col-md-2 col-sm-6">
                                <label for="gst">GST No</label>
                                <input type="text" tabindex="2" name="gst" tabindex="1000" id="gst" value="<?= $this->input->get('gst') ? $this->input->get('gst') : '' ?>" class="form-control" />

                            </div>
                            <div class="form-group col-md-2 col-sm-6">
                                <label for="number">Mobile Number</label>
                                <input type="text" tabindex="2" name="number" tabindex="1001" id="number" value="<?= $this->input->get('number') ? $this->input->get('number') : '' ?>" class="form-control" />
                            </div>



                            <div class="form-group col-md-2 col-sm-6" style="margin-top:31px">

                                <button type="submit" class="btn btn-primary"> Search</button> &nbsp
                                <i class="fa fa-refresh" title="Refresh" id="resetbtn" aria-hidden="true"></i>

                            </div>



                        </div>
                    </form>

                    <!-- <div class="row">
                        <div class="col-md-6">
                            <?php if ($totalCount) { ?>
                                <p>Showing <?= $start ? $start : 1; ?> to <?= $end ?> of <?= $totalCount ?> entries</p>
                            <?php } else { ?>
                                <p>No result found.</p>
                            <?php } ?>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-5">
                            <?= $this->pagination->create_links(); ?>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-sm-12">

                            <table id="product-table" class="table table-bordered table-hover table-striped dataTable dtr-column text-center" cellspacing="0" width="100%" aria-describedby="product-table_info" role="grid">
                                <thead>
                                    <tr role="row">
                                        <!-- <th rowspan="1" colspan="1" aria-label=""><input type="checkbox" id="product-selectAll"><label for="product-selectAll"></label></th> -->
                                        <th>Sr No</th>
                                        <th>Company Name</th>
                                        <th>USER</th>
                                        <th>State</th>
                                        <th>GST</th>
                                        <th>Number</th>
                                        <th>...</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    if (isset($company_assign) && !empty($company_assign)) {
                                        foreach ($company_assign as  $key => $value) {
                                            $date = $value->created_at;
                                            $newDate = date("d-m-Y", strtotime($date));
                                    ?>

                                            <tr>
                                                <td><?= $key + 1; ?></td>
                                                <td><?= $value->company_name ?></td>
                                                <td><a href="<?= base_url() ?>company_assign/<?= $value->user_id ?>"><?= get_title('users', ['id' => $value->user_id], 'name') ?></a></td>
                                                <td><?= get_title('state_list', ['id' => $value->state], 'state') ?></td>
                                                <td><?= $value->gst_no ?></td>
                                                <td><?= $value->number ?></td>
                                                <td>
                                                    <a href="<?= base_url('inventory_billing/viewable/') . $value->id ?>"> <i class="fa fa-eye" aria-hidden="true"></i> </a>
                                                    <a href="#" onclick="productEdit(<?= $value->id ?>)" class="btn text-warning"> <i class="fa fa-edit" aria-hidden="true"></i></a>
                                                    <a href="#" onclick="productStock(<?= $value->id ?>)" class="btn text-warning"> <i class="fa fa-shopping-basket blue-text" aria-hidden="true"></i></a>
                                                    <!-- <a href="#" data-target="#update_product<?= $value->id ?>" data-toggle="modal" class="btn text-warning"> <i class="fa fa-edit" aria-hidden="true"></i></a> -->
                                                    <a href="<?= base_url('product/store/') . $value->id ?>" class="text-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a>


                                                </td>
                                            </tr>

                                    <?php }
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
<?php
include_once('script.php');
?>
</div>