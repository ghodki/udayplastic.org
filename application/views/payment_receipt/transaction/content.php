            <style type="text/css">
                .modal-content {
                    top: -150px;
                }
                .form-group {
                    margin-bottom: 0rem;
                }
            </style>
            
            <form method="POST" action="<?= base_url(); ?>paymentReceipt/sendmail" enctype="multipart/form-data" novalidate="novalidate">
                <input type="hidden" name="id" id="id" value="<?= $id; ?>" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from">From <span class="text-danger">*</span></label>
                                <input type="email" name="from" value="<?= set_value('from', $maildata->mailfrom); ?>" id="from" class="form-control" placeholder="From" required=""  />
                                <span class="col-sm-4 control-label text-danger">
                                    <?= form_error('from'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to">To <span class="text-danger">*</span></label>
                                <input type="email" name="to" value="<?= set_value('to', get_title('payment_receipt_party', ['id' => $transactioninfo->party_id], 'email')); ?>" id="to" class="form-control" placeholder="To" required=""  />
                                <span class="col-sm-4 control-label text-danger">
                                    <?= form_error('to'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" value="<?= set_value('subject', $maildata->subject); ?>" id="subject" class="form-control" placeholder="Subject" required=""  />
                                <span class="col-sm-4 control-label text-danger">
                                    <?= form_error('subject'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="body">Body <span class="text-danger">*</span></label>
                                <textarea name="body" id="body" class="form-control" placeholder="Body" required=""><?= set_value('body', $maildata->body); ?></textarea>
                                <span class="col-sm-4 control-label text-danger">
                                    <?= form_error('body'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-send"></i> Send
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="Close();">
                        <span aria-hidden="true">×</span> Close
                    </button>
                </div>
            </form>
            
            <script type="text/javascript">
                CKEDITOR.replace('body');
            </script>