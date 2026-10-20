<style type="text/css">
    .dt-buttons {
        left: 15.5%;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="card-title">
                                File Sharing Report
                            </h3>
                            <div class="pull-right">
                                <a href="<?= base_url(); ?>file_sharing" class="btn btn-info btn-sm" title="Back to File Sharing">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12 p-1">
                            <div class="row pb-2">
                                <div class="col-md-3">
                                    <div class="box bg-info p-1 text-center rounded">
                                        <h3 title="Total File Sharing Received">
                                            <i class="fa fa-inbox"></i>
                                            :
                                            <b>
                                                <?= count($total_request); ?>
                                            </b>
                                        </h3>
                                        <small>
                                            Request Received
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="box bg-primary p-1 text-center rounded">
                                        <h3 title="Total File Sharing Approved">
                                            <i class="fa fa-check-circle"></i>
                                            : 
                                            <b>
                                                <?= count($approved_request); ?>
                                            </b>
                                        </h3>
                                        <small>
                                            Request Approved
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="box bg-warning p-1 text-center rounded">
                                        <h3 title="Total File Sharing Pending">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            : 
                                            <b>
                                                <?= count($panding_request); ?>
                                            </b>
                                        </h3>
                                        <small>
                                            Request Pending
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="box bg-danger p-1 text-center rounded">
                                        <h3 title="Total File Sharing Rejected">
                                            <i class="fa fa-ban"></i>
                                            :
                                            <b>
                                                <?= count($reject_request); ?>
                                            </b>
                                        </h3>
                                        <small>
                                            Request Rejected
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-3 pt-1">
                                    <div class="box bg-success p-1 text-center rounded">
                                        <h3 title="Total File Sharing Request Amount">
                                            <i class="fa fa-inr"></i>
                                            :
                                            <b>
                                                <?= number_format($totalamount_approved_request, 2); ?>
                                            </b>
                                        </h3>
                                        <small>
                                            Approved Request Amount  
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <form action="<?= base_url(); ?>file_sharing/report" method="POST">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label>From</label>
                                        <input type="date" name="from" value="<?= set_value('from', date('Y-m-01')); ?>" class="form-control" title="From Date" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>To</label>
                                        <input type="date" name="to" value="<?= set_value('to', date('Y-m-d')); ?>" class="form-control" title="From To" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Status</label>
                                        <select name="status" id="status" class="form-control" title="Choose Status">
                                            <option selected="" disabled="" value="">Choose Status</option>
                                            <option value="1" <?= set_select('status', '1'); ?>>Approved</option>
                                            <option value="0" <?= set_select('status', '0'); ?>>Pending</option>
                                            <option value="2" <?= set_select('status', '2'); ?>>Rejected</option>
                                            <option value="All" <?= set_select('status', 'All'); ?>>All</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 2rem;">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-boby">
                    <div class="table-responsive pt-3 p-1">
                        <table class="table table-bordered table-condensed dataTable">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (!empty($filedata)) {
                                        $n = 0;
                                        foreach ($filedata as $key => $data) {
                                            $n++;
                                ?>
                                <tr>
                                    <td><?= $n; ?></td>
                                    <td><?= date('d-m-Y h:i A', strtotime($data->created_at)); ?></td>
                                    <td><?= get_title('categoryfilesharing', ['id' => $data->category], 'name'); ?></td>
                                    <td><?= get_title('users', ['id' => $data->user_id], 'name'); ?></td>
                                    <td><i class="fa fa-inr"></i> <?= number_format($data->amount, 2); ?></td>
                                    <td>
                                        <?php if ($data->status == 0) { ?>
                                        <label class="badge badge-warning"><i class="fa fa-exclamation-triangle"></i> Pending</label>
                                        <?php } elseif ($data->status == 1) { ?>
                                        <label class="badge badge-success"><i class="fa fa-check-circle"></i> Approved</label>
                                        <?php } elseif ($data->status == 2) { ?>
                                        <label class="badge badge-danger"><i class="fa fa-ban"></i> Rejected</label>
                                        <?php } ?>
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
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script>