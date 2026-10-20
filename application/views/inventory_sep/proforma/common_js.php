<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="post" action="<?= base_url('party/create') ?>" onsubmit="onUserFormSubmitHandler(event)" autocomplete="on">
                    <div class="" id="partyMsg" style="opacity: 500; display: none;"></div>
                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
                            <select name="group" id="group" class="form-control" data-live-search="true" required>
                                <option value="">-Select-</option>
                                <?php

                                $tax = $this->user_model->getResult('accounting_group');
                                $role = $this->session->userdata('roles');
                                if ($role == 1) {
                                    if (isset($tax) && !empty($tax)) {
                                        foreach ($tax as $units) {
                                ?>
                                            <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                } else {
                                    if (isset($tax) && !empty($tax)) {
                                        foreach ($tax as $units) {
                                            $taxWhere = ['user_id' => $userId, 'comp_id' => $compId, 'bill_id' => $units->id];
                                            if ($this->session->userdata('BranchRole')){
                                                $taxWhere['CompanyBranchId'] = $compBranchId;
                                            }                                            
                                            $resu = $this->user_model->getRow('accountant_status', $taxWhere);
                                            // $this->session->userdata('user_id');
                                            if (isset($resu) && !empty($resu)) {
                                                if ($resu->status == 1) {
                                            ?>
                                                    <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                                    </option>

                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                                </option>

                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="name" class="">Name <span class="text-danger">*</span></label>
                            <input required name="name" id="name" class="form-control textOnly" type="text" placeholder="Enter Name">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="contact" class="">Mobile <span class="text-danger"></span></label>
                            <input onkeyup="checkContacts()" name="contact" id="contacts" class="form-control" type="text" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" placeholder="Enter Mobile">
                            <span id="contackcheck"></span>
                            <!-- <span class="text-success" id="contackcheck1"></span> -->


                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="email" class="">Email <span class="text-danger"></span></label>
                            <input onkeyup="checkEmail()" name="email" id="emails" class="form-control " type="email" placeholder="Enter Email">
                            <span id="mailcheck"></span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="pan" class="">Pan No</label>
                            <input name="pan" id="pan" class="form-control toUpper" type="text" placeholder="Enter Pan No" maxlength="10">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="address_1" class="">Address <span class="text-danger"></span></label>
                            <input name="address_1" id="address_1" class="form-control" type="text" placeholder="Enter Address">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="control-label" for="state">City <span class="text-danger"></span></label>
                            <select name="city" id="city" class="form-control" data-live-search="true">
                                <option value="">-Select-</option>
                                <?php $states = $this->db->get('districts')->result();
                                if ($states) {
                                    foreach ($states as $row) { ?>
                                        <option value="<?= $row->id ?>" data-id="<?= $row->name ?>" data-state="<?= $row->name ?>" <?= set_value('city') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="pin" class="">Pin <span class="text-danger"></span></label>
                            <input type="text" name="pin" id="pin" placeholder="Pin Code" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="state" class="">State <span class="text-danger">*</span></label><br>
                            <select name="state" id="state" class="form-control" style="width:100%" required>
                                <option value="">Select State</option>
                                <?php $states = $this->db->get('state_list')->result();
                                if ($states) {
                                    foreach ($states as $row) { ?>
                                        <option value="<?= $row->id ?>" data-id="<?= $row->code ?>"> <?= $row->state; ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="code" class="">State Code</label><br>
                            <input type="text" readonly name="state_code" id="code" placeholder="Code" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="credit_limit" class="">Credit Limit</label><br>
                            <input type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit') ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="gstTypeParty" class="">gst Type <span class="text-danger"></span></label><br>
                            <select name="gstType" id="gstTypeParty" class="form-control">
                                <option value="">-Select-</option>
                                <option value="Regular">Regular</option>
                                <option value="Composite">Composite</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2" id="gstTypeVal">
                            <!-- <label for="gst" class="">GSTIN No <span class="text-danger">*</span></label> -->
                            <label class="control-label" for="gst">
                                <a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No
                                    &gt;&gt;</a>
                            </label>
                            <input name="gst" id="gst" class="form-control toUpper" type="text" placeholder="Enter GSTIN No">
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label class="control-label" for="opening_balance">Opening Balance</label>
                            <input autocomplete="off" type="text" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="<?= set_value('opening_balance') ?>" class="form-control">
                        </div>

                        <div class="form-group col-md-4 mb-2">
                            <label class="control-label" for="opBalnceType">Type</label>
                            <select name="opBalnceType" id="opBalnceType" class="form-control">
                                <!--<option value="">-Select-</option>-->
                                  <option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR
                                </option>
                                <option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR
                                </option>
                              
                            </select>
                        </div>

                        <div class="form-group col-md-4 mb-2" style="margin-top:30px;">
                            <label class="control-label" for="sms">Send Sms</label>
                            <input type="checkbox" name="sms" id="sms">

                        </div>
                    </div>
                    <!-- <div class="modal-footer"> -->
                    <button type="button" class="btn btn-secondary" id="custClose" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_login">Save</button>
                    <button type="reset" class="btn btn-danger" id="reset">Reset</button>

                    <!-- </div> -->
                </form>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="updateSalePayment" tabindex="-1" role="dialog" aria-labelledby="okjanu" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xlg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="okjanu">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="closePaymentModel()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="messages" class="text-center"></div>
                <form id="updatePaymentMode" class="col-sm-12 col-md-12 col-lg-12" action="<?= site_url() ?>/sale/paymentModeUpdate" method="POST" enctype="multipart/form-data">

                    <div class="row text-dark">

                        <input type="hidden" name="saleIdForUpdate" id="saleIdForUpdate" value="">
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <div class="form-group">
                                <label for="Category">Payment<span class="text-danger">*</span></label>
                                <select class="form-control select2" required name="paymentModes" id="paymentModes">
                                    <option value="">Select Payment</option>

                                    <option value="Card">Card
                                    </option>
                                    <option value="Phone Pay">Phone Pay
                                    </option>
                                    <option value="Google Pay">Google Pay
                                    </option>
                                    <option value="Paytm">Paytm
                                    </option>
                                    <option value="Cash">Cash
                                    </option>
                                    <option value="Credit">Credit
                                    </option>

                                </select>

                            </div>

                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <div class="form-group">
                                <label for="ammoouunntt">Amount<span class="text-danger">*</span></label>
                                <input id="ammoouunntt" name="ammoouunntt" placeholder="Amount" type="number" min="0" required class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closePaymentModel()" aria-label="Close">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal07" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="adpartyform" method="post" action="<?= base_url('party/updateparty') ?>" onsubmit="onFormPartySubmitHandler(event)" autocomplete="on">
                    <div class="" id="errormsg" style="opacity: 500; display: none;"></div>
                    <div class="row">
                        <!-- <div class="col-md-4 mb-2">
            <label for="type" class="">Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-control searchselect" required>
                <option value="">-Select-</option>
         
            </select>
        </div> -->
                        <div class="col-md-4 mb-2">
                            <label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
                            <select name="group" id="dontgroup" class="form-control" data-live-search="true" required>
                                <option value="">-Select-</option>
                                <?php

                                $tax = $this->user_model->getResult('accounting_group');
                                $role = $this->session->userdata('roles');
                                if ($role == 1) {
                                    if (isset($tax) && !empty($tax)) {
                                        foreach ($tax as $units) {
                                ?>
                                            <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                } else {
                                    if (isset($tax) && !empty($tax)) {

                                        foreach ($tax as $units) {
                                            $accountGroupWhere = ['user_id' => $userId, 'comp_id' => $compId, 'bill_id' => $units->id];
                                            if ($this->session->userdata('BranchRole')){
                                                $accountGroupWhere['CompanyBranchId'] = $compBranchId;
                                            }
                                            
                                            $resu = $this->user_model->getRow('accountant_status', $accountGroupWhere);
                                            // $this->session->userdata('user_id');
                                            if (isset($resu) && !empty($resu)) {
                                                if ($resu->status == 1) {
                                            ?>
                                                    <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                                    </option>

                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                                </option>

                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="name" class="">Name <span class="text-danger">*</span></label>
                            <input required name="name" id="dontnameememe" class="form-control textOnly" type="text" placeholder="Enter Name">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="contact" class="">Mobile <span class="text-danger"></span></label>
                            <input onkeyup="checkContacts()" name="number" id="dontnumber" class="form-control" type="text" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" placeholder="Enter Mobile">
                            <span id="contackcheck"></span>
                            <!-- <span class="text-success" id="contackcheck1"></span> -->


                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="email" class="">Email <span class="text-danger"></span></label>
                            <input onkeyup="checkEmail()" name="email" id="dontemail" class="form-control " type="email" placeholder="Enter Email">
                            <span id="mailcheck"></span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="pan" class="">Pan No</label>
                            <input name="pan" id="dontpan" class="form-control toUpper" type="text" placeholder="Enter Pan No" maxlength="10">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="address_1" class="">Address <span class="text-danger"></span></label>
                            <input name="address_1" id="dontaddress_1" class="form-control" type="text" placeholder="Enter Address">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="control-label" for="state">City <span class="text-danger"></span></label>
                            <select name="city" id="dontcity" class="form-control" data-live-search="true">
                                <option value="">-Select-</option>
                                <?php $states = $this->db->get('districts')->result();
                                if ($states) {
                                    foreach ($states as $row) { ?>
                                        <option value="<?= $row->id ?>" data-id="<?= $row->name ?>" data-state="<?= $row->name ?>" <?= set_value('city') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="pin" class="">Pin <span class="text-danger"></span></label>
                            <input type="text" name="pin" id="dontpin" placeholder="Pin Code" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="state" class="">State <span class="text-danger">*</span></label><br>
                            <select name="state" id="dontstate" class="form-control" style="width:100%" required>
                                <option value="">Select State</option>
                                <?php $states = $this->db->get('state_list')->result();
                                if ($states) {
                                    foreach ($states as $row) { ?>
                                        <option value="<?= $row->id ?>" data-id="<?= $row->code ?>"> <?= $row->state; ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="code" class="">State Code</label><br>
                            <input type="text" readonly name="state_code" id="dontcode" placeholder="Code" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="credit_limit" class="">Credit Limit</label><br>
                            <input type="text" name="credit_limit" id="dontcredit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit') ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="gstTypeParty" class="">gst Type <span class="text-danger"></span></label><br>
                            <select name="gstType" id="dontgstTypeParty" class="form-control">
                                <option value="">-Select-</option>
                                <option value="Regular">Regular</option>
                                <option value="Composite">Composite</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2" id="gstTypeVal">
                            <!-- <label for="gst" class="">GSTIN No <span class="text-danger">*</span></label> -->
                            <label class="control-label" for="gst">
                                <a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No
                                    &gt;&gt;</a>
                            </label>
                            <input name="gst" id="dontgst" class="form-control toUpper" type="text" placeholder="Enter GSTIN No">
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label class="control-label" for="opening_balance">Opening Balance</label>
                            <input autocomplete="off" type="text" name="opening_balance" id="dontopening_balance" placeholder="Opening Balance" value="<?= set_value('opening_balance') ?>" class="form-control">
                        </div>

                        <div class="form-group col-md-4 mb-2">
                            <label class="control-label" for="opBalnceType">Type</label>
                            <select name="opBalnceType" id="dontopBalnceType" class="form-control">
                                <option value="">-Select-</option>
                                <option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR
                                </option>
                                <option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR
                                </option>
                            </select>
                        </div>
                        <input type="hidden" name="partyid" id="dont_party_id">
                        <div class="form-group col-md-4 mb-2" style="margin-top:30px;">
                            <label class="control-label" for="sms">Send Sms</label>
                            <input type="checkbox" name="sms" id="sms">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="custClose" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_login">Update</button>
                        <button type="reset" class="btn btn-danger" id="reset">Reset</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal08" tabindex="-1" role="dialog" aria-labelledby="productModalLabel08" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel08"></h5>
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="text-center">Update Product</h2>
                <!-- Card stats -->
                <div class="row">
                    <!-- <div id="productMsg" style="opacity: 500; display: none;"></div> -->

                    <form id="onProductHandlers" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/updateproducts" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandlers(event)">

                        <?php
                        if (isset($error) && !empty($error)) {
                            echo $error;
                        } ?>

                        <div class="row">
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <lable for="Name">Product Name:</lable>
                                    <input id="dontpname" name="name" tabindex="1" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('name'); ?>

                                </span>
                            </div>
                            <input type="hidden" name="id" id="prosId">
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">

                                <input type="text" class="form-control" tabindex="2" require readonly name="pcode" id="dontppcode" value="">
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('p_code'); ?>

                                </span>
                            </div>

                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontpprice" name="price" tabindex="3" min="1" placeholder="Selling Price" type="number" class="form-control" value="<?= set_value('price') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('price'); ?>

                                </span>
                            </div>
                            <!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
								<div class="form-group">
									<input id="cost" readonly name="sale_rate" data-toggle="modal" data-target="#myModal123" min="0" placeholder="Cost" type="number" class="form-control" required="true" value="<?= set_value('sale_rate') ?>">
								</div>
							</div> -->
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontpcost" value="" name="sale_rate" min="0" placeholder="Purchase Price" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontpmrp" name="mrp" min="0" placeholder="MRP" type="number" class="form-control" value="<?= set_value('mrp') ?>">
                                </div>
                            </div>


                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontpqty" name="qty" min="1" placeholder="Product Qunatity" type="number" class="form-control" value="<?= set_value('qty') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontpdiscount" name="discount" min="0" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontphsn_code" name="hsn_code" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontpmin_qty" name="min_qty" min="0" placeholder="Min Qty" type="number" class="form-control" value="<?= set_value('min_qty') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <input id="dontpweight" name="weight" min="0" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <select id="dontpunit" name="unit" class="form-control" required>
                                    <option value="">Select Unit</option>
                                    <?php
                                    $unit = $this->user_model->getResult('unit');
                                    $role = $this->session->userdata('roles');
                                    if ($role == 1) {
                                        if (isset($unit) && !empty($unit)) {
                                            foreach ($unit as $units) {
                                    ?>
                                                <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                                </option>

                                                <?php
                                            }
                                        }
                                    } else {
                                        if (isset($unit) && !empty($unit)) {
                                            foreach ($unit as $units) {
                                                $unitWhere = ['user_id' => $userId, 'comp_id' => $compId, 'bill_id' => $units->id];
                                                if ($this->session->userdata('BranchRole')){
                                                    $unitWhere['CompanyBranchId'] = $compBranchId;
                                                }
                                                $resu = $this->user_model->getRow('unit_status', $unitWhere);
                                                if (isset($resu) && !empty($resu)) {
                                                    if ($resu->status == 1) {
                                                ?>
                                                        <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                                        </option>

                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                                    </option>

                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('unit'); ?>

                                </span>
                            </div>

                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">

                                    <select id="dontptax" name="tax" class="form-control" required>
                                        <option value="">Select Tax</option>
                                        <?php
                                        $tax = $this->user_model->getResult('tax');

                                        if ($role == 1) {
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $units) {
                                        ?>
                                                    <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
                                                        <?= $units->name; ?></option>

                                                    <?php
                                                }
                                            }
                                        } else {
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $units) {
                                                    $taxWhere2 = ['user_id' => $userId, 'comp_id' => $compId, 'bill_id' => $units->id];
                                                    if ($this->session->userdata('BranchRole')){
                                                        $taxWhere2['CompanyBranchId'] = $compBranchId;
                                                    }
                                                    $resu = $this->user_model->getRow('tax_status', $taxWhere2);
                                                    // $this->session->userdata('user_id');
                                                    if (isset($resu) && !empty($resu)) {
                                                        if ($resu->status == 1) {
                                                    ?>
                                                            <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
                                                                <?= $units->name; ?></option>

                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
                                                            <?= $units->name; ?></option>

                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error("tax"); ?>

                                </span>
                            </div>

                            <!-- 
							<div class="col-md-3 col-md-3 col-sm-6 col-lg-3 form-group">
								<div class="custom-file"> -->
                            <input id="dontpcustomFile" name="image" type="file" class="custom-file-input" value="<?= set_value('image') ?>" hidden>
                            <!-- <label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div> -->

                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <textarea id="dontpshortdescription" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <textarea id="dontpdescription" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= set_value('long_des') ?></textarea>
                                </div>
                            </div>

                        </div>


                        <div class="row mt-5">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-default">


                                    Update

                                </button>
                                <a href="#" type="reset" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="productModel" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 2000px; width: 1300px">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="update_products" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1400px; width: 1390px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabels">Update Product</h5>
                <input type="hidden" id="rownumber">
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleForParty" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabels"></h5>
                <input type="hidden" id="partyRow">
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mynewmodal" tabindex="-1" role="dialog" aria-labelledby="mynewmodal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px; width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mynewmodal"></h5>
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="text-center">Description</h2>
                <textarea type="text" id="description_for" onchange="getDescriptionOnly()" name="description_for" class="form-control"></textarea>
                <input type="hidden" id="description_for_id" value=''>
                <br>
                <br>
                <br>

                <button class="btn btn-primary" class="close" data-dismiss="modal" onclick="closeModel()">Save</button>
                <!-- Card stats -->
                <div class="row">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="desca" tabindex="-1" role="dialog" aria-labelledby="desca" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descasss"></h5>
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="text-center">Add Product</h2>
                <!-- Card stats -->
                <div class="row">
                    <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                        <div class="form-group">
                            <input id="description" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                        </div>

                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-default">


                            Save

                        </button>
                        <a href="#" type="reset" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Select Warehouse -->
<div class="modal fade" id="warehouse_modal_for_product" tabindex="-1" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" name="warehouse_nowno" value="" id="warehouse_nowno" />
        <div class="modal-content warehouse_modal_product"></div>
    </div>
</div>
<!-- Select Warehouse End -->

<!--Quantity Calculator-->
<div class="modal fade" id="quantityCalculatorModal" tabindex="-1" role="dialog" aria-labelledby="quantityCalculatorModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quantity Calculator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                      <div class="col-md-12">
                           <input type="text" name="quantityValue" id="quantityValue" class="form-control form-control-sm" placeholder="00*00*00" autocomplete="off" />
                           <input type="hidden" name="calculatorRowNumber" id="calculatorRowNumber" />
                      </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="addCalculatedQuantity();">Add Quantity</button>
                <button type="button" class="btn btn-danger" class="close" data-dismiss="modal">Close</button>                        
            </div>
        </div>
    </div>
</div>

<style>
    div.product_data_append{
    position: absolute;
    top: 40px;
    left: 0;
}
</style>

<script type="text/javascript">
        const GetProductModel = () => {
                jQuery.noConflict();
                $("#productModel .modal-dialog .modal-content").load("<?= base_url(); ?>" + "product/ProductForm");
                $("#productModel").modal("show");
        };
    
        <?php if (isset($invoiceNumber) && !empty($invoiceNumber)) { ?>
        let invoiceNumber = "<?= $invoiceNumber; ?>";
        document.getElementById('invoiceNumber').value = invoiceNumber;
        $("invoiceNumber").trigger("change");
        <?php } ?>

        $(document).ready(function() {
                // get ware house
                getWarehouseName($("#warehouse").find(":selected").val());
            
                // load_data();
                function load_data(query) {
                        $.ajax({
                                url            :       "<?= base_url(); ?>party/showParty",
                                method     :       "POST",
                                data         :      {query: query},
                                success   :               function(data) {
                                                                        $('#party_data_append').html(data);
				}
                        });
                }

                $('#partyname').keyup(function() {
                        var search = $("#partyname").val();
                        if (search.length > 0) {
                                load_data(search);
                        } else {
                                document.getElementById('party_id').value = '';
                                document.getElementById('party_data_append').innerHTML = '';
                        }
                });
        });


	function productSearch(e, row_id) {
		// var search = e.target.value;
		var search = $("#productname" + row_id).val();
		if (search.length > 0) {
			product_fetch(search, row_id);
		} else {
			// console.log('hello');
			document.getElementById('product_data_append' + row_id).innerHTML = '';

		}
	}

	function product_fetch(query, row_id) {
		$.ajax({
			url: "<?php echo base_url(); ?>index.php/product/all_product_for_sales",
			method: "POST",
			data: {
				query: query,
				rowId: row_id
			},
			success: function(data) {
				// console.log(row_id);
				// document.getElementById('product_data_append'+row_id).innerHTML;
				$('#product_data_append' + row_id).html(data);
			}
		})
	}

	function productSearchCode(e, row_id) {
		// var search = e.target.value;
		var search = $("#p_code" + row_id).val();
		if (search.length > 0) {
			product_fetch_for_product_code(search, row_id);
		} else {
			// console.log('hello');
			document.getElementById('product_data_append_for_pcode' + row_id).innerHTML = '';

		}
	}

	function product_fetch_for_product_code(query, row_id) {
		$.ajax({
			url: "<?php echo base_url(); ?>index.php/product/all_product_for_sales_by_code",
			method: "POST",
			data: {
				query: query,
				rowId: row_id
			},
			success: function(data) {
				// console.log(row_id);
				// document.getElementById('product_data_append'+row_id).innerHTML;
				$('#product_data_append_for_pcode' + row_id).html(data);
			}
		})
	}
</script>
<script>
    function onProductFormSubmitHandler(e) {
        e.preventDefault();
        // $(".searchselect").select2();
        var url = $("#onProductFormSubmitHandler").attr("action");
        var form = $("#onProductFormSubmitHandler")[0];
        var data = new FormData(form);
        //(data);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                // $(".searchselect").select2();
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    console.log(customerList, "ustomer_list")
                    let last_row_id = $(allUniqueRows).get(-1);
                    console.log(last_row_id, "returns");
                    let check_value = $("#productId" + last_row_id).val();
                    let type_sale = "Sale";
                    if (!check_value) {
                        setSelectedProductDetails(last_row_id, customerList.data, type_sale);
                    } else {
                        onAddNewRowHandler();
                        setTimeout(function() {
                            setSelectedProductDetails(last_row_id, customerList.data, type_sale);
                        }, 1000)
                    }

                    $("#productMsg").hide();
                    alert("Product Added");
                    document.getElementById('productResetBtn').click();
                    document.getElementById('productModel').click();

                } else {
                    $("#productMsg").show();
                    $("#productMsg").addClass("text");
                    $("#productMsg").addClass("text-danger");
                    $("#productMsg").html(customerList.msg);
                    // setTimeout(function() {
                    // 	$("#partyMsg").hide();
                    // }, 3000);
                }
            },

        });
    }

    function onProductFormSubmitHandlers(e) {
        e.preventDefault();
        // $(".searchselect").select2();
        var url = $("#onProductHandlers").attr("action");
        var form = $("#onProductHandlers")[0];
        var data = new FormData(form);
        //(data);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                // $(".searchselect").select2();
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    alert("Product Updated")
                    document.getElementById('exampleModal08').click();

                } else {
                    $("#productMsg").show();
                    $("#productMsg").addClass("text");
                    $("#productMsg").addClass("text-danger");
                    $("#productMsg").html(customerList.msg);
                    // setTimeout(function() {
                    // 	$("#partyMsg").hide();
                    // }, 3000);
                }
            },

        });
    }
</script>

<script>
    $(document).ready(function() {
        document.body.style.zoom = 0.8;
        jQuery.noConflict();
        $('#invoiceNumber').focus();
        // $("#invoiceDate").datepicker({
        //     // minDate: 0,
        // });




    });



    function checkEmail() {
        var email = document.getElementById('emails').value;
        // //(email);
        if (email != '') {
            $.ajax({
                url: baseurl + "index.php/party/checkmail",
                method: "POST",
                data: {
                    email: email,
                },
                success: function(products) {
                    var contact = JSON.parse(products);
                    //(contact);
                    if (contact.status == false) {
                        // spanTag1.innerHTML = '';
                        // document.getElementById('contackcheck').style.display = 'none';
                        document.getElementById('mailcheck').innerHTML =
                            '<span class="text-success">Available</span>';
                        document.getElementById('btn_login').disabled = false;
                        //("true");
                    } else {
                        document.getElementById('mailcheck').innerHTML =
                            '<span class="text-danger">Not Available</span>';
                        // document.getElementById('contackcheck1').style.display = 'none';
                        // document.getElementById('contackcheck').innerHTML = 'Not Available';
                        document.getElementById('btn_login').disabled = true;
                    }

                },
            });
        } else {
            document.getElementById('mailcheck').innerHTML = '<span class="text-danger">Not Valid</span>';
            // document.getElementById('contackcheck1').style.display = 'none';
            // document.getElementById('contackcheck').innerHTML = 'Not Available';
            document.getElementById('btn_login').disabled = true;

        }
    }

    function checkContacts() {
        var contacts = document.getElementById('contacts').value;
        if (contacts.length > 5) {
            //(contacts);
            $.ajax({
                url: baseurl + "index.php/party/checkcontacts",
                method: "POST",
                data: {
                    contact: contacts,
                },
                success: function(products) {
                    var contact = JSON.parse(products);
                    //(contact);
                    if (contact.status == false) {
                        // spanTag1.innerHTML = '';
                        // document.getElementById('contackcheck').style.display = 'none';
                        document.getElementById('contackcheck').innerHTML =
                            '<span class="text-success">Available</span>';
                        document.getElementById('btn_login').disabled = false;
                        //("true");
                    } else {
                        document.getElementById('contackcheck').innerHTML =
                            '<span class="text-danger">Not Available</span>';
                        // document.getElementById('contackcheck1').style.display = 'none';
                        // document.getElementById('contackcheck').innerHTML = 'Not Available';
                        document.getElementById('btn_login').disabled = true;
                    }
                },
            });
        } else {
            document.getElementById('contackcheck').innerHTML = '<span class="text-danger">Not Vallid</span>';
        }
    }


    function getProducts() {
        $.ajax({
            url: baseurl + "index.php/product/getproducts",
            method: "GET",
            success: function(products) {
                var producshowPartyPopts = JSON.parse(products);
                if (products.status == true) {
                    prepareProductsDropdown(products.data.product, products.data.productCode);

                }
                $('.searchselect').select2();


            },

        });
    }

    function getOnlyKey(row) {
        var rowKey = row;
    }



    function ProductsGet(product_id, invoiceID) {
        //(product_id);
        $.ajax({
            method: "POST",
            data: {
                id: product_id,
                invoiceID: invoiceID,
            },
            url: baseurl + "index.php/product/proInfo",

            success: function(products) {
                var products = JSON.parse(products);
                if (products.status == true) {
                    //(products.data);
                    // let rowCount = $('#dataTable tbody tr').length + 1;
                    prepareProducts(products.data);
                }

            },

        });
    }

    function print(value) {
        window.print(value);
    }
    $("#consignee").click(function() {

        $('#consigneediv').toggle("slow");
    });

    function deleteRow() {
        var printValue = document.getElementById('printSale').value;
        var invoiceNumber = document.getElementById('invoiceNumber').value;

        if (printValue == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Enter Invoice First",
                showConfirmButton: false,
                timer: 2000,
            });
            // alert('ENter First Invocie Number That you Want to delete');
            return;
        }
        var url = "<?php echo base_url(); ?>proforma/trash/" + printValue
        //(url, "url");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                removeRow(url);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }




    function removeRow(url) {
        $.ajax({
            type: "get",
            url: url,
            success: function(success) {
                success = JSON.parse(success);
                if (success.status == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(function() {
                        // window.location.reload();
                        window.location.href = '<?= base_url(); ?>proforma';
                    }, 2000);
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            },
        });
    }

    $('#consigneestate').change(function() {
        var code = $(this).find(':selected').data('id');
        $('#consigneestatecode').val(code);
    });



    $(document).ready(function() {
        var invoiceNumber = document.getElementById('invoiceNumber').value;
        document.getElementById('printSale').value = 0;
        //(invoiceNumber, "invocienumber");
        getInvoiceInfo(invoiceNumber);


    })


    // var x = '';
    var tr = 0;

    function trIncriment(tr, withdata) {
        return tr++;
    }




    var xml = 0;



    function filterWithSearh(e) {
        if (e.keyCode == '40') {

            var searchparty = document.getElementById('searchparty').value;
            // console.log(searchparty, "searchparty");
            if (searchparty != '') {
                tr = xml;
                var elmtTable = document.getElementById('example12');
                var tableRows = elmtTable.getElementsByTagName('tr');
                var rowCount = tableRows.length;
                var start = document.getElementById('tr0');
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';

                // console.log(tr, "tr");
                // console.log(xml, "xmlll");
                // console.log(rowCount, "rowCOUNTTT");
                xml++;
                // }
            }
        }
    }

    function filterWithoutSearh(e) {
        if (e.keyCode == '40') {

            var elmtTable = document.getElementById('example12');
            var tableRows = elmtTable.getElementsByTagName('tr');
            // console.log(tableRows, "Tablerowssssss");
            var rowCount = tableRows.length;
            if (rowCount >= tr) {
                if (tr < 0) {
                    tr = 0;
                }
                // console.log(tr, "tr");
                var start = document.getElementById('tr' + tr);
                // var searchparty = document.getElementById('searchparty').value;
                // console.log(start, "start");
                if (tr != 0) {
                    var previous = start.previousSibling.previousElementSibling;
                }

                // console.log(previous, "previous");

                if (previous != null) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                    // }
                }

                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(tr);
                tr++;
            }
        } else {
            document.getElementById('tr0').focus();

        }
    }



    function chnageDirections(event) {
        if (event.keyCode == 37) {
            // alert("left click for btnwithprint");
            var start = document.getElementById('btnSaveAndPrint');

            start.style.backgroundColor = 'white';
            start.style.color = 'black';
            document.getElementById('btnSave').focus();
            var start = document.getElementById('btnSave');
            start.style.backgroundColor = 'blue';
            return true;

            // console.log("okk")

        }
        if (event.keyCode == 39) {
            // alert("right click for btnwithprint");
            var start = document.getElementById('btnSaveAndPrint');

            start.style.backgroundColor = 'white';
            start.style.color = 'black';
            document.getElementById('btnSaveCancel').focus();
            var start = document.getElementById('btnSaveCancel');
            start.style.backgroundColor = 'blue';
            // start.style.border = '1 px red';
            return true;
        }
    }

    function chnageDirectionss(event) {
        if (event.keyCode == 37) {
            var start = document.getElementById('btnSaveCancel');
            start.style.backgroundColor = 'white';
            start.style.color = 'black';

            document.getElementById('btnSaveAndPrint').focus();
            var start = document.getElementById('btnSaveAndPrint');
            start.style.backgroundColor = 'blue';
            // console.log("okk")
            // alert("okokok")
            // return true;

        }
        if (event.keyCode == 39) {
            var start = document.getElementById('btnSaveAndPrint');

            start.style.backgroundColor = 'white';
            start.style.color = 'black';
            document.getElementById('btnSaveCancel').focus();
            var start = document.getElementById('btnSaveCancel');
            start.style.backgroundColor = 'blue';
            // start.style.border = '1 px red';
            return true;

        }
    }

    function enterKeysearchcity(event) {
        if (event.keyCode == 13) {
            var search = $('#searchcity').val();
            if (search.length != '') {
                $("tr[tabindex=0]").click();
                document.getElementById("customerMobile").focus();

                return;

            } else {
                var elmtTable = document.getElementById('example14');
                var tableRows = elmtTable.getElementsByTagName('tr');
                var rowCount = tableRows.length;
                // console.log(tableRows.tr0, "trooo");
                // for (var x = 1; x > 0; x--) {
                tableRows.tr0.click();
                // if (rowCount)f
                document.getElementById("customerMobile").focus();

                // 	document.getElementById(tableRows + 'tr0').click();
                return;
                // $("#tr0").click();
                // }

            }

        }
    }



    function enterKeysearchState(event) {
        if (event.keyCode == 13) {
            var search = $('#searchstate').val();
            if (search.length != '') {
                $("tr[tabindex=0]").click();
                // console.log("okk")
                return true;
            } else {
                // $('#searchparty').focus();
                document.getElementById("tr0").click();
                document.getElementById("searchstate").focus();
            }

        }
    }


    $('#statesearch').keyup(function() {
        // $('#consigneestatecode').val(code);
        var search = $('#statesearch').val();
        if (search.length != '') {
            $.ajax({
                method: "POST",
                // url: baseurl + "get-customer",
                url: baseurl + "index.php/party/statesearch",
                data: {
                    name: search,
                },
                success: function(customer) {
                    var customer = JSON.parse(customer);
                    if (customer.status == true) {
                        //(customer, "details");
                        var da = customer.data;
                        myHtml = '';
                        if (da.party) {
                            $.each(da.party, function(i, item) {

                                myHtml += "<tr tabindex=" + i + " id=" + i +
                                    " onclick='getStateSelect(" + item.id + ")'><td>" + item
                                    .state + "</td>" +
                                    "</tr>";
                            });
                            $("#statesearchdata").html(myHtml);
                        } else {
                            $('#statesearch').focus();

                        }
                    }
                }
            })

        } else {
            $('#searchcity').focus();
        }

    });

    $('#searchcity').keyup(function() {
        // $('#consigneestatecode').val(code);
        var search = $('#searchcity').val();
        if (search.length != '') {
            $.ajax({
                method: "POST",
                // url: baseurl + "get-customer",
                url: baseurl + "index.php/party/searchcity",
                data: {
                    name: search,
                },
                success: function(customer) {
                    var customer = JSON.parse(customer);
                    if (customer.status == true) {
                        //(customer, "details");
                        var da = customer.data;
                        myHtml = '';
                        if (da.party) {
                            $.each(da.party, function(i, item) {

                                myHtml += "<tr tabindex=" + i + " id=tr" + i +
                                    " onclick='getCitySelect(" + item.id + ")' ><td>" + item
                                    .name + "</td>" +
                                    "</tr>";
                            });
                            $("#searchcitydata").html(myHtml);
                        } else {
                            $('#searchcity').focus();

                        }
                    }
                }
            })

        } else {
            $('#searchcity').focus();
        }

    });



    $('#searchproduct').keyup(function() {
        // $('#consigneestatecode').val(code);
        var search = $('#searchproduct').val();
        if (search.length != '') {
            $.ajax({
                method: "POST",
                // url: baseurl + "get-customer",
                url: baseurl + "index.php/product/searchproduct",
                data: {
                    name: search,
                },
                success: function(customer) {
                    // alert("searchparty");
                    var customer = JSON.parse(customer);
                    if (customer.status == true) {
                        document.getElementById('producttbdoy').innerHTML = '';
                        //(customer, "details");
                        var da = customer.data;
                        myHtml = '';
                        if (da.party) {
                            $.each(da.party, function(i, item) {
                                // getTaxDatas(item.tax, i);
                                // getUnitDatas(item.unit, i);
                                $("#producttbdoy").append("<tr tabindex=" + -1 +
                                    " id=protr" + i +
                                    " onkeyup='keyUpForProduct(event," + i +
                                    ")' onkeydown='keyDownForProduct(event," +
                                    i + ")'  onkeypress='onProductSelect(" +
                                    item.id + ")' onclick='onProductSelect(" +
                                    item.id + ")'><td>" + item.p_name +
                                    "</td>" +
                                    "<td>" + item.hsn + "</td>" +
                                    "<td>" + item.qty + "</td>" +
                                    "<td>" + item.price + "</td>" +


                                    // "<td>" + item.mrp + "</td>" +

                                    "</tr>"

                                )
                            });

                        } else {
                            $('#searchproduct').focus();

                        }
                    }
                }
            })

        } else {
            $('#searchproduct').focus();
        }

    })






    function partyID(e) {
        // $("#party_id").change(function() {
        // //(e);
        var elmtTable = document.getElementById('dataTable');
        var tableRows = elmtTable.getElementsByTagName('tr');
        var rowCount = tableRows.length;
        // //(rowCount);
        for (var x = rowCount; x > 0; x--) {

            $("#tr" + x).remove();
        }
        getProducts();
        // getProductCode();


        var party_id = e.target.value;;
        // //(party_id);
        getCustomerDetails(party_id);
        // document.getElementById("exampleModalLong").click();

        // })
    };


    $("#invoiceNumber").change(function() {

        var invoiceNumber = document.getElementById('invoiceNumber').value;
        document.getElementById('printSale').value = 0;
        //(invoiceNumber, "invocienumber");
        getInforMation(invoiceNumber);
    });

    const getInforMation = (InvoiceNumber) => {
        $.ajax({
            method  :   "POST",
            url     :   baseurl + "proforma/getProformaByInvoiceNumber",
            data    :   {id: InvoiceNumber},
            success :   function(Response) {
                            allUniqueRows.forEach((element) => {
                                onRemoveRowHandlers(element);
                            });
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.status == true) {
                                window.location.href = '<?= base_url() ?>proforma/edit/' + ResponseData.data.id + '';
                            } else {
                                getProducts();
                            }
                        }
        });
    };

    const getInvoiceInfo = (InvoiceNumber) => {
        $.ajax({
            method  :   "POST",
            url     :   baseurl + "proforma/getProformaById",
            data    :   {id: InvoiceNumber},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.status == true) {
                                var JsonData = ResponseData.data;
                                // total_data
                                var product_details = JsonData.order_product;
                                // product details
                                var customerDetails = JsonData.orderbook;
                                // global details
                                document.getElementById('printSale').value = customerDetails.id;
                                // sale id
                                $("#invoiceDate").val(customerDetails.invoiceDate != null ? customerDetails.invoiceDate : "");
                                $("#voucherType").val(customerDetails.voucherType != null ? customerDetails.voucherType : "");
                                $("#taxcal").val(customerDetails.taxCal != null ? customerDetails.taxCal : "");
                                $("#party_id").val(customerDetails != null ? customerDetails.customerId : "");
                                $("#partyname").val(customerDetails.customerName != null ? customerDetails.customerName : "");
                                $("#gstNos").val(customerDetails.gstNo != null ? customerDetails.gstNo : "");
                                $("#customerAddress").val(customerDetails.customerAddress != null ? customerDetails.customerAddress : "");
                                $("#customerMobile").val(customerDetails.customerMobile != null ? customerDetails.customerMobile : "");
                                $("#customerEmail").val(customerDetails.customerEmail != null ? customerDetails.customerEmail : "");
                                $("#statename").val(customerDetails.state_name != null ? customerDetails.state_name : "");
                                $("#stateSelect").val(customerDetails != null ? customerDetails.customerState : "");
                                $("#customerCity").val(customerDetails.customerCity != null ? customerDetails.customerCity : "");
                                $("#cityCustomer").val(customerDetails.customerCity != null ? customerDetails.city_name : "");
                                $("#customerBalance").val(customerDetails.customerBalance != null ? customerDetails.customerBalance : "");
                                $("#taxableAmount").val(customerDetails.taxableAmount != null ? customerDetails.taxableAmount : 0);
                                $("#totalsgst").val(customerDetails.totalsgst != null ? customerDetails.totalsgst : 0);
                                $("#totaligst").val(customerDetails.totaligst != null ? customerDetails.totaligst : 0);
                                $("#totalcgst").val(customerDetails.totalcgst != null ? customerDetails.totalcgst : 0);
                                $("#totalTax").val(customerDetails.totalTax != null ? customerDetails.totalTax : 0);
                                $("#cess").val(customerDetails.cess != null ? customerDetails.cess : "");
                                $("#expenses").val(customerDetails.expenses != null ? customerDetails.expenses : "");
                                $("#totalValue").val(customerDetails.totalValue != null ? customerDetails.totalValue : 0);
                                $("#roundOff").val(customerDetails.roundOff != null ? customerDetails.roundOff : 0);
                                $("#freight_charge").val(customerDetails.freight_charge != null ? customerDetails.freight_charge : 0);
                                $("#cash_discount").val(customerDetails.cash_discount != null ? customerDetails.cash_discount : 0);
                                $("#lrDate").val(customerDetails.lrDate != null ? customerDetails.lrDate : "");
                                $("#ewayBill").val(customerDetails.ewayBill != null ? customerDetails.ewayBill : "");
                                $("#transport").val(customerDetails.transport != null ? customerDetails.transport : "");
                                $("#orderNumber").val(customerDetails.orderNumber != null ? customerDetails.orderNumber : "");
                                $("#orderDate").val(customerDetails.orderDate != null ? customerDetails.orderDate : "");
                                $("#vehicle").val(customerDetails.vahical_no != null ? customerDetails.vahical_no : "");
                                $("#payTermsDay").val(customerDetails.payTermsDay != null ? customerDetails.payTermsDay : "");
                                $("#dueDate").val(customerDetails.dueDate != null ? customerDetails.dueDate : "");
                                $("#lot_no").val(customerDetails.lot_no != null ? customerDetails.lot_no : "");
                                $("#dcNumber").val(customerDetails.dcNumber != null ? customerDetails.dcNumber : "");
                                $("#dcDate").val(customerDetails.dcDate != null ? customerDetails.dcDate : "");
                                $("#oldBalance").val(customerDetails.oldBalance != null ? customerDetails.oldBalance : "");
                                $("#total").val(customerDetails.total != null ? customerDetails.total : 0);
                                $("#total_amount_for").val(customerDetails.total != null ? customerDetails.total : 0);
                                $("#num_of_items").val(customerDetails.num_of_items != null ? customerDetails.num_of_items : 0);
                                $("#remark").val(customerDetails.remark != null ? customerDetails.remark : "");
                                $("#amountInWords").val(customerDetails.amountInWords != null ? customerDetails.amountInWords : "");

                                if (customerDetails.shippingInfo != null && customerDetails.shippingInfo.length > 0) {
                                       var shippingInfo = JSON.parse(customerDetails.shippingInfo);
                                       $("#shippingNameHidden").val(customerDetails != null ? shippingInfo.shippingName : "");
                                       $("#shippingEmailHidden").val(customerDetails != null ? shippingInfo.shippingEmail : "");
                                       $("#shippingMobileHidden").val(customerDetails != null ? shippingInfo.shippingMobile : "");
                                       $("#shippingGSTTypeHidden").val(customerDetails != null ? shippingInfo.shippingGSTType : "");
                                       $("#shippingGSTHidden").val(customerDetails != null ? shippingInfo.shippingGST : "");
                                       $("#shippingStateHidden").val(customerDetails != null ? shippingInfo.shippingState : "");
                                       $("#shippingAddressHidden").val(customerDetails != null ? shippingInfo.shippingAddress : "");
                                       $("#shippingCityHidden").val(customerDetails != null ? shippingInfo.shippingCity : "");
                                       $("#shippingZipHidden").val(customerDetails != null ? shippingInfo.shippingZip : "");
                                }

                                $("#formats").val(customerDetails.format_invoice != null ? customerDetails.format_invoice : "Tax");
                                $("#format_invoice").val(customerDetails.format_invoice != null ? customerDetails.format_invoice : "Tax");
                                getSalesmanName(customerDetails.salesman != null ? customerDetails.salesman : "");
                                for (i = 0; i < product_details.length; i++) {
                                    prepareProducts(product_details[i]);
                                }
                            } else {
                                getProducts();
                            }
                        }
        });
    };

    const getCustomerDetails = (customerId) => {
        $.ajax({
            method: "POST",
            // url: baseurl + "get-customer",
            url: baseurl + "index.php/party/getCustomer",

            data: {
                id: customerId,
            },
            success: function(customer) {
                var customer = JSON.parse(customer);
                setSelectedCustomerDetails(customer.data);
            },

        });
    };

    const getCustomerEdit = (customerId) => {
        $.ajax({
            method: "POST",
            // url: baseurl + "get-customer",
            url: baseurl + "index.php/party/getCustomer",

            data: {
                id: customerId,
            },
            success: function(customer) {
                var customer = JSON.parse(customer);
                setSelectedCustomer(customer.data);
            },

        });
    };

    const getProducutEditable = (productid) => {
        $.ajax({
            method: "POST",
            // url: baseurl + "get-customer",
            url: baseurl + "index.php/product/productset",

            data: {
                id: productid,
            },
            success: function(customer) {
                var customer = JSON.parse(customer);
                setSelectedproduct(customer.data);
            },

        });
    };

    // const orBanoProductForm = (row, e) => {

    // }

    const setSelectedCustomer = (customer) => {
        // console.log(customer, "party details..............");
        // stateCode = customer.state_code ? customer.state_code : "";
        // getStateName(customer.state_code);
        $("#dontnameememe").val(customer != null ? customer.name : "");
        $("#dontgroup").val(customer != null ? customer.group_head : "");
        $("#dontnumber").val(customer != null ? customer.contact : "");
        $("#dontemail").val(customer != null ? customer.email : "");
        $("#dontpin").val(customer != null ? customer.pin : "");
        $("#dontcity").val(customer != null ? customer.city : "");
        $("#dontaddress_1").val(customer != null ? customer.address_1 : "");
        $("#dontstate").val(customer != null ? customer.state_code : "");
        $("#dontcode").val(customer != null ? customer.state_code : "");

        $("#dontopening_balance").val(customer != null ? customer.opening_balance : "");
        $("#dontopBalnceType").val(customer != null ? customer.opBalnceType : "");
        $("#dontcredit_limit").val(customer != null ? customer.credit_limit : "");
        $("#dontopBalnceType").val(customer != null ? customer.opBalnceType : "");
        $("#dontgstTypeParty").val(customer != null ? customer.gstType : "");
        $("#dont_party_id").val(customer != null ? customer.id : "");
        // document.getElementById("exampleModalLong").click();

    };

    const setSelectedproduct = (customer) => {
        // console.log(customer, "product details..............");

        $("#dontpname").val(customer != null ? customer.p_name : "");
        $("#dontppcode").val(customer != null ? customer.qrCode : "");

        $("#dontpprice").val(customer != null ? customer.price : "");
        $("#dontpcost").val(customer != null ? customer.purchase_rate : "");

        $("#dontpmrp").val(customer != null ? customer.mrp : "");
        $("#dontpqty").val(customer != null ? customer.qty : "");
        $("#dontpdiscount").val(customer != null ? customer.discount : "");

        $("#dontphsn_code").val(customer != null ? customer.hsn : "");
        $("#dontpmin_qty").val(customer != null ? customer.min_qty : "");
        $("#dontpweight").val(customer != null ? customer.weight : "");
        $("#dontptax").val(customer != null ? customer.tax : "");
        $("#dontpunit").val(customer != null ? customer.unit : "");
        $("#dontpshortdescription").val(customer != null ? customer.short_de : "");
        $("#dontpdescription").val(customer != null ? customer.long_de : "");
        $("#prosId").val(customer != null ? customer.id : "");


        // document.getElementById("exampleModalLong").click();
    };




    const getPartyList = () => {
        $.ajax({
            method: "POST",
            // url: baseurl + "get-customer",
            url: baseurl + "index.php/party/getList",
            success: function(customer) {
                // if (customer.status == true) {
                // customer.parse
                var customers = JSON.parse(customer);
                $("#party_id").html(customers.data);
                // }

            },

        });
    };


    function showAddCustomerDialoag() {
        $("#exampleModal .modal-dialog .modal-body").load(baseurl + "index.php/party/load_party");
        $("#exampleModalLabel").html("Add Party");
        // $("#exampleModal").show();
        $('#exampleModal').modal('show');
        $(".searchselect").select2({
            dropdownParent: "#exampleModal"
        });
    }

    function getStateName(state_id, state_name = null) {
        $.ajax({
            method: "POST",
            // url: baseurl + "get-customer",
            url: baseurl + "index.php/state/stateNames/" + state_id,
            success: function(customer) {
                var product = JSON.parse(customer);
                // console.log(product);
                if (product.status == true) {
                    var product = product.data;
                    // console.log(product);
                    document.getElementById('statename').value = product.party[0].state;
                } else {
                    // console.log("not find");
                    document.getElementById('statename').value = state_name;

                }



            },
        })
    }

    function getStateNames() {
        var state_value = document.getElementById('statename').value;
        if (state_value != '') {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "index.php/state/stateNamess",
                data    :   {name: state_value},
                success :   function(customer) {
                                var product = JSON.parse(customer);
                                // console.log(product);
                                if (product.status == true) {
                                    var product = product.data;
                                    // console.log(product);
                                    document.getElementById('stateSelect').value = product.party[0].id;
                                    document.getElementById('statename').value = product.party[0].state;
                                } else {
                                    // console.log("not find");
                                    document.getElementById('stateSelect').value = "";
                                }
                                changeTaxCal();
                            }
            });
        } else {
            document.getElementById('stateSelect').value = "";
        }
    }

    function changeCityNames() {

        var city_name = document.getElementById('cityCustomer').value;
        // console.log(city_name);
        if (city_name != '') {

            $.ajax({
                method: "POST",
                // url: baseurl + "get-customer",
                url: baseurl + "index.php/district/citynames",
                data: {
                    name: city_name,
                },
                success: function(customer) {
                    var product = JSON.parse(customer);
                    // console.log(product);
                    if (product.status == true) {
                        var product = product.data;
                        // console.log(product);
                        document.getElementById('customerCity').value = product.party[0].id;
                        document.getElementById('cityCustomer').value = product.party[0].name;

                    } else {
                        // console.log("not find");
                        document.getElementById('customerCity').value = "";

                    }



                },
            })
        } else {
            document.getElementById('customerCity').value = "";

        }
    }

    function getPartyName(party_id) {
        $.ajax({
            method: "POST",
            // url: baseurl + "get-customer",
            url: baseurl + "index.php/party/partyname/" + party_id,
            success: function(customer) {
                var product = JSON.parse(customer);
                // console.log(product);
                if (product.status == true) {
                    var product = product.data;
                    // console.log(product);
                    document.getElementById('partyname').value = product.party[0].name;
                } else {
                    // console.log("not find");
                    document.getElementById('partyname').value = "";

                }



            },
        })
    }

    function showDDLBox() {
        $("#party_id").attr("disabled", false);
        $("#party_id").attr("required", true);
        $("#customerName").val("");
        $("#party_idDiv").show();
        $("#customerNameDiv").hide();
        $("#customerName").attr("disabled", true);
        $("#customerName").attr("required", false);
        $(".addPatys").show();
        $(".addText").show();
        $(".addDdl").hide();
    }

    function showTextBox() {
        $("#party_id").attr("disabled", true);
        $("#party_id").attr("required", false);
        $("#party_id").val("").trigger("change");
        $("#party_idDiv").hide();
        $("#customerNameDiv").show();
        $("#customerName").attr("disabled", false);
        $("#customerName").attr("required", true);
        $(".addPatys").hide();
        $(".addText").hide();
        $(".addDdl").show();
    }

    var uniqueRow = 1;
    const allUniqueRows = [];
    const prepareProductsDropdown = (products, productsCode) => {
        let srNo = $('#dataTable tbody tr').length + 1;
        $("#dataTable tbody").append('<tr style="margin-bottom:-10px;margin-top:-10px;" id="tr' + uniqueRow + '" >\n' +
            '                                                        <td style="width:20px;padding:4px;">\n' +
            '                                                      ' + srNo + '</td>\n' +
            <?php
            if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
            ?> '<td style="width:120px;padding:4px;">\n' +
                '                                                          <input type="text" style="width:100%;border:none;" name="product[' +
                uniqueRow + '][p_code]"  id="p_code' + uniqueRow +
                '" class="form-control" onkeydown="keyDownForProductInfo(' + uniqueRow +
                ',event)" onchange="onProductCodeEnter(' + uniqueRow + ',event)"  onkeypress="onProductCodeEnter(' +
                uniqueRow + ',event)">\n' +
                '                                                      <div id="product_data_append_for_pcode' + uniqueRow + '"></td>\n' +
            <?php
            } else {
            ?> '<td hidden style="width:120px;padding:4px;">\n' +
                '                                                          <input type="text" style="width:100%;border:none;" name="product[' +
                uniqueRow + '][p_code]"   id="p_code' + uniqueRow +
                '" class="form-control" onkeydown="keyDownForProductInfo(' + uniqueRow +
                ',event)" onchange="onProductCodeEnter(' + uniqueRow + ',event)" onkeypress="onProductCodeEnter(' +
                uniqueRow + ',event)">\n' +
                '                                                       </td>\n' +
            <?php
            }
            ?> '                                                        <td style="width: 300px !important;padding:4px;position:relative;">\n' +
            '                                                           <div id="pro_names' + uniqueRow +
            '"><input type="text" style="width: 100% !important;border:none;"  onkeydown="return keyDownProduct(event)" onkeyup="productSearch(event,' + uniqueRow + ')" onmouseleave="ProductDataRemove(event,' + uniqueRow + ')" onchange="ProductDataRemove(event,' + uniqueRow + ')" onkeypress="return enterKey(event)" name="product[' + uniqueRow + '][productname]"   id="productname' +
            uniqueRow + '" class="form-control okproduct"  required="true"></div>\n' +
            '                                                       </div>' +
            '<input type="hidden" id="productId' + uniqueRow + '" name="product[' + uniqueRow + '][product]" value=""><div class="product_data_append" id="product_data_append' + uniqueRow + '"></div></td>\n' +
            '<td style="width:4px;padding:4px;"><textarea hidden type="text" readonly id="product_dec' + uniqueRow +
            '"  name="product[' + uniqueRow + '][product_dec]" data-toggle="modal" data-target="#desc' + uniqueRow +
            '" onclick="showPop(' + uniqueRow +
            ',event)" value="" class="form-control"></textarea><i class="fas fa-edit" data-toggle="modal" data-target="#desc' +
            uniqueRow + '" onclick="showPop(' + uniqueRow + ',event)" title="Product Edit"></i></td>' +
            '                                                        <td style="width:30px;padding:4px;"><input style="width:45px;border:none;" type="text"  id="hsnCode' +
            uniqueRow + '" name="product[' + uniqueRow + '][hsn]"  class="form-control" /></td>\n' +
              <?php
            if ($compProfile->category != "Normal") {
            ?> '<td style="width:120px;padding:4px;">\n' +
                '                                                          <input type="text" style="width:100%;border:none;" name="product[' +
                uniqueRow + '][sqft]"  id="sqft' + uniqueRow +
                '" class="form-control"></td>\n' +
            <?php
            } else {
            ?> '<td hidden style="width:120px;padding:4px;">\n' +
                '                                                          <input type="text" style="width:100%;border:none;" name="product[' +
                uniqueRow + '][sqft]"  id="sqft' + uniqueRow +
                '" class="form-control"></td>\n' +
            <?php
            }
            ?>'                                                         <td style="width:125px;padding:4px;"><div class="input-group"><input  type="number" style="border:none;" min="1" required="true"  onchange="onQuantityChangeHandler(event,' +
            uniqueRow + ');" showSerial(event,' + uniqueRow + ');" onkeypress="return EnterPress(event,' + uniqueRow +
            ');" onkeyup="onQuantityChangeHandler(event,' + uniqueRow + ')" id="qty' + uniqueRow +
            '" name="product[' + uniqueRow + '][qty]" class="form-control"/><a href="javascript:void(0);" class="input-group-text" onclick="quantityCalculator(' + uniqueRow + ', event);"><i class="fa fa-calculator"></i></a></div></td>\n' +
            '                                                        <td style="width:125px;padding:4px;"><input  type="number" style="border:none;" min="0" value="0" id="rate' +
            uniqueRow + '"  name="product[' + uniqueRow +
            '][rate]"  required="true" onchange="onRateChangeHandler(event,' + uniqueRow +
            ')" onkeyup="onRateChangeHandler(event,' + uniqueRow + ')"  class="form-control floatNumber"/></td>\n' +
            <?php if ($discount == 'yes') {
            ?> '<td style="width:30px;padding:4px;"><input  type="number" style="border:none;" min="0" value="0" id="discount' +
                uniqueRow + '" name="product[' + uniqueRow +
                '][discount]"   onchange="onDiscountHandler(event,' + uniqueRow +
                ')" onkeyup="onDiscountHandler(event,' + uniqueRow +
                ')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +
            <?php } else {
            ?> '<td hidden style="width:30px;padding:4px;"><input  type="text" style="border:none;" min="0" value="0" id="discount' +
                uniqueRow + '" name="product[' + uniqueRow +
                '][discount]"   onchange="onDiscountHandler(event,' + uniqueRow +
                ')" onkeyup="onDiscountHandler(event,' + uniqueRow +
                ')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +
            <?php } ?> '<td style="display:none;"><input  type="text" min="0" style="border:none;" value="0" id="cd' +
            uniqueRow + '" name="product[' + uniqueRow + '][cd]"  onchange="onCDHandler(event,' + uniqueRow +
            ')" onkeyup="onCDHandler(event,' + uniqueRow +
            ')" class="form-control floatNumber" style="width:70px;float:left;"/></td>\n' +
            '                                                        <td style="width:81px;padding:4px;"><div id="taxal' +
            uniqueRow + '"><input style="width:65px;border:none;"  type="number" value="0" min="0" id="tax' +
            uniqueRow + '" name="product[' + uniqueRow + '][tax]" class="form-control"/></div></td>\n' +
            '                                                        <td style="width:125px;padding:4px;"><input style="border:none;"  type="text" readonly name="product[' +
            uniqueRow + '][price]" id="price' + uniqueRow + '" value="0" onkeyup="onPriceChangeHandler(event,' +
            uniqueRow + ')" required="true" class="form-control bg-light" ></td>\n' +
            '                                                        <td style="width:125px;padding:4px;"><input style="border:none;" type="text" min="0" value="0" readonly id="amount' +
            uniqueRow + '" name="product[' + uniqueRow + '][amount]" class="form-control bg-light"/>' +
            '                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="taxAmount' +
            uniqueRow + '" name="product[' + uniqueRow + '][taxAmount]" class="form-control bg-light"/>' +
            '                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="sgst' +
            uniqueRow + '" name="product[' + uniqueRow + '][sgst]" class="form-control"/>' +
            '                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="cgst' +
            uniqueRow + '" name="product[' + uniqueRow + '][cgst]" class="form-control"/>' +
            '                                                        <input  type="hidden" min="0" value="0" style="border:none;" readonly id="igst' +
            uniqueRow + '" name="product[' + uniqueRow + '][igst]" class="form-control"/></td>\n' +
            '                                                        <td style="width:140px;padding:4px;"><input style="border:none;"  type="number" min="0" value="0" readonly id="netAmount' +
            uniqueRow + '"  name="product[' + uniqueRow +
            '][netAmount]" class="form-control bg-light add-new-row" onkeydown="onNewRowAdd(event)"/></td>\n' +
            '<td style="padding:4px;width:84px;">' +
            '<input type="hidden" name="product['+uniqueRow+'][warehouse]" id="warehouse_id'+uniqueRow+'" />'+
            '<a href="javascript:void(0);" style="width:1px;height:1px;" onclick="onAddNewRowHandler()">'+
            '<i class="fa fa-plus-circle text-success cp" title="Add new row"></i>'+
            '</a>'+
            '&nbsp;&nbsp;'+
            '<a style="width:1px;height:1px;" href="javascript:void(0);" onclick="onRemoveRowHandler(' + uniqueRow + ')">'+
            '<i class="fa fa-minus-circle text-danger cp" title="Remove selected row"></i>'+
            '</a>' +
            '<span id="editProduct' + uniqueRow + '"></span>' +
            '</td>' +
            '</tr>');

        if (uniqueRow >= 1) {
            allUniqueRows.push(uniqueRow);
            console.log(allUniqueRows, "alluniqueRows");
            ++uniqueRow;

        }
    }

  const showPop = (rowNumber, e) => {
        jQuery.noConflict();
        var des = document.getElementById('product_dec' + rowNumber);
        if (des != null) {
            des = des.value;
        } else {
            des = "";
        }
        document.getElementById('description_for_id').value = rowNumber;
        document.getElementById('description_for').value = des;
        $('#mynewmodal').modal('show');
    }
    
    const quantityCalculator = (rowNumber, e) => {
        jQuery.noConflict();
        $("#quantityValue").val("");
        $('#quantityCalculatorModal').modal('show');
        $("#calculatorRowNumber").val(rowNumber);
    };
    
    const addCalculatedQuantity = () => {
        var calculatorRowNumber = $("#calculatorRowNumber").val();
        var quantityValue = $("#quantityValue").val();
        var valuesAndOperator = quantityValue.split('*');
        var value1 = parseFloat(valuesAndOperator[0]) || 0;
        var value2 = parseFloat(valuesAndOperator[1]) || 0;
        var value3 = parseFloat(valuesAndOperator[2]) || 1;

        var result = value1 * value2 * value3;
        $("#qty" + calculatorRowNumber).val(result);
        $("#qty" + calculatorRowNumber).trigger("change").focus();
        $('#quantityCalculatorModal').modal('hide');
    };
    
    // Trigger on Enter key press
    $("#quantityValue").on("keypress", function (event) {
        if (event.key === "Enter") {
            addCalculatedQuantity();
        }
    });

    const prepareProducts = (products) => {
        var type = "Sale";
        let srNo = $('#dataTable tbody tr').length + 1;
        $("#dataTable tbody").append(' <tr id="tr' + uniqueRow + '">\n' +
            '                                                        <td style="width:20px;padding:4px;">\n' +
            '                                                      ' + srNo + '</td>\n' +
            <?php
            if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
            ?> '<td style="width:120px;padding:4px;">\n' +
                '                                                          <input style="border:none;" type="text" value="' +
                products.p_code + '" style="width:100%;" name="product[' + uniqueRow + '][p_code]"  id="p_code' +
                uniqueRow + '" onkeydown="keyDownForProductInfo(' + uniqueRow +
                ',event)" onkeydown="keyDownForProductInfo(' + uniqueRow + ',event)"  onchange="onProductCodeEnter(' +
                uniqueRow + ',event)" onkeypress="onProductCodeEnter(' + uniqueRow + ',event)" class="form-control">\n' +
                '                                                       </td>\n' +
            <?php
            } else {
            ?> '<td hidden style="width:120px;padding:4px;">\n' +
                '                                                          <input style="border:none;" type="text" value="' +
                products.p_code + '" style="width:100%;" name="product[' + uniqueRow + '][p_code]"  id="p_code' +
                uniqueRow + '" onkeydown="keyDownForProductInfo(' + uniqueRow +
                ',event)" onkeydown="keyDownForProductInfo(' + uniqueRow + ',event)"  onchange="onProductCodeEnter(' +
                uniqueRow + ',event)" onkeypress="onProductCodeEnter(' + uniqueRow + ',event)" class="form-control">\n' +
                '                                                       </td>\n' +
            <?php
            }
            ?> '                                                        <td style="width: 150px !important;padding:4px;position:relative;">\n' +
            '                                                           <div id="pro_names' + uniqueRow +
            '"><input type="text" style="width: 195px !important;border:none;"   onkeydown="return keyDownProduct(event)" onkeyup="productSearch(event,' + uniqueRow + ')" onmouseleave="ProductDataRemove(event,' + uniqueRow + ')"  onchange="ProductDataRemove(event,' + uniqueRow + ')" onkeypress="return enterKey(event)" name="product[' + uniqueRow + '][productname]"   id="productname' +
            uniqueRow + '" value="' + products.productname + '" class="form-control okproduct"  required="true"></div>\n' +
            '                                                       </div>' +
            '<input type="hidden" id="productId' + uniqueRow + '" name="product[' + uniqueRow + '][product]" value="' + products.product_id + '"><div class="product_data_append" id="product_data_append' + uniqueRow + '"></div></td>\n' +
            '<td style="width:4px;padding:4px;">' +
            '<textarea type="text" readonly hidden id="product_dec' + uniqueRow + '" name="product[' + uniqueRow +
            '][product_dec]" data-toggle="modal" data-target="#desc' + uniqueRow + '" onclick="showPop(' + uniqueRow +
            ',event)" class="form-control">'+ products.product_dec + '</textarea>' +
            '<i class="fas fa-edit" data-toggle="modal" data-target="#desc' + uniqueRow + '" onclick="showPop(' +
            uniqueRow + ',event)" title="Product Edit"></i></td>' +
            '                                                        <td style="width:30px;padding:4px;"><input style="border:none;" type="text" id="hsnCode' +
            uniqueRow + '" name="product[' + uniqueRow + '][hsn]" value="' + products.hsn +
            '" class="form-control" / style="width:45px;"></td>\n' +
             <?php

            if ($compProfile->category != "Normal") {
            ?> '<td style="width:120px;padding:4px;">\n' +
                '                                                          <input type="text" style="width:100%;border:none;" name="product[' +
                uniqueRow + '][sqft]"  id="sqft' + uniqueRow +
                '" class="form-control" value="' + products.sqft + '"></td>\n' +
            <?php
            } else {
            ?> '<td hidden style="width:120px;padding:4px;">\n' +
                '                                                          <input type="text" style="width:100%;border:none;" name="product[' +
                uniqueRow + '][sqft]"  id="sqft' + uniqueRow +
                '" class="form-control" value="' + products.sqft + '"></td>\n' +
            <?php
            }
            if ($compProfile->category != "Normal") { ?> '                                                         <td style="width:125px;padding:4px;"><div class="input-group"><input style="border:none;"  type="number" min="1" value="' +
                products.sqft + '" required="true" onchange="onQuantityChangeHandler(event,' + uniqueRow +
                ');" showSerial(event,' + uniqueRow + ');" onkeypress="return EnterPress(event,' + uniqueRow +
                ');" onkeyup="onQuantityChangeHandler(event,' + uniqueRow + ')" id="qty' + uniqueRow +
                '" name="product[' + uniqueRow + '][qty]" class="form-control"/><a href="javascript:void(0);" class="input-group-text" onclick="quantityCalculator(' + uniqueRow + ', event);"><i class="fa fa-calculator"></i></a></div></td>\n' +
            <?php
            } else { ?> '                                                         <td style="width:125px;padding:4px;"><div class="input-group"><input style="border:none;"  type="number" min="1" value="' +
                products.qty + '" required="true" onchange="onQuantityChangeHandler(event,' + uniqueRow +
                ');" showSerial(event,' + uniqueRow + ');" onkeypress="return EnterPress(event,' + uniqueRow +
                ');" onkeyup="onQuantityChangeHandler(event,' + uniqueRow + ')" id="qty' + uniqueRow +
                '" name="product[' + uniqueRow + '][qty]" class="form-control"/><a href="javascript:void(0);" class="input-group-text" onclick="quantityCalculator(' + uniqueRow + ', event);"><i class="fa fa-calculator"></i></a></div></td>\n' +

            <?php } ?> '" name="product[' + uniqueRow + '][qty]" class="form-control"/></td>\n' +
            '                                                        <td style="width:125px;padding:4px;"><input style="border:none;"  type="number" min="0" value="' +
            products.rate + '" id="rate' + uniqueRow + '" name="product[' + uniqueRow +
            '][rate]" required="true" onchange="onRateChangeHandler(event,' + uniqueRow +
            ')" onkeyup="onRateChangeHandler(event,' + uniqueRow + ')"  class="form-control floatNumber"/></td>\n' +
            <?php
            $discount = $this->session->userdata('discount');
            if ($discount == 'yes') {
            ?> '                                                        <td style="width:30px;padding:4px;"><input style="border:none;" type="number" min="0" value="' +
                products.discount + '" id="discount' + uniqueRow + '" name="product[' + uniqueRow +
                '][discount]"  onchange="onDiscountHandler(event,' + uniqueRow + ')" onkeyup="onDiscountHandler(event,' +
                uniqueRow + ')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +
            <?php
            } else {
            ?> '<td hidden style="width:30px;padding:4px;"><input style="border:none;" type="text" min="0" value="' +
                products.discount + '" id="discount' + uniqueRow + '" name="product[' + uniqueRow +
                '][discount]"  onchange="onDiscountHandler(event,' + uniqueRow + ')" onkeyup="onDiscountHandler(event,' +
                uniqueRow + ')" class="form-control floatNumber" style="width:45px;float:left;"/></td>\n' +

            <?php
            } ?> '                                                        <td style="display:none;"><input  type="text" style="border:none;" min="0" value="0" id="cd' +
            uniqueRow + '" name="product[' + uniqueRow + '][cd]"  onchange="onCDHandler(event,' + uniqueRow +
            ')" onkeyup="onCDHandler(event,' + uniqueRow +
            ')" class="form-control floatNumber" style="width:70px;float:left;"/></td>\n' +
            '                                                        <td style="width:81px;padding:4px;"><div id="taxal' +
            uniqueRow + '"><input style="border:none;" style="width:66px;" type="number" value="' + products.tax +
            '" min="0"  id="tax' + uniqueRow + '"  name="product[' + uniqueRow +
            '][tax]" class="form-control"/></div></td>\n' +
            '                                                        <td style="width:125px;padding:4px;"><input style="border:none;" type="text" value="' +
            products.purchase_price + '" readonly name="product[' + uniqueRow + '][price]" id="price' + uniqueRow +
            '" onkeyup="onPriceChangeHandler(event,' + uniqueRow +
            ')" required="true" class="form-control bg-light" ></td>\n' +
            '                                                        <td style="width:125px;padding:4px;"><input style="border:none;"  type="text" min="0" value="' +
            products.amount + '" readonly id="amount' + uniqueRow + '" name="product[' + uniqueRow +
            '][amount]" class="form-control bg-light"/>' +
            '                                                        <input  type="hidden" min="0" value="' +
            products.taxAmount + '" readonly id="taxAmount' + uniqueRow + '" name="product[' + uniqueRow +
            '][taxAmount]" class="form-control"/>' +
            '                                                        <input  type="hidden" min="0" value="' +
            products.sgst + '" readonly id="sgst' + uniqueRow + '" name="product[' + uniqueRow +
            '][sgst]" class="form-control"/>' +
            '                                                        <input  type="hidden" min="0" value="' +
            products.cgst + '" readonly id="cgst' + uniqueRow + '" name="product[' + uniqueRow +
            '][cgst]" class="form-control"/>' +
            '                                                        <input  type="hidden" min="0" value="' +
            products.igst + '" readonly id="igst' + uniqueRow + '" name="product[' + uniqueRow +
            '][igst]" class="form-control"/></td>\n' +
            '                                                        <td style="width:140px;padding:4px;"><input  type="number" style="border:none;" min="0" value="' +
            products.netAmount + '" readonly id="netAmount' + uniqueRow + '" name="product[' + uniqueRow +
            '][netAmount]" class="form-control bg-light add-new-row" onkeydown="onNewRowAdd(event)"/></td>\n' +
            '<td style="padding:4px;width:100px;">' +
            '<a href="javascript:void(0);" onclick="onAddNewRowHandler()">'+
            '<i class="fa fa-plus-circle text-success cp" title="Add new row"></i>'+
            '</a>'+
            '&nbsp;&nbsp;'+
            '<a href="javascript:void(0);" onclick="onRemoveRowHandler(' + uniqueRow + ')">'+
            '<i class="fa fa-minus-circle text-danger cp" title="Remove selected row"></i>'+
            '</a>' +
            '<span id="editProduct' + uniqueRow + '">'+
            '<a href="javascript:void(0);" onclick="productForEdit(' + uniqueRow + ',' + products.product_id + ')">'+
            '<i class="fas fa-edit" style="width:41px;" title="Product Edit"></i>'+
            '</a>'+
            '</span>' +
            '<a href="javascript:void(0);" class="text-warning" onclick="getWarehouseForProduct('+uniqueRow+', '+products.id+');" title="Warehouse">'+
            '<i class="fa fa-home"></i>'+
            '</a>'+
            '</td>' +
            '</tr>');

            ProductDataArray.push(products.p_code);
            ProductDataArray2.push({barcode: products.p_code, price: products.rate, rowNumber: uniqueRow});


        let formats = $("#formates").val();
        if (formats == 'Estimate') {
            $("#tax" + uniqueRow).val(0);

            var tax = 0;
            var tax1 = 0;
            getTaxData(tax1, uniqueRow);
            // alert("formaes");
            // document.getElementById("tax" + rowNo).disabled = true;
            $("#tax" + uniqueRow).prop("disabled", true);
            $("#rate" + uniqueRow).trigger("change");


        } else {
            var tax = products.tax;
            var tax1 = products.tax;
            // document.getElementById("tax" + rowNo).disabled = false;
            $("#tax" + uniqueRow).prop("disabled", false);
            getTaxesForGettingInPreview(tax1, uniqueRow);
            $("#tax" + uniqueRow).val(tax);
            // alert(tax);
            $("#tax" + uniqueRow).trigger("change");
        }
        if (uniqueRow >= 1) {
            allUniqueRows.push(uniqueRow);
            console.log(allUniqueRows);
            ++uniqueRow;

        }

    }





    const getStateSelect = (id, type = "Sale") => {
        const stateid = id;
        // alert(cityid);
        // alert(name);

        if (stateid != null) {
            getStateName(stateid);

            document.getElementById('stateSelect').value = stateid;
            document.getElementById('exampleModalLong3').click();
            document.getElementById("customerBalance").focus()

            // alert(cityid)
        } else {
            // alert("hello");
            document.getElementById('customerCity').value = "";
            document.getElementById('cityCustomer').value = "";
            document.getElementById("customerBalance").focus()


        }
    }






    const opBalnceType = (id, i) => {
        $.ajax({
            method: "GET",
            url: baseurl + "index.php/party/opBalnceType/" + id,
            success: function(product) {
                document.getElementById('opBalnceType' + i).innerHTML = product;
            }
        })
    }






    const getCitySelect = (id, type = "Sale") => {
        const cityid = id;
        // alert(cityid);
        // alert(name);

        if (cityid != null) {
            getCityName(cityid);
            document.getElementById('customerCity').value = cityid;
            document.getElementById('exampleModalLong2').click();
            document.getElementById("customerMobile").focus()

            alert(cityid)
        } else {
            // alert("hello");
            document.getElementById('exampleModalLong2').click();
            document.getElementById('customerCity').value = "";
            document.getElementById('cityCustomer').value = "";
            document.getElementById("customerMobile").focus()


        }
    }

    const getCityName = (id, city_name = null) => {

        $.ajax({
            method: "GET",
            url: baseurl + "index.php/product/getName/" + id,
            success: function(product) {
                var product = JSON.parse(product);
                // console.log(product.data);
                if (product.status == true) {
                    var product = product.data;
                    // console.log(product);
                    document.getElementById('cityCustomer').value = product.name;
                } else {
                    document.getElementById('cityCustomer').value = city_name;

                }

            }
        })
    }





    jQuery(document).bind("keyup keydown", function(e) {
        if (e.ctrlKey && e.keyCode == 80) {
            printSale();

            return false;
        }
    });

    jQuery(document).bind("keyup keydown", function(e) {
        var x = event.which || event.keyCode;
        if (e.ctrlKey && e.keyCode == 100) {
            // printSale();
            deleteRow();


            return false;
        }
    });

    document.addEventListener("keydown", function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
            <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
            e.preventDefault();
            // Process the event here (such as click on submit button)
            onFormSubmitHandler(event);
            <?php } else { ?>
            alert("Your license has been expired, Please contact your administrator.");
            <?php } } else { ?>
            e.preventDefault();
            // Process the event here (such as click on submit button)
            onFormSubmitHandler(event);            
            <?php } ?>
        }
    }, false);
    
    const ProductDataArray = [];
    const ProductDataArray2 = [];

    const onProductCodeEnter = (rowNo, e, type = 'Sale') => {
        if (e.keyCode == 13) {
            e.preventDefault();
            var productId = e.target.value;
            if (productId == "" || productId == null) {
                document.getElementById('productname' + rowNo).focus();
                return;
            }

            $.ajax({
                method  :   "GET",
                url     :   baseurl + "productcode/getProductCode/" + productId,
                success :   function(product) {
                                var productJson = JSON.parse(product);
                                if (productJson.status == true) {
                                    var productData = productJson.data;
                                    if (productJson.BarCodeStatus == "Y") { 
                                        window.setTimeout(function() {
                                            if($.inArray(productData.qrCode, ProductDataArray) == -1) {
                                                ProductDataArray.push(productData.qrCode);
                                                ProductDataArray2.push({barcode: productData.qrCode, price: parseFloat(productData.price).toFixed(2), rowNumber: rowNo});
                                                setSelectedProductDetailsForBarcode(rowNo, productData, type);
                                                onAddNewRowHandler();
                                                rowNo = allUniqueRows[allUniqueRows.length - 1] + 1;
                                                window.setTimeout(function() {
                                                    $('#p_code' + rowNo).focus();
                                                }, 1000);
                                            } else {
                                                ProductDataArray2.forEach(function(Items) {
                                                    if (productData.qrCode == Items.barcode && parseFloat(productData.price).toFixed(2) == Items.price) {
                                                        var qty = $("#qty"+Items.rowNumber).val();
                                                        var NewQty = Number(qty) + Number(1);
                                                        $("#qty"+Items.rowNumber).val(NewQty);
                                                        onQuantityChangeHandler(e, Items.rowNumber, type);
                                                        $('#p_code' + rowNo).val("");
                                                        return false;
                                                    } else if (productData.qrCode == Items.barcode && parseFloat(productData.price).toFixed(2) != Items.price) {
                                                        setSelectedProductDetailsForBarcode(rowNo, productData, type);
                                                        onAddNewRowHandler();
                                                        rowNo = allUniqueRows[allUniqueRows.length - 1] + 1;
                                                        window.setTimeout(function() {
                                                            $('#p_code' + rowNo).focus();
                                                        }, 1000);
                                                    }
                                                });
                                            }
                                        }, 1000);
                                    } else {
                                        var productData = productJson.data;
                                        setSelectedProductDetails(rowNo, productData, type);
                                        onAddNewRowHandler();
                                        rowNo = allUniqueRows[allUniqueRows.length - 1] + 1;
                                        window.setTimeout(function() {
                                            $('#p_code' + rowNo).focus();
                                        }, 1000);    
                                    }
                                } else {
                                    document.getElementById('p_code' + rowNo).value = '';
                                    document.getElementById('qty' + rowNo).value = 1;
                                    document.getElementById('rate' + rowNo).value = 0;
                                    document.getElementById('netAmount' + rowNo).value = 0;
                                    document.getElementById('price' + rowNo).value = 0;
                                    document.getElementById('amount' + rowNo).value = 0;
                                    document.getElementById('productId' + rowNo).value = '';
                                    document.getElementById('productname' + rowNo).value = '';
                                    document.getElementById('productname' + rowNo).focus();
                                    manipulateRHSData();
                                }
                            }
            });
        }
    };

    const getDescriptionOnly = () => {
        var rowNumber = document.getElementById('description_for_id');
        if (rowNumber != null) {
            rowNumber = rowNumber.value;
        } else {
            rowNumber = "";
        }
        var contents = document.getElementById('description_for');
        // alert(contents.value);
        if (contents != null) {
            contents = contents.value;
        } else {
            contents = "";
        }

        document.getElementById('product_dec' + rowNumber).innerHTML = contents;


    }



    const discounttypeHandler = (e, rowNumber) => {
        discount_type = e.target.value;
        $("#discount" + rowNumber).trigger("change");
    };


    const calculateTaxAmount = (amount, taxPercent) => {

        let taxAmount = (amount * taxPercent) / 100;
        return taxAmount;
    };

    const calculateTaxAmountss = (amount, taxPercent) => {

        let taxAmount = (amount * taxPercent) / 100;
        return taxAmount;
    };

    function calculateGST(tax, amount, rowNo) {
        var sgstPer = 0;
        var cgstPer = 0;
        var igstPer = 0;

        let stateCode = document.getElementById('stateSelect').value;
        let taxamounts = document.getElementById('amount' + rowNo).value;

        if (stateCode == <?= $this->session->userdata('state'); ?>) {
            sgstPer = tax / 2;
            cgstPer = tax / 2;
        } else {
            igstPer = tax;
        }

        var sgst = calculateTaxAmount(amount, sgstPer);
        var cgst = calculateTaxAmount(amount, cgstPer);
        var igst = calculateTaxAmount(amount, igstPer);

        $("#sgst" + rowNo).val(parseFloat(sgst).toFixed(2));
        $("#cgst" + rowNo).val(parseFloat(cgst).toFixed(2));
        $("#igst" + rowNo).val(parseFloat(igst).toFixed(2));
    }


    function calculateGSTwithall(tax, amount, rowNo) {
        var sgstPer = 0;
        var cgstPer = 0;
        var igstPer = 0;

        let stateCode = document.getElementById('stateSelect').value;
        let taxcal = document.getElementById('taxcal').value;
        if (taxcal == 2) {
            var amount = document.getElementById('price' + rowNo).value;
        }
        
        if (stateCode == <?= $this->session->userdata('state'); ?>) {
            sgstPer = tax / 2;
            cgstPer = tax / 2;
        } else {
            igstPer = tax;
        }

        var sgst = calculateTaxAmount(amount, sgstPer);
        var cgst = calculateTaxAmount(amount, cgstPer);
        var igst = calculateTaxAmount(amount, igstPer);

        $("#sgst" + rowNo).val(parseFloat(sgst).toFixed(2));
        $("#cgst" + rowNo).val(parseFloat(cgst).toFixed(2));
        $("#igst" + rowNo).val(parseFloat(igst).toFixed(2));
    }

    const onNewRowAdd = (e) => {
        if (e.keyCode === 13) {
            onAddNewRowHandler();
        }
    };


    const onAddNewRowHandler = () => {
        getProducts();

    }


    function IsNumeric(evt) {
        evt = evt ? evt : window.event;
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function ChangeTax(e, rowNumber, type = "Sale") {
        const tax = e;
        $('#tax' + rowNumber).val(tax);
        var cd = $("#cd" + rowNumber).val();
        var qty = $("#qty" + rowNumber).val();
        var purchasre = $("#rate" + rowNumber).val();
        var discount = $("#discount" + rowNumber).val();
        var taxcal = $("#taxcal").val();
        var rate = calculateRate(rowNumber, type);
        rate = (rate * (100 - discount)) / 100;
        rate = (rate * (100 - cd)) / 100;
        rate = rate * qty;

        let amount = purchasre;
        $("#price" + rowNumber).val(rate.toFixed(3));
        const taxPercent = $("#tax" + rowNumber).val();
        const taxAmount = calculateTaxAmount(rate, taxPercent);
        $("#taxAmount" + rowNumber).val(taxAmount.toFixed(3));
        $("#amount" + rowNumber).val(taxAmount.toFixed(3));
        
        calculatePrice(rowNumber, type);
        calculateGST(taxPercent, amount, rowNumber);
        manipulateRHSData();
        
        $("#rate" + rowNumber).trigger("change");
        onRateChangeHandler(rate, rowNumber);
    };

    function onPriceChangeHandler() {
        return;
    }
    
    const onDiscountHandler = (e, rowNumber, type = "Sale") => {
        var discount = e.target.value;
        var cd = $("#cd" + rowNumber).val();
        let qty = $("#qty" + rowNumber).val();
        let rate = calculateRate(rowNumber, type);
        //(rate, "discount_rate");
        const taxPercent = $("#tax" + rowNumber).val();
        let discountAmt = "";
        if (discount > 100) {
            Swal.fire({
                position: "top-end",
                title: "Invalid Discount",
                text: "Discount can't be greater than 100%",
                icon: "warning",
                showConfirmButton: false,
                timer: 2000,
            });
            discount = 0;
            $("#discount" + rowNumber).val("0");
        }
        $("#qty" + rowNumber).trigger("change");
    };


    function EnterPress(event, rowNo) {
        if (event.keyCode == 13) {

            onAddNewRowHandler();
            // document.getElementById("qty" + rowNo).disabled = true;
            var firstrow = rowNo;
            // rowNo = rowNo + 1;
            rowNo = allUniqueRows[allUniqueRows.length - 1] + 1;

            setTimeout(function() {
                <?php
                $barcode = $this->session->userdata('barcode');
                if (isset($barcode) && !empty($barcode) && $barcode == 'yes') {
                ?>
                    document.getElementById('p_code' + rowNo).focus();
                    // document.getElementById("qty" + firstrow).disabled = false;

                <?php
                } else {
                ?>
                    // document.getElementById("qty" + firstrow).disabled = false;

                    document.getElementById('productname' + rowNo).focus();

                <?php

                } ?>
            }, 500);

            return false;

            // return true;
            // forProductCode(rowNo);


        }
    }

        const calculatePrice = (rowNumber, type) => {
          // Get all input values
          const qty = parseFloat($(`#qty${rowNumber}`).val()) || 0;
          const rate = parseFloat($(`#rate${rowNumber}`).val()) || 0;
          const discount = parseFloat($(`#discount${rowNumber}`).val()) || 0;
          const cd = parseFloat($(`#cd${rowNumber}`).val()) || 0; // Cash discount
          const taxPercent = parseFloat($(`#tax${rowNumber}`).val()) || 0;
          const formates = $("#formates").val();
          const taxcal = $("#taxcal").val(); // 1=inclusive, 2=exclusive

          // Step 1: Get base rate (extract GST if inclusive)
          let baseRate = rate;
          if (taxcal == 1) { // Inclusive
            baseRate = (rate / (taxPercent / 100 + 1)).toFixed(3);
          }

          // Step 2: Apply discount
          const afterDiscount = baseRate * (1 - discount / 100);

          // Step 3: Apply cash discount (cd)
          const afterCashDiscount = afterDiscount * (1 - cd / 100);

          // Step 4: Calculate amounts
          const baseAmount = afterCashDiscount * qty;
          const taxAmount = (baseAmount * taxPercent) / 100;
          const netAmount = baseAmount + taxAmount;

          // Step 5: Update UI fields
          if (formates === 'Estimate') {
            $(`#price${rowNumber}`).val(0);
            $(`#tax${rowNumber}`).val(0);
            $(`#tax${rowNumber}`).prop("disabled", true);
          } else {
            $(`#price${rowNumber}`).val(baseAmount.toFixed(3));
          }

          $(`#taxAmount${rowNumber}`).val(taxAmount.toFixed(3));
          $(`#amount${rowNumber}`).val(taxAmount.toFixed(3));
          $(`#netAmount${rowNumber}`).val(netAmount.toFixed(2));
        };

        const onCDHandler = (e, rowNumber, type = "Sale") => {
          // Get all input values
          const cd = parseFloat(e.target.value) || 0;
          const qty = parseFloat($(`#qty${rowNumber}`).val()) || 0;
          const rate = parseFloat($(`#rate${rowNumber}`).val()) || 0;
          const discount = parseFloat($(`#discount${rowNumber}`).val()) || 0;
          const taxPercent = parseFloat($(`#tax${rowNumber}`).val()) || 0;
          const formates = $("#formates").val();
          const taxcal = $("#taxcal").val(); // 1=inclusive, 2=exclusive

          // Validate CD
          if (cd > 100) {
            Swal.fire({
              position: "top-end",
              title: "Invalid CD",
              text: "CD can't be greater than 100%",
              icon: "warning",
              confirmButtonColor: "#3085d6",
              showConfirmButton: false,
              timer: 2000,
            });
            $(`#cd${rowNumber}`).val("0");
            return;
          }

          // Step 1: Get base rate (extract GST if inclusive)
          let baseRate = rate;
          if (taxcal == 1) { // Inclusive
            baseRate = (rate / (taxPercent / 100 + 1)).toFixed(3);
          }

          // Step 2: Apply discount
          const afterDiscount = baseRate * (1 - discount / 100);

          // Step 3: Apply cash discount (cd)
          const afterCashDiscount = afterDiscount * (1 - cd / 100);

          // Step 4: Calculate amounts
          const baseAmount = afterCashDiscount * qty;
          const taxAmount = (baseAmount * taxPercent) / 100;
          const netAmount = baseAmount + taxAmount;

          // Step 5: Update UI fields
          if (formates === 'Estimate') {
            $(`#price${rowNumber}`).val(0);
          } else {
            $(`#price${rowNumber}`).val(baseAmount.toFixed(3));
          }

          $(`#amount${rowNumber}`).val(baseAmount.toFixed(3));
          $(`#taxAmount${rowNumber}`).val(taxAmount.toFixed(3));
          $(`#netAmount${rowNumber}`).val(netAmount.toFixed(2));

          // Additional calculations
          calculateGST(taxPercent, baseAmount, rowNumber);
          manipulateRHSData();
          $("#expenses").val(0);
        };
        
    const a = [
        "",
        "One ",
        "Two ",
        "Three ",
        "Four ",
        "Five ",
        "Six ",
        "Seven ",
        "Eight ",
        "Nine ",
        "Ten ",
        "Eleven ",
        "Twelve ",
        "Thirteen ",
        "Fourteen ",
        "Fifteen ",
        "Sixteen ",
        "Seventeen ",
        "Eighteen ",
        "Nineteen ",
    ];
    const b = [
        "",
        "",
        "Twenty",
        "Thirty",
        "Forty",
        "Fifty",
        "Sixty",
        "Seventy",
        "Eighty",
        "Ninety",
    ];
    const numberToWords = (num) => {
        if ((num = num.toString()).length > 9) return "overflow";
        let n = ("000000000" + num)
            .substr(-9)
            .match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        var str = "";
        str +=
            n[1] != 0 ?
            (a[Number(n[1])] || b[n[1][0]] + " " + a[n[1][1]]) + "Crore " :
            "";
        str +=
            n[2] != 0 ?
            (a[Number(n[2])] || b[n[2][0]] + " " + a[n[2][1]]) + "Lakh " :
            "";
        str +=
            n[3] != 0 ?
            (a[Number(n[3])] || b[n[3][0]] + " " + a[n[3][1]]) + "Thousand " :
            "";
        str +=
            n[4] != 0 ?
            (a[Number(n[4])] || b[n[4][0]] + " " + a[n[4][1]]) + "Hundred " :
            "";
        str +=
            n[5] != 0 ?
            (str != "" ? "And " : "") +
            (a[Number(n[5])] || b[n[5][0]] + " " + a[n[5][1]]) +
            "Only " :
            "";
        return str;
    };


    const onRemoveRowHandlers = (rowNo) => {
        // allUniqueRows.remove(rowNo);
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();

        manipulateRHSData();
    };

    const onRemoveRowHandler = (rowNo) => {
        if ($("#productId" + rowNo).val()) {
            var PreviousItemCount = $("#num_of_items").val();
            var NewItemCount = Number(PreviousItemCount) - 1;
            $("#num_of_items").val(NewItemCount);
        };
        
        // var row = $("#tr" + rowNo).remove();
        //it was not working on first click so i have clicked it four time bcz its working on click at four time 
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();
        $("#tr" + rowNo).remove();


        let rowCount = $("#dataTable tbody tr").length;
        if (rowCount == 0) {
            onAddNewRowHandler();
            let res = arrayRemove(allUniqueRows, rowNo);
            console.log(res);
            for (var i = 0; i < res.length; i++) {
                console.log(res[i]);

            }
            if (res) {
                while (allUniqueRows.length > 0) {
                    allUniqueRows.pop();

                }
            }
        } else {
            let res = arrayRemove(allUniqueRows, rowNo);
            console.log(res, "result of rows");
            if (res) {
                while (allUniqueRows.length > 0) {
                    allUniqueRows.pop();
                }
            }

            if (res) {
                for (i = 0; i < res.length; i++) {
                    allUniqueRows.push(res[i]);
                }
            }
            console.log(allUniqueRows);
            // document.getElementById("expenses").focus();


        }
        manipulateRHSData();
    }

    function arrayRemove(arr, value) {

        return arr.filter(function(ele) {
            return ele != value;
        });
    }
    
    const manipulateRHSData = () => {
        let taxableAmount = 0;
        let totalTax = 0;
        let totalValue = 0;
        let roundOff = 0;
        let total = 0;
        let totalsgst = 0;
        let totalcgst = 0;
        let totaligst = 0;
        const rows = $("#dataTable").children("tbody").children("tr");
        rows.map(function(row) {
            let row1 = $("#dataTable").children("tbody").children("tr")[row];
            const trId = row1.getAttribute("id");
            const rowNumber = trId.slice(2, trId.length);
            const taxAmount = $("#taxAmount" + rowNumber).val();
            const netAmount = $("#netAmount" + rowNumber).val();
            const sgst = $("#sgst" + rowNumber).val();
            const cgst = $("#cgst" + rowNumber).val();
            const igst = $("#igst" + rowNumber).val();
            const amount = $("#amount" + rowNumber).val();
            const price = $("#price" + rowNumber).val();

            if (taxAmount != null && taxAmount != "") {
                taxableAmount += parseFloat(price);
                totalTax += parseFloat(amount);
                totalValue += parseFloat(netAmount);
            }

            totalsgst += parseFloat(sgst);
            totalcgst += parseFloat(cgst);
            totaligst += parseFloat(igst);
        });

        total=Math.round(totalValue).toFixed(2);
        roundOffVal = (totalsgst + totalcgst + totaligst + taxableAmount) - total;
        roundOff = Math.abs(roundOffVal);
        $("#taxableAmount").val(taxableAmount.toFixed(3));
        $("#totalTax").val(totalTax.toFixed(3));
        $("#amount").val(totalTax.toFixed(3));
        $("#totalValue").val(totalValue.toFixed(2));
        $("#roundOff").val(roundOff == "" || isNaN(roundOff) ? 0 : roundOff.toFixed(2));
        $("#total").val(Math.round(totalValue).toFixed(2));
        $("#total_amount_for").val(Math.round(totalValue).toFixed(2));
        $("#amountInWords").val(numberToWords(parseInt(totalValue)));
        $("#totalsgst").val(totalsgst.toFixed(3));
        $("#totalcgst").val(totalcgst.toFixed(3));
        $("#totaligst").val(totaligst.toFixed(3));

        var formats = document.getElementById('formats').value;
        if (formats == 'Estimate') {
            $("#taxAmount").val(0);
        }
    };

    const getTaxData = (tax_id, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: tax_id,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>tax/gettax",
            success: function(invoiceNo) {
                $("#taxal" + rowNum).html(invoiceNo);
            },

        });
    }

    const getTaxesForGettingInPreview = (tax_id, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: tax_id,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>tax/getTaxesForGettingInPreview",
            success: function(invoiceNo) {
                $("#taxal" + rowNum).html(invoiceNo);
            },

        });
    }

    const getTaxDatas = (tax_id, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: tax_id,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>tax/gettaxs",

            success: function(invoiceNo) {

                $("#protax" + rowNum).html(invoiceNo);

                // invoiceData = invoiceNo.data;
                // cosnole.log(invoiceData);


            },

        });
    }

    const getUnitDatas = (unit_id, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: unit_id,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>tax/getunit",

            success: function(invoiceNo) {

                $("#unit" + rowNum).html(invoiceNo);

                // invoiceData = invoiceNo.data;
                // cosnole.log(invoiceData);


            },

        });
    }
    const getProductsCode = (procode, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: procode,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>productcode/getProductsCode",
            success: function(productCode) {
                //(productCode, "productcode");
                $("#productcode" + rowNum).html(productCode);

                // invoiceData = invoiceNo.data;
                // cosnole.log(invoiceData);


            },

        });
    }

    const getProductName = (pro_id, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: pro_id,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>product/pro_name",

            success: function(invoiceNo) {

                $("#pro_names" + rowNum).html(invoiceNo);

            },

        });
    }





    const getProductCodeDetails = (procode, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: procode,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>productcode/productCodeInfo",

            success: function(procodess) {

                $("#productcode" + rowNum).html(procodess);

                // invoiceData = invoiceNo.data;
                // cosnole.log(invoiceData);




            },

        });
    }

    const CheckSaleInvoiceNumber = (InvoiceNumber) => {
        return $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>proforma/checkProformaInvoiceNumber",
            data    :   {InvoiceNumber: InvoiceNumber},
            async   :   false,
            success :   function(Response) {
                            if (JSON.parse(Response).status == true) {
                                JSON.parse(Response);
                            }
                        }
        }).responseText;
    };

    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var InvoiceNumber = $("#invoiceNumber").val();
        if (InvoiceNumber != null && $("#invoiceNumber").length < 1) {
            Swal.fire({
                type: 'fail',
                title: "Invoice Number is required"
            });
            $("#btnSubmit").prop("disabled", false);
            return true;
        }
        
        if (JSON.parse(CheckSaleInvoiceNumber(InvoiceNumber)).status == true) {
            if (!confirm(InvoiceNumber + ' This invoice number already saved, do you want continue with next invoice number?')) {
                return false;
            }
        }
        
        var invoicedate = $("#invoiceDate").val();
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear() + "-" + (month) + "-" + (day);

        if (invoicedate != today) {
            var message = confirm("Are you sure proforma date select is " + invoicedate + " want to continue.");
            if (message) {
                let buttons = '<button id="btnSaveAndPrint" class="btn btn-secondary mr-2"  onkeyup="chnageDirections(event)" onkeydown="chnageDirections(event)" onclick="saveInvoice(2)">Save & Print</button>';
                    buttons += '<button id="btnSaveCancel" class="btn btn-default mr-2"  onkeyup="chnageDirectionss(event)" onkeydown="chnageDirectionss(event)" onclick="swalclose()">Cancel</button></div>';
                Swal.fire({
                    title: 'Are you sure to save proforma ?',
                    text: "This will create an proforma.",
                    html: buttons,
                    showConfirmButton: false,
                    showCancelButton: false,
                    confirmButtonColor: '#009a1a',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                });
            } else {
                return false;
            } 
        }

        let buttons = '<button id="btnSaveAndPrint" class="btn btn-secondary mr-2"  onkeyup="chnageDirections(event)" onkeydown="chnageDirections(event)" onclick="saveInvoice(2)">Save & Print</button>';
            buttons += '<button id="btnSaveCancel" class="btn btn-default mr-2"  onkeyup="chnageDirectionss(event)" onkeydown="chnageDirectionss(event)" onclick="swalclose()">Cancel</button></div>';
        Swal.fire({
            title: 'Are you sure to save proforma ?',
            text: "This will create an proforma.",
            html: buttons,
            showConfirmButton: false,
            showCancelButton: false,
            confirmButtonColor: '#009a1a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!'
        });
    };

    const swalclose = () => {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: "Cancelled",
            timer: 2000
        });


    }


    const saveInvoice = (saveAction) => {
        var saleValue = document.getElementById('printSale').value;
        if (saleValue == 0) {
            $("#saveAction").val(saveAction);

            // $("#taxcal").trigger("change");
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>proforma/save",
                data: $("#dataForm").serialize(),
                success: function(invoiceNo) {

                    invoiceNo = JSON.parse(invoiceNo);
                    if (invoiceNo.status == true) {
                        invoiceData = invoiceNo.data;
                        // cosnole.log(invoiceData);
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Proforma saved successfully.",
                            showConfirmButton: false,
                            timer: 2000
                        })
                        if (saveAction == 1) { //Save Only
                            setTimeout(function() {
                                //window.location.reload()
                                window.location.replace('proforma');
                            }, 2000);
                        } else if (saveAction == 2) { 
                            <?php
                    if ($compProfile->invoicePayment == 'Enable') {
                    ?>
                        $("#updateSalePayment").modal('show');
                        $("#saleIdForUpdate").val(invoiceData);
                    <?php
                    } else {
                    ?>
                        setTimeout(function() {
                            redirectToPrint(invoiceData)
                        }, 2000)
                    <?php
                    } ?>

                            
                        }
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: invoiceNo.msg,
                            showConfirmButton: false,
                            timer: 2000
                        })
                        document.getElementById('invoiceNumber').focus();
                    }
                    $("#btnSubmit").prop("disabled", false);

                },

            });
        } else {
            var url = "<?= base_url() ?>proforma/update";
            updateInvoiceMail(url);

        }
    }
    
        $("#updatePaymentMode").on('submit', function(e) {
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
                    $("#updateSalePayment").modal("hide");
                    setTimeout(function() {
                        redirectToPrint(response.data)
                    }, 2000);

                } else {
                    toastr.error(response.message);

                }


            }
        });
    })


    function closePaymentModel() {
        $("#updateSalePayment").modal("hide");
        let saleId = $("#saleIdForUpdate").val();
        redirectToPrint(saleId);

    }

    const updateInvoice = () => {
        //console.info($("#dataForm").serialize())
        var printValue = document.getElementById('printSale').value;
        // printValue=1+parseFloat(printValue);
        // alert(printValue);
        var invoiceNumber = document.getElementById('invoiceNumber').value;
        // if(invoiceNumber)

        if (printValue == 0) {
            // alert('ENter First Invocie Number That you Want to Update');
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Enter Invoice First",
                showConfirmButton: false,
                timer: 2000,
            });
            return;
        }
        var url = "<?= base_url() ?>proforma/update";
        //(url, "url");
        Swal.fire({
            title: "Are you sure to update?",
            text: "You won't be able to revert this!",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "success",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Update it!",
        }).then((result) => {
            if (result.isConfirmed) {
                updateInvoiceMail(url);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });

    }



    const onProductFocusChange = (e, rowNo) => {
        if (e.target.value.length >= 1) {
            const row = document.getElementById("tr" + rowNo);
            row.parentNode.removeChild(row);
            document.getElementById("expenses").focus();
            //Add A Blank Row if table is empty
            let rowCount = $("#dataTable tbody tr").length;
            if (rowCount < 1) {
                onAddNewRowHandler();
                document.getElementById("party_id").focus();
            }
        }
    };

    const redirectToPrint = (invoiceNo) => {
        var POS = document.getElementById("pos").checked;
        if (POS) {
            window.open(
                '<?= base_url(); ?>proforma/POS_1/' + invoiceNo + '',
            );
            window.location.href = '<?= base_url(); ?>proforma';
        } else {
            window.open(
                '<?= base_url(); ?>proforma/print/' + invoiceNo + '',
            );
            window.location.href = '<?= base_url(); ?>proforma';            
        }
    };
    
    const redirectToPrints = (invoiceNo) => {
        window.open(
            '<?= base_url(); ?>proforma/preview/' + invoiceNo + '',
            '_blank' // <- This is what makes it open in a new window.
        );
        window.location.href = '<?= base_url(); ?>proforma';

    }



    function save_serial(currentrowid) {
        var qty = $('#qty' + currentrowid).val();
        $('#product_serial' + currentrowid).html('');
        for (var s = 1; s <= qty; s++) {
            //  alert($('#serial' + s).val());
            var val = $('#serial' + currentrowid + s).val();
            $('#product_serial' + currentrowid).append('<input id="serial' + currentrowid + '' + s +
                '" type="text" value="' + val + '" class="form-control" name="product[' + currentrowid +
                '][item_serialno][]" style="margin-bootom:5px;" />');
        }

        // $('#product_serial' + currentrowid).html($('#serialdiv').html());
        $('#serialModal').modal('hide');
        $('#rate' + currentrowid).focus();
    }
</script>
<script>
    function onUserFormSubmitHandler(e) {
        e.preventDefault();
        // $(".searchselect").select2();
        var url = $("#addUserForm").attr("action");
        var form = $("#addUserForm")[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                // $(".searchselect").select2();
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    // closeModel();
                    // window.location.href = "<?= base_url() ?>invent/sale";
                    // location.reload();
                    // alert(customerList.data.id);
                    alert("party Created");
                    document.getElementById("reset").click();
                    document.getElementById("custClose").click();
                    // $("#party_id").trigger("change");
                    // getPartyList();
                    $("#party_id").val(customerList.data.id);
                    getCustomerDetail(customerList.data.id, 0);

                    // $("#party_id").trigger("change");
                    // $("#party_id").trigger("change");
                    // //(customerList.data.id, "id");
                    // prepareCustomerDropdown(customerList.data);


                } else {
                    $("#partyMsg").show();
                    $("#partyMsg").addClass("text");
                    $("#partyMsg").addClass("text-danger");
                    $("#partyMsg").html(customerList.msg);
                    // setTimeout(function() {
                    // 	$("#partyMsg").hide();
                    // }, 3000);
                }
            },

        });
    }

    function onProductSubmitHandler(e, row) {
        e.preventDefault();
        // $(".searchselect").select2();
        var url = $("#productEditData" + row).attr("action");
        var form = $("#productEditData" + row)[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                // $(".searchselect").select2();
                customerList = JSON.parse(customerList);
                // console.log(customerList.status);
                if (customerList.status == true) {
                    // alert("huu");
                    document.getElementById('proclick' + row).click();
                    // partyProfileEdit('hiddendata' + row,partyId,event);
                    alert("Product Updated");
                    document.getElementById('protr' + row).focus();

                    var start = document.getElementById('protr' + row);
                    //  = document.getElementById('trp' + rowId);
                    // console.log(start, "without js")
                    start.style.backgroundColor = 'blue';
                    start.style.color = 'white';

                } else {
                    $("#errormsg" + row).show();
                    $("#errormsg" + row).addClass("text");
                    $("#errormsg" + row).addClass("text-danger");
                    $("#errormsg" + row).html(customerList.msg);
                    // setTimeout(function() {
                    // 	$("#partyMsg").hide();
                    // }, 3000);
                }
            },

        });
    }

    function party_wala_data() {
        var url = $("#adpartyform").attr("action");
        var form = $("#adpartyform")[0];
        var data = new FormData(form);
        // console.log(data, "sdfghjkl");
    }


    function onFormPartySubmitHandler(e) {
        e.preventDefault();
        // $(".searchselect").select2();
        var url = $("#adpartyform").attr("action");
        var form = $("#adpartyform")[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                // $(".searchselect").select2();
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    alert("party updated");
                    document.getElementById("exampleModal07").click();
                    $("#party_id").trigger("change");
                    $("#party_id").val(customerList.data.id);
                    $("#partyname").val(customerList.data.name);


                } else {
                    $("#errormsg").show();
                    $("#errormsg").addClass("text");
                    $("#errormsg").addClass("text-danger");
                    $("#errormsg").html(customerList.msg);
                    // setTimeout(function() {
                    // 	$("#partyMsg").hide();
                    // }, 3000);
                }
            },

        });
    }

    function onFormPartySubmitHandlers(e, i) {
        e.preventDefault();
        // $(".searchselect").select2();
        var url = $("#fom" + i).attr("action");
        var form = $("#fom" + i)[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                // $(".searchselect").select2();
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    // partyProfileEdit('hiddendata' + row,partyId,event);
                    alert("Party Updated");
                    document.getElementById('clik' + i).click();

                    document.getElementById('trp' + i).focus();

                    var start = document.getElementById('trp' + i);
                    //  = document.getElementById('trp' + rowId);
                    // console.log(start, "trpstart")
                    start.style.backgroundColor = 'blue';
                    start.style.color = 'white';


                } else {
                    $("#errormsg" + i).show();
                    $("#errormsg" + i).addClass("text");
                    $("#errormsg" + i).addClass("text-danger");
                    $("#errormsg" + i).html(customerList.msg);
                    // setTimeout(function() {
                    // 	$("#partyMsg").hide();
                    // }, 3000);
                }
            },

        });
    }

    function onFormProductSubmitHandlers(e, i) {
        e.preventDefault();
        // $(".searchselect").select2();
        var url = $("#id_pata_nahi" + i).attr("action");
        var form = $("#id_pata_nahi" + i)[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                // $(".searchselect").select2();
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {

                    alert("Product Updated");
                    // window.location.href='';

                    document.getElementById('protr' + i).focus();
                    var start = document.getElementById('protr' + i);
                    //  = document.getElementById('trp' + rowId);
                    // console.log(start, "ye chal")
                    start.style.backgroundColor = 'blue';
                    start.style.color = 'white';

                } else {
                    $("#errorpro" + i).show();
                    $("#errorpro" + i).addClass("text");
                    $("#errorpro" + i).addClass("text-danger");
                    $("#errorpro" + i).html(customerList.msg);
                    // setTimeout(function() {
                    // 	$("#partyMsg").hide();
                    // }, 3000);
                }
            },

        });
    }

    const prepareCustomerDropdown = (customers) => {

        $("#party_id").value(customers.id);

        $('#gstNos').val(customers.gstno);


    };
</script>

<script>
    // $(document).ready(function() {
    $('#gstTypeVal').hide()

    function stateChanger(e, row) {
        var aman = e.target.value;
        // $('#state').change(function() {
        // var code = $(this).find(':selected').data('id');
        $('#state_code' + row).val(aman);
        // });
    }
    $('#state').change(function() {
        var code = $(this).find(':selected').data('id');
        $('#code').val(code);
    });

    $('#gstType').change(function() {
        if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
            $('#gstTypeVal').show();
            $('#gst').attr('required', true);
        } else {
            $('#gstTypeVal').hide();
            $('#gst').attr('required', false);
        }
    });
    // });

    $('#gst').blur(function() {
        $("#custClose").focus()
    });

    document.getElementById('party_id').tabIndex = 6;
</script>

<script>

</script>
<script>
    var inputs = document.querySelectorAll("input,select");
    // //(inputs);
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                // alert(nextInput);

                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                nextInput[0].focus();
            }
        })
    }
</script>
<script>
    function printSale() {
        var saleValue = document.getElementById('printSale').value;
        // alert(saleValue);
        var invoiceNumber = document.getElementById('invoiceNumber').value;

        if (saleValue == 0) {
            // alert('Enter First Invoice Number That you Want to Print');
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Enter Invoice First",
                showConfirmButton: false,
                timer: 2000,
            });
            return;

        }
        //(invoiceNumber);
        if (saleValue)

            redirectToPrint(saleValue);
    }

    function printSalePreview() {
        var saleValue = document.getElementById('printSale').value;
        // alert(saleValue);
        var invoiceNumber = document.getElementById('invoiceNumber').value;

        if (saleValue == 0) {
            // alert('Enter First Invoice Number That you Want to Print');
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Enter Invoice First",
                showConfirmButton: false,
                timer: 2000,
            });
            return;

        }
        //(invoiceNumber);
        if (saleValue)

            redirectToPrints(saleValue);
    }
</script>
<script>
    document.onkeyup = function(e) {
        if (e.which == 77) {
            // alert("M key was pressed");
        } else if (e.ctrlKey && e.which == 66) {
            // alert("Ctrl + B shortcut combination was pressed");
            deleteRow();
        } else if (e.ctrlKey && e.altKey && e.which == 89) {
            preview();

            // alert("Ctrl + Alt + Y shortcut combination was pressed");
        } else if (e.ctrlKey && e.altKey && e.shiftKey && e.which == 85) {
            next();
            // alert("Ctrl + Alt + Shift + U shortcut combination was pressed");
        }
    };

    function updateInvoiceMail(url) {
        $.ajax({
            type: "POST",
            url: url,
            data: $("#dataForm").serialize(),
            success: function(invoiceNo) {
                invoiceNo = JSON.parse(invoiceNo);
                // //(invoiceNo,"idreturn");
                if (invoiceNo.status == true) {
                    invoiceData = invoiceNo.data;
                    // cosnole.log(invoiceData);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: "Proforma updated successfully.",
                        showConfirmButton: false,
                        timer: 2000
                    })
                        <?php if ($compProfile->invoicePayment == 'Enable') { ?>
                        $("#updateSalePayment").modal('show');
                        $("#saleIdForUpdate").val(invoiceData);
                        <?php } else { ?>
                        setTimeout(function() {
                                redirectToPrint(invoiceData)
                        }, 2000);
                        <?php } ?>


                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: invoiceNo.msg,
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                // $("#updateInvoice").prop("disabled", false);

            },
            error: function(jqXHR, exception) {
                $("#loader").addClass("d-none");
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 403) {
                    msg = 'Permission denied. [403]';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 406) {
                    msg = jqXHR.responseText;
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n';
                    console.error(jqXHR.responseText);
                }
                Swal.fire({
                    type: 'error',
                    title: msg,
                });
                $("#updateInvoice").prop("disabled", false);
            },
        });
    }

    function preview() {
        var invoiceNumber = document.getElementById('invoiceNumber').value;
        if (invoiceNumber != '') {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "proforma/getProformaByIdPrevious",
                data    :   {id: invoiceNumber},
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.status) {
                                    window.location.href = '<?= base_url(); ?>proforma/edit/' + ResponseData.data.id + '';
                                }
                            }
            });
        }
    }

    function next() {
        var invoiceNumber = document.getElementById('invoiceNumber').value;
        if (invoiceNumber != '') {
            $.ajax({
                method  :   "POST",
                url     :   baseurl + "proforma/getProformaByIdNext",
                data    :   {id: invoiceNumber},
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                var saleData = ResponseData.data;
                                if (saleData.invoiceNumber != null) {
                                    window.location.href = '<?= base_url(); ?>proforma/edit/' + saleData.id + '';
                                } else {
                                    alert("Did not Find the Data");
                                }
                            }
            });
        }
    }

    function productForEdit(rowNumber, productId) {
        jQuery.noConflict();
        $("#rownumber").val(rowNumber);
        // document.getElementById('rownumber').value = rowNumber;
        $("#update_products .modal-dialog .modal-body").load(baseurl + "index.php/product/edit_product_for_sale/" + productId);
        $("#exampleModalLabels").html("Update Product");
        $("#update_products").modal("show");
    }

    function partyProfileEdit(rowNumber, party_id, e) {
        $("#exampleModalLong").modal("show");
        // document.getElementById().style.display = "block";
        var x = document.getElementById('hiddendata' + rowNumber);
        // var notidea = document.getElementById('notidea' + rowNumber);
        // console.log(x);
        if (x.style.display === "none") {
            x.style.display = "block";
            // notidea.style.display = "block";

        } else {
            x.style.display = "none";
            start1 = document.getElementById('searchparty');
            // console.log(start1, "asd")
            start1.focus();
            var elmtTable = document.getElementById('example12');
            var tableRows = elmtTable.getElementsByTagName('tr');
            var rowCount = tableRows.length;
            for (var i = 0; i < rowCount; i++) {
                var start = document.getElementById('trp' + i);
                // document.getElementById('hiddendata' + i).style.display = 'none';

                // console.log(start, "styuiopj")
                start.style.backgroundColor = 'white';
                start.style.color = 'black';
            }


        }

    }


    function productProfileEdit(rowNumber, productId) {
        // alert("Heide");
        jQuery.noConflict();
        // $("#exampleModalLongTitle").modal("show");
        var x = document.getElementById('hiddenproduct' + rowNumber);
        // console.log(x);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
            start1 = document.getElementById('searchproduct');
            // console.log(start1, "asd")
            start1.focus();
            var elmtTable = document.getElementById('example13');
            var tableRows = elmtTable.getElementsByTagName('div');
            var rowCount = tableRows.length;
            for (var i = 0; i < rowCount; i++) {
                var start = document.getElementById('protr' + i);
                // document.getElementById('hiddendata' + i).style.display = 'none';

                // console.log(start, "styuiopj")
                start.style.backgroundColor = 'white';
                start.style.color = 'black';
            }

        }
    }

    function toggleData() {
        // alert("hello")
        $("#rightsidebar").toggle();
        $("#fulldisplay").toggleClass("col-md-12");
        $("#pud").css({
            "margin-top": "-78px",
            "margin-left": "165px"
        });
    }
    
    $(document).ready(function() {
        $("#rightsidebar").toggle();
        $("#fulldisplay").toggleClass("col-md-12");
        // $("#pud").style='';
        $("#pud").css({
            "margin-top": "-78px",
            "margin-left": "165px"
        });


    });

    function requireValue() {
        var elmtTable = document.getElementById('dataTable');
        var tableRows = elmtTable.getElementsByTagName('tr');
        var rowCount = tableRows.length;
        // //(rowCount);
        for (var x = rowCount; x > 0; x--) {

            $("#tr" + x).remove();
        }
    }

</script>
<script>
    $('#closepartymodel').click(function() {
        $('#exampleModalLong').modal('hide');
        // console.log("Hide");
        // document.getElementById('partyname').focus();

        var elmtTable = document.getElementById('example12');
        var tableRows = elmtTable.getElementsByTagName('tr');
        var rowCount = tableRows.length;
        for (var i = 0; i < rowCount; i++) {
            var start = document.getElementById('trp' + i);
            document.getElementById('hiddendata' + i).style.display = 'none';

            // console.log(start, "styuiopj")
            start.style.backgroundColor = 'white';
            start.style.color = 'black';
        }

    })

    $('#closeproductmodel').click(function() {
        $('#exampleModalLong1').modal('hide');
        // console.log("Hide");
        var elmtTable = document.getElementById('example13');
        var tableRows = elmtTable.getElementsByTagName('div');
        var rowCount = tableRows.length;
        for (var i = 0; i < rowCount; i++) {
            var start = document.getElementById('protr' + i);
            document.getElementById('hiddenproduct' + i).style.display = 'none';

            // console.log(start, "styuiopj")
            start.style.backgroundColor = 'white';
            start.style.color = 'black';
        }
    })
</script>

<script>
    const keyDownForProductInfo = (row, e) => {
        // function keyDownProduct(e) {
            if (e.keyCode == '40') {
                var start = document.getElementById('protr0');
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';

            }
        // }
    }

    const keyDownForProduct = (e, row) => {
        //down arrow key
        if (e.keyCode == '40') {
            // console.log(row, "rowpaykyah");
            var elmtTable = document.getElementById('example13');
            // var tableRow = document.getElementById('producttbdoy');
            var tableRows = elmtTable.getElementsByTagName('div');
            // console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            // console.log(rowCount, "rowCounter")
            // console.log(row, "row")

            if (rowCount > row) {
                if (row < 0) {
                    row = 0;
                }
                row = row + 1;
                previrow = row - 1;
                // console.log(row, "tr");
                var start = document.getElementById('protr' + row);
                var previous = document.getElementById('protr' + previrow);

                // console.log(previous, "previousrow");
                // var searchparty = document.getElementById('searchproduct').value;
                // console.log(start, "start");
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                    // }
                }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            } else {
                // row = row - 1;
                var start = document.getElementById('protr' + row);
                // console.log(start, "eleswala");
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    }
    const keyUpForProduct = (e, row) => {
        // function keyUpForProduct(e, row) {
        //up arrow key
        if (e.keyCode == '38') {
            if (row == 0) {
                var previous = document.getElementById('protr' + row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black'
                document.getElementById("productname" + row).focus();
                return;

            }


            // console.log(row, "rowpaykyahupwalep");
            var elmtTable = document.getElementById('example13');
            // var tableRow = document.getElementById('producttbdoy');
            var tableRows = elmtTable.getElementsByTagName('div');
            // console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            // console.log(rowCount, "rowCounter")
            // console.log(row, "row")
            previrow = '';
            if (rowCount > row) {
                if (row <= 0) {
                    row = 0;
                    // previrow = row + 1;

                } else {
                    row = row - 1;
                    previrow = row + 1;
                }

                // console.log(row, "tr");
                var start = document.getElementById('protr' + row);
                if (previrow) {
                    var previous = document.getElementById('protr' + previrow);

                    // console.log(previous, "previousrow");
                    // var searchparty = document.getElementById('searchproduct').value;
                    // console.log(start, "start");
                    if (previous) {
                        previous.style.backgroundColor = 'white';
                        previous.style.color = 'black';
                    }
                }
                // }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            }
        }
    }

    const keyUpForProductCode = (e, row) => {
        // function keyUpForProduct(e, row) {
        //up arrow key
        if (e.keyCode == '38') {
            if (row == 0) {
                var previous = document.getElementById('protr' + row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black'
                document.getElementById("p_code" + row).focus();
                return;

            }


            // console.log(row, "rowpaykyahupwalep");
            var elmtTable = document.getElementById('example13');
            // var tableRow = document.getElementById('producttbdoy');
            var tableRows = elmtTable.getElementsByTagName('div');
            // console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            // console.log(rowCount, "rowCounter")
            // console.log(row, "row")
            previrow = '';
            if (rowCount > row) {
                if (row <= 0) {
                    row = 0;
                    // previrow = row + 1;

                } else {
                    row = row - 1;
                    previrow = row + 1;
                }

                // console.log(row, "tr");
                var start = document.getElementById('protr' + row);
                if (previrow) {
                    var previous = document.getElementById('protr' + previrow);

                    // console.log(previous, "previousrow");
                    // var searchparty = document.getElementById('searchproduct').value;
                    // console.log(start, "start");
                    if (previous) {
                        previous.style.backgroundColor = 'white';
                        previous.style.color = 'black';
                    }
                }
                // }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            }
        }
    }

    const keyDownForProductCode = (e, row) => {
        //down arrow key
        if (e.keyCode == '40') {
            // console.log(row, "rowpaykyah");
            var elmtTable = document.getElementById('example13');
            // var tableRow = document.getElementById('producttbdoy');
            var tableRows = elmtTable.getElementsByTagName('div');
            // console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            // console.log(rowCount, "rowCounter")
            // console.log(row, "row")

            if (rowCount > row) {
                if (row < 0) {
                    row = 0;
                }
                row = row + 1;
                previrow = row - 1;
                // console.log(row, "tr");
                var start = document.getElementById('protr' + row);
                var previous = document.getElementById('protr' + previrow);

                // console.log(previous, "previousrow");
                // var searchparty = document.getElementById('searchproduct').value;
                // console.log(start, "start");
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                    // }
                }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            } else {
                // row = row - 1;
                var start = document.getElementById('protr' + row);
                // console.log(start, "eleswala");
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    }

    const keyDownForParty = (e, row) => {

        if (e.keyCode == '40') {
            // console.log(row, "rowpaykyah");
            var elmtTable = document.getElementById('example12');
            // var tableRow = document.getElementById('tbody');
            // console.log(elmtTable);
            var tableRows = elmtTable.getElementsByTagName('div');
            console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            console.log(rowCount, "rowCounter")
            // console.log(row, "row")

            if (rowCount > row) {
                if (row < 0) {
                    row = 0;
                }
                row = row + 1;
                previrow = row - 1;
                // console.log(row, "tr");
                var start = document.getElementById('trp' + row);
                var previous = document.getElementById('trp' + previrow);

                // console.log(previous, "previousrow");
                // var searchparty = document.getElementById('searchproduct').value;
                // console.log(start, "start");
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                    // }
                }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            } else {
                // row = row - 1;
                var start = document.getElementById('trp' + row);
                // console.log(start, "eleswala");
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    }
    const keyUpForParty = (e, row) => {
        // function keyUpForParty(e, row) {
        //up arrow key
        if (e.keyCode == '38') {
            if (row == 0) {
                var previous = document.getElementById('trp' + row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black'
                document.getElementById('partyname').focus();
                return;
            }
            // console.log(row, "rowpaykyahupwalep");
            var elmtTable = document.getElementById('example12');
            // var tableRow = document.getElementById('producttbdoy');
            var tableRows = elmtTable.getElementsByTagName('div');
            // console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            // console.log(rowCount, "rowCounter")
            // console.log(row, "row")
            previrow = '';
            if (rowCount > row) {
                if (row <= 0) {
                    row = 0;
                    // previrow = row + 1;

                } else {
                    row = row - 1;
                    previrow = row + 1;
                }

                // console.log(row, "tr");
                var start = document.getElementById('trp' + row);
                if (previrow) {
                    var previous = document.getElementById('trp' + previrow);
                    if (previous) {
                        previous.style.backgroundColor = 'white';
                        previous.style.color = 'black';
                    }
                }
                // }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            }
        }
    }


    function keyDownProduct(e) {
        if (e.keyCode == '40') {
            var start = document.getElementById('protr0');
            start.focus();
            start.style.backgroundColor = 'blue';
            start.style.color = 'white';

        }
    }

    function keyDown(e) {

        if (e.keyCode == '40') {
            // document.getElementById('partyname').blur();
            var start = document.getElementById('trp0');
            start.focus();
            start.style.backgroundColor = 'blue';
            start.style.color = 'white';
            // $('#trp0').css('cursor', 'pointer');
            // console.log(start);

        }

    }

    function getProducts() {
        $.ajax({
            url: baseurl + "index.php/product/getproducts",
            method: "GET",
            success: function(products) {
                var products = JSON.parse(products);
                if (products.status == true) {
                    prepareProductsDropdown(products.data.product, products.data.productCode);
                    if ("" != null && "" !== "") {
                        $("#invoiceNumber").val("");
                        $("#invoiceNumber").trigger("change");
                    }
                    $("td .select2-container").css("width", "150px");
                    //("=====Prepare======");
                }
                // $('.searchselect').select2();

            },

        });
    }


    const onProductSelect = (id, type = "Sale", rowNo) => {
        const productId = id;
        if (productId == "" || productId == null) {
            onRemoveRowHandler(rowNo);
            return;
        }

        $.ajax({
            method  :   "GET",
            url     :   baseurl + "index.php/product/getProduct/" + productId,
            success :   function(product) {
                            var product = JSON.parse(product);
                            var product = product.data;
                            document.getElementById("product_data_append" + rowNo).innerHTML = '';
                            setSelectedProductDetails(rowNo, product, type);
                            document.getElementById("qty" + rowNo).focus();
                            onAddNewRowHandler();
                            rowNo = allUniqueRows[allUniqueRows.length - 1] + 1;
                        }

        });
    };
    
    $(document).ready(function() {
        $('#partyname').on('focus', function() {
            // $('#exampleModalLong').on('shown.bs.modal', function() {
            $('#exampleModalLong').modal('show');

            // $().find('[autofocus]').focus();

        });
    })

    $(document).ready(function() {
        $('#searchproduct').on('focus', function() {
            // $('#exampleModalLong').on('shown.bs.modal', function() {
            $('#exampleModalLong1').modal('show');

            // $().find('[autofocus]').focus();

        });
    })



    $(document).ready(function() {
        $('#exampleModalLong').on('shown.bs.modal', function() {
            $('#searchparty').trigger('focus');
        });
    });


    $(document).ready(function() {
        $('#exampleModalLong1').on('shown.bs.modal', function() {
            $('#searchproduct').trigger('focus');
        });
    });

    const getAccountGroup = (id, i) => {

        $.ajax({
            method: "GET",
            url: baseurl + "index.php/accounting_group/groupfind/" + id,
            success: function(product) {
                document.getElementById('group' + i).innerHTML = product;

            }
        })
    }


    const getCity = (id, i) => {

        $.ajax({
            method: "GET",
            url: baseurl + "index.php/district/cityfind/" + id,
            success: function(product) {
                document.getElementById('city_select' + i).innerHTML = product;
            }
        })
    }
    
    const getStateSelects = (id, i) => {

        $.ajax({
            method: "GET",
            url: baseurl + "index.php/state/statefind/" + id,
            success: function(product) {
                document.getElementById('state_select' + i).innerHTML = product;
            }
        })
    }

    const get_state_name = (id) => {

        $.ajax({
            method: "GET",
            url: baseurl + "index.php/state/statefind/" + id,
            success: function(product) {
                return product;

            }
        })
    }
    const gstType = (id, i) => {

        $.ajax({
            method: "GET",
            url: baseurl + "index.php/party/gstType/" + id,
            success: function(product) {
                document.getElementById('gstType' + i).innerHTML = product;
            }
        })
    }




    $(document).ready(function() {
        $('#exampleModalLong3').on('shown.bs.modal', function() {
            $('#searchstate').trigger('focus');
        });
    });

    function enterKeyPressed(event) {
        if (event.keyCode == 13) {
            var search = $('#partyname').val();
            if (search.length != '') {
                document.getElementById("trp0").click();
            }

        }
    }

    
    function enterKey(event) {
        if (event.keyCode == 13) {
            document.getElementById("tr0").click();
        }
    }

    function chnageDirection(event) {
        if (event.keyCode == 37) {
            //left
            // alert("left click");
            document.getElementById('btnSave').focus();

            var start = document.getElementById('btnSave');

            start.style.backgroundColor = 'blue';
            return false;

            // console.log("okk")

        }
        if (event.keyCode == 39) {
            //right
            // alert("right click");

            var start = document.getElementById('btnSave');

            start.style.backgroundColor = 'white';
            start.style.color = 'black';

            document.getElementById('btnSaveAndPrint').focus();
            var start = document.getElementById('btnSaveAndPrint');
            start.style.backgroundColor = 'blue';
            // alert("pAndT")
            return false;

        }
    }

    function keyUp(e) {
        if (e.keyCode == '38') {
            var elmtTable = document.getElementById('example12');
            var tableRows = elmtTable.getElementsByTagName('tr');
            var rowCount = tableRows.length;
            // for (var i = 0; rowCount > tr;)
            if (rowCount >= tr) {
                tr = --tr;
                if (tr < 0) {
                    tr = 0;
                }
                var start = document.getElementById('tr' + tr);
                // console.log(start, "start");
                // if (tr <= 0) {

                var next = start.previousSibling.previousElementSibling;

                if (next != null) {
                    // console.log(next, "previouskeyup");

                    next.focus();
                    next.style.backgroundColor = 'blue';
                    next.style.color = 'white';
                    // }
                }
                start.focus();
                start.style.backgroundColor = 'white';
                start.style.color = '#040404';
                // alert("keydown")
                // console.log(start, "focus");


            } else {
                document.getElementById('tr0').focus();
            }
        } else {
            document.getElementById('tr0').focus();

        }




    }

    function changeFormat() {
        var formats = document.getElementById('formats').value;
        // alert(formats);
        document.getElementById('format_invoice').value = formats;
        // let taxcal=document.getElementById('taxcal').value;

        // $("#taxcal").trigger("change");
        changeTaxCal();


    }

    function changeTaxCal() {
        // $("#taxcal").trigger("change");

        var formats = document.getElementById('taxcal').value;
        // if (formats == 2) {==exclusive
        var rowCount = allUniqueRows.length;
        console.log(allUniqueRows);
        if (formats == 2) {
            if (rowCount > 0) {
                allUniqueRows.forEach((element) => {
                    // alert(element)
                    productId = document.getElementById('productId' + element).value;
                    if (productId != '') {
                        // alert(productId);
                        productInfo(productId, element);
                    }

                });
            }
        } else {
            if (rowCount > 0) {
                allUniqueRows.forEach((element) => {
                    // alert(element) inclusive
                    console.log(element);
                    productId = document.getElementById('productId' + element).value;
                    if (productId != '') {
                        productInfoForInclusive(productId, element);
                    }

                })

            }
        }
    }



    function productInfo(productId, rowNo) {
        $.ajax({
            method: "GET",
            url: baseurl + "index.php/product/getProduct/" + productId,
            success: function(product) {
                var product = JSON.parse(product);
                var product = product.data;
                let type = "Sale";
                setteledProInfo(rowNo, product, type);
            },

        });

    }

    const setteledProInfo = (rowNo, product, type) => {
        var formats = document.getElementById('formats').value;
        if (formats == 'Estimate') {
            var tax = 0;
            var tax1 = 0;
            $("#tax" + rowNo).val(0);
            // document.getElementById("tax" + rowNo).disabled = true;
            $("#tax" + rowNo).prop("disabled", true);

        } else {
            $("#tax" + rowNo).prop("disabled", false);
            // $("#tax" + rowNo).trigger("change");
            getTaxesForGettingInPreview(product.tax_qty, rowNo);
            // (product.tax, rowNo);
            $("#tax" + rowNo).trigger("change");


        }
        $("#qty" + rowNo).trigger("change");

    }
    // selectProductInfo();
    const getOnlyTax = (tax_id, rowNum) => {
        $.ajax({
            type: "POST",
            data: {
                id: tax_id,
                rownumber: rowNum,
            },
            url: "<?= base_url() ?>tax/gettax",
            success: function(invoiceNo) {
                $("#taxal" + rowNum).html(invoiceNo);
            },

        });
    }

    function productInfoForInclusive(productId, rowNo) {
        $.ajax({
            method: "GET",
            url: baseurl + "index.php/product/getProduct/" + productId,
            success: function(product) {
                var product = JSON.parse(product);
                var product = product.data;
                let type = "Sale";
                inclusiveChange(rowNo, product, type);
            },

        })



    }


    const inclusiveChange = (rowNo, product, type) => {
        let formats = $("#formats").val();
        if (formats == 'Estimate') {
            var tax = 0;
            var tax1 = 0;
            $("#tax" + rowNo).val(0);
            // document.getElementById("tax" + rowNo).disabled = true;
            $("#tax" + rowNo).prop("disabled", true);

        } else {
            $("#tax" + rowNo).prop("disabled", false);
            getTaxData(product.tax, rowNo);
            // $("#tax" + rowNo).trigger("change");

        }
        // console.log("clicked");
        $("#qty" + rowNo).trigger("change");

    }

    const setSelectedProductDetailsForBarcode = (rowNo, product, type) => {
      // Helper function to avoid code duplication
      const calculateAndSetProductDetails = () => {
        const formats = $("#formats").val();
        let tax, tax1;

        if (formats === 'Estimate') {
          tax = 0;
          tax1 = 0;
          $(`#tax${rowNo}`).val(0);
          $(`#tax${rowNo}`).prop("disabled", true);
        } else {
          tax = product.tax_qty;
          tax1 = product.tax;
          $(`#tax${rowNo}`).prop("disabled", false);
          getTaxData(tax1, rowNo);
          $(`#tax${rowNo}`).val(tax);
          $(`#tax${rowNo}`).trigger("change");
        }

        // Set product details
        $(`#p_code${rowNo}`).val(product.qrCode);
        getProductName(product.id, rowNo);
        $(`#qty${rowNo}`).val(1);
        $(`#productId${rowNo}`).val(product.id);
        $(`#hsnCode${rowNo}`).val(product?.hsn || "");
        $(`#rate${rowNo}`).val(product ? parseFloat(product.price) : 0);
        $(`#discount${rowNo}`).val(0);

        const qty = 1; // Default quantity
        const rate = parseFloat(product.price) || 0;
        const discount = 0;
        const taxPercent = parseFloat(tax) || 0;
        const taxcal = $("#taxcal").val();

        // Calculate using consistent tax logic
        let baseRate = rate;
        if (taxcal == 1) { // Tax Inclusive
          baseRate = (rate / (taxPercent / 100 + 1)).toFixed(3);
        }

        const afterDiscount = baseRate * (1 - discount / 100);
        const baseAmount = afterDiscount * qty;
        const taxAmount = (baseAmount * taxPercent) / 100;
        const netAmount = baseAmount + taxAmount;

        // Update fields based on format
        if (formats === 'Estimate') {
          $(`#price${rowNo}`).val(rate.toFixed(3));
        } else {
          $(`#price${rowNo}`).val(baseAmount.toFixed(3));
        }

        $(`#taxAmount${rowNo}`).val(taxAmount.toFixed(3));
        $(`#amount${rowNo}`).val(taxAmount.toFixed(3));
        $(`#netAmount${rowNo}`).val(netAmount.toFixed(2));

        calculateGST(taxPercent, baseAmount, rowNo);
        manipulateRHSData();

        const warehouse_id = $("#warehouse").find(":selected").val();
        $(`#warehouse_id${rowNo}`).val(warehouse_id);

        // Set edit and copy buttons
        $(`#editProduct${rowNo}`).html(
          `<a href="javascript:void(0);" onclick="productForEdit(${rowNo},${product.id})" style="display: <?= $this->session->userdata('RoleType') ? 'none' : 'contents'; ?>;">` +
          '<i class="fas fa-edit" style="width:41px;" title="Product Edit"></i></a>' +
          <?php if ($this->session->userdata('RoleType')) { ?>
          '&nbsp;&nbsp;' +
          <?php } else { ?>
          `<a href="javascript:void(0);" class="text-warning" onclick="getWarehouseForProduct(${rowNo},${product.id});" title="Warehouse">` +
          '<i class="fa fa-home"></i></a>' +
          <?php } ?>
          ''
        );

        $(`#copy${rowNo}`).html(
          `<a href="#" onclick="productForCopy(${rowNo},${product.id})">` +
          '<i class="fas fa-copy" style="width:41px;" title="Product Copy"></i></a>'
        );

        const PreviousItemCount = parseInt($("#num_of_items").val()) || 0;
        $("#num_of_items").val(PreviousItemCount + 1);
      };

      <?php if (!empty($CompProfile) && isset($CompProfile->ProductStockCheck) && $CompProfile->ProductStockCheck == 'Y') { ?>
      const BalanceQty = JSON.parse(CheckProductBalanceQuantity(product.id, rowNo)).data;
      if (BalanceQty > 0) {
        calculateAndSetProductDetails();
      } else {
        toastr.error("Selected product stock is empty");
        $(`#p_code${rowNo}`).val("");
        $(`#productId${rowNo}`).val("");
        $(`#productname${rowNo}`).val("");
      }
      <?php } else { ?>
      calculateAndSetProductDetails();
      <?php } ?>
    };

    const setSelectedProductDetails = (rowNo, product, type) => {
      // Helper function to avoid code duplication
      const calculateAndSetProductDetails = () => {
        const formats = $("#formats").val();
        let tax, tax1;

        if (formats === 'Estimate') {
          tax = 0;
          tax1 = 0;
          $(`#tax${rowNo}`).val(0);
          $(`#tax${rowNo}`).prop("disabled", true);
        } else {
          tax = product.tax_qty;
          tax1 = product.tax;
          $(`#tax${rowNo}`).prop("disabled", false);
          getTaxData(tax1, rowNo);
          $(`#tax${rowNo}`).val(tax);
          $(`#tax${rowNo}`).trigger("change");
        }

        // Set product details
        $(`#p_code${rowNo}`).val(product.qrCode);
        getProductName(product.id, rowNo);
        $(`#qty${rowNo}`).val(1);
        $(`#productId${rowNo}`).val(product.id);
        $(`#hsnCode${rowNo}`).val(product?.hsn || "");

        // Get rate based on party_wise_rate setting
        let rate;
        <?php if (!empty($CompProfile) && isset($CompProfile->party_wise_rate) && $CompProfile->party_wise_rate == 'Y') { ?>
        const PartyRate = JSON.parse(getLastSaleMaxPriceOfProduct(rowNo)).data.Rate;
        rate = PartyRate ?? product.price;
        <?php } else { ?>
        rate = product.price;
        <?php } ?>

        $(`#rate${rowNo}`).val(parseFloat(rate) || 0);
        $(`#discount${rowNo}`).val(0);

        const qty = 1; // Default quantity
        const discount = 0;
        const taxPercent = parseFloat(tax) || 0;
        const taxcal = $("#taxcal").val();

        // Calculate using consistent tax logic
        let baseRate = rate;
        if (taxcal == 1) { // Tax Inclusive
          baseRate = (rate / (taxPercent / 100 + 1)).toFixed(3);
        }

        const afterDiscount = baseRate * (1 - discount / 100);
        const baseAmount = afterDiscount * qty;
        const taxAmount = (baseAmount * taxPercent) / 100;
        const netAmount = baseAmount + taxAmount;

        // Update fields based on format
        if (formats === 'Estimate') {
          $(`#price${rowNo}`).val(rate.toFixed(3));
        } else {
          $(`#price${rowNo}`).val(baseAmount.toFixed(3));
        }

        $(`#taxAmount${rowNo}`).val(taxAmount.toFixed(3));
        $(`#amount${rowNo}`).val(taxAmount.toFixed(3));
        $(`#netAmount${rowNo}`).val(netAmount.toFixed(2));

        calculateGST(taxPercent, baseAmount, rowNo);
        manipulateRHSData();

        const warehouse_id = $("#warehouse").find(":selected").val();
        $(`#warehouse_id${rowNo}`).val(warehouse_id);

        // Set edit and copy buttons
        $(`#editProduct${rowNo}`).html(
          `<a href="javascript:void(0);" onclick="productForEdit(${rowNo},${product.id})" style="display: <?= $this->session->userdata('RoleType') ? 'none' : 'contents'; ?>;">` +
          '<i class="fas fa-edit" style="width:41px;" title="Product Edit"></i></a>' +
          <?php if ($this->session->userdata('RoleType')) { ?>
          '&nbsp;&nbsp;' +
          <?php } else { ?>
          `<a href="javascript:void(0);" class="text-warning" onclick="getWarehouseForProduct(${rowNo},${product.id});" title="Warehouse">` +
          '<i class="fa fa-home"></i></a>' +
          <?php } ?>
          ''
        );

        $(`#copy${rowNo}`).html(
          `<a href="#" onclick="productForCopy(${rowNo},${product.id})">` +
          '<i class="fas fa-copy" style="width:41px;" title="Product Copy"></i></a>'
        );

        const PreviousItemCount = parseInt($("#num_of_items").val()) || 0;
        $("#num_of_items").val(PreviousItemCount + 1);
      };

      <?php if (!empty($CompProfile) && isset($CompProfile->ProductStockCheck) && $CompProfile->ProductStockCheck == 'Y') { ?>
      const BalanceQty = JSON.parse(CheckProductBalanceQuantity(product.id, rowNo)).data;
      if (BalanceQty > 0) {
        calculateAndSetProductDetails();
      } else {
        toastr.error("Selected product stock is empty");
        $(`#productId${rowNo}`).val("");
        $(`#productname${rowNo}`).val("");
      }
      <?php } else { ?>
      calculateAndSetProductDetails();
      <?php } ?>
    };

    
    const getLastSaleMaxPriceOfProduct = (rowNo) => {
        var party_id = $("#party_id").val();
        if (party_id != "" && party_id != null) {
            var product_id = $("#productId" + rowNo).val();
            return $.ajax({
                        method  :   "POST",
                        url     :   baseurl + "proforma/getLastMaxPriceOfProductByProforma",
                        data    :   {product_id: product_id, party_id: party_id},
                        async   :   false,
                        success :   function(Response) {
                                        if (JSON.parse(Response).status == true) {
                                            JSON.parse(Response);
                                        }
                                    }
                   }).responseText;
        } else {
            alert("First select party for sale");
            location.reload();
        }
    };

    const CheckProductBalanceQuantity = (ProductId, RowNo) => {
        if (ProductId != null && RowNo != null) {
            return $.ajax({
                    method  :   "POST", 
                    url     :   "<?= base_url(); ?>product/getProductBalanceQuantity",
                    data    :   {ProductId: ProductId},
                    async   :   false,
                    success :   function(Response) {
                                    if (JSON.parse(Response).status == true) {
                                        JSON.parse(Response);
                                    }
                                }
            }).responseText;
        }
    };

    const onQuantityChangeHandler = (e, rowNumber, type = "Sale") => {
      const qty = parseFloat(e.target.value) || 0;
      const rate = parseFloat($(`#rate${rowNumber}`).val()) || 0;
      const discount = parseFloat($(`#discount${rowNumber}`).val()) || 0;
      const taxPercent = parseFloat($(`#tax${rowNumber}`).val()) || 0;
      const formates = $("#formates").val();
      const taxcal = $("#taxcal").val(); // 1=inclusive, 2=exclusive

      // Step 1: Get base rate (extract GST if inclusive)
      let baseRate = rate;
      if (taxcal == 1) { // Inclusive
        baseRate = (rate / (taxPercent / 100 + 1)).toFixed(3);
      }

      // Step 2: Apply discount
      const baseAmount = baseRate * qty;
      const discountAmount = baseAmount * (discount / 100);
      const taxableAmount = baseAmount - discountAmount;

      // Step 3: Apply tax
      const cgst = taxableAmount * (taxPercent / 2) / 100;
      const sgst = taxableAmount * (taxPercent / 2) / 100;
      const taxAmount = (taxableAmount * taxPercent) / 100
      const netAmount = taxableAmount + taxAmount;
      
      // Step 4: Update UI fields
      if (formates === 'Estimate') {
        $(`#price${rowNumber}`).val(0);
      } else {
        $(`#price${rowNumber}`).val(taxableAmount.toFixed(3));
      }

      $(`#taxAmount${rowNumber}`).val(taxAmount.toFixed(3));
      $(`#amount${rowNumber}`).val(taxAmount.toFixed(3));
      $(`#netAmount${rowNumber}`).val(netAmount.toFixed(2));

      // Additional calculations
      calculateGSTwithall(taxPercent, taxableAmount, rowNumber);
      manipulateRHSData();
    };

    const onRateChangeHandler = (e, rowNumber, type = 'Sale') => {
        $("#qty" + rowNumber).trigger("change");
    };

    const calculateRate = (rowNo, type) => {
      const rate = parseFloat($(`#rate${rowNo}`).val()) || 0;
      const qty = parseFloat($(`#qty${rowNo}`).val()) || 0;
      const formats = $("#formats").val();
      const taxcal = $("#taxcal").val();

      let taxPercent = 0;
      if (formats === 'Estimate') {
        $(`#tax${rowNo}`).val(0);
        $(`#tax${rowNo}`).prop("disabled", true);
      } else {
        $(`#tax${rowNo}`).prop("disabled", false);
        taxPercent = parseFloat($(`#tax${rowNo}`).val()) || 0;
      }

      const totalRate = rate * qty;

      if (taxcal == 1) { // Tax Inclusive
        // Extract base rate (remove tax from total)
        const baseRate = totalRate / (taxPercent / 100 + 1);
        return baseRate.toFixed(3);
      } else { // Tax Exclusive
        return totalRate.toFixed(3);
      }
    };

    const showPartyPop = (e) => {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load(baseurl + "index.php/party/showParty");
        $("#exampleModals").modal("show");

    }

    const setShippingAddress = (e) => {
        jQuery.noConflict();
        $("#setShippingAddressModel .modal-dialog .modal-body").load(baseurl + "index.php/party/showParty");
        $("#setShippingAddressModel").modal("show");

    }



    const editPartyPopup = (e) => {
        jQuery.noConflict();
        $("#editPartyPopup .modal-dialog .modal-body").load(baseurl + "index.php/party/showParty");
        $("#editPartyPopup").modal("show");

    }
    $(document).ready(function() {
        $('#exampleModals').on('shown.bs.modal', function() {
            $('#searchparty').trigger('focus');
        });
    });

    const entPressByParty = (e, party_id, row_id) => {

        if (e.keyCode == 13) {
            getCustomerDetail(party_id, row_id);
        }
        console.log(e.keyCode);

    }

    const getCustomerDetail = (customerId, rowId = null) => {
        console.log("cutomer")
        $.ajax({
            method: "POST",
            // url: baseurl + "get-customer",
            url: baseurl + "index.php/party/getCustomer",
            data: {
                id: customerId,
            },
            success: function(customer) {
                var customer = JSON.parse(customer);
                setSelectedCustomerDetails(customer.data);


            },

        });
    }
    
        const setSelectedCustomerDetails = (customer) => {
                $("#party_id").val(customer != null ? customer.id : "");
                $("#partyname").val(customer != null ? customer.name : "");
                $("#gstNos").val(customer != null ? customer.gst : "");
                $("#customerAddress").val(customer != null ? customer.address_1 + (customer.area != "" ? ", " : "") + customer.area : "");
                $("#customerMobile").val(customer != null ? customer.contact : "");
                $("#customerEmail").val(customer != null ? customer.email : "");
                $("#stateSelect").val(customer != null ? customer.state : "");
                getStateName(customer.state, customer.state_name);
                $("#customerCity").val(customer != null ? customer.city : "");
                getCityName(customer.city, customer.city_name);
                $("#customerBalance").val(customer != null ? customer.opening_balance : "");
                getSalesmanName(customer != null ? customer.SalesmanId : "");
        
                document.getElementById("party_data_append").innerHTML = '';
                let last_row_id = $(allUniqueRows).get(-1);
        
                setTimeout(function() {
                        getStateNames();
                }, 1000);
        
                <?php if (isset($barcode) && !empty($barcode) && $barcode == 'yes') { ?>
                document.getElementById("p_code" + last_row_id).focus();
                <?php } else { ?>
                document.getElementById("productname" + last_row_id).focus();
                <?php } ?>
                    
                //Shipping Info set
                $("#shippingNameHidden").val(customer.name);
                $("#shippingEmailHidden").val(customer.email);
                $("#shippingMobileHidden").val(customer.contact);
                $("#shippingGSTTypeHidden").val(customer.gstType);
                $("#shippingGSTHidden").val(customer.gst);
                $("#shippingStateHidden").val(customer.state);
                $("#shippingAddressHidden").val(customer.address_1);
                $("#shippingCityHidden").val(getCityNameById(customer.city));
                $("#shippingZipHidden").val(customer.pin);
        };
        
        const getCityNameById = (cityId) => {
                return $.ajax({
                               method    :       "POST",
                               url           :       baseurl + "district/getCityName",
                               data         :       {city_id: cityId},
                               async       :       false
                       }).responseText;
        };        
    
    const getSalesmanName = (SalesmanId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Employee/getSalesmanForSale",
            data    :   {SalesmanId: SalesmanId},
            success :   function(response) {
                            if (response) {
                                $(".SalesmanId").html(response);
                            } else {
                                $(".SalesmanId").html("");
                            }
                        }
        });
    };

    $("#editing_party").click(function() {
        let party_id = $("#party_id").val();
        $("#party_edit_ok .modal-dialog .modal-body").load(baseurl + "index.php/party/edit_party_for_sale/" + party_id);
        $("#editparty").html("Update Party");
        $("#party_edit_ok").modal("show");

    })

    $("#copying_party").click(function() {
        let party_id = $("#party_id").val();
        $("#copying_party_ok .modal-dialog .modal-body").load(baseurl + "index.php/party/create_party_for_sale/" + party_id);
        $("#copyparty").html("Create Party");
        $("#copying_party_ok").modal("show");

    })

    $("#adding_party").click(function() {
        // let party_id = $("#party_id").val();
        $("#adding_party_ok .modal-dialog .modal-body").load(baseurl + "index.php/party/create_party_for_sale_without_copying");
        $("#addparty").html("Create Party");
        $("#adding_party_ok").modal("show");

    })

        $("#shippingInfo").click(function() {
                $("#shippingDetails .modal-content").load(baseurl + "party/shippingInfo", function() {
                        // Once content is loaded, fetch the hidden field values
                        var shippingName = $("#shippingNameHidden").val();
                        var shippingEmail = $("#shippingEmailHidden").val();
                        var shippingMobile = $("#shippingMobileHidden").val();
                        var shippingGSTType = $("#shippingGSTTypeHidden").val();
                        var shippingGST = $("#shippingGSTHidden").val();
                        var shippingState = $("#shippingStateHidden").val();
                        var shippingAddress = $("#shippingAddressHidden").val();
                        var shippingCity = $("#shippingCityHidden").val();
                        var shippingZip = $("#shippingZipHidden").val();

                        // Set the values in the modal
                        $("#shippingName").val(shippingName);
                        $("#shippingEmail").val(shippingEmail);
                        $("#shippingMobile").val(shippingMobile);
                        $("#shippingGSTType").val(shippingGSTType);
                        $("#shippingGST").val(shippingGST);
                        $("#shippingState").val(shippingState);
                        $("#shippingAddress").val(shippingAddress);
                        $("#shippingCity").val(shippingCity);
                        $("#shippingZip").val(shippingZip);

                        // Show the modal after setting values
                        $("#shippingDetails").modal("show");
                });
        });
        
    $(".editInvoiceNumberEnable").click(function() {
        // $("#invoiceNumber").prop("readonly", true);
        $("#invoiceNumber").prop("readonly", false);
        $(".editInvoiceNumberEnable").addClass('editInvoiceNumberDisable');
        $('.editInvoiceNumberEnable').removeClass('editInvoiceNumberEnable');
    });

    $(".editInvoiceNumberDisable").click(function() {
        $("#invoiceNumber").prop("readonly", "readonly");
        console.log("Error");
        // $("#invoiceNumber").prop("readonly", false);
        $(".editInvoiceNumberDisable").addClass('editInvoiceNumberEnable');
        $('.editInvoiceNumberDisable').removeClass('editInvoiceNumberDisable');

    });
    
    function setWarehouse(e) {
        jQuery.noConflict();
        e.preventDefault();
        
        var url = $("#setWarehouse").attr("action");
        var form = $("#setWarehouse")[0];
        var data = new FormData(form);
        
        $.ajax({
            type        :   "POST",
            url         :   url,
            data        :   data,
            processData :   false,
            contentType :   false,
            cache       :   false,
            dataType    :   "JSON",
            success     :   function(Response) {
                                if (Response.status == true) {
                                    toastr.success("Warehouse set successfully");
                                    var WarehouseId = $("#warehouse").find(":selected").val();
                                    getWarehouseName(WarehouseId);
                                    document.getElementById("warehouse_modal").click();
                                } else {
                                    $("#respnce_msg").show();
                                    $("#respnce_msg").addClass("text");
                                    $("#respnce_msg").addClass("text-danger");
                                    $("#respnce_msg").html(Response.msg);
                                }
                            }
        });
    }
    
    function getWarehouseName(WarehouseId) {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>warehouse/getWarehouseName",
            data    :   {WarehouseId: WarehouseId},
            success :   function (Response) {
                            var JsonResponse = JSON.parse(Response);
                            if (JsonResponse.status == true) {
                                $("#warehouse_name").text(JsonResponse.data);
                            }
                        }
        });
    }
    
    const onFreightChargeCalculate = () => {
        var TotalValue = $("#totalValue").val();
        var FreightCharge = $("#freight_charge").val();
        var CashDiscount = $("#cash_discount").val();
        var GrandTotal = Number(TotalValue) + Number(FreightCharge) - Number(CashDiscount);
        $("#total_amount_for").val(Math.round(GrandTotal).toFixed(2));
        $("#total").val(Math.round(GrandTotal).toFixed(2));
    };
    
    const onCashDiscountCalculate = () => {
        var TotalValue = $("#totalValue").val();
        var FreightCharge = $("#freight_charge").val();
        var CashDiscount = $("#cash_discount").val();
        var GrandTotal = Number(TotalValue) - Number(CashDiscount) + Number(FreightCharge);
        $("#total_amount_for").val(Math.round(GrandTotal).toFixed(2));
        $("#total").val(Math.round(GrandTotal).toFixed(2));
    };
    
    function getWarehouseForProduct(rowNo) {
        jQuery.noConflict();
        $("#warehouse_nowno").val(rowNo);
        $("#warehouse_modal_for_product .modal-dialog .warehouse_modal_product").load(baseurl + "proforma/set_warehouse_for_product");
        $("#warehouse_modal_for_product").modal("show");
    }
    
    function setWarehouseForProduct() {
        jQuery.noConflict();
        var rowNo = $("#warehouse_nowno").val();
        var warehouse_for_product = $("#warehouse_for_product").find(":selected").val();
        if ($("#warehouse_id"+rowNo).val(warehouse_for_product)) {
            toastr.success("Warehouse set successfully.");
            document.getElementById("warehouse_modal_for_product").click();
        } else {
            toastr.error("Unable to set warehouse.");
        }
    }
    
    const suggestPartyName = (PartyName) => {
        if (PartyName == "" || PartyName == null) {
            $('#party_name_list').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyName",
            data    :   {PartyName: PartyName},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list-party-name" onclick="onSelectgetPartyName('${element.name}');">${element.name}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_name_list').html(partyopt);
                                $('#name').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_name_list').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_name_list').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyName = (PartyName) => {
        $('#name').val(PartyName);
        window.setTimeout(function() {
            $('#party_name_list').html('');
        }, 1000);
    };
        
    const getPartyArea = (PartyArea) => {
        if (PartyArea == "" || PartyArea == null) {
            $('#party_area_list').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyArea",
            data    :   {PartyArea: PartyArea},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list" onclick="onSelectgetPartyArea('${element.area}');">${element.area}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_area_list').html(partyopt);
                                $('#area').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_area_list').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_area_list').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyArea = (PartyArea) => {
        $('#area').val(PartyArea);
        window.setTimeout(function() {
            $('#party_area_list').html('');
        }, 1000);
    };
        
    const getPartyCity = (PartyCity) => {
        if (PartyCity == "" || PartyCity == null) {
            $('#party_city_list').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyCity",
            data    :   {PartyCity: PartyCity},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list" onclick="onSelectgetPartyCity('${element.cname}');">${element.cname}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_city_list').html(partyopt);
                                $('#city').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_city_list').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_city_list').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyCity = (PartyCity) => {
        $('#city').val(PartyCity);
        window.setTimeout(function() {
            $('#party_city_list').html('');
        }, 1000);
    };
</script>