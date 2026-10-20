<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style>
        .control-label {
            color: red;
        }

        label {
            color: aliceblue;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header  pb-8 pt-5 pt-md-8" style="background: #2C4668;">
                        <div class="container-fluid mb-9" style="margin-top:100px;">
                            <div class="col">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <label class="mb-0" style="vertical-align: -moz-middle-with-baseline;color: black;">Digital Signature Applied List</label>
                                        <div class="post-search-panel">
                                            <div class="row mt-1">
                                                <div class="col-md-3">
                                                    <input type="text" id="keywords" class="form-control" placeholder="Type user name to filter services" onkeyup="searchFilter();" />
                                                </div>
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
                                            </div>
                                        </div>
                                             <?php if ($this->session->flashdata('msg')) { ?>
                                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                                <?php echo $this->session->flashdata('msg'); ?>
                                            </div>
                                        <?php 
                                        } ?>

                                        <div class="table-responsive mt-2">
                                            <table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataList">

                                            </table>

                                        </div>
                                        <div class="loading" style="display: none;position:fixed;left:50%;top:50%;">
                                            <div class="content"><img src="<?php echo base_url('assets/images/loader.png'); ?>" /></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('supermaster/ajaxPaginationData/'); ?>' + page_num,
            data: 'page=' + page_num + '&keywords=' + keywords + '&sortBy=' + sortBy + '&pagelength=' + pagelength,
            beforeSend: function() {
                $('.loading').show();
            },
            success: function(html) {
                $('#dataList').html(html);
                $('.loading').fadeOut("slow");
            }
        });
    }
</script>