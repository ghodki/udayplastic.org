<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-blue pb-8 pt-5 pt-md-8">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                        <?php

                        }
                        ?>
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">UPDATE NOTICE</h2>
                                <!-- Card stats -->
                                <div class="row">

                                    <form method="POST" action="<?= base_url('notice/update'); ?>" class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                                <input type="hidden" name="id" id="id" value="<?= $notice->id ?>">
                                            </div>
                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                <label for="usertype" class="text-dark">Assign To</label>
                                                <select class="form-control" name="usertype" id="usertype" required>
                                                    <option value="users" <?= $notice->usertype == 'users' ? 'selected' : '' ?>>Users</option>
                                                    <?php
                                                    $role = $this->session->userdata('roles');
                                                    if ($role == 1) {
                                                    ?>
                                                        <option value="subadmin" <?= $notice->usertype == 'subadmin' ? 'selected' : '' ?>>Subadmin</option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                            </div>
                                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                            </div>
                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="name" class="text-dark">Notice</label>
                                                    <textarea id="name" type="text" class="form-control" placeholder="TEXT" name="name" required="true"><?= $notice->name ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                            </div>

                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    UPDATE
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
            </div>
        </div>
    </div>
</div>