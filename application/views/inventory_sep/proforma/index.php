<style type="text/css">
        td.active {
                border: 1px solid blue;
                font-weight: bold;
                color: yellow;
                background-color: red;
        }
        td {
                padding: 5px;
                text-align: center;
        }
        .form-control {
                width: 100%;
                height: 32px;
        }
        div#party_data_append {
                position: absolute;
                top: 43px;
                z-index: 999;
                background: #fff;
        }
        @media (max-width:991px) {
                .btn-outline-primary, .btn-outline-dark, .btn-outline-danger, .btn-outline-info, .btn-outline-secondary, .btn-outline-success, .btn-outline-warning {
                        margin-bottom: 10px;
                }
                .btn-display {
                        display: contents;
                }
        }
        #party_name_list, #party_area_list, #party_city_list, #party_name_list_for_edit, #party_area_list_for_edit, #party_city_list_for_edit, #party_name_list_for_copy, #party_area_list_for_copy, #party_city_list_for_copy {
                max-height: 200px;
                height: auto;
                overflow: auto;    
        }
        .search-list-party-name {
                color: #000;
                list-style: none;
                position: relative;
                left: -40px;
                border-bottom: 0.5px solid #e7e7e7;
                padding-top: 5px;
                width: 300px;
                font-size: 12px;
        }
        .search-list {
                color: #000;
                list-style: none;
                position: relative;
                left: -40px;
                border-bottom: 0.5px solid #e7e7e7;
                padding-top: 5px;
                width: 150px;
                font-size: 12px;
        }
        .select2-dropdown {
                left: 2px !important;
                top: 6px;
        }
        u {
                font-size: 18px;
                color: unset;
        }
</style>

<?php 
        $userId = $this->session->userdata('user_id');
        $compId = $this->session->userdata('comp_id');        
        $compBranchId = $this->session->userdata('CompBranchId');
        
        $where = ['user_id' => $userId, 'comp_id' => $compId];
        if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
        }
        $compProfile = $this->user_model->getRow('comp_profile', $where);
        if (!$compProfile) {
                $compProfile = new stdClass();
        }
        foreach (['pos', 'voucherType', 'ProductStockCheck', 'party_wise_rate', 'warehouse', 'taxcal'] as $_cpKey) {
                if (!isset($compProfile->{$_cpKey})) {
                        $compProfile->{$_cpKey} = ($_cpKey === 'voucherType') ? '2' : 'N';
                }
        }
        // common_js.php and some fields use $CompProfile
        $CompProfile = $compProfile;
        $barcode = $this->session->userdata('barcode');
        $discount = $this->session->userdata('discount');
?>

<div class="content-wrapper" id="donknow">
        <!-- Content Header (Page header) -->
        <div class="content-header">
                <div class="container-fluid">
                        <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card card-outline card-maroon">
                                                <div class="card-header">
                                                        <div class="header-title">
                                                                <div class="row">
                                                                        <div class="col-md-6">
                                                                                <h4>Create Proforma</h4>
                                                                                <select hidden class="form-control" onchange="changeFormat();" name="formats" id="formats">
                                                                                        <option value="Tax" <?= set_value('formats') == 'Tax' ? 'selected' : ''; ?>>Tax</option>
                                                                                        <option value="Estimate" <?= set_value('formats') == 'Estimate' ? 'selected' : ''; ?>>Estimate</option>
                                                                                </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                                <div class="pull-right">
                                                                                        <label class="text-success">
                                                                                                Last Order Amount : 
                                                                                                <i class="fa fa-inr"></i> 
                                                                                                <?= $this->db->select('total')->where($where)->where('status', 1)->order_by('id', 'DESC')->get('proforma')->row('total'); ?>
                                                                                        </label>
                                                                                        <a href="javascript:void(0);" class="btn btn-outline-primary btn-xs" data-toggle="modal" data-target="#warehouse_modal" title="Warehouse">
                                                                                                <i class="fa fa-home" style="color: #000;"></i> <span id="warehouse_name"></span>
                                                                                        </a>
                                                                                        &nbsp;
                                                                                        <a href="javascript:void(0);" title="Expand" onclick="toggleData();">
                                                                                                <i class="fa fa-expand"></i>
                                                                                        </a>
                                                                                        &nbsp;
                                                                                        <a href="<?= base_url(); ?>proforma/report" title="Proforma List">
                                                                                                <i class="fa fa-list"></i>
                                                                                        </a>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                        
                                                <!-- Select Warehouse -->
                                                <div class="modal fade" id="warehouse_modal" tabindex="-1" role="dialog" aria-labelledby="title" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                        <div class="modal-header">
                                                                                <h5 class="modal-title" id="title">Warehouse</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                </button>
                                                                        </div>
                                                                        <div class="" id="respnce_msg" style="opacity: 500; display: none;"></div>
                                                                        <form action="<?= base_url('comp_profile/setWarehouse'); ?>" method="POST" id="setWarehouse" onsubmit="setWarehouse(event);">
                                                                                <div class="modal-body">
                                                                                        <div class="row">
                                                                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                                                                        <div class="form-group">
                                                                                                                <select name="warehouse" id="warehouse" class="form-control form-control-sm">
                                                                                                                        <option selected="" disabled="" value="">Select Warehouse</option>
                                                                                                                        <?php
                                                                                                                                $this->db->select('id, name, location');
                                                                                                                                $this->db->where($where);
                                                                                                                                $warehouses = $this->db->get('warehouse')->result();
                                                                                                                                if (!empty($warehouses)) { foreach ($warehouses as $key => $warehouse) {
                                                                                                                        ?>
                                                                                                                        <option <?= $compProfile->warehouse == $warehouse->id ? "selected" : ""; ?> value="<?= $warehouse->id; ?>" <?= set_select('warehouse', $warehouse->id); ?>><?= $warehouse->name; ?> - <?= $warehouse->location; ?></option>
                                                                                                                        <?php } } ?>
                                                                                                                </select>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                        <button type="submit" class="btn btn-warning btn-sm">Set Warehouse</button>
                                                                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                                                                                </div>
                                                                        </form>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- Select Warehouse End -->                        
                        
                                                <form action="<?= base_url(); ?>proforma/save" autocomplete="off" novalidate class="form-horizontal" id="dataForm" method="POST" onsubmit="onFormSubmitHandler(event);">
                                                        <div class="card-body">
                                                                <div class="row">
                                                                        <div class="col-md-10" id="fulldisplay">
                                                                                <div class="row mb-3">
                                                                                        <div class="col-sm-4">
                                                                                                <div class="row mb-1">
                                                                                                        <label for="invoiceNumber" class="col-4">Invoice Number</label>
                                                                                                        <div class="col-8 p-0">
                                                                                                                <?php
                                                                                                                        $series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where(['seriestitle.series' => 'Proforma', 'series.status' => 2])->where($where)->get()->row();
                                                                                                                        if (isset($series) && !empty($series)) {
                                                                                                                            $ser2 = $this->user_model->getSeris($series);
                                                                                                                ?>
                                                                                                                <input autocomplete="nope" type="text" readonly class="form-control" required="true" name="invoiceNumber" style=" width: 92%; height: 42px; color: red;font-size: 20px;" id="invoiceNumber" tabindex="1" value="<?= $ser2 ?>">
                                                                                                                <input autocomplete="nope" type="hidden" required="true" name="series_id" id="series_id" readonly value="<?= $series->series_id; ?>">
                                                                                                                <?php } else {
                                                                                                                        $this->db->select_max('unorder_invoice_number');
                                                                                                                        $this->db->where($where);
                                                                                                                        $query = $this->db->get('proforma')->row();
                                                                                                                        if (!empty($query->unorder_invoice_number)) {
                                                                                                                            $inital = $query->unorder_invoice_number + 1;
                                                                                                                        } else {
                                                                                                                            $inital = substr(10000 + 1, 1);
                                                                                                                        }
                                                                                                                ?>
                                                                                                                <input autocomplete="nope" tabindex="1" readonly="" type="text" class="form-control" required="true" style=" width: 92%; height: 42px; color: red;font-size: 20px;" name="invoiceNumber" id="invoiceNumber" value="<?= $inital; ?>" />
                                                                                                                <input type="hidden" value="" name="series_id" />
                                                                                                                <?php } ?>
                                                                                                                <span class="edit_invoice editInvoiceNumberEnable" title="Edit Invoice" id="edit_invoice" style="cursor: pointer;"><i class="fa fa-lg fa fa-edit text-primary editInvoiceNumberEnable" style="float: right;margin-top: -24px;"></i></span>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <?php
                                                                                                        $this->db->select_max('unorder_invoice_number');
                                                                                                        $this->db->where($where);
                                                                                                        $query = $this->db->get('proforma')->row();
                                                                                                ?>
                                                                                                <div class="row mb-1">
                                                                                                        <label for="invoiceDate" class="col-4">Date</label>
                                                                                                        <input type="date" tabindex="2" class="form-control col-8" required="true" name="invoiceDate" id="invoiceDate" value="<?= date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" />
                                                                                                </div>
                                                                        <div class="row mb-1">
                                                                            <label for="voucherType" class="col-4" title="Voucher Type">Payment Mode</label>
                                                                            <select tabindex="3" name="voucherType" id="voucherType" required="true" class="form-control col-<?= $compProfile->pos == 'Y' ? '4' : '8'; ?>">
                                                                                <option value="1" <?= set_value('voucherType', $compProfile->voucherType) == '1' ? 'selected' : ''; ?>>Cash</option>
                                                                                <option value="2" <?= set_value('voucherType', $compProfile->voucherType) == '2' ? 'selected' : ''; ?>>Credit</option>
                                                                                <option value="Card" <?= set_value('voucherType', $compProfile->voucherType) == 'Card' ? 'selected' : ''; ?>>Card</option>
                                                                                <option value="Phone Pay" <?= set_value('voucherType', $compProfile->voucherType) == 'Phone Pay' ? 'selected' : ''; ?>>Phone Pay</option>
                                                                                <option value="Google Pay" <?= set_value('voucherType', $compProfile->voucherType) == 'Google Pay' ? 'selected' : ''; ?>>Google Pay</option>
                                                                                <option value="Paytm" <?= set_value('voucherType', $compProfile->voucherType) == 'Paytm' ? 'selected' : ''; ?>>Paytm</option>
                                                                                <option value="RTGS" <?= set_value('voucherType', $compProfile->voucherType) == 'RTGS' ? 'selected' : ''; ?>>RTGS</option>
                                                                                <option value="NEFT" <?= set_value('voucherType', $compProfile->voucherType) == 'NEFT' ? 'selected' : ''; ?>>NEFT</option>
                                                                                <option value="Cheque" <?= set_value('voucherType', $CompProfile->voucherType) == 'Cheque' ? 'selected' : ''; ?>>Cheque</option>
                                                                                <option value="IMPS" <?= set_value('voucherType', $CompProfile->voucherType) == 'IMPS' ? 'selected' : ''; ?>>IMPS</option>
                                                                            </select>
                                                                            <?php if ($compProfile->pos == 'Y') { ?>
                                                                            <label for="pos" class="col-2">POS</label>
                                                                            <div class="col-2">
                                                                                <span class="text-success"></span>
                                                                                <label class="switch">
                                                                                    <input type="checkbox" id="pos" checked="" />
                                                                                    <span class="slider round"></span>
                                                                                </label>
                                                                            </div>
                                                                            <?php } else { ?>
                                                                            <input type="checkbox" id="pos" class="d-none" />
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="mb-1 row">
                                                                            <label for="taxcal" class="col-4">Tax Cal</label>
                                                                            <select name="taxcal" tabindex="4" onchange="changeTaxCal();" id="taxcal" required class="form-control col-8">
                                                                                <option value="1" <?= set_value('taxcal', $compProfile->taxcal) == '1' ? 'selected' : ''; ?>>Inclusvie</option>
                                                                                <option value="2" <?= set_value('taxcal', $compProfile->taxcal) == '2' ? 'selected' : ''; ?>>Exclusive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                      <!-- due to outside of the form we write here for set value and get -->
                                                                      <input type="hidden" name="format_invoice" id="format_invoice" value="Tax">

                                                                       <input type="hidden" name="shippingNameHidden" id="shippingNameHidden" />
                                                                       <input type="hidden" name="shippingEmailHidden" id="shippingEmailHidden" />
                                                                       <input type="hidden" name="shippingMobileHidden" id="shippingMobileHidden" />
                                                                       <input type="hidden" name="shippingGSTTypeHidden" id="shippingGSTTypeHidden" />
                                                                       <input type="hidden" name="shippingGSTHidden" id="shippingGSTHidden" />
                                                                       <input type="hidden" name="shippingStateHidden" id="shippingStateHidden" />
                                                                       <input type="hidden" name="shippingAddressHidden" id="shippingAddressHidden" />
                                                                       <input type="hidden" name="shippingCityHidden" id="shippingCityHidden" />
                                                                       <input type="hidden" name="shippingZipHidden" id="shippingZipHidden" />

                                                                    <div class="col-sm-5">
                                                                        <div class="row mb-1 ">
                                                                            <label for="customerId" class="col-3">Party</label>
                                                                            <div class="col-9 p-0">
                                                                                <div id="party_idDiv" style="width:85%; float: left; position: relative;">
                                                                                    <input type="hidden" name="customerId" id="party_id" value="" required="true">
                                                                                    <input type="text" autocomplete="off" tabindex="5" placeholder="Party Name" class="form-control col-8 modal-lg" name="partyname" id="partyname" onkeypress="return enterKeyPressed(event);" onkeydown="return keyDown(event);" style="width: 100%;height:47px;" value="<?= get_title('parties', ['id' => set_value('party_id')], 'name'); ?>" required="true">
                                                                                </div>
                                                                                <span class="fr mr-1 addPatys" title="Add new customer" id="adding_party" data-toggle="modal" style="cursor: pointer;">
                                                                                    <i style="margin-left:6px;" class="fa fa-lg fa-plus-circle text-primary"></i>
                                                                                </span>
                                                                                <span class="fr mr-1 edit_party" title="Edit" value="" id="editing_party" data-toggle="modal" style="cursor: pointer;">
                                                                                    <i style="margin-left:6px;" class="fa fa-lg fa fa-edit text-primary"></i>
                                                                                </span>
                                                                                <span class="fr mr-1 copy_party" title="Copy Customer" id="copying_party" data-toggle="modal" style="cursor: pointer;">
                                                                                    <i style="margin-left:6px;" class="fa fa-lg fa fa-copy text-primary"></i>
                                                                                </span>
                                                                              <span class="fr mr-1" title="Shipping Info" id="shippingInfo" style="cursor: pointer;">
                                                                                      <i style="margin-left: 4px;" class="fas fa-shipping-fast text-primary"></i>
                                                                              </span>
                                                                                <div id="party_data_append"></div>
                                                                            </div>
                                                                        </div>

                                                                       <div class="modal" id="shippingDetails" tabindex="-1" role="dialog" aria-labelledby="shippingInformation" aria-hidden="true">
                                                                              <div class="modal-dialog" role="document">
                                                                                      <div class="modal-content"></div>
                                                                              </div>
                                                                       </div>

                                                                        <div class="modal fade" id="party_edit_ok" tabindex="-1" role="dialog" aria-labelledby="editparty" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document" style="max-width: 1400px; width: 1100px; height: 800px;">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="editparty"></h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">×</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal fade" id="copying_party_ok" tabindex="-1" role="dialog" aria-labelledby="copyparty" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document" style="max-width: 1400px; width: 1100px; height: 800px;">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="copyparty"></h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">×</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal fade" id="adding_party_ok" tabindex="-1" role="dialog" aria-labelledby="addparty" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document" style="max-width: 1400px; width: 1100px; height: 800px;">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="addparty"></h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">×</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Modal -->

                                                                        <div class="mb-1 row">
                                                                            <label for="gstNo" class="col-3">GST No</label>
                                                                            <input type="text" placeholder="GST No" tabindex="6" class="form-control col-9" style="font-size: 20px;color:red;" name="gstNo" id="gstNos">
                                                                        </div>
                                                                        <div class="mb-1 row">
                                                                            <label for="customerAddress" class="col-3">Address</label>
                                                                            <input autocomplete=" nope" tabindex="7" placeholder="Address" cols=" 4" class="form-control col-9" name="customerAddress" id="customerAddress">
                                                                        </div>
                                                                        <div class="input-group mb-1">
                                                                            <label for="Mobile" class="col-3">Mobile </label>
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                                                            </div>
                                                                            <input type="text" cols="4" tabindex="8" class="form-control" id="customerMobile" name="customerMobile" placeholder="A/C Mobile" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <div class="row">
                                                                            <!-- City Modal Closed -->
                                                                            <div class="col-sm-12">
                                                                                <div class="input-group mb-1">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                                                    </div>
                                                                                    <input type="email" tabindex="9" class="form-control" id="customerEmail" name="customerEmail" placeholder="A/C Email">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-12">
                                                                                <div class="input-group mb-1">
                                                                                    <input type="text" placeholder="State Name" tabindex="10" class="form-control col-8 modal-lg" name="statename" onchange="getStateNames()" id="statename" style="width: 100%;" value="<?= get_title('state_list', ['id' => set_value('customerState')], 'state') ?>" required="true">
                                                                                    <input type="hidden" name="customerState" id="stateSelect" style="width: 100%;" value="" required="true">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-12">
                                                                                <div class="input-group mb-1">
                                                                                    <input type="hidden" name="customerCity" id="customerCity" placeholder="City" style="width: 100%;" value="" required="true">
                                                                                    <input type="text" placeholder="City Name" tabindex="11" onchange="changeCityNames()" class="form-control" name="cityCustomer" id="cityCustomer" style="width: 100%;" value="<?= get_title('districts', ['id' => set_value('customerCity')], 'name') ?>">
                                                                                    <span id="hiddencity"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-12">
                                                                                <div class="input-group mb-1">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fa fa-money"></i></span>
                                                                                    </div>
                                                                                    <input type="text" tabindex="12" class="form-control" id="customerBalance" name="customerBalance" readonly placeholder="A/C Balance">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- table -->
                                                                <div class="row mb-3" style="width:100%; min-height: 350px; height:auto; margin-top:-11px;">
                                                                        <div class="col-sm-12">
                                                                                <table class="table table-bordered inner_table table-responsive-sm mb-0" id="dataTable">
                                                                                        <thead class="bg-dark" style="position: sticky; top: 0; z-index: 99;">
                                                                                                <tr>
                                                                                                        <th>Sr</th>
                                                                                                        <?php if (isset($barcode) && !empty($barcode) && $this->session->userdata('barcode') == 'yes') { ?>
                                                                                                        <th width="120px;">PCode</th>
                                                                                                        <?php } ?>
                                                                                                        <th width="150px">Product Name
                                                                                                            <a class="cp ml-2 py-0" style="color:#fff;" onclick="GetProductModel();" title="Add New Product">
                                                                                                                <i class="fa fa-plus-circle text-white" style="font-size: 16px;"></i>
                                                                                                            </a>
                                                                                                        </th>
                                                                                                        <th>Des</th>
                                                                                                        <th width="60px">HSN</th>
                                                                                                        <?php
                                                                                                                if ($compProfile->category != "Normal") {
                                                                                                                    echo "<th width='60px'>Qty</th>";
                                                                                                                } else {
                                                                                                                    echo "<th hidden width='60px'>Qty</th>";
                                                                                                                }
                                                                                                                if ($compProfile->category == "Glass") {
                                                                                                                    echo "<th>SqFt</th>";
                                                                                                                } else {
                                                                                                                    echo "<th>Qty</th>";
                                                                                                                }
                                                                                                        ?>
                                                                                                        <th width="80x">Rate</th>
                                                                                                        <?php if ($discount == 'yes') { ?>
                                                                                                        <th width="20px">Disc.</th>
                                                                                                        <?php } ?>
                                                                                                        <th width="50px">Tax %</th>
                                                                                                        <th width="80px">Taxable Amt</th>
                                                                                                        <th width="100px">Tax Amt</th>
                                                                                                        <th width="100px">Net Amt</th>
                                                                                                        <th width="45px"></th>
                                                                                                </tr>
                                                                                        </thead>
                                                                                        <tbody></tbody>
                                                                                </table>
                                                                        </div>
                                                                </div>

                                                                <!-- words -->
                                                                <div class="row" style="margin-top:-13px;">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4">Amount in words</label>
                                                                            <input type="text" required readonly class="form-control" style="font-size: 20px;color:red;border:none;" id="amountInWords" name="amountInWords">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-4">Total Amount</label>
                                                                            <input type="text" required readonly class="form-control" style="font-size: 20px;color:red;border:none;" id="total_amount_for">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="nameCustomerKa" id="nameCustomerKa" value="">
                                                                <input type="hidden" name="printSale" id="printSale" value="0">

                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label for="num_of_items">Number Of Items</label>
                                                                            <input type="text" name="num_of_items" id="num_of_items" class="form-control" placeholder="Number Of Items" autocomplete="off" readonly="" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="form-group">
                                                                            <label for="remark">Remark</label>
                                                                            <input type="text" name="remark" id="remark" class="form-control" placeholder="Remark" autocomplete="off" />
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($this->session->userdata('salesman_sale') == 'Yes') { ?>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <label for="SalesmanId">Salesman</label>
                                                                            <select name="SalesmanId" id="SalesmanId" class="form-control SalesmanId" title="Salesman">
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>

                                                            <!-- Right panel -->
                                                            <div class="col-md-2 col-sm-12" id="rightsidebar">
                                                                <div class="row mb-1">
                                                                    <label class="col-7 p-0">Description</label>
                                                                    <label class="col-5 p-0">Amount</label>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" title="Taxable Amount">Taxable Amt.</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="taxableAmount" required="true" name="taxableAmount" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" title="totalsgst">Total SGST </span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="totalsgst" required="true" name="totalsgst" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" title="totalcgst">Total CGST</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="totalcgst" required="true" name="totalcgst" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" title="totaligst">Total IGST</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="totaligst" required="true" name="totaligst" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Total Tax</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="totalTax" name="totalTax" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Expenses</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="expenses" name="expenses" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">CESS</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="cess" name="cess" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Total Value</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="totalValue" name="totalValue" required="true" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Round Off</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" step="any" id="roundOff" name="roundOff" min="0" value="0" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Freight Charge</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" name="freight_charge" value="<?= set_value('freight_charge'); ?>" id="freight_charge" class="form-control" step="any" min="0" placeholder="Freight Charge" oninput="onFreightChargeCalculate();" />
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Cash Discount</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" name="cash_discount" value="<?= set_value('cash_discount'); ?>" id="cash_discount" class="form-control" step="any" min="0" placeholder="Cash Discount" oninput="onCashDiscountCalculate();" />
                                                                    </div>
                                                                </div>
                                                                <?php if ($this->session->userdata('lrdate') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">L.R. Date</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="date" id="lrDate" name="lrDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($this->session->userdata('lrNumber') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">L.R. No.</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" id="lrNumber" name="lrNumber" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($this->session->userdata('eway') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">E-Way Bill</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" id="ewayBill" name="ewayBill" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($this->session->userdata('dpb') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Disp. By</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" id="transport" name="transport" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Order No</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" class="form-control" name="orderNumber" id="orderNumber" placeholder="Order Number">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Order Date</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="date" class="form-control" name="orderDate" id="orderDate" value="<?php echo date('Y-m-d'); ?>" required="true">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Vehicle No</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" class="form-control" name="vehicle" id="vehicle" value="<?php ?>" required="true">
                                                                    </div>
                                                                </div>
                                                                <?php if ($this->session->userdata('ptd') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;" title="Pay Terms (Day)">PTD.</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" id="payTermsDay" name="payTermsDay" class="form-control" placeholder="Pay Terms Day">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($this->session->userdata('duedate') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Due Date</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="date" id="dueDate" name="dueDate" required="true" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($this->session->userdata('dcno') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">DC No.</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" id="dcNumber" name="dcNumber" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($this->session->userdata('dcdate') == 'yes') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">DC Date</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="date" id="dcDate" name="dcDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($compProfile->lot_no == 'Y') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">LOT No</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" id="lot_no" name="lot_no" class="form-control" required="true" value="">
                                                                    </div>
                                                                </div>
                                                                <?php } if ($compProfile->lh == 'Y') { ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Lh</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="text" id="lh" name="lh" class="form-control" required="true" value="">
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;" title="Old Amount">
                                                                        Old Balance
                                                                    </span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" id="oldBalance" name="oldBalance" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group mb-1">
                                                                    <span class="col-5 p-0" style="font-size: 14px;">Total</span>
                                                                    <div class="col-7 p-0">
                                                                        <input type="number" id="total" name="total" class="form-control" required="true">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="row btnsbmt">
                                                            <div class="col-sm-8 col-md-8 col-xl-8 col-lg-8 btn-display">
                                                                <!-- Submit button -->
                                                                <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                                                <button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg">
                                                                    <u>S</u>ave
                                                                </button>
                                                                <?php } else { ?>
                                                                <span class="text-danger">Your license has been expired,<br />Please contact your administrator.</span>
                                                                <?php } } else { ?>
                                                                <button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg">
                                                                    <u>S</u>ave
                                                                </button>
                                                                <?php } ?>
                                                                <a href="javascript:void(0);" class="btn btn-outline-danger btn-lg" onclick="return deleteRow();">
                                                                    <u>D</u>elete
                                                                </a>
                                                                <a href="javascript:void(0);" id="updateInvoice" class="btn btn-outline-warning btn-lg" onclick="return updateInvoice();">
                                                                    <u>U</u>pdate
                                                                </a>
                                                                <a href="javascript:void(0);" id="printSales" class="btn btn-outline-success btn-lg" onclick="printSale();">
                                                                    <u>P</u>rint
                                                                </a>
                                                                <a href="javascript:void(0);" id="printSales" class="btn btn-outline-info btn-lg" onclick="printSalePreview();" >
                                                                    <u>P</u>rint <u>P</u>review
                                                                </a>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4 col-xl-4 col-lg-4 btn-display">
                                                                <div class="pull-right">
                                                                    <a href="javascript:void(0);" class="btn btn-outline-dark btn-lg" onclick="return preview();">
                                                                        <u>P</u>revious
                                                                    </a>
                                                                    <a href="javascript:void(0);" class="btn btn-outline-secondary btn-lg" onclick="return next();">
                                                                        <u>N</u>ext
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>

<?php include_once('common_js.php'); ?>

<script type="text/javascript">
        $(".editInvoiceNumberEnable").click(function() {
                $("#invoiceNumber").prop("readonly", false);
                $(".editInvoiceNumberEnable").addClass('editInvoiceNumberDisable');
                $('.editInvoiceNumberEnable').removeClass('editInvoiceNumberEnable');
        });

        $(".editInvoiceNumberDisable").click(function() {
                $("#invoiceNumber").prop("readonly", "readonly");
                $(".editInvoiceNumberDisable").addClass('editInvoiceNumberEnable');
                $('.editInvoiceNumberDisable').removeClass('editInvoiceNumberDisable');
        });

        $(document).ready(function() {
                function load_data(query) {
                        $.ajax({
                                url           :       "<?= base_url(); ?>party/showParty",
                                method    :       "POST",
                                data        :       {query: query},
                                success  :        function(data) {
                                                                $('#party_data_append').html(data);
                                                        }
                        });
                }

                $('#partyname').keyup(function() {
                        var search = $("#partyname").val();
                        if (search.length > 0) {
                            load_data(search);
                        }   else  {
                            document.getElementById('party_id').value = '';
                            document.getElementById('party_data_append').innerHTML = '';
                        }
                });
        });

        function productSearch(e, row_id) {
                var search = $("#productname" + row_id).val();
                if (search.length > 0) {
                        product_fetch(search, row_id);
                } else {
                        document.getElementById('product_data_append' + row_id).innerHTML = '';
                }
        }

        function product_fetch(query, row_id) {
                $.ajax({
                        url            :       "<?= base_url(); ?>index.php/product/all_product_for_sales",
                        method     :       "POST",
                        data         :      {query: query, rowId: row_id},
                        success   :       function(data) {
                                                        $('#product_data_append' + row_id).html(data);
                                                }
                });
        }

        function productSearchCode(e, row_id) {
                var search = $("#p_code" + row_id).val();
                if (search.length > 0) {
                    product_fetch_for_product_code(search, row_id);
                } else {
                    document.getElementById('product_data_append_for_pcode' + row_id).innerHTML = '';
                }
        }

        function product_fetch_for_product_code(query, row_id) {
                $.ajax({
                        url            :       "<?= base_url(); ?>index.php/product/all_product_for_sales_by_code",
                        method     :       "POST",
                        data         :      {query: query, rowId: row_id},
                        success   :       function(data) {
                                                        $('#product_data_append_for_pcode' + row_id).html(data);
                                                }
                });
        }
</script>