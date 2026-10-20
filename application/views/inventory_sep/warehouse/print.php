<html>
    <head>
        <title>Uday Plastic - Stock Transfer Receipt</title>
        <meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1" />
    	<meta http-equiv="x-ua-compatible" content="ie=edge" />
        <link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>dist/css/adminlte.min.css" />
	<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <style type="text/css">
            @media print{
                @page {
                    size: auto;
                }
            }
            body {
                font-family: sans-serif;
                margin: 0px;
                font-size: 12px;
            }
            .main_container {
                page-break-after: always;
                margin: auto;

            }
            .main {
                border: 1px solid;
            }
            .company_info {
                text-align: center;
                align-content: center;
            }
            .table tr td div.company_info {
                text-align: center !important;
                vertical-align: middle !important;
            }
            .table {
                margin-bottom: 0rem;
            }
            .table td, .table th {
                padding: 4px;
                border-top: unset;
            }
            .table thead th {
                vertical-align: bottom;
                border-bottom: unset;
            }
            .address, .contact_no, .email, .gst_no {
                padding-bottom: 2px;
            }
        </style>
    </head>
    <body>
        <div class="main_container">
            <table class="table main" cellspacing="0">
                <tr>
                    <td colspan="2">
                        <table class="table" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td>
                                    <div class="company_info">
                                        <div class="name">
                                            <h2><?= $this->session->userdata('BranchRole') ? $BranchInfo->BranchName : $compinfo->company_name; ?></h2>
                                        </div>
                                        <div class="address">
                                            <?=  $this->session->userdata('BranchRole') ? $BranchInfo->Address : $compinfo->address; ?>
                                        </div>
                                        <div class="contact_no">
                                            <?=  $this->session->userdata('BranchRole') ? $BranchInfo->Mobile : $compinfo->number; ?>
                                        </div>
                                        <div class="email">
                                            <?=  $this->session->userdata('BranchRole') ? $BranchInfo->Email : $compinfo->email; ?>
                                        </div>
                                        <div class="gst_no">
                                            <b>GSTIN : <?=  $this->session->userdata('BranchRole') ? $BranchInfo->GST : $compinfo->gst_no; ?></b>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; border: 1px solid #000000; padding: 5px 0 5px 0;">
                        <b style="font-size: 18px;">Stock Transfer</b>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border: 1px solid #000000; padding: 5px 5px 5px 5px; width: 600px;">
                        <b># : <?= $result->transfer_id; ?></b>
                    </td>
                    <td style="text-align: right; border: 1px solid #000000; padding: 5px 5px 5px 5px; width: 595px;">
                        <b>Date : <?= date('d-m-Y', strtotime($result->transfer_date)); ?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 0px;">
                        <table class="table" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 5px 5px 5px 5px; border-right: 1px solid; width: 600px;">
                                    <h6>From</h6>
                                    <div class="warehouse_info">
                                        <?php $from = $this->user_model->getRow('warehouse', ['id' => $result->warehouse_form]); ?>
                                        <div class="name">
                                            <h4><?= $from->name; ?></h4>
                                        </div>
                                        <div class="location">
                                            <?= $from->location; ?>
                                        </div>
                                        <div class="contact_person">
                                            <?= $from->contact_person; ?>
                                        </div>
                                        <div class="mobile">
                                            <?= $from->mobile; ?>
                                        </div>
                                        <div class="email">
                                            <?= $from->email; ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 5px 5px 5px 5px; width: 595px;">
                                    <h6>To</h6>
                                    <div class="warehouse_info">
                                        <?php $to = $this->user_model->getRow('warehouse', ['id' => $result->warehouse_to]); ?>
                                        <div class="name">
                                            <h4><?= $to->name; ?></h4>
                                        </div>
                                        <div class="location">
                                            <?= $to->location; ?>
                                        </div>
                                        <div class="contact_person">
                                            <?= $to->contact_person; ?>
                                        </div>
                                        <div class="mobile">
                                            <?= $to->mobile; ?>
                                        </div>
                                        <div class="email">
                                            <?= $to->email; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 0px;">
                        <table class="table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="width: 20px; border-top: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">Sr</th>
                                    <th style="width: 80px; border-top: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">HSN/SAC</th>
                                    <th style="width: 300px; border-top: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">Description of goods/services</th>
                                    <th style="width: 50px; border-top: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">Quantity</th>
                                    <th style="width: 100px; border-top: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">Unit</th>
                                    <th style="width: 50px; border-top: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">Rate</th>
                                    <th style="width: 50px; border-top: 1px solid; border-bottom: 1px solid;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $products = $this->user_model->getResult('transfer_products', ['transfer_id' => $result->id]);
                                    if (!empty($products)) {
                                        $Quantity = 0;
                                        $n = 0;
                                        foreach ($products as $key => $data) {
                                            $Quantity += $data->quantity;
                                            $n++;
                                ?>
                                <tr>
                                    <td style="border-right: 1px solid;"><?= $n; ?></td>
                                    <td style="border-right: 1px solid;"><?= $data->hsn; ?></td>
                                    <td style="border-right: 1px solid;">
                                        <?= $data->product_name; ?>
                                        <br />
                                        <small><?= $data->description; ?></small>
                                    </td>
                                    <td style="border-right: 1px solid;"><?= $data->quantity; ?></td>
                                    <td style="border-right: 1px solid;"><?= $data->unit; ?></td>
                                    <td style="border-right: 1px solid;"><?= $data->rate; ?></td>
                                    <td><?= $data->total; ?></td>
                                </tr>
                                <?php } } ?>
                                <tr id="page-main">
                                    <td style="border-right: 1px solid; border-bottom: 1px solid;"></td>
                                    <td style="border-right: 1px solid; border-bottom: 1px solid;"></td>
                                    <td style="border-right: 1px solid; border-bottom: 1px solid;"></td>
                                    <td style="border-right: 1px solid; border-bottom: 1px solid;"></td>
                                    <td style="border-right: 1px solid; border-bottom: 1px solid;"></td>
                                    <td style="border-right: 1px solid; border-bottom: 1px solid;"></td>
                                    <td style="border-bottom: 1px solid;"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="border-right: 1px solid; border-bottom: 1px solid; text-align: right;">Total</th>
                                    <th style="border-right: 1px solid;border-bottom: 1px solid;"><?= $Quantity; ?></th>
                                    <th colspan="2" style="border-right: 1px solid;border-bottom: 1px solid;"></th>
                                    <th style="border-bottom: 1px solid;"><?= $result->total_amount; ?></th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="border-right: 1px solid;">Amount in word</th>
                                    <th colspan="5"><?= $result->amount_word; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid; height: auto; text-align: right;">
                        <div style="min-height: 150px; padding-top: 100px; margin-right: 50px;">
                            <b>Authorized Signature</b>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid;">
                        <div class="footer" style="font-size: 12px; text-align: center;">
                            This is a computer generated copy by Uday Plastic
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function() {
                if (document.body.scrollHeight < 1000) {
                    document.getElementById("page-main").style.height = document.getElementById("page-main").clientHeight + 170 + 'px';
                }
                 window.print();
            });
        </script>
    </body>
</html>