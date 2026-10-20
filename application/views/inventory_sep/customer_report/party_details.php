<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row" style="margin-top:65px;">

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"><?= $party->name ?> Customer Report</div>
                        </div>
                        <form id="customer-search-frm" method="POST" action="<?= base_url(); ?>party/customer_report_by_filter/<?= $party->id; ?>">
                            <div class="card-body">
                                <?php
                                    if (!empty($this->session->userdata('from_date'))) {
                                        $fromDate = $this->session->userdata('from_date');
                                        $endDate = $this->session->userdata('endDate');
                                    } else {
                                        $fromDate = date('Y-m-01');
                                        $endDate = date('Y-m-d');
                                    }
                                ?>
                                <div class="row" style="margin: 10px 0px;">
                                    <div class="col-sm-3">
                                        <div class="input-field">
                                            <label for="src_inv_start_date" class="active">From</label>
                                            <input name="start_date" id="src_inv_start_date" type="date" class="form-control" autocomplete="off" value="<?= $fromDate ?>" spellcheck="false" data-ms-editor="true" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-field">
                                            <label for="src_cn" class="active">To</label>
                                            <input name="end_date" id="src_inv_end_date" type="date" class="form-control" autocomplete="off" value="<?= $endDate ?>" spellcheck="false" data-ms-editor="true" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="printing_format">Format</label>
                                        <select class="form-control" name="printing_format" id="printing_format">
                                            <option value="print">Print</option>
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="type">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="all">All</option>
                                            <option value="credit">Credit</option>
                                            <option value="debit">Debit</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer center" style="overflow: auto;">
                                <button id="search-customer" type="submit" class="btn btn-outline-primary pull-right" style="margin-top: 10px;">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>