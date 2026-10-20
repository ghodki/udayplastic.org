<style type="text/css">
    .dt-buttons {
        left: 20%;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Payment Mode Report
                    </h3>
                    <a href="<?= base_url(); ?>Paymentmode/Create" class="btn btn-info btn-xs pull-right" title="Create Payment Mode">
                        <i class="fa fa-plus"></i> Create
                    </a>
                </div>
                <div class="card-boby">
                    <div class="table-responsive pt-3 p-1">
                        <table class="table table-bordered table-condensed dataTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Payment Mode</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (!empty($PaymentModes)) {
                                        $n = 0;
                                        foreach ($PaymentModes as $key => $data) {
                                            $n++;
                                ?>
                                <tr>
                                    <td><?= $n; ?></td>
                                    <td><?= $data->payment_method; ?></td>
                                    <td>
                                        <?php if ($data->status) { ?>
                                        <a href="<?= base_url(); ?>Paymentmode/ChangeStatus/<?= $data->id; ?>" class="btn btn-success btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-check-circle"></i> Active
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?= base_url(); ?>Paymentmode/ChangeStatus/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                            <i class="fa fa-ban"></i> Inactive
                                        </a>
                                        <?php } ?>
                                    </td>
                                    <td><?= !empty($data->created) ? date('d-m-Y', strtotime($data->created)) : ""; ?></td>
                                    <td>
                                        <a href="<?= base_url(); ?>Paymentmode/Edit/<?= $data->id; ?>" class="btn btn-success btn-xs" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        &nbsp;
                                        <a href="<?= base_url(); ?>Paymentmode/Delete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<script type="text/javascript">
    $(function() {
        $(".dataTable").DataTable({
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });
</script>