<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                    
                <div class="col-lg-12">
                    <div class="card card-outline card-fuchsia">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-fuchsia">Billing Series List</h3>
                            </div>
                            <a href="<?= base_url(); ?>series/series_list" class="btn btn-info pull-right">
                                <i class="fa fa-angle-double-left"></i> Go Back
                            </a>
                            <input id="seriesid" type="hidden" value="<?= set_value('seriesid', $seriesid); ?>" />
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered dataTable" id="example">
                                <thead class="bg-fuchsia">
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Series Start</th>
                                        <th>Financial Year</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if ($seriess) {
                                            $Sr = 0;
                                            foreach ($seriess as $key => $row) {
                                                $Sr++;
                                    ?>
                                    <tr>
                                    <td><?= $Sr; ?></td>
                                    <td><?= $this->user_model->getSeriss($row); ?></td>
                                    <td>
                                        <?php
                                            $where = ['id' => $row->financial_year];
                                            $year = $this->user_model->getRow('year', $where);
                                            if ($year) {
                                                echo $year->year;
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <input type="radio" id="status" name="status" value="<?= $row->series_id; ?>" <?= $row->status == 2 ? "checked" : '' ?> />
                                    </td>
                                    <td>
                                        <a href="<?= base_url(); ?>series/edit/<?= $row->series_id; ?>" class="btn btn-success btn-sm" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="return deleteRow('<?= base_url(); ?>series/delete/<?= $row->series_id; ?>');" class="btn btn-danger btn-sm" title="Delete">
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#example').DataTable();
        
        $('input[type=radio][name="status"]').on('change', function () {
            var id = this.value;
            var series = document.getElementById("seriesid");
            var seriesid = series.value;
            var r = confirm("Activate New Billing Series?");

            if (r == true) {
                $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>series/activeseries",
                    data    :   {id: id, seriesid: seriesid},
                    success :   function (data) {
                                    var data1 = JSON.parse(data);
                                    if (data1.status == true) {
                                        window.location.reload(true);
                                    } else {
                                        window.location.reload(true);
                                    }
                                }
                });
            } else {
                window.location.reload(true);
            }
        });
    });
    
    function deleteRow(url) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                removeRow(url);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }

    function removeRow(url) {
        $.ajax({
            type    :   "GET",
            url     :   url,
            success :   function (success) {
                            success = JSON.parse(success);
                            if (success.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: success.msg,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: success.msg,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                            }
                        }
        });
    }
</script>