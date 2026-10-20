<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green p-2">
                        <div class="header-body">
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Edit Sub Group</h2>
                            <form method="POST" action="<?= base_url(); ?>sub_group/update">
                                <input type="hidden" name="id" value="<?= set_value('id', $sub_group->id); ?>" id="id" />
                                <div class="row">
                                    <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
                                        <select name="account" required="" class="form-control">
                                            <option value=""><?= $sub_group->id; ?></option>
                                            <?php
                                                $accounting = $this->user_model->getAry('accounting_group');
                                                if ($accounting) {
                                                    foreach ($accounting as $account) {
                                            ?>
                                            <option value="<?= $account->id; ?>" <?= $sub_group->sub_id == $account->id ? 'selected' : ''; ?> <?= set_select('account', $account->id); ?>><?= $account->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="name" value="<?= set_value('name', $sub_group->name); ?>" id="name" class="form-control" required="" placeholder="Sub Group" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Update
                                        </button>
                                        <a href="<?= base_url(); ?>sub_group" class="btn btn-danger" title="Cancel">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>