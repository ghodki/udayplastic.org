<table class="table table-sm">
    <thead class="bg-dark">
        <tr>
            <th>Sr</th>
            <th>Action</th>
            <th class="stickyColumn">Invoice Number</th>
            <th class="stickyColumn">Invoice Date</th>
            <th class="stickyColumn">Party Name</th>
            <th>Mobile</th>
            <th>GST No</th>
            <th>Payment Mode</th> 
            <th>Taxable</th>
            <th>Tax</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Paid</th>
            <th>Balance</th>
            <th>Payment Mode</th>
            <th>Discount</th>
            <th>Salesman</th>
            <th>Delivery</th>
            <th>Created</th>
            <th>Created By</th>
            <th>Updated</th>
            <th>Updated By</th>
        </tr>
    </thead>
        <tbody>
            <?php
                if (isset($SalesInvoices) && !empty($SalesInvoices)) {
                    $Sr = 0;
                    $Taxable = 0;
                    $Tax = 0;
                    $Total = 0;
                    $Paid = 0;
                    $Balance = 0;
                    foreach ($SalesInvoices as $key => $Row) {
                        $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$Row->id\"]') AND transaction_type = 'DR'";
                        $query = $this->db->query($sql);
                        $Payment = $query->row();
                        $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, cashDiscountAmount, ChequeStatus')->where('invoice_id', $Row->id)->where('transaction_type', 'DR')->get('payment')->row();

                        $Sr++;
                        if ($Row->status == '1') {
                            $TextDecoration = 'unset';
                            $Taxable += $Row->taxableAmount;
                            $Tax += $Row->totalTax;
                            $Total += $Row->total;
                            $Paid += $Row->paid_amount;
                            $Balance += ($Row->total - $Row->paid_amount);
                        } else {
                            $TextDecoration = 'line-through';
                        }
            ?>
            <tr>
                <td><?= $Sr; ?></td>
                <td>
                    <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $Row->id; ?>);" class="text-success" title="Delivery">
                        <i class="fa fa-bus"></i>
                    </a>
                    &nbsp;
                    <a href="<?= base_url(); ?>Sale/Copy/<?= $Row->id; ?>" class="text-warning" title="Copy Invoice" target="_blank">
                        <i class="fa fa-copy"></i>
                    </a>
                    &nbsp;
                    <a href="<?= base_url(); ?>Sale/viewsale/<?= $Row->id; ?>" class="text-info" target="_blank" title="View">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                    &nbsp;
                    <a href="<?= base_url(); ?>Sale/saleEdit/<?= $Row->id; ?>" class="text-primary" target="_blank" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <?php if ($Row->status == '1') { if ($Row->paid_amount == NULL || $Row->paid_amount == '0') { ?>
                    &nbsp;
                    <a href="<?= base_url(); ?>Sale/sale_deleted/<?= $Row->id; ?>" class="text-danger" onclick="return confirm('Are you sure to want to delete this invoice');" title="Cancelled">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php } } ?>
                    &nbsp;
                    <a href="<?= base_url(); ?>Sale/printSale/<?= $Row->id; ?>" target="_blank" title="Print">
                        <i class="fa fa-print text-olive"></i>
                    </a>
                    &nbsp;
                    <a href="<?= base_url(); ?>Sale/printSales/<?= $Row->id; ?>" class="text-fuchsia" target="_blank" title="Download">
                        <i class="fa fa-download"></i>
                    </a>
                    &nbsp;
                    <a href="<?= base_url(); ?>credit_note/Create/<?= $Row->id; ?>" target="_blank" title="Credit Note">
                        <i class="fa fa-comments text-navy"></i>
                    </a>
                    &nbsp;
                    <a href="javascript:void(0);" title="Send Mail" onclick="SendMail(<?= $Row->id; ?>);">
                        <i class="fa fa-envelope text-orange"></i>
                    </a>
                    &nbsp;
                    <a href="https://api.whatsapp.com/send?phone=+91<?= $Row->contact; ?>&text=<?= base_url(); ?>printInvoice/<?= $Row->id; ?>" title="Send on WhatsApp" target="_blank">
                        <img src="<?= base_url(); ?>assets/images/whatsapp.png" alt="whatsapp" style="width: 20px;" />
                    </a>
                    &nbsp;
                    <a href="javascript:void(0);" onclick="onDeliveryChatHandler(<?= $Row->id; ?>);" title="Delivery Chat">
                        <i class="fa fa-comment text-purple"></i>
                    </a>
                    <?php 
                        $compWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                            $compWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        if (get_title('comp_profile', $compWhere, 'formE') == 'Y') {
                    ?>
                    &nbsp;
                    <a href="<?= base_url(); ?>Sale/formE/<?= $Row->id; ?>" title="From-E" target="_blank">
                        <i class="fa fa-paperclip text-maroon"></i>
                    </a>
                    <?php } ?>
                </td>
                <td class="stickyColumn"><?= $Row->invoiceNumber; ?></td>
                <td class="stickyColumn"><?= date('d-m-Y', strtotime($Row->invoiceDate)); ?></td>
                <td class="stickyColumn"><?= $Row->name; ?></td>
                <td><?= $Row->contact; ?></td>
                <td><?= $Row->gst; ?></td>
                <td>
                    <?php
                        if ($Row->voucherType == 1) {
                            $PaymentMode = "Cash";
                        } elseif ($Row->voucherType == 2) {
                            $PaymentMode = "Credit";
                        } else {
                            $PaymentMode = $Row->voucherType;
                        }
                        echo $PaymentMode;
                    ?>
                </td>
                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($Row->taxableAmount); ?></td>
                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($Row->totalTax); ?></td>
                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($Row->total); ?></td>
                <td>
                    <?php if ($Row->status == '1') { if ($Row->paid_amount == $Row->total) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                    <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $Row->id; ?>);" title="Cheque Info">
                        <i class="fa fa-exclamation"></i> UnPaid/PDC
                    </a>
                    <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                    <label class="btn btn-success btn-xs">
                        <i class="fa fa-check-circle text-white"></i> Paid
                    </label>
                    <?php } } else { ?>
                    <label class="btn btn-success btn-xs">
                        <i class="fa fa-check-circle text-white"></i> Paid
                    </label>
                    <?php } } elseif ($Row->paid_amount == NULL || $Row->paid_amount == 0) { ?>
                    <a href="<?= base_url(); ?>receipt/index/<?= $Row->id; ?>" class="btn btn-danger btn-xs">
                        <i class="fa fa-exclamation-triangle"></i> Unpaid
                    </a>
                    <?php } else { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                    <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $Row->id; ?>);" title="Cheque Info">
                        <i class="fa fa-exclamation"></i> UnPaid/PDC
                    </a>
                    <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                    <a href="<?= base_url(); ?>receipt/index/<?= $Row->id; ?>" class="btn btn-warning btn-xs">
                        <i class="fa fa-exclamation-circle"></i> Partial
                    </a>
                    <?php } } else { ?>
                    <a href="<?= base_url(); ?>receipt/index/<?= $Row->id; ?>" class="btn btn-warning btn-xs">
                        <i class="fa fa-exclamation-circle"></i> Partial
                    </a>
                    <?php } } } else { ?>
                    <label class="btn btn-danger btn-xs">
                        <i class="fa fa-ban text-white"></i> Cancelled
                    </label>    
                    <?php } ?>
                </td>
                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($Row->paid_amount); ?></td>
                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat(($Row->total - $Row->paid_amount)); ?></td>
                <td><?= get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method'); ?></td>
                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= $PaymentInfo->cashDiscountAmount; ?></td>
                <td><?= get_title('employees', ['Id' => $Row->SalesmanId], 'Name'); ?></td>
                <td><?= $Row->delivery_status == 1 ? "Yes" : "No"; ?></td>
                <td><?= $Row->created; ?></td>
                <td><?= $Row->created_by; ?></td>
                <td><?= $Row->updated; ?></td>
                <td><?= $Row->updated_by; ?></td>
            </tr>
            <?php } } ?>
        </tbody>
    <tfoot>
        <tr>
            <th colspan="8" style="text-align: right;">Sub Total</th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Taxable); ?></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Tax); ?></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total); ?></th>
            <th></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Paid); ?></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Balance); ?></th>
            <th colspan="8"></th>
        </tr>
        <tr style="border: none;"><th colspan="22" style="border: none;"></th></tr>
        <tr style="border: none;"><th colspan="22" style="border: none;"></th></tr>
        <tr style="border: none;"><th colspan="22" style="border: none;"></th></tr>
        <tr>
            <?php
                $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $SaleInfo = $this->db->select('SUM(taxableAmount) AS taxableAmount, SUM(totalTax) AS totalTax, SUM(total) AS total, SUM(COALESCE(paid_amount, 0)) AS paid_amount')->where($Where)->get('sales')->row();
            ?>
            <th colspan="8" style="text-align: right;">Total</th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($SaleInfo->taxableAmount); ?></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($SaleInfo->totalTax); ?></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($SaleInfo->total); ?></th>
            <th></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($SaleInfo->paid_amount); ?></th>
            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat(($SaleInfo->total - $SaleInfo->paid_amount)); ?></th>
            <th colspan="8"></th>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td><?= $this->ajax_pagination_dashboard->create_links(); ?></td>
        </tr>
    </tfoot>
</table>