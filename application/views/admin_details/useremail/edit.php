<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5 mb-2">
                <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Update User Email</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('useremail/updateuseremail') ?>" method="POST">
                                <input type="hidden" name="id" value="<?= $userinfo->id; ?>" />
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="group_id">Group Name <span class="text-danger">*</span></label>
                                            <select name="group_id" id="group_id" class="form-control" title="Select Group Name" required="">
                                                <option selected="" value="<?= $userinfo->id; ?>"><?= get_title('emailgroup', ['id' => $userinfo->group_id], 'name'); ?></option>
                                                <?php if (!empty($groupname)) { foreach ($groupname as $key => $gname) { ?>
                                                <option value="<?= $gname->id; ?>" <?= set_select('group_id', $gname->id)?>><?= $gname->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <span class="text-danger">
                                            <?= form_error('group_id'); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-8 col-sm-6 col-lg-8">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="<?= set_value('email', $userinfo->email); ?>" id="email" class="form-control" placeholder="Example: demo@gmail.com" required="" />
                                        </div>
                                        <span class="text-danger">
                                            <?= form_error('email'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-warning btn-sm" value="Update" />
                                            <a href="<?= base_url(); ?>useremail" class="btn btn-danger btn-sm" value="Reset">Cancel</a>
                                        </div>
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