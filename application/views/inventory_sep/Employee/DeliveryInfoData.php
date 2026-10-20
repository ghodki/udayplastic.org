<div class="modal-header">
    <h5 class="modal-title">Delivery Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed request-data">
                    <tr>
                        <th>Invoice Date</th>
                        <td><?= date('d-m-Y', strtotime($DeliveryInfo->invoiceDate)); ?></td>
                        <th>Invoice Number</th>
                        <td><?= $DeliveryInfo->invoiceNumber; ?></td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td><?= $DeliveryInfo->type; ?></td>
                        <th>Party Name</th>
                        <td><?= get_title('parties', ['id' => $DeliveryInfo->customerId], 'name'); ?></td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td><i class="fa fa-inr"></i> <?= number_format($DeliveryInfo->total, 2); ?></td>
                        <th>Salesman</th>
                        <td><?= get_title('employees', ['Id' => get_title('parties', ['id' => $DeliveryInfo->customerId], 'SalesmanId')], 'Name'); ?></td>
                    </tr>
                    <tr>
                        <th>Delivery Date</th>
                        <td><?= $DeliveryInfo->delivery_date ? date('d-m-Y', strtotime($DeliveryInfo->delivery_date)) : ""; ?></td>
                        <th>Delivered By</th>
                        <td><?= get_title('employees', ['Id' => $DeliveryInfo->DeliveredBy], 'Name'); ?></td>
                    </tr>
                </table>
            </div>
            <hr />
            <?php if ($DeliveryInfo->delivery_status == 0) { ?>
            <form action="<?= base_url(); ?>Employee/UpdateDelivery" method="POST">
                <input type="hidden" name="InvoiceId" value="<?= set_value('InvoiceId', $DeliveryInfo->id); ?>" id="InvoiceId" />
                <input type="hidden" name="InvoiceType" value="<?= set_value('InvoiceType', $DeliveryInfo->type); ?>" id="InvoiceType" />
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="form-group">
                            <label for="DeliveryDate">Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" name="DeliveryDate" value="<?= set_value('DeliveryDate', date('Y-m-d')); ?>" id="DeliveryDate" class="form-control form-control-sm" title="Delivery Date" required="" />
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="form-group">
                            <label for="DeliveredBy">Delivered By <span class="text-danger">*</span></label>
                            <select name="DeliveredBy" id="DeliveredBy" class="form-control form-control-sm" title="Select Delivered By" required="">
                                <option selected="" value="" disabled="">Select Delivered By</option>
                                <?php if (!empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                <option value="<?= $Salesman->Id; ?>" <?= $DeliveryInfo->DeliveredBy == $Salesman->Id ? "selected" : ""; ?> <?= set_select('DeliveredBy', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                        <div class="form-group">
                            <label for="DeliveryStatus">Delivery Status <span class="text-danger">*</span></label>
                            <br />
                            <input type="radio" name="DeliveryStatus" value="1" <?= set_radio('DeliveryStatus', 1); ?> id="Yes" <?= $DeliveryInfo->DeliveryStatus == 1 ? "checked" : ""; ?> title="Yes" required="" />
                            <label>Yes</label>
                            <input type="radio" name="DeliveryStatus" value="0" <?= set_radio('DeliveryStatus', 2); ?> id="No" <?= $DeliveryInfo->DeliveryStatus == 0 ? "checked" : ""; ?> class="ml-2" title="No" required="" />
                            <label>No</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 offset-4">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Submit" />
                        </div>
                    </div>
                </div>
            </form>
            <?php } elseif ($DeliveryInfo->delivery_status == 1) { ?>
            <label class="text-warning text-lg text-center">Delivery Status Updated.</label>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>