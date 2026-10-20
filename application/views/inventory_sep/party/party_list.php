<style type="text/css">
    table.dataTable tbody td {
        padding: 2px 5px;
    }
    table.dataTable tbody th, tbody td {
        font-size: .7rem;
    }
    th, td {
        white-space: nowrap;
    }
    .form-group {
        margin-bottom: 0rem;
    }
    .search-list-party-name {
        color: #000;
        list-style: none;
        position: relative;
        left: -25px;
        border-bottom: 0.5px solid #e7e7e7;
        padding-top: 5px;
        width: 300px;
        font-size: 12px;
    }
    .search-list {
        color: #000;
        list-style: none;
        position: relative;
        left: -25px;
        border-bottom: 0.5px solid #e7e7e7;
        padding-top: 5px;
        width: 150px;
        font-size: 12px;
    }
    .select2-container {
        width: 100% !important;
    }
</style>

<?php
    $UserId = $this->session->userdata('user_id');
    $CompId = $this->session->userdata('comp_id');
    $CompanyBranchId = $this->session->userdata('CompBranchId');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <div class="card card-outline card-navy">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-navy">
                                    Parties
                                </h5>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-outline-danger btn-sm" title="Delete" id="delete-product" style="display: none;">
                                    <i class="fa fa-trash fa-lg"></i>
                                </button>
                                <a href="<?= base_url(); ?>party/exportparty" class="btn btn-primary btn-sm" title="Export" id="Export_PARTY">
                                   <i class="fas fa-file-export fa-lg"></i>
                                </a>
                                <button class="btn btn-info btn-sm" title="Upload" id="upload-product" data-toggle="modal" data-target="#importModal">
                                    <i class="fa fa-upload fa-lg text-white"></i>
                                </button>
                                <button class="btn btn-default btn-sm" title="Copy" id="copy-product">
                                    <i class="fa fa-clone fa-lg"></i>
                                </button>
                                <button class="btn btn-success btn-sm" title="Add" data-toggle="modal" data-target="#add-party">
                                    <i class="fa fa-plus fa-lg text-white"></i>
                                </button>
                                <a href="<?= base_url(); ?>party/parties" class="btn btn-warning btn-sm" title="Party Bulk Update">
                                    <i class="fa fa-users"></i>
                                </a>
                            </div>
                        </div>
                             <div class="card-body">
                                     <form action="<?= base_url(); ?>party/index" method="GET">
                                        <div class="row">
                                                <div class="col-md-3 col-sm-3 form-group">
                                                    <label for="salesmanId">Salesman's</label>
                                                    <select name="salesmanId" id="salesmanId" class="form-control form-control-sm" title="Salesman's">
                                                        <option <?= $this->input->get('salesmanId') == "All" ? "selected" : ""; ?> value="All" <?= set_select('salesmanId', 'All'); ?>>All</option>
                                                        <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $salesman) { ?>
                                                        <option <?= $this->input->get('salesmanId') == $salesman->Id ? "selected" : ""; ?> value="<?= $salesman->Id; ?>" <?= set_select('salesmanId', $salesman->Id); ?>><?= $salesman->Name; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-sm-3 form-group">
                                                    <label for="categoryId">Categories</label>
                                                    <select name="categoryId" id="categoryId" class="form-control form-control-sm" title="Categories">
                                                        <option <?= $this->input->get('categoryId') == "All" ? "selected" : ""; ?> value="All" <?= set_select('categoryId', 'All'); ?>>All</option>
                                                        <?php if (!empty($categories)) { foreach ($categories as $key => $category) { ?>
                                                        <option <?= $this->input->get('categoryId') == $category->id ? "selected" : ""; ?> value="<?= $category->id; ?>" <?= set_select('categoryId', $category->id); ?>><?= $category->name; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-sm-3 form-group">
                                                    <label for="areaName">Area Name</label>
                                                    <select name="areaName" id="areaName" class="form-control form-control-sm" title="Area Name">
                                                        <option <?= $this->input->get('areaName') == "All" ? "selected" : ""; ?> value="All" <?= set_select('areaName', 'All'); ?>>All</option>
                                                        <?php if (!empty($areaLists)) { foreach ($areaLists as $key => $areaData) { ?>
                                                        <option <?= $this->input->get('areaName') == $areaData->area ? "selected" : ""; ?> value="<?= $areaData->area; ?>" <?= set_select('areaName', $areaData->area); ?>><?= $areaData->area; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                        <button type="submit" class="btn btn-sm bg-olive">
                                                                <i class="fa fa-search text-white"></i> Search
                                                        </button>
                                                        <a href="<?= base_url(); ?>party/index" class="btn btn-danger btn-sm" title="Refresh">
                                                                <i class="fa fa-refresh"></i>
                                                        </a>
                                                </div>
                                            </div>
                                     </form>
                                     <div class="table-responsive mt-2">
                                           <table class="table table-bordered table-sm dataTable">
                                                   <thead class="bg-navy">
                                                           <tr>
                                                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="">
                                                                         <input type="checkbox" id="product-selectAll" />
                                                                         <label for="product-selectAll"></label>
                                                                  </th>
                                                                  <th>Sr.</th>
                                                                  <th>Name</th>
                                                                  <th>Acc Group</th>
                                                                  <th>Contact</th>
                                                                  <th>GST</th>
                                                                  <th>Address</th>
                                                                  <th>Area</th>
                                                                  <th>City</th>
                                                                  <th>Salesman</th>
                                                                  <th>Category</th>
                                                                  <th>Action</th>
                                                          </tr>
                                                   </thead>
                                                   <tbody>
                                                           <?php 
                                                                 if ($parties) {
                                                                         $partyIds = array_column($parties, 'id');
                                                                         $saleCustomerIds = [];
                                                                         $estimateCustomerIds = [];
                                                                         $purchaseCustomerIds = [];
                                                                         $estimatePurchaseCustomerIds = [];

                                                                         if (!empty($partyIds)) {
                                                                             $sales = $this->db->select('customerId')->where_in('customerId', $partyIds)->get('sales')->result_array();
                                                                             $saleCustomerIds = array_column($sales, 'customerId');

                                                                             $estimates = $this->db->select('customerId')->where_in('customerId', $partyIds)->get('estimates')->result_array();
                                                                             $estimateCustomerIds = array_column($estimates, 'customerId');

                                                                             $purchases = $this->db->select('customerId')->where_in('customerId', $partyIds)->get('purchases')->result_array();
                                                                             $purchaseCustomerIds = array_column($purchases, 'customerId');

                                                                             $estimatePurchases = $this->db->select('customerId')->where_in('customerId', $partyIds)->get('estimate_purchase')->result_array();
                                                                             $estimatePurchaseCustomerIds = array_column($estimatePurchases, 'customerId');
                                                                         }
                                                
                                                                        foreach ($parties as $key => $row) {
                                                                                $saleCustomerId = in_array($row->id, $saleCustomerIds);
                                                                                $estimateCustomerId = in_array($row->id, $estimateCustomerIds);
                                                                                $purchaseCustomerId = in_array($row->id, $purchaseCustomerIds);
                                                                                $estimatePurchaseCustomerId = in_array($row->id, $estimatePurchaseCustomerIds);

                                                                                if ($saleCustomerId || $estimateCustomerId || $purchaseCustomerId || $estimatePurchaseCustomerId) {
                                                                                    $bgColor = "ffffff";
                                                                                    $display = "none;";
                                                                                } else {
                                                                                    $bgColor = "ff000061";
                                                                                    $display = "contents;";
                                                                                }
                                                          ?>
                                                          <tr style="background-color: #<?= $bgColor; ?>;">
                                                                  <td class="cheking">
                                                                         <input class="checkdata" type="checkbox" name="party_id[]" value="<?= $row->id; ?>" />
                                                                  </td>
                                                                  <td><?= $key + 1; ?></td>
                                                                  <td style="white-space: normal;"><?= $row->name; ?></td>
                                                                  <td><?= get_title('accounting_group', ['id' => $row->group_head], 'name'); ?></td>
                                                                  <td><?= $row->contact; ?></td>
                                                                  <td><?= $row->gst; ?></td>
                                                                  <td style="white-space: normal;"><?= $row->address_1; ?></td>
                                                                  <td><?= $row->area; ?></td>
                                                                  <td><?= get_title('districts', ['id' => $row->city], 'name'); ?></td>
                                                                  <td><?= get_title('employees', ['Id' => $row->SalesmanId], 'Name'); ?></td>
                                                                  <td><?= get_title('party_category', ['id' => $row->categoryId], 'name'); ?></td>
                                                                  <td>
                                                                         <a href="javascript:void(0);" onclick="partyEdit(<?= $row->id; ?>);" class="text-primary" title="Edit">
                                                                                 <i class="fa fa-edit"></i>
                                                                         </a>
                                                                         &nbsp;
                                                                         <a href="javascript:void(0);" style="display: <?= $display; ?>;" onclick="return deleteRow('<?= base_url(); ?>party/party_delete/<?= $row->id; ?>');" class="text-danger" title="Delete">
                                                                                 <i class="fa fa-trash"></i>
                                                                         </a>
                                                                  </td>
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
    </div>
</div>

<!-- Import File -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="import-frm" method="POST" action="<?= base_url() ?>party/importparty" enctype="multipart/form-data" novalidate="novalidate">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p>
                                <b>Note</b> <i class="fa fa-angle-double-right"></i>
                                <br />
                                <span class="text-danger">
                                    1. Party name, contact number, email and pan number should be unique.
                                </span>
                                <br />
                                <span class="text-danger" style="text-align:left;">
                                    2. Party Name, state name, city and group head are mandatory.
                                </span>
                                <br />
                                <span class="text-danger">
                                    3. State, group head, city Should be Match from given.
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <a href="<?= base_url() ?>sample/party_sample.xlsx" class="float-right" download>
                                    <i class="fa fa-download"></i>
                                    <br />
                                    Download Template
                                </a>  
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Upload File</label>
                                <input type="file" name="file" data-error=".logo" class="form-control" required="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                <div class="logo"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-upload"></i> Import
                    </button>
                    <?php } else { ?>
                    <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                    <?php } } else { ?>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-upload"></i> Import
                    </button>
                    <?php } ?>
                    <button type="button" class="btn btn-sm  btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Import File End -->

<!-- Add Party -->
<div class="modal fade" id="add-party" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Party</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Card stats -->
                <div class="row">
                    <div class="text-center" id="productMsg" style="opacity: 500; display: none;"></div>
                    <form id="onProductFormSubmitHandler" action="<?= base_url(); ?>party/partyAdd" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event);">
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="group">Accounting Group <span class="text-danger">*</span></label>
                                <select name="group" id="group" class="form-control form-control-sm select2Dropdown" tabindex="1" data-live-search="true" required>
                                    <option value="">Select Accounting Group</option>
                                    <?php
                                        $tax = $this->user_model->getResult('accounting_group');
                                        $role = $this->session->userdata('roles');
                                        if ($role == 1) {
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $units) {
                                    ?>
                                    <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } } else {
                                        if (isset($tax) && !empty($tax)) {
                                            foreach ($tax as $units) {
                                                $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'bill_id' => $units->id];
                                                $resu = $this->user_model->getRow('accountant_status', $where);
                                                if (isset($resu) && !empty($resu)) {
                                                    if ($resu->status == 1) {
                                    ?>
                                    <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } else { ?>
                                    <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } } } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control form-control-sm textOnly" tabindex="2" onkeydown="return keyDownForPartyNameList(event);" oninput="getPartyName(this.value);" placeholder="Name" autocomplete="off" required="" />
                                <div id="party_name_list"></div>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('name'); ?>
                                </span>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="contact">Contact Person</label>
                                <input autocomplete="off" type="text" name="ContactPerson" tabindex="4" id="ContactPerson" value="<?= set_value('ContactPerson'); ?>" placeholder="Contact Person" class="form-control form-control-sm" />
                                <span class="col-sm-4 control-label">
                                    <?= form_error('ContactPerson'); ?>
                                </span>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="contact">Contact Number</label>
                                <input autocomplete="off" type="tel" name="contact" tabindex="3" id="contact" value="<?= set_value('contact'); ?>" placeholder="Contact Number" class="form-control form-control-sm" required="" minlength="10" maxlength="10" />
                                <span class="col-sm-4 control-label">
                                    <?= form_error('contact'); ?>
                                </span>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="email">Email</label>
                                <input autocomplete="off" type="email" name="email" tabindex="4" id="email" value="<?= set_value('email') ?>" placeholder="Email" class="form-control form-control-sm" />
                                <span class="col-sm-4 control-label">
                                    <?= form_error('email'); ?>
                                </span>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="address_1">Address</label>
                                <textarea name="address_1" id="address_1" class="form-control form-control-sm" tabindex="5" rows="1" autocomplete="off" placeholder="Address"><?= set_value('address_1'); ?></textarea>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('address_1'); ?>
                                </span>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="area">Area</label>
                                <textarea name="area" id="area" class="form-control form-control-sm" rows="1" tabindex="6" autocomplete="off" placeholder="Area" oninput="getPartyArea(this.value);"><?= set_value('area'); ?></textarea>
                                <div id="party_area_list"></div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="state">State <span class="text-danger">*</span></label>
                                <select name="state" id="state" class="form-control form-control-sm select2Dropdown" tabindex="7" data-live-search="" required="" />
                                    <option value="">Select State</option>
                                    <?php 
                                        $states = $this->db->get('state_list')->result();
                                        if ($states) {
                                            foreach ($states as $row) { 
                                    ?>
                                    <option value="<?= $row->id ?>" <?= $this->session->userdata('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?>(<?= $row->code?>)</option>
                                    <?php } } ?>
                                </select>
                                <span class="col-sm-4 control-label">
                                    <?= form_error('state'); ?>
                                </span>
                            </div>
                            <div class="form-group col-sm-3" hidden>
                                <label class="control-label" for="state_code">Code</label>
                                <input autocomplete="off" type="text" readonly="" name="state_code" id="code" value="" placeholder="Code" class="form-control form-control-sm" required="">
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="city">City</label>
                                <input type="text" name="city" value="<?= set_value('city'); ?>" id="city" class="form-control form-control-sm" tabindex="8" autocomplete="off" placeholder="City Name" oninput="getPartyCity(this.value);" />
                                <div id="party_city_list"></div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="pin">Pin Code</label>
                                <input autocomplete="off" type="text" name="pin" id="pin" tabindex="9" placeholder="Pin Code" value="<?= set_value('pin') ?>" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control form-control-sm">
                            </div>
                               <div class="form-group col-sm-3">
                                <label class="control-label" for="segement">Segment</label>
                                <input autocomplete="off" height="100px;" type="text" name="segement" id="segement" tabindex="10" placeholder="Segment" value="<?= set_value('segement') ?>" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="pan">Pan No</label>
                                <input autocomplete="off" type="text" name="pan" tabindex="11" id="pan" placeholder="Pan No" value="<?= set_value('pan') ?>" class="form-control form-control-sm toUpper" maxlength="10">
                                <span class="col-sm-4 control-label">
                                    <?= form_error('pan'); ?>
                                </span>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="credit_limit">Credit Amount Limit</label>
                                <input type="text" name="credit_limit" value="<?= set_value('credit_limit'); ?>" id="credit_limit" class="form-control form-control-sm" autocomplete="off" tabindex="12"  placeholder="Credit Amount Limit" onkeypress="return IsNumeric(event);" maxlength="20" />
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="credit_limit_day">Credit Day Limit</label>
                                <input type="text" name="credit_limit_day" value="<?= set_value('credit_limit_day'); ?>" id="credit_limit_day" class="form-control form-control-sm" tabindex="13" autocomplete="off" placeholder="Credit Day Limit" onkeypress="return IsNumeric(event);" />
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="gstType">GST Type</label>
                                <select name="gstType" id="gstType" class="form-control form-control-sm" tabindex="14">
                                    <option value="">-Select-</option>
                                    <option value="Regular" <?= set_value('gstType') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                                    <option value="Composite" <?= set_value('gstType') == 'Composite' ? 'selected' : ''; ?>>Composite</option>
                                    <option value="Tax Deductor" <?= set_value('gstType') == 'Tax Deductor' ? 'selected' : ''; ?>>Tax Deductor</option>
                                    <option value="N/A" <?= set_value('gstType') == 'N/A' ? 'selected' : ''; ?>>N/A</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-3" id="gstTypeVal" style="display:none;">
                                <label class="control-label" for="gst">
                                    <a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No &gt;&gt;</a>
                                </label>
                                <input type="hidden" name="eInvoiceApiModeAccessTokne" id="eInvoiceApiModeAccessTokne" value="" />
                                <input autocomplete="off" type="hidden" name="ledger_gst_details" id="ledger_gst_details" value="" placeholder="GST No"> 
                                <input autocomplete="off" type="text" name="gst" id="<?php if(getUserPermission()->eInvoice=="Y"){ echo "gst"; }else{ echo "gstold";  } ?>" onkeyup="checkStateCode()" tabindex="15" placeholder="GST No" value="" class="form-control form-control-sm" maxlength="15">
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="opening_balance">Opening Balance</label>
                                <input autocomplete="off" type="text" name="opening_balance" id="opening_balance" tabindex="16" placeholder="Opening Balance" value="<?= set_value('opening_balance') ?>" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="opBalnceType">Type</label>
                                <select name="opBalnceType" id="opBalnceType" tabindex="17" class="form-control form-control-sm">
                                    <option value="">-Select-</option>
                                    <option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR</option>
                                    <option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR</option>
                                </select>
                            </div>
                            <?php if ($this->session->userdata('salesman_estimate') == 'Yes' || $this->session->userdata('salesman_sale') == 'Yes') { ?>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="SalesmanId">Salesman</label>
                                <select name="SalesmanId" id="SalesmanId" class="form-control form-control-sm select2Dropdown" title="Salesman" tabindex="18" required="">
                                    <option value="" disabled="">Select Salesman</option>
                                    <?php
                                        $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                                        $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
                                        if ($this->session->userdata('BranchRole')) {
                                            $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                                        }
                                        $Salesmans = $this->db->select('Id, Name')->where($SalesmanWhere)->get('employees')->result();
                                        if (!empty($Salesmans)) {
                                            foreach($Salesmans as $key => $Salesman) {
                                    ?>
                                    <option value="<?= $Salesman->Id; ?>" <?= set_select('SalesmanId', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <?php } ?>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="partyCategoryId">Party Category</label>
                                <select name="partyCategoryId" id="partyCategoryId" class="form-control form-control-sm select2Dropdown" title="Party Category" tabindex="18">
                                    <option value="" disabled="" selected="">Select Party Category</option>
                                    <?php
                                        $partyCategoryWhere = ['isDeleted' => 1, 'userId' => $UserId, 'compId' => $CompId];
                                        if ($this->session->userdata('BranchRole')) {
                                            $partyCategoryWhere['compBranchId'] = $CompanyBranchId;
                                        }
                                        $partyCategories = $this->db->select('id, name')->where($partyCategoryWhere)->get('party_category')->result();
                                        if (!empty($partyCategories)) {
                                            foreach($partyCategories as $key => $categoryData) {
                                    ?>
                                    <option value="<?= $categoryData->id; ?>" <?= set_select('partyCategoryId', $categoryData->id); ?>><?= $categoryData->name; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="LocationQR">Location QR</label>
                                <input type="file" name="LocationQR" id="LocationQR" class="form-control form-control-sm" tabindex="19" title="Location QR" accept="image/*" style="line-height: 1.2;" />
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="sms">Send SMS</label>
                                <br />
                                <input type="checkbox" name="sms" id="sms" tabindex="20" />
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label" for="remark">Remark</label>
                                <textarea name="remark" id="remark" class="form-control form-control-sm" tabindex="21" rows="1" autocomplete="off" placeholder="Remark"><?= set_value('remark'); ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group ml-auto pr-5 pt-2">
                                <?php if(getUserPermission()->eInvoice=="Y"){ ?>
                                <input type="button" id="refresh_gst" onclick="checkGstDetails()" class="btn btn-outline-primary" style="cursor: pointer;" class="btn btn-info" value="Refresh Gst" title="GST synchronize with GST Service Provider" />
                                <?php } ?>   
                                <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                <input type="submit" class="btn btn-primary" value="Save" />
                                <?php } else { ?>
                                <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                <?php } } else { ?>
                                <input type="submit" class="btn btn-primary" value="Save" />
                                <?php } ?>
                                <input type="reset" class="btn btn-danger" value="Reset" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Party End -->

<div class="modal fade" id="exam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels_party" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabels_party"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2Dropdown').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent()
            });
        });        
    });
    
    function deleteRow(url) {
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
                        window.location.reload();
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

    var inputs = document.querySelectorAll("input, select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                //  alert(e.which)
                nextInput[0].focus();
            }
        })
    }
    
    // Select All
    $("#product-selectAll").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    // Delete Selected Value
    $(function() {
        $("#delete-product").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                if (!confirm('Are you sure to Deletes the Parties?')) return false;
                console.log(selected)
                $.ajax({
                    type: "POST",
                    url: '<?= base_url("index.php/party/deleteParties") ?>',
                    data: {
                        id: selected,
                    },
                    dataType: 'json',
                    success: function(data) {
                        // data = JSON.parse(data);
                        console.log(data);
                        if (data.status == true) {
                            toastr.success(data.data);

                        } else {
                            toastr.error(data.data);

                        }
                        setInterval(function() {
                            location.reload();
                        }, 1000);

                    },
                    error: function() {
                        alert("Something is Wrong");
                    }

                });
            } else {
                alert("Select First");
            }

        });

    });
    
    // COPY DATA
    $(function() {
        $("#copy-product").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                console.log(selected[0])
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/party/getParties/" + selected[0],
                    dataType: 'json',
                    success: function(data) {
                        // data = JSON.parse(data);
                        console.log(data);
                        if (data.status == true) {
                            console.log(data);
                            data = data.data;
                            $('#add-party').modal('show');
                            $("#group").val(data.group_head);
                            $("#name").val(data.name);
                            $("#contact").val(data.contact);
                            $("#address_1").val(data.address_1);
                            $("#area").val(data.area);
                            $("#email").val(data.email);
                            $("#state").val(data.state);
                            getCityName(data.city);
                            $("#pin").val(data.pin);
                            $("#pan").val(data.pan);
                            $("#credit_limit").val(data.credit_limit);
                            $("#credit_limit_day").val(data.credit_limit_day);
                            $("#gstType").val(data.gstType);
                            $("#gst").val(data.gst);
                            $("#opBalnceType").val(data.opBalnceType);
                            $("#opening_balance").val(data.opening_balance);
                            $('#SalesmanId option[value="'+ data.SalesmanId +'"]').attr("selected", "selected");


                        } else {
                            toastr.error("", data.data);

                        }
                    },
                    error: function() {
                        alert("Something is Wrong");
                    }

                });
            } else {
                alert("Select First");
            }

        });

    });

    const getCityName = (city_id) => {
        $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>district/getCityName",
                data    :   {city_id: city_id},
                success :   function (Response) {                                                      
                                if (Response) {
                                    $("#city").val(Response);
                                }
                            }
        });             
    };

    $(document).ready(function() {
        // salesman selection filter
        $('#salesmanId').select2();
        
        // category selection filter
        $('#categoryId').select2();
        
        // category selection filter
        $('#areaName').select2();        

        // add party
        $('#submitButton').click(function() {
            $('#import_form').ajaxForm({
                target: '#outputImage',
                url: '<?= base_url() ?>',
                beforeSubmit: function() {
                    $("#outputImage").hide();
                    if ($("#uploadImage").val() == "") {
                        $("#outputImage").show();
                        $("#outputImage").html("<div class='error'>Choose a file to upload.</div>");
                        return false;
                    }
                    $("#progressDivId").css("display", "block");
                    var percentValue = '0%';

                    $('#progressBar').width(percentValue);
                    $('#percent').html(percentValue);
                },
                uploadProgress: function(event, position, total, percentComplete) {

                    var percentValue = percentComplete + '%';
                    $("#progressBar").animate({
                        width: '' + percentValue + ''
                    }, {
                        duration: 5000,
                        easing: "linear",
                        step: function(x) {
                            percentText = Math.round(x * 100 / percentComplete);
                            $("#percent").text(percentText + "%");
                            if (percentText == "100") {
                                $("#outputImage").show();
                            }
                        }
                    });
                },
                error: function(response, status, e) {
                    alert('Oops something went.');
                },

                complete: function(xhr) {
                    if (xhr.responseText && xhr.responseText != "error") {
                        $("#outputImage").html(xhr.responseText);
                    } else {
                        $("#outputImage").show();
                        $("#outputImage").html("<div class='error'>Problem in uploading file.</div>");
                        $("#progressBar").stop();
                    }
                }
            });
        });
    });

    function submitHandler() {
        var checkboxes = $('input:checkbox:checked').length;
        if (length > 0) { // alert("select first");
            alert("select fisrt")
            return false;
        }
        return true;
    }

    $(document).ready(function() {
        $('.dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'pdfHtml5',
                'print',
                'pageLength'
            ],
            "lengthMenu": [[50, 100, 250, -1], [50, 100, 250, "All"]]
        });
    });
    // $(document).on('expanded.pushMenu', handleExpandedEvent);

    jQuery.noConflict();

    function onProductFormSubmitHandler(e) {
        e.preventDefault();
        var url = $("#onProductFormSubmitHandler").attr("action");
        var form = $("#onProductFormSubmitHandler")[0];
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
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    toastr.success(customerList.msg);
                    setTimeout(function() {
                        location.reload();
                    }, 2000)

                } else {
                    $("#productMsg").show();
                    $("#productMsg").addClass("text");
                    $("#productMsg").addClass("text-danger");
                    $("#productMsg").html(customerList.msg);

                }
            },
           
        });
    }

    function partyEdit(productId) {
        jQuery.noConflict();
        $("#exam .modal-dialog .modal-body").load(baseurl + "party/edit_party/" + productId);
        $("#exampleModalLabels_party").html("Update Party");
        $("#exam").modal("show");
    }
    
    //GST CODE
    $(document).ready(function() {
        $('#gstTypeVal').hide();       
        
        $('#gstType').change(function() {
            if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
                $('#gstTypeVal').show();
                $('#gst').attr('required', true);
            } else {
                $('#gstTypeVal').hide();
                $('#gst').attr('required', false);
            }
        });
        <?php if (isset($error) && !empty($error)) { ?>
            $('#gstTypeVal').show();
        <?php } ?>
    });

    function checkStateCode() {
             let gstValue = $("#gst").val();
             if (gstValue.length == 2) {
                     var code = gstValue;
                     $.ajax({
                             url: '<?= base_url('state/getStateByGst/'); ?>' + gstValue,
                             success: function(data) {
                                     data1 = JSON.parse(data);
                                     $("#state").val(data1.data.id);
                                     $("#code").val(data1.data.code);
                             }
                     });

             }
     }
        
    const getPartyName = (PartyName) => {
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
                                var partyopt = `<ul class="search-ul" id="party_name_list_ul">`;
                                var n = 0;
                                PartyData.forEach((element) => {
                                    var number = n++;
                                    partyopt += `<li class="search-list-party-name" id="party_name_li${number}" onkeydown="return keyDownForPartyName(event, ${number});" onkeyup="return keyUpForPartyName(event, ${number});" onclick="onSelectgetPartyName('${element.name}');">${element.name}</li>`;
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
    
    const keyDownForPartyName = (e, row) => {
        // down arrow key
        if (e.keyCode == '40') {
            alert();
            var elmtTable = document.getElementById('party_name_list_ul');
            var tableRows = elmtTable.getElementsByTagName('li');
            var rowCount = tableRows.length;
            if (rowCount > row) {
                if (row < 0) {
                    row = 0;
                }
                row = row + 1;
                var previrow = row - 1;
                var start = document.getElementById('party_name_li' + row);
                var previous = document.getElementById('party_name_li' + previrow);
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                }
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            } else {
                var start = document.getElementById('party_name_li' + row);
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    };
    
    const keyUpForPartyName = (e, row) => {
        // up arrow key
        if (e.keyCode == '38') {
            if (row == 0) {
                var previous = document.getElementById('party_name_li' + row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black';
                document.getElementById('name').focus();
                return;
            }
            var elmtTable = document.getElementById('party_name_list_ul');
            var tableRows = elmtTable.getElementsByTagName('li');
            var rowCount = tableRows.length;
            previrow = '';
            if (rowCount > row) {
                if (row <= 0) {
                    row = 0;
                } else {
                    row = row - 1;
                    var previrow = row + 1;
                }
                var start = document.getElementById('party_name_li' + row);
                if (previrow) {
                    var previous = document.getElementById('party_name_li' + previrow);
                    if (previous) {
                        previous.style.backgroundColor = 'white';
                        previous.style.color = 'black';
                    }
                }
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    };
    
    const keyDownForPartyNameList = (e) => {
        if (e.keyCode == '40') {
            var start = document.getElementById('party_name_li0');
            start.focus();
            start.style.backgroundColor = 'blue';
            start.style.color = 'white';
        }
    };

    function checkGstDetails1() {
        var gst = $("#gst").val(); 
        console.log(gst,'gst func'); 
        if (gst != '') { 
             $(".loader").show();
            gst_num_varifie(gst); 
            //alert(gst);  
        } else {

            alert("Please Enter Gst Number");

        }
    }
</script>