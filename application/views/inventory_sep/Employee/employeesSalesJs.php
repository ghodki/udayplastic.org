<script type="text/javascript">
    const onEmployeeSalesFormSubmitHandler = (event) => {
        $("#employeeSalesButton").prop("disabled", true);
        event.preventDefault();
        var employeeId = $("#employeeId").val();
        var month = $("#month").val();
        var stateId = $("#stateId").val();
        var grandTotal = $("#grandTotal").val();
        
        if (employeeId && month && stateId && grandTotal) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/saveEmployeeSales",
                data    :   $("#employeeSales").serialize(),
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
                                        window.location.href = '<?= base_url(); ?>Employee/employeeSalesReport';
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#employeeSalesButton").prop("disabled", false);
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
        <?php if (empty($employeeSalesPartiesId)) { ?>
        preparePartiesList();
        <?php } else { ?>
        var employeeSalesPartiesId = "<?= $employeeSalesPartiesId; ?>";
        $.ajax({
            method  :   "GET",
            url     :   "<?= base_url(); ?>Employee/getEmployeeSalesParties/" + employeeSalesPartiesId,
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
        <?php } ?>
        
        window.setTimeout(function() {
            calculateGrandTotal();
        }, 1000);
        
    });
    
    let UniqueRow = 1;
    const UniqueRows = [];
    
    const preparePartiesList = () => {
        let Sr = $('#partiesTbody tbody tr').length + 1;
        $("#partiesTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="parties[' + UniqueRow + '][billingDate]" value="<?= date('Y-m-d'); ?>" id="billingDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Billing Date" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][distributorName]" value="" id="distributorName' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Distributor Name" autocomplete="off" />' +
            '</td>'+
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][retailerName]" value="" id="retailerName' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Retailer Name" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][location]" value="" id="location' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Location" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][contactPerson]" value="" id="contactPerson' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Contact Person" autocomplete="off" />' +
            '</td>' +
            '<td>' +
            '<input type="tel" name="parties[' + UniqueRow + '][mobileNumber]" value="" id="mobileNumber' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Mobile Number" autocomplete="off" minlength="10" maxlength="10" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="parties[' + UniqueRow + '][netSale]" value="" id="netSale' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Bill Value" autocomplete="off" oninput="calculateGrandTotal();" onchange="calculateGrandTotal();" onkeyup="calculateGrandTotal();" onblur="calculateGrandTotal();" />' +
            '</td>' +
            '<td>' +
            '<input type="text" name="parties[' + UniqueRow + '][remark]" value="" id="remark' + UniqueRow + '" class="form-control form-control-sm border-0" placeholder="Remark" autocomplete="off"  />' +
            '</td>' +
            '<td>' +
            '<a href="javascript:void(0);" onclick="OnAddNewRowHandler();" title="Add New Row">' +
            '<i class="fa fa-plus-circle text-success"></i>' +
            '</a>' +
            '&nbsp;' +
            '<a href="javascript:void(0);" onclick="onRowRemoveHandler(' + UniqueRow + ')" title="Remove Selected Row">'+
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
    
    const prepareNewPartiesList = () => {
        let Sr = $('#partiesTbody tbody tr').length + 1;
        $("#partiesTbody tbody").append(
            '<tr id="tr' + UniqueRow + '">' +
            '<td>' + Sr + '</td>' +
            '<td>' +
            '<input type="date" name="newParties[' + UniqueRow + '][billingDate]" value="<?= date('Y-m-d'); ?>" id="billingDate' + UniqueRow + '" class="form-control form-control-sm border-0" title="Billing Date" autocomplete="off" />' +
            '</td>'+
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
            '<td>' +
            '<a href="javascript:void(0);" onclick="OnRemoveRowHandler('+ parties.id +', ' + UniqueRow + ')" title="Remove Selected Row">'+
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
        prepareNewPartiesList();
    };
    
    const OnAddNewRowHandler = () => {
        preparePartiesList();
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
                url     :   "<?= base_url(); ?>Employee/updateEmployeeSales",
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
    
    const onDeleteRowHandler = (rowId) => {
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>Employee/deleteEmployeeSalesData",
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