<style type="text/css">
    .loader-bg {
        background: #161616 url("<?= base_url(); ?>assets/images/loader.gif") no-repeat center center;
        opacity: 0.5;
        background-size: 5%;
    }
    .loader-bg {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 10000;
    }
    td.active {
        border: 1px solid blue;
        font-weight: bold;
        color: yellow;
        background-color: red
    }
    td {
        padding: 5px;
        text-align: center
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
</style>

<?php 
    if ($this->session->userdata('RoleType')) {
        $EmployeeId = $this->session->userdata('EmployeeId');
        $UserId = $this->session->userdata('UserId');
        $CompId = $this->session->userdata('CompId');
    } else {
        $UserId = $this->session->userdata('user_id');
        $CompId = $this->session->userdata('comp_id');
    }
    $CompanyBranchId = $this->session->userdata('CompBranchId');
    
    $CommonWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
    if ($this->session->userdata('BranchRole')) {
        $CommonWhere['CompanyBranchId'] = $CompanyBranchId;
    }
    $CompProfile = $this->user_model->getRow('comp_profile', $CommonWhere);
    $BarCode = $this->session->userdata('barcode'); 
?>

<div class="loader-bg"></div>

<div class="content-wrapper" id="donknow">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <h4>Orderbook to Estimate</h4>
                                </div>
                                <div class="col-md-2">
                                    <select hidden class="form-control" onchange="changeFormat()" name="formats" id="formats">
                                        <option <?= $this->session->userdata('estimate_format') == "Tax" ? "selected" : ""; ?> value="Tax" <?= set_select('formats', 'Tax'); ?>>Tax</option>
                                        <option <?= $this->session->userdata('estimate_format') == "Estimate" ? "selected" : ""; ?> value="Estimate" <?= set_select('formats', 'Estimate'); ?>>Estimate</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <div class="pull-right">
                                        <label class="text-success">
                                            Last Invoice Amount : 
                                            <i class="fa fa-inr"></i> 
                                            <?php
                                                if ($this->session->userdata('RoleType')) {
                                                    $EmployeeWhere = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'), true);
                                                    echo $this->db->select('total')->where($CommonWhere)->where('status', 1)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('estimates')->row('total');
                                                } else {
                                                    echo $this->db->select('total')->where($CommonWhere)->where('status', 1)->order_by('id', 'DESC')->get('estimates')->row('total'); 
                                                }
                                            ?>
                                        </label>
                                        <a href="javascript:void(0);" class="btn btn-outline-primary btn-xs" data-toggle="modal" data-target="#warehouse_modal" title="Warehouse">
                                            <i class="fa fa-home"></i> <span id="warehouse_name"></span>
                                        </a>
                                        &nbsp;
                                        <a href="javascript:void(0);" title="Expand" onclick="toggleData()">
                                            <i class="fas fa-expand"></i>
                                        </a>
                                        &nbsp;
                                        <?php if ($this->session->userdata('RoleType')) { ?>
                                        <a href="<?= base_url(); ?>Employee/EstimatesReport" title="Estimate List">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?= base_url(); ?>estimate/estimate_report" title="Estimate List">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <?php } ?>
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
                                    <form action="<?= base_url('comp_profile/setWarehouseForEstimate'); ?>" method="POST" id="setWarehouse" onsubmit="setWarehouse(event);">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-lg-6">
                                                    <div class="form-group">
                                                        <select name="warehouse" id="warehouse" class="form-control form-control-sm">
                                                            <option selected="" disabled="" value="">Select Warehouse</option>
                                                            <?php
                                                                $this->db->select('id, name, location');
                                                                $this->db->where($CommonWhere);
                                                                $warehouses = $this->db->get('warehouse')->result();
                                                                if (!empty($warehouses)) { foreach ($warehouses as $key => $warehouse) {
                                                            ?>
                                                            <option <?= $CompProfile->warehouse_estimate == $warehouse->id ? "selected" : ""; ?> value="<?= $warehouse->id; ?>" <?= set_select('warehouse', $warehouse->id); ?>><?= $warehouse->name; ?> - <?= $warehouse->location; ?></option>
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
                        
                        <form action="<?= base_url(); ?>estimate/saveestimate" autocomplete="off" novalidate class="form-horizontal" id="dataForm" method="post" onsubmit="onFormSubmitHandler(event)">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-10" id="fulldisplay">
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <div class="row mb-1">
                                                    <label for="OrderbookNumber" class="col-4">Orderbook Number</label>
                                                    <div class="col-7 p-0">
                                                        <input type="text" name="OrderbookNumber" value="<?= $OrderbookInfo->OrderNumber; ?>" id="OrderbookNumber" class="form-control text-bold" placeholder="Orderbook Number" autocomplete="nope" tabindex="1" required="" readonly="" />
                                                        <input type="hidden" name="OrderbookId" value="<?= $OrderbookInfo->Id; ?>" id="OrderbookId" />
                                                    </div>
                                                    <div class="col-1 p-0 text-center">
                                                        <a href="javascript:void(0);" class="text-primary EnableOrderbookNumber" title="Edit Orderbook Number">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="invoiceNumber" class="col-4">Invoice No.</label>
                                                    <div class="col-8 p-0">
                                                        <?php
                                                            $SeriesWhere = ['series.comp_id' => $CompId, 'series.user_id' => $UserId, 'series.status' => 2];
                                                            if ($this->session->userdata('BranchRole')){
                                                                $SeriesWhere['series.CompanyBranchId'] = $CompanyBranchId; 
                                                            }
                                                            
                                                            $Series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('seriestitle.series', 'estimate')->where($SeriesWhere)->get()->row();
                                                            if (isset($Series) && !empty($Series)) {
                                                                $EstimateNumber = $this->user_model->getSeris($Series);
                                                        ?>
                                                        <input autocomplete="nope" type="text" readonly class="form-control" required="true" name="invoiceNumber" style=" width: 92%; height: 47px;" id="invoiceNumber" tabindex="1" value="<?= $EstimateNumber; ?>" />
                                                        <input type="hidden" name="series_id" id="series_id" value="<?= $Series->series_id; ?>" />
                                                        <?php } else {
                                                            $this->db->select_max('unorder_invoice_number');
                                                            $this->db->where($CommonWhere);
                                                            $this->db->where('status', 1);
                                                            $query = $this->db->get('estimates')->row();
                                                            
                                                            if (!empty($query->unorder_invoice_number)) {
                                                                $inital = $query->unorder_invoice_number + 1;
                                                            } else {
                                                                $inital = substr(10000 + 1, 1);
                                                            }
                                                        ?>
                                                        <input autocomplete="nope" tabindex="1" readonly type="text" class="form-control" required="true" style=" width: 92%; height: 47px; " name="invoiceNumber" id="invoiceNumber" value="<?= $inital; ?>">
                                                        <input type="hidden" value="" name="series_id" />
                                                        <?php } ?>
                                                        <span class="edit_invoice editInvoiceNumberEnable" title="Edit Invoice" id="edit_invoice" style="cursor: pointer;">
                                                            <i class="fa fa-lg fa fa-edit text-primary editInvoiceNumberEnable" style="float: right;margin-top: -24px;"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="invoiceDate" class="col-4">Date</label>
                                                    <input type="date" tabindex="2" class="form-control col-8" required="true" name="invoiceDate" id="invoiceDate" value="<?= date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" />
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="voucherType" class="col-4" title="Voucher Type">Payment Mode</label>
                                                    <select tabindex="3" name="voucherType" id="voucherType" required="true" class="form-control col-8">
                                                        <option value="1" <?= set_value('voucherType', $CompProfile->voucherType) == '1' ? 'selected' : ''; ?>>Cash</option>
                                                        <option value="2" selected="" <?= set_value('voucherType', $CompProfile->voucherType) == '2' ? 'selected' : ''; ?>>Credit</option>
                                                        <option value="Card" <?= set_value('voucherType', $CompProfile->voucherType) == 'Card' ? 'selected' : ''; ?>>Card</option>
                                                        <option value="Phone Pay" <?= set_value('voucherType', $CompProfile->voucherType) == 'Phone Pay' ? 'selected' : ''; ?>>Phone Pay</option>
                                                        <option value="Google Pay" <?= set_value('voucherType', $CompProfile->voucherType) == 'Google Pay' ? 'selected' : ''; ?>>Google Pay</option>
                                                        <option value="Paytm" <?= set_value('voucherType', $CompProfile->voucherType) == 'Paytm' ? 'selected' : ''; ?>>Paytm</option>
                                                        <option value="RTGS" <?= set_value('voucherType', $CompProfile->voucherType) == 'RTGS' ? 'selected' : ''; ?>>RTGS</option>
                                                        <option value="NEFT" <?= set_value('voucherType', $CompProfile->voucherType) == 'NEFT' ? 'selected' : ''; ?>>NEFT</option>
                                                        <option value="Cheque" <?= set_value('voucherType', $CompProfile->voucherType) == 'Cheque' ? 'selected' : ''; ?>>Cheque</option>
                                                        <option value="IMPS" <?= set_value('voucherType', $CompProfile->voucherType) == 'IMPS' ? 'selected' : ''; ?>>IMPS</option>
                                                    </select>
                                                </div>
                                                <div class="mb-1 row">
                                                    <label for="taxcal" class="col-4">Tax Cal</label>
                                                    <select name="taxcal" tabindex="4" onchange="changeTaxCal()" id="taxcal" required class="form-control  col-8">
                                                        <option value="1" <?= set_value('taxcal', $CompProfile->taxcal) == '1' ? 'selected' : ''; ?>>Inclusive</option>
                                                        <option value="2" <?= set_value('taxcal', $CompProfile->taxcal) == '2' ? 'selected' : ''; ?>>Exclusive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <!-- due to outside of the form we write here for set value and get -->
                                            <input type="hidden" name="format_invoice" id="format_invoice" value="<?= $this->session->userdata('estimate_format'); ?>" />

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
                                                        <div id="party_idDiv" style="width:85%;float:left;position:relative;">
                                                        <input type="hidden" name="customerId" id="party_id" value="" required="true">
                                                            <input type="text" autocomplete="off" tabindex="5" placeholder="Party Name" class="form-control col-8 modal-lg" name="partyname" id="partyname" onkeypress="return enterKeyPressed(event)" onkeydown="return keyDown(event)" style="width: 100%;height:47px;" value="<?= get_title('parties', ['id' => set_value('party_id')], 'name') ?>" required="true">
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
                                                    <div class="modal-dialog" role="document" style="max-width:1400px; width: 1100px;height:800px;">
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
                                                    <div class="modal-dialog" role="document" style="max-width:1400px; width: 1100px;height:800px;">
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
                                                    <div class="modal-dialog" role="document" style="max-width:1400px; width: 1100px;height:800px;">
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
                                                    <input type="text" placeholder="GST No" tabindex="6" class="form-control col-9" name="gstNo" id="gstNos">
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
                                                    <input type="text" cols="4" tabindex="8" class="form-control" id="customerMobile" name="customerMobile" placeholder="Mobile" />
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
                                        <div class="row mb-3" style="width: 100%; min-height: 350px; height: auto; margin-top: -11px;">
                                            <div class="col-sm-12">
                                                <table class="table table-bordered inner_table table-responsive-sm mb-0" id="dataTable">
                                                    <thead class="bg-dark" style="position: sticky; top: 0; z-index: 99;">
                                                        <tr>
                                                            <th class="text-white">Sr</th>
                                                            <?php if (isset($BarCode) && !empty($BarCode) && $BarCode == 'yes') { ?>
                                                            <th class="text-white" width="120px;">PCode</th>
                                                            <?php } ?>
                                                            <th class="text-white" width="150px">
                                                                Product Name
                                                                <?php if (empty($this->session->userdata('RoleType'))) { ?>
                                                                <a class="cp ml-2 py-0" style="color: #fff;" onclick="GetProductModel();" title="Add New Product">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </a>
                                                                <?php } ?>
                                                            </th>
                                                            <th class="text-white">Des</th>
                                                            <th class="text-white" width="60px">HSN</th>
                                                            <th class="text-white" width="60px">Qty</th>
                                                            <th class="text-white" width="80x">Rate</th>
                                                            <?php if ($this->session->userdata('discount') == 'yes') { ?>
                                                            <th class="text-white" width="20px">Disc.</th>
                                                            <?php } ?>
                                                            <th class="text-white" width="50px">Tax %</th>
                                                            <th class="text-white" width="80px">Taxable Amt</th>
                                                            <th class="text-white" width="100px">Tax Amt</th>
                                                            <th class="text-white" width="100px">Net Amt</th>
                                                            <th class="text-white" width="45px"></th>
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
                                                    <label for="amountInWords">Amount in words</label>
                                                    <input type="text" required readonly class="form-control" id="amountInWords" name="amountInWords">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="total_amount_for">Total Amount</label>
                                                    <input type="text" required readonly class="form-control" style="border:none;" id="total_amount_for">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="nameCustomerKa" id="nameCustomerKa" value="" />
                                        <input type="hidden" name="printSale" id="printSale" value="0" />
                                        
                                        <div class="row" style="margin-top: -13px;">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="num_of_items">Number Of Items</label>
                                                    <input type="text" name="num_of_items" id="num_of_items" class="form-control" placeholder="Number Of Items" autocomplete="off" readonly="" />
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="form-group">
                                                    <label class="remark">Remark</label>
                                                    <input autocomplete="off" type="text" class="form-control" id="remark" name="remark">
                                                </div>
                                            </div>
                                            <?php if ($this->session->userdata('salesman_estimate') == 'Yes') { ?>
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
                                            <span class="col-5 p-0" style="font-size: 14px">Total Tax</span>
                                            <div class="col-7 p-0">
                                                <input type="number" step="any" id="totalTax" name="totalTax" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Expenses</span>
                                            <div class="col-7 p-0">
                                                <input type="number" step="any" id="expenses" name="expenses" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">CESS</span>
                                            <div class="col-7 p-0">
                                                <input type="number" step="any" id="cess" name="cess" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Total Value</span>
                                            <div class="col-7 p-0">
                                                <input type="number" step="any" id="totalValue" name="totalValue" required="true" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Round Off</span>
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
                                        <?php if ($CompProfile->lrdate== 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px;">L.R. Date</span>
                                            <div class="col-7 p-0">
                                                <input type="date" id="lrDate" name="lrDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <?php } if ($CompProfile->lrNumber == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px;">L.R. No.</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="lrNumber" name="lrNumber" class="form-control">
                                            </div>
                                        </div>
                                        <?php } if ($CompProfile->eway == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px;">E-Way Bill</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="ewayBill" name="ewayBill" class="form-control">
                                            </div>
                                        </div>
                                        <?php } if ($CompProfile->dpb == 'yes') { ?>
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
                                        <?php } if ($CompProfile->lot_no == 'Y') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px;">LOT No</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="lot_no" name="lot_no" class="form-control" required="true" value="">
                                            </div>
                                        </div>
                                        <?php } if ($CompProfile->lh == 'Y') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px;">Lh</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="lh" name="lh" class="form-control" required="true" value="">
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px;" title="Old Amount">Old Balance</span>
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
                                        <button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg" style="width: auto;">
                                            <u>Create</u> Invoice
                                        </button>
                                        <?php } else { ?>
                                        <span class="text-danger">Your license has been expired,<br />Please contact your administrator.</span>
                                        <?php } } else { ?>
                                        <button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg" style="width: auto;">
                                            <u>Create</u> Invoice
                                        </button>
                                        <?php } if ($this->session->userdata('RoleType')) { if (json_decode(get_title('employee_permission', ['EmployeeId' => $this->session->userdata('EmployeeId')], 'Orderbook'))->Delete == "Delete") { ?>
                                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-lg" id="Cancel" onclick="return OrderbookCancel();">
                                            <u>C</u>ancel
                                        </a>
                                        <?php } } else { ?>
                                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-lg" id="Cancel" onclick="return OrderbookCancel();">
                                            <u>C</u>ancel
                                        </a>    
                                        <?php } ?>
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

<?php include_once(APPPATH  . 'views/inventory_sep/estimate/commonjs.php'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        getCustomerDetail("<?= $OrderbookInfo->PartyId; ?>", 0);
        
        getOrderbookProductsForSaleInvoice("<?= $OrderBookId; ?>");
        
        window.setTimeout(function() {
            var TotalRows = [allUniqueRows.length - 1];
            $("#num_of_items").val(TotalRows);
        }, 500);
    });
    
    
    const getOrderbookProductsForSaleInvoice = (OrderbookId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Orderform/getOrderbookProductsForSaleInvoice",
            data    :   {OrderbookId: OrderbookId},
            success :   function(Response) {
                            var JSONResponse = JSON.parse(Response);
                            if (JSONResponse.Status == true) {
                                var ProductsData = JSONResponse.ProductDataArray;
                                for (var i = 0; i < ProductsData.length; i++) {
                                    prepareProducts(ProductsData[i]);
                                }
                                window.setTimeout(function() {
                                    $(".loader-bg").css("display", "none");
                                }, 3000);
                            }
                        }
        });
    };
    
    const OrderbookCancel = () => {
        var OrderbookNumber = document.getElementById('OrderbookNumber').value;
        if (OrderbookNumber == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Enter Orderbook Number First",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        var URL = "<?= base_url(); ?>Orderform/OrderbookCancel/" + "<?= $OrderbookInfo->Id; ?>";
            
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Cancel it!"
        }).then((result) => {
            if (result.isConfirmed) {
                OrderbookCancelHandler(URL);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    };
    
    const OrderbookCancelHandler = (URL) => {
        $.ajax({
            type    :   "GET",
            url     :   URL,
            success :   function(Response) {
                            var JSONResponse = JSON.parse(Response);
                            if (JSONResponse.Status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: JSONResponse.Message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                    <?php if ($this->session->userdata('RoleType')) { ?>
                                    window.location.href = '<?= base_url(); ?>Employee/OrderbookReport';
                                    <?php } else { ?>
                                    window.location.href = '<?= base_url(); ?>Orderform';
                                    <?php } ?>
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: JSONResponse.Message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    };
    
    $(".EnableOrderbookNumber").click(function() {
        $("#OrderbookNumber").prop("readonly", false);
    });    
    
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
</script>

<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>