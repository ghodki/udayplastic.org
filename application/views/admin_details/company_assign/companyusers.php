<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Company Name</th>
                <th>Assigned User</th>
                <th>Type</th>
                <th>Email & Mobile</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($company_users) {
                    $n = 0;
                    foreach ($company_users as $key => $value) {
                        $n++;
            ?>
            <tr>
                <td><?= $n; ?></td>
                <td><?= get_title('inventory_billing', ['id' => $value->comp_id], 'company_name'); ?></td>
                <?php if ($value->assign_user != 0) { ?>
                <td><?= get_title('users', ['id' => $value->assign_user], 'name'); ?></td>
                <td><?= get_title('roles', ['id' => get_title('users', ['id' => $value->assign_user], 'userType')], 'full_name'); ?></td>
                <td>
                    <?= get_title('users', ['id' => $value->assign_user], 'email'); ?>
                    <br />
                    <?= get_title('users', ['id' => $value->assign_user], 'number'); ?>
                </td>
                <?php } else { ?>
                <th colspan="3"><p class="text-danger text-center">User permission remove for Company.</p></th>
                <?php } ?>
            </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>