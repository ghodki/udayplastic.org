<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Category</th>
        <th scope="col">Prefix</th>
        <th scope="col">Receipt</th>
        <th scope="col">Description</th>
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
        <td><?= $unknown->date; ?></td>
        <td><?= $unknown->category; ?></td>
        <td><?= $unknown->prefix; ?></td>
        <td><?= $unknown->receipt_number; ?></td>
        <td><?= $unknown->description; ?></td>
        <td>
            <a href="<?= base_url('paymentReceipt/editcategory/') . $unknown->id; ?>" class="text-success">
                <i class="fa fa-edit"></i>
            </a>
            |
            <a href="<?= base_url('paymentReceipt/delete_category/') . $unknown->id; ?>" class="text-danger" onclick="return confirm('Are you sure to delete');">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
    <?php } } else { ?>
    <tr>
        <td colspan="4"></td>
        <td>
            <?= "There is no Records" ?>
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