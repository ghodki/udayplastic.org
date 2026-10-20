<style type="text/css">
    .user-block .username {
        margin-left: 20px;
    }
    th, td {
        white-space: nowrap;
    }
    .card {
        margin-bottom: 0rem;
    }
    .card-footer p {
        margin-bottom: 0rem;
    }
    .card-header, .card-body, .card-footer {
        padding: 5px;
    }
    table.dataTable tfoot th {
        padding: 10px 5px 6px 5px;
    }
    th {
        padding: 5px !important;
    }
    td {
        padding: 5px !important;
        font-size: 14px;
        font-weight: 500;
        vertical-align: middle !important;
    }
    table.dataTable {
        border-collapse: collapse;
    }
    td label {
        margin-bottom: 0px;
    }
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-maroon">
                                    Parties Outstanding
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-3">
                                        <label for="partyId">Parties</label>
                                        <select name="partyId" id="partyId" class="form-control form-control-sm select2" title="Parties">
                                            <option <?= $this->input->get('partyId') == 'All' ? "selected" : ""; ?> value="All" <?= set_select('partyId', 'All'); ?>>All</option>
                                            <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                            <option <?= $this->input->get('partyId') == $party->id ? "selected" : ""; ?> value="<?= $party->id; ?>" <?= set_select('partyId', $party->id); ?>><?= $party->name; ?>, <?= $party->contact; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") { ?>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="salesmanId">Salesman</label>
                                        <select name="salesmanId" id="salesmanId" class="form-control form-control-sm select2" title="Salesman">
                                            <option <?= $this->input->get('salesmanId') == 'All' ? "selected" : ""; ?> value="All" <?= set_select('salesmanId', 'All'); ?>>All</option>
                                            <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $salesman) { ?>
                                            <option <?= $this->input->get('salesmanId') == $salesman->Id ? "selected" : ""; ?> value="<?= $salesman->Id; ?>" <?= set_select('salesmanId', $salesman->Id); ?>><?= $salesman->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group col-md-1 col-sm-1">
                                        <label>Estimate</label>
                                        <br />
                                        <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                        Estimate
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="fromDate">From</label>
                                            <input type="date" name="fromDate" value="<?= !empty($this->input->get('fromDate')) ? $this->input->get('fromDate') : date('Y-m-d'); ?>" id="fromDate" class="form-control form-control-sm" title="From Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="toDate">To</label>
                                            <input type="date" name="toDate" value="<?= !empty($this->input->get('toDate')) ? $this->input->get('toDate') : date('Y-m-d'); ?>" id="toDate" class="form-control form-control-sm" title="To Date" />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Employee/PartiesOutstanding" class="btn btn-info btn-sm" title="Refresh">
                                            <i class="fa fa-refresh text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive pt-2">
                                <table class="table table-bordered dataTable">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Total Invoice</th>
                                            <th>Sale day ago</th>
                                            <th>Total</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Salesman</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $totalInvoice = 0;
                                            $total = 0;
                                            $paidAmount = 0;
                                            $balance = 0;
                                            if ($partiesSales) {
                                                $srNo = 0;
                                                foreach ($partiesSales as $key => $row) {
                                                    $srNo++;
                                                    $totalInvoice += $row->saleInvoice;
                                                    $total += $row->saleTotal;
                                                    $paidAmount += $row->salePaidAmount;
                                                    $balance += ($row->saleTotal - $row->salePaidAmount);

                                                    $partyInfo = $this->db->select('name, contact, gst')->where('id', $row->customerId)->get('parties')->row();
                                                    if (empty($partyInfo)) {
                                                        $partyInfo = (object) ['name' => '', 'contact' => '', 'gst' => ''];
                                                    }
                                        ?>
                                        <tr>
                                            <td><?= $srNo; ?></td>
                                            <td><?= $partyInfo->name; ?></td>
                                            <td><?= $partyInfo->contact; ?></td>
                                            <td><?= $partyInfo->gst; ?></td>
                                            <td><?= $row->saleInvoice; ?></td>
                                            <td><?= $row->invoiceDate ? DifferenceFromDates($row->invoiceDate) : "0.0"; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->saleTotal); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->salePaidAmount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($row->saleTotal - $row->salePaidAmount)); ?></td>
                                            <td><?= get_title('employees', ['Id' => $row->SalesmanId], 'Name'); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="onPaymentFollowUpChatHandler(<?= $row->customerId; ?>);" title="Payment Follow Up Chat">
                                                    <i class="fa fa-comment text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><?= $totalInvoice; ?></th>
                                            <th></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($paidAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($balance); ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Type</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Total Invoice</th>
                                            <th>Sale day ago</th>
                                            <th>Total</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Salesman</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $totalInvoice = 0;
                                            $total = 0;
                                            $paidAmount = 0;
                                            $balance = 0;
                                            if ($partiesSales) {
                                                $srNo = 0;
                                                foreach ($partiesSales as $key => $row) {
                                                    $srNo++;
                                                    $totalInvoice += $row->saleInvoice;
                                                    $total += $row->saleTotal;
                                                    $paidAmount += $row->salePaidAmount;
                                                    $balance += ($row->saleTotal - $row->salePaidAmount);

                                                    $partyInfo = $this->db->select('name, contact, gst')->where('id', $row->customerId)->get('parties')->row();
                                                    if (empty($partyInfo)) {
                                                        $partyInfo = (object) ['name' => '', 'contact' => '', 'gst' => ''];
                                                    }
                                        ?>
                                        <tr>
                                            <td><?= $srNo; ?></td>
                                            <td><?= $row->type; ?></td>
                                            <td><?= $partyInfo->name; ?></td>
                                            <td><?= $partyInfo->contact; ?></td>
                                            <td><?= $partyInfo->gst; ?></td>
                                            <td><?= $row->saleInvoice; ?></td>
                                            <td><?= $row->invoiceDate ? DifferenceFromDates($row->invoiceDate) : "0.0"; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->saleTotal); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->salePaidAmount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($row->saleTotal - $row->salePaidAmount)); ?></td>
                                            <td><?= get_title('employees', ['Id' => $row->SalesmanId], 'Name'); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="onPaymentFollowUpChatHandler(<?= $row->customerId; ?>);" title="Payment Follow Up Chat">
                                                    <i class="fa fa-comment text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th><?= $totalInvoice; ?></th>
                                            <th></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($paidAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($balance); ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>                                    
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="PaymentFollowUpChat" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    const onPaymentFollowUpChatHandler = (Id) => {
        jQuery.noConflict();
        $("#PaymentFollowUpChat .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/PaymentFollowUpChatHandler/" + Id);
        $("#PaymentFollowUpChat").modal("show");
        getPaymentFollowUpChat(Id);
    };
    
    const onChatFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var Chat = $("#Chat").val();
        
        if (Chat) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/PaymentFollowUpChatSave",
                data    :   $("#ChatForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    toastr.success(ResponseData.Message);
                                    window.setTimeout(function() {
                                        $("#Chat").val("");
                                        
                                        getPaymentFollowUpChat($("#CustomerId").val());
                                        $("#submitButton").prop("disabled", false);
                                    }, 1000);
                                } else {
                                    toastr.error(ResponseData.Message);
                                    $("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            toastr.error("Please enter required fields.");
            $("#submitButton").prop("disabled", false);
        }
    };
    
    const getPaymentFollowUpChat = (CustomerId) => {
        if (CustomerId != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/getPaymentFollowUpChat",
                data    :   {CustomerId: CustomerId},
                success :   function (Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status == true) {
                                    $(".card-comments").html(ResponseData.Chat);
                                } else {
                                    $(".card-comments").html('<span class="text-danger text-center">No Record found.</span>');
                                }
                            }
            });
        }
    };
    
    $(document).ready(function() {
        $('.select2').select2();
    });
    
    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time(); ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pageLength',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });
    });
</script>