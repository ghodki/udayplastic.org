<div class="modal-header">
    <h5 class="modal-title">Applicant Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-condensed">
                    <tr>
                        <th>Date</th>
                        <td><?= date('d-m-Y', strtotime($ApplicantData->Date)); ?></td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td><?= get_title('vacancies', ['Id' => $ApplicantData->VacancyId], 'Designation'); ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?= $ApplicantData->Name; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $ApplicantData->Email; ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?= $ApplicantData->Mobile; ?></td>
                    </tr>
                    <tr>
                        <th>Qualification</th>
                        <td><?= $ApplicantData->Qualification; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?= $ApplicantData->Address; ?></td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td><?= get_title('users', ['id' => $ApplicantData->UserId], 'name'); ?></td>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <td><?= get_title('inventory_billing', ['id' => $ApplicantData->CompId], 'company_name'); ?></td>
                    </tr>
                    <tr>
                        <th>Company Branch</th>
                        <td><?= get_title('companybranch', ['Id' => $ApplicantData->CompanyBranchId], 'BranchName'); ?></td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $ApplicantData->Created; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $ApplicantData->Updated; ?></td>
                    </tr>
                    <tr>
                        <th>Updated By</th>
                        <td><?= $ApplicantData->UpdatedBy; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>