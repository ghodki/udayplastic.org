<style type="text/css">
    div.dt-buttons {
        left: 135px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green pb-1 pt-1 pt-md-1">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px;" class="text-center">
                                    Add Product Service
                                </h2>
                                <form action="<?= base_url() ?>product_service/save" method="POST" enctype="multipart/form-data">
                                    <?php if (isset($error) && $error != '') { echo $error; } ?>

                                    <div class="row">
                                        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input id="name" name="name" placeholder="Product Service Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?= form_error('name'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                            <select class="form-control" name="unit" id="unit" required>
                                                <option value="">Select Unit</option>
                                                <?php
                                                    $acgroup = $this->user_model->getAry('unit');
                                                    foreach ($acgroup as $units) {
                                                ?>
                                                <option value="<?= $units->id; set_value('unit') == $units->id ? 'selected' : '' ?>"><?= $units->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="col-sm-4 control-label">
                                                <?=form_error('unit'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input id="price" name="price" min="1" placeholder="Product Service Charge" type="number" class="form-control" value="<?= set_value('price') ?>">
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?= form_error('price'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea id="description" name="long_des" placeholder="Description" class="form-control"><?= set_value('long_des'); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="reset" class="btn btn-danger">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="container-fluid">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <label style="vertical-align: -moz-middle-with-baseline; color: #000000;">
                                            Product Service List
                                        </label>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-items-center table-flush" style="width: 100%;" id="product_service">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Product Service</th>
                                                        <th>Charge</th>
                                                        <th>Action</th>
                                                        <th>Description</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark row_position">
                                                    <?php
                                                        if (isset($product_service) && $product_service != '') {
                                                            foreach ($product_service as  $key => $value) {
                                                                $date = $value->created_at;
                                                                $newDate = date("d-m-Y", strtotime($date));
                                                    ?>
                                                    <tr id="<?= $value->id; ?>">
                                                        <td>
                                                            <span><?= $key + 1; ?></span>
                                                        </td>
                                                        <td><?= $newDate; ?></td>
                                                        <td><?= $value->name; ?></td>
                                                        <td><?= $value->amount; ?></td>
                                                        <?php if ($value->status == 1) { ?>
                                                        <td class="text-blue">
                                                            <button class="btn btn-success" onclick="changeStatus('<?= $value->id; ?>', '0')">
                                                                Active
                                                            </button>
                                                        </td>
                                                        <?php } else { ?>
                                                        <td class="text-blue">
                                                            <button class="btn btn-danger" onclick="changeStatus('<?= $value->id; ?>', '1')">
                                                                Deactivated
                                                            </button>
                                                        </td>
                                                        <?php } ?>
                                                        <td><?= $value->des; ?></td>
                                                        <td>
                                                            <a href="<?= base_url('product_service/edit/') . $value->id; ?>">
                                                                <i class='far fa-edit' style='font-size:36px;'></i>
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
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#product_service").DataTable({
            dom: 'lBfrtip',
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]]
        });
    });
        
    $(".row_position").sortable({
        delay   :   150,
        stop    :   function() {
                        var selectedData = new Array();
                        $('.row_position>tr').each(function() {
                            selectedData.push($(this).attr("id"));
                        });
                        updateOrder(selectedData);
                    }
    });

    function updateOrder(value) {
        console.log(value);
        $.ajax({
            url     :   "<?= base_url(); ?>product_service/changeApplicationPosition",
            type    :   "POST",
            data    :   {position: value},
            success :   function(data) {
                            toastr.success('Your Change Successfully Saved.');
                        }
        });
    }

    function changeStatus(service_id, status) {
        let returnValue = confirm("Are You Sure Want to change Status?");
        if (returnValue) {
            $.ajax({
                url         :   "<?= base_url(); ?>productServiceChangeStatus",
                type        :   "POST",
                data        :   {service_id: service_id, status: status},
                dataType    :   "JSON",
                success     :   function(response) {
                                    console.log(response.status);
                                    if (response.status == 'success') {
                                        toastr.success(response.msg);
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        toastr.error(response.msg);
                                    }
                                }
            });
        }
    }
</script>