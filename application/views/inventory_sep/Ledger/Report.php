<style type="text/css">
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
                <div class="col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <div class="card card-outline card-fuchsia">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-fuchsia">
                                    Ledger Entries Report
                                </h5>
                            </div>
                            <?php if ($this->session->userdata('RoleType')) { $SearchAction = base_url() . 'Employee/LedgerReport'; ?>
                            <a href="<?= base_url(); ?>Employee/LedgerEntries" class="btn btn-primary btn-sm pull-right">
                                <i class="fa fa-plus-circle text-white"></i> Create
                            </a>
                            <?php } else { $SearchAction = base_url() . 'Ledger/Report'; ?>
                            <a href="<?= base_url(); ?>Ledger" class="btn btn-primary btn-sm pull-right">
                                <i class="fa fa-plus-circle"></i> Create
                            </a>
                            <?php } ?>
                        </div>
                        <div class="card-body">
                            <form action="<?= $SearchAction; ?>" method="GET">
                                <div class="row mb-2">
                                    <div class="form-group col-md-4 col-lg-4 col-sm-4">
                                        <select name="LedgerId" id="LedgerId" class="form-control form-control-sm" title="Ledger">
                                            <option <?= $this->input->get('LedgerId') == "" ? "selected" : ""; ?> value="" disabled="">Select Ledger</option>
                                            <?php
                                                if ($Ledgers) {
                                                    foreach ($Ledgers as $key => $Ledger) {
                                                        $AccountGroupName = "(" . get_title('accounting_group', ['id' => $Ledger->accounting_group], 'name') . ")";
                                            ?>
                                            <option <?= $this->input->get('LedgerId') == $Ledger->id ? "selected" : ""; ?> value="<?= $Ledger->id; ?>" <?= set_select('LedgerId', $Ledger->id); ?>><?= $AccountGroupName; ?> <?= $Ledger->name; ?>, <?= $Ledger->ContactPerson; ?>, <?= $Ledger->Mobile; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-lg-2 col-sm-2">
                                        <input type="date" name="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" onkeydown="firstRow(event);" id="fromDate" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" required="" />
                                    </div>
                                    <div class="form-group col-md-2 col-lg-2 col-sm-2">
                                        <input type="date" name="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" onclick="enterKeyPressed(event);" onkeydown="firstRow(event);" id="toDate" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-lg-2 col-sm-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <?php if ($this->session->userdata('RoleType')) { ?>
                                        <a href="<?= base_url(); ?>Employee/LedgerReport" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh text-white"></i>
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?= base_url(); ?>Ledger/Report" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh text-white"></i>
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-condensed table-sm table-bordered">
                                    <thead class="bg-fuchsia">
                                        <tr>
                                            <th class="text-white">Sr</th>
                                            <th class="text-white">Date</th>
                                            <th class="text-white">Account</th>
                                            <th class="text-white">Ledger</th>
                                            <th class="text-white">Amount</th>
                                            <th class="text-white">Type</th>
                                            <th class="text-white">Mode</th>
                                            <th class="text-white"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($LedgersEntries) {
                                                $Sr = 0;
                                                $Amount = 0;
                                                foreach ($LedgersEntries as $key => $Data) {
                                                    $Sr++;
                                                    $Amount += $Data->Amount;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->Date)); ?></td>
                                            <td><?= get_title('accounting_group', ['id' => $Data->AccountGroupId], 'name'); ?></td>
                                            <td><?= get_title('ladger', ['id' => $Data->LedgerId], 'name'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($Data->Amount, 2); ?></td>
                                            <td><?= $Data->Type; ?></td>
                                            <td><?= get_title('payment_mode', ['id' => $Data->PaymentMode], 'payment_method'); ?></td>
                                            <th>
                                                <a href="javascript:void(0);" onclick="LedgerInfo(<?= $Data->Id; ?>);" class="btn btn-primary btn-sm" title="Info">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                                <?php if (!empty($this->session->userdata('RoleType'))) { if (json_decode($EmployeePermission->Ledger)->Edit == "Edit") { ?>
                                                <a href="<?= base_url(); ?>Employee/LedgerEntryEdit/<?= $Data->Id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <?php } if (json_decode($EmployeePermission->Ledger)->Delete == "Delete") { ?>
                                                <a href="<?= base_url(); ?>Ledger/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash text-white"></i>
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?>Ledger/Edit/<?= $Data->Id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Ledger/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash text-white"></i>
                                                </a>
                                                <?php } ?>
                                            </th>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Amount, 2); ?></th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer"></div>
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
        $("table").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
    
    const LedgerInfo = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Ledger/Info/" + Id);
        $('#Info').modal('show');
    };
</script>