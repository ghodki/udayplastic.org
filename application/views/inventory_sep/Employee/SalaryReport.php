<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-dark">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="header-title text-dark">
                                Salary Report
                            </h5>
                        </div>
                        <a href="<?= base_url(); ?>Employee/Salary" class="btn btn-sm bg-dark pull-right" title="Salary">
                            <i class="fa fa-inr text-white"></i> Salary
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url(); ?>Employee/SalaryReport" method="GET">
                            <div class="row">
                                <div class="col-md-2 col-lg-2 col-sm-4">
                                    <div class="form-group">
                                        <input type="date" name="From" value="<?= set_value('From', !empty($this->input->get('From')) ? $this->input->get('From') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-4">
                                    <div class="form-group">
                                        <input type="date" name="To" value="<?= set_value('To', !empty($this->input->get('From')) ? $this->input->get('To') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1 col-sm-4">
                                    <div class="form-group">
                                        <select name="Status" id="Status" class="form-control form-control-sm" title="Salary Status">
                                            <option <?= $this->input->get('Status') == "All" ? "selected" : ""; ?> value="All">All</option>
                                            <option <?= $this->input->get('Status') == "0" ? "selected" : ""; ?> value="0">Pending</option>
                                            <option <?= $this->input->get('Status') == "1" ? "selected" : ""; ?> value="1">Released</option>
                                            <option <?= $this->input->get('Status') == "2" ? "selected" : ""; ?> value="2">Hold</option>
                                            <option <?= $this->input->get('Status') == "3" ? "selected" : ""; ?> value="3">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm bg-dark">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Employee/SalaryReport" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm DataTable">
                                <thead class="bg-dark">
                                    <tr>
                                        <th class="text-white">
                                            <input type="checkbox" name="allbox" title="Select OR De-Select ALL" onclick="un_check();" />
                                        </th>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Date</th>
                                        <th class="text-white">Name</th>
                                        <th class="text-white">Month</th>
                                        <th class="text-white">Days</th>
                                        <th class="text-white">Salary</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $Sr = 0;
                                        $TotalSalary = 0;
                                        if (!empty($EmployeeSalary)) {
                                            foreach ($EmployeeSalary as $key => $Report) {
                                                $Sr++;
                                                $rowSalary = (float) ($Report->NetTakeHomeSalary ?? 0);
                                                $TotalSalary += $rowSalary;
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="EmpId[]" value="<?= $Report->Id; ?>" />
                                        </td>
                                        <td><?= $Sr; ?></td>
                                        <td><?= !empty($Report->Date) ? date('d-m-Y', strtotime($Report->Date)) : ''; ?></td>
                                        <td><?= get_title('employees', ['Id' => $Report->EmpId], 'Name'); ?></td>
                                        <td><?= $Report->Month; ?></td>
                                        <td><?= $Report->TotalDays; ?></td>
                                        <td><i class="fa fa-inr"></i> <?= $rowSalary; ?></td>
                                        <td>
                                            <?php if ($Report->Status == 0) { ?>
                                            <label class="btn btn-warning btn-sm"><i class="fa fa-spinner"></i> Pending</label>
                                            <?php } elseif ($Report->Status == 1) { ?>
                                            <label class="btn btn-success btn-sm"><i class="fa fa-check-circle-o"></i> Released</label>
                                            <?php } elseif ($Report->Status == 2) { ?>
                                            <label class="btn btn-da btn-sm"><i class="fa fa-exclamation"></i> Hold</label>
                                            <?php } elseif ($Report->Status == 3) { ?>
                                            <label class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Cancelled</label>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url(); ?>Employee/SalaryInfo/<?= $Report->Id; ?>" class="btn btn-primary btn-xs" title="More Info">
                                                <i class="fa fa-info-circle text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/PaySlip/<?= $Report->Id; ?>" class="btn btn-dark btn-xs" title="PaySlip" target="_blank">
                                                <i class="fa fa-print text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/SalaryEdit/<?= $Report->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                <i class="fa fa-edit text-white"></i>
                                            </a>
                                            <a href="<?= base_url(); ?>Employee/SalaryDelete/<?= $Report->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                <i class="fa fa-trash text-white"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" style="text-align: right;">Total</th>
                                        <th><i class="fa fa-inr"></i> <?= $TotalSalary; ?></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>