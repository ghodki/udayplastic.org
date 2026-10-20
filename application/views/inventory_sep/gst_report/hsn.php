<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <div class="card-danger">
                                <h4 class="text-danger">HSN Report</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable" id="example">
                                    <thead class="bg-danger">
                                        <tr>
                                            <th class="text-white">HSN</th>
                                            <th class="text-white">Description</th>
                                            <th class="text-white">Unit</th>
                                            <th class="text-white">Total Quantity</th>
                                            <th class="text-white">Total Value</th>
                                            <th class="text-white">Rate</th>
                                            <th class="text-white">Taxable value</th>
                                            <th class="text-white">IGST</th>
                                            <th class="text-white">CGST</th>
                                            <th class="text-white">SGST</th>
                                            <th class="text-white">Cess Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                                            $total_amount = 0;
                                            $total_tax = 0;
                                            $total_taxAbleAmount = 0;
                                            $this->db->select('*');
                                            $this->db->select('hsn');
                                            $this->db->select('invoiceID');
                                            $this->db->select('productname');
                                            $this->db->select_sum('price');

                                            $this->db->select_sum('netAmount');
                                            $this->db->select_sum('qty');
                                            $this->db->select_sum('sgst');
                                            $this->db->select_sum('cgst');
                                            $this->db->select_sum('igst');
                                            $this->db->select_sum('taxAmount');
                                            $this->db->select_sum('purchase_price');
                                            // taxable amount
                                            $this->db->from('invoice_product');
                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $this->db->where('status', 1);
                                            $this->db->where('invoiceDate >=', $start_date);
                                            $this->db->where('invoiceDate <=', $end_date);
                                            $this->db->where('type', 'Sale');
                                            $this->db->group_by("hsn", "tax");
                                            $result_of_products = $this->db->get()->result();

                                            if (!empty($result_of_products)) {
                                                foreach ($result_of_products as $key => $tax) {
                                                    $total_amount += $tax->purchase_price;
                                                    $total_tax += $tax->taxAmount;
                                                    $total_amount += $tax->netAmount;
                                                    $overall_igst = 0;
                                                    $overall_cgst = 0;
                                                    $overall_sgst = 0;
                                                    $overall_sgst = $overall_sgst + $tax->sgst;
                                                    $overall_cgst = $overall_cgst + $tax->cgst;
                                                    $overall_igst = $overall_igst + $tax->igst;
                                        ?>
                                        <tr>
                                            <td style="text-align:center;"><?= $tax->hsn ?></td>
                                            <td style="text-align:center;"><?= $tax->productname ?></td>
                                            <td style="text-align:center;"><?= get_title('product', ['id' => $tax->product_id], 'unit_name') ?></td>
                                            <td style="text-align:center;"><?= money_formats($tax->qty) ?></td>
                                            <td style="text-align:center;"><?= $tax->netAmount ?></td>
                                            <td style="text-align:center;"><?= $tax->tax ?></td>
                                            <td style="text-align:center;"><?= money_formats($tax->price) ?></td>
                                            <td style="text-align:center;"><?= money_formats($tax->igst) ?></td>
                                            <td style="text-align:center;"><?= money_formats($tax->cgst) ?></td>
                                            <td style="text-align:center;"><?= money_formats($tax->sgst) ?></td>
                                            <td style="text-align:center;"><?= 0.00 ?></td>
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