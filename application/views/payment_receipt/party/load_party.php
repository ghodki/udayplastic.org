<thead>
    <tr>
        <th scope="col" width="2%">#</th>
        <th scope="col">Date</th>
        <th scope="col">Party Name</th>
        <th scope="col">Phone Number</th>
        <th scope="col">User Type</th>
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
        <td title="Transactions">
            <a href="<?= base_url(); ?>paymentReceipt/user_transaction/<?= $unknown->id; ?>">
            <?= $unknown->party_name; ?>
            </a>
        </td>
        <td><?= $unknown->number; ?></td>
        <td><?= $unknown->usertype; ?></td>
        <td>
            <a href="<?= base_url('paymentReceipt/editParty/') . $unknown->id; ?>" class="text-success">
                <i class="fa fa-edit"></i>
            </a>
            |
            <a href="<?= base_url('paymentReceipt/delete_party/') . $unknown->id; ?>" class="text-danger" onclick="return confirm('Are you sure to delete');">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
    <?php } } else { ?>
    <tr>
        <td colspan="4"></td>
        <td>
            <?= "There is no Records"; ?>
        </td>
        <td colspan="3"></td>
    </tr>
    <?php } ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="7">
            <?= $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
</tfoot>