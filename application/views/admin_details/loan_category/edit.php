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
                                    <?php echo form_open_multipart('loan_category_edit/' . $result->id, ['id' => "loan_category_updated"]); ?>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="category_name">Loan Category Name:</label>
                                                <input type="text" class="form-control" name="title" id="title" placeholder="Category Name" value="<?= set_value('title', $result->loan_name) ?>" required="true">
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?php echo form_error('title'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="image">Image:</label>
                                                <input type="file" class="form-control" accept="image/*" name="image" id="file" />
                                                <span><img src="<?= base_url() ?>uploads/loan_category/<?= $result->image ?>" style="height:100px;"></span>
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?php echo form_error('image'); ?>
                                                </span>

                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea id="YourEditor" name="description"><?= set_value('description', $result->description) ?></textarea>
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
<script>
    var loadFile = function(event) {
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    };
</script>