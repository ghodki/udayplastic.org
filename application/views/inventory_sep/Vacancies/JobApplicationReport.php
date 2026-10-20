<style type="text/css">
    th, td {
        white-space: nowrap;
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
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <div class="card card-outline card-olive">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-olive">
                                    Applicants Report
                                </h4>
                            </div>
                            <div class="pull-right">
                                <a href="<?= base_url(); ?>Vacancies" class="btn btn-sm bg-olive" title="Job Application Report">
                                    <i class="fa fa-angle-double-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>Vacancies/JobApplicationReport">
                                <div class="row mb-2">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : date('Y-m-d')); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : date('Y-m-d')); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <select name="status" id="status" class="form-control form-control-sm" title="Status">
                                                <option value="All" <?= $this->input->get('status') == 'All' ? 'selected' : ''; ?>>All</option>
                                                <option value="1" <?= $this->input->get('status') == '2' ? 'selected' : ''; ?>>Reviewed</option>
                                                <option value="2" <?= $this->input->get('status') == '1' ? 'selected' : ''; ?>>Not Reviewed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm bg-olive">
                                                Search
                                            </button>
                                            <a href="<?= base_url(); ?>Vacancies/JobApplicationReport" class="btn btn-danger btn-sm" title="Refresh">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-olive">
                                        <tr>
                                            <th class="text-white">Sr</th>
                                            <th class="text-white">Date</th>
                                            <th class="text-white">Designation</th>
                                            <th class="text-white">Name</th>
                                            <th class="text-white">Mobile</th>
                                            <th class="text-white">Email</th>
                                            <th class="text-white">Status</th>
                                            <th class="text-white">Resume</th>
                                            <th class="text-white"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($Applicants) && !empty($Applicants)) {
                                                $Sr = 0;
                                                foreach ($Applicants as $key => $Data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->Date)); ?></td>
                                            <td><?= get_title('vacancies', ['Id' => $Data->VacancyId], 'Designation'); ?></td>
                                            <td><?= $Data->Name; ?></td>
                                            <td><?= $Data->Mobile; ?></td>
                                            <td><?= $Data->Email; ?></td>
                                            <td>
                                                <?php if ($Data->Status == 1) { ?>
                                                <a href="<?= base_url(); ?>Vacancies/ApplicantStatus/<?= $Data->Id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-envelope-open"></i> Not Reviewed
                                                </a>
                                                <?php } elseif ($Data->Status == 2) { ?>
                                                <a href="<?= base_url(); ?>Vacancies/ApplicantStatus/<?= $Data->Id; ?>" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-check-circle"></i> Reviewed
                                                </a>                                                
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($Data->Ressume)) { ?>
                                                <a href="<?= base_url(); ?>uploads/ressume/<?= $Data->Ressume; ?>" class="btn btn-primary btn-xs" download="">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn bg-olive btn-xs" onclick="getApplicantInfo(<?= $Data->Id; ?>);" title="More Info">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
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
    </div>
</div>

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("table").DataTable({
            dom: "Bfrtip",
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "print", "pageLength"]
        });
    });
    
    const getApplicantInfo = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Vacancies/ApplicantInfo/" + Id);
        $('#Info').modal('show');
    };
</script>