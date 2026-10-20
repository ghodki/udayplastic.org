<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row" style="margin-top:65px;">
                <div class="col-lg-12">
                    <div class="card mb-30">

                        <div class="card-header row">
                            <div class="col-md-6">
                                <h4><?= get_title('parties', ['id' => $party_id], 'name') ?> REPORT</h4>
                            </div>
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-1">
                                <a href="<?= base_url() ?>invent/customer_report" class="btn btn-info"><i data-feather="arrow-left-circle" class="icon icon-size"></i>Back</a>

                            </div>
                        </div>
                      
                     
                        <?php
                        if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                        <?php } ?>
                        <h3 class="text-center">SALE REPORT</h3>
                     
                            <table class="table table-striped table-bordered table-responsive-sm dataTable" id="example">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Invoice No</th>
                                        <th>Invoice Date</th>
                                        <th>Party Name</th>
                                        <!-- <th>Address</th> -->
                                        <th>Mobile</th>
                                        <!-- <th>Email</th> -->
                                        <th>GST No</th>
                                        <th>Payment Mode</th>
                                        <th>Taxable Amount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <?php
                                $financial_year=get_title('comp_profile',['comp_id'=>$this->session->userdata('comp_id'),'user_id'=>$this->session->userdata('user_id')],'financial_year');

                                $this->db->select('*');
                                $this->db->from('sales');
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('financial_year', $financial_year);
                                $this->db->where('customerId', $party_id);
                                $result = $this->db->get()->result();
                                ?>
                                <tbody id="tbody">
                                    <?php
                                    $totalTaxable_amount = 0;
                                    $total_tax = 0;
                                    $total_amount = 0;
                                    if ($result) {
                                        foreach ($result as $key => $row) {
                                            $totalTaxable_amount += $row->taxableAmount;
                                            $total_tax += $row->totalTax;
                                            $total_amount += $row->total;
                                    ?>
                                            <tr id="trp<?= $key ?>" tabindex="<?= $key + 5 ?>" onkeyup="keyUpForParty(event,<?= $key ?>)" onkeypress="getCustomerDetail(<?= $row->id ?>,<?= $key ?>)" onkeydown="keyDownForParty(event,<?= $key ?>)" onclick="getCustomerDetail(<?= $row->id ?>,<?= $key ?>)">
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $row->invoiceNumber; ?></td>
                                                <td><?= $row->invoiceDate; ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'name') ?></td>
                                                <!-- <td><?= $row->customerAddress; ?></td> -->
                                                <td><?= $row->customerMobile; ?></td>
                                                <!-- <td><?= $row->customerEmail; ?></td> -->
                                                <td><?= $row->gstNo ?></td>
                                                <?php
                                                if ($row->voucherType == 1) {
                                                    $payment_mode = "Cash";
                                                } else if ($row->voucherType == 2) {
                                                    $payment_mode = "Credit";
                                                } else {
                                                    $payment_mode = $row->voucherType;
                                                }
                                                ?>
                                                <td><?= $payment_mode ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->taxableAmount; ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->totalTax; ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->total; ?></td>
                                                <td>

                                                    <a href="<?= base_url('sale/viewsale/') . $row->id ?>" class="text-success" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    | <a href="<?= base_url('sale/saleEdit/') . $row->id ?>" class="text-info" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                    |<a href="<?= base_url('sale/sale_deleted/') . $row->id ?>" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon icon-size">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg></a>
                                                    | <a href="<?= base_url('sale/printSale/') . $row->id ?>" class="text-success" title="Print"><i class="fa fa-print" aria-hidden="true"></i></a>

                                                    |<a href="<?= base_url('sale/printSales/') . $row->id ?>" class="text-primary" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>


                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>

                                <tfoot>
                                    <th colspan="7">Total</th>
                                    <th><i class="fa fa-inr total" aria-hidden="true">
                                            <?php
                                            echo $totalTaxable_amount;
                                            // $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                            // $this->db->select_sum('taxableAmount');
                                            // $this->db->from('sales');
                                            // $this->db->where($where);
                                            // $res = $this->db->get()->row();
                                            // if ($res) {
                                            // 	echo $res->taxableAmount;
                                            // } else {
                                            // 	echo 0;
                                            // }
                                            ?>
                                    </th>
                                    <th><i class="fa fa-inr" aria-hidden="true">
                                            <?php
                                            echo $total_tax;
                                            // $this->db->select_sum('totalTax');
                                            // $this->db->from('sales');
                                            // $this->db->where($where);

                                            // $res = $this->db->get()->row();
                                            // if ($res) {
                                            // 	echo $res->totalTax;
                                            // } else {
                                            // 	echo 0;
                                            // }
                                            ?>
                                    </th>
                                    <th><i class="fa fa-inr" aria-hidden="true">
                                            <?php
                                            // $this->db->select_sum('total');
                                            // $this->db->from('sales');
                                            // $this->db->where($where);
                                            // $res = $this->db->get()->row();
                                            // if ($res) {
                                            // 	echo $res->total;
                                            // } else {
                                            // 	echo 0;
                                            // }
                                            echo $total_amount;
                                            ?>
                                    </th>
                                    <th></th>
                                </tfoot>
                            </table>
                        <h3 class="text-center">PURCHASE REPORT</h3>
                            <table class="table table-striped table-bordered table-responsive-sm dataTable" id="example">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Invoice No</th>
                                        <th>Invoice Date</th>
                                        <th>Party Name</th>
                                        <!-- <th>Address</th> -->
                                        <th>Mobile</th>
                                        <!-- <th>Email</th> -->
                                        <th>GST No</th>
                                        <th>Payment Mode</th>
                                        <th>Taxable Amount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <?php
                                $this->db->select('*');
                                $this->db->from('purchases');
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('financial_year', $financial_year);
                                $this->db->where('customerId', $party_id);
                                $result = $this->db->get()->result();
                                ?>
                                <tbody id="tbody">
                                    <?php
                                    $totalTaxable_amount = 0;
                                    $total_tax = 0;
                                    $total_amount = 0;
                                    if ($result) {
                                        foreach ($result as $key => $row) {
                                            $totalTaxable_amount += $row->taxableAmount;
                                            $total_tax += $row->totalTax;
                                            $total_amount += $row->total;
                                    ?>
                                            <tr id="trp<?= $key ?>" tabindex="<?= $key + 5 ?>" onkeyup="keyUpForParty(event,<?= $key ?>)" onkeypress="getCustomerDetail(<?= $row->id ?>,<?= $key ?>)" onkeydown="keyDownForParty(event,<?= $key ?>)" onclick="getCustomerDetail(<?= $row->id ?>,<?= $key ?>)">
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $row->invoiceNumber; ?></td>
                                                <td><?= $row->invoiceDate; ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'name') ?></td>
                                                <!-- <td><?= $row->customerAddress; ?></td> -->
                                                <td><?= $row->customerMobile; ?></td>
                                                <!-- <td><?= $row->customerEmail; ?></td> -->
                                                <td><?= $row->gstNo ?></td>
                                                <?php
                                                if ($row->voucherType == 1) {
                                                    $payment_mode = "Cash";
                                                } else if ($row->voucherType == 2) {
                                                    $payment_mode = "Credit";
                                                } else {
                                                    $payment_mode = $row->voucherType;
                                                }
                                                ?>
                                                <td><?= $payment_mode ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->taxableAmount; ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->totalTax; ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i><?= $row->total; ?></td>
                                                <td>

                                                    <a href="<?= base_url('purchase/viewpurchase/') . $row->id ?>" class="text-success" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    | <a href="<?= base_url('purchase/purchaseEdit/') . $row->id ?>" class="text-info" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                    |<a href="<?= base_url('purchase/purchase_delete/') . $row->id ?>" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon icon-size">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg></a>
                                                    | <a href="<?= base_url('purchase/printpurchase/') . $row->id ?>" class="text-success" title="Print"><i class="fa fa-print" aria-hidden="true"></i></a>

                                                    |<a href="<?= base_url('purchase/printpurchases/') . $row->id ?>" class="text-primary" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a> |


                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>

                                <tfoot>
                                    <th colspan="7">Total</th>
                                    <th><i class="fa fa-inr total" aria-hidden="true">
                                            <?php
                                            echo $totalTaxable_amount;
                                            // $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                            // $this->db->select_sum('taxableAmount');
                                            // $this->db->from('sales');
                                            // $this->db->where($where);
                                            // $res = $this->db->get()->row();
                                            // if ($res) {
                                            // 	echo $res->taxableAmount;
                                            // } else {
                                            // 	echo 0;
                                            // }
                                            ?>
                                    </th>
                                    <th><i class="fa fa-inr" aria-hidden="true">
                                            <?php
                                            echo $total_tax;
                                            // $this->db->select_sum('totalTax');
                                            // $this->db->from('sales');
                                            // $this->db->where($where);

                                            // $res = $this->db->get()->row();
                                            // if ($res) {
                                            // 	echo $res->totalTax;
                                            // } else {
                                            // 	echo 0;
                                            // }
                                            ?>
                                    </th>
                                    <th><i class="fa fa-inr" aria-hidden="true">
                                            <?php
                                            // $this->db->select_sum('total');
                                            // $this->db->from('sales');
                                            // $this->db->where($where);
                                            // $res = $this->db->get()->row();
                                            // if ($res) {
                                            // 	echo $res->total;
                                            // } else {
                                            // 	echo 0;
                                            // }
                                            echo $total_amount;
                                            ?>
                                    </th>
                                    <th></th>
                                </tfoot>
                            </table>
                        <?php

                        ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1],
                [20, 50, 100, "All"]
            ],
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'print',
                'pageLength',
            ]

        });
    });
</script>
