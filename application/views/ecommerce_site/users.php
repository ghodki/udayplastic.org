<div class="content-wrapper">
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <label class="mb-0" style="vertical-align: -moz-middle-with-baseline" class="text-center">
                            Store Users
                        </label>
                        <?php if ($this->session->flashdata('msg')) { ?>
                        <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                            <?php echo $this->session->flashdata('msg'); ?>
                        </div>
                        <?php } ?>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped table-bordered" id="dataList">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>State</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableData">
                                    <?php if (isset($users) && !empty($users)) { foreach ($users as $key => $unknown) { ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td>
                                            <a href="javascript:void(0);">
                                                <?= $unknown->username; ?>
                                            </a>
                                        </td>
                                        <td><?= $unknown->email; ?></td>
                                        <td><?= $unknown->phone; ?></td>
                                        <td><?= $unknown->state; ?></td>
                                        <td>
                                            <a href="javascript:void(0);" class="text-primary">
                                                <i class="fa fa-info-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } } else { echo "There Is no users"; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    @media print{
        @page {
            size: auto;
        }
    }
    .dataTables_wrapper .dt-buttons {
        float:none;  
        text-align:center;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('#dataList').DataTable({
            "autoWidth": false,
            dom: 'Blfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            columnDefs: [{
                bSortable: false,
                targets: [0]
            }]
        });
    });
</script>