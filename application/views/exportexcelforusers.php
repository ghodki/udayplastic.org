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
        <div style="margin: 3em;">
            <h1 style="text-align: center; margin-top: 1em;">Users Report</h1>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%;" border="1">
                <thead>
                    <tr style="background-color: #ccc;">
                        <th style="padding: 3px; font-size: 15px;">Sr.</th>
                        <th style="padding: 3px; font-size: 15px;">User Number</th>
                        <th style="padding: 3px; font-size: 15px;">Name</th>
                        <th style="padding: 3px; font-size: 15px;">Email</th>
                        <th style="padding: 3px; font-size: 15px;">Mobile</th>
                        <th style="padding: 3px; font-size: 15px;">Gender</th>
                        <th style="padding: 3px; font-size: 15px;">User Type</th>
                        <th style="padding: 3px; font-size: 15px;">GST Number</th>
                        <th style="padding: 3px; font-size: 15px;">Joining Date</th>
                        <th style="padding: 3px; font-size: 15px;">Address</th>
                        <th style="padding: 3px; font-size: 15px;">Status</th>
                        <th style="padding: 3px; font-size: 15px;">Registered</th>
                        <th style="padding: 3px; font-size: 15px;">Fees</th>
                        <th style="padding: 3px; font-size: 15px;">Tenure</th>
                        <th style="padding: 3px; font-size: 15px;">Days</th>
                        <th style="padding: 3px; font-size: 15px;">Licensed</th>
                        <th style="padding: 3px; font-size: 15px;">Status</th>
                        <th style="padding: 3px; font-size: 15px;">Expiry</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($allusers)) $n = 0; { foreach ($allusers as $key => $data) { $n++; ?>
                    <tr>
                        <td style="padding: 3px;"><?= $n; ?></td>
                        <td style="padding: 3px;"><?= $data->user_number; ?></td>
                        <td style="padding: 3px;"><?= $data->name; ?></td>
                        <td style="padding: 3px;"><?= $data->email; ?></td>
                        <td style="padding: 3px;"><?= $data->number; ?></td>
                        <td style="padding: 3px;"><?= $data->gender; ?></td>
                        <td style="padding: 3px;"><?= get_title('roles', ['id' => $data->userType], 'full_name'); ?></td>
                        <td style="padding: 3px;"><?= $data->gst; ?></td>
                        <td style="padding: 3px;"><?= date('d-m-Y', strtotime($data->created)); ?></td>
                        <td style="padding: 3px;"><?= $data->address; ?></td>
                        <td style="padding: 3px;"><?= $data->status == 1 ? "Active" : "Inactive"; ?></td>
                        <td style="padding: 3px;"><?= date('d-m-Y', strtotime($data->created)); ?></td>
                        <td style="padding: 3px;"><i class="fa fa-inr"></i> <?= $data->license_cost; ?></td>
                        <td style="padding: 3px;"><?= DifferenceFromDates(date('d-m-Y', strtotime($data->created))); ?></td>
                        <td style="padding: 3px;">
                            <?php 
                                if (DifferenceFromDates($data->expiry) <= 7 && strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($data->expiry)))) {
                                    echo DifferenceFromDates($data->expiry);
                                } elseif (strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($data->expiry)))) {
                                    echo DifferenceFromDates($data->expiry);
                                } else {
                                    echo "-" . DifferenceFromDates($data->expiry);
                                }
                            ?>
                        </td>
                        <td style="padding: 3px;"><?= $data->started; ?></td>
                        <td style="padding: 3px;"><?= strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($data->expiry))) ? "Licensed" : "Expired"; ?></td>
                        <td style="padding: 3px;"><?= $data->expiry; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>