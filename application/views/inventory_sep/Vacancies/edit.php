<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
    .ck-editor__editable_inline {
        min-height: 150px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <form action="<?= base_url(); ?>Vacancies/Update/<?= $VacanciesData->Id; ?>" method="POST">
                        <input type="hidden" name="VacanciesId" value="<?= set_value('VacanciesId', $VacanciesData->Id); ?>" id="VacanciesId" />
                        <div class="card card-outline card-purple">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-purple">
                                        Edit
                                    </h4>
                                </div>
                                <a href="<?= base_url(); ?>Vacancies" class="btn btn-sm bg-purple pull-right" title="Vacancies Report">
                                    <i class="fa fa-table"></i> Vacancies Report
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Designation">Designation <span class="text-danger">*</span></label>
                                            <input type="text" name="Designation" value="<?= set_value('Designation', $VacanciesData->Designation); ?>" id="Designation" class="form-control form-control-sm" placeholder="Designation (Role)" required="" />
                                            <span class="text-danger"><?= form_error('Designation'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Experience">Experience</label>
                                            <select name="Experience" id="Experience" class="form-control form-control-sm" title="Experience">
                                                <option value="" selected="" <?= $VacanciesData->Experience == '' ? 'selected' : ''; ?> disabled="">Select Experience</option>
                                                <option value="Fresher" <?= set_select('Experience', 'Fresher'); ?> <?= $VacanciesData->Experience == 'Fresher' ? 'selected' : ''; ?>>Fresher</option>
                                                <option value="1 Year" <?= set_select('Experience', '1 Year'); ?> <?= $VacanciesData->Experience == '1 Year' ? 'selected' : ''; ?>>1 Year</option>
                                                <option value="2 Year" <?= set_select('Experience', '2 Year'); ?> <?= $VacanciesData->Experience == '2 Year' ? 'selected' : ''; ?>>2 Year</option>
                                                <option value="3 Year" <?= set_select('Experience', '3 Year'); ?> <?= $VacanciesData->Experience == '3 Year' ? 'selected' : ''; ?>>3 Year</option>
                                                <option value="4 Year" <?= set_select('Experience', '4 Year'); ?> <?= $VacanciesData->Experience == '4 Year' ? 'selected' : ''; ?>>4 Year</option>
                                                <option value="5 Year" <?= set_select('Experience', '5 Year'); ?> <?= $VacanciesData->Experience == '5 Year' ? 'selected' : ''; ?>>5 Year</option>
                                                <option value="6 Year" <?= set_select('Experience', '6 Year'); ?> <?= $VacanciesData->Experience == '6 Year' ? 'selected' : ''; ?>>6 Year</option>
                                                <option value="7 Year" <?= set_select('Experience', '7 Year'); ?> <?= $VacanciesData->Experience == '7 Year' ? 'selected' : ''; ?>>7 Year</option>
                                                <option value="8 Year" <?= set_select('Experience', '8 Year'); ?> <?= $VacanciesData->Experience == '8 Year' ? 'selected' : ''; ?>>8 Year</option>
                                                <option value="9 Year" <?= set_select('Experience', '9 Year'); ?> <?= $VacanciesData->Experience == '9 Year' ? 'selected' : ''; ?>>9 Year</option>
                                                <option value="10 Year" <?= set_select('Experience', '10 Year'); ?> <?= $VacanciesData->Experience == '10 Year' ? 'selected' : ''; ?>>10 Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Location">Location</label>
                                            <input type="text" name="Location" value="<?= set_value('Location', $VacanciesData->Location); ?>" id="Location" class="form-control form-control-sm" placeholder="Job Location" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Openings">Openings</label>
                                            <input type="number" name="Openings" value="<?= set_value('Openings', $VacanciesData->Openings); ?>" id="Openings" class="form-control form-control-sm" placeholder="Job Openings" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                                        <div class="form-group">
                                            <label for="JobDescription">Job Description</label>
                                            <textarea name="JobDescription" id="JobDescription" class="form-control form-control-sm" placeholder="Job Description" rows="4"><?= set_value('JobDescription', $VacanciesData->JobDescription); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                                        <div class="form-group">
                                            <label for="Requirements">Requirements</label>
                                            <textarea name="Requirements" id="Requirements" class="form-control form-control-sm" placeholder="Requirements" rows="4"><?= set_value('Requirements', $VacanciesData->Requirements); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                                        <div class="form-group">
                                            <label for="Other">Other</label>
                                            <textarea name="Other" id="Other" class="form-control form-control-sm" placeholder="Other" rows="2"><?= set_value('Other', $VacanciesData->Other); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" class="btn btn-success btn-sm" value="Save Changes" />
                                <a href="<?= base_url(); ?>Vacancies" class="btn btn-danger btn-sm" title="Cancel">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    ClassicEditor.create(document.querySelector('#JobDescription')).then(editor => {
        console.log(editor);
    }).catch(error => {
        console.error(error);
    });
    
    ClassicEditor.create(document.querySelector('#Requirements')).then(editor => {
        console.log(editor);
    }).catch(error => {
        console.error(error);
    });
    
    ClassicEditor.create(document.querySelector('#Other')).then(editor => {
        console.log(editor);
    }).catch(error => {
        console.error(error);
    });
</script>