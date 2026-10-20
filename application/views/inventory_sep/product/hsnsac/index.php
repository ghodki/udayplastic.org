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
                                    Hsn/Sac
                                </h3>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm pull-right" title="Create" data-toggle="modal" data-target="#hsnSacCreate">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-danger">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Hsn/Sac</th>
                                            <th class="text-white">Description</th>
                                            <th class="text-white">Type</th>
                                            <th class="text-white">Created</th>
                                            <th class="text-white"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($hsnSacData) && !empty($hsnSacData)) {
                                                $sr = 0;
                                                foreach ($hsnSacData as $key => $data) {
                                                    $sr++;
                                        ?>
                                        <tr>
                                            <td><?= $sr; ?></td>
                                            <td><?= $data->hsnSacCode; ?></td>
                                            <td><?= limit_words($data->hsnSacDescription, 10); ?></td>
                                            <td><?= $data->type; ?></td>
                                            <td><?= $data->created; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="hsnSacInfo(<?= $data->id; ?>);" class="btn btn-info btn-xs" title="Info">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="hsnSacEdit(<?= $data->id; ?>);" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>product/hsnSacDelete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                                <div class="mt-2 pull-right">
                                    <?= $pagination_links; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

<!--Create-->
<div class="modal fade" id="hsnSacCreate" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form onsubmit="onHsnSacSubmitFormHendler(event);" id="submitForm" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Hsn/Sac Create</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="hsnSacCode">Hsn/Sac Code <span class="text-danger">*</span></label>
                                <input type="text" name="hsnSacCode" id="hsnSacCode" class="form-control form-control-sm" placeholder="Hsn/Sac Code" required="" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="hsnSacDescription">Hsn/Sac Description</label>
                                <textarea name="hsnSacDescription" id="hsnSacDescription" class="form-control form-control-sm" placeholder="Hsn/Sac Description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="type">Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control form-control-sm" required="">
                                    <option value="" disabled="" selected="">Select Type</option>
                                    <option value="Sale">Sale</option>
                                    <option value="Service">Service</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submitButton" class="btn btn-success btn-sm">Submit</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Edit-->
<div class="modal fade" id="hsnSacModal" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
            "paging": false,
            "info": false
        });
    });
    
    const onHsnSacSubmitFormHendler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var hsnSacCode = $("#hsnSacCode").val();
        
        if (hsnSacCode) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>product/hsnSacSave",
                data    :   $("#submitForm").serialize(),
                success :   function(response) {
                                var responseData = JSON.parse(response);
                                if (responseData.status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        window.location.href = "<?= base_url(); ?>product/hsnSac";
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#submitButton").prop("disabled", false);
        }
    };
    
    const hsnSacEdit = (hsnSacId) => {
        $("#hsnSacModal .modal-dialog .modal-content").load("<?= base_url(); ?>product/hsnSacEdit/" + hsnSacId);
        $('#hsnSacModal').modal('show');
    };
    
    const hsnSacInfo = (hsnSacId) => {
        $("#hsnSacModal .modal-dialog .modal-content").load("<?= base_url(); ?>product/hsnSacInfo/" + hsnSacId);
        $('#hsnSacModal').modal('show');
    };
    
    const onHsnSacUpdateFormHendler = (event) => {
        $("#updateButton").prop("disabled", true);
        event.preventDefault();
        var hsnSacCode = $("#hsnSacCodeEdit").val();
        
        if (hsnSacCode) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>product/hsnSacUpdate",
                data    :   $("#updateForm").serialize(),
                success :   function(response) {
                                var responseData = JSON.parse(response);
                                if (responseData.status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#updateButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#updateButton").prop("disabled", false);
        }
    };
</script>