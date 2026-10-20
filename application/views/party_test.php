<!-- <link href="<?= base_url(); ?>assets/css/salecss.css" rel="stylesheet" /> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script> -->
<!-- <script src="jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

<style>
    td.active {
        border: 1px solid blue;
        font-weight: bold;
        color: yellow;
        background-color: red
    }

    td {
        padding: 5px;
        text-align: center
    }

    .form-control {
        width: 100%;
        height: 32px;
    }


    /* .col-sm-3 {
		margin-bottom: -1rem;
	}
	.col-sm-6 {
		margin-bottom: -1rem;
	} */
</style>

<div class="content-wrapper" id="donknow">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row" style="margin-top:34px;">
                <div class="col-lg-12 card">
                    <div class="card-header row">
                        <div class="col-md-2">
                            <h4>Create Sales</h4>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" onchange="changeFormat()" name="formats" id="formats">
                                <!-- <option value="">----</option> -->

                                <option value="Tax" <?= set_value('formats') == 'Tax' ? 'selected' : ''; ?>>Tax
                                </option>
                                <option value="Estimate" <?= set_value('formats') == 'Estimate' ? 'selected' : ''; ?>>
                                    Estimate
                                </option>

                            </select>

                        </div>
                        <div class="col-md-6">

                        </div>

                        <div class="col-md-1">
                            <a class="nav-link" title="Expand" onclick="toggleData()">
                                <i style="margin-left:60px;" class="fas fa-expand"></i>
                            </a>
                        </div>
                        <div class="col-md-1">
                            <a class="nev-link" href="<?= base_url() ?>sale/sale_report" title="Sale List">
                                <i style="margin-top:12px;" class="fas fa-list"></i>

                            </a>
                        </div>
                    </div>

                    <form action="<?= base_url() ?>sale/savesale" novalidate class="form-horizontal" style="margin-left:20px;margin-top:8px" id="dataForm" method="post" onsubmit="onFormSubmitHandler(event)">
                        <div class="row">
                            <div class="col-md-10" id="fulldisplay">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <div class="row mb-1">
                                            <label for="invoiceNumber" class="col-4">Invoice No.</label>
                                            <?php
                                            $user_id = $this->session->userdata('user_id');
                                            $comp_id = $this->session->userdata('comp_id');
                                            $where = ['series.comp_id' => $comp_id, 'series.user_id' => $user_id, 'series.status' => 2];
                                            $series = $this->db->select('*')->from('seriestitle')->join('series', 'seriestitle.id=series.series_type')->like('series', 'sales')->where($where)->get()->row();

                                            if (isset($series) && !empty($series)) {
                                                $ser2 = $this->user_model->getSeris($series);

                                            ?>
                                                <input autocomplete="nope" type="text" readonly class="form-control col-8" required="true" name="invoiceNumber" id="invoiceNumber" tabindex="1" value="<?= $ser2 ?>">
                                                <input autocomplete="nope" type="hidden" class="form-control col-8" required="true" name="series_id" id="series_id" readonly value="<?= $series->series_id; ?>">

                                            <?php } else {
                                                $user_id = $this->session->userdata('user_id');
                                                $comp_id = $this->session->userdata('comp_id');
                                                $where = ['comp_id' => $comp_id, 'user_id' => $user_id,];
                                                // $order=['id'=>'asc'];

                                                $this->db->select_max('unorder_invoice_number');
                                                $this->db->where($where);
                                                $query = $this->db->get('sales')->row();
                                                if ($query) {
                                                    $inital = $query->unorder_invoice_number;
                                                } else {
                                                    $inital = 0;
                                                }

                                            ?>
                                                <input autocomplete="nope" tabindex="1" readonly type="text" class="form-control col-8" required="true" name="invoiceNumber" id="invoiceNumber" value="<?= $inital + 1; ?>">
                                                <input type="hidden" value="" name="series_id">
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            ?>

                                        </div>
                                        <div class="row mb-1">
                                            <label for="invoiceDate" class="col-4">Date</label>
                                            <input type="date" tabindex="2" class="form-control col-8" required="true" name="invoiceDate" id="invoiceDate" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="row mb-1">
                                            <label for="voucherType" class="col-4" title="Voucher Type">Vchr Typ</label>
                                            <select tabindex="3" name="voucherType" id="voucherType" required="true" class="form-control col-8">
                                                <option value="1" <?= set_value('voucherType') == '1' ? 'selected' : ''; ?>>Cash
                                                </option>
                                                <option value="2" <?= set_value('voucherType') == '2' ? 'selected' : ''; ?>>Credit
                                                </option>


                                            </select>
                                        </div>
                                        <div class="mb-1 row">
                                            <label for="taxcal" class="col-4">Tax Cal</label>
                                            <select name="taxcal" tabindex="4" onchange="changeTaxCal()" id="taxcal" required class="form-control  col-8">

                                                <option value="1" <?= set_value('taxcal') == '1' ? 'selected' : ''; ?>>
                                                    Inclusvie</option>
                                                <option value="2" <?= set_value('taxcal') == '2' ? 'selected' : ''; ?>>
                                                    Exclusive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="format_invoice" id="format_invoice" value="Tax">
                                    <div class="col-sm-5">
                                        <div class="row mb-1 ">
                                            <label for="customerId" class="col-3">Party</label>
                                            <div class="col-9 p-0">
                                                <div id="party_idDiv" style="width:93%;float:left">
                                                    <input type="hidden" name="customerId" id="party_id" style="width: 100%;" value="" required="true">
                                                    <input type="text" tabindex="5" placeholder="Party Name" class="form-control col-8 modal-lg" name="partyname" autocomplete="off" id="partyname" style="width: 100%;" value="" required="true">
                                                    <ul class="list-group" id="hidden_data">

                                                    </ul>
                                                </div>
                                                <div class="party_data">
                                                    <!-- <div class="" id="hidden_products" style="display:show;"> -->

                                                    <!-- </div> -->
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
    </div>
</div>

<script>
    $('#partyname').keyup(function() {
        // $('#consigneestatecode').val(code);
        var search = $('#partyname').val();
        if (search.length != '') {
            $.ajax({
                method: "POST",
                // url: baseurl + "get-customer",
                url: baseurl + "index.php/party/searchpartyfor_test",
                data: {
                    name: search,
                },
                success: function(customer) {
                    // alert("searchparty");
                    var customer = JSON.parse(customer);
                    console.log(customer);
                    if (customer.status == true) {
                        document.getElementById('hidden_data').innerHTML = '';
                        $('#hidden_data').show();
                        var da = customer.data;
                        if (da.party) {
                            $.each(da.party, function(i, item) {
                                if (i == 0) {
                                    var dynamic_class = 'active';
                                } else {
                                    var dynamic_class = 'inactive';

                                }

                                $("#hidden_data").append("<li class='list-group-item"++ + dynamic_class +" 'id='party_id" + i + "' onkeydown='changeValue(" + i + ")' onkeypress='getCustomerDetail(" + item.id + "," + i + ")'>" + item.name + "</li>");
                            });
                            document.getElementById('party_id' + 0).focus();
                        } else {
                            $('#hidden_data').hide();

                        }
                    }
                }
            })

        } else {
            $('#hidden_data').hide();
        }

    })
    $(document).ready(function() {
        $('ul li').click(function(e) {


        });
    });

    // function changeValue(id) {
    //     $('li' + id).addClass('active');
    //     alert(id);
    //     // var $parent = $(this).parent();
    //     // $parent.addClass('active');
    //     e.preventDefault();
    // }
    const keyDownForParty = (e, row) => {
        // function keyDownForParty(e, row) {
        //down arrow key
        // alert("yes");

        if (e.keyCode == '40') {

            // $(".list-group-item").removeClass("active");
            // $(".tab").addClass("active"); // instead of this do the below 
            $(e.target.value).addClass("active");
        } else {
           
        }
    }
    
</script>