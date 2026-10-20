<style type="text/css">
    .ui-tooltip {
        will-change: transform;
        position: absolute;
        top: 0px;
        left: 0px;
        background-color: #000000;
        padding: 5px;
        color: #ffffff;
        border-radius: 5px;
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
                <div class="container-fluid mb-9" style="margin-top:50px;">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <label class="mb-0" style="vertical-align: -moz-middle-with-baseline;">
                                    Enquiry List
                                </label>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="enquiry">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($result) && $result != '') {
                                                    $n = 0;
                                                    foreach ($result as $key => $data) {
                                                        $n++;
                                            ?>
                                            <tr>
                                                <td><span><?= $n; ?></span></td>
                                                <td><?= $data->created; ?></td>
                                                <td><?= $data->name; ?></td>
                                                <td><?= $data->email; ?></td>
                                                <td><?= $data->mobile; ?></td>
                                                <td data-toggle="tooltip" data-placement="top" title="<?= $data->message; ?>">
                                                    <?= substr($data->message, 0, 200); ?>
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
    $(function() {
        $("#enquiry").DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'pageLength']
        });
    });
</script>