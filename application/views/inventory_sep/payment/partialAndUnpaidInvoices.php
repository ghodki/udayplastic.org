<style type="text/css">
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
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="card-title">Partial & Unpaid Invoices</h4>
                            </div>
                            <a href="<?= base_url(); ?>payment" class="btn btn-info btn-xs pull-right">
                                <i class="fa fa-inr"></i> Payment
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" id="StatementForm" class="mb-1">
                                <div class="row">
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="account_group">Account Group</label>
                                        <select name="account_group" id="account_group" class="form-control form-control-sm" title="Account Group" onchange="GetParties(this.value);" required="">
                                            <option selected="" disabled="" value="" >Select Account Group</option>
                                            <?php if (!empty($account_group)) { foreach ($account_group as $key => $account_id) { ?>
                                            <option <?= $this->input->get('account_group') == $account_id->id ? "selected" : ""; ?> value="<?= $account_id->id; ?>"><?= $account_id->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-3">
                                        <label for="party">Parties</label>
                                        <select name="party" id="party" class="form-control form-control-sm select2" title="Select Party Name" required="">
                                            <option value="" selected="" disabled="">Select Party</option>
                                            <?php 
                                                if (!empty($this->input->get('account_group')) && !empty($this->input->get('party'))) {
                                                    if ($this->session->userdata('RoleType')) {
                                                        $EmployeeId = $this->session->userdata('EmployeeId');
                                                        $UserId = $this->session->userdata('UserId');
                                                        $CompId = $this->session->userdata('CompId');
                                                        $SalesmanId = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'), true);
                                                    } else {
                                                        $UserId = $this->session->userdata('user_id');
                                                        $CompId = $this->session->userdata('comp_id');
                                                    }
                                                    $CompBranchId = $this->session->userdata('CompBranchId');

                                                    $Where = ['group_head' => $this->input->get('account_group'), 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $Where['CompanyBranchId'] = $CompBranchId;
                                                    }

                                                    $this->db->select('id, name, area, contact');
                                                    $this->db->where($Where);
                                                    if ($this->session->userdata('RoleType')) {
                                                        $this->db->where_in('SalesmanId', $SalesmanId);
                                                    }
                                                    $this->db->order_by('name', 'ASC');
                                                    $AccountGroupParties = $this->db->get('parties')->result();
                                                    foreach ($AccountGroupParties as $key => $PartyData) {
                                            ?>
                                            <option <?= $PartyData->id == $this->input->get('party') ? "selected" : ""; ?> value="<?= $PartyData->id; ?>"><?= $PartyData->name; ?>, <?= $PartyData->area; ?>, <?= $PartyData->contact; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1">
                                        <label>Estimate</label>
                                        <br />
                                        <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                        Estimate
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="toDate">To Date</label>
                                        <input type="date" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Payment/PartialUnpaidInvoices" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed dataTable">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Sr = 0;
                                            $Total = 0;
                                            $Paid = 0;
                                            $Balance = 0;
                                            if (!empty($InvoiceData)) {
                                                foreach ($InvoiceData as $key => $data) {
                                                    $Sr++;
                                                    $Total += (float) $data->total;
                                                    $Paid += (float) $data->paid_amount;
                                                    $Balance += ((float) $data->total - (float) $data->paid_amount);

                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$data->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, ChequeStatus')->where('invoice_id', $data->id)->where('transaction_type', 'DR')->get('payment')->row();
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                            <td>
                                                <?php if ($data->type == "Sale") { ?>
                                                <a href="<?= base_url(); ?>Sale/viewsale/<?= $data->id; ?>" title='View Invoice'>
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($data->type == "Purchase") { ?>
                                                <a href="<?= base_url(); ?>Purchase/viewpurchase/<?= $data->id; ?>" title='View Invoice'>
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td><?= $data->customerName; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->total ?? 0), 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->paid_amount ?? 0), 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->total ?? 0) - (float) ($data->paid_amount ?? 0), 2); ?></td>
                                            <td>
                                                <?php if ($data->paid_amount == $data->total) { if (!empty($PaymentInfo) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $data->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } else { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } elseif ($data->paid_amount == NULL || $data->paid_amount == 0) { ?>
                                                <a href="<?= base_url(); ?><?= $data->type == "Sale" ? 'receipts' : 'payment'; ?>/index/<?= $data->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle text-white"></i> Unpaid
                                                </a>
                                                <?php } else { if (!empty($PaymentInfo) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $data->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <a href="<?= base_url(); ?><?= $data->type == "Sale" ? 'receipts' : 'payment'; ?>/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?><?= $data->type == "Sale" ? 'receipts' : 'payment'; ?>/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Total ?? 0, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Paid ?? 0, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format(($Total ?? 0) - ($Paid ?? 0), 2); ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $CustometId = $this->input->get('party');
                                            $fromDate = $this->input->get('fromDate');
                                            $toDate = $this->input->get('toDate');
                                            $UserId = $this->session->userdata('user_id');
                                            $CompId = $this->session->userdata('comp_id');
                                            $CompBranchId = $this->session->userdata('CompBranchId');        

                                            $CommonWhere = ['invoiceDate >= ' => $fromDate, 'invoiceDate <= ' => $toDate, 'customerId' => $CustometId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                                            if ($this->session->userdata('BranchRole')) {
                                                $CommonWhere['CompanyBranchId'] = $CompBranchId;
                                            }
                                            
                                            if ($this->input->get('account_group') == 5) {
                                                $this->db->from('sales');
                                                $this->db->select('id, invoiceDate, invoiceNumber, customerName, type, total, paid_amount');
                                                $this->db->where($CommonWhere);
                                                $this->db->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end();
                                                $Query1 = $this->db->get_compiled_select();
                                                
                                                $this->db->from('estimates');
                                                $this->db->select('id, invoiceDate, invoiceNumber, customerName, type, total, paid_amount');
                                                $this->db->where($CommonWhere);
                                                $this->db->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end();
                                                $Query2 = $this->db->get_compiled_select();
                                                
                                                $InvoiceData = $this->db->query($Query1 . " UNION " . $Query2 . " ORDER BY id DESC")->result();
                                            } elseif ($this->input->get('account_group') == 6) {
                                                $this->db->from('purchases');
                                                $this->db->select('id, invoiceDate, invoiceNumber, customerName, type, total, paid_amount');
                                                $this->db->where($CommonWhere);
                                                $this->db->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end();
                                                $Query1 = $this->db->get_compiled_select();
                                                
                                                $this->db->from('estimate_purchase');
                                                $this->db->select('id, invoiceDate, invoiceNumber, customerName, type, total, paid_amount');
                                                $this->db->where($CommonWhere);
                                                $this->db->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end();
                                                $Query2 = $this->db->get_compiled_select();
                                                
                                                $InvoiceData = $this->db->query($Query1 . " UNION " . $Query2 . " ORDER BY id DESC")->result();
                                            } else {
                                                $InvoiceData = [];
                                            }

                                            $Sr = 0;
                                            $Total = 0;
                                            $Paid = 0;
                                            $Balance = 0;
                                            if (!empty($InvoiceData)) {
                                                foreach ($InvoiceData as $key => $data) {
                                                    $Sr++;
                                                    $Total += (float) $data->total;
                                                    $Paid += (float) $data->paid_amount;
                                                    $Balance += ((float) $data->total - (float) $data->paid_amount);
                                                    
                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$data->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, ChequeStatus')->where('invoice_id', $data->id)->where('transaction_type', 'DR')->get('payment')->row();
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $data->type; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                            <td>
                                                <?php if ($data->type == "Sale") { ?>
                                                <a href="<?= base_url(); ?>Sale/viewsale/<?= $data->id; ?>" title='View Invoice'>
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($data->type == "Estimate") { ?>
                                                <a href="<?= base_url(); ?>Estimate/viewestimate/<?= $data->id; ?>" title='View Invoice'>
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($data->type == "Purchase") { ?>
                                                <a href="<?= base_url(); ?>Purchase/viewpurchase/<?= $data->id; ?>" title='View Invoice'>
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($data->type == "Estimate Purchase") { ?>
                                                <a href="<?= base_url(); ?>estimate_purchase/view/<?= $data->id; ?>" title='View Invoice'>
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td><?= $data->customerName; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->total ?? 0), 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->paid_amount ?? 0), 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->total ?? 0) - (float) ($data->paid_amount ?? 0), 2); ?></td>
                                            <td>
                                                <?php if ($data->paid_amount == $data->total) { if (!empty($PaymentInfo) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $data->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } else { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } elseif ($data->paid_amount == NULL || $data->paid_amount == 0) { ?>
                                                <a href="<?= base_url(); ?><?= $data->type == "Sale" ? 'receipts' : 'payment'; ?>/index/<?= $data->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle text-white"></i> Unpaid
                                                </a>
                                                <?php } else { if (!empty($PaymentInfo) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $data->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <a href="<?= base_url(); ?><?= $data->type == "Sale" ? 'receipts' : 'payment'; ?>/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?><?= $data->type == "Sale" ? 'receipts' : 'payment'; ?>/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Total ?? 0, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Paid ?? 0, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Balance ?? 0, 2); ?></th>
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
    <!-- /.content-header -->
</div>

<!-- Cheque Info -->
<div class="modal fade" id="ChequeInfo" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
<!-- Cheque Info end -->

<script type="text/javascript">
    const GetParties = (AccountId) => {
        if (AccountId != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>party/getPartiesByAccountGroupForStatement",
                data    :   {account_id: AccountId},
                success :   function (Response) {
                                var PartyData = JSON.parse(Response);
                                if (PartyData.status == true) {
                                    var AllParties = PartyData.data;
                                    var party = `<option value="" selected="" disabled="">Select Party</option>`;
                                    AllParties.forEach((element) => {
                                        party += `<option value="${element.id}">${element.name+", "+element.area+", "+element.contact}</option>`;
                                    });                                    
                                    $("#party").html(party);
                                }
                            }
            });
        }
    };
    
    $(document).ready(function() {
        $('.select2').select2();
    
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: [
                'copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'
            ]
        });
    });
    
    const OnChequeInfo = (SaleId) => {
        $("#ChequeInfo .modal-dialog .modal-content").load("<?=base_url(); ?>Sale/ChequeInfo/" + SaleId);
        $("#ChequeInfo").modal("show");
    };
</script>