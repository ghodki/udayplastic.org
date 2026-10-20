<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body pl-2 pr-2">
                            <h4 style="color: white;" class="text-center">
                                Add Slider
                            </h4>
                            <!-- Card stats -->
                            <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                                <?= $this->session->flashdata('msg'); ?>
                            </div>
                            <?php } ?>

                            <?= $error; ?>

                            <?= form_open_multipart('slider/upload'); ?>
                                <div class="row">
                                    <div class="col-md-6 col-md-6 col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="Title">Title</label>
                                            <input type="text" name="Title" value="<?= set_value('Title'); ?>" id="Title" class="form-control form-control-sm" placeholder="Title" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-6 col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="url">URL</label>
                                            <input type="text" name="url" value="<?= set_value('url'); ?>" id="url" class="form-control form-control-sm" placeholder="URL" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-6 col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" name="image" id="image" class="form-control form-control-sm" accept="image/*" title="Image" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1 pb-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-xs">
                                            Save
                                        </button>
                                        <button type="reset" class="btn btn-danger btn-xs">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Slider List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="SliderTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>URL</th>
                                            <th>Image</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="row_position">
                                        <?php
                                            if (isset($slider) && $slider != '') {
                                                $n = 0;
                                                foreach ($slider as $key => $data) {
                                                    $n++;
                                        ?>
                                        <tr id="<?= $data->id; ?>">
                                            <td><span><?= $n; ?></span></td>
                                            <td><?= $data->Title; ?></td>
                                            <td>
                                                <a target="_blank" href="<?= base_url() . $data->url; ?>">
                                                    <?= $data->url; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <img src='<?= base_url() . "uploads/slider/" . $data->image; ?>' width="100px" height="40px" class="photo" />
                                            </td>
                                            <td><?= $data->created; ?></td>
                                            <td><?= $data->updated; ?></td>
                                            <td>
                                                <a href="<?= base_url('slider/edit/') . $data->id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('slider/delete/') . $data->id; ?>" onclick="return confirm('Are you sure you want to delete the Slider?');" class="btn btn-danger btn-xs">
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
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#SliderTable").DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'pageLength']
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

    function updateOrder(data) {
        $.ajax({
            url     :   "<?= base_url(); ?>slider/changeSliderPosition",
            type    :   "POST",
            data    :   {position: data},
            success :   function(data) {
                            toastr.success('Your Change Successfully Saved.');
                        }
        });
    }
</script>