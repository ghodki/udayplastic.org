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
                                <?php } ?>
                                
                                <form action="<?= base_url(); ?>paymentReceipt/partysave" method="POST" enctype="multipart/form-data">
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
                                            <input type="text" name="pan" value="<?= set_value('pan'); ?>" id="pan" class="form-control" placeholder="PAN Number" minlength="10" maxlength="10" required="" />
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
                                            <label for="city">City</label>
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
                                    <select id="usertype2" class="form-control" onchange="searchFilter();">
                                        <option value="" selected="">User Type</option>
                                        <option value="Customer">Customer</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="column_filter" class="form-control" onchange="searchFilter();">
                                        <option value="" disabled="">Search By</option>
                                        <option selected="" value="party_name">Name</option>
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

<script type="text/javascript">
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $("#keywords").val();
        var sortBy = $("#sortBy").val();
        var pagelength = $("#pagelength").val();
        var column_filter = $("#column_filter").val();
        var usertype = $("#usertype2").val();
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('paymentReceipt/load_party/'); ?>" + page_num,
            data    :   "page=" + page_num + "&keywords=" + keywords + "&sortBy=" + sortBy + "&pagelength=" + pagelength + "&column_filter=" + column_filter + "&usertype=" + usertype,
            success :   function(html) {
                            $("#dataList").html(html);
            }
        });
    }
</script>