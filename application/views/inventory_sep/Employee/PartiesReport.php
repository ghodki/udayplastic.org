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
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Parties</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Parties</a></li>
                        <li class="breadcrumb-item active">Party Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="header-title text-success">
                                Parties Report
                            </h5>
                        </div>
                        <?php if (employee_perm($EmployeePermission, 'Parties', 'Create')) { ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-xs pull-right" id="AddParty" title="Create Party">
                            <i class="fa fa-plus-circle text-white"></i> Create
                        </a>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead class="bg-success">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Name</th>
                                            <th class="text-white">Contact</th>
                                            <th class="text-white">GST</th>
                                            <th class="text-white">Address</th>
                                            <th class="text-white">State</th>
                                            <th class="text-white"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (!empty($partiesdata)) {
                                                $n = 0;
                                                foreach ($partiesdata as $key => $data) {
                                                    $n++;
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Employee/PartyInfo/<?= $data->id; ?>" class="text-info" title="View Details">
                                                    <?= $data->name; ?>
                                                </a>
                                            </td>
                                            <td><?= $data->contact; ?></td>
                                            <td><?= $data->gst; ?></td>
                                            <td><?= $data->address_1; ?></td>
                                            <td>
                                                <?= $data->state_code; ?>
                                                -
                                                <?= get_title('state_list', ['id' => $data->state], 'state'); ?>
                                            </td>
                                            <td>
                                                <?php if (employee_perm($EmployeePermission, 'Parties', 'Edit')) { ?>
                                                <a href="javascript:void(0);" class="btn btn-success btn-xs pull-right" title="Edit" onclick="partyEdit(<?= $data->id; ?>);">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <?php } ?>
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
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="AddingParty" tabindex="-1" role="dialog" aria-labelledby="addparty" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
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
    $(function() {
        $(".table").DataTable({
            dom: 'lBfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: [
                {
                    extend : 'copyHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'excelHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'csvHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'pdfHtml5',
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend : 'print',
                    exportOptions:  {
                        columns: []
                    }
                }
            ]
        });
    });
    
    $("#AddParty").click(function() {
        $("#AddingParty .modal-dialog .modal-body").load("<?= base_url(); ?>Employee/CreateParty");
        $("#title").html("Create Party");
        $("#AddingParty").modal("show");
    });
    
    const partyEdit = (PartyId) => {
        jQuery.noConflict();
        $("#AddingParty .modal-dialog .modal-body").load("<?= base_url(); ?>Employee/EditParty/" + PartyId);
        $("#title").html("Update Party");
        $("#AddingParty").modal("show");
    };
    
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
<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>