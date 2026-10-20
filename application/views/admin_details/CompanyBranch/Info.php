<div class="content-wrapper">
        <div class="content-header mt-5">
                <div class="container-fluid">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="card card-outline card-fuchsia">
                                        <div class="card-header">
                                                <div class="card-title">
                                                        <h5 class="header-title text-fuchsia">
                                                                Branch Information
                                                        </h5>
                                                </div>
                                                <a href="<?= base_url(); ?>CompanyBranch" title="Back to Report" class="btn btn-xs bg-fuchsia pull-right">
                                                        <i class="fa fa-angle-double-left text-white"></i> Back
                                                </a>
                                        </div>
                                        <div class="card-body">
                                                <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-sm">
                                                                <tr>
                                                                        <th>Date Of Registration</th>
                                                                        <td><?= !empty($CompanyBranch->Date) ? date('d-m-Y', strtotime($CompanyBranch->Date)) : ""; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Branch Code</th>
                                                                        <td><?= $CompanyBranch->BranchCode; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Branch Name</th>
                                                                        <td><?= $CompanyBranch->BranchName; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Mobile</th>
                                                                        <td><?= $CompanyBranch->Mobile; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Email</th>
                                                                        <td><?= $CompanyBranch->Email; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>GST</th>
                                                                        <td><?= $CompanyBranch->GST; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Contact Person</th>
                                                                        <td><?= $CompanyBranch->ContactPerson; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>State</th>
                                                                        <td><?= get_title('state_list', ['id' => $CompanyBranch->State], 'state'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Address</th>
                                                                        <td><?= $CompanyBranch->Address; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Financial Year</th>
                                                                        <td><?= get_title('year', ['id' => $CompanyBranch->FinancialYear], 'year'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Billing Format</th>
                                                                        <td><?= get_title('billing_format', ['id' => $CompanyBranch->BillingFormat], 'name'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Accounting Type</th>
                                                                        <td><?= $CompanyBranch->AccountingType == 1 ? "Basic" : "Advanced"; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Branch Division</th>
                                                                        <td><?= $CompanyBranch->branchDivision; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Main Branch</th>
                                                                        <td><?= get_title('companybranch', ['Id' => $CompanyBranch->mainBranchId], 'BranchName'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Status</th>
                                                                        <td>
                                                                            <?php if ($CompanyBranch->Status) { ?>
                                                                            <label class="btn btn-success btn-xs">
                                                                                <i class="fa fa-check-circle text-white"></i> Active
                                                                            </label>
                                                                            <?php } else { ?>
                                                                            <label class="btn btn-danger btn-xs">
                                                                                <i class="fa fa-ban text-white"></i> Inactive
                                                                            </label>
                                                                            <?php } ?>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Branch User</th>
                                                                        <td><?= get_title('users', ['id' => $CompanyBranch->BranchUserId], 'name'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Company</th>
                                                                        <td><?= get_title('inventory_billing', ['id' => $CompanyBranch->CompId], 'company_name'); ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Created</th>
                                                                        <td><?= $CompanyBranch->Created; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Created By</th>
                                                                        <td><?= $CompanyBranch->CreatedBy; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Updated</th>
                                                                        <td><?= $CompanyBranch->Updated; ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <th>Updated By</th>
                                                                        <td><?= $CompanyBranch->UpdatedBy; ?></td>
                                                                </tr>
                                                        </table>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>