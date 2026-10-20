<html>

<head>

    <title>Uday Plastic</title>


    <script type="text/javascript" src="?= base_url() ?assets/js/jquery.min.js"></script>
</head>

<!--<body onload="window.print()">-->

<body style="font-family: sans-serif;margin: 0px;font-size: 12px;">

    <?php
    $product_field = 0;
    $comp_id = $this->session->userdata('comp_id');
    $user_id = $this->session->userdata('user_id');
    $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
    $comp_profile = $this->user_model->getRow('comp_profile', $where);

    $company_ka_data = $this->user_model->getRow('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
    $counter = 0;
    if ($company_ka_data->dcdate == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->dcno == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->lrnumber == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->eway == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->dpb == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->ptd == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->duedate == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->lrdate == 'yes') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->vahical_no == 'yes') {
        $counter = $counter + 1;
    }

    if ($company_ka_data->orderDate == 'Y') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->orderNumber == 'Y') {
        $counter = $counter + 1;
    }
    if ($company_ka_data->lot_no == 'Y') {
        $counter = $counter + 1;
    }


    $a5config = $this->user_model->getRow('a5config', $where);

    if ($a5config->hsn == 'Y')
        ++$product_field;
    if ($a5config->qty == 'Y')
        ++$product_field;
    if ($a5config->mrp == 'Y')
        ++$product_field;
    if ($a5config->sell_price == 'Y')
        ++$product_field;
    if ($a5config->purchase_price == 'Y')
        ++$product_field;
    if ($a5config->tax == 'Y')
        ++$product_field;
    if ($a5config->tax == 'Y')
        ++$product_field;
    if ($a5config->taxable_amount == 'Y')
        ++$product_field;
    if ($a5config->net_amount == 'Y')
        ++$product_field;
    if ($a5config->taxamount == 'Y')
        ++$product_field;
    if ($a5config->sqrfoot == 'Y')
        ++$product_field;



    if ($comp_profile) {
        $jklmn = $comp_profile->total_invoice;
        for ($jkl = 0; $jkl < $jklmn; $jkl++) {
    ?>
            <div class="main_container" style="page-break-after: always;margin: auto;">

                <?php

                if ($jkl == 0) {
                ?>
                    <div class="copy_title text-primary" style="margin-right: 34px;font-size: 14px;text-align: right;">ORIGINAL</div>

                <?php
                } elseif ($jkl == 1) {
                ?>
                    <div class="copy_title text-dark" style="margin-right: 34px;font-size: 14px;text-align: right;">DUPLICATE</div>

                <?php
                } else {
                ?>
                    <div class="copy_title text-dark" style="margin-right: 34px;font-size: 14px;text-align: right;">TRIPLICATE</div>

                <?php
                }
                ?>

                <div class="container" style="border: 1px solid;">
                    <table border="0" cellspacing="0" class="item_table" id="item_table_1" style="border-spacing: 0;width: 100%;font-size: 13px;">
                        <thead style="display: table-header-group !important;">

                            <tr style="page-break-inside: avoid !important;">
                                <td style="padding: 0px;border-right: 0px;page-break-inside: avoid;vertical-align: top;" colspan="13">
                                    <div class="header_content">
                                        <?php
                                        $comp_id = $this->session->userdata('comp_id');
                                        $user_id = $this->session->userdata('user_id');
                                        $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
                                        $comp_profile = $this->user_model->getRow('comp_profile', $where);
                                        $log_postion = $comp_profile->logo_position;
                                        $logo_status = $comp_profile->logo_status;
                                        $comp_details_position = $comp_profile->comp_details_position;
                                        $inventory_billing = $this->user_model->getRow('inventory_billing', ['id' => $comp_id, 'user_id' => $user_id]);

                                        ?>
                                        <div class="header" style="text-align: <?= $log_postion ?>;padding: 5px 10px;font-size: 14px;clear: both;overflow: auto;margin-bottom:200px;">
                                            <?php
                                            if ($logo_status == 'enable' && $log_postion) {
                                            ?>
                                            <img src="<?= base_url() ?>uploads/complogo/<?= $comp_profile-logo ?>" style="width: <?= $comp_profile-logo_width ?>px;height: <?= $comp_profile-logo_height ?>px;float:<?= $log_postion ?>;text-align:<?= $log_postion ?>;">

                                                <!--<img src="<?= base_url() ?>uploads/complogo/<?= $comp_profile->logo ?>" style="width: 400px;height: 200px;float:<?= $log_postion ?>;text-align:<?= $log_postion ?>;">-->
                                            <?php
                                            }
                                            ?>
                                            <div style="float:?= $comp_details_position ?;text-align:?= $comp_details_position ?;margin-top:-13px;" ">
												<?php
                                                if ($a5config->comp_name == 'Y') {
                                                ?>
													<div class=" name" style="font-size:16;margin-bottom: -20px;">
                                                <h2 style=" margin-top: 23px; "><strong><?= $comp_profile->name ?></strong></h2>
                                            </div>
                                        <?php
                                                }
                                        ?>
                                        <?php
                                        if ($a5config->comp_address == 'Y') {
                                        ?>
                                            <div class="address" style="font-size: 16px;white-space: pre-wrap;"><?= $inventory_billing->address ?></div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if ($a5config->comp_mobile_number == 'Y') {
                                        ?>
                                            <div class="contact_no" style="font-size: 16px;"><?= $inventory_billing->number ?></div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if ($a5config->comp_email == 'Y') {
                                        ?>
                                            <div class="email" style="font-size: 16px;"><?= $inventory_billing->email ?></div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if ($a5config->comp_gst == 'Y') {
                                        ?>
                                            <!--<div class="website" style="font-size: 18px;">www.umatic.in</div>-->
                                            <div class="gst_no" style="font-size: 16px;color:red;"><b style="color:black;">GSTIN:<?= $inventory_billing->gst_no ?></b></div>
                                        <?php
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    <?php if ($result->customerId != '' && $result->customerId != 0) {
                                        $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                    }
                                    ?>
                                    <table style="width: 100%;border-collapse: collapse;font-size: 16px;border-spacing: 0;">
                                        <?php
                                        if ($a5config->title) {
                                        ?>
                                            <tr style="page-break-inside: avoid !important;">
                                                <td colspan="2" style="text-align: center;border-bottom: 1px solid #000;border-top: 1px solid #000;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">
                                                    <h3 style="margin: 0px;"><?= $a5config->title ?></h3>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr style="page-break-inside: avoid !important;">

                                            <td style="width: 50%;padding: 10px;border-bottom: 1px solid #000;line-height: 15px;font-size: 12px;border-right: 1px solid;page-break-inside: avoid;vertical-align: top;">
                                                <h6 style=" margin-bottom: auto; "><strong>Billing Address,</strong></h6>
                                                <?php
                                                if ($a5config->customer_name == 'Y') {
                                                ?>
                                                    <h6 style="text-transform: uppercase; margin: 5px 0 5px 0;;font-size:15px;"><strong><?= $party->name ?></strong></h6>


                                                <?php
                                                }
                                                if ($a5config->customer_address == 'Y') {
                                                ?>
                                                <p style="margin: 5px 0 5px 0;font-size:12px;">
                                                    <?= $result->customerAddress ?>
                                                    <br />
                                                    <?php $city = $this->db->get_where('districts', ['id' => $party->city])->row(); echo $city->name; ?>
                                                    <br />
                                                    <?php $state = $this->db->get_where('state_list', ['id' => $party->state])->row(); echo $state->state; ?>&nbsp;(<?= $party->pin; ?>)
                                                </p>


                                                <?php
                                                }
                                                if ($a5config->customer_mobile_number == 'Y') {
                                                ?>
                                                    <p style="margin: 5px 0 5px 0;">Contact: <?= $result->customerMobile ?></p>

                                                <?php
                                                }
                                                if ($a5config->customer_email == 'Y') {
                                                ?>
                                                    <p style="margin: 5px 0 5px 0;">Email: <?= $result->customerEmail ?></p>


                                                <?php
                                                }


                                                if ($a5config->customer_gst == 'Y') {
                                                ?>
                                                    <p style="margin: 0 0 5px 0;color:red;"><strong>GSTIN: <?= $result->gstNo ?></strong> </p>

                                                <?php
                                                }

                                                ?>



                                            </td>
                                            <?php
                                            if ($a5config->shipping_address == 'Y') {

                                            ?>
                                                <td style="width: 50%;padding: 10px;border-left: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid;page-break-inside: avoid;vertical-align: top;">

                                                    <h5 style="margin: 0 0 5px 0;"><strong>Shipping Address,</strong></h5>
                                                    <p style="text-transform: uppercase; margin: 0 0 5px 0;"><?= $result->shipping_address ?></p>

                                                    <!-- <h4 style="text-transform: uppercase; margin: 0 0 5px 0;">Brigade panorama apartment owners association</h4>
													<p style="text-transform: uppercase; margin: 0 0 5px 0;">43/34/453, Club house, ground floor, brigade
														panorama cluchouse</p>
													<p style="margin: 0 0 5px 0;">Contact: </p>
													<p style="margin: 0 0 5px 0;">Email: </p>
													<p style="margin: 0 0 5px 0;"><strong>GSTN: 29768A678</strong> </p> -->
                                                </td>
                                            <?php
                                            } else {
                                            ?>
                                                <td style="width: 50%;padding: 10px;border-right: 0px;page-break-inside: avoid;vertical-align: top;">
                                                    <table style="width: 100%;border-collapse: collapse;font-size: 13px;border-spacing: 0;">
                                                        <?php
                                                        if ($a5config->invoice_number == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Invoice No :</strong></td>
                                                                <td style="width: 50%;text-align: right;color: red;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->invoiceNumber ?></strong></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($a5config->invoice_date == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Invoice Date :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                                            </tr>

                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($a5config->state == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>State of Supply :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?></strong></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($a5config->payment_mode == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Payment Mode :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>

                                                                        <?php
                                                                        if ($result->voucherType == 1) {
                                                                            echo "Cash";
                                                                        } elseif ($result->voucherType == 2) {
                                                                            echo "Credit";
                                                                        } else {
                                                                            echo $result->voucherType;
                                                                        }
                                                                        ?>
                                                                    </strong></td>
                                                            </tr>
                                                        <?php
                                                        }

                                                        if ($counter > 4 || $a5config->shipping_address == 'N') {

                                                        ?>



                                                            <?php
                                                            $dispatch_by = $this->session->userdata('dpb');
                                                            if ($dispatch_by == 'yes') {
                                                            ?>

                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Dispatch By :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= ucwords($result->transport) ?></strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            $lr = $this->session->userdata('lrnumber');
                                                            if ($lr == 'yes') {
                                                            ?>

                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>LR No :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->lrNumber ?></strong></td>
                                                                </tr>

                                                            <?php
                                                            }


                                                            $ewayBill = $this->session->userdata('eway');
                                                            if ($ewayBill == 'yes') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Eway Bill :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->ewayBill ?></strong></td>
                                                                </tr>

                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            $duedate = $this->session->userdata('duedate');
                                                            if ($duedate == 'yes') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>DueDate :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->dueDate ?></strong></td>
                                                                </tr>
                                                            <?php
                                                            }


                                                            if ($company_ka_data->ptd == 'yes') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Reciept Date :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->recieptDate ?></strong></td>
                                                                </tr>
                                                            <?php


                                                            }


                                                            if ($company_ka_data->lrdate == 'yes') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Lrdate :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->lrDate ?></strong></td>
                                                                </tr>
                                                            <?php


                                                            }
                                                            if ($company_ka_data->vahical_no == 'yes') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Vehicle No :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->vahical_no ?></strong></td>
                                                                </tr>
                                                            <?php


                                                            }

                                                            if ($company_ka_data->orderDate == 'Y') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>OrderDate :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->orderDate ?></strong></td>
                                                                </tr>
                                                            <?php

                                                            }
                                                            if ($company_ka_data->orderNumber == 'Y') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>OrderNumber :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->orderNumber ?></strong></td>
                                                                </tr>
                                                            <?php

                                                            }
                                                            if ($company_ka_data->lot_no == 'Y') {
                                                            ?>
                                                                <tr style="page-break-inside: avoid !important;">
                                                                    <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Lot No :</strong></td>
                                                                    <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->lot_no ?></strong></td>
                                                                </tr>
                                                            <?php


                                                            }

                                                            ?>





                                                        <?php
                                                        }
                                                        ?>
                                                    </table>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                        <tr style="page-break-inside: avoid !important;">

                                            <?php
                                            if ($a5config->shipping_address == 'Y') {

                                            ?>
                                                <td style="width: 50%;padding: 10px;border-right: 1px solid;page-break-inside: avoid;vertical-align: top;">
                                                    <table style="width: 100%;border-collapse: collapse;font-size: 12px;border-spacing: 0;">
                                                        <?php
                                                        $dispatch_by = $this->session->userdata('dpb');
                                                        if ($dispatch_by == 'yes') {
                                                        ?>

                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Dispatch By :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= ucwords($result->transport) ?></strong></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        $lr = $this->session->userdata('lrnumber');
                                                        if ($lr == 'yes') {
                                                        ?>

                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>LR No :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->lrNumber ?></strong></td>
                                                            </tr>

                                                        <?php
                                                        }


                                                        $ewayBill = $this->session->userdata('eway');
                                                        if ($ewayBill == 'yes') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Eway Bill :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->ewayBill ?></strong></td>
                                                            </tr>

                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        $duedate = $this->session->userdata('duedate');
                                                        if ($duedate == 'yes') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>DueDate :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->dueDate ?></strong></td>
                                                            </tr>
                                                        <?php
                                                        }


                                                        if ($company_ka_data->ptd == 'yes') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Reciept Date :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->recieptDate ?></strong></td>
                                                            </tr>
                                                        <?php


                                                        }


                                                        if ($company_ka_data->lrdate == 'yes') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Lrdate :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->lrDate ?></strong></td>
                                                            </tr>
                                                        <?php


                                                        }
                                                        if ($company_ka_data->vahical_no == 'yes') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Vehicle No :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->vahical_no ?></strong></td>
                                                            </tr>
                                                        <?php


                                                        }

                                                        if ($company_ka_data->orderDate == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>OrderDate :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->orderDate ?></strong></td>
                                                            </tr>
                                                        <?php

                                                        }
                                                        if ($company_ka_data->orderNumber == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>OrderNumber :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->orderNumber ?></strong></td>
                                                            </tr>
                                                        <?php

                                                        }
                                                        if ($company_ka_data->lot_no == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Lot No :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->lot_no ?></strong></td>
                                                            </tr>
                                                        <?php


                                                        }

                                                        ?>





                                                        <?php

                                                        ?>
                                                    </table>
                                                </td>
                                                <?php
                                            } else {
                                                if ($a5config->shipping_address == 'Y') {

                                                ?>
                                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                                <?php
                                                }
                                            }

                                            if ($a5config->shipping_address == 'Y') {


                                                ?>
                                                <td style="width: 50%;padding: 10px;border-right: 0px;page-break-inside: avoid;vertical-align: top;">
                                                    <table style="width: 100%;border-collapse: collapse;ont-size: 12px;border-spacing: 0;">
                                                        <?php
                                                        if ($a5config->invoice_number == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Invoice No :</strong></td>
                                                                <td style="width: 50%;text-align: right;color: red;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= $result->invoiceNumber ?></strong></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($a5config->invoice_date == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Invoice Date :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                                            </tr>

                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($a5config->state == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>State of Supply :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong><?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?></strong></td>

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($a5config->payment_mode == 'Y') {
                                                        ?>
                                                            <tr style="page-break-inside: avoid !important;">
                                                                <td style="width: 50%;border-right: none;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>Payment Mode :</strong></td>
                                                                <td style="width: 50%;text-align: right;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><strong>

                                                                        <?php
                                                                        if ($result->voucherType == 2) {
                                                                            echo "Cash";
                                                                        } elseif ($result->voucherType == 1) {
                                                                            echo "Credit";
                                                                        } else {
                                                                            echo $result->voucherType;
                                                                        }
                                                                        ?>
                                                                    </strong></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </table>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    </table>
                </div>
                </td>
                </tr>

                <tr style="page-break-inside: avoid !important;">
                    <th class="item_no" style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;text-align: center !important;vertical-align: middle !important;">SL</th>
                    <?php
                    if ($product_field == 10) {
                        $width = 275;
                    } elseif ($product_field == 9) {
                        $width = 300;
                    } elseif ($product_field == 8) {
                        $width = 325;
                    } elseif ($product_field == 7) {
                        $width = 340;
                    } elseif ($product_field == 6) {
                        $width = 360;
                    } elseif ($product_field == 5) {
                        $width = 390;
                    } elseif ($product_field == 4) {
                        $width = 450;
                    } elseif ($product_field == 3) {
                        $width = 500;
                    } elseif ($product_field == 2) {
                        $width = 550;
                    } else {
                        $width = 650;
                    }
                    ?>
                    <th class="item_name" style="width: <?= $width ?>px;border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;text-align: center !important;">DESCRIPTION OF GOODS/SERVICE</th>
                    <?php
                    if ($a5config->hsn == 'Y') {
                        echo '<th class="item_hsn">HSN/SAC</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }
                    if ($a5config->qty == 'Y') {
                        echo '<th class="item_qty">QTY</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }

                    if ($a5config->sqrfoot == 'Y') {
                        echo '<th class="item_qty">SqFt</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }
                    if ($a5config->discount_invoice == 'Y') {
                        echo '<th class="discount">Discount</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }

                    if ($a5config->unit == 'Y') {
                        echo '<th class="unit">UNIT</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }
                    if ($a5config->mrp == 'Y') {
                        echo '<th class="item_mrp">MRP</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }


                    if ($a5config->sell_price == 'Y') {
                        echo '<th class="item_rate">RATE</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }
                    if ($a5config->tax == 'Y') {
                        echo '<th class="tax">Tax%</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }
                    if ($a5config->taxable_amount == 'Y') {
                        echo '<th class="taxable_amount">Taxble Amt</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }

                    if ($a5config->taxamount == 'Y') {
                        echo '<th class="tax_amount">Tax Amount</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }
                    if ($a5config->net_amount == 'Y') {
                        echo '<th class="netAmount">NetAmount</th>';
                    } else {
                        echo '<th style="border-right:0px;"></th>';
                    }
                    ?>
                    <!-- <th></th> -->




                </tr>
                </thead>
                <tbody class="tbody">
                    <?php
                    $nosItm = 0;
                    $lines = 6;
                    $counts = 0;
                    $totalDiscount = 0;
                    $totalRate = 0;
                    $totalCD = 0;
                    if ($result->product) {
                        foreach ($result->product as $key => $row) {
                            $qtty = $row->qty;
                            $nosItm += $qtty;
                            $taxIte =  get_title('product', ['id' => $row->tax], 'tax');
                            $rate = $row->rate;
                            // $trheight=40;

                    ?>
                            <tr class="text-center" style="page-break-inside: avoid !important;">
                                <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= $key + 1 ?></td>
                                <td class="text-left" style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?php

                                                                                                                                                        $product_name = get_title('product', ['id' => $row->product_id], 'p_name');
                                                                                                                                                        if ($product_name) {
                                                                                                                                                            echo $product_name;
                                                                                                                                                        } else {
                                                                                                                                                            echo $product_name;
                                                                                                                                                        }
                                                                                                                                                        if ($row->product_dec) {
                                                                                                                                                        ?>
                                        <br>
                                        <div class="descc" style="font-size: 10px;white-space: pre-wrap;"><?= $row->product_dec ?></div>
                                    <?php
                                                                                                                                                        }
                                    ?>


                                </td>

                                <?php
                                if ($a5config->hsn == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= $row->hsn ?></td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                ?>
                                <?php
                                if ($a5config->qty == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= $row->qty ?></td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                ?>
                                <?php
                                if ($a5config->sqrfoot == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= $row->sqft ?></td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                ?>
                                <?php
                                if ($a5config->discount_invoice == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= $row->discount ?>%</td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                ?>
                                <?php
                                if ($a5config->unit == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= get_title('product', ['id' => $row->product_id], 'unit_name') ?></td>

                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                ?>
                                <?php
                                if ($a5config->mrp == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= get_title('product', ['id' => $row->product_id], 'mrp') ?></td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }

                                if ($a5config->sell_price == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= money_formats($row->purchase_price / $row->qty) ?></td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                if ($a5config->tax == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"><?= $row->tax ?>%</td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                if ($a5config->taxable_amount == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">
                                        <?php
                                        echo $row->purchase_price;
                                        ?>
                                    </td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }

                                if ($a5config->taxamount == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">
                                        <?= money_formats($row->amount) ?>
                                    </td>
                                <?php
                                } else {
                                    echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                }
                                if ($a5config->net_amount == 'Y') {
                                ?>
                                    <td style="border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">
                                        <?= money_formats($row->netAmount); ?>
                                    </td>
                                <?php
                                    // }else
                                    // {
                                    // 	echo '<td style="border-right:0px;border-bottom:0px;"></td>';

                                }
                                ?>
                                <!-- <td></td> -->
                            </tr>

                        <?php
                            $counts = $key;
                        }
                    }
                    if (isset($counts) && !empty($counts)) {
                        $lines = $lines - $counts;
                        $lines = $lines * 1;
                    } else {
                        $lines = $lines - $counts;
                    }

                    if (isset($counts) && !empty($counts)) {

                        // $count=count($counts);

                        $lines = $lines - $counts;
                    } else {

                        $lines = $lines - $counts;
                    }

                    if ($lines > 0) {
                        for ($i = 0; $i < $lines; $i++) { ?>
                            <tr style="page-break-inside: avoid !important;">

                                <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;
                                    <p></p>
                                </td>

                                <?php
                                if ($a5config->hsn == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->qty == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->sqrfoot == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->discount_invoice == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->unit == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->mrp == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                ?>

                                <?php
                                if ($a5config->sell_price == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }

                                if ($a5config->tax == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                if ($a5config->taxable_amount == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }

                                if ($a5config->taxamount == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                if ($a5config->net_amount == 'Y') {
                                ?>
                                    <td style="border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;">&nbsp;</td>
                                <?php
                                } else {
                                ?>
                                    <td style="border: none;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: top;"></td>
                                <?php
                                }
                                ?>
                                <!-- <td>&nbsp;</td> -->




                            </tr>
                    <?php }
                    } ?>
                    <tr style="text-align: center;page-break-inside: avoid !important;">
                        <!-- <th></th> -->
                        <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        if ($a5config->hsn == 'Y') {
                        ?>
                            <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }

                        if ($a5config->qty == 'Y') {
                        ?>
                            <th class="item_qty" style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"><?= $nosItm ?></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }
                        if ($a5config->sqrfoot == 'Y') {
                        ?>
                            <th class="item_qty" style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }
                        if ($a5config->discount_invoice == 'Y') {
                        ?>
                            <th class="discount" style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }

                        if ($a5config->unit == 'Y') {
                        ?>
                            <th class="unit" style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }

                        ?>
                        <?php
                        if ($a5config->mrp == 'Y') {
                        ?>
                            <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }

                        if ($a5config->sell_price == 'Y') {
                        ?>
                            <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }
                        if ($a5config->tax == 'Y') {
                        ?>
                            <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }

                        if ($a5config->taxable_amount == 'Y') {
                        ?>


                            <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"><?= money_formats($result->taxableAmount) ?></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }

                        ?>
                        <?php
                        if ($a5config->taxamount == 'Y') {
                        ?>
                            <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 1px solid;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;">
                                <?= money_formats($result->totalTax) ?>
                            </th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }
                        if ($a5config->net_amount == 'Y') {
                        ?>

                            <th style="border-top: 1px solid;border-bottom: 1px solid;font-weight: bold;border-right: 0px;page-break-inside: avoid;padding: 1px 2px;vertical-align: middle !important;"><?= money_formats($result->totalValue) ?></th>
                        <?php
                        } else {
                            echo '<th style="border-right:0px;"></th>';
                        }
                        ?>
                        <!-- <th></th> -->
                    </tr>
                </tbody>
                </table>
                <table border="0" width="100%" style="border-spacing: 0;">
                    <tbody>
                        <tr style="page-break-inside: avoid !important;">
                            <?php
                            if ($a5config->total_summary == 'Y') {
                            ?>
                                <td class="summary_table_div" style="padding: 0px 10px;vertical-align: top;border-right: 1px solid;width: 530px;">
                                    <table class="tax_summary" border="0" cellspacing="0" style="border-spacing: 0;width: 100%;">
                                        <thead style="display: table-header-group !important;">
                                            <tr style="page-break-inside: avoid !important;">
                                                <th class="tax_slab" style="text-align: left;font-weight: bold;font-size: 13px;">SUMMARY</th>
                                                <th class="tax_summary_details" style="text-align: right;font-weight: bold;font-size: 13px;">Taxable</th>
                                                <th class="tax_summary_details" style="text-align: right;font-weight: bold;font-size: 13px;">CGST</th>
                                                <th class="tax_summary_details" style="text-align: right;font-weight: bold;font-size: 13px;">SGST</th>
                                                <th class="tax_summary_details" style="text-align: right;font-weight: bold;font-size: 13px;">IGST</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $this->db->select('tax');
                                            $this->db->select_sum('purchase_price');
                                            $this->db->select_sum('sgst');
                                            $this->db->select_sum('cgst');
                                            $this->db->select_sum('igst');
                                            $this->db->select_sum('taxAmount');
                                            $this->db->from('invoice_product');
                                            $this->db->where('invoiceID', $result->id);
                                            $this->db->where('InvoiceNo', $result->invoiceNumber);
                                            $this->db->where('type', 'Sale');
                                            $this->db->group_by('tax');
                                            $tax = $this->db->get()->result();
                                            $tcount = 2;
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $row) { ?>
                                                    <tr style="font-size: 20px;page-break-inside: avoid !important;">
                                                        <td class="tax_slab" style="text-align: left;font-size: 13px;"><b>GST @<?= $row->tax; ?>%</b></td>
                                                        <td style="font-size: 13px;text-align: right;"><?= ($row->purchase_price) ?></td>
                                                        <!-- <td><?= money_formats($row->taxAmount, 2) ?></td> -->
                                                        <td style="font-size: 13px;text-align: right;"><?= money_formats($row->sgst, 2) ?></td>
                                                        <td style="font-size: 13px;text-align: right;"><?= money_formats($row->cgst, 2) ?></td>
                                                        <td style="font-size: 13px;text-align: right;"><?= money_formats($row->igst, 2) ?></td>
                                                    </tr>
                                                <?php $tcount--;
                                                }
                                            }
                                            if ($tcount > 0) {
                                                for ($i = 0; $i < $tcount; $i++) { ?>
                                                    <tr style="page-break-inside: avoid !important;">
                                                        <td style="font-size: 13px;text-align: right;">&nbsp;</td>
                                                        <td style="font-size: 13px;text-align: right;">&nbsp;</td>
                                                        <td style="font-size: 13px;text-align: right;">&nbsp;</td>
                                                        <td style="font-size: 13px;text-align: right;">&nbsp;</td>
                                                        <td style="font-size: 13px;text-align: right;">&nbsp;</td>
                                                        <!-- <td>&nbsp;</td> -->
                                                    </tr>
                                            <?php   }
                                            } ?>

                                        </tbody>
                                    </table>
                                    <div style="position:relative;bottom:-22px;font-size:14px;">
                                        <span style="margin-right: 1em;"><span>No of ltems : </span><?= $counts + 1 ?> </span>
                                        <span><span>Total Qty : </span><?= $nosItm; ?></span>
                                    </div>
                                </td>
                            <?php
                            }
                            ?>
                            <td class="total_summary" style="width: 493px;vertical-align: top;">
                                <?php
                                if ($a5config->total_taxable_amount == 'Y') {
                                ?>
                                    <div class="summary_label" style="clear: both;display: inline-block;width: 97%;font-weight: bold;font-size: 13px;padding-left: 10px;border-bottom: 1px solid;line-height: 22px;">
                                        <span style="float:left;">TAXABLE</span>
                                        <span style="float:right;position: relative;right: 6px;"><i class="fa fa-inr" style="font-family: var(--fa-style-family,&quot;Font Awesome 6 Free&quot;);font-weight: var(--fa-style,900);-moz-osx-font-smoothing: grayscale;-webkit-font-smoothing: antialiased;display: var(--fa-display,inline-block);font-style: normal;font-variant: normal;line-height: 1;text-rendering: auto;"></i><?= money_formats($result->taxableAmount) ?></span>

                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->total_tax_amount == 'Y') {
                                ?>
                                    <div class="summary_label" style="clear: both;display: inline-block;width: 97%;font-weight: bold;font-size: 13px;padding-left: 10px;border-bottom: 1px solid;line-height: 22px;">
                                        <span style="float:left;">TAX</span>
                                        <span style="float:right;position: relative;right: 6px;"><i class="fa fa-inr" style="font-family: var(--fa-style-family,&quot;Font Awesome 6 Free&quot;);font-weight: var(--fa-style,900);-moz-osx-font-smoothing: grayscale;-webkit-font-smoothing: antialiased;display: var(--fa-display,inline-block);font-style: normal;font-variant: normal;line-height: 1;text-rendering: auto;"></i><?= money_formats($result->totalTax) ?></span>

                                    </div>

                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->round_off == 'Y') {
                                ?>
                                    <div class="summary_label" style="clear: both;border-bottom: 1px solid;display: inline-block;width: 97%;font-weight: bold;font-size: 13px;padding-left: 10px;line-height: 22px;">
                                        <span style="float:left;">ROUND OFF</span>
                                        <span style="float:right;position: relative;right: 6px;"><i class="fa fa-inr" style="font-family: var(--fa-style-family,&quot;Font Awesome 6 Free&quot;);font-weight: var(--fa-style,900);-moz-osx-font-smoothing: grayscale;-webkit-font-smoothing: antialiased;display: var(--fa-display,inline-block);font-style: normal;font-variant: normal;line-height: 1;text-rendering: auto;"></i><?= money_formats($result->roundOff) ?></span>

                                    </div>

                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->grand_total == 'Y') {
                                ?>

                                    <div class="summary_label" style="clear: both;border-bottom: none;display: inline-block;width: 97%;font-weight: bold;font-size: 13px;padding-left: 10px;line-height: 22px;">
                                        <span style="float:left;">GRAND TOTAL</span>
                                        <span style="float:right;position: relative;right: 6px;"><i class="fa fa-inr" style="font-family: var(--fa-style-family,&quot;Font Awesome 6 Free&quot;);font-weight: var(--fa-style,900);-moz-osx-font-smoothing: grayscale;-webkit-font-smoothing: antialiased;display: var(--fa-display,inline-block);font-style: normal;font-variant: normal;line-height: 1;text-rendering: auto;"></i><?= money_formats($result->total) ?></span>

                                    </div>
                                <?php
                                }
                                ?>
                            </td>

                        </tr>

                    </tbody>
                </table>
                <div class="amount_word" style="border-top: 1px solid;font-weight: bold;border-bottom: 1px solid;padding: 5px 10px;"><?= ucwords($result->amountInWords) ?>/-</div>
                <div class="bank_info" style="font-size: 11px;border-bottom: 1px solid;padding: 5px 10px;">
                    <?php
                    if ($a5config->bank_info == 'Y') {
                    ?>
                        <div style="font-weight:bold;font-family:'ArialMt Bold'">Bank Account Information :</div>

                        <?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
                        if (isset($bank) && !empty($bank)) {
                        ?>
                            <div class="bank_account_name" style="width: 100%;"><strong><?= $bank->bank_details; ?> </strong></div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <table class="inv_details" cellspacing="0" cellpadding="0" style="border-spacing: 0;font-size: 12px;width: 100%;">
                    <tbody>
                        <tr style="page-break-inside: avoid !important;">
                            <td class="terms" style="border-right: 1px solid;padding: 5px;vertical-align: top;width: 300px;">
                                <div class="label" style="font-size: 15px;margin-bottom: -12px;font-weight: bold;">Terms and Conditions</div>
                                <?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
                                if (isset($bank) && !empty($bank)) {
                                ?>
                                    <span style="font-size:12px;line-height: 1.1;"><?= $bank->content; ?></span>
                                <?php
                                }
                                ?>

                            </td>
                            <?php
                            if ($a5config->signature == 'Y') {
                            ?>
                                <td class="receiver_signature" style="font-size: 14px;border-right: 1px solid;padding: 5px;vertical-align: bottom;text-align: center;width: 100px;">

                                    <div>
                                        <p>Receiver Signatures</p>
                                    </div>
                                </td>
                            <?php
                            }
                            ?>

                            <td class="customer_signature" style="font-size: 16px;border-right: 0px solid;padding: 5px;vertical-align: bottom;text-align: center;width: 300px;height: 100px;">
                                <div class="label"><b>For:<?= $comp_profile->name ?></b></div>
                                <?php
                                if ($comp_profile->signature_height) {
                                    $signature_height = $comp_profile->signature_height;
                                } else {
                                    $signature_height = "137";
                                }

                                if ($comp_profile->signature_width) {
                                    $signature_width = $comp_profile->signature_width;
                                } else {
                                    $signature_width = "260";
                                }
                                if ($comp_profile->signature_display == 'enable') {

                                ?>
                                    <?php
                                    if ($bank->scan_signature) {
                                    ?>
                                <img src="<?= base_url() ?>uploads/signature/<?= $bank-scan_signature ?>" style=" width: <?= $signature_width ?>px; height: <?= $signature_height ?>px;">
                                <?php
                                    }else
										{
										echo '<div style="height:100px;"></div>';

										}
                                }else
										{
										echo '<div style="height:100px;"></div>';

										}
                                ?>
                                <br>
                                Authorised Signature
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>

            <?php
            if ($a5config->footer == 'Y') {
            ?>
                <div class=" footer" style="font-size: 12px;text-align: center;margin-top: 5px;">This is a Computer Generated Copy Generated by Uday Plastic
                </div>
            <?php
            }
            ?>
            <!--<span style="float:right;">Continue..</span>-->
            </div>
    <?php
        }
    }
    ?>



    <script>
        $(document).ready(function() {
            window.print();
            // 			setTimeout("closePrintView()", 1000);
        });

        function closePrintView() {
            // 			document.location.href = "<?= base_url('sale/sale_report') ?>";
            window.open('', '_self')
            window.close()
        }
        $(document).ready(function() {
            for (z = 1; z <= 2; z++) {
                var height = document.getElementById('item_table_' + z).offsetHeight;
                if (height < 600) {
                    var tableis = document.getElementById('tbody_' + z);
                    var last = tableis.rows.length - 1;
                    var Tr = tableis.insertRow(last);
                    var size = 600 - height;
                    Tr.style.height = size + "px";
                    var i;
                    for (i = 0; i < 8; i++) {
                        var Td1 = Tr.insertCell(i);
                        Td1.innerHTML = "";
                        Tr.appendChild(Td1);
                    }
                }
            }
            // 			window.print();
        });
        jQuery(document).bind("keyup keydown", function(e) {
            if (e.ctrlKey && e.keyCode == 80) {
                window.print();
                return false;
            }
        });
    </script>
</body>


</html>