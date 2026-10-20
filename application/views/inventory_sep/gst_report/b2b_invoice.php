<h6 class="text-center">Payment Details for <b><?= $result->p_name ?></b></h6>
<?php
$instock = 0;
$outstock = 0;
$opening_stock = $result->qty;


?>
<div class="row" style="background: chartreuse;">
    <div class="col-xs-6 col-md-6 col-lg-3" style="background: aqua;">Opening Stock : <i class="fa fa-inr"></i> <span id="opening_stock"><?= $result->qty ?>.00</span></div>
    <div class="col-xs-6 col-md-6 col-lg-3 text-sm-right">In Stock : <i class="fa fa-inr"></i> <span id="in_stock">
            <?php
            $user_id = $this->session->userdata('user_id');
            $comp_id = $this->session->userdata('comp_id');
            $this->db->select('SUM(qty) as sqty');
            $this->db->where('user_id', $user_id);
            $this->db->where('comp_id', $comp_id);
            if ($this->session->userdata('BranchRole')) {
                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
            }
            $this->db->where('status', 1);
            $this->db->where('product_id', $result->id);
            $this->db->where('type', "Purchase");
            $q = $this->db->get('invoice_product');
            $r = $q->row();
            $salqty = $r->sqty;
            if ($salqty == '') {
                echo 0;
            } else {
                $instock = $salqty;
                echo $salqty;
            }
            ?>
            .00</span></div>
    <div class="col-xs-6 col-md-6 col-lg-3 text-lg-right" style="background: burlywood;">Out Stock : <i class="fa fa-inr"></i> <span id="out_stock">
            <?php
            $this->db->select('SUM(qty) as sqty');
            $this->db->where('user_id', $user_id);
            $this->db->where('comp_id', $comp_id);
            if ($this->session->userdata('BranchRole')) {
                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
            }
            $this->db->where('status', 1);
            $this->db->where('product_id', $result->id);
            $this->db->where('type', "Sale");
            $q = $this->db->get('invoice_product');
            $r = $q->row();
            $outs = $r->sqty;
            if ($outs == '') {
                echo 0;
            } else {
                $outstock = $outs;
                echo $outs;
            }
            ?>
            .00</span></div>
    <div class="col-xs-6 col-md-6 col-lg-3 text-right">Available Stock : <i class="fa fa-inr"></i> <span id="available_stock">
            <?php
            $available_stock = $opening_stock;
            echo $available_stock;
            ?>
            .00</span></div>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>NetAmount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $invoice_product = $this->user_model->getAry('invoice_product', '', '', '', ['product_id' => $result->id]);
                if ($invoice_product) {
                    foreach ($invoice_product as $invoice_details) {
                ?>
                        <tr>
                            <td><?= $invoice_details->InvoiceNo ?></td>
                            <td><?= $invoice_details->invoiceDate ?></td>
                            <td><?= $invoice_details->type ?></td>
                            <td><?= $invoice_details->qty ?></td>
                            <td><?= $invoice_details->netAmount ?></td>
                        <tr>
                    <?php
                    }
                }
                    ?>
            </tbody>
        </table>
    </div>
</div>