<thead class="thead-light">
    <tr>
        <th>#</th>
        <th>Group Name</th>
        <th>Email</th>
        <th>Action</th>
    </tr>
</thead>
<tbody id="tableData">
    <?php
        if (isset($result) && $result != '') {
            $n = 0;
            foreach ($result as $key => $unknown) {
                $unknown = (object) $unknown;
                $n++;
    ?>
    <tr>
        <td><?= $n; ?></td>
        <td><?= get_title('emailgroup', ['id' => $unknown->group_id], 'name'); ?></td>
        <td><?= $unknown->email; ?></td>
        <td>
            <a href="<?= base_url('useremail/edit/') . $unknown->id; ?>" class="text-success">
                <i class="fa fa-pencil-square"></i>
            </a>
            &nbsp;
            <a href="<?= base_url('useremail/delete/') . $unknown->id; ?>" class="text-danger" onclick="return confirm('Are you sure want to delete record ?');">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
    <?php } } else { ?>
    <tr>
        <td colspan="4">
            <p class="text-danger text-center">There is no record found.</p>
        </td>
    </tr>
    <?php } ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="4">
            <?= $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
</tfoot>