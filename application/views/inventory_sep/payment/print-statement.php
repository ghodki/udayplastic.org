<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <style type="text/css">
            body {
                font-size: 11px;
            }
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
            $isBranchRole = $this->session->userdata('BranchRole');
            $accountGroupId = $this->input->get('accountGroupId');
            $ledgerId = $this->input->get('ledgerId');
            $fromDate = $this->input->get('fromDate');
            $toDate = $this->input->get('toDate');
            $status = 1;

            $openingBalanceWhere = ['account_group' => $accountGroupId, 'party_id' => $ledgerId, 'date <' => $fromDate, 'status' => $status, 'user_id' => $userId, 'comp_id' => $compId];
            $where = ['account_group' => $accountGroupId, 'party_id' => $ledgerId, 'date >=' => $fromDate, 'date <=' => $toDate, 'status' => $status, 'user_id' => $userId, 'comp_id' => $compId];
            if ($isBranchRole) {
                $branchInfo = $this->user_model->getRow('companybranch', ['Id' => $compBranchId, 'BranchUserId' => $userId, 'CompId' => $compId]);
                $openingBalanceWhere['CompanyBranchId'] = $compBranchId;
                $where['CompanyBranchId'] = $compBranchId;
            } else {
                $compInfo = $this->user_model->getRow('inventory_billing', ['user_id' => $userId, 'id' => $compId]); 
            }
        ?>
        <table cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
                <td style="text-align: center;">
                    <b style="text-transform: uppercase; font-size: 30px;">
                        <?= $isBranchRole ? $branchInfo->BranchName : $compInfo->company_name; ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <span style="font-size: 18px; font-weight: 500;">
                        <?= $isBranchRole ? $branchInfo->Address : $compInfo->address; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <span style="font-size: 18px; font-weight: 500;">
                        GSTIN : <?= $isBranchRole ? $branchInfo->GST : $compInfo->gst_no; ?>
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
                        (From <?= date('d-m-Y', strtotime($fromDate)); ?> To <?= date('d-m-Y', strtotime($toDate)); ?>)
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <b style="font-size: 18px;">
                        Account : <?= $this->db->select('name')->where('id', $ledgerId)->get('parties')->row('name'); ?>
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
                        <tr>
                            <th style="border-top: none; text-align: right;" colspan="7">Opening Balance</th>
                            <th style="border-top: none;">
                                <?php
                                    $openingBalanceData = $this->db->select('SUM(credit) AS totalCredit, SUM(debit) AS totalDebit')->where($openingBalanceWhere)->get('payment')->row();

                                    if (get_title('accounting_group', ['id' => $accountGroupId], 'name') == "Sundry Debitors") {
                                        $openingBalanceValue = ($openingBalanceData->totalCredit - $openingBalanceData->totalDebit);
                                    } elseif (get_title('accounting_group', ['id' => $accountGroupId], 'name') == "Sundry Creditors") {
                                        $openingBalanceValue = ($openingBalanceData->totalDebit - $openingBalanceData->totalCredit);
                                    } else {
                                        $openingBalanceValue = ($openingBalanceData->totalCredit - $openingBalanceData->totalDebit);
                                    }
                                    echo $openingBalanceValue;
                                ?>
                            </th>
                        </tr>
                        <?php
                            $this->db->select("*");
                            $this->db->where($where);
                            $this->db->order_by("date", "ASC");
                            $payments = $this->db->get("payment")->result();
                            if (!empty($payments)) {
                                $totalDebit = 0;
                                $totalCredit = 0;
                                foreach ($payments as $key => $data) {
                                    $totalDebit += (float) $data->debit;
                                    $totalCredit += (float) $data->credit;
                                    $invoiceTypes = json_decode($data->invoice_type ?? '', true);
                                    $invoiceTypes = is_array($invoiceTypes) ? $invoiceTypes : [];
                                    
                                    if ($data->invoice_type == "Sale" || $data->invoice_type == "Estimate" || in_array('Sale', $invoiceTypes) || in_array('Estimate', $invoiceTypes)) {
                                        $totalBalance = ($totalCredit - $totalDebit);
                                    } 
                                    if ($data->invoice_type == "Purchase" || $data->invoice_type == "Estimate Purchase" || in_array('Purchase', $invoiceTypes) || in_array('Estimate Purchase', $invoiceTypes)) {
                                        $totalBalance = ($totalDebit - $totalCredit);
                                    }
                                    if (empty($data->invoice_type) && $data->payment_type == "Journal" || $data->payment_type == "Contra") {
                                        $totalBalance = ($totalCredit - $totalDebit);
                                    }
                        ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                            <td><?= $data->payment_type == "" ? $data->invoice_type : $data->payment_type; ?></td>
                            <td>
                                <?php
                                    if ((is_array(json_decode($data->invoice_type))) || ($data->invoice_type == "Sale" && $data->payment_type == "Receipt") || ($data->invoice_type == "Estimate" && $data->payment_type == "Receipt") || ($data->invoice_type == "Purchase" && $data->payment_type == "Payment") || ($data->invoice_type == "Estimate Purchase" && $data->payment_type == "Payment")) {
                                        echo $data->voucher_number;
                                    } else {
                                        if ($data->invoice_type == "Sale") {
                                            echo ($data->payment_type == NULL) ? get_title('sales', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                        } elseif ($data->invoice_type == "Estimate") {
                                            echo ($data->payment_type == NULL) ? get_title('estimates', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                        } elseif ($data->invoice_type == "Purchase") {
                                            echo ($data->payment_type == NULL) ? get_title('purchases', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                        } elseif ($data->invoice_type == "Estimate Purchase") {
                                            echo ($data->payment_type == NULL) ? get_title('estimate_purchase', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                        }
                                    }
                                    if (empty($data->invoice_type) && $data->payment_type == "Journal" || $data->payment_type == "Contra") {
                                        echo $data->voucher_number;
                                    }
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if ((in_array("Sale", $invoiceTypes) || $data->invoice_type == "Sale") || (in_array("Estimate", $invoiceTypes) || $data->invoice_type == "Estimate")) {
                                        if ($data->transaction_type == "CR") {
                                            echo "Cr" . "&nbsp;" . "Sales";
                                        } elseif ($data->transaction_type == "DR")  {
                                            echo "Dr" . "&nbsp;" . get_title("payment_mode", ["id" => $data->paymentmode], "payment_method");
                                        }
                                    } elseif ((in_array("Purchase", $invoiceTypes) || $data->invoice_type == "Purchase") || (in_array("Estimate Purchase", $invoiceTypes) || $data->invoice_type == "Estimate Purchase")) {
                                        if ($data->transaction_type == "DR") {
                                            echo "Dr" . "&nbsp;" . "Purchases";
                                        } elseif ($data->transaction_type == "CR")  {
                                            echo "Cr" . "&nbsp;" . get_title("payment_mode", ["id" => $data->paymentmode], "payment_method");
                                        }
                                    } elseif (empty($data->invoice_type) && $data->payment_type == "Journal" || $data->payment_type == "Contra") {
                                        $debitLedgerId = get_title('payment', ['voucher_number' => $data->voucher_number, 'transaction_type' => 'DR'], 'party_id');
                                        echo get_title('parties', ['id' => $debitLedgerId], 'name');
                                    }
                                ?>
                            </td>
                            <td>
                                <?= get_title('payment_mode', ['id' => $data->paymentmode], 'payment_method'); ?>
                                <?php if (!empty($data->remark)) { ?>
                                <br />
                                <small>
                                    <?= $data->remark; ?>
                                </small>
                                <?php } ?>
                            </td>
                            <td><?= !empty($data->debit) ? indianMoneyFormat($data->debit) : ""; ?></td>
                            <td><?= !empty($data->credit) ? indianMoneyFormat($data->credit) : ""; ?></td>
                            <td><?= indianMoneyFormat($totalBalance); ?></td>
                        </tr>
                        <?php } } ?>
                        <tr>
                            <th colspan="5" style="text-align: right; border-top: 1px solid #000; border-bottom: unset;">Total</th>
                            <th style="border-top: 1px solid #000; border-bottom: unset;"><?= indianMoneyFormat($totalDebit); ?></th>
                            <th style="border-top: 1px solid #000; border-bottom: unset;"><?= indianMoneyFormat($totalCredit); ?></th>
                            <th style="border-top: 1px solid #000; border-bottom: unset;"><?= indianMoneyFormat($totalBalance); ?></th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="7">Closing Balance</th>
                            <th><?= indianMoneyFormat(($openingBalanceValue + $totalBalance)); ?></th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            window.print();
        });
    </script>
</html>