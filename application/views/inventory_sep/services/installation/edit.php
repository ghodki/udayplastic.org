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
                    <form onsubmit="onInstallationSubmitFormHendler(event);" id="submitForm" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="installationId" value="<?= $installationData->id; ?>" id="installationId" />
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="title text-danger">
                                        Edit
                                    </h3>
                                </div>
                                <a href="<?= base_url(); ?>services/installation" class="btn bg-danger btn-sm pull-right" title="Back">
                                    <i class="fa fa-angle-double-left"></i> Back
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="date" value="<?= $installationData->date; ?>" id="date" class="form-control form-control-sm" title="Date" required="" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="billNumber">Bill Number <span class="text-danger">*</span></label>
                                            <input type="text" name="billNumber" value="<?= $installationData->billNumber; ?>" id="billNumber" class="form-control form-control-sm text-danger" placeholder="Bill Number" required="" autocomplete="off" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="manualReportNo">Manual Report Number</label>
                                            <input type="text" name="manualReportNo" value="<?= $installationData->manualReportNo; ?>" id="manualReportNo" class="form-control form-control-sm" placeholder="Manual Report Number" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="warrantyDuration">Select Warranty Duration</label>
                                            <select name="warrantyDuration" id="warrantyDuration" class="form-control form-control-sm" title="Select Warranty Duration">
                                                <option value="" <?= $installationData->warrantyDuration == "" ? "selected" : ""; ?> disabled="">Select Warranty Duration</option>
                                                <option value="1 Year" <?= $installationData->warrantyDuration == "1 Year" ? "selected" : ""; ?>>1 Year</option>
                                                <option value="2 Year" <?= $installationData->warrantyDuration == "2 Year" ? "selected" : ""; ?>>2 Year</option>
                                                <option value="3 Year" <?= $installationData->warrantyDuration == "3 Year" ? "selected" : ""; ?>>3 Year</option>
                                                <option value="4 Year" <?= $installationData->warrantyDuration == "4 Year" ? "selected" : ""; ?>>4 Year</option>
                                                <option value="5 Year" <?= $installationData->warrantyDuration == "5 Year" ? "selected" : ""; ?>>5 Year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="installationDate">Installation Date</label>
                                            <input type="date" name="installationDate" value="<?= $installationData->installationDate; ?>" id="installationDate" class="form-control form-control-sm" title="Installation Date" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="categoryId">Select Category</label>
                                            <select name="categoryId" id="categoryId" class="form-control form-control-sm" title="Select Category" onchange="getTrainingByCategory(this.value);">
                                                <option value="" <?= $installationData->categoryId == "" ? "selected" : ""; ?> disabled="">Select Category</option>
                                                <?php if (!empty($categories)) { foreach($categories as $key => $category) { ?>
                                                <option value="<?= $category->id; ?>" <?= $installationData->categoryId == $category->id ? "selected" : ""; ?>><?= $category->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="brandId">Select Brand</label>
                                            <select name="brandId" id="brandId" class="form-control form-control-sm" title="Select Brand">
                                                <option value="" <?= $installationData->brandId == "" ? "selected" : ""; ?> disabled="">Select Brand</option>
                                                <?php if (!empty($brands)) { foreach($brands as $key => $brand) { ?>
                                                <option value="<?= $brand->id; ?>" <?= $installationData->brandId == $brand->id ? "selected" : ""; ?>><?= $brand->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="productModelName">Product/Model Name</label>
                                            <input type="text" name="productModelName" value="<?= $installationData->productModelName; ?>" id="productModelName" class="form-control form-control-sm" placeholder="Product/Model Name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="machineSerialNo">Machine Serial Number</label>
                                            <input type="text" name="machineSerialNo" value="<?= $installationData->machineSerialNo; ?>" id="machineSerialNo" class="form-control form-control-sm" placeholder="Machine Serial Number" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="engineerCodeName">Engineer Code/Name</label>
                                            <input type="text" name="engineerCodeName" value="<?= $installationData->engineerCodeName; ?>" id="engineerCodeName" class="form-control form-control-sm" placeholder="Engineer Code/Name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="meterReading">Meter Reading (If Applicable)</label>
                                            <input type="number" name="meterReading" value="<?= $installationData->meterReading; ?>" id="meterReading" class="form-control form-control-sm" placeholder="Meter Reading (If Applicable)" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                Installation location: weather product installed at billing address?
                                            </label>
                                            <br />
                                            <input type="radio" name="installationLocation" value="Yes" id="installationLocationYes" title="Yes" <?= $installationData->installationLocation == "Yes" ? "checked" : ""; ?> />
                                            <label for="installationLocationYes">Yes</label>
                                            <input type="radio" name="installationLocation" value="No" id="installationLocationNo" class="ml-5" title="No" <?= $installationData->installationLocation == "No" ? "checked" : ""; ?> />
                                            <label for="installationLocationNo">No</label>
                                        </div>
                                    </div>
                                    <div class="installationLocationNo">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="consignName">Consign Name</label>
                                                    <input type="text" name="consignName" value="<?= $installationData->consignName; ?>" id="consignName" class="form-control form-control-sm" placeholder="Consign Name" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="designation">Designation</label>
                                                    <input type="text" name="designation" value="<?= $installationData->designation; ?>" id="designation" class="form-control form-control-sm" placeholder="Designation" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="installationAddress">Installation Address</label>
                                                    <textarea name="installationAddress" id="installationAddress" class="form-control form-control-sm" placeholder="Installation Address" autocomplete="off" rows="2"><?= $installationData->installationAddress; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contactPerson">Contact Person</label>
                                                    <input type="text" name="contactPerson" value="<?= $installationData->contactPerson; ?>" id="contactPerson" class="form-control form-control-sm" placeholder="Contact Person" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contactNumber">Contact Number</label>
                                                    <input type="number" name="contactNumber" value="<?= $installationData->contactNumber; ?>" id="contactNumber" class="form-control form-control-sm" placeholder="contact Number" autocomplete="off" oninput="this.value = this.value.slice(0, 10);" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="keyOperator">Key Operator</label>
                                                    <input type="text" name="keyOperator" value="<?= $installationData->keyOperator; ?>" id="keyOperator" class="form-control form-control-sm" placeholder="Key Operator" autocomplete="off" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerId">Select Customer Name <span class="text-danger">*</span></label>
                                            <select name="customerId" id="customerId" class="form-control form-control-sm" title="Select Customer Name" onchange="getCustomerInfoById(this.value);" required="">
                                                <option value="" <?= $installationData->keyOperator == "" ? "selected" : ""; ?> disabled="">Select Customer Name</option>
                                                <?php if (!empty($parties)) { foreach($parties as $key => $party) { ?>
                                                <option value="<?= $party->id; ?>" <?= $installationData->keyOperator == $party->id ? "selected" : ""; ?>><?= $party->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <input type="hidden" name="customerName" value="<?= $installationData->customerName; ?>" id="customerName" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerContactNo">Contact Number <span class="text-danger">*</span></label>
                                            <input type="number" name="customerContactNo" value="<?= $installationData->customerContactNo; ?>" id="customerContactNo" class="form-control form-control-sm" placeholder="Contact Number" autocomplete="off" required="" oninput="this.value = this.value.slice(0, 10);" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customerAddress">Address</label>
                                            <textarea name="customerAddress" id="customerAddress" class="form-control form-control-sm" placeholder="Address" autocomplete="off" rows="2"><?= $installationData->customerAddress; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerEmail">Email</label>
                                            <input type="text" name="customerEmail" value="<?= $installationData->customerEmail; ?>" id="customerEmail" class="form-control form-control-sm" placeholder="Email" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerKeyOperator">Key Operator</label>
                                            <input type="text" name="customerKeyOperator" value="<?= $installationData->customerKeyOperator; ?>" id="customerKeyOperator" class="form-control form-control-sm" placeholder="Key Operator" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customerContactPerson">Contact Person</label>
                                            <input type="text" name="customerContactPerson" value="<?= $installationData->customerContactPerson; ?>" id="customerContactPerson" class="form-control form-control-sm" placeholder="Contact Person" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" style="margin-top: 31px;">
                                            <input type="radio" name="type" value="Metro" id="typeMetro" title="Metro" <?= $installationData->type == "Metro" ? "checked" : ""; ?> />
                                            <label for="typeMetro">Metro</label>
                                            <input type="radio" name="type" value="Remote" id="typeRemote" class="ml-5" title="Remote" <?= $installationData->type == "Remote" ? "checked" : ""; ?> />
                                            <label for="typeRemote">Remote</label>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Place Of Installation Details.</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="installCustomerName">Customer Name</label>
                                            <input type="text" name="installCustomerName" value="<?= $installationData->installCustomerName; ?>" id="installCustomerName" class="form-control form-control-sm" placeholder="Customer Name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="installContactNo">Contact Number</label>
                                            <input type="number" name="installContactNo" value="<?= $installationData->installContactNo; ?>" id="installContactNo" class="form-control form-control-sm" placeholder="Contact Number" autocomplete="off" oninput="this.value = this.value.slice(0, 10);" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="placeOfInstallation">Place of Installation</label>
                                            <textarea name="placeOfInstallation" id="placeOfInstallation" class="form-control form-control-sm" placeholder="Place of Installation" autocomplete="off" rows="2"><?= $installationData->placeOfInstallation; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="installEmail">Email</label>
                                            <input type="text" name="installEmail" value="<?= $installationData->installEmail; ?>" id="installEmail" class="form-control form-control-sm" placeholder="Email" autocomplete="off" />
                                        </div>
                                    </div>                        
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telephoneNoOne">Telephone Number One</label>
                                            <input type="number" name="telephoneNoOne" value="<?= $installationData->telephoneNoOne; ?>" id="telephoneNoOne" class="form-control form-control-sm" placeholder="Telephone Number One" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telephoneNoTwo">Telephone Number Two</label>
                                            <input type="number" name="telephoneNoTwo" value="<?= $installationData->telephoneNoTwo; ?>" id="telephoneNoTwo" class="form-control form-control-sm" placeholder="Telephone Number Two" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="faxMobileNo">Fax/Mobile Number</label>
                                            <input type="number" name="faxMobileNo" value="<?= $installationData->faxMobileNo; ?>" id="faxMobileNo" class="form-control form-control-sm" placeholder="Fax/Mobile Number" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="businessStartTime">Business Hrs From Start Time</label>
                                            <input type="time" name="businessStartTime" value="<?= $installationData->businessStartTime; ?>" id="businessStartTime" class="form-control form-control-sm" title="Business Start Time" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="businessEndTime">Business Hrs To End Time</label>
                                            <input type="time" name="businessEndTime" value="<?= $installationData->businessEndTime; ?>" id="businessEndTime" class="form-control form-control-sm" title="Business End Time" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="weeklyOff">Select Weekly Off</label>
                                            <select name="weeklyOff" id="weeklyOff" class="form-control form-control-sm" title="Select Weekly Off">
                                                <option value="" <?= $installationData->weeklyOff == "" ? "selected" : ""; ?> disabled="">Select Weekly Off</option>
                                                <option value="Sunday" <?= $installationData->weeklyOff == "Sunday" ? "selected" : ""; ?>>Sunday</option>
                                                <option value="Monday" <?= $installationData->weeklyOff == "Monday" ? "selected" : ""; ?>>Monday</option>
                                                <option value="Tuesday" <?= $installationData->weeklyOff == "Tuesday" ? "selected" : ""; ?>>Tuesday</option>
                                                <option value="Wednesday" <?= $installationData->weeklyOff == "Wednesday" ? "selected" : ""; ?>>Wednesday</option>
                                                <option value="Thursday" <?= $installationData->weeklyOff == "Thursday" ? "selected" : ""; ?>>Thursday</option>
                                                <option value="Friday" <?= $installationData->weeklyOff == "Friday" ? "selected" : ""; ?>>Friday</option>
                                                <option value="Saturday" <?= $installationData->weeklyOff == "Saturday" ? "selected" : ""; ?>>Saturday</option>	  
                                            </select>
                                        </div>
                                    </div>                        
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Training</label>
                                    </div>                        
                                    <div class="col-md-12">
                                        <div class="form-group" id="training"></div>
                                    </div>                        
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Add Accessories Installation</label>
                                    </div>
                                </div>

                                <?php
                                    if (isset($installationProducts) && !empty($installationProducts)) {
                                        $sr = 0;
                                        foreach ($installationProducts as $key => $productData) {
                                            $sr++;
                                ?>
                                <div class="row" id="row<?= $sr; ?>">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="productId">Select Product Name</label>
                                            <select name="productId[]" id="productId<?= $sr; ?>" class="form-control form-control-sm productId" title="Select Product Name" onchange="getProductInfoById(this.value, <?= $sr; ?>);">
                                                <option value="" <?= $productData->productId == "" ? "selected" : ""; ?> disabled="">Select Product Name</option>
                                                <?php if (!empty($products)) { foreach($products as $key => $product) { ?>
                                                <option value="<?= $product->id; ?>" <?= $productData->productId == $product->id ? "selected" : ""; ?>><?= $product->p_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <input type="hidden" name="productName[]" value="<?= $productData->productName; ?>" id="productName<?= $sr; ?>" />
                                            <input type="hidden" name="productCode[]" value="<?= $productData->productCode; ?>" id="productCode<?= $sr; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description[]" id="description<?= $sr; ?>" class="form-control form-control-sm" placeholder="Description" autocomplete="off" rows="2"><?= $productData->productDescription; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="serialNo">Serial Number</label>
                                            <input type="number" name="serialNo[]" value="<?= $productData->serialNumber; ?>" id="serialNo<?= $sr; ?>" class="form-control form-control-sm" placeholder="Serial Number" autocomplete="off" />
                                        </div>
                                    </div>                        
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger btn-sm btnRemove" id="<?= $sr; ?>" style="margin-top: 31px;">
                                                <i class="fa fa-minus-circle text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php } } ?>
                                
                                <?php $newSr = $sr + 1; ?>
                                <div class="row" id="row<?= $newSr; ?>">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="productId">Select Product Name</label>
                                            <select name="productId[]" id="productId<?= $newSr; ?>" class="form-control form-control-sm" title="Select Product Name" onchange="getProductInfoById(this.value, <?= $newSr; ?>);">
                                                <option value="" disabled="" selected="">Select Product Name</option>
                                                <?php if (!empty($products)) { foreach($products as $key => $product) { ?>
                                                <option value="<?= $product->id; ?>"><?= $product->p_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <input type="hidden" name="productName[]" id="productName<?= $newSr; ?>" />
                                            <input type="hidden" name="productCode[]" id="productCode<?= $newSr; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description[]" id="description<?= $newSr; ?>" class="form-control form-control-sm" placeholder="description" autocomplete="off" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="serialNo">Serial Number</label>
                                            <input type="number" name="serialNo[]" id="serialNo<?= $newSr; ?>" class="form-control form-control-sm" placeholder="Serial Number" autocomplete="off" />
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

                                <hr />
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="docs">Upload Doc File</label>
                                            <input type="file" name="docs" id="docs" class="form-control form-control-sm" title="Upload Doc File" />
                                            <input type="hidden" name="oldDocs" value="<?= $installationData->docs; ?>" id="oldDocs" />
                                        </div>
                                    </div>                        
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="docs">Uploaded Doc File</label>
                                            <br />
                                            <?php if (!empty($installationData->docs)) { ?>
                                            <img src="<?= base_url(); ?>uploads/serviceDocs/<?= $installationData->docs; ?>" style="height: 50px;" alt="<?= $installationData->docs; ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>                        
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="submitButton" class="btn btn-success btn-sm">Make Changes</button>
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
        <?php if ($installationData->installationLocation === "No") { ?>
        $('.installationLocationNo').show();
        <?php } ?>
        $('#categoryId').select2();
        $('#brandId').select2();
        $('#customerId').select2();
        getTrainingByCategoryAndInstallationId();
        $('.productId').select2();
        $('#productId<?= $newSr; ?>').select2();
        
        var i = '<?= $newSr; ?>';
        $('#add').click(function() {
            i++;
            $('#moreProduct').append(
                '<div class="row" id="row'+i+'">'+
                '<div class="col-md-3">'+
                '<div class="form-group">'+
                '<label for="productId">Select Product Name</label>'+
                '<select name="productId[]" id="productId'+i+'" class="form-control form-control-sm" title="Select Product Name" onchange="getProductInfoById(this.value, '+i+');">'+
                '<option value="" disabled="" selected="">Select Product Name</option>'+
                '<?php if (!empty($products)) { foreach($products as $key => $product) { ?>'+
                '<option value="<?= $product->id; ?>"><?= $product->p_name; ?></option>'+
                '<?php } } ?>'+
                '</select>'+
                '<input type="hidden" name="productName[]" id="productName'+i+'" />'+
                '<input type="hidden" name="productCode[]" id="productCode'+i+'" />'+
                '</div>'+
                '</div>'+
                '<div class="col-md-6">'+
                '<div class="form-group">'+
                '<label for="description">Description</label>'+
                '<textarea name="description[]" id="description'+i+'" class="form-control form-control-sm" placeholder="Description" autocomplete="off" rows="2"></textarea>'+
                '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                '<div class="form-group">'+
                '<label for="serialNo">Serial Number</label>'+
                '<input type="number" name="serialNo[]" id="serialNo'+i+'" class="form-control form-control-sm" placeholder="Serial Number" autocomplete="off" />'+
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
                                    $('#customerAddress').val(customerData.address_1);
                                    $('#customerEmail').val(customerData.email);
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
                                    $('#productCode' + rowId).val(productData.qrCode);
                                    $('#productDescription' + rowId).val(productData.long_de);
                                }
                            }
            });
        }
    };

    const getTrainingByCategory = (categoryId) => {
        if (categoryId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>services/getTrainingByCategory",
                data    :   {categoryId: categoryId},
                success :   function(Response) {
                                if (Response) {
                                    $('#training').html(Response);
                                }
                            }
            });
        }
    };

    const getTrainingByCategoryAndInstallationId = () => {
        var categoryId = $('#categoryId').val();
        var installationId = $('#installationId').val();
        if (categoryId && installationId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>services/getTrainingByCategoryAndInstallationId",
                data    :   {categoryId: categoryId, installationId: installationId},
                success :   function(Response) {
                                if (Response) {
                                    $('#training').html(Response);
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
        
    const onInstallationSubmitFormHendler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var date = $("#date").val();
        var customerId = $("#customerId").val();
        var customerContactNo = $("#customerContactNo").val();
        
        if (date && customerId && customerContactNo) {
            $.ajax({
                type        :   "POST",
                url         :   "<?= base_url(); ?>services/installationUpdate",
                data        :   new FormData($("#submitForm")[0]),
                contentType :   false,
                processData :   false,
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
                                            window.location.href='<?= base_url(); ?>services/installation';
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