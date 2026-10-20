<?php

if (isset($result) && $result != '') {
    foreach ($result as  $key => $value) {

        $date = $value->date;

?>
        <tr scope="<?= $key ?>">
            <td>
                <span><?= $key + 1; ?></span>
            </td>
            <td><?= $date; ?></td>
            <td>
                <?= $value->applicant_name ?>
            </td>
            <td>
                <?= $value->email ?>
            </td>
            <td>
                <?= $value->expiry_date ?>
            </td>
            <td>
                <?= get_title('crm_status', ['id' => $value->status], 'name') ?>
            </td>
            <td>
                <?= get_title('crm_type', ['id' => $value->type], 'name') ?>
            </td>
            <td>


                <?= $value->remark ?>
            </td>

            <td class="text-right">

                <a href="<?= getCrmUrl() ?>master/delete_dsc_process/<?= $value->id ?>"><button class="btn btn-danger" onclick='return confirm("Are You Sure Want to delete dsc_process?")'><i class="bi bi-trash"></i></button></a>

                <button class="btn btn-primary" onclick="viewModel(<?= $value->id ?>)"><i class="bi bi-eye"></i></button>

                <button class="btn btn-primary" onclick="companyEdit(<?= $value->id ?>)"><i class="ri-edit-box-line"></i></button>
            </td>
        </tr>
<?php
    }
}
?>