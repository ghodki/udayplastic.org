<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body">
                            <h5 style="color: white; padding:10px 0px 10px 0px" class="text-center">
                                Add Accounting Group
                            </h5>
                            <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                            <?php } ?>
                            <form method="POST" action="<?= base_url(); ?>Accounting_group/save">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="name" class="text-white">Accounting Group <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" placeholder="Accounting Group" required="" />
                                                <span class="text-danger"><?= form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-lg-2">
                                            <div class="form-group">
                                                <label for="name" class="text-white">Type <span class="text-danger">*</span></label>
                                                <select name="Type" id="Type" class="form-control" required="" title="Select Type">
                                                    <option value="" selected="" disabled="">Select Type</option>
                                                    <option value="DR" <?= set_select('Type', 'DR'); ?>>DR</option>
                                                    <option value="CR" <?= set_select('Type', 'CR'); ?>>CR</option>
                                                </select>
                                                <span class="text-danger"><?= form_error('Type'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 pb-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-default">
                                                Save
                                            </button>
                                            <input type="reset" class="btn btn-secondary" value="Reset" />
                                        </div>
                                    </div>
                                </div>
                            </form>					
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12 mt-1">
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Accounting Group List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Accounting Group</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($accounting_group) && $accounting_group != '') {
                                                $Sr = 0;
                                                foreach ($accounting_group as $key => $cat) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date("d-m-Y", strtotime($cat->created_at)); ?></td>
                                            <td><?= $cat->name; ?></td>
                                            <td><?= $cat->Type; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Accounting_group/edit/<?= $cat->id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>Accounting_group/delete/<?= $cat->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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
    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script>