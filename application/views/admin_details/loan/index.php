<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="container-fluid mb-9" style="margin-top:20px;">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Loan Category List</label>
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
                            <div class="row mt-5">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
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
<script>
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('loan/loan_list/'); ?>' + page_num,
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



    $(document).ready(function() {

        $('select').selectize({
            sortField: 'text',
        });
        // $('#dataList').DataTable({
        //     "autoWidth": false,
        //     "lengthChange": false,
        //     paging: false,
        //     ordering: false,
        //     info: false,
        //     dom: 'Blfrtip',
        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print'
        //     ],

        // });


    });
</script>