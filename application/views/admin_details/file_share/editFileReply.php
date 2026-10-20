<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-md-12 col-xl-12 col-lg-12">

                    <div class="header bg-blue pb-8 pt-5 pt-md-8">



                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Replying</h2>
                                <!-- Card stats -->
                                <div class="row">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                            <?php echo $this->session->flashdata('msg'); ?>
                                        </div>
                                    <?php

                                    }
                                    ?>

                                    <form method="POST" action="<?= base_url('file_sharing/updateFileChat'); ?>" enctype="multipart/form-data" class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <?php
                                            // date_default_timezone_set('Asia/Kolkata');

                                            ?>
                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <input id="createdAt" name="date" type="text" class="form-control" value="<?= $result->date; ?>" readonly required="true">

                                                </div>
                                            </div>
                                            <input type="hidden" name="id" value="<?= $result->id ?>">
                                            <input type="hidden" name="file_sharing_id" value="<?= $result->file_sharing_id ?>">

                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6 field_wrapper">
                                                <div class="form-group">
                                                    <label for="wallet">Wallet Balance:</label>
                                                    <input type="number" min="0" autocomplete="off" readonly class="form-control" value="<?= get_title('users', ['id' => $result->user_id], 'amount') ?>" name="amount">

                                                </div>
                                            </div>
                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6 field_wrapper">
                                                <div class="form-group">

                                                    <label for="amount">Charge Amount</label>
                                                    <input type="hidden" name="user_id" value="<?= $result->user_id ?>">
                                                    <input type="number" min="0" autocomplete="off" readonly class="form-control" value="<?= get_title('file_sharing', ['id' => $result->file_sharing_id], 'amount') ?>" name="amount">

                                                </div>
                                            </div>

                                            <div class="col-md-6 col-md-6 col-sm-6 col-lg-6 field_wrapper">

                                                <div class="form-group">
                                                    <label for="file">File</label>
                                                    <input type="file" class="form-control" name="field_name[]" multiple="" />
                                                    <span class="text-danger">


                                                    </span>

                                                </div>
                                            </div>
                                                                <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                                <div class="form-qty">
                                                    <label for="remark">Qty</label>
                                                    <input type="number" class="form-control" name="qty" id="qty" value="<?= $result->qty ?>" />

                                                </div>
                                            </div>
                                            <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                                <div class="form-group">
                                                    <label for="remark">Remark</label>
                                                    <textarea id="name" type="text" class="form-control" name="name" required="true"><?= $result->msg ?></textarea>
                                                    <script>
														CKEDITOR.replace('name');
													</script>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6 col-md-6 col-sm-6 col-lg-6">
                                                <div class="form-group">
                                                    <a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field"><span style="font-size:20px;">+</span></a>


                                                </div>
                                            </div> -->


                                        </div>

                                        <div class="row mt-5" style="margin-bottom: 32px;">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    Send
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
                <style>
                    .pata_nahi_bhai {
                        position: fixed;
                        left: 0;
                        bottom: 0;
                        width: 100%;
                        background-color: black;
                        color: white;
                        text-align: center;
                    }
                </style>
                <div class="col-md-12 pata_nahi_bhai">
                    <h4 class="text-danger text-center">Note:Only 20 Files can be uploaded and same type of files in single time.</h4>
                </div>


            </div>

        </div>
    </div>

</div>