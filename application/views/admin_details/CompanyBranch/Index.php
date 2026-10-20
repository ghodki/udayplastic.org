<style type="text/css">
    .widget-user-2 .widget-user-username, .widget-user-2 .widget-user-desc {
        margin-left: 12px;
    }
    .widget-user-2 .widget-user-username {
        font-weight: 700;
    }
    .card-body {
        padding: 0.25rem;
    }

    .widget-user-header {background:#1d3350 !important}
    .branchCard li a {font-size:14px}
</style>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">            
            <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") { ?>
            <div class="row pb-2">
                <div class="col-md-2 col-lg-2 col-sm-2">
                    <a href="<?= base_url(); ?>CompanyBranch/Create" class="btn btn-success btn-xs" title="Create Company Branch">
                        <i class="fa fa-plus text-white"></i> Create
                    </a>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-2">
                    <a href="<?= base_url(); ?>CompanyBranch/Manage" class="btn btn-danger btn-xs" title="Manage Company Branch">
                        <i class="fa fa-th text-white"></i> Manage
                    </a>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                        <?php if ($this->session->flashdata('msg')) { ?>
                        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                            <?= $this->session->flashdata('msg'); ?>
                        </div>
                        <?php } ?>                        
                </div>
                <?php
                    if (!empty($CompanyBranch)) {
                        foreach ($CompanyBranch as $key => $Data) {
                ?>
                <div class="col-md-4 col-lg-4 col-sm-4">
                    <div class="card card-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header">
                            
                            <a href="<?= get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "user" ? base_url() . 'CompanyBranch/Redirect/' . $Data->Id : 'javascript:void(0);'; ?>" title="Branch Login" class="d-flex text-light">
                                <div class="widget-user-image">
                                    <?php $logo = get_title('comp_profile', ['CompanyBranchid' => $Data->Id], 'logo'); if ($logo) { ?>
                                    <img class="img-circle elevation-2" src="<?= base_url(); ?>uploads/complogo/<?= $logo; ?>" alt="logo" />
                                    <?php } else { ?>
                                    <img class="img-circle elevation-2" src="<?= base_url(); ?>assets/images/avatar.png" alt="User-Avatar" />
                                    <?php } ?>
                                </div>
                                <!-- /.widget-user-image -->
                                <div>
                                    <h3 class="widget-user-username" style="font-size: 13px;">
                                        <?= $Data->BranchName; ?>
                                    </h3>
                                    <h5 class="widget-user-desc">
                                        <small><?= $Data->Mobile; ?></small>
                                    </h5>
                                </div>
                            </a>
                        </div>
                        <div class="card-footer p-0 branchCard">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        Branch Code 
                                        <span class="float-right text-bold text-dark">
                                            <?= $Data->BranchCode; ?>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        Status 
                                        <span class="float-right">
                                            <?php if ($Data->Status) { ?>
                                                <label class="pull-right btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Active
                                                </label>
                                                <?php } else { ?>
                                                <label class="pull-right btn btn-danger btn-xs">
                                                    <i class="fa fa-ban text-white"></i> Inactive
                                                </label>
                                            <?php } ?>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        Created 
                                        <span class="float-right text-bold text-dark">
                                            <?= date('d-m-Y', strtotime($Data->Date)); ?>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        Email 
                                        <span class="float-right  text-bold text-dark" style="font-size: 12.5px;">
                                            <?= $Data->Email; ?>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        State 
                                        <span class="float-right text-bold text-dark">
                                            <?= get_title('state_list', ['id' => $Data->State], 'state'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        GST 
                                        <span class="float-right text-bold text-dark">
                                            <?= $Data->GST; ?>
                                        </span>
                                    </a>
                                </li>
                                <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") { ?>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        User
                                        <span class="float-right text-bold text-dark">
                                            <?= get_title('users', ['id' => $Data->BranchUserId], 'name'); ?>
                                        </span>
                                    </a>
                                </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-gray">
                                        Company
                                        <span class="float-right text-bold text-dark">
                                            <?= get_title('inventory_billing', ['id' => $Data->CompId], 'company_name'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url(); ?>CompanyBranch/Info/<?= $Data->Id; ?>" class="nav-link" title="More Info">
                                        <i class="fa fa-info-circle"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>
    </div>
</div>