<style type="text/css">
    u {
        color: unset;
    }
    #view_selected_party span {
        color: #ffffff;
    }
    th, td {
        white-space: nowrap;
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
    @media (max-width:420px) {
        .inner_table {
            min-height: 200px;
        }
    }
    @media (max-width:400px) {
        .inner_table {
            min-height: 200px;
        }
    }
    @media (max-width:380px) {
        .inner_table {
            min-height: 200px;
        }
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
    
    $Where = ['comp_id' => $CompId, 'user_id' => $UserId];
    if ($this->session->userdata('BranchRole')){
        $Where['CompanyBranchId'] = $CompanyBranchId;
    }
    $CompProfile = $this->user_model->getRow('comp_profile', $Where);
    $Barcode = $this->session->userdata('barcode');
?>

<div class="content-wrapper" id="donknow">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Gadget Edit</h4>
                                        <select hidden class="form-control" onchange="changeFormat()" name="formats" id="formats">
                                            <option selected="" value="Estimate" <?= set_value('formats') == 'Estimate' ? 'selected' : ''; ?>>Estimate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <label class="text-success">
                                                Last Voucher Amount : 
                                                <i class="fa fa-inr"></i> 
                                                <?php
                                                    if ($this->session->userdata('RoleType')) {
                                                        $EmployeeWhere = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'), true);
                                                        echo $this->db->select('total')->where($Where)->where('status', 1)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('sales')->row('total'); 
                                                    } else {
                                                        echo $this->db->select('total')->where($Where)->where('status', 1)->order_by('id', 'DESC')->get('sales')->row('total'); 
                                                    }
                                                ?>
                                            </label>
                                            <a href="javascript:void(0);" class="btn btn-outline-primary btn-xs" title="Warehouse">
                                                <i class="fa fa-home"></i> <span id="warehouse_name"></span>
                                            </a>
                                            &nbsp;
                                            <a href="javascript:void(0);" title="Expand" class="btn btn-info btn-sm" onclick="toggleData();">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                            &nbsp;
                                            <?php if ($this->session->userdata('RoleType')) { ?>
                                            <a href="<?= base_url(); ?>Employee/SalesReport" class="btn btn-dark btn-sm" title="Sale List">
                                                <i class="fa fa-list"></i>
                                            </a>                                            
                                            <?php } else { ?>
                                            <a href="<?= base_url(); ?>gadget/report" class="btn btn-dark btn-sm" title="Sale List">
                                                <i class="fa fa-list"></i>
                                            </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Select Warehouse -->
                        <div class="modal fade" id="warehouse_modal" tabindex="-1" role="dialog" aria-labelledby="title" aria-hidden="true">
                            <div class="modal-dialog">
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
                                                                $this->db->where($Where);
                                                                $warehouses = $this->db->get('warehouse')->result();
                                                                if (!empty($warehouses)) { foreach ($warehouses as $key => $warehouse) {
                                                            ?>
                                                            <option <?= $CompProfile->warehouse == $warehouse->id ? "selected" : ""; ?> value="<?= $warehouse->id; ?>" <?= set_select('warehouse', $warehouse->id); ?>><?= $warehouse->name; ?> - <?= $warehouse->location; ?></option>
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
                        
                        <form action="<?= base_url(); ?>gadget/updateSale" novalidate class="form-horizontal" id="dataForm" method="POST" onsubmit="onFormSubmitHandler(event);" autocomplete="off">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-10" id="fulldisplay">
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <div class="row mb-1">
                                                    <label for="invoiceNumber" class="col-4">Voucher No.</label>
                                                    <div class="col-8 p-0">
                                                        <input autocomplete="nope" tabindex="1" readonly type="text" class="form-control" required="true" style=" width: 92%; height: 42px; color: red;font-size: 20px;" name="invoiceNumber" id="invoiceNumber" value="<?= $invoiceNumber; ?>">
                                                        <input type="hidden" value="" name="series_id" />
                                                        <span class="edit_invoice editInvoiceNumberEnable" title="Edit Invoice" id="edit_invoice" style="cursor: pointer;">
                                                            <i class="fa fa-lg fa fa-edit text-primary editInvoiceNumberEnable" style="float: right;margin-top: -24px;"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="invoiceDate" class="col-4">Date</label>
                                                    <input type="date" tabindex="2" class="form-control col-8" required="true" name="invoiceDate" id="invoiceDate" value="<?= date('Y-m-d') ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" />
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="voucherType" class="col-4" title="Voucher Type">Payment Mode</label>
                                                    <select tabindex="3" name="voucherType" id="voucherType" required="true" class="form-control col-<?= $CompProfile->pos == 'Y' ? '4' : '8'; ?>">
                                                        <option value="1" <?= set_value('voucherType') == '1' ? 'selected' : ''; ?>>Cash</option>
                                                        <option value="2" <?= set_value('voucherType') == '2' ? 'selected' : ''; ?>>Credit</option>
                                                        <option value="Card" <?= set_value('voucherType') == 'Card' ? 'selected' : ''; ?>>Card</option>
                                                        <option value="Phone Pay" <?= set_value('voucherType') == 'Phone' ? 'selected' : ''; ?>>Phone Pay</option>
                                                        <option value="Google Pay" <?= set_value('voucherType') == 'Google' ? 'selected' : ''; ?>>Google Pay</option>
                                                        <option value="Paytm" <?= set_value('voucherType') == 'Paytm' ? 'selected' : ''; ?>>Paytm</option>
                                                        <option value="RTGS" <?= set_value('voucherType') == 'RTGS' ? 'selected' : ''; ?>>RTGS</option>
                                                        <option value="NEFT" <?= set_value('voucherType') == 'NEFT' ? 'selected' : ''; ?>>NEFT</option>
                                                        <option value="Cheque" <?= set_value('voucherType') == 'Cheque' ? 'selected' : ''; ?>>Cheque</option>
                                                        <option value="IMPS" <?= set_value('voucherType') == 'IMPS' ? 'selected' : ''; ?>>IMPS</option>
                                                    </select>
                                                </div>
                                                <div class="mb-1 row" hidden>
                                                    <label for="taxcal" class="col-4">Tax Cal</label>
                                                    <select name="taxcal" tabindex="4" onchange="changeTaxCal();" id="taxcal" required class="form-control col-8">
                                                        <option value="1" <?= set_value('taxcal') == '1' ? 'selected' : ''; ?>>Inclusive</option>
                                                        <option value="2" <?= set_value('taxcal') == '2' ? 'selected' : ''; ?>>Exclusive</option>
                                                    </select>
                                                </div>                                                
                                            </div>
                                            <!-- due to outside of the form we write here for set value and get -->
                                            <input type="hidden" name="format_invoice" id="format_invoice" value="Tax">
                                            <input type="hidden" name="shipping_hideen_address" id="shipping_hideen_address" value="">

                                            <div class="col-sm-5">
                                                <div class="row mb-1 ">
                                                    <label for="customerId" class="col-3">Party</label>
                                                    <div class="col-9 p-0">
                                                        <div id="party_idDiv">
                                                            <input type="hidden" name="customerId" id="party_id" value="" required="true">
                                                            <input type="text" autocomplete="off" tabindex="5" placeholder="Party Name" class="form-control col-8 modal-lg" name="partyname" id="partyname" onkeypress="return enterKeyPressed(event)" onkeydown="return keyDown(event)" style="width: 100%;height:47px;" value="<?= get_title('parties', ['id' => set_value('party_id')], 'name') ?>" required="true">
                                                        </div>
                                                        <div id="party_data_append"></div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-1">
                                                    <label for="Mobile" class="col-3">Mobile </label>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" cols="4" tabindex="8" class="form-control" id="customerMobile" name="customerMobile" placeholder="Mobile" required="" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3" hidden>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="input-group mb-1">
                                                            <input type="text" placeholder="State Name" tabindex="10" class="form-control col-8 modal-lg" name="statename" onchange="getStateNames()" id="statename" style="width: 100%;" value="<?= get_title('state_list', ['id' => set_value('customerState')], 'state') ?>" required="true">
                                                            <input type="hidden" name="customerState" id="stateSelect" style="width: 100%;" value="" required="true">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- table -->
                                        <div class="row mb-3" style="width:100%; min-height: 350px; height:auto; margin-top:-11px;">
                                            <div class="col-sm-12">
                                                <table class="table table-bordered inner_table table-responsive-sm mb-0" id="dataTable">
                                                    <thead class="bg-dark" style="position: sticky; top: 0;">
                                                        <tr>
                                                            <th class="text-white">Sr</th>
                                                            <?php if (isset($Barcode) && !empty($Barcode) && $Barcode == 'yes') { ?>
                                                            <th class="text-white" width="100px;">P Code</th>
                                                            <?php } ?>
                                                            <th class="text-white" width="150px">
                                                                Product Name
                                                            </th>
                                                            <th class="text-white">Desc.</th>
                                                            <th class="text-white">Qty</th>
                                                            <th class="text-white" width="80x">Rate</th>
                                                            <?php if ($this->session->userdata('discount') == 'yes') { ?>
                                                            <th class="text-white" width="20px">Disc.</th>
                                                            <?php } ?>
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
                                                    <input type="text" required="" readonly="" class="form-control" style="font-size: 20px;color:red;border:none;" id="amountInWords" name="amountInWords">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="total_amount_for">Total Amount</label>
                                                    <input type="text" required="" readonly="" class="form-control" style="font-size: 20px;color:red;border:none;" id="total_amount_for">
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="nameCustomerKa" id="nameCustomerKa" value="">
                                        <input type="hidden" name="printSale" id="printSale" value="0">
                                        
                                        <div class="row" style="margin-top:-13px;">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="remark">Number Of Items</label>
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
                                            <div class="col-sm-3" style="display: <?= $this->session->userdata('role_type') == "Salesman" ? "none" : "block"; ?>">
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
                                        <?php if ($this->session->userdata('lrdate') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">L.R. Date</span>
                                            <div class="col-7 p-0">
                                                <input type="date" id="lrDate" name="lrDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <?php } if ($this->session->userdata('lrNumber') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">L.R. No.</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="lrNumber" name="lrNumber" class="form-control">
                                            </div>
                                        </div>
                                        <?php } if ($this->session->userdata('eway') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">E-Way Bill</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="ewayBill" name="ewayBill" class="form-control">
                                            </div>
                                        </div>
                                        <?php } if ($this->session->userdata('dpb') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Disp. By</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="transport" name="transport" class="form-control">
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Order No</span>
                                            <div class="col-7 p-0">
                                                <input type="text" class="form-control" name="orderNumber" id="orderNumber" placeholder="Order Number">
                                            </div>
                                        </div>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Order Date</span>
                                            <div class="col-7 p-0">
                                                <input type="date" class="form-control" name="orderDate" id="orderDate" value="<?php echo date('Y-m-d'); ?>" required="true">
                                            </div>
                                        </div>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Vehicle No</span>
                                            <div class="col-7 p-0">
                                                <input type="text" class="form-control" name="vehicle" id="vehicle" value="<?php ?>" required="true">
                                            </div>
                                        </div>
                                        <?php if ($this->session->userdata('ptd') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px" title="Pay Terms (Day)">PTD.</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="payTermsDay" name="payTermsDay" class="form-control" placeholder="Pay Terms Day">
                                            </div>
                                        </div>
                                        <?php } if ($this->session->userdata('duedate') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Due Date</span>
                                            <div class="col-7 p-0">
                                                <input type="date" id="dueDate" name="dueDate" required="true" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <?php } if ($CompProfile->lot_no == 'Y') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">LOT No</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="lot_no" name="lot_no" class="form-control" required="true" value="">
                                            </div>
                                        </div>
                                        <?php } if ($CompProfile->lh == 'Y') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Lh</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="lh" name="lh" class="form-control" required="true" value="">
                                            </div>
                                        </div>
                                        <?php } if ($this->session->userdata('dcno') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">DC No.</span>
                                            <div class="col-7 p-0">
                                                <input type="text" id="dcNumber" name="dcNumber" class="form-control">
                                            </div>
                                        </div>
                                        <?php } if ($this->session->userdata('dcdate') == 'yes') { ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">DC Date</span>
                                            <div class="col-7 p-0">
                                                <input type="date" id="dcDate" name="dcDate" class="form-control" required="true" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px" title="Old Amount">
                                                Old Balance
                                            </span>
                                            <div class="col-7 p-0">
                                                <input type="number" id="oldBalance" name="oldBalance" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row form-group mb-1">
                                            <span class="col-5 p-0" style="font-size: 14px">Total</span>
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
                                            <u style="font-size: 18px;">S</u>ave
                                        </button>
                                        <?php } else { ?>
                                        <span class="text-danger">Your license has been expired,<br />Please contact your administrator.</span>
                                        <?php } } else { ?>
                                        <button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg">
                                            <u style="font-size: 18px;">S</u>ave
                                        </button>
                                        <?php } ?>
                                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-lg" id="Delete" onclick="return deleteRow();">
                                            <u style="font-size: 18px;">C</u>ancelled
                                        </a>
                                        <a href="javascript:void(0);" id="updateInvoice" class="btn btn-outline-warning btn-lg" onclick="return updateInvoice();">
                                            <u style="font-size: 18px;">U</u>pdate
                                        </a>
                                        <a href="javascript:void(0);" id="printSales" class="btn btn-outline-success btn-lg" onclick="printSale();">
                                            <u style="font-size: 18px;">P</u>rint
                                        </a>
                                        <a href="javascript:void(0);" id="printSales" class="btn btn-outline-info btn-lg" onclick="printSalePreview();" >
                                            <u style="font-size: 18px;">P</u>rint <u style="font-size: 18px;">P</u>review
                                        </a>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-xl-4 col-lg-4 btn-display">
                                        <div class="pull-right">
                                            <a href="javascript:void(0);" class="btn btn-outline-dark btn-lg" onclick="return preview();">
                                                <u style="font-size: 18px;">P</u>revious
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-outline-secondary btn-lg" onclick="return next();">
                                                <u style="font-size: 18px;">N</u>ext
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
    
<?php include_once('common_js.php'); ?>