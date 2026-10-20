<div class="modal-header">
    <h5 class="modal-title">Sale Invoice History</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($invoiceHistory)): ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Invoice Edit History</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($invoiceHistory as $record): ?>
                        <div class="border rounded p-3 mb-3">
                            <h6 class="text-info">
                                <i class="fas fa-user-edit"></i> Edited by: <?= htmlspecialchars($record['editedBy']) ?> 
                            </h6>
                            <p class="text-muted">
                                <i class="far fa-clock"></i> <?= date("d M Y, h:i A", strtotime($record['editedDateTime'])) ?>
                            </p>

                            <!-- Invoice Changes -->
                            <?php if ($record['saleInvoiceChanges']): ?>
                                <h6 class="text-warning"><i class="fas fa-file-invoice"></i> Invoice Changes</h6>
                                <ul class="list-group mb-2">
                                    <?php 
                                        $invoice_changes = json_decode($record['saleInvoiceChanges'], true);
                                        foreach ($invoice_changes as $field => $values):
                                            $skipFields = ['customerId', 'customerCity', 'customerState'];
                                            if (in_array($field, $skipFields)) {
                                                continue;
                                            }
                                    ?>
                                    <li class="list-group-item">
                                        <strong><?= ucfirst($field) ?>:</strong>
                                        <?php switch ($field) { case 'parties': ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-danger">Column</th>
                                                                <th class="text-danger">Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $oldCustomer = json_decode($values['old'], true); 
                                                                foreach($oldCustomer as $column => $value) {
                                                                    $skipColumn = ['customer_id', 'state_id', 'city_id'];
                                                                    if (in_array($column, $skipColumn)) {
                                                                        continue;
                                                                    }
                                                            ?>
                                                            <tr>
                                                                <td class="text-danger"><?= htmlspecialchars($column); ?></td>
                                                                <td class="text-danger"><?= htmlspecialchars($value);  ?></td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-success">Column</th>
                                                                <th class="text-success">Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $newCustomer = json_decode($values['new'], true); 
                                                                foreach ($newCustomer as $column => $value) {
                                                                    $skipColumn = ['customer_id', 'state_id', 'city_id'];
                                                                    if (in_array($column, $skipColumn)) {
                                                                        continue;
                                                                    }
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><?= htmlspecialchars($column); ?></td>
                                                                <td class="text-success"><?= htmlspecialchars($value);  ?></td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php break; case 'shippingInfo': ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-danger">Column</th>
                                                                <th class="text-danger">Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $oldShippingInfo = json_decode($values['old'], true); 
                                                                foreach($oldShippingInfo as $column => $value) {
                                                                    if ($column == 'shippingState') {
                                                                        $value = get_title('state_list', ['id' => $value], 'state');
                                                                    }
                                                            ?>
                                                            <tr>
                                                                <td class="text-danger"><?= htmlspecialchars($column); ?></td>
                                                                <td class="text-danger"><?= htmlspecialchars($value);  ?></td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-success">Column</th>
                                                                <th class="text-success">Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $newShippingInfo = json_decode($values['new'], true); 
                                                                foreach ($newShippingInfo as $column => $value) {
                                                                    if ($column == 'shippingState') {
                                                                        $value = get_title('state_list', ['id' => $value], 'state');
                                                                    }
                                                            ?>
                                                            <tr>
                                                                <td class="text-success"><?= htmlspecialchars($column); ?></td>
                                                                <td class="text-success"><?= htmlspecialchars($value);  ?></td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>                                                
                                                </div>
                                            </div>
                                        </div>                                        
                                        <?php break; case 'product': ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-danger">Sr</th>
                                                                <th class="text-danger">Column</th>
                                                                <th class="text-danger">Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $oldProduct = json_decode($values['old'], true); 
                                                                foreach($oldProduct as $key => $product) {
                                                                    $oldSr = 0;
                                                                    foreach ($product as $column => $value) {
                                                                        $skipColumn = ['product', 'amount'];
                                                                        if (in_array($column, $skipColumn)) {
                                                                            continue;
                                                                        }

                                                                        if ($column == 'warehouse') {
                                                                            $warehouse = $this->db->select('name, location')->where('id', $value)->get('warehouse')->row();
                                                                            $value = $warehouse->name . ' - ' . $warehouse->location;
                                                                        }

                                                                        $oldSr++;
                                                                        $borderClass = ($oldSr === 1) ? 'border-top' : '';
                                                            ?>
                                                            <tr class="<?= $borderClass; ?>">
                                                                <td class="text-danger"><?= $oldSr; ?></td>
                                                                <td class="text-danger"><?= htmlspecialchars($column); ?></td>
                                                                <td class="text-danger"><?= htmlspecialchars($value);  ?></td>
                                                            </tr>
                                                            <?php } } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-success">Sr</th>
                                                                <th class="text-success">Column</th>
                                                                <th class="text-success">Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $newProduct = json_decode($values['new'], true); 
                                                                foreach($newProduct as $key => $product) {
                                                                    $newSr = 0;
                                                                    foreach ($product as $column => $value) {
                                                                        $skipColumn = ['product', 'amount'];
                                                                        if (in_array($column, $skipColumn)) {
                                                                            continue;
                                                                        }

                                                                        if ($column == 'warehouse') {
                                                                            $warehouse = $this->db->select('name, location')->where('id', $value)->get('warehouse')->row();
                                                                            $value = $warehouse->name . ' - ' . $warehouse->location;
                                                                        }

                                                                        $newSr++;
                                                                        $borderClass = ($newSr === 1) ? 'border-top' : '';
                                                            ?>
                                                            <tr class="<?= $borderClass; ?>">
                                                                <td class="text-success"><?= $newSr; ?></td>
                                                                <td class="text-success"><?= htmlspecialchars($column); ?></td>
                                                                <td class="text-success"><?= htmlspecialchars($value);  ?></td>
                                                            </tr>
                                                            <?php } } ?>
                                                        </tbody>
                                                    </table>                                                
                                                </div>
                                            </div>
                                        </div>
                                        <?php break; case 'SalesmanId': ?>
                                        <span class="text-danger">
                                            <?= get_title('employees', ['Id' => $values['old']], 'Name'); ?>
                                        </span>
                                        <i class="fa fa-arrow-right"></i>
                                        <span class="text-success">
                                            <?= get_title('employees', ['Id' => $values['new']], 'Name'); ?>
                                        </span>
                                        <?php break; default: ?>
                                        <span class="text-danger"><?= htmlspecialchars($values['old']) ?></span>
                                        <i class="fa fa-arrow-right"></i>
                                        <span class="text-success"><?= htmlspecialchars($values['new']) ?></span>
                                        <?php } ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-info">No edit history available for this invoice.</div>
            <?php endif; ?>            
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>