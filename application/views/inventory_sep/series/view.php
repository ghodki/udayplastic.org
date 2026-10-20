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
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-info">Billing Series</h3>
                            </div>
                            <a href="<?= base_url('series') ?>" class="btn btn-primary pull-right">
                                <i class="fa fa-plus-circle"></i> Add
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <thead class="bg-info">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Voucher Type</th>
                                            <th class="text-white">Active Financial Year</th>
                                            <th class="text-white">Active Series</th>
                                            <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($seriestitle) {
                                                $Sr = 0;
                                                foreach ($seriestitle as $key => $row) {
                                                    $Sr++;
                                                    $where = ['series_type' => $row->id, 'status' => 2, 'comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                    }

                                                    $result = $this->user_model->getRow('series', $where);
                                                    if ($result) {
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $row->series; ?></td>
                                            <td>
                                                <?php
                                                    $res = $this->user_model->getRow('year', ['id' => $result->financial_year]);
                                                    if ($res) {
                                                        echo $res->year;
                                                    } else {
                                                        echo 'NA';
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $result->start_point; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>series/viewseries/<?= $row->id; ?>" class="text-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } else { ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $row->series; ?></td>
                                            <td>NA</td>
                                            <td>NA</td>
                                            <td>
                                                <a href="<?= base_url(); ?>series/viewseries/<?= $row->id; ?>" class="text-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } } ?>
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
        $('#example').DataTable();
    });
</script>