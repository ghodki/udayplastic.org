<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic</title>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
        <style type="text/css">
            body {
                font-family: 'Nunito';
                font-size: 12px;
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
        <table cellspacing="0" cellpadding="0" border="1" style="width: 100%;">
            <tr style="border-top: none; border-left: none; border-right: none;">
                <td colspan="2" style="text-align: center; line-height: 1.3; padding-top: 10px; padding-bottom: 10px; border: none;">
                    <b style="text-transform: uppercase; font-size: 30px;">
                        <?= $companyData->company_name; ?>
                    </b>
                    <span style="white-space: pre-line;">
                        <?= $companyData->address; ?>
                    </span>
                    <br />
                    <b>State Name</b> : <?= get_title('state_list', ['id' => $companyData->state], 'state'); ?>,
                    <b>Code</b> : <?= get_title('state_list', ['id' => $companyData->state], 'code'); ?>
                    <br />
                    <b>Mobile</b> : +91-<?= $companyData->number; ?>, 
                    <b>Email</b> : <?= $companyData->email; ?>
                </td>
            </tr>
            <tr style="border-top: none; border-left: none; border-right: none;">
                <th colspan="2" style="border-left: none; border-right: none;">
                    <p style="line-height: 0; margin-block-start: 12px; margin-block-end: 12px;">Vestige</p>
                </th>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">
                    <p style="padding-left: 10px;">Voucher Number. : <b><?= $vestigeData->voucherNumber; ?></b></p>
                </td>
                <td style="float: right; border:none;">
                    <p style="padding-right: 10px;">Dated : <b><?= date('d-M-Y', strtotime($vestigeData->date)); ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: none;">
                    <table cellspacing="0" cellpadding="5" border="1" style="width: 100%; border-left: none; border-right: none;">
                        <tr>
                            <th style="text-align: left; border-left: none; border-top: none;">Sr</th>
                            <th style="text-align: left; border-left: none; border-top: none;">Particulars</th>
                            <th style="text-align: left; border-left: none; border-top: none;">Price</th>
                            <th style="text-align: left; border-left: none; border-top: none;">Quantity</th>
                            <th style="text-align: left; border-left: none; border-top: none; border-right: none;">Total</th>
                        </tr>
                        <?php
                            if (isset($vestigeProducts) && !empty($vestigeProducts)) {
                                $sr = 0;
                                $totalQuantity = 0;
                                foreach ($vestigeProducts as $key => $data) {
                                    $sr++;
                                    $totalQuantity += $data->quantity;
                        ?>
                        <tr>
                            <td style="border-left: none; border-top: none; border-bottom: none;"><?= $sr; ?></td>
                            <td style="border-left: none; border-top: none; border-bottom: none;"><?= $data->productName; ?></td>
                            <td style="border-left: none; border-top: none; border-bottom: none;">&#8377; <?= indianMoneyFormat($data->price); ?></td>
                            <td style="border-left: none; border-top: none; border-bottom: none;"><?= $data->quantity; ?></td>
                            <td style="border: none;">&#8377; <?= indianMoneyFormat($data->total); ?></td>
                        </tr>
                        <?php } } ?>
                        <tr>
                            <th colspan="3" style="text-align: right; border-bottom: none; border-left: none;">Total</th>
                            <th style="border-bottom: none; border-left: none; text-align: left;"><?= $totalQuantity; ?></th>
                            <th style="border-bottom: none; border-left: none; border-right: none; text-align: left;">&#8377; <?= indianMoneyFormat($vestigeData->subTotal); ?></th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="line-height: 2;">
                <td style="border: none;">
                    <b style="margin-left: 10px;">Amount (in words) : </b>
                    <?php
                        /** * Converting Currency Numbers to words currency format */  
                        $number = $vestigeData->subTotal;
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
                       $points = ($point) ? "" . $words[$point / 10] . " " . $words[$point = $point % 10] : "";
                       echo "INR " . $result; if (!empty($points)) { echo $points . " Paise"; } echo " Only"
                    ?>
                </td>
            </tr>
            <tr style="border-top: none;">
                <td colspan="2" style="border: none;">
                    <p style="padding-left: 10px;">
                        <b>Remarks : </b>
                        <?= $vestigeData->remark; ?>
                    </p>
                </td>
            </tr>
            <tr style="border-top: none;">
                <td colspan="2" style="border: none;">
                    <p style="float: right; padding-right: 20px; padding-top: 40px; padding-bottom: 20px;">
                        Authorized Signatory
                    </p>
                </td>
            </tr>
        </table>
        <div style="font-size: 10px; margin-top: 50px; text-align: center;">
            This is a Computer Generated Copy by Uday Plastic.
        </div>
    </body>
</html>