<style type="text/css">
        th, td {
                white-space: nowrap;
        }
</style>

<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header mt-5">
                <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if ($this->session->flashdata('msg')) { ?>
                                        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                                            <?= $this->session->flashdata('msg'); ?>
                                        </div>
                                        <?php } ?>                        
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="card card-outline card-success">
                                                <div class="card-header">
                                                        <div class="card-title">
                                                                <h5 class="header-title text-success">
                                                                        Company Branch Report
                                                                </h5>
                                                        </div>
                                                        <a href="<?= base_url(); ?>CompanyBranch" class="btn btn-success btn-xs pull-right" title="Back">
                                                                <i class="fa fa-angle-double-left text-white"></i> Back
                                                        </a>
                                                </div>
                                                <div class="card-body">
                                                        <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-sm dataTable">
                                                                        <thead class="bg-success">
                                                                                <tr>
                                                                                        <th>Sr.</th>
                                                                                        <th>Date</th>
                                                                                        <th>Name</th>
                                                                                        <th>Email</th>
                                                                                        <th>State</th>
                                                                                        <th>GST</th>
                                                                                        <th>Status</th>
                                                                                        <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") { ?>
                                                                                        <th>User</th>
                                                                                        <?php } ?>
                                                                                        <th>Company</th>
                                                                                        <th></th>
                                                                                </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                                <?php
                                                                                        if (!empty($CompanyBranch)) {
                                                                                                $Sr = 0;
                                                                                                foreach ($CompanyBranch as $key => $Data) {
                                                                                                        $Sr++;
                                                                                ?>
                                                                                <tr>
                                                                                        <td><?= $Sr; ?></td>
                                                                                        <td><?= date('d-m-Y', strtotime($Data->Date)); ?></td>
                                                                                        <td>
                                                                                                <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "user") { ?>
                                                                                                <a href="<?= base_url(); ?>CompanyBranch/Redirect/<?= $Data->Id; ?>" title="Branch Login">
                                                                                                        <?= $Data->BranchName; ?>
                                                                                                </a>
                                                                                                <?php } else { ?>
                                                                                                <?= $Data->BranchName; ?>
                                                                                                <?php } ?>
                                                                                                <br />
                                                                                                <small><?= $Data->Mobile; ?></small>
                                                                                        </td>
                                                                                        <td><?= $Data->Email; ?></td>
                                                                                        <td><?= get_title('state_list', ['id' => $Data->State], 'state'); ?></td>
                                                                                        <td><?= $Data->GST; ?></td>
                                                                                        <td>
                                                                                                <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") { if ($Data->Status) { ?>
                                                                                                <a href="<?= base_url(); ?>CompanyBranch/Status/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                                                                        <i class="fa fa-check-circle text-white"></i> Active
                                                                                                </a>
                                                                                                <?php } else { ?>
                                                                                                <a href="<?= base_url(); ?>CompanyBranch/Status/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                                                                        <i class="fa fa-ban text-white"></i> Inactive
                                                                                                </a>
                                                                                                <?php } } elseif (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "user") { if ($Data->Status) { ?>
                                                                                                <label class="btn btn-success btn-xs">
                                                                                                        <i class="fa fa-check-circle text-white"></i> Active
                                                                                                </label>
                                                                                                <?php } else { ?>
                                                                                                <label class="btn btn-danger btn-xs">
                                                                                                        <i class="fa fa-ban text-white"></i> Inactive
                                                                                                </label>
                                                                                                <?php } } ?>
                                                                                        </td>
                                                                                        <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") { ?>
                                                                                        <td><?= get_title('users', ['id' => $Data->BranchUserId], 'name'); ?></td>
                                                                                        <?php } ?>
                                                                                        <td><?= get_title('inventory_billing', ['id' => $Data->CompId], 'company_name'); ?></td>
                                                                                        <td>
                                                                                                <a href="<?= base_url(); ?>CompanyBranch/Info/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="More Info">
                                                                                                        <i class="fa fa-info-circle text-white"></i>
                                                                                                </a>
                                                                                                <?php if (get_title('roles', ['id' => $this->session->userdata('roles')], 'name') == "admin") { ?>
                                                                                                <a href="<?= base_url(); ?>CompanyBranch/Edit/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                                                                        <i class="fa fa-edit text-white"></i>
                                                                                                </a>
                                                                                                <?php if ($this->db->select('id')->where('CompanyBranchid', $Data->Id)->get('sales')->num_rows() == '0') { ?>
                                                                                                <a href="<?= base_url(); ?>CompanyBranch/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                                                                        <i class="fa fa-trash text-white"></i>
                                                                                                </a>
                                                                                                <?php } } ?>
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
                </div>
        </div>
</div>

<script type="text/javascript">
        $(function() {
                $(".table").DataTable({
                        dom: 'Bfrtip',
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
                });
        });
</script>