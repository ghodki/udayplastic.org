<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    @media print {
        @page {
            size: auto;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-maroon">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-maroon">
                                    Day Book Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <form action="" id="reportForm" method="GET">
                                        <div class="row">
                                            <div class="form-group col-md-2 col-lg-2 col-sm-6">
                                                <label for="fromDate">From Date</label>
                                                <input type="date" name="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" id="fromDate" class="form-control form-control-sm" required="" />
                                            </div>
                                            <div class="form-group col-md-2 col-sm-2">
                                                <label for="toDate">To Date</label>
                                                <input type="date" name="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" id="toDate" class="form-control form-control-sm" />
                                            </div>
                                            <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                                <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                                    <i class="fa fa-search text-white"></i> Search
                                                </button>
                                                <a href="<?= base_url(); ?>Reports/DayBookReport" class="btn btn-danger btn-sm" title="Refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="Print('printeblearea');" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="printeblearea">
                                <?php
                                    $CashOpeningBalance = (float) ($CashOpeningBalance ?? 0);
                                    $BankOpeningBalance = (float) ($BankOpeningBalance ?? 0);
                                    $CreditCashTotal = 0;
                                    $DebitTotal = 0;
                                    $OtherDebitTotal = 0;
                                    $OtherLedgerCreditTotal = 0;
                                    $DebitCashTotal = 0;
                                    $PaymentTotal = 0;
                                    $OtherLedgerDebitTotal = 0;
                                    $CashDiscountTotal = 0;
                                    $CreditCreditTotal = 0;
                                    $DebitCreditTotal = 0;
                                ?>
                                <div class="row" style="display: flex;">
                                    <div class="col-md-12 col-lg-12 col-sm-12" style="width: 100%;">
                                        <h6 class="text-center">
                                            Date : From <?= date('d-m-Y', strtotime($this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'))); ?>
                                            - To <?= date('d-m-Y', strtotime($this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'))); ?>
                                        </h6>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6" style="width: 50%;">
                                        <div class="table-responsive">
                                            <h3 class="text-center" style="text-align: center;">Credit</h3>
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-gradient-primary">
                                                    <tr>
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?= indianMoneyFormat($CashOpeningBalance); ?>
                                                        </th>
                                                        <th colspan="2" class="text-white">Cash Opening Balance</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <hr />
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-gradient-primary">
                                                    <tr>
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?= indianMoneyFormat($BankOpeningBalance); ?>
                                                        </th>
                                                        <th colspan="2" class="text-white">Bank Opening Balance</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            
                                            <?php 
                                                if (!empty($CreditAccountsGroup)) {
                                                    foreach ($CreditAccountsGroup as $key => $Credit) {
                                                        if ($Credit->name == "Cash Sale") {
                                                            if (!empty($CreditCashResult)) {
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $CreditCashTotal = 0;
                                                                foreach ($CreditCashResult as $key => $CreditCashData) {
                                                                    $CreditCashTotal += (float) ($CreditCashData->total ?? 0);
                                                                }
                                                                echo indianMoneyFormat($CreditCashTotal); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2"><?= $Credit->name; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($CreditCashResult as $key => $CreditCashData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($CreditCashData->total); ?></td>
                                                        <td>
                                                            <?php if ($CreditCashData->type == 'Sale') { ?>
                                                            <a href="<?= base_url(); ?>Sale/saleEdit/<?= $CreditCashData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $CreditCashData->customerId], 'name'); ?>
                                                            </a>
                                                            <?php } elseif ($CreditCashData->type == 'Estimate') { ?>
                                                            <a href="<?= base_url(); ?>Estimate/estimateEdit/<?= $CreditCashData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $CreditCashData->customerId], 'name'); ?>
                                                            </a>    
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } } 
                                                if ($CashReceiptResult) {
                                                    if ($Credit->name == "Cash Receipt") {
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $DebitTotal = 0;
                                                                foreach ($CashReceiptResult as $key => $DebitData) {
                                                                    $DebitTotal += (float) ($DebitData->receivedPaidAmount ?? 0);
                                                                }
                                                                echo indianMoneyFormat($DebitTotal); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2"><?= $Credit->name; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($CashReceiptResult as $key => $DebitData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($DebitData->receivedPaidAmount); ?></td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>Payment/Print/<?= $DebitData->id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $DebitData->party_id], 'name'); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } }
                                                if ($Credit->name == "Bank Account") {
                                                    if ($OtherReceiptResult) {            
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $OtherDebitTotal = 0;
                                                                foreach ($OtherReceiptResult as $key => $DebitData) {
                                                                    $OtherDebitTotal += (float) ($DebitData->receivedPaidAmount ?? 0);
                                                                }
                                                                echo indianMoneyFormat($OtherDebitTotal); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2"><?= $Credit->name; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($OtherReceiptResult as $key => $DebitData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($DebitData->receivedPaidAmount); ?></td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>Payment/Print/<?= $DebitData->id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $DebitData->party_id], 'name'); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } } 
                                                if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                                                    $FromDate = $this->input->get('fromDate');
                                                    $ToDate = $this->input->get('toDate');
                                                } else {
                                                    $FromDate = date('Y-m-d');
                                                    $ToDate = date('Y-m-d');
                                                }
                                                
                                                $OtherCreditWhere = ['AccountGroupId' => $Credit->id, 'Type' => 'CR', 'Date >= ' => $FromDate, 'Date <= ' => $ToDate, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                                                if ($this->session->userdata('BranchRole')) {
                                                    $OtherCreditWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                }
                                                
                                                $OtherCreditResult = $this->db->select('Id, AccountGroupId, LedgerId, Amount')->where($OtherCreditWhere)->order_by('Id', 'DESC')->get('ledger_entries')->result();
                                                if ($OtherCreditResult) {
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $TotalOtherCredit = 0;
                                                                foreach ($OtherCreditResult as $key => $OtherCreditData) {
                                                                    $TotalOtherCredit += (float) ($OtherCreditData->Amount ?? 0);
                                                                    $OtherLedgerCreditTotal += (float) ($OtherCreditData->Amount ?? 0);
                                                                }
                                                                echo indianMoneyFormat($TotalOtherCredit); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2"><?= $Credit->name; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($OtherCreditResult as $key => $OtherCreditData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($OtherCreditData->Amount); ?></td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>Ledger/Edit/<?= $OtherCreditData->Id; ?>" target="_blank">
                                                                <?= get_title('ladger', ['id' => $OtherCreditData->LedgerId], 'name'); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } } } ?>
                                        </div>  
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6" style="width: 50%;">
                                        <div class="table-responsive">
                                            <h3 class="text-center" style="text-align: center;">Debit</h3>
                                            <?php 
                                                if (!empty($DebitAccountsGroup)) {
                                                    foreach ($DebitAccountsGroup as $key => $Debit) {
                                                        if ($Debit->name == 'Cash Purchase') {
                                                            if (!empty($DebitCashResult)) { 
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $DebitCashTotal = 0;
                                                                foreach ($DebitCashResult as $key => $DeditCashData) {
                                                                    $DebitCashTotal += (float) ($DeditCashData->total ?? 0);
                                                                }
                                                                echo indianMoneyFormat($DebitCashTotal); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2"><?= $Debit->name; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($DebitCashResult as $key => $DeditCashData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($DeditCashData->total); ?></td>
                                                        <td>
                                                            <?php if ($DeditCashData->type == 'Purchase') { ?>
                                                            <a href="<?= base_url(); ?>Purchase/purchaseEdit/<?= $DeditCashData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $DeditCashData->customerId], 'name'); ?>
                                                            </a>
                                                            <?php } elseif ($DeditCashData->type == 'Estimate Purchase') { ?>
                                                            <a href="<?= base_url(); ?>Estimate_purchase/edit/<?= $DeditCashData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $DeditCashData->customerId], 'name'); ?>
                                                            </a>    
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } }
                                                if ($Debit->name == 'Payment') {
                                                    if ($PaymentResult) { 
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $PaymentTotal = 0;
                                                                foreach ($PaymentResult as $key => $CreditData) {
                                                                    $PaymentTotal += (float) ($CreditData->credit ?? 0);
                                                                }
                                                                echo indianMoneyFormat($PaymentTotal); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2"><?= $Debit->name; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($PaymentResult as $key => $CreditData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($CreditData->credit); ?></td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>Payment/Print/<?= $CreditData->id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $CreditData->party_id], 'name'); ?>
                                                                <small>
                                                                    (<?= get_title('payment_mode', ['id' => $CreditData->paymentmode], 'payment_method'); ?>)
                                                                </small>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } }
                                                if ($Debit->name == 'Cash Discount Receipt') {
                                                    if ($CashDiscountReceiptResult) {
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $CashDiscountTotal = 0;
                                                                foreach ($CashDiscountReceiptResult as $key => $CashDiscountData) {
                                                                    $CashDiscountTotal += (float) ($CashDiscountData->cashDiscountAmount ?? 0);
                                                                }
                                                                echo indianMoneyFormat($CashDiscountTotal); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2">Cash Discount Receipt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($CashDiscountReceiptResult as $key => $CashDiscountData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($CashDiscountData->cashDiscountAmount); ?></td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>Payment/Print/<?= $CashDiscountData->id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $CashDiscountData->party_id], 'name'); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } }
                                                if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                                                    $FromDate = $this->input->get('fromDate');
                                                    $ToDate = $this->input->get('toDate');
                                                } else {
                                                    $FromDate = date('Y-m-d');
                                                    $ToDate = date('Y-m-d');
                                                }
                                                
                                                $OtherDebitWhere = ['AccountGroupId' => $Debit->id, 'Type' => 'DR', 'Date >= ' => $FromDate, 'Date <= ' => $ToDate, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                                                if ($this->session->userdata('BranchRole')) {
                                                    $OtherDebitWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                }
                                                
                                                $OtherDebitResult = $this->db->select('Id, AccountGroupId, LedgerId, Amount')->where($OtherDebitWhere)->order_by('Id', 'DESC')->get('ledger_entries')->result();
                                                if ($OtherDebitResult) {
                                            ?>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $TotalOtherDebit = 0;
                                                                foreach ($OtherDebitResult as $key => $OtherDebitData) {
                                                                    $TotalOtherDebit += (float) ($OtherDebitData->Amount ?? 0);
                                                                    $OtherLedgerDebitTotal += (float) ($OtherDebitData->Amount ?? 0);
                                                                }
                                                                echo indianMoneyFormat($TotalOtherDebit); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2"><?= $Debit->name; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($OtherDebitResult as $key => $OtherDebitData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($OtherDebitData->Amount); ?></td>
                                                        <td>
                                                            <a href="<?= base_url(); ?>Ledger/Edit/<?= $OtherDebitData->Id; ?>" target="_blank">
                                                                <?= get_title('ladger', ['id' => $OtherDebitData->LedgerId], 'name'); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php } } } ?>

                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-gradient-primary">
                                                    <tr>
                                                        <th class="text-pink text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?= indianMoneyFormat(($CreditCashTotal + $DebitTotal + $OtherDebitTotal + $OtherLedgerCreditTotal + $CashOpeningBalance + $BankOpeningBalance) - ($DebitCashTotal + $PaymentTotal + $OtherLedgerDebitTotal + $CashDiscountTotal)); ?>
                                                        </th>
                                                        <th colspan="2" class="text-white">Closing Balance</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>  
                                    </div>
                                </div>
                                <div class="row mt-4" style="display: flex;">
                                    <div class="col-md-6 col-lg-6 col-sm-6" style="width: 50%;">
                                        <!-- Credit -->
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="bg-gradient-navy">
                                                    <tr>
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i>
                                                            <?= indianMoneyFormat(($CreditCashTotal + $DebitTotal + $OtherDebitTotal + $OtherLedgerCreditTotal + $CashOpeningBalance + $BankOpeningBalance)); ?>
                                                        </th>
                                                        <th class="text-white" colspan="2">Grand Total</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6" style="width: 50%;">
                                        <!-- Debit -->
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered"> 
                                                <thead class="bg-gradient-navy">
                                                    <tr>
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $TotalCredit = ($CreditCashTotal + $DebitTotal + $OtherDebitTotal + $OtherLedgerCreditTotal + $CashOpeningBalance + $BankOpeningBalance);
                                                                $TotalDebit = ($DebitCashTotal + $PaymentTotal + $OtherLedgerDebitTotal + $CashDiscountTotal);
                                                                $TotalClosing = ($TotalCredit - $TotalDebit);
                                                                echo indianMoneyFormat(($TotalDebit + $TotalClosing)); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2">Grand Total</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6" style="width: 50%;">
                                        <?php if ($CreditCreditResult) { ?>
                                        <hr class="bg-fuchsia" />
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i>
                                                            <?php
                                                                $CreditCreditTotal = 0;
                                                                foreach ($CreditCreditResult as $key => $CreditCreditData) {
                                                                    $CreditCreditTotal += (float) ($CreditCreditData->total ?? 0);
                                                                }
                                                                echo indianMoneyFormat($CreditCreditTotal);
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2">Credit Sale</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($CreditCreditResult as $key => $CreditCreditData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($CreditCreditData->total); ?></td>
                                                        <td>
                                                            <?php if ($CreditCreditData->type == 'Sale') { ?>
                                                            <a href="<?= base_url(); ?>Sale/saleEdit/<?= $CreditCreditData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $CreditCreditData->customerId], 'name'); ?>
                                                            </a>
                                                            <?php } elseif ($CreditCreditData->type == 'Estimate') { ?>
                                                            <a href="<?= base_url(); ?>Estimate/estimateEdit/<?= $CreditCreditData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $CreditCreditData->customerId], 'name'); ?>
                                                            </a>    
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6" style="width: 50%;">
                                        <?php if ($DebitCreditResult) { ?>
                                        <hr class="bg-fuchsia" />
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-gradient-gray">
                                                        <th class="text-white">
                                                            <i class="fa fa-inr text-white"></i> 
                                                            <?php
                                                                $DebitCreditTotal = 0;
                                                                foreach ($DebitCreditResult as $key => $DebitCreditData) {
                                                                    $DebitCreditTotal += (float) ($DebitCreditData->total ?? 0);
                                                                }
                                                                echo indianMoneyFormat($DebitCreditTotal); 
                                                            ?>
                                                        </th>
                                                        <th class="text-white" colspan="2">Credit Purchase</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($DebitCreditResult as $key => $DebitCreditData) { ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($DebitCreditData->total); ?></td>
                                                        <td>
                                                            <?php if ($DebitCreditData->type == 'Purchase') { ?>
                                                            <a href="<?= base_url(); ?>Purchase/purchaseEdit/<?= $DebitCreditData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $DebitCreditData->customerId], 'name'); ?>
                                                            </a>
                                                            <?php } elseif ($DebitCreditData->type == 'Estimate Purchase') { ?>
                                                            <a href="<?= base_url(); ?>Estimate_purchase/edit/<?= $DebitCreditData->Id; ?>" target="_blank">
                                                                <?= get_title('parties', ['id' => $DebitCreditData->customerId], 'name'); ?>
                                                            </a>    
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function Print(divName) {
        var Anchors = document.getElementsByTagName('a');
        for (var i = 0; i < Anchors.length; i++) {
            Anchors[i].style.color = 'black';
            Anchors[i].style.textDecoration = 'none';
        }
        var tableTh = document.getElementsByTagName('th');
        for (var i = 0; i < tableTh.length; i++) {
            tableTh[i].style.setProperty('color', 'black', 'important');
        }
        var inrI = document.getElementsByTagName('i');
        for (var i = 0; i < inrI.length; i++) {
            inrI[i].style.setProperty('color', 'black', 'important');
        }
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>