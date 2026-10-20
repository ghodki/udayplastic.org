<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5 mb-2">
                <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">Send Mail Report</h3>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?= base_url(); ?>useremail" class="btn btn-info btn-sm text-white pull-right" title="Back to user email">
                                        <i class="fa fa-arrow-circle-left"></i> Back
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    <div class="post-search-panel mt-2">
                                        <div class="row">
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
                                                    <option value="250">500</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select id="column_filter" class="form-control" onchange="searchFilter();">
                                                    <option value="" disabled="">Search By</option>
                                                    <option value="send_from" selected="">From</option>
                                                    <option value="group_id">Group Name</option>
                                                    <option value="subject">Subject</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" id="keywords" class="form-control" placeholder="Type group name, email to filter" onkeyup="searchFilter();" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-striped" style="width: 100%;" id="dataList"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- message start -->
<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <span aria-hidden="true">×</span> Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- message end -->

<script type="text/javascript">
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $("#keywords").val();
        var sortBy = $("#sortBy").val();
        var pagelength = $("#pagelength").val();
        var column_filter = $("#column_filter").val();

        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('useremail/load_mailreport/'); ?>" + page_num,
            data    :   "page=" + page_num + "&keywords=" + keywords + "&sortBy=" + sortBy + "&pagelength=" + pagelength + "&column_filter=" + column_filter,
            success :   function(html) {
                            $("#dataList").html(html);
                        }
        });
    }
    
    
    function showMessage(id) {
        $("#message .modal-dialog .modal-body").load("<?= base_url('useremail/messagebody/'); ?>" + id);
        $("#message").modal("show");
    }
</script>