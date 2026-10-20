<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header pb-8 pt-5 pt-md-8" style="background: #2C4668;">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Loan Category</h2>
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="row container">
                                    <?php echo form_open_multipart('loan_category', ['id' => "loan_category_add"]); ?>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="category_name">Loan Category Name:</label>
                                                <input type="text" class="form-control" name="title" id="title" placeholder="Category Name" value="<?= set_value('title') ?>" required="true">
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?php echo form_error('title'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="image">Image:</label>
                                                <input type="file" class="form-control" accept="image/*" name="image" id="file" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?php echo form_error('image'); ?>
                                                </span>
                                                <!-- <p><label for="file" style="cursor: pointer;">Upload Image</label></p>
                                                <p><img id="output" width="200" /></p> -->
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea id="YourEditor" name="description"><?= set_value('description') ?></textarea>
                                            </div>
                                            <script>
                                                CKEDITOR.replace('YourEditor');
                                            </script>
                                            <span class="col-sm-4 control-label text-danger">
                                                <?php echo form_error('description'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-default">
                                            Save
                                        </button>
                                        <input type="reset" class="btn btn-secondary" placeholder="Reset">
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mb-9" style="margin-top:100px;">

                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Loan Category List</label>
                            <div class="row mt-5">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush" style="width: 100%" id="dataTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" width="2%">#</th>
                                            <th>Title</th>
                                            <th>Photo</th>
                                            <th>Description</th>
                                            <th><span class="text-success">Active</span>/<span class="text-danger">Deactive</span></th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($loan_category) && !empty($loan_category)) {
                                            foreach ($loan_category as  $key => $cat) {
                                        ?>
                                                <tr>
                                                    <td><span><?= $key + 1; ?></span> </td>
                                                    <td><?= $cat->loan_name ?></td>
                                                    <td><img style="height: 100px;" src="<?= base_url() ?>uploads/loan_category/<?= $cat->image ?>"></td>
                                                    <td><?= $cat->description ?></td>

                                                    <td>
                                                        <?php
                                                        if ($cat->status != 1) {
                                                            echo '<div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onchange="changeStatus(' . $cat->id . ',1)">
                                                            </div>';
                                                        } else {
                                                            echo ' <div class="form-check form-switch">
                                                             <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onchange="changeStatus(' . $cat->id . ',0)" checked>
                                                              <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                                             </div>';
                                                        }
                                                        ?>

                                                    </td>
                                                    <td class="text-right">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a href="<?= base_url('loan_category_edit/') . $cat->id ?>" class="dropdown-item cp">Edit</a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                        <?php }
                                        }

                                        ?>


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

<script>
    var loadFile = function(event) {
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    };

    function changeStatus(table_id, status) {
        $.ajax({
            url: "<?= base_url() ?>loan_change_status",
            type: "POST",
            data: {
                table_id: table_id,
                status: status,
            },
            success: function(response) {
                response = JSON.parse(response);
                console.log(response, "responce");
                if (response.status === true) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
</script>