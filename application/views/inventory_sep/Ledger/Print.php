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
        </style>
    </head>
    <body>
        <?php 
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompBranchId = $this->session->userdata('CompBranchId');
            $LedgerId = $this->input->get('LedgerId');
            $FromDate = $this->input->get('fromDate');
            $ToDate = $this->input->get('toDate');
            $IsDeleted = 1;

            $Where = ['Date >=' => $FromDate, 'Date <=' => $ToDate, 'IsDeleted' => $IsDeleted, 'LedgerId' => $LedgerId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $BranchInfo = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId, 'BranchUserId' => $UserId, 'CompId' => $CompId]);
                $Where['CompanyBranchId'] = $CompBranchId;
            } else {
                $CompInfo = $this->user_model->getRow('inventory_billing', ['user_id' => $UserId, 'id' => $CompId]); 
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
                        (From <?= date('d-m-Y', strtotime($this->input->get('fromDate'))); ?> To <?= date('d-m-Y', strtotime($this->input->get('toDate'))); ?>)
                    </span>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <b style="font-size: 18px;">
                        Account : <?= $this->db->select('name')->where('id', $this->input->get('LedgerId'))->get('ladger')->row('name'); ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="data-list" cellspacing="0" cellpadding="0" style="text-align: left; width: 100%; padding-top: 25px;">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                        <?php                            
                            $this->db->select("*");
                            $this->db->where($Where);
                            $this->db->order_by("Date", "ASC");
                            $LedgerEntries = $this->db->get("ledger_entries")->result();
                            if (!empty($LedgerEntries)) {
                                $TotalAmount = 0;
                                foreach ($LedgerEntries as $key => $Data) {
                                    $TotalAmount += $Data->Amount;
                        ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($Data->Date)); ?></td>
                            <td><?= $Data->Type; ?></td>
                            <td><?= !empty($Data->Amount) ? number_format($Data->Amount, 2) : ""; ?></td>
                        </tr>
                        <?php } } ?>
                        <tr>
                            <th colspan="2" style="text-align: right; border-top: 1px solid #000; border-bottom: unset;">Total</th>
                            <th style="border-top: 1px solid #000; border-bottom: unset;"><?= number_format($TotalAmount, 2); ?></th>
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