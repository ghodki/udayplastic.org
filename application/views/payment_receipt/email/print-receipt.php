<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/css/style.css" />
        <style type="text/css">
            @media print{
                @page {
                    size: auto;
                }
            }
            body {
                background: #ffffff;
            }
            .table-bordered, .table-bordered tr, .table-bordered th, .table-bordered td {
                border: 0.5px solid #000000;
            }
            .table td, .table th {
                padding: 0.3rem;
            }
            .table {
                margin-bottom: 0rem;
                margin-top: 0rem;
                width: 100%;
                border-collapse:collapse;
            }
            p {
                margin-bottom: 0rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php 
                $compinfo = $this->user_model->getRow('payment_receipt_company', ['id' => $receipt->payment_receipt_company_id]); 
                $config = $this->user_model->getRow('payment_receipt_config', ['payment_receipt_company_id' => $receipt->payment_receipt_company_id]); 
            ?>
            <table class="table table-bordered">
                <tr>
                    <td style="border-right: none;">
                        <?php 
                            if (!empty($config->logo)) {
                                $logourl = 'uploads/complogo/' . $config->logo; 
                                $ext = pathinfo($logourl, PATHINFO_EXTENSION);
                                $logoimg = file_get_contents($logourl);
                                $logo = 'data:image/' . $ext . ';base64,' . base64_encode($logoimg);
                        ?>
                        <img src="<?= $logo; ?>" alt="logo" />
                        <?php } ?>
                    </td>
                    <td style="border-left: none; text-align: right;">
                        <p>
                            <b style="text-transform: uppercase; font-size: 15px; font-weight: 700;">
                                <?= $compinfo->company_name; ?>
                            </b>
                            <br />
                            <?= $compinfo->address; ?> 
                            <br />
                            Tel: <b><?= $compinfo->number; ?></b>
                            <br />
                            <b>PAN : <?= $compinfo->pan; ?></b>
                        </p>
                    </td>
                    <td style="padding: 0rem;">
                        <table class="table table-bordered text-center" style="line-height: 43.5px;">
                            <tr>
                                <th colspan="2" style="font-size: 12.5px;">
                                    RECEIPT
                                </th>
                            </tr>
                            <tr>
                                <th>No.</th>
                                <td style="font-weight: 600; color: <?= $config->receipt_color; ?>;">
                                    <?= $receipt->receipt_id; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td style="white-space: nowrap;">
                                    <?php
                                        $rdate = $receipt->receipt_date;
                                        $dateforment2 = date_create($rdate);
                                        $date2 = date_format($dateforment2, 'd-m-Y');
                                        echo $date2;
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered">
                <tr>
                    <td style="padding: 0.75rem;"></td>
                </tr>
                <tr>
                    <td>
                        <b>Received with thanks from M/s /Smt /Shri</b>
                        <span style="padding-left: 30px; text-transform: uppercase;">
                            <?= get_title('payment_receipt_party', ['id' => $receipt->party_id], 'party_name'); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <?php if ($config->address == "Yes") { ?>
                    <td>
                        <b>Address</b>
                        <span style="padding-left: 30px;">
                            <?= get_title('payment_receipt_party', ['id' => $receipt->party_id], 'address'); ?>
                            <?php
                                $stateid = get_title('payment_receipt_party', ['id' => $receipt->party_id], 'state_id');
                                echo get_title('state_list', ['id' => $stateid], 'state') . "&nbsp;"; 
                                echo get_title('payment_receipt_party', ['id' => $receipt->party_id], 'city');
                            ?> 
                            &nbsp;-&nbsp; 
                            <?= get_title('payment_receipt_party', ['id' => $receipt->party_id], 'pin'); ?>
                        </span>
                    </td>
                    <?php } elseif ($config->address == "No") { ?>
                    <td style="padding: 0.75rem;"></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>
                        <b>Vide Cheque/DD/RTGS</b>
                        <span style="padding-left: 30px;">
                            <?= $receipt->transaction_id; ?>
                        </span>
                        <b style="padding-left: 100px;">for Rs</b>
                        <span style="padding-left: 30px;">
                            <?= $receipt->amount; ?>/-
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Dated</b>
                        <span style="padding-left: 30px;">
                            <?php
                                $tdate = $receipt->transaction_date;
                                $dateforment = date_create($tdate);
                                $date = date_format($dateforment, 'd-m-Y');
                                echo $date;
                            ?>
                        </span>
                        <b style="padding-left: 80px;">Drawn on</b>
                        <span style="padding-left: 30px;">
                            <?= $receipt->bank_name; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.75rem;"></td>
                </tr>
                <tr>
                    <td>
                        <b>Towards</b>
                        <span style="padding-left: 30px;">
                            <?= get_title('payment_receipt_category', ['user_id' => $receipt->user_id, 'payment_receipt_company_id' => $receipt->payment_receipt_company_id, 'id' => $receipt->paymentcategory], 'category'); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>(Rupees)</b>
                        <span style="padding-left: 30px;">
                            <?php
                                /** * Converting Currency Numbers to words currency format */  
                                $number = $receipt->amount;
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
                                    '90' => 'Ninety');
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
                               echo $result . "Rupees  "; if (!empty($points)) { echo $points . " Paise"; }
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0.75rem;"></td>
                </tr>
                <tr style="border-bottom: none;">
                    <th style="border-bottom: none; text-align: right; float: right; font-size: 12px; text-transform: uppercase;">
                        FOR <?= $compinfo->company_name; ?>
                    </th>
                </tr>
                <tr>
                    <td style="border-top: none; border-bottom: none;">
                        <b>Donor PAN # :</b>
                        <span style="padding-left: 30px; text-decoration: underline;">
                            <?= $receipt->donor_pan; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="border-top: none; border-bottom: none;">
                        <span style="border: 0.5px solid #000000; padding: 100px; margin-left: -7px;">
                            <b>Rs.</b> <?= $receipt->amount; ?>/-
                        </span>
                        <br />
                        <b style="font-size: 13px; text-align: left;">(Cheque subject to realisation)</b>
                    </td>
                </tr>
                <tr>
                    <td style="border-top: none; text-align: right;">
                        <?php 
                            if (!empty($config->signature)) {
                                $signatureurl = 'uploads/signature/' . $config->signature; 
                                $ext2 = pathinfo($signatureurl, PATHINFO_EXTENSION);
                                $signatureimg = file_get_contents($signatureurl);
                                $signature = 'data:image/' . $ext2 . ';base64,' . base64_encode($signatureimg);
                        ?>
                        <img src="<?= $signature; ?>" alt="signature" style="margin-right: 60px;" />
                        <?php } ?>
                        <br />
                        <b>Authorised Signatory</b>
                    </td>
                </tr>
                <tr>
                    <th style="font-size: 13.5px; line-height: 20px; text-align: justify;">
                        <?= get_title('payment_receipt_category', ['user_id' => $receipt->user_id, 'payment_receipt_company_id' => $receipt->payment_receipt_company_id, 'id' => $receipt->paymentcategory], 'description'); ?>
                    </th>
                </tr>
            </table>
            <p class="text-center" style="text-align: center;">
                This is auto generated receipt, hence signature is not required
            </p>
        </div>
    </body>
</html>