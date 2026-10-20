<script type="text/javascript">
    const onEmployeeTravelsFormSubmitHandler = (event) => {
        $("#employeeTravelsButton").prop("disabled", true);
        event.preventDefault();
        var employeeId = $("#employeeId").val();
        var month = $("#month").val();
        var stateId = $("#stateId").val();
        var grandTotal = $("#grandTotal").val();
        
        if (employeeId && month && stateId && grandTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/saveEmployeeTravels",
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
                                        window.location.href = '<?= base_url(); ?>Employee/employeeTravelsReport';
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
        <?php if (empty($employeeTravelId)) { ?>
        prepareTravelsList();
        <?php } else { ?>
        var employeeTravelId = "<?= $employeeTravelId; ?>";
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Employee/getEmployeeTravelsData/" + employeeTravelId,
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
        <?php } ?>
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
    
    const prepareAddMoreTravelsList = () => {
        let Sr = $('#travelsTbody tbody tr').length + 1;
        $("#travelsTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="newTravels[' + UniqueRow + '][travelDate]" value="<?= date('Y-m-d'); ?>" id="travelDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Travel Date" autocomplete="off" />' +
            '</td>'+
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
            '<input type="text" name="travels[' + UniqueRow + '][remark]" value="'+ travels.remark +'" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off"  />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" onclick="onRowDeleteRemoveHandler('+ travels.id +', ' + UniqueRow + ')" title="Remove Selected Row">'+
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
    
    const onAddMoreNewRowHandler = () => {
        prepareAddMoreTravelsList();
    };
    
    const onRowDeleteRemoveHandler = (rowId, RowNo) => {
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
                url     :   "<?= base_url(); ?>Employee/updateEmployeeTravels",
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
    
    const onDeleteRowHandler = (rowId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Employee/deleteEmployeeTravelsData",
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