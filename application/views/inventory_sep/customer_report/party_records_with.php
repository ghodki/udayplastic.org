<html>

<head>
    <style data-merge-styles="true"></style>
    <title>Customer PAYMENT STATMENT </title>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favicon.ico">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: sans-serif;
            margin: 0px 10px;
            font-size: 14px;
        }

        table {
            border-spacing: 0;
        }

        thead {
            display: table-header-group !important;
        }

        tfoot {
            display: table-row-group !important;
        }

        tr {
            page-break-inside: avoid !important;
        }

        .header {
            padding: 5px 10px;
            font-size: 16px;
            clear: both;
            overflow: auto;
            text-align: center;
        }

        table {
            width: 100%;
            font-size: 15px;
            table-layout: fixed;
            word-break: break-all;
        }

        table thead th {
            border-top: 1px solid;
        }

        td,
        th {
            min-width: 150px;
        }

        table th {
            font-family: 'ArialMt Bold';
            font-weight: bold;
        }

        table th:first-child,
        table td:first-child {
            border-left: 1px solid;
            width: 40px;
            text-align: center;
            vertical-align: top;
        }

        table th,
        table td {
            border-right: 1px solid;
            border-bottom: 1px solid;
            page-break-inside: avoid;
            padding: 1px 2px;
        }

        table td {
            vertical-align: bottom;
        }

        .date {
            width: 80px;
        }

        .title,
        .customer {
            width: 150px;
        }

        .amount {
            text-align: right;
            width: 100px;
        }

        .days {
            text-align: right;
            width: 60px;
        }

        .payment_mode {
            width: 110px;
        }

        .remarks {
            width: 150px;
            white-space: pre-wrap;
            ;
        }
    </style>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
    <script type="text/javascript" src=" https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
        jQuery(document).bind("keyup keydown", function(e) {
            if (e.ctrlKey && e.keyCode == 80) {
                window.print();
                return false;
            }
        });

        $(document).ready(function() {
            $('.datatable').DataTable({
                "order": [6, "desc"],
                searching: false,
                paging: false,
                info: false,

            });
        });
    </script>

</head>

<body>
    <?php
    $comp_id = $this->session->userdata('comp_id');
    $user_id = $this->session->userdata('user_id');
    $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
    $comp_profile = $this->user_model->getRow('comp_profile', $where);
    $inventory_billing = $this->user_model->getRow('inventory_billing', ['id' => $comp_id, 'user_id' => $user_id]);

    ?>
    <div class="header">
        <div style="font-size:20px;font-family:'ArialMt Bold'"><?= $comp_profile->name ?></div>
        <div style="white-space: pre-wrap;"><?= $inventory_billing->address ?></div>
        <div>GSTIN : <?= $this->session->userdata('gst_nos') ?></div>
        <div>Contact : <?= $inventory_billing->number ?></div>
        <div>E-mail : <?= $inventory_billing->email ?></div>
    </div>
    <div style="margin:10px;font-size:18px;text-align:center;">Customer PAYMENT STATMENT <span style="font-family:'ArialMt Bold'"><?= date('d-m-Y', strtotime($start_date)) ?></span> TO <span style="font-family:'ArialMt Bold'"><?= date('d-m-Y', strtotime($end_date)) ?></span></div>

    <div style="float:left;margin-bottom:100px;">
        <div style="font-family:'ArialMt Bold'"><?= $party->name ?></div>
        <div style="white-space: pre-wrap;"> <?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?> </div>
        <?php
        if ($party->gst) {
        ?>
            <div>GSTIN : <?= $party->gst ?></div>
        <?php
        }
        ?>
        <?php
        if ($party->contact) {
        ?>
            <div>Contact : <?= $party->contact ?></div>
        <?php
        }
        ?>
        <?php
        if ($party->email) {
        ?>
            <div>E-mail :<?= $party->email ?> </div>

        <?php
        }
        ?>
    </div>

    <?php

    $start_year = date('Y', strtotime($start_date));
    $finance_year = $start_year . '-04-01';
    $starting_year = get_title('year', ['id' => $this->session->userdata('financial_year')], 'starting_year');
    $starting_year = date('Y-m-d', strtotime($starting_year));

    $this->db->select_sum('amount');
    $this->db->from('payment');
    $this->db->where(['date<' => $start_date]);
    $this->db->where('partyID', $party->id);
    $this->db->where('paymentReceipt', 'Reciept');
    $this->db->group_by('paymentReceipt');
    $paymentReceipt = $this->db->get()->row()->amount;
    $opening_balance = 0;


    $this->db->select_sum('amount');
    $this->db->from('payment');
    $this->db->where(['date<' => $start_date]);
    $this->db->where('partyID', $party->id);
    $this->db->where('paymentReceipt', 'Payment');
    $this->db->group_by('paymentReceipt');
    $paymentSend = $this->db->get()->row()->amount;
    // echo '<pre>';
    // print_r($finance_year);
    // echo '<pre>';

    // print_r($starting_year);

    // die;



    $this->db->select_sum('total');
    $this->db->from('sales');
    $this->db->where(['invoiceDate <' => $start_date]);
    $this->db->where('customerId', $party->id);
    $this->db->where('status', 1);
    $salesAmount = $this->db->get()->row()->total;

    $this->db->select('total');
    $this->db->from('purchases');
    $this->db->where(['invoiceDate <' => $start_date]);
    $this->db->where('customerId', $party->id);
    $this->db->where('status', 1);
    $purchaseAmount = $this->db->get()->row()->total;

    $opening_balance_cr = 0;
    $opening_balance_dbt = 0;
    $opBalnceType = get_title('parties', ['id' => $party->id], 'opBalnceType');
    if ($opBalnceType == 'CR') {
        number_format($opening_balance_cr = get_title('parties', ['id' => $party->id], 'opening_balance'), 2);
    } else {
        number_format($opening_balance_dbt = get_title('parties', ['id' => $party->id], 'opening_balance'), 2);
    }
    $opening_balance = 0;

    $opening_balance = ($salesAmount + $paymentSend + $opening_balance_dbt) - ($purchaseAmount + $paymentReceipt + $opening_balance_cr);
        
        if ($opening_balance > 0) {
            $paymentMode = 'DBT';
        } else {
            $paymentMode = 'CR';
        }
    ?>



    <table border="0" cellspacing="0" class="datatable" style="margin-top:100px;">
        <thead>
            <tr>

                <!-- <th class="slno">Sl No</th> -->

                <th class="date">Date</th>


                <th class="date">Invoice No</th>

                <!-- <th class="title">For</th> -->

                <th class="payment_mode">Mode</th>
                <!-- <th class="remarks">Invoice Number</th> -->

                <th class="remarks">Remarks</th>

                <th class="amount">Debit</th>

                <th class="amount">Credit</th>

                <th class="days">Days</th>

            </tr>

        </thead>
        <tbody>
            <tr>
                <!-- <td class="slno"><?= 1 ?></td> -->
                <!--<td></td>-->
                <td class="date" data-sort="<?= date('d-m-Y', strtotime($start_date)) ?>"><?= date('d-m-Y', strtotime($start_date)) ?></td>



                <!-- <td><?= $res->remark ?></td> -->
                <td colspan="3" style="text-align: right;"><?= "Opening Balance" ?></td>
                <?php


                ?>
                <td class="amount"><?php
                                    if ($paymentMode == 'DBT') {
                                        echo number_format($opening_balance, 2);
                                    } else {
                                        echo 0;
                                    }
                                    ?></td>
                <td class="amount">
                    <?php
                    if ($paymentMode == 'CR') {
                        echo number_format($opening_balance, 2);
                    } else {
                        echo 0;
                    }
                    ?></td>



                <!-- <td class="amount"></td> -->
                <td class="days">
                    <?php
                    $current_date = date('d-m-Y');
                    $dt1 = strtotime($current_date); //Enter your first date
                    $dt2 = strtotime($start_date); //Enter your second date
                    $day = abs(($dt1 - $dt2) / (60 * 60 * 24));
                    $total_days += $day;
                    echo $day;
                    ?>

                </td>
                <!--<td></td>-->

            </tr>
            <?php
            // $this->db->select('*');
            // $this->db->from('payment');
            // $this->db->where(['date >=' => $start_date]);
            // $this->db->where(['date <=' => $end_date]);
            // $this->db->where('partyID', $party->id);
            // $result = $this->db->get()->result();

            $this->db->select('*');
            $this->db->from('payment');
            $this->db->where(['date >=' => $start_date]);
            $this->db->where(['date <=' => $end_date]);
            $this->db->where('partyID', $party->id);
            $payment_result = $this->db->get()->result();



            $this->db->select('*');
            $this->db->from('sales');
            $this->db->where(['invoiceDate >=' => $start_date]);
            $this->db->where(['invoiceDate <=' => $end_date]);
            $this->db->where('customerId', $party->id);
            $this->db->where('status', 1);
            $sales_result = $this->db->get()->result();

            // echo '<pre>';
            // print_r($sales_result);
            // die;

            $this->db->select('*');
            $this->db->from('purchases');
            $this->db->where(['invoiceDate >=' => $start_date]);
            $this->db->where(['invoiceDate <=' => $end_date]);
            $this->db->where('customerId', $party->id);
            $this->db->where('status', 1);
            $purchase_result = $this->db->get()->result();




            $total_credit = 0;
            $total_debit = 0;
            $total_days = 0;
            $key = 0;
            if ($payment_result) {
                foreach ($payment_result as  $res) {

            ?>
                    <tr>
                        <!-- <td class="slno"><?= $key + 2 ?></td> -->
                        <td style="border-left:1px solid;" class="date" data-sort="<?= date('d-m-Y', strtotime($res->date)) ?>"><?= date('d-m-Y', strtotime($res->date)) ?></td>

                        <td class="date"></td>

                        <td>
                            <?= get_title('payment_mode', ['id' => $res->paymentmode], 'payment_method'); ?>
                        </td>
                        <!-- <td><?= $res->remark ?></td> -->
                        <td><?= $res->remark ?></td>
                        <?php
                        if ($res->paymentReceipt == 'Reciept') {
                            $total_credit += $res->amount;
                            $credit_amount = $res->amount;
                            $debit_amount = 0;
                        } else {
                            $total_debit += $res->amount;
                            $debit_amount = $res->amount;
                            $credit_amount = 0;
                        }

                        ?>
                        <td class="amount"><?= number_format($debit_amount, 2) ?></td>
                        <td class="amount"><?= number_format($credit_amount, 2) ?></td>
                        <td class="days">
                            <?php
                            $current_date = date('d-m-Y');
                            $dt1 = strtotime($current_date); //Enter your first date
                            $dt2 = strtotime($res->date); //Enter your second date
                            $day = abs(($dt1 - $dt2) / (60 * 60 * 24));
                            $total_days += $day;
                            echo $day;
                            ?>

                        </td>

                    </tr>
                <?php
                    ++$key;
                }
            }

            if ($sales_result) {
                foreach ($sales_result as  $res) {

                ?>
                    <tr>
                        <!-- <td class="slno"><?= $key + 2 ?></td> -->
                        <td style="border-left:1px solid;" class="date" data-sort="<?= date('d-m-Y', strtotime($res->invoiceDate)) ?>"><?= date('d-m-Y', strtotime($res->invoiceDate)) ?></td>

                        <td class="date"><?= $res->invoiceNumber ?></td>

                        <td>
                            <?php
                            if ($res->voucherType == 1) {
                                echo "Cash";
                            } elseif ($res->voucherType == 2) {
                                echo "Credit";
                            } else {
                                echo $res->voucherType;
                            }
                            ?>
                        </td>
                        <!-- <td><?= $res->remark ?></td> -->
                        <td><?= $res->remark ?></td>
                        <?php

                        $total_debit += $res->total;
                        $debit_amount = $res->total;
                        $credit_amount = 0;

                        ?>
                        <td class="amount"><?= number_format($debit_amount, 2) ?></td>
                        <td class="amount"><?= number_format($credit_amount, 2) ?></td>
                        <td class="days">
                            <?php
                            $current_date = date('d-m-Y');
                            $dt1 = strtotime($current_date); //Enter your first date
                            $dt2 = strtotime($res->invoiceDate); //Enter your second date
                            $day = abs(($dt1 - $dt2) / (60 * 60 * 24));
                            $total_days += $day;
                            echo $day;
                            ?>

                        </td>

                    </tr>
                <?php
                    ++$key;
                }
            }


            if ($purchase_result) {
                foreach ($purchase_result as  $res) {

                ?>
                    <tr>
                        <!-- <td class="slno"><?= $key + 2 ?></td> -->
                        <td style="border-left:1px solid;" class="date"><?= date('d-m-Y', strtotime($res->invoiceDate)) ?></td>
                        <td class="date"><?= $res->invoiceNumber ?></td>

                        <td>
                            <?php
                            if ($res->voucherType == 1) {
                                echo "Cash";
                            } elseif ($res->voucherType == 2) {
                                echo "Credit";
                            } else {
                                echo $res->voucherType;
                            }
                            ?>
                        </td>
                        <!-- <td><?= $res->remark ?></td> -->
                        <td><?= $res->remark ?></td>
                        <?php

                        $total_credit += $res->total;
                        $credit_amount = $res->total;
                        $debit_amount = 0;

                        ?>
                        <td class="amount"><?= number_format($debit_amount, 2) ?></td>
                        <td class="amount"><?= number_format($credit_amount, 2) ?></td>
                        <td class="days">
                            <?php
                            $current_date = date('d-m-Y');
                            $dt1 = strtotime($current_date); //Enter your first date
                            $dt2 = strtotime($res->invoiceDate); //Enter your second date
                            $day = abs(($dt1 - $dt2) / (60 * 60 * 24));
                            $total_days += $day;
                            echo $day;
                            ?>

                        </td>

                    </tr>
            <?php
                    ++$key;
                }
            }


            ?>







        </tbody>
        <tfoot>

            <tr>

                <th style="border-left:1px solid; text-align: right;" colspan="4">Sub Total</th>
                <?php
                if ($paymentMode == 'DBT') {
                    $total_debit = $opening_balance + $total_debit;
                }
                if ($paymentMode == 'CR') {
                    $total_credit = $opening_balance + $total_credit;
                }
                ?>
                <th class="amount"><?= number_format($total_debit, 2) ?></th>

                <th class="amount"><?= number_format($total_credit, 2) ?></th>

                <th class="days"></th>

            </tr>

            <tr>

                <td style="border-left:1px solid; text-align: right;" colspan="4">Closing Balance</td>

                <td class="amount"><?= number_format($total_debit - $total_credit, 2) ?></td>

                <!-- <td class="amount"><?= $total_credit ?></td> -->

                <th class="days" colspan="2"></th>

            </tr>

            <tr>

                <th style="border-left:1px solid; text-align: right;" colspan="4">Total</th>

                <th class="amount"><?= number_format($total_debit, 2) ?></th>

                <th class="amount"><?= number_format($total_credit, 2) ?></th>

                <th class="days"></th>

            </tr>

        </tfoot>
    </table>

</body>


</html>