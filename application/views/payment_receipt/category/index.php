<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Create Payment Category</h5>
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
                                
                                <form action="<?= base_url(); ?>paymentReceipt/categorysave" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="category">Category Name <span class="text-danger">*</span></label>
                                            <input type="text" name="category" value="<?= set_value('category'); ?>" id="category" placeholder="Category Name" class="form-control" required="true" />
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('category'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="prefix">Prefix</label>
                                            <input type="text" name="prefix" value="<?= set_value('prefix'); ?>" id="prefix" placeholder="Prefix" class="form-control" />
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <label for="receipt_number">Receipt Number <span class="text-danger">*</span></label>
                                            <input type="text" name="receipt_number" value="<?= set_value('receipt_number'); ?>" id="receipt_number" placeholder="Receipt Number 0001, 0002" class="form-control" />
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-lg-8">
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            <textarea name="description" id="description" class="form-control" placeholder="Description"><?= set_value('description'); ?></textarea>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?= form_error('description'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Save
                                            </button>
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Payment Receipt Category List</h5>
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
                                        <option value="" disabled="">Search By</option>
                                        <option value="category" selected="">Category Name</option>
                                        <option value="prefix">Prefix</option>
                                        <option value="receipt_number">Receipt Number</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="keywords" class="form-control" placeholder="Type category name to filter" onkeyup="searchFilter();" />
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped align-items-center table-flush" style="width: 100%; min-height: 300px;" id="dataList"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<script type="text/javascript">
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        var column_filter = $('#column_filter').val();
        
        $.ajax({
            type    :   'POST',
            url     :   '<?= base_url('paymentReceipt/load_category/'); ?>' + page_num,
            data    :   'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pagelength=' + pagelength + '&column_filter=' + column_filter,
            success :   function(html) {
                            $('#dataList').html(html);
                        }
        });
    }
</script>