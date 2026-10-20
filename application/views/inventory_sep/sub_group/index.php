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
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green p-2">
                        <div class="header-body">
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Add Sub Group</h2>
                            <form method="POST" action="<?= base_url(); ?>sub_group/save">
                                <div class="row">
                                    <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
                                        <select name="account" required="" class="form-control">
                                            <option value="">Accounting Group Select</option>
                                            <?php
                                                $accounting = $this->user_model->getAry('accounting_group');
                                                if ($accounting) {
                                                    foreach ($accounting as $account) {
                                            ?>
                                            <option value="<?= $account->id; ?>" <?= set_select('account', $account->id); ?>><?= $account->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" required="" placeholder="Sub Group" />
                                            <span class="control-label"><?= form_error('name'); ?></span>
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
            
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-blue mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-blue">Sub Group List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-blue">
                                        <tr>
                                            <th class="text-white">Sr</th>
                                            <th class="text-white">Date</th>
                                            <th class="text-white">Accounting Group</th>
                                            <th class="text-white">Sub Group</th>
                                            <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($sub_group) && $sub_group != '') {
                                                $Sr = 0;
                                                foreach ($sub_group as $key => $cat) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date("d-m-Y", strtotime($cat->created_at)); ?></td>
                                            <td>
                                                <?php
                                                    $acc = $this->user_model->getRow('accounting_group', ['id' => $cat->sub_id]);
                                                    if ($acc) {
                                                        echo $acc->name;
                                                    } else {
                                                        echo "Deleted";
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $cat->name; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>sub_group/edit/<?= $cat->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a> 
                                                <a href="<?= base_url(); ?>sub_group/delete/<?= $cat->id ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
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
    </div>
</div>