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
                                <h4 style="color: #fff; padding: 5px 0px 4px 0px" class="text-center">
                                    Add Holidays
                                </h4>
                                <form method="POST" action="<?= base_url(); ?>Holiday/save">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="holiday_name">Holiday Name <span class="text-danger">*</span></label>
                                                <input type="text" name="holiday_name" value="<?= set_value('holiday_name'); ?>" id="holiday_name" class="form-control" placeholder="Holiday Name" required="" />
                                                <span class="text-danger"><?= form_error('holiday_name'); ?></span>
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
                </div>
                    
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-primary mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-primary">Holiday List</h4>
                            </div>
                            <a href="<?= base_url(); ?>Holiday/setHoliday" class="btn btn-sm btn-primary pull-right">
                                Set Holiday
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Holiday</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($result) && $result != '') {
                                                $Sr = 0;
                                                foreach ($result as  $key => $cat) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $cat->name; ?></td>
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
                                                 <?php  } ?>
                                            </td>
                                            <td><?= date("d-m-Y", strtotime($cat->created_at)); ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Holiday/edit/<?= $cat->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Holiday/delete/<?= $cat->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure want to delete this record!');" title="Delete">
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

<script type="text/javascript">
     function changestatus(year_id, val) {
        var id = year_id;
        var value = val;
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Holiday/changeData",
            data    :   {id: id, status: value},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData) {
                                window.location.reload();
                            }
                        }
        });
    }
    
    $(function() {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
 </script>