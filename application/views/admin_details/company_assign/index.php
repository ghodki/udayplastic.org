<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid" style="margin-top:3.5rem!important;">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php } ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Company Assign</div>
                </div>
                <div class="card-body card">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <table id="product-table" class="table table-bordered table-hover table-striped Datatable dtr-column text-center" cellspacing="0" width="100%" aria-describedby="product-table_info" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th>Sr No</th>
                                        <th>Company Name</th>
                                        <th>USER</th>
                                        <th>State</th>
                                        <th>GST</th>
                                        <th>Number</th>
                                        <th>SENT SMS</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($company_assign) && !empty($company_assign)) {
                                            foreach ($company_assign as $key => $value) {
                                    ?>
                                    <tr>
                                        <td><?= $key + 1; ?></td>
                                        <td>
                                            <a href="<?= base_url(); ?>userlist/loginForInventory/<?= $value->user_id; ?>/<?= $value->id; ?>">
                                                <?= $value->company_name; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?= base_url() ?>company_assign/mobile_number_report/<?= $value->user_id; ?>">
                                                <?= get_title('users', ['id' => $value->user_id], 'name'); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= get_title('state_list', ['id' => $value->state], 'state'); ?>
                                        </td>
                                        <td><?= $value->gst_no; ?></td>
                                        <td><?= $value->number;; ?></td>
                                        <td>
                                            <?php
                                                $this->db->select('*');
                                                $this->db->from('sales');
                                                $this->db->where('comp_id', $value->id);
                                                $this->db->where('user_id', $value->user_id);
                                                $this->db->where(['customerMobile!=' => '']);
                                                $result = $this->db->get()->result();
                                                echo count($result);
                                            ?>
                                        </td>
                                        <td>
                                            <a herf="javascript:void(0);" class="text-primary fr mr-1 addPatys" title="Assign Company" onclick="assignValue(<?= $value->id; ?>);" style="cursor: pointer;">
                                                <i style="margin-left: 6px;" class="fa fa-lg fa-plus-circle"></i>
                                            </a>
                                            &nbsp;
                                            <a herf="javascript:void(0);" class="text-danger" title="Block Sms" onclick="blockSms(<?= $value->id; ?>);" style="cursor: pointer;">
                                                <i class="fa fa-sms"></i>
                                            </a>
                                        </td>
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
    
    <div class="modal fade" id="adding_party_ok" tabindex="-1" role="dialog" aria-labelledby="addparty" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addparty"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="sms_block" tabindex="-1" role="dialog" aria-labelledby="sms_block" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sms_block_title"></h5>
                    <!-- <input type="hidden" id="rownumber"> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
</div>

<?php include_once('script.php'); ?>