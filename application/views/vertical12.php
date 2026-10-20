<style>
 

    body {
        font-family: ArialMt;
        margin: 0px;
    }

    #barcode {
        margin-left: 40px;
        margin-top:20px;
    }

    .barcode:nth-child(even) {
        margin-left: 6px;
    }

    .barcode {
        text-align: center;
        /* float: left; */
        display: inline-block;
        font-size: 12px;
        overflow: hidden;
        width: 4.76cm;
        height: 20mm;
        margin-bottom: 0px;
        margin-left: 0px;
        padding-top: 10px;
    }

    body {
        margin: 0px 12px;
    }

    .barcode img {
        margin-top: -0px;
    }

    .clear {
        clear: both;
    }

    .clear:nth-child(odd) {
        margin: 5px;
    }

    .iat {
        clear: both;
        float: right;
        font-size: 8px;
        font-weight: bold;
        padding-right: 10px;
    }

    .price_code {
        float: left;
        font-size: 8px;
        font-weight: bold;
        padding-left: 30px;
    }

    @page {
        size: auto;
        margin: 1mm 1mm 1mm 1mm;
    }
    .rotatetxt {
        transform: rotate(-90deg);
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        position: absolute;
        bottom: 27px;
        text-align: center;
        font-size: 18px;
        left: -20px;
        text-transform: uppercase;
        font-weight: 600;
    }
</style>
<script type="text/javascript" src="<?= base_url() ?>assets/js/JsBarcode.all.min.js"></script>

<body>
    <div id="barcode">
        <?php
        $barcode_title = '';
        $barcode_sell_prefix = '';
        $barcode_mrp_prefix = '';
        $company_details = $this->user_model->getRow('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
        if ($company_details) {


            $barcode_title = $company_details->barcode_title;
            $barcode_mrp_prefix = $company_details->barcode_mrp_prefix;
            $barcode_sell_prefix = $company_details->barcode_sell_prefix;
            $input_number = $company_details->barcode_pre_row;

            if (empty($input_number) || $input_number == '') {
                $input_number = 2;
            }
            $item1 = $company_details->item1;
            $item2 = $company_details->item2;
            $item3 = $company_details->item3;
            if ($company_details->price_code == 'mrp_price') {
                $purchase_code = $this->user_model->getRow('mrp_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
            } else if ($company_details->price_code == 'sell_price') {
                $purchase_code = $this->user_model->getRow('sell_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                // } else if ($purchase_code == 'purchase_price') {
            } else {
                $purchase_code = $this->user_model->getRow('purchase_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
            }
        }



        if (isset($result) && !empty($result)) {
            $counter = 0;
            foreach ($result as $key => $row) {
                $where = ['productID' => $row];
                $qrcode = $this->user_model->getRow('qrcode', $where);
                if ($qrcode) {
                    for ($i = 0; $i < $qrcode->qty; $i++) {
                        ++$counter;
        ?>


                        <?php
                        $where = ['id' => $row];
                        $product = $this->user_model->getRow('product', $where);
                        $where = ['id' => $this->session->userdata('comp_id')];
                        $productCode = $this->user_model->getRow('inventory_billing', $where);
                        $resp = '';
                        ?>
                        <?php
                        if ($qrcode) {

                        ?>
                            <div class="barcode">
                                       <div class="rotatetxt">Rotate</div>

                                <div style="font-size:11px;"><?= $barcode_title ?></div>
                                <!-- <img id="barcode_0" data="J5419" class="barcode_img" /> -->
                                <img class="barcode_img" id="barcode_<?= $row ?>" data="" src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="width:100px;height:15px;">

                                <div style="font-size:11px;">
                                    <div style="margin-top:5px;"><?= $product->p_name; ?></div>
                                    <div><?= $barcode_mrp_prefix ?>.<?= $product->mrp ?></div>
                                    <div><?= $barcode_sell_prefix ?>.<?= $product->price ?></div>
                                </div>
                                <?php
                                $array  = array_map('intval', str_split($product->price));
                                $arry_of_code = [];
                                if ($array && $purchase_code) {
                                    foreach ($array as $a) {
                                        if ($a == 0) {
                                            $zero = $purchase_code->zero;
                                            $arry_of_code[] = $zero;
                                        } elseif ($a == 1) {
                                            $first = $purchase_code->first;
                                            $arry_of_code[] = $first;
                                        } elseif ($a == 2) {
                                            $second = $purchase_code->second;
                                            $arry_of_code[] = $second;
                                        } elseif ($a == 3) {
                                            $third = $purchase_code->third;
                                            $arry_of_code[] = $third;
                                        } elseif ($a == 4) {
                                            $fourth = $purchase_code->fourth;
                                            $arry_of_code[] = $fourth;
                                        } elseif ($a == 5) {
                                            $fifth = $purchase_code->fifth;
                                            $arry_of_code[] = $fifth;
                                        } elseif ($a == 6) {
                                            $sixth = $purchase_code->sixth;
                                            $arry_of_code[] = $sixth;
                                        } elseif ($a == 7) {
                                            $seventh = $purchase_code->seventh;
                                            $arry_of_code[] = $seventh;
                                        } elseif ($a == 8) {
                                            $eight = $purchase_code->eight;
                                            $arry_of_code[] = $eight;
                                        } else {
                                            $nine = $purchase_code->nine;
                                            $arry_of_code[] = $nine;
                                        }
                                    }
                                    $resp = implode("", $arry_of_code);
                                }


                                ?>
                                <div class="iat">(Incl. of all taxes)</div>
                                <span class="price_code"><?= $resp ?></span>
                            </div>
                            <?php

                            if ($counter % $input_number == 0) {
                            ?>
                                <div class='clear'></div>
                            <?php
                            }
                            ?>
        <?php
                        }
                    }
                }
            }
        }
        ?>

    </div>
</body>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script>
    jQuery(document).bind("keyup keydown", function(e) {
        if (e.ctrlKey && e.keyCode == 80) {
            window.print();
            return false;
        }
    });
    window.print();

</script>