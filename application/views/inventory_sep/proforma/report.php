<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
        .dropdown-menu-end {
                min-width: 9rem !important;
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
        .btn-group{
                display: block;
        }
        .custom-select {
                height: calc(2rem + 1px);
                line-height: 1;
        }
        .form-check-label {
                color: #000 !important;
                font-size: 14px;
        }
        table.dataTable tfoot th {
                padding: 10px 5px 6px 5px;
        }
        .multiselect-container {
                transform: translate3d(0px, 32px, 0px) !important;
        }
        .multiselect-container .multiselect-option {
                padding: 0px 5px 0px 5px;
        }
        .dropdown-menu.show {
                height: auto;
                max-height: 200px;
                overflow-y: scroll;
        }
        .dropdown-menu {
                min-width: 24.6rem;
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
                                       <!-- Index Start -->
                                       <div class="card card-outline card-info">
                                               <div class="card-header">
                                                       <div class="row">
                                                               <div class="col-md-4">
                                                                       <h2 class="card-title">Proforma Report</h2>
                                                               </div>
                                                               <div class="col-md-6 text-center">
                                                                       <button type="button" class="btn btn-primary btn-xs" title="Previous Month" id="pre" onclick="return PreMonth();">
                                                                               <i class="fa fa-calendar-minus-o text-white"></i>
                                                                       </button>
                                                                       <button type="button" class="btn btn-warning btn-xs" title="Current Month" id="currentMont" onclick="return curent();">
                                                                               <i class="fa fa-calendar-check-o text-white"></i>
                                                                       </button>
                                                                       <button type="button" class="btn btn-dark btn-xs" title="Next Month" id="nextMonth" onclick="return nextmonth();">
                                                                               <i class="fa fa-calendar-plus-o text-white"></i>
                                                                       </button>                                    
                                                                       <button type="button" class="btn btn-default btn-xs" title="JSON File" id="json">
                                                                               <i class="fa fa-file-code"></i>
                                                                       </button>
                                                                       <button type="button" class="btn btn-secondary btn-xs" title="Excel File" id="excelExport" >
                                                                               <i class="fa fa-file-excel-o text-white"></i>
                                                                       </button>
                                                                       <button type="button" class="btn btn-danger btn-xs" title="Print File" id="printmultiple">
                                                                               <i class="fa fa-print text-white"></i>
                                                                       </button>
                                                                       <button type="button" class="btn btn-success btn-xs" title="PDF File" id="downloadPDF">
                                                                               <i class="fa fa-file-pdf-o text-white"></i>
                                                                       </button>
                                                               </div>
                                                               <div class="col-md-2">
                                                                       <div class="pull-right">
                                                                               <a href="<?= base_url(); ?>proforma" class="btn btn-info btn-xs" title="Back to Order Book Create">
                                                                                       <i class="fa fa-arrow-circle-left"></i> Back
                                                                               </a>
                                                                       </div>
                                                               </div>
                                                       </div>
                                                       <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                                               <div class="row">
                                                                       <div class="form-group col-md-4 col-sm-4">
                                                                              <label for="party">Parties</label>
                                                                              <select name="party[]" id="party" class="form-control form-control-sm" title="Select Party Name" multiple="">
                                                                                      <option <?= is_array($this->input->get('party')) ? in_array('All', $this->input->get('party')) ? "selected" : "" : ''; ?> value="All">All Party</option>
                                                                                      <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                                                                      <option <?= is_array($this->input->get('party')) ? in_array($party->id, $this->input->get('party')) ? "selected" : "" : ''; ?> value="<?= $party->id; ?>"><?= $party->name; ?></option>
                                                                                      <?php } } ?>
                                                                               </select>
                                                                       </div>
                                                                       <div class="form-group col-md-2 col-sm-2">
                                                                               <label for="fromDate">From Date</label>
                                                                               <input type="date" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-01'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                                                       </div>
                                                                       <div class="form-group col-md-2 col-sm-2">
                                                                               <label for="toDate">To Date</label>
                                                                               <input type="date" onclick="enterKeyPressed(event);" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                                                       </div>
                                                                       <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                                                               <button type="submit" class="btn btn-primary btn-xs" onClick="return search();" onkeydown="firstRow(event);">
                                                                                       <i class="fa fa-search text-white"></i> Search
                                                                               </button>
                                                                               <button type="button" class="btn btn-info btn-xs" title="Refresh" id="resetbtn">
                                                                                       <i class="fa fa-refresh text-white"></i>
                                                                               </button>                                        
                                                                       </div>
                                                                </div>
                                                       </form>
                                               </div>
                                                <div class="card-body">
                                                       <div id="divas" class="table-responsive">
                                                               <table class="table table-striped table-bordered dataTable" id="example">
                                                                       <thead>
                                                                               <tr role="row">
                                                                                      <th rowspan="1" colspan="1" aria-label="">
                                                                                              <input type="checkbox" id="sale-selectall">
                                                                                              <label for="sale-selectall"></label>
                                                                                       </th>
                                                                                      <th>Sr.</th>
                                                                                      <th>Invoice No</th>
                                                                                      <th>Invoice Date</th>
                                                                                      <th>Party Name</th>
                                                                                      <th>Mobile</th>
                                                                                      <th>GST No</th>
                                                                                      <th>Payment Mode</th> 
                                                                                      <th>Taxable Amount</th>
                                                                                      <th>Tax</th>
                                                                                      <th>Total</th>
                                                                                      <th>Round Off</th>
                                                                                      <th>Invoice</th>
                                                                                      <th>Action</th>
                                                                               </tr>
                                                                       </thead>
                                                                        <tbody id="tbody">
                                                                               <form class="form_select" method="POST" id="form_select">
                                                                                      <?php
                                                                                               $TotalTaxableAmount = 0;
                                                                                               $TotalTax = 0;
                                                                                               $TotalAmount = 0;
                                                                                               $RoundOff = 0;
                                                                                               if ($result) {
                                                                                                      foreach ($result as $key => $row) {
                                                                                                              $TotalTaxableAmount += $row->taxableAmount;
                                                                                                              $TotalTax += $row->totalTax;
                                                                                                              $TotalAmount += $row->total;
                                                                                                              $RoundOff += $row->roundOff;
                                                                                       ?>
                                                                                       <tr id="trp<?= $key; ?>" tabindex="<?= $key + 5; ?>">
                                                                                              <td class="cheking text-center">
                                                                                                      <input class="checkdata" type="checkbox" name="product_id[]" value="<?php echo $row->id; ?>" />
                                                                                              </td>
                                                                                              <td><?= $key + 1; ?></td>
                                                                                              <td><?= $row->invoiceNumber; ?></td>
                                                                                              <td><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                                                                              <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                                                                              <td><?= get_title('parties', ['id' => $row->customerId], 'contact') ; ?></td>
                                                                                              <td><?= get_title('parties', ['id' => $row->customerId], 'gst') ; ?></td>
                                                                                              <td>
                                                                                                      <?php
                                                                                                              if ($row->voucherType == 1) {
                                                                                                                      $payment_mode = "Cash";
                                                                                                              } else if ($row->voucherType == 2) {
                                                                                                                      $payment_mode = "Credit";
                                                                                                              } else {
                                                                                                                      $payment_mode = $row->voucherType;
                                                                                                              }
                                                                                                              echo $payment_mode;
                                                                                                      ?>
                                                                                              </td>
                                                                                              <td><i class="fa fa-inr"></i> <?= number_format($row->taxableAmount); ?></td>
                                                                                              <td><i class="fa fa-inr"></i> <?= number_format($row->totalTax); ?></td>
                                                                                              <td><i class="fa fa-inr"></i> <?= number_format($row->total); ?></td>
                                                                                              <td><i class="fa fa-inr"></i> <?= $row->roundOff; ?></td>
                                                                                              <td>
                                                                                                      <?php if ($row->invoiceStatus == "0") { ?>
                                                                                                      <a href="<?= base_url(); ?>proforma/proformaToSale/<?= $row->id; ?>" title="Create Sale Invoice" class="badge badge-primary">
                                                                                                              <i class="fa fa-plus-circle text-white"></i> Create
                                                                                                      </a>
                                                                                                      <?php } elseif ($row->invoiceStatus == "1") { ?>
                                                                                                      <a href="<?= base_url(); ?>sale/saleEdit/<?= get_title('sales', ['proformaId' => $row->id], 'id'); ?>" title="Open Sale Invoice" class="badge badge-success">
                                                                                                              <?= get_title('sales', ['proformaId' => $row->id], 'invoiceNumber'); ?>
                                                                                                      </a>
                                                                                                      <?php } elseif ($row->invoiceStatus == "2") { ?>
                                                                                                      <label class="badge badge-danger"><i class="fa fa-ban text-white"> Cancelled</i></label>
                                                                                                      <?php } ?>
                                                                                              </td>
                                                                                              <td>
                                                                                                      <a href="<?= base_url(); ?>proforma/view/<?= $row->id; ?>" class="text-success" target="_blank" title="View">
                                                                                                              <i class="fa fa-eye"></i>
                                                                                                      </a>
                                                                                                      &nbsp;
                                                                                                      <a href="<?= base_url(); ?>proforma/edit/<?= $row->id; ?>" class="text-info" target="_blank" title="Edit">
                                                                                                              <i class="fa fa-edit"></i>
                                                                                                      </a>
                                                                                                      &nbsp;
                                                                                                      <a href="<?= base_url(); ?>proforma/recycle/<?= $row->id; ?>" class="text-danger" onclick="return confirm('Are you sure to want to delete this invoice');">
                                                                                                              <i class="fa fa-trash"></i>
                                                                                                      </a>
                                                                                                      &nbsp;
                                                                                                      <a href="<?= base_url(); ?>proforma/print/<?= $row->id; ?>" class="text-success" target="_blank" title="Print">
                                                                                                              <i class="fa fa-print"></i>
                                                                                                      </a>
                                                                                                      &nbsp;
                                                                                                      <a href="<?= base_url(); ?>proforma/download/<?= $row->id; ?>" class="text-primary" target="_blank" title="Download">
                                                                                                              <i class="fa fa-download"></i>
                                                                                                      </a>
                                                                                                      &nbsp;
                                                                                                      <a href="javascript:void(0);" class="text-dark" title="Send Mail" onclick="sendmail(<?= $row->id; ?>);">
                                                                                                              <i class="fa fa-envelope" aria-hidden="true"></i>
                                                                                                      </a>
                                                                                                      &nbsp;
                                                                                                      <a href="https://api.whatsapp.com/send?phone=+91<?= get_title('parties', ['id' => $row->customerId], 'contact'); ?>&text=<?= base_url(); ?>orderbook/printInvoice/<?= $row->id; ?>" class="text-info" title="Send on WhatsApp" target="_blank">
                                                                                                              <img src="<?= base_url(); ?>assets/images/whatsapp.png" alt="whatsapp" style="width: 20px;" />
                                                                                                      </a>
                                                                                              </td>
                                                                                      </tr>
                                                                                      <?php } } ?>
                                                                               </form>
                                                                       </tbody>
                                                                       <tfoot>
                                                                              <tr>
                                                                                      <th colspan="8" style="text-align: right;">Total</th>
                                                                                      <th><i class="fa fa-inr"></i> <?= number_format($TotalTaxableAmount); ?></th>
                                                                                      <th><i class="fa fa-inr"></i> <?= number_format($TotalTax); ?></th>
                                                                                      <th><i class="fa fa-inr"></i> <?= number_format($TotalAmount); ?></th>
                                                                                      <th><i class="fa fa-inr"></i> <?= number_format($RoundOff); ?></th>
                                                                                      <th></th>
                                                                                      <th></th>
                                                                               </tr>
                                                                       </tfoot>
                                                               </table>
                                                       </div>
                                               </div>
                                               <div class="card-footer">
                                                       <p class="text-center">
                                                               Note : <span class="text-danger font-italic">**Deleted files stored in cancelled invoices**</span>
                                                       </p>
                                               </div>
                                       </div>
                                       <!-- Index End -->
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $('#party').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });

    $("#sale-selectall").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    $(function() {
        $("#printmultiple").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                if (!confirm('Are you sure to print all invoices?')) return false;
                console.log(selected, "ids");
                $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url('proforma/printMultiple'); ?>",
                    data    :   {id: selected},
                    dataType:   "JSON",
                    success :   function(data) {
                                    console.log(data);
                                    if (data.status == true) {
                                        toastr.success("", data.data);
                                        window.location.href = "<?= base_url() ?>proforma/" + data.url;
                                    } else {
                                        toastr.error("", data.data);
                                    }
                                }
                });
            }
        });
    });

    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>proforma/export_excel?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "proforma_report.xls");
        $a[0].click();
        $a.remove();
    });
    
    $('#downloadPDF').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>orderbook/pdf?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    });

    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>proforma/report?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };

    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>proforma/report";
    });

    function PreMonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate');
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>proforma/report?" + form;
    }

    function curent() {
        var today = new Date();
        var dd = today.getDate();
        console.log(today);
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }
        today = yyyy + '-' + mm + '-' + '01';
        var monthlastdate = yyyy + '-' + mm + '-' + dd;
        console.log(today);
        document.getElementById('fromDate').value = today;
        document.getElementById('toDate').value = monthlastdate;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>proforma/report?" + form;
    }

    function nextmonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate');
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>proforma/report?" + form;
    }
    
    var inputs = document.querySelectorAll("input, select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');

                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                nextInput[0].focus();
            }
        });
    }

    $('a').each(function() {
        var a = new RegExp('/' + window.location.host + '/');
        if (!a.test(this.href)) {
            $(this).attr("target", "_blank");
        }
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
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>proformaReport<?= time() ?>",
                    footer: true,
                    exportOptions:  {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>proformaReport<?= time() ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>proformaReport<?= time() ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>proformaReport<?= time() ?>",
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>proformaReport<?= time() ?>",
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

    function deleteRow(url) {
        Swal.fire({
            title: "Are you sure?",
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
            success :   function(success) {
                            var successJson = JSON.parse(success);
                            if (successJson.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: successJson.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: successJson.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    }

    $('#order_register').focus();

    function firstRow(event) {
        var start = document.getElementById('trp0');
        start.focus();
        start.style.backgroundColor = 'blue';
        start.style.color = 'white';
    }

    function sendmail(id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "proforma/sendmail/" + id);
        $("#common_modal_label").html("Send Mail");
        $("#common_modal").modal("show");
    }
</script>