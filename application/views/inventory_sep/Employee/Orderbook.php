<style type="text/css">
    .search-list-party-name {
        color: #000;
        list-style: none;
        position: relative;
        left: -40px;
        border-bottom: 0.5px solid #e7e7e7;
        padding-top: 5px;
        width: 300px;
        font-size: 12px;
    }
    .search-list {
        color: #000;
        list-style: none;
        position: relative;
        left: -40px;
        border-bottom: 0.5px solid #e7e7e7;
        padding-top: 5px;
        width: 150px;
        font-size: 12px;
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
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <h3 class="m-0">Orderbook</h3>
                </div><!-- /.col -->
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Voucher</a></li>
                        <li class="breadcrumb-item active">Orderbook</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-navy">
                                    Orderbook Create
                                </h5>
                            </div>
                            <?php if (employee_perm($EmployeePermission, 'Orderbook', 'View')) { ?>
                            <a href="<?= base_url(); ?>Employee/OrderbookReport" class="btn btn-xs bg-navy pull-right" title="Orderbook Report">
                                <i class="fa fa-file text-white"></i> Orderbook Report
                            </a>
                            <?php } ?>
                        </div>
                        <form action="<?= base_url(); ?>Employee/OrderbookSave" method="POST" id="Orderbook">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', date('Y-m-d')); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                            <span class="text-danger"><?= form_error('Date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="PartyId">Party <span class="text-danger">*</span></label>
                                            <select name="PartyId" id="PartyId" class="form-control form-control-sm" required="" title="Party" onchange="SalesmanForOrderbook(this.value);">
                                                <option value="" selected="" disabled="">Select Party</option>
                                                <?php if ($Parties) { foreach ($Parties as $key => $Data) { ?>
                                                <option value="<?= $Data->id; ?>" <?= set_select('PartyId', $Data->id)?>><?= $Data->name; ?>, <?= $Data->area; ?>, <?= $Data->contact; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('PartyId'); ?></span>
                                        </div>
                                    </div>
                                    <?php if (employee_perm($EmployeePermission, 'Parties', 'Create')) { ?>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xl-1 col-xs-1 col-xxl-1">
                                        <div class="form-group">
                                            <label for="VoucherType">Create Party</label>
                                            <a href="javascript:void(0);" class="btn btn-success btn-xs pull-right" id="AddParty" title="Create Party">
                                                <i class="fa fa-plus text-white"></i> Create
                                            </a>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="VoucherType">Voucher Type <span class="text-danger">*</span></label>
                                            <select name="VoucherType" id="VoucherType" class="form-control form-control-sm" required="" title="Voucher Type">
                                                <option value="" selected="" disabled="">Select Voucher</option>
                                                <option value="Sale" <?= set_select('VoucherType', 'Sale'); ?>>Sale</option>
                                                <option value="Estimate" <?= set_select('VoucherType', 'Estimate'); ?>>Estimate</option>
                                            </select>
                                            <span class="text-danger"><?= form_error('VoucherType'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-navy" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm ProductsDropDown">
                                                <thead class="bg-navy" style="position: sticky; top: 0; z-index: 99;">
                                                    <tr>
                                                        <th class="text-white">Sr.</th>
                                                        <th class="text-white" style="width: 45%;">Product Name</th>
                                                        <th class="text-white">Price</th>
                                                        <th class="text-white">Quantity</th>
                                                        <th class="text-white">Total</th>
                                                        <th class="text-white"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4" style="text-align: right;">Sub Total</th>
                                                        <th colspan="2">
                                                            <i class="fa fa-inr"></i> <span id="SubTotalTxt"><?= set_value('SubTotal'); ?></span>
                                                            <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal'); ?>" id="SubTotal" />
                                                            <span class="text-danger"><?= form_error('SubTotal'); ?></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="SalesmanId">Salesman <span class="text-danger">*</span></label>
                                            <select name="SalesmanId" id="SalesmanId" class="form-control form-control-sm" title="Salesman" required="">
                                            </select>
                                            <span class="text-danger"><?= form_error('SalesmanId'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-navy">
                                        Submit
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
 </div>

<div class="modal fade" id="AddingParty" tabindex="-1" role="dialog" aria-labelledby="addparty" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#AddParty").click(function() {
        $("#AddingParty .modal-dialog .modal-body").load("<?= base_url(); ?>Employee/CreateParty");
        $("#title").html("Create Party");
        $("#AddingParty").modal("show");
    });
    
    const suggestPartyName = (PartyName) => {
        if (PartyName == "" || PartyName == null) {
            $('#party_name_list').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyName",
            data    :   {PartyName: PartyName},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list-party-name" onclick="onSelectgetPartyName('${element.name}');">${element.name}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_name_list').html(partyopt);
                                $('#name').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_name_list').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_name_list').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyName = (PartyName) => {
        $('#name').val(PartyName);
        window.setTimeout(function() {
            $('#party_name_list').html('');
        }, 1000);
    };
        
    const getPartyArea = (PartyArea) => {
        if (PartyArea == "" || PartyArea == null) {
            $('#party_area_list').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyArea",
            data    :   {PartyArea: PartyArea},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list" onclick="onSelectgetPartyArea('${element.area}');">${element.area}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_area_list').html(partyopt);
                                $('#area').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_area_list').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_area_list').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyArea = (PartyArea) => {
        $('#area').val(PartyArea);
        window.setTimeout(function() {
            $('#party_area_list').html('');
        }, 1000);
    };
    
    const getPartyCity = (PartyCity) => {
        if (PartyCity == "" || PartyCity == null) {
            $('#party_city_list').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyCity",
            data    :   {PartyCity: PartyCity},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list" onclick="onSelectgetPartyCity('${element.cname}');">${element.cname}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_city_list').html(partyopt);
                                $('#city').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_city_list').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_city_list').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyCity = (PartyCity) => {
        $('#city').val(PartyCity);
        window.setTimeout(function() {
            $('#party_city_list').html('');
        }, 1000);
    };
</script>

<?php include('OrderBookJs.php'); ?>