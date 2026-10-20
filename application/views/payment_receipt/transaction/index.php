<style type="text/css">
    .fade:not(.show) {
        opacity: 1;
    }
    .modal-backdrop {
        background-color: #0000;
    }
    .select2 {
        width: 100% !important;
    }
</style>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                <strong><?php echo $this->session->flashdata('msg'); ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php } ?>
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title mb-0 pl-4">Create Transaction</h5>
                            </div>
                            <div class="col-md-6 text-right mt-2">
                                <a href="javascript:void(0);" data-target="#importModal" data-toggle="modal" title="Upload emails data" class="btn btn-dark btn-sm">
                                    <i class="fa fa-upload"></i> Import Transaction
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="align-items-center">
                            <form action="<?= base_url(); ?>paymentReceipt/transaction_save" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="receipt_date">Receipt Date <span class="text-danger">*</span></label>
                                        <input type="date" name="receipt_date" value="<?= set_value('receipt_date', date('Y-m-d')); ?>" id="receipt_date" class="form-control" title="Receipt Date" required="" />
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('receipt_date'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="paymentcategory">Payment Category <span class="text-danger">*</span></label>
                                        <select name="paymentcategory" id="paymentcategory" class="form-control" title="Payment Category" required="" onchange="ReceiptNumber(this.value);">
                                            <option value="" disabled="" selected="">Payment Category</option>
                                            <?php if ($category) { foreach ($category as $key => $pcategory) { ?>
                                            <option value="<?= $pcategory->id; ?>"><?= $pcategory->category; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('paymentcategory'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="receipt_id">Receipt Number <span class="text-danger">*</span></label>
                                        <input type="text" name="receipt_id" value="<?= set_value('receipt_id'); ?>" id="receipt_id" class="form-control" title="Receipt Number" required="" readonly="" />
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('receipt_id'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="party">
                                            Party Name 
                                            <span class="text-danger">*</span>
                                            <a class="text-info" data-toggle="modal" data-target="#createparty" title="Create New Party">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            &nbsp;
                                            <a id="pid" class="text-primary" onclick="loadParty();" title="Update Party" data-id="">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </label>
                                        <select name="party" id="party" class="form-control" title="Select Party" required="" onchange="GetPartyPan(this.value);">
                                            <option value="" selected="" disabled="">Select Party</option>
                                            <?php
                                                $conditions = ['payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')];
                                                $getPartyResult = $this->user_model->getResult('payment_receipt_party', $conditions);
                                                if ($getPartyResult) {
                                                    foreach ($getPartyResult as $partyList) {
                                            ?>
                                            <option value="<?= $partyList->id; ?>" <?= set_value('party') == $partyList->id ? 'selected' : ''; ?>><?= $partyList->party_name; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('party'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="donor_pan">Donor PAN <span class="text-danger">*</span></label>
                                        <input type="text" name="donor_pan" value="<?= set_value('donor_pan'); ?>" id="donor_pan" class="form-control" placeholder="Donor PAN" required="" minlength="10" maxlength="10" readonly="" />
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('donor_pan'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="transaction_type">Transaction Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="transaction_type" id="transaction_type">
                                            <option value="Debit" <?= set_value('transaction_type', 'Debit'); ?>>Debit </option>
                                            <option value="Credit" <?= set_value('transaction_type', 'Credit'); ?> selected="">Credit</option>
                                        </select>
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('transaction_type'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="transaction_id">Vide Cheque/DD/RTGS <span class="text-danger">*</span></label>
                                        <input type="text" name="transaction_id" value="<?= set_value('transaction_id'); ?>" id="transaction_id" class="form-control" placeholder="Vide Cheque/DD/RTGS" required="" />
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('transaction_id'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="transaction_date">Transaction/Cheque Date<span class="text-danger">*</span></label>
                                        <input type="date" name="transaction_date" value="<?= set_value('transaction_date', date('Y-m-d')); ?>" id="transaction_date" class="form-control" title="Transaction/Cheque Date" required=""  />
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('transaction_date'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="bank_name">Name of Bank <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_name" value="<?= set_value('bank_name'); ?>" id="bank_name" class="form-control" placeholder="Name of Bank" required=""  />
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('bank_name'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input id="amount" name="amount" min="0" placeholder="Amount" type="number" class="form-control" required="true" value="<?= set_value('amount'); ?>" />
                                        <span class="col-sm-4 control-label text-danger">
                                            <?= form_error('amount'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <label for="remark">Remark</label>
                                        <textarea name="remark" id="remark" class="form-control" placeholder="Remark"><?= set_value('remark'); ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Send SMS <span class="text-danger">*</span></label>
                                        <br />
                                        <input type="radio" name="send_sms" value="Yes<?= set_radio('send_sms', 'Yes'); ?>" id="yes" title="Yes" required="" />
                                        Yes
                                        <input type="radio" name="send_sms" value="No<?= set_radio('send_sms', 'No'); ?>" id="no" title="No" style="margin-left: 30px;" required="" />
                                        No
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <input type="reset" class="btn btn-secondary" value="Reset" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- End Sales Card -->
        </div><!-- End Left side columns -->
    </section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Transaction List</h5>
                    </div>
                    <div class="card-body">
                        <div class="post-search-panel">
                            <div class="row mt-1">
                                <div class="col-md-2">
                                    <select id="sortBy" class="form-control" onchange="searchFilter();">
                                        <option value="">Sort by Title</option>
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="pagelength" class="form-control pagelength" onchange="searchFilter();">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="250">250</option>
                                        <option value="500">500</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="column_filter" class="form-control" onchange="searchFilter();">
                                        <option value="" selected>Search By</option>
                                        <option value="party_id">Party Name</option>
                                        <option value="amount">Amount</option>
                                        <option value="transaction_type">Transaction Type</option>
                                        <option value="remark">Remark</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="keywords" class="form-control" placeholder="Type user name to filter services" onkeyup="searchFilter();" />
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped align-items-center table-flush" style="width: 100%;" id="dataList">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<!-- Create Party -->
<div class="modal fade" id="createparty" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New Party</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>paymentReceipt/partysave2" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="usertype">User Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="usertype" id="usertype" required="">
                                <option selected="" value="Customer">Customer</option>
                            </select>
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('usertype'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="name">Party Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" placeholder="Party Name" required="true" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('name'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="number">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" name="number" value="<?= set_value('number'); ?>" id="number" class="form-control" placeholder="Mobile Number" required="true" minlength="10" maxlength="10" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('number'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="pan">PAN Number <span class="text-danger">*</span></label>
                            <input type="text" name="pan" value="<?= set_value('pan'); ?>" id="pan2" class="form-control" placeholder="PAN Number" required="" minlength="10" maxlength="10" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('pan'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control" placeholder="Email" required="true" />
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('email'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="state_id">State <span class="text-danger">*</span></label>
                            <select name="state_id" id="state_id" class="form-control" title="Select State" required="">
                                <option disabled="" selected="" value="">Select State</option>
                                <?php if (!empty($statelist)) { foreach ($statelist as $key => $state) { ?>
                                <option value="<?= $state->id; ?>"><?= $state->state; ?></option>
                                <?php } } ?>
                            </select>
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('state_id'); ?>
                            </span>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="city_id">City</label>
                            <input type="text" name="city" value="<?= set_value('city'); ?>" id="city" class="form-control" placeholder="City" />
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control" placeholder="Address"><?= set_value('address'); ?></textarea>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <label for="pin">Pin</label>
                            <input type="tel" name="pin" value="<?= set_value('pin'); ?>" id="pin" class="form-control" placeholder="Pin" minlength="6" maxlength="6" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Party -->
<div class="modal fade" id="editparty" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Party</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loadparty"></div>
        </div>
    </div>
</div>

<!-- import excel file start -->
<div class="modal fade" id="importModal" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="<?= base_url(); ?>paymentReceipt/importTransaction" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= base_url(); ?>sample/transaction_sample.xlsx" class="btn btn-info btn-sm" download>
                                <i class="fa fa-download"></i> Download Template
                            </a>
                        </div>
                        <div class="col-md-6">
                            <label>Send SMS <span class="text-danger">*</span></label>
                            <br />
                            <input type="radio" name="send_sms" value="Yes<?= set_radio('send_sms', 'Yes'); ?>" id="yes" title="Yes" required="" />
                            Yes
                            <input type="radio" name="send_sms" value="No<?= set_radio('send_sms', 'No'); ?>" id="no" title="No" style="margin-left: 30px;" required="" />
                            No
                            <span class="col-sm-4 control-label text-danger">
                                <?= form_error('send_sms'); ?>
                            </span>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Upload File <span class="text-danger">*</span></span>
                                <input type="file" name="file" id="file" class="form-control" required="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="height: calc(2.55rem + 2px);" />
                                <span class="col-sm-4 control-label text-danger">
                                    <?= form_error('file'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-upload"></i> Import
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <span aria-hidden="true">×</span> Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- import file end  -->

<!-- send mail modal start -->
<div class="modal fade" id="mailModal" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Compose mail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Close();">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="content"></div>
        </div>
    </div>
</div>
<!-- end mail modal end  -->

<?php include('payment_receipt/transaction/content.php'); ?>

<script type="text/javascript">
    searchFilter(0);
    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        var column_filter = $('#column_filter').val();
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('paymentReceipt/load_payment_receipt_transaction/'); ?>" + page_num,
            data    :   "page=" + page_num + "&keywords=" + keywords + "&sortBy=" + sortBy + "&pagelength=" + pagelength + "&column_filter=" + column_filter,
            success :   function (html) {
                            $('#dataList').html(html);
                        }
        });
    }
    
    function ReceiptNumber(categoryid) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('paymentReceipt/getreceiptnumber'); ?>",
            data    :   {categoryid: categoryid},
            success :   function (data) {
                            $("#receipt_id").val(data);
            }
        });
    }
    
    function GetPartyPan(partyid) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('paymentReceipt/getpartypan'); ?>",
            data    :   {partyid: partyid},
            success :   function (data) {
                            $("#donor_pan").val(data);
                            $("#pid").attr("data-id", partyid);
            }
        });
    }
    
    function loadBody(id) {
        $("#mailModal .modal-dialog .content").load("<?= base_url('paymentReceipt/mailcontent'); ?>/" + id);
        $("#mailModal").modal("show");
    }
    
    function loadParty() {
        $("#editparty .modal-dialog .loadparty").load("<?= base_url('paymentReceipt/loadparty'); ?>/" + $("#pid").data("id"));
        $("#editparty").modal("show");
    }
    
    function Close () {
        $("#mailModal").modal('hide');
    }
    
    function Close2 () {
        $("#editparty").modal('hide');
    }
</script>