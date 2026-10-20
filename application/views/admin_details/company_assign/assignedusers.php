<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header" style="padding-top: 4rem;">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Assigned Users</div>
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <table class="table table-bordered table-hover table-striped Datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Company Name</th>
                                        <th>GST No</th>
                                        <th>Email & Mobile</th>
                                        <th>Person</th>
                                        <th>Code & State</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($companys) && !empty($companys)) {
                                            $n = 0;
                                            foreach ($companys as $key => $value) {
                                                $n++;
                                    ?>
                                    <tr>
                                        <td><?= $n; ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" herf="javascript:void(0);" title="Assign users list" onclick="viewCompanyUsers('<?= $value->comp_id; ?>');">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td><?= $value->company_name; ?></td>
                                        <td><?= $value->gst_no; ?></td>
                                        <td>
                                            <?= $value->email; ?>
                                            <br />
                                            <?= $value->number; ?>
                                        </td>
                                        <td><?= $value->contact_person; ?></td>
                                        <td>
                                            <?= get_title('state_list', ['id' => $value->state], 'code'); ?>-<?= get_title('state_list', ['id' => $value->state], 'state'); ?>
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
</div>

<div class="modal fade" id="companyusers" role="dialog" aria-labelledby="companyuserslabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="companyuserslabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include_once('script.php'); ?>