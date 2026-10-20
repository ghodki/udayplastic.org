<thead class="thead-dark">
    <tr>
        <td colspan="4">
            <?php echo $this->ajax_pagination->create_links(); ?>
        </td>
    </tr>
    <tr>
        <th>#</th>
        <th>Date</th>
        <th>Loan Type</th>
        <th>User</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>

<tbody class="text-dark">
    <?php
    if (isset($result) && $result != '') {
        foreach ($result as  $key => $value) {
            $date = $value['created_at'];
    ?>
            <tr>
                <td>
                    <span><?= $start + 1; ?></span>
                </td>
                <td><?= $date; ?></td>
                <td><?= get_title('loan_category', ['id' => $value['loan_cat_id']], 'loan_name') ?></td>
                <td><?= $value['name'] ?></td>
                <td><?= $value['email']; ?></td>
                <td><?= $value['mobile_number']; ?></td>
                <td><?php
                    if ($value['status'] == 0)
                        echo '<button class="btn btn-danger">Failed</button>';
                    elseif ($value['status'] == 1)
                        echo '<button class="btn btn-warning">Under Processing</button>';
                    elseif ($value['status'] == 2)
                        echo '<button class="btn btn-success">Approved</button>';
                    elseif ($value['status'] == 4)
                        echo '<button class="btn btn-danger">Declined</button>';

                    ?></td>
                <td>
                    <?php
                    if ($this->session->userdata('user_id') != $value['user_id']) {
                    ?>
                        <i class="fa fa-check" aria-hidden="true" title="Approve" onclick="approveRequest(<?= $value['id'] ?>)"></i>
                        <i class="fa fa-ban" aria-hidden="true" title="Declined" onclick="declineRequest(<?= $value['id'] ?>)"></i>
                    <?php
                    }
                    ?>
                    <!-- <a href="<?= base_url() ?>viewDigitalSignature/<?= $value['id'] ?>"><i class="fa fa-eye" aria-hidden="true" title="View details"></i></a> -->

                </td>
            </tr>

    <?php
            ++$start;
        }
    }

    ?>


</tbody>

<script>
    function approveRequest(service_id) {
        if (service_id) {
            if (!confirm('Are you sure to approve the request?')) return false;
            $.ajax({
                type: "POST",
                url: '<?= base_url("approve_loan") ?>',
                data: {
                    service_id: service_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == true) {
                        toastr.success(data.message);
                        searchFilter(0);

                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function() {
                    alert("Server issue");
                }
            });
        } else {
            alert("Something went wrong");
        }
    }

    function declineRequest(service_id) {
        if (service_id) {
            if (!confirm('Are you sure to decline the request?')) return false;
            $.ajax({
                type: "POST",
                url: '<?= base_url("decline_loan") ?>',
                data: {
                    service_id: service_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == true) {
                        toastr.success(data.message);
                        searchFilter(0);

                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function() {
                    alert("Server issue");
                }
            });
        } else {
            alert("Something went wrong");
        }
    }
</script>