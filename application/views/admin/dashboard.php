<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
                    <div class="pt-2 mt-5">
                        <!-- Notification -->
                        <?php
                            $roles = $this->session->userdata('roles');
                            $user_id = $this->session->userdata('user_id');
                            // role 1 = Admin
                            // role 2 = subadmin
                            // role 3 = user
                            // role 4 = Employee
                            $notice = $this->user_model->getAryss('notice', '', '', '', ['status' => 1]);
                            if ($notice) {
                        ?>
                        <marquee width="100%" id="notification" style="background: white; color: blue;">
                            <a class="dropdown-item"> <?= $notice[0]->name; ?> </a>
                        </marquee>
                        <?php } ?>
                        <!-- Notification END -->
                        <div class="container-fluid">
                            <div class="header-body">
                                <div class="row d-boxes-style">
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>userlist">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Active Users</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getAry('users', '', '', '', ['userType' => 3, 'status' => 1])); ?>
                                                        <i class="fas fa-users"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>file_sharing">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Total Filling</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getResult('file_sharing')); ?> 
                                                        <i class="far fa-share-square"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>file_sharing/filled">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Filing Done</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getAry('file_sharing', '', '', '', ['status' => 1])); ?> 
                                                        <i class="far fa-share-square"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>file_sharing/nonfilled">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Filling Not Yet</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getAry('file_sharing', '', '', '', ['status' => 0])); ?> 
                                                        <i class="far fa-share-square"></i>
                                                    </h3>
                                                </div>
                                            </div>

                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>file_sharing/rejectfile">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Rejected</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getAry('file_sharing', '', '', '', ['status' => 2])); ?> 
                                                        <i class="far fa-share-square"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>service_product">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Service Request</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getAry('product_service_apply', '', '', '', ['status' => 0])); ?> 
                                                        <i class="fab fa-servicestack"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>wallet">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Wallet Request</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getAry('wallet', '', '', '', ['status' => 0])); ?> 
                                                        <i class="fas fa-wallet"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>inventory_billing">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Companies</h1>
                                                </div>
                                                <div class="card-body" style="height: 80px;">
                                                    <?php
                                                        $this->db->select('*');
                                                        $this->db->from('company_assign');
                                                        $this->db->where('assign_user', $this->session->userdata('user_id'));
                                                        $assignedcount = $this->db->get()->result();
                                                    ?>
                                                    <h3>
                                                        <?= count($this->user_model->getAry('inventory_billing', '', '', '', ['status' => 1, 'user_id' => $this->session->userdata('user_id')])) + count($assignedcount); ?> 
                                                        <i class="fas fa-dolly-flatbed"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>CompanyBranch">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1 style="font-size:27px;">Company Branch</h1>
                                                </div>
                                                <div class="card-body" style="height: 80px;">
                                                    <h3>
                                                        <?= $this->db->where('UserId', $this->session->userdata('user_id'))->get('companybranch')->num_rows(); ?> 
                                                        <i class="fa fa-sitemap"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>crm/company">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>CRM</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <i class="fa fa-users"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>paymentReceipt">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Payment Receipt</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= count($this->user_model->getAry('payment_receipt_company', '', '', '', '')); ?> 
                                                        <i class="fa fa-user-secret"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>offer">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Offers</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <i class="fa fa-gift" aria-hidden="true"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>module_assign">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Module Assign</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <i class="fa fa-tasks" aria-hidden="true"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>store_ecom">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Store Ecom</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>store-users-list">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Store Users</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <?= $totalstoreusers; ?> 
                                                        <i class="fa fa-users" aria-hidden="true"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>ticket/ticket_generate_all">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Support</h1>
                                                </div>
                                                <div class="card-body" style="height: 80px;">
                                                    <a style="color: currentColor;" href="tel:+916383880776">
                                                        +91-6383880776
                                                    </a>
                                                    <h3>
                                                        <?= count($this->user_model->getAry('ticket_generate', '', '', '', ['status' => 0])); ?> 
                                                        <i class="fas fa-dolly-flatbed"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>enquiry">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Enquiry</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <i class="fa fa-info"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <a href="<?= base_url(); ?>Index/DBBackUpDownload" title="Download Database Backup">
                                            <div class="card text-center text-muted">
                                                <div class="card-header">
                                                    <h1>Database</h1>
                                                </div>
                                                <div class="card-body">
                                                    <h3>
                                                        <i class="fa fa-download"></i>
                                                    </h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- Card stats -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DBDownloadAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-center">Database Back Up</h5>
                <button type="button" class="close CloseModal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p class="text-primary">
                    Do you want to download database backup ?
                </p>
                <a href="<?= base_url(); ?>Index/DBBackUpDownload" title="Download Database Backup" class="btn btn-primary btn-sm">
                    <i class="fa fa-download"></i> Download
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm CloseModal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jaisa_mera_mai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-center">Top Notice</h2>
            </div>
            <div class="modal-body">
                <table class="table align-items-center table-flush" style="width: 100%;" id="dataTable">
                    <?php
                        $notify = $this->user_model->getAryss('notice', '', 0, 10, '');
                        if ($notify) {
                        foreach ($notify as $key => $notification) {
                            $date = $notification->created_at;
                            $newDate = date("d-m-Y h:i", strtotime($date));
                    ?>
                    <tr>
                        <th><?= $newDate; ?></th>
                        <th class="text-danger"><?= $notification->name; ?></th>
                    </tr>
                    <?php } } ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#jaisa_mera_mai_jaise_mera_tu">See All</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jaisa_mera_mai_jaise_mera_tu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-center">All Notice</h2>
            </div>
            <div class="modal-body">
                <table class="table align-items-center table-flush" style="width: 100%;" id="dataTable">
                    <?php
                        $notify = $this->user_model->getAryss('notice', '', '', '', '');
                        if ($notify) {
                            foreach ($notify as $key => $notification) {
                                $date = $notification->created_at;
                                $newDate = date("d-m-Y h:i", strtotime($date));
                    ?>
                    <tr>
                        <th><?= $newDate; ?></th>
                        <th class="text-danger"><?= $notification->name; ?></th>
                    </tr>
                    <?php } } ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if (count($this->user_model->getAryss('notice_file', '', '', '', ['user_id' => $this->session->userdata('user_id'), 'watch' => 'no']))) { ?>
<div class="modal fade" id="auto_load" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-center">File Sharing Notice</h2>
            </div>
            <div class="modal-body">
                <table class="table align-items-center table-flush" style="width: 100%;" id="dataTable">
                    <tr>
                        <th>DATE</th>
                        <th>CATEGORY</th>
                        <th>AMOUNT DBT</th>
                        <th>MSG</th>
                    </tr>
                    <?php
                        $notify = $this->user_model->getAryss('notice_file', '', '', 10, ['user_id' => $this->session->userdata('user_id'), 'watch' => 'no']);
                        if ($notify) {
                            foreach ($notify as $key => $notification) {
                                $date = $notification->date;
                                $newDate = date("d-m-Y h:i", strtotime($date));
                                $catname = '';
                                $category_id = get_title('file_sharing', ['user_id' => $this->session->userdata('user_id'), 'id' => $notification->file_id], 'category');
                                if ($category_id) {
                                    $catname = get_title('categoryfilesharing', ['id' => $category_id], 'name');
                                }
                    ?>
                    <tr>
                        <th><?= $newDate ?></th>
                        <th><?= $catname ?></th>
                        <th><?= get_title('file_sharing', ['user_id' => $this->session->userdata('user_id'), 'id' => $notification->file_id], 'amount'); ?></th>
                        <th class="text-success"><?= $notification->msg ?></th>
                    </tr>
                    <?php } } ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script type="text/javascript">
    $('#year').change(function () {
        var year_id = $(this).val();
        var gst = document.getElementById('gst').value;
        document.getElementById('cl').innerHTML = '';

        if (parseInt(year_id) > 0 && parseInt(gst) > 0) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url('dashboard/getData'); ?>",
                data    :   {year_id: year_id, gst: gst},
                success :   function (data) {
                                var data1 = JSON.parse(data);
                                var alldate = data1.data;
                                var userinfo = alldate.userinfo;
                                var months = alldate.months;
                                console.log(userinfo);
                                for (i = 0; months.length > i; i++) {
                                    if (userinfo != '' && userinfo != null) {
                                        for (j = 0; j < userinfo.length; j++) {
                                            if (userinfo[j].current == i + 1) {
                                                if (userinfo[j].status == 1) {
                                                    var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
                                                            '</p><span class="text-light text-center" style="margin-right:70px">' + (userinfo[j].store_date) + '</span><label class="switch"><input onclick="return false;" type="checkbox" checked><span class="slider round"></span></label><a class="btn btn-light" href="<?= base_url() ?>uploads/filling/' + userinfo[i].image + '" download>Download</a>'
                                                    '</div>';
                                                    $("#cl").append(row);
                                                } else {
                                                    var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;"><p class="text-center">' + months[i] +
                                                            '<p style="margin-left:40%"><label class="switch"><input type="checkbox" onclick="return false;"><span class="slider round"></span></label><br>'
                                                    '</div>';
                                                    $("#cl").append(row);
                                                }
                                            }
                                        }
                                    } else {
                                        var row = '<div class="col-md-3 col-sm-6 p-3 bg-muted" style="border:2px solid black;">' + months[i] +
                                                '<span class="text-danger"></span><label class="switch"><input type="checkbox"  id="gst1' + i + '+1" onclick="return false;"><span class="slider round"></span></label> </div>'
                                        $("#cl").append(row);
                                    }
                                }
                            }
            });
        } else {
            document.getElementById('cl').innerHTML = '';
        }
    });

    $(document).ready(function () {
        $('#dataTable').DataTable();
        
        <?php if ($this->user_model->getRow('notice_file', ['user_id' => $this->session->userdata('user_id'), 'watch' => 'no'])) { ?>
        $(window).on('load', function () {
            $('#auto_load').modal('show');
        });
        <?php } ?>
            
        <?php if ($DBAlert == "Ok") { ?>
        $(window).on('load', function () {
            $('#DBDownloadAlert').addClass('show');
            $('#DBDownloadAlert').css('display', 'block');
        });
        <?php } ?>
    });
    
    $('.CloseModal').click(function () {
        $('#DBDownloadAlert').removeClass('show');
        $('#DBDownloadAlert').addClass('hide');
        $('#DBDownloadAlert').css('display', 'none');        
    });
</script>