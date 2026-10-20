<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style>
        .control-label {
            color: red;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header  pb-8 pt-5 pt-md-8" style="background: #2C4668;">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Blog</h2>
                                <!-- Card stats -->
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php
                                    unset($_SESSION['msg']);
                                }

                                // die;
                                ?>
                                <div class="row">

                                    <form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>blog" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <input id="name" name="name" placeholder="Blog Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('name'); ?>
                                                </span>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-6">
                                                <input id="image" name="image" type="file" class="form-control" required="true">
                                                <span class="col-sm-4 control-label">
                                                    <?php echo $error ?>
                                                </span>
                                            </div>

                                            <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                                <textarea id="description" name="description" placeholder="Description" type="text" class="form-control"><?= set_value('description') ?></textarea>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('description'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    Save
                                                </button>
                                                <a href="#" type="reset" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid mb-9" style="margin-top:100px;">

                            <div class="col">
                                <div class="card shadow">
                                    <div class="card-header border-0">
                                        <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Blog List</label>
                                        <div class="table-responsive">
                                            <table class="table table-striped align-items-center table-flush" style="width: 100%" id="dataTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Blog Name</th>
                                                        <th>Image</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-dark">
                                                    <?php
                                                    if (isset($blog) && $blog != '') {
                                                        foreach ($blog as  $key => $value) {
                                                            $date = $value->created_at;
                                                            $newDate = date("d-m-Y", strtotime($date));
                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <span><?= $key + 1; ?></span>
                                                                </td>
                                                                <td><?= $newDate; ?></td>
                                                                <td><?= $value->blog_name ?></td>
                                                                <td><img src="<?= base_url() ?>uploads/blog/<?= $value->image ?>" height="100px;"></td>
                                                                <td class="text-right">
                                                                    <div class="dropdown">
                                                                        <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                            <a href="<?= base_url('edit_blog/') . $value->id ?>" class="dropdown-item cp">Edit</a>
                                                                            <a href="<?= base_url('delete_blog/') . $value->id ?>" onclick="if (!confirm('Are you sure?')) { return false }" class="dropdown-item cp">Delete</a>
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
        </div>
    </div>
</div>