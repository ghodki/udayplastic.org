<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .dt-buttons {
        left: 0%;
    }
</style>

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
            </div>
            <div class="col-md-12 col-xl-12 col-lg-12">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="text-danger">
                                Leave Report
                            </h5>
                        </div>
                        <a href="<?= base_url(); ?>Employee/LeaveCreate" class="btn btn-sm btn-danger pull-right" title="Leave Request">
                            <i class="fa fa-check-circle text-white"></i> Leave Request
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET" id="ReportForm">
                            <div class="row">
                                <div class="col-md-2 col-sm-4 form-group">
                                    <label for="From">From</label>
                                    <input type="date" name="From" value="<?= $this->input->get('From') ? $this->input->get('From') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                </div>
                                <div class="col-md-2 col-sm-4 form-group">
                                    <label for="To">To</label>
                                    <input type="date" name="To" value="<?= $this->input->get('To') ? $this->input->get('To') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>"  id="To" class="form-control form-control-sm" title="To Date" />
                                </div>
                                <div class="col-md-2 col-sm-4 form-group">
                                    <label for="Status">Status</label>
                                    <select name="Status" id="Status" class="form-control form-control-sm" title="Leave Request Status">
                                        <option value="" <?= $this->input->get('Status') == '' ? 'selected' : ''; ?>>All</option>
                                        <option value="0" <?= $this->input->get('Status') == '0' ? 'selected' : ''; ?>>Rejected</option>
                                        <option value="1" <?= $this->input->get('Status') == '1' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="2" <?= $this->input->get('Status') == '2' ? 'selected' : ''; ?>>Approved</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-4 form-group" style="margin-top: 31px;">
                                    <button type="submit" class="btn btn-xs bg-purple">
                                        <i class="fa fa-search text-white"></i> Search
                                    </button>
                                    <a href="<?= base_url(); ?>Employee/Leave" class="btn btn-danger btn-xs" title="Refresh">
                                        <i class="fa fa-refresh text-white"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead class="bg-danger">
                                    <tr>
                                        <th class="text-white">#</th>
                                        <th class="text-white">Date</th>
                                        <th class="text-white">Start Date</th>
                                        <th class="text-white">End Date</th>
                                        <th class="text-white">Days</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">Remark</th>
                                        <th class="text-white">Remark</th>
                                        <th class="text-white">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($EmployeesLeave) && $EmployeesLeave != '') {
                                            $Sr = 0;
                                            foreach ($EmployeesLeave as  $key => $Leave) {
                                                $Sr++;
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td><?= date('d-m-Y', strtotime($Leave->Date)); ?></td>
                                        <td><?= date('d-m-Y', strtotime($Leave->StartDate)); ?></td>
                                        <td><?= date('d-m-Y', strtotime($Leave->EndDate)); ?></td>
                                        <td><?= DifferenceDays($Leave->StartDate, $Leave->EndDate); ?></td>
                                        <td>
                                            <?php if ($Leave->Status == 2) { ?>
                                            <label class="btn btn-success btn-xs">
                                                <i class="fa fa-check-circle text-white"></i> Approved
                                            </label>
                                            <?php } elseif ($Leave->Status == 0) { ?>
                                            <label class="btn btn-danger btn-xs">
                                                <i class="fa fa-ban text-white"></i> Rejected
                                            </label>
                                            <?php } elseif ($Leave->Status == 1) { ?>
                                            <label class="btn btn-warning btn-xs">
                                                <i class="fa fa-spinner text-white"></i> Pending
                                            </label>
                                            <?php } ?>
                                        </td>
                                        <td><?= $Leave->Remark; ?></td>
                                        <td><?= $Leave->AdminRemark; ?></td>
                                        <td>
                                            <a href="<?= base_url(); ?>Employee/LeaveEdit/<?= $Leave->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                <i class="fa fa-edit text-white"></i>
                                            </a>
                                            &nbsp;
                                            <a href="<?= base_url(); ?>Employee/LeaveDelete/<?= $Leave->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                <i class="fa fa-trash text-white"></i>
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

<script type="text/javascript">    
    $(function() {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'pageLength'
            ]
        });
    });
</script>