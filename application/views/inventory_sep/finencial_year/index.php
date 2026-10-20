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
                    <div class="header bg-gradient-green p-2">
                        <div class="header-body">
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Add Year</h2>
                            <form method="POST" action="<?= base_url(); ?>finencial_year/save">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="syear" class="text-white">Starting Year <span class="text-danger">*</span></label>
                                            <input type="date" name="syear" value="<?= set_value('syear'); ?>" id="syear" class="form-control" title="Starting Year" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="eyear" class="text-white">Ending Year <span class="text-danger">*</span></label>
                                            <input type="date" name="eyear" value="<?= set_value('eyear'); ?>" id="eyear" class="form-control" title="Ending Year" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="year" class="text-white">Add Financial Year <span class="text-danger">*</span></label>:
                                            <input type="text" name="year" value="<?= set_value('year'); ?>" id="year" class="form-control" placeholder="Add Financial Year" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                        <input type="reset" class="btn btn-danger" value="Reset">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-cyan mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-cyan">Year List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-cyan">
                                        <tr>
                                            <th class="text-white">Sr</th>
                                            <th class="text-white">Date</th>
                                            <th class="text-white">Starting Year</th>
                                            <th class="text-white">Ending year</th>
                                            <th class="text-white">Financial Year</th>
                                            <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($year) && $year != '') {
                                                $Sr = 0;
                                                foreach ($year as $key => $value) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date("d-m-Y", strtotime($value->created_at)); ?></td>
                                            <td><?= date('d-m-Y', strtotime($value->starting_year)); ?></td>
                                            <td><?= date('d-m-Y', strtotime($value->ending_year)); ?></td>
                                            <td><?= $value->year; ?></td>
                                            <td>
                                                <?php if ($value->status == 1) { ?>
                                                <span class="text-success"></span>
                                                <label class="switch">
                                                    <input type="checkbox" checked="" onchange="changestatus(<?= $value->id; ?>, 0);" />
                                                    <span class="slider round"></span>
                                                </label>
                                                <?php } else { ?>
                                                <span class="text-danger"></span>
                                                <label class="switch">
                                                    <input type="checkbox" onchange="changestatus(<?= $value->id; ?>, 1);" />
                                                    <span class="slider round"></span>
                                                </label>
                                                <?php } ?>
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
            url     :   "<?= base_url(); ?>finencial_year/changeData",
            data    :   {year_id: id, status: value},
            success :   function (data) {
                            var data1 = JSON.parse(data);
                            if (data1) {
                                window.location.reload();
                            }
                        }
        });
    }
</script>