<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php
                        $role = $this->session->userdata('roles');
                        if ($this->session->userdata('roles') == 1) {
                    ?>
                    <div class="header bg-gradient-green">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h2 style="color: #ffffff; padding: 10px 0px 10px 0px;" class="text-center">
                                    Add Billing Format
                                </h2>
                                <!-- Card stats -->
                                <div class="row">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                    <?php } ?>
                                    <form method="POST" action="<?= base_url('billing_format/save'); ?>" class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="name" class="text-white">Billing Format</label>
                                                    <textarea id="name" type="text" class="form-control" name="name" required="true" placeholder="Billing Format"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    Save
                                                </button>
                                                <input type="reset" class="btn btn-secondary" placeholder="Reset">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="container-fluid">
                        <div class="col-md-12 col-xl-12 col-lg-12">
                            <div class="card card-outline card-pink">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h5 class="header-title text-pink">
                                            Billing Format List
                                        </h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" id="dataTable">
                                            <thead class="bg-pink">
                                                <tr>
                                                    <th scope="col" width="2%">#</th>
                                                    <th scope="col" width="18%">Date</th>
                                                    <th scope="col" width="80%">Description</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($billing_format) && $billing_format != '') {
                                                        foreach ($billing_format as  $key => $cat) {
                                                            $date = $cat->created_at;
                                                            $newDate = date("d-m-Y", strtotime($date));
                                                ?>
                                                <tr>
                                                    <td><span><?= $key + 1; ?></span></td>
                                                    <td><?= $newDate; ?></td>
                                                    <td><?= $cat->name ?></td>
                                                    <?php if ($role == 1) { ?>
                                                    <td class="text-right">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v text-dark"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a href="<?= base_url('billing_format/delete/') . $cat->id ?>" class="dropdown-item cp">Delete</a>
                                                                <!-- <a href="<?= base_url('billing_format/edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a> -->
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <?php } else { ?>
                                                    <td>
                                                        <?php
                                                            $user_id = $this->session->userdata('user_id');
                                                            $comp_id = $this->session->userdata('comp_id');
                                                            $bill_id = $cat->id;
                                                            $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $bill_id];
                                                            $resu = $this->user_model->getRow('bill_format_status', $where);
                                                            if ($resu) {
                                                                if ($resu->status == 1) {
                                                        ?>
                                                        <span class="text-success"></span>
                                                        <label class="switch">
                                                            <input type="checkbox" checked onchange="changestatus(<?= $cat->id; ?>, 0);">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <?php } else { ?>
                                                        <span class="text-danger"></span>
                                                        <label class="switch">
                                                            <input type="checkbox" onchange="changestatus(<?= $cat->id; ?>, 1);">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <?php } } else { if ($cat->status == 1) { ?>
                                                        <span class="text-success"></span>
                                                        <label class="switch">
                                                            <input type="checkbox" checked onchange="changestatus(<?= $cat->id; ?>, 0);">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <?php } else { ?>
                                                        <span class="text-danger"></span>
                                                        <label class="switch">
                                                            <input type="checkbox" onchange="changestatus(<?= $cat->id; ?>, 1);">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <?php } } ?>
                                                    </td>
                                                    <?php } ?>
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
    </div>
</div>

<script type="text/javascript">
    function changestatus(year_id, val) {
        var id = year_id;
        var value = val;
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>billing_format/changeData",
            data    :   {id: id, status: value},
            success :   function(data) {
                            var data1 = JSON.parse(data);
                            if (data1) {
                                window.location.reload();
                            }
			}
        });
    }
</script>