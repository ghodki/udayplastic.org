<style type="text/css">
    .ui-tooltip {
        will-change: transform;
        position: absolute;
        top: 0px;
        left: 0px;
        background-color: #000000;
        padding: 5px;
        color: #ffffff;
        border-radius: 5px;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 col-xl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xxl-12 col-xl-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-primary">
                                    Product Enquiry Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="<?= base_url(); ?>ProductEnquiryReport">
                                <div class="row mb-2">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 col-xxl-2 col-xl-2">
                                        <input type="date" name="From" value="<?= set_value('From', $this->input->get('From') ? $this->input->get('From') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 col-xxl-2 col-xl-2">
                                        <input type="date" name="To" value="<?= set_value('To', $this->input->get('To') ? $this->input->get('To') : date('Y-m-d')); ?>" id="To" class="form-control form-control-sm" title="To Date" />
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 col-xxl-2 col-xl-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>ProductEnquiryReport" class="btn btn-danger btn-sm">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Organization</th>
                                            <th>Contact Person</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Created</th>
                                            <th>Remark</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($ProductEnquiries) && $ProductEnquiries != '') {
                                                $Sr = 0;
                                                foreach ($ProductEnquiries as $key => $data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $data->Organization; ?></td>
                                            <td><?= $data->ContactPerson; ?></td>
                                            <td><?= $data->Mobile; ?></td>
                                            <td><?= $data->Email; ?></td>
                                            <td><?= $data->ProductName; ?></td>
                                            <td><?= $data->Quantity; ?></td>
                                            <td><?= $data->Created; ?></td>
                                            <td data-toggle="tooltip" data-placement="top" title="<?= $data->Remark; ?>">
                                                <?= substr($data->Remark, 0, 10); ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url(); ?>Master/ProductEnquiryDelete/<?= $data->Id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
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

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'pageLength']
        });
    });
</script>