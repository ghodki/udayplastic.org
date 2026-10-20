<div class="modal-header">
    <h5 class="modal-title">Invoice Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Customer Name</th>
                            <th>Invoice Date</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($invoiceInfo) && !empty($invoiceInfo)) {
                                $sr = 0;
                                foreach ($invoiceInfo as $key => $info) {
                                    $sr++;
                                    $customerName = get_title('parties', ['id' => $info->customerId], 'name');
                        ?>
                        <tr>
                            <td><?= $sr; ?></td>
                            <td><?= $customerName; ?></td>
                            <td><?= date('d-m-Y', strtotime($info->invoiceDate)); ?></td>
                            <td><?= $info->type; ?></td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>