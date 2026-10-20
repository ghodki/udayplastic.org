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
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-purple">
                                    Reward Point Cost
                                </h3>
                            </div>
                            <button class="btn bg-purple btn-sm pull-right" data-toggle="modal" data-target="#rewardPointCostCreate" title="Create">
                                <i class="fa fa-plus text-white"></i> Create
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-purple">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Point</th>
                                            <th>Rupee</th>
                                            <th>Min. Amount</th>
                                            <th>File</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($rewardPointCost) && !empty($rewardPointCost)) {
                                                $sr = 0;
                                                foreach ($rewardPointCost as $key => $data) {
                                                    $sr++;
                                        ?>
                                        <tr>
                                            <td><?= $sr; ?></td>
                                            <td><?= $data->rewardPoint; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= $data->equivalentRupee; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= $data->minimumWithdrawalAmount; ?></td>
                                            <td>
                                                <?php if (!empty($data->catalogue)) { ?>
                                                <a href="<?= base_url(); ?>uploads/catalogue/<?= $data->catalogue; ?>" class="btn btn-success btn-xs" title="Open Catalogue" target="_blank">
                                                    <i class="fa fa-file-pdf"></i>
                                                </a>
                                                <?php } ?>
                                            </td>                                            
                                            <td><?= $data->created; ?></td>
                                            <td><?= $data->updated; ?></td>                                            
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-success btn-xs" title="Edit" onclick="rewardPointCostEdit(<?= $data->id; ?>);">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>electrician/rewardPointCostDelete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<div class="modal fade" id="rewardPointCostCreate" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form onsubmit="onRewardPointCostFormSubmitHandler(event);" id="rewardPointCostForm" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="rewardPoint">Reward Point <span class="text-danger">*</span></label>
                            <input type="number" name="rewardPoint" value="<?= set_value('rewardPoint'); ?>" id="rewardPoint" class="form-control form-control-sm" placeholder="Reward Point" required="" autocomplete="off" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="equivalentRupee">Equivalent Rupee <span class="text-danger">*</span></label>
                            <input type="number" name="equivalentRupee" value="<?= set_value('equivalentRupee'); ?>" id="equivalentRupee" class="form-control form-control-sm" placeholder="Equivalent Rupee" required="" autocomplete="off" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="minimumWithdrawalAmount">Minimum Withdrawal Amount <span class="text-danger">*</span></label>
                            <input type="number" name="minimumWithdrawalAmount" value="<?= set_value('minimumWithdrawalAmount'); ?>" id="minimumWithdrawalAmount" class="form-control form-control-sm" placeholder="Minimum Withdrawal Amount" required="" autocomplete="off" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="catalogue">Catalogue</label>
                            <input type="file" name="catalogue" id="catalogue" class="form-control form-control-sm" title="Catalogue" accept=".pdf,application/pdf" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-xs" id="submitButton">Create</button>
                    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rewardPointCostEditModal" role="dialog" aria-labelledby="title" aria-hidden="true">
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

    const onRewardPointCostFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var rewardPoint = $("#rewardPoint").val();
        var equivalentRupee = $("#equivalentRupee").val();
        var minimumWithdrawalAmount = $("#minimumWithdrawalAmount").val();
        
        if (rewardPoint && equivalentRupee && minimumWithdrawalAmount) {
            $.ajax({
                type        :   "POST",
                url         :   "<?= base_url(); ?>electrician/rewardPointCostSave",
                data        :   new FormData($("#rewardPointCostForm")[0]),
                contentType :   false,
                processData :   false,
                success     :   function(Response) {
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
    
    const rewardPointCostEdit = (id) => {
        $("#rewardPointCostEditModal .modal-dialog .modal-content").load("<?= base_url(); ?>electrician/rewardPointCostEdit/" + id);
        $('#rewardPointCostEditModal').modal('show');
    };
    
    const onRewardPointCostFormUpdateSubmitHandler = (event) => {
        $("#updateButton").prop("disabled", true);
        event.preventDefault();
        var rewardPoint = $("#rewardPointEdit").val();
        var equivalentRupee = $("#equivalentRupeeEdit").val();
        var minimumWithdrawalAmount = $("#minimumWithdrawalAmountEdit").val();
        
        if (rewardPoint && equivalentRupee && minimumWithdrawalAmount) {
            $.ajax({
                type        :   "POST",
                url         :   "<?= base_url(); ?>electrician/rewardPointCostUpdate",
                data        :   new FormData($("#rewardPointCostFormEdit")[0]),
                contentType :   false,
                processData :   false,
                success     :   function(Response) {
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