<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Create Party</h5>
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
                                <form action="<?= base_url() ?>khatabook/partysave" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="usertype">User Type<span class="text-danger">*</span></label>
                                            <select class="form-control" name="usertype" required="true">
                                                <option value="" selected>Select UserType</option>
                                                <option value="Customer">Customer</option>
                                                <option value="Supplier">Supplier</option>
                                            </select>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('usertype'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <label for="name">Party Name<span class="text-danger">*</span></label>
                                            <input id="name" name="name" placeholder="Party Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('name'); ?>
                                            </span>
                                        </div>

                                        <div class="col-md-4  col-sm-12 col-lg-4">
                                            <label for="number">Mobile Number<span class="text-danger">*</span></label>
                                            <input id="number" name="number" placeholder="Mobile Number" type="tel" class="form-control" required="true" minlength="10" maxlength="10" value="<?= set_value('number') ?>">
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('number'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <label for="address">Address<span></span></label>
                                            <textarea name="address" type="text" class="form-control" required="true" value=""><?= set_value('address') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Save
                                            </button>
                                            <a href="#" type="reset" class="btn btn-secondary">Cancel</a>
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
                        <h5 class="card-title">Party List</h5>
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
                                <div class="col-md-2">
                                    <select id="usertype" class="form-control" onchange="searchFilter();">
                                        <option value="" selected>UserType</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Supplier">Supplier</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="column_filter" class="form-control" onchange="searchFilter();">
                                        <option value="" disabled>Search By</option>
                                        <option value="party_name" selected>Name</option>
                                        <option value="number">Phone Number</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="keywords" class="form-control" placeholder="Type user name to filter services" onkeyup="searchFilter();" />
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped align-items-center table-flush" style="width: 100%;min-height:300px;" id="dataList">
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
        var usertype = $('#usertype').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('khatabook/load_party/'); ?>' + page_num,
            data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pagelength=' + pagelength + '&column_filter=' + column_filter + '&usertype=' + usertype,
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