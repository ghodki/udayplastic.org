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
                    <div class="header bg-gradient-green p-3">
                        <div class="header-body">
                            <h2 style="color: #ffffff; padding: 10px 0px 10px 0px;" class="text-center">
                                Add Billing Format
                            </h2>
                            <form method="POST" action="<?= base_url(); ?>billing_format/save">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="name" class="text-white">Billing Format</label>
                                            <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" required="" placeholder="Billing Format" />
                                        </div>
                                        <span class="col-sm-4 control-label">
                                            <?= form_error('name'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2 pb-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                        <input type="reset" class="btn btn-danger" value="Reset" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-dark mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-dark">Billing Format List</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($billing_format) && $billing_format != '') {
                                                $Sr = 0;
                                                foreach ($billing_format as $key => $cat) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date("d-m-Y", strtotime($cat->created_at)); ?></td>
                                            <td><?= $cat->name; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>billing_format/edit/<?= $cat->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a> 
                                                <a href="<?= base_url(); ?>billing_format/delete/<?= $cat->id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<script type="text/javascript">
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>