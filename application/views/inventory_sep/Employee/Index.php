<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="header-title text-success">
                                Employee Report
                            </h5>
                        </div>
                        <a href="<?= base_url(); ?>Employee/Create" class="btn btn-success btn-xs pull-right" title="Create Employee">
                            <i class="fa fa-user-plus text-white"></i> Create
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm DataTable">
                                <thead class="bg-success">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Id</th>
                                        <th class="text-white">Name</th>
                                        <th class="text-white">Mobile</th>
                                        <th class="text-white">Email</th>
                                        <th class="text-white">Login</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">Created</th>
                                        <th class="text-white"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (!empty($Employees)) {
                                            $Sr = 0;
                                            foreach ($Employees as $key => $Data) {
                                                $Sr++;
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td><?= $Data->EmployeeId; ?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-2 col-xs-2 col-lg-2 col-xx-2 col-sm-2 col-xxl-2">
                                                    <?php 
                                                        $photographfile = $Data->Photograph;
                                                        $photograph = 'uploads/Employee/Photograph/' . $photographfile;
                                                        if (file_exists($photograph) && !empty($photographfile)) {
                                                    ?>
                                                    <img src="<?= base_url() . '/' . $photograph; ?>" alt="Photograph" style="width: 30px; border-radius: 25px;" />
                                                    <?php } else { ?>
                                                    <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 30px; border-radius: 25px;" />
                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-8 col-xs-8 col-lg-8 col-xx-8 col-sm-8 col-xxl-8">
                                                    <?= $Data->Name; ?>
                                                    <br />
                                                    <small><?= get_title('position', ['Id' => $Data->RoleId], 'PostName'); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $Data->Mobile; ?></td>
                                        <td><?= $Data->Email; ?></td>
                                        <td>
                                            <?php if ($Data->IsActive) { ?>
                                            <a href="<?= base_url(); ?>Employee/IsActive/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Login Access" onclick="return confirm('Are you sure want to change status!');">
                                                <i class="fa fa-sign text-white"></i> Access
                                            </a>
                                            <?php } else { ?>
                                            <a href="<?= base_url(); ?>Employee/IsActive/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Login Access" onclick="return confirm('Are you sure want to change status!');">
                                                <i class="fa fa-ban text-white"></i> Denied
                                            </a>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($Data->Status) { ?>
                                            <a href="<?= base_url(); ?>Employee/Status/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                <i class="fa fa-check-circle text-white"></i> Active
                                            </a>
                                            <?php } else { ?>
                                            <a href="<?= base_url(); ?>Employee/Status/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                <i class="fa fa-ban text-white"></i> Inactive
                                            </a>
                                            <?php } ?>
                                        </td>
                                        <td><?= !empty($Data->Date) ? date('d-m-Y', strtotime($Data->Date)) : ""; ?></td>
                                        <td>
                                            <a href="<?= base_url(); ?>Employee/Permission/<?= $Data->Id; ?>" class="btn btn-info btn-xs" title="Employee Permission">
                                                <i class="fa fa-universal-access text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/Card/<?= $Data->Id; ?>" class="btn btn-dark btn-xs" title="Employee Card" target="_blank">
                                                <i class="fa fa-id-card text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/Info/<?= $Data->Id; ?>" class="btn btn-primary btn-xs" title="More Info">
                                                <i class="fa fa-info-circle text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/Edit/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                <i class="fa fa-edit text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                <i class="fa fa-trash text-white"></i>
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
    </div>
</section>