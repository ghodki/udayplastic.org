<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    .card-header {
        padding: .5rem .5rem;
    }
    .card {
        margin-bottom: 0rem;
    }
    .card-footer p {
        margin-bottom: 0rem;
    }
    .btn-group{
        display: block;
    }
    .custom-select {
        height: calc(2rem + 1px);
        line-height: 1;
    }
    .widget-user-2 .widget-user-header {
        padding: 0.25rem;
    }
    .widget-user-2 .widget-user-desc, .widget-user-2 .widget-user-username {
        margin-left: 5px;
    }
    .widget-user-2 .widget-user-username {
        font-size: 15px;
    }
    .widget-user-desc {
        font-size: 12px;
    }
</style>

 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Sales</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Sales</a></li>
                        <li class="breadcrumb-item active">Sale Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                
                <div class="col-lg-12">
                    <!-- Index Start -->
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-maroon">Sales Report</h5>
                            </div>
                            <div class="pull-right">
                                <button type="button" title="Previous Month" onclick="return PreMonth();" class="btn btn-primary btn-sm">
                                    <i class="fa fa-calendar-minus text-white"></i>
                                </button>
                                <button type="button" title="Current Month" id="currentMont" onclick="return curent();" class="btn btn-warning btn-sm">
                                    <i class="fa fa-calendar-check text-white"></i>
                                </button>
                                <button type="button" title="Next Month" id="nextMonth" onclick="return nextmonth();" class="btn btn-danger btn-sm">
                                    <i class="fa fa-calendar-plus text-white"></i>
                                </button>
                                <a href="<?= base_url(); ?>Employee/SalesReport" title="List View" class="btn bg-maroon btn-sm">
                                    <i class="fa fa-th-list text-white"></i> List View
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                    <div class="row">
                                        <?php 
                                            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                                $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeInfo->Id, 'IsDeleted' => 1], 'WarehouseSalesman'), true);
                                                if (!is_array($SalesmanIds)) {
                                                    $SalesmanIds = ($SalesmanIds !== null && $SalesmanIds !== '') ? [$SalesmanIds] : [];
                                                }
                                                $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');

                                                $salesmans = !empty($SalesmanIds)
                                                    ? $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $SalesmanIds)->get('employees')->result()
                                                    : [];
                                                $salesman_selected = [];
                                                foreach (input_get_array('salesman') as $key => $sids) {
                                                    $salesman_selected[] = $sids;
                                                }
                                        ?>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="salesman">Salesman</label>
                                            <select name="salesman[]" id="salesman" class="form-control form-control-sm" multiple="multiple" title="Salesman">
                                                <option <?= in_array('All', $salesman_selected) ? 'selected' : ''; ?> value="All" <?= set_select('salesman', 'All'); ?>>All</option>
                                                <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $sdata) { ?>
                                                <option <?= in_array($sdata->Id, $salesman_selected) ? 'selected' : ''; ?> value="<?= $sdata->Id; ?>" <?= set_select('salesman', $sdata->Id); ?>><?= $sdata->Name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="Party">Party</label>
                                            <select name="Party" id="Party" class="form-control form-control-sm" title="Party">
                                                <option value="All" <?= $this->input->get('Party') == 'All' ? 'selected' : ''; ?><?= set_select('Party', 'All'); ?>>All</option>
                                                <?php
                                                    $PartyWhere = ['user_id' => $this->session->userdata('UserId'), 'comp_id' => $this->session->userdata('CompId')];
                                                    $empId = !empty($EmployeeInfo) ? $EmployeeInfo->Id : $this->session->userdata('EmployeeId');
                                                    $PartySalesman = json_decode(get_title('employees', ['Id' => $empId, 'IsDeleted' => 1], 'WarehouseSalesman'), true);
                                                    if (!is_array($PartySalesman)) {
                                                        $PartySalesman = ($PartySalesman !== null && $PartySalesman !== '') ? [$PartySalesman] : [];
                                                    }
                                                    if ($this->session->userdata('RoleType') != "Account Operator" && $this->session->userdata('RoleType') != "Supervisor" && $this->session->userdata('RoleType') != "Team Leader") {
                                                        $PartySalesman = [];
                                                    }
                                                    $PartySalesman[] = $empId;
                                                    $PartySalesman = array_values(array_unique(array_filter($PartySalesman, static function ($id) {
                                                        return $id !== null && $id !== '';
                                                    })));
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $PartyWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                    }

                                                    $Parties = !empty($PartySalesman)
                                                        ? $this->db->select('id, name')->where($PartyWhere)->where_in('SalesmanId', $PartySalesman)->order_by('id', 'DESC')->get('parties')->result()
                                                        : [];
                                                    if (isset($Parties) && !empty($Parties)) {
                                                        foreach ($Parties as $key => $Party) {
                                                ?>
                                                <option value="<?= $Party->id; ?>" <?= $this->input->get('Party') == $Party->id ? 'selected' : ''; ?><?= set_select('Party', $Party->id); ?>><?= $Party->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-1 col-sm-1">
                                            <label>Estimate</label>
                                            <br />
                                            <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                            Estimate
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="fromDate">From Date</label>
                                            <input type="date" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="toDate">To Date</label>
                                            <input type="date" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-1 col-sm-1">
                                            <label for="Delivery">Delivery</label>
                                            <select name="Delivery" id="Delivery" class="form-control form-control-sm" title="Delivery">
                                                <option value="All" <?= $this->input->get('Delivery') == "All" ? "selected" : ""; ?>>All</option>
                                                <option value="1" <?= $this->input->get('Delivery') == "1" ? "selected" : ""; ?>>Yes</option>
                                                <option value="0" <?= $this->input->get('Delivery') == "0" ? "selected" : ""; ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                            <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                                Search
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" title="Refresh" id="resetbtn">
                                                <i class="fa fa-refresh text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xxl-4 col-xs-4">
                                        <input type="search" name="Search" class="form-control mb-2" id="Search" placeholder="Search By Party Name" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row" id="SalesReportData"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Index End -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="PaymentRequest" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="DeliveryChat" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

    });
        
    const PaymentRequestForm = (Id) => {
        jQuery.noConflict();
        $("#PaymentRequest .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/PaymentRequestForm/" + Id);
        $("#PaymentRequest").modal("show");
    };
    
    const onDeliveryChatHandler = (Id) => {
        jQuery.noConflict();
        $("#DeliveryChat .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/DeliveryChatHandler/" + Id);
        $("#DeliveryChat").modal("show");
        getSaleDeliveryChat(Id);
    };
    
    const onChatFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var Chat = $("#Chat").val();
        
        if (Chat) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/DeliveryChatSave",
                data    :   $("#ChatForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    toastr.success(ResponseData.Message);
                                    window.setTimeout(function() {
                                        $("#Chat").val("");
                                        
                                        getSaleDeliveryChat($("#InvoiceId").val());
                                        $("#submitButton").prop("disabled", false);
                                    }, 1000);
                                } else {
                                    toastr.error(ResponseData.Message);
                                    $("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            toastr.error("Please enter required fields.");
            $("#submitButton").prop("disabled", false);
        }
    };
    
    const getSaleDeliveryChat = (InvoiceId) => {
        if (InvoiceId != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/getSaleDeliveryChat",
                data    :   {InvoiceId: InvoiceId},
                success :   function (Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status == true) {
                                    $(".card-comments").html(ResponseData.Chat);
                                } else {
                                    $(".card-comments").html('<span class="text-danger text-center">No Record found.</span>');
                                }
                            }
            });
        }
    };
    
    $('#salesman').multiselect({
        buttonWidth: 'auto'
    });

    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>Employee/SalesReportGrid";
    });

    function PreMonth() {
        var fromDate = document.getElementById('fromDate').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $('#reportForm').serialize();        
        window.location.href = "<?= base_url(); ?>Employee/SalesReportGrid?" + form;
    }

    function curent() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }
        today = yyyy + '-' + mm + '-' + '01';
        var monthlastdate = yyyy + '-' + mm + '-' + dd;
        document.getElementById('fromDate').value = today;
        document.getElementById('toDate').value = monthlastdate;
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>Employee/SalesReportGrid?" + form;
    }

    function nextmonth() {
        var fromDate = document.getElementById('fromDate').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $('#reportForm').serialize();        
        window.location.href = "<?= base_url(); ?>Employee/SalesReportGrid?" + form;
    }
    
    const ifCheque = () => {
        if ($('#payment_mode').val() == 6) {
            $('.Cheque').removeClass('d-none');
        } else {
            $('.Cheque').addClass('d-none');
        }
    };
    
    const onDeliveryInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/DeliveryInfo/" + Id);
        $('#Info').modal('show');
    };
    
    $(document).ready(function() {
        $('#Party').select2();
        
        var salesman = $('#salesman').val();
        var Party = $('#Party').val();
        var estimate = $("#estimate:checked").val();
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        var PartyName = $('#Search').val();
        var Limit = 10;
        var Start = 0;
        var Action = 'Inactive';
        
        const SalesReportGrid = (Limit, Start, PartyName) => {
            $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Employee/SalesReportGridAjax",
                data    :   {salesman: salesman, Party: Party, estimate: estimate, fromDate: fromDate, toDate: toDate, Limit: Limit, Start: Start, PartyName: PartyName},
                cache   :   false,
                success :   function(Response) {
                                if (Start === 0) {
                                    $('#SalesReportData').html(Response);
                                } else {
                                    $('#SalesReportData').append(Response);
                                }
                                if (Response == '') {
                                    Action = 'Active';
                                } else {
                                    Action = 'Inactive';
                                }
                            }
            });
        };
        
        if (Action == 'Inactive') {
            Action = 'Active';
            SalesReportGrid(Limit, Start, PartyName);
        }
        
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() > $("#SalesReportData").height() && Action == 'Inactive') {
                Action = 'Active';
                Start = Start + Limit;
                setTimeout(function(){
                    SalesReportGrid(Limit, Start, PartyName);
                }, 500);
            }
        });
        
        $('#Search').on('input', function () {
            let PartyName = $(this).val().trim();
            Start = 0;
            SalesReportGrid(Limit, Start, PartyName);
        });
    });
</script>