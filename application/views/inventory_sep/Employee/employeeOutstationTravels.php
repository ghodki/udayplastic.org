<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form id="employeeOutstationTravles" method="POST" onsubmit="onEmployeeOutstationTravelsFormSubmitHandler(event);" autocomplete="off">
                        <input type="hidden" name="employeeId" value="<?= set_value('employeeId', $EmployeeInfo->Id); ?>" id="employeeId" />
                        <input type="hidden" name="grandTotal" value="<?= set_value('grandTotal'); ?>" id="grandTotal" />
                        <div class="card card-purple card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-purple">
                                        Create
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/employeeOutstationTravelsReport" class="btn bg-purple btn-sm pull-right" title="Employee Outstation Travels Report">
                                    <i class="fa fa-list-ul text-white"></i> Employee Outstation Travels Report
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="date" name="date" value="<?= set_value('date', date('Y-m-d')); ?>" id="date" class="form-control form-control-sm" title="Date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="location">Location</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="location" value="<?= set_value('location'); ?>" id="location" class="form-control form-control-sm" placeholder="Location" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="stateId">Place of Posting <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <select name="stateId" id="stateId" class="form-control form-control-sm" title="Place of Posting">
                                                <option value="" selected="" disabled="">Select State</option>
                                                <?php if (!empty($statesList)) { foreach($statesList as $key => $state) { ?>
                                                <option value="<?= $state->id; ?>" <?= $this->session->userdata('state') == $state->id ? 'selected' : ''; ?><?= set_select('stateId', $state->id); ?>><?= $state->code . "-" . $state->state; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="employeeName">Employee Name <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="employeeName" value="<?= set_value('employeeName', $EmployeeInfo->Name); ?>" id="employeeName" class="form-control form-control-sm" placeholder="Employee Name" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="month">Month <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="month" value="<?= set_value('month', date('F-Y')); ?>" id="month" class="form-control form-control-sm" placeholder="Month" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="department">Department</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="department" value="<?= set_value('baseLocation', 'Electrical'); ?>" id="department" class="form-control form-control-sm" placeholder="Department" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="designation">Designation <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="designation" value="<?= set_value('designation', get_title('position', ['Id' => $EmployeeInfo->RoleId], 'PostName')); ?>" id="designation" class="form-control form-control-sm" placeholder="Designation" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="band">Band</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="band" value="<?= set_value('band'); ?>" id="band" class="form-control form-control-sm" placeholder="Band" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="reportingSenior">Reporting Senior</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="reportingSenior" value="<?= set_value('reportingSenior'); ?>" id="reportingSenior" class="form-control form-control-sm" placeholder="Reporting Senior" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="headQuarter">Head Quarter</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="headQuarter" value="<?= set_value('headQuarter'); ?>" id="headQuarter" class="form-control form-control-sm" placeholder="Head Quarter" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                        <div class="table-responsive" style="min-height: 300px; height: auto;">
                                            <table class="table table-sm table-bordered mb-3" id="travelsTbody">
                                                <thead class="bg-purple">
                                                    <tr>
                                                        <th rowspan="3" class="text-white">Sr.</th>
                                                        <th rowspan="3" class="text-white">Date</th>
                                                        <th colspan="2" class="text-white text-center">Places Visited</th>
                                                        <th colspan="8" class="text-white text-center">Mode Of Travel</th>
                                                        <th colspan="2" class="text-white text-center">Outstation</th>
                                                        <th colspan="3" class="text-white text-center">Other Misc. Expenses</th>
                                                        <th rowspan="3" class="text-white">Total Amount</th>
                                                        <th rowspan="3" class="text-white">Remark</th>
                                                        <th rowspan="3" class="text-white"></th>
                                                    </tr>
                                                    <tr>
                                                        <th rowspan="2" class="text-white">From</th>
                                                        <th rowspan="2" class="text-white">To</th>
                                                        <th rowspan="2" class="text-white">Bus</th>
                                                        <th colspan="5" class="text-white text-center">Private Own Vehicle</th>
                                                        <th rowspan="2" class="text-white">Railway</th>
                                                        <th rowspan="2" class="text-white">Auto</th>
                                                        <th rowspan="2" class="text-white">Hotel Accommodation</th>
                                                        <th rowspan="2" class="text-white">Food</th>
                                                        <th rowspan="2" class="text-white">Internet/Fax</th>
                                                        <th rowspan="2" class="text-white">Xerox/Print</th>
                                                        <th rowspan="2" class="text-white">Post/Courier</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-white">Start KM (A)</th>
                                                        <th class="text-white">End KM (B)</th>
                                                        <th class="text-white">Total KM (B - A)</th>
                                                        <th class="text-white">Amount</th>
                                                        <th class="text-white">Car/Bike Model Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="17" style="text-align: right;">Total</th>
                                                        <th colspan="2"><i class="fa fa-inr"></i> <span id="grandTotalTxt"></span></th>
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
                                            <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('remark'); ?></textarea>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn bg-purple btn-sm" id="employeeOutstationTravelsButton">
                                    Save
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

<?php include('employeeOutstationTravelsJs.php'); ?>