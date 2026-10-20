<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form id="employeeSalesEdit" method="POST" onsubmit="onEmployeeSalesFormUpdateHandler(event);" autocomplete="off">
                        <input type="hidden" name="employeeSalesId" value="<?= set_value('employeeSalesId', $employeeSalesInfo->id); ?>" id="employeeSalesId" />
                        <input type="hidden" name="employeeId" value="<?= set_value('employeeId', $employeeSalesInfo->employeeId); ?>" id="employeeId" />
                        <input type="hidden" name="grandTotal" value="<?= set_value('grandTotal', $employeeSalesInfo->grandTotal); ?>" id="grandTotal" />
                        <div class="card card-fuchsia card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-fuchsia">
                                        Edit
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/employeeSalesReport" class="btn bg-fuchsia btn-sm pull-right" title="Employee Sales Report">
                                    <i class="fa fa-list-ul text-white"></i> Employee Sales Report
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="date" name="date" value="<?= set_value('date', $employeeSalesInfo->date); ?>" id="date" class="form-control form-control-sm" title="Date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="baseLocation">Base Location</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="baseLocation" value="<?= set_value('baseLocation', $employeeSalesInfo->baseLocation); ?>" id="baseLocation" class="form-control form-control-sm" placeholder="Base Location" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="stateId">State <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <select name="stateId" id="stateId" class="form-control form-control-sm" title="State">
                                                <option value="" <?= $employeeSalesInfo->stateId == '' ? 'selected' : ''; ?> disabled="">Select State</option>
                                                <?php if (!empty($statesList)) { foreach($statesList as $key => $state) { ?>
                                                <option value="<?= $state->id; ?>" <?= $employeeSalesInfo->stateId == $state->id ? 'selected' : ''; ?><?= set_select('stateId', $state->id); ?>><?= $state->code . "-" . $state->state; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="employeeName">Name <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="employeeName" value="<?= set_value('employeeName', get_title('employees', ['Id' => $employeeSalesInfo->employeeId], 'Name')); ?>" id="employeeName" class="form-control form-control-sm" placeholder="Employee Name" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="month">Month <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="month" value="<?= set_value('month', $employeeSalesInfo->month); ?>" id="month" class="form-control form-control-sm" placeholder="Month" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="department">Department</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="department" value="<?= set_value('baseLocation', $employeeSalesInfo->department); ?>" id="department" class="form-control form-control-sm" placeholder="Department" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="designation">Designation <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="designation" value="<?= set_value('designation', get_title('position', ['Id' => get_title('employees', ['Id' => $employeeSalesInfo->employeeId], 'RoleId')], 'PostName')); ?>" id="designation" class="form-control form-control-sm" placeholder="Designation" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="band">Band</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="band" value="<?= set_value('band', $employeeSalesInfo->band); ?>" id="band" class="form-control form-control-sm" placeholder="Band" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="reportingSenior">Reporting Senior</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="reportingSenior" value="<?= set_value('reportingSenior', $employeeSalesInfo->reportingSenior); ?>" id="reportingSenior" class="form-control form-control-sm" placeholder="Reporting Senior" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                        <div class="table-responsive" style="min-height: 300px; height: auto;">
                                            <table class="table table-sm table-bordered mb-3" id="partiesTbody">
                                                <thead class="bg-fuchsia">
                                                    <tr>
                                                        <th class="text-white">Sr.</th>
                                                        <th class="text-white">Billing Date</th>
                                                        <th class="text-white">Distributor Name</th>
                                                        <th class="text-white">Retailer Name</th>
                                                        <th class="text-white">Location</th>
                                                        <th class="text-white">Contact Person</th>
                                                        <th class="text-white">Mobile No</th>
                                                        <th class="text-white">Bill Value</th>
                                                        <th class="text-white">Remark</th>
                                                        <th class="text-white"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="7" style="text-align: right;">Total</th>
                                                        <th colspan="2"><i class="fa fa-inr"></i> <span id="grandTotalTxt"><?= $employeeSalesInfo->grandTotal; ?></span></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="display: none;">
                                    <div class="col-md-1 col-sm-1 col-xl-1 col-lg-1 col-xxl-1 col-xs-1">
                                        <label for="remark">Remark</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xl-4 col-lg-4 col-xxl-4 col-xs-4">
                                        <div class="form-group">
                                            <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('remark', $employeeSalesInfo->remark); ?></textarea>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning btn-sm" id="employeeSalesUpdate">
                                    Make Changes
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('employeesSalesJs.php'); ?>