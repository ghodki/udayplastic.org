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
                    <form method="POST" action="<?= base_url(); ?>Employee/LeaveUpdate/<?= $EmployeeLeave->Id; ?>">
                        <input type="hidden" name="LeaveId" value="<?= set_value('LeaveId', $EmployeeLeave->Id); ?>" id="LeaveId" />
                        <div class="card card-outline card-danger">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-danger">
                                        Edit Leave Request
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/Leave" class="btn btn-sm btn-danger pull-right" title="Leave Report">
                                    <i class="fa fa-angle-double-left text-white"></i> Back
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-3">
                                        <div class="form-group">
                                            <label for="EmployeeId">Employees <span class="text-danger">*</span></label>
                                            <select name="EmployeeId" id="EmployeeId" class="form-control form-control-sm" title="Employees" required="">
                                                <?php
                                                    if (isset($Employees) && !empty($Employees)) {
                                                        foreach ($Employees as $key => $Employee) {
                                                            $RoleName = get_title('position', ['Id' => $Employee->RoleId], 'PostName');
                                                ?>
                                                <option value="<?= $Employee->Id; ?>" <?= $EmployeeLeave->EmployeeId == $Employee->Id ? "selected" : ""; ?> <?= set_select('EmpId', $Employee->Id); ?>><?= $Employee->Name; ?> (<?= $RoleName; ?>)</option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('EmployeeId'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-3">
                                        <div class="form-group">
                                            <label for="StartDate">Start Date <span class="text-danger">*</span></label>
                                            <input type="Date" name="StartDate" id="StartDate" value="<?= set_value('StartDate', $EmployeeLeave->StartDate); ?>" class="form-control form-control-sm" title="Start Date" required="" />
                                            <span class="text-danger"><?= form_error('StartDate'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="EndDate">End Date <span class="text-danger">*</span></label>
                                            <input type="Date" name="EndDate" id="EndDate" value="<?= set_value('EndDate', $EmployeeLeave->EndDate); ?>" class="form-control form-control-sm" title="End Date" required="" />
                                            <span class="text-danger"><?= form_error('EndDate'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-lg-6 offset-sm-3">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="2"><?= set_value('Remark', $EmployeeLeave->Remark); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-4">
                                    <button type="submit" class="btn btn-sm bg-navy">
                                        Request Update
                                    </button> 
                                    <button type="reset" class="btn btn-sm btn-danger">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>