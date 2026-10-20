<div class="modal-header">
    <h5 class="modal-title">Vacancies Info</h5>
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
                        <td><?= date('d-m-Y', strtotime($VacanciesData->Date)); ?></td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td><?= $VacanciesData->Designation; ?></td>
                    </tr>
                    <tr>
                        <th>Experience</th>
                        <td><?= $VacanciesData->Experience; ?></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td><?= $VacanciesData->Location; ?></td>
                    </tr>
                    <tr>
                        <th>Openings</th>
                        <td><?= $VacanciesData->Openings; ?></td>
                    </tr>
                    <tr>
                        <th>Job Description</th>
                        <td><?= $VacanciesData->JobDescription; ?></td>
                    </tr>
                    <tr>
                        <th>Requirements</th>
                        <td><?= $VacanciesData->Requirements; ?></td>
                    </tr>
                    <tr>
                        <th>Other</th>
                        <td><?= $VacanciesData->Other; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($VacanciesData->Status == 1) { ?>
                            <label class="btn btn-success btn-xs">
                                <i class="fa fa-flag-checkered text-white"></i> Published
                            </label>
                            <?php } elseif ($VacanciesData->Status == 2) { ?>
                            <label class="btn btn-danger btn-xs">
                                <i class="fa fa-ban text-white"></i> Unpublished
                            </label>                                                
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $VacanciesData->Created; ?></td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td><?= $VacanciesData->CreatedBy; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $VacanciesData->Updated; ?></td>
                    </tr>
                    <tr>
                        <th>Updated By</th>
                        <td><?= $VacanciesData->UpdatedBy; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>