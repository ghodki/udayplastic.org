<!DOCTYPE html>
<html lang="en">
        <head>
                <title>Invoice</title>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
                
                <style type="text/css">
                        .tblbody {
                                border-bottom: 1px solid #000;
                                margin-bottom: 15px;
                        }                        
                        .text-right {
                                text-align: right;
                        }
                        .container {
                                margin: auto;
                        }
                        li {
                                line-height: 11px;
                                margin-left: 0px;
                        }
                        div {
                                font-size: 12px;
                        }
                        ol {
                                margin-block-start: 0PX;
                                padding-inline-start: 20px;
                        }
                        .outer {
                                font-size: 100%;
                                table-layout: fixed;
                                width: 100%;
                                border: 1px solid #000;
                                border-collapse: collapse;
                                font-size: 12px
                        }
                        td {
                                line-height: 15px;
                                padding: 2px;
                                padding-left: .25em;
                        }
                        tr {
                                font-size: 12px;
                        }
                        .mytable th {
                                border-bottom: 1px solid #000;
                                border-top: 1px solid #000;
                                border-collapse: collapse;
                        }
                        .mytable {
                                border-collapse: collapse;
                                width: 100%;
                                font-size: 12px
                        }
                        .text-uppercase {
                                text-transform: uppercase;
                                margin-top: 1px;
                                margin-bottom: 0px;
                        }
                        .border-right {
                                border-right: 1px solid #000;
                        }
                        .border-left {
                                border-left: 1px solid #000;
                        }
                        .border-bottom {
                                border-bottom: 1px solid #000;
                        }
                        .border-top {
                                border-top: 1px solid #000;
                        }
                        .text-center {
                                text-align: center;
                        }
                        small {
                                font-size: 10px;
                                line-height: 11px;
                        }
                        .gstTable {
                                width: 100%;
                                border: 1px solid #000;
                                border-collapse: collapse;
                        }
                        .gstTable td {
                                border: 1px solid #000;
                        }
                        .blankLine {
                                line-height: 30px;
                        }
                        body {
                            font-size: 80%;
                        }
                        @media print {
                                .outer thead {
                                        display: table-header-group;
                                }
                                .page-break {
                                        page-break-before: always;
                                }
                        }
                        #footerkk {
                                font-size: 100%;
                                table-layout: fixed;
                                width: 100%;
                                border: 1px solid #000;
                                border-collapse: collapse;
                                font-size: 12px
                        }
                        .copy_title {
                                font-size: 14px;
                                text-align: right;
                                margin-right: 35px;
                        }                        
                </style>
        </head>
        <body>
                <?php
                        if ($this->session->userdata('RoleType')) {
                                $userId = $this->session->userdata('UserId');
                                $compId = $this->session->userdata('CompId');
                        } else {
                                $userId = $this->session->userdata('user_id');
                                $compId = $this->session->userdata('comp_id');
                        }
                        $compBranchId = $this->session->userdata('CompBranchId');

                        $where = ['comp_id' => $compId, 'user_id' => $userId];
                        if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                                $companyBranch = $this->user_model->getRow('companybranch', ['Id' => $compBranchId, 'CompId' => $compId, 'BranchUserId' => $userId]);
                        } else {
                                $company = $this->user_model->getRow('inventory_billing', ['id' => $compId, 'user_id' => $userId]);                    
                        }

                        $compProfile = $this->user_model->getRow('comp_profile', $where);
                       
                        $proformaConfig = $this->user_model->getRow('proforma_config', $where);

                        if ($compProfile) {
                            $totalInvoiceCopies = $compProfile->total_invoice;
                            for ($invoiceCopies = 0; $invoiceCopies < $totalInvoiceCopies; $invoiceCopies++) {                        
                ?>
               <div class="container">
                        <?php if ($invoiceCopies == 0) { ?>
                        <div class="copy_title">ORIGINAL</div>
                        <?php } elseif ($invoiceCopies == 1) { ?>
                        <div class="copy_title" style="page-break-before: always;">DUPLICATE</div>
                        <?php } elseif ($invoiceCopies == 2) { ?>
                        <div class="copy_title" style="page-break-before: always;">TRIPLICATE</div>
                        <?php } else { ?>
                        <div class="copy_title" style="page-break-before: always;">ONLY DUPLICATE</div>
                        <?php } ?>                       
                       <h1 style="text-align:center;margin:0px;"><?= ($proformaConfig->title) ? $proformaConfig->title : 'GST-Invoice'; ?></h1>
                       
                       <table class="outer" style="border-bottom: 0px;">                        
                                <thead id="header_1">
                                        <tr>
                                               <td style="width: 65%;" class="border-right border-bottom">
                                                       <h3 class="text-uppercase"><?=  $compProfile ? $compProfile->name : ''; ?></h3>
                                                       <p style="margin: 2px;">
                                                               <?=  $this->session->userdata('BranchRole') ? $companyBranch->Address : $company->address;  ?>
                                                               <br />
                                                               Mobile No.:  <?= $this->session->userdata('BranchRole') ? $companyBranch->Mobile : $company->number ; ?>
                                                               Email: <?= $this->session->userdata('BranchRole') ? $companyBranch->Email : $company->email; ?>
                                                       </p>
                                                       <p style="margin: 2px;">
                                                               <b>GSTN: <?= $this->session->userdata('BranchRole') ? $companyBranch->GST : $company->gst_no; ?></b>
                                                               <b>Pan: <?=  $compProfile ? $compProfile->panNumber : ''; ?></b>
                                                               <b>Phone No.: <?=  $compProfile ? $compProfile->phoneNumber : ''; ?></b>
                                                        </p>
                                               </td>
                                               <td rowspan="3" style="padding: 0px; vertical-align: text-top;">
                                                       <table style="width: 100%; border-collapse: collapse; margin-top: 0px;">
                                                               <tr class="border-bottom">
                                                                       <td class="border-right" style="width:45%"><b>Date</b></td>
                                                                       <td><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Invoice No</b></td>
                                                                       <td><?= $result->invoiceNumber; ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Order Date</b></td>
                                                                       <td><?= $result->orderDate ? date('d/m/Y', strtotime($result->orderDate)) : ''; ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Order No</b></td>
                                                                       <td><?= $result->orderNumber ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Dispatch By</b></td>
                                                                       <td><?= ucwords($result->transport); ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Docket No</b></td>
                                                                       <td><?= $result->lrNumber ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Reached Date</b></td>
                                                                       <td><?= $result->reachedDate ? date('d/m/Y', strtotime($result->reachedDate)) : ''; ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Eway Bill</b></td>
                                                                       <td><?= $result->ewayBill; ?></td>
                                                               </tr>
                                                               <tr class="border-bottom">
                                                                       <td class="border-right"><b>Chalan No.</b></td>
                                                                       <td><?= $result->challanNumber; ?></td>
                                                               </tr>
                                                               <tr>
                                                                       <td colspan="2" class="noborder">
                                                                               <div class="row">
                                                                                       <div class="col-12">
                                                                                              <b>Company's Bank Details :-</b>
                                                                                       </div>
                                                                                       <div class="col-12">
                                                                                               <b><?= $compProfile ? $compProfile->bank_details : ''; ?></b>
                                                                                       </div>
                                                                               </div>
                                                                       </td>
                                                               </tr>
                                                       </table>
                                               </td>
                                       </tr>
                                       <tr>
                                               <?php if ($result->customerId != '' && $result->customerId != 0) { $party = $this->db->get_where('parties', ['id' => $result->customerId])->row(); } ?>
                                               <td style="width: 70%;" class="border-right border-bottom">
                                                       <b>Buyer Details</b>
                                                       <br />
                                                       <b><?=$party->name; ?></b>
                                                       <br />
                                                       <?= $result->customerId != '' && $result->customerId != 0 ?  $party->address_1 : '' ?><br>
                                                       <?= $result->customerId != '' && $result->customerId != 0 && $party->address_2 ?  $party->address_2 . '<br>' : '' ?>
                                                       <?php $city = $this->db->get_where('districts', ['id' => $party->city])->row();  ?>
                                                       <?= isset($city) ? $city->name : '' ?> Pin : <?= isset($party) ? $party->pin : '' ?> 
                                                       <?php $state = $this->db->get_where('state_list', ['id' => $party->state])->row(); echo $state->state; ?>
                                                       <br />
                                                       <b>GSTN. </b> <?= isset($party) ? $party->gst : '' ?>
                                                       <br />
                                                       <b>Contact Details</b> <?= isset($party) ? $party->contact : '' ?>, <?= isset($party) ? $party->email : '' ?>
                                               </td>
                                       </tr>
                                       <tr>
                                               <td width="70%" style="border-right: 1px solid #000;">
                                                       <b>Consignee Details</b><br>
                                                       <?php $consignee = json_decode($result->shippingInfo); ?>
                                                       <b><?= $consignee->shippingName;  ?></b>
                                                       <br />
                                                       <?= $consignee->shippingAddress; ?>
                                                       <br />
                                                       <?= $consignee->shippingCity; ?>
                                                       Pin : <?= $consignee->ShippingZip;  ?> 
                                                       <?= get_title('state_list', ['id' => $consignee->shippingSate], 'state'); ?>
                                                       <br />
                                                       <b>GSTN. </b><?= $consignee->shippingGST; ?>
                                                       <br />
                                                       <b>Contact Details </b> <?= $consignee->shiipingMobile ?>, <?= $consignee->shippingEmaill; ?>
                                               </td>
                                       </tr>
                               </thead>
                               <tbody class="tblbody" id="tbody_1">
                                       <tr>
                                               <td style="vertical-align:text-top" colspan="2">
                                                       <table class="mytable" style="margin-top: -2px;">
                                                               <thead>
                                                                       <tr style="background-color: #999999; -webkit-print-color-adjust: exact; font-size:14px">
                                                                              <th class="border-right" style="width:25px">S/N</th>
                                                                              <th class="border-right">HSN</th>
                                                                              <th class="border-right" style="width:30%">PARTICULAR</th>
                                                                              <th class="border-right" style="width:40px">GST</th>
                                                                              <th class="border-right" style="width:50px">QTY</th>
                                                                              <th class="border-right">RATE</th>
                                                                              <th class="border-right">DIS</th>
                                                                              <th class="border-right">CD</th>
                                                                              <th class="border-right">NLC</th>
                                                                              <th>AMOUNT</th>
                                                                       </tr>
                                                               </thead>
                                                               <tbody style="font-size:12px;">
                                                                       <?php
                                                                               $nosItm = 0;
                                                                               $lines = 6;
                                                                               $counts = 0;
                                                                               $totalDiscount = 0;
                                                                               $totalRate = 0;
                                                                               $totalCD = 0;
                                                                               $productPerPage = ($compProfile->item_per_page) ? $compProfile->item_per_page : 30;
                                                                               if ($result->product) {
                                                                                       foreach ($result->product as $key => $row) {
                                                                                                if ($key > 0 && $key % $productPerPage === 0) {
                                                                                                        echo '</tbody></table></td></tr></tbody></table>';
                                                                                                        echo '<div style="float:right; padding-top: 10px;">Continue to next page...</div>'; // Page break
                                                                                                        echo '<div style="page-break-after: always;"></div>'; // Page break
                                                                        ?>
                                                                        <h1 style="text-align:center;margin:0px;">
                                                                                <?= ($proformaConfig->title) ? $proformaConfig->title : 'GST-Invoice'; ?>
                                                                        </h1>
                                                                        <table class="outer">
                                                                                <thead id="header_1">
                                                                                        <tr>
                                                                                               <td style="width: 65%;" class="border-right border-bottom">
                                                                                                       <h3 class="text-uppercase"><?=  $compProfile ? $compProfile->name : ''; ?></h3>
                                                                                                       <p style="margin: 2px;">
                                                                                                               <?=  $this->session->userdata('BranchRole') ? $companyBranch->Address : $company->address;  ?>
                                                                                                               <br />
                                                                                                               Mobile No.:  <?= $this->session->userdata('BranchRole') ? $companyBranch->Mobile : $company->number ; ?>
                                                                                                               Email: <?= $this->session->userdata('BranchRole') ? $companyBranch->Email : $company->email; ?>
                                                                                                       </p>
                                                                                                       <p style="margin: 2px;">
                                                                                                                <b>GSTN: <?= $this->session->userdata('BranchRole') ? $companyBranch->GST : $company->gst_no; ?></b>
                                                                                                                <b>Pan: <?=  $compProfile ? $compProfile->panNumber : ''; ?></b>
                                                                                                                <b>Phone No.: <?=  $compProfile ? $compProfile->phoneNumber : ''; ?></b>
                                                                                                        </p>
                                                                                               </td>
                                                                                               <td rowspan="3" style="padding: 0px; vertical-align: text-top;">
                                                                                                       <table style="width: 100%; border-collapse: collapse; margin-top: 0px;">
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right" style="width:45%"><b>Date</b></td>
                                                                                                                       <td><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Invoice No</b></td>
                                                                                                                       <td><?= $result->invoiceNumber; ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Order Date</b></td>
                                                                                                                       <td><?= $result->orderDate ? date('d/m/Y', strtotime($result->orderDate)) : ''; ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Order No</b></td>
                                                                                                                       <td><?= $result->orderNumber ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Dispatch By</b></td>
                                                                                                                       <td><?= ucwords($result->transport); ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Docket No</b></td>
                                                                                                                       <td><?= $result->lrNumber ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Reached Date</b></td>
                                                                                                                       <td><?= $result->reachedDate ? date('d/m/Y', strtotime($result->reachedDate)) : ''; ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Eway Bill</b></td>
                                                                                                                       <td><?= $result->ewayBill; ?></td>
                                                                                                               </tr>
                                                                                                               <tr class="border-bottom">
                                                                                                                       <td class="border-right"><b>Chalan No.</b></td>
                                                                                                                       <td><?= $result->challanNumber; ?></td>
                                                                                                               </tr>
                                                                                                               <tr>
                                                                                                                       <td colspan="2" class="noborder">
                                                                                                                               <div class="row">
                                                                                                                                       <div class="col-12">
                                                                                                                                              <b>Company's Bank Details :-</b>
                                                                                                                                       </div>
                                                                                                                                       <div class="col-12">
                                                                                                                                               <b><?= $compProfile ? $compProfile->bank_details : ''; ?></b>
                                                                                                                                       </div>
                                                                                                                               </div>
                                                                                                                       </td>
                                                                                                               </tr>
                                                                                                       </table>
                                                                                               </td>
                                                                                       </tr>
                                                                                       <tr>
                                                                                               <?php if ($result->customerId != '' && $result->customerId != 0) { $party = $this->db->get_where('parties', ['id' => $result->customerId])->row(); } ?>
                                                                                               <td style="width: 70%;" class="border-right border-bottom">
                                                                                                       <b>Buyer Details</b>
                                                                                                       <br />
                                                                                                       <b><?=$party->name; ?></b>
                                                                                                       <br />
                                                                                                       <?= $result->customerId != '' && $result->customerId != 0 ?  $party->address_1 : '' ?><br>
                                                                                                       <?= $result->customerId != '' && $result->customerId != 0 && $party->address_2 ?  $party->address_2 . '<br>' : '' ?>
                                                                                                       <?php $city = $this->db->get_where('districts', ['id' => $party->city])->row();  ?>
                                                                                                       <?= isset($city) ? $city->name : '' ?> Pin : <?= isset($party) ? $party->pin : '' ?> 
                                                                                                       <?php $state = $this->db->get_where('state_list', ['id' => $party->state])->row(); echo $state->state; ?>
                                                                                                       <br />
                                                                                                       <b>GSTN. </b> <?= isset($party) ? $party->gst : '' ?>
                                                                                                       <br />
                                                                                                       <b>Contact Details</b> <?= isset($party) ? $party->contact : '' ?>, <?= isset($party) ? $party->email : '' ?>
                                                                                               </td>
                                                                                       </tr>
                                                                                       <tr>
                                                                                               <td width="70%" style="border-right: 1px solid #000;">
                                                                                                       <b>Consignee Details</b><br>
                                                                                                       <?php $consignee = json_decode($result->shippingInfo); ?>
                                                                                                       <b><?= $consignee->shippingName;  ?></b>
                                                                                                       <br />
                                                                                                       <?= $consignee->shippingAddress; ?>
                                                                                                       <br />
                                                                                                       <?= $consignee->shippingCity; ?>
                                                                                                       Pin : <?= $consignee->ShippingZip;  ?> 
                                                                                                       <?= get_title('state_list', ['id' => $consignee->shippingSate], 'state'); ?>
                                                                                                       <br />
                                                                                                       <b>GSTN. </b><?= $consignee->shippingGST; ?>
                                                                                                       <br />
                                                                                                       <b>Contact Details </b> <?= $consignee->shiipingMobile ?>, <?= $consignee->shippingEmaill; ?>
                                                                                               </td>
                                                                                       </tr>
                                                                               </thead>
                                                                        </table>
                                                                        <?php 
                                                                                echo '<table class="mytable" style="margin-top: -2px;"><thead><tr style="border-left: 1px solid;border-right: 1px solid;background-color: #999999; -webkit-print-color-adjust: exact; font-size:14px"><th class="border-right" style="width:25px">S/N</th><th class="border-right">HSN</th><th class="border-right" style="width:30%">PARTICULAR</th><th class="border-right" style="width:40px">GST</th><th class="border-right" style="width:50px">QTY</th><th class="border-right">RATE</th><th class="border-right">DIS</th><th class="border-right">CD</th><th class="border-right">NLC</th><th>AMOUNT</th></tr></thead><tbody style="border-left: 1px solid;border-right: 1px solid;font-size:12px;">';
                                                                        }
                                                                                $qtty = $row->qty;
                                                                                $nosItm += $qtty;
                                                                                $taxIte =  get_title('product', ['id' => $row->tax], 'tax');
                                                                                $rate = $row->rate;
                                                                                $totalDiscount += get_title('product', ['id' => $row->product_id], 'mrp') * $row->qty;
                                                                       ?>
                                                                       <tr>
                                                                               <td class="border-right" style="text-align:center"><?= $key + 1; ?></td>
                                                                               <td class="border-right text-right"><?= $row->hsn; ?></td>
                                                                               <td class="border-right">
                                                                                      <small>
                                                                                              <?php
                                                                                                      $product_name = get_title('product', ['id' => $row->product_id], 'p_name');
                                                                                                      if ($product_name) {
                                                                                                              echo $product_name;
                                                                                                      } else {
                                                                                                              echo $product_name;
                                                                                                      }
                                                                                                      if ($row->product_dec) {
                                                                                              ?>
                                                                                              <br />
                                                                                              <div class="descc"><?= $row->product_dec; ?></div>
                                                                                              <?php } ?>
                                                                                      </small>
                                                                              </td>
                                                                              <td class="border-right text-right"><?= $row->tax; ?>%</td>
                                                                              <td class="border-right" style="text-align:center;">
                                                                                       <?php
                                                                                              $totalRate += $row->purchase_price;
                                                                                              if ($a5config->SubUnit == 'Y') {
                                                                                                      if (get_title('sub_unit', ['Id' => get_title('product', ['id' => $row->product_id], 'SubUnit')], 'Value')) {
                                                                                                              echo ($row->qty / get_title('sub_unit', ['Id' => get_title('product', ['id' => $row->product_id], 'SubUnit')], 'Value'));
                                                                                                      } else {
                                                                                                              echo $row->qty;
                                                                                                       }
                                                                                              } else {
                                                                                                      echo $row->qty;
                                                                                              }
                                                                                      ?>
                                                                              </td>
                                                                              <td class="border-right text-right"><?= number_format($row->purchase_price / $row->qty, 3); ?></td>
                                                                              <td class="border-right text-right">
                                                                                      <?= ($row->discount / $row->rate) * 100; ?>%
                                                                              </td>
                                                                              <td class="border-right text-right">
                                                                                      <?= $row->discount; ?>
                                                                              </td>
                                                                              <td class="border-right text-right"><?= $row->purchase_price; ?></td>
                                                                              <td class="text-right"><?= number_format($row->netAmount, 2); ?></td>
                                                                       </tr>
                                                              

                                                                       <?php  $counts = $key; $totalItemLast = ($key % $productPerPage) + 1 ;} } ?>
                                                                     
                                                                       <input type="hidden" value="<?= $totalItemLast; ?>" />
                                                                       <?php 
                                                                                $extrarow = $productPerPage - $totalItemLast;
                                                                                if ($extrarow > 13) {
                                                                                        for($i = 0; $i <= $extrarow -12; $i++) {
                                                                        ?>
                                                                      <tr>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="border-right">&nbsp;</td>
                                                                               <td class="">&nbsp;</td>
                                                                       </tr>
                                                                       <?php  } } ?>                                                                    
                                                               </tbody>
                                                               <tfoot style="border-left: 1px solid;border-right: 1px solid;display:none;">
                                                                      <tr>
                                                                               <td class="border-right blankLine"></td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="border-right blankLine">&nbsp;</td>
                                                                               <td class="blankLine">&nbsp;</td>
                                                                       </tr>
                                                               </tfoot>
                                                       </table>
                                               </td>
                                       </tr>
                               </tbody>                            
                       </table>
               </div>
               <div class="container">
                        <table id="footerkk">
                                <tr style="font-size:12px">
                                    <td class="border-right border-top">
                                        <table class="gstTable" style="margin-top: -15px;">
                                            <tr style="background-color: #999999; -webkit-print-color-adjust: exact; font-size:12px">
                                                <th style="border-right:1px solid #222;">TAX</th>
                                                <th style="border-right:1px solid #222;">GOODS AMT</th>
                                                <th style="border-right:1px solid #222;">TAX AMT</th>
                                                <th style="border-right:1px solid #222;">CGST</th>
                                                <th style="border-right:1px solid #222;">SGST</th>
                                                <th style="border-right:1px solid #222;">IGST</th>
                                            </tr>
                                            <?php
                                                            $this->db->select('tax');
                                                            $this->db->select_sum('purchase_price');
                                                            $this->db->select_sum('sgst');
                                                            $this->db->select_sum('cgst');
                                                            $this->db->select_sum('igst');
                                                            $this->db->select_sum('taxAmount');
                                                            $this->db->from('proforma_product');
                                                            $this->db->where('invoiceID', $result->id);
                                                            $this->db->where('InvoiceNo', $result->invoiceNumber);
                                                            $this->db->where('type', 'Proforma');
                                                            $this->db->group_by('tax');
                                                            $tax = $this->db->get()->result();
                                                            $tcount = 4;
                                                            if (isset($tax) && !empty($tax)) {
                                                                foreach ($tax as $row) { 
                                                        ?>
                                                    <tr style="font-size:12px">
                                                        <td style="text-align:center;"><?= $row->tax; ?>%</td>
                                                        <td style="text-align:center;"><?= number_format($row->purchase_price, 3); ?></td>
                                                        <td style="text-align:center;"><?= number_format($row->taxAmount, 3) ?></td>
                                                        <td style="text-align:center;"><?= number_format($row->cgst, 3); ?></td>
                                                        <td style="text-align:center;"><?= number_format($row->sgst, 3); ?></td>
                                                        <td style="text-align:center;"><?= number_format($row->igst, 3); ?></td>
                                                    </tr>
                                                    <?php $tcount--; } } ?>

                                                    <?php 
                                                            if ($tcount > 0) {
                                                                for ($i = 0; $i < $tcount; $i++) { 
                                                        ?>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <?php } } ?>

                                            <?php   
                                             ?>
                                        </table>
                                        <span style="margin-right: 1em;"><b>No of ltems : </b><?= $counts + 1; ?> </span>
                                        <span><b>Total Qty : </b><?= $nosItm; ?></span>
                                        <div><b>Remark : </b> <?= $result->remark; ?></div>
                                        <div><b>Rs In Words : </b><?= ucwords($result->amountInWords); ?></div>
                                    </td>
                                    <td class="border-top" style="padding:0px">
                                        <table style="width: 100%; border-collapse: collapse; margin:0px">
                                            <tr style="font-size:12px">
                                                <td class="border-right" style="width:40%"><b>GROSS AMT.</b></td>
                                                <td><b style="float:right"><?= number_format($totalRate, 3) ?></b></td>
                                            </tr>
                                            <tr>
                                                <td class="border-right border-top"><b>LESS DIS.</b></td>
                                                <td class="border-top"><b style="float:right"></b></td>
                                            </tr>
                                            <tr>
                                                <td class="border-right border-top"><b>LESS CD</b></td>
                                                <td class="border-top"><b style="float:right"></b></td>
                                            </tr>
                                            <tr class="d-none">
                                                <td class="border-right border-top"><b>SUB TOTAL</b></td>
                                                <td class="border-top"><b style="float:right"><?= number_format($result->taxableAmount, 3); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td class="border-right border-top"><b>GST</b></td>
                                                <td class="border-top"><b style="float:right"><?= number_format($result->totalTax, 3); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td class="border-right border-top"><b>TDS</b></td>
                                                <td class="border-top"><b style="float:right"></b></td>
                                            </tr>
                                            <tr>
                                                <td class="border-right border-top"><b>ROUND OFF</b></td>
                                                <td class="border-top"><b style="float:right"><?= number_format($result->roundOff, 3); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td class="border-right border-top"><b>NET AMT.</b></td>
                                                <td class="border-top"><b style="float:right"><?= number_format($result->total, 3); ?></b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border-top" style="padding:0px">
                                        <table style="width:100%; margin:0px;  border-collapse: collapse; ">
                                            <tr>
                                                <td style="width:18%; vertical-align: bottom;text-align:center;" class="border-right"> 
                                                        <?php if($result->einvoie_details != false){ ?>
                                                        <img src="<?= $result->einvoie_details->QRCodeUrl ?>" width="125" />
                                                        <?php } ?> 
                                                </td>
                                                <td>
                                                        <small>
                                                                <?php 
                                                                        if (!empty($compProfile->content)) {
                                                                                echo $compProfile->content;
                                                                        }
                                                                ?>
                                                    </small>
                                                    <h6 style="margin:0px">E & O E</h6>
                                                </td>
                                                <td style="width:18%; vertical-align: bottom;" class="border-left">
                                                    <small style="position: relative; bottom: 0;"> &nbsp; (Authorised Signatory)</small>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                               <tr>
                                       <td colspan="2" class="text-center border-top"><b>IRN :- </b><?= $result->Irn; ?></td>
                               </tr>
                        </table>
                       <div style="font-size: 10px; text-align: center; margin-top: 5px;">
                               This is computer generated copy by Uday Plastic
                       </div>
               </div>
                <?php } } ?>
                
               <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
               <script type="text/javascript">
                        $(document).ready(function() {
                                window.print();
                                
                                setTimeout("closePrintView()", 3000);
                        });

                        function closePrintView() {
                                document.location.href = "<?= base_url(); ?>proforma";
                        }
                </script>
        </body>
</html>