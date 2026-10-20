<style type="text/css">
    #travelsTbody>tbody>tr>td>input, #travelsTbody>tbody>tr>td>select {
        width: 150px;
    }    
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form id="employeeTravles" method="POST" onsubmit="onEmployeeTravelsFormSubmitHandler(event);" autocomplete="off">
                        <input type="hidden" name="grandTotal" value="<?= set_value('grandTotal'); ?>" id="grandTotal" />
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-primary">
                                        Create
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/seniorEmployeeTravelsReport" class="btn btn-primary btn-sm pull-right" title="Employee Travels Report">
                                    <i class="fa fa-list-ul text-white"></i> Employee Travels Report
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
                                        <label for="band">Band</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="text" name="band" value="<?= set_value('band'); ?>" id="band" class="form-control form-control-sm" placeholder="Band" />
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
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th rowspan="2" class="text-white">Sr.</th>
                                                        <th rowspan="2" class="text-white">Date</th>
                                                        <th rowspan="2" class="text-white">Salesman</th>
                                                        <th rowspan="2" class="text-white">Places Visited</th>
                                                        <th rowspan="2" class="text-white">Daily Allowance Claimed</th>
                                                        <th colspan="3" class="text-white text-center">Other Misc. Expenses</th>
                                                        <th rowspan="2" class="text-white">Total</th>
                                                        <th rowspan="2" class="text-white">Remark</th>
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
                                                        <th colspan="8" style="text-align: right;">Total</th>
                                                        <th colspan="2"><i class="fa fa-inr"></i> <span id="grandTotalTxt"></span></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="display:none;">
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
                                <button type="submit" class="btn btn-primary btn-sm" id="employeeTravelsButton">
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

<script type="text/javascript">
    const onEmployeeTravelsFormSubmitHandler = (event) => {
        $("#employeeTravelsButton").prop("disabled", true);
        event.preventDefault();
        var month = $("#month").val();
        var stateId = $("#stateId").val();
        var grandTotal = $("#grandTotal").val();
        
        if (month && stateId && grandTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/saveSeniorEmployeeTravels",
                data    :   $("#employeeTravles").serialize(),
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
                                        window.location.href = '<?= base_url(); ?>Employee/seniorEmployeeTravelsReport';
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#employeeTravelsButton").prop("disabled", false);
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
            $("#employeeSalesButton").prop("disabled", false);
        }
    };    
    
    $(document).ready(function() {
        prepareTravelsList();
        
        window.setTimeout(function() {
            calculateGrandTotal();
        }, 1000);
    });
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const prepareTravelsList = () => {
        let Sr = $('#travelsTbody tbody tr').length + 1;
        $("#travelsTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="travels[' + UniqueRow + '][travelDate]" value="<?= date('Y-m-d'); ?>" id="travelDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Travel Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<select name="travels[' + UniqueRow + '][employeeId]" id="employeeId' + UniqueRow + '" class="form-control form-control-sm border-0" title="Salesman"><?php foreach ($employees as $key => $salesman) { ?><option value="<?= $salesman->Id; ?>"><?= $salesman->Name; ?></option><?php } ?></select>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][placesVisited]" value="" id="placesVisited' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Places Visited" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][dailyAllowanceClaimed]" value="" id="dailyAllowanceClaimed' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Daily Allowance Claimed" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][internetFax]" value="" id="internetFax' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Internet/Fax" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][xeroxPrint]" value="" id="xeroxPrint' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Xerox/Print" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][postCourier]" value="" id="postCourier' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Post/Courier" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][total]" value="" id="total' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][remark]" value="" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off"  />' +
            '</td>' + 
            '<td>' +
            '<a href="javascript:void(0);" onclick="OnAddNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle text-success"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" onclick="OnRemoveRowHandler(' + UniqueRow + ')" title="Remove Selected Row">'+
            '<i class="fa fa-minus-circle text-danger"></i>' +
            '</a>' +
            '</td>' +
            '</tr>'
        );
    
        if (UniqueRow >= 1) {
            UniqueRows.push(UniqueRow);
            ++UniqueRow;
        }
    };
    
    const OnAddNewRowHandler = () => {
        prepareTravelsList();
    };
    
    const OnRemoveRowHandler = (RowNo) => {
        $("#tr" + RowNo).remove();

        let RowCount = $("#travelsTbody tbody tr").length;
        if (RowCount == 0) {
            OnAddNewRowHandler();
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
</script>