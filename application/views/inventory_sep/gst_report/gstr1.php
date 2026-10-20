<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-navy">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-navy">
                                    GSTR-1 for <?= date('d-m-Y', strtotime($start_date)); ?> - <?= date('d-m-Y', strtotime($end_date)); ?> - Details of outward supplies of goods or services
                                </h4>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-primary btn-sm" title="Excel Download" target="_blank" href="<?= base_url(); ?>Gst_report/all_excel/<?= $start_date; ?>/<?= $end_date; ?>">
                                    <i class="fa fa-file-excel-o fa-lg"></i> Download
                                </a>
                                <a class="btn btn-success btn-sm" target="_blank" href="<?= base_url(); ?>Gst_report/all_json/<?= $start_date; ?>/<?= $end_date; ?>" title="JSON Download" id="json_download">
                                    <i class="fa fa-file-code-o fa-lg"></i> Download
                                </a>
                                <a href="<?= base_url(); ?>Gst_report" class="btn btn-sm bg-navy" title="Back">
                                    <i class="fa fa-angle-double-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h1 class="text-center text-navy">GSTR-1 Invoice Details</h1>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>SN</th>
                                            <th>Invoices</th>
                                            <th>Total Taxable Value:</th>
                                            <th>Total Tax</th>
                                            <th>Total Value</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>4A, 4B, 4C, 6B, 6C - B2B Invoices</td>
                                            <?php
                                                $this->db->select(['SUM(total) as stotalValue', 'SUM(taxableAmount) as staxableAmount', 'SUM(totalTax) as stotalTax']);
                                                $this->db->where(['invoiceDate >=' => $start_date]);
                                                $this->db->where(['invoiceDate <=' => $end_date]);
                                                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                                                if ($this->session->userdata('BranchRole')) {
                                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                }
                                                $this->db->where(['gstNo!=' => '']);
                                                $totals_data = $this->db->get('sales')->result();
                                                // with gst number =>4a,4b,4c,6b,6c,b2b
                                            ?>
                                            <td><?= $totals_data[0]->staxableAmount ? indianMoneyFormat($totals_data[0]->staxableAmount) : 0.00 ?></td>
                                            <td><?= $totals_data[0]->stotalTax ? indianMoneyFormat($totals_data[0]->stotalTax) : 0.00 ?></td>
                                            <td><?= $totals_data[0]->stotalValue ? indianMoneyFormat($totals_data[0]->stotalValue) : 0.00 ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>gst_report/b2b_report/<?= $start_date ?>/<?= $end_date ?>" target="_blank" title="B2B EXCEL Download" id="b2b_excel" class="btn btn-dark btn-xs excel">
                                                    <i class="fa fa-file-excel-o"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>gst_report/b2b_json_report/<?= $start_date ?>/<?= $end_date ?>" target="_blank" title="B2B JSON Download" id="b2b_json" class="btn btn-danger btn-xs json">
                                                    <i class="fa fa-file-code-o"></i>
                                                </a>
                                                <a title="B2B View" id="b2b_view_a" class="btn btn-primary btn-xs view">
                                                    <i class="fa fa-table" onclick="b2b_invoice()"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>B2C(Small) Invoice -7</td>
                                            <?php
                                                $this->db->select(['SUM(total) as stotalValue', 'SUM(taxableAmount) as staxableAmount', 'SUM(totalTax) as stotalTax']);
                                                $this->db->where(['invoiceDate >=' => $start_date]);
                                                $this->db->where(['invoiceDate <=' => $end_date]);
                                                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                                                if ($this->session->userdata('BranchRole')) {
                                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                }
                                                $this->db->where(['gstNo' => '']);
                                                $totals_data_without_gstNo = $this->db->get('sales')->result();
                                                // with gst number =>4a,4b,4c,6b,6c,b2b
                                            ?>
                                            <td><?= $totals_data_without_gstNo[0]->staxableAmount ? indianMoneyFormat($totals_data_without_gstNo[0]->staxableAmount) : 0.00 ?></td>
                                            <td><?= $totals_data_without_gstNo[0]->stotalTax ? indianMoneyFormat($totals_data_without_gstNo[0]->stotalTax) : 0.00 ?></td>
                                            <td><?= $totals_data_without_gstNo[0]->stotalValue ? indianMoneyFormat($totals_data_without_gstNo[0]->stotalValue) : 0.00 ?></td>
                                            <td>
                                                <a href="<?= base_url() ?>gst_report/b2c_small/<?= $start_date ?>/<?= $end_date ?>" target="_blank" title="B2C EXCEL Download" id="b2c_small_excel" class="btn btn-dark btn-xs excel">
                                                    <i class="fa fa-file-excel-o"></i>
                                                </a>
                                                <a href="<?= base_url() ?>gst_report/b2c_small_json_report/<?= $start_date ?>/<?= $end_date ?>" title="B2C Small JSON Download" target="_blank" id="b2c_small_json" class="btn btn-danger btn-xs json">
                                                    <i class="fa fa-file-code-o"></i>
                                                </a>
                                                <a href="javascript:void(0);" title="B2C(Small) View" id="b2c_small_view" class="btn btn-primary btn-xs view">
                                                    <i class="fa fa-table" onclick="b2c_small_invoice()"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>12 - HSN-wise summary of outward supplies</td>
                                            <?php
                                                $this->db->select(['SUM(total) as stotalValue', 'SUM(taxableAmount) as staxableAmount', 'SUM(totalTax) as stotalTax']);
                                                $this->db->where(['invoiceDate >=' => $start_date]);
                                                $this->db->where(['invoiceDate <=' => $end_date]);
                                                $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                                                if ($this->session->userdata('BranchRole')) {
                                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                }
                                                $totals_data_without_gstNo = $this->db->get('sales')->result();
                                                // with gst number =>4a,4b,4c,6b,6c,b2b
                                            ?>
                                            <td><?= $totals_data_without_gstNo[0]->staxableAmount ? indianMoneyFormat($totals_data_without_gstNo[0]->staxableAmount) : 0.00 ?></td>
                                            <td><?= $totals_data_without_gstNo[0]->stotalTax ? indianMoneyFormat($totals_data_without_gstNo[0]->stotalTax) : 0.00 ?></td>
                                            <td><?= $totals_data_without_gstNo[0]->stotalValue ? indianMoneyFormat($totals_data_without_gstNo[0]->stotalValue) : 0.00 ?></td>
                                            <td>
                                                <a href="<?= base_url() ?>gst_report/hsn/<?= $start_date ?>/<?= $end_date ?>" target="_blank" title="HSN EXCEL Download" id="hsn_excel" class="btn btn-dark btn-xs excel">
                                                    <i class="fa fa-file-excel-o"></i>
                                                </a>
                                                <a href="<?= base_url() ?>gst_report/hsn_json_report/<?= $start_date ?>/<?= $end_date ?>" title="HSN JSON Download" target="_blank" id="hsn_json" class="btn btn-danger btn-xs json">
                                                    <i class="fa fa-file-code-o"></i>
                                                </a>
                                                <a href="javascript:void(0);" title="HSN View" id="hsn_view" class="btn btn-primary btn-xs view">
                                                    <i class="fa fa-table" onclick="hsn_invoice()"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr class="text-center">
                                            <td colspan="6">DOCUMENT SUMMARY</td>
                                        </tr>
                                        <tr>
                                            <th>Starting Invoice Number</th>
                                            <th>Ending Invoice Number</th>
                                            <th>Total Invoice Number</th>
                                            <th>Cancel Invoice Number</th>
                                            <th>Net Issued</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                    $this->db->select(['invoiceNumber']);
                                                    $this->db->where(['invoiceDate >=' => $start_date]);
                                                    $this->db->where(['invoiceDate <=' => $end_date]);
                                                    $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year'), 'status' => 1]);
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                    }
                                                    $this->db->order_by('id', 'asc');
                                                    $find_stating_invoice = $this->db->get('sales')->row();
                                                    $starting_invoice_number = $find_stating_invoice->invoiceNumber;
                                                ?>
                                                <?= $starting_invoice_number ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $this->db->select(['invoiceNumber']);
                                                    $this->db->where(['invoiceDate >=' => $start_date]);
                                                    $this->db->where(['invoiceDate <=' => $end_date]);
                                                    $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year'), 'status' => 1]);
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                    }
                                                    $this->db->order_by('id', 'desc');
                                                    $endwalainvooice = $this->db->get('sales')->row();
                                                    $ending_inoive = $endwalainvooice->invoiceNumber;
                                                ?>
                                                <?= $ending_inoive ?>
                                            </td>
                                            <td onclick="document_report()">
                                                <?php
                                                    $this->db->select(['count(*) as total_sale']);
                                                    $this->db->where(['invoiceDate >=' => $start_date]);
                                                    $this->db->where(['invoiceDate <=' => $end_date]);
                                                    $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year'), 'status' => 1]);
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                    }
                                                    $total_sales = $this->db->get('sales')->row();
                                                ?>
                                                <?= $total_sales->total_sale; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $this->db->select(['count(*) as total_sale']);
                                                    $this->db->where(['invoiceDate >=' => $start_date]);
                                                    $this->db->where(['invoiceDate <=' => $end_date]);
                                                    $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'financial_year' => $this->session->userdata('financial_year'), 'status' => 0]);
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                    }
                                                    $total_cancel = $this->db->get('sales')->row();
                                                    $total_cancel_invoice = $total_cancel->total_sale;
                                                ?>
                                                <?= $total_cancel_invoice; ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
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

<div class="modal fade" id="common_modal" tabindex="-1" role="dialog" aria-labelledby="common_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="common_modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function b2b_invoice() {
        let start_date = "<?= $start_date ?>";
        let last_date = "<?= $end_date ?>";
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "gst_report/b2b_gst_invoice/" + start_date + "/" + last_date);
        $("#common_modal_label").html("B2B REPORT");
        $("#common_modal").modal("show");
    }

    function b2c_large_invoice() {
        let start_date = "<?= $start_date ?>";
        let last_date = "<?= $end_date ?>";
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "gst_report/b2c_large_gst_invoice/" + start_date + "/" + last_date);
        $("#common_modal_label").html("B2C(LARGE) REPORT");
        $("#common_modal").modal("show");
    }

    function b2c_small_invoice() {
        let start_date = "<?= $start_date ?>";
        let last_date = "<?= $end_date ?>";
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "gst_report/b2c_small_gst_invoice/" + start_date + "/" + last_date);
        $("#common_modal_label").html("B2C REPORT");
        $("#common_modal").modal("show");
    }

    function hsn_invoice() {
        let start_date = "<?= $start_date ?>";
        let last_date = "<?= $end_date ?>";
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "gst_report/hsn_gst_invoice/" + start_date + "/" + last_date);
        $("#common_modal_label").html("HSN REPORT");
        $("#common_modal").modal("show");
    }

    function document_report() {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "index.php/gst_report/docum/" + start_date + "/" + last_date);
        $("#common_modal_label").html("HSN REPORT");
        $("#common_modal").modal("show");
    }
</script>