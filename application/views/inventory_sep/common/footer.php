<?php
/* FOOTER_ALERT_DEFAULTS_V2 */
$NewFinancialYear = $NewFinancialYear ?? null;
$NewFinancialYearFlag = $NewFinancialYearFlag ?? null;
$ChequeData = $ChequeData ?? null;
?><footer class="border-top bg-dark">
    <div class="container-fluid">
        <p style="font-size: 14px; color: #fff;" class="text-center mb-0 py-3">
            Copyright © 2023 Uday Plastic. All rights reserved.
        </p>
    </div>
</footer>

<script type="text/javascript" src="<?= base_url() ?>assets/js/mastersindia_gst.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/mastersindia_eway_gst.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/selectize.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/toastr.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script type="text/javascript" src="<?= base_url(); ?>dist/js/adminlte.js"></script>
<!-- OPTIONAL SCRIPTS -->
<script type="text/javascript" src="<?= base_url(); ?>dist/js/demo.js"></script>
<!-- jQuery Mapael -->
<script type="text/javascript" src="<?= base_url(); ?>plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>plugins/raphael/raphael.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>plugins/jquery-mapael/maps/usa_states.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>plugins/chart.js/Chart.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>dist/js/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/dt/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/wow.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/script.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>inv-assets/js/chart.js"></script>
<script type="text/javascript">            
    $(function() {
        $(".DataTable").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
    
    const ChangeCompany = (CompData) => {
        let text = CompData;
        let other = text.split(", ");
        if (other[1] == "AssignCompany") {
            window.location.href='<?= base_url(); ?>inventory_billing/AssignCompanyRedirect/' + other[0];
        } else if (other[1] == "Company") {
            window.location.href='<?= base_url(); ?>inventory_billing/redirect/' + other[0];
        }
    };

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

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        
        <?php unset($_SESSION['msg']); ?>
        $('#loading-image').hide();
        jQuery.noConflict();
        setTimeout(function() {
            $('.alert').hide(1000, function() {
                $(this).remove();
            });
        }, 1000);
     
        $(".addnew").click(function() {
            $(".addnewrole").toggle();
        });
                
        setTimeout(function() {
            <?php unset($_SESSION['msg']); ?>
            $('.alert').hide(1000, function() {
                $(this).remove();
            });
        }, 1000);

        <?php
            // Safe for pages that do not pass dashboard alert vars (avoids PHP 8 undefined-variable warnings)
            $_invFooterVars = get_defined_vars();
            $_newFy = $_invFooterVars['NewFinancialYear'] ?? null;
            $_newFyFlag = $_invFooterVars['NewFinancialYearFlag'] ?? null;
            $_chequeData = $_invFooterVars['ChequeData'] ?? null;
            if ($_newFy && $_newFy == date('Y-m-d') && (int) $_newFyFlag === 0) {
                echo 'FinancialYearAlert();';
            }
            if (!empty($_chequeData)) {
                echo 'ChequeAlert();';
            }
            unset($_invFooterVars, $_newFy, $_newFyFlag, $_chequeData);
        ?>
     });


    function searchFilter1(page_num){
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords1').val();
        var sortBy = $('#sortBy').val();
        $.ajax({
            type        :   "POST",
            url         :   "<?= base_url('inventory_billing/AjaxOutstatdingData/'); ?>"+page_num,
            data        :   "page="+page_num+"&keywords="+keywords+"&sortBy="+sortBy,
            success     :   function(html){
                                $('#OutstatdingList').html(html);
                            }
        });
    }

    function searchFilter2(page_num){
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords2').val();
        var sortBy = $('#sortBy').val();
        $.ajax({
            type        :   "POST",
            url         :   "<?= base_url('inventory_billing/AjaxPreviousSaleData/'); ?>"+page_num,
            data        :   "page="+page_num+"&keywords="+keywords+"&sortBy="+sortBy,
            success     :   function(html){
                                $('#PreviousSaleList').html(html);
                            }
        });
    }

    function searchFilter3(page_num){
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords3').val();
        var sortBy = $('#sortBy').val();
        $.ajax({
            type        :   "POST",
            url         :   "<?= base_url('inventory_billing/AjaxPopullerProductsData/'); ?>"+page_num,
            data        :   "page="+page_num+"&keywords="+keywords+"&sortBy="+sortBy,
            success     :   function(html){
                                $('#PopullerProductsList').html(html);
                            }
        });
    }
    
    const ChequeAlert = () => {
        var ChequeAlert = new bootstrap.Modal(document.getElementById('ChequeAlert'), {
            keyboard: true
        });
        ChequeAlert.show();
    };
    
    const FinancialYearAlert = () => {
        var FinancialYearAlert = new bootstrap.Modal(document.getElementById('FinancialYearAlert'), {
            keyboard: false
        });
        FinancialYearAlert.show();
    };
    
    $('.floatNumber').keypress(function(eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
            eve.preventDefault();
        }
        // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
        $('.floatNumber').keyup(function(eve) {
            if ($(this).val().indexOf('.') == 0) {
                $(this).val($(this).val().substring(1));
            }
        });
    });

    function setNotification(table_id, status) {
        $.ajax({
            url     :   "<?= base_url(); ?>offer/setNotification",
            type    :   "POST",
            data    :   {table_id: table_id, notification_status: status},
            success :   function(response) {
                            response = JSON.parse(response);
                            console.log(response, "responce");
                            if (response.status === true) {
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                    toastr.error(response.message);
                            }
                        }
        });
    }

    function IsNumeric(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    
    $('.module').change(function(event) {
        var flag = true;
        $('.module').each(function() {
            if (!$(this).is(":checked")) {
                flag = false;
            }
        });
        $('#selectAll').prop('checked', flag);
    });

    $('.childModule').change(function(event) {
        var id = $(this).attr('id');
        var flag = false;
        $('.childModule_' + id).each(function() {
            if ($(this).is(":checked")) {
                flag = true;
            }
        });
        $('.parentModule_' + id).prop('checked', flag);
    });

    $('.parentModule').change(function(event) {
        var id = $(this).attr('id');
        $('.childModule_' + id).prop('checked', this.checked);
        //childModule_1
    });

    $('#selectAll').change(function(event) {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $("#wrapper").toggleClass("toggled");
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        console.log("hrllo")
        $("#wrapper").toggleClass("toggled");
    });

    function checkuser() {
        var select_role_id = document.getElementById("roles").value;
        console.log(select_role_id);
        $(".poultryNamediv").hide();
        if (select_role_id == 2) {
            $(".poultryNamediv").show();
        }
    }

    function loginInfo(id) {
        $("#loginmodal .modal-dialog .content").load("<?= base_url('reports/content'); ?>/" + id);
        $("#loginmodal").modal("show");
    }
</script>  
