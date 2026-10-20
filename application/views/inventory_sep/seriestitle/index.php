<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-primary">Billing Series Tittle</h3>
                            </div>
                            <a href="<?= base_url(); ?>seriestitle/add" class="btn btn-primary pull-right">
                                <i class="fa fa-plus-circle"></i> Add
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($seriestitle) && $seriestitle != '') {
                                                $Sr = 0;
                                                foreach ($seriestitle as $key => $cat) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date("d-m-Y", strtotime($cat->added_at)); ?></td>
                                            <td><?= $cat->series; ?></td>
                                            <td>
                                                <?php if ($role == 1) { ?>
                                                <a href="<?= base_url(); ?>seriestitle/edit/<?= $cat->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a> 
                                                <a href="<?= base_url(); ?>seriestitle/Deleteseriestitle/<?= $cat->id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php } else {
                                                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'bill_id' => $cat->id];
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                    }

                                                    $resu = $this->user_model->getRow('seriestitle_status', $where);
                                                    if ($resu) { if ($resu->status == 1) {
                                                ?>
                                                <span class="text-success"></span>
                                                <label class="switch">
                                                    <input type="checkbox" checked onchange="changestatus(<?= $cat->id; ?>, 0);" />
                                                    <span class="slider round"></span>
                                                </label>
                                                <?php } else { ?>
                                                <span class="text-danger"></span>
                                                <label class="switch">
                                                    <input type="checkbox" onchange="changestatus(<?= $cat->id; ?>, 1);" />
                                                    <span class="slider round"></span>
                                                </label>
                                                <?php } } else { if ($cat->status == 1) { ?>
                                                <span class="text-success"></span>
                                                <label class="switch">
                                                    <input type="checkbox" checked onchange="changestatus(<?= $cat->id; ?>, 0);" />
                                                    <span class="slider round"></span>
                                                </label>
                                                <?php } else { ?>
                                                <span class="text-danger"></span>
                                                <label class="switch">
                                                    <input type="checkbox" onchange="changestatus(<?= $cat->id; ?>, 1);" />
                                                    <span class="slider round"></span>
                                                </label>
                                                <?php } } } ?>
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
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>