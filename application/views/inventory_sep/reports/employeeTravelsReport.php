<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-danger">
                                    Employee Travels Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <form action="<?= base_url(); ?>Reports/employeeTravelsReport" method="GET">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xl-2 col-lg-2 col-xxl-2 col-xs-2">
                                                <div class="form-group">
                                                    <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : date('Y-m-d')); ?>" id="from" class="form-control form-control-sm" title="From" />
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                                <div class="form-group">
                                                    <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : date('Y-m-d')); ?>" id="to" class="form-control form-control-sm" title="To" />
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                                <div class="form-group">
                                                    <select name="employeeId" id="employeeId" class="form-control form-control-sm" title="Selesman">
                                                        <option value="All" <?= $this->input->get('employeeId') == 'All' ? 'selected' : ''; ?>>All</option>
                                                        <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $salesman) { ?>
                                                        <option value="<?= $salesman->Id; ?>" <?= $this->input->get('employeeId') == $salesman->Id ? 'selected' : ''; ?>><?= $salesman->Name; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-search text-white"></i>
                                                </button>
                                                <a href="<?= base_url(); ?>Reports/employeeTravelsReport" class="btn btn-danger btn-sm" title="Refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-danger">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">Date</th>
                                                    <th class="text-white">Location/Place of posting</th>
                                                    <th class="text-white">Month</th>
                                                    <th class="text-white">Total</th>
                                                    <th class="text-white"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sr = 0;
                                                    $totalAmount = 0;
                                                    if (isset($employeeTravels) && !empty($employeeTravels)) {
                                                        foreach ($employeeTravels as $key => $travels) {
                                                            $sr++;
                                                            $rowTotal = (float) ($travels->grandTotal ?? 0);
                                                            $totalAmount += $rowTotal;
                                                ?>
                                                <tr>
                                                    <td><?= $sr; ?></td>
                                                    <td><?= !empty($travels->date) ? date('d-m-Y', strtotime($travels->date)) : ''; ?></td>
                                                    <td><?= $travels->location; ?> - <?= get_title('state_list', ['id' => $travels->stateId], 'state'); ?></td>
                                                    <td><?= $travels->month; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rowTotal); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn bg-navy btn-sm" onclick="employeeTravelsInfo();" title="Info">
                                                            <i class="fa fa-info-circle text-white"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>Reports/employeeTravelsReportDownload?from=<?= $this->input->get('from') ? $this->input->get('from') : date('Y-m-d'); ?>&to=<?= $this->input->get('to') ? $this->input->get('to') : date('Y-m-d'); ?>&employeeId=<?= $this->input->get('employeeId') ? $this->input->get('employeeId') : 'All'; ?>" class="btn btn-primary btn-sm" title="Download">
                                                            <i class="fa fa-file-excel text-white"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>Reports/employeeTravelsEdit?from=<?= $this->input->get('from') ? $this->input->get('from') : date('Y-m-d'); ?>&to=<?= $this->input->get('to') ? $this->input->get('to') : date('Y-m-d'); ?>&employeeId=<?= $this->input->get('employeeId') ? $this->input->get('employeeId') : 'All'; ?>" class="btn btn-success btn-sm" title="Edit">
                                                            <i class="fa fa-edit text-white"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>Reports/employeeTravelsDelete?from=<?= $this->input->get('from') ? $this->input->get('from') : date('Y-m-d'); ?>&to=<?= $this->input->get('to') ? $this->input->get('to') : date('Y-m-d'); ?>&employeeId=<?= $this->input->get('employeeId') ? $this->input->get('employeeId') : 'All'; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                            <i class="fa fa-trash text-white"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" style="text-align: right;">Total</th>
                                                    <th colspan="2"><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
    
    const employeeTravelsInfo = () => {
        var from = $("#from").val();
        var to = $("#to").val();
        var employeeId = $("#employeeId").val();        
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/employeeTravelsInfo?from="+ from +"&to="+ to + "&employeeId="+ employeeId);
        $('#Info').modal('show');
    };
</script>