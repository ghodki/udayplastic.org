<style>
    @font-face {
        font-family: 'ArialMt';
        src: url('../assets/fonts/Arial MT.ttf') format("truetype");
    }

    body {
        font-family: sans-serif;
        margin: 0px;
    }

    #barcode {
        margin-left: 40px;
    }

    .barcode:nth-child(even) {
        margin-left: 6px;
    }

    .barcode {
        text-align: center;
        float: left;
        display: inline-block;
        font-size: 12px;
        overflow: hidden;
        width: 4.76cm;
        height: 20mm;
        margin-bottom: 0px;
        margin-left: 0px;
        padding-top: 10px;
        position: relative;
    }

    body {
        margin: 0px 12px;
    }

    .barcode img {
        margin-top: -10px;
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
        padding-right: 49px;
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
        font-size: 15px;
        left: -8px;
        text-transform: uppercase;
        font-weight: 600;
    }
</style>
<script type="text/javascript" src="../assets/js/JsBarcode.all.min.js"></script>

<body onload="window.print()" style="font-family: sans-serif;">
    <div id="barcode">

        <?php
        $barcode_title = '';
        $barcode_sell_prefix = '';
        $barcode_mrp_prefix = '';
        $barcode_purchase_prefix = '';

        $item1 = 'product_name';
        $item2 = 'mrp';
        $item3 = 'sell';
        $comp_name = '';
        $company_details = $this->user_model->getRow('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
        if ($company_details) {


            $barcode_title = $company_details->barcode_title;
            $barcode_mrp_prefix = $company_details->barcode_mrp_prefix;
            $barcode_sell_prefix = $company_details->barcode_sell_prefix;
            $barcode_purchase_prefix = $company_details->barcode_purchase_prefix;

            $input_number = $company_details->barcode_pre_row;
            $comp_name = $company_details->name;


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
                                <div class="rotatetxt" style="font-size:12px;"><?= substr($barcode_title,0, 21); ?></div>
                                <div style="font-size:11px;"><b><?= $product->p_name  ?><b></div>
                                <!-- <img id="barcode_0" data="J5419" class="barcode_img" /> -->
                                <!-- <img id="" data="J5418" class="barcode_img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGMAAAAjCAYAAACAY7T5AAAAAXNSR0IArs4c6QAAAS1JREFUaEPtmuEKwjAMhLf3f2iluOgZ0y5jECJ8/nHUrEnvet0uuG982iCwt6mEQjbIaLQJIAMyGiHQqBSUARmNEGhUCsqAjEYINCoFZfwpGY+j7kHguDYi9dqWZmP+HvtdN4GPHTGWQ6HyY1qH5tEabK7xbTF+zM+j69LY2bzR3NH6T2m/ogzIeMG5Il43km6eFM6poINSyICMt7o5puSgQxkoA2VET36UgTJQBsqIX2F5tXVmU43jylx5P4DpWwA5c+s48FOv/i1b2iGfdhDtEOlfzdoOHFPSOvEgRWe27aqZu1bB4sBx4D//oGnRKEw8Vgi5g8CVru2dPNybQAAyEiBVhUBGFdKJPJCRAKkqBDKqkE7kgYwESFUhkFGFdCIPZCRAqgqBjCqkE3mejqlKMyiveh0AAAAASUVORK5CYII="> -->

                                <img class="barcode_img" id="barcode_<?= $row ?>" data="" src="<?php echo base_url() ?>uploads/qr_image/<?php echo $qrcode->img_url; ?>" style="margin-top:1px;">

                                <div style="margin-top: -3px;font-size:10px;">
                                    <?php
                                    $item1_data = $product->p_name;
                                    $item2_data = $barcode_mrp_prefix . ":" . $product->mrp . " /-";
                                    $item3_data = $barcode_sell_prefix . ":" . $product->price . " /-";

                                    if ($item1 == 'product_name') {
                                        $item1_data = $product->p_name;
                                    } elseif ($item1 == 'mrp') {
                                        $item1_data = $barcode_mrp_prefix . ":" . $product->mrp . " /-";
                                    } elseif ($item1 == 'sell') {
                                        $item1_data = $barcode_sell_prefix . ":" . $product->price . " /-";
                                    } elseif ($item1 == 'hsn_code') {
                                        $item1_data = $product->hsn;
                                    } elseif ($item1 == 'product_code') {
                                        $item1_data = $product->qrCode;
                                    } elseif ($item1 == 'purchase') {
                                        $item1_data = $barcode_purchase_prefix . ":" . $product->purchase_rate . " /-";
                                    }

                                    if ($item2 == 'product_name') {
                                        $item2_data = $product->p_name;
                                    } elseif ($item2 == 'mrp') {
                                        $item2_data = $barcode_mrp_prefix . ":" . $product->mrp . " /-";
                                    } elseif ($item2 == 'sell') {
                                        $item2_data = $barcode_sell_prefix . ":" . $product->price . " /-";
                                    } elseif ($item2 == 'hsn_code') {
                                        $item2_data = $product->hsn;
                                    } elseif ($item2 == 'product_code') {
                                        $item2_data = $product->qrCode;
                                    } elseif ($item2 == 'purchase') {
                                        $item2_data = $barcode_purchase_prefix . ":" . $product->purchase_rate . " /-";
                                    }

                                    if ($item3 == 'product_name') {
                                        $item3_data = $product->p_name;
                                    } elseif ($item3 == 'mrp') {
                                        $item3_data = $barcode_mrp_prefix . ":" . $product->mrp . " /-";
                                    } elseif ($item3 == 'sell') {
                                        $item3_data = $barcode_sell_prefix . ":" . $product->price . " /-";
                                    } elseif ($item3 == 'hsn_code') {
                                        $item3_data = $product->hsn;
                                    } elseif ($item3 == 'product_code') {
                                        $item3_data = $product->qrCode;
                                    } elseif ($item3 == 'purchase') {
                                        $item3_data = $barcode_purchase_prefix . ":" . $product->purchase_rate . " /-";
                                    }
                                    ?>
                                    <div style="margin-top:5px;font-size:11px;"><b><?= $item1_data ?></b></div>
                                    <div style="font-size:13px;"><b><?= $item2_data ?></b></div>
                                    <div ><b><?= $item3_data ?></b></div>
                                </div>
                                <?php
                                if ($company_details->price_code == 'mrp_price') {
                                    $array  = array_map('intval', str_split($product->mrp));
                                } else if ($company_details->price_code == 'sell_price') {
                                    $array  = array_map('intval', str_split($product->price));
                                } else {
                                    $array  = array_map('intval', str_split($product->purchase_rate));
                                }

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
                                <div class="iat">(Incl. all taxes)</div>
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
<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
<script>
    jQuery(document).bind("keyup keydown", function(e) {
        if (e.ctrlKey && e.keyCode == 80) {
            window.print();
            return false;
        }
    });
    $(document).ready(function() {
        $(".barcode_img").each(function() {
            JsBarcode(this, $(this).attr("data"), {
                width: 1,
                height: '15',
                background: "transparent",
                displayValue: false
            });
        }).promise().done(function() {
            setTimeout(function() {
                window.print();
            }, 500);

        });

    });
</script>