<style type="text/css">
    #travelsTbody>tbody>tr>td>input {
        width: 150px;
    }
</style>

<script type="text/javascript">
    const onEmployeeOutstationTravelsFormSubmitHandler = (event) => {
        $("#employeeOutstationTravelsButton").prop("disabled", true);
        event.preventDefault();
        var employeeId = $("#employeeId").val();
        var month = $("#month").val();
        var stateId = $("#stateId").val();
        var grandTotal = $("#grandTotal").val();
        
        if (employeeId && month && stateId && grandTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/saveEmployeeOutstationTravels",
                data    :   $("#employeeOutstationTravles").serialize(),
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
                                        window.location.href = '<?= base_url(); ?>Employee/employeeOutstationTravelsReport';
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#employeeOutstationTravelsButton").prop("disabled", false);
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
            $("#employeeOutstationTravelsButton").prop("disabled", false);
        }
    };    
    
    $(document).ready(function() {
        <?php if (empty($employeeTravelId)) { ?>
        prepareOutstationTravelsList();
        <?php } else { ?>
        var employeeTravelId = "<?= $employeeTravelId; ?>";
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Employee/getEmployeeOutstationTravelsData/" + employeeTravelId,
            success :   function(response) {
                            var travelsResponse = JSON.parse(response);
                            if (travelsResponse.status === true) {
                                var travels = travelsResponse.data; 
                                for (var i = 0; i < travels.length; i++) {
                                    prepareOutstationTravelsEdit(travels[i]);
                                }
                                onAddMoreNewRowHandler();
                            }
                        }
        });            
        <?php } ?>
    });
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const prepareOutstationTravelsList = () => {
        let Sr = $('#travelsTbody tbody tr').length + 1;
        $("#travelsTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="travels[' + UniqueRow + '][travelDate]" value="<?= date('Y-m-d'); ?>" id="travelDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Travel Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][from]" value="" id="from' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="From" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][to]" value="" id="to' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="To" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][bus]" value="" id="bus' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Bus" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][startKM]" value="" id="startKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Start KM (A)" autocomplete="off" oninput="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][endKM]" value="" id="endKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="End KM (B)" autocomplete="off" oninput="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][totalKM]" value="" id="totalKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total KM (B - A)" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][amount]" value="" id="amount' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Amount" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][modelType]" value="" id="modelType' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Car/Bike Model Type" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][railway]" value="" id="railway' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Railway" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][auto]" value="" id="auto' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Auto" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][hotel]" value="" id="hotel' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Hotel Accommodation" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][food]" value="" id="food' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Food" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
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
            '<input type="number" name="travels[' + UniqueRow + '][totalAmount]" value="" id="totalAmount' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total Amount" autocomplete="off" readonly="" />' +
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
    
    const prepareAddMoreOutstationTravelsList = () => {
        let Sr = $('#travelsTbody tbody tr').length + 1;
        $("#travelsTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="newTravels[' + UniqueRow + '][travelDate]" value="<?= date('Y-m-d'); ?>" id="travelDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Travel Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="newTravels[' + UniqueRow + '][from]" value="" id="from' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="From" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="newTravels[' + UniqueRow + '][to]" value="" id="to' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="To" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][bus]" value="" id="bus' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Bus" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][startKM]" value="" id="startKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Start KM (A)" autocomplete="off" oninput="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][endKM]" value="" id="endKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="End KM (B)" autocomplete="off" oninput="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][totalKM]" value="" id="totalKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total KM (B - A)" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][amount]" value="" id="amount' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Amount" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newTravels[' + UniqueRow + '][modelType]" value="" id="modelType' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Car/Bike Model Type" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][railway]" value="" id="railway' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Railway" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][auto]" value="" id="auto' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Auto" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][hotel]" value="" id="hotel' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Hotel Accommodation" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="newTravels[' + UniqueRow + '][food]" value="" id="food' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Food" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
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
            '<input type="number" name="newTravels[' + UniqueRow + '][totalAmount]" value="" id="totalAmount' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total Amount" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="newTravels[' + UniqueRow + '][remark]" value="" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off"  />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" onclick="onAddMoreNewRowHandler();" title="Add New Row">' +
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
    
    const prepareOutstationTravelsEdit = (travels) => {
        let Sr = $('#travelsTbody tbody tr').length + 1;
        $("#travelsTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="hidden" name="travels[' + UniqueRow + '][rowId]" value="'+ travels.id +'" id="rowId' + UniqueRow + '" />' +
            '<input type="date" name="travels[' + UniqueRow + '][travelDate]" value="'+ travels.travelDate +'" id="travelDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][from]" value="'+ travels.from +'" id="from' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="From" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][to]" value="'+ travels.to +'" id="to' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="To" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][bus]" value="'+ travels.bus +'" id="bus' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Bus" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][startKM]" value="'+ travels.startKM +'" id="startKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Start KM (A)" autocomplete="off" oninput="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][endKM]" value="'+ travels.endKM +'" id="endKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="End KM (B)" autocomplete="off" oninput="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalKilometerCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][totalKM]" value="'+ travels.totalKM +'" id="totalKM' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total KM (B - A)" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][amount]" value="'+ travels.amount +'" id="amount' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Amount" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][modelType]" value="'+ travels.modelType +'" id="modelType' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Car/Bike Model Type" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][railway]" value="'+ travels.railway +'" id="railway' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Railway" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +            
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][auto]" value="'+ travels.auto +'" id="auto' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Auto" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][hotel]" value="'+ travels.hotel +'" id="hotel' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Hotel Accommodation" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="travels[' + UniqueRow + '][food]" value="'+ travels.food +'" id="food' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Food" autocomplete="off" oninput="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onchange="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onkeyup="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" onblur="onTotalCalculateHandler(this.value, ' + UniqueRow + ');" />' +
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
            '<input type="number" name="travels[' + UniqueRow + '][totalAmount]" value="'+ travels.totalAmount +'" id="totalAmount' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Total Amount" autocomplete="off" readonly="" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="travels[' + UniqueRow + '][remark]" value="'+ travels.remark +'" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off"  />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" onclick="onDeleteRemoveRowHandler('+ travels.id +', ' + UniqueRow + ')" title="Remove Selected Row">'+
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
    
    const onAddMoreNewRowHandler = () => {
        prepareAddMoreOutstationTravelsList();
    };
    
    const onDeleteRemoveRowHandler = (rowId, RowNo) => {
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
    
    const OnAddNewRowHandler = () => {
        prepareOutstationTravelsList();
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
        let bus = $("#bus"+RowNo).val();
        let amount = $("#amount"+RowNo).val();
        let railway = $("#railway"+RowNo).val();
        let auto = $("#auto"+RowNo).val();
        let hotel = $("#hotel"+RowNo).val();
        let food = $("#food"+RowNo).val();
        let internetFax = $("#internetFax"+RowNo).val();
        let xeroxPrint = $("#xeroxPrint"+RowNo).val();
        let postCourier = $("#postCourier"+RowNo).val();
        let total = (Number(bus) + Number(amount) + Number(railway) + Number(auto) + Number(hotel) + Number(food) + Number(internetFax) + Number(xeroxPrint) + Number(postCourier));
        $("#totalAmount"+RowNo).val(total.toFixed(2));
        calculateGrandTotal();
    };
    
    const onTotalKilometerCalculateHandler = (ThisValue, RowNo) => {
        let startKM = $("#startKM"+RowNo).val();
        let endKM = $("#endKM"+RowNo).val();
        let totalKM = (Number(endKM) - Number(startKM));
        $("#totalKM"+RowNo).val(totalKM.toFixed(2));
    };
    
    const calculateGrandTotal = () => {
        let grandTotal = 0;
        const Rows = $("#travelsTbody").children("tbody").children("tr");
        Rows.map(function(Row) {
            let Row1 = $("#travelsTbody").children("tbody").children("tr")[Row];
            const trId = Row1.getAttribute("id");
            const RowNo = trId.slice(2, trId.length);
            const Total = $("#totalAmount" + RowNo).val();
            if (Total != null && Total != "") {
                grandTotal += parseFloat(Total);
            }
        });
        $("#grandTotalTxt").text(grandTotal.toFixed(2));
        $("#grandTotal").val(grandTotal.toFixed(2));
    };
    
    const onEmployeeOutstationTravelsFormUpdateHandler = (event) => {
        $("#employeeOutstationTravelsUpdate").prop("disabled", true);
        event.preventDefault();
        var employeeId = $("#employeeId").val();
        var month = $("#month").val();
        var stateId = $("#stateId").val();
        var grandTotal = $("#grandTotal").val();
        
        if (employeeId && month && stateId && grandTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/updateEmployeeOutstationTravels",
                data    :   $("#employeeOutstationTravelsEdit").serialize(),
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
                                    $("#employeeOutstationTravelsUpdate").prop("disabled", false);
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
            $("#employeeOutstationTravelsUpdate").prop("disabled", false);
        }
    };
    
    const onDeleteRowHandler = (rowId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Employee/deleteEmployeeOutstationTravelsData",
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