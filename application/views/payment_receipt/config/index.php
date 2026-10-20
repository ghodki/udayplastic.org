<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-12 col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Receipt Config
                        </h5>
                        <div class="align-items-center">
                            <div class="ps-3">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?> alert-dismissible fade show" role="alert">
                                    <strong><?php echo $this->session->flashdata('msg'); ?></strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php } ?>
                                
                                <form action="<?= base_url(); ?>paymentReceipt/config" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="logo">Logo</label>
                                                <input type="file" name="logo" id="logo" class="form-control" title="Logo" accept="image/*" />
                                                <input type="hidden" name="oldlogo" id="oldlogo" value="<?= $result->logo; ?>" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('logo'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2">
                                            <?php if (!empty($result->logo)) { ?>
                                            <img src="<?= base_url(); ?>uploads/complogo/<?= $result->logo; ?>" alt="logo" style="border-radius: 25px; width: 100px;" />
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="signature">Signature</label>
                                                <input type="file" name="signature" id="signature" class="form-control" title="Signature" accept="image/*" />
                                                <input type="hidden" name="oldsignature" id="oldsignature" value="<?= $result->signature; ?>" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('signature'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2">
                                            <?php if (!empty($result->signature)) { ?>
                                            <img src="<?= base_url(); ?>uploads/signature/<?= $result->signature; ?>" alt="signature" style="border-radius: 25px; width: 100px;" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                    
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <br />
                                                <input type="radio" name="address" value="Yes<?= set_radio('address', 'Yes'); ?>" id="yes" <?php if ($result->address == "Yes") { echo "checked"; } ?> title="Yes" />
                                                Yes
                                                <input type="radio" name="address" value="No<?= set_radio('address', 'No'); ?>" id="no" <?php if ($result->address == "No") { echo "checked"; } ?> title="No" class="ml-5" />
                                                No
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="receipt_color">
                                                    Receipt Number Color
                                                    <span class="text-danger" style="font-size: 12px;">(Click and pick)</span>
                                                </label>
                                                <input type="color" name="receipt_color" value="<?= htmlspecialchars($result->receipt_color); ?>" id="receipt_color" class="form-control" title="Receipt Number Color" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="mailfrom">Mail Send From</label>
                                                <input type="email" name="mailfrom" value="<?= $result->mailfrom; ?>" id="mailfrom" class="form-control" placeholder="Mail Send From" />
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12 col-lg-8">
                                            <div class="form-group">
                                                <label for="subject">Mail Subject</label>
                                                <input type="text" name="subject" value="<?= $result->subject; ?>" id="subject" class="form-control" placeholder="Mail Subject" />
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="body">Mail Message(Body)</label>
                                                <textarea name="body" id="body" class="form-control" placeholder="Mail Message(Body)"><?= $result->body; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12 col-lg-2">
                                            <div class="form-group">
                                                <label for="sms_status">SMS Status</label>
                                                <select name="sms_status" id="sms_status" class="form-control">
                                                    <option value="Enable" <?= set_value('sms_status', $result->sms_status) == 'Enable' ? 'selected' : 'selected'; ?>>Enable</option>
                                                    <option value="Disable" <?= set_value('sms_status', $result->sms_status) == 'Disable' ? 'selected' : ''; ?>>Disable</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-lg-2">
                                            <div class="form-group">
                                                <label for="sms_sender_id">SMS Header <span style="font-size: 12px;">(Sender Id)</span></label>
                                                <input type="text" name="sms_sender_id" value="<?= $result->sms_sender_id; ?>" id="sms_sender_id" class="form-control" placeholder="SMS Header(Sender Id)" />
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12 col-lg-8">
                                            <div class="form-group">
                                                <label for="sms_body">SMS Body (Template)</label>
                                                <br />
                                                <input type="radio" name="sms_body" value="temp_1<?= set_radio('sms_body', 'temp_1'); ?>" id="sms_body" <?php if ($result->sms_body == "temp_1") { echo "checked"; } ?> title="SMS Body (Template)" />
                                                Dear User, Thank you for Donate {#var#} Rs {#var#} Download receipts {#var#} Powered By Umatic
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr />
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-warning">Update</button>
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<script type="text/javascript">
    CKEDITOR.replace('body');
</script>