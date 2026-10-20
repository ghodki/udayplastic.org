<!DOCTYPE html>
<html>
    <head>
        <title>Uday Plastic - FORM - E</title>
        <meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1" />
    	<meta http-equiv="x-ua-compatible" content="ie=edge" />
        <link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <style type="text/css">
            body {
                font-family: sans-serif;
                font-size: 12px; 
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 3px;
                text-align: left;
            }
        </style>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                window.print();
                window.onafterprint = function() {
                    window.close();
                };                
            });
        </script>
    </head>
    <body>
        <?php
            $userId = $this->session->userdata('user_id');
            $compId = $this->session->userdata('comp_id');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $invoiceWhere = ['id' => $invoiceId, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $invoiceProductWhere = ['invoiceId' => $invoiceId, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $compWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $invoiceWhere['CompanyBranchId'] = $compBranchId;
                $invoiceProductWhere['CompanyBranchId'] = $compBranchId;
                $compWhere['CompanyBranchId'] = $compBranchId;
                $compBranchInfo = $this->db->select('BranchName, Address')->where(['Id' => $compBranchId, 'BranchUserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1])->get('companybranch')->row();
            } else {
                $compInfo = $this->db->select('company_name, address')->where(['id' => $compId, 'user_id' => $userId])->get('inventory_billing')->row();
            }
        ?>
        <table>
            <tr>
                <th style="border-right: none; font-size: 20px; white-space: nowrap;">Vendor Name</th>
                <th style="border-left: none; border-right: none; font-size: 20px; white-space: nowrap;">:</th>
                <th style="border-left: none; font-size: 20px; white-space: nowrap;">
                    <?= $this->session->userdata('BranchRole') ? $compBranchInfo->BranchName : $compInfo->company_name; ?>
                </th>
            </tr>
            <tr>
                <th style="border-right: none; font-size: 18px; vertical-align: top;">Address</th>
                <th style="border-left: none; border-right: none; font-size: 18px; vertical-align: top;">:</th>
                <th style="border-left: none; font-size: 18px;">
                    <?= $this->session->userdata('BranchRole') ? $compBranchInfo->Address : $compInfo->address; ?>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="height: 40px;"></td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: center;">
                    <b style="font-size: 16px;">FROM - E</b>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="height: 20px;"></td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: center;">
                    <b style="font-size: 16px;">Form Of Guarantee</b>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="height: 20px;"></td>
            </tr>
            <tr>
                <th style="border-right: none; font-size: 16px;">Date</th>
                <th style="border-left: none; border-right: none; font-size: 16px;">:</th>
                <th style="border-left: none; font-size: 16px;">
                    <?= date('d-m-Y', strtotime(get_title('sales', $invoiceWhere, 'invoiceDate'))); ?>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="height: 20px;"></td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 0px;">
                    <table>
                        <tr>
                            <th>Sr</th>
                            <th>Bill No</th>
                            <th>Item Name</th>
                            <th>Batch Code</th>
                            <th>Date of Manufacturing</th>
                            <th>Date of Expiry</th>
                            <th>Qty</th>
                            <th>Amount in Rs</th>
                        </tr>
                        <?php
                            $invoiceProductQuery = $this->db->select('InvoiceNo, product_id, qty, netAmount')->where($invoiceProductWhere)->get('invoice_product');
                            if ($invoiceProductQuery->num_rows()) {
                                $sr = 0;
                                $totalQty = 0;
                                $totalAmount = 0;
                                foreach ($invoiceProductQuery->result() as $key => $invoiceData) {
                                    $sr++;
                                    $totalQty += $invoiceData->qty;
                                    $totalAmount += $invoiceData->netAmount;
                                    $productData = $this->db->select('p_name, unit, batchCode, dateOfManufacturing, dateOfExpiry')->where('id', $invoiceData->product_id)->get('product')->row();
                        ?>
                        <tr>
                            <td><?= $sr; ?></td>
                            <td><?= $invoiceData->InvoiceNo; ?></td>
                            <td><?= $productData->p_name; ?></td>
                            <td><?= $productData->batchCode; ?></td>
                            <td><?= $productData->dateOfManufacturing ? date('d-m-Y', strtotime($productData->dateOfManufacturing)) : ''; ?></td>
                            <td><?= $productData->dateOfExpiry ? date('d-m-Y', strtotime($productData->dateOfExpiry)) : ''; ?></td>
                            <td><?= $invoiceData->qty; ?>&nbsp;(<?= get_title('unit', ['id' => $productData->unit], 'uqc_code'); ?>)</td>
                            <td><?= indianMoneyFormat($invoiceData->netAmount); ?></td>
                        </tr>
                        <?php } } ?>
                        <tr>
                            <th colspan="6" style="text-align: right;">Total</th>
                            <th><?= $totalQty; ?></th>
                            <th><?= indianMoneyFormat($totalAmount); ?></th>
                        </tr>                        
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="height: 40px; border: none;"></td>
            </tr>
            <tr>
                <td colspan="3" style="border: none;">
                    <p style="text-align: justify;">
                        We hereby certify that food/foods mentioned in this invoice is / are warranted to be of the nature and /quality which/it/these purports/purported to be.
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="height: 20px; border: none;"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right; border: none;">
                    <b style="position: relative; left: -50px;">Yours Faithfully</b>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="height: 20px; border: none;"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right; border: none;">
                    <b style="position: relative; left: -120px;">For</b>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="height: 20px; border: none;"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right; border: none;">
                    <b style="position: relative; left: -15px;">Authorised Signatory</b>
                </td>
            </tr>
        </table>
        <p style="text-align: center;">
            This is a Computer Generated Copy by Uday Plastic
        </p>
    </body>
</html>