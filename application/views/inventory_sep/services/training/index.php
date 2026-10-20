<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .select2-container {
        display: block;
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
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-purple">
                                    Training
                                </h3>
                            </div>
                            <a href="javascript:void(0);" class="btn bg-purple btn-sm pull-right" title="Create" data-toggle="modal" data-target="#trainingCreate">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered traningTable">
                                    <thead class="bg-purple">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Category</th>
                                            <th>Training</th>
                                            <th>Created</th>
                                            <th>Created By</th>
                                            <th>Updated</th>
                                            <th>Updated By</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($trainings) && !empty($trainings)) {
                                                $Sr = 0;
                                                foreach ($trainings as $key => $data) {
                                                    $Sr++;
                                                    $trainingNameData = explode(',', $data->trainingName);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= get_title('category', ['id' => $data->categoryId], 'name'); ?></td>
                                            <td>
                                                <?php
                                                    $tSr = 1;
                                                    foreach($trainingNameData as $val) {
                                                        echo $tSr++ . '. ' . $val . "&nbsp;";
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $data->created; ?></td>
                                            <td><?= $data->createdBy; ?></td>
                                            <td><?= $data->updated; ?></td>
                                            <td><?= $data->updatedBy; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="trainingEdit(<?= $data->categoryId; ?>);" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>services/trainingDelete/<?= $data->categoryId; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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
<div class="modal fade" id="trainingCreate" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form onsubmit="onTrainingSubmitFormHendler(event);" id="submitForm" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Training Create</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="categoryId">Select Category <span class="text-danger">*</span></label>
                                <select name="categoryId" id="categoryId" class="form-control form-control-sm" title="Category Name" required="">
                                    <option value="" disabled="" selected="">Select Category</option>
                                    <?php if (!empty($categories)) { foreach($categories as $key => $category) { ?>
                                    <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Sr</th>
                                        <th>Training Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="dynamicField">
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <input type="text" name="trainingName[]" id="trainingName1" class="form-control form-control-sm" placeholder="Training Name" required="" autocomplete="off" />
                                        </td>
                                        <td>
                                            <button type="button" id="add" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus-circle text-white"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
<div class="modal fade" id="trainingEdit" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.traningTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
        
        $('#categoryId').select2();
        
        var i = 1;
        $('#add').click(function(){  
           i++;  
           $('#dynamicField').append('<tr id="row'+i+'"><td>'+i+'</td><td><input type="text" name="trainingName[]" placeholder="Training Name" class="form-control form-control-sm" autocomplete="off" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-sm btn_remove"><i class="fa fa-minus-circle text-white"></i></button></td></tr>');  
        });  
      
        $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
        });         
    });
    
    const onTrainingSubmitFormHendler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var categoryId = $("#categoryId").val();
        
        if (categoryId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>services/trainingSave",
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
    
    const trainingEdit = (categoryId) => {
        $("#trainingEdit .modal-dialog .modal-content").load("<?= base_url(); ?>services/trainingEdit/" + categoryId, function () {
            $('#categoryIdEdit').select2();        
        });
        $('#trainingEdit').modal('show');
    };
    
    const onTrainingUpdateFormHendler = (event) => {
        $("#updateButton").prop("disabled", true);
        event.preventDefault();
        var categoryId = $("#categoryIdEdit").val();
        
        if (categoryId) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>services/trainingUpdate",
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