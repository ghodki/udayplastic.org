<style type="text/css">
    .border-top {
        border-top: 2px solid #070809 !important;
    }    
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="header-title">
                                    Sale Invoice Edit History Report
                                </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="from">From</label>
                                        <input type="date" name="from" value="<?= $this->input->get('from') ? $this->input->get('from') : date('Y-m-01'); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group">
                                        <label for="to">To</label>
                                        <input type="date" name="to" value="<?= $this->input->get('to') ? $this->input->get('to') : date('Y-m-d'); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="col-md-2 col-sm-4 form-group" style="margin-top: 30px;">
                                        <button type="submit" class="btn btn-sm bg-purple">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>reports/saleInoviceEditHistory" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Invoice Number</th>
                                            <th>Edited By</th>
                                            <th>Edited Date Time</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($saleInvoiceHistory) && !empty($saleInvoiceHistory)) {
                                                $sr = 0;
                                                foreach ($saleInvoiceHistory as $key => $data) {
                                                    $sr++;
                                        ?>
                                        <tr>
                                            <td><?= $sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                                            <td><?= get_title('sales', ['id' => $data->invoiceId], 'invoiceNumber'); ?></td>
                                            <td><?= $data->editedBy; ?></td>
                                            <td><?= $data->editedDateTime; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="saleInvoiceEditHistoryInfo(<?= $data->invoiceId; ?>);" class="btn btn-primary btn-sm" title="More Info">
                                                    <i class="fa fa-info-circle text-white"></i>
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
    </div>
 </div>


<div class="modal fade" id="info-modal" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
    
    const saleInvoiceEditHistoryInfo = (invoiceId) => {
        $("#info-modal .modal-dialog .modal-content").load("<?= base_url(); ?>reports/saleInvoiceEditHistoryInfo/" + invoiceId);
        $('#info-modal').modal('show');
    };
</script>