<script type="text/javascript">
    $(function() {
        $(".DataTable").DataTable({
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print']
        });
    });

    const ChangeFinancialYear = (FinancialYearId) => {
        if (FinancialYearId != "" && FinancialYearId != null) {
            $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>inventory_billing/ChangeFinancialYear",
                data    :   {FinancialYearId: FinancialYearId},
                success :   function (Response) {                                                      
                                if (Response) {
                                    var FinancialYearData = JSON.parse(Response);
                                    if (FinancialYearData.status == true) {
                                        location.reload();
                                    }
                                }
                            }
            });
        }
    };

    const ChangeCompany = (CompData) => {
        let text = CompData;
        let other = text.split(", ");
        if (other[1] == "AssignCompany") {
            window.location.href='<?= base_url(); ?>inventory_billing/AssignCompanyRedirect/' + other[0];
        } else if (other[1] == "Company") {
            window.location.href='<?= base_url(); ?>inventory_billing/redirect/' + other[0];
        }
    };

    setTimeout(function() {
        $('.alert').hide(1000, function() {
            $(this).remove();
        });
    }, 2000);

    function un_check() {
        for (var i = 0; i < document.frmactive.elements.length; i++) {
            var e = document.frmactive.elements[i];
            if ((e.name != 'allbox') && (e.type == 'checkbox')) {
                e.checked = document.frmactive.allbox.checked;
            }
        }
    }

    $(document).ready(function() {
        $("#EmpId").select2();
        $("#Month").select2();
        getWarehouseSalesman($("#WarehouseId").val());
    });
           
    const MultiSelect = new Choices("#WarehouseSalesman", {
        removeItemButton: true
    });
        
    const getWarehouseSalesman = (WarehouseId) => {        
        if (WarehouseId != "" && WarehouseId != null) {
            $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Employee/getWarehouseSalesman",
                data    :   {WarehouseId: WarehouseId},
                success :   function (Response) {
                                if (Response) {
                                    var SalesmanData = JSON.parse(Response);
                                    if (SalesmanData.Status == true) {
                                        MultiSelect.setChoices(SalesmanData.Data, 'Id', 'Name', true);
                                    }
                                }
                            }
            });
        }    
    };
    
    const SalaryCalculate = () => {
        let Basic = $("#Basic").val();
        let HRA = $("#HRA").val();
        let ConveyanceAllowance = $("#ConveyanceAllowance").val();
        let StaffSpecialAllowance = $("#StaffSpecialAllowance").val();
        // Gross
        let GrossMonthlyTotal = parseInt(Basic) + parseInt(HRA) + parseInt(ConveyanceAllowance) + parseInt(StaffSpecialAllowance);
        $("#GrossMonthlyTotal").val(GrossMonthlyTotal);
        // PF
        if (Basic > 15000) { 
            let EmployeePF = 0;
            let EmployerPF = 0;
            $("#EmployeePF").val(EmployeePF);
            $("#EmployerPF").val(EmployerPF);
        } else {
            let EmployeePF = parseInt((Basic * 12) / 100);
            let EmployerPF = parseInt((Basic * 12) / 100);
            $("#EmployeePF").val(EmployeePF);
            $("#EmployerPF").val(EmployerPF);
        }
        // ESI
        if (GrossMonthlyTotal > 21000) {
            let EmployeeESI = 0;
            let EmployerESI = 0;
            $("#EmployeeESI").val(EmployeeESI);
            $("#EmployerESI").val(EmployerESI);
        } else {
            let EmployeeESI = parseInt((GrossMonthlyTotal * 0.75) / 100);
            let EmployerESI = parseInt((GrossMonthlyTotal * 3.25) / 100);
            $("#EmployeeESI").val(EmployeeESI);
            $("#EmployerESI").val(EmployerESI);
        }
        // Taxes
        let ProfessionTax = $("#ProfessionTax").val();
        let IncomeTax = $("#IncomeTax").val();
        let TDS = $("#TDS").val();
        let TDSAmount = parseInt((GrossMonthlyTotal * TDS) / 100);
        if (TDSAmount > 0) {
            $("#TDSAmount").val(TDSAmount);
        } else {
            $("#TDSAmount").val(0);
        }
        let EmployeePFAmount = $("#EmployeePF").val();
        let EmployeeESIAmount = $("#EmployeeESI").val();
        // Monthly Deduction
        let MonthlyDeduction = parseInt(ProfessionTax) + parseInt(IncomeTax) + parseInt(TDSAmount) + parseInt(EmployeePFAmount) + parseInt(EmployeeESIAmount);
        $("#MonthlyDeduction").val(MonthlyDeduction);
        // Take Home
        let MonthlyNetTakeHome = parseInt(GrossMonthlyTotal) - parseInt(MonthlyDeduction);
        $("#MonthlyNetTakeHome").val(MonthlyNetTakeHome);

        let EmployerPFAmount = $("#EmployerPF").val();
        let EmployerESIAmount = $("#EmployerESI").val();
        let AnnualComponents = $("#AnnualComponents").val();
        let PerformanceBonus = $("#PerformanceBonus").val();
        let Gratuity = $("#Gratuity").val();
        let LeaveTravelAllowance = $("#LeaveTravelAllowance").val();
        // CTC
        let CostToCompany = parseInt(GrossMonthlyTotal) + parseInt(EmployerPFAmount) + parseInt(EmployerESIAmount) + parseInt(AnnualComponents) + parseInt(PerformanceBonus) + parseInt(Gratuity) + parseInt(LeaveTravelAllowance);
        $("#CostToCompany").val(CostToCompany);
    };
    
    const GetEmployeeAttendance = () => {
        let EmployeeId = $("#EmpId").val();
        let Month = $("#Month").val();
        if (EmployeeId != "" && EmployeeId != null && Month != "" && Month != null) {
            $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Employee/GetEmployeeAttendance",
                data    :   {EmployeeId: EmployeeId, Month: Month},
                success :   function (Response) {                                                      
                                if (Response) {
                                    var EmployeeDaysData = JSON.parse(Response);
                                    if (EmployeeDaysData.Status == true) {
                                        $("#PresentDays").val(EmployeeDaysData.DaysData.PresentDays);
                                        $("#LeaveDays").val(EmployeeDaysData.DaysData.LeaveDays);
                                        $("#Holidays").val(EmployeeDaysData.DaysData.HoliDays);
                                        $("#AbsentDays").val(EmployeeDaysData.DaysData.AbsentDays);
                                        $("#TotalDays").val(EmployeeDaysData.DaysData.TotalDays);
                                        $("#OverTimeHours").val(0);
                                    }
                                }
                            }
            });
        }
    };
    
    const GetEmployeeSalary = (EmployeeId) => {
        if (EmployeeId != "" && EmployeeId != null) {
            return $.ajax({
                        method  :   "POST",
                        url     :   "<?= base_url(); ?>Employee/GetEmployeeSalary",
                        data    :   {EmployeeId: EmployeeId},
                        async   :   false,
                        success :   function (Response) {
                                        let EmployeeSalaryRecord = JSON.parse(Response);
                                        if (EmployeeSalaryRecord.Status == true) {
                                            JSON.parse(Response.EmployeeSalaryData);
                                        }
                                    }
                   }).responseText;
        }
    };
    
    const EmployeeSalary = () => {
        let EmployeeId = $("#EmpId").val();
        let EmployeeSalary = JSON.parse(GetEmployeeSalary(EmployeeId)).EmployeeSalaryData;
        let Basic = EmployeeSalary.Basic;
        let HRA = EmployeeSalary.HRA;
        let ConveyanceAllowance = EmployeeSalary.ConveyanceAllowance;
        let StaffSpecialAllowance = EmployeeSalary.StaffSpecialAllowance;
        let GrossMonthlyTotal = EmployeeSalary.GrossMonthlyTotal;
        let EmployeePF = EmployeeSalary.EmployeePF;
        let EmployeeESI = EmployeeSalary.EmployeeESI;
        let ProfessionTax = EmployeeSalary.ProfessionTax;
        let IncomeTax = EmployeeSalary.IncomeTax;
        let TDSAmount = EmployeeSalary.TDSAmount;
        let MonthlyDeduction = EmployeeSalary.MonthlyDeduction;
        let MonthlyNetTakeHome = EmployeeSalary.MonthlyNetTakeHome;
        let EmployerPF = EmployeeSalary.EmployerPF;
        let EmployerESI = EmployeeSalary.EmployerESI;
        let AnnualComponents = EmployeeSalary.AnnualComponents;
        let PerformanceBonus = EmployeeSalary.PerformanceBonus;
        let Gratuity = EmployeeSalary.Gratuity;
        let LeaveTravelAllowance = EmployeeSalary.LeaveTravelAllowance;
        let CTC = EmployeeSalary.CTC;
        
        let WorkingDays = $("#WorkingDays").val();
        let TotalDays = $("#TotalDays").val();

        let BasicAmount = ((Basic / WorkingDays) * TotalDays);
        let HRAAmount = ((HRA / WorkingDays) * TotalDays);
        let ConveyanceAllowanceAmount = ((ConveyanceAllowance / WorkingDays) * TotalDays);
        let StaffSpecialAllowanceAmount = ((StaffSpecialAllowance / WorkingDays) * TotalDays);
        let GrossMonthlyTotalAmount = ((GrossMonthlyTotal / WorkingDays) * TotalDays);
        let EmployeePFAmount = ((EmployeePF / WorkingDays) * TotalDays);
        let EmployeeESIAmount = ((EmployeeESI / WorkingDays) * TotalDays);
        let ProfessionTaxAmount = ((ProfessionTax / WorkingDays) * TotalDays);
        let IncomeTaxAmount = ((IncomeTax / WorkingDays) * TotalDays);
        let TDSAmountAmount = ((TDSAmount / WorkingDays) * TotalDays);
        let MonthlyDeductionAmount = ((MonthlyDeduction / WorkingDays) * TotalDays);
        let MonthlyNetTakeHomeAmount = ((MonthlyNetTakeHome / WorkingDays) * TotalDays);
        let EmployerPFAmount = ((EmployerPF / WorkingDays) * TotalDays);
        let EmployerESIAmount = ((EmployerESI / WorkingDays) * TotalDays);
        let AnnualComponentsAmount = ((AnnualComponents / WorkingDays) * TotalDays);
        let PerformanceBonusAmount = ((PerformanceBonus / WorkingDays) * TotalDays);
        let GratuityAmount = ((Gratuity / WorkingDays) * TotalDays);
        let LeaveTravelAllowanceAmount = ((LeaveTravelAllowance / WorkingDays) * TotalDays);
      
        $("#Basic").val((BasicAmount).toFixed(0));
        $("#HRA").val((HRAAmount).toFixed(0));
        $("#ConveyanceAllowance").val((ConveyanceAllowanceAmount).toFixed(0));
        $("#StaffSpecialAllowance").val((StaffSpecialAllowanceAmount).toFixed(0));
        $("#GrossMonthlyTotal").val((GrossMonthlyTotalAmount).toFixed(0));
        $("#EmployeePF").val((EmployeePFAmount).toFixed(0));
        $("#EmployeeESI").val((EmployeeESIAmount).toFixed(0));
        $("#ProfessionTax").val((ProfessionTaxAmount).toFixed(0));
        $("#IncomeTax").val((IncomeTaxAmount).toFixed(0));
        $("#TDSAmount").val((TDSAmountAmount).toFixed(0));
        $("#MonthlyDeduction").val((MonthlyDeductionAmount).toFixed(0));
        $("#MonthlyNetTakeHome").val((MonthlyNetTakeHomeAmount).toFixed(0));
        $("#EmployerPF").val((EmployerPFAmount).toFixed(0));
        $("#EmployerESI").val((EmployerESIAmount).toFixed(0));
        $("#AnnualComponents").val((AnnualComponentsAmount).toFixed(0));
        $("#PerformanceBonus").val((PerformanceBonusAmount).toFixed(0));
        $("#Gratuity").val((GratuityAmount).toFixed(0));
        $("#LeaveTravelAllowance").val((LeaveTravelAllowanceAmount).toFixed(0));
        
        let SecurityDeduction = $("#SecurityDeduction").val();
        let NetTakeHomeSalary = (MonthlyNetTakeHomeAmount - SecurityDeduction).toFixed(0);
        $("#NetTakeHomeSalary").val(NetTakeHomeSalary);
    };
    
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("Password");
        var icon = document.querySelector(".password-toggle-btn");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>