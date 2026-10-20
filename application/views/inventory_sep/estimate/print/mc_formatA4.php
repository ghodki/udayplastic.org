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
                padding: 0px;
                padding-left: 0em;
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
                font-size: 12px;
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

            $estimateConfig = $this->user_model->getRow('estimateconfig', $where);

            if ($compProfile) {
                $totalInvoiceCopies = $compProfile->total_invoice;
                for ($invoiceCopies = 0; $invoiceCopies < $totalInvoiceCopies; $invoiceCopies++) {                        
        ?>
        <?php if ($invoiceCopies == 0) { ?>
        <p class="copy_title">ORIGINAL</p>
        <?php } elseif ($invoiceCopies == 1) { ?>
        <p class="copy_title" style="page-break-before: always;">DUPLICATE</p>
        <?php } elseif ($invoiceCopies == 2) { ?>
        <p class="copy_title" style="page-break-before: always;">TRIPLICATE</p>
        <?php } else { ?>
        <p class="copy_title" style="page-break-before: always;">ONLY DUPLICATE</p>
        <?php } ?>
        
        <h1 style="text-align: center; margin:0px;"><?= ($estimateConfig->title) ? $estimateConfig->title : 'GST-Invoice'; ?></h1>
        
        <table class="outer" style="border-bottom: 0px;">                        
            <thead id="header_1">
                <tr>
                    <td style="width: 65%; padding-left: 2px;" class="border-right border-bottom">
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
                    <td rowspan="3" style="padding: 0px; vertical-align: text-top; padding-left: 2px;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 0px;">
                            <tr style="border-bottom: 1px solid #000;">
                                <td class="border-right" style="border-bottom: 1px solid #000; width: 45%;"><b>Date</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Invoice No</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->invoiceNumber; ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Order Date</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->orderDate ? date('d/m/Y', strtotime($result->orderDate)) : ''; ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Order No</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->orderNumber ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Dispatch By</b></td>
                                <td style="border-bottom: 1px solid #000;"><?= ucwords($result->transport); ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Docket No</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->lrNumber ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Reached Date</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->reachedDate ? date('d/m/Y', strtotime($result->reachedDate)) : ''; ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Eway Bill</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->ewayBill; ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="border-right" style="border-bottom: 1px solid #000;"><b>Challan No.</b></td>
                                <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->challanNumber; ?></td>
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
                    <td style="width: 70%; padding-left: 2px;" class="border-right border-bottom">
                       <?php
                           if ($result->customerId != '' && $result->customerId != 0) {
                               $party = $this->db->get_where('parties', ['id' => $result->customerId])->row(); 
                           }
                       ?>
                        <b>Buyer Details</b>
                        <br />
                        <b><?= $party->name; ?></b>
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
                    <td style="width: 70%; border-right: 1px solid #000; padding-left: 2px; padding-bottom: 2px;">
                        <b>Consignee Details</b>
                        <br />
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
                   <td style="vertical-align: text-top;" colspan="2">
                       <table class="mytable" style="margin-top: -2px; width: 100%;">
                           <thead>
                               <tr style="background-color: #999999; -webkit-print-color-adjust: exact; font-size: 14px;">
                                   <th class="border-right">S/N</th>
                                   <th class="border-right">HSN</th>
                                   <th class="border-right">PARTICULAR</th>
                                   <th class="border-right">GST</th>
                                   <th class="border-right">QTY</th>
                                   <th class="border-right">RATE</th>
                                   <th class="border-right">DIS</th>
                                   <th class="border-right">CD</th>
                                   <th class="border-right">NLC</th>
                                   <th>AMOUNT</th>
                               </tr>
                           </thead>
                           <tbody style="font-size: 12px;">
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
                                   <?= ($estimateConfig->title) ? $estimateConfig->title : 'GST-Invoice'; ?>
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
                                                       <td class="border-right" style="border-bottom: 1px solid #000; width: 45%;"><b>Date</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Invoice No</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->invoiceNumber; ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Order Date</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->orderDate ? date('d/m/Y', strtotime($result->orderDate)) : ''; ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Order No</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->orderNumber ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Dispatch By</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= ucwords($result->transport); ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Docket No</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->lrNumber ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Reached Date</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->reachedDate ? date('d/m/Y', strtotime($result->reachedDate)) : ''; ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Eway Bill</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->ewayBill; ?></td>
                                                   </tr>
                                                   <tr class="border-bottom">
                                                       <td class="border-right" style="border-bottom: 1px solid #000;"><b>Challan No.</b></td>
                                                       <td style="border-bottom: 1px solid #000; padding-left: 2px;"><?= $result->challanNumber; ?></td>
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
                                           <td style="width: 70%;" class="border-right border-bottom">
                                               <?php
                                                   if ($result->customerId != '' && $result->customerId != 0) {                                                            
                                                       $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                                   }
                                               ?>
                                               <b>Buyer Details</b>
                                               <br />
                                               <b><?= $party->name; ?></b>
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
                                           <td style="width: 70%; border-right: 1px solid #000;">
                                                   <b>Consignee Details</b>
                                                   <br />
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
                                   echo '<table class="mytable" style="margin-top: -2px; width: 100%;">'
                                            . '<thead>'
                                            . '<tr style="border-left: 1px solid; border-right: 1px solid; background-color: #999999; -webkit-print-color-adjust: exact; font-size: 14px;">'
                                            . '<th class="border-right">S/N</th>'
                                            . '<th class="border-right">HSN</th>'
                                            . '<th class="border-right">PARTICULAR</th>'
                                            . '<th class="border-right">GST</th>'
                                            . '<th class="border-right">QTY</th>'
                                            . '<th class="border-right">RATE</th>'
                                            . '<th class="border-right">DIS</th>'
                                            . '<th class="border-right">CD</th>'
                                            . '<th class="border-right">NLC</th>'
                                            . '<th>AMOUNT</th>'
                                            . '</tr>'
                                            . '</thead>'
                                            . '<tbody style="border-left: 1px solid; border-right: 1px solid; font-size: 12px;">';
                                   }
                                   $qtty = $row->qty;
                                   $nosItm += $qtty;
                                   $taxIte =  get_title('product', ['id' => $row->tax], 'tax');
                                   $rate = $row->rate;
                                   $totalDiscount += get_title('product', ['id' => $row->product_id], 'mrp') * $row->qty;
                               ?>

                               <tr>
                                   <td class="border-right" style="text-align: center;"><?= $key + 1; ?></td>
                                   <td class="border-right text-right" style="padding-right: 2px;"><?= $row->hsn; ?></td>
                                   <td class="border-right" style="padding-left: 2px;">
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
                                           <div class="descc" style="padding-right: 2px;"><?= $row->product_dec; ?></div>
                                           <?php } ?>
                                       </small>
                                   </td>
                                   <td class="border-right text-right" style="padding-right: 2px;"><?= $row->tax; ?>%</td>
                                   <td class="border-right" style="text-align: center; padding-right: 2px;">
                                       <?php
                                           $totalRate += $row->purchase_price;
                                           if ($estimateconfig->SubUnit == 'Y') {
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
                                   <td class="border-right text-right" style="padding-right: 2px;"><?= money_formats($row->purchase_price / $row->qty); ?></td>
                                   <td class="border-right text-right" style="padding-right: 2px;">
                                       <?= ($row->discount / $row->rate) * 100; ?>%
                                   </td>
                                   <td class="border-right text-right" style="padding-right: 2px;">
                                       <?= $row->discount; ?>
                                   </td>
                                   <td class="border-right text-right" style="padding-right: 2px;"><?= $row->purchase_price; ?></td>
                                   <td class="text-right" style="padding-right: 2px;"><?= money_formats($row->netAmount); ?></td>
                               </tr>

                               <?php 
                                    $counts = $key; $totalItemLast = ($key % $productPerPage) + 1;                                        
                                    } }
                                    
                                   $extrarow = $productPerPage - $totalItemLast;
                                   if ($extrarow > 13) {
                                       for ($i = 0; $i <= $extrarow - 12; $i++) {
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
                                   <td>&nbsp;</td>
                               </tr>
                               <?php  } } ?>                                                                    
                           </tbody>
                       </table>
                   </td>
               </tr>
           </tbody>
       </table>
       
        <table id="footerkk">
            <tr style="font-size: 12px;">
                <td class="border-right" style="padding: 0px; width: 70%;">
                    <table class="gstTable" style="margin-top: 0px;">
                        <tr style="background-color: #999999; -webkit-print-color-adjust: exact; font-size: 12px;">
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
                           $this->db->from('estimate_product');
                           $this->db->where('invoiceID', $result->id);
                           $this->db->where('InvoiceNo', $result->invoiceNumber);
                           $this->db->where('type', 'Sale');
                           $this->db->group_by('tax');
                           $tax = $this->db->get()->result();
                           $tcount = 4;
                           if (isset($tax) && !empty($tax)) {
                               foreach ($tax as $row) { 
                       ?>
                       <tr style="font-size: 12px;">
                           <td style="text-align: center;"><?= $row->tax; ?>%</td>
                           <td style="text-align: center;"><?= money_formats($row->purchase_price, 2); ?></td>
                           <td style="text-align: center;"><?= money_formats($row->taxAmount, 2) ?></td>
                           <td style="text-align: center;"><?= money_formats($row->cgst, 2); ?></td>
                           <td style="text-align: center;"><?= money_formats($row->sgst, 2); ?></td>
                           <td style="text-align: center;"><?= money_formats($row->igst, 2); ?></td>
                       </tr>
                       <?php
                           $tcount--; } }

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
                       <tr>
                           <td colspan="6">
                                <span style="padding-left: 2px;"><b>No of Items : </b><?= $counts + 1; ?></span>
                                <span style="padding-left: 2px;"><b>Total Qty : </b><?= $nosItm; ?></span>
                                <div style="padding-left: 2px;"><b>Remark : </b><?= $result->remark; ?></div>
                                <div style="padding-left: 2px;"><b>Rs In Words : </b><?= ucwords($result->amountInWords); ?></div>
                           </td>
                       </tr>
                   </table>
               </td>
               <td class="border-top" style="padding: 0px; width: 30%;">
                   <table style="width: 100%; border-collapse: collapse; margin: 0px;">
                       <tr style="font-size: 12px;">
                           <td class="border-right" style="width: 40%; padding-left: 2px;"><b>GROSS AMT.</b></td>
                           <td><b style="float: right; padding-right: 2px;"><?= money_formats($totalRate) ?></b></td>
                       </tr>
                       <tr>
                           <td class="border-right border-top" style="padding-left: 2px;"><b>LESS DIS.</b></td>
                           <td class="border-top"><b style="float: right; padding-right: 2px;"></b></td>
                       </tr>
                       <tr>
                           <td class="border-right border-top" style="padding-left: 2px;"><b>LESS CD</b></td>
                           <td class="border-top"><b style="float: right; padding-right: 2px;"></b></td>
                       </tr>
                       <tr class="d-none">
                           <td class="border-right border-top" style="padding-left: 2px;"><b>SUB TOTAL</b></td>
                           <td class="border-top"><b style="float: right; padding-right: 2px;"><?= money_formats($result->taxableAmount); ?></b></td>
                       </tr>
                       <tr>
                           <td class="border-right border-top" style="padding-left: 2px;"><b>GST</b></td>
                           <td class="border-top"><b style="float: right; padding-right: 2px;"><?= money_formats($result->totalTax); ?></b></td>
                       </tr>
                       <tr>
                           <td class="border-right border-top" style="padding-left: 2px;"><b>TDS</b></td>
                           <td class="border-top"><b style="float: right; padding-right: 2px;"><?= money_formats($result->tds); ?></b></td>
                       </tr>
                       <tr>
                           <td class="border-right border-top" style="padding-left: 2px;"><b>ROUND OFF</b></td>
                           <td class="border-top"><b style="float: right; padding-right: 2px;"><?= money_formats($result->roundOff); ?></b></td>
                       </tr>
                       <tr>
                           <td class="border-right border-top" style="padding-left: 2px;"><b>NET AMT.</b></td>
                           <td class="border-top"><b style="float: right; padding-right: 2px;"><?= money_formats($result->total); ?></b></td>
                       </tr>
                   </table>
               </td>
           </tr>
           <tr>
               <td colspan="2" class="border-top" style="padding:0px">
                   <table style="width:100%; margin:0px;  border-collapse: collapse; ">
                       <tr>
                           <td style="width: 15%; vertical-align: bottom;text-align:center;" class="border-right">
                               <img src="<?= get_title('e_invoice_details', ['invoiceNumber' => $result->invoiceNumber], 'QRCodeUrl'); ?>" style="width: 100px;" />
                           </td>
                           <td style="width: 70%; padding-left: 2px;">
                               <small>
                                   <?php 
                                       if (!empty($compProfile->content)) {
                                           echo $compProfile->content;
                                       }
                                   ?>
                               </small>
                               <h6 style="margin: 0px; padding-left: 2px;">E & O E</h6>
                           </td>
                           <td style="width: 15%; vertical-align: bottom;" class="border-left">
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
        
        <p style="font-size: 10px; text-align: center; margin-top: 5px;">
            This is computer generated copy by Uday Plastic
        </p>
        <?php } } ?>

        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                 window.print();
            });
        </script>
    </body>
</html>