<thead>
    <tr>
        <th scope="col" width="2%">#</th>
        <th scope="col">Date</th>
        <th scope="col">Party Name</th>
        <th scope="col">Amount</th>
        <th scope="col">Transaction Type</th>
        <th scope="col">Remark</th>
        <th scope="col">Action</th>
    </tr>
</thead>
<tbody id="tableData">
    <?php
        if (isset($result) && $result != '') {
            foreach ($result as $key => $unknown) {
                $unknown = (object) $unknown;
    ?>
    <tr>
        <td><?= $key + 1; ?></td>
        <td><?= $unknown->created_at; ?></td>
        <td><?= get_title('payment_receipt_party', ['id' => $unknown->party_id], 'party_name'); ?></td>
        <td><?= $unknown->amount; ?></td>
        <td><?= $unknown->transaction_type; ?></td>
        <td><?= $unknown->remark; ?></td>
        <td>
            <a href="<?= base_url('paymentReceipt/receipt/') . $unknown->id; ?>" target="_blank" class="text-info">
                <i class="fa fa-eye"></i>
            </a>|
            <a href="javascript:void(0);" onclick="loadBody(<?= $unknown->id; ?>);" class="text-warning">
                <i class="fa fa-envelope"></i>
            </a>|
            <a href="https://wa.me/?text=<?= base_url(); ?>receipt/<?= $unknown->id; ?>" target="_blank" class="text-primary" onclick="return confirm('Are you sure you want to sent the WhatsApp?');">
                <img src="<?= base_url(); ?>assets/images/whatsapp.png" alt="whatsapp" />
            </a>|
            <a href="<?= base_url(); ?>paymentReceipt/sendsms/<?= $unknown->id; ?>" class="text-primary" onclick="return confirm('Are you sure you want to sent the SMS?');">
                <i class="fa fa-envelope-square"></i>
            </a>|
            <a href="<?= base_url('paymentReceipt/edit_transaction/') . $unknown->id; ?>" class="text-success">
                <i class="fa fa-edit"></i>
            </a>|
            <a href="<?= base_url('paymentReceipt/delete_transaction/') . $unknown->id; ?>" class="text-danger" onclick="return confirm('Are you sure to delete')">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
    <?php } } else { ?>
    <tr>
        <td colspan="4"></td>
        <td>
            <?php echo "There is no Records" ?>
        </td>
        <td colspan="3"></td>
    </tr>
    <?php } ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="7">
            <?php echo $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
</tfoot>