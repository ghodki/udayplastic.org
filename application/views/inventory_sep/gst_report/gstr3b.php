<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title text-center">
                                <h4 class="text-primary">
                                    GSTR-3B for <?= date('d-m-Y', strtotime($start_date)) ?> - <?= date('d-m-Y', strtotime($end_date)) ?> - Details of outward supplies of goods or services
                                </h4>
                            </div>
                            <a href="<?= base_url(); ?>Gst_report" class="btn btn-primary btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <h1 class="text-center text-primary">GSTR-3 Invoice Details</h1>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th colspan="6" class="text-center">Sales Invoices</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Total Taxable Value</th>
                                            <th>Integrated Tax</th>
                                            <th>Central Tax</th>
                                            <th>State Tax</th>
                                            <th>CESS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                            $this->db->select(['SUM(totalsgst) as stotalsgst', 'SUM(taxableAmount) as staxableAmount', 'SUM(totaligst) as stotaligst', 'SUM(totalcgst) as stotalcgst']);
                                            $this->db->where(['invoiceDate >=' => $start_date]);
                                            $this->db->where(['invoiceDate <=' => $end_date]);
                                            $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $totals_data = $this->db->get('sales')->result();
                                        ?>
                                        <tr>
                                            <td>SALE</td>
                                            <td class="text-center">
                                                <?= $totals_data[0]->staxableAmount ? indianMoneyFormat($totals_data[0]->staxableAmount) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data[0]->stotaligst ? indianMoneyFormat($totals_data[0]->stotaligst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data[0]->stotalcgst ? indianMoneyFormat($totals_data[0]->stotalcgst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data[0]->stotalsgst ? indianMoneyFormat($totals_data[0]->stotalsgst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">0.00</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="text-center">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-center">
                                                <?= $totals_data[0]->staxableAmount ? indianMoneyFormat($totals_data[0]->staxableAmount) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data[0]->stotaligst ? indianMoneyFormat($totals_data[0]->stotaligst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data[0]->stotalcgst ? indianMoneyFormat($totals_data[0]->stotalcgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data[0]->stotalsgst ? indianMoneyFormat($totals_data[0]->stotalsgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th colspan="6" class="text-center">Purchase Invoices</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Total Taxable Value</th>
                                            <th>Integrated Tax</th>
                                            <th>Central Tax</th>
                                            <th>State Tax</th>
                                            <th>CESS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                            $this->db->select(['SUM(totalsgst) as stotalsgst', 'SUM(taxableAmount) as staxableAmount', 'SUM(totaligst) as stotaligst', 'SUM(totalcgst) as stotalcgst']);
                                            $this->db->where(['invoiceDate >=' => $start_date]);
                                            $this->db->where(['invoiceDate <=' => $end_date]);
                                            $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $totals_data2 = $this->db->get('purchases')->result();
                                        ?>
                                        <tr>
                                            <td>PURCHASE</td>
                                            <td class="text-center">
                                                <?= $totals_data2[0]->staxableAmount ? indianMoneyFormat($totals_data2[0]->staxableAmount) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data2[0]->stotaligst ? indianMoneyFormat($totals_data2[0]->stotaligst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data2[0]->stotalcgst ? indianMoneyFormat($totals_data2[0]->stotalcgst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data2[0]->stotalsgst ? indianMoneyFormat($totals_data2[0]->stotalsgst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">0.00</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="text-center">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-center">
                                                <?= $totals_data2[0]->staxableAmount ? indianMoneyFormat($totals_data2[0]->staxableAmount) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data2[0]->stotaligst ? indianMoneyFormat($totals_data2[0]->stotaligst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data2[0]->stotalcgst ? indianMoneyFormat($totals_data2[0]->stotalcgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data2[0]->stotalsgst ? indianMoneyFormat($totals_data2[0]->stotalsgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th colspan="6" class="text-center">Credit Note Invoices</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Total Taxable Value</th>
                                            <th>Integrated Tax</th>
                                            <th>Central Tax</th>
                                            <th>State Tax</th>
                                            <th>CESS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                            $this->db->select(['SUM(totalsgst) as stotalsgst', 'SUM(taxableAmount) as staxableAmount', 'SUM(totaligst) as stotaligst', 'SUM(totalcgst) as stotalcgst']);
                                            $this->db->where(['invoiceDate >=' => $start_date]);
                                            $this->db->where(['invoiceDate <=' => $end_date]);
                                            $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $totals_data3 = $this->db->get('credit_notes')->result();
                                        ?>
                                        <tr>
                                            <td>Credit Note</td>
                                            <td class="text-center">
                                                <?= $totals_data3[0]->staxableAmount ? indianMoneyFormat($totals_data3[0]->staxableAmount) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data3[0]->stotaligst ? indianMoneyFormat($totals_data3[0]->stotaligst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">

                                                <?= $totals_data3[0]->stotalcgst ? indianMoneyFormat($totals_data3[0]->stotalcgst) : 0.00;
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data3[0]->stotalsgst ? indianMoneyFormat($totals_data3[0]->stotalsgst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">0.00</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="text-center">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-center">
                                                <?= $totals_data3[0]->staxableAmount ? indianMoneyFormat($totals_data3[0]->staxableAmount) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data3[0]->stotaligst ? indianMoneyFormat($totals_data3[0]->stotaligst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data3[0]->stotalcgst ? indianMoneyFormat($totals_data3[0]->stotalcgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data3[0]->stotalsgst ? indianMoneyFormat($totals_data3[0]->stotalsgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th colspan="6" class="text-center">Debit Note Invoices</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Total Taxable Value</th>
                                            <th>Integrated Tax</th>
                                            <th>Central Tax</th>
                                            <th>State Tax</th>
                                            <th>CESS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                            $this->db->select(['SUM(totalsgst) as stotalsgst', 'SUM(taxableAmount) as staxableAmount', 'SUM(totaligst) as stotaligst', 'SUM(totalcgst) as stotalcgst']);
                                            $this->db->where(['invoiceDate >=' => $start_date]);
                                            $this->db->where(['invoiceDate <=' => $end_date]);
                                            $this->db->where(['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id'), 'status' => 1]);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $totals_data4 = $this->db->get('debit_notes')->result();
                                        ?>
                                        <tr>
                                            <td>Debit Note</td>
                                            <td class="text-center">
                                                <?= $totals_data4[0]->staxableAmount ? indianMoneyFormat($totals_data4[0]->staxableAmount) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data4[0]->stotaligst ? indianMoneyFormat($totals_data4[0]->stotaligst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data4[0]->stotalcgst ? indianMoneyFormat($totals_data4[0]->stotalcgst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $totals_data4[0]->stotalsgst ? indianMoneyFormat($totals_data4[0]->stotalsgst) : 0.00; ?>
                                            </td>
                                            <td class="text-center">0.00</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="text-center">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-center">
                                                <?= $totals_data4[0]->staxableAmount ? indianMoneyFormat($totals_data4[0]->staxableAmount) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data4[0]->stotaligst ? indianMoneyFormat($totals_data4[0]->stotaligst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data4[0]->stotalcgst ? indianMoneyFormat($totals_data4[0]->stotalcgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">
                                                <?= $totals_data4[0]->stotalsgst ? indianMoneyFormat($totals_data4[0]->stotalsgst) : 0.00; ?>
                                            </th>
                                            <th class="text-center">0.00</th>
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