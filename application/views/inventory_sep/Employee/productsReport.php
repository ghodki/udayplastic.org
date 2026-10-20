<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Products</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Products</a></li>
                        <li class="breadcrumb-item active">Products Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="header-title text-success">
                                Products Report
                            </h5>
                        </div>
                        <?php if (employee_perm($EmployeePermission, 'Products', 'Create')) { ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-xs pull-right" id="NewProductModal" title="Create Product">
                            <i class="fa fa-plus-circle text-white"></i> Create
                        </a>
                        <?php } ?>                        
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead class="bg-success">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Code</th>
                                            <th class="text-white">Name</th>
                                            <th class="text-white">HSN</th>
                                            <th class="text-white">Tax</th>
                                            <th class="text-white">Unit</th>
                                            <th class="text-white">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (!empty($productsData)) {
                                                $n = 0;
                                                foreach ($productsData as $key => $data) {
                                                    $n++;
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $data->qrCode; ?></td>
                                            <td><?= $data->p_name; ?></td>
                                            <td><?= $data->hsn; ?></td>
                                            <td><?= $data->tax_qty; ?></td>
                                            <td><?= $data->unit_name; ?></td>
                                            <td><?= $data->price; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

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
                    <form id="onProductFormSubmitHandler" action="<?= base_url(); ?>product/savePro" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event);">
                        <div class="row">
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="product_name">Product Name<span class="text-danger">*</span></label>
                                    <input id="product_name" name="name" tabindex="1" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('name'); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="product_code">Product Code<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" tabindex="2" required="" name="pcode" id="pcode" />
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('p_code'); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="price">Selling Price<span class="text-danger">*</span></label>
                                    <input id="price" name="price" tabindex="3" min="1" placeholder="Selling Price" type="text" class="form-control" value="<?= set_value('price') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('price'); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="cost">Purchase Price<span class="text-danger"></span></label>
                                    <input id="cost" name="sale_rate" tabindex="4" min="0" placeholder="Purchase Price" type="text" class="form-control" value="<?= set_value('sale_rate') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="mrp">MRP<span class="text-danger"></span></label>
                                    <input id="mrp" name="mrp" min="0" tabindex="5" placeholder="MRP" type="text" class="form-control" value="<?= set_value('mrp') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="qty">Product Quantity<span class="text-danger">*</span></label>
                                    <input id="qty" name="qty" min="1" tabindex="6" placeholder="Product Quantity" type="text" class="form-control" value="<?= set_value('qty') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="segement">Segment<span class="text-danger"></span></label>
                                    <input id="segement" name="segement" placeholder="Product Segment" type="text" class="form-control" value="<?= set_value('segement') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="discount">Discount%<span class="text-danger"></span></label>
                                    <input id="discount" name="discount" min="0" tabindex="7" placeholder="Discount in %" type="text" class="form-control" value="<?= set_value('discout') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="hsn">HSN CODE<span class="text-danger"></span></label>
                                    <input id="hsn_code" name="hsn_code" tabindex="8" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="min_qty">MIN QTY<span class="text-danger"></span></label>
                                    <input id="min_qty" name="min_qty" min="0" tabindex="9" placeholder="Min Qty" type="text" class="form-control" value="<?= set_value('min_qty') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="weight">Weight<span class="text-danger"></span></label>
                                    <input id="weight" name="weight" min="0" tabindex="10" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <label for="unit">Unit<span class="text-danger">*</span></label>
                                <select id="unit" name="unit" class="form-control" tabindex="11" required>
                                    <option value="">Select Unit</option>
                                    <?php
                                        $unit = $this->user_model->getResult('unit');
                                        $role = $this->session->userdata('roles');
                                        if ($role == 1) {
                                            if (isset($unit) && !empty($unit)) {
                                                foreach ($unit as $units) {
                                    ?>
                                    <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } 
                                        } else {
                                        if (isset($unit) && !empty($unit)) {
                                            foreach ($unit as $units) {
                                                $UserId = $this->session->userdata('UserId');
                                                $CompId = $this->session->userdata('CompId');
                                                $CompBranchId = $this->session->userdata('CompBranchId');
                                                
                                                $CompanyBranchWhere = ['bill_id' => $units->id, 'user_id' => $UserId, 'comp_id' => $CompId];
                                                if ($this->session->userdata('BranchRole')) {
                                                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                                                }
                                                
                                                $resu = $this->user_model->getRow('unit_status', $CompanyBranchWhere);
                                                if (isset($resu) && !empty($resu)) {
                                                    if ($resu->status == 1) {
                                    ?>
                                    <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } else { ?>
                                    <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } } } ?>
                                </select>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('unit'); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label for="SubUnit">Sub Unit</label>
                                    <select name="SubUnit" id="SubUnit" class="form-control" title="Sub Unit">
                                        <option value="" selected="" disabled="">Select Sub Unit</option>
                                        <?php
                                            $SubUnit = $this->user_model->getResult('sub_unit', ['Status' => 1]);
                                            if (isset($SubUnit) && !empty($SubUnit)) {
                                                foreach ($SubUnit as $key => $SUData) {
                                        ?>
                                        <option value="<?= $SUData->Id; ?>" <?= set_select('SubUnit', $SUData->Id); ?>><?= $SUData->SubUnitName; ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="tax">TAX<span class="text-danger">*</span></label>
                                    <select id="tax" name="tax" class="form-control" tabindex="12" required>
                                        <option value="">Select Tax</option>
                                        <?php
                                            $tax = $this->user_model->getResult('tax');
                                            if ($role == 1) {
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $units) {
                                        ?>
                                        <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                        <?php } }
                                            } else {
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $units) {
                                                        $UserId = $this->session->userdata('UserId');
                                                        $CompId = $this->session->userdata('CompId');
                                                        $CompBranchId = $this->session->userdata('CompBranchId');
                                                        
                                                        $CompanyBranchWhere = ['bill_id' => $units->id, 'user_id' => $UserId, 'comp_id' => $CompId];
                                                        if ($this->session->userdata('BranchRole')) {
                                                            $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                                                        }
                                                        
                                                        $resu = $this->user_model->getRow('tax_status', $CompanyBranchWhere);
                                                        if (isset($resu) && !empty($resu)) {
                                                            if ($resu->status == 1) {
                                        ?>
                                        <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                        <?php } } else { ?>
                                        <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                        <?php } } } } ?>
                                    </select>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error("tax"); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="OrderBook">Order Book</label>
                                    <br />
                                    <input type="radio" name="OrderBook" value="Yes" <?= set_radio('OrderBook', 'Yes'); ?> id="Yes" checked="" tabindex="14" title="Product for OrderBook Yes" />
                                    Yes
                                    <input type="radio" name="OrderBook" value="No" <?= set_radio('OrderBook', 'No'); ?> id="No" tabindex="14" title="Product for OrderBook No" class="ml-5" />
                                    No
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="packetQty">Master Packet Quantity</label>
                                    <input type="text" name="packetQty" value="<?= set_value('packetQty'); ?>" id="packetQty" class="form-control" placeholder="Master Packet Quantity" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="rewardPoint">Reward Point</label>
                                    <input type="text" name="rewardPoint" value="<?= set_value('rewardPoint'); ?>" id="rewardPoint" class="form-control" placeholder="reward Point" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="batchCode">Batch Code</label>
                                    <input type="text" name="batchCode" value="<?= set_value('batchCode'); ?>" id="batchCode" class="form-control" placeholder="Batch Code" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="dateOfManufacturing">Date Of Manufacturing</label>
                                    <input type="date" name="dateOfManufacturing" value="<?= set_value('dateOfManufacturing'); ?>" id="dateOfManufacturing" class="form-control" title="Date Of Manufacturing" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="dateOfExpiry">Date Of Expiry</label>
                                    <input type="date" name="dateOfExpiry" value="<?= set_value('dateOfExpiry'); ?>" id="dateOfExpiry" class="form-control" title="Date Of Expiry" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="shortdescription">SHORT DESC<span class="text-danger"></span></label>
                                    <textarea id="shortdescription" tabindex="13" name="short_des" placeholder="Product Short Description" class="form-control" rows="1"><?= set_value('short_des') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="description">Remark<span class="text-danger"></span></label>
                                    <textarea id="description" tabindex="14" name="long_des" placeholder="Product Long Description" class="form-control" rows="1"><?= set_value('long_des') ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 text-center">
                                <?php if (get_title('users', ['id' => $this->session->userdata('UserId')], 'userType') == 3) { if (IsLicensed()) { ?>
                                <button type="submit" class="btn btn-primary">
                                    SAVE a
                                </button>
                                <?php } else { ?>
                                <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                <?php } } else { ?>
                                <button type="submit" class="btn btn-primary">
                                    SAVE
                                </button>
                                <?php } ?>
                                <button type="reset" id="productResetBtn" class="btn btn-danger">RESET</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Product END-->

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'lBfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: [
                {
                    extend : 'copyHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'excelHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'csvHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'pdfHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'print',
                    exportOptions:  {
                        columns: []
                    }
                }
            ]
        });
    });
    
    function getProductCode() {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Product/getProductCode",
            success :   function(Response) {
                            $("#pcode").val(Response);
                        }
        });
    }    
    
    $("#NewProductModal").click(function() {
        setTimeout(function() {
            $("#add-product").modal("show");
            $("#pcode").val(getProductCode());
            $("#add-product").css("display", "block");
            $(".HideModal").css("opacity", "0.5");
            $(".HideModal").addClass("modal-backdrop");
        }, 500);
    });
    
    function onProductFormSubmitHandler(e) {
        e.preventDefault();
        var url = $("#onProductFormSubmitHandler").attr("action");
        var form = $("#onProductFormSubmitHandler")[0];
        var data = new FormData(form);
        $.ajax({
            type        :   "POST",
            enctype     :   "multipart/form-data",
            url         :   url,
            data        :   data,
            processData :   false,
            contentType :   false,
            cache       :   false,
            timeout     :   600000,
            success     :   function(customerList) {
                                customerList = JSON.parse(customerList);
                                if (customerList.status == true) {
                                    toastr.success("Product Added Successfully!", "");
                                    setTimeout(function() {
                                        $("#onProductFormSubmitHandler")[0].reset();
                                        $("#add-product").modal("hide");
                                        $(".show").css("opacity", "0");
                                        $(".show").addClass("HideModal");
                                        $(".show").removeClass("modal-backdrop");
                                        
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    $("#productMsg").show();
                                    $("#productMsg").addClass("text");
                                    $("#productMsg").addClass("text-danger");
                                    $("#productMsg").html(customerList.msg);
                                }
                            }
        });
    }    
</script>