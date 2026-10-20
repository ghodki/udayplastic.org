<style type="text/css">
    .control-label {
        color: red;
    }
    .fade:not(.show) {
        opacity: 2;
    }
    .form-group {
        margin-bottom: 0rem;
    }
    .card-body {
        padding: 0.25rem;
    }
    .post-search-panel {
        padding-bottom: 5px;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    
                    <?php
                        $role = $this->session->userdata('roles');
                        if ($role != 3) {
                            if ($role != 4) { 
                    ?>                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Create Account</h3>
                        </div>
                        <div class="card-body">
                            <?php
                                $roles = $this->session->userdata('roles');
                                if ($roles == 1) {
                            ?>                            
                            <form id="registrationForm" action="<?= base_url('userlist/saveuser'); ?>" method="POST">
                            <?php } else { ?>
                            <form id="registrationForm" action="<?= base_url('userlist/savesubuser'); ?>" method="POST">
                            <?php } ?>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <div class="form-group">
                                            <label for="usertype">User Type</label>
                                            <select class="form-control" name="userType" required id="usertype">
                                                <option value="">--Select User Type--</option>
                                                <option value="3">User</option>
                                                <?php if ($this->session->userdata('roles') != 1) { ?>
                                                <option value="4">Employee</option>
                                                <?php } if ($this->session->userdata('roles') != 2) { ?>
                                                <option value="2">Sub Admin</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" name="name" placeholder="Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                        </div>
                                        <span class="col-sm-4 control-label">
                                            <?php echo form_error('name'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input id="password" name="password" placeholder="Password" type="password" class="form-control" required="true" value="<?= set_value('password') ?>">
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('password'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <div class="form-group">
                                            <label for="number">Phone</label>
                                            <input id="number" name="number" placeholder="Phone Number" type="text" class="form-control" value="<?= set_value('number') ?>" required>
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('number'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input id="email" name="email" placeholder="Email" type="email" class="form-control" required="true" value="<?= set_value('email') ?>">
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('email'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3" id="account_informations">
                                        <div class="form-group">
                                            <label for="permission_account_add">Account Add Permission</label>
                                            <select class="form-control" id="permission_account_add" name="permission_account_add">
                                                <option value="">--Select--</option>
                                                <option value="YES">YES</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <label for="license_cost">License Fees</label>
                                        <input type="number" name="license_cost" value="<?= set_value('license_cost'); ?>" id="license_cost" class="form-control" placeholder="License Fees" required="" />
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3" id="userdata"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary" value="Register" />
                                            <input type="reset" class="btn btn-danger" value="Reset" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title">Users Report</h3>
                            <button type="button" class="btn btn-success btn-xs pull-right" id="export-users" title="Export Excel">
                                <i class="fa fa-file-excel"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="post-search-panel">
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
                                                <option value="500">500</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select id="column_filter" class="form-control" onchange="searchFilter();">
                                                <option value="" disabled>Search By</option>
                                                <option value="name" selected>Name</option>
                                                <option value="email">Email</option>
                                                <option value="number">Mobile Number</option>
                                                <option value="user_number">User Number</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" id="keywords" class="form-control" placeholder="Type user name to filter services" onkeyup="searchFilter();" />
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered" id="dataList"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="UserInfo" tabindex="-1" role="dialog" aria-labelledby="UserInfo" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $('#export-users').click(function(e) {
        e.preventDefault();
        var href = "<?= base_url(); ?>userlist/exportusers";
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "users_report.xls");
        $a[0].click();
        $a.remove();
    });
    
    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        var column_filter = $('#column_filter').val();

        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('userlist/load_users/'); ?>" + page_num,
            data    :   "page=" + page_num + "&keywords=" + keywords + "&sortBy=" + sortBy + "&pagelength=" + pagelength + "&column_filter=" + column_filter,
            success :   function(html) {
                            $('#dataList').html(html);
                        }
        });
    }
    
    const RenewLicensed = (UserId) => {
        $("#UserInfo .modal-dialog .modal-content").load("<?= base_url(); ?>" + "userlist/GetUserInfo/"+UserId);
        $("#UserInfo").addClass("show");
        $("#UserInfo").css("display", "block");
    };
    
    const CloseModal = () => {
        $("#UserInfo").removeClass("show");
        $("#UserInfo").css("display", "none");
    };

    function cancel() {
        window.location.href = "<?= base_url('userlist') ?>";
    }

    function showModel(model_id) {
        document.getElementById('myModal' + model_id).style.display = 'block';
    }

    function showModels(model_id) {
        document.getElementById('myModals' + model_id).style.display = 'block';
    }

    function closeModel(model_id) {
        document.getElementById('myModal' + model_id).style.display = 'none';
    }

    function closeModels(model_id) {
        document.getElementById('myModals' + model_id).style.display = 'none';
    }

    $(document).ready(function() {
        $('#usertype').change(function() {
            var party_id = $(this).val();
            if (party_id) {
                if (party_id == 2) {
                    document.getElementById('account_informations').style.display = "block";
                } else {
                    document.getElementById('account_informations').style.display = 'none';
                }
                $.ajax({
                    type    :   "POST",
                    <?php if ($this->session->userdata('roles') == 2) { ?>
                    url     :   "<?= base_url('admin/referals'); ?>"
                    <?php } else { ?>
                    url     :   "<?= base_url('admin/referal'); ?>"
                    <?php } ?>,
                    data    :   {usertype: party_id},
                    success :   function(data) {
                                    if (data) {
                                        $("#userdata").html(data);
                                    } else {
                                        document.getElementById('userdata').innerHTML = '';
                                    }
                                }
                });
            } else {
                document.getElementById('userdata').innerHTML = '';
            }
        });
    });

    function OnSubmitHandler(user_id) {
        var user_ids = user_id;
        var amount = document.getElementById('amount' + user_id).value;
        var msg = document.getElementById('msg' + user_id).value;

        if (amount > 0 && user_ids != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>userlist/amount",
                data    :   {amount: amount, user_id: user_ids, msg: msg},
                success :   function(invoiceNo) {
                                invoiceNo = JSON.parse(invoiceNo);
                                if (invoiceNo.status == true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Amount Added',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    setTimeout(function() {
                                        window.reload();
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: invoiceNo.msg,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            }
            });
        } else {
            alert("Please Enter Amount");
        }
    }

    function OnSubmitHandlers(user_id) {
        var user_ids = user_id;
        var amount = document.getElementById('amounts' + user_id).value;
        var msg = document.getElementById('msgs' + user_id).value;

        if (amount >= 0 && user_ids != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>userlist/detect_amount",
                data    :   {amount: amount, user_id: user_ids, msg: msg},
                success :   function(invoiceNo) {
                                invoiceNo = JSON.parse(invoiceNo);
                                if (invoiceNo.status == true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Amount Deducted',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    setTimeout(function() {
                                        window.reload();
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: invoiceNo.msg,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            },
            });
        } else {
            alert("Please Enter Amount");
        }
    }
</script>