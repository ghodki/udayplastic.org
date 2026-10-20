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
    .content {
        padding-top: 10px !important;
    }
</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
       <div class="container-fluid">
           <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
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
                              <h4>
                                  Account Statement
                              </h4>
                          </div>
                          <button type="button" class="btn btn-success btn-sm pull-right" title="Print" onclick="PrintStatement();">
                              <i class="fa fa-print text-white"></i> Print Statement
                          </button>                                
                      </div>
                      <div class="card-body">
                          <form action="" method="GET" id="StatementForm">
                              <input type="hidden" name="accountGroupId" id="accountGroupId" value="<?= $this->input->get('accountGroupId') ?? $this->input->get('accountGroupId'); ?>" />
                              <div class="row">
                                  <div class="form-group col-md-6 col-sm-6">
                                      <label for="ledgerId">Select Ledger Name</label>
                                      <select name="ledgerId" id="ledgerId" class="form-control form-control-sm select2" title="Select Ledger Name" required="" onchange="getAccountGroupId(this.value);">
                                          <option value="" selected="" disabled="">Select Ledger Name</option>
                                          <?php
                                                  if ($this->session->userdata('RoleType')) {
                                                      $employeeId = $this->session->userdata('EmployeeId');
                                                      $userId = $this->session->userdata('UserId');
                                                      $compId = $this->session->userdata('CompId');
                                                      $salesmanId = json_decode((string) get_title('employees', ['Id' => $employeeId], 'WarehouseSalesman'), true);
                                                      if (!is_array($salesmanId)) {
                                                          $salesmanId = [];
                                                      }
                                                  } else {
                                                      $userId = $this->session->userdata('user_id');
                                                      $compId = $this->session->userdata('comp_id');
                                                  }
                                                  $compBranchId = $this->session->userdata('CompBranchId');

                                                  $ledgerWhere = ['status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
                                                  if ($this->session->userdata('BranchRole')) {
                                                      $ledgerWhere['CompanyBranchId'] = $compBranchId;
                                                  }
                                                    
                                                  $this->db->select('id, name, contact, group_head');
                                                  $this->db->where($ledgerWhere);
                                                  if ($this->session->userdata('RoleType') && !empty($salesmanId)) {
                                                      $this->db->where_in('SalesmanId', $salesmanId);
                                                  }
                                                  $this->db->order_by('name', 'ASC');
                                                  $ledgers = $this->db->get('parties')->result();
                                                  foreach ($ledgers as $key => $ledger) {
                                                      $accountGroupName = get_title('accounting_group', ['id' => $ledger->group_head], 'name');
                                          ?>
                                          <option <?= $ledger->id == $this->input->get('ledgerId') ? "selected" : ""; ?> value="<?= $ledger->id; ?>"><?= $ledger->name; ?> (<?= $ledger->contact; ?>) (<?= $accountGroupName; ?>)</option>
                                          <?php } ?>
                                      </select>
                                  </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-01'); ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="toDate">To Date</label>
                                        <input type="date" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <?php if ($this->session->userdata('RoleType')) { ?>
                                        <a href="<?= base_url(); ?>Employee/PaymentsStatement" class="btn btn-info btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                        <?php } else { ?>
                                        <a href="<?= base_url(); ?>payment/statement" class="btn btn-info btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>                                
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-condensed dataTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Particulars</th>
                                            <th>Invoice/Voucher</th>
                                            <th>Mode</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Balance</th>
                                            <th>#</th>
                                        </tr>
                                        <tr>
                                            <th class="text-right" colspan="8"><b>Opening Balance</b></th>
                                            <th colspan="2"><b><i class="fa fa-inr"></i> <?= indianMoneyFormat($openingBalance ?? 0); ?></b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $srNo = 0;
                                            $totalCredit = 0;
                                            $totalDebit = 0;
                                            $totalBalance = 0;
                                            $deleteMethodUrl = 'payment';
                                            if (!empty($ledgerStatement)) {
                                                foreach ($ledgerStatement as $key => $data) {
                                                    $srNo++;
                                                    $totalCredit += (float) $data->credit;
                                                    $totalDebit += (float) $data->debit;
                                                    $invoiceTypes = json_decode($data->invoice_type ?? '', true);
                                                    $invoiceTypes = is_array($invoiceTypes) ? $invoiceTypes : [];
                                                    
                                                    if ($data->invoice_type == "Sale" || $data->invoice_type == "Estimate" || in_array('Sale', $invoiceTypes) || in_array('Estimate', $invoiceTypes)) {
                                                        $totalBalance = ($totalCredit - $totalDebit);
                                                    } 
                                                    if ($data->invoice_type == "Purchase" || $data->invoice_type == "Estimate Purchase" || in_array('Purchase', $invoiceTypes) || in_array('Estimate Purchase', $invoiceTypes)) {
                                                        $totalBalance = ($totalDebit - $totalCredit);
                                                    }
                                                    if (empty($data->invoice_type) && ($data->payment_type == "Journal" || $data->payment_type == "Contra")) {
                                                        $totalBalance = ($totalCredit - $totalDebit);
                                                    }
                                                    
                                                    $deleteMethodUrl = 'payment';
                                                    if ($data->payment_type == "Receipt") {
                                                        $deleteMethodUrl = 'receipt';
                                                    }
                                                    if ($data->payment_type == "Payment") {
                                                        $deleteMethodUrl = 'payment';
                                                    }
                                                    if ($data->payment_type == "Journal") {
                                                        $deleteMethodUrl = 'journal';
                                                    }
                                                    if ($data->payment_type == "Contra") {
                                                        $deleteMethodUrl = 'contra';
                                                    }
                                                    
                                        ?>
                                        <tr>
                                            <td><?= $srNo; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                                            <td><?= $data->payment_type == "" ? $data->invoice_type : $data->payment_type; ?></td>
                                            <td>
                                                    <?php
                                                        if (empty($data->invoice_type) && ($data->payment_type == "Journal" || $data->payment_type == "Contra")) {
                                                            $debitLedgerId = get_title('payment', ['voucher_number' => $data->voucher_number, 'transaction_type' => 'DR'], 'party_id');
                                                            echo get_title('parties', ['id' => $debitLedgerId], 'name');
                                                        } elseif (!empty($data->invoice_type)) {
                                                            if ($data->invoice_type == "Sale" && $data->transaction_type == 'CR') {
                                                                echo ($data->payment_type == NULL) ? get_title('parties', ['id' => $data->party_id], 'name') : "";
                                                            } elseif ($data->invoice_type == "Estimate" && $data->transaction_type == 'CR') {
                                                                echo ($data->payment_type == NULL) ? get_title('parties', ['id' => $data->party_id], 'name') : "";
                                                            } elseif ($data->invoice_type == "Purchase" && $data->transaction_type == 'DR') {
                                                                echo ($data->payment_type == NULL) ? get_title('parties', ['id' => $data->party_id], 'name') : "";
                                                            } elseif ($data->invoice_type == "Estimate Purchase" && $data->transaction_type == 'DR') {
                                                                echo ($data->payment_type == NULL) ? get_title('parties', ['id' => $data->party_id], 'name') : "";
                                                            }                                                            
                                                        }
                                                    ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if ((is_array(json_decode($data->invoice_type))) || ($data->invoice_type == "Sale" && $data->payment_type == "Receipt") || ($data->invoice_type == "Estimate" && $data->payment_type == "Receipt") || ($data->invoice_type == "Purchase" && $data->payment_type == "Payment") || ($data->invoice_type == "Estimate Purchase" && $data->payment_type == "Payment")) {
                                                        echo $data->voucher_number;
                                                    } else {
                                                        if ($data->invoice_type == "Sale") {
                                                            echo ($data->payment_type == NULL) ? get_title('sales', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                                        } elseif ($data->invoice_type == "Estimate") {
                                                            echo ($data->payment_type == NULL) ? get_title('estimates', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                                        } elseif ($data->invoice_type == "Purchase") {
                                                            echo ($data->payment_type == NULL) ? get_title('purchases', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                                        } elseif ($data->invoice_type == "Estimate Purchase") {
                                                            echo ($data->payment_type == NULL) ? get_title('estimate_purchase', ['id' => $data->invoice_id], 'invoiceNumber') : "";
                                                        }
                                                    }
                                                    if (empty($data->invoice_type) && ($data->payment_type == "Journal" || $data->payment_type == "Contra")) {
                                                        echo $data->voucher_number;
                                                    }
                                                ?>
                                            </td>
                                            <td><?= get_title('payment_mode', ['id' => $data->paymentmode], 'payment_method'); ?></td>
                                            <td><?= !empty($data->credit) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($data->credit) : "-"; ?></td>
                                            <td><?= !empty($data->debit) ? "<i class='fa fa-inr'></i> " . indianMoneyFormat($data->debit) : "-"; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalBalance); ?></td>
                                            <td>
                                                    <?php
                                                    if ($data->payment_through == "Manually") {
                                                        $canDeletePayment = false;
                                                        if ($this->session->userdata('RoleType')) {
                                                            if (!empty($EmployeePermission) && !empty($EmployeePermission->Payments)) {
                                                                $payPerm = json_decode($EmployeePermission->Payments);
                                                                $canDeletePayment = (!empty($payPerm) && isset($payPerm->Delete) && $payPerm->Delete == "Delete");
                                                            }
                                                            if ($canDeletePayment) {
                                                ?>
                                                <a href="<?= base_url(); ?><?= $deleteMethodUrl; ?>/delete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash-alt"></i>
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?>Payment/Print/<?= $data->id; ?>" class="btn btn-success btn-xs" title="Print" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <a href="<?= base_url(); ?><?= $deleteMethodUrl; ?>/delete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash-alt"></i>
                                                </a>
                                                <?php } } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalCredit ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalDebit ?? 0); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalBalance ?? 0); ?></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th class="text-right" colspan="8"><b>Closing Balance</b></th>
                                            <th colspan="2"><b><i class="fa fa-inr"></i> <?= indianMoneyFormat(((float) ($openingBalance ?? 0) + (float) ($totalBalance ?? 0))); ?></b></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-center">
                                Note : <span class="text-danger font-italic">** Sundry Creditors is Purchase, Sundry Debtors is Sale **</span>
                            </p>                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">
    const getAccountGroupId = (ledgerId) => {
        if (ledgerId != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>party/getAccountGroupIdByLedgerId",
                data    :   {ledgerId: ledgerId},
                success :   function (response) {
                                var accountGroupData = JSON.parse(response);
                                if (accountGroupData.status == true) {
                                    $("#accountGroupId").val(accountGroupData.data);
                                }
                            }
            });
        }
    };
    
    $(document).ready(function() {
        $('.select2').select2();

        $(".dataTable").DataTable({
            dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            },
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                            orthogonal: 'export'
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            // Find the last row of the sheet (this will be after your actual table data)
                            var lastRow = $('row', sheet).length;

                            // Blank Row
                            var blankRow = 
                                '<row r="' + (lastRow + 1) + '">' +
                                    '<c t="s" r="A' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="B' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="C' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="D' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="E' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="F' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="G' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="H' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="I' + (lastRow + 1) + '" s="2"><v></v></c>' +
                                '</row>';

                            // Opening Balance Row
                            var openingBalanceRow = 
                                '<row r="' + (lastRow + 2) + '">' +
                                    '<c t="s" r="A' + (lastRow + 2) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="B' + (lastRow + 2) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="C' + (lastRow + 2) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="D' + (lastRow + 2) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="E' + (lastRow + 2) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="F' + (lastRow + 2) + '" s="2"><v></v></c>' +
                                    '<c t="n" r="G' + (lastRow + 2) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="H' + (lastRow + 2) + '" s="2"><v>Opening Balance</v></c>' +
                                    '<c t="n" r="I' + (lastRow + 2) + '" s="2"><v>' + '<?= indianMoneyFormat($openingBalance ?? 0); ?>' + '</v></c>' +
                                '</row>';

                            // Closing Balance Row
                            var closingBalanceRow = 
                                '<row r="' + (lastRow + 3) + '">' +
                                    '<c t="s" r="A' + (lastRow + 3) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="B' + (lastRow + 3) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="C' + (lastRow + 3) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="D' + (lastRow + 3) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="E' + (lastRow + 3) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="F' + (lastRow + 3) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="G' + (lastRow + 3) + '" s="2"><v></v></c>' +
                                    '<c t="s" r="H' + (lastRow + 3) + '" s="2"><v>Closing Balance</v></c>' +
                                    '<c t="n" r="I' + (lastRow + 3) + '" s="2"><v>' + '<?= indianMoneyFormat($totalBalance ?? 0); ?>' + '</v></c>' +
                                '</row>';

                            // Append the blank row, then the custom rows for Opening and Closing Balance to the sheet
                            $(sheet).find('sheetData').append(blankRow);
                            $(sheet).find('sheetData').append(openingBalanceRow);
                            $(sheet).find('sheetData').append(closingBalanceRow);
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            },
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                            orthogonal: 'export'
                        },
                        customize: function (doc) {
                            var openingBalance = '<?= indianMoneyFormat($openingBalance ?? 0); ?>';
                            var closingBalance = '<?= indianMoneyFormat($totalBalance ?? 0); ?>';

                            // Safeguard check for table existence
                            if (doc.content[1] && doc.content[1].table && doc.content[1].table.body) {
                                // Set the table widths to a fixed percentage to fit 100% of the page width
                                doc.content[1].table.widths = [
                                    '12%', '12%', '12%', '12%', '12%', '12%', '12%', '12%', '12%' // Adjust percentages as needed
                                ];

                                // Add Opening Balance as the second row (index 1)
                                doc.content[1].table.body.splice(1, 0, [
                                    { text: 'Opening Balance', colSpan: 8, alignment: 'right', bold: true },
                                    {}, {}, {}, {}, {}, {}, {}, { text: openingBalance, alignment: 'left', bold: true }
                                ]);

                                // Add Closing Balance as the last row
                                doc.content[1].table.body.push([
                                    { text: 'Closing Balance', colSpan: 8, alignment: 'right', bold: true },
                                    {}, {}, {}, {}, {}, {}, {}, { text: closingBalance, alignment: 'left', bold: true }
                                ]);
                            } else {
                                console.error('Table structure not found in doc.content');
                            }
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            },
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                            orthogonal: 'export'
                        },
                        customize: function (win) {
                            var openingBalance = '<?= indianMoneyFormat($openingBalance ?? 0); ?>';
                            var closingBalance = '<?= indianMoneyFormat($totalBalance ?? 0); ?>';

                            // Insert Opening Balance row at the top
                            $(win.document.body).find('table tbody').prepend(
                                `<tr><th colspan="8" style="text-align: right;"><b>Opening Balance</b></th><th><b>${openingBalance}</b></th></tr>`
                            );
                            // Insert Closing Balance row at the bottom
                            $(win.document.body).find('table tfoot').append(
                                `<tr><th colspan="8" style="text-align: right;">Closing Balance</th><th>${closingBalance}</th></tr>`
                            );
                        }
                    },
                    "pageLength"
                ],
            lengthMenu: [[50, 100, 200, 400, -1], [50, 100, 200, 400, "All"]],
        });
    });

    const PrintStatement = () => {
        if ($("#accountGroupId").val() && $("#ledgerId").val() && $("#fromDate").val() && $("#toDate").val()) {
            var FormData = $("#StatementForm").serialize();
            <?php if ($this->session->userdata('RoleType')) { ?>
            window.open("<?= base_url(); ?>Employee/PrintPaymentStatement?" + FormData, "_blank");
            <?php } else { ?>
            window.open("<?= base_url(); ?>payment/PrintStatement?" + FormData, "_blank");
            <?php } ?>
        } else {
            alert("Please select required columns.");            
        }
    };
</script>