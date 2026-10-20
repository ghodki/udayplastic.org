<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <style type="text/css">
            b, span {
                font-family: math;
            }
            .data-list tr th {
                border-top: 1px solid #000; 
                border-bottom: 1px solid #000;
                padding: 5px;
            }
            .data-list tr td {
                padding: 2px;
            }
            th, td {
                white-space: nowrap;
            }
        </style>
    </head>
    <body>
        <?php 
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $Status = 1;

            $Where = ['account_group' => $AccountGroup, 'date >=' => $FromDate, 'date <=' => $ToDate, 'status' => $Status, 'user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $BranchInfo = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId, 'BranchUserId' => $UserId, 'CompId' => $CompId]);
                $Where['CompanyBranchId'] = $CompBranchId;
                if (empty($BranchInfo)) {
                    $BranchInfo = (object) ['BranchName' => '', 'Address' => '', 'GST' => ''];
                }
            } else {
                $CompInfo = $this->user_model->getRow('inventory_billing', ['user_id' => $UserId, 'id' => $CompId]);
                if (empty($CompInfo)) {
                    $CompInfo = (object) ['company_name' => '', 'address' => '', 'gst_no' => ''];
                }
            }
        ?>
        <table cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
                <td style="text-align: center;">
                    <b style="text-transform: uppercase; font-size: 30px;">
                        <?= $this->session->userdata('BranchRole') ? $BranchInfo->BranchName : $CompInfo->company_name; ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <span style="font-size: 18px; font-weight: 500;">
                        <?= $this->session->userdata('BranchRole') ? $BranchInfo->Address : $CompInfo->address; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <span style="font-size: 18px; font-weight: 500;">
                        GSTIN : <?= $this->session->userdata('BranchRole') ? $BranchInfo->GST : $CompInfo->gst_no; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <span style="font-size: 18px; font-weight: 700; letter-spacing: 5px;">
                        LEDGER
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <span style="font-size: 18px; font-weight: 500;">
                        (From <?= date('d-m-Y', strtotime($FromDate)); ?> To <?= date('d-m-Y', strtotime($ToDate)); ?>)
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <b style="font-size: 18px;">
                        Account : <?= $this->db->select('name')->where('id', $Party)->get('parties')->row('name'); ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="data-list" cellspacing="0" cellpadding="0" style="text-align: left; width: 100%; padding-top: 25px;">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Voucher Number</th>
                            <th>Particulars</th>
                            <th>Narration</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                        <?php
                            $TotalDebit = 0;
                            $TotalCredit = 0;
                            $TotalBalance = number_format(0, 2);
                            $decodeInvoiceTypes = function ($raw) {
                                $decoded = json_decode($raw ?? '', true);
                                return is_array($decoded) ? $decoded : [];
                            };
                            $this->db->select("*");
                            $this->db->where($Where);
                            $this->db->where("party_id", $Party);
                            $this->db->order_by("date", "ASC");
                            $Payments = $this->db->get("payment")->result();
                            if (!empty($Payments)) {
                                foreach ($Payments as $key => $Data) {
                                    $TotalDebit += (float) ($Data->debit ?? 0);
                                    $TotalCredit += (float) ($Data->credit ?? 0);
                                    $invoiceTypes = $decodeInvoiceTypes($Data->invoice_type);
                                    if ($Data->invoice_type == "Sale" || $Data->invoice_type == "Estimate" || in_array('Sale', $invoiceTypes) || in_array('Estimate', $invoiceTypes)) {
                                        $TotalBalance = number_format(($TotalCredit - $TotalDebit), 2);
                                    }
                        ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($Data->date)); ?></td>
                            <td><?= $Data->payment_type == "" ? $Data->invoice_type : $Data->payment_type; ?></td>
                            <td>
                                <?php
                                    if ((!empty($invoiceTypes)) || ($Data->invoice_type == "Sale" && $Data->payment_type == "Receipt") || ($Data->invoice_type == "Estimate" && $Data->payment_type == "Receipt") || ($Data->invoice_type == "Purchase" && $Data->payment_type == "Payment") || ($Data->invoice_type == "Estimate Purchase" && $Data->payment_type == "Payment")) {
                                        echo $Data->voucher_number;
                                    } else {
                                        if ($Data->invoice_type == "Sale") {
                                            echo ($Data->payment_type == NULL) ? get_title('sales', ['id' => $Data->invoice_id], 'invoiceNumber') : "";
                                        } elseif ($Data->invoice_type == "Estimate") {
                                            echo ($Data->payment_type == NULL) ? get_title('estimates', ['id' => $Data->invoice_id], 'invoiceNumber') : "";
                                        }
                                    }
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if ((in_array("Sale", $invoiceTypes) || $Data->invoice_type == "Sale") || (in_array("Estimate", $invoiceTypes) || $Data->invoice_type == "Estimate")) {
                                        if ($Data->transaction_type == "CR") {
                                            echo "Cr" . "&nbsp;" . "Sales";
                                        } elseif ($Data->transaction_type == "DR")  {
                                            echo "Dr" . "&nbsp;" . get_title("payment_mode", ["id" => $Data->paymentmode], "payment_method");
                                        }
                                    }
                                ?>
                            </td>
                            <td></td>
                            <td><?= !empty($Data->debit) ? number_format($Data->debit, 2) : ""; ?></td>
                            <td><?= !empty($Data->credit) ? number_format($Data->credit, 2) : ""; ?></td>
                            <td><?= $TotalBalance; ?></td>
                        </tr>
                        <?php } } ?>
                        <tr>
                            <th colspan="5" style="text-align: right; border-top: 1px solid #000; border-bottom: unset;">Total</th>
                            <th style="border-top: 1px solid #000; border-bottom: unset;"><?= number_format($TotalDebit, 2); ?></th>
                            <th style="border-top: 1px solid #000; border-bottom: unset;"><?= number_format($TotalCredit, 2); ?></th>
                            <th style="border-top: 1px solid #000; border-bottom: unset;"><?= $TotalBalance; ?></th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>