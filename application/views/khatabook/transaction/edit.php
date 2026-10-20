<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Update Party</h5>
                        <div class="align-items-center">
                            <div class="ps-3">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                        <strong><?php echo $this->session->flashdata('msg'); ?></strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php
                                }
                                ?>
                                <form action="<?= base_url() ?>khatabook/edit_transaction/<?= $result->id ?>" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="party">Party Name<span class="text-danger">*</span></label>
                                            <select class="form-control" name="party">
                                                <option value='' selected>Select Party</option>
                                                <?php
                                                $conditions = ['khatabook_company_id' => $this->session->userdata('khatabook_company_id')];
                                                $getPartyResult = $this->user_model->getResult('khatabook_party', $conditions);
                                                if ($getPartyResult) {
                                                    foreach ($getPartyResult as $partyList) { ?>
                                                        <option value="<?= $partyList->id ?>" <?= set_value('party', $result->party_id) == $partyList->id ? 'selected' : '' ?>><?= $partyList->party_name ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('party'); ?>
                                            </span>
                                        </div>
                                        <input type="hidden" name="id" value="<?= $result->id ?>">

                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="transaction_type">Transaction Type<span class="text-danger">*</span></label>
                                            <select class="form-control" name="transaction_type">
                                                <option value='' selected>Transaction Type</option>
                                                <option value="Debit" <?= set_value('transaction_type', $result->transaction_type) == 'Debit' ? 'selected' : '' ?>>Debit </option>
                                                <option value="Credit" <?= set_value('transaction_type', $result->transaction_type) == 'Credit' ? 'selected' : '' ?>>Credit</option>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('transaction_type'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4  col-sm-12 col-lg-4">
                                            <label for="amount">Amount<span class="text-danger">*</span></label>
                                            <input id="amount" name="amount" min="0" placeholder="Amount" type="number" class="form-control" required="true" value="<?= set_value('amount', $result->amount) ?>">
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('amount'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <label for="remark">Remark<span></span></label>
                                            <textarea name="remark" type="text" class="form-control" required="true" value=""><?= set_value('remark', $result->remark) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
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