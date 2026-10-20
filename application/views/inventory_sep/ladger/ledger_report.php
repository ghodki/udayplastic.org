<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card shadow" style="margin-top: 50px;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <h2 class="header-title" style="vertical-align: -moz-middle-with-baseline;">
                                        Ledger Report
                                    </h2>
                                    <hr />
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <form method="GET" action="<?= base_url(); ?>invent/ledger_report">
                                        <div class="row">
                                            <div class="form-group col-md-2 col-sm-6">
                                                <label for="bank_account" class="text-dark">Ledger Name:</label>
                                                <select name="ledger" tabindex="4" id="ledger" class="form-control select2">
                                                    <option value="">--Select--</option>
                                                    <?php if ($ladger) { foreach ($ladger as $key => $value) { ?>
                                                    <option value="<?= $value->id; ?>" <?= $this->input->get('ledger') == $value->id ? 'selected' : '' ?>><?= $value->name; ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
<!--                                            <div class="form-group col-md-2 col-sm-6">
                                                <label for="fromDate">From Date</label>
                                                <input type="date" tabindex="3" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" class="form-control" />
                                            </div>
                                            <div class="form-group col-md-2 col-sm-6">
                                                <label for="toDate">To Date</label>
                                                <input type="date" tabindex="4" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" class="form-control" />
                                            </div>-->
                                            <div class="form-group col-md-6 col-sm-6" style="margin-top:30px;">
                                                <button type="submit" class="btn btn-primary" onClick="return search();" onkeydown="firstRow(event);">
                                                    Search
                                                </button>
                                                <button type="button" class="btn btn-danger" tabindex="4">
                                                    Reset
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table multiple="multiple" class="js-example-basic-multiple table align-items-center table-flush dataTable" style="width: 100%;" id="dataTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Party Name</th>
                                            <th>Payment Mode</th>
                                            <th>Payment</th>
                                            <th>Receipt</th>
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="partyDeDo">
                                        <?php
                                            $sum_of_receipt = 0;
                                            $sum_of_payment = 0;
                                            if (isset($payment) && $payment) {
                                                foreach ($payment as  $key => $cat) {
                                                    $date = $cat->date;
                                                    $newDate = date("d-m-Y", strtotime($date));
                                        ?>
                                        <tr>
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td><?= $newDate; ?></td>
                                            <td><?= get_title('parties', ['id' => $cat->partyID], 'name'); ?></td>
                                            <td><?= get_title('payment_mode', ['id' => $cat->paymentmode], 'payment_method'); ?></td>
                                            <?php if ($cat->paymentReceipt == "Payment") { ?>
                                            <td><i class="fa fa-inr"></i> <?= $cat->amount; ?></td>
                                            <?php $sum_of_payment = $sum_of_payment + $cat->amount; } else { ?>
                                            <td><i class="fa fa-inr"></i> 0</td>
                                            <?php } if ($cat->paymentReceipt == "Reciept") { ?>
                                            <td><i class="fa fa-inr"></i> <?= $cat->amount; ?></td>
                                            <?php
                                                $sum_of_receipt = $sum_of_receipt + $cat->amount;
                                                $balance = $sum_of_receipt - $sum_of_payment;
                                                } else {
                                            ?>
                                            <td><i class="fa fa-inr"></i> 0</td>
                                            <?php } ?>
                                            <td><i class="fa fa-inr"></i> <?= $balance = $sum_of_receipt - $sum_of_payment; ?></td>
                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a href="<?= base_url('payment/edit/') . $cat->id ?>" class="dropdown-item cp">
                                                            Edit
                                                        </a> 
                                                        <!-- <a href="<?= base_url('payment/delete/') . $cat->id ?>" class="dropdown-item cp">Delete</a> -->
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>Total: <i class="fa fa-inr"></i> <?= $sum_of_payment; ?></strong></td>
                                            <td><strong>Total: <i class="fa fa-inr"></i> <?= $sum_of_receipt; ?></strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'print',
                'pageLength'
            ]
        });
    });
</script>