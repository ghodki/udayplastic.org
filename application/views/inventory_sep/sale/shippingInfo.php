<div class="modal-header">
        <h5 class="modal-title" id="shippingInformation">Shipping Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
        </button>
</div>
<div class="modal-body">
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingName">Name</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                               <input type="text" name="shippingName" id="shippingName" class="form-control" placeholder="Name" />
                       </div>
                </div>
        </div>
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingEmail">Email</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                               <input type="email" name="shippingEmail" id="shippingEmail" class="form-control" placeholder="Email" />
                       </div>
                </div>
        </div>
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingMobile">Mobile</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                               <input type="number" name="shippingMobile" id="shippingMobile" class="form-control" placeholder="Mobile" oninput="this.value = this.value.slice(0, 10);" />
                       </div>
                </div>
        </div>
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingGSTType">GST Type</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                                <select name="shippingGSTType" id="shippingGSTType" class="form-control">
                                        <option value="">GST Type</option>
                                        <option value="Regular">Regular</option>
                                        <option value="Composite">Composite</option>
                                        <option value="N/A">N/A</option>
                                </select>
                       </div>
                </div>
        </div>    
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingGST">GST</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                               <input type="text" name="shippingGST" id="shippingGST" class="form-control" placeholder="GST" oninput="this.value = this.value.slice(0, 15);" />
                       </div>
                </div>
        </div>
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingState">State</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                               <select name="shippingState" id="shippingState" class="form-control">
                                       <option value="">Select State</option>
                                       <?php $stateList = $this->user_model->getResult('state_list'); if ($stateList) { foreach ($stateList as $stateRow) { ?>
                                       <option value="<?= $stateRow->id ?>"><?= $stateRow->state; ?>(<?= $stateRow->code; ?>)</option>
                                       <?php } } ?>
                               </select>
                       </div>
                </div>
        </div>
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingAddress">Shipping Address</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                                <textarea name="shippingAddress" id="shippingAddress" class="form-control" placeholder="Shipping Address"></textarea>
                       </div>
                </div>
        </div>    
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingCity">City</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                               <input type="text" name="shippingCity" id="shippingCity" class="form-control" placeholder="City" />
                       </div>
                </div>
        </div>
        <div class="row">
                <div class="col-md-4">
                        <label for="shippingZip">Zip</label>
                </div>
                <div class="col-md-8">
                       <div class="form-group">
                               <input type="number" name="shippingZip" id="shippingZip" class="form-control" placeholder="Zip" oninput="this.value = this.value.slice(0, 6);" />
                       </div>
                </div>
        </div>
</div>
<div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" onclick="setShippingInfo();">Save</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">Close</button>
</div>

<script type="text/javascript">
        const setShippingInfo = () => {
                var shippingName = $("#shippingName").val();
                var shippingEmail = $("#shippingEmail").val();
                var shippingMobile = $("#shippingMobile").val();
                var shippingGSTType = $("#shippingGSTType").val();
                var shippingGST = $("#shippingGST").val();
                var shippingState = $("#shippingState").val();
                var shippingAddress = $("#shippingAddress").val();
                var shippingCity = $("#shippingCity").val();
                var shippingZip = $("#shippingZip").val();
                
                $("#shippingNameHidden").val(shippingName);
                $("#shippingEmailHidden").val(shippingEmail);
                $("#shippingMobileHidden").val(shippingMobile);
                $("#shippingGSTTypeHidden").val(shippingGSTType);
                $("#shippingGSTHidden").val(shippingGST);
                $("#shippingStateHidden").val(shippingState);
                $("#shippingAddressHidden").val(shippingAddress);
                $("#shippingCityHidden").val(shippingCity);
                $("#shippingZipHidden").val(shippingZip);
                
                toastr.success("Shipping info set successfully.");
                $("#shippingDetails").modal("hide");
        };
</script>