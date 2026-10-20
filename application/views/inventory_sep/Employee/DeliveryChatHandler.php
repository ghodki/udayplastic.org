<div class="modal-header">
    <h5 class="modal-title" id="title">Delivery Chat</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <?php if (empty($SaleInfo)) { ?>
    <div class="alert alert-warning mb-0">Invoice not found.</div>
    <?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <div class="card card-widget">
                    <div class="card-header">
                        <div class="user-block d-flex">
                            <span class="username">
                                <a href="javascript:void(0);" class="mr-5">
                                    <?= !empty($SaleInfo->customerId) ? get_title('parties', ['id' => $SaleInfo->customerId], 'name') : ''; ?>
                                </a>
                            </span>
                            <span class="username">
                                <?= $SaleInfo->invoiceNumber ?? ''; ?>
                            </span>
                            <span class="username">
                                <i class="fa fa-calendar"></i> <?= !empty($SaleInfo->invoiceDate) ? date('d-m-Y', strtotime($SaleInfo->invoiceDate)) : ''; ?> 
                                - 
                                <i class="fa fa-inr"></i> <?= number_format((float) ($SaleInfo->total ?? 0), 2); ?>
                            </span>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-footer card-comments" style="height: 250px; overflow-y: scroll;"></div>
                    <!-- /.card-footer -->
                    <div class="card-footer">
                        <form id="ChatForm" method="POST" onsubmit="onChatFormSubmitHandler(event);">
                            <input type="hidden" name="InvoiceId" value="<?= $SaleInfo->id ?? ''; ?>" id="InvoiceId" />
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <textarea name="Chat" id="Chat" class="form-control" placeholder="Enter massage"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-success btn-sm" value="Send" id="submitButton" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-footer -->
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>