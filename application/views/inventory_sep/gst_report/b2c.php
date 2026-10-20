<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-navy">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-navy">B2C Report</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable" id="example">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>Type</th>
                                            <th>Place Of Supply</th>
                                            <th>Applicable % of Tax Rate</th>
                                            <th>Rate</th>
                                            <th>Taxable value</th>
                                            <th>Cess Amount</th>
                                            <th>E-Commerce GSTIN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <?php
                                            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                                            $this->db->select('*');
                                            $this->db->select_sum('invoice_product.amount');
                                            $this->db->select_sum('invoice_product.purchase_price');
                                            $this->db->select_sum('sales.cess');
                                            $this->db->from('invoice_product');
                                            $this->db->join('sales', 'invoice_product.invoiceID = sales.id', 'left');
                                            $this->db->group_by('invoice_product.tax');
                                            $this->db->group_by('sales.customerState');
                                            $this->db->where(['invoice_product.type' => 'Sale']);
                                            $this->db->where(['sales.gstNo' => '']);
                                            $this->db->where(['sales.user_id' => $this->session->userdata('user_id'), 'sales.comp_id' => $this->session->userdata('comp_id'), 'sales.status' => 1]);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('sales.CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $this->db->where(['sales.invoiceDate >=' => $start_date]);
                                            $this->db->where(['sales.invoiceDate <=' => $end_date]);
                                            $totals_data = $this->db->get()->result();

                                            if ($totals_data) {
                                                foreach ($totals_data as $key => $invoice_details) {
                                                    $StateId = get_title('parties', ['id' => $invoice_details->customerId], 'state');
                                        ?>
                                        <tr>
                                            <td>OE</td>
                                            <td><?= get_title('state_list', ['id' => $StateId], 'code') . "-" . get_title('state_list', ['id' => $StateId], 'state'); ?></td>
                                            <td><?= number_format($invoice_details->amount) ?></td>
                                            <td><?= $invoice_details->tax ?></td>
                                            <td><?= number_format($invoice_details->purchase_price) ?></td>
                                            <td><?= $invoice_details->cess ?></td>
                                            <td></td>
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
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            sorting: false,
            buttons: [{
                    extend: 'excelHtml5',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"
                },
                {
                    extend: 'pdfHtml5',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"
                },
                {
                    extend: 'copy',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"
                },
                {
                    extend: 'print',
                    title: "__<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>"
                }
            ]
        });
        
        document.getElementsByClassName('buttons-excel')[0].click();
    });
</script>