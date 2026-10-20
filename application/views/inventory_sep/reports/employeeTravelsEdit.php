<style type="text/css">
    #travelsTbody>tbody>tr>td>input {
        width: 150px;
    }    
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-purple">
                                    Edit
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Reports/employeeTravelsReport" class="btn bg-purple btn-sm pull-right" title="Employee Travels Report">
                                <i class="fa fa-list-ul text-white"></i> Employee Travels Report
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>Reports/employeeTravelsEdit" method="GET" autocomplete="off">
                                <input type="hidden" name="from" value="<?= set_value('from', $from); ?>" id="from" />
                                <input type="hidden" name="to" value="<?= set_value('to', $to); ?>" id="to" />
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="text" name="department" value="<?= set_value('department', $this->input->get('department')); ?>" id="department" class="form-control form-control-sm" placeholder="Department" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="text" name="band" value="<?= set_value('band', $this->input->get('band')); ?>" id="band" class="form-control form-control-sm" placeholder="Band" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <select name="stateId" id="stateId" class="form-control form-control-sm" title="State">
                                                <?php if (!empty($statesList)) { foreach($statesList as $key => $state) { ?>
                                                <option value="<?= $state->id; ?>" <?= $employeeTravelsInfo->stateId == $state->id || $this->input->get('stateId') == $state->id ? 'selected' : ''; ?><?= set_select('stateId', $state->id); ?>><?= $state->code . "-" . $state->state; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <select name="employeeId" id="employeeId" class="form-control form-control-sm" title="Employees">
                                                <option value="All" <?= $this->input->get('employeeId') == 'All' ? 'selected' : ''; ?>>All</option>
                                                <?php if (!empty($employees)) { foreach ($employees as $key => $employee) { ?>
                                                <option value="<?= $employee->Id; ?>" <?= $this->input->get('employeeId') == $employee->Id ? 'selected' : ''; ?>><?= $employee->Name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <button type="submit" class="btn btn-sm bg-navy">
                                            <i class="fa fa-search text-white"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <hr class="bg-dark" />                            
                            <form id="employeeTravelsEdit" method="POST" onsubmit="onEmployeeTravelsFormUpdateHandler(event);" autocomplete="off">
                                <input type="hidden" name="from" value="<?= set_value('from', $from); ?>" id="from" />
                                <input type="hidden" name="to" value="<?= set_value('to', $to); ?>" id="to" />
                                <input type="hidden" name="employeeTravelId" value="<?= set_value('employeeTravelId', $employeeTravelsInfo->id); ?>" id="employeeTravelId" />
                                <input type="hidden" name="employeeId" value="<?= set_value('employeeId', $employeeTravelsInfo->employeeId); ?>" id="employeeId" />
                                <input type="hidden" name="grandTotal" value="<?= set_value('grandTotal', $employeeTravelsInfo->grandTotal); ?>" id="grandTotal" />

                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="date" name="date" value="<?= set_value('date', $employeeTravelsInfo->date); ?>" id="date" class="form-control form-control-sm" title="Date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="location">Location</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="location" value="<?= set_value('location', $employeeTravelsInfo->location); ?>" id="location" class="form-control form-control-sm" placeholder="Location" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="stateId">State <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <select name="stateId" id="stateId" class="form-control form-control-sm" title="State">
                                                <option value="" <?= $employeeTravelsInfo->stateId == '' ? 'selected' : ''; ?> disabled="">Select State</option>
                                                <?php if (!empty($statesList)) { foreach($statesList as $key => $state) { ?>
                                                <option value="<?= $state->id; ?>" <?= $employeeTravelsInfo->stateId == $state->id ? 'selected' : ''; ?><?= set_select('stateId', $state->id); ?>><?= $state->code . "-" . $state->state; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="month">Month <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="month" value="<?= set_value('month', $employeeTravelsInfo->month); ?>" id="month" class="form-control form-control-sm" placeholder="Month" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="department">Department</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="department" value="<?= set_value('baseLocation', $employeeTravelsInfo->department); ?>" id="department" class="form-control form-control-sm" placeholder="Department" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="band">Band</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="band" value="<?= set_value('band', $employeeTravelsInfo->band); ?>" id="band" class="form-control form-control-sm" placeholder="Band" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="headQuarter">Head Quarter</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="headQuarter" value="<?= set_value('headQuarter', $employeeTravelsInfo->headQuarter); ?>" id="headQuarter" class="form-control form-control-sm" placeholder="Head Quarter" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                        <div class="table-responsive" style="min-height: 300px; height: auto;">
                                            <table class="table table-sm table-bordered mb-3" id="travelsTbody">
                                                <thead class="bg-purple">
                                                    <tr>
                                                        <th rowspan="2" class="text-white">Sr.</th>
                                                        <th rowspan="2" class="text-white">Date</th>
                                                        <th rowspan="2" class="text-white">Senior Employee</th>
                                                        <th rowspan="2" class="text-white">Salesman</th>                                                        
                                                        <th rowspan="2" class="text-white">Places Visited</th>
                                                        <th rowspan="2" class="text-white">Daily Allowance Claimed</th>
                                                        <th colspan="3" class="text-white text-center">Other Misc. Expenses</th>
                                                        <th rowspan="2" class="text-white">Total</th>
                                                        <th rowspan="2" class="text-white">Remark</th>
                                                        <th rowspan="2" class="text-white">Created</th>
                                                        <th rowspan="2" class="text-white">Created By</th>
                                                        <th rowspan="2" class="text-white">Updated</th>
                                                        <th rowspan="2" class="text-white">Updated By</th>
                                                        <th rowspan="2" class="text-white"></th>
                                                    </tr>
                                                    <tr>
                                                         <th class="text-white">Internet/Fax</th>
                                                         <th class="text-white">Xerox/Print</th>
                                                         <th class="text-white">Post/Courier</th>
                                                     </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="9" style="text-align: right;">Total</th>
                                                        <th colspan="2"><i class="fa fa-inr"></i> <span id="grandTotalTxt"><?= $employeeTravelsInfo->grandTotal; ?></span></th>
                                                        <th colspan="5"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 col-sm-1 col-xl-1 col-lg-1 col-xxl-1 col-xs-1">
                                        <label for="remark">Remark</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xl-4 col-lg-4 col-xxl-4 col-xs-4">
                                        <div class="form-group">
                                            <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('remark', $employeeTravelsInfo->remark); ?></textarea>
                                        </div>
                                    </div>                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xl-4 col-lg-4 col-xxl-4 col-xs-4">
                                        <button type="submit" class="btn btn-warning btn-sm" id="employeeTravelsUpdate">
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
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var from = "<?= $from; ?>";
        var to = "<?= $to; ?>";
        var employeeId = "<?= $employeeId; ?>";
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Reports/getEmployeeTravelsData?from="+ from + "&to=" + to + "&employeeId=" + employeeId,
            success :   function(response) {
                            var travelsResponse = JSON.parse(response);
                            if (travelsResponse.status === true) {
                                var travels = travelsResponse.data; 
                                for (var i = 0; i < travels.length; i++) {
                                    prepareTravelsEdit(travels[i]);
                                }
                                onAddMoreNewRowHandler();
                            }
                        }
        });
        
        window.setTimeout(function() {
            calculateGrandTotal();
        }, 1000);        
        
    });
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const prepareNewTravelsList = () => {
        let Sr = $('#travelsTbody tbody tr').length + 1;
        $("#travelsTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="newTravels[' + UniqueRow + '][travelDate]" value="<?= date('Y-m-d'); ?>" id="travelDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Travel Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<select name="newTravels[' + UniqueRow + '][seniorEmployeeId]" id="seniorEmployeeId' + UniqueRow + '" class="form-control form-control-sm border-0" title="Senior Employee" onchange=getSalesmanEmployees(' + UniqueRow + ');><?php foreach($seniorEmployees as $key => $seniorEmp) { ?><option value="<?= $seniorEmp->Id; ?>"><?= $seniorEmp->Name; ?></option><?php } ?></select>' +
            '</td>' +
            '<td>' +
            '<select name="newTravels[' + UniqueRow + '][employeeId]" id="employeeId' + UniqueRow + '" class="form-control form-control-sm border-0" title="Salesman"></select>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newTravels[' + UniqueRow + '][placesVisited]" value="" id="placesVisited' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Places Visited" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][dailyAllowanceClaimed]" value="" id="dailyAllowanceClaimed' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Daily Allowance Claimed" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][internetFax]" value="" id="internetFax' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Internet/Fax" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][xeroxPrint]" value="" id="xeroxPrint' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Xerox/Print" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][postCourier]" value="" id="postCourier' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Post/Courier" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][total]" value="" id="total' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newTravels[' + UniqueRow + '][remark]" value="" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off" />' +
            '</td>' +
            '<td colspan="4"></td>' +
            '<td>' +
            '<a href="javascript:void(0);" onclick="onAddMoreNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle text-success"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" onclick="onRowRemoveHandler(' + UniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle text-danger"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );
    
        getSalesmanEmployees(UniqueRow);
    
        if (UniqueRow >= 1) {
            UniqueRows.push(UniqueRow);
            ++UniqueRow;
        }
    };
    
    const prepareTravelsEdit = (travels) => {
        let Sr = $('#travelsTbody tbody tr').length + 1;
        $("#travelsTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="hidden" name="travels[' + UniqueRow + '][rowId]" value="'+ travels.id +'" id="rowId' + UniqueRow + '" />' +
            '<input type="date" name="travels[' + UniqueRow + '][travelDate]" value="'+ travels.travelDate +'" id="travelDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][seniorEmployeeId]" value="" id="seniorEmployeeId' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Senior Employee Name" autocomplete="off" readonly="" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][employeeId]" value="" id="employeeId' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Employee Name" autocomplete="off" readonly="" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][placesVisited]" value="'+ travels.placesVisited +'" id="placesVisited' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Places Visited" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][dailyAllowanceClaimed]" value="'+ travels.dailyAllowanceClaimed +'" id="dailyAllowanceClaimed' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Daily Allowance Claimed" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][internetFax]" value="'+ travels.internetFax +'" id="internetFax' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Internet/Fax" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][xeroxPrint]" value="'+ travels.xeroxPrint +'" id="xeroxPrint' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Xerox/Print" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][postCourier]" value="'+ travels.postCourier +'" id="postCourier' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Post/Courier" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][total]" value="'+ travels.total +'" id="total' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][remark]" value="'+ travels.remark +'" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off" />' +
            '</td>' +
            '<td>'+ travels.created + '</td>' +
            '<td>'+ travels.createdBy + '</td>' +
            '<td>'+ travels.updated + '</td>' +
            '<td>'+ travels.updatedBy + '</td>' +            
            '<td>' +
            '<a href="javascript:void(0);" onclick="OnRemoveRowHandler('+ travels.id +', ' + UniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle text-danger"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );

        getSeniorEmployeeName(travels.seniorEmployeeId, UniqueRow);
        getEmployeeName(travels.employeeId, UniqueRow);
        
        if (UniqueRow >= 1) {
            UniqueRows.push(UniqueRow);
            ++UniqueRow;
        }
    };
    
    const onAddMoreNewRowHandler = () => {
        prepareNewTravelsList();
    };
    
    const onRowRemoveHandler = (RowNo) => {
        $("#tr" + RowNo).remove();

        let RowCount = $("#travelsTbody tbody tr").length;
        if (RowCount == 0) {
            onAddMoreNewRowHandler();
            let Result = ArrayRemove(UniqueRows, RowNo);
            if (Result) {
                while (UniqueRows.length > 0) {
                    UniqueRows.pop();
                }
            }
        } else {
            let Result = ArrayRemove(UniqueRows, RowNo);
            if (Result) {
                while (UniqueRows.length > 0) {
                    UniqueRows.pop();
                }
            }
            if (Result) {
                for (var i = 0; i < Result.length; i++) {
                    UniqueRows.push(Result[i]);
                }
            }
        }
        calculateGrandTotal();
    };
    
   const OnRemoveRowHandler = (rowId, RowNo) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                onDeleteRowHandler(rowId);
                
                $("#tr" + RowNo).remove();

                let RowCount = $("#travelsTbody tbody tr").length;
                if (RowCount == 0) {
                    onAddMoreNewRowHandler();
                    let Result = ArrayRemove(UniqueRows, RowNo);
                    if (Result) {
                        while (UniqueRows.length > 0) {
                            UniqueRows.pop();
                        }
                    }
                } else {
                    let Result = ArrayRemove(UniqueRows, RowNo);
                    if (Result) {
                        while (UniqueRows.length > 0) {
                            UniqueRows.pop();
                        }
                    }
                    if (Result) {
                        for (var i = 0; i < Result.length; i++) {
                            UniqueRows.push(Result[i]);
                        }
                    }
                }                
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
        
        calculateGrandTotal();
    };
    
    const ArrayRemove = (Array, Value) => {
        return Array.filter(function(Element) {
            return Element != Value;
        });
    };
    
    const onTotalCalculateHandler = (ThisValue, RowNo) => {
        let dailyAllowanceClaimed = $("#dailyAllowanceClaimed"+RowNo).val();
        let internetFax = $("#internetFax"+RowNo).val();
        let xeroxPrint = $("#xeroxPrint"+RowNo).val();
        let postCourier = $("#postCourier"+RowNo).val();
        let total = (Number(dailyAllowanceClaimed) + Number(internetFax) + Number(xeroxPrint) + Number(postCourier));
        $("#total"+RowNo).val(total.toFixed(2));
        calculateGrandTotal();
    };
    
    const calculateGrandTotal = () => {
        let grandTotal = 0;
        const Rows = $("#travelsTbody").children("tbody").children("tr");
        Rows.map(function(Row) {
            let Row1 = $("#travelsTbody").children("tbody").children("tr")[Row];
            const trId = Row1.getAttribute("id");
            const RowNo = trId.slice(2, trId.length);
            const Total = $("#total" + RowNo).val();
            if (Total != null && Total != "") {
                grandTotal += parseFloat(Total);
            }
        });
        $("#grandTotalTxt").text(grandTotal.toFixed(2));
        $("#grandTotal").val(grandTotal.toFixed(2));
    };
    
    const onEmployeeTravelsFormUpdateHandler = (event) => {
        $("#employeeTravelsUpdate").prop("disabled", true);
        event.preventDefault();
        var employeeId = $("#employeeId").val();
        var month = $("#month").val();
        var stateId = $("#stateId").val();
        var grandTotal = $("#grandTotal").val();
        
        if (employeeId && month && stateId && grandTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Reports/updateEmployeeTravels",
                data    :   $("#employeeTravelsEdit").serialize(),
                success :   function(response) {
                                var responseData = JSON.parse(response);
                                if (responseData.status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        window.location.href = '<?= $_SERVER['HTTP_REFERER']; ?>';
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#employeeTravelsUpdate").prop("disabled", false);
                                }
                            }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#employeeTravelsUpdate").prop("disabled", false);
        }
    };
    
    const getSeniorEmployeeName = (employeeId, rowId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Reports/getEmployeeName",
            data    :   {employeeId: employeeId},
            success :   function(Response) {
                            if (Response) {
                                $('#seniorEmployeeId' + rowId).val(Response);
                            }
                        }
        });
    };
    
    const getEmployeeName = (employeeId, rowId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Reports/getEmployeeName",
            data    :   {employeeId: employeeId},
            success :   function(Response) {
                            if (Response) {
                                $('#employeeId' + rowId).val(Response);
                            }
                        }
        });
    };
    
    const getSalesmanEmployees = (rowId) => {
        var seniorEmployeeId = $('#seniorEmployeeId'+rowId).val();
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Reports/getSalesmanEmployees",
            data    :   {seniorEmployeeId: seniorEmployeeId},
            success :   function(Response) {
                            if (Response) {
                                $('#employeeId' + rowId).html(Response);
                            }
                        }
        });
    };
    
    const onDeleteRowHandler = (rowId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Reports/deleteEmployeeTravelsData",
            data    :   {rowId: rowId},
            success :   function(Response) {
                            var responseData = JSON.parse(Response);
                            if (responseData.status === true) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: responseData.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            } else {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: responseData.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });                                    
                            }
                            window.setTimeout(function() {
                                window.location.reload();
                            }, 1000);                                
                        }
        });
    };    
</script>