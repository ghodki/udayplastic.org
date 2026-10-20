<style type="text/css">
    #partiesTbody>tbody>tr>td>input {
        width: 150px;
    }    
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-fuchsia card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-fuchsia">
                                    Edit
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Reports/employeeSalesReport" class="btn bg-fuchsia btn-sm pull-right" title="Employee Sales Report">
                                <i class="fa fa-list-ul text-white"></i> Employee Sales Report
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>Reports/employeeSalesEdit" method="GET" autocomplete="off">
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
                                                <option value="<?= $state->id; ?>" <?= $employeeSalesInfo->stateId == $state->id ? 'selected' : ''; ?><?= set_select('stateId', $state->id); ?>><?= $state->code . "-" . $state->state; ?></option>
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
                            <form id="employeeSalesEdit" method="POST" onsubmit="onEmployeeSalesFormUpdateHandler(event);" autocomplete="off">
                                <input type="hidden" name="from" value="<?= set_value('from', $from); ?>" id="from" />
                                <input type="hidden" name="to" value="<?= set_value('to', $to); ?>" id="to" />
                                <input type="hidden" name="employeeSalesId" value="<?= set_value('employeeSalesId', $employeeSalesInfo->id); ?>" id="employeeSalesId" />
                                <input type="hidden" name="employeeId" value="<?= set_value('employeeId', $employeeSalesInfo->employeeId); ?>" id="employeeId" />
                                <input type="hidden" name="grandTotal" value="<?= set_value('grandTotal', $employeeSalesInfo->grandTotal); ?>" id="grandTotal" />
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="date" name="date" value="<?= set_value('date', $employeeSalesInfo->date); ?>" id="date" class="form-control form-control-sm" placeholder="Employee Name" required="" />
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
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <label for="band">Band</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="band" value="<?= set_value('band', $employeeSalesInfo->band); ?>" id="band" class="form-control form-control-sm" placeholder="Band" />
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
                                                        <th class="text-white">Senior Employee</th>
                                                        <th class="text-white">Salesman</th>
                                                        <th class="text-white">Distributor Name</th>
                                                        <th class="text-white">Retailer Name</th>
                                                        <th class="text-white">Location</th>
                                                        <th class="text-white">Contact Person</th>
                                                        <th class="text-white">Mobile No</th>
                                                        <th class="text-white">Bill Value</th>
                                                        <th class="text-white">Remark</th>
                                                        <th class="text-white">Created</th>
                                                        <th class="text-white">Created</th>
                                                        <th class="text-white">Updated</th>
                                                        <th class="text-white">Updated By</th>
                                                        <th class="text-white"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="9" style="text-align: right;">Total</th>
                                                        <th colspan="2"><i class="fa fa-inr"></i> <span id="grandTotalTxt"><?= $employeeSalesInfo->grandTotal; ?></span></th>
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
                                            <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('remark', $employeeSalesInfo->remark); ?></textarea>
                                        </div>
                                    </div>                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xl-4 col-lg-4 col-xxl-4 col-xs-4">
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
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var from = "<?= $from; ?>";
        var to = "<?= $to; ?>";
        var employeeId = "<?= $employeeId; ?>";
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Reports/getEmployeeSalesParties?from=" + from + "&to=" + to + "&employeeId=" + employeeId,
            success :   function(response) {
                            var partiesResponse = JSON.parse(response);
                            if (partiesResponse.status === true) {
                                var parties = partiesResponse.data; 
                                for (var i = 0; i < parties.length; i++) {
                                    preparePartiesEdit(parties[i]);
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
    
    const prepareNewPartiesList = () => {
        let Sr = $('#partiesTbody tbody tr').length + 1;
        $("#partiesTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="newParties[' + UniqueRow + '][billingDate]" value="<?= date('Y-m-d'); ?>" id="billingDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Billing Date" autocomplete="off" />' +
            '</td>'+            
            '<td>' +
            '<select name="newParties[' + UniqueRow + '][seniorEmployeeId]" id="seniorEmployeeId' + UniqueRow + '" class="form-control form-control-sm border-0" title="Senior Employee" onchange=getSalesmanEmployees(' + UniqueRow + ');><?php foreach($seniorEmployees as $key => $seniorEmp) { ?><option value="<?= $seniorEmp->Id; ?>"><?= $seniorEmp->Name; ?></option><?php } ?></select>' +
            '</td>' +
            '<td>' +
            '<select name="newParties[' + UniqueRow + '][employeeId]" id="employeeId' + UniqueRow + '" class="form-control form-control-sm border-0" title="Salesman"></select>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newParties[' + UniqueRow + '][distributorName]" value="" id="distributorName' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Distributor Name" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="newParties[' + UniqueRow + '][retailerName]" value="" id="retailerName' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Retailer Name" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newParties[' + UniqueRow + '][location]" value="" id="location' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Location" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newParties[' + UniqueRow + '][contactPerson]" value="" id="contactPerson' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Contact Person" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="tel" name="newParties[' + UniqueRow + '][mobileNumber]" value="" id="mobileNumber' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Mobile Number" autocomplete="off" minlength="10" maxlength="10" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newParties[' + UniqueRow + '][netSale]" value="" id="netSale' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Bill Value" autocomplete="off" oninput="calculateGrandTotal();" onchange="calculateGrandTotal();" onkeyup="calculateGrandTotal();" onblur="calculateGrandTotal();" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newParties[' + UniqueRow + '][remark]" value="" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off"  />' +
            '</td>' +
            '<td colspan="4">' +
            '</td>' +
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
    
    const preparePartiesEdit = (parties) => {
        let Sr = $('#partiesTbody tbody tr').length + 1;
        $("#partiesTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="hidden" name="parties[' + UniqueRow + '][rowId]" value="'+ parties.id +'" id="rowId' + UniqueRow + '" />' +
            '<input type="date" name="parties[' + UniqueRow + '][billingDate]" value="'+ parties.billingDate +'" id="billingDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Billing Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][seniorEmployeeId]" value="" id="seniorEmployeeId' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Senior Employee Name" autocomplete="off" readonly="" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][employeeId]" value="" id="employeeId' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Employee Name" autocomplete="off" readonly="" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][distributorName]" value="'+ parties.distributorName +'" id="distributorName' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Distributor Name" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][retailerName]" value="'+ parties.retailerName +'" id="retailerName' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Retailer Name" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][location]" value="'+ parties.location +'" id="location' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Location" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][contactPerson]" value="'+ parties.contactPerson +'" id="contactPerson' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Contact Person" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="tel" name="parties[' + UniqueRow + '][mobileNumber]" value="'+ parties.mobileNumber +'" id="mobileNumber' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Mobile Number" autocomplete="off" minlength="10" maxlength="10" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="parties[' + UniqueRow + '][netSale]" value="'+ parties.netSale +'" id="netSale' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Bill Value" autocomplete="off" oninput="calculateGrandTotal();" onchange="calculateGrandTotal();" onkeyup="calculateGrandTotal();" onblur="calculateGrandTotal();" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][remark]" value="'+ parties.remark +'" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off" />' +
            '</td>' +
            '<td>'+ parties.created + '</td>' +
            '<td>'+ parties.createdBy + '</td>' +
            '<td>'+ parties.updated + '</td>' +
            '<td>'+ parties.updatedBy + '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" onclick="OnRemoveRowHandler('+ parties.id +', ' + UniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle text-danger"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );
        
        getSeniorEmployeeName(parties.seniorEmployeeId, UniqueRow);
        getEmployeeName(parties.employeeId, UniqueRow);
        
        if (UniqueRow >= 1) {
            UniqueRows.push(UniqueRow);
            ++UniqueRow;
        }
    };

    const onAddMoreNewRowHandler = () => {
        prepareNewPartiesList();
    };
    
    const onRowRemoveHandler = (RowNo) => {
        $("#tr" + RowNo).remove();

        let RowCount = $("#partiesTbody tbody tr").length;
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

                let RowCount = $("#partiesTbody tbody tr").length;
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
    
    const calculateGrandTotal = () => {
        let grandTotal = 0;
        const Rows = $("#partiesTbody").children("tbody").children("tr");
        Rows.map(function(Row) {
            let Row1 = $("#partiesTbody").children("tbody").children("tr")[Row];
            const trId = Row1.getAttribute("id");
            const RowNo = trId.slice(2, trId.length);
            const Total = $("#netSale" + RowNo).val();
            if (Total != null && Total != "") {
                grandTotal += parseFloat(Total);
            }
        });
        $("#grandTotalTxt").text(grandTotal.toFixed(2));
        $("#grandTotal").val(grandTotal.toFixed(2));
    };
    
    const onEmployeeSalesFormUpdateHandler = (event) => {
        $("#employeeSalesUpdate").prop("disabled", true);
        event.preventDefault();
        var employeeId = $("#employeeId").val();
        var month = $("#month").val();
        var stateId = $("#stateId").val();
        var grandTotal = $("#grandTotal").val();
        
        if (employeeId && month && stateId && grandTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Reports/updateEmployeeSales",
                data    :   $("#employeeSalesEdit").serialize(),
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
                                    $("#employeeSalesUpdate").prop("disabled", false);
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
            $("#employeeSalesUpdate").prop("disabled", false);
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
    
    const onDeleteRowHandler = (rowId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Reports/deleteEmployeeSalesData",
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
</script>