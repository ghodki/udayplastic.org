                                <form action="<?= base_url(); ?>product/synchronizeHSN" method="POST" autocomplete="off">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Product Name</th>
                                                <th>Unit</th>
                                                <th>Tax</th>
                                                <th>HSN <small>(Product)</small></th>
                                                <th>HSN <small>(Sale)</small></th>
                                                <th>HSN <small>(Purchase)</small></th>
                                                <th>HSN <small>(Estimate)</small></th>
                                                <th>HSN <small>(Estimate P)</small></th>
                                            </tr>
                                        </thead>
                                        <?php if ($this->session->userdata('BranchRole')) { ?>
                                        <tbody>
                                            <?php
                                                if (!empty($products)) {
                                                    $n = 0;
                                                    $CompBranchId = $this->session->userdata('CompBranchId');
                                                    foreach ($products as $key => $data) {
                                                        $n++;
                                                        $salehsn = get_title('invoice_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Sale', 'product_id' => $data['id']], 'hsn');
                                                        $purchasehsn = get_title('invoice_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Purchase', 'product_id' => $data['id']], 'hsn');
                                                        $estimatehsn = get_title('estimate_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Sale', 'product_id' => $data['id']], 'hsn');
                                                        $estimate_purchasae_hsn = get_title('estimate_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Purchase', 'product_id' => $data['id']], 'hsn');

                                                        $salecount = $this->user_model->RowCount('invoice_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Sale', 'product_id' => $data['id']]);
                                                        $purchasecount = $this->user_model->RowCount('invoice_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Purchase', 'product_id' => $data['id']]);
                                                        $estimatecount = $this->user_model->RowCount('estimate_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Sale', 'product_id' => $data['id']]);
                                                        $estimate_purchasae_count = $this->user_model->RowCount('estimate_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Purchase', 'product_id' => $data['id']]);
                                            ?>
                                            <input type="hidden" name="product_id[]" value="<?= $data['id']; ?>" />
                                            <input type="hidden" name="sale_product_id[]" value="<?= get_title('invoice_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Sale', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="purchase_product_id[]" value="<?= get_title('invoice_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Purchase', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="estimate_product_id[]" value="<?= get_title('estimate_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Sale', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="estimate_purchase_product_id[]" value="<?= get_title('estimate_product', ['CompanyBranchId' => $CompBranchId, 'type' => 'Purchase', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="product_hsn[]" value="<?= $data['hsn']; ?>" />
                                            <tr>
                                                <td><?= $n; ?></td>
                                                <td><?= $data['p_name']; ?></td>
                                                <td><?= $data['unit_name']; ?></td>
                                                <td><?= $data['tax_qty']; ?> <i class="fa fa-percent"></i></td>
                                                <td><?= $data['hsn']; ?></td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $salehsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $salehsn; ?>
                                                    </span>
                                                    <?php if ($salecount > 0) { ?>
                                                    <small>(<?= $salecount; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $purchasehsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $purchasehsn; ?>
                                                    </span>
                                                    <?php if ($purchasecount > 0) { ?>
                                                    <small>(<?= $purchasecount; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $estimatehsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $estimatehsn; ?>
                                                    </span>
                                                    <?php if ($estimatecount > 0) { ?>
                                                    <small>(<?= $estimatecount; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $estimate_purchase_hsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $estimate_purchase_hsn; ?>
                                                    </span>
                                                    <?php if ($estimate_purchase_count > 0) { ?>
                                                    <small>(<?= $estimate_purchase_count; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                        <?php } else { ?>
                                        <tbody>
                                            <?php
                                                if (!empty($products)) {
                                                    $n = 0;
                                                    foreach ($products as $key => $data) {
                                                        $n++;
                                                        $salehsn = get_title('invoice_product', ['type' => 'Sale', 'product_id' => $data['id']], 'hsn');
                                                        $purchasehsn = get_title('invoice_product', ['type' => 'Purchase', 'product_id' => $data['id']], 'hsn');
                                                        $estimatehsn = get_title('estimate_product', ['type' => 'Sale', 'product_id' => $data['id']], 'hsn');
                                                        $estimate_purchasae_hsn = get_title('estimate_product', ['type' => 'Purchase', 'product_id' => $data['id']], 'hsn');

                                                        $salecount = $this->user_model->RowCount('invoice_product', ['type' => 'Sale', 'product_id' => $data['id']]);
                                                        $purchasecount = $this->user_model->RowCount('invoice_product', ['type' => 'Purchase', 'product_id' => $data['id']]);
                                                        $estimatecount = $this->user_model->RowCount('estimate_product', ['type' => 'Sale', 'product_id' => $data['id']]);
                                                        $estimate_purchasae_count = $this->user_model->RowCount('estimate_product', ['type' => 'Purchase', 'product_id' => $data['id']]);
                                            ?>
                                            <input type="hidden" name="product_id[]" value="<?= $data['id']; ?>" />
                                            <input type="hidden" name="sale_product_id[]" value="<?= get_title('invoice_product', ['type' => 'Sale', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="purchase_product_id[]" value="<?= get_title('invoice_product', ['type' => 'Purchase', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="estimate_product_id[]" value="<?= get_title('estimate_product', ['type' => 'Sale', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="estimate_purchase_product_id[]" value="<?= get_title('estimate_product', ['type' => 'Purchase', 'product_id' => $data['id']], 'product_id'); ?>" />
                                            <input type="hidden" name="product_hsn[]" value="<?= $data['hsn']; ?>" />
                                            <tr>
                                                <td><?= $n; ?></td>
                                                <td><?= $data['p_name']; ?></td>
                                                <td><?= $data['unit_name']; ?></td>
                                                <td><?= $data['tax_qty']; ?> <i class="fa fa-percent"></i></td>
                                                <td><?= $data['hsn']; ?></td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $salehsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $salehsn; ?>
                                                    </span>
                                                    <?php if ($salecount > 0) { ?>
                                                    <small>(<?= $salecount; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $purchasehsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $purchasehsn; ?>
                                                    </span>
                                                    <?php if ($purchasecount > 0) { ?>
                                                    <small>(<?= $purchasecount; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $estimatehsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $estimatehsn; ?>
                                                    </span>
                                                    <?php if ($estimatecount > 0) { ?>
                                                    <small>(<?= $estimatecount; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span class="<?= $data['hsn'] == $estimate_purchase_hsn ? 'text-success' : 'text-danger'; ?>">
                                                        <?= $estimate_purchase_hsn; ?>
                                                    </span>
                                                    <?php if ($estimate_purchase_count > 0) { ?>
                                                    <small>(<?= $estimate_purchase_count; ?>)</small>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>                                        
                                        <?php } ?>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7"><?= $this->ajax_pagination_dashboard->create_links(); ?></td>
                                                <td colspan="2">
                                                    <button type="submit" class="btn btn-warning">Synchronize HSN</button>
                                                    <a href="<?= base_url(); ?>/product" class="btn btn-danger">Cancel</a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>