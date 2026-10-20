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
</style>

<div class="loader-bg"></div>

<div class="content-wrapper" id="donknow">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form action="<?= base_url(); ?>Debit_note/save" novalidate="" class="form-horizontal" id="dataForm" method="POST" onsubmit="onFormSubmitHandler(event);">
                        <div class="card card-outline card-dark">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-dark">Debit Note</h4>
                                </div>
                                <div class="pull-right">
                                    <select class="form-control" hidden onchange="changeFormat()" name="formats" id="formats">
                                        <option value="Tax" <?= set_select('formats', 'Tax'); ?>>Tax</option>
                                        <option value="Estimate" <?= set_select('formats', 'Estimate'); ?>>Estimate</option>
                                    </select>
                                    <a href="javascript:void(0);" title="Expand" onclick="toggleData();" class="btn btn-danger btn-xs">
                                        <i class="fas fa-expand"></i>
                                    </a>
                                    <a href="<?= base_url(); ?>invent/debit_note_report" class="btn btn-primary btn-xs" title="Debit Note List">
                                        <i class="fas fa-list"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-10" id="fulldisplay">
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <div class="row mb-1">
                                                    <label for="CreditNoteinvoiceNumber" class="col-4"><small class="font-weight-bold">Credit Note Invoice No.</small></label>
                                                    <input type="text" name="CreditNoteInvoiceNumber" value="<?= set_value('CreditNoteInvoiceNumber', $CreditNoteInfo->invoiceNumber); ?>" id="CreditNoteInvoiceNumber" class="form-control col-8" placeholder="Credit Note Invoice Number" autocomplete="off" tabindex="1" required="" readonly="" />
                                                    <input type="hidden" name="CreditNoteId" value="<?= set_value('CreditNoteId', $CreditNoteInfo->id); ?>" id="CreditNoteId" />
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="invoiceNumber" class="col-4"><small class="font-weight-bold">Debit Note Invoice No.</small></label>
                                                    <?php
                                                        $SeriesWhere = ['series.comp_id' => $this->session->userdata('comp_id'), 'series.user_id' => $this->session->userdata('user_id'), 'series.status' => 2];
                                                        if ($this->session->userdata('BranchRole')) {
                                                            $SeriesWhere['series.CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                        }
                                                        $Series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id = series.series_type')->where('series', 'DebitNote')->where($SeriesWhere)->get()->row();

                                                        if (isset($Series) && !empty($Series)) {
                                                            $SeriesInvoiceNumber = $this->user_model->getSeris($Series);
                                                    ?>                                                    
                                                    <input type="text" name="invoiceNumber" value="<?= set_value('invoiceNumber', $SeriesInvoiceNumber); ?>" id="invoiceNumber" class="form-control col-8" placeholder="Debit Note Invoice Number" autocomplete="off" tabindex="1" required="" />
                                                    <input type="hidden" name="series_id" value="<?= $Series->series_id; ?>" id="series_id" />
                                                    <?php } else {
                                                        $this->db->select_max('unorder_invoice_number');
                                                        $this->db->where(['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id'), 'status' => 1]);
                                                        if ($this->session->userdata('BranchRole')) {
                                                            $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                        }
                                                        $query = $this->db->get('debit_notes')->row();
                                                        if ($query) {
                                                            $inital = $query->unorder_invoice_number;
                                                        } else {
                                                            $inital = 0;
                                                        }
                                                    ?>
                                                    <input type="text" name="invoiceNumber" value="<?= set_value('invoiceNumber', $inital + 1); ?>" id="invoiceNumber" class="form-control col-8" placeholder="Debit Note Invoice Number" autocomplete="off" tabindex="1" required="" />
                                                    <input type="hidden" name="series_id" value="<?= set_value('series_id'); ?>" id="series_id" />
                                                    <?php } ?>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="invoiceDate" class="col-4">Date</label>
                                                    <input type="date" tabindex="2" class="form-control col-8" required="true" name="invoiceDate" id="invoiceDate" value="<?php echo date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" />
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="voucherType" class="col-4" title="Voucher Type">Payment Mode</label>
                                                    <select tabindex="3" name="voucherType" id="voucherType" required="" class="form-control col-8">
                                                        <option value="1" <?= set_select('voucherType', '1'); ?><?= $CreditNoteInfo->voucherType == '1' ? 'selected' : ''; ?>>Cash</option>
                                                        <option value="2" <?= set_select('voucherType', '2'); ?><?= $CreditNoteInfo->voucherType == '2' ? 'selected' : ''; ?>>Credit</option>
                                                        <option value="Phone Pay" <?= set_select('voucherType', 'Phone Pay'); ?><?= $CreditNoteInfo->voucherType == 'Phone' ? 'selected' : ''; ?>>Phone Pay</option>
                                                        <option value="Google Pay" <?= set_select('voucherType', 'Google Pay'); ?><?= $CreditNoteInfo->voucherType == 'Google' ? 'selected' : ''; ?>>Google Pay</option>
                                                        <option value="Paytm" <?= set_select('voucherType', 'Paytm'); ?><?= $CreditNoteInfo->voucherType == 'Paytm' ? 'selected' : ''; ?>>Paytm</option>
                                                        <option value="RTGS" <?= set_value('voucherType', 'RTGS'); ?><?= $CreditNoteInfo->voucherType == 'RTGS' ? 'selected' : ''; ?>>RTGS</option>
                                                        <option value="NEFT" <?= set_value('voucherType', 'NEFT'); ?><?= $CreditNoteInfo->voucherType == 'NEFT' ? 'selected' : ''; ?>>NEFT</option>
                                                        <option value="Cheque" <?= set_value('voucherType', 'Cheque'); ?><?= $CreditNoteInfo->voucherType == 'Cheque' ? 'selected' : ''; ?>>Cheque</option>
                                                        <option value="IMPS" <?= set_value('voucherType', 'IMPS'); ?><?= $CreditNoteInfo->voucherType == 'IMPS' ? 'selected' : ''; ?>>IMPS</option>
                                                    </select>
                                                </div>
                                                <div class="mb-1 row">
                                                    <label for="taxcal" class="col-4">Tax Cal</label>
                                                    <select name="taxcal" tabindex="4" onchange="changeTaxCal()" id="taxcal" required="" class="form-control col-8">
                                                        <option value="1" <?= set_select('taxcal', '1'); ?>>Inclusive</option>
                                                        <option value="2" <?= set_select('taxcal', '2'); ?>>Exclusive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" name="format_invoice" id="format_invoice" value="Tax" />
                                            <div class="col-sm-5">
                                                <div class="row mb-1 ">
                                                    <label for="customerId" class="col-3">Party</label>
                                                    <div class="col-9 p-0">
                                                        <div id="party_idDiv" style="width: 81%; float: left; position: relative;">
                                                            <input type="hidden" name="customerId" id="party_id" value="" required="" />
                                                            <input type="text" tabindex="5" placeholder="Party Name" class="form-control col-8 modal-lg" name="partyname" id="partyname" style="width: 100%; height: 47px;" value="<?= get_title('parties', ['id' => set_value('party_id')], 'name'); ?>" required="" autocomplete="off" onkeypress="return enterKeyPressed(event);" onkeydown="return keyDown(event);" />
                                                        </div>
                                                        <span class="fr mr-1 addPatys" title="Add new customer" id="adding_party" data-toggle="modal" style="cursor: pointer;"><i style="margin-left:6px;" class="fa fa-lg fa-plus-circle text-primary"></i></span>
                                                        <span class="fr mr-1 edit_party" title="Edit" value="" id="editing_party" data-toggle="modal" style="cursor: pointer;"><i style="margin-left:6px;" class="fa fa-lg fa fa-edit text-primary"></i></span>
                                                        <span class="fr mr-1 copy_party" title="Copy Customer" id="copying_party" data-toggle="modal" style="cursor: pointer;"><i style="margin-left:6px;" class="fa fa-lg fa fa-copy text-primary"></i></span>
                                                        <div id="party_data_append"></div>
                                                    </div>
                                                </div>
                                                <div class="modal" id="ship_address_pop_up" tabindex="-1" role="dialog" aria-labelledby="shipping_address_aman" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="shipping_address_aman">Shipping Address</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body"></div>
                                                        </div>
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
                                                <!-- Party Select Start Modal -->
                                                <div class="modal fade modal-xlg" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle1">
                                                                    <strong>Select Party</strong>
                                                                </h5>
                                                                <input type="search" name="searchparty" onkeydown="return keyDown(event)" autocomplete="off" id="searchparty" onkeypress="return enterKeyPressed(event)" class="form-control">
                                                                <button type="button" id="closepartymodel" class="btn btn-danger" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  Party Select Modal Close-->
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
                                                    <thead class="bg-dark">
                                                        <tr>
                                                            <th class="text-white">Sr</th>
                                                            <?php
                                                                $barcode = $this->session->userdata('barcode');
                                                                if (isset($barcode) && !empty($barcode) && $this->session->userdata('barcode') == 'yes') {
                                                            ?>
                                                            <th class="text-white" width="120px;">PCode</th>
                                                            <?php } ?>
                                                            <th class="text-white" width="150px">
                                                                Product Name
                                                                <a class="cp ml-2 py-0" data-toggle="modal" data-target="#productModel" title="Add New Product"><i class="fa fa-plus-circle text-white"></i></a>
                                                            </th>
                                                            <th class="text-white">Des</th>
                                                            <th class="text-white" width="60px">HSN</th>
                                                            <th class="text-white" width="60px">Qty</th>
                                                            <th class="text-white" width="80x">Rate</th>
                                                            <?php
                                                                $discount = $this->session->userdata('discount');
                                                                if ($discount == 'yes') {
                                                            ?>
                                                            <th class="text-white" width="20px">Disc.</th>
                                                            <?php } ?>
                                                            <th class="text-white" width="50px">Tax %</th>
                                                            <th class="text-white" width="80px">Taxable Amt</th>
                                                            <th class="text-white" width="100px">Tax Amt</th>
                                                            <th class="text-white" width="100px">Net Amt</th>
                                                            <th class="text-white" width="45px"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- words -->
                                        <div class="row" style="margin-top:-13px;">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Amount in words</label>
                                                    <input type="text" required readonly class="form-control" style="border:none;height:48px" id="amountInWords" name="amountInWords">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Total Amount</label>
                                                    <input type="text" required readonly class="form-control" style="border:none;height:48px" id="total_amount_for">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="nameCustomerKa" id="nameCustomerKa" value="">
                                        <input type="hidden" name="printdebit_note" id="printdebit_note" value="0">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Remark</label>
                                                    <input autocomplete="off" type="text" class="col-sm-10 form-control" id="remark" name="remark" placeholder="Remark" />
                                                </div>
                                            </div>
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
                                            <span class="col-5 p-0" style="font-size: 14px" title="Old Amount">Old Balance</span>
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
                                <!-- Submit button -->
                                <div class="row btnsbmt">
                                    <div class="col-sm-6 col-md-6 col-xl-6">
                                        <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                        <button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg" style="width: 200px;">
                                            <u style="font-size: 18px;">Create</u> Invoice
                                        </button>
                                        <?php } else { ?>
                                        <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                        <?php } } else { ?>
                                        <button type="submit" id="btnSubmit" class="btn btn-outline-primary btn-lg" style="width: 200px;">
                                            <u style="font-size: 18px;">Create</u> Invoice
                                        </button>
                                        <?php } ?>
                                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-lg" id="Cancel" onclick="return DebitNoteCancel();">
                                            <u style="font-size: 18px;">C</u>ancel
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

<script type="text/javascript">
    $(document).ready(function() {
        getCustomerDetail("<?= $CustomerId; ?>", 0);
        
        getCreditNoteProductsForDebitNoteInvoice("<?= $CreditNoteInfo->id; ?>");
    });
    
    const getCreditNoteProductsForDebitNoteInvoice = (CreditNoteId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Debit_note/getCreditNoteProductsForDebitNoteInvoice",
            data    :   {CreditNoteId: CreditNoteId},
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
    
    const DebitNoteCancel = () => {
        var CreditNoteInvoiceNumber = document.getElementById('CreditNoteInvoiceNumber').value;
        if (CreditNoteInvoiceNumber == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Enter Credit Note Invoice Number First",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        var URL = "<?= base_url(); ?>Debit_note/DebitNoteCancel/" + "<?= $CreditNoteInfo->id; ?>";
            
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
                DebitNoteCancelHandler(URL);
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
    
    const DebitNoteCancelHandler = (URL) => {
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
                                    window.location.href = '<?= base_url(); ?>Reports/DebitNoteRequestReport';
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
</script>
<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>

<?php include_once('commonjs.php'); ?>