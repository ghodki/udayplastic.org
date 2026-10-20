<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <?php
            $roles = $this->session->userdata('roles');
            if ($roles != 1) {
            ?>
                <!--<div class="col card">-->
                <button class="btn mt-5 ml-2 card-btn" style="background: coral;" data-toggle="modal" data-target="#exampleModalCenter">
                    Add Money To Wallet
                </button>
        </div>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style=" background: darkorchid; ">
                        <h5 class="modal-title text-dark" id="exampleModalLongTitle">Pay Now</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="product_info" enctype="multipart/form-data" action="<?php echo base_url(); ?>wallet/check">
                            <div class="form-group">
                                <input type="number" name="payble_amount" min="1" id="payble_amount" class="form-control" onkeypress="return onlyNumberKey(event)" placeholder="Enter Payble Amount(min 1)" value="100" required />
                            </div>
                            <input type="hidden" name="user_id" value="<?= $this->session->userdata('user_id') ?>">
                            <div class="form-group">
                                <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Full Name (Only alphabets)" value="<?= $this->session->userdata('name') ?>" required />
                            </div>
                            <div class="form-group">
                                <input type="number" name="mobile_number" id="mobile_number" class="form-control" placeholder="Mobile Number(10 digits)" value="<?= get_title('users', ['id' => $this->session->userdata('user_id')], 'number') ?>" required />
                            </div>
                            <div class="form-group">
                                <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="Email" value="<?= $this->session->userdata('email') ?>" required />
                            </div>
                            <div class="hidden">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="customer_address" id="customer_address" placeholder="Address" value="BANGALORE" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="product_info" id="product_info" class="form-control" Placeholder="Description" value="Wallet" required />
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="remark" id="remark" placeholder="Remarks" value="Wallet" required>Wallet</textarea>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button class="btn btn-secondary" type="reset">Reset</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    <?php
            }
    ?>
    <div class="container-fluid mb-9" style="margin-top:20px;">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0" style="margin-top:10px;">
                    <label class="mb-0" style="vertical-align: -moz-middle-with-baseline">Transaction History</label>
                    <div class="card p-3">
                        <div class="row">
                            <div class="form-group col-md-2 col-sm-6">
                                <label for="bank_account" class="text-dark">Account No:</label>
                                <select name="bank_account" tabindex="4" onchange="searchFilter();" id="bank_account" class="form-control select2">
                                    <option value="">--Select--</option>
                                    <?php
                                    $bank_details = $this->user_model->getResult('bank_details');
                                    if ($bank_details) {
                                        foreach ($bank_details as $key => $value) {
                                    ?>
                                            <!-- <option value="<?= $value->id ?>">
											</option> -->
                                            <option value="<?= $value->id; ?>" <?= $this->input->get('bank_account') == $value->id ? 'selected' : '' ?>><?= $value->name; ?>(<?= $value->account_number ?>)</option>


                                    <?php    }
                                    }
                                    ?>
                                </select>
                            </div>

                            <?php
                            $date = date('Y-m-d');
                            ?>
                            <div class="form-group col-md-2 col-sm-6">
                                <label for="fromDate">From Date</label>
                                <input type="date" tabindex="3" name="fromDate" onchange="searchFilter();" id="fromDate" value="<?= date('Y-m-01') ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-2 col-sm-6">
                                <label for="toDate">To Date</label>
                                <input type="date" tabindex="4" onchange="searchFilter();" name="toDate" id="toDate" value="<?= $date ?>" class="form-control" />
                            </div>
                            <div class="col-md-2">
                                <label for="order">Order</label>
                                <select id="sortBy" class="form-control" onchange="searchFilter();">
                                    <option value="">Sort by Title</option>
                                    <option value="asc">Ascending</option>
                                    <option value="desc">Descending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="Total">Total</label>
                                <select id="pagelength" class="form-control pagelength" onchange="searchFilter();">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="250">500</option>
                                </select>
                            </div>
                            <div class="form-group col-md-1 col-sm-4" style="margin-top:20px">
                                <button type="button" class="btn btn-warning" tabindex="5"><a href="<?= base_url() ?>wallet/sync" class="text-primary">Sync</a></button> &nbsp
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-striped align-items-center table-flush" style="width: 100%;min-height:300px;" id="dataList">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
</div>
<!-- <input type="hidden" id="total_Balance" value="<?= $total_amount_cr - $total_amount_dbt ?>"> -->
<script>

    searchFilter(0);

    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        // var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        var fromDate = $('#fromDate').val();
        var todate = $('#toDate').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('wallet/load_wallet_list/'); ?>' + page_num,
            data: 'page=' + page_num + '&sortBy=' + sortBy + '&pagelength=' + pagelength + '&fromDate=' + fromDate + '&toDate=' + todate,
            success: function(html) {
                $('#dataList').html(html);
            }
        });
    }
</script>
<script>
    function Approve(url, id, user_id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "success",
            cancelButtonColor: "success",
            confirmButtonText: "Yes, Approve it!",
        }).then((result) => {
            if (result.isConfirmed) {
                removeRow(url, id, user_id);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000,
                });
            }
        });
    }
</script>
<script>
    function removeRow(url, id, user_id) {
        var amount = document.getElementById('amount' + id).value;
        console.log(id, "id");
        console.log(amount, "amount");


        $.ajax({
            type: "POST",
            url: url,
            data: {
                amount: amount,
                id: id,
                user_id: user_id,

            },
            success: function(success) {
                success = JSON.parse(success);
                if (success.status == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            },
        });
    }
</script>
<script>
    function OnSubmitHandler(id) {
        var amount = document.getElementById('amount' + id).value;
        var id = document.getElementById('id' + id).value;
        var user_ids = document.getElementById('user_id' + id).value;
        if (amount > 0 && user_ids != '' && id != '') {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>userlist/amount_update",
                data: {
                    amount: amount,
                    user_id: user_ids,
                    id: id,
                },
                success: function(invoiceNo) {
                    invoiceNo = JSON.parse(invoiceNo);
                    console.log(invoiceNo, "dtaaaa");
                    if (invoiceNo.status == true) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Amount Added',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(function() {
                            window.reload();
                        }, 2000)

                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: invoiceNo.msg,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }

                },
                // error: function(jqXHR, exception) {
                // 	$("#loader").addClass("d-none");
                // 	var msg = '';
                // 	if (jqXHR.status === 0) {
                // 		msg = 'Not connect.\n Verify Network.';
                // 	} else if (jqXHR.status == 403) {
                // 		msg = 'Permission denied. [403]';
                // 	} else if (jqXHR.status == 404) {
                // 		msg = 'Requested page not found. [404]';
                // 	} else if (jqXHR.status == 406) {
                // 		msg = jqXHR.responseText;
                // 	} else if (jqXHR.status == 500) {
                // 		msg = 'Internal Server Error [500].';
                // 	} else if (exception === 'parsererror') {
                // 		msg = 'Requested JSON parse failed.';
                // 	} else if (exception === 'timeout') {
                // 		msg = 'Time out error.';
                // 	} else if (exception === 'abort') {
                // 		msg = 'Ajax request aborted.';
                // 	} else {
                // 		msg = 'Uncaught Error.\n';
                // 		console.error(jqXHR.responseText);
                // 	}
                // 	Swal.fire({
                // 		type: 'error',
                // 		title: msg,
                // 	});
                // 	$("#btnSubmit").prop("disabled", false);
                // },
            });
        } else {
            alert("Please Enter Amount");
        }
    }
</script>
<script>
    function onlyNumberKey(evt) {
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        href = "<?= base_url() ?>wallet/wallet_report?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };
</script>