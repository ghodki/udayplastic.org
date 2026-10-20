<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Update Party Transaction</h5>
                        <div class="align-items-center">
                            <div class="ps-3">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                    <strong><?php echo $this->session->flashdata('msg'); ?></strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php } ?>
                                <form action="<?= base_url(); ?>paymentReceipt/edit_transaction/<?= $result->id; ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $result->id; ?>" />
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="receipt_date">Receipt Date <span class="text-danger">*</span></label>
                                            <input type="date" name="receipt_date" value="<?= set_value('receipt_date', $result->receipt_date); ?>" id="receipt_date" class="form-control" title="Receipt Date" required=""  />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('receipt_date'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="paymentcategory">Payment Category <span class="text-danger">*</span></label>
                                            <select name="paymentcategory" id="" class="form-control" title="Payment Category" required="">
                                                <option value="<?= $result->paymentcategory; ?>" selected=""><?= get_title('payment_receipt_category', ['id' => $result->paymentcategory], 'category'); ?></option>
                                                <?php if ($category) { foreach ($category as $key => $categoryinfo) { ?>
                                                <option value="<?= $categoryinfo->id; ?>"><?= $categoryinfo->category; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('paymentcategory'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="party">Party Name <span class="text-danger">*</span></label>
                                            <select name="party" id="party" class="form-control" title="Select Party" required="" onchange="GetPartyPan(this.value);">
                                                <option value="" disabled="" selected="">Select Party</option>
                                                <?php
                                                    $conditions = ['payment_receipt_company_id' => $this->session->userdata('payment_receipt_company_id')];
                                                    $getPartyResult = $this->user_model->getResult('payment_receipt_party', $conditions);
                                                    if ($getPartyResult) {
                                                        foreach ($getPartyResult as $partyList) { 
                                                ?>
                                                <option value="<?= $partyList->id; ?>" <?= set_value('party', $result->party_id) == $partyList->id ? 'selected' : '' ?>><?= $partyList->party_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('party'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="donor_pan">Donor PAN <span class="text-danger">*</span></label>
                                            <input type="text" name="donor_pan" value="<?= set_value('donor_pan', $result->donor_pan); ?>" id="donor_pan" class="form-control" placeholder="Donor PAN" required="" minlength="10" maxlength="10" readonly="" />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('donor_pan'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="transaction_type">Transaction Type <span class="text-danger">*</span></label>
                                            <select name="transaction_type" id="transaction_type" class="form-control">
                                                <option value="" disabled="" selected="">Transaction Type</option>
                                                <option value="Debit" <?= set_value('transaction_type', $result->transaction_type) == 'Debit' ? 'selected' : '' ?>>Debit </option>
                                                <option value="Credit" <?= set_value('transaction_type', $result->transaction_type) == 'Credit' ? 'selected' : '' ?>>Credit</option>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('transaction_type'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="transaction_id">Vide Cheque/DD/RTGS <span class="text-danger">*</span></label>
                                            <input type="text" name="transaction_id" value="<?= set_value('transaction_id', $result->transaction_id); ?>" id="transaction_id" class="form-control" placeholder="Vide Cheque/DD/RTGS" required="" />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('transaction_id'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="transaction_date">Transaction Date <span class="text-danger">*</span></label>
                                            <input type="date" name="transaction_date" value="<?= set_value('transaction_date', $result->transaction_date); ?>" id="transaction_date" class="form-control" title="Transaction Date" required=""  />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('transaction_date'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="bank_name">Name of Bank <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_name" value="<?= set_value('bank_name', $result->bank_name); ?>" id="bank_name" class="form-control" placeholder="Name of Bank" required=""  />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('bank_name'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="amount">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" value="<?= set_value('amount', $result->amount); ?>" id="amount" class="form-control" placeholder="Amount" required="" min="0" />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('amount'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" class="form-control" placeholder="Remark"><?= set_value('remark', $result->remark); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                            <a href="<?= base_url(); ?>paymentReceipt/transaction" class="btn btn-danger">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Sales Card -->

        </div><!-- End Left side columns -->
        <!-- </div> -->
    </section>
</main><!-- End #main -->

<script type="text/javascript">
    function GetPartyPan(partyid) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('paymentReceipt/getpartypan'); ?>",
            data    :   {partyid: partyid},
            success :   function (data) {
                            $("#donor_pan").val(data);
            }
        });
    }
</script>