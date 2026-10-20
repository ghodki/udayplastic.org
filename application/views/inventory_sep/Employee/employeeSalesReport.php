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
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-navy">
                                    Employee Sales Report
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/employeeSales" class="btn bg-navy btn-sm pull-right" title="Create">
                                <i class="fa fa-plus-circle text-white"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <form action="<?= base_url(); ?>Employee/employeeSalesReport" method="GET">
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
                                                <button type="submit" class="btn btn-sm bg-navy">
                                                    <i class="fa fa-search text-white"></i>
                                                </button>
                                                <a href="<?= base_url(); ?>Employee/employeeSalesReport" class="btn btn-default btn-sm" title="Refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-navy">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">Date</th>
                                                    <th class="text-white">Name</th>
                                                    <th class="text-white">Location</th>
                                                    <th class="text-white">Month</th>
                                                    <th class="text-white">Total</th>
                                                    <th class="text-white"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($employeeSales) && !empty($employeeSales)) {
                                                        $sr = 0;
                                                        $totalAmount = 0;
                                                        foreach ($employeeSales as $key => $sales) {
                                                            $sr++;
                                                            $totalAmount += $sales->grandTotal;
                                                ?>
                                                <tr>
                                                    <td><?= $sr; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($sales->date)); ?></td>
                                                    <td><?= get_title('employees', ['Id' => $sales->employeeId], 'Name'); ?></td>
                                                    <td><?= $sales->baseLocation; ?></td>
                                                    <td><?= $sales->month; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sales->grandTotal); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn bg-navy btn-sm" onclick="employeeSalesInfo(<?= $sales->id; ?>);" title="Info">
                                                            <i class="fa fa-info-circle text-white"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>Employee/employeeSalesReportDownload/<?= $sales->id; ?>" class="btn btn-primary btn-sm" title="Download">
                                                            <i class="fa fa-file-excel text-white"></i>
                                                        </a>
                                                        <?php if ($sales->date == date('Y-m-d')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/employeeSalesEdit/<?= $sales->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                            <i class="fa fa-edit text-white"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>Employee/employeeSalesDelete/<?= $sales->id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                            <i class="fa fa-trash text-white"></i>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" style="text-align: right;">Total</th>
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
    
    const employeeSalesInfo = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/employeeSalesInfo/" + Id);
        $('#Info').modal('show');
    };
</script>