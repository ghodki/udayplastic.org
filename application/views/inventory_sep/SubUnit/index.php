<style type="text/css">
    .form-group {
        margin-bottom: 0.5rem;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <form action="<?= base_url(); ?>SubUnit/Save" method="POST">
                        <div class="card card-pink card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="header-title text-pink">
                                        Create
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="SubUnitName">Sub Unit Name <span class="text-danger">*</span></label>
                                            <input type="text" name="SubUnitName" value="<?= set_value('SubUnitName'); ?>" id="SubUnitName" class="form-control" placeholder="Sub Unit Name" required="" />
                                            <span class="text-danger"><?= form_error('SubUnitName'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Value">Value</label>
                                            <input type="text" name="Value" value="<?= set_value('Value'); ?>" id="Value" class="form-control" placeholder="Value" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success btn-sm" value="Submit" />
                                <input type="reset" class="btn btn-danger btn-sm" value="Reset" />
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12 mt-2">
                    <div class="card card-pink card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-pink">
                                    Sub Unit Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="bg-pink">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Sub Unit Name</th>
                                                    <th>Value</th>
                                                    <th>Created</th>
                                                    <th>Updated</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($SubUnits) && !empty($SubUnits)) {
                                                        $Sr = 0;
                                                        foreach ($SubUnits as $key => $Data) { 
                                                            $Sr++;
                                                ?>
                                                <tr>
                                                    <td><?= $Sr; ?></td>
                                                    <td><?= $Data->SubUnitName; ?></td>
                                                    <td><?= $Data->Value; ?></td>
                                                    <td><?= $Data->Created; ?></td>
                                                    <td><?= $Data->Updated; ?></td>
                                                    <td>
                                                        <a href="<?= base_url(); ?>SubUnit/Edit/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>SubUnit/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to delete this record!');" title="Delete">
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
    </div>
    <!-- /.content -->
 </div>

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script> 