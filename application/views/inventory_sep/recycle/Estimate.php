<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-navy">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-navy">Cancelled Estimates Invoices</h4>
                            </div>
                            <a href="<?= base_url(); ?>Estimate" class="btn bg-navy btn-sm float-right">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Invoice No</th>
                                            <th class="text-white">Invoice Date</th>
                                            <th class="text-white">Party Name</th>
                                            <th class="text-white">Mobile</th>
                                            <th class="text-white">GST No</th>
                                            <th class="text-white">Taxable Amount</th>
                                            <th class="text-white">Tax</th>
                                            <th class="text-white">Total</th>
                                            <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Sr = 0;
                                            $TaxAble = 0;
                                            $Tax = 0;
                                            $Total = 0;
                                            if (!empty($result)) {
                                                foreach ($result as $key => $row) {
                                                    $Sr++;
                                                    $rowTaxable = (float) ($row->taxableAmount ?? 0);
                                                    $rowTax = (float) ($row->totalTax ?? 0);
                                                    $rowTotal = (float) ($row->total ?? 0);
                                                    $TaxAble += $rowTaxable;
                                                    $Tax += $rowTax;
                                                    $Total += $rowTotal;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $row->invoiceNumber; ?></td>
                                            <td><?= !empty($row->invoiceDate) ? date('d-m-Y', strtotime($row->invoiceDate)) : ''; ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rowTaxable); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rowTax); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rowTotal); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="return store('<?= base_url(); ?>Recycle/EstimateStore/<?= $row->id; ?>');" class="text-success" title="Restore">
                                                    <i class="fas fa-store"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" onclick="return deleteRow('<?= base_url(); ?>Estimate/estimate_deletes/<?= $row->id; ?>');" class="text-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TaxAble); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Tax); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total); ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-center font-italic">
                                NOTE : 
                                <span class="text-danger">
                                    Permanent delete invoice can not be revert.
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.dataTable').DataTable();
    });

    function deleteRow(url) {
        Swal.fire({
            title: "Are you sure For Permanent Delete?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                removeRow(url);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }

    function removeRow(url) {
        $.ajax({
            type    :   "GET",
            url     :   url,
            success :   function (Response) {
                            var success = JSON.parse(Response);
                            if (success.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: success.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: success.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    }

    function store(url) {
        Swal.fire({
            title: "Are you sure for Storing ?",
            text: "Same invoice number will be removed",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Store it!",
        }).then((result) => {
            if (result.isConfirmed) {
                stored(url);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }

    function stored(url) {
        $.ajax({
            type    :   "GET",
            url     :   url,
            success :   function (Response) {
                            var success = JSON.parse(Response);
                            if (success.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: success.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: success.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    }
</script>