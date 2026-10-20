<style type="text/css">
    .form-group {
        margin-bottom: 0.5rem;
    }
    th, td {
        white-space: nowrap;
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
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <h3 class="m-0">Orderbook</h3>
                </div><!-- /.col -->
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Reports</a></li>
                        <li class="breadcrumb-item active">Orderbook Report</li>
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
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-maroon">
                                    Orderbook Report
                                </h5>
                            </div>
                            <div class="pull-right">
                                <?php if (employee_perm($EmployeePermission, 'Orderbook', 'Create')) { ?>
                                <a href="<?= base_url(); ?>Employee/Orderbook" class="btn btn-xs bg-maroon" title="Orderbook Create">
                                    <i class="fa fa-plus text-white"></i> Create
                                </a>
                                <?php } ?>
                                <a href="<?= base_url(); ?>Employee/OrderbookReport" class="btn btn-xs bg-primary" title="Orderbook Report List View">
                                    <i class="fa fa-th-list text-white"></i> List View
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>Employee/OrderbookReportGrid" method="GET">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="From" value="<?= set_value('From', $this->input->get('From') ? $this->input->get('From') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="To" value="<?= set_value('To', $this->input->get('To') ? $this->input->get('To') : date('Y-m-d')); ?>" id="To" class="form-control form-control-sm" title="To" />
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-4 form-group">
                                        <select name="Status" id="Status" class="form-control form-control-sm" title="Orderbook Status">
                                            <option value="" <?= $this->input->get('Status') == '' ? 'selected' : ''; ?>>All</option>
                                            <option value="1" <?= $this->input->get('Status') == '1' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="2" <?= $this->input->get('Status') == '2' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="0" <?= $this->input->get('Status') == '0' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <button type="submit" class="btn btn-sm bg-maroon">
                                            <i class="fa fa-search text-white"></i>
                                        </button>
                                        <a href="<?= base_url(); ?>Employee/OrderbookReportGrid" class="btn btn-default btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xxl-4 col-xs-4">
                                        <input type="search" name="Search" class="form-control mb-2" id="Search" placeholder="Search By Party Name" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row" id="OrderbookReportData"></div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
 </div>

<script type="text/javascript">
    $(document).ready(function() {
        var fromDate = $('#From').val();
        var toDate = $('#To').val();
        var Status = $('#Status').val();
        var PartyName = $('#Search').val();
        var Limit = 10;
        var Start = 0;
        var Action = 'Inactive';
        
        const OrderbookReportGrid = (Limit, Start, PartyName) => {
            $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Employee/OrderbookReportGridAjax",
                data    :   {fromDate: fromDate, toDate: toDate, Status: Status, Limit: Limit, Start: Start, PartyName: PartyName},
                cache   :   false,
                success :   function(Response) {
                                if (Start === 0) {
                                    $('#OrderbookReportData').html(Response);
                                } else {
                                    $('#OrderbookReportData').append(Response);
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
            OrderbookReportGrid(Limit, Start, PartyName);
        }
        
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() > $("#OrderbookReportData").height() && Action == 'Inactive') {
                Action = 'Active';
                Start = Start + Limit;
                setTimeout(function(){
                    OrderbookReportGrid(Limit, Start, PartyName);
                }, 500);
            }
        });
        
        $('#Search').on('input', function () {
            let PartyName = $(this).val().trim();
            Start = 0;
            OrderbookReportGrid(Limit, Start, PartyName);
        });
    });
</script>