<style type="text/css">
    .header label {
        color: #ffffff;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h2 style="color: #fff; padding: 5px 0px 5px 0px;" class="text-center">
                                    Set Holiday
                                </h2>
                                <form method="POST" action="<?= base_url(); ?>Holiday/saveHoliday">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="holiday_id">Holiday Name <span class="text-danger">*</span></label>
                                                <select name="holiday_id" id="holiday_id" class="form-control" title="Holiday Name" required="">
                                                    <option value="" selected="" disabled="">Select Holiday</option>
                                                    <?php foreach ($holidays as $holiday) { ?>
                                                    <option value="<?= $holiday->id; ?>" <?= set_select('holiday_id', $holiday->id); ?>><?= $holiday->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?= form_error('holiday_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                                <input type="date" name="start_date" value="<?= set_value('start_date'); ?>" id="start_date" class="form-control" title="Start Date" required="" />
                                                <span class="text-danger"><?= form_error('start_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                                <input type="date" name="end_date" value="<?= set_value('end_date'); ?>" id="end_date" class="form-control" title="End Date" required="" />
                                                <span class="text-danger"><?= form_error('end_date'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-2">
                                            <button type="submit" class="btn btn-primary">
                                                Save
                                            </button>
                                            <input type="reset" class="btn btn-danger" value="Reset" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-12 col-lg-12">
                        <div class="card card-outline card-dark mt-2">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-dark">Holiday List</h4>
                                </div>
                                <a href="<?= base_url(); ?>Holiday" class="btn btn-sm btn-dark pull-right">
                                    <i class="fa fa-angle-double-left"></i> Back
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Holiday</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($result) && $result != '') {
                                                    $Sr = 0;
                                                    foreach ($result as $key => $cat) {
                                                        $Sr++;
                                                        $holiday = $this->user_model->getRow('holidays', ['id' => $cat->holiday_id]);
                                            ?>
                                            <tr>
                                                <td><?= $Sr; ?></td>
                                                <td><?= $holiday->name; ?></td>
                                                <td><?= date('d-m-Y', strtotime($cat->start_date)); ?></td>
                                                <td><?= date('d-m-Y', strtotime($cat->end_date)); ?></td>
                                                <td>
                                                    <?php if ($cat->status == 1) { ?>
                                                    <span class="text-success"></span>
                                                    <label class="switch">
                                                        <input type="checkbox" checked="" onchange="changestatus(<?= $cat->id; ?>, 0);" />
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <?php } else { ?>
                                                    <span class="text-danger"></span>
                                                    <label class="switch">
                                                        <input type="checkbox" onchange="changestatus(<?= $cat->id; ?>, 1);" />
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url(); ?>Holiday/editHoliday/<?= $cat->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url(); ?>Holiday/DeleteHoliday/<?= $cat->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure want to delete this record!');" title="Delete">
                                                        <i class="fa fa-trash"></i>
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
</div>

<script type="text/javascript">
    function changestatus(year_id, val) {
        var id = year_id;
        var value = val;
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Holiday/changeHolidayDataStatus",
            data    :   {id: id, status: value},
            success :   function (Response) {
                            var JSONResponse = JSON.parse(Response);
                            if (JSONResponse) {
                                window.location.reload();
                            }
                        }
        });
    }

    $('#start_date').change(function () {
        var dateInput = document.getElementById('end_date');
        var selected_date = $("#start_date").val();
        dateInput.setAttribute('min', selected_date);
    });

    $('#end_date').change(function () {
        var dateInput = document.getElementById('start_date');
        var selected_date = $("#end_date").val();
        dateInput.setAttribute('max', selected_date);
    });

    $(document).ready(function () {
        var start_date = $("#start_date").val();
        if (start_date != '') {
            var dateInput = document.getElementById('end_date');
            dateInput.setAttribute('min', start_date);
        }

        var end_date = $("#end_date").val();
        if (end_date != '') {
            var dateInput = document.getElementById('start_date');
            dateInput.setAttribute('max', end_date);
        }
    });

    $(function () {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script>