<style type="text/css">
    .select2-container {
        display: block;
    }    
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form onsubmit="onServiceCallSubmitFormHendler(event);" id="submitForm" autocomplete="off">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="title text-danger">
                                        Create
                                    </h3>
                                </div>
                                <a href="<?= base_url(); ?>services/serviceCall" class="btn btn-danger btn-sm pull-right" title="Back">
                                    <i class="fa fa-angle-double-left"></i> Back
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="date" value="<?= date('Y-m-d'); ?>" id="date" class="form-control form-control-sm" title="Date" required="" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="billNumber">Bill Number <span class="text-danger">*</span></label>
                                            <input type="text" name="billNumber" value="<?= $billNumber; ?>" id="billNumber" class="form-control form-control-sm text-danger" placeholder="Bill Number" required="" autocomplete="off" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="installationReportNo">Installation Report Number</label>
                                            <input type="text" name="installationReportNo" id="installationReportNo" class="form-control form-control-sm" placeholder="Installation Report Number" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="manualReportNo">Manual Report Number</label>
                                            <input type="text" name="manualReportNo" id="manualReportNo" class="form-control form-control-sm" placeholder="Manual Report Number" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bill Status</label>
                                            <br />
                                            <input type="radio" name="billStatus" value="Billable" id="Billable" title="Billable" checked="" />
                                            <label for="Billable">Billable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <br />
                                            <input type="radio" name="billStatus" value="Not Billable" id="NotBillable" title="Not Billable" />
                                            <label for="NotBillable">Not Billable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <br />
                                            <input type="radio" name="billStatus" value="Estimated Cost of Repair (Taxes Extra)" id="Estimated" title="Estimated Cost of Repair(Taxes Extra)" />
                                            <label for="Estimated">Estimated Cost of Repair (Taxes Extra)</label>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerId">Select Customer Name <span class="text-danger">*</span></label>
                                            <select name="customerId" id="customerId" class="form-control form-control-sm" title="Select Customer Name" onchange="getCustomerInfoById(this.value);" required="">
                                                <option value="" disabled="" selected="">Select Customer Name</option>
                                                <?php if (!empty($parties)) { foreach($parties as $key => $party) { ?>
                                                <option value="<?= $party->id; ?>"><?= $party->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <input type="hidden" name="customerName" id="customerName" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerContactNo">Contact Number <span class="text-danger">*</span></label>
                                            <input type="number" name="customerContactNo" id="customerContactNo" class="form-control form-control-sm" placeholder="Contact Number" autocomplete="off" required="" oninput="this.value = this.value.slice(0, 10);" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerEmail">Email</label>
                                            <input type="text" name="customerEmail" id="customerEmail" class="form-control form-control-sm" placeholder="Email" autocomplete="off" />
                                        </div>
                                    </div>                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerAddress">Address</label>
                                            <textarea name="customerAddress" id="customerAddress" class="form-control form-control-sm" placeholder="Address" autocomplete="off" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="categoryId">Select Category</label>
                                            <select name="categoryId" id="categoryId" class="form-control form-control-sm" title="Select Category">
                                                <option value="" disabled="" selected="">Select Category</option>
                                                <?php if (!empty($categories)) { foreach($categories as $key => $category) { ?>
                                                <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="brandId">Select Brand</label>
                                            <select name="brandId" id="brandId" class="form-control form-control-sm" title="Select Brand">
                                                <option value="" disabled="" selected="">Select Brand</option>
                                                <?php if (!empty($brands)) { foreach($brands as $key => $brand) { ?>
                                                <option value="<?= $brand->id; ?>"><?= $brand->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="productModelName">Product/Model Name</label>
                                            <input type="text" name="productModelName" id="productModelName" class="form-control form-control-sm" placeholder="Product/Model Name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="modelNumber">Model Number</label>
                                            <input type="text" name="modelNumber" id="modelNumber" class="form-control form-control-sm" placeholder="Model Number" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="machineSerialNo">Machine Serial Number</label>
                                            <input type="text" name="machineSerialNo" id="machineSerialNo" class="form-control form-control-sm" placeholder="Machine Serial Number" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="counterReading">Counter Reading</label>
                                            <input type="text" name="counterReading" id="counterReading" class="form-control form-control-sm" placeholder="Counter Reading" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="contractStatus">Contract Status</label>
                                            <input type="text" name="contractStatus" id="contractStatus" class="form-control form-control-sm" placeholder="Contract Status" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="callType">Call Type</label>
                                            <input type="text" name="callType" id="callType" class="form-control form-control-sm" placeholder="Call Type" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="installationLocation">
                                                Installation location: weather product installed at billing address?
                                            </label>
                                            <br />
                                            <input type="radio" name="installationLocation" value="Yes" id="installationLocationYes" title="Yes" checked="" />
                                            <label>Yes</label>
                                            <input type="radio" name="installationLocation" value="No" id="installationLocationNo" class="ml-5" title="No" />
                                            <label>No</label>
                                        </div>
                                    </div>
                                    <div class="installationLocationNo">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="consignName">Consign Name</label>
                                                    <input type="text" name="consignName" id="consignName" class="form-control form-control-sm" placeholder="Consign Name" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="designation">Designation</label>
                                                    <input type="text" name="designation" id="designation" class="form-control form-control-sm" placeholder="Designation" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="installationAddress">Installation Address</label>
                                                    <textarea name="installationAddress" id="installationAddress" class="form-control form-control-sm" placeholder="Installation Address" autocomplete="off" rows="2"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contactPerson">Contact Person</label>
                                                    <input type="text" name="contactPerson" id="contactPerson" class="form-control form-control-sm" placeholder="Contact Person" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contactNumber">Contact Number</label>
                                                    <input type="number" name="contactNumber" id="contactNumber" class="form-control form-control-sm" placeholder="contact Number" autocomplete="off" oninput="this.value = this.value.slice(0, 10);" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="keyOperator">Key Operator</label>
                                                    <input type="text" name="keyOperator" id="keyOperator" class="form-control form-control-sm" placeholder="Key Operator" autocomplete="off" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="problem">Problem</label>
                                            <textarea name="problem" id="problem" class="form-control form-control-sm" placeholder="Problem" autocomplete="off" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cause">Cause</label>
                                            <textarea name="cause" id="cause" class="form-control form-control-sm" placeholder="Cause" autocomplete="off" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Counter Reading</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="blackA3">Black A3</label>
                                            <input type="text" name="blackA3" id="blackA3" class="form-control form-control-sm" placeholder="Black A3" autocomplete="off" oninput="getCounterReadingTotal();" onchange="getCounterReadingTotal();" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="blackA4">Black A4</label>
                                            <input type="text" name="blackA4" id="blackA4" class="form-control form-control-sm" placeholder="Black A4" autocomplete="off" oninput="getCounterReadingTotal();" onchange="getCounterReadingTotal();" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="colourA3">Colour A3</label>
                                            <input type="text" name="colourA3" id="colourA3" class="form-control form-control-sm" placeholder="Colour A3" autocomplete="off" oninput="getCounterReadingTotal();" onchange="getCounterReadingTotal();" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="colourA4">Colour A4</label>
                                            <input type="text" name="colourA4" id="colourA4" class="form-control form-control-sm" placeholder="Colour A4" autocomplete="off" oninput="getCounterReadingTotal();" onchange="getCounterReadingTotal();" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="totalReading">Total Reading</label>
                                            <input type="text" name="totalReading" id="totalReading" class="form-control form-control-sm" placeholder="Total Reading" autocomplete="off" readonly="" />
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="actionTaken">Action Taken</label>
                                            <textarea name="actionTaken" id="actionTaken" class="form-control form-control-sm" placeholder="Action Taken" autocomplete="off" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Machine Status</label>
                                            <br />
                                            <input type="radio" name="machineStatus" value="OK" id="machineStatus" title="OK" checked="" />
                                            <label for="machineStatus">OK</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <br />
                                            <input type="radio" name="machineStatus" value="Not OK" id="machineStatus" title="Not OK" />
                                            <label for="machineStatus">Not OK</label>
                                        </div>
                                    </div>                                    
                                </div>
                                
                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="productId">Spare Used/Reg. this call</label>
                                            <select name="productId[]" id="productId1" class="form-control form-control-sm" title="Select Product Name" onchange="getProductInfoById(this.value, 1); getCalculateTotalAmount(1);">
                                                <option value="" disabled="" selected="">Select Product Name</option>
                                                <?php if (!empty($products)) { foreach($products as $key => $product) { ?>
                                                <option value="<?= $product->id; ?>"><?= $product->p_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <input type="hidden" name="productName[]" id="productName1" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="partNumber">Part Number</label>
                                            <input type="text" name="partNumber[]" id="partNumber1" class="form-control form-control-sm" placeholder="Part Number" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" name="quantity[]" id="quantity1" class="form-control form-control-sm" placeholder="Quantity" autocomplete="off" oninput="getCalculateTotalAmount(1);" onchange="getCalculateTotalAmount(1);" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="unitPrice">Unit Price</label>
                                            <input type="number" name="unitPrice[]" id="unitPrice1" class="form-control form-control-sm" placeholder="Unit Price" autocomplete="off" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="number" name="amount[]" id="amount1" class="form-control form-control-sm" placeholder="Amount" autocomplete="off" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button type="button" id="add" class="btn btn-success btn-sm" style="margin-top: 31px;">
                                                <i class="fa fa-plus-circle text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="moreProduct"></div>
                                <input type="hidden" name="grandTotalAmount" id="grandTotalAmount" />
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="submitButton" class="btn btn-success btn-sm">Submit</button>
                                <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 </div>

<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#categoryId').select2();
        $('#brandId').select2();
        $('#customerId').select2();
        $('#productId1').select2();

        $('.installationLocationNo').hide();
        
        var i = 1;
        $('#add').click(function() {
            i++;
            $('#moreProduct').append(
                '<div class="row" id="row'+i+'">'+
                '<div class="col-md-3">'+
                '<div class="form-group">'+
                '<label for="productId">Spare Used/Reg. this call</label>'+
                '<select name="productId[]" id="productId'+i+'" class="form-control form-control-sm" title="Select Product Name" onchange="getProductInfoById(this.value, '+i+'); getCalculateTotalAmount('+i+');">'+
                '<option value="" disabled="" selected="">Select Product Name</option>'+
                '<?php if (!empty($products)) { foreach($products as $key => $product) { ?>'+
                '<option value="<?= $product->id; ?>"><?= $product->p_name; ?></option>'+
                '<?php } } ?>'+
                '</select>'+
                '<input type="hidden" name="productName[]" id="productName'+i+'" />'+
                '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                '<div class="form-group">'+
                '<label for="Part Number">Part Number</label>'+
                '<input type="text" name="partNumber[]" id="partNumber'+i+'" class="form-control form-control-sm" placeholder="Part Number" autocomplete="off" />'+
                '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                '<div class="form-group">'+
                '<label for="quantity">Quantity</label>'+
                '<input type="number" name="quantity[]" id="quantity'+i+'" class="form-control form-control-sm" placeholder="Quantity" autocomplete="off" oninput="getCalculateTotalAmount('+i+');" onchange="getCalculateTotalAmount('+i+');" />'+
                '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                '<div class="form-group">'+
                '<label for="unitPrice">Unit Price</label>'+
                '<input type="number" name="unitPrice[]" id="unitPrice'+i+'" class="form-control form-control-sm" placeholder="Unit Price" autocomplete="off" readonly="" />'+
                '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                '<div class="form-group">'+
                '<label for="amount">Amount</label>'+
                '<input type="number" name="amount[]" id="amount'+i+'" class="form-control form-control-sm" placeholder="Amount" autocomplete="off" readonly="" />'+
                '</div>'+
                '</div>'+
                '<div class="col-md-1">'+
                '<div class="form-group">'+
                '<button type="button" id="'+i+'" class="btn btn-danger btn-sm btnRemove" style="margin-top: 31px;">'+
                '<i class="fa fa-minus-circle text-white"></i>'+
                '</button>'+
                '</div>'+
                '</div>'+
                '</div>'
            );
    
            $("#productId"+i).select2();
        });
        
        $(document).on('click', '.btnRemove', function() {
            var buttonId = $(this).attr("id");   
            $('#row'+buttonId+'').remove();  
        });
    });

    const getCounterReadingTotal = () => {
        var blackA3 = parseInt($("#blackA3").val()) || 0;
        var blackA4 = parseInt($("#blackA4").val()) || 0;
        var colourA3 = parseInt($("#colourA3").val()) || 0;
        var colourA4 = parseInt($("#colourA4").val()) || 0;
        var totalReading = blackA3 + blackA4 + colourA3 + colourA4;
        $("#totalReading").val(totalReading);
    };

    var grandTotalAmount = 0;
    const getCalculateTotalAmount = (rowNo) => {
        var quantity = parseFloat($("#quantity" + rowNo).val()) || 0;
        var unitPrice = parseFloat($("#unitPrice" + rowNo).val()) || 0;
    
        var previousTotalAmount = parseFloat($("#amount" + rowNo).val()) || 0;
        var newTotalAmount = quantity * unitPrice;
        $("#amount" + rowNo).val(newTotalAmount.toFixed(2));
    
        grandTotalAmount = grandTotalAmount - previousTotalAmount + newTotalAmount;   
        $("#grandTotalAmount").val(grandTotalAmount.toFixed(2));
    };

    const getCustomerInfoById = (customerId) => {
        if (customerId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>services/getCustomerInfoById",
                data    :   {customerId: customerId},
                success :   function(response) {
                                if (response) {
                                    var customerData = JSON.parse(response);
                                    $('#customerName').val(customerData.name);
                                    $('#customerContactNo').val(customerData.contact);
                                    $('#customerEmail').val(customerData.email);
                                    $('#customerAddress').val(customerData.address_1);                                    
                                }
                            }
            });
        }
    };
    
    const getProductInfoById = (productId, rowId) => {
        if (productId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>services/getProductInfoById",
                data    :   {productId: productId},
                success :   function(response) {
                                if (response) {
                                    var productData = JSON.parse(response);
                                    $('#productName' + rowId).val(productData.p_name);
                                    $('#unitPrice' + rowId).val(productData.price);
                                }
                            }
            });
        }
    };

    $('#installationLocationYes').click(function(){
        $('.installationLocationNo').hide();
    });

    $('#installationLocationNo').click(function(){
        $('.installationLocationNo').show();
    });
        
    const onServiceCallSubmitFormHendler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var date = $("#date").val();
        var customerId = $("#customerId").val();
        var customerContactNo = $("#customerContactNo").val();
        
        if (date && customerId && customerContactNo) {
            $.ajax({
                type        :   "POST",
                url         :   "<?= base_url(); ?>services/serviceCallSave",
                data        :   $("#submitForm").serialize(),
                success     :   function(Response) {
                                    var ResponseData = JSON.parse(Response);
                                    if (ResponseData.Status === true) {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'success',
                                            title: ResponseData.Message,
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        window.setTimeout(function() {
                                            window.location.href='<?= base_url(); ?>services/serviceCall';
                                        }, 1000);
                                    } else {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'error',
                                            title: ResponseData.Message,
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        $("#submitButton").prop("disabled", false);
                                    }
                                }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#submitButton").prop("disabled", false);
        }
    };
</script>