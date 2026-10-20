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
                                    Categories
                                </h3>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary btn-sm pull-right" title="Create" data-toggle="modal" data-target="#categoryCreate">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Created</th>
                                            <th>Created By</th>
                                            <th>Updated</th>
                                            <th>Updated By</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($categories) && !empty($categories)) {
                                                $Sr = 0;
                                                foreach ($categories as $key => $data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $data->name; ?></td>
                                            <td><?= $data->created; ?></td>
                                            <td><?= $data->createdBy; ?></td>
                                            <td><?= $data->updated; ?></td>
                                            <td><?= $data->updatedBy; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="categoryEdit(<?= $data->id; ?>);" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>product/categoryDelete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<!--Create-->
<div class="modal fade" id="categoryCreate" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form onsubmit="onCategorySubmitFormHendler(event);" id="submitForm" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Category Create</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="categoryName">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="categoryName" id="categoryName" class="form-control form-control-sm" placeholder="Category Name" required="" autocomplete="off" />
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
<div class="modal fade" id="categoryEdit" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog">
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
    
    const onCategorySubmitFormHendler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var categoryName = $("#categoryName").val();
        
        if (categoryName) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>product/categorySave",
                data    :   $("#submitForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: ResponseData.Message,
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
                                        title: ResponseData.Message,
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
    
    const categoryEdit = (categoryId) => {
        $("#categoryEdit .modal-dialog .modal-content").load("<?= base_url(); ?>product/categoryEdit/" + categoryId);
        $('#categoryEdit').modal('show');
    };
    
    const onCategoryUpdateFormHendler = (event) => {
        $("#updateButton").prop("disabled", true);
        event.preventDefault();
        var categoryName = $("#categoryNameEdit").val();
        
        if (categoryName) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>product/categoryUpdate",
                data    :   $("#updateForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: ResponseData.Message,
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
                                        title: ResponseData.Message,
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