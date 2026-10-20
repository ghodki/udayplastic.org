<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><?= get_title('khatabook_party', ['id' => $this->session->userdata('party_id')], 'party_name') ?> Transction List</h5>
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
                                    <select id="transaction_type" class="form-control" onchange="searchFilter();">
                                        <option value="" selected>Transaction Type</option>
                                        <option value="Credit">Credit</option>
                                        <option value="Debit">Debit</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="column_filter" class="form-control" onchange="searchFilter();">
                                        <option value="" selected>Search By</option>
                                        <option value="created_at">Created At</option>
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
        var transaction_type = $('#transaction_type').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('khatabook/user_load_khatabook_transaction/'); ?>' + page_num,
            data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pagelength=' + pagelength + '&column_filter=' + column_filter + '&transaction_type=' + transaction_type,
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