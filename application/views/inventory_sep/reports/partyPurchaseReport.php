<style type="text/css">
    th, td {
        white-space: nowrap;
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Party Purchase Report</h4>
                        </div>
                        <div class="card-body">
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET" style="padding-bottom: 10px;">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="from">From</label>
                                            <input type="date" name="from" value="<?= !empty($this->input->get('from')) ? $this->input->get('from') : date('Y-m-01'); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="to">To</label>
                                            <input type="date" name="to" value="<?= !empty($this->input->get('to')) ? $this->input->get('to') : date('Y-m-d'); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                        </div>
                                    </div>
                                        <div class="form-group col-md-3 col-sm-3">
                                                <label for="partyId">Party</label>
                                                <select name="partyId" id="partyId" class="form-control form-control-sm">
                                                        <option <?= $this->input->get('partyId') == "All" ? "selected" : ""; ?> value="All">Select All Parties</option>
                                                        <?php if (isset($parties) && !empty($parties)) { foreach ($parties as $key => $party) { ?>
                                                        <option <?= $this->input->get('partyId') == $party->id ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?>, <?= $party->area; ?>, <?= $party->contact; ?></option>
                                                        <?php } } ?>
                                                </select>
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Search
                                            </button>
                                            <a href="<?= base_url(); ?>reports/partyPurchaseReport" class="btn btn-info btn-sm" title="Refresh">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Total Invoice</th>
                                            <th>Purchase day ago</th>
                                            <th>E. Purchase day ago</th>
                                            <th>Total</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $totalInvoice = 0;
                                            $total = 0;
                                            $paidAmount = 0;
                                            $balance = 0;
                                            if (isset($partiesPurchases) && !empty($partiesPurchases)) {
                                                $n = 0;
                                                if ($this->input->get('from') != "" && $this->input->get('to') != "") {
                                                    $from = $this->input->get('from');
                                                    $to = $this->input->get('to');
                                                } else {
                                                    $from = date('Y-m-01');
                                                    $to = date('Y-m-d');
                                                }
                                                $where = ['invoiceDate >= ' => $from, 'invoiceDate <= ' => $to, 'status' => 1];
                                                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");                                                
                                                foreach ($partiesPurchases as $key => $row) {
                                                    $where['customerId'] = $row->id;
                                                    if ($this->db->select('customerId')->where($where)->get('purchases')->num_rows() || $this->db->select('customerId')->where($where)->get('estimate_purchase')->num_rows()) {
                                                        $purchasesQuery = $this->db->select('invoiceDate, customerId, COUNT(customerId) AS purchaseInvoice, SUM(total) AS purchaseTotal, SUM(paid_amount) AS purchasePaidAmount')->where($where)->get('purchases');
                                                        $estimatePurchasesQuery = $this->db->select('customerId, COUNT(customerId) AS estimatePurchaseInvoice, SUM(total) AS estimatePurchaseTotal, SUM(paid_amount) AS estimatePurchasePaidAmount')->where($where)->get('estimate_purchase');
                                                        
                                                        $purchasesData = $purchasesQuery->row();
                                                        $estimatePurchasesData = $estimatePurchasesQuery->row();

                                                        $n++;

                                                        $totalInvoice += ($purchasesData->purchaseInvoice + $estimatePurchasesData->estimatePurchaseInvoice);
                                                        $total += ($purchasesData->purchaseTotal + $estimatePurchasesData->estimatePurchaseTotal);
                                                        $paidAmount += ($purchasesData->purchasePaidAmount + $estimatePurchasesData->EstimatePaidAmount);
                                                        $balance += (($purchasesData->purchaseTotal + $estimatePurchasesData->estimatePurchaseTotal) - ($purchasesData->purchasePaidAmount + $estimatePurchasesData->estimatePurchasePaidAmount));
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $row->name; ?></td>
                                            <td><?= $row->contact; ?></td>
                                            <td><?= $row->gst; ?></td>
                                            <td><?= $purchasesData->purchaseInvoice + $estimatePurchasesData->estimatePurchaseInvoice; ?></td>
                                            <td>
                                                <?php
                                                    $this->db->select('invoiceDate');
                                                    $this->db->where($where);
                                                    $this->db->order_by('invoiceDate', 'DESC');
                                                    $purchasesTime = $this->db->get('purchases')->row();
                                                    echo $purchasesTime->invoiceDate ? DifferenceFromDates($purchasesTime->invoiceDate) : "0.0";
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $this->db->select('invoiceDate');
                                                    $this->db->where($where);
                                                    $this->db->order_by('invoiceDate', 'DESC');
                                                    $estimatePurchasesTime = $this->db->get('estimate_purchase')->row();
                                                    echo $estimatePurchasesTime->invoiceDate ? DifferenceFromDates($estimatePurchasesTime->invoiceDate) : "0.0";
                                                ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($purchasesData->purchaseTotal + $estimatePurchasesData->estimatePurchaseTotal)); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($purchasesData->purchasePaidAmount + $estimatePurchasesData->estimatePurchasePaidAmount)); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat((($purchasesData->purchaseTotal + $estimatePurchasesData->estimatePurchaseTotal) - ($purchasesData->purchasePaidAmount + $estimatePurchasesData->estimatePurchasePaidAmount))); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="getInvoiceInfo(<?= $row->id; ?>);" class="btn btn-primary btn-sm" title="Info">
                                                        <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><?= $totalInvoice; ?></th>
                                            <th colspan="2"></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($paidAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($balance); ?></th>
                                            <th></th>
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

<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content"></div>
        </div>
</div>

<script type="text/javascript">
        const getInvoiceInfo = (partyId) => {
                var from = $("#from").val();
                var to = $("#to").val();                
                $("#info .modal-dialog .modal-content").load("<?= base_url(); ?>reports/partyInvoiceInfo?from=" + from + "&to=" + to + "&partyId=" +  partyId);
                $('#info').modal('show');
        };
        
    $(document).ready(function () {
        $('#partyId').select2();
    });
    
    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>reports/partyPurchaseReport?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };
    
    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_PartyPurchaseReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_PartyPurchaseReport";
        <?php } ?>
            
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            order: [[7, 'desc']],
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: FileName,
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