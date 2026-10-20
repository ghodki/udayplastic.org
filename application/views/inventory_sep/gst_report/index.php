<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-fuchsia card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>GST Report</h4>
                            </div>
                            <a href="<?= base_url(); ?>Sale" class="btn bg-fuchsia btn-sm float-right" title="back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <form id="gst-frm" action="<?= base_url(); ?>Gst_report/gstr1" method="POST">
                                <div class="row">
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                        <div class="input-field">
                                            <input name="start_date" id="src_inv_start_date" type="date" class="form-control" autocomplete="off" value="<?= date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" spellcheck="false" data-ms-editor="true">
                                            <label for="src_inv_start_date" class="active">From</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                        <div class="input-field">
                                            <input name="end_date" id="src_inv_end_date" type="date" class="form-control" autocomplete="off" value="<?= date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" spellcheck="false" data-ms-editor="true">
                                            <label for="src_inv_end_date" class="active">To</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4">
                                        <span title="Previous Month" id="pre" style="border-color: #900000;color:blue" onclick="return PreMonth()" class="btn btn-primary text-white">Previous Month</span>
                                        <span title="Current Month" id="currentMont" onclick="return curent()" style="background-color: aqua;" class="btn">Current Month</span>
                                        <span title="Next Month" id="nextMonth" style="border-color: #900000;color:green" onclick="return nextmonth()" class="btn btn-warning text-white">Next Month</span>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 text-center p-3 text-light" style="border:1px solid-dark;background-color:green;font-size:20px;">
                                    <div id="gstr1">
                                        <div class="main text-white">Details of outward supplies of goods or services</div>
                                        <div class="main text-white">GSTR1</div>
                                        <div class="icon"><i class="fa fa-inr fa-3x text-white"></i></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6 text-center p-3" style="border:1px solid-dark;background-color:yellow;font-size:20px;">
                                    <div id="gstr3b">
                                        <div class="main">Monthly Return</div>
                                        <div class="main">GSTR3B</div>
                                        <div class="icon"><i class="fa fa-inr fa-3x"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php if(getUserPermission()->gstr1Status=="Y"){ ?>
                              <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6 text-center p-3" style="border:1px solid-dark;font-size:20px;background-color:#0b3389;color:#fff;cursor:pointer" onclick="gstr_monthly(5)">
                            <a href="<?= base_url('einvoice/monthlyGstr') ?>">E-GSTR1 Monthly</a> 
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 text-center p-3" style="border:1px solid-dark;font-size:20px;background-color:#f11ec1;color:#fff;cursor:pointer">
                            E-GSTR1 Yearly
                            </div> 
                            </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });

    $("#gstr1").on('click', function(e) {
        let end_date = $("#src_inv_end_date").val();
        let start_date = $("#src_inv_start_date").val();
        window.location.href = "<?= base_url(); ?>Gst_report/gstr1/" + start_date + "/" + end_date;
    });

    $("#gstr3b").on('click', function(e) {
        let end_date = $("#src_inv_end_date").val();
        let start_date = $("#src_inv_start_date").val();
        window.location.href = "<?= base_url() ?>Gst_report/gstr3b/" + start_date + "/" + end_date;
    }); 
    
    function PreMonth() {
        var fromDate = document.getElementById('src_inv_start_date').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD')
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD')
        document.getElementById('src_inv_end_date').value = lastDay;
        document.getElementById('src_inv_start_date').value = firstDay;
    }
        
    function curent() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }
        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById('src_inv_start_date').value = today;
        document.getElementById('src_inv_end_date').value = today;
    }

    function nextmonth() {
        var fromDate = document.getElementById('src_inv_start_date').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD')
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD')
        document.getElementById('src_inv_end_date').value = lastDay;
        document.getElementById('src_inv_start_date').value = firstDay;
    }
</script>