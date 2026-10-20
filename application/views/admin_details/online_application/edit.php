<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Update Online Application</h2>
                                <!-- Card stats -->
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                    </div>
                                <?php

                                }
                                ?>

                                <?php echo form_open_multipart('online_application/edit/' . $result->id); ?>
                                <div class="row">
                                    <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="<?= $result->name ?>" placeholder="Name" required name="name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="link" value="<?= $result->link ?>" required name="link" />
                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-default">
                                            Update
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