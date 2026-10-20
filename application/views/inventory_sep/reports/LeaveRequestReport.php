<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .card-body {
        padding: 0.25rem 0.25rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-dark">Leave Request Report</h5>
                            </div>
                            <button type="button" class="btn btn-danger btn-xs pull-right" title="Pending Request">
                                <i class="fa fa-bell text-white"></i> Pending Request : 
                                <?php
                                    $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                                    if ($this->session->userdata('BranchRole')) {
                                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                    }
                                    echo $this->db->where($Where)->where('status', 1)->get('leave_request')->num_rows(); 
                                ?>
                            </button>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" id="ReportForm">
                                <div class="row">
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="From">From</label>
                                        <input type="date" name="From" value="<?= $this->input->get('From') ? $this->input->get('From') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="To">To</label>
                                        <input type="date" name="To" value="<?= $this->input->get('To') ? $this->input->get('To') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>"  id="To" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="Status">Status</label>
                                        <select name="Status" id="Status" class="form-control form-control-sm" title="Leave Request Status">
                                            <option value="" <?= $this->input->get('Status') == '' ? 'selected' : ''; ?>>All</option>
                                            <option value="0" <?= $this->input->get('Status') == '0' ? 'selected' : ''; ?>>Rejected</option>
                                            <option value="1" <?= $this->input->get('Status') == '1' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="2" <?= $this->input->get('Status') == '2' ? 'selected' : ''; ?>>Approved</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-xs bg-purple">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/LeaveRequestReport" class="btn btn-danger btn-xs" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-xs" title="Previous Month" onclick="return PreviousMonth();">
                                            <i class="fa fa-calendar-minus-o"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-warning btn-xs" title="Current Month" onclick="return CurrentMonth();">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-dark btn-xs" title="Next Month" onclick="return NextMonth();">
                                            <i class="fa fa-calendar-plus-o"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="DataTable">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                            <th>Employee</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!empty($LeaveRequestData)) { 
                                                $Sr = 0;
                                                foreach ($LeaveRequestData as $key => $Data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->Date)); ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->StartDate)); ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->EndDate)); ?></td>
                                            <td><?= DifferenceDays($Data->StartDate, $Data->EndDate); ?></td>
                                            <td>
                                                <?php if ($Data->Status == 2) { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Approved
                                                </label>
                                                <?php } elseif ($Data->Status == 0) { ?>
                                                <label class="btn btn-danger btn-xs">
                                                    <i class="fa fa-ban text-white"></i> Rejected
                                                </label>
                                                <?php } elseif ($Data->Status == 1) { ?>
                                                <label class="btn btn-warning btn-xs">
                                                    <i class="fa fa-spinner text-white"></i> Pending
                                                </label>
                                                <?php } ?>
                                            </td>
                                            <td><?= get_title('employees', ['Id' => $Data->EmployeeId], 'Name'); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="getLeaveRequestData(<?= $Data->Id; ?>);" title="Information">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="getLeaveRequestDataEdit(<?= $Data->Id; ?>);" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>Report/LeaveDelete/<?= $Leave->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- request data -->
<div class="modal fade" id="LeaveRequestData" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"></div>
    </div>
</div>
<!-- request data end -->

<!-- request data edit -->
<div class="modal fade" id="LeaveRequestDataEdit" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"></div>
    </div>
</div>
<!-- request data edit end -->

<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">    
    const getLeaveRequestData = (Id) => {
        jQuery.noConflict();
        $("#LeaveRequestData .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/LeaveRequestData/" + Id);
        $("#LeaveRequestData").modal("show");
    };
    
    const getLeaveRequestDataEdit = (Id) => {
        jQuery.noConflict();
        $("#LeaveRequestDataEdit .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/LeaveRequestDataEdit/" + Id);
        $("#LeaveRequestDataEdit").modal("show");
    };
    
    function PreviousMonth() {
        var fromDate = document.getElementById('From').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('To').value = lastDay;
        document.getElementById('From').value = firstDay;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/LeaveRequestReport?" + form;
    }

    function CurrentMonth() {
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
        document.getElementById('From').value = today;
        document.getElementById('To').value = monthlastdate;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/LeaveRequestReport?" + form;
    }

    function NextMonth() {
        var fromDate = document.getElementById('From').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('To').value = lastDay;
        document.getElementById('From').value = firstDay;
        var form = $("#ReportForm").serialize();        
        window.location.href = "<?= base_url(); ?>Reports/LeaveRequestReport?" + form;
    }

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_LeaveRequestReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_LeaveRequestReport";
        <?php } ?>
        
        $("#DataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pageLength',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });
    });
</script>