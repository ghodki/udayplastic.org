<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />        
        <style type="text/css">
            body {
                font-family: sans-serif;
                margin: 0px 12px;
            }
            #barcode {
                margin-left: 0px;
            }
            .barcode:nth-child(even) {
                margin-left: 6px;
            }
            .barcode {
                text-align: center;
                display: inline-block;
                font-size: 12px;
                overflow: hidden;
                width: 9cm;
                height: 4.8cm;
                margin-bottom: 10px;
                margin-left: 0px;
                padding-top: 10px;
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
            }
        </style>
    </head>
    <body>
        <div id="barcode">
            <?php
                if (isset($stockManufacturerProducts) && !empty($stockManufacturerProducts)) {
                    $count = 0;
                    $where = ['userId' => $this->session->userdata('user_id'), 'compId' => $this->session->userdata('comp_id'), 'voucherId' => $stockManufacturerId, 'status' => 0, 'isDeleted' => 1];
                    foreach ($stockManufacturerProducts as $productData) {
                        $where['productId'] = $productData->ProductId;
                        $productsBarcodeQuery = $this->db->select('barcode, qrImage, productId')->where($where)->order_by('id', 'ASC')->get('products_barcode');
                        if ($productsBarcodeQuery->num_rows()) {
                            foreach ($productsBarcodeQuery->result() as $data) {
            ?>
            <div class="barcode">
                <div style="font-size: 25px; margin-bottom: 5px;">
                    <b><?= $companyData->company_name; ?></b>
                </div>
                <img class="barcode_img" id="barcode" src="<?= base_url(); ?>uploads/barcodeImage/<?= $data->qrImage; ?>" style="width: 100%; height: 90px;" />
                <div style="margin-top: 5px; font-size: 15px;">
                    <b><?= $data->barcode; ?></b>
                </div>    
                <div style="margin-top: 10px; font-size: 18px;">
                    <b><?= get_title('product', ['id' => $data->productId], 'p_name'); ?></b>
                </div>    
            </div>
            <?php $count++; if ($count % 2 == 0) { ?>
            <div class="clear"></div>
            <?php } } } } } ?>
        </div>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script> 
        <script type="text/javascript">
            $(document).ready(function() {
                setTimeout(function() {
                    window.print();
                }, 1000);
            });
        </script>
    </body>
</html>