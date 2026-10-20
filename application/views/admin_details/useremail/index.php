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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Create Group Email</h3>
                            <div class="pull-right">
                                <a href="javascript:void(0);" data-target="#getmodal" data-toggle="modal" title="Send group mail" class="btn btn-primary btn-sm">
                                    <i class="fa fa-send"></i> Send Mail
                                </a>
                                <a href="javascript:void(0);" data-target="#importModal" data-toggle="modal" title="Upload emails data" class="btn btn-dark btn-sm">
                                    <i class="fa fa-upload"></i> Import Email
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('useremail/saveuseremail') ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">
                                                Group Name <span class="text-danger">*</span>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#groupmodal" class="btn btn-info btn-sm text-white" title="Create new group name & manage" style="margin-top: -9px;">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </label>
                                            <select name="group_id" id="group_id" class="form-control" title="Select Group Name" required="">
                                                <option disabled="" selected="" value="">Select Group Name</option>
                                                <?php if (!empty($groupname)) { foreach ($groupname as $key => $gname) { ?>
                                                <option value="<?= $gname->id; ?>" <?= set_select('group_id', $gname->id)?>><?= $gname->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <span class="text-danger">
                                            <?= form_error('group_id'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-8 col-sm-6 col-lg-8">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control" placeholder="Example: demo@gmail.com" required="" />
                                        </div>
                                        <span class="text-danger">
                                            <?= form_error('email'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-primary btn-sm" value="Save" />
                                            <input type="reset" class="btn btn-danger btn-sm" value="Reset" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">Group Email List</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#groupemails" class="btn btn-info btn-sm text-white" title="Group Emails">
                                            Group Emails <i class="fa fa-envelope-open"></i>
                                        </a>
                                        &nbsp;
                                        <a href="<?= base_url(); ?>useremail/mailreport" class="btn btn-success btn-sm text-white pull-right" title="Send mail report">
                                            Send Mail Report <i class="fa fa-file"></i>
                                        </a>
                                    </div>
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
                                                    <option value="group_id" selected="">Group Name</option>
                                                    <option value="email">Email</option>
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

<!-- start send mail modal -->
<div class="modal fade" id="getmodal" tabindex="-1" role="dialog" aria-labelledby="getmodal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Send Email
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('useremail/sendmail'); ?>" method="POST">
                <input type="hidden" name="email_id" value="<?= $eval->id; ?>" id="email_id" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from">From <span class="text-danger">*</span></label>
                                <input type="email" name="from" value="<?= set_value('from'); ?>" id="from" class="form-control" placeholder="From" required="" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group_id">Select Group Name <span class="text-danger">*</span></label>
                                <select name="group_id" id="group_id" class="form-control" title="Select Group Name" required="">
                                    <option disabled="" selected="" value="">Select Group Name</option>
                                    <?php if (!empty($groupname)) { foreach ($groupname as $key => $gname) { ?>
                                    <option value="<?= $gname->id; ?>" <?= set_select('group_id', $gname->id)?>><?= $gname->name; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" value="<?= set_value('subject'); ?>" id="subject" class="form-control" placeholder="Subject" required="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="message">Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" required=""><?= set_value('message'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Mail</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end send mail modal -->

<!-- import excel file start -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="<?= base_url(); ?>useremail/importemails" enctype="multipart/form-data" novalidate="novalidate">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 pb-3">
                            <a href="<?= base_url(); ?>sample/email_sample.xlsx" class="btn btn-info btn-sm" download>
                                <i class="fa fa-download"></i> Download Template
                            </a>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Upload File</span>
                                <input type="file" name="file" id="file" class="form-control" required="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="height: calc(2.55rem + 2px);" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-upload"></i> Import
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <span aria-hidden="true">×</span> Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- import file end  -->

<!-- group name start -->
<div class="modal fade" id="groupmodal" tabindex="-1" role="dialog" aria-labelledby="groupmodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Group Name & Manage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (empty($groupinfo)) { ?>
                <form method="POST" action="<?= base_url(); ?>useremail/addgroup">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Group Name</span>
                                <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" placeholder="Group Name" required="" />
                            </div>
                        </div>
                    </div>
                    <div class="row pull-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Submit
                        </button>
                        &nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
                <?php } if (isset($id) && !empty($groupinfo)) { ?>
                <form method="POST" action="<?= base_url(); ?>useremail/updategroup">
                    <input type="hidden" name="groupid" value="<?= $groupinfo->id; ?>" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Group Name</span>
                                <input type="text" name="name" value="<?= set_value('name', $groupinfo->name); ?>" id="name" class="form-control" placeholder="Group Name" required="" />
                            </div>
                        </div>
                    </div>
                    <div class="row pull-right">
                        <button type="submit" class="btn btn-warning btn-sm">
                            Update
                        </button>
                        &nbsp;&nbsp;&nbsp;
                        <a href="<?= base_url(); ?>useremail" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            Cancel
                        </a>
                    </div>
                </form>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Group Name</th>
                                <th>Action(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($groupname)) { $n = 0; foreach ($groupname as $key => $gvalue) { $n++; ?>
                            <tr>
                                <td><?= $n; ?></td>
                                <td><?= $gvalue->name; ?></td>
                                <td>
                                    <a href="<?= base_url(); ?>useremail/editgroup/<?= $gvalue->id; ?>" class="text-success">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    &nbsp;
                                    <a href="<?= base_url(); ?>useremail/deletegroup/<?= $gvalue->id; ?>" class="text-danger" onclick="return confirm('Are you sure want to delete record ?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- group name end -->

<!-- group email start -->
<div class="modal fade" id="groupemails" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Group Email List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style="overflow-y: scroll; height: 500px">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Group Name</th>
                                        <th>Emails</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $this->db->select("*");
                                        $this->db->from("emailgroup");
                                        $query = $this->db->get();
                                        $listemails = $query->result();
                                        if (!empty($listemails)) {
                                            $n = 0;
                                            foreach ($listemails as $key => $gemail) {
                                                $n++;
                                    ?>
                                    <tr>
                                        <td><?= $n; ?></td>
                                        <td><?= $gemail->name; ?></td>
                                        <td>
                                            <?php
                                                $this->db->select("*");
                                                $this->db->from("usersemail");
                                                $this->db->where("group_id", $gemail->id);
                                                $query = $this->db->get();
                                                $emails = $query->result();
                                                foreach ($emails as $key => $email) {
                                                    echo $email->email . "<br />";
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <span aria-hidden="true">×</span> Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- group email end  -->

<script type="text/javascript">
    CKEDITOR.replace('message');
        
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $("#keywords").val();
        var sortBy = $("#sortBy").val();
        var pagelength = $("#pagelength").val();
        var column_filter = $("#column_filter").val();

        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('useremail/load/'); ?>" + page_num,
            data    :   "page=" + page_num + "&keywords=" + keywords + "&sortBy=" + sortBy + "&pagelength=" + pagelength + "&column_filter=" + column_filter,
            success :   function(html) {
                            $("#dataList").html(html);
                        }
        });
    }
    <?php if (!empty($id)) { ?>
    $(window).on('load', function() {
        $("#groupmodal").addClass("show");
        $("#groupmodal").css("display", "block");
    });
    $(".close").on('click', function() {
        $("#groupmodal").removeClass("show");
        $("#groupmodal").css("display", "none");
    });
    <?php } ?>
</script>