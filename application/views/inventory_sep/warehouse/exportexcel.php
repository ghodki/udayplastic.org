<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=TIS-620" />
        <title>Uday Plastic</title>
        <style type="text/css">
            td {
                white-space: nowrap;
            }
        </style>
    </head>
    <body style="font-size: 13px; padding: 3em;">
        <br />
        <div style="margin: 3em">
            <h1 style="text-align: center; margin-top:1em">Stock Transfer Report</h1>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%;" border="1">
                <thead>
                    <tr style="background-color: #ccc;">
                        <th style="padding: 3px; font-size: 15px;">Sr.</th>
                        <th style="padding: 3px; font-size: 15px;">Transfer Id</th>
                        <th style="padding: 3px; font-size: 15px;">Transfer Date</th>
                        <th style="padding: 3px; font-size: 15px;">Warehouse Form</th>
                        <th style="padding: 3px; font-size: 15px;">Warehouse To</th>
                        <th style="padding: 3px; font-size: 15px;">Amount in Word</th>
                        <th style="padding: 3px; font-size: 15px;">Total Amount</th>
                        <th style="padding: 3px; font-size: 15px;">Round Off</th>
                        <th style="padding: 3px; font-size: 15px;">User</th>
                        <th style="padding: 3px; font-size: 15px;">Company</th>
                        <th style="padding: 3px; font-size: 15px;">Financial Year</th>
                        <th style="padding: 3px; font-size: 15px;">Created</th>
                        <th style="padding: 3px; font-size: 15px;">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ($result) {
                            $TotalAmount = 0;
                            $RoundOff = 0;
                            $n = 0;
                            foreach ($result as $key => $row) {
                                $TotalAmount += $row->total_amount;
                                $RoundOff += $row->roundoff;
                                $n++;
                    ?>
                    <tr>
                        <td style="padding: 3px;"><?= $n; ?></td>
                        <td style="padding: 3px;"><?= $row->transfer_id; ?></td>
                        <td style="padding: 3px;"><?= date('d-m-Y', strtotime($row->transfer_date)); ?></td>
                        <td style="padding: 3px;" width="300"><?= get_title('warehouse', ['id' => $row->warehouse_form], 'name'); ?> - <?= get_title('warehouse', ['id' => $row->warehouse_form], 'location'); ?></td>
                        <td style="padding: 3px;" width="300"><?= get_title('warehouse', ['id' => $row->warehouse_to], 'name'); ?> - <?= get_title('warehouse', ['id' => $row->warehouse_to], 'location'); ?></td>
                        <td style="padding: 3px;"><?= $row->amount_word; ?></td>
                        <td style="padding: 3px;"><?= $row->total_amount; ?></td>
                        <td style="padding: 3px;"><?= $row->roundoff; ?></td>
                        <td style="padding: 3px;"><?= get_title('users', ['id' => $row->user_id], 'name'); ?></td>
                        <td style="padding: 3px;"><?= get_title('inventory_billing', ['id' => $row->comp_id], 'company_name'); ?></td>
                        <td style="padding: 3px;"><?= get_title('year', ['id' => $row->financial_year], 'year'); ?></td>
                        <td style="padding: 3px;"><?= $row->created; ?></td>
                        <td style="padding: 3px;"><?= $row->updated; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #ccc;">
                        <th colspan="6" style="text-align: right;">Total</th>
                        <th><?= $TotalAmount; ?></th>
                        <th><?= $RoundOff; ?></th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
   </body>
</html>