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
    $total_credit = 0;
    $total_debit = 0;
    $balance = 0;

    if (isset($result) && $result != '') {
        foreach ($result as $key => $unknown) {
            $unknown = (object) $unknown;
            if ($unknown->transaction_type == 'Credit') {
                $total_credit = $total_credit + $unknown->amount;
            } else {
                $total_debit = $total_debit + $unknown->amount;
            }
    ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $unknown->created_at ?></td>
                <td><?= get_title('khatabook_party', ['id' => $unknown->party_id], 'party_name'); ?></td>
                <td><?= $unknown->amount; ?></td>
                <td><?= $unknown->transaction_type; ?></td>
                <td><?= $unknown->remark; ?></td>
                <td>
                    <a href="<?= base_url('khatabook/edit_transaction/') . $unknown->id ?>" class="text-success"><i class="fa fa-edit"></i></a>|
                    <a href="<?= base_url('khatabook/delete_transaction/') . $unknown->id ?>" class="text-danger" onclick="return confirm('Are you sure to delete')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php }
    } else {
        ?>
        <tr>
            <td colspan="4"></td>
            <td>
                <?php echo "There is no Records" ?>
            </td>
            <td colspan="3"></td>

        </tr>
    <?php
    }
    ?>
</tbody>
<tfoot>
    <tr>
        <td></td>
        <td class="text-primary">Credit-Debit</td>
        <td class="text-info"><?= $total_credit  ?>-<?= $total_debit ?></td>
        <td>Balance</td>
        <td class="text-success"><?= $total_credit - $total_debit ?></td>
    </tr>
    <tr>
        <td colspan="7">
            <?php echo $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
</tfoot>