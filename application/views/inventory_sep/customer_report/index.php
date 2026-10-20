<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Customer Report</h5>
                            </div>
                            <div class="pull-right">
                                <a href="<?= base_url(); ?>sale" class="btn btn-info btn-sm" title="Sale">
                                    <i class="fa fa-shopping-bag"></i> Sale
                                </a>
                                &nbsp;
                                <a href="<?= base_url(); ?>purchase" class="btn btn-primary btn-sm" title="Purchase">
                                    <i class="fa fa-shopping-cart"></i> Purchase
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Customer Name</th>
                                            <th>GSTIN</th>
                                            <th>Email</th>
                                            <th>Mobile Number</th>
                                            <th class="text-danger">Debit</th>
                                            <th class="text-primary">Credit</th>
                                            <th class="text-success">Balance</th>
                                            <th class="text-warning">Opening Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($this->session->userdata('RoleType')) {
                                                $UserId = $this->session->userdata('UserId');
                                                $CompId = $this->session->userdata('CompId');
                                            } else {
                                                $UserId = $this->session->userdata('user_id');
                                                $CompId = $this->session->userdata('comp_id');
                                            }
                                            $CompanyBranchId = $this->session->userdata('CompBranchId');

                                            $CommonWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                                            if ($this->session->userdata('BranchRole')) {
                                                $CommonWhere['CompanyBranchId'] = $CompanyBranchId;
                                            }
                                            $Parties = $this->db->where($CommonWhere)->get('parties')->result();
                                            
                                            $TotalAmount = 0;
                                            $TotalTax = 0;
                                            $TotalTaxAbleAmount = 0;
                                            if ($Parties) {
                                                $Sr = 0;
                                                foreach ($Parties as $key => $PartyData) {
                                                    $Sr++;
                                                    $this->db->select_sum('total');
                                                    $this->db->where($CommonWhere);
                                                    $this->db->where('customerId', $PartyData->id);
                                                    $this->db->where('status',1);
                                                    $sales_result = $this->db->get('sales')->row();

                                                    $this->db->select_sum('total');
                                                    $this->db->where($CommonWhere);
                                                    $this->db->where('customerId', $PartyData->id);
                                                    $this->db->where('status',1);
                                                    $purchase_result = $this->db->get('purchases')->row();

                                                    $this->db->select_sum('credit');
                                                    $this->db->where($CommonWhere);
                                                    $this->db->where('payment_type', "Receipt");
                                                    $this->db->where('party_id', $PartyData->id);
                                                    $crditAmount = $this->db->get('payment')->row();

                                                    $this->db->select_sum('credit');
                                                    $this->db->where($CommonWhere);
                                                    $this->db->where('payment_type', "Payment");
                                                    $this->db->where('party_id', $PartyData->id);
                                                    $debitAmount = $this->db->get('payment')->row();
                                                    
                                                    $opening_balance_cr = 0;
                                                    $opening_balance_dbt = 0;
                                                    $opBalnceType = get_title('parties', ['id' => $PartyData->id], 'opBalnceType');
                                                    if ($opBalnceType == 'CR') {
                                                        number_format($opening_balance_cr = get_title('parties', ['id' => $PartyData->id], 'opening_balance'), 2);
                                                    } else {
                                                        number_format($opening_balance_dbt = get_title('parties', ['id' => $PartyData->id], 'opening_balance'), 2);
                                                    }
                                                    
                                                    $bal = ($sales_result->total + $debitAmount->credit + $opening_balance_dbt) - ($purchase_result->total + $crditAmount->credit + $opening_balance_cr);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>party/party_records/<?= $PartyData->id; ?>">
                                                    <?= $PartyData->name; ?>
                                                </a>
                                            </td>
                                            <td><?= $PartyData->gst; ?></td>
                                            <td><?= $PartyData->email; ?></td>
                                            <td><?= $PartyData->contact; ?></td>
                                            <td style="text-align:center;">
                                                <?= $sales_result->total ? number_format($sales_result->total + $debitAmount->credit + $opening_balance_dbt, 2) : number_format(0 + $debitAmount->credit + $opening_balance_dbt, 2) ?>
                                            </td>
                                            <td style="text-align:center;">
                                                <?= $purchase_result->total ? number_format($purchase_result->total + $crditAmount->credit + $opening_balance_cr, 2) : number_format(0 + $crditAmount->credit + $opening_balance_cr, 2) ?>
                                            </td>
                                            <td style="text-align:center;">
                                                <?= number_format($bal, 2) ?>
                                            </td>
                                            <td>  
                                                <?= number_format($opening_balance = get_title('parties', ['id' => $PartyData->id], 'opening_balance'), 2); ?>
                                            </td>
                                            <td class=" text-center text-nowrap">
                                                <a href="javascript:void(0);" class="edit" title="Edit">
                                                    <i class="fa fa-pencil teal-text"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="credit" title="Credit Entry">
                                                    <i class="fa fa-money green" onclick="purchase(<?= $PartyData->id ?>);"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="debit" title="Debit Entry">
                                                    <i class="fa fa-money danger" onclick="sale(<?= $PartyData->id ?>);"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="payment" title="Payment">
                                                    <i class="fa fa-money blue" onclick="payment(<?= $PartyData->id ?>);"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="common_modal" tabindex="-1" role="dialog" aria-labelledby="common_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="common_modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, -1],
                [20, 50, 100, "All"]
            ],
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5',
                'print',
                'pageLength',
            ]

        });
    });

    function sale(party_id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "payment/sale_report/" + party_id);
        $("#common_modal_label").html("Sale Report");
        $("#common_modal").modal("show");
    }

    function purchase(party_id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "payment/purchase_report/" + party_id);
        $("#common_modal_label").html("Purchase Report");
        $("#common_modal").modal("show");
    }

    function payment(party_id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "payment/payment/" + party_id);
        $("#common_modal_label").html("Payment Report");
        $("#common_modal").modal("show");
    }
</script>