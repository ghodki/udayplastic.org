<!DOCTYPE html>
<html>
    <head>
        <title>Service Call Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <style type="text/css">
            body {
                margin: 0px;
                padding: 0px;
            }
            th, td {
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <table style="border-collapse: collapse; border:1px solid #4c8ec9; width: 100%; font-size: 12px; text-align: left; color: #114574;">
                <tr>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:0 1px; padding: 5px;" colspan="4">
                        <table style="width: 100%; border: 0px;">
                            <tr>
                                <td style="width: 100px;">
                                    <?php
                                        $logoUrl = 'uploads/complogo/' . $profileData->logo; 
                                        $ext = pathinfo($logoUrl, PATHINFO_EXTENSION);
                                        $logoImg = file_get_contents($logoUrl);
                                        $logo = 'data:image/' . $ext . ';base64,' . base64_encode($logoImg);                                    
                                        if (!empty($profileData->logo)) { 
                                    ?>
                                    <img src="<?= $logo; ?>" style="width: 100px;" />
                                    <?php } ?>
                                </td>
                                <td style="text-align: center; color:#4c8ec9;">
                                    <h1 style="font-size: 30px; font-weight: bold; text-transform: uppercase; color: #542f26; margin: 0px; padding: 0px;">
                                        <?= $this->session->userdata('BranchRole') ? $companyBranchData->BranchName : $companyData->company_name; ?>
                                    </h1>
                                    <p style="margin: 0px; padding: 0px; font-weight: 600;">
                                        <?= $this->session->userdata('BranchRole') ? $companyBranchData->Address : $companyData->address; ?>, 
                                        <?= $this->session->userdata('BranchRole') ? get_title('state_list', ['id' => $companyBranchData->State], 'state') : get_title('state_list', ['id' => $companyData->state], 'state'); ?>
                                    </p>
                                    <p style="margin: 0 0 10px 0; padding: 0px; font-weight: 600;">
                                        Mob. : +91-<?= $this->session->userdata('BranchRole') ? $companyBranchData->Mobile : $companyData->number; ?>
                                        Email : <?= $this->session->userdata('BranchRole') ? $companyBranchData->Email : $companyData->email; ?>
                                        GST : <?= $this->session->userdata('BranchRole') ? $companyBranchData->GST : $companyData->gst_no; ?>
                                    </p>
                                    <div style="background: #793532;color: #fff;font-size: 18px;font-weight: bold;padding: 2px 20px 5px 20px;border-radius: 40px; width: 250px; margin: 0 auto;">
                                        Service Call Report
                                    </div>
                                </td>
                                <td style="width: 100px; text-align: right;">
                                    <?php if (!empty($profileData->logo)) { ?>
                                    <img src="<?= $logo; ?>" style="width: 100px;" />
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; width: 50%; vertical-align:top;" colspan="2">
                        <table style="width: 100%; border-collapse: collapse;color: #114574; text-align: left;">
                            <tr>
                                <td colspan="2" style="padding: 5px; border-bottom:1px solid #114574;">
                                    <strong>Serial Number : </strong>
                                    <?= $serviceCallData->billNumber; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Product Category : </strong>
                                    <?= get_title('category', ['id' => $serviceCallData->categoryId], 'name'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Product Model/Name : </strong>
                                    <?= $serviceCallData->productModelName; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Machine Serial No : </strong>
                                    <?= $serviceCallData->machineSerialNo; ?>
                                </td>
                            </tr>
                            <tr>
                                <?php $partyData = $this->db->select('name, contact, email, address_1')->where('id', $serviceCallData->customerId)->get('parties')->row(); ?>
                                <td colspan="2" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Email Id : </strong>
                                    <?= $partyData->email; ?>
                                </td>
                            </tr>
                            <tr>                                
                                <td colspan="2" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Customer Address : </strong>
                                    <?= $partyData->address_1; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;border-bottom:1px solid #114574;" colspan="2">
                                    <strong>Date : </strong>
                                    <?= date('d-m-Y', strtotime($serviceCallData->date)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;border-bottom:1px solid #114574;" colspan="2">
                                    <strong>Counter Reading : </strong>
                                    <?= $serviceCallData->counterReading; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px; height: 50px;border-bottom:1px solid  #4c8ec9;">
                                    <strong>Problem : </strong>
                                    <?= $serviceCallData->problem; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align:top;" colspan="2">
                        <table style="width: 100%; border-collapse: collapse;color: #114574; text-align: left;">
                            <tr>
                                <td colspan="10" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Customer Name : </strong>
                                    <?= $partyData->name; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Brand : </strong>
                                    <?= get_title('brand', ['id' => $serviceCallData->brandId], 'name'); ?>
                                </td>
                            </tr>
                            <tr>
                                 <td colspan="10" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Model Number : </strong>
                                    <?= $serviceCallData->modelNumber; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Contract Status : </strong>
                                    <?= $serviceCallData->contractStatus; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Call Type : </strong>
                                    <?= $serviceCallData->callType; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Mobile Number : </strong>
                                    <?= $partyData->contact; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" style="padding: 5px;border-bottom:1px solid #114574;">
                                    <strong>Cause : </strong>
                                    <?= $serviceCallData->cause; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php if ($serviceCallData->installationLocation === "No") { ?>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left; width: 20%;">
                        Consign Name
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;width: 30%;">
                        <?= $serviceCallData->consignName; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;width: 20%;">
                        Designation
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; width: 30%;">
                        <?= $serviceCallData->designation; ?>
                    </td>
                </tr>
                <tr>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left; width: 20%;">
                        Installation Address
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;width: 30%;">
                        <?= $serviceCallData->installationAddress; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;width: 20%;">
                        Contact Person
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; width: 30%;">
                        <?= $serviceCallData->contactPerson; ?>
                    </td>
                </tr>
                <tr>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left; width: 20%;">
                        Contact Number
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;width: 30%;">
                        <?= $serviceCallData->contactNumber; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;width: 20%;">
                        Key Operator
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; width: 30%;">
                        <?= $serviceCallData->keyOperator; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <?php } ?>
                <tr>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align:top; width: 50%;" colspan="2">
                        <table style="width: 100%; border-collapse: collapse;color: #114574; text-align: left;">
                            <tr>
                                <th style="padding: 5px;border-bottom:1px solid  #4c8ec9; width:30%;">
                                    Black A3
                                </th>
                                <td style="padding: 5px;border-bottom:1px solid  #4c8ec9;">
                                    <?= $serviceCallData->blackA3; ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding: 5px;border-bottom:1px solid  #4c8ec9;">
                                    Black A4
                                </th>
                                <td style="padding: 5px;border-bottom:1px solid  #4c8ec9;">
                                    <?= $serviceCallData->blackA4; ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding: 5px;border-bottom:1px solid  #4c8ec9;border-width:1px 1px;">
                                    Colour A3
                                </th>
                                <td style="padding: 5px;border-bottom:1px solid  #4c8ec9;">
                                    <?= $serviceCallData->colourA3; ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding: 5px;border-bottom:1px solid  #4c8ec9;">
                                    Colour A4
                                </th>
                                <td style="padding: 5px;border-bottom:1px solid  #4c8ec9;">
                                    <?= $serviceCallData->colourA4; ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding: 5px;">
                                   Total
                                </th>
                                <td style="padding: 5px;">
                                    <?= $serviceCallData->totalReading; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; vertical-align: top;" colspan="2">
                       <strong>Action Token : </strong>
                        <?= $serviceCallData->actionTaken; ?>
                    </td>
                </tr>
                <tr>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px;" colspan="4">
                        <table style="width: 100%; border-collapse: collapse;color: #114574; text-align: left;">
                            <tr>
                                <th style="padding: 5px;width: 35%;">
                                    Spare Used/Reg. This Call
                                </th>
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    Part No.
                                </th>
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    Quantity
                                </th>
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    Unit Price
                                </th>
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    Amount
                                </th>
                            </tr>
                            <?php
                                if (isset($serviceCallProducts) && !empty($serviceCallProducts)) {
                                    $totalQuantity = 0;
                                    foreach($serviceCallProducts as $key => $product) {
                                        $totalQuantity += $product->quantity;
                            ?>
                            <tr>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    <?= get_title(['product'], ['id' => $product->productId], 'p_name'); ?>
                                </td>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;text-align:right;">
                                    <?= $product->partNumber; ?>
                                </td>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;text-align:right;">
                                    <?= $product->quantity; ?>
                                </td>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;text-align:right;">
                                    <?= indianMoneyFormat($product->unitPrice); ?>
                                </td>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;text-align:right;">
                                    <?= indianMoneyFormat($product->amount); ?>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr>
                                <th colspan="2" style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px; text-align: right;">
                                    Total
                                </th>
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;text-align:right;">
                                    <?= $totalQuantity; ?>
                                </th>
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;text-align:right;">
                                </th>                                
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;text-align:right;">
                                    <?= indianMoneyFormat($serviceCallData->grandTotalAmount); ?>
                                </th>
                            </tr>
                            <tr>
                                <td style="padding:0px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;" colspan="4">
                                    <table style="width: 100%; border-collapse: collapse;color: #114574; text-align: left;">
                                        <tr style="border-color: #4c8ec9;border-style: solid;border-width:0px 0;">
                                            <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                                Billable
                                            </th>
                                            <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                                <?php if ($serviceCallData->billStatus === 'Billable') { ?>
                                                <input type="checkbox" checked="" style="margin-right: 5px;" />
                                                <?php } else { ?>
                                                <input type="checkbox" style="margin-right: 5px;" />
                                                <?php } ?>
                                            </td>
                                            <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                                Not Billable
                                            </th>
                                            <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                                <?php if ($serviceCallData->billStatus === 'Not Billable') { ?>
                                                <input type="checkbox" checked="" style="margin-right: 5px;" />
                                                <?php } else { ?>
                                                <input type="checkbox" style="margin-right: 5px;" />
                                                <?php } ?>
                                            </td>
                                            <th style="padding: 5px; text-align: right;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                                Estimated Cost of Repair (Taxes Extra)
                                            </th>
                                        </tr>
                                    </table>
                                </td>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    <?php if ($serviceCallData->billStatus === 'Estimated Cost of Repair (Taxes Extra)') { ?>
                                    <input type="checkbox" checked="" />
                                    <?php } else { ?>
                                    <input type="checkbox" />
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align: top;" colspan="2">
                        <table style="width: 100%; border-collapse: collapse;color: #114574; text-align: left;">
                            <tr style="border-color: #4c8ec9;border-style: solid;border-width:0px 0;">
                                <th style="padding: 5px; width: 50%;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    Machine Status
                                </th>
                                <td style="padding: 5px; border-color: #4c8ec9; border-style: solid; border-width: 1px 1px;">
                                    <?php if ($serviceCallData->machineStatus === "OK") { ?>
                                    <input Type="checkbox" checked="" style="margin-top: 5px;" /> Ok
                                    <?php } else { ?>
                                    <input Type="checkbox" style="margin-top: 5px;" /> Ok
                                    <?php } ?>
                                </td>
                                <td style="padding: 5px; border-color: #4c8ec9; border-style: solid; border-width: 1px 1px;">
                                    <?php if ($serviceCallData->machineStatus === 'Not OK') { ?>
                                    <input Type="checkbox" checked="" style="margin-top: 5px;" /> Not Ok
                                    <?php } else { ?>
                                    <input Type="checkbox" style="margin-top: 5px;" /> Not Ok
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;border-bottom:1px solid #114574;" colspan="3">
                                    Our machine is working to our satisfaction the meter reading of this report can be used for billing purpose:
                                    <br /><br /><br />
                                    <strong>Customer's Signature</strong>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding: 5px; height: 85px; vertical-align: top; text-align: left;" colspan="3">Remark</th>
                            </tr>
                        </table>
                    </td>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align: top;" colspan="2">
                        <table style="width: 100%; border-collapse: collapse;color: #114574; text-align: left;">
                            <tr>
                                <td style="padding: 5px;border-bottom:1px solid #114574;">
                                    Estimate cost of Repair (as mentioned above) is approved.
                                    <br /><br /><br />
                                    <strong>Customer's Signature & Seal</strong>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding: 5px; text-align: center;border-bottom:1px solid #114574;">
                                   Engineer Details
                                </th>
                            </tr>
                            <tr>
                                <td style="padding: 5px;border-bottom:1px solid #114574;">
                                   <strong>Name : </strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;border-bottom:1px solid #114574;">
                                   <strong>Signature : </strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">
                                   Please contact for any support / information / order to our email address :
                                   <p style="text-align: center; padding: 0px; margin: 0px;">
                                       <?= $this->session->userdata('BranchRole') ? $companyBranchData->Email : $companyData->email; ?>
                                   </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>            
        </div>
    </body>
</html>