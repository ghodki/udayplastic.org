<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic - Payment Receipt</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Uday Plastic - Payment Receipt" />
        <meta name="keywords" content="Uday Plastic - Payment Receipt" />
        <!-- Favicons -->
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" />
        <!-- Vendor CSS Files -->
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/vendor/bootstrap/css/bootstrap.min.css" />
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
            .rs {                
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
                border-right: 0.5px solid #000000;
                border-top: 0.5px solid #000000;
                border-bottom: 0.5px solid #000000;
                margin-left: -8px;
            }
            .table {
                margin-bottom: 0rem;
            }
            p {
                margin-bottom: 0rem;
            }
            .signature {
                width: 40px;
                float: right;
                position: relative;
                top: -45px;
                bottom: 0px;
                right: 110px;
            }
        </style>
    </head>
    <body>
        <section class="section mt-2">
            <div class="container">
                <div class="row" id="printeblearea">
                    <div class="col-md-12">
                        <?php 
                            $compinfo = $this->user_model->getRow('payment_receipt_company', ['id' => $receipt->payment_receipt_company_id]); 
                            $config = $this->user_model->getRow('payment_receipt_config', ['payment_receipt_company_id' => $receipt->payment_receipt_company_id]); 
                        ?>
                        <table class="table table-bordered">
                            <tr>
                                <td style="border: none; width: 6%;">
                                    <?php if (!empty($config->logo)) { ?>
                                    <img src="<?= base_url(); ?>/uploads/complogo/<?= $config->logo; ?>" alt="logo_receipt" style="width: 140px;" />
                                    <?php } ?>
                                </td>
                                <td style="text-align: right; border: none; width: 68%;">
                                    <b style="text-transform: uppercase; font-size: 23px; font-weight: 600;">
                                        <?= $compinfo->company_name; ?>
                                    </b>
                                    <p style="white-space: pre-wrap; display: contents; line-height: 20px;">
                                        <?= $compinfo->address; ?> 
                                        Tel: <b><?= $compinfo->number; ?></b>
                                        <b>PAN : <?= $compinfo->pan; ?></b>
                                    </p>
                                </td>
                                <td style="padding: 0rem; width: 25%;">
                                    <table class="table table-bordered text-center" style="line-height: 57.5px; border: none;">
                                        <tr style="border: none;">
                                            <th colspan="2" style="font-size: 12.5px; border-left: none; border-top: none; border-right: none;">
                                                RECEIPT
                                            </th>
                                        </tr>
                                        <tr style="border: none;">
                                            <th style="border-left: none; border-top: none; border-right: none;">No.</th>
                                            <td style="border-right: none; border-bottom: none; font-weight: 600; color: <?= $config->receipt_color; ?>;">
                                                <?= $receipt->receipt_id; ?>
                                            </td>
                                        </tr>
                                        <tr style="border: none;">
                                            <th style="border: none;">Date</th>
                                            <td style="border-right: none; border-bottom: none;">
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
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border: none;">
                            <tr style="border-top: none; border-bottom: none;">
                                <td style="padding: 0.75rem; border-top: none; border-bottom: none;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
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
                                           echo $result . "Rupees  "; if (!empty($points)) { echo $points . " Paise"; }
                                        ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0.75rem;"></td>
                            </tr>
                            <tr style="border: none;">
                                <th style="border: none; float: right; font-size: 12px; text-transform: uppercase;">
                                    FOR <?= $compinfo->company_name; ?>
                                </th>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none;">
                                    <b>Donor PAN # :</b>
                                    <span style="padding-left: 30px; text-decoration: underline;">
                                        <?= $receipt->donor_pan; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none;">
                                    <span class="rs">
                                        <b style="padding-left: 8px;">Rs. </b>
                                        <span style="padding-left: 10px;"><?= $receipt->amount; ?>/-</span>
                                    </span>
                                </td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none;">
                                    <b style="font-size: 13px;">(Cheque subject to realisation)</b>
                                    <?php if (!empty($config->signature)) { ?>
                                    <img src="<?= base_url(); ?>/uploads/signature/<?= $config->signature; ?>" alt="signature" class="signature" />
                                    <?php } ?>
                                    <b style="float: right;">
                                        Authorised Signatory
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <th style="font-size: 13.5px;">
                                    <?= get_title('payment_receipt_category', ['user_id' => $receipt->user_id, 'payment_receipt_company_id' => $receipt->payment_receipt_company_id, 'id' => $receipt->paymentcategory], 'description'); ?>
                                </th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <p class="text-center mt-1">
                            This is auto generated receipt, hence signature is not required
                        </p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12 text-center mb-5">
                        <button class="btn btn-sm btn-success" onclick="Print('printeblearea');">
                            Print
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="window.close();">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </section>
        
        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            function Print(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }             
        </script>
    </body>
</html>