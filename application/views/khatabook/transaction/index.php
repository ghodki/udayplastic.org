<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Create Transaction</h5>
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
                                <form action="<?= base_url() ?>khatabook/transaction_save" method="POST" enctype="multipart/form-data">
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
                                                        <option value="<?= $partyList->id ?>" <?= set_value('party') == $partyList->id ? 'selected' : '' ?>><?= $partyList->party_name ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('party'); ?>
                                            </span>
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="transaction_type">Transaction Type<span class="text-danger">*</span></label>
                                            <select class="form-control" name="transaction_type">
                                                <option value='' selected>Transaction Type</option>
                                                <option value="Debit" <?= set_value('transaction_type') == 'Debit' ? 'selected' : '' ?>>Debit </option>
                                                <option value="Credit" <?= set_value('transaction_type') == 'Credit' ? 'selected' : '' ?>>Credit</option>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('transaction_type'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4  col-sm-12 col-lg-4">
                                            <label for="amount">Amount<span class="text-danger">*</span></label>
                                            <input id="amount" name="amount" min="0" placeholder="Amount" type="number" class="form-control" required="true" value="<?= set_value('amount') ?>">
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('amount'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <label for="remark">Remark<span></span></label>
                                            <textarea name="remark" type="text" class="form-control" required="true" value=""><?= set_value('remark') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Save
                                            </button>
                                            <input type="reset" class="btn btn-secondary" value="Reset">
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

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Transction List</h5>
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
<script>
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        var column_filter = $('#column_filter').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('khatabook/load_khatabook_transaction/'); ?>' + page_num,
            data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pagelength=' + pagelength + '&column_filter=' + column_filter,
            // beforeSend: function() {
            //     $('.loading').show();
            // },
            success: function(html) {
                $('#dataList').html(html);
                // $('.loading').fadeOut("slow");
            }
        });
    }

    function cancel() {
        window.location.href = "<?= base_url('userlist') ?>";
    }
</script>