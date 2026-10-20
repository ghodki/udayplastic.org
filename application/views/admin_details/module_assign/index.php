<style type="text/css">
    .form-control {
        border: none;
        border-bottom: 1px solid black;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid" style="margin-top: 3.5rem !important;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Module Assign</div>
                    <?php
                        if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                    <?php } ?>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-md-12 col-sm-6 col-lg-12">
                            <div class="form-group">
                                <button class="btn btn-primary" id="assign_user">
                                    Assign</b> to <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table id="module-table" class="table table-bordered table-hover table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="user-selectAll" /> Sr
                                        </th>
                                        <th style="text-align: left;">Users</th>
                                        <th>Access</th>
                                        <th>Side Menu</th>
                                        <th>File Sharing</th>
                                        <th>Inventory</th>
                                        <th>CRM</th>
                                        <th>Services</th>
                                        <th>Wallet</th>
                                        <th>Payment Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($module_assign) && !empty($module_assign)) {
                                            foreach ($module_assign as $key => $res) {
                                                $permission = 'Full';
                                    ?>
                                    <tr>
                                        <?php
                                            $company_result = $this->user_model->getRow('module_assign', ['user_id' => $res->id]);
                                            if ($company_result) {
                                                $cheked = 'checked';
                                                if ($company_result->permission == 'Full') {
                                                    $checkedModule = "checked disabled";
                                        ?>
                                        <td class="cheking">
                                            <input type="checkbox" name="assign_user[]" value="<?= $res->id; ?>" id="assign_user<?= $res->id; ?>" class="checkdata" <?= $cheked; ?> />
                                        </td>
                                        <th style="text-align: left;"><?= $res->name; ?></th>
                                        <td>
                                            <select name="permission[<?= $res->id; ?>]" id="permission<?= $res->id; ?>" class="form-control" onchange="SetValueofCheckbox(<?= $res->id; ?>);">
                                                <option value="Full" <?= $company_result->permission == 'Full' ? 'selected' : ''; ?>>Full</option>
                                                <option value="Custom" <?= $company_result->permission == 'Custom' ? 'selected' : ''; ?>>Custom</option>
                                            </select>
                                        </td>
                                        <td>                                         
                                            <input type="checkbox" name="side_menu[<?= $res->id; ?>]" value="side_menu" id="side_menu<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="file_sharing[<?= $res->id; ?>]" value="file_sharing" id="file_sharing<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>                                            
                                            <input type="checkbox" name="inventory[<?= $res->id; ?>]" value="inventory" id="inventory<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />                                           
                                        </td>
                                        <td>
                                            <input type="checkbox" name="crm[<?= $res->id; ?>]" value="crm" id="crm<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="services[<?= $res->id; ?>]" value="services" id="services<?= $res->id ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="wallet[<?= $res->id; ?>]" value="wallet" id="wallet<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="paymentreceipt[<?= $res->id; ?>]" value="paymentreceipt" id="paymentreceipt<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <?php } else { ?>
                                        <td class="cheking">
                                            <input type="checkbox" name="assign_user[]" value="<?= $res->id; ?>" id="assign_user<?= $res->id; ?>" class="checkdata" <?= $cheked; ?> />
                                        </td>
                                        <th style="text-align: left;"><?= $res->name; ?></th>
                                        <td>
                                            <select name="permission[<?= $res->id; ?>]" id="permission<?= $res->id; ?>" class="form-control" onchange="SetValueofCheckbox(<?= $res->id; ?>);">
                                                <option value="Full" <?= $company_result->permission == 'Full' ? 'selected' : ''; ?>>Full</option>
                                                <option value="Custom" <?= $company_result->permission == 'Custom' ? 'selected' : ''; ?>>Custom</option>
                                            </select>
                                        </td>
                                        <td>
                                            <?php if ($company_result->side_menu == 'side_menu') { $side_menu_selected = 'checked'; } else { $side_menu_selected = ''; } ?>
                                            <input class="form-check-input findoneid<?= $res->id; ?>" <?= $side_menu_selected; ?> type="checkbox" name="side_menu[<?= $res->id; ?>]" id="side_menu<?= $res->id ?>" value="side_menu">
                                        </td>
                                        <td>
                                            <?php if ($company_result->file_sharing == 'file_sharing') { $file_sharing_selected = 'checked'; } else { $file_sharing_selected = ''; } ?>
                                            <input class="form-check-input findoneid<?= $res->id; ?>" <?= $file_sharing_selected; ?> type="checkbox" name="file_sharing[<?= $res->id; ?>]" id="file_sharing<?= $res->id ?>" value="file_sharing">
                                        </td>
                                        <td>
                                            <?php if ($company_result->inventory == 'inventory') { $inventory_selected = 'checked'; } else { $inventory_selected = ''; } ?>
                                            <input class="form-check-input findoneid<?= $res->id; ?>" <?= $inventory_selected; ?> type="checkbox" name="inventory[<?= $res->id; ?>]" id="inventory<?= $res->id ?>" value="inventory">
                                        </td>
                                        <td>
                                            <?php if ($company_result->crm == 'crm') { $crm_selected = 'checked'; } else { $crm_selected = ''; } ?>
                                            <input class="form-check-input findoneid<?= $res->id; ?>" <?= $crm_selected; ?> type="checkbox" name="crm[<?= $res->id; ?>]" id="crm<?= $res->id; ?>" value="crm">
                                        </td>
                                        <td>
                                            <?php if ($company_result->services == 'services') { $services_selected = 'checked'; } else { $services_selected = ''; } ?>
                                            <input class="form-check-input findoneid<?= $res->id; ?>" <?= $services_selected; ?> type="checkbox" name="services[<?= $res->id; ?>]" id="services<?= $res->id ?>" value="services">
                                        </td>
                                        <td>
                                            <?php if ($company_result->wallet == 'wallet') { $wallet_selected = 'checked'; } else { $wallet_selected = ''; } ?>
                                            <input type="checkbox" name="wallet[<?= $res->id; ?>]" value="wallet" id="wallet<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $wallet_selected; ?> />
                                        </td>
                                        <td>
                                            <?php if ($company_result->paymentreceipt == 'paymentreceipt') { $paymentreceipt_selected = 'checked'; } else { $paymentreceipt_selected = ''; } ?>
                                            <input type="checkbox" name="paymentreceipt[<?= $res->id; ?>]" value="paymentreceipt" id="paymentreceipt<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $paymentreceipt_selected; ?> />
                                        </td>
                                        <?php } } else { $checkedModule = "checked disabled"; ?>
                                        <td class="cheking">
                                            <input type="checkbox" name="assign_user[]" value="<?= $res->id; ?>" id="assign_user<?= $res->id; ?>" class="checkdata" <?= $cheked; ?> />
                                        </td>
                                        <th style="text-align: left;"><?= $res->name; ?></th>
                                        <td>
                                            <select name="permission[<?= $res->id; ?>]" id="permission<?= $res->id; ?>" class="form-control" onchange="SetValueofCheckbox(<?= $res->id; ?>);">
                                                <option value="Full" <?= $permission == 'Full' ? 'selected' : ''; ?>>Full</option>
                                                <option value="Custom" <?= $permission == 'Custom' ? 'selected' : ''; ?>>Custom</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="side_menu[<?= $res->id; ?>]" value="side_menu" id="side_menu<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>                                            
                                            <input type="checkbox" name="file_sharing[<?= $res->id; ?>]" value="file_sharing" id="file_sharing<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />                                           
                                        </td>
                                        <td>
                                            <input type="checkbox" name="inventory[<?= $res->id; ?>]" value="inventory" id="inventory<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="crm[<?= $res->id; ?>]" value="crm" id="crm<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="services[<?= $res->id; ?>]" value="services" id="services<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="wallet[<?= $res->id; ?>]" value="wallet" id="wallet<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <td>
                                            <input type="checkbox" name="paymentreceipt[<?= $res->id; ?>]" value="paymentreceipt" id="paymentreceipt<?= $res->id; ?>" class="form-check-input findoneid<?= $res->id; ?>" <?= $checkedModule; ?> />
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
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
                checkboxes[i].disabled = false;
            }
        }
    }

    $(function() {
        $("#assign_user").click(function() {
            var selected = new Array();
            var allData = new Array();
            var obj = {};
            i = 0;
            $(".cheking input[type=checkbox]:checked").each(function() {
                var obj = {};
                obj['user_id'] = this.value;
                obj['permission'] = $("#permission" + this.value).val();

                var modulesData = document.getElementById('side_menu' + this.value).checked;
                if (modulesData == true) {
                    obj['side_menu'] = document.getElementById('side_menu' + this.value).value;
                } else {
                    obj['side_menu'] = '';
                }

                var modulesData = document.getElementById('file_sharing' + this.value).checked;
                if (modulesData == true) {
                    obj['file_sharing'] = document.getElementById('file_sharing' + this.value).value;
                } else {
                    obj['file_sharing'] = '';
                }
                
                var modulesData = document.getElementById('inventory' + this.value).checked;
                if (modulesData == true) {
                    obj['inventory'] = document.getElementById('inventory' + this.value).value;
                } else {
                    obj['inventory'] = '';
                }
                
                var modulesData = document.getElementById('crm' + this.value).checked;
                if (modulesData == true) {
                    obj['crm'] = document.getElementById('crm' + this.value).value;
                } else {
                    obj['crm'] = '';
                }

                var modulesData = document.getElementById('services' + this.value).checked;
                if (modulesData == true) {
                    obj['services'] = document.getElementById('services' + this.value).value;
                } else {
                    obj['services'] = '';
                }

                var modulesData = document.getElementById('wallet' + this.value).checked;
                if (modulesData == true) {
                    obj['wallet'] = document.getElementById('wallet' + this.value).value;
                } else {
                    obj['wallet'] = '';
                }
                
                var modulesData = document.getElementById('paymentreceipt' + this.value).checked;
                if (modulesData == true) {
                    obj['paymentreceipt'] = document.getElementById('paymentreceipt' + this.value).value;
                } else {
                    obj['paymentreceipt'] = '';
                }

                selected.push(obj);
            });
            
            if (!confirm('Are you sure to Assign the Module to users?')) return false;
            $.ajax({
                type        :   "POST",
                url         :   '<?= base_url("company_assign/module_assign"); ?>',
                data        :   {allData: selected},
                dataType    :   'JSON',
                success     :   function(data) {
                                    console.log(data);
                                    if (data.status == true) {
                                        toastr.success(data.data);
                                    } else {
                                        toastr.error(data.data);
                                    }
                                }
            });
        });
    });

    $("#user-selectAll").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    $(document).ready(function() {
        $("#module-table").DataTable({
            dom: 'Bfrtip',
            buttons: ['pageLength'],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            autoWidth: false
        });
    });
</script>