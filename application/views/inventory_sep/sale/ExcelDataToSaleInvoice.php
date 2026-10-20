<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-primary">
                                    Excel Data to Sale Invoice
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>sample/InvoiceSample.xlsx" class="btn btn-primary btn-sm pull-right" title="Sample Excel File For Sale Invoice" download="">
                                <i class="fa fa-download"></i> Sample
                            </a>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?= base_url(); ?>Sale/CreateSaleInvoices" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xxl-4 col-xl-4">
                                        <div class="form-group">
                                            <label for="ExcelSaleData">Choose Excel File <span class="text-danger">*</span></label>
                                            <input type="file" name="ExcelSaleData" id="ExcelSaleData" class="form-control form-control-sm" title="Choose Excel File" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required="" />
                                            <span class="text-danger"><?= form_error('ExcelSaleData'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xxl-4 col-xl-4">
                                        <div class="form-group" style="margin-top: 28px;">
                                            <input type="submit" class="btn btn-success btn-sm" value="Import Excel Data" />
                                            <input type="reset" class="btn btn-danger btn-sm" value="Reset" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Customers</th>
                                            <th>Invoices</th>
                                            <th>Products</th>
                                            <th>Invoice Products</th>
                                            <th>Payments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>New : <?= isset($NewCustomers) ? (int) $NewCustomers : 0; ?></td>
                                            <td>New : <?= isset($NewInvoices) ? (int) $NewInvoices : 0; ?></td>
                                            <td>New : <?= isset($NewProducts) ? (int) $NewProducts : 0; ?></td>
                                            <td>New : <?= isset($NewInvoiceProducts) ? (int) $NewInvoiceProducts : 0; ?></td>
                                            <td>New : <?= isset($NewPayments) ? (int) $NewPayments : 0; ?></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">
                                                <a href="<?= base_url(); ?>Sale/sale_report" class="btn btn-primary btn-sm" titile="View Sale Invoices">
                                                    View Sale Invoices
                                                </a>
                                            </th>
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