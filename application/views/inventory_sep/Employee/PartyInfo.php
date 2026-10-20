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
                        <li class="breadcrumb-item active">Info</li>
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
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="card-title">
                                        Party Information
                                    </h4>
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>Employee/PartiesReport" title="Back to Report" class="btn btn-info btn-xs">
                                            <i class="fa fa-angle-double-left"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-sm">
                                <tr>
                                    <th>Name</th>
                                    <td><?= $partydata->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td><?= $partydata->contact; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= $partydata->email; ?></td>
                                </tr>
                                <tr>
                                    <th>PAN</th>
                                    <td><?= $partydata->pan; ?></td>
                                </tr>
                                <tr>
                                    <th>GST</th>
                                    <td><?= $partydata->gst; ?></td>
                                </tr>
                                <tr>
                                    <th>GST Type</th>
                                    <td><?= $partydata->gstType; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?= $partydata->address_1; ?></td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td><?= get_title('districts', ['id' => $partydata->city], 'name'); ?></td>
                                </tr>
                                <tr>
                                    <th>Pin</th>
                                    <td><?= $partydata->pin; ?></td>
                                </tr>
                                <tr>
                                    <th>State Code</th>
                                    <td><?= $partydata->state_code; ?></td>
                                </tr>
                                <tr>
                                    <th>State</th>
                                    <td><?= get_title('state_list', ['id' => $partydata->state], 'state'); ?></td>
                                </tr>                                
                                <tr>
                                    <th>Opening Balance Type</th>
                                    <td><?= $partydata->opBalnceType; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php if ($partydata->status == 1) { ?>
                                        <label class="btn btn-success btn-xs">
                                            <i class="fa fa-check-circle"></i> Active
                                        </label>
                                        <?php } elseif ($partydata->status == 0) { ?>
                                        <label class="btn btn-danger btn-xs">
                                            <i class="fa fa-ban"></i> Inactive
                                        </label>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td><?= $partydata->added_at; ?></td>
                                </tr>
                                <tr>
                                    <th>Salesman</th>
                                    <td><?= get_title('employees', ['Id' => $partydata->SalesmanId], 'Name'); ?></td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td><?= get_title('users', ['id' => $partydata->user_id], 'name'); ?></td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td><?= get_title('inventory_billing', ['id' => $partydata->comp_id], 'company_name'); ?></td>
                                </tr>
                                <?php if ($this->session->userdata('BranchRole')) { ?>
                                <tr>
                                    <th>Company Branch</th>
                                    <td><?= get_title('companybranch', ['Id' => $partydata->CompanyBranchId], 'BranchName'); ?></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
 </div>