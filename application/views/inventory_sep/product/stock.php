<h6 class="text-center">Stock Details for <b><?= $result->p_name ?></b></h6>
<div class="row">
    <div class="col-xs-6 col-md-6 col-lg-3 bg-navy">
        Opening Stock :
        <span id="opening_stock" class="text-white">
            <?php
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $Where = ['user_id' => $UserId, 'comp_id' => $CompId];
                $EstimateStockWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EstimateStockWhere['CompanyBranchId'] = $CompBranchId;
                }
                $EstimateStock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');

                echo number_format($result->qty, 2); 
            ?>
        </span>
    </div>
    <div class="col-xs-6 col-md-6 col-lg-3 text-sm-right bg-purple">
        In Stock :
        <span id="in_stock" class="text-white">
            <?php
                $this->db->select('SUM(qty) as pqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('status', 1);
                $this->db->where('type', "Purchase");
                $Purchase = $this->db->get('invoice_product')->row();
                
                $this->db->select('SUM(qty) as epqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('status', 1);
                $this->db->where('type', "Purchase");
                $EstimatePurchase = $this->db->get('estimate_product')->row();
                
                $EstimatePurchaseQuantity = $EstimateStock == "Yes" ? $EstimatePurchase->epqty : 0;
                
                $this->db->select('SUM(quantity) as ttqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('transaction_type', "TO");
                $TransferTo = $this->db->get('transfer_products')->row();
                
                $this->db->select('SUM(Quantity) as smqty');
                $this->db->where('UserId', $this->session->userdata('user_id'));
                $this->db->where('CompId', $this->session->userdata('comp_id'));
                $this->db->where('ProductId', $result->id);
                $this->db->where('IsDeleted', 1);
                $StockManufacturer = $this->db->get('stock_manufacturer_products')->row();
                
                $this->db->select('SUM(qty) as dnqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('status', 1);
                $this->db->where('type', 'DebitNote');
                $DebitNote = $this->db->get('credit_product')->row();

                echo number_format(($Purchase->pqty + $EstimatePurchaseQuantity + $TransferTo->ttqty + $StockManufacturer->smqty + $DebitNote->dnqty), 2);
            ?>
        </span>
    </div>
    <div class="col-xs-6 col-md-6 col-lg-3 text-lg-right bg-danger">
        Out Stock :
        <span id="out_stock" class="text-white">
            <?php
                $this->db->select('SUM(qty) as sqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('status', 1);
                $this->db->where('type', "Sale");
                $Sale = $this->db->get('invoice_product')->row();
                
                $this->db->select('SUM(qty) as esqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('status', 1);
                $this->db->where('type', "Sale");
                $EstimateSale = $this->db->get('estimate_product')->row();
                
                $EstimateSaleQuantity = $EstimateStock == "Yes" ? $EstimateSale->esqty : 0;
                
                $this->db->select('SUM(quantity) as tfqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('transaction_type', "FROM");
                $TransferFrom = $this->db->get('transfer_products')->row();

                $this->db->select('MainProductId, SUM(Quantity) as rmqty');
                $this->db->where('UserId', $this->session->userdata('user_id'));
                $this->db->where('CompId', $this->session->userdata('comp_id'));
                $this->db->where('ProductId', $result->id);
                $this->db->where('IsDeleted', 1);
                $RawMaterial = $this->db->get('raw_material_products')->row();
                
                $this->db->select('SUM(Quantity) as smqty_value');
                $this->db->where('UserId', $this->session->userdata('user_id'));
                $this->db->where('CompId', $this->session->userdata('comp_id'));
                $this->db->where('ProductId', $RawMaterial->MainProductId);
                $this->db->where('IsDeleted', 1);
                $StockManufacturerData = $this->db->get('stock_manufacturer_products')->row();
                
                $this->db->select('SUM(qty) as cnqty');
                $this->db->where($Where);
                $this->db->where('product_id', $result->id);
                $this->db->where('status', 1);
                $this->db->where('type', 'CreditNote');
                $CreditNote = $this->db->get('credit_product')->row();
                
                echo number_format(($Sale->sqty + $EstimateSaleQuantity + $TransferFrom->tfqty + ($StockManufacturerData->smqty_value * $RawMaterial->rmqty) + $CreditNote->cnqty), 2);
            ?>
        </span>
    </div>
    <div class="col-xs-6 col-md-6 col-lg-3 text-right bg-success">
        Available Stock :
        <span id="available_stock" class="text-white">
            <?= number_format(($result->qty + $Purchase->pqty + $TransferTo->ttqty + $EstimatePurchaseQuantity + $StockManufacturer->smqty + $DebitNote->dnqty) - ($Sale->sqty + $TransferFrom->tfqty + $EstimateSaleQuantity + ($StockManufacturerData->smqty_value * $RawMaterial->rmqty) + $CreditNote->cnqty), 2); ?>
        </span>
    </div>
</div>

<br />
<div class="row">
    <div class="col-md-12">
        <h6 class="text-center">Payment Details for <b><?= $result->p_name; ?></b></h6>
        <div class="table-responsive">
            <table class="table table-bordered" id="ProductStockReport">
                <thead class="bg-navy">
                    <tr>
                        <th>S/N</th>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                        $this->db->select('*, SUM(qty) AS sqty, SUM(netAmount) as snetAmount');
                        $this->db->where($Where);
                        $this->db->where('product_id', $result->id);
                        $this->db->where('status', 1);
                        $this->db->order_by('created_at', 'DESC');
                        $this->db->group_by('InvoiceNo');
                        $invoice_product = $this->db->get('invoice_product')->result();

                        $combined_data = [];
                        if ($invoice_product) {
                            foreach ($invoice_product as $invoice_details) {
                                $invoice_details->type = $invoice_details->type;
                                $invoice_details->qty = $invoice_details->sqty;
                                $invoice_details->netAmount = $invoice_details->snetAmount;
                                $combined_data[] = $invoice_details;
                            }
                        }

                        if ($EstimateStock == 'Yes') {
                            $this->db->select('*, SUM(qty) AS eqty, SUM(netAmount) as enetAmount');
                            $this->db->where($Where);
                            $this->db->where('product_id', $result->id);
                            $this->db->where('status', 1);
                            $this->db->order_by('id', 'DESC');
                            $this->db->group_by('InvoiceNo');
                            $estimate_product = $this->db->get('estimate_product')->result();

                            if ($estimate_product) {
                                foreach ($estimate_product as $estimate_details) {
                                    $estimate_details->type = "Estimate " . $estimate_details->type;
                                    $estimate_details->qty = $estimate_details->eqty;
                                    $estimate_details->netAmount = $estimate_details->enetAmount;
                                    $combined_data[] = $estimate_details;
                                }
                            }
                        }

                        usort($combined_data, function($a, $b) {
                            return strtotime($b->created_at) - strtotime($a->created_at);
                        });

                        $n = 0;
                        foreach ($combined_data as $details) {
                            $n++;
                    ?>
                    <tr>
                        <td><?= $n; ?></td>
                        <td><?= $details->InvoiceNo; ?></td>
                        <td><?= $details->invoiceDate; ?></td>
                        <td><?= $details->type; ?></td>
                        <td><?= $details->qty; ?></td>
                        <td><i class="fa fa-inr"></i> <?= number_format($details->netAmount, 2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#ProductStockReport").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script>