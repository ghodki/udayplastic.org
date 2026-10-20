<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <?php if (empty($PositionId)) { ?>
                <form action="<?= base_url(); ?>Employee/PositionSave" method="POST">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-secondary">
                                    Create Position
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="PostName">Post Name <span class="text-danger">*</span></label>
                                        <input type="text" name="PostName" value="<?= set_value('PostName'); ?>" id="PostName" class="form-control form-control-sm" placeholder="Post Name" required="" autocomplete="off" />
                                        <span class="text-danger"><?= form_error('PostName'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                            <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                        </div>
                    </div>
                </form>
                <?php } else { ?>
                <form action="<?= base_url(); ?>Employee/PositionUpdate/<?= $PositionData->Id; ?>" method="POST">
                    <input type="hidden" name="PositionId" value="<?= set_value('PositionId', $PositionData->Id); ?>" />
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-secondary">
                                    Edit Position
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="PostName">Post Name <span class="text-danger">*</span></label>
                                        <input type="text" name="PostName" value="<?= set_value('PostName', $PositionData->PostName); ?>" id="PostName" class="form-control form-control-sm" placeholder="Post Name" required="" autocomplete="off" />
                                        <span class="text-danger"><?= form_error('PostName'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                            <a href="<?= base_url(); ?>Employee/Position" class="btn btn-danger btn-sm">Cancel</a>
                        </div>
                    </div>
                </form>
                <?php } ?>
                <div class="card card-outline card-dark">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="header-title text-dark">
                                Position Report
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm DataTable">
                                <thead class="bg-dark">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Post Name</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">Created</th>
                                        <th class="text-white">Updated</th>
                                        <th class="text-white"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (!empty($Positions)) {
                                            $Sr = 0;
                                            foreach ($Positions as $key => $Position) {
                                                $Sr++;
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td><?= $Position->PostName; ?></td>
                                        <td>
                                            <?php if ($Position->Status) { ?>
                                            <a href="<?= base_url(); ?>Employee/PositionStatus/<?= $Position->Id; ?>" class="btn btn-success btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                <i class="fa fa-check-circle text-white"></i> Active
                                            </a>
                                            <?php } else { ?>
                                            <a href="<?= base_url(); ?>Employee/PositionStatus/<?= $Position->Id; ?>" class="btn btn-danger btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                <i class="fa fa-ban text-white"></i> Inactive
                                            </a>
                                            <?php } ?>
                                        </td>
                                        <td><?= $Position->Created; ?></td>
                                        <td><?= $Position->Updated; ?></td>
                                        <td>
                                            <a href="<?= base_url(); ?>Employee/PositionEdit/<?= $Position->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                <i class="fa fa-edit text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/PositionDelete/<?= $Position->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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