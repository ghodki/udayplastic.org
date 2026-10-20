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
                                  Multiple Account Statement
                              </h4>
                          </div>
                          <!-- <button type="button" class="btn btn-success btn-sm pull-right" title="Print" onclick="PrintStatement();">
                              <i class="fa fa-print text-white"></i> Print Statement
                          </button>                                 -->
                      </div>
                      <div class="card-body">
                          <form action="" method="GET" id="StatementForm">
                              <div class="row">
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
                                        <a href="<?= base_url(); ?>payment/multipleStatement" class="btn btn-info btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a> 
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
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

    $("#StatementForm").on("submit", function(e) {
        e.preventDefault(); // stop refresh
        PrintStatement();   // run your custom code
    });

    const PrintStatement = () => {
        if ($("#fromDate").val() && $("#toDate").val()) {
            var FormData = $("#StatementForm").serialize();
            window.open("<?= base_url(); ?>payment/PrintMultiplePaymentStatement?" + FormData, "_blank");
        } else {
            alert("Please select required columns.");            
        }
    };
</script>