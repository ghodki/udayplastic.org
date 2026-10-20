<style type="text/css">
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
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-primary">
                                    Electrician Report
                                </h3>
                            </div>
                            <a href="<?= base_url(); ?>electrician/create" class="btn btn-primary btn-sm pull-right" title="Create">
                                <i class="fa fa-user-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($electricians) && !empty($electricians)) {
                                                $Sr = 0;
                                                foreach ($electricians as $key => $data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-2 col-xs-2 col-lg-2 col-xx-2 col-sm-2 col-xxl-2">
                                                        <?php 
                                                            $photographfile = $data->photograph;
                                                            $photograph = 'uploads/electrician/' . $photographfile;
                                                            if (file_exists($photograph) && !empty($photographfile)) {
                                                        ?>
                                                        <img src="<?= base_url() . '/' . $photograph; ?>" alt="Photograph" style="width: 30px; height: 30px; border-radius: 25px;" />
                                                        <?php } else { ?>
                                                        <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 30px; border-radius: 25px;" />
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 col-lg-8 col-xx-8 col-sm-8 col-xxl-8">
                                                        <a href="<?= base_url(); ?>electrician/insight/<?= $data->id; ?>">
                                                            <?= $data->name; ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $data->mobile; ?></td>
                                            <td><?= $data->email; ?></td>
                                            <td><?= $data->gender; ?></td>
                                            <td>
                                                <?php if ($data->status == '1') { ?>
                                                <a href="<?= base_url(); ?>electrician/status/<?= $data->id; ?>" class="badge badge-success" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                    <i class="fa fa-check-circle text-white"></i> Active
                                                </a>
                                                <?php } elseif ($data->status == '0') { ?>
                                                <a href="<?= base_url(); ?>electrician/status/<?= $data->id; ?>" class="badge badge-danger" title="Change Status" onclick="return confirm('Are you sure want to change status!');">
                                                    <i class="fa fa-ban text-white"></i> Inactive
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="electricianInfo(<?= $data->id; ?>);" class="btn btn-primary btn-xs" title="More Info">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>electrician/edit/<?= $data->id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>electrician/delete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<div class="modal fade" id="userModal" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
    
    const electricianInfo = (userId) => {
        $("#userModal .modal-dialog .modal-content").load("<?= base_url(); ?>electrician/info/" + userId);
        $('#userModal').modal('show');
    };
</script>