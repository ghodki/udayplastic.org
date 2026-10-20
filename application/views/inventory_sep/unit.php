<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                
                <?php if ($this->session->userdata('roles') == 1) { ?>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green p-2">
                        <div class="header-body">
                            <h4 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Add Unit</h4>
                            <form method="POST" action="<?= base_url(); ?>unit/save">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="unit_name" class="text-white">Unit Name <span class="text-danger">*</span></label>
                                            <input type="text" name="unit_name" value="<?= set_value('unit_name'); ?>" id="unit_name" class="form-control" placeholder="Unit Name" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="uqc_code" class="text-white">UQC Code <span class="text-danger">*</span></label>
                                            <input type="text" name="uqc_code" value="<?= set_value('uqc_code'); ?>" id="uqc_code" class="form-control" placeholder="UQC Code" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                        <input type="reset" class="btn btn-danger" value="Reset">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-purple mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-purple">
                                    Unit List
                                </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-purple">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Date</th>
                                            <th>Unit Name</th>
                                            <th>UQC Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($unit) && $unit != '') {
                                                $Sr = 0;
                                                foreach ($unit as $key => $cat) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date("d-m-Y", strtotime($cat->created_at)); ?></td>
                                            <td><?= $cat->name; ?></td>
                                            <td><?= $cat->uqc_code; ?></td>
                                            <td>
                                                <?php if ($this->session->userdata('roles') == 1) { ?>
                                                <a href="<?= base_url(); ?>unit/edit/<?= $cat->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a> 
                                                <a href="<?= base_url(); ?>unit/delete/<?= $cat->id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php } else {
                                                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'bill_id' => $cat->id];
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                    }
                                                    
                                                    $resu = $this->user_model->getRow('unit_status', $where);
                                                    if ($resu) {
                                                        if ($resu->status == 1) {
                                                ?>
                                                <span class="text-success"></span>
                                                <label class="switch">
                                                    <input type="checkbox" checked="" onchange="changestatus(<?= $cat->id; ?>, 0);" />
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
                                                    <input type="checkbox" checked="" onchange="changestatus(<?= $cat->id; ?>, 0);" />
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
    
    function changestatus(year_id, val) {
        var id = year_id;
        var value = val;
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>unit/changeData",
            data    :   {id: id, status: value},
            success :   function (data) {
                            var data1 = JSON.parse(data);
                            if (data1) {
                                window.location.reload();
                            }
                        }
        });
    }
</script>