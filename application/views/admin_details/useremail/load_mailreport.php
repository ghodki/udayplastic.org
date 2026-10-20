<thead class="thead-light">
    <tr>
        <th>#</th>
        <th>From</th>
        <th>Group Name</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Created</th>
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
        <td><?= $unknown->send_from; ?></td>
        <td><?= get_title('emailgroup', ['id' => $unknown->group_id], 'name'); ?></td>
        <td><?= $unknown->subject; ?></td>
        <td>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#message" title="Message Detail" class="text-info" onclick="showMessage(<?= $unknown->id; ?>);">
                <i class="fa fa-info-circle"></i>
            </a>
        </td>
        <td><?= $unknown->created_at; ?></td>
    </tr>
    <?php } } else { ?>
    <tr>
        <td colspan="6">
            <p class="text-danger text-center">There is no record found.</p>
        </td>
    </tr>
    <?php } ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="6">
            <?= $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
</tfoot>