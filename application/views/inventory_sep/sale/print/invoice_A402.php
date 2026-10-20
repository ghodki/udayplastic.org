<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic - Invoice</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Uday Plastic - Invoice" />
        <meta name="keywords" content="Uday Plastic - Invoice" />
        <!-- Favicons -->
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=PT+Serif&family=Playfair+Display&family=Roboto+Slab&display=swap" />
        <!-- Vendor CSS Files -->
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/vendor/bootstrap/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/css/style.css" />
        <style type="text/css">
            @media print {
                @page {
                    size: auto;
                }
            }
            body {
                background: #ffffff;
                font-family: 'Noto Serif', serif;
                font-family: 'Playfair Display', serif;
                font-family: 'PT Serif', serif;
                font-family: 'Roboto Slab', serif;
                font-size: 14px;
            }
            .table-bordered,
            .table-bordered tr,
            .table-bordered th,
            .table-bordered td {
                border: 0.5px solid #000000;
            }
            .table td,
            .table th {
                padding: 3px;
            }
            .table {
                margin-bottom: 0rem;
            }
            p {
                margin-bottom: 0rem;
            }
            th, td {
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <?php
            $userid = $this->session->userdata('user_id');
            $compid = $this->session->userdata('comp_id');
            $compinfo = $this->user_model->getRow('inventory_billing', ['id' => $compid, 'user_id' => $userid]);
            $comprofile = $this->user_model->getRow('comp_profile', ['comp_id' => $compid, 'user_id' => $userid]);
            $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
        ?>
        <div class="container" style="border: 2px solid #000000;">
            <div class="row">
                <div class="col-md-12" style="padding-top: 15px;">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="3" style="text-align: center;">
                                <h3>
                                    <b><?= $compinfo->company_name; ?></b>
                                </h3>
                                <h6 style="white-space: pre-line; margin-top: -20px;">
                                    <?= $compinfo->address; ?>
                                </h6>
                                <p>
                                    <b>Mob: <?= $compinfo->number; ?></b>
                                    <br />
                                    <small>
                                        <b>Email ID: <?= $compinfo->email; ?></b>
                                    </small>
                                    <br />
                                    <b>GST NO: <?= $compinfo->gst_no; ?></b>
                                </p>
                            </td>
                        </tr>
                        <tr style="background-color: rgb(247, 209, 177);">
                            <td colspan="3" style="padding: 5px;"></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <h4 style="text-align: center;"><b>Tax Invoice</b></h4>
                            </td>
                        </tr>
                        <tr style="background-color: rgb(247, 209, 177);">
                            <td colspan="3" style="padding: 5px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    Invoice No : <span style="padding-left: 50px;"><?= $result->invoiceNumber; ?></span>
                                </p>
                            </td>
                            <td colspan="2">
                                <p>To</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    Invoice Date : <span style="padding-left: 50px;"><?= date('d-m-Y', strtotime($result->invoiceDate)); ?></span>
                                </p>
                            </td>
                            <td>
                                <p>Name : </p>
                            </td>
                            <td>
                                <p><?= $party->name; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    Payment Mode :
                                    <span style="padding-left: 50px;">
                                        <?php
                                            if ($result->voucherType == 1) {
                                                echo "Cash";
                                            } elseif ($result->voucherType == 2) {
                                                echo "Credit";
                                            } else {
                                                echo $result->voucherType;
                                            }
                                        ?>
                                    </span>
                                </p>
                            </td>
                            <td rowspan="2">
                                <p>Address : </p>
                            </td>
                            <td rowspan="2">
                                <p>
                                    <?= $party->address_1; ?>
                                    <?= get_title('districts', ['id' => $party->city], 'name'); ?>
                                    <?= $party->pin; ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    State : 
                                    <span style="padding-left: 50px;">
                                        <?= get_title('state_list', ['id' => $party->state], 'state'); ?>
                                    </span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    State Code : 
                                    <span style="padding-left: 50px;"><?= $party->state_code; ?></span>
                                </p>
                            </td>
                            <td><p>GSTIN :</p></td>
                            <td>
                                <p>
                                    <?= $party->gst; ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12" style="padding-bottom: 15px;">
                    <table class="table table-bordered" style="border-top: none;">
                        <thead>
                            <tr style="border-top: none; background-color: rgb(247, 209, 177); text-align: center;">
                                <th style="border-top: none;">S.No</th>
                                <th style="border-top: none; white-space: nowrap;">Product Description</th>
                                <th style="border-top: none;">HSN Code</th>
                                <th style="border-top: none;">Qty</th>
                                <th style="border-top: none;">Sq fit</th>
                                <th style="border-top: none;">Discount</th>
                                <th style="border-top: none;">Unit</th>
                                <th style="border-top: none;">MRP</th>
                                <th style="border-top: none;">Rate</th>
                                <th style="border-top: none;">Tax</th>
                                <th style="border-top: none;">Taxable Value</th>
                                <th style="border-top: none;">Tax Amount</th>
                                <th style="border-top: none;">Net Amount</th>
                            </tr>
                        </thead>
                        <tbody style="border-top: none;">
                            <?php
                                if ($result->product) {
                                    $n = 0;
                                    foreach ($result->product as $key => $item) {
                                        $n++;
                            ?>
                            <tr style="text-align: center;">
                                <td><?= $n; ?></td>
                                <td>
                                    <?= get_title('product', ['id' => $item->product_id], 'p_name'); ?>
                                    <br />
                                    <?= $item->product_dec; ?>
                                </td>
                                <td><?= $item->hsn; ?></td>
                                <td><?= $item->qty; ?></td>
                                <td><?= $item->sqft; ?></td>
                                <td><?= $item->discount; ?></td>
                                <td><?= get_title('product', ['id' => $item->product_id], 'unit_name'); ?></td>
                                <td><?= get_title('product', ['id' => $item->product_id], 'mrp'); ?></td>
                                <td><?= $item->rate; ?></td>
                                <td><?= $item->tax; ?></td>
                                <td><?= $item->purchase_price; ?></td>
                                <td><?= $item->amount; ?></td>
                                <td><?= $item->netAmount; ?></td>
                            </tr>
                            <?php } } ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                        <tfoot style="border-top: none;">
                            <tr>
                                <td colspan="12" style="text-align: right;">
                                    <p style="padding-right: 10px;"><b>Total</b></p>
                                </td>
                                <td>
                                    <p style="padding-left: 10px;"><b><?= $result->totalValue; ?></b></p>
                                </td>
                            </tr>
                            <tr style="background-color: rgb(247, 209, 177);">
                                <td colspan="7">Total Invoice Amount in Words</td>
                                <td colspan="5">Total Amount before Tax</td>
                                <td>
                                    <p style="padding-left: 10px;"><b><?= $result->taxableAmount; ?></b></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" rowspan="3">
                                    <p>
                                        <?= ucwords($result->amountInWords); ?>/-
                                    </p>
                                </td>
                                <td colspan="5">Add : CGST - %</td>
                                <td>
                                    <p style="padding-left: 10px;"><?= $result->totalcgst; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">Add : SGST - %</td>
                                <td>
                                    <p style="padding-left: 10px;"><?= $result->totalsgst; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">Add : IGST - %</td>
                                <td>
                                    <p style="padding-left: 10px;"><?= $result->totaligst; ?></p>
                                </td>
                            </tr>
                            <tr style="background-color: rgb(247, 209, 177);">
                                <td colspan="7">Bank Details</td>
                                <td colspan="5">
                                    <p style="text-align: right; padding-right: 10px;"><b>Grand Total</b></p>
                                </td>
                                <td>
                                    <p style="padding-left: 10px;"><b><?= $result->total; ?></b></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <p style="white-space: pre-line; margin-top: -20px;">
                                        <?= $comprofile->bank_details; ?>
                                    </p>
                                </td>
                                <td colspan="7" rowspan="2">
                                    <p style="text-align:center;">
                                        Certified that the particulars given above
                                        <br />
                                        are true and correct.
                                        <br /> 
                                        For ABC & CO
                                    </p>
                                    <p style="padding-top: 100px; text-align:center;">
                                        <b>Authorized Signatory</b>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <p>Terms and Conditions : </p>
                                    <p style="white-space: pre-line; margin-top: -20px; font-size: 12px;">
                                        <?= $comprofile->content; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr style="background-color: rgb(247, 209, 177);">
                            <td colspan="13">
                                <p style="text-align: center;">Computer Generated Invoice</p>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>