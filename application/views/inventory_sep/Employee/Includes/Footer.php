<footer class="border-top bg-dark">
    <div class="container-fluid">
        <p style="font-size: 14px; color: #fff;" class="text-center mb-0 py-3">
            Copyright © 2023 Uday Plastic. All rights reserved.
        </p>
    </div>
</footer>

<script type="text/javascript" src="<?= base_url() ?>assets/js/mastersindia_gst.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/popper.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
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
    $(document).ready(function() {
        setTimeout(function() {
            <?php unset($_SESSION['msg']); ?>
            $('.alert').hide(1000, function() {
                $(this).remove();
            });
        }, 1000);

        GetTotalSaleAndEstimate();
        GetAccountSummary();
     });

    const SaleAmountArray = [];
    const PurchaseAmountArray = [];
    const EstimateAmountArray = [];
    const DaysArray = [];
    var chart = null;
    const GetTotalSaleAndEstimate = () => {
        SaleAmountArray.length = 0;
        PurchaseAmountArray.length = 0;
        EstimateAmountArray.length = 0;
        DaysArray.length = 0;

        var Type = $("#type").val();
        var Year = $("#year").val();
        var Month = $("#month").val();
        if (Type != "" && Year != "" && Month != "") {
            $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>Employee/SalePurchaseAndEstimateData",
                    data    :   {type: Type, year: Year, month: Month},
                    success :   function(Response) {
                                    var ResponseJson = JSON.parse(Response);
                                    if (ResponseJson.status == true) {
                                        var InoviceJson = ResponseJson.InvoiceData;
                                        InoviceJson.forEach((element) => {
                                            if (element.type == "Sale") {
                                                SaleAmountArray.push(element.total);
                                                PurchaseAmountArray.push(0);
                                                EstimateAmountArray.push(0);
                                                DaysArray.push(element.invoiceDate);
                                            } 
                                            if (element.type == "Purchase") {
                                                SaleAmountArray.push(0);
                                                PurchaseAmountArray.push(element.total);
                                                EstimateAmountArray.push(0);
                                                DaysArray.push(element.invoiceDate);
                                            }
                                            if (element.type == "Estimate") {
                                                SaleAmountArray.push(0);
                                                PurchaseAmountArray.push(0);
                                                EstimateAmountArray.push(element.total);
                                                DaysArray.push(element.invoiceDate);
                                            }
                                        });

                                        const ctx = document.getElementById('Chart');

                                        if (chart) {
                                            chart.clear();
                                            chart.destroy();
                                        }

                                        chart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: DaysArray,
                                                datasets: [
                                                    {
                                                        label: 'Sale',
                                                        data: SaleAmountArray,
                                                        borderWidth: 1
                                                    },
                                                    {
                                                        label: 'Purchase',
                                                        data: PurchaseAmountArray,
                                                        borderWidth: 1
                                                    },
                                                    {
                                                        label: 'Estimate',
                                                        data: EstimateAmountArray,
                                                        borderWidth: 1
                                                    }
                                                ]
                                            },
                                            options: {
                                                scales: {
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });
                                    } else {
                                        console.log("Empty");
                                    }
                    }
            });
        } else {
            alert("Field can not be blank.");
        }
    };

    const GetAccountSummary = () => {
        $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/GetAccountSummary",
                data    :   {accountdata: true},
                success :   function(Response) {
                                var ResponseJson = JSON.parse(Response);
                                if (ResponseJson.status == true) {
                                    var Credit = ResponseJson.CreditorsBalance.Credit;
                                    var Debit = ResponseJson.DebtorsBalance.Debit;
                                    var Cash = ResponseJson.CashBalance;

                                    var xValues = [Cash + " Cash in Hand(Bank)", Credit + " Creditors", Debit + " Debtors"];
                                    var yValues = [Cash, Credit, Debit];
                                    var barColors = ["#01319d", "#f11313", "#6392fa"];

                                    new Chart("weeklyStatistic", {
                                        type: "doughnut",
                                        data: {
                                            labels: xValues,
                                            datasets: [{
                                                backgroundColor: barColors,
                                                data: yValues
                                            }]
                                        },
                                        options: {
                                            title: {
                                                display: true,
                                                text: "Account's Summary"
                                            }
                                        }
                                    });
                                }
                            }
        });
    };

    function searchFilter1(page_num){
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords1').val();
        var sortBy = $('#sortBy').val();
        $.ajax({
            type        :   "POST",
            url         :   "<?= base_url('Employee/AjaxOutstatdingData/'); ?>"+page_num,
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
            url         :   "<?= base_url('Employee/AjaxPreviousSaleData/'); ?>"+page_num,
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
            url         :   "<?= base_url('Employee/AjaxPopullerProductsData/'); ?>"+page_num,
            data        :   "page="+page_num+"&keywords="+keywords+"&sortBy="+sortBy,
            success     :   function(html){
                                $('#PopullerProductsList').html(html);
                            }
        });
    }
</script>