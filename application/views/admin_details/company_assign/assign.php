<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>

<!-- <h6 class="text-center"></h6> -->
<div class="row">
    <input type="hidden" name="comp_id" id="comp_id" value="<?= $id ?>">
    <!-- <div class="col-md-12 col-md-12 col-sm-6 col-lg-12">
        <div class="form-group">
            <button class="btn btn-primary">Assign <b><?= get_title('inventory_billing', ['id' => $id], 'company_name'); ?></b> to <i class="fa fa-arrow-down" aria-hidden="true"></i>
            </button>
        </div>
    </div> -->
</div>
<table id="product-table" class="table table-bordered table-hover table-striped DataTables dtr-column text-center" cellspacing="0" width="100%" aria-describedby="product-table_info" role="grid">
    <thead>
        <tr role="row">
            <!-- <th rowspan="1" colspan="1" aria-label=""><input type="checkbox" id="product-selectAll"><label for="product-selectAll"></label></th> -->
            <th aria-label=""><input type="checkbox" id="user-selectAll"><label for="user-selectAll"></label>Sr</th>
            <!-- <th></th> -->
            <th>User Name</th>
            <th>Permission</th>
            <th>Modules</th>
            <th>Assign</th>

        </tr>
    </thead>
    <tbody>
        <?php
        $user_id = get_title('inventory_billing', ['id' => $id], 'user_id');
        $result = $this->user_model->getAry('users', '', '', '', ['status' => 1, 'id!=' => $user_id]);
        if ($result) {
            foreach ($result as $key => $res) {
                $cheked = '';
                $permission = '';
                $view_invoice_current_month = '';
                $view_report_and_gst_return = '';
                $date_range = '';
                $today = '';
                $only_user_display = '';
                $checkedModule = '';
                $cheked_only_user_display = '';
                $cheked_view_invoice_current_month = '';
                $cheked_view_report_and_gst_return = '';
                $cheked_date_range = '';
                $cheked_today = '';
        ?>
                <tr>
                    <?php
                    $company_result = $this->user_model->getRow('company_assign', ['comp_id' => $id, 'user_assign_by_unique' => $res->id]);
                    if ($company_result) {
                        if ($company_result->assign_user == $res->id) {
                            $cheked = "checked";
                        }
                        $permission = $company_result->permission;
                        if ($permission == "Full") {
                            $checkedModule = "checked disabled";
                        } else {
                            $checkedModule = '';
                        }
                        if ($permission == "Custom") {
                            if ($company_result->only_user_display == 'only_user_display') {
                                $cheked_only_user_display = 'checked';
                            }

                            if ($company_result->view_invoice_current_month == 'view_invoice_current_month') {
                                $cheked_view_invoice_current_month = 'checked';
                            }

                            if ($company_result->view_report_and_gst_return == 'view_report_and_gst_return') {
                                $cheked_view_report_and_gst_return = 'checked';
                            }

                            if ($company_result->date_range == 'date_range') {
                                $cheked_date_range = 'checked';
                            }
                            if ($company_result->today == 'today') {
                                $cheked_today = 'checked';
                            }
                        }


                    ?>

                        <td class="cheking">
                            <input class="checkdata" <?= $cheked ?> type="checkbox" name="assign_user[]" id="assign_user<?php echo $res->id; ?>" value="<?php echo $res->id; ?>">
                        </td>
                        <th><?= $res->name ?></th>

                        <th>
                            <select class="form-control" onchange="SetValueofCheckbox(<?= $res->id ?>)" name="permission[<?= $res->id ?>]" id="permission<?php echo $res->id; ?>">
                                <option value="Full" <?= $permission == 'Full' ? 'selected' : '' ?>>Full</option>
                                <option value="Custom" <?= $permission == 'Custom' ? 'selected' : '' ?>>Custom</option>
                            </select>
                        </th>
                        <th>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule . $cheked_only_user_display ?> type="checkbox" type="checkbox" name="only_user_display[<?= $res->id ?>]" id="only_user_display<?= $res->id ?>" value="only_user_display">
                                <label class="form-check-label" for="only_user_display">Display only this User</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule . $cheked_view_report_and_gst_return  ?> type="checkbox" type="checkbox" name="view_report_and_gst_return[<?= $res->id ?>]" id="view_report_and_gst_return<?= $res->id ?>" value="view_report_and_gst_return">
                                <label class="form-check-label" for="view_report_and_gst_return">View Report and Gst Return</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule . $cheked_view_invoice_current_month ?> type="checkbox" type="checkbox" name="view_invoice_current_month[<?= $res->id ?>]" id="view_invoice_current_month<?= $res->id ?>" value="view_invoice_current_month">
                                <label class="form-check-label" for="view_invoice_current_month">View Invoice-Current Month</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule . $cheked_today ?> type="checkbox" type="checkbox" name="today[<?= $res->id ?>]" id="today<?= $res->id ?>" value="today">
                                <label class="form-check-label" for="today">Today</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule . $cheked_date_range ?> type="checkbox" type="checkbox" name="date_range[<?= $res->id ?>]" id="date_range<?= $res->id ?>" value="date_range">
                                <label class="form-check-label" for="Date Range">Date Range</label>
                            </div>
                        </th>
                    <?php
                    } else {
                        $checkedModule = "checked disabled";

                    ?><td class="cheking">
                            <input class="checkdata" <?= $cheked ?> type="checkbox" name="assign_user[]" id="assign_user<?php echo $res->id; ?>" value="<?php echo $res->id; ?>">
                        </td>
                        <th><?= $res->name ?></th>

                        <th><select class="form-control" onchange="SetValueofCheckbox(<?= $res->id ?>)" name="permission[<?= $res->id ?>]" id="permission<?php echo $res->id; ?>">
                                <option value="Full" <?= $permission == 'Full' ? 'selected' : '' ?>>Full</option>
                                <option value="Custom" <?= $permission == 'Custom' ? 'selected' : '' ?>>Custom</option>

                            </select></th>
                        <th>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule ?> type="checkbox" type="checkbox" name="only_user_display[<?= $res->id ?>]" id="only_user_display<?= $res->id ?>" value="only_user_display">
                                <label class="form-check-label" for="only_user_display">Display only this User</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule ?> type="checkbox" type="checkbox" name="view_report_and_gst_return[<?= $res->id ?>]" id="view_report_and_gst_return<?= $res->id ?>" value="view_report_and_gst_return">
                                <label class="form-check-label" for="view_report_and_gst_return">View Report and Gst Return</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule ?> type="checkbox" type="checkbox" name="view_invoice_current_month[<?= $res->id ?>]" id="view_invoice_current_month<?= $res->id ?>" value="view_invoice_current_month">
                                <label class="form-check-label" for="view_invoice_current_month">View Invoice-Current Month</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule ?> type="checkbox" type="checkbox" name="today[<?= $res->id ?>]" id="today<?= $res->id ?>" value="today">
                                <label class="form-check-label" for="today">Today</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input findoneid<?= $res->id ?>" <?= $checkedModule ?> type="checkbox" type="checkbox" name="date_range[<?= $res->id ?>]" id="date_range<?= $res->id ?>" value="date_range">
                                <label class="form-check-label" for="Date Range">Date Range</label>
                            </div>
                        </th>
                    <?php
                    }
                    ?>

                    <th>
                        <button class="btn btn-primary" onclick="AssignPerticular(<?= $res->id ?>)">Assign</button>
                    </th>

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


    $(document).ready(function() {
        $(".DataTables").DataTable({
            dom: 'Bfrtip',
            "pageLength": 500,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'pageLength',
            ],
        });
    })
</script>
<script>
    // $(function() {
    //     $("#assign_user").click(function() {
    //         var selected = new Array();
    //         var permission = new Array();
    //         var modules = new Array();
    //         var only_user_display = new Array();
    //         var date_range = new Array();
    //         var today = new Array();
    //         var view_invoice_current_month = new Array();
    //         var view_report_and_gst_return = new Array();

    //         i = 0;
    //         $(".cheking input[type=checkbox]:checked").each(function() {

    //             selected.push(this.value);

    //             var permissionValue = $("#permission" + this.value).val();
    //             permission.push(permissionValue);
    //             var modulesData = document.getElementById('only_user_display' + this.value).checked;
    //             if (modulesData == true)
    //                 only_user_display.push(document.getElementById('only_user_display' + this.value).value);
    //             var modulesData = document.getElementById('date_range' + this.value).checked;
    //             if (modulesData == true)
    //                 date_range.push(document.getElementById('date_range' + this.value).value);
    //             var modulesData = document.getElementById('today' + this.value).checked;
    //             if (modulesData == true)
    //                 today.push(document.getElementById('today' + this.value).value);
    //             var modulesData = document.getElementById('view_invoice_current_month' + this.value).checked;
    //             if (modulesData == true)
    //                 view_invoice_current_month.push(document.getElementById('view_invoice_current_month' + this.value).value);
    //             var modulesData = document.getElementById('view_report_and_gst_return' + this.value).checked;
    //             if (modulesData == true)
    //                 view_report_and_gst_return.push(document.getElementById('view_report_and_gst_return' + this.value).value);

    //         });
    //         let comp_id = $("#comp_id").val();
    //         // console.log(new_module);
    //         if (comp_id) {
    //             if (!confirm('Are you sure to Assign the Company?')) return false;
    //             console.log(selected)
    //             $.ajax({
    //                 type: "POST",
    //                 url: '<?= base_url("company_assign/assign_comp_to_user") ?>',
    //                 data: {
    //                     assign_user: selected,
    //                     only_user_display: only_user_display,
    //                     date_range: date_range,
    //                     today: today,
    //                     view_invoice_current_month: view_invoice_current_month,
    //                     view_report_and_gst_return: view_report_and_gst_return,
    //                     permission: permission,
    //                     comp_id: comp_id
    //                 },
    //                 dataType: 'json',
    //                 success: function(data) {
    //                     // data = JSON.parse(data);
    //                     console.log(data);
    //                     if (data.status == true) {
    //                         toastr.success("", data.data);
    //                         $("#adding_party_ok").modal("hide");

    //                     } else {
    //                         toastr.error("", data.data);

    //                     }
    //                     // setInterval(function() {
    //                     //     location.reload();
    //                     // }, 1000);

    //                 },
    //                 error: function() {
    //                     alert("Server issue");
    //                 }

    //             });
    //         } else {
    //             alert("Company Fields are Required");
    //         }

    //     });

    // })


    function AssignPerticular(user_id) {
        var permission = '';
        var only_user_display = '';
        var date_range = '';
        var today = '';
        var view_invoice_current_month = '';
        var view_report_and_gst_return = '';
        var user_assign_by_unique = '';
        var permission = $("#permission" + user_id).val();
        var modulesData = document.getElementById('assign_user' + user_id).checked;
        if (modulesData == true)
            user_assign_by_unique = document.getElementById('assign_user' + user_id).value;
        var modulesData = document.getElementById('only_user_display' + user_id).checked;
        if (modulesData == true)
            only_user_display = document.getElementById('only_user_display' + user_id).value;
        var modulesData = document.getElementById('date_range' + user_id).checked;
        if (modulesData == true)
            date_range = document.getElementById('date_range' + user_id).value;
        var modulesData = document.getElementById('today' + user_id).checked;
        if (modulesData == true)
            today = document.getElementById('today' + user_id).value;
        var modulesData = document.getElementById('view_invoice_current_month' + user_id).checked;
        if (modulesData == true)
            view_invoice_current_month = document.getElementById('view_invoice_current_month' + user_id).value;
        var modulesData = document.getElementById('view_report_and_gst_return' + user_id).checked;
        if (modulesData == true)
            view_report_and_gst_return = document.getElementById('view_report_and_gst_return' + user_id).value;

        let comp_id = $("#comp_id").val();
        // console.log(new_module);
        if (comp_id) {
            if (!confirm('Are you sure to Assign the Company?')) return false;
            $.ajax({
                type: "POST",
                url: '<?= base_url("company_assign/assign_comp_to_user") ?>',
                data: {
                    only_user_display: only_user_display,
                    date_range: date_range,
                    today: today,
                    view_invoice_current_month: view_invoice_current_month,
                    view_report_and_gst_return: view_report_and_gst_return,
                    permission: permission,
                    comp_id: comp_id,
                    user_id: user_id,
                    user_assign_by_unique: user_assign_by_unique
                },
                dataType: 'json',
                success: function(data) {
                    // data = JSON.parse(data);
                    console.log(data);
                    if (data.status == true) {
                        toastr.success(data.data);
                        $("#adding_party_ok").modal("hide");

                    } else {
                        toastr.error(data.data);

                    }
                },
                error: function() {
                    alert("Server issue");
                }
            });
        } else {
            alert("Company Fields are Required");
        }
    }




    function SetValueofCheckbox(main_id) {
        let permission_id = $("#permission" + main_id).val();
        if (permission_id == 'Full') {
            var checkboxes = document.getElementsByClassName('findoneid' + main_id);
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = true;
                checkboxes[i].disabled = true;

            }
        } else {
            var checkboxes = document.getElementsByClassName('findoneid' + main_id);
            for (var i = 0; i < checkboxes.length; i++) {
                // checkboxes[i].checked = false;
                checkboxes[i].disabled = false;

            }

        }
    }

    // function AssignUsers() {
    //     let comp_id = $("#comp_id").val();
    //     // console.log(new_module);
    //     var selected = new Array();
    //     $(".cheking input[type=checkbox]:checked").each(function() {
    //         selected.push(this.value);
    //     })
    //     if (comp_id) {
    //         // if (!confirm('Are you sure to Assign the Company?')) return false;
    //         console.log(selected)
    //         $.ajax({
    //             type: "POST",
    //             url: '<?= base_url("company_assign/assign_comp_to_user_only_assign") ?>',
    //             data: {
    //                 assign_user: selected,
    //                 comp_id: comp_id
    //             },
    //             dataType: 'json',
    //             success: function(data) {
    //                 // data = JSON.parse(data);
    //                 console.log(data);
    //                 if (data.status == true) {
    //                     toastr.success("", data.data);
    //                     $("#adding_party_ok").modal("hide");

    //                 } else {
    //                     toastr.error("", data.data);

    //                 }
    //                 // setInterval(function() {
    //                 //     location.reload();
    //                 // }, 1000);

    //             },
    //             error: function() {
    //                 alert("Server issue");
    //             }

    //         });
    //     } else {
    //         alert("Company Fields are Required");
    //     }

    // }
</script>