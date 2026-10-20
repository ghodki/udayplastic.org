<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-1">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
                        <div class="header-body">
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Create Company</h2>
                            <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                <strong><?= $this->session->flashdata('msg'); ?></strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php } ?>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <form action="<?= base_url(); ?>paymentReceipt" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" name="company_name" value="<?= set_value('company_name'); ?>" id="company_name" class="form-control" placeholder="Company Name" required="true" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('company_name'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="number">Tel Number</label>
                                                <input type="tel" name="number" value="<?= set_value('number'); ?>" id="number" class="form-control" placeholder="Tel Number" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('number'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control" placeholder="Email" required="true" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('email'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="pan">PAN</label>
                                                <input type="text" name="pan" value="<?= set_value('pan'); ?>" id="pan" class="form-control" placeholder="PAN Number" minlength="10" maxlength="10" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="address">Address <span class="text-danger">*</span></label>
                                                <textarea name="address" id="address" class="form-control" placeholder="Address" required="true"><?= set_value('address'); ?></textarea>
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('address'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="pin">Pin <span class="text-danger">*</span></label>
                                                <input type="tel" name="pin" value="<?= set_value('pin'); ?>" id="pin" class="form-control" placeholder="Pin" required="true" minlength="6" maxlength="6" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('pin'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-default">Save</button>
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card shadow mt-2">
                                <div class="card-header border-0">
                                    <label style="color: #000000;">
                                        Payment Receipt List
                                    </label>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped align-items-center table-flush" style="width: 100%;" id="dataTable">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Company Name</th>
                                                    <th>Email</th>
                                                    <th>PAN</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-dark">
                                                <?php
                                                    if (isset($payment_receipt) && $payment_receipt != '') {
                                                        foreach ($payment_receipt as  $key => $value) {
                                                ?>
                                                <tr>
                                                    <td><span><?= $key + 1; ?></span></td>
                                                    <td><?= $value->created_at; ?></td>
                                                    <td>
                                                        <a href="<?= base_url('paymentReceipt/viewclient/') . $value->id; ?>" class="active">
                                                            <?= $value->company_name; ?>(<?= $value->number; ?>)
                                                        </a>
                                                    </td>
                                                    <td><?= $value->email; ?></td>
                                                    <td><?= $value->pan; ?></td>
                                                    <td>
                                                        <a href="<?= base_url('paymentReceipt/edit/') . $value->id; ?>">
                                                            <i class="fa fa-edit" style="font-size: 24px;"></i>
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