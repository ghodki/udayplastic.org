<!DOCTYPE html>
<html>
    <head>
        <title>Installation Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    </head>
    <body>
        <div class="container">
            <table style="border-collapse: collapse; border:1px solid #4c8ec9; width: 100%; font-size: 12px; text-align: left; color: #114574;">
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:0 1px; padding: 5px;" colspan="4">
                        <table style="width: 100%; border: 0px;">
                            <tr>
                                <td style="width: 100px; text-align: left;">
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
                                    <div style="background: #793532;color: #fff;font-size: 20px;font-weight: bold;padding: 2px 20px 5px 20px; border-radius: 40px; width: 200px; margin: 0 auto;">
                                        Installation Report
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
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0px;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left; width: 20%;">
                        Serial Number
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;width: 30%;">
                        <?= $installationData->billNumber; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;width: 25%;">
                        Manual Installation No.
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; width: 25%;">
                        <?= $installationData->manualReportNo; ?>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Installation Date
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= date('d-m-Y', strtotime($installationData->installationDate)); ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Product Category
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; ">
                        <?= get_title('category', ['id' => $installationData->categoryId], 'name'); ?>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Product Type
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= get_title('brand', ['id' => $installationData->brandId], 'name'); ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Product Model/Name
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; ">
                        <?= $installationData->productModelName; ?>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        M/C Sr. No.
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->machineSerialNo; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Engineer Code
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->engineerCodeName; ?>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Meter Reading
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->meterReading; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Warranty Duration
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->warrantyDuration; ?>
                    </td>
                </tr>
                <?php if ($installationData->installationLocation === "No") { ?>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Consign Name
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->consignName; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Designation
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; ">
                        <?= $installationData->designation; ?>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Installation Address
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->installationAddress; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Contact Person
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; ">
                        <?= $installationData->contactPerson; ?>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Contact Number
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->contactNumber; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Key Operator
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; ">
                        <?= $installationData->keyOperator; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <?php } ?>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <td colspan="2" style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align: top;">
                        <table style="width: 100%; border: 0;border-collapse: collapse;color: #114574;">
                            <tr style="border-bottom: 1px solid #4c8ec9;">
                                <td style="border-bottom: 1px solid #4c8ec9; height: 70px; padding: 5px; vertical-align: top;" colspan="2">
                                    <b>Installation  Details</b>
                                    <br />
                                    Name : <?= $installationData->installCustomerName; ?>
                                    <br />
                                    Email Id : <?= $installationData->installEmail; ?>
                                    <br />
                                    Contact Number : <?= $installationData->installContactNo; ?>
                                    <br />
                                    Address : <?= $installationData->placeOfInstallation; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px; verticla-align:middle;">
                                    <table>
                                        <tr>
                                            <?php if ($installationData->type === "Metro") { ?>
                                            <td style="padding-right: 15px;">
                                                <input type="checkbox" class="fl-left" checked="" />
                                            </td>
                                            <?php } else { ?>
                                            <td style="padding-right: 15px;">
                                                <input type="checkbox" class="fl-left" />
                                            </td>                                            
                                            <?php } ?>
                                            <td>Metro</td>
                                        </tr>                                       
                                    </table>
                                </td>
                                <td style=" padding: 5px; verticla-align:middle;">
                                    <table>
                                        <tr>
                                            <?php if ($installationData->type === "Remote") { ?>
                                            <td style="padding-right: 15px;">
                                                <input type="checkbox" class="fl-left" checked="" />
                                            </td>
                                            <?php } else { ?>
                                            <td style="padding-right: 15px;">
                                                <input type="checkbox" class="fl-left" />
                                            </td>                                
                                            <?php } ?>
                                            <td>Remote</td>
                                        </tr>
                                    </table> 
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2" style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align: top;">
                        <table style="width: 100%; border: 0;border-collapse: collapse;color: #114574;">
                            <tr>
                                <td style="border-bottom: 1px solid #4c8ec9; height: 50px; padding: 5px; vertical-align: top;">
                                    <b>Customer Details</b>
                                    <br />
                                    <?php $partyData = $this->db->select('name, email, contact, ContactPerson, address_1')->where('id', $installationData->customerId)->get('parties')->row(); ?>
                                    Name : <?= $partyData->name; ?>
                                    <br />
                                    Email Id : <?= $partyData->email; ?>
                                    <br />
                                    Contact Number : <?= $partyData->contact; ?>
                                    <br />
                                    Contact Person : <?= $partyData->ContactPerson; ?>
                                    <br />
                                    Address : <?= $partyData->address_1; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Telephone No. 1
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->telephoneNoOne; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Fax/Mob. No.
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->faxMobileNo; ?>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Telephone No. 2
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->telephoneNoTwo; ?>
                    </td>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Email
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->customerEmail; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;" colspan="2">
                        Business Hrs. 
                        From : <?= $installationData->businessStartTime; ?>
                        To : <?= $installationData->businessEndTime; ?>
                    </th>
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: left;">
                        Weekly Off
                    </th>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;">
                        <?= $installationData->weeklyOff; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: center;" colspan="4">
                        Accessories Installation
                    </th>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align:top;" colspan="4">
                        <table style="width: 100%; border: 0; border-collapse: collapse;color: #114574;">
                            <tr style="border-color: #4c8ec9;border-style: solid;border-width:0 1px 1px 0">
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;; width: 33%; text-align: left;">
                                    Product Name/Code
                                </th>
                                <th style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px; width: 33%; text-align: left;">
                                    Description
                                </th>
                                <th style="padding: 5px; width: 33%;border-width:1px 1px; text-align: left;">
                                    Serial No
                                </th>
                            </tr>
                            <?php
                                if (!empty($installationProducts)) {
                                    foreach ($installationProducts as $key => $product) {
                            ?>
                            <tr style="border-color: #4c8ec9;border-style: solid;border-width:0 1px 1px 0;">
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    <?= get_title(['product'], ['id' => $product->productId], 'p_name'); ?>
                                </td>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    <?= $product->productDescription; ?>
                                </td>
                                <td style="padding: 5px;border-color: #4c8ec9;border-style: solid;border-width:1px 1px;">
                                    <?= $product->serialNumber; ?>
                                </td>
                            </tr>
                            <?php } } ?>
                        </table>
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <th style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; text-align: center;" colspan="4">
                        Key Operator Training
                    </th>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 0px; vertical-align:top;" colspan="4">
                        <table style="width: 100%; border: 0; border-collapse: collapse;color: #114574;">
                            <?php
                                if (!empty($installationData->trainingName)) {
                                    $keyOperatorTraning = explode(',', $installationData->trainingName); 
                                }

                                if (!empty($keyOperatorTraning)) {
                                    $split = array_chunk($keyOperatorTraning, 3);
                                    foreach ($split as $key => $row) {
                            ?>
                            <tr>
                                <td style="width: 3%; padding: 10px;">
                                    <?php if (!empty($row[0])) { ?>
                                    <input type="checkbox" checked="" />
                                    <?php } ?>
                                </td>
                                <td style="width: 30%; padding: 10px;">
                                    <?= !empty($row[0]) ? $row[0] : ""; ?>
                                </td>
                                <td style="width: 3%; padding: 10px;">
                                    <?php if (!empty($row[1])) { ?>
                                    <input type="checkbox" checked="" />
                                    <?php } ?>
                                </td>
                                <td style="width: 30%; padding: 10px;">
                                    <?= !empty($row[1]) ? $row[1] : ""; ?>
                                </td>
                                <td style="width: 3%; padding: 10px;">
                                    <?php if (!empty($row[2])) { ?>
                                    <input type="checkbox" checked="" />
                                    <?php } ?>
                                </td>
                                <td style="width: 30%; padding: 10px;">
                                    <?= !empty($row[2]) ? $row[2] : ""; ?>
                                </td>
                            </tr>
                            <?php } } ?> 
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;" colspan="2">
                        Engineer's Signature & Date
                        <br /><br /><br />
                        Engr. Name
                    </td>
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px;" colspan="2">
                        We certify that the Installation has been done satisfactory.
                        <br /><br /><br />
                        Customer's Signature & Stamp
                    </td>
                </tr>
                <tr style="border-color: #4c8ec9;border-style: solid;border-width:1px 0;">
                    <td style="border-color: #4c8ec9;border-style: solid;border-width:1px 1px; padding: 5px; height: 50px;" colspan="4">
                        Remark
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>