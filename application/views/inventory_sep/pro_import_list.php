<style>
    .form-control {
        border: none;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">

        <div class="container-fluid" style="margin-top:3.5rem!important;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">PRODUCTS IMPORT LIST</div>
                    <div class="card-btn" style="float:right;">
                        <button class="btn btn-info" type="submit">
                            SAVE
                        </button>
                    </div>

                    <?php
                    if ($this->session->flashdata('msg')) { ?>
                        <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                            <?php echo $this->session->flashdata('msg'); ?>
                        </div>
                    <?php } ?>

                </div>

                <div class="card-body card" style="width:140%;">



                    <div class="row">
                        <div class="col-sm-12">

                            <table id="product-table" class="table table-reponsive-xl table-responsive-lg table-responsive-md table-bordered dataTable dtr-column text-center" cellspacing="0" aria-describedby="product-table_info" role="grid">
                                <thead>
                                    <tr role="row">
                                        <!-- <th rowspan="1" colspan="1" aria-label=""></th> -->
                                        <th>Sr No</th>
                                        <th>Product Code</th>
                                        <th style="width:200px;">Product Name</th>
                                        <th>HSN</th>
                                        <th style="width:97px;">TAX</th>
                                        <th style="width:120px;">UNIT</th>
                                        <!-- <th>Selling Price</th> -->
                                        <!-- <th>Qty</th>
                                        <th>MRP</th>
                                        <th>Discount</th>
                                        <th>Min Qty</th>
                                        <th>Short Des</th>
                                        <th>Long Des</th>
                                        <th>Weight</th> -->
                                        <th>..</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    <form>
                                        <?php
                                        // echo '<pre>';
                                        // print_r($unknown);
                                        // die;
                                        if (isset($unknown) && !empty($unknown)) {
                                            foreach ($unknown as  $key => $value) {

                                        ?>

                                                <tr>

                                                    <!-- <td class="cheking">
													<input class="checkdata" type="checkbox" name="product_id[]" value="<?php echo $value->id; ?>" ?>

													<span><?= $key + 1; ?></span>
												</td>  -->
                                                    <td><?= $key + 1; ?></td>
                                                    <td><input type="text" class="form-control" value="<?= $value['qrcode'] ?>" id="product_code<?= $key  ?>" name="products[<?= $key ?>]['product_code]"><span class="text-danger"></td>
                                                    <td><input type="text" class="form-control" value="<?= $value['hsn'] ?>" id="product_name<?= $key  ?>" name="products[<?= $key ?>]['product_name']"></td>
                                                    <td><input type="text" class="form-control" value="<?= $value['hsn'] ?>" id="hsn<?= $key  ?>" name="products[<?= $key ?>]['hsn']"></td>
                                                    <td>
                                                        <select class="form-control" name="products[<?= $key ?>]['tax']" id="tax<?= $key ?>">
                                                            <option value=""></option>
                                                            <?php
                                                            $alltax = $this->user_model->getResult('tax');
                                                            if ($alltax) {
                                                                foreach ($alltax as $taxes) {
                                                            ?>
                                                                    <!-- <option value="<?= $taxes->id ?>"></option> -->
                                                                    <option value="<?= $taxes->id ?>" <?= $value['tax'] == $taxes->tax_per ? 'selected' : ''; ?>><?= $taxes->name; ?></option>


                                                            <?php
                                                                }
                                                            }
                                                            ?>

                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="products[<?= $key ?>]['unit']" id="unit<?= $key ?>">
                                                            <option value=""></option>
                                                            <?php
                                                            $allunit = $this->user_model->getResult('unit');
                                                            if ($allunit) {
                                                                foreach ($allunit as $units) {
                                                            ?>
                                                                    <!-- <option value="<?= $taxes->id ?>"></option> -->
                                                                    <option value="<?= $units->id ?>" <?= $value['unit'] == $units->name ? 'selected' : ''; ?>><?= $units->name; ?></option>


                                                            <?php
                                                                }
                                                            }
                                                            ?>

                                                        </select>
                                                    </td>
                                                    <!-- <a href="<?= base_url('inventory_billing/viewable/') . $value->id ?>"> <i class="fa fa-eye" aria-hidden="true"></i> </a>| -->
                                                    <!-- <a href="#" onclick="productEdit(<?= $value->id ?>)" class="btn text-warning"> <i class="fa fa-edit" aria-hidden="true" style="font-size: x-large;"></i></a>
													<a href="#" onclick="productStock(<?= $value->id ?>)" class="btn text-warning"> <i class="fa fa-shopping-basket blue-text" aria-hidden="true" style="font-size: x-large;"></i></a> -->

                                                    <!-- <a href="#" data-target="#update_product<?= $value->id ?>" data-toggle="modal" class="btn text-warning"> <i class="fa fa-edit" aria-hidden="true" style="font-size: x-large;"></i></a> -->

                                                    <!-- <a href="<?= base_url('product/store/') . $value->id ?>" class="text-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a> -->
                                                    <td>
                                                    <a href="<?= base_url('product/delete_from_excel_data/') . $key ?>" class="text-danger"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                                      
                                                    </td>


                                                </tr>

                                        <?php }
                                        }

                                        ?>




                                </tbody>
                                </form>
                            </table>

                        </div>
                    </div>
                </div>


            </div>

        </div>

    </div>
</div>