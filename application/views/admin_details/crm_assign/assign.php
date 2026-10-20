<h6 class="text-center"><b><?= get_title('crm_company', ['id' => $id], 'company_name'); ?></b></h6>
<div class="row">
    <input type="hidden" name="comp_id" id="comp_id" value="<?= $id ?>">
    <div class="col-md-12 col-md-12 col-sm-6 col-lg-12">
        <div class="form-group">
            <button class="btn btn-primary" id="assign_user" style="/* margin-bottom: -84px; */width: 100%;">Assign</button>
        </div>
    </div>
</div>
<table id="product-table" class="table table-bordered table-hover table-striped dataTable dtr-column text-center" cellspacing="0" width="100%" aria-describedby="product-table_info" role="grid">
    <thead>
        <tr role="row">
            <!-- <th rowspan="1" colspan="1" aria-label=""><input type="checkbox" id="product-selectAll"><label for="product-selectAll"></label></th> -->
            <th aria-label=""><input type="checkbox" id="user-selectAll"><label for="user-selectAll"></label>Sr</th>
            <!-- <th></th> -->
            <th>User Name</th>
            <th aria-label="" hidden><input type="checkbox" id="view-select"><label for="view-select"></label>View</th>
            <th aria-label="" hidden><input type="checkbox" id="edit-select"><label for="edit-select"></label>Edit</th>


        </tr>
    </thead>
    <tbody>
        <?php
        $user_id = get_title('crm_company', ['id' => $id], 'user_id');
        $result = $this->user_model->getAry('users', '', '', '', ['status' => 1, 'id!=' => $user_id]);
        if ($result) {
            foreach ($result as $key => $res) {
                $cheked = '';

        ?>
                <tr>
                    <?php
                    $company_result = $this->user_model->getRow('crm_assign', ['comp_id' => $id]);
                    if ($company_result) {
                        $assign_users = $company_result->assign_user;
                        if ($assign_users) {
                            $all_users_assign_comp = json_decode($assign_users);
                            foreach ($all_users_assign_comp as $key => $users_of_assigning) {
                                if ($users_of_assigning == $res->id) {
                                    $cheked = "checked";
                                    break;
                                }
                            }
                        }
                    }
                    ?>

                    <td class="cheking">
                        <input class="checkdata" <?= $cheked ?> type="checkbox" name="assign_user[]" value="<?php echo $res->id; ?>">
                    </td>
                    <th><?= $res->name ?></th>
                    <td class="viewchecking" hidden>
                        <input class="viewall" <?= $cheked ?> type="checkbox" name="view[]" value="<?php echo $res->id; ?>">
                    </td>
                    <td class="editchecking" hidden>
                        <input class="editall" <?= $cheked ?> type="checkbox" name="edit[]" value="<?php echo $res->id; ?>">
                    </td>
                </tr>


        <?php
            }
        }
        // echo '<pre>';
        // print_r($result);
        ?>
    </tbody>
</table>


<script>
    $("#user-selectAll").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
        // $(".viewall").prop('checked', $(this).prop('checked'));
        // $(".editall").prop('checked', $(this).prop('checked'));


    });

    $("#view-select").click(function() {
        $(".viewall").prop('checked', $(this).prop('checked'));
        // $(".checkdata").prop('checked', $(this).prop('checked'));

    });

    $("#edit-select").click(function() {
        $(".editall").prop('checked', $(this).prop('checked'));
        // $(".checkdata").prop('checked', $(this).prop('checked'));

    });

    var inputs = document.querySelectorAll("input,select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                //  alert(e.which)
                nextInput[0].focus();
            }
        })
    }
</script>
<script>
    $(function() {
        $("#assign_user").click(function() {
            var selected = new Array();
            var view = new Array();
            var edit = new Array();

            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            $(".viewchecking input[type=checkbox]:checked").each(function() {
                view.push(this.value);
            });
            $(".editchecking input[type=checkbox]:checked").each(function() {
                edit.push(this.value);
            });
            let comp_id = $("#comp_id").val();
            console.log(selected);
            if (comp_id) {
                if (!confirm('Are you sure to Assign the Company?')) return false;
                console.log(selected)
                $.ajax({
                    type: "POST",
                    url: '<?= base_url("index.php/crm/crm_assign/assign_comp_to_user") ?>',
                    data: {
                        assign_user: selected,
                        view: view,
                        edit: edit,
                        comp_id: comp_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        // data = JSON.parse(data);
                        console.log(data);
                        if (data.status == true) {
                            toastr.success("", data.data);
                            $("#adding_party_ok").modal("hide");

                        } else {
                            toastr.error("", data.data);

                        }
                        // setInterval(function() {
                        //     location.reload();
                        // }, 1000);

                    },
                    error: function() {
                        alert("Server issue");
                    }

                });
            } else {
                alert("Company Fields are Required");
            }

        });

    })
</script>