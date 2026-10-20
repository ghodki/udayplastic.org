<style type="text/css">
    .header label {
        color: #ffffff;
    }
    th, td {
        white-space: nowrap;
    }
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    <div class="header">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h3 style="color: #ffffff; padding: 5px 0px 5px 0px;" class="text-center">
                                    Add Ledger
                                </h3>                               
                                <form method="POST" action="<?= base_url(); ?>ladger/addledger">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="accounting_group">Accounting Group <span class="text-danger">*</span></label>
                                                <select id="accounting_group" name="accounting_group" class="form-control form-control-sm" required="" title="Account Group">
                                                    <option selected="" disabled="" value="">Select Account Group</option>
                                                    <?php $accounting_group = $this->user_model->getResult('accounting_group'); if (isset($accounting_group) && !empty($accounting_group)) { foreach ($accounting_group as $data) { ?>
                                                    <option value="<?= $data->id; ?>" <?= set_select('accounting_group', $data->id); ?>><?= $data->name; ?></option>
                                                    <?php } } ?>
                                                </select>
                                                <span class="text-danger">
                                                    <?= form_error('accounting_group'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="name">Ledger Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="<?= set_value('name'); ?>" class="form-control form-control-sm" placeholder="Ledger Name" required="" />
                                                <span class="text-danger">
                                                    <?= form_error('name'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="ContactPerson">Contact Person <span class="text-danger">*</span></label>
                                                <input type="text" name="ContactPerson" value="<?= set_value('ContactPerson'); ?>" class="form-control form-control-sm" placeholder="Contact Person" required="" />
                                                <span class="text-danger"><?= form_error('ContactPerson'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="Mobile">Mobile <span class="text-danger">*</span></label>
                                                <input type="tel" name="Mobile" value="<?= set_value('Mobile'); ?>" class="form-control form-control-sm" placeholder="Mobile" required="" minlength="10" maxlength="10" />
                                                <span class="text-danger"><?= form_error('Mobile'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="Email">Email</label>
                                                <input type="text" name="Email" value="<?= set_value('Email'); ?>" class="form-control form-control-sm" placeholder="Email" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="Address">Address</label>
                                                <textarea name="Address" class="form-control form-control-sm" placeholder="Address" rows="1"><?= set_value('Address'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="Area">Area</label>
                                                <input type="text" name="Area" value="<?= set_value('Area'); ?>" class="form-control form-control-sm" placeholder="Area" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="opening_balance">Opening Balance</label>
                                                <input type="number" name="opening_balance" value="<?= set_value('opening_balance'); ?>" class="form-control form-control-sm" min="0" placeholder="Opening Balance" required="" />
                                                <span class="text-danger">
                                                    <?= form_error('opening_balance'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="type">Ledger Type <span class="text-danger">*</span></label>
                                                <select name="type" id="type" class="form-control form-control-sm" title="Ledger Type" required="">
                                                    <option selected="" disabled="" value="">Select Ledger Type</option>
                                                    <option <?= set_select('type', 'CR'); ?> value="CR">CR</option>
                                                    <option <?= set_select('type', 'DR'); ?> value="DR">DR</option>
                                                </select>
                                                <span class="text-danger">
                                                    <?= form_error('type'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group text-center mt-2 mb-2">
                                                <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    Save
                                                </button>
                                                <?php } else { ?>
                                                <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                                <?php } } else { ?>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    Save
                                                </button>
                                                <?php } ?>
                                                <input type="reset" class="btn btn-danger btn-sm" placeholder="Reset">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12 pt-1">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-primary">Ledger List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">Sr</th>
                                            <th class="text-white">Date</th>
                                            <th class="text-white">Accounting Group</th>
                                            <th class="text-white">Ledger Name</th>
                                            <th class="text-white">Contact Person</th>
                                            <th class="text-white">Opening Balance</th>
                                            <th class="text-white">Ledger Type</th>
                                            <th class="text-white"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($Ledgers) && $Ledgers != '') { 
                                                $n = 0;
                                                foreach ($Ledgers as $key => $ddata) {
                                                    $n++;
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= date('d-m-Y', strtotime($ddata->date)); ?></td>
                                            <td><?= get_title('accounting_group', ['id' => $ddata->accounting_group], 'name'); ?></td>
                                            <td><?= $ddata->name; ?></td>
                                            <td>
                                                <?= isset($ddata->ContactPerson) ? $ddata->ContactPerson : ''; ?>
                                                <br />
                                                <small>(<?= isset($ddata->Mobile) ? $ddata->Mobile : ''; ?>)</small>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= $ddata->opening_balance; ?></td>
                                            <td><?= $ddata->type; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="LedgerInfo(<?= $ddata->id; ?>);" class="btn btn-primary btn-sm" title="Info">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                                <?php if (!empty($this->session->userdata('RoleType'))) { if (json_decode($EmployeePermission->Ledger)->Edit == "Edit") { ?>
                                                <a href="<?= base_url(); ?>Employee/LedgerEdit/<?= $ddata->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <?php } if (json_decode($EmployeePermission->Ledger)->Delete == "Delete") { ?>
                                                <a href="<?= base_url(); ?>ladger/delete/<?= $ddata->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure want to delete ladger');" title="Delete">
                                                    <i class="fa fa-trash text-white"></i>
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?>ladger/edit/<?= $ddata->id ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>ladger/delete/<?= $ddata->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure want to delete ladger');" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>                                                
                                                <?php } ?>
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
</div>

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
    
    const LedgerInfo = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Ladger/Info/" + Id);
        $('#Info').modal('show');
    };
</script>