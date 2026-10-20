<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic - <?= $RequestData->payment_type; ?> Voucher</title>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
        <style type="text/css">
            body {
                font-family: 'Nunito';
            }
            table, td, th {
                border-color: #000000;
            }
        </style>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                setTimeout(function() {
                    window.print();
                }, .1000);
            });
        </script>
    </head>
    <body>
        <table cellspacing="0" cellpadding="0" style="width: 100%; border: solid 1px #000;">
            <tr style="border-top: none; border-left: none; border-right: none;">
                <td colspan="2" style="text-align: center; line-height: 1.3; padding-top: 10px; padding-bottom: 10px; border: none;">
                    <b style="text-transform: uppercase; font-size: 30px;">
                        <?= $this->session->userdata('BranchRole') ? $Branch->BranchName : $Company->company_name; ?>
                    </b>
                    <span style="white-space: pre-line;">
                        <?= $this->session->userdata('BranchRole') ? $Branch->Address : $Company->address; ?>
                    </span>
                    <br />
                    <b>State Name</b> : <?= get_title('state_list', ['id' => $this->session->userdata('BranchRole') ? $Branch->State : $Company->state], 'state'); ?>,
                    <b>Code</b> : <?= get_title('state_list', ['id' => $this->session->userdata('BranchRole') ? $Branch->State : $Company->state], 'code'); ?>
                    <br />
                    <b>Mobile</b> : +91-<?= $this->session->userdata('BranchRole') ? $Branch->Mobile : $Company->number; ?>, 
                    <b>Email</b> : <?= $this->session->userdata('BranchRole') ? $Branch->Email : $Company->email; ?>
                </td>
            </tr>
            <tr style="border-left: none; border-right: none;">
                <td colspan="2" style="border-left: none; border-right: none; border-top: solid 1px #000;">
                    <p style="border-bottom: solid 1px #000; text-align: center; line-height: 1; padding-bottom: 5px; font-weight: 700; margin-top: 0px;">
                        Receipt Voucher
                    </p>
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">
                    <p style="padding-left: 10px;">
                        No. : <b><?= $RequestData->id; ?></b>
                    </p>
                </td>
                <td style="border: none;">
                    <p style="padding-right: 10px; float: right; text-align: right;">
                        Dated : <b><?= date('d-M-Y', strtotime($RequestData->payment_date)); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: none;">
                    <table cellspacing="0" cellpadding="5" style="width: 100%; border-top: solid 1px #000; border-bottom: solid 1px #000; border-left: none; border-right: none;">
                        <tr>
                            <th style="border-right: none; border-top: none; border-bottom: solid 1px #000;">Particulars</th>
                            <th style="border-right: none; border-left: solid 1px #000; border-top: none; border-bottom: solid 1px #000;">Amount</th>
                        </tr>
                        <tr style="line-height: 2;">
                            <td style="border-bottom: none; border-left: none; border-right: none;">
                                <b>Account : </b><?= get_title('parties', ['id' => $RequestData->party_id], 'name'); ?>                                
                            </td>
                            <td style="border-bottom: none; border-right: none; border-left: solid 1px #000;">
                                &#8377; <?= number_format((float) ($RequestData->payment_amount ?? 0), 2); ?>
                            </td>
                        </tr>
                        <tr style="line-height: 4;">
                            <td style="border: none;">
                                <b>Payment Mode : </b><?= get_title('payment_mode', ['id' => $RequestData->payment_mode], 'payment_method'); ?>
                            </td>
                            <td style="border-top: none; border-bottom: none; border-right: none; border-left: solid 1px #000;"></td>
                        </tr>
                        <tr style="line-height: 2;">
                            <td style="border: none;">
                                <b>Amount (in words) : </b>
                                        <?php
                                            /** * Converting Currency Numbers to words currency format */  
                                            $number = $RequestData->payment_amount;
                                            $no = floor($number);
                                            $point = round($number - $no, 2) * 100;
                                            $hundred = null;
                                            $digits_1 = strlen($no);
                                            $i = 0;
                                            $str = array();
                                            $words = array(
                                                '0' => 'Zero', 
                                                '1' => 'One', 
                                                '2' => 'Two',
                                                '3' => 'Three', 
                                                '4' => 'Four', 
                                                '5' => 'Five', 
                                                '6' => 'Six',
                                                '7' => 'Seven', 
                                                '8' => 'Eight', 
                                                '9' => 'Nine',
                                                '10' => 'Ten', 
                                                '11' => 'Eleven', 
                                                '12' => 'Twelve',
                                                '13' => 'Thirteen', 
                                                '14' => 'Fourteen',
                                                '15' => 'Fifteen', 
                                                '16' => 'Sixteen', 
                                                '17' => 'Seventeen',
                                                '18' => 'Eighteen', 
                                                '19' => 'Nineteen', 
                                                '20' => 'Twenty',
                                                '30' => 'Thirty', 
                                                '40' => 'Forty', 
                                                '50' => 'Fifty',
                                                '60' => 'Sixty', 
                                                '70' => 'Seventy',
                                                '80' => 'Eighty', 
                                                '90' => 'Ninety'
                                            );
                                            $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
                                            while ($i < $digits_1) {
                                                $divider = ($i == 2) ? 10 : 100;
                                                $number = floor($no % $divider);
                                                $no = floor($no / $divider);
                                                $i += ($divider == 10) ? 1 : 2;
                                                if ($number) {
                                                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                                                    $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
                                                    $str [] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
                                                } else $str[] = null;
                                           }
                                           $str = array_reverse($str);
                                           $result = implode('', $str);
                                           $points = ($point) ?  "" . $words[$point / 10] . " " . $words[$point = $point % 10] : '';
                                           echo "INR " . $result; if (!empty($points)) { echo $points . " Paise"; } echo "Only"
                                        ?>
                            </td>
                            <td style="border-top: none; border-bottom: none; border-right: none; border-left: solid 1px #000;"></td>
                        </tr>
                        <tr style="line-height: 2;">
                            <td style="border: none;">
                                <b>Amount For Invoices : </b>
                                <?php
                                    $invoiceNumbers = json_decode($RequestData->invoiceNumber ?? '', true);
                                    if (!is_array($invoiceNumbers)) {
                                        $invoiceNumbers = [];
                                    }
                                    if (!empty($invoiceNumbers)) {
                                        $SaleInvoiceNumber = "";
                                        foreach ($invoiceNumbers as $key => $data) {
                                            $SaleInvoiceNumber .= $data . ", ";
                                        }
                                        echo rtrim($SaleInvoiceNumber, ", ");
                                    }
                                ?>
                            </td>
                            <td style="border-top: none; border-bottom: none; border-right: none; border-left: solid 1px #000;"></td>
                        </tr>
                        <tr>
                            <td style="border-bottom: none; border-left: none; border-right: none; border-top: solid 1px #000;"></td>
                            <td style="border-bottom: none; border-right: none; border-left: solid 1px #000; border-top: solid 1px #000;">
                                &#8377; <?= number_format((float) ($RequestData->payment_amount ?? 0), 2); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="border-top: none;">
                <td colspan="2" style="border: none;">
                    <p style="padding-left: 10px;">
                        <b>Remarks : </b><?= $RequestData->remark; ?>
                    </p>
                </td>
            </tr>
            <tr style="border-top: none;">
                <td colspan="2" style="border: none;">
                    <p style="float: right; padding-right: 20px; padding-top: 40px; padding-bottom: 20px;">Authorized Signatory</p>
                </td>
            </tr>
        </table>
    </body>
</html>